<?php
declare(strict_types = 1);

namespace App\Services;

use App\Models\Compilation;
use App\Models\Location;
use App\Models\Ward;

class CompilationService
{

    /**
     * Detect whether all environment requirements
     * to create a compilation are met
     *
     * @return bool
     */
    public function isCompilationCreatable() : bool
    {
        return
            Location::count() > 0 &&
            Ward::count() > 0;
    }

    /**
     * Get compilation statistics.
     *
     * @return array e.g. Array (
     *                      [stageLocations] => Array (
     *                        [sede 2] => 1
     *                        [sede 3] => 2
     *                      )
     *                      [stageWards] => Array (
     *                        [reparto 3] => 1
     *                        [reparto 2] => 2
     *                      )
     *                      [stageAcademicYears] => Array (
     *                        [2016/2017] => 2
     *                        [2017/2018] => 1
     *                      )
     *                      [stageWeeks] => Array (
     *                        [1] => 3
     *                      )
     *                      [studentGenders] => Array (
     *                        [male] => 3
     *                      )
     *                      [studentNationalities] => Array (
     *                        [IT] => 3
     *                      )
     *                    )
     */
    public function getStatistics() : array
    {

        $compilations =
            Compilation
                ::with([
                    // Deleted locations, wards and students are included by default via model relatioships
                    'stageLocation',
                    'stageWard',
                    'student'
                ])
                ->get();

        $statistics = [
            'stageLocations' => [],
            'stageWards' => [],
            'stageAcademicYears' => [],
            'stageWeeks' => [],
            'studentGenders' => [],
            'studentNationalities' => [],
        ];

        foreach ($compilations as $compilation) {

            if (isset($statistics['stageLocations'][$compilation->stageLocation->name]) === false) {
                $statistics['stageLocations'][$compilation->stageLocation->name] = 0;
            }
            $statistics['stageLocations'][$compilation->stageLocation->name]++;

            if (isset($statistics['stageWards'][$compilation->stageWard->name]) === false) {
                $statistics['stageWards'][$compilation->stageWard->name] = 0;
            }
            $statistics['stageWards'][$compilation->stageWard->name]++;

            if (isset($statistics['stageAcademicYears'][$compilation->stage_academic_year]) === false) {
                $statistics['stageAcademicYears'][$compilation->stage_academic_year] = 0;
            }
            $statistics['stageAcademicYears'][$compilation->stage_academic_year]++;

            if (isset($statistics['stageWeeks'][$compilation->stage_weeks]) === false) {
                $statistics['stageWeeks'][$compilation->stage_weeks] = 0;
            }
            $statistics['stageWeeks'][$compilation->stage_weeks]++;

            if (isset($statistics['studentGenders'][$compilation->student->gender]) === false) {
                $statistics['studentGenders'][$compilation->student->gender] = 0;
            }
            $statistics['studentGenders'][$compilation->student->gender]++;

            if (isset($statistics['studentNationalities'][$compilation->student->nationality]) === false) {
                $statistics['studentNationalities'][$compilation->student->nationality] = 0;
            }
            $statistics['studentNationalities'][$compilation->student->nationality]++;
        }

        return $statistics;

    }

}
