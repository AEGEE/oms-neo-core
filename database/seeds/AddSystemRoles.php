<?php

use Illuminate\Database\Seeder;

use App\Models\Role;

class AddSystemRoles extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::create([
     			'name'			=>	'AEGEE-member',
     			'code'			=>	'aegee',
     			'system_role'	=>	1
     		]);

        $role = Role::create([
     			'name'			=>	'Comite-Directeur',
     			'code'			=>	'comite_directeur',
     			'system_role'	=>	1
     		]);

        $role = Role::create([
     			'name'			=>	'Super-Admin',
     			'code'			=>	'super_admin',
     			'system_role'	=>	1
     		]);
    }
}
