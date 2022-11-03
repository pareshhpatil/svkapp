@extends('home.master')
@section('title', 'Send payment links, invoices or forms and collect payment faster from customers')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<section class="jumbotron jumbotron-features bg-transparent py-5" id="header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12 col-md-12 col-lg-6 col-xl-5 d-none d-lg-block">
                <h1>e-invoicing software for GST filing by Swipez</h1>
                <p class="lead mb-2">e-invoicing solution for your B2B operations simplified in just a few clicks! Generate GST compliant e-invoices and file them directly on the Invoice Registration Portal (IRP) from a single dashboard.  </p>
                @include('home.product.web_register',['d_type' => "web"])
            </div>
            <div class="col-12 col-md-8 m-auto ml-lg-auto mr-lg-0 col-lg-6 pt-5 pt-lg-0 d-none d-lg-block">
                <img alt="Simplified GST einvoicing for businesses" class="img-fluid" src="{!! asset('images/product/einvoicing-software.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none text-center">
                <img alt="Simplified GST einvoicing for businesses" class="img-fluid mb-5" src="{!! asset('images/product/einvoicing-software.svg') !!}" />
                <h1>e-invoicing software for GST filing by Swipez</h1>
                <p class="lead mb-2">e-invoicing solution for your B2B operations simplified in just a few clicks! Generate GST compliant e-invoices and file them directly on the Invoice Registration Portal (IRP) from a single dashboard. </p>
                @include('home.product.web_register',['d_type' => "mob"])
            </div>
            <!-- end -->
        </div>
    </div>
</section>
<section class="jumbotron py-5 bg-secondary text-white" id="cta">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-12">
                <h3>Easy, error-free, e-invoicing software for your business</h3>
            </div>
        </div>
    </div>
</section>
<section class="jumbotron jumbotron-features bg-transparent py-5" id="header" style="background-color: #fbfbfb !important;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12 col-md-12 col-lg-6 col-xl-5 d-none d-lg-block">
                <h1>​Multi-mode e-invoicing solution</h1>
                <p class="lead mb-2">Different modes of integration to suit your needs </p>
                <img alt="E-invoicing supported via multiple modes" class="img-fluid" src="{!! asset('images/product/einvoicing/features/einvoicing-modes.svg') !!}" />
            </div>
            <div class="col-12 col-md-8 m-auto ml-lg-auto mr-lg-0 col-lg-6 pt-5 pt-lg-0 d-none d-lg-block">
               <div class="row">
                <div class="col-md-12 mb-2">
                    <div class="card shadow-sm p-3  bg-white rounded h-100">

                        <h3 class="text-secondary pb-2"> Upload directly</h3>
                        <p>Upload your e-invoices directly to the IRP from your Swipez dashboard. Our <a href="{{ route('home.integrations') }}" target="_blank">GST API integration</a> will take care of the rest for you. All invoices created and sent with Swipez will be validated with a unique Invoice Reference Number (IRN), digital signature, and QR code.</p>
                    </div>
                </div>

                <div class="col-md-12  mb-2">
                    <div class="card shadow-sm p-3  bg-white rounded h-100">

                        <h3 class="text-secondary pb-2"> Bulk upload</h3>
                        <p>Import invoices in bulk via excel sheet uploads for e-invoicing under GST. Swipez’s GST API integration will submit the invoices to the IRP for validation. Bulk upload your Amazon, Flipkart, and/or WooCommerce invoices to start e-invoicing in just a few clicks.</p>
                    </div>
                </div>
                <div class="col-md-12  mb-2">
                    <div class="card shadow-sm p-3  bg-white rounded h-100">

                        <h3 class="text-secondary pb-2">API integration</h3>
                        <p>If you want to integrate the API you have generated directly from the IRP with the Swipez dashboard, you can do that too. Sync your portal credentials with Swipez to create & send e-invoices as per your requirements.</p>
                    </div>
                </div>
            </div>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none text-center">
                 <h1>Multi-mode e-invoicing solution</h1>
                <p class="lead mb-2">Different modes of integration to suit your needs </p>
                <img alt="Simplified GST filing software for businesses" class="img-fluid mb-5" src="{!! asset('images/product/gst-filing.svg') !!}" />
                <div class="row">
                    <div class="col-md-12 mb-2">
                        <div class="card shadow-sm p-3  bg-white rounded h-100">

                            <h3 class="text-secondary pb-2"> Upload directly</h3>
                            <p>Upload your e-invoices directly to the IRP from your Swipez dashboard. Our GST API integration will take care of the rest for you. All invoices created and sent with Swipez will be validated with a unique Invoice Reference Number (IRN), digital signature, and QR code.</p>
                        </div>
                    </div>

                    <div class="col-md-12  mb-2">
                        <div class="card shadow-sm p-3  bg-white rounded h-100">

                            <h3 class="text-secondary pb-2"> Bulk upload</h3>
                            <p>Import invoices in bulk via excel sheet uploads for e-invoicing. Swipez’s GST API integration will submit the invoices to the IRP for validation. Bulk upload your Amazon, Flipkart, and/or WooCommerce invoices to start e-invoicing in just a few clicks.</p>
                        </div>
                    </div>
                    <div class="col-md-12  mb-2">
                        <div class="card shadow-sm p-3  bg-white rounded h-100">

                            <h3 class="text-secondary pb-2">API integration</h3>
                            <p>If you want to integrate the API you have generated directly from the IRP with the Swipez dashboard, you can do that too. Sync your portal credentials with Swipez to create & send e-invoices as per your requirements.</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end -->
        </div>
    </div>
</section>
<section class="jumbotron bg-secondary py-5" id="cta">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-12 mb-5">
                <h3 class="text-white">Streamline your e-invoicing under GST with ease! Sign up to know more. </h3>
            </div>
            <div class="col-12 d-none d-sm-block">
                <a class="btn btn-lg text-white bg-primary" href="{{ config('app.APP_URL') }}merchant/register">Get a
                    free account now</a>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-sm-none">
                <div class="row justify-content-center">
                    <a class="btn btn-lg text-white bg-primary mr-1" href="{{ config('app.APP_URL') }}merchant/register">Get a free account</a>
                </div>
            </div>
            <!-- end -->
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
                <img alt="Automated e-invoicing" class="img-fluid" src="{!! asset('images/product/einvoicing/features/automated-einvoicing.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Automated e-invoicing" class="img-fluid" src="{!! asset('images/product/einvoicing/features/automated-einvoicing.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 m-md-auto d-none d-lg-block">
                <h2><strong>E-invoicing automated</strong></h2>
                <p class="lead">Automate your GST invoicing & filling with an e-invoicing solution tailor-made to ensure 100% GST compliance. Generate accurate e-invoices in just a few clicks.  </p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>E-invoicing automated</strong></h2>
                <p class="lead">Automate your GST invoicing & filling with an e-invoicing solution tailor-made to ensure 100% GST compliance. Generate accurate e-invoices in just a few clicks.  </p> </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5 pb-md-5">
            <div class="col-4 col-md-5 m-md-auto order-md-5 d-none d-lg-block">
                <img alt="Plug n play e-invoicing" class="img-fluid" src="{!! asset('images/product/einvoicing/features/plug-n-play-einvoicing.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Plug n play e-invoicing" class="img-fluid" src="{!! asset('images/product/einvoicing/features/plug-n-play-einvoicing.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 d-none d-lg-block">
                <h2><strong>Plug n play e-invoicing</strong></h2>
                <p class="lead">Generate & upload e-invoices directly from your Swipez dashboard with the best in class API integration. Ensure error-free e-invoicing under GST with Swipez without writing any code.</p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>Plug n play e-invoicing</strong></h2>
                <p class="lead">Generate & upload e-invoices directly from your Swipez dashboard with the best in class API integration. Ensure error-free e-invoicing under GST with Swipez without writing any code.
                </p>
            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5">
            <div class="col-4 col-md-5 d-none d-lg-block">
                <img alt="Generate IRN for e-invoicing" class="img-fluid" src="{!! asset('images/product/einvoicing/features/smart-validation.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Generate IRN for e-invoicing" class="img-fluid" src="{!! asset('images/product/einvoicing/features/smart-validation.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 m-md-auto d-none d-lg-block">
                <h2><strong>Generate IRN and QR codes
                </strong></h2>
                <p class="lead">Generate e-invoices with a unique Invoice Reference Number (IRN), digital signature, and QR code in just a few clicks. Preview, print, and cancel IRP validated e-invoices with QR codes effortlessly. </p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>Generate IRN and QR codes</strong></h2>
                <p class="lead">Generate e-invoices with a unique Invoice Reference Number (IRN), digital signature, and QR code in just a few clicks. Preview, print, and cancel IRP validated e-invoices with QR codes effortlessly.</p>
            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5 pb-md-5">
            <div class="col-4 col-md-5 m-md-auto order-md-5 d-none d-lg-block">
                <img alt="Industry wise invoicing" class="img-fluid" src="{!! asset('images/product/einvoicing/features/industry-wise-invoicing.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Industry wise invoicing" class="img-fluid" src="{!! asset('images/product/einvoicing/features/industry-wise-invoicing.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 d-none d-lg-block">
                <h2><strong>Industry specific e-invoice templates
                </strong></h2>
                <p class="lead">Automated e-invoice templates for your billing and GST filing needs. Customized invoices for B2B, SEZs, WPAY, and more.</p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong> Industry specific e-invoice templates
                </strong></h2>
                <p class="lead">Automated e-invoice templates for your billing and GST filing needs. Customized invoices for B2B, SEZs, WPAY, and more. </p>
            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5 pb-md-5">
            <div class="col-4 col-md-5 d-none d-lg-block">
                <img alt="Bulk einvoicing" class="img-fluid" src="{!! asset('images/product/einvoicing/features/bulk-einvoicing.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Bulk einvoicing" class="img-fluid" src="{!! asset('images/product/einvoicing/features/bulk-einvoicing.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 m-md-auto d-none d-lg-block">
                <h2><strong>Bulk e-invoicing software</strong></h2>
                <p class="lead">Create e-invoices in bulk with simple excel uploads. Add Invoice Reference Number(s) (IRN) and QR codes to your bulk invoices with a few simple clicks.</p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>Bulk e-invoicing software</strong></h2>
                <p class="lead">Create e-invoices in bulk with simple excel uploads. Add Invoice Reference Number(s) (IRN) and QR codes to your bulk invoices with a few simple clicks.</p>
            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5 pb-md-5">
            <div class="col-4 col-md-5 m-md-auto order-md-5 d-none d-lg-block">
                <img alt="Recurring einvoicing" class="img-fluid" src="{!! asset('images/product/einvoicing/features/recurring-einvoicing.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Recurring einvoicing" class="img-fluid" src="{!! asset('images/product/einvoicing/features/recurring-einvoicing.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 d-none d-lg-block">
                <h2><strong>Recurring e-invoicing solution
                </strong></h2>
                <p class="lead">Automate recurring e-invoices for your B2B operations. Create e-invoices with unique IRNs & QR codes for your clients and upload them directly to the IRP. </p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>Recurring e-invoicing solution
                </strong></h2>
                <p class="lead">Automate recurring e-invoices for your B2B operations. Create e-invoices with unique IRNs & QR codes for your clients and upload them directly to the IRP. </p>
            </div>
            <!-- end -->
        </div>

        <div class="row text-left align-items-center pt-5 pb-md-5">
            <div class="col-4 col-md-5 d-none d-lg-block">
                <img alt="E-Invoicing PDF via email" class="img-fluid" src="{!! asset('images/product/einvoicing/features/einvoicing-email-pdf.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="E-Invoicing PDF via email" class="img-fluid" src="{!! asset('images/product/einvoicing/features/einvoicing-email-pdf.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 m-md-auto d-none d-lg-block">
                <h2><strong>E-Invoice PDF via email</strong></h2>
                <p class="lead">Send IRP validated e-invoices automatically to your customers via emails. Attach e-invoices with unique IRNs & QR codes for your customers to download, preview, and print as per their requirements.</p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>E-Invoice PDF via email</strong></h2>
                <p class="lead">Send IRP validated e-invoices automatically to your customers via emails. Attach e-invoices with unique IRNs & QR codes for your customers to download, preview, and print as per their requirements.</p>
            </div>
            <!-- end -->
        </div>

        <div class="row text-left align-items-center pt-5 pb-md-5">
            <div class="col-4 col-md-5 m-md-auto order-md-5 d-none d-lg-block">
                <img alt="Error free GST calculation" class="img-fluid" src="{!! asset('images/product/einvoicing/features/accurate-gst-data.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Error free GST calculation" class="img-fluid" src="{!! asset('images/product/einvoicing/features/accurate-gst-data.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 d-none d-lg-block">
                <h2><strong>Error-free GST calculations</strong></h2>
                <p class="lead">Ensure accurate GST additions on all your e-invoices with automated calculation of applicable GST rates. </p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>Error-free GST calculations</strong></h2>
                <p class="lead">Ensure accurate GST additions on all your e-invoices with automated calculation of applicable GST rates. </p>
            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5 pb-md-5">
            <div class="col-4 col-md-5 d-none d-lg-block">
                <img alt="Manage multiple GST profiles" class="img-fluid" src="{!! asset('images/product/einvoicing/features/multiple-gst-profiles.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Manage multiple GST profiles" class="img-fluid" src="{!! asset('images/product/einvoicing/features/multiple-gst-profiles.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 m-md-auto d-none d-lg-block">
                <h2><strong>Multiple GST profiles</strong></h2>
                <p class="lead">Create & manage multiple GST profiles for your e-invoices. Add multiple addresses, contact information, and GST numbers to generate e-invoices for different billing profiles from a single dashboard.</p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>Multiple GST profiles</strong></h2>
                <p class="lead">Create & manage multiple GST profiles for your e-invoices. Add multiple addresses, contact information, and GST numbers to generate e-invoices for different billing profiles from a single dashboard.</p>
            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5 pb-md-5">
            <div class="col-4 col-md-5 m-md-auto order-md-5 d-none d-lg-block">
                <img alt="Invoice tracking" class="img-fluid" src="{!! asset('images/product/einvoicing/features/invoice-tracking.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Invoice tracking" class="img-fluid" src="{!! asset('images/product/einvoicing/features/invoice-tracking.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 d-none d-lg-block">
                <h2><strong> Real-time invoice tracking</strong></h2>
                <p class="lead">Comprehensive reports on all your e-invoices with real-time updates on their status. Track & monitor vendor/supplier e-invoices in real-time.  </p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong> Real-time invoice tracking</strong></h2>
                <p class="lead">Comprehensive reports on all your e-invoices with real-time updates on their status. Track & monitor vendor/supplier e-invoices in real-time. </p>
            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5 pb-md-5">
            <div class="col-4 col-md-5 d-none d-lg-block">
                <img alt="Faster GST processing" class="img-fluid" src="{!! asset('images/product/einvoicing/features/fast-processing.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Faster GST processing" class="img-fluid" src="{!! asset('images/product/einvoicing/features/fast-processing.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 m-md-auto d-none d-lg-block">
                <h2><strong>Faster GST reconciliations</strong></h2>
                <p class="lead">Ensure faster GST reconciliation with a robust e-invoicing solution by eliminating mismatch errors at the invoice level. Accurate Input Tax Credit at your fingertips!
                </p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>Faster GST reconciliations</strong></h2>
                <p class="lead">Ensure faster GST reconciliation with a robust e-invoicing solution by eliminating mismatch errors at the invoice level. Accurate Input Tax Credit at your fingertips!
                </p>
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
                    for
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
<section class="jumbotron py-5 bg-primary" id="features">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 text-center">
                <h2 class="display-4 text-white">A one-stop solution for all your e-invoicing needs! </h2>
            </div>
        </div><br />
         <div class="row row-eq-height text-center pb-5">
            <div class="col-md-4">
                <div class="bg-white p-4 h-100">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check-square" class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                        <path fill="currentColor" d="M400 480H48c-26.51 0-48-21.49-48-48V80c0-26.51 21.49-48 48-48h352c26.51 0 48 21.49 48 48v352c0 26.51-21.49 48-48 48zm-204.686-98.059l184-184c6.248-6.248 6.248-16.379 0-22.627l-22.627-22.627c-6.248-6.248-16.379-6.249-22.628 0L184 302.745l-70.059-70.059c-6.248-6.248-16.379-6.248-22.628 0l-22.627 22.627c-6.248 6.248-6.248 16.379 0 22.627l104 104c6.249 6.25 16.379 6.25 22.628.001z"></path>
                    </svg>
                    <h3 class="text-secondary pb-2">Error-free e-invoicing under GST</h3>
                    <p>Automated & accurate GST calculation on every e-invoice. No manual intervention required to ensure error-free, 100% GST compliant e-invoices.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bg-white p-4 h-100">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="cloud" class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512">
                        <path fill="currentColor" d="M537.6 226.6c4.1-10.7 6.4-22.4 6.4-34.6 0-53-43-96-96-96-19.7 0-38.1 6-53.3 16.2C367 64.2 315.3 32 256 32c-88.4 0-160 71.6-160 160 0 2.7.1 5.4.2 8.1C40.2 219.8 0 273.2 0 336c0 79.5 64.5 144 144 144h368c70.7 0 128-57.3 128-128 0-61.9-44-113.6-102.4-125.4z"></path>
                    </svg>
                    <h3 class="text-secondary pb-2">Multiple GST profiles</h3>
                    <p>Generate e-invoices for multiple GST numbers from a single dashboard. Create & manage multiple GST billing profiles with ease..</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bg-white p-4 h-100">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="list-ol" class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path fill="currentColor" d="M61.77 401l17.5-20.15a19.92 19.92 0 0 0 5.07-14.19v-3.31C84.34 356 80.5 352 73 352H16a8 8 0 0 0-8 8v16a8 8 0 0 0 8 8h22.83a157.41 157.41 0 0 0-11 12.31l-5.61 7c-4 5.07-5.25 10.13-2.8 14.88l1.05 1.93c3 5.76 6.29 7.88 12.25 7.88h4.73c10.33 0 15.94 2.44 15.94 9.09 0 4.72-4.2 8.22-14.36 8.22a41.54 41.54 0 0 1-15.47-3.12c-6.49-3.88-11.74-3.5-15.6 3.12l-5.59 9.31c-3.72 6.13-3.19 11.72 2.63 15.94 7.71 4.69 20.38 9.44 37 9.44 34.16 0 48.5-22.75 48.5-44.12-.03-14.38-9.12-29.76-28.73-34.88zM496 224H176a16 16 0 0 0-16 16v32a16 16 0 0 0 16 16h320a16 16 0 0 0 16-16v-32a16 16 0 0 0-16-16zm0-160H176a16 16 0 0 0-16 16v32a16 16 0 0 0 16 16h320a16 16 0 0 0 16-16V80a16 16 0 0 0-16-16zm0 320H176a16 16 0 0 0-16 16v32a16 16 0 0 0 16 16h320a16 16 0 0 0 16-16v-32a16 16 0 0 0-16-16zM16 160h64a8 8 0 0 0 8-8v-16a8 8 0 0 0-8-8H64V40a8 8 0 0 0-8-8H32a8 8 0 0 0-7.14 4.42l-8 16A8 8 0 0 0 24 64h8v64H16a8 8 0 0 0-8 8v16a8 8 0 0 0 8 8zm-3.91 160H80a8 8 0 0 0 8-8v-16a8 8 0 0 0-8-8H41.32c3.29-10.29 48.34-18.68 48.34-56.44 0-29.06-25-39.56-44.47-39.56-21.36 0-33.8 10-40.46 18.75-4.37 5.59-3 10.84 2.8 15.37l8.58 6.88c5.61 4.56 11 2.47 16.12-2.44a13.44 13.44 0 0 1 9.46-3.84c3.33 0 9.28 1.56 9.28 8.75C51 248.19 0 257.31 0 304.59v4C0 316 5.08 320 12.09 320z"></path>
                    </svg>
                    <h3 class="text-secondary pb-2">Fast GST reconciliation</h3>
                    <p>Ensure 70% faster GST reconciliation for your e-invoices. Effortless & reliable GST reconciliation with a robust e-invoicing solution.</p>
                </div>
            </div>


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
                            <img alt="Billing software clients picture" class="img-fluid rounded" src="{!! asset('images/product/einvoicing/svklogo2.jpeg') !!}">
                        </div>
                        <div class="col-md-8 mt-4 mt-md-0">
                            <p>"Earlier GST filing was time consuming and expensive. I am now able to file my own monthly GST R1 and 3B within minutes. I am able to stay GST
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
                            <img alt="Billing software clients picture" class="img-fluid rounded" src="{!! asset('images/product/einvoicing/chordia-sarda-associates.png') !!}">
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
<section class="jumbotron bg-secondary py-5" id="cta">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-12 mb-5">
                <h2 class="display-4 text-white">Trusted by 2000+ tax experts. Register today to learn more!               </h2>
                {{-- <p class="text-white lead">Supercharge your WooCommerce business with a comprehensive payment collection solution</p> --}}
            </div>
            <div class="col-12 d-none d-sm-block">
                <a class="btn btn-lg text-white bg-primary" href="{{ config('app.APP_URL') }}merchant/register">Get a
                    free account</a>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-sm-none">
                <div class="row justify-content-center">
                    <a class="btn btn-lg text-white bg-primary mr-1"
                        href="{{ config('app.APP_URL') }}merchant/register">Get a free account</a>
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
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingOne">

                        <div itemprop="name" class="btn btn-link color-black" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            <h4>    What is an e-invoice?</h4>
                        </div>
                        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                The term "e-invoice" refers to an electronic invoice created & exchanged between businesses for the purchase & sale of goods/services. The Invoice Registration Portal (IRP) authenticates the invoices with a unique Invoice Reference Number (IRN), digital signature, and QR code.

                            </div>
                        </div>
                    </div>
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingTwo">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            <h4> Is there a prescribed template for e-invoices?</h4>
                        </div>
                        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                The e-invoice templates offered by the GST portal are readily available on Swipez. Moreover, you can customize the prescribed e-invoice templates to suit your business needs. You can add your brand logo, customize & personalize the template to reflect your brand.
                            </div>
                        </div>
                    </div>

                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingThree">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            <h4>Is my business eligible for e-invoicing under GST?</h4>
                        </div>
                        <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                In accordance with the official notification published by the Central Board of Indirect Taxes & Custom on 24th February 2022, GST-registered businesses/person(s) with an annual turnover of ₹ 50 Crores are eligible for e-invoicing from 1st April 2022.<br/>
                                Moreover, according to the notification published on 1st August 2022, B2B transactions with annual turnover of ₹ 10 Crores and up to ₹ 20 Crores are also eligible for e-invoicing under GST regulations from 1st October, 2022.
                                <br/>
                                However, Special Economic Zones (SEZ) units, insurance, banking, financial institutions, Non-Banking Financial Company (NBFC), Goods Transport Agency (GTA), passenger transportation services, and sales of cinema tickets are exempted from the new amendment to the e-invoicing under GST regulations.<br/>
                                To know more visit the Central Board of Indirect Taxes & Custom <a href="https://www.cbic.gov.in/index">website</a>.
                            </div>
                        </div>
                    </div>


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingFour">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                            <h4>Is it possible to partially cancel an e-invoice?</h4>
                        </div>
                        <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                No, an e-invoice can’t be partially canceled, it must be canceled as a whole. Moreover, the cancellation must be reported on the IRN within 24 hours. </div>
                        </div>
                    </div>


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingFive">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                            <h4>How can I avail Swipez’s e-invoicing solution?</h4>
                        </div>
                        <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                <a href="https://www.swipez.in/merchant/register" >Sign up</a> today to know more about everything you’ll need for your GST compliant e-invoicing with Swipez.


                            </div>
                        </div>
                    </div>


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingSix">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                            <h4>  Is my data safe with Swipez’s e-invoicing solution?</h4>
                        </div>
                        <div id="collapseSix" class="collapse" aria-labelledby="headingSix" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                We at Swipez value our customer’s privacy above all else. Merchants, e-commerce sellers, businesses rely on us for their invoicing & payment collections. And, we take that responsibility and our duty of care towards them very seriously. The security of our software, systems, and customer data are our number one priority.<br/>
                                Every piece of information that is transmitted between your browser and Swipez is protected with 256-bit SSL encryption. This ensures that your data is secure in transit. All data you have entered into Swipez sits securely behind web firewalls. This system is used by some of the biggest companies in the world and is widely acknowledged as safe and secure.

                            </div>
                        </div>
                    </div>
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingSeven">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
                            <h4> How do I add multiple GSTNs for my business?</h4>

                        </div>
                        <div id="collapseSeven" class="collapse" aria-labelledby="headingSeven" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Here’s an <a href="https://helpdesk.swipez.in/help/how-to-manage-multiple-billing-profiles-and-gst-numbers-05ea9899">article</a> to walk you through the creation of multiple GST numbers on Swipez. You can create & manage multiple GST profiles with different addresses, contact information & more to start generating e-invoices for various GST numbers from a single dashboard.

                            </div>
                        </div>
                    </div>
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingSeven">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseEight" aria-expanded="false" aria-controls="collapseEight">
                            <h4>  Who can I reach out to for help related to e-invoicing software?</h4>

                        </div>
                        <div id="collapseEight" class="collapse" aria-labelledby="headingEight" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                We have a dedicated team of experts who will be happy to assist you. You can reach out to us via email, chat, and our call center.
                            </div>
                        </div>
                    </div>
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingSeven">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseNine" aria-expanded="false" aria-controls="collapseNine">
                            <h4> Do I need to download anything to start using Swipez’s e-invoicing solution?</h4>

                        </div>
                        <div id="collapseNine" class="collapse" aria-labelledby="headingNine" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                No, you don’t need to download any third-party software to start using the Swipez e-invoicing software. Our seamless <a href="{{ route('home.integrations') }}" target="_blank">integrations</a> ensure that you can start using the Swipez e-invoicing software effortlessly.
                            </div>
                        </div>
                    </div>
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingSeven">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseTen" aria-expanded="false" aria-controls="collapseTen">
                            <h4> How do I update my inventory on Swipez?</h4>


                        </div>
                        <div id="collapseTen" class="collapse" aria-labelledby="headingTen" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Good news you don’t have to. We’ll take care of it for you. Once you generate an e-invoice, your inventory will be automatically updated in real-time.
                            </div>
                        </div>
                    </div>
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingSeven">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseEleven" aria-expanded="false" aria-controls="collapseEleven">
                            <h4>  I work for a tax consultancy/accountancy firm. Can I use Swipez’s e-invoicing software?</h4>

                        </div>
                        <div id="collapseEleven" class="collapse" aria-labelledby="headingEleven" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, you can use Swipez e-invoicing software to manage your clients and ensure error-free Input Tax Credit for their business.

                            </div>
                        </div>
                    </div>
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingSeven">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseTwelve" aria-expanded="false" aria-controls="collapseTwelve">
                            <h4>  I work for a tax consultancy/accountancy firm. Can I manage multiple client filings?</h4>

                        </div>
                        <div id="collapseTwelve" class="collapse" aria-labelledby="headingTwelve" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, certainly. With a simple excel upload, you can import your client’s e-invoicing data in bulk. Create IRNs and QR codes in bulk for the different e-invoices with ease. You can also create multiple GST profiles for your client’s business to generate e-invoices for different GST numbers from a single dashboard. With features like there, Swipez’s e-invoicing solution is just what your firm needs.

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
<section class="jumbotron py-5 bg-primary" id="cta">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-12 mb-5">
                <h3 class="text-white">Did we miss your question?
                    <br /><br />Drop us a line and we’ll get in touch.

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
