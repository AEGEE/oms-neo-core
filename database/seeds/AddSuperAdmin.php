<?php

use Illuminate\Database\Seeder;

use App\Models\Antenna;
use App\Models\User;

class AddSuperAdmin extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	Antenna::create([
    		'name'			=>	'Global antenna',
    		'city'			=>	'Cluj-Napoca',
    		'country_id' 	=>	1
    	]);

        User::create([
			'contact_email' 	=> 	'flaviu@glitch.ro',
			'first_name'		=>	'Flaviu',
			'last_name'			=>	'Porutiu',
			'date_of_birth'		=>	'1994-01-24',
			'gender'			=>	1,
			'antenna_id'		=>	1,
			'university'		=>	'UBB Cluj',
			'studies_type_id'	=>	1,
			'studies_field_id'	=>	1,
			'password'			=>	Hash::make('1234'),
			'activated_at'		=>	date('Y-m-d H:i:s'),
			'is_superadmin'		=>	1,
            'city'              =>  'Cluj',
            'seo_url'           =>  'glitch'
		]);
    }
}
