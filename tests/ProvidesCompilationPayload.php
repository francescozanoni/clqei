<?php
declare(strict_types = 1);

namespace Tests;

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
    ) : array
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

        $payload['q6'] = '104';
        unset($payload['q7']);
        $payload['q8'] = '110';
        $payload['q9'] = null;

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
    ) : array
    {

        return [
            'student_id' => (string)$studentId,
            'stage_location_id' => (string)$stageLocationId,
            'stage_ward_id' => (string)$stageWardId,
            'stage_start_date' => $stageStartDate,
            'stage_end_date' => $stageEndDate,
            'stage_academic_year' => $stageAcademicYear,
            'q1' => '1',
            'q2' => '84',
            'q3' => '87',
            'q4' => '89',
            'q5' => '91',
            'q6' => '103',
            'q7' => [
                '105',
                '106',
                '107',
                '108'
            ],
            'q8' => '109',
            'q9' => '111',
            'q10' => '142',
            'q11' => '147',
            'q12' => '151',
            'q13' => '155',
            'q14' => '159',
            'q15' => '163',
            'q16' => '167',
            'q17' => '171',
            'q18' => '175',
            'q19' => '179',
            'q20' => '183',
            'q21' => '187',
            'q22' => '191',
            'q23' => '195',
            'q24' => '199',
            'q25' => '203',
            'q26' => '207',
            'q27' => '211',
            'q28' => '215',
            'q29' => '219',
            'q30' => '223',
            'q31' => '227',
            'q32' => '231',
            'q33' => '235'
        ];

    }

}
