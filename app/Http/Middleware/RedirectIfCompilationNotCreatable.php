<?php
declare(strict_types = 1);

namespace App\Http\Middleware;

use App;
use Closure;

class RedirectIfCompilationNotCreatable
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  string|null $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {

        if (App::make('App\Services\CompilationService')->isCompilationCreatable() === false) {
            return redirect('/home');
        }

        return $next($request);
    }
}
