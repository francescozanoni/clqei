<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{

    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Get the possible answers of this question
     */
    public function answers()
    {
        return $this->hasMany('App\Models\Answer');
    }

    /**
     * Get the section of this question
     */
    public function section()
    {
        return $this->belongsTo('App\Models\Section');
    }

}
