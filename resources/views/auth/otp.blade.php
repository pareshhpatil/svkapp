@extends('layouts.guest',['title'=>'OTP'])
@section('content')
<div id="appCapsule" style="background-color: #ffffff;">

  <img src="/assets/img/login-page.png" style="max-width: 100%;">
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

    <form action="/login/validateotp" method="post">
      @csrf
      <label class="label" for="text4b">Enter 4 digit OTP number</label>
      <div class="form-group basic mt-1">

        <input type="text" required class="form-control verification-input" name="otp" pattern="[0-9]*" minlength="4" placeholder="••••" maxlength="4">
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
        <button type="submit" class="btn btn-primary btn-block btn-lg">Verify OTP</button>
      </div>

    </form>
  </div>

  <script>
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
  </script>

</div>
@endsection