@extends('layouts.app')
@section('content')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

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
        <div id="loader" v-if="notloded">
            <img src="/assets/img/animation1.gif" alt="icon" class="loading-icon">
        </div>
        <div @if($title!='Assign cab' ) v-if="!data.driver.name" @endif>
            @if(Session::get('user_type')!=3)
            <div class="appHeader bg-warning text-light" style="top:50px;margin-bottom:50px">
                <div class="pageTitle">Cab not assigned yet</div>
            </div>
            <div class="mt-2">
                &nbsp;
            </div>
            @else
            @if($title=='Assign cab')
            <div class="section mt-2 mb-2">
                <div class="section-title">Assign Cab</div>
                <div class="card">
                    <div class="card-body">
                        <form onsubmit="lod(true);" action="/admin/assign/cab" method="post">
                            @csrf
                            <div class="form-group boxed">
                                <div class="input-wrapper">
                                    <label class="label" for="text4b">Select Driver</label>
                                    <select name="driver_id" class="form-control custom-select select2">
                                        <option value="">Select..</option>
                                        @if(!empty($driver_list))
                                        @foreach($driver_list as $v)
                                        <option value="{{$v->id}}">{{$v->name}} - {{$v->location}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="form-group boxed">
                                <div class="input-wrapper">
                                    <label class="label" for="email4b">Select Vehicle</label>
                                    <select name="vehicle_id" class="form-control custom-select select2">
                                        <option value="">Select..</option>
                                        @if(!empty($vehicle_list))
                                        @foreach($vehicle_list as $v)
                                        <option value="{{$v->vehicle_id}}">{{$v->number}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group boxed">
                                <input type="hidden" name="ride_id" value="{{$ride_id}}">
                                <button type="submit" class="btn btn-primary btn-block ">Submit</button>
                            </div>


                        </form>

                    </div>
                </div>
            </div>
            @endif
            @endif
        </div>

        <div v-if="data.ride_passenger.status==3">
            <div class="appHeader bg-danger text-light" style="top:50px;margin-bottom:50px">
                <div class="pageTitle" v-html="rcmsg"></div>
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
                                    <div onclick="window.location.assign('tel:{{$data['driver']['mobile']}}', '_system');" class="icon-box text-black">
                                        <ion-icon name="call-outline"></ion-icon>
                                    </div>
                                    <div class="in">
                                        <div>
                                            <div class="text-small text-secondary">Mobile</div>
                                            <strong onclick="window.location.assign('tel:{{$data['driver']['mobile']}}', '_system');" v-html="data.driver.mobile"></strong>
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
                    <h2 v-if="data.ride_passenger.otp && data.ride_passenger.status!=2" class="mb-2">OTP <span v-html="data.ride_passenger.otp"></span></h2>
                </div>

            </div>
        </div>

        <div v-if="data.ride_passenger.status!=3" class=" ">
            <div class="wallet-card" style="box-shadow: none;padding: 0;padding-bottom: 10px;">
                <!-- Balance -->
                <!-- Wallet Footer -->
                <div v-if="data.ride_passenger.status==2" class="wallet-footer mt-1" style="    padding: 10px;border: none;">
                    <div class="full-star-ratings jq-ry-container" data-rateyo-full-star="true">
                        <div class="jq-ry-group-wrapper">
                            <div class="jq-ry-rated-group jq-ry-group" style="width: 100%;">
                                <div class="row" style="padding: 10px;">
                                    <p>Review</p>
                                    <div v-for="count in 5" class="col">
                                        <svg v-on:click="rating(count)" v-if="count<=data.ride_passenger.rating" version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 12.705 512 486.59" xml:space="preserve" width="30px" height="30px" fill="#e8481e">
                                            <polygon points="256.814,12.705 317.205,198.566 512.631,198.566 354.529,313.435 414.918,499.295 256.814,384.427 98.713,499.295 159.102,313.435 1,198.566 196.426,198.566 ">
                                            </polygon>
                                        </svg>
                                        <svg v-on:click="rating(count)" v-if="count>data.ride_passenger.rating" version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 12.705 512 486.59" xml:space="preserve" width="30px" height="30px" fill="#dbdade">
                                            <polygon points="256.814,12.705 317.205,198.566 512.631,198.566 354.529,313.435 414.918,499.295 256.814,384.427 98.713,499.295 159.102,313.435 1,198.566 196.426,198.566 ">
                                            </polygon>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-if="data.ride_passenger.status!=2" class="wallet-footer">
                    <div v-if="data.ride.status==2" class="item mb-1">
                        <a href="{{$data['link']}}/track">
                            <div class="icon-wrapper bg-success">
                                <ion-icon name="location-outline"></ion-icon>
                            </div>
                            <strong>Track</strong>
                        </a>
                    </div>
                    @if(Session::get('user_type')!=3)
                    <div v-if="data.ride.status<2" class="item">
                        <a href="#" data-bs-toggle="modal" data-bs-target="#cancelride">
                            <div class="icon-wrapper bg-primary bg-red" style="background: #e8481e !important;">
                                <ion-icon name="close-circle-outline"></ion-icon>
                            </div>
                            <strong>Cancel</strong>
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
                        <a href="#" data-bs-toggle="modal" data-bs-target="#sosmodel">
                            <div class="icon-wrapper bg-primary bg-red" style="background: #e8481e !important;">
                                <ion-icon name="accessibility-outline"></ion-icon>
                            </div>
                            <strong>SOS</strong>
                        </a>
                    </div>

                    @endif
                    <div class="item">
                        <a href="whatsapp://send?text=Hey, Please track my ride {{$data['link']}}" data-action="share/whatsapp/share" id="shareBtn">
                            <div class="icon-wrapper bg-info">
                                <ion-icon name="logo-whatsapp"></ion-icon>
                            </div>
                            <strong>Share</strong>
                        </a>
                    </div>

                    @if(Session::get('user_type')==3)
                    <div class="item">
                        <a href="/admin/ride/assign/{{$enc_link}}">
                            <div class="icon-wrapper bg-primary bg-red">
                                <ion-icon name="create-outline"></ion-icon>
                            </div>
                            <strong>Update</strong>
                        </a>
                    </div>
                    @endif

                </div>
                <!-- * Wallet Footer -->
            </div>
        </div>
        <div class="mt-1">Route

            @if($title=='Assign cab')
            <a href="#" data-bs-toggle="modal" data-bs-target="#addpassenger" class="btn btn-info btn-sm pull-right">
                <ion-icon name="person-add-outline"></ion-icon>
                Add
            </a>
            @endif
        </div>

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
                @if($title=='Assign cab')
                <span class="time">
                    <a href="#" data-bs-toggle="modal" v-on:click="remove_id=item.id" data-bs-target="#removepassenger" class="btn btn-primary btn-sm">
                        <ion-icon name="close-circle-outline"></ion-icon>
                    </a>
                </span>
                @endif
                <div class="content">
                    <h4 class="title"><span v-html="item.name"></span>
                        <div class="text-end" style="right: 10px;float: right;">
                            <img v-if="!item.icon && item.gender=='Male'" src="/assets/img/map-male.png" alt="avatar" class="imaged w48 rounded right">
                            <img v-if="!item.icon && item.gender=='Female'" src="/assets/img/map-female.png" alt="avatar" class="imaged w48 rounded right">
                            <img v-if="item.icon" :src="item.icon" alt="avatar" class="imaged w48 rounded right">

                        </div>
                    </h4>
                    <div v-html="item.mobile" class="text text-danger"></div>
                    <div v-html="item.location" class="text"></div>
                    @if(Session::get('user_type')==3)
                    <div v-html="'Otp: '+item.otp" class="text text-info"></div>

                    @endif
                </div>
            </div>
            <div class="item" v-if="data.ride.type=='Pickup'">
                <span class="time" v-html="data.ride.end_time"></span>
                <div class="dot bg-primary bg-red"></div>
                <div class="content">
                    <h4 class="title">Office
                        <div class="text-end" style="right: 10px;float: right;">
                            <img src="/assets/img/office.png" alt="avatar" class="imaged w48 rounded right">
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
                    <form onsubmit="lod(true);" action="/passenger/ride/cancel" method="post">
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
        <div class="modal fade dialogbox" id="helpmodal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Chat</h5>
                    </div>
                    <form onsubmit="lod(true);" action="/passenger/help" method="post">
                        @csrf
                        <div class="modal-body text-start mb-2">
                            <div class="">
                                <div class="">

                                    <a href="/chat/create/5/{{$data['ride']['id']}}/1/{{$data['ride_passenger']['passenger_id']}}/0">
                                        <div class="alert alert-imaged alert-outline-primary alert-dismissible fade show mb-2" role="alert">
                                            <div class="icon-wrap">
                                                <ion-icon name="person-circle-outline"></ion-icon>
                                            </div>
                                            <div>
                                                <strong>Supervisor</strong>
                                            </div>
                                        </div>
                                    </a>
                                    <a href="/chat/create/5/{{$data['ride']['id']}}/2/{{$data['ride_passenger']['passenger_id']}}/0">
                                        <div class="alert alert-imaged alert-outline-primary alert-dismissible fade show mb-2" role="alert">
                                            <div class="icon-wrap">
                                                <ion-icon name="car-outline"></ion-icon>
                                            </div>
                                            <div>
                                                <strong>Driver</strong>
                                            </div>
                                        </div>
                                    </a>
                                    <a href="/chat/create/5/{{$data['ride']['id']}}/3/{{$data['ride_passenger']['passenger_id']}}/0">
                                        <div class="alert alert-imaged alert-outline-primary alert-dismissible fade show mb-2" role="alert">
                                            <div class="icon-wrap">
                                                <ion-icon name="people-outline"></ion-icon>
                                            </div>
                                            <div>
                                                <strong>Co Passengers Group</strong>
                                            </div>
                                        </div>
                                    </a>

                                    <a :href="'/chat/create/5/{{$data['ride']['id']}}/4/{{$data['ride_passenger']['passenger_id']}}/'+item.passenger_id" v-if="item.id!=data.ride_passenger.id" v-for="item in data.ride_passengers">
                                        <div class="alert alert-imaged alert-outline-primary alert-dismissible fade show mb-2" role="alert">
                                            <div class="icon-wrap">
                                                <ion-icon name="person-outline"></ion-icon>
                                            </div>

                                            <div>
                                                <strong v-html="item.name"></strong>
                                            </div>

                                        </div>
                                    </a>

                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="btn-inline">
                                <input type="hidden" :value="data.ride_passenger.id" name="ride_passenger_id">
                                <input type="hidden" :value="data.ride_passenger.ride_id" name="ride_id">
                                <button type="button" class="btn btn-text-secondary" data-bs-dismiss="modal">CLOSE</button>
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
                    <form onsubmit="lod(true);" action="/passenger/sos" method="post">
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

        <div id="toast-11" class="toast-box toast-center">
            <div class="in">
                <ion-icon name="checkmark-circle" class="text-success"></ion-icon>
                <div class="text">
                    Thank you for Review
                </div>
            </div>
            <button type="button" onclick="closeT();" class="btn btn-sm  btn-text-light bg-red">CLOSE</button>
        </div>





        @if($title=='Assign cab')
        <div class="modal fade dialogbox" id="removepassenger" data-bs-backdrop="static" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Remove Passenger</h5>
                    </div>
                    <div class="modal-body">
                        Are you sure about that?
                    </div>
                    <div class="modal-footer">
                        <div class="btn-inline">
                            <a href="#" class="btn btn-text-danger" data-bs-dismiss="modal">
                                <ion-icon name="close-outline"></ion-icon>
                                CANCEL
                            </a>
                            <a href="#" class="btn btn-text-primary" v-on:click="removePassenger" data-bs-dismiss="modal">
                                <ion-icon name="checkmark-outline"></ion-icon>
                                Confirm
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade dialogbox" id="addpassenger" data-bs-backdrop="static" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Passenger</h5>
                    </div>
                    <div class="modal-body">
                        <form class="search-form">
                            <div class="form-group searchbox mb-1">
                                <input type="time" v-model="start_time" class="form-control" placeholder="Time">
                                <i class="input-icon icon ion-ios-clock-outline"><ion-icon name="input-icon alarm-outline"></ion-icon></i>
                            </div>
                            <div class="form-group searchbox">
                                <input type="text" v-model="search" class="form-control" placeholder="Search...">
                                <i class="input-icon icon ion-ios-search"></i>
                            </div>
                        </form>

                        <div class="extra-header-active">
                            <div class="mt-2 mb-2">
                                <div class="card" v-if="passenger_id>0">
                                    <ul class="listview media transparent flush">
                                        <li>
                                            <a href="#" v-on:click="passenger_id=0" class="item text-primary">
                                                <div class="">
                                                    <span class="text-primary" v-html="emp_title">
                                                    </span> &nbsp;&nbsp;
                                                    <ion-icon class="pull-right" name="close-outline"></ion-icon>
                                                </div>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="card" v-if="passenger_id==0" style="overflow-y: auto;">
                                    <ul class="listview image-listview media transparent flush">
                                        <li v-for="emp in filteredAndSorted">
                                            <a href="#" v-on:click="selectPassenger(emp);" class="item">
                                                <div class="">
                                                    <div v-html="emp.title">
                                                    </div>

                                                </div>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="btn-inline">
                            <a href="#" class="btn btn-text-danger" data-bs-dismiss="modal">
                                <ion-icon name="close-outline"></ion-icon>
                                CANCEL
                            </a>
                            <a href="#" v-if="passenger_id>0 && start_time!=''" v-on:click="addEmployee()" class="btn btn-text-primary" data-bs-dismiss="modal">
                                <ion-icon name="checkmark-outline"></ion-icon>
                                Confirm
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @endif



    </div>

</div>
@endsection

@section('footer')
<script src="https://admin.ridetrack.in/assets/vendor/libs/jquery/jquery.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


<script>
    function closeT() {
        document.getElementById('toast-11').classList.remove("show");
    }
    new Vue({
        el: '#app',
        data() {
            return {
                data: [],
                rcmsg: '',
                search: '',
                notloded: true,
                passenger_id: 0,
                emp_title: '',
                start_time: '',
                remove_id: 0,
                filter_passengers: [],
                passengers: []
            }
        },

        mounted() {
            this.data = JSON.parse('{!!json_encode($data)!!}');
            @if($title == 'Assign cab')
            this.passengers = JSON.parse('{!!json_encode($passenger_list)!!}');
            @endif
            this.rcmsg = 'Ride has been cancelled';
            this.start_time = this.data.ride_start_time;
            this.notloded = false;
        },
        computed: {
            filteredAndSorted() {
                // function to compare names
                function compare(a, b) {
                    if (a.title < b.title) return -1;
                    if (a.title > b.title) return 1;
                    return 0;
                }

                return this.passengers.filter(emp => {
                    return emp.title.toLowerCase().includes(this.search.toLowerCase())
                }).sort(compare)
            }
        },
        methods: {
            rating(rating) {
                this.data.ride_passenger.rating = rating;
                id = this.data.ride_passenger.id;
                axios.get('/passenger/ride/rating/' + id + '/' + rating);
                toastbox('toast-11');
            },
            selectPassenger(emp) {
                this.passenger_id = emp.id;
                this.emp_title = emp.title;
            },
            async addEmployee() {
                this.notloded = true;
                let res = await axios.get('/ride/passenger/add/' + this.data.ride.id + '/' + this.passenger_id + '/' + this.start_time);
                location.reload();
            },
            async removePassenger() {
                this.notloded = true;
                let res = await axios.get('/ride/passenger/remove/' + this.remove_id);
                location.reload();
                // this.data.ride_passengers = JSON.parse(res);
                // this.remove_id = 0;
            }
        }
    })


    $(document).ready(function() {
        $('.select2').select2();
    });
</script>


@endsection