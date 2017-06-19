<?php
namespace App\Contracts;

use App\Models\User;

interface OnlineBusinessEnvironment
{
    public function createAccountForUser(User $user);
}
