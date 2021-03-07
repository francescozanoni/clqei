<?php
declare(strict_types=1);

namespace App\Http\Requests;

use App\User;
use Auth;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        $user = Auth::user();
        return
            $user->can("create", User::class) ||
            $user->can("update", $user);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @param \App\Services\UserService $userService
     *
     * @return array
     */
    public function rules(\App\Services\UserService $userService): array
    {

        $rules = [];

        if (Auth::user()->role === User::ROLE_ADMINISTRATOR) {
            $rules = $userService->getAdministratorValidationRules($this->user);
        }
        if (Auth::user()->role === User::ROLE_VIEWER) {
            $rules = $userService->getViewerValidationRules($this->user);
        }

        return $rules;
    }
}
