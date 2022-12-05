<?php

/**
 * Plan controller class to handle Merchants Plans
 */
class Website extends Controller
{

    function __construct()
    {
        parent::__construct();
        $this->validateSession('merchant');
        $this->view->selectedMenu = 'website';
        $cloud_front = getenv('CLOUD_FRONT');
        $this->smarty->assign("cloud_front", $cloud_front);
    }

    function start()
    {
        try {

            $merchant_id = $this->session->get('merchant_id');
            $setting = $this->model->getMerchantWebsite($merchant_id);
            if ($setting['status'] > 1) {
                header('Location: /merchant/website/build');
                exit();
            }
            $this->smarty->assign("type", 'setup');
            $this->smarty->display(VIEW . 'merchant/website/setting/header.tpl');
            $this->smarty->display(VIEW . 'merchant/website/setting/started.tpl');
            $this->smarty->display(VIEW . 'merchant/website/setting/footer.tpl');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EW01]Error while build webiste Error: for merchant id [' . $merchant_id . '] ' . $e->getMessage());
        }
    }

    function domain()
    {
        try {
            $merchant_id = $this->session->get('merchant_id');
            if (isset($_POST['domain'])) {
                require_once UTIL . 'SiteBuilderS3Bucket.php';
                $res = $this->isValidBucketName($_POST['domain']);
                if ($res) {
                    $txtkey = 'swipez-site-verification=' . $this->encrypt->encode($merchant_id);
                    $this->model->saveMerchantWebsite($merchant_id, $_POST['domain'], $txtkey, $this->user_id);
                    header('Location: /merchant/website/verify');
                    exit();
                } else {
                    $this->smarty->assign("error", 'Invalid Domain name');
                }
            }
            $setting = $this->model->getMerchantWebsite($merchant_id);
            $this->smarty->assign("type", 'setup');
            $this->smarty->assign("domain", $setting['merchant_domain']);
            $this->smarty->display(VIEW . 'merchant/website/setting/header.tpl');
            $this->smarty->display(VIEW . 'merchant/website/setting/domain.tpl');
            $this->smarty->display(VIEW . 'merchant/website/setting/footer.tpl');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EW02]Error while build webiste Error: for merchant id [' . $merchant_id . '] ' . $e->getMessage());
        }
    }

    function isValidBucketName($bucket)
    {
        if (preg_match('/^([a-z0-9]+(-[a-z0-9]+)*\.)+[a-z]{2,}$/', $bucket)) {
            return true;
        }
        return false;
    }

    function verify()
    {
        try {
            $merchant_id = $this->session->get('merchant_id');
            $setting = $this->model->getMerchantWebsite($merchant_id);
            if ($setting['merchant_domain'] == '') {
                header('Location: /merchant/website/domain');
                exit();
            }
            $this->smarty->assign("txt_text", $setting['txt_text']);
            $this->smarty->assign("type", 'setup');
            $this->smarty->assign("domain", $setting['merchant_domain']);
            $this->smarty->display(VIEW . 'merchant/website/setting/header.tpl');
            $this->smarty->display(VIEW . 'merchant/website/setting/verify.tpl');
            $this->smarty->display(VIEW . 'merchant/website/setting/footer.tpl');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EW03]Error while build webiste Error: for merchant id [' . $merchant_id . '] ' . $e->getMessage());
        }
    }

    function dns()
    {
        try {
            $merchant_id = $this->session->get('merchant_id');
            $setting = $this->model->getMerchantWebsite($merchant_id);
            if ($setting['domain_cname'] == '') {
                header('Location: /merchant/website/domain');
                exit();
            }
            $this->smarty->assign("domain_cname", $setting['domain_cname']);
            $this->smarty->assign("type", 'setup');
            $this->smarty->assign("domain", $setting['merchant_domain']);
            $this->smarty->display(VIEW . 'merchant/website/setting/header.tpl');
            $this->smarty->display(VIEW . 'merchant/website/setting/dns.tpl');
            $this->smarty->display(VIEW . 'merchant/website/setting/footer.tpl');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EW04]Error while build webiste Error: for merchant id [' . $merchant_id . '] ' . $e->getMessage());
        }
    }

    function success()
    {
        try {
            $merchant_id = $this->session->get('merchant_id');
            $setting = $this->model->getMerchantWebsite($merchant_id);
            if ($setting['status'] < 2) {
                header('Location: /merchant/website/domain');
                exit();
            }
            $this->smarty->assign("domain_cname", $setting['domain_cname']);
            $this->smarty->assign("type", 'setup');
            $this->smarty->assign("domain", $setting['merchant_domain']);
            $this->smarty->display(VIEW . 'merchant/website/setting/header.tpl');
            $this->smarty->display(VIEW . 'merchant/website/setting/success.tpl');
            $this->smarty->display(VIEW . 'merchant/website/setting/footer.tpl');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EW05]Error while build webiste Error: for merchant id [' . $merchant_id . '] ' . $e->getMessage());
        }
    }

    function build($type = 'home')
    {
        try {
            $merchant_id = $this->session->get('merchant_id');
            $json = $this->model->getStagingJSON($merchant_id);
            $setting = $this->model->getMerchantWebsite($merchant_id);
            $json = json_decode($json, 1);
            $cloud_front = getenv('CLOUD_FRONT');
            $this->smarty->assign("cloud_front", $cloud_front);
            $this->smarty->assign("json", $json);
            $this->smarty->assign("status", $setting['status']);
            $this->smarty->assign("type", $type);
            $this->view->canonical = 'merchant/supplier/viewlist';
            $this->smarty->display(VIEW . 'merchant/website/build/header.tpl');
            $this->smarty->display(VIEW . 'merchant/website/build/' . $type . '.tpl');
            $this->smarty->display(VIEW . 'merchant/website/build/footer.tpl');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EW06]Error while build webiste Error: for merchant id [' . $merchant_id . '] ' . $e->getMessage());
        }
    }

    function preview($type = 'home')
    {
        try {
            $merchant_id = $this->session->get('merchant_id');
            $json = $this->model->getStagingJSON($merchant_id);
            $json = json_decode($json, 1);
            $cloud_front = getenv('CLOUD_FRONT');
            $this->smarty->assign("cloud_front", $cloud_front);
            $this->smarty->assign("merchant_link", $this->encrypt->encode($merchant_id));
            $this->smarty->assign("json", $json);
            $this->smarty->assign("type", $type);
            $this->smarty->assign("mode", 'preview');
            $this->smarty->display(VIEW . 'merchant/website/preview/' . $type . '.tpl');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EW07]Error while preview webiste Error: for merchant id [' . $merchant_id . '] ' . $e->getMessage());
        }
    }

    function publish($type)
    {
        try {
            $merchant_id = $this->session->get('merchant_id');
            $setting = $this->model->getMerchantWebsite($merchant_id);
            $display_url = $this->model->getMerchantDisplayUrl($merchant_id);
            if ($setting['status'] < 2) {
                header('Location: /merchant/website/domain');
                exit();
            }
            require_once MODEL . 'merchant/LandingModel.php';
            $landing = new LandingModel();
            $is_plan = $landing->isMerchantPlan($merchant_id, 1);
            $json = $this->model->getStagingJSON($merchant_id);
            $json = json_decode($json, 1);
            require_once UTIL . 'SiteBuilderS3Bucket.php';
            $aws = new SiteBuilderS3Bucket('ap-south-1');
            //$aws->deleteBucketfile(getenv('S3_LIVE_BUCKET'), 'merchant/' . $merchant_id);
            $json['logo']['image'] = $this->copyimagestobucket($merchant_id, $json['logo']['image']);
            if ($type == 'home') {
                $json['content']['home']['banner']['value'] = $this->copyimagestobucket($merchant_id, $json['content']['home']['banner']['value']);
                if ($json['section']['services']['status'] == 1) {
                    $json['content']['services']['service1']['icon'] = $this->copyimagestobucket($merchant_id, $json['content']['services']['service1']['icon']);
                    $json['content']['services']['service2']['icon'] = $this->copyimagestobucket($merchant_id, $json['content']['services']['service2']['icon']);
                    $json['content']['services']['service3']['icon'] = $this->copyimagestobucket($merchant_id, $json['content']['services']['service3']['icon']);
                    $json['content']['services']['service4']['icon'] = $this->copyimagestobucket($merchant_id, $json['content']['services']['service4']['icon']);
                    $json['content']['services']['service5']['icon'] = $this->copyimagestobucket($merchant_id, $json['content']['services']['service5']['icon']);
                    $json['content']['services']['service6']['icon'] = $this->copyimagestobucket($merchant_id, $json['content']['services']['service6']['icon']);
                }

                if ($json['section']['team']['status'] == 1) {
                    $json['content']['team']['member1']['photo'] = $this->copyimagestobucket($merchant_id, $json['content']['team']['member1']['photo']);
                    $json['content']['team']['member2']['photo'] = $this->copyimagestobucket($merchant_id, $json['content']['team']['member2']['photo']);
                    $json['content']['team']['member3']['photo'] = $this->copyimagestobucket($merchant_id, $json['content']['team']['member3']['photo']);
                }

                if ($json['section']['testimonial']['status'] == 1) {
                    $json['content']['testimonial']['message1']['photo'] = $this->copyimagestobucket($merchant_id, $json['content']['testimonial']['message1']['photo']);
                    $json['content']['testimonial']['message2']['photo'] = $this->copyimagestobucket($merchant_id, $json['content']['testimonial']['message2']['photo']);
                    $json['content']['testimonial']['message3']['photo'] = $this->copyimagestobucket($merchant_id, $json['content']['testimonial']['message3']['photo']);
                }

                if ($json['section']['project']['status'] == 1) {
                    $json['content']['project']['project1']['image'] = $this->copyimagestobucket($merchant_id, $json['content']['project']['project1']['image']);
                    $json['content']['project']['project2']['image'] = $this->copyimagestobucket($merchant_id, $json['content']['project']['project2']['image']);
                    $json['content']['project']['project3']['image'] = $this->copyimagestobucket($merchant_id, $json['content']['project']['project3']['image']);
                    $json['content']['project']['project4']['image'] = $this->copyimagestobucket($merchant_id, $json['content']['project']['project4']['image']);
                    $json['content']['project']['project5']['image'] = $this->copyimagestobucket($merchant_id, $json['content']['project']['project5']['image']);
                    $json['content']['project']['project6']['image'] = $this->copyimagestobucket($merchant_id, $json['content']['project']['project6']['image']);
                    $json['content']['project']['project7']['image'] = $this->copyimagestobucket($merchant_id, $json['content']['project']['project7']['image']);
                }
            }
            $this->smarty->assign("merchant_link", $this->encrypt->encode($merchant_id));
            $this->smarty->assign("is_plan", $is_plan);
            $this->model->saveLiveJSON($merchant_id, json_encode($json), $this->user_id);
            $this->smarty->assign("display_url", $display_url);
            $this->smarty->assign("company_name", $this->session->get('company_name'));
            $this->smarty->assign("json", $json);
            $this->smarty->assign("type", $type);
            $cloud_front = getenv('CLOUD_FRONT');
            $this->smarty->assign("cloud_front", $cloud_front);
            $data = $this->smarty->fetch(VIEW . 'merchant/website/preview/' . $type . '.tpl');
            $data = str_replace('/assets/site-builder/live', getenv('CLOUD_FRONT'), $data);
            $folder = ($type == 'home') ? '' : $type . '/';
            $aws->putBucketFile($setting['merchant_domain'], $folder . 'index.html', $data, 'html');
            $this->published();
        } catch (Exception $e) {
            Sentry\captureException($e);
            SwipezLogger::error(__CLASS__, '[EW08]Error while publish webiste Error: for merchant id [' . $merchant_id . '] ' . $e->getMessage());
        }
    }

    function published()
    {
        try {
            $merchant_id = $this->session->get('merchant_id');
            $setting = $this->model->getMerchantWebsite($merchant_id);
            $this->smarty->assign("domain_cname", $setting['domain_cname']);
            $this->smarty->assign("type", 'setup');
            $this->smarty->assign("domain", $setting['merchant_domain']);
            $this->smarty->display(VIEW . 'merchant/website/setting/header.tpl');
            $this->smarty->display(VIEW . 'merchant/website/setting/published.tpl');
            $this->smarty->display(VIEW . 'merchant/website/setting/footer.tpl');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EW05]Error while build webiste Error: for merchant id [' . $merchant_id . '] ' . $e->getMessage());
        }
    }

    function save($path = NULL)
    {
        try {
            $merchant_id = $this->session->get('merchant_id');
            $json = $this->model->getStagingJSON($merchant_id);
            $json = json_decode($json, 1);
            if ($path == NULL) {
                $path = $_POST['path'];
            } else {
                $image = $this->uploadImage($_FILES['fileupload'], $merchant_id);
                $_POST['value'] = $image;
                $_POST['path'] = $path;
            }
            $path = explode('_', $path);
            $count = count($path);
            switch ($count) {
                case 1:
                    $json[$path[0]] = $_POST['value'];
                    break;
                case 2:
                    $json[$path[0]][$path[1]] = $_POST['value'];
                    break;
                case 3:
                    $json[$path[0]][$path[1]][$path[2]] = $_POST['value'];
                    break;
                case 4:
                    $json[$path[0]][$path[1]][$path[2]][$path[3]] = $_POST['value'];
                    break;
                case 5:
                    $json[$path[0]][$path[1]][$path[2]][$path[3]][$path[4]] = $_POST['value'];
                    break;
                case 6:
                    $json[$path[0]][$path[1]][$path[2]][$path[3]][$path[4]][$path[5]] = $_POST['value'];
                    break;
                case 7:
                    $json[$path[0]][$path[1]][$path[2]][$path[3]][$path[4]][$path[5]][$path[6]] = $_POST['value'];
                    break;
            }
            $this->model->updateStagingJSON($merchant_id, json_encode($json), $this->user_id);
            echo json_encode($_POST);
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EW09]Error while save webiste Error: for merchant id [' . $merchant_id . '] ' . $e->getMessage());
        }
    }

    function verifytxt()
    {
        try {
            require_once UTIL . 'SiteBuilderS3Bucket.php';
            $aws = new SiteBuilderS3Bucket('ap-south-1');
            $merchant_id = $this->session->get('merchant_id');
            $setting = $this->model->getMerchantWebsite($merchant_id);
            $status = 1;
            $domain[] = $setting['merchant_domain'];
            if (substr($setting['merchant_domain'], 0, 3) == 'www') {
                $domain[] = substr($setting['merchant_domain'], 4);
            } else {
                $domain[] = 'www.' . $setting['merchant_domain'];
            }
            foreach ($domain as $host) {
                $result = dns_get_record($host, DNS_TXT);
                if (!empty($result)) {
                    foreach ($result as $txt) {
                        if ($txt['txt'] == $setting['txt_text']) {
                            $status = 1;
                            break;
                        }
                    }
                }
            }
            if ($setting['status'] == 0) {
                if ($status == 1) {
                    $result = $aws->getBucketinfo($setting['merchant_domain']);
                    if ($result) {
                        SwipezLogger::info(__CLASS__, $setting['merchant_domain'] . ' bucket is already exist');
                        $status = 2;
                    } else {
                        $result = $aws->createBucket($setting['merchant_domain']);
                        $cname = $setting['merchant_domain'] . '.s3-website-ap-south-1.amazonaws.com';
                        $this->model->updateCname($merchant_id, $cname, 1, $this->user_id);
                    }
                }
            }
            echo $status;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EW10]Error while verifytxt Error: for merchant id [' . $merchant_id . '] ' . $e->getMessage());
        }
    }

    function verifydns()
    {
        try {
            $merchant_id = $this->session->get('merchant_id');
            $setting = $this->model->getMerchantWebsite($merchant_id);
            $status = 1;
            $domain[] = $setting['merchant_domain'];
            if (substr($setting['merchant_domain'], 0, 3) == 'www') {
                $domain[] = substr($setting['merchant_domain'], 4);
            } else {
                $domain[] = 'www.' . $setting['merchant_domain'];
            }
            foreach ($domain as $host) {
                $result = dns_get_record($host, DNS_CNAME);
                if (!empty($result)) {
                    foreach ($result as $txt) {
                        if ($txt['target'] == $setting['domain_cname']) {
                            $status = 1;
                            break;
                        }
                    }
                }
            }
            if ($status == 1) {
                $this->model->updateCname($merchant_id, $setting['domain_cname'], 2, $this->user_id);
            }
            echo $status;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EW11]Error while verifytxt Error: for merchant id [' . $merchant_id . '] ' . $e->getMessage());
        }
    }

    function copyimagestobucket($merchant_id, $file)
    {
        try {
            require_once UTIL . 'SiteBuilderS3Bucket.php';
            $aws = new SiteBuilderS3Bucket('ap-south-1');
            $ext = substr($file, strrpos($file, '.') + 1);
            $keyname = 'merchant/' . $merchant_id . '/' . time() . uniqid() . '.' . $ext;
            $aws->copyBucketfile(getenv('S3_LIVE_BUCKET'), $keyname, substr($file, strpos($file, getenv('S3_STAGING_BUCKET'))));
            return getenv('CLOUD_FRONT') . '/' . $keyname;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EW12]Error while verifytxt Error: for merchant id [' . $merchant_id . '] ' . $e->getMessage());
            return '';
        }
    }

    public function uploadImage($image_file, $merchant_id)
    {
        try {
            require_once UTIL . 'SiteBuilderS3Bucket.php';
            $aws = new SiteBuilderS3Bucket('ap-south-1');
            $staging_bucket = env('S3_STAGING_BUCKET');
            $filename = $image_file['name'];
            $ext = substr($filename, strrpos($filename, '.') + 1);
            $system_filename = time() . '.' . $ext;
            $keyname = $merchant_id . '/' . $system_filename;
            $result = $aws->putBucket($staging_bucket, $keyname, $image_file['tmp_name']);
            return $result;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E252]Error while uploading excel Error: for merchant id[' . $merchant_id . '] ' . $e->getMessage());
        }
    }
}
