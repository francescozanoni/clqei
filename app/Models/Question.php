<?php
declare(strict_types=1);

namespace App\Models;

use App\Models\Traits\EloquentGetTableName;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{

    use SoftDeletes;
    use EloquentGetTableName;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Get the possible answers of this question
     * @return HasMany
     */
    public function answers() : HasMany
    {
        return $this->hasMany('App\Models\Answer');
    }

    /**
     * Get the section of this question
     * @return BelongsTo
     */
    public function section() : BelongsTo
    {
        return $this->belongsTo('App\Models\Section');
    }

}
