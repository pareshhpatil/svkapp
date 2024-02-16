@extends('layouts.guest',['title'=>'Register'])
@section('content')
<div id="appCapsule">

    <div class="section mt-2">
        <div class="card">
            <div class="card-body">
                <div class="p-1">
                    <form action="/register/submit" method="post">
                        @csrf
                        <div class="form-group basic">
                            <div class="input-wrapper">
                                <label class="label" for="name2">Your Name</label>
                                <input type="text" required class="form-control" id="name2" name="name" placeholder="Your name">
                                <i class="clear-input">
                                    <ion-icon name="close-circle"></ion-icon>
                                </i>
                            </div>
                        </div>
                        <div class="form-group basic ">
                            <div class="input-wrapper">
                                <label class="label " for="radio">Gender</label>
                                <div class="btn-group mt-1" role="group">
                                    <input type="radio" class="btn-check" required value="Male" checked name="gender" id="gender1">
                                    <label class="btn btn-outline-primary" for="gender1">Male</label>

                                    <input type="radio" class="btn-check" required value="Female" name="gender" id="gender2">
                                    <label class="btn btn-outline-primary" for="gender2">Female</label>


                                </div> <i class="clear-input">
                                    <ion-icon name="close-circle"></ion-icon>
                                </i>
                            </div>
                        </div>

                        <div class="form-group basic ">
                            <div class="input-wrapper">
                                <label class="label" for="mobile">Mobile</label>
                                <input type="text" readonly name="mobile" value="{{$mobile}}" id="mobile" required class="form-control" placeholder="Mobile number">
                                <i class="clear-input">
                                    <ion-icon name="close-circle"></ion-icon>
                                </i>
                            </div>
                        </div>
                        <div class="form-group basic">
                            <div class="input-wrapper">
                                <label class="label" for="email">Email (Optional)</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Email ID">
                                <i class="clear-input">
                                    <ion-icon name="close-circle"></ion-icon>
                                </i>
                            </div>
                        </div>
                        <div class="form-group basic ">
                            <div class="input-wrapper">
                                <label class="label" for="location">Location</label>
                                <input type="text" name="location" id="location" required class="form-control" placeholder="Location">
                                <i class="clear-input">
                                    <ion-icon name="close-circle"></ion-icon>
                                </i>
                            </div>
                        </div>
                        <div class="form-group basic ">
                            <div class="input-wrapper">
                                <label class="label " for="radio">User Type</label>
                                <div class="btn-group mt-1" role="group">
                                    <input type="radio" class="btn-check" required value="Passenger" name="user_type" id="btnradio1">
                                    <label class="btn btn-outline-primary" for="btnradio1">Passenger</label>

                                    <input type="radio" class="btn-check" required value="Driver" name="user_type" id="btnradio2">
                                    <label class="btn btn-outline-primary" for="btnradio2">Driver</label>

                                    <input type="radio" class="btn-check" required value="Vendor" name="user_type" id="btnradio3">
                                    <label class="btn btn-outline-primary" for="btnradio3">Vendor</label>

                                </div> <i class="clear-input">
                                    <ion-icon name="close-circle"></ion-icon>
                                </i>
                            </div>
                        </div>

                        <div class="mt-2">
                            <input type="hidden" name="link" value="{{$link}}">
                            <input type="hidden" name="token" id="token" value="">
                            <button type="submit" class="btn btn-primary btn-lg btn-block">Submit</button>
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
    </script>

</div>
@endsection
