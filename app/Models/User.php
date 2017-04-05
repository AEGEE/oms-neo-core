<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\AccessControlledModel;

class User extends Model
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
      error_log("No object assigned to this user.");
      //Panic.
      return;
    }
  }

  public function getLoginUserArray() {
      return array(
          'id'                =>  $this->id,
          'username'          =>  $this->getObject()->getUserName(),
          'fullname'          =>  $this->getObject()->getName(),
          'is_superadmin'     =>  $this->is_superadmin,
          'logged_in'         =>  true,
          'authToken'         =>  $this->oauth_token,
          'seo_url'           =>  $this->getObject()->getSEOURL(),
      );
  }

  public function checkStillValid() {
    return true;
    if(empty($user->is_superadmin)) {
        $fee->checkFeesForSuspention($user);
    }
  }
}
