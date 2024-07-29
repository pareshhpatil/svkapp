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

    .image-listview>li .item {
        min-height: 40px;
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
        <div class="mb-2" v-if="data.ride.status==2 || data.ride.status==6 || data.ride.status==7 || data.ride.status==5">
            <div class="appHeader bg-info text-light" style="top:60px;margin-bottom:50px">
                <div class="pageTitle" v-if="data.ride.status==2">Driver Started Ride</div>
                <div class="pageTitle" v-if="data.ride.status==6">Driver Reached at Pickup Location</div>
                <div class="pageTitle" v-if="data.ride.status==7">Passenger picked up</div>
                <div class="pageTitle" v-if="data.ride.status==5">Ride completed</div>
            </div>
            <div class="mt-2">
                &nbsp;
            </div>
        </div>
        <div class="card mt-1 mb-1" style="top:10px">
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

                                                <strong v-html="data.driver.name"></strong>
                                            </div>

                                        </div>
                                    </div>
                                </li>
                                @isset($data['driver']['mobile'])
                                <li style="padding:0px;">
                                    <div class="item" style="padding: 0;">
                                        <div v-on:click="call('{{$data['driver']['mobile']}}')" class="icon-box text-black">
                                            <ion-icon name="call-outline"></ion-icon>
                                        </div>
                                        <div class="in">
                                            <div>
                                                <strong v-html="data.driver.mobile"></strong>

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
        </div>
        <div class="card mt-1 mb-1" style="top:10px">
            <ul class="listview flush transparent no-line image-listview detailed-list mt-1 mb-1">
                <!-- item -->
                <li>
                    <a href="#" class="item" style="padding: 5px 16px;min-height: 20px;">
                        <div class="icon-box bg-success">
                            <ion-icon name="arrow-up-outline" role="img" class="md hydrated" aria-label="arrow up outline"></ion-icon>
                        </div>
                        <div class="in">
                            <div>
                                <strong v-html="data.ride.start_time"></strong>
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

        <hr>



       <div class="carousel-slider splide">
            <div class="splide__track">
                <ul class="splide__list">
                    <li class="splide__slide p-2" v-for="(item, index) in data.photos">
                        <img :src="item.url" alt="alt" class="imaged w-100 square mb-4">
                        <h2 v-text="item.name"></h2>
                    </li>
                </ul>
            </div>
        </div>












    </div>

</div>



@endsection



@section('footer')





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




    function stop() {
        window.WTN.backgroundLocation.stop();
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

        }
    });
</script>


@endsection
