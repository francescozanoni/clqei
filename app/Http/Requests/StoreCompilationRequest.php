<?php
declare(strict_types = 1);

namespace App\Http\Requests;

use App\Models\Compilation;
use App\Models\Question;
use Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCompilationRequest extends FormRequest
{

    /**
     * @var array Queue reporting current question mandatority.
     */
    private $requiredQueue = [];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = Auth::user();
        return $user->can('create', Compilation::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $rules = [
            'student_id' => 'required|exists:students,id|in:' . Auth::user()->student->id,
            'stage_location_id' => 'required|exists:locations,id',
            'stage_ward_id' => 'required|exists:wards,id',
            'stage_start_date' => 'required|date|before:today',
            'stage_end_date' => 'required|date|after:stage_start_date|before:today',
            'stage_academic_year' => 'required|regex:/^\d{4}\/\d{4}$/'
        ];

        $questions = Question::all();

        foreach ($questions as $question) {

            foreach ($this->getItemRules($question) as $rule) {
                $rules['q' . $question->id][] = $rule;
            }

            $this->updateRequiredQueue($question);

        }

        return $rules;
    }

    /**
     * Get validation rules of a single question.
     *
     * @param Question $question
     * @return \Generator
     */
    private function getItemRules(Question $question)
    {
        if ($question->required == true) {
            yield 'required';
        } else {
            // https://laravel.com/docs/5.5/validation#a-note-on-optional-fields
            yield 'nullable';
        }

        if (in_array($question->type, ['single_choice', 'multiple_choice']) === true) {
            yield Rule::exists('answers', 'id')
                ->where(function ($query) use ($question) {
                    $query->where('question_id', $question->id);
                });
        }

        if ($question->type === 'date') {
            yield 'date';
        }

        // Reading of the queue reporting whether the current question must be
        // required according to the value of a previous question.
        $requirement = array_pop($this->requiredQueue);
        if ($requirement !== null) {
            yield 'required_if:q' . $requirement['question'] . ',' . $requirement['answer'];
        }
    }

    /**
     * Writing of the queue reporting whether next question(s) must be
     * required according to the value of the current question.
     *
     * @param Question $question
     */
    private function updateRequiredQueue(Question $question)
    {
        if (isset($question->options) === false) {
            return;
        }

        $options = json_decode($question->options);
        if (isset($options->makes_next_required) === false) {
            return;
        }
        for ($i = 0; $i < $options->makes_next_required->next; $i++) {
            array_push(
                $this->requiredQueue,
                [
                    'question' => $question->id,
                    'answer' => $question->answers[$options->makes_next_required->answer - 1]->id
                ]
            );
        }

    }
}
