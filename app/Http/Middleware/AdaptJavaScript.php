<?php
declare(strict_types = 1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdaptJavaScript
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
         
        $response = $next($request);
        
        // If <old browser> is used, JavaScript is simplified to allow execution.
        // @todo implement browser detection
        if (true) {
            $content = $response->getContent();
            $content = preg_replace('/(?<=\s)let /', 'var ', $content);
            $response->setContent($content);
        }
        
        return $response;
        
    }
    
}
