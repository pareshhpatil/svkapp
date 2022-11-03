@extends('home.master')
@section('title', 'Swipez Payouts allows a business disburse bulk payments instantly to any bank account, UPI ID, debit
cards and different digital wallets.')

@section('content')
<style>
    .plus-background {
     background-image: url() !important;
}
</style>
<section class="jumbotron jumbotron-features bg-transparent py-5" id="header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12 col-md-12 col-lg-6 col-xl-5 d-none d-lg-block">
                <h1>Bulk payout automation for vendors, franchise, employees, and more</h1>
                <p class="lead mb-2">Instant payouts for your vendors, franchise, employees & more in just a few clicks! Disburse bulk payouts directly into any bank account or UPI ID</p>
                @include('home.product.web_register',['d_type' => "web"])
            </div>
            <div class="col-12 col-md-8 m-auto ml-lg-auto mr-lg-0 col-lg-6 pt-5 pt-lg-0 d-none d-lg-block">
                <img alt="Payouts made simple for businesses" class="img-fluid"
                    src="{!! asset('images/product/payouts.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none text-center">
                <img alt="Payouts made simple for businesses" class="img-fluid"
                    src="{!! asset('images/product/payouts.svg') !!}" />
                <h1>Bulk payout automation for vendors, franchise, employees, and more.</h1>
                <p class="lead mb-2">Instant payouts for your vendors, franchise, employees & more in just a few clicks! Disburse bulk payouts directly into any bank account or UPI ID</p>
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
                <h3>Automate payout disbursal </h3>
            </div>
        </div>
    </div>
</section>
<section class="jumbotron jumbotron-features bg-transparent py-5" id="header" style="background-color: #fbfbfb !important;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12 col-md-12 col-lg-6 col-xl-5 d-none d-lg-block">
                <h1>Disburse payouts with ease in 3 steps</h1>
                <img alt="Payouts integration process" class="img-fluid" src="{!! asset('images/product/payouts/features/payout-integration-steps.svg') !!}" />
            </div>
            <div class="col-12 col-md-8 m-auto ml-lg-auto mr-lg-0 col-lg-6 pt-5 pt-lg-0 d-none d-lg-block">
               <div class="row">
                <div class="col-md-12 mb-2">
                    <div class="card shadow-sm p-3  bg-white rounded h-100">

                        <h3 class="text-secondary pb-2">  Transfer funds to Swipez nodal account</h3>
                        <p>Deposit funds into the nodal account created for you via a range of payment options like NEFT/IMPS, UPI, eWallets, and more.</p>
                    </div>
                </div>

                <div class="col-md-12  mb-2">
                    <div class="card shadow-sm p-3  bg-white rounded h-100">

                        <h3 class="text-secondary pb-2"> Add beneficiaries for payouts</h3>
                        <p>Add vendors, franchises, employees, and more as beneficiaries for payouts. Include payment details for beneficiaries like bank account number, IFSC, UPI ID and more. Bulk upload beneficiary data with a simple excel upload.</p>
                    </div>
                </div>
                <div class="col-md-12  mb-2">
                    <div class="card shadow-sm p-3  bg-white rounded h-100">

                        <h3 class="text-secondary pb-2">Initiate bulk payouts with a single click</h3>
                        <p>Transfer bulk payouts to your beneficiaries with a simple excel import. Or, a simple API integration.</p>
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

<section class="jumbotron bg-transparent py-5" id="features">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 text-center">
                <h2 class="display-4">Features</h2>

            </div>
        </div>
        <div class="row pt-4">
            <div class="col-sm-3 d-none d-sm-block">
                <ul class="nav nav-pills" id="featureTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active text-uppercase gray-400" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">Make payouts to a bank account, UPI, wallet or debit cards
</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link text-uppercase gray-400" id="pills-onlinepayment-tab" data-toggle="pill" href="#pills-onlinepayment" role="tab" aria-controls="pills-onlinepayment" aria-selected="false">Bulk payouts and management for vendors
</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link text-uppercase gray-400" id="pills-reminder-tab" data-toggle="pill" href="#pills-reminder" role="tab" aria-controls="pills-reminder" aria-selected="false">Split payments
</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link text-uppercase gray-400" id="pills-bulk-tab" data-toggle="pill" href="#pills-bulk" role="tab" aria-controls="pills-bulk" aria-selected="false">Bulk payouts and management for franchises
</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link text-uppercase gray-400" id="pills-inventory-tab" data-toggle="pill" href="#pills-inventory" role="tab" aria-controls="pills-inventory" aria-selected="false">Bulk payouts
</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link text-uppercase gray-400" id="pills-recurring-tab" data-toggle="pill" href="#pills-recurring" role="tab" aria-controls="pills-recurring" aria-selected="false">Multiple logins for payout beneficiaries
</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link text-uppercase gray-400" id="pills-international-tab" data-toggle="pill" href="#pills-international" role="tab" aria-controls="pills-international" aria-selected="false">Single view of all payouts
</a>
                    </li>
                    
                    <li class="nav-item" role="presentation">
                        <a class="nav-link text-uppercase gray-400" id="pills-payout-tab" data-toggle="pill" href="#pills-payout" role="tab" aria-controls="pills-payout" aria-selected="false">Automate payments for operational expenses
</a>
                    </li>
                </ul>
            </div>
            <div class="col-12 col-sm-9">
                <div class="tab-content">
                <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                        <div class="border-0">
                            <div class="card-title">
                                <h2 class="card-title gray-600">
                                    <center>Make payouts to a bank account, UPI, wallet or debit cards
</center>
                                </h2>
                            </div>
                            <img class="plus-background card-img-top p-2 mt-4" src="{!! asset('/images/product/payouts/features/payouts-features-multiple-payout-modes.svg') !!}" alt="Make payouts to a bank account, UPI, wallet or debit cards" width="640" height="360" loading="lazy" class="lazyload" />
                            <div class="card-body">
                                <p class="lead">
                                Send payouts to any mode preferred by your beneficiary. Verify the beneficiary bank accounts in real time before making payouts.


                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-onlinepayment" role="tabpanel" aria-labelledby="pills-onlinepayment-tab">
                        <div class="border-0">
                            <div class="card-title">
                                <h2 class="card-title gray-600">
                                    <center>Bulk payouts and management for vendors
                                    </center>
                                </h2>
                            </div>
                            <img class="plus-background card-img-top p-2 mt-4" src="{!! asset('/images/product/payouts/features/payout-features-bulk-vendor-payments.svg') !!}" alt="Bulk payouts and management for vendors" width="640" height="360" loading="lazy" class="lazyload" />
                            <div class="card-body">
                                <p class="lead">
                                Make bulk payouts to vendors and suppliers. Disburse payments for vendors directly to your vendor’s bank account or UPI ID and Manage vendor data centrally via an easy on-boarding process from a single dashboard.

                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-reminder" role="tabpanel" aria-labelledby="pills-reminder-tab">
                        <div class="border-0">
                            <div class="card-title">
                                <h2 class="card-title gray-600">
                                    <center>Split payments
</center>
                                </h2>
                            </div>
                            <img class="plus-background card-img-top p-2 mt-4" src="{!! asset('/images/product/payouts/features/payout-features-split-payments.svg') !!}" alt="Split payments" width="640" height="360" loading="lazy" class="lazyload" />
                            <div class="card-body">
                                <p class="lead">
                                    Automatically split incoming payments between one or many beneficiaries. Pre-define the split of payouts either in percentage or fixed values. Eliminate manual reconciliations and errors via automated transfers.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-bulk" role="tabpanel" aria-labelledby="pills-bulk-tab">
                        <div class="border-0">
                            <div class="card-title">
                                <h2 class="card-title gray-600">
                                    <center>Bulk payouts and management for franchises</center>
                                </h2>
                            </div>
                            <img class="plus-background card-img-top p-2 mt-4" src="{!! asset('/images/product/payouts/features/payout-features-franchise-payments.svg') !!}" alt="Bulk payouts and management for franchises" width="640" height="360" loading="lazy" class="lazyload" />
                            <div class="card-body">
                                <p class="lead">
                                Transfer payouts to your franchises automatically or via a maker/checker mode. Configure revenue split between your organization and your franchise once and let the payouts product do the rest.

                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-inventory" role="tabpanel" aria-labelledby="pills-inventory-tab">
                        <div class="border-0">
                            <div class="card-title">
                                <h2 class="card-title gray-600">
                                    <center>Bulk payouts
                                    </center>
                                </h2>
                            </div>
                            <img class="plus-background card-img-top p-2 mt-4" src="{!! asset('/images/product/payouts/features/payouts-feature-bulk-payouts.svg') !!}" alt="Bulk payouts" width="640" height="360" loading="lazy" class="lazyload" />
                            <div class="card-body">
                                <p class="lead">
                                    Easy to use Make payouts in bulk. Save time and effort invested in making payouts. Simply upload an excel sheet and the payouts are completed within minutes.

                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="pills-recurring" role="tabpanel" aria-labelledby="pills-recurring-tab">
                        <div class="border-0">
                            <div class="card-title">
                                <h2 class="card-title gray-600">
                                    <center>Multiple logins for payout beneficiaries
                                    </center>
                                </h2>
                            </div>
                            <img class="plus-background card-img-top p-2 mt-4" src="{!! asset('/images/product/payouts/features/payouts-feature-multiple-logins.svg') !!}" alt="Multiple logins for payouts" width="640" height="360" loading="lazy" class="lazyload" />
                            <div class="card-body">
                                <p class="lead">
                                    Easy to use Create distinctive roles for your team to give them access to review the status of their payment and transaction reference number. Eliminate tedious inquiries from your vendors, franchise, and more about when and whether they have received payment.


                                </p>
                            </div>
                        </div>
                    </div>


                    <div class="tab-pane fade" id="pills-international" role="tabpanel" aria-labelledby="pills-international-tab">
                        <div class="border-0">
                            <div class="card-title">
                                <h2 class="card-title gray-600">
                                    <center>Single view of all payouts
                                    </center>
                                </h2>
                            </div>
                            <img class="plus-background card-img-top p-2 mt-4" src="{!! asset('/images/product/payouts/features/payouts-feature-report-dashboard.svg') !!}" alt="Payout reports" width="640" height="360" loading="lazy" class="lazyload" />
                            <div class="card-body">
                                <p class="lead">
                                Get a consolidated view of all payouts made by you. Extensive reports linking your payouts to incoming invoices, vendors and much more.


                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-payout" role="tabpanel" aria-labelledby="pills-payout-tab">
                        <div class="border-0">
                            <div class="card-title">
                                <h2 class="card-title gray-600">
                                    <center>Automate payments for operational expenses
                                    </center>
                                </h2>
                            </div>
                            <img class="plus-background card-img-top p-2 mt-4" src="{!! asset('/images/product/payouts/features/payouts-feature-automate-expenses.svg') !!}" alt="Automate expenses" width="640" height="360" loading="lazy" class="lazyload" />
                            <div class="card-body">
                                <p class="lead">
                                Add multiple beneficiary accounts and schedule timely payments for payroll, rent, insurance, and other operational costs. Get a detailed overview of your disbursed and pending payouts. 


                                </p>
                            </div>
                        </div>
                    </div>


                    
                    






                   
                    
                </div>
                <div class="text-center d-sm-none">
                    <button id="prevtab" class="btn btn-primary" onclick="prevFeatureClick();">
                        < </button> <button id="nexttab" class="ml-5 btn btn-primary" onclick="nextFeatureClick();"> >
                            </button>
                </div>
            </div>
        </div>
    </div>
</section>








<section class="jumbotron bg-secondary py-5" id="cta">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-12 mb-5">
                <h3 class="text-white">Used and trusted by {{env('SWIPEZ_BIZ_NUM')}} businesses.</h3>
            </div>
            <div class="col-12 d-none d-sm-block">
                <a class="btn btn-lg text-white bg-primary" href="{{ config('app.APP_URL') }}merchant/register">Get your free account today!
</a>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-sm-none">
                <div class="row justify-content-center">
                    <a class="btn btn-lg bg-primary text-white" href="{{ config('app.APP_URL') }}merchant/register">Get your free account today!
</a>
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
                <h2 class="display-4 text-white">Hassle-free bulk payouts</h2>
                <h3 class=" text-white">Free payouts up to ₹ 5 Lakhs</h3>
            </div>
        </div><br />
        <div class="row row-eq-height text-center">
            <div class="col-md-3">
                <div class="bg-white p-4 h-100">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="money-bill-wave"
                        class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 640 512">
                        <path fill="currentColor"
                            d="M621.16 54.46C582.37 38.19 543.55 32 504.75 32c-123.17-.01-246.33 62.34-369.5 62.34-30.89 0-61.76-3.92-92.65-13.72-3.47-1.1-6.95-1.62-10.35-1.62C15.04 79 0 92.32 0 110.81v317.26c0 12.63 7.23 24.6 18.84 29.46C57.63 473.81 96.45 480 135.25 480c123.17 0 246.34-62.35 369.51-62.35 30.89 0 61.76 3.92 92.65 13.72 3.47 1.1 6.95 1.62 10.35 1.62 17.21 0 32.25-13.32 32.25-31.81V83.93c-.01-12.64-7.24-24.6-18.85-29.47zM48 132.22c20.12 5.04 41.12 7.57 62.72 8.93C104.84 170.54 79 192.69 48 192.69v-60.47zm0 285v-47.78c34.37 0 62.18 27.27 63.71 61.4-22.53-1.81-43.59-6.31-63.71-13.62zM320 352c-44.19 0-80-42.99-80-96 0-53.02 35.82-96 80-96s80 42.98 80 96c0 53.03-35.83 96-80 96zm272 27.78c-17.52-4.39-35.71-6.85-54.32-8.44 5.87-26.08 27.5-45.88 54.32-49.28v57.72zm0-236.11c-30.89-3.91-54.86-29.7-55.81-61.55 19.54 2.17 38.09 6.23 55.81 12.66v48.89z">
                        </path>
                    </svg>
                    <h3 class="text-secondary pb-2">Payouts made simpler</h3>
                    <p>Accurate and timely payouts processing. No manual intervention or errors. Fast and easy to
                        reconcile.</p>
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
                    <h3 class="text-secondary pb-2">Multiple payout options</h3>
                    <p>Make payouts to bank account or UPI handles. Verify beneficiary accounts before starting your
                        payouts process.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="bg-white p-4 h-100">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="file-excel"
                        class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 384 512">
                        <path fill="currentColor"
                            d="M224 136V0H24C10.7 0 0 10.7 0 24v464c0 13.3 10.7 24 24 24h336c13.3 0 24-10.7 24-24V160H248c-13.2 0-24-10.8-24-24zm60.1 106.5L224 336l60.1 93.5c5.1 8-.6 18.5-10.1 18.5h-34.9c-4.4 0-8.5-2.4-10.6-6.3C208.9 405.5 192 373 192 373c-6.4 14.8-10 20-36.6 68.8-2.1 3.9-6.1 6.3-10.5 6.3H110c-9.5 0-15.2-10.5-10.1-18.5l60.3-93.5-60.3-93.5c-5.2-8 .6-18.5 10.1-18.5h34.8c4.4 0 8.5 2.4 10.6 6.3 26.1 48.8 20 33.6 36.6 68.5 0 0 6.1-11.7 36.6-68.5 2.1-3.9 6.2-6.3 10.6-6.3H274c9.5-.1 15.2 10.4 10.1 18.4zM384 121.9v6.1H256V0h6.1c6.4 0 12.5 2.5 17 7l97.9 98c4.5 4.5 7 10.6 7 16.9z">
                        </path>
                    </svg>
                    <h3 class="text-secondary pb-2">Save time and resources</h3>
                    <p>Hassle-free bulk payouts to one or many recipients. Simple and secure process. Bulk payouts using
                        simple excel
                        uploads or APIs.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="bg-white p-4 h-100">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="user-plus"
                        class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 640 512">
                        <path fill="currentColor"
                            d="M624 208h-64v-64c0-8.8-7.2-16-16-16h-32c-8.8 0-16 7.2-16 16v64h-64c-8.8 0-16 7.2-16 16v32c0 8.8 7.2 16 16 16h64v64c0 8.8 7.2 16 16 16h32c8.8 0 16-7.2 16-16v-64h64c8.8 0 16-7.2 16-16v-32c0-8.8-7.2-16-16-16zm-400 48c70.7 0 128-57.3 128-128S294.7 0 224 0 96 57.3 96 128s57.3 128 128 128zm89.6 32h-16.7c-22.2 10.2-46.9 16-72.9 16s-50.6-5.8-72.9-16h-16.7C60.2 288 0 348.2 0 422.4V464c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48v-41.6c0-74.2-60.2-134.4-134.4-134.4z">
                        </path>
                    </svg>
                    <h3 class="text-secondary pb-2">Manage vendors and beneficiaries</h3>
                    <p>Organize you vendors and beneficiaries. Save all their bank account information
                        in one dashboard.</p>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="jumbotron bg-secondary py-5" id="cta">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-12 mb-5">
                <h3 class="text-white">Over {{env('SWIPEZ_BIZ_NUM')}} businesses use Swipez for their payouts</h3>
            </div>
            <div class="col-12 d-none d-sm-block">
                <a class="btn btn-lg text-white bg-primary" href="{{ config('app.APP_URL') }}merchant/register">Get a
                    free account</a>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-sm-none">
                <div class="row justify-content-center">
                    <a class="btn btn-lg bg-primary text-white" href="{{ config('app.APP_URL') }}merchant/register">Get
                        a free
                        account</a>
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
                <p class="lead">You are in good company. Here’s what happy businesses using Swipez payouts have to say</p>
            </div>
        </div>
        <div class="row align-items-center justify-content-center">
            <div class="col-lg-9 col-xl-6">
                <div class="card p-5">
                    <div class="row">
                        <div class="col-8 col-sm-6 col-md-4 col-xl-3 ml-auto mr-auto">
                            <img alt="Billing software clients picture" class="img-fluid rounded"
                                src="{!! asset('images/product/payouts/litcabs.png') !!}">
                        </div>
                        <div class="col-md-8 mt-4 mt-md-0">
                            <p>"Payouts has saved over 100 man hours for our team every month. A simple excel upload
                                takes care of payments to our vendor partners and suppliers. Reconciliation of payments
                                is present
                                in the same dashboard."</p>
                            <p class="h3 mt-4 mt-xl-5">
                                <strong>Siddhartha S</strong>
                            </p>
                            <p>
                                <em>Co-founder, Litcabs</em>
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
                                src="{!! asset('images/product/payouts/shah-infinite.jpg') !!}">
                        </div>
                        <div class="col-md-8 mt-4 mt-md-0">
                            <p>"Making timely salary payments was a time consuming activity for us. Now using payouts we
                                make our salary payments within minutes. The extensive reporting provide us a clear view
                                of all payouts."</p>
                            <p class="h3 mt-4 mt-xl-5">
                                <strong>Jayesh Shah</strong>
                            </p>
                            <p>
                                <em>Founder, Shah Infinite Solutions</em>
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
                <h3>Fast track your business with a one-stop payouts solution</h3>
            </div>
            <div class="col-12 d-none d-sm-block">
                <a class="btn btn-lg text-white bg-secondary" href="{{ config('app.APP_URL') }}merchant/register">Get a
                    free account</a>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-sm-none">
                <div class="row justify-content-center">
                    <a class="btn btn-lg bg-secondary text-white"
                        href="{{ config('app.APP_URL') }}merchant/register">Get a free
                        account</a>
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
                            Can I activate bulk payouts from my current Swipez account?
                        </div>
                        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne"
                            data-parent="#accordion" itemscope itemprop="acceptedAnswer"
                            itemtype="http://schema.org/Answer" itemscope itemprop="acceptedAnswer"
                            itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                            Certainly. You can activate payouts for your vendors, franchise, and more from your current Swipez account. Stay on top of all your business expenses & payouts from a centralized dashboard.

                            </div>
                        </div>
                    </div>



                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingTwo">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            Which modes of payment does Swipez payouts offer?
                        </div>
                        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion"
                            itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                            You can initiate payouts via transfers to bank accounts, UPI IDs, or eWallets for your vendors, franchise, employees, and more as per your requirements. Our seamless <a href="{{ route('home.integrations') }}" target="_blank">integrations</a> ensure that your beneficiaries will receive payments directly into their bank accounts, UPI IDs, and eWallets.

                            </div>
                        </div>
                    </div>


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingThree">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            What are the charges applicable on bulk payouts?
                        </div>
                        <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion"
                            itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                            A transaction fee is applicable on payouts as per the mode of payment and the payment gateway used. To know more about the different transaction charges, drop us a line or chat with us, and we’ll be happy to assist you.
                            </div>
                        </div>
                    </div>


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingFour">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                            Can I make payouts in bulk?
                        </div>
                        <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion"
                            itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, irrespective of the number of payouts you need to make. You can make your payouts
                                in bulk using excel upload or APIs.
                            </div>
                        </div>
                    </div>

                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingNinteen">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapseNinteen" aria-expanded="false" aria-controls="collapseNinteen">
                            Can I make payouts to vendors?
                        </div>
                        <div id="collapseNinteen" class="collapse" aria-labelledby="headingNinteen"
                            data-parent="#accordion" itemscope itemprop="acceptedAnswer"
                            itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, you can organize your vendor data and make payouts to your vendors via Swipez
                                billing software.
                            </div>
                        </div>
                    </div>

                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingTwenty">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapseTwenty" aria-expanded="false" aria-controls="collapseTwenty">
                            Can I make payouts to my franchises?
                        </div>
                        <div id="collapseTwenty" class="collapse" aria-labelledby="headingTwenty"
                            data-parent="#accordion" itemscope itemprop="acceptedAnswer"
                            itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, you can organize your franchise data and make payouts to your vendors via Swipez
                                billing software.
                            </div>
                        </div>
                    </div>

                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingFive">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                            How can I make payouts in bulk?
                        </div>
                        <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordion"
                            itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Bulk payouts can be made simply using excel uploads or APIs. Excel can be exported from
                                any existing systems you use like Tally or manually created as well. The APIs help to
                                integrate with any existing systems you might be already using.
                            </div>
                        </div>
                    </div>


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingSix">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                            Can I use Swipez’s payouts software on multiple devices and platforms?
                        </div>
                        <div id="collapseSix" class="collapse" aria-labelledby="headingSix" data-parent="#accordion"
                            itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, you can flawlessly use the payouts software on multiple devices and platforms such
                                as mobiles, tablets, desktops, laptops etc.
                            </div>
                        </div>
                    </div>


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingSeven">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
                            Do I need to download anything to start using Swipez payouts software?
                        </div>
                        <div id="collapseSeven" class="collapse" aria-labelledby="headingSeven" data-parent="#accordion"
                            itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                No, you don’t need to download any third party software to start using the Swipez
                                payouts software. Our seamless <a href="{{ route('home.integrations') }}" target="_blank">integrations</a> ensure that you can start using the Swipez payouts software effortlessly.
                            </div>
                        </div>
                    </div>


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingEight">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapseEight" aria-expanded="false" aria-controls="collapseEight">
                            Can multiple people from my team use the payouts application at one time?
                        </div>
                        <div id="collapseEight" class="collapse" aria-labelledby="headingEight" data-parent="#accordion"
                            itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, the Swipez payouts software can be used by you and your team members together.
                                Multiple team members can access the platform at one time. You can also assign roles and
                                give access as per your organization structure.
                            </div>
                        </div>
                    </div>


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingNine">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapseNine" aria-expanded="false" aria-controls="collapseNine">
                            Can I provide a login to vendors or suppliers to check the payments made from our account?
                        </div>
                        <div id="collapseNine" class="collapse" aria-labelledby="headingNine" data-parent="#accordion"
                            itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, you can provide a login to your vendors to check the payments they have received
                                from your end. Your vendors can also raise invoices from this account to you. This can
                                streamline all incoming invoices to your account making it easy for you to reconcile and
                                settle payments to your vendors.
                            </div>
                        </div>
                    </div>

                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingTwelve">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapseTwelve" aria-expanded="false" aria-controls="collapseTwelve">
                            Can I restrict access of data as per my organization structure?
                        </div>
                        <div id="collapseTwelve" class="collapse" aria-labelledby="headingTwelve"
                            data-parent="#accordion" itemscope itemprop="acceptedAnswer"
                            itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, you can create multiple roles and decide if you would like to give full control or
                                restrict access to certain features and data sets for your employees.
                            </div>
                        </div>
                    </div>


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingThirteen">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapseThirteen" aria-expanded="false" aria-controls="collapseThirteen">
                            Can I track offline payouts?
                        </div>
                        <div id="collapseThirteen" class="collapse" aria-labelledby="headingThirteen"
                            data-parent="#accordion" itemscope itemprop="acceptedAnswer"
                            itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, you can track payouts made via Cash, Cheque and NEFT in one console, giving you a
                                consolidated view of all payouts made by your company.
                            </div>
                        </div>
                    </div>


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingFourteen">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapseFourteen" aria-expanded="false" aria-controls="collapseFourteen">
                            Can I split payments to multiple parties?
                        </div>
                        <div id="collapseFourteen" class="collapse" aria-labelledby="headingFourteen"
                            data-parent="#accordion" itemscope itemprop="acceptedAnswer"
                            itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, you can enable the split payments plugin for your invoices to make direct payments to your vendors. The funds will be automatically transfered to your vendors.
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

    function showdescription() {
        document.getElementById('showdescription').style.display = "block";
        document.getElementById('readmore').style.display = "none";

    }
</script>

@endsection
