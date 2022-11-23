@extends('home.master')
@include('home.googleauth')
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
                <p>Easybiz just got better on Swipez. Moneycontrol and Swipez have partnered to bring you an improved version of Easybiz that will help you automate your business operations. 
                    You can now manage your receivables payables and GST filing of a single dashboard.
                </p>
                <h4>Key Benefits</h4>
                <ul>
                    <li>Manage your customers, vendors and franchisees from a single console</li>
                    <li>Collect payments upto 35% faster with the Easybiz online invoicing and collections system</li>
                    <li>Reconcile payments received via all payment modes centrally</li>
                    <li>Organize your expenses and make payouts to vendors centrally</li>
                    <li>Integrate seamlessly with Tally. Transfer data straight into Tally via a simple excel export</li>
                    <li>Send notifications and reminders to your customers from your dasboard</li>
                    <li>Automate GST tax filing</li>
                </ul>
                <p>We care about your business. Receive dedicated support for your account to help digitize your business operations faster.
                </p>
            </div>
            <div class="col-md-6 center-text">
                <img style="max-width: 50%;" class="mb-3" src="https://images.moneycontrol.com/images/common/headfoot/logo.png"/>
                <h4 class="center-text">Login to your Easybiz account
                </h4>
                <p class="center-text">Swipez products help you make time for growth!</p>
                <div class="alert alert-block alert-info text-left" >
                    It appears you have an Easybiz account with Moneycontrol. Your account has now been migrated to Swipez. Please validate your email to login to your account.
                </div>
                @guest
                @include('home.googleauth')
                <span id="easybiz-error" class="color-red small display-none"></span>
                <a id="easybiz-start_now" class="btn btn-lg btn-tertiary text-white display-none" href="">Dashboard</a>
                <form method="post" id="easybiz-form" onsubmit="return productRegister('easybiz');" >

                    <div class="row display-none" id="easybiz-successdiv">
                        <div class="col-12">

                            <div class="alert alert-block alert-success">
                                <p style="color: #ffffff;">Success! Thank you for registration</p>
                                <a class="btn btn-primary bg-tertiary" href="{{ config('app.APP_URL') }}merchant/register/complete">Dashboard</a>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="easybiz-formdiv">
                        <div class="col-12">
                            <div id="gSignInWrapper">
                                <div id="googleBtnweb" class="customGPlusSignIn">
                                    <span class="icon"><img alt="Google Logo" class="img-square-20px" src="{{asset('images/google.png')}}"></span>
                                    <span class="buttonText">Sign up with Google</span>
                                </div>
                            </div>

                            <div class="google-signup-or text-center overflow-hidden mb-2">
                                <div class="line-text float-left">&nbsp;</div>or<div class="line-text float-right">&nbsp;</div>
                                <br>
                            </div>
                            <input type="text" required id="easybiz-username" name="username" class="form-control big-text"  placeholder="Enter Email Or Mobile">
                            <input type="number"  id="easybiz-otp" name="otp" maxlength="4" min="4" class="form-control big-text display-none"  placeholder="Enter valid OTP">
                            <input type="password"  id="easybiz-password" name="password"  class="form-control big-text display-none mt-2"  placeholder="Enter Password">
                            <input type="hidden" name="recaptcha_response" id="captchaweb">
                            <input type="hidden" name="exist_type" value="2">
                            <input type="hidden" name="service_id" value="2">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        </div>
                        <div class="col-12 mt-2">
                            <button id="easybiz-btn" class="btn btn-primary pull-left big-text" type="submit">Validate your account</button>
                            <div id="forgot_p" style="text-align: right;float: right;display: none;">
                                <span class="small pull-right" style="text-align: right;"><a href="/login/forgot">Forgot password?</a></span>
                            </div>
                        </div>
                    </div>
                </form>
                <script>startApp();</script>
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
