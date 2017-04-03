<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccessControlledModel extends Model
{
    private $roles = array();

    protected $permissions = array();

    public function setRoles($roles) {
      $this->roles = $roles;
      $this->updateRoles();
    }

    public function updateRoles() {
      $this->makeHidden(array_keys($this->getAttributes()));

      $visible = $this->permissions['default'];
      foreach($this->roles as $role) {
        if (isset($this->permissions[$role])) {
          $visible = array_merge($visible, $this->permissions[$role]);
        }
      }

      $this->makeVisible($visible);
    }
}
