<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Session;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $user_id = null;


    public function sqlDate($date, $format = 'Y-m-d')
    {
        return date($format, strtotime($date));
    }
    public function sqlDateTime($date, $format = 'Y-m-d H:i:s')
    {
        return date($format, strtotime($date));
    }
    public function sqlTime($time)
    {
        return date('H:i:s', strtotime($time));
    }
    public function htmlTime($time)
    {
        if ($time != '') {
            return date('h:i:A', strtotime($time));
        }
    }
    public function htmlDate($time, $dateonly = 0)
    {
        if ($dateonly == 1) {
            return date('d M Y', strtotime($time));
        }
        $w = substr(date("l", strtotime($time)), 0, 3);
        if ($time != '') {
            return $w . date(' d M Y', strtotime($time));
        }
    }

    public function htmlDateTime($time)
    {
        $w = substr(date("l", strtotime($time)), 0, 3);
        if ($time != '') {
            return $w . date(' d M y h:i:A', strtotime($time));
        }
    }
}
