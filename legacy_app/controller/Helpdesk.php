<?php

use App\Jobs\SupportTeamNotification;

class Helpdesk extends Controller
{

    function __construct()
    {
        parent::__construct();
    }

    function index()
    {
        $this->view->canonical = $_GET['url'];
        if ($this->session->get('logged_in') == TRUE) {
            $this->view->isloggedin = TRUE;
        } else {
            $this->view->isloggedin = FALSE;
        }
        $this->view->canonical = $_GET['url'];
        $this->view->title = 'Helpdesk | Customer Support| Talk to us | Swipez';
        $this->view->description = 'Swipez solves for Customer Database management, invoicing, payments and reconciliation. Our support team will get you up and running quickly and solve any queries.';

        // $this->view->render('helpdesk/index');

        $this->view->render('header/guest');
        $this->smarty->display(VIEW . 'helpdesk/index.tpl');
        $this->view->render('footer/nonfooter');
    }

    function send()
    {
        try {
            if (empty($_POST)) {
                header("Location:/helpdesk");
            }
            require CONTROLLER . 'Profilevalidator.php';
            $validator = new Profilevalidator($this->model);
            if ($this->session->get('logged_in') == TRUE) {
                $isloggedin = TRUE;
                $validator->validatehelpdeskwithlogin();
                $user_name = $this->session->get('user_name');
                $email = $this->session->get('email_id');
            } else {
                $isloggedin = FALSE;
                $validator->validatehelpdesknonlogin();
                $user_name = $_POST['name'];
                $email = $_POST['email'];
            }
            $hasErrors = $validator->fetchErrors();

            if ($hasErrors == false) {
                $this->sendMail($user_name, $email, $_POST['subject'], $_POST['message'], $isloggedin);
                $this->view->render('helpdesk/success');
            } else {
                $this->smarty->assign("errors", $hasErrors);
                $this->index();
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E059]Error while saving helpdesk Error: ' . $e->getMessage());
        }
    }

    function contactus($link, $subject = null)
    {
        $cloud_front = getenv('CLOUD_FRONT');
        $this->smarty->assign("cloud_front", $cloud_front);
        $this->smarty->assign("post_url", $this->host . '');
        if ($link == 'swipez') {
            $this->smarty->assign("subject", $subject);
            $this->smarty->display(VIEW . 'helpdesk/contactus_swipez.tpl');
        } else if ($link == 'partner') {
            $this->smarty->assign("subject", $subject);
            $this->smarty->display(VIEW . 'helpdesk/contactus_partner.tpl');
        } else {
            $merchant_id = $this->encrypt->decode($link);
            $this->smarty->assign("link", $link);
            $this->smarty->display(VIEW . 'helpdesk/contactus.tpl');
        }
    }

    function sendenquiry()
    {
        $result = $this->validateCaptcha($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
        if ($result) {
        } else {
            $hasErrors = "Invalid captcha please click on captcha box";
        }
        $link = $_POST['link'];
        if ($hasErrors == FALSE) {
            if ($link == 'swipez') {
                $row['business_email'] = 'support@swipez.in';
            } else {
                $merchant_id = $this->encrypt->decode($link);
                $row = $this->common->getMerchantProfile($merchant_id);
            }
            $this->sendEnquiryMail($_POST, $row['business_email']);
            $this->smarty->assign("success", 'Thank you for getting in touch with us. Our team will get in touch with you at the earliest');
            $this->contactus($link);
        } else {
            $this->smarty->assign("haserrors", $hasErrors);
            $this->smarty->assign("post", $_POST);
            $this->contactus($link);
        }
    }

    function partner()
    {
        if (isset($_POST['type']) && $_POST['type'] != '') {
            if (isset($_POST['g-recaptcha-response'])) {
                $result = $this->validateCaptcha($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
                if ($result) {
                } else {
                    $hasErrors = "Invalid captcha please click on captcha box";
                    $this->smarty->assign("haserrors", $hasErrors);
                    $this->smarty->assign("post", $_POST);
                }
            } else {
                $hasErrors = "Invalid captcha please click on captcha box";
                $this->smarty->assign("haserrors", $hasErrors);
                $this->smarty->assign("post", $_POST);
            }

            if ($hasErrors == FALSE) {
                $subject = "Partner request";
                $body_message = "Company name: " . $_POST['company_name'] . " <br>Company type: " . $_POST['type'] . "<br>" . "Name: " . $_POST['name'] . "<br>" . "Email Id: " . $_POST['email'] . "<br>" . "Mobile no: " . $_POST['mobile'] . "<br>" . "Postal address: " . $_POST['address'] . "<br>" . "Website: " . $_POST['website'] . "<br>" . "Brief Description about Company: " . $_POST['description'] . "<br>" . "Team size: " . $_POST['team_size'] . "<br>" . "Representing other products: " . $_POST['other_product'];
                SupportTeamNotification::dispatch($subject, $body_message, 'SUPPORT')->onQueue(env('SQS_MERCHANT_REGISTRATION_NOTIFICATION_QUEUE'));
                $this->smarty->assign("success", 'Thank you for getting in touch with us. Our team will get in touch with you at the earliest');
                $this->contactus('partner');
            } else {
                $this->contactus('partner');
            }
        }
    }

    public function sendMail($user_name, $user_email, $subject, $user_message)
    {
        try {
            #($toEmail_, $toName_, $subject_, $messageHTML_, $messageText_ = NULL)
            $body_message = "Name: " . $user_name . " <br>Email: " . $user_email . "<br>" . "Message: <br>" . $user_message;
            SupportTeamNotification::dispatch($subject, $body_message, 'SUPPORT')->onQueue(env('SQS_MERCHANT_REGISTRATION_NOTIFICATION_QUEUE'));
        } catch (Exception $e) {
            Sentry\captureException($e);
            SwipezLogger::error(__CLASS__, '[E174]Error while sending mail Error:  for user email [' . $user_email . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function sendEnquiryMail($post, $toEmail_)
    {
        try {
            $emailWrapper = new EmailWrapper();
            #($toEmail_, $toName_, $subject_, $messageHTML_, $messageText_ = NULL)
            $subject = 'Customer Enquiry';
            $user_email = $post['email'];
            if (isset($post['subject'])) {
                $body_message = "Subject: " . $post['subject'] . "<br>";
            }
            $body_message .= "Name: " . $post['name'] . " <br>Email: " . $post['email'] . " <br>Mobile: " . $post['mobile'] . "<br>" . "Message: " . $post['message'];
            $emailWrapper->sendMail($toEmail_, "", $subject, $body_message, $body_message, $user_email);
        } catch (Exception $e) {
            Sentry\captureException($e);
            SwipezLogger::error(__CLASS__, '[E174]Error while sending mail Error:  for user email [' . $toEmail_ . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }
}
