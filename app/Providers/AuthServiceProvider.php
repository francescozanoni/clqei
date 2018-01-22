<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Models\Compilation' => 'App\Policies\CompilationPolicy',
        'App\User' => 'App\Policies\UserPolicy',
        'App\Models\Location' => 'App\Policies\LocationPolicy',
        'App\Models\Ward' => 'App\Policies\WardPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
