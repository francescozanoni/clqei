<?php
declare(strict_types = 1);

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User $user
     * @param  \App\User $model
     * @return bool
     */
    public function view(User $user, User $model) : bool
    {
        // Any users can view their own data.
        if ($user->id === $model->id) {
            return true;
        }

        $return = null;

        switch ($user->role) {

            case User::ROLE_ADMINISTRATOR;
                // Administrators can view any users.
                $return = true;
                break;

            case User::ROLE_VIEWER;
                // Viewers can view viewers and students.
                $return = in_array($model->role, [User::ROLE_STUDENT, User::ROLE_VIEWER]);
                break;

            default:
                $return = false;

        }

        return $return;
    }

    /**
     * Determine whether the user can view all models with student role.
     *
     * @param  \App\User $user
     * @return bool
     */
    public function viewStudents(User $user) : bool
    {
        return
            $user->role === User::ROLE_VIEWER ||
            $user->role === User::ROLE_ADMINISTRATOR;
    }

    /**
     * Determine whether the user can create models with viewer role.
     *
     * @param  \App\User $user
     * @return bool
     */
    public function createViewer(User $user) : bool
    {
        return
            $user->role === User::ROLE_VIEWER ||
            $user->role === User::ROLE_ADMINISTRATOR;
    }

    /**
     * Determine whether the user can view all models with viewer role.
     *
     * @param  \App\User $user
     * @return bool
     */
    public function viewViewers(User $user) : bool
    {
        return
            $user->role === User::ROLE_VIEWER ||
            $user->role === User::ROLE_ADMINISTRATOR;
    }

    /**
     * Determine whether the user can create models with administrator role.
     *
     * @param  \App\User $user
     * @return bool
     */
    public function createAdministrator(User $user) : bool
    {
        return $user->role === User::ROLE_ADMINISTRATOR;
    }

    /**
     * Determine whether the user can view all models with administrator role.
     *
     * @param  \App\User $user
     * @return bool
     */
    public function viewAdministrators(User $user) : bool
    {
        return $user->role === User::ROLE_ADMINISTRATOR;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User $user
     * @param  \App\User $model
     * @return bool
     */
    public function update(User $user, User $model) : bool
    {
        // A user can only update its own user (if it's not a student)
        // or be modified by an administrator.
        return
            $user->role === User::ROLE_ADMINISTRATOR ||
            ($user->id === $model->id && $user->role !== User::ROLE_STUDENT);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User $user
     * @param  \App\User $model
     * @return bool
     */
    public function delete(User $user, User $model) : bool
    {

        $return = null;

        switch ($model->role) {

            case User::ROLE_ADMINISTRATOR;
                // Administrators can be deleted only by other administrators.
                $return =
                    $user->role === User::ROLE_ADMINISTRATOR &&
                    $user->id !== $model->id;
                break;

            case User::ROLE_VIEWER;
                // Viewers can be deleted only by administrators.
                $return = $user->role === User::ROLE_ADMINISTRATOR;
                break;

            case User::ROLE_STUDENT;
                // Students can be deleted only by viewer and administrators.
                $return = in_array($user->role, [User::ROLE_ADMINISTRATOR, User::ROLE_VIEWER]);
                break;

            default:
                $return = false;

        }

        return $return;

    }
}
