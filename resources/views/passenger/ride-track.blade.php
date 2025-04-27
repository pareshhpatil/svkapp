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
                                <strong class="text-info">Speed : <span id="speed"></span> </strong> <p>Location updated: <span id="timestamp">NA</span></p>
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

<script src="https://unpkg.com/webtonative@1.0.63/webtonative.min.js"></script>




<script>
    function startlocation() {
        window.WTN.backgroundLocation.start({
            callback: successCallbackMap,
            apiUrl: "https://app.svktrv.in/ride/track/1",
            timeout: 10,
            data: "userid1",
            backgroundIndicator: true,
            pauseAutomatically: true,
            distanceFilter: 0.0,
            desiredAccuracy: "best",
            activityType: "other",
        });
    }

    function stop() {
        window.WTN.backgroundLocation.stop();
    }
</script>





<script>
    let my_lat = 0;
    let my_long = 0;
    let start = false;
    let currentMarker = null;
    let driverMarker;
    let originMarker;
    let destinationMarker;
    let app_location =false;

    const options = {
        enableHighAccuracy: true,
        timeout: 10000,
        maximumAge: 0
    };

    navigator.geolocation.watchPosition(successCallback, errorCallback, options);

    function successCallbackMap(position) {
        const {
            latitude,
            longitude,
            altitude,
            speed
        } = position;

        my_lat = position.latitude;
        my_long = position.longitude;
        app_location=true;
        start=false;
        navigate();
        stop();
    }

    function successCallback(position) {
        const { latitude, longitude } = position.coords;
        my_lat = latitude;
        my_long = longitude;

        
    }

    function errorCallback(error) {
        //alert('Error getting location:', error);
    }

    function createCustomMarkerContent(name, iconUrl, labelClass = 'marker-label') {
        const div = document.createElement('div');
        div.style.position = 'relative';
        
        const img = document.createElement('img');
        img.src = iconUrl;
        img.style.width = '40px';
        img.style.height = '40px';
        
        const label = document.createElement('div');
        label.innerText = name;
        label.className = labelClass;
        
        div.appendChild(img);
        div.appendChild(label);
        
        return div;
    }

    function setMyPosition() {
        if (currentMarker == null) {
            const myLatLng = { lat: my_lat, lng: my_long };
            currentMarker = new google.maps.marker.AdvancedMarkerElement({
                map: map,
                position: myLatLng,
                content: createCustomMarkerContent(
                    "{{$data['passenger']['name']}}",
                    "{{$data['passenger']['icon'],$user_icon}}",
                    'marker-label'
                )
            });
        }
    }

    let k = 0;
    let lat = 0;
    let lat_long = 0;
    let old_lat = 0;
    let old_lat_long = 0;
    let speedshow = '';

    @if(isset($live_location['latitude']))
    lat = {{$live_location['latitude']}};
    lat_long = {{$live_location['longitude']}};
    speedshow = Math.round({{$live_location['speed']}} * 3.6);
    document.getElementById("speed").innerText = speedshow;
    timeAgo({{$live_location['timestamp']}});
    @endif

    let map;
    let directionsService;
    let directionsRenderer;

    function initialize() {
        const driverLatLng = { lat: lat, lng: lat_long };
        const myOptions = {
            zoom: 15,
            center: driverLatLng,
            mapId: '46bf20bc83a0ec31',
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };

        map = new google.maps.Map(document.getElementById('map-canvas'), myOptions);

        directionsService = new google.maps.DirectionsService();
        directionsRenderer = new google.maps.DirectionsRenderer({ suppressMarkers: true });
        directionsRenderer.setMap(map);

        setDriverLocation();

        setInterval(updateLocation, 10000);
    }

    async function updateLocation() {
    await getData(); // Wait for fresh location

    if (old_lat === lat && old_lat_long === lat_long) {
        // No location change
        return;
    }

    old_lat = lat;
    old_lat_long = lat_long;

    document.getElementById("speed").innerText = speedshow;

    const dvMarkerPosition = new google.maps.LatLng(lat, lat_long);

    if (driverMarker) {
        driverMarker.position = dvMarkerPosition;
    }

    if (start) {
        direction(); // Recalculate route
    }
}


    function setDriverLocation() {
        driverMarker = new google.maps.marker.AdvancedMarkerElement({
            map: map,
            position: { lat: lat, lng: lat_long },
            content: createCustomMarkerContent(
                "{{$data['driver']['name']}}",
                "https://app.svktrv.in/assets/img/sm-icon.png",
                'marker-label'
            )
        });
    }

    function navigate() {
        if(app_location==false)
        {
            startlocation();
        }
       
        if (!start) {
            start = true;
            try { driverMarker.map = null; } catch (o) {}
            try { currentMarker.map = null; } catch (o) {}

            originMarker = new google.maps.marker.AdvancedMarkerElement({
                map: map,
                position: { lat: lat, lng: lat_long },
                content: createCustomMarkerContent(
                    "{{$data['driver']['name']}}",
                    "https://app.svktrv.in/assets/img/sm-icon.png",
                    'marker-label'
                )
            });

            destinationMarker = new google.maps.marker.AdvancedMarkerElement({
                map: map,
                position: { lat: my_lat, lng: my_long },
                content: createCustomMarkerContent(
                    "{{$data['passenger']['name']}}",
                    "https://app.svktrv.in/assets/img/map-male.png",
                    'marker-label-user'
                )
            });

            direction();
        }
    }

    function direction() {
        originMarker.position = { lat: lat, lng: lat_long };
        directionsService.route({
            origin: { lat: lat, lng: lat_long },
            destination: { lat: my_lat, lng: my_long },
            travelMode: 'DRIVING'
        }).then((response) => {
            const duration = response.routes[0].legs[0].duration.text;
            document.getElementById("arr").style.display = 'block';
            document.getElementById("duration").innerText = duration;

            directionsRenderer.setDirections(response);
        }).catch((e) => {
            console.error('Directions request failed:', e);
        });
    }
    function getData() {
    return new Promise((resolve, reject) => {
        const xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState === 4) {
                if (this.status === 200) {
                    try {
                        const array = JSON.parse(this.responseText);
                        lat = array.latitude;
                        lat_long = array.longitude;
                        speedshow = Math.round(array.speed * 3.6);
                        timeAgo(array.timestamp);
                        //alert(timeAgo(array.timestamp));
                        resolve();
                    } catch (e) {
                        console.error('Parsing error:', e);
                        reject(e);
                    }
                } else {
                    reject(new Error(`HTTP error: ${this.status}`));
                }
            }
        };
        xhttp.open("GET", "https://vlpf3uqi3h.execute-api.ap-south-1.amazonaws.com/live/location/{{$ride_id}}", true);
        xhttp.send();
    });
}

function timeAgo(timestamp) {
    const now = Date.now();
    const secondsPast = Math.floor((now - timestamp) / 1000);

    if (secondsPast < 60) {
        return `${secondsPast} seconds ago`;
    }
    if (secondsPast < 3600) {
        const minutes = Math.floor(secondsPast / 60);
        return `${minutes} minute${minutes !== 1 ? 's' : ''} ago`;
    }
    if (secondsPast < 86400) {
        const hours = Math.floor(secondsPast / 3600);
        return `${hours} hour${hours !== 1 ? 's' : ''} ago`;
    }
    if (secondsPast < 2592000) {
        const days = Math.floor(secondsPast / 86400);
        return `${days} day${days !== 1 ? 's' : ''} ago`;
    }
    // More than 30 days ago
    const date = new Date(timestamp);
    document.getElementById('timestamp').innerHTML= date.toLocaleDateString();
}
</script>

<!-- Now load Google Maps API async and deferred correctly -->

<script src="https://maps.googleapis.com/maps/api/js?key={{env('MAP_KEY')}}&callback=initialize&libraries=marker&loading=async" 
async 
defer>
</script>

@endsection
