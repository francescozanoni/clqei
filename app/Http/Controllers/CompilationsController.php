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
     * Display compilation statistics as charts.
     *
     * @param App\Services\StatisticService $statisticService
     *
     * @return \Illuminate\Http\Response
     */
    public function statisticsCharts(App\Services\StatisticService $statisticService)
    {

        /**
         * @var array e.g. Array (
         *                   [stage_location_id] => Array (
         *                     [14] => 82
         *                     [21] => 11
         *                     [...]
         *                   )
         *                   [stage_ward_id] => Array (
         *                     [65] => 3
         *                     [3] => 7
         *                     [...]
         *                   )
         *                   [...]
         *                 )
         */
        $statistics = $this->getStatistics(request()->all(), $statisticService);

        $sections = Section::with('questions.answers')->get();
        $pseudoSection = new Section();
        $pseudoSection->id = 0;
        $pseudoSection->title = __('Stage');
        $sections->prepend($pseudoSection);

        return view('compilations.statistics_charts', ['statistics' => $statistics, 'sections' => $sections]);
    }

    private function getStatistics(array $requestParameters, App\Services\StatisticService $statisticService) : array
    {

        $query = Compilation
            ::with([
                // Deleted students are included by default via model relationships
                'student',
                'items',
            ]);

        $queryWithFilters = $statisticService->getQueryWithFilters($query, $requestParameters);

        $compilations = $queryWithFilters->get();

        /**
         * @var array e.g. Array (
         *                   Array (
         *                     [stage_location_id] => 14
         *                     [stage_ward_id] => 47
         *                     [stage_academic_year] => 2017/2018
         *                     [stage_weeks] => 10
         *                     [student_gender] => female
         *                     [student_nationality] => IT
         *                     [q1] => 10
         *                     [q2] => 86
         *                     [q3] => 87
         *                     [q4] => 90
         *                     [q5] => 95
         *                     [q6] => 103
         *                     [q7] => 105
         *                     [q9] => 109
         *                     [q10] => 139
         *                     [q11] => 141
         *                     [q13] => 147
         *                     [q14] => 152
         *                     [q15] => 156
         *                     [q16] => 160
         *                     [q17] => 164
         *                     [q18] => 168
         *                     [q19] => 172
         *                     [q20] => 176
         *                     [q21] => 180
         *                     [q22] => 184
         *                     [q23] => 188
         *                     [q24] => 192
         *                     [q25] => 196
         *                     [q26] => 200
         *                     [q27] => 204
         *                     [q28] => 208
         *                     [q29] => 212
         *                     [q30] => 216
         *                     [q31] => 220
         *                     [q32] => 224
         *                     [q33] => 228
         *                     [q34] => 232
         *                     [q35] => 236
         *                   )
         *                   [...]
         *                 )
         */
        $formattedCompilations = $statisticService->formatCompilations($compilations);

        /**
         * @var array e.g. Array (
         *                   [stage_location_id] => Array (
         *                     [14] => 82
         *                     [21] => 11
         *                     [...]
         *                   )
         *                   [stage_ward_id] => Array (
         *                     [65] => 3
         *                     [3] => 7
         *                     [...]
         *                   )
         *                   [...]
         *                 )
         */
        $statistics = $statisticService->getStatistics($formattedCompilations);

        return $statistics;
    }

    /**
     * Display compilation statistics as counts.
     *
     * @param App\Services\StatisticService $statisticService
     *
     * @return \Illuminate\Http\Response
     */
    public function statisticsCounts(App\Services\StatisticService $statisticService)
    {

        /**
         * @var array e.g. Array (
         *                   [stage_location_id] => Array (
         *                     [14] => 82
         *                     [21] => 11
         *                     [...]
         *                   )
         *                   [stage_ward_id] => Array (
         *                     [65] => 3
         *                     [3] => 7
         *                     [...]
         *                   )
         *                   [...]
         *                 )
         */
        $statistics = $this->getStatistics(request()->all(), $statisticService);

        $sections = Section::with('questions.answers')->get();
        $pseudoSection = new Section();
        $pseudoSection->id = 0;
        $pseudoSection->title = __('Stage');
        $sections->prepend($pseudoSection);

        return view('compilations.statistics_counts', ['statistics' => $statistics, 'sections' => $sections]);
    }

}
