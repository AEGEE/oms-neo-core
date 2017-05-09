<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\SaveBodyRequest;

use App\Models\Body;
use App\Models\BodyType;
use App\Models\Address;

use Excel;
use Input;

class BodyController extends Controller
{
    public function getBodies(Body $body, Request $req) {
        $max_permission = $req->get('max_permission');

        //TODO: rewrite search (filtering)
        $bodies = $body->getFiltered();

        return response()->success($bodies);
    }

    public function getBody($id) {
        //TODO Decide what (if) should be eager loaded.
        return response()->success(Body::findOrFail($id)->with(['bodyType', 'address' => function ($q) { $q->with('country');}])->get());
    }

    public function updateBody($id, SaveBodyRequest $req) {
        $body = Body::findOrFail($id);
        $body->name = $req->has('name') ? $req->name : $body->name;
        $body->email = $req->has('email') ? $req->email : $body->email;
        $body->phone = $req->has('phone') ? $req->phone : $body->phone;

        $body->type_id = $req->has('type_id') ? BodyType::findOrFail($req->type_id)->id : $body->type_id;
        $body->address_id = $req->has('address_id') ? Address::findOrFail($req->address_id)->id : $body->address_id;

        $body->save();

        return response()->success($body, null, "Body saved");
    }
}
