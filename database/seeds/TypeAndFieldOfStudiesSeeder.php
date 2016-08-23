<?php

use Illuminate\Database\Seeder;

use App\Models\StudyField;
use App\Models\StudyType;

class TypeAndFieldOfStudiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	// Study types..
		StudyType::create([
			'name'	=>	'Bachelor'
		]);

		StudyType::create([
			'name'	=>	'Master'
		]);

		StudyType::create([
			'name'	=>	'PhD'
		]);

		StudyType::create([
			'name'	=>	'Other'
		]);

		// Study field
		StudyField::create(['name' => "Agriculture"]);
		StudyField::create(['name' => "Architecture"]);
		StudyField::create(['name' => "Arts"]);
		StudyField::create(['name' => "Biology"]);
		StudyField::create(['name' => "Chemistry"]);
		StudyField::create(['name' => "Civil Engineering"]);
		StudyField::create(['name' => "Communications"]);
		StudyField::create(['name' => "Computer Science"]);
		StudyField::create(['name' => "Design"]);
		StudyField::create(['name' => "Electrical Engineering"]);
		StudyField::create(['name' => "European Studies"]);
		StudyField::create(['name' => "Economy"]);
		StudyField::create(['name' => "Food Science"]);
		StudyField::create(['name' => "Geography"]);
		StudyField::create(['name' => "History"]);
		StudyField::create(['name' => "International Relations"]);
		StudyField::create(['name' => "Languages"]);
		StudyField::create(['name' => "Law"]);
		StudyField::create(['name' => "Literature"]);
		StudyField::create(['name' => "Maths"]);
		StudyField::create(['name' => "Mechanical Engineering"]);
		StudyField::create(['name' => "Medicine"]);
		StudyField::create(['name' => "Music"]);
		StudyField::create(['name' => "No studies"]);
		StudyField::create(['name' => "Other Applied Sciences"]);
		StudyField::create(['name' => "Other Engineering"]);
		StudyField::create(['name' => "Other Humanities"]);
		StudyField::create(['name' => "Other Medical Studies"]);
		StudyField::create(['name' => "Other Natural Sciences"]);
		StudyField::create(['name' => "Paedagology"]);
		StudyField::create(['name' => "Pharmacy"]);
		StudyField::create(['name' => "Philosophy"]);
		StudyField::create(['name' => "Physics"]);
		StudyField::create(['name' => "Political Science"]);
		StudyField::create(['name' => "Psychology"]);
		StudyField::create(['name' => "Public Relations"]);
		StudyField::create(['name' => "Publicity"]);
		StudyField::create(['name' => "Sociology"]);
		StudyField::create(['name' => "Tourism"]);
		StudyField::create(['name' => "Veterinary"]);
    }
}
