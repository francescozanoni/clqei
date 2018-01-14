<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Answer extends Model
{

    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Get the question of this answer
     */
    public function question()
    {
        return $this->belongsTo('App\Models\Question');
    }
    
    public function __toString()
    {
        return $this->text;
    }

}
