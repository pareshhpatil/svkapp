@extends('layouts.app')
@section('content')
<style>
    .timeline:before {
        bottom: 60px;
        top: 28px;
    }
    body.dark-mode .text-black {
  color: #fff;
  background: #20162a;
} 
</style>
<div id="appCapsule" class="full-height">

    <div id="app" class="section ">


        <div class="listed-detail ">
            <div class="row" style="border-bottom: 1px solid lightgrey;">
                <div class="col ">
                    <img class="mt-3" src="/assets/img/sample/avatar/avatar1.jpg">
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

                                            <strong>MH 02 9545</strong>
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

                                            <strong>Paresh Patil</strong>
                                        </div>

                                    </div>
                                </div>
                            </li>
                            <li style="padding:0px;">
                                <div class="item" style="padding: 0;">
                                    <div class="icon-box text-black">
                                        <ion-icon name="call-outline"></ion-icon>
                                    </div>
                                    <div class="in">
                                        <div>
                                            <div class="text-small text-secondary">Mobile</div>

                                            <strong>9730946150</strong>
                                        </div>

                                    </div>
                                </div>
                            </li>
                            <!-- * item -->
                            <!-- item -->

                            <!-- * item -->
                        </ul>
                    </div>

                </div>
            </div>

            <h4 class="text-center mt-1"><span class="badge badge-success btn btn-success"> OTP 5484</span></h4>

        </div>


        <ul class="listview flush transparent simple-listview no-space" style="padding: 10px;">
            <li>
                <strong><ion-icon name="location-outline"></ion-icon> Pickup
                </strong>
                <span>Office 09:30 AM</span>
            </li>
            <li>
                <strong><ion-icon name="home-outline"></ion-icon> Drop
                </strong>
                <span>Home</span>
            </li>
        </ul>
        <div class=" ">
            <div class="wallet-card" style="box-shadow: none;padding: 0;">
                <!-- Balance -->
                <!-- Wallet Footer -->
                <div class="wallet-footer">
                    <div class="item mb-1">
                        <a href="#" data-bs-toggle="modal" data-bs-target="#withdrawActionSheet">
                            <div class="icon-wrapper bg-success">
                                <ion-icon name="location-outline"></ion-icon>
                            </div>
                            <strong>Track</strong>
                        </a>
                    </div>
                    <div class="item">
                        <a href="#" data-bs-toggle="modal" data-bs-target="#actionSheetForm">
                            <div class="icon-wrapper bg-primary bg-red">
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
                        <a href="#" data-bs-toggle="modal" data-bs-target="#exchangeActionSheet">
                            <div class="icon-wrapper bg-primary">
                                <ion-icon name="accessibility-outline"></ion-icon>
                            </div>
                            <strong>SOS</strong>
                        </a>
                    </div>
                    <div class="item">
                        <a href="#" id="shareBtn">
                            <div class="icon-wrapper bg-info">
                                <ion-icon name="share-social-outline"></ion-icon>
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

            <div class="item">
                <span class="time">02:40 PM</span>
                <div class="dot bg-info"></div>
                <div class="content">
                    <h4 class="title">Office
                        <div class="text-end" style="right: 10px;float: right;">
                            <div class="icon-wrapper bg-dark w24 rounded">
                                <ion-icon name="business-outline" style="color: rgb(232, 24, 78);background: #ffffff; font-size: 25px;border-radius: 25px;"></ion-icon>
                            </div>

                        </div>

                    </h4>

                    <div class="text">BKC</div>
                </div>
            </div>
            <div class="item">
                <span class="time">09:00 PM</span>
                <div class="dot bg-primary bg-red"></div>
                <div class="content">
                    <h4 class="title">Paresh Patil
                        <div class="text-end" style="right: 10px;float: right;"><img src="/assets/img/sample/avatar/avatar1.jpg" alt="avatar" class="imaged w24 rounded right"></div>

                    </h4>
                    <div class="text">Pune</div>
                </div>
            </div>
            <div class="item">
                <span class="time">02:40 AM</span>
                <div class="dot bg-primary bg-red"></div>
                <div class="content">
                    <h4 class="title">Mahesh Patil
                        <div class="text-end" style="right: 10px;float: right;"><img src="/assets/img/sample/avatar/avatar1.jpg" alt="avatar" class="imaged w24 rounded right"></div>

                    </h4>
                    <div class="text">Kharghar9</div>
                </div>
            </div>

        </div>
        <div class="modal fade action-sheet" id="actionSheetForm" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Cancel Ride</h5>
                    </div>
                    <div class="modal-body">
                        <div class="action-sheet-content">

                            <form>
                                <div class="form-group boxed">
                                    <div class="input-wrapper">
                                        <label class="label">Reason</label>
                                        <textarea type="text" class="form-control" placeholder="Enter a reason"></textarea>
                                        <i class="clear-input">
                                            <ion-icon name="close-circle"></ion-icon>
                                        </i>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <button type="button" class="btn btn-primary  btn-lg">Confirm</button>
                                    <button type="button" class="btn btn-default  btn-lg" data-bs-dismiss="modal">Close</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade action-sheet" id="helpmodal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Help</h5>
                    </div>
                    <div class="modal-body">
                        <div class="action-sheet-content">
                            <form>
                                <div class="form-group boxed">
                                    <div class="input-wrapper">
                                        <label class="label">Message</label>
                                        <textarea type="text" class="form-control" placeholder="Enter a message"></textarea>
                                        <i class="clear-input">
                                            <ion-icon name="close-circle"></ion-icon>
                                        </i>
                                    </div>
                                </div>
                                <div class="form-group boxed">
                                    <button type="button" class="btn btn-primary  btn-lg">Submit</button>
                                    <button type="button" class="btn btn-default  btn-lg" data-bs-dismiss="modal">Close</button>
                                </div>
                            </form>

                        </div>
                    </div>
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

<script>
    document.querySelector('#shareBtn')
        .addEventListener('click', event => {

            // Fallback, Tries to use API only
            // if navigator.share function is
            // available
            if (navigator.share) {
                navigator.share({

                    // Title that occurs over
                    // web share dialog
                    title: 'GeeksForGeeks',

                    // URL to share
                    url: 'https://geeksforgeeks.org'
                }).then(() => {
                    console.log('Thanks for sharing!');
                }).catch(err => {

                    // Handle errors, if occurred
                    console.log(
                        "Error while using Web share API:");
                    console.log(err);
                });
            } else {

                // Alerts user if API not available
                alert("Browser doesn't support this API !");
            }
        })
</script>
@endsection