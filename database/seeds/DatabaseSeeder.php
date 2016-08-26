<?php

use Illuminate\Database\Seeder;

use App\Models\Antenna;
use App\Models\Country;
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
        $this->call(CountrySeeder::class);
        $this->call(TypeAndFieldOfStudiesSeeder::class);
        $this->call(ModuleSeeder::class);
        $this->call(OptionsSeeder::class);
        $this->call(EmailTemplateSeeder::class);
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

		User::create([
			'contact_email' 	=> 	'flaviu@glitch.ro',
			'first_name'		=>	'Flaviu',
			'last_name'			=>	'Porutiu',
			'date_of_birth'		=>	'1994-01-24',
			'gender'			=>	1,
			'antenna_id'		=>	$antenna->id,
			'university'		=>	'UBB Cluj',
			'studies_type_id'	=>	1,
			'studies_field_id'	=>	1,
			'password'			=>	Hash::make('1234'),
			'activated_at'		=>	date('Y-m-d H:i:S'),
			'is_superadmin'		=>	1
		]);
	}
}
