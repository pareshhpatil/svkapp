@extends('home.master')
@section('content')
<section class="jumbotron jumbotron-features bg-transparent py-5" id="header">
    <style>
        .plugin-p{
            margin-top:12px;padding:2px;font-size: 1.5rem;
        }
        .pstyle{
        text-align: start;
        margin-top: 2px;
        }
    </style>
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12 col-md-12 col-lg-6 col-xl-5 d-none d-lg-block">
                <h1>Swipez integrations for your business</h1>
                <p class="lead mb-2">Integrate industry-leading payment gateways, ecommerce platforms & more directly into your Swipez account</p>
                @include('home.product.web_register',['d_type' => "web"])
            </div>
            <div class="col-12 col-md-8 m-auto ml-lg-auto mr-lg-0 col-lg-6 pt-5 pt-lg-0 d-none d-lg-block">
                <img alt="Swipez Integrations" class="img-fluid" src="{!! asset('images/integrations/Swipez_Integrations.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none text-center">
                <img alt="Swipez Integrations" class="img-fluid mb-5" src="{!! asset('images/integrations/Swipez_Integrations.svg') !!}" />
                <h1>Swipez integrations for your business</h1>
                <p class="lead mb-2">Integrate industry-leading payment gateways, ecommerce platforms & more directly into your Swipez account</p>
                @include('home.product.web_register',['d_type' => "mob"])
            </div>
            <!-- end -->
        </div>
    </div>
</section>
<style>
    a {
        color: #394242;
    }
    a:hover {
       text-decoration: none;
       color: #394242;
    }
   .btns{
    text-transform: uppercase;
    font-size: 9px;
    padding-left: 5px;
    padding-right: 5px;
    padding-top: 2px;
    padding-bottom: 2px;
    border-radius: 8px;
    margin-bottom: 5px;
   }
.p_title{
    font-size: 14px; color: white !important;
}
.apps-left-box {
    padding-top: 1.5rem;
    padding-bottom: 1.5rem;
    display: flex;
    justify-content: center;
}
.imgs{
    /* width: 60px  !important; */
    height: 35px  !important;
    /* max-width: 30% !important; */
    display: block;
    max-width: 100%;
    height: auto;
}

@media only screen and (max-width: 600px) {
    .imgs{
        height: 30px  !important;
}
.text_center{
    text-align: center;
}
}
.text_center{
    text-align: auto;
}
.para {
    line-height: 1.2em;
    font-size:16px;
  /* overflow: hidden;
  display: -webkit-box;
  -webkit-box-orient: vertical;
  -webkit-line-clamp: 3; */
}
.apps-shadow {
    box-shadow: 2px 4px 4px rgb(0 0 0 / 25%);
    border-radius: 0.5rem !important;
    padding: 15px;
    background-color: #ffffff;
}
.no-margin {
    margin: 0;
}
</style>
<section class="jumbotron py-5 bg-primary" id="features">
    <div class="container shadow-lg p-5 bg-white rounded-1">
        <div class="row ">
            <div class="col-12 col-md-12 col-lg-6 col-xl-5 d-none d-lg-block">
                <img alt="Easy integrations with Swipez" class="img-fluid" src="{!! asset('images/integrations/Easy_integrations_with_Swipez.svg') !!}" />
            </div>
            <div class="col-12 col-md-8 m-auto ml-lg-auto mr-lg-0 col-lg-6 pt-5 pt-lg-0 d-none d-lg-block">
                <h2>Easy integrations with Swipez</h2>
                <p class="lead mb-2">Bring your tools and teams on a single platform. Get work done faster! </p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none text-center">
                <img alt="Easy integrations with Swipez" class="img-fluid mb-5" src="{!! asset('images/integrations/Easy_integrations_with_Swipez.svg') !!}" />
                <h1>Easy integrations with Swipez</h1>
                <p class="lead mb-2">Bring your tools and teams on a single platform. Get work done faster! </p>     
            </div>
            <!-- end -->
        </div>
    </div>
</section>

<section class="jumbotron py-5 bg-secondary text-white" id="cta">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-12">
                <h3>Accelerate your business growth with Swipez & our partners.</h3>
            </div>
        </div>
    </div>
</section>

<section class="jumbotron py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-6 mb-2 d-flex">
                <div class="apps-shadow">
                    <div class="row no-margin">
                        <div class="col-md-12">
                            <h2 class="mb-1">GST APIs</h2>
                            <p class="apps-help pull-left">GST APIs</p>
                        </div>
                    </div>
                    <div class="row no-margin">
                        <div class="col-md-3 apps-left-box">
                            <img class="imgs img-responsive" alt="Swipez Integrations GST APIs" src="{!! asset('images/data-flow/gst_apis.jpg?id=v1')!!}">
                            {{-- <img class="img-responsive" src="/images/benefits/razorpaypaymentgateway/razorpay.png"> --}}
                        </div>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-12 apps-description">
                                    File your GSTR1 and 3B returns directly to the GST portal with our GST API integration. Ensure error-free ITC returns with ease.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-2 d-flex">
                <div class="apps-shadow">
                    <div class="row no-margin">
                        <div class="col-md-12">
                            <h2 class="mb-1">GST e-invoicing</h2>
                            <p class="apps-help pull-left">GST Portal</p>
                        </div>
                    </div>
                    <div class="row no-margin">
                        <div class="col-md-3 apps-left-box">
                            <img class="imgs" alt="Swipez Integrations GST e-invoicing" src="{!! asset('images/data-flow/gst_portal.jpg?id=v1')!!}">
                            {{-- <img class="img-responsive" src="/images/benefits/razorpaypaymentgateway/razorpay.png"> --}}
                        </div>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-12 apps-description">
                                    Generate GST compliant e-invoices with unique Invoice Reference Number (IRN) and QR codes in just a few clicks!
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-2 d-flex">
                <div class="apps-shadow">
                    <div class="row no-margin">
                        <div class="col-md-12">
                            <h2 class="mb-1">Excel</h2>
                            <p class="apps-help pull-left">Accounting tool</p>
                        </div>
                    </div>
                    <div class="row no-margin">
                        <div class="col-md-3 apps-left-box">
                            <img class="imgs" alt="Swipez Integrations Excel" src="{!! asset('images/integrations/excel.png?id=v1')!!}">
                        </div>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-12 apps-description">
                                    Create invoices, estimates, subscriptions for recurring invoices, and more with simple excel imports. Upload customer, expense, and franchise/vendor data with excel uploads. Export data from your account back into excel.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-2 d-flex">
                <div class="apps-shadow">
                    <div class="row no-margin">
                        <div class="col-md-12">
                            <h2 class="mb-1">Tally</h2>
                            <p class="apps-help pull-left">Accounting tool</p>
                        </div>
                    </div>
                    <div class="row no-margin">
                        <div class="col-md-3 apps-left-box">
                            <img class="imgs" alt="Swipez Integrations Tally" src="{!! asset('images/data-flow/tally-logo.svg?id=v1')!!}">
                        </div>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-12 apps-description">
                                    Stay on top of all your accounting needs with easy-to-use tally integration. Manage your sales, debit notes, and credit notes vouchers from a single dashboard with effortless import/export.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-2 d-flex">
                <div class="apps-shadow">
                    <div class="row no-margin">
                        <div class="col-md-12">
                            <h2 class="mb-1">Intuit QuickBooks</h2>
                            <p class="apps-help pull-left">Accounting tool</p>
                        </div>
                    </div>
                    <div class="row no-margin">
                        <div class="col-md-3 apps-left-box">
                            <img class="imgs" alt="Swipez Integrations Intuit QuickBooks" src="{!! asset('images/data-flow/quickbooks-logo.png?id=v1')!!}">
                        </div>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-12 apps-description">
                                    Seamless integration with intuit quickbooks to ensure the best in cloud-based accounting. Manage all your reports, transactions, and more from a centralized dashboard.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-2 d-flex">
                <div class="apps-shadow">
                    <div class="row no-margin">
                        <div class="col-md-12">
                            <h2 class="mb-1">Busy</h2>
                            <p class="apps-help pull-left">Accounting tool</p>
                        </div>
                    </div>
                    <div class="row no-margin">
                        <div class="col-md-3 apps-left-box">
                            <img class="imgs" alt="Swipez Integrations Busy" src="{!! asset('images/data-flow/busy-logo.jpg?id=v1')!!}">
                        </div>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-12 apps-description">
                                    Ensure GST compliant invoicing, comprehensive inventory management, expense management and more with Busy.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-2 d-flex">
                <div class="apps-shadow">
                    <div class="row no-margin">
                        <div class="col-md-12">
                            <h2 class="mb-1">Atom</h2>
                            <p class="apps-help pull-left">Payment Gateway</p>
                        </div>
                    </div>
                    <div class="row no-margin">
                        <div class="col-md-3 apps-left-box">
                            <img class="imgs" alt="Swipez Integrations Atom" src="{!! asset('images/integrations/Atom-Payment-Gateway.png?id=v1')!!}">
                        </div>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-12 apps-description">
                                    Accept & manage payments across a range of online and offline platforms with Atom payment gateway integration.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-2 d-flex">
                <div class="apps-shadow">
                    <a href="/partner-benefits/cashfree" target="_blank">
                        <div class="row no-margin">
                            <div class="col-md-12">
                                <h2 class="mb-1">Cashfree</h2>
                                <p class="apps-help pull-left">Payment Gateway</p>
                            </div>
                        </div>
                        <div class="row no-margin">
                            <div class="col-md-3 apps-left-box">
                                <img class="imgs" alt="Swipez Integrations Cashfree" style="padding-bottom: 7px;" src="{!! asset('images/benefits/cashfreepaymentgateway/cashfree2.png?id=v1')!!}">
                            </div>
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-12 apps-description">
                                        From payment collection to on-demand payouts and everything in between, Cashfree has you covered.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
      
        <div class="row">
            <div class="col-md-6 mb-2 d-flex">
                <div class="apps-shadow">
                    <a href="/partner-benefits/razorpay" target="_blank">
                        <div class="row no-margin">
                            <div class="col-md-12">
                                <h2 class="mb-1">Razorpay</h2>
                                <p class="apps-help pull-left">Payment Gateway</p>
                            </div>
                        </div>
                        <div class="row no-margin">
                            <div class="col-md-3 apps-left-box">
                                <img class="imgs" alt="Swipez Integrations Razorpay" src="{!! asset('images/benefits/razorpaypaymentgateway/razorpay.png?id=v1')!!}">
                            </div>
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-12 apps-description">
                                        Avail over 100+ payment methods with Razorpay checkout and let us take care of the rest.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-md-6 mb-2 d-flex">
                <div class="apps-shadow">
                    <div class="row no-margin">
                        <div class="col-md-12">
                            <h2 class="mb-1">Paytm</h2>
                            <p class="apps-help pull-left">Payment Gateway</p>
                        </div>
                    </div>
                    <div class="row no-margin">
                        <div class="col-md-3 apps-left-box">
                            <img class="imgs" alt="Swipez Integrations Paytm" src="{!! asset('images/data-flow/paytm-logo.png?id=v1')!!}">
                        </div>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-12 apps-description">
                                    Offer your customers the flexibility and security of UPI payments. Collect prompt payments with Paytm integration for your invoices.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6 mb-2 d-flex">
                <div class="apps-shadow">
                    <div class="row no-margin">
                        <div class="col-md-12">
                            <h2 class="mb-1">Stripe</h2>
                            <p class="apps-help pull-left">International Payment Gateway</p>
                        </div>
                    </div>
                    <div class="row no-margin">
                        <div class="col-md-3 apps-left-box">
                            <img class="imgs" alt="Swipez Integrations Stripe" src="{!! asset('images/benefits/stripe.png?id=v1')!!}">
                        </div>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-12 apps-description">
                                    Power your payment collections with Stripe. Accept both domestic and international payments with a simple and streamlined payment gateway integration.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-2 d-flex">
                <div class="apps-shadow">
                    <div class="row no-margin">
                        <div class="col-md-12">
                            <h2 class="mb-1">Bharat BillPay</h2>
                            <p class="apps-help pull-left">Payment Gateway</p>
                        </div>
                    </div>
                    <div class="row no-margin">
                        <div class="col-md-3 apps-left-box">
                            <img class="imgs" alt="Swipez Integrations Bharat Billpay" src="{!! asset('images/integrations/bharat_pay.png?id=v1')!!}">
                        </div>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-12 apps-description">
                                    Simplify payroll, operational expenses, and utility bill payments for your business. Ensure prompt payments for all your business bills from a single dashboard. 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6 mb-2 d-flex">
                <div class="apps-shadow">
                    <div class="row no-margin">
                        <div class="col-md-12">
                            <h2 class="mb-1">YES Bank</h2>
                            <p class="apps-help pull-left">Payment Gateway, Bank</p>
                        </div>
                    </div>
                    <div class="row no-margin">
                        <div class="col-md-3 apps-left-box">
                            <img class="imgs" alt="Swipez Integrations YES Bank" src="{!! asset('images/integrations/yesbank_logo.png?id=v1')!!}">
                        </div>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-12 apps-description">
                                    Connect your YES bank account to your business operations. Automate reconciliation for your bank account, settlements for invoices, payouts to your beneficiaries against corporate expenses. 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-2 d-flex">
                <div class="apps-shadow">
                    <div class="row no-margin">
                        <div class="col-md-12">
                            <h2 class="mb-1">ICICI Bank</h2>
                            <p class="apps-help pull-left">Payment Gateway, Bank</p>
                        </div>
                    </div>
                    <div class="row no-margin">
                        <div class="col-md-3 apps-left-box">
                            <img class="imgs" alt="Swipez Integrations ICICI Bank" src="{!! asset('images/data-flow/icicibank-logo.png?id=v1')!!}">
                        </div>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-12 apps-description">
                                    Link your Swipez account to your ICICI Bank current account. Ensure effortless invoice settlements directly in your bank account, easy refunds, and seamless expense payouts.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-2 d-flex">
                <div class="apps-shadow">
                    <div class="row no-margin">
                        <div class="col-md-12">
                            <h2 class="mb-1">Axis Bank</h2>
                            <p class="apps-help pull-left">Payment Gateway, Bank</p>
                        </div>
                    </div>
                    <div class="row no-margin">
                        <div class="col-md-3 apps-left-box">
                            <img class="imgs" alt="Swipez Integrations Axis Bank" src="{!! asset('images/integrations/axisbank.png?id=v1')!!}">
                        </div>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-12 apps-description">
                                    Integrate your Axis Bank and Swipez accounts. Settle invoice payments directly in your bank account, stay on top of payouts and corporate expenses, and reconcile your bank statements effortlessly. 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-2 d-flex">
                <div class="apps-shadow">
                    <div class="row no-margin">
                        <div class="col-md-12">
                            <h2 class="mb-1">Payoneer</h2>
                            <p class="apps-help pull-left">International Payment Gateway</p>
                        </div>
                    </div>
                    <div class="row no-margin">
                        <div class="col-md-3 apps-left-box">
                            <img class="imgs" alt="Swipez Integrations Payoneer" src="{!! asset('images/integrations/payoneer.png?id=v1')!!}">
                        </div>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-12 apps-description">
                                    Receive payments from across the globe with Payoneer’s seamless international payment gateway integration. 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-2 d-flex">
                <div class="apps-shadow">
                    <div class="row no-margin">
                        <div class="col-md-12">
                            <h2 class="mb-1">WooCommerce</h2>
                            <p class="apps-help pull-left">E-commerce platform</p>
                        </div>
                    </div>
                    <div class="row no-margin">
                        <div class="col-md-3 apps-left-box">
                            <img class="imgs" alt="Swipez Integrations WooCommerce" src="{!! asset('images/data-flow/woocommerce-logo.png?id=v1')!!}">
                        </div>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-12 apps-description">
                                    Simplify invoicing and payment collections for your WooCommerce store. Create and send invoices, manage your inventory, enable online payment collections, and get real-time reports from a single dashboard. 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-2 d-flex">
                <div class="apps-shadow">
                    <div class="row no-margin">
                        <div class="col-md-12">
                            <h2 class="mb-1">Magento</h2>
                            <p class="apps-help pull-left">E-commerce platform</p>
                        </div>
                    </div>
                    <div class="row no-margin">
                        <div class="col-md-3 apps-left-box">
                            <img class="imgs" alt="Swipez Integrations Magento" src="{!! asset('images/integrations/mangneto.png?id=v1')!!}">
                        </div>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-12 apps-description">
                                    Enable online payment collections for your e-commerce store on Magento. Stay on top of your online payments from a central dashboard.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-2 d-flex">
                <div class="apps-shadow">
                    <div class="row no-margin">
                        <div class="col-md-12">
                            <h2 class="mb-1">Amazon.in</h2>
                            <p class="apps-help pull-left">E-commerce platform</p>
                        </div>
                    </div>
                    <div class="row no-margin">
                        <div class="col-md-3 apps-left-box">
                            <img class="imgs" alt="Swipez Integrations Amazon" src="{!! asset('images/data-flow/amazon-logo.png?id=v1')!!}">
                        </div>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-12 apps-description">
                                    Ensure error-free invoicing, payment collections, inventory management, and more for your Amazon store. Import your Amazon invoices for GST filing with simple excel uploads.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-2 d-flex">
                <div class="apps-shadow">
                    <div class="row no-margin">
                        <div class="col-md-12">
                            <h2 class="mb-1">Flipkart</h2>
                            <p class="apps-help pull-left">E-commerce platform</p>
                        </div>
                    </div>
                    <div class="row no-margin">
                        <div class="col-md-3 apps-left-box">
                            <img class="imgs" alt="Swipez Integrations FlipKart" src="{!! asset('images/data-flow/flipkart-logo.png?id=v1')!!}">
                        </div>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-12 apps-description">
                                    Manage your GST invoicing, inventory, and more for your e-commerce store on Flipkart. Simple excel imports can be used to upload your Flipkart invoices for GST filing. 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-2 d-flex">
                <div class="apps-shadow">
                    <div class="row no-margin">
                        <div class="col-md-12">
                            <h2 class="mb-1">Shopify</h2>
                            <p class="apps-help pull-left">E-commerce platform</p>
                        </div>
                    </div>
                    <div class="row no-margin">
                        <div class="col-md-3 apps-left-box">
                            <img class="imgs" alt="Swipez Integrations Shopify" src="{!! asset('images/data-flow/shopify-logo.png?id=v1')!!}">
                        </div>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-12 apps-description">
                                    Build and manage your e-commerce brand on Shopify with a simple integration. Create and send invoices, collect payments, and much more.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-2 d-flex">
                <div class="apps-shadow">
                    <div class="row no-margin">
                        <div class="col-md-12">
                            <h2 class="mb-1">ClickUp</h2>
                            <p class="apps-help pull-left">Project management platform</p>
                        </div>
                    </div>
                    <div class="row no-margin">
                        <div class="col-md-3 apps-left-box">
                            <img class="imgs" alt="Swipez Integrations Clickup" src="{!! asset('images/integrations/clickup.svg?id=v1')!!}">
                        </div>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-12 apps-description">
                                    Whether you are working from home or office, Clickup is an essential tool to stay connected and manage all your projects. Clickup helps plan, monitor, and stay on top of all your projects.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-2 d-flex">
                <div class="apps-shadow">
                    <div class="row no-margin">
                        <div class="col-md-12">
                            <h2 class="mb-1">Zapier</h2>
                            <p class="apps-help pull-left">E-commerce platform</p>
                        </div>
                    </div>
                    <div class="row no-margin">
                        <div class="col-md-3 apps-left-box">
                            <img class="imgs" alt="Swipez Integrations Zapier" src="{!! asset('images/integrations/zapier.png?id=v1')!!}">
                        </div>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-12 apps-description">
                                    Hassle-free payment collections and invoicing for your Zapier store. Create and send invoices, enable online payment collections, and get real-time reports from a single dashboard.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-2 d-flex">
                <div class="apps-shadow">
                    <div class="row no-margin">
                        <div class="col-md-12">
                            <h2 class="mb-1">SETU</h2>
                            <p class="apps-help pull-left">Payment gateway</p>
                        </div>
                    </div>
                    <div class="row no-margin">
                        <div class="col-md-3 apps-left-box">
                            <img class="imgs" alt="Swipez Integrations SETU" src="{!! asset('images/integrations/setu.png?id=v1')!!}">
                        </div>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-12 apps-description">
                                    Offer your customers multiple UPI app options with a simple SETU integration. Compatible with different operating systems and devices via QR codes & mobile UPI apps.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<section class="jumbotron py-5 bg-primary" id="cta">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-12 mb-5 ">
                <h3 class="text-white">Drop us a line and we’ll get in touch

                </h3>
            </div>
            <div class="col-md-12">
                <a  data-toggle="modal" class="btn btn-primary btn-lg text-white bg-tertiary" href="#chatnowModal" onclick="showPanel()">Chat now</a>
                <a class="btn btn-primary btn-lg text-white bg-secondary" href="/getintouch/billing-software-pricing">Send email</a>
            </div>
        </div>
    </div>
</section>


<script>
    // Instantiate the Bootstrap carousel
$('.multi-item-carousel').carousel({
  interval: 3000
});

// for every slide in carousel, copy the next slide's item in the slide.
// Do the same for the next, next item.
$('.multi-item-carousel .item').each(function(){
  var next = $(this).next();
  if (!next.length) {
    next = $(this).siblings(':first');
  }
  next.children(':first-child').clone().appendTo($(this));
  
  if (next.next().length>0) {
    next.next().children(':first-child').clone().appendTo($(this));
  } else {
  	$(this).siblings(':first').children(':first-child').clone().appendTo($(this));
  }
});
    </script>
<script>
    if (document.getElementById('video-promo-container')) {
        document.getElementById('video-promo-container').addEventListener("click", function() {
            //   document.getElementById('video-promo').classList.remove("d-none")
            document.getElementById('video-play-button').classList.add("d-none")
            document.getElementById('video-text').classList.add("d-none")
            document.getElementById('youtube-video').innerHTML = `<iframe id="video-promo" class="" width="480" height="270" src="https://www.youtube.com/embed/V17c56geXtg" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen="" style="position:absolute; top:0px; left:0px; width:100%; height:100%"></iframe>`
            $("#video-promo")[0].src += "?rel=0&autoplay=1";
        });
    }

    function showdescription() {
        document.getElementById('showdescription').style.display = "block";
        document.getElementById('readmore').style.display = "none";

    }
</script>

@endsection
