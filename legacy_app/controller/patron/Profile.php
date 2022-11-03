<?php

use App\Jobs\SupportTeamNotification;

/**
 * Profile controller class to handle profile for patron
 */
class Profile extends Controller
{

    function __construct()
    {
        parent::__construct();

        //TODO : Check if using static function is causing any problems!
        $this->validateSession('patron');
        $this->view->selectedMenu = 'profile';
        //$this->view->js = array('dashboard/js/default.js');
        $this->view->disable_talk = 1;
    }

    /**
     * Display patron profile
     */
    function index()
    {
        try {
            $user_id = $this->session->get('userid');
            $personalDetails = $this->model->getPersonalDetails($user_id);
            if (!empty($personalDetails)) {
                $this->smarty->assign("details", $personalDetails);
            } else {
                SwipezLogger::error(__CLASS__, '[E047]Error while update patron profile fetching personal details. user id ' . $user_id);
                $this->setGenericError();
            }
            $this->view->title = 'Patron profile';
            $this->view->header_file = ['profile'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'patron/profile/profile.tpl');
            $this->view->render('footer/profile');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E048]Error while patron profile initiate Error: for user id [' . $user_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    /**
     * Update patron profile
     */
    function update()
    {
        try {
            $user_id = $this->session->get('userid');
            if (empty($_POST)) {
                header('Location:/patron/profile');
            }
            $result = $this->model->pesonalUpdate($user_id, $_POST['f_name'], $_POST['l_name'], $_POST['mobile']);
            $this->session->set('display_name', ucfirst($_POST['f_name']));
            $this->smarty->assign("success", ' Updates made to your profile have been saved.');
            $this->index();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E049]Error while updating profile Error: for user id [' . $user_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    /**
     * Suggest new merchant
     */
    function suggest()
    {
        try {
            $user_id = $this->session->get('userid');
            $this->view->selectedMenu = array(120);
            $this->view->patron_has_pay_req = $this->session->get('patron_has_payreq');
            $this->view->title = 'Suggest a Merchant';
            $this->smarty->assign('title',$this->view->title);

            //Breadcumbs array start
            $breadcumbs_array = array(
                array('title'=> $this->view->title, 'url'=>'')
            );
            $this->smarty->assign("links", $breadcumbs_array);
            //Breadcumbs array end

            $this->view->header_file = ['profile'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'patron/profile/suggest.tpl');
            $this->view->render('footer/profile');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E050]Error while suggesting merchant initiate Error: for user id [' . $user_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    /**
     * Send merchant suggestion mail to swipez admin 
     */
    function send()
    {
        try {
            $user_id = $this->session->get('userid');
            if (empty($_POST)) {
                header("Location:/patron/profile/suggest");
            }
            require CONTROLLER . 'Profilevalidator.php';
            $validator = new Profilevalidator($this->model);
            $validator->validatesuggestmerchant();
            $email = $this->session->get('email_id');
            $hasErrors = $validator->fetchErrors();
            $this->view->selectedMenu = 'suggest';
            if ($hasErrors == false) {
                $this->sendMail($email, $_POST['name'], $_POST['email'], $_POST['contact_no'], $_POST['business_nature']);
                $this->smarty->assign("success", 'TRUE');
            } else {
                $this->smarty->assign("errors", $hasErrors);
            }
            $this->suggest();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E051]Error while saving suggest merchant Error: for user id [' . $user_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function sendMail($user_email, $merchant_name, $merchant_email, $merchant_contact, $business_nature)
    {
        try {
            $subject = "Merchant suggestion - " . $user_email;
            $body_message = "Merchant name: " . $merchant_name . " <br>" . "Merchant email: " . $merchant_email . " <br>" . "Merchant contact: " . $merchant_contact . " <br>" . "Nature of business: " . $business_nature;
            SupportTeamNotification::dispatch($subject, $body_message, 'SUPPORT')->onQueue(env('SQS_MERCHANT_REGISTRATION_NOTIFICATION_QUEUE'));
        } catch (Exception $e) {
            Sentry\captureException($e);
            SwipezLogger::error(__CLASS__, '[E164]Error while sending mail Error: for user email[' . $user_email . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }
}
