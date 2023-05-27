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
        height: 600px;
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

    <div id="map">
        <iframe width="700" height="300" id="maplink" src=""></iframe>
    </div>

</div>




@endsection

@section('footer')

<script src="https://unpkg.com/webtonative@1.0.43/webtonative.min.js"></script>










<script>
    // var my_lat = '';
    // var my_long = '';

    var my_lat = 18.6020798;
    var my_long = 73.7908489;
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



    function errorCallback(error) {

    }
</script>
<script type="text/javascript">
    var k = 0;
    marker = '';
    lat = 0;
    lat_long = 0;
    @if(isset($live_location['latitude']))
    lat = {
        {
            $live_location['latitude']
        }
    };
    lat_long = {
        {
            $live_location['longitude']
        }
    };
    @endif
    //speed = loc_array.speed;

    mylocation_lat = '';
    mylocation_long = '';

    var myLatLng = new google.maps.LatLng(lat, lat_long);
    myOptions = {
        zoom: 15,
        center: myLatLng,
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
        //setDriverLocation();

        setInterval(function() {
            getData();
            if (start == true) {
                document.getElementById('maplink').src = 'https://www.google.com/maps/dir/' + lat + ',' + lat_long + '/' + my_lat + ',' + my_long;
            } else {
                driverMarker.setPosition(new google.maps.LatLng(lat, lat_long));
            }

        }, 30000);

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
            position: myLatLng,
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
                } catch (o) {}

            }
        };
        xhttp.open("GET", "https://app.svktrv.in/ride/track/location/{{$ride_id}}", true);
        xhttp.send();
    }
</script>

@endsection