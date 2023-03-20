<?php

include_once SWIPEZ_UTIL_PATH . '/src/shorturl/SwipezShortURLWrapper.php';

use Illuminate\Support\Facades\Redis;

/**
 * Profile controller class to handle profile update for merchant
 */
class Directpaylink extends Controller
{

    function __construct()
    {
        parent::__construct();

        //TODO : Check if using static function is causing any problems!
        $this->validateSession('merchant');
        $this->view->js = array('setting');
    }

    /**
     * Display merchant profile
     */
    function index()
    {
        try {
            if (isset($_POST['display_url'])) {
                $_POST['display_url'] = trim($_POST['display_url']);
                $_POST['display_url'] = str_replace(' ', '', $_POST['display_url']);
                require_once CONTROLLER . 'merchant/Registervalidator.php';
                require_once MODEL . 'merchant/CompanyprofileModel.php';
                $compModel = new CompanyprofileModel();
                $validator = new Registervalidator($compModel);
                $validator->validateHome($this->merchant_id);
                $hasErrors = $validator->fetchErrors();
                if ($hasErrors == false) {
                    $this->common->genericupdate('merchant', 'display_url', $_POST['display_url'], 'merchant_id', $this->merchant_id, $this->user_id);
                    $this->smarty->assign("success", 'Display URL saved successfully.');
                } else {
                    $this->smarty->assign("haserrors", $hasErrors);
                }
            }

            $merchant_id = $this->session->get('merchant_id');
            $merchant = $this->common->getSingleValue('merchant', 'merchant_id', $merchant_id);
            $setting = $this->common->getSingleValue('merchant_setting', 'merchant_id', $merchant_id);
            if (env('APP_ENV') != 'LOCAL') {
                //menu list
                $mn1 = Redis::get('merchantMenuList' . $this->merchant_id);
                $item_list = json_decode($mn1, 1);
                $row_array['name'] = 'Quick link';
                $row_array['link'] = '/merchant/directpaylink';
                $item_list[] = $row_array;
                Redis::set('merchantMenuList' . $this->merchant_id, json_encode($item_list));
            }

            if ($setting['has_qrcode'] == 1) {
                $this->smarty->assign("enable_qr", 1);
            }



            if ($merchant['is_legal_complete'] == 0) {
                $this->session->set('return_url', '/merchant/profile/complete/document');
                $this->session->set('button_text', 'Upload now');
                $this->session->set('errorTitle', 'Activate quick payment link');
                $this->setError('Activate quick payment link', 'Payment links can be sent to your customers to collect payments online. Complete your KYC documents and activate online payments.');
                header("Location:/error");
                exit;
            }

            $directpay_link = $this->app_url . '/m/' . $merchant['display_url'] . '/directpay';
            $qrlink = $this->app_url . '/m/' . $merchant['display_url'] . '/qrcode';

            $list = $this->common->getListValue('direct_pay_request', 'merchant_id', $this->merchant_id, 1);
            $formlist = $this->common->getListValue('form_builder_request', 'merchant_id', $this->merchant_id, 1);
            $list = $this->generic->getEncryptedList($list, 'encrypted_id', 'id');
            $list = $this->generic->getMoneyFormatList($list, 'amount', 'amount');
            $formlist = $this->generic->getEncryptedList($formlist, 'encrypted_id', 'id');
            $this->smarty->assign("list", $list);
            $this->smarty->assign("formlist", $formlist);

            $short_link = $setting['directpay_link'];
            $this->smarty->assign("directpaylink", $directpay_link);
            $this->smarty->assign("qrlink", $qrlink);
            $this->smarty->assign("display_url", $merchant['display_url']);
            $this->smarty->assign("shortlink", $short_link);
            $this->smarty->assign("currency_list", $this->session->get('currency'));
            $this->view->datatablejs = 'table-no-export';
            $this->view->selectedMenu = array(3, 22);
            $this->view->title = "Direct pay link";
            $this->smarty->assign('title', 'Payment Links');
            //Breadcumbs array start
            $breadcumbs_array = array(
                array('title' => 'Collect Payments ', 'url' => ''),
                array('title' => 'Payment Links', 'url' => '')
            );
            $this->smarty->assign("links", $breadcumbs_array);
            //Breadcumbs array end

            $country_code = $this->common->getListValue('config', 'config_type', 'country_name');
            $this->smarty->assign("country_code", $country_code);

            $this->view->header_file = ['list'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/directpay/index.tpl');
            $this->view->render('footer/list');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E016]Error while merchant profile initiate Error: for merchant [' . $merchant_id . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function qrcode($type)
    {
        try {
            $merchant_id = $this->session->get('merchant_id');
            $merchant = $this->common->getSingleValue('merchant', 'merchant_id', $merchant_id);
            $setting = $this->common->getSingleValue('merchant_setting', 'merchant_id', $merchant_id);
            if ($setting['has_qrcode'] == 1) {
                $this->smarty->assign("enable_qr", 1);
            } else {
                $this->setError('Qr code feature disabled!', 'To enable the QR code link. Contact to Swipez support at support@swipez.in');
                header("Location:/error");
                exit;
            }
            $qrlink = $this->app_url . '/m/' . $merchant['display_url'] . '/qrcode';
            $this->smarty->assign("qrlink", $qrlink);
            $this->view->title = 'QR code scanner';
            $this->smarty->assign('title', $this->view->title);
            if ($type == 'event') {
                $this->view->selectedMenu = array(8, 156);
                //Breadcumbs array start
                $breadcumbs_array = array(
                    array('title' => 'Events', 'url' => ''),
                    array('title' => $this->view->title, 'url' => '')
                );
                $this->smarty->assign("links", $breadcumbs_array);
                //Breadcumbs array end

            } else {
                //Breadcumbs array start
                $breadcumbs_array = array(
                    array('title' => ' Booking calendar', 'url' => ''),
                    array('title' => $this->view->title, 'url' => '')
                );
                $this->smarty->assign("links", $breadcumbs_array);
                //Breadcumbs array end
                $this->view->selectedMenu = array(9, 157);
            }

            $this->view->description = 'Scan tickets issued to your customers using the QR code scanner.';
            $this->view->header_file = ['list'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/directpay/qrcode.tpl');
            $this->view->render('footer/list');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E016]Error while merchant profile initiate Error: for merchant [' . $merchant_id . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function save()
    {
        try {
            $id = $this->model->saveDirectPayRequest($this->merchant_id, $_POST, $this->user_id);
            $link = $this->encrypt->encode($id);
            $short_link = $this->shortlink($link);
            $this->common->genericupdate('direct_pay_request', 'short_link', $short_link, 'id', $id, $this->user_id);
            $this->session->set('successMessage', 'Direct pay request has been saved successfully.');
            header("Location:/merchant/directpaylink");
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[Ed290]Error while saving direct pay request vendor Error: ' . $e->getMessage());
            header("Location:/error");
        }
    }

    function delete($id)
    {
        try {
            $id = $this->encrypt->decode($id);
            if (!is_numeric($id)) {
                $this->setInvalidLinkError();
            }
            $this->common->genericupdate('direct_pay_request', 'is_active', '0', 'id', $id, $this->user_id, " and merchant_id='" . $this->merchant_id . "'");
            $this->session->set('successMessage', 'Direct pay request have been deleted successfully.');
            header("Location:/merchant/directpaylink");
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E290]Error while deleting vendor Error: ' . $e->getMessage());
            header("Location:/error");
        }
    }

    function shortlink($id = null)
    {
        $merchant_id = $this->session->get('merchant_id');
        $merchant = $this->common->getSingleValue('merchant', 'merchant_id', $merchant_id);
        $extra = '';
        if ($id != null) {
            $extra = "/" . $id;
        }
        $directpay_link[] = $this->app_url . '/m/' . $merchant['display_url'] . '/directpay' . $extra;
        //$shortUrlWrap = new SwipezShortURLWrapper();
        $shortUrlWrap = new Swipez\ShortUrl\ShortUrl();
        $shortUrls = $shortUrlWrap->SaveUrl($directpay_link);
        if ($id != null) {
            return $shortUrls[0];
        } else {
            $this->common->genericupdate('merchant_setting', 'directpay_link', $shortUrls[0], 'merchant_id', $merchant_id, $merchant_id);
            echo $shortUrls[0];
        }
    }

    function setformbuilder()
    {
        $merchant_id = $this->merchant_id;
        $user_id = $this->user_id;
        $gst_number = $this->common->getMerchantProfile($merchant_id, 0, 'gst_number');
        $company_name = $this->common->getRowValue('company_name', 'merchant', 'merchant_id', $merchant_id);
        if ($gst_number == FALSE) {
            echo 'Merchant GST number is empty';
            die();
        }
        $det = $this->common->getRowValue('id', 'form_builder_request', 'merchant_id', $merchant_id);
        if ($det != FALSE) {
            echo 'Form builder request alredy exist';
            die();
        }

        $column1 = 'Business legal name';
        $column2 = 'GST number';
        $column3 = 'Place of supply';

        $form_title = $company_name . " APOB Charges";
        $form_builder_name = "APOB Charges";
        $base_amount = "590.00";
        $purpose = "APOB Charges";
        $grand_total = "696.20";

        $subscript = "INV-";

        require_once MODEL . 'merchant/CustomerModel.php';
        $customerModel = new CustomerModel();
        require_once MODEL . 'merchant/ProfileModel.php';
        $profileModel = new ProfileModel();
        $prefix = "C";
        $is_autogenerate = 1;
        $position = array('L', 'L', 'R');
        $column_name = array($column1, $column2, $column3);
        $datatype = array('text', 'text', 'text');
        $exist_col_id = array();
        $exist_datatype = array();
        $exist_col_name = array();
        $customerModel->saveCustomerStructure($user_id, $merchant_id, $prefix, $is_autogenerate, $position, $column_name, $datatype, $exist_col_id, $exist_datatype, $exist_col_name);
        $seq_id = $profileModel->saveInvoiceNumber($user_id, $merchant_id, $subscript, 0);

        $column_id1 = $this->common->getRowValue('column_id', 'customer_column_metadata', 'merchant_id', $merchant_id, 0, " and column_name='" . $column1 . "'");
        $column_id2 = $this->common->getRowValue('column_id', 'customer_column_metadata', 'merchant_id', $merchant_id, 0, " and column_name='" . $column2 . "'");
        $column_id3 = $this->common->getRowValue('column_id', 'customer_column_metadata', 'merchant_id', $merchant_id, 0, " and column_name='" . $column3 . "'");

        $sql = "call `template_save`(:templatename,:template_type,:user_id,:main_header_id,:main_header_default,:customer_column,:custom_column,:header,:position,:column_type,:sort,:column_position,:function_id,:function_param,:function_val,:is_delete,:headerdatatype,:headertablename,:headermandatory,:particularname,:tax,:debit,:is_debit,:debitdefault,:tnc,:cc,:defaultValue,:particular_total,:tax_total,:ext,:maxposition,:is_supplier,:supplier,:is_coupon,:is_roundoff,:is_cc,:is_vendor,:is_franchise,:franchise_notify_email,:franchise_notify_sms,:franchise_name_invoice,:is_prepaid,:has_webhook,:has_acknowledgement,:has_covering,:covering_id,:has_cust_noti,:cust_subject,:cust_sms,:has_custom_reminder,:custom_reminder,:reminder_subject,:reminder_sms,:created_by,@message,@template_id);";
        $json = '{":templatename":"amazon",":template_type":"isp",":user_id":"' . $user_id . '",":main_header_id":"9~10~11~12",":main_header_default":"Profile~Profile~Profile~Profile",":customer_column":"1~2~3~4~5",":custom_column":"' . $column_id1 . '~' . $column_id3 . '~' . $column_id2 . '",":header":"Billing cycle name~Bill date~Due date~Invoice number",":position":"R~R~R~R",":column_type":"H~H~H~H",":sort":"MCompany name~MMerchant contact~MMerchant email~MMerchant address~CBusiness legal name~CCustomer code~CCustomer name~CEmail ID~CMobile no~CAddress~CPlace of supply~CGST number~HBilling cycle name~HBill date~HDue date~HInvoice number",":column_position":"4~5~6~-1",":function_id":"-1~-1~-1~9",":function_param":"~~~system_generated",":function_val":"~~~' . $seq_id . '",":is_delete":"2~2~2~1",":headerdatatype":"text~date~date~text",":headertablename":"request~request~request~metadata",":headermandatory":"1~1~1~2",":particularname":"",":tax":"",":debit":"",":is_debit":0,":debitdefault":"",":tnc":"",":cc":"",":defaultValue":"",":particular_total":"Sub total",":tax_total":"Tax total",":ext":".",":maxposition":"6",":is_supplier":0,":supplier":"",":is_coupon":0,":is_roundoff":0,":is_cc":0,":is_vendor":0,":is_franchise":0,":franchise_notify_email":0,":franchise_notify_sms":0,":franchise_name_invoice":0,":is_prepaid":0,":has_webhook":0,":has_acknowledgement":0,":has_covering":0,":covering_id":0,":has_cust_noti":0,":cust_subject":"",":cust_sms":"",":has_custom_reminder":0,":custom_reminder":"",":reminder_subject":"[\"\",\"\",\"\"]",":reminder_sms":"[\"\",\"\",\"\"]",":created_by":"' . $user_id . '"}';
        $params = json_decode($json, 1);
        $this->common->queryexecute($sql, $params);
        $det = $this->common->querysingle('select @message,@template_id');
        $template_id = $det['@template_id'];
        $template_link = $this->encrypt->encode($template_id);

        $security = $this->common->getSingleValue('merchant_security_key', 'merchant_id', $merchant_id);
        if (empty($security)) {
            $access_key = $this->encrypt->encode($user_id . $merchant_id);
            $secret_key = $this->encrypt->encode($merchant_id . $user_id);

            $sql = "INSERT INTO `merchant_security_key` (`merchant_id`, `user_id`, `access_key_id`, `secret_access_key`, `is_active`,`created_date`) 
VALUES ('" . $merchant_id . "', '" . $user_id . "', '" . $access_key . "', '" . $secret_key . "', '1',now());";
            $this->common->queryexecute($sql);
        } else {
            $access_key = $security['access_key_id'];
            $secret_key = $security['secret_access_key'];
        }

        $json = '[{"type":"setting","template_id":"' . $template_link . '","access_key":"' . $access_key . '","secret_key":"' . $secret_key . '","billing_cycle_name":"Amazon APOB","invoice_enable":"1","menu_home":"1","menu_paybill":"0","menu_package":"0","menu_booking":"0","menu_policies":"1","menu_about":"1","menu_contact":"1"},{"type":"label","subtype":"h3","display":"1","label":"' . $form_title . '","className":"form-control","value":"","name":"label"},{"type":"customer_custom_field","subtype":"text","required":"1","column_id":"' . $column_id1 . '","display":"1","label":"' . $column1 . '","className":"form-control","value":"","name":"field-' . $column_id1 . '"},{"type":"customer_field","subtype":"text","required":"1","validation":"name_text","display":"1","label":"Contact person name","className":"form-control","value":"","name":"customer_name"},{"type":"customer_field","subtype":"text","required":"1","validation":"email","display":"1","label":"Email","className":"form-control","value":"","name":"email"},{"type":"customer_field","subtype":"text","required":"1","validation":"mobile","display":"1","label":"Mobile","className":"form-control","value":"","name":"mobile"},{"type":"customer_field","subtype":"textarea","required":"0","validation":"address","display":"1","label":"Address","className":"form-control","value":"","name":"address"},{"type":"customer_field","subtype":"text","required":"0","validation":"state","display":"1","label":"City","className":"form-control","value":"","name":"city"},{"type":"customer_field","subtype":"text","required":"0","validation":"state","display":"1","label":"State","className":"form-control","value":"","name":"state"},{"type":"customer_field","subtype":"text","required":"0","validation":"zipcode","display":"1","label":"Zipcode","className":"form-control","value":"","name":"zipcode"},{"type":"customer_custom_field","subtype":"text","required":"0","column_id":"' . $column_id3 . '","display":"1","label":"' . $column3 . '","className":"form-control","value":"","name":"field-' . $column_id3 . '"},{"type":"customer_custom_field","subtype":"text","required":"1","info_text":"' . $column2 . '","validation":"gst_number","column_id":"' . $column_id2 . '","id":"customer_gst","onchange":"changeGSTtext();","display":"1","label":"' . $column2 . '","className":"form-control","value":"","name":"gst_number"},{"type":"base_amount","subtype":"text","required":"1","validation":"money","display":"0","className":"form-control","value":"' . $base_amount . '","name":"base_amount"},{"type":"purpose","subtype":"text","required":"1","validation":"text","display":"0","className":"form-control","value":"' . $purpose . '","name":"purpose"},{"type":"in_state_tax","subtype":"text","required":"1","validation":"percent","display":"0","label":"CGST@9%","className":"form-control","value":"9","name":"s_tax1"},{"type":"in_state_tax","subtype":"text","required":"1","validation":"percent","display":"0","label":"SGST@9%","className":"form-control","value":"9","name":"s_tax2"},{"type":"out_state_tax","subtype":"text","required":"1","validation":"percent","display":"0","label":"IGST@18%","className":"form-control","value":"18","name":"o_tax1"},{"type":"grand_total","subtype":"text","required":"1","readonly":"1","validation":"money","display":"1","label":"Amount","className":"form-control","value":"' . $grand_total . '","name":"grand_total"}]';
        $params = array();
        $sql = "INSERT INTO `form_builder_request` (`merchant_id`, `name`, `json`, `random_id`, `is_active`, `created_by`, `created_date`, `last_update_by`)
 VALUES (:merchant_id, :name,:json, '1', '1', 'admin', now(), 'admin');";
        $params[':merchant_id'] = $merchant_id;
        $params[':name'] = $form_builder_name;
        $params[':json'] = $json;
        $this->common->queryexecute($sql, $params);
        $this->common->genericupdate('merchant_setting', 'has_formbuilder', 1, 'merchant_id', $merchant_id, $user_id);
        $this->session->set('has_formbuilder', 1);
        echo 'Success';

        die();
    }

    function updateformbuilder()
    {
        $rowall = $this->common->getListValue('form_builder_request', 'name', 'APOB Charges', " and last_update_by='Adminv4' and id>2");
        if (empty($rowall)) {
            echo 'Empty row';
            die();
        }
        $array["type"] = "form_field";
        $array["subtype"] = "checkbox";
        $array["required"] = "0";
        $array["display"] = "1";
        $array["label"] = "Are you a food Seller ?";
        $array["className"] = "form-control";
        $array["value"] = "Yes";
        $array["name"] = "form-field-1";
        $a[] = $array;
        foreach ($rowall as $srow) {
            $new_array = array();
            $rowarray = array();
            $rowarray = json_decode($srow['json'], 1);
            $rowarray[12]['info_text'] = "Same as provided in Seller central";
            $rowarray[12]['placeholder'] = "Important - Enter the same GSTIN provided in Amazon";
            $rowarray[12]['className'] = "form-control custom-placeholder";
            $new_array = array_merge(array_slice($rowarray, 0, 15), $a, array_slice($rowarray, 15));
            $this->common->genericupdate('form_builder_request', 'json', json_encode($new_array), 'id', $srow['id'], 'Adminv5');
        }

        echo 'Success';
        die();
    }
}
