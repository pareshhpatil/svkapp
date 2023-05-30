@extends('layouts.guest',['title'=>'Get Started'])
@section('content')
<div id="appCapsule">

  <div class="carousel-slider splide">
    <div class="splide__track">
      <ul class="splide__list">
        <li class="splide__slide p-2">
          <p>Login dashboard to navigate.</p>
          <img src="/assets/img/sample/photo/11.png" alt="alt" class="imaged w-100 square mb-4">
        </li>
        <li class="splide__slide p-2">
          <p>Track your Ride history</p>
          <img src="/assets/img/sample/photo/12.png" alt="alt" class="imaged w-100 square mb-4">
        </li>
        <li class="splide__slide p-2">
          <p>Your Ride details</p>
          <img src="/assets/img/sample/photo/13.png" alt="alt" class="imaged w-100 square mb-4">
        </li>
        <li class="splide__slide p-2">
          <p>Track your Driver</p>
          <img src="/assets/img/sample/photo/14.png" alt="alt" class="imaged w-100 square mb-4">

        </li>
        <li class="splide__slide p-2">
          <p>Emergency Panic option</p>
          <img src="/assets/img/sample/photo/15.png" alt="alt" class="imaged w-100 square mb-4">
        </li>
        <li class="splide__slide p-2">
          <p>Manage your Ride calendar</p>
          <img src="/assets/img/sample/photo/16.png" alt="alt" class="imaged w-100 square mb-4">
        </li>
        <li class="splide__slide p-2">
          <p>Book your own Ride</p>
          <img src="/assets/img/sample/photo/17.png" alt="alt" class="imaged w-100 square mb-4">
        </li>

      </ul>
    </div>
  </div>
  <!-- * carousel slider -->

  <div class="carousel-button-footer">
    <div class="row">
      <div class="col-12">
        <a href="/dashboard" class="btn btn-primary btn-lg btn-block">Get Started</a>
      </div>

    </div>
  </div>







</div>
@endsection