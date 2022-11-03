@extends('home.master')
@include('home.googleauth')
@section('content')
<section class="jumbotron jumbotron-features bg-transparent py-5" id="header">
    <div class="container">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6 center-text">
                
                <h4 class="center-text">Set your password
                </h4>
                <p class="center-text">Swipez products help you make time for growth!</p>
                @isset($errors)
                @if($errors->any())
                <span class="color-red small text-left">{!! implode('', $errors->all('<div>:message</div>')) !!}</span>
                @endif
                @endisset
                @guest
                <form method="post" action="/login/passwordsave" >
                    <div class="row" >
                        <div class="col-12">
                            @if($company_name=='')
                            <input type="text" required  name="company_name" class="form-control big-text mb-2"  placeholder="Company name">
                            @else 
                            <input type="hidden"  name="company_name" class="form-control big-text mb-2"  >
                            @endif
                            <input type="password" required  name="password" class="form-control big-text mb-2"  placeholder="Set password">
                            <input type="password" required  name="password_confirmation" class="form-control big-text"  placeholder="Confirm password">
                            <input type="hidden" name="recaptcha_response" >
                            <input type="hidden" name="user_id" value="{{$user_id}}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        </div>
                        <div class="col-12 mt-2">
                            <button id="web-btn" class="btn btn-primary pull-left big-text" type="submit">Set password</button>
                        </div>
                    </div>
                </form>
                @else
                <a class="btn btn-lg btn-tertiary text-white" href="/merchant/dashboard">Dashboard</a>
                @endguest
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
