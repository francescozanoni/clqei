<?php
declare(strict_types = 1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\Question;
use App\Events\CompilationCreationAttempted;

class AddMissingQuestions
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
    
        // @todo move this event to a more suitable location
        event(new CompilationCreationAttempted($request->all()));
    
        // When a question could have several answers but none is given,
        // one compilation item is created with NULL answer.
        // This logic is required because HTML array fields (e.g. set of checkboxes),
        // are not sent when no value is selected (even "nullable"
        // validation flag is useless in this case).
        foreach (Question::all() as $question) {
            if ($request->has('q' . $question->id) === false) {
                $request->merge(['q' . $question->id => null]);
            }
        }
            
        return $next($request);
    }
}
