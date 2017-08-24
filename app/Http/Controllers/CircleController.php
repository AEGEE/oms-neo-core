<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models;
use Input;

class CircleController extends Controller
{
    public function getCircles() {
        return response()->success(Models\Circle);
    }

    public function getCircle($circle_id) {
        return response()->success(Models\Circle::where('id', $circle_id)->with(['body', 'childrenCircles', 'parentCircle'])->first());
    }

    public function getCirclesOfBody($body_id) {
        return response()->success(Models\Body::findOrFail($body_id)->circles);
    }

    public function getCirclesOfUser($user_id) {
        return response()->success(Models\User::findOrFail($user_id)->circles);
    }

    //RECURSIVE METHODS
    public function getCircleMembers($circle_id) {
        $circle = Models\Circle::findOrFail($circle_id);
        if (Input::get('recursive', 'false') != 'true') {
            return response()->success($circle->users);
        } else {
            $circles = $circle->getChildrenRecursive();
            return response()->success($circles->flatMap(function ($circle) { return $circle->users;}));
        }
    }

    public function getCircleChildren($circle_id) {
        $circle = Models\Circle::findOrFail($circle_id);
        if (Input::get('recursive', 'false') != 'true') {
            return response()->success($circle->childrenCircles);
        } else {
            $circles = $circle->getChildrenRecursive();
            $circles->shift(); //Remove the starting circle
            return response()->success($circles);
        }
    }

    public function getCircleParents($circle_id) {
        $circle = Models\Circle::findOrFail($circle_id);
        if (Input::get('recursive', 'false') != 'true') {
            return response()->success($circle->parentCircle);
        } else {
            $circles = $circle->getParentsRecursive();
            $circles->shift(); //Remove the starting circle
            return response()->success($circles);
        }
    }
}
