<?php
declare(strict_types = 1);

namespace App\Http\Requests;

use App\User;
use Auth;
use Illuminate\Foundation\Http\FormRequest;

class IndexUsersRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() : bool
    {
        $user = Auth::user();
        
        switch ($this->get('role')) {
        
            case User::ROLE_ADMINISTRATOR:
                return $user->can('viewAdministrators', User::class);
                
            case User::ROLE_VIEWER:
                return $user->can('viewViewers', User::class);
                
            case User::ROLE_STUDENT:
                return $user->can('viewStudents', User::class);
                
            default:
                return true;
               
        }
    }
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() : array
    {

        $rules = [
        
        ];

        return $rules;
    }

}
