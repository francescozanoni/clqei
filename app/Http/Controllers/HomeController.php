<?php
declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Models\Compilation;
use App\Models\Location;
use App\Models\Ward;
use App\User;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    
        $data = [
            'number_of_locations' => Location::count(),
            'number_of_wards' => Ward::count(),
            'number_of_compilations' => Compilation::count(),
        ];

        if (Auth::user()->cannot('viewAll', Compilation::class)) {
            // If the current user cannot view all compilations,
            // it's automatically a student.
            $data['number_of_compilations'] = Auth::user()->student->compilations->count();
        }
        
        if (Auth::user()->can('viewStudents', User::class)) {
            $data['number_of_students'] = User::students()->count();
        }
        
        if (Auth::user()->can('viewViewers', User::class)) {
            $data['number_of_viewers'] = User::viewers()->count();
        }
        
        if (Auth::user()->can('viewAdministrators', User::class)) {
            $data['number_of_administrators'] = User::administrators()->count();
        }

        return view('home', $data);

    }

}
