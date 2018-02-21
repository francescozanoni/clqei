<?php

declare(strict_types = 1);

namespace Tests\Feature;

use App\Models\Location;
use App\Models\Ward;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdministratorTest extends TestCase
{

    use RefreshDatabase;

    /**
     * Pages available to students.
     */
    public function testAvailablePages()
    {

        $this->seed();

        $user = User::administrators()->first();

        $response = $this->actingAs($user)->get(route('home'));
        $response->assertStatus(200);

        $response = $this->actingAs($user)->get(route('compilations.create'));
        $response->assertStatus(200);

        $response = $this->actingAs($user)->get(route('compilations.index'));
        $response->assertStatus(200);
        // Compilation list is rendered by DataTables, for administrators.
        $response->assertSee('datatables');

        // Administrators can see their own profile page.
        $response = $this->actingAs($user)->get(route('users.show', ['user' => $user]));
        $response->assertStatus(200);

        $response = $this->actingAs($user)->post(route('logout'));
        $response->assertRedirect('/');

        // Administrators can manage stage locations.
        $response = $this->actingAs($user)->get(route('locations.index'));
        $response->assertStatus(200);
        $response = $this->actingAs($user)->get(route('locations.edit', ['location' => Location::first()]));
        $response->assertStatus(200);
        $response = $this->actingAs($user)->post(route('locations.store', ['name' => 'TEST']));
        $response->assertStatus(302);
        $this->assertDatabaseHas('locations', ['name' => 'TEST']);
        $response = $this->actingAs($user)->put(route('locations.update', ['location' => Location::first()]));
        $response->assertStatus(302);
        $response = $this->actingAs($user)->delete(route('locations.destroy', ['location' => Location::first()]));
        $response->assertStatus(302);
        $this->assertDatabaseMissing('locations', ['id' => 1, 'deleted_at' => null]);

        // Administrators can manage stage wards.
        $response = $this->actingAs($user)->get(route('wards.index'));
        $response->assertStatus(200);
        $response = $this->actingAs($user)->get(route('wards.edit', ['ward' => Ward::first()]));
        $response->assertStatus(200);
        $response = $this->actingAs($user)->post(route('wards.store', ['name' => 'TEST']));
        $response->assertStatus(302);
        $this->assertDatabaseHas('wards', ['name' => 'TEST']);
        $response = $this->actingAs($user)->put(route('wards.update', ['ward' => Ward::first()]));
        $response->assertStatus(302);
        $response = $this->actingAs($user)->delete(route('wards.destroy', ['ward' => Ward::first()]));
        $response->assertStatus(302);
        $this->assertDatabaseMissing('wards', ['id' => 1, 'deleted_at' => null]);

        // Administrators can see the list of users.
        $response = $this->actingAs($user)->get(route('users.index'));
        $response->assertStatus(200);
        $response->assertSee(__('Administrators'));
        $response = $this->actingAs($user)->get(route('users.index', ['role' => 'student']));
        $response->assertStatus(200);
        $response = $this->actingAs($user)->get(route('users.index', ['role' => 'viewer']));
        $response->assertStatus(200);
        $response = $this->actingAs($user)->get(route('users.index', ['role' => 'administrator']));
        $response->assertStatus(200);

        // @todo add test administrators can see other administrators profile page

        // Administrators can see students' profile page.
        $response = $this->actingAs($user)->get(route('users.show', ['user' => User::students()->first()]));
        $response->assertStatus(200);

        // Administrators can see viewers' profile page.
        $response = $this->actingAs($user)->get(route('users.show', ['user' => User::viewers()->first()]));
        $response->assertStatus(200);

        // Pages available only to unauthenticated users, viewers or administrators.
        $response = $this->get(route('register'));
        $response->assertStatus(200);

    }

    /**
     * Pages unavailable to students.
     */
    public function testUnavailablePages()
    {

        $this->seed();

        $user = User::administrators()->first();

        // Administrators cannot delete themselves.
        $response = $this->actingAs($user)->delete(route('users.destroy', ['user' => $user]));
        $response->assertStatus(403);
        // @todo add test that administrators can delete other administrators
        // These two assertions are to be updated and moved to available pages method,
        // once user edit logic is implemented.
        $response = $this->actingAs($user)->get(route('users.edit', ['user' => $user]));
        $response->assertRedirect(route('home'));
        $response = $this->actingAs($user)->put(route('users.update', ['user' => $user]));
        $response->assertRedirect(route('home'));

        // Pages available only to unauthenticated users.
        $response = $this->get(route('login'));
        $response->assertRedirect(route('home'));
        $response = $this->get(route('password.request'));
        $response->assertRedirect(route('home'));
        $response = $this->get(route('password.reset', ['token' => 'random']));
        $response->assertRedirect(route('home'));

    }

}
