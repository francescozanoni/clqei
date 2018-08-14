<?php
declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreLocationRequest;
use App\Models\Location;

class LocationsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $locations = Location::with('compilations')->get()->sortBy('name');
        $deletedLocations = Location::with('compilations')->onlyTrashed()->get();
        return view(
            'locations.index',
            [
                'locations' => $locations,
                'deleted_locations' => $deletedLocations
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreLocationRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreLocationRequest $request)
    {
        $location = new Location;

        $location->name = $request->input('name');
        $location->save();

        return \Redirect::route('locations.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Location $location
     * @return \Illuminate\View\View
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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(StoreLocationRequest $request, Location $location)
    {
        $location->name = $request->input('name');
        $location->save();

        return \Redirect::route('locations.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Location $location
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Location $location)
    {
        $location->delete();

        return \Redirect::route('locations.index');
    }

    /**
     * Restore the specified resource.
     *
     * @param  int $locationId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore(int $locationId)
    {
        $location = Location::onlyTrashed()->where('id', $locationId)->get()->first();
        $location->restore();

        return \Redirect::route('locations.index');
    }

}
