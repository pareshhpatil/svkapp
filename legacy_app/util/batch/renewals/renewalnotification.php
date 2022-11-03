<?php

include('../config.php');

class Packagenotification extends Batch
{

    public $logger = NULL;
    public $common = NULL;

    function __construct()
    {
        parent::__construct();
        $this->logger = new SwipezLogger('log4php_config.xml');
        require_once MODEL . 'CommonModel.php';
        $this->common = new CommonModel();
        $this->sending('sms');
        $this->sending('plan');
        $this->sending('website');
        $this->sending('api');
    }

    function getSMSpackage($min, $max)
    {
        try {
            $sql = "SELECT m.merchant_id,sum(license_available) as sms_av from merchant_addon a inner join merchant_setting m on a.merchant_id=m.merchant_id and m.sms_gateway_type=2 inner join notification_count c on m.merchant_id=c.merchant_id where c.total_sms_count>0 and a.is_active=1 and a.package_id=7 and a.license_available>0 and a.start_date<=NOW() and a.end_date>=NOW() group by merchant_id";
            $params = array();
            $this->db->exec($sql, $params);
            $rows = $this->db->resultset();
            $merchant_sms = array();
            foreach ($rows as $row) {
                if ($row['sms_av'] > $min && $row['sms_av'] <= $max) {
                    $merchant_sms[] = array('merchant_id' => $row['merchant_id'], 'sms_av' => $row['sms_av']);
                }
            }
            return $merchant_sms;
        } catch (Exception $e) {
Sentry\captureException($e);
            
$this->logger->error(__CLASS__, '[PN100]Error while get getSMSpackage Error: ' . $e->getMessage());
        }
    }

    function getMerchantSMSdetail($merchant_id)
    {
        try {
            $sql = "SELECT sum(license_bought) as total_sms,sum(license_available) as sms_av from merchant_addon  where is_active=1 and package_id=7 and merchant_id=:merchant_id";
            $params = array(':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $row = $this->db->single();

            $sql = "SELECT total_sms_count from notification_count  where merchant_id=:merchant_id";
            $params = array(':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $row1 = $this->db->single();
            $row['sent_sms'] = $row1['total_sms_count'];
            return $row;
        } catch (Exception $e) {
Sentry\captureException($e);
            
$this->logger->error(__CLASS__, '[PN10136]Error while get getMerchantSMSdetail Error: ' . $e->getMessage());
        }
    }

    function getInvoicepackage()
    {
        try {
            $sql = "SELECT m.merchant_id,total_invoices,a.start_date,a.end_date,package_id from account a inner join merchant_setting m on a.merchant_id=m.merchant_id where a.is_active=1 and package_id in(3,9) where a.end_date>NOW()";
            $params = array();
            $this->db->exec($sql, $params);
            $rows = $this->db->resultset();
            $merchant_inv = array();
            foreach ($rows as $row) {
                $count = $this->getMerchantInvoice($row['merchant_id'], $row['start_date']);
                $av = $row['total_invoices'] - $count;
                $merchant_sms = array();
                $merchant_inv[] = array('merchant_id' => $row['merchant_id'], 'total_invoices' => $row['total_invoices'], 'inv_av' => $av, 'package_id' => $row['package_id'], 'end_date' => $row['end_date']);
            }
            return $merchant_inv;
        } catch (Exception $e) {
Sentry\captureException($e);
            
$this->logger->error(__CLASS__, '[PN102]Error while get getInvoicepackage Error: ' . $e->getMessage());
        }
    }

    function getMerchantInvoice($merchant_id, $start_date)
    {
        try {
            $sql = "select count(payment_request_id) as pcount from payment_request where merchant_id='" . $merchant_id . "' and parent_request_id <> '0' and created_date>'" . $start_date . "'";
            $params = array();
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            return $row['pcount'];
        } catch (Exception $e) {
Sentry\captureException($e);
            
$this->logger->error(__CLASS__, '[PN103]Error while get getMerchantInvoice Error: ' . $e->getMessage());
        }
    }

    function getMerchantEmail($merchant_id)
    {
        try {
            $sql = "select business_email from merchant_billing_profile where merchant_id=:merchant_id and is_default=1";
            $params = array(':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            return $row['business_email'];
        } catch (Exception $e) {
Sentry\captureException($e);
            
$this->logger->error(__CLASS__, '[PN104]Error while get getMerchantEmail Error: ' . $e->getMessage());
        }
    }

    function getMerchantMobile($merchant_id)
    {
        try {
            $sql = "select mobile_no from user d inner join merchant m on m.user_id=d.user_id where m.merchant_id=:merchant_id";
            $params = array(':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            return $row['mobile_no'];
        } catch (Exception $e) {
Sentry\captureException($e);
            
$this->logger->error(__CLASS__, '[PN104+1]Error while get getMerchantMobile Error: ' . $e->getMessage());
        }
    }

    function getPackageAmount($package_id)
    {
        try {
            $sql = "select package_cost from package where package_id=:package_id";
            $params = array(':package_id' => $package_id);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            return $row['package_cost'];
        } catch (Exception $e) {
Sentry\captureException($e);
            
$this->logger->error(__CLASS__, '[PN104+1]Error while get getMerchantMobile Error: ' . $e->getMessage());
        }
    }

    function isExistNotification($merchant_id, $type, $days)
    {
        try {
            $sql = "select count(id) total_sent from merchant_notification where merchant_id=:merchant_id and type=:type";
            $params = array(':merchant_id' => $merchant_id, ':type' => $type);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            if ($row['total_sent'] > 4) {
                return true;
            }
            $sql = "select id from merchant_notification where DATE_SUB(NOW(), INTERVAL " . $days . " DAY)<notification_sent and merchant_id=:merchant_id and type=:type";
            $params = array(':merchant_id' => $merchant_id, ':type' => $type);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            if (empty($row)) {
                return false;
            } else {
                return true;
            }
        } catch (Exception $e) {
Sentry\captureException($e);
            
$this->logger->error(__CLASS__, '[PN105]Error while isExistNotification Error: ' . $e->getMessage());
        }
    }

    function getPackageExpired($package_id, $min, $max)
    {
        try {
            $sql = "SELECT DATEDIFF(end_date, NOW()) datediff,merchant_id,end_date,package_id  from merchant_addon where is_active=1 and package_id=" . $package_id . " and ( end_date BETWEEN DATE_ADD(now(), INTERVAL " . $min . " DAY) and DATE_ADD(now(), INTERVAL " . $max . " DAY) OR end_date<now());";
            $params = array();
            $this->db->exec($sql, $params);
            $rows = $this->db->resultset();
            return $rows;
        } catch (Exception $e) {
Sentry\captureException($e);
            
$this->logger->error(__CLASS__, '[PN106]Error while get getSMSExpired Error: ' . $e->getMessage());
        }
    }

    function getPlanExpired($min, $max)
    {
        try {
            $sql = "SELECT DATEDIFF(end_date, NOW()) datediff,merchant_id,end_date,package_id  from account where is_active=1 and package_id in(3,4,9,12,13,14) and ( end_date BETWEEN DATE_ADD(now(), INTERVAL " . $min . " DAY) and DATE_ADD(now(), INTERVAL " . $max . " DAY) OR end_date<now())";
            $params = array();
            $this->db->exec($sql, $params);
            $rows = $this->db->resultset();
            return $rows;
        } catch (Exception $e) {
Sentry\captureException($e);
            
$this->logger->error(__CLASS__, '[PN106]Error while get plan expired Error: ' . $e->getMessage());
        }
    }

    function getSMSExpired()
    {
        try {
            $sql = "SELECT distinct merchant_id  from merchant_addon where is_active=1 and package_id=7 and license_available>0 and start_date<=NOW() and end_date>=NOW()";
            $params = array();
            $this->db->exec($sql, $params);
            $rows = $this->db->resultset();
            foreach ($rows as $mrow) {
                $package_ex[] = $mrow['merchant_id'];
            }

            $sql = "SELECT s.merchant_id from merchant_setting s inner join notification_count c on s.merchant_id=c.merchant_id inner join merchant_addon a on s.merchant_id=a.merchant_id where c.total_sms_count>0 and s.sms_gateway_type=2";
            $params = array();
            $this->db->exec($sql, $params);
            $merchant_list = $this->db->resultset();
            $epired_merchant = array();
            foreach ($merchant_list as $row) {
                if (!in_array($row['merchant_id'], $package_ex)) {
                    $epired_merchant[] = array('merchant_id' => $row['merchant_id'], 'sms_av' => 0);
                }
            }
            return $epired_merchant;
        } catch (Exception $e) {
Sentry\captureException($e);
            
$this->logger->error(__CLASS__, '[PN106]Error while get getSMSExpired Error: ' . $e->getMessage());
        }
    }

    function SMSpack($merchant_list, $type)
    {
        try {
            $link = 'https://www.swipez.in/merchant/package/confirm/kcZr9mU5PZj_m1Y7aiIcww';
            foreach ($merchant_list as $row) {
                $merchant_id = $row['merchant_id'];
                $res = $this->isExistNotification($merchant_id, $type, 15);
                if ($res == false) {
                    $sms_det = $this->getMerchantSMSdetail($merchant_id);
                    if ($row['sms_av'] < 1) {
                        $message_text = 'You have used up 100% quota of ' . $sms_det['total_sms'] . ' SMS messages. Your customers will not receive your bills and payment receipts via SMS.';
                        $subject = "SMS pack expired";
                        $message = @file_get_contents(VIEW . 'mailer/admin/sms_expired.html');
                        $total_sms = $this->moneyFormatIndia($sms_det['total_sms']);
                        $message = str_replace('__TOTAL_SMS__', $total_sms, $message);
                        $sms = "Your Swipez SMS pack has expired. To continue sending SMS messages to your customers renew your SMS pack http://go.swipez.in/renewsms";
                    } else {
                        $message_text = 'You have used ' . $sms_det['sent_sms'] . ' SMS messages so far and now have ' . $row['sms_av'] . ' SMS messages left in your Swipez account.';
                        $subject = "SMS pack renewal due";
                        $message = @file_get_contents(VIEW . 'mailer/admin/sms_renewal.html');
                        $total_sent_sms = $this->moneyFormatIndia($sms_det['sent_sms']);
                        $message = str_replace('__TOTAL_SENT_SMS__', $total_sent_sms, $message);
                        $message = str_replace('__SMS_AVAILABLE__', $row['sms_av'], $message);
                        $sms = "Your Swipez SMS pack is due for renewal. To continue sending SMS messages to your customers renew your SMS pack http://go.swipez.in/renewsms";
                    }
                    $this->logger->info(__CLASS__, 'Merchant SMS pack notification initiated ' . $merchant_id);
                    $this->sendEmailNotification($row['merchant_id'], $subject, $message);
                    $this->sendSMSNotification($merchant_id, $sms);
                    $label_icon = 'fa-bell-o';
                    switch ($type) {
                        case 1:
                            $label_class = 'label-default';
                            break;
                        case 2:
                            $label_class = 'label-warning';
                            break;
                        case 3:
                            $label_class = 'label-danger';
                            break;
                    }
                    $this->saveMerchantNotification($merchant_id, $type, $label_class, $label_icon, $message_text, $message_text, $link, 'Buy more SMS');
                }
            }
        } catch (Exception $e) {
Sentry\captureException($e);
            
$this->logger->error(__CLASS__, '[PN107]Error while send SMSpack notification Error: ' . $e->getMessage());
        }
    }

    function invoicePack($merchant_list, $type, $min, $max)
    {
        try {
            $link = 'https://www.swipez.in/home/pricing';
            foreach ($merchant_list as $row) {
                $is_notify = false;
                $merchant_id = $row['merchant_id'];
                if ($row['inv_av'] < 1 || (strtotime(date('Y-m-d')) > strtotime($row['end_date']) && $row['package_id'] != 2)) {
                    if (strtotime(date('Y-m-d')) > strtotime($row['end_date'])) {
                        return true;
                    }
                    $message_text = 'Swipez invoice package has been expired. Please upgrade you package';
                    $message = @file_get_contents(VIEW . 'mailer/admin/package_expired.html');
                    $message = str_replace('__HEADER__', "Swipez Package Expired", $message);
                    $message = str_replace('__SUBJECT__', 'Your Swipez invoice package has been expired.', $message);
                    $message = str_replace('__MESSAGE__', '', $message);

                    $is_notify = true;
                    $type = 6;
                } else {
                    if ($row['inv_av'] > $min && $row['inv_av'] <= $max) {
                        $is_notify = true;
                        $message_text = "Your Swipez package have only " . $row['inv_av'] . ' invoices are available. Please upgrade you package';
                        $subject = "Invoice pack renewal due";
                        $message = @file_get_contents(VIEW . 'mailer/admin/invoice_renewal.html');
                        $total_sent_sms = $this->moneyFormatIndia($row['total_invoices'] - $row['inv_av']);
                        $message = str_replace('__TOTAL_SENT_SMS__', $total_sent_sms, $message);
                        $message = str_replace('__SMS_AVAILABLE__', $row['inv_av'], $message);
                    }
                }
                if ($is_notify == true) {
                    $res = $this->isExistNotification($merchant_id, $type, 15);
                    if ($res == false) {
                        $this->logger->info(__CLASS__, 'Merchant Invoice pack notification initiated ' . $merchant_id);
                        $this->sendEmailNotification($row['merchant_id'], "Invoice pack notification", $message);
                        $label_icon = 'fa-bell-o';
                        switch ($type) {
                            case 4:
                                $label_class = 'label-default';
                                break;
                            case 5:
                                $label_class = 'label-warning';
                                break;
                            case 6:
                                $label_class = 'label-danger';
                                break;
                        }
                        $this->saveMerchantNotification($merchant_id, $type, $label_class, $label_icon, $message_text, $message_text, $link, 'Upgrade package');
                    }
                }
            }
        } catch (Exception $e) {
Sentry\captureException($e);
            
$this->logger->error(__CLASS__, '[PN108]Error while send invoice pack notification Error: ' . $e->getMessage());
        }
    }

    function packageExpired($merchant_list, $type)
    {
        try {
            $link = 'https://www.swipez.in/home/pricing';
            foreach ($merchant_list as $row) {
                $is_notify = false;
                $merchant_id = $row['merchant_id'];
                switch ($row['package_id']) {
                    case 6:
                        $extype = 7;
                        $msg = "Website Builder";
                        $website = $this->common->getRowValue('merchant_domain', 'website_live', 'merchant_id', $merchant_id);
                        $short_link='https://go.swipez.in/webpkg1';
                        break;
                    case 10:
                        $extype = 8;
                        $msg = "API Integration";
                        $short_link='https://go.swipez.in/invpkg1';
                        break;
                    case 9:
                        $extype = 9;
                        $msg = "Billing startup";
                        $short_link='https://go.swipez.in/invpkg1';
                        break;
                    case 3:
                        $extype = 10;
                        $msg = "Growth";
                        $short_link='https://go.swipez.in/invpkg2';
                        break;
                    case 13:
                        $extype = 11;
                        $msg = "Event registrations";
                        $short_link='https://go.swipez.in/envpkg1';
                        break;
                    case 14:
                        $extype = 12;
                        $msg = "Venue booking software";
                        $short_link='https://go.swipez.in/bookpkg1';
                        break;
                }

                if (strtotime(date('Y-m-d')) > strtotime($row['end_date'])) {
                    if ($row['package_id'] == 6) {
                        $message = "Your website " . $website . " has expired. Renew your site subscription for as low as 2999 ";
                        $email_message = @file_get_contents(VIEW . 'mailer/admin/package expired - site builder.html');
                    } else {
                        $message = "Your Swipez " . $msg . " has expired. To continue collecting payments online renew your plan now ";
                        $email_message = @file_get_contents(VIEW . 'mailer/admin/package expired - swipez subscription.html');
                    }
                    $email_message = str_replace('__SUBJECT__', $message, $email_message);
                    $message_text = $message;
                    $is_notify = true;
                    $type = $extype;
                } else {
                    $is_notify = true;
                    if ($row['package_id'] == 6) {
                        $message = "Your website " . $website . " will expire in " . $row['datediff'] . " days. Renew your site subscription for as low as 2999 ";
                        $email_message = @file_get_contents(VIEW . 'mailer/admin/renewal due - site builder');
                    } else {
                        $message = "Your Swipez " . $msg . " will expire in " . $row['datediff'] . " days. To continue collecting payments online renew your plan now ";
                        $email_message = @file_get_contents(VIEW . 'mailer/admin/renewal due - swipez subscription.html');
                    }

                    $message_text = $message;
                    $email_message = str_replace('__SUBJECT__', $message, $email_message);
                    $email_message = str_replace('__DATE__', date("d M Y", strtotime($row['end_date'])), $email_message);
                }
                if ($is_notify == true) {
                    $res = $this->isExistNotification($merchant_id, $type, 15);
                    if ($res == false) {
                        $this->logger->info(__CLASS__, 'Merchant ' . $msg . ' pack notification initiated ' . $merchant_id);
                        $email_message = str_replace('__URL__', $short_link, $email_message);
                        $this->sendEmailNotification($row['merchant_id'], "Swipez " . $msg . " notification", $email_message);
                        $label_icon = 'fa-bell-o';
                        $this->sendSMSNotification($merchant_id, $message . ' - ' . $short_link);
                        if ($row['datediff'] < 1) {
                            $label_class = 'label-danger';
                        } else {
                            $label_class = 'label-warning';
                        }

                        $this->saveMerchantNotification($merchant_id, $type, $label_class, $label_icon, $message_text, $message_text, $short_link, 'Upgrade package');
                    }
                }
            }
        } catch (Exception $e) {
Sentry\captureException($e);
            
$this->logger->error(__CLASS__, '[PN108]Error while send invoice pack notification Error: ' . $e->getMessage());
        }
    }

    public function saveMerchantNotification($merchant_id, $type, $label_class, $label_icon, $message, $description, $link, $link_text)
    {
        try {
            $sql = "INSERT INTO `merchant_notification`(`merchant_id`,`type`,`label_class`,`label_icon`,`message`,
`description`,`link`,`link_text`,`notification_sent`,`created_date`)VALUES(:merchant_id,:type,:label_class,:label_icon,:message,:description,:link,:link_text,CURRENT_TIMESTAMP(),CURRENT_TIMESTAMP());";
            $params = array(':merchant_id' => $merchant_id, ':type' => $type, ':label_class' => $label_class, ':label_icon' => $label_icon, ':message' => $message, ':description' => $description, ':link' => $link, ':link_text' => $link_text);
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
Sentry\captureException($e);
            
$this->logger->error(__CLASS__, '[PN109]Error while insertt merchant notification Error: ' . $e->getMessage());
        }
    }

    function sending($type)
    {
        try {
            switch ($type) {
                case 'sms':
                    #sms package
                    $merchant_list = $this->getSMSpackage(100, 500);
                    $this->SMSpack($merchant_list, 1);
                    $merchant_list = $this->getSMSpackage(0, 100);
                    $this->SMSpack($merchant_list, 2);
                    $merchant_list = $this->getSMSExpired();
                    $this->SMSpack($merchant_list, 3);
                    break;
                case 'plan':
                    $merchant_list = $this->getPlanExpired(14, 21);
                    $this->packageExpired($merchant_list, 11);
                    $merchant_list = $this->getPlanExpired(7, 14);
                    $this->packageExpired($merchant_list, 12);
                    $merchant_list = $this->getPlanExpired(2, 7);
                    $this->packageExpired($merchant_list, 13);
                    $merchant_list = $this->getPlanExpired(0, 2);
                    $this->packageExpired($merchant_list, 14);
                    break;
                case 'website':
                    $merchant_list = $this->getPackageExpired(6, 14, 21);
                    $this->packageExpired($merchant_list, 15);
                    $merchant_list = $this->getPackageExpired(6, 7, 14);
                    $this->packageExpired($merchant_list, 16);
                    $merchant_list = $this->getPackageExpired(6, 2, 7);
                    $this->packageExpired($merchant_list, 17);
                    $merchant_list = $this->getPackageExpired(6, 0, 2);
                    $this->packageExpired($merchant_list, 18);
                    break;
                case 'api':
                    $merchant_list = $this->getPackageExpired(10, 14, 21);
                    $this->packageExpired($merchant_list, 19);
                    $merchant_list = $this->getPackageExpired(10, 7, 14);
                    $this->packageExpired($merchant_list, 20);
                    $merchant_list = $this->getPackageExpired(10, 2, 7);
                    $this->packageExpired($merchant_list, 21);
                    $merchant_list = $this->getPackageExpired(10, 0, 2);
                    $this->packageExpired($merchant_list, 22);
                    break;
            }
        } catch (Exception $e) {
Sentry\captureException($e);
            
$this->logger->error(__CLASS__, '[PN110]Error while sending notification Error: ' . $e->getMessage());
        }
    }

    function sendEmailNotification($merchant_id, $subject, $message)
    {
        try {
            $emailWrapper = new EmailWrapper();
            $emailWrapper->bcc = array('support@swipez.in');
            //$emailWrapper->from_email_ = 'support@swipez.in';
            $emailWrapper->from_email_ = 'accounts@swipez.in';
            $toEmail_ = $this->getMerchantEmail($merchant_id);
            //$toEmail_ = 'paresh.patil@opusnet.in';
            if ($toEmail_ != '') {
                $this->logger->info(__CLASS__, 'Merchant ' . $merchant_id . ' notification email sending ' . $toEmail_);
                $emailWrapper->sendMail($toEmail_, "", $subject, $message);
            } else {
                $this->logger->info(__CLASS__, 'Business email does not exist Merchant_id ' . $merchant_id);
            }
        } catch (Exception $e) {
Sentry\captureException($e);
            
$this->logger->error(__CLASS__, '[PN111]Error while sending email notification Error: ' . $e->getMessage());
        }
    }

    function sendSMSNotification($merchant_id, $message)
    {
        try {
            $SMSHelper = new SMSSender();
            $mobile = $this->getMerchantMobile($merchant_id);
            //$mobile = '9730946150';
            if ($mobile != '') {
                $this->logger->debug(__CLASS__, 'Sending SMS initiateMobile number is : ' . $mobile);
                $responseArr = $SMSHelper->send($message, $mobile);
                $this->logger->info(__CLASS__, 'Sending SMS Response : ' . $responseArr);
            } else {
                $this->logger->info(__CLASS__, 'Merchant mobile does not exist Merchant_id ' . $merchant_id);
            }
        } catch (Exception $e) {
Sentry\captureException($e);
            
$this->logger->error(__CLASS__, '[PN111]Error while sending mobile notification Error: ' . $e->getMessage());
        }
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

    function saveDirectpayRequest($merchant_id, $package_id, $narrative)
    {
        try {
            $data['email'] = $this->getMerchantEmail($merchant_id);
            $data['mobile'] = $this->getMerchantMobile($merchant_id);
            $merchantdet = $this->common->getSingleValue('merchant', 'merchant_id', $merchant_id);
            $data['name'] = $merchantdet['company_name'];
            $amount = $this->getPackageAmount($package_id);
            $gst = (9 * $amount / 100) * 2;
            $data['amount'] = round($amount + $gst);
            $data['purpose'] = $narrative;
            $sql = "select short_link from direct_pay_request where merchant_id=:merchant_id and amount=:amount and narrative=:narrative and email=:email and mobile=:mobile";
            $params = array(
                ':merchant_id' => $merchant_id, ':amount' => $data['amount'], ':narrative' => $narrative, ':email' => $data['email'],
                ':mobile' => $data['mobile']
            );
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            if (empty($row)) {
                $converter = new Encryption;
                require_once MODEL . 'merchant/DirectpaylinkModel.php';
                $directpay = new DirectpaylinkModel();
                $id = $directpay->saveDirectPayRequest('M000000151', $data, 'Admin');
                $encoded = $converter->encode($id);
                $link = "https://www.swipez.in/m/swipez/directpay/" . $encoded;
                $short_link = $this->getShortLink($link);
                $this->common->genericupdate('direct_pay_request', 'short_link', $short_link, 'id', $id, 'Admin');
                $row['short_link'] = $short_link;
            }
            return $row['short_link'];
        } catch (Exception $e) {
Sentry\captureException($e);
            
$this->logger->error(__CLASS__, '[PN10178]Error while get getMerchantSMSdetail Error: ' . $e->getMessage());
        }
    }
}

$ab = new Packagenotification();
