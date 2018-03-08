<?php
declare(strict_types = 1);

namespace App\Models;

use App\Models\Traits\EloquentGetTableName;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Compilation extends Model
{

    use SoftDeletes;
    use EloquentGetTableName;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    protected $appends = [
        'stage_weeks'
    ];

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
        return $this->hasMany('App\Models\CompilationItem')
            // @todo item sorting must be based on section+question "position" attributes
            ->orderBy('question_id');
    }

    /**
     * Get the number of stage weeks.
     *
     * @return int
     */
    public function getStageWeeksAttribute() : int
    {
        return (int)round($this->getStageDays() / 7);
    }

    /**
     * Get the number of stage days.
     *
     * @return int
     */
    public function getStageDays() : int
    {
        return ((strtotime($this->stage_end_date) - strtotime($this->stage_start_date)) / 60 / 60 / 24) + 1;
    }

}
