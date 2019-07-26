<?php
declare(strict_types = 1);

namespace App\Services;

use App;
use App\User;

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

        $rules["role"][] = "in:" . User::ROLE_STUDENT;
        $rules["identification_number"] = [
            "required",
            "regex:/" . config("clqei.students.identification_number.pattern") . "/",
            "unique:students"
        ];
        $rules["gender"] = ["required", "in:male,female"];
        $rules["nationality"] = [
            "required",
            "in:" . implode(",", App::make("App\Services\CountryService")->getCountryCodes())
        ];
        $rules["email"][] = "regex:/" . config("clqei.students.email.pattern") . "/";

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
            "first_name" => ["required", "string", "min:2", "max:255"],
            "last_name" => ["required", "string", "min:2", "max:255"],
            "email" => ["required", "string", "email", "max:255", "unique:users"],
            "password" => ["required", "string", "min:6", "confirmed"],
            "role" => ["required"],
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

        $rules["role"][] = "in:" . User::ROLE_VIEWER;

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

        $rules["role"][] = "in:" . User::ROLE_VIEWER . "," . User::ROLE_ADMINISTRATOR;

        return $rules;
    }

}
