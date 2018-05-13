<?php
declare(strict_types = 1);

namespace App\Listeners;

use App\Events\CompilationCreationAttempted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CompilationCreationAttemptLog
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  CompilationCreationAttempted  $event
     * @return void
     */
    public function handle(CompilationCreationAttempted $event)
    {
        // During test executions, no request is logged.
        if (app('env') === 'testing') {
            return;
        }
        
        file_put_contents(
            storage_path('logs/compilation_attemp_' . date('YmdHis') . '.json'),
            json_encode($event->data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        );
    }
}
