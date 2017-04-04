<?php

namespace App\Repositories;

use App\Models\Member;

use Hash;

class UserRepository {
  public static function getFromCredentials($username, $password) {
    // For now only Members have credentials.

    // Trying to find a user..
  	// Non oAuth logins are handled by contact_email field..
  	try {
  		$user = Member::where('contact_email', $username)
				->whereNotNull('password')
				->whereNotNull('activated_at') // If its null, then the account was not activated..
				->firstOrFail(); // If password is null, then account should be used with oAuth..
  	} catch(ModelNotFoundException $ex) {
  		return null;
  	}

  	// Check password..
  	if(Hash::check($password, $user->forceGetAttribute("password"))) {
  		return $user;
  	} else {
      return null;
    }
  }
}
