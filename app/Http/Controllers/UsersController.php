<?php
declare(strict_types = 1);

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        // @todo add authorization filter (now enabled only on single models, on the view)

        $users = null;
        $viewPanelTitle = null;

        switch ($request->get('role')) {
            case 'administrator':
                $users = User::administrators();
                $viewPanelTitle = 'Administrators';
                break;
            case 'viewer':
                $users = User::viewers();
                $viewPanelTitle = 'Viewers';
                break;
            case 'student':
                $users = User::students();
                $viewPanelTitle = 'Students';
                break;
            default:
        }

        return view(
            'users.index',
            [
                'users' => $users->get(),
                'panel_title' => $viewPanelTitle
            ]
        );
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
