<?php
declare(strict_types=1);

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
    public function location()
    {
        return $this->belongsTo('App\Models\Location', 'stage_location_id', 'id');
    }

    /**
     * Get the ward where the stage of this compilation took place
     */
    public function ward()
    {
        return $this->belongsTo('App\Models\Ward', 'stage_ward_id', 'id');
    }

    /**
     * Get the items of this compilation
     *
     * @return array
     */
    public function getItemsAttribute() : array
    {
        $itemsToReturn = [];

        // Compilation items are returned only once, although items related
        // to "multiple_choice" are stored as several records/models.
        $questionIdsAlreadyUsed = [];
        foreach ($this->items()->get() as $item) {
            if (in_array($item->question_id, $questionIdsAlreadyUsed) === false) {
                $itemsToReturn[] = $item;
                $questionIdsAlreadyUsed[] = $item->question_id;
            }
        }

        return $itemsToReturn;
    }

    /**
     * Get the items of this compilation (as relationship)
     */
    public function items()
    {
        return $this->hasMany('App\Models\CompilationItem');
    }

}
