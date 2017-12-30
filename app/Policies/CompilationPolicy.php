<?php

namespace App\Policies;

use App\User;
use App\AppModelsCompilation;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompilationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the appModelsCompilation.
     *
     * @param  \App\User  $user
     * @param  \App\AppModelsCompilation  $appModelsCompilation
     * @return mixed
     */
    public function view(User $user, AppModelsCompilation $appModelsCompilation)
    {
        //
    }

    /**
     * Determine whether the user can create appModelsCompilations.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the appModelsCompilation.
     *
     * @param  \App\User  $user
     * @param  \App\AppModelsCompilation  $appModelsCompilation
     * @return mixed
     */
    public function update(User $user, AppModelsCompilation $appModelsCompilation)
    {
        //
    }

    /**
     * Determine whether the user can delete the appModelsCompilation.
     *
     * @param  \App\User  $user
     * @param  \App\AppModelsCompilation  $appModelsCompilation
     * @return mixed
     */
    public function delete(User $user, AppModelsCompilation $appModelsCompilation)
    {
        //
    }
}
