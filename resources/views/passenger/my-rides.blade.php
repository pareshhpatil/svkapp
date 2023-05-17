@extends('layouts.app')
@section('content')
<div class="extraHeader pe-0 ps-0">
    <ul class="nav nav-tabs lined" role="tablist">

        <li class="nav-item">
            <a class="nav-link active" id="tab-upcoming" data-bs-toggle="tab" href="#upcoming" role="tab">
                <ion-icon name="pulse-outline"></ion-icon>
                Upcoming
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="tab-past" data-bs-toggle="tab" href="#past" role="tab">
                <ion-icon name="calendar-outline"></ion-icon>
                Past
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link " id="tab-booking" data-bs-toggle="tab" href="#live" role="tab">
                <ion-icon name="add-circle-outline"></ion-icon>
                Booking
            </a>
        </li>
    </ul>
</div>
<div id="appCapsule" class="extra-header-active full-height">

    <div id="app" class="section tab-content mt-2 mb-1">
        <!-- waiting tab -->
        <div class="tab-pane fade active show " id="upcoming" role="tabpanel">
            <div class="transactions mt-2">
                <a v-if="data.live" href="app-transaction-detail.html" class="item">
                    <div class="detail">
                        <img src="/assets/img/driver.png" alt="img" class="image-block imaged w48">
                        <div>
                            <strong v-html="data.live.pickup_time"></strong>
                            <p><span v-html="data.live.pickup_location"></span> - <span v-html="data.live.drop_location"></span></p>
                        </div>
                    </div>
                    <div class="right">
                        <ion-icon name="chevron-forward-outline" role="img" class="md hydrated" aria-label="chevron forward outline"></ion-icon>
                    </div>
                </a>
                <!-- item -->
                <a v-for="item in data.upcoming" href="app-transaction-detail.html" class="item">
                    <div class="detail">
                        <img src="/assets/img/driver.png" alt="img" class="image-block imaged w48">
                        <div>
                            <strong v-html="item.pickup_time"></strong>
                            <p><span v-html="item.pickup_location"></span> - <span v-html="item.drop_location"></span></p>
                        </div>
                    </div>
                    <div class="right">
                        <ion-icon name="chevron-forward-outline" role="img" class="md hydrated"></ion-icon>
                    </div>
                </a>


                <div v-if="!data.upcoming.length && !data.live">
                    <img src="/assets/img/no-record.png" alt="img" style="max-width: 100%;" class="">
                    <h3 class="text-center">No upcoming rides</h3>
                    <p class="text-center">Time to book your next ride </p>
                </div>

                <!-- * item -->
            </div>


            <!-- <div class="section mt-2 mb-2">
                <a href="#" class="btn btn-primary btn-block btn-lg">Load More</a>
            </div>-->
        </div>
        <div class="tab-pane fade" id="past" role="tabpanel">
            <div class="transactions mt-2">
                <!-- item -->
                <a v-for="item in data.past" href="app-transaction-detail.html" class="item">
                    <div class="detail">
                        <img src="/assets/img/driver.png" alt="img" class="image-block imaged w48">
                        <div>
                            <strong v-html="item.pickup_time"></strong>
                            <p><span v-html="item.pickup_location"></span> - <span v-html="item.drop_location"></span></p>
                            <div class="full-star-ratings jq-ry-container" data-rateyo-full-star="true">
                                <div class="jq-ry-group-wrapper">
                                    <div class="jq-ry-rated-group jq-ry-group" style="width: 80%;">
                                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 12.705 512 486.59" x="0px" y="0px" xml:space="preserve" width="15px" height="15px" fill="#dbdade">
                                            <polygon points="256.814,12.705 317.205,198.566 512.631,198.566 354.529,313.435 414.918,499.295 256.814,384.427 98.713,499.295 159.102,313.435 1,198.566 196.426,198.566 ">
                                            </polygon>
                                        </svg><svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 12.705 512 486.59" x="0px" y="0px" xml:space="preserve" width="15px" height="15px" fill="#dbdade" style="margin-left: 8px;">
                                            <polygon points="256.814,12.705 317.205,198.566 512.631,198.566 354.529,313.435 414.918,499.295 256.814,384.427 98.713,499.295 159.102,313.435 1,198.566 196.426,198.566 ">
                                            </polygon>
                                        </svg><svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 12.705 512 486.59" x="0px" y="0px" xml:space="preserve" width="15px" height="15px" fill="#dbdade" style="margin-left: 8px;">
                                            <polygon points="256.814,12.705 317.205,198.566 512.631,198.566 354.529,313.435 414.918,499.295 256.814,384.427 98.713,499.295 159.102,313.435 1,198.566 196.426,198.566 ">
                                            </polygon>
                                        </svg><svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 12.705 512 486.59" x="0px" y="0px" xml:space="preserve" width="15px" height="15px" fill="#dbdade" style="margin-left: 8px;">
                                            <polygon points="256.814,12.705 317.205,198.566 512.631,198.566 354.529,313.435 414.918,499.295 256.814,384.427 98.713,499.295 159.102,313.435 1,198.566 196.426,198.566 ">
                                            </polygon>
                                        </svg><svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 12.705 512 486.59" x="0px" y="0px" xml:space="preserve" width="15px" height="15px" fill="#dbdade" style="margin-left: 8px;">
                                            <polygon points="256.814,12.705 317.205,198.566 512.631,198.566 354.529,313.435 414.918,499.295 256.814,384.427 98.713,499.295 159.102,313.435 1,198.566 196.426,198.566 ">
                                            </polygon>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="right">
                        <ion-icon name="chevron-forward-outline" role="img" class="md hydrated"></ion-icon>
                    </div>
                </a>

                <div v-if="!data.past.length">
                    <img src="/assets/img/no-record.png" alt="img" style="max-width: 100%;" class="">
                    <h3 class="text-center">No past rides</h3>
                </div>

                <!-- * item -->
            </div>
        </div>
        <!-- * waiting tab -->

        <div class="tab-pane fade show" id="live" role="tabpanel">
            <div class="transactions mt-2">
                <a v-for="item in data.booking" href="app-transaction-detail.html" class="item">
                    <div class="detail">
                        <img src="/assets/img/driver.png" alt="img" class="image-block imaged w48">
                        <div>
                            <strong v-html="item.pickup_time"></strong>
                            <p><span v-html="item.type"></span></p>
                            <p><span class="badge badge-warning">Pending</span></p>
                        </div>
                    </div>
                    <div class="right">
                        <ion-icon name="chevron-forward-outline" role="img" class="md hydrated" aria-label="chevron forward outline"></ion-icon>
                    </div>
                </a>

                <div class="text-center" v-if="!data.booking.length">
                    <img src="/assets/img/no-record.png" alt="img" style="max-width: 100%;" class="">
                    <h3 class="text-center">No booking rides</h3>
                    <p class="text-center">Time to book your next ride </p>
                    <a href="/book-ride" type="button" class="btn btn-icon btn-primary me-1">
                        <ion-icon name="add-outline" role="img" class="md hydrated" aria-label="add outline"></ion-icon>
                    </a>
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
    document.getElementById('tab-{{$type}}').click();
</script>
@endsection