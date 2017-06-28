<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models;

class CircleController extends Controller
{
    //LOCAL
    public function getCircles() {
        return response()->success(Models\BodyCircle::get());
    }

    public function getCircle($circle_id) {
        return response()->success(Models\Circle::where('id', $circle_id)->with(['body', 'childrenCircles'])->first());
    }

    public function getCircleMembers($circle_id) {
        return response()->success(Models\Circle::findOrFail($circle_id)->getUsers());
    }

    public function getCirclesOfBody($body_id) {
        return response()->success(Models\Body::findOrFail($body_id)->circles);
    }

    public function getCirclesOfUser($user_id) {
        return response()->success(Models\User::findOrFail($user_id)->getCircles());
    }
}
