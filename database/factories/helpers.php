<?php
/**
 * Created by PhpStorm.
 * User: Francesco.Zanoni
 * Date: 12/02/2018
 * Time: 14:17
 */

/**
 * First name, last name and e-mail address array from first name and last name
 *
 * @param string $firstName
 * @param string $lastName
 * @return array
 */
function firstNameLastNameEMail(string $firstName, string $lastName) : array
{
    return [
        'first_name' => $firstName,
        'last_name' => $lastName,
        'email' => strtolower($firstName . '.' . $lastName) . '@example.com',
    ];
}
