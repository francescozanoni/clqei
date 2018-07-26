<?php
declare(strict_types = 1);

namespace Tests\Feature;

use App;
use App\Observers\EloquentModelObserver;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{

    use RefreshDatabase;

    /**
     * Successful registration.
     */
    public function testSuccess()
    {

        $payload = $this->getPayload();

        $response = $this->post(route('register', $payload));

        $response->assertRedirect(route('home'));
        $response->assertSessionHas(
            EloquentModelObserver::FLASH_MESSAGE_KEY,
            __('The new student has been created')
        );

        $this->assertDatabaseHas('users', ['id' => 1]);
        $this->assertDatabaseMissing('users', ['id' => 2]);
        $this->assertDatabaseHas('students', ['id' => 1]);
        $this->assertDatabaseMissing('students', ['id' => 2]);

        $user = User::first();
        $this->assertAuthenticatedAs($user);

    }

    private function getPayload() : array
    {
        return [
            'first_name' => 'Foo',
            'last_name' => 'Bar',
            'email' => 'foo.bar@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => User::ROLE_STUDENT,
            'identification_number' => '12121212',
            'nationality' => 'IT',
            'gender' => 'male',
        ];
    }

    /**
     * Failed registration: required field missing.
     */
    public function testMissingRequiredField()
    {

        $data = $this->getPayload();

        foreach (array_keys($data) as $keyToRemove) {

            // password_confirmation field is not required,
            // it must only match password field.
            if ($keyToRemove === 'password_confirmation') {
                continue;
            }

            $response =
                $this->post(
                    route(
                        'register',
                        array_filter(
                            $data,
                            function ($key) use ($keyToRemove) {
                                return $key !== $keyToRemove;
                            },
                            ARRAY_FILTER_USE_KEY
                        )
                    )
                );

            $response->assertSessionHasErrors([$keyToRemove]);

            $this->assertDatabaseMissing('users', ['id' => 1]);
            $this->assertDatabaseMissing('students', ['id' => 1]);

            $this->assertGuest();

        }

    }

    /**
     * Failed registration: student already registered.
     */
    public function testStudentAlreadyRegistered()
    {

        $this->seed();

        $basePayload = [
            'first_name' => 'Example',
            'last_name' => 'Student',
            'password' => 'student',
            'password_confirmation' => 'student',
            'role' => User::ROLE_STUDENT,
            'nationality' => 'IT',
            'gender' => 'male',
        ];

        // Both e-mail and identification number duplicate.
        $payload = $basePayload;
        $payload['email'] = 'student@example.com';
        $payload['identification_number'] = '12345678';
        $response = $this->post(route('register', $payload));

        $response->assertSessionHasErrors(['email', 'identification_number']);

        $this->assertDatabaseMissing('users', ['id' => 4]);
        $this->assertDatabaseMissing('students', ['id' => 2]);

        $this->assertGuest();

        // Duplicate e-mail.
        $payload = $basePayload;
        $payload['email'] = 'student@example.com';
        $payload['identification_number'] = '12121212';
        $response = $this->post(route('register', $payload));

        $response->assertSessionHasErrors(['email']);

        $this->assertDatabaseMissing('users', ['id' => 4]);
        $this->assertDatabaseMissing('students', ['id' => 2]);

        $this->assertGuest();

        // Duplicate identification number.
        $payload = $basePayload;
        $payload['email'] = 'another.student@example.com';
        $payload['identification_number'] = '12345678';
        $response = $this->post(route('register', $payload));

        $response->assertSessionHasErrors(['identification_number']);

        $this->assertDatabaseMissing('users', ['id' => 4]);
        $this->assertDatabaseMissing('students', ['id' => 2]);

        $this->assertGuest();

    }

    /**
     * Failed registration: invalid first name.
     */
    public function testInvalidFirstName()
    {

        foreach (['F', ''] as $fieldValue) {

            $payload = $this->getPayload();
            $payload['first_name'] = $fieldValue;
            $response = $this->post(route('register', $payload));

            $response->assertSessionHasErrors(['first_name']);

            $this->assertDatabaseMissing('users', ['id' => 4]);
            $this->assertDatabaseMissing('students', ['id' => 2]);

            $this->assertGuest();

        }

    }

    /**
     * Failed registration: invalid last name.
     */
    public function testInvalidLastName()
    {

        foreach (['B', ''] as $fieldValue) {

            $payload = $this->getPayload();
            $payload['last_name'] = $fieldValue;
            $response = $this->post(route('register', $payload));

            $response->assertSessionHasErrors(['last_name']);

            $this->assertDatabaseMissing('users', ['id' => 4]);
            $this->assertDatabaseMissing('students', ['id' => 2]);

            $this->assertGuest();

        }

    }

    /**
     * Failed registration: invalid e-mail.
     */
    public function testInvalidEMail()
    {

        foreach (['student@another.domain.com', 'example.com', ''] as $fieldValue) {

            $payload = $this->getPayload();
            $payload['email'] = $fieldValue;
            $response = $this->post(route('register', $payload));

            $response->assertSessionHasErrors(['email']);

            $this->assertDatabaseMissing('users', ['id' => 4]);
            $this->assertDatabaseMissing('students', ['id' => 2]);

            $this->assertGuest();

        }

    }

    /**
     * Failed registration: invalid password.
     */
    public function testInvalidPassword()
    {

        foreach (['pwd', ''] as $fieldValue) {

            $payload = $this->getPayload();
            $payload['password'] = $fieldValue;
            $payload['password_confirmation'] = $fieldValue;
            $response = $this->post(route('register', $payload));

            $response->assertSessionHasErrors(['password']);

            $this->assertDatabaseMissing('users', ['id' => 4]);
            $this->assertDatabaseMissing('students', ['id' => 2]);

            $this->assertGuest();

        }

        // Unmatched password.
        $payload = $this->getPayload();
        $payload['password'] = 'password1';
        $payload['password_confirmation'] = 'password2';
        $response = $this->post(route('register', $payload));

        $response->assertSessionHasErrors(['password']);

        $this->assertDatabaseMissing('users', ['id' => 4]);
        $this->assertDatabaseMissing('students', ['id' => 2]);

        $this->assertGuest();

        // Missing confirmation.
        $payload = $this->getPayload();
        unset($payload['password_confirmation']);
        $response = $this->post(route('register', $payload));

        $response->assertSessionHasErrors(['password']);

        $this->assertDatabaseMissing('users', ['id' => 4]);
        $this->assertDatabaseMissing('students', ['id' => 2]);

        $this->assertGuest();

    }

    /**
     * Failed registration: invalid role.
     */
    public function testInvalidRole()
    {

        foreach (['abc', 'STUDENT', User::ROLE_VIEWER, User::ROLE_ADMINISTRATOR, ''] as $fieldValue) {

            $payload = $this->getPayload();
            $payload['role'] = $fieldValue;
            $response = $this->post(route('register', $payload));

            $response->assertSessionHasErrors(['role']);

            $this->assertDatabaseMissing('users', ['id' => 4]);
            $this->assertDatabaseMissing('students', ['id' => 2]);

            $this->assertGuest();

        }

    }

    /**
     * Failed registration: invalid identification number.
     */
    public function testInvalidIdentificationNumber()
    {

        foreach (['abcd1234', '123456', ''] as $fieldValue) {

            $payload = $this->getPayload();
            $payload['identification_number'] = $fieldValue;
            $response = $this->post(route('register', $payload));

            $response->assertSessionHasErrors(['identification_number']);

            $this->assertDatabaseMissing('users', ['id' => 4]);
            $this->assertDatabaseMissing('students', ['id' => 2]);

            $this->assertGuest();

        }

    }

    /**
     * Failed registration: invalid nationality.
     */
    public function testInvalidNationality()
    {

        foreach (['XX', 'it', ''] as $fieldValue) {

            $payload = $this->getPayload();
            $payload['nationality'] = $fieldValue;
            $response = $this->post(route('register', $payload));

            $response->assertSessionHasErrors(['nationality']);

            $this->assertDatabaseMissing('users', ['id' => 4]);
            $this->assertDatabaseMissing('students', ['id' => 2]);

            $this->assertGuest();

        }

    }

    /**
     * Failed registration: invalid gender.
     */
    public function testInvalidGender()
    {

        foreach (['abc', 'MALE', ''] as $fieldValue) {

            $payload = $this->getPayload();
            $payload['gender'] = $fieldValue;
            $response = $this->post(route('register', $payload));

            $response->assertSessionHasErrors(['gender']);

            $this->assertDatabaseMissing('users', ['id' => 4]);
            $this->assertDatabaseMissing('students', ['id' => 2]);

            $this->assertGuest();

        }

    }

}
