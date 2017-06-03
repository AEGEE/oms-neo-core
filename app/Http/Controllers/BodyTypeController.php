<?php

namespace App\Http\Controllers;

use App\Models\BodyType;
use App\Http\Requests\CreateBodyTypeRequest;
use App\Http\Requests\UpdateBodyTypeRequest;

class BodyTypeController extends Controller
{
    public function getBodyTypes() {
        return response()->success(BodyType::All());
    }

    public function getBodyType(BodyType $body_type) {
        return response()->success($body_type);
    }

    public function createBodyType(CreateBodyTypeRequest $req) {
        $arr = [
            'name'       => $req->name,
        ];
        $bodyType = BodyType::create($arr);
        return response()->success($bodyType, null, 'BodyType created');
    }

    public function updateBodyType(UpdateBodyTypeRequest $req) {
        $fields = array('name');
        $arr = array();

        foreach($fields as $field) {
            if ($req->has($field)) { $arr[$field] = $req->get($field);}
        }

        $bodyType = BodyType::find($req->id);
        $bodyType->update($arr);
        return response()->success($bodyType, null, 'BodyType updated');
    }
}