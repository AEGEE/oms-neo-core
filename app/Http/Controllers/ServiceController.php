<?php

namespace App\Http\Controllers;

class ServiceController extends Controller
{
    public function ping() {
        if (file_exists("/var/private/omscore.bootstrapped")) {
            return response()->success(null, null, 'The service is up and running.');
        } else {
            return response()->failure();
        }
    }
}
