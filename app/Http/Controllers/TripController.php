<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Lib\Encryption;

class TripController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function tripDetails($type,$user_id,$link)
    {
        return view('home');
    }
}
