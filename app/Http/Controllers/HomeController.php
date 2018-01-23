<?php
declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Models\Compilation;
use App\User;
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

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
            ]
        );
    }
}
