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
        $search = array();
        $bodies = $body->getFiltered($search);

        return response()->json($bodies);
    }

    public function saveBody($id, SaveBodyRequest $req) {
        $body = Body::findOrFail($id);
        $body->name = $req->name;
        $body->email = $req->email;
        $body->phone = $req->phone;

        $body->type_id = BodyType::findOrFail($req->type_id)->id;
        $body->address_id = Address::findOrFail($req->address_id)->id;

        $body->save();

        return response()->json($body);
    }

    public function getBody($id) {
        return response()->json(Body::findOrFail($id)->with(['bodyType', 'address' => function ($q) { $q->with('country');}])->get());
    }
}
