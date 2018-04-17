<?php
declare(strict_types=1);

namespace App\Models;

use App\Models\Traits\EloquentGetTableName;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Interfaces\Importable;

class Ward extends Model implements Importable
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
     * Get the compilations of this student
     * @return HasMany
     */
    public function compilations() : HasMany
    {
        return $this->hasMany('App\Models\Compilation', 'stage_ward_id');
    }

}
