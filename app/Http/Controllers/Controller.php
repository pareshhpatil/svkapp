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

    public function sqlDate($date)
    {
        return date('Y-m-d', strtotime($date));
    }
    public function sqlTime($time)
    {
        return date('H:i:s', strtotime($time));
    }
}
