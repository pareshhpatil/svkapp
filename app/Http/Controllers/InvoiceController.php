<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\MasterModel;
use App\Models\InvoiceModel;
use App\Http\Controllers\ApiController;
use App\Http\Lib\Encryption;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Services\DynamoDBService;
use Illuminate\Support\Facades\Storage;


class InvoiceController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public $model = null;
    public $user_id = null;

    public function __construct()
    {
        $this->model = new InvoiceModel();
        $this->user_id = Session::get('user_id');
    }

    public function create()
    {
        $data['selectedMenu'] = [25, 31];
        $data['menus'] = Session::get('menus');
        $data['project_list'] = $this->model->getTableList('project', 'is_active', 1, 0, Session::get('project_access'));
        $data['det'] = [];
        $data['type'] = 1;
        dd($data['menus']);
        return view('web.invoice.create', $data);
    }

    public function save(Request $request)
    {

        $this->user_id = Session::get('user_id');
        $array['project_id'] = $request->project_id;
        $array['title'] = $request->title;
        $array['invoice_number'] = $request->invoice_number;
        $array['amount'] = $request->amount;
        $array['bill_date'] = $this->sqlDate($request->date);
        $array['month'] = $this->sqlDate($request->month);

        $invoice_id = $this->model->saveTable('ridetrack_invoice', $array, $this->user_id);
        foreach ($request->documents as $row) {
            $array = [];
            $array['document_name'] = $row['document_name'];
            $array['invoice_id'] = $invoice_id;
            $file = $row['file']; // This is an instance of UploadedFile
            $extension = $file->getClientOriginalExtension(); // Get original extension (e.g. pdf, xlsx)
            $file_name = 'documents/' . $request->invoice_number . '_' . str_replace(' ', '_', $array['document_name']) . '_' . rand(10, 99) . '.' . $extension;

            Storage::disk('s3')->putFileAs('', $file, $file_name);
            $array['url'] = Storage::disk('s3')->url($file_name);
            $this->model->saveTable('ridetrack_invoice_document', $array, $this->user_id);
        }

        if ($request->passenger_id > 0) {
            return redirect('/passenger/list')->withSuccess('Passenger updated successfully');
        } else {
            return redirect()->back()->withSuccess('Passengers added successfully');
        }
    }

    public function list()
    {
        $data['selectedMenu'] = [25, 32];
        $data['menus'] = Session::get('menus');
        $data['enc'] = Encryption::encode(date('Y-m-d'));
        $invoices = $this->model->getInvoiceList(Session::get('project_access'));
        foreach ($invoices as $k => $row) {
            $data['invoices'][$k] = $row;
            $data['invoices'][$k]->documents = $this->model->getTableList('ridetrack_invoice_document', 'invoice_id', $row->invoice_id, 0, [], [], 'document_name,url');
        }
        return view('web.invoice.list', $data);
    }
    public function delete($id)
    {
        $this->model->updateTable('ridetrack_invoice', 'id', $id, 'is_active', 0);
        return redirect()->back()->withSuccess('Invoice deleted successfully');
    }
}
