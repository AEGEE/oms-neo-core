<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

use App\Models\AccessControlledModel;
use App\Models\Interfaces\HasUser;

class User extends Model implements HasUser
{
  protected $table = "users";

  public function auth() {
  	return $this->hasMany('App\Models\Auth');
  }

  public function member() {
    return $this->belongsTo('App\Models\Member', 'id');
  }

  public function body() {
    return $this->belongsTo('App\Models\Body', 'id');
  }

  public function getObject() {
    if ($this->member) {
      return $this->member;
    } else if ($this->body) {
      return $this->body;
    } else {
      Log::debug("No object assigned to this user.");
      //Panic.
      return;
    }
  }

  public function getRoles() {
    return $this->getObject()->getRoles();
  }

  public function isSuperAdmin() {
    return in_array('super_admin', $this->getRoles());
  }

  public function getLoginUserArray($loginKey) {
      return array(
          'id'                =>  $this->id,
          'username'          =>  $this->getObject()->getUserName(),
          'fullname'          =>  $this->getObject()->getName(),
          'is_superadmin'     =>  $this->is_superadmin,
          'logged_in'         =>  true,
          'authToken'         =>  $loginKey,
          'seo_url'           =>  $this->getObject()->getSEOURL(),
      );
  }

  public function checkStillValid() {
    return true;
    if(empty($user->is_superadmin)) {
        $fee->checkFeesForSuspention($user);
    }
  }

  public function getName() {
    return $this->getObject()->getName();
  }
  public function getUserName() {
    return $this->getObject()->getUserName();
  }
  public function getSEOURL() {
    return $this->getObject()->getSEOURL();
  }
}
