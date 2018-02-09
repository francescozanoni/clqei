<?php
declare(strict_types = 1);

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomePageTest extends TestCase
{

    use RefreshDatabase;

    /**
     * What administrators can see on home page.
     */
    public function testAdministrator()
    {

        $this->seed();

        $user = User::administrators()->first();

        $response = $this->actingAs($user)->get('/home');

        $response->assertSee('<h3>' . __('Questionnaire compilations') . '</h3>');
        $response->assertSeeText(__('Compilation form'));
        $response->assertDontSeeText(__('New compilation'));
        $response->assertSeeText(__('All compilations'));
        $response->assertDontSeeText(__('My compilations'));

        $response->assertSee('<h3>' . __('Stages') . '</h3>');
        $response->assertSeeText(__('Locations'));
        $response->assertSeeText(__('Wards'));

        $response->assertSee('<h3>' . __('Users') . '</h3>');
        $response->assertSeeText(__('Register new viewer or administrator'));
        $response->assertDontSee(__('Register new viewer') . '</a>');
        $response->assertSeeText(__('Administrators'));
        $response->assertSeeText(__('Viewers'));
        $response->assertSeeText(__('Students'));

    }

    /**
     * What viewers can see on home page.
     */
    public function testViewer()
    {

        $this->seed();

        $user = User::viewers()->first();

        $response = $this->actingAs($user)->get('/home');

        $response->assertSee('<h3>' . __('Questionnaire compilations') . '</h3>');
        $response->assertSeeText(__('Compilation form'));
        $response->assertDontSeeText(__('New compilation'));
        $response->assertSeeText(__('All compilations'));
        $response->assertDontSeeText(__('My compilations'));

        $response->assertSee('<h3>' . __('Stages') . '</h3>');
        $response->assertSeeText(__('Locations'));
        $response->assertSeeText(__('Wards'));

        $response->assertSee('<h3>' . __('Users') . '</h3>');
        $response->assertDontSeeText(__('Register new viewer or administrator'));
        $response->assertSee(__('Register new viewer') . '</a>');
        $response->assertDontSeeText(__('Administrators'));
        $response->assertSeeText(__('Viewers'));
        $response->assertSeeText(__('Students'));

    }

    /**
     * What students can see on home page.
     */
    public function testStudent()
    {

        $this->seed();

        $user = User::students()->first();

        $response = $this->actingAs($user)->get('/home');

        $response->assertSee('<h3>' . __('Questionnaire compilations') . '</h3>');
        $response->assertDontSeeText(__('Compilation form'));
        $response->assertSeeText(__('New compilation'));
        $response->assertDontSeeText(__('All compilations'));
        $response->assertSeeText(__('My compilations'));

        $response->assertDontSee('<h3>' . __('Stages') . '</h3>');
        $response->assertDontSeeText(__('Locations'));
        $response->assertDontSeeText(__('Wards'));

        $response->assertDontSee('<h3>' . __('Users') . '</h3>');
        $response->assertDontSeeText(__('Register new viewer or administrator'));
        $response->assertDontSee(__('Register new viewer') . '</a>');
        $response->assertDontSeeText(__('Administrators'));
        $response->assertDontSeeText(__('Viewers'));
        $response->assertDontSeeText(__('Students'));

    }

}
