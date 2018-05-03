<?php
declare(strict_types = 1);

namespace App\Models;

use App\Models\Traits\EloquentGetTableName;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class CompilationItem extends Model
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
     * Get the compilation to which this item belongs
     * @return BelongsTo
     */
    public function compilation() : BelongsTo
    {
        return $this->belongsTo('App\Models\Compilation')->withTrashed();
    }

    /**
     * Get the question to which this item answers
     * @return BelongsTo
     */
    public function question() : BelongsTo
    {
        return $this->belongsTo('App\Models\Question')->withTrashed();
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

        // Quick-and-dirty optimization of the huge amount of queries triggered by this method.
        // @todo refactor
        if (Cache::has('compilation_' . $this->compilation->id . '_item_' . $this->id) === true) {
            return Cache::get('compilation_' . $this->compilation->id . '_item_' . $this->id);
        }

        if ($this->attributes['answer'] === null) {
            return null;
        }

        $answer = null;

        switch ($this->question->type) {

            case 'single_choice':
                $answer = $this->answer()->first()->text;
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
                $answer = $siblingItemAnswers;
                break;

            default:
                $answer = $this->attributes['answer'];
        }

        Cache::put('compilation_' . $this->compilation->id . '_item_' . $this->id, $answer, (new \DateTime())->add(\DateInterval::createFromDateString('1 seconds')));

        return $answer;
    }

    /**
     * Get the related answer object (if any)
     * @return BelongsTo
     */
    public function answer() : BelongsTo
    {
        return $this->belongsTo('App\Models\Answer', 'answer', 'id');
    }

}
