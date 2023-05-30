@extends('layouts.guest',['title'=>'Contact us'])
@section('content')
<div id="appCapsule">

  <div class="section mt-2">
    <div class="card">
      <div class="card-body">
        <div class="p-1">
          <div class="text-center">
            <h2 class="text-primary">Get in Touch</h2>
            <p>Fill the form to contact us</p>
          </div>
          <form action="/contact/submit" method="post">
            @csrf
            <div class="form-group basic animated">
              <div class="input-wrapper">
                <label class="label" for="name2">Your name</label>
                <input type="text" required class="form-control" name="name" placeholder="Your name">
                <i class="clear-input">
                  <ion-icon name="close-circle"></ion-icon>
                </i>
              </div>
            </div>

            <div class="form-group basic animated">
              <div class="input-wrapper">
                <label class="label" for="email2">Mobile</label>
                <input type="text" name="mobile" required class="form-control" id="email2" placeholder="Mobile number">
                <i class="clear-input">
                  <ion-icon name="close-circle"></ion-icon>
                </i>
              </div>
            </div>

            <div class="form-group basic animated">
              <div class="input-wrapper">
                <label class="label" for="textarea2">Message</label>
                <textarea id="textarea2" name="message" rows="4" class="form-control" placeholder="Message"></textarea>
                <i class="clear-input">
                  <ion-icon name="close-circle"></ion-icon>
                </i>
              </div>
            </div>

            <div class="mt-2">
              <button type="submit" class="btn btn-primary btn-lg btn-block">Send</button>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>

  <div class="section mt-2">
    <div class="card">
      <div class="card-body">
        <div class="p-1">
          <div class="text-center">
            <h2 class="text-primary">Our Contact</h2>
            <p class="card-text">
              contact@ridetrack.in<br>
              Mobile: 9730946150
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="section mt-2 mb-2">
    <div class="card">
      <div class="card-body">
        <div class="p-1">
          <div class="text-center">
            <h2 class="text-primary mb-2">Social Profiles</h2>

            <a href="https://www.facebook.com/RideTrack" target="_blank" class="btn btn-facebook btn-icon me-05">
              <ion-icon name="logo-facebook"></ion-icon>
            </a>

          
            <a href="https://api.whatsapp.com/send?phone=9730946150&text=Message from booking app" class="btn btn-whatsapp btn-icon me-05">
              <ion-icon name="logo-whatsapp"></ion-icon>
            </a>

          

            
          </div>
        </div>
      </div>
    </div>
  </div>



</div>
@endsection