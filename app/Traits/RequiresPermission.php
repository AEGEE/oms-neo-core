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
        //First check if the object can allow the action on his own.
        if ($this->checkPermission($permission, $this->getUserPermissions($user))) {
            return true;
        } else {
            //If not, allow parents to give the access.
            foreach ($this->getGrantingParents() as $parent) {
                if ($parent->askPermission($user, $permission)) {
                    return true;
                }
            }
        }
        //If none could give access, return false.
        return false;
    }

    function checkPermission($needle, $permissions) {
        return $permissions->contains($needle);
    }

    function getUserPermissions($user) {
        return null;
    }

    function getGrantingParents() {
        return [];
    }
}
