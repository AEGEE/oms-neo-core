<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models;
use Input;

class CircleController extends Controller
{
    //LOCAL
    public function getCircles() {
        return response()->success(Models\Circle::get());
    }

    public function getCircle($circle_id) {
        return response()->success(Models\Circle::where('id', $circle_id)->with(['body', 'childrenCircles'])->first());
    }

    public function getCircleMembers($circle_id) {
        $circles = collect([Models\Circle::findOrFail($circle_id)]);
        if (Input::get('recursive', 'false') == 'true') {
            $circles = $circles->get(0)->getChildrenRecursive();
        }
        return response()->success($circles->flatMap(function ($circle) { return $circle->users;}));
    }

    public function getCirclesOfBody($body_id) {
        return response()->success(Models\Body::findOrFail($body_id)->circles);
    }

    public function getCirclesOfUser($user_id) {
        return response()->success(Models\User::findOrFail($user_id)->circles);
    }
}
