<?php
declare(strict_types = 1);

namespace App\Services;

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
     * Flags used by query builder/Eloquent
     * to trigger all thrashed model loading.
     *
     * @return array
     */
    public function allTrashedRelatedModels() : array
    {

        return [
            'stageLocation' => function ($query) {
                $query->withTrashed();
            },
            'stageWard' => function ($query) {
                $query->withTrashed();
            },
            'student' => function ($query) {
                $query->withTrashed();
            },
            'student.user' => function ($query) {
                $query->withTrashed();
            }
        ];

    }

}
