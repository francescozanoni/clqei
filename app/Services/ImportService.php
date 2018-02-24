<?php
declare(strict_types = 1);

namespace App\Services;

class ImportService
{

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