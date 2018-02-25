<?php
declare(strict_types = 1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ImportService;
use App;

class ImportLocations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:locations
                            {file_path : Path to file containing locations}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import stage locations from file';

    protected $importService;

    /**
     * Create a new command instance.
     */
    public function __construct(ImportService $importService)
    {
	    parent::__construct();
	    $this->importService = $importService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filePath = $this->argument('file_path');

        // $importService = App::make('App\Services\ImportService');
        
        $errors = $this->importService->validate($filePath);
        
        if (empty($errors) === false) {
            foreach ($errors as $error) {
                $this->error($error);
            }
            return;
        }
        
        $this->importService->import($filePath, App\Models\Location::class);

    }

}
