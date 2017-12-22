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
        return $this->belongsTo('App\Models\Student');
    }

    /**
     * Get the items of this compilation
     */
    public function items()
    {
        return $this->hasMany('App\Models\CompilationItem');
    }

}
