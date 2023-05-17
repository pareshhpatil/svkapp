@extends('layouts.guest',['title'=>'Thank you'])
@section('content')
<div id="appCapsule">

  <div class="section">
    <div class="splash-page mt-5 mb-5">
    <img src="assets/img/login-page.png" style="max-width: 100%;">
    <br>
    <br>
    <br>

      <h1>Thank you</h1>
      <h4 class="mb-2">Your message has been received</h4>
      <p>
        We will get back to you soon regarding this.
      </p>
    </div>
  </div>

  <div class="fixed-bar">
    <div class="row">
      <div class="col-6">
        <a href="/dashboard" class="btn btn-lg btn-outline-secondary btn-block goBack">Go Back</a>
      </div>
      <div class="col-6">
        <a href="/contact-us" class="btn btn-lg btn-primary btn-block">Contact Us</a>
      </div>
    </div>
  </div>

</div>
@endsection