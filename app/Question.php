<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{

    /**
     * Get the possible answers of this question
     */
    public function answers()
    {
        return $this->hasMany('App\Answers');
    }

}
