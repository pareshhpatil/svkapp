@extends('home.master')
@section('title', 'Free invoice software with payment collections and payment reminders')

@section('content')
<section class="jumbotron jumbotron-features bg-transparent py-5 d-lg-none" id="header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12 col-md-12 col-lg-6 col-xl-5 d-none d-lg-block">
                <h1>Event registration & online ticketing</h1>
                <p class="lead mb-2">Free event bookings with <span class="bold text-primary"><b>no platform
                            fees!</b></span> Create events, manage ticket sales, promote, and collate customer
                    information
                    for future events and more. Sell tickets faster via online payment modes like UPI, Wallets,
                    Credit, Debit Card or Net Banking – focus only on great event execution.</p>
                @include('home.product.web_register',['d_type' => "web"])
            </div>
            <div class="col-12 col-md-8 m-auto ml-lg-auto mr-lg-0 col-lg-6 pt-5 pt-lg-0 d-none d-lg-block">
                <img alt="Create events online and sell tickets" class="img-fluid"
                    src="{!! asset('images/product/online-event-registration.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none text-center">
                <img alt="Create events online and sell tickets" class="img-fluid"
                    src="{!! asset('images/product/online-event-registration.svg') !!}" />
                <h1>Event registration & online ticketing</h1>
                <p class="lead mb-2">Free event bookings with <span class="text-primary"><b>no platform fees!</b></span>
                    Create events, manage ticket sales, promote, and collate customer information
                    for future events and more. Sell tickets faster via online payment modes like UPI, Wallets,
                    Credit, Debit Card or Net Banking – focus only on great event execution.</p>
                @include('home.product.web_register',['d_type' => "mob"])
            </div>
            <!-- end -->
        </div>
    </div>
</section>
<section class="jumbotron jumbotron-features bg-transparent py-6 d-none d-lg-block" id="data-flow">
    <script src="/assets/global/plugins/jquery.min.js?id={{time()}}" type="text/javascript"></script>
    <script src="/js/data-flow/jquery.html-svg-connect.js?id={{time()}}"></script>

    <h1 class="pb-3 gray-700 text-center"><span class="highlighter">Free</span> event management software</h1>
    <center>
        <p class="pb-3 lead gray-700 text-center" style="width: 620px;">Create events, manage ticket sales, promote, and
            collate customer information for future events and more. Sell tickets faster via online payment modes like
            UPI, Wallets, Credit, Debit Card or Net Banking – focus only on great event execution.</p>
    </center>
    @include('home.data_flow');
</section>
<section class="jumbotron py-5 bg-secondary text-white" id="cta">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-12">
                <h2>Ideal for event organisers and planners, for small, large or complex events.</h2>
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
                <img alt="Create your event page online" class="img-fluid"
                    src="{!! asset('images/product/event-registration/features/online-event-creator.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Create your event page online" class="img-fluid"
                    src="{!! asset('images/product/event-registration/features/online-event-creator.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 m-md-auto d-none d-lg-block">
                <h2><strong>Build your events page in a minute</strong></h2>
                <p class="lead">Create a beautiful event listing page with built-in online payments. Promote and track
                    your event from any device.</p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>Build your events page in a minute</strong></h2>
                <p class="lead">Create a beautiful event listing page with built-in online payments. Promote and track
                    your event from any device.</p>
            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5 pb-md-5">
            <div class="col-4 col-md-5 m-md-auto order-md-5 d-none d-lg-block">
                <img alt="Create event page online with different packages" class="img-fluid"
                    src="{!! asset('images/product/event-registration/features/create-event-with-packages.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Create event page online with different packages" class="img-fluid"
                    src="{!! asset('images/product/event-registration/features/create-event-with-packages.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 d-none d-lg-block">
                <h2><strong>Design events with different packages</strong></h2>
                <p class="lead">Create different package options for your events with ease. Perk up your lunch
                    event, or add early bird discounts for music fest bookings, take pre-orders and bundle a lot
                    more.</p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>Design events with different packages</strong></h2>
                <p class="lead">Create different package options for your events with ease. Perk up your lunch
                    event, or add early bird discounts for music fest bookings, take pre-orders and bundle a lot
                    more.</p>
            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5">
            <div class="col-4 col-md-5 d-none d-lg-block">
                <img alt="Fast and orderly entry to event with QR reader" class="img-fluid"
                    src="{!! asset('images/product/event-registration/features/qr-code-based-event-entry.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Fast and orderly entry to event with QR reader" class="img-fluid"
                    src="{!! asset('images/product/event-registration/features/qr-code-based-event-entry.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 m-md-auto d-none d-lg-block">
                <h2><strong>Manage event entry with incredible ease</strong></h2>
                <p class="lead">Manage online or offline bookings with ease. Use a QR code reader to manage your box
                    office entries with ease and even free up seats against cancellations.</p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>Manage event entry with incredible ease</strong></h2>
                <p class="lead">Manage online or offline bookings with ease. Use a QR code reader to manage your box
                    office entries with ease and even free up seats against cancellations.</p>
            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5 pb-md-5">
            <div class="col-4 col-md-5 m-md-auto order-md-5 d-none d-lg-block">
                <img alt="Promote event via SMS messages and social networks" class="img-fluid"
                    src="{!! asset('images/product/event-registration/features/promote-events-online.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Promote event via SMS messages and social networks" class="img-fluid"
                    src="{!! asset('images/product/event-registration/features/promote-events-online.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 d-none d-lg-block">
                <h2><strong>Promote your events</strong></h2>
                <p class="lead">Patrons from your earlier events are added to your customer database automatically.
                    Notify them via SMS on upcoming events with ease. You are always in total control of your
                    customer data to help you re-target users on social platforms like Facebook, Instagram, etc.</p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>Promote your events</strong></h2>
                <p class="lead">Patrons from your earlier events are added to your customer database automatically.
                    Notify them via SMS on upcoming events with ease. You are always in total control of your
                    customer data to help you re-target users on social platforms like Facebook, Instagram, etc.</p>
            </div>
            <!-- end -->
        </div>

        <div class="row text-left align-items-center pt-5">
            <div class="col-4 col-md-5 d-none d-lg-block">
                <img alt="Collect payments in any currency" class="img-fluid"
                    src="{!! asset('images/product/event-registration/features/international-payment-collections.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Collect payments in any currency" class="img-fluid"
                    src="{!! asset('images/product/event-registration/features/international-payment-collections.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 m-md-auto d-none d-lg-block">
                <h2><strong>Host global events</strong></h2>
                <p class="lead">Organize events for a worldwide audience with ease.
                     Create, manage & monitor events for domestic and international audiences seamlessly with payment options in 50+ international currencies.</p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>Host global events</strong></h2>
                <p class="lead">Organize events for a worldwide audience with ease.
                     Create, manage & monitor events for domestic and international audiences seamlessly with payment options in 50+ international currencies.</p>
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
                <a class="btn btn-lg text-white bg-tertiary" href="{{ config('app.APP_URL') }}merchant/register">Start
                    Now</a>
                <a class="btn btn-lg text-white bg-primary" href="{{ route('home.pricing.event') }}">See pricing
                    plans</a>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-sm-none">
                <div class="row justify-content-center">
                    <a class="btn btn-lg text-white bg-tertiary mr-1"
                        href="{{ config('app.APP_URL') }}merchant/register">Start Now</a>
                    <a class="btn btn-outline-primary btn-lg text-white bg-primary"
                        href="{{ route('home.pricing.event') }}">Pricing plans</a>
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
                <h2 class="display-4 text-white">Online event bookings made simple</h2>
            </div>
        </div><br />
        <div class="row row-eq-height text-center">
            <div class="col-md-3">
                <div class="bg-white p-4 h-100">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check-square"
                        class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 448 512">
                        <path fill="currentColor"
                            d="M400 480H48c-26.51 0-48-21.49-48-48V80c0-26.51 21.49-48 48-48h352c26.51 0 48 21.49 48 48v352c0 26.51-21.49 48-48 48zm-204.686-98.059l184-184c6.248-6.248 6.248-16.379 0-22.627l-22.627-22.627c-6.248-6.248-16.379-6.249-22.628 0L184 302.745l-70.059-70.059c-6.248-6.248-16.379-6.248-22.628 0l-22.627 22.627c-6.248 6.248-6.248 16.379 0 22.627l104 104c6.249 6.25 16.379 6.25 22.628.001z">
                        </path>
                    </svg>
                    <h3 class="text-secondary pb-2">Well-organised bookings</h3>
                    <p>Set up seats and packages easily. People are intimated upon availability and cancellation of
                        seats.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="bg-white p-4 h-100">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="credit-card"
                        class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 576 512">
                        <path fill="currentColor"
                            d="M0 432c0 26.5 21.5 48 48 48h480c26.5 0 48-21.5 48-48V256H0v176zm192-68c0-6.6 5.4-12 12-12h136c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12H204c-6.6 0-12-5.4-12-12v-40zm-128 0c0-6.6 5.4-12 12-12h72c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12H76c-6.6 0-12-5.4-12-12v-40zM576 80v48H0V80c0-26.5 21.5-48 48-48h480c26.5 0 48 21.5 48 48z">
                        </path>
                    </svg>
                    <h3 class="text-secondary pb-2">Swift and easy payments</h3>
                    <p>Advance payments. Payments at the venue. Sync with offline bookings as well.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="bg-white p-4 h-100">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="thumbs-up"
                        class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 512 512">
                        <path fill="currentColor"
                            d="M104 224H24c-13.255 0-24 10.745-24 24v240c0 13.255 10.745 24 24 24h80c13.255 0 24-10.745 24-24V248c0-13.255-10.745-24-24-24zM64 472c-13.255 0-24-10.745-24-24s10.745-24 24-24 24 10.745 24 24-10.745 24-24 24zM384 81.452c0 42.416-25.97 66.208-33.277 94.548h101.723c33.397 0 59.397 27.746 59.553 58.098.084 17.938-7.546 37.249-19.439 49.197l-.11.11c9.836 23.337 8.237 56.037-9.308 79.469 8.681 25.895-.069 57.704-16.382 74.757 4.298 17.598 2.244 32.575-6.148 44.632C440.202 511.587 389.616 512 346.839 512l-2.845-.001c-48.287-.017-87.806-17.598-119.56-31.725-15.957-7.099-36.821-15.887-52.651-16.178-6.54-.12-11.783-5.457-11.783-11.998v-213.77c0-3.2 1.282-6.271 3.558-8.521 39.614-39.144 56.648-80.587 89.117-113.111 14.804-14.832 20.188-37.236 25.393-58.902C282.515 39.293 291.817 0 312 0c24 0 72 8 72 81.452z">
                        </path>
                    </svg>
                    <h3 class="text-secondary pb-2">Easy event promotion</h3>
                    <p>Publish your event page within minutes. Add your packages & T&C. Share your event landing page on
                        social media</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="bg-white p-4 h-100">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="qrcode"
                        class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 448 512">
                        <path fill="currentColor"
                            d="M0 224h192V32H0v192zM64 96h64v64H64V96zm192-64v192h192V32H256zm128 128h-64V96h64v64zM0 480h192V288H0v192zm64-128h64v64H64v-64zm352-64h32v128h-96v-32h-32v96h-64V288h96v32h64v-32zm0 160h32v32h-32v-32zm-64 0h32v32h-32v-32z">
                        </path>
                    </svg>
                    <h3 class="text-secondary pb-2">QR code ready tickets</h3>
                    <p>Customers receive QR code post booking. Use our QR code reader to manage box entries smoothly.
                    </p>
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
                    event registration software.</p>
            </div>
        </div>
        <div class="row align-items-center justify-content-center">
            <div class="col-lg-9 col-xl-6">
                <div class="card p-5">
                    <div class="row">
                        <div class="col-8 col-sm-6 col-md-4 col-xl-3 ml-auto mr-auto">
                            <img alt="Event registration client picture" class="img-fluid rounded"
                                src="{!! asset('images/product/event-registration/swipez-client1.jpg') !!}">
                        </div>
                        <div class="col-md-8 mt-4 mt-md-0">
                            <p>"We have hosted multiple events with no issues over last couple of years. The support
                                team at Swipez have been great in responding quickly and solving our queries
                                effectively."</p>
                            <p class="h3 mt-4 mt-xl-5">
                                <strong>Avinash Agarwal</strong>
                            </p>
                            <p>
                                <em>MD, Classic Rock Coffee Co (India)</em>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-9 col-xl-6 mt-4 mt-xl-0">
                <div class="card p-5">
                    <div class="row">
                        <div class="col-8 col-sm-6 col-md-4 col-xl-3 ml-auto mr-auto">
                            <img alt="Event registration client picture" class="img-fluid rounded"
                                src="{!! asset('images/product/event-registration/swipez-client2.jpg') !!}">
                        </div>
                        <div class="col-md-8 mt-4 mt-md-0">
                            <p>"From creating events to collecting ticket costs online it has been a breeze. The
                                flexibility provided
                                by the event creation tool is perfect, it has been able to meet our every need."</p>
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
                <h3>Power your events with a comprehensive events solution</h3>
            </div>
            <div class="col-md-12 d-none d-sm-block">
                <a class="btn btn-primary btn-lg bg-tertiary" href="{{ config('app.APP_URL') }}merchant/register">Start
                    Now</a>
                <a class="btn btn-lg text-white bg-secondary" href="{{ route('home.pricing.event') }}">See pricing
                    plans</a>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-sm-none">
                <div class="row justify-content-center">
                    <a class="btn btn-lg text-white bg-tertiary mr-1"
                        href="{{ config('app.APP_URL') }}merchant/register">Start Now</a>
                    <a class="btn btn-lg text-white bg-secondary" href="{{ route('home.pricing.event') }}">Pricing
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
                            Can I host a multi day event on the Swipez event registration platform?
                        </div>
                        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne"
                            data-parent="#accordion" itemscope itemprop="acceptedAnswer"
                            itemtype="http://schema.org/Answer" itemscope itemprop="acceptedAnswer"
                            itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, the Swipez event registration platform lets you create and schedule multi day
                                events with ease.
                            </div>
                        </div>
                    </div>
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingTwo">
                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            Can I have multiple packages for my event with Swipez event registration?
                        </div>
                        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion"
                            itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, you can create multiple event ticketing packages with different pricing.
                            </div>
                        </div>
                    </div>
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingThree">
                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            Do I get charged any percentage other than transaction rates on selling tickets through
                            Swipez event Platform?
                        </div>
                        <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion"
                            itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                No, there will be no extra charges or commissions except per transaction payment gateway
                                charges.
                            </div>
                        </div>
                    </div>
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingFour">
                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                            Can I make offline booking using the Swipez online event registration platform?
                        </div>
                        <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion"
                            itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, you can easily make offline booking using the Swipez free event ticketing platform.
                            </div>
                        </div>
                    </div>
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingFive">
                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                            Can I see the contact details of the people who have bought tickets for my event ?
                        </div>
                        <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordion"
                            itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, our online event registration software allows you to see the captured details of
                                people who have bought tickets for all your events hosted on Swipez.
                            </div>
                        </div>
                    </div>
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingSix">
                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                            Can I get a consolidated report of all the people attending my event using Swipez free event
                            ticketing system?
                        </div>
                        <div id="collapseSix" class="collapse" aria-labelledby="headingSix" data-parent="#accordion"
                            itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, Swipez free event registration software has integrated tools to export information
                                about attendees either in PDF or excel sheet format.
                            </div>
                        </div>
                    </div>
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingSeven">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
                            When does all the money that is collected for my event get settled to my account?
                        </div>
                        <div id="collapseSeven" class="collapse" aria-labelledby="headingSeven" data-parent="#accordion"
                            itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Swipez initiates the settlement for all ticket sales 5 days after the event.
                            </div>
                        </div>
                    </div>
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingSeven1">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapseSeven1" aria-expanded="false" aria-controls="collapseSeven1">
                            Will the latest exchange rates be used to convert the various international currencies?
                        </div>
                        <div id="collapseSeven1" class="collapse" aria-labelledby="headingSeven" data-parent="#accordion"
                            itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                            Yes, all foreign currencies will be automatically converted to INR at the current exchange rate.
                            </div>
                        </div>
                    </div>
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingEight">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapseEight" aria-expanded="false" aria-controls="collapseEight">
                            How can I handle box office entry during the day of my event?
                        </div>
                        <div id="collapseEight" class="collapse" aria-labelledby="headingEight" data-parent="#accordion"
                            itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                You can use our QR enabled ticketing and QR reader to manage your box office entry on
                                the day of the event
                            </div>
                        </div>
                    </div>
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingNine">
                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapseNine" aria-expanded="false" aria-controls="collapseNine">
                            Can I give a discount to some of the customers making a booking for my event?
                        </div>
                        <div id="collapseNine" class="collapse" aria-labelledby="headingNine" data-parent="#accordion"
                            itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, the Swipez platform gives you a host of options to offer various discounts, and
                                offers to your customers.
                            </div>
                        </div>
                    </div>
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingTen">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapseTen" aria-expanded="false" aria-controls="collapseTen">
                            Can I create a free event on the Swipez events platform?
                        </div>
                        <div id="collapseTen" class="collapse" aria-labelledby="headingTen" data-parent="#accordion"
                            itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, for creating free events you can use the Swipez free plan where there will be 0
                                charges incurred for taking registrations and bookings.
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
    var intcounter=0;
    var istimer=false;
    var titles1 = ["Event / Booking calender"];

</script>

@endsection
