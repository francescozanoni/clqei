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

        // @todo extract file check logic

        if (file_exists($filePath) === false ||
            is_readable($filePath) === false ||
            is_file($filePath) === false
        ) {

            $this->error('Invalid file path');
            return;

        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        if (finfo_file($finfo, $filePath) !== 'text/plain') {

            $this->error('Invalid file type');
            finfo_close($finfo);
            return;

        }
        finfo_close($finfo);

        $wardNames = file($filePath);

        if (count($wardNames) === 0) {

            $this->error('Empty file');
            return;

        }

        $importService = App::make('App\Services\ImportService');
        $importService->import($filePath, App\Models\Ward::class);

    }

}
