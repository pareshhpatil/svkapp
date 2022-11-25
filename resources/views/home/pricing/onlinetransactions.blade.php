@extends('home.master')
@section('title', 'Helping businesses collect payments faster by improving their business operations')

@section('content')
<section class="jumbotron py-4 bg-transparent" id="header">
    <div class="container">
        <div class="row">
            <div class="col text-center">
                <h1 class="display-4">Online transaction charges</h1>
                <h2 class="h4 gray-600 d-none d-md-block">The lowest payment gateway fees in the industry</h2>
                <h2 class="h4 gray-600 d-none d-md-block">Perfect for small business owners</h2>
                <h2 class="h4 text-primary">Register today and get online transactions worth ₹5 lakhs at no charge 🚀</h2>
            </div>
        </div>
    </div>
</section>
<!-- Laptop and large screen view -->
<section class="jumbotron py-0 bg-transparent d-none d-md-block" id="header">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-9 shadow p-3 mb-5 bg-white rounded">
                <div class="row">
                    <div class="col-6 align-self-center text-center">
                        <p class="lead">One-time setup fee</p>
                        <p class="lead"><span class="h2">₹0</span></p>
                    </div>
                    <div class="col-6 align-self-center text-center">
                        <p class="lead">Annual maintenance fee</p>
                        <p class="lead"><span class="h2">₹0</span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-9 shadow p-3 mb-5 bg-white rounded">
                <p class="text-uppercase gray-400 mb-0">Did you know?</p>
                <h3 class="text-primary mb-0">Over 65% online payments are done using UPI and Debit cards</h3>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-9 shadow p-3 mb-5 bg-white rounded">
                <div class="row justify-content-center">
                    <div class="col-12">
                        <h2 class="card-title text-center">UPI</h2>
                    </div>
                    <div class="col-5">
                        <img class="rounded mx-auto d-block pt-2 pb-4" src="{!! asset('static/images/upi.png') !!}"
                            alt="Card image cap">
                    </div>
                    <div class="col-7 align-self-center text-center">
                        <p class="lead"><span class="h2">0.5%</span> for transactions less than ₹2000
                        </p>
                        <p class="lead"><span class="h2">1%</span> for transactions more than ₹2000
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-9 shadow p-3 mb-5 bg-white rounded">
                <div class="row justify-content-center">
                    <div class="col-12">
                        <h2 class="card-title text-center">Debit cards</h2>
                    </div>
                    <div class="col-5">
                        <img class="rounded mx-auto d-block pt-2 pb-4" src="{!! asset('static/images/cards.png') !!}"
                            alt="Card image cap">
                    </div>
                    <div class="col-7 align-self-center text-center">
                        <p class="lead"><span class="h2">0.50%</span> for transactions less than ₹2000</p>
                        <p class="lead"><span class="h2">1%</span> for transactions more than ₹2000</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-9 shadow p-3 mb-5 bg-white rounded">
                <div class="row justify-content-center">
                    <div class="col-12">
                        <h2 class="card-title text-center">Credit cards</h2>
                    </div>
                    <div class="col-5">
                        <img class="rounded mx-auto d-block pt-2 pb-4" src="{!! asset('static/images/cards.png') !!}"
                            alt="Card image cap">
                    </div>
                    <div class="col-7 align-self-center text-center">
                        <p><span class="h3">2.1%</span> for Visa, Mastercard, RuPay cards</p>
                        <p><span class="h3">2.8%</span> for Amex, Diners</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-9 shadow p-3 mb-5 bg-white rounded">
                <div class="row justify-content-center">
                    <div class="col-12">
                        <h2 class="card-title text-center">Net banking</h2>
                    </div>
                    <div class="col-5">
                        <img class="rounded mx-auto d-block pt-2 pb-4"
                            src="{!! asset('static/images/netbanking.png') !!}" alt="Card image cap">
                    </div>
                    <div class="col-7 align-self-center text-center">
                        <p class="lead">Over 50+ banks available for net banking</p>
                        <p class="lead"><span class="h2">2.1%</span></p>
                        <p>Fixed rates available for business to business transactions <a href="/contactus">Get in
                                touch</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-9 shadow p-3 mb-2 bg-white rounded">
                <div class="row justify-content-center">
                    <div class="col-12">
                        <h2 class="card-title text-center">Wallets</h2>
                    </div>
                    <div class="col-5">
                        <img class="rounded mx-auto d-block pt-2 pb-4" src="{!! asset('static/images/wallet.png') !!}"
                            alt="Card image cap">
                    </div>
                    <div class="col-7 align-self-center text-center">
                        <p class="lead">Collect via 9 wallets</p>
                        <p class="lead"><span class="h2">2.1%</span></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-9 text-right">GST applicable on transaction fee</div>
            <div class="col-9 text-center mb-5 mt-5">
                <a href="{{ config('app.APP_URL') }}merchant/register" class="btn btn-primary">Get started</a>
                <a href="{{ route('home.partnerbenefits') }}" class="btn btn-primary" target="_blank">Know more</a>
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
                <div class="row">
                    <div class="col-9 align-self-center text-center">
                        <p class="lead">One-time setup fee</p>
                    </div>
                    <div class="col-3 align-self-center text-center">
                        <p class="lead"><span class="h2">₹0</span></p>
                    </div>
                    <div class="col-9 align-self-center text-center">
                        <p class="lead">Annual maintenance fee</p>
                    </div>
                    <div class="col-3 align-self-center text-center">
                        <p class="lead"><span class="h2">₹0</span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-11 shadow p-3 mb-5 bg-white rounded">
                <p class="text-uppercase gray-400 mb-0">Did you know?</p>
                <h3 class="text-primary mb-0">Over 65% online payments are done using UPI and Debit cards</h3>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-11 shadow p-3 mb-5 bg-white rounded">
                <div class="row justify-content-center">
                    <div class="col-11">
                        <h2 class="card-title text-center">UPI</h2>
                        <img class="rounded mx-auto d-block pt-2 pb-4" src="{!! asset('static/images/upi.png') !!}"
                            alt="Card image cap">
                    </div>
                    <div class="col-11 text-center">
                        <p class="lead"><span class="h2">0.5%</span> for transactions less than ₹2000
                        </p>
                        <p class="lead"><span class="h2">1.0%</span> for transactions more than ₹2000
                        </p>
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
                        <h2 class="card-title text-center">Debit cards</h2>
                        <img class="rounded mx-auto d-block pt-2 pb-4" src="{!! asset('static/images/cards.png') !!}"
                            alt="Card image cap">
                    </div>
                    <div class="col-11 text-center">
                        <p><span class="h3">0.50%</span> less than ₹2000</p>
                        <p><span class="h3">1%</span> more than ₹2000</p>
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
                        <h2 class="card-title text-center">Credit cards</h2>
                        <img class="rounded mx-auto d-block pt-2 pb-4" src="{!! asset('static/images/cards.png') !!}"
                            alt="Card image cap">
                    </div>
                    <div class="col-11 text-center">
                        <p><span class="h3">2.1%</span> for Visa, Mastercard, RuPay cards</p>
                        <p><span class="h3">2.8%</span> for Amex, Diners</p>
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
                        <h2 class="card-title text-center">Net banking</h2>
                        <img class="rounded mx-auto d-block pt-2 pb-4"
                            src="{!! asset('static/images/netbanking.png') !!}" alt="Card image cap">
                    </div>
                    <div class="col-11 text-center">
                        <p class="lead">Over 50+ banks available for net banking</p>
                        <p class="lead"><span class="h2">2.1%</span></p>
                        <p>Fixed rates available for business to business transactions <a href="/contactus">Get in
                                touch</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-11 shadow p-3 mb-2 bg-white rounded">
                <div class="row justify-content-center">
                    <div class="col-11">
                        <h2 class="card-title text-center">Wallets</h2>
                        <img class="rounded mx-auto d-block pt-2 pb-4" src="{!! asset('static/images/wallet.png') !!}"
                            alt="Card image cap">
                    </div>
                    <div class="col-11 text-center">
                        <p class="lead">Collect via 9 wallets</p>
                        <p class="lead"><span class="h2">2.1%</span></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-11 mb-4">GST applicable on transaction fee</div>
            <div class="col-11 text-right">
                <p class="text-center"><a href="{{ config('app.APP_URL') }}merchant/register" class="btn btn-primary">Get started</a></p>
                <p class="text-center"><a href="{{ route('home.partnerbenefits') }}" class="btn btn-primary" target="_blank">Know more</a></p>
            </div>
        </div>
    </div>
</section>
<!-- End of Mobile and smaller screen view -->
<section class="jumbotron py-5 bg-primary" id="cta">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-12 mb-5">
                <h3 class="text-white">Any queries?<br /><br />Reach out to our support team. We're here to help.
                </h3>
            </div>
            <div class="col-md-12">
                <a class="btn btn-primary btn-lg text-white bg-tertiary"
                    href="javascript:void(groove.widget.toggle())">Chat now</a>
                <a class="btn btn-primary btn-lg text-white bg-secondary"
                    href="/getintouch/payment-gateway-charges">Send email</a>
            </div>
        </div>
    </div>
</section>
@endsection
