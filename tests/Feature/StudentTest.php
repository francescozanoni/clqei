<?php
declare(strict_types = 1);

namespace Tests\Feature;

use App\Models\Location;
use App\Models\Ward;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentTest extends TestCase
{

    use RefreshDatabase;

    /**
     * Pages available to students.
     */
    public function testAvailablePages()
    {

        $this->seed();

        $user = User::students()->first();

        $response = $this->actingAs($user)->get(route('home'));
        $response->assertStatus(200);

        $response = $this->actingAs($user)->get(route('compilations.create'));
        $response->assertStatus(200);

        $response = $this->actingAs($user)->get(route('compilations.index'));
        $response->assertStatus(200);
        // Compilation list is not rendered by DataTables, for students.
        $response->assertDontSee('dataTables');

        $response = $this->actingAs($user)->get(route('users.show', ['user' => $user]));
        $response->assertStatus(200);

        $response = $this->actingAs($user)->post(route('logout'));
        $response->assertRedirect('/');

    }

    /**
     * Pages unavailable to students.
     */
    public function testUnavailablePages()
    {

        $this->seed();

        $user = User::students()->first();

        // Students cannot do anything with stage locations.
        $response = $this->actingAs($user)->get(route('locations.index'));
        $response->assertRedirect(route('home'));
        $response = $this->actingAs($user)->get(route('locations.edit', ['location' => Location::first()]));
        $response->assertRedirect(route('home'));
        $response = $this->actingAs($user)->post(route('locations.store'));
        $response->assertRedirect(route('home'));
        $response = $this->actingAs($user)->put(route('locations.update', ['location' => Location::first()]));
        $response->assertRedirect(route('home'));
        $response = $this->actingAs($user)->delete(route('locations.destroy', ['location' => Location::first()]));
        $response->assertRedirect(route('home'));

        // Students cannot do anything with stage wards.
        $response = $this->actingAs($user)->get(route('wards.index'));
        $response->assertRedirect(route('home'));
        $response = $this->actingAs($user)->get(route('wards.edit', ['ward' => Ward::first()]));
        $response->assertRedirect(route('home'));
        $response = $this->actingAs($user)->post(route('wards.store'));
        $response->assertRedirect(route('home'));
        $response = $this->actingAs($user)->put(route('wards.update', ['ward' => Location::first()]));
        $response->assertRedirect(route('home'));
        $response = $this->actingAs($user)->delete(route('wards.destroy', ['ward' => Location::first()]));
        $response->assertRedirect(route('home'));

        // Students cannot see the list of users.
        $response = $this->actingAs($user)->get(route('users.index'));
        $response->assertRedirect(route('home'));
        // Students cannot see other users' profile page.
        $response = $this->actingAs($user)->get(route('users.show', ['user' => User::administrators()->first()]));
        $response->assertStatus(403);
        $response = $this->actingAs($user)->get(route('users.show', ['user' => User::viewers()->first()]));
        $response->assertStatus(403);
        // Students cannot delete themselves.
        $response = $this->actingAs($user)->delete(route('users.destroy', ['user' => $user]));
        $response->assertStatus(403);
        // @todo add test that students cannot delete other students
        // These two assertions are to be updated and moved to available pages method,
        // once user edit logic is implemented.
        $response = $this->actingAs($user)->get(route('users.edit', ['user' => $user]));
        $response->assertRedirect(route('home'));
        $response = $this->actingAs($user)->put(route('users.update', ['user' => $user]));
        $response->assertRedirect(route('home'));

        // Pages available only to unauthenticated users.
        $response = $this->get(route('login'));
        $response->assertRedirect(route('home'));
        $response = $this->get(route('register'));
        $response->assertRedirect(route('home'));
        $response = $this->get(route('password.request'));
        $response->assertRedirect(route('home'));
        $response = $this->get(route('password.reset', ['token' => 'random']));
        $response->assertRedirect(route('home'));

    }

    //@todo add test that a student can see his/her compilations
    //@todo add test that a student cannot see other students' compilations

}
