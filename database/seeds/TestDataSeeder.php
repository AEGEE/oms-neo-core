<?php

use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\Body;
use App\Models\Member;
use App\Models\MemberRole;
use App\Models\MemberBodyRelation;
use App\Models\Interfaces\HasUser;

class TestDataSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {

  	Body::create([
  		'name'			=>	'AEGEE-Enschede',
  		'city'			=>	'Enschede',
  		'country_id' 	=>	2
  	]);

  	Body::create([
  		'name'			=>	'AEGEE-Brussels',
  		'city'			=>	'Brussels',
  		'country_id' 	=>	3
  	]);



    User::create([
      'password'			=>	Hash::make('1234'),
      'is_superadmin'		=>	0,
      'activated_at'		=>	date('Y-m-d H:i:s'),
    ]);

    Member::create([
      'user_id' => 2,
  		'contact_email' 	=> 	'bob@bob',
  		'first_name'		=>	'Bob',
  		'last_name'			=>	'De Bouwer',
  		'date_of_birth'		=>	'1992-01-24',
  		'gender'			=>	0,
  		'university'		=>	'Yes',
  		'studies_type_id'	=>	1,
  		'studies_field_id'	=>	1,
      'city'              =>  'Enschede',
      'seo_url'           =>  'bob'
   ]);

   MemberBodyRelation::create([
     'member_id' => 1,
     'body_id' => 1,
     'body_role' => 1, //member
   ]);


   MemberBodyRelation::create([
     'member_id' => 2,
     'body_id' => 2,
     'body_role' => 2, //board
   ]);
    MemberBodyRelation::create([
      'member_id' => 2,
      'body_id' => 3,
      'body_role' => 1, //member
    ]);
  }
}
