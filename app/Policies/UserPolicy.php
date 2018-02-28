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
        $return = false;

        // Any users can view their own data.
        if ($user->id === $model->id) {
            $return = true;
        } else {

        // Administrators can view any users.
        if ($user->role === User::ROLE_ADMINISTRATOR) {
            $return = true;
        }

        // Viewers can view only viewers.
        if ($user->role === User::ROLE_VIEWER &&
            in_array($model->role, [User::ROLE_STUDENT, User::ROLE_VIEWER]) === true
        ) {
            $return = true;
        }
        
        }

        return $return;
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
     * Determine whether the user can update the model.
     *
     * @param  \App\User $user
     * @param  \App\User $model
     * @return bool
     */
    public function update(User $user, User $model) : bool
    {
        // A user can only update its own user
        // or be modified by an administrator.
        return
            $user->role === User::ROLE_ADMINISTRATOR ||
            $user->id === $model->id;
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

        $return = false;
        
        // Students can be deleted only by viewer and administrators.
        if ($model->role === User::ROLE_STUDENT) {
            $return = in_array($user->role, [User::ROLE_ADMINISTRATOR, User::ROLE_VIEWER]);
        }

        // Viewers can be deleted only by administrators.
        if ($model->role === User::ROLE_VIEWER) {
            $return = $user->role === User::ROLE_ADMINISTRATOR;
        }

        // Administrators can be deleted only by other administrators.
        if ($model->role === User::ROLE_ADMINISTRATOR) {
            $return =
                $user->role === User::ROLE_ADMINISTRATOR &&
                $user->id !== $model->id;
        }

        return $return;

    }
}
