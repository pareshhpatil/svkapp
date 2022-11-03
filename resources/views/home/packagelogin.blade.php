@extends('home.master')
@section('title', 'Helping businesses collect payments faster by improving their business operations')

@section('content')
<section class="jumbotron pt-4 pb-1 bg-transparent" id="header">
    <div class="container">
        <div class="row">
            <div class="col text-center">
                <h1>{{$detail->package_name}}</h1>
                <h4>₹<span data-monthly="15" data-annual="12"> {{$detail->total_cost}}</h4>
            </div>
        </div>
    </div>
</section>
<!-- Laptop and large screen view -->
<section class="jumbotron py-1 bg-transparent" id="">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-9 p-3 bg-white rounded">
                @include('home.googleauth')
                <div class="loader"><img src="{{asset('assets/admin/layout/img/Spinner.gif')}}"></div>
                <section class="jumbotron jumbotron-features bg-transparent py-1" id="header">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-2"></div>
                            <div class="col-md-8">
                                @if ($errors->any())
                                @foreach ($errors->all() as $error)
                                <span class="color-red small">{{ $error }}</span>
                                @endforeach
                                @endif

                                <a id="login-start_now" class="btn btn-lg btn-tertiary text-white display-none" href="">Dashboard</a>
                                <form method="post" action="/login" id="login-form" onsubmit="document.getElementById('login-btn').disabled = true;">
                                    <div class="row display-none" id="login-successdiv">
                                        <div class="col-12">
                                            <div class="alert alert-block alert-success">
                                                <p style="color: #ffffff;">Success! Thank you for registration</p>
                                                <a class="btn btn-primary bg-tertiary" href="{{ config('app.APP_URL') }}merchant/register/complete">Dashboard</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" id="login-formdiv">
                                        <div class="col-12">
                                            <div id="gSignInWrapper">
                                                <div id="googleBtnweb" class="customGPlusSignIn">
                                                    <span class="icon"><img alt="Google Logo" class="img-square-20px" src="{{asset('images/google.png')}}"></span>
                                                    <span class="buttonText">Log in with Google</span>
                                                </div>
                                            </div>
                                            <div id="name"></div>
                                            <script>
                                                startApp();
                                            </script>
                                            <div class="google-signup-or text-center overflow-hidden mb-2">
                                                <div class="line-text float-left">&nbsp;</div>or<div class="line-text float-right">&nbsp;</div>
                                                <br>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-12">
                                                    <input type="text" required id="login-username" name="email_id" value="{{ old('email_id') }}" class="form-control big-text" placeholder="Enter Email Or Mobile">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <input type="password" required id="login-password" name="password" class="form-control big-text" placeholder="Enter Password">
                                                </div>
                                            </div>

                                            <input type="number" id="login-otp" name="otp" maxlength="4" min="4" class="form-control big-text display-none" placeholder="Enter valid OTP">
                                            <input type="hidden" name="recaptcha_response" id="captcha1">
                                            <input type="hidden" id="service_id" name="service_id" value="{{$service_id}}">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        </div>
                                        <div class="col-12 mt-2">
                                            <button id="login-btn" class="btn btn-primary pull-left big-text" type="submit">Login</button>
                                            <span class="small pull-left"><a href="/merchant/register">New to Swipez?</a></span>
                                            <div style="text-align: right;float: right;">
                                                <span class="small pull-right" style="text-align: right;"><a href="/login/forgot">Forgot password?</a></span>
                                            </div>

                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </section>
            </div>
        </div>
    </div>

</section>
<!-- End of Laptop and large screen view -->
<!-- Mobile and smaller screen view -->

<!-- End of Mobile and smaller screen view -->
<section class="jumbotron py-5 bg-primary" id="cta">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-12 mb-5">
                <h3 class="text-white">More questions?<br /><br />Reach out to our support team. We're here to help.
                </h3>
            </div>
            <div class="col-md-12">
                <a class="btn btn-primary btn-lg text-white bg-tertiary" href="javascript:void(groove.widget.toggle())">Chat now</a>
                <a class="btn btn-primary btn-lg text-white bg-secondary" href="/getintouch/pricing">Send email</a>
            </div>
        </div>
    </div>
</section>
@endsection