<?php

namespace app\helpers;

class Uri {

    public $hello = 1;

    public static function get(string $type = 'path')
    {
        return parse_url($_SERVER['REQUEST_URI'])[$type];
    }
}