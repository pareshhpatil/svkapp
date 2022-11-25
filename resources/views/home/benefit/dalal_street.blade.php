@extends('home.master')
@section('content')
<section class="jumbotron jumbotron-features bg-transparent py-5" id="header">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h1>Dalal Street Investment Journal benefits with Swipez</h1>
                {{-- <h2> </h2> --}}
                <h5>High-quality research and expert advice & recommendations for investors. Get the best in stock market research, recommendations, capital market analysis, and investment advice from experts</h5>
            </div>
            <div class="col-md-6 center-text">
                <img class="imgs" style="max-width: 20%;" src="{!! asset('images/benefits/dsij/dsijlogo.png?id=v1')!!}">
                <h5 class="center-text mt-2">Stay on top of your investments</h5>
                @include('home.product.web_register',['d_type' => "web"])
            </div>
        </div>

    </div>
</section>
<section class="jumbotron bg-secondary py-5" id="cta">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-12 mb-5">
                <h2 class="text-white">Flat 50% off on print and ₹1,560 off on digital, annual subscriptions of DSIJ with Swipez</h2>
            </div>
            <div class="col-12 d-none d-sm-block">
                <a class="btn btn-lg text-white bg-primary" href="{{ config('app.APP_URL') }}merchant/register">Start
                    now</a>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-sm-none">
                <div class="row justify-content-center">
                    <a class="btn btn-lg text-white bg-primary mr-1"
                        href="{{ config('app.APP_URL') }}merchant/register">Start
                        now</a>
                   
                </div>
            </div>
            <!-- end -->
        </div>
    </div>
</section>

<section class="py-5 bg-tranparent" id="cta">
    <div class="container">
        <div class="row">
            <div class="col mx-auto">
                <div class="bg-white rounded-1 p-5">
                    <div class="row">
                        <div class="col-md-6 d-none d-lg-block">
                            <img alt="Swipez Benefits About Dalal_street" src="{!! asset('images/benefits/Swipez_Benefits_About.svg') !!}" width="640" height="360" loading="lazy" class="lazyload" />
                        </div>
                        <div class="col-md-6 px-3 d-none d-lg-block">
                            <h2 class="pb-3">
                                <b>About Dalal Street Investment Journal
                                </b>
                            </h2>
                            <p>What began as a 12-page cyclostyled stapled booklet in 1986, Dalal Street Investment Journal today is one of the most sought-after publications for stock market investing. Get expert advice and recommendations from in-depth analysis and research for your next stock market investment. The fortnightly magazine, with a dedicated readership of more than 8 lakh is supported by a team of carefully chosen experts on markets and corporate India, focuses on stock market research and recommendations, capital market analysis, investment advice for individuals, as well as an analysis of various economic activities in the nation and their effects on Indian share markets. 
                            </p>
                        </div>
                    </div>
                    <!-- for iPad and mobile -->
                    <div class="row">
                        <div class="col-md-6 d-lg-none mb-3">
                            <img alt="Swipez Benefits About Dalal_street" src="{!! asset('images/benefits/Swipez_Benefits_About.svg') !!}" width="640" height="360" loading="lazy" class="lazyload" />
                        </div>
                        <div class="col-md-6 d-lg-none">
                            <h2 class="py-3 ">
                                <b>About Dalal Street Investment Journal
                                </b>
                            </h2>
                            <p>What began as a 12-page cyclostyled stapled booklet in 1986, Dalal Street Investment Journal today is one of the most sought-after publications for stock market investing. Get expert advice and recommendations from in-depth analysis and research for your next stock market investment. The fortnightly magazine, with a dedicated readership of more than 8 lakh is supported by a team of carefully chosen experts on markets and corporate India, focuses on stock market research and recommendations, capital market analysis, investment advice for individuals, as well as an analysis of various economic activities in the nation and their effects on Indian share markets. 
                            </p>
                        </div>
                    </div>
                    <!-- end -->
                </div>
            </div>
        </div>
    </div>
</section>
<section class="py-5 bg-tranparent" id="cta">
    <div class="container">
        <div class="row">
            <div class="col mx-auto">
                <div class="bg-secondary rounded-1 p-5">
                    <div class="row text-white">
                        <div class="col-md-6 px-3 d-none d-lg-block">
                            <h2 class="pb-3">
                                <b>Benefits of Dalal Street Investment Journal</b>
                            </h2>
                            <ul class="list-unstyled">
                                <li class="lead text-white"> <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" class="h-6 pr-1" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                        <path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                                        </path>
                                    </svg>
                                    ₹1,560 discount on the annual subscription to DSIJ online magazine
                                </li>
                                <li class="lead text-white"> <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" class="h-6 pr-1" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                        <path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                                        </path>
                                    </svg>
                                    50% off on the annual subscription to the print publication of DSIJ worth ₹3,120
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6 d-none d-lg-block">
                            <img alt="Swipez Benefits Dalal-street offer" src="{!! asset('images/benefits/Swipez_Benefits_offer.svg') !!}" width="640" height="360" loading="lazy" class="lazyload" />
                        </div>
                    </div>
                    <!-- for iPad and mobile -->
                    <div class="row text-white">
                        <div class="col-md-6 d-lg-none mb-3">
                            <img alt="Swipez Benefits Dalal-street offer" src="{!! asset('images/benefits/Swipez_Benefits_offer.svg') !!}" width="640" height="360" loading="lazy" class="lazyload" />
                        </div>
                        <div class="col-md-6 d-lg-none">
                            <h2 class="py-3 text-center">
                                <b>Benefits of Dalal Street Investment Journal</b>
                            </h2>
                            <ul class="list-unstyled pb-2">
                                <li class="lead text-white"> <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" class="h-6 pr-1" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                        <path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                                        </path>
                                    </svg>
                                    ₹1,560 discount on the annual subscription to DSIJ online magazine</li>
                                <li class="lead text-white"> <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" class="h-6 pr-1" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                        <path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                                        </path>
                                    </svg>
                                    50% off on the annual subscription to the print publication of DSIJ worth ₹3,120
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- end -->
                </div>
            </div>
        </div>
    </div>
</section>
<section class="jumbotron py-5 bg-primary" id="cta">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-12 mb-5 ">
                <h3 class="text-white">Drop us a line and we’ll get in touch
                </h3>
            </div>
            <div class="col-md-12">
                <a  data-toggle="modal" class="btn btn-primary btn-lg text-white bg-tertiary" href="#chatnowModal" onclick="showPanel()">Chat now</a>
                <a class="btn btn-primary btn-lg text-white bg-secondary" href="/getintouch/billing-software-pricing">Send email</a>
            </div>
        </div>
    </div>
</section>
@endsection
