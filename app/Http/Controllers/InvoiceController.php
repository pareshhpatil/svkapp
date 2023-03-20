<?php

namespace App\Http\Controllers;

use App\Constants\Models\IColumn;
use App\Constants\Models\ITable;
use App\Model\Notification;
use Aws\S3\S3Client;
use Illuminate\Http\Request;
use App\Libraries\Encrypt;
use App\Model\InvoiceFormat;
use App\Model\Invoice;
use App\Model\ParentModel;
use DOMPDF;
use App\Model\Master;
use App\Libraries\Helpers;
use App\Model\InvoiceColumnMetadata;
use App\Http\Controllers\AppController;
use App\Http\Traits\InvoiceFormatTrait;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Validator;
use Exception;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Redis;
use Numbers_Words;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Filesystem;
use League\Flysystem\ZipArchive\ZipArchiveAdapter;
use File;
use App\Model\CostType;
use App\PaymentRequest;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Calculation\TextData\Replace;
use App\Http\Controllers\API\APIController;
use App;

use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;

class InvoiceController extends AppController
{
    private $formatModel;
    use InvoiceFormatTrait;

    private $invoiceModel;
    private $parentModel;
    private $apiController = null;

    public function __construct()
    {

        parent::__construct();

        $this->invoiceModel = new Invoice();
        $this->parentModel = new ParentModel();
        $this->formatModel = new InvoiceFormat();
        $this->inventory_service_id = Encrypt::encode('15'); //15 service_id
        $this->apiController = new APIController();
    }
    /**
     * Renders form to create invoice
     *
     * @param $type - Invoice or Estimate or Subscription
     *
     * @return void
     */
    public function createlegacy(Request $request, $type = 'invoice', $invoice_type = 1)
    {
        if (env('INVOICE_VERSION') == '2') {
            return redirect('/merchant/invoice/createv2');
        }

        if (isset($request->invoice_type)) {
            $invoice_type = $request->invoice_type;
        }
        $req_types = array('invoice' => 1, 'estimate' => 2, 'subscription' => 4, 'construction' => 1);
        $menus = array('invoice' => 19, 'estimate' => 122, 'subscription' => 21, 'construction' => 19);
        if (!isset($req_types[$type])) {
            throw new Exception('Invalid invoice type ' . $type);
        }
        $template_type = '';
        $menu = $menus[$type];
        $data = $this->setBladeProperties('Create ' . $type, ['invoiceformat', 'template', 'coveringnote', 'product', 'subscription'], [3, $menu]);
        #get merchant invoice format list
        $data['format_list'] = $this->invoiceModel->getMerchantFormatList($this->merchant_id, $type);
        if (count($data['format_list']) == 1) {
            $request->template_id = $data['format_list']->first()->template_id;
        }

        $data['billing_profile'] = $this->invoiceModel->getMerchantValues($this->merchant_id, 'merchant_billing_profile');
        $data['billing_profile_id'] = '';
        if (count($data['billing_profile']) == 1) {
            $request->billing_profile_id = $data['billing_profile']->first()->id;
            $data['billing_profile_id'] = $request->billing_profile_id;
        }
        $data['currency'] = '';
        $data['template_id'] = '';
        $data['currency_list'] = Session::get('currency');
        $data['multi_currency'] = env('ENABLE_MULTI_CURRENCY');
        $data['subscription'] = 0;
        $data['type'] = ucfirst($type);
        $data['invoice_product_taxation'] = 1;
        $data['richtext'] = true;
        if ($invoice_type == 2 || $type == 'estimate') {
            $data['invoice_type'] = 2;
        } else {
            $data['invoice_type'] = 1;
        }

        $data['request_type'] = $req_types[$type];


        $data['contract_id'] = 0;
        $userRole = Session::get('user_role');

        if($userRole == 'Admin') {
            $contractPrivilegesIDs = ['all' => 'full'];
        } else {
            //get privileges from redis
            $contractPrivilegesIDs = json_decode(Redis::get('contract_privileges_' . $this->user_id), true);
        }

        //contracts from privileges
        $whereContractIDs = [];
        foreach ($contractPrivilegesIDs as $key => $contractPrivilegesID) {
            if($contractPrivilegesID == 'full') {
                $whereContractIDs[] = $key;
            }
        }

        $data['contract'] = $this->invoiceModel->getContract($this->merchant_id, $whereContractIDs, $userRole);
        $breadcrumbs['menu'] = 'collect_payments';
        $breadcrumbs['title'] = $data['title'];
        $breadcrumbs['url'] = '/merchant/invoice/create/' . $type;

        if (env('APP_ENV') != 'LOCAL') {
            //menu list
            $mn1 = Redis::get('merchantMenuList' . $this->merchant_id);
            $item_list = json_decode($mn1, 1);
            $row_array['name'] = $data['title'];
            $row_array['link'] = '/merchant/invoice/create/' . $type;
            $item_list[] = $row_array;
            Redis::set('merchantMenuList' . $this->merchant_id, json_encode($item_list));
        }

        $request->session()->put('breadcrumbs', $breadcrumbs);
        if (isset($request->template_id)) {
            $formatModel = new InvoiceFormat();
            $template_id = $request->template_id;
            $data['template_link'] = Encrypt::encode($template_id);
            $data['template_id'] = $template_id;
            $data['contract_id'] = isset($request->contract_id) ? $request->contract_id : 0;
            $data['type'] = ($data['contract_id'] > 0) ? 'construction' : '';
            $data = $this->setInvoiceData($data, $template_id, $request->billing_profile_id, $request->currency);

            #get pre define system column metadata
            $metarows = $formatModel->getFormatMetadata($template_id);
            $metadata = $this->setMetadata($metarows);
            $invoice_seq_id = 0;
            if (isset($data['contract_detail']->sequence_number)) {
                $invoice_seq_id = $data['contract_detail']->sequence_number;
            }
            if (isset($metadata['H'])) {
                $metadata['H'] = $this->setCreateFunction($metadata['H']);
                foreach ($metadata['H'] as $k => $row) {
                    if (isset($row->script)) {
                        $data['script'] .= $row->script;
                    }

                    if ($row->function_id == 9 && $row->param == 'system_generated') {
                        if ($invoice_seq_id > 0) {
                            $metadata['H'][$k]->value = "System generated" . $invoice_seq_id;
                            $metadata['H'][$k]->param_value = $invoice_seq_id;
                        }
                        if ($metadata['H'][$k]->param_value > 0) {
                            $seq_row = $this->invoiceModel->getTableRow('merchant_auto_invoice_number', 'auto_invoice_id', $metadata['H'][$k]->param_value);
                            $seq_no = $seq_row->val + 1;
                            $metadata['H'][$k]->display_value =  $seq_row->prefix .  $seq_no;
                        }
                    }
                }
            }

            $template_type = $data['template_info']->template_type;
            $data['metadata'] = $metadata;
            $data['mode'] = 'create';
            $data['cycleName'] = date('M-Y') . ' Bill';
            $data['plugin'] = json_decode($data['template_info']->plugin, 1);
            $data['properties'] = json_decode($data['template_info']->properties, 1);
            $data['setting'] = json_decode($data['template_info']->setting, 1);
        }
        if ($template_type == 'construction') {
            return view('app/merchant/invoice/construction', $data);
        }
        return view('app/merchant/invoice/create', $data);
    }

    public function create(Request $request, $link = null, $update = null)
    {
        $cycleName = date('M-Y') . ' Bill';
        $invoice_number = '';
        $bill_date = '';
        $due_date = '';
        $narrative = '';
        $plugin = [];
        if ($link != null) {
            $request_id = Encrypt::decode($link);
            if (strlen($request_id) != 10) {
                return redirect('/error/invalidlink');
            }

            $invoice = $this->invoiceModel->getTableRow('payment_request', 'payment_request_id', $request_id);
            if ($update == 1 && $invoice->payment_request_status != 11) {
                $req_id = $this->invoiceModel->validateUpdateConstructionInvoice($invoice->contract_id, $this->merchant_id);
                if ($req_id != false) {
                    if ($request_id != $req_id) {
                        $invoice_number = $this->invoiceModel->getColumnValue('payment_request', 'payment_request_id', $req_id, 'invoice_number');
                        $invoice_number = ($invoice_number == '') ? 'Invoice' : $invoice_number;
                        Session::put('errorMessage', 'You can only edit the last raised invoice for this project. 
                        The last raised raised invoice contains previously billed amounts for the project. Update last raised invoice - <a href="/merchant/invoice/update/' . Encrypt::encode($req_id) . '">' . $invoice_number . "</a>");
                        return redirect('/merchant/paymentrequest/viewlist');
                    }
                }
            }

            $request->template_id = $invoice->template_id;
            $request->contract_id = $invoice->contract_id;
            $request->currency = $invoice->currency;
            $request->billing_profile_id = $invoice->billing_profile_id;
            $_POST['template_id'] = $invoice->template_id;
            $_POST['contract_id'] = $invoice->contract_id;
            $_POST['currency'] = $invoice->currency;
            $_POST['billing_profile_id'] = $invoice->billing_profile_id;
            $cycleName = $this->invoiceModel->getColumnValue('billing_cycle_detail', 'billing_cycle_id', $invoice->billing_cycle_id, 'cycle_name');
            if ($invoice->payment_request_status != 11) {
                $invoice_number = $invoice->invoice_number;
            }

            $bill_date = Helpers::htmlDate($invoice->bill_date);
            $due_date = Helpers::htmlDate($invoice->due_date);
            $narrative = $invoice->narrative;
        }
        $type = 'invoice';
        $invoice_type = 1;
        if (isset($request->invoice_type)) {
            $invoice_type = $request->invoice_type;
        }
        $req_types = array('invoice' => 1, 'estimate' => 2, 'subscription' => 4, 'construction' => 1);
        $menus = array('invoice' => 19, 'estimate' => 122, 'subscription' => 21, 'construction' => 19);
        if (!isset($req_types[$type])) {
            throw new Exception('Invalid invoice type ' . $type);
        }
        $template_type = '';
        $menu = $menus[$type];
        $title = ($update == null) ? 'Create ' . $type : 'Update ' . $type;
        $data = $this->setBladeProperties($title, ['invoiceformat', 'template', 'coveringnote', 'product', 'subscription'], [3, $menu]);
        #get merchant invoice format list
        $data['format_list'] = $this->invoiceModel->getMerchantFormatList($this->merchant_id, $type);
        if (count($data['format_list']) == 1) {
            $request->template_id = $data['format_list']->first()->template_id;
        }

        $data['billing_profile'] = $this->invoiceModel->getMerchantValues($this->merchant_id, 'merchant_billing_profile');
        $data['billing_profile_id'] = '';
        if (count($data['billing_profile']) == 1) {
            $request->billing_profile_id = $data['billing_profile']->first()->id;
            $data['billing_profile_id'] = $request->billing_profile_id;
        }
        $data['currency'] = '';
        $data['template_id'] = '';
        $data['currency_list'] = Session::get('currency');
        $data['multi_currency'] = env('ENABLE_MULTI_CURRENCY');
        $data['subscription'] = 0;
        $data['type'] = ucfirst($type);
        $data['invoice_product_taxation'] = 1;
        $data['richtext'] = true;
        if ($invoice_type == 2 || $type == 'estimate') {
            $data['invoice_type'] = 2;
        } else {
            $data['invoice_type'] = 1;
        }

        $data['request_type'] = $req_types[$type];


        $data['contract_id'] = 0;

        //contracts from privileges
        $userRole = Session::get('user_role');

        if($userRole == 'Admin') {
            $privilegesIDs = ['all' => 'full'];
        } else {
            $privilegesIDs = json_decode(Redis::get('contract_privileges_' . $this->user_id), true);
        }

        $whereContractIDs = [];
        foreach ($privilegesIDs as $key => $privilegesID) {
            if($privilegesID == 'full' || $privilegesID == 'edit' || $privilegesID == 'approve') {
                $whereContractIDs[] = $key;
            }
        }

        $data['contract'] = $this->invoiceModel->getContract($this->merchant_id, $whereContractIDs, $userRole);
        $breadcrumbs['menu'] = 'collect_payments';
        $breadcrumbs['title'] = $data['title'];
        $breadcrumbs['url'] = '/merchant/invoice/create/' . $type;

        if (env('APP_ENV') != 'LOCAL') {
            //menu list
            $mn1 = Redis::get('merchantMenuList' . $this->merchant_id);
            $item_list = json_decode($mn1, 1);
            $row_array['name'] = $data['title'];
            $row_array['link'] = '/merchant/invoice/create/' . $type;
            $item_list[] = $row_array;
            Redis::set('merchantMenuList' . $this->merchant_id, json_encode($item_list));
        }

        Session::put('breadcrumbs', $breadcrumbs);
        if (isset($request->template_id)) {
            $formatModel = new InvoiceFormat();
            $template_id = $request->template_id;
            $data['template_link'] = Encrypt::encode($template_id);
            $data['template_id'] = $template_id;
            $data['contract_id'] = isset($request->contract_id) ? $request->contract_id : 0;
            $data['type'] = ($data['contract_id'] > 0) ? 'construction' : '';
            $data = $this->setInvoiceData($data, $template_id, $request->billing_profile_id, $request->currency);

            #get pre define system column metadata
            $metarows = $formatModel->getFormatMetadata($template_id);
            $metadata = $this->setMetadata($metarows);

            $invoice_seq_id = 0;
            if (isset($data['contract_detail']->sequence_number)) {
                $invoice_seq_id = $data['contract_detail']->sequence_number;
            }

            if (isset($metadata['H'])) {
                $metadata['H'] = $this->setCreateFunction($metadata['H']);
                foreach ($metadata['H'] as $k => $row) {
                    if (isset($row->script)) {
                        $data['script'] .= $row->script;
                    }
                    if ($bill_date != '') {
                        if ($row->column_position == 5) {
                            $metadata['H'][$k]->value = $bill_date;
                        }
                        if ($row->column_position == 6) {
                            $metadata['H'][$k]->value = $due_date;
                        }
                        if ($row->column_position == 4) {
                            $metadata['H'][$k]->value = $cycleName;
                        }
                    }

                    if ($row->function_id == 9 && $row->param == 'system_generated') {
                        if ($invoice_number == '') {
                            if ($invoice_seq_id > 0) {
                                $metadata['H'][$k]->value = "System generated" . $invoice_seq_id;
                                $metadata['H'][$k]->param_value = $invoice_seq_id;
                            }
                            if ($metadata['H'][$k]->param_value > 0) {
                                $metadata['H'][$k]->display_value = $this->invoiceModel->getAutoInvoiceNo($metadata['H'][$k]->param_value);
                            }
                        } else {
                            $metadata['H'][$k]->value =  $invoice_number;
                            $metadata['H'][$k]->display_value =  $invoice_number;
                        }
                    }
                }
            }
            $template_type = $data['template_info']->template_type;
            $data['metadata'] = $metadata;
            $data['link'] = $link;

            $data['mode'] = 'create';
            $data['cycleName'] = $cycleName;
            if ($link == null) {
                $plugin = json_decode($data['template_info']->plugin, 1);
            } else {
                $plugin = json_decode($invoice->plugin_value, 1);
            }
            $data['properties'] = json_decode($data['template_info']->properties, 1);
            $data['setting'] = json_decode($data['template_info']->setting, 1);
        }

        if (isset($plugin['has_covering_note'])) {
            $data['covering_list'] = $this->invoiceModel->getMerchantValues($this->merchant_id, 'covering_note');
            $logo = $this->invoiceModel->getColumnValue('merchant_landing', 'merchant_id', $this->merchant_id, 'logo');
            if ($logo != '') {
                $logo = env('APP_URL') . '/uploads/images/landing/' . $logo;
            } else {
                $logo = env('APP_URL') . '/assets/frontend/onepage2/img/logo_scroll.png';
            }
            $data['logo'] = $logo;
        }

        if ($link != null) {
            $request_id = Encrypt::decode($link);
            if (strlen($request_id) != 10) {
                return redirect('/error/invalidlink');
            }
            $data['mandatory_files'] = [];
            if (isset($plugin['has_mandatory_upload'])) {
                if ($plugin['has_mandatory_upload'] == 1) {
                    foreach ($plugin['mandatory_data'] as $key => $mandatory_data) {
                        $data['mandatory_files' . $key] = [];
                        $mandatory_files = $this->invoiceModel->getMandatoryDocumentByPaymentRequestID($request_id, $mandatory_data['name']);
                        foreach ($mandatory_files as $files) {
                            $file_url =  $files->file_url;
                            array_push($data['mandatory_files' . $key], $file_url);
                        }
                        array_push($data['mandatory_files'], $data['mandatory_files' . $key]);
                    }
                }
            }
        }


        $data['plugin'] = $plugin;
        $data['narrative'] = $narrative;

        if ($template_type == 'construction') {
            return view('app/merchant/invoice/constructionv2', $data);
        }
        return view('app/merchant/invoice/create', $data);
    }

    /**
     * Renders form to update invoice
     *
     * @param $link - encrypted link
     *
     * @return void
     */
    public function update($link, $staging = 0, $revision = 0)
    {
        $payment_request_id = Encrypt::decode($link);
        if (strlen($payment_request_id) == 10) {
            if ($staging == 1) {
                $info = $this->invoiceModel->getStagingInvoiceInfo($payment_request_id, $this->merchant_id);
            } else {
                $info = $this->invoiceModel->getInvoiceInfo($payment_request_id, $this->merchant_id);
            }
            if ($info->message != 'success') {
                return redirect('/error')->with('errorTitle', 'Invalid URL');
            }
            if ($revision != 1) {
                $request = new Request();
                if ($info->template_type == 'construction') {
                    return $this->create($request, $link, 1);
                }
            }

            $req_types = array(1 => 'invoice', 2 => 'estimate', 4 => 'subscription');
            $type =  $req_types[$info->invoice_type];

            $menu = ($staging == 0) ? 28 : 29;
            $data = $this->setBladeProperties('Update ', ['invoiceformat', 'template', 'coveringnote', 'product', 'subscription'], [5, $menu]);
            #get merchant invoice format list
            $data['subscription'] = 0;
            $data['richtext'] = true;
            $data['type'] = ucfirst($type);
            $data['invoice_type'] = $info->invoice_type;
            $data['invoice_product_taxation'] = isset($info->invoice_product_taxation) ? $info->invoice_product_taxation : "1";
            if ($info->payment_request_type == 4) {
                $info->invoice_type = 4;
            }
            $data['request_type'] = $info->invoice_type;

            $data['payment_request_link'] = Encrypt::encode($payment_request_id);
            $data['template_link'] = Encrypt::encode($info->template_id);
            $data['payment_request_id'] = $payment_request_id;
            $data['type'] = $info->template_type;
            if ($info->template_type == 'construction') {
                $data['contract_id'] = $this->invoiceModel->getColumnValue('payment_request', 'payment_request_id', $payment_request_id, 'contract_id');
                $req_id = $this->invoiceModel->validateUpdateConstructionInvoice($data['contract_id'], $this->merchant_id);
                if ($req_id != false) {
                    if ($payment_request_id != $req_id) {
                        $invoice_number = $this->invoiceModel->getColumnValue('payment_request', 'payment_request_id', $req_id, 'invoice_number');
                        $invoice_number = ($invoice_number == '') ? 'Invoice' : $invoice_number;
                        if ($revision != 1) {
                            Session::put('errorMessage', 'You can only edit the last raised invoice for this project. 
                        The last raised raised invoice contains previously billed amounts for the project. Update last raised invoice - <a href="/merchant/invoice/update/' . Encrypt::encode($req_id) . '">' . $invoice_number . "</a>");

                            return redirect('/merchant/paymentrequest/viewlist');
                        }
                    }
                }
            }
            $data = $this->setInvoiceData($data, $info->template_id, $info->billing_profile_id, $info->currency);

            #get pre define system column metadata
            $metarows = $this->invoiceModel->getInvoiceMetadata($info->template_id, $payment_request_id, $staging);
            $metadata = $this->setMetadata($metarows);
            if (isset($metadata['H'])) {
                $metadata['H'] = $this->setUpdateFunction($metadata['H'], $info);
                foreach ($metadata['H'] as $row) {
                    if (isset($row->script)) {
                        $data['script'] .= $row->script;
                    }
                }
            }
            if ($info->payment_request_type == 4) {
                if ($staging == 1) {
                    $data['subscription'] = $this->invoiceModel->getTableRow('staging_subscription', 'payment_request_id', $payment_request_id);
                } else {
                    $data['subscription'] = $this->invoiceModel->getTableRow('subscription', 'payment_request_id', $payment_request_id);
                }
            }

            if ($info->template_type == 'franchise' || $info->template_type == 'nonbrandfranchise') {
                $data['sale_details'] = $this->invoiceModel->getTableList('invoice_food_franchise_sales', 'payment_request_id', $payment_request_id);
                $data['sale_summary'] = $this->invoiceModel->getTableRow('invoice_food_franchise_summary', 'payment_request_id', $payment_request_id);
            } elseif ($info->template_type == 'travel') {
                if ($staging == 1) {
                    $data['ticket_detail'] = $this->invoiceModel->getTableList('staging_invoice_travel_particular', 'payment_request_id', $payment_request_id);
                    $data['invoice_particular'] = $this->invoiceModel->getTableList('staging_invoice_particular', 'payment_request_id', $payment_request_id);
                } else {
                    $data['ticket_detail'] = $this->invoiceModel->getTableList('invoice_travel_particular', 'payment_request_id', $payment_request_id);
                    $data['invoice_particular'] = $this->invoiceModel->getTableList('invoice_particular', 'payment_request_id', $payment_request_id);
                }
                $data['travel_col'] = ["booking_date", "journey_date", "from_date", "to_date", "qty", "rate", "gst", "tax_amount", "discount_perc", "discount", "total_amount", "amount", "charge", "name", "type", "from", "to", "item", "product_gst"];
            } else {
                if ($staging == 1) {
                    $data['invoice_particular'] = $this->invoiceModel->getTableList('staging_invoice_particular', 'payment_request_id', $payment_request_id);
                } else {
                    $data['invoice_particular'] = $this->invoiceModel->getTableList('invoice_particular', 'payment_request_id', $payment_request_id);
                }
            }

            if ($info->template_type == 'construction') {
                $userRole = Session::get('user_role');
                if($userRole == 'Admin') {
                    $contractPrivilegesIDs = ['all' => 'full'];
                } else {
                    //get privileges from redis
                    $contractPrivilegesIDs = json_decode(Redis::get('contract_privileges_' . $this->user_id), true);
                }

                //contracts from privileges
                $whereContractIDs = [];
                foreach ($contractPrivilegesIDs as $key => $contractPrivilegesID) {
                    if($contractPrivilegesID == 'full') {
                        $whereContractIDs[] = $key;
                    }
                }

                $data['contract'] = $this->invoiceModel->getContract($this->merchant_id, $whereContractIDs, $userRole);
                $data['invoice_particular'] = $this->invoiceModel->getInvoiceConstructionParticulars($payment_request_id);
            }

            $exist_particular_ids = [];
            if (isset($data['invoice_particular'])) {
                foreach ($data['invoice_particular'] as $i => $particular) {
                    $exist_particular_ids[] = $particular->id;
                }
            }
            $data['exist_particular_id'] = json_encode($exist_particular_ids, 1);
            $data['currency_list'] = Session::get('currency');
            $data['invoice_tax'] = $this->invoiceModel->invoiceTax($payment_request_id, $staging);
            if ($info->vendor_id > 0) {
                $vendor_commission = $this->invoiceModel->getTableRow('invoice_vendor_commission', 'payment_request_id', $payment_request_id, 1);
                $data['vendor_commission'] = $vendor_commission;
            }
            $data['covering_invoice_list'] = (array) $this->invoiceModel->getCoveringNoteDetails($payment_request_id);
            $data['info'] = $info;
            $data['staging'] = $staging;
            $data['merchant_state'] = $info->merchant_state;
            $data['metadata'] = $metadata;
            $data['mode'] = 'update';
            $data['plugin'] = json_decode($info->plugin_value, 1);
            $data['setting'] = json_decode($data['template_info']->setting, 1);
            if (isset($data['template_info']->properties) && $data['template_info']->properties != '') {
                $data['properties'] = json_decode($data['template_info']->properties, 1);
            } else {
                $data['properties'] = array();
            }

            if ($revision == 1) {
                return $data;
            }

            if ($info->template_type == 'construction') {
                return view('app/merchant/invoice/construction_update', $data);
            }

            return view('app/merchant/invoice/update', $data);
        } else {
            return redirect('/error')->with('errorTitle', 'Invalid URL');
        }
    }

    public function revision($link)
    {
        //echo Encrypt::encode(15);die();
        $id = Encrypt::decode($link);
        if (strlen($id) == 10) {
            $payment_request_id = $id;
        } else {
            $row = $this->invoiceModel->getTableRow('invoice_revision', 'id', $id);
            $payment_request_id = $row->payment_request_id;
        }

        $data = $this->update(Encrypt::encode($payment_request_id), 0, 1);
        if (strlen($id) != 10) {
            $rows = $this->invoiceModel->getRevisionList($payment_request_id, $id);
            $contract_particulars = json_decode(json_encode($data['contract_particulars']), 1);
            foreach ($contract_particulars as $p) {
                $particulars[$p['id']] = $p;
            }
            foreach ($rows as $row) {
                $array = json_decode($row->json, 1);
                foreach ($array as $key => $rv) {
                    foreach ($rv as $column_name => $col_value) {
                        if ($key == 'payment_request') {
                            $data['info']->{$column_name} = $col_value['old_value'];
                        } elseif ($key == 'invoice_column_values') {
                            foreach ($data['metadata']['H'] as $kh => $mhv) {
                                $data['metadata']['H'][$kh]->value = $col_value['old_value'];
                            }
                        } elseif ($key == 'invoice_construction_particular') {
                            $col_value['old_value']['calculated_perc'] = '';
                            $col_value['old_value']['calculated_row'] = '';
                            if ($col_value['type'] == 'update' || $col_value['type'] == 'remove') {
                                $particulars[$column_name] = $col_value['old_value'];
                            } elseif ($col_value['type'] == 'add') {
                                unset($particulars[$column_name]);
                            }
                        }
                    }
                }
            }
            foreach ($data['metadata']['H'] as $kh => $mhv) {
                if ($mhv->column_name == 'Bill date') {
                    $data['metadata']['H'][$kh]->value = Helpers::htmlDate($data['info']->bill_date);
                } elseif ($mhv->column_name == 'Due date') {
                    $data['metadata']['H'][$kh]->value = Helpers::htmlDate($data['info']->due_date);
                }
            }
            ksort($particulars);
            //$data['contract_particulars'] = (object) $particulars;
            $data['contract_particulars'] = collect(json_decode(json_encode($particulars), 0))->all();
        }

        $data['title'] = 'Revision';

        return view('app/merchant/invoice/construction_revision', $data);
    }

    public function setInvoiceData($data, $template_id, $billing_profile_id, $currency)
    {
        $data['template_info'] = $this->invoiceModel->getTableRow('invoice_template', 'template_id', $template_id);
        $data['customer_auto_generate'] = $this->invoiceModel->getColumnValue('merchant_setting', 'merchant_id', $this->merchant_id, 'customer_auto_generate');
        $data['product_taxation_type'] = $this->invoiceModel->getColumnValue('merchant_setting', 'merchant_id', $this->merchant_id, 'product_taxation');

        if (isset($data['template_info']->default_tax) && $data['template_info']->default_tax != '' && $data['template_info']->default_tax != 'null') {
            $default_tax_ids = json_decode($data['template_info']->default_tax, 1);
            $default_tax_list = $this->invoiceModel->getDefaultTaxList($this->merchant_id, $default_tax_ids);
        } else {
            $default_tax_list = array();
        }

        //default tax list
        $data['default_tax_list'] = $default_tax_list;

        $product_list = $this->invoiceModel->getAllParentProducts($this->merchant_id);
        if (!empty($product_list)) {
            $products = array();
            foreach ($product_list as $pr) {
                $pr->product_name = str_replace("'", "", $pr->product_name);
                $products[$pr->product_name] = array(
                    'price' => $pr->price, 'sac_code' => $pr->sac_code,
                    'gst_percent' => $pr->gst_percent, 'unit_type' => $pr->unit_type, 'available_stock' => $pr->available_stock,
                    'enable_stock' => $pr->has_stock_keeping, 'name' => $pr->product_name,
                    'mrp' => $pr->mrp, 'product_expiry_date' => $pr->product_expiry_date, 'product_number' => $pr->product_number
                );
            }
            $data["product_json"] = json_encode($products);
            $data["product_list"] = $products;
        }

        $tax_list = $this->invoiceModel->getMerchantValues($this->merchant_id, 'merchant_tax');
        $tax_array = array();
        $tax_rate_array = array();
        if (!empty($tax_list)) {
            foreach ($tax_list as $ar) {
                $tax_array[$ar->tax_id] = array('tax_type' => $ar->tax_type, 'tax_name' => $ar->tax_name, 'percentage' => $ar->percentage, 'fix_amount' => $ar->fix_amount);
                $tax_rate_array[$ar->tax_name] = $ar->percentage;
            }
            $data["tax_array"] = json_encode($tax_array);
            $data["tax_list"] = $tax_array;
            $data["merchant_tax_list"] = $tax_list;
            $data["tax_rate_array"] = json_encode($tax_rate_array);
        }
        $plugin = array();
        if (isset($data['template_info']->plugin) && $data['template_info']->plugin != '') {
            $plugin = json_decode($data['template_info']->plugin, 1);
        }

        $data["tax_type"] = $this->invoiceModel->getConfigList('tax_type');
        $data["tax_calculated_on"] = $this->invoiceModel->getConfigList('tax_calculated_on');
        $data["state_code"] = $this->invoiceModel->getConfigList('gst_state_code');
        $data["country_code"] = $this->invoiceModel->getConfigList('country_name');
        $data["einvoice_type"] = $this->invoiceModel->getConfigList('einvoice_type');

        $data['customer_list'] = $this->invoiceModel->getMerchantValues($this->merchant_id, 'customer');

        $data['billing_profile_id'] = $billing_profile_id;
        $data['currency'] = $currency;
        $data['billing_profile'] = $this->invoiceModel->getMerchantValues($this->merchant_id, 'merchant_billing_profile');
        $data['default_profile'] = $this->invoiceModel->defaultBillingProfile($this->merchant_id);
        $data['merchant_state'] = $this->invoiceModel->getColumnValue('merchant_billing_profile', 'id', $billing_profile_id, 'state');
        $data['customer_custom_column'] = $this->invoiceModel->getMerchantValues($this->merchant_id, 'customer_column_metadata');
        if (isset($plugin['has_supplier'])) {
            $data['supplier'] = $this->invoiceModel->getMerchantValues($this->merchant_id, 'supplier');
        }
        if (isset($plugin['has_franchise'])) {
            $data['franchise_list'] = $this->invoiceModel->getMerchantValues($this->merchant_id, 'franchise');
        }
        if (isset($plugin['has_autocollect'])) {
            $data['auto_collect_list'] = $this->invoiceModel->getMerchantValues($this->merchant_id, 'autocollect_plans');
        }
        if (isset($plugin['has_vendor'])) {
            $data['vendor_list'] = $this->invoiceModel->getMerchantValues($this->merchant_id, 'vendor');
        }
        if (isset($plugin['has_covering_note'])) {
            $data['covering_list'] = $this->invoiceModel->getMerchantValues($this->merchant_id, 'covering_note');
            $logo = $this->invoiceModel->getColumnValue('merchant_landing', 'merchant_id', $this->merchant_id, 'logo');
            if ($logo != '') {
                $logo = env('APP_URL') . '/uploads/images/landing/' . $logo;
            } else {
                $logo = env('APP_URL') . '/assets/frontend/onepage2/img/logo_scroll.png';
            }
            $data['logo'] = $logo;
        }
        if (isset($plugin['has_coupon'])) {
            $data['coupon_list'] = $this->invoiceModel->getActiveCoupon($this->merchant_id);
        }
        if (isset($plugin['has_signature'])) {
            $signature = $this->invoiceModel->getMerchantData($this->merchant_id, 'DIGITAL_SIGNATURE');
            if ($signature != false) {
                $data['signature'] = json_decode($signature);
            }
        }
        if ($data['type'] == 'construction') {
            // $data['csi_code'] = $this->invoiceModel->getMerchantValues($this->merchant_id, 'csi_code');
            $userRole = Session::get('user_role');

            if($userRole == 'Admin') {
                $contractPrivilegesIDs = ['all' => 'full'];
            } else {
                //get privileges from redis
                $contractPrivilegesIDs = json_decode(Redis::get('contract_privileges_' . $this->user_id), true);
            }

            //contracts from privileges
            $whereContractIDs = [];
            foreach ($contractPrivilegesIDs as $key => $contractPrivilegesID) {
                if($contractPrivilegesID == 'full') {
                    $whereContractIDs[] = $key;
                }
            }
            $contract = $this->invoiceModel->getContractDetail($data['contract_id'], $whereContractIDs, $userRole);
            $model = new Master();
            $data['csi_code'] = $model->getProjectCodeList($this->merchant_id, $contract->id);

            $customer = $this->invoiceModel->getTableRow('customer', 'customer_id', $contract->customer_id);
            $data['customer'] = $customer;
            $data['contract_detail'] = $contract;
            $data['project_id'] = $contract->project_id;
            $data['csi_code_json'] = json_encode($data['csi_code']);

            $data['contract_particulars'] = json_decode($contract->particulars);

            if (isset($data['payment_request_id'])) {
                $data['contract_particulars'] = $this->invoiceModel->getTableList('invoice_construction_particular', 'payment_request_id', $data['payment_request_id']);
            } else {
                $pre_req_id =  $this->invoiceModel->getPreviousContractBill($this->merchant_id, $data['contract_id'], 'NA');
                $change_order_data = $this->invoiceModel->getOrderbyContract($data['contract_id'], date("Y-m-d"));
                $change_order_data = json_decode($change_order_data, true);

                $cop_particulars = [];
                foreach ($change_order_data as $co_data) {
                    foreach (json_decode($co_data["particulars"], true) as $co_par) {
                        $co_par["change_order_amount"] = (int)$co_par["change_order_amount"];
                        array_push($cop_particulars, $co_par);
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
                            $co_particulars[] = array('bill_code' => $key, 'change_order_amount' => array_sum($value), 'description' =>  $kdata["description"]);
                        }
                    }
                }
                if ($pre_req_id != false) {
                    $contract_particulars = $this->invoiceModel->getTableList('invoice_construction_particular', 'payment_request_id', $pre_req_id);
                    $cp = array();
                    foreach ($contract_particulars as $row) {
                        $cp[$row->bill_code] = $row;
                    }

                    foreach ($data['contract_particulars'] as $k => $v) {
                        if (isset($cp[$v->bill_code])) {
                            $data['contract_particulars'][$k]->previously_billed_percent = $cp[$v->bill_code]->current_billed_percent;
                            $data['contract_particulars'][$k]->previously_billed_amount = $cp[$v->bill_code]->current_billed_amount;
                            $data['contract_particulars'][$k]->retainage_amount_previously_withheld = $cp[$v->bill_code]->retainage_amount_for_this_draw;
                        }
                    }
                }

                if ($change_order_data != false) {
                    $cop = array();
                    foreach ($data['contract_particulars'] as $row2) {
                        if (isset($cop[$row2->bill_code])) {
                            $cop[$row2->bill_code . rand()] = $row2;
                        } else {
                            $cop[$row2->bill_code] = $row2;
                        }
                    }

                    foreach ($co_particulars as $k => $v) {
                        if (isset($cop[$v["bill_code"]])) {
                            $cop[$v["bill_code"]]->approved_change_order_amount = $v["change_order_amount"];
                        } else {
                            $cop[$v["bill_code"]] = (object)[];
                            $cop[$v["bill_code"]]->approved_change_order_amount = $v["change_order_amount"];
                            $cop[$v["bill_code"]]->bill_code = $v["bill_code"];
                            $cop[$v["bill_code"]]->bill_type = '';
                            $cop[$v["bill_code"]]->description = $v["description"];
                            $cop[$v["bill_code"]]->calculated_perc = '';
                            $cop[$v["bill_code"]]->calculated_row  = '';
                        }
                    }
                    $data['contract_particulars'] = (object)$cop;
                }
            }
            $groups = [];

            foreach ($data['contract_particulars'] as $cp) {
                if (isset($cp->group)) {
                    if (!in_array($cp->group, $groups)) {
                        $groups[] = $cp->group;
                    }
                }
            }
            $data['group'] = json_encode($groups);
        }
        $product = new ProductController();
        $getData = $product->getCommonData();
        $data['productCategories'] = $getData['productCategories'];
        $data['gstTax'] = $getData['gstTax'];
        $data['getVendors'] = $getData['getVendors'];
        $data['getUnitTypes'] = $getData['getUnitTypes'];
        $data['enable_inventory'] = $product->checkInventoryServiceEnable();
        $data['service_id'] = $this->inventory_service_id;
        return $data;
    }

    public function estimateSubscription(Request $request)
    {

        return $this->create($request, 'subscription', 2);
    }
    public function view_g702($link)
    {
        $payment_request_id = Encrypt::decode($link);

        if (strlen($payment_request_id) == 10) {
            $data = Helpers::setBladeProperties('Invoice', ['expense', 'contract', 'product', 'template', 'invoiceformat'], [5, 28]);
            #get default billing profile

            $info =  $this->invoiceModel->getInvoiceInfo($payment_request_id, $this->merchant_id);

            $userRole = Session::get('user_role');
            $contractPrivilegesAccessIDs = json_decode(Redis::get('contract_privileges_' . $this->user_id), true);
            $invoicePrivilegesAccessIDs = json_decode(Redis::get('invoice_privileges_' . $this->user_id), true);

            $hasAccess = false;
            if($userRole == 'Admin') {
                $hasAccess = true;
            } else {
                if(in_array($info->payment_request_id, array_keys($invoicePrivilegesAccessIDs))) {
                    $hasAccess = true;
                }
                if(in_array($info->contract_id, array_keys($contractPrivilegesAccessIDs))) {
                    $hasAccess = true;
                }
            }

            if(!$hasAccess) {
                return redirect('/merchant/no-permission');
            }

            $info = (array)$info;
            if (!isset($info['payment_request_status'])) {
                return redirect('/error/invalidlink');
            }
            $info['gtype'] = '702';

            $offlineResponse = $this->invoiceModel->getPaymentRequestOfflineResponse($payment_request_id, $this->merchant_id);

            if (!empty($offlineResponse)) {
                $info['offline_response_id'] = Encrypt::encode($offlineResponse->offline_response_id) ?? '';
            }

            if ($info['payment_request_status'] == '2') {
                $info['offline_success_transaction'] = $offlineResponse;
            }


            //end code for new design
            $banklist = $this->parentModel->getConfigList('Bank_name');
            $banklist = json_decode($banklist, 1);

            $imgpath = '';
            if (isset($info['image_path'])) {
                $imgpath = env('APP_URL') . '/uploads/images/logos/' . $info['image_path'];
                if ($info['image_path'] != '') {
                    $info['image_path'] =  $imgpath;
                }
            }
            if (Session::get('success_array')) {
                $whatsapp_share = $this->getWhatsapptext($info);
                $success_array = Session::get('success_array');
                $active_payment = Session::get('has_payment_active');
                Session::remove('success_array');
                $info["invoice_success"] = true;

                $info["whatsapp_share"] = $whatsapp_share;
                foreach ($success_array as $key => $val) {
                    $info[$key] = $val;
                }
                if (Session::get('has_payment_active') == false) {
                    Session::put('has_payment_active', $this->invoiceModel->isPaymentActive($this->merchant_id));
                }
                if ($success_array['type'] == 'insert' && $active_payment == false) {
                    $info["payment_gateway_info"] = true;
                }
            }
            $data = $this->setdataV2($data, $info, $banklist, $payment_request_id);
            return view('app/merchant/invoice/view/invoice_view_g702', $data);
        } else {
        }
    }

    public function view_g703($link, Request $request)
    {
        $payment_request_id = Encrypt::decode($link);
        $notificationID = $request->get('notification_id');

        if (strlen($payment_request_id) == 10) {
            $data = Helpers::setBladeProperties('Invoice', ['expense', 'contract', 'product', 'template', 'invoiceformat'], [5, 28]);
            #get default billing profile

            $info =  $this->invoiceModel->getInvoiceInfo($payment_request_id, $this->merchant_id);

            $userRole = Session::get('user_role');
            $contractPrivilegesAccessIDs = json_decode(Redis::get('contract_privileges_' . $this->user_id), true);
            $invoicePrivilegesAccessIDs = json_decode(Redis::get('invoice_privileges_' . $this->user_id), true);

            $hasAccess = false;
            if($userRole == 'Admin') {
                $hasAccess = true;
            } else {
                if(in_array($info->payment_request_id, array_keys($invoicePrivilegesAccessIDs))) {
                    $hasAccess = true;
                }
                if(in_array($info->contract_id, array_keys($contractPrivilegesAccessIDs))) {
                    $hasAccess = true;
                }
            }

            if(!$hasAccess) {
                return redirect('/merchant/no-permission');
            }

            if(!empty($notificationID)) {
                /** @var Notification $Notification */
                $Notification = Notification::findOrFail($notificationID);

                $Notification->markAsRead();
            }

            $info = (array)$info;
            $info['gtype'] = '703';

            $offlineResponse = $this->invoiceModel->getPaymentRequestOfflineResponse($payment_request_id, $this->merchant_id);
            if (!empty($offlineResponse)) {
                $info['offline_response_id'] = Encrypt::encode($offlineResponse->offline_response_id) ?? '';
            }

            if (!isset($info['payment_request_status'])) {
                return redirect('/error/invalidlink');
            }
            if ($info['payment_request_status'] == '2') {
                $info['offline_success_transaction'] = $offlineResponse;
            }

            //end code for new design
            $banklist = $this->parentModel->getConfigList('Bank_name');
            $banklist = json_decode($banklist, 1);

            $imgpath = env('APP_URL') . '/uploads/images/logos/' . $info['image_path'] ?? '';

            if (isset($info['image_path'])) {
                if ($info['image_path'] != '') {
                    $info['image_path'] =  $imgpath;
                }
            }
            if (Session::get('success_array')) {
                $whatsapp_share = $this->getWhatsapptext($info);
                $success_array = Session::get('success_array');
                $active_payment = Session::get('has_payment_active');
                Session::remove('success_array');
                $info["invoice_success"] = true;

                $info["whatsapp_share"] = $whatsapp_share;
                foreach ($success_array as $key => $val) {
                    $info[$key] = $val;
                }
                if (Session::get('has_payment_active') == false) {
                    Session::put('has_payment_active', $this->invoiceModel->isPaymentActive($this->merchant_id));
                }
                if ($success_array['type'] == 'insert' && $active_payment == false) {
                    $info["payment_gateway_info"] = true;
                }
            }

            $data = $this->setdata($data, $info, $banklist, $payment_request_id);
            return view('app/merchant/invoice/view/invoice_view_g703', $data);
        } else {
        }
    }

    public function view_g703_v2($link)
    {
        $payment_request_id = Encrypt::decode($link);

        if (strlen($payment_request_id) == 10) {
            $data = Helpers::setBladeProperties('Invoice', ['expense', 'contract', 'product', 'template', 'invoiceformat'], [5, 28]);
            #get default billing profile

            $info =  $this->invoiceModel->getInvoiceInfo($payment_request_id, $this->merchant_id);

            $info = (array)$info;
            $info['gtype'] = '703';

            $offlineResponse = $this->invoiceModel->getPaymentRequestOfflineResponse($payment_request_id, $this->merchant_id);

            if (!empty($offlineResponse)) {
                $info['offline_response_id'] = Encrypt::encode($offlineResponse->offline_response_id) ?? '';
            }
            if (!isset($info['payment_request_status'])) {
                return redirect('/error/invalidlink');
            }
            if ($info['payment_request_status'] == '2') {
                $info['offline_success_transaction'] = $offlineResponse;
            }

            //end code for new design
            $banklist = $this->parentModel->getConfigList('Bank_name');
            $banklist = json_decode($banklist, 1);

            $imgpath = env('APP_URL') . '/uploads/images/logos/' . $info['image_path'] ?? '';

            if (isset($info['image_path'])) {
                if ($info['image_path'] != '') {
                    $info['image_path'] =  $imgpath;
                }
            }
            if (Session::get('success_array')) {
                $whatsapp_share = $this->getWhatsapptext($info);
                $success_array = Session::get('success_array');
                $active_payment = Session::get('has_payment_active');
                Session::remove('success_array');
                $info["invoice_success"] = true;

                $info["whatsapp_share"] = $whatsapp_share;
                foreach ($success_array as $key => $val) {
                    $info[$key] = $val;
                }
                if (Session::get('has_payment_active') == false) {
                    Session::put('has_payment_active', $this->invoiceModel->isPaymentActive($this->merchant_id));
                }
                if ($success_array['type'] == 'insert' && $active_payment == false) {
                    $info["payment_gateway_info"] = true;
                }
            }

            $data = $this->setdataV2($data, $info, $banklist, $payment_request_id);
            return view('app/merchant/invoice/view/invoice_view_g703', $data);
        } else {
        }
    }

    public function documents($link, $parentnm = '', $sub = '', $docpath = '')
    {
        $payment_request_id = Encrypt::decode($link);

        if (strlen($payment_request_id) == 10) {
            $data = Helpers::setBladeProperties('Invoice', [], [5, 28]);
            #get default billing profile

            $info =  $this->invoiceModel->getInvoiceInfo($payment_request_id, 'customer');

            $userRole = Session::get('user_role');
            $contractPrivilegesAccessIDs = json_decode(Redis::get('contract_privileges_' . $this->user_id), true);
            $invoicePrivilegesAccessIDs = json_decode(Redis::get('invoice_privileges_' . $this->user_id), true);

            $hasAccess = false;
            if($userRole == 'Admin') {
                $hasAccess = true;
            } else {
                if(in_array($info->payment_request_id, array_keys($invoicePrivilegesAccessIDs))) {
                    $hasAccess = true;
                }
                if(in_array($info->contract_id, array_keys($contractPrivilegesAccessIDs))) {
                    $hasAccess = true;
                }
            }

            if(!$hasAccess) {
                return redirect('/merchant/no-permission');
            }

            $plugin_value =  $this->invoiceModel->getColumnValue('payment_request', 'payment_request_id', $payment_request_id, 'plugin_value');

            $banklist = $this->parentModel->getConfigList('Bank_name');
            $banklist = json_decode($banklist, 1);

            $info = (array)$info;
            $info['its_from'] = 'real';
            $info['gtype'] = 'attachment';

            $offlineResponse = $this->invoiceModel->getPaymentRequestOfflineResponse($payment_request_id, $this->merchant_id);

            if (!empty($offlineResponse)) {
                $info['offline_response_id'] = Encrypt::encode($offlineResponse->offline_response_id) ?? '';
            }

            if ($info['payment_request_status'] == '2') {
                $info['offline_success_transaction'] = $offlineResponse;
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
            $data = $this->setdata($data, $info, $banklist, $payment_request_id);

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

    public function documentsPatron($link, $parentnm = '', $sub = '', $docpath = '')
    {

        $payment_request_id = Encrypt::decode($link);

        if (strlen($payment_request_id) == 10) {
            $data = Helpers::setBladeProperties('Invoice', [], [5, 28]);

            #get default billing profile
            $info =  $this->invoiceModel->getInvoiceInfo($payment_request_id, 'customer');
            $plugin_value =  $this->invoiceModel->getColumnValue('payment_request', 'payment_request_id', $payment_request_id, 'plugin_value');

            $banklist = $this->parentModel->getConfigList('Bank_name');
            $banklist = json_decode($banklist, 1);

            $info = (array)$info;
            $info['its_from'] = 'real';
            $info['gtype'] = '703';
            $plugin = json_decode($info['plugin_value'], 1);
            if (isset($plugin['has_coupon']) && $plugin['has_coupon'] == 1) {
                $is_coupon = $this->invoiceModel->getActiveCoupon($info['merchant_user_id']);
                if ($is_coupon)
                    $info["is_coupon"] = true;
                else
                    $info["is_coupon"] = false;
            }

            if ($info['payment_request_status'] == 6) {
                $invoice_link = Encrypt::encode($info['converted_request_id']);
                $info["invoice_link"] = $invoice_link;
            }

            if (isset($plugin['has_autocollect']) && $plugin['has_autocollect'] == 1) {
                $parent_request_id = $this->parentModel->getColumnValue('payment_request', 'payment_request_id', $info['payment_request_id'], 'parent_request_id');
                $subscription_id = $this->parentModel->getColumnValue('subscription', 'payment_request_id', $parent_request_id, 'subscription_id');
                $subscription_id = $this->parentModel->getColumnValueExtraWhere('autocollect_subscriptions', 'invoice_subscription_id', $subscription_id, 'subscription_id', 1, '1');

                if ($subscription_id != false) {
                    $plugin['has_autocollect'] = 0;
                    $plugin['autocollect_plan_id'] = 0;
                }
            }

            $fee_id = $this->invoiceModel->getmerchantfeeID($info['merchant_id']);
            if ($fee_id != false) {
                $info['fee_id'] = $fee_id;
            } else {
                $info['fee_id'] = '';
            }

            $info['plugin_value'] = json_encode($plugin);

            if ($info['customer_user_id'] != '') {
                Session::put('patron_type', 1);
                $info['patron_type'] = 1;
            } else {
                Session::put('patron_type', 2);
                $info['patron_type'] = 2;
            }


            if ($info['customer_user_id'] != $this->user_id) {
                $info['diff_login'] = '1';
            }

            $is_online_payment = ($info['merchant_type'] == 2 && $info['legal_complete'] == 1) ? 1 : 0;
            $info["is_online_payment"] = $is_online_payment;
            $paidMerchant_request = ($is_online_payment == 1) ? TRUE : FALSE;
            Session::put('paidMerchant_request', $paidMerchant_request);



            $plugin_array = json_decode($plugin_value, 1);
            if (!empty($plugin_array['files'])) {
                $data['files'] = $plugin_array['files'];
            }

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


                    $menus1['id'] = str_replace(' ', '_', substr(substr(basename($files), 0, strrpos(basename($files), '.')), -10));
                    $menus1['full'] = basename($files);
                    $nm = substr(substr(substr(basename($files), 0, strrpos(basename($files), '.')), 0, -4), 0, 10);
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
            $constriuction_details = $this->parentModel->getTableList('invoice_construction_particular', 'payment_request_id', $payment_request_id);
            $tt = json_decode($constriuction_details, 1);
            $data = $this->getDataBillCodeAttachment($tt, $doclist, $data);
            $selectedDoc = array();
            $selectnm = '';
            if (!empty($parentnm)) {
                //  $docpath = '';
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
            $data = $this->setdata($data, $info, $banklist, $payment_request_id, 'Invoice', 'patron');

            return view('app/merchant/invoice/documents', $data);
        } else {
        }
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

        return  redirect(Storage::disk('s3_expense')->temporaryUrl(
            $filePath,
            now()->addHour(),
            ['ResponseContentDisposition' => 'attachment']
        ));
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

                    $source_path = 'invoices/' . basename($file_name);
                    $file_content = Storage::disk($source_disk)->get($source_path);
                    $zip->put(basename($file_name), $file_content);
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

            return redirect('tmp/documents.zip');
        }
    }

    public function view($link)
    {


        $payment_request_id = Encrypt::decode($link);

        if (strlen($payment_request_id) == 10) {
            $data = $this->setBladeProperties('Invoice view', [], [3]);

            #get default billing profile
            $info =  $this->invoiceModel->getInvoiceInfo($payment_request_id, $this->merchant_id);
            $info = (array)$info;
            //if new design is empty  then send user to old merchnat paymentrequest link here
            if (empty($info['design_name']) || is_null($info['design_name'])) {
                header('Location: /merchant/paymentrequest/view/' . $link);
                die();
            }
            //end code for new design
            $banklist = $this->parentModel->getConfigList('Bank_name');
            $banklist = json_decode($banklist, 1);
            $imgpath = env('APP_URL') . '/uploads/images/logos/' . $info['image_path'];

            if (isset($info['image_path'])) {
                if ($info['image_path'] != '') {
                    $info['image_path'] =  $imgpath;
                }
            }
            if (Session::get('success_array')) {
                $whatsapp_share = $this->getWhatsapptext($info);
                $success_array = Session::get('success_array');
                $active_payment = Session::get('has_payment_active');
                Session::remove('success_array');
                $info["invoice_success"] = true;

                $info["whatsapp_share"] = $whatsapp_share;
                foreach ($success_array as $key => $val) {
                    $info[$key] = $val;
                }
                if (Session::get('has_payment_active') == false) {
                    Session::put('has_payment_active', $this->invoiceModel->isPaymentActive($this->merchant_id));
                }
                if ($success_array['type'] == 'insert' && $active_payment == false) {
                    $info["payment_gateway_info"] = true;
                }
            }
            $data = $this->setdata($data, $info, $banklist, $payment_request_id);

            return view('app/merchant/invoice/view/invoice_view', $data);
        } else {
        }
    }

    public function patronView($link)
    {


        $payment_request_id = Encrypt::decode($link);

        if (strlen($payment_request_id) == 10) {
            $data = $this->setBladeProperties('Invoice view', [], [3]);

            #get default billing profile
            $info =  $this->invoiceModel->getInvoiceInfo($payment_request_id, 'customer');
            $info = (array)$info;
            //if new design empty then added patron link here
            if (empty($info['design_name']) || is_null($info['design_name'])) {
                header('Location: /patron/paymentrequest/view/' . $link);
                die();
            }
            $banklist = $this->parentModel->getConfigList('Bank_name');
            $banklist = json_decode($banklist, 1);
            $plugin = json_decode($info['plugin_value'], 1);
            if (isset($plugin['has_coupon']) && $plugin['has_coupon'] == 1) {
                $is_coupon = $this->invoiceModel->getActiveCoupon($info['merchant_user_id']);
                if ($is_coupon)
                    $info["is_coupon"] = true;
                else
                    $info["is_coupon"] = false;
            }

            $imgpath = env('APP_URL') . '/uploads/images/logos/' . $info['image_path'];

            if (isset($info['image_path'])) {
                if ($info['image_path'] != '') {
                    $info['image_path'] =  $imgpath;
                }
            }

            if ($info['payment_request_status'] == 6) {
                $invoice_link = Encrypt::encode($info['converted_request_id']);
                $info["invoice_link"] = $invoice_link;
            }

            if (isset($plugin['has_autocollect']) && $plugin['has_autocollect'] == 1) {
                $parent_request_id = $this->parentModel->getColumnValue('payment_request', 'payment_request_id', $info['payment_request_id'], 'parent_request_id');
                $subscription_id = $this->parentModel->getColumnValue('subscription', 'payment_request_id', $parent_request_id, 'subscription_id');
                $subscription_id = $this->parentModel->getColumnValueExtraWhere('autocollect_subscriptions', 'invoice_subscription_id', $subscription_id, 'subscription_id', 1, '1');

                if ($subscription_id != false) {
                    $plugin['has_autocollect'] = 0;
                    $plugin['autocollect_plan_id'] = 0;
                }
            }

            $fee_id = $this->invoiceModel->getmerchantfeeID($info['merchant_id']);
            if ($fee_id != false) {
                $info['fee_id'] = $fee_id;
            } else {
                $info['fee_id'] = '';
            }

            $info['plugin_value'] = json_encode($plugin);

            if ($info['customer_user_id'] != '') {
                Session::put('patron_type', 1);
                $info['patron_type'] = 1;
            } else {
                Session::put('patron_type', 2);
                $info['patron_type'] = 2;
            }


            if ($info['customer_user_id'] != $this->user_id) {
                $info['diff_login'] = '1';
            }

            $is_online_payment = ($info['merchant_type'] == 2 && $info['legal_complete'] == 1) ? 1 : 0;
            $info["is_online_payment"] = $is_online_payment;
            $paidMerchant_request = ($is_online_payment == 1) ? TRUE : FALSE;
            Session::put('paidMerchant_request', $paidMerchant_request);
            $data = $this->setdata($data, $info, $banklist, $payment_request_id, 'Invoice', 'patron');


            return view('app/merchant/invoice/view/invoice_view', $data);
        } else {
        }
    }
    public function patronView703($link, $type)
    {

        $payment_request_id = Encrypt::decode($link);

        if (strlen($payment_request_id) == 10) {
            $data = $this->setBladeProperties('Invoice view', [], [3]);
            #get default billing profile
            $info =  $this->invoiceModel->getInvoiceInfo($payment_request_id, 'customer');
            $info = (array)$info;
            $info['gtype'] = $type;
            //if new design empty then added patron link here
            // if (empty($info['design_name']) || is_null($info['design_name'])) {
            //     header('Location: /patron/paymentrequest/view/' . $link);
            //     die();
            // }

            $banklist = $this->parentModel->getConfigList('Bank_name');
            $banklist = json_decode($banklist, 1);
            $plugin = json_decode($info['plugin_value'], 1);
            if (isset($plugin['has_coupon']) && $plugin['has_coupon'] == 1) {
                $is_coupon = $this->invoiceModel->getActiveCoupon($info['merchant_user_id']);
                if ($is_coupon)
                    $info["is_coupon"] = true;
                else
                    $info["is_coupon"] = false;
            }

            $imgpath = env('APP_URL') . '/uploads/images/logos/' . $info['image_path'];

            if (isset($info['image_path'])) {
                if ($info['image_path'] != '') {
                    $info['image_path'] =  $imgpath;
                }
            }

            if ($info['payment_request_status'] == 6) {
                $invoice_link = Encrypt::encode($info['converted_request_id']);
                $info["invoice_link"] = $invoice_link;
            }

            if (isset($plugin['has_autocollect']) && $plugin['has_autocollect'] == 1) {
                $parent_request_id = $this->parentModel->getColumnValue('payment_request', 'payment_request_id', $info['payment_request_id'], 'parent_request_id');
                $subscription_id = $this->parentModel->getColumnValue('subscription', 'payment_request_id', $parent_request_id, 'subscription_id');
                $subscription_id = $this->parentModel->getColumnValueExtraWhere('autocollect_subscriptions', 'invoice_subscription_id', $subscription_id, 'subscription_id', 1, '1');

                if ($subscription_id != false) {
                    $plugin['has_autocollect'] = 0;
                    $plugin['autocollect_plan_id'] = 0;
                }
            }

            $fee_id = $this->invoiceModel->getmerchantfeeID($info['merchant_id']);
            if ($fee_id != false) {
                $info['fee_id'] = $fee_id;
            } else {
                $info['fee_id'] = '';
            }

            $info['plugin_value'] = json_encode($plugin);

            if ($info['customer_user_id'] != '') {
                Session::put('patron_type', 1);
                $info['patron_type'] = 1;
            } else {
                Session::put('patron_type', 2);
                $info['patron_type'] = 2;
            }


            if ($info['customer_user_id'] != $this->user_id) {
                $info['diff_login'] = '1';
            }

            $is_online_payment = ($info['merchant_type'] == 2 && $info['legal_complete'] == 1) ? 1 : 0;
            $info["is_online_payment"] = $is_online_payment;
            $paidMerchant_request = ($is_online_payment == 1) ? TRUE : FALSE;
            Session::put('paidMerchant_request', $paidMerchant_request);
            $data = $this->setdataV2($data, $info, $banklist, $payment_request_id, 'Invoice', 'patron');

            return view('app/merchant/invoice/view/invoice_view_g' . $type, $data);
        } else {
        }
    }

    public function bulkview($link)
    {

        $payment_request_id = Encrypt::decode($link);

        if (strlen($payment_request_id) == 10) {
            $data = $this->setBladeProperties('Invoice view', [], [3]);
            #get default billing profile
            $info = $this->invoiceModel->getStagingInvoiceInfo($payment_request_id, $this->merchant_id);
            $info = (array)$info;
            $imgpath = env('APP_URL') . '/uploads/images/logos/' . $info['image_path'];

            if (isset($info['image_path'])) {
                if ($info['image_path'] != '') {
                    $info['image_path'] =  $imgpath;
                }
            }
            $data = $this->setdata($data, $info, [], $payment_request_id, 'Invoice', 'merchant', 1);
            return view('app/merchant/invoice/view/invoice_view', $data);
        } else {
        }
    }

    public function download($link, $savepdf = 0, $type = null)
    {
        ini_set('max_execution_time', 120);
        $payment_request_id = Encrypt::decode($link);

        if (strlen($payment_request_id) == 10) {
            $data = $this->setBladeProperties('Invoice view', [], [3]);

            #get default billing profile
            $info =  $this->invoiceModel->getInvoiceInfo($payment_request_id, $this->merchant_id);
            $info = (array)$info;
            $banklist = $this->parentModel->getConfigList('Bank_name');
            $banklist = json_decode($banklist, 1);
            $info['logo'] = '';

            if (isset($info['image_path'])) {
                $imgpath = env('APP_URL') . '/uploads/images/logos/' . $info['image_path'];
                if ($info['image_path'] != '') {
                    try {
                        $info['logo'] = base64_encode(file_get_contents($imgpath));
                    } catch (Exception $o) {
                    }
                }
            } else {
                $info['image_path'] = '';
            }

            if ($type === '703' || $type === '702') {
                $imgpath = env('APP_URL') . '/images/logo-703.PNG';
                try {
                    $arrContextOptions = [
                        "ssl" => [
                            "verify_peer" => false,
                            "verify_peer_name" => false,
                            "allow_self_signed" => true,
                        ]
                    ];

                    $info['logo'] = base64_encode(file_get_contents($imgpath, false, stream_context_create($arrContextOptions)));
                } catch (Exception $o) {
                }
            }
            $info['signimg'] = '';
            if (isset($info['signature']['signature_file'])) {
                $imgpath = env('APP_URL') . '/uploads/images/landing/' . $info['signature']['signature_file'];
                if ($info['signature']['signature_file'] != '') {
                    $info['signimg'] = base64_encode(file_get_contents($imgpath));
                }
            }

            $data = $this->setdata($data, $info, $banklist, $payment_request_id);


            // if ($savepdf == 2) {
            //    $data['viewtype'] = 'print';
            //   if ($info['template_type'] == 'construction') {

            //     return view('mailer/invoice/format-' . $type, $data);
            //  } else {
            //       return view('mailer/invoice/' . $info['design_name'], $data);
            //  }
            //   die();
            //  } else

            if ($savepdf == 1) {

                $data['viewtype'] = 'pdf';
                define("DOMPDF_ENABLE_HTML5PARSER", true);
                define("DOMPDF_ENABLE_FONTSUBSETTING", true);
                define("DOMPDF_UNICODE_ENABLED", true);
                define("DOMPDF_DPI", 120);
                define("DOMPDF_ENABLE_REMOTE", true);
                $name = $info['customer_name'] . '_' . date('Y-M-d H:m:s');
                $name = str_replace('-', '', $name);
                $name = str_replace(':', '', $name);
                if ($info['template_type'] == 'construction') {

                    $pdf = DOMPDF::loadView('mailer.invoice.format-702', $data);
                    $pdf->setPaper("a4", "landscape");
                    $pdf->save(storage_path('pdf\\702' . $name . '.pdf'));
                    $pdf = DOMPDF::loadView('mailer.invoice.format-703', $data);
                    $pdf->setPaper("a4", "landscape");
                    $pdf->save(storage_path('pdf\\703' . $name . '.pdf'));
                }




                return $name;
            } else {
                $data['viewtype'] = 'pdf';
                define("DOMPDF_ENABLE_HTML5PARSER", true);
                define("DOMPDF_ENABLE_FONTSUBSETTING", true);
                define("DOMPDF_UNICODE_ENABLED", true);
                define("DOMPDF_DPI", 120);
                define("DOMPDF_ENABLE_REMOTE", true);
                if ($info['template_type'] == 'construction') {
                    $pdf = DOMPDF::loadView('mailer.invoice.format-' . $type, $data);
                    $pdf->setPaper("a4", "landscape");
                } else {
                    $pdf = DOMPDF::loadView('mailer.invoice.' . $info['design_name'], $data);
                    $pdf->setPaper("a4", "portrait");
                }


                $name = $info['customer_name'] . '_' . date('Y-M-d H:m:s');

                if ($savepdf == 2) {
                    return  $pdf->stream();
                } else {
                    return $pdf->download($name . '.pdf');
                }
            }
        } else {
        }
    }

    public function downloadV2($link, $savepdf = 0, $type = null)
    {
        
        ini_set('max_execution_time', 120);
        $payment_request_id = Encrypt::decode($link);

        if (strlen($payment_request_id) == 10) {
            $data = $this->setBladeProperties('Invoice view', [], [3]);

            #get default billing profile
            $info =  $this->invoiceModel->getInvoiceInfo($payment_request_id, $this->merchant_id);
            $info = (array)$info;
            $banklist = $this->parentModel->getConfigList('Bank_name');
            $banklist = json_decode($banklist, 1);
            $info['logo'] = '';

            if (isset($info['image_path'])) {
                $imgpath = env('APP_URL') . '/uploads/images/logos/' . $info['image_path'];
                if ($info['image_path'] != '') {
                    try {
                        $info['logo'] = base64_encode(file_get_contents($imgpath));
                    } catch (Exception $o) {
                    }
                }
            } else {
                $info['image_path'] = '';
            }

            if ($type === '703' || $type === '702') {
                $imgpath = env('APP_URL') . '/images/logo-703.PNG';
                try {
                    $arrContextOptions = [
                        "ssl" => [
                            "verify_peer" => false,
                            "verify_peer_name" => false,
                            "allow_self_signed" => true,
                        ]
                    ];

                    $info['logo'] = base64_encode(file_get_contents($imgpath, false, stream_context_create($arrContextOptions)));
                } catch (Exception $o) {
                }
            }
            $info['signimg'] = '';
            if (isset($info['signature']['signature_file'])) {
                $imgpath = env('APP_URL') . '/uploads/images/landing/' . $info['signature']['signature_file'];
                if ($info['signature']['signature_file'] != '') {
                    $info['signimg'] = base64_encode(file_get_contents($imgpath));
                }
            }

            $data = $this->setdataV2($data, $info, $banklist, $payment_request_id);
            if ($savepdf == 2) {
                $data['viewtype'] = 'print';
                if ($info['template_type'] == 'construction') {

                    return view('mailer/invoice/format-' . $type, $data);
                } else {
                    return view('mailer/invoice/' . $info['design_name'], $data);
                }
                die();
            } else if ($savepdf == 1) {

                $data['viewtype'] = 'pdf';
                define("DOMPDF_ENABLE_HTML5PARSER", true);
                define("DOMPDF_ENABLE_FONTSUBSETTING", true);
                define("DOMPDF_UNICODE_ENABLED", true);
                define("DOMPDF_DPI", 120);
                define("DOMPDF_ENABLE_REMOTE", true);
                $name = $info['customer_name'] . '_' . date('Y-M-d H:m:s');
                $name = str_replace('-', '', $name);
                $name = str_replace(':', '', $name);
                if ($info['template_type'] == 'construction') {

                    $pdf = DOMPDF::loadView('mailer.invoice.format-702', $data);
                    $pdf->setPaper("a4", "landscape");
                    $pdf->save(storage_path('pdf\\702' . $name . '.pdf'));
                    $pdf = DOMPDF::loadView('mailer.invoice.format-703', $data);
                    $pdf->setPaper("a4", "landscape");
                    $pdf->save(storage_path('pdf\\703' . $name . '.pdf'));
                }

                return $name;
            } else {
                $data['viewtype'] = 'pdf';
                define("DOMPDF_ENABLE_HTML5PARSER", true);
                define("DOMPDF_ENABLE_FONTSUBSETTING", true);
                define("DOMPDF_UNICODE_ENABLED", true);
                define("DOMPDF_DPI", 120);
                define("DOMPDF_ENABLE_REMOTE", true);
                if ($info['template_type'] == 'construction') {
                    $pdf = DOMPDF::loadView('mailer.invoice.format-' . $type, $data);
                    $pdf->setPaper("a4", "landscape");
                } else {
                    $pdf = DOMPDF::loadView('mailer.invoice.' . $info['design_name'], $data);
                    $pdf->setPaper("a4", "portrait");
                }


                $name = $info['customer_name'] . '_' . date('Y-M-d H:m:s');

                // return  $pdf->stream();
                return $pdf->download($name . '.pdf');
            }
        } else {
        }
    }

    public function downloadPatron($link, $savepdf = 0, $type = null)
    {

        $payment_request_id = Encrypt::decode($link);

        if (strlen($payment_request_id) == 10) {
            $data = $this->setBladeProperties('Invoice view', [], [3]);

            #get default billing profile
            $info =  $this->invoiceModel->getInvoiceInfo($payment_request_id, 'customer');
            $info = (array)$info;
            $banklist = $this->parentModel->getConfigList('Bank_name');
            $banklist = json_decode($banklist, 1);
            $imgpath = env('APP_URL') . '/uploads/images/logos/' . $info['image_path'];
            $info['logo'] = '';
            if (isset($info['image_path'])) {
                if ($info['image_path'] != '') {
                    try {
                        $info['logo'] = base64_encode(file_get_contents($imgpath));
                    } catch (Exception $o) {
                    }
                }
            }
            if ($type == '703' || $type == '702') {
                $imgpath = env('APP_URL') . '/images/logo-703.PNG';
                try {
                    $info['logo'] = base64_encode(file_get_contents($imgpath));
                } catch (Exception $o) {
                }
            }
            $info['signimg'] = '';
            if (isset($info['signature']['signature_file'])) {
                $imgpath = env('APP_URL') . '/uploads/images/landing/' . $info['signature']['signature_file'];
                if ($info['signature']['signature_file'] != '') {
                    $info['signimg'] = base64_encode(file_get_contents($imgpath));
                }
            }
            $plugin = json_decode($info['plugin_value'], 1);

            if (isset($plugin['has_coupon']) && $plugin['has_coupon'] == 1) {
                $is_coupon = $this->invoiceModel->getActiveCoupon($info['merchant_user_id']);
                if ($is_coupon)
                    $info["is_coupon"] = true;
                else
                    $info["is_coupon"] = false;
            }


            if ($info['payment_request_status'] == 6) {
                $invoice_link = Encrypt::encode($info['converted_request_id']);
                $info["invoice_link"] = $invoice_link;
            }

            if (isset($plugin['has_autocollect']) && $plugin['has_autocollect'] == 1) {

                $parent_request_id = $this->parentModel->getColumnValue('payment_request', 'payment_request_id', $info['payment_request_id'], 'parent_request_id');
                $subscription_id = $this->parentModel->getColumnValue('subscription', 'payment_request_id', $parent_request_id, 'subscription_id');
                $subscription_id = $this->parentModel->getColumnValueExtraWhere('autocollect_subscriptions', 'invoice_subscription_id', $subscription_id, 'subscription_id', 1, '1');
                if ($subscription_id != false) {
                    $plugin['has_autocollect'] = 0;
                    $plugin['autocollect_plan_id'] = 0;
                }
            }

            $fee_id = $this->invoiceModel->getmerchantfeeID($info['merchant_id']);
            if ($fee_id != false) {
                $info['fee_id'] = $fee_id;
            } else {
                $info['fee_id'] = '';
            }

            $info['plugin_value'] = json_encode($plugin);

            if ($info['customer_user_id'] != '') {
                Session::put('patron_type', 1);
                $info['patron_type'] = 1;
            } else {
                Session::put('patron_type', 2);
                $info['patron_type'] = 2;
            }
            $user_id = Session::get('userid');

            if ($info['customer_user_id'] != $this->user_id) {
                $info['diff_login'] = '1';
            }

            $is_online_payment = ($info['merchant_type'] == 2 && $info['legal_complete'] == 1) ? 1 : 0;
            $info["is_online_payment"] = $is_online_payment;
            $paidMerchant_request = ($is_online_payment == 1) ? TRUE : FALSE;
            Session::put('paidMerchant_request', $paidMerchant_request);
            $data = $this->setdata($data, $info, $banklist, $payment_request_id, 'Invoice', 'patron');

            if ($savepdf == 2) {
                $data['viewtype'] = 'print';
                if ($info['template_type'] == 'construction') {

                    return view('mailer/invoice/format-' . $type, $data);
                } else {
                    return view('mailer/invoice/' . $info['design_name'], $data);
                }
                die();
            } else {

                $data['viewtype'] = 'pdf';
                define("DOMPDF_ENABLE_HTML5PARSER", true);
                define("DOMPDF_ENABLE_FONTSUBSETTING", true);
                define("DOMPDF_UNICODE_ENABLED", true);
                define("DOMPDF_DPI", 120);
                define("DOMPDF_ENABLE_REMOTE", true);
                if ($info['template_type'] == 'construction') {
                    $pdf = DOMPDF::loadView('mailer.invoice.format-' . $type, $data);
                    $pdf->setPaper("a4", "landscape");
                } else {
                    $pdf = DOMPDF::loadView('mailer.invoice.' . $info['design_name'], $data);
                    $pdf->setPaper("a4", "portrait");
                }
                $name = $info['customer_name'] . '_' . date('Y-M-d H:m:s');
                return $pdf->download($name . '.pdf');
            }
        } else {
        }
    }

    /**
     * @param $link
     * @return \Illuminate\Http\JsonResponse
     */
    public function downloadFullInvoice($link)
    {
        ini_set('max_execution_time', 120);
        $payment_request_id = Encrypt::decode($link);

        if (strlen($payment_request_id) == 10) {
            $data = $this->setBladeProperties('Invoice view', [], [3]);

            #get default billing profile
            $info =  $this->invoiceModel->getInvoiceInfo($payment_request_id, 'customer');
            $info = (array)$info;

            $banklist = $this->parentModel->getConfigList('Bank_name');
            $banklist = json_decode($banklist, 1);
            $info['logo'] = '';

            $logoPath = env('APP_URL') . '/images/logo-703.PNG';
            try {
                $arrContextOptions = [
                    "ssl" => [
                        "verify_peer" => false,
                        "verify_peer_name" => false,
                        "allow_self_signed" => true,
                    ]
                ];

                $info['logo'] = base64_encode(file_get_contents($logoPath, false, stream_context_create($arrContextOptions)));
            } catch (Exception $o) {
            }

            $info['signimg'] = '';
            if (isset($info['signature']['signature_file'])) {
                $imgpath = env('APP_URL') . '/uploads/images/landing/' . $info['signature']['signature_file'];
                if ($info['signature']['signature_file'] != '') {
                    $info['signimg'] = base64_encode(file_get_contents($imgpath));
                }
            }

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


            $info['invoice_attachments'] = $invoiceAttachments;


            $mandatoryDocumentAttachments = [];
            $pdf_link_array = [];
            if (isset($pluginValue->has_mandatory_upload)) {
                $oMerger = PDFMerger::init();
                if ($pluginValue->has_mandatory_upload == 1) {
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


            $info['mandatory_document_attachments'] = $mandatoryDocumentAttachments;

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

            $info['bill_code_attachments'] = $billCodeAttachments;
            $data = $this->setdata($data, $info, $banklist, $payment_request_id);

            $data['viewtype'] = 'pdf';
            define("DOMPDF_ENABLE_HTML5PARSER", true);
            define("DOMPDF_ENABLE_FONTSUBSETTING", true);
            define("DOMPDF_UNICODE_ENABLED", true);
            define("DOMPDF_DPI", 120);
            define("DOMPDF_ENABLE_REMOTE", true);

            if ($info['template_type'] == 'construction') {
                $pdf = App::make('dompdf.wrapper');
                $data['pdf'] = $pdf;
                $pdf->loadView('mailer.invoice.full-invoice', $data);
                $pdf->setPaper("a4", "landscape");
            }

            $name = str_replace(" ", "_", $info['customer_name']) . '_' . time() . '.pdf';

            if (count($pdf_link_array) > 0) {
                Storage::disk('local')->put($name, $pdf->output());
                $DOMpath = Storage::disk('local')->path($name);
                $oMerger->addPDF($DOMpath, 'all', 'L');

                foreach ($pdf_link_array as $path) {
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

        return response()->json([
            'success' => false,
            'message' => 'Invalid Payment request ID'
        ]);
    }

    public function downloadFullInvoiceV2($link)
    {
        $payment_request_id = Encrypt::decode($link);

        if (strlen($payment_request_id) == 10) {
            $data = $this->setBladeProperties('Invoice view', [], [3]);

            #get default billing profile
            $info =  $this->invoiceModel->getInvoiceInfo($payment_request_id, $this->merchant_id);
            $info = (array)$info;

            $banklist = $this->parentModel->getConfigList('Bank_name');
            $banklist = json_decode($banklist, 1);
            $info['logo'] = '';

            $logoPath = env('APP_URL') . '/images/logo-703.PNG';
            try {
                $arrContextOptions = [
                    "ssl" => [
                        "verify_peer" => false,
                        "verify_peer_name" => false,
                        "allow_self_signed" => true,
                    ]
                ];

                $info['logo'] = base64_encode(file_get_contents($logoPath, false, stream_context_create($arrContextOptions)));
            } catch (Exception $o) {
            }

            $info['signimg'] = '';
            if (isset($info['signature']['signature_file'])) {
                $imgpath = env('APP_URL') . '/uploads/images/landing/' . $info['signature']['signature_file'];
                if ($info['signature']['signature_file'] != '') {
                    $info['signimg'] = base64_encode(file_get_contents($imgpath));
                }
            }

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


            $info['invoice_attachments'] = $invoiceAttachments;


            $mandatoryDocumentAttachments = [];
            $pdf_link_array = [];
            if (isset($pluginValue->has_mandatory_upload)) {
                $oMerger = PDFMerger::init();
                if ($pluginValue->has_mandatory_upload == 1) {
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


            $info['mandatory_document_attachments'] = $mandatoryDocumentAttachments;

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

            $info['bill_code_attachments'] = $billCodeAttachments;
            $data = $this->setdataV2($data, $info, $banklist, $payment_request_id);

            $data['viewtype'] = 'pdf';
            define("DOMPDF_ENABLE_HTML5PARSER", true);
            define("DOMPDF_ENABLE_FONTSUBSETTING", true);
            define("DOMPDF_UNICODE_ENABLED", true);
            define("DOMPDF_DPI", 120);
            define("DOMPDF_ENABLE_REMOTE", true);

            if ($info['template_type'] == 'construction') {
                $pdf = DOMPDF::loadView('mailer.invoice.full-invoice', $data);
                $pdf->setPaper("a4", "landscape");
            }

            $name = str_replace(" ", "_", $info['customer_name']) . '_' . time() . '.pdf';

            if (count($pdf_link_array) > 0) {
                Storage::disk('local')->put($name, $pdf->output());
                $DOMpath = Storage::disk('local')->path($name);
                $oMerger->addPDF($DOMpath, 'all', 'L');

                foreach ($pdf_link_array as $path) {
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

        return response()->json([
            'success' => false,
            'message' => 'Invalid Payment request ID'
        ]);
    }

    public function sendEmail($link, $subject)
    {
        $payment_request_id = Encrypt::decode($link);

        if (strlen($payment_request_id) == 10) {
            $data = $this->setBladeProperties('Invoice view', [], [3]);

            #get default billing profile
            $info =  $this->invoiceModel->getInvoiceInfo($payment_request_id, 'customer');
            $info = (array)$info;
            $banklist = $this->parentModel->getConfigList('Bank_name');
            $banklist = json_decode($banklist, 1);

            $unsuburl = env('APP_URL') . '/unsubscribe/select/' . $link;
            $info['unsuburl'] = $unsuburl;
            $savepdfurl = env('APP_URL') . '/patron/invoice/download/' . $link;
            $info['savepdfurl'] = $savepdfurl;
            $info['paylink'] = env('APP_URL') . '/patron/paymentrequest/pay/' . $link;
            $info['viewurl'] = env('APP_URL') . '/patron/invoice/view/' . $link . '/702';
            $data = $this->setdata($data, $info, $banklist, $payment_request_id);
            //attache pdf

            if ($info['template_type'] == 'construction') {

                $covernote = (array) $this->invoiceModel->getCoveringNoteDetails($payment_request_id);

                $subject = $this->getDynamicString($info, $covernote['subject']);
                $msg = $this->getDynamicString($info, $covernote['body']);
                $data['body'] = $msg;
                $data['notebutton'] = $covernote['invoice_label'];
                if ($covernote['pdf_enable'] == 1) {
                    $nm = $this->download($link, 1);
                    $attached[] = array('path' => storage_path("pdf\\702" . $nm . ".pdf"), 'name' => '702.pdf');
                    $attached[] = array('path' => storage_path("pdf\\703" . $nm . ".pdf"), 'name' => '703.pdf');

                    $data['multiattach'] = $attached;
                }
                $data['viewtype'] = 'mailer';

                Helpers::sendMail($info['customer_email'], 'invoice.covering-note', $data, $subject);
            } else {
                $data['viewtype'] = 'mailer';
                Helpers::sendMail($info['customer_email'], 'invoice.' . $info['design_name'], $data, $subject);
            }
        }
    }
    function getDynamicString($info, $message)
    {

        $vars = $this->parentModel->getFromTableName('dynamic_variable');

        $vars = json_decode($vars, 1);
        foreach ($vars as $row) {

            if ($row['name'] == '%BILL_MONTH%') {
                $message = str_replace($row['name'], date("M-y", strtotime($info[$row['column_name']])), $message);
            } else {
                $message = str_replace($row['name'], $info[$row['column_name']], $message);
            }
        }
        return $message;
    }
    private function getWhatsapptext($info)
    {
        $link = env('APP_URL') . '/patron/paymentrequest/view/' .  Encrypt::encode($info['payment_request_id']);
        if (strlen($info['customer_mobile']) == 10) {
            $mobile = '91' . $info['customer_mobile'];
            $mobile = '&phone=' . $mobile;
        } else {
            $mobile = '';
        }
        $sms = "Your latest invoice by " . $info['company_name'] . " for " . number_format($info['grand_total'], 2) . " is ready. Click here to view your invoice and make the payment online - " . $link;
        return 'https://api.whatsapp.com/send?text=' . $sms . $mobile;
    }

    public function setdata($data, $info, $banklist, $payment_request_id, $type = 'Invoice', $user_type = 'merchant', $staging = 0)
    {

        $responce_tax =  $this->invoiceModel->getInvoiceTax($payment_request_id);
        $responce_meta =  $this->invoiceModel->getInvoiceMetadata($info['template_id'], $payment_request_id);
        $cust_values = $this->invoiceModel->getCustomerbreckup($info['customer_id']);

        //find  payment reuest count 
        $paymentRequestData = PaymentRequest::find($payment_request_id);
        $firstpaymentRequest =  $this->invoiceModel->getPaymentRequest($paymentRequestData->contract_id);
        $isFirstInvoice = false;
        $prevDPlusE = [];
        if (!empty($firstpaymentRequest)) {
            if ($firstpaymentRequest->payment_request_id == $payment_request_id) {
                $isFirstInvoice = true;
            }
        } else {
            $isFirstInvoice = true;
        }

        if ($isFirstInvoice == false) {
            $previousInvoiceParticulars = [];
            $previousInvoice = $this->invoiceModel->getPreviousRequest($payment_request_id, $paymentRequestData->contract_id, $paymentRequestData->created_date);
            if ($previousInvoice) {
                $previousInvoiceParticulars =  $this->invoiceModel->getPreviousInvoiceParticular($previousInvoice->payment_request_id);
            }
            $prevDPlusE = [];
            foreach ($previousInvoiceParticulars as $k => $val) {
                $prevDPlusE[$val->pint] = $val->current_billed_amount + $val->previously_billed_amount;
            }
        }

        $info['user_type'] = $user_type;
        $info['staging'] = $staging;
        $data['links'] = $payment_request_id;
        $data['formatename'] = $info['design_name'];
        $data['colors'] = $info['design_color'];
        $data['isFirstInvoice'] = $isFirstInvoice;
        $data['prevDPlusE'] = $prevDPlusE;

        $merchant_header[] = array('column_name' => 'Company name', 'value' => $info['company_name']);
        $merchant_header[] = array('column_name' => 'Merchant address', 'value' => $info['merchant_address']);
        $merchant_header[] = array('column_name' => 'Merchant email', 'value' => $info['business_email']);
        $merchant_header[] = array('column_name' => 'Merchant contact', 'value' => $info['business_contact']);

        $travel_json = $info['properties'];
        $data['metadata']['travel_particular'] = json_decode($travel_json, 1);

        $responce_travel_particulars = '';
        $responce_particular = '';
        if ($info['template_type'] == 'travel') {
            $responce_travel_particulars = $this->invoiceModel->getTravelInvoiceParticular($payment_request_id);
        }

        if ($staging == 0) {
            $responce_particular = $this->parentModel->getTableList('invoice_particular', 'payment_request_id', $payment_request_id, 1);
        } else {
            $responce_particular = $this->parentModel->getTableList('staging_invoice_particular', 'payment_request_id', $payment_request_id, 1);
        }
        $data['metadata']['travel_data'] = json_decode($responce_travel_particulars, 1);
        $data['metadata']['vehicle_details'] = json_decode($responce_meta, 1);
        $data['metadata']['particular'] = json_decode($responce_particular, 1);
        $data['metadata']['tax'] = json_decode($responce_tax, 1);

        if (isset($info['tnc'])) {
            $tnc = preg_replace('/[[:^print:]]/', '', $info['tnc']);
            $info['tnc'] = str_replace("<p>", "<p class='text-sm mt-1'>",  $tnc);
        } else {
            $info['tnc'] = '';
        }

        $num_words = Numbers_Words::toCurrency($info['absolute_cost'], "en_IN", $info['currency']);
        $num_words1 = str_replace("Indian Rupees", "Rupees", $num_words);
        $money_words = ucwords($num_words1);
        $info['absolute_cost_words'] = str_replace('Zero Paises', '', $money_words);

        $data['tax_heders'] = [
            "tax_name" => "Tax name",
            "tax_percent" => "Percentage",
            "applicable" => "Applicable",
            "tax_amount" => "Amount"

        ];
        $data['table_heders'] = json_decode($info['particular_column'], 1);
        $info['its_from'] = 'real';  //To do rename as source
        $rows = json_decode($responce_meta, 1);

        $headerinc = 0;
        $bdsinc = 0;
        $tnckey = 0;
        $header = array();
        $particular = array();
        foreach ($rows as $row) {

            if ($row['column_type'] == 'H') {
                $header[$headerinc]['column_name'] = $row['column_name'];
                $header[$headerinc]['value'] = $row['value'];
                $header[$headerinc]['position'] = $row['position'];
                $header[$headerinc]['function_id'] = $row['function_id'];
                $header[$headerinc]['column_position'] = $row['column_position'];
                $header[$headerinc]['datatype'] = $row['column_datatype'];
                if ($row['save_table_name'] == 'request') {
                    if ($info['template_type'] == 'simple') {
                        switch ($row['column_position']) {
                            case 4:
                                if ($info['bill_date'] != '31 Dec 2050') {
                                    $header[$headerinc]['value'] = $info['bill_date'];
                                }
                                break;
                            case 5:
                                if ($info['due_date'] != '31 Dec 2050') {
                                    $header[$headerinc]['value'] = $info['due_date'];
                                }
                                break;
                            case 8:
                                $header[$headerinc]['value'] = $info['invoice_total'];
                                break;
                            case 9:
                                $header[$headerinc]['value'] = $info['late_fee'];
                                break;
                            case 10:
                                $header[$headerinc]['value'] = $info['invoice_total'];
                                break;
                        }
                    } else {
                        switch ($row['column_position']) {
                            case 5:
                                if ($info['bill_date'] != '31 Dec 2050') {
                                    $header[$headerinc]['value'] = $info['bill_date'];
                                }
                                break;
                            case 6:
                                if ($info['due_date'] != '31 Dec 2050') {
                                    $header[$headerinc]['value'] = $info['due_date'];
                                }
                                break;
                        }
                    }
                }
                if ($row['function_id'] == 4) {
                    $info['previous_due'] = $row['value'];
                }
                $headerinc++;
            }

            if ($row['column_type'] == 'BDS') {
                $bds[$bdsinc]['column_name'] = $row['column_name'];
                $bds[$bdsinc]['value'] = $row['value'];
                $bds[$bdsinc]['position'] = $row['position'];
                $bds[$bdsinc]['function_id'] = $row['function_id'];
                $bds[$bdsinc]['column_position'] = $row['column_position'];
                $bds[$bdsinc]['datatype'] = $row['column_datatype'];
                $bdsinc++;
            }

            if ($row['column_type'] == 'M') {

                switch ($row['column_name']) {
                    case 'Company name':
                        $value = $info['company_name'];
                        break;
                    case 'Merchant contact':
                        $value = $info['business_contact'];
                        break;
                    case 'Merchant email':
                        $value = $info['business_email'];
                        break;
                    case 'Merchant address':
                        $value = str_replace('|', '<br>', $info['merchant_address']);
                        break;
                    case 'Merchant website':
                        $value = $info['merchant_website'];
                        break;
                    case 'Company pan':
                        $value = $info['pan'];
                        break;
                    case 'GSTIN Number':
                        if ($info['gst_number'] !== '') {
                            $value = $info['gst_number'];
                        } else {
                            $value = '';
                        }
                        break;
                    case 'Company TAN':
                        if ($info['tan'] !== '') {
                            $value = $info['tan'];
                        } else {
                            $value = '';
                        }
                        break;
                    case 17:
                        $value = '';
                        break;
                    case 'CIN Number':
                        $value = $info['cin_no'];
                        break;
                }


                $main_header[] = array('column_name' => $row['column_name'], 'value' => $value);
            }

            if ($row['column_type'] == 'C') {
                if ($row['save_table_name'] == 'customer') {
                    switch ($row['customer_column_id']) {
                        case 1:
                            $value = $info['customer_code'];
                            break;
                        case 2:
                            $value = $info['customer_name'];
                            break;
                        case 3:
                            $value = $info['customer_email'];
                            break;
                        case 4:
                            $value = $info['customer_mobile'];
                            break;
                        case 5:
                            $value = $info['customer_address'];
                            break;
                        case 6:
                            $value = $info['customer_city'];
                            break;
                        case 7:
                            $value = $info['customer_state'];
                            break;
                        case 8:
                            $value = $info['customer_zip'];
                            break;
                        case 9:
                            $value = $info['customer_country'];
                            break;
                    }
                } else {
                    if (isset($cust_values[$row['customer_column_id']])) {
                        $value = $cust_values[$row['customer_column_id']];
                    } else {
                        $value = '';
                    }
                }

                $customer_breckup[] = array('column_name' => $row['column_name'], 'value' => $value);
            }
            if ($row['function_id'] == 4) {
                $data['previousdue'] = $row['value'];
                $data['previousdue_col'] = $row['column_name'];
            }
            if ($row['function_id'] == 12) {
                $data['adjustment'] = $row['value'];
                $data['adjustment_col'] = $row['column_name'];
            }
            if ($row['function_id'] == 14) {
                $data['discount'] = $row['value'];
                $data['discount_col'] = $row['column_name'];
            }
        }


        $plugin = json_decode($info['plugin_value'], 1);
        if ($info['franchise_id'] > 0) {
            if ($plugin['franchise_name_invoice'] == 1) {
                $info['main_company_name'] = $info['company_name'];
                $franchise = $this->parentModel->getTableRow('franchise', 'franchise_id', $info['franchise_id']);
                $info['company_name'] = $franchise['franchise_name'];
                $info['gst_number'] = $franchise['gst_number'];
                $info['pan'] = $franchise['pan'];
            }
        }

        if (isset($plugin['has_signature'])) {
            if ($plugin['has_signature'] == 1) {
                $info['signature'] = isset($plugin['signature']) ? $plugin['signature'] : '';
            }
        }

        $merchant_page = null;
        if ($info['display_url'] != '') {
            $merchant_page = env('APP_URL') . '/m/' . $info['display_url'];
        }


        if (empty($main_header)) {
            $main_header[] = array('column_name' => 'Company name', 'value' => $info['company_name']);
            $main_header[] = array('column_name' => 'Merchant email', 'value' => $info['business_email']);

            $main_header[] = array('column_name' => 'Merchant address', 'value' => $info['merchant_address']);
        }

        if (isset($plugin['has_partial'])) {
            $partial_payments =  $this->invoiceModel->querylist("call get_partial_payments('" . $payment_request_id . "')");
            $info["partial_payments"] = $partial_payments;
        } else {
            if ($info['payment_request_status'] == 1) {
                $param['payment_transaction_status'] = 1;
                $res = $this->parentModel->getTableRow('payment_transaction', 'payment_request_id', $payment_request_id, 0, $param);
                $receipt_info = $this->invoiceModel->getReceipt($res->pay_transaction_id, 'Online');
                $info["transaction"] = $receipt_info;
            } elseif ($info['payment_request_status'] == 2) {
                $res = $this->parentModel->getTableRow('offline_response', 'payment_request_id', $payment_request_id, 1);
                $res = (array)$res;

                $receipt_info = $this->invoiceModel->getReceipt($res['offline_response_id'], 'Offline');
                $info["transaction"] = $receipt_info;
            }
        }

        if ($row['column_type'] == 'TC') {
            $tnc[$tnckey] = $row;
            $val = str_replace('&lt;', '<', $row['column_name']);
            $val = str_replace('&gt;', '>', $val);
            $tnc[$tnckey]['column_name'] = $val;
            $tnckey++;
        }
        $tnc = str_replace('&lt;', '<', $info['tnc']);
        $tnc = str_replace('&gt;', '>', $tnc);
        $info["absolute_cost"] = $info['absolute_cost'] - $info['paid_amount'];
        if (isset($plugin['roundoff'])) {
            if ($plugin['roundoff'] == 1) {
                $info["absolute_cost"] = round($info["absolute_cost"], 0);
            }
        }

        $info['grand_total'] = $info['grand_total'] - $info['paid_amount'];
        $grand_total = $info['grand_total'];
        $date = date("m/d/Y");
        $refDate = date("m/d/Y", strtotime($info['due_date']));
        if ($date > $refDate) {
            $info["invoice_total"] = $info['invoice_total'];
            if ($info['grand_total'] > 0) {
                $grand_total = $info['grand_total'] + $info['late_fee'];
            }
        }
        $info['tnc'] = $tnc;
        $num = $info['absolute_cost'];
        $num_words = Numbers_Words::toCurrency($num, "en_IN", $info['currency']);
        $num_words1 = str_replace("Indian Rupees", "Rupees", $num_words);
        $money_words = ucwords($num_words1);
        $info['money_words'] = str_replace('Zero Paises', '', $money_words);
        if ($staging == 0) {
            foreach ($banklist as $value) {
                $bank_ids[] = $value['config_key'];
                $bank_values[] = $value['config_value'];
            }
            $info["bank_id"] = $bank_ids;
            $info["bank_value"] = $bank_values;
            $bankselect = isset($_POST['bank_name']) ? $_POST['bank_name'] : '';
            $info["bank_selected"] = $bankselect;


            $commentlist = $this->parentModel->getTableList('comments', 'parent_id', $payment_request_id);
            $commentlist = json_decode($commentlist, 1);
            $int = 0;
            foreach ($commentlist as $list) {
                $commentlist[$int]['link'] =  Encrypt::encode($list['id']);
                $int++;
            }
            $info["commentlist"] = $commentlist;
        }
        $info["properties"] = json_decode($info['properties'], 1);
        if ($info['template_type'] == 'travel_ticket_booking' || $info['template_type'] == 'travel') {
            if ($staging == 1) {
                $ticket_details = $this->parentModel->getTableList('staging_invoice_travel_particular', 'payment_request_id', $payment_request_id, 1);
            } else {
                $ticket_details = $this->parentModel->getTableList('invoice_travel_particular', 'payment_request_id', $payment_request_id, 1);
            }
            $info["ticket_detail"] = json_decode($ticket_details, 1);
            $info['sec_col'] = array("A", "B", "C", "D", "E", "F", "G", "H", "I");
            $ticket_details = json_decode($ticket_details, 1);

            if ($info['template_type'] == 'travel') {
                $secarray = array();
                foreach ($ticket_details as $td) {
                    if (!in_array($td['type'], $secarray)) {
                        if ($td['type'] == 1 && isset($info['properties']['travel_section'])) {
                            $secarray[] = $td['type'];
                        } elseif ($td['type'] == 2 && isset($info['properties']['travel_cancel_section'])) {
                            $secarray[] = $td['type'];
                        } elseif ($td['type'] == 3 && isset($info['properties']['hotel_section'])) {
                            $secarray[] = $td['type'];
                        } elseif ($td['type'] == 4 && isset($info['properties']['facility_section'])) {
                            $secarray[] = $td['type'];
                        }
                    }

                    $info['secarray'] = $secarray;
                }
            }
        } else if ($info['template_type'] == 'construction') {
            $constriuction_details = $this->invoiceModel->getInvoiceConstructionParticulars($payment_request_id);
            //$this->parentModel->getTableList('invoice_construction_particular', 'payment_request_id', $payment_request_id);
            $tt = json_decode($constriuction_details, 1);

            $info['constriuction_details'] = $this->getData703($tt, $data['isFirstInvoice'], $data['prevDPlusE']);
            $project_details = $this->invoiceModel->getProjectDeatils($payment_request_id);
            $info['project_details'] = $project_details;

            $change_order_ids = json_decode($info['change_order_id'], 1);
            if (!empty($change_order_ids)) {
                $info['last_month_co_amount_positive'] = 0;
                $info['last_month_co_amount_negative'] = 0;
                $info['this_month_co_amount_positive'] = 0;
                $info['this_month_co_amount_negative'] = 0;

                $start_date = '1990-01-01';
                $end_date = date("Y-m-01");
                $info['last_month_co_amount_positive'] = $this->invoiceModel->getChangeOrderAmount($change_order_ids, $start_date, $end_date, '>');
                $info['last_month_co_amount_negative'] = $this->invoiceModel->getChangeOrderAmount($change_order_ids, $start_date, $end_date, '<');

                $start_date = date("Y-m-01");
                $end_date = date("Y-m-d", strtotime("first day of next month"));
                $info['this_month_co_amount_positive'] = $this->invoiceModel->getChangeOrderAmount($change_order_ids, $start_date, $end_date,  '>');
                $info['this_month_co_amount_negative'] = $this->invoiceModel->getChangeOrderAmount($change_order_ids, $start_date, $end_date, '<');

                $info['last_month_co_amount'] = $info['last_month_co_amount_positive'] +  $info['last_month_co_amount_negative'];
                $info['this_month_co_amount'] = $info['this_month_co_amount_positive'] +  $info['this_month_co_amount_negative'];

                $info['total_co_amount_positive'] = $info['last_month_co_amount_positive'] +  $info['this_month_co_amount_positive'];
                $info['total_co_amount_negative'] = $info['last_month_co_amount_negative'] +  $info['this_month_co_amount_negative'];
            } else {

                $info['last_month_co_amount_positive'] = 0;
                $info['last_month_co_amount_negative'] = 0;
                $info['this_month_co_amount_positive'] = 0;
                $info['this_month_co_amount_negative'] = 0;

                $info['total_co_amount_negative'] = 0;
                $info['total_co_amount_positive'] = 0;

                $pre_month_change_order_amount =  $this->invoiceModel->querylist("select sum(`total_change_order_amount`) as change_order_amount from `order`
                where EXTRACT(YEAR_MONTH FROM approved_date)= EXTRACT(YEAR_MONTH FROM '" . $info['created_date'] . "'-INTERVAL 1 MONTH) AND last_update_date<'" . $info['created_date'] . "' AND `status`=1 AND `is_active`=1 AND `contract_id`='" . $info['project_details']->contract_id . "'");
                if ($pre_month_change_order_amount[0]->change_order_amount != null) {
                    $info['last_month_co_amount'] = $pre_month_change_order_amount[0]->change_order_amount;
                } else {
                    $info['last_month_co_amount'] = 0;
                }
                $current_month_change_order_amount =  $this->invoiceModel->querylist("select sum(`total_change_order_amount`) as change_order_amount from `order`
              where EXTRACT(YEAR_MONTH FROM approved_date)=EXTRACT(YEAR_MONTH FROM '" . $info['created_date'] . "') AND last_update_date<'" . $info['created_date'] . "' AND `status`=1 AND `is_active`=1 AND `contract_id`='" . $info['project_details']->contract_id . "'");
                if ($current_month_change_order_amount[0]->change_order_amount != null) {
                    $info['this_month_co_amount'] = $current_month_change_order_amount[0]->change_order_amount;
                } else {
                    $info['this_month_co_amount'] = 0;
                }
            }


            $sumOfc = 0;
            $sumOfd = 0;
            $sumOfe = 0;
            $sumOff = 0;
            $sumOfrasm = 0;
            $sumOfg = 0;
            $sumOfh = 0;
            $sumOfi = 0;
            $sumOforg = 0;
            $total_appro = 0;
            $total_appro = 0;
            $total_retainage_amount = 0;
            $retainage_amount_for_this_draw = 0;
            $total_previously_billed_amount = 0;
            $retainage_amount_stored_materials = 0;
            $retainage_release_amount = 0;
            $retainage_stored_materials_release_amount = 0;
            foreach ($tt as $itesm) {
                $total_appro += $itesm['approved_change_order_amount'];
                $sumOforg += $itesm['original_contract_amount'];
                $sumOfc += $itesm['current_contract_amount'];
                if ($data['isFirstInvoice'] == true) {
                    $sumOfd += $itesm['previously_billed_amount'];
                    $prevBillAmt = $itesm['previously_billed_amount'];
                } else {
                    $sumOfd += $data['prevDPlusE'][$itesm['pint']] ?? 0;
                    $prevBillAmt = $data['prevDPlusE'][$itesm['pint']] ?? 0;
                }
                //$sumOfd += $itesm['previously_billed_amount'];
                $sumOfe += $itesm['current_billed_amount'];
                $total_previously_billed_amount += $itesm['previously_billed_amount'];
                $sumOff += $itesm['stored_materials'];
                $sumOfrasm += $itesm['retainage_amount_stored_materials'] + $itesm['retainage_amount_previously_stored_materials'] - $itesm['retainage_stored_materials_release_amount'];
                $retainage_amount_stored_materials += $itesm['retainage_amount_stored_materials'];
                $total_retainage_amount += $itesm['retainage_amount_for_this_draw'] + $itesm['retainage_amount_previously_withheld'] - $itesm['retainage_release_amount'];
                $retainage_amount_for_this_draw += $itesm['retainage_amount_for_this_draw'];

                $retainage_release_amount += $itesm['retainage_release_amount'];
                $retainage_stored_materials_release_amount += $itesm['retainage_stored_materials_release_amount'];

                //$sumOfg += $sumOfd + $sumOfe + $sumOff; 
                $sumOfg += $prevBillAmt + $itesm['current_billed_amount'] + $itesm['stored_materials'];
                $sumOfh += $itesm['current_contract_amount'] - ($prevBillAmt + $itesm['current_billed_amount'] + $itesm['stored_materials']);
                //$sumOfh += $sumOfc - $sumOfg;

                if (!empty($itesm['total_outstanding_retainage'])) {
                    $sumOfi += $itesm['total_outstanding_retainage'];
                } else {
                    $sumOfi += $itesm['retainage_amount_previously_withheld'];
                }
            }
            $info['total_c'] = $sumOfc;
            $info['total_d'] = $sumOfd;
            $info['total_e'] = $sumOfe;
            $info['total_retainage_amount'] = $total_retainage_amount;
            $info['total_f'] = $sumOff;
            $info['total_rasm'] = $sumOfrasm;
            $info['percent_rasm'] = 0;
            $info['percent_rcw'] = 0;
            $totalBilledAmount = $total_previously_billed_amount + $sumOfe;
            if ($total_retainage_amount > 0 && $totalBilledAmount > 0) {
                $info['percent_rcw'] = $total_retainage_amount * 100 / $totalBilledAmount;
            }

            $info['total_retainage'] = $info['total_retainage_amount'] + $sumOfrasm;

            if ($sumOff > 0 && $sumOfrasm > 0) {
                $info['percent_rasm'] = $sumOfrasm * 100 / $sumOff;
            }

            $info['total_g'] = $sumOfg;
            $info['total_h'] = $sumOfc - $sumOfg;   //$sumOfh;
            $info['total_i'] = $sumOfi;
            $info['total_original_contract'] = $sumOforg;
            $info['total_approve'] = $total_appro;
        }



        if ($info['template_type'] == 'franchise' || $info['template_type'] == 'nonbrandfranchise') {
            $sale_details = $this->parentModel->getTableList('invoice_food_franchise_sales', 'payment_request_id', $payment_request_id, 1);
            $sale_summary = $this->parentModel->getTableList('invoice_food_franchise_summary', 'payment_request_id', $payment_request_id);
            $info["sale_details"] = json_decode($sale_details, 1);
            $info["sale_summary"] = json_decode($sale_summary, 1);
        }

        $info["Url"] =  Encrypt::encode($payment_request_id);
        $info["customer_breckup"] = $customer_breckup;

        $info["merchant_page"] = $merchant_page;
        $info["merchant_id"] = $info['merchant_user_id'];


        $info["payment_request_id"] = $payment_request_id;
        $info["money_text"] = $money_words;

        if (strrpos($info['narrative'], 'http') > 0) {
            $link = substr($info['narrative'], strrpos($info['narrative'], 'http'), strrpos($info['narrative'], ' '));
            $info['narrative'] = str_replace($link, '<a target="_BLANK" href="' . $link . '">' . $link . '</a>', $info['narrative']);
        }
        $info["narrative"] = str_replace('|', '<br>', $info['narrative']);

        $info["amount"] = $info['invoice_total'];
        $info["grand_total"] = $grand_total;
        $info["surcharge_amount"] = 0;

        if ($user_type == 'patron') {
            $count_res =  $this->invoiceModel->querylist("select fee_detail_id from merchant_fee_detail where (surcharge_enabled=1 or pg_surcharge_enabled=1) and is_active=1 and merchant_id='" . $info['merchant_id'] . "'");
            if (empty($count_res)) {
                $info["is_surcharge"] = 0;
            } else {
                $info["is_surcharge"] = 1;
            }
        }


        $info["currentdate"] = date("d M Y");
        if (isset($plugin['has_supplier'])) {
            if ($plugin['has_supplier'] == 1) {
                $supplierlist = $this->invoiceModel->getInvoiceSupplierlist($plugin['supplier']);

                $info["supplierlist"] = json_decode($supplierlist, 1);
            }
        }
        if (isset($plugin['coupon_id'])) {
            if ($plugin['coupon_id'] > 0) {
                $coupon_details = $this->invoiceModel->getCouponDetails($plugin['coupon_id']);

                $info["coupon_details"] = (array)$coupon_details;
            }
        }


        switch ($info['payment_request_status']) {
            case 1:
                $info["error"] = 'This invoice has already been paid online.';
                break;
                //            case 2:
                //                $info["error"] =  'This invoice has already been settled.';
                //                break;
            case 3:
                $info["error"] =  'This invoice has already been deleted.';
                break;
            default:
                break;
        }

        if ($info['is_expire'] == 1) {
            $id = $this->parentModel->getTableRow('payment_request', 'payment_request_id', 'merchant_id', $info['merchant_id'], 0, ' and customer_id=' . $info['customer_id'] . ' and (expiry_date is null or expiry_date>curdate()) order by payment_request_id desc limit 1');
            $info["error"] = 'This invoice has expired and cannot be paid online anymore. ';
            if ($id != false) {
                $link =  Encrypt::encode($id);
                $info["error"] .= '<a href="/' . $user_type . '/paymentrequest/view/' . $link . '">View latest invoice</a>';
            }
        }
        if ($info['short_url'] == '') {
            $link = Encrypt::encode($info['payment_request_id']);
            $info["patron_url"] = env('APP_URL') . '/patron/paymentrequest/view/' . $link;
        } else {
            $info["patron_url"] = $info['short_url'];
        }
        if (isset($info['error'])) {
            if ($info['error'] != '') {
                $info["is_online_payment"] = 0;
            }
        }
        if (substr($info['invoice_number'], 0, 16) == 'System generated') {
            $info['invoice_number'] = $this->invoiceModel->getAutoInvoiceNo(substr($info['invoice_number'], 16));
        }
        //get less Previous certificates for payment from previous invoice
        $info["less_previous_certificates_for_payment"] = 0;
        if (isset($info['project_details'])) {
            $info["less_previous_certificates_for_payment"] = $this->getLessPreviousCertificatesForPayment($info['project_details']->contract_id, $payment_request_id);
            // $info['grand_total'] = $info['grand_total'] - $info["less_previous_certificates_for_payment"];
        }

        $info['user_name'] = Session::get('user_name');
        $info['grouping'] = false;
        $data['metadata']['plugin'] = $plugin;
        $data['info'] = $info;
        $data['metadata']['header'] = $main_header;
        $data['metadata']['customer'] = $customer_breckup;
        $data['metadata']['invoice'] = $header;

        $plugins = json_decode($info['plugin_value'], 1);
        $hasAIALicense = false;
        if (isset($plugins['invoice_output'])) {
            if (isset($plugins['has_aia_license'])) {
                $hasAIALicense = true;
            }
        }

        $data['has_aia_license'] = $hasAIALicense;

        $invoicePrivilegesAccessIDs = json_decode(Redis::get('invoice_privileges_' . $this->user_id), true);
        //$projectPrivilegesAccessIDs = json_decode(Redis::get('project_privileges_' . $this->user_id), true);
        $contractPrivilegesAccessIDs = json_decode(Redis::get('contract_privileges_' . $this->user_id), true);
        $invoiceAccess = '';

//        if(!empty($projectPrivilegesAccessIDs) && in_array($info['project_id'], array_keys($projectPrivilegesAccessIDs))) {
//            if($projectPrivilegesAccessIDs[$info['project_id']] == 'full') {
//                $invoiceAccess = 'full';
//            }
//        }

        if (in_array($info['payment_request_id'], array_keys($invoicePrivilegesAccessIDs))) {
            if($invoicePrivilegesAccessIDs[$info['payment_request_id']] == 'full') {
                $invoiceAccess = 'full';
            }
            if($invoicePrivilegesAccessIDs[$info['payment_request_id']] == 'edit') {
                $invoiceAccess = 'edit';
            }
            if($invoicePrivilegesAccessIDs[$info['payment_request_id']] == 'view-only') {
                $invoiceAccess = 'view-only';
            }
            if($invoicePrivilegesAccessIDs[$info['payment_request_id']] == 'approve') {
                $invoiceAccess = 'approve';
            }
        } elseif(in_array($info['contract_id'], array_keys($contractPrivilegesAccessIDs))) {
            if($contractPrivilegesAccessIDs[$info['contract_id']] == 'full') {
                $invoiceAccess = 'full';
            }

            if($contractPrivilegesAccessIDs[$info['contract_id']] == 'edit') {
                $invoiceAccess = 'edit';
            }

            if($contractPrivilegesAccessIDs[$info['contract_id']] == 'approve') {
                $invoiceAccess = 'approve';
            }

            if($contractPrivilegesAccessIDs[$info['contract_id']] == 'view-only') {
                $invoiceAccess = 'view-only';
            }

        } elseif(in_array('all', array_keys($invoicePrivilegesAccessIDs))) {
            if($invoicePrivilegesAccessIDs['all'] == 'full') {
                $invoiceAccess = 'full';
            }

            if($invoicePrivilegesAccessIDs['all'] == 'edit') {
                $invoiceAccess = 'edit';
            }

            if($invoicePrivilegesAccessIDs['all'] == 'view-only') {
                $invoiceAccess = 'view-only';
            }

            if($invoicePrivilegesAccessIDs['all'] == 'approve') {
                $invoiceAccess = 'approve';
            }
        }

        $data['invoice_access'] = $invoiceAccess;

        return $data;
    }

    public function setdataV2($data, $info, $banklist, $payment_request_id, $type = 'Invoice', $user_type = 'merchant', $staging = 0)
    {

        $responce_tax =  $this->invoiceModel->getInvoiceTax($payment_request_id);
        $responce_meta =  $this->invoiceModel->getInvoiceMetadata($info['template_id'], $payment_request_id);
        $cust_values = $this->invoiceModel->getCustomerbreckup($info['customer_id']);

        //find  payment reuest count 
        $paymentRequestData = PaymentRequest::find($payment_request_id);
        $firstpaymentRequest =  $this->invoiceModel->getPaymentRequest($paymentRequestData->contract_id);
        $isFirstInvoice = false;
        $prevDPlusE = [];
        if (!empty($firstpaymentRequest)) {
            if ($firstpaymentRequest->payment_request_id == $payment_request_id) {
                $isFirstInvoice = true;
            }
        } else {
            $isFirstInvoice = true;
        }

        if ($isFirstInvoice == false) {
            $previousInvoice = $this->invoiceModel->getPreviousRequest($payment_request_id, $paymentRequestData->contract_id, $paymentRequestData->created_date);
            $previousInvoiceParticulars =  $this->invoiceModel->getPreviousInvoiceParticular($previousInvoice->payment_request_id);
            $prevDPlusE = [];
            foreach ($previousInvoiceParticulars as $k => $val) {
                $prevDPlusE[$val->pint] = $val->current_billed_amount + $val->previously_billed_amount;
            }
        }

        $info['user_type'] = $user_type;
        $info['staging'] = $staging;
        $data['links'] = $payment_request_id;
        $data['formatename'] = $info['design_name'];
        $data['colors'] = $info['design_color'];
        $data['isFirstInvoice'] = $isFirstInvoice;
        $data['prevDPlusE'] = $prevDPlusE;

        $merchant_header[] = array('column_name' => 'Company name', 'value' => $info['company_name']);
        $merchant_header[] = array('column_name' => 'Merchant address', 'value' => $info['merchant_address']);
        $merchant_header[] = array('column_name' => 'Merchant email', 'value' => $info['business_email']);
        $merchant_header[] = array('column_name' => 'Merchant contact', 'value' => $info['business_contact']);

        $travel_json = $info['properties'];
        $data['metadata']['travel_particular'] = json_decode($travel_json, 1);

        $responce_travel_particulars = '';
        $responce_particular = '';
        if ($info['template_type'] == 'travel') {
            $responce_travel_particulars = $this->invoiceModel->getTravelInvoiceParticular($payment_request_id);
        }

        if ($staging == 0) {
            $responce_particular = $this->parentModel->getTableList('invoice_particular', 'payment_request_id', $payment_request_id, 1);
        } else {
            $responce_particular = $this->parentModel->getTableList('staging_invoice_particular', 'payment_request_id', $payment_request_id, 1);
        }
        $data['metadata']['travel_data'] = json_decode($responce_travel_particulars, 1);
        $data['metadata']['vehicle_details'] = json_decode($responce_meta, 1);
        $data['metadata']['particular'] = json_decode($responce_particular, 1);
        $data['metadata']['tax'] = json_decode($responce_tax, 1);

        if (isset($info['tnc'])) {
            $tnc = preg_replace('/[[:^print:]]/', '', $info['tnc']);
            $info['tnc'] = str_replace("<p>", "<p class='text-sm mt-1'>",  $tnc);
        } else {
            $info['tnc'] = '';
        }

        $num_words = Numbers_Words::toCurrency($info['absolute_cost'], "en_IN", $info['currency']);
        $num_words1 = str_replace("Indian Rupees", "Rupees", $num_words);
        $money_words = ucwords($num_words1);
        $info['absolute_cost_words'] = str_replace('Zero Paises', '', $money_words);

        $data['tax_heders'] = [
            "tax_name" => "Tax name",
            "tax_percent" => "Percentage",
            "applicable" => "Applicable",
            "tax_amount" => "Amount"

        ];
        $data['table_heders'] = json_decode($info['particular_column'], 1);
        $info['its_from'] = 'real';  //To do rename as source
        $rows = json_decode($responce_meta, 1);

        $headerinc = 0;
        $bdsinc = 0;
        $tnckey = 0;
        $header = array();
        $particular = array();
        foreach ($rows as $row) {

            if ($row['column_type'] == 'H') {
                $header[$headerinc]['column_name'] = $row['column_name'];
                $header[$headerinc]['value'] = $row['value'];
                $header[$headerinc]['position'] = $row['position'];
                $header[$headerinc]['function_id'] = $row['function_id'];
                $header[$headerinc]['column_position'] = $row['column_position'];
                $header[$headerinc]['datatype'] = $row['column_datatype'];
                if ($row['save_table_name'] == 'request') {
                    if ($info['template_type'] == 'simple') {
                        switch ($row['column_position']) {
                            case 4:
                                if ($info['bill_date'] != '31 Dec 2050') {
                                    $header[$headerinc]['value'] = $info['bill_date'];
                                }
                                break;
                            case 5:
                                if ($info['due_date'] != '31 Dec 2050') {
                                    $header[$headerinc]['value'] = $info['due_date'];
                                }
                                break;
                            case 8:
                                $header[$headerinc]['value'] = $info['invoice_total'];
                                break;
                            case 9:
                                $header[$headerinc]['value'] = $info['late_fee'];
                                break;
                            case 10:
                                $header[$headerinc]['value'] = $info['invoice_total'];
                                break;
                        }
                    } else {
                        switch ($row['column_position']) {
                            case 5:
                                if ($info['bill_date'] != '31 Dec 2050') {
                                    $header[$headerinc]['value'] = $info['bill_date'];
                                }
                                break;
                            case 6:
                                if ($info['due_date'] != '31 Dec 2050') {
                                    $header[$headerinc]['value'] = $info['due_date'];
                                }
                                break;
                        }
                    }
                }
                if ($row['function_id'] == 4) {
                    $info['previous_due'] = $row['value'];
                }
                $headerinc++;
            }

            if ($row['column_type'] == 'BDS') {
                $bds[$bdsinc]['column_name'] = $row['column_name'];
                $bds[$bdsinc]['value'] = $row['value'];
                $bds[$bdsinc]['position'] = $row['position'];
                $bds[$bdsinc]['function_id'] = $row['function_id'];
                $bds[$bdsinc]['column_position'] = $row['column_position'];
                $bds[$bdsinc]['datatype'] = $row['column_datatype'];
                $bdsinc++;
            }

            if ($row['column_type'] == 'M') {

                switch ($row['column_name']) {
                    case 'Company name':
                        $value = $info['company_name'];
                        break;
                    case 'Merchant contact':
                        $value = $info['business_contact'];
                        break;
                    case 'Merchant email':
                        $value = $info['business_email'];
                        break;
                    case 'Merchant address':
                        $value = str_replace('|', '<br>', $info['merchant_address']);
                        break;
                    case 'Merchant website':
                        $value = $info['merchant_website'];
                        break;
                    case 'Company pan':
                        $value = $info['pan'];
                        break;
                    case 'GSTIN Number':
                        if ($info['gst_number'] !== '') {
                            $value = $info['gst_number'];
                        } else {
                            $value = '';
                        }
                        break;
                    case 'Company TAN':
                        if ($info['tan'] !== '') {
                            $value = $info['tan'];
                        } else {
                            $value = '';
                        }
                        break;
                    case 17:
                        $value = '';
                        break;
                    case 'CIN Number':
                        $value = $info['cin_no'];
                        break;
                }


                $main_header[] = array('column_name' => $row['column_name'], 'value' => $value);
            }

            if ($row['column_type'] == 'C') {
                if ($row['save_table_name'] == 'customer') {
                    switch ($row['customer_column_id']) {
                        case 1:
                            $value = $info['customer_code'];
                            break;
                        case 2:
                            $value = $info['customer_name'];
                            break;
                        case 3:
                            $value = $info['customer_email'];
                            break;
                        case 4:
                            $value = $info['customer_mobile'];
                            break;
                        case 5:
                            $value = $info['customer_address'];
                            break;
                        case 6:
                            $value = $info['customer_city'];
                            break;
                        case 7:
                            $value = $info['customer_state'];
                            break;
                        case 8:
                            $value = $info['customer_zip'];
                            break;
                        case 9:
                            $value = $info['customer_country'];
                            break;
                    }
                } else {
                    if (isset($cust_values[$row['customer_column_id']])) {
                        $value = $cust_values[$row['customer_column_id']];
                    } else {
                        $value = '';
                    }
                }

                $customer_breckup[] = array('column_name' => $row['column_name'], 'value' => $value);
            }
            if ($row['function_id'] == 4) {
                $data['previousdue'] = $row['value'];
                $data['previousdue_col'] = $row['column_name'];
            }
            if ($row['function_id'] == 12) {
                $data['adjustment'] = $row['value'];
                $data['adjustment_col'] = $row['column_name'];
            }
            if ($row['function_id'] == 14) {
                $data['discount'] = $row['value'];
                $data['discount_col'] = $row['column_name'];
            }
        }


        $plugin = json_decode($info['plugin_value'], 1);
        if ($info['franchise_id'] > 0) {
            if ($plugin['franchise_name_invoice'] == 1) {
                $info['main_company_name'] = $info['company_name'];
                $franchise = $this->parentModel->getTableRow('franchise', 'franchise_id', $info['franchise_id']);
                $info['company_name'] = $franchise['franchise_name'];
                $info['gst_number'] = $franchise['gst_number'];
                $info['pan'] = $franchise['pan'];
            }
        }

        if (isset($plugin['has_signature'])) {
            if ($plugin['has_signature'] == 1) {
                $info['signature'] = isset($plugin['signature']) ? $plugin['signature'] : '';
            }
        }

        $merchant_page = null;
        if ($info['display_url'] != '') {
            $merchant_page = env('APP_URL') . '/m/' . $info['display_url'];
        }


        if (empty($main_header)) {
            $main_header[] = array('column_name' => 'Company name', 'value' => $info['company_name']);
            $main_header[] = array('column_name' => 'Merchant email', 'value' => $info['business_email']);

            $main_header[] = array('column_name' => 'Merchant address', 'value' => $info['merchant_address']);
        }

        if (isset($plugin['has_partial'])) {
            $partial_payments =  $this->invoiceModel->querylist("call get_partial_payments('" . $payment_request_id . "')");
            $info["partial_payments"] = $partial_payments;
        } else {
            if ($info['payment_request_status'] == 1) {
                $param['payment_transaction_status'] = 1;
                $res = $this->parentModel->getTableRow('payment_transaction', 'payment_request_id', $payment_request_id, 0, $param);
                $receipt_info = $this->invoiceModel->getReceipt($res->pay_transaction_id, 'Online');
                $info["transaction"] = $receipt_info;
            } elseif ($info['payment_request_status'] == 2) {
                $res = $this->parentModel->getTableRow('offline_response', 'payment_request_id', $payment_request_id, 1);
                $res = (array)$res;

                $receipt_info = $this->invoiceModel->getReceipt($res['offline_response_id'], 'Offline');
                $info["transaction"] = $receipt_info;
            }
        }

        if ($row['column_type'] == 'TC') {
            $tnc[$tnckey] = $row;
            $val = str_replace('&lt;', '<', $row['column_name']);
            $val = str_replace('&gt;', '>', $val);
            $tnc[$tnckey]['column_name'] = $val;
            $tnckey++;
        }
        $tnc = str_replace('&lt;', '<', $info['tnc']);
        $tnc = str_replace('&gt;', '>', $tnc);
        $info["absolute_cost"] = $info['absolute_cost'] - $info['paid_amount'];
        if (isset($plugin['roundoff'])) {
            if ($plugin['roundoff'] == 1) {
                $info["absolute_cost"] = round($info["absolute_cost"], 0);
            }
        }

        $info['grand_total'] = $info['grand_total'] - $info['paid_amount'];
        $grand_total = $info['grand_total'];
        $date = date("m/d/Y");
        $refDate = date("m/d/Y", strtotime($info['due_date']));
        if ($date > $refDate) {
            $info["invoice_total"] = $info['invoice_total'];
            if ($info['grand_total'] > 0) {
                $grand_total = $info['grand_total'] + $info['late_fee'];
            }
        }
        $info['tnc'] = $tnc;
        $num = $info['absolute_cost'];
        $num_words = Numbers_Words::toCurrency($num, "en_IN", $info['currency']);
        $num_words1 = str_replace("Indian Rupees", "Rupees", $num_words);
        $money_words = ucwords($num_words1);
        $info['money_words'] = str_replace('Zero Paises', '', $money_words);
        if ($staging == 0) {
            foreach ($banklist as $value) {
                $bank_ids[] = $value['config_key'];
                $bank_values[] = $value['config_value'];
            }
            $info["bank_id"] = $bank_ids;
            $info["bank_value"] = $bank_values;
            $bankselect = isset($_POST['bank_name']) ? $_POST['bank_name'] : '';
            $info["bank_selected"] = $bankselect;


            $commentlist = $this->parentModel->getTableList('comments', 'parent_id', $payment_request_id);
            $commentlist = json_decode($commentlist, 1);
            $int = 0;
            foreach ($commentlist as $list) {
                $commentlist[$int]['link'] =  Encrypt::encode($list['id']);
                $int++;
            }
            $info["commentlist"] = $commentlist;
        }
        $info["properties"] = json_decode($info['properties'], 1);
        if ($info['template_type'] == 'travel_ticket_booking' || $info['template_type'] == 'travel') {
            if ($staging == 1) {
                $ticket_details = $this->parentModel->getTableList('staging_invoice_travel_particular', 'payment_request_id', $payment_request_id, 1);
            } else {
                $ticket_details = $this->parentModel->getTableList('invoice_travel_particular', 'payment_request_id', $payment_request_id, 1);
            }
            $info["ticket_detail"] = json_decode($ticket_details, 1);
            $info['sec_col'] = array("A", "B", "C", "D", "E", "F", "G", "H", "I");
            $ticket_details = json_decode($ticket_details, 1);

            if ($info['template_type'] == 'travel') {
                $secarray = array();
                foreach ($ticket_details as $td) {
                    if (!in_array($td['type'], $secarray)) {
                        if ($td['type'] == 1 && isset($info['properties']['travel_section'])) {
                            $secarray[] = $td['type'];
                        } elseif ($td['type'] == 2 && isset($info['properties']['travel_cancel_section'])) {
                            $secarray[] = $td['type'];
                        } elseif ($td['type'] == 3 && isset($info['properties']['hotel_section'])) {
                            $secarray[] = $td['type'];
                        } elseif ($td['type'] == 4 && isset($info['properties']['facility_section'])) {
                            $secarray[] = $td['type'];
                        }
                    }

                    $info['secarray'] = $secarray;
                }
            }
        } else if ($info['template_type'] == 'construction') {
            $constriuction_details = $this->invoiceModel->getInvoiceConstructionParticulars($payment_request_id);
            //$this->parentModel->getTableList('invoice_construction_particular', 'payment_request_id', $payment_request_id);
            $tt = json_decode($constriuction_details, 1);

            $info['constriuction_details'] = $this->getData703V2($tt, $data['isFirstInvoice'], $data['prevDPlusE']);
            $project_details = $this->invoiceModel->getProjectDeatils($payment_request_id);
            $info['project_details'] = $project_details;

            $change_order_ids = json_decode($info['change_order_id'], 1);
            if (!empty($change_order_ids)) {
                $info['last_month_co_amount_positive'] = 0;
                $info['last_month_co_amount_negative'] = 0;
                $info['this_month_co_amount_positive'] = 0;
                $info['this_month_co_amount_negative'] = 0;

                $start_date = '1990-01-01';
                $end_date = date("Y-m-01");
                $info['last_month_co_amount_positive'] = $this->invoiceModel->getChangeOrderAmount($change_order_ids, $start_date, $end_date, '>');
                $info['last_month_co_amount_negative'] = $this->invoiceModel->getChangeOrderAmount($change_order_ids, $start_date, $end_date, '<');

                $start_date = date("Y-m-01");
                $end_date = date("Y-m-d", strtotime("first day of next month"));
                $info['this_month_co_amount_positive'] = $this->invoiceModel->getChangeOrderAmount($change_order_ids, $start_date, $end_date,  '>');
                $info['this_month_co_amount_negative'] = $this->invoiceModel->getChangeOrderAmount($change_order_ids, $start_date, $end_date, '<');

                $info['last_month_co_amount'] = $info['last_month_co_amount_positive'] +  $info['last_month_co_amount_negative'];
                $info['this_month_co_amount'] = $info['this_month_co_amount_positive'] +  $info['this_month_co_amount_negative'];

                $info['total_co_amount_positive'] = $info['last_month_co_amount_positive'] +  $info['this_month_co_amount_positive'];
                $info['total_co_amount_negative'] = $info['last_month_co_amount_negative'] +  $info['this_month_co_amount_negative'];
            } else {

                $info['last_month_co_amount_positive'] = 0;
                $info['last_month_co_amount_negative'] = 0;
                $info['this_month_co_amount_positive'] = 0;
                $info['this_month_co_amount_negative'] = 0;

                $info['total_co_amount_positive'] = 0;
                $info['total_co_amount_negative'] = 0;

                $pre_month_change_order_amount =  $this->invoiceModel->querylist("select sum(`total_change_order_amount`) as change_order_amount from `order`
                where EXTRACT(YEAR_MONTH FROM approved_date)= EXTRACT(YEAR_MONTH FROM '" . $info['created_date'] . "'-INTERVAL 1 MONTH) AND last_update_date<'" . $info['created_date'] . "' AND `status`=1 AND `is_active`=1 AND `contract_id`='" . $info['project_details']->contract_id . "'");
                if ($pre_month_change_order_amount[0]->change_order_amount != null) {
                    $info['last_month_co_amount'] = $pre_month_change_order_amount[0]->change_order_amount;
                } else {
                    $info['last_month_co_amount'] = 0;
                }
                $current_month_change_order_amount =  $this->invoiceModel->querylist("select sum(`total_change_order_amount`) as change_order_amount from `order`
              where EXTRACT(YEAR_MONTH FROM approved_date)=EXTRACT(YEAR_MONTH FROM '" . $info['created_date'] . "') AND last_update_date<'" . $info['created_date'] . "' AND `status`=1 AND `is_active`=1 AND `contract_id`='" . $info['project_details']->contract_id . "'");
                if ($current_month_change_order_amount[0]->change_order_amount != null) {
                    $info['this_month_co_amount'] = $current_month_change_order_amount[0]->change_order_amount;
                } else {
                    $info['this_month_co_amount'] = 0;
                }
            }


            $sumOfc = 0;
            $sumOfd = 0;
            $sumOfe = 0;
            $sumOff = 0;
            $sumOfrasm = 0;
            $sumOfg = 0;
            $sumOfh = 0;
            $sumOfi = 0;
            $sumOforg = 0;
            $total_appro = 0;
            $total_appro = 0;
            $total_retainage_amount = 0;
            $retainage_amount_for_this_draw = 0;
            $total_previously_billed_amount = 0;
            $retainage_amount_stored_materials = 0;
            $retainage_release_amount = 0;
            $retainage_stored_materials_release_amount = 0;
            foreach ($tt as $itesm) {
                $total_appro += $itesm['approved_change_order_amount'];
                $sumOforg += $itesm['original_contract_amount'];
                $sumOfc += $itesm['current_contract_amount'];
                if ($data['isFirstInvoice'] == true) {
                    $sumOfd += $itesm['previously_billed_amount'];
                    $prevBillAmt = $itesm['previously_billed_amount'];
                } else {
                    $sumOfd += $data['prevDPlusE'][$itesm['pint']] ?? 0;
                    $prevBillAmt = $data['prevDPlusE'][$itesm['pint']] ?? 0;
                }
                //$sumOfd += $itesm['previously_billed_amount'];
                $sumOfe += $itesm['current_billed_amount'];
                $total_previously_billed_amount += $itesm['previously_billed_amount'];
                $sumOff += $itesm['stored_materials'];
                $sumOfrasm += $itesm['retainage_amount_stored_materials'] + $itesm['retainage_amount_previously_stored_materials'] - $itesm['retainage_stored_materials_release_amount'];
                $retainage_amount_stored_materials += $itesm['retainage_amount_stored_materials'];
                $total_retainage_amount += $itesm['retainage_amount_for_this_draw'] + $itesm['retainage_amount_previously_withheld'] - $itesm['retainage_release_amount'];
                $retainage_amount_for_this_draw += $itesm['retainage_amount_for_this_draw'];

                $retainage_release_amount += $itesm['retainage_release_amount'];
                $retainage_stored_materials_release_amount += $itesm['retainage_stored_materials_release_amount'];

                //$sumOfg += $sumOfd + $sumOfe + $sumOff; 
                $sumOfg += $prevBillAmt + $itesm['current_billed_amount'] + $itesm['stored_materials'];
                $sumOfh += $itesm['current_contract_amount'] - ($prevBillAmt + $itesm['current_billed_amount'] + $itesm['stored_materials']);
                //$sumOfh += $sumOfc - $sumOfg;

                if (!empty($itesm['total_outstanding_retainage'])) {
                    $sumOfi += $itesm['total_outstanding_retainage'];
                } else {
                    $sumOfi += $itesm['retainage_amount_previously_withheld'];
                }
            }
            $info['total_c'] = $sumOfc;
            $info['total_d'] = $sumOfd;
            $info['total_e'] = $sumOfe;
            $info['total_retainage_amount'] = $total_retainage_amount;
            $info['total_f'] = $sumOff;
            $info['total_rasm'] = $sumOfrasm;
            $info['percent_rasm'] = 0;
            $info['percent_rcw'] = 0;
            $totalBilledAmount = $total_previously_billed_amount + $sumOfe;
            if ($total_retainage_amount > 0 && $totalBilledAmount > 0) {
                $info['percent_rcw'] = $total_retainage_amount * 100 / $totalBilledAmount;
            }

            $info['total_retainage'] = $info['total_retainage_amount'] + $sumOfrasm;

            if ($sumOff > 0 && $sumOfrasm > 0) {
                $info['percent_rasm'] = $sumOfrasm * 100 / $sumOff;
            }

            $info['total_g'] = $sumOfg;
            $info['total_h'] = $sumOfc - $sumOfg;   //$sumOfh;
            $info['total_i'] = $sumOfi;
            $info['total_original_contract'] = $sumOforg;
            $info['total_approve'] = $total_appro;
        }



        if ($info['template_type'] == 'franchise' || $info['template_type'] == 'nonbrandfranchise') {
            $sale_details = $this->parentModel->getTableList('invoice_food_franchise_sales', 'payment_request_id', $payment_request_id, 1);
            $sale_summary = $this->parentModel->getTableList('invoice_food_franchise_summary', 'payment_request_id', $payment_request_id);
            $info["sale_details"] = json_decode($sale_details, 1);
            $info["sale_summary"] = json_decode($sale_summary, 1);
        }

        $info["Url"] =  Encrypt::encode($payment_request_id);
        $info["customer_breckup"] = $customer_breckup;

        $info["merchant_page"] = $merchant_page;
        $info["merchant_id"] = $info['merchant_user_id'];


        $info["payment_request_id"] = $payment_request_id;
        $info["money_text"] = $money_words;

        if (strrpos($info['narrative'], 'http') > 0) {
            $link = substr($info['narrative'], strrpos($info['narrative'], 'http'), strrpos($info['narrative'], ' '));
            $info['narrative'] = str_replace($link, '<a target="_BLANK" href="' . $link . '">' . $link . '</a>', $info['narrative']);
        }
        $info["narrative"] = str_replace('|', '<br>', $info['narrative']);

        $info["amount"] = $info['invoice_total'];
        $info["grand_total"] = $grand_total;
        $info["surcharge_amount"] = 0;

        if ($user_type == 'patron') {
            $count_res =  $this->invoiceModel->querylist("select fee_detail_id from merchant_fee_detail where (surcharge_enabled=1 or pg_surcharge_enabled=1) and is_active=1 and merchant_id='" . $info['merchant_id'] . "'");
            if (empty($count_res)) {
                $info["is_surcharge"] = 0;
            } else {
                $info["is_surcharge"] = 1;
            }
        }


        $info["currentdate"] = date("d M Y");
        if (isset($plugin['has_supplier'])) {
            if ($plugin['has_supplier'] == 1) {
                $supplierlist = $this->invoiceModel->getInvoiceSupplierlist($plugin['supplier']);

                $info["supplierlist"] = json_decode($supplierlist, 1);
            }
        }
        if (isset($plugin['coupon_id'])) {
            if ($plugin['coupon_id'] > 0) {
                $coupon_details = $this->invoiceModel->getCouponDetails($plugin['coupon_id']);

                $info["coupon_details"] = (array)$coupon_details;
            }
        }


        switch ($info['payment_request_status']) {
            case 1:
                $info["error"] = 'This invoice has already been paid online.';
                break;
                //            case 2:
                //                $info["error"] =  'This invoice has already been settled.';
                //                break;
            case 3:
                $info["error"] =  'This invoice has already been deleted.';
                break;
            default:
                break;
        }

        if ($info['is_expire'] == 1) {
            $id = $this->parentModel->getTableRow('payment_request', 'payment_request_id', 'merchant_id', $info['merchant_id'], 0, ' and customer_id=' . $info['customer_id'] . ' and (expiry_date is null or expiry_date>curdate()) order by payment_request_id desc limit 1');
            $info["error"] = 'This invoice has expired and cannot be paid online anymore. ';
            if ($id != false) {
                $link =  Encrypt::encode($id);
                $info["error"] .= '<a href="/' . $user_type . '/paymentrequest/view/' . $link . '">View latest invoice</a>';
            }
        }
        if ($info['short_url'] == '') {
            $link = Encrypt::encode($info['payment_request_id']);
            $info["patron_url"] = env('APP_URL') . '/patron/paymentrequest/view/' . $link;
        } else {
            $info["patron_url"] = $info['short_url'];
        }
        if (isset($info['error'])) {
            if ($info['error'] != '') {
                $info["is_online_payment"] = 0;
            }
        }
        if (substr($info['invoice_number'], 0, 16) == 'System generated') {
            $info['invoice_number'] = $this->invoiceModel->getAutoInvoiceNo(substr($info['invoice_number'], 16));
        }
        //get less Previous certificates for payment from previous invoice
        $info["less_previous_certificates_for_payment"] = 0;
        if (isset($info['project_details'])) {
            $info["less_previous_certificates_for_payment"] = $this->getLessPreviousCertificatesForPayment($info['project_details']->contract_id, $payment_request_id);
            // $info['grand_total'] = $info['grand_total'] - $info["less_previous_certificates_for_payment"];
        }

        $info['user_name'] = Session::get('user_name');
        $info['grouping'] = true;
        $data['metadata']['plugin'] = $plugin;
        $data['info'] = $info;
        $data['metadata']['header'] = $main_header;
        $data['metadata']['customer'] = $customer_breckup;
        $data['metadata']['invoice'] = $header;

        $plugins = json_decode($info['plugin_value'], 1);
        $hasAIALicense = false;
        if (isset($plugins['invoice_output'])) {
            if (isset($plugins['has_aia_license'])) {
                $hasAIALicense = true;
            }
        }

        $data['has_aia_license'] = $hasAIALicense;
        
        $has_watermark = false;
        $data['watermark_text'] = '';
        if (isset($plugins['has_watermark'])) {
            if($plugins['has_watermark'] == 1){
                $has_watermark = true;
                $data['watermark_text'] =$plugins['watermark_text'];
            }
        }
        $data['has_watermark'] = $has_watermark;

        dd($this->user_id, $this->merchant_id);

        $invoicePrivilegesAccessIDs = json_decode(Redis::get('invoice_privileges_' . $this->user_id), true);
        //$projectPrivilegesAccessIDs = json_decode(Redis::get('project_privileges_' . $this->user_id), true);
        $contractPrivilegesAccessIDs = json_decode(Redis::get('contract_privileges_' . $this->user_id), true);
        $invoiceAccess = '';

//        if(!empty($projectPrivilegesAccessIDs) && in_array($info['project_id'], array_keys($projectPrivilegesAccessIDs))) {
//            if($projectPrivilegesAccessIDs[$info['project_id']] == 'full') {
//                $invoiceAccess = 'full';
//            }
//        }

        if (in_array($info['payment_request_id'], array_keys($invoicePrivilegesAccessIDs))) {
            if($invoicePrivilegesAccessIDs[$info['payment_request_id']] == 'full') {
                $invoiceAccess = 'full';
            }
            if($invoicePrivilegesAccessIDs[$info['payment_request_id']] == 'edit') {
                $invoiceAccess = 'edit';
            }
            if($invoicePrivilegesAccessIDs[$info['payment_request_id']] == 'view-only') {
                $invoiceAccess = 'view-only';
            }
            if($invoicePrivilegesAccessIDs[$info['payment_request_id']] == 'approve') {
                $invoiceAccess = 'approve';
            }
        } elseif(in_array($info['contract_id'], array_keys($contractPrivilegesAccessIDs))) {
            if($contractPrivilegesAccessIDs[$info['contract_id']] == 'full') {
                $invoiceAccess = 'full';
            }

            if($contractPrivilegesAccessIDs[$info['contract_id']] == 'edit') {
                $invoiceAccess = 'edit';
            }

            if($contractPrivilegesAccessIDs[$info['contract_id']] == 'approve') {
                $invoiceAccess = 'approve';
            }

            if($contractPrivilegesAccessIDs[$info['contract_id']] == 'view-only') {
                $invoiceAccess = 'view-only';
            }

        } elseif(in_array('all', array_keys($invoicePrivilegesAccessIDs))) {
            if($invoicePrivilegesAccessIDs['all'] == 'full') {
                $invoiceAccess = 'full';
            }

            if($invoicePrivilegesAccessIDs['all'] == 'edit') {
                $invoiceAccess = 'edit';
            }

            if($invoicePrivilegesAccessIDs['all'] == 'view-only') {
                $invoiceAccess = 'view-only';
            }

            if($invoicePrivilegesAccessIDs['all'] == 'approve') {
                $invoiceAccess = 'approve';
            }
        }

        $data['invoice_access'] = $invoiceAccess;

        return $data;
    }

    /**
     * @param $contract_id
     * @param $customer_id
     * @return int|string
     */
    private function getLessPreviousCertificatesForPayment($contract_id, $payment_request_id)
    {
        $less_previous_certificates_for_payment = 0;
        $pre_req_id =  $this->invoiceModel->getPreviousInvoice($this->merchant_id, $contract_id, $payment_request_id);

        if ($pre_req_id != false) {
            $less_previous_certificates_for_payment = $this->invoiceModel->getColumnValue('payment_request', 'payment_request_id', $pre_req_id, 'invoice_total');
        }
        return $less_previous_certificates_for_payment;
    }


    public function getData703($tt, $isFirstInvoice = true, $prevParictular = null)
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
            $type = "";
            $bill_code = '';
            $desc = '';
            $c = 0;
            $d = 0;
            $e = 0;
            $f = 0;
            $g = 0;
            $retain = 0;
            $isattach = '';
            $bill_desc = '';
            $pos = 0;
            $pos1 = 0;
            $sub_c = 0;
            $sub_d = 0;
            $sub_e = 0;
            $sub_f = 0;
            $sub_g = 0;
            $sub_g_per = 0;
            $sub_h = 0;
            $sub_i = 0;
            $attach_count = 0;
            foreach ($result[$names] as $data) {


                $pos1++;
                if (!empty($data['group']) && $data['bill_code_detail'] == 'No') {
                    $type = 'combine';
                    $desc = $names;
                    $c += $data['current_contract_amount'];

                    if ($isFirstInvoice == true) {
                        $d += $data['previously_billed_amount'];
                    } else {
                        if (isset($prevParictular[$data['pint']])) {
                            if (is_numeric($prevParictular[$data['pint']])) {
                                $pp = $prevParictular[$data['pint']] ?? 0;
                                $d += $pp;
                            }
                        }
                    }

                    //$d += $data['previously_billed_amount'];
                    $e += $data['current_billed_amount'];
                    $f += $data['stored_materials'];
                    $retain += $data['total_outstanding_retainage'];
                    $counts = 0;
                    if (empty($bill_code)) {
                        $bill_code = $data['bill_code'];
                    }
                    $data['attachments'] = str_replace('"undefined",', '', $data['attachments']);
                    $data['attachments'] = str_replace('"undefined"', '', $data['attachments']);
                    $data['attachments'] = str_replace('[]', '', $data['attachments']);
                    if (!empty($data['attachments']))
                        $counts = count(json_decode($data['attachments'], 1));



                    $attach_count += $counts;
                    if (empty($isattach)) {
                        $nm = '';
                        if (!empty($data['attachments'])) {
                            $nm = substr(substr(basename(json_decode($data['attachments'], 1)[0]), 0, strrpos(basename(json_decode($data['attachments'], 1)[0]), '.')), -10);
                        }


                        $isattach = str_replace(' ', '_', $data['attachments'] ? strlen(substr(basename(json_decode($data['attachments'], 1)[0]), 0, strrpos(basename(json_decode($data['attachments'], 1)[0]), '.'))) < 10 ? substr(basename(json_decode($data['attachments'], 1)[0]), 0, strrpos(basename(json_decode($data['attachments'], 1)[0]), '.')) : $nm : '');
                    }
                    if (empty($bill_desc)) {
                        if (!empty($data['description']))
                            $bill_desc = str_replace(' ', '_', strlen($data['bill_code']) > 7 ? substr($data['bill_code'], 0, 7) : $data['bill_code']);
                        else
                            $bill_desc = str_replace(' ', '_', strlen($data['bill_code']) > 7 ? substr($data['bill_code'], 0, 7) : $data['bill_code']);
                    }
                } else  if (!empty($data['group']) && $data['bill_code_detail'] == 'Yes') {
                    if ($pos == 0) {
                        $single_data = array();
                        $single_data['a'] = '';
                        $single_data['type'] = 'heading';
                        $single_data['b'] = $names;
                        $single_data['c'] = '';
                        $single_data['d'] = '';
                        $single_data['e'] = '';

                        $single_data['f'] = '';
                        $single_data['g'] = '';
                        $single_data['g_per'] = '';
                        $single_data['h'] = '';
                        $single_data['i'] = '';
                        $grouping_data[] = $single_data;
                    }
                    $single_data = array();
                    $single_data['a'] = $data['code']; // $data['bill_code'];
                    $single_data['type'] = '';
                    $single_data['b'] = $data['description'];
                    $single_data['group_name'] = str_replace(' ', '_', strlen($names) > 7 ? substr($names, 0, 7) : $names);
                    $single_data['c'] = $data['current_contract_amount'];
                    if ($isFirstInvoice == true) {
                        $single_data['d'] = ($data['previously_billed_amount']);
                    } else {
                        $single_data['d'] = $prevParictular[$data['pint']] ?? 0;
                    }
                    //$single_data['d'] = number_format(($data['previously_billed_amount']), 2);
                    $single_data['e'] = $data['current_billed_amount'];
                    $single_data['f'] = $data['stored_materials'];

                    $nm = '';
                    if (!empty($data['attachments'])) {
                        $nm = substr(substr(basename(json_decode($data['attachments'], 1)[0]), 0, strrpos(basename(json_decode($data['attachments'], 1)[0]), '.')), -10);
                    }

                    $single_data['attachment'] = str_replace(' ', '_', $data['attachments'] ? strlen(substr(basename(json_decode($data['attachments'], 1)[0]), 0, strrpos(basename(json_decode($data['attachments'], 1)[0]), '.'))) < 10 ? substr(basename(json_decode($data['attachments'], 1)[0]), 0, strrpos(basename(json_decode($data['attachments'], 1)[0]), '.')) : $nm : '');

                    $counts = 0;
                    if (!empty($data['attachments']))
                        $counts = count(json_decode($data['attachments'], 1));

                    if ($counts > 1)
                        $single_data['files'] = $counts . ' files';
                    else
                        $single_data['files'] = $counts . ' file';

                    //$single_data['g'] = number_format($data['previously_billed_amount'] + $data['current_billed_amount'] + $data['stored_materials'], 2);
                    $single_data['g'] = $single_data['d'] + $single_data['e'] + $single_data['f'];
                    $per = 0;
                    if ($data['current_contract_amount'] > 0)
                        $per = number_format(($single_data['g']) / $data['current_contract_amount'], 2);

                    $single_data['g_per'] = number_format($per, 2);
                    $single_data['h'] = number_format($data['current_contract_amount'] - ($single_data['g']), 2);

                    if (!empty($data['total_outstanding_retainage'])) {
                        $single_data['i'] = number_format($data['total_outstanding_retainage'], 2);
                    } else {
                        $single_data['i'] = number_format($data['retainage_amount_previously_withheld'], 2);
                    }


                    $grouping_data[] = $single_data;

                    $pos++;
                    $sub_c += $data['current_contract_amount'];

                    if ($isFirstInvoice == true) {
                        $sub_d += $data['previously_billed_amount'];
                    } else {
                        $sub_d += $prevParictular[$data['pint']] ?? 0;
                    }
                    //$sub_d += $data['previously_billed_amount'];
                    $sub_e += $data['current_billed_amount'];
                    $sub_f += $data['stored_materials'];
                    //$sub_g += $data['previously_billed_amount'] + $data['current_billed_amount'] + $data['stored_materials'];
                    $sub_g += $single_data['g'];
                    $sub_g_per += $per;
                    $sub_h += $data['current_contract_amount'] - ($single_data['d'] + $single_data['e'] + $single_data['f']);
                    //$sub_i += $data['total_outstanding_retainage'];

                    if (!empty(floatval($data['total_outstanding_retainage']))) {
                        $sub_i += $data['total_outstanding_retainage'];
                    } else {
                        $sub_i += $data['retainage_amount_previously_withheld'];
                    }

                    if ($pos1 == count($result[$names]) ||  $pos == count($result[$names])) {
                        $single_data = array();
                        $single_data['a'] = '';
                        $single_data['type'] = 'footer';
                        $single_data['b'] = 'sub total';
                        $single_data['c'] = number_format($sub_c, 2);
                        $single_data['d'] = number_format($sub_d, 2);
                        $single_data['e'] = number_format($sub_e, 2);
                        $single_data['f'] = number_format($sub_f, 2);

                        $single_data['g'] = number_format($sub_g, 2);
                        $single_data['g_per'] = number_format($sub_g_per, 2);
                        $single_data['h'] = number_format($sub_h, 2);
                        $single_data['i'] = number_format($sub_i, 2);
                        $grouping_data[] = $single_data;
                    }
                } else {

                    $single_data = array();
                    $single_data['a'] = $data['code']; //$data['bill_code'];
                    $single_data['b'] = $data['description'];
                    $single_data['type'] = '';
                    $single_data['c'] = $data['current_contract_amount'];
                    if ($isFirstInvoice == true) {
                        $single_data['d'] = $data['previously_billed_amount'];
                    } else {
                        $single_data['d'] = $prevParictular[$data['pint']] ?? 0;
                    }
                    //$single_data['d'] = number_format(($data['previously_billed_amount']), 2);
                    $single_data['e'] = $data['current_billed_amount'];
                    $single_data['f'] = $data['stored_materials'];
                    $single_data['g'] = $single_data['d'] + $single_data['e'] + $single_data['f'];
                    //  $single_data['attachment']=$data['attachments']?substr(substr(substr(basename(json_decode($data['attachments'],1)[0]), 0, strrpos(basename(json_decode($data['attachments'],1)[0]), '.')),0,-4),0,7):'';
                    $nm = '';
                    if (!empty($data['attachments'])) {
                        $nm = substr(substr(basename(json_decode($data['attachments'], 1)[0]), 0, strrpos(basename(json_decode($data['attachments'], 1)[0]), '.')), -10);
                    }


                    $single_data['attachment'] = str_replace(' ', '_', $data['attachments'] ? strlen(substr(basename(json_decode($data['attachments'], 1)[0]), 0, strrpos(basename(json_decode($data['attachments'], 1)[0]), '.'))) < 10 ? substr(basename(json_decode($data['attachments'], 1)[0]), 0, strrpos(basename(json_decode($data['attachments'], 1)[0]), '.')) : $nm : '');
                    $counts = 0;
                    if (!empty($data['attachments']))
                        $counts = count(json_decode($data['attachments'], 1));

                    if ($counts > 1)
                        $single_data['files'] = $counts . ' files';
                    else
                        $single_data['files'] = $counts . ' file';

                    $per = 0;

                    if (!empty($data['current_contract_amount'])) {
                        if ($data['current_contract_amount'] > 0) {
                            $per = number_format(($single_data['g']) / $data['current_contract_amount'], 2);
                        }
                    }
                    $per = str_replace(',', '', $per);
                    $single_data['g_per'] = number_format($per, 2);
                    $single_data['h'] = number_format($data['current_contract_amount'] - ($single_data['g']), 2);

                    if (!empty($data['total_outstanding_retainage'])) {
                        $single_data['i'] = number_format($data['total_outstanding_retainage'], 2);
                    } else {
                        $single_data['i'] = number_format($data['retainage_amount_previously_withheld'], 2);
                    }

                    $grouping_data[] = $single_data;
                }
            }

            if (!empty($type)) {
                $g = $d + $e + $f;
                $single_data1['a'] = $bill_code;
                $single_data1['type'] = $type;
                $single_data1['b'] = $desc;
                $single_data1['c'] = number_format($c, 2);

                //not added here
                // if($isFirstInvoice == true) {
                //     $single_data1['d'] = number_format($d, 2);
                // } else {
                //     $single_data1['d'] = $prevParictular[$data['pint']]??0;
                // }

                $single_data1['d'] = number_format($d, 2);
                $single_data1['e'] = number_format($e, 2);
                $single_data1['group_name'] = $bill_desc;
                $single_data1['f'] = number_format($f, 2);
                $single_data1['g'] = number_format($g, 2);
                if ($attach_count > 1)
                    $single_data1['files'] = $attach_count . ' files';
                else
                    $single_data1['files'] = $attach_count . ' file';


                if ($c > 0)
                    $single_data1['g_per'] = number_format(($g / $c), 2);
                else
                    $single_data1['g_per'] = number_format(0, 2);
                $single_data1['h'] = number_format(($c - $g), 2);
                $single_data1['i'] = number_format($retain, 2);
                $single_data1['attachment'] = $isattach;

                $grouping_data[] = $single_data1;
                $bill_code = '';
                $type = '';
                $desc = '';
                $c = 0;
                $d = 0;
                $e = 0;
                $f = 0;
                $g = 0;
                $retain = 0;
            }
        }

        return $grouping_data;
    }

    public function getData703V2($tt, $isFirstInvoice = true, $prevParictular = null)
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

        $sub_result = array();
        foreach ($tt as $element) {
            $sub_result[$element['group']][$element['sub_group']][] = $element;
        }

        $single_data1 = array();

        foreach ($group_names as $names) {
            $type = "";
            $bill_code = '';
            $desc = '';
            $c = 0;
            $d = 0;
            $e = 0;
            $f = 0;
            $g = 0;
            $retain = 0;
            $isattach = '';
            $bill_desc = '';
            $pos = 0;
            $pos1 = 0;
            $sub_c = 0;
            $sub_d = 0;
            $sub_e = 0;
            $sub_f = 0;
            $sub_g = 0;
            $sub_g_per = 0;
            $sub_h = 0;
            $sub_i = 0;
            $attach_count = 0;
            $sub_key = '';
            $footer_sub_key = '';
            foreach ($sub_result[$names] as $key => $data2) {

                foreach ($data2 as $data) {

                    $pos1++;
                    if (!empty($data['group']) && $data['bill_code_detail'] == 'No') {
                        $type = 'combine';
                        $desc = $names;
                        $c += $data['current_contract_amount'];

                        if ($isFirstInvoice == true) {
                            $d += $data['previously_billed_amount'];
                        } else {
                            if (isset($prevParictular[$data['pint']])) {
                                if (is_numeric($prevParictular[$data['pint']])) {
                                    $pp = $prevParictular[$data['pint']] ?? 0;
                                    $d += $pp;
                                }
                            }
                        }

                        //$d += $data['previously_billed_amount'];
                        $e += $data['current_billed_amount'];
                        $f += $data['stored_materials'];
                        $retain += $data['total_outstanding_retainage'];
                        $counts = 0;
                        if (empty($bill_code)) {
                            $bill_code = $data['bill_code'];
                        }
                        $data['attachments'] = str_replace('"undefined",', '', $data['attachments']);
                        $data['attachments'] = str_replace('"undefined"', '', $data['attachments']);
                        $data['attachments'] = str_replace('[]', '', $data['attachments']);
                        if (!empty($data['attachments']))
                            $counts = count(json_decode($data['attachments'], 1));



                        $attach_count += $counts;
                        if (empty($isattach)) {
                            $nm = '';
                            if (!empty($data['attachments'])) {
                                $nm = substr(substr(basename(json_decode($data['attachments'], 1)[0]), 0, strrpos(basename(json_decode($data['attachments'], 1)[0]), '.')), -10);
                            }


                            $isattach = str_replace(' ', '_', $data['attachments'] ? strlen(substr(basename(json_decode($data['attachments'], 1)[0]), 0, strrpos(basename(json_decode($data['attachments'], 1)[0]), '.'))) < 10 ? substr(basename(json_decode($data['attachments'], 1)[0]), 0, strrpos(basename(json_decode($data['attachments'], 1)[0]), '.')) : $nm : '');
                        }
                        if (empty($bill_desc)) {
                            if (!empty($data['description']))
                                $bill_desc = str_replace(' ', '_', strlen($data['bill_code']) > 7 ? substr($data['bill_code'], 0, 7) : $data['bill_code']);
                            else
                                $bill_desc = str_replace(' ', '_', strlen($data['bill_code']) > 7 ? substr($data['bill_code'], 0, 7) : $data['bill_code']);
                        }
                    } else  if (!empty($data['group']) && $data['bill_code_detail'] == 'Yes') {
                        if ($pos == 0) {
                            $single_data = array();
                            $single_data['a'] = '';
                            $single_data['type'] = 'heading';
                            $single_data['b'] = $names;
                            $single_data['c'] = '';
                            $single_data['d'] = '';
                            $single_data['e'] = '';

                            $single_data['f'] = '';
                            $single_data['g'] = '';
                            $single_data['g_per'] = '';
                            $single_data['h'] = '';
                            $single_data['i'] = '';
                            $grouping_data[] = $single_data;
                        }


                        $current_sub_key =  $names . $key;
                        if ($key != '' && $sub_key != $current_sub_key) {
                            $single_data = array();
                            $single_data['a'] = '';
                            $single_data['type'] = 'sub-heading';
                            $single_data['b'] = $key;
                            $single_data['c'] = '';
                            $single_data['d'] = '';
                            $single_data['e'] = '';
                            $single_data['f'] = '';
                            $single_data['g'] = '';
                            $single_data['g_per'] = '';
                            $single_data['h'] = '';
                            $single_data['i'] = '';
                            $grouping_data[] = $single_data;
                        }

                        $single_data = array();
                        $single_data['a'] = $data['code']; // $data['bill_code'];
                        $single_data['type'] = '';
                        $single_data['b'] = $data['description'];
                        $single_data['group_name'] = str_replace(' ', '_', strlen($names) > 7 ? substr($names, 0, 7) : $names);
                        $single_data['c'] = $data['current_contract_amount'];
                        if ($isFirstInvoice == true) {
                            $single_data['d'] = ($data['previously_billed_amount']);
                        } else {
                            $single_data['d'] = $prevParictular[$data['pint']] ?? 0;
                        }
                        //$single_data['d'] = number_format(($data['previously_billed_amount']), 2);
                        $single_data['e'] = $data['current_billed_amount'];
                        $single_data['f'] = $data['stored_materials'];

                        $nm = '';
                        if (!empty($data['attachments'])) {
                            $nm = substr(substr(basename(json_decode($data['attachments'], 1)[0]), 0, strrpos(basename(json_decode($data['attachments'], 1)[0]), '.')), -10);
                        }

                        $single_data['attachment'] = str_replace(' ', '_', $data['attachments'] ? strlen(substr(basename(json_decode($data['attachments'], 1)[0]), 0, strrpos(basename(json_decode($data['attachments'], 1)[0]), '.'))) < 10 ? substr(basename(json_decode($data['attachments'], 1)[0]), 0, strrpos(basename(json_decode($data['attachments'], 1)[0]), '.')) : $nm : '');

                        $counts = 0;
                        if (!empty($data['attachments']))
                            $counts = count(json_decode($data['attachments'], 1));

                        if ($counts > 1)
                            $single_data['files'] = $counts . ' files';
                        else
                            $single_data['files'] = $counts . ' file';

                        //$single_data['g'] = number_format($data['previously_billed_amount'] + $data['current_billed_amount'] + $data['stored_materials'], 2);
                        $single_data['g'] = $single_data['d'] + $single_data['e'] + $single_data['f'];
                        $per = 0;
                        if ($data['current_contract_amount'] > 0)
                            $per = number_format(($single_data['g']) / $data['current_contract_amount'], 2);

                        $single_data['g_per'] = number_format($per, 2);
                        $single_data['h'] = number_format($data['current_contract_amount'] - ($single_data['g']), 2);

                        if (!empty($data['total_outstanding_retainage'])) {
                            $single_data['i'] = number_format($data['total_outstanding_retainage'], 2);
                        } else {
                            $single_data['i'] = number_format($data['retainage_amount_previously_withheld'], 2);
                        }


                        $grouping_data[] = $single_data;

                        $pos++;
                        $sub_c += $data['current_contract_amount'];

                        if ($isFirstInvoice == true) {
                            $sub_d += $data['previously_billed_amount'];
                        } else {
                            $sub_d += $prevParictular[$data['pint']] ?? 0;
                        }
                        //$sub_d += $data['previously_billed_amount'];
                        $sub_e += $data['current_billed_amount'];
                        $sub_f += $data['stored_materials'];
                        //$sub_g += $data['previously_billed_amount'] + $data['current_billed_amount'] + $data['stored_materials'];
                        $sub_g += $single_data['g'];
                        $sub_g_per += $per;
                        $sub_h += $data['current_contract_amount'] - ($single_data['d'] + $single_data['e'] + $single_data['f']);
                        //$sub_i += $data['total_outstanding_retainage'];

                        if (!empty(floatval($data['total_outstanding_retainage']))) {
                            $sub_i += $data['total_outstanding_retainage'];
                        } else {
                            $sub_i += $data['retainage_amount_previously_withheld'];
                        }

                        if ($key != '' && ($pos1 == count($sub_result[$names][$key]) || $pos == count($result[$names]))) {
                            // if ($sub_key == count($sub_result[$names][$key])) {
                            $single_data = array();
                            $single_data['a'] = '';
                            $single_data['type'] = 'sub-footer';
                            $single_data['b'] = $key . ' sub total';
                            $single_data['c'] = number_format($sub_c, 2);
                            $single_data['d'] = number_format($sub_d, 2);
                            $single_data['e'] = number_format($sub_e, 2);
                            $single_data['f'] = number_format($sub_f, 2);

                            $single_data['g'] = number_format($sub_g, 2);
                            $single_data['g_per'] = number_format($sub_g_per, 2);
                            $single_data['h'] = number_format($sub_h, 2);
                            $single_data['i'] = number_format($sub_i, 2);
                            $grouping_data[] = $single_data;
                            // }
                        }

                        if ($pos1 == count($result[$names]) ||  $pos == count($result[$names])) {
                            $single_data = array();
                            $single_data['a'] = '';
                            $single_data['type'] = 'footer';
                            $single_data['b'] = $names . ' sub total';
                            $single_data['c'] = number_format($sub_c, 2);
                            $single_data['d'] = number_format($sub_d, 2);
                            $single_data['e'] = number_format($sub_e, 2);
                            $single_data['f'] = number_format($sub_f, 2);

                            $single_data['g'] = number_format($sub_g, 2);
                            $single_data['g_per'] = number_format($sub_g_per, 2);
                            $single_data['h'] = number_format($sub_h, 2);
                            $single_data['i'] = number_format($sub_i, 2);
                            $grouping_data[] = $single_data;
                        }
                    } else {

                        $single_data = array();
                        $single_data['a'] = $data['code']; //$data['bill_code'];
                        $single_data['b'] = $data['description'];
                        $single_data['type'] = '';
                        $single_data['c'] = $data['current_contract_amount'];
                        if ($isFirstInvoice == true) {
                            $single_data['d'] = $data['previously_billed_amount'];
                        } else {
                            $single_data['d'] = $prevParictular[$data['pint']] ?? 0;
                        }
                        //$single_data['d'] = number_format(($data['previously_billed_amount']), 2);
                        $single_data['e'] = $data['current_billed_amount'];
                        $single_data['f'] = $data['stored_materials'];
                        $single_data['g'] = $single_data['d'] + $single_data['e'] + $single_data['f'];
                        //  $single_data['attachment']=$data['attachments']?substr(substr(substr(basename(json_decode($data['attachments'],1)[0]), 0, strrpos(basename(json_decode($data['attachments'],1)[0]), '.')),0,-4),0,7):'';
                        $nm = '';
                        if (!empty($data['attachments'])) {
                            $nm = substr(substr(basename(json_decode($data['attachments'], 1)[0]), 0, strrpos(basename(json_decode($data['attachments'], 1)[0]), '.')), -10);
                        }


                        $single_data['attachment'] = str_replace(' ', '_', $data['attachments'] ? strlen(substr(basename(json_decode($data['attachments'], 1)[0]), 0, strrpos(basename(json_decode($data['attachments'], 1)[0]), '.'))) < 10 ? substr(basename(json_decode($data['attachments'], 1)[0]), 0, strrpos(basename(json_decode($data['attachments'], 1)[0]), '.')) : $nm : '');
                        $counts = 0;
                        if (!empty($data['attachments']))
                            $counts = count(json_decode($data['attachments'], 1));

                        if ($counts > 1)
                            $single_data['files'] = $counts . ' files';
                        else
                            $single_data['files'] = $counts . ' file';

                        $per = 0;

                        if (!empty($data['current_contract_amount'])) {
                            if ($data['current_contract_amount'] > 0) {
                                $per = number_format(($single_data['g']) / $data['current_contract_amount'], 2);
                            }
                        }
                        $per = str_replace(',', '', $per);
                        $single_data['g_per'] = number_format($per, 2);
                        $single_data['h'] = number_format($data['current_contract_amount'] - ($single_data['g']), 2);

                        if (!empty($data['total_outstanding_retainage'])) {
                            $single_data['i'] = number_format($data['total_outstanding_retainage'], 2);
                        } else {
                            $single_data['i'] = number_format($data['retainage_amount_previously_withheld'], 2);
                        }

                        $grouping_data[] = $single_data;
                    }

                    $sub_key =  $names . $key;
                }
                
                $footer_sub_key =  $names . $key;
            }

            if (!empty($type)) {
                $g = $d + $e + $f;
                $single_data1['a'] = $bill_code;
                $single_data1['type'] = $type;
                $single_data1['b'] = $desc;
                $single_data1['c'] = number_format($c, 2);

                //not added here
                // if($isFirstInvoice == true) {
                //     $single_data1['d'] = number_format($d, 2);
                // } else {
                //     $single_data1['d'] = $prevParictular[$data['pint']]??0;
                // }

                $single_data1['d'] = number_format($d, 2);
                $single_data1['e'] = number_format($e, 2);
                $single_data1['group_name'] = $bill_desc;
                $single_data1['f'] = number_format($f, 2);
                $single_data1['g'] = number_format($g, 2);
                if ($attach_count > 1)
                    $single_data1['files'] = $attach_count . ' files';
                else
                    $single_data1['files'] = $attach_count . ' file';


                if ($c > 0)
                    $single_data1['g_per'] = number_format(($g / $c), 2);
                else
                    $single_data1['g_per'] = number_format(0, 2);
                $single_data1['h'] = number_format(($c - $g), 2);
                $single_data1['i'] = number_format($retain, 2);
                $single_data1['attachment'] = $isattach;

                $grouping_data[] = $single_data1;
                $bill_code = '';
                $type = '';
                $desc = '';
                $c = 0;
                $d = 0;
                $e = 0;
                $f = 0;
                $g = 0;
                $retain = 0;
            }
        }

        return $grouping_data;
    }


    public function save(Request $request)
    {
        $invoice_number = '';
        foreach ($request->function_id as $k => $function_id) {
            if ($function_id == 9) {
                $invoice_number = $request->newvalues[$k];
            }
        }
        $template = $this->invoiceModel->getTableRow('invoice_template', 'template_id', $request->template_id);

        foreach ($request->col_position as $k => $position) {
            if ($position == 4) {
                $cyclename = $request->requestvalue[$k];
            } elseif ($position == 5) {
                $billdate = Helpers::sqlDate($request->requestvalue[$k]);
            } elseif ($position == 6) {
                $duedate = Helpers::sqlDate($request->requestvalue[$k]);
            }
        }
        if ($request->link != '') {
            $request_id = Encrypt::decode($request->link);
            $invoice = $this->invoiceModel->getTableRow('payment_request', 'payment_request_id', $request_id);
            $plugin = $this->setPlugins(json_decode($invoice->plugin_value, 1), $request);
            $revision = false;
            if ($invoice->payment_request_status != 11) {
                if ($plugin['save_revision_history'] == 1) {
                    $revision = true;
                    $revision_data['payment_request'] = $this->invoiceModel->getTableRow('payment_request', 'payment_request_id', $request_id);
                    $revision_data['payment_request'] = json_decode(json_encode($revision_data['payment_request']), 1);
                    $revision_data['invoice_column_values'] = $this->invoiceModel->getTableList('invoice_column_values', 'payment_request_id', $request_id);
                    $revision_data['invoice_column_values'] = json_decode(json_encode($revision_data['invoice_column_values']), 1);
                    $revision_data['invoice_construction_particular'] = $this->invoiceModel->getTableList('invoice_construction_particular', 'payment_request_id', $request_id);
                    $revision_data['invoice_construction_particular'] = json_decode(json_encode($revision_data['invoice_construction_particular']), 1);
                }
            }

            $response = $this->invoiceModel->updateInvoice($request_id, $this->user_id, $request->customer_id, $invoice_number, implode('~', $request->newvalues), implode('~', $request->ids), $billdate, $duedate, $cyclename, $request->narrative, $invoice->grand_total, 0, 0, json_encode($plugin), $invoice->billing_profile_id, $invoice->currency,  1, $invoice->notify_patron, $invoice->payment_request_status);
            if (isset($plugin['has_mandatory_upload'])) {
                if ($plugin['has_mandatory_upload'] == 1) {
                    $this->invoiceModel->deleteMandatoryFiles($request_id);
                    foreach ($plugin['mandatory_data'] as $key => $mandatory_data) {
                        $mandatory_files = $_POST['file_upload_mandatory' . $key];
                        $mandatory_files_insert_array = explode(',', $mandatory_files);
                        foreach ($mandatory_files_insert_array as $file_url) {
                            $insert_id = $this->invoiceModel->saveMandatoryFiles($request_id, $file_url, $mandatory_data['name'], $mandatory_data['description'], $mandatory_data['required']);
                        }
                    }
                }
            }
            if ($revision == true) {
                $this->storeRevision($request_id, $revision_data);
            }
        } else {
            $plugin = $this->setPlugins(json_decode($template->plugin, 1), $request);
            $response = $this->invoiceModel->saveInvoice($this->merchant_id, $this->user_id, $request->customer_id, $invoice_number, $request->template_id, implode('~', $request->newvalues), implode('~', $request->ids), $billdate, $duedate, $cyclename, $request->narrative, 0, 0, 0, json_encode($plugin), $request->currency,  1, 0, 11);

            if (isset($plugin['has_mandatory_upload'])) {
                if ($plugin['has_mandatory_upload'] == 1) {
                    foreach ($plugin['mandatory_data'] as $key => $mandatory_data) {
                        $mandatory_files = $_POST['file_upload_mandatory' . $key];
                        $mandatory_files_insert_array = explode(',', $mandatory_files);
                        foreach ($mandatory_files_insert_array as $file_url) {
                            $insert_id = $this->invoiceModel->saveMandatoryFiles($response->request_id, $file_url, $mandatory_data['name'], $mandatory_data['description'], $mandatory_data['required']);
                        }
                    }
                }
            }

            $this->invoiceModel->updateTable('payment_request', 'payment_request_id', $response->request_id, 'contract_id', $request->contract_id);
            $request_id = $response->request_id;
        }
        return redirect('/merchant/invoice/particular/' . Encrypt::encode($request_id));
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
        return $plugin;
    }

    public function particularsave(Request $request, $type = null)
    {
        ini_set('max_execution_time', 120);
        //        dd($request);
        $request_id = Encrypt::decode($request->link);

        if (strlen($request_id) != 10) {
            throw new Exception('Invalid id ' . $request_id);
        }
        $invoice = $this->invoiceModel->getTableRow('payment_request', 'payment_request_id', $request_id);

        //Check Privileges
        $userRole = Session::get('user_role');
        $contractPrivilegesAccessIDs = json_decode(Redis::get('contract_privileges_' . $this->user_id), true);
        $invoicePrivilegesAccessIDs = json_decode(Redis::get('invoice_privileges_' . $this->user_id), true);
        
        $hasAccess = false;
        if($userRole == 'Admin') {
            $hasAccess = true;
        } else {
            if(in_array($invoice->contract_id, array_keys($contractPrivilegesAccessIDs)) || in_array($invoice->payment_request_id, array_keys($invoicePrivilegesAccessIDs))) {
                if($contractPrivilegesAccessIDs[$invoice->contract_id] != 'view-only' || $invoicePrivilegesAccessIDs[$invoice->payment_request_id] != 'view-only') {
                    $hasAccess = true;
                }
            }
        }

        if(!$hasAccess) {
            return redirect('/merchant/no-permission');
        }

        if ($invoice == false) {
            throw new Exception('Invalid id ' . $request_id);
        }
        $revision = false;
        if ($invoice->payment_request_status != 11) {
            $plugin = json_decode($invoice->plugin_value, 1);
            if ($plugin['save_revision_history'] == 1) {
                $revision = true;
                $revision_data['payment_request'] = $invoice;
                $revision_data['payment_request'] = json_decode(json_encode($revision_data['payment_request']), 1);
                $revision_data['invoice_column_values'] = $this->invoiceModel->getTableList('invoice_column_values', 'payment_request_id', $request_id);
                $revision_data['invoice_column_values'] = json_decode(json_encode($revision_data['invoice_column_values']), 1);
                $revision_data['invoice_construction_particular'] = $this->invoiceModel->getTableList('invoice_construction_particular', 'payment_request_id', $request_id);
                $revision_data['invoice_construction_particular'] = json_decode(json_encode($revision_data['invoice_construction_particular']), 1);
            }
        }

        $this->invoiceModel->updateTable('invoice_construction_particular', 'payment_request_id', $request_id, 'is_active', 0);
        $project_id = $this->invoiceModel->getColumnValue('contract', 'contract_id', $invoice->contract_id, 'project_id');
        $this->invoiceModel->updateBilledTransactionStatus($request_id, $project_id);
        if ($type == null) {
            $billed_transaction_ids = [];
            if (!empty($request->bill_code)) {
                foreach ($request->bill_code as $k => $bill_code) {
                    $request = Helpers::setArrayZeroValue(array(
                        'original_contract_amount', 'approved_change_order_amount', 'current_contract_amount', 'previously_billed_percent', 'previously_billed_amount', 'current_billed_percent', 'current_billed_amount', 'total_billed', 'retainage_percent', 'retainage_amount_previously_withheld', 'retainage_amount_for_this_draw', 'net_billed_amount', 'retainage_release_amount', 'total_outstanding_retainage', 'calculated_perc',
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
                    $data['project'] = $request->project[$k];
                    $data['cost_code'] = $request->cost_code[$k];
                    $data['cost_type'] = $request->cost_type[$k];
                    $data['group'] = $request->group[$k];
                    $data['sub_group'] = $request->sub_group[$k];
                    $data['bill_code_detail'] = ($request->bill_code_detail[$k] == '') ? 'Yes' : $request->bill_code_detail[$k];
                    $data['calculated_perc'] = $request->calculated_perc[$k];
                    $data['calculated_row'] = $request->calculated_row[$k];
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
                    $previous_invoice_amount = $this->getLessPreviousCertificatesForPayment($invoice->contract_id, $request_id);
                    $this->invoiceModel->updateInvoiceDetail($request_id, $request->totalcost, $request->order_ids ?? [], $previous_invoice_amount);
                    if ($data['id'] > 0) {
                        $this->invoiceModel->updateConstructionParticular($data, $data['id'], $this->user_id);
                    } else {
                        $this->invoiceModel->saveConstructionParticular($data, $request_id, $this->user_id);
                    }
                }
            }
            if (!empty($billed_transaction_ids)) {
                $this->invoiceModel->updateBilledTransactionRequestID($request_id, $billed_transaction_ids);
            }
            if ($revision == true) {
                $this->storeRevision($request_id, $revision_data);
            }
        }

        return redirect('/merchant/invoice/viewg703/' . Encrypt::encode($request_id));
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



    public function particular($link)
    {
        $request_id = Encrypt::decode($link);
        if (strlen($request_id) != 10) {
            return redirect('/error/invalidlink');
        }

        $invoice = $this->invoiceModel->getTableRow('payment_request', 'payment_request_id', $request_id);
        $template = $this->invoiceModel->getTableRow('invoice_template', 'template_id', $invoice->template_id);
        $contract = $this->invoiceModel->getTableRow('contract', 'contract_id', $invoice->contract_id);
        $project = $this->invoiceModel->getTableRow('project', 'id', $contract->project_id);
        $csi_codes = $this->invoiceModel->getBillCodes($contract->project_id);


        $billed_transactions = $this->invoiceModel->getBilledTransactions($project->id, $invoice->bill_date, $request_id);
        $cost_codes = [];
        $cost_types = [];
        foreach ($billed_transactions as $k => $row) {
            if (!in_array($row->cost_code, $cost_codes)) {
                $cost_codes[] = $row->cost_code;
            }
            $billed_transactions[$k]->rate = number_format($row->rate);
            $billed_transactions[$k]->amount = number_format($row->amount);
        }
        $invoice_particulars = $this->invoiceModel->getTableList('invoice_construction_particular', 'payment_request_id', $request_id);
        $merchant_cost_types = $this->getCostTypes();
        $particulars[] = [];
        $groups = [];
        $total = 0;


        $order_id_array = [];
        if ($invoice_particulars->isEmpty()) {
            $particulars = json_decode($contract->particulars);
            $pre_req_id =  $this->invoiceModel->getPreviousContractBill($this->merchant_id, $invoice->contract_id, $request_id);
            if ($pre_req_id != false) {
                $particulars = $this->invoiceModel->getTableList('invoice_construction_particular', 'payment_request_id', $pre_req_id);
                foreach ($particulars as $key => $row) {
                    $particulars[$key]->previously_billed_amount = $particulars[$key]->previously_billed_amount + $particulars[$key]->current_billed_amount;
                    $particulars[$key]->current_billed_amount = '';
                    $particulars[$key]->previously_billed_percent = $particulars[$key]->previously_billed_percent + $particulars[$key]->current_billed_percent;
                    $particulars[$key]->current_billed_percent = '';
                    $particulars[$key]->retainage_amount_previously_withheld = $particulars[$key]->retainage_amount_previously_withheld + $particulars[$key]->retainage_amount_for_this_draw;
                    $particulars[$key]->retainage_amount_for_this_draw = '';
                    $particulars[$key]->retainage_amount_previously_stored_materials = $particulars[$key]->retainage_amount_previously_stored_materials + $particulars[$key]->retainage_amount_stored_materials;
                    $particulars[$key]->retainage_amount_stored_materials = '';
                    $particulars[$key]->current_stored_materials = '';
                    $particulars[$key]->id = '';
                }
            }
            $change_order_data = $this->invoiceModel->getOrderbyContract($invoice->contract_id, date("Y-m-d"));
            $change_order_data = json_decode($change_order_data, true);

            $cop_particulars = [];
            foreach ($change_order_data as $co_data) {
                array_push($order_id_array, (int)$co_data["order_id"]);
                foreach (json_decode($co_data["particulars"], true) as $co_par) {
                    $co_par["change_order_amount"] = $co_par["change_order_amount"];
                    array_push($cop_particulars, $co_par);
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
                        $kdata["cost_type"] = isset($kdata["cost_type"]) ? $kdata["cost_type"] : '';

                        $co_particulars[] = array(
                            'bill_code' => $key,
                            'change_order_amount' => array_sum($value),
                            'description' =>  $kdata["description"],
                            'retainage_percent' => isset($kdata["retainage_percent"]) ? $kdata["retainage_percent"] : '',
                            'sub_group' => isset($kdata["sub_group"]) ? $kdata["sub_group"] : '',
                            'group' => isset($kdata["group"]) ? $kdata["group"] : '',
                            'cost_type' =>  $kdata["cost_type"]
                        );
                    }
                }
            }
            $cp = array();
            if ($pre_req_id != false) {
                $contract_particulars = $this->invoiceModel->getTableList('invoice_construction_particular', 'payment_request_id', $pre_req_id);

                foreach ($contract_particulars as $row) {
                    $cp[$row->bill_code] = $row;
                }

                foreach ($particulars as $k => $v) {
                    if (isset($cp[$v->bill_code])) {
                        $particulars[$k]->previously_stored_materials = $cp[$v->bill_code]->stored_materials;
                    }
                }
            }

            if ($change_order_data != false) {
                $cop = array();
                foreach ($particulars as $row2) {
                    if (isset($cop[$row2->bill_code])) {
                        $cop[$row2->bill_code . rand()] = $row2;
                    } else {
                        $cop[$row2->bill_code] = $row2;
                    }
                }

                foreach ($co_particulars as $k => $v) {
                    if (isset($cop[$v["bill_code"]])) {
                        $cop[$v["bill_code"]]->approved_change_order_amount = $v["change_order_amount"];
                        $cop[$v["bill_code"]]->retainage_percent = $v["retainage_percent"];
                        $cop[$v["bill_code"]]->sub_group = $v["sub_group"];
                        $cop[$v["bill_code"]]->group = $v["group"];
                    } else {
                        $cop[$v["bill_code"]] = (object)[];
                        if (!empty($cp[$v["bill_code"]])) {
                            if (isset($cp[$v["bill_code"]])) {
                                $cop[$v["bill_code"]]->previously_billed_amount = number_format($cp[$v["bill_code"]]->current_billed_amount + $cp[$v["bill_code"]]->previously_billed_amount, 2);
                                $cop[$v["bill_code"]]->previously_billed_percent = number_format($cp[$v["bill_code"]]->current_billed_percent + $cp[$v["bill_code"]]->previously_billed_percent, 2);
                                $cop[$v["bill_code"]]->retainage_amount_previously_withheld = number_format($cp[$v["bill_code"]]->retainage_amount_for_this_draw + $cp[$v["bill_code"]]->retainage_amount_previously_withheld -  $cp[$v["bill_code"]]->retainage_release_amount, 2);
                                $cop[$v["bill_code"]]->retainage_amount_previously_stored_materials = number_format($cp[$v["bill_code"]]->retainage_amount_stored_materials + $cp[$v["bill_code"]]->retainage_amount_previously_stored_materials -  $cp[$v["bill_code"]]->retainage_stored_materials_release_amount, 2);
                            }
                        }
                        $cop[$v["bill_code"]]->approved_change_order_amount = $v["change_order_amount"];
                        $cop[$v["bill_code"]]->original_contract_amount = 0;
                        $cop[$v["bill_code"]]->bill_code = $v["bill_code"];
                        $cop[$v["bill_code"]]->cost_type = $v["cost_type"];
                        $cop[$v["bill_code"]]->bill_type = '% Complete';
                        $cop[$v["bill_code"]]->description = $v["description"];
                        $cop[$v["bill_code"]]->retainage_percent = $v["retainage_percent"];
                        $cop[$v["bill_code"]]->sub_group = $v["sub_group"];
                        $cop[$v["bill_code"]]->group = $v["group"];
                        $cop[$v["bill_code"]]->calculated_perc = '';
                        $cop[$v["bill_code"]]->calculated_row  = '';
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
                $ocm = (isset($row['original_contract_amount'])) ? $row['original_contract_amount'] : 0;
                $acoa = (isset($row['approved_change_order_amount'])) ? $row['approved_change_order_amount'] : 0;
                $particulars[$k]['current_contract_amount'] = $ocm + $acoa;
                $particulars[$k]['attachments'] = '';
                $particulars[$k]['override'] = false;
                if (isset($row['pint'])) {
                    $int = $row['pint'];
                } else {
                    $particulars[$k]['pint'] = $int + 1;
                    $int = $int + 1;
                }
            }
        } else {
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
            $order_id_array = json_decode($invoice->change_order_id, 1);
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
        $data = Helpers::setBladeProperties(ucfirst($title) . ' contract', ['expense', 'contract', 'product', 'template', 'invoiceformat2'], [3, 179]);
        $merchant_cost_types_array = $this->getKeyArrayJson($merchant_cost_types, 'value');
        $data['billed_transactions'] = $billed_transactions;
        $data['merchant_cost_types'] = $merchant_cost_types;
        $data['cost_types_array'] = $merchant_cost_types_array;
        $data['cost_types'] = $merchant_cost_types;
        $data['cost_codes'] = $cost_codes;
        $data['order_id_array'] = json_encode($order_id_array);
        $data['gst_type'] = 'intra';
        $data['button'] = 'Save';
        $data['mode'] = 'create';
        $data['title'] = 'Add Particulars';
        $data['contract_id'] = $invoice->contract_id;
        $data['contract_code'] = $contract->contract_code;
        $data['project_id'] = $project->id;
        $data['project_code'] = $project->project_id;
        $data['link'] = $link;
        $data['particulars'] = $particulars;
        $data['csi_codes'] = json_decode(json_encode($csi_codes), 1);
        $data['csi_codes_array'] = $this->getKeyArrayJson($data['csi_codes'], 'value');
        $data['total'] = $total;
        $data['groups'] = $groups;
        $data['mode'] = $mode;
        $data["particular_column"] = json_decode($template->particular_column, 1);

        return view('app/merchant/invoice/invoice-particular-new', $data);
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
}
