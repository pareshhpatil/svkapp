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

    .timeline.timed:before {
        left: 60px;
    }

    .navigate-icon {
        font-size: 25px;
        background: #196FE1;
        color: #ffffff;
        border-radius: 20%;
        padding: 5px;
    }
</style>
<div id="appCapsule" class="full-height">

    <div id="app" class="section ">
        <div id="loader" v-if="notloded">
            <img src="/assets/img/animation1.gif" alt="icon" class="loading-icon">
        </div>
        <div v-if="!data.driver.name">
            <div class="appHeader bg-warning text-light" style="top:50px;margin-bottom:50px">
                <div class="pageTitle">Cab not assigned yet</div>
            </div>
            <div class="mt-2">
                &nbsp;
            </div>
        </div>
        <div v-if="data.ride.status==3">
            <div class="appHeader bg-danger text-light" style="top:50px;margin-bottom:50px">
                <div class="pageTitle">Ride has been cancelled</div>
            </div>
            <div class="mt-2">
                &nbsp;
            </div>
        </div>
        <div class="card mt-1 mb-1">
            <ul class="listview flush transparent no-line image-listview detailed-list mt-1 mb-1">
                <!-- item -->
                <li>
                    <a href="#" class="item" style="padding: 5px 16px;min-height: 20px;">
                        <div class="icon-box bg-success">
                            <ion-icon name="arrow-up-outline" role="img" class="md hydrated" aria-label="arrow up outline"></ion-icon>
                        </div>
                        <div class="in">
                            <div>
                                <strong>Today 11:38 AM</strong>
                                <div class="text-small text-secondary" v-text="data.ride.start_location"> </div>
                            </div>

                        </div>
                    </a>

                </li>
                <li>
                    <ul class="" style="margin-left: 50px;">
                        <!-- item -->
                        <li v-for="(item, index) in data.ride_passengers">
                            <a href="#" class="item" style="padding: 0px 16px;min-height: 20px;">

                                <div class="in">
                                    <div>
                                        <strong><span v-text="item.name"></span>
                                            <span class="text-small text-secondary" v-text="item.mobile"></span></strong>
                                    </div>

                                </div>
                            </a>
                        </li>

                    </ul>
                </li>
                <!-- * item -->
                <!-- item -->
                <li>
                    <a href="#" class="item" style="padding: 5px 16px;min-height: 20px;">
                        <div class="icon-box bg-danger">
                            <ion-icon name="arrow-down-outline" role="img" class="md hydrated" aria-label="arrow down outline"></ion-icon>
                        </div>
                        <div class="in">
                            <div>
                                <strong>Drop</strong>
                                <div class="text-small text-secondary" v-text="data.ride.end_location"></div>
                            </div>

                        </div>
                    </a>
                </li>

                <!-- * item -->
            </ul>
        </div>
        <div class="col text-center mb-1">
            <button v-if="data.ride.status==1" onclick="startlocation();" class="btn btn-success text-center">
                Start Ride
            </button>
            <a v-if="data.ride.status==2" href="/driver/ride/status/{{$ride_id}}/6" class="btn btn-success text-center">
                Reached at Location
            </a>
            <a v-if="data.ride.status==6" href="/driver/ride/status/{{$ride_id}}/7" class="btn btn-success text-center">
                Passenger Picked Up
            </a>
            <button v-if="data.ride.status==6" data-bs-toggle="modal" data-bs-target="#endmodal" class="btn btn-danger text-center">
                End Ride
            </button>
        </div>
        <hr>
        <form id="frm" onsubmit="" action="/upload/ride/file" method="post" enctype="multipart/form-data">

            <div class="col text-center"><div class="text-info" id="loder" role="status"></div></div>

            @csrf
            <div class="row mb-1" v-for="(item, index) in data.vehicle_photos">
                <div class="col-6">
                    <h3 style="padding-top: 20px;" v-if="index==0">Vehicle photos</h3>
                    <a class="btn btn-info text-center" v-on:click="addNewPhoto('Vehicle')" v-if="index==0">
                        Add new
                    </a>
                </div>
                <div class="col-6">
                    <div class="avatar-section">
                        <a href="#" v-on:click="selectPhoto('vehicle_'+index)">
                            <img :id="'img_vehicle_'+index" alt="avatar" class="imaged w100 " :src="item.image">
                            <input type="file" :id="'vehicle_'+index" v-on:change="onImageChange" name="file[]" accept=".png, .jpg, .jpeg" style="display: none;">
                            <input type="hidden" :id="'id_vehicle_'+index" v-model="item.id" name="vehicle_photo_ids[]">
                            <input type="hidden" :id="'type_vehicle_'+index" v-model="item.type" name="photo_type[]">
                            <span class="button">
                                <ion-icon name="camera-outline" role="img" class="md hydrated" aria-label="camera outline"></ion-icon></span>
                        </a>
                    </div>
                </div>
            </div>
            <hr v-if="data.ride.status>1">

            <div v-if="data.ride.status>1" class="row mb-1" v-for="(item, index) in data.driver_photos">
                <div class="col-6">
                    <h3 style="padding-top: 20px;" v-if="index==0">Driver photos</h3>
                    <a class="btn btn-info text-center" v-on:click="addNewPhoto('Driver')" v-if="index==0">
                        Add new
                    </a>
                </div>
                <div class="col-6">
                    <div class="avatar-section">
                        <a href="#" v-on:click="selectPhoto('driver_'+index)">
                            <img :id="'img_driver_'+index" alt="avatar" class="imaged w100 " :src="item.image">
                            <input type="file" :id="'driver_'+index" v-on:change="onImageChange" name="file[]" accept=".png, .jpg, .jpeg" style="display: none;">
                            <input type="hidden" :id="'id_driver_'+index" v-model="item.id" name="driver_photo_ids[]">
                            <input type="hidden" :id="'type_driver_'+index" v-model="item.type" name="photo_type[]">
                            <span class="button">
                                <ion-icon name="camera-outline" role="img" class="md hydrated" aria-label="camera outline"></ion-icon></span>
                        </a>
                    </div>
                </div>
            </div>
            <hr v-if="data.ride.status>1">
            <div v-if="data.ride.status>1" class="row mb-1" v-for="(item, index) in data.placard_photos">
                <div class="col-6">
                    <h3 style="padding-top: 20px;" v-if="index==0">Placard photos</h3>
                    <a class="btn btn-info text-center" v-on:click="addNewPhoto('Placard')" v-if="index==0">
                        Add new
                    </a>
                </div>
                <div class="col-6">
                    <div class="avatar-section">
                        <a href="#" v-on:click="selectPhoto('placard_'+index)">
                            <img :id="'img_placard_'+index" alt="avatar" class="imaged w100 " :src="item.image">
                            <input type="file" :id="'placard_'+index" v-on:change="onImageChange" name="file[]" accept=".png, .jpg, .jpeg" style="display: none;">
                            <input type="hidden" :id="'id_placard_'+index" v-model="item.id" name="placard_photo_ids[]">
                            <input type="hidden" :id="'type_placard_'+index" v-model="item.type" name="photo_type[]">
                            <span class="button">
                                <ion-icon name="camera-outline" role="img" class="md hydrated" aria-label="camera outline"></ion-icon></span>
                        </a>
                    </div>
                </div>
            </div>


        </form>








        <div class="modal fade dialogbox" id="endmodal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirm?</h5>
                    </div>
                    <div class="modal-body text-start mb-2">
                        <p>Do you want to continue?</p>
                    </div>
                    <div class="modal-footer">
                        <div class="btn-inline">
                            <button type="button" class="btn btn-text-secondary" data-bs-dismiss="modal">CLOSE</button>
                            <button type="button" onclick="stoplocation();" class="btn btn-primary bg-red">CONFIRM</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade dialogbox" id="outmodal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirm?</h5>
                    </div>
                    <div class="modal-body text-start mb-2">
                        <p>Do you want to continue?</p>
                    </div>
                    <div class="modal-footer">
                        <div class="btn-inline">
                            <button type="button" id="closeout" class="btn btn-text-secondary" data-bs-dismiss="modal">CLOSE</button>
                            <button type="button" v-on:click="out();" class="btn btn-primary bg-red">CONFIRM</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<div id="toast-15" class="toast-box toast-center" style="z-index: 5000;">
    <div class="in">
        <ion-icon name="checkmark-circle" class="text-success"></ion-icon>
        <div class="text">
            Request sent successfully wait for 30 sec
        </div>
    </div>
    <button type="button" onclick="closeT(15);" class="btn btn-sm  btn-text-light bg-red">CLOSE</button>
</div>

@endsection



@section('footer')

<script src="https://unpkg.com/webtonative@1.0.43/webtonative.min.js"></script>




<script>
    function successCallback(position) {
        const {
            latitude,
            longitude,
            altitude,
            speed
        } = position;
        // Show a map centered at latitude / longitude.
    }


    function start() {
        window.WTN.backgroundLocation.start({
            callback: successCallback,
            apiUrl: "https://app.siddhivinayaktravelshouse.in/ride/track/{{$ride_id}}",
            timeout: 30,
            data: "ride_id-{{$ride_id}}",
            backgroundIndicator: true,
            pauseAutomatically: false,
            distanceFilter: "{{env('DISTANCE_FILTER')}}",
            desiredAccuracy: "best",
            activityType: "other",
        });
    }

    function stop() {
        window.WTN.backgroundLocation.stop();
    }

    function startlocation() {
        lod(true);
        start();
        window.location.href = "/driver/ride/status/{{$ride_id}}/2";
    }

    function stoplocation() {
        lod(true);
        stop();
        window.location.href = "/driver/ride/status/{{$ride_id}}/5";
    }
    @if($data['ride']['status'] == 2)
    //setInterval(function() {
    //    stop();
    //    start();
    //}, 300000);
    @endif
</script>

<script>
    new Vue({
        el: '#app',
        data() {
            return {
                data: [],
                selected_id: 0,
                verror: '',
                alldone: false,
                image: null,
                notloded: true,
                selected_photo: '',
                defaultPhoto: {
                    image: '/assets/img/upload.png',
                    id: '0',
                    type: 'na'
                }
            }
        },
        mounted() {
            this.data = JSON.parse('{!!json_encode($data)!!}');
            this.notloded = false;
        },
        methods: {
            setId(id) {
                this.selected_id = id;
                //this.data.ride_passengers[id].status=1;
            },
            verifyotp() {
                var otp = document.getElementById('otp').value;
                array = this.data.ride_passengers[this.selected_id];
                if (array.otp != otp && array.mobile != '') {
                    this.verror = 'Invalid OTP';
                    document.getElementById('otp').value = '';
                } else {
                    document.getElementById('otp').value = '';
                    this.data.ride_passengers[this.selected_id].status = 1;
                    axios.get('/driver/ride/passenger/status/' + array.id + '/1');
                    document.getElementById('closeotp').click();
                }
            },
            directverify(selected_id) {
                array = this.data.ride_passengers[selected_id];
                this.data.ride_passengers[selected_id].status = 1;
                axios.get('/driver/ride/passenger/status/' + array.id + '/1');
            },
            resendotp(selected_id) {
                array = this.data.ride_passengers[this.selected_id];
                axios.get('/driver/ride/passenger/resendotp/' + array.id);
                toastbox('toast-15');
            },
            noshow() {
                array = this.data.ride_passengers[this.selected_id];
                this.data.ride_passengers[this.selected_id].status = 4;
                axios.get('/driver/ride/passenger/status/' + array.id + '/4');
                this.endStatus();
            },
            reach(id) {
                array = this.data.ride_passengers[id];
                this.data.ride_passengers[id].status = 5;
                axios.get('/driver/ride/passenger/status/' + array.id + '/5');
            },
            out() {
                array = this.data.ride_passengers[this.selected_id];
                this.data.ride_passengers[this.selected_id].status = 2;
                axios.get('/driver/ride/passenger/status/' + array.id + '/2');
                document.getElementById('closeout').click();
                this.endStatus();
            },
            endStatus() {
                var done = true;
                for (let i = 0; i < this.data.ride_passengers.length; i++) {
                    pstatus = this.data.ride_passengers[i].status;
                    if (pstatus == 0 || pstatus == 1 || pstatus == 5) {
                        console.log(pstatus);
                        done = false;
                    }
                }
                if (done == true) {
                    stoplocation();
                }
                this.alldone = done;
            },
            call(mobile) {
                axios.get('/call/' + mobile);
                toastbox('toast-15');
            },
            onImageChange(e) {
                // alert(e.target.files[0]);
                this.image = e.target.files[0];
                // console.log(this.image);
                this.formSubmit(e.target.files[0]);
            },
            selectPhoto(selected_photo) {
                this.selected_photo = selected_photo;
                document.getElementById(selected_photo).click();
            },
            addNewPhoto(type) {
                this.defaultPhoto.type = type;
                if (type == 'Vehicle') {
                    this.data.vehicle_photos.push(this.defaultPhoto);
                } else if (type == 'Placard') {
                    this.data.placard_photos.push(this.defaultPhoto);
                } else if (type == 'Driver') {
                    this.data.driver_photos.push(this.defaultPhoto);
                }

            },
            formSubmit(image) {
                lo(true);
                let currentObj = this;

                const config = {
                    headers: {
                        'content-type': 'multipart/form-data'
                    }
                }
                const form = document.getElementById('frm');
                let formData = new FormData(form);

                //image = document.getElementById('fileuploadInput').value;
                // alert(image);
                //console.log(formData);
                var sphotoid = this.selected_photo;

                formData.append('image', image);
                formData.append('id', document.getElementById('id_' + sphotoid).value);
                formData.append('type', document.getElementById('type_' + sphotoid).value);
                formData.append('ride_id', this.data.ride.id);
                // formData.append('test', 'hiii');
                axios.post('/upload/ride/file', formData, config)
                    .then(function(response) {
                        document.getElementById('img_' + sphotoid).src = response.data.url;
                        document.getElementById('id_' + sphotoid).value = response.data.id;
                        lo(false);
                    })
                    .catch(function(error) {
                        alert(error);
                        currentObj.output = error;
                        lo(false);
                    });
            }
        }
    });
</script>


@endsection
