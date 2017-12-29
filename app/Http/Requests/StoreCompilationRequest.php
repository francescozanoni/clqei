<?php

namespace App\Http\Requests;

use App\Models\Question;
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
        return Auth::user()->role === 'student';
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
