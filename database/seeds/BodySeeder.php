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
            'legacy_key'    =>  'ACO'
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
            'legacy_key'    =>  'AAC'
    	]);


	    Address::create([ //TODO: Address unknown
            'country_id'    =>  220,
            'street'        =>  '',
            'zipcode'       =>  '',
            'city'          =>  'Adana',
        ]);

    	Body::create([
            'type_id'       =>  2,
            'address_id'    =>  3,
    	    'name'	        =>	'AEGEE-Adana',
            'email'         =>  'adanaaegee@gmail.com',
	        'phone'	        =>  '5071378919',
            'legacy_key'    =>  'ADA'
    	]);


	    Address::create([ //TODO: Address unknown
            'country_id'    =>  106,
            'street'        =>  '',
            'zipcode'       =>  '',
            'city'          =>  'Agrigento',
        ]);

    	Body::create([
            'type_id'       =>  1,
            'address_id'    =>  4,
    	    'name'	        =>	'AEGEE-Agrigento',
            'email'         =>  'aegee92100@gmail.com',
            'legacy_key'    =>  'AGR'
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
            'legacy_key'    =>  'ALI'
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
            'legacy_key'    =>  'AMS'
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
            'legacy_key'    =>  'ANK'
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
            'legacy_key'    =>  'ATH'
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
            'legacy_key'    =>  'BAK'
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
            'legacy_key'    =>  'BAM'
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
            'legacy_key'    =>  'BAN'
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
            'legacy_key'    =>  'BRC'
    	]);


    	Address::create([ //TODO: Address unknown
            'country_id'    =>  106,
            'street'        =>  '',
            'zipcode'       =>  '',
            'city'          =>  'Bari',
        ]);

    	Body::create([
            'type_id'       =>  1,
            'address_id'    =>  13,
    	    'name'	        =>	'AEGEE-Bari',
            'email'         =>  'bari@aegee.org',
	        'phone'	        =>  '3423892968',
            'legacy_key'    =>  'BAR'
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
            'legacy_key'    =>  'BEO'
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
            'legacy_key'    =>  'BEG'
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
            'legacy_key'    =>  'BER'
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
            'legacy_key'    =>  'BIA'
    	]);


	    Address::create([ //TODO: Address unknown
            'country_id'    =>  202,
            'street'        =>  '',
            'zipcode'       =>  '',
            'city'          =>  'Bilbao',
        ]);

    	Body::create([
            'type_id'       =>  1,
            'address_id'    =>  18,
    	    'name'	        =>	'AEGEE-Bilbao',
            'email'         =>  'aegeebilbao@gmail.com',
            'legacy_key'    =>  'BIL'
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
            'legacy_key'    =>  'BOL'
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
            'legacy_key'    =>  'BRA'
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
            'legacy_key'    =>  'BRE'
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
            'legacy_key'    =>  'BRN'
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
            'legacy_key'    =>  'BRU'
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
            'legacy_key'    =>  'BUC'
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
            'legacy_key'    =>  'BUD'
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
            'legacy_key'    =>  'BUR'
        ]);


        Address::create([
            'country_id'    =>  106,
            'street'        =>  'Viale Sant\'Ignazio 17',
            'zipcode'       =>  'CA 09125',
            'city'          =>  'Cagliari',
        ]);

    	Body::create([
            'type_id'       =>  1,
            'address_id'    =>  27,
    	    'name'	        =>	'AEGEE-Cagliari',
            'email'         =>  'info@aegeecagliari.com',
	        'phone'	        =>  '3403984894',
            'legacy_key'    =>  'CAG'
        ]);


        Address::create([ //TODO: Address unknown
            'country_id'    =>  220,
            'street'        =>  '',
            'zipcode'       =>  '',
            'city'          =>  'Canakkale',
        ]);

    	Body::create([
            'type_id'       =>  2,
            'address_id'    =>  28,
    	    'name'	        =>	'AEGEE-Canakkale',
            'email'         =>  'aegee.canakkale@gmail.com',
	        'phone'	        =>  '5432822552',
            'legacy_key'    =>  'CAN'
        ]);


        Address::create([
            'country_id'    =>  202,
            'street'        =>  'Campus de Riu Sec Universitat Jaume I',
            'zipcode'       =>  '12071',
            'city'          =>  'Castello de la Plana',
        ]);

    	Body::create([
            'type_id'       =>  1,
            'address_id'    =>  29,
    	    'name'	        =>	'AEGEE-Castello',
            'email'         =>  'info@aegeecastello.org',
	        'phone'	        =>  '964729363',
            'legacy_key'    =>  'CAS'
        ]);


        Address::create([
            'country_id'    =>  106,
            'street'        =>  'Via Orto dei Limoni 5',
            'zipcode'       =>  '95125',
            'city'          =>  'Catania',
        ]);

    	Body::create([
            'type_id'       =>  1,
            'address_id'    =>  30,
    	    'name'	        =>	'AEGEE-Catania',
            'email'         =>  'contact@aegee-catania.org',
	        'phone'	        =>  '3393467257',
            'legacy_key'    =>  'CAT'
        ]);


        Address::create([ //TODO: Address unknown
            'country_id'    =>  142,
            'street'        =>  '',
            'zipcode'       =>  '',
            'city'          =>  'Chisinau',
        ]);

    	Body::create([
            'type_id'       =>  1,
            'address_id'    =>  31,
    	    'name'	        =>	'AEGEE-Chisinau',
            'email'         =>  'aegeechisinau@yahoo.com',
	        'phone'	        =>  '68009079',
            'legacy_key'    =>  'CHI'
        ]);


        Address::create([
            'country_id'    =>  179,
            'street'        =>  'str. Marinescu Gheorghe, nr. 8, camin 14, parter',
            'zipcode'       =>  'OP1, CP1244',
            'city'          =>  'Cluj-Napoca',
        ]);

    	Body::create([
            'type_id'       =>  1,
            'address_id'    =>  32,
    	    'name'	        =>	'AEGEE-Cluj-Napoca',
            'email'         =>  'aegee_cjn@yahoo.com',
            'legacy_key'    =>  'CLU'
        ]);


        Address::create([
            'country_id'    =>  106,
            'street'        =>  'via Pietro Bucci, Blocco 1 app. 10 Centro residenziale della Calabria (Maisonettes)',
            'zipcode'       =>  '87036',
            'city'          =>  'Rende',
        ]);

    	Body::create([
            'type_id'       =>  1,
            'address_id'    =>  33,
    	    'name'	        =>	'AEGEE-Cosenza',
            'email'         =>  'aegeecosenza17@gmail.com',
	        'phone'	        =>  '3280133895',
            'legacy_key'    =>  'COS'
        ]);


        Address::create([
            'country_id'    =>  81,
            'street'        =>  'Hochschulstrasse 1',
            'zipcode'       =>  '64289',
            'city'          =>  'Darmstadt',
        ]);

    	Body::create([
            'type_id'       =>  1,
            'address_id'    =>  34,
    	    'name'	        =>	'AEGEE-Darmstadt',
            'email'         =>  'info@aegee-darmstadt.eu',
            'legacy_key'    =>  'DAR'
        ]);


        Address::create([
            'country_id'    =>  98,
            'street'        =>  'Nagyerdei krt. 96',
            'zipcode'       =>  '4032',
            'city'          =>  'Debrecen',
        ]);

    	Body::create([
            'type_id'       =>  1,
            'address_id'    =>  35,
    	    'name'	        =>	'AEGEE-Debrecen',
            'email'         =>  'info@aegee-debrecen.hu',
            'legacy_key'    =>  'DEB'
        ]);


        Address::create([
            'country_id'    =>  153,
            'street'        =>  'Leeghwaterstraat 42 (room 45-01-1.14)',
            'zipcode'       =>  '2628 CA',
            'city'          =>  'Delft',
        ]);

    	Body::create([
            'type_id'       =>  1,
            'address_id'    =>  36,
    	    'name'	        =>	'AEGEE-Delft',
            'email'         =>  'board@aegee-delft.nl',
	        'phone'	        =>  '152789893',
            'legacy_key'    =>  'DEL'
        ]);


        Address::create([ //TODO: Address unknown
            'country_id'    =>  225,
            'street'        =>  '',
            'zipcode'       =>  '',
            'city'          =>  'Dnipropetrovsk',
        ]);

    	Body::create([
            'type_id'       =>  2,
            'address_id'    =>  37,
    	    'name'	        =>	'AEGEE-Dnipropetrovsk',
            'email'         =>  'office.aegee@gmail.com',
	        'phone'	        =>  '504539845',
            'legacy_key'    =>  'DNI'
        ]);


        Address::create([
            'country_id'    =>  81,
            'street'        =>  'Strehlener Strasse 22',
            'zipcode'       =>  '01069',
            'city'          =>  'Dresden',
        ]);

    	Body::create([
            'type_id'       =>  1,
            'address_id'    =>  38,
    	    'name'	        =>	'AEGEE-Dresden',
            'email'         =>  'info@aegee-dresden.org',
	        'phone'	        =>  '15756085800',
            'legacy_key'    =>  'DRE'
        ]);


        Address::create([
            'country_id'    =>  81,
            'street'        =>  'Universitatsstrasse 1',
            'zipcode'       =>  '40225',
            'city'          =>  'Dusseldorf',
        ]);

    	Body::create([
            'type_id'       =>  1,
            'address_id'    =>  39,
    	    'name'	        =>	'AEGEE-Dusseldorf',
            'email'         =>  'board@aegee-dusseldorf.eu',
	        'phone'	        =>  '15126777180',
            'legacy_key'    =>  'DUS'
        ]);


        Address::create([
            'country_id'    =>  153,
            'street'        =>  'John F. Kennedylaan 3',
            'zipcode'       =>  '5612 AB',
            'city'          =>  'Eindhoven',
        ]);

    	Body::create([
            'type_id'       =>  1,
            'address_id'    =>  40,
    	    'name'	        =>	'AEGEE-Eindhoven',
            'email'         =>  'board@aegee-eindhoven.nl',
	        'phone'	        =>  '402472916',
            'legacy_key'    =>  'EIN'
        ]);


        Address::create([
            'country_id'    =>  153,
            'street'        =>  'Oude Markt 24',
            'zipcode'       =>  '7511 GB',
            'city'          =>  'Enschede',
        ]);

    	Body::create([
            'type_id'       =>  1,
            'address_id'    =>  41,
    	    'name'	        =>	'AEGEE-Enschede',
            'email'         =>  'board@aegee-enschede.nl',
	        'phone'	        =>  '534321040',
            'legacy_key'    =>  'ENS'
        ]);


        Address::create([
            'country_id'    =>  81,
            'street'        =>  'Nordhauserstrasse 63',
            'zipcode'       =>  '99089',
            'city'          =>  'Erfurt',
        ]);

    	Body::create([
            'type_id'       =>  1,
            'address_id'    =>  42,
    	    'name'	        =>	'AEGEE-Erfurt',
            'email'         =>  'aegee.erfurt@yahoo.de',
	        'phone'	        =>  '15222664406',
            'legacy_key'    =>  'ERF'
        ]);


        Address::create([
            'country_id'    =>  220,
            'street'        =>  'Anadolu Universitesi Yunusemre Kampusu Ogrenci Merkezi',
            'zipcode'       =>  'K324',
            'city'          =>  'Eskisehir',
        ]);

    	Body::create([
            'type_id'       =>  1,
            'address_id'    =>  43,
    	    'name'	        =>	'AEGEE-Eskisehir',
            'email'         =>  'info@aegee-eskisehir.org',
	        'phone'	        =>  '2223208060/4573',
            'legacy_key'    =>  'ESK'
        ]);


        Address::create([ //TODO: Address unknown
            'country_id'    =>  106,
            'street'        =>  '',
            'zipcode'       =>  '',
            'city'          =>  'Ferrara',
        ]);

    	Body::create([
            'type_id'       =>  2,
            'address_id'    =>  44,
    	    'name'	        =>	'AEGEE-Ferrara',
            'email'         =>  'aegee-ferrara@aegee.it',
            'legacy_key'    =>  'FER'
        ]);


        Address::create([
            'country_id'    =>  106,
            'street'        =>  'vicolo Santa Maria Maggiore 1',
            'zipcode'       =>  '50100',
            'city'          =>  'Firenze',
        ]);

    	Body::create([
            'type_id'       =>  1,
            'address_id'    =>  45,
    	    'name'	        =>	'AEGEE-Firenze',
            'email'         =>  'info@aegeefirenze.it',
	        'phone'	        =>  '559756913',
            'legacy_key'    =>  'FIR'
        ]);


        Address::create([
            'country_id'    =>  81,
            'street'        =>  'Kurfurstenstrasse 10',
            'zipcode'       =>  '60486',
            'city'          =>  'Frankfurt am Main',
        ]);

    	Body::create([
            'type_id'       =>  1,
            'address_id'    =>  46,
    	    'name'	        =>	'AEGEE-Frankfurt am Main',
            'email'         =>  'aegeefrankfurt@googlemail.com',
	        'phone'	        =>  '1719090415',
            'legacy_key'    =>  'FRA'
        ]);


        Address::create([ //TODO: Address unknown
            'country_id'    =>  220,
            'street'        =>  '',
            'zipcode'       =>  '',
            'city'          =>  'Gaziantep',
        ]);

    	Body::create([
            'type_id'       =>  2,
            'address_id'    =>  47,
    	    'name'	        =>	'AEGEE-Gaziantep',
            'email'         =>  'aegeegaziantep@gmail.com',
            'legacy_key'    =>  'GAZ'
        ]);


        Address::create([
            'country_id'    =>  174,
            'street'        =>  'Bazynskiego 1a/222',
            'zipcode'       =>  '80-952',
            'city'          =>  'Gdansk',
        ]);

    	Body::create([
            'type_id'       =>  1,
            'address_id'    =>  48,
    	    'name'	        =>	'AEGEE-Gdansk',
            'email'         =>  'gdansk@aegee.org',
	        'phone'	        =>  '720873027',
            'legacy_key'    =>  'GDA'
        ]);


        Address::create([ //TODO: Address unknown
            'country_id'    =>  106,
            'street'        =>  '',
            'zipcode'       =>  '',
            'city'          =>  'Genova',
        ]);

    	Body::create([
            'type_id'       =>  1,
            'address_id'    =>  49,
    	    'name'	        =>	'AEGEE-Genova',
            'email'         =>  'aegee.genova@gmail.com',
	        'phone'	        =>  '3383215260',
            'legacy_key'    =>  'GEN'
        ]);


        Address::create([
            'country_id'    =>  174,
            'street'        =>  'ul. Pszczynska 85/10 (1 pietro)',
            'zipcode'       =>  '44-100',
            'city'          =>  'Gliwice',
        ]);

    	Body::create([
            'type_id'       =>  1,
            'address_id'    =>  50,
    	    'name'	        =>	'AEGEE-Gliwice',
            'email'         =>  'office@aegee-gliwice.org',
	        'phone'	        =>  '322371834',
            'legacy_key'    =>  'GLI'
    	]);
    }
}
