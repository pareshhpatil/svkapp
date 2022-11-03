@extends('home.master')
@section('title', 'Free time slot management for venues and sports facilities')

@section('content')
<section class="jumbotron jumbotron-features bg-transparent py-5" id="header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12 col-md-12 col-lg-6 col-xl-5">
                <h1>Billing & venue booking software for housing societies</h1>
                <p class="lead mb-5">Organizing amenity booking, memberships and payment collections for housing
                    societies.</p>
                @include('home.product.web_register',['d_type' => "web"])
            </div>
            <div class="col-12 col-md-8 m-auto ml-lg-auto mr-lg-0 col-lg-6 pt-5 pt-lg-0">
                <img alt="Billing & venue booking software for housing societies" class="img-fluid"
                    src="{!! asset('images/product/venue-booking-software/industry/housing-society.svg') !!}" />
            </div>
        </div>
    </div>
</section>
<section class="jumbotron py-5 bg-secondary text-white" id="cta">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-12">
                <h2>Billing & club house management software for housing societies of all sizes.</h2>
            </div>
        </div>
    </div>
</section>
<section class="jumbotron py-5 bg-transparent" id="steps">
    <div class="container">
        <h2 class="text-center pb-5">Benefits of using Swipez housing society <a
                href="{{ route('home.billing') }}">billing software</a></h2>
        <p class="lead text-center">Swipez society management system offers you a hassle-free platform to transform your
            society, making it highly functional and easily manageable</p>
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
                            <h2 class="card-title">Efficient use of society amenities using the Swipez housing society
                                management software
                            </h2>
                            <p class="card-text">For housing societies that provide numerous amenities to its residents,
                                efficiently organizing bookings and memberships is of prime importance. Especially since
                                clashes caused by overlapping bookings of amenities is a genuine concern for resident
                                satisfaction. Beyond this, a society has to control usage of facilities to avoid
                                outsiders from using their facilities and keep a tab on usage details. Using the Swipez
                                society software venue booking calendar, housing societies can organize clubhouse
                                amenities with ease no matter the size of the society. Create calendars for all the
                                amenities they offer, collect membership fees and slot bookings online from residents.
                                Using automation, human intervention and booking conflicts caused by issuing slots
                                manually are eliminated. </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row no-gutters pb-5">
                <div class="col-sm py-2">
                    <div class="card d-none d-sm-flex card-border-none">
                        <div class="card-body">
                            <h2 class="card-title">Manage amenity memberships with Swipez’s free society amenities
                                booking system </h2>
                            <p class="card-text">The Swipez housing society management software helps reduce the time
                                and effort spent by residents to enjoy their own amenities and reduce the burden on
                                society management staff. List all your available amenities, collect and track
                                membership fees from flat owners and residents on rent through a centralized database.
                                Set up different fees for residents on rent and society members.</p>
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
                            <h2 class="card-title">Manage amenity memberships with Swipez’s free society amenities
                                booking system </h2>
                            <p class="card-text">The Swipez housing society management software helps reduce the time
                                and effort spent by residents to enjoy their own amenities and reduce the burden on
                                society management staff. List all your available amenities, collect and track
                                membership fees from flat owners and residents on rent through a centralized database.
                                Set up different fees for residents on rent and society members.</p>
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
                            <h2 class="card-title">Organising events and celebrations with our hassle-free event
                                management software for housing society</h2>
                            <p class="card-text">Hosting housing society events is typically a painstaking task for
                                society managers. From getting an accurate headcount to collecting funds are all
                                resource heavy tasks for usually understaffed society management. The Swipez event
                                booking system for housing society lets you create intuitive event pages, where you can
                                display all the information about your event, collect registrations and even collect
                                online payment for tickets. Simplifying tedious tasks that are a huge strain on time and
                                resources.</p>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row no-gutters pb-5">
                <div class="col-sm py-2">
                    <div class="card d-none d-sm-flex card-border-none">
                        <div class="card-body">
                            <h2 class="card-title">Society broadcasts and communication</h2>
                            <p class="card-text">Society members often live their lives on the go, with all this they
                                seldom have time to look at important society information posted on the building
                                bulletin boards. The Swipez housing society management software uses SMS notifications
                                to deliver important news to its member, all which can be done within a few minutes.
                            </p>
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
                            <h2 class="card-title">Society broadcasts and communication</h2>
                            <p class="card-text">Society members often live their lives on the go, with all this they
                                seldom have time to look at important society information posted on the building
                                bulletin boards. The Swipez housing society management software uses SMS notifications
                                to deliver important news to its member, all which can be done within a few minutes.
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
                            <h2 class="card-title">Timely collection of maintenance fees through the Swipez housing
                                society management software </h2>
                            <p class="card-text">Society management has numerous flats apartments to manage in a housing
                                society. Managing multiple tasks such as sending payments invoices to society members,
                                tracking housing society monthly maintenance collection, sending reminders to society
                                members for late payments etc. All these are daunting tasks. These tasks take a toll on
                                your precious time which can be used to manage other functions. The Swipez society
                                billing software streamlines all these processes for you, by sending out maintenance fee
                                invoices to members by email and SMS, automated payment reminders for delayed payments,
                                rebates for early payments and late fees for defaulters. </p>
                        </div>
                    </div>
                </div>

            </div>
            <div class="row no-gutters pb-5">
                <div class="col-sm py-2">
                    <div class="card d-none d-sm-flex card-border-none">
                        <div class="card-body">
                            <h2 class="card-title">Accurate centralised reporting of society finances using our 100%
                                reliable housing society management software </h2>
                            <p class="card-text">The Swipez housing society software allows society management a quick
                                and convenient view into maintenance collections and dues. They can track individual
                                members payments through a consolidated ledger statement. Thus reducing inefficiencies
                                in the billing cycle. These reports can be viewed by management to get a clear and up to
                                date report on timely payments and defaulters. </p>
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
                            <h2 class="card-title">Accurate centralised reporting of society finances using our 100%
                                reliable housing society management software </h2>
                            <p class="card-text">The Swipez housing society software allows society management a quick
                                and convenient view into maintenance collections and dues. They can track individual
                                members payments through a consolidated ledger statement. Thus reducing inefficiencies
                                in the billing cycle. These reports can be viewed by management to get a clear and up to
                                date report on timely payments and defaulters. </p>
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
                                <b>Download housing society invoice format</b>
                            </h2>
                            <ul class="list-unstyled">
                                <li class="lead text-white">
                                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check"
                                        class="h-6 pr-1" role="img" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 512 512">
                                        <path fill="currentColor"
                                            d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                                        </path>
                                    </svg>
                                    Create your invoice in your browser
                                </li>
                                <li class="lead text-white">
                                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check"
                                        class="h-6 pr-1" role="img" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 512 512">
                                        <path fill="currentColor"
                                            d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                                        </path>
                                    </svg>
                                    Download free invoice formats</li>
                                <li class="lead text-white">
                                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check"
                                        class="h-6 pr-1" role="img" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 512 512">
                                        <path fill="currentColor"
                                            d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                                        </path>
                                    </svg>
                                    Invoice as per housing society formats</li>
                                <li class="lead text-white">
                                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check"
                                        class="h-6 pr-1" role="img" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 512 512">
                                        <path fill="currentColor"
                                            d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                                        </path>
                                    </svg>
                                    Great design to impress your clients!</li>
                            </ul>
                            <a class="btn btn-primary big-text" href="{{ route('home.housinginvoiceformat') }}">Download
                                housing society invoice format</a>
                        </div>
                        <div class="col-md-6 d-none d-lg-block">
                            <img alt="Download an invoice as per your business requirement"
                                src="{!! asset('images/home/download-invoice-format.svg') !!}" />
                        </div>
                    </div>
                    <!-- for iPad and mobile -->
                    <div class="row text-white">
                        <div class="col-md-6 d-lg-none mb-3">
                            <img alt="Download an invoice as per your business requirement"
                                src="{!! asset('images/home/download-invoice-format.svg') !!}" />
                        </div>
                        <div class="col-md-6 d-lg-none">
                            <h2 class="py-3 text-center">
                                <b>Download housing society invoice format</b>
                            </h2>
                            <ul class="list-unstyled pb-2">
                                <li class="lead text-white">
                                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check"
                                        class="h-6 pr-1" role="img" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 512 512">
                                        <path fill="currentColor"
                                            d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                                        </path>
                                    </svg>
                                    Create your invoice in your browser</li>
                                <li class="lead text-white">
                                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check"
                                        class="h-6 pr-1" role="img" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 512 512">
                                        <path fill="currentColor"
                                            d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                                        </path>
                                    </svg>
                                    Download free invoice formats</li>
                                <li class="lead text-white">
                                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check"
                                        class="h-6 pr-1" role="img" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 512 512">
                                        <path fill="currentColor"
                                            d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                                        </path>
                                    </svg>
                                    Invoice as per housing society formats</li>
                                <li class="lead text-white">
                                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check"
                                        class="h-6 pr-1" role="img" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 512 512">
                                        <path fill="currentColor"
                                            d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                                        </path>
                                    </svg>
                                    Clean design to impress your clients!</li>
                            </ul>
                            <a class="btn btn-primary big-text" href="{{ route('home.ispinvoiceformat') }}">Download
                                format</a>
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
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="calendar-alt"
                        class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 448 512">
                        <path fill="currentColor"
                            d="M0 464c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48V192H0v272zm320-196c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12h-40c-6.6 0-12-5.4-12-12v-40zm0 128c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12h-40c-6.6 0-12-5.4-12-12v-40zM192 268c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12h-40c-6.6 0-12-5.4-12-12v-40zm0 128c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12h-40c-6.6 0-12-5.4-12-12v-40zM64 268c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12H76c-6.6 0-12-5.4-12-12v-40zm0 128c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12H76c-6.6 0-12-5.4-12-12v-40zM400 64h-48V16c0-8.8-7.2-16-16-16h-32c-8.8 0-16 7.2-16 16v48H160V16c0-8.8-7.2-16-16-16h-32c-8.8 0-16 7.2-16 16v48H48C21.5 64 0 85.5 0 112v48h448v-48c0-26.5-21.5-48-48-48z">
                        </path>
                    </svg>
                    <h5 class="pb-2">Publish venue availability calendar</h5>
                    <p>Set up calendar for all your facilities along with available time slots. Allow residents to book
                        as
                        per their convenience.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bg-light-blue p-4 h-100">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="calendar-check"
                        class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 448 512">
                        <path fill="currentColor"
                            d="M436 160H12c-6.627 0-12-5.373-12-12v-36c0-26.51 21.49-48 48-48h48V12c0-6.627 5.373-12 12-12h40c6.627 0 12 5.373 12 12v52h128V12c0-6.627 5.373-12 12-12h40c6.627 0 12 5.373 12 12v52h48c26.51 0 48 21.49 48 48v36c0 6.627-5.373 12-12 12zM12 192h424c6.627 0 12 5.373 12 12v260c0 26.51-21.49 48-48 48H48c-26.51 0-48-21.49-48-48V204c0-6.627 5.373-12 12-12zm333.296 95.947l-28.169-28.398c-4.667-4.705-12.265-4.736-16.97-.068L194.12 364.665l-45.98-46.352c-4.667-4.705-12.266-4.736-16.971-.068l-28.397 28.17c-4.705 4.667-4.736 12.265-.068 16.97l82.601 83.269c4.667 4.705 12.265 4.736 16.97.068l142.953-141.805c4.705-4.667 4.736-12.265.068-16.97z">
                        </path>
                    </svg>
                    <h5 class="pb-2">Ensure maximum utilization</h5>
                    <p>Publish your booking calendar online for residents to pick a time for themselves. Free up slots
                        against cancellations.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bg-light-blue p-4 h-100">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="user-plus"
                        class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 640 512">
                        <path fill="currentColor"
                            d="M624 208h-64v-64c0-8.8-7.2-16-16-16h-32c-8.8 0-16 7.2-16 16v64h-64c-8.8 0-16 7.2-16 16v32c0 8.8 7.2 16 16 16h64v64c0 8.8 7.2 16 16 16h32c8.8 0 16-7.2 16-16v-64h64c8.8 0 16-7.2 16-16v-32c0-8.8-7.2-16-16-16zm-400 48c70.7 0 128-57.3 128-128S294.7 0 224 0 96 57.3 96 128s57.3 128 128 128zm89.6 32h-16.7c-22.2 10.2-46.9 16-72.9 16s-50.6-5.8-72.9-16h-16.7C60.2 288 0 348.2 0 422.4V464c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48v-41.6c0-74.2-60.2-134.4-134.4-134.4z">
                        </path>
                    </svg>
                    <h5 class="pb-2">Manage memberships</h5>
                    <p>Set up memberships with different packages for owners and residents on rent. Set up reminders and
                        collect renewal fees online.</p>
                </div>
            </div>
        </div>
        <div class="row row-eq-height text-center pb-5">
            <div class="col-md-4">
                <div class="bg-light-blue p-4 h-100">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="qrcode"
                        class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 448 512">
                        <path fill="currentColor"
                            d="M0 224h192V32H0v192zM64 96h64v64H64V96zm192-64v192h192V32H256zm128 128h-64V96h64v64zM0 480h192V288H0v192zm64-128h64v64H64v-64zm352-64h32v128h-96v-32h-32v96h-64V288h96v32h64v-32zm0 160h32v32h-32v-32zm-64 0h32v32h-32v-32z">
                        </path>
                    </svg>
                    <h5 class="pb-2">QR code based access</h5>
                    <p>Residents receive QR code post booking. Use our QR code reader to manage entry to your facility.
                        Prevent
                        unauthorized access.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bg-light-blue p-4 h-100">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="credit-card"
                        class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 576 512">
                        <path fill="currentColor"
                            d="M0 432c0 26.5 21.5 48 48 48h480c26.5 0 48-21.5 48-48V256H0v176zm192-68c0-6.6 5.4-12 12-12h136c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12H204c-6.6 0-12-5.4-12-12v-40zm-128 0c0-6.6 5.4-12 12-12h72c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12H76c-6.6 0-12-5.4-12-12v-40zM576 80v48H0V80c0-26.5 21.5-48 48-48h480c26.5 0 48 21.5 48 48z">
                        </path>
                    </svg>
                    <h5 class="pb-2">Swift and easy payments</h5>
                    <p>Collect advance payments with ease. Sync with offline bookings via your own admin dashboard.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bg-light-blue p-4 h-100">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="users"
                        class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 640 512">
                        <path fill="currentColor"
                            d="M96 224c35.3 0 64-28.7 64-64s-28.7-64-64-64-64 28.7-64 64 28.7 64 64 64zm448 0c35.3 0 64-28.7 64-64s-28.7-64-64-64-64 28.7-64 64 28.7 64 64 64zm32 32h-64c-17.6 0-33.5 7.1-45.1 18.6 40.3 22.1 68.9 62 75.1 109.4h66c17.7 0 32-14.3 32-32v-32c0-35.3-28.7-64-64-64zm-256 0c61.9 0 112-50.1 112-112S381.9 32 320 32 208 82.1 208 144s50.1 112 112 112zm76.8 32h-8.3c-20.8 10-43.9 16-68.5 16s-47.6-6-68.5-16h-8.3C179.6 288 128 339.6 128 403.2V432c0 26.5 21.5 48 48 48h288c26.5 0 48-21.5 48-48v-28.8c0-63.6-51.6-115.2-115.2-115.2zm-223.7-13.4C161.5 263.1 145.6 256 128 256H64c-35.3 0-64 28.7-64 64v32c0 17.7 14.3 32 32 32h65.9c6.3-47.4 34.9-87.3 75.2-109.4z">
                        </path>
                    </svg>
                    <h5 class="pb-2">Create your customer database</h5>
                    <p>Collate resident data from your bookings automatically. Use collated resident data to notify
                        residents for future events & bookings.</p>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="jumbotron bg-secondary py-5" id="cta">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-12 mb-5">
                <h3 class="text-white">Over {{env('SWIPEZ_BIZ_NUM')}} businesses trust Swipez with their billing
                    everyday!<br /><br />Register to get your free account</h3>
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
                    <h2 class="section_title">Advantages of using Swipez billing software for housing societies</h2>
                    <p class="intro-description">Key advantages societies gain using <a
                            href="{{ route('home.billing') }}">software for billing</a></p>
                    <div class="step mt-5">
                        <div>
                            <div class="circle bg-primary">1</div>
                        </div>
                        <div>
                            <h3>Double booking</h3>
                            <p>Double booking of amenities by residents completely eliminated</p>
                        </div>
                    </div>
                    <div class="step">
                        <div>
                            <div class="circle bg-primary">2</div>
                        </div>
                        <div>
                            <h3>Late payments reduced</h3>
                            <p>Late membership fee payments reduced by 65%</p>
                        </div>
                    </div>
                    <div class="step">
                        <div>
                            <div class="circle bg-primary">3</div>
                        </div>
                        <div>
                            <h3>Reutilization</h3>
                            <p>Reutilization of cancelled bookings improved by 70%</p>
                        </div>
                    </div>
                    <div class="step">
                        <div>
                            <div class="circle bg-primary">4</div>
                        </div>
                        <div>
                            <h3>Booking increase</h3>
                            <p>Special instructor led events saw an increase of 40% in booking</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-5 col-xl-5 pt-5 mt-5 d-none d-sm-flex">
                    <img class="zig-image img-fluid mx-auto"
                        src="{!! asset('images/product/billing-software/industry/cable/advantages-of-cable-billing-software.svg') !!}"
                        title="Companies with Swipez cable billing software"
                        alt="Companies with swipez cable tv operator software have organized their billing" />
                </div>
            </div>
        </div>
    </div>
</section>
<section class="jumbotron bg-primary py-5" id="cta">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-12 mb-5">
                <h3 class="text-white">See why over {{env('SWIPEZ_BIZ_NUM')}} businesses trust Swipez.<br /><br />Try it
                    free. No credit
                    card required.</h3>
            </div>
            <div class="col-md-12"><a class="btn btn-primary btn-lg text-white bg-tertiary"
                    href="{{ config('app.APP_URL') }}merchant/register">Start Now</a>
            </div>
        </div>
    </div>
</section>
@endsection
