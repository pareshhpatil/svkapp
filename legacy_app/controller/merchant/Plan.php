<?php

/**
 * Plan controller class to handle Merchants Plans
 */
class Plan extends Controller
{

    function __construct()
    {
        parent::__construct();
        $this->validateSession('merchant');
        $this->view->selectedMenu = array(14, 62);
        $this->view->js = array('plan');
    }

    /**
     * Display merchant suppliers list
     */
    function viewlist()
    {
        try {
            $this->hasRole(1, 19);
            $list = $this->model->getPlanList($this->merchant_id);
            $display_url = $this->common->getRowValue('display_url', 'merchant', 'merchant_id', $this->merchant_id);
            $int = 0;
            $source = 0;
            foreach ($list as $item) {
                if ($item['source'] != '') {
                    $set_source = strtolower($item['source']);
                    $set_source = trim($set_source);
                    $set_source = str_replace(' ', '-', $set_source);
                    $list[$int]['url'] = $this->view->server_name . '/m/' . $display_url . '/packages/' . $set_source;
                    $source = 1;
                }
                $list[$int]['encrypted_id'] = $this->encrypt->encode($item['plan_id']);
                $int++;
            }
            $merchant = $this->common->getSingleValue('merchant', 'merchant_id', $this->merchant_id);
            $booking_link = $this->app_url . '/m/' . $merchant['display_url'] . '/packages';
            $settings = $this->common->getSingleValue('merchant_setting', 'merchant_id', $this->merchant_id);
            $user_id = $this->session->get('userid');
            $sub_franchise_id = $this->session->get('sub_franchise_id');
            $templatelist = $this->common->getTemplateList($user_id, $sub_franchise_id);
            $this->smarty->assign("plan_link", $booking_link);
            $this->smarty->assign("templatelist", $templatelist);
            $this->smarty->assign("setting", $settings);
            $this->smarty->assign("is_source", $source);
            $this->smarty->assign("list", $list);
            $this->smarty->assign("title", "Plan list");
            $this->view->title = "Plan list";

            //Breadcumbs array start
            $breadcumbs_array = array(
                array('title' => 'Settings','url' => '/merchant/profile/settings'),
                array('title' => 'Data configuration','url' => ''),
                array('title'=> $this->view->title, 'url'=>'')
            );
            $this->smarty->assign("links", $breadcumbs_array);

            $this->view->datatablejs = 'table-no-export';
            $this->view->header_file = ['list'];
            $this->view->js = ['transaction'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/plan/list.tpl');
            $this->view->render('footer/list');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E999]Error while listing suppliers Error: for user id [' . $this->session->get('userid') . '] ' . $e->getMessage());
        }
    }

    /**
     * Create new supplier for merchant
     */
    function create()
    {
        $this->hasRole(2, 19);
        $category = $this->model->getCategory($this->merchant_id);
        $source = $this->model->getSource($this->merchant_id);
        $list = $this->common->getListValue('merchant_tax', 'merchant_id', $this->merchant_id, 1);
        $this->smarty->assign("category", $category);
        $this->smarty->assign("source", $source);
        $this->smarty->assign("tax", $list);
        $this->view->title = 'Create Plan';
        $this->smarty->assign('title',$this->view->title);
        //Breadcumbs array start
         $breadcumbs_array = array(
            array('title' => 'Settings','url' => '/merchant/profile/settings'),
            array('title' => 'Data configuration','url' => ''),
            array('title'=> 'Plan list', 'url'=>'/merchant/plan/viewlist'),
            array('title'=> $this->view->title,'url'=>'')
        );
        $this->smarty->assign("links", $breadcumbs_array);
        $this->view->header_file = ['profile'];
        $this->view->render('header/app');
        $this->smarty->display(VIEW . 'merchant/plan/create.tpl');
        $this->view->render('footer/profile');
    }

    /**
     * Save new supplier 
     */
    function plansave()
    {
        try {
            if (empty($_POST)) {
                header("Location:/merchant/plan/create");
            }
            require CONTROLLER . 'merchant/Suppliervalidator.php';
            $int = 0;
            $hasErrors = array();
            $_POST['category_name'] = ($_POST['category_name'] != '') ? $_POST['category_name'] : $_POST['ex_category_name'];
            $_POST['source_name'] = ($_POST['source_name'] != '') ? $_POST['source_name'] : $_POST['ex_source_name'];
            foreach ($_POST['plan_name'] as $row) {
                $_POST['v_plan_name'] = $_POST['plan_name'][$int];
                $_POST['v_speed'] = $_POST['speed'][$int];
                $_POST['v_duration'] = $_POST['duration'][$int];
                $_POST['v_price'] = $_POST['price'][$int];
                $_POST['data_limit_type'][$int] = ($_POST['data_limit'][$int] != '0') ? $_POST['data_limit_type'][$int] : 'Unlimited';
                $_POST['data_limit'][$int] = ($_POST['data_limit_type'][$int] != 'Unlimited') ? $_POST['data_limit'][$int] : '';
                $_POST['data_limit'][$int] = ($_POST['data_limit'][$int] == '0') ? '' : $_POST['data_limit'][$int];
                $_POST['tax1'][$int] = 0;
                $_POST['tax2'][$int] = 0;
                $_POST['tax1_text'][$int] = '';
                $_POST['tax2_text'][$int] = '';
                if ($_POST['tax1_id'][$int] > 0) {
                    $tax = $this->common->getSingleValue('merchant_tax', 'tax_id',  $_POST['tax1_id'][$int]);
                    $_POST['tax1'][$int] = $tax['percentage'];
                    $_POST['tax1_text'][$int] = $tax['tax_name'];
                } else {
                    $_POST['tax1_id'][$int] = 0;
                }
                if ($_POST['tax2_id'][$int] > 0) {
                    $tax = $this->common->getSingleValue('merchant_tax', 'tax_id',  $_POST['tax2_id'][$int]);
                    $_POST['tax2'][$int] = $tax['percentage'];
                    $_POST['tax2_text'][$int] = $tax['tax_name'];
                } else {
                    $_POST['tax2_id'][$int] = 0;
                }

                $data_array[] = array('source_name' => $_POST['source_name'], 'category_name' => $_POST['category_name'], 'plan_name' => $_POST['plan_name'][$int], 'speed' => $_POST['speed'][$int] . $_POST['speed_type'][$int], 'data' => $_POST['data_limit'][$int] . $_POST['data_limit_type'][$int], 'duration' => $_POST['duration'][$int], 'price' => $_POST['price'][$int], 'tax1_text' => $_POST['tax1_text'][$int], 'tax2_text' => $_POST['tax2_text'][$int], 'tax1' => $_POST['tax1'][$int], 'tax2' => $_POST['tax2'][$int], 'tax1_id' => $_POST['tax1_id'][$int], 'tax2_id' => $_POST['tax2_id'][$int]);
                $validator = new Suppliervalidator($this->model);
                $validator->validatePlanSave($this->merchant_id);
                $Errors = $validator->fetchErrors();
                if ($Errors != false) {
                    $hasErrors = array_merge($hasErrors, $Errors);
                }
                $int++;
            }
            if (empty($hasErrors)) {
                foreach ($data_array as $data) {
                    $this->model->createPlan($this->merchant_id, $data['source_name'], $data['category_name'], $data['plan_name'], $data['speed'], $data['data'], $data['duration'], $data['price'], $data['tax1_text'], $data['tax2_text'], $data['tax1'], $data['tax2'], $data['tax1_id'], $data['tax2_id'], $this->user_id);
                }
                $this->session->set('successMessage', 'Plans have been saved.');
                header("Location:/merchant/plan/viewlist");
            } else {
                $this->smarty->assign("haserrors", $hasErrors);
                $this->smarty->assign("post", $_POST);
                $this->create();
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E289]Error while creating supplier Error: ' . $e->getMessage());
            header("Location:/error");
        }
    }

    /**
     * Delete merchant supplier
     */
    function delete($plan_id)
    {
        try {
            $this->hasRole(3, 19);
            $user_id = $this->session->get('userid');
            $converter = new Encryption;
            $plan_id = $converter->decode($plan_id);
            if (!is_numeric($plan_id)) {
                $this->setInvalidLinkError();
            }
            $result = $this->model->deletePlan($plan_id, $user_id, $this->merchant_id);
            if ($result == true) {
                $this->session->set('successMessage', 'Plan has been deleted successfully.');
                header("Location:/merchant/plan/viewlist");
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E290]Error while deleting supplier Error: ' . $e->getMessage());
            header("Location:/error");
        }
    }

    /**
     * Supplier update initiate
     */
    function update($link)
    {
        try {
            $this->hasRole(2, 19);
            $converter = new Encryption;
            $plan_id = $converter->decode($link);
            if (!is_numeric($plan_id)) {
                $this->setInvalidLinkError();
            }
            $plan = $this->model->getPlanDetails($plan_id);
            if ($plan['merchant_id'] != $this->merchant_id) {
                SwipezLogger::error(__CLASS__, '[E291]Error while update plan profile fetching plan details Error: ');
                $this->setInvalidLinkError();
            }
            $plan['speed_type'] = substr($plan['speed'], -4);
            $plan['speed'] = trim(str_replace($plan['speed_type'], '', $plan['speed']));
            if ($plan['data'] == 'Unlimited') {
                $plan['data'] = '0';
                $plan['data_limit_type'] = 'Unlimited';
            } else {
                $plan['data_limit_type'] = substr($plan['data'], -3);
                $plan['data'] = trim(str_replace($plan['data_limit_type'], '', $plan['data']));
            }


            $category = $this->model->getCategory($this->merchant_id);
            $source = $this->model->getSource($this->merchant_id);
            $list = $this->common->getListValue('merchant_tax', 'merchant_id', $this->merchant_id, 1);
            $this->smarty->assign("tax", $list);

            $this->smarty->assign("category", $category);
            $this->smarty->assign("source", $source);
            $this->smarty->assign("plan", $plan);
            $this->smarty->assign("plan_id", $link);
            $this->view->title = 'Update plan';
            $this->smarty->assign('title',$this->view->title);
            //Breadcumbs array start
            $breadcumbs_array = array(
                array('title' => 'Settings','url' => '/merchant/profile/settings'),
                array('title' => 'Data configuration','url' => ''),
                array('title'=> 'Plan list', 'url'=>'/merchant/plan/viewlist'),
                array('title'=> $plan['plan_name'],'url'=>'')
            );
            $this->smarty->assign("links", $breadcumbs_array);

            $this->view->header_file = ['profile'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/plan/update.tpl');
            $this->view->render('footer/profile');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E292]Error while updating supplier Error: ' . $e->getMessage());
            header("Location:/error");
        }
    }

    /**
     * Update save supplier
     */
    function saveupdate()
    {
        try {
            if (empty($_POST)) {
                header("Location:/merchant/plan/viewlist");
            }

            $_POST['category_name'] = ($_POST['category_name'] != '') ? $_POST['category_name'] : $_POST['ex_category_name'];
            $_POST['source_name'] = ($_POST['source_name'] != '') ? $_POST['source_name'] : $_POST['ex_source_name'];
            $_POST['data_limit_type'] = ($_POST['data'] != '0') ? $_POST['data_limit_type'] : 'Unlimited';
            $_POST['data'] = ($_POST['data_limit_type'] != 'Unlimited') ? $_POST['data'] : '';
            $_POST['data'] = ($_POST['data'] == '0') ? '' : $_POST['data'];
            $_POST['tax1'] = ($_POST['tax1'] > 0) ? $_POST['tax1'] : 0;
            $_POST['tax2'] = ($_POST['tax2'] > 0) ? $_POST['tax2'] : 0;


            $_POST['tax1'] = 0;
            $_POST['tax2'] = 0;
            $_POST['tax1_text'] = '';
            $_POST['tax2_text'] = '';
            if ($_POST['tax1_id'] > 0) {
                $tax = $this->common->getSingleValue('merchant_tax', 'tax_id',  $_POST['tax1_id']);
                $_POST['tax1'] = $tax['percentage'];
                $_POST['tax1_text'] = $tax['tax_name'];
            } else {
                $_POST['tax1_id'] = 0;
            }
            if ($_POST['tax2_id'] > 0) {
                $tax = $this->common->getSingleValue('merchant_tax', 'tax_id',  $_POST['tax2_id']);
                $_POST['tax2'] = $tax['percentage'];
                $_POST['tax2_text'] = $tax['tax_name'];
            } else {
                $_POST['tax2_id'] = 0;
            }

            $plan_id = $this->encrypt->decode($_POST['plan_id']);
            $this->model->updatePlan($plan_id, $this->user_id, $_POST['source_name'], $_POST['category_name'], $_POST['plan_name'], $_POST['speed'] . $_POST['speed_type'], $_POST['data'] . $_POST['data_limit_type'], $_POST['duration'], $_POST['price'], $_POST['tax1'], $_POST['tax1_text'], $_POST['tax2'], $_POST['tax2_text'],$_POST['tax1_id'],$_POST['tax2_id']);
            $this->session->set('successMessage', 'Plan details have been updated.');
            header("Location:/merchant/plan/viewlist");
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E293]Error while updating supplier Error: ' . $e->getMessage());
            header("Location:/error");
        }
    }

    /**
     * Update save supplier
     */
    function savesetting()
    {
        try {
            if (empty($_POST)) {
                header("Location:/merchant/plan/viewlist");
            }
            $invoice_create = ($_POST['plan_invoice_create'] == 1) ? 1 : 0;
            $invoice_send = ($_POST['plan_invoice_send'] == 1) ? 1 : 0;
            $template_id = $_POST['selecttemplate'];
            $this->model->updatePlanSetting($invoice_create, $invoice_send, $template_id, $this->merchant_id);
            $this->session->set('successMessage', 'Plan settings have been updated.');
            header("Location:/merchant/plan/viewlist");
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E293]Error while updating supplier Error: ' . $e->getMessage());
            header("Location:/error");
        }
    }
}
