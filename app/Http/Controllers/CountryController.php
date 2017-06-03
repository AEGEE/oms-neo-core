<?php

namespace App\Http\Controllers;

use App\Models\Country;

class CountryController extends Controller
{
    public function getCountries() {
        return response()->success(Country::All());
    }

    public function getCountry(Country $country) {
        return response()->success($country);
    }
}
