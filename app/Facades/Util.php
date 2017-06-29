<?php

namespace App\Facades;

class Util
{
    public static function encodeSimple($string) {
        return strtolower(iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $string));
    }
}
