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
                <h2>Stay home, stay safe</h2>
                <p>With the current government guidelines to stay home, we know how important the ability to work from
                    home is for businesses right now. Even in these uncertain times business continuity is of key
                    importance to all business owners.
                </p>
                <h4>Work from home, collect payments online</h4>
                <p> In the spirit of helping the business community, we are providing our <b><a
                            href="{{ route('home.billing') }}" class="text-small pb-0 text-secondary"
                            target="_blank">Billing
                            software</a> at ₹ 0 cost</b> for the duration of this pandemic. Our team has been setup up
                    with remote working capabilities and are available to assist you with data setup, migration and on
                    boarding during this period.
                </p>
            </div>
            <div class="col-md-6 center-text">
                <h4 class="center-text">Register your account — it’s free!
                </h4>
                <p class="center-text">Swipez products help you work from home!</p>

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
