<?php

/**
 * Invoice controller class to handle create and update invoice for patron
 */

use App\Libraries\Helpers;

class InvoiceWrapper
{

    protected $common = NULL;
    protected $encrypt = NULL;
    protected $session = NULL;
    public $bill_date = NULL;
    public $paid_date = NULL;
    public $app_url = '';

    function __construct($model)
    {
        $this->common = $model;
        $this->encrypt = new Encryption();
        if (BATCH_CONFIG == true) {
            $this->app_url = getenv('APP_URL');
        } else {
            $this->session = new SessionLegacy();
            $this->app_url = config('app.url');
        }
    }

    function asignSmarty($info, $banklist, $payment_request_id, $type = 'Invoice', $user_type = 'merchant', $staging = 0)
    {
        //$rows = $this->common->getTemplateMetadata($info['template_id']);
        $rows = $this->common->getTemplateInvoiceBreakup($payment_request_id, $staging);
        $cust_values = $this->common->getCustomerbreckup($info['customer_id']);
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
                    $smarty['previous_due'] = $row['value'];
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
                if ($row['default_column_value'] == 'Profile') {
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
                } else {
                    $value = $row['value'];
                    switch ($row['column_position']) {
                        case 10:
                            $info['business_contact'] = $value;
                            break;
                        case 11:
                            $info['business_email'] = $value;
                            break;
                        case 12:
                            $info['merchant_address'] = str_replace('|', '<br>', $value);
                            break;
                    }
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
                } else if ($row['save_table_name'] == 'customer_metadata' && $row['column_datatype'] == 'company_name') {
                    $value = $info['customer_company_name'];
                } else {
                    $value = $cust_values[$row['customer_column_id']];
                }
                $customer_breckup[] = array('column_name' => $row['column_name'], 'value' => $value);
            }
            if ($row['function_id'] == 4) {
                $smarty['previousdue'] = $row['value'];
                $smarty['previousdue_col'] = $row['column_name'];
            }
            if ($row['function_id'] == 12) {
                $smarty['adjustment'] = $row['value'];
                $smarty['adjustment_col'] = $row['column_name'];
            }
            if ($row['function_id'] == 14) {
                $smarty['discount'] = $row['value'];
                $smarty['discount_col'] = $row['column_name'];
            }
        }
        $plugin = json_decode($info['plugin_value'], 1);
        if ($info['franchise_id'] > 0) {
            if ($plugin['franchise_name_invoice'] == 1) {
                $smarty['main_company_name'] = $info['company_name'];
                $franchise = $this->common->getSingleValue('franchise', 'franchise_id', $info['franchise_id']);
                $info['company_name'] = $franchise['franchise_name'];
                $info['gst_number'] = $franchise['gst_number'];
                $info['pan'] = $franchise['pan'];
            }
        }

        if ($plugin['has_signature'] == 1) {
            $smarty['signature'] = $plugin['signature'];
        }

        if ($info['display_url'] != '') {
            $merchant_page = $this->app_url . '/m/' . $info['display_url'];
        }

        if (empty($main_header)) {
            $main_header[] = array('column_name' => 'Company name', 'value' => $info['company_name']);
            $main_header[] = array('column_name' => 'Merchant email', 'value' => $info['business_email']);
            if ($info['phone_on_invoice'] == 1) {
                $main_header[] = array('column_name' => 'Merchant contact', 'value' => $info['business_contact']);
            }
            $main_header[] = array('column_name' => 'Merchant address', 'value' => $info['merchant_address']);
        }


        if (isset($smarty['plugin']['has_partial'])) {
            $partial_payments = $this->common->querylist("call get_partial_payments('" . $payment_request_id . "')");
            $smarty["partial_payments"] = $partial_payments;
        } else {
            if ($smarty['payment_request_status'] == 1) {
                $res = $this->common->getSingleValue('payment_transaction', 'payment_request_id', $payment_request_id);
                $receipt_info = $this->common->getReceipt($res['pay_transaction_id'], 'Online');
                $smarty["transaction"] = $receipt_info;
            } elseif ($smarty['payment_request_status'] == 2) {
                $res = $this->common->getSingleValue('offline_response', 'payment_request_id', $payment_request_id);
                $receipt_info = $this->common->getReceipt($res['offline_response_id'], 'Offline');
                $smarty["transaction"] = $receipt_info;
            }
        }

        $smarty["main_header"] = $main_header;

        if ($row['column_type'] == 'TC') {
            $tnc[$tnckey] = $row;
            $val = str_replace('&lt;', '<', $row['column_name']);
            $val = str_replace('&gt;', '>', $val);
            $tnc[$tnckey]['column_name'] = $val;
            $tnckey++;
        }
        $tnc = str_replace('&lt;', '<', $info['tnc']);
        $tnc = str_replace('&gt;', '>', $tnc);

        $smarty["paid_amount"] = $info['paid_amount'];
        $smarty["absolute_cost"] = $info['absolute_cost'] - $info['paid_amount'];
        if ($plugin['roundoff'] == 1) {
            $smarty["absolute_cost"] = round($smarty["absolute_cost"], 0);
        }
        $info['absolute_cost'] = $smarty["absolute_cost"];
        $info['grand_total'] = $info['grand_total'] - $info['paid_amount'];
        $smarty["grand_total"] = $info['grand_total'];
        $grand_total = $info['grand_total'];
        $date = date("m/d/Y");
        $refDate = date("m/d/Y", strtotime($info['due_date']));
        if ($date > $refDate) {
            $smarty["invoice_total"] = $info['invoice_total'];
            if ($info['grand_total'] > 0) {
                $grand_total = $info['grand_total'] + $info['late_fee'];
            }
        }
        $smarty['tnc'] = $tnc;
        if (req_type != 'cron') {
            $num = $info['absolute_cost'];
            $num_words = Numbers_Words::toCurrency($num, "en_IN", $info['currency']);
            $num_words1 = str_replace("Indian Rupees", "Rupees", $num_words);
            $money_words = ucwords($num_words1);
            $smarty['money_words'] = str_replace('Zero Paises', '', $money_words);

            foreach ($banklist as $value) {
                $bank_ids[] = $value['config_key'];
                $bank_values[] = $value['config_value'];
            }
            $smarty["bank_id"] = $bank_ids;
            $smarty["bank_value"] = $bank_values;
            $bankselect = isset($_POST['bank_name']) ? $_POST['bank_name'] : '';
            $smarty["bank_selected"] = $bankselect;

            require_once MODEL . 'merchant/CommentsModel.php';
            $comments = new CommentsModel();
            $commentlist = $comments->getCommentsList($payment_request_id);
            $int = 0;
            foreach ($commentlist as $list) {
                $commentlist[$int]['link'] = $this->encrypt->encode($list['id']);
                $int++;
            }
            $smarty["commentlist"] = $commentlist;
        }
        $smarty["properties"] = json_decode($info['properties'], 1);
        if ($info['template_type'] == 'travel_ticket_booking' || $info['template_type'] == 'travel') {
            if ($staging == 1) {
                $ticket_details = $this->common->getListValue('staging_invoice_travel_particular', 'payment_request_id', $payment_request_id, 1);
            } else {
                $ticket_details = $this->common->getListValue('invoice_travel_particular', 'payment_request_id', $payment_request_id, 1);
            }
            $smarty["ticket_detail"] = $ticket_details;
            $smarty['sec_col'] = array("A", "B", "C", "D", "E", "F", "G", "H", "I");

            if ($info['template_type'] == 'travel') {
                $secarray = array();
                foreach ($ticket_details as $td) {
                    if (!in_array($td['type'], $secarray)) {
                        if ($td['type'] == 1 && isset($smarty['properties']['travel_section'])) {
                            $secarray[] = $td['type'];
                        } elseif ($td['type'] == 2 && isset($smarty['properties']['travel_cancel_section'])) {
                            $secarray[] = $td['type'];
                        } elseif ($td['type'] == 3 && isset($smarty['properties']['hotel_section'])) {
                            $secarray[] = $td['type'];
                        } elseif ($td['type'] == 4 && isset($smarty['properties']['facility_section'])) {
                            $secarray[] = $td['type'];
                        }
                    }
                    $smarty['secarray'] = $secarray;
                }
            }
        }

        if ($info['template_type'] == 'franchise' || $info['template_type'] == 'nonbrandfranchise') {
            $sale_details = $this->common->getListValue('invoice_food_franchise_sales', 'payment_request_id', $payment_request_id, 1);
            $sale_summary = $this->common->getSingleValue('invoice_food_franchise_summary', 'payment_request_id', $payment_request_id);
            $smarty["sale_details"] = $sale_details;
            $smarty["sale_summary"] = $sale_summary;
        }

        $smarty["Url"] = $this->encrypt->encode($payment_request_id);
        $smarty["customer_breckup"] = $customer_breckup;

        $smarty["merchant_page"] = $merchant_page;
        $smarty["merchant_id"] = $info['merchant_user_id'];
        $smarty["setting"] = json_decode($info['setting'], 1);


        $smarty["legal_complete"] = $info['legal_complete'];
        $smarty["debit"] = $debit;

        $smarty["plugin"] = $plugin;
        $smarty["particular_column"] = json_decode($info['particular_column'], 1);
        if ($staging == 0) {
            $smarty["particular"] = $this->common->getListValue('invoice_particular', 'payment_request_id', $payment_request_id, 1);
        } else {
            $smarty["particular"] = $this->common->getListValue('staging_invoice_particular', 'payment_request_id', $payment_request_id, 1);
        }


        $smarty["tax"] = $this->common->getInvoiceTax($payment_request_id, $staging);

        $smarty["header"] = $header;
        $smarty["bds_column"] = $bds;
        $smarty["payment_request_id"] = $payment_request_id;
        $smarty["money_text"] = $money_words;
        $smarty["landline"] = $info['landline'];
        $smarty["phone_on_invoice"] = $info['phone_on_invoice'];
        if (strrpos($info['narrative'], 'http') > 0) {
            $link = substr($info['narrative'], strrpos($info['narrative'], 'http'), strrpos($info['narrative'], ' '));
            $info['narrative'] = str_replace($link, '<a target="_BLANK" href="' . $link . '">' . $link . '</a>', $info['narrative']);
        }
        $smarty["narrative"] = str_replace('|', '<br>', $info['narrative']);
        $smarty["invoice_total"] = $info['invoice_total'];
        $smarty["previous_due"] = $info['previous_due'];
        $smarty["amount"] = $info['invoice_total'];
        $smarty["basic_total"] = $info['basic_amount'];
        $smarty["tax_total"] = $info['tax_amount'];
        $smarty["registration_number"] = $info['registration_number'];
        $smarty["gst_number"] = $info['gst_number'];
        $smarty["pan"] = $info['pan'];
        $smarty["tan"] = $info['tan'];
        $smarty["cin_no"] = $info['cin_no'];

        $smarty["due_date"] = $info['due_date'];
        $smarty["bill_date"] = $info['bill_date'];
        $smarty["company_name"] = $info['company_name'];
        $smarty["landline"] = $info['landline'];
        $smarty["late_fee"] = $info['late_fee'];
        $smarty["payment_request_status"] = $info['payment_request_status'];
        $smarty["info"] = $info;
        $smarty["advance"] = $info['advance'];

        $pg_details = array();

        $smarty["grand_total"] = $grand_total;
        $smarty["surcharge_amount"] = 0;
        if ($user_type == 'patron') {
            $count_res = $this->common->querysingle("select fee_detail_id from merchant_fee_detail where (surcharge_enabled=1 or pg_surcharge_enabled=1) and is_active=1 and merchant_id='" . $info['merchant_id'] . "'");
            if (empty($count_res)) {
                $smarty["is_surcharge"] = 0;
            } else {
                $smarty["is_surcharge"] = 1;
            }
        }

        $smarty["merchant_address"] = $info['merchant_address'];
        $smarty["business_email"] = $info['business_email'];
        $smarty["business_contact"] = $info['business_contact'];
        $smarty["image_path"] = $info['image_path'];
        $smarty["template_type"] = $info['template_type'];
        $smarty["currentdate"] = date("d M Y");
        if ($plugin['has_supplier'] == 1) {
            $supplierlist = $this->common->getInvoiceSupplierlist($plugin['supplier']);
            $smarty["supplierlist"] = $supplierlist;
        }
        if ($plugin['coupon_id'] > 0) {
            $coupon_details = $this->common->getCouponDetails($plugin['coupon_id']);
            $smarty["coupon_details"] = $coupon_details;
        }

        if (BATCH_CONFIG == true) {
        } else {
            $login_merchant = $this->session->get('merchant_id');
        }

        switch ($info['payment_request_status']) {
            case 1:
                $smarty["error"] = 'This invoice has already been paid online.';
                break;
            case 2:
                $smarty["error"] = (isset($login_merchant)) ? 'This invoice has already been settled.' : 'This invoice has been settled by your merchant.';
                break;
            case 3:
                $smarty["error"] = (isset($login_merchant)) ? 'This invoice has already been deleted.' : 'This invoice has deleted by merchant.';
                break;
            default:
                break;
        }
        if ($info['is_expire'] == 1) {
            $id = $this->common->getRowValue('payment_request_id', 'payment_request', 'merchant_id', $info['merchant_id'], 0, ' and customer_id=' . $info['customer_id'] . ' and (expiry_date is null or expiry_date>curdate()) order by payment_request_id desc limit 1');
            $smarty["error"] = 'This invoice has expired and cannot be paid online anymore. ';
            if ($id != false) {
                $link = $this->encrypt->encode($id);
                $smarty["error"] .= '<a href="/' . $user_type . '/paymentrequest/view/' . $link . '">View latest invoice</a>';
            }
        }
        if ($info['short_url'] == '') {
            $link = $this->encrypt->encode($info['payment_request_id']);
            $smarty["patron_url"] = $this->app_url . '/patron/paymentrequest/view/' . $link;
        } else {
            $smarty["patron_url"] = $info['short_url'];
        }
        if ($smarty['error'] != '') {
            $smarty["is_online_payment"] = 0;
        }
        return $smarty;
    }

    function getinvoiceBreckup($rows)
    {
        $template = array();
        $int = 0;
        $mint = 0;
        $headerinc = 0;
        $bdsinc = 0;
        foreach ($rows as $row) {
            if ($row['invoice_id'] > 0 || $row['save_table_name'] == 'request') {
                if ($row['column_type'] == 'H' && $row['save_table_name'] != 'request') {
                    $type = 'header';
                    $template[$type][$headerinc]['column_id'] = $row['column_id'];
                    $template[$type][$headerinc]['invoice_id'] = $row['invoice_id'];
                    $template[$type][$headerinc]['column_name'] = $row['column_name'];
                    $template[$type][$headerinc]['is_mandatory'] = $row['is_mandatory'];
                    $template[$type][$headerinc]['column_datatype'] = $row['column_datatype'];
                    $template[$type][$headerinc]['is_delete_allow'] = $row['is_delete_allow'];
                    $template[$type][$headerinc]['function_id'] = $row['function_id'];
                    $template[$type][$headerinc]['column_position'] = $row['column_position'];
                    $template[$type][$headerinc]['position'] = $row['position'];
                    $template[$type][$headerinc]['value'] = $row['value'];
                    $headerinc++;
                }
                if ($row['column_type'] == 'BDS') {
                    $type = 'bds';
                    $template[$type][$bdsinc]['column_id'] = $row['column_id'];
                    $template[$type][$bdsinc]['invoice_id'] = $row['invoice_id'];
                    $template[$type][$bdsinc]['column_name'] = $row['column_name'];
                    $template[$type][$bdsinc]['is_mandatory'] = $row['is_mandatory'];
                    $template[$type][$bdsinc]['column_datatype'] = $row['column_datatype'];
                    $template[$type][$bdsinc]['is_delete_allow'] = $row['is_delete_allow'];
                    $template[$type][$bdsinc]['function_id'] = $row['function_id'];
                    $template[$type][$bdsinc]['column_position'] = $row['column_position'];
                    $template[$type][$bdsinc]['position'] = $row['position'];
                    $template[$type][$bdsinc]['value'] = $row['value'];
                    $bdsinc++;
                }

                if ($row['save_table_name'] == 'request') {
                    $type = 'request';
                    $template[$type][$int]['column_id'] = $row['column_id'];
                    $template[$type][$int]['column_name'] = $row['column_name'];
                    $template[$type][$int]['column_datatype'] = $row['column_datatype'];
                    $template[$type][$int]['column_position'] = $row['column_position'];
                    $template[$type][$int]['function_id'] = $row['function_id'];
                    $template[$type][$int]['position'] = $row['position'];
                    $int++;
                }

                if ($row['column_type'] == 'M' && $row['default_column_value'] != 'Profile') {
                    $type = 'main_header';
                    $template[$type][$mint]['invoice_id'] = $row['invoice_id'];
                    $template[$type][$mint]['column_name'] = $row['column_name'];
                    $template[$type][$mint]['datatype'] = $row['column_datatype'];
                    $template[$type][$mint]['column_position'] = $row['column_position'];
                    $template[$type][$mint]['position'] = $row['position'];
                    $template[$type][$mint]['value'] = $row['value'];
                    $mint++;
                }

                if ($row['function_id'] > 0) {
                    $template['function'][] = $row['function_id'];
                }
            }
        }

        return $template;
    }

    public function getTemplateBreakup($template_id)
    {
        try {
            $rows = $this->common->getListValue('invoice_column_metadata', 'template_id', $template_id, 1, ' order by sort_order,column_id');
            $template = array();
            $headerinc = 0;
            $bdsinc = 0;
            foreach ($rows as $row) {
                if ($row['column_type'] == 'C') {
                    $template['customer_column'][] = array('column_id' => $row['column_id'], 'column_name' => $row['column_name'], 'datatype' => $row['column_datatype'], 'customer_column_id' => $row['customer_column_id'], 'save_table_name' => $row['save_table_name']);
                    if ($row['save_table_name'] == 'customer') {
                        $template['exist_customer_column'][] = $row['customer_column_id'];
                    } else {
                        $template['exist_custom_column'][] = $row['customer_column_id'];
                    }
                }
                if ($row['column_type'] == 'M') {
                    $template['main_header'][$row['sort_order']] = array('column_id' => $row['column_id'], 'column_name' => $row['column_name'], 'datatype' => $row['column_datatype'], 'default_column_value' => $row['default_column_value']);
                }
                if ($row['column_type'] == 'H') {
                    $type = 'header';
                    $template[$type][$headerinc]['column_id'] = $row['column_id'];
                    $template[$type][$headerinc]['column_name'] = $row['column_name'];
                    $template[$type][$headerinc]['datatype'] = $row['column_datatype'];
                    $template[$type][$headerinc]['table_name'] = $row['save_table_name'];
                    $template[$type][$headerinc]['position'] = $row['position'];
                    $template[$type][$headerinc]['is_mandatory'] = $row['is_mandatory'];
                    $template[$type][$headerinc]['value'] = $row['default_column_value'];
                    $template[$type][$headerinc]['column_position'] = $row['column_position'];
                    $template[$type][$headerinc]['delete_allow'] = $row['is_delete_allow'];
                    $function_id = ($row['function_id'] > 0) ? $row['function_id'] : -1;
                    $template[$type][$headerinc]['function_id'] = $function_id;
                    if ($function_id > 0) {
                        $function_mapping = $this->common->getSingleValue('column_function_mapping', 'column_id', $row['column_id'], 1, ' and function_id=' . $function_id);
                        $template[$type][$headerinc]['param'] = $function_mapping['param'];
                        $template[$type][$headerinc]['value'] = $function_mapping['value'];
                    }
                    $headerinc++;
                }

                if ($row['column_type'] == 'BDS') {
                    $type = 'BDS';
                    $template[$type][$bdsinc]['column_id'] = $row['column_id'];
                    $template[$type][$bdsinc]['column_name'] = $row['column_name'];
                    $template[$type][$bdsinc]['datatype'] = $row['column_datatype'];
                    $template[$type][$bdsinc]['table_name'] = $row['save_table_name'];
                    $template[$type][$bdsinc]['position'] = $row['position'];
                    $template[$type][$bdsinc]['is_mandatory'] = $row['is_mandatory'];
                    $template[$type][$bdsinc]['value'] = $row['default_column_value'];
                    $template[$type][$bdsinc]['column_position'] = $row['column_position'];
                    $template[$type][$bdsinc]['delete_allow'] = $row['is_delete_allow'];
                    $template[$type][$bdsinc]['function_id'] = ($row['function_id'] > 0) ? $row['function_id'] : -1;
                    $bdsinc++;
                }
            }

            return $template;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E100]Error while getting InvoiceBreakup Error:' . $e->getMessage());
        }
    }

    function handleInvoicePost($POST, $invoicevalues)
    {
        $ids = $POST['ids'];
        $values = $POST['newvalues'];
        $post_values = array();
        $int = 0;
        foreach ($ids as $id) {
            $post_values[$id] = $values[$int];
            $int++;
        }
        $int = 0;

        foreach ($invoicevalues['header'] as $key => $h) {
            if ($invoicevalues['header'][$key]['table_name'] == 'request') {
                $invoicevalues['header'][$key]['value'] = $POST['requestvalue'][$int];
            } else {
                $invoicevalues['header'][$key]['value'] = $post_values[$h['column_id']];
            }
            $int++;
        }
        $int = 0;
        foreach ($invoicevalues['particular'] as $group_id => $particular) {
            foreach ($particular as $key => $h) {
                $invoicevalues['particular'][$group_id][$key]['value'] = $post_values[$h['column_id']];
            }
            $int++;
        }
        $int = 0;
        foreach ($invoicevalues['tax'] as $group_id => $particular) {
            foreach ($particular as $key => $h) {
                $invoicevalues['tax'][$group_id][$key]['value'] = $post_values[$h['column_id']];
            }
            $int++;
        }
        return $invoicevalues;
    }

    function setInvoiceFunction($invoicevalues, $template_type)
    {
        require_once PACKAGE . 'swipez/function/DataFunction.php';
        $int = 0;

        if ($template_type == 'simple') {
            $bill_date_column = 4;
            $due_date_column = 5;
        } else {
            $bill_date_column = 5;
            $due_date_column = 6;
        }
        foreach ($invoicevalues['header'] as $column) {

            if ($column['column_position'] == $bill_date_column) {
                $invoicevalues['header'][$int]['id'] = 'bill_date';
            }
            if ($column['column_position'] == $due_date_column) {
                $invoicevalues['header'][$int]['id'] = 'due_date';
            }

            if ($column['function_id'] > 0) {
                $function_details = $this->common->getSingleValue('column_function', 'function_id', $column['function_id']);
                $function = new $function_details['php_class']();
                $function->req_type = 'Invoice_create';
                $function->function_id = $column['function_id'];
                $function->user_id = $this->user_id;
                $mapping_details = $this->common->getfunctionMappingDetails($column['column_id'], $column['function_id']);
                if (!empty($mapping_details)) {
                    $function->param_name = $mapping_details['param'];
                    $function->param_value = $mapping_details['value'];
                }
                $function->set();
                $invoicevalues['header'][$int]['value'] = $function->get('value');
                if (!isset($invoicevalues['header'][$int]['id'])) {
                    $invoicevalues['header'][$int]['id'] = $function_details['column_name'];
                }
                $function_script .= $function->get('script');
            }
            $int++;
        }

        return array('invoice_value' => $invoicevalues, 'function_script' => $function_script);
    }

    function setInvoiceFunctionData($invoicevalues, $type, $req_id, $validate)
    {

        $cycle_datekey = array_search('4', $_POST['col_position']);
        $bill_datekey = array_search('5', $_POST['col_position']);
        $due_datekey = array_search('6', $_POST['col_position']);
        $_POST['bill_cycle_name'] = $_POST['requestvalue'][$cycle_datekey];
        $_POST['bill_date'] = $_POST['requestvalue'][$bill_datekey];
        $_POST['due_date'] = $_POST['requestvalue'][$due_datekey];
        $_POST['late_fee'] = 0;
        $bill_date_col = 5;
        $due_date_col = 6;

        require_once PACKAGE . 'swipez/function/DataFunction.php';
        $int = 0;

        foreach ($invoicevalues['header'] as $column) {
            if ($column['function_id'] > 0) {
                $function_details = $this->common->getSingleValue('column_function', 'function_id', $column['function_id']);
                $function = new $function_details['php_class']();
                if ($type == 'insert') {
                    if ($validate == NULL) {
                        $function->req_type = 'Invoice';
                    } else {
                        $function->req_type = 'Invoice_update';
                    }
                } else {
                    $function->req_type = 'Invoice';
                }
                $function->function_id = $column['function_id'];
                $mapping_details = $this->common->getfunctionMappingDetails($column['column_id'], $column['function_id']);
                if (!empty($mapping_details)) {
                    $function->param_name = $mapping_details['param'];
                    $function->param_value = $mapping_details['value'];
                }
                if ($column['column_position'] == $bill_date_col) {
                    $function->set($_POST['bill_date']);
                    $_POST['bill_date'] = $function->get('value');
                }
                if ($column['column_position'] == $due_date_col) {
                    $function->set($_POST['due_date']);
                    $_POST['due_date'] = $function->get('value');
                }
                if ($column['table_name'] == 'metadata') {
                    if ($type == 'insert') {
                        if ($req_id == NULL) {
                            $function->set($_POST['newvalues'][$int]);
                            $_POST['newvalues'][$int] = $function->get('value');
                        } else {
                            $function->set($_POST['existvalues'][$int]);
                            $_POST['existvalues'][$int] = $function->get('value');
                        }
                    } else {
                        $function->set($_POST['existvalues'][$int]);
                        $_POST['existvalues'][$int] = $function->get('value');
                    }
                }
            }
            if ($column['table_name'] == 'metadata') {
                $int++;
            }
        }
    }

    function setCreateInvoice($template_id, $merchant_id, $user_id, $type)
    {
        $smartyarray["template_selected"] = $template_id;
        $smartyarray["invoice_type"] = $type;
        $pgdetails = $this->common->getPGDetails($user_id);
        $sub_franchise_id = $this->session->get('sub_franchise_id');
        require MODEL . 'merchant/CustomerModel.php';
        $Customermodel = new CustomerModel();

        $where = '';
        $grptext = '';
        if ($this->session->get('login_customer_group')) {
            foreach ($this->session->get('login_customer_group') as $grpname) {
                if ($grptext == '') {
                    $grptext = " and (customer_group like ~%" . '{' . $grpname . '}' . '%~';
                } else {
                    $grptext .= " or customer_group like ~%" . '{' . $grpname . '}' . '%~';
                }
            }
            $grptext .= ')';

            $where = str_replace('~', "'", $grptext);
        }

        $customer_list = $Customermodel->getCustomerallList($merchant_id, $where);
        $customer_column = $Customermodel->getCustomerBreakup($merchant_id);
        $smartyarray["column"] = $customer_column;
        $template_info = $this->common->getSingleValue('invoice_template', 'template_id', $template_id);
        $properties = json_decode($template_info['properties'], 1);
        $plugin = json_decode($template_info['plugin'], 1);
        if ($plugin['has_covering_note'] == 1) {
            $logo = $this->common->getRowValue('logo', 'merchant_landing', 'merchant_id', $merchant_id);
            if ($logo != '') {
                $logo = $this->view->server_name . '/uploads/images/landing/' . $logo;
            } else {
                $logo = $this->view->server_name . '/assets/frontend/onepage2/img/logo_scroll.png';
            }
            $jsarray[] = 'coveringnote';
            $smartyarray["logo"] = $logo;
            $covering_list = $this->common->getListValue('covering_note', 'merchant_id', $merchant_id, 1);
            $smartyarray["covering_list"] = $covering_list;
            $smartyarray["covering_id"] = $plugin['default_covering_note'];
        }
        $template_type = $template_info['template_type'];
        $merchant_setting = $this->common->getSingleValue('merchant_setting', 'merchant_id', $merchant_id);
        $template_info['merchant_name'] = $this->session->get('company_name');
        $template_info['template_id'] = $this->encrypt->encode($template_info['template_id']);
        $invoicevalues = $this->getTemplateBreakup($template_id);
        if (isset($_POST['newvalues'])) {
            $invoicevalues = $this->handleInvoicePost($_POST, $invoicevalues);
            $smartyarray["post"] = $_POST;
        }
        if ($plugin['has_coupon'] == 1) {
            require MODEL . 'merchant/CouponModel.php';
            $couponmodel = new CouponModel();
            $coupon_list = $couponmodel->getActiveCoupon($merchant_id);
            $smartyarray["coupon_list"] = $coupon_list;
        }

        if ($plugin['has_franchise'] == 1) {
            if ($sub_franchise_id > 0) {
                $franchise = $this->common->getSingleValue('franchise', 'franchise_id', $sub_franchise_id, 1);
                if (!empty($franchise)) {
                    $franchise_list[] = $franchise;
                }
            } else {
                $franchise_list = $this->common->getListValue('franchise', 'merchant_id', $merchant_id, 1, 'and status=1');
            }
            $smartyarray["franchise_list"] = $franchise_list;
        }

        if ($plugin['has_signature'] == 1) {
            $signature = $this->common->getRowValue('`value`', 'merchant_config_data', 'merchant_id', $merchant_id, 0, " and `key`='DIGITAL_SIGNATURE'");
            $smartyarray['signature'] = json_decode($signature, 1);
        }

        if ($plugin['has_vendor'] == 1) {
            $vendor_list = $this->common->getListValue('vendor', 'merchant_id', $merchant_id, 1, 'and status=1');
            $smartyarray["vendor_list"] = $vendor_list;
        }

        $profile_list = $this->common->getListValue('merchant_billing_profile', 'merchant_id', $merchant_id, 1);
        $smartyarray["billing_profile"] = $profile_list;

        if ($plugin['has_autocollect'] == 1) {
            //   $this->session->set('valid_ajax', 'auto_collect');
            //  $autocollect_plans = $this->common->getListValue('autocollect_plans', 'merchant_id', $merchant_id, 1, 'and status=1');
            //  $smartyarray["autocollect_plans"] = $autocollect_plans;
        }


        $is_supplier = ($plugin['has_supplier'] == 1) ? '1' : '';
        $is_debit = ($plugin['has_deductible'] == 1) ? '1' : '';
        $supplierlist = $plugin['supplier'];

        $supplier = $this->common->getSupplierlist($user_id);
        $supplierlist2 = $this->common->getInvoiceSupplierlist($supplierlist);
        $smartyarray["supplierid"] = $supplierlist;
        $res = $this->setInvoiceFunction($invoicevalues, $template_type);
        $invoicevalues = $res['invoice_value'];

        if ($plugin['has_custom_reminder'] == 1) {
            $reminder_array = explode(',', $plugin['reminders']);
            foreach ($plugin['reminders'] as $key => $value) {
                $reminders[] = array('day' => $key, 'subject' => $value['email_subject'], 'sms' => $value['sms']);
            }

            $smartyarray["reminders"] = $reminders;
            $json = json_encode($reminder_array);
        }

        $product_list = $this->common->getListValue('merchant_product', 'merchant_id', $merchant_id, 1);
        foreach ($product_list as $ar) {
            $products[$ar['product_name']] = array('price' => $ar['price'], 'sac_code' => $ar['sac_code'], 'gst_percent' => $ar['gst_percent'], 'unit_type' => $ar['unit_type'], 'name' => $ar['product_name']);
        }
        if (!empty($product_list)) {
            $smartyarray["product_json"] = json_encode($products);
            $smartyarray["product_list"] = $products;
        }

        $tax_list = $this->common->getListValue('merchant_tax', 'merchant_id', $merchant_id, 1);
        $tax_array = array();
        $tax_rate_array = array();
        foreach ($tax_list as $ar) {
            $tax_array[$ar['tax_id']] = array('tax_type' => $ar['tax_type'], 'tax_name' => $ar['tax_name'], 'percentage' => $ar['percentage'], 'fix_amount' => $ar['fix_amount']);
            $tax_rate_array[$ar['tax_name']] = $ar['percentage'];
        }

        $tax_type = $this->common->getListValue('config', 'config_type', 'tax_type');
        $smartyarray["tax_type"] = $tax_type;

        if (!empty($tax_list)) {
            $smartyarray["tax_array"] = json_encode($tax_array);
            $smartyarray["tax_list"] = $tax_array;
            $smartyarray["merchant_tax_list"] = $tax_list;
            $smartyarray["tax_rate_array"] = json_encode($tax_rate_array);
        }

        $function_script = $res['function_script'];


        $smartyarray["supplierid"] = explode(",", $supplierlist);
        $smartyarray["merchant_setting"] = $merchant_setting;

        if ($template_info['profile_id'] > 0) {
            $smartyarray["merchant_state"] = $this->common->getRowValue('state', 'merchant_billing_profile', 'id', $template_info['profile_id']);
        } else {
            $smartyarray["merchant_state"] = $this->common->getRowValue('state', 'merchant_billing_profile', 'merchant_id', $merchant_id);
        }

        $state_code = $this->common->getListValue('config', 'config_type', 'gst_state_code');
        $smartyarray["state_code"] = $state_code;

        $smartyarray["customer_list"] = $customer_list;
        $smartyarray["default_particular"] = json_decode($template_info['default_particular'], 1);
        $smartyarray["default_tax"] = json_decode($template_info['default_tax'], 1);
        $smartyarray["particular_values"] = json_decode($template_info['particular_values'], 1);
        $smartyarray["customer_column"] = $invoicevalues['customer_column'];
        $smartyarray["main_header"] = $invoicevalues['main_header'];
        $smartyarray["supplier"] = $supplier;
        $smartyarray["properties"] = $properties;
        $smartyarray["is_supplier"] = $is_supplier;
        $smartyarray["is_debit"] = $is_debit;
        $smartyarray["supplierlist"] = $supplierlist2;
        $smartyarray["info"] = $template_info;
        $smartyarray["header"] = $invoicevalues['header'];
        $smartyarray["bds_column"] = $invoicevalues['BDS'];
        $smartyarray["cycleName"] = date('M Y') . '- Bill';
        $smartyarray["particular"] = $invoicevalues['particular'];
        $smartyarray["tax"] = $invoicevalues['tax'];
        $smartyarray["plugin"] = $plugin;
        $smartyarray["debit"] = $plugin['deductible'];
        $smartyarray["cc"] = $invoicevalues['cc'];
        $smartyarray["particularTotal"] = $template_info['particular_total'];
        $smartyarray["taxTotal"] = $template_info['tax_total'];
        $smartyarray["custom_particular"] = $invoicevalues['custom_particular'];
        $smartyarray["pg"] = $pgdetails;
        $current_date = date("d M Y");
        $smartyarray["current_date"] = $current_date;
        $reinfo['smarty'] = $smartyarray;
        $reinfo['template_type'] = $template_info['template_type'];
        $reinfo['function_script'] = $function_script;

        return $reinfo;
    }

    function saveInvoiceApi($invoice_array, $amount, $transaction_id, $mode = 'Swipez')
    {
        $_POST['data'] = json_encode($invoice_array);
        require_once CONTROLLER . 'api/Api.php';
        require_once CONTROLLER . 'api/v3/merchant/Invoice.php';
        $apiinv = new Invoice();
        $apiinv->webrequest = false;
        $apiinv->save();
        $response = $apiinv->response;
        //$response = $this->curlRequest($post_string, $post_url, $host);
        SwipezLogger::info(__CLASS__, 'Xway invoice : ' . $response);
        $response_array = json_decode($response, 1);
        $invoice_id = $response_array['srvrsp'][0]['invoice_id'];
        $offline_transaction_id = '';
        if ($invoice_id != '') {
            $jsonArray['access_key_id'] = $invoice_array['access_key_id'];
            $jsonArray['secret_access_key'] = $invoice_array['secret_access_key'];
            $jsonArray['invoice_id'] = $invoice_id;
            if ($this->paid_date == null) {
                $paid_date = date('Y-m-d');
            } else {
                $paid_date = $this->paid_date;
            }
            $jsonArray['paid_date'] = $paid_date;
            $jsonArray['amount'] = $amount;
            $jsonArray['mode'] = $mode;
            $jsonArray['bank_name'] = '';
            $jsonArray['bank_ref_no'] = $transaction_id;
            $jsonArray['cheque_no'] = '';
            $jsonArray['cash_paid_to'] = '';
            $jsonArray['notify'] = '0';
            //$post_url = $base_url . '/api/v2/merchant/invoice/settle';
            //$post_string = 'data=' . json_encode($jsonArray);
            $json = json_encode($jsonArray);
            $_POST['data'] = $json;
            $apisettle = new Invoice();
            $apisettle->webrequest = false;
            $apisettle->settle();
            $response = $apisettle->response;
            //$response = $this->curlRequest($post_string, $post_url, $host);
            SwipezLogger::info(__CLASS__, 'Form builder settle invoice : ' . $response);
            $arr = json_decode($response, 1);
            if (isset($arr['srvrsp']['transaction_id'])) {
                $offline_transaction_id = $arr['srvrsp']['transaction_id'];
                $this->common->genericupdate('offline_response', 'post_paid_invoice', 1, 'offline_response_id', $arr['srvrsp']['transaction_id'], 'System');
            }
        } else {
            $array['invoice_id'] = '';
            return $array;
        }

        $link = $this->encrypt->encode($invoice_id);
        require_once CONTROLLER . 'patron/Paymentrequest.php';
        $Paymentrequest = new Paymentrequest();
        $file_name = $Paymentrequest->download($link, 1);
        $array['invoice_id'] = $invoice_id;
        $array['file_name'] = $file_name;
        $array['invoice_link'] = $link;
        $array['offline_transaction_id'] = $offline_transaction_id;
        return $array;
    }



    function xwayInvoice($details)
    {
        try {
            $invoice_id = $this->common->getRowValue('payment_request_id', 'xway_transaction', 'xway_transaction_id', $details['transaction_id']);
            if (strlen($invoice_id) == 10) {
            } else {
                $invoice_array = $this->createInvoiceJson($details);
                //$post_url = $base_url . '/api/v3/merchant/invoice/save';
                //$post_string = 'data=' . json_encode($invoice_array);
                $response = $this->saveInvoiceApi($invoice_array, $details['amount'], $details['transaction_id']);
                if ($response['invoice_id'] != '') {
                    if ($details['id'] > 0) {
                        $this->common->genericupdate('form_builder_transaction', 'payment_request_id', $response['invoice_id'], 'id', $details['id'], 'System');
                    }
                    $this->common->genericupdate('xway_transaction', 'payment_request_id', $response['invoice_id'], 'xway_transaction_id', $details['transaction_id']);
                }
                if (REQUEST_TYPE == 'SWIPEZ_BATCH') {
                    return true;
                }
                return $response;
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, 'Error while create xway invoice: Error: ' . $e->getMessage());
        }
    }

    function createInvoiceJson($details)
    {
        try {
            $json = $details['json'];
            $json_array = json_decode($json, 1);
            $template_link = '';
            $access_key = '';
            $secret_key = '';
            $cycle_name = '';
            $customer_gst = '';
            $particular_name = '';
            $sac_code = '';
            $base_amount = 0;
            $profile_id = 0;
            $customer_detail = array();
            $custom_detail = array();
            $instate_tax = array();
            $outstate_tax = array();
            $custom_tax = array();
            $narrative = '';
            $discount = 0;
            $coupon_name = '';
            foreach ($json_array as $row) {
                if ($row['type'] == 'setting') {
                    $access_key = $row['access_key'];
                    $secret_key = $row['secret_key'];
                    $template_link = $row['template_id'];
                    $cycle_name = $row['billing_cycle_name'];
                } elseif ($row['type'] == 'customer_field') {
                    $customer_detail[$row['name']] = $row['value'];
                } elseif ($row['type'] == 'customer_custom_field') {
                    $custom_detail[$row['column_id']] = $row['value'];
                    if ($row['name'] == 'gst_number') {
                        $customer_gst = $row['value'];
                    }
                } elseif ($row['type'] == 'purpose') {
                    $narrative = $row['value'];
                } elseif ($row['type'] == 'particular') {
                    $particular_name = $row['description'];
                    $sac_code = $row['sac_code'];
                } elseif ($row['type'] == 'base_amount') {
                    $base_amount = $row['value'];
                } elseif ($row['type'] == 'in_state_tax') {
                    $instate_tax[] = array('name' => $row['label'], 'value' => $row['value']);
                } elseif ($row['type'] == 'out_state_tax') {
                    $outstate_tax[] = array('name' => $row['label'], 'value' => $row['value']);
                } elseif ($row['type'] == 'custom_tax') {
                    $custom_tax[] = array('name' => $row['label'], 'value' => $row['value']);
                }
                if ($row['name'] == 'discount_amount') {
                    $discount = $row['value'];
                }
                if ($row['name'] == 'particular_name') {
                    $particular_name .= $row['value'];
                }
                if ($row['name'] == 'profile_id') {
                    $profile_id = $row['value'];
                }
                if ($row['name'] == 'discount_coupon_id') {
                    $coupon_id = $row['value'];
                    if ($coupon_id > 0) {
                        $coupon_name = $this->common->getRowValue('coupon_code', 'coupon', 'coupon_id', $coupon_id);
                        $this->common->queryexecute('update coupon set available=available-1 where `limit`>0 and coupon_id=' . $coupon_id);
                    }
                }
            }
            if (BATCH_CONFIG == true) {
            } else {
                $this->session->set('merchant_id', $details['merchant_id']);
            }

            require_once CONTROLLER . 'merchant/Template.php';
            $template = new Template(1);
            $template_array = $template->saveInvoceJson('v3', $template_link, 2, $details['merchant_id']);
            $template_id = $this->encrypt->decode($template_link);
            if (strlen($template_id) != 10) {
                $template_id = $this->common->getRowValue('value', 'encrypt_backup', 'encrypt_key', $template_link);
            }
            $template_detail = $this->common->getSingleValue('invoice_template', 'template_id', $template_id);
            $particular_column = json_decode($template_detail['particular_column'], 1);
            if ($customer_detail['state'] != '') {
                $p_id = $this->common->getRowValue('id', 'merchant_billing_profile', 'merchant_id', $details['merchant_id'], 1, " and state='" . $customer_detail['state'] . "'");
                if ($p_id != false) {
                    $profile_id = $p_id;
                }
            }

            $merchant_gst = $this->common->getMerchantProfile($details['merchant_id'], $profile_id, 'gst_number');
            $template_array['access_key_id'] = $access_key;
            $template_array['secret_access_key'] = $secret_key;
            $template_array['template_id'] = $template_link;
            $invoice = $template_array['invoice'][0];
            if ($profile_id > 0) {
                $invoice['profile_id'] = $profile_id;
            }
            $invoice['customer_code'] = '';

            $invoice['bill_cycle_name'] = $cycle_name;
            $invoice['invoice_narrative'] = $narrative;
            if ($this->bill_date == null) {
                $bill_date = date('Y-m-d');
            } else {
                $bill_date = $this->bill_date;
            }
            $invoice['bill_date'] = $bill_date;
            $invoice['due_date'] = date('Y-m-d');
            $invoice['invoice_properties']['notify_patron'] = '0';

            $p_int = 0;
            if (is_numeric($sac_code)) {
            } else {
                $description = $sac_code;
                $sac_code = '';
            }
            foreach ($particular_column as $col => $name) {
                $value = '';
                switch ($col) {
                    case 'item':
                        $value = $particular_name;
                        break;
                    case 'qty':
                        $value = 1;
                        break;
                    case 'rate':
                        $value = $base_amount;
                        break;
                    case 'total_amount':
                        $value = $base_amount;
                        break;
                    case 'sac_code':
                        $value = $sac_code;
                        break;
                    case 'description':
                        $value = $description;
                        break;
                }
                $particular[$p_int][$col] = $value;
            }
            $p_int++;
            if ($discount > 0) {
                foreach ($particular_column as $col => $name) {
                    $value = '';
                    switch ($col) {
                        case 'item':
                            $value = "Coupon discount : " . $coupon_name;
                            break;
                        case 'qty':
                            $value = 1;
                            break;
                        case 'rate':
                            $value = '-' . $discount;
                            break;
                        case 'total_amount':
                            $value = '-' . $discount;
                            break;
                    }
                    $particular[$p_int][$col] = $value;
                }
            }



            //Set Particular rows
            //$particular = array('id' => '', 'description' => $particular_name, 'annual_recurring_charges' => '', 'time_period' => $sac_code, 'amount' => $base_amount);
            $invoice['particular_rows'] = $particular;
            if ($discount > 0) {
                $base_amount = $base_amount - $discount;
            }
            //Set Tax rows
            $tax = array();
            if ($customer_detail['state'] != '') {
                $state_code = $this->common->getRowValue('config_key', 'config', 'config_value', $customer_detail['state'], 0, " and config_type='gst_state_code'");
                if ($customer_gst == '') {
                    if (strlen($state_code) == 1) {
                        $customer_gst = '0' . $state_code;
                    } else {
                        $customer_gst = $state_code;
                    }
                }
            }
            if ($merchant_gst != '' && $customer_gst != '') {
                if (substr($merchant_gst, 0, 2) == substr($customer_gst, 0, 2)) {
                    foreach ($instate_tax as $instate) {
                        $tax[] = array('id' => '', 'name' => $instate['name'], 'percentage' => $instate['value'], 'applicable_on' => $base_amount, 'Narrative' => '');
                    }
                } else {
                    foreach ($outstate_tax as $outstate) {
                        $tax[] = array('id' => '', 'name' => $outstate['name'], 'percentage' => $outstate['value'], 'applicable_on' => $base_amount, 'Narrative' => '');
                    }
                }
            }
            foreach ($custom_tax as $custtax) {
                $tax[] = array('id' => '', 'name' => $custtax['name'], 'percentage' => $custtax['value'], 'applicable_on' => $base_amount, 'Narrative' => '');
            }
            $invoice['tax_rows'] = array();
            $invoice['tax_rows'] = $tax;

            if (isset($customer_detail['customer_code']) && $customer_detail['customer_code'] != '') {
                $customer_id = $this->common->getRowValue('customer_id', 'customer', 'customer_code', $customer_detail['customer_code'], 1, " and merchant_id='" . $details['merchant_id'] . "'");
                if ($customer_id != false) {
                    $invoice['customer_code'] = $customer_detail['customer_code'];
                    $customer_code = $customer_detail['customer_code'];
                }
            }
            // Set customer details
            require_once MODEL . 'merchant/CustomerModel.php';
            $customer_model = new CustomerModel();
            if (isset($invoice['new_customer']['customer_code'])) {
                $space_position = strpos($customer_detail['customer_name'], ' ');
                if ($space_position > 0) {
                    $first_name = substr($customer_detail['customer_name'], 0, $space_position);
                } else {
                    $first_name = $customer_detail['customer_name'];
                }
                if ($customer_code == false) {
                    $custdetail = $customer_model->getcustomerId($details['merchant_id'], $first_name, $customer_detail['email']);
                    if ($custdetail == false) {
                        $customer_code = $customer_model->getCustomerCode($details['merchant_id']);
                    } else {
                        $customer_id = $custdetail['customer_id'];
                        $customer_code = $custdetail['customer_code'];
                        $invoice['customer_code'] = $customer_code;
                    }
                }
                $invoice['new_customer']['customer_code'] = $customer_code;
            }


            $invoice['new_customer']['customer_name'] = $customer_detail['customer_name'];
            $invoice['new_customer']['email'] = $customer_detail['email'];
            $invoice['new_customer']['mobile'] = $customer_detail['mobile'];
            $invoice['new_customer']['address'] = $customer_detail['address'];
            $invoice['new_customer']['city'] = $customer_detail['city'];
            $invoice['new_customer']['state'] = $customer_detail['state'];
            $invoice['new_customer']['zipcode'] = $customer_detail['zipcode'];

            if (empty($custom_detail) && isset($customer_id)) {
                $column_values = $customer_model->getCustomerCustomValues($customer_id, $details['merchant_id']);
                foreach ($column_values as $col) {
                    $custom_detail[$col['column_id']] = $col['value'];
                }
            }

            foreach ($invoice['new_customer']['custom_fields'] as $key => $cfield) {
                $invoice['new_customer']['custom_fields'][$key]['value'] = $custom_detail[$cfield['id']];
            }
            $template_array['invoice'][0] = $invoice;
            SwipezLogger::info(__CLASS__, 'INVOICE Json : ' . json_encode($template_array));
            return $template_array;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, 'Error while save form builder invoice Error: ' . $e->getMessage());
        }
    }

    function curlRequest($post_string, $post_url, $host, $filter = 1)
    {
        try {
            if ($filter == 1) {
                $post_string = str_replace('&', '%26', $post_string);
                $post_string = str_replace('@', '%40', $post_string);
            }
            $ch = curl_init() or die(curl_error($ch));
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
            curl_setopt($ch, CURLOPT_URL, $post_url); // here the request is sent to payment gateway 
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); //create a SSL connection object server-to-server
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            if ($host == 'https') {
                curl_setopt($ch, CURLOPT_PORT, 443); // port 443
                curl_setopt($ch, CURLOPT_SSL_CIPHER_LIST, 'ECDHE-RSA-AES128-GCM-SHA256,ECDHE-ECDSA-AES128-SHA');
            }
            if (curl_error($ch)) {
                Sentry\captureMessage(' API Error: ' . curl_error($ch));
                SwipezLogger::error(__CLASS__, 'Curl Error: Error while calling invoice Post Data: ' . $post_string . ' API Error: ' . curl_error($ch));
            }
            $data1 = curl_exec($ch);
            curl_close($ch);
            return $data1;
        } catch (Exception $e) {
            Sentry\captureException($e);
            SwipezLogger::error(__CLASS__, 'Error while curl request Post string: ' . $post_string . ' Post Url: ' . $post_url . ' Error: ' . $e->getMessage());
        }
    }

    function getPlanInvoiceDetails($xway_det)
    {
        try {
            $plan_details = $this->common->getSingleValue('prepaid_plan', 'plan_id', $xway_det['plan_id']);
            $merchant_id = $plan_details['merchant_id'];
            $template_id = $this->common->getRowValue('plan_invoice_template', 'merchant_setting', 'merchant_id', $merchant_id);
            $user_id = $this->common->getRowValue('user_id', 'merchant', 'merchant_id', $merchant_id);
            if ($template_id == '') {
                require_once MODEL . 'merchant/LandingModel.php';
                $landingModel = new LandingModel();
                $template_id = $landingModel->getplantemplate($user_id);
            }
            $security_key = $this->common->getSingleValue('merchant_security_key', 'merchant_id', $merchant_id);
            $total_tax = $plan_details['tax1_percent'] + $plan_details['tax2_percent'] + 100;
            $base_amount = $plan_details['price'];
            if ($total_tax > 100) {
                $base_amount = $base_amount * 100 / $total_tax;
                $base_amount = $this->roundAmount($base_amount, 2);
            }
            if (empty($security_key)) {
                require_once MODEL . 'merchant/LandingModel.php';
                $landingModel = new LandingModel();
                $access_key = sha1($merchant_id . $user_id);
                $secret_key = sha1($user_id . $merchant_id);
                $landingModel->saveSecurityKey($merchant_id, $user_id, $access_key, $secret_key);
            } else {
                $access_key = $security_key['access_key_id'];
                $secret_key = $security_key['secret_access_key'];
            }
            $array[0]['type'] = "setting";
            $array[0]['template_id'] = $this->encrypt->encode($template_id);
            $array[0]['access_key'] = $access_key;
            $array[0]['secret_key'] = $secret_key;
            $array[0]['billing_cycle_name'] = date('Y M') . ' Plan';
            $array[0]['invoice_enable'] = "1";
            $array[1] = array('type' => 'customer_field', 'name' => 'customer_name', 'value' => $xway_det['name']);
            $array[2] = array('type' => 'customer_field', 'name' => 'email', 'value' => $xway_det['email']);
            $array[3] = array('type' => 'customer_field', 'name' => 'mobile', 'value' => $xway_det['phone']);
            $array[4] = array('type' => 'customer_field', 'name' => 'address', 'value' => $xway_det['address']);
            $array[5] = array('type' => 'customer_field', 'name' => 'city', 'value' => $xway_det['city']);
            $array[6] = array('type' => 'customer_field', 'name' => 'state', 'value' => $xway_det['state']);
            $array[7] = array('type' => 'customer_field', 'name' => 'zipcode', 'value' => $xway_det['postal_code']);
            $array[8] = array('type' => 'customer_field', 'name' => 'customer_code', 'value' => $xway_det['udf1']);
            $array[9] = array('type' => 'base_amount', 'name' => 'base_amount', 'value' => $base_amount);
            $array[10] = array('type' => 'purpose', 'name' => 'purpose', 'value' => $plan_details['plan_name']);
            $array[11] = array('type' => 'particular', 'name' => 'particular1', 'description' => $plan_details['plan_name'], 'sac_code' => $plan_details['duration'] . ' Month');
            if ($plan_details['tax1_percent'] > 0) {
                $array[12] = array('type' => 'custom_tax', 'label' => $plan_details['tax1_text'], 'value' => $plan_details['tax1_percent']);
            }
            if ($plan_details['tax2_percent'] > 0) {
                $array[13] = array('type' => 'custom_tax', 'label' => $plan_details['tax2_text'], 'value' => $plan_details['tax2_percent']);
            }
            $return['json'] = json_encode($array);
            $return['merchant_id'] = $xway_det['merchant_id'];
            $return['transaction_id'] = $xway_det['xway_transaction_id'];
            $return['amount'] = $xway_det['amount'];
            return $return;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, 'Error while curl request Post string: Error: ' . $e->getMessage());
        }
    }

    function setRedirectPost($transaction_id, $status, $company_name)
    {
        $data = $this->common->getSingleValue('form_builder_transaction', 'transaction_id', $transaction_id);
        $json_array = json_decode($data['json'], 1);
        $json_val = array();
        $post_param = array();
        foreach ($json_array as $j) {
            if (isset($j['label'])) {
                $json_val[strtolower($j['label'])] = $j['value'];
            }
            if ($j['type'] == 'web_redirect') {
                $post_param = $j['post_param'];
            }
        }
        if ($status == 'success') {
            $req_det = array();
            $customer_name = '';
            require_once MODEL . 'patron/FormModel.php';
            $form_model = new FormModel();
            if ($data['payment_request_id'] != '') {
                $req_det = $form_model->getRequestDetail($data['payment_request_id']);
                $customer_name = $req_det['first_name'] . ' ' . $req_det['last_name'];
            }
        }
        $array['transaction_ref_no'] = $transaction_id;
        $array['amount'] = $data['amount'];
        $array['status'] = $status;
        $array['company_name'] = $company_name;
        $array['paid_date'] = $data['last_update_date'];
        $array['customer_id'] = $req_det['customer_id'];
        $array['customer_code'] = $req_det['customer_code'];
        $array['invoice_number'] = $req_det['invoice_number'];
        $array['customer_contact_name'] = $customer_name;
        $array['address'] = $req_det['address'];
        $array['city'] = $req_det['city'];
        $array['zipcode'] = $req_det['zipcode'];
        foreach ($post_param as $key => $val) {
            $array[$key] = $json_val[$val];
        }
        return $array;
    }

    function setInvoicePluginValues($template_id, $type = 'web')
    {
        $template_row = $this->common->getSingleValue('invoice_template', 'template_id', $template_id);
        $plugin = $template_row['plugin'];
        $plugin_array = json_decode($plugin, 1);
        if ($plugin_array['has_deductible'] == 1) {
            $deduct = array();
            if (!empty($_POST['deduct_tax'])) {
                foreach ($_POST['deduct_tax'] as $key => $value) {
                    $deduct[] = array('tax_name' => $value, 'percent' => $_POST['deduct_percent'][$key], 'applicable' => $_POST['deduct_applicable'][$key], 'total' => $_POST['deduct_total'][$key]);
                }
            }
            $plugin_array['deductible'] = $deduct;
        }
        if ($type == "web") {
            if ($plugin_array['has_supplier'] == 1) {
                $plugin_array['supplier'] = $_POST['supplier'];
            }
            if ($plugin_array['has_cc'] == 1) {
                $plugin_array['cc_email'] = $_POST['cc'];
            }
            if ($plugin_array['has_covering_note'] == 1) {
                $plugin_array['default_covering_note'] = $_POST['covering_id'];
            }
            if ($plugin_array['has_coupon'] == 1) {
                $plugin_array['coupon_id'] = $_POST['coupon_id'];
            }

            if (isset($_POST['enable_autocollect'])) {
                if ($_POST['enable_autocollect'] == 1) {
                    if ($_POST['auto_collect_plan_id'] > 0) {
                        $plugin_array['has_autocollect'] = 1;
                        $plugin_array['autocollect_plan_id'] = $_POST['auto_collect_plan_id'];
                    } else {
                        if ($_POST['mode'] == 1 || $_POST['mode'] == 3) {
                            $interval_type = ($_POST['mode'] == 3) ? 'month' : 'day';
                            $intervals = $_POST['repeat_every'];
                            $occurrence = ($_POST['occurrence'] > 0) ? $_POST['occurrence'] : 12;
                            $plan_id = $this->common->getRowValue('plan_id', 'autocollect_plans', 'merchant_id', $template_row['merchant_id'], 1, ' and amount=' . $_POST['grand_total'] . ' and occurrence=' . $occurrence . " and interval_type='" . $interval_type . "' and intervals=" . $intervals);
                            if ($plan_id == false) {
                                $array['name'] = 'Subscription';
                                $array['amount'] = $_POST['grand_total'];
                                $array['occurrence'] = $occurrence;
                                $array['description'] = 'Auto collect subscription';
                                $array['type'] = 'PERIODIC';
                                $array['interval_type'] = $interval_type;
                                $array['intervals'] = $intervals;
                                $post_string = json_encode($array);
                                $result = Helpers::APIrequest('v1/autocollect/plan/save', $post_string);
                                $response = json_decode($result, 1);
                                if ($response['plan_id'] > 0) {
                                    $plan_id = $response['plan_id'];
                                }
                            }
                            if ($plan_id != false) {
                                $plugin_array['has_autocollect'] = 1;
                                $_POST['auto_collect_plan_id'] = $plan_id;
                                $plugin_array['autocollect_plan_id'] = $plan_id;
                            }
                        }
                    }
                }
            }
            if ($plugin_array['has_custom_notification'] == 1) {
                $plugin_array['custom_email_subject'] = $_POST['custom_subject'];
                $plugin_array['custom_sms'] = $_POST['custom_sms'];
            }
            if ($plugin_array['has_custom_reminder'] == 1) {
                $_POST['has_custom_reminder'] = 1;
                $reminders = array();
                if (!empty($_POST['reminders'])) {
                    foreach ($_POST['reminders'] as $key => $value) {
                        $reminders[$value] = array('email_subject' => $_POST['reminder_subject'][$key], 'sms' => $_POST['reminder_sms'][$key]);
                    }
                }
                $plugin_array['reminders'] = $reminders;
            }
        }

        if (isset($_POST['file_upload'])) {
            $files = explode(',', $_POST['file_upload']);
            foreach ($files as $file) {
                $plugin_array['files'][] = $file;
            }
        }


        if (isset($plugin_array['has_online_payments'])) {
            $plugin_array['has_online_payments'] = $_POST['has_online_payments'];
            $plugin_array['enable_payments'] = ($plugin_array['has_online_payments'] == 1) ? $_POST['enable_payments'] : 0;
        }

        if (isset($plugin_array['has_partial'])) {
            $plugin_array['has_partial'] = $_POST['has_partial'];
            $plugin_array['partial_min_amount'] = ($plugin_array['has_partial'] == 1) ? $_POST['partial_min_amount'] : 0;
        }

        if ($plugin_array['has_franchise'] == 1) {
            $plugin_array['franchise_id'] = $_POST['franchise_id'];
            $plugin_array['franchise_name_invoice'] = $_POST['franchise_name_invoice'];
        }
        if ($plugin_array['has_vendor'] == 1) {
            $plugin_array['vendor_id'] = $_POST['vendor_id'];
        }
        if ($_POST['has_e_invoice'] == 1) {
            $plugin_array['has_e_invoice'] = 1;
            $plugin_array['notify_e_invoice'] = ($_POST['notify_e_invoice'] == 1) ? 1 : 0;
        } else {
            unset($plugin_array['has_e_invoice']);
        }

        if ($plugin_array['has_signature'] == 1) {
            $signature = $this->common->getRowValue('`value`', 'merchant_config_data', 'merchant_id', $template_row['merchant_id'], 0, " and `key`='DIGITAL_SIGNATURE'");
            $plugin_array['signature'] = json_decode($signature, 1);
        } else {
            unset($plugin_array['signature']);
        }
        if ($_POST['invoice_title'] != '') {
            $plugin_array['invoice_title'] = $_POST['invoice_title'];
        }
        $plugin_array['profile_id'] = $template_row['profile_id'];
        return json_encode($plugin_array);
    }

    function roundAmount($amount, $num)
    {
        $text = number_format($amount, $num);
        $amount = str_replace(',', '', $text);
        return $amount;
    }

    function setSubscriptionPost()
    {
        $_POST['col_position'][] = 5;
        $_POST['function_id'][] = "-1";
        $_POST['requestvalue'][] = $_POST['bill_date'];
        $_POST['col_position'][] = 6;
        $_POST['function_id'][] = "-1";
        $_POST['requestvalue'][] = $_POST['due_date'];
        $start_date = new DateTime($_POST['bill_date']);
        $duedate = new DateTime($_POST['due_date']);
        $mode = $_POST['mode'];
        $repeat_every = $_POST['repeat_every'];
        $repeat_on = isset($_POST['repeat_on']) ? $_POST['repeat_on'] : 0;
        $end_mode = $_POST['end_mode'];

        if (isset($_POST['end_date'])) {
            $end_date = new DateTime($_POST['end_date']);
            $end_date = $end_date->format('Y-m-d');
        } else {
            $end_date = date('Y-m-d');
        }

        if (isset($_POST['billing_start_date'])) {
            $billing_start_date = new DateTime($_POST['billing_start_date']);
            $billing_start_date = $billing_start_date->format('Y-m-d');
        } else {
            $billing_start_date = NULL;
        }
        $billing_period = isset($_POST['billing_period']) ? $_POST['billing_period'] : NULL;
        $period_type = isset($_POST['period_type']) ? $_POST['period_type'] : NULL;


        $date_diff = isset($_POST['due_datetime_diff']) ? $_POST['due_datetime_diff'] : 0;
        $carry_due = isset($_POST['carry_due']) ? $_POST['carry_due'] : 0;
        $occurrence = ($_POST['occurrence'] > 0) ? $_POST['occurrence'] : 0;
        $display_text = $_POST['display_text'];
        switch ($mode) {
            case 1:
                $add = 'days';
                $time_period = '+1 DAY';
                break;
            case 2:
                $add = 'weeks';
                $time_period = '+1 WEEK';
                break;
            case 3:
                $add = 'months';
                $time_period = '+1 MONTH';
                break;
            case 4:
                $add = 'year';
                $time_period = '+1 YEAR';
                break;
        }

        if ($end_mode == 2) {
            $days = ($occurrence * $repeat_every) - 1;
            $start_date = $start_date->format('Y-m-d');
            $end_date = strtotime("+" . $days . " " . $add . "", strtotime($start_date));
            $end_date = date('Y-m-d', $end_date);
        }

        //calculate occurencess from end_date
        if ($end_mode == 3) {
            if (isset($_POST['end_date'])) {
                $date1 = strtotime($_POST['bill_date']);
                $date2 = strtotime($_POST['end_date']);
                $occurenceCnt = 1;

                while (($date1 = strtotime($time_period, $date1)) <= $date2) {
                    $occurenceCnt++;
                }
                //$repeat_every = ($data['repeat_every']!=0) ? $data['repeat_every'] : 1;
                $occurrence = (int)($occurenceCnt / $repeat_every);
            }
        }

        $array['mode'] = $mode;
        $array['repeat_every'] = $repeat_every;
        $array['repeat_on'] = $repeat_on;
        $array['end_mode'] = $end_mode;
        $array['end_date'] = $end_date;
        $array['occurrence'] = $occurrence;
        $array['display_text'] = $display_text;
        $array['date_diff'] = $date_diff;
        $array['carry_due'] = $carry_due;
        $array['billing_start_date'] = $billing_start_date;
        $array['billing_period'] = $billing_period;
        $array['period_type'] = $period_type;
        return $array;
    }

    function setUpdateInvoiceSmarty($info, $staging = 0)
    {
        $plugin = json_decode($info['plugin_value'], 1);
        $payment_request_id = $info['payment_request_id'];
        $merchant_id = $info['merchant_id'];
        $temp_info = $this->common->getSingleValue('invoice_template', 'template_id', $info['template_id']);
        $rows = $this->common->getTemplateInvoiceBreakup($payment_request_id, $staging);
        $rows = $this->getinvoiceBreckup($rows);
        if ($plugin['has_supplier']) {
            $supplierlist = $plugin['supplier'];
            $supplier = $this->common->getSupplierlist($this->session->get('userid'));
            $supplierlist2 = $this->common->getInvoiceSupplierlist($supplierlist);
            $smarty["exist_supplier_id"] = $plugin['supplier'];
            $smarty["supplierlist"] = $supplierlist2;
        }
        if ($staging == 0) {
            $particular = $this->common->getListValue('invoice_particular', 'payment_request_id', $payment_request_id, 1, " and (item<>'' or total_amount<>0)");
        } else {
            $particular = $this->common->getListValue('staging_invoice_particular', 'payment_request_id', $payment_request_id, 1, " and (item<>'' or total_amount<>0)");
        }
        $smarty["info"] = $info;

        if ($plugin['has_covering_note'] == 1) {
            $logo = $this->common->getRowValue('logo', 'merchant_landing', 'merchant_id', $merchant_id);
            if ($logo != '') {
                $logo = $this->app_url . '/uploads/images/landing/' . $logo;
            } else {
                $logo = $this->app_url . '/assets/frontend/onepage2/img/logo_scroll.png';
            }
            $smarty["logo"] = $logo;
            $covering_list = $this->common->getListValue('covering_note', 'merchant_id', $merchant_id, 1);
            $smarty["covering_list"] = $covering_list;
            $smarty["covering_id"] = $plugin['default_covering_note'];
        }

        if ($plugin['has_autocollect'] == 1) {
            $autocollect_plans = $this->common->getListValue('autocollect_plans', 'merchant_id', $merchant_id, 1, 'and status=1');
            $smarty["autocollect_plans"] = $autocollect_plans;
        }

        $profile_list = $this->common->getListValue('merchant_billing_profile', 'merchant_id', $merchant_id, 1);
        $smarty["billing_profile"] = $profile_list;


        $smarty["temp_info"] = $temp_info;

        if ($plugin['has_coupon'] == 1) {
            require MODEL . 'merchant/CouponModel.php';
            $couponmodel = new CouponModel();
            $coupon_list = $couponmodel->getActiveCoupon($merchant_id);
            $smarty["coupon_list"] = $coupon_list;
        }
        if ($plugin['has_franchise'] == 1) {
            $sub_franchise_id = $this->session->get('sub_franchise_id');
            if ($sub_franchise_id > 0) {
                $franchise_list[] = $this->common->getSingleValue('franchise', 'franchise_id', $sub_franchise_id, 1);
            } else {
                $franchise_list = $this->common->getListValue('franchise', 'merchant_id', $merchant_id, 1, 'and status=1');
            }
            $smarty["franchise_list"] = $franchise_list;
        }
        if ($plugin['has_vendor'] == 1) {
            $vendor_list = $this->common->getListValue('vendor', 'merchant_id', $merchant_id, 1, 'and status=1');
            $smarty["vendor_list"] = $vendor_list;
        }

        require_once MODEL . 'merchant/CustomerModel.php';
        $Customermodel = new CustomerModel();
        $customer_list = $Customermodel->getCustomerallList($merchant_id);
        $customer_column = $Customermodel->getcustomercoldetails($info['customer_id'], $info['template_id'], $merchant_id);
        $c_column = $Customermodel->getCustomerBreakup($merchant_id);
        $smarty["column"] = $c_column;
        $smarty["customer_column"] = $customer_column;
        $smarty["customer_list"] = $customer_list;
        $requestvalue = $rows['request'];
        $template_type = $info['template_type'];
        $int = 0;
        require_once PACKAGE . 'swipez/function/DataFunction.php';
        foreach ($rows['header'] as $column) {
            if ($column['column_position'] == 5) {
                $rows['header'][$int]['id'] = 'bill_date';
            }
            if ($column['column_position'] == 6) {
                $rows['header'][$int]['id'] = 'due_date';
            }

            if ($column['function_id'] > 0) {
                $function_details = $this->common->getSingleValue('column_function', 'function_id', $column['function_id']);
                $function = new $function_details['php_class']();
                $function->req_type = 'Invoice_update';
                $function->function_id = $column['function_id'];
                $mapping_details = $this->common->getfunctionMappingDetails($column['column_id'], $column['function_id']);
                if (!empty($mapping_details)) {
                    $function->param_name = $mapping_details['param'];
                    $function->param_value = $mapping_details['value'];
                }
                $function->set();
                if (!isset($rows['header'][$int]['id'])) {
                    $rows['header'][$int]['id'] = $function_details['column_name'];
                }
                $function_script .= $function->get('script');
            }
            $int++;
        }


        if ($plugin['has_custom_reminder'] == 1) {
            $custom_reminder = $this->common->getListValue('invoice_custom_reminder', 'payment_request_id', $payment_request_id, 1);
            $reminder_array = explode(',', $temp_info['custom_reminder']);
            $smarty["reminders"] = $custom_reminder;
            $json = json_encode($reminder_array);
        }

        if ($temp_info['template_type'] == 'franchise') {
            $sale_details = $this->common->getListValue('invoice_food_franchise_sales', 'payment_request_id', $payment_request_id, 1);
            $sale_summary = $this->common->getSingleValue('invoice_food_franchise_summary', 'payment_request_id', $payment_request_id);
            $smarty["sale_details"] = $sale_details;
            $smarty["sale_summary"] = $sale_summary;
        }

        if ($plugin['has_signature'] == 1) {
            $smarty['signature'] = $plugin['signature'];
        }

        if ($template_type == 'travel_ticket_booking') {
            $ticket_details = $this->common->getListValue('invoice_travel_ticket_detail', 'payment_request_id', $payment_request_id, 1);
            $particular = $ticket_details;
        }

        if ($info['template_type'] == 'travel') {
            $ticket_details = $this->common->getListValue('invoice_travel_particular', 'payment_request_id', $payment_request_id, 1);
            $smarty["ticket_detail"] = $ticket_details;
            $smarty['sec_col'] = array("A", "B", "C", "D", "E", "F", "G", "H", "I");
            $smarty['travel_col'] = ["booking_date", "journey_date", "from_date", "to_date", "qty", "rate", "gst", "tax_amount", "discount", "total_amount", "amount", "charge", "name", "type", "from", "to", "item", "product_gst"];

            if ($info['template_type'] == 'travel') {
                $secarray = array();
                foreach ($ticket_details as $td) {
                    if (!in_array($td['type'], $secarray)) {
                        $secarray[] = $td['type'];
                    }
                    $smarty['secarray'] = $secarray;
                }
            }
        }
        //dd($ticket_details);


        $tax_list = $this->common->getListValue('merchant_tax', 'merchant_id', $merchant_id, 1);
        $tax_array = array();
        $tax_rate_array = array();
        $tax_type = $this->common->getListValue('config', 'config_type', 'tax_type');
        $smarty["tax_type"] = $tax_type;
        if (!empty($tax_list)) {
            foreach ($tax_list as $ar) {
                $tax_array[$ar['tax_id']] = array('tax_type' => $ar['tax_type'], 'tax_name' => $ar['tax_name'], 'percentage' => $ar['percentage'], 'fix_amount' => $ar['fix_amount']);
                $tax_rate_array[$ar['tax_name']] = $ar['percentage'];
            }
            $smarty["tax_array"] = json_encode($tax_array);
            $smarty["tax_list"] = $tax_array;
            $smarty["merchant_tax_list"] = $tax_list;
            $smarty["tax_rate_array"] = json_encode($tax_rate_array);
        }

        $product_list = $this->common->getListValue('merchant_product', 'merchant_id', $merchant_id, 1);
        $product_array = array();
        if (!empty($product_list)) {
            foreach ($product_list as $ar) {
                $tax = array();
                foreach (explode(',', $ar['taxes']) as $tt) {
                    $tax[] = $tt;
                }
                $products[$ar['product_name']] = array('price' => $ar['price'], 'sac_code' => $ar['sac_code'], 'product_taxes' => $tax, 'name' => $ar['product_name']);
            }
            $smarty["product_json"] = json_encode($products);
            $smarty["product_list"] = $products;
        }
        $smarty["properties"] = json_decode($temp_info['properties'], 1);
        $smarty["supplier"] = $supplier;
        $smarty["payment_request_id"] = $this->encrypt->encode($payment_request_id);
        $smarty["customer_column"] = $customer_column;
        $smarty["request_value"] = $requestvalue;
        $smarty["template_id"] = $this->encrypt->encode($info['template_id']);
        $smarty["main_header"] = $rows['main_header'];
        $smarty["header"] = $rows['header'];
        $smarty["customer_state"] = $info['customer_state'];
        $smarty["merchant_state"] = $info['merchant_state'];
        $smarty["plugin"] = $plugin;
        $smarty["particular_column"] = json_decode($info['particular_column'], 1);
        $smarty["particular"] = $particular;
        $tax = $this->common->getInvoiceTax($payment_request_id, $staging);
        $smarty["tax"] = $tax;

        $state_code = $this->common->getListValue('config', 'config_type', 'gst_state_code');
        $smarty["state_code"] = $state_code;

        $smarty["bds_column"] = $rows['bds'];
        $smarty["particularTotal"] = $info['particular_total'];
        $smarty["taxTotal"] = $info['tax_total'];
        $smarty["staging"] = $staging;
        //$smarty["pg"]= $pgdetails);
        $smarty["info"] = $info;
        $smarty["template_type"] = $template_type;
        $smarty["function_script"] = $function_script;
        return $smarty;
    }

    function getPackageInvoiceDetails($fee_transaction_id)
    {
        try {
            $details = $this->common->getSingleValue('package_transaction', 'package_transaction_id', $fee_transaction_id);
            if (substr($details['pg_ref_no'], 0, 1) == 'H') {
                return true;
            }
            $transaction_details = $this->common->getSingleValue('package_transaction_details', 'package_transaction_id', $fee_transaction_id);
            $merchant_id = SUPPORT_MERCHANT_ID;
            $merchant = $this->common->getSingleValue('merchant', 'merchant_id', $details['merchant_id']);
            $billing_profile = $this->common->getSingleValue('merchant_billing_profile', 'merchant_id', $details['merchant_id']);
            $admin_customer_code = ($merchant['admin_customer_code'] != '') ? $merchant['admin_customer_code'] : "";
            $security_key = $this->common->getSingleValue('merchant_security_key', 'merchant_id', $merchant_id);
            $total_tax = 118;
            $base_amount = $details['amount'];
            if ($total_tax > 100) {
                $base_amount = $base_amount * 100 / $total_tax;
                $base_amount = $this->roundAmount($base_amount, 2);
            }
            $ids = array(1155452, 419065, 43, 87, 134, 135, 141);
            //$ids = array(88056, 88057, 4830, 4839, 4840, 4841, 4842);

            $apiArray['access_key_id'] = $security_key['access_key_id'];
            $apiArray['secret_access_key'] =  $security_key['secret_access_key'];
            $apiArray['template_id'] = PACKAGE_TEMPLATE_KEY;
            $invoice['customer_code'] = $admin_customer_code;
            $invoice['bill_cycle_name'] = date('Y M') . ' SAAS Fee';
            $invoice['bill_date'] = date('Y-m-d');
            $invoice['due_date'] = date('Y-m-d');
            $invoice['advance_received'] = 0;
            $invoice['enable_partial_payment'] = '0';
            $invoice['partial_min_amount'] = '50';
            $invoice['invoice_properties']['notify_patron'] = '0';
            $invoice['custom_header_fields'][] = array('id' => "$ids[0]", 'name' => "Purchase Order No", 'type' => "text", 'value' => "");
            $invoice['custom_header_fields'][] = array('id' => "$ids[1]", 'name' => "SAC Code", 'type' => "text", 'value' => "997331");
            $invoice['particular_rows'][] = array("item" => $details['narrative'], "description" => "1 Year", "sac_code" => "997331", "total_amount" => $base_amount);

            if ($billing_profile['reg_state'] == '' || strtolower($billing_profile['reg_state']) == 'maharashtra') {
                $invoice['tax_rows'][] = array("name" => "SGST@9%", "percentage" => "9.00", "applicable_on" => $base_amount);
                $invoice['tax_rows'][] = array("name" => "CGST@9%", "percentage" => "9.00", "applicable_on" => $base_amount);
            } else {
                $invoice['tax_rows'][] = array("name" => "IGST@18%", "percentage" => "18.00", "applicable_on" => $base_amount);
            }

            $invoice['cc_email'] = array("sarang@swipez.in", "support@swipez.in");
            $customer['customer_name'] = $transaction_details['name'];
            $customer['email'] = $transaction_details['email'];
            $customer['mobile'] = $transaction_details['mobile'];
            $customer['address'] = $transaction_details['address'];
            $customer['city'] = $transaction_details['city'];
            $customer['state'] = $transaction_details['state'];
            $customer['zipcode'] = $transaction_details['zipcode'];

            $customer['custom_fields'][] = array('id' => "$ids[2]", 'name' => "Company Name", 'type' => "text", 'value' => $merchant['company_name']);
            $customer['custom_fields'][] = array('id' => "$ids[3]", 'name' => "Customer GSTN", 'type' => "gst", 'value' => $billing_profile['gst_number']);
            $customer['custom_fields'][] = array('id' => "$ids[4]", 'name' => "Customer PAN Number", 'type' => "text", 'value' => $billing_profile['pan']);
            $customer['custom_fields'][] = array('id' => "$ids[5]", 'name' => "Swipez Merchant Id", 'type' => "text", 'value' => $merchant['merchant_id']);
            $customer['custom_fields'][] = array('id' => "$ids[6]", 'name' => "Notes", 'type' => "text", 'value' => "");
            $invoice['new_customer'] = $customer;
            $apiArray['invoice'][0] = $invoice;
            $response = $this->saveInvoiceApi($apiArray, $details['amount'], $fee_transaction_id, 'Online');
            if ($response['offline_transaction_id']) {
                $this->common->genericupdate('package_transaction', 'pg_ref_no', $response['offline_transaction_id'], 'package_transaction_id', $fee_transaction_id, 'System');
            }
            return $response;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, 'Error while curl request Post string: Error: ' . $e->getMessage());
        }
    }
}
