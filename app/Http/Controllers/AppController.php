<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Libraries\Encrypt;
use Illuminate\Support\Facades\View;
use App\Libraries\HTMLValidationPrinter;

class AppController extends Controller
{
    public $merchant_id = null;
    public $user_id = null;

    public function __construct()
    {
       
        $this->merchant_id = Encrypt::decode(Session::get('merchant_id'));
        $this->user_id = Encrypt::decode(Session::get('userid'));
        if ($this->merchant_id == '') {
            header('location: /patron/dashboard');
           // exit;
        }
    }

    public function setBladeProperties($title, $jsFiles, $selectedMenu)
    {
        $data['title'] = $title;
        $data['jsfile'] = $jsFiles;
        $data['datatablejs'] = '';
        $data['script'] = '';
        $data['selectedMenu'] = $selectedMenu;
        View::share('server_name', config('app.url') . '/');
        View::share('menus', Session::get('menus'));
        View::share('has_master_login', 0);
        View::share('display_name', Session::get('display_name') . ' - ' . Session::get('company_name'));
        View::share('validate', HTMLValidationPrinter::fetch());
        View::share('post', $_POST);
        View::share('current_year', date('Y'));

        if (Session::has('help_hero_popup')) {
            if (Session::get('help_hero_popup') == 1) {
                View::share('help_hero', 1);
                View::share('merchant_id', $this->merchant_id);
                View::share('created_date', Session::get('created_date'));
            }
        }

        if (Session::has('customer_default_column')) {
            View::share('customer_default_column', Session::get('customer_default_column'));
        }

        $is_paid_package = Session::get('is_paid_package');
        $registered_on =  Session::get('created_date');
        $is_smart_look_active = env('IS_SMART_LOOK_ACTIVE');
        $registered_after = '2021-11-01';

        if ($is_paid_package == 0 && $is_smart_look_active == 1 && $registered_after <= $registered_on) {
            View::share('show_smart_look_script', true);
        }

        $this->setLanguage();
        return $data;
    }

    public function setZeroValue($array)
    {
        foreach ($array as $key) {
            if (!isset($_POST[$key])) {
                $_POST[$key] = 0;
            }
            if ($_POST[$key] > 0) {
            } else {
                $_POST[$key] = 0;
            }
        }
    }

    public function setEmptyArray($array)
    {
        foreach ($array as $key) {
            if (!isset($_POST[$key])) {
                $_POST[$key] = array();
            }
        }
    }

    public  function setLanguage()
    {
        $language = Session::get('language');
        if (!Session::has('language')) {
            Session::put('language', 'english');
            $language = 'english';
        }
        $json = '{"hindi":{"menu":{"1":"डैशबोर्ड","update_profile":"प्रोफ़ाइल अपडेट करें","company_profile":"कंपनी प्रोफाइल","package_details":"पैकेज विस्तार","password_reset":"पासवर्ड बदलना","logout":"साइन आउट","2":"संपर्क","15":"ग्राहक","67":"संरचना","68":"ग्राहक बनाये ","69":"ग्राहक  देखें ","70":"सामूहिक ग्राहकों  को बनाये ","71":"समूह का प्रबंधन ","73":"लंबित अनुमोदन","16":"विक्रेता","74":"विक्रेता बनाये ","75":"विक्रेता देखें ","76":"सामूहिक विक्रेताओं को  बनाये ","17":"मताधिकार","77":"मताधिकार बनाये ","78":"मताधिकार देखें ","79":"सामूहिक मताधिकारों   को बनाये ","3":"भुगतान लें ","18":"चालान प्रारूप","80":"प्रारूप बनाये ","81":"प्रारूप लिस्ट ","19":"व्यक्तिगत अनुरोध भेजें ","20":"सामूहिक अनुरोधों  को बनाये ","21":"सदस्यता बनाएं ","22":"प्रत्यक्ष भुगतान लिंक पाएं ","4":"पैसे भेजिये","23":"पैसे भेजना आरंभ करें","24":"पैसे का लेन-देन","25":"सामूहिक पैसे भेजिये","26":"लंबित अदायगी","27":"केंद्रीय  बहीखाता बयान ","5":"अनुरोध","28":"व्यक्तिगत अनुरोध","29":"सामूहिक अनुरोध","118":"बनायीं  गयी सदस्यता ","30":"सामूहिक लेन-देन करें","6":"लेन-देन ","31":"भुगतान  लेन-देन ","32":"वेबसाइट के लेन -देन","33":"फ्रॉम निर्माता  के लेन -देन","34":"प्लान के लेन -देन","7":"वेबसाइट निर्माता ","8":"आयोजन","35":"आयोजना  बनाये ","36":"आयोजना लिस्ट ","37":"आयोजनाओ के लेन -देन","9":"बुकिंग कैलेंडर","38":"कैलेंडर्स ","39":"ऑफ़लाइन बुकिंग ","41":"बुकिंग  के लेन -देन","10":"प्रचार","43":"प्रचार बनाये ","44":"प्रचार लिस्ट ","12":"ज़ी एस टी","50":"चालान अपलोड  ","52":"ज़ीएसटीआर १ ","90":"ज़ी एस टी आर १  अपलोड","invoice_listing":"चालान  लिस्ट ","save_as_drafts":"ड्राफ्ट के रूप में बचाने के लिए","91":"जीएसटी के लिए प्रस्तुत","51":"जीएसटी आर ३ बी ","88":"जीएसटी आर ३ बी","89":"फाइल अपलोड","53":"जीएसटी आर २ ","49":"जीएसटी संपर्क","13":"रिपोर्ट","54":"भुगतान संग्रह","94":"वेबसाइट भुगतान","95":"पैकेज भुगतान","96":"वहीखाता रिपोर्ट ","97":"टी डी र स ","98":"कूपन विश्लेषण ","55":"चालान","99":"चालान विवरण","100":"आंकलित  विवरण","101":"विलम्ब सारांश","102":"विलम्ब विवरण","93":"प्राप्त भुगतान","103":"अपेक्षित भुगतान","104":"टैक्स सारांश","105":"टैक्स विवरण","56":"अदायगी","106":"अदायगी सारांश","107":"अदायगी विवरण","108":"धन-वापसी विवरण","57":"फ्रॉम निर्माता ","109":"फ्रॉम निर्माता  डाटा","14":"डाटा  को  रूप देना","58":"सामान्य सेटिंग्स","59":"आपूर्तिकर्ता ","62":"योजनाये ","61":"कूपन ","60":"उत्पाद","64":"भूमिकाएं ","65":"उप उपयोगकर्ता","66":"ईमेल संरचना ","110":"केबल","111":"सेट टॉप बॉक्स","112":"ग्राहक पैकेज","11":"पुरस्कार","45":"स्कैन QR","46":"कमाया अंक","47":"भुनाएं अंक","48":"सेटिंग्स"},"title":{"merchant_dashboard":"व्यापारी डैशबोर्ड","total_customer":"कुल ग्राहक","current_month_transaction":"वर्तमान माह के लेन-देन","current_month_settlement":"वर्तमान महीने की बस्तियाँ","sms_sent":"SMS भेजी","notification":"सूचनाएं","payment_received":"भुगतान प्राप्त","billing_status":"बिलिंग स्थिति","contact":"संपर्क","customer_code":"ग्राहक क्रमांक","customer_name":"ग्राहक का नाम ","email":"ईमेल","mobile":"मोबाइल","address":"पता","city":"शहर","state":"राज्य","zipcode":"पिन कोड","status":"स्थिति","payment":"भुगतान","action":"क्रिया","customer_list":"ग्राहक सूची","change_search_criteria":"खोज प्रकार बदलें","excel_export":"एक्सेल एक्सपोर्ट","search":"खोज","custom_column":"कस्टम कॉलम","choose_group":"समूह चुनें","view_customer":"ग्राहक विस्तार","system_fields":"सिस्टम फील्ड्स","custom_fields":"कस्टम फील्ड्स","invoice":"चालान","tax":"कर","billing_summary":"बिलिंग सारांश","past_due":"पिछली देय राशि","current_charges":"वर्तमान शुल्क","total_due":"कुल देय राशि","sn":"क्रमांक","description":"तफ़सील","time_period":"समय सीमा","amount_rs":"राशि","sub_total":"उप कुल","total_rs":"कुल रकम","invoice_note":"नोट: यह एक सिस्टम जनरेट चालान है। कोई हस्ताक्षर की आवश्यकता नहीं है।","total_amount_payable":"कुल भुगतान राशि","pan_no":"पैन नंबर","gst_no":"जी एस टी नंबर","amount_in_word":"राशि शब्दों में","pay_now":"भुगतान करें","save_pdf":"पीडीएफ़ डाउनलोड","footer_note":"यदि आप अपने व्यवसाय के लिए ऑनलाइन भुगतान करना चाहते हैं, तो स्वाइप पर <a target=\"_BLANK\" href=\"\/merchant\/register\">अभी रजिस्टर <\/a> करें।","convenience_fee":"सुविधा शुल्क","enter_billing_details":"बिलिंग विवरण दर्ज करें","confirm_note":"कृपया ध्यान दें: भुगतान करते समय हम आपके किसी भी कार्ड / खाते की जानकारी संग्रहीत नहीं करते हैं। ऑनलाइन भुगतान के लिए, हम आपको अपना कार्ड / खाता क्रेडेंशियल्स प्रदान करने के लिए एक सुरक्षित बैंकिंग / भुगतान गेटवे वेबसाइट पर पुनर्निर्देशित कर सकते हैं।","i_accept":"मैं स्वीकारता हूँ","terms_conditions":"नियम और शर्तें","privacy_policy":"गोपनीयता नीति","click_here":"भुगतान करने के लिए यहां क्लिक करें","send_promo_sms":"प्रचार एस एम एस भेजें","promotion_name":"प्रचार नाम","select_sms":"एस एम एस का चयन करें","message":"संदेश","new_sms":"नया संदेश","promotion_list":"प्रचार सूची","create_date":"रचना तिथि","records":"रेकार्ड","comma_mobile":"मोबाइल ( बहुत के लिए अल्पविराम)"}},"english":{"menu":{"dashboard":"Dashboard","update_profile":"Update profile","company_profile":"Company profile","package_details":"Package details","password_reset":"Password reset","logout":"Logout","contacts":"Contacts","customer":"Customer","structure":"Structure","create_customer":"Create Customer","view_customer":"View Customer","bulk_upload_customer":"Bulk Upload Customer","manage_group":"Manage Group","pending_approvals":"Pending Approvals","vendors":"Vendors","create_vendor":"Create Vendor","view_vendor":"View Vendor","bulk_upload_vendors":"Bulk Upload Vendors","franchise":"Franchise","create_franchise":"Create Franchise","view_franchise":"View Franchise","bulk_upload_franchise":"Bulk Upload Franchise","collect_payments":"Collect Payments","invoice_formats":"Invoice Formats","create_format":"Create Format","list_format":"List Format","send_individual_request":"Send Individual Request","bulk_upload_request":"Bulk Upload Request","create_subscription":"Create Subscription ","get_direct_pay_link":"Get Direct Pay Link","make_transfer":"Make Transfer","initiate_transfer":"Initiate Transfer","transfer_transactions":"Transfer Transactions","bulk_upload_transfer":"Bulk Upload Transfer","pending_settelments":"Pending Settelments","nodal_ledger_statements":"Nodal Ledger Statements","requests":"Requests","individual_requests":"Individual Requests","bulk_requests":"Bulk Requests","subscription_created":"Subscription Created","bulk_upload_transaction":"Bulk Upload Transaction ","transactions":"Transactions","payment_transactions":"Payment Transactions","website_transactions":"Website Transactions","form_builder_transactions":"Form Builder Transactions","plan_transactions":"Plan Transactions","site_builder":"Site Builder","events":"Events","create_events":"Create Events","list_events":"List Events","event_transactions":"Event Transactions","booking_calendar":"Booking Calendar","calendars":"Calendars","offline_bookings":"Offline Bookings","booking_transactions":"Booking Transactions","promotions":"Promotions","create_promotions":"Create Promotions","list_promotions":"List Promotions","gst":"GST","invoice_upload":"Upload your invoices","gstr1":"GSTR 1","gstr1_upload":"Prepare GSTR 1","invoice_listing":"Invoice Listing ","save_as_drafts":"Submit to GST","submit_to_gst":"Submission status","gstr3b":"GSTR 3B","generate_file":"Generate summary","file_upload":"Upload GSTR 3B","gstr2":"GSTR2","gst_connection":"GST Connection","reports":"Reports","collections":"Collections","payment_received":"Payment Received","website_payment_received":"Website Payment Received","plan_payment_received":"Plan Payment Received","ledger_reports":"Ledger Reports","tdrs":"TDRs","coupon_analytics":"Coupon Analytics","invoicing":"Invoicing","invoice_details":"Invoice Details","estimate_details":"Estimate Details","aging_summary":"Aging Summary","aging_details":"Aging Details","payment_expected":"Payment Expected","tax_summary":"Tax Summary ","tax_details":"Tax Details","settlements":"Settlements","settlements_summary":"Settlements Summary","settlements_details":"Settlements Details","refund_details":"Refund Details","form_builder":"Form Builder","form_builder_data":"Form Builder Data","data_configuration":"Data Configuration","general_settings":"General Settings","suppliers":"Suppliers","plans":"Plans","coupons":"Coupons","product":"Products","roles":"Roles","sub_merchants":"Sub Merchants","covering_notes":"Covering Notes","cable":"Cable","set_top_box":"Set top box","customer_packages":"Customer packages","loyalty":"Loyalty","scan_qr":"Scan QR","earned_points":"Earned points","redeem_points":"Redeem points","settings":"Settings"},"title":{"merchant_dashboard":"Merchant dashboard","total_customer":"Total customers","current_month_transaction":"Current Month Transactions","current_month_settlement":"Current Month Settlements","sms_sent":"SMS sent","notification":"NOTIFICATIONS","payment_received":"PAYMENT RECEIVED","billing_status":"BILLING STATUS","contact":"Contact","customer_code":"Customer code","customer_name":"Customer name","email":"Email","mobile":"Mobile","country":"Country","address":"Address","city":"City","state":"State","zipcode":"Zipcode","status":"Status","payment":"Payment","action":"Action","customer_list":"Customer list","change_search_criteria":"Change search criteria","excel_export":"Excel export","search":"Search","custom_column":"Custom column","choose_group":"Choose group","view_customer":"View customer","system_fields":"System fields","custom_fields":"Custom fields","invoice":"Invoice","tax":"Tax","billing_summary":"Billing Summary","past_due":"Past Due","current_charges":"Current Charges","total_due":"Total Due","sn":"SN.","description":"Description","time_period":"Time Period","amount_rs":"Amount Rs.","sub_total":"SUB TOTAL","total_rs":"Total Rs.","invoice_note":"Note: This is a system generated invoice. No signature required.","total_amount_payable":"Total Amount Payable","pan_no":"PAN NO","gst_no":"GST Number","amount_in_word":"Amount (in words)","pay_now":"Pay now","save_pdf":"Save as PDF","footer_note":"If you would like to collect online payments for your business, <a target=\"_BLANK\" href=\"\/merchant\/register\">register now<\/a> on Swipez.","convenience_fee":"Convenience fees","enter_billing_details":"Enter billing details","confirm_note":"Please note: We do not store any of your card/ account information when you make a payment. For online payment, we may redirect you to a secure banking/payment gateway website to provide your card/account credentials.","i_accept":"I accept the","terms_conditions":"Terms and conditions","privacy_policy":"Privacy policy","click_here":"Click here to make payment","send_promo_sms":"Send Promo SMS","promotion_name":"Promotion name","select_sms":"Select SMS","message":"Message","new_sms":"New SMS Template","promotion_list ":"Promotion list","create_date ":"Created on","records":"Records","comma_mobile":"Mobile (comma for multiple)"}}}';
        $array = json_decode($json, 1);
        View::share('language', $language);
        View::share('lang', $array[$language]);
        View::share('lang_title', $array[$language]['title']);
        View::share('menu', $array[$language]['menu']);
    }
}
