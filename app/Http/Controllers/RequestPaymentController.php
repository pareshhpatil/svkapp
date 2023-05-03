<?php

namespace App\Http\Controllers;

use App\Constants\Models\IColumn;
use App\Constants\Models\ITable;
use App\Model\Notification;
use Aws\S3\S3Client;
use Illuminate\Http\Request;
use App\Libraries\Encrypt;
use App\Model\InvoiceFormat;
use App\Model\SubcontractRequest;
use App\Model\ParentModel;
use DOMPDF;
use App\Model\Master;
use App\Libraries\Helpers;
use App\Http\Controllers\AppController;
use App\Http\Traits\InvoiceFormatTrait;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Validator;
use Exception;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Filesystem;
use League\Flysystem\ZipArchive\ZipArchiveAdapter;
use File;
use App\Model\CostType;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\API\APIController;

use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;

class RequestPaymentController extends AppController
{
    private $formatModel;
    use InvoiceFormatTrait;

    private $invoiceModel;
    private $parentModel;
    private $apiController = null;

    public function __construct()
    {
        parent::__construct();
        $this->invoiceModel = new SubcontractRequest();
        $this->parentModel = new ParentModel();
        $this->formatModel = new InvoiceFormat();
        $this->apiController = new APIController();
    }


    public function create(Request $request, $link = null, $update = null)
    {
        $narrative = '';
        $plugin = [];

        $type = 'request for payment';
        $title = ($update == null) ? 'Create ' . $type : 'Update ' . $type;
        $data = $this->setBladeProperties($title, ['invoiceformat', 'template'], [3, 19]);
        $data['currency'] = '';
        $data['currency_list'] = Session::get('currency');
        $data['richtext'] = true;

        $data['sub_contract_id'] = '';
        $breadcrumbs['menu'] = 'collect_payments';
        $data['link'] = '';
        $breadcrumbs['title'] = $data['title'];
        $breadcrumbs['url'] = '/merchant/subcontract/requestpayment/create';
        if ($link != null) {
            $request_id = Encrypt::decode($request->link);
            $data['link'] = $link;
            $data['detail'] =  $this->invoiceModel->getTableRow('subcontract_payment_request', 'request_id', $request_id);
            $contract_detail = $this->invoiceModel->getSubContractDetail($data['detail']->sub_contract_id);
            $data['contract_detail'] = $contract_detail;
            $data['sub_contract_id'] = $data['detail']->sub_contract_id;
            $data['request_number'] = $data['detail']->request_number;
            $data['bill_date'] = Helpers::htmlDate($data['detail']->bill_date);
            $data['due_date'] = Helpers::htmlDate($data['detail']->due_date);
        }


        Session::put('breadcrumbs', $breadcrumbs);
        if (isset($request->sub_contract_id)) {
            $contract_detail = $this->invoiceModel->getSubContractDetail($request->sub_contract_id);
            $data['contract_detail'] = $contract_detail;
            $data['mode'] = 'create';
            $data['sub_contract_id'] = $request->sub_contract_id;
            $data['currency'] = $request->currency;
            $data['request_number'] = '';
            $data['bill_date'] = date('M d Y');
            $data['due_date'] = date('M d Y');
        }
        $data['contract'] = $this->invoiceModel->getSubContract($this->merchant_id);

        $data['plugin'] = $plugin;
        $data['narrative'] = $narrative;
        return view('app/merchant/subcontractrequest/constructionv2', $data);
    }



    public function save(Request $request)
    {
        if ($request->link != '') {
            $request_id = Encrypt::decode($request->link);
            $this->invoiceModel->updateSubcontractRequest($request_id,  $request->all(), $this->user_id);
        } else {
            $request_id = $this->invoiceModel->saveSubcontractRequest($this->merchant_id,  $request->all(), $this->user_id);
        }
        return redirect('/merchant/subcontract/requestpayment/particular/' . Encrypt::encode($request_id));
    }

    public function saveInvoice(Request $request)
    {
        $this->invoiceModel->updateTable('subcontract_payment_request', 'request_id', $request->payment_request_id, 'payment_request_status', 14);
        $link = Encrypt::encode($request->payment_request_id);
        return redirect('/merchant/subcontract/requestpayment/view/702/' . $link);
    }


    public function list(Request $request)
    {
        $dates = Helpers::setListDates();
        $title = 'Request payment list';
        $data = Helpers::setBladeProperties($title,  [],  [5, 181]);
        $data['cancel_status'] = isset($request->cancel_status) ? $request->cancel_status : 0;
        $data['project_id'] = isset($request->project_id) ? $request->project_id : '';
        $userRole = Session::get('user_role');

        $masterModel = new Master();

        //store last search criteria into Redis
        $redis_items = $this->getSearchParamRedis('contract_list', $this->merchant_id);

        //find last search criteria into Redis 
        if (isset($redis_items['contract_list']['search_param']) && $redis_items['contract_list']['search_param'] != null) {
            $data['from_date'] = $dates['from_date'] = Helpers::sqlDate($redis_items['contract_list']['search_param']['from_date']);
            $data['to_date'] = $dates['to_date'] = Helpers::sqlDate($redis_items['contract_list']['search_param']['to_date']);
            $data['project_id'] = $redis_items['contract_list']['search_param']['project_id'];
        }
        //$data['showLastRememberSearchCriteria'] = true;
        //get contract privileges from redis
        if ($userRole == 'Admin') {
            $privilegesIDs = ['all' => 'full'];
        } else {
            $privilegesIDs = json_decode(Redis::get('contract_privileges_' . $this->user_id), true);
        }

        $list = $this->invoiceModel->getRequestPaymentList($this->merchant_id, $dates['from_date'],  $dates['to_date'], $data['project_id']);
        foreach ($list as $ck => $row) {
            $list[$ck]->encrypted_id = Encrypt::encode($row->request_id);
        }
        $data['list'] = $list;
        $userRole = Session::get('user_role');

        if ($userRole == 'Admin') {
            $projectPrivilegesIDs = ['all' => 'full'];
        } else {
            $projectPrivilegesIDs = json_decode(Redis::get('project_privileges_' . $this->user_id), true);
        }

        $whereProjectIDs = [];
        foreach ($projectPrivilegesIDs as $key => $privilegesID) {
            if ($privilegesID == 'full' || $privilegesID == 'edit' || $privilegesID == 'approve' || $privilegesID == 'view-only') {
                $whereProjectIDs[] = $key;
            }
        }

        $data["project_list"] = $masterModel->getProjectList($this->merchant_id, $whereProjectIDs, $userRole);
        $data['datatablejs'] = 'table-no-export-tablestatesave';  //table-no-export old value
        $data['hide_first_col'] = 1;
        $data['list_name'] = 'contract_list';
        $data['customer_name'] = 'Contact person name';
        $data['customer_code'] = 'Customer code';
        $data['privileges'] = $privilegesIDs;


        return view('app/merchant/subcontractrequest/list', $data);
    }



    public function get702Contents($payment_request_id, $data, $user_type =  'merchant')
    {
        $data['gtype'] = '702';

        $offlineResponse = $this->invoiceModel->getPaymentRequestOfflineResponse($payment_request_id, $this->merchant_id);

        if (!empty($offlineResponse)) {
            $data['offline_response_id'] = Encrypt::encode($offlineResponse->offline_response_id) ?? '';
        }

        if ($data['payment_request_status'] == '2') {
            $data['offline_success_transaction'] = $offlineResponse;
        }

        //merchant data 
        //$merchant_data =  (array)$this->invoiceModel->getMerchantDataByID($this->merchant_id);
        //$data["is_online_payment"] = ($merchant_data['merchant_type'] == 2 && $merchant_data['is_legal_complete'] == 1) ? 1 : 0;

        //get merchsnt company name from billing profile id 
        $data['company_name']  = $this->invoiceModel->getCompanyNameFromBillingID($this->merchant_id);

        $data['user_type'] = $user_type;

        $construction_details = $this->invoiceModel->getInvoiceConstructionParticularsSum($payment_request_id);
        $construction_details = json_decode($construction_details, 1)[0];

        $project_details =  $this->invoiceModel->getProjectDeatils($payment_request_id);
        $project_details->owner_address = '';
        $project_details->project_address = '';
        $project_details->contractor_address  = '';
        $project_details->architect_address   = '';
        $project_details->contract_date = '';
        $data['project_details'] =  $project_details;
        //set CO Data
        $changOrderData = $this->setChangeOrderValues($data['change_order_id'], $data['created_date'], $project_details->sub_contract_id, $data['bill_date']);
        $data['last_month_co_amount_positive'] =   $this->formatInvoiceValues($changOrderData['last_month_co_amount_positive'], $data['currency_icon']);
        $data['last_month_co_amount_negative'] =  $this->formatInvoiceValues($changOrderData['last_month_co_amount_negative'], $data['currency_icon']);
        $data['this_month_co_amount_positive'] =   $this->formatInvoiceValues($changOrderData['this_month_co_amount_positive'], $data['currency_icon']);
        $data['this_month_co_amount_negative'] =  $this->formatInvoiceValues($changOrderData['this_month_co_amount_negative'], $data['currency_icon']);
        $data['total_co'] = $this->formatInvoiceValues($changOrderData['last_month_co_amount'] + $changOrderData['this_month_co_amount'], $data['currency_icon']);
        $data['total_co_amount_positive'] = $this->formatInvoiceValues($changOrderData['total_co_amount_positive'], $data['currency_icon']);
        $data['total_co_amount_negative'] = $this->formatInvoiceValues($changOrderData['total_co_amount_negative'], $data['currency_icon']);

        $data["total_complete_stored"] =   $this->formatInvoiceValues($construction_details['previously_billed_amount'] + $construction_details['current_billed_amount'] + $construction_details['stored_materials'], $data['currency_icon']);
        $data["original_contract_amount"] =  $this->formatInvoiceValues($construction_details['original_contract_amount'], $data['currency_icon']);
        $data["contract_sum_to_date"] =  $this->formatInvoiceValues($construction_details['original_contract_amount'] + $changOrderData['last_month_co_amount'] + $changOrderData['this_month_co_amount'], $data['currency_icon']);


        if (!empty($construction_details['total_outstanding_retainage'])) {
            $sumOfi = $construction_details['total_outstanding_retainage'];
        } else {
            $sumOfi = $construction_details['retainage_amount_previously_withheld'];
        }

        $totalBilledAmount = $this->getTotalBilledAmount($construction_details);
        $sum_stored_materials = $this->getStoredMaterialsSum($construction_details);
        $total_retainage_amount = $this->getTotalRetinageAmount($construction_details);
        $data['work_complete_perc'] = $this->getWorkCompletePerc($totalBilledAmount, $total_retainage_amount, $data['currency_icon']);
        $data['stored_material_perc'] = $this->getStoredMaterialPerc($construction_details['stored_materials'], $sum_stored_materials,  $data['currency_icon']);

        $data['total_retainage'] = $total_retainage_amount + $sum_stored_materials;
        $data['total_retainage_amount']  = $this->formatInvoiceValues($total_retainage_amount, $data['currency_icon']);
        $data['total_stored_materials'] =  $this->formatInvoiceValues($sum_stored_materials, $data['currency_icon']);

        if ($data['total_retainage'] == 0) {
            $data['total_retainage'] = $sumOfi;
        }

        $sumOfg = $construction_details['previously_billed_amount'] + $construction_details['current_billed_amount'] + $construction_details['stored_materials'];
        $data['total_earned_less_retain'] = $this->formatInvoiceValues($sumOfg - ($data['total_retainage']), $data['currency_icon']);
        $data["total_previously_billed_amount"] = 0;
        if (isset($project_details)) {
            $data["total_previously_billed_amount"] = $this->formatInvoiceValues($this->getLessPreviousCertificatesForPayment($project_details->sub_contract_id, $payment_request_id), $data['currency_icon']);
        }

        $data['balance_to_finish'] = $this->formatInvoiceValues(($construction_details['original_contract_amount'] + $changOrderData['last_month_co_amount'] + $changOrderData['this_month_co_amount']) - ($sumOfg - $data['total_retainage']), $data['currency_icon']);
        $data['total_retainage'] = $this->formatInvoiceValues($data['total_retainage'], $data['currency_icon']);
        $data['owner_address '] = '';

        return $data;
    }


    public function getTotalBilledAmount($construction_details)
    {
        $totalBilledAmount = $construction_details['previously_billed_amount'] +  $construction_details['current_billed_amount'];

        return $totalBilledAmount;
    }

    public function getStoredMaterialsSum($construction_details)
    {
        $sum_stored_materials =  $construction_details['retainage_amount_stored_materials'] +
            $construction_details['retainage_amount_previously_stored_materials'] - $construction_details['retainage_stored_materials_release_amount'];

        return $sum_stored_materials;
    }

    public function getTotalRetinageAmount($construction_details)
    {
        $total_retainage_amount = $construction_details['retainage_amount_for_this_draw'] +
            $construction_details['retainage_amount_previously_withheld'] - $construction_details['retainage_release_amount'];

        return $total_retainage_amount;
    }

    public function getBalanceToFinish($construction_details, $changOrderData, $total_retainage, $currency_icon)
    {
        $balance_to_finish = $construction_details['original_contract_amount'] + $changOrderData['last_month_co_amount']
            + $changOrderData['this_month_co_amount'] - (($construction_details['previously_billed_amount'] + $construction_details['current_billed_amount'] +
                $construction_details['stored_materials']) - (($construction_details['retainage_amount_stored_materials'] +
                $construction_details['retainage_amount_previously_stored_materials'] - $construction_details['retainage_stored_materials_release_amount'])
                + $total_retainage));

        $balance_to_finish = $this->formatInvoiceValues($balance_to_finish, $currency_icon);
        return $balance_to_finish;
    }

    public function getWorkCompletePerc($totalBilledAmount, $total_retainage_amount, $currency_icon)
    {
        $work_complete_perc = 0;
        if ($total_retainage_amount > 0 && $totalBilledAmount > 0) {
            $work_complete_perc = $this->formatInvoiceValues($total_retainage_amount * 100 / $totalBilledAmount, '');
        }

        return $work_complete_perc;
    }

    public function getStoredMaterialPerc($stored_materials_sum, $sumOfrasm, $currency_icon)
    {
        $stored_material_perc = 0;
        if ($stored_materials_sum > 0 && $sumOfrasm > 0) {
            $stored_material_perc = $this->formatInvoiceValues((($sumOfrasm * 100) / $stored_materials_sum), '');
        }

        return $stored_material_perc;
    }

    public function getGrandTotal($info, $currency_icon)
    {
        $info['grand_total'] = $info['grand_total'] - $info['paid_amount'];
        $grand_total = $info['grand_total'];
        $date = date("m/d/Y");
        $refDate = date("m/d/Y", strtotime($info['due_date']));
        if ($date > $refDate) {
            $info["invoice_total"] = $info['grand_total'];
            if ($info['grand_total'] > 0) {
                $grand_total = $info['grand_total'];
            }
        }
        $grand_total = $this->formatInvoiceValues($grand_total, $currency_icon);
        return $grand_total;
    }

    public function setChangeOrderValues($change_order_id, $created_date, $contract_id, $bill_date)
    {

        $change_order_ids = json_decode($change_order_id, 1);
        if (!empty($change_order_ids)) {
            $co_data['last_month_co_amount_positive'] = 0;
            $co_data['last_month_co_amount_negative'] = 0;
            $co_data['this_month_co_amount_positive'] = 0;
            $co_data['this_month_co_amount_negative'] = 0;

            $start_date = '1990-01-01';
            $end_date = date("Y-m-01", strtotime($bill_date));
            $co_data['last_month_co_amount_positive'] = $this->invoiceModel->getChangeOrderAmount($change_order_ids, $start_date, $end_date, '>');
            $co_data['last_month_co_amount_negative'] = $this->invoiceModel->getChangeOrderAmount($change_order_ids, $start_date, $end_date, '<');

            $start_date = date("Y-m-01", strtotime($bill_date));
            $end_date = date("Y-m-d", strtotime("first day of next month"));
            $co_data['this_month_co_amount_positive'] = $this->invoiceModel->getChangeOrderAmount($change_order_ids, $start_date, $end_date,  '>');
            $co_data['this_month_co_amount_negative'] = $this->invoiceModel->getChangeOrderAmount($change_order_ids, $start_date, $end_date, '<');

            $co_data['last_month_co_amount'] = $co_data['last_month_co_amount_positive'] +  $co_data['last_month_co_amount_negative'];
            $co_data['this_month_co_amount'] = $co_data['this_month_co_amount_positive'] +  $co_data['this_month_co_amount_negative'];

            $co_data['total_co_amount_positive'] = $co_data['last_month_co_amount_positive'] +  $co_data['this_month_co_amount_positive'];
            $co_data['total_co_amount_negative'] = $co_data['last_month_co_amount_negative'] +  $co_data['this_month_co_amount_negative'];
        } else {
            $co_data['last_month_co_amount_positive'] = 0;
            $co_data['last_month_co_amount_negative'] = 0;
            $co_data['this_month_co_amount_positive'] = 0;
            $co_data['this_month_co_amount_negative'] = 0;

            $co_data['total_co_amount_positive'] = 0;
            $co_data['total_co_amount_negative'] = 0;

            $pre_month_change_order_amount =  $this->invoiceModel->querylist("select sum(`total_change_order_amount`) as change_order_amount from `order`
            where EXTRACT(YEAR_MONTH FROM approved_date)= EXTRACT(YEAR_MONTH FROM '" . $created_date . "'-INTERVAL 1 MONTH) AND last_update_date<'" . $created_date  . "' AND `status`=1 AND `is_active`=1 AND `contract_id`='" . $contract_id . "'");
            if ($pre_month_change_order_amount[0]->change_order_amount != null) {
                $co_data['last_month_co_amount'] = $pre_month_change_order_amount[0]->change_order_amount;
            } else {
                $co_data['last_month_co_amount'] = 0;
            }
            $current_month_change_order_amount =  $this->invoiceModel->querylist("select sum(`total_change_order_amount`) as change_order_amount from `order`
          where EXTRACT(YEAR_MONTH FROM approved_date)=EXTRACT(YEAR_MONTH FROM '" . $created_date  . "') AND last_update_date<'" . $created_date  . "' AND `status`=1 AND `is_active`=1 AND `contract_id`='" . $contract_id . "'");
            if ($current_month_change_order_amount[0]->change_order_amount != null) {
                $co_data['this_month_co_amount'] = $current_month_change_order_amount[0]->change_order_amount;
            } else {
                $co_data['this_month_co_amount'] = 0;
            }
        }

        return $co_data;
    }

    public function formatInvoiceValues($value, $currency_icon)
    {
        if ($value < 0) {
            $value = '(' . str_replace('-', '', number_format($value, 2)) . ')';
        } else {
            $value = number_format($value, 2);
        }

        return $currency_icon . $value;
    }

    public function documents($user_type = "merchant", $link, $parentnm = '', $sub = '', $docpath = '')
    {
        $payment_request_id = Encrypt::decode($link);

        if (strlen($payment_request_id) == 10) {
            $data = Helpers::setBladeProperties('Invoice', [], [5, 181]);
            #get default billing profile
            //$info =  $this->invoiceModel->getInvoiceInfo($payment_request_id, 'customer');
            $userRole = Session::get('user_role');
            $data['user_type'] = $user_type;
            $invoice_Data = $this->getInvoiceDetailsForViews($payment_request_id, $userRole, $user_type);
            $data = array_merge($data, $invoice_Data);

            $plugin_value =  $this->invoiceModel->getColumnValue('payment_request', 'payment_request_id', $payment_request_id, 'plugin_value');

            // $banklist = $this->parentModel->getConfigList('Bank_name');
            // $banklist = json_decode($banklist, 1);


            $data['its_from'] = 'real';
            $data['gtype'] = 'attachment';

            $offlineResponse = $this->invoiceModel->getPaymentRequestOfflineResponse($payment_request_id, $this->merchant_id);

            if (!empty($offlineResponse)) {
                $data['offline_response_id'] = Encrypt::encode($offlineResponse->offline_response_id) ?? '';
            }

            if ($data['payment_request_status'] == '2') {
                $data['offline_success_transaction'] = $offlineResponse;
            }


            $plugin_array = json_decode($plugin_value, 1);
            if (!empty($plugin_array['files'])) {
                $data['files'] = $plugin_array['files'];
            }
            if (isset($data['files'])) {
                $data['files'] = array_filter($data['files'], 'strlen');
            }
            $menus = array();
            $doclist = array();
            if (!empty($data['files'][0])) {
                $menus['title'] = "Invoice";
                $menus['id'] = 'Invoice';
                $menus['full'] = 'Invoice';
                $menus['link'] = "";

                $menus1 = array();
                $menus2 = array();
                $pos = 1;

                foreach ($data['files'] as $files) {
                    $nm = '';
                    if (!empty($files)) {
                        $nm = substr(substr(substr(basename($files), 0, strrpos(basename($files), '.')), 0, -4), 0, 10);
                    }

                    $menus1['id'] = str_replace(' ', '_', substr(substr(basename($files), 0, strrpos(basename($files), '.')), -10));
                    $menus1['full'] = basename($files);

                    $menus1['title'] = strlen(substr(substr(basename($files), 0, strrpos(basename($files), '.')), 0, -4)) < 10 ? substr(substr(basename($files), 0, strrpos(basename($files), '.')), 0, -4) : $nm . '...';


                    $menus1['link'] = substr(substr(substr(basename($files), 0, strrpos(basename($files), '.')), 0, -4), 0, 7);
                    $menus1['menu'] = "";
                    $menus1['type'] = "invoice";
                    $menus2[$pos] = $menus1;
                    if ($pos == 1) {
                        if (empty($docpath)) {
                            $docpath  = str_replace(' ', '_', substr(substr(basename($files), 0, strrpos(basename($files), '.')), -10));
                        }
                    }
                    $pos++;
                }

                $menus['menu'] = $menus2;
                $doclist[] = $menus;
            }


            if (isset($plugin_array['has_mandatory_upload'])) {
                if ($plugin_array['has_mandatory_upload'] == 1) {

                    $menus0['title'] = "Required documents";
                    $menus0['id'] = "hellow_required_document";
                    $menus0['full'] = "Required documents";
                    $menus0['link'] = "";
                    $menus0['type'] = "required";

                    foreach ($plugin_array['mandatory_data'] as $key => $mandatory_data) {
                        $mandatory_files = $this->invoiceModel->getMandatoryDocumentByPaymentRequestID($payment_request_id, $mandatory_data['name']);

                        $menus1 = array();
                        $menus2 = array();
                        $pos = 1;

                        foreach ($mandatory_files as $files) {
                            if ($files->file_url != '') {
                                $data['files'][] = $files->file_url;
                                $nm = '';
                                if (!empty($files->file_url)) {
                                    $nm = substr(substr(substr(basename($files->file_url), 0, strrpos(basename($files->file_url), '.')), 0, -4), 0, 10);
                                }

                                $menus1['id'] = str_replace(' ', '_', substr(substr(basename($files->file_url), 0, strrpos(basename($files->file_url), '.')), -10));
                                $menus1['full'] = basename($files->file_url);

                                $menus1['title'] = strlen(substr(substr(basename($files->file_url), 0, strrpos(basename($files->file_url), '.')), 0, -4)) < 10 ? substr(substr(basename($files->file_url), 0, strrpos(basename($files->file_url), '.')), 0, -4) : $nm . '...';


                                $menus1['link'] = substr(substr(substr(basename($files->file_url), 0, strrpos(basename($files->file_url), '.')), 0, -4), 0, 7);
                                $menus1['menu'] = "";
                                $menus1['type'] = "required";
                                $menus2[$pos] = $menus1;
                                if ($pos == 1) {
                                    if (empty($docpath)) {
                                        $docpath  = str_replace(' ', '_', substr(substr(basename($files->file_url), 0, strrpos(basename($files->file_url), '.')), -10));
                                    }
                                }
                                $pos++;
                            }
                        }
                        if (isset($mandatory_files[0]->file_url)) {
                            if ($mandatory_files[0]->file_url != '') {

                                $menus['title'] = strlen($mandatory_data['name']) > 10 ? substr($mandatory_data['name'], 0, 10) . "..." : $mandatory_data['name'];
                                $menus['id'] = "required_document" . $key;
                                $menus['full'] = $mandatory_data['name'];
                                $menus['link'] = "";

                                $menus['menu'] = $menus2;
                                $menus0['menu'][] = $menus;
                            }
                        }
                    }

                    $doclist[] = $menus0;
                }
            }

            $constriuction_details = $this->parentModel->getTableList('invoice_construction_particular', 'payment_request_id', $payment_request_id);
            $tt = json_decode($constriuction_details, 1);
            $data = $this->getDataBillCodeAttachment($tt, $doclist, $data);

            //dd( $data);
            //dd($data['docs'][0]['menu'][0]['title']);

            $selectedDoc = array();
            $selectnm = '';
            if (!empty($parentnm)) {
                // $docpath = '';
                $selectnm = $parentnm;
            } else if (isset($data['docs'][0]['id'])) {
                $selectnm = $data['docs'][0]['id'];
            }


            if (empty($sub)) {
                if (isset($data['docs'][0]['menu'][0]['id']))
                    $sub = $data['docs'][0]['menu'][0]['id'];
            }

            if (empty($parentnm)) {
                if (empty($docpath)) {
                    if (isset($data['docs'][0]['menu'][0]['menu'][0]['id']))
                        $docpath = $data['docs'][0]['menu'][0]['menu'][0]['id'];
                    else if (isset($data['docs'][0]['menu'][0]['id']))
                        $docpath = $data['docs'][0]['menu'][0]['id'];
                }
            }


            $selectedDoc[0] = $selectnm;
            $selectedDoc[1] = $sub;
            $selectedDoc[2] = $docpath;
            $data['selectedDoc'] = $selectedDoc;
            $data['staging'] = 0;
            return view('app/merchant/invoice/documents', $data);
        } else {
        }
    }
    public function  getDataBillCodeAttachment($tt, $datalist, $datas)
    {

        $group_names = array();
        $grouping_data = array();
        foreach ($tt as $td) {
            if (!in_array($td['group'], $group_names)) {
                $group_names[] = $td['group'];
            }
        }
        $result = array();
        foreach ($tt as $element) {
            $result[$element['group']][] = $element;
        }

        $single_data1 = array();

        foreach ($group_names as $names) {

            $pos = 0;
            $pos1 = 0;
            $chiledmenu = array();
            $parentmenu = array();
            $chiledmenu2 = array();
            $parentmenu['title'] = strlen($names) > 10 ? substr($names, 0, 10) . "..." : $names;
            $parentmenu['id'] = str_replace(' ', '_', substr($names, 0, 7));
            $parentmenu['full'] = $names;

            $parentmenu['link'] = "";
            $parentmenu['type'] = 'billcode';

            foreach ($result[$names] as $data) {

                $pos1++;
                if (!empty($data['group']) && $data['bill_code_detail'] == 'No') {


                    if (!empty($data['attachments'])) {


                        $emptyarray = array();
                        if (!empty($data['description'])) {
                            $chiledmenu2['title'] =  strlen($data['description']) > 10 ? substr($data['description'], 0, 10) . "..." : $data['description'];
                            $chiledmenu2['id'] = str_replace(' ', '_', substr($data['bill_code'], 0, 7));
                            $chiledmenu2['full'] = $data['description'];
                        } else {
                            $chiledmenu2['title'] = strlen($data['bill_code']) > 10 ? substr($data['bill_code'], 0, 10) . "..." : $data['bill_code'];
                            $chiledmenu2['id'] = str_replace(' ', '_', substr($data['bill_code'], 0, 7));
                            $chiledmenu2['full'] = $data['bill_code'];
                        }


                        $chiledmenu2['link'] = "";
                        $chiledmenu2['type'] = 'billcode';

                        foreach (json_decode($data['attachments'], 1) as $files) {
                            $datas['files'][] = $files;
                            $subchiledmenu = array();
                            $nm = substr(substr(substr(basename($files), 0, strrpos(basename($files), '.')), 0, -4), 0, 10);
                            $subchiledmenu['title'] = strlen(substr(substr(basename($files), 0, strrpos(basename($files), '.')), 0, -4)) < 10 ? substr(substr(basename($files), 0, strrpos(basename($files), '.')), 0, -4) : $nm . '...';
                            $subchiledmenu['id'] = str_replace(' ', '_', substr(substr(basename($files), 0, strrpos(basename($files), '.')), -10));
                            $subchiledmenu['full'] = basename($files);
                            $subchiledmenu['link'] = substr(substr(substr(basename($files), 0, strrpos(basename($files), '.')), 0, -4), 0, 7);
                            $subchiledmenu['type'] = 'billcode';
                            $subchiledmenu['menu'] = '';
                            $emptyarray[] = $subchiledmenu;
                        }

                        $chiledmenu2['menu'] = $emptyarray;
                        $parentmenu['menu'][] = $chiledmenu2;
                    }
                } else if (empty($names)) {

                    if (!empty($data['attachments'])) {

                        $chiledmenu1 = array();
                        if (!empty($data['description'])) {
                            $chiledmenu1['title'] =  strlen($data['description']) > 10 ? substr($data['description'], 0, 10) . "..." : $data['description'];
                            $chiledmenu1['id'] = str_replace(' ', '_', substr($data['bill_code'], 0, 7));
                            $chiledmenu1['full'] = $data['description'];
                        } else {
                            $chiledmenu1['title'] = strlen($data['bill_code']) > 10 ? substr($data['bill_code'], 0, 10) . "..." : $data['bill_code'];
                            $chiledmenu1['id'] = str_replace(' ', '_', substr($data['bill_code'], 0, 7));
                            $chiledmenu1['full'] = $data['bill_code'];
                        }

                        $chiledmenu1['link'] = "";
                        $chiledmenu1['type'] = 'billcode';
                        $emptyarray = array();
                        foreach (json_decode($data['attachments'], 1) as $files) {
                            $datas['files'][] = $files;
                            $subchiledmenu = array();
                            $nm = substr(substr(substr(basename($files), 0, strrpos(basename($files), '.')), 0, -4), 0, 10);
                            $subchiledmenu['title'] = strlen(substr(substr(basename($files), 0, strrpos(basename($files), '.')), 0, -4)) < 10 ? substr(substr(basename($files), 0, strrpos(basename($files), '.')), 0, -4) : $nm . '...';
                            $subchiledmenu['id'] = str_replace(' ', '_', substr(substr(basename($files), 0, strrpos(basename($files), '.')), -10));

                            $subchiledmenu['full'] = basename($files);
                            $subchiledmenu['link'] = substr(substr(substr(basename($files), 0, strrpos(basename($files), '.')), 0, -4), 0, 7);
                            $subchiledmenu['type'] = 'billcode';
                            $subchiledmenu['menu'] = '';
                            $emptyarray[] = $subchiledmenu;
                        }


                        $chiledmenu1['menu'] = $emptyarray;
                        $datalist[] = $chiledmenu1;
                    }
                } else {

                    if (!empty($data['attachments'])) {


                        if (!empty($data['description'])) {

                            $chiledmenu['title'] =  strlen($data['description']) > 10 ? substr($data['description'], 0, 10) . "..." : $data['description'];
                            $chiledmenu['id'] = str_replace(' ', '_', substr($data['bill_code'], 0, 7));
                            $chiledmenu['full'] = $data['description'];
                        } else {
                            $chiledmenu['title'] = strlen($data['bill_code']) > 10 ? substr($data['bill_code'], 0, 10) . "..." : $data['bill_code'];
                            $chiledmenu['id'] = str_replace(' ', '_', substr($data['bill_code'], 0, 7));
                            $chiledmenu['full'] = $data['bill_code'];
                        }


                        $chiledmenu['link'] = "";
                        $chiledmenu['type'] = 'billcode';
                        $emptyarray = array();
                        foreach (json_decode($data['attachments'], 1) as $files) {
                            $datas['files'][] = $files;
                            $subchiledmenu = array();
                            $nm = substr(substr(substr(basename($files), 0, strrpos(basename($files), '.')), 0, -4), 0, 10);
                            $subchiledmenu['title'] = strlen(substr(substr(basename($files), 0, strrpos(basename($files), '.')), 0, -4)) < 10 ? substr(substr(basename($files), 0, strrpos(basename($files), '.')), 0, -4) : $nm . '...';
                            // $subchiledmenu['id'] = str_replace(' ', '_', substr(substr(substr(basename($files), 0, strrpos(basename($files), '.')), 0, -4), 0, 7));
                            $subchiledmenu['id'] = str_replace(' ', '_', substr(substr(basename($files), 0, strrpos(basename($files), '.')), -10));

                            $subchiledmenu['full'] = basename($files);
                            $subchiledmenu['link'] = substr(substr(substr(basename($files), 0, strrpos(basename($files), '.')), 0, -4), 0, 7);
                            $subchiledmenu['type'] = 'billcode';
                            $subchiledmenu['menu'] = '';
                            $emptyarray[] = $subchiledmenu;
                        }

                        $chiledmenu['menu'] = $emptyarray;
                        $parentmenu['menu'][] = $chiledmenu;
                    }
                }
            }
            if (!empty($names)) {
                if (!empty($chiledmenu)) {
                    //$parentmenu['menu'][]=$chiledmenu;
                    $datalist[] = $parentmenu;
                }
                if (!empty($chiledmenu2)) {
                    // $parentmenu['menu'][]=$chiledmenu2;
                    $datalist[] = $parentmenu;
                }
            } else {
                if (!empty($chiledmenu))
                    $datalist[] = $chiledmenu;
            }

            // $datalist[]=$parentmenu;

        }

        $datas['docs'] = $datalist;
        return $datas;
    }

    public function downloadSingle($link)
    {
        $filePath = '';
        $data = explode("_", $link);

        $folder = $data[0];
        $link = str_replace($data[0] . '_', "", $link);
        if ($folder != 'invoices') {
            $filePath =  'invoices/' . $folder . '/' . $link;
        } else {
            $filePath = 'invoices/' . $link;
        }

        $url = Storage::disk('s3_expense')->temporaryUrl(
            $filePath,
            now()->addHour(),
            ['ResponseContentDisposition' => 'attachment']
        );
        header("Location:" . $url);
        exit();
    }
    public function downloadZip($link)
    {
        $payment_request_id = Encrypt::decode($link);

        if (strlen($payment_request_id) == 10) {
            $plugin_value =  $this->invoiceModel->getColumnValue('payment_request', 'payment_request_id', $payment_request_id, 'plugin_value');
            $attach_value =  $this->invoiceModel->getColumnValueWithAllRow('invoice_construction_particular', 'payment_request_id', $payment_request_id, 'attachments');

            $plugin_array = json_decode($plugin_value, 1);

            $source_disk = 's3_expense';
            if (File::exists(public_path('tmp/documents.zip'))) {
                unlink(public_path('tmp/documents.zip'));
            }

            $zip = new Filesystem(new ZipArchiveAdapter(public_path('tmp/documents.zip')));
            if (isset($plugin_array['files'])) {
                foreach ($plugin_array['files'] as $file_name) {
                    if ($file_name != '') {
                        $source_path = 'invoices/' . basename($file_name);
                        $file_content = Storage::disk($source_disk)->get($source_path);
                        $zip->put(basename($file_name), $file_content);
                    }
                }
            }

            //required documents attachments
            if (isset($plugin_array['has_mandatory_upload'])) {
                if ($plugin_array['has_mandatory_upload'] == 1) {
                    foreach ($plugin_array['mandatory_data'] as $key => $mandatory_data) {
                        $mandatory_files = $this->invoiceModel->getMandatoryDocumentByPaymentRequestID($payment_request_id, $mandatory_data['name']);
                        foreach ($mandatory_files as $files) {
                            if ($files->file_url != '') {
                                $file_name = basename($files->file_url);
                                $source_path = 'invoices/' . $file_name;
                                $file_content = Storage::disk($source_disk)->get($source_path);
                                $zip->put($file_name, $file_content);
                            }
                        }
                    }
                }
            }

            $billcode_docs = json_decode($attach_value, 1);

            foreach ($billcode_docs as $items) {

                $inner_data = json_decode($items['value'], 1);
                if (!empty($inner_data)) {
                    foreach ($inner_data as $values) {
                        $lastWord = explode("/", $values);
                        $folder = $lastWord[count($lastWord) - 2];
                        $source_path = 'invoices/' . $folder . '/' . basename($values);
                        $file_content = Storage::disk($source_disk)->get($source_path);
                        $zip->put(basename($values), $file_content);
                    }
                }
            }
            header('Location:/tmp/documents.zip');
            exit();
            //return redirect('tmp/documents.zip');
        }
    }

    public function sendEmail($link, $subject)
    {
        $payment_request_id = Encrypt::decode($link);

        if (strlen($payment_request_id) == 10) {
            #get default billing profile
            $info =  $this->invoiceModel->getInvoiceInfo($payment_request_id, 'customer');
            $info = (array)$info;


            $savepdfurl = env('APP_URL') . '/patron/invoice/download/' . $link;  //download link 
            $info['savepdfurl'] = $savepdfurl;
            $info['paylink'] = env('APP_URL') . '/patron/paymentrequest/pay/' . $link;
            $info['viewurl'] = env('APP_URL') . '/patron/invoice/view/' . $link . '/702'; //new url

            //attache pdf
            $project = $this->parentModel->getTableRow('project', 'id', $info['project_id']);
            $info['project_name'] = $project->project_name;
            $covernote = (array) $this->invoiceModel->getCoveringNoteDetails($payment_request_id);
            if (!empty($covernote)) {
                $subject = $this->getDynamicString($info, $covernote['subject']);
                $msg = $this->getDynamicString($info, $covernote['body']);
                $data['body'] = $msg;
                $data['notebutton'] = $covernote['invoice_label'];
                $pdf_enable = $covernote['pdf_enable'];
                $file = 'invoice.covering-note';
            } else {
                $pdf_enable = 1;
                $file = 'invoice.mail';

                $subject = $info['project_name'] . ' invoice';
                $data['project_name'] = $info['project_name'];
                $data['bill_date'] = Helpers::htmlDate($info['bill_date']);
                $data['due_date'] = Helpers::htmlDate($info['due_date']);
                $data['company_name'] = $info['company_name'];
                $data['absolute_cost'] = number_format($info['absolute_cost'], 2);
                $data['invoice_link'] = $info['viewurl'];
            }
            if ($pdf_enable == 1) {
                $nm = $this->download($link, 1, '702');
                $attached[] = array('path' => storage_path("app\\pdf\\702" . $nm . ".pdf"), 'name' => '702.pdf');
                $nm = $this->download($link, 1, '703');
                $attached[] = array('path' => storage_path("app\\pdf\\703" . $nm . ".pdf"), 'name' => '703.pdf');
                $data['multiattach'] = $attached;
            }
            $data['viewtype'] = 'mailer';
            Helpers::sendMail($info['customer_email'], $file, $data, $subject);
        }
    }
    function getDynamicString($info, $message)
    {

        $vars = $this->parentModel->getFromTableName('dynamic_variable');

        $vars = json_decode($vars, 1);
        foreach ($vars as $row) {
            if ($row['name'] == '%BILL_MONTH%') {
                $message = str_replace($row['name'], date("M-y", strtotime($info[$row['column_name']])), $message);
            } else if ($row['name'] == '%INVOICE_LINK%') {
                $message = str_replace($row['name'], '<a href="' . $info[$row['column_name']] . '">invoice link</a>', $message);
            } else if ($row['name'] == '%PAYABLE_AMOUNT%') {
                $message = str_replace($row['name'], number_format($info[$row['column_name']], 2), $message);
            } else {
                $message = str_replace($row['name'], $info[$row['column_name']], $message);
            }
        }
        return $message;
    }
    private function getWhatsapptext($info)
    {
        $link = env('APP_URL') . '/patron/paymentrequest/view/' .  Encrypt::encode($info['payment_request_id']); //to do
        if (isset($info['customer_mobile']) && strlen($info['customer_mobile']) == 10) {
            $mobile = '91' . $info['customer_mobile'];
            $mobile = '&phone=' . $mobile;
        } else {
            $mobile = '';
        }
        $sms = "Your latest invoice by " . $info['company_name'] . " for " . $info['grand_total'] . " is ready. Click here to view your invoice and make the payment online - " . $link;
        return 'https://api.whatsapp.com/send?text=' . $sms . $mobile;
    }

    public function hasInvoiceAccess($payment_request_id, $contract_id, $userType)
    {
        $invoicePrivilegesAccessIDs = json_decode(Redis::get('invoice_privileges_' . $this->user_id), true);
        $contractPrivilegesAccessIDs = json_decode(Redis::get('contract_privileges_' . $this->user_id), true);
        $invoiceAccess = '';

        if ($userType == 'patron') {
            $invoiceAccess = 'full';
        } else {
            if (in_array($payment_request_id, array_keys($invoicePrivilegesAccessIDs))) {
                $invoiceAccess = $invoicePrivilegesAccessIDs[$payment_request_id];
            } elseif (in_array($contract_id, array_keys($contractPrivilegesAccessIDs))) {
                $invoiceAccess = $contractPrivilegesAccessIDs[$contract_id];
            } elseif (in_array('all', array_keys($invoicePrivilegesAccessIDs))) {
                $invoiceAccess = $invoicePrivilegesAccessIDs['all'];
            }
        }

        return $invoiceAccess;
    }

    /**
     * @param $contract_id
     * @param $customer_id
     * @return int|string
     */
    private function getLessPreviousCertificatesForPayment($contract_id, $payment_request_id)
    {
        $less_previous_certificates_for_payment = 0;
        $pre_req_id =  $this->invoiceModel->getPreviousSubcontractRequest($this->merchant_id, $contract_id, $payment_request_id);

        if ($pre_req_id != false) {
            $less_previous_certificates_for_payment = $this->invoiceModel->getColumnValue('subcontract_payment_request', 'request_id', $pre_req_id, 'invoice_total');
        }
        return $less_previous_certificates_for_payment;
    }



    function setPlugins($plugin, $request)
    {
        if (isset($plugin['has_upload'])) {
            if ($plugin['has_upload'] == 1) {
                $plugin['files'] = explode(',', $request->file_upload);
            }
        }
        if (isset($plugin['has_covering_note'])) {
            if ($plugin['has_covering_note'] == 1) {
                $plugin['default_covering_note'] = (isset($request->covering_id)) ? $request->covering_id : 0;
            }
        }
        if (isset($plugin['has_watermark'])) {
            if ($plugin['has_watermark'] == 1) {
                $plugin['watermark_text'] = (isset($request->watermark_text)) ? $request->watermark_text : '';
            }
        }


        return $plugin;
    }

    public function particularsave(Request $request, $type = null)
    {
        ini_set('max_execution_time', 120);
        $request_id = Encrypt::decode($request->link);


        $invoice = $this->invoiceModel->getTableRow('subcontract_payment_request', 'request_id', $request_id);

        //Check Privileges

        if ($invoice == false) {
            throw new Exception('Invalid id ' . $request_id);
        }
        $revision = false;


        $this->invoiceModel->updateTable('subcontract_request_payment_particular', 'subcontract_request_id', $request_id, 'is_active', 0);
        if ($type == null) {
            $billed_transaction_ids = [];
            if (!empty($request->bill_code)) {
                foreach ($request->bill_code as $k => $bill_code) {
                    $request = Helpers::setArrayZeroValue(array(
                        'original_contract_amount', 'approved_change_order_amount', 'current_contract_amount', 'previously_billed_percent', 'previously_billed_amount', 'current_billed_percent', 'current_billed_amount', 'total_billed', 'retainage_percent', 'retainage_amount_previously_withheld', 'retainage_amount_for_this_draw', 'net_billed_amount', 'retainage_release_amount', 'total_outstanding_retainage',
                        'retainage_percent_stored_materials', 'retainage_amount_stored_materials', 'retainage_amount_previously_stored_materials', 'retainage_stored_materials_release_amount'
                    ));
                    $data['bill_code'] = $request->bill_code[$k];
                    if ($request->description[$k] == '') {
                        $request->description[$k] = $this->invoiceModel->getColumnValue('csi_code', 'id', $data['bill_code'], 'title');
                    }
                    $data['id'] = $request->id[$k];
                    $data['description'] = $request->description[$k];
                    $data['bill_type'] = $request->bill_type[$k];
                    $data['original_contract_amount'] = $request->original_contract_amount[$k];
                    $data['approved_change_order_amount'] = $request->approved_change_order_amount[$k];
                    $data['pint'] = $request->pint[$k];
                    $data['sort_order'] = $request->sort_order[$k];
                    $data['current_contract_amount'] = $request->current_contract_amount[$k];
                    $data['previously_billed_percent'] = $request->previously_billed_percent[$k];
                    $data['previously_billed_amount'] = $request->previously_billed_amount[$k];
                    $data['current_billed_percent'] = $request->current_billed_percent[$k];
                    $data['current_billed_amount'] = $request->current_billed_amount[$k];
                    $data['total_billed'] = $request->total_billed[$k];
                    $data['retainage_percent'] = $request->retainage_percent[$k];
                    $data['retainage_amount_previously_withheld'] = $request->retainage_amount_previously_withheld[$k];
                    $data['retainage_amount_previously_stored_materials'] = $request->retainage_amount_previously_stored_materials[$k];
                    $data['retainage_stored_materials_release_amount'] = $request->retainage_stored_materials_release_amount[$k];
                    $data['retainage_amount_for_this_draw'] = $request->retainage_amount_for_this_draw[$k];
                    $data['retainage_percent_stored_materials'] = $request->retainage_percent_stored_materials[$k];
                    $data['retainage_amount_stored_materials'] = $request->retainage_amount_stored_materials[$k];
                    $data['net_billed_amount'] = $request->net_billed_amount[$k];
                    $data['retainage_release_amount'] = $request->retainage_release_amount[$k];
                    $data['total_outstanding_retainage'] = $request->total_outstanding_retainage[$k];
                    $data['previously_stored_materials'] = $request->previously_stored_materials[$k];
                    $data['current_stored_materials'] = $request->current_stored_materials[$k];
                    $data['stored_materials'] = $request->stored_materials[$k];
                    $data['task_number'] = $request->task_number[$k];
                    $data['project'] = $request->project[$k];
                    $data['cost_code'] = $request->cost_code[$k];
                    $data['cost_type'] = $request->cost_type[$k];
                    $data['group'] = $request->group[$k];
                    $data['sub_group'] = $request->sub_group[$k];
                    $data['bill_code_detail'] = ($request->bill_code_detail[$k] == '') ? 'Yes' : $request->bill_code_detail[$k];
                    $data['calculated_perc'] = null;
                    $data['calculated_row'] = null;
                    $data['billed_transaction_ids'] = $request->billed_transaction_ids[$k];
                    $ids = json_decode($data['billed_transaction_ids'], 1);
                    if (!empty($ids)) {
                        if (empty($billed_transaction_ids)) {
                            $billed_transaction_ids = $ids;
                        } else {
                            $billed_transaction_ids = array_merge($billed_transaction_ids, $ids);
                        }
                    }
                    if ($request->attachments[$k] != '') {
                        $data['attachments'] = json_encode(explode(',', $request->attachments[$k]));
                        $data['attachments'] = str_replace('\\', '',  $data['attachments']);
                        $data['attachments'] = str_replace('"undefined",', '', $data['attachments']);
                        $data['attachments'] = str_replace('"undefined"', '', $data['attachments']);
                        $data['attachments'] = str_replace('[]', '', $data['attachments']);
                    } else {
                        $data['attachments'] = null;
                    }
                    $request->totalcost = str_replace(',', '', $request->totalcost ?? 0);
                    $previous_invoice_amount = $this->getLessPreviousCertificatesForPayment($invoice->sub_contract_id, $request_id);
                    $this->invoiceModel->updateSubcontractRequestDetail($request_id, $request->totalcost, $request->order_ids ?? [], $previous_invoice_amount);
                    if ($data['id'] > 0) {
                        $this->invoiceModel->updateConstructionParticular($data, $data['id'], $this->user_id, 'subcontract');
                    } else {
                        $this->invoiceModel->saveConstructionParticular($data, $request_id, $this->user_id, 'subcontract');
                    }
                }
            }
        }

        return redirect('/merchant/subcontract/requestpayment/view/703/' . Encrypt::encode($request_id));
    }

    public function saveParticularRow(Request $request)
    {

        $data = json_decode($_POST['data'], 1);
        $request_id = Encrypt::decode($data['request_id']);
        $data = Helpers::setArrayPostZeroValue(array(
            'id', 'original_contract_amount', 'approved_change_order_amount', 'current_contract_amount', 'previously_billed_percent', 'previously_billed_amount', 'current_billed_percent', 'current_billed_amount', 'total_billed', 'retainage_percent', 'retainage_amount_previously_withheld', 'retainage_amount_for_this_draw', 'net_billed_amount', 'retainage_release_amount', 'total_outstanding_retainage', 'calculated_perc',
            'retainage_percent_stored_materials', 'retainage_amount_stored_materials', 'retainage_amount_previously_stored_materials', 'retainage_stored_materials_release_amount'
        ), $data);
        if (!Session::has('draft_id')) {
            $data['draft_id'] = $this->invoiceModel->saveDraft($request_id, $this->user_id);
            Session::put('draft_id', $data['draft_id']);
        } else {
            $data['draft_id'] = Session::get('draft_id');
        }
        if ($data['dpid'] > 0) {
            $this->invoiceModel->updateConstructionParticular($data, $data['dpid'], $this->user_id, 'draft');
            $id = $data['dpid'];
        } else {
            $id = $this->invoiceModel->saveConstructionParticular($data, $request_id, $this->user_id, 'draft');
        }
        return $id;
    }

    function storeRevision($req_id, $revision, $template_type = 'construction')
    {
        $user_id = $this->user_id;
        $new_payment_request = $this->invoiceModel->getTableRow('payment_request', 'payment_request_id', $req_id);
        $new_payment_request = json_decode(json_encode($new_payment_request), 1);
        $result1 = array_diff($revision['payment_request'], $new_payment_request);
        $result2 = array_diff($new_payment_request, $revision['payment_request']);
        if (!empty($result2)) {
            $result = array_merge($result1, $result2);
        } else {
            $result = $result1;
        }
        $revision_array = [];
        $revision_column_json = null;
        if (env('APP_ENV') != 'LOCAL') {
            $revision_column_json = Redis::get('revision_payment_request_column');
        }
        if ($revision_column_json == null) {
            $revision_column_json = '{"bill_date":"Bill date","due_date":"Due date","grand_total":"Grand total","currency":"Currency"}';
        }
        $revision_columns = json_decode($revision_column_json, 1);
        if (!empty($result)) {
            foreach ($result as $key => $row) {
                if (isset($revision_columns[$key])) {
                    $col_name = $revision_columns[$key];
                    $old_value = $revision['payment_request'][$key];
                    $new_value = $new_payment_request[$key];
                    if ($key == 'bill_date' || $key == 'due_date') {
                        $old_value = Helpers::htmlDate($old_value);
                        $new_value = Helpers::htmlDate($new_value);
                    }
                    if ($key == 'grand_total') {
                        $old_value = number_format($old_value, 2);
                        $new_value = number_format($new_value, 2);
                    }

                    $title =  'updated ' . ucfirst($col_name) . ' from ' . $old_value . ' to ' . $new_value;
                    $revision_array['payment_request'][$key] = array('title' => $title, 'old_value' => $revision['payment_request'][$key], 'new_value' => $new_payment_request[$key]);
                }
            }
        }
        $new_invoice_values = $this->invoiceModel->getTableList('invoice_column_values', 'payment_request_id', $req_id);
        $new_invoice_values = json_decode(json_encode($new_invoice_values), 1);
        $column_values = [];
        foreach ($revision['invoice_column_values'] as $row) {
            $column_values[$row['invoice_id']] = array('column_id' => $row['column_id'], 'value' => $row['value']);
        }
        $new_column_values = [];
        foreach ($new_invoice_values as $row) {
            $new_column_values[$row['invoice_id']] = array('column_id' => $row['column_id'], 'value' => $row['value']);
        }

        if (!empty($column_values)) {
            foreach ($column_values as $key => $row) {
                if ($column_values[$key]['value'] != $new_column_values[$key]['value']) {
                    $col_name = $this->invoiceModel->getColumnValue('invoice_column_metadata', 'column_id', $column_values[$key]['column_id'], 'column_name');
                    $title =  'updated ' . $col_name . ' from ' . $column_values[$key]['value'] . ' to ' . $new_column_values[$key]['value'];
                    $revision_array['invoice_column_values'][$key] = array('title' => $title, 'old_value' => $column_values[$key]['value'], 'new_value' => $new_column_values[$key]['value']);
                }
            }
        }

        if (!empty($revision_array)) {
            // dd("select count(*) as total from invoice_revision where payment_request_id='" . $req_id . "'");
            $version_count = $this->invoiceModel->querylist("select count(*) as total from invoice_revision where payment_request_id='" . $req_id . "'");
            if ($version_count[0]->total > 0) {
                $version = $version_count[0]->total + 1;
                $version = 'V' . $version;
            } else {
                $version = 'V1';
            }

            $new_particular = [];
            $old_particular = [];

            $table_name = 'invoice_construction_particular';
            $new_construction_particular = $this->invoiceModel->getTableList($table_name, 'payment_request_id', $req_id);
            $new_construction_particular = json_decode(json_encode($new_construction_particular), 1);
            if (isset($revision[$table_name])) {
                foreach ($revision[$table_name] as $row) {
                    $id = $row['id'];
                    $removeKeys = array('id', 'payment_request_id', 'calculated_perc', 'calculated_row', 'group_code1', 'group_code2', 'group_code3', 'group_code4', 'group_code5', 'is_active', 'created_by', 'created_date', 'last_update_by', 'last_update_date');
                    $row = array_diff_key($row, array_flip($removeKeys));
                    $old_particular[$id] = $row;
                }
            }

            foreach ($new_construction_particular as $row) {
                $id = $row['id'];
                $removeKeys = array('id', 'payment_request_id', 'calculated_perc', 'calculated_row', 'is_active', 'created_by', 'group_code1', 'group_code2', 'group_code3', 'group_code4', 'group_code5', 'created_date', 'last_update_by', 'last_update_date');
                $row = array_diff_key($row, array_flip($removeKeys));
                $new_particular[$id] = $row;
            }

            foreach ($old_particular as $key => $row) {
                if (isset($new_particular[$key])) {
                    $array1 = $old_particular[$key];
                    $array2 = $new_particular[$key];
                    $result = array_diff($array1, $array2);
                    if (!empty($result)) {
                        $title =  'updated row';
                        $revision_array[$table_name][$key] = array('title' => $title, 'type' => 'update', 'old_value' => $old_particular[$key], 'new_value' => $new_particular[$key]);
                    }
                } else {
                    $title =  'removed row';
                    $revision_array[$table_name][$key] = array('title' => $title, 'type' => 'remove', 'old_value' => $old_particular[$key], 'new_value' => '');
                }
            }

            foreach ($new_particular as $key => $row) {
                if (!isset($old_particular[$key])) {
                    $title =  'added new row';
                    $revision_array[$table_name][$key] = array('title' => $title, 'type' => 'add', 'old_value' => [], 'new_value' => $new_particular[$key]);
                }
            }
            $this->invoiceModel->updateTable('payment_request', 'payment_request_id', $req_id, 'revision_no', $version);
            $this->invoiceModel->saveRevision($req_id, json_encode($revision_array), $version, $user_id);
        }
    }

    public function getCostTypes(): array
    {
        return CostType::where('merchant_id', $this->merchant_id)->where('is_active', 1)
            ->select(['id as value', DB::raw('CONCAT(abbrevation, " - ", name) as label')])
            ->get()->toArray();
    }



    public function particular($link, $req_type = null)
    {
        $request_id = Encrypt::decode($link);

        if (!is_numeric($request_id)) {
            return redirect('/error/invalidlink');
        }
        $invoice = $this->invoiceModel->getTableRow('subcontract_payment_request', 'request_id', $request_id);
        $contract = $this->invoiceModel->getTableRow('sub_contract', 'sub_contract_id', $invoice->sub_contract_id);
        $project = $this->invoiceModel->getTableRow('project', 'id', $contract->project_id);
        $csi_codes = $this->invoiceModel->getBillCodes($contract->project_id);


        $cost_codes = [];
        $cost_types = [];
        $invoice_particulars = $this->invoiceModel->getTableListOrderby('subcontract_request_payment_particular', 'subcontract_request_id', $request_id, 'sort_order');
        $merchant_cost_types = $this->getCostTypes();
        $particulars[] = [];
        $groups = [];
        $total = 0;

        $order_id_array = [];
        $change_order_enable = true;
        $change_order_compare = false;
        $exist_order_id_array = [];
        $new_change_order_array = [];
        $cp = array();
        if ($invoice_particulars->isEmpty()) {
            $type = 1;
            $particulars = json_decode($contract->particulars);
            $pre_req_id =  $this->invoiceModel->getPreviousSubContractRequestPayment($this->merchant_id, $invoice->sub_contract_id, $request_id);
            if ($pre_req_id != false) {
                $particulars = $this->invoiceModel->getTableList('subcontract_request_payment_particular', 'subcontract_request_id', $pre_req_id);
                foreach ($particulars as $key => $row) {
                    $particulars[$key]->previously_stored_materials = $particulars[$key]->previously_stored_materials + $particulars[$key]->current_stored_materials;
                    $particulars[$key]->previously_billed_amount = $particulars[$key]->previously_billed_amount + $particulars[$key]->current_billed_amount;
                    $particulars[$key]->previously_billed_percent = $particulars[$key]->previously_billed_percent + $particulars[$key]->current_billed_percent;
                    $particulars[$key]->retainage_amount_previously_withheld = $particulars[$key]->retainage_amount_previously_withheld + $particulars[$key]->retainage_amount_for_this_draw;
                    $particulars[$key]->retainage_amount_previously_stored_materials = $particulars[$key]->retainage_amount_previously_stored_materials + $particulars[$key]->retainage_amount_stored_materials;
                    $particulars[$key]->previously_stored_materials = $particulars[$key]->stored_materials;
                    $particulars[$key]->current_billed_amount = '';
                    $particulars[$key]->current_billed_percent = '';

                    $particulars[$key]->retainage_amount_for_this_draw = '';
                    $particulars[$key]->retainage_amount_stored_materials = '';
                    $particulars[$key]->current_stored_materials = '';
                    $particulars[$key]->id = '';
                    $cp[$row->bill_code] = $row;
                }
            }
        } else {
            $type = 2;
            $particulars = json_decode(json_encode($invoice_particulars), 1);
            foreach ($particulars as $k => $row) {
                $total = $total + $particulars[$k]['net_billed_amount'];
                $particulars[$k]['override'] = false;

                if ($particulars[$k]['attachments'] != '') {
                    $attachment = json_decode($particulars[$k]['attachments'], 1);
                    $particulars[$k]['count'] = count($attachment);
                    $particulars[$k]['attachments'] = implode(',', $attachment);
                }

                foreach ($row as $kr => $kv) {
                    if ($kr != 'pint') {
                        $particulars[$k][$kr] = ($particulars[$k][$kr] == '0.00') ? '' : $particulars[$k][$kr];
                    }
                }
            }
            $exist_order_id_array = json_decode($invoice->change_order_id, 1);
            $order_id_array = $exist_order_id_array;
            if ($invoice->payment_request_status <> 11) {
                $change_order_enable = false;
            } else {
                $change_order_compare = true;
            }
        }

        if ($change_order_enable == true) {
            $change_order_data = $this->invoiceModel->getOrderbyContract($invoice->sub_contract_id, $invoice->bill_date);
            $change_order_data = json_decode($change_order_data, true);
            $cop_particulars = [];
            foreach ($change_order_data as $co_data) {
                $change_order_id = (int)$co_data["order_id"];
                $include_co = false;
                if (in_array($change_order_id, $exist_order_id_array)) {
                    $include_co = true;
                } else {
                    if ($req_type != null || $change_order_compare == false) {
                        $include_co = true;
                    } else {
                        $co_detail = $this->invoiceModel->getTableRow('order', 'order_id', $change_order_id);
                        $new_change_order_array[] = array('order_no' => $co_detail->order_no, 'total_change_order_amount' => $co_detail->total_change_order_amount, 'approved_date' => $co_detail->approved_date);
                    }
                }
                if ($include_co == true) {
                    array_push($order_id_array, $change_order_id);
                    foreach (json_decode($co_data["particulars"], true) as $co_par) {
                        $co_par["change_order_amount"] = $co_par["change_order_amount"];
                        array_push($cop_particulars, $co_par);
                    }
                }
            }

            $result = array();
            foreach ($cop_particulars as $k => $v) {
                $id = $v['bill_code'];
                $result[$id][] = $v['change_order_amount'];
            }

            $co_particulars = array();
            foreach ($result as $key => $value) {
                foreach ($cop_particulars as $kdata) {
                    if ($kdata["bill_code"] == $key) {
                        $co_particulars[] = array(
                            'bill_code' => $key,
                            'change_order_amount' => array_sum($value),
                            'description' =>  $kdata["description"],
                            'retainage_percent' => isset($kdata["retainage_percent"]) ? $kdata["retainage_percent"] : '',
                            'sub_group' => isset($kdata["sub_group"]) ? $kdata["sub_group"] : '',
                            'group' => isset($kdata["group"]) ? $kdata["group"] : '',
                            'cost_type' =>  isset($kdata["cost_type"]) ? $kdata["cost_type"] : ''
                        );
                    }
                }
            }

            if ($change_order_data != false) {
                $cop = array();
                $particulars = json_decode(json_encode($particulars), 1);
                foreach ($particulars as $row2) {
                    if (isset($cop[$row2['bill_code']])) {
                        $cop[$row2['bill_code'] . rand()] = $row2;
                    } else {
                        $cop[$row2['bill_code']] = $row2;
                    }
                }
                foreach ($co_particulars as $k => $v) {
                    if (isset($cop[$v["bill_code"]])) {
                        $cop[$v["bill_code"]]['approved_change_order_amount'] = $v["change_order_amount"];
                        $cop[$v["bill_code"]]['retainage_percent'] = $v["retainage_percent"];
                        $cop[$v["bill_code"]]['sub_group'] = $v["sub_group"];
                        $cop[$v["bill_code"]]['group'] = $v["group"];
                    } else {
                        $cop[$v["bill_code"]] = [];
                        $cop[$v["bill_code"]]['approved_change_order_amount'] = $v["change_order_amount"];
                        $cop[$v["bill_code"]]['original_contract_amount'] = 0;
                        $cop[$v["bill_code"]]['bill_code'] = $v["bill_code"];
                        $cop[$v["bill_code"]]['cost_type'] = $v["cost_type"];
                        $cop[$v["bill_code"]]['bill_type'] = '% Complete';
                        $cop[$v["bill_code"]]['description'] = $v["description"];
                        $cop[$v["bill_code"]]['retainage_percent'] = $v["retainage_percent"];
                        $cop[$v["bill_code"]]['sub_group'] = $v["sub_group"];
                        $cop[$v["bill_code"]]['group'] = $v["group"];
                        $cop[$v["bill_code"]]['calculated_perc'] = '';
                        $cop[$v["bill_code"]]['calculated_row']  = '';
                    }
                }
                $particulars_c = json_decode(json_encode($cop), 1);
                $particulars = [];
                foreach ($particulars_c as $row) {
                    $particulars[] = $row;
                }
            }
            $particulars = json_decode(json_encode($particulars), 1);

            $int = 0;
            foreach ($particulars as $k => $row) {
                $ocm = (is_numeric($row['original_contract_amount'])) ? $row['original_contract_amount'] : 0;
                $acoa = (isset($row['approved_change_order_amount'])) ? (is_numeric($row['approved_change_order_amount']) ? $row['approved_change_order_amount'] : 0) : 0;
                $particulars[$k]['current_contract_amount'] = $ocm + $acoa;
                $particulars[$k]['attachments'] = '';
                $particulars[$k]['override'] = false;
                if (isset($row['pint'])) {
                    $int = $row['pint'];
                } else {
                    $particulars[$k]['pint'] = $int + 1;
                    $int = $int + 1;
                }
                $particulars[$k]['sort_order'] = $int;
            }
        }


        if (!empty($particulars)) {
            foreach ($particulars as $cp) {
                if ($cp['group'] != '') {
                    if (!in_array($cp['group'], $groups)) {
                        $groups[] = $cp['group'];
                    }
                }
            }
        }
        $mode = ($invoice->payment_request_status == 11) ? 'Preview' : 'Save';
        Helpers::hasRole(2, 27);
        $title = 'create';

        Session::put('valid_ajax', 'expense');
        $data = Helpers::setBladeProperties(ucfirst($title) . ' contract', ['invoiceConstruction', 'template', 'invoiceformat2'], [3, 179]);
        $merchant_cost_types_array = $this->getKeyArrayJson($merchant_cost_types, 'value');
        $data['merchant_cost_types'] = $merchant_cost_types;
        $data['cost_types_array'] = $merchant_cost_types_array;
        $data['cost_types'] = json_decode($merchant_cost_types_array, 1);
        $data['cost_codes'] = $cost_codes;
        $data['order_id_array'] = json_encode($order_id_array);
        $data['new_change_order_array'] = $new_change_order_array;
        $data['gst_type'] = 'intra';
        $data['type'] = $type;
        $data['button'] = 'Save';
        $data['title'] = 'Add Particulars';
        $data['contract_id'] = $invoice->sub_contract_id;
        $data['contract_code'] = $contract->sub_contract_code;
        $data['project_id'] = $project->id;
        $data['project_code'] = $project->project_id;
        $data['link'] = $link;
        $data['draft_particulars'] = '';




        list($particulars, $summary) = $this->setParticularMoney($particulars);
        $data['particulars'] = $particulars;
        $data['summary'] = $summary;
        $data['csi_codes'] = json_decode(json_encode($csi_codes), 1);
        $data['csi_codes_array'] = $this->getKeyArrayJson($data['csi_codes'], 'value');
        $data['csi_codes_list'] = json_decode($data['csi_codes_array'], 1);
        $data['total'] = $total;
        $data['groups'] = $groups;
        $data['mode'] = $mode;
        $particular_column = '{"bill_code":"Bill Code","description":"Desc","bill_type":"Bill Type","cost_type":"Cost Type","original_contract_amount":"Original Contract Amount","approved_change_order_amount":"Approved Change Order Amount","current_contract_amount":"Current Contract Amount","previously_billed_percent":"Previously Billed Percent","previously_billed_amount":"Previously Billed Amount","current_billed_percent":"Current Billed Percent","current_billed_amount":"Current Billed Amount","previously_stored_materials":"Previously Stored Materials","current_stored_materials":"Current Stored Materials","stored_materials":"Materials Presently Stored","total_billed":"Total Billed (including this draw)","retainage_percent":"Retainage %","retainage_amount_previously_withheld":"Retainage Amount Previously Withheld","retainage_amount_for_this_draw":"Retainage amount for this draw","retainage_percent_stored_materials":"Retainage % Stored Materials","retainage_amount_previously_stored_materials":"Retainage Amount Previously Stored Materials","retainage_amount_stored_materials":"Retainage amount Stored Materials","net_billed_amount":"Net Billed Amount","retainage_release_amount":"Retainage Release Amount","retainage_stored_materials_release_amount":"Retainage Release Amount Stored Materials","total_outstanding_retainage":"Total outstanding retainage","task_number":"Task No.","project":"Project","cost_code":"Cost Code","group":"Group","bill_code_detail":"Bill code detail"}';
        $data["particular_column"] = json_decode($particular_column, 1);
        Session::remove('draft_id');
        return view('app/merchant/subcontractrequest/invoice-particular', $data);
    }

    private function setParticularMoney($particulars)
    {
        $list = [];
        $arraysum = ['original_contract_amount', 'approved_change_order_amount', 'current_contract_amount',  'previously_billed_amount',  'current_billed_amount', 'total_billed', 'retainage_amount_previously_withheld', 'retainage_amount_for_this_draw', 'retainage_amount_previously_stored_materials', 'retainage_stored_materials_release_amount', 'retainage_amount_stored_materials', 'net_billed_amount', 'retainage_release_amount', 'total_outstanding_retainage', 'stored_materials', 'previously_stored_materials', 'current_stored_materials'];
        $array = ['original_contract_amount', 'approved_change_order_amount', 'current_contract_amount', 'previously_billed_percent', 'previously_billed_amount', 'current_billed_percent', 'current_billed_amount', 'total_billed', 'retainage_amount_previously_withheld', 'retainage_amount_for_this_draw', 'retainage_amount_previously_stored_materials', 'retainage_stored_materials_release_amount', 'retainage_amount_stored_materials', 'net_billed_amount', 'retainage_release_amount', 'total_outstanding_retainage', 'stored_materials', 'previously_stored_materials', 'current_stored_materials'];
        foreach ($particulars as $k => $v) {
            foreach ($array as $key) {
                if (isset($v[$key])) {
                    if ($v[$key] != '') {
                        if (in_array($key, $arraysum)) {
                            $list[$key][] = $particulars[$k][$key];
                        }
                        $particulars[$k][$key] = $this->num_format($particulars[$k][$key], 2, 0);
                    }
                }
            }
        }
        $summary = [];
        foreach ($list as $l => $v) {
            $summary['sum_' . $l] = $this->num_format(array_sum($v));
        }
        return array($particulars, $summary);
    }

    function num_format($numVal, $afterPoint = 2, $minAfterPoint = 0, $thousandSep = ",", $decPoint = ".")
    {
        // Same as number_format() but without unnecessary zeros.
        $ret = number_format($numVal, $afterPoint, $decPoint, $thousandSep);
        if ($afterPoint != $minAfterPoint) {
            while (($afterPoint > $minAfterPoint) && (substr($ret, -1) == "0")) {
                // $minAfterPoint!=$minAfterPoint and number ends with a '0'
                // Remove '0' from end of string and set $afterPoint=$afterPoint-1
                $ret = substr($ret, 0, -1);
                $afterPoint = $afterPoint - 1;
            }
        }
        if (substr($ret, -1) == $decPoint) {
            $ret = substr($ret, 0, -1);
        }
        return $ret;
    }

    private function getKeyArrayJson($array, $key)
    {
        $data = [];
        foreach ($array as $row) {
            $data[$row[$key]] = $row;
        }
        return json_encode($data);
    }

    public function preview($link)
    {
        $request_id = Encrypt::decode($link);
        if (strlen($request_id) != 10) {
            return redirect('/error/invalidlink');
        }
        $invoice = $this->invoiceModel->getTableRow('payment_request', 'payment_request_id', $request_id);
        $customer = $this->invoiceModel->getTableRow('customer', 'customer_id', $invoice->customer_id);
        $template = $this->invoiceModel->getTableRow('invoice_template', 'template_id', $invoice->template_id);
        $contract = $this->invoiceModel->getTableRow('contract', 'contract_id', $invoice->contract_id);
        $project = $this->invoiceModel->getTableRow('project', 'id', $contract->project_id);
        $csi_codes = $this->invoiceModel->getTableList('csi_code', 'project_id', $contract->project_id);
        $invoice_particulars = $this->invoiceModel->getTableList('invoice_construction_particular', 'payment_request_id', $request_id);
        $particulars[] = [];
        $groups = [];
        $total = 0;
        $particulars = json_decode(json_encode($invoice_particulars), 1);

        Helpers::hasRole(2, 27);
        $title = 'create';


        Session::put('valid_ajax', 'expense');

        $data = Helpers::setBladeProperties(ucfirst($title) . ' contract', ['expense', 'contract', 'product', 'template', 'invoiceformat2'], [3, 179]);
        $data['gst_type'] = 'intra';
        $data['button'] = 'Save';
        $data['mode'] = 'create';
        $data['title'] = 'Preview Invoice';
        $data['customer'] = $customer;
        $data['invoice'] = $invoice;
        $data['contract_id'] = $invoice->contract_id;
        $data['contract_code'] = $contract->contract_code;
        $data['project_id'] = $project->project_id;
        $data['project'] = $project;
        $data['link'] = $link;
        $data['particulars'] = $particulars;
        $data['csi_codes'] = json_decode(json_encode($csi_codes), 1);
        $data['total'] = $total;
        $data['groups'] = $groups;

        $data['plugin'] = json_decode($template->plugin, 1);
        $data['template_link'] = '';
        $data['invoice_type'] = 1;
        $data['notify_patron'] = 1;
        $data['payment_request_id'] = $request_id;



        $data["particular_column"] = json_decode($template->particular_column, 1);
        return view('app/merchant/invoice/invoice-preview', $data);
    }

    public function saveProjectInvoiceSequence()
    {
        $prefix = ($_POST['prefix'] != '') ? $_POST['prefix'] : '';
        $number = ($_POST['last_no'] != '' ? $_POST['last_no'] : 0);
        $prefix = str_replace('~', '/', $prefix);
        $separator = isset($_POST['seprator']) ? $_POST['seprator'] : '';

        $formatModel = new InvoiceFormat();
        if ($prefix == '' && $separator != '') {
            $response['error'] = 'You can not add separator without prefix';
            $response['status'] = 0;
        } else if ($number == '') {
            $response['error'] = 'Sequence number is required.';
            $response['status'] = 0;
        } else {

            $res = $formatModel->existInvoicePrefix($this->merchant_id, $prefix, $separator);

            if ($res == FALSE) {
                $seq_number = $number - 1;
                $id = $formatModel->saveSequence($this->merchant_id, $prefix, $seq_number, $this->user_id, $separator);
                $response['name'] = $prefix . $separator . $number;
                $response['id'] = $id;
                $response['prefix'] = $prefix;
                $response['number'] = $number;
                $response['seprator'] = $separator;
                $response['merchant_id'] = $this->merchant_id;
                $response['status'] = 1;
            } else {
                if ($prefix == '' && $separator == '') {
                    $response['status'] = 2;
                } else {
                    $response['error'] = 'Invoice prefix alredy exist';
                    $response['status'] = 0;
                }
            }
        }
        echo json_encode($response);
    }

    function saveExistingSequence()
    {
        $prefix = ($_POST['prefix'] != '') ? $_POST['prefix'] : '';
        $separator = isset($_POST['seprator']) ? $_POST['seprator'] : '';
        $formatModel = new InvoiceFormat();
        $res = $formatModel->existInvoicePrefix($this->merchant_id, $prefix, $separator);

        if ($res != '') {
            $response['name'] = $res->prefix . $res->seprator . $res->val;
            $response['id'] = $res->auto_invoice_id;
            $response['prefix'] = $res->prefix;
            $response['number'] = $res->val;
            $response['merchant_id'] = $this->merchant_id;
            $response['status'] = 1;
        } else {
            $response['status'] = 0;
        }
        echo json_encode($response);
    }

    function createNewSequence()
    {
        $prefix = ($_POST['prefix'] != '') ? $_POST['prefix'] : '';
        $number = ($_POST['last_no'] != '') ? $_POST['last_no'] : 0;
        $prefix = str_replace('~', '/', $prefix);
        $separator = isset($_POST['seprator']) ? $_POST['seprator'] : '';
        $formatModel = new InvoiceFormat();
        $seq_number = $number - 1;
        $id = $formatModel->saveSequence($this->merchant_id, $prefix, $seq_number, $this->user_id, $separator);
        $response['name'] = $prefix . $separator . $number;
        $response['id'] = $id;
        $response['status'] = 1;
        echo json_encode($response);
    }

    public function getInvoiceList(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'start' => 'numeric',
            'limit' => 'numeric',
        ]);
        if ($validator->fails()) {
            return response()->json($this->apiController->APIResponse(0, '', $validator->errors()), 422);
        }
        $start = ($request->start > 0) ? $request->start : -1;
        $limit = ($request->limit > 0) ? $request->limit : 15;
        $from_date = isset($request->from_date) ? Helpers::sqlDate($request->from_date) : date('Y-m-d', strtotime(date('01 M Y')));
        $to_date = isset($request->to_date) ? Helpers::sqlDate($request->to_date) : date('Y-m-d', strtotime(date('d M Y')));
        //$invoice_status =  isset($request->invoice_status) ? $request->invoice_status : '0';

        $list = $this->invoiceModel->getInvoiceList($request->merchant_id, $from_date, $to_date, $start, $limit);

        $response['lastno'] = count($list) + $start;
        $response['list'] = $list;
        return response()->json($this->apiController->APIResponse('', $response), 200);
    }

    public function getInvoiceDetails($payment_request_id)
    {
        if ($payment_request_id != null) {
            $info =  $this->invoiceModel->getInvoiceDetails($payment_request_id);
            return response()->json($this->apiController->APIResponse('', $info), 200);
        }
    }

    public function invoiceView($type, $link, $user_type = "merchant", Request $request)
    {
        $payment_request_id = Encrypt::decode($link);
        $notificationID = $request->get('notification_id');

        if (is_numeric($payment_request_id)) {
            $data = Helpers::setBladeProperties('Request payment    ', ['template'], [5, 181]);
            $data['gtype'] = $type;
            $userRole = Session::get('user_role');
            $data['user_type'] = $user_type;
            $invoice_Data = $this->getInvoiceDetailsForViews($payment_request_id, $userRole, $user_type);
            $data = array_merge($data, $invoice_Data);
            if (!empty($notificationID)) {
                /** @var Notification $Notification */
                $Notification = Notification::findOrFail($notificationID);
                $Notification->markAsRead();
            }

            if ($type == '703') {
                $particular_details = $this->get703Contents($payment_request_id, $data);
            } else if ($type == '702') {
                $particular_details = $this->get702Contents($payment_request_id, $data, $user_type);
            } else if ($type == 'co-listing') {
                $particular_details = $this->getChangeOrderListingContents($payment_request_id, $data);
            } else {
                return redirect('/error/invalidlink');
            }
            $data = array_merge($data, $particular_details);
            $data['has_total_co_col'] = false;
            return view('app/merchant/subcontractrequest/G' . $type . '/index', $data);
        } else {
            header('Location: /error/invalidlink');
            die();
        }
    }

    public function get703Contents($payment_request_id, $data)
    {
        $particular_details = $this->invoiceModel->getInvoiceConstructionParticularRows($payment_request_id);
        $particular_details = json_decode($particular_details, 1);
        $int = 0;
        $grand_total_schedule_value = 0;
        $grand_total_change_from_previous_application = 0;
        $grand_total_change_this_period = 0;
        $grand_total_current_total = 0;
        $grand_total_previouly_billed_amt = 0;
        $grand_total_current_billed_amt = 0;
        $grand_total_d_plus_e = 0;
        $grand_total_stored_material = 0;
        $grand_total_total_completed = 0;
        $grand_total_balance_to_finish = 0;
        $grand_total_budget_reallocation = 0;
        $grand_total_g_per = 0;
        $grand_total_retainge = 0;
        $has_budget = false;
        $particularRows = array();
        if (!empty($particular_details)) {
            foreach ($particular_details as $ck => $val) {
                //dd($val);
                if (!empty($val['group']) && $val['bill_code_detail'] == 'No') {
                    //show details without subgroup, total, bill code
                    $valArray = $this->setParticularRowArray($val, $data);
                    $particularRows[$val['group']]['no-bill-code-detail~'][$int] = $valArray;
                } else if (!empty($val['group']) && $val['bill_code_detail'] == 'Yes') {
                    //check if subgroup is exist in co
                    if ($val['sub_group'] != '') {
                        $groups[$val['group']]['subgroup'][$val['sub_group']] = $val['sub_group'];
                        if (in_array($val['sub_group'], $groups[$val['group']]['subgroup'])) {
                            $valArray = $this->setParticularRowArray($val, $data);
                            $particularRows[$val['group']]['subgroup'][$val['sub_group']][$int] = $valArray;
                        }
                    } else {
                        $groups[$val['group']][$val['group']] = $val['group'];
                        if (in_array($val['group'], $groups[$val['group']])) {
                            $valArray = $this->setParticularRowArray($val, $data);
                            $particularRows[$val['group']]['only-group~'][$int] = $valArray;
                        }
                    }
                } else {
                    //show all details without group , subgroup , total rows
                    $valArray = $this->setParticularRowArray($val, $data);
                    $particularRows['no-group~'][$int] = $valArray;
                }

                if (is_numeric($valArray['budget_reallocation'])) {
                    if ($valArray['budget_reallocation'] != 0) {
                        $has_budget = true;
                        $grand_total_budget_reallocation = $grand_total_budget_reallocation + $valArray['budget_reallocation'];
                    }
                }

                //calculate grand total
                if ($data['has_schedule_value']) {
                    $grand_total_schedule_value = $grand_total_schedule_value + $valArray['current_contract_amount'];
                } else {
                    $grand_total_schedule_value = $grand_total_schedule_value + $val['current_contract_amount'];
                }
                if ($data['has_schedule_value']) {
                    $grand_total_change_from_previous_application = $grand_total_change_from_previous_application + $valArray['change_from_previous_application'];
                    $grand_total_change_this_period = $grand_total_change_this_period + $valArray['change_this_period'];
                    $grand_total_current_total = $grand_total_current_total + $valArray['current_total'];
                }
                $grand_total_previouly_billed_amt = $grand_total_previouly_billed_amt + $val['previously_billed_amount'];
                $grand_total_d_plus_e = $grand_total_d_plus_e;
                $grand_total_current_billed_amt = $grand_total_current_billed_amt + $val['current_billed_amount'];
                $grand_total_stored_material = $grand_total_stored_material + $val['stored_materials'];
                $grand_total_total_completed = $grand_total_total_completed + ($val['previously_billed_amount'] + $val['current_billed_amount'] + $val['stored_materials']);
                if ($data['has_schedule_value']) {
                    if ($grand_total_current_total != 0) {
                        $grand_total_g_per = $grand_total_total_completed / $grand_total_current_total;
                    }
                    $grand_total_balance_to_finish = $grand_total_current_total - $grand_total_total_completed;
                } else {
                    if ($grand_total_schedule_value != 0) {
                        $grand_total_g_per = $grand_total_total_completed / $grand_total_schedule_value;
                    }
                    $grand_total_balance_to_finish = $grand_total_schedule_value - $grand_total_total_completed;
                }

                $grand_total_retainge = $grand_total_retainge + $val['total_outstanding_retainage'];

                $int++;
            }
        }

        $data['grand_total_schedule_value'] = $grand_total_schedule_value;
        $data['grand_total_change_from_previous_application'] = $grand_total_change_from_previous_application;
        $data['grand_total_change_this_period'] = $grand_total_change_this_period;
        $data['grand_total_current_total'] = $grand_total_current_total;
        $data['grand_total_previouly_billed_amt'] = $grand_total_previouly_billed_amt;
        $data['grand_total_d_plus_e'] = $grand_total_d_plus_e;
        $data['grand_total_current_billed_amt'] = $grand_total_current_billed_amt;
        $data['grand_total_stored_material'] = $grand_total_stored_material;
        $data['grand_total_total_completed'] = $grand_total_total_completed;
        $data['grand_total_budget_reallocation'] = $grand_total_budget_reallocation;
        $data['grand_total_g_per'] = $grand_total_g_per;
        $data['grand_total_balance_to_finish'] = $grand_total_balance_to_finish;
        $data['grand_total_retainge'] = $grand_total_retainge;
        $data['particularRows'] = $particularRows;
        $data['has_budget'] = $has_budget;
        //dd($data['particularRows']);
        return $data;
    }

    public function getInvoiceDetailsForViews($payment_request_id = null, $userRole = null, $user_type = null)
    {
        $merchant_id = ($user_type == 'merchant') ? $this->merchant_id : 'customer';
        $payment_request_data =  $this->invoiceModel->getPaymentRequestData($payment_request_id, $merchant_id);
        $project_details =  $this->invoiceModel->getProjectDeatils($payment_request_id);
        $project_details->owner_address = '';
        $data['project_details'] =  $project_details;

        if (!isset($payment_request_data->payment_request_status)) {
            // header('Location: /error/invalidlink');
            die();
        }

        //get currecy icon
        $currency_icon =  $this->invoiceModel->getCurrencyIcon($payment_request_data->currency)->icon;

        $data["url"] =  Encrypt::encode($payment_request_id);

        $data['invoice_number'] = $payment_request_data->request_number;
        $data['currency_icon'] = $currency_icon;
        $data['payment_request_status'] = $payment_request_data->payment_request_status;
        $data['absolute_cost'] = $payment_request_data->absolute_cost;
        $data['invoice_type'] = 1;
        $data['payment_request_id'] = $payment_request_data->request_id;
        $data['notify_patron'] = $payment_request_data->notify_patron;
        $data['payment_request_type'] = 1;
        $data['user_name'] = Session::get('user_name');
        $data['invoice_total'] = $payment_request_data->grand_total;
        $data["surcharge_amount"] = 0;
        $data['document_url'] = $payment_request_data->document_url;
        $data['bill_date'] = $payment_request_data->bill_date;
        $data['vendor_id'] = $payment_request_data->vendor_id;
        $data['customer_name'] = '';
        $data['company_name'] = $this->invoiceModel->getColumnValue('vendor', 'vendor_id', $payment_request_data->vendor_id, 'vendor_name');
        $plugins = [];
        $data['change_order_id'] = $payment_request_data->change_order_id;
        $data['created_date'] = $payment_request_data->created_date;
        $data['contract_id'] = $payment_request_data->sub_contract_id;

        $hasScheduleValue = false;
        if (isset($plugins['has_schedule_value'])) {
            $hasScheduleValue = true;
        }
        $data['has_schedule_value'] = $hasScheduleValue;

        $data['user_id'] = $payment_request_data->created_by;
        $hasAIALicense = false;
        if (isset($plugins['has_aia_license'])) {
            $hasAIALicense = true;
        }

        $hasListAllChangeOrders = false;
        if (isset($plugins['list_all_change_orders'])) {
            $hasListAllChangeOrders = true;
        }
        $data['has_aia_license'] = $hasAIALicense;
        $data['list_all_change_orders'] = $hasListAllChangeOrders;

        $has_watermark = false;
        $data['watermark_text'] = '';
        if (isset($plugins['has_watermark'])) {
            if ($plugins['has_watermark'] == 1) {
                $has_watermark = true;
                $data['watermark_text'] = $plugins['watermark_text'];
            }
        }
        $data['has_watermark'] = $has_watermark;
        //$data['cycle_name'] = $this->invoiceModel->getColumnValue('billing_cycle_detail', 'billing_cycle_id', $payment_request_data->billing_cycle_id, 'cycle_name');

        $data['grand_total'] =  $this->getGrandTotal((array)$payment_request_data,  $currency_icon);
        $data['grand_total_offline'] =  $this->getGrandTotal((array)$payment_request_data,  '');
        $data['user_type'] = $user_type;

        //check is online payment 
        $fee_id = $this->invoiceModel->getmerchantfeeID($payment_request_data->merchant_id);

        if ($fee_id != false) {
            $data['is_online_payment'] = TRUE;
        } else {
            $data['is_online_payment'] = FALSE;
        }

        //Check If user have acces to this invoice
        if ($user_type == 'merchant') {
            if (!empty($userRole) && $userRole == 'Admin') {
                $invoiceAccess = 'full';
            } else {
                $invoiceAccess = $this->hasInvoiceAccess($payment_request_data->payment_request_id, $payment_request_data->contract_id, $user_type);
            }

            //for invoice-alert blade file
            if (Session::get('success_array')) {
                $whatsapp_share = $this->getWhatsapptext($data);
                $success_array = Session::get('success_array');
                $active_payment = Session::get('has_payment_active');
                Session::remove('success_array');
                $data["invoice_success"] = true;

                $data["whatsapp_share"] = $whatsapp_share;
                foreach ($success_array as $key => $val) {
                    $data[$key] = $val;
                }
                if (Session::get('has_payment_active') == false) {
                    Session::put('has_payment_active', $this->invoiceModel->isPaymentActive($this->merchant_id));
                }
                if ($success_array['type'] == 'insert' && $active_payment == false) {
                    $data["payment_gateway_info"] = true;
                }
            }
        } else {
            $invoiceAccess = 'full';
        }

        $data['invoice_access'] = $invoiceAccess;

        return $data;
    }


    function setParticularRowArray($rowArray = null, $data = [])
    {
        if ($rowArray != null) {
            $rowArray['total_completed'] = $rowArray['previously_billed_amount'] + $rowArray['current_billed_amount'] + $rowArray['stored_materials'];
            if ($rowArray['current_contract_amount'] > 0) {
                $rowArray['g_per'] = $rowArray['total_completed'] / $rowArray['current_contract_amount'];
            } else {
                $rowArray['g_per'] = 0;
            }
            $rowArray['balance_to_finish'] = $rowArray['current_contract_amount'] - $rowArray['total_completed'];

            if (!empty($rowArray['attachments'])) {
                $nm = substr(substr(basename(json_decode($rowArray['attachments'], 1)[0]), 0, strrpos(basename(json_decode($rowArray['attachments'], 1)[0]), '.')), -10);
            }

            $rowArray['attachment'] = str_replace(' ', '_', $rowArray['attachments'] ? strlen(substr(basename(json_decode($rowArray['attachments'], 1)[0]), 0, strrpos(basename(json_decode($rowArray['attachments'], 1)[0]), '.'))) < 10 ? substr(basename(json_decode($rowArray['attachments'], 1)[0]), 0, strrpos(basename(json_decode($rowArray['attachments'], 1)[0]), '.')) : $nm : '');

            $counts = 0;
            if (!empty($rowArray['attachments']))
                $counts = count(json_decode($rowArray['attachments'], 1));
            if ($counts > 1)
                $rowArray['files'] = $counts . ' files';
            else
                $rowArray['files'] = $counts . ' file';

            //schedule plugin calcualtions
            $start_date = '1990-01-01';
            $end_date = date("Y-m-01", strtotime($data['bill_date']));
            $rowArray['change_from_previous_application'] = $this->getChangeOrderSumRow($data['change_order_id'], $rowArray['bill_code'], $start_date,  $end_date);

            $start_date = date("Y-m-01", strtotime($data['bill_date']));
            $end_date = date("Y-m-d", strtotime("first day of next month"));
            $rowArray['change_this_period'] = $this->getChangeOrderSumRow($data['change_order_id'], $rowArray['bill_code'], $start_date,  $end_date);
            if ($data['has_schedule_value']) {
                $rowArray['current_contract_amount'] = ($rowArray['current_contract_amount'] - $rowArray['approved_change_order_amount']);
            }
            $rowArray['budget_reallocation'] =  $this->getBudgetSumRow($data['change_order_id'], $rowArray['bill_code']);
            $rowArray['current_total'] = $rowArray['current_contract_amount'] + $rowArray['change_from_previous_application'] +  $rowArray['change_this_period'];
        }
        return $rowArray;
    }

    public function getChangeOrderSumRow($change_order_ids, $billcode, $start_date,  $end_date)
    {
        $total_co_amount = 0;
        $change_order_ids = json_decode($change_order_ids, 1);
        if (!empty($change_order_ids)) {
            $co_data  = (array)$this->invoiceModel->getChangeOrderAmountRow($change_order_ids, $start_date, $end_date);
            foreach ($co_data as $co_row) {
                foreach (json_decode($co_row->particulars, 1) as $row) {
                    if ($billcode == $row['bill_code']) {
                        if (is_numeric($row['change_order_amount'])) {
                            $total_co_amount =  $total_co_amount +  $row['change_order_amount'];
                        }
                    }
                }
            }
        }

        return $total_co_amount;
    }

    public function getBudgetSumRow($change_order_ids, $billcode)
    {
        $total_co_amount = 0;
        $change_order_ids = json_decode($change_order_ids, 1);
        if (!empty($change_order_ids)) {
            $co_data  = (array)$this->invoiceModel->getChangeOrderRow($change_order_ids);
            foreach ($co_data as $co_row) {
                foreach (json_decode($co_row->particulars, 1) as $row) {
                    if (isset($row['co_type'])) {
                        if ($billcode == $row['bill_code'] && $row['co_type'] == 2) {
                            if (is_numeric($row['budget_reallocation'])) {
                                $total_co_amount =  $total_co_amount +  $row['budget_reallocation'];
                            }
                        }
                    }
                }
            }
        }

        return $total_co_amount;
    }

    public function download($link, $savepdf = 0, $type = null, $user_type = 'merchant')
    {
        ini_set('max_execution_time', 120);
        $payment_request_id = Encrypt::decode($link);
        if (strlen($payment_request_id) == 10) {
            $data = $this->setBladeProperties('Invoice view', [], [3]);
            $userRole = Session::get('user_role');

            $invoice_Data = $this->getInvoiceDetailsForViews($payment_request_id, $userRole, $user_type);
            $data = array_merge($data, $invoice_Data);
            if ($type != null) {
                $imgpath = env('APP_URL') . '/images/logo-703.PNG';
                try {
                    $arrContextOptions = [
                        "ssl" => [
                            "verify_peer" => false,
                            "verify_peer_name" => false,
                            "allow_self_signed" => true,
                        ]
                    ];
                    $data['logo'] = base64_encode(file_get_contents($imgpath, false, stream_context_create($arrContextOptions)));
                } catch (Exception $o) {
                }
            }

            //refactor 703 code
            if ($type == '703') {
                $particular_703_details = $this->get703Contents($payment_request_id, $data);
                $data = array_merge($data, $particular_703_details);
            } else if ($type == '702') {
                $particular_702_details = $this->get702Contents($payment_request_id, $data, $user_type);
                $data = array_merge($data, $particular_702_details);
            } else if ($type == 'co-listing') {
                $particular_co_listing_details = $this->getChangeOrderListingContents($payment_request_id, $data);
                $data = array_merge($data, $particular_co_listing_details);
            } else if ($type == 'full') {
                $particular_702_details = $this->get702Contents($payment_request_id, $data, $user_type);
                $data = array_merge($data, $particular_702_details);
                $particular_703_details = $this->get703Contents($payment_request_id, $data);
                $data = array_merge($data, $particular_703_details);
                if ($data['list_all_change_orders']) {
                    $particular_co_listing_details = $this->getChangeOrderListingContents($payment_request_id, $data);
                    $particular_co_listing_details['viewtype'] = 'pdf';
                    $data = array_merge($data, ['co_listing_data' => $particular_co_listing_details]);
                }
                $attachements = $this->download_attachments($payment_request_id);
                $data = array_merge($data, $attachements);
            }

            $data['viewtype'] = 'pdf';
            define("DOMPDF_ENABLE_HTML5PARSER", true);
            define("DOMPDF_ENABLE_FONTSUBSETTING", true);
            define("DOMPDF_UNICODE_ENABLED", true);
            define("DOMPDF_DPI", 120);
            define("DOMPDF_ENABLE_REMOTE", true);
            $name = $data['customer_name'] . '_' . date('Y-M-d H:m:s');

            if ($type == '702' || $type == '703' || $type == 'co-listing') {
                $pdf = DOMPDF::loadView('mailer.invoice.format-' . $type . '-v2', $data);

                if ($type == 'co-listing') {
                    // If change order have more than 10 and 4 then change size
                    if (count($data['change_order_columns']) > 10) {
                        $pdf->setPaper("a2", "landscape");
                    } elseif (count($data['change_order_columns']) > 4) {
                        $pdf->setPaper("a3", "landscape");
                    } else {
                        $pdf->setPaper("a4", "landscape");
                    }
                } else {
                    $pdf->setPaper("a4", "landscape");
                }

                if ($savepdf == 1) {
                    $name = str_replace('-', '', $name);
                    $name = str_replace(':', '', $name);

                    $pdf->save(storage_path('pdf\\' . $type . $name . '.pdf'));
                    return $name;
                } else {
                    if ($savepdf == 2) {
                        return  $pdf->stream();
                    } else {
                        return $pdf->download($name . '.pdf');
                    }
                }
            } else {
                $pdf = DOMPDF::loadView('mailer.invoice.full-invoice-v2', $data);
                $pdf->setPaper("a4", "landscape");


                $name = str_replace(" ", "_", $data['customer_name']) . '_' . time() . '.pdf';
                $oMerger = PDFMerger::init();

                //If change order listing enable then merge it as new pdf
                if ($data['list_all_change_orders']) {
                    $coPDF = DOMPDF::loadView('mailer.invoice.format-co-listing-v2', $data['co_listing_data']);

                    if (count($data['co_listing_data']['change_order_columns']) > 10) {
                        $coPDF->setPaper("a2", "landscape");
                    } elseif (count($data['co_listing_data']['change_order_columns']) > 4) {
                        $coPDF->setPaper("a3", "landscape");
                    } else {
                        $coPDF->setPaper("a4", "landscape");
                    }

                    $coPDFName = str_replace(" ", "_", $data['customer_name']) . '_co_pdf_' . time() . '.pdf';
                    $fullPDFName = str_replace(" ", "_", $data['customer_name']) . '_full_pdf_' . time() . '.pdf';

                    $coPDFPath = 'public/pdf/' . $coPDFName;
                    $fullPDFPath = 'public/pdf/' . $fullPDFName;

                    Storage::disk('local')->put($coPDFPath, $coPDF->output());
                    Storage::disk('local')->put($fullPDFPath, $pdf->output());
                    $coPDFDOMPath = Storage::disk('local')->path($coPDFPath);
                    $fullPDFDOMPath = Storage::disk('local')->path($fullPDFPath);

                    $oMerger->addPDF($fullPDFDOMPath, 'all');
                    $oMerger->addPDF($coPDFDOMPath, 'all');
                    $oMerger->merge();

                    $oMerger->setFileName($data['customer_name'] . '.pdf');

                    return $oMerger->download();
                }


                if (count($data['pdf_link_array']) > 0) {
                    Storage::disk('local')->put($name, $pdf->output());
                    $DOMpath = Storage::disk('local')->path($name);
                    $oMerger->addPDF($DOMpath, 'all', 'L');

                    foreach ($data['pdf_link_array'] as $path) {
                        $oMerger->addPDF($path, 'all');
                    }

                    $oMerger->merge();
                    $oMerger->setFileName($name);
                    $oMerger->save();
                    return $oMerger->download();
                } else {
                    return $pdf->download($name);
                }
            }
        } else {
            return redirect('/error/invalidlink');
        }
    }

    public function download_attachments($payment_request_id)
    {

        $IAM_KEY = config('filesystems.disks.s3_expense.key');
        $IAM_SECRET = config('filesystems.disks.s3_expense.secret');
        $region = config('filesystems.disks.s3_expense.region');

        $s3 = S3Client::factory(
            array(
                'credentials' => array(
                    'key' => $IAM_KEY,
                    'secret' => $IAM_SECRET
                ),
                'version' => 'latest',
                'region'  => $region
            )
        );

        $invoicePaymentRequest = $this->invoiceModel->getTableRow('payment_request', 'payment_request_id', $payment_request_id);

        $invoiceAttachments = [];
        if (!empty($invoicePaymentRequest->plugin_value)) {
            $pluginValue = json_decode($invoicePaymentRequest->plugin_value);
            if (isset($pluginValue->has_upload)) {
                //uat.expense/invoices/download 190637995.jpeg
                $files = $pluginValue->files;
                foreach ($files as $file) {
                    if (!empty($file)) {
                        $fileUrlExplode = explode('/', $file);
                        $fileLastFromURL = end($fileUrlExplode);
                        $fileExplode = explode('.', $fileLastFromURL);

                        $fileName = Arr::first($fileExplode);
                        $fileType = Arr::last($fileExplode);
                        $fileContent = '';

                        if ($fileType == 'jpeg' || $fileType == 'jpg' || $fileType == 'png') {
                            $filePath = 'invoices/' . $fileLastFromURL;
                            $bucketName = 'uat.expense';

                            $result = $s3->getObject(array(
                                'Bucket' => $bucketName,
                                'Key'    => $filePath
                            ));

                            $body = $result->get('Body');
                            $fileContent = base64_encode($body->getContents());
                        }

                        $invoiceAttachments[] = [
                            'fileName' => $fileName,
                            'fileNameSlug' => Str::slug($fileName, '-'),
                            'fileType' => $fileType,
                            'fileContent' => $fileContent,
                            'url' => $file
                        ];
                    }
                }
            }
        }


        $data['invoice_attachments'] = $invoiceAttachments;


        $mandatoryDocumentAttachments = [];
        $pdf_link_array = [];
        if (isset($pluginValue->has_mandatory_upload)) {
            $oMerger = PDFMerger::init();
            if ($pluginValue->has_mandatory_upload == 1) {
                if (!empty($pluginValue->mandatory_data)) {
                    foreach ($pluginValue->mandatory_data as $key => $mandatory_data) {

                        $mandatory_files = $this->invoiceModel->getMandatoryDocumentByPaymentRequestID($payment_request_id, $mandatory_data->name);
                        foreach ($mandatory_files as $file) {
                            if (!empty($file->file_url)) {
                                $fileUrlExplode = explode('/', $file->file_url);
                                $fileLastFromURL = end($fileUrlExplode);
                                $fileExplode = explode('.', $fileLastFromURL);

                                $fileName = Arr::first($fileExplode);
                                $fileType = Arr::last($fileExplode);
                                $fileContent = '';

                                if ($fileType == 'jpeg' || $fileType == 'jpg' || $fileType == 'png') {
                                    $filePath = 'invoices/' . $fileLastFromURL;
                                    $bucketName = 'uat.expense';

                                    $result = $s3->getObject(array(
                                        'Bucket' => $bucketName,
                                        'Key'    => $filePath
                                    ));

                                    $body = $result->get('Body');
                                    $fileContent = base64_encode($body->getContents());
                                }

                                if ($fileType == 'pdf') {
                                    $filePath = 'invoices/' . $fileLastFromURL;
                                    $bucketName = 's3_expense';

                                    $source_path = 'invoices/' . basename($file->file_url);
                                    $file_content = Storage::disk($bucketName)->get($source_path);
                                    Storage::disk('local')->put($fileName . '.' . $fileType, $file_content);

                                    $path = Storage::disk('local')->path($fileName . '.' . $fileType);
                                    array_push($pdf_link_array, $path);
                                }

                                $mandatoryDocumentAttachments[] = [
                                    'fileName' => $fileName,
                                    'name' => $mandatory_data->name,
                                    'fileNameSlug' => Str::slug($fileName, '-'),
                                    'fileType' => $fileType,
                                    'fileContent' => $fileContent,
                                    'url' => $file->file_url
                                ];
                            }
                        }
                    }
                }
            }
        }

        $data['mandatory_document_attachments'] = $mandatoryDocumentAttachments;

        $constructionParticulars = $this->parentModel->getTableList('invoice_construction_particular', 'payment_request_id', $payment_request_id);

        $billCodeAttachments = [];
        foreach ($constructionParticulars as $constructionParticular) {
            $billCode = $this->parentModel->getTableRow(ITable::CSI_CODE, IColumn::ID, $constructionParticular->bill_code);
            $particularAttachments = json_decode($constructionParticular->attachments);

            $billCodeAttachments[$billCode->id] = [
                'billCodeId' => $billCode->id,
                'billCode' => $billCode->code,
                'billName' => $billCode->title,
                'attachments' => []
            ];

            if (!empty($particularAttachments)) {
                foreach ($particularAttachments as $particularAttachment) {
                    $urlExplode = explode('/', $particularAttachment);
                    $file = end($urlExplode);
                    $fileExplode = explode('.', $file);

                    $fileName = Arr::first($fileExplode);
                    $fileType = Arr::last($fileExplode);
                    $fileContent = '';

                    if ($fileType == 'jpeg' || $fileType == 'jpg' || $fileType == 'png') {
                        $filePath = 'invoices/' . $billCode->id . '/' . $file;
                        $bucketName = 'uat.expense';

                        $result = $s3->getObject(array(
                            'Bucket' => $bucketName,
                            'Key'    => $filePath
                        ));

                        $body = $result->get('Body');
                        $fileContent = base64_encode($body->getContents());
                    }


                    $billCodeAttachments[$billCode->id]['attachments'][] = [
                        'fileName' => $fileName,
                        'fileNameSlug' => Str::slug($fileName, '-'),
                        'fileType' => $fileType,
                        'fileContent' => $fileContent,
                        'url' => $particularAttachment
                    ];
                }
            }
        }

        $data['bill_code_attachments'] = $billCodeAttachments;
        $data['pdf_link_array'] = $pdf_link_array;

        return $data;
    }

    public function getChangeOrderListingContents($payment_request_id, $data)
    {
        $particular_details = $this->invoiceModel->getInvoiceConstructionParticularRows($payment_request_id);

        $changeOrdersData = $this->invoiceModel->getOrderbyContract($data['contract_id'], date("Y-m-d"));

        if ($changeOrdersData->count() <= 0) {
            return [
                'has_change_order_data' => true
            ];
        }

        $particular_details = json_decode($particular_details, 1);
        $int = 0;
        $grand_total_schedule_value = 0;
        $grand_total_original_schedule_value = 0;
        $grand_total_budget_reallocation = 0;
        $grand_total_current_billed_amt = 0;
        $grand_total_approved_change_order_value = 0;
        $particularRows = array();
        $changeOrderColumns = [];
        $changeOrderGroupData = [];
        $has_budget = false;

        if (!empty($particular_details)) {

            foreach ($particular_details as $ck => $val) {

                $changeOrderValues = [];
                foreach ($changeOrdersData as $changeOrderData) {

                    $changeOrderParticulars = json_decode($changeOrderData->particulars, 1);

                    $changeOrderGroupData[$changeOrderData->order_id] = $changeOrderParticulars;


                    if (!in_array($changeOrderData->order_id, $changeOrderColumns)) {
                        $changeOrderColumns[] = $changeOrderData->order_id;
                    }

                    //create collection for change order particulars
                    $changeOrderParticularsCollect = collect($changeOrderParticulars);

                    $findParticular = $changeOrderParticularsCollect->where('bill_code', $val['bill_code'])->first();

                    if (!empty($findParticular)) {
                        if (isset($findParticular['budget_reallocation'])) {
                            if (is_numeric($findParticular['budget_reallocation'])) {
                                if ($findParticular['budget_reallocation'] != 0) {
                                    $has_budget = true;
                                    $grand_total_budget_reallocation = $grand_total_budget_reallocation + $findParticular['budget_reallocation'];
                                    $findParticular['change_order_amount'] = $findParticular['change_order_amount'] - $findParticular['budget_reallocation'];
                                }
                            }
                        }
                        $changeOrderValues[$changeOrderData->order_id] = $findParticular['change_order_amount'];
                    } else {
                        $changeOrderValues[$changeOrderData->order_id] = 0;
                    }
                }



                if (!empty($val['group']) && $val['bill_code_detail'] == 'No') {
                    //show details without subgroup, total, bill code
                    $valArray = $this->setChangeOrderParticularRowArray($val, $data);
                    $valArray['change_order_col_values'] = $changeOrderValues;
                    $particularRows[$val['group']]['no-bill-code-detail~'][$int] = $valArray;
                } else if (!empty($val['group']) && $val['bill_code_detail'] == 'Yes') {

                    if ($val['sub_group'] != '') {
                        $groups[$val['group']]['subgroup'][$val['sub_group']] = $val['sub_group'];
                        if (in_array($val['sub_group'], $groups[$val['group']]['subgroup'])) {
                            $valArray = $this->setChangeOrderParticularRowArray($val, $data);
                            $valArray['change_order_col_values'] = $changeOrderValues;
                            $particularRows[$val['group']]['subgroup'][$val['sub_group']][$int] = $valArray;
                        }
                    } else {
                        $groups[$val['group']][$val['group']] = $val['group'];
                        if (in_array($val['group'], $groups[$val['group']])) {
                            $valArray = $this->setChangeOrderParticularRowArray($val, $data);
                            $valArray['change_order_col_values'] = $changeOrderValues;
                            $particularRows[$val['group']]['only-group~'][$int] = $valArray;
                        }
                    }
                } else {
                    //show all details without group , subgroup , total rows
                    $valArray = $this->setChangeOrderParticularRowArray($val, $data);
                    $valArray['change_order_col_values'] = $changeOrderValues;
                    $particularRows['no-group~'][$int] = $valArray;
                }

                //calculate grand total
                $grand_total_schedule_value = $grand_total_schedule_value + $val['current_contract_amount'];
                $grand_total_original_schedule_value = $grand_total_original_schedule_value + $val['original_contract_amount'];

                $grand_total_current_billed_amt = $grand_total_current_billed_amt + $val['current_billed_amount'];

                $grand_total_approved_change_order_value = $grand_total_approved_change_order_value + $val['approved_change_order_amount'];

                $int++;
            }
        }

        $data['grand_total_schedule_value'] = $grand_total_schedule_value;
        $data['grand_total_original_schedule_value'] = $grand_total_original_schedule_value;
        $data['grand_total_budget_reallocation'] = $grand_total_budget_reallocation;
        $data['grand_total_approved_change_order_value'] = $grand_total_approved_change_order_value;
        $data['particularRows'] = $particularRows;
        $data['change_order_columns'] = $changeOrderColumns;
        $data['change_orders_group_data'] = $changeOrderGroupData;
        $data['has_budget'] = $has_budget;


        return $data;
    }

    function setChangeOrderParticularRowArray($rowArray = null, $data = [])
    {
        if ($rowArray != null) {
            $rowArray['total_completed'] = $rowArray['previously_billed_amount'] + $rowArray['current_billed_amount'] + $rowArray['stored_materials'];
            if ($rowArray['current_contract_amount'] > 0) {
                $rowArray['g_per'] = $rowArray['total_completed'] / $rowArray['current_contract_amount'];
            } else {
                $rowArray['g_per'] = 0;
            }
            $rowArray['balance_to_finish'] = $rowArray['current_contract_amount'] - $rowArray['total_completed'];

            if (!empty($rowArray['attachments'])) {
                $nm = substr(substr(basename(json_decode($rowArray['attachments'], 1)[0]), 0, strrpos(basename(json_decode($rowArray['attachments'], 1)[0]), '.')), -10);
            }

            $rowArray['attachment'] = str_replace(' ', '_', $rowArray['attachments'] ? strlen(substr(basename(json_decode($rowArray['attachments'], 1)[0]), 0, strrpos(basename(json_decode($rowArray['attachments'], 1)[0]), '.'))) < 10 ? substr(basename(json_decode($rowArray['attachments'], 1)[0]), 0, strrpos(basename(json_decode($rowArray['attachments'], 1)[0]), '.')) : $nm : '');

            $counts = 0;
            if (!empty($rowArray['attachments']))
                $counts = count(json_decode($rowArray['attachments'], 1));
            if ($counts > 1)
                $rowArray['files'] = $counts . ' files';
            else
                $rowArray['files'] = $counts . ' file';

            //schedule plugin calcualtions
            $start_date = '1990-01-01';
            $end_date = date("Y-m-01", strtotime($data['bill_date']));
            $rowArray['change_from_previous_application'] = $this->getChangeOrderSumRow($data['change_order_id'], $rowArray['bill_code'], $start_date,  $end_date);

            $start_date = date("Y-m-01", strtotime($data['bill_date']));
            $end_date = date("Y-m-d", strtotime("first day of next month"));
            $rowArray['change_this_period'] = $this->getChangeOrderSumRow($data['change_order_id'], $rowArray['bill_code'], $start_date,  $end_date);
            $rowArray['budget_reallocation'] =  $this->getBudgetSumRow($data['change_order_id'], $rowArray['bill_code']);

            $rowArray['current_total'] = $rowArray['current_contract_amount'] + $rowArray['change_from_previous_application'] +  $rowArray['change_this_period'];
        }
        return $rowArray;
    }
}
