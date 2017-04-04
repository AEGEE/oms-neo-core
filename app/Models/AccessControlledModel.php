<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Repositories\RolesRepository as Repo;

class AccessControlledModel extends Model
{

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

    public function getAttribute($key)
    {
      if ($this->canRead($key)) {
        return parent::getAttribute($key);
      } else {
        error_log("Permission denied to: " . $key . "; Roles: " . json_encode($this->roles));
        return null;
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

    public function syncRoles($source = null) {
      $roles = array();

      if ($source) {
        //Get global roles of the user (source)
        $roles = Repo::getGlobalRoles($source);
        //Get scoped roles of the user (source) on this object.
        $roles = Repo::getRoles($source, $roles, $this);
      }

      //Set roles of this object.
      $this->setRoles($roles);
    }

    public function setRoles($roles) {
      $this->roles = $roles;
      $this->updateRoles();
    }

    public function updateRoles() {
      //$this->updateReadRoles();
      //$this->updateWriteRoles();
    }

    public function updateReadRoles() {
      $attributes = array_keys($this->getAttributes());

      if (!in_array('super_admin', $this->roles)) {
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
      if (!in_array('super_admin', $this->roles)) {
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

    public function canRead($attribute) {
      if (!in_array('super_admin', $this->roles)) {
        return in_array($attribute, $this->getVisible());
      } else {
        //Superadmin
        return true;
      }
    }

    public function canWrite($attribute) {
      if (!in_array('super_admin', $this->roles)) {
        return in_array($attribute, $this->visible_write);
      } else {
        //Superadmin
        return true;
      }
    }
}
