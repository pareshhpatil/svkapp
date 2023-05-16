<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libraries\Encrypt;
use App\Model\InvoiceFormat;
use App\Model\Invoice;
use App\Model\ParentModel;
use DOMPDF;
use App\Libraries\Helpers;
use App\Model\InvoiceColumnMetadata;
use App\Http\Controllers\AppController;
use App\Http\Traits\InvoiceFormatTrait;
use Validator;
use Exception;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Redis;
use Numbers_Words;
use View;

class LegacyInvoiceController extends AppController
{
    private $formatModel;
    use InvoiceFormatTrait;

    private $invoiceModel;
    private $parentModel;
    public function __construct()
    {

        parent::__construct();

        $this->invoiceModel = new Invoice();
        $this->parentModel = new ParentModel();
        $this->formatModel = new InvoiceFormat();
        $this->inventory_service_id = Encrypt::encode('15'); //15 service_id

    }
    /**
     * Renders form to create invoice 
     *
     * @param $type - Invoice or Estimate or Subscription
     * 
     * @return void
     */
    public function create(Request $request, $type = 'invoice', $invoice_type = 1)
    {


        if (isset($request->invoice_type)) {
            $invoice_type = $request->invoice_type;
        }
        $req_types = array('invoice' => 1, 'estimate' => 2, 'subscription' => 4,);
        $menus = array('invoice' => 19, 'estimate' => 122, 'subscription' => 21);
        if (!isset($req_types[$type])) {
            throw new Exception('Invalid invoice type ' . $type);
        }
        $menu = $menus[$type];
        $data = $this->setBladeProperties('Create ' . $type, ['invoiceformat', 'template', 'coveringnote', 'product', 'subscription'], [3, $menu]);
        #get merchant invoice format list
        $data['format_list'] = $this->invoiceModel->getMerchantFormatList($this->merchant_id, $type);
        if (count($data['format_list']) == 1) {
            $request->template_id = $data['format_list']->first()->template_id;
        }

        $data['project'] = $this->invoiceModel->getMerchantValues($this->merchant_id, 'project');
        $data['project_id'] = '';

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
        $breadcrumbs['menu'] = 'collect_payments';
        $breadcrumbs['title'] = $data['title'];
        $breadcrumbs['url'] = '/merchant/invoice/create/' . $type;

        if (env('ENV') != 'LOCAL') {
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
            $data = $this->setInvoiceData($data, $template_id, $request->billing_profile_id, $request->currency);

            #get pre define system column metadata
            $metarows = $formatModel->getFormatMetadata($template_id);
            $metadata = $this->setMetadata($metarows);
            if (isset($metadata['H'])) {
                $metadata['H'] = $this->setCreateFunction($metadata['H']);
                foreach ($metadata['H'] as $row) {
                    if (isset($row->script)) {
                        $data['script'] .= $row->script;
                    }
                }
            }

            $csi_codes = $this->invoiceModel->getBillCodes($request->project_id);

            $data['csi_codes'] = json_decode(json_encode($csi_codes), 1);
            $data['csi_codes_array'] = $this->getKeyArrayJson($data['csi_codes'], 'value');
            $data['metadata'] = $metadata;
            $data['project_id'] = $request->project_id;
            $data['customer_id'] =  $this->invoiceModel->getColumnValue('project', 'id', $request->project_id, 'customer_id');
            $data['customer'] =  $this->invoiceModel->getTableRow('customer', 'customer_id', $data['customer_id']);
            $data['mode'] = 'create';
            $data['cycleName'] = date('M-Y') . ' Bill';
            $data['plugin'] = json_decode($data['template_info']->plugin, 1);
            $data['properties'] = json_decode($data['template_info']->properties, 1);
            $data['setting'] = json_decode($data['template_info']->setting, 1);
        }

        return view('app/merchant/invoice/legacy-create', $data);
    }

    private function getKeyArrayJson($array, $key)
    {
        $data = [];
        foreach ($array as $row) {
            $data[$row[$key]] = $row;
        }
        return json_encode($data);
    }

    /**
     * Renders form to update invoice
     *
     * @param $link - encrypted link
     * 
     * @return void
     */
    public function update($link, $staging = 0)
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
            return view('app/merchant/invoice/update', $data);
        } else {
            return redirect('/error')->with('errorTitle', 'Invalid URL');
        }
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
            $imgpath =  '/uploads/images/logos/' . $info['image_path'];

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
            View::share('invoice_access', 'full');
            $data = $this->setdata($data, $info, $banklist, $payment_request_id);
            $data['info']['gtype'] = 'standard';
            $data['gtype'] = 'standard';
            $data['invoice_access'] = 'full';
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

            $imgpath =  '/uploads/images/logos/' . $info['image_path'];

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

            $data['info']['gtype'] = 'standard';
            $data['has_watermark'] = false;
            return view('app/merchant/invoice/view/invoice_view', $data);
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

    public function download($link, $savepdf = 0)
    {
        $payment_request_id = Encrypt::decode($link);

        if (strlen($payment_request_id) == 10) {
            $data = $this->setBladeProperties('Invoice view', [], [3]);
            #get default billing profile

            $info =  $this->invoiceModel->getInvoiceInfo($payment_request_id, 'customer');
            $info = (array)$info;
            $banklist = $this->parentModel->getConfigList('Bank_name');
            $banklist = json_decode($banklist, 1);
            $info['logo'] = '';
            if (isset($info['image_path'])) {
                $imgpath = env('APP_URL')  . '/uploads/images/logos/' . $info['image_path'];
                if ($info['image_path'] != '') {
                    $info['logo'] = base64_encode(file_get_contents($imgpath));
                }
            } else {
                $info['image_path'] = '';
            }

            $info['signimg'] = '';
            if (isset($info['signature']['signature_file'])) {
                $imgpath = env('APP_URL') . '/uploads/images/landing/' . $info['signature']['signature_file'];
                if ($info['signature']['signature_file'] != '') {
                    $info['signimg'] = base64_encode(file_get_contents($imgpath));
                }
            }

            $data = $this->setdata($data, $info, $banklist, $payment_request_id);
            if ($savepdf == 2) {
                $data['viewtype'] = 'print';
                return view('mailer/invoice/' . $info['design_name'], $data);
                die();
            } else {
                $data['viewtype'] = 'pdf';
                define("DOMPDF_ENABLE_HTML5PARSER", true);
                define("DOMPDF_ENABLE_FONTSUBSETTING", true);
                define("DOMPDF_UNICODE_ENABLED", true);
                define("DOMPDF_DPI", 120);
                define("DOMPDF_ENABLE_REMOTE", true);
                $pdf = DOMPDF::loadView('mailer.invoice.' . $info['design_name'], $data);
                $pdf->setPaper("a4", "portrait");
                $name = $info['customer_name'] . '_' . date('Y-M-d H:m:s');
                return $pdf->download($name . '.pdf');
            }
        } else {
        }
    }
    public function downloadPatron($link, $savepdf = 0)
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
                    $info['logo'] = base64_encode(file_get_contents($imgpath));
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
                return view('mailer/invoice/' . $info['design_name'], $data);
                die();
            } else {

                $data['viewtype'] = 'pdf';
                define("DOMPDF_ENABLE_HTML5PARSER", true);
                define("DOMPDF_ENABLE_FONTSUBSETTING", true);
                define("DOMPDF_UNICODE_ENABLED", true);
                define("DOMPDF_DPI", 120);
                define("DOMPDF_ENABLE_REMOTE", true);
                $pdf = DOMPDF::loadView('mailer.invoice.' . $info['design_name'], $data);
                $pdf->setPaper("a4", "portrait");
                $name = $info['customer_name'] . '_' . date('Y-M-d H:m:s');
                return $pdf->download($name . '.pdf');
            }
        } else {
        }
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
            $data = $this->setdata($data, $info, $banklist, $payment_request_id);

            $data['viewtype'] = 'mailer';
            Helpers::sendMail($info['customer_email'], 'invoice.' . $info['design_name'], $data, $subject);
        }
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

        $info['user_type'] = $user_type;
        $info['staging'] = $staging;
        $data['links'] = $payment_request_id;
        $data['formatename'] = $info['design_name'];
        $data['colors'] = $info['design_color'];

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
                $info['signature'] = $plugin['signature'];
            }
        }

        if ($info['display_url'] != '') {
            $merchant_page = env('APP_URL') . '/m/' . $info['display_url'];
        }


        if (empty($main_header)) {
            $main_header[] = array('column_name' => 'Company name', 'value' => $info['company_name']);
            $main_header[] = array('column_name' => 'Merchant email', 'value' => $info['business_email']);

            $main_header[] = array('column_name' => 'Merchant address', 'value' => $info['merchant_address']);
        }

        if (isset($info['plugin']['has_partial'])) {
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
            case 2:
                $info["error"] =  'This invoice has already been settled.';
                break;
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
        $info['user_name'] = Session::get('user_name');
        $data['metadata']['plugin'] = $plugin;
        $data['info'] = $info;
        $data['metadata']['header'] = $main_header;
        $data['metadata']['customer'] = $customer_breckup;
        $data['metadata']['invoice'] = $header;

        return $data;
    }
}
