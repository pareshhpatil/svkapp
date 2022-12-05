<?php

/**
 * Supplier controller class to handle Merchants supplier
 */
class Coveringnote extends Controller {

    function __construct() {
        parent::__construct();
        $this->validateSession('merchant');
        $this->view->selectedMenu = array(14, 66);
    }

    /**
     * Display merchant suppliers list
     */
    function viewlist() {
        try {
            
            $list = $this->common->getListValue('covering_note', 'merchant_id', $this->merchant_id, 1);
            $int = 0;
            foreach ($list as $item) {
                $list[$int]['encrypted_id'] = $this->encrypt->encode($item['covering_id']);
                $list[$int]['created_at'] = $this->generic->formatTimeString($item['created_date']);
                $int++;
            }
            $this->smarty->assign("list", $list);
            $this->smarty->assign("title", "Covering note");
            $this->view->title = "Covering note";

            //Breadcumbs array start
            $breadcumbs_array = array(
                array('title' => 'Settings','url' => '/merchant/profile/settings'),
                array('title'=>'Billing & Invoicing','url'=>''),
                array('title'=> $this->view->title, 'url'=>'')
            );
            $this->smarty->assign("links", $breadcumbs_array);
            //Breadcumbs array end

            $this->view->canonical = 'merchant/coveringnote/viewlist';
            $this->view->hide_first_col = true;
            $this->view->datatablejs = 'table-no-export';
            $this->view->header_file = ['list'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/coveringnote/list.tpl');
            $this->view->render('footer/list');
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E999]Error while listing suppliers Error: for user id [' . $this->session->get('userid') . '] ' . $e->getMessage());
        }
    }

    /**
     * Display merchant suppliers list
     */
    function dynamicvariable() {
        try {
            $list = $this->common->getListValue('dynamic_variable', 'is_active', 1);
            $this->smarty->assign("list", $list);
            $this->smarty->assign("title", "Covering note list");
            $this->view->title = "Covering note list";
            $this->view->canonical = 'merchant/coveringnote/viewlist';
            $this->view->datatablejs = 'table-no-export';
            $this->view->render('header/guest');
            $this->smarty->display(VIEW . 'merchant/coveringnote/dynamiclist.tpl');
            $this->view->render('footer/nonfooter');
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E999]Error while listing suppliers Error: for user id [' . $this->session->get('userid') . '] ' . $e->getMessage());
        }
    }

    /**
     * Create new Covering note for merchant
     */
    function create() {
        $logo = $this->common->getRowValue('logo', 'merchant_landing', 'merchant_id', $this->merchant_id);
        if ($logo != '') {
            $logo = $this->view->server_name . '/uploads/images/landing/' . $logo;
        } else {
            $logo = $this->view->server_name . '/assets/frontend/onepage2/img/logo_scroll.png';
        }
        $this->view->js = array('coveringnote');
        $this->smarty->assign("logo", $logo);
        $this->smarty->assign("merchant_email", $this->session->get('email_id'));
        $this->view->title = 'Create Covering note';
        $this->smarty->assign('title',$this->view->title);
        //Breadcumbs array start
        $breadcumbs_array = array(
            array('title' => 'Settings','url' => '/merchant/profile/settings'),
            array('title'=>'Billing & Invoicing','url'=>''),
            array('title'=> 'Covering note list', 'url'=>'/merchant/coveringnote/viewlist'),
            array('title'=> $this->view->title, 'url'=>'')
        );
        $this->smarty->assign("links", $breadcumbs_array);
        //Breadcumbs array end

        $this->view->header_file = ['create_invoice','template'];
        $this->view->render('header/app');
        $this->smarty->display(VIEW . 'merchant/coveringnote/create.tpl');
        $this->view->render('footer/create_event');
    }

    /**
     * Supplier update initiate
     */
    function update($link) {
        try {
            $covering_id = $this->encrypt->decode($link);
            if (!is_numeric($covering_id)) {
                $this->setInvalidLinkError();
            }
            $user_id = $this->session->get('userid');
            $details = $this->common->getSingleValue('covering_note', 'covering_id', $covering_id);
            if (empty($details)) {
                $this->setInvalidLinkError();
            }
            $this->view->js = array('coveringnote');
            $this->smarty->assign("merchant_email", $this->session->get('email_id'));
            $this->smarty->assign("detail", $details);
            $this->view->title = 'Update Covering note';
            $this->smarty->assign('title',$this->view->title);

            //Breadcumbs array start
            $breadcumbs_array = array(
                array('title' => 'Settings','url' => '/merchant/profile/settings'),
                array('title'=>'Billing & Invoicing','url'=>''),
                array('title'=> 'Covering note list', 'url'=>'/merchant/coveringnote/viewlist'),
                array('title'=> 'Update covering note', 'url'=>'/merchant/coveringnote/update/'.$link),
                array('title'=> $details['template_name'], 'url'=>'')
            );
            $this->smarty->assign("links", $breadcumbs_array);
            //Breadcumbs array end

            $this->view->header_file = ['create_invoice','template'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/coveringnote/update.tpl');
            $this->view->render('footer/create_event');
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E292]Error while updating supplier Error: ' . $e->getMessage());
            header("Location:/error");
        }
    }

    function sendtestmail($type = null) {
        if ($type == 'store') {
            $this->session->set('covering_post', $_POST);
        } else {
            $captcha = $_POST['g-recaptcha-response'];
            $_POST = $this->session->get('covering_post');
            $this->session->remove('covering_post');
            $_POST['g-recaptcha-response'] = $captcha;
            $result = $this->validateCaptcha($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
            if ($result) {
                require_once CONTROLLER . 'Notification.php';
                $notification = new Notification();
                $info['company_name'] = $this->session->get('company_name');
                $notification->sendCoveringNote($_POST, $info);
                $this->smarty->assign("post", $_POST);
                $this->smarty->assign("email_sent", true);
                $this->create();
            } else {
                $hasErrors[0][0] = "Captcha";
                $hasErrors[0][1] = "Invalid captcha please click on captcha box";
                $this->smarty->assign("haserrors", $hasErrors);
                $this->smarty->assign("post", $_POST);
                $this->create();
            }
        }
    }

    /**
     * Save new supplier 
     */
    function save($ajax = 0) {
        try {
            if (empty($_POST)) {
                header("Location:/merchant/coveringnote/create");
            }
            require CONTROLLER . 'merchant/Suppliervalidator.php';
            $validator = new Suppliervalidator($this->model);
            $validator->validateCoveringnoteSave($this->merchant_id);
            $hasErrors = $validator->fetchErrors();
            if ($hasErrors == false) {
                $id = $this->model->createCoveringNote($this->merchant_id, $_POST['template_name'], $_POST['body'], $_POST['subject'], $_POST['invoice_label'], $_POST['pdf_enable'], $this->user_id);
                if ($ajax == 1) {
                    $res['status'] = 1;
                    $res['id'] = $id;
                    $res['name'] = $_POST['template_name'];
                    echo json_encode($res);
                } else {
                    $this->session->set('successMessage', 'Covering details have been saved.');
                    header("Location:/merchant/coveringnote/viewlist");
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
                        $err .= $error;
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
    function delete($link) {
        try {
            $covering_id = $this->encrypt->decode($link);
            if (!is_numeric($covering_id)) {
                $this->setInvalidLinkError();
            }
            $result = $this->model->deleteCoveringnote($covering_id, $this->user_id, $this->merchant_id);
            $this->session->set('successMessage', 'Covering note have been deleted successfully.');
            header("Location:/merchant/coveringnote/viewlist");
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E290]Error while deleting supplier Error: ' . $e->getMessage());
            header("Location:/error");
        }
    }

    /**
     * Update save supplier
     */
    function saveupdate() {
        try {
            $user_id = $this->session->get('userid');
            if (empty($_POST)) {
                header("Location:/merchant/coveringnote/create");
            }
            require CONTROLLER . 'merchant/Suppliervalidator.php';
            $validator = new Suppliervalidator($this->model);
            $validator->validateCoveringnoteUpdate($this->merchant_id, $_POST['covering_id']);
            $hasErrors = $validator->fetchErrors();
            if ($hasErrors == false) {
                $result = $this->model->updateCoveringNote($_POST['covering_id'], $_POST['template_name'], $_POST['body'], $_POST['subject'], $_POST['invoice_label'], $_POST['pdf_enable'], $user_id);
                $this->session->set('successMessage', 'Covering details have been saved.');
                header("Location:/merchant/coveringnote/viewlist");
            } else {
                $this->smarty->assign("haserrors", $hasErrors);
                $this->smarty->assign("post", $_POST);
                $this->update($this->encrypt->encode($_POST['covering_id']));
            }
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E293]Error while updating covering note Error: ' . $e->getMessage());
            header("Location:/error");
        }
    }

}
