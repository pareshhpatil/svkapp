<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DashboardController
 *
 * @author Paresh
 */

namespace App\Http\Controllers;

use App\Model\Logsheet;
use App\Model\Master;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;

class LogsheetController extends Controller {

    private $logsheet_model;
    private $master_model;

    function __construct() {
        parent::__construct();
        $this->validateSession(1);
        $this->logsheet_model = new Logsheet();
        $this->master_model = new Master();
    }

    public function logsheet() {
        $vehicle_list = $this->master_model->getMaster('vehicle', $this->admin_id);
        $company_list = $this->master_model->getMaster('company', $this->admin_id);
        $invoice_list = $this->logsheet_model->getLogsheetBill($this->admin_id);

        $int = 0;
        foreach ($invoice_list as $item) {
            $link = $this->encrypt->encode($item->invoice_id);
            $invoice_list[$int]->link = $link;
            $int++;
        }

        $data['title'] = 'Logsheet';
        $data['current_date'] = date('d-m-Y');
        $data['vehicle_list'] = $vehicle_list;
        $data['company_list'] = $company_list;
        $data['invoice_list'] = $invoice_list;
        return view('logsheet.list', $data);
    }

    public function printlogsheet($link) {
        $id = $this->encrypt->decode($link);
        $invoice = $this->master_model->getMasterDetail('logsheet_invoice', 'invoice_id', $id);
        $logsheet_detail = $this->master_model->getMaster('logsheet_detail', $id, 'invoice_id');
        $data['invoice'] = $invoice;
        $data['vehicle_id'] = $invoice->vehicle_id;
        $data['company_id'] = $invoice->company_id;
        $data['month'] = date('M-Y', strtotime($invoice->date));
        $date = $invoice->date;

        $type = 1;
        $list = $this->logsheet_model->getBillData($date, $data['company_id'], $data['vehicle_id']);
        $int = 0;
        foreach ($list as $item) {
            $type = $item->type;
            $link = $this->encrypt->encode($item->logsheet_id);
            $list[$int]->link = $link;
            $int++;
        }
        $vehicle = $this->master_model->getMasterDetail('vehicle', 'vehicle_id', $data['vehicle_id']);
        $company = $this->master_model->getMasterDetail('company', 'company_id', $data['company_id']);
        $logsheet_detail = json_decode(json_encode($logsheet_detail), true);
        $data['logsheet_detail'] = $logsheet_detail;
        $data['vehicle'] = $vehicle;
        $data['company'] = $company;
        $data['list'] = $list;
        $data['title'] = 'Logsheet Print';
        $data['type'] = $type;
        $type = ($type == 2) ? 'location' : 'default';
        return view('logsheet.' . $type . '_excel', $data);
    }
    
    public function printbill($link) {
        $id = $this->encrypt->decode($link);
        $invoice = $this->master_model->getMasterDetail('logsheet_invoice', 'invoice_id', $id);
        $logsheet_detail = $this->master_model->getMaster('logsheet_detail', $id, 'invoice_id');
        $admin = $this->master_model->getMasterDetail('admin','admin_id', $this->admin_id);
        $data['admin'] = $admin;
        $data['invoice'] = $invoice;
        $data['vehicle_id'] = $invoice->vehicle_id;
        $data['company_id'] = $invoice->company_id;
        $data['month'] = date('M-Y', strtotime($invoice->date));
        $date = $invoice->date;

        $type = 1;
        $list = $this->logsheet_model->getBillData($date, $data['company_id'], $data['vehicle_id']);
        $int = 0;
        foreach ($list as $item) {
            $type = $item->type;
            $link = $this->encrypt->encode($item->logsheet_id);
            $list[$int]->link = $link;
            $int++;
        }
        $vehicle = $this->master_model->getMasterDetail('vehicle', 'vehicle_id', $data['vehicle_id']);
        $company = $this->master_model->getMasterDetail('company', 'company_id', $data['company_id']);
        $logsheet_detail = json_decode(json_encode($logsheet_detail), true);
        $data['logsheet_detail'] = $logsheet_detail;
        $data['vehicle'] = $vehicle;
        $data['company'] = $company;
        $data['list'] = $list;
        $data['title'] = 'Logsheet Print';
        $data['type'] = $type;
        $type = ($type == 2) ? 'location' : 'default';
        return view('logsheet.bill', $data);
    }

    public function logsheetbillsave(Request $request) {
        $date = date('Y-m-d', strtotime($request->date));
        $bill_date = date('Y-m-d', strtotime($request->bill_date));
        $toll = ($_POST['amount'][5] > 0) ? $_POST['amount'][5] : 0;
        if ($request->invoice_id > 0) {
            $invoice_id = $request->invoice_id;
            $invoice_id = $this->logsheet_model->updateLogsheetInvoice($invoice_id, $request->vehicle_id, $request->company_id, $date, $bill_date, $request->cgst, $request->sgst, $request->igst, $request->total_gst, $request->base_total, $request->grand_total, $toll, $request->type, $this->user_id, $this->admin_id);
        } else {
            $invoice_number = $this->logsheet_model->getInvoiceNumber($request->invoice_seq);
            $invoice_id = $this->logsheet_model->saveLogsheetInvoice($invoice_number, $request->vehicle_id, $request->company_id, $date, $bill_date, $request->cgst, $request->sgst, $request->igst, $request->total_gst, $request->base_total, $request->grand_total, $toll, $request->type, $this->user_id, $this->admin_id);
        }
        $int = 0;
        foreach ($_POST['int'] as $row) {
            if ($_POST['detail_id'][$int] > 0) {
                $this->logsheet_model->updateLogsheetDetail($_POST['detail_id'][$int], $_POST['particular_name'][$int], $_POST['unit'][$int], $_POST['qty'][$int], $_POST['rate'][$int], $_POST['amount'][$int], $_POST['is_deduct'][$int], $this->user_id);
            } else {
                $this->logsheet_model->saveLogsheetDetail($invoice_id, $_POST['particular_name'][$int], $_POST['unit'][$int], $_POST['qty'][$int], $_POST['rate'][$int], $_POST['amount'][$int], $_POST['is_deduct'][$int], $this->user_id);
            }
            $int++;
        }
        $this->setSuccess('Logsheet Bill has been save successfully');
        header('Location: /admin/logsheet');
        exit;
    }

    public function generatebill($link = null) {
        $id = 0;
        if (isset($_POST['vehicle_id'])) {
            $data['vehicle_id'] = $_POST['vehicle_id'];
            $data['company_id'] = $_POST['company_id'];
            $data['month'] = $_POST['date'];
            $date = date('Y-m-d', strtotime('01-' . $_POST['date']));
            $id = $this->logsheet_model->getInvoiceId($_POST['company_id'], $_POST['vehicle_id'], $date, $this->admin_id);
        } else {
            $data['vehicle_id'] = 0;
            $data['company_id'] = 0;
            $data['month'] = '';
        }

        if ($link != null) {
            $id = $this->encrypt->decode($link);
        }
        if ($id > 0) {
            $invoice = $this->master_model->getMasterDetail('logsheet_invoice', 'invoice_id', $id);
            $logsheet_detail = $this->master_model->getMaster('logsheet_detail', $id, 'invoice_id');
            $data['invoice'] = $invoice;
            $data['vehicle_id'] = $invoice->vehicle_id;
            $data['company_id'] = $invoice->company_id;
            $data['month'] = date('M-Y', strtotime($invoice->date));
            $date = $invoice->date;
        }

        $type = 1;
        if ($data['vehicle_id'] > 0) {
            $list = $this->logsheet_model->getBillData($date, $data['company_id'], $data['vehicle_id']);
            $int = 0;
            foreach ($list as $item) {
                $type = $item->type;
                $link = $this->encrypt->encode($item->logsheet_id);
                $list[$int]->link = $link;
                $int++;
            }
            $sequence = $this->master_model->getMaster('sequence', $this->admin_id);
            $vehicle = $this->master_model->getMasterDetail('vehicle', 'vehicle_id', $data['vehicle_id']);
            $company = $this->master_model->getMasterDetail('company', 'company_id', $data['company_id']);

            if (empty($logsheet_detail)) {
                $total_days = date(' t ', strtotime($date));
                $day_rate = round(45000 / $total_days, 2);
                $array[] = array('particular_name' => 'Fixed monthly charges', 'unit' => 'Month', 'qty' => '1', 'rate' => '45000.00', 'amount' => '45000.00', 'is_deduct' => '0', 'id' => 0);
                $array[] = array('particular_name' => 'Extra Day', 'unit' => 'Day', 'qty' => '', 'rate' => $day_rate, 'amount' => '', 'is_deduct' => '0', 'id' => 0);
                $array[] = array('particular_name' => 'Extra KM.', 'unit' => 'KM', 'qty' => '', 'rate' => '12.00', 'amount' => '', 'is_deduct' => '0', 'id' => 0);
                $array[] = array('particular_name' => 'Extra Hour', 'unit' => 'Hour', 'qty' => '', 'rate' => '65.00', 'amount' => '', 'is_deduct' => '0', 'id' => 0);
                $array[] = array('particular_name' => 'Break down', 'unit' => 'Day', 'qty' => '', 'rate' => '', 'amount' => '', 'is_deduct' => '1', 'id' => 0);
                $array[] = array('particular_name' => 'Toll /Parking', 'unit' => '', 'qty' => '', 'rate' => '', 'amount' => '', 'is_deduct' => '0', 'id' => 0);
                $logsheet_detail = $array;
            } else {
                $logsheet_detail = json_decode(json_encode($logsheet_detail), true);
            }
            $data['logsheet_detail'] = $logsheet_detail;
            $data['vehicle'] = $vehicle;
            $data['sequence'] = $sequence;
            $data['company'] = $company;
            $data['list'] = $list;
        }
        $vehicle_list = $this->master_model->getMaster('vehicle', $this->admin_id);
        $company_list = $this->master_model->getMaster('company', $this->admin_id);
        $data['title'] = 'Generate Bill';
        $data['vehicle_list'] = $vehicle_list;
        $data['company_list'] = $company_list;
        $data['type'] = $type;
        $type = ($type == 2) ? 'location' : 'default';
        return view('logsheet.' . $type . '_bill', $data);
    }

    public function logsheetdelete($link) {
        $id = $this->encrypt->decode($link);
        $this->master_model->deleteReccord('logsheet_bill', 'logsheet_id', $id, $this->user_id);
        $this->setSuccess('Entry has been deleted successfully');
        header('Location: /admin/logsheet/generatebill');
        exit;
    }

    public function confirmLogsheet(Request $request) {
        $type = $request->type;
        $vehicle = $this->master_model->getMasterDetail('vehicle', 'vehicle_id', $request->vehicle_id);
        $company = $this->master_model->getMasterDetail('company', 'company_id', $request->company_id);
        $toll = ($_POST['toll_amount'] > 0) ? $_POST['toll_amount'] : 0;
        $totalkm = $_POST['end_km'] - $_POST['start_km'];
        echo '<table class="table table-bordered table-condensed" style="margin: 0px 0 0px 0 !important;">';
        echo ' <tr><td><b>Vehicle number:</b></td><td>' . $vehicle->name . '</td></tr>';
        echo ' <tr><td><b>Comapny name:</b></td><td>' . $company->name . '</td></tr>';
        echo ' <tr><td><b>Date:</b></td><td>' . $_POST['date'] . '</td></tr>';
        echo ' <tr><td><b>Start KM:</b></td><td>' . $_POST['start_km'] . '</td></tr>';
        echo ' <tr><td><b>End KM:</b></td><td>' . $_POST['end_km'] . '</td></tr>';
        echo ' <tr><td><b>Total KM:</b></td><td>' . $totalkm . '</td></tr>';
        if ($type == 1) {
            echo ' <tr><td><b>Start Time:</b></td><td>' . date('h:i A', strtotime($_POST['start_time'])) . '</td></tr>';
            echo ' <tr><td><b>Close Time:</b></td><td>' . date('h:i A', strtotime($_POST['close_time'])) . '</td></tr>';
        } else {
            echo ' <tr><td><b>From Location:</b></td><td>' . $_POST['from'] . '</td></tr>';
            echo ' <tr><td><b>To Location:</b></td><td>' . $_POST['to'] . '</td></tr>';
        }
        echo ' <tr><td><b>Toll:</b></td><td>' . $toll . '</td></tr>';
        echo ' <tr><td><b>Remark:</b></td><td>' . $_POST['remark'] . '</td></tr>';
        echo '</tbody></table>';
    }

    public function saveLogsheet(Request $request) {
        $type = $request->type;
        $pick_drop = $request->pickup;
        $vehicle_id = isset($_POST['vehicle_id']) ? $_POST['vehicle_id'] : 0;
        $company_id = isset($_POST['company_id']) ? $_POST['company_id'] : 0;
        $_POST['toll_amount'] = ($_POST['toll_amount'] > 0) ? $_POST['toll_amount'] : 0;
        $start_time = date('H:i:s', strtotime($request->start_time));
        $close_time = date('H:i:s', strtotime($request->close_time));
        if ($type == 2) {
            $start_time = '00:00:00';
            $close_time = '00:00:00';
        } else {
            $pick_drop = '';
            $_POST['from'] = '';
            $_POST['to'] = '';
        }
        $day_night = (isset($request->day_night)) ? $request->day_night : 'Day';

        $date = date('Y-m-d', strtotime($request->date));
        $result = $this->logsheet_model->saveLogsheetbill($request->vehicle_id, $request->company_id, $date, $request->start_km, $request->end_km, $start_time, $close_time, $day_night, $_POST['remark'], $_POST['toll_amount'], $type, $pick_drop, $_POST['from'], $_POST['to'], $this->user_id, $this->admin_id);
        echo 'Logsheet has been saved successfully';
    }

}
