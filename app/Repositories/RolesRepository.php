<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Role;
use App\Models\SimpleRole;

class RolesRepository {

  public function getRoles($request, $target) {
    $globalRoles = $request->get('roles_global');
    $scopedRoles = $this->getScopedRoles($request->get('roles_source'), $globalRoles, $target);
    return $this->getSimpleRoles(array_merge($globalRoles->toArray(), $scopedRoles->toArray()));
  }

  public function getGlobalRoles($user) {
    return $user->roles()->get();
  }

  public function getScopedRoles($source, $globalRoles, $target) {
    //TODO: Decide which function to call based on source and target types.
    return $this->resolveRelationUserToUser($source, $globalRoles, $target);
  }

  public function hasRole($needle, $haystack) {
    foreach ($haystack as $role) {
      if ($role->code == $needle) {
        return true;
      }
    }

    return false;
  }


  public function resolveRelationUserToUser(User $source, $globalRoles, User $target) {
    if ($this->hasRole('recruter', $globalRoles)) {
      return collect(array(array("name" => "board")));
    } else {
      //Not a member of aegee means no special relations to any Member.
      return array();
    }
  }

  public function getSimpleRoles($array) {
    $return = array();

    foreach($array as $value) {
      array_push($return, $value['name']);
    }

    return $return;
  }
}
 ?>
