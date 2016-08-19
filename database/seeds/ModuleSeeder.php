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
        	'name'			=>	'Antennae management',
        	'code'			=>	'antennae_management',
        	'module_link'	=>	'modules/loggedIn/antennae_management/antennae_management.js',
        	'is_active'		=>	1
        ]);
    }
}
