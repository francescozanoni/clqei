<?php
declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Models\Compilation;
use App\Models\Location;
use App\Models\Ward;
use App\User;
use Auth;

class HomeController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $numberOfCompilations = null;
        if (Auth::user()->can('viewAll', Compilation::class)) {
            $numberOfCompilations = Compilation::count();
        } else {
            $numberOfCompilations = Auth::user()->student->compilations->count();
        }

        return view(
            'home',
            [
                'number_of_compilations' => $numberOfCompilations,
                'number_of_students' => User::students()->count(),
                'number_of_viewers' => User::viewers()->count(),
                'number_of_administrators' => User::administrators()->count(),
                'number_of_locations' => Location::count(),
                'number_of_wards' => Ward::count(),
            ]
        );

    }

}
