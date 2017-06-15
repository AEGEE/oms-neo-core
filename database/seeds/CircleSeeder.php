<?php

use Illuminate\Database\Seeder;

use App\Models\GlobalCircle;
use App\Models\BodyCircle;
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
        GlobalCircle::create([
        	'name'			=>	'IT Circle',
        	'description'	=>	'The official group of IT people within AEGEE'
        ]);


        GlobalCircle::create([
        	'name'			=>	'Shitty people',
        	'description'	=>	'The official group of shitty people people within AEGEE'
        ]);




        BodyCircle::create([
            'name'              =>  'COT',
            'description'       =>  'Computer Operating Team',
            'body_id'           =>  2,
            'global_circle_id'  =>  1
        ]);

        BodyCircle::create([
            'name'              =>  'Lia & Derk',
            'description'       =>  'IT people in da house',
            'body_id'           =>  4,
            'global_circle_id'  =>  1,
        ]);

        BodyCircle::create([
            'name'              =>  'IT Germany',
            'body_id'           =>  3,
            'global_circle_id'  =>  1,
        ]);

        BodyCircle::create([
            'body_id'           =>  1,
            'global_circle_id'  =>  2,
        ]);


        BodyMembershipCircle::create([
            'membership_id'     =>  1,
            'circle_id'         =>  2,
        ]);

        BodyMembershipCircle::create([
            'membership_id'     =>  2,
            'circle_id'         =>  1,
        ]);

        BodyMembershipCircle::create([
            'membership_id'     =>  3,
            'circle_id'         =>  2,
        ]);

        BodyMembershipCircle::create([
            'membership_id'     =>  4,
            'circle_id'         =>  3,
        ]);

        BodyMembershipCircle::create([
            'membership_id'     =>  5,
            'circle_id'         =>  3,
        ]);
    }
}
