<?php
declare(strict_types = 1);

namespace App\Services;

use App\Models\Compilation;
use App\Models\Location;
use App\Models\Ward;
use Illuminate\Support\Facades\App;

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
     * Get compilation statistics, only stage-related data.
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
     *                    )
     */
    public function getStageStatistics() : array
    {

        $compilations =
            Compilation
                ::with([
                    // Deleted locations and wards are included by default via model relatioships
                    'stageLocation',
                    'stageWard',
                ])
                ->get();

        $statistics = [
            'stageLocations' => [],
            'stageWards' => [],
            'stageAcademicYears' => [],
            'stageWeeks' => [],
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
        }

        ksort($statistics['stageLocations'], SORT_NATURAL);
        ksort($statistics['stageWards'], SORT_NATURAL);
        ksort($statistics['stageAcademicYears'], SORT_NATURAL);
        ksort($statistics['stageWeeks'], SORT_NATURAL);

        return $statistics;

    }

    /**
     * Get compilation statistics, only student-related data.
     *
     * @return array e.g. Array (
     *                      [studentGenders] => Array (
     *                        [male] => 3
     *                      )
     *                      [studentNationalities] => Array (
     *                        [IT] => 3
     *                      )
     *                    )
     */
    public function getStudentStatistics() : array
    {

        $compilations =
            Compilation
                ::with([
                    // Deleted students are included by default via model relatioships
                    'student'
                ])
                ->get();

        $statistics = [
            'studentGenders' => [],
            'studentNationalities' => [],
        ];

        foreach ($compilations as $compilation) {

            if (isset($statistics['studentGenders'][__($compilation->student->gender)]) === false) {
                $statistics['studentGenders'][__($compilation->student->gender)] = 0;
            }
            $statistics['studentGenders'][__($compilation->student->gender)]++;

            $countries = App::make('App\Services\CountryService')->getCountries();
            if (isset($statistics['studentNationalities'][$countries[$compilation->student->nationality]]) === false) {
                $statistics['studentNationalities'][$countries[$compilation->student->nationality]] = 0;
            }
            $statistics['studentNationalities'][$countries[$compilation->student->nationality]]++;
        }

        ksort($statistics['studentGenders'], SORT_NATURAL);
        ksort($statistics['studentNationalities'], SORT_NATURAL);

        return $statistics;

    }

}
