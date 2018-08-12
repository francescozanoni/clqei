<?php
declare(strict_types = 1);

namespace App\Services;

use Illuminate\Filesystem\Filesystem;

class ImportService
{

    /**
     * Validate files to import
     *
     * @param string|array $filePaths paths to files to import
     * @return array validation errors
     *
     * @todo adapt validation error output in case of multiple file paths
     */
    public function validate($filePaths) : array
    {

        if (is_array($filePaths) === false) {
            $filePaths = [$filePaths];
        }
        
        foreach ($filePaths as $filePath) {
        
        if (file_exists($filePath) === false ||
            is_readable($filePath) === false ||
            is_file($filePath) === false
        ) {
            return ['Invalid file path'];
        }

        $fileSystem = new Filesystem;
        $fileMimeType = $fileSystem->mimeType($filePath);
        if ($fileMimeType !== 'text/plain') {
            return ['Invalid file type'];
        }

        if (count(file($filePath)) === 0) {
            return ['Empty file'];
        }
        
        }

        return [];

    }

    /**
     * Import file
     *
     * @param string $filePath path to file to import
     * @param string $class class of models to import
     */
    public function import(string $filePath, string $class)
    {

        if (in_array('App\Models\Interfaces\Importable', class_implements($class)) === false) {
            throw new \InvalidArgumentException('Invalid import model class');
        }

        $records = file($filePath);

        // @todo add character check
        // @todo add uniqueness check
        // @todo add return of number of records imported

        // Data is cleaned.
        $records = array_unique(array_map('trim', $records));

        foreach ($records as $record) {
            $model = new $class();
            $model->name = $record;
            $model->save();
        }

    }

}
