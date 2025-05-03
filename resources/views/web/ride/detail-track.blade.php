@extends('layouts.web')
@section('header')
<link rel="stylesheet" href="/assets/vendor/libs/select2/select2.css" />
<link rel="stylesheet" href="/assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css" />
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #map {
        height: 100vh;
        width: 100%;
    }

    .control-panel {
        position: fixed;
        bottom: 10px;
        left: 35%;
        width: 70%;
        transform: translateX(-20%);
        background: white;
        padding: 10px 20px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        z-index: 1000;
        font-family: Arial, sans-serif;
        text-align: center;
    }

    #layout-navbar {
        display: none;
    }

    .content-wrapper {
        margin-top: -80px;
    }
</style>
<script src="https://maps.googleapis.com/maps/api/js?key={{env('MAP_KEY')}}"></script>

@endsection
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card invoice-preview-card">
            <div class="card-body">

                <!-- Earning Reports -->

                <!--/ Earning Reports -->

                <!-- Support Tracker -->


                <div id="map"></div>

                <div class="control-panel">
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-2"><select id="speed-select" class="form-control" onchange="updateSpeed()" style="min-width:80px;">
                                        <option value="500">0.5 sec</option>
                                        <option value="1000" selected>1 sec</option>
                                        <option value="2000">2 sec</option>
                                        <option value="3000">3 sec</option>
                                    </select> </div>
                                <div class="col-md-10"><button class="btn btn-primary" onclick="startAnimation()">▶ Start</button>
                                    <button class="btn btn-default" onclick="pauseAnimation()">⏸ Pause</button>
                                    <button class="btn btn-default" onclick="resetAnimation()">⏮ Reset</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-4">
                                    <div style="margin-bottom: 8px; font-weight: bold;">Total KM: 16.87</div>
                                </div>
                                <div class="col-md-4">
                                    <div id="speed-display" style="margin-bottom: 8px; font-weight: bold;">Speed: 16.87</div>
                                </div>
                                <div class="col-md-4">
                                    <div id="eta" style="margin-bottom: 8px; font-weight: bold;">Time: 16.87</div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div id="progress-bar-container" style="background: #eee; border-radius: 5px; height: 10px; width: 100%; margin-bottom: 8px;">
                        <div id="progress-bar" style="background: #4caf50; height: 100%; width: 0%; border-radius: 5px;"></div>
                    </div>

                </div>

                <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
                <script>
                    var latlngs = [
                        @foreach($ride as $k=>$v)
                        [{{$v['latitude']}}, {{$v['longitude']}}],
                        @endforeach
                    ];

                    //var speeds = [0.0, 0.29111502, 1.6551784999999999, 3.0393953, 2.4126149999999997, 2.510195, 2.1766443, 1.7115233, 2.0199623, 1.1119008, 1.9977009, 4.058317, 3.271556, 2.9884412, 6.8384023, 8.30834, 9.207298, 9.875681, 10.288685, 8.81488, 8.620662, 9.595407999999999, 10.04881, 10.119814, 9.960448, 10.379048, 9.493032, 8.113638, 6.026553, 5.690608, 5.3308516, 5.9840355, 7.643785, 7.600367, 4.737984, 3.7333283, 6.453311, 6.9280524, 8.19278, 8.548691999999999, 8.510045, 8.789202, 8.680458999999999, 9.168374, 7.1365169999999996, 4.5574703, 3.6235716, 3.6144745, 5.471633, 6.796028, 8.105363, 8.359158, 7.702072, 7.8892117, 5.8067994, 8.0125, 7.950361, 7.810579, 7.64029, 7.3314047, 5.7090654, 3.9962103, 3.9705209999999997, 4.3688807, 5.5878515, 6.058408, 5.9009705, 5.072357, 5.7478952, 5.949359, 5.7012835, 5.351844, 4.8216023, 4.889786, 5.369271, 5.111208, 3.595445, 3.609668, 3.779176, 5.2350636, 5.7085094, 6.4279423, 6.3203949999999995, 5.8117537, 7.22494, 7.608812, 6.6831074, 3.465183, 3.1391807, 5.621894, 7.4231987, 8.466485, 9.117866, 9.408981, 9.459833, 9.639371, 10.197969, 8.555674, 8.570075, 7.7824883, 5.015758, 4.6697745, 7.0320354, 7.80727, 7.670524, 7.5504866, 4.12774, 4.1728816, 5.2771235, 6.5958323, 7.227359, 5.8880973, 8.442018, 10.752269, 11.148693, 11.080115, 11.488549, 10.902972, 10.362176, 13.042289, 12.681772, 11.673451, 13.753373, 13.460878, 11.512270000000001, 12.207428, 12.240002, 9.605467, 7.545792, 5.6156077, 8.042998, 9.803404, 10.426489, 7.07417, 3.7549374, 4.872764, 2.9714787, 5.322082, 7.9539185, 8.766365, 7.1549606, 4.8049283, 5.875125, 8.430612, 10.159709, 11.656531, 12.237494, 13.992322, 14.009807, 13.161965, 11.753484, 8.632044, 9.697442, 11.11725, 12.267239, 13.396955, 14.277435, 14.30952, 15.536297, 15.201007, 14.772035, 14.669621, 14.471099, 13.153375, 13.449821, 13.300757, 13.149848, 12.592501, 12.719534, 11.95098, 12.496373, 12.520264, 13.278911, 13.110999, 14.895787, 15.3184, 15.23038, 14.751884, 13.064603, 13.158887, 11.842749, 13.212492, 13.877838, 15.193656, 15.6487665, 15.34119, 15.01142, 15.377963, 15.2900915, 14.382945, 14.558608, 13.941909, 14.816515, 16.68391, 17.149096, 17.508804, 17.687923, 18.197863, 19.545357, 16.819693, 16.0931, 15.392584, 15.250863, 16.275665, 16.907906, 15.375264, 16.684265, 16.241484, 13.530799, 12.603335, 10.2360325, 11.58296, 12.237069, 12.498277999999999, 11.723694, 12.128446, 11.901298, 11.682638, 11.671132, 12.558043, 12.420364, 13.477299, 14.706276, 14.919453, 14.171822, 12.623031, 13.646749, 12.8415575, 12.391129, 13.078075, 13.768739, 13.879244, 13.670708, 11.685398, 10.112152, 10.66794, 10.47934, 12.905353, 12.900352, 14.584541, 13.485536, 12.664185, 11.404817, 12.207581, 12.827701, 12.570791, 11.782274, 13.015697, 13.567351, 13.600024, 15.979872, 15.95041, 15.939937, 15.332873, 14.068948, 13.980281, 12.426311, 12.5494795, 12.967528, 12.980402999999999, 13.089603, 13.269105, 12.266268, 11.094162, 8.843196, 5.570469, 3.994506, 4.0374703, 4.868406, 2.0861347, 6.7902017, 6.8697076, 8.305506, 9.426254, 10.81524, 11.148779, 9.7885475, 8.846456, 7.086477, 5.8845925, 4.7551694, 4.3413343, 2.6451044, 1.6724685, 4.995915, 5.2990785, 7.4821873, 7.5398207, 7.4104524, 6.2543573, 4.467407, 3.135835, 4.5168705, 5.6765723, 5.3808489999999995, 7.2642226, 9.510985, 10.286624, 11.224755, 12.08678, 10.514931, 10.131466, 10.329097, 10.011662, 10.089813, 11.305507, 11.100749, 10.940602, 12.994211, 14.017464, 14.907439, 14.52128, 14.420862, 14.9589205, 14.232042, 13.042587, 12.620633, 11.513233, 11.14114, 10.113198, 10.698134, 10.929278, 11.567786, 11.619815, 11.898985, 11.949863, 12.865493, 13.377321, 12.323294, 13.356062, 12.502877, 12.061424, 12.149652, 11.792138, 11.710219, 11.460749, 12.197535, 11.890954, 12.577824, 11.774048, 11.510963, 11.719377, 11.352217, 11.280321, 10.892682, 10.899915, 10.810691, 10.620931, 10.510856, 7.0214477, 6.2926555, 7.0675435, 7.8671894, 8.537787, 8.202033, 9.256767, 7.107193, 5.246618, 6.067156, 4.544787, 4.001785, 3.1040359, 4.017001, 5.385298, 7.5227175, 7.2116876, 6.4722557, 4.28679, 4.958324, 4.9603972, 6.7431927, 6.7603507, 6.1516585, 6.7289639999999995, 7.1888914, 7.359434, 7.6293564, 8.686263, 9.028872, 8.999783, 9.139891, 10.453138, 9.562012, 9.508186, 9.828071, 10.537847, 9.851962, 9.810312, 8.743022, 8.88912, 10.39514, 11.586257, 11.131968, 10.621405, 8.2218275, 10.263543, 9.233443, 10.155178, 11.695307, 10.593328, 11.397532, 10.881741, 9.119954, 8.871001, 9.826925, 9.9098015, 10.767063, 10.3316345, 10.260539, 10.300579, 9.691856, 11.715281, 11.252487, 10.272054, 9.481582, 8.671275, 8.610097, 8.150836, 10.6754875, 11.798141, 10.302701, 9.521233, 11.75468, 12.049493, 11.331218, 11.9391, 10.603164, 9.1921215, 9.659158, 9.819724, 10.228875, 9.461567, 8.423927, 4.298635, 4.7550793, 4.839838, 5.5476203, 6.665193, 6.59035, 6.9389215, 7.6052237, 8.736445, 8.441269, 8.719084, 8.391178, 7.742953, 7.3014483, 7.309999, 9.405098, 9.210797, 7.8179193, 2.9853125, 6.190835, 7.5326056, 8.147798, 9.495469, 9.609532, 8.106489, 4.4750667, 5.6420155, 6.126643, 5.5918508, 1.6133136000000001, 3.6953785, 4.536925, 6.0846343, 5.532598, 2.4939399, 4.021074, 5.7538214, 5.191928, 2.308193, 2.771725, 4.5325413, 5.89916, 5.9098945, 6.3680534, 3.3816612, 1.7384729, 0.0, 0.017960468, 0.7744565999999999, 1.6669996999999999, 0.84461296, 3.4133222, 6.8794830000000005, 7.276915, 9.49239, 9.072434, 9.518409, 7.5909896, 2.5994631999999998, 5.229657, 6.5765033, 7.7857904, 8.298266, 8.878128, 9.118628, 9.070256, 10.674588, 9.444307, 9.001564, 8.711135, 8.391155, 6.297752, 7.456183, 6.0348964, 4.0666857, 3.4390848, 4.2762785, 4.489963, 6.406127, 7.537002, 8.258655, 8.768894, 8.630194, 8.360667, 8.918959, 8.131152, 7.9405503, 9.046901, 8.830217, 7.3245735, 5.9833617, 4.452699, 6.513869, 7.6267304, 9.3254385, 9.858159, 9.620563, 11.322063, 11.748285, 11.928486, 11.63088, 10.54348, 10.330786, 8.5874195, 8.659896, 7.743647, 8.736926, 9.767019, 9.540866, 8.5029125, 8.031605, 8.059956, 6.2640667, 5.073432, 3.3741753, 5.5535846, 6.716562, 7.416762, 7.9978423, 9.156796, 9.199707, 7.6043769999999995, 8.566883, 8.460336999999999, 7.652585, 4.5885563, 4.8528223, 7.2322016, 7.6877427, 8.885449, 9.388127, 8.7915745, 9.09878, 6.1803145, 6.2394667, 8.223068, 8.668523, 7.664021, 7.201597, 2.7086594, 6.449319, 7.0767527, 8.315416, 7.1866794, 5.35526, 5.3101263, 5.9375973, 6.1891994, 5.3627915, 3.3969564, 3.7013967, 4.8146873, 3.9036975, 5.5747995, 4.1256375, 5.1860013, 1.6512549, 2.538088, 3.309599, 3.3474314, 3.4873593, 3.9164947999999997, 4.438767, 4.879531, 3.7519894, 3.2100773, 2.9966806999999998, 4.3771763, 4.5900955, 5.0473995, 4.700371, 4.5801034, 3.282933, 4.137653, 5.9342875, 5.720695, 2.899191, 3.0299609, 3.8979676, 3.6106505, 4.347376, 4.9486823, 2.923581, 1.7844741];
                    let jsonString = '{!! $speed !!}';
                    let speedArray = JSON.parse(jsonString);

                    var map = L.map('map').setView(latlngs[0], 15);

                    var OpenStreetMap = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; Contributors'
                    });
                    var CartoDB_Positron = L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
                        attribution: '&copy; Contributors'
                    });
                    var CartoDB_Dark_Matter = L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
                        attribution: '&copy; Contributors'
                    });
                    var Stamen_Toner = L.tileLayer('https://stamen-tiles.a.ssl.fastly.net/toner/{z}/{x}/{y}.png', {
                        attribution: '&copy; Contributors'
                    });
                    var Stamen_Terrain = L.tileLayer('https://stamen-tiles.a.ssl.fastly.net/terrain/{z}/{x}/{y}.jpg', {
                        attribution: '&copy; Contributors'
                    });
                    var Stamen_Watercolor = L.tileLayer('https://stamen-tiles.a.ssl.fastly.net/watercolor/{z}/{x}/{y}.jpg', {
                        attribution: '&copy; Contributors'
                    });

                    OpenStreetMap.addTo(map);

                    var baseMaps = {
                        "OpenStreetMap": OpenStreetMap,
                        "CartoDB Positron": CartoDB_Positron,
                        "CartoDB Dark Matter": CartoDB_Dark_Matter,
                        "Stamen Toner": Stamen_Toner,
                        "Stamen Terrain": Stamen_Terrain,
                        "Stamen Watercolor": Stamen_Watercolor
                    };
                    L.control.layers(baseMaps).addTo(map);

                    @foreach($ride as $k=>$v)
                    @php 
                    $start_lat=$v['latitude'];
                    $start_lang=$v['longitude'];
                    if(isset($ride[$k+1]['latitude']))
                    {
                        $end_lat=$ride[$k+1]['latitude'];
                        $end_lang=$ride[$k+1]['longitude'];
                    }else{
                        $end_lat=$v['latitude'];
                        $end_lang=$v['longitude'];
                    }

                    @endphp
                    L.polyline([
                        [{{$start_lat}}, {{$start_lang}}],
                        [{{$end_lat}}, {{$end_lang}}]
                    ], {
                        color: 'green',
                        weight: 5
                    }).addTo(map);
                    @endforeach

                    // Start/End markers
                    @foreach($ride_passengers as $v)

                    var address = "{{$v->address}}";

                    fetch(`https://maps.googleapis.com/maps/api/geocode/json?address=${encodeURIComponent(address)}&key={{env('MAP_KEY')}}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === "OK") {
                            let location = data.results[0].geometry.location;
                            let latlng = [location.lat, location.lng];

                        L.marker(latlng, {
                            icon: L.icon({
                            iconUrl: '{{$v->icon}}',
                            iconSize: [32, 32],
                            iconAnchor: [16, 32]
                            })
                        })
                        .addTo(map)
                        .bindPopup('{{$v->name}}');
                        } else {
                        console.error("Address not found" +address);
                        }
                    })
                    .catch(error => console.error('Geocoding failed:', error));

                    @endforeach

                   
                    let office_address = "{{$company_address}}";

                    fetch(`https://maps.googleapis.com/maps/api/geocode/json?address=${encodeURIComponent(office_address)}&key={{env('MAP_KEY')}}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === "OK") {
                        let location = data.results[0].geometry.location;
                        let latlng = [location.lat, location.lng];

                        L.marker(latlng, {
                            icon: L.icon({
                            iconUrl: 'https://app.svktrv.in/assets/img/office.png',
                            iconSize: [32, 32],
                            iconAnchor: [16, 32]
                            })
                        })
                        .addTo(map)
                        .bindPopup('Office');
                        } else {
                        console.error("Address not found office" +office_address);
                        }
                    })
                    .catch(error => console.error('Geocoding failed:', error));

                    var carIcon = L.icon({
                        iconUrl: 'https://app.svktrv.in/favicon.ico',
                        iconSize: [40, 40],
                        iconAnchor: [20, 40]
                    });

                    var marker = L.marker(latlngs[0], {
                        icon: carIcon
                    }).addTo(map);
                    var index = 0;
                    var interval = null;
                    var animationDuration = 1000;
                    var playing = false;

                    function updateProgress() {
                        var percent = (index / (latlngs.length - 1)) * 100;
                        var progressBar = document.getElementById('progress-bar');
                        progressBar.style.width = percent + '%';
                        if (percent < 30) {
                            progressBar.style.background = 'red';
                        } else if (percent < 70) {
                            progressBar.style.background = 'orange';
                        } else {
                            progressBar.style.background = '#4caf50';
                        }
                    }

                    function updateETA(kmph) {
                        return updateTime(kmph);
                        var remainingPoints = latlngs.length - 1 - index;
                        if (kmph > 0 && remainingPoints > 0) {
                            var avgDistancePerPoint = 16.87 / (latlngs.length - 1);
                            var remainingDistance = avgDistancePerPoint * remainingPoints;
                            var etaHours = remainingDistance / kmph;
                            var etaMinutes = Math.floor(etaHours * 60);
                            var etaSeconds = Math.floor((etaHours * 3600) % 60);
                            document.getElementById('eta').innerText = '⏳ ETA: ' + etaMinutes + ' min ' + etaSeconds + ' sec';
                        } else {
                            document.getElementById('eta').innerText = '⏳ ETA: --';
                        }
                    }

                    function updateTime(kmph) {
                        if (index >= latlngs.length - 1) return;
                        let timestamp = speedArray[index].timestamp; // in milliseconds
                        let date = new Date(timestamp);

                        let hours = date.getHours();
                        let minutes = date.getMinutes().toString().padStart(2, '0');
                        let seconds = date.getSeconds().toString().padStart(2, '0');
                        let ampm = hours >= 12 ? 'PM' : 'AM';

                        hours = hours % 12;
                        hours = hours ? hours : 12; // convert 0 to 12

                        let formattedTime = `${hours}:${minutes}:${seconds} ${ampm}`;

                        document.getElementById('eta').innerText = '⏳ Time: '+formattedTime;
                    }

                    function updateSpeedDisplay(kmph) {
                        document.getElementById('speed-display').innerText = '🚀 Speed: ' + kmph.toFixed(1) + ' km/h';
                    }

                    function moveToNextSegment() {
                        if (index >= latlngs.length - 1) return;
                        var start = latlngs[index].slice();
                        var end = latlngs[index + 1];
                        var duration = animationDuration;
                        var steps = 50;
                        var step = 0;
                        var deltaLat = (end[0] - start[0]) / steps;
                        var deltaLng = (end[1] - start[1]) / steps;
                        var speedKmph = (speedArray[index].speed || 0) * 3.6;

                        interval = setInterval(function() {
                            // GPS drift simulation: add tiny random noise
                            var driftLat = (Math.random() - 0.5) * 0.00000;
                            var driftLng = (Math.random() - 0.5) * 0.00000;

                            start[0] += deltaLat + driftLat;
                            start[1] += deltaLng + driftLng;
                            marker.setLatLng(start);
                            updateSpeedDisplay(speedKmph);
                            updateProgress();
                            updateETA(speedKmph);

                            step++;
                            if (step >= steps) {
                                clearInterval(interval);
                                index++;
                                if (playing) moveToNextSegment();
                            }
                        }, duration / steps);
                    }

                    function startAnimation() {
                        if (!playing) {
                            playing = true;
                            moveToNextSegment();
                        }
                    }

                    function pauseAnimation() {
                        playing = false;
                        clearInterval(interval);
                    }


                    function updateSpeed() {
                        var selected = document.getElementById('speed-select').value;
                        animationDuration = parseInt(selected);
                    }

                    function resetAnimation() {

                        pauseAnimation();
                        index = 0;
                        marker.setLatLng(latlngs[0]);
                        updateSpeedDisplay(0);
                        updateProgress();
                        updateETA(0);
                    }
                </script>

            </div>
        </div>
    </div>
</div>


@endsection

@section('footer')


<script src="/assets/vendor/libs/cleavejs/cleave.js"></script>
<script src="/assets/vendor/libs/jquery-repeater/jquery-repeater.js"></script>

<script src="/assets/js/app-invoice-add.js"></script>
<script src="/assets/vendor/libs/select2/select2.js"></script>

<script src="/assets/js/forms-selects.js"></script>
<script src="/assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js"></script>

<script>
    var defaultTime = '09:00';
    $('#bs-datepicker-autoclose').datepicker({
        todayHighlight: true,
        autoclose: true,
        format: 'dd MM yyyy',
        orientation: isRtl ? 'auto right' : 'auto left'
    });
</script>


@endsection