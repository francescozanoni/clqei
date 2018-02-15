<?php
declare(strict_types = 1);

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use DB;

class UsersController extends Controller
{

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('not_student')->only('index');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $users = null;
        $userRole =$request->get('role');

        switch ($userRole) {
            case 'administrator':
                $this->authorize('createAdministrator', User::class);
                $users = User::administrators()->get();
                break;
            case 'viewer':
                $this->authorize('createViewer', User::class);
                $users = User::viewers()->get();
                break;
            case 'student':
                $this->authorize('createViewer', User::class);
                $users = User::students()->with('student')->get();
                break;
            default:
                $userRole = null;
        }

        return view(
            'users.index',
            [
                'users' => $users,
                'user_role' => $userRole
            ]
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $this->authorize('view', $user);

        if ($user->role === 'student') {
            $user->load('student');
        }

        return view(
            'users.show',
            ['user' => $user]
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $this->authorize('update', $user);

        // @todo implement edit logic
        return redirect(route('home'));
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
        $this->authorize('update', $user);

        // @todo implement edit logic
        return redirect(route('home'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {

        $this->authorize('destroy', $user);

        $userRole = $user->role;

        DB::transaction(function () use ($user) {

            if ($user->role === 'student') {
                $user->student()->delete();
            }
            $user->delete();

        });

        return \Redirect::route('users.index', ['role' => $userRole])
            ->with('message', __('The ' . $userRole . ' has been deleted'));
    }
}
