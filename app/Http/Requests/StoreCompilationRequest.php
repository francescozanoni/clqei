<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
        return [
            'q1' => '',
            'q2' => '',
            'q3' => '',
            'q4' => '',
            'q5' => '',

            'q6' => '',
            'q7' => '',
            'q8' => '',
            'q9' => '',
            'q10' => '',
            'q11' => '',
            'q12' => '',
            'q13' => '',
            'q14' => '',
            'q15' => '',
            'q16' => '',
            'q17' => '',
            'q18' => '',
            'q19' => '',

            'q20' => '',
            'q21' => '',
            'q22' => '',
            'q23' => '',
            'q24' => '',
            'q25' => '',

            'q26' => '',
            'q27' => '',
            'q28' => '',
            'q29' => '',
            'q30' => '',
            'q31' => '',

            'q32' => '',
            'q33' => '',
            'q34' => '',
            'q35' => '',

            'q36' => '',
            'q37' => '',
            'q38' => '',

            'q39' => '',
            'q40' => '',
            'q41' => '',
        ];
    }
}
