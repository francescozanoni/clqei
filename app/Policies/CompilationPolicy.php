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
    public function view(User $user, Compilation $compilation)
    {
        // Compilations can be viewed by administrators, viewers
        // and the student that created the compilation.
        return $user->role !== 'student' ||
        $user->student->id === $compilation->student->id;
    }

    /**
     * Determine whether the user can create compilations.
     *
     * @param  \App\User $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->role === 'student';
    }

    /**
     * Determine whether the user can update the compilation.
     *
     * @param  \App\User $user
     * @param  \App\Models\Compilation $compilation
     * @return bool
     */
    public function update(User $user, Compilation $compilation)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the compilation.
     *
     * @param  \App\User $user
     * @param  \App\Models\Compilation $compilation
     * @return bool
     */
    public function delete(User $user, Compilation $compilation)
    {
        return false;
    }

    /**
     * Determine whether the user can view all compilations.
     *
     * @param  \App\User $user
     * @return bool
     */
    public function viewAll(User $user)
    {
        return $user->role !== 'student';
    }
}
