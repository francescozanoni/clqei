<?php
declare(strict_types = 1);

namespace App\Policies;

use App\Models\Location;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LocationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the location.
     *
     * @param  \App\User $user
     * @return bool
     */
    public function view(User $user) : bool
    {
        // Locations can be viewed by administrators and viewers.
        return $user->role !== User::ROLE_STUDENT;
    }

    /**
     * Determine whether the user can create locations.
     *
     * @param  \App\User $user
     * @return bool
     */
    public function create(User $user) : bool
    {
        return $user->role !== User::ROLE_STUDENT;
    }

    /**
     * Determine whether the user can update locations.
     *
     * @param  \App\User $user
     * @return bool
     */
    public function update(User $user) : bool
    {
        return $user->role !== User::ROLE_STUDENT;
    }

    /**
     * Determine whether the user can delete the location.
     *
     * @param  \App\User $user
     * @return bool
     */
    public function delete(User $user) : bool
    {
        return $user->role !== User::ROLE_STUDENT;
    }

}
