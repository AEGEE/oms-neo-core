<?php

use Illuminate\Database\Seeder;

use Models\Antenna;
use Models\Body;
use Models\BoardMember;
use Models\BodyMembership;
use Models\Department;
use Models\OrganizationalRole;
use Models\RecrutementCampaign;
use Models\Type;
use Models\User;
use Models\UserWorkingGroup;
use Models\WorkingGroup;

class BodiesPatch extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	echo "---- Bodies Patch ----".PHP_EOL;
    	echo "----------------------".PHP_EOL;
    	echo "Starting process.. This may take a while..".PHP_EOL;

    	// Creating types..
    	echo "Creating types..".PHP_EOL;
    	$antennaType = Type::firstOrCreate([
			'name'	=>	'Antenna'
		]);
		echo "Antenna type created!".PHP_EOL;

		$workingGroupType = Type::firstOrCreate([
			'name'	=>	'Working Group'
		]);
		echo "Working Group type created!".PHP_EOL;

		// Caches..
		$oldAntennaCache = array();
		$oldWgCache = array();
		$oldDepartmentCache = array();

		// Migrating Antennae..
		echo "Migrating antennae..".PHP_EOL;
		$allAntennae = Antenna::all();
		foreach($allAntennae as $antenna) {
			$oldAntennaCache[$antenna->id] = Body::create([
				'name'			=>	$antenna->name,
				'type_id'		=>	$antennaType->id,
				'email'			=>	$antenna->email,
				'city'			=>	$antenna->city,
				'address'		=>	$antenna->address,
				'phone'			=>	$antenna->phone,
				'country_id' 	=>	$antenna->country_id
			]);
			echo "Migrated antenna ".$antenna->name.PHP_EOL;
		}

		// Migrating Working Groups..
		echo "Migrating working groups..".PHP_EOL;
		$allWgs = WorkingGroup::all();
		foreach($allWgs as $wg) {
			$oldWgCache[$wg->id] = Body::create([
				'name'			=>	$wg->name,
				'type_id'		=>	$workingGroupType->id,
				'description'	=> 	$wg->description
			]);
			echo "Migrated Working group ".$wg->name.PHP_EOL;
		}

		// Migrating departments..
		echo "Migrating departments..".PHP_EOL;
		$allDepts = Department::all();
		foreach($allDepts as $dept) {
			$oldDepartmentCache[$dept->id] = OrganizationalRole::create([
				'name'	=>	$dept->name
			]);
			echo "Migrated department ".$dept->name.PHP_EOL;
		}

        // Migrating membershits..
        echo "Migrating memberships..".PHP_EOL;
        echo "Step 1. Migrating antenna membership".PHP_EOL;
    	$allUsers = User::all();
    	foreach($allUsers as $user) {
    		// Getting if its board member..
    		$isBoardMember = BoardMember::where('user_id', '=', $user->id)
    									->where('deparment_id', '=', $user->department_id)
    									->where('start_date', '<=', date('Y-m-d'))
    									->where(function($query) {
    										$query->where('end_date', '>=', date('Y-m-d'))
    												->orWhereNull('end_date');
    									})
    									->count();
    		$status = $isBoardMember > 0 ? 2 : 1;

    		// Generating membership..
    		BodyMembership::create([
    			'user_id'					=>	$user->id,
    			'body_id'					=>	$oldAntennaCache[$user->antenna_id]->id,
    			'organizational_role_id' 	=> 	$oldDepartmentCache[$user->department_id]->id,
    			'status'					=>	$status,
    			'start_date'				=>	$user->activated_at
    		]);
    		echo "Migrated user membership".$user->first_name." ".$user->last_name.PHP_EOL;
    		echo "Antenna ".$oldAntennaCache[$user->antenna_id]->name.PHP_EOL;
    		echo "Department ".$oldDepartmentCache[$user->department_id]->id.PHP_EOL;
    		echo PHP_EOL;
    	}

    	echo "Step 2. Migrating working groups memberships".PHP_EOL;
    	$wgMemberships = UserWorkingGroup::all();
    	foreach($wgMemberships as $wg) {
    		BodyMembership::create([
    			'user_id'					=>	$wg->user_id,
    			'body_id'					=>	$oldWgCache[$wg->working_group_id]->id,
    			'status'					=>	1,
    			'start_date'				=>	$wg->start_date,
    			'end_date'					=>	$wg->end_date
    		]);
    		echo "Migrated membership of user id ".$wg->user_id." working group ".$oldWgCache[$wg->working_group_id]->id->name.PHP_EOL;
    	}

    	echo "Migrating recrutement campaigns..".PHP_EOL;
    	$recrutementCampaigns = RecrutementCampaign::all();
    	foreach($recrutementCampaigns as $campaign) {
    		$campaign->body_id = $oldAntennaCache[$campaign->body_id]->id;
    		$campaign->save();
    		echo "Migrated campaign with link ".$campaign->link.PHP_EOL;
    	}

    	echo "Patch finished!".PHP_EOL;
    }
}
