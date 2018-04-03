<?php
declare(strict_types = 1);

namespace Tests\Feature;

use App;
use App\Observers\EloquentModelObserver;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ViewerManagementTest extends TestCase
{

    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();
        $this->seed();
    }

    /**
     * Successful creation by administrator.
     */
    public function testCreationSuccessByAdministrator()
    {

        $payload = $this->getPayload();

        $user = User::administrators()->first();

        $response = $this->actingAs($user)->post(route('register', $payload));

        $response->assertRedirect(route('home'));
        $response->assertSessionHas(
            EloquentModelObserver::FLASH_MESSAGE_KEY,
            __('The new viewer has been created')
        );

        $this->assertDatabaseHas('users', ['id' => 4]);
        $this->assertDatabaseMissing('users', ['id' => 5]);

    }

    private function getPayload() : array
    {
        return [
            'first_name' => 'Foo',
            'last_name' => 'Bar',
            'email' => 'foo.bar@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => User::ROLE_VIEWER,
        ];
    }

    /**
     * Successful creation by viewer.
     */
    public function testCreationSuccessByViewer()
    {

        $payload = $this->getPayload();

        $user = User::viewers()->first();

        $response = $this->actingAs($user)->post(route('register', $payload));

        $response->assertRedirect(route('home'));
        $response->assertSessionHas(
            EloquentModelObserver::FLASH_MESSAGE_KEY,
            __('The new viewer has been created')
        );

        $this->assertDatabaseHas('users', ['id' => 4]);
        $this->assertDatabaseMissing('users', ['id' => 5]);

    }

}
