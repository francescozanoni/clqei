<?php
declare(strict_types=1);

namespace Tests;

use App\Models\Answer;

trait ProvidesCompilationPayload
{

    /**
     * Compilation creation payload, with only required fields populated.
     *
     * @param int $studentId
     * @param int $stageLocationId
     * @param int $stageWardId
     * @param string $stageStartDate
     * @param string $stageEndDate
     * @param string $stageAcademicYear
     * @return array
     */
    protected function getPayloadWithOnlyRequiredFields(
        int $studentId,
        int $stageLocationId,
        int $stageWardId,
        string $stageStartDate,
        string $stageEndDate,
        string $stageAcademicYear
    ): array
    {
        $payload =
            $this->getPayloadWithAllFields(
                $studentId,
                $stageLocationId,
                $stageWardId,
                $stageStartDate,
                $stageEndDate,
                $stageAcademicYear
            );

        $payload["q6"] = "104";
        unset($payload["q7"]);
        $payload["q8"] = "110";
        $payload["q9"] = null;

        return $payload;
    }

    /**
     * Compilation creation payload, with all fields populated.
     *
     * @param int $studentId
     * @param int $stageLocationId
     * @param int $stageWardId
     * @param string $stageStartDate
     * @param string $stageEndDate
     * @param string $stageAcademicYear
     * @return array
     */
    protected function getPayloadWithAllFields(
        int $studentId,
        int $stageLocationId,
        int $stageWardId,
        string $stageStartDate,
        string $stageEndDate,
        string $stageAcademicYear
    ): array
    {

        return [
            "student_id" => (string)$studentId,
            "stage_location_id" => (string)$stageLocationId,
            "stage_ward_id" => (string)$stageWardId,
            "stage_start_date" => $stageStartDate,
            "stage_end_date" => $stageEndDate,
            "stage_academic_year" => $stageAcademicYear,
            "q1" => "1",
            "q2" => "84",
            "q3" => "87",
            "q4" => "89",
            "q5" => "91",
            "q6" => "103",
            "q7" => [
                "105",
                "106",
                "107",
                "108"
            ],
            "q8" => "109",
            "q9" => "111",
            "q10" => "114",
            "q11" => "119",
            "q12" => "123",
            "q13" => "127",
            "q14" => "131",
            "q15" => "135",
            "q16" => "139",
            "q17" => "143",
            "q18" => "147",
            "q19" => "151",
            "q20" => "155",
            "q21" => "159",
            "q22" => "163",
            "q23" => "167",
            "q24" => "171",
            "q25" => "175",
            "q26" => "179",
            "q27" => "183",
            "q28" => "187",
            "q29" => "191",
            "q30" => "195",
            "q31" => "199",
            "q32" => "203",
            "q33" => "207"
        ];

    }

}
