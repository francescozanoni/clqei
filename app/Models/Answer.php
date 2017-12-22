<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{

    public $timestamps = false;

    /**
     * Get the question of this answer
     */
    public function question()
    {
        return $this->belongsTo('App\Models\Question');
    }

}
