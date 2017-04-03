<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Role;
use App\Models\SimpleRole;

class RolesRepository {

  public function getRoles($request, $target) {
    return array("super_admin");
    $globalRoles = $request->get('roles_global');
    $scopedRoles = $this->getScopedRoles($request->get('roles_source'), $globalRoles, $target);
    return array_merge($globalRoles, $scopedRoles);
  }

  public function getGlobalRoles($user) {
    return $this->getSimpleRoles($user->roles()->get());
  }

  public function getScopedRoles($source, $globalRoles, $target) {
    if (get_class($source) == "App\Models\User") {
      return $this->resolveRelationFromUser($source, $globalRoles, $target);
    } else {

    }

    return collect();
  }

  public function hasRole($needle, $haystack) {
    foreach ($haystack as $role) {
      if ($role->code == $needle) {
        return true;
      }
    }

    return false;
  }

  public function getSimpleRoles($collection) {
    $return = array();

    foreach($collection as $role) {
      array_push($return, $role->getAttribute('code'));
    }
    return $return;
  }

  public function resolveRelationFromUser(User $source, $globalRoles, $target) {
    if (get_class($target) == "App\Models\User") {
      return $this->resolveRelationUserToUser($source, $globalRoles, $target);
    } else {

    }

    return array();
  }


  public function resolveRelationUserToUser(User $source, $globalRoles, User $target) {
    $roles = array();
    if ($source == $target) {
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
