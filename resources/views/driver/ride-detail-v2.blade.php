@extends('layouts.app')
@section('content')
<script src="https://unpkg.com/webtonative@1.0.63/webtonative.min.js"></script>

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

    .timeline.timed {
        padding-left: 25px;
        padding-right: 20px;
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
        left: 25px;
    }

    .navigate-icon {
        font-size: 25px;
        background: #196FE1;
        color: #ffffff;
        border-radius: 20%;
        padding: 5px;
    }

    extra-small {
        line-height: normal;
        margin-top: 5px;
        text-align: center;
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
                    <div class="px-3">
                        <h4 style="font-weight:300" v-html="'Ride id: #'+ data.ride.id"></h4>
                        <h4 style="font-weight:300" v-html="'Ride type: '+ data.ride.trip_type"></h4>
                        <h4 style="font-weight:300" v-html="'Ride start: '+ data.ride.start_time"></h4>
                    </div>
                </li>
                <!-- * item -->
            </ul>
        </div>
        <div class="mt-1"><b>Route</b></div>
        <div class="card timeline timed " style="">

            <div class="item" v-if="data.ride.type=='Drop'">

                <div class="dot bg-info"></div>
                <div class="content">
                    <h4 class="title">Office
                        <div class="text-end" style="right: 10px;float: right;">
                            <a v-on:click="window.location.assign('https://www.google.com/maps/search/?api=1&query='+data.project.address, '_system');" class="btn btn-icon btn-outline-blue">
                                <ion-icon name="navigate-outline"></ion-icon>
                            </a>
                        </div>
                    </h4>
                    <div class="text" v-html="data.project.location + ' - ' +data.ride.start_short_time"></div>
                </div>
            </div>
            <div v-for="(item, index) in data.ride_passengers" :class="['item', { 'mb-0': !marginMap[index] }]">
                <span class="time">
                    <!-- <span v-if="data.ride.type=='Drop'" v-html="item.drop_time"></span> -->
                    <!-- <span v-if="data.ride.type=='Pickup'" v-html="item.pickup_time"></span> -->
                    <!--<img  src="/assets/img/sample/avatar/avatar1.jpg" alt="avatar" class="imaged w48  rounded right">
                    <img v-if="item.icon!=''" :src="item.icon" alt="avatar" class="imaged w48  rounded right">-->
                </span>
                <div v-if="data.ride.type=='Drop'" class="dot bg-primary bg-red"></div>
                <div v-if="data.ride.type=='Pickup'" class="dot bg-info "></div>
                <div class="content">
                    <h4 class="title"><span v-on:click="window.location.assign('https://www.google.com/maps/search/?api=1&query='+item.address, '_system');" v-html="item.name"></span> <span v-if="item.passenger_type==2"> (Escort)</span>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <!-- <span v-if="item.mobile!=''" v-on:click="call(item.mobile)" class="icon-box text-danger">
                            <ion-icon name="call-outline"></ion-icon>
                        </span> -->
                        <!-- <div v-if="item.status!=3" class="text-end" style="right: 10px;float: right;">
                            <div v-if="data.ride.status==2">
                                <div class="dropdown">
                                    <button v-if="item.status==0 || item.status==5" v-on:click="setId(index)" data-bs-toggle="dropdown" aria-expanded="false" class="btn btn-success text-center dropdown-toggle">
                                        IN
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end" style="">
                                        <a v-if="item.status==0" class="dropdown-item" v-on:click="window.location.assign('https://www.google.com/maps/search/?api=1&query='+item.address, '_system');">Navigate</a>
                                        <div class="dropdown-divider"></div>
                                        <a v-if="item.status!=5" class="dropdown-item" v-on:click="reach(index);">Reached at Location</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" v-if="item.mobile!=''" v-on:click="setId(index);" data-bs-target="#inmodal" data-bs-toggle="modal" href="#">Passenger Entry</a>
                                        <a class="dropdown-item" v-if="item.mobile==''" v-on:click="directverify(index);" href="#">Passenger Entry</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" v-on:click="setId(index);" data-bs-target="#noshowmodal" data-bs-toggle="modal" href="#">No Show</a>
                                    </div>
                                </div>
                                <button v-if="item.status==1" v-on:click="setId(index);" data-bs-toggle="modal" data-bs-target="#outmodal" class="btn btn-danger text-center">
                                    OUT
                                </button>

                            </div>
                        </div> -->

                    </h4>
                    <div v-if="item.status==3" class="text text-danger">Cancelled</div>

                    <div class="text">
                        <span v-if="data.ride.type=='Drop'" style="float: left;" v-html="item.location + ' - ' +item.pickup_time "></span>
                        <span v-if="data.ride.type=='Pickup'" style="float: left;" v-html="item.location + ' - ' +item.pickup_time "></span>
                    </div>

                    <div v-if="item.status!=3 && item.status!=1 && data.ride.status!=1" class="card-body d-flex justify-content-center flex-nowrap  w-100 mx-1">
                        <div v-if="item.status==0 || item.status==5" class="d-flex flex-column align-items-center me-3">
                            <a v-if="item.mobile!=''" v-on:click="setModal(1,item.mobile)" data-bs-target="#commonmodal" data-bs-toggle="modal" href="#" class="btn btn-icon btn-outline-primary">
                                <ion-icon name="call-outline"></ion-icon>
                            </a>
                            <extra-small>Call
                                <br>
                                Passenger
                            </extra-small>
                        </div>

                        <div v-if="item.status==0 && data.ride.type=='Pickup'" class="d-flex flex-column align-items-center me-3">
                            <a v-on:click="setModal(2,item.address)" data-bs-target="#commonmodal" data-bs-toggle="modal" href="#" class="btn btn-icon btn-outline-blue">
                                <ion-icon name="navigate-outline"></ion-icon>
                            </a>
                            <extra-small>Passenger
                                <br>
                                Location
                            </extra-small>
                        </div>

                        <div v-if="item.status==0 && data.ride.type=='Pickup'" class="d-flex flex-column align-items-center me-3">
                            <a v-on:click="setModal(3,index)" data-bs-target="#commonmodal" data-bs-toggle="modal" href="#" class="btn btn-icon btn-outline-warning">
                                <ion-icon name="location-outline"></ion-icon>
                            </a>
                            <extra-small>Reached
                                <br>
                                Location
                            </extra-small>
                        </div>

                        <div v-if="item.status==0 || item.status==5" class="d-flex flex-column align-items-center me-3">
                            <a v-on:click="setId(index);" data-bs-target="#inmodal" data-bs-toggle="modal" href="#" class="btn btn-icon btn-outline-success">
                                <ion-icon name="person-add-outline"></ion-icon>
                            </a>
                            <extra-small>Passenger
                                <br>Entry
                            </extra-small>
                        </div>

                        <div v-if="item.status==0 || item.status==5" class="d-flex flex-column align-items-center" style="min-width: 55px;">
                            <a v-on:click="setModal(5,index)" data-bs-target="#commonmodal" data-bs-toggle="modal" href="#" class="btn btn-icon btn-outline-danger">
                                <ion-icon name="close-circle-outline"></ion-icon>
                            </a>
                            <extra-small>Passenger
                                <br>No Show</extra-small>
                        </div>

                    </div>
                    <a v-if="item.status==1 && data.ride.type!='Pickup'" v-on:click="setModal(4,index)" data-bs-target="#commonmodal" data-bs-toggle="modal" href="#" class="btn btn-primary btn-block my-2">
                        <ion-icon name="walk-outline"></ion-icon> Drop Passenger
                    </a>
                </div>
            </div>
            <div class="item" v-if="data.ride.type=='Pickup'">
                <span class="pull-right">
                    <a v-on:click="window.location.assign('https://www.google.com/maps/search/?api=1&query='+data.project.address, '_system');" class="btn btn-icon btn-outline-blue">
                        <ion-icon name="navigate-outline"></ion-icon>
                    </a>
                </span>
                <div class="dot bg-primary bg-red"></div>
                <div class="content">
                    <h4 class="title">Office
                        <div class="text-end" style="right: 10px;float: right;">

                        </div>
                    </h4>
                    <div class="text" v-html="data.project.location+ ' - '+data.ride.end_short_time"></div>
                </div>
            </div>


        </div>
        <div class="col text-center">
            @if(!empty($data['ride_passengers']))
            <button v-if="data.ride.status==1" onclick="startlocation();" class="btn btn-success btn-block text-center mt-2">
                Start Ride
            </button>
            @else
            <br>
            No Passengers
            @endif
            <button v-if="data.ride.type=='Pickup' && allReadyForDrop" v-on:click="setModal(6,'')" data-bs-target="#commonmodal" data-bs-toggle="modal" href="#" class="btn btn-primary btn-block mt-2">
                <ion-icon name="walk-outline"></ion-icon> Drop All Passengers
            </button>
            <button v-if="data.ride.type=='Drop' && reachAtLocation && data.ride.status==2" v-on:click="setModal(7,'')" data-bs-target="#commonmodal" data-bs-toggle="modal" href="#" class="btn btn-warning btn-block mt-2">
                <ion-icon name="location-outline"></ion-icon> Reached At Pickup Location
            </button>
            <!-- <button v-if="data.ride.status==2 " data-bs-toggle="modal" data-bs-target="#endmodal" class="btn btn-danger text-center mt-2">
                End Ride
            </button> -->
            <!-- <p style="width: 100%; height:100px" id="speed"></p> -->
            <!-- <button  onclick="scanBarcode()" class="btn btn-success text-center mt-2">
                Scan
            </button> -->
            <!-- <a  href="/location.html" class="btn btn-success text-center mt-2">
                Test
                                        </a>  -->

            <!-- <button  onclick="restart()" class="btn btn-success text-center mt-2">
                App
            </button> 

            <button  onclick="restart1()" class="btn btn-success text-center mt-2">
                Third
            </button> 
            <button  onclick="restart2()" class="btn btn-success text-center mt-2">
                Admin
            </button> 
            <button  onclick="restart3()" class="btn btn-success text-center mt-2">
                Aws
            </button>  -->
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
                                <br>
                                <a v-on:click="resendotp()" class="text-primary text-center">Re-Send OTP</a>
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


        <div class="modal fade dialogbox" id="commonmodal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content" style="width: 80%;">
                    <div class="modal-header">
                        <h5 class="modal-title" v-html="commonModalTitle">Confirm?</h5>
                    </div>
                    <div class="modal-body text-start mb-2">
                        <p v-html="commonModalDescription">Do you want to continue?</p>
                    </div>

                    <div class="modal-footer">
                        <div class="btn-inline">
                            <button type="button" class="btn btn-text-secondary" data-bs-dismiss="modal">CLOSE</button>
                            <button type="button" v-on:click="commonModalClick()" data-bs-dismiss="modal" class="btn btn-primary bg-red">हाँ</button>
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

<div id="toast-16" class="toast-box toast-center" style="z-index: 5000;">
    <div class="in">
        <ion-icon name="checkmark-circle" class="text-success"></ion-icon>
        <div class="text">
            Ride ended successfully
        </div>
    </div>
    <button type="button" onclick="closeT(16);" class="btn btn-sm  btn-text-light bg-red">CLOSE</button>
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
<script type="text/javascript">
    window._mfq = window._mfq || [];
    (function() {
        var mf = document.createElement("script");
        mf.type = "text/javascript";
        mf.defer = true;
        mf.src = "//cdn.mouseflow.com/projects/50d13653-5856-4629-b229-ff191c828d5b.js";
        document.getElementsByTagName("head")[0].appendChild(mf);
    })();
</script>
@endsection



@section('footer')




<script>
    //WTN.clearAppCache(false);

    const {
        screen
    } = window.WTN

    //to keep device screen on all the time
    screen.keepScreenOn()


    function scanBarcode() {
        const {
            Format,
            BarcodeScan
        } = WTN.Barcode;
        BarcodeScan({
            formats: Format.ALL_FORMATS, // optional
            onBarcodeSearch: (value) => {
                alert(value);
            },
        });
    }

    var mylatitude = '';
    var mylongitude = '';
    var default_url = "https://vlpf3uqi3h.execute-api.ap-south-1.amazonaws.com/live/location";

    var location_get = false;

    function success(pos) {
        const crd = pos.coords;
        mylatitude = crd.latitude;
        mylongitude = crd.longitude;
        location_get = true;
    }

    function error(err) {
        console.warn(`ERROR(${err.code}): ${err.message}`);
    }

    function setLocation() {
        const options = {
            enableHighAccuracy: true,
            timeout: 4000,
            maximumAge: 0,
        };
        navigator.geolocation.getCurrentPosition(success, error, options);
    }


    function start() {
        window.WTN.backgroundLocation.start({
            //callback: successCallback,
            apiUrl: default_url,
            timeout: 3000,
            data: '{{$ride_id}}',
            backgroundIndicator: true,
            pauseAutomatically: false,
            distanceFilter: 10.0,
            desiredAccuracy: "best",
            activityType: "other",
        });
    }

    //alert("https://ridetrack.free.beeceptor.com");
    //apiUrl= "https://app.svktrv.in/ride/track/{{$ride_id}}";


    function stop() {
        window.WTN.backgroundLocation.stop();
    }

    function startlocation() {
        lod(true);
        start();
        window.location.href = "/driver/ride/status/{{$ride_id}}/2";
    }

    function restartLocation() {
        stop();
        start();
    }

    function stoplocation() {
        lod(true);
        stop();
        window.location.href = "/driver/ride/status/{{$ride_id}}/5";
    }
    @if($data['ride']['status'] == 2 && Session::get('mobile') != '9730946150')
    restartLocation();
    //setInterval(function() {
    //    stop();
    //    start();
    //}, 300000);
    @endif

    function sleep(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
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
                notloded: true,
                commonModalTitle: '',
                commonModalDescription: '',
                commonModalItem: '',
                commonModalType: '',
                allPassengerStatus: false
            }
        },
        mounted() {
            this.data = JSON.parse('{!!json_encode($data)!!}');
            this.notloded = false;
        },
        computed: {
            reachAtLocation() {
                return this.data.ride_passengers.every(p => p.status != 5);
            },
            allReadyForDrop() {
                return this.data.ride_passengers.every(p => p.status === 1 || p.status === 4);
            },
            marginMap() {
                return this.data.ride_passengers.map((p) => {
                    const isRelevantStatus = [1, 3, 4].includes(p.status);
                    const isPickup = this.data.ride.type === 'Pickup';
                    return isRelevantStatus && isPickup;
                });
            }
        },
        methods: {
            setId(id) {
                this.selected_id = id;
                //this.data.ride_passengers[id].status=1;
            },
            setModal(type, val) {
                this.commonModalItem = val;
                this.commonModalType = type;
                if (type == 1) {
                    this.commonModalTitle = 'कॉल करें?';
                    this.commonModalDescription = 'क्या आप पैसेंजर को कॉल करना चाहते हैं?';
                } else if (type == 2) {
                    this.commonModalTitle = 'पैसेंजर लोकेशन देखनी है?';
                    this.commonModalDescription = 'क्या आप पैसेंजर की लोकेशन Google Map पे देखना चाहते हैं?';
                } else if (type == 3) {
                    this.commonModalTitle = 'पैसेंजर लोकेशन पर पहुंच गए?';
                    this.commonModalDescription = 'क्या आप पैसेंजर की पिकअप लोकेशन पे पक्का पहुँच गए?';
                } else if (type == 4) {
                    this.commonModalTitle = 'पैसेंजर उतर रहै है?';
                    this.commonModalDescription = 'क्या आप प्यासेंजर को ड्रॉप करना चाहते हैं?';
                    this.selected_id = val;
                } else if (type == 5) {
                    this.commonModalTitle = 'पैसेंजर नहीं आया?';
                    this.commonModalDescription = 'क्या प्यासेंजर ने No Show किया है?';
                    this.selected_id = val;
                } else if (type == 6) {
                    this.commonModalTitle = 'सब पैसेंजर उतर रहै है?';
                    this.commonModalDescription = 'क्या आप सब प्यासेंजर को ड्रॉप करना चाहते हैं?';
                } else if (type == 7) {
                    this.commonModalTitle = 'Pickup लोकेशन पर पहुंच गए?';
                    this.commonModalDescription = 'क्या आप पैसेंजर की पिकअप लोकेशन पे पक्का पहुँच गए?';
                }
            },
            commonModalClick() {
                if (this.commonModalType == 1) {
                    this.call(this.commonModalItem);
                } else if (this.commonModalType == 2) {
                    window.location.assign('https://www.google.com/maps/search/?api=1&query=' + this.commonModalItem, '_system');
                } else if (this.commonModalType == 3) {
                    this.reach(this.commonModalItem);
                } else if (this.commonModalType == 4) {
                    this.out();
                } else if (this.commonModalType == 5) {
                    this.noshow();
                } else if (this.commonModalType == 6) {
                    this.outall();
                } else if (this.commonModalType == 7) {
                    this.reachall();
                }
            },
            verifyotp() {
                var otp = document.getElementById('otp').value;
                array = this.data.ride_passengers[this.selected_id];
                verified = "1";
                if (otp == '0000') {
                    otp = array.otp;
                    verified = "0";
                }
                if (array.otp != otp && array.mobile != '') {
                    this.verror = 'Invalid OTP';
                    document.getElementById('otp').value = '';
                } else {
                    document.getElementById('otp').value = '';
                    this.data.ride_passengers[this.selected_id].status = 1;
                    axios.get('/driver/ride/passenger/status/' + array.id + '/1' + verified);
                    document.getElementById('closeotp').click();
                    this.sendLocation('passenger', array.id, 1);
                }
                restartLocation();
            },
            directverify(selected_id) {
                array = this.data.ride_passengers[selected_id];
                this.data.ride_passengers[selected_id].status = 1;
                axios.get('/driver/ride/passenger/status/' + array.id + '/10');
                this.sendLocation('passenger', array.id, 1);
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
                this.sendLocation('passenger', array.id, 5);
            },
            out() {
                array = this.data.ride_passengers[this.selected_id];
                this.data.ride_passengers[this.selected_id].status = 2;
                axios.get('/driver/ride/passenger/status/' + array.id + '/2');
                document.getElementById('closeout').click();
                this.sendLocation('passenger', array.id, 2);
                this.endStatus();
            },
            outall() {
                for (let i = 0; i < this.data.ride_passengers.length; i++) {
                    this.data.ride_passengers[i].status = 2;
                }
                document.getElementById('closeout').click();
                this.sendLocation('ride', '{{$ride_id}}', 2);
                stop();
                toastbox('toast-16');
            },
            reachall() {
                for (let i = 0; i < this.data.ride_passengers.length; i++) {
                    this.data.ride_passengers[i].status = 5;
                }
                document.getElementById('closeout').click();
                this.sendLocation('ride', '{{$ride_id}}', 5);
                restartLocation();
            },
            endStatus() {
                var done = true;
                for (let i = 0; i < this.data.ride_passengers.length; i++) {
                    pstatus = this.data.ride_passengers[i].status;
                    if (pstatus == 0 || pstatus == 1 || pstatus == 5) {
                        done = false;
                    }
                }
                if (done == true) {
                    //lod(true);
                    stop();
                    axios.get('/driver/ride/status/{{$ride_id}}/5');
                    //setTimeout(() => stoplocation(), 6000);
                    toastbox('toast-16');
                } else {
                    restartLocation();
                }
                this.alldone = done;
            },
            // sendLocation(type,id,status) {
            //     setLocation();
            //     setTimeout(() => this.updateLocationApi(type,id,status,mylatitude,mylongitude), 5000);
            // },
            async sendLocation(type, id, status) {
                location_get = false;
                setLocation();
                let count = 0;
                while (location_get == false) {
                    await sleep(1000); // wait 1 second
                    if (count === 5) {
                        break;
                    }
                    count++;
                }
                this.updateLocationApi(type, id, status, mylatitude, mylongitude);
            },
            async updateLocationApi(type, id, status, lat, long) {
                await axios.get('/driver/ride/location/status/' + type + '/' + id + '/' + status + '/' + lat + '/' + long)
                    .then(response => {

                    })
                    .catch(error => {
                        console.error(error);
                    });
            },
            call(mobile) {
                axios.get('/call/' + mobile);
                toastbox('toast-15');
            }
        }
    })
</script>


@endsection