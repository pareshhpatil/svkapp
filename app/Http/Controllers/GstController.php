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


class GstController extends Controller
{

    private $gstModel;

    public function __construct()
    {
        $this->gstModel = new Gst();
        $this->merchant_id = Encrypt::decode(Session::get('merchant_id'));
        $this->user_id = Encrypt::decode(Session::get('userid'));
    }

    /**
     * Getting started landing page
     */
    public function gstr2aLanding(Request $request)
    {
        $gstModel = new Gst();
        $data = Helpers::setBladeProperties('GSTR 2B Reconciliation',  ['expense'], []);

        $breadcrumbs['menu'] = 'GSTR 2B Reconciliation';
        $breadcrumbs['title'] = $data['title'];
        $breadcrumbs['url'] = '/merchant/gst/reconciliation';
        $request->session()->put('breadcrumbs', $breadcrumbs);

        $data['datatablejs'] = 'table-no-export';
        $data['gst2ra'] = 'gst2ra';
        $data['gst_list'] = $gstModel->getGSTList($this->merchant_id);
        $data['gst_list_count'] = count($data['gst_list']);

        return view('/gst/index', $data);
    }

    public function gstr2aLandingData(Request $request)
    {
        $gstModel = new Gst();
        $list = $gstModel->getJobList($this->merchant_id);
        if (!empty($list)) {
            foreach ($list as $key => $row) {
                $list[$key]->encrypted_job_id = Encrypt::encode($row->id);
                $list[$key]->gst_from =  date('M', mktime(0, 0, 0, $row->gst_from_month, 10)) . '-' . $row->gst_from_year;
                $list[$key]->gst_to =  date('M', mktime(0, 0, 0, $row->gst_to_month, 10)) . '-' . $row->gst_to_year;
                $list[$key]->mydata_from =  date('M', mktime(0, 0, 0, $row->expense_from_month, 10)) . '-' . $row->expense_from_year;
                $list[$key]->mydata_to =  date('M', mktime(0, 0, 0, $row->expense_to_month, 10)) . '-' . $row->expense_to_year;
            }
        }
        return Datatables::of($list)
            ->addColumn('action', function ($list) {
                if ($list->status == 'processed') {
                    return ' <div class="btn-group">
                                <button class="btn btn-link dropdown-toggle" type="button" data-toggle="dropdown">
                                &nbsp;&nbsp;<i class="fa fa-ellipsis-v"></i>&nbsp;&nbsp;
                                </button>
                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="/merchant/gst/reconciliation/summary/' . $list->encrypted_job_id . '" target="_blank"><i class="fa fa-download"></i>View Summary</a></span>
                                    </li>
                                    <li>
                                        <a href="/merchant/gst/reconciliation/detail/' . $list->encrypted_job_id . '/all/all" target="_blank"><i class="fa fa-undo"></i>View Details</a>
                                    </li>
                                    <li>
                                        <a onclick="deleteRowbyJobID(' . $list->id . ')"><i class="fa fa-undo"></i>Delete</a>
                                    </li>
                                </ul>
                            </div>';
                } else if ($list->status == 'error') {
                    return ' <div class="btn-group">
                                <button class="btn btn-link dropdown-toggle" type="button" data-toggle="dropdown">
                                &nbsp;&nbsp;<i class="fa fa-ellipsis-v"></i>&nbsp;&nbsp;
                                </button>
                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a onclick="deleteRowbyJobID(' . $list->id . ')"><i class="fa fa-undo"></i>Delete</a>
                                    </li>
                                </ul>
                             </div>';
                }
            })
            ->addColumn('month_range', function ($list) {
                if ($list->mydata_from == $list->mydata_to) {
                    if ($list->gst_from == $list->gst_to) {
                        if ($list->mydata_from == $list->gst_from) {
                            return $list->mydata_from;
                        } else {
                            return $list->mydata_from . '-' . $list->mydata_from;
                        }
                    } else {
                        return $list->mydata_from . '- (' . $list->gst_from . ' - ' . $list->gst_to . ')';
                    }
                } else {
                    if ($list->gst_from == $list->gst_to) {
                        return  ' (' . $list->mydata_from . ' - ' . $list->mydata_to . ') - ' . $list->gst_from;
                    } else {
                        return ' (' . $list->mydata_from . ' - ' . $list->mydata_to . ') - (' . $list->gst_from . ' - ' . $list->gst_to . ')';
                    }
                }
            })
            ->editColumn('gstin', function ($list) {
                if ($list->status == 'processed') {
                    return  '<a href="/merchant/gst/reconciliation/summary/' . $list->encrypted_job_id . '" target="_blank">' . $list->gstin . ' - ' . $list->company_name . '</a>';
                } else {
                    return  $list->gstin . ' - ' . $list->company_name;
                }
            })
            ->editColumn('status', function ($list) {
                if ($list->status == 'processing') {
                    return   '<span class="label label-sm label-warning" id="changeText">' . $list->statusp . '</span>';
                } else if ($list->status == 'error') {
                    return   '<span class="label label-sm label-danger" id="changeText">' . $list->statusp . '</span>';
                } else {
                    return   '<span class="label label-sm label-success" id="changeText">' . $list->statusp . '</span>';
                }
            })
            ->rawColumns(['action', 'month_range', 'gstin', 'status'])
            ->make(true);
    }

    public function gstr2aSummary(Request $request)
    {

        $gstModel = new Gst();
        $method = $request->method();

        if ($method == 'POST') {
            $job_id = Encrypt::decode($_POST["job_id"]);
            $gst_in = $_POST["gstin"];
            $page = $_POST["page_limit"];
            $gstin_field = $_POST["gstin_field"];
            $gstin_condition = $_POST["gstin_condition"];
            $gstin_condition_value = $_POST["gstin_condition_value1"];
            $between_from = $_POST["gstin_condition_value2"];
            $between_to = $_POST["gstin_condition_value2"];
            $taxable_difference_sort = $_POST["taxable_difference_sort"];

            if ($gst_in == '') {
                $gst_in = 'none';
            }

            $data = Helpers::setBladeProperties('GSTR 2B Reconciliation Summary', ['invoiceformat'], []);

            $breadcrumbs['menu'] = 'GSTR 2B Reconciliation';
            $breadcrumbs['title'] = $data['title'];
            $breadcrumbs['url'] = '/merchant/gst/reconciliation/summary';

            $request->session()->put('breadcrumbs', $breadcrumbs);
            $data['gst2ra'] = 'gst2ra';
            $list = $gstModel->getSummarybyJobID($job_id, $gst_in, $gstin_field, $gstin_condition, $gstin_condition_value, $between_from, $between_to,  $taxable_difference_sort, false, $page);
            $no_of_doc_purch = 0;
            $no_of_doc_gst = 0;
            $diff_of_doc = 0;

            foreach ($list as $lists) {
                if ($lists->status == 'Missing in my data') {
                    $no_of_doc_purch++;
                } else if ($lists->status == 'Missing in vendor GST filing') {
                    $no_of_doc_gst++;
                }

                $diff_of_doc  = $no_of_doc_purch - $no_of_doc_gst;

                $lists->no_of_doc_purch = $no_of_doc_purch;
                $lists->no_of_doc_gst = $no_of_doc_gst;
                $lists->diff_of_doc = $diff_of_doc;

                if ($lists->vendor_name != '') {
                    $lists->supplier = $lists->vendor_gstin . ' - ' . $lists->vendor_name;
                } else {
                    $lists->supplier = $lists->vendor_gstin;
                }
            }

            $data['list'] = $list;
            $data['record_count']  = count($data['list']);
            $data['vendor_list'] = $gstModel->getVendorListbyJobID($job_id);
            $data['job_details'] = $gstModel->getJobDetails($job_id);
            $data['job_details'] = json_decode(json_encode($data['job_details']), true);
            $data['job_id'] = Encrypt::encode($job_id);
            $data['table_title_gst'] =  $data['job_details'][0]["gstin"];
            $data['page_limit'] =  $page;
            $data['table_title_month_year'] = date('M', mktime(0, 0, 0, $data['job_details'][0]["gst_from_month"], 10)) . "-" . $data['job_details'][0]["gst_from_year"];
        } else {
            $data = Helpers::setBladeProperties('GSTR 2B Reconciliation Summary', ['invoiceformat'], []);
            $job_id = Encrypt::decode($request->id);
            $breadcrumbs['menu'] = 'GSTR 2B Reconciliation';
            $breadcrumbs['title'] = $data['title'];
            $breadcrumbs['url'] = '/merchant/gst/reconciliation/summary';
            $request->session()->put('breadcrumbs', $breadcrumbs);

            $data['gst2ra'] = 'gst2ra';
            $list = $gstModel->getSummarybyJobID($job_id, 'none', '', '', '', '', '', '', false, '1');

            $no_of_doc_purch = 0;
            $no_of_doc_gst = 0;
            $diff_of_doc = 0;

            foreach ($list as $lists) {
                if ($lists->status == 'Missing in my data') {
                    $no_of_doc_purch++;
                } else if ($lists->status == 'Missing in vendor GST filing') {
                    $no_of_doc_gst++;
                }

                $diff_of_doc  = $no_of_doc_purch - $no_of_doc_gst;

                $lists->no_of_doc_purch = $no_of_doc_purch;
                $lists->no_of_doc_gst = $no_of_doc_gst;
                $lists->diff_of_doc = $diff_of_doc;

                if ($lists->vendor_name != '') {
                    $lists->supplier = $lists->vendor_gstin . ' - ' . $lists->vendor_name;
                } else {
                    $lists->supplier = $lists->vendor_gstin;
                }
            }

            $data['list'] = $list;
            $data['record_count']  = count($data['list']);
            $data['vendor_list'] = $gstModel->getVendorListbyJobID($job_id);
            $data['job_details'] = $gstModel->getJobDetails($job_id);
            $data['job_details'] = json_decode(json_encode($data['job_details']), true);
            $data['job_id'] =  Encrypt::encode($job_id);
            $data['page_limit'] =  1;
            $data['table_title_gst'] =  $data['job_details'][0]["gstin"];
            $data['table_title_month_year'] = date('M', mktime(0, 0, 0, $data['job_details'][0]["gst_from_month"], 10)) . "-" . $data['job_details'][0]["gst_from_year"];
        }
        return view('/gst/summary', $data);
    }

    public function gstr2aDetail(Request $request)
    {
        $gstModel = new Gst();

        $data = Helpers::setBladeProperties('GSTR 2B Reconciliation Details', [], []);

        $breadcrumbs['menu'] = 'GSTR 2B Reconciliation';
        $breadcrumbs['title'] = $data['title'];
        $breadcrumbs['url'] = '/merchant/gst/reconciliation/Detail';
        $request->session()->put('breadcrumbs', $breadcrumbs);

        $data['datatablejs'] = 'table-no-export';
        $data['gst2ra'] = '';
        $data['job_id'] =  $request->id;
        $data['supplier'] =  $request->supplier;
        $data['status'] =  $request->status;
        $data['summary_data'] =  $gstModel->getDetailSummaryData(Encrypt::decode($data['job_id']), $data['supplier'], $data['status']);

        return view('/gst/detail', $data);
    }

    public function getIrisToken()
    {

        $header =  array(
            "Content-Type: application/json",
            "product:SAPPHIRE",
            "tenant: " . env('IRIS_TENANT')
        );

        $post_string = '{"email":"' .  env('IRIS_EMAIL') . '", "password":"' . env('IRIS_PASSWORD') . '"}';
        $response = Helpers::APIrequest('https://api.irisgst.com/irisgst/mgmt/login', $post_string, "POST", true, $header);
        $response = json_decode($response, 1);
        if ($response['status'] == 'SUCCESS') {
            return  $response['response'];
        } else {
            log::error('IRIS Token Error');
        }
    }

    public function validateConnection(Request $request)
    {
        $response = $this->getIrisToken();

        $header2 =  array(
            "Content-Type: application/json",
            "product:SAPPHIRE",
            "X-Auth-Token: " . $response['token'],
            "companyId:" . $response['companyid']
        );
        $response = Helpers::APIrequest('https://api.irisgst.com/irisgst/sapphire/company/validateGstnSession?gstin=' .  $request->gstin, [], "GET", true, $header2);
        $response = json_decode($response, 1);
        return $response;
        if ($response['status'] == 'SUCCESS') {
            return  $response;
        } else {
            $this->updateJobStatus('error');
            $this->fail($response['message']);
        }
        return $response;
    }

    public function numberOfMonths($start, $end)
    {
        $timeStart = strtotime($start);
        $timeEnd = strtotime($end);
        $numMonths = 1 + (date("Y", $timeEnd) - date("Y", $timeStart)) * 12;
        $numMonths += date("m", $timeEnd) - date("m", $timeStart);

        return $numMonths;
    }

    public function gstr2aCreateJob(Request $request)
    {
        $ex_numMonths = $this->numberOfMonths($request->expense_from_month_year, $request->expense_to_month_year);
        $gst_numMonths = $this->numberOfMonths($request->gst_from_month_year, $request->gst_to_month_year);

        if ($ex_numMonths > 12 || $gst_numMonths > 12) {
            return 4;
        }

        $request->expense_from_month_year =  date('m-Y', strtotime($request->expense_from_month_year));
        $request->expense_to_month_year =  date('m-Y', strtotime($request->expense_to_month_year));
        $request->gst_from_month_year =  date('m-Y', strtotime($request->gst_from_month_year));
        $request->gst_to_month_year =  date('m-Y', strtotime($request->gst_to_month_year));

        $expense_json = $this->gstModel->getExpenseData($this->merchant_id, $request->expense_from_month_year, $request->expense_to_month_year);
        if (count($expense_json) == 0) {
            return 5;
        }
        
        $job_id = $this->gstModel->setJobID($this->merchant_id, $request->gstin, $request->expense_from_month_year, $request->expense_to_month_year, $request->gst_from_month_year, $request->gst_to_month_year, $this->user_id);

        Gstr2AReconJob::dispatch($this->merchant_id, $this->user_id, $job_id, $request->gstin, $request->expense_from_month_year, $request->expense_to_month_year, $request->gst_from_month_year, $request->gst_to_month_year)->onQueue(env('MERCHANT_GSTR2A_QUEUE'));

        return true;
    }

    public function deleteJob(Request $request)
    {
        $this->gstModel->deleteJobbyID($request->id);

        return true;
    }

    public function gstr2aDetailData(Request $request)
    {
        $gstModel = new Gst();
        $data = $gstModel->getDetailData(Encrypt::decode($request->id), $request->supplier, $request->status, false);

        return Datatables::of($data)
            ->addColumn('action', function ($data) {
                return '<input onclick="showHideActionBar()" name="checkbox[]" value="' . $data->id . '"  type="checkbox">';
            })
            ->editColumn('supplier', function ($data) {
                return '<a onclick="actionBarClicked(' . " 'view_invoice' " . ', ' . $data->id . ', ' . $data->job_id . ')"> ' . $data->supplier . '</a>';
            })
            ->editColumn('status', function ($data) {
                if ($data->status == 'Matched') {
                    return '<div class="btn-group text-center" style="width: 100% !important;">
                    <button title="' . strtoupper($data->status) . '" class="btn-status btn-match" type="button"> ' . strtoupper($data->status) . '
                    </button>
                    </div>';
                } else if ($data->status == 'Reconciled') {
                    return '<div class="btn-group text-center" style="width: 100% !important;">
                    <button  title="' . strtoupper($data->status) . '" class="btn-status btn-recon" type="button"> ' . strtoupper($data->status) . '
                    </button>
                    </div>';
                } else if ($data->status == 'Pending') {
                    return '<div class="btn-group text-center" style="width: 100% !important;">
                    <button  title="' . strtoupper($data->status) . '" class="btn-status btn-pending" type="button"> ' . strtoupper($data->status) . '
                    </button>
                    </div>';
                } else if ($data->status == 'Missing in my data') {
                    return '<div class="btn-group text-center" style="width: 100% !important;">
                    <button  title="' . strtoupper($data->status) . '" class="btn-status btn-missing-my-data" type="button"> ' . strtoupper($data->status) . '
                    </button>
                    </div>';
                } else if ($data->status == 'Missing in vendor GST filing') {
                    return '<div class="btn-group text-center" style="width: 100% !important;">
                    <button  title="' . strtoupper($data->status) . '" class="btn-status btn-missing-vendor" type="button"> ' . strtoupper('Missing in GSTIN') . '
                    </button>
                    </div>';
                } else if ($data->status == 'Mismatch in values') {
                    return '<div class="btn-group text-center" style="width: 100% !important;">
                    <button  title="' . strtoupper($data->status) . '" class="btn-status btn-mismatch" type="button"> ' . strtoupper($data->status) . '
                    </button>
                    </div>';
                }
            })
            ->rawColumns(['action', 'supplier', 'status'])
            ->make(true);
    }

    public function gstr2aActionBar(Request $request)
    {
        $gstModel = new Gst();
        $list = $_POST["list"];
        $type = $_POST["type"];

        if ($type == 'recon_status') {

            $value = $_POST["value"];
            $data = $gstModel->updateReconStatus($list, $value);

            return $data;
        } else if ($type == 'vendor') {
            $file_name = 'gst_recon_report.xlsx';
            $gstin = $gstModel->getColumnValue('invoice_comparision', 'id', $list, 'vendor_gstin');
            $job_id = $gstModel->getColumnValue('invoice_comparision', 'id', $list, 'job_id');
            $month = $gstModel->getColumnValue('gstr2b_recon_jobs', 'id', $job_id, 'gst_from_month');
            $year = $gstModel->getColumnValue('gstr2b_recon_jobs', 'id', $job_id, 'gst_from_year');
            $email = $gstModel->getColumnValue('vendor', 'gst_number', $gstin, 'email_id');
            if ($email != '') {
                $data['list'] = $gstModel->getSummarybyJobID($job_id, $gstin, '', '', '', '', '', '', true, '');
                $company_name = $gstModel->getColumnValue('merchant', 'merchant_id',  $this->merchant_id, 'company_name');
                $excel_data = $gstModel->getDetailData($job_id, $gstin, '', true);
                $export = new GstExport($excel_data);
                Excel::store($export, $file_name);
                $path = Storage::path($file_name);
                $data['attachment'] = $path;
                $data['attachment_name'] = $file_name;
                $data['attachment_type'] = 'application/vnd.ms-excel';
                $data['click_here_text'] = '';
                $data['merchant_gst_number'] = $gstModel->getColumnValue('merchant_billing_profile', 'merchant_id',  $this->merchant_id, 'gst_number');
                $data['merchant_company_name'] = $company_name;
                $data['is_vendor_mail'] = 'false';
                $data['gst_text'] = '';
                $data['gstin_number'] = $gstin;
                $data['month_year'] = $month . '/' . $year;
                MerchantSendMail::dispatch($email,  $company_name . ' GST filing mismatch', $data, 'MAIL_VENDOR')->onQueue(env('SQS_MERCHANT_REGISTRATION_UPDATE_CRM_QUEUE'));
            } else {
                log::error('no email in DB');
            }

            return 1;
        } else if ($type == 'update_data') {
            $gstin = $gstModel->getColumnValue('invoice_comparision', 'id', $list, 'vendor_gstin');
            $vendor_id = $gstModel->getColumnValue('vendor', 'gst_number', $gstin, 'vendor_id');
            $data = $gstModel->getMissingData($list);
            $data = json_decode(json_encode($data), true);
            foreach ($data as $row) {
                $my_data_update_array = [
                    'purch_request_id' =>  $row["gst_request_id"],
                    'purch_request_number' => $row["gst_request_number"],
                    'purch_request_date' =>   $row["gst_request_date"],
                    'purch_request_total_amount' =>  $row["gst_request_total_amount"],
                    'purch_request_taxable_amount' => $row["gst_request_taxable_amount"],
                    'purch_request_type' =>    $row["gst_request_state"],
                    'purch_request_state' =>   $row["gst_request_state"],
                    'purch_request_cgst' =>  $row["gst_request_cgst"],
                    'purch_request_sgst' => $row["gst_request_sgst"],
                    'purch_request_igst' =>  $row["gst_request_igst"],
                    'status' =>  'Matched',
                ];

                $affected_rows = $gstModel->updateInvoiceComparison($row["id"], $my_data_update_array);
                $expense_id = $gstModel->save_expense($row, $this->merchant_id, $vendor_id, $gstin, $this->user_id);
            }
            return 1;
        } else if ($type == 'view_invoice') {
            $data = Helpers::setBladeProperties('GSTR 2B Reconciliation Details', [], []);

            $breadcrumbs['menu'] = 'GSTR 2B Reconciliation';
            $breadcrumbs['title'] = $data['title'];
            $breadcrumbs['url'] = '/merchant/gst/reconciliation/Detail';
            $request->session()->put('breadcrumbs', $breadcrumbs);

            $data['datatablejs'] = 'table-no-export';
            $data['gst2ra'] = '';
            $data["id"] = $_POST["row_id"];
            if ($data["id"] == '' || $data["id"] == null) {
                $data["id"] = $list;
            }
            $data["list"] = $gstModel->getParticularData($data["id"]);
            $data["row_count"] = count($data["list"]);

            return view('/gst/particular-detail', $data);
        }
    }

    public function gstr2aParticularDetailData(Request $request)
    {
        $gstModel = new Gst();
        $data = $gstModel->getParticularData($request->id);

        return Datatables::of($data)
            ->make(true);
    }

    public function gstr2aExportDetailData(Request $request)
    {
        $gstModel = new Gst();
        $data = $gstModel->getDetailData(Encrypt::decode($request->id), $request->supplier, $request->status, true);
        $export = new GstExport($data);

        return Excel::download($export, 'gst_recon_report.xlsx');
    }
}
