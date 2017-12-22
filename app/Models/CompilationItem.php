<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompilationItem extends Model
{

    /**
     * Get the compilation to which this item belongs
     */
    public function compilation()
    {
        return $this->belongsTo('App\Models\Compilation');
    }

}
