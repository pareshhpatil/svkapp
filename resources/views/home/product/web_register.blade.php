@guest
@if($d_type=='web')
@include('home.googleauth')
@endif
<span id="{{$d_type}}-error" class="color-red small display-none"></span>
<a id="{{$d_type}}-start_now" class="btn btn-lg btn-tertiary text-white display-none" href="">Dashboard</a>
<form method="post" id="{{$d_type}}-form" onsubmit="return productRegister('{{$d_type}}');" >
    
    <div class="row display-none" id="{{$d_type}}-successdiv">
        <div class="col-12">
           
            <div class="alert alert-block alert-success">
                <p style="color: #ffffff;">Success! Thank you for registration</p>
                <a class="btn btn-primary bg-tertiary" href="{{ config('app.APP_URL') }}merchant/register/complete">Dashboard</a>
            </div>
        </div>
    </div>
    <div class="row" id="{{$d_type}}-formdiv">
        <div class="col-12">
            <div id="gSignInWrapper">
                <div id="googleBtn{{$d_type}}" class="customGPlusSignIn">
                    <span class="icon"><img alt="Google Logo" class="img-square-20px" src="{{asset('images/google.png')}}"></span>
                    <span class="buttonText">Sign up with Google</span>
                </div>
            </div>
            
            <div class="google-signup-or text-center overflow-hidden mb-2">
                <div class="line-text float-left">&nbsp;</div>or<div class="line-text float-right">&nbsp;</div>
                <br>
            </div>
            <input type="text" required id="{{$d_type}}-username" name="username" class="form-control big-text"  placeholder="Enter Email Or Mobile">
            <input type="number"  id="{{$d_type}}-otp" name="otp" maxlength="4" min="4" class="form-control big-text display-none"  placeholder="Enter valid OTP">
            <input type="hidden" name="recaptcha_response" id="captcha{{$d_type}}">
            <input type="hidden" @if($d_type=='web') id="service_id" @endif name="service_id" value="{{$service_id}}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
        </div>
        <div class="col-12 mt-2">
            <button id="{{$d_type}}-btn" class="btn btn-primary pull-left big-text" type="submit">Get started for free</button>
        </div>
    </div>
</form>
<script>startApp();</script>
@else
<a class="btn btn-lg btn-tertiary text-white" href="/merchant/dashboard">Dashboard</a>
@endguest
