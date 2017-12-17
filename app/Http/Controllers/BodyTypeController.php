<?php

namespace App\Http\Controllers;

use App\Models\BodyType;
use App\Http\Requests\CreateBodyTypeRequest;
use App\Http\Requests\UpdateBodyTypeRequest;

class BodyTypeController extends Controller
{
    public function getBodyTypes() {
        return response()->success(BodyType::query());
    }

    public function getBodyType(BodyType $body_type_id) {
        return response()->success($body_type_id);
    }

    public function createBodyType(CreateBodyTypeRequest $req) {
        $arr = [
            'name'       => $req->name,
        ];
        $bodyType = BodyType::create($arr);
        return response()->success($bodyType, null, 'BodyType created');
    }

    public function updateBodyType($body_type_id, UpdateBodyTypeRequest $req) {
        $fields = array('name');
        $arr = array();

        foreach($fields as $field) {
            if ($req->has($field)) { $arr[$field] = $req->get($field);}
        }

        $bodyType = BodyType::findOrFail($body_type_id);
        $bodyType->update($arr);
        return response()->success($bodyType, null, 'BodyType updated');
    }
}
