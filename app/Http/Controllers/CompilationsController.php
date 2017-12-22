<?php

namespace App\Http\Controllers;

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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
