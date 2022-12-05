@extends('home.master')
@section('title', 'Travel and tour operator billing and invoicing software.')

@section('content')
<section class="jumbotron jumbotron-features bg-transparent py-5" id="header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12 col-md-12 col-lg-6 col-xl-5">
                <h1>Billing software for travel and tour operators</h1>
                <p class="lead mb-5">Organize your invoicing, collect payments faster and manage pay outs with ease.</p>
                @include('home.product.web_register',['d_type' => "web"])
            </div>
            <div class="col-12 col-md-8 m-auto ml-lg-auto mr-lg-0 col-lg-6 pt-5 pt-lg-0">
                <img alt="Billing software for travel and tour operators" class="img-fluid"
                     src="{!! asset('images/product/billing-software/industry/travel-tour-operator.svg') !!}" />
            </div>
        </div>
    </div>
</section>
<section class="jumbotron py-5 bg-secondary text-white" id="cta">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-12">
                <h2>Billing solution for travel and tour operators of all sizes.</h2>
            </div>
        </div>
    </div>
</section>
<section class="jumbotron py-5 bg-transparent" id="steps">
    <div class="container">
        <h2 class="text-center pb-5">Travel and tour operators <a href="{{ route('home.billing') }}">billing software</a></h2>
        <p class="lead">The Swipez billing software for travel and tour companies assists travel agents and tour operators by automating their day to day activities and cutting down their manual effort.</p>
        <div class="container py-2">
            <div class="row no-gutters pb-5">
                <div class="col-sm">
                    <div class="card card-border-none">
                        <div class="card-media">
                            <img src="{!! asset('images/product/billing-software/industry/cable/ala-cart-selection-of-channels.svg') !!}"
                                 class="img-fluid" />
                        </div>
                    </div>
                </div>
                <div class="col-sm-1 text-center flex-column d-none d-sm-flex">
                    <h5 class="m-2">
                        <button type="button" class="btn btn-primary btn-circle">1</button>
                    </h5>
                    <div class="row h-100">
                        <div class="col border-right">&nbsp;</div>
                        <div class="col">&nbsp;</div>
                    </div>
                </div>
                <div class="col-sm py-2">
                    <div class="card card-border-none">
                        <div class="card-body">
                            <h2 class="card-title">Advance payments Using our risk free,100% secure travel and tour billing software
                            </h2>
                            <p class="card-text">Most travellers are scattered across the globe and collecting payments for bookings becomes a huge challenge. International money transfers are cumbersome, time consuming and bare high transaction fees. Using Swipez travel operators can send out electronic invoices anywhere in the world and your customers can pay you online, thus reducing risk and time required to book your customers perfect vacation.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row no-gutters pb-5">
                <div class="col-sm py-2">
                    <div class="card d-none d-sm-flex card-border-none">
                        <div class="card-body">
                            <h2 class="card-title">Accurate invoicing  with Swipez’s error free billing software for travel and tour company</h2>
                            <p class="card-text">The travel industry has very specific travel information that it needs on its invoices.For car rentals tracking starting kilo meter, ending kilo meter, starting time, ending time of the journey, room details for hotel bookings and flight details for air travel. The Swipez platform addresses these by providing customized travel industry templates.</p>
                        </div>
                    </div>
                    <!-- for iPad and mobile -->
                    <div class="card card-border-none d-sm-none">
                        <div class="card-media">
                            <img src="{!! asset('images/product/billing-software/industry/cable/subscriber-data-management.svg') !!}"
                                 class="img-fluid" />
                        </div>
                    </div>
                    <!-- end -->
                </div>
                <div class="col-sm-1 text-center flex-column d-none d-sm-flex">
                    <h5 class="m-2">
                        <button type="button" class="btn btn-primary btn-circle">2</button>
                    </h5>
                    <div class="row h-100">
                        <div class="col border-right">&nbsp;</div>
                        <div class="col">&nbsp;</div>
                    </div>
                </div>
                <div class="col-sm">
                    <div class="card card-border-none d-none d-sm-flex">
                        <div class="card-media">
                            <img src="{!! asset('images/product/billing-software/industry/cable/subscriber-data-management.svg') !!}"
                                 class="img-fluid" />
                        </div>
                    </div>
                    <!-- for iPad and mobile -->
                    <div class="card card-border-none d-sm-none">
                        <div class="card-body">
                            <h2 class="card-title">Accurate invoicing  with Swipez’s error free billing software for travel and tour company</h2>
                            <p class="card-text">The travel industry has very specific travel information that it needs on its invoices.For car rentals tracking starting kilo meter, ending kilo meter, starting time, ending time of the journey, room details for hotel bookings and flight details for air travel. The Swipez platform addresses these by providing customized travel industry templates.</p>
                            </p>
                        </div>
                    </div>
                    <!-- end -->
                </div>
            </div>
            <div class="row no-gutters pb-5">
                <div class="col-sm">
                    <div class="card card-border-none">
                        <div class="card-media">
                            <img src="{!! asset('images/product/billing-software/industry/cable/accurate-billing.svg') !!}"
                                 class="img-fluid" />
                        </div>
                    </div>
                </div>
                <div class="col-sm-1 text-center flex-column d-none d-sm-flex">
                    <h5 class="m-2">
                        <button type="button" class="btn btn-primary btn-circle">3</button>
                    </h5>
                    <div class="row h-100">
                        <div class="col border-right">&nbsp;</div>
                        <div class="col">&nbsp;</div>
                    </div>
                </div>
                <div class="col-sm py-2">
                    <div class="card card-border-none">
                        <div class="card-body">
                            <h2 class="card-title">Professional Estimates using the Swipez billing software for travel and tour operators </h2>
                            <p class="card-text">Tour and travel agencies need to send out many itinerary estimates to prospective clients.Using Swipez sending quotes/estimates to potential clients has never been easier. Once your offer is accepted, you can convert it into an invoice with just one click!
                            </p>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row no-gutters pb-5">
                <div class="col-sm py-2">
                    <div class="card d-none d-sm-flex card-border-none">
                        <div class="card-body">
                            <h2 class="card-title">Real time updates with the Swipez travel and tour billing software dashboard</h2>
                            <p class="card-text">Most bookings are time sensitive and have a cost implication associated with it. Knowing when payments are received are of key importance to beat the booking price rise. With Swipez get notified through multiple channels when you receive a  customer payment and avoid and delays in booking your customers perfect trip.</p>
                        </div>
                    </div>
                    <!-- for iPad and mobile -->
                    <div class="card card-border-none d-sm-none">
                        <div class="card-media">
                            <img src="{!! asset('images/product/billing-software/industry/cable/accurate-reporting.svg') !!}"
                                 class="img-fluid" />
                        </div>
                    </div>
                    <!-- end -->
                </div>
                <div class="col-sm-1 text-center flex-column d-none d-sm-flex">
                    <h5 class="m-2">
                        <button type="button" class="btn btn-primary btn-circle">4</button>
                    </h5>
                    <div class="row h-100">
                        <div class="col border-right">&nbsp;</div>
                        <div class="col">&nbsp;</div>
                    </div>
                </div>

                <div class="col-sm">
                    <div class="card card-border-none d-none d-sm-flex">
                        <div class="card-media">
                            <img src="{!! asset('images/product/billing-software/industry/cable/accurate-reporting.svg') !!}"
                                 class="img-fluid" />
                        </div>
                    </div>
                    <!-- for iPad and mobile -->
                    <div class="card card-border-none d-sm-none">
                        <div class="card-body">
                            <h2 class="card-title">Real time updates with the Swipez travel and tour billing software dashboard</h2>
                            <p class="card-text">Most bookings are time sensitive and have a cost implication associated with it. Knowing when payments are received are of key importance to beat the booking price rise. With Swipez get notified through multiple channels when you receive a  customer payment and avoid and delays in booking your customers perfect trip.</p>
                        </div>
                    </div>
                    <!-- end -->
                </div>


            </div>
            <div class="row no-gutters pb-5">
                <div class="col-sm">
                    <div class="card card-border-none">
                        <div class="card-media">
                            <img src="{!! asset('images/product/billing-software/industry/cable/accurate-billing.svg') !!}"
                                 class="img-fluid" />
                        </div>
                    </div>
                </div>


                <div class="col-sm-1 text-center flex-column d-none d-sm-flex">
                    <h5 class="m-2">
                        <button type="button" class="btn btn-primary btn-circle">5</button>
                    </h5>
                    <div class="row h-100">
                        <div class="col border-right">&nbsp;</div>
                        <div class="col">&nbsp;</div>
                    </div>
                </div>
                <div class="col-sm py-2">
                    <div class="card card-border-none">
                        <div class="card-body">
                            <h2 class="card-title">Simplified payments to vendors and supplier</h2>
                            <p class="card-text">Collection of payments is one pain point for tour and travel operators. The second, once you collect this revenue is making payments to your vendors and suppliers. The Swipez billing software makes this easier using one business dashboard you can collect and disburse money.
                            </p>
                        </div>
                    </div>
                </div>

            </div>
            <div class="row no-gutters pb-5">
                <div class="col-sm py-2">
                    <div class="card d-none d-sm-flex card-border-none">
                        <div class="card-body">
                            <h2 class="card-title">Tracking of all collections which include online and offline transactions</h2>
                            <p class="card-text">The Swipez billing software allows agents and administrators a quick and convenient view into payment collections and dues. Agents can track online payments and offline collections on the go using the Swipez software.Thus reducing inefficiencies in the billing cycle. These reports can be viewed by management to get a clear and up to date report of business collections.</p>
                        </div>
                    </div>
                    <!-- for iPad and mobile -->
                    <div class="card card-border-none d-sm-none">
                        <div class="card-media">
                            <img src="{!! asset('images/product/billing-software/industry/cable/accurate-reporting.svg') !!}"
                                 class="img-fluid" />
                        </div>
                    </div>
                    <!-- end -->
                </div>
                <div class="col-sm-1 text-center flex-column d-none d-sm-flex">
                    <h5 class="m-2">
                        <button type="button" class="btn btn-primary btn-circle">6</button>
                    </h5>
                    <div class="row h-100">
                        <div class="col border-right">&nbsp;</div>
                        <div class="col">&nbsp;</div>
                    </div>
                </div>
                <div class="col-sm">
                    <div class="card card-border-none d-none d-sm-flex">
                        <div class="card-media">
                            <img src="{!! asset('images/product/billing-software/industry/cable/accurate-reporting.svg') !!}"
                                 class="img-fluid" />
                        </div>
                    </div>
                    <!-- for iPad and mobile -->
                    <div class="card card-border-none d-sm-none">
                        <div class="card-body">
                            <h2 class="card-title">Tracking of all collections which include online and offline transactions</h2>
                            <p class="card-text">The Swipez billing software allows agents and administrators a quick and convenient view into payment collections and dues. Agents can track online payments and offline collections on the go using the Swipez software.Thus reducing inefficiencies in the billing cycle. These reports can be viewed by management to get a clear and up to date report of business collections.</p>
                        </div>
                    </div>
                    <!-- end -->
                </div>
            </div>



        </div>
    </div>
</section>
<section class="py-7 bg-primary" id="cta">
    <div class="container">
        <div class="row">
            <div class="col mx-auto">
                <div class="bg-secondary rounded-1 p-5">
                    <div class="row text-white">
                        <div class="col-md-6 px-3 d-none d-lg-block">
                            <h2 class="pb-3">
                                <b>Download travel and tour invoice format</b>
                            </h2>
                            <ul class="list-unstyled">
                                <li class="lead text-white">                                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check"
                                        class="h-6 pr-1" role="img" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 512 512">
                                        <path fill="currentColor"
                                            d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                                        </path>
                                    </svg>
Create your invoice in your browser</li>
                                <li class="lead text-white">                                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check"
                                        class="h-6 pr-1" role="img" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 512 512">
                                        <path fill="currentColor"
                                            d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                                        </path>
                                    </svg>
Download free invoice formats</li>
                                <li class="lead text-white">                                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check"
                                        class="h-6 pr-1" role="img" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 512 512">
                                        <path fill="currentColor"
                                            d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                                        </path>
                                    </svg>
Invoice formats as per travel and tour industry</li>
                                <li class="lead text-white">                                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check"
                                        class="h-6 pr-1" role="img" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 512 512">
                                        <path fill="currentColor"
                                            d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                                        </path>
                                    </svg>
Great design to impress your clients!</li>
                            </ul>
                            <a class="btn btn-primary" href="{{ route('home.travelticketinvoiceformat') }}">Ticket booking format</a>
                            <a class="btn btn-primary" href="{{ route('home.travelcarinvoiceformat') }}">Car booking format</a>
                        </div>
                        <div class="col-md-6 d-none d-lg-block">
                            <img alt="Download an invoice as per your business requirement" src="{!! asset('images/home/download-invoice-format.svg') !!}" />
                        </div>
                    </div>
                    <!-- for iPad and mobile -->
                    <div class="row text-white">
                        <div class="col-md-6 d-lg-none mb-3">
                            <img alt="Download an invoice as per your business requirement" src="{!! asset('images/home/download-invoice-format.svg') !!}" />
                        </div>
                        <div class="col-md-6 d-lg-none">
                            <h2 class="py-3 text-center">
                                <b>Download travel and tour invoice format</b>
                            </h2>
                            <ul class="list-unstyled pb-2">
                                <li class="lead text-white">                                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check"
                                        class="h-6 pr-1" role="img" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 512 512">
                                        <path fill="currentColor"
                                            d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                                        </path>
                                    </svg>
Create your invoice in your browser</li>
                                <li class="lead text-white">                                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check"
                                        class="h-6 pr-1" role="img" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 512 512">
                                        <path fill="currentColor"
                                            d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                                        </path>
                                    </svg>
Download free invoice formats</li>
                                <li class="lead text-white">                                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check"
                                        class="h-6 pr-1" role="img" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 512 512">
                                        <path fill="currentColor"
                                            d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                                        </path>
                                    </svg>
Invoice formats as per travel and tour industry</li>
                                <li class="lead text-white">                                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check"
                                        class="h-6 pr-1" role="img" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 512 512">
                                        <path fill="currentColor"
                                            d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                                        </path>
                                    </svg>
Clean design to impress your clients!</li>
                            </ul>
                            <a class="btn btn-primary big-text mb-2" href="{{ route('home.travelticketinvoiceformat') }}">Ticket booking format</a>
                            <a class="btn btn-primary big-text" href="{{ route('home.travelcarinvoiceformat') }}">Car booking format</a>
                        </div>
                    </div>
                    <!-- end -->
                </div>
            </div>
        </div>
    </div>
</section>
<section class="jumbotron py-5 bg-white" id="features">
    <div class="container">
        <div class="row justify-content-center pb-4">
            <div class="col-12 text-center">
                <h2 class="display-4">Features</h2>
            </div>
        </div>
        <div class="row row-eq-height text-center pb-5">
            <div class="col-md-4">
                <div class="bg-light-blue p-4 h-100">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="plane-departure" class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><path fill="currentColor" d="M624 448H16c-8.84 0-16 7.16-16 16v32c0 8.84 7.16 16 16 16h608c8.84 0 16-7.16 16-16v-32c0-8.84-7.16-16-16-16zM80.55 341.27c6.28 6.84 15.1 10.72 24.33 10.71l130.54-.18a65.62 65.62 0 0 0 29.64-7.12l290.96-147.65c26.74-13.57 50.71-32.94 67.02-58.31 18.31-28.48 20.3-49.09 13.07-63.65-7.21-14.57-24.74-25.27-58.25-27.45-29.85-1.94-59.54 5.92-86.28 19.48l-98.51 49.99-218.7-82.06a17.799 17.799 0 0 0-18-1.11L90.62 67.29c-10.67 5.41-13.25 19.65-5.17 28.53l156.22 98.1-103.21 52.38-72.35-36.47a17.804 17.804 0 0 0-16.07.02L9.91 230.22c-10.44 5.3-13.19 19.12-5.57 28.08l76.21 82.97z"></path></svg>
                    <h5 class="pb-2">Travel specific invoice templates</h5>
                    <p>Start with business-ready invoice templates to handle travel bookings like airplane / bus / train
                        tickets, hotel bookings, cancellations and car bookings.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bg-light-blue p-4 h-100">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="file-invoice" class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path fill="currentColor" d="M288 256H96v64h192v-64zm89-151L279.1 7c-4.5-4.5-10.6-7-17-7H256v128h128v-6.1c0-6.3-2.5-12.4-7-16.9zm-153 31V0H24C10.7 0 0 10.7 0 24v464c0 13.3 10.7 24 24 24h336c13.3 0 24-10.7 24-24V160H248c-13.2 0-24-10.8-24-24zM64 72c0-4.42 3.58-8 8-8h80c4.42 0 8 3.58 8 8v16c0 4.42-3.58 8-8 8H72c-4.42 0-8-3.58-8-8V72zm0 64c0-4.42 3.58-8 8-8h80c4.42 0 8 3.58 8 8v16c0 4.42-3.58 8-8 8H72c-4.42 0-8-3.58-8-8v-16zm256 304c0 4.42-3.58 8-8 8h-80c-4.42 0-8-3.58-8-8v-16c0-4.42 3.58-8 8-8h80c4.42 0 8 3.58 8 8v16zm0-200v96c0 8.84-7.16 16-16 16H80c-8.84 0-16-7.16-16-16v-96c0-8.84 7.16-16 16-16h224c8.84 0 16 7.16 16 16z"></path></svg>
                    <h5 class="pb-2">On time billing</h5>
                    <p>Fast and error-free invoicing with online payment collections. GST compliant invoices with
                        customized invoice templates as per your company needs.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bg-light-blue p-4 h-100">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="mail-bulk" class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path fill="currentColor" d="M160 448c-25.6 0-51.2-22.4-64-32-64-44.8-83.2-60.8-96-70.4V480c0 17.67 14.33 32 32 32h256c17.67 0 32-14.33 32-32V345.6c-12.8 9.6-32 25.6-96 70.4-12.8 9.6-38.4 32-64 32zm128-192H32c-17.67 0-32 14.33-32 32v16c25.6 19.2 22.4 19.2 115.2 86.4 9.6 6.4 28.8 25.6 44.8 25.6s35.2-19.2 44.8-22.4c92.8-67.2 89.6-67.2 115.2-86.4V288c0-17.67-14.33-32-32-32zm256-96H224c-17.67 0-32 14.33-32 32v32h96c33.21 0 60.59 25.42 63.71 57.82l.29-.22V416h192c17.67 0 32-14.33 32-32V192c0-17.67-14.33-32-32-32zm-32 128h-64v-64h64v64zm-352-96c0-35.29 28.71-64 64-64h224V32c0-17.67-14.33-32-32-32H96C78.33 0 64 14.33 64 32v192h96v-32z"></path></svg>
                    <h5 class="pb-2">Bulk invoicing</h5>
                    <p>Raise invoices in bulk to a large customer base via excel upload or APIs. Easy upload formats
                        provided out of the box.</p>
                </div>
            </div>
        </div>
        <div class="row row-eq-height text-center pb-5">
            <div class="col-md-4">
                <div class="bg-light-blue p-4 h-100">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="bell" class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M224 512c35.32 0 63.97-28.65 63.97-64H160.03c0 35.35 28.65 64 63.97 64zm215.39-149.71c-19.32-20.76-55.47-51.99-55.47-154.29 0-77.7-54.48-139.9-127.94-155.16V32c0-17.67-14.32-32-31.98-32s-31.98 14.33-31.98 32v20.84C118.56 68.1 64.08 130.3 64.08 208c0 102.3-36.15 133.53-55.47 154.29-6 6.45-8.66 14.16-8.61 21.71.11 16.4 12.98 32 32.1 32h383.8c19.12 0 32-15.6 32.1-32 .05-7.55-2.61-15.27-8.61-21.71z"></path></svg>
                    <h5 class="pb-2">Automated reminders</h5>
                    <p>Payment reminders sent to customers automatically on Email and SMS with <a href="{{ route('home.paymentlink') }}">payment links</a>.
                        Customizable reminder schedule.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bg-light-blue p-4 h-100">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="piggy-bank" class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path fill="currentColor" d="M560 224h-29.5c-8.8-20-21.6-37.7-37.4-52.5L512 96h-32c-29.4 0-55.4 13.5-73 34.3-7.6-1.1-15.1-2.3-23-2.3H256c-77.4 0-141.9 55-156.8 128H56c-14.8 0-26.5-13.5-23.5-28.8C34.7 215.8 45.4 208 57 208h1c3.3 0 6-2.7 6-6v-20c0-3.3-2.7-6-6-6-28.5 0-53.9 20.4-57.5 48.6C-3.9 258.8 22.7 288 56 288h40c0 52.2 25.4 98.1 64 127.3V496c0 8.8 7.2 16 16 16h64c8.8 0 16-7.2 16-16v-48h128v48c0 8.8 7.2 16 16 16h64c8.8 0 16-7.2 16-16v-80.7c11.8-8.9 22.3-19.4 31.3-31.3H560c8.8 0 16-7.2 16-16V240c0-8.8-7.2-16-16-16zm-128 64c-8.8 0-16-7.2-16-16s7.2-16 16-16 16 7.2 16 16-7.2 16-16 16zM256 96h128c5.4 0 10.7.4 15.9.8 0-.3.1-.5.1-.8 0-53-43-96-96-96s-96 43-96 96c0 2.1.5 4.1.6 6.2 15.2-3.9 31-6.2 47.4-6.2z"></path></svg>
                    <h5 class="pb-2">Auto debit payments</h5>
                    <p>Set up recurring deductions via wallets. Eliminate OTP input for subscription payment collections
                        resulting in faster collections.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bg-light-blue p-4 h-100">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="credit-card" class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path fill="currentColor" d="M0 432c0 26.5 21.5 48 48 48h480c26.5 0 48-21.5 48-48V256H0v176zm192-68c0-6.6 5.4-12 12-12h136c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12H204c-6.6 0-12-5.4-12-12v-40zm-128 0c0-6.6 5.4-12 12-12h72c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12H76c-6.6 0-12-5.4-12-12v-40zM576 80v48H0V80c0-26.5 21.5-48 48-48h480c26.5 0 48 21.5 48 48z"></path></svg>
                    <h5 class="pb-2">Collect payments online</h5>
                    <p>Provide multiple payment modes to your customers like UPI, Wallets, Credit, Debit Card, Net
                        Banking. Send and present online receipts to customers upon payments.</p>
                </div>
            </div>
        </div>
        <div class="row row-eq-height text-center pb-5">
            <div class="col-md-4">
                <div class="bg-light-blue p-4 h-100">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="retweet" class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><path fill="currentColor" d="M629.657 343.598L528.971 444.284c-9.373 9.372-24.568 9.372-33.941 0L394.343 343.598c-9.373-9.373-9.373-24.569 0-33.941l10.823-10.823c9.562-9.562 25.133-9.34 34.419.492L480 342.118V160H292.451a24.005 24.005 0 0 1-16.971-7.029l-16-16C244.361 121.851 255.069 96 276.451 96H520c13.255 0 24 10.745 24 24v222.118l40.416-42.792c9.285-9.831 24.856-10.054 34.419-.492l10.823 10.823c9.372 9.372 9.372 24.569-.001 33.941zm-265.138 15.431A23.999 23.999 0 0 0 347.548 352H160V169.881l40.416 42.792c9.286 9.831 24.856 10.054 34.419.491l10.822-10.822c9.373-9.373 9.373-24.569 0-33.941L144.971 67.716c-9.373-9.373-24.569-9.373-33.941 0L10.343 168.402c-9.373 9.373-9.373 24.569 0 33.941l10.822 10.822c9.562 9.562 25.133 9.34 34.419-.491L96 169.881V392c0 13.255 10.745 24 24 24h243.549c21.382 0 32.09-25.851 16.971-40.971l-16.001-16z"></path></svg>
                    <h5 class="pb-2">Recurring billing</h5>
                    <p>Set up subscription billing and your customers will receive your bills automatically at a set
                        frequency every month.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bg-light-blue p-4 h-100">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="percent" class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M112 224c61.9 0 112-50.1 112-112S173.9 0 112 0 0 50.1 0 112s50.1 112 112 112zm0-160c26.5 0 48 21.5 48 48s-21.5 48-48 48-48-21.5-48-48 21.5-48 48-48zm224 224c-61.9 0-112 50.1-112 112s50.1 112 112 112 112-50.1 112-112-50.1-112-112-112zm0 160c-26.5 0-48-21.5-48-48s21.5-48 48-48 48 21.5 48 48-21.5 48-48 48zM392.3.2l31.6-.1c19.4-.1 30.9 21.8 19.7 37.8L77.4 501.6a23.95 23.95 0 0 1-19.6 10.2l-33.4.1c-19.5 0-30.9-21.9-19.7-37.8l368-463.7C377.2 4 384.5.2 392.3.2z"></path></svg>
                    <h5 class="pb-2">GST filing for billing data</h5>
                    <p>File your monthly GST directly from your console. Automated reconciliation and preparation of GST
                        R1 & 3B from your collections data.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bg-light-blue p-4 h-100">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="users" class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><path fill="currentColor" d="M96 224c35.3 0 64-28.7 64-64s-28.7-64-64-64-64 28.7-64 64 28.7 64 64 64zm448 0c35.3 0 64-28.7 64-64s-28.7-64-64-64-64 28.7-64 64 28.7 64 64 64zm32 32h-64c-17.6 0-33.5 7.1-45.1 18.6 40.3 22.1 68.9 62 75.1 109.4h66c17.7 0 32-14.3 32-32v-32c0-35.3-28.7-64-64-64zm-256 0c61.9 0 112-50.1 112-112S381.9 32 320 32 208 82.1 208 144s50.1 112 112 112zm76.8 32h-8.3c-20.8 10-43.9 16-68.5 16s-47.6-6-68.5-16h-8.3C179.6 288 128 339.6 128 403.2V432c0 26.5 21.5 48 48 48h288c26.5 0 48-21.5 48-48v-28.8c0-63.6-51.6-115.2-115.2-115.2zm-223.7-13.4C161.5 263.1 145.6 256 128 256H64c-35.3 0-64 28.7-64 64v32c0 17.7 14.3 32 32 32h65.9c6.3-47.4 34.9-87.3 75.2-109.4z"></path></svg>
                    <h5 class="pb-2">Customer database</h5>
                    <p>Built-in customer database to manage all your customer data accurately. Ability to group
                        customers and notify them of your services.</p>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="jumbotron bg-secondary py-5" id="cta">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-12 mb-5">
                <h3 class="text-white">Over {{env('SWIPEZ_BIZ_NUM')}} businesses trust Swipez with their billing everyday!<br /><br />Register to get your free account</h3>
            </div>
            <div class="col-md-12"><a class="btn btn-primary btn-lg text-white"
                    href="{{ config('app.APP_URL') }}merchant/register">Start using for free</a>
            </div>
        </div>
    </div>
</section>
<section id="steps" class="jumbotron py-5 bg-transparent">
    <div class="container">
        <div class="zig">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-7 col-xl-7">
                    <h2 class="section_title">Advantages of using Swipez billing software for travel and tour operator</h2>
                    <p class="intro-description">Key advantages travel and tour using have seen using our <a href="{{ route('home.billing') }}">billing & invoicing</a> software</p>
                    <div class="step mt-5">
                        <div>
                            <div class="circle bg-primary">1</div>
                        </div>
                        <div>
                            <h3>Save time</h3>
                            <p>Saved over 100+ hours every month, earlier spent in creating bills via MS Word or Excel</p>
                        </div>
                    </div>
                    <div class="step">
                        <div>
                            <div class="circle bg-primary">2</div>
                        </div>
                        <div>
                            <h3>Online payments</h3>
                            <p>Over 40% of customers switched to more convenient online payments in the first billing cycle</p>
                        </div>
                    </div>
                    <div class="step">
                        <div>
                            <div class="circle bg-primary">3</div>
                        </div>
                        <div>
                            <h3>Cash collection reduced</h3>
                            <p>Cash collection reduced by 45%</p>
                        </div>
                    </div>
                    <div class="step">
                        <div>
                            <div class="circle bg-primary">4</div>
                        </div>
                        <div>
                            <h3>Faster payments </h3>
                            <p>Average days outstanding down by 50% - from 20 to 11 days</p>
                        </div>
                    </div>
                    <div class="step">
                        <div>
                            <div class="circle bg-primary">5</div>
                        </div>
                        <div>
                            <h3>Late payments reduced</h3>
                            <p>Late payments reduced by 18%</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-5 col-xl-5 pt-5 mt-5 d-none d-sm-flex">
                    <img class="zig-image img-fluid mx-auto" src="{!! asset('images/product/billing-software/industry/cable/advantages-of-cable-billing-software.svg') !!}" title="Companies with Swipez cable billing software" alt="Companies with swipez cable tv operator software have organized their billing" />
                </div>
            </div>
        </div>
    </div>
</section>
<section class="jumbotron bg-primary py-5" id="cta">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-12 mb-5">
                <h3 class="text-white">See why over {{env('SWIPEZ_BIZ_NUM')}} businesses trust Swipez.<br /><br />Try it free. No credit
                    card required.</h3>
            </div>
            <div class="col-md-12"><a class="btn btn-primary btn-lg text-white bg-tertiary" href="{{ config('app.APP_URL') }}merchant/register">Start Now</a>
            </div>
        </div>
    </div>
</section>
@endsection
