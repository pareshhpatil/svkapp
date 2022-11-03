<?php

use App\Jobs\SupportTeamNotification;

class Home extends Controller
{

    function __construct()
    {
        parent::__construct();
        $this->view->canonical = $_GET['url'];
    }

    function home()
    {
        try {
            $this->logincheck();
            $this->view->title = 'Home';
            $this->smarty->assign("talk_user_name", $this->session->get('user_name'));
            $this->smarty->assign("talk_email_id", $this->session->get('email_id'));
            $this->smarty->display(VIEW . 'home/index.tpl');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E060]Error while home page initiate Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function insurance()
    {
        try {
            $this->logincheck();
            $this->view->title = 'Home';
            $this->view->render('home/insurance');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E060]Error while home page initiate Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function invoice()
    {
        try {
            $this->logincheck();
            $this->view->title = 'Free Invoice Software: Send unlimited professional invoices online';
            $this->view->description = 'Create custom invoices or choose from our wide range of billing templates within Swipez';
            $this->view->feature_page = 'invoice.html';
            $this->view->render('header/feature');
            $this->smarty->display(VIEW . 'home/invoice.tpl');
            $this->view->render('footer/feature');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E0601]Error while home page initiate Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function features($link = NULL)
    {
        try {
            if ($link != NUll) {
                $campaign_text = substr($link, 0, 40);
                $campaign_id = $this->common->getRowValue('id', 'registration_campaigns', 'campaign_text_id', $link, 1);
                if ($campaign_id != false) {
                    $this->session->setCookie('registration_campaign_id', $campaign_id);
                }
            }
            $this->view->utm_source = (isset($_GET['utm_source'])) ? $_GET['utm_source'] : '';
            $this->view->utm_campaign = (isset($_GET['utm_campaign'])) ? $_GET['utm_campaign'] : '';
            $this->view->utm_adgroup = (isset($_GET['utm_adgroup'])) ? $_GET['utm_adgroup'] : '';
            $this->view->utm_term = (isset($_GET['utm_term'])) ? $_GET['utm_term'] : '';

            $this->view->title = 'Swipez features';
            $this->view->description = 'Create custom invoices or choose from our wide range of billing templates within Swipez';
            $this->view->feature_page = 'invoice.html';
            $this->view->render('home/promo_register');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E0601]Error while home page initiate Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function springboard($link = NULL)
    {
        try {
            $link = 'b2324c05a555624bd68a330610378e4ba2473810';
            if ($link != NUll) {
                $campaign_id = $this->common->getRowValue('id', 'registration_campaigns', 'campaign_text_id', $link, 1);
                if ($campaign_id != false) {
                    $this->session->setCookie('registration_campaign_id', $campaign_id);
                }
            }
            $this->view->utm_source = (isset($_GET['utm_source'])) ? $_GET['utm_source'] : '';
            $this->view->utm_campaign = (isset($_GET['utm_campaign'])) ? $_GET['utm_campaign'] : '';
            $this->view->utm_adgroup = (isset($_GET['utm_adgroup'])) ? $_GET['utm_adgroup'] : '';
            $this->view->utm_term = (isset($_GET['utm_term'])) ? $_GET['utm_term'] : '';

            $this->view->title = 'Swipez features';
            $this->view->description = 'Create custom invoices or choose from our wide range of billing templates within Swipez';
            $this->view->feature_page = 'invoice.html';
            $this->view->render('home/promo_springboard_register');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E0601]Error while home page initiate Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function license()
    {
        try {
            $this->view->show_hubspot = 1;
            $this->view->success = 0;
            if (isset($_POST['key1']) && $_POST['key1'] != '') {
                if (isset($_POST['g-recaptcha-response'])) {
                    $result = $this->validateCaptcha($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
                    if ($result) {
                        $key = $_POST['key1'] . '-' . $_POST['key2'] . '-' . $_POST['key3'] . '-' . $_POST['key4'];
                        $row = $this->common->getSingleValue('license', 'license_key', $key, 1, ' and status=0');
                        if (empty($row)) {
                            $hasErrors = "Invalid captcha please click on captcha box";
                            $this->view->error_type = 2;
                        } else {
                            $this->session->set('license_key_id', $row['id']);
                            $this->view->success = 1;
                        }
                    } else {
                        $hasErrors = "Invalid captcha please click on captcha box";
                        $this->view->error_type = 1;
                    }
                }

                if ($hasErrors == FALSE) {
                } else {
                    $this->view->error = $hasErrors;
                }
            }
            $this->view->title = 'Swipez license';
            $this->view->description = 'Create custom invoices or choose from our wide range of billing templates within Swipez';
            $this->view->render('home/license');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E0601]Error while home page initiate Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function demo($link = NULL)
    {
        try {
            $this->view->show_hubspot = 1;
            if ($link != NUll) {
                $campaign_id = $this->common->getRowValue('id', 'registration_campaigns', 'campaign_text_id', $link, 1);
                if ($campaign_id != false) {
                    $this->session->setCookie('registration_campaign_id', $campaign_id);
                }
            }
            if (isset($_POST['company_name']) && $_POST['company_name'] != '') {
                if (isset($_POST['g-recaptcha-response'])) {
                    $result = $this->validateCaptcha($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
                    if ($result) {
                    } else {
                        $hasErrors = "Invalid captcha please click on captcha box";
                        $this->smarty->assign("haserrors", $hasErrors);
                        $this->smarty->assign("post", $_POST);
                    }
                }
                if ($hasErrors == FALSE) {
                    $subject = "Demo request";
                    $campaign_id = $this->session->getCookie('registration_campaign_id');
                    $campaign_name = '';
                    if ($campaign_id > 0) {
                        $campaign_name = $this->common->getRowValue('campaign_name', 'registration_campaigns', 'id', $campaign_id);
                    }
                    $body_message = "Company name: " . $_POST['company_name'] . "<br>" . "Email Id: " . $_POST['email'] . "<br>" . "Mobile no: " . $_POST['mobile'] . "<br>" . "Campaign name: " . $campaign_name;
                    SupportTeamNotification::dispatch($subject, $body_message, 'SUPPORT')->onQueue(env('SQS_MERCHANT_REGISTRATION_NOTIFICATION_QUEUE'));
                    if (isset($_POST['utm_source'])) {
                        require_once MODEL . 'merchant/RegisterModel.php';
                        $registerModel = new RegisterModel();
                        $registerModel->saveCampaignAcquisitions($campaign_id, 'Demo', '', $_POST['company_name'], $_POST['email'], $_POST['mobile'], $_POST['utm_source'], $_POST['utm_campaign'], $_POST['utm_adgroup'], $_POST['utm_term']);
                        $this->session->set('showcampaign_script', 1);
                    }
                    header('Location: /home/thankyou');
                    die();
                } else {
                    $this->view->error = $hasErrors;
                }
            }

            $this->view->utm_source = (isset($_GET['utm_source'])) ? $_GET['utm_source'] : '';
            $this->view->utm_campaign = (isset($_GET['utm_campaign'])) ? $_GET['utm_campaign'] : '';
            $this->view->utm_adgroup = (isset($_GET['utm_adgroup'])) ? $_GET['utm_adgroup'] : '';
            $this->view->utm_term = (isset($_GET['utm_term'])) ? $_GET['utm_term'] : '';
            $this->view->title = 'Swipez demo';
            $this->view->description = 'Create custom invoices or choose from our wide range of billing templates within Swipez';
            $this->view->render('home/promo_demo_request');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E0601]Error while home page initiate Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function invoicedemo($link = NULL)
    {
        try {
            $this->view->show_hubspot = 1;
            $this->view->utm_source = (isset($_GET['utm_source'])) ? $_GET['utm_source'] : '';
            $this->view->utm_campaign = (isset($_GET['utm_campaign'])) ? $_GET['utm_campaign'] : '';
            $this->view->utm_adgroup = (isset($_GET['utm_adgroup'])) ? $_GET['utm_adgroup'] : '';
            $this->view->utm_term = (isset($_GET['utm_term'])) ? $_GET['utm_term'] : '';
            $this->view->title = 'Swipez demo';
            $this->view->description = 'Create custom invoices or choose from our wide range of billing templates within Swipez';
            $this->view->render('home/promo_demo_request_invoice');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E0601]Error while home page initiate Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function booking()
    {
        try {
            $this->logincheck();
            $this->view->title = 'Create & Manage multiple booking spaces';
            $this->view->description = 'Managing spaces on rentals can be quite a task. Slots can get double booked, there could be last minute cancellations or at times guests book a slot and don’t turn up.  All this leads to loss of revenue. Managing bookings via a register is cumbersome. With the Swipez booking calendar you can manage space bookings with ease and entirely online';
            $this->view->feature_page = 'calendar.html';
            $this->view->render('header/feature');
            $this->view->render('home/booking');
            $this->view->render('footer/feature');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E0601]Error while home page initiate Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function website()
    {
        try {
            $this->logincheck();
            $this->view->title = 'A Great Business Begins With A Great Website';
            $this->view->description = 'Create custom website, add your images, paste your content. That’s it. Your website is ready.';
            $this->view->feature_page = 'website.html';
            $this->view->render('header/feature');
            $this->smarty->display(VIEW . 'home/website.tpl');
            $this->view->render('footer/feature');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E0601]Error while home page initiate Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function coupon()
    {
        try {
            $this->logincheck();
            $this->view->title = 'Couponing Engine';
            $this->view->description = 'Allow you to create different offers for different customer sets. Coupons are time bound.';
            $this->view->feature_page = 'coupon.html';
            $this->view->render('header/feature');
            $this->smarty->display(VIEW . 'home/coupon.tpl');
            $this->view->render('footer/feature');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E0601]Error while home page initiate Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function shorturl()
    {
        try {
            $this->logincheck();
            $this->view->title = 'URL Shortener';
            $this->view->description = 'Shorten, simplify and share links for your business. Swipez URL Shortener helps you maximize the impact of every link with features like custom, branded domains. Try Swipez URL Shortener for free.';
            $this->session->set('valid_ajax', 'short_url');
            require_once CONTROLLER . 'Ajax.php';
            $ajax = new Ajax();
            $is_mobile = $this->session->get('short_url_mobile');
            if ($is_mobile > 0) {
                $is_mobile = 1;
            } else {
                $is_mobile = 0;
            }
            $data = $ajax->getShortUrlData('', '', $is_mobile);
            $this->smarty->assign("short_div", $data);
            $this->view->nomobile_redirect = 1;
            $this->view->render('header/feature');
            $this->smarty->display(VIEW . 'home/shorturl.tpl');
            $this->view->render('footer/shorturl');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E0601]Error while home page initiate Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function event()
    {
        try {
            $this->logincheck();
            $this->view->title = 'Manage events with ease';
            $this->view->description = 'Manage events of all sizes with ease. 0 commission fees, get started within minutes.';
            $this->view->og_type = 'website';
            $this->view->og_image = 'eventGraphic.png';
            $this->view->og_link = '/home/event';
            $this->view->feature_page = 'event.html';
            $this->view->render('header/feature');
            $this->smarty->display(VIEW . 'home/event.tpl');
            $this->view->render('footer/feature');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E0601]Error while home page initiate Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function pricing()
    {
        try {
            $this->logincheck();
            $this->view->title = 'Pricing';
            $this->view->description = 'Pricing';
            $this->view->metadata = '<title>Swipez - Pricing</title>
	<meta id="Meta Keywords" name="KEYWORDS" content="billing software, generate invoices, payment gateway, swipez"/>
	<meta id="Meta Description" name="DESCRIPTION" content="Swipez provides billing software facilities for small and medium businesses. With a fast, safe and user friendly online payment gateway."/>';
            $packages = $this->common->getListValue('package', 'is_active', 1);

            foreach ($packages as $pack) {
                if ($pack['type'] == 1) {
                    $plan[] = array('details' => $pack, 'id' => $this->encrypt->encode($pack['package_id']));
                } else {
                    $addon[] = array('details' => $pack, 'id' => $this->encrypt->encode($pack['package_id']));
                }
            }

            if ($this->session->get('logged_in') == TRUE) {
                $this->smarty->assign("logged_in", TRUE);
                if ($this->session->get('user_status') != ACTIVE_PATRON) {
                    $id = $this->session->get('merchant_plan');
                    $id = $this->encrypt->encode($id);
                    $this->smarty->assign("merchant_plan", $id);
                    $this->smarty->assign("user_type", 'merchant');
                } else {
                    $this->smarty->assign("user_type", 'patron');
                }
            } else {
                $this->smarty->assign("logged_in", FALSE);
            }
            $this->smarty->assign("plan", $plan);
            $this->smarty->assign("addon", $addon);
            $this->smarty->display(VIEW . 'home/pricing.tpl');
            $this->view->render('footer/home');
            $this->view->render('footer/pricing');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E061]Error while about us initiate Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function partner()
    {
        try {
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
                    if ($this->env == 'PROD') {
                        $subject = "Partner request";
                        $body_message = "Company name: " . $_POST['company_name'] . " <br>Company type: " . $_POST['type'] . "<br>" . "First name: " . $_POST['f_name'] . "<br>" . "Last name: " . $_POST['l_name'] . "<br>" . "Email Id: " . $_POST['email'] . "<br>" . "Mobile no: " . $_POST['mobile'] . "<br>" . "Postal address: " . $_POST['address'] . "<br>" . "Website: " . $_POST['website'] . "<br>" . "Brief Description about Company: " . $_POST['description'] . "<br>" . "Team size: " . $_POST['team_size'] . "<br>" . "Representing other products: " . $_POST['other_product'];
                        SupportTeamNotification::dispatch($subject, $body_message, 'SUPPORT')->onQueue(env('SQS_MERCHANT_REGISTRATION_NOTIFICATION_QUEUE'));
                        $this->smarty->assign("haserrors", "error");
                        $this->smarty->assign("success", "success");
                    }
                }
            }
            if ($this->session->get('logged_in') == TRUE) {
                $this->smarty->assign("logged_in", TRUE);
                if ($this->session->get('user_status') != ACTIVE_PATRON) {
                    $this->smarty->assign("user_type", 'merchant');
                } else {
                    $this->smarty->assign("user_type", 'patron');
                }
            } else {
                $this->smarty->assign("logged_in", FALSE);
            }

            $this->view->title = 'Partner | Earn a recurring income';
            $this->view->description = 'Earn recurring income by becoming a online partner';
            $this->view->metadata = '<meta id="Meta Keywords" name="KEYWORDS" content="billing software, generate income, payment gateway, swipez"/>
	<meta id="Meta Description" name="DESCRIPTION" content="Swipez provides billing software facilities for small and medium businesses. With a fast, safe and user friendly online payment gateway."/>';
            $this->smarty->display(VIEW . 'home/reseller.tpl');
            $this->view->render('footer/home');
            $this->view->render('footer/pricing');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E061]Error while about us initiate Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function workfromhome()
    {
        try {
            $this->view->title = 'Swipez | Work From Home';
            $this->view->render('header/feature');
            $this->smarty->display(VIEW . 'home/workfromhome.tpl');
            $this->view->render('footer/home');
            $this->view->render('footer/pricing');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E061]Error while about us initiate Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function demorequest()
    {
        try {
            $this->view->show_hubspot = 1;
            if (isset($_POST['company_name']) && $_POST['company_name'] != '') {
                if (isset($_POST['g-recaptcha-response'])) {
                    $result = $this->validateCaptcha($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
                    if ($result) {
                    } else {
                        $hasErrors = "Invalid captcha please click on captcha box";
                        if ($_POST['form_type'] == 'promo') {
                            echo "Invalid captcha please click on captcha box";
                            die();
                        }
                        $this->smarty->assign("haserrors", $hasErrors);
                        $this->smarty->assign("post", $_POST);
                    }
                }

                if ($hasErrors == FALSE) {
                    $subject = "Demo request";
                    if ($_POST['form_type'] == 'promo') {
                        $campaign_id = $this->session->getCookie('registration_campaign_id');
                        $campaign_name = '';
                        if ($campaign_id > 0) {
                            $campaign_name = $this->common->getRowValue('campaign_name', 'registration_campaigns', 'id', $campaign_id);
                        }
                        $body_message = "Company name: " . $_POST['company_name'] . "<br>" . "Email Id: " . $_POST['email'] . "<br>" . "Mobile no: " . $_POST['mobile'] . "<br>" . "Campaign name: " . $campaign_name;
                        SupportTeamNotification::dispatch($subject, $body_message, 'SUPPORT')->onQueue(env('SQS_MERCHANT_REGISTRATION_NOTIFICATION_QUEUE'));
                        if (isset($_POST['utm_source'])) {
                            $file = fopen("inc/campaign.csv", "r");
                            while (!feof($file)) {
                                $filerow = fgetcsv($file);
                                if ($filerow[0] != '') {
                                    $row[] = implode(',', $filerow);
                                }
                            }
                            $row[] = 'Demo,' . $_POST['company_name'] . ',' . $_POST['email'] . ',' . $_POST['mobile'] . ',' . $campaign_id . ',' . "'" . $_POST['utm_source'] . "'" . ',' . "'" . $_POST['utm_campaign'] . "'" . ',' . "'" . $_POST['utm_adgroup'] . "'" . ',' . "'" . $_POST['utm_term'] . "'";
                            $file = fopen("inc/campaign.csv", "w");
                            foreach ($row as $line) {
                                fputcsv($file, explode(',', $line));
                            }
                            fclose($file);
                        }

                        echo 'success';
                    } else {
                        $body_message = "Name: " . $_POST['name'] . " <br>Company name: " . $_POST['company_name'] . "<br>" . "Email Id: " . $_POST['email'] . "<br>" . "Mobile no: " . $_POST['mobile'] . "<br>" . "Preferred Date & Time: " . $_POST['date'] . " " . $_POST['time'];
                        SupportTeamNotification::dispatch($subject, $body_message, 'SUPPORT')->onQueue(env('SQS_MERCHANT_REGISTRATION_NOTIFICATION_QUEUE'));
                        header('Location: /home/thankyou');
                    }
                    die();
                }
            }
            if ($this->session->get('logged_in') == TRUE) {
                $this->smarty->assign("logged_in", TRUE);
                if ($this->session->get('user_status') != ACTIVE_PATRON) {
                    $this->smarty->assign("user_type", 'merchant');
                } else {
                    $this->smarty->assign("user_type", 'patron');
                }
            } else {
                $this->smarty->assign("logged_in", FALSE);
            }
            $this->smarty->assign("current_date", date('d M Y'));
            $this->view->title = 'Demo request';
            $this->view->description = 'Demo request';
            $this->view->metadata = '<meta id="Meta Keywords" name="KEYWORDS" content="billing software, generate income, payment gateway, swipez"/>
	<meta id="Meta Description" name="DESCRIPTION" content="Swipez provides billing software facilities for small and medium businesses. With a fast, safe and user friendly online payment gateway."/>';
            $this->view->title = 'Swipez | Demo Request';
            $this->view->render('header/feature');
            $this->smarty->display(VIEW . 'home/demorequest.tpl');
            $this->view->render('footer/home');
            $this->view->render('footer/pricing');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E061]Error while about us initiate Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function thankyou()
    {
        try {
            $this->logincheck();
            $this->view->showcampaign_script = $this->session->get('showcampaign_script');
            $this->session->remove('showcampaign_script');
            $this->view->title = 'Thank you';
            $this->view->header_file = ['profile'];
            $this->view->render('header/app');
            $this->smarty->assign("title", "Demo request sent successfully");
            $this->smarty->display(VIEW . 'home/success.tpl');
            $this->view->render('footer/profile');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E088]Error while preferences save Error:' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function aboutus()
    {
        try {
            $this->logincheck();
            $this->view->title = 'About us | Simplify business | Informal work culture';
            $this->view->description = 'We are focused on keeping our customers happy and their experience simple. We have a informal & motivated work culture. Click here to know more about us.';
            $this->view->metadata = '<meta id="Meta Keywords" name="KEYWORDS" content="billing software, generate invoices, payment gateway, swipez"/>
	<meta id="Meta Description" name="DESCRIPTION" content="Swipez provides billing software facilities for small and medium businesses. With a fast, safe and user friendly online payment gateway."/>';
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'home/about.tpl');
            $this->view->render('footer/home');
            $this->view->render('footer/about');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E061]Error while about us initiate Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function merchantlist()
    {
        try {
            $this->view->title = 'Merchant list';
            $this->view->description = '';
            $this->view->metadata = '';
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'home/merchantlist.tpl');
            $this->view->render('footer/home');
            $this->view->render('footer/about');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E061]Error while about us initiate Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function contactus()
    {
        try {
            $this->logincheck();
            $this->view->title = 'Contact-Us | Mini ERP for Service Providers | Swipez ';
            $this->view->description = 'Swipez has offices in Pune & Mumbai. You can reach us via Email @ support@swipez.in or via Phone';
            $this->view->render('header/app');
            $this->view->render('home/contactus');
            $this->view->render('footer/home');
            $this->view->render('footer/error');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E284]Error while  help desk initiate Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function returnpolicy()
    {
        try {
            $this->logincheck();
            $this->view->title = 'Online Cancellation | Swipez Refund Policy | Swipez';
            $this->view->description = 'Swipez Cancellation Policy gives ample clarity on the role of Swipez as a technology service provider and clarifies that any service related cancellation is between the merchant and the customer. View our detailed policy here.';
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'home/returnpolicy.tpl');
            $this->view->render('footer/home');
            $this->view->render('footer/error');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E061]Error while returnpolicy initiate Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function privacy($type = Null, $merchant_id = null)
    {
        try {
            $this->logincheck();
            $this->view->title = 'Privacy Policy | Swipez';
            $this->view->description = 'Swipez Privacy Policy details your privacy rights with collection, use & storage of your personal information while using our website.';
            $this->view->metadata = '<meta id="Meta Keywords" name="KEYWORDS" content="privacy policy, swipez privacy policy"/>
	<meta id="Meta Description" name="DESCRIPTION" content="Swipez privacy policy which is as per the Indian Information Technology Act, 2000 and the Information Technology Rules, 2011."/>';
            if (substr($type, 0, 5) == 'popup') {
                if ($merchant_id != null) {
                    $merchant_terms = $this->common->getRowValue('cancellation_policy', 'merchant_landing', 'merchant_id', $merchant_id);
                }
                $this->smarty->assign("privacy", $merchant_terms);
                $this->view->render('header/guest');
                $this->smarty->display(VIEW . 'home/privacy.tpl');
                $this->view->render('footer/nonfooter');
            } else {
                $this->view->render('header/app');
                $this->smarty->display(VIEW . 'home/privacy.tpl');
                $this->view->render('footer/home');
                $this->view->render('footer/error');
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E062]Error while privacy initiate Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function disclaimer($type = Null)
    {
        try {
            $this->logincheck();
            $this->view->title = 'Disclaimer Policy | Terms of Use | Swipez';
            $this->view->description = 'Learn about Swipez Disclaimer Policy that governs your access and use of its online Services. Click here to know more about our policy.';
            $this->view->metadata = '<meta id="Meta Keywords" name="KEYWORDS" content="disclaimer, swipez disclaimer"/>
	<meta id="Meta Description" name="DESCRIPTION" content="Know the merchant acceptable use policy which is applicable to merchants using Swipez to manage or collect payments or both."/>';
            if (substr($type, 0, 5) == 'popup') {
                $this->view->render('header/guest');
                $this->smarty->display(VIEW . 'home/disclaimer.tpl');
                $this->view->render('footer/nonfooter');
            } else {
                $this->view->render('header/app');
                $this->smarty->display(VIEW . 'home/disclaimer.tpl');
                $this->view->render('footer/home');
                $this->view->render('footer/error');
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E063]Error while disclaimer initate Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function terms($type = Null, $merchant_id = null)
    {
        try {
            $merchant_terms = '';
            $this->view->title = 'Terms & Conditions | Payment Services Agreement | Swipez';
            $this->view->description = 'Know about Swipez Terms & Conditions that govern your access and use of its online Payment Processing Services. Click here to find out more about our T&C.';
            $this->view->metadata = '<meta id="Meta Keywords" name="KEYWORDS" content="terms of use, swipez terms of use"/>
	<meta id="Meta Description" name="DESCRIPTION" content="Know the merchant acceptable use policy which is applicable to merchants using Swipez to manage or collect payments or both."/>';
            if (substr($type, 0, 5) == 'popup') {
                if ($merchant_id != null) {
                    $merchant_terms = $this->common->getRowValue('terms_condition', 'merchant_landing', 'merchant_id', $merchant_id);
                }
                $this->smarty->assign("terms", $merchant_terms);
                $this->view->render('header/guest');
                $this->smarty->display(VIEW . 'home/terms.tpl');
                $this->view->render('footer/nonfooter');
            } else {
                $this->smarty->assign("terms", $merchant_terms);
                $this->view->render('header/app');
                $this->smarty->display(VIEW . 'home/terms.tpl');
                $this->view->render('footer/home');
                $this->view->render('footer/error');
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E063]Error while terms initate Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function register()
    {
        try {
            $this->view->title = 'Sign Up | Get Started | Merchant Account | Swipez';
            $this->view->description = 'Experience the complete set of Swipez features and tools online. Empower your business. Get started with Swipez now.';
            $this->view->render('header/guest');
            $this->view->render('home/register');
            $this->view->render('footer/footer');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E064]Error while selecting register initiate Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function logincheck()
    {
        $this->smarty->assign("env", $this->env);
        if ($this->session->get('logged_in') == TRUE) {
            $this->view->isLoggedIn = TRUE;
            $this->smarty->assign("isLoggedIn", TRUE);
            if ($this->session->get('user_status') != ACTIVE_PATRON) {
                $this->view->usertype = 'merchant';
                $this->smarty->assign("user_type", 'merchant');
                $merchant_type = $this->session->get('merchant_type');
                $this->view->showWhygopaid = ($merchant_type == 1) ? TRUE : FALSE;
            } else {
                $this->view->usertype = 'patron';
                $this->smarty->assign("user_type", 'patron');
                $this->view->merchantType = FALSE;
            }
            //$this->view->usertype= ($this->session->get('user_status') != ACTIVE_PATRON) ? 'merchant' : 'patron';
        } else {
            $this->view->isLoggedIn = FALSE;
        }
    }

    function wework()
    {
        try {
            $this->logincheck();
            $this->view->title = 'Swipez - We Work';
            $this->view->description = 'Swipez - We Work';
            $this->view->render('header/feature');
            $this->view->render('home/weworks');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E0601]Error while home page initiate Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function sitemap()
    {
        try {
            $this->logincheck();
            $this->view->title = 'Site Map | Swipez';
            $this->view->description = 'Affordable online business operations software for managing payment collections, customer data, customer support, and events management in a single system.';
            $this->view->metadata = '<meta id="Meta Keywords" name="KEYWORDS" content="billing software, generate invoices, payment gateway, swipez"/>
	<meta id="Meta Description" name="DESCRIPTION" content="Swipez provides billing software facilities for small and medium businesses. With a fast, safe and user friendly online payment gateway."/>';
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'home/sitemap.tpl');
            $this->view->render('footer/home');
            $this->view->render('footer/about');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E061]Error while about us initiate Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function billingdemo($link = NULL)
    {
        try {
            $this->view->show_hubspot = 1;
            if ($link != NUll) {
                $campaign_id = $this->common->getRowValue('id', 'registration_campaigns', 'campaign_text_id', $link, 1);
                if ($campaign_id != false) {
                    $this->session->setCookie('registration_campaign_id', $campaign_id);
                }
            }
            if (isset($_POST['company_name']) && $_POST['company_name'] != '') {
                if (isset($_POST['g-recaptcha-response'])) {
                    $result = $this->validateCaptcha($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
                    if ($result) {
                    } else {
                        $hasErrors = "Invalid captcha please click on captcha box";
                        $this->smarty->assign("haserrors", $hasErrors);
                        $this->smarty->assign("post", $_POST);
                    }
                }
                if ($hasErrors == FALSE) {
                    $subject = "Billing demo request";
                    $campaign_id = $this->session->getCookie('registration_campaign_id');
                    $campaign_name = '';
                    if ($campaign_id > 0) {
                        $campaign_name = $this->common->getRowValue('campaign_name', 'registration_campaigns', 'id', $campaign_id);
                    }
                    $body_message = "Company name: " . $_POST['company_name'] . "<br>" . "Email Id: " . $_POST['email'] . "<br>" . "Mobile no: " . $_POST['mobile'] . "<br>" . "Campaign name: " . $campaign_name;
                    SupportTeamNotification::dispatch($subject, $body_message, 'SUPPORT')->onQueue(env('SQS_MERCHANT_REGISTRATION_NOTIFICATION_QUEUE'));
                    if (isset($_POST['utm_source'])) {
                        require_once MODEL . 'merchant/RegisterModel.php';
                        $registerModel = new RegisterModel();
                        $registerModel->saveCampaignAcquisitions($campaign_id, 'Demo', '', $_POST['company_name'], $_POST['email'], $_POST['mobile'], $_POST['utm_source'], $_POST['utm_campaign'], $_POST['utm_adgroup'], $_POST['utm_term']);
                        $this->session->set('showcampaign_script', 1);
                    }
                    header('Location: /home/thankyou');
                    die();
                } else {
                    $this->view->error = $hasErrors;
                }
            }

            $this->view->utm_source = (isset($_GET['utm_source'])) ? $_GET['utm_source'] : '';
            $this->view->utm_campaign = (isset($_GET['utm_campaign'])) ? $_GET['utm_campaign'] : '';
            $this->view->utm_adgroup = (isset($_GET['utm_adgroup'])) ? $_GET['utm_adgroup'] : '';
            $this->view->utm_term = (isset($_GET['utm_term'])) ? $_GET['utm_term'] : '';
            $this->view->title = 'Free Billing Software | Best Billing and Invoicing Software | Swipez';
            $this->view->description = ' Swipez provides Best Billing and Invoicing Software which covers B2B and B2C invoicing with a variety of online payment options including flat rates for B2B billing. Key Features of Invoicing Software is to Generate individual or bulk invoices, Auto generate PDF invoices and etc.';
            $this->view->render('home/promo_billing_demo_request');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E0601]Error while home page initiate Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }


    function changelanguage($lang = 'english')
    {
        $this->session->set('language', $lang);
        $this->setMenu(1, $lang);
        if (isset($_SERVER['HTTP_REFERER'])) {
            header('Location: ' . $_SERVER['HTTP_REFERER'], 301);
        } else {
            header('Location: /merchant/dashboard', 301);
        }
        die();
    }

    public function testcurl()
    {
        print_r($_POST);
        print_r($_FILES);
        die();
    }
}
