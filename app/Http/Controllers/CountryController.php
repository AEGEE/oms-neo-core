<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Http\Requests\CreateCountryRequest;

class CountryController extends Controller
{
    public function getCountries() {
        return response()->success(Country::All());
    }

    public function getCountry(Country $country) {
        return response()->success($country);
    }

    public function createCountry(CreateCountry $req) {
        $arr = [
            'name'       => $req->name,
        ];
        $country = Country::create($arr);
        return response()->success($country, null, 'Country created');
    }
}
