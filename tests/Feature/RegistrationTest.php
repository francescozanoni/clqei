<?php
declare(strict_types = 1);

namespace Tests\Feature;

use App;
use App\Observers\EloquentModelObserver;
use App\User;
use Carbon\Carbon;
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

        // Both e-mail and identification number duplicate.
        $response =
            $this->post(
                    route(
                        'register',
                        [
            'first_name' => 'Example',
            'last_name' => 'Student',
            'email' => 'student@example.com',
            'password' => 'student',
            'password_confirmation' => 'student',
            'role' => User::ROLE_STUDENT,
            'identification_number' => '12345678',
            'nationality' => 'IT',
            'gender' => 'male',
        ]
                    )
                );

        $response->assertSessionHasErrors(['email', 'identification_number']);
        
        $this->assertDatabaseMissing('users', ['id' => 4]);
        $this->assertDatabaseMissing('students', ['id' => 2]);
        
        $this->assertGuest();
        
        // Duplicate e-mail.
        $response =
            $this->post(
                    route(
                        'register',
                        [
            'first_name' => 'Example',
            'last_name' => 'Student',
            'email' => 'student@example.com',
            'password' => 'student',
            'password_confirmation' => 'student',
            'role' => User::ROLE_STUDENT,
            'identification_number' => '12121212',
            'nationality' => 'IT',
            'gender' => 'male',
        ]
                    )
                );

        $response->assertSessionHasErrors(['email']);
        
        $this->assertDatabaseMissing('users', ['id' => 4]);
        $this->assertDatabaseMissing('students', ['id' => 2]);
        
        $this->assertGuest();
        
        // Duplicate identification number.
        $response =
            $this->post(
                    route(
                        'register',
                        [
            'first_name' => 'Example',
            'last_name' => 'Student',
            'email' => 'another.student@example.com',
            'password' => 'student',
            'password_confirmation' => 'student',
            'role' => User::ROLE_STUDENT,
            'identification_number' => '12345678',
            'nationality' => 'IT',
            'gender' => 'male',
        ]
                    )
                );

        $response->assertSessionHasErrors(['identification_number']);
        
        $this->assertDatabaseMissing('users', ['id' => 4]);
        $this->assertDatabaseMissing('students', ['id' => 2]);
        
        $this->assertGuest();

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
    
}
