<?php
declare(strict_types = 1);

namespace App\Http\Controllers;

use App;
use App\Http\Requests\StoreCompilationRequest;
use App\Models\Compilation;
use App\Models\CompilationItem;
use App\Models\Location;
use App\Models\Question;
use App\Models\Section;
use App\Models\Ward;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class CompilationsController extends Controller
{

    /**
     * @var App\Services\CompilationService
     */
    private $compilationService;

    /**
     * Create a new controller instance.
     *
     * @param App\Services\CompilationService $compilationService
     */
    public function __construct(App\Services\CompilationService $compilationService)
    {
        // If compilations cannot be currently created,
        // users are redirected.
        $this->middleware('no_new_compilations')->only('create');

        $this->compilationService = $compilationService;
    }

    /**
     * Display a listing of the compilations.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $compilationBaseQuery = Compilation
            ::with([
                // Deleted locations, wards and students are included by default via model relationships
                'stageLocation',
                'stageWard',
                'student',
                // Deleted users must be included
                // https://stackoverflow.com/questions/33900124/eloquent-withtrashed-for-soft-deletes-on-eager-loading-query-laravel-5-1
                'student.user' => function ($query) {
                    $query->withTrashed();
                }
            ]);

        // Student view of compilation list: no DataTables
        if (Auth::user()->cannot('viewAll', Compilation::class)) {
            $compilations = $compilationBaseQuery
                ->whereHas('student', function ($query) {
                    $query->where('id', Auth::user()->student->id);
                })
                ->get();
            return view('compilations.index_student', ['compilations' => $compilations]);
        }


        // Non-student view of compilation list: DataTables-based

        // AJAX data call from DataTables.
        if (request()->ajax()) {

            $compilationQuery = $compilationBaseQuery->select('compilations.*');

            $toReturn = DataTables::of($compilationQuery)
                ->editColumn('created_at', function ($compilation) {
                    // Date/times must be formatted to simple dates, in order to allow
                    // DataTables plugin (datetimes) correctly format the value.
                    return with(new Carbon($compilation->created_at))->format('Y-m-d');
                });

            // @todo refactor by extracting all order parameter usage and sanitization logic
            $order = request()->get('order')[0];
            $order['column'] = (int)$order['column'];
            $order['dir'] = in_array($order['dir'], ['asc', 'desc']) ? $order['dir'] : 'asc';
            // Since stage weeks are computed on-the-fly,
            // sorting by that column requires a different logic.
            if (request()->input('columns')[$order['column']]['data'] === 'stage_weeks') {
                $toReturn->order(function ($query) use ($order) {
                    // @todo check whether this SQL string is compatible with other database engines
                    $query->orderByRaw(
                        '(strftime("%J", stage_end_date) - strftime("%J", stage_start_date)) ' . $order['dir']
                    );
                });
            }

            return $toReturn->make(true);

        }

        return view('compilations.index');
    }

    /**
     * Show the form for creating a new compilation.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        // Fetch all active sections,
        // with their active questions and active answers.
        $sections = Section::with('questions.answers')->get();

        // @todo sort sections and questions by position

        return view('compilations.create', ['sections' => $sections]);
    }

    /**
     * Store a newly created compilation in storage.
     *
     * @param  StoreCompilationRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCompilationRequest $request)
    {

        // http://www.easylaravelbook.com/blog/creating-and-validating-a-laravel-5-form-the-definitive-guide/

        $compilation = new Compilation;

        DB::transaction(function () use ($request, $compilation) {

            $compilation->student()->associate(Auth::user()->student);
            $compilation->stageLocation()->associate(Location::find($request->input('stage_location_id')));
            $compilation->stageWard()->associate(Ward::find($request->input('stage_ward_id')));
            $compilation->stage_start_date = $request->input('stage_start_date');
            $compilation->stage_end_date = $request->input('stage_end_date');
            $compilation->stage_academic_year = $request->input('stage_academic_year');
            $compilation->save();

            collect($request->all())
                // Only "qN" parameters are considered, to create compilation items.
                ->filter(function ($answers, $questionKey) {
                    return preg_match('/^q\d+$/', $questionKey) === 1;
                })
                // When a question has several answers,
                // one compilation item is created for each answer.
                // For code shortness, all answers are considered as arrays.
                ->map(function ($answers, $questionKey) use ($compilation) {

                    $answers = (is_array($answers) === true ? $answers : [$answers]);

                    foreach ($answers as $answer) {
                        $item = new CompilationItem;
                        $item->answer = $answer;
                        $item->question()->associate(Question::find(substr($questionKey, 1)));
                        $item->compilation()->associate($compilation);
                        $item->save();
                    }

                });

        });

        // Redirection does not work from within transaction block.
        return \Redirect::route('compilations.show', [$compilation->id]);

    }

    /**
     * Display the specified compilation.
     *
     * @param  \App\Models\Compilation $compilation
     * @return \Illuminate\Http\Response
     */
    public function show(Compilation $compilation)
    {

        $compilation->load([
            // Deleted locations, wards and students are included by default via model relationships
            'stageLocation',
            'stageWard',
            'student',
            // Deleted users must be included
            // https://stackoverflow.com/questions/33900124/eloquent-withtrashed-for-soft-deletes-on-eager-loading-query-laravel-5-1
            'student.user' => function ($query) {
                $query->withTrashed();
            },
            'items',
            'items.aanswer',
            // "aanswer" is not a mistake
            'items.question',
            'items.question.section',
        ]);

        $this->authorize('view', $compilation);

        // When request contains the "receipt" parameter, the compilation's receipt view is rendered.
        if (request()->has('receipt')) {
            return view('compilations.receipt', ['compilation' => $compilation]);
        }

        return view('compilations.show', ['compilation' => $compilation]);
    }

    /**
     * Display compilation statistics.
     *
     * @param App\Services\StatisticService $statisticService
     *
     * @return \Illuminate\Http\Response
     */
    public function statistics(App\Services\StatisticService $statisticService)
    {

        $query = Compilation
            ::with([
                // Deleted students are included by default via model relationships
                'student',
                'items',
            ]);

        if (request()->has('stage_location_id') === true) {
            $query->where('stage_location_id', request()->get('stage_location_id'));
        }
        if (request()->has('stage_ward_id') === true) {
            $query->where('stage_ward_id', request()->get('stage_ward_id'));
        }
        if (request()->has('stage_academic_year') === true) {
            $query->where('stage_academic_year', request()->get('stage_academic_year'));
        }
        if (request()->has('stage_weeks') === true) {
            // @todo implement this logic
            // $query->where('stage_weeks', request()->get('stage_weeks'));
        }
        if (request()->has('student_gender') === true) {
            $studentGender = request()->get('student_gender');
            $query->whereHas('student', function ($query) use ($studentGender) {
                $query->where('gender', $studentGender);
            });
        }
        if (request()->has('student_nationality') === true) {
            $studentNationality = request()->get('student_nationality');
            $query->whereHas('student', function ($query) use ($studentNationality) {
                $query->where('nationality', $studentNationality);
            });
        }

        $compilations = $query->get();

        $formattedCompilations = $statisticService->formatCompilations($compilations);
        $statistics = $statisticService->getStatistics($formattedCompilations);

        return view('compilations.statistics', ['statistics' => $statistics]);
    }

}
