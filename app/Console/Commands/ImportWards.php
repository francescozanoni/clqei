<?php
declare(strict_types = 1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ImportService;
use App\Console\Commands\Interfaces\WithIntegerExitCode;
use App;

class ImportWards extends Command implements WithIntegerExitCode
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
     * @var ImportService
     */
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
     *
     * @return int exit code
     */
    public function handle() : int
    {
    
        $filePath = $this->argument('file_path');
        
        $errors = $this->importService->validate($filePath);
        
        if (empty($errors) === false) {
            foreach ($errors as $error) {
                $this->error($error);
            }
            return self::INVALID_INPUT;
        }
        
        $this->importService->import($filePath, App\Models\Ward::class);
        
        $this->info('Wards imported successfully');
        
        return self::SUCCESS;

    }
    
}