<?php

namespace App\Http\Controllers;

use App\Models\BodyType;

class BodyTypeController extends Controller
{
    public function getBodyTypes() {
        return response()->success(BodyType::All());
    }

    public function getBodyTypes(BodyType $bodyType) {
        return response()->success($bodyType);
    }
}
