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
        return $this->belongsTo('App\Models\Answer', 'answer', 'id');
    }

    public function getAnswersAttribute()
    {
        switch ($this->question->type) {
            //case 'single_choice':
            //    return $this->answer()->first()->text;
            //    break;
            case 'multiple_choice':
                $siblingItems =
                    CompilationItem::where('compilation_id', $this->compilation_id)
                        ->where('question_id', $this->question_id)
                        ->get();
                $siblingItemAnswers = [];
                foreach ($siblingItems as $siblingItem) {
                    $siblingItemAnswers[] = $siblingItem->answer()->first();
                }
                return $siblingItemAnswers;
                break;
            //default:
            //    return $this->attributes['answer'];
        }
    }
    
    public function getAnswerAttribute()
    {
        switch ($this->question->type) {
            case 'single_choice':
                return $this->answer()->first()->text;
                break;
            default:
                return $this->attributes['answer'];
        }
    }

}
