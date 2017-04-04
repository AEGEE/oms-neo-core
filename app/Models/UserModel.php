<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Models\AccessControlledModel;

abstract class UserModel extends AccessControlledModel
{
  public function auth() {
  	return $this->hasMany('App\Models\Auth');
  }

  public abstract function checkStillValid() {
    if(empty($user->is_superadmin)) {
        $fee->checkFeesForSuspention($user);
    }
  }
}
