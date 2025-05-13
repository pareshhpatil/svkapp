@extends('layouts.web')
@section('header')

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

@endsection
@section('content')
<div id="app" class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-3">
                <h4 class="fw-bold py-2"><span class="text-muted fw-light">Ride /</span> Tracking</h4>
            </div>
            <div class="col-lg-3 pull-right">
                <input type="date" v-model="filters.date" autocomplete="off" required placeholder="Select Date" class="form-control" />
            </div>
            <div class="col-lg-3 pull-right">
                <select v-model="filters.project" class="form-select input-sm" data-allow-clear="true">
                    <option value="0">Projects..</option>
                    @if(!empty($project_list))
                    @foreach($project_list as $v)
                    <option @if(count($project_list)==1) selected @endif value="{{$v->project_id}}">{{$v->name}}</option>
                    @endforeach
                    @endif
                </select>
            </div>
            <div class="col-lg-3 pull-right">
                <select v-model="filters.status" class="form-select input-sm" data-allow-clear="true">
                    <option value="0">Status..</option>
                    <option value="1">Not Started</option>
                    <option value="2">Live</option>
                    <option value="5">Completed</option>
                </select>
            </div>

        </div>
        <div class="row g-4">
            <div v-for="ride in rides" :key="ride.id" class="col-xl-4 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <a href="javascript:;" class="d-flex align-items-center">
                                <div class="avatar me-2">
                                    <img :src="ride.photo!='' || defaultPhoto" alt="Driver Photo" class="rounded-circle">
                                </div>
                                <div class="me-2 ms-1">
                                    <h5 class="mb-0">
                                        <a href="javascript:;" class="stretched-link text-body" style="font-size: 14px;">@{{ride.driver_name}}</a>
                                    </h5>
                                    <div class="client-info">
                                        <span class="text-muted" style="font-size: 12px;">@{{ride.vehicle_number}}</span>
                                    </div>
                                </div>


                            </a>
                            <div class="ms-auto">
                                <ul class="list-inline mb-0 d-flex align-items-center">
                                    <li class="list-inline-item me-0">
                                        <a href="javascript:;"><span :class="getStatusClass(ride.status)">@{{ride.ride_status}}</span></a>
                                    </li>
                                    <li>
                                        <div class="dropdown zindex-2">
                                            <button type="button" class="btn dropdown-toggle hide-arrow p-0" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ti ti-dots-vertical text-muted"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end" style="">
                                                <li v-if="ride.ride_status=='Live'"><a class="dropdown-item" data-bs-toggle="modal"
                                                        data-bs-target="#TrackingModal" @click="setLiveTracking(ride)" href="javascript:void(0);">Live Track</a></li>
                                                <li><a class="dropdown-item" @click="fetchUpdates(ride.ride_id)" data-bs-toggle="modal"
                                                        data-bs-target="#updatesModal" href="javascript:void(0);">Updates</a></li>
                                            </ul>
                                        </div>
                                    </li>
                                </ul>


                            </div>
                        </div>
                        <div class="d-flex align-items-center py-2 mb-2">
                            <div class="d-flex align-items-center">
                                <div v-for="passenger in ride.passengers">
                                    <img :src="passenger.photo" style="max-width: 40px;" class="rounded-circle">
                                </div>
                            </div>
                            <small class="text-muted" style="margin-left: 10px;">@{{ride.passengers.length}} Passengers assigned</small>
              
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <h6 class="mb-1">@{{ride.ride_title}}</h6>
                            <span class="ms-auto"><small>#@{{ride.ride_id}}</small></span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2 pb-1">
                            <small>@{{ride.start_location}}</small>
                            <small>@{{ride.end_location}}</small>
                        </div>
                        <div class="border-top">
                            <div class="d-flex align-items-center pt-1">
                                <div class="d-flex align-items-center">
                                    <div class="avatar me-1 avatar-online">
                                        <img :src="ride.photo || defaultPhoto" class="rounded-circle">
                                    </div>
                                    <div v-for="passenger in ride.live_passengers" class="avatar me-1 avatar-online">
                                        <img :src="passenger.photo" style="max-width: 40px;" alt="@{{passenger.employee_name}}"
                                            class="rounded-circle">
                                    </div>
                                </div>
                                <small class="text-muted" style="margin-left: 10px;">@{{ride.live_passengers.length+1}} Members in cab</small>
                            </div>
                        </div>


                    </div>
                </div>
            </div>





        </div>
    </div>
    <div class="modal fade" id="TrackingModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-simple modal-pricing">
            <div class="modal-content mx-0  p-md-5 px-0">
                <div class="modal-body py-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h5 class="my-0">Speed: <span id="speed"></span> </h5>
                    <p>Last updated: <span id="timestamp"></span></p>
                    <!-- Pricing Plans -->
                    <div id="map"></div>
                    <!--/ Pricing Plans -->
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="updatesModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-simple modal-pricing">
            <div class="modal-content mx-0  p-md-5 px-0">
                <div class="modal-body py-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <ul class="timeline timeline-advance mb-0">
                        <li v-for="row in updates" :key="row.id" class="timeline-item ps-4 border-left-dashed">
                            <span :class="getUpdatesClass(row.type)">
                                <i class="ti ti-circle-check"></i>
                            </span>
                            <div class="timeline-event ps-0 pb-0">
                                <div class="timeline-header">
                                    <small class="text-success text-uppercase fw-semibold">@{{formatDate(row.last_update_date)}}</small>
                                </div>
                                <h6 class="mb-0">@{{row.title}}</h6>
                                <p class="text-muted mb-0 text-nowrap">@{{row.message}}</p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    let map, directionsService, directionsRenderer, cabMarker, intervalId;
    var office = {
        lat: 15.2993,
        lng: 74.1240
    };
    var ride_id = 0;
    var ride_type = '';



    var employees = [];

    const driverName = "John (Cab Driver)";
    const geocoder = new google.maps.Geocoder();

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

    function addEmployeeMarkers() {
        employees.forEach(emp => {
            new CustomMarker(emp.coords, map, emp.employee_name, emp.photo);
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

        fetch("https://vlpf3uqi3h.execute-api.ap-south-1.amazonaws.com/live/location/" + ride_id) // Replace with your actual Laravel API endpoint
            .then(response => response.json())
            .then(data => {
                const position = {
                    lat: parseFloat(data.latitude),
                    lng: parseFloat(data.longitude)
                };
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
</script>
@endsection

@section('footer')
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
                    date: '{{$date}}',
                    defaultPhoto: 'https://app.svktrv.in/assets/img/driver.png'
                },
                rides: [],
                loading: false,
                updates: []
            };
        },
        methods: {
            async fetchRides() {
                this.loading = true;
                const params = new URLSearchParams();

                if (this.filters.project) params.append('project', this.filters.project);
                if (this.filters.status) params.append('status', this.filters.status);
                if (this.filters.date) params.append('date', this.filters.date);

                try {
                    const res = await fetch(`/api/rides?${params}`);
                    const data = await res.json();
                    this.rides = data;
                } catch (err) {
                    console.error('Error fetching rides:', err);
                    this.rides = [];
                } finally {
                    this.loading = false;
                }

            },
            async fetchUpdates(ride_id) {
                this.loading = true;

                try {
                    const res = await fetch('/api/ride/updates/' + ride_id);
                    const data = await res.json();
                    this.updates = data;
                } catch (err) {
                    console.error('Error fetching rides:', err);
                    this.rides = [];
                } finally {
                    this.loading = false;
                }
            },
            getPassengerPhoto(passenger) {
                if (passenger.passenger_type == 2) {
                    return 'https://admin.ridetrack.in/assets/img/escort.png';
                }

                return passenger.gender === 'Female' ?
                    'https://app.svktrv.in/assets/img/map-female.png' :
                    'https://app.svktrv.in/assets/img/map-male.png';
            },
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
            setLiveTracking(ride) {
                ride_id = ride.ride_id;
                officeString = ride.project_cords;
                employees = JSON.parse(JSON.stringify(ride.passengers));
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

                $('#pricingModal').on('hidden.bs.modal', function() {
                    clearInterval(intervalId);
                });
                initMap();
            }

        },
        watch: {
            filters: {
                deep: true,
                handler() {
                    this.fetchRides();
                }
            }
        },
        mounted() {
            this.fetchRides(); // Initial call
            this.rideInterval = setInterval(() => {
                this.fetchRides();
            }, 30000);
        },
        beforeUnmount() {
            clearInterval(this.rideInterval);
        },
    }).mount('#app');

    
</script>



@endsection