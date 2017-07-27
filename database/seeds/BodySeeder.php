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
	    BodyType::create([
            'name'      =>  'antenna', //1
        ]);

        BodyType::create([
            'name'      =>  'contact antenna', //2
        ]);

        BodyType::create([
            'name'      =>  'contact', //3
        ]);

	    BodyType::create([
	        'name'	    =>  'commission', //4
	    ]);

	    BodyType::create([
	        'name'	    =>  'committee', //5
        ]);

        BodyType::create([
            'name'      =>  'special', //6
        ]);
	

	    Address::create([ //TODO: Validate address
            'country_id'    =>  202,
            'street'        =>  'Campus de Elviña',
            'zipcode'       =>  '15071',
            'city'          =>  'A Coruna',
        ]);

    	Body::create([
            'type_id'       =>  1,
            'address_id'    =>  1,
    	    'name'	        =>	'AEGEE-A Coruna',
            'email'         =>  'aegee.coruna@gmail.com',
            'legacy_key'    =>  'ACO' //TODO
    	]);

	
        Address::create([
            'country_id'    =>  81,
            'street'        =>  'Templergraben 55',
            'zipcode'       =>  '52056',
            'city'          =>  'Aachen',
        ]);

    	Body::create([
            'type_id'       =>  1,
            'address_id'    =>  2,
    	    'name'	        =>	'AEGEE-Aachen',
            'email'         =>  'info@aegee-aachen.org',
	        'phone'	        =>  '2418097121',
            'legacy_key'    =>  'AAC' //TODO
    	]);


	    Address::create([ //TODO: Address unknown
            'country_id'    =>  220,
            'street'      =>  '',
            'zipcode'     =>  '',
            'city'          =>  'Adana',
        ]);

    	Body::create([
            'type_id'       =>  2,
            'address_id'    =>  3,
    	    'name'	        =>	'AEGEE-Adana',
            'email'         =>  'adanaaegee@gmail.com',
	        'phone'	        =>  '5071378919',
            'legacy_key'    =>  'ADA' //TODO
    	]);


	    Address::create([ //TODO: Address unknown
            'country_id'    =>  106,
            'street'      =>  '',
            'zipcode'     =>  '',
            'city'          =>  'Agrigento',
        ]);

    	Body::create([
            'type_id'       =>  1,
            'address_id'    =>  4,
    	    'name'	        =>	'AEGEE-Agrigento',
            'email'         =>  'aegee92100@gmail.com',
            'legacy_key'    =>  'AGR' //TODO
    	]);


	    Address::create([
            'country_id'    =>  202,
            'street'        =>  'Carretera de San Vicente',
            'zipcode'       =>  '03690',
            'city'          =>  'Alicante',
        ]);

    	Body::create([
            'type_id'       =>  1,
            'address_id'    =>  5,
    	    'name'	        =>	'AEGEE-Alicante',
            'email'         =>  'info@aegeealicante.org',
	        'phone'	        =>  '644111358',
            'legacy_key'    =>  'ALI' //TODO
    	]);


	    Address::create([
            'country_id'    =>  153,
            'street'        =>  'Nieuwe Achtergracht 170',
            'zipcode'       =>  '1018 WV',
            'city'          =>  'Amsterdam',
        ]);

    	Body::create([
            'type_id'       =>  1,
            'address_id'    =>  6,
    	    'name'	        =>	'AEGEE-Amsterdam',
            'email'         =>  'office@aegee-amsterdam.nl',
	        'phone'	        =>  '205252496',
            'legacy_key'    =>  'AMS' //TODO
    	]);


	    Address::create([
            'country_id'    =>  220,
            'street'        =>  'Engineering Center Building MM-120',
            'zipcode'       =>  'TR-06800',
            'city'          =>  'Ankara',
        ]);

    	Body::create([
            'type_id'       =>  1,
            'address_id'    =>  7,
    	    'name'	        =>	'AEGEE-Ankara',
            'email'         =>  'yk@aegee-ankara.org',
	        'phone'	        =>  '3122103625',
            'legacy_key'    =>  'ANK' //TODO
    	]);


	    Address::create([
            'country_id'    =>  84,
            'street'        =>  '28is Oktovriou 80',
            'zipcode'       =>  '10434',
            'city'          =>  'Athina',
        ]);

    	Body::create([
            'type_id'       =>  1,
            'address_id'    =>  8,
    	    'name'	        =>	'AEGEE-Athina',
            'email'         =>  'info@aegee-athina.gr',
	        'phone'	        =>  '2108203711',
            'legacy_key'    =>  'ATH' //TODO
    	]);


	    Address::create([
            'country_id'    =>  15,
            'street'        =>  'Boyuk Gala Kucasi 24',
            'zipcode'       =>  '1000',
            'city'          =>  'Baki',
        ]);

    	Body::create([
            'type_id'       =>  1,
            'address_id'    =>  9,
    	    'name'	        =>	'AEGEE-Baki',
            'email'         =>  'baki@aegee.org',
	        'phone'	        =>  '124921543',
            'legacy_key'    =>  'BAK' //TODO
    	]);


	    Address::create([
            'country_id'    =>  81,
            'street'        =>  'Feldkirchenstrasse 21',
            'zipcode'       =>  '96052',
            'city'          =>  'Bamberg',
        ]);

    	Body::create([
            'type_id'       =>  1,
            'address_id'    =>  10,
    	    'name'	        =>	'AEGEE-Bamberg',
            'email'         =>  'bamberg@aegee.org',
            'legacy_key'    =>  'BAM' //TODO
    	]);


	    Address::create([
            'country_id'    =>  27,
            'street'        =>  'Bulevar vojvode Petra Bojovica 1A',
            'zipcode'       =>  '78000',
            'city'          =>  'Banja Luka',
        ]);

    	Body::create([
            'type_id'       =>  1,
            'address_id'    =>  11,
    	    'name'	        =>	'AEGEE-Banja Luka',
            'email'         =>  'aegee.banjaluka@gmail.com',
	        'phone'	        =>  '65699557',
            'legacy_key'    =>  'BAN' //TODO
    	]);


	    Address::create([
            'country_id'    =>  202,
            'street'        =>  'Gran Via de les Corts Catalanes 585',
            'zipcode'       =>  '08007',
            'city'          =>  'Barcelona',
        ]);

    	Body::create([
            'type_id'       =>  1,
            'address_id'    =>  12,
    	    'name'	        =>	'AEGEE-Barcelona',
            'email'         =>  'president@aegeebarcelona.org',
	        'phone'	        =>  '616237339',
            'legacy_key'    =>  'BRC' //TODO
    	]);


    	Address::create([ //TODO: Address unknown
            'country_id'    =>  106,
            'street'      =>  '',
            'zipcode'     =>  '',
            'city'          =>  'Bari',
        ]);

    	Body::create([
            'type_id'       =>  1,
            'address_id'    =>  13,
    	    'name'	        =>	'AEGEE-Bari',
            'email'         =>  'bari@aegee.org',
	        'phone'	        =>  '3423892968',
            'legacy_key'    =>  'BAR' //TODO
    	]);


	    Address::create([
            'country_id'    =>  192,
            'street'        =>  'Djusina 7',
            'zipcode'       =>  '11000',
            'city'          =>  'Beograd',
        ]);

    	Body::create([
            'type_id'       =>  1,
            'address_id'    =>  14,
    	    'name'	        =>	'AEGEE-Beograd',
            'email'         =>  'aegee.beograd@gmail.com',
	        'phone'	        =>  '669399551',
            'legacy_key'    =>  'BEO' //TODO
    	]);


	    Address::create([
            'country_id'    =>  106,
            'street'        =>  'Via San Bernardino, 72/e',
            'zipcode'       =>  '24127',
            'city'          =>  'Bergamo',
        ]);

    	Body::create([
            'type_id'       =>  1,
            'address_id'    =>  15,
    	    'name'	        =>	'AEGEE-Bergamo',
            'email'         =>  'info@aegeebergamo.eu',
            'legacy_key'    =>  'BEG' //TODO
    	]);


	    Address::create([
            'country_id'    =>  81,
            'street'        =>  'Unter den Linden 6',
            'zipcode'       =>  '10099',
            'city'          =>  'Berlin',
        ]);

    	Body::create([
            'type_id'       =>  1,
            'address_id'    =>  16,
    	    'name'	        =>	'AEGEE-Berlin',
            'email'         =>  'info@aegee-berlin.org',
	        'phone'	        =>  '1784042916',
            'legacy_key'    =>  'BER' //TODO
    	]);


	    Address::create([
            'country_id'    =>  174,
            'street'        =>  'ul. M. Sklodowskiej-Curie 14 pok.307',
            'zipcode'       =>  '15-097',
            'city'          =>  'Bialystok',
        ]);

    	Body::create([
            'type_id'       =>  1,
            'address_id'    =>  17,
    	    'name'	        =>	'AEGEE-Bialystok',
            'email'         =>  'bxaegee@yahoo.com',
	        'phone'	        =>  '857447678',
            'legacy_key'    =>  'BIA' //TODO
    	]);


	    Address::create([ //TODO: Address unknown
            'country_id'    =>  202,
            'street'      =>  '',
            'zipcode'     =>  '',
            'city'          =>  'Bilbao',
        ]);

    	Body::create([
            'type_id'       =>  1,
            'address_id'    =>  18,
    	    'name'	        =>	'AEGEE-Bilbao',
            'email'         =>  'aegeebilbao@gmail.com',
            'legacy_key'    =>  'BIL' //TODO
    	]);


	    Address::create([
            'country_id'    =>  106,
            'street'        =>  'Via San Donato 146 2/C presso AICS',
            'zipcode'       =>  '40127',
            'city'          =>  'Bologna',
        ]);

    	Body::create([
            'type_id'       =>  1,
            'address_id'    =>  19,
    	    'name'	        =>	'AEGEE-Bologna',
            'email'         =>  'aegeebologna1@gmail.com',
	        'phone'	        =>  '3737232954',
            'legacy_key'    =>  'BOL' //TODO
    	]);


	    Address::create([
            'country_id'    =>  196,
            'street'        =>  'Stare Grunty 36',
            'zipcode'       =>  '84225',
            'city'          =>  'Bratislava',
        ]);

    	Body::create([
            'type_id'       =>  1,
            'address_id'    =>  20,
    	    'name'	        =>	'AEGEE-Bratislava',
            'email'         =>  'info@aegee-bratislava.sk',
            'legacy_key'    =>  'BRA' //TODO
        ]);


        Address::create([
            'country_id'    =>  106,
            'street'        =>  'Via Valotti 3',
            'zipcode'       =>  '25100',
            'city'          =>  'Brescia',
        ]);

    	Body::create([
            'type_id'       =>  1,
            'address_id'    =>  21,
    	    'name'	        =>	'AEGEE-Brescia',
            'email'         =>  'aegee.brescia@gmail.com',
	        'phone'	        =>  '3349507036',
            'legacy_key'    =>  'BRE' //TODO
        ]);


        Address::create([
            'country_id'    =>  57,
            'street'        =>  'Terasova 66',
            'zipcode'       =>  '61600',
            'city'          =>  'Brno',
        ]);

    	Body::create([
            'type_id'       =>  1,
            'address_id'    =>  22,
    	    'name'	        =>	'AEGEE-Brno',
            'email'         =>  'info@aegee-brno.org',
	        'phone'	        =>  '607161655',
            'legacy_key'    =>  'BRN' //TODO
        ]);


        Address::create([ //TODO: Address unknown
            'country_id'    =>  21,
            'street'        =>  '',
            'zipcode'       =>  '',
            'city'          =>  'Brussel-Bruxelles',
        ]);

    	Body::create([
            'type_id'       =>  1,
            'address_id'    =>  23,
    	    'name'	        =>	'AEGEE-Brussel/Bruxelles',
            'email'         =>  'aegee.bruxelles@gmail.com',
            'legacy_key'    =>  'BRU' //TODO
        ]);


        Address::create([ //TODO: No zipcode?
            'country_id'    =>  179,
            'street'        =>  'Str. Frumoasa 31',
            'zipcode'       =>  '',
            'city'          =>  'Bucuresti',
        ]);

    	Body::create([
            'type_id'       =>  1,
            'address_id'    =>  24,
    	    'name'	        =>	'AEGEE-Bucuresti',
            'email'         =>  'aegeebucuresti@gmail.com',
	        'phone'	        =>  '727596374',
            'legacy_key'    =>  'BUC' //TODO
        ]);


        Address::create([
            'country_id'    =>  98,
            'street'        =>  'Fovam ter 8. (Budapest Corvinus University, Fszt. 9.)',
            'zipcode'       =>  '1093',
            'city'          =>  'Budapest',
        ]);

    	Body::create([
            'type_id'       =>  1,
            'address_id'    =>  25,
    	    'name'	        =>	'AEGEE-Budapest',
            'email'         =>  'board@aegee-budapest.hu',
            'legacy_key'    =>  'BUD' //TODO
        ]);


        Address::create([ //TODO: Address unknown
            'country_id'    =>  202,
            'street'        =>  '',
            'zipcode'       =>  '',
            'city'          =>  'Burgos',
        ]);

    	Body::create([
            'type_id'       =>  1,
            'address_id'    =>  26,
    	    'name'	        =>	'AEGEE-Burgos',
            'email'         =>  'aegeeburgos@gmail.com',
            'legacy_key'    =>  'BUR' //TODO
    	]);
    }
}
