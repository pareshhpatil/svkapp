@extends('home.master')
@section('title', 'Online form builder to collect data from any user. Form builder with online payments. No coding
required')

@section('content')
<section class="jumbotron jumbotron-features bg-transparent py-5" id="header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12 col-md-12 col-lg-6 col-xl-5 d-none d-lg-block">
                <h1>Online form builder</h1>
                <p class="lead mb-2">Create forms that can collect data, accept payments, allow file uploads and more!
                    <br class="pb-2">Simple and easy to use online form builder, perfect data collection tool for your
                    business!
                </p>
                @include('home.product.web_register',['d_type' => "web"])
            </div>
            <div class="col-12 col-md-8 m-auto ml-lg-auto mr-lg-0 col-lg-6 pt-5 pt-lg-0 d-none d-lg-block">
                <img alt="Easy to use online form builder" class="img-fluid"
                    src="{!! asset('images/product/online-form-builder.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none text-center">
                <img alt="Easy to use online form builder" class="img-fluid"
                    src="{!! asset('images/product/online-form-builder.svg') !!}" />
                <h1>Online form builder</h1>
                <p class="lead mb-2">Create forms that can collect data, accept payments, allow file uploads and more!
                    Simple and easy to use online form builder, perfect data collection tool for your business!
                </p>
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
                <h2>Online form builder made for businesses of all sizes</h2>
            </div>
        </div>
    </div>
</section>
<section class="jumbotron bg-transparent py-5" id="features">
    <!-- reusable svg icon -->
    <svg style="display: none" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" class=""
        role="img" xmlns="http://www.w3.org/2000/svg">
        <defs>
            <symbol id="tickmark">
                <path fill="currentColor"
                    d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                </path>
            </symbol>
        </defs>
    </svg>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 text-center">
                <h2 class="display-4">Features</h2>
            </div>
        </div>
        <div class="row text-left align-items-center pt-5 pb-md-5">
            <div class="col-4 col-md-5 d-none d-lg-block">
                <img alt="Simple online form builder" class="img-fluid"
                    src="{!! asset('images/product/online-form-builder/features/simple-form-builder.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Simple online form builder" class="img-fluid"
                    src="{!! asset('images/product/online-form-builder/features/simple-form-builder.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 m-md-auto d-none d-lg-block">
                <h2><strong>Online form creation made simple</strong></h2>
                <p class="lead">Create forms that suits your business requirement. Add a title, help text or
                    placeholders in your forms, make it easy for your customers to fill your forms and submit validated
                    data. Our simple to use form builder supports an array of form fields like:</p>
                <ul class="list-unstyled">
                    <li class="lead">
                        <svg class="h-4 pr-1 text-primary" viewBox="0 0 512 512">
                            <use href="#tickmark" /></svg>
                        Text or Text Area
                    </li>
                    <li class="lead">
                        <svg class="h-4 pr-1 text-primary" viewBox="0 0 512 512">
                            <use href="#tickmark" /></svg>
                        Email or Mobile
                    </li>
                    <li class="lead">
                        <svg class="h-4 pr-1 text-primary" viewBox="0 0 512 512">
                            <use href="#tickmark" /></svg>
                        Number or Money
                    </li>
                    <li class="lead">
                        <svg class="h-4 pr-1 text-primary" viewBox="0 0 512 512">
                            <use href="#tickmark" /></svg>
                        Checkbox or Radio button
                    </li>
                    <li class="lead">
                        <svg class="h-4 pr-1 text-primary" viewBox="0 0 512 512">
                            <use href="#tickmark" /></svg>
                        Drop down
                    </li>
                    <li class="lead">
                        <svg class="h-4 pr-1 text-primary" viewBox="0 0 512 512">
                            <use href="#tickmark" /></svg>
                        File uploads
                    </li>
                    <li class="lead">
                        <svg class="h-4 pr-1 text-primary" viewBox="0 0 512 512">
                            <use href="#tickmark" /></svg>
                        And more
                    </li>
                </ul>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2><strong>Online form creation made simple</strong></h2>
                <p class="lead">Create forms that suits your business requirement. Add a title, help text or
                    placeholders in your forms, make it easy for your customers to fill your forms and submit validated
                    data. Our simple to use form builder supports an array of form fields like:</p>
                <ul class="list-unstyled">
                    <li class="lead">
                        <svg class="h-4 pr-1 text-primary" viewBox="0 0 512 512">
                            <use href="#tickmark" /></svg>
                        Text or Text Area
                    </li>
                    <li class="lead">
                        <svg class="h-4 pr-1 text-primary" viewBox="0 0 512 512">
                            <use href="#tickmark" /></svg>
                        Email or Mobile
                    </li>
                    <li class="lead">
                        <svg class="h-4 pr-1 text-primary" viewBox="0 0 512 512">
                            <use href="#tickmark" /></svg>
                        Number or Money
                    </li>
                    <li class="lead">
                        <svg class="h-4 pr-1 text-primary" viewBox="0 0 512 512">
                            <use href="#tickmark" /></svg>
                        Checkbox or Radio button
                    </li>
                    <li class="lead">
                        <svg class="h-4 pr-1 text-primary" viewBox="0 0 512 512">
                            <use href="#tickmark" /></svg>
                        Drop down
                    </li>
                    <li class="lead">
                        <svg class="h-4 pr-1 text-primary" viewBox="0 0 512 512">
                            <use href="#tickmark" /></svg>
                        File uploads
                    </li>
                    <li class="lead">
                        <svg class="h-4 pr-1 text-primary" viewBox="0 0 512 512">
                            <use href="#tickmark" /></svg>
                        And more
                    </li>
                </ul>
            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5 pb-md-5">
            <div class="col-4 col-md-5 m-md-auto order-md-5 d-none d-lg-block">
                <img alt="Online form builder with online payments" class="img-fluid"
                    src="{!! asset('images/product/online-form-builder/features/form-builder-with-online-payments.png') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Online form builder with online payments" class="img-fluid"
                    src="{!! asset('images/product/online-form-builder/features/form-builder-with-online-payments.png') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 d-none d-lg-block">
                <h2><strong>Form builder with online payment collections</strong></h2>
                <p class="lead">Collect not just data but also payments with our online form builder. Form creator that
                    allows you to collect money as per your business rules with customized landing pages. Setup your
                    payment amount logic as per the data submitted by your customer. Integrated with payment gateways
                    that deposit collected funds directly to your bank account. Allow your customer to make online
                    payments using:</p>
                <ul class="list-unstyled">
                    <li class="lead">
                        <svg class="h-4 pr-1 text-primary" viewBox="0 0 512 512">
                            <use href="#tickmark" /></svg>
                        UPI
                    </li>
                    <li class="lead">
                        <svg class="h-4 pr-1 text-primary" viewBox="0 0 512 512">
                            <use href="#tickmark" /></svg>
                        Debit card
                    </li>
                    <li class="lead">
                        <svg class="h-4 pr-1 text-primary" viewBox="0 0 512 512">
                            <use href="#tickmark" /></svg>
                        Credit card
                    </li>
                    <li class="lead">
                        <svg class="h-4 pr-1 text-primary" viewBox="0 0 512 512">
                            <use href="#tickmark" /></svg>
                        Net Banking
                    </li>
                    <li class="lead">
                        <svg class="h-4 pr-1 text-primary" viewBox="0 0 512 512">
                            <use href="#tickmark" /></svg>
                        Wallets
                    </li>
                </ul>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>Form builder with online payment collections</strong></h2>
                <p class="lead">Collect not just data but also payments with our online form builder. Form creator that
                    allows you to collect money as per your business rules with customized landing pages. Setup your
                    payment amount logic as per the data submitted by your customer. Integrated with payment gateways
                    that deposit collected funds directly to your bank account. Allow your customer to make online
                    payments using:</p>
                <ul class="list-unstyled">
                    <li class="lead">
                        <svg class="h-4 pr-1 text-primary" viewBox="0 0 512 512">
                            <use href="#tickmark" /></svg>
                        UPI
                    </li>
                    <li class="lead">
                        <svg class="h-4 pr-1 text-primary" viewBox="0 0 512 512">
                            <use href="#tickmark" /></svg>
                        Debit card
                    </li>
                    <li class="lead">
                        <svg class="h-4 pr-1 text-primary" viewBox="0 0 512 512">
                            <use href="#tickmark" /></svg>
                        Credit card
                    </li>
                    <li class="lead">
                        <svg class="h-4 pr-1 text-primary" viewBox="0 0 512 512">
                            <use href="#tickmark" /></svg>
                        Net Banking
                    </li>
                    <li class="lead">
                        <svg class="h-4 pr-1 text-primary" viewBox="0 0 512 512">
                            <use href="#tickmark" /></svg>
                        Wallets
                    </li>
                </ul>
            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5 pb-md-5">
            <div class="col-4 col-md-5 d-none d-lg-block">
                <img alt="Split payments between parties" class="img-fluid"
                    src="{!! asset('images/product/online-form-builder/features/form-builder-with-automations.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Split payments between parties" class="img-fluid"
                    src="{!! asset('images/product/online-form-builder/features/form-builder-with-automations.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 m-md-auto d-none d-lg-block">
                <h2><strong>Form builder with useful automations</strong></h2>
                <p class="lead">Forms which collect not just data or payments but also help with automating key parts of
                    your day to day business operations. Imagine collecting funds online and then creating invoices
                    manually? That does not sound right does it? Our online form builder comes with integrations which
                    automate all your routine tasks within minutes. Automations that save time and effort for your
                    business every day:</p>
                <ul class="list-unstyled">
                    <li class="lead">
                        <svg class="h-4 pr-1 text-primary" viewBox="0 0 512 512">
                            <use href="#tickmark" /></svg>
                        Auto generate invoices using our invoice templates
                    </li>
                    <li class="lead">
                        <svg class="h-4 pr-1 text-primary" viewBox="0 0 512 512">
                            <use href="#tickmark" /></svg>
                        Auto generate GST friendly invoices with automated allocation of CGST, SGST or IGST depending on
                        customer input
                    </li>
                    <li class="lead">
                        <svg class="h-4 pr-1 text-primary" viewBox="0 0 512 512">
                            <use href="#tickmark" /></svg>
                        Send invoices to customers automatically on email and SMS post payment
                    </li>
                    <li class="lead">
                        <svg class="h-4 pr-1 text-primary" viewBox="0 0 512 512">
                            <use href="#tickmark" /></svg>
                        Send email notifications upon form submission to yourself or your customer
                    </li>
                    <li class="lead">
                        <svg class="h-4 pr-1 text-primary" viewBox="0 0 512 512">
                            <use href="#tickmark" /></svg>
                        Automate any process using our web hooks! Web hooks help to setup workflows upon form submission
                        or payment completion
                    </li>
                    <li class="lead">
                        <svg class="h-4 pr-1 text-primary" viewBox="0 0 512 512">
                            <use href="#tickmark" /></svg>
                        Export data to excel and import it any of your existing tools
                    </li>
                </ul>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2><strong>Form builder with useful automations</strong></h2>
                <p class="lead">Forms which collect not just data or payments but also help with automating key parts of
                    your day to day business operations. Imagine collecting funds online and then creating invoices
                    manually? That does not sound right does it? Our online form builder comes with integrations which
                    automate all your routine tasks within minutes. Automations that save time and effort for your
                    business every day:</p>
                <ul class="list-unstyled">
                    <li class="lead">
                        <svg class="h-4 pr-1 text-primary" viewBox="0 0 512 512">
                            <use href="#tickmark" /></svg>
                        Auto generate invoices using our invoice templates
                    </li>
                    <li class="lead">
                        <svg class="h-4 pr-1 text-primary" viewBox="0 0 512 512">
                            <use href="#tickmark" /></svg>
                        Auto generate GST friendly invoices with automated allocation of CGST, SGST or IGST depending on
                        customer input
                    </li>
                    <li class="lead">
                        <svg class="h-4 pr-1 text-primary" viewBox="0 0 512 512">
                            <use href="#tickmark" /></svg>
                        Send invoices to customers automatically on email and SMS post payment
                    </li>
                    <li class="lead">
                        <svg class="h-4 pr-1 text-primary" viewBox="0 0 512 512">
                            <use href="#tickmark" /></svg>
                        Send email notifications upon form submission to yourself or your customer
                    </li>
                    <li class="lead">
                        <svg class="h-4 pr-1 text-primary" viewBox="0 0 512 512">
                            <use href="#tickmark" /></svg>
                        Automate any process using our web hooks! Web hooks help to setup workflows upon form submission
                        or payment completion
                    </li>
                    <li class="lead">
                        <svg class="h-4 pr-1 text-primary" viewBox="0 0 512 512">
                            <use href="#tickmark" /></svg>
                        Export data to excel and import it any of your existing tools
                    </li>
                </ul>
            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5 pb-md-5">
            <div class="col-4 col-md-5 m-md-auto order-md-5 d-none d-lg-block">
                <img alt="Manage franchise payments" class="img-fluid"
                    src="{!! asset('images/product/online-form-builder/features/share-form-links.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Manage franchise payments" class="img-fluid"
                    src="{!! asset('images/product/online-form-builder/features/share-form-links.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 d-none d-lg-block">
                <h2><strong>Create easy to share forms</strong></h2>
                <p class="lead">Online forms should be easy to share so that you can collect customer data on time. Our
                    online form builder makes it easy to share on any medium of your choice. Make paperform a thing of
                    the past enhance your respondents experience by creating or sharing your form anywhere:</p>
                <ul class="list-unstyled">
                    <li class="lead">
                        <svg class="h-4 pr-1 text-primary" viewBox="0 0 512 512">
                            <use href="#tickmark" /></svg>
                        Facebook / Instagram
                    </li>
                    <li class="lead">
                        <svg class="h-4 pr-1 text-primary" viewBox="0 0 512 512">
                            <use href="#tickmark" /></svg>
                        Whatsapp
                    </li>
                    <li class="lead">
                        <svg class="h-4 pr-1 text-primary" viewBox="0 0 512 512">
                            <use href="#tickmark" /></svg>
                        Your website
                    </li>
                    <li class="lead">
                        <svg class="h-4 pr-1 text-primary" viewBox="0 0 512 512">
                            <use href="#tickmark" /></svg>
                        SMS
                    </li>
                    <li class="lead">
                        <svg class="h-4 pr-1 text-primary" viewBox="0 0 512 512">
                            <use href="#tickmark" /></svg>
                        Email
                    </li>
                </ul>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2><strong>Create easy to share forms</strong></h2>
                <p class="lead">Online forms should be easy to share so that you can collect customer data on time. Our
                    online form builder makes it easy to share on any medium of your choice. Make paperform a thing of
                    the past enhance your respondents experience by creating or sharing your form anywhere:</p>
                <ul class="list-unstyled">
                    <li class="lead">
                        <svg class="h-4 pr-1 text-primary" viewBox="0 0 512 512">
                            <use href="#tickmark" /></svg>
                        Facebook / Instagram
                    </li>
                    <li class="lead">
                        <svg class="h-4 pr-1 text-primary" viewBox="0 0 512 512">
                            <use href="#tickmark" /></svg>
                        Whatsapp
                    </li>
                    <li class="lead">
                        <svg class="h-4 pr-1 text-primary" viewBox="0 0 512 512">
                            <use href="#tickmark" /></svg>
                        Your website
                    </li>
                    <li class="lead">
                        <svg class="h-4 pr-1 text-primary" viewBox="0 0 512 512">
                            <use href="#tickmark" /></svg>
                        SMS
                    </li>
                    <li class="lead">
                        <svg class="h-4 pr-1 text-primary" viewBox="0 0 512 512">
                            <use href="#tickmark" /></svg>
                        Email
                    </li>
                </ul>
            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5 pb-md-5">
            <div class="col-4 col-md-5 d-none d-lg-block">
                <img alt="Online form builder for data collection" class="img-fluid"
                    src="{!! asset('images/product/online-form-builder/features/data-collection.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Online form builder for data collection" class="img-fluid"
                    src="{!! asset('images/product/online-form-builder/features/data-collection.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 m-md-auto d-none d-lg-block">
                <h2><strong>Collect data for any scenario</strong></h2>
                <p class="lead">Collect data for a few or thousands of respondents without any coding. No matter which
                    industry segment you belong to, you can customize the form landing pages with your logo and color to
                    make it your own. Whether you need to present your customers a mobile form or web form, for an one
                    time activity or an ongoing basis, to collect KYC information or just some basic leads data we have
                    you covered. Businesses from the following segments are collecting data from their users using our
                    online form builder:</p>
                <ul class="list-unstyled">
                    <li class="lead">
                        <svg class="h-4 pr-1 text-primary" viewBox="0 0 512 512">
                            <use href="#tickmark" /></svg>
                        Schools & Education
                    </li>
                    <li class="lead">
                        <svg class="h-4 pr-1 text-primary" viewBox="0 0 512 512">
                            <use href="#tickmark" /></svg>
                        Accountancy firms
                    </li>
                    <li class="lead">
                        <svg class="h-4 pr-1 text-primary" viewBox="0 0 512 512">
                            <use href="#tickmark" /></svg>
                        E-Commerce
                    </li>
                    <li class="lead">
                        <svg class="h-4 pr-1 text-primary" viewBox="0 0 512 512">
                            <use href="#tickmark" /></svg>
                        Utility providers
                    </li>
                    <li class="lead">
                        <svg class="h-4 pr-1 text-primary" viewBox="0 0 512 512">
                            <use href="#tickmark" /></svg>
                        Travel and tour operators
                    </li>
                    <li class="lead">
                        <svg class="h-4 pr-1 text-primary" viewBox="0 0 512 512">
                            <use href="#tickmark" /></svg>
                        Hospitality and Events
                    </li>
                    <li class="lead">
                        <svg class="h-4 pr-1 text-primary" viewBox="0 0 512 512">
                            <use href="#tickmark" /></svg>
                        Health and fitness
                    </li>
                    <li class="lead">
                        <svg class="h-4 pr-1 text-primary" viewBox="0 0 512 512">
                            <use href="#tickmark" /></svg>
                        NGO or non-profits
                    </li>
                    <li class="lead">
                        <svg class="h-4 pr-1 text-primary" viewBox="0 0 512 512">
                            <use href="#tickmark" /></svg>
                        And 12 more industries
                    </li>
                </ul>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>Collect data for any scenario</strong></h2>
                <p class="lead">Collect data for a few or thousands of respondents without any coding. No matter which
                    industry segment you belong to, you can customize the form landing pages with your logo and color to
                    make it your own. Whether you need to present your customers a mobile form or web form, for an one
                    time activity or an ongoing basis, to collect KYC information or just some basic leads data we have
                    you covered. Businesses from the following segments are collecting data from their users using our
                    online form builder:</p>
                <ul class="list-unstyled">
                    <li class="lead">
                        <svg class="h-4 pr-1 text-primary" viewBox="0 0 512 512">
                            <use href="#tickmark" /></svg>
                        Text or Text Area
                    </li>
                    <li class="lead">
                        <svg class="h-4 pr-1 text-primary" viewBox="0 0 512 512">
                            <use href="#tickmark" /></svg>
                        Email or Mobile
                    </li>
                    <li class="lead">
                        <svg class="h-4 pr-1 text-primary" viewBox="0 0 512 512">
                            <use href="#tickmark" /></svg>
                        Number or Money
                    </li>
                    <li class="lead">
                        <svg class="h-4 pr-1 text-primary" viewBox="0 0 512 512">
                            <use href="#tickmark" /></svg>
                        Checkbox or Radio button
                    </li>
                    <li class="lead">
                        <svg class="h-4 pr-1 text-primary" viewBox="0 0 512 512">
                            <use href="#tickmark" /></svg>
                        Drop down
                    </li>
                    <li class="lead">
                        <svg class="h-4 pr-1 text-primary" viewBox="0 0 512 512">
                            <use href="#tickmark" /></svg>
                        File uploads
                    </li>
                    <li class="lead">
                        <svg class="h-4 pr-1 text-primary" viewBox="0 0 512 512">
                            <use href="#tickmark" /></svg>
                        And more
                    </li>
                </ul>
            </div>
            <!-- end -->
        </div>
    </div>
</section>
<section class="jumbotron bg-secondary py-5" id="cta">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-12 mb-5">
                <h3 class="text-white">{{env('SWIPEZ_BIZ_NUM')}} businesses use Swipez on a day to day
                    basis<br /><br />Try the online form builder for free</h3>
            </div>
            <div class="col-12 d-none d-sm-block">
                <a class="btn btn-lg text-white bg-primary" href="{{ config('app.APP_URL') }}merchant/register">Register
                    for a free account</a>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-sm-none">
                <div class="row justify-content-center">
                    <a class="btn btn-lg bg-primary text-white"
                        href="{{ config('app.APP_URL') }}merchant/register">Register for a free account</a>
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
                <h2 class="display-4 text-white">Your forms as you need it</h2>
            </div>
        </div><br />
        <div class="row row-eq-height text-center">
            <div class="col-md-3">
                <div class="bg-white p-4 h-100">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="list-ol"
                        class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 512 512">
                        <path fill="currentColor"
                            d="M61.77 401l17.5-20.15a19.92 19.92 0 0 0 5.07-14.19v-3.31C84.34 356 80.5 352 73 352H16a8 8 0 0 0-8 8v16a8 8 0 0 0 8 8h22.83a157.41 157.41 0 0 0-11 12.31l-5.61 7c-4 5.07-5.25 10.13-2.8 14.88l1.05 1.93c3 5.76 6.29 7.88 12.25 7.88h4.73c10.33 0 15.94 2.44 15.94 9.09 0 4.72-4.2 8.22-14.36 8.22a41.54 41.54 0 0 1-15.47-3.12c-6.49-3.88-11.74-3.5-15.6 3.12l-5.59 9.31c-3.72 6.13-3.19 11.72 2.63 15.94 7.71 4.69 20.38 9.44 37 9.44 34.16 0 48.5-22.75 48.5-44.12-.03-14.38-9.12-29.76-28.73-34.88zM496 224H176a16 16 0 0 0-16 16v32a16 16 0 0 0 16 16h320a16 16 0 0 0 16-16v-32a16 16 0 0 0-16-16zm0-160H176a16 16 0 0 0-16 16v32a16 16 0 0 0 16 16h320a16 16 0 0 0 16-16V80a16 16 0 0 0-16-16zm0 320H176a16 16 0 0 0-16 16v32a16 16 0 0 0 16 16h320a16 16 0 0 0 16-16v-32a16 16 0 0 0-16-16zM16 160h64a8 8 0 0 0 8-8v-16a8 8 0 0 0-8-8H64V40a8 8 0 0 0-8-8H32a8 8 0 0 0-7.14 4.42l-8 16A8 8 0 0 0 24 64h8v64H16a8 8 0 0 0-8 8v16a8 8 0 0 0 8 8zm-3.91 160H80a8 8 0 0 0 8-8v-16a8 8 0 0 0-8-8H41.32c3.29-10.29 48.34-18.68 48.34-56.44 0-29.06-25-39.56-44.47-39.56-21.36 0-33.8 10-40.46 18.75-4.37 5.59-3 10.84 2.8 15.37l8.58 6.88c5.61 4.56 11 2.47 16.12-2.44a13.44 13.44 0 0 1 9.46-3.84c3.33 0 9.28 1.56 9.28 8.75C51 248.19 0 257.31 0 304.59v4C0 316 5.08 320 12.09 320z">
                        </path>
                    </svg>
                    <h3 class="text-secondary pb-2">Pick your form fields</h3>
                    <p>Setup every form field and button as you need it. Create any form that you need and apply field
                        validations to get accurate data every time!</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="bg-white p-4 h-100">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="money-bill-wave"
                        class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 640 512">
                        <path fill="currentColor"
                            d="M621.16 54.46C582.37 38.19 543.55 32 504.75 32c-123.17-.01-246.33 62.34-369.5 62.34-30.89 0-61.76-3.92-92.65-13.72-3.47-1.1-6.95-1.62-10.35-1.62C15.04 79 0 92.32 0 110.81v317.26c0 12.63 7.23 24.6 18.84 29.46C57.63 473.81 96.45 480 135.25 480c123.17 0 246.34-62.35 369.51-62.35 30.89 0 61.76 3.92 92.65 13.72 3.47 1.1 6.95 1.62 10.35 1.62 17.21 0 32.25-13.32 32.25-31.81V83.93c-.01-12.64-7.24-24.6-18.85-29.47zM48 132.22c20.12 5.04 41.12 7.57 62.72 8.93C104.84 170.54 79 192.69 48 192.69v-60.47zm0 285v-47.78c34.37 0 62.18 27.27 63.71 61.4-22.53-1.81-43.59-6.31-63.71-13.62zM320 352c-44.19 0-80-42.99-80-96 0-53.02 35.82-96 80-96s80 42.98 80 96c0 53.03-35.83 96-80 96zm272 27.78c-17.52-4.39-35.71-6.85-54.32-8.44 5.87-26.08 27.5-45.88 54.32-49.28v57.72zm0-236.11c-30.89-3.91-54.86-29.7-55.81-61.55 19.54 2.17 38.09 6.23 55.81 12.66v48.89z">
                        </path>
                    </svg>
                    <h3 class="text-secondary pb-2">Forms with online payment</h3>
                    <p>Collect data and online payment from the users who fill your form. Setup payment amounts as per
                        conditional field logic.</p>
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
                    <h3 class="text-secondary pb-2">Data always available</h3>
                    <p>Your users data is stored in a secure environment and always available for you to access from
                        anywhere in your account!</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="bg-white p-4 h-100">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="code"
                        class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 640 512">
                        <path fill="currentColor"
                            d="M278.9 511.5l-61-17.7c-6.4-1.8-10-8.5-8.2-14.9L346.2 8.7c1.8-6.4 8.5-10 14.9-8.2l61 17.7c6.4 1.8 10 8.5 8.2 14.9L293.8 503.3c-1.9 6.4-8.5 10.1-14.9 8.2zm-114-112.2l43.5-46.4c4.6-4.9 4.3-12.7-.8-17.2L117 256l90.6-79.7c5.1-4.5 5.5-12.3.8-17.2l-43.5-46.4c-4.5-4.8-12.1-5.1-17-.5L3.8 247.2c-5.1 4.7-5.1 12.8 0 17.5l144.1 135.1c4.9 4.6 12.5 4.4 17-.5zm327.2.6l144.1-135.1c5.1-4.7 5.1-12.8 0-17.5L492.1 112.1c-4.8-4.5-12.4-4.3-17 .5L431.6 159c-4.6 4.9-4.3 12.7.8 17.2L523 256l-90.6 79.7c-5.1 4.5-5.5 12.3-.8 17.2l43.5 46.4c4.5 4.9 12.1 5.1 17 .6z">
                        </path>
                    </svg>
                    <h3 class="text-secondary pb-2">Automate or integrate</h3>
                    <p>Integrate your forms into your existing apps or products. Automate your business process and
                        services the way you like it!</p>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="jumbotron bg-secondary py-5" id="cta">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-12 mb-5">
                <h3 class="text-white">Over {{env('SWIPEZ_BIZ_NUM')}} businesses use Swipez apps like form builder on a
                    daily basis!</h3>
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
                <p class="lead">You are in good company. Join {{env('SWIPEZ_BIZ_NUM')}} happy businesses who are already
                    using Swipez apps.</p>
            </div>
        </div>
        <div class="row align-items-center justify-content-center">
            <div class="col-lg-9 col-xl-6">
                <div class="card p-5">
                    <div class="row">
                        <div class="col-8 col-sm-6 col-md-4 col-xl-3 ml-auto mr-auto">
                            <img alt="Form builder testimonial hispanic horizon logo" class="img-fluid rounded"
                                src="{!! asset('images/product/online-form-builder/hispanic-hoirizon-logo.png') !!}">
                        </div>
                        <div class="col-md-8 mt-4 mt-md-0">
                            <p>"Our Spanish language institute is run by a small and dedicated team of professionals.
                                Swipez form builder has saved us multiple hours every week, it not only helps us collect
                                student details and contact information but also online payments for all our courses."
                            </p>
                            <p class="h3 mt-4 mt-xl-5">
                                <strong>Blanca Dean</strong>
                            </p>
                            <p>
                                <em>Founder, Hispanic Horizons</em>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-9 col-xl-6 mt-4 mt-xl-0">
                <div class="card p-5">
                    <div class="row">
                        <div class="col-8 col-sm-6 col-md-4 col-xl-3 ml-auto mr-auto">
                            <img alt="Form builder testimonial Pune eat outs logo" class="img-fluid rounded"
                                src="{!! asset('images/product/online-form-builder/pune-eat-outs-logo.png') !!}">
                        </div>
                        <div class="col-md-8 mt-4 mt-md-0">
                            <p>"Collecting data and payments from our customers and partners has become a breeze using
                                the online form creator from Swipez. All our data and payment information is also
                                available in one dashboard and the automation helps us send invoices as well."</p>
                            <p class="h3 mt-4 mt-xl-5">
                                <strong>Aniruddha Patil</strong>
                            </p>
                            <p>
                                <em>Founder, Pune Eat Outs</em>
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
                <h3>Form building and collecting user data was never this simple!</h3>
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
                        <p class="lead">Looking for more information? Here are some things we're commonly asked</p>
                    </div>
                </div>
                <!--Accordion wrapper-->
                <div id="accordion" itemscope itemtype="http://schema.org/FAQPage">
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingOne">

                        <div itemprop="name" class="btn btn-link color-black" data-toggle="collapse"
                            data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            <h4>What is the cost of using Swipez online form builder?</h4>
                        </div>
                        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne"
                            data-parent="#accordion" itemscope itemprop="acceptedAnswer"
                            itemtype="http://schema.org/Answer" itemscope itemprop="acceptedAnswer"
                            itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Swipez form builder is part of our <a href="{{route('home.pricing.billing')}}">Growth
                                    plan</a>, but in case you want to avail it without
                                the other facilities in our Growth plan our sales support team will be happy to provide
                                you a sweet discount 🤗
                            </div>
                        </div>
                    </div>



                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingTwo">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            <h4>Are there any limits on the form field or user data I can collect?</h4>
                        </div>
                        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion"
                            itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                No, there is no limit. You can create a form with as many fields as you need. Also,
                                there is no limit on the data you can collect from the responses you get on your form.
                            </div>
                        </div>
                    </div>


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingThree">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            <h4>Can I collect payments from users along with form data?</h4>
                        </div>
                        <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion"
                            itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, you have the ability to collect payments from your users via the Swipez online form
                                builder. Payments can be collected using multiple payment modes like UPI, Debit card,
                                Credit Card, Net banking and Wallets.
                            </div>
                        </div>
                    </div>


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingFour">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                            <h4>Is my form protected from spam responses?</h4>
                        </div>
                        <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion"
                            itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, we protect every form with a Google captcha. This ensures that there is no spam or
                                bot responses collected against your form.
                            </div>
                        </div>
                    </div>

                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingNinteen">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapseNinteen" aria-expanded="false" aria-controls="collapseNinteen">
                            <h4>Are there form templates I can use?</h4>
                        </div>
                        <div id="collapseNinteen" class="collapse" aria-labelledby="headingNinteen"
                            data-parent="#accordion" itemscope itemprop="acceptedAnswer"
                            itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, you can build a form using any of our existing templates and you can build a form
                                from scratch.
                            </div>
                        </div>
                    </div>

                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingTwenty">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapseTwenty" aria-expanded="false" aria-controls="collapseTwenty">
                            <h4>Can I build the form online?</h4>
                        </div>
                        <div id="collapseTwenty" class="collapse" aria-labelledby="headingTwenty"
                            data-parent="#accordion" itemscope itemprop="acceptedAnswer"
                            itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, you can simply submit a request of the fields and validations you need over an
                                email. Your form link will ready for usage within a few hours. Within the next few
                                months, we will be making the form creation available for you to use as well.
                            </div>
                        </div>
                    </div>

                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingFive">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                            <h4>Can I create a questions based form like survey?</h4>
                        </div>
                        <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordion"
                            itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, you add questions and collect responses like a survey or questionnaire using our
                                online
                                form builder.
                            </div>
                        </div>
                    </div>


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingSix">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                            <h4>How is this different from other form builders?</h4>
                        </div>
                        <div id="collapseSix" class="collapse" aria-labelledby="headingSix" data-parent="#accordion"
                            itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                There are many form builders available online. Here is a concise difference between
                                other form builders and Swipez online form builder:<br /><br />
                                Google forms - It is very easy to create Google forms but it has no online payment
                                options. Google forms also lack of automations like invoice generation or web hooks.
                                Moreover there is no central dashboard to view all your data in one
                                dashboard<br /><br />
                                Typeform - You can create visually appealing forms using typeform but it cannot be
                                integrated with a payment gateway of your choice unlike Swipez online form builder.
                                While there are automations possible in Typeform, there is no automation to create an
                                invoice or webhooks.<br /><br />
                                Also, a key point is pricing; Swipez online form builder is one of the most cost
                                effective solutions out there.
                            </div>
                        </div>
                    </div>


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingSeven">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
                            <h4>Can I integrate my form with my website or apps?</h4>
                        </div>
                        <div id="collapseSeven" class="collapse" aria-labelledby="headingSeven" data-parent="#accordion"
                            itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, a lot of businesses use Swipez online form builder to enhance their existing apps
                                or website. By using a form builder businesses are able to collect information from
                                their customers without writing a single line of code and changing their existing apps.
                            </div>
                        </div>
                    </div>


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingEight">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapseEight" aria-expanded="false" aria-controls="collapseEight">
                            <h4>How many forms can I create?</h4>
                        </div>
                        <div id="collapseEight" class="collapse" aria-labelledby="headingEight" data-parent="#accordion"
                            itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                There is no limitation on the number of forms you can create or the information you can
                                collect using Swipez online form builder.
                            </div>
                        </div>
                    </div>


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingNine">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapseNine" aria-expanded="false" aria-controls="collapseNine">
                            <h4>Is my data secure?</h4>
                        </div>
                        <div id="collapseNine" class="collapse" aria-labelledby="headingNine" data-parent="#accordion"
                            itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                At Swipez we really value our customer's privacy. You rely on us for a big part of your
                                business, so we really take your needs seriously. That is why the security of our
                                software, systems and business data are our number one priority. All information that is
                                transmitted between your browser and Swipez is protected with 256-bit SSl encryption.
                                This ensures to keep data secure and unreadable during transit. All the business data
                                you have entered into Swipez sits securely behind web firewalls.
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

@endsection
