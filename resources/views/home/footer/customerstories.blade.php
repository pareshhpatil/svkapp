@extends('home.master')

@section('content')
<section class="jumbotron bg-secondary text-white py-8" id="header">
    <div class="container text-center text-lg-left">
        <div class="row align-items-center">
            <div class="col-md-12 col-lg-6 col-xl-5">
                <h1>Customer success stories and Swipez product reviews</h1>
                <p class="lead mt-5 mb-2 text-white">Swipez serves {{env('SWIPEZ_BIZ_NUM')}} businesses across different
                    business segments. Swipez products has been built ground up by incorporating feedback from all our
                    customers!</p>
                <p class="lead mb-5 text-white">Learn how
                    different businesses are benefiting by digitizing their business operations.</p>
                <a href="{{ config('app.APP_URL') }}merchant/register" class="btn btn-tertiary btn-lg text-white">Get
                    Started Now</a>
            </div>
            <div class="col-md-8 m-auto ml-lg-auto mr-lg-0 col-lg-6 pt-5 pt-lg-0">
                <img alt="image" class="img-fluid" src="images/customer-stories/customer-stories.svg" />
            </div>
        </div>
    </div>
</section>
<style>
    .image-container {
        position: relative;
    }

    /* Bottom right text */
    .image-text-block {
        position: absolute;
        width: 100%;
        bottom: 0px;
        right: 0px;
        background-color: rgba(83, 83, 83, 0.3);
        color: white;
        padding-left: 20px;

    }
</style>
<section class="jumbotron py-8 bg-transparent" id="team">
    <div class="container">
        <div class="row p-0 py-0">
            <div class="col-md-8 offset-md-2 text-center mb-2">
                <h2 class="display-4">Discover how businesses use Swipez</h2>
                <p class="lead">Success stories and product reviews from business owners</p>
            </div>
        </div>
        <div class="row">
            <div class="card-deck">
                <div class="card">
                    <div class="image-container">
                        <a href="https://www.swipez.in/blog/hispanic-horizons-learn-the-2nd-most-spoken-language-in-the-world/"
                            class="card-link" target="_blank"><img class="card-img-top"
                                src="images/customer-stories/hispanic-horizons.jpg" alt="Hispanic Horizons education billing" />
                            <div class="image-text-block">
                                <p class="text-white">Education industry</p>
                                <h5>Billing and collections software</h5>
                            </div>
                        </a>
                    </div>
                    <div class="card-body">
                        <blockquote class="blockquote">
                            <p class="mb-0">"Using Swipez we have organized our online fee collections and invoicing.
                                Their form builder helps us collect student information and payments in one go."</p>
                            <footer class="blockquote-footer">Joslin Cherian, <cite title="Source Title">Admin manager,
                                    Hispanic Horizons</cite></footer>
                        </blockquote>
                    </div>
                    <div class="card-footer text-right bg-white border-0">
                        <p class="card-text">
                            <a href="https://www.swipez.in/blog/hispanic-horizons-learn-the-2nd-most-spoken-language-in-the-world/"
                                class="card-link" target="_blank">Learn more</a>
                            <small class="pl-3 text-muted">3 mins read</small>
                        </p>
                    </div>
                </div>
                <div class="card">
                    <div class="image-container">
                        <a href="https://www.swipez.in/blog/vztrack-complete-housing-society-management-suite/"
                            class="pull-right card-link" target="_blank"><img class="card-img-top"
                                src="images/customer-stories/vz-track.jpg" alt="Society management software billing integration" />
                            <div class="image-text-block">
                                <p class="text-white">App development</p>
                                <h5>Billing and venue booking software</h5>
                            </div>
                        </a>
                    </div>
                    <div class="card-body">
                        <blockquote class="blockquote">
                            <p class="mb-0">"Swipez helped us extend the feature set of our product with little or no
                                new development at our end. This integration has helped us expand our services to our
                                clients within weeks."</p>
                            <footer class="blockquote-footer">Ajoy Sreedharan, <cite title="Source Title">Co-Founder, VZ
                                    Track</cite></footer>
                        </blockquote>
                    </div>
                    <div class="card-footer text-right bg-white border-0">
                        <p class="card-text">
                            <a href="https://www.swipez.in/blog/vztrack-complete-housing-society-management-suite/"
                                class="pull-right card-link" target="_blank">Learn more</a>
                            <small class="pl-3 text-muted">4 mins read</small>
                        </p>
                    </div>
                </div>
            </div>
            <div class="card-deck pt-2">
                <div class="card">
                    <div class="image-container">
                        <a href="https://www.swipez.in/blog/the-great-next-for-the-adventure-junkie/" class="card-link"
                            target="_blank"><img class="card-img-top" src="images/customer-stories/the-great-next.jpg"
                                alt="The Great Next adventure travel market place customer story" />
                            <div class="image-text-block">
                                <p class="text-white">Travel and tour</p>
                                <h5>Billing and payouts software</h5>
                            </div>
                        </a>
                    </div>
                    <div class="card-body">
                        <blockquote class="blockquote">
                            <p class="mb-0">"Swipez has helped us organize payment collections, market place settlements
                                to tour operators and GST filing under one dashboard without any tech work!"</p>
                            <footer class="blockquote-footer">Amit Thaker, <cite title="Source Title">Co-founder The
                                    Great Next</cite></footer>
                        </blockquote>
                    </div>
                    <div class="card-footer text-right bg-white border-0">
                        <p class="card-text">
                            <a href="https://www.swipez.in/blog/the-great-next-for-the-adventure-junkie/"
                                class="card-link" target="_blank">Learn more</a>
                            <small class="pl-3 text-muted">3 mins read</small>
                        </p>
                    </div>
                </div>
                <div class="card">
                    <div class="image-container">
                        <a href="https://www.swipez.in/blog/shah-solutions-internet-service-provider-for-households-and-businesses/"
                            class="pull-right card-link" target="_blank"><img class="card-img-top"
                                src="images/customer-stories/shah-solutions.jpg" alt="ISP billing software use case" />
                            <div class="image-text-block">
                                <p class="text-white">Internet service provider</p>
                                <h5>Billing software</h5>
                            </div>
                        </a>
                    </div>
                    <div class="card-body">
                        <blockquote class="blockquote">
                            <p class="mb-0">"Automated payment reminders has reduced telephonic follow ups and need for
                                door step visits for payment collections. Our on-time payment collections have increased
                                by over 45%."</p>
                            <footer class="blockquote-footer">Jayesh Shah, <cite title="Source Title">Founder, Shah
                                    solutions</cite></footer>
                        </blockquote>
                    </div>
                    <div class="card-footer text-right bg-white border-0">
                        <p class="card-text">
                            <a href="https://www.swipez.in/blog/shah-solutions-internet-service-provider-for-households-and-businesses/"
                                class="pull-right card-link" target="_blank">Learn more</a>
                            <small class="pl-3 text-muted">4 mins read</small>
                        </p>
                    </div>
                </div>
            </div>
            <div class="card-deck pt-2">
                <div class="card">
                    <div class="image-container">
                        <a href="https://www.swipez.in/blog/fishvish-shipping-great-produce-to-your-door-step/"
                            class="card-link" target="_blank"><img class="card-img-top" src="images/customer-stories/fishvish.jpg"
                                alt="Food delivery billing software" />
                            <div class="image-text-block">
                                <p class="text-white">Food delivery</p>
                                <h5>Billing software</h5>
                            </div>
                        </a>
                    </div>
                    <div class="card-body">
                        <blockquote class="blockquote">
                            <p class="mb-0">"Swipez helped us organize our online and offline payment collections in one
                                dashboard, allowing us to serve our clients as per their convenience."</p>
                            <footer class="blockquote-footer">Shumu Gupta, <cite title="Source Title">Co-Founder, Fish
                                    Vish</cite></footer>
                        </blockquote>
                    </div>
                    <div class="card-footer text-right bg-white border-0">
                        <p class="card-text">
                            <a href="https://www.swipez.in/blog/fishvish-shipping-great-produce-to-your-door-step/"
                                class="card-link" target="_blank">Learn more</a>
                            <small class="pl-3 text-muted">3 mins read</small>
                        </p>
                    </div>
                </div>
                <div class="card">
                    <div class="image-container">
                        <a href="https://www.swipez.in/blog/glossaread-making-higher-education-affordable-and-convenient/"
                            class="pull-right card-link" target="_blank"><img class="card-img-top"
                                src="images/customer-stories/glossaread.jpg" alt="Education billing software use case" />
                            <div class="image-text-block">
                                <p class="text-white">Education industry</p>
                                <h5>Billing software</h5>
                            </div>
                        </a>
                    </div>
                    <div class="card-body">
                        <blockquote class="blockquote">
                            <p class="mb-0">"Swipez's SaaS platform helped us not only collect payments with ease but
                                also helped us automate our disbursements to our content creators"</p>
                            <footer class="blockquote-footer">Chandrabhanu Pattajoshi, <cite
                                    title="Source Title">Founder, Glossaread Technologies</cite></footer>
                        </blockquote </div>
                        <div class="card-footer text-right bg-white border-0">
                            <p class="card-text">
                                <a href="https://www.swipez.in/blog/glossaread-making-higher-education-affordable-and-convenient/"
                                    class="pull-right card-link" target="_blank">Learn more</a>
                                <small class="pl-3 text-muted">4 mins read</small>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-deck pt-2">
                <div class="card">
                    <div class="image-container">
                        <a href="https://www.swipez.in/blog/atlas-facility-management-solutions-facility-managers-to-help-you-grow/"
                            class="card-link" target="_blank"><img class="card-img-top"
                                src="images/customer-stories/atlas-facilities.jpg" alt="Facility management billing and vendor management" />
                            <div class="image-text-block">
                                <p class="text-white">Facilities management</p>
                                <h5>Franchise management software</h5>
                            </div>
                        </a>
                    </div>
                    <div class="card-body">
                        <blockquote class="blockquote">
                            <p class="mb-0">"Swipez has made it possible for us to manage our payments effectively
                                across the board. Swipez helps us ensure prompt vendor management while we focus on our
                                core business"</p>
                            <footer class="blockquote-footer">Shantonu Adhya, <cite title="Source Title">Co-founder,
                                    Atlas Facility Management Solutions</cite></footer>
                        </blockquote>
                    </div>
                    <div class="card-footer text-right bg-white border-0">
                        <p class="card-text">
                            <a href="https://www.swipez.in/blog/atlas-facility-management-solutions-facility-managers-to-help-you-grow/"
                                class="card-link" target="_blank">Learn more</a>
                            <small class="pl-3 text-muted">3 mins read</small>
                        </p>
                    </div>
                </div>
                <div class="card">
                    <div class="image-container">
                        <a href="mailto:support [@] swipez.in"
                            class="pull-right card-link" target="_blank"><img class="card-img-top"
                                src="images/customer-stories/get-featured.jpg" alt="Feature my business" />
                            <div class="image-text-block">
                                <p class="text-white">Get featured</p>
                                <h5>We would love to write about your business ♥</h5>
                            </div>
                        </a>
                    </div>
                    <div class="card-body">
                        <blockquote class="blockquote">
                            <p class="mb-0">Are you using Swipez already, just like these businesses? We would love to
                                write about your business and your team. Get in touch with us and share your story to
                                get featured on the Swipez customer stories pages</p>
                        </blockquote>
                    </div>
                    <div class="card-footer text-right bg-white border-0">
                        <p class="card-text">
                            <a href="mailto:support [@] swipez.in"
                                class="btn btn-primary pull-right" target="_blank">Feature my business</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
</section>
<section class="jumbotron py-8" id="cta">
    <div class="container">
        <div class="row">
            <div class="col mx-auto">
                <div class="card text-white">
                    <div class="card-body bg-primary rounded-2">
                        <div class="row">
                            <div class="col-md-6 p-5">
                                <h2>
                                    <b>Get Swipez free billing software for your business</b>
                                </h2>
                                <h4 class="mb-5">It's free, register now and digitize your business.</h4>
                                <a class="btn btn-outline-secondary btn-lg text-white bg-secondary"
                                    href="{{ config('app.APP_URL') }}merchant/register">Get started</a>
                            </div>
                            <div class="col-md-6">
                                <img
                                    src="{!! asset('images/product/billing-software/features/payment-collections-dashboard.svg') !!}" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
