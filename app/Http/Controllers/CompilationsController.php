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
use DB;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class CompilationsController extends Controller
{
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
                ::with('stageLocation')
                ->with('stageWard')
                ->with('student.user')
                ->select('compilations.*');

            if (Auth::user()->cannot('viewAll', Compilation::class)) {
                $compilationQuery
                    ->whereHas('student', function ($query) {
                        $query->where('id', Auth::user()->student->id);
                    });
            }

            return DataTables::of($compilationQuery)->make(true);
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
    public function store(StoreCompilationRequest $request) {

        // http://www.easylaravelbook.com/blog/creating-and-validating-a-laravel-5-form-the-definitive-guide/

        $compilation = new Compilation;

        DB::transaction(function () use ($request, $compilation) {

            $student = Auth::user()->student;
            $stageLocation = Location::find($request->input('stage_location_id'));
            $stageWard = Ward::find($request->input('stage_ward_id'));
            $stageStartDate = $request->input('stage_start_date');
            $stageEndDate = $request->input('stage_end_date');

            $compilation->student()->associate($student);
            $compilation->stageLocation()->associate($stageLocation);
            $compilation->stageWard()->associate($stageWard);
            $compilation->stage_start_date = $stageStartDate;
            $compilation->stage_end_date = $stageEndDate;
            $compilation->save();

            foreach ($request->all() as $key => $value) {
                if (preg_match('/^q\d+$/', $key) === 1) {
                    // When a question has several answers,
                    // one compilation item is created for each answer.
                    if (is_array($value) === true) {
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
    public function show(
        Compilation $compilation
    ) {

        $compilation->load('items');

        return view('compilations.show', ['compilation' => $compilation]);
    }

    /**
     * Show the form for editing the specified compilation.
     *
     * @param  \App\Models\Compilation $compilation
     * @return \Illuminate\Http\Response
     */
    public function edit(
        Compilation $compilation
    ) {
        return view('compilations.edit');
    }

    /**
     * Update the specified compilation in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Compilation $compilation
     * @return \Illuminate\Http\Response
     */
    public function update(
        Request $request,
        Compilation $compilation
    ) {
        //
    }

    /**
     * Remove the specified compilation from storage.
     *
     * @param  \App\Models\Compilation $compilation
     * @return \Illuminate\Http\Response
     */
    public function destroy(
        Compilation $compilation
    ) {
        //
    }
}
