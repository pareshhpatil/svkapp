@extends('home.master')
@section('title', 'Send payment links, invoices or forms and collect payment faster from customers')

@section('content')
<section class="jumbotron jumbotron-features bg-transparent py-5 d-lg-none" id="header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12 col-md-12 col-lg-6 col-xl-5 d-none d-lg-block">
                <h1>​Secure and efficient GST filing software</h1>
                <p class="lead mb-2">Wish filing GST was easier? Swipez has made it simple and secure with its
                    cloud-based platform. It is designed to stay updated with the ever changing GST rules and laws. Our
                    GST filing software facilitates bulk uploading of GST invoices for multiple clients in a single
                    dashboard. In addition to all this, you also get easy to submit drafts of your businesses' GSTR3B
                    and R1 filing directly to the GST portal.</p>
                @include('home.product.web_register',['d_type' => "web"])
            </div>
            <div class="col-12 col-md-8 m-auto ml-lg-auto mr-lg-0 col-lg-6 pt-5 pt-lg-0 d-none d-lg-block">
                <img alt="Simplified GST filing software for businesses" class="img-fluid"
                    src="{!! asset('images/product/gst-filing.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none text-center">
                <img alt="Simplified GST filing software for businesses" class="img-fluid mb-5"
                    src="{!! asset('images/product/gst-filing.svg') !!}" />
                <h1>​Secure and efficient GST filing software</h1>
                <p class="lead mb-2">Wish filing GST was easier? Swipez has made it simple and secure with its
                    cloud-based platform. It is designed to stay updated with the ever changing GST rules and laws. Our
                    GST filing software facilitates bulk uploading of GST invoices for multiple clients in a single
                    dashboard. In addition to all this, you also get easy to submit drafts of your businesses' GSTR3B
                    and R1 filing directly to the GST portal.</p>
                @include('home.product.web_register',['d_type' => "mob"])

            </div>
            <!-- end -->
        </div>
    </div>
</section>

<section class="jumbotron jumbotron-features bg-transparent py-6 d-none d-lg-block" id="data-flow">
    <script src="/assets/global/plugins/jquery.min.js?id={{time()}}" type="text/javascript"></script>
    <script src="/js/data-flow/jquery.html-svg-connect.js?id={{time()}}"></script>

    <h1 class="pb-3 gray-700 text-center">GST filing & reconciliation made <span class="highlighter">easy</span></h1>
    <center>
        <p class="pb-3 lead gray-700 text-center" style="width: 620px;">Gather invoices from various sources and file
            your GST in minutes every
            month! Automate your GST reconciliations make sure you are getting GST input credit you deserve!</p>
    </center>
    @include('home.data_flow');
</section>

<section class="jumbotron py-5 bg-secondary text-white" id="cta">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-12">
                <h3>Fast, easy and accurate GST filing for businesses of all sizes</h3>
            </div>
        </div>
    </div>
</section>
<section class="jumbotron py-3" id="cta">
    <div class="container">
        <div class="row text-center">
            <div class="col-12 col-md-9 mx-auto my-3">
                <h2 class="pb-2 display-4">Perfect for CA & eCommerce sellers</h2>
                <p class="mb-0">Watch this 1 min video overview to learn more</p>
                <div style="cursor:pointer;">
                    <div id="video-promo-container" style="max-width:100%;position:relative;padding-top:62.25%">
                        <div id="video-play-button"
                            style="position:absolute; top:0px; left:0px; right:0px; bottom:0px; z-index:400">
                            <div style="position:absolute; top:0%; left:0%; width:100%">
                                <img class="shadow" title="Click to GST filing overview video"
                                    src="{!! asset('images/product/gst-filing/simple-gst-filing-tool.png') !!}"
                                    alt="GST filing software overview" class="img-fluid" width="825" height="464"
                                    loading="lazy" class="lazyload" />
                            </div>
                            <span style="position:absolute; margin-top:-40px; margin-left:-30px; top:42%; left:48%">
                                <svg style="color: #ff0000; text-shadow: 0px 0px 40px #18aebf;" aria-hidden="true"
                                    focusable="false" data-prefix="fab" data-icon="youtube" class="h-16" role="img"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                                    <path fill="currentColor"
                                        d="M549.655 124.083c-6.281-23.65-24.787-42.276-48.284-48.597C458.781 64 288 64 288 64S117.22 64 74.629 75.486c-23.497 6.322-42.003 24.947-48.284 48.597-11.412 42.867-11.412 132.305-11.412 132.305s0 89.438 11.412 132.305c6.281 23.65 24.787 41.5 48.284 47.821C117.22 448 288 448 288 448s170.78 0 213.371-11.486c23.497-6.321 42.003-24.171 48.284-47.821 11.412-42.867 11.412-132.305 11.412-132.305s0-89.438-11.412-132.305zm-317.51 213.508V175.185l142.739 81.205-142.739 81.201z">
                                    </path>
                                </svg>
                            </span>
                        </div>
                        <div id="video-text" class="w-100"
                            style="position:absolute; bottom:0px; left:0px; right:0px; z-index:400">
                            <p class="lead text-center font-weight-bold font-italic text-primary d-none">See Swipez
                                Billing in
                                action</p>
                        </div>
                        <div id="youtube-video" class="pb-3"></div>
                    </div>
                    <div class="col mx-auto">
                        <div class="card">
                            <div class="card-body p-5">
                                <div class="row">
                                    <div class="col-xl-7 mb-4 mb-sm-0 text-center">
                                        <h5>{{env('SWIPEZ_BIZ_NUM')}} businesses use Swipez Billing. Get your billing
                                            software account now!</h5>
                                    </div>
                                    <div class="col-xl-5 text-center">
                                        <a class="btn btn-primary btn-lg d-inline-block mr-3 mt-xl-0 mt-3"
                                            href="{{ config('app.APP_URL') }}merchant/register">Start using for free</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="jumbotron bg-transparent py-5" id="features">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 text-center">
                <h2 class="display-4">Features</h2>
            </div>
        </div>
        <div class="row text-left align-items-center pt-5 pb-md-5">
            <div class="col-4 col-md-5 d-none d-lg-block">
                <img alt="Collect payments per your customers preference" class="img-fluid"
                    src="{!! asset('images/product/gst-filing/features/fast-gst-filing.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Collect payments per your customers preference" class="img-fluid"
                    src="{!! asset('images/product/gst-filing/features/fast-gst-filing.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 m-md-auto d-none d-lg-block">
                <h2><strong>File GSTR 1 and 3B within minutes</strong></h2>
                <p class="lead">Upload your GSTR1 and 3B directly to the GST portal from your Swipez dashboard. Our GST
                    API integration has done all the hard work for you.</p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>File GSTR 1 and 3B within minutes</strong></h2>
                <p class="lead">Upload your GSTR1 and 3B directly to the GST portal from your Swipez dashboard. Our GST
                    API integration has done all the hard work for you.</p>
            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5 pb-md-5">
            <div class="col-4 col-md-5 m-md-auto order-md-5 d-none d-lg-block">
                <img alt="Reconcile payments across all modes of collections" class="img-fluid"
                    src="{!! asset('images/product/gst-filing/features/easy-to-use-interface.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Reconcile payments across all modes of collections" class="img-fluid"
                    src="{!! asset('images/product/gst-filing/features/easy-to-use-interface.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 d-none d-lg-block">
                <h2><strong>Simplified GST filing</strong></h2>
                <p class="lead">Our easy to use interface presents all your monthly invoices for your review and filing.
                    Just a few clicks of the button and you’re done.</p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>Simplified GST filing</strong></h2>
                <p class="lead">Our easy to use interface presents all your monthly invoices for your review and filing.
                    Just a few clicks of the button and you’re done.</p>
            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5">
            <div class="col-4 col-md-5 d-none d-lg-block">
                <img alt="Calculate GST dues" class="img-fluid"
                    src="{!! asset('images/product/gst-filing/features/auto-calculate-dues.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Calculate GST dues" class="img-fluid"
                    src="{!! asset('images/product/gst-filing/features/auto-calculate-dues.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 m-md-auto d-none d-lg-block">
                <h2><strong>Automatic calculation of dues</strong></h2>
                <p class="lead">Your monthly, quarterly or yearly filings are automatically presented for your review.
                    Modify your GSTR1 or 3B before submission or simply file it.</p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>Automatic calculation of dues</strong></h2>
                <p class="lead">Your monthly, quarterly or yearly filings are automatically presented for your review.
                    Modify your GSTR1 or 3B before submission or simply file it.</p>
            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5 pb-md-5">
            <div class="col-4 col-md-5 m-md-auto order-md-5 d-none d-lg-block">
                <img alt="Reconcile input credit" class="img-fluid"
                    src="{!! asset('images/product/gst-filing/features/reconcile-input-credit.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Reconcile input credit" class="img-fluid"
                    src="{!! asset('images/product/gst-filing/features/reconcile-input-credit.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 d-none d-lg-block">
                <h2><strong>Reconcile input credit</strong></h2>
                <p class="lead">Quick & easy <a href="{{ route('home.gstrecon') }}">GST reconciliation</a> to ensure
                    accurate input tax credit for your business. Identify and reconcile differences from a single
                    dashboard.</p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>Reconcile input credit</strong></h2>
                <p class="lead">Quick & easy <a href="{{ route('home.gstrecon') }}">GST reconciliation</a> to ensure
                    accurate input tax credit for your business. Identify and reconcile differences from a single
                    dashboard.</p>
            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5 pb-md-5">
            <div class="col-4 col-md-5 d-none d-lg-block">
                <img alt="Upload your invoices in the format of your choice" class="img-fluid"
                    src="{!! asset('images/product/gst-filing/features/upload-invoices-for-gst.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Upload your invoices in the format of your choice" class="img-fluid"
                    src="{!! asset('images/product/gst-filing/features/upload-invoices-for-gst.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 m-md-auto d-none d-lg-block">
                <h2><strong>Upload your invoices in the format of your choice</strong></h2>
                <p class="lead">Simply upload excel sheets to import your invoices for GST filing. Popular e-commerce
                    sales extracts like Amazon & Flipkart seller central already supported out of the box.</p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>Upload your invoices in the format of your choice</strong></h2>
                <p class="lead">Simply upload excel sheets to import your invoices for GST filing. Popular e-commerce
                    sales extracts like Amazon & Flipkart seller central already supported out of the box.</p>
            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5 pb-md-5">
            <div class="col-4 col-md-5 m-md-auto order-md-5 d-none d-lg-block">
                <img alt="Avoid GST penalties with timely filing" class="img-fluid"
                    src="{!! asset('images/product/gst-filing/features/stay-gst-compliant.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Avoid GST penalties with timely filing" class="img-fluid"
                    src="{!! asset('images/product/gst-filing/features/stay-gst-compliant.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 d-none d-lg-block">
                <h2><strong>Stay compliant and avoid penalties</strong></h2>
                <p class="lead">Get timely reminders to complete your monthly GST filings. Setup automatic GST
                    submissions for your invoices within Swipez.</p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>Stay compliant and avoid penalties</strong></h2>
                <p class="lead">Get timely reminders to complete your monthly GST filings. Setup automatic GST
                    submissions for your invoices within Swipez.</p>
            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5 pb-md-5">
            <div class="col-4 col-md-5 d-none d-lg-block">
                <img alt="Verify GST compliance of vendor" class="img-fluid"
                    src="{!! asset('images/product/gst-filing/features/verify-gst-compliance.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Verify GST compliance of vendor" class="img-fluid"
                    src="{!! asset('images/product/gst-filing/features/verify-gst-compliance.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 m-md-auto d-none d-lg-block">
                <h2><strong>Verify GST compliance of your vendors</strong></h2>
                <p class="lead">On boarding a new vendor or starting to work with a new business partner? Check their
                    GST compliance by simply entering their GST number called GSTIN. Avoid loss due to your vendors non
                    compliance.</p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>Verify GST compliance of your vendors</strong></h2>
                <p class="lead">On boarding a new vendor or starting to work with a new business partner? Check their
                    GST compliance by simply entering their GST number called GSTIN. Avoid loss due to your vendors non
                    compliance.</p>
            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5 pb-md-5">
            <div class="col-4 col-md-5 m-md-auto order-md-5 d-none d-lg-block">
                <img alt="Accept part payments against invoices" class="img-fluid"
                    src="{!! asset('images/product/gst-filing/features/multi-client-support.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Accept part payments against invoices" class="img-fluid"
                    src="{!! asset('images/product/gst-filing/features/multi-client-support.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 d-none d-lg-block">
                <h2><strong>Multi client support for CA firms and accountants</strong></h2>
                <p class="lead">Manage filing for multiple GSTN’s with one login. Automate and streamline your clients
                    filings. Serve more clients without hiring more resources.</p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>Multi client support for CA firms and accountants</strong></h2>
                <p class="lead">Manage filing for multiple GSTN’s with one login. Automate and streamline your clients
                    filings. Serve more clients without hiring more resources.</p>
            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5 pb-md-5">
            <div class="col-4 col-md-5 d-none d-lg-block">
                <img alt="Automated GST filing for ecommerce sellers" class="img-fluid"
                    src="{!! asset('images/product/gst-filing/features/automate-gst-filing-for-ecommerce.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Automated GST filing for ecommerce sellers" class="img-fluid"
                    src="{!! asset('images/product/gst-filing/features/automate-gst-filing-for-ecommerce.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 m-md-auto d-none d-lg-block">
                <h2><strong>GST filing for eCommerce sellers</strong></h2>
                <p class="lead">Import your monthly transaction records from your e-commerce seller central dashboard on
                    to Swipez. Automate the creation and calculation of refunds and adjustments in your e-commerce
                    reports to credit/debit notes sent directly to the GST portal. </p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>GST filing for eCommerce sellers</strong></h2>
                <p class="lead">Import your monthly transaction records from your e-commerce seller central dashboard on
                    to Swipez. Automate the creation and calculation of refunds and adjustments in your e-commerce
                    reports to credit/debit notes sent directly to the GST portal. </p>
            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5 pb-md-5">
            <div class="col-4 col-md-5 m-md-auto order-md-5 d-none d-lg-block">
                <img alt="Stock transfer between GST profiles" class="img-fluid"
                    src="{!! asset('images/product/gst-filing/features/stock-transfer.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Stock transfer between GST profiles" class="img-fluid"
                    src="{!! asset('images/product/gst-filing/features/stock-transfer.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 d-none d-lg-block">
                <h2><strong>Synchronize stock transfer between GST profiles</strong></h2>
                <p class="lead">Simplify <a href="{{ route('home.billing.feature.onlineinvoicing') }}">creating invoices</a> and <a href="{{ route('home.expenses') }}">expenses</a> for multiple GST numbers when moving stock
                    between your eCommerce platform and warehouses. Automate stock transfer invoicing and submission to
                    the GST portal.</p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>Synchronize stock transfer between GST profiles</strong></h2>
                <p class="lead">Simplify <a href="{{ route('home.billing.feature.onlineinvoicing') }}">creating invoices </a>and <a href="{{ route('home.expenses') }}">expenses</a> for multiple GST numbers when moving stock
                    between your eCommerce platform and warehouses. Automate stock transfer invoicing and submission to
                    the GST portal.</p>
            </div>
            <!-- end -->
        </div>
    </div>
</section>
<section class="jumbotron bg-secondary py-5" id="cta">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-12 mb-5">
                <h3 class="text-white">See why over {{env('SWIPEZ_BIZ_NUM')}} businesses trust Swipez.<br /><br />Try it
                    free. No credit
                    card required.</h3>
            </div>
            <div class="col-12 d-none d-sm-block">
                <a class="btn btn-lg text-white bg-primary" href="{{ config('app.APP_URL') }}merchant/register">Start
                    Now</a>
                <a class="btn btn-outline-primary btn-lg" href="{{ route('home.pricing.billing') }}">See pricing
                    plans</a>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-sm-none">
                <div class="row justify-content-center">
                    <a class="btn btn-lg text-white bg-primary mr-1"
                        href="{{ config('app.APP_URL') }}merchant/register">Start Now</a>
                    <a class="btn btn-outline-primary btn-lg" href="{{ route('home.pricing.billing') }}">Pricing
                        plans</a>
                </div>
            </div>
            <!-- end -->
        </div>
    </div>
</section>
<section id="steps" class="jumbotron bg-transparent py-5">
    <div class="container">
        <div class="row text-center justify-content-center pb-5">
            <div class="col-md-10 col-lg-10 col-xl-12">
                <h2 class="display-4">GST filing software for accountants</h2>
                <p class="lead">CA firms and accountants are able to service more clients by using Swipez</p>
            </div>
        </div>
        <div class="zig">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-7 col-xl-7">
                    <ul class="list-group">
                        <li class="list-group-item">Most businesses depend on CA firms professional advice for their tax
                            filing</li>
                        <li class="list-group-item">Monthly recurring filings can get time consuming and resource
                            intensive for your firm</li>
                        <li class="list-group-item">Automate your clients GST filing with simple excel formats</li>
                        <li class="list-group-item">Save time spent per filing. Serve more clients without hiring more
                            resources</li>
                        <li class="list-group-item">Manage multiple clients via one central dashboard</li>
                        <li class="list-group-item">Serving e-commerce clients? Use the already built plugins to import
                            sales data from their e-commerce sales dashboard</li>
                        <li class="list-group-item">Support for custom input formats provided as per requirement</li>
                    </ul>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-5 col-xl-5">
                    <img class="zig-image img-fluid mx-auto"
                        src="{!! asset('images/product/gst-filing/features/gst-filing-software-for-accountants.svg') !!}"
                        title="Cross Care Seeker" alt="Take Charge of the Care You Receive" />
                </div>
            </div>
        </div>
    </div>
</section>
<section class="jumbotron py-5 bg-primary" id="features">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 text-center">
                <h2 class="display-4 text-white">Easiest way to file GST every month</h2>
            </div>
        </div><br />
        <div class="row row-eq-height text-center pb-5">
            <div class="col-md-3">
                <div class="bg-white p-4 h-100">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check-square"
                        class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 448 512">
                        <path fill="currentColor"
                            d="M400 480H48c-26.51 0-48-21.49-48-48V80c0-26.51 21.49-48 48-48h352c26.51 0 48 21.49 48 48v352c0 26.51-21.49 48-48 48zm-204.686-98.059l184-184c6.248-6.248 6.248-16.379 0-22.627l-22.627-22.627c-6.248-6.248-16.379-6.249-22.628 0L184 302.745l-70.059-70.059c-6.248-6.248-16.379-6.248-22.628 0l-22.627 22.627c-6.248 6.248-6.248 16.379 0 22.627l104 104c6.249 6.25 16.379 6.25 22.628.001z">
                        </path>
                    </svg>
                    <h3 class="text-secondary pb-2">GST filing made simple</h3>
                    <p>Accurate GST filing. No manual intervention or errors. Easy to
                        reconcile all your invoices and expenses. Connected with your GST portal login.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="bg-white p-4 h-100">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="cloud"
                        class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 640 512">
                        <path fill="currentColor"
                            d="M537.6 226.6c4.1-10.7 6.4-22.4 6.4-34.6 0-53-43-96-96-96-19.7 0-38.1 6-53.3 16.2C367 64.2 315.3 32 256 32c-88.4 0-160 71.6-160 160 0 2.7.1 5.4.2 8.1C40.2 219.8 0 273.2 0 336c0 79.5 64.5 144 144 144h368c70.7 0 128-57.3 128-128 0-61.9-44-113.6-102.4-125.4z">
                        </path>
                    </svg>
                    <h3 class="text-secondary pb-2">Access your data anywhere</h3>
                    <p>Cloud-based software. Access all your data on-the-go anywhere. Works securely across any
                        operating
                        system or device all you need is a browser.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="bg-white p-4 h-100">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="list-ol"
                        class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 512 512">
                        <path fill="currentColor"
                            d="M61.77 401l17.5-20.15a19.92 19.92 0 0 0 5.07-14.19v-3.31C84.34 356 80.5 352 73 352H16a8 8 0 0 0-8 8v16a8 8 0 0 0 8 8h22.83a157.41 157.41 0 0 0-11 12.31l-5.61 7c-4 5.07-5.25 10.13-2.8 14.88l1.05 1.93c3 5.76 6.29 7.88 12.25 7.88h4.73c10.33 0 15.94 2.44 15.94 9.09 0 4.72-4.2 8.22-14.36 8.22a41.54 41.54 0 0 1-15.47-3.12c-6.49-3.88-11.74-3.5-15.6 3.12l-5.59 9.31c-3.72 6.13-3.19 11.72 2.63 15.94 7.71 4.69 20.38 9.44 37 9.44 34.16 0 48.5-22.75 48.5-44.12-.03-14.38-9.12-29.76-28.73-34.88zM496 224H176a16 16 0 0 0-16 16v32a16 16 0 0 0 16 16h320a16 16 0 0 0 16-16v-32a16 16 0 0 0-16-16zm0-160H176a16 16 0 0 0-16 16v32a16 16 0 0 0 16 16h320a16 16 0 0 0 16-16V80a16 16 0 0 0-16-16zm0 320H176a16 16 0 0 0-16 16v32a16 16 0 0 0 16 16h320a16 16 0 0 0 16-16v-32a16 16 0 0 0-16-16zM16 160h64a8 8 0 0 0 8-8v-16a8 8 0 0 0-8-8H64V40a8 8 0 0 0-8-8H32a8 8 0 0 0-7.14 4.42l-8 16A8 8 0 0 0 24 64h8v64H16a8 8 0 0 0-8 8v16a8 8 0 0 0 8 8zm-3.91 160H80a8 8 0 0 0 8-8v-16a8 8 0 0 0-8-8H41.32c3.29-10.29 48.34-18.68 48.34-56.44 0-29.06-25-39.56-44.47-39.56-21.36 0-33.8 10-40.46 18.75-4.37 5.59-3 10.84 2.8 15.37l8.58 6.88c5.61 4.56 11 2.47 16.12-2.44a13.44 13.44 0 0 1 9.46-3.84c3.33 0 9.28 1.56 9.28 8.75C51 248.19 0 257.31 0 304.59v4C0 316 5.08 320 12.09 320z">
                        </path>
                    </svg>
                    <h3 class="text-secondary pb-2">Manage multiple GST numbers</h3>
                    <p>Import data across multiple GSTNs. Manage one or many GST numbers. Handle GST multiple filings
                        from the same dashboard.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="bg-white p-4 h-100">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="file-invoice"
                        class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 384 512">
                        <path fill="currentColor"
                            d="M288 256H96v64h192v-64zm89-151L279.1 7c-4.5-4.5-10.6-7-17-7H256v128h128v-6.1c0-6.3-2.5-12.4-7-16.9zm-153 31V0H24C10.7 0 0 10.7 0 24v464c0 13.3 10.7 24 24 24h336c13.3 0 24-10.7 24-24V160H248c-13.2 0-24-10.8-24-24zM64 72c0-4.42 3.58-8 8-8h80c4.42 0 8 3.58 8 8v16c0 4.42-3.58 8-8 8H72c-4.42 0-8-3.58-8-8V72zm0 64c0-4.42 3.58-8 8-8h80c4.42 0 8 3.58 8 8v16c0 4.42-3.58 8-8 8H72c-4.42 0-8-3.58-8-8v-16zm256 304c0 4.42-3.58 8-8 8h-80c-4.42 0-8-3.58-8-8v-16c0-4.42 3.58-8 8-8h80c4.42 0 8 3.58 8 8v16zm0-200v96c0 8.84-7.16 16-16 16H80c-8.84 0-16-7.16-16-16v-96c0-8.84 7.16-16 16-16h224c8.84 0 16 7.16 16 16z">
                        </path>
                    </svg>
                    <h3 class="text-secondary pb-2">GST data in one place</h3>
                    <p>Sync your data to your booking tools like Tally. All GST filing needs under
                        one roof. Using a simple, easy to use and user friendly dashboard.</p>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="jumbotron bg-secondary py-5" id="cta">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-12 mb-5">
                <h3 class="text-white">Are you an accountancy or CA firm helping businesses with GST filing?<br /><br />
                </h3>
                <h4 class="text-white">Discounted pricing available to manage filings for multiple clients.</h4>
            </div>
            <div class="col-12 d-none d-sm-block">
                <a class="btn btn-lg text-white bg-primary" href="{{ config('app.APP_URL') }}merchant/register">Start
                    Now</a>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-sm-none">
                <div class="row justify-content-center">
                    <a class="btn btn-lg text-white bg-primary mr-1"
                        href="{{ config('app.APP_URL') }}merchant/register">Start Now</a>
                </div>
            </div>
            <!-- end -->
        </div>
    </div>
</section>
<section class="jumbotron bg-transparent py-5" id="testimonials">
    <div class="container">
        <div class="row text-center justify-content-center pb-5">
            <div class="col-md-10 col-lg-8 col-xl-7">
                <h2 class="display-4">Testimonials</h2>
                <p class="lead">You are in good company. Join {{env('SWIPEZ_BIZ_NUM')}} happy businesses who are already
                    using Swipez
                    collections software</p>
            </div>
        </div>
        <div class="row align-items-center justify-content-center">
            <div class="col-lg-9 col-xl-6">
                <div class="card p-5">
                    <div class="row">
                        <div class="col-8 col-sm-6 col-md-4 col-xl-3 ml-auto mr-auto p-0">
                            <img alt="Billing software clients picture" class="img-fluid rounded"
                                src="{!! asset('images/product/gst-filing/svklogo2.jpeg') !!}">
                        </div>
                        <div class="col-md-8 mt-4 mt-md-0">
                            <p>"Earlier GST filing was time consuming and expensive. I am now able to file my own
                                monthly GST R1 and 3B within minutes. I am able to stay GST
                                compliant and not worry about monthly GST penalties anymore."</p>
                            <p class="h3 mt-4 mt-xl-5">
                                <strong>Mahesh Patil</strong>
                            </p>
                            <p>
                                <em>Founder, Siddhivinayak Travels House</em>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-9 col-xl-6 mt-4 mt-xl-0">
                <div class="card p-5">
                    <div class="row">
                        <div class="col-8 col-sm-6 col-md-4 col-xl-3 ml-auto mr-auto">
                            <img alt="Billing software clients picture" class="img-fluid rounded"
                                src="{!! asset('images/product/gst-filing/chordia-sarda-associates.png') !!}">
                        </div>
                        <div class="col-md-8 mt-4 mt-md-0">
                            <p>"Using Swipez GST filing we are now able to service more clients with the same resources.
                                We are now able to automate large aspects of the filing process."</p>
                            <p class="h3 mt-4 mt-xl-5">
                                <strong>Amit Chordia</strong>
                            </p>
                            <p>
                                <em>Co-founder, Chordia Sarda & Associates</em>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="jumbotron text-white bg-primary py-5" id="cta">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-12 mb-5">
                <h3>Power your business with a comprehensive GST filing solution</h3>
            </div>
            <div class="col-12 d-none d-sm-block">
                <a class="btn btn-lg text-white bg-secondary" href="{{ config('app.APP_URL') }}merchant/register">Start
                    Now</a>
                <a class="btn btn-lg text-white bg-outline-secondary" href="{{ route('home.pricing.billing') }}">See
                    pricing
                    plans</a>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-sm-none">
                <div class="row justify-content-center">
                    <a class="btn btn-lg text-white bg-secondary mr-1"
                        href="{{ config('app.APP_URL') }}merchant/register">Start Now</a>
                    <a class="btn btn-lg text-white bg-outline-secondary"
                        href="{{ route('home.pricing.billing') }}">Pricing
                        plans</a>
                </div>
            </div>
            <!-- end -->
        </div>
    </div>
</section>

<section id="faq" class="jumbotron py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8">
                <div class="row text-center justify-content-center pb-3">
                    <div class="col-md-12 col-lg-12 col-xl-12">
                        <h2 class="display-4">FAQ'S</h2>
                        <p class="lead">Looking for more info? Here are some things we're commonly asked</p>
                    </div>
                </div>
                <!--Accordion wrapper-->
                <div id="accordion" itemscope itemtype="http://schema.org/FAQPage">
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingOne">

                        <div itemprop="name" class="btn btn-link color-black" data-toggle="collapse"
                            data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            Is my data posted (filed) to the GST portal?
                        </div>
                        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne"
                            data-parent="#accordion" itemscope itemprop="acceptedAnswer"
                            itemtype="http://schema.org/Answer" itemscope itemprop="acceptedAnswer"
                            itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, Swipez GST filing software submits your invoices to your profile on the GST portal.
                                This is done via APIs which are used to send your information to the GST portal for
                                filing.
                            </div>
                        </div>
                    </div>
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingTwo">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            How do I upload an invoice to GST by using Swipez dashboard?
                        </div>
                        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion"
                            itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Invoices created within Swipez can be submitted to the GST portal for filing via the GST
                                tab. The GST tab of Swipez has the facility to file your GST R1 and GST R3B with a few
                                clicks. Invoices which have been created externally can also be sent to GST portal
                                directly via the Swipez GST filing software by a simple excel import. 
                                Ensure hassle-free GST filing with Swipez's robust <a href="{{ route('home.integrations') }}" target="_blank">integrations</a> with GST APIs, Excel & more.
                            </div>
                        </div>
                    </div>
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingTwo">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            How do you add GST to an invoice?
                        </div>
                        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion"
                            itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                GST values can be added to your invoices by adding the applicable GST components like
                                CGST, SGST or IGST. Invoices created on Swipez are GST compliant by default and have a
                                section where GST related percentages can be added to your invoices.
                            </div>
                        </div>
                    </div>


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingThree">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            What is difference between GSTR1 and GSTR-3B?
                        </div>
                        <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion"
                            itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                GSTR 3B is a summary of all your invoices for a particular. GSTR 3B needs to be filed
                                every month by the 20th of the following month. For example - Invoices raised in Jan
                                2020 need to be summarized and filed on or before 20th Feb 2020. GSTR 3B contains input
                                tax credit claimed, GST liability to be paid, reversal charges if any, etc.
                                <br />
                                GSTR 1 is filed either monthly or quarterly. GSTR 1 contains invoice wise details of
                                every sale along with the tax liability. This helps your customer to take the eligible
                                input tax credit.
                            </div>
                        </div>
                    </div>


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingFour">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                            How do my invoices get prepared for GST R1 annd GSTR 3B filing?
                        </div>
                        <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion"
                            itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Invoices created in Swipez are automatically prepared for GST filing. You can also
                                import your invoices from external sources like E-Commerce seller portals, accounting
                                softwares or any ERP systems you might be using.
                            </div>
                        </div>
                    </div>

                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingFourone">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapseFourone" aria-expanded="false" aria-controls="collapseFourone">
                            Does Swipez offer e-invoicing solutions?
                        </div>
                        <div id="collapseFourone" class="collapse" aria-labelledby="headingFourone" data-parent="#accordion"
                            itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, Swipez offers an <a href="{{ route('home.einvoicing') }}">e-invoicing solution</a> that lets you upload your e-invoices directly to the Invoice Registration Portal (IRP). The invoices will be validated with a unique Invoice Reference Number (IRN), digital signature, and QR code.

                            </div>
                        </div>
                    </div>


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingFive">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                            Who’s there if I need any help related to usage of the GST filing product?
                        </div>
                        <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordion"
                            itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                We have a team of experts who are available for assistance through email, chat and our
                                call centre.
                            </div>
                        </div>
                    </div>


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingSix">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                            Can I check if my vendor / supplier is paying their GST so that I get my input credit on
                            time?
                        </div>
                        <div id="collapseSix" class="collapse" aria-labelledby="headingSix" data-parent="#accordion"
                            itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, you can avail Swipez's GST reconciliation software to ensure error-free input tax
                                credit for your business. All you need is your vendor's GST number and the date & time
                                limits for which you want to generate a <a href="{{ route('home.gstrecon') }}">GST
                                    reconciliation</a> report.
                            </div>
                        </div>
                    </div>


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingSeven">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
                            Do I need to download anything to start using Swipez GST filing software?
                        </div>
                        <div id="collapseSeven" class="collapse" aria-labelledby="headingSeven" data-parent="#accordion"
                            itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                No, you don’t need to download any third party software to start using the Swipez GST
                                filing software. Our seamless <a href="{{ route('home.integrations') }}" target="_blank">integrations</a> ensure that you can start using the Swipez GST filing software effortlessly.
                            </div>
                        </div>
                    </div>


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingEight">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapseEight" aria-expanded="false" aria-controls="collapseEight">
                            Can multiple people from my team use the GST filing software at one time?
                        </div>
                        <div id="collapseEight" class="collapse" aria-labelledby="headingEight" data-parent="#accordion"
                            itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, the Swipez GST filing software can be used by you and your team members together.
                                Multiple team members can access the platform at one time. You can also assign roles and
                                give access as per your organization structure.
                            </div>
                        </div>
                    </div>


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingNine">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapseNine" aria-expanded="false" aria-controls="collapseNine">
                            I am tax consultancy / accountancy firm can I use Swipez GST filing software?
                        </div>
                        <div id="collapseNine" class="collapse" aria-labelledby="headingNine" data-parent="#accordion"
                            itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, you can use Swipez GST filing software to manage and file GST for your clients.
                            </div>
                        </div>
                    </div>

                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingTwelve">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapseTwelve" aria-expanded="false" aria-controls="collapseTwelve">
                            I am tax consultancy / accountancy firm can I manage multiple client filings?
                        </div>
                        <div id="collapseTwelve" class="collapse" aria-labelledby="headingTwelve"
                            data-parent="#accordion" itemscope itemprop="acceptedAnswer"
                            itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, we provide a facility wherein you can manage and file multiple GST numbers with one
                                login. You could also provide limited access to team members via a maker checker flow.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Accordion wrapper -->
        </div>
    </div>
    </div>
</section>
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
</script>
<script>
    var intcounter=0;
    var istimer=false;
    var titles1 = ["GST filling"];

</script>

@endsection
