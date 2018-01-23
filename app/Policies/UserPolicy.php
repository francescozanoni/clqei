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
        // A user can view itself and administrators can view any users.
        if ($user->id === $model->id ||
            $user->role === 'administrator'
        ) {
            return true;
        }

        // Viewers can view only viewers.
        if ($user->role === $model->role &&
            $user->role === 'viewer'
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
        // A user can only update its own same user.
        return $user->id === $model->id;
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
        // Only administrators can delete users, but not themselves.
        return
            $user->role === 'administrator' &&
            $user->id === $model->id;
    }
}
