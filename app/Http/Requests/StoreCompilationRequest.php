<?php

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
        ];

        $questions = Question::all();

        // Flag object reporting whether the current question must be
        // required according to the value of a previous question.
        $makesNextRequired = null;

        // @todo add date range constraints

        foreach ($questions as $question) {
            $questionId = $question->id;
            $singleQuestionRules = [];

            if ($question->required == true) {
                $singleQuestionRules[] = 'required';
            } else {
                // https://laravel.com/docs/5.5/validation#a-note-on-optional-fields
                $singleQuestionRules[] = 'nullable';
            }

            // Usage of the flag object reporting whether the current question must be
            // required according to the value of a previous question.
            if ($makesNextRequired !== null) {
                $singleQuestionRules[] =
                    'required_if:' .
                    'q' . $makesNextRequired->question . ',' .
                    $makesNextRequired->answer;
                $makesNextRequired->next--;
                if ($makesNextRequired->next === 0) {
                    $makesNextRequired = null;
                }
            }
            if ($question->type === 'single_choice' ||
                $question->type === 'multiple_choice'
            ) {
                $singleQuestionRules[] = Rule::exists('answers', 'id')
                    ->where(function ($query) use ($questionId) {
                        $query->where('question_id', $questionId);
                    });
            }
            if ($question->type === 'date') {
                $singleQuestionRules[] = 'date';
            }
            $rules['q' . $questionId] = $singleQuestionRules;

            // Management of the flag reporting whether next question(s) must be
            // required according to the value of the current question.
            // This statement must stay here on the bottom,
            // anyway after if ($makesNextRequired !== null) {} block.
            if (isset($question->options) === true) {
                $options = json_decode($question->options);
                if (isset($options->makes_next_required) === true) {
                    $makesNextRequired = $options->makes_next_required;
                    $makesNextRequired->question = $question->id;
                    $makesNextRequired->answer = $question->answers[$makesNextRequired->answer - 1]->id;
                }
            }
        }

        return $rules;
    }
}
