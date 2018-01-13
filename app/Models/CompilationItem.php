<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompilationItem extends Model
{

    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Get the compilation to which this item belongs
     */
    public function compilation()
    {
        return $this->belongsTo('App\Models\Compilation');
    }

    /**
     * Get the question to which this item answers
     */
    public function question()
    {
        return $this->belongsTo('App\Models\Question');
    }

    /**
     * Get the related answer object (if any)
     */
    public function answer()
    {
        // Scalar answers (other than "single_choice" and "multiple_choice")
        // are retrieved straightforward by the "answer" attribute.
        if ($this->question->type !== 'single_choice' &&
            $this->question->type !== 'multiple_choice') {
            return null;
        }
        return $this->belongsTo('App\Models\Answer', 'answer', 'id');
    }

}
