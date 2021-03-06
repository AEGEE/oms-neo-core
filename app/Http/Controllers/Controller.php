<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use App\Models\User;

use File;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function getAppVersion() {
        $path = storage_path()."/../.version";
        return File::get($path);
    }

    protected function getUpdateArray($req, $fields, $allowNull = true) {
        $arr = array();

        foreach($fields as $field) {
            if ($req->has($field) && ($allowNull || $req->get($field)!=null)) { $arr[$field] = $req->get($field);}
        }
        return $arr;
    }

    protected function isMapsDefined() {
        $mapsKey = config('services.google_maps');

        if(!empty($mapsKey)) {
            return $mapsKey;
        }

        return false;
    }
}
