<?php

use Illuminate\Database\Seeder;

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
            'BodySeeder',
            'UserSeeder',
    	);

        dump(BodySeeder::class);

    	$seeders = SeederLog::all()->pluck('code')->toArray();
    	$seededSomething = false;
    	foreach($seedersToRun as $seeder) {
    		if(in_array($seeder, $seeders)) {
    			continue;
    		}

    		eval('$this->call('.$seeder.'::class);');
    		SeederLog::create([
    			'code'	=>	$seeder
    		]);

    		$seededSomething = true;
    	}

    	if(!$seededSomething) {
    		echo "Nothing to seed!".PHP_EOL;
    	}
    }
}
