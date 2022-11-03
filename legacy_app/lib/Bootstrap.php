<?php

class Bootstrap
{

    private $_url = null;
    private $_controller = null;
    private $_controllerPath = CONTROLLER; // Always include trailing slash
    private $_modelPath = MODEL; // Always include trailing slash
    private $_utilPath = UTIL;
    private $_errorFile = 'Errorclass.php';
    private $_defaultFile = 'Home.php';
    private $Restricted_GET = array(
        "Login/login/", "merchant/invoice/create", "merchant/paymentrequest/view", "merchant/register/index", "merchant/register", "merchant/register/saved", "merchant/register/verifyemail",
        "patron/register/saved", "patron/register/verifyemail", "patron/register", "patron/register/index", "merchant/template/create", "merchant/template/update", "patron/paymentrequest/view", "secure/response"
    );
    private $Restricted_POST = array(
        "Login/login/failed", "merchant/invoice/invoicesave", "merchant/paymentrequest/respond", "merchant/register/personalsave", "merchant/register/entitysave", "merchant/register/thankyou",
        "merchant/register/success", "merchant/template/saved", "merchant/template/saveupdate", "patron/paymentrequest/respond", "patron/paymentrequest/success", "patron/register/personalsave", "patron/register/success", "patron/register/thankyou",
        "paymentgateways/success"
    );
    private $session;
    private $redirected_url = array(
        "booking-calendar-pricing" => "/venue-booking-software-pricing", "home/website" => "/website-builder", "home/event" => "/event-management-software", "home/coupon" => "/coupon",
        "home/booking" => "/booking", "home/pricing" => "/pricing", "home/reseller" => "/partner", "home/aboutus" => "/aboutus", "home/contactus" => "/contactus",
        "home/privacy" => "/privacy", "home/terms" => "/terms", "home/register" => "/register", "home/workfromhome" => "/work-from-home", "event" => "/event-management-software",
        "website" => "/website-builder", "reseller" => "/partner", "invoice" => "/invoicing-system", "invoicing-system" => "/invoicing-software", "coupon" => "/couponing-system", "couponing-system" => "/coupon-management-software", "booking" => "/booking-calendar", "booking-calendar" => "/booking-management-software", "home/howitworks" => "/", "package/select" => "/pricing", "patron/event/prebook/maplebandra" => "/"
    );
    private $setcontroller_url = array(
        "oops" => "errorclass/oops", "merchant/expense/bulkupload" => "merchant/vendor/bulkupload/expense", "merchant/expense/upload" => "merchant/vendor/upload", "url-shortener" => "home/shorturl", "invoice" => "home/invoice", "website" => "home/website", "event" => "home/event", "coupon" => "home/coupon", "booking" => "home/booking",
        "pricing" => "home/pricing", "reseller" => "home/partner", "partner" => "home/partner", "aboutus" => "home/aboutus", "contactus" => "home/contactus", "privacy" => "home/privacy", "terms" => "home/terms",
        "register" => "merchant/register", "work-from-home" => "home/workfromhome", "website-builder" => "home/website", "invoicing-system" => "home/invoice", "invoicing-software" => "home/invoice", "couponing-system" => "home/coupon", "coupon-management-software" => "home/coupon",
        "event-management-software" => "home/event", "91springboard" => "home/springboard", "booking-calendar" => "home/booking", "booking-management-software" => "home/booking", "sitemap" => "home/sitemap", "now" => "home/license", "gst-info" => "profile/gst"
    );
    private $api = 0;
    /**
     * Starts the Bootstrap
     * 
     * @return boolean
     */
    public function init()
    {
        $this->session = new SessionLegacy();
        if (!SITEDOWN) {
            // Sets the protected $_url
            $this->_handleURL();
        } else {
            $this->_invokeSiteDownPage();
        }
    }

    /**
     * (Optional) Set a custom path to controllers
     * @param string $path
     */
    public function setControllerPath($path)
    {
        $this->_controllerPath = trim($path, '/') . '/';
    }

    /**
     * (Optional) Set a custom path to models
     * @param string $path
     */
    public function setModelPath($path)
    {
        $this->_modelPath = trim($path, '/') . '/';
    }

    /**
     * (Optional) Set a custom path to the error file
     * @param string $path Use the file name of your controller, eg: error.php
     */
    public function setErrorFile($path)
    {
        $this->_errorFile = trim($path, '/');
    }

    /**
     * (Optional) Set a custom path to the error file
     * @param string $path Use the file name of your controller, eg: index.php
     */
    public function setDefaultFile($path)
    {
        $this->_defaultFile = trim($path, '/');
    }

    /**
     * Fetches the $_GET from 'url'
     */
    private function _handleURL()
    {
        try {
            $_GET['url'] = $_SERVER['REQUEST_URI'];
            $_GET['url'] = isset($_GET['url']) ? substr($_GET['url'], 1) : null;
            $rawurl = isset($_GET['url']) ? $_GET['url'] : null;

            $this->redirectPage($rawurl);
            $rawurl = $this->setCustomControllerPath($rawurl);

            $url = rtrim($rawurl, '/');
            $filterurl = filter_var($url, FILTER_SANITIZE_URL);
            $this->_url = explode('/', $filterurl);
            $length = count($this->_url);

            //This block is for root domain level invocation of Swipez i.e. http://www.swipez.in/
            if ($this->_url[0] == "") {
                $this->_loadDefaultController();
                return;
            }

            $this->_url[0] = strtolower($this->_url[0]);
            $firstLevel = $this->_url[0];
            $firstLevel = ucfirst($firstLevel);
            $accessdenied = array("Uploads", "Images", "Css", "Js", "Fonts", "Inc");
            if (in_array($firstLevel, $accessdenied)) {
                header('Location: /error');
                exit;
            }
            if (substr($rawurl, -1) == "/") {
                $redirectUrl = "/" . substr_replace($rawurl, "", -1);
                header('Location:' . $redirectUrl, TRUE, 301);
            }

            switch ($firstLevel) {
                case 'Patron':
                    $this->_handlePatronURL();
                    break;
                case 'P':
                    $this->_handleShortPatronURL();
                    break;
                case 'Event':
                    $this->_handleShortEventURL();
                    break;
                case 'M':
                    $this->_handleMerchantLandingUrl();
                    break;
                case 'Merchant':
                    $this->_handleMerchantURL();
                    break;
                case 'Login':
                    $this->_handleGenericURL($firstLevel);
                    break;
                case 'Group':
                    $this->_handleGenericURL($firstLevel);
                    break;
                case 'Helpdesk':
                    $this->_handleGenericURL($firstLevel);
                    break;
                case 'Logout':
                    $this->_handleGenericURL($firstLevel);
                    break;
                case 'Home':
                    $this->_handleGenericURL($firstLevel);
                    break;
                case 'Secure':
                    $this->_handleSecureURL($rawurl, $firstLevel);
                    break;
                case 'Xway':
                    $this->_handleSecureURL($rawurl, $firstLevel);
                    break;
                case 'Profile':
                    $this->_handleGenericURL($firstLevel);
                    break;
                case 'Referrer':
                    $this->_handleGenericURL($firstLevel);
                    break;
                case 'Payment-gateways':
                    //$this->_handleGenericURL(str_replace("-", "", $firstLevel));
                    $this->_error();
                    break;
                case 'Hvk':
                    $this->_handleGenericURL($firstLevel);
                    break;
                case 'Mybills':
                    $this->_handleGenericURL($firstLevel);
                    break;
                case 'Promotion':
                    $this->_handleGenericURL($firstLevel);
                    break;
                case 'Unsubscribe':
                    $this->_handleGenericURL($firstLevel);
                    break;
                case 'Ajax':
                    $this->_handleGenericURL($firstLevel);
                    break;
                case 'Cable':
                    $this->_handleGenericURL($firstLevel);
                    break;
                case 'Api':
                    $this->_handleApiURL();
                    break;
                case 'Error':
                    $this->_error();
                    break;
                default:
                    $this->pagenotfound();
                    break;
            }
        } catch (Exception $e) {
            Sentry\captureException($e);
            throw $e;
        }
    }

    public function _handleMerchantLandingUrl()
    {
        if (empty($this->_url[1])) {
            $this->pagenotfound();
        }
        $firstLevel = $this->_url[0];
        $secondLevel = $this->_url[1];
        require_once LIB . 'Model.php';
        $model = new Model();
        $result = $model->_isvalidLandingUrl($secondLevel);
        if ($result == TRUE) {
            $this->_url[0] = 'merchant';
            $this->_url[1] = (isset($this->_url[2])) ? $this->_url[2] : NULL;
            $this->_url[2] = (isset($this->_url[3])) ? $this->_url[3] : NULL;
            $this->_url[3] = (isset($this->_url[4])) ? $this->_url[4] : NULL;
            $this->_handleMerchantURL();
        } else {
            $this->_error();
        }
    }

    /**
     * Handle generic urls i.e. non patron, merchant and secure urls are handled here
     * 
     */
    private function _handleGenericURL($firstLevel_)
    {
        $this->_loadExistingController(TRUE, $firstLevel_, NULL);
    }

    /**
     * Handle generic urls i.e. non patron, merchant and secure urls are handled here
     * 
     */
    private function _handleSecureURL($url_, $firstLevel_)
    {
        $ebsResponseURLArray = explode('/', $url_);
        $ebsResponseURLArray = array_slice($ebsResponseURLArray, 2, count($ebsResponseURLArray));
        $ebsResponseText = implode('/', $ebsResponseURLArray);
        $this->_url[2] = $ebsResponseText;

        $controllerName = ucfirst($this->_url[0]);
        //Secure does not have anything in the index and thus should throw an error
        if (!isset($this->_url[1])) {
            $this->_error();
            return;
        }

        $this->_loadExistingController(TRUE, $controllerName, NULL);
    }

    private function _handleShortPatronURL()
    {
        if (empty($this->_url[1])) {
            $this->_error();
        }
        $thirdLevel = ucfirst($this->_url[2]);
        switch ($thirdLevel) {
            case 'V':
                $this->_url[2] = 'view';
                break;
            case 'P':
                $this->_url[2] = 'pay';
                break;
        }

        $secondLevel = ucfirst($this->_url[1]);
        switch ($secondLevel) {
            case 'E':
                $this->_loadExistingController(FALSE, 'patron', 'Event');
                break;
            case 'P':
                $this->_loadExistingController(FALSE, 'patron', 'Paymentrequest');
                break;
            default:
                $this->_error();
                break;
        }
    }

    private function _handleShortEventURL()
    {
        $u0 = $this->_url[0];
        $u1 = $this->_url[1];
        $u2 = $this->_url[2];
        $u3 = $this->_url[3];

        if (isset($u3)) {
            $this->_url[4] = $u3;
        }

        if (isset($u2)) {
            $this->_url[3] = $u2;
        }

        if (isset($u1)) {
            $this->_url[2] = $u1;
        }

        $this->_url[1] = $u0;
        $this->_url[0] = 'patron';

        $this->_handlePatronURL();
    }

    /**
     * Handle patron url requests
     * 
     */
    private function _handlePatronURL()
    {
        if (empty($this->_url[1])) {
            $this->_error();
        }

        $firstLevel = $this->_url[0];
        $secondLevel = ucfirst($this->_url[1]);
        switch ($secondLevel) {
            case 'Dashboard':
                $this->_loadExistingController(FALSE, $firstLevel, $secondLevel);
                break;
            case 'Profile':
                $this->_loadExistingController(FALSE, $firstLevel, $secondLevel);
                break;
            case 'Paymentrequest':
                $this->_loadExistingController(FALSE, $firstLevel, $secondLevel);
                break;
            case 'Event':
                $this->_loadExistingController(FALSE, $firstLevel, $secondLevel);
                break;
            case 'Register':
                $this->_loadExistingController(FALSE, $firstLevel, $secondLevel);
                break;
            case 'Transaction':
                $this->_loadExistingController(FALSE, $firstLevel, $secondLevel);
                break;
            case 'Form':
                $this->_loadExistingController(FALSE, $firstLevel, $secondLevel);
                break;
            default:
                $this->pagenotfound();
                break;
        }
    }

    /**
     * Handle merchant url requests
     * 
     */
    private function _handleMerchantURL()
    {

        if (empty($this->_url[1])) {
            $this->_url[1] = 'Landing';
        }
        $firstLevel = $this->_url[0];
        $secondLevel = ucfirst($this->_url[1]);

        #sub-merchant validation start
        $group_type = $this->session->get('group_type');
        if ($group_type == 2) {
            $result = $this->_roleExist($secondLevel);
            if ($result != 1 && $secondLevel != 'Register') {
                $this->setError('Access denied', 'You do not have access to this feature. If you need access to this feature please contact your main merchant.');
                $this->_error();
            }
        }
        #sub merchant validation end
        switch ($secondLevel) {
            case 'Dashboard':
                $this->_loadExistingController(FALSE, $firstLevel, $secondLevel);
                break;
            case 'Profile':
                $this->_loadExistingController(FALSE, $firstLevel, $secondLevel);
                break;
            case 'Customer':
                $this->_loadExistingController(FALSE, $firstLevel, $secondLevel);
                break;
            case 'Paymentrequest':
                $this->_loadExistingController(FALSE, $firstLevel, $secondLevel);
                break;
            case 'Bulkupload':
                $this->_loadExistingController(FALSE, $firstLevel, $secondLevel);
                break;
            case 'Register':
                $this->_loadExistingController(FALSE, $firstLevel, $secondLevel);
                break;
            case 'Transaction':
                $this->_loadExistingController(FALSE, $firstLevel, $secondLevel);
                break;
            case 'Invoice':
                $this->_loadExistingController(FALSE, $firstLevel, $secondLevel);
                break;
            case 'Subscription':
                $this->_loadExistingController(FALSE, $firstLevel, $secondLevel);
                break;
            case 'Template':
                $this->_loadExistingController(FALSE, $firstLevel, $secondLevel);
                break;
            case 'Event':
                $this->_loadExistingController(FALSE, $firstLevel, $secondLevel);
                break;
            case 'Onboarding':
                $this->_loadExistingController(FALSE, $firstLevel, $secondLevel);
                break;
            case 'Supplier':
                $this->_loadExistingController(FALSE, $firstLevel, $secondLevel);
                break;
            case 'Coupon':
                $this->_loadExistingController(FALSE, $firstLevel, $secondLevel);
                break;
            case 'Comments':
                $this->_loadExistingController(FALSE, $firstLevel, $secondLevel);
                break;
            case 'Subuser':
                $this->_loadExistingController(FALSE, $firstLevel, $secondLevel);
                break;
            case 'Announcement':
                $this->_url[3] = $this->_url[2];
                $this->_url[2] = 'index';
                $this->_loadExistingController(FALSE, $firstLevel, $secondLevel);
                break;
            case 'Landing':
                $this->_loadExistingController(FALSE, $firstLevel, $secondLevel);
                break;
            case 'Companyprofile':
                $this->_loadExistingController(FALSE, $firstLevel, $secondLevel);
                break;
            case 'Policies':
                $this->_url[2] = (isset($this->_url[1])) ? $this->_url[1] : NULL;
                $this->_url[1] = 'Landing';
                $secondLevel = 'Landing';
                $this->_loadExistingController(FALSE, $firstLevel, $secondLevel);
                break;
            case 'Aboutus':
                $this->_url[2] = (isset($this->_url[1])) ? $this->_url[1] : NULL;
                $this->_url[1] = 'Landing';
                $secondLevel = 'Landing';
                $this->_loadExistingController(FALSE, $firstLevel, $secondLevel);
                break;
            case 'Contactus':
                $this->_url[2] = (isset($this->_url[1])) ? $this->_url[1] : NULL;
                $this->_url[1] = 'Landing';
                $secondLevel = 'Landing';
                $this->_loadExistingController(FALSE, $firstLevel, $secondLevel);
                break;
            case 'Paymybills':
                $this->_url[2] = (isset($this->_url[1])) ? $this->_url[1] : NULL;
                $this->_url[1] = 'Landing';
                $secondLevel = 'Landing';
                $this->_loadExistingController(FALSE, $firstLevel, $secondLevel);
                break;
            case 'Packages':
                $this->_url[3] = (isset($this->_url[2])) ? $this->_url[2] : NULL;
                $this->_url[2] = (isset($this->_url[1])) ? $this->_url[1] : NULL;
                $this->_url[1] = 'Landing';
                $secondLevel = 'Landing';
                $this->_loadExistingController(FALSE, $firstLevel, $secondLevel);
                break;
            case 'Booking':
                $this->_url[2] = (isset($this->_url[1])) ? $this->_url[1] : NULL;
                $this->_url[1] = 'Landing';
                $secondLevel = 'Landing';
                $this->_loadExistingController(FALSE, $firstLevel, $secondLevel);
                break;
            case 'Selectcourt':
                $this->_url[2] = (isset($this->_url[1])) ? $this->_url[1] : NULL;
                $this->_url[1] = 'Landing';
                $secondLevel = 'Landing';
                $this->_loadExistingController(FALSE, $firstLevel, $secondLevel);
                break;
            case 'Selectslot':
                $this->_url[2] = (isset($this->_url[1])) ? $this->_url[1] : NULL;
                $this->_url[1] = 'Landing';
                $secondLevel = 'Landing';
                $this->_loadExistingController(FALSE, $firstLevel, $secondLevel);
                break;
            case 'Selectpackage':
                $this->_url[2] = (isset($this->_url[1])) ? $this->_url[1] : NULL;
                $this->_url[1] = 'Landing';
                $secondLevel = 'Landing';
                $this->_loadExistingController(FALSE, $firstLevel, $secondLevel);
                break;
            case 'Calendar':
                $this->_url[2] = (isset($this->_url[1])) ? $this->_url[1] : NULL;
                $this->_url[1] = 'Landing';
                $secondLevel = 'Landing';
                $this->_loadExistingController(FALSE, $firstLevel, $secondLevel);
                break;
            case 'Confirmslot':
                $this->_url[2] = (isset($this->_url[1])) ? $this->_url[1] : NULL;
                $this->_url[1] = 'Landing';
                $secondLevel = 'Landing';
                $this->_loadExistingController(FALSE, $firstLevel, $secondLevel);
                break;
            case 'Confirmmembership':
                $this->_url[3] = (isset($this->_url[2])) ? $this->_url[2] : NULL;
                $this->_url[2] = (isset($this->_url[1])) ? $this->_url[1] : NULL;
                $this->_url[1] = 'Landing';
                $secondLevel = 'Landing';
                $this->_loadExistingController(FALSE, $firstLevel, $secondLevel);
                break;
            case 'Membershippayment':
                $this->_url[2] = (isset($this->_url[1])) ? $this->_url[1] : NULL;
                $this->_url[1] = 'Landing';
                $secondLevel = 'Landing';
                $this->_loadExistingController(FALSE, $firstLevel, $secondLevel);
                break;
            case 'Slotpayment':
                $this->_url[2] = (isset($this->_url[1])) ? $this->_url[1] : NULL;
                $this->_url[1] = 'Landing';
                $secondLevel = 'Landing';
                $this->_loadExistingController(FALSE, $firstLevel, $secondLevel);
                break;
            case 'Login':
                $this->_url[2] = (isset($this->_url[1])) ? $this->_url[1] : NULL;
                $this->_url[1] = 'Landing';
                $secondLevel = 'Landing';
                $this->_loadExistingController(FALSE, $firstLevel, $secondLevel);
                break;
            case 'Memberregister':
                $this->_url[2] = (isset($this->_url[1])) ? $this->_url[1] : NULL;
                $this->_url[1] = 'Landing';
                $secondLevel = 'Landing';
                $this->_loadExistingController(FALSE, $firstLevel, $secondLevel);
                break;
            case 'Memberlogout':
                $this->_url[2] = (isset($this->_url[1])) ? $this->_url[1] : NULL;
                $this->_url[1] = 'Landing';
                $secondLevel = 'Landing';
                $this->_loadExistingController(FALSE, $firstLevel, $secondLevel);
                break;
            case 'Validatememberlogin':
                $this->_url[2] = (isset($this->_url[1])) ? $this->_url[1] : NULL;
                $this->_url[1] = 'Landing';
                $secondLevel = 'Landing';
                $this->_loadExistingController(FALSE, $firstLevel, $secondLevel);
                break;
            case 'Logincheck':
                $this->_url[2] = (isset($this->_url[1])) ? $this->_url[1] : NULL;
                $this->_url[1] = 'Landing';
                $secondLevel = 'Landing';
                $this->_loadExistingController(FALSE, $firstLevel, $secondLevel);
                break;
            case 'Bookingrequest':
                $this->_url[2] = (isset($this->_url[1])) ? $this->_url[1] : NULL;
                $this->_url[1] = 'Landing';
                $secondLevel = 'Landing';
                $this->_loadExistingController(FALSE, $firstLevel, $secondLevel);
                break;
            case 'Confirmpackage':
                $this->_url[3] = (isset($this->_url[2])) ? $this->_url[2] : NULL;
                $this->_url[2] = (isset($this->_url[1])) ? $this->_url[1] : NULL;
                $this->_url[1] = 'Landing';
                $secondLevel = 'Landing';
                $this->_loadExistingController(FALSE, $firstLevel, $secondLevel);
                break;
            case 'Logoutcustomer':
                $this->_url[3] = (isset($this->_url[2])) ? $this->_url[2] : NULL;
                $this->_url[2] = (isset($this->_url[1])) ? $this->_url[1] : NULL;
                $this->_url[1] = 'Landing';
                $secondLevel = 'Landing';
                $this->_loadExistingController(FALSE, $firstLevel, $secondLevel);
                break;
            case 'Packagepayment':
                $this->_url[2] = (isset($this->_url[1])) ? $this->_url[1] : NULL;
                $this->_url[1] = 'Landing';
                $secondLevel = 'Landing';
                $this->_loadExistingController(FALSE, $firstLevel, $secondLevel);
                break;
            case 'Onlinepayment':
                $this->_url[2] = (isset($this->_url[1])) ? $this->_url[1] : NULL;
                $this->_url[1] = 'Landing';
                $secondLevel = 'Landing';
                $this->_loadExistingController(FALSE, $firstLevel, $secondLevel);
                break;
            case 'Directpay':

                $this->_url[3] = (isset($this->_url[2])) ? $this->_url[2] : NULL;
                $this->_url[2] = (isset($this->_url[1])) ? $this->_url[1] : NULL;
                $this->_url[1] = 'Landing';
                $secondLevel = 'Landing';
                $this->_loadExistingController(FALSE, $firstLevel, $secondLevel);
                break;
            case 'Confirmpayment':
                $this->_url[2] = (isset($this->_url[1])) ? $this->_url[1] : NULL;
                $this->_url[1] = 'Landing';
                $secondLevel = 'Landing';
                $this->_loadExistingController(FALSE, $firstLevel, $secondLevel);
                break;
            case 'Directpayment':
                $this->_url[4] = (isset($this->_url[3])) ? $this->_url[3] : NULL;
                $this->_url[3] = (isset($this->_url[2])) ? $this->_url[2] : NULL;
                $this->_url[2] = (isset($this->_url[1])) ? $this->_url[1] : NULL;
                $this->_url[1] = 'Landing';
                $secondLevel = 'Landing';
                $this->_loadExistingController(FALSE, $firstLevel, $secondLevel);
                break;
            case 'Upipgtrack':
                $this->_url[4] = (isset($this->_url[3])) ? $this->_url[3] : NULL;
                $this->_url[3] = (isset($this->_url[2])) ? $this->_url[2] : NULL;
                $this->_url[2] = (isset($this->_url[1])) ? $this->_url[1] : NULL;
                $this->_url[1] = 'Landing';
                $secondLevel = 'Landing';
                $this->_loadExistingController(FALSE, $firstLevel, $secondLevel);
                break;
            case 'payment-gateway':
                $this->_url[4] = (isset($this->_url[3])) ? $this->_url[3] : NULL;
                $this->_url[3] = (isset($this->_url[2])) ? $this->_url[2] : NULL;
                $this->_url[2] = (isset($this->_url[1])) ? $this->_url[1] : NULL;
                $this->_url[1] = 'Landing';
                $secondLevel = 'Landing';
                $this->_loadExistingController(FALSE, $firstLevel, $secondLevel);
                break;
            case 'Paymentsuccess':
                $this->_url[2] = (isset($this->_url[1])) ? $this->_url[1] : NULL;
                $this->_url[1] = 'Landing';
                $secondLevel = 'Landing';
                $this->_loadExistingController(FALSE, $firstLevel, $secondLevel);
                break;
            case 'Showcoupon':
                $this->_url[4] = (isset($this->_url[3])) ? $this->_url[4] : NULL;
                $this->_url[3] = (isset($this->_url[2])) ? $this->_url[2] : NULL;
                $this->_url[2] = (isset($this->_url[1])) ? $this->_url[1] : NULL;
                $this->_url[1] = 'Landing';
                $secondLevel = 'Landing';
                $this->_loadExistingController(FALSE, $firstLevel, $secondLevel);
                break;
            case 'Qrcode':
                $this->_url[4] = (isset($this->_url[3])) ? $this->_url[4] : NULL;
                $this->_url[3] = (isset($this->_url[2])) ? $this->_url[2] : NULL;
                $this->_url[2] = (isset($this->_url[1])) ? $this->_url[1] : NULL;
                $this->_url[1] = 'Landing';
                $secondLevel = 'Landing';
                $this->_loadExistingController(FALSE, $firstLevel, $secondLevel);
                break;
            case 'Attendeeavailed':
                $this->_url[4] = (isset($this->_url[3])) ? $this->_url[4] : NULL;
                $this->_url[3] = (isset($this->_url[2])) ? $this->_url[2] : NULL;
                $this->_url[2] = (isset($this->_url[1])) ? $this->_url[1] : NULL;
                $this->_url[1] = 'Landing';
                $secondLevel = 'Landing';
                $this->_loadExistingController(FALSE, $firstLevel, $secondLevel);
                break;
            case 'Qrcodereceipt':
                $this->_url[4] = (isset($this->_url[3])) ? $this->_url[4] : NULL;
                $this->_url[3] = (isset($this->_url[2])) ? $this->_url[2] : NULL;
                $this->_url[2] = (isset($this->_url[1])) ? $this->_url[1] : NULL;
                $this->_url[1] = 'Landing';
                $secondLevel = 'Landing';
                $this->_loadExistingController(FALSE, $firstLevel, $secondLevel);
                break;
            case 'Report':
                $this->_loadExistingController(FALSE, $firstLevel, $secondLevel);
                break;
            case 'Chart':
                $this->_loadExistingController(FALSE, $firstLevel, $secondLevel);
                break;
            case 'API':
                $this->_loadExistingController(FALSE, $firstLevel, $secondLevel);
                break;
            case 'Approve':
                $this->_loadExistingController(FALSE, $firstLevel, $secondLevel);
                break;
            case 'Package':
                $this->_loadExistingController(FALSE, $firstLevel, $secondLevel);
                break;
            case 'Plan':
                $this->_loadExistingController(FALSE, $firstLevel, $secondLevel);
                break;
            case 'Website':
                $this->_loadExistingController(FALSE, $firstLevel, $secondLevel);
                break;
            case 'Bookings':
                $this->_loadExistingController(FALSE, $firstLevel, $secondLevel);
                break;
            case 'Directpaylink':
                $this->_loadExistingController(FALSE, $firstLevel, $secondLevel);
                break;
            case 'Promotions':
                $this->_loadExistingController(FALSE, $firstLevel, $secondLevel);
                break;
            case 'Franchise':
                $this->_loadExistingController(FALSE, $firstLevel, $secondLevel);
                break;
            case 'Vendor':
                $this->_loadExistingController(FALSE, $firstLevel, $secondLevel);
                break;
            case 'Ticket':
                $this->_loadExistingController(FALSE, $firstLevel, $secondLevel);
                break;
            case 'Coveringnote':
                $this->_loadExistingController(FALSE, $firstLevel, $secondLevel);
                break;
            case 'Gst':
                $this->_loadExistingController(FALSE, $firstLevel, $secondLevel);
                break;
            case 'Cable':
                $this->_loadExistingController(FALSE, $firstLevel, $secondLevel);
                break;
            case 'Loyalty':
                $this->_loadExistingController(FALSE, $firstLevel, $secondLevel);
                break;
            case 'Product':
                $this->_loadExistingController(FALSE, $firstLevel, $secondLevel);
                break;
            case 'Tax':
                $this->_loadExistingController(FALSE, $firstLevel, $secondLevel);
                break;
            case 'Payout':
                $this->_loadExistingController(FALSE, $firstLevel, $secondLevel);
                break;
            case 'Beneficiary':
                $secondLevel = 'Payout';
                $this->_url[1] = 'payout';
                $this->_loadExistingController(FALSE, $firstLevel, $secondLevel);
                break;
            default:
                $this->pagenotfound();
                break;
        }
    }

    #handle api request

    public function _handleApiUrl()
    {
        $this->api = 1;
        $firstLevel = $this->_url[1];
        $secondLevel = $this->_url[2];
        $thirdLevel = $this->_url[3];
        $fourthLevel = $this->_url[4];
        $version = $firstLevel . '/' . $secondLevel;
        switch ($version) {
            case 'v1/merchant':
                $this->_loadApiController($version, $thirdLevel, $fourthLevel);
                break;
            case 'v2/merchant':
                $this->_loadApiController($version, $thirdLevel, $fourthLevel);
                break;
            case 'v3/merchant':
                $this->_loadApiController($version, $thirdLevel, $fourthLevel);
                break;
            case 'v1/patron':
                $this->_loadApiController($version, $thirdLevel, $fourthLevel);
                break;
            case 'v2/patron':
                $this->_loadApiController($version, $thirdLevel, $fourthLevel);
                break;
            default:
                $this->_Apierror();
                break;
        }
    }

    private function _loadApiController($version, $thirdLevel, $controllerMethodName)
    {
        $controller = ucfirst($thirdLevel);
        switch ($controller) {
            case 'Invoice':
                $file = $this->_controllerPath . 'api/' . $version . '/' . $controller . ".php";
                break;
            case 'Subscription':
                $file = $this->_controllerPath . 'api/' . $version . '/' . $controller . ".php";
                break;
            case 'Customer':
                $file = $this->_controllerPath . 'api/' . $version . '/' . $controller . ".php";
                break;
            case 'Payment':
                $file = $this->_controllerPath . 'api/' . $version . '/' . $controller . ".php";
                break;
            case 'Ticket':
                $file = $this->_controllerPath . 'api/' . $version . '/' . $controller . ".php";
                break;
            case 'Login':
                $file = $this->_controllerPath . 'api/' . $version . '/' . $controller . ".php";
                break;
            case 'Sms':
                $file = $this->_controllerPath . 'api/' . $version . '/' . $controller . ".php";
                break;
            default:
                $this->_Apierror();
                break;
        }

        if (file_exists($file)) {
            #include Api controller
            $api = $this->_controllerPath . "api/Api.php";
            require_once $api;
            #include merchant controller with version 
            require_once $file;

            $this->_controller = new $controller;
            switch ($controllerMethodName) {
                case 'save':
                    $this->_callControllerMethod($controllerMethodName);
                    break;
                case 'getlist':
                    $this->_callControllerMethod($controllerMethodName);
                    break;
                case 'pay':
                    $this->_callControllerMethod($controllerMethodName);
                    break;
                case 'settle':
                    $this->_callControllerMethod($controllerMethodName);
                    break;
                case 'update':
                    $this->_callControllerMethod($controllerMethodName);
                    break;
                case 'received':
                    $this->_callControllerMethod($controllerMethodName);
                    break;
                case 'delete':
                    $this->_callControllerMethod($controllerMethodName);
                    break;
                case 'status':
                    $this->_callControllerMethod($controllerMethodName);
                    break;
                case 'searchcustomer':
                    $this->_callControllerMethod($controllerMethodName);
                    break;
                case 'saveagent':
                    $this->_callControllerMethod($controllerMethodName);
                    break;
                case 'deleteagent':
                    $this->_callControllerMethod($controllerMethodName);
                    break;
                case 'validate':
                    $this->_callControllerMethod($controllerMethodName);
                    break;
                case 'check':
                    $this->_callControllerMethod($controllerMethodName);
                    break;
                case 'detail':
                    $this->_callControllerMethod($controllerMethodName);
                    break;
                case 'settelementlist':
                    $this->_callControllerMethod($controllerMethodName);
                    break;
                case 'send':
                    $this->_callControllerMethod($controllerMethodName);
                    break;
                case 'refund':
                    $this->_callControllerMethod($controllerMethodName);
                    break;
                case 'reconcile':
                    $this->_callControllerMethod($controllerMethodName);
                    break;
                case 'timestamp':
                    $this->_callControllerMethod($controllerMethodName);
                    break;
                case 'scheduleInvoice':
                    $this->_callControllerMethod($controllerMethodName);
                    break;
                case 'deleteInvoice':
                    $this->_callControllerMethod($controllerMethodName);
                    break;
                case 'cancelRefundTransaction':
                    $this->_callControllerMethod($controllerMethodName);
                    break;
                case 'settleCOD':
                    $this->_callControllerMethod($controllerMethodName);
                    break;
                case 'save_WC_Product':
                    $this->_callControllerMethod($controllerMethodName);
                    break;
                default:
                    $this->_Apierror();
                    break;
            }
        } else {
            $this->_Apierror();
            return false;
        }
    }

    private function _roleExist($controller)
    {
        $controller_list = array(
            'Dashboard' => '1', 'Template' => '2', 'Invoice' => '3', 'Bulkupload' => '4', 'Subscription' => '5',
            'Paymentrequest' => '6', 'Event' => '7', 'Transaction' => '9', 'Report' => '10', 'Supplier' => '11', 'Profile' => '12',
            'Companyprofile' => '13', 'Chart' => '14', 'Customer' => '15', 'Approve' => '16', 'Coupon' => '17', 'Bookings' => '18', 'Plan' => '19', 'Vendor' => '20', 'Franchise' => '21', 'Transfer' => '22', 'Gst' => '23', 'Cable' => '24', 'Payout' => '25', 'Autocollect' => '26', 'Expense' => '27'
        );
        $role_id = $controller_list[$controller];
        $roles = $this->session->get('view_roles');
        if (in_array($role_id, $roles)) {
            return 1;
        }
        $roles = $this->session->get('update_roles');
        if (in_array($role_id, $roles)) {
            return 1;
        }
    }

    /**
     * This loads if there is no GET parameter passed
     */
    private function _loadDefaultController()
    {
        require $this->_controllerPath . $this->_defaultFile;
        $this->_controller = new Home();
        if ($_SERVER['HTTP_HOST'] == 'insurance.swipez.in') {
            $this->_controller->Insurance();
        } else {
            $this->_controller->Home();
        }
    }

    /**
     * Load an existing controller if there IS a GET parameter passed
     * 
     * @return boolean|string
     */
    private function _loadExistingController($isGenericURL_, $firstLevel_, $secondLevel_ = NULL)
    {
        $file = "";
        $controller = "";
        if (isset($secondLevel_)) {
            $file = $this->_controllerPath . $firstLevel_ . "/" . $secondLevel_ . ".php";
            $controller = $secondLevel_;
        } else {
            $file = $this->_controllerPath . $firstLevel_ . ".php";
            $controller = $firstLevel_;
        }
        // SwipezLogger::debug(__CLASS__, "filepath " . $file);
        if (file_exists($file)) {
            require $file;
            $this->_controller = new $controller;

            $controllerMethodName = "";
            $controllerMethodParams = "";
            $controllerMethodParams2 = "";


            //If generic url is set to false then the url is for /patron and /merchant
            if ($isGenericURL_ == FALSE) {
                $firstLevel_ = lcfirst($firstLevel_);
                $this->_controller->loadModel($controller, $this->_modelPath . $firstLevel_ . "/");
                $controllerMethodName = isset($this->_url[2]) ? $this->_url[2] : NULL;
                $controllerMethodParams = isset($this->_url[3]) ? $this->_url[3] : NULL;
                $controllerMethodParams2 = isset($this->_url[4]) ? $this->_url[4] : NULL;
                $controllerMethodParams3 = isset($this->_url[5]) ? $this->_url[5] : NULL;
            } else {
                $this->_controller->loadModel($controller, $this->_modelPath);
                $controllerMethodName = isset($this->_url[1]) ? $this->_url[1] : NULL;
                $controllerMethodParams = isset($this->_url[2]) ? $this->_url[2] : NULL;
                $controllerMethodParams2 = isset($this->_url[3]) ? $this->_url[3] : NULL;
                $controllerMethodParams3 = isset($this->_url[4]) ? $this->_url[4] : NULL;
            }
            //restricted post -- check if user reload submit form and success form 
            // $this->restrictedPost($firstLevel_, $controller, $controllerMethodName, $controllerMethodParams);
            $this->_callControllerMethod($controllerMethodName, $controllerMethodParams, $controllerMethodParams2, $controllerMethodParams3);
        } else {
            $this->_error();
            return false;
        }
    }

    /**
     * Invoice the site down class and renders the page
     * 
     */
    private function _invokeSiteDownPage()
    {
        $file = $this->_controllerPath . SITE_DOWN_CLASS . ".php";
        require $file;
        if (file_exists($file)) {
            $this->_controller = new SiteDown;

            $this->_callControllerMethod("index", NULL, NULL);
        } else {
            $this->_error();
            return false;
        }
    }

    /**
     * If a method is passed in the GET url paremter
     * 
     *  http://localhost/controller/method/(param)/
     *  $thirdLevel_ = Method
     *  $fourthLevel_ = Param
     */
    private function _callControllerMethod($controllerMethodName_ = NULL, $controllerMethodParams_ = NULL, $controllerMethodParams2_ = NULL, $controllerMethodParams3_ = NULL)
    {
        //The below block checks if the third level url is empty then redirects to index method of the controller
        if ($controllerMethodName_ == NULL) {
            $controllerMethodName_ = 'index';
        }
        //Check if invoked method exists in the controller

        if (!method_exists($this->_controller, $controllerMethodName_)) {
            $this->_error();
            return;
        }

        $r = new ReflectionMethod($this->_controller, $controllerMethodName_);
        $params = $r->getParameters();
        if (!empty($params)) {
            foreach ($params as $key => $param) {
                $optional = $param->isOptional();
                switch ($key) {
                    case 0:
                        if ($optional != 1 && $controllerMethodParams_ == null) {
                            $this->setInvalidLinkError();
                            return;
                        }
                        break;
                    case 1:
                        if ($optional != 1 && $controllerMethodParams2_ == null) {
                            $this->setInvalidLinkError();
                            return;
                        }
                        break;
                    case 2:
                        if ($optional != 1 && $controllerMethodParams3_ == null) {
                            $this->setInvalidLinkError();
                            return;
                        }
                        break;
                }
            }
        }

        //If there are no parameters passed in the url then call the controllers method without params
        if ($controllerMethodParams_ == NULL) {
            $response = $this->_controller->{$controllerMethodName_}();
        } else {
            // Handle parameters sent within request url
            if ($controllerMethodParams2_ == NULL) {
                $response =  $this->_controller->{$controllerMethodName_}($controllerMethodParams_);
            } else {
                if ($controllerMethodParams3_ == NULL) {
                    $response = $this->_controller->{$controllerMethodName_}($controllerMethodParams_, $controllerMethodParams2_);
                } else {
                    $response = $this->_controller->{$controllerMethodName_}($controllerMethodParams_, $controllerMethodParams2_, $controllerMethodParams3_);
                }
            }
        }
        if ($this->api == 1) {
            echo $response;
        }
    }

    public function setInvalidLinkError()
    {
        $this->session->set('errorTitle', 'Invalid link');
        $this->session->set('errorMessage', 'Invalid link please do not modified encrypted link.');
        header("Location:/error", 301);
        exit;
    }

    /**
     * Display an error page if nothing exists
     * 
     * @return boolean
     */
    private function _Apierror()
    {
        $error['reqtime'] = date("Y-m-d H:i:s");
        $error['resptime'] = date("Y-m-d H:i:s");
        $error['srvrsp'] = '';
        $error['errcode'] = 'ER01002';
        $error['errmsg'] = 'Invalid request URL.';
        $error['errlist'] = '';

        print json_encode($error);
        die();
    }



    private function pagenotfound()
    {
        header('location: /404', 301);
        return;
    }

    private function _error()
    {
        require CONTROLLER . $this->_errorFile;
        $this->_controller = new Errorclass();
        $controllerMethodName_ = $this->_url[1];
        $controllerMethodParams_ = $this->_url[2];
        if ($controllerMethodName_ == NULL) {
            $this->_controller->index();
            return;
        }
        //Check if invoked method exists in the controller
        if (!method_exists($this->_controller, $controllerMethodName_)) {
            header('location: /404', 301);
            return;
        }

        //If there are no parameters passed in the url then call the controllers method without params
        if ($controllerMethodParams_ == NULL) {
            $this->_controller->{$controllerMethodName_}();
        } else {
            // Handle parameters sent within request url
            $this->_controller->{$controllerMethodName_}($controllerMethodParams_);
        }
        exit;
    }

    public function ucfirst($str)
    {
        $fc = strtoupper(substr($str, 0, 1));
        return $fc . substr($str, 1);
    }

    public function restrictedPost($first, $controller, $MethodName)
    {

        $controller = strtolower($controller);
        $first = ($first != '') ? $first . '/' : '';
        $url = $first . $controller . '/' . $MethodName;
        if (in_array($url, $this->Restricted_GET)) {
            $this->session->set('isValidPost', TRUE);
        } else {
            if (in_array($url, $this->Restricted_POST)) {
                if ($this->session->get('isValidPost') == TRUE) {
                    $this->session->remove('isValidPost');
                } else {
                    $this->setError('Invalid request', 'Please do not refresh the browser window or hit enter in the browser address bar click <a href="/" >here</a> go back to home page.');
                    header('location: /error');
                    die();
                }
            }
        }
    }

    public function setError($title, $message)
    {
        $this->session->set('errorTitle', $title);
        $this->session->set('errorMessage', $message);
    }

    private function redirectPage($url)
    {
        if (isset($this->redirected_url[$url])) {
            header('Location: ' . $this->redirected_url[$url], TRUE, 301);
            die();
        }
    }

    private function setCustomControllerPath($url)
    {
        if (substr($url, -1, 1) == "/") {
            $url = substr($url, 0, -1);
            header('Location: /' . $url, TRUE, 301);
        }
        if (isset($this->setcontroller_url[strtolower($url)])) {
            if (!isset($this->setcontroller_url[$url])) {
                header('Location: /' . strtolower($url), TRUE, 301);
                die();
            }
            return $this->setcontroller_url[$url];
        } else {
            return $url;
        }
    }
}
