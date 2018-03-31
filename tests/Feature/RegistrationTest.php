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

        }

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
