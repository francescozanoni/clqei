<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{

    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Get the compilations of this student
     * @return HasMany
     */
    public function compilations() : HasMany
    {
        return $this->hasMany('App\Models\Compilation');
    }

    /**
     * Get the user of this student
     * @return BelongsTo
     */
    public function user() : BelongsTo
    {
        return $this->belongsTo('App\User');
    }

}
