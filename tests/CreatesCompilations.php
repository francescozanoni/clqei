<?php

declare(strict_types = 1);

namespace Tests;

use App;
use App\Models\Compilation;
use App\Models\Location;
use App\Models\Ward;
use App\User;
use Carbon\Carbon;

trait CreatesCompilations
{

    use ProvidesCompilationPayload;

    /**
     * @return Compilation
     */
    public function createAndGetCompilation() : Compilation
    {

        $student = User::students()->first();
        $stageLocation = Location::first();
        $stageWard = Ward::first();
        $stageStartDate = Carbon::today()->subMonth()->format("Y-m-d");
        $stageEndDate = Carbon::today()->subWeek()->format("Y-m-d");
        $stageAcademicYear = App::make("App\Services\AcademicYearService")->getFromDate($stageStartDate);

        $this->actingAs($student)
            ->post(
                route("compilations.store",
                    $this->getPayloadWithAllFields(
                        $student->student->id,
                        $stageLocation->id,
                        $stageWard->id,
                        $stageStartDate,
                        $stageEndDate,
                        $stageAcademicYear
                    )
                )
            );

        return Compilation::latest()->first();

    }

}