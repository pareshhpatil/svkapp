@extends('home.master')

@section('content')
<section class="jumbotron jumbotron-features bg-transparent py-5" id="header">
    <div class="container">
        <div class="row">
            <!-- for iPad and mobile -->
            <div class="card card-border-none d-sm-none pb-5 px-4">
                <div class="card-media">
                    <img alt="Access your billing software from your home" src="{!! asset('images/stay-home-stay-safe.svg') !!}"
                        class="img-fluid" />
                </div>
            </div>
            <!-- end -->
            <div class="col-md-6">
                <h2>Proud to partner with TradeIndia</h2>
                <p>TradeIndia and Swipez have partnered to digitize your business operations via a cloud based billing solution.
                </p>
                <h4>Advantages for your company</h4>
                <ul>
                    <li>Billing solution with invoicing and online payments</li>
                    <li>Organize your expenses and make payouts centrally</li>
                    <li>Automate GST tax filing</li>
                    <li>Professional website builder for your business</li>
                    <li>Free to try for 3 months</li>
                    <li>0 setup and onboarding fees</li>
                    <li>Dedicated support for your account</li>
                </ul>

            </div>
            <div class="col-md-6 center-text">
                <img src="https://tiimg.tistatic.com/new_website1/ti-design/images/tradeindia-logo.png"/>
                <h4 class="center-text">Register your Swipez account — it’s free!
                </h4>
                <p class="center-text">Swipez products help you digitize your business!</p>

                @include('home.product.web_register',['d_type' => "web"])
            </div>
        </div>

    </div>
</section>

<section class="jumbotron py-5" id="clients">
    <div class="container">
        <h2 class="text-center pb-5">Trusted by Great Companies</h2>
        <div class="row">
            <div class="col d-none d-lg-block">
                <img alt="Swipez client Amazon" class="img-grayscale" src="{!! asset('images/home/amazon.png') !!}" />
            </div>
            <div class="col d-none d-lg-block">
                <img alt="Swipez client Reliance Mutual Fund" class="img-grayscale"
                    src="{!! asset('images/home/reliance-mutual-fund.png') !!}" />
            </div>
            <div class="col d-none d-lg-block">
                <img alt="Swipez client Global Insurance" class="img-grayscale"
                    src="{!! asset('images/home/global-insurance.png') !!}" />
            </div>
            <div class="col d-none d-lg-block">
                <img alt="Swipez client Malaka Spice" class="img-grayscale"
                    src="{!! asset('images/home/malaka-spice.png') !!}" />
            </div>
            <div class="col d-none d-lg-block">
                <img alt="Swipez client IRIS GST" class="img-grayscale"
                    src="{!! asset('images/home/iris-gst.png') !!}" />
            </div>
            <div class="col-6 d-lg-none">
                <img alt="Swipez client Amazon" class="img-grayscale" src="{!! asset('images/home/amazon.png') !!}" />
            </div>
            <div class="col-6 d-lg-none">
                <img alt="Swipez client Reliance mutual fund" class="img-grayscale"
                    src="{!! asset('images/home/reliance-mutual-fund.png') !!}" />
            </div>
            <div class="col-6 d-lg-none">
                <img alt="Swipez client Global Insurance" class="img-grayscale"
                    src="{!! asset('images/home/global-insurance.png') !!}" />
            </div>
            <div class="col-6 d-lg-none">
                <img alt="Swipez client Malaka Spice" class="img-grayscale"
                    src="{!! asset('images/home/malaka-spice.png') !!}" />
            </div>
            <div class="col-12 d-lg-none text-center">
                <img alt="Swipez client IRIS GST" class="img-grayscale"
                    src="{!! asset('images/home/iris-gst.png') !!}" />
            </div>
        </div>
    </div>
</section>
@endsection
