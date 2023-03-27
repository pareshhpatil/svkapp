<?php

class Profile extends Controller
{

    function __construct()
    {
        parent::__construct();
        $group_id = $this->session->get('group_type');
        if ($group_id == 2) {
            $this->user_id = $this->session->get('sub_user_id');
        } else {
            $this->user_id = $this->session->get('userid');
        }
    }

    function reset()
    {
        try {
            $this->view->title = 'Password reset';
            $this->smarty->assign('title', $this->view->title);
            $breadcumbs_array = array(
                array('title' => 'Settings', 'url' => '/merchant/profile/settings'),
                array('title' => 'Personal Preferences', 'url' => ''),
                array('title' => $this->view->title, 'url' => '')
            );
            $this->smarty->assign("links", $breadcumbs_array);

            $this->validateSession('patron');
            $this->view->header_file = ['profile'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'profile/reset.tpl');
            $this->view->render('footer/footer');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E083]Error while reset password initiate Error:  for user id [' . $this->user_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function savepreferences()
    {
        try {
            $user_id = $this->user_id;
            $sms = isset($_POST['sms']) ? 1 : 0;
            $email = isset($_POST['email']) ? 1 : 0;
            $push = isset($_POST['push']) ? 1 : 0;
            $result = $this->model->updatepreferences($this->user_id, $sms, $email, $push);
            header('Location: /profile/preferencesaved');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E084]Error while preference save Error:  for user id [' . $user_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function preferences()
    {
        try {
            $user_id = $this->user_id;
            $result = $this->model->getpreferences($user_id);

            if (empty($result)) {
                SwipezLogger::error(__CLASS__, '[E085]Error while fetching user preferences. user id  ' . $user_id);
                $this->setGenericError();
            } else {
                $smschecked = '';
                $emailchecked = '';
                $pushchecked = '';
                if ($result['send_sms'] == '1') {
                    $smschecked = 'checked';
                }
                if ($result['send_email'] == '1') {
                    $emailchecked = 'checked';
                }

                if ($result['send_push'] == '1') {
                    $pushchecked = 'checked';
                }

                $this->validateSession('merchant');
                $this->smarty->assign("sms", $smschecked);
                $this->smarty->assign("email", $emailchecked);
                $this->smarty->assign("push", $pushchecked);

                $this->smarty->assign("user_type", $this->view->usertype);
                //$this->view->title = ucfirst($this->view->usertype) . ' preferences';
                $this->view->title = "Notification preferences";
                $this->smarty->assign('title', 'Notification preferences');

                $breadcumbs_array = array(
                    array('title' => 'Settings', 'url' => '/merchant/profile/settings'),
                    array('title' => 'Personal Preferences', 'url' => ''),
                    array('title' => $this->view->title, 'url' => '')
                );
                $this->smarty->assign("links", $breadcumbs_array);
                $this->view->header_file = ['profile'];
                $this->view->render('header/app');
                $this->smarty->display(VIEW . 'profile/preferences.tpl');
                $this->view->render('footer/profile');
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E086]Error while preferences initiate Error:  for user id [' . $user_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function passwordsaved()
    {
        try {
            $user_id = $this->user_id;
            $message = 'Your password has been reset. Please remember to use your new password the next time you login into Swipez.';
            $this->view->title = 'Password reset successful';
            $this->view->header_file = ['profile'];
            $this->view->render('header/app');
            $this->smarty->assign("title", "Password reset successful");
            $this->smarty->assign("message", $message);
            $this->smarty->assign("user_type", $this->view->usertype);
            $this->smarty->display(VIEW . 'profile/success.tpl');
            $this->view->render('footer/profile');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E087]Error while reset password save Error:  for user id [' . $user_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function preferencesaved()
    {
        try {
            $user_id = $this->user_id;
            $message = 'Your preferences has been stored successfully.';
            $this->view->header_file = ['profile'];
            $this->view->render('header/app');
            $this->smarty->assign("message", $message);
            $this->smarty->assign("title", "Preference set successful");
            $this->smarty->assign("user_type", $this->view->usertype);
            $this->smarty->display(VIEW . 'profile/success.tpl');
            $this->view->render('footer/profile');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E088]Error while preferences save Error:  for user id [' . $user_id . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function resetpassword()
    {
        try {
            if (empty($_POST)) {
                header('Location: /profile/reset');
            }
            $user_id = $this->user_id;
            require CONTROLLER . 'Profilevalidator.php';
            $validator = new Profilevalidator($this->model);
            $validator->validateResetPassword($user_id);
            $hasErrors = $validator->fetchErrors();
            if ($hasErrors == false) {
                if (strlen($user_id) == 10 && substr($user_id, 0, 1) == 'U') {
                    $result = $this->model->resetPassword($_POST['password'], $user_id);
                    if (isset($result['email_id']) && isset($result['mobile_no'])) {
                        $mailid = $result['email_id'];
                        SwipezLogger::info(__CLASS__, "Sending email to : " . $mailid);
                        $this->model->sendMail($mailid, $this->session->get('user_name'));
                        //sending sms
                        $mobileNo = $result['mobile_no'];
                        $message = $this->model->fetchMessage('p6');
                        $this->model->sendSMS($this->user_id, $message, $mobileNo);
                        header('Location: /profile/passwordsaved');
                    } else {
                        SwipezLogger::error(__CLASS__, "[E089]Error while resetting password for user email id and mobile number not found from db for : " . $userid);
                        $errormessage = "There was an error while resetting your password. Please contact " . '<a href="' . $this->view->server_name . '/helpdesk" class="example5"> contact us</a>' . "  for help";
                        $this->setError("Password reset failed", $errormessage);
                        header('Location:/error');
                    }
                } else {
                }
            } else {
                $this->smarty->assign("errors", $hasErrors);
                $this->reset();
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E090]Error while reset password Error:  for user id [' . $user_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }


    public function gst()
    {
        if ($_POST['gst_number']) {
            $this->validatev3Captcha();
            $iris_data = $this->common->getListValue('config', 'config_type', 'IRIS_GST_DATA');
            require_once UTIL . 'IRISAPI.php';
            $iris = new IRISAPI($iris_data);
            $GSTInfo = $iris->getGSTInfo($_POST['gst_number']);
            $info = $GSTInfo['response'];
            $array = array('01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'May', '06' => 'Jun', '07' => 'Jul', '08' => 'Aug', '09' => 'Sep', '10' => 'Oct', '11' => 'Nov', '12' => 'Dec');
            $this->smarty->assign("datearray", $array);
    
            if ($info['status_code'] == 0) {
                $this->smarty->assign("error", $info['error']['message']);
            } else {
                $returnstatus = $iris->getGSTReturnStatus($_POST['gst_number']);
                $return_list = $returnstatus['response']['data']['EFiledlist'];
                foreach ($return_list as $row) {
                    if ($row['rtntype'] == 'GSTR3B') {
                        $lastfileperiod = $row['ret_prd'];
                        break;
                    }
                }

                $day = date('d');
                if ($day > 23) {
                    $fperiod = date("mY", strtotime("-1 months"));
                } else {
                    $fperiod = date("mY", strtotime("-2 months"));
                }
                if (strtotime($lastfileperiod) < strtotime($fperiod)) {
                    if ($lastfileperiod != '') {
                        $lastfileperiod = substr($lastfileperiod, 2) . '-' . substr($lastfileperiod, 0, 2) . '-01';
                        $fperiod = substr($fperiod, 2) . '-' . substr($fperiod, 0, 2) . '-01';
                        $datetime1 = new DateTime($lastfileperiod);
                        $datetime2 = new DateTime($fperiod);
                        $interval = $datetime1->diff($datetime2);
                        $month = $interval->format('%m');
                        if ($month > 1) {
                            $mtext = ' Months';
                        } else {
                            $mtext = ' Month';
                        }
                        $filereturn_error = 'Tax return not filed for ' . $month . $mtext;
                        $this->smarty->assign("filereturn_error", $filereturn_error);
                    } else {
                        $filereturn_error = 'Tax return never filed';
                        $this->smarty->assign("filereturn_error", $filereturn_error);
                    }
                } else {
                    $filereturn_success = 'Tax Return filing upto date';
                    $this->smarty->assign("filereturn_success", $filereturn_success);
                }
                $info['nature'] = implode(',', $info['nature']);
                $this->smarty->assign("det", $info);
                $this->smarty->assign("return_list", $return_list);
            }
            $this->smarty->assign("gst_number", $_POST['gst_number']);
        }
        $this->view->datatablejs = 'table-no-export-class';
        $this->view->title = 'Filing status';
        $this->smarty->assign('title', $this->view->title);

        //Breadcumbs array start
        $breadcumbs_array = array(
            array('title' => 'GST', 'url' => ''),
            array('title' => 'Filling status', 'url' => '')
        );
        $this->smarty->assign("links", $breadcumbs_array);
        //Breadcumbs array end
        $this->view->v3captcha = true;
        $this->view->hide_first_col = true;
        $V3_CAPTCHA_CLIENT_ID = env('V3_CAPTCHA_CLIENT_ID');
        $this->smarty->assign("V3_CAPTCHA_CLIENT_ID", $V3_CAPTCHA_CLIENT_ID);
        $this->smarty->display(VIEW . 'profile/gst.tpl');
        $this->view->render('footer/merchantwebsite');
    }
}
