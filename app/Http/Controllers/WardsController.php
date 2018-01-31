<?php
declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreWardRequest;
use App\Models\Ward;

class WardsController extends Controller
{

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('not_student');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $wards = Ward::all();
        return view('wards.index', ['wards' => $wards]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreWardRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreWardRequest $request)
    {
        $ward = new Ward;

        $ward->name = $request->input('name');
        $ward->save();

        return \Redirect::route('wards.index')
            ->with('message', __('The new ward has been created'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Ward $ward
     * @return \Illuminate\Http\Response
     */
    public function edit(Ward $ward)
    {
        return view('wards.edit', ['ward' => $ward]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\StoreWardRequest $request
     * @param  \App\Models\Ward $ward
     * @return \Illuminate\Http\Response
     */
    public function update(StoreWardRequest $request, Ward $ward)
    {
        $ward->name = $request->input('name');
        $ward->save();

        return \Redirect::route('wards.index')
            ->with('message', __('The ward has been updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ward $ward
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ward $ward)
    {
        $ward->delete();

        return \Redirect::route('wards.index')
            ->with('message', __('The ward has been deleted'));
    }
}
