<?php

use Illuminate\Database\Seeder;

use App\Models\Body;
use App\Models\BodyMembership;
use App\Models\BodyType;
use App\Models\Address;
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
        Address::create([
            'id'            =>  1,
            'country_id'    =>  21,
            'street'        =>  'Notelaarsstraat 55',
            'zipcode'       =>  '1000',
            'city'          =>  'Brussels',
        ]);

        BodyType::create([
            'id'            =>  1,
            'name'          =>  'special',
        ]);

    	Body::create([
            'type_id'       =>  1,
            'address_id'    =>  1,
    		'name'			=>	'AEGEE-Europe',
            'email'         =>  'headoffice@aegee.org',
            'legacy_key'    =>  'AEU'
    	]);

        User::create([
            'id'                =>  1,
            'address_id'        =>  1,
			'contact_email' 	=> 	'admin@aegee.org',
			'first_name'		=>	'Super',
			'last_name'			=>	'Admin',
			'date_of_birth'		=>	'1985-04-16',
			'gender'			=>	'other',
			'password'			=>	Hash::make('1234'),
			'activated_at'		=>	date('Y-m-d H:i:s'),
			'is_superadmin'		=>	1,
            'seo_url'           =>  'superadmin'
		]);

        BodyMembership::create([
            'user_id'       =>  1,
            'body_id'       =>  1,
            'status'        =>  1,
            'start_date'  =>  date('Y-m-d H:i:s'),
            'end_date'  =>  date('Y-m-d H:i:s'),
        ]);
    }
}
