<?php

namespace App\Proxies;

use Log;
use App\Proxies\MicrosoftGraphProxy as OBE;

class MailProxy
{
    public static function sendLoginDetails($email, $username, $password) {
        Log::debug("Sending login details email...");
        $mail =
"Hello,
Hereby your credential information for MyAEGEE - OMS:
    Username: $username
    Password: $password

Please keep this information savely stored.
Once your account has been approved and activated by an admin, you will receive your personal Microsoft Office 365 @member.aegee.eu account.

You do not have to do anything with these credentials just yet. For now wait on admin approval after which you will receive a new email.

Proud to bring this to you,
~The MyAEGEE team
";

        $obe = new OBE();
        $response = $obe->sendEmail($email, "MyAEGEE - login information", $mail);
        Log::debug("Login details email SENT.");
        return $response;
    }

    public static function sendOBELoginDetails($user, $username, $password) {
        Log::debug("Sending OBE login details email...");
        $mail =
"Hello,
Hereby your credential information for your new Microsoft Office 365 account:
    Username: $username
    Password: $password

Please keep this information savely stored.
You can login at https://portal.office.com, you will be asked to change your password on your first login.


Proud to bring this to you,
~The MyAEGEE team
";

        $obe = new OBE();
        $response = $obe->sendEmail($user->personal_email, "MyAEGEE - Your @aegee.eu account", $mail);
        Log::debug("Login OBE details email SENT.");
        return $response;
    }
}
