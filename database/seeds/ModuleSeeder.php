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
            'name'          =>  'Users',
            'code'          =>  'users',
            'module_link'   =>  'modules/loggedIn/users/users.js',
            'icon'          =>  'fa fa-user',
            'is_active'     =>  1
        ]);

        /*ModulePage::create([
            'name'          =>  'Settings',
            'code'          =>  'settings',
            'module_link'   =>  'modules/loggedIn/settings/settings.js',
            'icon'          =>  'fa fa-cog',
            'is_active'     =>  1
        ]);*/

        ModulePage::create([
            'name'          =>  'Modules',
            'code'          =>  'modules',
            'module_link'   =>  'modules/loggedIn/modules/modules.js',
            'icon'          =>  'fa fa-puzzle-piece',
            'is_active'     =>  1
        ]);

        ModulePage::create([
            'name'          =>  'Bodies',
            'code'          =>  'body_management',
            'module_link'   =>  'modules/rewrite/body_management/body_management.js',
            'icon'          =>  'fa fa-bank',
            'is_active'     =>  1
        ]);

         ModulePage::create([
            'name'          =>  'Circles',
            'code'          =>  'circles',
            'module_link'   =>  'modules/rewrite/circles/circles.js',
            'icon'          =>  'fa fa-circle-o',
            'is_active'     =>  1
        ]);

        ModulePage::create([
            'name'          =>  'Admin',
            'code'          =>  'admin',
            'module_link'   =>  'modules/rewrite/admin/admin.js',
            'icon'          =>  'fa fa-magic',
            'is_active'     =>  1
        ]);
    }
}
