<?php
declare(strict_types = 1);

namespace App\Console\Commands\Interfaces;

interface WithIntegerExitCode
{
    
    const SUCCESS = 0;
    const INVALID_INPUT = 1;
    const UNEXPECTED_ERROR = 2;

    /**
     * Execute the console command.
     *
     * @return int exit code
     */
    public function handle() : int;

}
