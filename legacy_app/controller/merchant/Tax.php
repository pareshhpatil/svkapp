<?php

/**
 * Supplier controller class to handle Merchants supplier
 */
class Tax extends Controller {

    function __construct() {
        parent::__construct();
        $this->validateSession('merchant');
        $this->view->selectedMenu = array(14, 61);
    }

    /**
     * Display merchant suppliers list
     */
    function viewlist() {
        try {
            $this->hasRole(1, 24);
            $success = $this->session->get('successMessage');
            if ($success != '') {
                $this->session->remove('successMessage');
            }
            $list = $this->common->getListValue('merchant_tax', 'merchant_id', $this->merchant_id, 1);
            $int = 0;
            foreach ($list as $item) {
                $list[$int]['encrypted_id'] = $this->encrypt->encode($item['tax_id']);
                $int++;
            }

            $this->smarty->assign("success", $success);
            $this->smarty->assign("list", $list);
            $this->smarty->assign("title", "Tax list");
            $this->view->title = "Tax list";

            //Breadcumbs array start
            $breadcumbs_array = array(
                array('title' => 'Settings','url' => '/merchant/profile/settings'),
                array('title' => 'Data configuration','url' => ''),
                array('title'=> $this->view->title, 'url'=>'')
                );
            $this->smarty->assign("links", $breadcumbs_array);
            //Breadcumbs array end

            $this->view->hide_first_col = true;
            $this->view->canonical = 'merchant/supplier/viewlist';
            $this->view->datatablejs = 'table-no-export';
            $this->view->header_file = ['list'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/tax/list.tpl');
            $this->view->render('footer/list');
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E999]Error while listing suppliers Error: for user id [' . $this->session->get('userid') . '] ' . $e->getMessage());
        }
    }

    /**
     * Create new supplier for merchant
     */
    function create() {
        $this->hasRole(2, 24);
        $tax_type = $this->common->getListValue('config', 'config_type', 'tax_type');
        $this->smarty->assign("_token", $token);
        $this->view->js = array('invoiceformat');
        $this->smarty->assign("tax_type", $tax_type);

        $tax_calculated_on = $this->common->getListValue('config', 'config_type', 'tax_calculated_on');
        $this->smarty->assign("tax_calculated_on", $tax_calculated_on);

        $this->view->title = 'Create Tax';
        $this->smarty->assign('title',$this->view->title);
        //Breadcumbs array start
        $breadcumbs_array = array(
            array('title' => 'Settings','url' => '/merchant/profile/settings'),
            array('title' => 'Data configuration','url' => ''),
            array('title'=> 'Tax list', 'url'=>'/merchant/tax/viewlist'),
            array('title'=> $this->view->title, 'url'=>'')
            );
        $this->smarty->assign("links", $breadcumbs_array);
        //Breadcumbs array end
        $this->view->header_file = ['profile'];
        $this->view->render('header/app');
        $this->smarty->display(VIEW . 'merchant/tax/create.tpl');
        $this->view->render('footer/profile');
    }

    /**
     * Save new supplier 
     */
    function taxsave($ajax = null) {
        try {
            if (empty($_POST)) {
                header("Location:/merchant/tax/create");
            }
            require CONTROLLER . 'merchant/Suppliervalidator.php';
            $validator = new Suppliervalidator($this->model);
            $validator->validateTaxSave($this->merchant_id);
            $hasErrors = $validator->fetchErrors();
            if ($hasErrors == false) {
                if ($_POST['tax_type'] == 5) {
                    $_POST['percentage'] = 0;
                } else {
                    $_POST['tax_amount'] = 0;
                }

                $tax_id = $this->model->createTax($_POST['tax_name'], $_POST['percentage'], $_POST['tax_amount'], $_POST['description'], $_POST['tax_type'],$_POST['tax_calculated_on'], $this->merchant_id, $this->user_id);

                if ($ajax == 1) {
                    $tax_list = $this->common->getListValue('merchant_tax', 'merchant_id', $this->merchant_id, 1);
                    $tax_array = array();
                    $tax_rate_array = array();
                    foreach ($tax_list as $ar) {
                        $tax_array[$ar['tax_id']] = array('tax_type' => $ar['tax_type'], 'tax_name' => $ar['tax_name'], 'percentage' => $ar['percentage'], 'fix_amount' => $ar['fix_amount']);
                        if ($ar['tax_type'] == 5) {
                            $tax_rate_array[$ar['tax_name']] = $ar['tax_amount'];
                        } else {
                            $tax_rate_array[$ar['tax_name']] = $ar['percentage'];
                        }
                    }
                    if (!empty($tax_list)) {
                        $array["tax_array"] = json_encode($tax_array);
                        $array["tax_list"] = $tax_array;
                        $array["merchant_tax_list"] = $tax_list;
                        $array["tax_rate_array"] = json_encode($tax_rate_array);
                    }
                    $array['status'] = 1;
                    $array['tax_id'] = $tax_id;
                    $array['tax_name'] = $_POST['tax_name'];
                    echo json_encode($array);
                    die();
                }

                $this->session->set('successMessage', 'Tax details have been saved.');
                header("Location:/merchant/tax/viewlist");
            } else {
                if ($ajax == 1) {
                    foreach ($hasErrors as $error_name) {
                        $error = '<b>' . $error_name[0] . '</b> -';
                        $int = 1;
                        while (isset($error_name[$int])) {
                            $error .= '' . $error_name[$int];
                            $int++;
                        }
                        $err .= $error . '<br>';
                    }
                    $haserror['status'] = 0;
                    $haserror['error'] = $err;
                    echo json_encode($haserror);
                } else {
                    $this->smarty->assign("haserrors", $hasErrors);
                    $this->smarty->assign("post", $_POST);
                    $this->create();
                }
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
    function delete($supplier_id) {
        try {
            $this->hasRole(3, 24);
            $user_id = $this->session->get('userid');
            $converter = new Encryption;
            $pr_id = $converter->decode($supplier_id);
            if (!is_numeric($pr_id)) {
                $this->setInvalidLinkError();
            }
            $result = $this->model->deleteTax($pr_id, $user_id, $this->merchant_id);
            if ($result == true) {
                $this->session->set('successMessage', 'Tax has been deleted successfully.');
                header("Location:/merchant/tax/viewlist");
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
    function update($link) {
        try {
            $this->hasRole(2, 24);
            $tax_id = $this->encrypt->decode($link);
            if (!is_numeric($tax_id)) {
                $this->setInvalidLinkError();
            }
            $tax_type = $this->common->getListValue('config', 'config_type', 'tax_type');
            $this->smarty->assign("tax_type", $tax_type);
            $tax_calculated_on = $this->common->getListValue('config', 'config_type', 'tax_calculated_on');
            $this->smarty->assign("tax_calculated_on", $tax_calculated_on);

            $detail = $this->common->getSingleValue('merchant_tax', 'tax_id', $tax_id);
            $this->smarty->assign("detail", $detail);
            $this->view->js = array('invoiceformat');
            $this->view->title = 'Update tax';
            $this->smarty->assign('title',$this->view->title);
            //Breadcumbs array start
            $breadcumbs_array = array(
                array('title' => 'Settings','url' => '/merchant/profile/settings'),
                array('title' => 'Data configuration','url' => ''),
                array('title'=> 'Tax list', 'url'=>'/merchant/tax/viewlist'),
                array('title'=> 'Update tax', 'url'=>'/merchant/tax/update/'.$link),
                array('title'=> $detail['tax_name'], 'url'=>'')
            );
            $this->smarty->assign("links", $breadcumbs_array);
            //Breadcumbs array end

            $this->view->header_file = ['profile'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/tax/update.tpl');
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
    function saveupdate() {
        try {
            $converter = new Encryption;
            $user_id = $this->session->get('userid');
            if (empty($_POST)) {
                header("Location:/merchant/tax/viewlist");
            }
            require CONTROLLER . 'merchant/Suppliervalidator.php';
            $validator = new Suppliervalidator($this->model);
            $validator->validateTaxUpdate($this->merchant_id, $_POST['tax_id']);
            $hasErrors = $validator->fetchErrors();
            if ($_POST['tax_type'] == 5) {
                $_POST['percentage'] = 0;
            } else {
                $_POST['tax_amount'] = 0;
            }

            if ($hasErrors == false) {
                $result = $this->model->updateTax($_POST['tax_id'], $_POST['tax_name'], $_POST['percentage'], $_POST['tax_amount'], $_POST['description'], $_POST['tax_type'], $_POST['tax_calculated_on'], $this->merchant_id, $this->user_id);
                $this->session->set('successMessage', 'Tax details have been updated.');
                header("Location:/merchant/tax/viewlist");
            } else {
                $this->view->title = 'Supplier Update';
                $this->view->_POST = $_POST;
                $this->view->setError($hasErrors);
                $this->smarty->assign("haserrors", $hasErrors);
                $this->update($_POST['supplier_id']);
            }
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E293]Error while updating supplier Error: ' . $e->getMessage());
            header("Location:/error");
        }
    }

}

?>
