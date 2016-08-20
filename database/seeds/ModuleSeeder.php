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
        // ModulePage::create([
        // 	'name'			=>	'Antennae management',
        // 	'code'			=>	'antennae_management',
        // 	'module_link'	=>	'modules/loggedIn/antennae_management/antennae_management.js',
        //     'icon'          =>  'ion-android-wifi',
        // 	'is_active'		=>	1
        // ]);

        // ModulePage::create([
        //     'name'          =>  'Working groups',
        //     'code'          =>  'working_groups',
        //     'module_link'   =>  'modules/loggedIn/working_groups/working_groups.js',
        //     'icon'          =>  'fa fa-group',
        //     'is_active'     =>  1
        // ]);
         ModulePage::create([
            'name'          =>  'Departments',
            'code'          =>  'departments',
            'module_link'   =>  'modules/loggedIn/departments/departments.js',
            'icon'          =>  'fa fa-briefcase',
            'is_active'     =>  1
        ]);
    }
}
