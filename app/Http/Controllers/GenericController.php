<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Models\Antenna;

class GenericController extends Controller
{
    public function test(Antenna $ant) {
    	return $ant->with('country')->find(1);
    }
}
