<?php

namespace App\Http\Controllers;

use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\JsonLd;
use App\Model\Home;
use Log;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\MerchantPagesController;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->setCampaignId();
    }

    /**
     * Set campaign id from url path
     *
     * @return void
     */
    function setCampaignId()
    {
        if (isset($_SERVER['QUERY_STRING'])) {
            $link = $_SERVER['QUERY_STRING'];
            if ($link != '') {
                $unwantedChars = [';', ' or ', 'update', 'delete', 'select', 'insert'];
                foreach ($unwantedChars as $char) {
                    if (strpos($link, $char) !== false) {
                        Log::warning('Invalid character found in Registration campaign exec : Data: ' . $link);
                        header('Location: /404');
                        exit();
                    }
                }
                #ignore text after "utm_medium=ppc"
                if (preg_match('/.+?(?=(?i)utm_medium=ppc)/', $link, $matches)) {
                    $link = $matches[0] . 'utm_medium=ppc';
                }
                $homeModel = new Home();
                $campaign_id = $homeModel->getCampaignId($link);
                if ($campaign_id != false) {
                    setcookie('registration_campaign_id', $campaign_id, time() + (864000 * 30), "/"); // 86400 = 1 day
                }
            }
        }
    }

    /**
     * Returns view for Swipez homepage
     *
     * @return view
     */
    public function homepage()
    {

        if (config('app.partner.home_url')) {
            return redirect(config('app.partner.home_url'));
        }
        #check merchant subdomain exist
        // if (config('app.merchant_subdomain')) {
        //     $controller = new MerchantPagesController(config('app.merchant_subdomain'));
        //     return $controller->merchantIndex(config('app.merchant_subdomain'));
        // }
        SEOMeta::setTitle('Swipez | GST Billing Software - Free Payment Gateway Free Invoice Software in India');
        SEOMeta::setDescription('Swipez is a free billing and invoicing software for your business, allowing you to increase ROI with faster and smoother payment collections by payment gateway services.');
        SEOMeta::addKeyword(['billing software', 'free payment gateway', 'event registration', 'event ticketing solutions', 'venue booking software', 'url shortener', 'website builder', 'online invoice maker', 'online bill generator', 'free invoice generator', 'gst filing software', 'vendor payouts', 'payment link']);
        SEOMeta::addMeta('application-name', $value = 'Swipez', $name = 'name');
        SEOMeta::addMeta('name', $value = 'Swipez', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Products for online payment collections', $name = 'itemprop');
        OpenGraph::setTitle('Swipez | Products for online payment collections.');
        OpenGraph::setDescription('Easy to use online payment collection for businesses. Organize your day to day business operations, collect payments online faster and file your GST directly from one dashboard.');
        OpenGraph::setUrl('https://www.swipez.in');
        $data['CAPTCHA_CLIENT_ID'] = env('V3_CAPTCHA_CLIENT_ID');
        $data['GOOGLE_AUTH_CLIENT_ID'] = env('GOOGLE_AUTH_CLIENT_ID');
        $data['javascript'] = array('register');
        $data['disablejquery'] = true;
        $data['header_code'] = '<script type="application/ld+json">
        [{
            "@context": "https://schema.org",
            "@type": "WebSite",
            "name": "Swipez",
            "url": "https://www.swipez.in/",
            "description": "Free Billing software-Manage your customer data, billing, vendors and their expenses & more in one single dashboard. Collect payments online instantly using UPI, Wallets, Credit Card, Debit Card & Net Banking with Free Billing Software",
            "sameAs": [
                "https://www.facebook.com/Swipez-347240818812009",
                    "https://twitter.com/Swipezonsocial",
                    "https://www.instagram.com/swipez.in",
                    "https://www.youtube.com/channel/UC62hpFWFt-S8aReQGDsdoSg/featured",
                    "https://www.linkedin.com/company/swipez/"
            ],
            "sourceOrganization": {
                "@type": "Organization",
                "name": "Swipez",
                "description": "Free Billing software-Manage your customer data, billing, vendors and their expenses & more in one single dashboard. Collect payments online instantly using UPI, Wallets, Credit Card, Debit Card & Net Banking with Free Billing Software",
                "url": "https://www.swipez.in/",
                "founder": {
                    "@type": "Person",
                    "name": "Shuaid Lambe"
                },
                "sameAs": [
                     "https://www.facebook.com/Swipez-347240818812009",
                    "https://twitter.com/Swipezonsocial"
                ]
            }
        },
             {
                "@context": "https://schema.org",
                "@type": "Corporation",
                "name": "Swipez",
                "alternateName": "Billing Software Company",
                "url": "https://www.swipez.in/",
                "logo": "https://www.swipez.in/static/images/logo_default.png",
                "sameAs": [
                    "https://www.facebook.com/Swipez-347240818812009",
                    "https://twitter.com/Swipezonsocial",
                    "https://www.instagram.com/swipez.in",
                    "https://www.youtube.com/channel/UC62hpFWFt-S8aReQGDsdoSg/featured",
                    "https://www.linkedin.com/company/swipez/",
                    "https://www.swipez.in/"
        ]
        }
        ]
</script>';
        if (Session::has('service_id')) {
            $data['service_id'] = Session::get('service_id');
        } else {
            $data['service_id'] = '2';
        }
        return view('home/index', $data);
    }

    /**
     * Returns view for Swipez register
     *
     * @return view
     */
    public function register()
    {
        if (config('app.partner.login_url')) {
            return redirect(config('app.partner.login_url'));
        }
        SEOMeta::setTitle('Swipez | Merchant Register');
        SEOMeta::setDescription('Register on Swipez to collect payments, bulk invoice your customers, manage events. Business organization software for SMEs');
        OpenGraph::setTitle('Swipez | Merchant Register');
        OpenGraph::setDescription('Register on Swipez to collect payments, bulk invoice your customers, manage events. Business organization software for SMEs');
        OpenGraph::setUrl('https://www.swipez.in/merchant/register');
        $data['CAPTCHA_CLIENT_ID'] = env('V3_CAPTCHA_CLIENT_ID');
        $data['GOOGLE_AUTH_CLIENT_ID'] = env('GOOGLE_AUTH_CLIENT_ID');
        $data['javascript'] = array('register');
        if (Session::has('service_id')) {
            $data['service_id'] = Session::get('service_id');
        } else {
            $data['service_id'] = '2';
        }
        return view('home/register', $data);
    }

    /**
     * Returns view for billing landing page
     *
     * @return view
     */
    public function billing()
    {
        SEOMeta::setTitle('Free Billing Software | Invoicing Billing Software');
        SEOMeta::setDescription('Free Billing Software for hassle-free billing & invoicing. Automate payment reminders and online payment collections with ease. The centralized dashboard of Swipez supporting multiple payment options makes online billing effortless');
        SEOMeta::addKeyword(['billing software', 'best gst billing software', 'free billing software', 'invoicing software', 'gst billing software', 'professional online billing software', 'best online billing software', 'gst invoice software', 'free invoice software', 'free gst billing software', 'online gst billing software', 'online invoice maker', 'gst billing software price', 'billing software price']);
        SEOMeta::addMeta('application-name', $value = 'Swipez Billing software', $name = 'name');
        SEOMeta::addMeta('name', $value = 'Swipez Billing software', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Free Online Billing Software | Best Billing Software With Payment Gateway', $name = 'itemprop');
        OpenGraph::setTitle('Free Billing Software | Invoicing Billing Software');
        OpenGraph::setDescription('Free Billing Software for hassle-free billing & invoicing. Automate payment reminders and online payment collections with ease. The centralized dashboard of Swipez supporting multiple payment options makes online billing effortless');
        OpenGraph::setUrl('https://www.swipez.in/billing-software');
        JsonLd::setTitle('Free Billing Software | Invoicing Billing Software');
        JsonLd::setType('Product');
        JsonLd::setDescription('Free Billing Software for hassle-free billing & invoicing. Automate payment reminders and online payment collections with ease. The centralized dashboard of Swipez supporting multiple payment options makes online billing effortless');
        JsonLd::addValue('review', json_decode('[{"@type":"Review","author":"Jayesh Shah","datePublished":"2018-04-01","description":"Now I can easily send & reconcile our companies telephone and internet bills with multiple payment options to thousands of our customers via e-mail and SMS at the click of a button through Swipez.","name":"Value purchase","reviewRating":{"@type":"Rating","bestRating":"5","ratingValue":"1","worstRating":"1"}},{"@type":"Review","author":"Vikas Sankhla","datePublished":"2018-03-25","description":"We are now managing payments across our complete customer base along with timely pay outs for all franchisees across the country from one dashboard. My team has saved over hundred hours after adopting Swipez Billing.","name":"Value purchase","reviewRating":{"@type":"Rating","bestRating":"5","ratingValue":"4","worstRating":"1"}}]', 1));
        JsonLd::addValue('aggregateRating', json_decode('{"@type":"AggregateRating","ratingValue":"4.4","reviewCount":"89"}', 1));
        JsonLd::setImage('https://www.swipez.in/images/product/billing-software.svg');
        $data['jsonld'] = true;
        $data['CAPTCHA_CLIENT_ID'] = env('V3_CAPTCHA_CLIENT_ID');
        $data['GOOGLE_AUTH_CLIENT_ID'] = env('GOOGLE_AUTH_CLIENT_ID');
        $data['javascript'] = array('register');
        $data['service_id'] = '2';
        Session::put('service_id', '2');
        $data['disablejquery'] = true;
        return view('home/product/billing', $data);
    }

    /**
     * Returns view for payment collections landing page
     *
     * @return view
     */
    public function paymentcollections()
    {
        SEOMeta::setTitle('Swipez | Send invoice payment links, and generate bulk payment reminders for  payment collections');
        SEOMeta::setDescription('Create a payment link using invoicing software and share it over WhatsApp, Email, and SMS and collect payments from customers anytime from anywhere. Send automated invoice payment links to scale your business.');
        SEOMeta::addKeyword(['payment link', 'link payment', 'free payment link', 'form builder with online payments', 'invoicing with online payments', 'collect payment faster online', 'invoice payment link', 'how to create online payment link']);
        SEOMeta::addMeta('application-name', $value = 'Swipez Billing software with payment collections', $name = 'name');
        SEOMeta::addMeta('name', $value = 'Swipez Billing software with payment collections', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Online billing software with payment collections for your business.', $name = 'itemprop');
        OpenGraph::setTitle('Swipez | Send payment link, invoices or forms and collect payment faster from customers');
        OpenGraph::setDescription('Create a payment link, invoices or forms and share it over WhatsApp, Email, and SMS and collect payments from customers preferred channels instantly anywhere, anytime.');
        OpenGraph::setUrl('https://www.swipez.in/payment-collections');
        JsonLd::setTitle('Billing Software with Payment Collections');
        JsonLd::setType('Product');
        JsonLd::setDescription('Create a payment link, invoices or forms and share it over WhatsApp, Email, and SMS and collect payments from customers preferred channels instantly anywhere, anytime.');
        JsonLd::addValue('review', json_decode('[{"@type":"Review","author":"Jayesh Shah","datePublished":"2018-04-01","description":"Now I can easily send & reconcile our companies telephone and internet bills with multiple payment options to thousands of our customers via e-mail and SMS at the click of a button through Swipez.","name":"Value purchase","reviewRating":{"@type":"Rating","bestRating":"5","ratingValue":"1","worstRating":"1"}},{"@type":"Review","author":"Vikas Sankhla","datePublished":"2018-03-25","description":"We are now managing payments across our complete customer base along with timely pay outs for all franchisees across the country from one dashboard. My team has saved over hundred hours after adopting Swipez Billing.","name":"Value purchase","reviewRating":{"@type":"Rating","bestRating":"5","ratingValue":"4","worstRating":"1"}}]', 1));
        JsonLd::addValue('aggregateRating', json_decode('{"@type":"AggregateRating","ratingValue":"4.4","reviewCount":"89"}', 1));
        JsonLd::setImage('https://www.swipez.in/images/product/payment-collections.svg');
        $data['CAPTCHA_CLIENT_ID'] = env('V3_CAPTCHA_CLIENT_ID');
        $data['GOOGLE_AUTH_CLIENT_ID'] = env('GOOGLE_AUTH_CLIENT_ID');
        $data['javascript'] = array('register');
        $data['service_id'] = '2';
        $data['jsonld'] = true;
        $data['disablejquery'] = true;
        Session::put('service_id', '2');
        return view('home/product/paymentcollections', $data);
    }

    /**
     * Returns view for gst filing landing page
     *
     * @return view
     */
    public function gstfiling()
    {

        SEOMeta::setTitle('GST Filing Online Software - Simplified GST Return for Businesses');
        SEOMeta::setDescription('Swipez provides powerful GST Return Filing Software thoughtfully designed to automate your GST Compliance tasks, increase productivity and save time. Single dashboard for easy filing of GSTR1 and GSTR 3B for multiple GST numbers.');
        SEOMeta::addKeyword(['gst filing software, gst filing, gst return filing software, gst return software']);
        SEOMeta::addMeta('application-name', $value = 'Swipez GST return filing software', $name = 'name');
        SEOMeta::addMeta('name', $value = 'Swipez Billing software', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Automated GST filing solution for your business.', $name = 'itemprop');
        OpenGraph::setTitle('GST Filing Online Software - Simplified GST Return for Businesses');
        OpenGraph::setDescription('Swipez provides powerful GST Return Filing Software thoughtfully designed to automate your GST Compliance tasks, increase productivity and save time. Single dashboard for easy filing of GSTR1 and GSTR 3B for multiple GST numbers.');
        OpenGraph::setUrl('https://www.swipez.in/gst-filing');
        JsonLd::setTitle('GST filing solution');
        JsonLd::setType('Product');
        JsonLd::setDescription('Swipez provides powerful GST Return Filing Software thoughtfully designed to automate your GST Compliance tasks, increase productivity and save time. Single dashboard for easy filing of GSTR1 and GSTR 3B for multiple GST numbers.');
        JsonLd::addValue('review', json_decode('[{"@type":"Review","author":"Jayesh Shah","datePublished":"2018-04-01","description":"Now I can easily send & reconcile our companies telephone and internet bills with multiple payment options to thousands of our customers via e-mail and SMS at the click of a button through Swipez.","name":"Value purchase","reviewRating":{"@type":"Rating","bestRating":"5","ratingValue":"1","worstRating":"1"}},{"@type":"Review","author":"Vikas Sankhla","datePublished":"2018-03-25","description":"We are now managing payments across our complete customer base along with timely pay outs for all franchisees across the country from one dashboard. My team has saved over hundred hours after adopting Swipez Billing.","name":"Value purchase","reviewRating":{"@type":"Rating","bestRating":"5","ratingValue":"4","worstRating":"1"}}]', 1));
        JsonLd::addValue('aggregateRating', json_decode('{"@type":"AggregateRating","ratingValue":"4.4","reviewCount":"89"}', 1));
        JsonLd::setImage('https://www.swipez.in/images/product/gst-filing.svg');
        $data['CAPTCHA_CLIENT_ID'] = env('V3_CAPTCHA_CLIENT_ID');
        $data['GOOGLE_AUTH_CLIENT_ID'] = env('GOOGLE_AUTH_CLIENT_ID');
        $data['javascript'] = array('register');
        $data['service_id'] = '6';
        $data['jsonld'] = true;
        $data['disablejquery'] = true;
        Session::put('service_id', '6');
        return view('home/product/gstfiling', $data);
    }

    /**
     * Returns view for payouts landing page
     *
     * @return view
     */
    public function payouts()
    {
        SEOMeta::setTitle('Bulk Payouts |Bulk Vendor Payments|Send Bulk Payments');
        SEOMeta::setDescription('Bulk payout software helps for Vendor Management and Franchise Management. Send bulk Payments instantly with Swipez Bulk Payout and Vendor Management Software.');
        SEOMeta::addKeyword(['payouts', 'vendor payouts', 'money disbursement', 'franchise payout management', 'Vendor Management software', 'Vendor Payout Management', 'Send and Automate Bulk Payments']);
        SEOMeta::addMeta('application-name', $value = 'Swipez Payouts', $name = 'name');
        SEOMeta::addMeta('name', $value = 'Swipez Payouts', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Make payments from your current account with ease.', $name = 'itemprop');
        OpenGraph::setTitle('Simplify payments to suppliers, vendors, partners and salary payments with payouts');
        OpenGraph::setDescription('Bulk payout software helps for Vendor Management and Franchise Management. Send bulk Payments instantly with Swipez Bulk Payout and Vendor Management Software.');
        OpenGraph::setUrl('https://www.swipez.in/payouts');
        JsonLd::setTitle('Payouts solutions for businesses');
        JsonLd::setType('Product');
        JsonLd::setDescription('Bulk payout software helps for Vendor Management and Franchise Management. Send bulk Payments instantly with Swipez Bulk Payout and Vendor Management Software.');
        JsonLd::addValue('review', json_decode('[{"@type":"Review","author":"Jayesh Shah","datePublished":"2018-04-01","description":"Now I can easily send & reconcile our companies telephone and internet bills with multiple payment options to thousands of our customers via e-mail and SMS at the click of a button through Swipez.","name":"Value purchase","reviewRating":{"@type":"Rating","bestRating":"5","ratingValue":"1","worstRating":"1"}},{"@type":"Review","author":"Vikas Sankhla","datePublished":"2018-03-25","description":"We are now managing payments across our complete customer base along with timely pay outs for all franchisees across the country from one dashboard. My team has saved over hundred hours after adopting Swipez Billing.","name":"Value purchase","reviewRating":{"@type":"Rating","bestRating":"5","ratingValue":"4","worstRating":"1"}}]', 1));
        JsonLd::addValue('aggregateRating', json_decode('{"@type":"AggregateRating","ratingValue":"4.4","reviewCount":"89"}', 1));
        JsonLd::setImage('https://www.swipez.in/images/product/billing-software.svg');
        $data['CAPTCHA_CLIENT_ID'] = env('V3_CAPTCHA_CLIENT_ID');
        $data['GOOGLE_AUTH_CLIENT_ID'] = env('GOOGLE_AUTH_CLIENT_ID');
        $data['javascript'] = array('register');
        $data['service_id'] = '10';
        $data['jsonld'] = true;
        Session::put('service_id', '10');
        return view('home/product/payouts', $data);
    }

    /**
     * Returns view for form builder landing page
     *
     * @return view
     */
    public function formbuilder()
    {
        SEOMeta::setTitle('Free Online Form builder | Form creator with online payment collection - Swipez');
        SEOMeta::setDescription('Easy to share free online form creator for businesses like Events, Surveys, Lead capture, Auto-generated GST invoice feature');
        SEOMeta::addKeyword(['form builder', 'form creator', 'form builder online payments', 'data collection']);
        SEOMeta::addMeta('application-name', $value = 'Swipez Online form builder', $name = 'name');
        SEOMeta::addMeta('name', $value = 'Swipez  Online form builder', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Easy to share free online form creator for businesses like Events, Surveys, Lead capture, Auto-generated GST invoice feature', $name = 'itemprop');
        OpenGraph::setTitle('Simplify payments to suppliers, vendors, partners and salary payments with payouts');
        OpenGraph::setDescription('Easy to share free online form creator for businesses like Events, Surveys, Lead capture, Auto-generated GST invoice feature');
        OpenGraph::setUrl('https://www.swipez.in/online-form-builder');
        JsonLd::setTitle('Free online form builder');
        JsonLd::setType('Product');
        JsonLd::setDescription('Easy to share free online form creator for businesses like Events, Surveys, Lead capture, Auto-generated GST invoice feature');
        JsonLd::addValue('review', json_decode('[{"@type":"Review","author":"Jayesh Shah","datePublished":"2018-04-01","description":"Now I can easily send & reconcile our companies telephone and internet bills with multiple payment options to thousands of our customers via e-mail and SMS at the click of a button through Swipez.","name":"Value purchase","reviewRating":{"@type":"Rating","bestRating":"5","ratingValue":"1","worstRating":"1"}},{"@type":"Review","author":"Vikas Sankhla","datePublished":"2018-03-25","description":"We are now managing payments across our complete customer base along with timely pay outs for all franchisees across the country from one dashboard. My team has saved over hundred hours after adopting Swipez Billing.","name":"Value purchase","reviewRating":{"@type":"Rating","bestRating":"5","ratingValue":"4","worstRating":"1"}}]', 1));
        JsonLd::addValue('aggregateRating', json_decode('{"@type":"AggregateRating","ratingValue":"4.4","reviewCount":"89"}', 1));
        JsonLd::setImage('https://www.swipez.in/images/product/online-form-builder.svg');
        $data['CAPTCHA_CLIENT_ID'] = env('V3_CAPTCHA_CLIENT_ID');
        $data['GOOGLE_AUTH_CLIENT_ID'] = env('GOOGLE_AUTH_CLIENT_ID');
        $data['javascript'] = array('register');
        $data['service_id'] = '10';
        $data['jsonld'] = true;
        Session::put('service_id', '10');
        return view('home/product/formbuilder', $data);
    }

    /**
     * Returns view for expense landing page
     *
     * @return view
     */
    public function expenses()
    {
        SEOMeta::setTitle('Swipez | Manage expenses to understand your business spends in seconds');
        SEOMeta::setDescription('Track expenses, create purchase orders and make payouts. Simplify purchases for your business, improve expense reporting and save costs.');
        SEOMeta::addKeyword(['expense management software', 'expense management system', 'expense report software', 'expense tracking software']);
        SEOMeta::addMeta('application-name', $value = 'Swipez Payouts', $name = 'name');
        SEOMeta::addMeta('name', $value = 'Swipez Payouts', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Manage expenses to understand your business spends in seconds.', $name = 'itemprop');
        OpenGraph::setTitle('Manage expenses to understand your business spends in seconds.');
        OpenGraph::setDescription('Track expenses, create purchase orders and make payouts. Simplify purchases for your business, improve expense reporting and save costs.');
        OpenGraph::setUrl('https://www.swipez.in/expenses');
        JsonLd::setTitle('Manage expenses to understand your business spends in seconds.');
        JsonLd::setType('Product');
        JsonLd::setDescription('Track expenses, create purchase orders and make payouts. Simplify purchases for your business, improve expense reporting and save costs.');
        JsonLd::addValue('review', json_decode('[{"@type":"Review","author":"Jayesh Shah","datePublished":"2018-04-01","description":"Now I can easily send & reconcile our companies telephone and internet bills with multiple payment options to thousands of our customers via e-mail and SMS at the click of a button through Swipez.","name":"Value purchase","reviewRating":{"@type":"Rating","bestRating":"5","ratingValue":"1","worstRating":"1"}},{"@type":"Review","author":"Vikas Sankhla","datePublished":"2018-03-25","description":"We are now managing payments across our complete customer base along with timely pay outs for all franchisees across the country from one dashboard. My team has saved over hundred hours after adopting Swipez Billing.","name":"Value purchase","reviewRating":{"@type":"Rating","bestRating":"5","ratingValue":"4","worstRating":"1"}}]', 1));
        JsonLd::addValue('aggregateRating', json_decode('{"@type":"AggregateRating","ratingValue":"4.4","reviewCount":"89"}', 1));
        JsonLd::setImage('https://www.swipez.in/images/product/billing-software.svg');
        $data['CAPTCHA_CLIENT_ID'] = env('V3_CAPTCHA_CLIENT_ID');
        $data['GOOGLE_AUTH_CLIENT_ID'] = env('GOOGLE_AUTH_CLIENT_ID');
        $data['javascript'] = array('register');
        $data['service_id'] = '12';
        $data['jsonld'] = true;
        $data['disablejquery'] = true;
        Session::put('service_id', '12');
        return view('home/product/expense', $data);
    }

    /**
     * Returns view for events landing page
     *
     * @return view
     */
    public function event()
    {
        SEOMeta::setTitle('Swipez | Event Registration Software - Free Event Registration System');
        SEOMeta::setDescription('Organized your events & conferences systematically with help of Swipez’s free event registration software. We offer the complete platform you need to make your event a hit with a free and easy to use platform.');
        SEOMeta::addKeyword(['event ticketing solutions', 'event registration', 'online event registration', 'event registration software', 'online event management software', 'event registration platforms', 'free event planning software', 'online event registration platforms']);
        SEOMeta::addMeta('application-name', $value = 'Swipez Event registration software', $name = 'name');
        SEOMeta::addMeta('name', $value = 'Swipez Event registration software', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Online event registration and ticketing platform', $name = 'itemprop');
        OpenGraph::setTitle('Online event registration and ticketing platform');
        OpenGraph::setDescription('A free event registration software that lets you create an event effortlessly in minutes,collect registrations and customer information.No commissions charges per ticket sold. ');
        OpenGraph::setUrl('https://www.swipez.in/event-registration');
        JsonLd::setTitle('Event Ticketing Solutions');
        JsonLd::setType('Product');
        JsonLd::setDescription('Create events, manage ticket sales, promote, and collate customer information for future events and more.');
        JsonLd::setImage('https://www.swipez.in/images/product/online-event-registration.svg');
        JsonLd::addValue('review', json_decode('[{"@type":"Review","author":"Avinash Agarwal","datePublished":"2018-04-01","description":"We have hosted multiple events with no issues over last couple of years. The support team at Swipez have been great in responding quickly and solving our queries effectively.","name":"Value purchase","reviewRating":{"@type":"Rating","bestRating":"5","ratingValue":"1","worstRating":"1"}},{"@type":"Review","author":"Aniruddha Patil","datePublished":"2018-03-25","description":"From creating events to collecting ticket costs online it has been a breeze. The flexibility provided by the event creation tool is perfect, it has been able to meet our every need.","name":"Value purchase","reviewRating":{"@type":"Rating","bestRating":"5","ratingValue":"4","worstRating":"1"}}]', 1));
        JsonLd::addValue('aggregateRating', json_decode('{"@type":"AggregateRating","ratingValue":"4.4","reviewCount":"89"}', 1));
        $data['jsonld'] = true;
        $data['CAPTCHA_CLIENT_ID'] = env('V3_CAPTCHA_CLIENT_ID');
        $data['GOOGLE_AUTH_CLIENT_ID'] = env('GOOGLE_AUTH_CLIENT_ID');
        $data['javascript'] = array('register');
        $data['service_id'] = '4';
        $data['disablejquery'] = true;
        Session::put('service_id', '4');
        return view('home/product/event', $data);
    }

    /**
     * Returns view for booking calendar landing page
     *
     * @return view
     */
    public function venuebooking()
    {
        SEOMeta::setTitle('Swipez | Online Venue Booking & Reservation Software for Corporate & Sports Venues');
        SEOMeta::setDescription('Swipez venue booking & reservation scheduling software is best for the rooms, studios, courts and places at your venue. Increase monetization of your venues by streamlining processes as well as communications.');
        SEOMeta::addKeyword(['venue booking software', 'venue booking system', 'online venue booking system', 'venue reservation system', 'event venue booking software', 'venue management system', 'venue booking management system']);
        SEOMeta::addMeta('application-name', $value = 'Swipez Venue booking software', $name = 'name');
        SEOMeta::addMeta('name', $value = 'Swipez Venue booking software', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Venue booking software to manage timeslot bookings', $name = 'itemprop');
        OpenGraph::setTitle('Venue booking software to manage timeslot bookings');
        OpenGraph::setDescription('Swipez venue booking software is an easy to use online venue management system. Businesses can manage bookings, rooms, studios, courts and calendars at their venue.');
        OpenGraph::setUrl('https://www.swipez.in/venue-booking-software');
        JsonLd::setTitle('Venue Booking Software');
        JsonLd::setType('Product');
        JsonLd::setDescription('Manage time slots across one or many venues, schedule appointments and publish your booking calendar to your customers.');
        JsonLd::setImage('https://www.swipez.in/images/product/venue-booking-software.svg');
        JsonLd::addValue('review', json_decode('[{"@type":"Review","author":"Mahesh Shelote","datePublished":"2018-04-01","description":"Earlier time slot bookings for all our facilities & venues was via phone calls and tracked manually on a register. Now with the booking calendar our residents book a facility online within minutes.","name":"Value purchase","reviewRating":{"@type":"Rating","bestRating":"5","ratingValue":"4","worstRating":"1"}},{"@type":"Review","author":"Lokesh Sonawane","datePublished":"2018-03-25","description":"Time slot bookings for our badminton courts is now completely online. Our players are now able to view availability, book a time slot, schedule a coaching session and pay us online via their mobile phones","name":"Value purchase","reviewRating":{"@type":"Rating","bestRating":"5","ratingValue":"4","worstRating":"1"}}]', 1));
        JsonLd::addValue('aggregateRating', json_decode('{"@type":"AggregateRating","ratingValue":"4.4","reviewCount":"89"}', 1));
        $data['jsonld'] = true;
        $data['CAPTCHA_CLIENT_ID'] = env('V3_CAPTCHA_CLIENT_ID');
        $data['GOOGLE_AUTH_CLIENT_ID'] = env('GOOGLE_AUTH_CLIENT_ID');
        $data['javascript'] = array('register');
        $data['service_id'] = '5';
        $data['disablejquery'] = true;
        Session::put('service_id', '5');
        return view('home/product/venuebooking', $data);
    }

    /**
     * Returns view for website builder landing page
     *
     * @return view
     */
    public function websitebuilder()
    {
        SEOMeta::setTitle('Swipez | Free Website Builder - Best Website Maker');
        SEOMeta::setDescription('Want to build your own good looking dynamic, fully responsive website? Explore Swipez’s Free Website Builder that is very easy to use and customize a fully functional website at few clicks.');
        SEOMeta::addKeyword(['website builder', 'free website builder', 'website maker', 'free website maker', 'best free website builder']);
        SEOMeta::addMeta('application-name', $value = 'Swipez Website builder', $name = 'name');
        SEOMeta::addMeta('name', $value = 'Swipez Website builder', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Swipez | Free Website Builder - Website Maker', $name = 'itemprop');
        OpenGraph::setTitle('Swipez | Free Website Builder - Website Maker');
        OpenGraph::setDescription('Create your free website today using our easy to use Website builder, no coding skills required. All the tools needed to create a professional website in minutes.');
        OpenGraph::setUrl('https://www.swipez.in/website-builder');
        JsonLd::setTitle('Swipez | Free Website Builder - Website Maker');
        JsonLd::setType('Product');
        JsonLd::setDescription('Create your free website today using our easy to use Website builder, no coding skills required. All the tools needed to create a professional website in minutes.');
        JsonLd::setImage('https://www.swipez.in/images/product/online-website-builder.svg');
        JsonLd::addValue('review', json_decode('[{"@type":"Review","author":"Harish Boardake","datePublished":"2018-04-01","description":"We were able to create our company website within a day. Our customers now pay online and renew their internet packages from our company website.","name":"Value purchase","reviewRating":{"@type":"Rating","bestRating":"5","ratingValue":"1","worstRating":"1"}},{"@type":"Review","author":"Victor Cardoz","datePublished":"2018-03-25","description":"It was easy to create a professional website for our consultancy services. Our website has helped us attract clients via online search.","name":"Value purchase","reviewRating":{"@type":"Rating","bestRating":"5","ratingValue":"4","worstRating":"1"}}]', 1));
        JsonLd::addValue('aggregateRating', json_decode('{"@type":"AggregateRating","ratingValue":"4.4","reviewCount":"89"}', 1));
        $data['jsonld'] = true;
        $data['CAPTCHA_CLIENT_ID'] = env('V3_CAPTCHA_CLIENT_ID');
        $data['GOOGLE_AUTH_CLIENT_ID'] = env('GOOGLE_AUTH_CLIENT_ID');
        $data['javascript'] = array('register');
        $data['service_id'] = '7';
        Session::put('service_id', '7');
        return view('home/product/website', $data);
    }

    /**
     * Returns view for url shortener landing page
     *
     * @return void
     */
    public function urlshortener()
    {
        SEOMeta::setTitle('Swipez | Free Custom URL Shortener - Link & URL Shortener and Short URL Generator');
        SEOMeta::setDescription('Easily shorten and generate your custom URL and share trusted URLs for your business with Swipez. Our URL shortener helps you send concise messages to your customers and gain remarkable business insights.');
        SEOMeta::addKeyword(['url shortener', 'link shortener', 'custom url shortener']);
        SEOMeta::addMeta('application-name', $value = 'Swipez URL shortener', $name = 'name');
        SEOMeta::addMeta('name', $value = 'Swipez URL shortener', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Swipez | Free Custom URL Shortener - Link & URL Shortener', $name = 'itemprop');
        OpenGraph::setTitle('Swipez | Free Custom URL Shortener - Link & URL Shortener');
        OpenGraph::setDescription('Swipez lets you shorten, generate, track and share secured links for your business. Use our URL shortener with custom domains to maximize the impact of your digital communications.');
        OpenGraph::setUrl('https://www.swipez.in/url-shortener');
        JsonLd::setTitle('Url Shortener');
        JsonLd::setType('Product');
        JsonLd::setDescription('Shorten long URLs instantaneously. Track, manage and free up characters in your customer communications - unleash the power of your links.');
        JsonLd::setImage('https://www.swipez.in/images/product/url-shortener.svg');
        JsonLd::addValue('review', json_decode('[{"@type":"Review","author":"Chandra Gupta","datePublished":"2018-04-01","description":"We have seamlessly added URL shortening capability to our technology stack. Now for all our client communications we are able to create and track shortened links the via the Swipez URL shortener.","name":"Value purchase","reviewRating":{"@type":"Rating","bestRating":"5","ratingValue":"1","worstRating":"1"}},{"@type":"Review","author":"Viren Hundekari","datePublished":"2018-03-25","description":"All our internet and telcom bills are now sent to our customers as a short link via SMS. This has reduced late payments by our customers significantly. It is now simple to send new offers and plans to our customers as well","name":"Value purchase","reviewRating":{"@type":"Rating","bestRating":"5","ratingValue":"4","worstRating":"1"}}]', 1));
        JsonLd::addValue('aggregateRating', json_decode('{"@type":"AggregateRating","ratingValue":"4.4","reviewCount":"89"}', 1));
        $data['CAPTCHA_CLIENT_ID'] = env('V3_CAPTCHA_CLIENT_ID');
        $data['GOOGLE_AUTH_CLIENT_ID'] = env('GOOGLE_AUTH_CLIENT_ID');
        $data['javascript'] = array('register');
        $data['service_id'] = '2';
        $data['jsonld'] = true;
        Session::put('service_id', '2');
        return view('home/product/urlshortener', $data);
    }


    /**
     * Returns view for inventory management landing page
     *
     * @return view
     */
    public function inventorymanagement()
    {
        SEOMeta::setTitle('Best Free Inventory Management Software | Swipez Inventory Management System | Small Business');
        SEOMeta::setDescription('Swipez is the worlds easiest inventory management software.  Its the perfect inventory solution for growing businesses looking to track and manage their inventory, products, clients and other assets all in one place.');
        SEOMeta::addKeyword(['inventory management software', 'best gst inventory management software', 'free inventory management software', 'gst inventory management software', 'professional online inventory management software', 'best online inventory management software', 'free gst inventory management software', 'online gst inventory management software', 'inventory management price']);
        SEOMeta::addMeta('application-name', $value = 'Swipez Inventory management software', $name = 'name');
        SEOMeta::addMeta('name', $value = 'Swipez Inventory management software', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Online Inventory management software & stock keeping system for your business.', $name = 'itemprop');
        OpenGraph::setTitle('Online inventory management software & billing system for your business.');
        OpenGraph::setDescription('Swipez free inventory management offers a comprehensive stock keeping solution for your business. From managing billing to stock keeping.');
        OpenGraph::setUrl('https://www.swipez.in/inventory-management-software');
        JsonLd::setTitle('Best Free Inventory Management Software | Swipez Inventory Management System | Small Business');
        JsonLd::setType('Product');
        JsonLd::setDescription('Take advantage of Swipez Billing software, you can send online payment reminders & email invoices with ease. The centralized dashboard of Swipez featuring online & offline payment system makes online bills – as painless as possible.');
        JsonLd::addValue('review', json_decode('[{"@type":"Review","author":"Jayesh Shah","datePublished":"2018-04-01","description":"Now I can easily send & reconcile our companies telephone and internet bills with multiple payment options to thousands of our customers via e-mail and SMS at the click of a button through Swipez.","name":"Value purchase","reviewRating":{"@type":"Rating","bestRating":"5","ratingValue":"1","worstRating":"1"}},{"@type":"Review","author":"Vikas Sankhla","datePublished":"2018-03-25","description":"We are now managing payments across our complete customer base along with timely pay outs for all franchisees across the country from one dashboard. My team has saved over hundred hours after adopting Swipez Billing.","name":"Value purchase","reviewRating":{"@type":"Rating","bestRating":"5","ratingValue":"4","worstRating":"1"}}]', 1));
        JsonLd::addValue('aggregateRating', json_decode('{"@type":"AggregateRating","ratingValue":"4.4","reviewCount":"89"}', 1));
        JsonLd::setImage('https://www.swipez.in/images/product/inventory-management-software.svg');
        $data['jsonld'] = true;
        $data['CAPTCHA_CLIENT_ID'] = env('V3_CAPTCHA_CLIENT_ID');
        $data['GOOGLE_AUTH_CLIENT_ID'] = env('GOOGLE_AUTH_CLIENT_ID');
        $data['javascript'] = array('register');
        $data['service_id'] = '15';
        $data['disablejquery'] = true;
        Session::put('service_id', '15');
        return view('home/product/inventory', $data);
    }

    /**
     * Returns view for cable industry landing page
     *
     * @return void
     */
    public function cable()
    {
        SEOMeta::setTitle('Swipez | Free Cable TV Billing Software - cable tv accounting software');
        SEOMeta::setDescription('Swipez’s cable TV billing software is designed to manage subscriber billing for cable TV businesses. Our cable TV billing software helps you avoid hassles of late payments by generating auto reminders.');
        SEOMeta::addKeyword(['billing software for cable operators', 'cable operator billing software', 'cable operator software', 'billing software for cable tv operators', 'cable tv billing software', 'cable tv accounting software', 'cable tv billing software free', 'online invoice maker for cable operator', 'cable tv operator billing']);
        SEOMeta::addMeta('name', $value = 'Swipez | Free Billing Software For Cable Operators | Cable TV Billing Software', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = '"Swipez is a user-friendly cable management software.Allowing cable operators to manage billing as per customer channel package selection thus increasing ease of business.', $name = 'itemprop');
        OpenGraph::setTitle('Swipez | Free Billing Software For Cable Operators | Cable TV Billing Software');
        OpenGraph::setDescription('Swipez is a user-friendly cable management software. Allowing cable operators to manage billing as per customer channel package selection thus increasing ease of business.');
        OpenGraph::setUrl('https://www.swipez.in/billing-software-for-cable-operator');
        JsonLd::setTitle('Billing software for cable operators');
        JsonLd::setType('Product');
        JsonLd::setDescription('Send invoices online, set automatic follow-up reminders, and balance your books. Our billing software helps you collect faster via online payment modes.');
        JsonLd::setImage('https://www.swipez.in/images/product/billing-software/industry/cable-operator.svg');
        JsonLd::addValue('aggregateRating', json_decode('{"@type":"AggregateRating","ratingValue":"4.4","reviewCount":"89"}', 1));
        Session::put('service_id', '8');

        $data['CAPTCHA_CLIENT_ID'] = env('V3_CAPTCHA_CLIENT_ID');
        $data['GOOGLE_AUTH_CLIENT_ID'] = env('GOOGLE_AUTH_CLIENT_ID');
        $data['javascript'] = array('register');
        $data['service_id'] = '8';
        $data['jsonld'] = true;
        return view('home/industry/cable', $data);
    }

    /**
     * Returns view for franchise industry landing page
     *
     * @return void
     */
    public function franchise()
    {
        SEOMeta::setTitle('Swipez | Billing Software For Franchise Business Management | Free Franchise Business Software');
        SEOMeta::setDescription('Swipez billing software is designed to manage customer’s payments in franchise business on a single dashboard. Likewise, free franchise business management software splits collected revenue in percentages in between the respective parties determined by a franchisor.');
        SEOMeta::addKeyword(['billing software for franchise business', 'gst billing software for franchise business', 'franchise business billing software', 'free billing software for franchise business', 'franchise business accounting software', 'invoicing software for franchise business', 'gst billing software for franchise business', 'free gst billing software for franchise business', 'free franchise business management software', 'free franchise business management system', 'free franchise business billing software', 'franchise business software', 'franchise business management solutions']);
        SEOMeta::addMeta('name', $value = 'Swipez | Billing Software For Franchise Business | Free Franchise Business Software', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Swipez billing software for franchise business helps manage all your franchisees billing centrally with ease.Automatically split revenue collected between your brand and franchise.', $name = 'itemprop');
        OpenGraph::setTitle('Swipez | Billing Software For Franchise Business | Free Franchise Business Software');
        OpenGraph::setDescription('Swipez billing software for franchise business helps manage all your franchisees billing centrally with ease.Automatically split revenue collected between your brand and franchise.');
        OpenGraph::setUrl('https://www.swipez.in/billing-software-for-franchise-business');
        JsonLd::setTitle('Billing software for franchise business');
        JsonLd::setType('Product');
        JsonLd::setDescription('Strengthening franchisee networks by virtue of streamlining business operations using invoicing software.');
        JsonLd::setImage('https://www.swipez.in/images/product/billing-software/industry/franchise-business.svg');
        JsonLd::addValue('aggregateRating', json_decode('{"@type":"AggregateRating","ratingValue":"4.5","reviewCount":"95"}', 1));
        Session::put('service_id', '2');

        $data['CAPTCHA_CLIENT_ID'] = env('V3_CAPTCHA_CLIENT_ID');
        $data['GOOGLE_AUTH_CLIENT_ID'] = env('GOOGLE_AUTH_CLIENT_ID');
        $data['javascript'] = array('register');
        $data['service_id'] = '2';
        $data['jsonld'] = true;
        return view('home/industry/franchise', $data);
    }

    /**
     * Returns view for isp industry landing page
     *
     * @return void
     */
    public function isp()
    {
        SEOMeta::setTitle('Swipez | GST Billing Software for internet service providers | ISP billing software free');
        SEOMeta::setDescription('Swipez GST billing Software for ISP - Internet Service Providers, helps organise customer information, plans and billing. Our free billing software helps manage invoicing; payment reminders & reconcile offline & online payments.');
        SEOMeta::addKeyword(['billing software for isp', 'billing software for internet service providers', 'gst billing software for internet service providers', 'internet service providers billing software', 'free billing software for internet service providers', 'internet service providers accounting software', 'invoicing software for internet service providers', 'free isp management software', 'free isp management system', 'free isp billing software']);
        SEOMeta::addMeta('name', $value = 'Billing software for ISP', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Billing solution for ISP with custom integrations', $name = 'itemprop');

        OpenGraph::setTitle('Billing software for ISP');
        OpenGraph::setDescription('Swipez billing software for Internet Service Providers, organises customer data and billing. Our free billing software manages invoicing, payment reminders and online payments.');
        OpenGraph::setUrl('https://www.swipez.in/billing-software-for-isp-telcos');
        JsonLd::setTitle('Billing software for internet service providers ');
        JsonLd::setType('Product');
        JsonLd::setDescription('Optimizing the most precious business aspect for a utility provider - Timely revenue collections.');
        JsonLd::setImage('https://www.swipez.in/images/product/billing-software/industry/isp.svg');
        JsonLd::addValue('aggregateRating', json_decode('{"@type":"AggregateRating","ratingValue":"4.5","reviewCount":"99"}', 1));
        Session::put('service_id', '2');

        $data['CAPTCHA_CLIENT_ID'] = env('V3_CAPTCHA_CLIENT_ID');
        $data['GOOGLE_AUTH_CLIENT_ID'] = env('GOOGLE_AUTH_CLIENT_ID');
        $data['javascript'] = array('register');
        $data['service_id'] = '2';
        $data['jsonld'] = true;
        return view('home/industry/isp', $data);
    }

    /**
     * Returns view for education industry landing page
     *
     * @return void
     */
    public function education()
    {
        SEOMeta::setTitle('Free GST Billing Software for Schools and Colleges | Swipez');
        SEOMeta::setDescription('Swipez billing Software for schools and colleges helps in automating the entire process of students from admissions, courses selection, fee collections to manage time slot bookings for different available facilities');
        SEOMeta::addKeyword(['billing software for schools', 'free gst billing software for colleges', 'invoicing software for schools and colleges', 'online billing software for schools', 'free billing software for school students']);
        SEOMeta::addMeta('name', $value = 'Free GST Billing Software for Schools and Colleges | Swipez', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Swipez billing Software for schools and colleges helps in automating the entire process of students from admissions, courses selection, fee collections to manage time slot bookings for different available facilitie', $name = 'itemprop');

        OpenGraph::setTitle('Free GST Billing Software for Schools and Colleges | Swipez');
        OpenGraph::setDescription('Swipez billing Software for schools and colleges helps in automating the entire process of students from admissions, courses selection, fee collections to manage time slot bookings for different available facilitie');
        OpenGraph::setUrl('https://www.swipez.in/billing-software-for-school');
        JsonLd::setTitle('Billing software for education institute');
        JsonLd::setType('Product');
        JsonLd::setDescription('Automate admissions, course selection, fee collections for students. Manage time slots bookings for different facilities and more');
        JsonLd::setImage('https://www.swipez.in/images/product/billing-software/industry/education.svg');
        JsonLd::addValue('aggregateRating', json_decode('{"@type":"AggregateRating","ratingValue":"4.5","reviewCount":"99"}', 1));

        //Set service as billing software
        Session::put('service_id', '2');

        $data['CAPTCHA_CLIENT_ID'] = env('V3_CAPTCHA_CLIENT_ID');
        $data['GOOGLE_AUTH_CLIENT_ID'] = env('GOOGLE_AUTH_CLIENT_ID');
        $data['javascript'] = array('register');
        $data['service_id'] = '2';
        $data['jsonld'] = true;

        return view('home/industry/education', $data);
    }

    /**
     * Returns view for travelntour industry landing page
     *
     * @return void
     */
    public function travelntour()
    {
        SEOMeta::setTitle('Swipez | Free GST Billing Software Solutions for Travel & Tour Agency | online invoice maker');
        SEOMeta::setDescription('To solve all GST billing & invoice related issues Swipez provide best GST Billing Software for Travel & Tour Agency that is accurate and secure.');
        SEOMeta::addKeyword(['billing software for travel and tour company', 'billing software for travel and tour operators', 'gst billing software for travel and tour company', 'travel and tour company billing software', 'free billing software for travel and tour company', 'travel and tour company accounting software', 'invoicing software for travel and tour agency', 'gst billing software for travel and tourism company']);
        SEOMeta::addMeta('name', $value = 'Swipez | Billing Software Solutions for Travel & Tour Agency | Free Gst Billing Software', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Swipez is a cloud billing software for travel and tour companies. Our customised travel billing templates make invoicing an ease and online payments help you get paid faster. ', $name = 'itemprop');

        OpenGraph::setTitle('Swipez | Billing Software Solutions for Travel & Tour Agency | Free Gst Billing Software');
        OpenGraph::setDescription('Swipez is a cloud billing software for travel and tour companies. Our customised travel billing templates make invoicing an ease and online payments help you get paid faster. ');
        OpenGraph::setUrl('https://www.swipez.in/billing-software-for-travel-and-tour-operator');
        JsonLd::setTitle('Billing software for travel and tour operators');
        JsonLd::setType('Product');
        JsonLd::setDescription('Organize your invoicing, collect payments faster and manage pay outs with ease.');
        JsonLd::setImage('https://www.swipez.in/images/product/billing-software/industry/travel-tour-operator.svg');
        JsonLd::addValue('aggregateRating', json_decode('{"@type":"AggregateRating","ratingValue":"4.6","reviewCount":"109"}', 1));

        $data['CAPTCHA_CLIENT_ID'] = env('V3_CAPTCHA_CLIENT_ID');
        $data['GOOGLE_AUTH_CLIENT_ID'] = env('GOOGLE_AUTH_CLIENT_ID');
        $data['javascript'] = array('register');
        $data['service_id'] = '2';
        $data['jsonld'] = true;
        return view('home/industry/travelntour', $data);
    }

    /**
     * Returns view for entertainment event industry landing page
     *
     * @return void
     */
    public function entertainmentevent()
    {
        SEOMeta::setTitle('Swipez | Entertainment Event Registration Software | Entertainment Event Ticketing Platform');
        SEOMeta::setDescription('Swipez free event registration software for entertainment makes ticket booking smooth for customers. The free entertainment event management software is packed with multiple features such as online payment collections, free event registration, auto payment to vendors, QR code-based entry, and multi packages based pricing.');
        SEOMeta::addKeyword(['entertainment event ticketing solutions', 'event registration software for entertainment events', 'online entertainment event registration software', 'entertainment event registration software', 'online entertainment event management software', 'online entertainment event registration platforms', 'best entertainment event management platforms']);
        SEOMeta::addMeta('name', $value = 'Event registration software for entertainment events', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Online event registration and ticketing platform for entertainment events', $name = 'itemprop');

        OpenGraph::setTitle('Event registration software for entertainment events');
        OpenGraph::setDescription('The Swipez free event registration software is intuitive to use. Our multiple online payment modes make it simpler for customers booking a ticket and reduce booking drop offs.');
        OpenGraph::setUrl('https://www.swipez.in/event-registration-for-entertainment-event');


        JsonLd::setTitle('Event registration and ticketing for entertainment');
        JsonLd::setType('Product');
        JsonLd::setDescription('Create modern event landing pages, allow customers to book tickets and make payments online.');
        JsonLd::setImage('https://www.swipez.in/images/product/event-registration/industry/entertainment.svg');
        JsonLd::addValue('review', json_decode('{"@type":"Review","reviewRating":{"@type":"Rating","ratingValue":"4","bestRating":"5"},"author":{"@type":"Person","name":"Mahesh Shelote "}}', 1));
        JsonLd::addValue('aggregateRating', json_decode('{"@type":"AggregateRating","ratingValue":"4.8","reviewCount":"155"}', 1));
        Session::put('service_id', '4');

        $data['CAPTCHA_CLIENT_ID'] = env('V3_CAPTCHA_CLIENT_ID');
        $data['GOOGLE_AUTH_CLIENT_ID'] = env('GOOGLE_AUTH_CLIENT_ID');
        $data['javascript'] = array('register');
        $data['service_id'] = '4';
        $data['jsonld'] = true;
        return view('home/industry/entertainmentevent', $data);
    }

    /**
     * Returns view for hospitality event industry landing page
     *
     * @return void
     */
    public function hospitalityevent()
    {
        SEOMeta::setTitle('Swipez | Event Booking Software For Hospitality Business |Hospitality Event Ticketing System (Hospitals & Restaurants)');
        SEOMeta::setDescription('Swipez event management software for the hospitality industry  featured with quick online event registration, QR Code based entry verification of attendees. The event booking software for restaurants and hospitals helps you design attractive event registration landing pages with ease. Track offline payment from anyplace, at any time while using any device.');
        SEOMeta::addKeyword(['event registration for hospitality business', 'event booking for hospitals', 'event registration for restaurant', 'event registration software', 'online restaurant event management software', 'restaurant event management software', 'event booking software for restaurant']);
        SEOMeta::addMeta('name', $value = 'Event Booking Software For Restaurants | Event Ticketing Solutions', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Online event registration and ticketing platform for hospitality events', $name = 'itemprop');

        OpenGraph::setTitle('Event Booking Software For Restaurants | Event Ticketing Solutions');
        OpenGraph::setDescription('The Swipez event management software for the hospitality industry has powerful features like event registration, QR code ticketing for box office operations and online payments.');
        OpenGraph::setUrl('https://www.swipez.in/event-registration-for-hospitality-event');


        JsonLd::setTitle('Event registration and bookings for hospitality events');
        JsonLd::setType('Product');
        JsonLd::setDescription('Create beautiful landing pages for your hospitality event and let your customers book and pay online.');
        JsonLd::setImage('https://www.swipez.in/images/product/event-registration/industry/hospitality.svg');
        JsonLd::addValue('review', json_decode('{"@type":"Review","reviewRating":{"@type":"Rating","ratingValue":"4","bestRating":"5"},"author":{"@type":"Person","name":"Aniruddha Patil"}}', 1));
        JsonLd::addValue('aggregateRating', json_decode('{"@type":"AggregateRating","ratingValue":"4.8","reviewCount":"158"}', 1));
        Session::put('service_id', '4');

        $data['CAPTCHA_CLIENT_ID'] = env('V3_CAPTCHA_CLIENT_ID');
        $data['GOOGLE_AUTH_CLIENT_ID'] = env('GOOGLE_AUTH_CLIENT_ID');
        $data['javascript'] = array('register');
        $data['service_id'] = '4';
        $data['jsonld'] = true;
        return view('home/industry/hospitalityevent', $data);
    }

    /**
     * Returns view for health and fitness bookings industry landing page
     *
     * @return void
     */
    public function venuebookingfitness()
    {
        SEOMeta::setTitle('Swipez | Venue booking software for health and fitness | Venue scheduling software for restaurants');
        SEOMeta::setDescription('Swipez venue booking software for hospitals is designed to increase overall revenue by utilizing your venue’s resources and facilities to the maximum. Hospitals venue reservation and scheduling software helps in publishing of booking calendar.');
        SEOMeta::addKeyword(['venue management software for health venue', 'fitness venue booking software', 'facility management software for hospitals', 'facility scheduling software for hospitals']);
        SEOMeta::addMeta('name', $value = 'Venue booking software for health and fitness', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Health and fitness venue booking and time slot management', $name = 'itemprop');

        OpenGraph::setTitle('Venue booking software for health and fitness');
        OpenGraph::setDescription('Manage times slots for your venues, collect payments online and publish your venue calendar link. Ideal for courts, studios, gyms and spaces at your venue.');
        OpenGraph::setUrl('https://www.swipez.in/venue-booking-management-for-health-and-fitness');


        JsonLd::setTitle('Venue booking management for health & fitness');
        JsonLd::setType('Product');
        JsonLd::setDescription('Our online time slot & calendar based booking system helps you focus on the things you love.');
        JsonLd::setImage('https://www.swipez.in/images/product/venue-booking-software/industry/health-fitness.svg');
        JsonLd::addValue('review', json_decode('{"@type":"Review","reviewRating":{"@type":"Rating","ratingValue":"4","bestRating":"5"},"author":{"@type":"Person","name":"Chandra Gupta"}}', 1));
        JsonLd::addValue('aggregateRating', json_decode('{"@type":"AggregateRating","ratingValue":"4.4","reviewCount":"105"}', 1));
        Session::put('service_id', '5');
        return view('home/industry/venuebookingfitness', ['jsonld' => true]);
    }

    /**
     * Returns view for real estate bookings industry landing page
     *
     * @return void
     */
    public function societybooking()
    {
        SEOMeta::setTitle('Swipez | Billing software and venue booking software for housing society | Free Society Billing & Venue Management Software');
        SEOMeta::setDescription('Swipez venue and billing software for housing societies generates maintenance bills with online payment options. Track cancellations, rebooking, and underbooking of society venues for proper resource scheduling.');
        SEOMeta::addKeyword(['housing society billing software', 'billing software for housing society', 'society billing software', 'society software', 'society management system', 'housing society management software', 'housing society software', 'billing software for apartment society', 'housing society invoice software', 'event booking system for housing society']);
        SEOMeta::addMeta('name', $value = 'Billing booking software for housing society', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Setup billing and venue booking management for your housing society', $name = 'itemprop');

        OpenGraph::setTitle('Swipez | Billing Software for Housing Society | Free Society Billing Software');
        OpenGraph::setDescription('Swipez Billing Software for Housing Society is a free society accounting software which tracks maintenance collections and resident dues.Thus efficiently managing your society.');
        OpenGraph::setUrl('https://www.swipez.in//billing-and-venue-booking-software-for-housing-societies');


        JsonLd::setTitle('Billing & venue booking software for housing societies');
        JsonLd::setType('Product');
        JsonLd::setDescription('Organizing amenity booking, memberships and payment collections for housing societies.');
        JsonLd::setImage('https://www.swipez.in/images/product/venue-booking-software/industry/housing-society.svg');
        JsonLd::addValue('review', json_decode('{"@type":"Review","reviewRating":{"@type":"Rating","ratingValue":"4","bestRating":"5"},"author":{"@type":"Person","name":"Vikas Sankhla"}}', 1));
        JsonLd::addValue('aggregateRating', json_decode('{"@type":"AggregateRating","ratingValue":"4.7","reviewCount":"108"}', 1));
        Session::put('service_id', '5');

        $data['CAPTCHA_CLIENT_ID'] = env('V3_CAPTCHA_CLIENT_ID');
        $data['GOOGLE_AUTH_CLIENT_ID'] = env('GOOGLE_AUTH_CLIENT_ID');
        $data['javascript'] = array('register');
        $data['service_id'] = '5';
        $data['jsonld'] = true;

        return view('home/industry/societybooking', $data);
    }

    /**
     * Returns view for utility providers short url industry landing page
     *
     * @return void
     */
    public function utilityprovider()
    {
        SEOMeta::setTitle('');
        SEOMeta::setDescription('');
        SEOMeta::addKeyword([]);
        SEOMeta::addMeta('application-name', $value = '', $name = 'name');
        SEOMeta::addMeta('name', $value = '', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = '', $name = 'itemprop');

        OpenGraph::setTitle('');
        OpenGraph::setDescription('');
        OpenGraph::setUrl('');



        return view('home/industry/utilityprovider');
    }

    /**
     * Returns view for financial services short url industry landing page
     *
     * @return void
     */
    public function enterprises()
    {
        SEOMeta::setTitle('Swipez | Custom Url Shortener For Businesses | Url Shortener in Bulks For Enterprise');
        SEOMeta::setDescription('Generate customized and branded short readable Urls from Long Urls. Share short Urls over WhatsApp, Facebook, and Twitter in bulk using Swipez enterprise mass link shortener.');
        SEOMeta::addKeyword(['enterprise url shortener', 'url shortener for enterprise', 'mass link shortener for enterprise', 'custom url shortener for businesses']);
        SEOMeta::addMeta('name', $value = 'Swipez URL shortener', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'URL shortener for enterprise', $name = 'itemprop');

        OpenGraph::setTitle('Swipez | Custom Url Shortener For Businesses | Url Shortener For Enterprise');
        OpenGraph::setDescription('Shorten, customize and share fully branded short URLs in bulk using our enterprise link management platform or with custom APIs. Simplifying all your customer communications.');
        OpenGraph::setUrl('https://www.swipez.in/short-url-solution-for-enterprise');


        JsonLd::setTitle('URL Shortener for enterprises');
        JsonLd::setType('Product');
        JsonLd::setDescription('Create branded short links for all your customer communications at scale and within budget.');
        JsonLd::setImage('https://www.swipez.in/images/product/url-shortener.svg');
        JsonLd::addValue('review', json_decode('{"@type":"Review","reviewRating":{"@type":"Rating","ratingValue":"4","bestRating":"5"},"author":{"@type":"Person","name":"Lokesh Sonawane"}}', 1));
        JsonLd::addValue('aggregateRating', json_decode('{"@type":"AggregateRating","ratingValue":"4.7","reviewCount":"135"}', 1));

        return view('home/industry/enterprises', ['jsonld' => true]);
    }

    /**
     * Returns view for freelancers landing page
     *
     * @return void
     */
    public function freelancers()
    {
        SEOMeta::setTitle('Swipez | Billing Software For IT Consulting Firms | GST Billing Software For Freelancers');
        SEOMeta::setDescription('Swipez  professional billing software for freelancers and consulting firms generates hassle-free GST compliant invoices. 100% accurate and cloud-based secure system. Freelancers & IT consulting billing software facilitates auto payment reminders to avoid late payments.');
        SEOMeta::addKeyword(['gst billing software for freelancers', 'billing software for consulting firms', 'billing software for IT consultant', ' consultancy firms billing software', 'free billing software for consulting firms', 'consultancy firms accounting software', 'invoicing software for freelancers', 'gst billing software for consultancy firms', 'consulting firms management software', 'free consultancy firms management system', 'free consultancy firms billing software', 'billing software consultant']);
        SEOMeta::addMeta('name', $value = 'Swipez | Billing Software For Consulting Firms | GST Billing Software For Freelancers', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Billing solution for consultants, freenlancers, startup. Start with our free plan.', $name = 'itemprop');
        OpenGraph::setTitle('Swipez | Billing Software For Consulting Firms | GST Billing Software For Freelancers');
        OpenGraph::setDescription('Swipez offers professional invoicing for freelancers and consulting firms. Create recurring invoices, estimates turn to invoices are just a few features that simplify your billing.');
        OpenGraph::setUrl('https://www.swipez.in/invoicing-software-for-freelancers-and-consultants');

        JsonLd::setTitle('Billing software for consultancy firms and freelancers');
        JsonLd::setType('Product');
        JsonLd::setDescription('Enabling timely revenue collections for your hard work.');
        JsonLd::setImage('https://www.swipez.in/images/product/billing-software/industry/freelancer.svg');
        JsonLd::addValue('review', json_decode('{"@type":"Review","reviewRating":{"@type":"Rating","ratingValue":"4","bestRating":"5"},"author":{"@type":"Person","name":"Avinash Agarwal"}}', 1));
        JsonLd::addValue('aggregateRating', json_decode('{"@type":"AggregateRating","ratingValue":"4.7","reviewCount":"108"}', 1));

        $data['CAPTCHA_CLIENT_ID'] = env('V3_CAPTCHA_CLIENT_ID');
        $data['GOOGLE_AUTH_CLIENT_ID'] = env('GOOGLE_AUTH_CLIENT_ID');
        $data['javascript'] = array('register');
        $data['service_id'] = '2';
        $data['jsonld'] = true;

        return view('home/industry/freelancers', $data);
    }

    /**
     * Returns view for pricing landing page
     *
     * @return void
     */
    public function pricing()
    {
        SEOMeta::setTitle('Swipez pricing | Free plans available with all our products');
        SEOMeta::setDescription('A family of products for payment collections. From startups to enterprises, Swipez has pricing plans for any organization.');
        SEOMeta::addMeta('name', $value = 'Swipez pricing', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Free plans available with all our products', $name = 'itemprop');

        OpenGraph::setTitle('Swipez pricing');
        OpenGraph::setDescription('A family of products for payment collections. From startups to enterprises, Swipez has pricing plans for any organization.');
        OpenGraph::setUrl('https://www.swipez.in/pricing');


        return view('home/pricing');
    }

    /**
     * Returns view for billing pricing landing page
     *
     * @return void
     */
    public function billingpricing()
    {
        SEOMeta::setTitle('Billing software pricing | Free forever plan available.');
        SEOMeta::setDescription('Buy the best online billing software for your business at the most affordable prices. Forever free plan available.');
        SEOMeta::addKeyword(['affordable invoicing', 'online invoicing software', 'online billing system', 'online invoice', 'invoice', 'billing software', 'invoicing with online payments', 'billing with online payments']);
        SEOMeta::addMeta('name', $value = 'Swipez Billing software pricing', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Billing software pricing | Free forever plan available.', $name = 'itemprop');

        OpenGraph::setTitle('Swipez Billing software pricing');
        OpenGraph::setDescription('Buy the best online billing software for your business at the most affordable prices. Forever free plan available.');
        OpenGraph::setUrl('https://www.swipez.in/billing-software-pricing');



        return view('home/pricing/billing');
    }

    /**
     * Returns view for event pricing landing page
     *
     * @return void
     */
    public function eventpricing()
    {
        SEOMeta::setTitle('Event registration software pricing | Free forever plan available.');
        SEOMeta::setDescription('View pricing and plans for online event registration and ticketing software. Free forever plans available.');
        SEOMeta::addKeyword(['affordable event management', 'online event registration software', 'online ticketing system', 'free online event hosting']);
        SEOMeta::addMeta('name', $value = 'Swipez Event registration software pricing', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Billing software pricing | Free forever plan available.', $name = 'itemprop');

        OpenGraph::setTitle('Event registration software pricing | Free forever plan available.');
        OpenGraph::setDescription('View pricing and plans for online event registration and ticketing software. Free forever plans available.');
        OpenGraph::setUrl('https://www.swipez.in/event-registration-pricing');

        Session::put('service_id', '4');
        return view('home/pricing/event');
    }

    /**
     * Returns view for booking calendar pricing landing page
     *
     * @return void
     */
    public function bookingcalendarpricing()
    {
        SEOMeta::setTitle('Venue booking software pricing | Free forever plan available.');
        SEOMeta::setDescription('View pricing & plans for venue booking software. Free forever plans available.');
        SEOMeta::addKeyword(['affordable venue booking software', 'online venue booking software', 'online venue time slot booking system', 'free venue booking software']);
        SEOMeta::addMeta('name', $value = 'Swipez Venue booking software pricing', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Venue booking software pricing. Free forever plan available.', $name = 'itemprop');

        OpenGraph::setTitle('Venue booking software pricing | Free forever plan available.');
        OpenGraph::setDescription('View pricing & plans for venue booking software. Free forever plans available.');
        OpenGraph::setUrl('https://www.swipez.in/venue-booking-software-pricing');


        return view('home/pricing/bookingcalendar');
    }

    /**
     * Returns view for website builder pricing landing page
     *
     * @return void
     */
    public function websitebuilderpricing()
    {
        SEOMeta::setTitle('Website builder pricing | Free forever plan available.');
        SEOMeta::setDescription('View pricing & plans for website builder. Free forever plans available.');
        SEOMeta::addKeyword(['free website builder', 'affordable website builder', 'online website builder', 'free website with online payments']);
        SEOMeta::addMeta('name', $value = 'Swipez Website builder pricing', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Website builder pricing. Free forever plan available.', $name = 'itemprop');

        OpenGraph::setTitle('Website builder pricing | Free forever plan available.');
        OpenGraph::setDescription('View pricing & plans for website builder. Free forever plans available.');
        OpenGraph::setUrl('https://www.swipez.in/website-builder-pricing');


        return view('home/pricing/websitebuilder');
    }

    /**
     * Returns view for url shortener pricing landing page
     *
     * @return void
     */
    public function urlshortenerpricing()
    {
        SEOMeta::setTitle('URL shortener pricing');
        SEOMeta::setDescription('View pricing and plans for URL shortener. Affordable pricing plans for large or small volumes.');
        SEOMeta::addKeyword(['url shortener pricing', 'custom domain url shortener pricing', 'affordable url shortener']);
        SEOMeta::addMeta('name', $value = 'Swipez URL shortener pricing', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Affordable pricing plans for enterprise URL shortener.', $name = 'itemprop');

        OpenGraph::setTitle('URL shortener pricing');
        OpenGraph::setDescription('View pricing and plans for URL shortener. Affordable pricing plans for large or small volumes.');
        OpenGraph::setUrl('https://www.swipez.in/url-shortener-pricing');


        return view('home/pricing/urlshortener');
    }

    /**
     * Returns view for online transactions pricing landing page
     *
     * @return void
     */
    public function paymentgatewaycharges()
    {
        SEOMeta::setTitle('Payment gateway charges. Charges starting at 0%');
        SEOMeta::setDescription('Payment gateway charges with 0 setup fee, 0 maintenance cost, TDRs starting at 0%');
        SEOMeta::addKeyword(['payment gateway charges', 'payment gateway charges india', 'payment gateway pricing']);
        SEOMeta::addMeta('name', $value = 'Swipez payment gateway charges', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Payment gateway charges. Charges starting at 0%', $name = 'itemprop');

        OpenGraph::setTitle('Payment gateway charges. Charges starting at 0%');
        OpenGraph::setDescription('Payment gateway charges with 0 setup fee, 0 maintenance cost, TDRs starting at 0%');
        OpenGraph::setUrl('https://www.swipez.in/payment-gateway-charges');


        return view('home/pricing/onlinetransactions');
    }

    /**
     * Returns view for Swipez terms
     *
     * @return void
     */
    public function terms($merchant_id = null)
    {
        SEOMeta::setTitle('Swipez | Terms of service');
        SEOMeta::setDescription('Terms of Swipez is committed to the safe and secure purchasing of products.');
        SEOMeta::addMeta('name', $value = 'Swipez terms of service', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Terms of Swipez is committed to the safe and secure purchasing of products.', $name = 'itemprop');

        OpenGraph::setTitle('Swipez terms of service');
        OpenGraph::setDescription('Terms of service for Swipez products');
        OpenGraph::setUrl('https://www.swipez.in/terms');



        return view('home/footer/terms');
    }

    /**
     * Returns view for Swipez terms
     *
     * @return void
     */
    public function termspopup($merchant_id = null)
    {
        SEOMeta::setTitle('Swipez | Terms of service');
        SEOMeta::setDescription('Terms of Swipez is committed to the safe and secure purchasing of products.');
        SEOMeta::addMeta('name', $value = 'Swipez terms of service', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Terms of Swipez is committed to the safe and secure purchasing of products.', $name = 'itemprop');

        OpenGraph::setTitle('Swipez terms of service');
        OpenGraph::setDescription('Terms of service for Swipez products');
        OpenGraph::setUrl('https://www.swipez.in/terms-popup');
        $tnc = '';
        if ($merchant_id != null) {
            $homeModel = new Home();
            $tnc = $homeModel->getTNC($merchant_id, 1);
        }
        return view('home/footer/termspopup', array('tnc' => $tnc));
    }

    /**
     * Returns view for Swipez privacy
     *
     * @return void
     */
    public function privacypopup($merchant_id = null)
    {
        SEOMeta::setTitle('Swipez | Privacy Policy');
        SEOMeta::setDescription('Swipez is committed to protect customer data and never misuse or misguide its customer records');
        SEOMeta::addMeta('name', $value = 'Swipez privacy policy', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Swipez is committed to protect customer data and never misuse or misguide its customer records', $name = 'itemprop');

        OpenGraph::setTitle('Swipez privacy policy');
        OpenGraph::setDescription('Privacy policy for Swipez products');
        OpenGraph::setUrl('https://www.swipez.in/privacy-popup');

        $tnc = '';
        if ($merchant_id != null) {
            $homeModel = new Home();
            $tnc = $homeModel->getTNC($merchant_id, 1);
        }

        return view('home/footer/privacypopup', array('tnc' => $tnc));
    }

    /**
     * Returns view for Swipez privacy
     *
     * @return void
     */
    public function privacy($merchant_id = null)
    {
        SEOMeta::setTitle('Swipez | Privacy Policy');
        SEOMeta::setDescription('Swipez is committed to protect customer data and never misuse or misguide its customer records');
        SEOMeta::addMeta('name', $value = 'Swipez privacy policy', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Swipez is committed to protect customer data and never misuse or misguide its customer records', $name = 'itemprop');
        OpenGraph::setTitle('Swipez privacy policy');
        OpenGraph::setDescription('Privacy policy for Swipez products');
        OpenGraph::setUrl('https://www.swipez.in/privacy');
        return view('home/footer/privacy');
    }

    /**
     * Returns view for Swipez about us
     *
     * @return void
     */
    public function aboutus()
    {
        // if (config('app.merchant_subdomain')) {
        //     $controller = new MerchantPagesController();
        //     return $controller->merchantAboutus(config('app.merchant_subdomain'));
        // }
        SEOMeta::setTitle('Swipez | About Us');
        SEOMeta::setDescription('Swipez provides a convenient and easy mechanism to businesses in oder to operate their operations and helping in faster payment collections');
        SEOMeta::addMeta('name', $value = 'Swipez about us', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Swipez provides a convenient and easy mechanism to businesses in oder to operate their operations and helping in faster payment collections', $name = 'itemprop');
        OpenGraph::setTitle('Swipez about us');
        OpenGraph::setDescription('Swipez provides a convenient and easy mechanism to businesses in oder to operate their operations and helping in faster payment collections');
        OpenGraph::setUrl('https://www.swipez.in/about-us');
        return view('home/footer/aboutus');
    }

    /**
     * Returns view for Swipez about us
     *
     * @return void
     */
    public function contactus()
    {
        // if (config('app.merchant_subdomain')) {
        //     $controller = new MerchantPagesController();
        //     return $controller->merchantContactus(config('app.merchant_subdomain'));
        // }
        SEOMeta::setTitle('Swipez contact us');
        SEOMeta::setDescription('Want to get in touch?');
        SEOMeta::addMeta('name', $value = 'Swipez contact us', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Want to get in touch?', $name = 'itemprop');
        OpenGraph::setTitle('Swipez contact us');
        OpenGraph::setDescription('Want to get in touch?');
        OpenGraph::setUrl('https://www.swipez.in/contactus');
        return view('home/footer/contactus');
    }

    /**
     * Returns view for Swipez Get in touch
     *
     * @return void
     */
    public function getintouch($subject = null)
    {
        SEOMeta::setTitle('Swipez Support | Contact Us for pricing related queries');
        SEOMeta::setDescription('Want to get in touch for pricing related queries? Drop us a line and we will get back to you');
        SEOMeta::addMeta('name', $value = 'Swipez contact us', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Want to get in touch?', $name = 'itemprop');
        OpenGraph::setTitle('Swipez contact us');
        OpenGraph::setDescription('Want to get in touch?');
        OpenGraph::setUrl('https://www.swipez.in/getintouch/' . $subject);
        return view('home/footer/getintouch', array('subject' => $subject));
    }

    /**
     * Returns view for Swipez partner
     *
     * @return void
     */
    public function partner()
    {
        SEOMeta::setTitle('Swipez | Software partner program | Earn partner income');
        SEOMeta::setDescription('One of the best software partner programs in the industry. Partner with Swipez and earn a recurring income forever!');
        SEOMeta::addMeta('name', $value = 'Swipez partner program', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'One of the best software partner programs in the industry. Partner with Swipez and earn a recurring income forever!', $name = 'itemprop');
        OpenGraph::setTitle('Swipez | Software partner program | Earn partner income');
        OpenGraph::setDescription('One of the best software partner programs in the industry. Partner with Swipez and earn a recurring income forever!');
        OpenGraph::setUrl('https://www.swipez.in/partner');
        return view('home/footer/partner');
    }

    public function pagenotfound()
    {
        // if (config('app.merchant_subdomain')) {
        //     $controller = new MerchantPagesController();
        //     return view('mpages/error');
        // }
        return view('errors/404');
    }

    public function legacyerror($type = null)
    {
        if ($type == 'oops') {
            return view('errors/system');
        } elseif ($type == 'packageexpire') {
            $data['title'] = 'Package expired';
            $data['button_text'] = 'Renew now';
            $data['return_url'] = '/merchant/package/confirm/' . Session::get('package_link');
            $data['message'] = 'Your current package has expired. Please renew your package to start using your account - <a href="/merchant/package/confirm/ ' . Session::get('package_link') . '">Renew package</a> or <a href="/' . Session::get('choose_package_link') . '">Pick another package</a>';
        } else {
            $data['title'] = 'Error';
            $data['return_url'] = '';
            $data['message'] = 'Something Went Wrong. Please try again';
        }

        if (Session::has('errorTitle')) {
            $data['title'] = Session::get('errorTitle');
            Session::forget('errorTitle');
        }
        $data['image'] = '404.svg';
        if (Session::has('button_text')) {
            $data['button_text'] = Session::get('button_text');
            if ($data['button_text'] == 'Activate now' || $data['button_text'] == 'Upload now') {
                $data['image'] = 'activate-feature.svg';
            }
            Session::forget('button_text');
        }
        if (Session::has('return_url')) {
            $data['return_url'] = Session::get('return_url');
            Session::forget('return_url');
        }
        if (Session::has('errorMessage')) {
            $data['message'] = Session::get('errorMessage');
            Session::forget('errorMessage');
        }
        SEOMeta::setTitle($data['title']);
        OpenGraph::setTitle($data['title']);
        return view('errors/legacy', $data);
    }

    public function showerror()
    {
        return view('errors/system');
    }

    public function accessdenied()
    {
        $data['return_url'] = '';
        $data['image'] = '404.svg';
        $data['title'] = 'Access denied';
        $data['message'] = 'You do not have access to this feature. If you need access to this feature please contact your main merchant.';
        return view('errors/legacy', $data);
    }

    public function landingpage($page, $company = null)
    {
        $data['display_name'] = str_replace('-', ' ', $company);
        $data['company_name'] = $company;
        return view('lp/' . $page, $data);
    }

    public function workfromhome()
    {
        setcookie('registration_campaign_id', '22', time() + (864000 * 30), "/"); // 86400 = 1 day
        SEOMeta::setTitle('Work from home and collect payments online');
        SEOMeta::setDescription('Organize your business billing data online and collect payment online from your customers using Swipez billing software.');
        SEOMeta::addKeyword(['work from home', 'payment link', 'link payment', 'free payment link', 'form builder with online payments', 'invoicing with online payments', 'collect payment faster online', 'invoice payment link', 'how to create online payment link']);
        SEOMeta::addMeta('application-name', $value = 'Swipez Billing software with payment collections', $name = 'name');
        SEOMeta::addMeta('name', $value = 'Swipez Billing software with payment collections', $name = 'itemprop');
        OpenGraph::setTitle('Work from home and collect payments online');
        OpenGraph::setDescription('Organize your business billing data online and collect payment online from your customers using Swipez billing software.');
        OpenGraph::setUrl('https://www.swipez.in/workfromhome');
        $data['CAPTCHA_CLIENT_ID'] = env('V3_CAPTCHA_CLIENT_ID');
        $data['GOOGLE_AUTH_CLIENT_ID'] = env('GOOGLE_AUTH_CLIENT_ID');
        $data['javascript'] = array('register');
        $data['service_id'] = '2';
        $data['jsonld'] = true;
        Session::put('service_id', '2');
        return view('home/workfromhome', $data);
    }

    /**
     * Returns view for customer stories landing page
     *
     * @return void
     */
    public function customerstories()
    {
        SEOMeta::setTitle('Swipez customers success stories and product reviews');
        SEOMeta::setDescription('Take a peek at some of these client reviews and success stories and check out what makes Swipez an excellent GST billing software for small businesses.');
        SEOMeta::addKeyword(['swipez products reviews', 'swipez customer reviews', 'swipez customers stories']);
        SEOMeta::addMeta('name', $value = 'Swipez customer stories and product reviews', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Learn how different small merchants use Swipez to organize and grow their business.', $name = 'itemprop');
        OpenGraph::setTitle('Swipez customers success stories and product reviews');
        OpenGraph::setDescription('Take a peek at some of these client reviews and success stories and check out what makes Swipez an excellent GST billing software for small businesses.');
        OpenGraph::setUrl('https://www.swipez.in/customerstories');
        return view('home/footer/customerstories');
    }

    /**
     * Returns view for trade india registration page
     *
     * @return void
     */
    public function tradeindia()
    {
        setcookie('registration_campaign_id', '23', time() + (864000 * 30), "/"); // 86400 = 1 day
        SEOMeta::setTitle('Partner program with TradeIndia');
        SEOMeta::setDescription('Organize your business billing data online and collect payment online from your customers using Swipez billing software.');
        SEOMeta::addKeyword(['trade india partner', 'payment link', 'link payment', 'free payment link', 'form builder with online payments', 'invoicing with online payments', 'collect payment faster online', 'invoice payment link', 'how to create online payment link']);
        SEOMeta::addMeta('application-name', $value = 'Swipez Billing software with payment collections', $name = 'name');
        SEOMeta::addMeta('name', $value = 'Swipez Billing software with payment collections', $name = 'itemprop');
        OpenGraph::setTitle('Partner program with Trade India');
        OpenGraph::setDescription('Organize your business billing data online and collect payment online from your customers using Swipez billing software.');
        OpenGraph::setUrl('https://www.swipez.in/tradeindia');
        $data['CAPTCHA_CLIENT_ID'] = env('V3_CAPTCHA_CLIENT_ID');
        $data['GOOGLE_AUTH_CLIENT_ID'] = env('GOOGLE_AUTH_CLIENT_ID');
        $data['javascript'] = array('register');
        $data['service_id'] = '2';
        $data['jsonld'] = true;
        Session::put('service_id', '2');
        return view('home/footer/tradeindia', $data);
    }

    /**
     * Returns view for boot 360 registration page
     *
     * @return void
     */
    public function boost360()
    {
        setcookie('registration_campaign_id', '24', time() + (864000 * 30), "/"); // 86400 = 1 day
        SEOMeta::setTitle('Partner program with Boost 360');
        SEOMeta::setDescription('Organize your business billing data online and collect payment online from your customers using Swipez billing software.');
        SEOMeta::addKeyword(['trade india partner', 'payment link', 'link payment', 'free payment link', 'form builder with online payments', 'invoicing with online payments', 'collect payment faster online', 'invoice payment link', 'how to create online payment link']);
        SEOMeta::addMeta('application-name', $value = 'Swipez Billing software with payment collections', $name = 'name');
        SEOMeta::addMeta('name', $value = 'Swipez Billing software with payment collections', $name = 'itemprop');
        OpenGraph::setTitle('Partner program with Boost 360');
        OpenGraph::setDescription('Organize your business billing data online and collect payment online from your customers using Swipez billing software.');
        OpenGraph::setUrl('https://www.swipez.in/boost360');
        $data['CAPTCHA_CLIENT_ID'] = env('V3_CAPTCHA_CLIENT_ID');
        $data['GOOGLE_AUTH_CLIENT_ID'] = env('GOOGLE_AUTH_CLIENT_ID');
        $data['javascript'] = array('register');
        $data['service_id'] = '2';
        $data['jsonld'] = true;
        Session::put('service_id', '2');
        return view('home/footer/boost360', $data);
    }

    /**
     * Returns view for Yatra registration page
     *
     * @return void
     */
    public function partnerPage(Request $request)
    {
        $url = $request->path();
        if ($url == 'yatra') {
            $campaign_id = '25';
            $title = 'Yatra';
            $data['partner_logo'] = 'https://secure.yatra.com/images/theme1/company_logo/yt_logo.png';
        } elseif ($url == 'nsrcel') {
            $campaign_id = '47';
            $title = 'NSRCEL';
            $data['partner_logo'] = 'https://www.nsrcel.org/wp-content/themes/iimb/img/logo.png';
        } elseif ($url == 'taxprint') {
            $campaign_id = '48';
            $title = 'Taxprint';
            $data['partner_logo'] = 'https://www.taxprint.com/assets/images/taxPrintIndiaLogo.png';
        }
        setcookie('registration_campaign_id', $campaign_id, time() + (864000 * 30), "/"); // 86400 = 1 day
        SEOMeta::setTitle('Partner program with ' . $title);
        SEOMeta::setDescription('Organize your business billing data online and collect payment online from your customers using Swipez billing software.');
        SEOMeta::addKeyword(['trade india partner', 'payment link', 'link payment', 'free payment link', 'form builder with online payments', 'invoicing with online payments', 'collect payment faster online', 'invoice payment link', 'how to create online payment link']);
        SEOMeta::addMeta('application-name', $value = 'Swipez Billing software with payment collections', $name = 'name');
        SEOMeta::addMeta('name', $value = 'Swipez Billing software with payment collections', $name = 'itemprop');
        OpenGraph::setTitle('Partner program with ' . $title);
        OpenGraph::setDescription('Organize your business billing data online and collect payment online from your customers using Swipez billing software.');
        OpenGraph::setUrl('https://www.swipez.in/' . $url);
        $data['CAPTCHA_CLIENT_ID'] = env('V3_CAPTCHA_CLIENT_ID');
        $data['GOOGLE_AUTH_CLIENT_ID'] = env('GOOGLE_AUTH_CLIENT_ID');
        $data['javascript'] = array('register');
        $data['service_id'] = '2';
        $data['jsonld'] = true;
        $data['title'] = $title;
        Session::put('service_id', '2');
        return view('home/footer/yatra', $data);
    }

    /**
     * Returns view for milk and dairy industry landing page
     *
     * @return void
     */
    public function milkdairy()
    {
        SEOMeta::setTitle('Milk dairy billing | Free Dairy Management Software | Milk Dairy | Swipez');
        SEOMeta::setDescription('Milk Dairy Management software is free and powerful software by Swipez that helps help organise customer information, plans, and billing. Our Dairy Software is easy to use and manage as well with no learning curve.');
        SEOMeta::addKeyword(['billing software for milk', 'billing software for dairy', 'gst billing software for milk dairy vendor', 'milk and dairy billing software', 'free billing software for milk and dairy', 'milk and dairy billing software', 'invoicing software for dairy vendors', 'free milk dairy management software', 'free milk dairy management system', 'free milk dairy billing software']);
        SEOMeta::addMeta('name', $value = 'Billing software for Milk and Dairy', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Billing solution for Milk and Dairy with custom integrations', $name = 'itemprop');

        OpenGraph::setTitle('Billing software for Milk and Dairy');
        OpenGraph::setDescription('Swipez billing software for Internet Service Providers, organises customer data and billing. Our free billing software manages invoicing, payment reminders and online payments.');
        OpenGraph::setUrl('https://www.swipez.in/milk-dairy-billing-software');
        JsonLd::setTitle('Billing software for milk and dairy');
        JsonLd::setType('Product');
        JsonLd::setDescription('Optimizing the most precious business aspect for a milk delivery provider - Timely revenue collections.');
        JsonLd::setImage('https://www.swipez.in/images/product/billing-software/industry/milkanddairy.svg');
        JsonLd::addValue('aggregateRating', json_decode('{"@type":"AggregateRating","ratingValue":"4.5","reviewCount":"99"}', 1));
        Session::put('service_id', '2');

        $data['CAPTCHA_CLIENT_ID'] = env('V3_CAPTCHA_CLIENT_ID');
        $data['GOOGLE_AUTH_CLIENT_ID'] = env('GOOGLE_AUTH_CLIENT_ID');
        $data['javascript'] = array('register');
        $data['service_id'] = '2';
        $data['jsonld'] = true;
        return view('home/industry/milkdairy', $data);
    }

    /**
     * Returns view for freelancers landing page
     *
     * @return void
     */
    public function inthenews()
    {
        SEOMeta::setTitle('We’re in news across top most media outlets | Swipez | For the right reasons 😊');
        SEOMeta::setDescription('Swipez is a B2B and SAAS platform that has been mentioned in various media outlets. It helps automate SMB businesses operations and improves payment collections by over 40%');
        SEOMeta::addKeyword(['swipez coverage', 'swipez reviews', 'swipez', 'swipez news', 'swipez in the news']);
        SEOMeta::addMeta('name', $value = 'We’re in news across top most media outlets | Swipez | For the right reasons 😊', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Swipez is a B2B and SAAS platform that has been mentioned in various media outlets. It helps automate SMB businesses operations and improves payment collections by over 40%', $name = 'itemprop');
        OpenGraph::setTitle('We’re in news across top most media outlets | Swipez | For the right reasons 😊');
        OpenGraph::setDescription('Swipez is a B2B and SAAS platform that has been mentioned in various media outlets. It helps automate SMB businesses operations and improves payment collections by over 40%');
        OpenGraph::setUrl('https://www.swipez.in/in-the-news');

        JsonLd::setTitle('We’re in news across top most media outlets | Swipez | For the right reasons 😊');
        JsonLd::setType('Product');
        JsonLd::setDescription('Swipez is a B2B and SAAS platform that has been mentioned in various media outlets. It helps automate SMB businesses operations and improves payment collections by over 40%');
        JsonLd::setImage('https://www.swipez.in/images/footer/in-the-news.svg');
        JsonLd::addValue('aggregateRating', json_decode('{"@type":"AggregateRating","ratingValue":"4.5","reviewCount":"99"}', 1));
        Session::put('service_id', '2');

        return view('home/footer/inthenews');
    }

    /**
     * Returns view for chatnow window
     *
     * @return void
     */
    public function chatnow()
    {
        return view('home/groove');
    }


    /**
     * Returns view for bulk invoicing feature page
     *
     * @return view
     */
    public function bulkinvoicing()
    {
        SEOMeta::setTitle('Bulk invoicing made simple using excel uploads or APIs | Swipez billing software');
        SEOMeta::setDescription('Generate invoice in bulk using simple excel uploads or APIs. Create bulk invoices in customizable formats as per your requirement, with the fields and value you need!');
        SEOMeta::addKeyword(['billing software', 'best gst billing software', 'free billing software', 'bulk invoicing software', 'gst bulk invoicing software', 'professional bulk billing software', 'best bulk invoicing software', 'gst bulk invoice software', 'free bulk invoicing software', 'batch invoicing software', 'online gst billing software', 'online bulk invoice maker']);
        SEOMeta::addMeta('application-name', $value = 'Swipez Billing software', $name = 'name');
        SEOMeta::addMeta('name', $value = 'Swipez Billing software', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Online billing software with bulk invoicing system for your business.', $name = 'itemprop');
        OpenGraph::setTitle('Online billing software with bulk invoicing system for your business.');
        OpenGraph::setDescription('Generate invoice in bulk using simple excel uploads or APIs. Create bulk invoices in customizable formats as per your requirement, with the fields and value you need!');
        OpenGraph::setUrl('https://www.swipez.in/bulk-invoicing');
        JsonLd::setTitle('Bulk Invoicing Software for Billing | Free Billing software');
        JsonLd::setType('Product');
        JsonLd::setDescription('Generate invoice in bulk using simple excel uploads or APIs. Create bulk invoices in customizable formats as per your requirement, with the fields and value you need!');
        JsonLd::addValue('review', json_decode('[{"@type":"Review","author":"Jayesh Shah","datePublished":"2018-04-01","description":"Now I can easily send & reconcile our companies telephone and internet bills with multiple payment options to thousands of our customers via e-mail and SMS at the click of a button through Swipez.","name":"Value purchase","reviewRating":{"@type":"Rating","bestRating":"5","ratingValue":"1","worstRating":"1"}},{"@type":"Review","author":"Vikas Sankhla","datePublished":"2018-03-25","description":"We are now managing payments across our complete customer base along with timely pay outs for all franchisees across the country from one dashboard. My team has saved over hundred hours after adopting Swipez Billing.","name":"Value purchase","reviewRating":{"@type":"Rating","bestRating":"5","ratingValue":"4","worstRating":"1"}}]', 1));
        JsonLd::addValue('aggregateRating', json_decode('{"@type":"AggregateRating","ratingValue":"4.4","reviewCount":"89"}', 1));
        JsonLd::setImage('https://www.swipez.in/images/product/billing-software.svg');
        $data['jsonld'] = true;
        $data['CAPTCHA_CLIENT_ID'] = env('V3_CAPTCHA_CLIENT_ID');
        $data['GOOGLE_AUTH_CLIENT_ID'] = env('GOOGLE_AUTH_CLIENT_ID');
        $data['javascript'] = array('register');
        $data['service_id'] = '2';
        Session::put('service_id', '2');
        return view('home/feature/billing/bulkinvoicing', $data);
    }

    /**
     * Returns view for payment reminder page
     *
     * @return view
     */
    public function paymentreminder()
    {
        SEOMeta::setTitle('Automated Payment Reminders | Payment Reminder Templates | Swipez');
        SEOMeta::setDescription('Swipez payment reminder software allows you to easily send automated reminders to all clients about overdue invoices. Try the payment
        reminder software and forget about your business worries.');
        SEOMeta::addKeyword(['payment reminder message', 'message for payment reminder', 'gentle reminder message for payment', 'payment reminder message sample', 'payment reminder message to client', 'reminder for payment message', 'payment due reminder message', 'message to remind payment ', 'payment reminder email', 'overdue payment reminder']);
        //SEOMeta::addMeta('application-name', $value = 'Swipez Billing software', $name = 'name');
        //SEOMeta::addMeta('name', $value = 'Swipez Billing software', $name = 'itemprop');
        //SEOMeta::addMeta('description', $value = 'Online billing software with bulk invoicing system for your business.', $name = 'itemprop');
        // OpenGraph::setTitle('Billing software for Milk and Dairy');
        // OpenGraph::setDescription('Swipez billing software for Internet Service Providers, organises customer data and billing. Our free billing software manages invoicing, payment reminders and online payments.');
        // OpenGraph::setUrl('https://www.swipez.in/milk-dairy-billing-software');
        // JsonLd::setTitle('Billing software for milk and dairy');
        // JsonLd::setType('Product');
        // JsonLd::setDescription('Optimizing the most precious business aspect for a milk delivery provider - Timely revenue collections.');
        // JsonLd::setImage('https://www.swipez.in/images/product/billing-software/industry/milkanddairy.svg');
        // JsonLd::addValue('aggregateRating', json_decode('{"@type":"AggregateRating","ratingValue":"4.5","reviewCount":"99"}', 1));
        // Session::put('service_id', '2');

        return view('home/feature/billing/paymentreminder');
    }


    /**
     * Returns view for Collect it landing page
     *
     * @return view
     */
    public function collectit()
    {
        SEOMeta::setTitle('Free Billing App | Collect it - Billing & Online payment collections app');
        SEOMeta::setDescription('Take advantage of Swipez free billing app, you can send bills online & collect payments with ease. Bill customers directly from your phone contacts with payment reminders.');
        SEOMeta::addKeyword(['billing app', 'collection app', 'free billing app', 'invoicing app', 'online payment collection app', 'payment reminder app', 'best online billing app', 'gst invoice app', 'free invoice app', 'free gst billing app', 'online gst billing app', 'app invoice maker']);
        SEOMeta::addMeta('application-name', $value = 'Collect it - Billing app', $name = 'name');
        SEOMeta::addMeta('name', $value = 'Swipez Collect it Billing app', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Online billing app & payment collections for your business.', $name = 'itemprop');
        OpenGraph::setTitle('Online billing app & invoicing app for your business.');
        OpenGraph::setDescription('Swipez free billing and online payment app offers a comprehensive billing solution for your business. From managing invoices to sending payment reminders and getting paid online.');
        OpenGraph::setUrl('https://www.swipez.in/collect-it-billing-app');
        JsonLd::setTitle('Free Billing App | Collect it - Billing & Online payment collections app');
        JsonLd::setType('App');
        JsonLd::setDescription('Take advantage of Swipez free billing app, you can send bills online & collect payments with ease. Bill customers directly from your phone contacts with payment reminders.');
        JsonLd::addValue('review', json_decode('[{"@type":"Review","author":"Jayesh Shah","datePublished":"2018-04-01","description":"Now I can easily send & reconcile our companies telephone and internet bills with multiple payment options to thousands of our customers via e-mail and SMS at the click of a button through Swipez.","name":"Value purchase","reviewRating":{"@type":"Rating","bestRating":"5","ratingValue":"1","worstRating":"1"}},{"@type":"Review","author":"Vikas Sankhla","datePublished":"2018-03-25","description":"We are now managing payments across our complete customer base along with timely pay outs for all franchisees across the country from one dashboard. My team has saved over hundred hours after adopting Swipez Billing.","name":"Value purchase","reviewRating":{"@type":"Rating","bestRating":"5","ratingValue":"4","worstRating":"1"}}]', 1));
        JsonLd::addValue('aggregateRating', json_decode('{"@type":"AggregateRating","ratingValue":"4.4","reviewCount":"89"}', 1));
        JsonLd::setImage('https://www.swipez.in/images/product/billing-app/billing-app.png');
        $data['jsonld'] = true;
        $data['CAPTCHA_CLIENT_ID'] = env('V3_CAPTCHA_CLIENT_ID');
        $data['GOOGLE_AUTH_CLIENT_ID'] = env('GOOGLE_AUTH_CLIENT_ID');
        $data['javascript'] = array('register');
        $data['service_id'] = '2';
        Session::put('service_id', '2');
        return view('home/product/collectit', $data);
    }


    /**
     * Returns view for payment link landing page
     *
     * @return view
     */
    public function paymentLink()
    {
        SEOMeta::setTitle('Generate free payment links | Collect payment online | Swipez');
        SEOMeta::setDescription('Collect online payment links. CREATE - SHARE - GET PAID. Collect payments online anytime, anywhere with Payment Links. Share your payment link via Whatsapp, Facebook, Twitter, Email, SMS, and more.');
        SEOMeta::addKeyword(['payment link', 'generate payment link', 'online payment links instantly', 'payment link generator', 'upi payment link generator', 'free payment link', 'online payment link', 'collect online payments without a website', 'generate secure payment link', 'generate payment link']);
        SEOMeta::addMeta('application-name', $value = 'Payment link generator', $name = 'name');
        SEOMeta::addMeta('name', $value = 'Payment link generator', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Generate free payment links and collect money online.', $name = 'itemprop');
        OpenGraph::setTitle('Generate free payment links | Collect payment online | Swipez');
        OpenGraph::setDescription('Collect online payment links. CREATE - SHARE - GET PAID. Collect payments online anytime, anywhere with Payment Links. Share your payment link via Whatsapp, Facebook, Twitter, Email, SMS, and more.');
        OpenGraph::setUrl('https://www.swipez.in/payment-link');
        JsonLd::setTitle('Generate free payment links | Collect payment online | Swipez');
        JsonLd::setType('Product');
        JsonLd::setDescription('Collect online payment links. CREATE - SHARE - GET PAID. Collect payments online anytime, anywhere with Payment Links. Share your payment link via Whatsapp, Facebook, Twitter, Email, SMS, and more.');
        JsonLd::addValue('review', json_decode('[{"@type":"Review","author":"Ramesh Pawar","datePublished":"2021-07-01","description":"Now my team is able to collect payments from my customers with so much ease! We just share a simple link and collect payments directly in to our bank account","name":"Value purchase","reviewRating":{"@type":"Rating","bestRating":"5","ratingValue":"1","worstRating":"1"}},{"@type":"Review","author":"Adil Dingkar","datePublished":"2022-01-25","description":"Makes our lives so much easier. Now we collect payments faster without any delays. Payment link product is completely customizable to our business needs","name":"Value purchase","reviewRating":{"@type":"Rating","bestRating":"5","ratingValue":"5","worstRating":"1"}}]', 1));
        JsonLd::addValue('aggregateRating', json_decode('{"@type":"AggregateRating","ratingValue":"4.4","reviewCount":"89"}', 1));
        JsonLd::setImage('https://www.swipez.in/images/product/<SHUHAID>');
        $data['jsonld'] = true;
        $data['CAPTCHA_CLIENT_ID'] = env('V3_CAPTCHA_CLIENT_ID');
        $data['GOOGLE_AUTH_CLIENT_ID'] = env('GOOGLE_AUTH_CLIENT_ID');
        $data['javascript'] = array('register');
        //Shuhaid confirm if service id can be 2 or should be changed in next 2 lines
        $data['service_id'] = '2';
        Session::put('service_id', '2');
        return view('home/product/paymentlink', $data);
    }


    /**
     * Returns view for gst filing landing page
     *
     * @return view
     * @author Abhijeet C
     */
    public function gstrecon()
    {

        SEOMeta::setTitle('Online GST Reconciliation Software | GSTR 2A Reconciliation');
        SEOMeta::setDescription('Reconcile 5X faster using Swipez GST Reconciliation software. Now save your time in reconciliation and claim 100% ITC for your clients.');
        SEOMeta::addKeyword(['Online GST Reconciliation Software', 'gst reconciliation software price', 'reconciliation software free', 'gst reconciliation in tally', 'gst reconciliation tool', 'GST reconciliation software', 'GSTR 2A Reconciliation']);
        SEOMeta::addMeta('application-name', $value = 'Swipez GST Reconciliation Software', $name = 'name');
        SEOMeta::addMeta('name', $value = 'Swipez Billing software', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Automated GST Reconciliation Software for your business.', $name = 'itemprop');
        OpenGraph::setTitle('Online GST Reconciliation Software | GSTR 2A Reconciliation');
        OpenGraph::setDescription('Reconcile 5X faster using Swipez GST Reconciliation software. Now save your time in reconciliation and claim 100% ITC for your clients.');
        OpenGraph::setUrl('https://www.swipez.in/gst-filing');
        JsonLd::setTitle('GST Reconciliation Software');
        JsonLd::setType('Product');
        JsonLd::setDescription('Reconcile 5X faster using Swipez GST Reconciliation software. Now save your time in reconciliation and claim 100% ITC for your clients.');
        JsonLd::addValue('review', json_decode('[{"@type":"Review","author":"Jayesh Shah","datePublished":"2018-04-01","description":"Now I can easily send & reconcile our companies telephone and internet bills with multiple payment options to thousands of our customers via e-mail and SMS at the click of a button through Swipez.","name":"Value purchase","reviewRating":{"@type":"Rating","bestRating":"5","ratingValue":"1","worstRating":"1"}},{"@type":"Review","author":"Vikas Sankhla","datePublished":"2018-03-25","description":"We are now managing payments across our complete customer base along with timely pay outs for all franchisees across the country from one dashboard. My team has saved over hundred hours after adopting Swipez Billing.","name":"Value purchase","reviewRating":{"@type":"Rating","bestRating":"5","ratingValue":"4","worstRating":"1"}}]', 1));
        JsonLd::addValue('aggregateRating', json_decode('{"@type":"AggregateRating","ratingValue":"4.4","reviewCount":"89"}', 1));
        JsonLd::setImage('https://www.swipez.in/images/product/gst-reconciliation/GST-reconciliation-software.png');
        $data['CAPTCHA_CLIENT_ID'] = env('V3_CAPTCHA_CLIENT_ID');
        $data['GOOGLE_AUTH_CLIENT_ID'] = env('GOOGLE_AUTH_CLIENT_ID');
        $data['javascript'] = array('register');
        $data['service_id'] = '6';
        $data['jsonld'] = true;
        Session::put('service_id', '6');
        return view('home/product/gstr2a', $data);
    }

    /**
     *
     */
    public function  woocommerce_invoicing()
    {
        SEOMeta::setTitle('Free WooCommerce PDF Invoice Plugin | Free Invoice');
        SEOMeta::setDescription('Create and send invoices, manage your inventory, and get real-time reports on payments using woocommerce invoice plugin by Swipez');
        SEOMeta::addKeyword(['woocommerce invoice plugin,woocommerce invoice plugin free,woocommerce custom invoice plugin,woocommerce gst invoice plugin,woocommerce invoice generator plugin,woocommerce invoice payment plugin,print invoice plugin woocommerce']);
        SEOMeta::addMeta('application-name', $value = 'Swipez GST return filing software', $name = 'name');
        SEOMeta::addMeta('name', $value = 'Swipez Billing software', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Automated GST filing solution for your business.', $name = 'itemprop');
        OpenGraph::setTitle('WooCommerce Invoice Plugin | Template and Billing - Swipez');
        OpenGraph::setDescription('Create and send invoices, manage your inventory, and get real-time reports on payments using woocommerce invoice plugin by Swipez');
        OpenGraph::setUrl('https://www.swipez.in/gst-filing');
        JsonLd::setTitle('Free WooCommerce PDF Invoice Plugin');
        JsonLd::setType('Product');
        JsonLd::setDescription('Create and send invoices, manage your inventory, and get real-time reports on payments using woocommerce invoice plugin by Swipez');
        JsonLd::addValue('review', json_decode('[{"@type":"Review","author":"Jayesh Shah","datePublished":"2018-04-01","description":"Now I can easily send & reconcile our companies telephone and internet bills with multiple payment options to thousands of our customers via e-mail and SMS at the click of a button through Swipez.","name":"Value purchase","reviewRating":{"@type":"Rating","bestRating":"5","ratingValue":"1","worstRating":"1"}},{"@type":"Review","author":"Vikas Sankhla","datePublished":"2018-03-25","description":"We are now managing payments across our complete customer base along with timely pay outs for all franchisees across the country from one dashboard. My team has saved over hundred hours after adopting Swipez Billing.","name":"Value purchase","reviewRating":{"@type":"Rating","bestRating":"5","ratingValue":"4","worstRating":"1"}}]', 1));
        JsonLd::addValue('aggregateRating', json_decode('{"@type":"AggregateRating","ratingValue":"4.4","reviewCount":"89"}', 1));
        JsonLd::setImage('https://www.swipez.in/images/product/gst-filing.svg');
        $data['CAPTCHA_CLIENT_ID'] = env('V3_CAPTCHA_CLIENT_ID');
        $data['GOOGLE_AUTH_CLIENT_ID'] = env('GOOGLE_AUTH_CLIENT_ID');
        $data['javascript'] = array('register');
        $data['service_id'] = '7';
        $data['jsonld'] = true;
        Session::put('service_id', '7');
        return view('home/product/woocommerce_invoicing', $data);
    }


    public function  woocommerce_paymentgateway()
    {
        SEOMeta::setTitle('Woocommerce Payment Gateway | 3 steps simple installation - Swipez');
        SEOMeta::setDescription('WooCommerce sellers now offer their customers an easy payment gateway through Swipez. Collect payment through UPI, e-wallet, credit/debit card, net banking, or cash.');
        SEOMeta::addKeyword(['payment gateway for ecommerce website,Swipez woocommerce payment gateways,woocommerce payment gateway in india,best payment gateway for woocommerce in india,payment gateway for woocommerce,payment gateway for wordpress']);
        SEOMeta::addMeta('application-name', $value = 'Swipez GST return filing software', $name = 'name');
        SEOMeta::addMeta('name', $value = 'Swipez Billing software', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Automated GST filing solution for your business.', $name = 'itemprop');
        OpenGraph::setTitle('Woocommerce Payment Gateway | 3 Steps Installation - Swipez');
        OpenGraph::setDescription('WooCommerce sellers now offer their customers an easy payment gateway through Swipez. Collect payment through UPI, e-wallet, credit/debit card, net banking, or cash.');
        OpenGraph::setUrl('https://www.swipez.in/gst-filing');
        JsonLd::setTitle('Woocommerce Payment Gateway');
        JsonLd::setType('Product');
        JsonLd::setDescription('WooCommerce sellers now offer their customers an easy payment gateway through Swipez. Collect payment through UPI, e-wallet, credit/debit card, net banking, or cash.');
        JsonLd::addValue('review', json_decode('[{"@type":"Review","author":"Jayesh Shah","datePublished":"2018-04-01","description":"Now I can easily send & reconcile our companies telephone and internet bills with multiple payment options to thousands of our customers via e-mail and SMS at the click of a button through Swipez.","name":"Value purchase","reviewRating":{"@type":"Rating","bestRating":"5","ratingValue":"1","worstRating":"1"}},{"@type":"Review","author":"Vikas Sankhla","datePublished":"2018-03-25","description":"We are now managing payments across our complete customer base along with timely pay outs for all franchisees across the country from one dashboard. My team has saved over hundred hours after adopting Swipez Billing.","name":"Value purchase","reviewRating":{"@type":"Rating","bestRating":"5","ratingValue":"4","worstRating":"1"}}]', 1));
        JsonLd::addValue('aggregateRating', json_decode('{"@type":"AggregateRating","ratingValue":"4.4","reviewCount":"89"}', 1));
        JsonLd::setImage('https://www.swipez.in/images/product/gst-filing.svg');
        $data['CAPTCHA_CLIENT_ID'] = env('V3_CAPTCHA_CLIENT_ID');
        $data['GOOGLE_AUTH_CLIENT_ID'] = env('GOOGLE_AUTH_CLIENT_ID');
        $data['javascript'] = array('register');
        $data['service_id'] = '8';
        $data['jsonld'] = true;
        Session::put('service_id', '8');
        return view('home/product/woocommerce_paymentgateway', $data);
    }


    public function einvoicing()
    {
        SEOMeta::setTitle('E-Invoicing | E-Invoicing Software for Your Business - Swipez');
        SEOMeta::setDescription('e-invoicing software for your business. Easy, error-free GST compliance. Book a demo! Free Demo Available.');
        SEOMeta::addKeyword(['e invoicing system,invoicing software,e invoicing software,e invoice software,e invoice billing software,e-invoice accounting software, e invoicing gst, e-invoicing solutions,gst e invoicing software,e invoice software india,e invoicing,e invoice portal,e invoicing gst,gst e invoice,E-invoicing,e invoicing in gst']);
        SEOMeta::addMeta('application-name', $value = 'Swipez GST return filing software', $name = 'name');
        SEOMeta::addMeta('name', $value = 'Swipez Billing software', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Automated GST filing solution for your business.', $name = 'itemprop');
        OpenGraph::setTitle('E-Invoicing | E-Invoicing Software for Your Business - Swipez');
        OpenGraph::setDescription('e-invoicing software for your business. Easy, error-free GST compliance. Book a demo! Free Demo Available.');
        OpenGraph::setUrl('https://www.swipez.in/gst-filing');
        JsonLd::setTitle('E-Invoicing');
        JsonLd::setType('Product');
        JsonLd::setDescription('e-invoicing software for your business. Easy, error-free GST compliance. Book a demo! Free Demo Available.');
        JsonLd::addValue('review', json_decode('[{"@type":"Review","author":"Jayesh Shah","datePublished":"2018-04-01","description":"Now I can easily send & reconcile our companies telephone and internet bills with multiple payment options to thousands of our customers via e-mail and SMS at the click of a button through Swipez.","name":"Value purchase","reviewRating":{"@type":"Rating","bestRating":"5","ratingValue":"1","worstRating":"1"}},{"@type":"Review","author":"Vikas Sankhla","datePublished":"2018-03-25","description":"We are now managing payments across our complete customer base along with timely pay outs for all franchisees across the country from one dashboard. My team has saved over hundred hours after adopting Swipez Billing.","name":"Value purchase","reviewRating":{"@type":"Rating","bestRating":"5","ratingValue":"4","worstRating":"1"}}]', 1));
        JsonLd::addValue('aggregateRating', json_decode('{"@type":"AggregateRating","ratingValue":"4.4","reviewCount":"89"}', 1));
        JsonLd::setImage('https://www.swipez.in/images/product/gst-filing.svg');
        $data['CAPTCHA_CLIENT_ID'] = env('V3_CAPTCHA_CLIENT_ID');
        $data['GOOGLE_AUTH_CLIENT_ID'] = env('GOOGLE_AUTH_CLIENT_ID');
        $data['javascript'] = array('register');
        $data['service_id'] = '9';
        $data['jsonld'] = true;
        Session::put('service_id', '9');
        return view('home/product/einvoicing', $data);
    }
    public function paymentpage()
    {
        SEOMeta::setTitle('Payment Page | Accept Payment with custom Payment Page-Swipez');
        SEOMeta::setDescription('Collect payments faster with a custom payment page suited for every need. Customize the payment page with your logo branding.');
        SEOMeta::addKeyword(['payment page design, payment page, online payment page, multi-gateway payment page, create payment page, customizable payment pages, custom payment page']);
        SEOMeta::addMeta('application-name', $value = 'Swipez GST return filing software', $name = 'name');
        SEOMeta::addMeta('name', $value = 'Swipez Billing software', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Automated GST filing solution for your business.', $name = 'itemprop');
        OpenGraph::setTitle('Payment Page | Accept Payment with custom Payment Page-Swipez');
        OpenGraph::setDescription('Collect payments faster with a custom payment page suited for every need. Customize the payment page with your logo branding.');
        OpenGraph::setUrl('https://www.swipez.in/gst-filing');
        JsonLd::setTitle('Payment Page');
        JsonLd::setType('Product');
        JsonLd::setDescription('Collect payments faster with a custom payment page suited for every need. Customize the payment page with your logo branding.');
        JsonLd::addValue('review', json_decode('[{"@type":"Review","author":"Jayesh Shah","datePublished":"2018-04-01","description":"Now I can easily send & reconcile our companies telephone and internet bills with multiple payment options to thousands of our customers via e-mail and SMS at the click of a button through Swipez.","name":"Value purchase","reviewRating":{"@type":"Rating","bestRating":"5","ratingValue":"1","worstRating":"1"}},{"@type":"Review","author":"Vikas Sankhla","datePublished":"2018-03-25","description":"We are now managing payments across our complete customer base along with timely pay outs for all franchisees across the country from one dashboard. My team has saved over hundred hours after adopting Swipez Billing.","name":"Value purchase","reviewRating":{"@type":"Rating","bestRating":"5","ratingValue":"4","worstRating":"1"}}]', 1));
        JsonLd::addValue('aggregateRating', json_decode('{"@type":"AggregateRating","ratingValue":"4.4","reviewCount":"89"}', 1));
        JsonLd::setImage('https://www.swipez.in/images/product/gst-filing.svg');
        $data['CAPTCHA_CLIENT_ID'] = env('V3_CAPTCHA_CLIENT_ID');
        $data['GOOGLE_AUTH_CLIENT_ID'] = env('GOOGLE_AUTH_CLIENT_ID');
        $data['javascript'] = array('register');
        $data['service_id'] = '9';
        $data['jsonld'] = true;
        Session::put('service_id', '9');
        return view('home/payment_page', $data);
    }


    public function invoicing()
    {
        SEOMeta::setTitle('Online Invoicing Software | Online Invoice Generator');
        SEOMeta::setDescription('Online invoicing software made for small businesses and freelancers. Create & send unlimited, professional invoices. Swipez online invoice generator seamlessly helps you track & receive payments online in seconds.');
        SEOMeta::addKeyword(['Online Invoicing', 'Recurring invoicing', 'Free invoice software', 'Online Invoices', 'Online Invoices Software', 'Create invoices online', 'Free online invoice Maker', 'Free invoice generator', 'Invoice Generator', 'online invoicing software', 'online invoicing software india', 'free invoicing software', 'Online invoicing generator', 'Create Invoices Online']);
        SEOMeta::addMeta('application-name', $value = 'Swipez GST return filing software', $name = 'name');
        SEOMeta::addMeta('name', $value = 'Swipez Billing software', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Automated GST filing solution for your business.', $name = 'itemprop');
        OpenGraph::setTitle('Online Invoicing Software | Online Invoice Generator');
        OpenGraph::setDescription('Online invoicing software made for small businesses and freelancers. Create & send unlimited, professional invoices. Swipez online invoice generator seamlessly helps you track & receive payments online in seconds.');
        OpenGraph::setUrl('https://www.swipez.in/gst-filing');
        JsonLd::setTitle('Online Invoicing Software');
        JsonLd::setType('Product');
        JsonLd::setDescription('Online invoicing software made for small businesses and freelancers. Create & send unlimited, professional invoices. Swipez online invoice generator seamlessly helps you track & receive payments online in seconds.');
        JsonLd::addValue('review', json_decode('[{"@type":"Review","author":"Jayesh Shah","datePublished":"2018-04-01","description":"Now I can easily send & reconcile our companies telephone and internet bills with multiple payment options to thousands of our customers via e-mail and SMS at the click of a button through Swipez.","name":"Value purchase","reviewRating":{"@type":"Rating","bestRating":"5","ratingValue":"1","worstRating":"1"}},{"@type":"Review","author":"Vikas Sankhla","datePublished":"2018-03-25","description":"We are now managing payments across our complete customer base along with timely pay outs for all franchisees across the country from one dashboard. My team has saved over hundred hours after adopting Swipez Billing.","name":"Value purchase","reviewRating":{"@type":"Rating","bestRating":"5","ratingValue":"4","worstRating":"1"}}]', 1));
        JsonLd::addValue('aggregateRating', json_decode('{"@type":"AggregateRating","ratingValue":"4.4","reviewCount":"89"}', 1));
        JsonLd::setImage('https://www.swipez.in/images/product/gst-filing.svg');
        $data['CAPTCHA_CLIENT_ID'] = env('V3_CAPTCHA_CLIENT_ID');
        $data['GOOGLE_AUTH_CLIENT_ID'] = env('GOOGLE_AUTH_CLIENT_ID');
        $data['javascript'] = array('register');
        $data['service_id'] = '9';
        $data['jsonld'] = true;

        Session::put('service_id', '9');
        return view('home/product/invoicing', $data);
    }
    public function benifits()
    {
        SEOMeta::setTitle('Swipez Partner Benefits and Offers');
        SEOMeta::setDescription('Swipez Partner Benefits and Offers');
        SEOMeta::addKeyword(['Swipez Benefits', 'Swipez Products', 'Swipez Business Benefits', 'Swipez Billing Software Benefits', 'Swipez Payment Gateway Benefits', 'Swipez GST Billing Software Benefits']);
        SEOMeta::addMeta('application-name', $value = 'Swipez Benefits', $name = 'name');
        SEOMeta::addMeta('name', $value = 'Swipez Benefits', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Swipez Partner Benefits and Offers', $name = 'itemprop');
        OpenGraph::setTitle('Swipez Partner Benefits and Offers');
        OpenGraph::setDescription('Swipez Partner Benefits and Offers');
        OpenGraph::setUrl('https://www.swipez.in/partner-benefits');
        JsonLd::setTitle('Swipez Partner Benefits and Offers');
        JsonLd::setType('Product');
        JsonLd::setDescription('Swipez Partner Benefits and Offers');
        JsonLd::addValue('review', json_decode('[{"@type":"Review","author":"Jayesh Shah","datePublished":"2018-04-01","description":"Now I can easily send & reconcile our companies telephone and internet bills with multiple payment options to thousands of our customers via e-mail and SMS at the click of a button through Swipez.","name":"Value purchase","reviewRating":{"@type":"Rating","bestRating":"5","ratingValue":"1","worstRating":"1"}},{"@type":"Review","author":"Vikas Sankhla","datePublished":"2018-03-25","description":"We are now managing payments across our complete customer base along with timely pay outs for all franchisees across the country from one dashboard. My team has saved over hundred hours after adopting Swipez Billing.","name":"Value purchase","reviewRating":{"@type":"Rating","bestRating":"5","ratingValue":"4","worstRating":"1"}}]', 1));
        JsonLd::addValue('aggregateRating', json_decode('{"@type":"AggregateRating","ratingValue":"4.4","reviewCount":"89"}', 1));
        JsonLd::setImage('https://www.swipez.in/images/benefits/Swipez_Benefits.svg');
        $data['CAPTCHA_CLIENT_ID'] = env('V3_CAPTCHA_CLIENT_ID');
        $data['GOOGLE_AUTH_CLIENT_ID'] = env('GOOGLE_AUTH_CLIENT_ID');
        $data['javascript'] = array('register');
        $data['service_id'] = '9';
        $data['jsonld'] = true;

        Session::put('service_id', '9');
        return view('home/benefits_page', $data);
    }
    public function integration_landing_page()
    {
        SEOMeta::setTitle('Swipez Integrations | Connect your favourite tools');
        SEOMeta::setDescription('Connect your favourite tools');
        SEOMeta::addKeyword(['Swipez Integration Software', 'Swipez API Integration Software', 'API Integration for payment gateway', 'API Integration for e invoicing', 'API integration for Billing Software', 'API integration for GST Billing Software', 'API integration', 'Billing Software API Integration']);
        SEOMeta::addMeta('application-name', $value = 'Swipez Integrations', $name = 'name');
        SEOMeta::addMeta('name', $value = 'Swipez Integrations', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Connect your favourite tools', $name = 'itemprop');
        OpenGraph::setTitle('Swipez Integrations| Connect your favourite tools');
        OpenGraph::setDescription('Connect your favourite tools');
        OpenGraph::setUrl('https://www.swipez.in/integrations');
        JsonLd::setTitle('Swipez Integrations| Connect your favourite tools');
        JsonLd::setType('Product');
        JsonLd::setDescription('Connect your favourite tools');
        JsonLd::addValue('review', json_decode('[{"@type":"Review","author":"Jayesh Shah","datePublished":"2018-04-01","description":"Now I can easily send & reconcile our companies telephone and internet bills with multiple payment options to thousands of our customers via e-mail and SMS at the click of a button through Swipez.","name":"Value purchase","reviewRating":{"@type":"Rating","bestRating":"5","ratingValue":"1","worstRating":"1"}},{"@type":"Review","author":"Vikas Sankhla","datePublished":"2018-03-25","description":"We are now managing payments across our complete customer base along with timely pay outs for all franchisees across the country from one dashboard. My team has saved over hundred hours after adopting Swipez Billing.","name":"Value purchase","reviewRating":{"@type":"Rating","bestRating":"5","ratingValue":"4","worstRating":"1"}}]', 1));
        JsonLd::addValue('aggregateRating', json_decode('{"@type":"AggregateRating","ratingValue":"4.4","reviewCount":"89"}', 1));
        JsonLd::setImage('https://www.swipez.in/images/integrations/SwipezIntegrations.svg');
        $data['CAPTCHA_CLIENT_ID'] = env('V3_CAPTCHA_CLIENT_ID');
        $data['GOOGLE_AUTH_CLIENT_ID'] = env('GOOGLE_AUTH_CLIENT_ID');
        $data['javascript'] = array('register');
        $data['service_id'] = '9';
        $data['jsonld'] = true;

        Session::put('service_id', '9');
        return view('home/integration_page', $data);
    }
    public function razorpay()
    {
        SEOMeta::setTitle('Razorpay Benefits with Swipez');
        SEOMeta::setDescription('Razorpay Benefits with Swipez');
        SEOMeta::addKeyword(['Razorpay Benefits, Razorpay Benefits for business, swipez billing software benefits. razorpay partner benefits']);
        SEOMeta::addMeta('application-name', $value = 'Razorpay Benefits with Swipez', $name = 'name');
        SEOMeta::addMeta('name', $value = 'Swipez Billing software', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Razorpay Benefits with Swipez', $name = 'itemprop');
        OpenGraph::setTitle('Razorpay Benefits with Swipez');
        OpenGraph::setDescription('Razorpay Benefits with Swipez');
        OpenGraph::setUrl('https://www.swipez.in/partner-benefits/razorpay');
        JsonLd::setTitle('Razorpay Benefits with Swipez');
        JsonLd::setType('Product');
        JsonLd::setDescription('Razorpay Benefits with Swipez');
        JsonLd::addValue('review', json_decode('[{"@type":"Review","author":"Jayesh Shah","datePublished":"2018-04-01","description":"Now I can easily send & reconcile our companies telephone and internet bills with multiple payment options to thousands of our customers via e-mail and SMS at the click of a button through Swipez.","name":"Value purchase","reviewRating":{"@type":"Rating","bestRating":"5","ratingValue":"1","worstRating":"1"}},{"@type":"Review","author":"Vikas Sankhla","datePublished":"2018-03-25","description":"We are now managing payments across our complete customer base along with timely pay outs for all franchisees across the country from one dashboard. My team has saved over hundred hours after adopting Swipez Billing.","name":"Value purchase","reviewRating":{"@type":"Rating","bestRating":"5","ratingValue":"4","worstRating":"1"}}]', 1));
        JsonLd::addValue('aggregateRating', json_decode('{"@type":"AggregateRating","ratingValue":"4.4","reviewCount":"89"}', 1));
        JsonLd::setImage('https://www.swipez.in/images/benefits/razorpaypaymentgateway/razorpay.png');
        $data['CAPTCHA_CLIENT_ID'] = env('V3_CAPTCHA_CLIENT_ID');
        $data['GOOGLE_AUTH_CLIENT_ID'] = env('GOOGLE_AUTH_CLIENT_ID');
        $data['javascript'] = array('register');
        $data['service_id'] = '9';
        $data['jsonld'] = true;

        Session::put('service_id', '9');
        return view('home/benefit/razorpay', $data);
    }

    public function cashfree()
    {
        SEOMeta::setTitle('Cashfree Benefits with Swipez');
        SEOMeta::setDescription('Cashfree Benefits with Swipez');
        SEOMeta::addKeyword(['Cashfree Benefits, Cashfree Benefits for business, swipez billing software benefits. cashfree partner benefits']);
        SEOMeta::addMeta('application-name', $value = 'Cashfree Benefits with Swipez', $name = 'name');
        SEOMeta::addMeta('name', $value = 'Swipez Billing software', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Cashfree Benefits with Swipez', $name = 'itemprop');
        OpenGraph::setTitle('Cashfree Benefits with Swipez');
        OpenGraph::setDescription('Cashfree Benefits with Swipez');
        OpenGraph::setUrl('https://www.swipez.in/partner-benefits/cashfree');
        JsonLd::setTitle('Cashfree Benefits with Swipez');
        JsonLd::setType('Product');
        JsonLd::setDescription('Cashfree Benefits with Swipez');
        JsonLd::addValue('review', json_decode('[{"@type":"Review","author":"Jayesh Shah","datePublished":"2018-04-01","description":"Now I can easily send & reconcile our companies telephone and internet bills with multiple payment options to thousands of our customers via e-mail and SMS at the click of a button through Swipez.","name":"Value purchase","reviewRating":{"@type":"Rating","bestRating":"5","ratingValue":"1","worstRating":"1"}},{"@type":"Review","author":"Vikas Sankhla","datePublished":"2018-03-25","description":"We are now managing payments across our complete customer base along with timely pay outs for all franchisees across the country from one dashboard. My team has saved over hundred hours after adopting Swipez Billing.","name":"Value purchase","reviewRating":{"@type":"Rating","bestRating":"5","ratingValue":"4","worstRating":"1"}}]', 1));
        JsonLd::addValue('aggregateRating', json_decode('{"@type":"AggregateRating","ratingValue":"4.4","reviewCount":"89"}', 1));
        JsonLd::setImage('https://www.swipez.in/images/images/benefits/cashfreepaymentgateway/cashfree2.png');
        $data['CAPTCHA_CLIENT_ID'] = env('V3_CAPTCHA_CLIENT_ID');
        $data['GOOGLE_AUTH_CLIENT_ID'] = env('GOOGLE_AUTH_CLIENT_ID');
        $data['javascript'] = array('register');
        $data['service_id'] = '9';
        $data['jsonld'] = true;

        Session::put('service_id', '9');
        return view('home/benefit/cashfree', $data);
    }
    public function cashfree_payout()
    {
        SEOMeta::setTitle('Online Invoicing Software | Online Invoice Generator');
        SEOMeta::setDescription('Online invoicing software made for small businesses and freelancers. Create & send unlimited, professional invoices. Swipez online invoice generator seamlessly helps you track & receive payments online in seconds.');
        SEOMeta::addKeyword(['Online Invoicing', 'Recurring invoicing', 'Free invoice software', 'Online Invoices', 'Online Invoices Software', 'Create invoices online', 'Free online invoice Maker', 'Free invoice generator', 'Invoice Generator', 'online invoicing software', 'online invoicing software india', 'free invoicing software', 'Online invoicing generator', 'Create Invoices Online']);
        SEOMeta::addMeta('application-name', $value = 'Swipez GST return filing software', $name = 'name');
        SEOMeta::addMeta('name', $value = 'Swipez Billing software', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Automated GST filing solution for your business.', $name = 'itemprop');
        OpenGraph::setTitle('Online Invoicing Software | Online Invoice Generator');
        OpenGraph::setDescription('Online invoicing software made for small businesses and freelancers. Create & send unlimited, professional invoices. Swipez online invoice generator seamlessly helps you track & receive payments online in seconds.');
        OpenGraph::setUrl('https://www.swipez.in/gst-filing');
        JsonLd::setTitle('Online Invoicing Software');
        JsonLd::setType('Product');
        JsonLd::setDescription('Online invoicing software made for small businesses and freelancers. Create & send unlimited, professional invoices. Swipez online invoice generator seamlessly helps you track & receive payments online in seconds.');
        JsonLd::addValue('review', json_decode('[{"@type":"Review","author":"Jayesh Shah","datePublished":"2018-04-01","description":"Now I can easily send & reconcile our companies telephone and internet bills with multiple payment options to thousands of our customers via e-mail and SMS at the click of a button through Swipez.","name":"Value purchase","reviewRating":{"@type":"Rating","bestRating":"5","ratingValue":"1","worstRating":"1"}},{"@type":"Review","author":"Vikas Sankhla","datePublished":"2018-03-25","description":"We are now managing payments across our complete customer base along with timely pay outs for all franchisees across the country from one dashboard. My team has saved over hundred hours after adopting Swipez Billing.","name":"Value purchase","reviewRating":{"@type":"Rating","bestRating":"5","ratingValue":"4","worstRating":"1"}}]', 1));
        JsonLd::addValue('aggregateRating', json_decode('{"@type":"AggregateRating","ratingValue":"4.4","reviewCount":"89"}', 1));
        JsonLd::setImage('https://www.swipez.in/images/product/gst-filing.svg');
        $data['CAPTCHA_CLIENT_ID'] = env('V3_CAPTCHA_CLIENT_ID');
        $data['GOOGLE_AUTH_CLIENT_ID'] = env('GOOGLE_AUTH_CLIENT_ID');
        $data['javascript'] = array('register');
        $data['service_id'] = '9';
        $data['jsonld'] = true;

        Session::put('service_id', '9');
        return view('home/benefit/cashfree_payout', $data);
    }
    public function stripe()
    {
        SEOMeta::setTitle('Stripe Benefits with Swipez');
        SEOMeta::setDescription('Stripe Benefits with Swipez');
        SEOMeta::addKeyword(['stripe Benefits, stripe Benefits for business, swipez billing software benefits. stripe partner benefits']);
        SEOMeta::addMeta('application-name', $value = 'Stripe Benefits with Swipez', $name = 'name');
        SEOMeta::addMeta('name', $value = 'Swipez Billing software', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Stripe Benefits with Swipez', $name = 'itemprop');
        OpenGraph::setTitle('Stripe Benefits with Swipez');
        OpenGraph::setDescription('Stripe Benefits with Swipez');
        OpenGraph::setUrl('https://www.swipez.in/partner-benefits/stripe');
        JsonLd::setTitle('Stripe Benefits with Swipez');
        JsonLd::setType('Product');
        JsonLd::setDescription('Stripe Benefits with Swipez');
        JsonLd::addValue('review', json_decode('[{"@type":"Review","author":"Jayesh Shah","datePublished":"2018-04-01","description":"Now I can easily send & reconcile our companies telephone and internet bills with multiple payment options to thousands of our customers via e-mail and SMS at the click of a button through Swipez.","name":"Value purchase","reviewRating":{"@type":"Rating","bestRating":"5","ratingValue":"1","worstRating":"1"}},{"@type":"Review","author":"Vikas Sankhla","datePublished":"2018-03-25","description":"We are now managing payments across our complete customer base along with timely pay outs for all franchisees across the country from one dashboard. My team has saved over hundred hours after adopting Swipez Billing.","name":"Value purchase","reviewRating":{"@type":"Rating","bestRating":"5","ratingValue":"4","worstRating":"1"}}]', 1));
        JsonLd::addValue('aggregateRating', json_decode('{"@type":"AggregateRating","ratingValue":"4.4","reviewCount":"89"}', 1));
        JsonLd::setImage('https://www.swipez.in/images/benefits/stripe.png');
        $data['CAPTCHA_CLIENT_ID'] = env('V3_CAPTCHA_CLIENT_ID');
        $data['GOOGLE_AUTH_CLIENT_ID'] = env('GOOGLE_AUTH_CLIENT_ID');
        $data['javascript'] = array('register');
        $data['service_id'] = '9';
        $data['jsonld'] = true;

        Session::put('service_id', '9');
        return view('home/benefit/stripe', $data);
    }
    public function payoneer()
    {
        SEOMeta::setTitle('Payoneer Benefits with Swipez');
        SEOMeta::setDescription('Payoneer Benefits with Swipez');
        SEOMeta::addKeyword(['payoneer Benefits, payoneer Benefits for business, swipez billing software benefits. payoneer partner benefits']);
        SEOMeta::addMeta('application-name', $value = 'Payoneer Benefits with Swipez', $name = 'name');
        SEOMeta::addMeta('name', $value = 'Swipez Billing software', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Payoneer Benefits with Swipez', $name = 'itemprop');
        OpenGraph::setTitle('Payoneer Benefits with Swipez');
        OpenGraph::setDescription('Payoneer Benefits with Swipez');
        OpenGraph::setUrl('https://www.swipez.in/partner-benefits/payoneer');
        JsonLd::setTitle('Payoneer Benefits with Swipez');
        JsonLd::setType('Product');
        JsonLd::setDescription('Payoneer Benefits with Swipez');
        JsonLd::addValue('review', json_decode('[{"@type":"Review","author":"Jayesh Shah","datePublished":"2018-04-01","description":"Now I can easily send & reconcile our companies telephone and internet bills with multiple payment options to thousands of our customers via e-mail and SMS at the click of a button through Swipez.","name":"Value purchase","reviewRating":{"@type":"Rating","bestRating":"5","ratingValue":"1","worstRating":"1"}},{"@type":"Review","author":"Vikas Sankhla","datePublished":"2018-03-25","description":"We are now managing payments across our complete customer base along with timely pay outs for all franchisees across the country from one dashboard. My team has saved over hundred hours after adopting Swipez Billing.","name":"Value purchase","reviewRating":{"@type":"Rating","bestRating":"5","ratingValue":"4","worstRating":"1"}}]', 1));
        JsonLd::addValue('aggregateRating', json_decode('{"@type":"AggregateRating","ratingValue":"4.4","reviewCount":"89"}', 1));
        JsonLd::setImage('https://www.swipez.in/images/integrations/payoneer.png');
        $data['CAPTCHA_CLIENT_ID'] = env('V3_CAPTCHA_CLIENT_ID');
        $data['GOOGLE_AUTH_CLIENT_ID'] = env('GOOGLE_AUTH_CLIENT_ID');
        $data['javascript'] = array('register');
        $data['service_id'] = '9';
        $data['jsonld'] = true;

        Session::put('service_id', '9');
        return view('home/benefit/payoneer', $data);
    }
    public function amazon()
    {
        SEOMeta::setTitle('Amazon Web Services Benefits with Swipez');
        SEOMeta::setDescription('Amazon Web Services Benefits with Swipez');
        SEOMeta::addKeyword(['Amazon Web Services Benefits, Amazon Web Services Benefits for business, swipez billing software benefits. Amazon Web Services partner benefits']);
        SEOMeta::addMeta('application-name', $value = 'Amazon Web Services Benefits with Swipez', $name = 'name');
        SEOMeta::addMeta('name', $value = 'Swipez Billing software', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Amazon Web Services Benefits with Swipez', $name = 'itemprop');
        OpenGraph::setTitle('Amazon Web Services Benefits with Swipez');
        OpenGraph::setDescription('Amazon Web Services Benefits with Swipez');
        OpenGraph::setUrl('https://www.swipez.in/partner-benefits/amazon-web-services');
        JsonLd::setTitle('Amazon Web Services Benefits with Swipez');
        JsonLd::setType('Product');
        JsonLd::setDescription('Amazon Web Services Benefits with Swipez');
        JsonLd::addValue('review', json_decode('[{"@type":"Review","author":"Jayesh Shah","datePublished":"2018-04-01","description":"Now I can easily send & reconcile our companies telephone and internet bills with multiple payment options to thousands of our customers via e-mail and SMS at the click of a button through Swipez.","name":"Value purchase","reviewRating":{"@type":"Rating","bestRating":"5","ratingValue":"1","worstRating":"1"}},{"@type":"Review","author":"Vikas Sankhla","datePublished":"2018-03-25","description":"We are now managing payments across our complete customer base along with timely pay outs for all franchisees across the country from one dashboard. My team has saved over hundred hours after adopting Swipez Billing.","name":"Value purchase","reviewRating":{"@type":"Rating","bestRating":"5","ratingValue":"4","worstRating":"1"}}]', 1));
        JsonLd::addValue('aggregateRating', json_decode('{"@type":"AggregateRating","ratingValue":"4.4","reviewCount":"89"}', 1));
        JsonLd::setImage('https://www.swipez.in/images/benefits/amazonwebservices/awsactivate.png');
        $data['CAPTCHA_CLIENT_ID'] = env('V3_CAPTCHA_CLIENT_ID');
        $data['GOOGLE_AUTH_CLIENT_ID'] = env('GOOGLE_AUTH_CLIENT_ID');
        $data['javascript'] = array('register');
        $data['service_id'] = '9';
        $data['jsonld'] = true;

        Session::put('service_id', '9');
        return view('home/benefit/amazon', $data);
    }
    public function lending_kart()
    {
        SEOMeta::setTitle('Lending Kart Benefits with Swipez');
        SEOMeta::setDescription('Lending Kart Benefits with Swipez');
        SEOMeta::addKeyword(['Lending Kart Benefits, Lending Kart Benefits for business, swipez billing software benefits. LendingKart partner benefits']);
        SEOMeta::addMeta('application-name', $value = 'Lending Kart Benefits with Swipez', $name = 'name');
        SEOMeta::addMeta('name', $value = 'Swipez Billing software', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Lending Kart Benefits with Swipez', $name = 'itemprop');
        OpenGraph::setTitle('Lending Kart Benefits with Swipez');
        OpenGraph::setDescription('Lending Kart Benefits with Swipez');
        OpenGraph::setUrl('https://www.swipez.in/partner-benefits/lending-kart');
        JsonLd::setTitle('Lending Kart Benefits with Swipez');
        JsonLd::setType('Product');
        JsonLd::setDescription('Lending Kart Benefits with Swipez');
        JsonLd::addValue('review', json_decode('[{"@type":"Review","author":"Jayesh Shah","datePublished":"2018-04-01","description":"Now I can easily send & reconcile our companies telephone and internet bills with multiple payment options to thousands of our customers via e-mail and SMS at the click of a button through Swipez.","name":"Value purchase","reviewRating":{"@type":"Rating","bestRating":"5","ratingValue":"1","worstRating":"1"}},{"@type":"Review","author":"Vikas Sankhla","datePublished":"2018-03-25","description":"We are now managing payments across our complete customer base along with timely pay outs for all franchisees across the country from one dashboard. My team has saved over hundred hours after adopting Swipez Billing.","name":"Value purchase","reviewRating":{"@type":"Rating","bestRating":"5","ratingValue":"4","worstRating":"1"}}]', 1));
        JsonLd::addValue('aggregateRating', json_decode('{"@type":"AggregateRating","ratingValue":"4.4","reviewCount":"89"}', 1));
        JsonLd::setImage('https://www.swipez.in/images/benefits/lendingkart.png');
        $data['CAPTCHA_CLIENT_ID'] = env('V3_CAPTCHA_CLIENT_ID');
        $data['GOOGLE_AUTH_CLIENT_ID'] = env('GOOGLE_AUTH_CLIENT_ID');
        $data['javascript'] = array('register');
        $data['service_id'] = '9';
        $data['jsonld'] = true;

        Session::put('service_id', '9');
        return view('home/benefit/lending_kart', $data);
    }
    public function boot()
    {
        SEOMeta::setTitle('Boost 360 Benefits with Swipez');
        SEOMeta::setDescription('Boost 360 Benefits with Swipez');
        SEOMeta::addKeyword(['Boost 360 Benefits, Boost 360 Benefits for business, swipez billing software benefits. Boost 360 partner benefits']);
        SEOMeta::addMeta('application-name', $value = 'Boost 360 Benefits with Swipez', $name = 'name');
        SEOMeta::addMeta('name', $value = 'Swipez Billing software', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Boost 360 Benefits with Swipez', $name = 'itemprop');
        OpenGraph::setTitle('Boost 360 Benefits with Swipez');
        OpenGraph::setDescription('Boost 360 Benefits with Swipez');
        OpenGraph::setUrl('https://www.swipez.in/partner-benefits/boot-360');
        JsonLd::setTitle('Boost 360 Benefits with Swipez');
        JsonLd::setType('Product');
        JsonLd::setDescription('Boost 360 Benefits with Swipez');
        JsonLd::addValue('review', json_decode('[{"@type":"Review","author":"Jayesh Shah","datePublished":"2018-04-01","description":"Now I can easily send & reconcile our companies telephone and internet bills with multiple payment options to thousands of our customers via e-mail and SMS at the click of a button through Swipez.","name":"Value purchase","reviewRating":{"@type":"Rating","bestRating":"5","ratingValue":"1","worstRating":"1"}},{"@type":"Review","author":"Vikas Sankhla","datePublished":"2018-03-25","description":"We are now managing payments across our complete customer base along with timely pay outs for all franchisees across the country from one dashboard. My team has saved over hundred hours after adopting Swipez Billing.","name":"Value purchase","reviewRating":{"@type":"Rating","bestRating":"5","ratingValue":"4","worstRating":"1"}}]', 1));
        JsonLd::addValue('aggregateRating', json_decode('{"@type":"AggregateRating","ratingValue":"4.4","reviewCount":"89"}', 1));
        JsonLd::setImage('https://www.swipez.in/images/benefits/boost-360-logo.svg');
        $data['CAPTCHA_CLIENT_ID'] = env('V3_CAPTCHA_CLIENT_ID');
        $data['GOOGLE_AUTH_CLIENT_ID'] = env('GOOGLE_AUTH_CLIENT_ID');
        $data['javascript'] = array('register');
        $data['service_id'] = '9';
        $data['jsonld'] = true;

        Session::put('service_id', '9');
        return view('home/benefit/boot', $data);
    }
    public function dalal_street()
    {
        SEOMeta::setTitle('Dalal Street Benefits with Swipez');
        SEOMeta::setDescription('Dalal Street Benefits with Swipez');
        SEOMeta::addKeyword(['dalal street Benefits, dalal street Benefits for business, swipez billing software benefits. dalal street partner benefits']);
        SEOMeta::addMeta('application-name', $value = 'Dalal Street Benefits with Swipez', $name = 'name');
        SEOMeta::addMeta('name', $value = 'Swipez Billing software', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Dalal Street Benefits with Swipez', $name = 'itemprop');
        OpenGraph::setTitle('Dalal Street Benefits with Swipez');
        OpenGraph::setDescription('Dalal Street Benefits with Swipez');
        OpenGraph::setUrl('https://www.swipez.in/partner-benefits/dalal-street');
        JsonLd::setTitle('Dalal Street Benefits with Swipez');
        JsonLd::setType('Product');
        JsonLd::setDescription('Dalal Street Benefits with Swipez');
        JsonLd::addValue('review', json_decode('[{"@type":"Review","author":"Jayesh Shah","datePublished":"2018-04-01","description":"Now I can easily send & reconcile our companies telephone and internet bills with multiple payment options to thousands of our customers via e-mail and SMS at the click of a button through Swipez.","name":"Value purchase","reviewRating":{"@type":"Rating","bestRating":"5","ratingValue":"1","worstRating":"1"}},{"@type":"Review","author":"Vikas Sankhla","datePublished":"2018-03-25","description":"We are now managing payments across our complete customer base along with timely pay outs for all franchisees across the country from one dashboard. My team has saved over hundred hours after adopting Swipez Billing.","name":"Value purchase","reviewRating":{"@type":"Rating","bestRating":"5","ratingValue":"4","worstRating":"1"}}]', 1));
        JsonLd::addValue('aggregateRating', json_decode('{"@type":"AggregateRating","ratingValue":"4.4","reviewCount":"89"}', 1));
        JsonLd::setImage('https://www.swipez.in/images/benefits/dsij/dsijlogo.png');
        $data['CAPTCHA_CLIENT_ID'] = env('V3_CAPTCHA_CLIENT_ID');
        $data['GOOGLE_AUTH_CLIENT_ID'] = env('GOOGLE_AUTH_CLIENT_ID');
        $data['javascript'] = array('register');
        $data['service_id'] = '9';
        $data['jsonld'] = true;

        Session::put('service_id', '9');
        return view('home/benefit/dalal_street', $data);
    }
    public function scatter_content()
    {
        SEOMeta::setTitle('Scatter Content Creation Benefits with Swipez');
        SEOMeta::setDescription('Scatter Content Creation Benefits with Swipez');
        SEOMeta::addKeyword(['Scatter Content Creation Benefits, Scatter Content Creation Benefits for business, swipez billing software benefits. scatter partner benefits']);
        SEOMeta::addMeta('application-name', $value = 'Scatter Content Creation Benefits with Swipez', $name = 'name');
        SEOMeta::addMeta('name', $value = 'Swipez Billing software', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Scatter Content Creation Benefits with Swipez', $name = 'itemprop');
        OpenGraph::setTitle('Scatter Content Creation Benefits with Swipez');
        OpenGraph::setDescription('Scatter Content Creation Benefits with Swipez');
        OpenGraph::setUrl('https://www.swipez.in/partner-benefits/scatter');
        JsonLd::setTitle('Scatter Content Creation Benefits with Swipez');
        JsonLd::setType('Product');
        JsonLd::setDescription('Scatter Content Creation Benefits with Swipez');
        JsonLd::addValue('review', json_decode('[{"@type":"Review","author":"Jayesh Shah","datePublished":"2018-04-01","description":"Now I can easily send & reconcile our companies telephone and internet bills with multiple payment options to thousands of our customers via e-mail and SMS at the click of a button through Swipez.","name":"Value purchase","reviewRating":{"@type":"Rating","bestRating":"5","ratingValue":"1","worstRating":"1"}},{"@type":"Review","author":"Vikas Sankhla","datePublished":"2018-03-25","description":"We are now managing payments across our complete customer base along with timely pay outs for all franchisees across the country from one dashboard. My team has saved over hundred hours after adopting Swipez Billing.","name":"Value purchase","reviewRating":{"@type":"Rating","bestRating":"5","ratingValue":"4","worstRating":"1"}}]', 1));
        JsonLd::addValue('aggregateRating', json_decode('{"@type":"AggregateRating","ratingValue":"4.4","reviewCount":"89"}', 1));
        JsonLd::setImage('https://www.swipez.in/images/benefits/scattercontentcreationservices/scatterlogo.png');
        $data['CAPTCHA_CLIENT_ID'] = env('V3_CAPTCHA_CLIENT_ID');
        $data['GOOGLE_AUTH_CLIENT_ID'] = env('GOOGLE_AUTH_CLIENT_ID');
        $data['javascript'] = array('register');
        $data['service_id'] = '9';
        $data['jsonld'] = true;

        Session::put('service_id', '9');
        return view('home/benefit/scatter_content', $data);
    }
    public function swipez_sms()
    {
        SEOMeta::setTitle('SMS Notification Benefits with Swipez');
        SEOMeta::setDescription('Swipez SMS notification package');
        SEOMeta::addKeyword(['SMS Notification Benefits, SMS Notification Benefits for business, swipez billing software benefits. sms partner benefits']);
        SEOMeta::addMeta('application-name', $value = 'SMS Notification Benefits with Swipez', $name = 'name');
        SEOMeta::addMeta('name', $value = 'Swipez Billing software', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'SMS Notification Benefits with Swipez', $name = 'itemprop');
        OpenGraph::setTitle('SMS Notification Benefits with Swipez');
        OpenGraph::setDescription('SMS Notification Benefits with Swipez');
        OpenGraph::setUrl('https://www.swipez.in/partner-benefits/sms');
        JsonLd::setTitle('SMS Notification Benefits with Swipez');
        JsonLd::setType('Product');
        JsonLd::setDescription('SMS Notification Benefits with Swipez');
        JsonLd::addValue('review', json_decode('[{"@type":"Review","author":"Jayesh Shah","datePublished":"2018-04-01","description":"Now I can easily send & reconcile our companies telephone and internet bills with multiple payment options to thousands of our customers via e-mail and SMS at the click of a button through Swipez.","name":"Value purchase","reviewRating":{"@type":"Rating","bestRating":"5","ratingValue":"1","worstRating":"1"}},{"@type":"Review","author":"Vikas Sankhla","datePublished":"2018-03-25","description":"We are now managing payments across our complete customer base along with timely pay outs for all franchisees across the country from one dashboard. My team has saved over hundred hours after adopting Swipez Billing.","name":"Value purchase","reviewRating":{"@type":"Rating","bestRating":"5","ratingValue":"4","worstRating":"1"}}]', 1));
        JsonLd::addValue('aggregateRating', json_decode('{"@type":"AggregateRating","ratingValue":"4.4","reviewCount":"89"}', 1));
        JsonLd::setImage('https://www.swipez.in/images/benefits/swipezwebsitebuilder/swipezlogo.png');
        $data['CAPTCHA_CLIENT_ID'] = env('V3_CAPTCHA_CLIENT_ID');
        $data['GOOGLE_AUTH_CLIENT_ID'] = env('GOOGLE_AUTH_CLIENT_ID');
        $data['javascript'] = array('register');
        $data['service_id'] = '9';
        $data['jsonld'] = true;

        Session::put('service_id', '9');
        return view('home/benefit/swipez_sms', $data);
    }
    public function website_builder()
    {
        SEOMeta::setTitle('Free Website Builder Services Benefits with Swipez');
        SEOMeta::setDescription('Free Website Builder Services Benefits with Swipez');
        SEOMeta::addKeyword(['Website Builder Benefits, Website Builder Benefits for business, swipez billing software benefits. Free Website Builder Services']);
        SEOMeta::addMeta('application-name', $value = 'Swipez website builder', $name = 'name');
        SEOMeta::addMeta('name', $value = 'Swipez Billing software', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Swipez website builder', $name = 'itemprop');
        OpenGraph::setTitle('Free Website Builder Services Benefits with Swipez');
        OpenGraph::setDescription('Free Website Builder Services Benefits with Swipez');
        OpenGraph::setUrl('https://www.swipez.in/partner-benefits/website-builder');
        JsonLd::setTitle('Free Website Builder Services Benefits with Swipez');
        JsonLd::setType('Product');
        JsonLd::setDescription('Free Website Builder Services Benefits with Swipez');
        JsonLd::addValue('review', json_decode('[{"@type":"Review","author":"Jayesh Shah","datePublished":"2018-04-01","description":"Now I can easily send & reconcile our companies telephone and internet bills with multiple payment options to thousands of our customers via e-mail and SMS at the click of a button through Swipez.","name":"Value purchase","reviewRating":{"@type":"Rating","bestRating":"5","ratingValue":"1","worstRating":"1"}},{"@type":"Review","author":"Vikas Sankhla","datePublished":"2018-03-25","description":"We are now managing payments across our complete customer base along with timely pay outs for all franchisees across the country from one dashboard. My team has saved over hundred hours after adopting Swipez Billing.","name":"Value purchase","reviewRating":{"@type":"Rating","bestRating":"5","ratingValue":"4","worstRating":"1"}}]', 1));
        JsonLd::addValue('aggregateRating', json_decode('{"@type":"AggregateRating","ratingValue":"4.4","reviewCount":"89"}', 1));
        JsonLd::setImage('https://www.swipez.in/images/benefits/swipezwebsitebuilder/swipezlogo.png');
        $data['CAPTCHA_CLIENT_ID'] = env('V3_CAPTCHA_CLIENT_ID');
        $data['GOOGLE_AUTH_CLIENT_ID'] = env('GOOGLE_AUTH_CLIENT_ID');
        $data['javascript'] = array('register');
        $data['service_id'] = '9';
        $data['jsonld'] = true;

        Session::put('service_id', '9');
        return view('home/benefit/website_builder', $data);
    }

    public function quickbooks()
    {
        SEOMeta::setTitle('Top QuickBooks Alternative | Features & Comparison  Swipez.in');
        SEOMeta::setDescription('Swipez accounting software is a powerful, easy-to-use tool. Small business owners and entrepreneurs are choosing to Swipez as a QuickBooks alternative Billing Software.');
        SEOMeta::addKeyword(['Online Invoicing', 'Recurring invoicing', 'Free invoice software', 'Online Invoices', 'Online Invoices Software', 'Create invoices online', 'Free online invoice Maker', 'Free invoice generator', 'Invoice Generator', 'online invoicing software', 'online invoicing software india', 'free invoicing software', 'Online invoicing generator', 'Create Invoices Online', 'Quickbooks Alternative', 'Quickbooks Alternative India', 'Online Accounting Software']);
        SEOMeta::addMeta('application-name', $value = 'Swipez GST return filing software', $name = 'name');
        SEOMeta::addMeta('name', $value = 'Swipez Billing software', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Automated GST filing solution for your business.', $name = 'itemprop');
        OpenGraph::setTitle('Top QuickBooks Alternative | Features & Comparison  Swipez.in');
        OpenGraph::setDescription('Swipez accounting software is a powerful, easy-to-use tool. Small business owners and entrepreneurs are choosing to Swipez as a QuickBooks alternative Billing Software.');
        OpenGraph::setUrl('https://www.swipez.in/gst-filing');
        JsonLd::setTitle('Online Invoicing Software');
        JsonLd::setType('Product');
        JsonLd::setDescription('Swipez accounting software is a powerful, easy-to-use tool. Small business owners and entrepreneurs are choosing to Swipez as a QuickBooks alternative Billing Software.');
        JsonLd::addValue('review', json_decode('[{"@type":"Review","author":"Jayesh Shah","datePublished":"2018-04-01","description":"Now I can easily send & reconcile our companies telephone and internet bills with multiple payment options to thousands of our customers via e-mail and SMS at the click of a button through Swipez.","name":"Value purchase","reviewRating":{"@type":"Rating","bestRating":"5","ratingValue":"1","worstRating":"1"}},{"@type":"Review","author":"Vikas Sankhla","datePublished":"2018-03-25","description":"We are now managing payments across our complete customer base along with timely pay outs for all franchisees across the country from one dashboard. My team has saved over hundred hours after adopting Swipez Billing.","name":"Value purchase","reviewRating":{"@type":"Rating","bestRating":"5","ratingValue":"4","worstRating":"1"}}]', 1));
        JsonLd::addValue('aggregateRating', json_decode('{"@type":"AggregateRating","ratingValue":"4.4","reviewCount":"89"}', 1));
        JsonLd::setImage('https://www.swipez.in/images/product/gst-filing.svg');
        $data['CAPTCHA_CLIENT_ID'] = env('V3_CAPTCHA_CLIENT_ID');
        $data['GOOGLE_AUTH_CLIENT_ID'] = env('GOOGLE_AUTH_CLIENT_ID');
        $data['javascript'] = array('register');
        $data['service_id'] = '9';
        $data['jsonld'] = true;

        Session::put('service_id', '9');
        return view('home/quickbooks', $data);
    }
}
