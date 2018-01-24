<?php
declare(strict_types = 1);

namespace App\Services;

use App\Models\Location;
use App\Models\Ward;
use App\User;

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
            User::students()->count() > 0 &&
            Location::count() > 0 &&
            Ward::count() > 0;
    }

}
