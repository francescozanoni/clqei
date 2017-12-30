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
        /* Example request data:
         * Array (
         [_token] => K5Q5fLi5jfLfiqmQE1zwHJ7qNTH3T8HiZX3Ht1ct
         [student_id] => 1
         [q1] => 6
         [q2] => 84
         [q3] => 87
         [q4] => 89
         [q5] => 90
         [q6] => 94
         [q7] => 106
         [q8] => 110
         [q9] => 121
         [q11] => 
         [q12] => 127
         [q13] => 136
         [q14] => 159
         [q15] => 
         [q16] => 162
         [q17] => 166
         [q18] => 171
         [q19] => 175
         [q20] => 180
         [q21] => 184
         [q22] => 187
         [q23] => 190
         [q24] => 196
         [q25] => 199
         [q26] => 203
         [q27] => 208
         [q28] => 211
         [q29] => 214
         [q30] => 219
         [q31] => 223
         [q32] => 227
         [q33] => 231
         [q34] => 235
         [q35] => 239
         [q36] => 243
         [q37] => 248
         [q38] => 251
         )
         */

        // http://www.easylaravelbook.com/blog/creating-and-validating-a-laravel-5-form-the-definitive-guide/
        
        DB::transaction(function () use ($request) {
 
        $student = Auth::user()->student;
        
        $compilation = new Compilation;
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
        
        // @todo check why redirection towards compilations index page
        return \Redirect::route('compilations.show', [$compilation->id])
            ->with('message', 'Your compilation has been created!');
 
        });
        
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
