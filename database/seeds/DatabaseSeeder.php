<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\QueryException;

use App\Models\SeederLog;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$seedersToRun = array(
    		'CountrySeeder',
    		'TypeAndFieldOfStudiesSeeder',
    		'ModuleSeeder',
    		'OptionsSeeder',
    		'EmailTemplateSeeder',
    		'AddSuperAdmin',
        'AddRecruitementModuleSeeder',
        'AddSystemRoles'
    	);

    	$seeders = SeederLog::all();
    	$seedersArr = array();
    	foreach($seeders as $seeder) {
    		$seedersArr[] = $seeder->code;
    	}

    	$seededSomething = false;
    	foreach($seedersToRun as $seeder) {
    		if(in_array($seeder, $seedersArr)) {
    			continue;
    		}


        try {
    		    eval('$this->call('.$seeder.'::class);');
        		SeederLog::create([
        			'code'	=>	$seeder
        		]);
        } catch (Exception $e) {
          echo $e . PHP_EOL;
          echo $e->getSql() . PHP_EOL;
        }

    		$seededSomething = true;
    	}

    	if(!$seededSomething) {
    		echo "Nothing to seed!".PHP_EOL;
    	}
    }
}

class memberSeeder extends Seeder {
	public function run() {
		Member::create([
			'contact_email' 	=> 	'flaviu@glitch.ro',
			'first_name'		=>	'Flaviu',
			'last_name'			=>	'Porutiu',
			'date_of_birth'		=>	'1994-01-24',
			'gender'			=>	1,
			'body_id'		=>	$body->id,
			'university'		=>	'UBB Cluj',
			'studies_type_id'	=>	1,
			'studies_field_id'	=>	1,
			'password'			=>	Hash::make('1234'),
			'activated_at'		=>	date('Y-m-d H:i:S'),
			'is_superadmin'		=>	1
		]);
	}
}
