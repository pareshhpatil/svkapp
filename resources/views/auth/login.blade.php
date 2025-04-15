@extends('layouts.guest',['title'=>'Login'])
@section('content')
<div id="appCapsule" style="background-color: #ffffff;">

  <img src="/assets/img/banner.png?v=5" style="max-width: 100%;">
  <div class="section mt-2 text-center">
    <h3 class="text-primary"> SIDDHIVINAYAK TRAVELS HOUSE</h3>

  </div>
  <div class="section mb-5 p-2">
    @if($errors->any())

    <div class="alert alert-outline-primary alert-dismissible fade show" role="alert">
      {{ $errors->first('mobile') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <form action="/login/sendotp" onsubmit="lod(true);" method="post">
      @csrf
      <div class="form-group boxed">
        <div class="input-wrapper">
          <label class="label" for="text4b">Enter your 10 digit mobile number</label>
          <input type="text" name="mobile" class="form-control" inputmode="numeric" autofocus pattern="[0-9]*" maxlength="10" minlength="10" placeholder="Mobile number">
          <i class="clear-input">
            <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
          </i>
        </div>
      </div>


      <div class=" transparent mt-2">
        <button type="submit" class="btn btn-primary btn-block btn-lg">Verify</button>
      </div>

    </form>
  </div>

</div>
@endsection