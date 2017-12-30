<?php

namespace App\Http\Requests;

use App\Models\Question;
use App\Models\Compilation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Auth;

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
            'student_id' => 'required|exists:students,id',
        ];

        $questions = Question::all();
        
        // @todo fix optional select box field validation (e.g. question 13)
        
        foreach ($questions as $question) {
            $questionId = $question->id;
            $singleQuestionRules = [];
            if ($question->required == true) {
                $singleQuestionRules[] = 'required';
            }
            if ($question->type === 'single_choice' ||
                $question->type === 'multiple_choice') {
                $singleQuestionRules[] = Rule::exists('answers', 'id')->where(function ($query) use ($questionId) {
                        $query->where('question_id', $questionId);
                    });
            }
            $rules['q' . $questionId] = $singleQuestionRules;
        }

        return $rules;
    }
}
