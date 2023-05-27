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
   
    //speed = loc_array.speed;

    


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
                document.getElementById('maplink').src = 'https://www.google.com/maps/dir/' + lat + ',' + lat_long + '/' + my_lat + ',' + my_long;
            }

        }, 3000);

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