<?php

namespace App\Http\Controllers;

use DOMPDF;
use Illuminate\Http\Request;
use App\Model\DownlodInvoiceLog;
use App\Libraries\Helpers;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\JsonLd;
use Exception;
use Validator;
use App\Model\InvoiceFormat;

class DownloadInvoiceFormatsController extends Controller
{

    public $formatModel;
    /**
     * Invoice formats landing page
     *
     * @return void
     */
    public function index()
    {
        SEOMeta::setTitle('Invoice Format | Billing Format | Download Invoice Format -Swipez');
        SEOMeta::setDescription('Download customized invoice templates for sales, consultancy firms, travel agency or hospitality businesses. Easy to create and send professional invoices or bills in required formats.');
        SEOMeta::addKeyword(['download excel invoice format', 'blank invoice template excel', 'retail invoice format in excel sheet free download', 'gst invoice format in excel download', 'gst tax invoice format in excel free download', 'gst tax invoice format in excel', 'travel agency invoice format excel download','invoice format','billing format','bill format','gst bill format','gst invoice format','tax invoice format','sample invoice format']);
        SEOMeta::addMeta('application-name', $value = 'Swipez Billing Software', $name = 'name');
        SEOMeta::addMeta('name', $value = 'Swipez Billing Software', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Download invoice formats for your business', $name = 'itemprop');
        OpenGraph::setTitle('Invoice Format | Billing Format | Download Invoice Format -Swipez');
        OpenGraph::setDescription('Download customized invoice templates for sales, consultancy firms, travel agency or hospitality businesses. Easy to create and send professional invoices or bills in required formats.');
        OpenGraph::setUrl("https://www.swipez.in/" . request()->path());
        JsonLd::setTitle('Invoice Format');
        JsonLd::setType('Product');
        JsonLd::setDescription('Download customized invoice templates for sales, consultancy firms, travel agency or hospitality businesses. Easy to create and send professional invoices or bills in required formats.');
        JsonLd::addValue('review', json_decode('[{"@type": "Review","author": "Shumu Gupta","datePublished": "2018-04-01","description": "Swipez invoicing is flexible and perfect for our business needs. We have been able to customize the invoices and create it to meet out business requirements.","name": "Value purchase","reviewRating": {"@type": "Rating","bestRating": "5","ratingValue": "4","worstRating": "1"}},{"@type": "Review","author": "Debarshi Dutta","datePublished": "2018-03-25","description": "Invoicing is so much easier and faster. We have been using the free plan and its been perfect for our business needs.","name": "Value purchase","reviewRating": {"@type": "Rating","bestRating": "5","ratingValue": "5","worstRating": "1"}}]', 1));
        JsonLd::addValue('aggregateRating', json_decode('{"@type":"AggregateRating","ratingValue":"4.7","reviewCount":"139"}', 1));
        JsonLd::setImage('https://www.swipez.in/images/product/billing-software/invoice-formats.webp');
        $data['CAPTCHA_CLIENT_ID'] = env('V3_CAPTCHA_CLIENT_ID');
        $data['GOOGLE_AUTH_CLIENT_ID'] = env('GOOGLE_AUTH_CLIENT_ID');
        $data['javascript'] = array('register');
        $data['service_id'] = '2';
        $data['jsonld'] = true;
        $data['format_name'] = 'sales';
        return view('download-invoice-formats/index', $data);
    }

    /**
     * Invoice templates landing page
     *
     * @return void
     */
    public function invoiceTemplate()
    {
        SEOMeta::setTitle('Invoice Template | Download Invoice Templates - Swipez');
        SEOMeta::setDescription('Download Free Invoice Template, Invoice format for utilities, fashion, shipping, retail, IT. Add your own brand logo & color for Invoice template. All excel, pdf, word invoice format available.');
        SEOMeta::addKeyword(['invoice template in word','invoice template in pdf','invoice template in excel','free invoice template',' download free invoice templates','download invoice template','standard invoice template','invoice templates','invoice template','free invoice template','free invoice template download','free invoice template']);
        SEOMeta::addMeta('application-name', $value = 'Swipez Billing Software', $name = 'name');
        SEOMeta::addMeta('name', $value = 'Swipez Billing Software', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Download invoice templates for your business', $name = 'itemprop');
        OpenGraph::setTitle('Invoice Template | Download Invoice Templates - Swipez');
        OpenGraph::setDescription('Download Free Invoice Template, Invoice format for utilities, fashion, shipping, retail, IT. Add your own brand logo & color for Invoice template. All excel, pdf, word invoice format available.');
        OpenGraph::setUrl("https://www.swipez.in/" . request()->path());
        JsonLd::setTitle('Download Invoice Templates');
        JsonLd::setType('Product');
        JsonLd::setDescription('Download Free Invoice Template, Invoice format for utilities, fashion, shipping, retail, IT. Add your own brand logo & color for Invoice template. All excel, pdf, word invoice format available.');
        JsonLd::addValue('review', json_decode('[{"@type": "Review","author": "Shumu Gupta","datePublished": "2018-04-01","description": "Swipez invoicing is flexible and perfect for our business needs. We have been able to customize the invoices and create it to meet out business requirements.","name": "Value purchase","reviewRating": {"@type": "Rating","bestRating": "5","ratingValue": "4","worstRating": "1"}},{"@type": "Review","author": "Debarshi Dutta","datePublished": "2018-03-25","description": "Invoicing is so much easier and faster. We have been using the free plan and its been perfect for our business needs.","name": "Value purchase","reviewRating": {"@type": "Rating","bestRating": "5","ratingValue": "5","worstRating": "1"}}]', 1));
        JsonLd::addValue('aggregateRating', json_decode('{"@type":"AggregateRating","ratingValue":"4.7","reviewCount":"139"}', 1));
        JsonLd::setImage('https://www.swipez.in/images/product/billing-software/invoice-formats.webp');
        $data['CAPTCHA_CLIENT_ID'] = env('V3_CAPTCHA_CLIENT_ID');
        $data['GOOGLE_AUTH_CLIENT_ID'] = env('GOOGLE_AUTH_CLIENT_ID');
        $data['javascript'] = array('register');
        $data['service_id'] = '2';
        $data['jsonld'] = true;
        $data['format_name'] = 'sales';
        $this->formatModel = new InvoiceFormat();
        $formateData = $this->formatModel->getSystemTemplateDesign();
      
        $data['templateList'] = json_decode($formateData, 1);


        return view('download-invoice-formats/invoice-template', $data);
    }

    /**
     * Invoice template excel landing page
     *
     * @return void
     */
    public function invoiceTemplateExcel()
    {
        SEOMeta::setTitle('Free Excel Invoice Template | Free to Edit & Download-Swipez');
        SEOMeta::setDescription('Use Swipez free and fully customizable Excel invoice template.  Free to download Excel Invoice templates for all small businesses. Excel invoice templates like Standard Invoice,Debit Invoice,Commercial Invoice, Credit Invoice.');
        SEOMeta::addKeyword(['Free excel invoice template',' editable excel invoice template',' download free excel invoice  template',' free excel invoice template for Businesses',' Standard excel invoice template']);
        SEOMeta::addMeta('application-name', $value = 'Swipez Billing Software', $name = 'name');
        SEOMeta::addMeta('name', $value = 'Swipez Billing Software', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Download invoice formats for your business', $name = 'itemprop');
        OpenGraph::setTitle('Free Excel Invoice Template | Free to Edit & Download-Swipez');
        OpenGraph::setDescription('Use Swipez free and fully customizable Excel invoice template.  Free to download Excel Invoice templates for all small businesses. Excel invoice templates like Standard Invoice,Debit Invoice,Commercial Invoice, Credit Invoice.');
        OpenGraph::setUrl("https://www.swipez.in/" . request()->path());
        JsonLd::setTitle('Free excel invoice template');
        JsonLd::setType('Product');
        JsonLd::setDescription('Use Swipez free and fully customizable Excel invoice template.  Free to download Excel Invoice templates for all small businesses. Excel invoice templates like Standard Invoice,Debit Invoice,Commercial Invoice, Credit Invoice.');
        JsonLd::addValue('review', json_decode('[{"@type": "Review","author": "Shumu Gupta","datePublished": "2018-04-01","description": "Swipez invoicing is flexible and perfect for our business needs. We have been able to customize the invoices and create it to meet out business requirements.","name": "Value purchase","reviewRating": {"@type": "Rating","bestRating": "5","ratingValue": "4","worstRating": "1"}},{"@type": "Review","author": "Debarshi Dutta","datePublished": "2018-03-25","description": "Invoicing is so much easier and faster. We have been using the free plan and its been perfect for our business needs.","name": "Value purchase","reviewRating": {"@type": "Rating","bestRating": "5","ratingValue": "5","worstRating": "1"}}]', 1));
        JsonLd::addValue('aggregateRating', json_decode('{"@type":"AggregateRating","ratingValue":"4.7","reviewCount":"139"}', 1));
        JsonLd::setImage('https://www.swipez.in/images/product/billing-software/invoice-formats.webp');
        $data['CAPTCHA_CLIENT_ID'] = env('V3_CAPTCHA_CLIENT_ID');
        $data['GOOGLE_AUTH_CLIENT_ID'] = env('GOOGLE_AUTH_CLIENT_ID');
        $data['javascript'] = array('register');
        $data['service_id'] = '2';
        $data['jsonld'] = true;
        $data['format_name'] = 'sales';
        return view('download-invoice-formats/invoice-template-excel', $data);
    }

    /**
     * Invoice template word landing page
     *
     * @return void
     */
    public function invoiceTemplateWord()
    {
        SEOMeta::setTitle('Free Word Invoice Template | Free to Edit & Download-Swipez');
        SEOMeta::setDescription('Use Swipez free and fully customizable word invoice template.  Free to download word Invoice templates for all small businesses. Word invoice templates like Standard Invoice,Debit Invoice,Commercial Invoice, Credit Invoice.');
        SEOMeta::addKeyword(['Free word invoice template',' editable word invoice template','  download free word invoice  template',' free word invoice template for Businesses',' Standard word invoice template']);
        SEOMeta::addMeta('application-name', $value = 'Swipez Billing Software', $name = 'name');
        SEOMeta::addMeta('name', $value = 'Swipez Billing Software', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Download invoice formats for your business', $name = 'itemprop');
        OpenGraph::setTitle('Free Word Invoice Template | Free to Edit & Download-Swipez');
        OpenGraph::setDescription('Use Swipez free and fully customizable word invoice template.  Free to download word Invoice templates for all small businesses. Word invoice templates like Standard Invoice,Debit Invoice,Commercial Invoice, Credit Invoice.');
        OpenGraph::setUrl("https://www.swipez.in/" . request()->path());
        JsonLd::setTitle('Free Word Invoice Template');
        JsonLd::setType('Product');
        JsonLd::setDescription('Use Swipez free and fully customizable word invoice template.  Free to download word Invoice templates for all small businesses. Word invoice templates like Standard Invoice,Debit Invoice,Commercial Invoice, Credit Invoice.');
        JsonLd::addValue('review', json_decode('[{"@type": "Review","author": "Shumu Gupta","datePublished": "2018-04-01","description": "Swipez invoicing is flexible and perfect for our business needs. We have been able to customize the invoices and create it to meet out business requirements.","name": "Value purchase","reviewRating": {"@type": "Rating","bestRating": "5","ratingValue": "4","worstRating": "1"}},{"@type": "Review","author": "Debarshi Dutta","datePublished": "2018-03-25","description": "Invoicing is so much easier and faster. We have been using the free plan and its been perfect for our business needs.","name": "Value purchase","reviewRating": {"@type": "Rating","bestRating": "5","ratingValue": "5","worstRating": "1"}}]', 1));
        JsonLd::addValue('aggregateRating', json_decode('{"@type":"AggregateRating","ratingValue":"4.7","reviewCount":"139"}', 1));
        JsonLd::setImage('https://www.swipez.in/images/product/billing-software/invoice-formats.webp');
        $data['CAPTCHA_CLIENT_ID'] = env('V3_CAPTCHA_CLIENT_ID');
        $data['GOOGLE_AUTH_CLIENT_ID'] = env('GOOGLE_AUTH_CLIENT_ID');
        $data['javascript'] = array('register');
        $data['service_id'] = '2';
        $data['jsonld'] = true;
        $data['format_name'] = 'sales';
        return view('download-invoice-formats/invoice-template-word', $data);
    }

    /**
     * Invoice template pdf landing page
     *
     * @return void
     */
    public function invoiceTemplatePDF()
    {
        SEOMeta::setTitle('Free PDF Invoice Template | Free to Edit & Download-Swipez');
        SEOMeta::setDescription('Use Swipez free and fully customizable PDF invoice template.  Free to download PDF Invoice templates for all small businesses. PDF, invoice templates like Standard Invoice,Debit Invoice,Commercial Invoice, Credit Invoice.');
        SEOMeta::addKeyword(['Free pdf invoice template',' editable pdf invoice template',' download free pdf invoice template',' free pdf invoice template for Businesses',' Standard pdf invoice template']);
        SEOMeta::addMeta('application-name', $value = 'Swipez Billing Software', $name = 'name');
        SEOMeta::addMeta('name', $value = 'Swipez Billing Software', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Download invoice formats for your business', $name = 'itemprop');
        OpenGraph::setTitle('Free PDF Invoice Template | Free to Edit & Download-Swipez');
        OpenGraph::setDescription('Use Swipez free and fully customizable PDF invoice template.  Free to download PDF Invoice templates for all small businesses. PDF, invoice templates like Standard Invoice,Debit Invoice,Commercial Invoice, Credit Invoice.');
        OpenGraph::setUrl("https://www.swipez.in/" . request()->path());
        JsonLd::setTitle('Free PDF Invoice Template');
        JsonLd::setType('Product');
        JsonLd::setDescription('Use Swipez free and fully customizable PDF invoice template.  Free to download PDF Invoice templates for all small businesses. PDF, invoice templates like Standard Invoice,Debit Invoice,Commercial Invoice, Credit Invoice.');
        JsonLd::addValue('review', json_decode('[{"@type": "Review","author": "Shumu Gupta","datePublished": "2018-04-01","description": "Swipez invoicing is flexible and perfect for our business needs. We have been able to customize the invoices and create it to meet out business requirements.","name": "Value purchase","reviewRating": {"@type": "Rating","bestRating": "5","ratingValue": "4","worstRating": "1"}},{"@type": "Review","author": "Debarshi Dutta","datePublished": "2018-03-25","description": "Invoicing is so much easier and faster. We have been using the free plan and its been perfect for our business needs.","name": "Value purchase","reviewRating": {"@type": "Rating","bestRating": "5","ratingValue": "5","worstRating": "1"}}]', 1));
        JsonLd::addValue('aggregateRating', json_decode('{"@type":"AggregateRating","ratingValue":"4.7","reviewCount":"139"}', 1));
        JsonLd::setImage('https://www.swipez.in/images/product/billing-software/invoice-formats.webp');
        $data['CAPTCHA_CLIENT_ID'] = env('V3_CAPTCHA_CLIENT_ID');
        $data['GOOGLE_AUTH_CLIENT_ID'] = env('GOOGLE_AUTH_CLIENT_ID');
        $data['javascript'] = array('register');
        $data['service_id'] = '2';
        $data['jsonld'] = true;
        $data['format_name'] = 'sales';
        return view('download-invoice-formats/invoice-template-pdf', $data);
    }

    public function download(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required',
            'bill_date' => 'required',
            'p_description' => 'required',
            'company_name' => 'required',
            'name' => 'required|max:100'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        #save download format info in database
        $this->saveFormatLog($request);

        #add line break in terms and conditions
        if (isset($_POST['tnc'])) {
            $_POST['tnc'] = str_replace("\n", "<br>", $_POST['tnc']);
        }
        $data = $_POST;
        $data['rowspan'] = $this->getRowSpan(3);
        $data['logo'] = '';
        if (isset($_FILES['img']['tmp_name'])) {
            if ($_FILES['img']['tmp_name'] != '') {
                $data['logo'] = base64_encode(file_get_contents($_FILES['img']['tmp_name']));
            }
        }

        if (isset($_POST['print'])) {
            return view('download-invoice-formats.pdf.' . $request->type . '-pdf', $data);
        }
        //return view('download-invoice-formats.pdf.' . $request->type . '-pdf', $data);
        define("DOMPDF_ENABLE_HTML5PARSER", true);
        define("DOMPDF_ENABLE_FONTSUBSETTING", true);
        define("DOMPDF_UNICODE_ENABLED", true);
        define("DOMPDF_DPI", 120);
        define("DOMPDF_ENABLE_REMOTE", true);
        $pdf = DOMPDF::loadView('download-invoice-formats.pdf.' . $request->type . '-pdf', $data);
        $pdf->setPaper("a4", "portrait");
        return $pdf->download('invoice.pdf');
    }

    function getRowSpan($rowspan)
    {
        if (isset($_POST['tax1'])) {
            if ($_POST['tax1'] > 0) {
                $rowspan++;
            }
        }
        if (isset($_POST['tax2'])) {
            if ($_POST['tax2'] > 0) {
                $rowspan++;
            }
        }
        if (isset($_POST['tax3'])) {
            if (isset($_POST['tax3'])) {
                if ($_POST['tax3'] > 0) {
                    $rowspan++;
                }
            }
        }
        if (isset($_POST['tax4'])) {
            if ($_POST['tax4'] > 0) {
                $rowspan++;
            }
        }
        if (isset($_POST['past_due'])) {
            if ($_POST['past_due'] != '') {
                $rowspan++;
            }
        }
        if (isset($_POST['gst'])) {
            if ($_POST['gst'] != '') {
                $rowspan++;
            }
        }
        return $rowspan;
    }

    function saveFormatLog($request)
    {
        $formatLog = new DownlodInvoiceLog();
        $formatLog->format_name = isset($request->format_name) ? $request->format_name : 'NA';
        $formatLog->user_ip = $request->ip();
        $formatLog->company_name = $request->company_name;
        $formatLog->email = $request->merchant_email;
        $formatLog->mobile = substr($request->merchant_mobile, 0, 45);
        $formatLog->address = $request->merchant_address;
        $formatLog->save();
    }

    /**
     * Invoice formats landing page
     *
     * @return void
     */
    public function ispInvoiceFormat()
    {

        SEOMeta::setTitle('Download Free Invoice Templates For ISP, Broadband Services | Simple Invoice Formats | Swipez');
        SEOMeta::setDescription('Download customized invoice templates for ISP, and Broadband Services. Easy to create and send beautifully created invoices or bills in required formats.');
        SEOMeta::addKeyword(['internet invoice', 'internet invoice template', 'internet invoice sample', 'internet invoice template excel', 'internet bill format', 'download invoice format for broadbands']);
        SEOMeta::addMeta('application-name', $value = 'Swipez Billing Software', $name = 'name');
        SEOMeta::addMeta('name', $value = 'Swipez Billing Software', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Download invoice formats for your business', $name = 'itemprop');
        OpenGraph::setTitle('Download Free Invoice Templates For ISP, Broadband Services | Simple Invoice Formats | Swipez');
        OpenGraph::setDescription('Download customized invoice templates for ISP, and Broadband Services. Easy to create and send beautifully created invoices or bills in required formats.');
        OpenGraph::setUrl("https://www.swipez.in/" . request()->path());
        JsonLd::setTitle('Download ISP broadband invoice formats');
        JsonLd::setType('Product');
        JsonLd::setDescription('Download professional ISP broadband invoice formats to send to your customers. Send these invoices to customer via email or print outs.');
        JsonLd::addValue('review', json_decode('[{"@type": "Review","author": "Shumu Gupta","datePublished": "2018-04-01","description": "Swipez invoicing is flexible and perfect for our business needs. We have been able to customize the invoices and create it to meet out business requirements.","name": "Value purchase","reviewRating": {"@type": "Rating","bestRating": "5","ratingValue": "4","worstRating": "1"}},{"@type": "Review","author": "Debarshi Dutta","datePublished": "2018-03-25","description": "Invoicing is so much easier and faster. We have been using the free plan and its been perfect for our business needs.","name": "Value purchase","reviewRating": {"@type": "Rating","bestRating": "5","ratingValue": "5","worstRating": "1"}}]', 1));
        JsonLd::addValue('aggregateRating', json_decode('{"@type":"AggregateRating","ratingValue":"4.7","reviewCount":"139"}', 1));
        JsonLd::setImage('https://www.swipez.in/images/product/billing-software/invoice-formats.webp');

        $data['CAPTCHA_CLIENT_ID'] = env('V3_CAPTCHA_CLIENT_ID');
        $data['GOOGLE_AUTH_CLIENT_ID'] = env('GOOGLE_AUTH_CLIENT_ID');
        $data['javascript'] = array('register');
        $data['service_id'] = '2';
        $data['current_date'] = date('d M Y');
        $data['jsonld'] = true;
        $data['format_name'] = 'ISP';
        $data['title'] = 'Download invoice format for ISP and broadband';
        $data['description'] = 'Create and download ISP invoice format right here in your browser. Add your customers contact information and company details. Create new rows or columns, attach your logo and choose invoice colors as per your brand.';
        return view('download-invoice-formats/isp-invoice-format', $data);
    }

    public function salesInvoiceFormat()
    {

        SEOMeta::setTitle('Download Free Invoice Templates | Simple Invoice Formats For Suppliers, Manufacturers | Swipez');
        SEOMeta::setDescription('Download customized invoice templates for the sales of products, goods or services for your businesses. Easy to create and send beautifully created invoices or bills in required formats.');
        SEOMeta::addKeyword(['sales invoice', 'sales invoice template', 'sales invoice sample', 'sales invoice sample', 'sales invoice template excel', 'sales invoice template free', 'download sales invoice format']);
        SEOMeta::addMeta('application-name', $value = 'Swipez Billing Software', $name = 'name');
        SEOMeta::addMeta('name', $value = 'Swipez Billing Software', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Download invoice formats for your business', $name = 'itemprop');
        OpenGraph::setTitle('Download Free Invoice Templates | Simple Invoice Formats For Suppliers, Manufacturers | Swipez');
        OpenGraph::setDescription('Download customized invoice templates for the sales of products, goods or services for your businesses. Easy to create and send beautifully created invoices or bills in required formats.');
        OpenGraph::setUrl("https://www.swipez.in/" . request()->path());
        JsonLd::setTitle('Download sales invoice formats for your business');
        JsonLd::setType('Product');
        JsonLd::setDescription('Download professional sales invoice formats to send to your customers. Send these invoices to customer via email or print outs.');
        JsonLd::addValue('review', json_decode('[{"@type": "Review","author": "Shumu Gupta","datePublished": "2018-04-01","description": "Swipez invoicing is flexible and perfect for our business needs. We have been able to customize the invoices and create it to meet out business requirements.","name": "Value purchase","reviewRating": {"@type": "Rating","bestRating": "5","ratingValue": "4","worstRating": "1"}},{"@type": "Review","author": "Debarshi Dutta","datePublished": "2018-03-25","description": "Invoicing is so much easier and faster. We have been using the free plan and its been perfect for our business needs.","name": "Value purchase","reviewRating": {"@type": "Rating","bestRating": "5","ratingValue": "5","worstRating": "1"}}]', 1));
        JsonLd::addValue('aggregateRating', json_decode('{"@type":"AggregateRating","ratingValue":"4.7","reviewCount":"139"}', 1));
        JsonLd::setImage('https://www.swipez.in/images/product/billing-software/invoice-formats.webp');

        $data['CAPTCHA_CLIENT_ID'] = env('V3_CAPTCHA_CLIENT_ID');
        $data['GOOGLE_AUTH_CLIENT_ID'] = env('GOOGLE_AUTH_CLIENT_ID');
        $data['javascript'] = array('register');
        $data['service_id'] = '2';
        $data['current_date'] = date('d M Y');
        $data['jsonld'] = true;
        $data['format_name'] = 'sales';
        $data['title'] = 'Download sales invoice format';
        $data['description'] = 'Create and download sales invoice format right here in your browser. Free invoice format perfect for sales teams. Add your customers contact information and company details. Add new rows or columns, attach your logo and choose invoice colors as per your brand requirement.';

        $data['downloadTitle'] = 'Sales invoice format';
        $data['downloadText'] = 'Download sales invoice format. Edit the downloaded sales invoice format and send to your customers.';
        $data['downloadButtonTitle'] = 'Download sales invoice format';
        $data['creatorTitle'] = 'Create sales invoice online';
        $data['downloadFileName'] = 'SalesInvoiceTemplate.xlsx';

        return view('download-invoice-formats/sales-invoice-format', $data);
    }

    public function cableInvoiceFormat()
    {

        SEOMeta::setTitle('Download Free Invoice Templates For Cable TV Operators | Simple Invoice Formats | Swipez');
        SEOMeta::setDescription('Download customized invoice templates for cable TV services. Easy to create and send beautifully created invoices or bills in required formats.');
        SEOMeta::addKeyword(['cable invoice', 'cable invoice template', 'cable invoice sample', 'cable invoice template excel', 'cable bill format', 'download cable invoice format']);
        SEOMeta::addMeta('application-name', $value = 'Swipez Billing Software', $name = 'name');
        SEOMeta::addMeta('name', $value = 'Swipez Billing Software', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Download invoice formats for your business', $name = 'itemprop');
        OpenGraph::setTitle('Download Free Invoice Templates For Cable TV Operators | Simple Invoice Formats | Swipez');
        OpenGraph::setDescription('Download customized invoice templates for cable TV services. Easy to create and send beautifully created invoices or bills in required formats.');
        OpenGraph::setUrl("https://www.swipez.in/" . request()->path());
        JsonLd::setTitle('Download invoice formats for your business');
        JsonLd::setType('Product');
        JsonLd::setDescription('Download professional cable tv invoice formats to send to your customers. Send these invoices to customer via email or print outs.');
        JsonLd::addValue('review', json_decode('[{"@type": "Review","author": "Shumu Gupta","datePublished": "2018-04-01","description": "Swipez invoicing is flexible and perfect for our business needs. We have been able to customize the invoices and create it to meet out business requirements.","name": "Value purchase","reviewRating": {"@type": "Rating","bestRating": "5","ratingValue": "4","worstRating": "1"}},{"@type": "Review","author": "Debarshi Dutta","datePublished": "2018-03-25","description": "Invoicing is so much easier and faster. We have been using the free plan and its been perfect for our business needs.","name": "Value purchase","reviewRating": {"@type": "Rating","bestRating": "5","ratingValue": "5","worstRating": "1"}}]', 1));
        JsonLd::addValue('aggregateRating', json_decode('{"@type":"AggregateRating","ratingValue":"4.7","reviewCount":"139"}', 1));
        JsonLd::setImage('https://www.swipez.in/images/product/billing-software/invoice-formats.webp');

        $data['CAPTCHA_CLIENT_ID'] = env('V3_CAPTCHA_CLIENT_ID');
        $data['GOOGLE_AUTH_CLIENT_ID'] = env('GOOGLE_AUTH_CLIENT_ID');
        $data['javascript'] = array('register');
        $data['service_id'] = '2';
        $data['current_date'] = date('d M Y');
        $data['jsonld'] = true;
        $data['format_name'] = 'cable';
        $data['title'] = 'Download invoice format for cable operator';
        $data['description'] = 'Create and download invoice format for cable operators right here in your browser. Add your customers contact information and company details. Create new rows or columns, attach your logo and choose invoice colors as per your brand.';

        $data['downloadTitle'] = 'Cable operator invoice format';
        $data['downloadText'] = 'Download cable operator invoice format. Edit the downloaded invoice format in excel.';
        $data['downloadButtonTitle'] = 'Download cable invoice format';
        $data['creatorTitle'] = 'Create cable operator invoice online';
        $data['downloadFileName'] = 'CableInvoiceTemplate.xlsx';

        return view('download-invoice-formats/cable-invoice-format', $data);
    }

    public function travelTicketInvoiceFormat()
    {

        SEOMeta::setTitle('Download Free Travel Invoice Templates For Ticket Booking | Simple Invoice Formats | Swipez');
        SEOMeta::setDescription('Download customized invoice templates for travel agencies. Easy to create and send beautifully crafted travel ticket invoices or bills in required formats.');
        SEOMeta::addKeyword(['travel invoice template', 'sample invoice travel', 'travel invoice template', 'travel invoice', 'travel invoice sample', 'travel invoice template excel', 'travel bill format', 'download travel bill format', 'tour bill format', 'ticket invoice format']);
        SEOMeta::addMeta('application-name', $value = 'Swipez Billing Software', $name = 'name');
        SEOMeta::addMeta('name', $value = 'Swipez Billing Software', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Download invoice formats for your business', $name = 'itemprop');
        OpenGraph::setTitle('Download Free Travel Invoice Templates For Ticket Booking | Simple Invoice Formats | Swipez');
        OpenGraph::setDescription('Download customized invoice templates for travel agencies. Easy to create and send beautifully crafted travel ticket invoices or bills in required formats.');
        OpenGraph::setUrl("https://www.swipez.in/" . request()->path());
        JsonLd::setTitle('Download travel ticket booking invoice formats');
        JsonLd::setType('Product');
        JsonLd::setDescription('Download professional invoice formats for travel ticket booking to send to your customers. Send these invoices to customer via email or print outs.');
        JsonLd::addValue('review', json_decode('[{"@type": "Review","author": "Shumu Gupta","datePublished": "2018-04-01","description": "Swipez invoicing is flexible and perfect for our business needs. We have been able to customize the invoices and create it to meet out business requirements.","name": "Value purchase","reviewRating": {"@type": "Rating","bestRating": "5","ratingValue": "4","worstRating": "1"}},{"@type": "Review","author": "Debarshi Dutta","datePublished": "2018-03-25","description": "Invoicing is so much easier and faster. We have been using the free plan and its been perfect for our business needs.","name": "Value purchase","reviewRating": {"@type": "Rating","bestRating": "5","ratingValue": "5","worstRating": "1"}}]', 1));
        JsonLd::addValue('aggregateRating', json_decode('{"@type":"AggregateRating","ratingValue":"4.7","reviewCount":"139"}', 1));
        JsonLd::setImage('https://www.swipez.in/images/product/billing-software/invoice-formats.webp');

        $data['CAPTCHA_CLIENT_ID'] = env('V3_CAPTCHA_CLIENT_ID');
        $data['GOOGLE_AUTH_CLIENT_ID'] = env('GOOGLE_AUTH_CLIENT_ID');
        $data['javascript'] = array('register');
        $data['service_id'] = '2';
        $data['current_date'] = date('d M Y');
        $data['jsonld'] = true;
        $data['format_name'] = 'ticket booking';
        $data['title'] = 'Download travel invoice format for ticket bookings';
        $data['description'] = 'Create and download invoice format for travel ticket bookings right here in your browser. Add your customers contact information and company details. Create new rows or columns, attach your logo and choose invoice colors as per your brand.';

        $data['downloadTitle'] = 'Travel invoice format';
        $data['downloadText'] = 'Download travel invoice format. Edit the downloaded invoice format in excel.';
        $data['downloadButtonTitle'] = 'Download travel invoice format';
        $data['creatorTitle'] = 'Create travel invoice online';
        $data['downloadFileName'] = 'TravelTicketInvoiceTemplate.xlsx';

        return view('download-invoice-formats/travel-ticket-invoice-format', $data);
    }

    public function travelCarInvoiceFormat()
    {

        SEOMeta::setTitle('Download Free Travel Invoice Templates For Car Booking Services | Simple Invoice Formats | Swipez');
        SEOMeta::setDescription('Download customized invoice templates for travel agency dealing in car bookings. Easy to create and send beautifully created invoices or bills in required formats.');
        SEOMeta::addKeyword(['download invoice format travel', 'car travel invoice template', 'travel car bill format', 'sample invoice car travel', 'download invoice format for car travel booking']);
        SEOMeta::addMeta('application-name', $value = 'Swipez Billing Software', $name = 'name');
        SEOMeta::addMeta('name', $value = 'Swipez Billing Software', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Download invoice formats for your business', $name = 'itemprop');
        OpenGraph::setTitle('Download Free Travel Invoice Templates For Car Booking Services | Simple Invoice Formats | Swipez');
        OpenGraph::setDescription('Download customized invoice templates for travel agency dealing in car bookings. Easy to create and send beautifully created invoices or bills in required formats.');
        OpenGraph::setUrl("https://www.swipez.in/" . request()->path());
        JsonLd::setTitle('Download car booking invoice formats for travel agency');
        JsonLd::setType('Product');
        JsonLd::setDescription('Download professional invoice formats for travel agency dealing in car bookings. Send invoices to customer via email or print outs.');
        JsonLd::addValue('review', json_decode('[{"@type": "Review","author": "Shumu Gupta","datePublished": "2018-04-01","description": "Swipez invoicing is flexible and perfect for our business needs. We have been able to customize the invoices and create it to meet out business requirements.","name": "Value purchase","reviewRating": {"@type": "Rating","bestRating": "5","ratingValue": "4","worstRating": "1"}},{"@type": "Review","author": "Debarshi Dutta","datePublished": "2018-03-25","description": "Invoicing is so much easier and faster. We have been using the free plan and its been perfect for our business needs.","name": "Value purchase","reviewRating": {"@type": "Rating","bestRating": "5","ratingValue": "5","worstRating": "1"}}]', 1));
        JsonLd::addValue('aggregateRating', json_decode('{"@type":"AggregateRating","ratingValue":"4.7","reviewCount":"139"}', 1));
        JsonLd::setImage('https://www.swipez.in/images/product/billing-software/invoice-formats.webp');

        $data['CAPTCHA_CLIENT_ID'] = env('V3_CAPTCHA_CLIENT_ID');
        $data['GOOGLE_AUTH_CLIENT_ID'] = env('GOOGLE_AUTH_CLIENT_ID');
        $data['javascript'] = array('register');
        $data['service_id'] = '2';
        $data['current_date'] = date('d M Y');
        $data['jsonld'] = true;
        $data['format_name'] = 'car booking';
        $data['title'] = 'Download travel invoice format for car bookings';
        $data['description'] = 'Create and download invoice format for travel car and vehicle bookings right here in your browser. Add your customers contact information and company details. Create new rows or columns, attach your logo and choose invoice colors as per your brand.';

        $data['downloadTitle'] = 'Car booking invoice format';
        $data['downloadText'] = 'Download car booking invoice format. Edit the downloaded invoice format in excel.';
        $data['downloadButtonTitle'] = 'Download car booking invoice format';
        $data['creatorTitle'] = 'Create car booking invoice online';
        $data['downloadFileName'] = 'TravelCarInvoiceTemplate.xlsx';

        return view('download-invoice-formats/travel-car-invoice-format', $data);
    }

    public function consultantInvoiceFormat()
    {

        SEOMeta::setTitle('Download Free Invoice Templates For Freelancers and Consultants | Simple Invoice Formats | Swipez');
        SEOMeta::setDescription('Download customized invoice templates for freelancers or consultants. Easy to create and send beautifully created invoices or bills in required formats.');
        SEOMeta::addKeyword(['consultant invoice template', 'sample invoice freelancers', 'freelancers invoice template', 'freelancers invoice', 'consultant invoice sample', 'consultant invoice template excel', 'freelances bill format', 'download freelancers bill format', 'download invoice format for consultants']);
        SEOMeta::addMeta('application-name', $value = 'Swipez Billing Software', $name = 'name');
        SEOMeta::addMeta('name', $value = 'Swipez Billing Software', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Download freelancer and consultancy invoice formats', $name = 'itemprop');
        OpenGraph::setTitle('Download Free Invoice Templates For Freelancers and Consultants | Simple Invoice Formats | Swipez');
        OpenGraph::setDescription('Download customized invoice templates for freelancers or consultants. Easy to create and send beautifully created invoices or bills in required formats.');
        OpenGraph::setUrl("https://www.swipez.in/" . request()->path());
        JsonLd::setTitle('Download freelancer and consultancy invoice formats');
        JsonLd::setType('Product');
        JsonLd::setDescription('Download professional invoice formats for freelancers and consultancy firms. Send these invoices to customer via email or print outs.');
        JsonLd::addValue('review', json_decode('[{"@type": "Review","author": "Shumu Gupta","datePublished": "2018-04-01","description": "Swipez invoicing is flexible and perfect for our business needs. We have been able to customize the invoices and create it to meet out business requirements.","name": "Value purchase","reviewRating": {"@type": "Rating","bestRating": "5","ratingValue": "4","worstRating": "1"}},{"@type": "Review","author": "Debarshi Dutta","datePublished": "2018-03-25","description": "Invoicing is so much easier and faster. We have been using the free plan and its been perfect for our business needs.","name": "Value purchase","reviewRating": {"@type": "Rating","bestRating": "5","ratingValue": "5","worstRating": "1"}}]', 1));
        JsonLd::addValue('aggregateRating', json_decode('{"@type":"AggregateRating","ratingValue":"4.7","reviewCount":"139"}', 1));
        JsonLd::setImage('https://www.swipez.in/images/product/billing-software/invoice-formats.webp');

        $data['CAPTCHA_CLIENT_ID'] = env('V3_CAPTCHA_CLIENT_ID');
        $data['GOOGLE_AUTH_CLIENT_ID'] = env('GOOGLE_AUTH_CLIENT_ID');
        $data['javascript'] = array('register');
        $data['service_id'] = '2';
        $data['current_date'] = date('d M Y');
        $data['jsonld'] = true;
        $data['format_name'] = 'consultant';
        $data['title'] = 'Download invoice format for consultants';
        $data['description'] = 'Create and download invoice format for freelancers right here in your browser. Add your customers contact information and company details. Create new rows or columns, attach your logo and choose invoice colors as per your brand.';

        $data['downloadTitle'] = 'Invoice format for consultants';
        $data['downloadText'] = 'Download consultant invoice format. Edit the downloaded invoice format in excel.';
        $data['downloadButtonTitle'] = 'Download consultant invoice format';
        $data['creatorTitle'] = 'Create consultancy invoice online';
        $data['downloadFileName'] = 'ConsultantInvoiceTemplate.xlsx';

        return view('download-invoice-formats/consultant-invoice-format', $data);
    }

    public function societyInvoiceFormat()
    {

        SEOMeta::setTitle('Download Free Invoice Templates For Housing Societies | Simple Invoice Formats | Swipez');
        SEOMeta::setDescription('Download customized invoice templates for housing society billing. Easy to create and send beautifully created invoices or bills in required formats.');
        SEOMeta::addKeyword(['housing society invoice template', 'sample invoice society', 'society invoice template', 'housing society invoice', 'society bill format', 'download society bill format', 'download invoice format for housing societies']);
        SEOMeta::addMeta('application-name', $value = 'Swipez Billing Software', $name = 'name');
        SEOMeta::addMeta('name', $value = 'Swipez Billing Software', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Download invoice formats for your business', $name = 'itemprop');
        OpenGraph::setTitle('Download Free Invoice Templates For Housing Societies | Simple Invoice Formats | Swipez');
        OpenGraph::setDescription('Download customized invoice templates for housing society billing. Easy to create and send beautifully created invoices or bills in required formats.');
        OpenGraph::setUrl("https://www.swipez.in/" . request()->path());
        JsonLd::setTitle('Download invoice formats for housing society');
        JsonLd::setType('Product');
        JsonLd::setDescription('Download professional invoice formats to send to your housing society members. Send these invoices to customer via email or print outs.');
        JsonLd::addValue('review', json_decode('[{"@type": "Review","author": "Shumu Gupta","datePublished": "2018-04-01","description": "Swipez invoicing is flexible and perfect for our business needs. We have been able to customize the invoices and create it to meet out business requirements.","name": "Value purchase","reviewRating": {"@type": "Rating","bestRating": "5","ratingValue": "4","worstRating": "1"}},{"@type": "Review","author": "Debarshi Dutta","datePublished": "2018-03-25","description": "Invoicing is so much easier and faster. We have been using the free plan and its been perfect for our business needs.","name": "Value purchase","reviewRating": {"@type": "Rating","bestRating": "5","ratingValue": "5","worstRating": "1"}}]', 1));
        JsonLd::addValue('aggregateRating', json_decode('{"@type":"AggregateRating","ratingValue":"4.7","reviewCount":"139"}', 1));
        JsonLd::setImage('https://www.swipez.in/images/product/billing-software/invoice-formats.webp');

        $data['CAPTCHA_CLIENT_ID'] = env('V3_CAPTCHA_CLIENT_ID');
        $data['GOOGLE_AUTH_CLIENT_ID'] = env('GOOGLE_AUTH_CLIENT_ID');
        $data['javascript'] = array('register');
        $data['service_id'] = '2';
        $data['current_date'] = date('d M Y');
        $data['jsonld'] = true;
        $data['format_name'] = 'society';
        $data['title'] = 'Download invoice format for housing society';
        $data['description'] = 'Create and download invoice format for housing societies right here in your browser. Add your customers contact information and company details. Create new rows or columns, attach your logo and choose invoice colors as per your brand.';

        $data['downloadTitle'] = 'Invoice format for housing society';
        $data['downloadText'] = 'Download housing society invoice format. Edit the downloaded invoice format in excel.';
        $data['downloadButtonTitle'] = 'Download housing society invoice format';
        $data['creatorTitle'] = 'Create housing society invoice online';
        $data['downloadFileName'] = 'HousingSocietyInvoiceTemplate.xlsx';

        return view('download-invoice-formats/society-invoice-format', $data);
    }

    public function FreeInvoiceFormat()
    {

        SEOMeta::setTitle('Free Invoice Generator | Free Online Invoice Generator Software');
        SEOMeta::setDescription('Free to use online invoice generator for your businesses. Add logo, brand colors and values as per your needs and get a PDF copy of your invoice.');
        SEOMeta::addKeyword(['free invoice generator', 'invoice generator', 'online invoice generator', 'generate invoices online']);
        SEOMeta::addMeta('application-name', $value = 'Swipez Billing Software', $name = 'name');
        SEOMeta::addMeta('name', $value = 'Swipez Billing Software', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Download invoice formats for your business', $name = 'itemprop');
        OpenGraph::setTitle('Generate Invoices Online for Free | Simple Invoice Generator | Swipez');
        OpenGraph::setDescription('Free to use online invoice generator for your businesses. Add logo, brand colors and values as per your needs and get a PDF copy of your invoice.');
        OpenGraph::setUrl("https://www.swipez.in/" . request()->path());
        JsonLd::setTitle('Generate Invoices Online for Free');
        JsonLd::setType('Product');
        JsonLd::setDescription('Generate professional invoice formats to send to your customers. Send these invoices as PDF to your customer via email or print outs.');
        JsonLd::addValue('review', json_decode('[{"@type": "Review","author": "Shumu Gupta","datePublished": "2018-04-01","description": "Swipez invoicing is flexible and perfect for our business needs. We have been able to customize the invoices and create it to meet out business requirements.","name": "Value purchase","reviewRating": {"@type": "Rating","bestRating": "5","ratingValue": "4","worstRating": "1"}},{"@type": "Review","author": "Debarshi Dutta","datePublished": "2018-03-25","description": "Invoicing is so much easier and faster. We have been using the free plan and its been perfect for our business needs.","name": "Value purchase","reviewRating": {"@type": "Rating","bestRating": "5","ratingValue": "5","worstRating": "1"}}]', 1));
        JsonLd::addValue('aggregateRating', json_decode('{"@type":"AggregateRating","ratingValue":"4.7","reviewCount":"139"}', 1));
        JsonLd::setImage('https://www.swipez.in/images/product/billing-software/invoice-formats.webp');

        $data['CAPTCHA_CLIENT_ID'] = env('V3_CAPTCHA_CLIENT_ID');
        $data['GOOGLE_AUTH_CLIENT_ID'] = env('GOOGLE_AUTH_CLIENT_ID');
        $data['javascript'] = array('register');
        $data['service_id'] = '2';
        $data['current_date'] = date('d M Y');
        $data['jsonld'] = true;
        $data['format_name'] = 'free';
        $data['title'] = 'Free invoice generator';
        $data['description'] = 'Generate invoices for free using easy to use tool. Create invoices online and share them with your clients in a PDF format. Generate GST invoices using free online invoice generator. Easy to use browser based free invoice generator tool. Free invoice builder that allows you to add your customers contact information and company details. Free invoice generator that enables you to create invoices with multiple rows or columns, attach your logo and choose invoice colors as per your brand.';

        $data['downloadTitle'] = 'GST Invoice format';
        $data['downloadText'] = 'Download GST invoice format. Edit the downloaded invoice format in excel.';
        $data['downloadButtonTitle'] = 'Download GST invoice format';
        $data['creatorTitle'] = 'Create GST invoice online';
        $data['downloadFileName'] = 'SalesInvoiceTemplate.xlsx';

        return view('download-invoice-formats/free-invoice-generator', $data);
    }

    public function GSTBillFormat()
    {

        SEOMeta::setTitle('Download GST bill format for free | Simple GST bill generator | Swipez');
        SEOMeta::setDescription('Free to download GST bill format. Create GST bill online and download PDF invoice. Add logo, brand colors and values as per your needs and get a PDF copy of your invoice.');
        SEOMeta::addKeyword(['free bill format', 'GST bill generator', 'online invoice generator', 'generate invoices online']);
        SEOMeta::addMeta('application-name', $value = 'Swipez Billing Software', $name = 'name');
        SEOMeta::addMeta('name', $value = 'Swipez Billing Software', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Download GST bill format online for free', $name = 'itemprop');
        OpenGraph::setTitle('Download GST bill format for free | Simple GST bill generator | Swipez');
        OpenGraph::setDescription('Free to download GST bill format. Create GST bill online and download PDF invoice. Add logo, brand colors and values as per your needs and get a PDF copy of your invoice.');
        OpenGraph::setUrl("https://www.swipez.in/" . request()->path());
        JsonLd::setTitle('Download GST bill format for free');
        JsonLd::setType('Product');
        JsonLd::setDescription('Free to download GST bill format. Create GST bill online and download PDF invoice. Add logo, brand colors and values as per your needs and get a PDF copy of your invoice.');
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
        $data['title'] = 'GST bill format';
        $data['description'] = 'Download GST bill format for free. Use the simple GST bill generator too to create invoices online and share them with your clients in a PDF format. Generate GST bills using free online invoice generator. Easy to use browser based free GST bill generator tool. Free bill creator that allows you to add your customers contact information and company details. GST bill generator that enables you to create invoice formats with multiple rows or columns, attach your logo and pick invoice colors as per your brand.';
        $data['downloadTitle'] = 'Download GST bill format';
        $data['downloadText'] = 'Download GST bill format. Edit the downloaded bill format and send to your customers.';
        $data['downloadButtonTitle'] = 'Download GST bill format';
        $data['creatorTitle'] = 'Create GST bill online';
        $data['downloadFileName'] = 'GSTBillFormat.xlsx';

        $data['downloadTitle'] = 'Download GST bill format';
        $data['downloadText'] = 'Download GST bill format. Edit the downloaded bill format in excel.';
        $data['downloadButtonTitle'] = 'Download GST bill format';
        $data['creatorTitle'] = 'Create GST bill online';
        $data['downloadFileName'] = 'GSTBillFormat.xlsx';

        return view('download-invoice-formats/gst-bill-format', $data);
    }

    public function ProformaFormat()
    {

        SEOMeta::setTitle('Download Free Proforma Format | Simple Invoice Formats | Swipez');
        SEOMeta::setDescription('Download customized Proforma Format and send to your customer. Easy to create and send beautifully created proforma invoices in required formats.');
        SEOMeta::addKeyword(['Proforma template', 'Proforma format', 'simple Proforma template', 'Proforma', 'estimate Proforma format', 'Proforma print out']);
        SEOMeta::addMeta('application-name', $value = 'Swipez Billing Software', $name = 'name');
        SEOMeta::addMeta('name', $value = 'Swipez Billing Software', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Download professional proforma format', $name = 'itemprop');
        OpenGraph::setTitle('Download Free Proforma Format | Simple Invoice Formats | Swipez');
        OpenGraph::setDescription('Download customized Proforma Format . Easy to create and send beautifully created invoices in required formats.');
        OpenGraph::setUrl("https://www.swipez.in/" . request()->path());
        JsonLd::setTitle('Download invoice formats for housing society');
        JsonLd::setType('Product');
        JsonLd::setDescription('Download customized Proforma Format and send to your customer. Easy to create and send beautifully created proforma invoices in required formats.');
        JsonLd::addValue('review', json_decode('[{"@type": "Review","author": "Shumu Gupta","datePublished": "2018-04-01","description": "Swipez invoicing is flexible and perfect for our business needs. We have been able to customize the invoices and create it to meet out business requirements.","name": "Value purchase","reviewRating": {"@type": "Rating","bestRating": "5","ratingValue": "4","worstRating": "1"}},{"@type": "Review","author": "Debarshi Dutta","datePublished": "2018-03-25","description": "Invoicing is so much easier and faster. We have been using the free plan and its been perfect for our business needs.","name": "Value purchase","reviewRating": {"@type": "Rating","bestRating": "5","ratingValue": "5","worstRating": "1"}}]', 1));
        JsonLd::addValue('aggregateRating', json_decode('{"@type":"AggregateRating","ratingValue":"4.7","reviewCount":"139"}', 1));
        JsonLd::setImage('https://www.swipez.in/images/product/billing-software/invoice-formats.webp');

        $data['CAPTCHA_CLIENT_ID'] = env('V3_CAPTCHA_CLIENT_ID');
        $data['GOOGLE_AUTH_CLIENT_ID'] = env('GOOGLE_AUTH_CLIENT_ID');
        $data['javascript'] = array('register');
        $data['service_id'] = '2';
        $data['current_date'] = date('d M Y');
        $data['jsonld'] = true;
        $data['format_name'] = 'proforma';
        $data['invoice_title'] = 'Proforma Invoice';
        $data['title'] = 'Proforma invoice format';
        $data['description'] = 'Download proforma invoice format for free. Use the simple proforma invoice generator to create proforma invoices online and share them with your clients in a PDF format. Generate proforma invoices using free online invoice generator. Easy to use browser based free proforma invoice generator tool. Free proforma invoice creator that allows you to add your customers contact information and company details. Create proforma invoices with your logo and pick invoice colors as per your brand and add multiple rows or columns.';

        $data['downloadTitle'] = 'Download proforma invoice format';
        $data['downloadText'] = 'Download proforma invoice format. Edit the downloaded proforma invoice format and send to your customers.';
        $data['downloadButtonTitle'] = 'Download proforma invoice format';
        $data['creatorTitle'] = 'Create proforma invoice format';
        $data['downloadFileName'] = 'ProformaInvoiceFormat.xlsx';

        return view('download-invoice-formats/proforma-invoice-format', $data);
    }

    public function EstimateFormat()
    {

        SEOMeta::setTitle('Download Estimate Format Document | Simple Invoice Formats | Swipez');
        SEOMeta::setDescription('Download Estimate Format Document to send to your to your customer. Easy to create and send beautifully created estimates in required formats.');
        SEOMeta::addKeyword(['estimate template', 'estimate format', 'simple estimate template', 'estimate', 'estimate template format', 'estimate print out']);
        SEOMeta::addMeta('application-name', $value = 'Swipez Billing Software', $name = 'name');
        SEOMeta::addMeta('name', $value = 'Swipez Billing Software', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Download invoice formats for your business', $name = 'itemprop');
        OpenGraph::setTitle('Download Free Invoice Templates For Housing Societies | Simple Invoice Formats | Swipez');
        OpenGraph::setDescription('Download Estimate Format Document. Easy to create and send beautifully created invoices in required formats.');
        OpenGraph::setUrl("https://www.swipez.in/" . request()->path());
        JsonLd::setTitle('Download invoice formats for housing society');
        JsonLd::setType('Product');
        JsonLd::setDescription('Download professional invoice estimtate to send to your to your customer. Send these estimates to customer via email or print outs.');
        JsonLd::addValue('review', json_decode('[{"@type": "Review","author": "Shumu Gupta","datePublished": "2018-04-01","description": "Swipez invoicing is flexible and perfect for our business needs. We have been able to customize the invoices and create it to meet out business requirements.","name": "Value purchase","reviewRating": {"@type": "Rating","bestRating": "5","ratingValue": "4","worstRating": "1"}},{"@type": "Review","author": "Debarshi Dutta","datePublished": "2018-03-25","description": "Invoicing is so much easier and faster. We have been using the free plan and its been perfect for our business needs.","name": "Value purchase","reviewRating": {"@type": "Rating","bestRating": "5","ratingValue": "5","worstRating": "1"}}]', 1));
        JsonLd::addValue('aggregateRating', json_decode('{"@type":"AggregateRating","ratingValue":"4.7","reviewCount":"139"}', 1));
        JsonLd::setImage('https://www.swipez.in/images/product/billing-software/invoice-formats.webp');

        $data['CAPTCHA_CLIENT_ID'] = env('V3_CAPTCHA_CLIENT_ID');
        $data['GOOGLE_AUTH_CLIENT_ID'] = env('GOOGLE_AUTH_CLIENT_ID');
        $data['javascript'] = array('register');
        $data['service_id'] = '2';
        $data['current_date'] = date('d M Y');
        $data['jsonld'] = true;
        $data['format_name'] = 'estimate';
        $data['invoice_title'] = 'Estimate';
        $data['title'] = 'Estimate format';
        $data['description'] = 'Download estimate format for free. Use the simple estimate generator to create estimate online and share them with your clients in a PDF format. Generate estimate using free online invoice generator. Easy to use browser based free estimate generator tool. Free estimate creator that allows you to add your customers contact information and company details. Create estimate with your logo and pick invoice colors as per your brand and add multiple rows or columns.';

        $data['downloadTitle'] = 'Download estimate format';
        $data['downloadText'] = 'Download estimate format. Edit the downloaded estimate format and send to your customers.';
        $data['downloadButtonTitle'] = 'Download estimate format';
        $data['creatorTitle'] = 'Create estimate format';
        $data['downloadFileName'] = 'EstimateFormat.xlsx';
        return view('download-invoice-formats/estimate-invoice-format', $data);
    }
}
