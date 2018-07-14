<?php
declare(strict_types = 1);

namespace Tests\Feature;

use App\Models\Location;
use App\Models\Ward;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ViewerTest extends TestCase
{

    use RefreshDatabase;
    
    public function setUp()
    {
        parent::setUp();
        $this->seed();
    }

    /**
     * Pages available to students.
     */
    public function testAvailablePages()
    {

        $user = User::viewers()->first();

        $response = $this->actingAs($user)->get(route('home'));
        $response->assertStatus(200);

        $response = $this->actingAs($user)->get(route('compilations.create'));
        $response->assertStatus(200);

        $response = $this->actingAs($user)->get(route('compilations.index'));
        $response->assertStatus(200);
        // Compilation list is rendered by DataTables, for viewers.
        $response->assertSee('datatables');
        
        $response = $this->actingAs($user)->get(route('compilations.statistics_charts'));
        $response->assertStatus(200);
        $response = $this->actingAs($user)->get(route('compilations.statistics_counts'));
        $response->assertStatus(200);

        // Viewers can see their own profile page.
        $response = $this->actingAs($user)->get(route('users.show', ['user' => $user]));
        $response->assertStatus(200);

        $response = $this->actingAs($user)->post(route('logout'));
        $response->assertRedirect('/');

        // Viewers can manage stage locations.
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

        // Viewers can manage stage wards.
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

        // Viewers can see the list of users.
        $response = $this->actingAs($user)->get(route('users.index'));
        $response->assertStatus(200);
        $response->assertDontSee(__('Administrators'));
        $response = $this->actingAs($user)->get(route('users.index', ['role' => User::ROLE_STUDENT]));
        $response->assertStatus(200);
        // Student list is rendered by DataTables, for administrators.
        $response->assertSee('datatables');
        $response = $this->actingAs($user)->get(route('users.index', ['role' => User::ROLE_VIEWER]));
        $response->assertStatus(200);

        // @todo add test viewers can see other viewers profile page

        // Viewers can see students' profile page.
        $response = $this->actingAs($user)->get(route('users.show', ['user' => User::students()->first()]));
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

        $user = User::viewers()->first();

        // Viewers cannot see the list of administrators.
        $response = $this->actingAs($user)->get(route('users.index', ['role' => User::ROLE_ADMINISTRATOR]));
        $response->assertStatus(403);

        // Viewers cannot see administrators' profile page.
        $response = $this->actingAs($user)->get(route('users.show', ['user' => User::administrators()->first()]));
        $response->assertStatus(403);
        // Viewers cannot delete themselves.
        $response = $this->actingAs($user)->delete(route('users.destroy', ['user' => $user]));
        $response->assertStatus(403);
        // @todo add test that viewers cannot delete other viewers
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
