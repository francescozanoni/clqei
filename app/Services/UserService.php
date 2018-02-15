<?php
/**
 * Created by PhpStorm.
 * User: Francesco.Zanoni
 * Date: 15/02/2018
 * Time: 14:17
 */
declare(strict_types = 1);

namespace App\Services;

class UserService
{

    /**
     * User creation validation rules for guest users:
     * only students can be created.
     *
     * @return array
     *
     * @todo extract configuration parameter reading and country list retrieval
     */
    public function getGuestValidationRules() : array
    {
        $rules = $this->getBaseValidationRules();

        $rules['role'][] = 'in:student';
        $rules['identification_number'] = [
            'required',
            'regex:/' . config('clqei.students.identification_number.pattern') . '/',
            'unique:students'
        ];
        $rules['gender'] = ['required', 'in:male,female'];
        $rules['nationality'] = [
            'required',
            'in:' . implode(',', App::make('App\Services\CountryService')->getCountryCodes())
        ];
        $rules['email'][] = 'regex:/' . config('clqei.students.email.pattern') . '/';

        return $rules;
    }

    /**
     * Base validation rules for user creation.
     *
     * @return array
     */
    public function getBaseValidationRules() : array
    {

        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'role' => ['required'],
        ];

    }

    /**
     * User creation validation rules for viewers:
     * only viewers can be created.
     *
     * @return array
     */
    public function getViewerValidationRules() : array
    {
        $rules = $this->getBaseValidationRules();

        $rules['role'][] = 'in:viewer';

        return $rules;
    }

    /**
     * User creation validation rules for administrators:
     * both viewers and administrators can be created.
     *
     * @return array
     */
    public function getAdministratorValidationRules() : array
    {
        $rules = $this->getBaseValidationRules();

        $rules['role'][] = 'in:viewer,administrator';

        return $rules;
    }

}
