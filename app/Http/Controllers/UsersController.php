<?php
declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Http\Requests\IndexUsersRequest;
use App\User;
use DB;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

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
     * @param IndexUsersRequest $request
     * @return \Illuminate\View\View
     */
    public function index(IndexUsersRequest $request)
    {

        $userRole = $request->get('role');

        switch ($userRole) {
            case User::ROLE_ADMINISTRATOR:
                $users = User::administrators()->get();
                break;
            case User::ROLE_VIEWER:
                $users = User::viewers()->get();
                break;
            case User::ROLE_STUDENT:
                // Students, since can be many, are handled in a different way.
                return $this->indexStudents($request);
            default:
                return view('users.index_no_role');
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
     * Display a listing of the resource (student users), via DataTables.
     *
     * @param IndexUsersRequest $request
     * @return \Illuminate\Http\Response|\Illuminate\View\View
     */
    private function indexStudents(IndexUsersRequest $request)
    {

        if (request()->ajax()) {
            $userQuery = User::students()
                ->with(['student', 'student.compilations'])
                ->select('users.*');
            return DataTables::of($userQuery)
                ->addColumn(
                    'student.number_of_compilations',
                    function ($user) {
                        // A blank space is appended as workaround
                        // to the still-unexplicable error occurring when returning zero.
                        return $user->student->compilations->count() . ' ';
                    }
                )
                ->make(true);
        }

        return view('users.index_students');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User $user
     * @return \Illuminate\View\View
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
     * @param  \App\User $user
     * @return \Illuminate\Http\RedirectResponse
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
     * @param  \Illuminate\Http\Request $request
     * @param  \App\User $user
     * @return \Illuminate\Http\RedirectResponse
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
     * @param  \App\User $user
     * @return \Illuminate\Http\RedirectResponse
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
