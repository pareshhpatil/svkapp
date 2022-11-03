<?php

/**
 * Supplier controller class to handle Merchants supplier
 */
class Coupon extends Controller {

    function __construct() {
        parent::__construct();
        $this->validateSession('merchant');
        $this->view->selectedMenu = array(14, 63);
        $this->common->isActiveService($this->merchant_id, 'coupon');
    }

    /**
     * Display merchant suppliers list
     */
    function viewlist() {
        try {
            $this->hasRole(1, 17);
            $list = $this->model->getCouponList($this->merchant_id);
            $int = 0;
            foreach ($list as $item) {
                $list[$int]['encrypted_id'] = $this->encrypt->encode($item['coupon_id']);
                $int++;
            }

            $this->smarty->assign("list", $list);
            $this->smarty->assign("title", "Coupon list");
            $this->view->title = "Coupon list";
            //Breadcumbs array start
            $breadcumbs_array = array(
                array('title' => 'Settings','url' => '/merchant/profile/settings'),
                array('title' => 'Data configuration','url' => ''),
                array('title'=> $this->view->title, 'url'=>''),
            );
            $this->smarty->assign("links", $breadcumbs_array);

            $this->view->datatablejs = 'table-no-export';
            $this->view->header_file = ['list'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/coupon/list.tpl');
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
        $this->hasRole(2, 17);
        $this->view->js = array('setting');
        $this->view->title = "Create Coupon";
        $this->smarty->assign('title',$this->view->title);
        //Breadcumbs array start
        $breadcumbs_array = array(
            array('title' => 'Settings','url' => '/merchant/profile/settings'),
            array('title' => 'Data configuration','url' => ''),
            array('title'=> 'Coupon list', 'url'=>'/merchant/coupon/viewlist'),
            array('title'=> $this->view->title, 'url'=>''),
        );
        $this->smarty->assign("links", $breadcumbs_array);

        $this->view->header_file = ['profile'];
        $this->view->render('header/app');
        $this->smarty->display(VIEW . 'merchant/coupon/create.tpl');
        $this->view->render('footer/profile');
    }

    /**
     * Save new supplier 
     */
    function save($is_popup = NULL) {
        try {
            $user_id = $this->session->get('userid');
            if (empty($_POST)) {
                header("Location:/merchant/coupon/create");
            }

            require_once CONTROLLER . 'merchant/Templatevalidator.php';

            $validator = new Templatevalidator($this->model);
            $validator->validateCouponSave($this->merchant_id);
            $hasErrors = $validator->fetchErrors();
            if ($is_popup != NULL) {
                $hasrole = $this->hasRole(2, 17, 1);
                if ($hasrole == 0) {
                    $hasErrors[0][0] = "Access denied";
                    $hasErrors[0][1] = "You do not have access to this feature. If you need access to this feature please contact your main merchant.";
                }
            }

            if ($hasErrors == false) {
                $sdate = new DateTime($_POST['start_date']);
                $start_date = $sdate->format('Y-m-d');
                $sdate = new DateTime($_POST['end_date']);
                $end_date = $sdate->format('Y-m-d');
                $is_fixed = ($_POST['is_fixed'] == 1) ? 1 : 2;
                $percent = ($_POST['percent'] > 0) ? $_POST['percent'] : 0;
                $fixed_amount = ($_POST['fixed_amount'] > 0) ? $_POST['fixed_amount'] : 0;
                if ($fixed_amount == 0 && $percent == 0) {
                    $coupon['status'] = 0;
                    echo json_encode($coupon);
                    exit();
                }

                $id = $this->model->createCoupon($user_id,$this->merchant_id, $_POST['coupon_code'], $_POST['descreption'], $start_date, $end_date, $_POST['limit'], $is_fixed, $percent, $fixed_amount);
                if ($is_popup == NULL) {
                    $this->session->set('successMessage', 'Coupon code has been saved.');
                    header("Location:/merchant/coupon/viewlist");
                } else {
                    $coupon['code'] = $_POST['coupon_code'];
                    $coupon['id'] = $id;
                    $coupon['status'] = 1;
                    echo json_encode($coupon);
                }
            } else {
                if ($is_popup == NULL) {
                    $this->smarty->assign("haserrors", $hasErrors);
                    $this->smarty->assign("post", $_POST);
                    $this->create();
                } else {
                    foreach ($hasErrors as $error_name) {
                        $error = '<b>' . $error_name[0] . '</b> -';
                        $int = 1;
                        while (isset($error_name[$int])) {
                            $error .= '' . $error_name[$int];
                            $int++;
                        }
                        $err[]['value'] = $error;
                    }
                    $coupon['error'] = $err;
                    $coupon['status'] = 0;
                    echo json_encode($coupon);
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
    function delete($link) {
        try {
            $this->hasRole(3, 17);
            $user_id = $this->session->get('userid');
            $coupon_id = $this->encrypt->decode($link);
            $result = $this->model->deleteCoupon($coupon_id, $user_id);
            if ($result == true) {
                $this->session->set('successMessage', 'Coupon has been deleted successfully.');
                header("Location:/merchant/coupon/viewlist");
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
            $this->hasRole(2, 17);
            $converter = new Encryption;
            $supplier_id = $converter->decode($link);
            $user_id = $this->session->get('userid');
            $supplierdetails = $this->model->getSupplierDetails($supplier_id, $user_id);
            if (empty($supplierdetails)) {
SwipezLogger::error(__CLASS__, '[E291]Error while update supplier profile fetching supplier details Error: ');
                $this->setGenericError();
            }
            $industrytype = $this->model->getIndustryType();
            if (empty($this->view->industrytype)) {
                SwipezLogger::warn(__CLASS__, 'Fetching empty industry type list while merchant update profile ');
            }
            $this->smarty->assign("supplier", $supplierdetails);
            $this->smarty->assign("supplier_id", $link);
            $this->smarty->assign("list", $industrytype);
            $this->view->title = 'Coupon update';
            $this->view->header_file = ['profile'];
$this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/supplier/update.tpl');
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
                header("Location:/merchant/supplier/viewlist");
            }
            require CONTROLLER . 'merchant/Suppliervalidator.php';
            $validator = new Suppliervalidator($this->model);
            $validator->validateSupplierUpdate();
            $hasErrors = $validator->fetchErrors();
            $supplier_id = $converter->decode($_POST['supplier_id']);

            if ($hasErrors == false) {
                $result = $this->model->updatesupplier($supplier_id, $user_id, $_POST['email1'], $_POST['email2'], $_POST['mob_country_code1'], $_POST['mobile1'], $_POST['mob_country_code2'], $_POST['mobile2'], $_POST['industry_type'], $_POST['supplier_company_name'], $_POST['contact_person_name'], $_POST['contact_person_name2'], $_POST['company_website']);
                if ($result == 'success') {
                    $this->session->set('successMessage', 'Supplier details have been updated.');
                    header("Location:/merchant/supplier/viewlist");
                }
            } else {
                $this->view->title = 'Coupon update';
                $this->view->_POST = $_POST;
                $this->view->setError($hasErrors);
                $this->create();
            }
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E293]Error while updating supplier Error: ' . $e->getMessage());
            header("Location:/error");
        }
    }

}

?>
