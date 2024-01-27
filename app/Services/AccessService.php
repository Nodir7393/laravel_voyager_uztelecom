<?php

namespace App\Services;

use App\Models\Application;
use App\Models\ApplicationSigners;

class AccessService
{
    public static function allowed(Application $application) : bool
    {
        $app_roles = ApplicationSigners::where('application_id', $application->id)->get()->pluck('role_id')->toArray();
        $access = in_array(auth()->user()->role_id, $app_roles);
        return $access;
    }
}