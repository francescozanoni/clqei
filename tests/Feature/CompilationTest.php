<?php
declare(strict_types = 1);

namespace Tests\Feature;

use App;
use App\Models\Location;
use App\Models\Ward;
use App\Observers\EloquentModelObserver;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\ProvidesCompilationPayload;

class CompilationTest extends TestCase
{

    use RefreshDatabase;
    use ProvidesCompilationPayload;

    const NUMBER_OF_COMPILATION_ITEMS = 37;
    const NUMBER_OF_COMPILATION_ITEMS_ALWAYS_STORED = 34;

    public function setUp()
    {
        parent::setUp();
        $this->seed();
    }

    /**
     * Successful creation: all fields populated.
     */
    public function testSuccessAllFields()
    {

        $user = User::students()->first();
        $stageLocation = Location::first();
        $stageWard = Ward::first();
        $stageStartDate = Carbon::today()->subMonth()->format("Y-m-d");
        $stageEndDate = Carbon::today()->subWeek()->format("Y-m-d");
        $stageAcademicYear = App::make("App\Services\AcademicYearService")->getFromDate($stageStartDate);

        $response =
            $this->actingAs($user)
                ->post(
                    route("compilations.store",
                        $this->getPayloadWithAllFields(
                            $user->student->id,
                            $stageLocation->id,
                            $stageWard->id,
                            $stageStartDate,
                            $stageEndDate,
                            $stageAcademicYear
                        )
                    )
                );

        $response->assertRedirect(route("compilations.show", ["compilation" => 1]));
        $response->assertSessionHas(
            EloquentModelObserver::FLASH_MESSAGE_KEY,
            __("The new compilation has been created")
        );
        $this->assertDatabaseHas("compilations", ["id" => 1]);
        $this->assertDatabaseMissing("compilations", ["id" => 2]);
        $this->assertDatabaseHas("compilation_items", ["id" => 1, "compilation_id" => 1]);
        $this->assertDatabaseHas("compilation_items", ["id" => self::NUMBER_OF_COMPILATION_ITEMS, "compilation_id" => 1]);
        $this->assertDatabaseMissing("compilation_items", ["id" => self::NUMBER_OF_COMPILATION_ITEMS + 1]);

    }

    /**
     * Successful creation: all fields populated, multiple compilations.
     */
    public function testSuccessAllFieldsMultipleCompilations()
    {

        $user = User::students()->first();
        $stageLocation = Location::first();
        $stageWard = Ward::first();

        // First compilation.
        $stageStartDate = Carbon::today()->subDays(40)->format("Y-m-d");
        $stageEndDate = Carbon::today()->subDays(20)->format("Y-m-d");
        $stageAcademicYear = App::make("App\Services\AcademicYearService")->getFromDate($stageStartDate);

        $response =
            $this->actingAs($user)
                ->post(
                    route("compilations.store",
                        $this->getPayloadWithAllFields(
                            $user->student->id,
                            $stageLocation->id,
                            $stageWard->id,
                            $stageStartDate,
                            $stageEndDate,
                            $stageAcademicYear
                        )
                    )
                );

        $response->assertRedirect(route("compilations.show", ["compilation" => 1]));
        $response->assertSessionHas(
            EloquentModelObserver::FLASH_MESSAGE_KEY,
            __("The new compilation has been created")
        );
        $this->assertDatabaseHas("compilations", ["id" => 1]);
        $this->assertDatabaseMissing("compilations", ["id" => 2]);
        $this->assertDatabaseHas("compilation_items", ["id" => 1, "compilation_id" => 1]);
        $this->assertDatabaseHas("compilation_items", ["id" => self::NUMBER_OF_COMPILATION_ITEMS, "compilation_id" => 1]);
        $this->assertDatabaseMissing("compilation_items", ["id" => self::NUMBER_OF_COMPILATION_ITEMS + 1]);

        // Second compilation.
        $stageStartDate = Carbon::today()->subDays(60)->format("Y-m-d");
        $stageEndDate = Carbon::today()->subDays(41)->format("Y-m-d");
        $stageAcademicYear = App::make("App\Services\AcademicYearService")->getFromDate($stageStartDate);

        $response =
            $this->actingAs($user)
                ->post(
                    route("compilations.store",
                        $this->getPayloadWithAllFields(
                            $user->student->id,
                            $stageLocation->id,
                            $stageWard->id,
                            $stageStartDate,
                            $stageEndDate,
                            $stageAcademicYear
                        )
                    )
                );

        $response->assertRedirect(route("compilations.show", ["compilation" => 2]));
        $response->assertSessionHas(
            EloquentModelObserver::FLASH_MESSAGE_KEY,
            __("The new compilation has been created")
        );
        $this->assertDatabaseHas("compilations", ["id" => 2]);
        $this->assertDatabaseMissing("compilations", ["id" => 3]);
        $this->assertDatabaseHas("compilation_items", ["id" => self::NUMBER_OF_COMPILATION_ITEMS + 1, "compilation_id" => 2]);
        $this->assertDatabaseHas("compilation_items", ["id" => self::NUMBER_OF_COMPILATION_ITEMS * 2, "compilation_id" => 2]);
        $this->assertDatabaseMissing("compilation_items", ["id" => self::NUMBER_OF_COMPILATION_ITEMS * 2 + 1]);

        // Third compilation.
        $stageStartDate = Carbon::today()->subDays(19)->format("Y-m-d");
        $stageEndDate = Carbon::today()->subDays(5)->format("Y-m-d");
        $stageAcademicYear = App::make("App\Services\AcademicYearService")->getFromDate($stageStartDate);

        $response =
            $this->actingAs($user)
                ->post(
                    route("compilations.store",
                        $this->getPayloadWithAllFields(
                            $user->student->id,
                            $stageLocation->id,
                            $stageWard->id,
                            $stageStartDate,
                            $stageEndDate,
                            $stageAcademicYear
                        )
                    )
                );

        $response->assertRedirect(route("compilations.show", ["compilation" => 3]));
        $response->assertSessionHas(
            EloquentModelObserver::FLASH_MESSAGE_KEY,
            __("The new compilation has been created")
        );
        $this->assertDatabaseHas("compilations", ["id" => 3]);
        $this->assertDatabaseMissing("compilations", ["id" => 4]);
        $this->assertDatabaseHas("compilation_items", ["id" => self::NUMBER_OF_COMPILATION_ITEMS * 2 + 1, "compilation_id" => 3]);
        $this->assertDatabaseHas("compilation_items", ["id" => self::NUMBER_OF_COMPILATION_ITEMS * 3, "compilation_id" => 3]);
        $this->assertDatabaseMissing("compilation_items", ["id" => self::NUMBER_OF_COMPILATION_ITEMS * 3 + 1]);

    }

    /**
     * Successful creation: all fields populated, previous academic year.
     */
    public function testSuccessAllFieldsPreviousAcademicYear()
    {

        $user = User::students()->first();
        $stageLocation = Location::first();
        $stageWard = Ward::first();
        $stageStartDate = Carbon::today()->subMonth()->format("Y-m-d");
        $stageEndDate = Carbon::today()->subWeek()->format("Y-m-d");
        $stageAcademicYear = App::make("App\Services\AcademicYearService")->getFromDate(Carbon::parse($stageStartDate)->subYear()->format("Y-m-d"));

        $response =
            $this->actingAs($user)
                ->post(
                    route("compilations.store",
                        $this->getPayloadWithAllFields(
                            $user->student->id,
                            $stageLocation->id,
                            $stageWard->id,
                            $stageStartDate,
                            $stageEndDate,
                            $stageAcademicYear
                        )
                    )
                );

        $response->assertRedirect(route("compilations.show", ["compilation" => 1]));
        $response->assertSessionHas(
            EloquentModelObserver::FLASH_MESSAGE_KEY,
            __("The new compilation has been created")
        );
        $this->assertDatabaseHas("compilations", ["id" => 1]);
        $this->assertDatabaseMissing("compilations", ["id" => 2]);
        $this->assertDatabaseHas("compilation_items", ["id" => 1, "compilation_id" => 1]);
        $this->assertDatabaseHas("compilation_items", ["id" => self::NUMBER_OF_COMPILATION_ITEMS, "compilation_id" => 1]);
        $this->assertDatabaseMissing("compilation_items", ["id" => self::NUMBER_OF_COMPILATION_ITEMS + 1]);

    }

    /**
     * Successful creation: only required fields.
     */
    public function testSuccessOnlyRequiredFields()
    {

        $user = User::students()->first();
        $stageLocation = Location::first();
        $stageWard = Ward::first();
        $stageStartDate = Carbon::today()->subMonth()->format("Y-m-d");
        $stageEndDate = Carbon::today()->subWeek()->format("Y-m-d");
        $stageAcademicYear = App::make("App\Services\AcademicYearService")->getFromDate($stageStartDate);

        $response =
            $this->actingAs($user)
                ->post(
                    route(
                        "compilations.store",
                        $this->getPayloadWithOnlyRequiredFields(
                            $user->student->id,
                            $stageLocation->id,
                            $stageWard->id,
                            $stageStartDate,
                            $stageEndDate,
                            $stageAcademicYear
                        )
                    )
                );

        $response->assertRedirect(route("compilations.show", ["compilation" => 1]));
        $response->assertSessionHas(
            EloquentModelObserver::FLASH_MESSAGE_KEY,
            __("The new compilation has been created")
        );
        $this->assertDatabaseHas("compilations", ["id" => 1]);
        $this->assertDatabaseMissing("compilations", ["id" => 2]);
        $this->assertDatabaseHas("compilation_items", ["id" => 1, "compilation_id" => 1]);
        $this->assertDatabaseHas("compilation_items", ["id" => self::NUMBER_OF_COMPILATION_ITEMS_ALWAYS_STORED, "compilation_id" => 1]);
        // Empty optional select box field is stored as NULL.
        $this->assertDatabaseHas("compilation_items", ["compilation_id" => 1, "question_id" => 7, "answer" => null]);
        // Empty optional checkbox field is stored as NULL.
        $this->assertDatabaseHas("compilation_items", ["compilation_id" => 1, "question_id" => 9, "answer" => null]);
        $this->assertDatabaseMissing("compilation_items", ["id" => self::NUMBER_OF_COMPILATION_ITEMS_ALWAYS_STORED + 1]);

    }

    /**
     * Failed creation: required field missing.
     */
    public function testMissingRequiredField()
    {

        $user = User::students()->first();
        $stageLocation = Location::first();
        $stageWard = Ward::first();
        $stageStartDate = Carbon::today()->subMonth()->format("Y-m-d");
        $stageEndDate = Carbon::today()->subWeek()->format("Y-m-d");
        $stageAcademicYear = App::make("App\Services\AcademicYearService")->getFromDate($stageStartDate);

        $fullData = $this->getPayloadWithAllFields(
            $user->student->id,
            $stageLocation->id,
            $stageWard->id,
            $stageStartDate,
            $stageEndDate,
            $stageAcademicYear
        );

        foreach (array_keys($fullData) as $keyToRemove) {

            $response =
                $this->actingAs($user)
                    ->post(
                        route(
                            "compilations.store",
                            array_filter(
                                $fullData,
                                function ($key) use ($keyToRemove) {
                                    return $key !== $keyToRemove;
                                },
                                ARRAY_FILTER_USE_KEY
                            )
                        )
                    );

            $response->assertSessionHasErrors([$keyToRemove]);
            $this->assertDatabaseMissing("compilations", ["id" => 1]);
            $this->assertDatabaseMissing("compilation_items", ["id" => 1]);

        }

    }

    /**
     * Failed creation: inexistent student.
     */
    public function testInexistentStudent()
    {

        $user = User::students()->first();
        $stageLocation = Location::first();
        $stageWard = Ward::first();
        $stageStartDate = Carbon::today()->subMonth()->format("Y-m-d");
        $stageEndDate = Carbon::today()->subWeek()->format("Y-m-d");
        $stageAcademicYear = App::make("App\Services\AcademicYearService")->getFromDate($stageStartDate);

        $response =
            $this->actingAs($user)
                ->post(
                    route("compilations.store",
                        $this->getPayloadWithAllFields(
                            $user->student->id + 1,
                            $stageLocation->id,
                            $stageWard->id,
                            $stageStartDate,
                            $stageEndDate,
                            $stageAcademicYear
                        )
                    )
                );

        $response->assertSessionHasErrors(["student_id"]);
        $this->assertDatabaseMissing("compilations", ["id" => 1]);
        $this->assertDatabaseMissing("compilation_items", ["id" => 1]);

    }

    /**
     * Failed creation: inexistent stage location.
     */
    public function testInexistentStageLocation()
    {

        $user = User::students()->first();
        $stageLocation = Location::first();
        $stageWard = Ward::first();
        $stageStartDate = Carbon::today()->subMonth()->format("Y-m-d");
        $stageEndDate = Carbon::today()->subWeek()->format("Y-m-d");
        $stageAcademicYear = App::make("App\Services\AcademicYearService")->getFromDate($stageStartDate);

        $response =
            $this->actingAs($user)
                ->post(
                    route("compilations.store",
                        $this->getPayloadWithAllFields(
                            $user->student->id,
                            $stageLocation->id + 1,
                            $stageWard->id,
                            $stageStartDate,
                            $stageEndDate,
                            $stageAcademicYear
                        )
                    )
                );

        $response->assertSessionHasErrors(["stage_location_id"]);
        $this->assertDatabaseMissing("compilations", ["id" => 1]);
        $this->assertDatabaseMissing("compilation_items", ["id" => 1]);

    }

    /**
     * Failed creation: inexistent stage ward.
     */
    public function testInexistentStageWard()
    {

        $user = User::students()->first();
        $stageLocation = Location::first();
        $stageWard = Ward::first();
        $stageStartDate = Carbon::today()->subMonth()->format("Y-m-d");
        $stageEndDate = Carbon::today()->subWeek()->format("Y-m-d");
        $stageAcademicYear = App::make("App\Services\AcademicYearService")->getFromDate($stageStartDate);

        $response =
            $this->actingAs($user)
                ->post(
                    route("compilations.store",
                        $this->getPayloadWithAllFields(
                            $user->student->id,
                            $stageLocation->id,
                            $stageWard->id + 1,
                            $stageStartDate,
                            $stageEndDate,
                            $stageAcademicYear
                        )
                    )
                );

        $response->assertSessionHasErrors(["stage_ward_id"]);
        $this->assertDatabaseMissing("compilations", ["id" => 1]);
        $this->assertDatabaseMissing("compilation_items", ["id" => 1]);

    }

    /**
     * Failed creation: switched stage dates.
     */
    public function testSwitchedStageDates()
    {

        $user = User::students()->first();
        $stageLocation = Location::first();
        $stageWard = Ward::first();
        $stageStartDate = Carbon::today()->subMonth()->format("Y-m-d");
        $stageEndDate = Carbon::today()->subWeek()->format("Y-m-d");
        $stageAcademicYear = App::make("App\Services\AcademicYearService")->getFromDate($stageStartDate);

        $response =
            $this->actingAs($user)
                ->post(
                    route("compilations.store",
                        $this->getPayloadWithAllFields(
                            $user->student->id,
                            $stageLocation->id,
                            $stageWard->id,
                            $stageEndDate,
                            $stageStartDate,
                            $stageAcademicYear
                        )
                    )
                );

        $response->assertSessionHasErrors(["stage_end_date"]);
        $this->assertDatabaseMissing("compilations", ["id" => 1]);
        $this->assertDatabaseMissing("compilation_items", ["id" => 1]);

    }

    /**
     * Failed creation: stage of too many weeks.
     */
    public function testTooManyStageWeeks()
    {

        $user = User::students()->first();
        $stageLocation = Location::first();
        $stageWard = Ward::first();
        $stageStartDate = Carbon::today()->subYear()->format("Y-m-d");
        $stageEndDate = Carbon::today()->subWeek()->format("Y-m-d");
        $stageAcademicYear = App::make("App\Services\AcademicYearService")->getFromDate($stageStartDate);

        $response =
            $this->actingAs($user)
                ->post(
                    route("compilations.store",
                        $this->getPayloadWithAllFields(
                            $user->student->id,
                            $stageLocation->id,
                            $stageWard->id,
                            $stageStartDate,
                            $stageEndDate,
                            $stageAcademicYear
                        )
                    )
                );

        $response->assertSessionHasErrors(["stage_end_date"]);
        $this->assertDatabaseMissing("compilations", ["id" => 1]);
        $this->assertDatabaseMissing("compilation_items", ["id" => 1]);

    }

    /**
     * Failed creation: invalid start date format.
     */
    public function testInvalidStartDateFormat()
    {

        $user = User::students()->first();
        $stageLocation = Location::first();
        $stageWard = Ward::first();
        $stageStartDate = "dffggfg";
        $stageEndDate = Carbon::today()->subWeek()->format("Y-m-d");
        $stageAcademicYear = App::make("App\Services\AcademicYearService")->getFromDate($stageEndDate);

        $response =
            $this->actingAs($user)
                ->post(
                    route("compilations.store",
                        $this->getPayloadWithAllFields(
                            $user->student->id,
                            $stageLocation->id,
                            $stageWard->id,
                            $stageStartDate,
                            $stageEndDate,
                            $stageAcademicYear
                        )
                    )
                );

        $response->assertSessionHasErrors(["stage_start_date"]);
        $this->assertDatabaseMissing("compilations", ["id" => 1]);
        $this->assertDatabaseMissing("compilation_items", ["id" => 1]);

    }

    /**
     * Failed creation: invalid academic year.
     */
    public function testInvalidAcademicYear()
    {

        $user = User::students()->first();
        $stageLocation = Location::first();
        $stageWard = Ward::first();
        $stageStartDate = Carbon::today()->subMonth()->format("Y-m-d");
        $stageEndDate = Carbon::today()->subWeek()->format("Y-m-d");
        $stageAcademicYear = App::make("App\Services\AcademicYearService")->getOther(-10);

        $response =
            $this->actingAs($user)
                ->post(
                    route("compilations.store",
                        $this->getPayloadWithAllFields(
                            $user->student->id,
                            $stageLocation->id,
                            $stageWard->id,
                            $stageStartDate,
                            $stageEndDate,
                            $stageAcademicYear
                        )
                    )
                );

        $response->assertSessionHasErrors(["stage_academic_year"]);
        $this->assertDatabaseMissing("compilations", ["id" => 1]);
        $this->assertDatabaseMissing("compilation_items", ["id" => 1]);

    }

    /**
     * Failed creation: overlapping stage date range.
     */
    public function testOverlappingStageDateRange()
    {

        $user = User::students()->first();
        $stageLocation = Location::first();
        $stageWard = Ward::first();

        // First compilation.
        $stageStartDate = Carbon::today()->subDays(40)->format("Y-m-d");
        $stageEndDate = Carbon::today()->subDays(20)->format("Y-m-d");
        $stageAcademicYear = App::make("App\Services\AcademicYearService")->getFromDate($stageStartDate);

        $response =
            $this->actingAs($user)
                ->post(
                    route("compilations.store",
                        $this->getPayloadWithAllFields(
                            $user->student->id,
                            $stageLocation->id,
                            $stageWard->id,
                            $stageStartDate,
                            $stageEndDate,
                            $stageAcademicYear
                        )
                    )
                );

        $response->assertRedirect(route("compilations.show", ["compilation" => 1]));
        $response->assertSessionHas(
            EloquentModelObserver::FLASH_MESSAGE_KEY,
            __("The new compilation has been created")
        );
        $this->assertDatabaseHas("compilations", ["id" => 1]);
        $this->assertDatabaseMissing("compilations", ["id" => 2]);
        $this->assertDatabaseHas("compilation_items", ["id" => 1, "compilation_id" => 1]);
        $this->assertDatabaseHas("compilation_items", ["id" => self::NUMBER_OF_COMPILATION_ITEMS, "compilation_id" => 1]);
        $this->assertDatabaseMissing("compilation_items", ["id" => self::NUMBER_OF_COMPILATION_ITEMS + 1]);

        // Following compilations, with overlapping date ranges.
        $data = [

            [
                "stage_start_date" => Carbon::today()->subDays(50)->format("Y-m-d"),
                "stage_end_date" => Carbon::today()->subDays(40)->format("Y-m-d"),
            ],
            [
                "stage_start_date" => Carbon::today()->subDays(50)->format("Y-m-d"),
                "stage_end_date" => Carbon::today()->subDays(30)->format("Y-m-d"),
            ],
            [
                "stage_start_date" => Carbon::today()->subDays(40)->format("Y-m-d"),
                "stage_end_date" => Carbon::today()->subDays(30)->format("Y-m-d"),
            ],
            [
                "stage_start_date" => Carbon::today()->subDays(35)->format("Y-m-d"),
                "stage_end_date" => Carbon::today()->subDays(30)->format("Y-m-d"),
            ],
            [
                "stage_start_date" => Carbon::today()->subDays(30)->format("Y-m-d"),
                "stage_end_date" => Carbon::today()->subDays(20)->format("Y-m-d"),
            ],
            [
                "stage_start_date" => Carbon::today()->subDays(30)->format("Y-m-d"),
                "stage_end_date" => Carbon::today()->subDays(10)->format("Y-m-d"),
            ],
            [
                "stage_start_date" => Carbon::today()->subDays(20)->format("Y-m-d"),
                "stage_end_date" => Carbon::today()->subDays(10)->format("Y-m-d"),
            ],

        ];

        foreach ($data as $datum) {

            $response =
                $this->actingAs($user)
                    ->post(
                        route("compilations.store",
                            $this->getPayloadWithAllFields(
                                $user->student->id,
                                $stageLocation->id,
                                $stageWard->id,
                                $datum["stage_start_date"],
                                $datum["stage_end_date"],
                                App::make("App\Services\AcademicYearService")->getFromDate($datum["stage_start_date"])
                            )
                        )
                    );

            $response->assertSessionHasErrors(["stage_start_date", "stage_end_date"]);
            $this->assertDatabaseMissing("compilations", ["id" => 2]);
            $this->assertDatabaseMissing("compilation_items", ["id" => self::NUMBER_OF_COMPILATION_ITEMS + 1]);

        }

    }

}
