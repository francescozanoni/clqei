<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return bool
     */
    public function view(User $user, User $model)
    {
        // A user can view itself and administrators can view any users.
        if ($user->id === $model->id ||
            $user->role === 'administrator') {
            return true;
        }
        
        // Viewers can view only viewers.
        if ($user->role === $model->role &&
            $user->role === 'viewer') {
            return true;
        }
        
        return false;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return bool
     */
    public function create(User $user)
    {
        // @todo check how to filter the possibility of creating only viewers.
        return $user->role === 'viewer' ||
            $user->role === 'administrator';
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return bool
     */
    public function update(User $user, User $model)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return bool
     */
    public function delete(User $user, User $model)
    {
        //
    }
}
