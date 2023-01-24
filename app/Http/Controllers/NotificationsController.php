<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libraries\Encrypt;
use App\Libraries\Helpers;
use App\Jobs\Gstr2AReconJob;
use App\Jobs\MerchantSendMail;
use Illuminate\Support\Facades\Storage;
use App\Model\Gst;
use Log;
use Validator;
use Exception;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\GstExport;
use PDO;


class NotificationsController extends Controller
{

    public function __construct()
    {
        $this->merchant_id = Encrypt::decode(Session::get('merchant_id'));
        $this->user_id = Encrypt::decode(Session::get('userid'));
    }

    /**
     * Getting started landing page
     */
    public function index()
    {
        $title = 'create';

        $data = Helpers::setBladeProperties('Inbox', ['expense', 'contract', 'product', 'template', 'invoiceformat'], [3, 180]);
        
        return view('/notifications/index',  $data );
    }
}
