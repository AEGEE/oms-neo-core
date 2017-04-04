<?php

use Illuminate\Database\Seeder;

use App\Models\ModulePage;
use App\Models\Role;
use App\Models\RoleModulePage;

class AddRecruitmentModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $campaigns = ModulePage::create([
        	'name'			=>	'Recruitment campaigns',
        	'code'			=>	'recruitment_campaigns',
        	'module_link'	=>	'modules/loggedIn/recruitment_campaigns/recruitment_campaigns.js',
            'icon'          =>  'fa fa-plus-square',
        	'is_active'		=>	1,
        	'is_hidden'		=>	1
        ]);

        $members = ModulePage::create([
        	'name'			=>	'Recruited members',
        	'code'			=>	'members',
        	'module_link'	=>	'modules/loggedIn/recruited_members/recruited_members.js',
            'icon'          =>  'fa fa-check-square',
        	'is_active'		=>	1,
        	'is_hidden'		=>	1
        ]);

        $role = Role::create([
 			'name'			=>	'Recruiter',
 			'code'			=>	'recruiter',
 			'system_role'	=>	1
 		]);

 		RoleModulePage::create([
 			'module_page_id'	=>	$campaigns->id,
 			'role_id'			=>	$role->id,
 			'permission_level'	=>	1
 		]);

 		RoleModulePage::create([
 			'module_page_id'	=>	$members->id,
 			'role_id'			=>	$role->id,
 			'permission_level'	=>	1
 		]);
    }
}
