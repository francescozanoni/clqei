<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\IndexUsersRequest;
use App\Http\Requests\StoreUserRequest;
use App\User;
use DB;
use Yajra\DataTables\DataTables;

class UsersController extends Controller
{

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware("not_student")->only("index");
    }

    /**
     * Display a listing of the resource.
     *
     * @param IndexUsersRequest $request
     *
     * @return \Illuminate\Http\Response|\Illuminate\View\View
     *
     * @throws \Exception
     */
    public function index(IndexUsersRequest $request)
    {

        $userRole = $request->get("role");

        switch ($userRole) {
            case User::ROLE_ADMINISTRATOR:
                $users = User::administrators()->get();
                break;
            case User::ROLE_VIEWER:
                $users = User::viewers()->get();
                break;
            case User::ROLE_STUDENT:
                // Students, since can be many, are handled in a different way.
                return $this->indexStudents();
            default:
                return view("users.index_no_role");
        }

        return view(
            "users.index",
            [
                "users" => $users,
                "user_role" => $userRole
            ]
        );
    }

    /**
     * Display a listing of the resource (student users), via DataTables.
     *
     * @return \Illuminate\Http\Response|\Illuminate\View\View
     *
     * @throws \Exception
     */
    private function indexStudents()
    {

        if (request()->ajax()) {
            $userQuery = User::students()
                ->with(["student", "student.compilations"])
                ->select("users.*");
            return DataTables::of($userQuery)
                ->addColumn(
                    "student.number_of_compilations",
                    function ($user) {
                        // A blank space is appended as workaround
                        // to the still-unexplicable error occurring when returning zero.
                        return $user->student->compilations->count() . " ";
                    }
                )
                ->make(true);
        }

        return view("users.index_students");

    }

    /**
     * Display the specified resource.
     *
     * @param \App\User $user
     *
     * @return \Illuminate\View\View
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(User $user)
    {
        $this->authorize("view", $user);

        if ($user->role === User::ROLE_STUDENT) {
            $user->load(User::ROLE_STUDENT);
        }

        return view(
            "users.show",
            ["user" => $user]
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\User $user
     *
     * @return \Illuminate\View\View
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(User $user)
    {
        $this->authorize("update", $user);

        return view(
            "users.edit",
            ["user" => $user]
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\StoreUserRequest $request
     * @param \App\User $user
     *
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(StoreUserRequest $request, User $user)
    {
        $this->authorize("update", $user);

        $user->first_name = $request->input("first_name");
        $user->last_name = $request->input("last_name");
        $user->email = $request->input("email");
        $user->role = $request->input("role");
        $user->save();

        return \Redirect::route("users.index", ["role" => $user->role]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\User $user
     *
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(User $user)
    {

        $this->authorize("delete", $user);

        $userRole = $user->role;

        DB::transaction(function () use ($user) {

            if ($user->role === User::ROLE_STUDENT) {
                $user->student()->delete();
            }
            $user->delete();

        });

        return \Redirect::route("users.index", ["role" => $userRole]);
    }

}
