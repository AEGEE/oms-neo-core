<?php

use Illuminate\Database\Seeder;

use App\Models\Antenna;
use App\Models\Country;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AntennaSeeder::class);
    }
}

class AntennaSeeder extends Seeder {
	public function run() {
		Country::create([
			'name'	=>	'Romania'
		]);

		Country::create([
			'name'	=>	'Germany'
		]);

		Antenna::create([
			'name' 			=> 'Aegee Cluj',
			'city' 			=> 'Cluj-Napoca',
			'country_id'	=>	1
		]);
	}
}
