<?php

namespace App\Policies;

use App\User;
use App\Models\Compilation;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompilationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the compilation.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Compilation  $compilation
     * @return bool
     */
    public function view(User $user, Compilation $compilation)
    {
        // Compilations can be viewed by administrators, viewers
        // and the student that created the compilation.
        return $user->role !== 'student' ||
            $user->student->id === $compilation->student->id;
    }

    /**
     * Determine whether the user can create appModelsCompilations.
     *
     * @param  \App\User  $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->role === 'student';
    }

    /**
     * Determine whether the user can update the compilation.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Compilation  $compilation
     * @return bool
     */
    public function update(User $user, Compilation $compilation)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the compilation.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Compilation  $compilation
     * @return bool
     */
    public function delete(User $user, Compilation $compilation)
    {
        return false;
    }
}
