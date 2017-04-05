<?php

use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\Body;
use App\Models\Member;
use App\Models\MemberRole;
use App\Models\Interfaces\HasUser;

class AddSuperAdmin extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {

  	Body::create([
  		'name'			=>	'Global antenna',
  		'city'			=>	'Cluj-Napoca',
  		'country_id' 	=>	1
  	]);

    User::create([
  		'password'			=>	Hash::make('1234'),
  		'is_superadmin'		=>	1,
  		'activated_at'		=>	date('Y-m-d H:i:s'),
    ]);

    Member::create([
      'user_id' => 1,
  		'contact_email' 	=> 	'flaviu@glitch.ro',
  		'first_name'		=>	'Flaviu',
  		'last_name'			=>	'Porutiu',
  		'date_of_birth'		=>	'1994-01-24',
  		'gender'			=>	1,
  		'body_id'		=>	1,
  		'university'		=>	'UBB Cluj',
  		'studies_type_id'	=>	1,
  		'studies_field_id'	=>	1,
      'city'              =>  'Cluj',
      'seo_url'           =>  'glitch'
   ]);

   MemberRole::create([
     'role_id' => 1,
     'member_id' => 1,
   ]);
   MemberRole::create([
     'role_id' => 2,
     'member_id' => 1,
   ]);
  }
}
