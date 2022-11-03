<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\JsonLd;
use App\Model\Home;
use Log;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\MerchantPagesController;
use App\Model\Gst;
use DateTime;

class FreeBusinessToolsController extends Controller
{

    public function  free_business_tools()
    {
        SEOMeta::setTitle('Top Free business tools for Indian businesses | Swipez');
        SEOMeta::setDescription('Free online tools that businesses can use to improve the efficiency and productivity of their business.');
        SEOMeta::addKeyword(['Business Tool,Top business tools for Indian small and medium enterprises']);
        SEOMeta::addMeta('application-name', $value = 'Swipez GST return filing software', $name = 'name');
        SEOMeta::addMeta('name', $value = 'Swipez Billing software', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Automated GST filing solution for your business.', $name = 'itemprop');
        OpenGraph::setTitle('Top Free business tools for Indian businesses-Swipez');
        OpenGraph::setDescription('Free online tools that businesses can use to improve the efficiency and productivity of their business.');
        OpenGraph::setUrl('https://www.swipez.in/gst-filing');
        JsonLd::setTitle('Top Free business tools for Indian businesses-Swipez');
        JsonLd::setType('Product');
        JsonLd::setDescription('Free online tools that businesses can use to improve the efficiency and productivity of their business.');
        JsonLd::addValue('review', json_decode('[{"@type":"Review","author":"Jayesh Shah","datePublished":"2018-04-01","description":"Now I can easily send & reconcile our companies telephone and internet bills with multiple payment options to thousands of our customers via e-mail and SMS at the click of a button through Swipez.","name":"Value purchase","reviewRating":{"@type":"Rating","bestRating":"5","ratingValue":"1","worstRating":"1"}},{"@type":"Review","author":"Vikas Sankhla","datePublished":"2018-03-25","description":"We are now managing payments across our complete customer base along with timely pay outs for all franchisees across the country from one dashboard. My team has saved over hundred hours after adopting Swipez Billing.","name":"Value purchase","reviewRating":{"@type":"Rating","bestRating":"5","ratingValue":"4","worstRating":"1"}}]', 1));
        JsonLd::addValue('aggregateRating', json_decode('{"@type":"AggregateRating","ratingValue":"4.4","reviewCount":"89"}', 1));
        JsonLd::setImage('https://www.swipez.in/images/product/gst-filing.svg');
        $data['CAPTCHA_CLIENT_ID'] = env('V3_CAPTCHA_CLIENT_ID');
        $data['GOOGLE_AUTH_CLIENT_ID'] = env('GOOGLE_AUTH_CLIENT_ID');
        $data['javascript'] = array('register');
        $data['service_id'] = '9';
        $data['jsonld'] = true;
        Session::put('service_id', '9');
        return view('home/free_business_tools', $data);
    }
    public function Gst_Calculator()
    {
        SEOMeta::setTitle('Online GST calculator');
        SEOMeta::setDescription('A simple, ready-to-use online GST calculator to ensure seamless billing & invoicing. Accurate GST calculations to ensure seamless GST compliance and reconciliations for all your taxation needs.');
        SEOMeta::addKeyword(['GST calculator, online GST calculator, free GST calculator, easy GST calculator']);
        SEOMeta::addMeta('application-name', $value = 'Swipez Billing Software', $name = 'name');
        SEOMeta::addMeta('name', $value = 'Swipez Billing Software', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Download GST bill format online for free', $name = 'itemprop');
        OpenGraph::setTitle('GST calculator | Simple GST bill generator | Swipez');
        OpenGraph::setDescription('A simple, ready-to-use online GST calculator to ensure seamless billing & invoicing. Accurate GST calculations to ensure seamless GST compliance and reconciliations for all your taxation needs.');
        OpenGraph::setUrl("https://www.swipez.in/" . request()->path());
        JsonLd::setTitle('GST calculator');
        JsonLd::setType('Product');
        JsonLd::setDescription('A simple, ready-to-use online GST calculator to ensure seamless billing & invoicing. Accurate GST calculations to ensure seamless GST compliance and reconciliations for all your taxation needs.');
        JsonLd::addValue('review', json_decode('[{"@type": "Review","author": "Shumu Gupta","datePublished": "2018-04-01","description": "Swipez invoicing is flexible and perfect for our business needs. We have been able to customize the invoices and create it to meet out business requirements.","name": "Value purchase","reviewRating": {"@type": "Rating","bestRating": "5","ratingValue": "4","worstRating": "1"}},{"@type": "Review","author": "Debarshi Dutta","datePublished": "2018-03-25","description": "Invoicing is so much easier and faster. We have been using the free plan and its been perfect for our business needs.","name": "Value purchase","reviewRating": {"@type": "Rating","bestRating": "5","ratingValue": "5","worstRating": "1"}}]', 1));
        JsonLd::addValue('aggregateRating', json_decode('{"@type":"AggregateRating","ratingValue":"4.7","reviewCount":"139"}', 1));
        JsonLd::setImage('https://www.swipez.in/images/product/billing-software/invoice-formats.webp');

        $data['CAPTCHA_CLIENT_ID'] = env('V3_CAPTCHA_CLIENT_ID');
        $data['GOOGLE_AUTH_CLIENT_ID'] = env('GOOGLE_AUTH_CLIENT_ID');
        $data['javascript'] = array('register');
        $data['service_id'] = '2';
        $data['current_date'] = date('d M Y');
        $data['jsonld'] = true;
        $data['format_name'] = 'gst';
        $data['title'] = 'GST calculator';
        $data['description'] = 'A simple, ready-to-use online GST calculator to ensure seamless billing & invoicing. Accurate GST calculations to ensure seamless GST compliance and reconciliations for all your taxation needs.';

        return view('home/tools/gst_calculator', $data);
    }
    public function simple_interest_calculator()
    {
        SEOMeta::setTitle('Simple interest calculator | Simple GST bill generator | Swipez');
        SEOMeta::setDescription('Calculate the simple interest daily, monthly, or yearly in just a few clicks!');
        SEOMeta::addKeyword(['free simple interest calculator, S.I. calculator, online S.I. calculator, free S.I. calculator']);
        SEOMeta::addMeta('application-name', $value = 'Swipez Billing Software', $name = 'name');
        SEOMeta::addMeta('name', $value = 'Swipez Billing Software', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Calculate the simple interest daily, monthly, or yearly in just a few clicks!', $name = 'itemprop');
        OpenGraph::setTitle('Simple interest calculator | Simple GST bill generator | Swipez');
        OpenGraph::setDescription('Calculate the simple interest daily, monthly, or yearly in just a few clicks!');
        OpenGraph::setUrl("https://www.swipez.in/" . request()->path());
        JsonLd::setTitle('Simple interest calculator');
        JsonLd::setType('Product');
        JsonLd::setDescription('Calculate the simple interest daily, monthly, or yearly in just a few clicks!');
        JsonLd::addValue('review', json_decode('[{"@type": "Review","author": "Shumu Gupta","datePublished": "2018-04-01","description": "Swipez invoicing is flexible and perfect for our business needs. We have been able to customize the invoices and create it to meet out business requirements.","name": "Value purchase","reviewRating": {"@type": "Rating","bestRating": "5","ratingValue": "4","worstRating": "1"}},{"@type": "Review","author": "Debarshi Dutta","datePublished": "2018-03-25","description": "Invoicing is so much easier and faster. We have been using the free plan and its been perfect for our business needs.","name": "Value purchase","reviewRating": {"@type": "Rating","bestRating": "5","ratingValue": "5","worstRating": "1"}}]', 1));
        JsonLd::addValue('aggregateRating', json_decode('{"@type":"AggregateRating","ratingValue":"4.7","reviewCount":"139"}', 1));
        JsonLd::setImage('https://www.swipez.in/images/product/billing-software/invoice-formats.webp');

        $data['CAPTCHA_CLIENT_ID'] = env('V3_CAPTCHA_CLIENT_ID');
        $data['GOOGLE_AUTH_CLIENT_ID'] = env('GOOGLE_AUTH_CLIENT_ID');
        $data['javascript'] = array('register');
        $data['service_id'] = '2';
        $data['current_date'] = date('d M Y');
        $data['jsonld'] = true;
        $data['format_name'] = 'gst';
        $data['title'] = 'Simple interest calculator';
        $data['description'] = 'Calculate the simple interest daily, monthly, or yearly in just a few clicks!';

        return view('home/tools/simple_interest_calculator', $data);
    }
    public function compound_interest_calculator()
    {
        SEOMeta::setTitle('Online compound interest calculator | Compound interest generator | Swipez');
        SEOMeta::setDescription('Calculate the compound interest on your investments, savings & more! Accurate estimates on your investments in seconds to ensure an informed financial decision.');
        SEOMeta::addKeyword(['free compound interest calculator, online free compound interest calculator, free C.I calculator, online C.I. calculator']);
        SEOMeta::addMeta('application-name', $value = 'Swipez Billing Software', $name = 'name');
        SEOMeta::addMeta('name', $value = 'Swipez Billing Software', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Download GST bill format online for free', $name = 'itemprop');
        OpenGraph::setTitle('Online compound interest calculator | Compound interest generator | Swipez');
        OpenGraph::setDescription('Calculate the compound interest on your investments, savings & more! Accurate estimates on your investments in seconds to ensure an informed financial decision.');
        OpenGraph::setUrl("https://www.swipez.in/" . request()->path());
        JsonLd::setTitle('Online compound interest calculator');
        JsonLd::setType('Product');
        JsonLd::setDescription('Calculate the compound interest on your investments, savings & more! Accurate estimates on your investments in seconds to ensure an informed financial decision.');
        JsonLd::addValue('review', json_decode('[{"@type": "Review","author": "Shumu Gupta","datePublished": "2018-04-01","description": "Swipez invoicing is flexible and perfect for our business needs. We have been able to customize the invoices and create it to meet out business requirements.","name": "Value purchase","reviewRating": {"@type": "Rating","bestRating": "5","ratingValue": "4","worstRating": "1"}},{"@type": "Review","author": "Debarshi Dutta","datePublished": "2018-03-25","description": "Invoicing is so much easier and faster. We have been using the free plan and its been perfect for our business needs.","name": "Value purchase","reviewRating": {"@type": "Rating","bestRating": "5","ratingValue": "5","worstRating": "1"}}]', 1));
        JsonLd::addValue('aggregateRating', json_decode('{"@type":"AggregateRating","ratingValue":"4.7","reviewCount":"139"}', 1));
        JsonLd::setImage('https://www.swipez.in/images/product/billing-software/invoice-formats.webp');

        $data['CAPTCHA_CLIENT_ID'] = env('V3_CAPTCHA_CLIENT_ID');
        $data['GOOGLE_AUTH_CLIENT_ID'] = env('GOOGLE_AUTH_CLIENT_ID');
        $data['javascript'] = array('register');
        $data['service_id'] = '2';
        $data['current_date'] = date('d M Y');
        $data['jsonld'] = true;
        $data['format_name'] = 'gst';
        $data['title'] = 'Compound interest calculator';
        $data['description'] = 'Calculate the compound interest on your investments, savings & more!';

        return view('home/tools/compound_interest_calculator', $data);
    }
    public function gst_lookup()
    {

        SEOMeta::setTitle('GST number lookup & online verification | Online GST lookup | Swipez');
        SEOMeta::setDescription("A one-click verification of any GSTIN, the GSTIN/UIN holder's name, address, status, and more.");
        SEOMeta::addKeyword(['free GST lookup, free GSTIN lookup, online GSTIN verification, free GSTIN verification, online GST lookup, online GSTIN lookup']);
        SEOMeta::addMeta('application-name', $value = 'Swipez Billing Software', $name = 'name');
        SEOMeta::addMeta('name', $value = 'Swipez Billing Software', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Download GST bill format online for free', $name = 'itemprop');
        OpenGraph::setTitle('GST number lookup & online verification | Online GST lookup | Swipez');
        OpenGraph::setDescription("A one-click verification of any GSTIN, the GSTIN/UIN holder's name, address, status, and more.");
        OpenGraph::setUrl("https://www.swipez.in/" . request()->path());
        JsonLd::setTitle('GST number lookup & online verification');
        JsonLd::setType('Product');
        JsonLd::setDescription("A one-click verification of any GSTIN, the GSTIN/UIN holder's name, address, status, and more.");
        JsonLd::addValue('review', json_decode('[{"@type": "Review","author": "Shumu Gupta","datePublished": "2018-04-01","description": "Swipez invoicing is flexible and perfect for our business needs. We have been able to customize the invoices and create it to meet out business requirements.","name": "Value purchase","reviewRating": {"@type": "Rating","bestRating": "5","ratingValue": "4","worstRating": "1"}},{"@type": "Review","author": "Debarshi Dutta","datePublished": "2018-03-25","description": "Invoicing is so much easier and faster. We have been using the free plan and its been perfect for our business needs.","name": "Value purchase","reviewRating": {"@type": "Rating","bestRating": "5","ratingValue": "5","worstRating": "1"}}]', 1));
        JsonLd::addValue('aggregateRating', json_decode('{"@type":"AggregateRating","ratingValue":"4.7","reviewCount":"139"}', 1));
        JsonLd::setImage('https://www.swipez.in/images/product/billing-software/invoice-formats.webp');
        if (isset($_POST['gst_number'])) {
            $filereturn_error = '';
            $nature = '';
            $gstModel = new Gst();
            $apikey = $gstModel->getGstApiKey();
            $info = $this->getGSTInfo($_POST['gst_number'], $apikey);

            $info1 = $info['response'];
            $return_list = '';
            $filereturn_success = '';
            $lastfileperiod = '2014-01-01';

            $returnstatus = $this->getGSTReturnStatus($_POST['gst_number'], $apikey);
            if ($info1['status_code'] == 0) {
                $filereturn_error = $info1['error']['message'];
            } else {
                $nature = implode(',', $info1['nature']);
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
                    if ($lastfileperiod != '2014-01-01') {
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
                        if ($month > 0) {
                            $filereturn_error = 'Tax return not filed for ' . $month . $mtext;
                        } else {
                            $filereturn_success = 'Tax Return filing upto date';
                        }
                        // $this->smarty->assign("filereturn_error", $filereturn_error);
                    } else {
                        $filereturn_error = 'Tax return never filed';
                        // $this->smarty->assign("filereturn_error", $filereturn_error);
                    }
                } else {
                    $filereturn_success = 'Tax Return filing upto date';
                    // $this->smarty->assign("filereturn_success", $filereturn_success);
                }
                // $info['nature'] = implode(',', $info['nature']);
            }
            // dd( $return_list);
            $array = array('01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'May', '06' => 'Jun', '07' => 'Jul', '08' => 'Aug', '09' => 'Sep', '10' => 'Oct', '11' => 'Nov', '12' => 'Dec');
            $info1['nature'] = $nature;
            $data['info'] = $info1;
            $data['details'] = $return_list;
            $data['datarray'] = $array;
            $data['filereturn_error'] = $filereturn_error;
            $data['filereturn_success'] = $filereturn_success;
            $data['gstno'] = $_POST['gst_number'];
        }

        $data['CAPTCHA_CLIENT_ID'] = env('V3_CAPTCHA_CLIENT_ID');
        $data['GOOGLE_AUTH_CLIENT_ID'] = env('GOOGLE_AUTH_CLIENT_ID');
        $data['javascript'] = array('register');
        $data['service_id'] = '2';
        $data['current_date'] = date('d M Y');
        $data['jsonld'] = true;
        $data['captcha'] = true;
        $data['format_name'] = 'gst';
        $data['title'] = 'GST number lookup & online verification ';
        $data['description'] = "A one-click verification of any GSTIN, the GSTIN/UIN holder's name, address, status, and more.";

        return view('home/tools/gst_lookup', $data);
    }
    public function hsn_code_lookup()
    {

        SEOMeta::setTitle('Online HSN/SAC code search | One click HSN/SAC code lookup | Swipez');
        SEOMeta::setDescription("Look up a product's HSN code and the applicable GST with just a click! Ensure error-free GST invoicing & inventory management.");
        SEOMeta::addKeyword(['online HSN/SAC code lookup, free HSN/SAC code lookup, online HSN/SAC code verification, free HSN/SAC code verification']);
        SEOMeta::addMeta('application-name', $value = 'Swipez Billing Software', $name = 'name');
        SEOMeta::addMeta('name', $value = 'Swipez Billing Software', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Download GST bill format online for free', $name = 'itemprop');
        OpenGraph::setTitle('Online HSN/SAC code search | One click HSN/SAC code lookup | Swipez');
        OpenGraph::setDescription("Look up a product's HSN code and the applicable GST with just a click! Ensure error-free GST invoicing & inventory management.");
        OpenGraph::setUrl("https://www.swipez.in/" . request()->path());
        JsonLd::setTitle('Online HSN/SAC code search');
        JsonLd::setType('Product');
        JsonLd::setDescription("Look up a product's HSN code and the applicable GST with just a click! Ensure error-free GST invoicing & inventory management.");
        JsonLd::addValue('review', json_decode('[{"@type": "Review","author": "Shumu Gupta","datePublished": "2018-04-01","description": "Swipez invoicing is flexible and perfect for our business needs. We have been able to customize the invoices and create it to meet out business requirements.","name": "Value purchase","reviewRating": {"@type": "Rating","bestRating": "5","ratingValue": "4","worstRating": "1"}},{"@type": "Review","author": "Debarshi Dutta","datePublished": "2018-03-25","description": "Invoicing is so much easier and faster. We have been using the free plan and its been perfect for our business needs.","name": "Value purchase","reviewRating": {"@type": "Rating","bestRating": "5","ratingValue": "5","worstRating": "1"}}]', 1));
        JsonLd::addValue('aggregateRating', json_decode('{"@type":"AggregateRating","ratingValue":"4.7","reviewCount":"139"}', 1));
        JsonLd::setImage('https://www.swipez.in/images/product/billing-software/invoice-formats.webp');


        $data['CAPTCHA_CLIENT_ID'] = env('V3_CAPTCHA_CLIENT_ID');
        $data['GOOGLE_AUTH_CLIENT_ID'] = env('GOOGLE_AUTH_CLIENT_ID');
        $data['javascript'] = array('register');
        $data['service_id'] = '2';
        $data['current_date'] = date('d M Y');
        $data['jsonld'] = true;
        $data['format_name'] = 'gst';
        $data['title'] = 'Seamless HSN/SAC code search ';
        $data['description'] = "Not sure what HSN/SAC code to use for your items of sale? Look up a product's HSN code and the applicable GST with just a click!";

        return view('home/tools/hsn_sac_code_lookup', $data);
    }

    function getGSTInfo($gstin, $api_key)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://taxpayer.irisgst.com/api/search?gstin=" . $gstin,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => '',
            CURLOPT_HTTPHEADER => array(
                "apikey:" . $api_key
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            $res['status'] = 0;
            $res['error'] = $err;
        } else {
            $res['status'] = 1;
            $rows = json_decode($response, true);
            $res['response'] = $rows;
        }
        return $res;
    }
    function getGSTReturnStatus($gstin, $api_key)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://taxpayer.irisgst.com/api/returnstatus?gstin=" . $gstin,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => '',
            CURLOPT_HTTPHEADER => array(
                "apikey:" . $api_key
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            $res['status'] = 0;
            $res['error'] = $err;
        } else {
            $res['status'] = 1;
            $rows = json_decode($response, true);
            $res['response'] = $rows;
        }
        return $res;
    }
}
