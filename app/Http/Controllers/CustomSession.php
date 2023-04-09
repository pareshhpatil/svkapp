<?php

namespace App\Http\Controllers;

use Session;
use Illuminate\Routing\Controller as BaseController;

class CustomSession extends BaseController {

    static function set($key, $value) {
        Session::put($key, $value);
        Session::save();
    }

    static function remove($key) {
        Session::forget($key);
    }

    static function get($key) {
        return Session::get($key);
    }

    static function destroy() {
        //unset($_SESSION);
        Session::flush();
    }

}
