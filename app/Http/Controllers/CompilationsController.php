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

        // AJAX data call from DataDables.
        if (request()->ajax()) {

            $compilationQuery = Compilation
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
                ])
                ->select('compilations.*');

            if (Auth::user()->cannot('viewAll', Compilation::class)) {
                $compilationQuery
                    ->whereHas('student', function ($query) {
                        $query->where('id', Auth::user()->student->id);
                    });
            }

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

        // Only students can create compilations.
        $this->authorize('create', Compilation::class);

        // Fetch all active sections,
        // with their active questions and active answers.
        $sections = Section::with('questions.answers')->get();

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

            $student = Auth::user()->student;
            $stageLocation = Location::find($request->input('stage_location_id'));
            $stageWard = Ward::find($request->input('stage_ward_id'));
            $stageStartDate = $request->input('stage_start_date');
            $stageEndDate = $request->input('stage_end_date');
            $stageAcademicYear = $request->input('stage_academic_year');

            $compilation->student()->associate($student);
            $compilation->stageLocation()->associate($stageLocation);
            $compilation->stageWard()->associate($stageWard);
            $compilation->stage_start_date = $stageStartDate;
            $compilation->stage_end_date = $stageEndDate;
            $compilation->stage_academic_year = $stageAcademicYear;
            $compilation->save();

            foreach ($request->all() as $key => $value) {
                if (preg_match('/^q\d+$/', $key) === 1) {
                    if (is_array($value) === true) {
                        // When a question has several answers,
                        // one compilation item is created for each answer.
                        foreach ($value as $singleValue) {
                            $item = new CompilationItem;
                            $item->answer = $singleValue;
                            $question = Question::find(substr($key, 1));
                            $item->question()->associate($question);
                            $item->compilation()->associate($compilation);
                            $item->save();
                        }
                    } else {
                        $item = new CompilationItem;
                        $item->answer = $value;
                        $question = Question::find(substr($key, 1));
                        $item->question()->associate($question);
                        $item->compilation()->associate($compilation);
                        $item->save();
                    }
                }
            }

            // When a question could have several answers but none is given,
            // one compilation item is created with NULL answer.
            // This logic is required because HTML array fields (e.g. set of checkboxes),
            // are not sent if no value is selected (even "nullable"
            // validation flag is useless in this case).
            $compilationQuestionIds = collect($compilation->items)->pluck('question')->pluck('id');
            $allCurrentQuestionIds = Question::all()->pluck('id');
            $missingQuestionIds = $allCurrentQuestionIds->diff($compilationQuestionIds)->all();
            foreach ($missingQuestionIds as $missingQuestionId) {
                $item = new CompilationItem;
                $item->answer = null;
                $question = Question::find($missingQuestionId);
                $item->question()->associate($question);
                $item->compilation()->associate($compilation);
                $item->save();
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
