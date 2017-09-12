<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

use Log;
use Auth;

trait RequiresPermission {

    /**
     * Called whenever a permission is required, checking if the current user has this permission.
     * Typically the first method in a permission lookup, often called after being intercepted by an Advice.
     * @param  String $permission The permission required.
     * @return Boolean            True if granted, false otherwise
     */
    function requiresPermission($permission) {
        Log::info("User (" . Auth::user()->getDisplayName() . ") requires permission to do ($permission) on target (" . get_class($this) . ")");
        $result = $this->askPermission(Auth::user(), $permission);
        return $result;
    }

    /**
     * Asks for a permission for a user.
     * First checks if the object itself can grant this permission, if not it will try to look up  the permission.
     * @param  User     $user       The user asking permission
     * @param  String   $permission The permission to ask
     * @return Boolean              True if granted, false otherwise
     */
    function askPermission($user, $permission, $cascading = false) {
        //First check if the object can allow the action on his own.
        if ($cascading) {
            if ($this->checkPermission($permission, $this->getCascadingUserPermissions($user))) {
                //This object can grant this (cascading) permission by itself
                return true;
            }
        } else {
            if ($this->checkPermission($permission, $this->getUserPermissions($user))) {
                //This object can grant this permission by itself
                return true;
            }
        }
        //This object cannot provide this, ask if some delegate can
        foreach ($this->getAuthorizationDelegates() as $parent) {
            Log::debug("Asking ($permission) to authorization delegate: ($parent)");
            if ($parent->askPermission($user, $permission, true)) {
                return true;
            }
        }
        //If none could give access, return false.
        return false;
    }

    /**
     * Checks a set of permissions for the permissions needed
     * @param  String $needle      Permision required
     * @param  String $permissions Permissions granted
     * @return Boolean             True if the searched permission is found
     */
    function checkPermission($needle, $permissions) {
        return $permissions->contains($needle);
    }

    /**
     * Asks the permissions this object gives for a User. Typically overriden or intercepted by an Advice.
     * @param  User        $user The user for which the permissions are asked
     * @return Collection        A collection of permissions the user has over this object
     */
    function getUserPermissions($user) {
        return null;
    }

    /**
     * Asks the permissions this object gives for a User over ANOTHER object.
     * When object X allows object Y to grant permissions on X, this method is called on Y to determine what a user can do on X.
     * Typically overriden or intercepted by an Advice.
     * @param  User        $user The user for which the permissions are asked
     * @return Collection        A collection of permissions the user has over this object
     */
    function getCascadingUserPermissions($user) {
        return null;
    }

    /**
     * Asks for objects (delegates) that are allowed to authorize permissions over this object.
     * @return Collection A collection of objects that can delegate permissions over this object.
     */
    function getAuthorizationDelegates() {
        return [];
    }
}
