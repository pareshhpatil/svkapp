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
</style>
<div id="appCapsule" class="full-height">

    <div id="app" class="section ">
        <div v-if="!data.driver.name">
            <div class="appHeader bg-warning text-light" style="top:50px;margin-bottom:50px">
                <div class="pageTitle">Cab not assigned yet</div>
            </div>
            <div class="mt-2">
                &nbsp;
            </div>
        </div>
        <div v-if="data.ride_passenger.status==3">
            <div class="appHeader bg-danger text-light" style="top:50px;margin-bottom:50px">
                <div class="pageTitle">Ride has been cancelled</div>
            </div>
            <div class="mt-2">
                &nbsp;
            </div>
        </div>
        <div v-if="data.driver.name" class="listed-detail ">
            <div class="row" style="border-bottom: 1px solid lightgrey;">
                <div class="col text-center">
                    <img v-if="data.driver.photo" :src="data.driver.photo" class="mt-3 img-circle" style="max-height: 130px;">
                    <img v-if="!data.driver.photo" class="mt-3 img-circle" style="max-height: 130px;" src="/assets/img/driver.png?v-1">
                </div>
                <div class="col">
                    <div class="">
                        <ul class="listview flush transparent no-line image-listview detailed-list mt-1 mb-1">
                            <!-- item -->
                            <li style="padding:0px;">
                                <div class="item" style="padding: 0;">
                                    <div class="icon-box text-black">
                                        <ion-icon name="car-sport-outline"></ion-icon>
                                    </div>
                                    <div class="in">
                                        <div>
                                            <div class="text-small text-secondary">Cab Number</div>

                                            <strong v-html="data.vehicle.number"></strong>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li style="padding:0px;">
                                <div class="item" style="padding: 0;">
                                    <div class="icon-box text-black">
                                        <ion-icon name="person-outline"></ion-icon>
                                    </div>
                                    <div class="in">
                                        <div>
                                            <div class="text-small text-secondary">Name</div>

                                            <strong v-html="data.driver.name"></strong>
                                        </div>

                                    </div>
                                </div>
                            </li>
                            @isset($data['driver']['mobile'])
                            <li style="padding:0px;">
                                <div class="item" style="padding: 0;">
                                    <div onclick="window.open('tel:{{$data['driver']['mobile']}}');" class="icon-box text-black">
                                        <ion-icon name="call-outline"></ion-icon>
                                    </div>
                                    <div class="in">
                                        <div>
                                            <div class="text-small text-secondary">Mobile</div>
                                            <strong onclick="window.open('tel:{{$data['driver']['mobile']}}');" v-html="data.driver.mobile"></strong>
                                        </div>

                                    </div>
                                </div>
                            </li>
                            @endisset
                            <!-- * item -->
                            <!-- item -->

                            <!-- * item -->
                        </ul>
                    </div>

                </div>
            </div>


        </div>
        <div class="">
            <div class="splash-page  mt-2">

                <div class="transfer-verification">
                    <div class="transfer-amount">
                        <span class="caption">Pickup Time</span>
                        <h5 v-html="data.ride_passenger.pickup_time"></h5>
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
                    <h2 class="mb-2">OTP <span v-html="data.ride_passenger.otp"></span></h2>
                </div>

            </div>
        </div>

        <div class=" ">
            <div class="wallet-card" style="box-shadow: none;padding: 0;padding-bottom: 10px;">
                <!-- Balance -->
                <!-- Wallet Footer -->
                <div class="wallet-footer">
                    <div v-if="data.ride.status==1" class="item mb-1">
                        <a href="{{$data['link']}}/track" >
                            <div class="icon-wrapper bg-success">
                                <ion-icon name="location-outline"></ion-icon>
                            </div>
                            <strong>Track</strong>
                        </a>
                    </div>
                    <div v-if="data.ride.status==0" class="item">
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
        <div class="mt-1">Route</div>
        <div class="timeline timed ms-1 me-2">

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
            <div v-for="item in data.ride_passengers" class="item">
                <span v-if="data.ride.type=='Drop'" v-html="item.drop_time" class="time"></span>
                <span v-if="data.ride.type=='Pickup'" v-html="item.pickup_time" class="time"></span>
                <div v-if="data.ride.type=='Drop'" class="dot bg-primary bg-red"></div>
                <div v-if="data.ride.type=='Pickup'" class="dot bg-info "></div>
                <div class="content">
                    <h4 class="title"><span v-html="item.name"></span>
                        <div class="text-end" style="right: 10px;float: right;">
                            <img v-if="item.icon==''" src="/assets/img/sample/avatar/avatar1.jpg" alt="avatar" class="imaged w24 rounded right">
                            <img v-if="item.icon!=''" :src="item.icon" alt="avatar" class="imaged w24 rounded right">
                        </div>
                    </h4>
                    <div v-html="item.location" class="text"></div>
                </div>
            </div>
            <div class="item" v-if="data.ride.type=='Pickup'">
                <span class="time" v-html="data.ride.end_time"></span>
                <div class="dot bg-primary bg-red"></div>
                <div class="content">
                    <h4 class="title">Office
                        <div class="text-end" style="right: 10px;float: right;">
                            <img src="/assets/img/office.png" alt="avatar" class="imaged w24 rounded right">
                        </div>
                    </h4>
                    <div class="text" v-html="data.project.location"></div>
                </div>
            </div>


        </div>
        <div class="modal fade dialogbox" id="cancelride" data-bs-backdrop="static" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Cancel Ride</h5>
                    </div>
                    <form action="/passenger/ride/cancel" method="post">
                        @csrf
                        <div class="modal-body text-start mb-2">
                            <div class="form-group basic">
                                <div class="input-wrapper">
                                    <label class="label" for="text1">Enter Reason</label>
                                    <input type="text" name="message" class="form-control" placeholder="Enter cancel reason" maxlength="100">
                                    <i class="clear-input">
                                        <ion-icon name="close-circle"></ion-icon>
                                    </i>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="btn-inline">
                                <input type="hidden" :value="data.ride_passenger.id" name="ride_passenger_id">
                                <input type="hidden" :value="data.ride_passenger.ride_id" name="ride_id">
                                <button type="button" class="btn btn-text-secondary" data-bs-dismiss="modal">CLOSE</button>
                                <button type="submit" class="btn btn-text-primary">CONFIRM</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        
        <div class="modal fade modalbox  dialogbox" id="sosmodel" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Emergency SOS</h5>
                    </div>
                    <form action="/passenger/sos" method="post">
                        @csrf
                        <div class="modal-body text-start mb-2 mt-1">
                            <div class="">
                                <div class="row">
                                    <div class="col">
                                        <div class="custom-control custom-checkbox image-checkbox">
                                            <input type="checkbox" name="emergency[]" value="Disaster" class="custom-control-input form-check-input" id="em1">
                                            <label class="custom-control-label" for="em1">
                                                <img src="/assets/img/sos/1.png" alt="#" class="img-fluid">
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="custom-control custom-checkbox image-checkbox">
                                            <input type="checkbox" name="emergency[]" value="Accident" class="custom-control-input form-check-input" id="em2">
                                            <label class="custom-control-label" for="em2">
                                                <img src="/assets/img/sos/2.png" alt="#" class="img-fluid">
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="custom-control custom-checkbox image-checkbox">
                                            <input type="checkbox" name="emergency[]" value="Fire" class="custom-control-input form-check-input" id="em3">
                                            <label class="custom-control-label" for="em3">
                                                <img src="/assets/img/sos/3.png" alt="#" class="img-fluid">
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-1">
                                    <div class="col">
                                        <div class="custom-control custom-checkbox image-checkbox">
                                            <input type="checkbox" name="emergency[]" value="Alert" class="custom-control-input form-check-input" id="em4">
                                            <label class="custom-control-label" for="em4">
                                                <img src="/assets/img/sos/4.png" alt="#" class="img-fluid">
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="custom-control custom-checkbox image-checkbox">
                                            <input type="checkbox" name="emergency[]" value="Crime" class="custom-control-input form-check-input" id="em5">
                                            <label class="custom-control-label" for="em5">
                                                <img src="/assets/img/sos/5.png" alt="#" class="img-fluid">
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="custom-control custom-checkbox image-checkbox">
                                            <input type="checkbox" name="emergency[]" value="Medical" class="custom-control-input form-check-input" id="em6">
                                            <label class="custom-control-label" for="em6">
                                                <img src="/assets/img/sos/6.png" alt="#" class="img-fluid">
                                            </label>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="form-group basic">
                                <div class="input-wrapper">
                                    <label class="label" for="text1">Message</label>
                                    <input type="text" class="form-control" name="message" placeholder="Enter emergency details" maxlength="100">
                                    <i class="clear-input">
                                        <ion-icon name="close-circle"></ion-icon>
                                    </i>
                                </div>
                            </div>
                            <div class="row">

                                <div class="listview-title mt-1">Notification</div>
                                <ul class="listview image-listview text">
                                    <li>
                                        <div class="item">
                                            <div class="in">
                                                <div>
                                                    Notify Supervisor
                                                </div>
                                                <div class="form-check form-switch ms-2">
                                                    <input name="notify_supervisor" value="1" readonly class="form-check-input" checked type="checkbox" id="SwitchCheckDefault5">
                                                    <label class="form-check-label" for="SwitchCheckDefault5"></label>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="item">
                                            <div class="in">
                                                <div>
                                                    Notify your emergency contact
                                                </div>
                                                <div class="form-check form-switch ms-2">
                                                    <input name="notify_emergency_contact" value="1" class="form-check-input" type="checkbox" id="SwitchCheckDefault2">
                                                    <label class="form-check-label" for="SwitchCheckDefault2"></label>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="btn-inline">
                                <input type="hidden" :value="data.ride_passenger.id" name="ride_passenger_id">
                                <input type="hidden" :value="data.ride_passenger.ride_id" name="ride_id">
                                <button type="button" class="btn btn-text-secondary" data-bs-dismiss="modal">CLOSE</button>
                                <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">SEND</button>
                            </div>
                        </div>
                    </form>
                </div>
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
    })
</script>


@endsection