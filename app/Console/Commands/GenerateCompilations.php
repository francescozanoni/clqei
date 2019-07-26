<?php
declare(strict_types = 1);

namespace App\Console\Commands;

use App;
use App\Console\Commands\Interfaces\WithIntegerExitCode;
use Illuminate\Console\Command;

class GenerateCompilations extends Command implements WithIntegerExitCode
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "generate:compilations
                            {number=10: number of compilations to generate}";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Generate compilations";

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int exit code
     */
    public function handle() : int
    {

        // $number = (int)$this->argument('number');

        // $this->info('Compilations generated successfully');

        // @todo implement logic

        return self::SUCCESS;

    }

}
