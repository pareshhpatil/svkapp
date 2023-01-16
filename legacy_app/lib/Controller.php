<?php
use Illuminate\Support\Facades\Redis;
class Controller
{

    protected $session = NULL;
    protected $encrypt = NULL;
    protected $env = NULL;
    protected $user_id = NULL;
    protected $merchant_id = NULL;
    protected $cache = NULL;
    protected $generic = NULL;
    protected $common = NULL;
    protected $gloss_event_id = NULL;
    public $app_url = NULL;

    function __construct()
    {
        $this->encrypt = new Encryption();
        $this->filterPost();
        $this->filterGet();
        $this->view = new View();
        $this->smarty = new Smarty();
        $this->common = new CommonModel();
        $env = getenv('ENV');
        $this->host = ($env == 'LOCAL') ? 'http' : 'https';
        $this->view->env = $env;
        $this->env = $env;
        $this->HTMLValidationPrinter();
        $this->setWebProperties();
        $this->generic = new Generic();
        $this->smarty->setCompileDir(SMARTY_FOLDER);
        $this->smarty->assign("server_name", $this->view->server_name);
    }

    /**
     * 
     * @param string $name Name of the model
     * @param string $path Location of the models
     */
    public function loadModel($name, $modelPath = 'model/')
    {

        $path = $modelPath . $name . 'Model.php';

        if (file_exists($path)) {
            require_once $modelPath . $name . 'Model.php';
            $modelName = $name . 'Model';
            $this->model = new $modelName();
        }
    }

    public function renderHeader()
    {
        if ($this->session->get('logged_in') == TRUE) {
            $this->view->usertype = ($this->session->get('merchant_id') == true) ? 'merchant' : 'patron';
            $merchant_type = $this->session->get('merchant_type');
            $this->view->showWhygopaid = ($merchant_type == 1) ? TRUE : FALSE;
            $this->view->display_name = $this->session->get('display_name');
            $this->view->userName = $this->session->get('user_name');
            $this->user_id = $this->session->get('userid');
            $this->merchant_id = $this->session->get('merchant_id');
            if ($this->session->get('show_hotjar') == 1) {
                //$this->view->show_hotjar = 1;
            }
            if ($this->session->get('help_hero_popup') == 1) {
                $this->view->help_hero = 1;
                $this->view->merchant_id = $this->merchant_id;
                $this->view->created_date = $this->session->get('created_date');
            }
        }

        $successMessage = $this->session->get('successMessage');
        if ($successMessage != false) {
            $this->view->successMessage = $successMessage;
            $this->smarty->assign("success", ' ' . $successMessage);
            $this->session->remove('successMessage');
        }
        $errorMessage = $this->session->get('errorMessage');
        if ($errorMessage != false) {
            $this->view->errorMessage = $errorMessage;
            $this->smarty->assign("error", ' ' . $errorMessage);
            $this->session->remove('errorMessage');
        }
    }

    /*
     * Validate csrf token for post request
     */

    public function handlecsrf($url)
    {
        if (!empty($_POST)) {
            $result = CSRF::validate($url);
            if ($result != true) {
                $this->session->set('return_url', $_SERVER['HTTP_REFERER']);
                $this->setError('Retry your request', 'Please retry your request by using the below button', true);
            }
        }
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
    }

    public function validateSession($userType, $service_id = null)
    {
        if ($userType == 'merchant') {
            if ($this->session->get('user_status') < ACTIVE_MERCHANT) {
                header('location: /login');
                exit;
            } else {
                if ($this->session->get('merchant_id') == '') {
                    header('location: /patron/dashboard');
                    exit;
                }
                if ($this->session->get('display_name') != '') {
                    $this->view->display_name = $this->session->get('display_name') . ' - ' . $this->session->get('company_name');
                } else {
                    $this->view->display_name = $this->session->get('company_name');
                }
                $this->view->company_name = $this->session->get('company_name');
                $this->view->display_url = $this->session->get('merchant_display_url');
                $this->view->xway_enable = $this->session->get('xway_enable');
                $this->view->has_formbuilder = $this->session->get('has_formbuilder');
                $this->view->gst_enable = $this->session->get('gst_enable');
                $this->view->cable_enable = $this->session->get('cable_enable');
                $this->view->coupon_enabled = $this->session->get('coupon_enabled');
                $this->view->loyalty_enable = $this->session->get('loyalty_enable');
                $this->view->ticket_enable = $this->session->get('ticket_enable');
                $this->view->transfer_enable = $this->session->get('transfer_enable');
                $this->view->vendor_enable = $this->session->get('vendor_enable');
                $this->view->site_builder = $this->session->get('site_builder');
                $this->view->booking_calendar = $this->session->get('booking_calendar');
                $this->view->promotions = $this->session->get('is_promotions');
                $this->view->franchise = $this->session->get('has_franchise');
                $this->user_id = $this->session->get('userid');
                $this->view->merchant_id = $this->session->get('merchant_id');
                $this->view->talk_first_name = $this->session->get('display_name');
                $this->view->talk_last_name = $this->session->get('last_name');
                $this->view->talk_email_id = $this->session->get('email_id');
                $this->view->talk_mobile = $this->session->get('mobile');
                $this->view->account_type = $this->session->get('account_type');
                $this->view->service_id = $this->session->get('service_id');
                $this->view->active_service_list = $this->session->get('active_service_list');
                //dd($this->view->active_service_list);
                if ($this->session->get('has_master_login') == 1) {
                    $this->view->master_login_list = $this->session->get('master_login_group');
                    $this->view->has_master_login = 1;
                }

                //$this->setUploadDoc();

                if ($service_id != null) {
                    $merchant_services = $this->session->get('merchant_services');
                    if (!empty($merchant_services)) {
                        if (!isset($merchant_services[$service_id])) {
                            $this->session->set('return_url', '/merchant/dashboard/home');
                            $this->session->set('button_text', 'Activate now');
                            if ($service_id == 4) {
                                $this->session->set('errorTitle', 'Activate event pages');
                                $this->setError('Activate event pages', 'Start creating event pages and collect event booking payments online.');
                            } elseif ($service_id == 5) {
                                $this->session->set('errorTitle', 'Activate venue booking');
                                $this->setError('Activate venue booking', 'Start creating booking calendars and collect booking payments online.');
                            } else {
                                $this->session->set('errorTitle', 'Activate service');
                                $this->setError('Activate service', 'Activate service in your account to start using this service.');
                            }
                            header("Location:/error");
                            exit;
                        }
                    }
                }
            }
        } else {
            if ($this->session->get('userid') == false) {
                header('location: /login');
                exit;
            } else {
                $this->view->account_type = $this->session->get('account_type');
                if ($this->session->get('company_name') != '') {
                    $this->view->display_name = $this->session->get('display_name') . ' - ' . $this->session->get('company_name');
                } else {
                    $this->view->display_name = $this->session->get('user_name');
                }
                $this->user_id = $this->session->get('userid');
                $this->view->talk_user_name = $this->session->get('user_name');
                $this->view->talk_email_id = $this->session->get('email_id');
                $this->view->active_service_list = $this->session->get('active_service_list');
                $this->view->company_name = $this->session->get('company_name');
            }
        }
    }

    function setUploadDoc()
    {
        $flag = $this->session->get('document_upload');
        if (isset($flag)) {
        } else {
            $row = $this->common->getSingleValue('merchant_setting', 'merchant_id', $this->merchant_id);
            if ($row['disable_cashfree_doc'] == 0 && date("Y-m-d H:i:s", strtotime('-24 hours')) > $row['reminder_date']) {
                $this->session->set('document_upload', true);
                $flag = true;
            } else {
                $this->session->set('document_upload', false);
            }
        }
        if ($flag == true) {
            $this->redirectDocument();
        }
    }

    function redirectDocument()
    {
        $array = array('/merchant/dashboard', '/merchant/dashboard/validateuploadform', '/merchant/dashboard/remindmelater', '/merchant/profile/documentsave', '/merchant/chart/paymentreceived/1', '/merchant/chart/billingstatus/1');
        if (!in_array($_SERVER['REQUEST_URI'], $array) && empty($_POST)) {
            header('Location: /merchant/dashboard', 301);
            die();
        }
    }

    public function validateLogin($userType)
    {
        if ($userType == 'merchant') {
            if ($this->session->get('user_status') < ACTIVE_MERCHANT) {
                return FALSE;
            } else {
                return TRUE;
            }
        } else {
            if ($this->session->get('user_status') != ACTIVE_PATRON) {
                return FALSE;
            } else {
                return TRUE;
            }
        }
    }

    public function encryptBackup($link)
    {
        $value = $this->common->getRowValue('value', 'encrypt_backup', 'encrypt_key', $link, 0);
        if ($value == FALSE) {
            $this->setInvalidLinkError();
        } else {
            return $value;
        }
    }

    public function HTMLValidationPrinter()
    {
        require_once(CONTROLLER . "HTMLValidationPrinter.php");
        $HTMLValidatorPrinter = new HTMLValidationPrinter();
        $this->view->HTMLValidatorPrinter = $HTMLValidatorPrinter;
        $this->smarty->assign("validate", $HTMLValidatorPrinter->_messageList);
    }

    public function setError($title, $message, $redirect = false)
    {
        $this->session->set('errorTitle', $title);
        $this->session->set('errorMessage', $message);
        if ($redirect == true) {
            header("Location:/error/index");
            exit;
        }
    }

    public function setGenericError()
    {
        $this->session->set('errorTitle', 'Error');
        $this->session->set('errorMessage', 'Error in Swipez system for this process please try again later.');
        header("Location:/error");
        exit;
    }

    public function setInvalidLinkError()
    {
        $this->session->set('errorTitle', 'Invalid link');
        $this->session->set('errorMessage', 'Invalid link please do not modified encrypted link.');
        header("Location:/error", 404);
        exit;
    }

    public function setPaymentFailedError($transaction_id = null)
    {
        $link = $this->encrypt->encode($transaction_id);
        header('Location:/secure/paymentfailed/' . $link);
        exit;
    }

    public function validatePackage()
    {
        if ($this->session->get('package_expire')) {
            header('Location: /error/packageexpire');
            die();
        }
    }

    public function hasRole($type, $controller, $response = 0, $package = 0)
    {
        $group_type = $this->session->get('group_type');
        if ($group_type == 2) {
            $all_roles = $this->session->get('all_roles');
            if ($all_roles == true) {
                return 1;
            }
            switch ($type) {
                case 1:
                    $role_type = 'view_roles';
                    break;
                case 2:
                    $role_type = 'update_roles';
                    break;
                case 3:
                    $role_type = 'delete_roles';
                    break;
            }
            $roles = $this->session->get($role_type);
            if (in_array($controller, $roles)) {
                return 1;
            }
            if ($response == 0) {
                header("Location:/accessdenied");
                exit;
            } else {
                return 0;
            }
        } else {
            return 1;
        }
    }

    public function filterPost()
    {
        $ignore_array = array('description', 'overview', 'cancellation_policy', 'terms_condition', 'about_us', 'event_tnc', 'body', 'custom_covering', 'tnc', 'exist_cc');
        if (!isset($_POST['site_builder'])) {
            foreach ($_POST as $key => $value) {
                if (is_array($_POST[$key])) {
                    if (!in_array($key, $ignore_array)) {
                        $filterarray = array();
                        foreach ($_POST[$key] as $postarray) {
                            $string = $this->replaceHTMLTags($postarray);
                            $filterarray[] = $this->xss_clean($string);
                        }
                        $_POST[$key] = $filterarray;
                    }
                } else {
                    if (!in_array($key, $ignore_array)) {
                        $string = $this->replaceHTMLTags($_POST[$key]);
                        $_POST[$key] = $string;
                    }
                }
            }
            foreach ($_POST as $key => $value) {
                if (is_array($_POST[$key])) {
                    if (!in_array($key, $ignore_array)) {
                        $filterarray = array();
                        foreach ($_POST[$key] as $postarray) {
                            $string = $this->replaceHTMLTags($postarray);
                            $filterarray[] = strip_tags($string);
                        }
                        $_POST[$key] = $filterarray;
                    }
                } else {
                    if (!in_array($key, $ignore_array)) {
                        $string = $this->replaceHTMLTags($_POST[$key]);
                        $_POST[$key] = strip_tags($string);
                    }
                }
            }
        }

        if (isset($_POST['date_range'])) {
            if (strlen($_POST['date_range']) == 25) {
                $from_date =  substr($_POST['date_range'], 0, 11);
                $to_date =  substr($_POST['date_range'], -11);
                if ($_SESSION["session_date_format"] == 'M d yyyy') {
                    if (preg_match('/^([A-Za-z]){3} ([0-9]){2} ([0-9]){4}?$/', $from_date)) {
                        $_POST['from_date'] = $from_date;
                    }
                    if (preg_match('/^([A-Za-z]){3} ([0-9]){2} ([0-9]){4}?$/', $to_date)) {
                        $_POST['to_date'] = $to_date;
                    }
                } else {
                    if (preg_match('/^([0-9]){2} ([A-Za-z]){3} ([0-9]){4}?$/', $from_date)) {
                        $_POST['from_date'] = $from_date;
                    }
                    if (preg_match('/^([0-9]){2} ([A-Za-z]){3} ([0-9]){4}?$/', $to_date)) {
                        $_POST['to_date'] = $to_date;
                    }
                }
            }
        }

        foreach ($ignore_array as $key) {
            if (isset($_POST[$key])) {
                $_POST[$key] = str_replace(array("'", "~"), "", $_POST[$key]);
            }
        }
    }

    function replaceHTMLTags($value)
    {
        $string = str_replace(array("\r\n", "\r", "\n"), "<br>", $value);
        $string = str_replace("%20", " ", $string);
        return $string;
    }

    public function xss_clean($data)
    {
        // Fix &entity\n;
        $data = str_replace(array('&amp;', '&lt;', '&gt;'), array('&amp;amp;', '&amp;lt;', '&amp;gt;'), $data);
        $data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
        $data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
        $data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');

        // Remove any attribute starting with "on" or xmlns
        $data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);

        // Remove javascript: and vbscript: protocols
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);

        // Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);

        // Remove namespaced elements (we do not need them)
        $data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);

        do {
            // Remove really unwanted tags
            $old_data = $data;
            $data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
        } while ($old_data !== $data);

        // we are done...
        $data = str_replace('">', '', $data);
        $data = str_replace("'>", '', $data);
        $data = str_replace("'", '', $data);
        $data = str_replace('"', '', $data);
        $data = str_replace('onMouseOver=', '', $data);
        return $data;
    }

    public function filterGET()
    {
        foreach ($_GET as $key => $value) {
            if (is_array($_GET[$key])) {
                $filterarray = array();
                foreach ($_GET[$key] as $postarray) {
                    $filterarray[] = strip_tags($postarray);
                }
                $_GET[$key] = $filterarray;
            } else {
                $_GET[$key] = strip_tags($_GET[$key]);
            }
        }
    }

    public function session_expire()
    {
        $loggedin = $this->session->get('loggedin');
        if ($loggedin != false && $this->env == 'PROD') {
            $expireAfter = 5;
            if ($this->session->get('last_action')) {
                $secondsInactive = time() - $this->session->get('last_action');
                $expireAfterSeconds = $expireAfter * 60;
                if ($secondsInactive >= $expireAfterSeconds) {
                    $this->session->destroy();
                    exit;
                }
            }
            $this->session->set('last_action', time());
        }
    }

    public function patronCookie()
    {
        if (BATCH_CONFIG == false) {
            $data = $this->session->getCookie('cookie_user_status');
            if (isset($data)) {
                $this->session->set('user_status', $_COOKIE['cookie_user_status']);
                $this->session->set('user_name', $_COOKIE['cookie_user_name']);
                $this->session->set('display_name', $_COOKIE['cookie_display_name']);
                $this->session->set('email_id', $_COOKIE['cookie_email_id']);
                $this->session->set('logged_in', TRUE);
                $this->session->set('userid', $_COOKIE['cookie_user_id']);
                $this->session->set('patron_has_payreq', $_COOKIE['cookie_patron_has_payreq']);
                $this->renderHeader();
            }
        }
    }

    public function validateCaptcha($response, $remote_addres)
    {
        try {
            $recaptcha = new \ReCaptcha\ReCaptcha($this->capcha_secretkey);
            $resp = $recaptcha->verify($response, $remote_addres);
            if ($resp->isSuccess()) {
                return 'success';
            } else {
                return FALSE;
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E003]Error validate Captcha Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function getLast_date()
    {
        return date('01 M Y');
        /* if (date('d') < 9) {
          $current_date = date('01 M Y');
          $date = strtotime($current_date . ' -1 month');
          $last_date = date('d M Y', $date);
          return $last_date;
          } else {
          return date('01 M Y');
          } */
    }

    public function date_add($days, $sql = 0)
    {
        $current_date = date("d M Y");
        $date = strtotime($current_date . ' ' . $days . ' days');
        if ($sql == 1) {
            return date('Y-m-d', $date);
        } else {
            return date('d M Y', $date);
        }
    }

    function roundAmount($amount, $num)
    {
        $text = number_format($amount, $num);
        $amount = str_replace(',', '', $text);
        return $amount;
    }

    function moneyFormatIndia($num)
    {
        $num = str_replace(',', '', $num);
        $explrestunits = "";
        $numdecimal = "";
        if (substr($num, -3, 1) == '.') {
            $numdecimal = substr($num, -3);
            $num = str_replace($numdecimal, '', $num);
        }
        if (strlen($num) > 3) {
            $lastthree = substr($num, strlen($num) - 3, strlen($num));
            $restunits = substr($num, 0, strlen($num) - 3); // extracts the last three digits
            $restunits = (strlen($restunits) % 2 == 1) ? "0" . $restunits : $restunits; // explodes the remaining digits in 2's formats, adds a zero in the beginning to maintain the 2's grouping.
            $expunit = str_split($restunits, 2);
            for ($i = 0; $i < sizeof($expunit); $i++) {
                // creates each of the 2's group and adds a comma to the end
                if ($i == 0) {
                    $explrestunits .= (int) $expunit[$i] . ","; // if is first value , convert into integer
                } else {
                    $explrestunits .= $expunit[$i] . ",";
                }
            }
            $thecash = $explrestunits . $lastthree;
        } else {
            $thecash = $num;
        }
        return $thecash . $numdecimal; // writes the final format where $currency is the currency symbol.
    }

    public function setLanguage()
    {
        $language = $this->session->get('language');
        if (!isset($language)) {
            $this->session->set('language', 'english');
            $language = 'english';
        }
        $json = '{"hindi":{"menu":{"1":"डैशबोर्ड","update_profile":"प्रोफ़ाइल अपडेट करें","company_profile":"कंपनी प्रोफाइल","package_details":"पैकेज विस्तार","password_reset":"पासवर्ड बदलना","logout":"साइन आउट","2":"संपर्क","15":"ग्राहक","67":"संरचना","68":"ग्राहक बनाये ","69":"ग्राहक  देखें ","70":"सामूहिक ग्राहकों  को बनाये ","71":"समूह का प्रबंधन ","73":"लंबित अनुमोदन","16":"विक्रेता","74":"विक्रेता बनाये ","75":"विक्रेता देखें ","76":"सामूहिक विक्रेताओं को  बनाये ","17":"मताधिकार","77":"मताधिकार बनाये ","78":"मताधिकार देखें ","79":"सामूहिक मताधिकारों   को बनाये ","3":"भुगतान लें ","18":"चालान प्रारूप","80":"प्रारूप बनाये ","81":"प्रारूप लिस्ट ","19":"व्यक्तिगत अनुरोध भेजें ","20":"सामूहिक अनुरोधों  को बनाये ","21":"सदस्यता बनाएं ","22":"प्रत्यक्ष भुगतान लिंक पाएं ","4":"पैसे भेजिये","23":"पैसे भेजना आरंभ करें","24":"पैसे का लेन-देन","25":"सामूहिक पैसे भेजिये","26":"लंबित अदायगी","27":"केंद्रीय  बहीखाता बयान ","5":"अनुरोध","28":"व्यक्तिगत अनुरोध","29":"सामूहिक अनुरोध","118":"बनायीं  गयी सदस्यता ","30":"सामूहिक लेन-देन करें","6":"लेन-देन ","31":"भुगतान  लेन-देन ","32":"वेबसाइट के लेन -देन","33":"फ्रॉम निर्माता  के लेन -देन","34":"प्लान के लेन -देन","7":"वेबसाइट निर्माता ","8":"आयोजन","35":"आयोजना  बनाये ","36":"आयोजना लिस्ट ","37":"आयोजनाओ के लेन -देन","9":"बुकिंग कैलेंडर","38":"कैलेंडर्स ","39":"ऑफ़लाइन बुकिंग ","41":"बुकिंग  के लेन -देन","10":"प्रचार","43":"प्रचार बनाये ","44":"प्रचार लिस्ट ","12":"ज़ी एस टी","50":"चालान अपलोड  ","52":"ज़ीएसटीआर १ ","90":"ज़ी एस टी आर १  अपलोड","invoice_listing":"चालान  लिस्ट ","save_as_drafts":"ड्राफ्ट के रूप में बचाने के लिए","91":"जीएसटी के लिए प्रस्तुत","51":"जीएसटी आर ३ बी ","88":"जीएसटी आर ३ बी","89":"फाइल अपलोड","53":"जीएसटी आर २ ","49":"जीएसटी संपर्क","13":"रिपोर्ट","54":"भुगतान संग्रह","94":"वेबसाइट भुगतान","95":"पैकेज भुगतान","96":"वहीखाता रिपोर्ट ","97":"टी डी र स ","98":"कूपन विश्लेषण ","55":"चालान","99":"चालान विवरण","100":"आंकलित  विवरण","101":"विलम्ब सारांश","102":"विलम्ब विवरण","93":"प्राप्त भुगतान","103":"अपेक्षित भुगतान","104":"टैक्स सारांश","105":"टैक्स विवरण","56":"अदायगी","106":"अदायगी सारांश","107":"अदायगी विवरण","108":"धन-वापसी विवरण","57":"फ्रॉम निर्माता ","109":"फ्रॉम निर्माता  डाटा","14":"डाटा  को  रूप देना","58":"सामान्य सेटिंग्स","59":"आपूर्तिकर्ता ","62":"योजनाये ","61":"कूपन ","60":"उत्पाद","64":"भूमिकाएं ","65":"उप उपयोगकर्ता","66":"ईमेल संरचना ","110":"केबल","111":"सेट टॉप बॉक्स","112":"ग्राहक पैकेज","11":"पुरस्कार","45":"स्कैन QR","46":"कमाया अंक","47":"भुनाएं अंक","48":"सेटिंग्स"},"title":{"merchant_dashboard":"व्यापारी डैशबोर्ड","total_customer":"कुल ग्राहक","current_month_transaction":"वर्तमान माह के लेन-देन","current_month_settlement":"वर्तमान महीने की बस्तियाँ","sms_sent":"SMS भेजी","notification":"सूचनाएं","payment_received":"भुगतान प्राप्त","billing_status":"बिलिंग स्थिति","contact":"संपर्क","customer_code":"ग्राहक क्रमांक","customer_name":"ग्राहक का नाम ","email":"ईमेल","mobile":"मोबाइल","address":"पता","city":"शहर","state":"राज्य","zipcode":"पिन कोड","status":"स्थिति","payment":"भुगतान","action":"क्रिया","customer_list":"ग्राहक सूची","change_search_criteria":"खोज प्रकार बदलें","excel_export":"एक्सेल एक्सपोर्ट","search":"खोज","custom_column":"कस्टम कॉलम","choose_group":"समूह चुनें","view_customer":"ग्राहक विस्तार","system_fields":"सिस्टम फील्ड्स","custom_fields":"कस्टम फील्ड्स","invoice":"चालान","tax":"कर","billing_summary":"बिलिंग सारांश","past_due":"पिछली देय राशि","current_charges":"वर्तमान शुल्क","total_due":"कुल देय राशि","sn":"क्रमांक","description":"तफ़सील","time_period":"समय सीमा","amount_rs":"राशि","sub_total":"उप कुल","total_rs":"कुल रकम","invoice_note":"नोट: यह एक सिस्टम जनरेट चालान है। कोई हस्ताक्षर की आवश्यकता नहीं है।","total_amount_payable":"कुल भुगतान राशि","pan_no":"पैन नंबर","gst_no":"जी एस टी नंबर","amount_in_word":"राशि शब्दों में","pay_now":"भुगतान करें","save_pdf":"पीडीएफ़ डाउनलोड","footer_note":"यदि आप अपने व्यवसाय के लिए ऑनलाइन भुगतान करना चाहते हैं, तो स्वाइप पर <a target=\"_BLANK\" href=\"\/merchant\/register\">अभी रजिस्टर <\/a> करें।","convenience_fee":"सुविधा शुल्क","enter_billing_details":"बिलिंग विवरण दर्ज करें","confirm_note":"कृपया ध्यान दें: भुगतान करते समय हम आपके किसी भी कार्ड / खाते की जानकारी संग्रहीत नहीं करते हैं। ऑनलाइन भुगतान के लिए, हम आपको अपना कार्ड / खाता क्रेडेंशियल्स प्रदान करने के लिए एक सुरक्षित बैंकिंग / भुगतान गेटवे वेबसाइट पर पुनर्निर्देशित कर सकते हैं।","i_accept":"मैं स्वीकारता हूँ","terms_conditions":"नियम और शर्तें","privacy_policy":"गोपनीयता नीति","click_here":"भुगतान करने के लिए यहां क्लिक करें","send_promo_sms":"प्रचार एस एम एस भेजें","promotion_name":"प्रचार नाम","select_sms":"एस एम एस का चयन करें","message":"संदेश","new_sms":"नया संदेश","promotion_list":"प्रचार सूची","create_date":"रचना तिथि","records":"रेकार्ड","comma_mobile":"मोबाइल ( बहुत के लिए अल्पविराम)"}},"english":{"menu":{"dashboard":"Dashboard","update_profile":"Update profile","company_profile":"Company profile","package_details":"Package details","password_reset":"Password reset","logout":"Logout","contacts":"Contacts","customer":"Customer","structure":"Structure","create_customer":"Create Customer","view_customer":"View Customer","bulk_upload_customer":"Bulk Upload Customer","manage_group":"Manage Group","pending_approvals":"Pending Approvals","vendors":"Vendors","create_vendor":"Create Vendor","view_vendor":"View Vendor","bulk_upload_vendors":"Bulk Upload Vendors","franchise":"Franchise","create_franchise":"Create Franchise","view_franchise":"View Franchise","bulk_upload_franchise":"Bulk Upload Franchise","collect_payments":"Collect Payments","invoice_formats":"Invoice Formats","create_format":"Create Format","list_format":"List Format","send_individual_request":"Send Individual Request","bulk_upload_request":"Bulk Upload Request","create_subscription":"Create Subscription ","get_direct_pay_link":"Get Direct Pay Link","make_transfer":"Make Transfer","initiate_transfer":"Initiate Transfer","transfer_transactions":"Transfer Transactions","bulk_upload_transfer":"Bulk Upload Transfer","pending_settelments":"Pending Settelments","nodal_ledger_statements":"Nodal Ledger Statements","requests":"Requests","individual_requests":"Individual Requests","bulk_requests":"Bulk Requests","subscription_created":"Subscription Created","bulk_upload_transaction":"Bulk Upload Transaction ","transactions":"Transactions","payment_transactions":"Payment Transactions","website_transactions":"Website Transactions","form_builder_transactions":"Form Builder Transactions","plan_transactions":"Plan Transactions","site_builder":"Site Builder","events":"Events","create_events":"Create Events","list_events":"List Events","event_transactions":"Event Transactions","booking_calendar":"Booking Calendar","calendars":"Calendars","offline_bookings":"Offline Bookings","booking_transactions":"Booking Transactions","promotions":"Promotions","create_promotions":"Create Promotions","list_promotions":"List Promotions","gst":"GST","invoice_upload":"Upload your invoices","gstr1":"GSTR 1","gstr1_upload":"Prepare GSTR 1","invoice_listing":"Invoice Listing ","save_as_drafts":"Submit to GST","submit_to_gst":"Submission status","gstr3b":"GSTR 3B","generate_file":"Generate summary","file_upload":"Upload GSTR 3B","gstr2":"GSTR2","gst_connection":"GST Connection","reports":"Reports","collections":"Collections","payment_received":"Payment Received","website_payment_received":"Website Payment Received","plan_payment_received":"Plan Payment Received","ledger_reports":"Ledger Reports","tdrs":"TDRs","coupon_analytics":"Coupon Analytics","invoicing":"Invoicing","invoice_details":"Invoice Details","estimate_details":"Estimate Details","aging_summary":"Aging Summary","aging_details":"Aging Details","payment_expected":"Payment Expected","tax_summary":"Tax Summary ","tax_details":"Tax Details","settlements":"Settlements","settlements_summary":"Settlements Summary","settlements_details":"Settlements Details","refund_details":"Refund Details","form_builder":"Form Builder","form_builder_data":"Form Builder Data","data_configuration":"Data Configuration","general_settings":"General Settings","suppliers":"Suppliers","plans":"Plans","coupons":"Coupons","product":"Products","roles":"Roles","sub_merchants":"Sub Merchants","covering_notes":"Covering Notes","cable":"Cable","set_top_box":"Set top box","customer_packages":"Customer packages","loyalty":"Loyalty","scan_qr":"Scan QR","earned_points":"Earned points","redeem_points":"Redeem points","settings":"Settings"},"title":{"merchant_dashboard":"Merchant dashboard","total_customer":"Total customers","current_month_transaction":"Current Month Transactions","current_month_settlement":"Current Month Settlements","sms_sent":"SMS sent","notification":"NOTIFICATIONS","payment_received":"PAYMENT RECEIVED","billing_status":"BILLING STATUS","contact":"Contact","customer_code":"Customer code","customer_name":"Customer name","email":"Email","mobile":"Mobile","country":"Country","address":"Address","city":"City","state":"State","zipcode":"Zipcode","status":"Status","payment":"Payment","action":"Action","customer_list":"Customer list","change_search_criteria":"Change search criteria","excel_export":"Excel export","search":"Search","custom_column":"Custom column","choose_group":"Choose group","view_customer":"View customer","system_fields":"System fields","custom_fields":"Custom fields","invoice":"Invoice","tax":"Tax","billing_summary":"Billing Summary","past_due":"Past Due","current_charges":"Current Charges","total_due":"Total Due","sn":"SN.","description":"Description","time_period":"Time Period","amount_rs":"Amount Rs.","sub_total":"SUB TOTAL","total_rs":"Total Rs.","invoice_note":"Note: This is a system generated invoice. No signature required.","total_amount_payable":"Total Amount Payable","pan_no":"PAN NO","gst_no":"GST Number","amount_in_word":"Amount (in words)","pay_now":"Pay now","save_pdf":"Save as PDF","footer_note":"If you would like to collect online payments for your business, <a target=\"_BLANK\" href=\"\/merchant\/register\"><u>register now<\/u><\/a> on Swipez.","convenience_fee":"Convenience fees","enter_billing_details":"Enter billing details","confirm_note":"Please note: We do not store any of your card/ account information when you make a payment. For online payment, we may redirect you to a secure banking/payment gateway website to provide your card/account credentials.","i_accept":"I accept the","terms_conditions":"Terms and conditions","privacy_policy":"Privacy policy","click_here":"Click here to make payment","send_promo_sms":"Send Promo SMS","promotion_name":"Promotion name","select_sms":"Select SMS","message":"Message","new_sms":"New SMS Template","promotion_list ":"Promotion list","create_date ":"Created on","records":"Records","comma_mobile":"Mobile (comma for multiple)"}}}';
        $array = json_decode($json, 1);
        $this->view->language = $language;
        $this->smarty->assign("lang", $array[$language]);
        $this->smarty->assign("lang_title", $array[$language]['title']);
        $this->view->lang = $array[$language];
        if ($this->view->logged_in == true) {
            $this->setMenu();
        }
    }

    public function setMenu($change = 0, $language = null)
    {
        try {
            $menus = '';
            if ($change == 0) {
                $array = $this->session->get('menus');
            } else {
                $array = array();
            }
            if (empty($array) || $change == 1) {
                if ($language == null) {
                    $language = $this->session->get('language');
                }
                $service_id = $this->session->get('service_id');
                if ($service_id > 0) {
                    $merge = $this->common->getRowValue('merge_menu', 'merchant_active_apps', 'user_id', $this->user_id, 1, ' and service_id=' . $service_id);
                    if ($merge == 1) {
                        $ser_list = $this->common->getListValue('merchant_active_apps', 'user_id', $this->user_id, 1, ' and merge_menu= 1 and status=1');
                        foreach ($ser_list as $r) {
                            $men = $this->common->getRowValue('menus', 'swipez_services', 'service_id', $r['service_id']);
                            $menus = $menus . ',' . $men;
                        }
                        if (substr($menus, 0, 1) == ',') {
                            $menus = substr($menus, 1);
                        }
                        $menu_array = explode(',', $menus);
                        $menus = implode(',', array_unique($menu_array));
                    } else {
                        $menus = $this->common->getRowValue('menus', 'swipez_services', 'service_id', $service_id);
                    }
                    if ($menus != '') {
                        $list = $this->common->getListValue('menu', 'is_active', 1, 0, ' and id in (' . $menus . ') order by seq,id');
                    } else {
                        $list = $this->common->getListValue('menu', 'id', 1);
                    }
                } else {
                    $list = $this->common->getListValue('menu', 'id', 1);
                }

                $array = array();
                foreach ($list as $row) {
                    if ($language == 'hindi') {
                        if (isset($this->view->lang['menu'][$row['id']])) {
                            $row['title'] = $this->view->lang['menu'][$row['id']];
                        } else {
                            $row['title'] = $row['eng_title'];
                        }
                    } else {
                        $row['title'] = $row['eng_title'];
                    }
                    $array[$row['parent_id']][$row['id']] = $row;
                }
                $this->session->set('menus', $array);
            }
            $this->view->menus = $array;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, 'Error while set merchant menu browser: ' . $e->getMessage());
        }
    }

    public function cronrequest($post_data, $post_url)
    {

        foreach ($post_data as $key => $value) {
            $post_items[] = $key . '=' . $value;
        }
        $post_string = implode('&', $post_items);
        $ch = curl_init() or die(curl_error($ch));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
        curl_setopt($ch, CURLOPT_PORT, 443); // port 443
        curl_setopt($ch, CURLOPT_URL, $post_url); // here the request is sent to payment gateway 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); //create a SSL connection object server-to-server
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        $host = ($this->env == 'LOCAL') ? 'http' : 'https';
        if ($host == 'https') {
            curl_setopt($ch, CURLOPT_SSL_CIPHER_LIST, 'ECDHE-RSA-AES128-GCM-SHA256,ECDHE-ECDSA-AES128-SHA');
        }
        $data1 = curl_exec($ch) or die(curl_error($ch));
        curl_close($ch);
        return $data1;
    }

    public function setAjaxDatatableSession()
    {
        $_SESSION['user_status'] = $this->session->get('user_status');
        $_SESSION['merchant_id'] = $this->session->get('merchant_id', true);
        $_SESSION['userid'] = $this->session->get('userid', true);
        $_SESSION['login_customer_group'] = $this->session->get('login_customer_group');
        $_SESSION['sub_franchise_id'] = $this->session->get('sub_franchise_id');
    }

    public function apisrequest($api_url, $post_string, $header = array())
    {
        try {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $api_url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $post_string,
                CURLOPT_HTTPHEADER => $header,
            ));
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            return $response;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E003]Error while swipez apis curl Error: ' . $e->getMessage());
        }
    }

    public function mobileBrowser()
    {
        try {
            $useragent = $_SERVER['HTTP_USER_AGENT'];
            if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i', $useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4))) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, 'Error while check mobile browser: ' . $e->getMessage());
        }
    }

    public function createApiToken($merchant_id)
    {
        try {
            $keys = $this->common->getSingleValue('merchant_security_key', 'merchant_id', $merchant_id);
            if (empty($keys)) {
                $post_data = "username=" . $_POST['username'] . "&password=" . $_POST['password'];
                $json = $this->apisrequest(env('SWIPEZ_API_URL') . 'merchantlogin', $post_data);
            } else {
                $post_data = "access_key_id=" . $keys['access_key_id'] . "&secret_access_key=" . $keys['secret_access_key'];
                $json = $this->apisrequest(env('SWIPEZ_API_URL') . 'token', $post_data);
            }
            $tokenarray = json_decode($json, 1);
            if (isset($tokenarray['success']['token'])) {
                $this->session->set('api_token', $tokenarray['success']['token']);
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, 'Error while  create Api Token: ' . $e->getMessage());
        }
    }

    private function setWebProperties()
    {
        if (BATCH_CONFIG == true) {
        } else {
            $this->session = new SessionLegacy();
            $this->view->logged_in = ($this->session->get('logged_in') == TRUE) ? TRUE : FALSE;
            $this->renderHeader();
            $this->session_expire();
            $this->view->display_url = $this->session->get('merchant_display_url');
            $this->setLanguage();
            $this->handlecsrf($_SERVER['REQUEST_URI']);
            $this->capcha_sitekey = CAPTCHAKEY;
            $this->capcha_secretkey = CAPTCHASECRET;

            $this->view->capcha_key = $this->capcha_sitekey;
            $this->app_url = config('app.url');
            $this->view->server_name = config('app.url');
            $this->view->source = 'WEB';
            $this->view->app_name = config('app.name');
            $this->view->has_partner = 0;
            $this->view->header_menu = 'swipez_header';
            $this->view->lhs_menu = 'swipez_menu';
            $this->smarty->assign("app_name", config('app.name'));
            if (config('app.partner')) {
                $this->view->header_files = config('app.partner.header_files');
                $this->view->lhs_menu = config('app.partner.lhs_menu');
                $this->view->footer_code = config('app.partner.footer_code');
                $this->view->header_menu = config('app.partner.header_menu');
                $this->view->has_partner = 1;
                $this->smarty->assign("has_partner", 1);
                $this->view->source = $this->session->get('source');
                if ($this->view->source == 'WEB') {
                    $this->view->home_url = config('app.partner.home_url');
                    $this->view->dashboard_url = config('app.partner.dashboard_url');
                } else {
                    $this->view->home_url = '#';
                    $this->view->dashboard_url = '#';
                }
            }

            if ($this->session->get('package_expire')) {
                $this->view->package_expire = true;
                $this->view->package_link = $this->session->get('package_link');
                $this->view->choose_package_link = $this->session->get('choose_package_link');
                $package_expire = true;
            }
            if ($this->session->has('package_reminder_days')) {
                $this->view->package_reminder_days = $this->session->get('package_reminder_days');
                $this->view->package_link = $this->session->get('package_link');
                $this->view->choose_package_link = $this->session->get('choose_package_link');
            }
            if ($this->session->get('customer_default_column')) {
                $this->smarty->assign("customer_default_column", $this->session->get('customer_default_column'));
            }

            $is_paid_package = $this->session->get('is_paid_package');
            $merchant_plan = $this->session->get('merchant_plan');
            $registered_on =  $this->session->get('created_date');
            $is_smart_look_active = env('IS_SMART_LOOK_ACTIVE');
            $registered_after = '2021-11-01';

            if ($is_paid_package == 1) {
                $this->view->package_type = 'Paid';
                $this->view->user_package_type = 'Paid';
            } else {
                $this->view->package_type = 'Free';
                $this->view->user_package_type = 'Free';
            }
            if ($merchant_plan == 1) {
                $this->view->package_type = 'Forever-free';
                $this->view->user_package_type = 'Forever-free';
            }
            if ($merchant_plan == 15) {
                $this->view->package_type = 'Linked-account';
                $this->view->user_package_type = 'Linked-account';
            }
            if ($package_expire) {
                $this->view->user_package_type = 'Expired';
            }

            if ($is_paid_package == 0 && $is_smart_look_active == 1 && $registered_after <= $registered_on) {
                $this->view->show_smart_look_script = true;
            }

            $this->smarty->assign("capcha_key", $this->capcha_sitekey);
            $this->smarty->assign("current_year", date('Y'));
            $this->view->current_year = date('Y');
            $this->view->support_link = SUPPORT_LINK;
            $this->smarty->assign("support_link", SUPPORT_LINK);
            $this->view->hide_home_menu = true;
            $this->view->v3_captcha_id = env('V3_CAPTCHA_CLIENT_ID');
            $this->smarty->assign("gst_tax", array(0, 5, 12, 18, 28));
        }
    }

    function getNotificationObj()
    {
        require_once CONTROLLER . 'Notification.php';
        $notification = new Notification();
        return $notification;
    }

    function validatev3Captcha()
    {
        if (isset($_POST)) {
            try {
                if (isset($_POST['recaptcha_response']) && !empty($_POST['recaptcha_response'])) {
                    //your site google secret key
                    $secret = env('V3_CAPTCHA_SECRET');
                    //get verify response data
                    $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($secret) . '&response=' . urlencode($_POST['recaptcha_response']);
                    $response = file_get_contents($url);
                    $responseKeys = json_decode($response, true);
                    if ($responseKeys["success"]) {
                        if ($responseKeys["score"] >= 0.05) {
                            return true;
                        }
                    }
                }
                $this->setError('Invalid captcha', 'Invalid captcha please try again', false);
                header('Location: ' . request()->headers->get('referer'));
                die();
            } catch (Exception $e) {
                app('sentry')->captureException($e);
            }
        }
    }

    function getSearchParamRedis($list_name=null) {
        $getRediscache = Redis::get('merchantSearchCriteria'.$this->merchant_id);
        $redis_items = json_decode($getRediscache, 1); 

        if (!empty($_POST)) {
            $redis_items[$list_name]['search_param'] = $_POST;
            Redis::set('merchantSearchCriteria'.$this->merchant_id, json_encode($redis_items));
        }
        return $redis_items;
    }
}
