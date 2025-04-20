<?php

namespace App\Models;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of payout
 *
 * @author Paresh
 */

use Log;
use App\Models\ParentModel;
use Illuminate\Support\Facades\DB;
use Exception;

class BookModel extends ParentModel
{

    public function getDashboard()
    {
        $retObj = DB::table('book_dashboard as d')
            ->join('book_books as b', 'd.book_id', '=', 'b.id')
            ->join('book_author as a', 'b.author_id', '=', 'a.id')
            ->where('d.is_active', 1)
            ->select(DB::raw("d.*"))
            ->get();
        return $retObj;
    }


}
