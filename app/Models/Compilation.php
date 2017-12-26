<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Compilation extends Model
{

    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

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
