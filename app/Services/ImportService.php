<?php
declare(strict_types = 1);

namespace App\Services;

class ImportService
{

    public function validate(string $filePath) : array
    {

        if (file_exists($filePath) === false ||
            is_readable($filePath) === false ||
            is_file($filePath) === false
        ) {
            return ['Invalid file path'];
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $fileMimeType = finfo_file($finfo, $filePath);
        finfo_close($finfo);
        if ($fileMimeType !== 'text/plain') {
            return ['Invalid file type'];
        }

        if (count(file($filePath)) === 0) {
            return ['Empty file'];
        }
        
        return [];

    }

    public function import(string $filePath, string $class)
    {

        if (in_array('App\Models\Interfaces\Importable', class_implements($class)) === false) {
            throw new \InvalidArgumentException('Invalid import model class');
        }
        
        $records = file($filePath);

        // @todo add character check
        // @todo add uniqueness check

        // Data is cleaned.
        $records = array_unique(array_map('trim', $records));

        foreach ($records as $record) {
            $model = new $class();
            $model->name = $record;
            $model->save();
        }

    }

}