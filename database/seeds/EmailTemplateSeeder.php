<?php

use Illuminate\Database\Seeder;

use App\Models\EmailTemplate;

class EmailTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Stub
    	// EmailTemplate::create([
    	// 	'name'				=>	'',
    	// 	'code'				=>	'',
    	// 	'title'				=>	'',
    	// 	'content'			=>	'',
    	// 	'allowed_fields'	=>	'',
    	// 	'description' 		=>	''
    	// ]);

    	EmailTemplate::create([
    		'name'				=>	'Account activated',
    		'code'				=>	'account_activated',
    		'title'				=>	'Your account on {app_name} has been activated',
    		'content'			=>	'Hello, {fullname} <br /><br />Your account on {app_name} has been activated!<br /><br />Your username is: {username} <br />Your password is: {password}<br /><br />You can login at: {link}<br /><b>We suggest, you change your password once logged in!</b>',
    		'allowed_fields'	=>	'{app_name}, {username}, {password}, {link}, {fullname}',
    		'description' 		=>	'Email sent when account was activated'
    	]);

    	EmailTemplate::create([
    		'name'				=>	'Password reset',
    		'code'				=>	'password_reset',
    		'title'				=>	'Password reset for {app_name}',
    		'content'			=>	'Hello, {fullname} <br /><br />A password reset has been requested for your account!<br />We remind you that your username is: {username}<br /><br />To reset your password, please follow this link: {link}<br /><br /><b>If you didn\'t request a password reset, please ignore and delete this email!</b><br />The password reset link is only available for {time_available} hours after generation!<br /><br />The link was generated at {time_generated}!',
    		'allowed_fields'	=>	'{app_name}, {username}, {link}, {fullname}, {time_available}, {time_generated}',
    		'description' 		=>	'Email sent when password reset is requested!'
    	]);

    	EmailTemplate::create([
    		'name'				=>	'Account suspended',
    		'code'				=>	'account_suspended',
    		'title'				=>	'Your account on {app_name} has been suspended',
    		'content'			=>	'Hello, {fullname} <br /><br />Your account on {app_name} has been suspended!<br /><br />The reason for suspention is: {reason}.<br />If you think this is a mistake, please contact us as soon as possible!',
    		'allowed_fields'	=>	'{app_name}, {reason}, {fullname}',
    		'description' 		=>	'Email sent when account was suspended'
    	]);

    	EmailTemplate::create([
    		'name'				=>	'Account unsuspended',
    		'code'				=>	'account_unsuspended',
    		'title'				=>	'Your account on {app_name} has been unsuspended',
    		'content'			=>	'Hello, {fullname} <br /><br />Your account on {app_name} has been unsuspended!<br /><br />We remind you that you can login at {link}!<br />Also, your username is {username}<br /><br /><b>Thank you for your time!</b>',
    		'allowed_fields'	=>	'{app_name}, {reason}, {fullname}',
    		'description' 		=>	'Email sent when account was suspended'
    	]);
    }
}
