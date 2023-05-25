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
    <div id="notification-1" class="notification-box show">
        <div class="notification-dialog android-style" style="padding: 10px 16px 10px 16px;">
            <div class="notification-header">
                <div class="in">
                    <strong class="text-primary">Live Ride</strong>
                    <span>{{$live_ride['only_time']}}</span>
                </div>
               
            </div>
            <div class="notification-content" style="margin-top: 5px;">
            <div class="transactions" style="width: 100%;">
                    <a href="{{$live_ride['link']}}" class="item">
                        <div class="detail">
                            <img src="{{$photo}}" alt="img" class="image-block imaged w48">
                            <div>
                                <strong>{{$live_ride['driver_name']}}</strong>
                                <p><span >{{$live_ride['number']}}</span> </p>
                                <p><span >OTP: {{$live_ride['otp']}}</span> </p>
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