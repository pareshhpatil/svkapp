@extends('home.master')
@section('title', 'Helping businesses collect payments faster by improving their business operations')

@section('content')
<section class="jumbotron bg-transparent py-5" id="pricing">
    <div class="container">
        <div class="col">
            <header class="text-center w-md-50 mx-auto mb-8">
                <h1 class="h1">Business website pricing</h1>
                <p class="h5">Affordable annual plans for businesses of all shapes and sizes.</p>
            </header>
        </div>
        <div class="row row-eq-height">
            <div class="col-md-6 my-5">
                <div class="card">
                    <div class="card-header p-4 text-center" style="margin-top: 0px; box-shadow: none!important;">
                        <h3 class="m-0">Free</h3>
                    </div>
                    <div class="card-body text-center py-5 bg-light">
                        <h2 class="d-block h1 mt-0 mb-4 font-weight-bold display-4">₹<span data-monthly="0"
                                data-annual="0">0</span>
                            <small class="lead"></small>
                        </h2>
                    </div>
                    <div class="card-list">
                        <ul class="list-group text-center">
                            <li class="list-group-item">Website builder</li>
                            <li class="list-group-item">Swipez domain (mycompany.swipez.in)</li>
                            <li class="list-group-item">Online payments @ 2.1%</li>
                            <li class="list-group-item">Single Page</li>
                        </ul>
                    </div>
                    <div class="card-body text-center">
                        <a class="btn btn-lg btn-block btn-outline-secondary" href="{{ config('app.APP_URL') }}merchant/register">Get Started
                            Now</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 my-5">
                <div class="card">
                    <div class="card-header p-4 text-center text-white bg-secondary"  style="margin-top: 0px; box-shadow: none!important;">
                        <h3 class="m-0">Professional</h3>
                    </div>
                    <div class="card-body text-center py-5 text-white bg-secondary">
                        <h2 class="d-block h1 mt-0 mb-4 font-weight-bold display-4">₹<span data-monthly="15"
                                data-annual="12">3,499</span>
                            <small class="lead"></small>
                        </h2>
                    </div>
                    <div class="card-list">
                        <ul class="list-group text-center">
                            <li class="list-group-item">Website builder</li>
                            <li class="list-group-item">Connect your domain</li>
                            <li class="list-group-item">Online payments starting at 0.50%</li>
                            <li class="list-group-item">Custom packages and plan pages</li>
                        </ul>
                    </div>
                    <div class="card-body text-center text-primary">
                        <a class="btn btn-lg btn-block btn-secondary text-white" href="https://www.swipez.in/merchant/package/confirm/nJlKXH8tL62zPcTWEf4u-Q">Buy
                            now</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="jumbotron py-5" id="faq">
    <div class="container text-center">
        <div class="row mb-5">
            <div class="col text-center">
                <h2 class="display-4 font-weight-bold">Frequently Asked Questions</h2>
                <p class="lead font-weight-bold">Looking for more info? Here are some things we're commonly asked</p>
            </div>
        </div>
        <div class="row mb-5">
            <div class="col-md-8 offset-md-2 col-sm-12">
                <div class="mb-5">
                    <h3>What is Swipez website builder?</h3>
                    <p class="font-weight-normal">Swipez lets you craft a beautiful business website in minutes with our
                        online Website Builder in just a few simple steps. No coding skills required,just use our drag
                        and drop editor and go live.</p>
                </div>
            </div>
            <div class="col-md-8 offset-md-2 col-sm-12">
                <div class="mb-5">
                    <h3>Is web hosting included with my Swipez website?</h3>
                    <p class="font-weight-normal">Yes. Swipez provides a complete, reliable hosting solution for your
                        website, with more than 99.9% uptime and uninterrupted service. Web hosting is included for free
                        in either of our plans.</p>
                </div>
            </div>
            <div class="col-md-8 offset-md-2 col-sm-12">
                <div class="mb-5">
                    <h3>Do I have to buy my own domain?</h3>
                    <p class="font-weight-normal">In our free plan you will be given a free dedicated Swipez sub domain
                        for your website, however in our professional plan we allow you to attach you own personalized
                        domain name which can be bought via any domain provider online.</p>
                </div>
            </div>
            <div class="col-md-8 offset-md-2 col-sm-12">
                <div class="mb-5">
                    <h3>How can I track online transactions made on my website?</h3>
                    <p class="font-weight-normal">When you sign up you will be provided with a Swipez dashboard where
                        you can conveniently track all payments and use our in depth reporting.</p>
                </div>
            </div>
            <div class="col-md-8 offset-md-2 col-sm-12">
                <div class="mb-5">
                    <h3>What are custom packages and plan pages?</h3>
                    <p class="font-weight-normal">These are pages that you can create to list your services and packages. Your customers can purchase a package from your package listing page. Package listing pages too can be created via the Swipez website builder tool.</p>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="jumbotron py-5 bg-primary" id="cta">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-12 mb-5">
                <h3 class="text-white">More questions?<br /><br />Reach out to our support team. We're here to help.
                </h3>
            </div>
            <div class="col-md-12">
                <a class="btn btn-primary btn-lg text-white bg-tertiary" href="javascript:void(groove.widget.toggle())">Chat now</a>
                <a class="btn btn-primary btn-lg text-white bg-secondary" href="/getintouch/website-builder-pricing">Send email</a>
            </div>
        </div>
    </div>
</section>
@endsection
