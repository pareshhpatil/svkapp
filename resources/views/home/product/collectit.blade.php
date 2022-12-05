@extends('home.master')
@section('title', 'Free invoice software with payment collections and payment reminders')
@section('content')
<section class="jumbotron jumbotron-features bg-transparent py-7" id="header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12 col-md-12 col-lg-6 pb-5 mr-lg-4">
                <img alt="Free billing app screen shots"
                    class="img-fluid block relative mx-auto lg:ml-auto w-[90%] z-[10]"
                    src="{!! asset('images/product/billing-app/billing-app.png') !!}" width="640" height="360" />
                <div class="absolute w-full h-full left-[0] top-[0] z-[0]">
                    <div class="absolute bg-primary rounded-full"
                        style="left: 30px; top: -10px; height: 220px; width: 220px; border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%; background-image: linear-gradient(45deg, #275770 0%, #18AEBF 100%);">
                    </div>
                    <div class="absolute bg-primary rounded-full"
                        style="right: 10px; top: -1px; height: 40px; width: 40px; border-radius: 45% 55% / 35% 34% 66% 65%; background-image: linear-gradient(45deg, #275770 0%, #18AEBF 100%);">
                    </div>
                    <div class="absolute bg-primary rounded-full"
                        style="top: 290px; left: -40px; height: 80px; width: 80px; border-radius: 66% 34% 60% 40% / 69% 62% 38% 31%; background-image: linear-gradient(45deg, #275770 0%, #18AEBF 100%);">
                    </div>
                    <div class="absolute bg-primary rounded-full d-none d-lg-block"
                        style="left: 30%; bottom: 0px; height: 165px; width: 165px; border-radius: 30% 70% 69% 31% / 30% 63% 37% 70%; background-image: linear-gradient(45deg, #275770 0%, #18AEBF 100%);">
                    </div>
                    <div class="absolute bg-primary rounded-full"
                        style="bottom: 75px; right: 50px; height: 50px; width: 50px; border-radius: 59% 41% 37% 63% / 46% 63% 37% 54%; background-image: linear-gradient(45deg, #275770 0%, #18AEBF 100%);">
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-12 col-lg-6 col-xl-5 ml-4 mr-4">
                <!--<h1>Billing Software</h1>-->
                <h1>Collect it - Billing and online payment collection app</h1>
                <p class="lead mb-2">A simple and easy to use billing app. Send bills to your contacts in your phone
                    book and start collecting payments online or offline. Collecting payments from your customers was
                    never this
                    easy!</p>

                <div class="row col-12 justify-center items-center">
                    <div class="col-md-8 col-lg-6 mt-8 md:mt-12 pt-4">
                        <div class="px-12 items-center justify-center bg-secondary rounded-full">
                            <a href="https://apps.apple.com/in/app/collect-it-billing-payments/id1583224355"
                                title="Go to App Store">
                                <center><img width="200" src="/images/product/billing-app/apple-app-store.svg"
                                        alt="apple app store logo"></center>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-8 col-lg-6 mt-8 md:mt-12 pt-4">
                        <div class="px-12 items-center justify-center items-center bg-primary rounded-full">
                            <a href="https://play.google.com/store/apps/details?id=com.swipez.collectit"
                                title="Go to Google Play">
                                <center><img width="200"
                                        src="{!! asset('/images/product/billing-app/google-play-store.svg') !!}"
                                        alt="google play logo"></center>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
<section class="jumbotron py-3" id="cta">
    <div class="container">
        <div class="row text-center">
            <div class="col-12 col-md-9 mx-auto my-3">
                <h2 class="pb-2 display-4">Free billing app</h2>
                <p class="lead mb-1">Learn how our billing app can help you collect payments faster!</p>
                <div>
                    <div id="video-promo-container"
                        style="max-width:100%;position:relative;padding-top:62.25%;cursor:pointer;">
                        <div id="video-play-button"
                            style="position:absolute; top:0px; left:0px; right:0px; bottom:0px; z-index:400">
                            <div style="position:absolute; top:0%; left:0%; width:100%">
                                <picture>
                                    <!--source title="Click to watch overview video"
                                        srcset="{!! asset('images/product/billing-software/dashboard.webp') !!}"
                                        type="image/webp" alt="Billing software explained" class="img-fluid" width="825"
                                        height="464" />
                                    <source title="Click to watch overview video"
                                        srcset="{!! asset('images/product/billing-software/dashboard.png') !!}"
                                        type="image/jpeg" alt="Billing software explained" class="img-fluid" width="825"
                                        height="464" /-->
                                    <img loading="lazy" title="Billing app overview video"
                                        src="{!! asset('images/product/billing-app/app-youtube-cover.jpeg') !!}"
                                        alt="Billing software explained" class="img-shadow img-fluid" width="825"
                                        height="464" loading="lazy" class="lazyload" />
                                </picture>
                            </div>
                            <span style="position:absolute; margin-top:-40px; margin-left:-30px; top:45%; left:48%">
                                <svg aria-hidden="true" focusable="false" data-prefix="fab" data-icon="youtube"
                                    class="h-16 pb-2" role="img" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 576 512"
                                    style="color: #ff0000; filter: drop-shadow(0px 3px 3px rgb(71, 71, 71, 1));">
                                    <path fill="currentColor"
                                        d="M549.655 124.083c-6.281-23.65-24.787-42.276-48.284-48.597C458.781 64 288 64 288 64S117.22 64 74.629 75.486c-23.497 6.322-42.003 24.947-48.284 48.597-11.412 42.867-11.412 132.305-11.412 132.305s0 89.438 11.412 132.305c6.281 23.65 24.787 41.5 48.284 47.821C117.22 448 288 448 288 448s170.78 0 213.371-11.486c23.497-6.321 42.003-24.171 48.284-47.821 11.412-42.867 11.412-132.305 11.412-132.305s0-89.438-11.412-132.305zm-317.51 213.508V175.185l142.739 81.205-142.739 81.201z">
                                    </path>
                                </svg>
                            </span>
                        </div>
                        <div id="video-text" class="w-100"
                            style="position:absolute; bottom:0px; left:0px; right:0px; z-index:400">
                            <p class="lead text-center font-weight-bold font-italic text-primary d-none">See Swipez
                                Billing in
                                action</p>
                        </div>
                        <div id="youtube-video" class="pb-3"></div>
                    </div>

                    <div class="col mx-auto">
                        <div class="card">
                            <div class="card-body p-5">
                                <div class="row">
                                    <div class="col-12 mb-4 mb-sm-0 text-center">
                                        <h5>{{env('SWIPEZ_BIZ_NUM')}} happy businesses use Swipez products everyday!
                                        </h5>
                                    </div>
                                    <div class="col-md-8 col-lg-6 text-center pt-4">
                                        <div
                                            class="px-4 items-center justify-center items-center bg-secondary rounded-full">
                                            <a href="https://apps.apple.com/in/app/collect-it-billing-payments/id1583224355"
                                                title="Go to App Store">
                                                <center><img width="200"
                                                        src="/images/product/billing-app/apple-app-store.svg"
                                                        alt="apple app store logo"></center>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-lg-6 text-center pt-4">
                                        <div
                                            class="px-4 items-center justify-center items-center bg-primary rounded-full">
                                            <a href="https://play.google.com/store/apps/details?id=com.swipez.collectit"
                                                title="Go to Google Play">
                                                <center><img loading="lazy" width="200"
                                                        src="{!! asset('/images/product/billing-app/google-play-store.svg') !!}"
                                                        alt="google play logo"></center>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="jumbotron bg-transparent py-5" id="Comparision">
    <div class="container">
        <div class="row text-center justify-content-center">
            <div class="col-md-10 col-lg-8 col-xl-7">
                <h2 class="display-4">Alternative to UPI apps</h2>
                <h3 class="lead">Collect it billing app is a better choice than Phone Pe, Google Pay and
                    PayTM UPI apps</h3>
            </div>
        </div>
        <div class="row align-items-center justify-content-center">
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
            <svg style="display: none" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" class=""
                role="img" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <symbol id="cross">
                        <path fill="currentColor"
                            d="M242.72 256l100.07-100.07c12.28-12.28 12.28-32.19 0-44.48l-22.24-22.24c-12.28-12.28-32.19-12.28-44.48 0L176 189.28 75.93 89.21c-12.28-12.28-32.19-12.28-44.48 0L9.21 111.45c-12.28 12.28-12.28 32.19 0 44.48L109.28 256 9.21 356.07c-12.28 12.28-12.28 32.19 0 44.48l22.24 22.24c12.28 12.28 32.2 12.28 44.48 0L176 322.72l100.07 100.07c12.28 12.28 32.2 12.28 44.48 0l22.24-22.24c12.28-12.28 12.28-32.19 0-44.48L242.72 256z">
                        </path>
                    </symbol>
                </defs>
            </svg>
            <svg style="display: none" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" class=""
                role="img" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <symbol id="info">
                        <path fill="currentColor"
                            d="M256 8C119.043 8 8 119.083 8 256c0 136.997 111.043 248 248 248s248-111.003 248-248C504 119.083 392.957 8 256 8zm0 110c23.196 0 42 18.804 42 42s-18.804 42-42 42-42-18.804-42-42 18.804-42 42-42zm56 254c0 6.627-5.373 12-12 12h-88c-6.627 0-12-5.373-12-12v-24c0-6.627 5.373-12 12-12h12v-64h-12c-6.627 0-12-5.373-12-12v-24c0-6.627 5.373-12 12-12h64c6.627 0 12 5.373 12 12v100h12c6.627 0 12 5.373 12 12v24z">
                        </path>
                    </symbol>
                </defs>
            </svg>
            <!-- comparison table -->
            <div class="container">
                <table class="table text-center mt-4 d-none d-lg-table">
                    <tbody>
                        <tr>
                            <th scope="row" class="border-0 bg-primary text-white">
                                <h2 class="font-weight-light">Compare</h2>
                            </th>
                            <td class="text-center border-0 bg-primary text-white">
                                <h2 class="font-weight-light">Google Pay, PhonePe, PayTM</h2>
                            </td>
                            <td class="text-center border-0 bg-primary text-white">
                                <h2 class="font-weight-light">Collect it - Billing app</h2>
                            </td>
                        </tr>
                        <tr style="background-color: #f3fbfe">
                            <th scope="row">Payment collection modes
                                <div class="simpletooltip">
                                    <svg class="h-4 text-primary pl-2" viewBox="0 0 512 512">
                                        <use href="#info" />
                                    </svg>
                                    <span class="simpletooltiptext">Different payment options provided to your customers
                                        to make payments</span>
                                </div>
                            </th>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <th scope="row">UPI</th>
                            <td><svg class="h-6 text-primary" viewBox="0 0 512 512">
                                    <use href="#tickmark" /></svg></td>
                            <td><svg class="h-6 text-primary" viewBox="0 0 512 512">
                                    <use href="#tickmark" /></svg></td>
                        </tr>
                        <tr>
                            <th scope="row">Credit card</th>
                            <td><svg class="h-6 gray-400" viewBox="0 0 512 512">
                                    <use href="#cross" /></svg></td>
                            <td><svg class="h-6 text-primary" viewBox="0 0 512 512">
                                    <use href="#tickmark" /></svg></td>
                        </tr>
                        <tr>
                            <th scope="row">Debit card</th>
                            <td><svg class="h-6 gray-400" viewBox="0 0 512 512">
                                    <use href="#cross" /></svg></td>
                            <td><svg class="h-6 text-primary" viewBox="0 0 512 512">
                                    <use href="#tickmark" /></svg></td>
                        </tr>
                        <tr>
                            <th scope="row">Net banking</th>
                            <td><svg class="h-6 gray-400" viewBox="0 0 512 512">
                                    <use href="#cross" /></svg></td>
                            <td><svg class="h-6 text-primary" viewBox="0 0 512 512">
                                    <use href="#tickmark" /></svg></td>
                        </tr>
                        <tr>
                            <th scope="row">Wallets</th>
                            <td><svg class="h-6 gray-400" viewBox="0 0 512 512">
                                    <use href="#cross" /></svg></td>
                            <td><svg class="h-6 text-primary" viewBox="0 0 512 512">
                                    <use href="#tickmark" /></svg></td>
                        </tr>
                        <tr style="background-color: #f3fbfe">
                            <th scope="row">App features</th>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <th scope="row">Send bills
                                <div class="simpletooltip">
                                    <svg class="h-4 text-primary pl-2" viewBox="0 0 512 512">
                                        <use href="#info" />
                                    </svg>
                                    <span class="simpletooltiptext">Send a bill to your customer via SMS and
                                        Email</span>
                                </div>
                            </th>
                            <td><svg class="h-6 gray-400" viewBox="0 0 512 512">
                                    <use href="#cross" /></svg></td>
                            <td><svg class="h-6 text-primary" viewBox="0 0 512 512">
                                    <use href="#tickmark" /></svg></td>
                        </tr>
                        <tr>
                            <th scope="row">Bill reminders
                                <div class="simpletooltip">
                                    <svg class="h-4 text-primary pl-2" viewBox="0 0 512 512">
                                        <use href="#info" />
                                    </svg>
                                    <span class="simpletooltiptext">Automatically remind customers of unpaid bills via
                                        SMS and Email</span>
                                </div>
                            </th>
                            <td><svg class="h-6 gray-400" viewBox="0 0 512 512">
                                    <use href="#cross" /></svg></td>
                            <td><svg class="h-6 text-primary" viewBox="0 0 512 512">
                                    <use href="#tickmark" /></svg></td>
                        </tr>
                        <tr>
                            <th scope="row">Send an invoice
                                <div class="simpletooltip">
                                    <svg class="h-4 text-primary pl-2" viewBox="0 0 512 512">
                                        <use href="#info" />
                                    </svg>
                                    <span class="simpletooltiptext">Create and send an invoice to your customer via SMS
                                        and Email</span>
                                </div>
                            </th>
                            <td><svg class="h-6 gray-400" viewBox="0 0 512 512">
                                    <use href="#cross" /></svg></td>
                            <td><svg class="h-6 text-primary" viewBox="0 0 512 512">
                                    <use href="#tickmark" /></svg></td>
                        </tr>
                        <tr>
                            <th scope="row">Customer data
                                <div class="simpletooltip">
                                    <svg class="h-4 text-primary pl-2" viewBox="0 0 512 512">
                                        <use href="#info" />
                                    </svg>
                                    <span class="simpletooltiptext">Maintain all your customer contact information in
                                        one place</span>
                                </div>
                            </th>
                            <td><svg class="h-6 gray-400" viewBox="0 0 512 512">
                                    <use href="#cross" /></svg></td>
                            <td><svg class="h-6 text-primary" viewBox="0 0 512 512">
                                    <use href="#tickmark" /></svg></td>
                        </tr>
                        <tr>
                            <th scope="row">Track all payments
                                <div class="simpletooltip">
                                    <svg class="h-4 text-primary pl-2" viewBox="0 0 512 512">
                                        <use href="#info" />
                                    </svg>
                                    <span class="simpletooltiptext">Track all your incoming payments collected either
                                        online or offline</span>
                                </div>
                            </th>
                            <td><svg class="h-6 gray-400" viewBox="0 0 512 512">
                                    <use href="#cross" /></svg></td>
                            <td><svg class="h-6 text-primary" viewBox="0 0 512 512">
                                    <use href="#tickmark" /></svg></td>
                        </tr>
                        <tr>
                            <th scope="row">Web browser login
                                <div class="simpletooltip">
                                    <svg class="h-4 text-primary pl-2" viewBox="0 0 512 512">
                                        <use href="#info" />
                                    </svg>
                                    <span class="simpletooltiptext">Manage all your collections and billing via a web
                                        browser on the desktop or laptop</span>
                                </div>
                            </th>
                            <td><svg class="h-6 gray-400" viewBox="0 0 512 512">
                                    <use href="#cross" /></svg></td>
                            <td><svg class="h-6 text-primary" viewBox="0 0 512 512">
                                    <use href="#tickmark" /></svg></td>
                        </tr>
                    </tbody>
                </table>
                <table class="table text-center mt-5 d-table d-lg-none">
                    <tbody>
                        <tr class="bg-primary text-white">
                            <td class="text-center" colspan="2">
                                <h2 class="font-weight-light">Google Pay, PhonePe, PayTM</h2>
                            </td>
                        </tr>
                        <tr style="background-color: #f3fbfe">
                            <th scope="row" colspan="2">Payment collection modes
                                <div class="simpletooltip">
                                    <svg class="h-4 text-primary pl-2" viewBox="0 0 512 512">
                                        <use href="#info" />
                                    </svg>
                                    <span class="simpletooltiptext">Different payment options provided to your customers
                                        to make payments</span>
                                </div>
                            </th>
                        </tr>
                        <tr>
                            <th scope="row">UPI payments</th>
                            <td><svg class="h-6 text-primary" viewBox="0 0 512 512">
                                    <use href="#tickmark" /></svg></td>
                        </tr>
                        <tr>
                            <th scope="row">Credit card</th>
                            <td><svg class="h-6 gray-400" viewBox="0 0 512 512">
                                    <use href="#cross" /></svg></td>
                        </tr>
                        <tr>
                            <th scope="row">Debit card</th>
                            <td><svg class="h-6 gray-400" viewBox="0 0 512 512">
                                    <use href="#cross" /></svg></td>
                        </tr>
                        <tr>
                            <th scope="row">Net banking</th>
                            <td><svg class="h-6 gray-400" viewBox="0 0 512 512">
                                    <use href="#cross" /></svg></td>
                        </tr>
                        <tr>
                            <th scope="row">Wallets</th>
                            <td><svg class="h-6 gray-400" viewBox="0 0 512 512">
                                    <use href="#cross" /></svg></td>
                        </tr>
                        <tr style="background-color: #f3fbfe">
                            <th scope="row" colspan="2">App features</th>
                        </tr>
                        <tr>
                            <th scope="row">Send bills
                                <div class="simpletooltip">
                                    <svg class="h-4 text-primary pl-2" viewBox="0 0 512 512">
                                        <use href="#info" />
                                    </svg>
                                    <span class="simpletooltiptext">Send a bill to your customer via SMS and
                                        Email</span>
                                </div>
                            </th>
                            <td><svg class="h-6 gray-400" viewBox="0 0 512 512">
                                    <use href="#cross" /></svg></td>
                        </tr>
                        <tr>
                            <th scope="row">Bill reminders
                                <div class="simpletooltip">
                                    <svg class="h-4 text-primary pl-2" viewBox="0 0 512 512">
                                        <use href="#info" />
                                    </svg>
                                    <span class="simpletooltiptext">Automatically remind customers of unpaid bills via
                                        SMS and Email</span>
                                </div>
                            </th>
                            <td><svg class="h-6 gray-400" viewBox="0 0 512 512">
                                    <use href="#cross" /></svg></td>
                        </tr>
                        <tr>
                            <th scope="row">Send an invoice
                                <div class="simpletooltip">
                                    <svg class="h-4 text-primary pl-2" viewBox="0 0 512 512">
                                        <use href="#info" />
                                    </svg>
                                    <span class="simpletooltiptext">Create and send an invoice to your customer via SMS
                                        and Email</span>
                                </div>
                            </th>
                            <td><svg class="h-6 gray-400" viewBox="0 0 512 512">
                                    <use href="#cross" /></svg></td>
                        </tr>
                        <tr>
                            <th scope="row">Customer data
                                <div class="simpletooltip">
                                    <svg class="h-4 text-primary pl-2" viewBox="0 0 512 512">
                                        <use href="#info" />
                                    </svg>
                                    <span class="simpletooltiptext">Maintain all your customer contact information in
                                        one place</span>
                                </div>
                            </th>
                            <td><svg class="h-6 gray-400" viewBox="0 0 512 512">
                                    <use href="#cross" /></svg></td>
                        </tr>
                        <tr>
                            <th scope="row">Track all payments
                                <div class="simpletooltip">
                                    <svg class="h-4 text-primary pl-2" viewBox="0 0 512 512">
                                        <use href="#info" />
                                    </svg>
                                    <span class="simpletooltiptext">Track all your incoming payments collected either
                                        online or offline</span>
                                </div>
                            </th>
                            <td><svg class="h-6 gray-400" viewBox="0 0 512 512">
                                    <use href="#cross" /></svg></td>
                        </tr>
                        <tr>
                            <th scope="row">Web browser login
                                <div class="simpletooltip">
                                    <svg class="h-4 text-primary pl-2" viewBox="0 0 512 512">
                                        <use href="#info" />
                                    </svg>
                                    <span class="simpletooltiptext">Manage all your collections and billing via a web
                                        browser on the desktop or laptop</span>
                                </div>
                            </th>
                            <td><svg class="h-6 gray-400" viewBox="0 0 512 512">
                                    <use href="#cross" /></svg></td>
                        </tr>
                    </tbody>
                </table>
                <table class="table text-center mt-5 d-table d-lg-none">
                    <tbody>
                        <tr class="bg-primary text-white">
                            <td class="text-center" colspan="2">
                                <h2 class="font-weight-light">Collect it - Billing app</h2>
                            </td>
                        </tr>
                        <tr style="background-color: #f3fbfe">
                            <th scope="row" colspan="2">Payment collection modes
                                <div class="simpletooltip">
                                    <svg class="h-4 text-primary pl-2" viewBox="0 0 512 512">
                                        <use href="#info" />
                                    </svg>
                                    <span class="simpletooltiptext">Different payment options provided to your customers
                                        to make payments</span>
                                </div>
                            </th>
                        </tr>
                        <tr>
                            <th scope="row">UPI payments</th>
                            <td><svg class="h-6 text-primary" viewBox="0 0 512 512">
                                    <use href="#tickmark" /></svg></td>
                        </tr>
                        <tr>
                            <th scope="row">Credit card</th>
                            <td><svg class="h-6 text-primary" viewBox="0 0 512 512">
                                    <use href="#tickmark" /></svg></td>
                        </tr>
                        <tr>
                            <th scope="row">Debit card</th>
                            <td><svg class="h-6 text-primary" viewBox="0 0 512 512">
                                    <use href="#tickmark" /></svg></td>
                        </tr>
                        <tr>
                            <th scope="row">Net banking</th>
                            <td><svg class="h-6 text-primary" viewBox="0 0 512 512">
                                    <use href="#tickmark" /></svg></td>
                        </tr>
                        <tr>
                            <th scope="row">Wallets</th>
                            <td><svg class="h-6 text-primary" viewBox="0 0 512 512">
                                    <use href="#tickmark" /></svg></td>
                        </tr>
                        <tr style="background-color: #f3fbfe">
                            <th scope="row" colspan="2">App features</th>
                        </tr>
                        <tr>
                            <th scope="row">Send bills
                                <div class="simpletooltip">
                                    <svg class="h-4 text-primary pl-2" viewBox="0 0 512 512">
                                        <use href="#info" />
                                    </svg>
                                    <span class="simpletooltiptext">Send a bill to your customer via SMS and
                                        Email</span>
                                </div>
                            </th>
                            <td><svg class="h-6 text-primary" viewBox="0 0 512 512">
                                    <use href="#tickmark" /></svg></td>
                        </tr>
                        <tr>
                            <th scope="row">Bill reminders
                                <div class="simpletooltip">
                                    <svg class="h-4 text-primary pl-2" viewBox="0 0 512 512">
                                        <use href="#info" />
                                    </svg>
                                    <span class="simpletooltiptext">Automatically remind customers of unpaid bills via
                                        SMS and Email</span>
                                </div>
                            </th>
                            <td><svg class="h-6 text-primary" viewBox="0 0 512 512">
                                    <use href="#tickmark" /></svg></td>
                        </tr>
                        <tr>
                            <th scope="row">Send an invoice
                                <div class="simpletooltip">
                                    <svg class="h-4 text-primary pl-2" viewBox="0 0 512 512">
                                        <use href="#info" />
                                    </svg>
                                    <span class="simpletooltiptext">Create and send an invoice to your customer via SMS
                                        and Email</span>
                                </div>
                            </th>
                            <td><svg class="h-6 text-primary" viewBox="0 0 512 512">
                                    <use href="#tickmark" /></svg></td>
                        </tr>
                        <tr>
                            <th scope="row">Customer data
                                <div class="simpletooltip">
                                    <svg class="h-4 text-primary pl-2" viewBox="0 0 512 512">
                                        <use href="#info" />
                                    </svg>
                                    <span class="simpletooltiptext">Maintain all your customer contact information in
                                        one place</span>
                                </div>
                            </th>
                            <td><svg class="h-6 text-primary" viewBox="0 0 512 512">
                                    <use href="#tickmark" /></svg></td>
                        </tr>
                        <tr>
                            <th scope="row">Track all payments
                                <div class="simpletooltip">
                                    <svg class="h-4 text-primary pl-2" viewBox="0 0 512 512">
                                        <use href="#info" />
                                    </svg>
                                    <span class="simpletooltiptext">Track all your incoming payments collected either
                                        online or offline</span>
                                </div>
                            </th>
                            <td><svg class="h-6 text-primary" viewBox="0 0 512 512">
                                    <use href="#tickmark" /></svg></td>
                        </tr>
                        <tr>
                            <th scope="row">Web browser login
                                <div class="simpletooltip">
                                    <svg class="h-4 text-primary pl-2" viewBox="0 0 512 512">
                                        <use href="#info" />
                                    </svg>
                                    <span class="simpletooltiptext">Manage all your collections and billing via a web
                                        browser on the desktop or laptop</span>
                                </div>
                            </th>
                            <td><svg class="h-6 text-primary" viewBox="0 0 512 512">
                                    <use href="#tickmark" /></svg></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- end of comparison table -->
        </div>

        <div class="col col-8 mx-auto">
            <div class="row text-center justify-content-center">
                <h3 class="lead">Free billing and payment collections app for business owners</h3>
                <div class="col-md-8 col-lg-6 text-center pt-4">
                    <div class="px-4 items-center justify-center items-center bg-secondary rounded-full">
                        <a href="https://apps.apple.com/in/app/collect-it-billing-payments/id1583224355"
                            title="Go to App Store">
                            <center><img width="200" src="/images/product/billing-app/apple-app-store.svg"
                                    alt="apple app store logo"></center>
                        </a>
                    </div>
                </div>
                <div class="col-md-8 col-lg-6 text-center pt-4">
                    <div class="px-4 items-center justify-center items-center bg-primary rounded-full">
                        <a href="https://play.google.com/store/apps/details?id=com.swipez.collectit"
                            title="Go to Google Play">
                            <center><img loading="lazy" width="200"
                                    src="{!! asset('/images/product/billing-app/google-play-store.svg') !!}"
                                    alt="google play logo"></center>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="jumbotron py-5" id="testimonials">
    <div class="container">
        <div class="row text-center justify-content-center pb-5">
            <div class="col-md-10 col-lg-8 col-xl-7">
                <h2 class="display-4">Testimonials</h2>
                <p class="lead">You are in good company. Join {{env('SWIPEZ_BIZ_NUM')}} happy businesses who are already
                    using Swipez products</p>
            </div>
        </div>
        <div class="row align-items-center justify-content-center">
            <div class="col-lg-9 col-xl-6">
                <div class="card p-5">
                    <div class="row">
                        <div class="col-8 col-sm-6 col-md-4 col-xl-3 ml-auto mr-auto">
                            <img loading="lazy" alt="Billing app clients picture" class="img-fluid rounded"
                                src="{!! asset('images/product/billing-software/swipez-client1.jpg') !!}" width="166"
                                height="185" loading="lazy" class="lazyload" />
                        </div>
                        <div class="col-md-8 mt-4 mt-md-0">
                            <p>"Now we send the monthly internet bills to our customers at the click of a
                                button. Customers receive bills on e-mail and SMS with multiple online payment options.
                                Payments collected online and offline are all reconciled with Collect it app."</p>
                            <p class="h3 mt-4 mt-xl-5">
                                <strong>Jayesh Shah</strong>
                            </p>
                            <p>
                                <em>Founder, Shah Solutions</em>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-9 col-xl-6 mt-4 mt-xl-0">
                <div class="card p-5">
                    <div class="row">
                        <div class="col-8 col-sm-6 col-md-4 col-xl-3 ml-auto mr-auto">
                            <img loading="lazy" alt="Billing app clients picture" class="img-fluid rounded"
                                src="{!! asset('images/product/billing-software/swipez-client2.jpg') !!}" width="166"
                                height="185" loading="lazy" class="lazyload" />
                        </div>
                        <div class="col-md-8 mt-4 mt-md-0">
                            <p>"We are now managing payment collections across our complete customer base. My team has
                                saved over hundred hours after adopting Collect it app. This is the easiest way to start
                                payment collections from your customers within minutes."</p>
                            <p class="h3 mt-4 mt-xl-5">
                                <strong>Vikas Sankhla</strong>
                            </p>
                            <p>
                                <em>Co-founder, Car Nanny</em>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="jumbotron py-5 bg-secondary text-white" id="cta">
    <div class="container">
        <div class="row text-center justify-content-center">
            <div class="col-md-12">
                <h2>The perfect free billing app for the small business owner</h2>
            </div>
            <div class="col-8 col-md-4 md:ml-6 mt-8 md:mt-12 pt-4">
                <div class="px-4 items-center justify-center bg-primary rounded-full">
                    <a href="https://apps.apple.com/in/app/collect-it-billing-payments/id1583224355"
                        title="Go to App Store">
                        <center><img width="200" src="/images/product/billing-app/apple-app-store.svg"
                                alt="apple app store logo"></center>
                    </a>
                </div>
            </div>
            <div class="col-8 col-md-4 md:ml-6 mt-8 md:mt-12 pt-4">
                <div class="px-4 items-center justify-center bg-primary rounded-full">
                    <a href="https://play.google.com/store/apps/details?id=com.swipez.collectit"
                        title="Go to Google Play">
                        <center><img width="200"
                                src="{!! asset('/images/product/billing-app/google-play-store.svg') !!}"
                                alt="google play logo"></center>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="jumbotron bg-transparent py-5" id="features">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 text-center">
                <h2 class="display-4">Billing App Features</h2>
            </div>
        </div>
        <div class="row text-left align-items-center pt-5 pb-md-5">
            <div class="col-4 col-md-5 d-none d-lg-block justify-center items-center plus-background">
                <center>
                    <img loading="lazy" alt="Billing app dashboard example" class="img-fluid"
                        src="{!! asset('/images/product/billing-app/features/billing-app-dashboard.jpg') !!}" />
                </center>
            </div>
            <div class="col-12 col-md-5 m-md-auto d-none d-lg-block">
                <h2><strong>Your collections dashboard</strong></h2>
                <p class="lead">Our dashboard helps you stay on top of all your collections and dues every day. Coupled
                    with a monthly sales tracker that shows your business progress month on month</p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4 plus-background">
                <center>
                    <img loading="lazy" alt="Billing app dashboard example" class="img-fluid"
                        src="{!! asset('/images/product/billing-app/features/billing-app-dashboard.jpg') !!}" />
                </center>
            </div>
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>Your collections dashboard</strong></h2>
                <p class="lead">Our dashboard helps you stay on top of all your collections and dues every day. Coupled
                    with a monthly sales tracker that shows your business progress month on month</p>
            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5 pb-md-5">
            <div class="col-4 col-md-5 m-md-auto order-md-5 d-none d-lg-block plus-background">
                <center>
                    <img loading="lazy" alt="Send bills to phone contacts" class="img-fluid"
                        src="{!! asset('/images/product/billing-app/features/send-bills-to-contacts.jpg') !!}" />
                </center>
            </div>
            <div class="col-12 col-md-5 d-none d-lg-block">
                <h2><strong>Bill your contacts</strong></h2>
                <p class="lead">Billing your phone contacts was never this easy!
                    <ol>
                        <li>Select your contact</li>
                        <li>Enter the amount</li>
                        <li>Hit Send</li>
                    </ol>
                    Your customer receive your bill on Email, SMS & WhatsApp, with online payment options! 🚀💸🙌
                </p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4 plus-background">
                <center>
                    <img loading="lazy" alt="Send bills to phone contacts" class="img-fluid"
                        src="{!! asset('/images/product/billing-app/features/send-bills-to-contacts.jpg') !!}" />
                </center>
            </div>
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>Bill your contacts</strong></h2>
                <p class="lead">Billing your phone contacts was never this easy!
                    <ol>
                        <li>Select your contact</li>
                        <li>Enter the amount</li>
                        <li>Hit Send</li>
                    </ol>
                    Your customer receive your bill on Email, SMS & WhatsApp, with online payment options! 🚀💸🙌
                </p>
            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5">
            <div class="col-4 col-md-5 d-none d-lg-block plus-background">
                <center>
                    <img loading="lazy" alt="Simple and beautiful bill format" class="img-fluid"
                        src="{!! asset('/images/product/billing-app/features/simple-bill-format.jpg') !!}" />
                </center>
            </div>
            <div class="col-12 col-md-5 m-md-auto d-none d-lg-block">
                <h2><strong>Bills with automated payment reminders and online payments</strong></h2>
                <p class="lead">Bills created with your company name front & centre, along with the bill due date, a
                    message from your end & the amount due. Send good looking bills to your customers with online
                    payment options like:</p>
                <ol>
                    <li>UPI</li>
                    <li>Wallets</li>
                    <li>Debit card</li>
                    <li>Credit card</li>
                    <li>Net banking</li>
                </ol>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4 plus-background">
                <center>
                    <img loading="lazy" alt="Simple and beautiful bill format" class="img-fluid"
                        src="{!! asset('/images/product/billing-app/features/simple-bill-format.jpg') !!}" />
                </center>
            </div>
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>Bills with automated payment reminders and online payments</strong></h2>
                <p class="lead">Bills created with your company name front & centre, along with the bill due date, a
                    message from your end & the amount due. Send good looking bills to your customers with online
                    payment options like:</p>
                <ol>
                    <li>UPI</li>
                    <li>Wallets</li>
                    <li>Debit card</li>
                    <li>Credit card</li>
                    <li>Net banking</li>
                </ol>
            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5 pb-md-5">
            <div class="col-4 col-md-5 m-md-auto order-md-5 d-none d-lg-block plus-background">
                <center>
                    <img loading="lazy" alt="Ready made bill receipt" class="img-fluid"
                        src="{!! asset('/images/product/billing-app/features/auto-generated-payment-receipt.jpg') !!}" />
                </center>
            </div>
            <div class="col-12 col-md-5 d-none d-lg-block">
                <h2><strong>Your payment collection receipts are ready!</strong></h2>
                <p class="lead">On successful payment, payment receipts are automatically generated and sent to you and
                    your customer via Email and SMS. Collected funds are transferred directly to your linked bank
                    account!</p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4 plus-background">
                <center>
                    <img loading="lazy" alt="Ready made bill receipt" class="img-fluid"
                        src="{!! asset('/images/product/billing-app/features/auto-generated-payment-receipt.jpg') !!}" />
                </center>
            </div>
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>Your payment collection receipts are ready!</strong></h2>
                <p class="lead">On successful payment, payment receipts are automatically generated and sent to you and
                    your customer via Email and SMS. Collected funds are transferred directly to your linked bank
                    account!</p>
            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5">
            <div class="col-4 col-md-5 d-none d-lg-block plus-background">
                <center>
                    <img loading="lazy" alt="Pending collections view" class="img-fluid"
                        src="{!! asset('/images/product/billing-app/features/pending-payments-view.jpg') !!}" />
                </center>
            </div>
            <div class="col-12 col-md-5 m-md-auto d-none d-lg-block">
                <h2><strong>All unpaid customer bills in one place</strong></h2>
                <p class="lead">Stay on top of all your pending collections at all times! Remind your customers to make
                    payments via the app. Get paid on time, every time!</p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4 plus-background">
                <center>
                    <img loading="lazy" alt="Pending collections view" class="img-fluid"
                        src="{!! asset('/images/product/billing-app/features/pending-payments-view.jpg') !!}" />
                </center>
            </div>
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>All unpaid customer bills in one place</strong></h2>
                <p class="lead">Stay on top of all your pending collections at all times! Remind your customers to make
                    payments via the app. Get paid on time, every time!</p>
            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5 pb-md-5">
            <div class="col-4 col-md-5 m-md-auto order-md-5 d-none d-lg-block plus-background">
                <center>
                    <img loading="lazy" alt="Ready made bill receipt" class="img-fluid"
                        src="{!! asset('/images/product/billing-app/features/track-all-payments.jpg') !!}" />
                </center>
            </div>
            <div class="col-12 col-md-5 d-none d-lg-block">
                <h2><strong>Customers paying you using Cash, Cheque, NEFT or UPI apps?</strong></h2>
                <p class="lead">No problem! Track all forms of customer payments like:
                    <ol>
                        <li>Cash payments</li>
                        <li>Cheques</li>
                        <li>UPI app based payments</li>
                        <li>NEFT</li>
                    </ol>
                    Settle unpaid bills and keep a track of all your customers payments using one app
                </p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4 plus-background">
                <center>
                    <img loading="lazy" alt="Ready made bill receipt" class="img-fluid"
                        src="{!! asset('/images/product/billing-app/features/track-all-payments.jpg') !!}" />
                </center>
            </div>
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>Customers paying you using Cash, Cheque, NEFT or UPI apps?</strong></h2>
                <p class="lead">No problem! Track all forms of customer payments like:
                    <ol>
                        <li>Cash payments</li>
                        <li>Cheques</li>
                        <li>UPI app based payments</li>
                        <li>NEFT</li>
                    </ol>
                    Settle unpaid bills and keep a track of all your customers payments using one app
                </p>
            </div>
            <!-- end -->
        </div>
    </div>
</section>

<section class="jumbotron bg-secondary py-5" id="cta">
    <div class="container">
        <div class="row text-center justify-content-center">
            <div class="col-md-12 mb-5">
                <h3 class="text-white">Over {{env('SWIPEZ_BIZ_NUM')}} businesses trust Swipez products. Download and
                    start using for free!</h3>
            </div>
            <div class="col-8 col-md-4 md:ml-6 mt-8 md:mt-12 pt-4">
                <div class="px-4 items-center justify-center bg-primary rounded-full">
                    <a href="https://apps.apple.com/in/app/collect-it-billing-payments/id1583224355"
                        title="Go to App Store">
                        <center><img width="200" src="/images/product/billing-app/apple-app-store.svg"
                                alt="apple app store logo"></center>
                    </a>
                </div>
            </div>
            <div class="col-8 col-md-4 md:ml-6 mt-8 md:mt-12 pt-4">
                <div class="px-4 items-center justify-center bg-primary rounded-full">
                    <a href="https://play.google.com/store/apps/details?id=com.swipez.collectit"
                        title="Go to Google Play">
                        <center><img loading="lazy" width="200"
                                src="{!! asset('/images/product/billing-app/google-play-store.svg') !!}"
                                alt="google play logo"></center>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="jumbotron py-8 bg-primary" id="features">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 text-center">
                <h2 class="display-4 text-white">Free customer payment collections app</h2>
            </div>
        </div><br />
        <div class="row row-eq-height text-center">
            <div class="col-md-3">
                <div class="bg-white p-4 h-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-28 pb-2" viewBox="0 0 197.35 195.766">
                        <g id="collect" transform="translate(-666.65 -471.385)">
                            <circle id="Ellipse_17" data-name="Ellipse 17" cx="16.43" cy="16.43" r="16.43"
                                transform="translate(780.62 558.735)" fill="#f89b37" />
                            <circle id="Ellipse_18" data-name="Ellipse 18" cx="16.43" cy="16.43" r="16.43"
                                transform="translate(820.912 514.923)" fill="#f89b37" />
                            <circle id="Ellipse_19" data-name="Ellipse 19" cx="16.43" cy="16.43" r="16.43"
                                transform="translate(771.232 481.282)" fill="#f89b37" />
                            <g id="Group_202" data-name="Group 202">
                                <g id="Group_201" data-name="Group 201">
                                    <path id="Path_83" data-name="Path 83"
                                        d="M764.149,667.151a2.832,2.832,0,0,1-1.413-.378l-12.909-7.433a2.836,2.836,0,0,1,2.831-4.916l12.909,7.433a2.836,2.836,0,0,1-1.418,5.294Zm11.22-2.973a2.828,2.828,0,0,1-1.429-.387l-39.17-22.849H718.088a2.835,2.835,0,0,1-2.834,2.738H702.443v6.943a2.837,2.837,0,0,1-2.836,2.837H669.486a2.837,2.837,0,0,1,0-5.673h27.285v-71H669.877a2.836,2.836,0,0,1,0-5.672h29.73a2.836,2.836,0,0,1,2.836,2.836v5.77h12.811a2.836,2.836,0,0,1,2.836,2.836v4.6h42.639a2.838,2.838,0,0,1,2.134.968l22.222,25.4,61.439-18.344c8.28-2.43,12.579,1.363,14.729,4.972.037.062.071.125.1.189,3.5,7.039,1.416,11.27-.948,13.579a2.879,2.879,0,0,1-.531.409l-83.058,49.455A2.829,2.829,0,0,1,775.369,664.178ZM718.09,635.27h17.447a2.837,2.837,0,0,1,1.429.386l38.388,22.394,81.309-48.414c.681-.735,1.742-2.435-.337-6.676-1.155-1.9-3.146-3.827-8.192-2.346l-58.929,17.594a2.811,2.811,0,0,1,.126.293c2.109,5.829,1.629,10.463-1.428,13.775-2.6,2.908-7.365,5.774-15.36,2.971a2.845,2.845,0,0,1-.992-.6l-17.935-16.655a2.836,2.836,0,1,1,3.859-4.156l17.487,16.237c5.1,1.617,7.413-.116,8.721-1.589l.042-.045c.546-.588,2.171-2.339.456-7.481l-2.106-2.27-.021-.023,0,0,0,0-.042-.048-22.566-25.788H718.09Zm-15.647,2.738h9.975V585.394h-9.975ZM803.663,661.5a2.837,2.837,0,0,1-1.545-5.217l5.223-3.384a2.836,2.836,0,0,1,3.084,4.761l-5.222,3.383A2.823,2.823,0,0,1,803.663,661.5Zm-61.42-4.91a2.825,2.825,0,0,1-1.52-.443l-4.3-2.739a2.836,2.836,0,1,1,3.045-4.785l4.3,2.738a2.836,2.836,0,0,1-1.525,5.229Zm74.642-4.107a2.837,2.837,0,0,1-1.475-5.261l31.627-19.207a2.836,2.836,0,0,1,2.944,4.849L818.355,652.07A2.826,2.826,0,0,1,816.885,652.482ZM683.373,641.333a9.312,9.312,0,1,1,6.552-2.717A9.123,9.123,0,0,1,683.373,641.333Zm0-12.909a3.575,3.575,0,0,0-3.619,3.618,3.623,3.623,0,1,0,3.619-3.618Zm113.638-27.343a25.907,25.907,0,1,1,18.337-7.6A25.1,25.1,0,0,1,797.011,601.081Zm0-46.179a20.236,20.236,0,1,0,14.326,5.937A19.357,19.357,0,0,0,797.011,554.9Zm-41.466,28.536H739.116a2.836,2.836,0,0,1,0-5.672h16.429a2.836,2.836,0,1,1,0,5.672Zm-25.817,0h-2.739a2.836,2.836,0,0,1,0-5.672h2.739a2.836,2.836,0,0,1,0,5.672Zm34.482-5.085a2.836,2.836,0,0,1-2.836-2.836v-.352a33.161,33.161,0,0,1,4.052-16.279,2.836,2.836,0,1,1,4.961,2.75,27.557,27.557,0,0,0-3.341,13.529v.352A2.836,2.836,0,0,1,764.21,578.353ZM692.957,567.4h-9.389a2.836,2.836,0,0,1,0-5.672h9.389a2.836,2.836,0,1,1,0,5.672Zm-18.386,0h-2.738a2.836,2.836,0,0,1,0-5.672h2.738a2.836,2.836,0,0,1,0,5.672Zm163.122-10.112a25.909,25.909,0,1,1,18.337-7.6A25.094,25.094,0,0,1,837.693,557.288Zm0-46.179a20.236,20.236,0,1,0,14.327,5.937A19.356,19.356,0,0,0,837.693,511.109Zm-64.936,45.455a2.836,2.836,0,0,1-2.069-4.775q.324-.347.671-.693a36.9,36.9,0,0,1,3.158-2.818,2.836,2.836,0,0,1,3.522,4.446,31.139,31.139,0,0,0-2.67,2.384c-.187.187-.367.373-.542.559A2.827,2.827,0,0,1,772.757,556.564ZM766.575,526.7a2.825,2.825,0,0,1-1.845-.684c-.026-.022-.051-.042-.077-.061s-.071-.052-.105-.079a33.117,33.117,0,0,1-10.268-13.257,2.836,2.836,0,1,1,5.23-2.194,27.514,27.514,0,0,0,8.505,10.962c.138.1.275.21.408.324a2.836,2.836,0,0,1-1.848,4.989Zm21.438-3.462a25.941,25.941,0,1,1,18.335-7.6A25.1,25.1,0,0,1,788.013,523.236Zm0-46.179a20.27,20.27,0,1,0,14.33,5.94A19.356,19.356,0,0,0,788.013,477.057ZM861.165,510.4a2.827,2.827,0,0,1-2.064-.89,26.694,26.694,0,0,0-7.993-5.857,2.836,2.836,0,0,1,2.431-5.125,32.349,32.349,0,0,1,9.688,7.09,2.836,2.836,0,0,1-2.062,4.782ZM755.131,505.34a2.836,2.836,0,0,1-2.806-2.453q-.065-.468-.108-.957a35.624,35.624,0,0,1-.29-4.15,2.837,2.837,0,0,1,2.807-2.865h.03a2.837,2.837,0,0,1,2.835,2.807,29.945,29.945,0,0,0,.25,3.536c.006.041.01.081.014.121.021.253.049.5.082.742a2.843,2.843,0,0,1-2.814,3.22Zm89.47-3.873a2.872,2.872,0,0,1-.511-.046q-.42-.076-.859-.153a33.225,33.225,0,0,0-3.531-.372,2.836,2.836,0,0,1,.289-5.665,39.079,39.079,0,0,1,4.177.443c.344.06.645.113.939.166a2.836,2.836,0,0,1-.5,5.627Z"
                                        fill="#2d5772" />
                                </g>
                            </g>
                            <path id="Path_84" data-name="Path 84"
                                d="M794.135,488.127l-.1-.1H781.715l-.117.1v1.194l.117.1h2.25a5.573,5.573,0,0,1,2.562.744,3.307,3.307,0,0,1,1.31,1.916h-6.122l-.117.118v1.173l.117.1H787.9v.157l-.059.586q-.84,3.051-5.065,3.052h-.783v.039q5.085,6.63,7.667,9.955h2.5v-.117l-6.611-8.567a.159.159,0,0,0,.117-.039A7.668,7.668,0,0,0,788.7,497.3q2.406-1.878,2.406-3.834h2.934l.1-.1V492.2l-.1-.118h-3.052l-.058-.371a3.526,3.526,0,0,0-1.526-2.191v-.1h4.636l.1-.1v-1.194"
                                fill="#2d5772" />
                            <g id="Group_203" data-name="Group 203">
                                <path id="Path_85" data-name="Path 85"
                                    d="M847.135,537.848H828.2a1.866,1.866,0,0,1-1.865-1.865v-9.466a1.866,1.866,0,0,1,1.865-1.865h18.933A1.867,1.867,0,0,1,849,526.517v9.466A1.867,1.867,0,0,1,847.135,537.848ZM828.2,525.513a1,1,0,0,0-1,1v9.466a1,1,0,0,0,1,1h18.933a1,1,0,0,0,1-1v-9.466a1,1,0,0,0-1-1Z"
                                    fill="#2d5772" />
                                <rect id="Rectangle_27" data-name="Rectangle 27" width="21.802" height="3.156"
                                    transform="translate(826.911 528.238)" fill="#2d5772" />
                            </g>
                            <g id="Group_207" data-name="Group 207">
                                <g id="Group_205" data-name="Group 205">
                                    <path id="Path_86" data-name="Path 86" d="M804.71,578.813h-1.436l1.995-7.206h1.436Z"
                                        fill="#2d5772" />
                                    <g id="Group_204" data-name="Group 204">
                                        <path id="Path_87" data-name="Path 87"
                                            d="M803.964,571.833a.526.526,0,0,0-.461-.206h-7.89l-.389,1.413H802.4l-.418,1.508h-5.741v0H794.8l-1.191,4.3h1.436l.8-2.885H802.3a.909.909,0,0,0,.569-.206.963.963,0,0,0,.351-.511l.8-2.885A.585.585,0,0,0,803.964,571.833Z"
                                            fill="#2d5772" />
                                    </g>
                                    <path id="Path_88" data-name="Path 88"
                                        d="M792.777,578.362a.661.661,0,0,1-.634.484h-7.4a.517.517,0,0,1-.451-.206.568.568,0,0,1-.062-.511l1.8-6.5h1.436l-1.612,5.809H791.6l1.612-5.809h1.436Z"
                                        fill="#2d5772" />
                                </g>
                                <g id="Group_206" data-name="Group 206">
                                    <path id="Path_89" data-name="Path 89" d="M809.185,571.62,811,575.231l-3.817,3.611Z"
                                        fill="#2d5772" />
                                    <path id="Path_90" data-name="Path 90" d="M807.909,571.62l1.815,3.611-3.821,3.611Z"
                                        fill="#2d5772" />
                                </g>
                            </g>
                        </g>
                    </svg>
                    <h3 class="text-secondary pb-2">All transactions in one place</h3>
                    <p>Track payments made either online or offline. All your customer payments made either online or
                        via Cash, Cheque & NEFT tracked in one app</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="bg-white p-4 h-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-28 pb-2" viewBox="0 0 132 131.699">
                        <g id="OTP" transform="translate(-1118.286 -493)">
                            <path id="Path_41" data-name="Path 41"
                                d="M1138.835,496.688l4.3,8.606,9.6,8.277,22.475-.74,3.674-7.536s8.936-3.64,7.281-8.606-42.7-2.979-42.7-2.979"
                                transform="translate(14.111 0.217)" fill="#f89b37" />
                            <path id="Path_42" data-name="Path 42"
                                d="M1152.14,531.575l-3.367,2.729.271-4.653-1.969.018.342,4.648L1144,531.652l-1.044,1.582,4.076,2.229L1143,537.785l1.074,1.546,3.367-2.729-.254,4.67,1.969-.018-.358-4.631,3.418,2.633,1.044-1.566-4.059-2.2,4.016-2.355-1.074-1.562"
                                transform="translate(16.94 25.169)" fill="#2e5670" />
                            <path id="Path_43" data-name="Path 43"
                                d="M1156.623,534.231l.271-4.653-1.969.018.342,4.648-3.418-2.666-1.045,1.582,4.076,2.229-4.033,2.322,1.074,1.546,3.367-2.729-.254,4.67,1.969-.019-.358-4.631,3.418,2.633,1.044-1.566-4.059-2.2,4.017-2.355-1.074-1.562-3.367,2.729"
                                transform="translate(22.33 25.118)" fill="#2e5670" />
                            <path id="Path_44" data-name="Path 44"
                                d="M1164.472,534.157l.271-4.653-1.969.018.342,4.648L1159.7,531.5l-1.044,1.582,4.076,2.229-4.033,2.322,1.074,1.546,3.367-2.729-.254,4.67,1.969-.018-.358-4.631,3.417,2.633,1.045-1.566-4.059-2.2,4.016-2.355-1.074-1.562Z"
                                transform="translate(27.72 25.067)" fill="#2e5670" />
                            <g id="Group_163" data-name="Group 163" transform="translate(1118.286 493)">
                                <path id="Path_45" data-name="Path 45"
                                    d="M1151.658,624.7a14.006,14.006,0,0,1-10.243-4.3,14.587,14.587,0,0,1-4.374-10.491l-.01-1.1a11.193,11.193,0,0,1-2.991-2.124,11.2,11.2,0,0,1-.538-15.393,11.283,11.283,0,0,1-2.937-2.1,11.221,11.221,0,0,1-1.26-14.5,11.2,11.2,0,0,1-5.674-18.783l.073-.073a11.5,11.5,0,0,1-2.075-1.628,11.172,11.172,0,0,1,7.724-19.149l6.983-.066-.248-26.446a14.585,14.585,0,0,1,4.176-10.572,14.02,14.02,0,0,1,10.313-4.493l50.775-.477a14,14,0,0,1,10.4,4.3,14.591,14.591,0,0,1,4.37,10.488l.182,19.495,5.875-.056h.16a14.943,14.943,0,0,1,14.909,14.787l.294,14.529,10.021-.094h.024a2.4,2.4,0,0,1,2.4,2.377l.3,32.089a2.4,2.4,0,0,1-2.318,2.422l-11.122.381c-.489,4.477-2.149,7.931-4.947,10.292-3.375,2.845-8.358,4-14.841,3.454l.017,1.688a14.58,14.58,0,0,1-4.176,10.57,13.981,13.981,0,0,1-10.314,4.495l-50.773.477Zm-9.817-14.819a9.815,9.815,0,0,0,2.974,7.133,9.289,9.289,0,0,0,6.951,2.886l50.775-.477a9.219,9.219,0,0,0,6.89-3.012,9.813,9.813,0,0,0,2.844-7.207l-.041-4.411h0l-.487-51.729a21.045,21.045,0,0,1-14.909-4.73c-4.532-3.787-7.889-9.889-9.974-18.134a2.4,2.4,0,0,1,2.326-2.989h.01l22.3.089-.184-19.463a9.814,9.814,0,0,0-2.975-7.15,9.232,9.232,0,0,0-6.949-2.886l-1.64.015.015,1.621a14.945,14.945,0,0,1-14.787,15.069l-17.641.165a14.942,14.942,0,0,1-15.067-14.787l-.017-1.621-1.638.015a9.259,9.259,0,0,0-6.9,3.016,9.816,9.816,0,0,0-2.84,7.2l.415,43.972,5-.047h.035l1.386-.013a11.177,11.177,0,0,1,8.083,19q-.387.392-.795.742a12.463,12.463,0,0,1,1.142.99,11.188,11.188,0,0,1,.148,15.806,11.016,11.016,0,0,1-3.849,2.589,11.178,11.178,0,0,1-8.2,18.319l-.057,0Zm2.326-2.422h0Zm-4.112-2.616.01,0a6.622,6.622,0,0,0,1.656.236l2.393-.022a6.377,6.377,0,0,0-.041-12.745l-2.753.027a6.778,6.778,0,0,0-1.366.263l-.019.005a6.41,6.41,0,0,0,.118,12.232Zm76.957-2.2c5.387.533,9.346-.238,11.792-2.3,2.188-1.845,3.3-4.789,3.389-9A2.4,2.4,0,0,1,1234.51,589l10.955-.374-.258-27.35-10,.094h-.024a2.4,2.4,0,0,1-2.4-2.351l-.341-16.929a10.144,10.144,0,0,0-10.225-10.058l-8.267.078h-.04l-21.548-.086c1.811,5.711,4.343,9.951,7.545,12.627,3.65,3.051,8.2,4.168,13.895,3.417a2.4,2.4,0,0,1,2.712,2.356Zm-78.536-15.06a2.438,2.438,0,0,1,.315.02,2.213,2.213,0,0,1,.439-.044l1.935-.019h.044l2.852-.027h.019l4.811-.046a6,6,0,0,0,3.984-1.884,6.375,6.375,0,0,0-4.394-10.84l-.251,0-.017,0-10.166.094a6.375,6.375,0,0,0,.406,12.741Zm-7.432-30.2a6.375,6.375,0,0,0,.437,12.724l.175,0c.113-.005.229-.008.344-.01l1.736-.015c.042,0,.083,0,.123,0s.084-.005.125-.005l3.972-.037h0l10.181-.1a6.374,6.374,0,0,0-.371-12.74l-1.341.012c-.214.013-.428.02-.644.024Zm5.337-17.594-6.983.066a6.372,6.372,0,1,0,.12,12.743l1.407-.013h0l5.574-.059Zm20.679-41.572.015,1.621a10.145,10.145,0,0,0,10.223,10.034l17.641-.167a10.144,10.144,0,0,0,10.034-10.223l-.015-1.621Zm10.21,111.44a2.4,2.4,0,0,1-.022-4.8l19.529-.184h.024a2.4,2.4,0,0,1,.022,4.8l-19.529.184Z"
                                    transform="translate(-1118.286 -493)" fill="#2e5670" />
                            </g>
                        </g>
                    </svg>

                    <h3 class="text-secondary pb-2">Anytime, anywhere</h3>
                    <p>All your customer and billing data available for you to access any time via your phone or web
                        browser. Works securely across any device</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="bg-white p-4 h-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-28 pb-2" viewBox="0 0 80 140.702">
                        <g id="welcome" transform="translate(-1191.176 -756)">
                            <path id="Path_55" data-name="Path 55"
                                d="M1223.152,859.365h8.669s7.87,12.838-4.316,20.673C1227.5,880.038,1213.577,874.815,1223.152,859.365Z"
                                transform="translate(4.118 14.876)" fill="#f89b37" />
                            <path id="Path_56" data-name="Path 56"
                                d="M1217.783,843.2l.436,11.316s10.662,4.57,18.279,0l1.958-11.316S1224.311,846.459,1217.783,843.2Z"
                                transform="translate(3.829 12.549)" fill="#f89b37" />
                            <ellipse id="Ellipse_13" data-name="Ellipse 13" cx="6.528" cy="6.637" rx="6.528" ry="6.637"
                                transform="translate(1224.441 783.714)" fill="#f89b37" />
                            <g id="Group_177" data-name="Group 177" transform="translate(1228.398 818.666)">
                                <g id="Group_176" data-name="Group 176">
                                    <circle id="Ellipse_14" data-name="Ellipse 14" cx="2.185" cy="2.185" r="2.185"
                                        fill="#2d5772" />
                                </g>
                            </g>
                            <g id="Group_179" data-name="Group 179" transform="translate(1221.71 781.036)">
                                <g id="Group_178" data-name="Group 178">
                                    <path id="Path_57" data-name="Path 57"
                                        d="M1227.167,777.886a9.295,9.295,0,1,0,9.3,9.3A9.237,9.237,0,0,0,1227.167,777.886Zm4.93,9.3a4.93,4.93,0,1,1-4.93-4.929A4.936,4.936,0,0,1,1232.1,787.185Z"
                                        transform="translate(-1217.868 -777.886)" fill="#2d5772" />
                                </g>
                            </g>
                            <g id="Group_181" data-name="Group 181" transform="translate(1222.025 874.184)">
                                <g id="Group_180" data-name="Group 180">
                                    <path id="Path_58" data-name="Path 58"
                                        d="M1231.134,859.428a2.184,2.184,0,0,0-1.376,2.766l1.855,5.535a7.211,7.211,0,0,1-1.4,7l-1.993,2.287a1.295,1.295,0,0,1-1.952,0l-1.994-2.286a7.213,7.213,0,0,1-1.4-7l1.855-5.535a2.185,2.185,0,0,0-4.143-1.388l-1.855,5.535a11.6,11.6,0,0,0,2.248,11.263l1.993,2.287a5.666,5.666,0,0,0,8.275.283c.09-.092.178-.185.264-.284l1.993-2.287a11.6,11.6,0,0,0,2.245-11.264l-1.856-5.534A2.185,2.185,0,0,0,1231.134,859.428Z"
                                        transform="translate(-1218.144 -859.315)" fill="#2d5772" />
                                </g>
                            </g>
                            <g id="Group_183" data-name="Group 183" transform="translate(1191.176 756)">
                                <g id="Group_182" data-name="Group 182">
                                    <path id="Path_59" data-name="Path 59"
                                        d="M1231.592,756a5.059,5.059,0,0,0-3.759,1.717l-1.348,1.529a65.918,65.918,0,0,0-16.031,50.2l.782,7.554-9.12,8.6-.046.045a36.961,36.961,0,0,0-10.895,26.309V870.1a2.184,2.184,0,0,0,3.648,1.621l20.2-18.225.182,1.748a2.184,2.184,0,0,0,1.554,1.87q1.282.378,2.578.688l.853,9.965a2.187,2.187,0,0,0,1.441,1.87,28.455,28.455,0,0,0,19.316,0,2.186,2.186,0,0,0,1.437-1.83c.185-1.778.372-3.854.554-5.861.129-1.431.261-2.894.392-4.254.714-.177,1.424-.368,2.13-.578a2.187,2.187,0,0,0,1.549-1.821l.236-1.87,20.284,18.3a2.185,2.185,0,0,0,3.648-1.622V851.951a36.967,36.967,0,0,0-10.9-26.308l-8.464-8.462.8-6.358a65.657,65.657,0,0,0-16.523-52.374l-.715-.788A5.061,5.061,0,0,0,1231.592,756Zm-36.046,109.191V851.954a32.619,32.619,0,0,1,9.593-23.2l6.666-6.286,2.66,25.649Zm43.04-3.634c-.134,1.479-.271,3-.407,4.394a23.994,23.994,0,0,1-13.759.008l-.625-7.295a50.669,50.669,0,0,0,7.14.53h.01a50.644,50.644,0,0,0,7.909-.6C1238.764,859.569,1238.674,860.572,1238.586,861.556Zm18.6-32.824a32.62,32.62,0,0,1,9.619,23.218v13.237l-18.9-17.047,3.211-25.473,6.065,6.064Zm-24.331-67.348a61.293,61.293,0,0,1,15.425,48.892l-5.424,43.031a46.256,46.256,0,0,1-9.727,1.467V838.437a2.185,2.185,0,0,0-4.37,0v16.323a46.375,46.375,0,0,1-9.363-1.44l-4.6-44.325a61.535,61.535,0,0,1,14.966-46.861l1.346-1.531a.687.687,0,0,1,.512-.233.678.678,0,0,1,.515.226Z"
                                        transform="translate(-1191.176 -756)" fill="#2d5772" />
                                </g>
                            </g>
                        </g>
                    </svg>



                    <h3 class="text-secondary pb-2">Grow your business</h3>
                    <p>Manage cash flow with hassle-free billing and sales reports. Streamline your collections
                        and accounting
                        processes. Make way for growth!</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="bg-white p-4 h-100">

                    <svg xmlns="http://www.w3.org/2000/svg" class="h-28 pb-2" viewBox="0 0 79.98 79.917">
                        <g id="Prizes" transform="translate(-63.468 -1554.57)">
                            <g id="Group_208" data-name="Group 208" transform="translate(62.942 1554.012)">
                                <path id="Path_91" data-name="Path 91"
                                    d="M19.38,1.058a1.409,1.409,0,0,0-1.411,1.406V6.7H2.485a.557.557,0,0,0-.133,0A1.407,1.407,0,0,0,1.084,8.03a39.515,39.515,0,0,0,1.827,13.2A21.505,21.505,0,0,0,8.69,30.549a17.451,17.451,0,0,0,11.558,4.3A22.535,22.535,0,0,0,33.456,46.421a14.971,14.971,0,0,1-1.923,5.571,29.262,29.262,0,0,1-2.013,3.185,1.4,1.4,0,0,0-.277.842v7.041H19.38a1.4,1.4,0,0,0-1.411,1.417V78.569a1.4,1.4,0,0,0,1.411,1.406H61.653a1.412,1.412,0,0,0,1.406-1.406V64.476a1.407,1.407,0,0,0-1.406-1.417H51.794V56.018a1.4,1.4,0,0,0-.282-.842A29.764,29.764,0,0,1,49.5,51.992a15.082,15.082,0,0,1-1.928-5.571,22.525,22.525,0,0,0,13.2-11.568,17.416,17.416,0,0,0,11.568-4.3,21.443,21.443,0,0,0,5.774-9.321,39.515,39.515,0,0,0,1.827-13.2A1.4,1.4,0,0,0,78.537,6.7H63.059V2.464a1.415,1.415,0,0,0-1.406-1.406Zm1.406,2.823H60.242c-.069,7.046,0,14.024,0,21.134a19.728,19.728,0,1,1-39.456,0C20.8,17.958,20.786,10.9,20.786,3.881ZM4.088,9.516h13.88v15.5a22.631,22.631,0,0,0,1.1,6.967,13.992,13.992,0,0,1-8.5-3.521,18.794,18.794,0,0,1-4.98-8.117A29.841,29.841,0,0,1,4.088,9.516Zm58.971,0H76.944a29.66,29.66,0,0,1-1.5,10.828,18.845,18.845,0,0,1-4.98,8.117,13.993,13.993,0,0,1-8.511,3.521,22.63,22.63,0,0,0,1.1-6.967ZM36.242,47.145a22.223,22.223,0,0,0,8.554,0,17.474,17.474,0,0,0,2.232,6.2c.25.458.5.879.74,1.268H33.27c.24-.389.485-.81.735-1.268A17.378,17.378,0,0,0,36.242,47.145Zm-4.181,10.29H48.977v5.624H32.061ZM20.786,65.882H60.242V77.158H20.786Z"
                                    transform="translate(0 0)" fill="#2d5772" stroke="#2d5772" stroke-width="1" />
                            </g>
                            <path id="Path_92" data-name="Path 92"
                                d="M39.426,23.007h7.2L49.39,14.69l3.285,8.316h7.283L56.14,25.834,53.6,27.711l2.53,6.69L49.39,29.855,42.732,34.4l2.393-6.69Z"
                                transform="translate(53.125 1551.56)" fill="none" stroke="#f89b37" stroke-width="3" />
                        </g>
                    </svg>


                    <h3 class="text-secondary pb-2">Win prizes every month!</h3>
                    <p>Top 5 transacting businesses win Amazon Firestick, Google Mini and lots more. All you need to do
                        is start sending bills and collecting payments online!</p>
                </div>

            </div>
        </div>
    </div>
</section>

<section class="jumbotron bg-transparent py-5" id="features">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 text-center">
                <h2 class="display-4">Billing app for small business</h2>
                <p class="lead">Over {{env('SWIPEZ_BIZ_NUM')}} small businesses use Swipez products
                    to manage
                    their business on a day to day basis</p>
            </div>
        </div>
        <div class="row pt-4">
            <div class="col-sm-3 d-none d-sm-block">
                <ul class="nav nav-pills" id="appfeatureTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active text-uppercase gray-400" id="appfeature-teacher-tab"
                            data-toggle="pill" href="#appfeature-teacher" role="tab" aria-controls="appfeature-teacher"
                            aria-selected="true">Teachers & Coaches</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link text-uppercase gray-400" id="appfeature-food-tab" data-toggle="pill"
                            href="#appfeature-food" role="tab" aria-controls="appfeature-food"
                            aria-selected="false">Food
                            Catering</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link text-uppercase gray-400" id="appfeature-seller-tab" data-toggle="pill"
                            href="#appfeature-seller" role="tab" aria-controls="appfeature-seller"
                            aria-selected="false">Online seller</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link text-uppercase gray-400" id="appfeature-milk-tab" data-toggle="pill"
                            href="#appfeature-milk" role="tab" aria-controls="appfeature-milk"
                            aria-selected="false">Milk
                            delivery</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link text-uppercase gray-400" id="appfeature-isp-tab" data-toggle="pill"
                            href="#appfeature-isp" role="tab" aria-controls="appfeature-isp"
                            aria-selected="false">Internet
                            service provider</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link text-uppercase gray-400" id="appfeature-newspaper-tab" data-toggle="pill"
                            href="#appfeature-newspaper" role="tab" aria-controls="appfeature-newspaper"
                            aria-selected="false">Newspaper delivery</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link text-uppercase gray-400" id="appfeature-cable-tab" data-toggle="pill"
                            href="#appfeature-cable" role="tab" aria-controls="appfeature-cable"
                            aria-selected="false">Cable
                            operator</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link text-uppercase gray-400" id="appfeature-smallbiz-tab" data-toggle="pill"
                            href="#appfeature-smallbiz" role="tab" aria-controls="appfeature-smallbiz"
                            aria-selected="false">Small business owner</a>
                    </li>
                </ul>
            </div>
            <div class="col-12 col-sm-9">
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="appfeature-teacher" role="tabpanel"
                        aria-labelledby="appfeature-teacher-tab">
                        <div class=" border-0">
                            <div class="card-title">
                                <h2 class="card-title gray-600">
                                    <center>Billing app for teachers and coaches</center>
                                </h2>
                            </div>
                            <img loading="lazy" class="rounded-1 card-img-top p-2"
                                src="{!! asset('/images/product/billing-app/industry/teachers-coaches.jpg') !!}"
                                alt="Billing app for teachers and coaches" width="640" height="360" loading="lazy"
                                class="lazyload" />
                            <div class="card-body">
                                <p class="lead">
                                    As a teacher or a coach, fee collection from your students is time consuming. With
                                    the Collect it billing app you can start sending pending dues to all your students
                                    right from your phones contact book. Your students can start making your payments
                                    online and you are always aware of who needs to pay you and who has already paid.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="appfeature-food" role="tabpanel"
                        aria-labelledby="appfeature-food-tab">
                        <div class="border-0">
                            <div class="card-title">
                                <h2 class="card-title gray-600">
                                    <center>Billing app for food catering businesses</center>
                                </h2>
                            </div>
                            <img loading="lazy" class="rounded-1 card-img-top p-2"
                                src="{!! asset('/images/product/billing-app/industry/home-chef-catering.jpg') !!}"
                                alt="Billing app food catering and home chefs" width="640" height="360" loading="lazy"
                                class="lazyload" />
                            <div class="card-body">
                                <p class="lead">
                                    Home chefs and catering services deal with multiple customers on a daily basis via
                                    WhatsApp. Your customers are typically already on your contacts book, using the
                                    Collect it Billing app you can send bills straight from your phone contacts. Keep a
                                    track of payment received and pending collections at all times!
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="appfeature-seller" role="tabpanel"
                        aria-labelledby="appfeature-seller-tab">
                        <div class="border-0">
                            <div class="card-title">
                                <h2 class="card-title gray-600">
                                    <center>Billing app for online sellers</center>
                                </h2>
                            </div>
                            <img loading="lazy" class="rounded-1 card-img-top p-2"
                                src="{!! asset('/images/product/billing-app/industry/online-seller.jpg') !!}"
                                alt="Billing app for online seller" width="640" height="360" loading="lazy"
                                class="lazyload" />
                            <div class="card-body">
                                <p class="lead">
                                    Selling on online platforms like Facebook Live & Instagram is a great way to reach a
                                    wide customer base. Keeping a track of who has paid and then shipping your products
                                    is time consuming and some times error prone. Using Collect it Billing app for
                                    online
                                    sellers you can organize for all your online orders and get paid faster.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="appfeature-milk" role="tabpanel"
                        aria-labelledby="appfeature-milk-tab">
                        <div class="border-0">
                            <div class="card-title">
                                <h2 class="card-title gray-600">
                                    <center>Billing app for milk delivery</center>
                                </h2>
                            </div>
                            <img loading="lazy" class="rounded-1 card-img-top p-2"
                                src="{!! asset('/images/product/billing-app/industry/milk-delivery.jpg') !!}"
                                alt="Billing app for milk delivery" width="640" height="360" loading="lazy"
                                class="lazyload" />
                            <div class="card-body">
                                <p class="lead">
                                    Delivering milk and dairy items to multiple homes? Using Collect it Billing app,
                                    send bills to your customers straight from your contacts, keep a track of who has
                                    paid and who is yet to pay. Automatically remind customers to make payments on time.
                                    Collect payments directly into your bank account. Basically, make your milk delivery
                                    billing simpler!
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="appfeature-isp" role="tabpanel" aria-labelledby="appfeature-isp-tab">
                        <div class="border-0">
                            <div class="card-title">
                                <h2 class="card-title gray-600">
                                    <center>Billing app for ISP</center>
                                </h2>
                            </div>
                            <img loading="lazy" class="rounded-1 card-img-top p-2"
                                src="{!! asset('/images/product/billing-app/industry/internet-service-provider.jpg') !!}"
                                alt="Billing app for internet service provider" width="640" height="360" loading="lazy"
                                class="lazyload" />
                            <div class="card-body">
                                <p class="lead">Internet service providers deal with hundreds of household connections
                                    and keeping a track of all these payments is really tough. Using the Collect it
                                    billing app send bills and reminders to your customers to make payments on time.
                                    Keep a track of offline as well as online payments with ease. Avoid late collections
                                    from your customers every month.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="appfeature-newspaper" role="tabpanel"
                        aria-labelledby="appfeature-newspaper-tab">
                        <div class="border-0">
                            <div class="card-title">
                                <h2 class="card-title gray-600">
                                    <center>Billing app for newspaper delivery</center>
                                </h2>
                            </div>
                            <img loading="lazy" class="rounded-1 card-img-top p-2"
                                src="{!! asset('/images/product/billing-app/industry/newspaper-delivery.jpg') !!}"
                                alt="Billing app for newspaper delivery" width="640" height="360" loading="lazy"
                                class="lazyload" />
                            <div class="card-body">
                                <p class="lead">
                                    As a newspaper delivery agency going to hundreds of households to collect money on a
                                    monthly basis take a lot of time and effort. Reduce the time taken for your monthly
                                    collection by using the Collect it Billing app for newspaper delivery agency. Track
                                    all your outstanding dues, send bills to customers with ease and start collecting
                                    payments online.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="appfeature-cable" role="tabpanel"
                        aria-labelledby="appfeature-cable-tab">
                        <div class="border-0">
                            <div class="card-title">
                                <h2 class="card-title gray-600">
                                    <center>Billing app for cable operator</center>
                                </h2>
                            </div>
                            <img loading="lazy" class="rounded-1 card-img-top p-2"
                                src="{!! asset('/images/product/billing-app/industry/cable-operator.jpg') !!}"
                                alt="Billing software for cable operator" width="640" height="360" loading="lazy"
                                class="lazyload" />
                            <div class="card-body">
                                <p class="lead">
                                    Collecting door to door payments as a Cable operator leads to a lot of issues like
                                    delay in payments, misuse of cash collected and more. Using the Collect it Billing
                                    app you can organize all your customer billing with ease. Send bills with online
                                    payments and automatically remind customers to make your payments on time. Track and
                                    settle all your cash collections via the app.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="appfeature-smallbiz" role="tabpanel"
                        aria-labelledby="appfeature-smallbiz-tab">
                        <div class="border-0">
                            <div class="card-title">
                                <h2 class="card-title gray-600">
                                    <center>Billing app for small business owner</center>
                                </h2>
                            </div>
                            <img loading="lazy" class="rounded-1 card-img-top p-2"
                                src="{!! asset('/images/product/billing-app/industry/small-business-owner.jpg') !!}"
                                alt="Best billing app for small business" width="640" height="360" loading="lazy"
                                class="lazyload" />
                            <div class="card-body">
                                <p class="lead">
                                    Covid has impacted all small business owners. Collect it - The free billing app by
                                    Swipez has been created to help the small business collect dues from their customers
                                    faster, without spending too much time and effort. Once you raise a bill to your
                                    customer, the app automatically will remind the customer to make payments before the
                                    due date via SMS and Email.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-center d-sm-none">
                    <button id="prevtab" class="btn btn-primary" onclick="prevAppFeatureClick();">
                        < </button> <button id="nexttab" class="ml-5 btn btn-primary" onclick="nextAppFeatureClick();">
                            >
                            </i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="jumbotron bg-secondary py-5" id="cta">
    <div class="container">
        <div class="row text-center justify-content-center">
            <div class="col-md-12 mb-5">
                <h3 class="text-white">Free billing app that makes payment collections easy!</h3>
            </div>
            <div class="col-8 col-md-4 md:ml-6 mt-8 md:mt-12 pt-4">
                <div class="px-4 items-center justify-center bg-primary rounded-full">
                    <a href="https://apps.apple.com/in/app/collect-it-billing-payments/id1583224355"
                        title="Go to App Store">
                        <center><img width="200" src="/images/product/billing-app/apple-app-store.svg"
                                alt="apple app store logo"></center>
                    </a>
                </div>
            </div>
            <div class="col-8 col-md-4 md:ml-6 mt-8 md:mt-12 pt-4">
                <div class="px-4 items-center justify-center bg-primary rounded-full">
                    <a href="https://play.google.com/store/apps/details?id=com.swipez.collectit"
                        title="Go to Google Play">
                        <center><img loading="lazy" width="200"
                                src="{!! asset('/images/product/billing-app/google-play-store.svg') !!}"
                                alt="google play logo"></center>
                    </a>
                </div>
            </div>
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
                        <h2 class="display-4">Frequently asked questions</h2>
                        <h3 class="lead">Looking for more information? Here are some questions small and medium
                            businesses
                            owners ask</h3>
                    </div>
                </div>
                <!--Accordion wrapper-->
                <div id="accordion" itemscope itemtype="http://schema.org/FAQPage">
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingOne">

                        <div itemprop="name" class="btn btn-link color-black" data-toggle="collapse"
                            data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            <h4>Is the Collect it billing app free?</h4>
                        </div>
                        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne"
                            data-parent="#accordion" itemscope itemprop="acceptedAnswer"
                            itemtype="http://schema.org/Answer" itemscope itemprop="acceptedAnswer"
                            itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, Collect it billing is free to download. There is no restriction on any feature on
                                the mobile app and can be used completely for free. Only charges involved are <a
                                    href="{{ route('home.pricing.onlinetransactions') }}" target="_blank">online payment
                                    collection fees</a>. These fees are charged by banks to provide online payment
                                collections via a payment gateway.
                            </div>
                        </div>
                    </div>
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingTwo">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            <h4>Do my customer receive payment reminders?</h4>
                        </div>
                        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion"
                            itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, your customers are automatically reminded via SMS and Email to make payments before
                                the due date. Your customer receives a SMS and Email as soon you send the bill.
                                Reminders are then sent 3 days before due date, 1 day before due date and on the due
                                date. This way your customers receives multiple reminders to pay you on time.
                            </div>
                        </div>
                    </div>


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingThree">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            <h4>How do I provide online payment options to my customers?</h4>
                        </div>
                        <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion"
                            itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Providing online payment options to your customers was never this easy!
                                <ul>
                                    <li>Download the Collect it billing app from the Google play store</li>
                                    <li>Sign up using your mobile number</li>
                                    <li>Enter your company name and related information</li>
                                    <li>Add your bank account information, this is the bank account in which you will
                                        receive the funds collected via customer payments</li>
                                    <li>Verify you bank account by checking your bank statement for a small amount
                                        (between ₹1 and ₹2). This amount is deposited for bank account verification</li>
                                    <li>Enter the verification amount in the app</li>
                                    <li>Upload your KYC information i.e. Aadhar card</li>
                                    <li>With this you are now ready to start sending bills to your customers and
                                        collecting payments online</li>
                                </ul>
                            </div>
                        </div>
                    </div>


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingFour">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                            <h4>How do my customers receive the bill?</h4>
                        </div>
                        <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion"
                            itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Your customers receive the bill via SMS and Email. The SMS contains a short link
                                which shows them the bill. Similarly the Email contains the bill sent by
                                you. All you need to do is select the customer on the app from your contacts, enter the
                                due amount, a message and due date and hit send!
                            </div>
                        </div>
                    </div>


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingFive">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                            <h4>When do I receive the money paid by my customers in my account?</h4>
                        </div>
                        <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordion"
                            itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                You receive money paid by your customers in 2 days time. For ex. if the payment was made
                                by your customer on Monday you will receive the funds in your account on Wednesday of
                                the same week. Funds are settled in bulk i.e. multiple customer payments are clubbed
                                together and you receive one lump sum payment in your bank account.
                            </div>
                        </div>
                    </div>


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingSix">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                            <h4>Can I see a report showing me all the transfers that are deposited into my bank account
                                (i.e. settlement report) on the app?</h4>
                        </div>
                        <div id="collapseSix" class="collapse" aria-labelledby="headingSix" data-parent="#accordion"
                            itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, you can view all funds deposited in your bank account by the Collect it Billing
                                app. Navigate to the Billing tab in your app and tap the <b>Settlements</b> option. In
                                the Settlements screen you can see all the payment transfers you have received in your
                                linked bank account. On clicking on any of the entries you can see a detail view. The
                                settlement details view shows you which customers payments have been clubbed and
                                transferred into your bank account.<br />
                            </div>
                        </div>
                    </div>


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingSeven">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
                            <h4>How I can view the bank charges or payment gateway fees that I have been charged?</h4>
                        </div>
                        <div id="collapseSeven" class="collapse" aria-labelledby="headingSeven" data-parent="#accordion"
                            itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                For every online payment you collect there is a minor bank charge that is applicable.
                                The money you receive in your bank account is deposited after removing this charge
                                automatically from your settlement amount. These charges are visible in the Settlement
                                option in the Billing tab on of you app. In the Settlements screen you can see all the
                                payment transfers you have received in your linked bank account, along with the charges
                                applicable and the GST. To view charges per transaction you can tap any of the
                                settlement entries and view the charges against each transaction.
                            </div>
                        </div>
                    </div>


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingEight">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapseEight" aria-expanded="false" aria-controls="collapseEight">
                            <h4>My customer has paid and amount has been cut from their bank account, but I have
                                not received any notification of the payment?</h4>
                        </div>
                        <div id="collapseEight" class="collapse" aria-labelledby="headingEight" data-parent="#accordion"
                            itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                In cases where your customer has made a payment and you have not received a success
                                notification from Swipez. Our systems will automatically reconcile the failed payment.
                                This automatic reconciliation process takes about 4-5 hours to complete.

                                <br /><br />During this automatic reconciliation process there are 2 possibilities:
                                <ul>
                                    <li>The payment is successful the system will automatically inform you and the
                                        customer via SMS / Email</li>
                                    <li>The payment is unsuccessful the money will be returned back to the customer
                                        within 3-5 working days.</li>
                                </ul>

                                Even after this you have a query regarding any of your transactions, you can write to us
                                for help on our support email id - support [@] swipez.in with the transaction details
                                like customer name, transaction id, amount and date of transaction.
                            </div>
                        </div>
                    </div>


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingNine">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapseNine" aria-expanded="false" aria-controls="collapseNine">
                            <h4>How to track offline payments via the billing app?</h4>
                        </div>
                        <div id="collapseNine" class="collapse" aria-labelledby="headingNine" data-parent="#accordion"
                            itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Not all your customers are going pay the bills you send them online. For this purpose we
                                have built a facility which allows you to settle your unpaid bills. To track offline
                                payments you tap on any of the sent bills from the <b>Collect</b> screen. Here you have
                                the option to Settle a bill via offline payment modes like Cash, Cheque, NEFT
                                etc.<br><br>
                                This way you can track all your customer payments made either online or offline via the
                                Collect it Billing app.
                            </div>
                        </div>
                    </div>


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingTen">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapseTen" aria-expanded="false" aria-controls="collapseTen">
                            <h4>Can I remind the customer for a bill that is already sent via the billing app?</h4>
                        </div>
                        <div id="collapseTen" class="collapse" aria-labelledby="headingTen" data-parent="#accordion"
                            itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, from the <b>Collect</b> screen tap on the customer entry. On the resulting screen
                                choose the Remind tab and send the customer a reminder to make your payment.
                            </div>
                        </div>
                    </div>


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingEleven">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapseEleven" aria-expanded="false" aria-controls="collapseEleven">
                            <h4>Can I login to Swipez web with my app login or the other way round?</h4>
                        </div>
                        <div id="collapseEleven" class="collapse" aria-labelledby="headingEleven"
                            data-parent="#accordion" itemscope itemprop="acceptedAnswer"
                            itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes you can use the same login to access your data via the web and access it via the
                                Collect it - Billing software app as well. Your invoices and payment requests are
                                visible in both places, so are your transactions and settlements.
                            </div>
                        </div>
                    </div>


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingTwelve">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapseTwelve" aria-expanded="false" aria-controls="collapseTwelve">
                            <h4>Can I provide a login to my team members?</h4>
                        </div>
                        <div id="collapseTwelve" class="collapse" aria-labelledby="headingTwelve"
                            data-parent="#accordion" itemscope itemprop="acceptedAnswer"
                            itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, you can create multiple roles and decide if you would like to give full control or
                                restrict access to certain features for your employees. To do this you will need access
                                your login from the Swipez billing web product and provide access to team members.
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
<script src="/assets/global/plugins/jquery.min.js" type="text/javascript"></script>

<script>
    if (document.getElementById('video-promo-container')) {
        document.getElementById('video-promo-container').addEventListener("click", function() {
            //   document.getElementById('video-promo').classList.remove("d-none")
            document.getElementById('video-play-button').classList.add("d-none")
            document.getElementById('video-text').classList.add("d-none")
            document.getElementById('youtube-video').innerHTML = `<iframe id="video-promo" class="" width="480" height="270" src="https://www.youtube.com/embed/y8DNizPUPsk" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen="" style="position:absolute; top:0px; left:0px; width:100%; height:100%"></iframe>`
            $("#video-promo")[0].src += "?rel=0&autoplay=1";
        });
    }

    var activeAppfeatureTab = 1;
    var appfeaturetabs = $('#appfeatureTab > li');
    var appfeaturetabLength = appfeaturetabs.length;

    var delay = 5000;
    var appfeatureVar = null;

    var tabChange3 = function () {
        var currTab = activeAppfeatureTab <= appfeaturetabLength ? activeAppfeatureTab++ : activeAppfeatureTab = 1;
        $('#appfeatureTab li:nth-child(' + currTab + ') a').tab('show');
    }

    var tabAppfeatureCycle = setInterval(tabChange3, delay);


    // Tab click event handler
    $(function () {
        $('#appfeatureTab a').on('click', function (event) {
            event.preventDefault();
            if (appfeatureVar !== undefined) {
                //console.log("myVar is defined")
                clearTimeout(appfeatureVar);
            }
            clearInterval(tabAppfeatureCycle);

            $(this).tab('show');
            activeAppfeatureTab = $(this).parent('li').index() + 1;
            tabAppfeatureCycle = null;

            appfeatureVar = setTimeout(function () {
                //console.log("inside function : " + delay)
                tabAppfeatureCycle = setInterval(tabChange3, delay)
            }, delay);
        });
    })

    function prevAppFeatureClick() {
        clearInterval(tabAppfeatureCycle);
        var currTab = activeAppfeatureTab == 1 ? appfeaturetabLength : --activeAppfeatureTab;
        activeAppfeatureTab = currTab;
        $('#appfeatureTab li:nth-child(' + currTab + ') a').tab('show');
    }

    function nextAppFeatureClick() {
        clearInterval(tabAppfeatureCycle);
        var currTab = activeAppfeatureTab < appfeaturetabLength ? ++activeAppfeatureTab : activeAppfeatureTab = 1;
        activeAppfeatureTab = currTab;
        $('#appfeatureTab li:nth-child(' + currTab + ') a').tab('show');
    }

    function downloadFile (clickedId) {
        var id = clickedId + 'Excel';
        document.getElementById(id).click();
    }

</script>
@endsection
