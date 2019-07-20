<?php
declare(strict_types = 1);

namespace App\Policies;

use App\Models\Compilation;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompilationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the compilation.
     *
     * @param  \App\User $user
     * @param  \App\Models\Compilation $compilation
     * @return bool
     */
    public function view(User $user, Compilation $compilation) : bool
    {
        // Compilation details can be viewed by administrators, viewers
        // and the student that created the compilation.
        return
            $this->viewAny($user) &&
            ($user->role !== User::ROLE_STUDENT ||
            $user->student->id === $compilation->student->id);
    }

    /**
     * Determine whether the user can create compilations.
     *
     * @param  \App\User $user
     * @return bool
     */
    public function create(User $user) : bool
    {
        return $user->role === User::ROLE_STUDENT;
    }

    /**
     * Determine whether the user can update the compilation.
     *
     * @param  \App\User $user
     * @return bool
     */
    public function update(User $user) : bool
    {
        // Currently no users can update compilations.
        return $user->id < 0;
    }

    /**
     * Determine whether the user can delete the compilation.
     *
     * @param  \App\User $user
     * @return bool
     */
    public function delete(User $user) : bool
    {
        // Currently no users can delete compilations.
        return $user->id < 0;
    }

    /**
     * Determine whether the user can view all compilations.
     *
     * @param  \App\User $user
     * @return bool
     */
    public function viewAll(User $user) : bool
    {
        return $user->role !== User::ROLE_STUDENT;
    }
    
    /**
     * Determine whether the user can view any compilation details.
     *
     * @param  \App\User $user
     * @return bool
     */
    public function viewAny(User $user) : bool
    {
        // Configuration can state viewers cannot view compilation details.
        if (config('clqei.viewers_can_view_compilation_details') === false) {
            return $user->role !== User::ROLE_VIEWER;
        }
        
        return true;
    }
}
