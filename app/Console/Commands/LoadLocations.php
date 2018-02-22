<?php
declare(strict_types = 1);

namespace App\Console\Commands;

use App\Models\Location;
use Illuminate\Console\Command;

class LoadLocations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'load:locations
                            {file_path : Path of file containing locations}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Load stage locations from file';

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

        $locationNames = file($filePath);

        if (count($locationNames) === 0) {

            $this->error('Empty file');
            return;

        }

        // @todo refactor by extracting to a job
        // @todo add character check
        // @todo add uniqueness check

        // Data is cleaned.
        $locationNames =
            array_values(
                array_unique(
                    array_map(
                        function ($locationName) {
                            return trim($locationName);
                        },
                        $locationNames
                    )
                )
            );

        foreach ($locationNames as $locationName) {
            $location = new Location();
            $location->name = $locationName;
            $location->save();
        }

    }

}
