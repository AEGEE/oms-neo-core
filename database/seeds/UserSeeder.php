<?php

use Illuminate\Database\Seeder;

use App\Models\Body;
use App\Models\BodyMembership;
use App\Models\BodyType;
use App\Models\Address;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
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

        User::create([
            'id'                =>  2,
            'address_id'        =>  2,
			'contact_email' 	=> 	'derk.snijders@aegee.org',
			'first_name'		=>	'Derk',
			'last_name'			=>	'Snijders',
			'date_of_birth'		=>	'1993-10-31',
			'gender'			=>	'male',
			'password'			=>	Hash::make('1234'),
			'activated_at'		=>	date('Y-m-d H:i:s'),
			'is_superadmin'		=>	0,
            'seo_url'           =>  'i3anaan'
		]);

        User::create([
            'id'                =>  3,
            'address_id'        =>  1,
			'contact_email' 	=> 	'nico.westerbeck@aegee.org',
			'first_name'		=>	'Nico',
			'last_name'			=>	'Westerbeck',
			'date_of_birth'		=>	'1985-04-16',
			'gender'			=>	'male',
			'password'			=>	Hash::make('1234'),
			'activated_at'		=>	date('Y-m-d H:i:s'),
			'is_superadmin'		=>	0,
            'seo_url'           =>  'blacksph3re'
		]);

        User::create([
            'id'                =>  4,
            'address_id'        =>  1,
			'contact_email' 	=> 	'sergey.peshkov@aegee.org',
			'first_name'		=>	'Sergey',
			'last_name'			=>	'Peshkov',
			'date_of_birth'		=>	'1985-04-16',
			'gender'			=>	'male',
			'password'			=>	Hash::make('1234'),
			'activated_at'		=>	date('Y-m-d H:i:s'),
			'is_superadmin'		=>	0,
            'seo_url'           =>  'sergey.peshkov'
		]);

        User::create([
            'id'                =>  5,
            'address_id'        =>  1,
			'contact_email' 	=> 	'fabrizio.bellicano@aegee.org',
			'first_name'		=>	'Grace',
			'last_name'			=>	'Hopper',
			'date_of_birth'		=>	'1985-04-16',
			'gender'			=>	'female',
			'password'			=>	Hash::make('1234'),
			'activated_at'		=>	date('Y-m-d H:i:s'),
			'is_superadmin'		=>	0,
            'seo_url'           =>  'gracehopper'
		]);

        User::create([
            'id'                =>  6,
            'address_id'        =>  1,
			'contact_email' 	=> 	'headoffice@aegee.org',
			'first_name'		=>	'Big',
			'last_name'			=>	'Boobs',
			'date_of_birth'		=>	'1998-04-16',
			'gender'			=>	'female',
			'password'			=>	Hash::make('1234'),
			'activated_at'		=>	date('Y-m-d H:i:s'),
			'is_superadmin'		=>	0,
            'seo_url'           =>  'boobies'
		]);

        BodyMembership::create([
            'user_id'       =>  1,
            'body_id'       =>  1,
            'status'        =>  1,
            'start_date'  =>  date('Y-m-d H:i:s'),
            'end_date'  =>  date('Y-m-d H:i:s'),
        ]);

        BodyMembership::create([
            'user_id'       =>  2,
            'body_id'       =>  2,
            'status'        =>  1,
            'start_date'  =>  date('Y-m-d H:i:s'),
            'end_date'  =>  date('Y-m-d H:i:s'),
        ]);

        BodyMembership::create([
            'user_id'       =>  2,
            'body_id'       =>  4,
            'status'        =>  2,
            'start_date'  =>  date('Y-m-d H:i:s'),
            'end_date'  =>  date('Y-m-d H:i:s'),
        ]);

        BodyMembership::create([
            'user_id'       =>  3,
            'body_id'       =>  3,
            'status'        =>  1,
            'start_date'  =>  date('Y-m-d H:i:s'),
            'end_date'  =>  date('Y-m-d H:i:s'),
        ]);

        BodyMembership::create([
            'user_id'       =>  4,
            'body_id'       =>  1,
            'status'        =>  3,
            'start_date'  =>  date('Y-m-d H:i:s'),
            'end_date'  =>  date('Y-m-d H:i:s'),
        ]);

        BodyMembership::create([
            'user_id'       =>  5,
            'body_id'       =>  2,
            'status'        =>  1,
            'start_date'  =>  date('Y-m-d H:i:s'),
            'end_date'  =>  date('Y-m-d H:i:s'),
        ]);

        BodyMembership::create([
            'user_id'       =>  5,
            'body_id'       =>  3,
            'status'        =>  1,
            'start_date'  =>  date('Y-m-d H:i:s'),
            'end_date'  =>  date('Y-m-d H:i:s'),
        ]);
    }
}
