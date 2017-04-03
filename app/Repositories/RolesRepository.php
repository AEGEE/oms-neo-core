<?php

namespace App\Repositories;

use App\Models\Member;
use App\Models\Role;
use App\Models\SimpleRole;

class RolesRepository {

  public static function syncRolesForAll($models, $source) {
    $models->map(function($model, $key) use ($source) { $model->syncRoles($source); });
  }

  public static function getRoles($source, $globalRoles, $target) {
    //return array("super_admin");
    $scopedRoles = self::getScopedRoles($source, $globalRoles, $target);
    return array_merge($globalRoles, $scopedRoles);
  }

  public static function getGlobalRoles($user) {
    if (!$user) {
      return array();
    } else {
      $roles = $user->roles()->get();
      return self::getSimpleRoles($roles);
    }
  }

  public static function getScopedRoles($source, $globalRoles, $target) {
    if (get_class($source) == "App\Models\Member") {
      return self::resolveRelationFromUser($source, $globalRoles, $target);
    } else {

    }

    return array();
  }

  public static function hasRole($needle, $haystack) {
    foreach ($haystack as $role) {
      if ($role->code == $needle) {
        return true;
      }
    }

    return false;
  }

  public static function getSimpleRoles($collection) {
    $return = array();

    foreach($collection as $role) {
      array_push($return, $role->getAttribute('code'));
    }
    return $return;
  }

  public static function resolveRelationFromUser(User $source, $globalRoles, $target) {
    if (get_class($target) == "App\Models\Member") {
      return self::resolveRelationUserToUser($source, $globalRoles, $target);
    } else {

    }

    return array();
  }


  public static function resolveRelationUserToUser(User $source, $globalRoles, Member $target) {
    $roles = array();
    if ($source->id == $target->id) {
      array_push($roles, "self");
    }
    if ($source->forceGetAttribute("antenna_id") == $target->forceGetAttribute("antenna_id")) {
      array_push($roles, "samebody");
    }
    if (true == false) { //No idea how to determine board members currently
      array_push($roles, "board");
    }

    return $roles;
  }
}
 ?>
