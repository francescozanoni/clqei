<?php
declare(strict_types = 1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App;

class ImportWards extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:wards
                            {file_path : Path to file containing wards}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import stage wards from file';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filePath = $this->argument('file_path');

        $importService = App::make('App\Services\ImportService');
        
        $errors = $importService->validate($filePath);
        
        if (empty($errors) === false) {
            foreach ($errors as $error) {
                $this->error($error);
            }
            return;
        }
        
        $importService->import($filePath, App\Models\Ward::class);

    }
    
}
