@extends('home.master')

@section('content')
<section class="jumbotron jumbotron-features bg-transparent py-5" id="header">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h1>Lendingkart benefits with Swipez</h1>
                {{-- <h2> </h2> --}}
                <h5>With cutting-edge technological solutions based on big data analysis, Lendingkart offers lenders viable credit risk assessment for their borrowers
                </h5>
            </div>
            <div class="col-md-6 center-text">
                <img class="imgs" alt="swipez benefits_lending_kart" style="max-width: 50%;" src="{!! asset('images/benefits/lendingkart.png?id=v1')!!}">
                
                <h5 class="center-text mt-2">  Hassle-free business loans from Lendingkart
                </h5>
                {{-- <h5 class="center-text">Register your Swipez account — it’s free!
                </h5>
                <p class="center-text">Swipez products help you digitize your business!</p> --}}

                @include('home.product.web_register',['d_type' => "web"])
            </div>
        </div>

    </div>
</section>
<section class="jumbotron bg-secondary py-5" id="cta">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-12 mb-5">
              
                <h2 class="text-white">Get business loans upto ₹1 Crore in less than 24 hours with Swipez</h2>
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
                            <img alt="Swipez Benefits About Lending-Kart" src="{!! asset('images/benefits/Swipez_Benefits_About.svg') !!}" width="640" height="360" loading="lazy" class="lazyload" />
                        </div>
                        <div class="col-md-6 px-3 d-none d-lg-block">
                            <h2 class="pb-3">
                                <b>About Lendingkart
                                </b>
                            </h2>
                            <p>An RBI-registered NBFC, Lendingkart aims to provide working capital loans to businesses so they may focus on their business rather than worrying about cash flow gaps. Lendingkart has created technological solutions based on big data analysis to make it easier for lenders to assess a borrower's creditworthiness and to offer other relevant services. Over 150k+ MSMEs across the nation have received loans from Lendingkart. </p>
                        </div>
                    </div>
                    <!-- for iPad and mobile -->
                    <div class="row">
                        <div class="col-md-6 d-lg-none mb-3">
                            <img alt="Swipez Benefits About Lending-Kart" src="{!! asset('images/benefits/Swipez_Benefits_About.svg') !!}" width="640" height="360" loading="lazy" class="lazyload" />
                        </div>
                        <div class="col-md-6 d-lg-none">
                            <h2 class="py-3 ">
                                <b>About Lendingkart</b>
                            </h2>
                            <p>An RBI-registered NBFC, Lendingkart aims to provide working capital loans to businesses so they may focus on their business rather than worrying about cash flow gaps. Lendingkart has created technological solutions based on big data analysis to make it easier for lenders to assess a borrower's creditworthiness and to offer other relevant services. Over 150k+ MSMEs across the nation have received loans from Lendingkart. </p>
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
                                <b>Benefits of the Lendingkart business loan</b>
                            </h2>
                            <ul class="list-unstyled">
                                <li class="lead text-white"> <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" class="h-6 pr-1" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                    <path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                                    </path>
                                    </svg>
                                    Get business loans upto ₹1 Crore in less than 24 hours with Swipez
                                </li>
                                <li class="lead text-white"> <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" class="h-6 pr-1" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                    <path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                                    </path>
                                    </svg>
                                    Ensure seamless growth with Lendingkart
                                </li>
                                <li class="lead text-white"> <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" class="h-6 pr-1" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                        <path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                                        </path>
                                    </svg>
                                    Business loans
                                </li>
                                <li class="lead text-white"> <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" class="h-6 pr-1" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                        <path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                                        </path>
                                    </svg>
                                    Working capital loans
                                </li>
                                <li class="lead text-white"> <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" class="h-6 pr-1" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                        <path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                                        </path>
                                    </svg>
                                    MSME loans
                                </li>
                                <li class="lead text-white"> <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" class="h-6 pr-1" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                        <path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                                        </path>
                                    </svg>
                                    Business loans for female-led MSMEs
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6 d-none d-lg-block">
                            <img alt="Swipez Benefits Lending-Kart offer" src="{!! asset('images/benefits/Swipez_Benefits_offer.svg') !!}" width="640" height="360" loading="lazy" class="lazyload" />
                        </div>
                    </div>
                    <!-- for iPad and mobile -->
                    <div class="row text-white">
                        <div class="col-md-6 d-lg-none mb-3">
                            <img alt="Swipez Benefits Lending-Kart offer" src="{!! asset('images/benefits/Swipez_Benefits_offer.svg') !!}" width="640" height="360" loading="lazy" class="lazyload" />
                        </div>
                        <div class="col-md-6 d-lg-none">
                            <h2 class="py-3 text-center">
                                <b>Benefits of the Lendingkart business loan</b>
                            </h2>
                            <ul class="list-unstyled pb-2">
                                <li class="lead text-white"> <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" class="h-6 pr-1" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                    <path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                                    </path>
                                    </svg>
                                    Get business loans upto ₹1 Crore in less than 24 hours with Swipez
                                </li>
                                <li class="lead text-white"> <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" class="h-6 pr-1" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                    <path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                                    </path>
                                    </svg>
                                    Ensure seamless growth with Lendingkart
                                </li>
                                <li class="lead text-white"> <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" class="h-6 pr-1" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                        <path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                                        </path>
                                    </svg>
                                    Business loans
                                </li>
                                <li class="lead text-white"> <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" class="h-6 pr-1" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                        <path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                                        </path>
                                    </svg>
                                    Working capital loans
                                </li>
                                <li class="lead text-white"> <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" class="h-6 pr-1" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                        <path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                                        </path>
                                    </svg>
                                    MSME loans</li>
                                <li class="lead text-white"> <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" class="h-6 pr-1" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                        <path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                                        </path>
                                    </svg>
                                    Business loans for female-led MSMEs</li>
                                   
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
