<?php

namespace App\Traits;

use Log;
use Auth;

trait RequiresPermission {

    function requiresPermission($permission) {
        Log::info("User " . Auth::user()->getDisplayName() . " requires permission to do $permission on target " . get_class($this));
        $result = $this->askPermission(Auth::user(), $permission);
        return $result;
    }

    function askPermission($user, $permission) {
        //Ask parent GPO if you have, in that case overload method.
        //If you do not have anyone else to ask, return false.
        return $this->checkPermission($permission, $this->getPermissions($user));
    }

    function checkPermission($needle, $permissions) {
        return $permissions->contains($needle);
    }

    function getPermissions($user) {
        return null;
    }
}
