<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCompilationRequest;
use App\Models\Compilation;
use App\Models\Section;
use Illuminate\Http\Request;

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
        // Fetch all active sections,
        // with their questions and answers.
        // @todo filter active questions and active answers
        $sections = Section::where('active', true)
            ->with('questions.answers')
            ->get();

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
         *     [_token] => 0gEJv4qWJP2NsUpa9Ed0PuQHspcXzqW2Jk8XviIb
         *     [q1] => 1
         *     [q2] => 12
         *     [q3] => 86
         *     [q4] => 88
         *     [q5] => 91
         *     [q6] => asdfafa
         *     [q7] => asdfasdf
         *     [q8] => 102
         *     [q9] => 107
         *     [q10] => 116
         *     [q11] => 121
         *     [q12] => 123
         *     [q13] => dsfsfdgds
         *     [q14] => 127
         *     [q15] => 137
         *     [q16] => 160
         *     [q17] => adfgdsf
         *     [q18] => sdfgsdfg
         *     [q19] => 163
         *     [q20] => 167
         *     [q21] => 171
         *     [q22] => 175
         *     [q23] => 179
         *     [q24] => 183
         *     [q25] => 187
         *     [q26] => 191
         *     [q27] => 195
         *     [q28] => 199
         *     [q29] => 203
         *     [q30] => 207
         *     [q31] => 211
         *     [q32] => 215
         *     [q33] => 219
         *     [q34] => 223
         *     [q35] => 227
         *     [q36] => 231
         *     [q37] => 235
         *     [q38] => 239
         *     [q39] => 243
         *     [q40] => 247
         *     [q41] => 251
         * )
         */
        // print_r($request->all());exit;

        /* http://www.easylaravelbook.com/blog/creating-and-validating-a-laravel-5-form-the-definitive-guide/
        $category = new Category;
        $category->name = $request->get('name');
        $category->save();
        return \Redirect::route('categories.show',
            array($category->id))
            ->with('message', 'Your category has been created!');
        */
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
