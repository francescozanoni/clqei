<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
     * @return BelongsTo
     */
    public function student() : BelongsTo
    {
        return $this->belongsTo('App\Models\Student');
    }

    /**
     * Get the location where the stage of this compilation took place
     * @return BelongsTo
     */
    public function stageLocation() : BelongsTo
    {
        return $this->belongsTo('App\Models\Location', 'stage_location_id', 'id');
    }

    /**
     * Get the ward where the stage of this compilation took place
     * @return BelongsTo
     */
    public function stageWard() : BelongsTo
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
     * @return HasMany
     */
    public function items() : HasMany
    {
        return $this->hasMany('App\Models\CompilationItem');
    }

}
