<?php

use Illuminate\Database\Seeder;

use App\Models\Circle;
use App\Models\BodyMembershipCircle;

class CircleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Circle::create([
        	'name'			=>	'IT Circle',
        	'description'	=>	'The official group of IT people within AEGEE'
        ]);


        Circle::create([
        	'name'			=>	'Shitty people',
        	'description'	=>	'The official group of shitty people people within AEGEE'
        ]);




        Circle::create([
            'name'              =>  'COT',
            'description'       =>  'Computer Operating Team',
            'body_id'           =>  2,
            'circle_id'         =>  1
        ]);

        Circle::create([
            'name'              =>  'Lia & Derk',
            'description'       =>  'IT people in da house',
            'body_id'           =>  4,
            'circle_id'         =>  1,
        ]);

        Circle::create([
            'name'              =>  'IT Germany',
            'body_id'           =>  3,
            'circle_id'         =>  1,
        ]);

        Circle::create([
            'body_id'           =>  1,
            'circle_id'         =>  2,
        ]);


        BodyMembershipCircle::create([
            'membership_id'     =>  1,
            'circle_id'         =>  4,
        ]);

        BodyMembershipCircle::create([
            'membership_id'     =>  2,
            'circle_id'         =>  3,
        ]);

        BodyMembershipCircle::create([
            'membership_id'     =>  3,
            'circle_id'         =>  4,
        ]);

        BodyMembershipCircle::create([
            'membership_id'     =>  4,
            'circle_id'         =>  5,
        ]);

        BodyMembershipCircle::create([
            'membership_id'     =>  8,
            'circle_id'         =>  6,
        ]);
    }
}
