<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompilationItem extends Model
{

    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Get the compilation to which this item belongs
     */
    public function compilation()
    {
        return $this->belongsTo('App\Models\Compilation');
    }
    
    /**
     * Get the question to which this item answers
     */
    public function question()
    {
        return $this->belongsTo('App\Models\Question');
    }

}
