<?php

use Illuminate\Database\Seeder;

use App\Models\Role;

class AddAnnouncementsRole extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::create([
 			'name'			=>	'Announcer',
 			'code'			=>	'announcer',
 			'system_role'	=>	1
 		]);
    }
}
