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
</style>
<script src="https://maps.googleapis.com/maps/api/js?key={{env('MAP_KEY')}}"></script>

<div id="appCapsule" class="full-height">

    <div id="app" class=" ">

        <div id="map-canvas"></div>



        <div class=" ">
            <div class="wallet-card" style="box-shadow: none;padding: 0;padding-bottom: 10px;">
                <!-- Balance -->
                <!-- Wallet Footer -->
                <div class="wallet-footer">
                    <div v-if="data.ride.status==1" class="item mb-1">
                        <a href="#" data-bs-toggle="modal" data-bs-target="#withdrawActionSheet">
                            <div class="icon-wrapper bg-success">
                                <ion-icon name="location-outline"></ion-icon>
                            </div>
                            <strong>Track</strong>
                        </a>
                    </div>
                    <div class="item">
                        <a href="#" data-bs-toggle="modal" data-bs-target="#cancelride">
                            <div class="icon-wrapper bg-primary bg-red" style="background: #e8481e !important;">
                                <ion-icon name="close-circle-outline"></ion-icon>
                            </div>
                            <strong>Cancel</strong>
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
                        <a href="#" data-bs-toggle="modal" data-bs-target="#sosmodel">
                            <div class="icon-wrapper bg-primary bg-red" style="background: #e8481e !important;">
                                <ion-icon name="accessibility-outline"></ion-icon>
                            </div>
                            <strong>SOS</strong>
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
@endsection

@section('footer')
<script>
    new Vue({
        el: '#app',
        data() {
            return {
                data: [],
            }
        },
        mounted() {
            this.data = JSON.parse('{!!json_encode($data)!!}');
        },
        methods: {

        }
    });
</script>
<script type="text/javascript">
    var k = 0;
    map = '';
    marker = '';
    lat = 18.6020798;

    function initialize() {

        const locationButton = document.createElement("button");
        locationButton.textContent = "Current Location";
        locationButton.classList.add("custom-map-control-button");

        var myLatLng = new google.maps.LatLng(lat, 73.7908489),
            myOptions = {
                zoom: 18,
                center: myLatLng,
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                icon: 'https://app.svktrv.in/assets/img/sm-icon.png'

            },



            map = new google.maps.Map(document.getElementById('map-canvas'), myOptions),

            marker = new google.maps.Marker({
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
                map: map
            });


        // Add the button to the map's controls
        map.controls[google.maps.ControlPosition.TOP_RIGHT].push(locationButton);

        // Handle button click event
        locationButton.addEventListener("click", () => {
            // Try to get the user's current location
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        // Get the user's coordinates
                        const {
                            latitude,
                            longitude
                        } = position.coords;
                        const pos = {
                            lat: latitude,
                            lng: longitude
                        };

                        // Center the map on the user's location
                        marker = new google.maps.Marker({
                            position: pos,
                            map: map
                        });
                        map.setCenter(pos);
                    },
                    (error) => {
                        // Handle geolocation error
                        alert("Error getting current location:", error);
                    }
                );
            } else {
                // Geolocation is not supported by the browser
                alert("Geolocation is not supported by this browser.");
            }
        });


        marker.setMap(map);
        moveBus(map, marker);

    }



    function moveBus(map, marker) {

        setInterval(function() {
            if (k < 50) {
                lat = lat + 0.00005;
                marker.setPosition(new google.maps.LatLng(lat, 73.7908489));
                map.panTo(new google.maps.LatLng(lat, 73.7908489));
                k = k + 1;
                console.log(k);
            }
        }, 1000);


    };
</script>

@endsection