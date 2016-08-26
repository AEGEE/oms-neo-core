<?php

use Illuminate\Database\Seeder;

use App\Models\GlobalOption;

class OptionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	// Stub 
        // GlobalOption::create([
        // 	'name'			=>	'',
        // 	'code'			=>	'',
        // 	'value' 		=>	'',
        // 	'description' 	=>	''
        // ]);

        GlobalOption::create([
        	'name'			=>	'Application name',
        	'code'			=>	'app_name',
        	'value' 		=>	'OMS',
        	'description' 	=>	'Application name which will be displayed everywhere'
        ]);

        GlobalOption::create([
        	'name'			=>	'Copyright info',
        	'code'			=>	'app_copyright',
        	'value' 		=>	'OMS - Open management system',
        	'description' 	=>	'Copyright information which will be displayed on the bottom of every page'
        ]);

        GlobalOption::create([
        	'name'			=>	'Email sender address',
        	'code'			=>	'email_sender',
        	'value' 		=>	'noreply@oms.dev',
        	'description' 	=>	'Email address from which all emails will be sent from'
        ]);

        GlobalOption::create([
            'name'          =>  'Password reset time',
            'code'          =>  'password_reset_time',
            'value'         =>  '1',
            'description'   =>  'Password reset link availability (in hours)'
        ]);
    }
}
