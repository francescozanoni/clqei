<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{

    /**
     * Get the compilations of this student
     */
    public function compilations()
    {
        return $this->hasMany('App\Models\Compilation');
    }
    
    /**
     * Get the user of this student
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

}
