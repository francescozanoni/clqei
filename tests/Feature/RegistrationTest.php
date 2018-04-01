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

        $response =
            $this->post(
                route(
                    'register',
                    $this->getPayload()
                )
            );

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
                        'email' => 'student@example.com',
                        'password' => 'student',
                        'password_confirmation' => 'student',
                        'role' => User::ROLE_STUDENT,
                        'identification_number' => '12345678',
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
        
        $basePayload = $this->getPayload();

        $payload = $basePayload;
        $payload['first_name'] = 'F';
        $response = $this->post(route('register', $payload));

        $response->assertSessionHasErrors(['first_name']);

        $this->assertDatabaseMissing('users', ['id' => 4]);
        $this->assertDatabaseMissing('students', ['id' => 2]);

        $this->assertGuest();
        
        $payload = $basePayload;
        $payload['first_name'] = '';
        $response = $this->post(route('register', $payload));

        $response->assertSessionHasErrors(['first_name']);

        $this->assertDatabaseMissing('users', ['id' => 4]);
        $this->assertDatabaseMissing('students', ['id' => 2]);

        $this->assertGuest();

    }
    
    /**
     * Failed registration: invalid last name.
     */
    public function testInvalidLastName()
    {
        
        $basePayload = $this->getPayload();

        $payload = $basePayload;
        $payload['last_name'] = 'B';
        $response = $this->post(route('register', $payload));

        $response->assertSessionHasErrors(['last_name']);

        $this->assertDatabaseMissing('users', ['id' => 4]);
        $this->assertDatabaseMissing('students', ['id' => 2]);

        $this->assertGuest();
        
        $payload = $basePayload;
        $payload['last_name'] = '';
        $response = $this->post(route('register', $payload));

        $response->assertSessionHasErrors(['last_name']);

        $this->assertDatabaseMissing('users', ['id' => 4]);
        $this->assertDatabaseMissing('students', ['id' => 2]);

        $this->assertGuest();

    }

}
