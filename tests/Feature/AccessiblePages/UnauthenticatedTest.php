<?php
declare(strict_types = 1);

namespace Tests\Feature\AccessiblePages;

use App\Models\Location;
use App\Models\Ward;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UnauthenticatedTest extends TestCase
{

    use RefreshDatabase;

    /**
     * Pages available to unauthenticated users.
     */
    public function testAvailablePages()
    {

        $response = $this->get("/");
        $response->assertStatus(200);

        $response = $this->get(route("login"));
        $response->assertStatus(200);

        $response = $this->get(route("register"));
        $response->assertStatus(200);

        $response = $this->get(route("password.request"));
        $response->assertStatus(200);

        $response = $this->get(route("password.reset", ["token" => "random"]));
        $response->assertStatus(200);

    }

    /**
     * Pages unavailable to unauthenticated users.
     */
    public function testUnavailablePages()
    {

        $this->seed();

        $response = $this->get(route("home"));
        $response->assertRedirect(route("login"));

        $response = $this->get(route("compilations.index"));
        $response->assertRedirect(route("login"));
        $response = $this->post(route("compilations.store"));
        $response->assertRedirect(route("login"));

        $response = $this->get(route("locations.index"));
        $response->assertRedirect(route("login"));
        $response = $this->get(route("locations.edit", ["location" => Location::first()]));
        $response->assertRedirect(route("login"));
        $response = $this->post(route("locations.store"));
        $response->assertRedirect(route("login"));
        $response = $this->put(route("locations.update", ["location" => Location::first()]));
        $response->assertRedirect(route("login"));
        $response = $this->delete(route("locations.destroy", ["location" => Location::first()]));
        $response->assertRedirect(route("login"));

        $response = $this->get(route("wards.index"));
        $response->assertRedirect(route("login"));
        $response = $this->get(route("wards.edit", ["ward" => Ward::first()]));
        $response->assertRedirect(route("login"));
        $response = $this->post(route("wards.store"));
        $response->assertRedirect(route("login"));
        $response = $this->put(route("wards.update", ["ward" => Ward::first()]));
        $response->assertRedirect(route("login"));
        $response = $this->delete(route("wards.destroy", ["ward" => Ward::first()]));
        $response->assertRedirect(route("login"));

        $response = $this->get(route("users.index"));
        $response->assertRedirect(route("login"));
        $response = $this->get(route("users.show", ["user" => User::first()]));
        $response->assertRedirect(route("login"));
        $response = $this->put(route("users.update", ["user" => User::first()]));
        $response->assertRedirect(route("login"));
        $response = $this->delete(route("users.destroy", ["user" => User::first()]));
        $response->assertRedirect(route("login"));

        $response = $this->get(route("datatables-language", ["country" => config("app.locale")]));
        $response->assertRedirect(route("login"));
        $response = $this->get(route("datatables-datetime"));
        $response->assertRedirect(route("login"));

    }

}
