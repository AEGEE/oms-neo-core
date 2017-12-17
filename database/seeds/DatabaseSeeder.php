<?php

use Illuminate\Database\Seeder;

use App\Models\SeederLog;

class DatabaseSeeder extends Seeder
{

    public static function getSeeders() {
        return array(
            'CountrySeeder',
            'TypeAndFieldOfStudiesSeeder',
            'ModuleSeeder',
            'OptionsSeeder',
            'BodySeeder',
            'UserSeeder',
            'CircleSeeder',
        );
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$seeders = SeederLog::all()->pluck('code')->toArray();
    	$seededSomething = false;
    	foreach(DatabaseSeeder::getSeeders() as $seeder) {
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

    public static function isSeeded() {
        return SeederLog::all()->count() == count(DatabaseSeeder::getSeeders());
    }
}
