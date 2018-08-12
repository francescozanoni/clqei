<?php
declare(strict_types = 1);

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomePageTest extends TestCase
{

    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();
        $this->seed();
    }

    /**
     * What administrators can see on home page.
     */
    public function testAdministrator()
    {

        $user = User::administrators()->first();

        $response = $this->actingAs($user)->get(route('home'));
        
        $response->assertSee('<h3>' . __('Questionnaire compilations') . '</h3>');
        $response->assertSeeText(__('Compilation form'));
        $response->assertDontSeeText(__('New compilation'));
        $response->assertSeeText(__('All compilations'));
        $response->assertDontSeeText(__('My compilations'));
        $response->assertSeeText(__('Statistics'));

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

        $user = User::viewers()->first();

        $response = $this->actingAs($user)->get(route('home'));
        
        $response->assertSee('<h3>' . __('Questionnaire compilations') . '</h3>');
        $response->assertSeeText(__('Compilation form'));
        $response->assertDontSeeText(__('New compilation'));
        $response->assertSeeText(__('All compilations'));
        $response->assertDontSeeText(__('My compilations'));
        $response->assertSeeText(__('Statistics'));

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

        $user = User::students()->first();

        $response = $this->actingAs($user)->get(route('home'));

        $response->assertSee('<h3>' . __('Questionnaire compilations') . '</h3>');
        $response->assertDontSeeText(__('Compilation form'));
        $response->assertSeeText(__('New compilation'));
        $response->assertDontSeeText(__('All compilations'));
        $response->assertSeeText(__('My compilations'));
        $response->assertDontSeeText(__('Statistics'));

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
    
    public function testViewData()
    {
    
        $user = User::administrators()->first();
        $response = $this->actingAs($user)->get(route('home'));
        // The following array is the content
        // of $response->baseResponse->original->getData().
        $response->assertViewHasAll([
            'number_of_compilations' => 0,
            'number_of_students' => 1,
            'number_of_viewers' => 1,
            'number_of_administrators' => 1,
            'number_of_locations' => 1,
            'number_of_wards' => 1,
        ]);
        
        $user = User::viewers()->first();
        $response = $this->actingAs($user)->get(route('home'));
        // The following array is the content
        // of $response->baseResponse->original->getData().
        $response->assertViewHasAll([
            'number_of_compilations' => 0,
            'number_of_students' => 1,
            'number_of_viewers' => 1,
            'number_of_locations' => 1,
            'number_of_wards' => 1,
        ]);
        $response->assertViewMissing('number_of_administrators');
        
        $user = User::students()->first();
        $response = $this->actingAs($user)->get(route('home'));
        // The following array is the content
        // of $response->baseResponse->original->getData().
        $response->assertViewHasAll([
            'number_of_compilations' => 0,
            'number_of_locations' => 1,
            'number_of_wards' => 1,
        ]);
        $response->assertViewMissing('number_of_administrators');
        $response->assertViewMissing('number_of_viewers');
        $response->assertViewMissing('number_of_students');

    
    }

}
