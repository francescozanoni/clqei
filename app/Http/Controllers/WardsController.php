<?php
declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreWardRequest;
use App\Models\Ward;

class WardsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $wards = Ward::with('compilations')->get()->sortBy('name');
        $deletedWards = Ward::with('compilations')->onlyTrashed()->get();
        return view(
            'wards.index',
            [
                'wards' => $wards,
                'deleted_wards' => $deletedWards
            ]
        );
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

        return \Redirect::route('wards.index');
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

        return \Redirect::route('wards.index');
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

        return \Redirect::route('wards.index');
    }

    /**
     * Restore the specified resource.
     *
     * @param  int $wardId
     * @return \Illuminate\Http\Response
     */
    public function restore(int $wardId)
    {
        $ward = Ward::onlyTrashed()->where('id', $wardId)->get()->first();
        $ward->restore();

        return \Redirect::route('wards.index');
    }

}
