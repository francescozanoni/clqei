<?php
declare(strict_types = 1);

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
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

        if ($request->has('role') === false) {
            return view('users.index_no_role');
        }
        
        $users = new Collection();
        $userRole = $request->get('role');

        switch ($userRole) {
            case User::ROLE_ADMINISTRATOR:
                $this->authorize('createAdministrator', User::class);
                $users = User::administrators()->get();
                break;
            case User::ROLE_VIEWER:
                $this->authorize('createViewer', User::class);
                $users = User::viewers()->get();
                break;
            case User::ROLE_STUDENT:
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

        if ($user->role === User::ROLE_STUDENT) {
            $user->load(User::ROLE_STUDENT);
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

        $this->authorize('delete', $user);

        $userRole = $user->role;

        DB::transaction(function () use ($user) {

            if ($user->role === User::ROLE_STUDENT) {
                $user->student()->delete();
            }
            $user->delete();

        });

        return \Redirect::route('users.index', ['role' => $userRole]);
    }
}
