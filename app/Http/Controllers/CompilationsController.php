<?php
declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreCompilationRequest;
use App\Models\Compilation;
use App\Models\CompilationItem;
use App\Models\Location;
use App\Models\Question;
use App\Models\Section;
use App\Models\Ward;
use Auth;
use Carbon\Carbon;
use DB;
use Yajra\DataTables\DataTables;

class CompilationsController extends Controller
{

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        // If compilations cannot be currently created,
        // users are redirected.
        $this->middleware('no_new_compilations')->only('create');
    }

    /**
     * Display a listing of the compilations.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $compilationBaseQuery = Compilation
            // Deleted locations, wards, students and users must be included
            // https://stackoverflow.com/questions/33900124/eloquent-withtrashed-for-soft-deletes-on-eager-loading-query-laravel-5-1
            ::with([
                'stageLocation' => function ($query) {
                    $query->withTrashed();
                },
                'stageWard' => function ($query) {
                    $query->withTrashed();
                },
                'student' => function ($query) {
                    $query->withTrashed();
                },
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

            $compilationQuery = $compilationBaseQuery
                ->select('compilations.*');

            return DataTables::of($compilationQuery)
                ->editColumn('created_at', function ($compilation) {
                    // Date/times must be formatted to simple dates, in order to allow
                    // DataTables plugin (datetimes) correctly format the value.
                    return with(new Carbon($compilation->created_at))->format('Y-m-d');
                })
                ->make(true);
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

        // Only students can create compilations.
        $this->authorize('create', Compilation::class);

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

            $rawCompilationItems = collect($request->all())
                // Only "qN" parameters are considered, to create compilation items.
                ->filter(function ($answers, $questionKey) {
                    return preg_match('/^q\d+$/', $questionKey) === 1;
                })
                // When a question has several answers,
                // one compilation item is created for each answer.
                // For ease of use, all answers are considered as arrays.
                ->map(function ($answers, $questionKey) {
                    return (is_array($answers) === true ? $answers : [$answers]);
                });
            // When a question could have several answers but none is given,
            // one compilation item is created with NULL answer.
            // This logic is required because HTML array fields (e.g. set of checkboxes),
            // are not sent when no value is selected (even "nullable"
            // validation flag is useless in this case).
            foreach (Question::all() as $question) {
                if ($rawCompilationItems->has('q' . $question->id) === false) {
                    $rawCompilationItems->put('q' . $question->id, [null]);
                }
            }

            foreach ($rawCompilationItems as $questionKey => $answers) {
                foreach ($answers as $answer) {
                    $item = new CompilationItem;
                    $item->answer = $answer;
                    $item->question()->associate(Question::find(substr($questionKey, 1)));
                    $item->compilation()->associate($compilation);
                    $item->save();
                }
            }

        });

        // Redirection does not work from within transaction block.
        return \Redirect::route('compilations.show', [$compilation->id])
            ->with('message', __('The new compilation has been created'));

    }

    /**
     * Display the specified compilation.
     *
     * @param  \App\Models\Compilation $compilation
     * @return \Illuminate\Http\Response
     */
    public function show(Compilation $compilation)
    {

        // Deleted students, users, locations and wards must be included
        // https://stackoverflow.com/questions/33900124/eloquent-withtrashed-for-soft-deletes-on-eager-loading-query-laravel-5-1
        $compilation->load([
            'student' => function ($query) {
                $query->withTrashed();
            },
            'student.user' => function ($query) {
                $query->withTrashed();
            },
            'stageLocation' => function ($query) {
                $query->withTrashed();
            },
            'stageWard' => function ($query) {
                $query->withTrashed();
            }
        ]);

        $this->authorize('view', $compilation);

        return view('compilations.show', ['compilation' => $compilation]);
    }

}
