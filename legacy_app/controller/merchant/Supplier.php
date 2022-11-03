<?php

/**
 * Supplier controller class to handle Merchants supplier
 */
class Supplier extends Controller {

    function __construct() {
        parent::__construct();
        $this->validateSession('merchant');
        $this->view->selectedMenu = array(14, 59);
        $this->common->isActiveService($this->merchant_id, 'supplier');
    }

    /**
     * Display merchant suppliers list
     */
    function viewlist() {
        try {
            $this->hasRole(1, 11);
            $success = $this->session->get('successMessage');
            if ($success != '') {
                $this->session->remove('successMessage');
            }
            $list = $this->model->getSupplierList($this->merchant_id);
            $int = 0;
            foreach ($list as $item) {
                $list[$int]['encrypted_id'] = $this->encrypt->encode($item['supplier_id']);
                $int++;
            }

            $this->smarty->assign("success", $success);
            $this->smarty->assign("list", $list);
            $this->smarty->assign("title", "Supplier list");
            $this->view->title = "Supplier list";
            //Breadcumbs array start
            $breadcumbs_array = array(
                array('title' => 'Settings','url' => '/merchant/profile/settings'),
                array('title' => 'Data configuration','url' => ''),
                array('title'=> $this->view->title, 'url'=>''),
            );
            $this->smarty->assign("links", $breadcumbs_array);

            $this->view->canonical = 'merchant/supplier/viewlist';
            $this->view->datatablejs = 'table-no-export';
            $this->view->header_file = ['list'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/supplier/list.tpl');
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
        $this->hasRole(2, 11);
        $industrytype = $this->model->getIndustryType();
        if (empty($industrytype)) {
            SwipezLogger::warn(__CLASS__, 'Fetching empty industry type list while merchant update profile ');
        }
        $this->smarty->assign("list", $industrytype);
        $this->view->title = 'Create Supplier';
        $this->smarty->assign('title',$this->view->title);
        //Breadcumbs array start
        $breadcumbs_array = array(
            array('title' => 'Settings','url' => '/merchant/profile/settings'),
            array('title' => 'Data configuration','url' => ''),
            array('title'=> $this->view->title, 'url'=>''),
        );
        $this->smarty->assign("links", $breadcumbs_array);
        $this->view->header_file = ['profile'];
        $this->view->render('header/app');
        $this->smarty->display(VIEW . 'merchant/supplier/create.tpl');
        $this->view->render('footer/profile');
    }

    /**
     * Save new supplier 
     */
    function suppliersave() {
        try {
            $user_id = $this->session->get('userid');
            if (empty($_POST)) {
                header("Location:/merchant/supplier/create");
            }
            require CONTROLLER . 'merchant/Suppliervalidator.php';
            $validator = new Suppliervalidator($this->model);
            $validator->validateSupplierSave();
            $hasErrors = $validator->fetchErrors();
            if ($hasErrors == false) {
                $result = $this->model->createSupplier($user_id, $_POST['email'], $_POST['email2'], $_POST['mob_country_code1'], $_POST['mobile'], $_POST['mob_country_code2'], $_POST['mobile2'], $_POST['industry_type'], $_POST['supplier_company_name'], $_POST['contact_person_name'], $_POST['contact_person_name2'], $_POST['company_website']);
                if ($result == 'success') {
                    $this->session->set('successMessage', 'Supplier details have been saved.');
                    header("Location:/merchant/supplier/viewlist");
                }
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
    function delete($supplier_id) {
        try {
            $this->hasRole(3, 11);
            $user_id = $this->session->get('userid');
            $converter = new Encryption;
            $supplier_id = $converter->decode($supplier_id);
            if (!is_numeric($supplier_id)) {
                $this->setInvalidLinkError();
            }
            $result = $this->model->deletesupplier($supplier_id, $user_id);
            if ($result == true) {
                $this->session->set('successMessage', 'Supplier have been deleted successfully.');
                header("Location:/merchant/supplier/viewlist");
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
            $this->hasRole(2, 11);
            $supplier_id = $this->encrypt->decode($link);
            if (!is_numeric($supplier_id)) {
                $this->setInvalidLinkError();
            }
            $user_id = $this->session->get('userid');
            $supplierdetails = $this->model->getSupplierDetails($supplier_id, $user_id);
            if (empty($supplierdetails)) {
                SwipezLogger::error(__CLASS__, '[E291]Error while update supplier profile fetching supplier details Error: ');
                $this->setInvalidLinkError();
            }
            $industrytype = $this->model->getIndustryType();
            if (empty($this->view->industrytype)) {
                SwipezLogger::warn(__CLASS__, 'Fetching empty industry type list while merchant update profile ');
            }
            $this->smarty->assign("supplier", $supplierdetails);
            $this->smarty->assign("supplier_id", $link);
            $this->smarty->assign("list", $industrytype);
            $this->view->title = 'Update supplier';
            $this->smarty->assign('title',$this->view->title);
            
            //Breadcumbs array start
            $breadcumbs_array = array(
                array('title' => 'Settings','url' => '/merchant/profile/settings'),
                array('title' => 'Data configuration','url' => ''),
                array('title'=> 'Supplier list', 'url'=>'/merchant/supplier/viewlist'),
                array('title'=> 'Update supplier', 'url'=>'/merchant/supplier/update/'.$link),
                array('title'=> $supplierdetails['supplier_company_name'],'url'=>'')
            );
            $this->smarty->assign("links", $breadcumbs_array);

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
