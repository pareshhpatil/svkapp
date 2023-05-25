@extends('layouts.app')
@section('content')

<div id="appCapsule" class="full-height">
    <div id="app">

        <!-- Wallet Card -->
        <div class="section wallet-card-section pt-1">
            <div class="wallet-card" style="padding: 0px 0px;border-radius: 20px;">
                <!-- Balance -->
                <div class=" text-center">


                </div>
                <div class="">
                    <img src="/assets/img/banner.png?v=5" style="max-width:100%">
                </div>
                <!-- * Balance -->
                <!-- Wallet Footer -->
                <div class=" text-center">


                </div>
                <!-- * Wallet Footer -->
            </div>
        </div>

        <div class="section">
            <div class="row mt-2">
                <template>
                    <div class="col-6">
                        <a href="/my-rides">
                            <div class="stat-box">
                                <div class="title text-center">Total Rides </div>
                                <div class="value text-success text-center" v-html="data.total_ride"></div>
                            </div>
                        </a>
                    </div>
                    <div class="col-6">
                        <div class="stat-box">
                            <div class="title text-center">Completed </div>
                            <div class="value text-warning text-center" v-html="data.completed_ride"></div>
                        </div>
                    </div>
                </template>
            </div>

        </div>
        <div v-if="data.upcoming" class="section">
            <div class="row mt-2">
                <div class="section-heading padding mb-0">
                    <h3>Upcoming Ride</h3>
                    <a href="/my-rides" class="link">View All</a>
                </div>
                <div class="transactions">
                    <a :href="data.upcoming.link" class="item">
                        <div class="detail">
                            <img src="/assets/img/driver.png" alt="img" class="image-block imaged w48">
                            <div>
                                <strong v-html="data.upcoming.pickup_time"></strong>
                                <p><span v-html="data.upcoming.pickup_location"></span> - <span v-html="data.upcoming.drop_location"></span></p>
                            </div>
                        </div>
                        <div class="right">
                            <ion-icon name="chevron-forward-outline" role="img" class="md hydrated" aria-label="chevron forward outline"></ion-icon>
                        </div>
                    </a>

                    <!-- * item -->
                </div>
            </div>
        </div>
        <!-- * Stats -->

        <!-- Transactions -->
        <div v-if="data.blogs.length" class="section full mt-4 mb-3">
            <div class="section-heading padding">
                <h4 class="title">Blog posts</h4>
                <a href="/blogs" class="link">View All</a>
            </div>

            <!-- carousel multiple -->
            <div class="carousel-multiple splide splide--loop splide--ltr splide--draggable is-active" id="splide03" style="visibility: visible;">
                <div class="splide__track" id="splide03-track" style="padding-left: 16px; padding-right: 16px;">
                    <ul class="splide__list" id="splide03-list" style="transform: translateX(-708px);">
                        <li v-for="item in data.blogs" class="splide__slide splide__slide--clone" aria-hidden="true" tabindex="-1" style="margin-right: 16px; width: 171px;">
                            <a :href="item.link">
                                <div class="blog-card">
                                    <img :src="item.img" alt="image" class="imaged w-100">
                                    <div class="text">
                                        <h4 v-html="item.title" class="title"></h4>
                                    </div>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- * carousel multiple -->

        </div>


    </div>
</div>

@if(!empty($live_ride))
@php
$photo=($live_ride['photo']!='')? $live_ride['photo'] : '/assets/img/driver.png';
@endphp
<div class="modal fade action-sheet show" id="actionSheet" tabindex="-1" role="dialog" aria-modal="true" style="display: block;top: inherit;">
    <div class="modal-dialog" role="document" style="bottom: 50px;">
        <div class="modal-content">

            <div class="modal-body">
                <div class="transactions">
                    <!-- item -->
                    <a href="app-transaction-detail.html" class="item">
                        <div class="detail">
                            <img src="{{$photo}}" alt="img" class="image-block imaged w48 img-circle">
                            <div>
                                <strong>{{$live_ride['driver_name']}}</strong>
                                <strong class="text-primary">OTP: {{$live_ride['otp']}} </strong>
                                <p>{{$live_ride['number']}}</p>
                            </div>
                        </div>
                        <div class="right">
                            <div onclick="window.location'tel:{{$live_ride['mobile']}}'" class="text-danger"> <ion-icon name="call-outline" style="font-size: 25px;" role="img" class="md hydrated" aria-label="call outline"></ion-icon></div>
                        </div>
                    </a>
                </div>
                <div class=" ">
                    <div class="wallet-card" style="box-shadow: none;padding: 0;padding-bottom: 10px;">
                        <!-- Balance -->
                        <!-- Wallet Footer -->
                        <div class="wallet-footer" style="padding-top: 10px;">
                            <div  class="item mb-1">
                                <a href="{{$live_ride['link']}}">
                                    <div class="icon-wrapper bg-primary">
                                        <ion-icon name="eye-outline"></ion-icon>
                                    </div>
                                    <strong>Details</strong>
                                </a>
                            </div>
                            <div  class="item mb-1">
                                <a href="{{$live_ride['link']}}/track">
                                    <div class="icon-wrapper bg-success">
                                        <ion-icon name="navigate-outline" role="img" class="md hydrated" aria-label="navigate outline"></ion-icon>
                                    </div>
                                    <strong>Track</strong>
                                </a>
                            </div>

                            <div class="item">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#helpmodal">
                                    <div class="icon-wrapper bg-warning">
                                        <ion-icon name="chatbubble-ellipses-outline" role="img" class="md hydrated" aria-label="chatbubble ellipses outline"></ion-icon>
                                    </div>
                                    <strong>Help</strong>
                                </a>
                            </div>

                            <div class="item">
                                <a href="whatsapp://send?text=Hey, Please track my ride {{env('APP_URL')}}{{$live_ride['link']}}" data-action="share/whatsapp/share" id="shareBtn">
                                    <div class="icon-wrapper bg-info">
                                        <ion-icon name="logo-whatsapp" role="img" class="md hydrated" aria-label="logo whatsapp"></ion-icon>
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


<div class="modal fade dialogbox" id="helpmodal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Help</h5>
            </div>
            <form action="/passenger/help" method="post">
                @csrf
                <div class="modal-body text-start mb-2">
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="text1">Enter Message</label>
                            <textarea rows="2" type="text" name="message" class="form-control" placeholder="Enter message" maxlength="250"></textarea>
                            <i class="clear-input">
                                <ion-icon name="close-circle"></ion-icon>
                            </i>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="btn-inline">
                        <input type="hidden" value="{{$live_ride['passenger_id']}}" name="ride_passenger_id">
                        <input type="hidden" value="{{$live_ride['ride_id']}}" name="ride_id">
                        <button type="button" class="btn btn-text-secondary" data-bs-dismiss="modal">CLOSE</button>
                        <button type="submit" class="btn btn-text-primary">SEND</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endif



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