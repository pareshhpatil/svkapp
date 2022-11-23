@extends('home.master')
@section('title', 'Looking to partners for Swipez billing products')

@section('content')

<section class="jumbotron jumbotron-features bg-transparent py-3" id="header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12 col-md-12 col-lg-6 col-xl-5 d-none d-lg-block">
                <h4>Swipez partners</h4>
                <h1>Better together</h1>
                <p class="lead mb-5">Become a Swipez partner and earn recurring revenue. Our products will help your
                    customers improve how they organize their business operations and payment collections.</p>
            </div>
            <div class="col-12 col-md-8 m-auto ml-lg-auto mr-lg-0 col-lg-6 pt-5 pt-lg-0 d-none d-lg-block">
                <img alt="Partner program to earn recurring revenue" class="img-fluid"
                    src="{!! asset('static/images/partner-program.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none text-center">
                <img alt="Partner program to earn recurring revenue" class="img-fluid"
                    src="{!! asset('static/images/partner-program.svg') !!}" />
                <h3 class="pt-3">Swipez partners</h3>
                <h1>Better together</h1>
                <p class="lead mb-5">Become a Swipez partner and earn recurring revenue. Our products will help your
                    customers improve how they organize their business operations and payment collections.</p>
            </div>
            <!-- end -->
        </div>
    </div>
</section>
<section class="jumbotron py-5 bg-secondary text-white" id="cta">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-12">
                <h4>No Investment Required | Competitive Pricing | Dedicated Technical & Customer Support</h4>
            </div>
        </div>
    </div>
</section>
<section id="steps" class="jumbotron bg-transparent py-4">
    <div class="container">
        <div class="zig">
            <div class="row">
                <div class="col-lg-2 col-xl-2"></div>
                <div class="col-sm-12 col-md-12 col-lg-8 col-xl-8">
                    <ul class="nav nav-pills" id="pills-tab" role="tablist">
                        <li class="nav-item partner-li ml-0 mt-2">
                            <a class="nav-link active" id="pills-home-tab" data-toggle="pill" data-target="#pills-home"
                                role="tab" aria-controls="pills-home" aria-selected="true">Partner program overview</a>
                        </li>
                        <li class="nav-item partner-li mt-2">
                            <a class="nav-link" id="pills-profile-tab" data-toggle="pill" data-target="#pills-profile"
                                role="tab" aria-controls="pills-profile" aria-selected="false">Why partner with us?</a>
                        </li>
                        <li class="nav-item partner-li mt-2">
                            <a class="nav-link" id="pills-contact-tab" data-toggle="pill" data-target="#pills-contact"
                                role="tab" aria-controls="pills-contact" aria-selected="false">Revenue share</a>
                        </li>
                        <li class="nav-item partner-li mt-2">
                            <a class="nav-link" id="joinus" data-toggle="pill" data-target="#pills-joinus" role="tab"
                                aria-controls="pills-joinus" aria-selected="false">Join us</a>
                        </li>
                    </ul>
                    <hr />
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                            aria-labelledby="pills-home-tab">
                            <p>
                                With India going digital, businesses need to automate their systems by going
                                digital as well. Businesses need a complete solution that takes care of payment
                                collections, accounting and reconciliation or customer retention. They must be able to
                                do this without having to learn some complex software. Swipez is an easy to use platform
                                that automates the business end to end. Swipez provides, bill presentment, accounting
                                integration, payment collections, Customer Retention tools and centralized accounting
                                under one roof. On-boarding existing users and getting started is all a matter of a few
                                minutes.
                            </p>
                            <p>
                                Swipez is relevant to Internet Service Providers (ISP), telecom network provider,
                                auto-dealer / service centre, educational institutions, hospitality providers or any
                                other business looking to automate business operations, payment collections and CRM
                                under
                                one roof.
                            </p>
                            <p>
                                The Swipez Partner Program is designed to reward Partners that are looking to provide
                                back office & operations management software solutions to their customers.
                            </p>
                            <div class="row text-center">
                                <div class="col-md-12 pt-2"><a class="btn btn-lg btn-tertiary text-white"
                                        onclick="$('#joinus').click();">Join us as a partner</a>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pills-profile" role="tabpanel"
                            aria-labelledby="pills-profile-tab">
                            <p>
                                Swipez Partners play a key role in delivering our products and services worldwide. We
                                are committed to working with our Partners to help them increase customer satisfaction
                                and build profitable, long-term businesses.
                            </p>
                            <div class="row">
                                <div class="col-md-3">
                                    <center>
                                        <svg aria-hidden="true" focusable="false" data-prefix="fas"
                                            data-icon="chart-line" class="h-8 text-secondary" role="img"
                                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                            <path fill="currentColor"
                                                d="M496 384H64V80c0-8.84-7.16-16-16-16H16C7.16 64 0 71.16 0 80v336c0 17.67 14.33 32 32 32h464c8.84 0 16-7.16 16-16v-32c0-8.84-7.16-16-16-16zM464 96H345.94c-21.38 0-32.09 25.85-16.97 40.97l32.4 32.4L288 242.75l-73.37-73.37c-12.5-12.5-32.76-12.5-45.25 0l-68.69 68.69c-6.25 6.25-6.25 16.38 0 22.63l22.62 22.62c6.25 6.25 16.38 6.25 22.63 0L192 237.25l73.37 73.37c12.5 12.5 32.76 12.5 45.25 0l96-96 32.4 32.4c15.12 15.12 40.97 4.41 40.97-16.97V112c.01-8.84-7.15-16-15.99-16z">
                                            </path>
                                        </svg>
                                        <h4 class="card-title text-secondary">Business Growth</h4>
                                        <p>
                                            Collaborate with us to scale your revenue with existing / new
                                            customers and industry connects.
                                        </p>
                                    </center>
                                </div>
                                <div class="col-md-3">
                                    <center>
                                        <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="wrench"
                                            class="h-8 text-secondary" role="img" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 512 512">
                                            <path fill="currentColor"
                                                d="M507.73 109.1c-2.24-9.03-13.54-12.09-20.12-5.51l-74.36 74.36-67.88-11.31-11.31-67.88 74.36-74.36c6.62-6.62 3.43-17.9-5.66-20.16-47.38-11.74-99.55.91-136.58 37.93-39.64 39.64-50.55 97.1-34.05 147.2L18.74 402.76c-24.99 24.99-24.99 65.51 0 90.5 24.99 24.99 65.51 24.99 90.5 0l213.21-213.21c50.12 16.71 107.47 5.68 147.37-34.22 37.07-37.07 49.7-89.32 37.91-136.73zM64 472c-13.25 0-24-10.75-24-24 0-13.26 10.75-24 24-24s24 10.74 24 24c0 13.25-10.75 24-24 24z">
                                            </path>
                                        </svg>
                                        <h4 class="card-title text-secondary">Technical Enablement</h4>
                                        <p>
                                            Build expertise on latest online payment technologies with our training and
                                            support programs.
                                        </p>
                                    </center>
                                </div>
                                <div class="col-md-3">
                                    <center>
                                        <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="bullhorn"
                                            class="h-8 text-secondary" role="img" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 576 512">
                                            <path fill="currentColor"
                                                d="M576 240c0-23.63-12.95-44.04-32-55.12V32.01C544 23.26 537.02 0 512 0c-7.12 0-14.19 2.38-19.98 7.02l-85.03 68.03C364.28 109.19 310.66 128 256 128H64c-35.35 0-64 28.65-64 64v96c0 35.35 28.65 64 64 64h33.7c-1.39 10.48-2.18 21.14-2.18 32 0 39.77 9.26 77.35 25.56 110.94 5.19 10.69 16.52 17.06 28.4 17.06h74.28c26.05 0 41.69-29.84 25.9-50.56-16.4-21.52-26.15-48.36-26.15-77.44 0-11.11 1.62-21.79 4.41-32H256c54.66 0 108.28 18.81 150.98 52.95l85.03 68.03a32.023 32.023 0 0 0 19.98 7.02c24.92 0 32-22.78 32-32V295.13C563.05 284.04 576 263.63 576 240zm-96 141.42l-33.05-26.44C392.95 311.78 325.12 288 256 288v-96c69.12 0 136.95-23.78 190.95-66.98L480 98.58v282.84z">
                                            </path>
                                        </svg>
                                        <h4 class="card-title text-secondary">Marketing Support</h4>
                                        <p>
                                            Access marketing best practices to plan and roll out joint marketing
                                            campaigns that will help expand your customer reach.
                                        </p>
                                    </center>
                                </div>
                                <div class="col-md-3">
                                    <center>
                                        <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="users"
                                            class="h-8 text-secondary" role="img"
                                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512">
                                            <path fill="currentColor"
                                                d="M96 224c35.3 0 64-28.7 64-64s-28.7-64-64-64-64 28.7-64 64 28.7 64 64 64zm448 0c35.3 0 64-28.7 64-64s-28.7-64-64-64-64 28.7-64 64 28.7 64 64 64zm32 32h-64c-17.6 0-33.5 7.1-45.1 18.6 40.3 22.1 68.9 62 75.1 109.4h66c17.7 0 32-14.3 32-32v-32c0-35.3-28.7-64-64-64zm-256 0c61.9 0 112-50.1 112-112S381.9 32 320 32 208 82.1 208 144s50.1 112 112 112zm76.8 32h-8.3c-20.8 10-43.9 16-68.5 16s-47.6-6-68.5-16h-8.3C179.6 288 128 339.6 128 403.2V432c0 26.5 21.5 48 48 48h288c26.5 0 48-21.5 48-48v-28.8c0-63.6-51.6-115.2-115.2-115.2zm-223.7-13.4C161.5 263.1 145.6 256 128 256H64c-35.3 0-64 28.7-64 64v32c0 17.7 14.3 32 32 32h65.9c6.3-47.4 34.9-87.3 75.2-109.4z">
                                            </path>
                                        </svg>
                                        <h4 class="card-title text-secondary">Community Support</h4>
                                        <p>
                                            Engage, share and learn from our strong and ever growing partner & merchant
                                            community.
                                        </p>
                                    </center>
                                </div>
                            </div>
                            <div class="row text-center">
                                <div class="col-md-12 pt-2"><a class="btn btn-lg btn-tertiary text-white"
                                        onclick="$('#joinus').click();">Join us as a partner</a>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pills-contact" role="tabpanel"
                            aria-labelledby="pills-contact-tab">
                            <p>
                                Swipez Partners play a key role in delivering our products and services worldwide. We
                                are committed to working with our Partners to help them build profitable, long-term
                                businesses.
                            </p>
                            <p>
                                As an approved Swipez Partner, you earn a percentage on every sale you make from the
                                Swipez product suite. As the Swipez product is licensed annually, we incentivize our
                                partners not just on the first year sale but also on subsequent renewals year on year.
                            </p>
                            <div class="row justify-content-center">
                                <div class="col-auto">
                                    <table class="table table-striped table-responsive">
                                        <thead>
                                            <tr class="bg-primary text-white">
                                                <th scope="col">Year</th>
                                                <th scope="col">Revenue Share</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th scope="row">1st Year</th>
                                                <td class="text-center">40%</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">2nd Year</th>
                                                <td class="text-center">20%</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">3rd Year onwards</th>
                                                <td class="text-center">10%</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <h4 class="mt-3">See how our partners are generating recurring revenue</h4>
                            <p>
                                Our Partners have the option of selling different Swipez packages and all our packages
                                offer meaningful incentives.
                            </p>
                            <p>
                                Here is an actual illustration of a Partner, who is growing rapidly by selling Swipez
                                packages.
                            </p>
                            <ul>
                                <li>One of our Partners has sold Swipez product units worth ₹ 3,96,000 in the past 5
                                    months has made a whopping ₹ 1,58,000. At the current rate this Partner stands to
                                    make over ₹ 4,00,000 within a year of joining the partner program.</li>
                                <li>On renewal by signed-up merchants additional revenues automatically adds to
                                    subsequent year’s earnings</li>
                                <li>All this from just one city and just in the past 5 months!</li>
                            </ul>
                            <div class="row justify-content-center">
                                <div class="col-auto">
                                    <table class="table table-striped table-responsive">
                                        <thead>
                                            <tr class="bg-primary text-white">
                                                <th scope="col">Month</th>
                                                <th scope="col">Sale value</th>
                                                <th scope="col">Commission</th>
                                                <th scope="col">Year 2 revenue</th>
                                                <th scope="col">Year 3 revenue</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th scope="row">{{ date("M-y", strtotime("-5 months")) }}</th>
                                                <td class="text-center">₹ 65,000</td>
                                                <td class="text-center">₹ 26,000</td>
                                                <td class="text-center">₹ 13,000</td>
                                                <td class="text-center">₹ 4,289</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">{{ date("M-y", strtotime("-4 months")) }}</th>
                                                <td class="text-center">₹ 71,500</td>
                                                <td class="text-center">₹ 28,600</td>
                                                <td class="text-center">₹ 14,300</td>
                                                <td class="text-center">₹ 4,292</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">{{ date("M-y", strtotime("-3 months")) }}</th>
                                                <td class="text-center">₹ 78,650</td>
                                                <td class="text-center">₹ 31,460</td>
                                                <td class="text-center">₹ 15,730</td>
                                                <td class="text-center">₹ 4,295</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">{{ date("M-y", strtotime("-2 months")) }}</th>
                                                <td class="text-center">₹ 86,515</td>
                                                <td class="text-center">₹ 34,606</td>
                                                <td class="text-center">₹ 17,303</td>
                                                <td class="text-center">₹ 4,298</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">{{ date("M-y", strtotime("-1 months")) }}</th>
                                                <td class="text-center">₹ 95,167</td>
                                                <td class="text-center">₹ 38,067</td>
                                                <td class="text-center">₹ 19,033</td>
                                                <td class="text-center">₹ 4,301</td>
                                            </tr>
                                            <tr>
                                                <th scope="row" colspan="2">Total Revenue Earned</th>
                                                <td class="text-center">₹ 1,58,733</td>
                                                <td class="text-center">₹ 79,366</td>
                                                <td class="text-center">₹ 21,475</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row text-center">
                                <div class="col-md-12 pt-2"><a class="btn btn-lg btn-tertiary text-white"
                                        onclick="$('#joinus').click();">Join us as a partner</a>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pills-joinus" role="tabpanel" aria-labelledby="joinus">
                            <iframe style="width: 100%;height: 520px;border:none;"
                                src="https://www.swipez.in/helpdesk/contactus/partner"></iframe>

                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-xl-2"></div>
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
                    onclick="$('#joinus').click();">Join us as a partner</a>
            </div>
        </div>
    </div>
</section>


@endsection

@section('customfooter')
<script>
    var x = location.hash;
    if (x != '') {
        $(x).click();
    }
</script>
@endsection
