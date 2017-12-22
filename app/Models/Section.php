<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{

    public $timestamps = false;

    /**
     * Get the questions of this section
     */
    public function questions()
    {
        return $this->hasMany('App\Models\Question');
    }

}
