<?php

/**
 * Template controller class to handle Template for merchant
 */
class Template extends Controller
{

    function __construct($customer = null)
    {
        parent::__construct();

        //TODO : Check if using static function is causing any problems!
        if ($customer == null) {
            $this->validateSession('merchant');
        }
        $this->view->js = array('template');
        $this->view->selectedMenu = array(14);
    }

    /*     * templatepreview
     * Display list of merchant templates
     */

    function viewlist()
    {
        try {
            $this->hasRole(1, 2);

            $list = $this->model->getTemplateList($this->merchant_id);
            $list = $this->generic->getEncryptedList($list, 'encrypted_id', 'template_id');
            $this->smarty->assign("templatelist", $list);
            $this->view->title = 'Invoice formats';
            $this->smarty->assign('title', 'Invoice formats');

            $old_links = $this->session->get('breadcrumbs');
            if (!empty($old_links) && $old_links['menu'] == 'collect_payments') {
                //Breadcumbs array start
                $breadcumbs_array = array(
                    array('title' => 'Collect Payments', 'url' => ''),
                    array('title' => $old_links['title'], 'url' => '/merchant/invoice/create/' . $old_links['url']),
                    array('title' => $this->view->title, 'url' => '')
                );
                $this->smarty->assign("links", $breadcumbs_array);
                //Breadcumbs array end
            } else {
                //Breadcumbs array start
                $breadcumbs_array = array(
                    array('title' => 'Settings', 'url' => '/merchant/profile/settings'),
                    array('title' => 'Billing & Invoicing', 'url' => ''),
                    array('title' => 'Invoice formats', 'url' => '')
                );
                $this->smarty->assign("links", $breadcumbs_array);
                //Breadcumbs array end
            }

            $this->view->header_file = ['list'];
            $this->view->list_name = 'invoice_format_list';
            $this->view->render('header/app');
            $this->view->datatablejs = 'table-small-statesave'; //old value - table-small
            $this->smarty->display(VIEW . 'merchant/template/list.tpl');
            $this->view->render('footer/list');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E034]Error while listing template Error: for merchant [' . $merchant_id . '] ' . $e->getMessage());
        }
    }

    /**
     * Display system template list
     */
    function newtemplate()
    {
        try {
            $this->hasRole(2, 2);
            $merchant_id = $this->session->get('merchant_id');
            $systemtemplatelist = $this->model->getSystemTemplateList('template');
            $this->smarty->assign("systemtemplatelist", $systemtemplatelist);
            $this->view->title = 'Create invoice format';
            $this->smarty->assign('title', $this->view->title);

            $old_links = $this->session->get('breadcrumbs');
            if (!empty($old_links) && $old_links['menu'] == 'collect_payments') {
                //Breadcumbs array start
                $breadcumbs_array = array(
                    array('title' => 'Collect Payments', 'url' => ''),
                    array('title' => $old_links['title'], 'url' => $old_links['url']),
                    array('title' => $this->view->title, 'url' => '')
                );
                $this->smarty->assign("links", $breadcumbs_array);
                //Breadcumbs array end
            } else {
                //Breadcumbs array start
                $breadcumbs_array = array(
                    array('title' => 'Settings', 'url' => '/merchant/profile/settings'),
                    array('title' => 'Billing & Invoicing', 'url' => ''),
                    array('title' => 'Create invoice format', 'url' => '')
                );
                $this->smarty->assign("links", $breadcumbs_array);
                //Breadcumbs array end
            }
            $this->view->header_file = ['choosetemplate'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/template/clone.tpl');
            $this->view->render('footer/mTemplate');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E034]Error while listing template Error: for merchant [' . $merchant_id . '] ' . $e->getMessage());
        }
    }

    function setTaxDetails()
    {
        $tax_list = $this->common->getListValue('merchant_tax', 'merchant_id', $this->merchant_id, 1);

        if (!empty($tax_list)) {
            foreach ($tax_list as $ar) {
                $tax_array[$ar['tax_id']] = array('tax_name' => $ar['tax_name'], 'percentage' => $ar['percentage']);
            }
            $this->smarty->assign("tax_array", json_encode($tax_array));
            $this->smarty->assign("tax_list", $tax_array);
            return $tax_array;
        }
    }

    function create($link)
    {
        $this->hasRole(2, 2);
        if (!isset($link)) {
            $this->setInvalidLinkError();
        }
        $template_id = $this->encrypt->decode($link);
        if (strlen($template_id) != 10) {
            $template_id = $this->encryptBackup($link);
            $link = $this->encrypt->encode($template_id);
        }

        $invoicevalues = $this->getCloneTemplateDetails($template_id);
        $template_type = $invoicevalues['template_type'];
        if (empty($invoicevalues)) {
            $this->setInvalidLinkError();
        }
        $main_header = $this->common->getListValue('invoice_template_mandatory_fields', 'type', 'M');
        $this->smarty->assign("header", $invoicevalues['header']);
        $this->smarty->assign("bds_column", $invoicevalues['BDS']);
        $this->smarty->assign("main_header", $main_header);
        $this->smarty->assign("template_link", $link);
        $this->smarty->assign("invoicevalues", $invoicevalues);
        $this->smarty->assign("is_franchise", $this->session->get('has_franchise'));
        $this->smarty->assign("is_vendor", $this->session->get('vendor_enable'));
        $this->smarty->assign("has_webhook", $this->session->get('has_webhook'));

        $particular_columns = $this->common->getListValue('invoice_template_mandatory_fields', 'type', 'P');
        $this->smarty->assign("particular_col", $invoicevalues['particular_col']);
        $this->smarty->assign("particular_columns", $particular_columns);

        $invoice_numbers = $this->common->getListValue('merchant_auto_invoice_number', 'merchant_id', $this->merchant_id, 1, " and type=1");
        $this->smarty->assign("invoice_numbers", json_encode($invoice_numbers));
        $covering_notes = $this->common->getListValue('covering_note', 'merchant_id', $this->merchant_id, 1);
        $this->smarty->assign("covering_notes", $covering_notes);

        $billing_profile = $this->common->getListValue('merchant_billing_profile', 'merchant_id', $this->merchant_id, 1);
        $this->smarty->assign("billing_profile", $billing_profile);

        $supplier = $this->common->getListValue('supplier', 'merchant_id', $this->merchant_id, 1);
        $function_result = $this->common->getColumn_functions();
        $customer_column = $this->common->getListValue('customer_mandatory_column', 'is_active', 1);
        require_once MODEL . '/merchant/CustomerModel.php';
        $customerModel = new CustomerModel();
        $custom_column = $customerModel->getCustomerBreakup($this->merchant_id);
        $this->smarty->assign("custom_column", $custom_column);
        $this->smarty->assign("customer_column", $customer_column);
        $this->smarty->assign("functions", $function_result['functions']);
        $this->smarty->assign("functionsJSON", $function_result['json']);
        $this->smarty->assign("functionsMapping", $function_result['mapping']);
        $this->smarty->assign("supplier", $supplier);

        $logo = $this->common->getRowValue('logo', 'merchant_landing', 'merchant_id', $this->merchant_id);
        if ($logo != '') {
            $logo = $this->view->server_name . '/uploads/images/landing/' . $logo;
        } else {
            $logo = $this->view->server_name . '/assets/frontend/onepage2/img/logo_scroll.png';
        }

        $this->smarty->assign('logo', $logo);
        $this->view->js = array('template', 'coveringnote', 'column_function');

        $this->setTaxDetails();
        $this->view->function_script = 'setProfileDetails();';
        $this->session->set('valid_ajax', 'template');
        $this->smarty->assign("template_type", $template_type);
        $this->view->title = 'Create Template';
        $this->smarty->assign('title', $this->view->title);

        $old_links = $this->session->get('breadcrumbs');
        if (!empty($old_links) && $old_links['menu'] == 'collect_payments') {
            $breadcumbs_array = array(
                array('title' => 'Collect Payments', 'url' => ''),
                array('title' => $old_links['title'], 'url' => $old_links['url']),
                array('title' => 'Create invoice format', 'url' => '/merchant/template/newtemplate'),
                array('title' => $this->view->title, 'url' => '')
            );
        } else {
            $breadcumbs_array = array(
                array('title' => 'Settings', 'url' => '/merchant/profile/settings'),
                array('title' => 'Billing & Invoicing', 'url' => ''),
                array('title' => 'Create invoice format', 'url' => '/merchant/template/newtemplate'),
                array('title' => $this->view->title, 'url' => '')
            );
        }
        $this->smarty->assign("links", $breadcumbs_array);

        $this->view->header_file = ['create_invoice', 'template'];
        $this->view->render('header/app');
        $tpl_file = 'particular';
        if ($template_type == 'travel_ticket_booking') {
            $tpl_file = 'travel_ticket_booking';
        }
        $this->smarty->display(VIEW . 'merchant/template/create_header.tpl');
        if ($template_type != 'scan') {
            $this->smarty->display(VIEW . 'merchant/template/create_' . $tpl_file . '.tpl');
        }
        $this->smarty->display(VIEW . 'merchant/template/create_footer.tpl');
        $this->smarty->display(VIEW . 'merchant/template/supplier.tpl');
        $this->view->render('footer/create_event');
    }

    function getCloneTemplateDetails($template_id)
    {
        try {
            $template = array();
            $headerinc = 0;
            $bdsinc = 0;
            $pinc = 0;
            $template['template_type'] = $this->common->getRowValue('template_type', 'system_template', 'system_template_id', $template_id);
            if ($template['template_type'] == false) {
                return false;
            }
            $rows = $this->model->getSystemTemplateDetails($template_id);
            foreach ($rows as $row) {
                if ($row['column_type'] == 'H' || $row['column_type'] == 'BDS') {
                    if ($row['column_type'] == 'H') {
                        $type = 'header';
                        $rowint = $headerinc;
                    } else {
                        $type = 'BDS';
                        $rowint = $bdsinc;
                    }
                    $template[$type][$rowint]['column_id'] = $row['column_id'];
                    $template[$type][$rowint]['column_name'] = $row['column_name'];
                    $template[$type][$rowint]['mandatory'] = ($row['is_mandatory'] == 0) ? 2 : 1;
                    $template[$type][$rowint]['datatype'] = $row['column_datatype'];
                    $template[$type][$rowint]['table_name'] = $row['save_table_name'];
                    $template[$type][$rowint]['function_id'] = ($row['function_id'] > 0) ? $row['function_id'] : -1;
                    $template[$type][$rowint]['delete_allow'] = ($row['is_delete_allow'] == 0) ? 2 : 1;
                    $template[$type][$rowint]['col_position'] = $row['column_position'];
                    $template[$type][$rowint]['position'] = $row['position'];

                    if ($row['column_type'] == 'H') {
                        $headerinc++;
                    } else {
                        $bdsinc++;
                    }
                }
                if ($row['column_type'] == 'PC') {
                    $template['particular_col'][$row['system_col_name']] = $row['column_name'];
                }
                if ($row['column_type'] == 'PT') {
                    $template['particular_total'] = $row['column_name'];
                }
            }
            return $template;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, 'Error while getting invoice breckup Error: for template id[' . $template_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    /**
     */
    function update($link)
    {
        $this->hasRole(2, 2);
        try {
            if (!isset($link)) {
                $this->setInvalidLinkError();
            }
            header('Location: /merchant/invoiceformat/update/' . $link, 301);
            die();
            $merchant_id = $this->session->get('merchant_id');
            $template_id = $this->model->getDecreptValue($link);
            $this->smarty->assign("template_id", $link);
            $info = $this->asignSmarty($template_id);
            if ($info['template_type'] == 'travel') {
                header('Location: /merchant/invoiceformat/update/' . $link, 301);
                die();
            }
            $invoice_numbers = $this->common->getListValue('merchant_auto_invoice_number', 'merchant_id', $merchant_id, 1, " and type=1");
            $this->smarty->assign("invoice_numbers", json_encode($invoice_numbers));
            $function_result = $this->common->getColumn_functions();

            $covering_notes = $this->common->getListValue('covering_note', 'merchant_id', $merchant_id, 1);
            $this->smarty->assign("covering_notes", $covering_notes);

            $particular_columns = $this->common->getListValue('invoice_template_mandatory_fields', 'type', 'P');
            $this->smarty->assign("particular_columns", $particular_columns);

            $billing_profile = $this->common->getListValue('merchant_billing_profile', 'merchant_id', $this->merchant_id, 1);
            $this->smarty->assign("billing_profile", $billing_profile);
            $supplier = $this->common->getListValue('supplier', 'merchant_id', $this->merchant_id, 1);
            $this->smarty->assign("supplier", $supplier);
            $this->view->js = array('template', 'coveringnote', 'column_function');
            $this->smarty->assign("functions", $function_result['functions']);
            $this->smarty->assign("functionsJSON", $function_result['json']);
            $this->smarty->assign("functionsMapping", $function_result['mapping']);
            $this->smarty->assign("is_franchise", $this->session->get('has_franchise'));
            $this->smarty->assign("is_vendor", $this->session->get('vendor_enable'));
            $template_type = $info['template_type'];
            $this->smarty->assign("template_type", $template_type);
            $this->view->title = 'Update format';
            $this->smarty->assign('title', 'Update format');

            $old_links = $this->session->get('breadcrumbs');
            if (!empty($old_links) && $old_links['menu'] == 'collect_payments') {
                $breadcumbs_array = array(
                    array('title' => 'Collect Payments', 'url' => ''),
                    array('title' => $old_links['title'], 'url' => $old_links['url']),
                    array('title' => 'Invoice formats', 'url' => '/merchant/template/viewlist'),
                    array('title' => $this->view->title, 'url' => '/merchant/template/update/' . $link),
                    array('title' => $info['template_name'], 'url' => '')
                );
            } else {
                $breadcumbs_array = array(
                    array('title' => 'Settings', 'url' => '/merchant/profile/settings'),
                    array('title' => 'Billing & Invoicing', 'url' => ''),
                    array('title' => 'Invoice formats', 'url' => '/merchant/template/viewlist'),
                    array('title' => $this->view->title, 'url' => '/merchant/template/update/' . $link),
                    array('title' => $info['template_name'], 'url' => '')
                );
            }
            $this->smarty->assign("links", $breadcumbs_array);

            $tpl_file = 'particular';
            if ($template_type == 'travel_ticket_booking') {
                $tpl_file = 'travel_ticket_booking';
            }
            $this->session->set('valid_ajax', 'template');
            $this->view->function_script = 'setProfileDetails();';
            $this->view->header_file = ['create_invoice', 'template'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/template/update_header.tpl');
            if ($template_type != 'scan') {
                $this->smarty->display(VIEW . 'merchant/template/update_' . $tpl_file . '.tpl');
            }
            $this->smarty->display(VIEW . 'merchant/template/update_footer.tpl');
            $this->smarty->display(VIEW . 'merchant/template/supplier.tpl');

            $this->view->render('footer/create_event');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E028]Error template update initiate Error: for merchant [' . $merchant_id . '] and for template [' . $template_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function setPlugins()
    {
        $this->generic->setZeroValue(array('is_debit', 'is_supplier', 'is_coupon', 'is_cc', 'is_roundoff', 'has_acknowledgement', 'franchise_notify_email', 'franchise_notify_sms', 'franchise_name_invoice', 'is_franchise', 'is_vendor', 'is_prepaid', 'has_autocollect', 'partial_min_amount', 'is_partial', 'default_covering', 'is_covering', 'is_custom_notification', 'is_custom_reminder'));
        $this->generic->setEmptyArray(array('debit', 'debitdefaultValue', 'supplier', 'cc', 'reminder', 'reminder_subject', 'reminder_sms'));
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
        if (empty($plugin)) {
            return null;
        }
        return json_encode($plugin);
    }

    /**
     * Saved new template for merchant
     */
    function saved()
    {
        try {
            $merchant_id = $this->session->get('merchant_id');
            if (empty($_POST)) {
                header('Location:/merchant/template/viewlist');
            }
            $plugin = $this->setPlugins();
            $this->generic->setEmptyArray(array('main_header_id', 'main_header_default', 'customer_column_id', 'customer_column_name', 'custom_column_id', 'headercolumn', 'position', 'headerdatatype', 'headertablesave', 'headermandatory', 'headercolumnposition', 'function_id', 'function_param', 'function_val', 'headerisdelete', 'taxx', 'defaultValue', 'debit', 'debitdefaultValue', 'particularname', 'column_type'));

            $_POST['particular_total'] = (isset($_POST['particular_total'])) ? $_POST['particular_total'] : 'Particular total';
            $_POST['tax_total'] = (isset($_POST['tax_total'])) ? $_POST['tax_total'] : 'Tax total';

            foreach ($_POST['main_header_name'] as $col) {
                $sort[] = 'M' . $col;
            }
            foreach ($_POST['customer_column_name'] as $col) {
                $sort[] = 'C' . $col;
            }
            foreach ($_POST['headercolumn'] as $col) {
                $sort[] = 'H' . $col;
            }

            foreach ($_POST['particular_col'] as $item) {
                $particular_columns[$item] = $_POST['pc_' . $item];
            }

            $_POST['PD'] = json_encode($_POST['particularname']);
            $_POST['TD'] = json_encode($_POST['tax_id']);

            $profile_id = ($_POST['billing_profile_id'] > 0) ? $_POST['billing_profile_id'] : 0;

            require CONTROLLER . 'merchant/Templatevalidator.php';
            $validator = new Templatevalidator($this->model);
            $validator->validateTemplateSave($this->user_id);
            $hasErrors = $validator->fetchErrors();
            if ($hasErrors == false) {
                if (in_array('gst', $_POST['particular_col'])) {
                    $this->setMerchantGSTTAX();
                }
                $result = $this->model->saveTemplate($this->merchant_id, $this->user_id, $_POST['main_header_id'], $_POST['main_header_default'], $_POST['customer_column_id'], $_POST['custom_column_id'], $_POST['headercolumn'], $_POST['position'], $_POST['column_type'], $sort, $_POST['headercolumnposition'], $_POST['function_id'], $_POST['function_param'], $_POST['function_val'], $_POST['headerisdelete'], $_POST['headerdatatype'], $_POST['headertablesave'], $_POST['headermandatory'], $_POST['tnc'], $_POST['defaultValue'], $_POST['template_name'], $_POST['template_type'], $_POST['particular_total'], $_POST['tax_total'], $_FILES["uploaded_file"], $particular_columns, $_POST['PD'], $_POST['TD'], $plugin, $profile_id);
                if ($result['@message'] == 'success') {
                    $link = $this->encrypt->encode($result['@template_id']);
                    header('Location: /merchant/template/success/' . $link);
                } else {
                    SwipezLogger::error(__CLASS__, '[E031]Error while create new template. error: ' . $result);
                    $this->setGenericError();
                }
            } else {
                $this->smarty->assign("haserrors", $hasErrors);
                $this->smarty->assign("post", $_POST);
                $this->create($_POST['template_id']);
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E032]Error while creating new template Error:for merchant [' . $merchant_id . '] and for template [' . $result['@template_id'] . '] ' . $e->getMessage());
        }
    }

    function saveupdate()
    {
        try {

            $merchant_id = $this->session->get('merchant_id');
            if (empty($_POST)) {
                header('Location:/merchant/template/viewlist');
            }
            require CONTROLLER . 'merchant/Templatevalidator.php';
            $plugin = $this->setPlugins();

            foreach ($_POST['main_header_name'] as $col) {
                $sort[] = 'M' . $col;
            }
            foreach ($_POST['customer_column_name'] as $col) {
                $sort[] = 'C' . $col;
            }
            foreach ($_POST['headercolumn'] as $col) {
                $sort[] = 'H' . $col;
            }


            $int = 0;
            foreach ($_POST['main_header_name'] as $mh) {
                if ($_POST['headerid'][$int] == '-1') {
                } else {
                    $existheader[] = $mh;
                    $headerid[] = $_POST['headerid'][$int];
                }
                $int++;
            }
            foreach ($_POST['headercolumn'] as $mh) {
                if ($_POST['headerid'][$int] == '-1') {
                    $headercolumn[] = $mh;
                } else {
                    $existheader[] = $mh;
                    $headerid[] = $_POST['headerid'][$int];
                }
                $int++;
            }
            if (count($_POST['headerid']) > count($headerid)) {
                foreach ($_POST['headerid'] as $key => $val) {
                    $max = count($headerid) - 1;
                    if ($key > $max) {
                        $headerid[] = $val;
                    }
                }
            }
            if (!empty($_POST['existheader'])) {
                $existheader = array_merge($existheader, $_POST['existheader']);
            }
            $this->generic->setEmptyArray(array(
                'main_header_id', 'main_header_default', 'exist_customer_column_id', 'customer_column_id',
                'customer_column_name', 'custom_column_id', 'supplier', 'headercolumn', 'position', 'headerdatatype', 'headertablesave',
                'headermandatory', 'headercolumnposition', 'function_id', 'function_param', 'function_val', 'headerisdelete', 'taxx', 'existtax',
                'defaultValue', 'existdefaultValue', 'debit', 'debitdefaultValue', 'cc', 'particularname', 'particularvalue',
                'existparticular', 'column_type', 'particularid', 'exist_function_id', 'exist_function_param', 'exist_function_val', 'exist_cc',
                'exist_cc_id', 'existheaderdatatype'
            ));

            foreach ($_POST['particular_col'] as $key => $item) {
                $particular_columns[$item] = $_POST['pc_' . $item];
            }

            $_POST['PD'] = json_encode($_POST['particularname']);
            $_POST['TD'] = json_encode($_POST['tax_id']);
            $headercolumn = (empty($headercolumn)) ? array() : $headercolumn;
            $existheader = (empty($existheader)) ? array() : $existheader;
            $headerid = (empty($headerid)) ? array() : $headerid;
            $_POST['particular_total'] = (isset($_POST['particular_total'])) ? $_POST['particular_total'] : 'Particular total';
            $_POST['tax_total'] = (isset($_POST['tax_total'])) ? $_POST['tax_total'] : 'Tax total';
            $profile_id = ($_POST['billing_profile_id'] > 0) ? $_POST['billing_profile_id'] : 0;

            $validator = new Templatevalidator($this->model);
            $template_type = $_POST['template_type'];
            $validator->validateTemplateUpdate();
            $hasErrors = $validator->fetchErrors();
            $template_id = $this->encrypt->decode($_POST['template_id']);
            if ($hasErrors == false) {
                if (in_array('gst', $_POST['particular_col'])) {
                    $this->setMerchantGSTTAX();
                }
                $result = $this->model->updateTemplate($this->user_id, $_POST['main_header_id'], $_POST['main_header_default'], $_POST['exist_header_default'], $template_id, $_POST['exist_customer_column_id'], $_POST['customer_column_id'], $_POST['custom_column_id'], $existheader, $headerid, $_POST['existheaderdatatype'], $_POST['exist_function_id'], $_POST['function_id'], $_POST['exist_function_param'], $_POST['exist_function_val'], $_POST['function_param'], $_POST['function_val'], $headercolumn, $_POST['headerdatatype'], $_POST['column_type'], $_POST['position'], $sort, $_POST['tnc'], $_POST['template_name'], $_POST['particular_total'], $_POST['tax_total'], $_FILES["uploaded_file"], $particular_columns, $_POST['PD'], $_POST['TD'], $plugin, $profile_id);
                if ($result['message'] == 'success') {
                    header('Location: /merchant/template/success/' . $_POST['template_id']);
                } else {
                    SwipezLogger::error(__CLASS__, '[E029]Error while updating template Error for merchant [' . $merchant_id . '] and for template id [' . $template_id . ']' . $result);
                    $this->setGenericError();
                }
            } else {
                $this->smarty->assign("haserrors", $hasErrors);
                $this->update($_POST['template_id']);
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E030]Error while update template Error: for merchant [' . $merchant_id . '] and for template id [' . $template_id . ']' . $e->getMessage());
        }
    }

    public function templatepreview($template_id)
    {
        if (strlen($template_id) == 10) {
            $merchant_id = $this->common->getRowValue('merchant_id', 'invoice_template', 'template_id', $template_id);
            if ($merchant_id == $this->merchant_id) {
                $html = $this->success($this->encrypt->encode($template_id), 1);
                $data['preview'] = $html;
                $data['link'] = '/merchant/template/update/' . $this->encrypt->encode($template_id);
                echo json_encode($data);
            }
        }
    }

    function success($link, $ajax = 0)
    {
        $template_id = $this->encrypt->decode($link);
        if (strlen($template_id) == 10) {
            $info = $this->asignSmarty($template_id);
            
            $this->smarty->assign("link", $link);
            $this->smarty->assign("ajax", $ajax);
            $this->smarty->assign("current_date", date("d M Y"));
            $this->view->title = 'Template Created / Modified';
            if ($ajax == 0) {
                $this->view->header_file = ['list'];
                $this->view->render('header/app');
            }
            $template_type = $info['template_type'];
            $this->smarty->assign("template_type", $template_type);
            if ($template_type != 'isp' && $template_type != 'travel') {
                $template_type = 'particular';
            }
            if ($ajax == 1) {
                $html = $this->smarty->fetch(VIEW . 'merchant/template/success_' . $template_type . '.tpl');
                if ($template_type != 'travel') {
                    $html .= $this->smarty->fetch(VIEW . 'merchant/template/success_footer.tpl');
                }
                return $html;
            } else {
                $this->smarty->display(VIEW . 'merchant/template/success_' . $template_type . '.tpl');
                if ($template_type != 'travel') {
                    $this->smarty->display(VIEW . 'merchant/template/success_footer.tpl');
                }
            }
            $this->view->render('footer/invoice');
        }
    }

    function asignSmarty($template_id)
    {
        if (strlen($template_id) == 10) {
            $header = array();
            $particular = array();
            $hederint = 0;
            $bdsint = 0;
            $info = $this->common->getSingleValue('invoice_template', 'template_id', $template_id, 0, " and merchant_id='" . $this->merchant_id . "'");
            if (empty($info)) {
                SwipezLogger::error(__CLASS__, '[E215]Error while fetching exsting template for merchant [' . $this->merchant_id . '] and for template id [' . $template_id . ']');
                $this->setGenericError();
            }
            //remove non-printable characters from tnc
            $info['tnc'] = preg_replace('/[[:^print:]]/', '', $info['tnc']);
            
            $plugin = json_decode($info['plugin'], 1);
            require_once CONTROLLER . 'InvoiceWrapper.php';
            $invoice = new InvoiceWrapper($this->common);
            $template = $invoice->getTemplateBreakup($template_id);

            $main_header = $this->common->getListValue('invoice_template_mandatory_fields', 'type', 'M');
            $int = 0;
            foreach ($main_header as $mh) {
                $main_header[$int]['is_default'] = 0;
                foreach ($template['main_header'] as $ex) {
                    if ($mh['column_name'] == $ex['column_name']) {
                        $main_header[$int]['is_default'] = 1;
                        $mainsort[$ex['sort_order']] = $int;
                        $main_header[$int]['column_id'] = $ex['column_id'];
                        $main_header[$int]['default_column_value'] = $ex['default_column_value'];
                    }
                }
                $int++;
            }
            ksort($mainsort);
            foreach ($mainsort as $ms) {
                $mhheader[] = $main_header[$ms];
                $mhheadername[] = $main_header[$ms]['column_name'];
            }

            foreach ($main_header as $mh) {
                if ($mh['is_mandatory'] == 1) {
                    $mh['is_default'] = 1;
                }
                if (in_array($mh['column_name'], $mhheadername)) {
                } else {
                    $mhheader[] = $mh;
                }
            }

            $customer_column = $this->common->getListValue('customer_mandatory_column', 'is_active', 1);
            $int = 0;
            foreach ($customer_column as $a) {
                if (in_array($a['id'], $template['exist_customer_column'])) {
                    $customer_column[$int]['is_checked'] = 1;
                } else {
                    $customer_column[$int]['is_checked'] = 0;
                }
                $int++;
            }
            require_once MODEL . '/merchant/CustomerModel.php';
            $customerModel = new CustomerModel();
            $custom_column = $customerModel->getCustomerBreakup($this->session->get('merchant_id'));
            $int = 0;
            foreach ($custom_column as $a) {
                if (in_array($a['column_id'], $template['exist_custom_column'])) {
                    $custom_column[$int]['is_checked'] = 1;
                } else {
                    $custom_column[$int]['is_checked'] = 0;
                }
                $int++;
            }

            $this->smarty->assign("custom_column", $custom_column);
            $this->smarty->assign("customer_column", $customer_column);

            if ($plugin['has_supplier'] == 1) {
                $supplier = $this->common->getListValue('supplier', 'merchant_id', $this->merchant_id, 1);
                $supplierlist2 = $this->common->getInvoiceSupplierlist($plugin['supplier']);
                $this->smarty->assign("supplierid", $plugin['supplier']);
                $this->smarty->assign("supplier", $supplier);
                $this->smarty->assign("supplierlist", $supplierlist2);
            }

            if ($plugin['has_signature'] == 1) {
                $signature = $this->common->getRowValue('`value`', 'merchant_config_data', 'merchant_id', $this->merchant_id, 0, " and `key`='DIGITAL_SIGNATURE'");
                $this->smarty->assign("signature", json_decode($signature, 1));
            }

            $tax_array = $this->setTaxDetails();
            //dd($template['header']);
            $default_particular = json_decode($info['default_particular'], 1);
            $particular_column = json_decode($info['particular_column'], 1);
            $default_tax = json_decode($info['default_tax'], 1);

            if (empty($default_particular)) {
                $default_particular[] = 'Particular';
            }
            $sub_total = count($default_particular) * 1000;
            $total_amount = $sub_total;

            foreach ($default_tax as $tax) {
                $tamt = round($tax_array[$tax]['percentage'] * $sub_total / 100, 2);
                $total_amount = $total_amount + $tamt;
                $tax_default[$tax] = $tamt;
            }

            $num_words = Numbers_Words::toCurrency($total_amount, "en_IN");
            $num_words1 = str_replace("Indian Rupees", "Rupees", $num_words);
            $money_words = ucwords($num_words1);
            $money_words = str_replace('Zero Paises', '', $money_words);


            $merchant_detail = $this->common->getSingleValue('merchant_detail', 'merchant_id', $this->merchant_id);
            if ($info['profile_id'] > 0) {
                $profile_detail = $this->common->getSingleValue('merchant_billing_profile', 'id', $info['profile_id']);
                $merchant_detail['gst_number'] = $profile_detail['gstin'];
                $merchant_detail['address'] = $profile_detail['address'];
                $merchant_detail['business_email'] = $profile_detail['business_email'];
                $merchant_detail['business_contact'] = $profile_detail['business_contact'];
            }
            $this->smarty->assign("customer_column_list", $template['customer_column']);
            $this->smarty->assign("sub_total", $sub_total);
            $this->smarty->assign("total_amount", $total_amount);
            $this->smarty->assign("money_words", $money_words);
            $this->smarty->assign("tax_default", $tax_default);
            $this->smarty->assign("tax", $default_tax);
            $this->smarty->assign("particular", $particular);
            $this->smarty->assign("header", $template['header']);
            $this->smarty->assign("company_name", $merchant_detail['company_name']);
            $this->smarty->assign("main_header", $mhheader);
            $this->smarty->assign("tncexist", $template['tnc']);
            $this->smarty->assign("bds_column", $template['BDS']);
            $this->smarty->assign("particular_col", $particular_column);
            $this->smarty->assign("default_particular", $default_particular);
            $this->smarty->assign("merchant_address", $merchant_detail['address']);
            $this->smarty->assign("business_email", $merchant_detail['business_email']);
            $this->smarty->assign("business_contact", $merchant_detail['business_contact']);
            $this->smarty->assign("image_path", $info['image_path']);
            $this->smarty->assign("logo", $merchant_detail['logo']);
            $this->smarty->assign("sec_col", array("A", "B", "C", "D", "E", "F", "G", "H", "I"));
            $this->smarty->assign("properties", json_decode($info['properties'], 1));
            $this->smarty->assign("merchant_detail", $merchant_detail);
            $this->smarty->assign("template_type", $info['template_type']);
            $this->smarty->assign("template_name", $info['template_name']);
            $this->smarty->assign("plugin", $plugin);

            $this->smarty->assign("info", $info);

            return $info;
        }
    }

    /**
     * Delete template for merchant
     */
    function delete($link)
    {
        try {
            $this->hasRole(3, 2);
            $merchant_id = $this->session->get('merchant_id');
            $template_id = $this->encrypt->decode($link);
            if (strlen($template_id) != 10) {
                $this->setInvalidLinkError();
            }
            $this->model->deleteTemplate($template_id, $this->user_id);
            $this->session->set('successMessage', 'Template deleted successfully.');
            header('Location:/merchant/template/viewlist');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E033]Error while deleting template Error: for merchant [' . $merchant_id . '] and for template [' . $template_id . ']' . $e->getMessage());
        }
    }

    function saveInvoceJson($version, $link, $type = 1, $merchant_id = null)
    {
        try {
            $template_id = $this->encrypt->decode($link);
            $rows = $this->common->getListValue('invoice_column_metadata', 'template_id', $template_id, 1, ' order by sort_order,column_id');
            require_once MODEL . 'merchant/TemplateModel.php';
            $templateModel = new TemplateModel();
            $info = $this->common->getSingleValue('invoice_template', 'template_id', $template_id);
            require_once CONTROLLER . 'api/Api.php';
            $api = new Api('Template');
            if ($merchant_id != null) {
                $api->merchant_id = $merchant_id;
            } else {
                $api->merchant_id = $this->merchant_id;
            }
            $jsonArray = $api->getTemplateJson($version, $info, $rows, 'template_id', $link);
            $jsonArray['template_id'] = $link;
            if ($type == 1) {
                header("Content-type: text/plain");
                header("Content-Disposition: attachment; filename=" . $info['template_name'] . '_upload_invoice_' . $version . ".json");
                print json_encode($jsonArray);
            } else {
                return $jsonArray;
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E0035]Error while getting json for template id [' . $template_id . '] ' . $e->getMessage());
        }
    }

    function updateInvoceJson($version, $link)
    {
        try {
            $template_id = $this->encrypt->decode($link);
            $info = $this->common->getSingleValue('invoice_template', 'template_id', $template_id);
            $rows = $this->common->getListValue('invoice_column_metadata', 'template_id', $template_id, 1, ' order by sort_order,column_id');
            require_once CONTROLLER . 'api/Api.php';
            $api = new Api(TRUE);
            $api->merchant_id = $this->session->get('merchant_id');
            $jsonArray = $api->getTemplateJson($version, $info, $rows, 'invoice_id');
            header("Content-type: text/plain");
            header("Content-Disposition: attachment; filename=" . $info['template_name'] . '_update_invoice_' . $version . ".json");
            print json_encode($jsonArray);
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E0035]Error while getting json for template id [' . $template_id . '] ' . $e->getMessage());
        }
    }

    function setMerchantGSTTAX()
    {
        try {
            $tax[] = array('tax_name' => 'CGST@9%', 'percentage' => 9, 'type' => '1');
            $tax[] = array('tax_name' => 'SGST@9%', 'percentage' => 9, 'type' => '2');
            $tax[] = array('tax_name' => 'IGST@18%', 'percentage' => 18, 'type' => '3');
            $tax[] = array('tax_name' => 'CGST@2.5%', 'percentage' => 2.5, 'type' => '1');
            $tax[] = array('tax_name' => 'SGST@2.5%', 'percentage' => 2.5, 'type' => '2');
            $tax[] = array('tax_name' => 'IGST@5%', 'percentage' => 5, 'type' => '3');
            $tax[] = array('tax_name' => 'CGST@6%', 'percentage' => 6, 'type' => '1');
            $tax[] = array('tax_name' => 'SGST@6%', 'percentage' => 6, 'type' => '2');
            $tax[] = array('tax_name' => 'IGST@12%', 'percentage' => 12, 'type' => '3');
            $tax[] = array('tax_name' => 'CGST@14%', 'percentage' => 14, 'type' => '1');
            $tax[] = array('tax_name' => 'SGST@14%', 'percentage' => 14, 'type' => '2');
            $tax[] = array('tax_name' => 'IGST@28%', 'percentage' => 28, 'type' => '3');
            require_once MODEL . 'merchant/TaxModel.php';
            $tax_model = new TaxModel();
            foreach ($tax as $row) {
                $exist = $this->model->GSTTaxExist($this->merchant_id, $row['percentage'], $row['type']);
                if ($exist == false) {
                    $tax_model->createTax($row['tax_name'], $row['percentage'], 0, '', $row['type'], $this->merchant_id, $this->user_id);
                }
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__ . __METHOD__, 'Merchant id ' . $this->merchant_id . 'Error ' . $e->getMessage());
        }
    }

    function settleInvoceJson($link)
    {
        try {
            require_once CONTROLLER . 'api/Api.php';
            $api = new Api(TRUE);

            $jsonArray = $api->getsettleJson();
            header("Content-type: text/plain");
            header("Content-Disposition: attachment; filename=settle_invoice.json");
            print json_encode($jsonArray);
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E0035]Error while getting json' . $e->getMessage());
        }
    }

    function clonetemplate($template_id, $merchant_id = null)
    {
        try {
            require_once MODEL . 'merchant/TemplateModel.php';
            $tempModel = new TemplateModel();
            if ($merchant_id == null) {
                $merchant_id = $this->merchant_id;
            }
            $merchant_det = $this->common->getSingleValue('merchant', 'merchant_id', $merchant_id);
            $template = $this->common->getSingleValue('invoice_template', 'template_id', $template_id);

            $exist = $this->common->getSingleValue('invoice_template', 'merchant_id', $merchant_id, 1, " and template_name='" . $template['template_name'] . "'");
            if (!empty($exist)) {
                $template_name = $template['template_name'] . '-clone';
            } else {
                $template_name = $template['template_name'];
            }
            if ($merchant_id != $this->merchant_id) {
                $tax = $template['default_tax'];
            } else {
                $tax_array = json_decode($template['default_tax'], 1);
                $mer_tax_array = array();
                foreach ($tax_array as $tax_id) {
                    $tax_det = $this->common->getSingleValue('merchant_tax', 'tax_id', $tax_id);
                    $tax_id = $this->common->GSTTaxExist($merchant_id, $tax_det['percentage'], $tax_det['tax_type']);
                    $mer_tax_array[] = $tax_id;
                }
                $tax = json_encode($mer_tax_array);
            }
            $new_template_id = $tempModel->saveTemplateData($merchant_id, $merchant_det['user_id'], $template_id, $template_name, $tax);
            $tempModel->saveTemplateMetaData($new_template_id, $template_id);
            $meta_rows = $tempModel->getCloneTemplateMetadata($template_id);
            foreach ($meta_rows as $row) {
                $column_id = $this->common->getRowValue('column_id', 'invoice_column_metadata', 'template_id', $new_template_id, 0, " and column_name='" . $row['column_name'] . "' and function_id=" . $row['function_id']);
                $param_value = $row['param_value'];
                if ($row['function_id'] == 9 && $row['param'] == 'system_generated') {
                    if ($merchant_id != $this->merchant_id) {
                        $seq_det = $this->common->getSingleValue('merchant_auto_invoice_number', 'auto_invoice_id', $row['param_value']);
                        $param_value = $this->common->getRowValue('auto_invoice_id', 'merchant_auto_invoice_number', 'merchant_id', $merchant_id, 1, " and prefix='" . $seq_det['prefix'] . "'");
                        if ($param_value == false) {
                            $param_value = $tempModel->saveInvoiceSeq($merchant_id, $seq_det['prefix']);
                        }
                    }
                }
                $tempModel->saveColumnFunctionMapping($column_id, $row['function_id'], $row['param'], $param_value, $template['merchant_id']);
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__METHOD__, 'Error: ' . $e->getMessage());
        }
    }
}
