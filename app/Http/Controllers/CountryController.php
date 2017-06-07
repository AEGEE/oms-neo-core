<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Http\Requests\CreateCountryRequest;
use App\Http\Requests\UpdateCountryRequest;

class CountryController extends Controller
{
    public function getCountries() {
        return response()->success(Country::All());
    }

    public function getCountry(Country $country) {
        return response()->success($country);
    }

    public function createCountry(CreateCountryRequest $req) {
        $arr = [
            'name'       => $req->name,
        ];
        $country = Country::create($arr);
        return response()->success($country, null, 'Country created');
    }

    public function updateCountry($country_id, UpdateCountryRequest $req) {
        $fields = array('name');
        $arr = array();

        foreach($fields as $field) {
            if ($req->has($field)) { $arr[$field] = $req->get($field);}
        }

        $country = Country::findOrFail($country_id);
        $country->update($arr);
        return response()->success($country, null, 'Country updated');
    }
}
