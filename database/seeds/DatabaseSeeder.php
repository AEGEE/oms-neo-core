<?php

use Illuminate\Database\Seeder;

use App\Models\Antenna;
use App\Models\Country;
use App\Models\StudyField;
use App\Models\StudyType;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(userSeeder::class);
    }
}

class userSeeder extends Seeder {
	public function run() {
		$country = Country::create([
			'name'	=>	'Romania'
		]);

		$antenna = Antenna::create([
			'name'			=>	'Aegee Cluj',
			'city'			=>	'Cluj-Napoca',
			'country_id' 	=>	$country->id
		]);

		$studies_type = StudyType::create([
			'name'	=>	'Masters'
		]);

		$study_fields = StudyField::create([
			'name'	=>	'IT'
		]);

		User::create([
			'contact_email' 	=> 	'flaviu@glitch.ro',
			'first_name'		=>	'Flaviu',
			'last_name'			=>	'Porutiu',
			'date_of_birth'		=>	'1994-01-24',
			'gender'			=>	1,
			'antenna_id'		=>	$antenna->id,
			'university'		=>	'UBB Cluj',
			'studies_type_id'	=>	$studies_type->id,
			'studies_field_id'	=>	$study_fields->id,
			'password'			=>	Hash::make('1234'),
			'is_superadmin'		=>	1
		]);
	}
}
