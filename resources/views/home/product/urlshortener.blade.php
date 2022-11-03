@extends('home.master')
@section('title', 'Free invoice software with payment collections and payment reminders')

@section('content')
<section class="jumbotron jumbotron-features bg-transparent py-6" id="header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12 col-md-12 col-lg-6 col-xl-5 d-none d-lg-block">
                <h1>URL Shortener</h1>
                <p class="lead mb-2">Shorten long URLs instantaneously. Track, manage and free up characters in your
                    customer communications - unleash the power of your links.</p>
                <a class="btn btn-primary btn-lg text-white bg-tertiary" href="/contactus">Contact Us</a>
            </div>
            <div class="col-12 col-md-8 m-auto ml-lg-auto mr-lg-0 col-lg-6 pt-5 pt-lg-0 d-none d-lg-block">
                <img alt="Online URL shortener for enterprises" class="img-fluid"
                    src="{!! asset('images/product/url-shortener.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none text-center">
                <img alt="Online URL shortener for enterprises" class="img-fluid"
                    src="{!! asset('images/product/url-shortener.svg') !!}" />
                <h1>URL Shortener</h1>
                <p class="lead mb-2">Shorten long URLs instantaneously. Track, manage and free up characters in your
                    customer communications - unleash the power of your links.</p>
                <a class="btn btn-primary btn-lg text-white bg-tertiary" href="/contactus">Contact Us</a>

            </div>
            <!-- end -->
        </div>
    </div>
</section>
<section class="jumbotron py-5 bg-secondary text-white" id="cta">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-12">
                <h3>Shorten long urls in bulk with your branded domain name</h3>
            </div>
        </div>
    </div>
</section>
<section class="jumbotron bg-transparent py-5" id="features">
    <div class="container">
        <div class="row justify-content-center pb-0">
            <div class="col-12 text-center">
                <h2 class="display-4">Features</h2>
            </div>
        </div>
        <div class="row text-left align-items-center pt-5 pb-md-5">
            <div class="col-4 col-md-5 d-none d-lg-block">
                <img alt="URL shortener for small or large enterprises" class="img-fluid"
                    src="{!! asset('images/product/url-shortener/features/large-scale-url-shortening.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="URL shortener for small or large enterprises" class="img-fluid"
                    src="{!! asset('images/product/url-shortener/features/large-scale-url-shortening.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 m-md-auto d-none d-lg-block">
                <h2><strong>URL Shortening at scale</strong></h2>
                <p class="lead">Custom algorithm to create and redirect URLs. Handles mass creation and redirection
                    requests as per growing business demands.</p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>URL Shortening at scale</strong></h2>
                <p class="lead">Custom algorithm to create and redirect URLs. Handles mass creation and redirection
                    requests as per growing business demands.</p>
            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5 pb-md-5">
            <div class="col-4 col-md-5 m-md-auto order-md-5 d-none d-lg-block">
                <img alt="Brand specific short URL domain" class="img-fluid"
                    src="{!! asset('images/product/url-shortener/features/custom-short-url-domain.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Brand specific short URL domain" class="img-fluid"
                    src="{!! asset('images/product/url-shortener/features/custom-short-url-domain.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 d-none d-lg-block">
                <h2><strong>Custom short URL domain as per your brand</strong></h2>
                <p class="lead">Enable your short URL to reflect your brands identity. Enable brand recall starting
                    with your own short URL - leave an impression on your customers.</p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>Custom short URL domain as per your brand</strong></h2>
                <p class="lead">Enable your short URL to reflect your brands identity. Enable brand recall starting
                    with your own short URL - leave an impression on your customers.</p>
            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5">
            <div class="col-4 col-md-5 d-none d-lg-block">
                <img alt="Flexible deployment options for URL shortener service" class="img-fluid"
                    src="{!! asset('images/product/url-shortener/features/flexible-deployment.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Flexible deployment options for URL shortener service" class="img-fluid"
                    src="{!! asset('images/product/url-shortener/features/flexible-deployment.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 m-md-auto d-none d-lg-block">
                <h2><strong>Fast & flexible deployment</strong></h2>
                <p class="lead">Ready APIs to extend the power of short URLs to your existing systems. Flexible
                    hosting options available, hosted on our cloud infrastructure or deployed on your servers.</p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>Fast & flexible deployment</strong></h2>
                <p class="lead">Ready APIs to extend the power of short URLs to your existing systems. Flexible
                    hosting options available, hosted on our cloud infrastructure or deployed on your servers.</p>
            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5 pb-md-5">
            <div class="col-4 col-md-5 m-md-auto order-md-5 d-none d-lg-block">
                <img alt="Analytics dashboard for URL shortener" class="img-fluid"
                    src="{!! asset('images/product/url-shortener/features/url-shortener-analytics.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Analytics dashboard for URL shortener" class="img-fluid"
                    src="{!! asset('images/product/url-shortener/features/url-shortener-analytics.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 d-none d-lg-block">
                <h2><strong>Click tracking & analytics</strong></h2>
                <p class="lead">Analyse clicks on your communication. Customize subsequent marketing communication
                    based on data backed insights.</p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>Click tracking & analytics</strong></h2>
                <p class="lead">Analyse clicks on your communication. Customize subsequent marketing communication
                    based on data backed insights.</p>
            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5">
            <div class="col-4 col-md-5 d-none d-lg-block">
                <img alt="Flexible pricing slabs for URL shortener" class="img-fluid"
                    src="{!! asset('images/product/url-shortener/features/flexible-pricing.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Flexible pricing slabs for URL shortener" class="img-fluid"
                    src="{!! asset('images/product/url-shortener/features/flexible-pricing.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 m-md-auto d-none d-lg-block">
                <h2><strong>Flexible pricing categories that cater to every business</strong></h2>
                <p class="lead">Pricing tiers that allow you to pay according to your requirements. Pay as you go
                    pricing for businesses of all sizes.</p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>Flexible pricing categories that cater to every business</strong></h2>
                <p class="lead">Pricing tiers that allow you to pay according to your requirements. Pay as you go
                    pricing for businesses of all sizes.</p>
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
                <a class="btn btn-primary btn-lg text-white bg-primary"
                    href="{{ config('app.APP_URL') }}contactus">Contact Us</a>
                <a class="btn btn-lg text-white" href="{{ route('home.pricing.urlshortener') }}">See pricing plans</a>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-sm-none">
                <div class="row justify-content-center">
                    <a class="btn btn-lg text-white bg-primary mr-1" href="{{ config('app.APP_URL') }}contactus">Contact
                        Us</a>
                    <a class="btn btn-lg text-white" href="{{ route('home.pricing.urlshortener') }}">Pricing plans</a>
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
                <h2 class="display-4 text-white">Shorten links, increase conversions</h2>
            </div>
        </div><br />
        <div class="row row-eq-height text-center pb-5">
            <div class="col-md-3">
                <div class="bg-white p-4 h-100">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check-double"
                        class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 512 512">
                        <path fill="currentColor"
                            d="M505 174.8l-39.6-39.6c-9.4-9.4-24.6-9.4-33.9 0L192 374.7 80.6 263.2c-9.4-9.4-24.6-9.4-33.9 0L7 302.9c-9.4 9.4-9.4 24.6 0 34L175 505c9.4 9.4 24.6 9.4 33.9 0l296-296.2c9.4-9.5 9.4-24.7.1-34zm-324.3 106c6.2 6.3 16.4 6.3 22.6 0l208-208.2c6.2-6.3 6.2-16.4 0-22.6L366.1 4.7c-6.2-6.3-16.4-6.3-22.6 0L192 156.2l-55.4-55.5c-6.2-6.3-16.4-6.3-22.6 0L68.7 146c-6.2 6.3-6.2 16.4 0 22.6l112 112.2z">
                        </path>
                    </svg>
                    <h3 class="text-secondary pb-2">Tried and tested</h3>
                    <p>Used by large institutions and small businesses alike. Audited for security and infrastructure
                        requirements.</p>
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
                    <h3 class="text-secondary pb-2">API access supported</h3>
                    <p>Upload a file with the URLs to be shortened OR invoke our APIs. Easy for business users or
                        existing systems to use the URL Shortener.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="bg-white p-4 h-100">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="server"
                        class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 512 512">
                        <path fill="currentColor"
                            d="M480 160H32c-17.673 0-32-14.327-32-32V64c0-17.673 14.327-32 32-32h448c17.673 0 32 14.327 32 32v64c0 17.673-14.327 32-32 32zm-48-88c-13.255 0-24 10.745-24 24s10.745 24 24 24 24-10.745 24-24-10.745-24-24-24zm-64 0c-13.255 0-24 10.745-24 24s10.745 24 24 24 24-10.745 24-24-10.745-24-24-24zm112 248H32c-17.673 0-32-14.327-32-32v-64c0-17.673 14.327-32 32-32h448c17.673 0 32 14.327 32 32v64c0 17.673-14.327 32-32 32zm-48-88c-13.255 0-24 10.745-24 24s10.745 24 24 24 24-10.745 24-24-10.745-24-24-24zm-64 0c-13.255 0-24 10.745-24 24s10.745 24 24 24 24-10.745 24-24-10.745-24-24-24zm112 248H32c-17.673 0-32-14.327-32-32v-64c0-17.673 14.327-32 32-32h448c17.673 0 32 14.327 32 32v64c0 17.673-14.327 32-32 32zm-48-88c-13.255 0-24 10.745-24 24s10.745 24 24 24 24-10.745 24-24-10.745-24-24-24zm-64 0c-13.255 0-24 10.745-24 24s10.745 24 24 24 24-10.745 24-24-10.745-24-24-24z">
                        </path>
                    </svg>
                    <h3 class="text-secondary pb-2">On premise or managed</h3>
                    <p>Ability to deploy in premise or use from our cloud hosted infrastructure. Use as per your
                        compliance requirements.</p>
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
                    <h3 class="text-secondary pb-2">Social media ready</h3>
                    <p>Shortened URLs are perfect for SMS messages and social platforms. Squeeze in your URL with
                        hashtags. Beat those character limits.</p>
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
                    URL shortener</p>
            </div>
        </div>
        <div class="row align-items-center justify-content-center">
            <div class="col-lg-9 col-xl-6">
                <div class="card p-5">
                    <div class="row">
                        <div class="col-8 col-sm-6 col-md-4 col-xl-3 ml-auto mr-auto">
                            <img alt="image" class="img-fluid rounded"
                                src="{!! asset('images/product/url-shortener/swipez-client1.jpg') !!}">
                        </div>
                        <div class="col-md-8 mt-4 mt-md-0">
                            <p>"We have seamlessly added URL shortening capability to our technology stack. Now for all
                                our client communications we are able to create and track shortened links the via the
                                Swipez URL shortener. "</p>
                            <p class="h3 mt-4 mt-xl-5">
                                <strong>Chandra Gupta</strong>
                            </p>
                            <p>
                                <em>CTO, Reliance Capital Asset Management Limited</em>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-9 col-xl-6 mt-4 mt-xl-0">
                <div class="card p-5">
                    <div class="row">
                        <div class="col-8 col-sm-6 col-md-4 col-xl-3 ml-auto mr-auto">
                            <img alt="image" class="img-fluid rounded"
                                src="{!! asset('images/product/url-shortener/swipez-client2.jpg') !!}">
                        </div>
                        <div class="col-md-8 mt-4 mt-md-0">
                            <p>"All our internet and telcom bills are now sent to our customers as a short link via SMS.
                                This has reduced late payments by our customers significantly. It is now simple to send
                                new offers and plans to our customers as well."</p>
                            <p class="h3 mt-4 mt-xl-5">
                                <strong>Viren Hundekari</strong>
                            </p>
                            <p>
                                <em>Director, Shivam Teleinfra</em>
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
                <h3>Create and share powerful links for your business and maximize the impact of every digital
                    initiative.</h3>
            </div>
            <div class="col-12 d-none d-sm-block">
                <a class="btn btn-secondary btn-lg text-white" href="{{ config('app.APP_URL') }}contactus">Contact
                    Us</a>
                <a class="btn btn-lg text-white" href="{{ route('home.pricing.urlshortener') }}">See pricing plans</a>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-sm-none">
                <div class="row justify-content-center">
                    <a class="btn btn-secondary btn-lg text-white mr-1"
                        href="{{ config('app.APP_URL') }}contactus">Contact Us</a>
                    <a class="btn btn-lg text-white" href="{{ route('home.pricing.urlshortener') }}">Pricing plans</a>
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
                            Is there an API for the Swipez URL Shortener?
                        </div>
                        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne"
                            data-parent="#accordion" itemscope itemprop="acceptedAnswer"
                            itemtype="http://schema.org/Answer" itemscope itemprop="acceptedAnswer"
                            itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, we have ready out of the box API’s for shortening URLs at scale.
                            </div>
                        </div>
                    </div>
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingTwo">
                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            Can I use a custom domain name via URL shortener?
                        </div>
                        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion"
                            itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, you can own custom branded domain name.
                            </div>
                        </div>
                    </div>
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingThree">
                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            Are there any click limits for the Swipez URL Shortener?
                        </div>
                        <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion"
                            itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                No, our URL shortener facilitates unlimited clicks.
                            </div>
                        </div>
                    </div>
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingFour">
                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                            Can I view any click stats via URL shortener?
                        </div>
                        <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion"
                            itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, you can use our reporting tab to check click stats.
                            </div>
                        </div>
                    </div>
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingFive">
                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                            Can I bulk upload URL’s that I want shortened opposed to one at a time?
                        </div>
                        <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordion"
                            itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, you can upload bulk urls for shortening them via excel sheets or our APIs
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
