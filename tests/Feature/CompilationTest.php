<?php
declare(strict_types = 1);

namespace Tests\Feature;

use App;
use App\Models\Location;
use App\Models\Ward;
use App\Observers\ModelObserver;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CompilationTest extends TestCase
{

    use RefreshDatabase;

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
        $stageWard = ward::first();
        $stageStartDate = Carbon::today()->subMonth()->format('Y-m-d');
        $stageEndDate = Carbon::today()->subWeek()->format('Y-m-d');
        $stageAcademicYear = App::make('App\Services\AcademicYearService')->getFromDate($stageStartDate);

        $response =
            $this->actingAs($user)
                ->post(
                    route('compilations.store',
                        json_decode(
                            '{
                              "student_id": "' . $user->student->id . '",
                              "stage_location_id": "' . $stageLocation->id . '",
                              "stage_ward_id": "' . $stageWard->id . '",
                              "stage_start_date": "' . $stageStartDate . '",
                              "stage_end_date": "' . $stageEndDate . '",
                              "stage_academic_year": "' . $stageAcademicYear . '",
                              "q1": "1",
                              "q2": "84",
                              "q3": "87",
                              "q4": "89",
                              "q5": "91",
                              "q6": "103",
                              "q7": [
                                "105",
                                "106",
                                "107",
                                "108"
                              ],
                              "q8": "109",
                              "q9": "111",
                              "q10": "142",
                              "q11": "147",
                              "q12": "151",
                              "q13": "155",
                              "q14": "159",
                              "q15": "163",
                              "q16": "167",
                              "q17": "171",
                              "q18": "175",
                              "q19": "179",
                              "q20": "183",
                              "q21": "187",
                              "q22": "191",
                              "q23": "195",
                              "q24": "199",
                              "q25": "203",
                              "q26": "207",
                              "q27": "211",
                              "q28": "215",
                              "q29": "219",
                              "q30": "223",
                              "q31": "227",
                              "q32": "231",
                              "q33": "235"
                            }',
                            true
                        )
                    )
                );

        $response->assertRedirect(route('compilations.show', ['compilation' => 1]));
        $response->assertSessionHas(ModelObserver::FLASH_MESSAGE_KEY, __('The new compilation has been created'));
        $this->assertDatabaseHas('compilations', ['id' => 1]);
        $this->assertDatabaseMissing('compilations', ['id' => 2]);
        $this->assertDatabaseHas('compilation_items', ['id' => 1, 'compilation_id' => 1]);
        $this->assertDatabaseHas('compilation_items', ['id' => 36, 'compilation_id' => 1]);
        $this->assertDatabaseMissing('compilation_items', ['id' => 37]);

    }

    /**
     * Successful creation: only required fields.
     */
    public function testSuccessOnlyRequiredFields()
    {

        $user = User::students()->first();
        $stageLocation = Location::first();
        $stageWard = ward::first();
        $stageStartDate = Carbon::today()->subMonth()->format('Y-m-d');
        $stageEndDate = Carbon::today()->subWeek()->format('Y-m-d');
        $stageAcademicYear = App::make('App\Services\AcademicYearService')->getFromDate($stageStartDate);

        $response =
            $this->actingAs($user)
                ->post(
                    route(
                        'compilations.store',
                        json_decode(
                            '{
                              "student_id": "' . $user->student->id . '",
                              "stage_location_id": "' . $stageLocation->id . '",
                              "stage_ward_id": "' . $stageWard->id . '",
                              "stage_start_date": "' . $stageStartDate . '",
                              "stage_end_date": "' . $stageEndDate . '",
                              "stage_academic_year": "' . $stageAcademicYear . '",
                              "q1": "1",
                              "q2": "84",
                              "q3": "87",
                              "q4": "89",
                              "q5": "91",
                              "q6": "104",
                              "q8": "110",
                              "q9": null,
                              "q10": "142",
                              "q11": "147",
                              "q12": "151",
                              "q13": "155",
                              "q14": "159",
                              "q15": "163",
                              "q16": "167",
                              "q17": "171",
                              "q18": "175",
                              "q19": "179",
                              "q20": "183",
                              "q21": "187",
                              "q22": "191",
                              "q23": "195",
                              "q24": "199",
                              "q25": "203",
                              "q26": "207",
                              "q27": "211",
                              "q28": "215",
                              "q29": "219",
                              "q30": "223",
                              "q31": "227",
                              "q32": "231",
                              "q33": "235",
                              "q7": null
                            }',
                            true
                        )
                    )
                );

        $response->assertRedirect(route('compilations.show', ['compilation' => 1]));
        $response->assertSessionHas(ModelObserver::FLASH_MESSAGE_KEY, __('The new compilation has been created'));
        $this->assertDatabaseHas('compilations', ['id' => 1]);
        $this->assertDatabaseMissing('compilations', ['id' => 2]);
        $this->assertDatabaseHas('compilation_items', ['id' => 1, 'compilation_id' => 1]);
        $this->assertDatabaseHas('compilation_items', ['id' => 33, 'compilation_id' => 1]);
        // Empty optional select box field is stored as NULL.
        $this->assertDatabaseHas('compilation_items', ['compilation_id' => 1, 'question_id' => 7, 'answer' => null]);
        // Empty optional checkbox field is stored as NULL.
        $this->assertDatabaseHas('compilation_items', ['compilation_id' => 1, 'question_id' => 9, 'answer' => null]);
        $this->assertDatabaseMissing('compilation_items', ['id' => 34]);

    }

    /**
     * Failed creation: required field missing.
     */
    public function testMissingRequiredField()
    {

        $user = User::students()->first();
        $stageLocation = Location::first();
        $stageWard = ward::first();
        $stageStartDate = Carbon::today()->subMonth()->format('Y-m-d');
        $stageEndDate = Carbon::today()->subWeek()->format('Y-m-d');
        $stageAcademicYear = App::make('App\Services\AcademicYearService')->getFromDate($stageStartDate);

        $fullData = json_decode(
            '{
              "student_id": "' . $user->student->id . '",
              "stage_location_id": "' . $stageLocation->id . '",
              "stage_ward_id": "' . $stageWard->id . '",
              "stage_start_date": "' . $stageStartDate . '",
              "stage_end_date": "' . $stageEndDate . '",
              "stage_academic_year": "' . $stageAcademicYear . '",
              "q1": "1",
              "q2": "84",
              "q3": "87",
              "q4": "89",
              "q5": "91",
              "q6": "103",
              "q7": [
                "105",
                "106",
                "107",
                "108"
              ],
              "q8": "109",
              "q9": "111",
              "q10": "142",
              "q11": "147",
              "q12": "151",
              "q13": "155",
              "q14": "159",
              "q15": "163",
              "q16": "167",
              "q17": "171",
              "q18": "175",
              "q19": "179",
              "q20": "183",
              "q21": "187",
              "q22": "191",
              "q23": "195",
              "q24": "199",
              "q25": "203",
              "q26": "207",
              "q27": "211",
              "q28": "215",
              "q29": "219",
              "q30": "223",
              "q31": "227",
              "q32": "231",
              "q33": "235"
            }',
            true
        );

        foreach (array_keys($fullData) as $keyToRemove) {

            $response =
                $this->actingAs($user)
                    ->post(
                        route(
                            'compilations.store',
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
            $this->assertDatabaseMissing('compilations', ['id' => 1]);
            $this->assertDatabaseMissing('compilation_items', ['id' => 1]);

        }

    }

    /**
     * Failed creation: inexistent student.
     */
    public function testInexistentStudent()
    {

        $user = User::students()->first();
        $stageLocation = Location::first();
        $stageWard = ward::first();
        $stageStartDate = Carbon::today()->subMonth()->format('Y-m-d');
        $stageEndDate = Carbon::today()->subWeek()->format('Y-m-d');
        $stageAcademicYear = App::make('App\Services\AcademicYearService')->getFromDate($stageStartDate);

        $response =
            $this->actingAs($user)
                ->post(
                    route('compilations.store',
                        json_decode(
                            '{
                              "student_id": "2",
                              "stage_location_id": "' . $stageLocation->id . '",
                              "stage_ward_id": "' . $stageWard->id . '",
                              "stage_start_date": "' . $stageStartDate . '",
                              "stage_end_date": "' . $stageEndDate . '",
                              "stage_academic_year": "' . $stageAcademicYear . '",
                              "q1": "1",
                              "q2": "84",
                              "q3": "87",
                              "q4": "89",
                              "q5": "91",
                              "q6": "103",
                              "q7": [
                                "105",
                                "106",
                                "107",
                                "108"
                              ],
                              "q8": "109",
                              "q9": "111",
                              "q10": "142",
                              "q11": "147",
                              "q12": "151",
                              "q13": "155",
                              "q14": "159",
                              "q15": "163",
                              "q16": "167",
                              "q17": "171",
                              "q18": "175",
                              "q19": "179",
                              "q20": "183",
                              "q21": "187",
                              "q22": "191",
                              "q23": "195",
                              "q24": "199",
                              "q25": "203",
                              "q26": "207",
                              "q27": "211",
                              "q28": "215",
                              "q29": "219",
                              "q30": "223",
                              "q31": "227",
                              "q32": "231",
                              "q33": "235"
                            }',
                            true
                        )
                    )
                );

        $response->assertSessionHasErrors(['student_id']);
        $this->assertDatabaseMissing('compilations', ['id' => 1]);
        $this->assertDatabaseMissing('compilation_items', ['id' => 1]);

    }

    /**
     * Failed creation: inexistent stage location.
     */
    public function testInexistentStageLocation()
    {

        $user = User::students()->first();
        $stageLocation = Location::first();
        $stageWard = ward::first();
        $stageStartDate = Carbon::today()->subMonth()->format('Y-m-d');
        $stageEndDate = Carbon::today()->subWeek()->format('Y-m-d');
        $stageAcademicYear = App::make('App\Services\AcademicYearService')->getFromDate($stageStartDate);

        $response =
            $this->actingAs($user)
                ->post(
                    route('compilations.store',
                        json_decode(
                            '{
                              "student_id": "' . $user->student->id . '",
                              "stage_location_id": "2",
                              "stage_ward_id": "' . $stageWard->id . '",
                              "stage_start_date": "' . $stageStartDate . '",
                              "stage_end_date": "' . $stageEndDate . '",
                              "stage_academic_year": "' . $stageAcademicYear . '",
                              "q1": "1",
                              "q2": "84",
                              "q3": "87",
                              "q4": "89",
                              "q5": "91",
                              "q6": "103",
                              "q7": [
                                "105",
                                "106",
                                "107",
                                "108"
                              ],
                              "q8": "109",
                              "q9": "111",
                              "q10": "142",
                              "q11": "147",
                              "q12": "151",
                              "q13": "155",
                              "q14": "159",
                              "q15": "163",
                              "q16": "167",
                              "q17": "171",
                              "q18": "175",
                              "q19": "179",
                              "q20": "183",
                              "q21": "187",
                              "q22": "191",
                              "q23": "195",
                              "q24": "199",
                              "q25": "203",
                              "q26": "207",
                              "q27": "211",
                              "q28": "215",
                              "q29": "219",
                              "q30": "223",
                              "q31": "227",
                              "q32": "231",
                              "q33": "235"
                            }',
                            true
                        )
                    )
                );

        $response->assertSessionHasErrors(['stage_location_id']);
        $this->assertDatabaseMissing('compilations', ['id' => 1]);
        $this->assertDatabaseMissing('compilation_items', ['id' => 1]);

    }

    /**
     * Failed creation: inexistent stage ward.
     */
    public function testInexistentStageWard()
    {

        $user = User::students()->first();
        $stageLocation = Location::first();
        $stageWard = ward::first();
        $stageStartDate = Carbon::today()->subMonth()->format('Y-m-d');
        $stageEndDate = Carbon::today()->subWeek()->format('Y-m-d');
        $stageAcademicYear = App::make('App\Services\AcademicYearService')->getFromDate($stageStartDate);

        $response =
            $this->actingAs($user)
                ->post(
                    route('compilations.store',
                        json_decode(
                            '{
                              "student_id": "' . $user->student->id . '",
                              "stage_location_id": "' . $stageLocation->id . '",
                              "stage_ward_id": "2",
                              "stage_start_date": "' . $stageStartDate . '",
                              "stage_end_date": "' . $stageEndDate . '",
                              "stage_academic_year": "' . $stageAcademicYear . '",
                              "q1": "1",
                              "q2": "84",
                              "q3": "87",
                              "q4": "89",
                              "q5": "91",
                              "q6": "103",
                              "q7": [
                                "105",
                                "106",
                                "107",
                                "108"
                              ],
                              "q8": "109",
                              "q9": "111",
                              "q10": "142",
                              "q11": "147",
                              "q12": "151",
                              "q13": "155",
                              "q14": "159",
                              "q15": "163",
                              "q16": "167",
                              "q17": "171",
                              "q18": "175",
                              "q19": "179",
                              "q20": "183",
                              "q21": "187",
                              "q22": "191",
                              "q23": "195",
                              "q24": "199",
                              "q25": "203",
                              "q26": "207",
                              "q27": "211",
                              "q28": "215",
                              "q29": "219",
                              "q30": "223",
                              "q31": "227",
                              "q32": "231",
                              "q33": "235"
                            }',
                            true
                        )
                    )
                );

        $response->assertSessionHasErrors(['stage_ward_id']);
        $this->assertDatabaseMissing('compilations', ['id' => 1]);
        $this->assertDatabaseMissing('compilation_items', ['id' => 1]);

    }

    /**
     * Failed creation: switched stage dates.
     */
    public function testSwitchedStageDates()
    {

        $user = User::students()->first();
        $stageLocation = Location::first();
        $stageWard = ward::first();
        $stageStartDate = Carbon::today()->subMonth()->format('Y-m-d');
        $stageEndDate = Carbon::today()->subWeek()->format('Y-m-d');
        $stageAcademicYear = App::make('App\Services\AcademicYearService')->getFromDate($stageStartDate);

        $response =
            $this->actingAs($user)
                ->post(
                    route('compilations.store',
                        json_decode(
                            '{
                              "student_id": "' . $user->student->id . '",
                              "stage_location_id": "' . $stageLocation->id . '",
                              "stage_ward_id": "' . $stageWard->id . '",
                              "stage_start_date": "' . $stageEndDate . '",
                              "stage_end_date": "' . $stageStartDate . '",
                              "stage_academic_year": "' . $stageAcademicYear . '",
                              "q1": "1",
                              "q2": "84",
                              "q3": "87",
                              "q4": "89",
                              "q5": "91",
                              "q6": "103",
                              "q7": [
                                "105",
                                "106",
                                "107",
                                "108"
                              ],
                              "q8": "109",
                              "q9": "111",
                              "q10": "142",
                              "q11": "147",
                              "q12": "151",
                              "q13": "155",
                              "q14": "159",
                              "q15": "163",
                              "q16": "167",
                              "q17": "171",
                              "q18": "175",
                              "q19": "179",
                              "q20": "183",
                              "q21": "187",
                              "q22": "191",
                              "q23": "195",
                              "q24": "199",
                              "q25": "203",
                              "q26": "207",
                              "q27": "211",
                              "q28": "215",
                              "q29": "219",
                              "q30": "223",
                              "q31": "227",
                              "q32": "231",
                              "q33": "235"
                            }',
                            true
                        )
                    )
                );

        $response->assertSessionHasErrors(['stage_end_date']);
        $this->assertDatabaseMissing('compilations', ['id' => 1]);
        $this->assertDatabaseMissing('compilation_items', ['id' => 1]);

    }

}
