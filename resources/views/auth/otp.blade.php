@extends('layouts.guest',['title'=>'OTP'])
@section('content')
<div id="appCapsule" style="background-color: #ffffff;">

  <img src="/assets/img/banner.png?v=5" style="max-width: 100%;">
  <div class="section mt-2 text-center">
    <h3 class="text-primary"> SIDDHIVINAYAK TRAVELS HOUSE</h3>

  </div>
  <div class="section mb-5 p-2">
    @if($errors->any())

    <div class="alert alert-outline-primary alert-dismissible fade show" role="alert">
      {{ $errors->first('otp') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <form action="/login/validateotp" onsubmit="lod(true);" method="post">
      @csrf
      <label class="label" for="text4b">Enter 4 digit OTP number</label>
      <div class="form-group basic mt-1">

        <input type="text" required class="form-control verification-input" id="otp" autofocus inputmode="numeric" name="otp" pattern="[0-9]*" minlength="4" placeholder="••••" maxlength="4">
      </div>
      <div class="form-links mt-2">
        <div id="counter">
          <a href="#">Resend otp in </a> <span class="text-secondary" id="count"></span>
        </div>
        <div id="resend" style="display: none;">
          <a href="/login/otp/resend/{{$link}}">Send OTP </a>
        </div>
      </div>

      <div class=" transparent mt-2">
        <input type="hidden" name="mobile" value="{{$mobile}}">
        <input type="hidden" name="link" value="{{$link}}">
        <input type="hidden" name="token" id="token">
        <button type="submit" class="btn btn-primary btn-block btn-lg">Verify OTP</button>
      </div>

    </form>
  </div>

  <script src="https://unpkg.com/webtonative@1.0.43/webtonative.min.js"></script>

  <script>
    const {
      Messaging: FirebaseMessaging
    } = window.WTN.Firebase

    FirebaseMessaging.getFCMToken({
      callback: function(data) {
        document.getElementById('token').value = data.token;
        //store it in your backend to send notification
      }
    });

    var count = 60;
    var x = setInterval(function() {
      count = count - 1;

      document.getElementById("count").innerHTML = count;
      if (count < 1) {
        clearInterval(x);
        document.getElementById("counter").style.display = "none";
        document.getElementById("resend").style.display = "block";
      }
    }, 1000);
    document.getElementById("otp").click();
  </script>

</div>
@endsection