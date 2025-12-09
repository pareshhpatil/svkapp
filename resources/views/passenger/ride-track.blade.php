@extends('layouts.app')
@section('content')
<script src="https://maps.googleapis.com/maps/api/js?key={{env('MAP_KEY')}}&libraries=places"></script>

<script>
    class CustomMarker extends google.maps.OverlayView {
        constructor(position, map, employee_name, photo) {
            super();
            this.position = position;
            this.map = map;
            this.name = employee_name;
            this.iconUrl = photo;
            this.div = null;
            this.setMap(map);
        }

        onAdd() {
            const div = document.createElement('div');
            div.style.position = 'absolute';
            div.style.textAlign = 'center';

            const img = document.createElement('img');
            img.src = this.iconUrl;
            img.style.width = '32px';
            img.style.height = '32px';
            img.style.display = 'block';
            img.style.margin = '0 auto';

            const label = document.createElement('div');
            label.textContent = this.name;
            label.style.background = '#fff';
            label.style.border = '1px solid #ccc';
            label.style.borderRadius = '4px';
            label.style.padding = '2px 5px';
            label.style.fontSize = '12px';
            label.style.marginTop = '4px';

            div.appendChild(img);
            div.appendChild(label);

            this.div = div;

            const panes = this.getPanes();
            panes.overlayImage.appendChild(div);
        }

        draw() {
            const projection = this.getProjection();
            const pos = projection.fromLatLngToDivPixel(this.position);

            if (this.div) {
                this.div.style.left = pos.x - 16 + 'px';
                this.div.style.top = pos.y - 32 + 'px';
            }
        }

        onRemove() {
            if (this.div) {
                this.div.parentNode.removeChild(this.div);
                this.div = null;
            }
        }

        setPosition(position) {
            this.position = position;
            this.draw();
        }
    }
</script>
<script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>
<script src="https://cdn.jsdelivr.net/npm/dayjs@1/dayjs.min.js"></script>



<style>
    #map {
        height: 700px;
        width: 100%;
        margin: 0;
    }
</style>
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
        <div id="map"></div>
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
                                <strong class="text-info">Speed : <span id="speed"></span> </strong>
                                <p>Location updated: <span id="timestamp">NA</span></p>
                                <p>{{$data['vehicle']['number']}}</p>
                            </div>
                        </div>
                        <div class="right">
                            <div v-on:click="call('{{$data['driver']['mobile']}}')" class="text-danger"> <ion-icon name="call-outline" style="font-size: 25px;"></ion-icon></div>
                        </div>
                    </a>
                </div>
                <div class=" ">
                    <div class="wallet-card" style="box-shadow: none;padding: 0;padding-bottom: 10px;">
                        <!-- Balance -->
                        <!-- Wallet Footer -->
                        <div class="wallet-footer" style="padding-top: 10px;">
                            <div v-if="data.ride.status==1" class="item mb-1">
                                <a href="#" onclick="navigate(false);">
                                    <div class="icon-wrapper bg-success">
                                        <ion-icon name="navigate-outline"></ion-icon>
                                    </div>
                                    <strong>Navigate</strong>
                                </a>
                            </div>

                            <div class="item">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#helpmodal">
                                    <div class="icon-wrapper bg-success">
                                        <ion-icon name="chatbubble-ellipses-outline"></ion-icon>
                                    </div>
                                    <strong>Chat</strong>
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
@include('passenger.ride-options')
@section('footer')

<script src="https://unpkg.com/webtonative@1.0.63/webtonative.min.js"></script>

<script>
    let map, directionsService, directionsRenderer, cabMarker, intervalId;
    var office = {
        lat: 15.2993,
        lng: 74.1240
    };
    var ride_id = 0;
    var ride_type = '';
    let my_lat = 0;
    let my_long = 0;
    let driver_lat = 0;
    let driver_long = 0;


    var employees = [];

    const driverName = "{{$data['driver']['name']}}";
    const geocoder = new google.maps.Geocoder();


    function initializeNavigate() {
        const driverLatLng = { lat: driver_lat, lng: driver_long };
        const myOptions = {
            zoom: 15,
            center: driverLatLng,
            mapId: '46bf20bc83a0ec31',
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };

        map = new google.maps.Map(document.getElementById('map'), myOptions);

        directionsService = new google.maps.DirectionsService();
        directionsRenderer = new google.maps.DirectionsRenderer({ suppressMarkers: true });
        directionsRenderer.setMap(map);

        setDriverLocation();

      //  setInterval(updateLocation, 10000);
    }

    function setDriverLocation() {
        driverMarker =   new CustomMarker(
            new google.maps.LatLng(driver_lat, driver_long),
            map,
            "{{$data['driver']['name']}}",
            "https://app.svktrv.in/favicon.ico"
        );
    }

    async function updateLocation() {

    if (old_lat === driver_lat && old_lat_long === driver_long) {
        // No location change
       // return;
       
    }

    old_lat = driver_lat;
    old_lat_long = driver_long;

    document.getElementById("speed").innerText = speedshow;

    const dvMarkerPosition = new google.maps.LatLng(lat, lat_long);

    if (driverMarker) {
        driverMarker.position = dvMarkerPosition;
    }

        direction(); // Recalculate route
}


    function navigate(app_location) {
        initializeNavigate();
        
        try {
            driverMarker.map = null;
        } catch (o) {}
        try {
            currentMarker.map = null;
        } catch (o) {}

        cabMarker = new CustomMarker(
            new google.maps.LatLng(driver_lat, driver_long),
            map,
            "{{$data['driver']['name']}}",
            "https://app.svktrv.in/favicon.ico"
        );


        destinationMarker = new CustomMarker(
            new google.maps.LatLng(my_lat, my_long),
            map,
            "{{$data['passenger']['name']}}",
            "https://app.svktrv.in/assets/img/map-male.png",
        );




        direction();
    }

    function drawRouteDriver() {
        var aorigin = {
            lat: driver_lat,
            lng: driver_long
        };
        var adestination = {
            lat: my_lat,
            lng: my_long
        };
        start = '';
        end = '';
        
            start = aorigin;
            end = adestination;
        const request = {
            origin: aorigin,
            destination: adestination,
            travelMode: google.maps.TravelMode.DRIVING
        };

        directionsService.route(request, (result, status) => {
            if (status === google.maps.DirectionsStatus.OK) {
                directionsRenderer.setDirections(result);
            } else {
                alert("Directions request failed due to " + status);
            }
        });
    }

    function direction() {
        cabMarker.position = {
            lat: driver_lat,
            lng: driver_long
        };
        directionsService.route({
            origin: {
                lat: driver_lat,
                lng: driver_long
            },
            destination: {
                lat: my_lat,
                lng: my_long
            },
            travelMode: 'DRIVING'
        }).then((response) => {
            const duration = response.routes[0].legs[0].duration.text;
            document.getElementById("arr").style.display = 'block';
            document.getElementById("duration").innerText = duration;

            directionsRenderer.setDirections(response);
        }).catch((e) => {
            alert('Directions request failed:', e);
        });
    }



    function initMap() {
        map = new google.maps.Map(document.getElementById("map"), {
            center: office,
            zoom: 13
        });

        directionsService = new google.maps.DirectionsService();
        directionsRenderer = new google.maps.DirectionsRenderer({
            map,
            suppressMarkers: true
        });

        addOfficeMarker();
        geocodeEmployees();
        updateCabLocation();
        intervalId = setInterval(updateCabLocation, 10000);
    }

    function addOfficeMarker() {
        if (typeof CustomMarker !== "undefined") {
            new CustomMarker(
                new google.maps.LatLng(office.lat, office.lng),
                map,
                "Office",
                "https://app.svktrv.in/assets/img/office.png"
            );
        } else {
            console.error("CustomMarker is not defined yet");
        }

    }

    function geocodeEmployees() {
        const promises = employees.map(emp => {
            return new Promise((resolve, reject) => {
                geocoder.geocode({
                    address: emp.address
                }, (results, status) => {
                    if (status === "OK" && results[0]) {
                        emp.coords = results[0].geometry.location;
                        resolve(emp);
                    } else {
                        console.error("Geocode failed for", emp.employee_name, status);
                        reject(status);
                    }
                });
            });
        });

        Promise.all(promises).then(() => {
            addEmployeeMarkers();
            drawRoute();
        }).catch(console.error);
    }

    function getPassengerPhoto(passenger) {
        if (passenger.passenger_type == 2) {
            return 'https://admin.ridetrack.in/assets/img/escort.png';
        }

        return passenger.gender === 'Female' ?
            'https://app.svktrv.in/assets/img/map-female.png' :
            'https://app.svktrv.in/assets/img/map-male.png';
    }

    function addEmployeeMarkers() {
        employees.forEach(emp => {
            new CustomMarker(emp.coords, map, emp.name, getPassengerPhoto(emp));
        });
    }


    function drawRoute() {
        const waypoints = employees.map(emp => ({
            location: emp.coords,
            stopover: true
        }));
        start = '';
        end = '';
        if (ride_type == 'Pickup') {
            end = office;
            start = employees[0].coords;

        } else {
            start = office;
            end = employees[employees.length - 1].coords;
        }
        const request = {
            origin: office,
            destination: employees[employees.length - 1].coords,
            waypoints,
            travelMode: google.maps.TravelMode.DRIVING
        };

        directionsService.route(request, (result, status) => {
            if (status === google.maps.DirectionsStatus.OK) {
                directionsRenderer.setDirections(result);
            } else {
                console.error("Directions request failed due to " + status);
            }
        });
    }


    function updateCabLocation() {

        fetch("https://vlpf3uqi3h.execute-api.ap-south-1.amazonaws.com/live/location/{{$ride_id}}") // Replace with your actual Laravel API endpoint
            .then(response => response.json())
            .then(data => {
                const position = {
                    lat: parseFloat(data.latitude),
                    lng: parseFloat(data.longitude)
                };
                driver_lat = parseFloat(data.latitude);
                driver_long = parseFloat(data.longitude);
                speedshow = Math.round(data.speed * 3.6);
                timeAgo(data.timestamp);
                document.getElementById("speed").innerText = speedshow;
                if (!cabMarker) {
                    cabMarker = new google.maps.Marker({
                        position,
                        map,
                        icon: {
                            url: "https://app.svktrv.in/favicon.ico", // Cab icon
                            scaledSize: new google.maps.Size(32, 32)
                        },
                        title: driverName
                    });

                    const info = new google.maps.InfoWindow({
                        content: `Driver: ${driverName}`
                    });

                    cabMarker.addListener("click", () => info.open(map, cabMarker));
                } else {
                    cabMarker.setPosition(position);
                }
            })
            .catch(error => {
                console.error("Failed to fetch cab location:", error);
            });
    }

    function timeAgo(timestamp) {
        const now = Date.now();
        const secondsPast = Math.floor((now - timestamp) / 1000);
        var text = '';
        if (secondsPast < 60) {
            text = `${secondsPast} seconds ago`;
        }
        if (secondsPast < 3600 && text == '') {
            const minutes = Math.floor(secondsPast / 60);
            text = `${minutes} minute${minutes !== 1 ? 's' : ''} ago`;
        }
        if (secondsPast < 86400 && text == '') {
            const hours = Math.floor(secondsPast / 3600);
            text = `${hours} hour${hours !== 1 ? 's' : ''} ago`;
        }
        if (secondsPast < 2592000 && text == '') {
            const days = Math.floor(secondsPast / 86400);
            text = `${days} day${days !== 1 ? 's' : ''} ago`;
        }
        // More than 30 days ago
        if (text == '') {
            const date = new Date(timestamp);
            text = date.toLocaleDateString();
        }
        document.getElementById('timestamp').innerHTML = text;
    }

    function popover() {

        const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
        const popoverList = popoverTriggerList.map(function(popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl);
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' || e.key === 'Esc') {
                popoverList.forEach(function(popover) {
                    popover.hide();
                });
            }
        });
    }
</script>


<script>
    function successCallbackMap(position) {
        const {
            latitude,
            longitude,
            altitude,
            speed
        } = position;

        my_lat = position.latitude;
        my_long = position.longitude;
        if (my_lat > 0) {
            // alert(my_lat);
            stop();
        }
        // app_location=true;
        // start=false;
        // navigate(true);
    }

    function startlocation() {
        window.WTN.backgroundLocation.start({
            callback: successCallbackMap,
            apiUrl: "",
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

    function initialize() {

    }
</script>







<!-- Now load Google Maps API async and deferred correctly -->



<script>
    const {
        createApp,
        watch
    } = Vue;

    createApp({
        data() {
            return {
                filters: {
                    project: '0',
                    status: '0',
                    defaultPhoto: 'https://app.svktrv.in/assets/img/driver.png'
                },
                rides: [],
                loading: false,
                ride: JSON.parse('{!!json_encode($data)!!}'),
                selected_ride: [],
                updates: []
            };
        },
        methods: {


            getStatusClass(status) {
                switch (status) {
                    case 1:
                        return 'badge bg-label-danger';
                    case 2:
                        return 'badge bg-label-success';
                    case 5:
                        return 'badge bg-label-primary';
                    default:
                        return 'badge bg-label-primary';
                }
            },
            getUpdatesClass(status) {
                switch (status) {
                    case 1:
                        return 'timeline-indicator timeline-indicator-success';
                    case 2:
                        return 'timeline-indicator timeline-indicator-info';
                    case 3:
                        return 'timeline-indicator timeline-indicator-warning';
                    default:
                        return 'timeline-indicator timeline-indicator-success';
                }
            },
            formatDate(date) {
                return dayjs(date).format('DD MMM YYYY, hh:mm A');
            },
            setLiveTracking() {
                ride = this.ride;
                ride_id = ride.ride_id;
                officeString = ride.project.lat_long;
                employees = ride.ride_passengers;
                ride_type = ride.type;
                const [lat, lng] = officeString.split(',').map(Number);
                office = {
                    lat,
                    lng
                };

                if (cabMarker) {
                    cabMarker.setMap(null);
                    cabMarker = null;
                }


                initMap();
            },
            call(mobile) {
                axios.get('/call/' + mobile);
                toastbox('toast-15');
            }

        },

        mounted() {
            this.setLiveTracking();
            startlocation();
        },
        beforeUnmount() {
            clearInterval(this.rideInterval);
        },
    }).mount('#app');
</script>

@endsection