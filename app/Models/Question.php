<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{

    public $timestamps = false;

    /**
     * Get the possible answers of this question
     */
    public function answers()
    {
        return $this->hasMany('App\Answer');
    }

    /**
     * Get the section of this question
     */
    public function section()
    {
        return $this->belongsTo('App\Section');
    }

}
