<?php

namespace App\Http\Controllers;

class ServiceController extends Controller
{
    public function ping() {
        if (\DatabaseSeeder::isSeeded()) {
            return response()->success(null, null, 'The service is up and running.');
        } else {
            return response()->failure();
        }
    }
}
