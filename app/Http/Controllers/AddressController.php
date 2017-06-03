<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Http\Requests\CreateAddressRequest;
use App\Http\Requests\UpdateAddressRequest;

class AddressController extends Controller
{
    public function getAddresses() {
        return response()->success(Address::All());
    }

    public function getAddress(Address $address) {
        return response()->success($address);
    }

    public function createAddress(CreateAddressRequest $req) {
        $arr = [
            'country_id'    => $req->country_id,
            'street'        => $req->street,
            'zipcode'       => $req->zipcode,
            'city'          => $req->city,
        ];
        $address = Address::create($arr);
        return response()->success($address, null, 'Address created');
    }

    public function updateAddress(UpdateAddressRequest $req) {
        $fields = array('country_id', 'street', 'zipcode', 'city');
        $arr = array();

        foreach($fields as $field) {
            if ($req->has($field)) { $arr[$field] = $req->get($field);}
        }

        $address = Address::find($req->id);
        $address->update($arr);
        return response()->success($address, null, 'Address updated');
    }
}
