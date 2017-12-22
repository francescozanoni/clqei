<?php

namespace App\Http\Requests;

use App\Models\Question;
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
        // @todo add authorization logic
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $rules = [];

        $questions = Question::where('active', true)->get();
        foreach ($questions as $question) {
            if (count($question->answers) > 0) {
                $questionId = $question->id;
                $rules['q' . $questionId] = [
                    'required',
                    Rule::exists('answers', 'id')->where(function ($query) use ($questionId) {
                        $query->where('question_id', $questionId)
                            ->where('active', true);
                    })
                ];
            }
        }

        return $rules;
    }
}
