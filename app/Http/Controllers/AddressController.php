<?php

namespace App\Http\Controllers;

use App\Models\Address;

class AddressController extends Controller
{
    public function getAddresses() {
        return response()->success(Address::All());
    }

    public function getAddress(Address $address) {
        return response()->success($address);
    }
}
