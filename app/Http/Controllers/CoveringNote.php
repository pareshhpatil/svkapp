<?php

namespace App\Http\Controllers;
use App\Libraries\Encrypt;
use App\Model\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CoveringNote extends Controller
{
    private $merchant_id = null;
    private $invoiceModel = null;
    public function __construct()
    {
        // $this->contract_model = new Contract();
        // $this->masterModel = new Master();
         $this->invoiceModel = new Invoice();

        $this->merchant_id = Encrypt::decode(Session::get('merchant_id'));
       
    }

    public function getSingleCoverNote($covering_note_id)
    {
        $data = $this->invoiceModel->getSingleCoveringNoteDetails($covering_note_id);
        return $data;
    }
}
