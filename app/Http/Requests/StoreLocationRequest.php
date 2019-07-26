<?php
declare(strict_types = 1);

namespace App\Http\Requests;

use App\Models\Location;
use Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLocationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() : bool
    {
        $user = Auth::user();
        return
            $user->can("create", Location::class) &&
            $user->can("update", Location::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() : array
    {

        $rules = [
            "name" => [
                "required",
                "min:3",
                "string",
                Rule::unique(Location::getTableName())
                    ->where(function ($query) {
                        return $query->whereNull("deleted_at");
                    })
            ],
        ];

        return $rules;
    }
}
