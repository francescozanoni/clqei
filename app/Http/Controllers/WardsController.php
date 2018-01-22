<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWardRequest;
use App\Models\Ward;
use Illuminate\Http\Request;

class WardsController extends Controller
{
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\StoreWardRequest  $request
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
     * Display the specified resource.
     *
     * @param  \App\Models\Ward  $ward
     * @return \Illuminate\Http\Response
     */
    public function show(Ward $ward)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Ward  $ward
     * @return \Illuminate\Http\Response
     */
    public function edit(Ward $ward)
    {
        return view('wards.edit', ['ward' => $ward]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\StoreWardRequest  $request
     * @param  \App\Models\Ward  $ward
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
     * @param  \App\Models\Ward  $ward
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ward $ward)
    {
        //
    }
}
