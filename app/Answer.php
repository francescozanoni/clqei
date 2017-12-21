<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{

    /**
     * Get the question of this answer
     */
    public function question()
    {
        return $this->belongsTo('App\Question');
    }

}
