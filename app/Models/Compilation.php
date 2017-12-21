<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Compilation extends Model
{

    /**
     * Get the student who created this compilation
     */
    public function student()
    {
        return $this->belongsTo('App\Student');
    }

}
