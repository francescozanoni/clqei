<?php
declare(strict_types = 1);

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class WardPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the ward.
     *
     * @param  \App\User $user
     * @return bool
     */
    public function view(User $user) : bool
    {
        // Wards can be viewed by administrators and viewers.
        return $user->role !== User::ROLE_STUDENT;
    }

    /**
     * Determine whether the user can create wards.
     *
     * @param  \App\User $user
     * @return bool
     */
    public function create(User $user) : bool
    {
        return $user->role !== User::ROLE_STUDENT;
    }

    /**
     * Determine whether the user can update wards.
     *
     * @param  \App\User $user
     * @return bool
     */
    public function update(User $user) : bool
    {
        return $user->role !== User::ROLE_STUDENT;
    }

    /**
     * Determine whether the user can delete the ward.
     *
     * @param  \App\User $user
     * @return bool
     */
    public function delete(User $user) : bool
    {
        return $user->role !== User::ROLE_STUDENT;
    }

}
