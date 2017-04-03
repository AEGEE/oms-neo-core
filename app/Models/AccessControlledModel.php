<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
      return $this->canRead($key) ? parent::getAttribute($key) : null;
    }

    public function setAttribute($key, $value)
    {
      dump("Check write rights");
      dump($key);
      dump($this->canWrite($key));
      return $this->canWrite($key) ? parent::setAttribute($key, $value) : null;
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
      $visible = $this->permissions['read']['default'];
      foreach($this->roles as $role) {
        if (isset($this->permissions['read'][$role])) {
          $visible = array_merge($visible, $this->permissions['read'][$role]);
        }
      }

      $attributes = array_keys($this->getAttributes());
      $this->setVisible($visible);
      $this->setHidden(array_diff($attributes, $visible));
    }

    public function updateWriteRoles() {
      $visible = $this->permissions['write']['default'];
      foreach($this->roles as $role) {
        if (isset($this->permissions['write'][$role])) {
          $visible = array_merge($visible, $this->permissions['write'][$role]);
        }
      }

      $this->visible_write = $visible;
    }

    public function canRead($attribute) {
      return in_array($attribute, $this->getVisible());
    }

    public function canWrite($attribute) {
      return in_array($attribute, $this->visible_write);
    }
}
