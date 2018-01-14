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
     * Get the location where the stage of this compilation took place
     */
    public function stageLocation()
    {
        return $this->belongsTo('App\Models\Location', 'stage_location_id', 'id');
    }
    
    /**
     * Get the ward where the stage of this compilation took place
     */
    public function stageWard()
    {
        return $this->belongsTo('App\Models\Ward', 'stage_ward_id', 'id');
    }

    /**
     * Get the items of this compilation
     */
    public function items()
    {
        return $this->hasMany('App\Models\CompilationItem');
    }

}
