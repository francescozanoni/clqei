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
     * @var array Queue reporting question mandatority depending on the answer of a previous question
     */
    private $mandatorityQueue = [];

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

            $rules['q' . $question->id] = [];

            foreach ($this->getSingleQuestionRules($question) as $rule) {
                $rules['q' . $question->id][] = $rule;
            }

            $this->updateMandatorityQueue($question);

        }

        return $rules;
    }

    /**
     * Get validation rules of a single question (dynamic questions, with key "qN").
     *
     * @param Question $question
     * @return \Generator
     */
    private function getSingleQuestionRules(Question $question)
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

        // Reading of the mandatority queue, in case the current question is
        // required according to the value of a previous question.
        $requirement = array_pop($this->mandatorityQueue);
        if ($requirement !== null) {
            yield 'required_if:q' . $requirement['question'] . ',' . $requirement['answer'];
        }
    }

    /**
     * If a specific answer of the input question makes next question(s) mandatory,
     * one or more items are added to mandatority queue.
     *
     * @param Question $question
     */
    private function updateMandatorityQueue(Question $question)
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
                $this->mandatorityQueue,
                [
                    'question' => $question->id,
                    'answer' => $question->answers[$options->makes_next_required->answer - 1]->id
                ]
            );
        }

    }

}
