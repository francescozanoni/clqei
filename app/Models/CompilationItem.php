<?php
declare(strict_types=1);

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
     * Get the answer of the item:
     *  - as a scalar value, if "answer" attribute contains an Answer model ID (single choice question)
     *  - as an array, if "answer" attribute contains an Answer model ID (multiple choice question)
     *  - as a string, if "answer" attribute contains the answer text itself
     *
     * This method cannot be "getAnswerAttribute",
     * because an attribute with the same name exists
     * @see https://stackoverflow.com/questions/41937721/undefined-property-in-model#41939732
     *
     * @return mixed
     */
    public function getTheAnswerAttribute()
    {
    
        // If the question has no answer (i.e. "answer" attribute is null,
        // null is returnd.
        if ($this->attributes['answer'] === null) {
            return null;
        }
        
        switch ($this->question->type) {

            case 'single_choice':
                return $this->answer()->first()->text;
                break;

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

            default:
                return $this->attributes['answer'];
        }
    }

    /**
     * Get the related answer object (if any)
     */
    public function answer()
    {
        return $this->belongsTo('App\Models\Answer', 'answer', 'id');
    }

}
