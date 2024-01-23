@extends('layouts.app')
@section('content')
<style>
    .timeline:before {
        bottom: 60px;
        top: 28px;
    }

    body.dark-mode .text-black {
        color: #fff !important;
        background: #20162a !important;
    }

    body.dark-mode .listview {
        color: #fff;
        background: #030108;
        border-top-color: #030108;
        border-bottom-color: #030108;
    }

    .bg-red {
        background: #e8481e !important;
    }

    .dialogbox .modal-dialog .modal-content {
        max-width: inherit;
        max-height: inherit;
    }

    .custom-control-input {
        position: absolute;
        border: none;
    }

    #map-canvas {
        height: 630px;
        width: 100%;
    }

    .custom-map-control-button {
        background-color: transparent;
        border: none;
        cursor: pointer;
        bottom: 30px !important;
        left: 10px !important;
    }

    .wallet-card .wallet-footer .item .icon-wrapper {
        background: #e8481e;
        width: 35px;
        height: 35px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        color: #fff;
        font-size: 20px;
        margin-bottom: 10px;
    }

    .transactions .item {
        padding: 10px 24px;
    }

    .marker-label {
        margin-top: 80px;
        font-size: 12px;
        line-height: 1em;
        border-radius: 100px;
        color: #ffffff !important;
        letter-spacing: 0;
        height: 22px;
        min-width: 22px;
        width: auto;
        padding: 5px 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 400;
        background: #1DCC70 !important;
    }

    .marker-label-user {
        margin-top: 60px;
        font-size: 12px;
        line-height: 1em;
        border-radius: 100px;
        color: #ffffff !important;
        letter-spacing: 0;
        height: 22px;
        min-width: 22px;
        width: auto;
        padding: 5px 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 400;
        background: #1DCC70 !important;
    }
</style>
<script src="https://maps.googleapis.com/maps/api/js?key={{env('MAP_KEY')}}"></script>

<div id="appCapsule" class="full-height">

    <div id="app" class=" ">
        <div id="map-canvas"></div>
    </div>

</div>
@php
$photo=($data['driver']['photo']!='')? $data['driver']['photo'] : '/assets/img/driver.png';
$user_icon=($data['passenger']['gender']!='Male')? 'https://app.svktrv.in/assets/img/map-female.png' : 'https://app.svktrv.in/assets/img/map-male.png';
@endphp
<div class="modal fade action-sheet show" id="actionSheet" tabindex="-1" role="dialog" aria-modal="true" style="display: block;top: inherit;">
    <div class="modal-dialog" role="document" style="bottom: 0px;">
        <div class="modal-content">

            <div class="modal-body">
                <div class="transactions">
                    <!-- item -->
                    <a href="#" class="item">
                        <div class="detail">
                            <img src="{{$photo}}" alt="img" class="image-block imaged w48 img-circle">
                            <div>
                                <strong>{{$data['driver']['name']}}</strong>
                                <strong id="arr" style="display: none;" class="text-primary">Arriving in <span id="duration"></span> </strong>
                                <!-- <strong class="text-info">Speed : <span id="speed"></span></strong> -->
                                <p>{{$data['vehicle']['number']}}</p>
                            </div>
                        </div>
                        <div class="right">
                            <div onclick="window.location.assign('tel:{{$data['driver']['mobile']}}', '_system');" class="text-danger"> <ion-icon name="call-outline" style="font-size: 25px;"></ion-icon></div>
                        </div>
                    </a>
                </div>
                <div class=" ">
                    <div class="wallet-card" style="box-shadow: none;padding: 0;padding-bottom: 10px;">
                        <!-- Balance -->
                        <!-- Wallet Footer -->
                        <div class="wallet-footer" style="padding-top: 10px;">
                            <div v-if="data.ride.status==1" class="item mb-1">
                                <a href="#" onclick="navigate();">
                                    <div class="icon-wrapper bg-success">
                                        <ion-icon name="navigate-outline"></ion-icon>
                                    </div>
                                    <strong>Navigate</strong>
                                </a>
                            </div>

                            <div class="item">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#helpmodal">
                                    <div class="icon-wrapper bg-warning">
                                        <ion-icon name="chatbubble-ellipses-outline"></ion-icon>
                                    </div>
                                    <strong>Help</strong>
                                </a>
                            </div>

                            <div class="item">
                                <a href="whatsapp://send?text=Hey, Please track my ride {{$data['link']}}" data-action="share/whatsapp/share" id="shareBtn">
                                    <div class="icon-wrapper bg-info">
                                        <ion-icon name="logo-whatsapp"></ion-icon>
                                    </div>
                                    <strong>Share</strong>
                                </a>
                            </div>

                        </div>
                        <!-- * Wallet Footer -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@if(Session::get('user_type')!=3)
<div class="modal fade dialogbox" id="helpmodal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Help</h5>
            </div>
            <form action="/passenger/help" method="post">
                @csrf
                <div class="modal-body text-start mb-2">
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="text1">Enter Message</label>
                            <textarea rows="2" type="text" name="message" class="form-control" placeholder="Enter message" maxlength="250"></textarea>
                            <i class="clear-input">
                                <ion-icon name="close-circle"></ion-icon>
                            </i>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="btn-inline">
                        <input type="hidden" :value="{{$data['ride_passenger']['id']}}" name="ride_passenger_id">
                        <input type="hidden" :value="{{$data['ride_passenger']['ride_id']}}" name="ride_id">
                        <button type="button" class="btn btn-text-secondary" data-bs-dismiss="modal">CLOSE</button>
                        <button type="submit" class="btn btn-text-primary">SEND</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection

@section('footer')

<script src="https://unpkg.com/webtonative@1.0.43/webtonative.min.js"></script>




<script>
    function startlocation() {
        window.WTN.backgroundLocation.start({
            callback: false,
            apiUrl: "https://app.svktrv.in/ride/track/{{$ride_id}}",
            timeout: 10,
            data: "userid1",
            backgroundIndicator: true,
            pauseAutomatically: true,
            distanceFilter: {{env('DISTANCE_FILTER')}},
            desiredAccuracy: "best",
            activityType: "other",
        });
    }

    function stop() {
        window.WTN.backgroundLocation.stop();
    }
</script>





<script>
    // var my_lat = '';
    // var my_long = '';

    var my_lat = 0;
    var my_long = 0;
    var start = false;
    var options = {
        enableHighAccuracy: true,
        timeout: 10000,
        maximumAge: 0
    };
    navigator.geolocation.watchPosition(successCallback, errorCallback, options);
    var currentMarker = null;
    var driverMarker;



    function successCallback(position) {
        const {
            accuracy,
            latitude,
            longitude,
            altitude,
            heading,
            speed
        } = position.coords;
        // Show a map centered at latitude / longitude.

        my_lat = latitude;
        my_long = longitude;

    }

    function setMyPosition() {
        if (currentMarker == null) {
            var myLatLng = new google.maps.LatLng(latitude, longitude);
            currentMarker = new google.maps.Marker({
                icon: {
                    url: "{{$data['passenger']['icon'],$user_icon}}",
                    // This marker is 20 pixels wide by 32 pixels high.
                    size: new google.maps.Size(40, 40),
                    // The origin for this image is (0, 0).
                    origin: new google.maps.Point(0, 0),
                    // The anchor for this image is the base of the flagpole at (0, 32).
                    anchor: new google.maps.Point(0, 32)
                },
                position: myLatLng,
                label: {
                    text: "{{$data['passenger']['name']}}",
                    className: 'marker-label'
                },
                map: map
            });

            // Add the button to the map's controls
            // map.controls[google.maps.ControlPosition.BOTTOM_LEFT].push(locationButton);

            currentMarker.setMap(map);
        } else {
            //currentMarker.setPosition(new google.maps.LatLng(my_lat, my_long));
        }
    }

    function errorCallback(error) {

    }
</script>
<script type="text/javascript">
    var k = 0;
    marker = '';
    lat = 0;
    lat_long = 0;
    speedshow = '';
    @if(isset($live_location['latitude']))
    lat = {{$live_location['latitude']}};
    lat_long = {{$live_location['longitude']}};
    speedshow = Math.round({{$live_location['speed']}} * 3.6);
    //document.getElementById("speed").innerHTML=speedshow;
    @endif
    //speed = loc_array.speed;

    mylocation_lat = '';
    mylocation_long = '';

    var driverLatLng = new google.maps.LatLng(lat, lat_long);
    myOptions = {
        zoom: 15,
        center: driverLatLng,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };

    var map = new google.maps.Map(document.getElementById('map-canvas'), myOptions);

    var directionsService = new google.maps.DirectionsService();
    var directionsRenderer = new google.maps.DirectionsRenderer();
    var originMarker;
    var destinationMarker;
    directionsRenderer.setMap(map);


    function initialize() {



        // Add the button to the map's controls
        // map.controls[google.maps.ControlPosition.BOTTOM_LEFT].push(locationButton);

        //driverMarker.setMap(map);

        //moveBus(map, marker);
        setDriverLocation();

        setInterval(function() {
            getData();
            //document.getElementById("speed").innerHTML=speedshow;
            if (start == true) {
                direction();
            } else {
                dvmarker = new google.maps.LatLng(lat, lat_long);
                driverMarker.setPosition(dvmarker);
                map.setCenter(dvmarker);
            }

        }, 10000);

    }

    function setDriverLocation() {
        driverMarker = new google.maps.Marker({
            icon: {
                url: 'https://app.svktrv.in/assets/img/sm-icon.png',
                // This marker is 20 pixels wide by 32 pixels high.
                size: new google.maps.Size(60, 68),
                // The origin for this image is (0, 0).
                origin: new google.maps.Point(0, 0),
                // The anchor for this image is the base of the flagpole at (0, 32).
                anchor: new google.maps.Point(0, 32)
            },
            position: driverLatLng,
            label: {
                text: "{{$data['driver']['name']}}",
                className: 'marker-label'
            },
            map: map
        });
    }



    function navigate() {
        start = true;
        try {
            driverMarker.setMap(null);
        } catch (o) {}
        try {
            currentMarker.setMap(null);
        } catch (o) {}



        originMarker = new google.maps.Marker({
            position: new google.maps.LatLng(lat, lat_long),
            map: map,
            icon: 'https://app.svktrv.in/assets/img/sm-icon.png', // Path to your custom marker icon
            label: {
                text: "{{$data['driver']['name']}}",
                className: 'marker-label'
            }
        });

        destinationMarker = new google.maps.Marker({
            position: new google.maps.LatLng(my_lat, my_long),
            map: map,
            icon: {
                url: 'https://app.svktrv.in/assets/img/map-male.png',
                // This marker is 20 pixels wide by 32 pixels high.
                size: new google.maps.Size(40, 40),
                // The origin for this image is (0, 0).
                origin: new google.maps.Point(0, 0),
                // The anchor for this image is the base of the flagpole at (0, 32).
                anchor: new google.maps.Point(0, 32)
            }, // Path to your custom marker icon
            label: {
                text: "{{$data['passenger']['name']}}",
                className: 'marker-label-user'
            }
        });
        direction();


    }

    function direction() {

        //lat=lat-0.00005;
        //map.setCenter(new google.maps.LatLng(lat, lat_long));
        originMarker.setPosition(new google.maps.LatLng(lat, lat_long));
        // map.panTo(new google.maps.LatLng(lat, lat_long));




        directionsService
            .route({
                origin: new google.maps.LatLng(lat, lat_long),
                destination: new google.maps.LatLng(my_lat, my_long),
                travelMode: 'DRIVING'
            })
            .then((response) => {
                const duration = response.routes[0].legs[0].duration.text;
                document.getElementById("arr").style.display = 'block';
                document.getElementById("duration").innerHTML = duration;


                // Customize the markers
                var markerOptions = {
                    origin: originMarker,
                    destination: destinationMarker,
                };
                directionsRenderer.setOptions({
                    markerOptions: markerOptions,
                    polylineOptions: {
                        strokeColor: '#FF0000' // Set your desired color
                    },
                    suppressMarkers: true

                });

                //directionsDisplay.setDirections(response);

                directionsRenderer.setDirections(response);
            })
            .catch((e) =>
                window.alert("Directions request failed due to " + status)
            );
    }



    function getData() {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                try {
                    array = JSON.parse(this.responseText);
                    lat = array.latitude;
                    lat_long = array.longitude;
                    speedshow = Math.round(array.speed * 3.6);
                } catch (o) {}

            }
        };
        xhttp.open("GET", "https://app.svktrv.in/ride/track/location/{{$ride_id}}", true);
        xhttp.send();
    }
</script>

@endsection