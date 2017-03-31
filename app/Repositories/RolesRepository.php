<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Role;

class RolesRepository {

  public function getRoles($request, $target) {
    $globalRoles = $request->get('roles_global');
    $scopedRoles = $this->getScopedRoles($request->get('roles_source'), $globalRoles, $target);
    return array_merge($globalRoles->toArray(), $scopedRoles);
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
      //dump($source);
      //dump($target);
      return array("<A scoped role>");
    } else {
      //Not a member of aegee means no special relations to any Member.
      return array();
    }
  }
}
 ?>
