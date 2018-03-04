<?php
declare(strict_types = 1);

namespace Tests\Feature;

use App;
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
        $stageStartDate = Carbon::today()->subMonth()->format('Y-m-d');
        $stageEndDate = Carbon::today()->subWeek()->format('Y-m-d');
        $stageAcademicYear = App::make('App\Services\AcademicYearService')->getFromDate($stageStartDate);

        $response =
            $this->actingAs($user)
                ->post(
                    route('compilations.store',
                    json_decode(
                        '{
                          "student_id": "1",
                          "stage_location_id": "1",
                          "stage_ward_id": "1",
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
        $response->assertStatus(302);
        $this->assertDatabaseHas('compilations', ['id' => 1]);
        
    }
    
    /**
     * Successful creation: only required fields.
     */
    public function testSuccessOnlyRequiredFields()
    {

        $user = User::students()->first();
        $stageStartDate = Carbon::today()->subMonth()->format('Y-m-d');
        $stageEndDate = Carbon::today()->subWeek()->format('Y-m-d');
        $stageAcademicYear = App::make('App\Services\AcademicYearService')->getFromDate($stageStartDate);

        $response =
            $this->actingAs($user)
                ->post(
                    route('compilations.store',
                    json_decode(
                        '{
                          "student_id": "1",
                          "stage_location_id": "1",
                          "stage_ward_id": "1",
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
        $response->assertStatus(302);
        $this->assertDatabaseHas('compilations', ['id' => 1]);
        
    }

}
