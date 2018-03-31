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
     * Successful creation.
     */
    public function testSuccess()
    {

        $response =
            $this->post(
                    route(
                        'register',
                        [
                            'first_name' => 'Foo',
                            'last_name' => 'Bar',
                            'email' => 'foo.bar@example.com',
                            'password' => 'password',
                            'password_confirmation' => 'password',
                            'role' => \App\User::ROLE_STUDENT,
                            'identification_number' => '12121212',
                            'nationality' => 'IT',
                            'gender' => 'male',
                        ]
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
    
}
