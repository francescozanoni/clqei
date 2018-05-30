<?php
declare(strict_types = 1);

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\App;
use Tests\TestCase;

class CompilationServiceTest extends TestCase
{

    use RefreshDatabase;

    /**
     * @var \App\Services\CompilationService
     */
    private $service;

    public function setUp()
    {
        parent::setUp();
        $this->seed();

        // @todo refactor by moving to setUpBeforeClass() method
        if (isset($this->service) === false) {
            $this->service = App::make('App\Services\CompilationService');
        }
    }

    public function testGetAnswerTextFound()
    {
        // Answer ID as string
        $text = $this->service->getAnswerText('1');
        $this->assertEquals('18', $text);

        // Answer ID as integer
        $text = $this->service->getAnswerText(1);
        $this->assertEquals('18', $text);
    }

    public function testGetAnswerTextFoundWithContext()
    {
        $text = $this->service->getAnswerText('1', 'stage_location_id');
        $this->assertEquals('Example location', $text);

        $text = $this->service->getAnswerText('1', 'stage_ward_id');
        $this->assertEquals('Example ward', $text);

        // @todo add tests of stage_weeks, student.gender and student.nationality
    }

    public function testGetAnswerTextNotFound()
    {
        $text = $this->service->getAnswerText('2017/2018');
        $this->assertEquals('2017/2018', $text);
    }

    public function testGetFixedQuestionTextFound()
    {
        $text = $this->service->getQuestionText('stage_location_id');
        $this->assertEquals(__('Location'), $text);
    }

    public function testGetDynamicQuestionTextFound()
    {
        // Question ID as string, with "q" prefix
        $text = $this->service->getQuestionText('q1');
        $this->assertEquals('Età', $text);

        // Question ID as string, without "q" prefix
        $text = $this->service->getQuestionText('1');
        $this->assertEquals('Età', $text);

        // Question ID as integer
        $text = $this->service->getQuestionText(1);
        $this->assertEquals('Età', $text);
    }

    public function testGetQuestionTextNotFound()
    {
        $text = $this->service->getQuestionText('__not_found__');
        $this->assertEquals('__not_found__', $text);
    }

}
