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

        <div class="">
            <div class="splash-page  mt-2">

                <div class="transfer-verification">
                    <div class="transfer-amount">
                        <span class="caption">Start Time</span>
                        <h5 v-html="data.ride.start_time"></h5>
                    </div>
                    <div class="from-to-block ">
                        <div v-if="data.ride.type=='Pickup'" class="item text-start text-center">
                            <img src="/assets/img/home.png?v=1" alt="avatar" class="imaged w48">
                            <strong>Home</strong>
                        </div>
                        <div v-if="data.ride.type=='Drop'" class="item text-start text-center">
                            <img src="/assets/img/office.png" alt="avatar" class="imaged w48">
                            <strong>Office</strong>
                        </div>
                        <div v-if="data.ride.type=='Drop'" class="item text-start text-center">
                            <img src="/assets/img/home.png?v=1" alt="avatar" class="imaged w48">
                            <strong>Home</strong>
                        </div>
                        <div v-if="data.ride.type=='Pickup'" class="item text-start text-center">
                            <img src="/assets/img/office.png" alt="avatar" class="imaged w48">
                            <strong>Office</strong>
                        </div>
                        <div class="arrow"></div>
                    </div>
                </div>

            </div>
        </div>


        <div class="mt-1">Route</div>
        <div class="timeline timed ms-1 me-2" style="padding-left: 60px;">

            <div class="item" v-if="data.ride.type=='Drop'">
                <span class="time" v-html="data.ride.start_time"></span>
                <div class="dot bg-info"></div>
                <div class="content">
                    <h4 class="title">Office
                        <div class="text-end" style="right: 10px;float: right;">
                            <img src="/assets/img/office.png" alt="avatar" class="imaged w24 rounded right">
                        </div>
                    </h4>
                    <div class="text" v-html="data.project.location"></div>


                </div>
            </div>
            <div v-for="(item, index) in data.ride_passengers" class="item">
                <span class="time">
                    <span v-if="data.ride.type=='Drop'" v-html="item.drop_time"></span>
                    <span v-if="data.ride.type=='Pickup'" v-html="item.pickup_time"></span>
                    <img v-if="item.icon==''" src="/assets/img/sample/avatar/avatar1.jpg" alt="avatar" class="imaged w48  rounded right">
                    <img v-if="item.icon!=''" :src="item.icon" alt="avatar" class="imaged w48  rounded right">
                </span>
                <div v-if="data.ride.type=='Drop'" class="dot bg-primary bg-red"></div>
                <div v-if="data.ride.type=='Pickup'" class="dot bg-info "></div>
                <div class="content">
                    <h4 class="title"><span v-on:click="window.open('https://www.google.com/maps/place/'+item.address, '_system');" v-html="item.name"></span>
                        <div v-if="item.status!=3" class="text-end" style="right: 10px;float: right;">
                            <div v-if="data.ride.status==2">
                                <div class="dropdown">
                                    <button v-if="item.status==0 || item.status==5" v-on:click="setId(index)" data-bs-toggle="dropdown" aria-expanded="false" class="btn btn-success text-center dropdown-toggle">
                                        IN
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end" style="">
                                        <a v-if="item.status==0" class="dropdown-item" v-on:click="window.open('https://www.google.com/maps/place/'+item.address, '_system');">Navigate</a>
                                        <div class="dropdown-divider"></div>
                                        <a v-if="item.status!=5" class="dropdown-item" v-on:click="reach(index);">Reached at Location</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" v-on:click="setId(index);" data-bs-target="#inmodal" data-bs-toggle="modal" href="#">Passenger Entry</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" v-on:click="setId(index);" data-bs-target="#noshowmodal" data-bs-toggle="modal" href="#">No Show</a>
                                    </div>
                                </div>
                                <button v-if="item.status==1" v-on:click="setId(index);" data-bs-toggle="modal" data-bs-target="#outmodal" class="btn btn-danger text-center">
                                    OUT
                                </button>

                            </div>
                        </div>

                    </h4>
                    <div v-html="item.mobile" class="text text-danger"></div>
                    <div v-if="item.status==3" class="text text-danger">Cancelled</div>

                    <div class="text">
                        <span style="float: left;" v-html="item.location"></span>

                    </div>

                </div>
                <br>
            </div>
            <div class="item" v-if="data.ride.type=='Pickup'">
                <span class="time">
                    <span v-html="data.ride.end_time"></span>
                    <br>
                    <img src="/assets/img/office.png" alt="avatar" class="imaged w48 rounded right">
                </span>
                <div class="dot bg-primary bg-red"></div>
                <div class="content">
                    <h4 class="title">Office
                        <div class="text-end" style="right: 10px;float: right;">

                        </div>
                    </h4>
                    <div class="text" v-html="data.project.location"></div>
                </div>
            </div>


        </div>
        <div class="col text-center">
            <button v-if="data.ride.status==1" onclick="startlocation();" class="btn btn-success text-center">
                Start Ride
            </button>
            <button v-if="data.ride.status==2 && alldone==true" data-bs-toggle="modal" data-bs-target="#endmodal" class="btn btn-danger text-center">
                End Ride
            </button>
        </div>







        <div class="modal fade dialogbox" id="inmodal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Enter OTP</h5>
                    </div>
                    <div class="modal-body text-start mb-2">
                        <div class="form-group basic">
                            <div class="input-wrapper">
                                <input type="text" required class="form-control verification-input" id="otp" autofocus inputmode="numeric" name="otp" pattern="[0-9]*" minlength="4" placeholder="••••" maxlength="4">
                                <i class="clear-input">
                                    <ion-icon name="close-circle"></ion-icon>
                                </i>
                                <p v-if="verror!=''" class="text-primary text-center" v-html="verror"></p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="btn-inline">
                            <button type="button" id="closeotp" class="btn btn-text-secondary" data-bs-dismiss="modal">CLOSE</button>
                            <button type="button" v-on:click="verifyotp()" class="btn btn-primary bg-red">VERIFY</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade dialogbox" id="noshowmodal" tabindex="-1" role="dialog">
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
                            <button type="button" v-on:click="noshow()" data-bs-dismiss="modal" class="btn btn-primary bg-red">NO SHOW</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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



@endsection



@section('footer')

<script src="https://unpkg.com/webtonative@1.0.43/webtonative.min.js"></script>




<script>
    function startlocation() {
        lod(true);
        window.WTN.backgroundLocation.start({
            callback: false,
            apiUrl: "https://app.svktrv.in/ride/track/{{$ride_id}}",
            timeout: 30,
            data: "ride_id-{{$ride_id}}",
            backgroundIndicator: true,
            pauseAutomatically: false,
            distanceFilter: 0.0,
            desiredAccuracy: "best",
            activityType: "other",
        });

        window.location.href = "/driver/ride/status/{{$ride_id}}/2";
    }

    function stoplocation() {
        lod(true);
        window.WTN.backgroundLocation.stop();
        window.location.href = "/driver/ride/status/{{$ride_id}}/5";
    }
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
                notloded: true
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
                if (array.otp != otp && array.mobile!='') {
                    this.verror = 'Invalid OTP';
                    document.getElementById('otp').value = '';
                } else {
                    document.getElementById('otp').value = '';
                    this.data.ride_passengers[this.selected_id].status = 1;
                    axios.get('/driver/ride/passenger/status/' + array.id + '/1');
                    document.getElementById('closeotp').click();
                }
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
                this.alldone = done;
            }
        }
    })
</script>


@endsection