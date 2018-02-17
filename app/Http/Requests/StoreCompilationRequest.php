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

        // Queue array reporting whether the current question must
        // set its mandatority according to it.
        $requiredQueue = [];

        foreach ($questions as $question) {

            if ($question->required == true) {
                $rules['q' . $question->id][] = 'required';
            } else {
                // https://laravel.com/docs/5.5/validation#a-note-on-optional-fields
                $rules['q' . $question->id][] = 'nullable';
            }
            
            if (in_array($question->type, ['single_choice', 'multiple_choice']) === true) {
                $rules['q' . $question->id][] = Rule::exists('answers', 'id')
                    ->where(function ($query) use ($question) {
                        $query->where('question_id', $question->id);
                    });
            }
            
            if ($question->type === 'date') {
                $rules['q' . $question->id][] = 'date';
            }

            // Reading of the queue array reporting whether the current question must be
            // required according to the value of a previous question.
            if (($popped = array_pop($requiredQueue)) !== null) {
                $rules['q' . $question->id][] = 'required_if:q' . $popped['question'] . ',' . $popped['answer'];
            }

            // Writing of the queue array reporting whether next question(s) must be
            // required according to the value of the current question.
            // This statement must stay here on the bottom.
            if (isset($question->options) === false) {
                continue;
            }
            $options = json_decode($question->options);
            if (isset($options->makes_next_required) === false) {
                continue;
            }
                    for ($i = 0; $i < $options->makes_next_required->next; $i++) {
                        array_push(
                            $requiredQueue,
                            [
                                'question' => $question->id,
                                'answer' => $question->answers[$options->makes_next_required->answer - 1]->id
                            ]
                        );
                    }
                
            
        }

        return $rules;
    }
}
