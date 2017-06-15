<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models;

class CircleController extends Controller
{
    //GLOBAL
    public function getGlobalCircles() {
        return response()->success(Models\GlobalCircle::get());
    }

    public function getGlobalCircle($circle_id) {
        return response()->success(Models\GlobalCircle::where('id', $circle_id)->with(['bodyCircles'])->first());
    }

    public function getChildrenOfGlobalCircle($circle_id) {
        return response()->success(Models\GlobalCircle::findOrFail($circle_id)->bodyCircles);
    }

    public function getMembersOfGlobalCircle($circle_id) {
        return response()->success(Models\GlobalCircle::findOrFail($circle_id)->bodyCircles->flatMap(function($bodyCircle) {
            return $bodyCircle->getUsers();
        })->unique());
    }



    //LOCAL
    public function getBodyCircles() {
        return response()->success(Models\BodyCircle::get());
    }

    public function getBodyCircle($circle_id) {
        return response()->success(Models\BodyCircle::where('id', $circle_id)->with(['body', 'globalCircle'])->first());
    }

    public function getMembersOfBodyCircle($circle_id) {
        return response()->success(Models\BodyCircle::findOrFail($circle_id)->getUsers());
    }

    public function getCirclesOfBody($body_id) {
        return response()->success(Models\Body::findOrFail($body_id)->bodyCircles);
    }

    public function getCirclesOfUser($user_id) {
        return response()->success(Models\User::findOrFail($user_id)->getCircles());
    }
}
