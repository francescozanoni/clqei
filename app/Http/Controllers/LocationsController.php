<?php
declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreLocationRequest;
use App\Models\Location;

class LocationsController extends Controller
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
        $locations = Location::all();
        return view('locations.index', ['locations' => $locations]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreLocationRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLocationRequest $request)
    {
        $location = new Location;

        $location->name = $request->input('name');
        $location->save();

        return \Redirect::route('locations.index')
            ->with('message', __('The new location has been created'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Location $location
     * @return \Illuminate\Http\Response
     */
    public function edit(Location $location)
    {
        return view('locations.edit', ['location' => $location]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\StoreLocationRequest $request
     * @param  \App\Models\Location $location
     * @return \Illuminate\Http\Response
     */
    public function update(StoreLocationRequest $request, Location $location)
    {
        $location->name = $request->input('name');
        $location->save();

        return \Redirect::route('locations.index')
            ->with('message', __('The location has been updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Location $location
     * @return \Illuminate\Http\Response
     */
    public function destroy(Location $location)
    {
        $location->delete();

        return \Redirect::route('locations.index')
            ->with('message', __('The location has been deleted'));
    }
}
