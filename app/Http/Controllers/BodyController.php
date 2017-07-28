<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\UpdateBodyRequest;
use App\Http\Requests\CreateBodyRequest;

use App\Models\Body;
use App\Models\BodyType;
use App\Models\Address;

use Excel;
use Input;

class BodyController extends Controller
{
    public function getBodies(Request $req) {
        $max_permission = $req->get('max_permission');

        // Extract URL arguments to filter on.
        $search = [
            'name'          => $req->name,
            'city'          => $req->city,
            'type_id'       => $req->type_id,
            'country_id'    => $req->country_id,
            'country_name'  => $req->country_name,
            ];

        $bodies = Body::filterArray($search)->with(['bodyType', 'address.country', 'circles', 'users'])->get();

        return response()->success($bodies);
    }

    public function getBody($body_id) {
        //TODO Decide what (if) should be eager loaded.
        return response()->success(Body::where('id', $body_id)->with(['bodyType', 'address.country', 'circles', 'users'])->first());
    }

    public function updateBody($body_id, UpdateBodyRequest $req) {
        $body = Body::findOrFail($body_id);
        $body->name = $req->has('name') ? $req->name : $body->name;
        $body->email = $req->has('email') ? $req->email : $body->email;
        $body->phone = $req->has('phone') ? $req->phone : $body->phone;
        $body->description = $req->has('description') ? $req->description : $body->description;

        $body->type_id = $req->has('type_id') ? BodyType::findOrFail($req->type_id)->id : $body->type_id;
        $body->address_id = $req->has('address_id') ? Address::findOrFail($req->address_id)->id : $body->address_id;

        $body->save();

        return response()->success($body, null, "Body saved");
    }

    public function createBody(CreateBodyRequest $req) {
        $arr = [
            'type_id'       => $req->type_id,
            'address_id'    => $req->address_id,
            'name'          => $req->name,
            'email'         => $req->email,
            'phone'         => $req->phone,
            'description'   => $req->description,
        ];
        $body = Body::create($arr);
        return response()->success($body, null, 'Body created');
    }
}
