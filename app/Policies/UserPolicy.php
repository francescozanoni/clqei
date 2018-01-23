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

        // Administrators can view any users.
        if ($user->role === 'administrator') {
            return true;
        }

        // Viewers can view only viewers.
        if ($user->role === 'viewer' &&
            in_array($model->role, ['student', 'viewer']) === true
        ) {
            return true;
        }

        return false;
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
            $user->role === 'viewer' ||
            $user->role === 'administrator';
    }

    /**
     * Determine whether the user can create models with administrator role.
     *
     * @param  \App\User $user
     * @return bool
     */
    public function createAdministrator(User $user) : bool
    {
        return $user->role === 'administrator';
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
        // or be modified by ad administrator.
        return
            $user->role === 'administrator' &&
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

        // Students can be deleted only by viewer and administrators.
        if ($model->role === 'student') {
            return in_array($user->role, ['administrator', 'viewer']);
        }

        // Viewers can be deleted only by administrators.
        if ($model->role === 'viewer') {
            return $user->role === 'administrator';
        }

        // Administrators can be deleted only by other administrators.
        if ($model->role === 'administrator') {
            return
                $user->role === 'administrator' &&
                $user->id !== $model->id;
        }

        return false;

    }
}
