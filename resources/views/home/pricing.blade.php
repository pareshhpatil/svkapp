@extends('home.master')
@section('title', 'Helping businesses collect payments faster by improving their business operations')

@section('content')
<section class="jumbotron py-4 bg-transparent" id="header">
    <div class="container">
        <div class="row">
            <div class="col text-center">
                <h1>Product pricing</h1>
                <h4>Pricing that adapts to your business needs.</h4>
            </div>
        </div>
    </div>
</section>
<!-- Laptop and large screen view -->
<section class="jumbotron py-4 bg-transparent d-none d-md-block" id="header">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-9 shadow p-3 mb-5 bg-white rounded">
                <div class="row justify-content-center">
                    <div class="col-5">
                        <img class="rounded mx-auto d-block pt-2 pb-4"
                            src="{!! asset('static/images/billing-software-pricing.png') !!}" alt="Card image cap">
                    </div>
                    <div class="col-7 align-self-center">
                        <p class="lead">Collect payments faster, organize your billing, taxation and customer data.</p>
                        <p>Annual plans from</p>
                        <h2 class="d-block my-3">₹<span data-monthly="15" data-annual="12">5,999</span>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-5">
                        <h3 class="card-title text-center">Billing software</h3>
                    </div>
                    <div class="col-7">
                        <a class="btn btn-sm btn-primary" href="https://www.swipez.in/merchant/package/confirm/O7MGk6h3Jgazj1Hy796ELA">Buy now</a>
                        <a class="btn btn-sm btn-outline-primary" href="{{ config('app.APP_URL') }}merchant/register">Start free</a>
                        <a class="align-middle ml-3" href="{{ route('home.pricing.billing') }}">Billing software pricing</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-9 shadow p-3 mb-5 bg-white rounded">
                <div class="row justify-content-center">
                    <div class="col-5">
                        <img class="rounded mx-auto d-block pt-2 pb-4"
                            src="{!! asset('static/images/online-transaction-charges-pricing.png') !!}"
                            alt="Card image cap">
                    </div>
                    <div class="col-7 align-self-center">
                        <p class="lead">Lowest online transaction rates in the business. No setup fees or hidden charges.
                        </p>
                        <p>Transaction charges starting from</p>
                        <h2 class="d-block my-3">0.50% <span class="lead">per transaction</span></h2>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-5">
                        <h3 class="card-title text-center">Payment gateway pricing</h3>
                    </div>
                    <div class="col-7">
                        <a class="btn btn-sm btn-primary" href="{{ config('app.APP_URL') }}merchant/register">Start Now</a>
                        <a class="align-middle ml-3" href="{{ route('home.pricing.onlinetransactions') }}">Payment gateway pricing</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-9 shadow p-3 mb-5 bg-white rounded">
                <div class="row justify-content-center">
                    <div class="col-5">
                        <img class="rounded mx-auto d-block pt-2 pb-4"
                            src="{!! asset('static/images/event-registration-pricing.png') !!}" alt="Card image cap">
                    </div>
                    <div class="col-7 align-self-center">
                        <p class="lead">Event registration and online ticketing for events of all sizes.</p>
                        <p>Annual plans from</p>
                        <h2 class="d-block my-3">₹<span data-monthly="15" data-annual="12">5,999</span>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-5">
                        <h3 class="card-title text-center">Event registrations</h3>
                    </div>
                    <div class="col-7">
                        <a class="btn btn-sm btn-primary" href="https://www.swipez.in/merchant/package/confirm/O7MGk6h3Jgazj1Hy796ELA">Buy now</a>
                        <a class="btn btn-sm btn-outline-primary" href="{{ config('app.APP_URL') }}merchant/register">Start free</a>
                        <a class="align-middle ml-3" href="{{ route('home.pricing.event') }}">Event booking pricing</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-9 shadow p-3 mb-5 bg-white rounded">
                <div class="row justify-content-center">
                    <div class="col-5">
                        <img class="rounded mx-auto d-block pt-2 pb-4"
                            src="{!! asset('static/images/booking-calendar-pricing.png') !!}" alt="Card image cap">
                    </div>
                    <div class="col-7 align-self-center">
                        <p class="lead">Manage time slot bookings for your venues and facilities.</p>
                        <p>Annual plans from</p>
                        <h2 class="d-block my-3">₹<span data-monthly="15" data-annual="12">5,999</span>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-5">
                        <h3 class="card-title text-center">Venue booking software</h3>
                    </div>
                    <div class="col-7">
                        <a class="btn btn-sm btn-primary" href="https://www.swipez.in/merchant/package/confirm/O7MGk6h3Jgazj1Hy796ELA">Buy now</a>
                        <a class="btn btn-sm btn-outline-primary" href="{{ config('app.APP_URL') }}merchant/register">Start free</a>
                        <a class="align-middle ml-3" href="{{ route('home.pricing.bookingcalendar') }}">Venue booking pricing</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-9 shadow p-3 mb-5 bg-white rounded">
                <div class="row justify-content-center">
                    <div class="col-5">
                        <img class="rounded mx-auto d-block pt-2 pb-4"
                            src="{!! asset('static/images/website-builder-pricing.png') !!}" alt="Card image cap">
                    </div>
                    <div class="col-7 align-self-center">
                        <p class="lead">Create a beautiful website with online payment collections facility for your
                            business.</p>
                        <p>Annual plans from</p>
                        <h2 class="d-block my-3">₹<span data-monthly="15" data-annual="12">3,499</span>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-5">
                        <h3 class="card-title text-center">Website builder</h3>
                    </div>
                    <div class="col-7">
                        <a class="btn btn-sm btn-primary" href="https://www.swipez.in/merchant/package/confirm/nJlKXH8tL62zPcTWEf4u-Q">Buy now</a>
                        <a class="btn btn-sm btn-outline-primary" href="{{ config('app.APP_URL') }}merchant/register">Start free</a>
                        <a class="align-middle ml-3" href="{{ route('home.pricing.websitebuilder') }}">Website builder pricing</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-9 shadow p-3 mb-5 bg-white rounded">
                <div class="row justify-content-center">
                    <div class="col-5">
                        <img class="rounded mx-auto d-block pt-2 pb-4"
                            src="{!! asset('static/images/url-shortener-pricing.png') !!}" alt="Card image cap">
                    </div>
                    <div class="col-7 align-self-center">
                        <p class="lead">Build and empower your brand using powerful, recognizable short links.</p>
                        <p>Annual plans from</p>
                        <h2 class="d-block my-3">₹0.015 <span class="lead">per link</span></h2>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-5">
                        <h3 class="card-title text-center">URL Shortener</h3>
                    </div>
                    <div class="col-7">
                        <a class="btn btn-sm btn-primary" href="{{ config('app.APP_URL') }}merchant/register">Start free</a>
                        <a class="align-middle ml-3" href="{{ route('home.pricing.urlshortener') }}">URL shortener pricing</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End of Laptop and large screen view -->
<!-- Mobile and smaller screen view -->
<section class="jumbotron py-4 bg-transparent d-md-none" id="header">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-11 shadow p-3 mb-5 bg-white rounded">
                <div class="row justify-content-center">
                    <div class="col-11">
                        <img class="rounded mx-auto d-block pt-2 pb-4"
                            src="{!! asset('static/images/billing-software-pricing.png') !!}" alt="Card image cap">
                        <h3 class="card-title text-center">Billing software</h3>
                    </div>
                    <div class="col-11 align-self-center">
                        <p>Collect payments faster, organize your billing, taxation and customer data.</p>
                        <p class="text-center">Annual plans from</p>
                        <h2 class="text-center my-3">₹<span data-monthly="15" data-annual="12">5,999</span>
                    </div>
                </div>
                <div class="row text-center">
                    <div class="col-11">
                        <a class="btn btn-sm btn-primary" href="https://www.swipez.in/merchant/package/confirm/O7MGk6h3Jgazj1Hy796ELA">Buy now</a>
                        <a class="btn btn-sm btn-outline-primary" href="{{ config('app.APP_URL') }}merchant/register">Start free</a>
                    </div>
                    <div class="col-11 pt-2">
                        <a class="ml-3" href="{{ route('home.pricing.billing') }}">Billing software pricing</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-11 shadow p-3 mb-5 bg-white rounded">
                <div class="row justify-content-center">
                    <div class="col-11">
                        <img class="rounded mx-auto d-block pt-2 pb-4"
                            src="{!! asset('static/images/online-transaction-charges-pricing.png') !!}"
                            alt="Card image cap">
                        <h3 class="card-title text-center">Payment gateway charges</h3>
                    </div>
                    <div class="col-11 align-self-center">
                        <p>Lowest online transaction rates in the business. No setup fees or hidden charges.
                        </p>
                        <p class="text-center">Transaction charges starting from</p>
                        <h2 class="text-center my-3">0.50% <span class="lead">per transaction</span></h2>
                    </div>
                </div>
                <div class="row text-center">
                    <div class="col-11">
                        <a class="btn btn-sm btn-primary" href="{{ config('app.APP_URL') }}merchant/register">Start now</a>
                    </div>
                    <div class="col-11 pt-2">
                        <a class="ml-3" href="{{ route('home.pricing.onlinetransactions') }}">Payment gateway pricing</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-11 shadow p-3 mb-5 bg-white rounded">
                <div class="row justify-content-center">
                    <div class="col-11">
                        <img class="rounded mx-auto d-block pt-2 pb-4"
                            src="{!! asset('static/images/event-registration-pricing.png') !!}" alt="Card image cap">
                        <h3 class="card-title text-center">Event bookings</h3>
                    </div>
                    <div class="col-11 align-self-center">
                        <p>Event registration and online ticketing for events of all sizes.</p>
                        <p class="text-center">Annual plans from</p>
                        <h2 class="text-center my-3">₹<span data-monthly="15" data-annual="12">5,999</span>
                    </div>
                </div>
                <div class="row text-center">
                    <div class="col-11">
                        <a class="btn btn-sm btn-primary" href="https://www.swipez.in/merchant/package/confirm/O7MGk6h3Jgazj1Hy796ELA">Buy now</a>
                        <a class="btn btn-sm btn-outline-primary" href="{{ config('app.APP_URL') }}merchant/register">Start free</a>
                    </div>
                    <div class="col-11 pt-2">
                        <a class="ml-3" href="{{ route('home.pricing.event') }}">Event booking pricing</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-11 shadow p-3 mb-5 bg-white rounded">
                <div class="row justify-content-center">
                    <div class="col-11">
                        <img class="rounded mx-auto d-block pt-2 pb-4"
                            src="{!! asset('static/images/booking-calendar-pricing.png') !!}" alt="Card image cap">
                        <h3 class="card-title text-center">Venue booking software</h3>
                    </div>
                    <div class="col-11 align-self-center">
                        <p>Manage time slot bookings for your venues and facilities.</p>
                        <p class="text-center">Annual plans from</p>
                        <h2 class="text-center my-3">₹<span data-monthly="15" data-annual="12">5,999</span>
                    </div>
                </div>
                <div class="row text-center">
                    <div class="col-11">
                        <a class="btn btn-sm btn-primary" href="https://www.swipez.in/merchant/package/confirm/O7MGk6h3Jgazj1Hy796ELA">Buy now</a>
                        <a class="btn btn-sm btn-outline-primary" href="{{ config('app.APP_URL') }}merchant/register">Start free</a>
                    </div>
                    <div class="col-11 pt-2">
                        <a class="ml-3" href="{{ route('home.pricing.bookingcalendar') }}">Booking calendar pricing</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-11 shadow p-3 mb-5 bg-white rounded">
                <div class="row justify-content-center">
                    <div class="col-11">
                        <img class="rounded mx-auto d-block pt-2 pb-4"
                            src="{!! asset('static/images/website-builder-pricing.png') !!}" alt="Card image cap">
                        <h3 class="card-title text-center">Website builder</h3>
                    </div>
                    <div class="col-11 align-self-center">
                        <p>Create a beautiful website with online payment collections facility for your
                            business.</p>
                        <p class="text-center">Annual plans from</p>
                        <h2 class="text-center my-3">₹<span data-monthly="15" data-annual="12">3,499</span>
                    </div>
                </div>
                <div class="row text-center">
                    <div class="col-11">
                        <a class="btn btn-sm btn-primary" href="https://www.swipez.in/merchant/package/confirm/nJlKXH8tL62zPcTWEf4u-Q">Buy now</a>
                        <a class="btn btn-sm btn-outline-primary" href="{{ config('app.APP_URL') }}merchant/register">Start free</a>
                    </div>
                    <div class="col-11 pt-2">
                        <a class="ml-3" href="{{ route('home.pricing.websitebuilder') }}">Website builder pricing</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-11 shadow p-3 mb-5 bg-white rounded">
                <div class="row justify-content-center">
                    <div class="col-11">
                        <img class="rounded mx-auto d-block pt-2 pb-4"
                            src="{!! asset('static/images/url-shortener-pricing.png') !!}" alt="Card image cap">
                        <h3 class="card-title text-center">URL Shortener</h3>
                    </div>
                    <div class="col-11 align-self-center">
                        <p>Build and empower your brand using powerful, recognizable short links.</p>
                        <p class="text-center">Annual plans from</p>
                        <h2 class="text-center d-block my-3">₹0.015 <span class="lead">per link</span></h2>
                    </div>
                </div>
                <div class="row text-center">
                    <div class="col-11">
                        <a class="btn btn-sm btn-primary" href="{{ config('app.APP_URL') }}merchant/register">Start free</a>
                        <!--<a class="btn btn-sm btn-outline-primary" href="/#">Buy now</a>-->
                    </div>
                    <div class="col-11 pt-2">
                        <a class="ml-3" href="{{ route('home.pricing.urlshortener') }}">URL shortener pricing</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End of Mobile and smaller screen view -->
<section class="jumbotron py-5 bg-primary" id="cta">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-12 mb-5">
                <h3 class="text-white">More questions?<br /><br />Reach out to our support team. We're here to help.
                </h3>
            </div>
            <div class="col-md-12">
                <a  data-toggle="modal" class="btn btn-primary btn-lg text-white bg-tertiary" href="#chatnowModal" onclick="showPanel()">Chat now</a>
                <a class="btn btn-primary btn-lg text-white bg-secondary" href="/getintouch/pricing">Send email</a>
            </div>
        </div>
    </div>
</section>
@endsection
