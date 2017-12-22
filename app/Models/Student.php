<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{

    /**
     * Get the questions of this section
     */
    public function compilations()
    {
        return $this->hasMany('App\Models\Compilation');
    }

}
