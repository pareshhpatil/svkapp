<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BookModel;


class BookController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $model;
    public function __construct()
    {
        $this->model = new BookModel();
    }



    public function dashboard(Request $request)
    {
        $data=$this->model->getDashboard();

        return response()->json($data, 200);
    }
}
