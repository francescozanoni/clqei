<?php
declare(strict_types = 1);

namespace App\Http\Middleware;

use App;
use Closure;

class RedirectIfCompilationNotCreatable
{

    /**
     * @var App\Services\CompilationService
     */
    private $compilationService;

    /**
     * @param App\Services\CompilationService $compilationService
     */
    public function __construct(App\Services\CompilationService $compilationService)
    {
        $this->compilationService = $compilationService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if ($this->compilationService->isCompilationCreatable() === false) {
            return redirect("/home");
        }

        return $next($request);
    }
}
