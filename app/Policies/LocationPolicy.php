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
     * @param  \App\Models\Location $location
     * @return bool
     */
    public function view(User $user, Location $location) : bool
    {
        // Locations can be viewed by administrators and viewers.
        return $user->role !== 'student';
    }

    /**
     * Determine whether the user can create locations.
     *
     * @param  \App\User $user
     * @return bool
     */
    public function create(User $user) : bool
    {
        return $user->role !== 'student';
    }

    /**
     * Determine whether the user can update the location.
     *
     * @param  \App\User $user
     * @param  \App\Models\Location $location
     * @return bool
     */
    public function update(User $user, Location $location) : bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the location.
     *
     * @param  \App\User $user
     * @param  \App\Models\Location $location
     * @return bool
     */
    public function delete(User $user, Location $location) : bool
    {
        return false;
    }

}
