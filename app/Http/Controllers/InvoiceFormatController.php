<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libraries\Encrypt;
use App\Model\InvoiceFormat;
use App\Model\InvoiceColumnMetadata;
use App\Http\Controllers\AppController;
use Illuminate\Support\Facades\Session;
use App\Http\Traits\InvoiceFormatTrait;
use Validator;
use Exception;
use App\Model\Invoice;

class InvoiceFormatController extends AppController
{
    use InvoiceFormatTrait;
    private $invoiceModel;
    private $formatModel;
    public function __construct()
    {
        $this->invoiceModel = new Invoice();
        parent::__construct();
        $this->formatModel = new InvoiceFormat();
    }
    /**
     * Renders form to create invoice format
     *
     * @param $link - Encrypted link of system invoice format
     * 
     * @return void
     */
    public function create($link, Request $request)
    {
        $template_id = Encrypt::decode($link);

        if (strlen($template_id) != 10) {
            return redirect('/404');
        }

        $data = $this->setBladeProperties('Create format', [], [1]);
        #get invoice format detail
        $data['detail'] = $this->formatModel->getTableRow('system_template', 'system_template_id', $template_id);
        $data['detail']->template_name = '';
        $data['detail']->default_particular = '';
        $data['detail']->default_tax = '';

        // $data['detail']->plugin = '';

        #get default billing profile
        $defaultProfile = $this->formatModel->getMerchantDefaultProfile($this->merchant_id);
        $data['defaultProfileId'] = ($defaultProfile != false) ? $defaultProfile->id : 0;

        #get pre define system column metadata
        $metarows = $this->formatModel->getSystemMetadata($template_id);
        $data['metadata'] = $this->setMetadata($metarows);

        foreach ($data['metadata']['H'] as $key => $row) {
            if ($row->function_id == 9) {
                $data['metadata']['H'][$key]->param = 'system_generated';
            }
        }
        $data['metadata']['M'] = [];

        #get pre define system fields
        $formatCol = $this->formatModel->getTemplateMandatoryFields();
        $data['formatColumns'] = $this->setMetadata($formatCol);
        $data['template_id'] = '';
        $data['vd_sec'] = true;
        $data['link'] = $link;
        $data['jsfile'] = ['template', 'coveringnote'];
        $data['vd_title'] = "VEHICLE DETAILS";
        $data['richtext'] = true;
        $data['mode'] = 'create';
        $data['detail']->custmized_receipt_fields = '';
        $data['defaultReceiptFields'] = $this->setDefaultReceiptFields();
        $data['logo'] = '';
        if (isset($request->design_name)) {
            if ($request->design_name == 'travel') {
                $data['designname'] = '';
            } else {
                $data['designname'] =  isset($request->design_name) ? $request->design_name : '';
            }
        }

        $data['colorname'] =  isset($request->design_color) ? $request->design_color : '';
        $data['footernote'] = 'Thank you for your purchase.';
        $formateData = '';
        if (isset($request->design_name))
            $formateData = $this->formatModel->getSystemTemplateDesignName($request->design_name);

        if ($formateData) {
            $title = (array)$formateData;
            $data['typename'] = $title['title'];
        } else
            $data['typename'] = '';

        $data['from'] =  'create';
        $added_available_fields = array();
        $data['drawCustomerRows'] = '';
        $data['drawBillingRows'] = '';
        $data['available_fields'] = json_encode($added_available_fields, 1);

        $data['customerColumns'] = $this->formatModel->getTableList('customer_mandatory_column', 'is_active', 1);

        $data["product_list"] = [];
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

        return view('app/merchant/invoiceformat/create', $data);
    }

    public function chooseDesign($from, $link)
    {

        $template_id = Encrypt::decode($link);

        if (strlen($template_id) == 10) {
            $data = $this->setBladeProperties('Create format', [], [1]);
            $formateData = $this->formatModel->getSystemTemplateDesign();
            $data['links'] = $link;
            $data['from'] = $from;
            $data['templateList'] = json_decode($formateData, 1);

            return view('app/merchant/invoiceformat/choose-design', $data);
        } else {
            return redirect('404', 404);
        }
    }

    public function chooseColor($from, $design, $colors, $link)
    {

        $template_id = Encrypt::decode($link);

        if (strlen($template_id) == 10) {
            $data = $this->setBladeProperties('Create format', [], [1]);
            #get default billing profile

            $defaultProfile = $this->formatModel->getMerchantDefaultProfile($this->merchant_id);


            $data['links'] = $link;
            $data['formatename'] = $design;
            $data['from'] = $from;
            $data['colors'] = $colors;

            $merchant_header[] = array('column_name' => 'Company name', 'value' => $defaultProfile->company_name);


            $merchant_header[] = array('column_name' => 'Merchant address', 'value' => $defaultProfile->address);
            $merchant_header[] = array('column_name' => 'Merchant email', 'value' => $defaultProfile->business_email);
            $merchant_header[] = array('column_name' => 'Merchant contact', 'value' => $defaultProfile->business_contact);

            $customer_details[] = array('column_name' => 'Customer code', 'value' => 'CUST-001');
            $customer_details[] = array('column_name' => 'Customer name', 'value' => 'Rohit Sharma');
            $customer_details[] = array('column_name' => 'Email ID', 'value' => 'rohit@swipez.in');
            $customer_details[] = array('column_name' => 'Mobile No', 'value' => '7303885709');

            $invoice_details[] = array('column_name' => 'Invoice', 'value' => 'INV-001', 'function_id' => '9', 'datatype' => 'text', 'position' => 'R', 'column_position' => '5');
            $invoice_details[] = array('column_name' => 'Bill date', 'value' => '31 May 2022', 'function_id' => '0', 'datatype' => 'date', 'position' => 'R', 'column_position' => '0');
            $invoice_details[] = array('column_name' => 'Due date', 'value' => '31 May 2022', 'function_id' => '0', 'datatype' => 'date', 'position' => 'R', 'column_position' => '0');

            $vehicle_details[] = array('column_name' => 'Vehicle type', 'value' => 'Sedan', 'position' => 'L', 'type' => 'BDS', 'column_datatype' => 'text');
            $vehicle_details[] = array('column_name' => 'Vehicle no', 'value' => 'MH 12 GS 1454', 'position' => 'L', 'type' => 'BDS', 'column_datatype' => 'text');
            $vehicle_details[] = array('column_name' => 'Total days', 'value' => '2', 'position' => 'L', 'type' => 'BDS', 'column_datatype' => 'text');
            $vehicle_details[] = array('column_name' => 'Booked by', 'value' => 'Rohit', 'position' => 'L', 'type' => 'BDS', 'column_datatype' => 'text');
            $vehicle_details[] = array('column_name' => 'Duty Slip No', 'value' => '5678', 'position' => 'L', 'type' => 'BDS', 'column_datatype' => 'text');
            $vehicle_details[] = array('column_name' => 'Destination', 'value' => 'Mumbai', 'position' => 'L', 'type' => 'BDS', 'column_datatype' => 'text');
            $vehicle_details[] = array('column_name' => 'Start Kms', 'value' => '65656', 'position' => 'R', 'type' => 'BDS', 'column_datatype' => 'text');
            $vehicle_details[] = array('column_name' => 'Close Kms', 'value' => '65785', 'position' => 'R', 'type' => 'BDS', 'column_datatype' => 'text');
            $vehicle_details[] = array('column_name' => 'Start date', 'value' => '31 May 2022', 'position' => 'R', 'type' => 'BDS', 'column_datatype' => 'text');
            $vehicle_details[] = array('column_name' => 'End date', 'value' => '65656', 'position' => 'R', 'type' => 'BDS', 'column_datatype' => 'text');
            $vehicle_details[] = array('column_name' => 'Start time', 'value' => '09:00 AM', 'position' => 'R', 'type' => 'BDS', 'column_datatype' => 'text');
            $vehicle_details[] = array('column_name' => 'End time', 'value' => '08:00 PM', 'position' => 'R', 'type' => 'BDS', 'column_datatype' => 'text');


            $travel_json = '{"vehicle_det_section":{"title":"VEHICLE DETAILS"},"vehicle_section":{"title":"VEHICLE BOOKING DETAILS","column":{"sr_no":"#","item":"Description","sac_code":"Sac Code","description":"Time period","gst":"GST","total_amount":"Absolute cost"}},"travel_section":{"title":"TRAVEL BOOKING DETAILS","column":{"sr_no":"#","booking_date":"Booking date","journey_date":"Journey date","name":"Name","type":"Type","from":"From","to":"To","amount":"Amt","charge":"Ser. ch.","total_amount":"Total"}},"travel_cancel_section":{"title":"TRAVEL BOOKING CANCELLATION","column":{"sr_no":"#","booking_date":"Booking date","journey_date":"Journey date","name":"Name","type":"Type","from":"From","to":"To","amount":"Amt","charge":"Ser. ch.","total_amount":"Total"}},"hotel_section":{"title":"HOTEL BOOKING DETAILS","column":{"sr_no":"#","item":"Description","from_date":"From date","to_date":"To date","qty":"Units","rate":"Rate","gst":"GST","total_amount":"Absolute cost"}},"facility_section":{"title":"FACILITIES DETAILS","column":{"sr_no":"#","item":"Description","from_date":"Date","qty":"Units","rate":"Rate","gst":"GST","total_amount":"Absolute cost"}}}';

            $data['metadata']['travel_particular'] = json_decode($travel_json, 1);

            $info['ticket_detail'][] = array('type' => '1', 'sr_no' => '1', 'item' => 'Sedan CAB', 'sac_code' => '9999', 'description' => 'Time period', 'rate' => '1000.00', 'gst' => '18%', 'total' => '1600.00', 'narrative' => 'Narrative', 'booking_date' => '29 July 2020', 'journey_date' => '29 July 2020', 'name' => 'Rakesh', 'vehicle_type' => 'Bus', 'from_station' => 'Pune', 'to_station' => 'Mumbai', 'amount' => '1500.00', 'charge' => '100.00', 'from_date' => '22 May 2022', 'to_date' => '22 May 2022', 'units' => '1');
            $info['ticket_detail'][] = array('type' => '1', 'sr_no' => '2', 'item' => 'Sedan CAB', 'sac_code' => '9999', 'description' => 'Time period', 'rate' => '1000.00', 'gst' => '18%', 'total' => '1600.00', 'narrative' => 'Narrative', 'booking_date' => '29 July 2020', 'journey_date' => '29 July 2020', 'name' => 'Vikas', 'vehicle_type' => 'Bus', 'from_station' => 'Pune', 'to_station' => 'Mumbai', 'amount' => '1500.00', 'charge' => '100.00', 'from_date' => '22 May 2022', 'to_date' => '22 May 2022', 'units' => '1');

            $info['ticket_detail'][] = array('type' => '2', 'sr_no' => '1', 'item' => 'Sedan CAB', 'sac_code' => '9999', 'description' => 'Time period', 'rate' => '1000.00', 'gst' => '18%', 'total' => '1100.00', 'narrative' => 'Narrative', 'booking_date' => '29 July 2020', 'journey_date' => '29 July 2020', 'name' => 'Tejas', 'vehicle_type' => 'Bus', 'from_station' => 'Pune', 'to_station' => 'Mumbai', 'amount' => '1500.00', 'charge' => '400.00', 'from_date' => '22 May 2022', 'to_date' => '22 May 2022', 'units' => '1');
            $info['ticket_detail'][] = array('type' => '3', 'sr_no' => '1', 'item' => 'Delux', 'sac_code' => '9999', 'description' => 'Delux', 'rate' => '2000.00', 'gst' => '18%', 'total' => '4000.00', 'narrative' => 'Narrative', 'booking_date' => '29 July 2020', 'journey_date' => '29 July 2020', 'name' => 'Paresh', 'vehicle_type' => 'Type', 'from_station' => '22 May 2022', 'to_station' => '22 May 2022', 'amount' => '200.00', 'charge' => '100.00', 'from_date' => '29 July 2020', 'to_date' => '31 July 2020', 'units' => '2');
            $info['ticket_detail'][] = array('type' => '4', 'sr_no' => '1', 'item' => 'Extra Bed', 'sac_code' => '9999', 'description' => 'Time period', 'rate' => '800.00', 'gst' => '18%', 'total' => '800.00', 'narrative' => 'Narrative', 'booking_date' => '29 July 2020', 'journey_date' => '29 July 2020', 'name' => 'Paresh', 'vehicle_type' => 'Type', 'from_station' => '22 May 2022', 'to_station' => '22 May 2022', 'amount' => '200.00', 'charge' => '100.00', 'from_date' => '29 July 2020', 'to_date' => '22 May 2022', 'units' => '1');

            $data['metadata']['header'] = $merchant_header;
            $data['metadata']['customer'] = $customer_details;
            $data['metadata']['invoice'] = $invoice_details;
            $data['metadata']['vehicle_details'] = $vehicle_details;

            $secarray = array();
            for ($i = 1; $i < 5; $i++) {
                $secarray[] = $i;

                $info['secarray'] = $secarray;
            }
            $data['metadata']['particular'][] = array('srno' => '1', 'item' => 'Particular', 'sac_code' => '9999', 'qty' => 1, 'rate' => '1000.00', 'gst' => '18%', 'total_amount' => '1000.00', "description" => "2 days");

            $data['metadata']['tax'][] = array('tax_name' => 'CGST@9%', 'tax_percent' => 9, 'applicable' => '1000.00', 'tax_amount' => '90.00');
            $data['metadata']['tax'][] = array('tax_name' => 'SGST@9%', 'tax_percent' => 9, 'applicable' => '1000.00', 'tax_amount' => '90.00');

            # travel invoice final summery details
            $data['metadata']['travel_tax'][] = array('title' => 'CGST@9%', 'percentage' => 9, 'taxable' => '14900.00', 'amount' => '432.00');
            $data['metadata']['travel_tax'][] = array('title' => 'SGST@9%', 'percentage' => 9, 'taxable' => '14900.00', 'amount' => '432.00');
            $logo = $this->invoiceModel->getColumnValue('merchant_landing', 'merchant_id', $this->merchant_id, 'logo');
            if ($logo != '') {
                $logo = env('APP_URL') . '/uploads/images/landing/' . $logo;
            } else {
                $logo = env('APP_URL') . '/uploads/images/logos/default-logo.png';
            }
            $info['image_path'] = $logo;
            $info['sub_total'] = '14,900.00';
            $info['grand_total'] = '15,764.00';
            $info['tavel_amount_words'] = 'Fifteen Thousand Seven Hundred Sixty-Four Rupees';
            #End finala summery details
            $info['hide_invoice_summary'] = 2;
            $info['currency_icon'] = '₹';

            $info['payment_request_status'] = 1;
            $info['main_company_name'] = '';
            $info['previous_due'] = '0.00';
            $info['basic_amount'] = '2000.00';
            $info['sec_col'] = array("A", "B", "C", "D", "E", "F", "G", "H", "I");
            $info['tax_amount'] = '360.00';
            $info['absolute_cost'] = '2360.00';
            $info['absolute_cost_words'] = 'Two Thousand Three Hundred Sixty Rupees';

            $info['invoice_number'] = 'INV-230';
            $info['due_date'] = '31 May 2022';
            $info['delivery_date'] = '31 May 2022';
            $info['bill_date'] = '31 May 2022';
            $info['billing_period'] = '30 April 2022 to 31 May 2022';

            $info['payment_mode'] = 'Bank Transfer';
            $info['transaction_id'] = 'H000000154';
            $info['pan'] = 'xxxxx';
            $info['tan'] = 'xxxxx';
            $info['gst_number'] = 'xxxxx';
            $info['cin_no'] = 'xxxxx';
            $info['its_from'] = 'preview';
            $info['invoice_type'] = '1';
            $info['narrative'] = 'xxxxxxxxxx';
            $info['footer_note'] = 'Thank you for your purchase.';

            $info['tnc'] = "<p class='text-sm mt-1'>1. Lorem Ipsum is simply dummy text of the printing.
            <br/>2. Text ever since the 1500s, when an unknown printer took.
            <br/>3. Survived not only five centuries, but also the leap into electronic
            <br/>4. Contrary to popular belief, Lorem Ipsum is not simply random text</p>";

            $data['info'] = $info;
            $data['tax_heders'] = [
                "tax_name" => "Tax name",
                "tax_percent" => "Percentage",
                "applicable" => "Applicable",
                "tax_amount" => "Amount"

            ];

            $data['table_heders'] = [
                "srno" => "#",
                "item" => "Item",
                "sac_code" => "Sac Code",
                "qty" => "Quantity",
                "rate" => "Unit Price",
                "gst" => "GST",
                "total_amount" => "Total"
            ];

            return view('app/merchant/invoiceformat/choose-color', $data);
        } else {
        }
    }


    public function update($link, Request $request)
    {

        $template_id = Encrypt::decode($link);
        if (strlen($template_id) == 10) {
            $data = $this->setBladeProperties('Update format', [], [1]);
            #get invoice format detail
            $data['detail'] = $this->formatModel->getTableRow('invoice_template', 'template_id', $template_id);
            #get billing profile
            $data['defaultProfileId'] = $data['detail']->profile_id;

            #get pre define system column metadata
            $metarows = $this->formatModel->getFormatMetadata($template_id);
            $data['metadata'] = $this->setMetadata($metarows);

            #get pre define system fields
            $formatCol = $this->formatModel->getTemplateMandatoryFields();
            $data['formatColumns'] = $this->setMetadata($formatCol);
            $data['vd_sec'] = false;
            $data['vd_title'] = "VEHICLE DETAILS";
            if ($data['detail']->template_type == 'travel') {
                $propertis = json_decode($data['detail']->properties, 1);
                if (isset($propertis['vehicle_det_section'])) {
                    $data['vd_sec'] = true;
                    $data['vd_title'] = $propertis['vehicle_det_section']['title'];
                }
            }
            $data['link'] = $link;
            $data['template_id'] = $template_id;
            $data['jsfile'] = ['template', 'coveringnote'];
            $data['richtext'] = true;
            $data['mode'] = 'update';
            $data['detail']->custmized_receipt_fields = '';
            $data['defaultReceiptFields'] = $this->setDefaultReceiptFields();
            $data['logo'] = '';
            $data['drawCustomerRows'] = '';
            $data['detail']->selected_template_name = $data['detail']->template_name;
            $data['drawBillingRows'] = '';
            $data['from'] =  'update';
            $data['footernote'] = isset($data['detail']->footer_note) ? $data['detail']->footer_note : '';

            if (isset($request->design_name)) {
                if ($request->design_name == 'travel') {
                    $data['designname'] = '';
                } else {
                    $data['designname'] =  isset($request->design_name) ? $request->design_name : '';
                }
                $data['colorname'] =  isset($request->design_color) ? $request->design_color : '';

                $formateData = '';
                if (isset($request->design_name))
                    $formateData = $this->formatModel->getSystemTemplateDesignName($request->design_name);

                if ($formateData) {
                    $title = (array)$formateData;
                    $data['typename'] = $title['title'];
                } else
                    $data['typename'] = '';
            } else {
                $data['designname'] =  isset($data['detail']->design_name) ? $data['detail']->design_name : '';
                $data['colorname'] =  isset($data['detail']->design_color) ? $data['detail']->design_color : '';

                $formateData = '';
                if (isset($data['detail']->design_name))
                    $formateData = $this->formatModel->getSystemTemplateDesignName($data['detail']->design_name);

                if ($formateData) {
                    $title = (array)$formateData;
                    $data['typename'] = $title['title'];
                } else
                    $data['typename'] = '';
            }

            #get configure receipt fields data if configure payment receipt fields plugin is on
            if (isset($data['detail']->plugin) && !empty($data['detail']->plugin)) {
                $plugin_value = json_decode($data['detail']->plugin, 1);
                foreach ($plugin_value as $pk => $pval) {
                    if ($pk == 'receipt_fields') {
                        if (!empty($pval)) {
                            $data['detail']->custmized_receipt_fields = $pval;
                        }
                    }
                }
            }

            $available_fields = array();
            if (!empty($data['detail']->custmized_receipt_fields) && $data['detail']->custmized_receipt_fields != '') {
                $keys = array_keys($data['defaultReceiptFields']);
                foreach ($data['detail']->custmized_receipt_fields as $k => $field) {
                    if (in_array($field['label'], $keys)) {
                        $added_default_fields[] = $field['label'];
                        $data['detail']->custmized_receipt_fields[$k]['is_mandatory'] = $data['defaultReceiptFields'][$field['label']]['is_mandatory'];
                        $data['detail']->custmized_receipt_fields[$k]['default_value'] = $data['defaultReceiptFields'][$field['label']]['default_value'];
                        $data['detail']->custmized_receipt_fields[$k]['parentNode'] = $data['defaultReceiptFields'][$field['label']]['parentNode'];
                        $data['detail']->custmized_receipt_fields[$k]['isDefault'] = 1;
                    } else {
                        $data['detail']->custmized_receipt_fields[$k]['is_mandatory'] = 0;
                        $data['detail']->custmized_receipt_fields[$k]['default_value'] = 'XXXXX';
                        $defaultValue = $this->formatModel->getColumnValue('invoice_column_metadata', 'column_id', $field['column_id'], 'column_type');
                        $data['detail']->custmized_receipt_fields[$k]['parentNode'] = ($defaultValue == 'C') ? 'customer' : 'billing';
                        $data['detail']->custmized_receipt_fields[$k]['isDefault'] = 0;
                        $available_fields[] = $field['label'];
                    }
                }

                //find array differnce 
                $drawCustomerRows = array();
                $drawBillingRows = array();

                $default_fields_not_added = array_diff($keys, $added_default_fields);
                if (!empty($default_fields_not_added)) {
                    foreach ($default_fields_not_added as $k => $val) {
                        if (in_array($val, $keys)) {
                            if ($data['defaultReceiptFields'][$val]['parentNode'] == 'customer') {
                                $drawCustomerRows[] = array('label' => $val, 'default_value' => $data['defaultReceiptFields'][$val]['default_value'], 'parentNode' => $data['defaultReceiptFields'][$val]['parentNode'], 'isDefault' => 1);
                            } else if ($data['defaultReceiptFields'][$val]['parentNode'] == 'billing') {
                                $drawBillingRows[] = array('label' => $val, 'default_value' => $data['defaultReceiptFields'][$val]['default_value'], 'parentNode' => $data['defaultReceiptFields'][$val]['parentNode'], 'isDefault' => 1);
                            }
                        }
                    }
                }
                $data['drawCustomerRows'] = json_encode($drawCustomerRows, 1);
                $data['drawBillingRows'] = json_encode($drawBillingRows, 1);
            }
            $data['available_fields'] = json_encode($available_fields, 1);

            $data["product_list"] = [];
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

            return view('app/merchant/invoiceformat/create', $data);
        } else {
            return redirect('/404');
        }
    }

    public function save(Request $request)
    {
        #validate form
        $validator = Validator::make($request->all(), [
            'uploaded_file' => 'mimes:jpeg,jpg,png,gif|max:500',
            'template_name' => 'required|max:45'
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return back()->withErrors($errors);
        }
        $logo = $this->uploadLogo($request);
        $template_id = $this->saveInvoiceFormat($request, $logo);
        $this->saveMetadata($request, $template_id);

        if ($request->has_customized_payment_receipt == 1) {
            //if configure payment receipt field is on and set the custmized receipt fields array
            if (isset($request->custmized_receipt_fields) && !empty($request->custmized_receipt_fields) && $request->custmized_receipt_fields != null) {
                $customized_receipt_fields = json_decode($request->custmized_receipt_fields);
                $i = 1;
                foreach ($customized_receipt_fields as $key => $val) {
                    $find_invoice_metadata_details = $this->formatModel->getInvoiceMetaDataColumnsDetails($template_id, $val->label);
                    if ($find_invoice_metadata_details != false) {
                        $receipt_fields[] = array('label' => $val->label, 'column_id' => $find_invoice_metadata_details, 'order' => $i);
                    } else {
                        $receipt_fields[] = array('label' => $val->label, 'column_id' => 0, 'order' => $i);
                    }
                    $i++;
                }
            } else {
                //if plugin is on and no configure receipt fields then add default fields 
                $defaultReceiptFields = $this->setDefaultReceiptFields();
                $i = 1;
                foreach ($defaultReceiptFields as $key => $val) {
                    $find_invoice_metadata_details = $this->formatModel->getInvoiceMetaDataColumnsDetails($template_id, $val['label']);
                    if ($find_invoice_metadata_details != false) {
                        $receipt_fields[] = array('label' => $val['label'], 'column_id' => $find_invoice_metadata_details, 'order' => $i);
                    } else {
                        $receipt_fields[] = array('label' => $val['label'], 'column_id' => 0, 'order' => $i);
                    }
                    $i++;
                }
            }

            $get_exist_plugin_value = $this->formatModel->getColumnValue('invoice_template', 'template_id', $template_id, 'plugin');
            $plugin_value = json_decode($get_exist_plugin_value, 1);
            $plugin_value['receipt_fields'] = $receipt_fields;

            $new_plugin_value = json_encode($plugin_value, 1);
            $this->formatModel->updateTable('invoice_template', 'template_id', $template_id, 'plugin', $new_plugin_value);
        }

        return redirect('/merchant/template/viewlist');
    }

    function saveInvoiceFormat($request, $logo, $merchant_id = null, $user_id = null)
    {

        $pcarray = [];
        if (isset($request->particular_col)) {
            foreach ($request->particular_col as $pc) {
                $pcarray[$pc] = $_POST['pc_' . $pc];
            }
        }
        $travelConfig = null;
        if ($request->template_type == 'travel') {
            $travelConfig = $this->travelConfig($request, $pcarray);
        }
        $type = 'create';
        if ($request->template_id != '') {
            $template_id = $request->template_id;

            $type = 'update';
        } else {
            $template_id = $this->formatModel->getSequenceId('Template_id');
        }
        $particular_column = json_encode($pcarray);

        if ($request->template_type == 'construction') {
            $particular_column = '{"bill_code":"Bill Code","description":"Desc","bill_type":"Bill Type","cost_type":"Cost Type","original_contract_amount":"Original Contract Amount","approved_change_order_amount":"Approved Change Order Amount","current_contract_amount":"Current Contract Amount","previously_billed_percent":"Previously Billed Percent","previously_billed_amount":"Previously Billed Amount","current_billed_percent":"Current Billed Percent","current_billed_amount":"Current Billed Amount","previously_stored_materials":"Previously Stored Materials","current_stored_materials":"Current Stored Materials","stored_materials":"Materials Presently Stored","total_billed":"Total Billed (including this draw)","retainage_percent":"Retainage %","retainage_amount_previously_withheld":"Retainage Amount Previously Withheld","retainage_amount_for_this_draw":"Retainage amount for this draw","retainage_percent_stored_materials":"Retainage % Stored Materials","retainage_amount_previously_stored_materials":"Retainage Amount Previously Stored Materials","retainage_amount_stored_materials":"Retainage amount Stored Materials","net_billed_amount":"Net Billed Amount","retainage_release_amount":"Retainage Release Amount","retainage_stored_materials_release_amount":"Retainage Release Amount Stored Materials","total_outstanding_retainage":"Total outstanding retainage","project":"Project","cost_code":"Cost Code","group":"Group","bill_code_detail":"Bill code detail"}';
        }

        $data['template_id'] = $template_id;
        if ($merchant_id == null) {
            $data['merchant_id'] = $this->merchant_id;
        } else {
            $data['merchant_id'] = $merchant_id;
        }
        if ($user_id == null) {
            $data['user_id'] = $this->user_id;
        } else {
            $data['user_id'] = $user_id;
        }
        $data['template_name'] = $request->template_name;
        $data['template_type'] = $request->template_type;
        $data['particular_column'] = $particular_column;
        $data['default_particular'] = json_encode($request->particularname);
        $data['default_tax'] = json_encode($request->tax_id);
        $data['particular_total'] = 'Particular total';
        $data['tax_total'] = 'Tax total';
        $data['tnc'] = $request->tnc;
        $data['properties'] = $travelConfig;
        $data['design_name'] = $request->design_name;
        $data['design_color'] = $request->design_color;
        $data['footer_note'] = $request->template_fooer_msg;
        $data['plugin'] = $this->getPlugins();
        $data['profile_id'] = ($request->billingProfile_id > 0) ?  $request->billingProfile_id : 0;
        $data['image_path'] = $logo;
        $data['invoice_title'] = 'Performa Invoice';
        $data['created_date'] = date('Y-m-d');
        $data['created_by'] = $this->user_id;
        $data['last_update_by'] = $this->user_id;

        if ($type == 'update') {
            InvoiceFormat::where('template_id',  $template_id)->update($data);
            InvoiceColumnMetadata::where('template_id',  $template_id)->update(['is_active' => 0]);
        } else {
            InvoiceFormat::insert($data);
        }

        return $template_id;
    }




    public function saveMetadata($request, $template_id)
    {
        $metadata = [];
        $updatemetadata = [];
        $int = 1;
        #main header company details
        if (!empty($request->main_header_id)) {
            foreach ($request->main_header_id as $k => $mid) {
                $array['column_datatype'] = $_POST['main_header_datatype'][$k];
                $array['column_name'] = $_POST['main_header_name'][$k];
                $array['sort_order'] = $int;
                $array['position'] = 'R';
                $array['is_mandatory'] = 0;
                $array['is_delete_allow'] = 1;
                $array['function_id'] = 0;
                $array['is_active'] = 1;
                $array['default_column_value'] = 'Profile';
                $array['customer_column_id'] = null;
                $array['column_type'] = 'M';
                $array['save_table_name'] = 'metadata';
                $array['column_position'] = $mid;
                $array['template_id'] = $template_id;
                $array['created_date'] = date('Y-m-d');
                $array['created_by'] = $this->user_id;
                $array['last_update_by'] = $this->user_id;
                $col_id = $_POST['main_header_column_id'][$k];
                if ($col_id > 0) {
                    $updatemetadata[$col_id] = $array;
                } else {
                    $metadata[] = $array;
                }
                $int++;
            }
        }
        #Customer column details
        foreach ($request->customer_column_id as $k => $id) {
            $array['column_datatype'] = $_POST['customer_datatype'][$k];
            $array['column_name'] = $_POST['customer_column_name'][$k];
            $array['sort_order'] = $int;
            $array['position'] = 'L';
            $array['is_mandatory'] = 0;
            $array['is_delete_allow'] = 1;
            $array['function_id'] = 0;
            $array['is_active'] = 1;
            $array['default_column_value'] = null;
            $array['customer_column_id'] = $id;
            $array['column_type'] = 'C';
            $array['save_table_name'] = ($_POST['customer_column_type'][$k] == 'customer') ? "customer" : "customer_metadata";
            $array['column_position'] = $k + 1;
            $array['template_id'] = $template_id;
            $array['created_date'] = date('Y-m-d');
            $array['created_by'] = $this->user_id;
            $array['last_update_by'] = $this->user_id;
            $col_id = $_POST['cust_column_id'][$k];
            if ($col_id > 0) {
                $updatemetadata[$col_id] = $array;
            } else {
                $metadata[] = $array;
            }
            $int++;
        }
        $function_array = [];
        #Invoice details
        foreach ($request->headercolumn as $k => $name) {
            $config = json_decode($_POST['column_config'][$k], 1);
            $array['column_datatype'] = $config['headerdatatype'];
            $array['column_name'] = $name;
            $array['sort_order'] = $int;
            $array['position'] = $config['position'];
            $array['is_mandatory'] = $config['headermandatory'];
            $array['is_delete_allow'] = $config['headerisdelete'];
            $array['function_id'] = ($config['function_id'] > 0) ? $config['function_id'] : 0;
            $array['is_active'] = 1;
            $array['default_column_value'] = null;
            $array['customer_column_id'] = null;
            $array['column_type'] = $config['column_type'];
            $array['save_table_name'] = $config['headertablesave'];
            $array['column_position'] = $config['headercolumnposition'];
            $array['template_id'] = $template_id;
            $array['created_date'] = date('Y-m-d');
            $array['created_by'] = $this->user_id;
            $array['last_update_by'] = $this->user_id;
            if ($config['function_param'] != '') {
                $function_array[$int - 1]['function_id'] = $config['function_id'];
                $function_array[$int - 1]['function_param'] = $config['function_param'];
                $function_array[$int - 1]['function_val'] = $config['function_val'];
            }
            $col_id = $_POST['column_id'][$k];
            if ($col_id > 0) {
                $updatemetadata[$col_id] = $array;
            } else {
                $metadata[] = $array;
            }
            $int++;
        }
        if (!empty($updatemetadata)) {
            foreach ($updatemetadata as $col_id => $data) {
                InvoiceColumnMetadata::where('column_id',  $col_id)->update($data);
            }
        }
        if (!empty($metadata)) {
            InvoiceColumnMetadata::insert($metadata);
        }
        if (!empty($function_array)) {
            foreach ($function_array as $k => $v) {
                if ($v['function_id'] > 0) {
                    $column_ids = InvoiceColumnMetadata::where('template_id', $template_id)->where('is_active', 1)->where('function_id', '=', $v['function_id'])->first('column_id');
                    if ($column_ids != null) {
                        $column_ids = $column_ids->toArray();
                        $column_id = $column_ids['column_id'];
                        InvoiceColumnMetadata::saveFunctionMapping($column_id, $v['function_id'], $v['function_param'], $v['function_val'], $this->user_id);
                    }
                }
            }
        }
    }

    public function saveMetadataV2($request, $template_id, $merchant_id, $user_id)
    {
        $metadata = [];
        $updatemetadata = [];
        $int = 1;
        #main header company details
        if (!empty($request->main_header_id)) {
            foreach ($request->main_header_id as $k => $mid) {
                $array['column_datatype'] = $request->main_header_datatype[$k];
                $array['column_name'] = $request->main_header_name[$k];
                $array['sort_order'] = $int;
                $array['position'] = 'R';
                $array['is_mandatory'] = 0;
                $array['is_delete_allow'] = 1;
                $array['function_id'] = 0;
                $array['is_active'] = 1;
                $array['default_column_value'] = 'Profile';
                $array['customer_column_id'] = null;
                $array['column_type'] = 'M';
                $array['save_table_name'] = 'metadata';
                $array['column_position'] = $mid;
                $array['template_id'] = $template_id;
                $array['created_date'] = date('Y-m-d');
                $array['created_by'] = $user_id;
                $array['last_update_by'] = $user_id;
                $col_id = $request->main_header_column_id[$k];
                if ($col_id > 0) {
                    $updatemetadata[$col_id] = $array;
                } else {
                    $metadata[] = $array;
                }
                $int++;
            }
        }
        #Customer column details
        foreach ($request->customer_column_id as $k => $id) {
            $array['column_datatype'] = $request->customer_datatype[$k];
            $array['column_name'] = $request->customer_column_name[$k];
            $array['sort_order'] = $int;
            $array['position'] = 'L';
            $array['is_mandatory'] = 0;
            $array['is_delete_allow'] = 1;
            $array['function_id'] = 0;
            $array['is_active'] = 1;
            $array['default_column_value'] = null;
            $array['customer_column_id'] = $id;
            $array['column_type'] = 'C';
            $array['save_table_name'] = ($request->customer_column_type[$k] == 'customer') ? "customer" : "customer_metadata";
            $array['column_position'] = $k + 1;
            $array['template_id'] = $template_id;
            $array['created_date'] = date('Y-m-d');
            $array['created_by'] = $user_id;
            $array['last_update_by'] = $user_id;
            $col_id = $request->cust_column_id[$k];
            if ($col_id > 0) {
                $updatemetadata[$col_id] = $array;
            } else {
                $metadata[] = $array;
            }
            $int++;
        }
        $function_array = [];
        #Invoice details
        foreach ($request->headercolumn as $k => $name) {
            $config = json_decode($request->column_config[$k], 1);
            $array['column_datatype'] = $config['headerdatatype'];
            $array['column_name'] = $name;
            $array['sort_order'] = $int;
            $array['position'] = $config['position'];
            $array['is_mandatory'] = $config['headermandatory'];
            $array['is_delete_allow'] = $config['headerisdelete'];
            $array['function_id'] = ($config['function_id'] > 0) ? $config['function_id'] : 0;
            $array['is_active'] = 1;
            $array['default_column_value'] = null;
            $array['customer_column_id'] = null;
            $array['column_type'] = $config['column_type'];
            $array['save_table_name'] = $config['headertablesave'];
            $array['column_position'] = $config['headercolumnposition'];
            $array['template_id'] = $template_id;
            $array['created_date'] = date('Y-m-d');
            $array['created_by'] = $this->user_id;
            $array['last_update_by'] = $this->user_id;
            if ($config['function_param'] != '') {
                $function_array[$int - 1]['function_id'] = $config['function_id'];
                $function_array[$int - 1]['function_param'] = $config['function_param'];
                $function_array[$int - 1]['function_val'] = $config['function_val'];
            }
            $col_id = $request->column_id[$k];
            if ($col_id > 0) {
                $updatemetadata[$col_id] = $array;
            } else {
                $metadata[] = $array;
            }
            $int++;
        }
        if (!empty($updatemetadata)) {
            foreach ($updatemetadata as $col_id => $data) {
                InvoiceColumnMetadata::where('column_id',  $col_id)->update($data);
            }
        }
        if (!empty($metadata)) {
            InvoiceColumnMetadata::insert($metadata);
        }
        if (!empty($function_array)) {
            foreach ($function_array as $k => $v) {
                if ($v['function_id'] > 0) {
                    $column_ids = InvoiceColumnMetadata::where('template_id', $template_id)->where('is_active', 1)->where('function_id', '=', $v['function_id'])->first('column_id');
                    if ($column_ids != null) {
                        $column_ids = $column_ids->toArray();
                        $column_id = $column_ids['column_id'];
                        InvoiceColumnMetadata::saveFunctionMapping($column_id, $v['function_id'], $v['function_param'], $v['function_val'], $this->user_id);
                    }
                }
            }
        }
    }

    function travelConfig($request, $pcarray)
    {
        $config = [];
        if ($request->sec_vehicle_det == 1) {
            $config['vehicle_det_section']['title'] = $request->sec_vehicle_det_name;
        }
        if ($request->sec_vehicle == 1) {
            $config['vehicle_section']['title'] = $request->sec_vehicle_name;
            $config['vehicle_section']['column'] = $pcarray;
        }
        if ($request->sec_travel == 1) {
            $config['travel_section']['title'] = $request->sec_travel_name;
            $array = [];
            foreach ($request->tb_col as $pc) {
                $array[$pc] = $_POST['tb_' . $pc];
            }
            $config['travel_section']['column'] = $array;

            $config['travel_cancel_section']['title'] = $request->sec_travel_cancel_name;
            $array = [];
            foreach ($request->tc_col as $pc) {
                $array[$pc] = $_POST['tc_' . $pc];
            }
            $config['travel_cancel_section']['column'] = $array;
        }
        if ($request->sec_hotel == 1) {
            $config['hotel_section']['title'] = $request->sec_hotel_name;
            $array = [];
            foreach ($request->hb_col as $pc) {
                $array[$pc] = $_POST['hb_' . $pc];
            }
            $config['hotel_section']['column'] = $array;
        }
        if ($request->sec_facility == 1) {
            $config['facility_section']['title'] = $request->sec_facility_name;
            $array = [];
            foreach ($request->fs_col as $pc) {
                $array[$pc] = $_POST['fs_' . $pc];
            }
            $config['facility_section']['column'] = $array;
        }
        if (!empty($config)) {
            return json_encode($config);
        } else {
            return null;
        }
    }

    function getPlugins()
    {
        $this->setZeroValue(array('is_debit','has_mandatory_upload', 'has_upload', 'has_signature', 'is_supplier', 'is_coupon', 'is_cc', 'is_roundoff', 'has_acknowledgement', 'franchise_notify_email', 'franchise_notify_sms', 'franchise_name_invoice', 'is_franchise', 'is_vendor', 'is_prepaid', 'has_autocollect', 'partial_min_amount', 'is_partial', 'default_covering', 'is_covering', 'is_custom_notification', 'is_custom_reminder', 'has_online_payments', 'has_customized_payment_receipt', 'has_e_invoice', 'is_revision', 'invoice_output', 'has_aia_license'));
        $this->setEmptyArray(array('debit', 'debitdefaultValue', 'supplier', 'cc', 'reminder', 'reminder_subject', 'reminder_sms'));
        $plugin = array();

        if ($_POST['is_debit'] == 1) {
            $plugin['has_deductible'] = $_POST['is_debit'];
            foreach ($_POST['debit'] as $key => $tax) {
                $plugin['deductible'][] = array('tax_name' => $tax, 'percent' => $_POST['debitdefaultValue'][$key]);
            }
        }
        if ($_POST['has_upload'] == 1) {
            $plugin['has_upload'] = 1;
            $plugin['upload_file_label'] = $_POST['upload_file_label'];
        }
        if ($_POST['has_mandatory_upload'] == 1) {
            $plugin['has_mandatory_upload'] = 1;
            foreach ($_POST['mandatory_document_name'] as $k => $v) {
                $plugin['mandatory_data'][$k]['name'] = $_POST['mandatory_document_name'][$k];
                $plugin['mandatory_data'][$k]['description'] = $_POST['mandatory_document_description'][$k];
                $plugin['mandatory_data'][$k]['required'] = $_POST['mandatory_document_action'][$k];
            }
        }
        if ($_POST['has_signature'] == 1) {
            $plugin['has_signature'] = 1;
        }
        if ($_POST['is_supplier'] == 1) {
            $plugin['has_supplier'] = $_POST['is_supplier'];
            $plugin['supplier'] = $_POST['supplier'];
        }
        if ($_POST['is_coupon'] == 1) {
            $plugin['has_coupon'] = $_POST['is_coupon'];
        }
        if ($_POST['is_cc'] == 1) {
            $plugin['has_cc'] = $_POST['is_cc'];
            $plugin['cc_email'] = $_POST['cc'];
        }
        if ($_POST['is_roundoff'] == 1) {
            $plugin['roundoff'] = $_POST['is_roundoff'];
        }
        if ($_POST['has_acknowledgement'] == 1) {
            $plugin['has_acknowledgement'] = $_POST['has_acknowledgement'];
        }
        if ($_POST['is_franchise'] == 1) {
            $plugin['has_franchise'] = $_POST['is_franchise'];
            $plugin['franchise_name_invoice'] = $_POST['franchise_name_invoice'];
            $plugin['franchise_notify_email'] = $_POST['franchise_notify_email'];
            $plugin['franchise_notify_sms'] = $_POST['franchise_notify_sms'];
        }
        if ($_POST['is_vendor'] == 1) {
            $plugin['has_vendor'] = $_POST['is_vendor'];
        }
        if ($_POST['is_prepaid'] == 1) {
            $plugin['is_prepaid'] = $_POST['is_prepaid'];
        }
        if ($_POST['has_autocollect'] == 1) {
            $plugin['has_autocollect'] = $_POST['has_autocollect'];
        }
        if ($_POST['is_partial'] == 1) {
            $plugin['has_partial'] = $_POST['is_partial'];
            $plugin['partial_min_amount'] = $_POST['partial_min_amount'];
        }
        if ($_POST['is_covering'] == 1) {
            $plugin['has_covering_note'] = $_POST['is_covering'];
            $plugin['default_covering_note'] = $_POST['default_covering'];
        }
        if ($_POST['is_custom_notification'] == 1) {
            $plugin['has_custom_notification'] = $_POST['is_custom_notification'];
        }
        if ($_POST['is_custom_notification'] == 1) {
            $plugin['custom_email_subject'] = $_POST['custom_subject'];
            $plugin['custom_sms'] = $_POST['custom_sms'];
        }
        if ($_POST['is_custom_reminder'] == 1) {
            $plugin['has_custom_reminder'] = $_POST['is_custom_reminder'];
            foreach ($_POST['reminder'] as $key => $day) {
                $plugin['reminders'][$day] = array('email_subject' => $_POST['reminder_subject'][$key], 'sms' => $_POST['reminder_sms'][$key]);
            }
        }
        if ($_POST['has_online_payments'] == 1) {
            $plugin['has_online_payments'] = $_POST['has_online_payments'];
            $plugin['enable_payments'] = $_POST['enable_payments'];
        }
        // if ($_POST['has_online_payments'] == 0) {
        //     $plugin['has_online_payments'] = $_POST['has_online_payments'];
        //     $plugin['enable_payments'] = "0";
        // }

        if ($_POST['has_customized_payment_receipt'] == 1) {
            $plugin['has_customized_payment_receipt'] = $_POST['has_customized_payment_receipt'];
            //$plugin['receipt_fields_col_id'] = array();
        }

        if (isset($_POST['is_einvoice'])) {
            $plugin['has_e_invoice'] = $_POST['is_einvoice'];
        }
        if (isset($_POST['is_revision'])) {
            if ($_POST['is_revision'] == 1) {
                $plugin['save_revision_history'] = $_POST['is_revision'];
            }
        }

        if ($_POST['invoice_output'] == 1) {
            $plugin['invoice_output'] = $_POST['invoice_output'];
        }

        if ($_POST['has_aia_license'] == 1) {
            $plugin['has_aia_license'] = $_POST['has_aia_license'];
        }

        if (empty($plugin)) {
            return null;
        }

        return json_encode($plugin);
    }

    function uploadLogo($request)
    {
        try {
            if ($request->hasFile('uploaded_file')) {
                $file = $request->file('uploaded_file');
                $file_name = Encrypt::encode(time()) . '.' . $file->extension();
                $file->move('uploads/images/logos/', $file_name);
                return $file_name;
            } else {
                if (isset($request->logo) && $request->logo != '') {
                    return $request->logo;
                }
                return null;
            }
        } catch (Exception $e) {
            app('sentry')->captureException($e);
            return null;
        }
    }

    public function savePluginValue(Request $request)
    {
        $pluginValue = array();
        try {
            if (!empty($request->label)) {
                foreach ($request->label as $k => $lbl) {
                    $pluginValue[] = array("label" => $lbl);
                }
            }

            if (!empty($pluginValue)) {
                $response['plginValue'] = json_encode($pluginValue, 1);
                $response['status'] = 1;
                echo json_encode($response, 1);
            } else {
                $response['status'] = 0;
                echo json_encode($response, 1);
            }
        } catch (Exception $e) {
            app('sentry')->captureException($e);
            return null;
        }
    }

    public function setDefaultReceiptFields()
    {
        $defaultReceiptFields = array();
        if (Session::has('customer_default_column')) {
            $default_column = Session::get('customer_default_column');
            if (isset($default_column['customer_code'])) {
                $defaultReceiptFields[$default_column['customer_code']] = array('label' => $default_column['customer_code'], 'default_value' => 'Cust-124', 'is_mandatory' => 0, 'parentNode' => 'customer');
            }
            if (isset($default_column['customer_name'])) {
                $defaultReceiptFields[$default_column['customer_name']] = array('label' => $default_column['customer_name'], 'default_value' => 'Rohit Sharma', 'is_mandatory' => 1, 'parentNode' => 'customer');
            }
            if (isset($default_column['email'])) {
                $defaultReceiptFields[$default_column['email']] = array('label' => $default_column['email'], 'default_value' => 'rohitsharmabills@gmail.com', 'is_mandatory' => 0, 'parentNode' => 'customer');
            }
        } else {
            $defaultReceiptFields['Customer code'] = array('label' => 'Customer code', 'default_value' => 'Cust-124', 'is_mandatory' => 0, 'parentNode' => 'customer');
            $defaultReceiptFields['Patron Name'] = array('label' => 'Patron Name', 'default_value' => 'Rohit Sharma', 'is_mandatory' => 1, 'parentNode' => 'customer');
            $defaultReceiptFields['Patron Email ID'] = array('label' => 'Patron Email ID', 'default_value' => 'rohitsharmabills@gmail.com', 'is_mandatory' => 0, 'parentNode' => 'customer');
        }

        $defaultReceiptFields['Payment Towards'] = array('label' => 'Payment Towards', 'default_value' => 'Super Company', 'is_mandatory' => 0, 'parentNode' => 'billing');
        $defaultReceiptFields['Payment Ref Number'] = array('label' => 'Payment Ref Number', 'default_value' => 'HXXXXXXXXX', 'is_mandatory' => 1, 'parentNode' => 'billing');
        $defaultReceiptFields['Transaction Ref Number'] = array('label' => 'Transaction Ref Number', 'default_value' => '1XXXXXX', 'is_mandatory' => 1, 'parentNode' => 'billing');
        $defaultReceiptFields['Payment Date & Time'] = array('label' => 'Payment Date & Time', 'default_value' => '2021-12-10 18:02:14', 'is_mandatory' => 1, 'parentNode' => 'billing');
        $defaultReceiptFields['Payment Amount'] = array('label' => 'Payment Amount', 'default_value' => '<i class="fa fa-inr"></i>' . '1000', 'is_mandatory' => 1, 'parentNode' => 'billing');
        $defaultReceiptFields['Mode of Payment'] = array('label' => 'Mode of Payment', 'default_value' => 'Cash', 'is_mandatory' => 0, 'parentNode' => 'billing');

        return $defaultReceiptFields;
    }
}
