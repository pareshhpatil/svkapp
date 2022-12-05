<?php

/**
 * Supplier controller class to handle Merchants supplier
 */
class Product extends Controller {

    function __construct() {
        parent::__construct();
        $this->validateSession('merchant');
        $this->view->selectedMenu = array(14, 60);
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
            $list = $this->common->getListValue('merchant_product', 'merchant_id', $this->merchant_id, 1);
            $int = 0;
            foreach ($list as $item) {
                $list[$int]['encrypted_id'] = $this->encrypt->encode($item['product_id']);
                $int++;
            }

            $this->smarty->assign("success", $success);
            $this->smarty->assign("list", $list);
            $this->smarty->assign("title", "Product list");
            $this->view->hide_first_col = true;
            $this->view->title = "Product list";
            $this->view->canonical = 'merchant/supplier/viewlist';
            $this->view->datatablejs = 'table-no-export';
            $this->view->header_file = ['list'];
        $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/product/list.tpl');
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
        $this->view->title = 'Create Product';
        $tax_list = $this->common->getListValue('merchant_tax', 'merchant_id', $this->merchant_id, 1);
        $this->smarty->assign("tax", $tax_list);
        $this->view->header_file = ['list'];
        $this->view->render('header/app');
        $this->smarty->display(VIEW . 'merchant/product/create.tpl');
        $this->view->render('footer/list');
    }

    /**
     * Save new supplier 
     */
    function productsave($ajax = null) {
        try {
            if (empty($_POST)) {
                header("Location:/merchant/product/create");
            }
            require CONTROLLER . 'merchant/Suppliervalidator.php';
            $validator = new Suppliervalidator($this->model);
            $validator->validateProductSave($this->merchant_id);
            $hasErrors = $validator->fetchErrors();
            if ($hasErrors == false) {
                $_POST['price'] = ($_POST['price'] != '') ? $_POST['price'] : 0;
                $result = $this->model->createProduct($_POST['product_name'], $_POST['price'], $_POST['description'], $_POST['type'], $_POST['unit_type'], $_POST['sac_code'], $_POST['gst_percent'], $this->merchant_id, $this->user_id);
                if ($ajax == 1) {
                    $product_list = $this->common->getListValue('merchant_product', 'merchant_id', $this->merchant_id, 1);
                    $products = array();
                    if (!empty($product_list)) {
                        foreach ($product_list as $ar) {
                            $products[$ar['product_name']] = array('price' => $ar['price'], 'sac_code' => $ar['sac_code'], 'gst_percent' => $ar['gst_percent'], 'unit_type' => $ar['unit_type'], 'name' => $ar['product_name']);
                        }
                        $res["product_array"] = json_encode($products);
                    }
                    $res['status'] = 1;
                    $res['name'] = $_POST['product_name'];
                    echo json_encode($res);
                } else {
                    $this->session->set('successMessage', 'Product details have been saved.');
                    header("Location:/merchant/product/viewlist");
                }
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
            $result = $this->model->deleteProduct($pr_id, $user_id, $this->merchant_id);
            if ($result == true) {
                $this->session->set('successMessage', 'Product has been deleted successfully.');
                header("Location:/merchant/product/viewlist");
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
            $product_id = $this->encrypt->decode($link);
            if (!is_numeric($product_id)) {
                $this->setInvalidLinkError();
            }
            $detail = $this->common->getSingleValue('merchant_product', 'product_id', $product_id);
            $this->smarty->assign("tax_select", explode(',', $detail['taxes']));
            $tax_list = $this->common->getListValue('merchant_tax', 'merchant_id', $this->merchant_id, 1);
            $this->smarty->assign("tax", $tax_list);
            $this->smarty->assign("detail", $detail);
            $this->view->title = 'Update product';
            $this->view->header_file = ['list'];
        $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/product/update.tpl');
            $this->view->render('footer/list');
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
            if (empty($_POST)) {
                header("Location:/merchant/product/viewlist");
            }
            require CONTROLLER . 'merchant/Suppliervalidator.php';
            $validator = new Suppliervalidator($this->model);
            $validator->validateProductUpdate($this->merchant_id, $_POST['product_id']);
            $hasErrors = $validator->fetchErrors();

            if ($hasErrors == false) {
                $result = $this->model->updateProduct($_POST['product_id'], $_POST['product_name'], $_POST['price'], $_POST['description'], $_POST['type'], $_POST['unit_type'], $_POST['sac_code'], $_POST['gst_percent'], $this->merchant_id, $this->user_id);
                $this->session->set('successMessage', 'Product details have been updated.');
                header("Location:/merchant/product/viewlist");
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
