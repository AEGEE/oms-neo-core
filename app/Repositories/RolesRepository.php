<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Member;
use App\Models\Body;
use App\Models\Role;
use App\Models\SimpleRole;

class RolesRepository {

  public static function syncRolesForAll($models, $user) {
    $models->map(function($model, $key) use ($user) { $model->syncRoles($user); });
  }

  public static function getRoles($user, $globalRoles, $object) {
    $scopedRoles = self::resolveRelation($user, $globalRoles, $object);
    return array_merge($globalRoles, $scopedRoles);
  }

  public static function getGlobalRoles($user) {
    if (!$user) {
      return array();
    } else {
      $roles = $user->getObject()->roles()->get();
      //dd($user->getObject()->roles()->get());
      return self::getSimpleRoles($roles);
    }
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

  public static function resolveRelation($user, $globalRoles, $target) {
    if (get_class($user->getObject()) == "App\Models\Member") {
      return self::resolveRelationFromUser($user, $globalRoles, $target);
    } else {
      return array('aegee');
    }

    return array();
  }

  public static function resolveRelationFromUser(User $user, $globalRoles, $target) {
    if (get_class($target) == "App\Models\Member") {
      return self::resolveRelationUserToMember($user, $globalRoles, $target);
    } else if (get_class($target) == "App\Models\Body") {
      return self::resolveRelationUserToBody($user, $globalRoles, $target);
    }

    return array();
  }


  public static function resolveRelationUserToMember(User $user, $globalRoles, Member $target) {
    $roles = array();
    if ($user->getObject()->id == $target->id) {
      array_push($roles, "self");
    }
    if ($user->getObject()->forceGetAttribute("antenna_id") == $target->forceGetAttribute("antenna_id")) {
      array_push($roles, "samebody");
    }
    if (true == false) { //No idea how to determine board members currently
      array_push($roles, "board");
    }

    return $roles;
  }


  public static function resolveRelationUserToBody(User $user, $globalRoles, Body $target) {
    $roles = array();
    if ($user->member->forceGetAttribute('body_id') == $target->id) {
      array_push($roles, "member");
    }

    return $roles;
  }
}
 ?>
