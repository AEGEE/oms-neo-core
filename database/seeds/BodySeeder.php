<?php

use Illuminate\Database\Seeder;

use App\Models\Body;
use App\Models\BodyMembership;
use App\Models\BodyType;
use App\Models\Address;
use App\Models\User;

class BodySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Address::create([
            'id'            =>  1,
            'country_id'    =>  21,
            'street'        =>  'Notelaarsstraat 55',
            'zipcode'       =>  '1000',
            'city'          =>  'Brussels',
        ]);
        Address::create([
            'id'            =>  2,
            'country_id'    =>  153,
            'street'        =>  'Oude Markt 24',
            'zipcode'       =>  '7511 GB',
            'city'          =>  'Enschede',
        ]);
        Address::create([
            'id'            =>  3,
            'country_id'    =>  81,
            'street'        =>  'Bergstraße 66c',
            'zipcode'       =>  '01069',
            'city'          =>  'Dresden',
        ]);

        BodyType::create([
            'id'            =>  1,
            'name'          =>  'local',
        ]);

        BodyType::create([
            'id'            =>  2,
            'name'          =>  'special',
        ]);

        BodyType::create([
            'id'            =>  3,
            'name'          =>  'commission-european',
        ]);

    	Body::create([
            'type_id'       =>  2,
            'address_id'    =>  1,
    		'name'			=>	'AEGEE-Europe',
            'email'         =>  'headoffice@aegee.org',
            'legacy_key'    =>  'AEU' //No clue if this is actually correct.
    	]);

    	Body::create([
            'type_id'       =>  1,
            'address_id'    =>  2,
    		'name'			=>	'AEGEE-Enschede',
            'email'         =>  'headoffice@aegee.org',
            'legacy_key'    =>  'ENS' //No clue if this is actually correct.
    	]);

    	Body::create([
            'type_id'       =>  1,
            'address_id'    =>  3,
    		'name'			=>	'AEGEE-Dresden',
            'email'         =>  'info@aegee-dresden.org',
            'legacy_key'    =>  'DRE' //No clue if this is actually correct.
    	]);

    	Body::create([
            'type_id'       =>  3,
            'address_id'    =>  1,
    		'name'			=>	'Comité Directeur',
            'email'         =>  'headoffice@aegee.org',
            'legacy_key'    =>  'DVD' //No clue if this is actually correct.
    	]);
    }
}
