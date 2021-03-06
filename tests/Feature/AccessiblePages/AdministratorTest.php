<?php

declare(strict_types = 1);

namespace Tests\Feature\AccessiblePages;

use App\Models\Location;
use App\Models\Ward;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesCompilations;
use Tests\TestCase;

class AdministratorTest extends TestCase
{

    use RefreshDatabase;
    use CreatesCompilations;

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

        $user = User::administrators()->first();

        $response = $this->actingAs($user)->get(route("home"));
        $response->assertStatus(200);

        $response = $this->actingAs($user)->get(route("compilations.create"));
        $response->assertStatus(200);

        $response = $this->actingAs($user)->get(route("compilations.index"));
        $response->assertStatus(200);
        // Compilation list is rendered by DataTables, for administrators.
        $response->assertSee("datatables");

        $response = $this->actingAs($user)->get(route("compilations.statistics_charts"));
        $response->assertStatus(200);
        $response = $this->actingAs($user)->get(route("compilations.statistics_numbers"));
        $response->assertStatus(200);

        // Administrators can see their own profile page.
        $response = $this->actingAs($user)->get(route("users.show", ["user" => $user]));
        $response->assertStatus(200);

        $response = $this->actingAs($user)->post(route("logout"));
        $response->assertRedirect("/");

        // Administrators can manage stage locations.
        $response = $this->actingAs($user)->get(route("locations.index"));
        $response->assertStatus(200);
        $response = $this->actingAs($user)->get(route("locations.edit", ["location" => Location::first()]));
        $response->assertStatus(200);
        $response = $this->actingAs($user)->post(route("locations.store", ["name" => "TEST"]));
        $response->assertStatus(302);
        $this->assertDatabaseHas("locations", ["name" => "TEST"]);
        $response = $this->actingAs($user)->put(route("locations.update", ["location" => Location::first()]));
        $response->assertStatus(302);
        $response = $this->actingAs($user)->delete(route("locations.destroy", ["location" => Location::first()]));
        $response->assertStatus(302);
        $this->assertDatabaseMissing("locations", ["id" => 1, "deleted_at" => null]);

        // Administrators can manage stage wards.
        $response = $this->actingAs($user)->get(route("wards.index"));
        $response->assertStatus(200);
        $response = $this->actingAs($user)->get(route("wards.edit", ["ward" => Ward::first()]));
        $response->assertStatus(200);
        $response = $this->actingAs($user)->post(route("wards.store", ["name" => "TEST"]));
        $response->assertStatus(302);
        $this->assertDatabaseHas("wards", ["name" => "TEST"]);
        $response = $this->actingAs($user)->put(route("wards.update", ["ward" => Ward::first()]));
        $response->assertStatus(302);
        $response = $this->actingAs($user)->delete(route("wards.destroy", ["ward" => Ward::first()]));
        $response->assertStatus(302);
        $this->assertDatabaseMissing("wards", ["id" => 1, "deleted_at" => null]);

        // Administrators can see the list of users.
        $response = $this->actingAs($user)->get(route("users.index"));
        $response->assertStatus(200);
        $response->assertSee(__("Administrators"));
        $response = $this->actingAs($user)->get(route("users.index", ["role" => User::ROLE_STUDENT]));
        $response->assertStatus(200);
        // Student list is rendered by DataTables, for administrators.
        $response->assertSee("datatables");
        $response = $this->actingAs($user)->get(route("users.index", ["role" => User::ROLE_VIEWER]));
        $response->assertStatus(200);
        $response = $this->actingAs($user)->get(route("users.index", ["role" => User::ROLE_ADMINISTRATOR]));
        $response->assertStatus(200);

        // @todo add test administrators can see other administrators profile page

        // Administrators can see students' profile page.
        $response = $this->actingAs($user)->get(route("users.show", ["user" => User::students()->first()]));
        $response->assertStatus(200);

        // Administrators can see viewers' profile page.
        $response = $this->actingAs($user)->get(route("users.show", ["user" => User::viewers()->first()]));
        $response->assertStatus(200);

        // Pages available only to unauthenticated users, viewers or administrators.
        $response = $this->get(route("register"));
        $response->assertStatus(200);

        // Seed a compilation.
        // THIS STATEMENTS MUST BE HERE, AT THE BOTTOM OF THE METHOD, IN ORDER TO AVOID UNEXPECTED BEHAVIOUR.
        $compilation = $this->createAndGetCompilation();

        // Administrators can view compilation details.
        $response = $this->actingAs($user)->get(route("compilations.show", ["compilation" => $compilation]));
        $response->assertStatus(200);

        // Administrators can edit their own profile.
        $response = $this->actingAs($user)->get(route("users.edit", ["user" => $user]));
        $response->assertStatus(200);
        $response = $this->actingAs($user)->put(route("users.update", ["user" => $user]));
        $response->assertRedirect(route("users.edit", ["user" => $user]));

        // @todo add test administrators can edit other administrators' profile

        // Administrators can edit viewers' profile.
        $response = $this->actingAs($user)->get(route("users.edit", ["user" => User::viewers()->first()]));
        $response->assertStatus(200);
        $response = $this->actingAs($user)->put(route("users.update", ["user" => User::viewers()->first()]));
        $response->assertRedirect(route("users.edit", ["user" => User::viewers()->first()]));

    }

    /**
     * Pages unavailable to students.
     */
    public function testUnavailablePages()
    {

        $user = User::administrators()->first();

        // Administrators cannot delete themselves.
        $response = $this->actingAs($user)->delete(route("users.destroy", ["user" => $user]));
        $response->assertStatus(403);

        // @todo add test that administrators can delete other administrators

        // Pages available only to unauthenticated users.
        $response = $this->get(route("login"));
        $response->assertRedirect(route("home"));
        $response = $this->get(route("password.request"));
        $response->assertRedirect(route("home"));
        $response = $this->get(route("password.reset", ["token" => "random"]));
        $response->assertRedirect(route("home"));

    }

}
