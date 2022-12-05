<?php

/**
 * Supplier controller class to handle Merchants supplier
 */
class Subuser extends Controller {

    function __construct() {
        parent::__construct();
        $this->validateSession('merchant');
        
    }

    /**
     * Display merchant suppliers list
     */
    function viewlist() {
        try {

            $list = $this->model->getSubuserList($this->session->get('userid'));
            $int = 0;
            foreach ($list as $item) {
                $list[$int]['encrypted_id'] = $this->encrypt->encode($item['user_id']);
                $int++;
            }
            $this->view->selectedMenu = array(14, 65);
            $this->smarty->assign("list", $list);
            $this->smarty->assign("title", "Sub merchant list");
            $this->view->title = "Submerchant list";

            $breadcumbs_array = array(
                array('title' => 'Settings','url' => '/merchant/profile/settings'),
                array('title' => 'Manage users','url' => ''),
                array('title'=> $this->view->title, 'url'=>'')
                );
            $this->smarty->assign("links", $breadcumbs_array);

            $this->view->datatablejs = 'table-no-export';
            $this->view->header_file = ['list'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/subuser/list.tpl');
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
        $roles = $this->model->getRoleList($this->session->get('userid'));
        $customer_group= $this->common->getListValue('customer_group', 'merchant_id', $this->merchant_id,1);
        $this->smarty->assign("customer_group", $customer_group);
        $this->smarty->assign("roles", $roles);
        $controllerlist = $this->model->getControllers();
        $this->smarty->assign("list", $controllerlist);
        $this->view->js = array('setting');
        $this->view->selectedMenu = array(14, 65);
        $this->view->title = 'Create sub-merchant';
        $this->smarty->assign('title',$this->view->title);
        $breadcumbs_array = array(
            array('title' => 'Settings','url' => '/merchant/profile/settings'),
            array('title' => 'Manage users','url' => ''),
            array('title'=> 'Submerchant list', 'url'=>'/merchant/subuser/viewlist'),
            array('title'=> $this->view->title, 'url'=>'')
        );
        $this->smarty->assign("links", $breadcumbs_array);
        $this->view->header_file = ['list'];
        $this->view->render('header/app');
        $this->smarty->display(VIEW . 'merchant/subuser/create.tpl');
        $this->view->render('footer/list');
    }

    function roles() {
        try {

            $list = $this->model->getRoleList($this->session->get('userid'));
            $int = 0;
            foreach ($list as $item) {
                $list[$int]['functions'] = $this->model->getControllersName($item['view_controllers']);
                $list[$int]['encrypted_id'] = $this->encrypt->encode($item['role_id']);
                $int++;
            }
            $this->view->selectedMenu = array(14, 64);
            $this->smarty->assign("list", $list);
            $this->smarty->assign("title", "Role list");
            $this->view->title = "Role list";

            //Breadcumbs array start
            $breadcumbs_array = array(
                array('title' => 'Settings','url' => '/merchant/profile/settings'),
                array('title' => 'Manage users','url' => ''),
                array('title'=> $this->view->title, 'url'=>'')
                );
            $this->smarty->assign("links", $breadcumbs_array);
            //Breadcumbs array end

            $this->view->datatablejs = 'table-no-export';
            $this->view->header_file = ['list'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/subuser/rolelist.tpl');
            $this->view->render('footer/list');
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E999]Error while listing suppliers Error: for user id [' . $this->session->get('userid') . '] ' . $e->getMessage());
        }
    }

    function createrole() {

        $controllerlist = $this->model->getControllers();
        $this->view->selectedMenu = array(14, 64);
        $this->smarty->assign("list", $controllerlist);
        $this->view->title = 'Create role';
        $this->smarty->assign('title', $this->view->title);
        //Breadcumbs array start
        $breadcumbs_array = array(
            array('title' => 'Settings','url' => '/merchant/profile/settings'),
            array('title' => 'Manage users','url' => ''),
            array('title'=> $this->view->title, 'url'=>'')
            );
        $this->smarty->assign("links", $breadcumbs_array);
        //Breadcumbs array end

        $this->view->header_file = ['list'];
        $this->view->render('header/app');
        $this->smarty->display(VIEW . 'merchant/subuser/createrole.tpl');
        $this->view->render('footer/list');
    }

    function updaterole($link) {
        $role_id = $this->encrypt->decode($link);
        if (!is_numeric($role_id)) {
            $this->setInvalidLinkError();
        }
        $details = $this->model->getRoleDeatails($role_id);
        if ($details['merchant_id'] != $this->merchant_id) {
            $this->setInvalidLinkError();
        }
        $view_roles = explode(',', $details['view_controllers']);
        $update_roles = explode(',', $details['update_controllers']);
        $delete_roles = explode(',', $details['delete_controllers']);
        $controllerlist = $this->model->getControllers();
        $int = 0;
        foreach ($controllerlist as $items) {
            if (in_array($items['controller_id'], $view_roles)) {
                $controllerlist[$int]['view']['checked'] = 'checked';
            } else {
                $controllerlist[$int]['view']['checked'] = '';
            }

            if (in_array($items['controller_id'], $update_roles)) {
                $controllerlist[$int]['edit']['checked'] = 'checked';
            } else {
                $controllerlist[$int]['edit']['checked'] = '';
            }
            if (in_array($items['controller_id'], $delete_roles)) {
                $controllerlist[$int]['delete']['checked'] = 'checked';
            } else {
                $controllerlist[$int]['delete']['checked'] = '';
            }
            $int++;
        }
        $this->view->selectedMenu = array(14, 64);
        $this->smarty->assign("list", $controllerlist);
        $this->smarty->assign("details", $details);
        $this->smarty->assign("role_id", $link);
        $this->view->title = 'Update role';

        $this->smarty->assign('title', $this->view->title);
        //Breadcumbs array start
        $breadcumbs_array = array(
            array('title' => 'Settings','url' => '/merchant/profile/settings'),
            array('title' => 'Manage users','url' => ''),
            array('title' => 'Role list', 'url'=>'/merchant/subuser/roles'),
            array('title'=> $details['name'], 'url'=>'')
            );
        $this->smarty->assign("links", $breadcumbs_array);
        //Breadcumbs array end

        $this->view->header_file = ['list'];
        $this->view->render('header/app');
        $this->smarty->display(VIEW . 'merchant/subuser/updaterole.tpl');
        $this->view->render('footer/list');
    }

    /**
     * Save new role 
     */
    function saverole($is_popup = null) {
        try {
            if (empty($_POST)) {
                header("Location:/merchant/subuser/createrole");
            }
            require_once CONTROLLER . 'merchant/Subuservalidator.php';
            $validator = new Subuservalidator($this->model);
            $validator->validateRoleSave($this->merchant_id);
            $hasErrors = $validator->fetchErrors();
            if ($hasErrors == false) {
                $id = $this->model->saveRole($this->user_id, $this->merchant_id, $_POST['role_name'], $_POST['view'], $_POST['edit'],$_POST['delete']);
                if ($is_popup == NULL) {
                    $this->session->set('successMessage', ' New role has been saved.');
                    header("Location:/merchant/subuser/roles");
                } else {
                    $role['name'] = $_POST['role_name'];
                    $role['id'] = $id;
                    $role['status'] = 1;
                    echo json_encode($role);
                }
            } else {
                if ($is_popup == NULL) {
                    $this->smarty->assign("haserrors", $hasErrors);
                    $this->smarty->assign("post", $_POST);
                    $this->createrole();
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

    function roleupdatesaved() {
        try {
            $role_id = $this->encrypt->decode($_POST['role_id']);

            if (empty($_POST)) {
                header("Location:/merchant/subuser/roles");
            }

            $this->model->updateRole($role_id, $_POST['role_name'], $_POST['view'], $_POST['edit'],$_POST['delete']);
            $this->session->set('successMessage', 'Update role successfully.');
            header("Location:/merchant/subuser/roles");
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E289]Error while creating supplier Error: ' . $e->getMessage());
            header("Location:/error");
        }
    }

    /**
     * Save new sub merchant 
     */
    function saved() {
        try {
            $user_id = $this->session->get('userid');
            if (empty($_POST)) {
                header("Location:/merchant/subuser/create");
            }
            require_once CONTROLLER . 'merchant/Subuservalidator.php';
            $validator = new Subuservalidator($this->model);
            $validator->validateSubmerchantSave($user_id);
            $hasErrors = $validator->fetchErrors();
            if ($hasErrors == false) {
                $_POST['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $result = $this->model->savesubMerchant($user_id, $_POST['first_name'], $_POST['last_name'], $_POST['email'], $_POST['mobile'], $_POST['mob_country_code'], $_POST['password'], $_POST['role'],0, $_POST['group']);
                if ($result['message'] == 'success') {
                    $this->sendMail($result['usertimestamp'], $_POST['email']);
                }
                $this->session->set('successMessage', 'New sub merchant has been saved.');
                header("Location:/merchant/subuser/viewlist");
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

    public function sendMail($concatStr_, $toEmail_) {
        try {
            $encoded = $this->encrypt->encode($concatStr_);
            $verifyemailurl = $this->app_url . '/merchant/register/verifyemail/' . $encoded . '';

            $emailWrapper = new EmailWrapper();
            $mailcontents = $emailWrapper->fetchMailBody("user.verifyemail");

            if (isset($mailcontents[0]) && isset($mailcontents[1])) {
                $message = $mailcontents[0];
                $message = str_replace('__EMAILID__', $toEmail_, $message);
                $message = str_replace('__LINK__', $verifyemailurl, $message);
                $message = str_replace('__BASEURL__', $this->app_url, $message);

                $emailWrapper->sendMail($toEmail_, "", $mailcontents[1], $message);
            } else {
                SwipezLogger::warn("Mail could not be sent with verify email link to : " . $toEmail_);
            }
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E166]Error while sending mail Error: email link to : ' . $toEmail_ . $e->getMessage());
        }
    }

    /**
     * Delete merchant supplier
     */
    function delete($user_id) {
        try {
            $user_id = $this->encrypt->decode($user_id);
            if (strlen($user_id) != 10) {
                $this->setInvalidLinkError();
            }
            $this->common->updateUserStatus(21, $user_id);
            $this->session->set('successMessage', ' Sub merchant has been deleted.');
            header("Location:/merchant/subuser/viewlist");
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E290]Error while deleting supplier Error: ' . $e->getMessage());
            header("Location:/error");
        }
    }

    /**
     * Delete merchant supplier
     */
    function deleterole($role_id) {
        try {
            $user_id = $this->session->get('userid');
            $role_id = $this->encrypt->decode($role_id);
            $result = $this->model->deleterole($role_id, $user_id);
            if ($result == true) {
                $this->session->set('successMessage', ' Role has been deleted.');
                header("Location:/merchant/subuser/roles");
            }
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E290]Error while deleting supplier Error: ' . $e->getMessage());
            header("Location:/error");
        }
    }

}

?>
