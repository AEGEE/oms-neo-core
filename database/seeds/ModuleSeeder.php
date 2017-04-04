<?php

use Illuminate\Database\Seeder;

use App\Models\ModulePage;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ModulePage::create([
        	'name'			=>	'Bodies management',
        	'code'			=>	'bodies_management',
        	'module_link'	=>	'modules/loggedIn/bodies_management/bodies_management.js',
            'icon'          =>  'ion-android-wifi',
        	'is_active'		=>	1
        ]);

        ModulePage::create([
            'name'          =>  'Working groups',
            'code'          =>  'working_groups',
            'module_link'   =>  'modules/loggedIn/working_groups/working_groups.js',
            'icon'          =>  'fa fa-group',
            'is_active'     =>  1
        ]);

        ModulePage::create([
            'name'          =>  'Departments',
            'code'          =>  'departments',
            'module_link'   =>  'modules/loggedIn/departments/departments.js',
            'icon'          =>  'fa fa-briefcase',
            'is_active'     =>  1
        ]);

        ModulePage::create([
            'name'          =>  'Fees management',
            'code'          =>  'fees_management',
            'module_link'   =>  'modules/loggedIn/fees_management/fees_management.js',
            'icon'          =>  'fa fa-usd',
            'is_active'     =>  1
        ]);

        ModulePage::create([
            'name'          =>  'Roles',
            'code'          =>  'roles',
            'module_link'   =>  'modules/loggedIn/roles/roles.js',
            'icon'          =>  'fa fa-unlock-alt',
            'is_active'     =>  1
        ]);

        ModulePage::create([
            'name'          =>  'Members',
            'code'          =>  'members',
            'module_link'   =>  'modules/loggedIn/members/members.js',
            'icon'          =>  'fa fa-user',
            'is_active'     =>  1
        ]);

        ModulePage::create([
            'name'          =>  'Settings',
            'code'          =>  'settings',
            'module_link'   =>  'modules/loggedIn/settings/settings.js',
            'icon'          =>  'fa fa-cog',
            'is_active'     =>  1
        ]);

        ModulePage::create([
            'name'          =>  'Modules',
            'code'          =>  'modules',
            'module_link'   =>  'modules/loggedIn/modules/modules.js',
            'icon'          =>  'fa fa-puzzle-piece',
            'is_active'     =>  1
        ]);
    }
}
