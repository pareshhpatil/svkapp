@extends('layouts.app',['title'=>'FAQ','menu'=>0])
@section('content')
<div id="appCapsule">


  <div class="section mt-2 text-center">
    <div class="card">
      <div class="card-body pt-3 pb-3">
        <img src="assets/img/faq.png?v=2" alt="image" class="imaged w-50 ">
        <h2 class="mt-2">Frequently Asked <br> Questions</h2>
      </div>
    </div>
  </div>

  <div class="section inset mt-2">
    <div class="accordion" id="accordionExample1">
      <div class="accordion-item">
        <h2 class="accordion-header">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#accordion01">
            What is a ride track mobile app?
          </button>
        </h2>
        <div id="accordion01" class="accordion-collapse collapse" data-bs-parent="#accordionExample1">
          <div class="accordion-body">
            A cab ride track mobile app is an application that allows you to track and monitor your cab rides in real-time. It typically provides features like GPS tracking, driver information, and trip history to enhance your overall cab riding experience.
          </div>
        </div>
      </div>
      <div class="accordion-item">
        <h2 class="accordion-header">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#accordion02">
            How does a cab ride tracking app work?
          </button>
        </h2>
        <div id="accordion02" class="accordion-collapse collapse" data-bs-parent="#accordionExample1">
          <div class="accordion-body">
            When cab is assigned for you, it uses GPS technology to track the cab's location in real-time. You can view the driver's details, track their arrival, and monitor your journey on a map.
          </div>
        </div>
      </div>
      <div class="accordion-item">
        <h2 class="accordion-header">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#accordion03">
            Which platforms are supported by the cab ride tracking app?
          </button>
        </h2>
        <div id="accordion03" class="accordion-collapse collapse" data-bs-parent="#accordionExample1">
          <div class="accordion-body">
            Ride track app is available on both iOS and Android platforms. You can download them from the respective app stores (such as the Apple App Store or Google Play Store) and install them on your compatible mobile device.
          </div>
        </div>
      </div>
      <div class="accordion-item">
        <h2 class="accordion-header">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#accordion04">
            How accurate is the GPS tracking in the app?
          </button>
        </h2>
        <div id="accordion04" class="accordion-collapse collapse" data-bs-parent="#accordionExample1">
          <div class="accordion-body">
            The accuracy of GPS tracking can vary depending on various factors like your device's GPS capabilities, signal strength, and environmental conditions. In general, the GPS technology used in ride tracking apps is quite reliable and provides accurate location information for tracking your cab rides.
          </div>
        </div>
      </div>
      <div class="accordion-item">
        <h2 class="accordion-header">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#accordion05">
            Can I track the driver's arrival time?
          </button>
        </h2>
        <div id="accordion05" class="accordion-collapse collapse" data-bs-parent="#accordionExample1">
          <div class="accordion-body">
            Yes, Ride track app provide real-time updates on the driver's location and estimated arrival time.
            You can monitor the driver's progress on a map and receive notifications or alerts when they are nearing your pickup location.
          </div>
        </div>
      </div>
      <div class="accordion-item">
        <h2 class="accordion-header">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#accordion06">
            Can I rate and review the driver?
          </button>
        </h2>
        <div id="accordion06" class="accordion-collapse collapse" data-bs-parent="#accordionExample1">
          <div class="accordion-body">
            Yes, Ride track app often allow passengers to rate their drivers and provide feedback on their experience.
            This rating system helps maintain quality standards and enables other users to make informed decisions when choosing a driver for their rides.
          </div>
        </div>
      </div>
      <div class="accordion-item">
        <h2 class="accordion-header">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#accordion07">
            Can I share my ride details with others?
          </button>
        </h2>
        <div id="accordion07" class="accordion-collapse collapse" data-bs-parent="#accordionExample1">
          <div class="accordion-body">
            Yes, you can share your ride details with others for safety purposes. You can often share the driver's information, vehicle details, and live tracking link with trusted contacts, ensuring they can monitor your journey in real-time.
          </div>
        </div>
      </div>


    </div>
  </div>


  <div class="section mt-3 mb-3">
    <div class="card bg-primary">
      <div class="card-body text-center">
        <h5 class="card-title">Still have question?</h5>
        <p class="card-text">
          Feel free to contact us
        </p>
        <a href="/contact-us" class="btn btn-dark">
          <ion-icon name="mail-open-outline"></ion-icon> Contact
        </a>
      </div>
    </div>
  </div>

</div>
@endsection