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
</style>
<script src="https://maps.googleapis.com/maps/api/js?key={{env('MAP_KEY')}}"></script>

<div id="appCapsule" class="full-height">

    <div id="app" class=" ">

        <div id="map-canvas"></div>










    </div>

</div>
<div class="modal fade action-sheet show" id="actionSheet" tabindex="-1" role="dialog" aria-modal="true" style="display: block;">
    <div class="modal-dialog" role="document" style="bottom: 50px;">
        <div class="modal-content">

            <div class="modal-body">
                <div class="transactions">
                    <!-- item -->
                    <a href="app-transaction-detail.html" class="item">
                        <div class="detail">
                            <img src="/assets/img/driver.png?v-1" alt="img" class="image-block imaged w48">
                            <div>
                                <strong>Nitin Kamble</strong>
                                <strong class="text-primary">Arriving in 10 min</strong>

                                <p>Shopping</p>
                            </div>
                        </div>
                        <div class="right">
                            <div class="text-danger"> <ion-icon name="call-outline" style="font-size: 25px;"></ion-icon></div>
                        </div>
                    </a>
                </div>
                <div class=" ">
                    <div class="wallet-card" style="box-shadow: none;padding: 0;padding-bottom: 10px;">
                        <!-- Balance -->
                        <!-- Wallet Footer -->
                        <div class="wallet-footer" style="padding-top: 10px;">
                            <div v-if="data.ride.status==1" class="item mb-1">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#withdrawActionSheet">
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

@endsection

@section('footer')

<script src="https://unpkg.com/webtonative@1.0.43/webtonative.min.js"></script>




<script>
    function start() {
        window.WTN.backgroundLocation.start({
            callback: getMylocation,
            apiUrl: "https://app.svktrv.in/app/ping",
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
    var map = '';
    marker = '';
    lat = 18.6020798;
    mylocation_lat = '';
    mylocation_long = '';


    function initialize() {

        const locationButton = document.createElement("button");
        locationButton.textContent = "";
        locationButton.id = 'trackme';
        locationButton.innerHTML = '<img style="max-width:50px;" src="https://app.svktrv.in/assets/img/locate.png">';
        locationButton.classList.add("custom-map-control-button");

        var myLatLng = new google.maps.LatLng(lat, 73.7908489),
            myOptions = {
                zoom: 18,
                center: myLatLng,
                mapTypeId: google.maps.MapTypeId.ROADMAP
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
                label: {
                    text: "Nitin Kamble",
                    className: 'marker-label'
                },
                map: map
            });


        // Add the button to the map's controls
        map.controls[google.maps.ControlPosition.BOTTOM_LEFT].push(locationButton);

        // Handle button click event
        locationButton.addEventListener("click", () => {

            marker = new google.maps.Marker({
                position: new google.maps.LatLng(mylocation_lat, mylocation_long),
                map: map
            });
            const pos = {
                lat: mylocation_lat,
                lng: mylocation_long
            };
            map.setCenter(pos);
        });


        marker.setMap(map);
        moveBus(map, marker);

    }

    function getMylocation(posistion) {
        try {
            mylocation_lat = posistion.latitude;
            mylocation_long = posistion.longitude;
            var myLatLng = new google.maps.LatLng(mylocation_lat, mylocation_long),
                myOptions = {
                    zoom: 18,
                    center: myLatLng,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                },

                map = new google.maps.Map(document.getElementById('map-canvas'), myOptions),
                marker = new google.maps.Marker({
                    position: new google.maps.LatLng(mylocation_lat, mylocation_long),
                    icon: 'https://app.svktrv.in/assets/img/sm-icon.png',
                    map: map
                });
            const pos = {
                lat: mylocation_lat,
                lng: mylocation_long
            };
            map.setCenter(pos);
        } catch (o) {
            alert(o.message);
        }
    }



    function moveBus(map, marker) {

        setInterval(function() {
            if (k < 5) {
                lat = lat + 0.00005;
                // marker.setPosition(new google.maps.LatLng(lat, 73.7908489));
                //   map.panTo(new google.maps.LatLng(lat, 73.7908489));
                k = k + 1;
                console.log(k);
            }
        }, 1000);


    };
</script>

@endsection