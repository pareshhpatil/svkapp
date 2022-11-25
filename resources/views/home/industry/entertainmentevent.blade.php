@extends('home.master')
@section('title', 'Free event registration and online ticketing payment collections and QR code tickets')

@section('content')
<section class="jumbotron jumbotron-features bg-transparent py-5" id="header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12 col-md-12 col-lg-6 col-xl-5">
                <h1>Event registration and ticketing for entertainment events</h1>
                <p class="lead mb-5">Create modern event landing pages, allow customers to book tickets and make
                    payments online.</p>
                @include('home.product.web_register',['d_type' => "web"])
            </div>
            <div class="col-12 col-md-8 m-auto ml-lg-auto mr-lg-0 col-lg-6 pt-5 pt-lg-0">
                <img alt="Event registration and ticketing for entertainment events" class="img-fluid"
                     src="{!! asset('images/product/event-registration/industry/entertainment.svg') !!}" />
            </div>
        </div>
    </div>
</section>
<section class="jumbotron py-5 bg-secondary text-white" id="cta">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-12">
                <h2>Event registration and ticketing for entertainment events of all sizes.</h2>
            </div>
        </div>
    </div>
</section>
<section class="jumbotron py-5 bg-transparent" id="steps">
    <div class="container">
        <h2 class="text-center pb-5">Benefits of using the Swipez <a href="{{ route('home.event') }}">event registration software</a> for entertainment events</h2>
        <p class="lead">Swipez entertainment event registration software is the perfect platform for hosting entertainment events which are a complex and time consuming undertaking. </p>
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
                            <h2 class="card-title">Offer multiple ticket categories using Swipez customised entertainment event management software
                            </h2>
                            <p class="card-text">The Swipez entertainment event management software lets you set up multiple packages with pricing tiers as per your events requirement. Giving patrons to the same event a customised and unique experience and giving you complete control to host your event as you envisioned it.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row no-gutters pb-5">
                <div class="col-sm py-2">
                    <div class="card d-none d-sm-flex card-border-none">
                        <div class="card-body">
                            <h2 class="card-title">No revenue share for tickets sold by ticketing through Swipez’s event registration software </h2>
                            <p class="card-text">Event organisers envision and execute their ideas to create beautiful live experiences for their customers, it’s only fair that they get to maximise their revenue for a successful event. The Swipez online entertainment event management software takes no revenue share on tickets sold for an event and charges one of the lowest online transaction rates in the industry.</p>
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
                            <h2 class="card-title">No revenue share for tickets sold by ticketing through Swipez’s event registration software </h2>
                            <p class="card-text">Event organisers envision and execute their ideas to create beautiful live experiences for their customers, it’s only fair that they get to maximise their revenue for a successful event. The Swipez online entertainment event management software takes no revenue share on tickets sold for an event and charges one of the lowest online transaction rates in the industry.</p>
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
                            <h2 class="card-title">Easy box office operations on D day using our QR code based event registration software for entertainment events
                            </h2>
                            <p class="card-text">Flawless execution is key for a successful event. Making sure patrons don't queue up in lines and have a smooth box office experience sets the tone for any event. The Swipez online entertainment event management software’s QR code enabled tickets make box office entry a breeze. Use our QR code scanner to mark individual entrants or all entrants from one ticket and let the focus be on enjoying the experience.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row no-gutters pb-5">
                <div class="col-sm py-2">
                    <div class="card d-none d-sm-flex card-border-none">
                        <div class="card-body">
                            <h2 class="card-title">Own your customer relationships with our free event reservation and registration system</h2>
                            <p class="card-text">Building relationships with customers and keeping them aware of upcoming events is key to running successful events. The Swipez event registration software for entertainment events
                                gives you free access to all the booking information of your patrons, use customer data from all your past events to target promotion of future events.</p>
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
                            <img src="{!! asset('images/product/billing-software/industry/cable/subscriber-data-management.svg') !!}"
                                 class="img-fluid" />
                        </div>
                    </div>
                    <!-- for iPad and mobile -->
                    <div class="card card-border-none d-sm-none">
                        <div class="card-body">
                            <h2 class="card-title">Own your customer relationships with our free event reservation and registration system</h2>
                            <p class="card-text">Building relationships with customers and keeping them aware of upcoming events is key to running successful events. The Swipez event registration software for entertainment events
                                gives you free access to all the booking information of your patrons, use customer data from all your past events to target promotion of future events.</p>
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
                            <h2 class="card-title">Keep your finger on the pulse using our free event management software </h2>
                            <p class="card-text">Before an event commences knowing which ticket categories have sold out, which categories have the most tickets left, how much revenue have you collected for the event are all important for you to forecast the success of your event or tweak your marketing. The Swipez online entertainment event management software has in depth reporting which covers category sales, complete event sales and even your tax liability if any.</p>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row no-gutters pb-5">
                <div class="col-sm py-2">
                    <div class="card d-none d-sm-flex card-border-none">
                        <div class="card-body">
                            <h2 class="card-title">Professional out-of-the-box event landing page </h2>
                            <p class="card-text">Your event landing page published online is the first interaction your patrons have with your event. An effective landing page captures your patrons interest,gives him a complete breakdown of the event and lists all ticketing options. The Swipez entertainment event ticketing solution has a professional out of the box event landing  page which is intuitive, informative and takes only a few minutes to set up.</p>
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
                            <h2 class="card-title">Professional out-of-the-box event landing page </h2>
                            <p class="card-text">Your event landing page published online is the first interaction your patrons have with your event. An effective landing page captures your patrons interest,gives him a complete breakdown of the event and lists all ticketing options. The Swipez entertainment event ticketing solution has a professional out of the box event landing page which is intuitive, informative and takes only a few minutes to set up.</p>
                        </div>
                    </div>
                    <!-- end -->
                </div>
            </div>
        </div>
    </div>
</section>
<section class="jumbotron bg-secondary py-5" id="cta">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-12 mb-5">
                <h3 class="text-white">Over {{env('SWIPEZ_BIZ_NUM')}} businesses trust Swipez with their events and billing everyday!<br /><br />Register to get your free account</h3>
            </div>
            <div class="col-md-12"><a class="btn btn-primary btn-lg text-white"
                    href="{{ config('app.APP_URL') }}merchant/register">Start using for free</a>
            </div>
        </div>
    </div>
</section>
<section class="jumbotron py-4 bg-white" id="features">
    <div class="container">
        <div class="row justify-content-center py-5">
            <div class="col-12 text-center">
                <h2 class="display-4">Features</h2>
            </div>
        </div>
        <div class="row row-eq-height text-center pb-5">
            <div class="col-md-4">
                <div class="bg-light-blue p-4 h-100">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="clipboard-list" class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path fill="currentColor" d="M336 64h-80c0-35.3-28.7-64-64-64s-64 28.7-64 64H48C21.5 64 0 85.5 0 112v352c0 26.5 21.5 48 48 48h288c26.5 0 48-21.5 48-48V112c0-26.5-21.5-48-48-48zM96 424c-13.3 0-24-10.7-24-24s10.7-24 24-24 24 10.7 24 24-10.7 24-24 24zm0-96c-13.3 0-24-10.7-24-24s10.7-24 24-24 24 10.7 24 24-10.7 24-24 24zm0-96c-13.3 0-24-10.7-24-24s10.7-24 24-24 24 10.7 24 24-10.7 24-24 24zm96-192c13.3 0 24 10.7 24 24s-10.7 24-24 24-24-10.7-24-24 10.7-24 24-24zm128 368c0 4.4-3.6 8-8 8H168c-4.4 0-8-3.6-8-8v-16c0-4.4 3.6-8 8-8h144c4.4 0 8 3.6 8 8v16zm0-96c0 4.4-3.6 8-8 8H168c-4.4 0-8-3.6-8-8v-16c0-4.4 3.6-8 8-8h144c4.4 0 8 3.6 8 8v16zm0-96c0 4.4-3.6 8-8 8H168c-4.4 0-8-3.6-8-8v-16c0-4.4 3.6-8 8-8h144c4.4 0 8 3.6 8 8v16z"></path></svg>
                    <h5 class="pb-2">Well-organised bookings</h5>
                    <p>Set up seats and packages for your events as per your needs. Intimate customers upon booking with a receipt and QR code for your event.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bg-light-blue p-4 h-100">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="credit-card" class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path fill="currentColor" d="M0 432c0 26.5 21.5 48 48 48h480c26.5 0 48-21.5 48-48V256H0v176zm192-68c0-6.6 5.4-12 12-12h136c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12H204c-6.6 0-12-5.4-12-12v-40zm-128 0c0-6.6 5.4-12 12-12h72c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12H76c-6.6 0-12-5.4-12-12v-40zM576 80v48H0V80c0-26.5 21.5-48 48-48h480c26.5 0 48 21.5 48 48z"></path></svg>
                    <h5 class="pb-2">Swift and easy payments</h5>
                    <p>Collect advance payments for events using online payment modes like UPI, Cards, Net Banking and Wallets. Power payments at the venue. Sync with offline bookings as well.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bg-light-blue p-4 h-100">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="piggy-bank" class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path fill="currentColor" d="M560 224h-29.5c-8.8-20-21.6-37.7-37.4-52.5L512 96h-32c-29.4 0-55.4 13.5-73 34.3-7.6-1.1-15.1-2.3-23-2.3H256c-77.4 0-141.9 55-156.8 128H56c-14.8 0-26.5-13.5-23.5-28.8C34.7 215.8 45.4 208 57 208h1c3.3 0 6-2.7 6-6v-20c0-3.3-2.7-6-6-6-28.5 0-53.9 20.4-57.5 48.6C-3.9 258.8 22.7 288 56 288h40c0 52.2 25.4 98.1 64 127.3V496c0 8.8 7.2 16 16 16h64c8.8 0 16-7.2 16-16v-48h128v48c0 8.8 7.2 16 16 16h64c8.8 0 16-7.2 16-16v-80.7c11.8-8.9 22.3-19.4 31.3-31.3H560c8.8 0 16-7.2 16-16V240c0-8.8-7.2-16-16-16zm-128 64c-8.8 0-16-7.2-16-16s7.2-16 16-16 16 7.2 16 16-7.2 16-16 16zM256 96h128c5.4 0 10.7.4 15.9.8 0-.3.1-.5.1-.8 0-53-43-96-96-96s-96 43-96 96c0 2.1.5 4.1.6 6.2 15.2-3.9 31-6.2 47.4-6.2z"></path></svg>
                    <h5 class="pb-2">No revenue share for tickets sold</h5>
                    <p>Lowest in industry online transaction rates helps you earn more revenue per ticket sold. No share taken for every
                        ticket sold.</p>
                </div>
            </div>
        </div>
        <div class="row row-eq-height text-center pb-5">
            <div class="col-md-4">
                <div class="bg-light-blue p-4 h-100">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="qrcode" class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M0 224h192V32H0v192zM64 96h64v64H64V96zm192-64v192h192V32H256zm128 128h-64V96h64v64zM0 480h192V288H0v192zm64-128h64v64H64v-64zm352-64h32v128h-96v-32h-32v96h-64V288h96v32h64v-32zm0 160h32v32h-32v-32zm-64 0h32v32h-32v-32z"></path></svg>
                    <h5 class="pb-2">QR code ready tickets</h5>
                    <p>Customers receive QR code with their bookings on their phone. Use our QR code reader to manage box entries smoothly.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bg-light-blue p-4 h-100">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="thumbs-up" class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M104 224H24c-13.255 0-24 10.745-24 24v240c0 13.255 10.745 24 24 24h80c13.255 0 24-10.745 24-24V248c0-13.255-10.745-24-24-24zM64 472c-13.255 0-24-10.745-24-24s10.745-24 24-24 24 10.745 24 24-10.745 24-24 24zM384 81.452c0 42.416-25.97 66.208-33.277 94.548h101.723c33.397 0 59.397 27.746 59.553 58.098.084 17.938-7.546 37.249-19.439 49.197l-.11.11c9.836 23.337 8.237 56.037-9.308 79.469 8.681 25.895-.069 57.704-16.382 74.757 4.298 17.598 2.244 32.575-6.148 44.632C440.202 511.587 389.616 512 346.839 512l-2.845-.001c-48.287-.017-87.806-17.598-119.56-31.725-15.957-7.099-36.821-15.887-52.651-16.178-6.54-.12-11.783-5.457-11.783-11.998v-213.77c0-3.2 1.282-6.271 3.558-8.521 39.614-39.144 56.648-80.587 89.117-113.111 14.804-14.832 20.188-37.236 25.393-58.902C282.515 39.293 291.817 0 312 0c24 0 72 8 72 81.452z"></path></svg>
                    <h5 class="pb-2">Easy event promotion</h5>
                    <p>Publish your event page in minutes. Publish event policies & T&C. Share the page on your social media
                        accounts with an easy to share link.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bg-light-blue p-4 h-100">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="users" class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><path fill="currentColor" d="M96 224c35.3 0 64-28.7 64-64s-28.7-64-64-64-64 28.7-64 64 28.7 64 64 64zm448 0c35.3 0 64-28.7 64-64s-28.7-64-64-64-64 28.7-64 64 28.7 64 64 64zm32 32h-64c-17.6 0-33.5 7.1-45.1 18.6 40.3 22.1 68.9 62 75.1 109.4h66c17.7 0 32-14.3 32-32v-32c0-35.3-28.7-64-64-64zm-256 0c61.9 0 112-50.1 112-112S381.9 32 320 32 208 82.1 208 144s50.1 112 112 112zm76.8 32h-8.3c-20.8 10-43.9 16-68.5 16s-47.6-6-68.5-16h-8.3C179.6 288 128 339.6 128 403.2V432c0 26.5 21.5 48 48 48h288c26.5 0 48-21.5 48-48v-28.8c0-63.6-51.6-115.2-115.2-115.2zm-223.7-13.4C161.5 263.1 145.6 256 128 256H64c-35.3 0-64 28.7-64 64v32c0 17.7 14.3 32 32 32h65.9c6.3-47.4 34.9-87.3 75.2-109.4z"></path></svg>
                    <h5 class="pb-2">Create your customer database</h5>
                    <p>Collect customer data like email id, mobile number and more from your event bookings. Use collected customer data to target for future events with ease.</p>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="jumbotron bg-secondary py-5" id="cta">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-12 mb-5">
                <h3 class="text-white">{{env('SWIPEZ_BIZ_NUM')}} small and medium business owners use Swipez <br /><br />Get your account, its free!</h3>
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
                    <h2 class="section_title">Advantages of using Swipez <a href="{{ route('home.event') }}">online event registration software</a> over other platforms</h2>
                    <p class="intro-description">Key advantages event companies experience using our events registration platform</p>
                    <div class="step mt-5">
                        <div>
                            <div class="circle bg-primary">1</div>
                        </div>
                        <div>
                            <h3>Save revenue</h3>
                            <p>Earn 18-20% more revenue compared to selling the same tickets on other event booking platforms</p>
                        </div>
                    </div>
                    <div class="step">
                        <div>
                            <div class="circle bg-primary">2</div>
                        </div>
                        <div>
                            <h3>Save time</h3>
                            <p>Saved over 80+ man hours per event by organizing their event bookings online</p>
                        </div>
                    </div>
                    <div class="step">
                        <div>
                            <div class="circle bg-primary">3</div>
                        </div>
                        <div>
                            <h3>Collect customer data</h3>
                            <p>Collected 100% more customer data for future events in comparison to other event booking platforms</p>
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
