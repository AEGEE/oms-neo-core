<?php

namespace App\Traits;

trait RequiresPermission {

    public function __call($method, $parameters) {
        dump("Checking permissions for: " . $method);
        dump($parameters);
        return parent::__call($method, $parameters);
    }

    public static function __callStatic($method, $parameters) {
        dump("Checking permissions for: " . $method);
        dump($parameters);
        return parent::__callStatic($method, $parameters);
    }
}
