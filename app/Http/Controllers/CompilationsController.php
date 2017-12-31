<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCompilationRequest;
use App\Models\Compilation;
use App\Models\CompilationItem;
use App\Models\Section;
use App\Models\Question;
use Illuminate\Http\Request;
use Auth;
use DB;

class CompilationsController extends Controller
{
    /**
     * Display a listing of the compilations.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
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
        
        $compilation->student()->associate($student);
        $compilation->save();
                
        foreach ($request->all() as $key => $value) {
           if (preg_match('/^q\d+$/', $key) === 1 &&
               empty($value) === false) {
              $item = new CompilationItem;
              $item->answer = $value;
              $question = Question::find(substr($key, 1));
              $item->question()->associate($question);
              $item->compilation()->associate($compilation);
              $item->save();
           }
        }
 
        });
        
        // Redirection does not work from within transaction block.
        return \Redirect::route('compilations.show', [$compilation->id])
            ->with('message', 'Your compilation has been created!');
        
    }

    /**
     * Display the specified compilation.
     *
     * @param  \App\Models\Compilation $compilation
     * @return \Illuminate\Http\Response
     */
    public function show(Compilation $compilation)
    {
        return view('compilations.show');
    }

    /**
     * Show the form for editing the specified compilation.
     *
     * @param  \App\Models\Compilation $compilation
     * @return \Illuminate\Http\Response
     */
    public function edit(Compilation $compilation)
    {
        return view('compilations.edit');
    }

    /**
     * Update the specified compilation in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Compilation $compilation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Compilation $compilation)
    {
        //
    }

    /**
     * Remove the specified compilation from storage.
     *
     * @param  \App\Models\Compilation $compilation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Compilation $compilation)
    {
        //
    }
}
