<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

use App\Repositories\RolesRepository as Repo;

//TODO: Improve performance by reducing unnecessary role updates
class AccessControlledModel extends Model
{

    // Needs to be false for seeding the database.
    protected static $ACCESS_CONTROL_ENABLED = true;

    private $roles = array();

    private $visible_write = array();

    protected $permissions = array();


    //TODO more methods that add attributes?
    public function fill(array $attributes) {
      $result = parent::fill($attributes);
      $this->updateRoles();
      return $result;
    }

    public function setRawAttributes(array $attributes, $sync = false)
    {
      $result = parent::setRawAttributes($attributes, $sync);
      $this->updateRoles();
      return $result;
    }

    //Gives error when permission is denied.
    public function getAttribute($key)
    {
      if ($this->canRead($key)) {
        return parent::getAttribute($key);
      } else {
        Log::debug("Permission denied to: " . $key . "on: " . get_class($this) . "; Roles: " . json_encode($this->roles));
        return null;
      }
    }

    //Returns default value when permission is denied, does not give an error.
    public function tryGet($key, $default = null) {
        if ($this->canRead($key)) {
          return parent::getAttribute($key);
        } else {
          return $default;
        }
    }

    public function forceGetAttribute($key)
    {
      return parent::getAttribute($key);
    }

    public function setAttribute($key, $value)
    {
      return $this->canWrite($key) ? parent::setAttribute($key, $value) : null;
    }

    public function syncRoles($user) {
      $roles = array();
      if ($user) {
        //Get global roles of the user (source)
        $roles = Repo::getGlobalRoles($user);
        //Get scoped roles of the user (source) on this object (target).
        $roles = Repo::getRoles($user, $roles, $this);
      }

      //Set roles of this object.
      $this->setRoles($roles);
    }

    public function getRoles() {
      $this->updateRoles();
      return $this->roles;
    }

    public function setRoles($roles) {
      $this->roles = $roles;
      $this->updateRoles();
    }

    public function updateRoles() {
      $this->updateReadRoles();
      $this->updateWriteRoles();
    }

    public function updateReadRoles() {
      $attributes = array_keys($this->getAttributes());

      if ($this->accessControlEnabled()) {
        $visible = $this->permissions['read']['default'];
        foreach($this->roles as $role) {
          if (isset($this->permissions['read'][$role])) {
            $visible = array_merge($visible, $this->permissions['read'][$role]);
          }
        }

        $this->setVisible($visible);
        $this->setHidden(array_diff($attributes, $visible));
      } else {
        //Superadmin
        $this->setVisible($attributes);
        $this->setHidden(array());
      }
    }

    public function updateWriteRoles() {
      if ($this->accessControlEnabled()) {
        $visible = $this->permissions['write']['default'];
        foreach($this->roles as $role) {
          if (isset($this->permissions['write'][$role])) {
            $visible = array_merge($visible, $this->permissions['write'][$role]);
          }
        }

        $this->visible_write = $visible;
      } else {
        //Superadmin
        $this->visible_write = array_keys($this->getAttributes());
      }
    }

    public function accessControlEnabled() {
      return self::$ACCESS_CONTROL_ENABLED && !in_array('super_admin', $this->roles);
    }

    public function canRead($attribute) {
      if ($this->accessControlEnabled()) {
        return in_array($attribute, $this->getVisible());
      } else {
        return true;
      }
    }

    public function canWrite($attribute) {
      if ($this->accessControlEnabled()) {
        return in_array($attribute, $this->visible_write);
      } else {
        return true;
      }
    }
}
