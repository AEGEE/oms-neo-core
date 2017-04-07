<?php

namespace App\Repositories;

use App\Models\Member;

use Hash;

class UserRepository {
  public static function getFromCredentials($username, $password) {
    // For now only Members have credentials.
    return self::getMemberFromCredentials($username, $password);
  }

  public static function getMemberFromCredentials($username, $password) {
    // Trying to find a member..
    // Non oAuth logins are handled by contact_email field..
    try {
      $member = Member::where('contact_email', $username)
        ->firstOrFail(); // If its null, then the account was not activated..
    } catch(ModelNotFoundException $ex) {
      return null;
    }
    $user = $member->forceGetAttribute('user');

    // Check password..
    if ($password && Hash::check($password, $user->password)) {
      return $user;
    } else {
      return null;
    }
  }
}
