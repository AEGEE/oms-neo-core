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
        	'name'			=>	'Body management',
        	'code'			=>	'body_management',
        	'module_link'	=>	'modules/loggedIn/body_management/body_management.js',
            'icon'          =>  'ion-android-wifi',
        	'is_active'		=>	1
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
