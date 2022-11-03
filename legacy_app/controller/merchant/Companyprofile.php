<?php

include_once SWIPEZ_UTIL_PATH . '/src/shorturl/SwipezShortURLWrapper.php';

class Companyprofile extends Controller
{

    function __construct()
    {
        parent::__construct();
        $this->validateSession('merchant');
        $this->view->selectedMenu = 'companyprofileupdate';
        $this->hasRole(1, 13);
    }

    function index()
    {
        try {
            $merchant_id = $this->session->get('merchant_id');
            require_once MODEL . 'merchant/LandingModel.php';
            $model = new LandingModel();
            $result = $model->getMerchantLandingDetails($merchant_id);
            $industry = $model->getMerchantIndustry($merchant_id);
            $merchant_details = $model->getMerchantDetails($merchant_id);
            $this->view->title = "Company - Home";
            $this->view->header_file = ['companyprofile'];
            $this->view->render('header/app');
            $this->smarty->assign("details", $result);
            $this->smarty->assign('industry', $industry);
            $this->smarty->assign('merchant_details', $merchant_details);
            $this->smarty->assign("post_url", '/merchant/companyprofile/saved/home');
            $this->smarty->assign("title", 'Home');
            $this->smarty->display(VIEW . 'merchant/companyprofile/banner.tpl');
            $this->smarty->display(VIEW . 'merchant/companyprofile/create.tpl');
            $this->view->render('footer/company_profile');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E083]Error while reset password initiate Error:  for user id [' . $user_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function policies()
    {
        try {
            $merchant_id = $this->session->get('merchant_id');
            require_once MODEL . 'merchant/LandingModel.php';
            $model = new LandingModel();
            $result = $model->getMerchantLandingDetails($merchant_id);
            $this->view->title = "Company - Policies";
            $this->view->header_file = ['companyprofile'];
            $this->view->render('header/app');
            $this->smarty->assign("details", $result);
            $this->smarty->assign("post_url", '/merchant/companyprofile/saved/policies');
            $this->smarty->assign("title", 'Policies');

            $this->smarty->display(VIEW . 'merchant/companyprofile/banner.tpl');
            $this->smarty->display(VIEW . 'merchant/companyprofile/policies.tpl');
            $this->view->render('footer/company_profile');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E083]Error while reset password initiate Error:  for user id [' . $user_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function aboutus()
    {
        try {
            $merchant_id = $this->session->get('merchant_id');
            require_once MODEL . 'merchant/LandingModel.php';
            $model = new LandingModel();
            $result = $model->getMerchantLandingDetails($merchant_id);
            $this->view->title = "Company - About us";
            $this->view->header_file = ['companyprofile'];
            $this->view->render('header/app');
            $this->smarty->assign("details", $result);
            $this->smarty->assign("post_url", '/merchant/companyprofile/saved/aboutus');
            $this->smarty->assign("title", 'About us');

            $this->smarty->display(VIEW . 'merchant/companyprofile/banner.tpl');
            $this->smarty->display(VIEW . 'merchant/companyprofile/aboutus.tpl');
            $this->view->render('footer/company_profile');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E083]Error while reset password initiate Error:  for user id [' . $user_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function contactus()
    {
        try {
            $merchant_id = $this->session->get('merchant_id');
            require_once MODEL . 'merchant/LandingModel.php';
            $model = new LandingModel();
            $result = $model->getMerchantLandingDetails($merchant_id);
            $this->view->title = "Company - Contact us";
            $this->view->header_file = ['companyprofile'];
            $this->view->render('header/app');
            $this->smarty->assign("details", $result);
            $this->smarty->assign("post_url", '/merchant/companyprofile/saved/contactus');
            $this->smarty->assign("title", 'Contact us');

            $this->smarty->display(VIEW . 'merchant/companyprofile/banner.tpl');
            $this->smarty->display(VIEW . 'merchant/companyprofile/contactus.tpl');
            $this->view->render('footer/company_profile');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E083]Error while reset password initiate Error:  for user id [' . $user_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function saved($type)
    {
        try {
            $this->hasRole(2, 13);
            if (!empty($_POST)) {
                $merchant_id = $this->session->get('merchant_id');
                require_once CONTROLLER . 'merchant/Registervalidator.php';
                $validator = new Registervalidator($this->model);
                
                switch ($type) {
                    case 'home':
                        $_POST['display_url'] = trim($_POST['display_url']);
                        $_POST['display_url'] = str_replace(' ', '', $_POST['display_url']);
                        if ($_POST['display_url'] != $_POST['ex_display_url']) {
                            $short_url = $this->common->getRowValue('directpay_link', 'merchant_setting', 'merchant_id', $this->merchant_id);
                            if ($short_url != '') {
                                $space_position = strpos($short_url, '.in/');
                                $short_code = substr($short_url, $space_position + 4);
                                $shortUrlWrap = new SwipezShortURLWrapper();
                                $shortdet = $shortUrlWrap->getDetail($short_code);
                                $long_url = str_replace($_POST['ex_display_url'], $_POST['display_url'], $shortdet['long']);
                                $shortUrlWrap->updateLongURL($shortdet['id'], $long_url);
                            }
                        }
                        if ($hasErrors == false) {
                            $validator->validateHome($merchant_id);
                            $hasErrors = $validator->fetchErrors();
                        }
                        if ($hasErrors == false) {
                            $this->smarty->assign("success", 'Home saved successfully.');
                            $this->model->saveHome($merchant_id, $_POST['overview'], $_POST['display_url'], $_POST['website']);
                            $this->session->set('merchant_display_url', $_POST['display_url']);
                            $validator->validateImageBanner();

                            $hasErrors = $validator->fetchErrors();
                            if ($hasErrors == false) {
                                $this->model->saveImageBanner($merchant_id, $_FILES['logo'], $_FILES['banner']);
                            }
                        } else {
                            $this->smarty->assign("haserrors", $hasErrors);
                        }
                        $this->index();
                        break;
                    case 'policies':
                        if ($hasErrors == false) {
                            $this->smarty->assign("success", 'Policies saved successfully.');
                            $this->model->savePolicies($merchant_id, $_POST['terms_condition'], $_POST['cancellation_policy']);
                        } else {
                            $this->smarty->assign("haserrors", $hasErrors);
                        }
                        $this->policies();

                        break;
                    case 'aboutus':
                        if ($hasErrors == false) {
                            $this->smarty->assign("success", 'Aboutus saved successfully.');
                            $this->model->saveAboutus($merchant_id, $_POST['about_us']);
                        } else {
                            $this->smarty->assign("haserrors", $hasErrors);
                        }
                        $this->aboutus();
                        break;
                    case 'contactus':
                        if ($hasErrors == false) {
                            $validator->validateContactus();
                            $hasErrors = $validator->fetchErrors();
                        }
                        if ($hasErrors == false) {
                            $this->smarty->assign("success", 'Contactus saved successfully.');
                            $this->model->saveContactus($merchant_id, $_POST['location'], $_POST['contact_no'], $_POST['email_id']);
                        } else {
                            $this->smarty->assign("haserrors", $hasErrors);
                        }
                        $this->contactus();
                        break;
                }
            } else {
                $url = ($type == 'home') ? '' : '/' . $type;
                header('location: /merchant/companyprofile' . $url);
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E083-b]Error while company profile update Error:  for user id [' . $user_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function error()
    {
        $this->setError('Incomplete company profile', 'Incomplete company profile click <a href="/merchant/companyprofile" >here</a> to set display url.');
        header('location: /error');
    }
}
