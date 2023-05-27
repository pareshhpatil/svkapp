@extends('layouts.app')
@section('content')
<div class="extraHeader pe-0 ps-0">
    <ul class="nav nav-tabs lined" role="tablist">

        @if(Session::get('user_type')==3)
        <li class="nav-item">
            <a class="nav-link @if($type=='booking') active @endif" id="tab-booking" data-bs-toggle="tab" href="#live" role="tab">
                <ion-icon name="add-circle-outline"></ion-icon>
                Live
            </a>
        </li>
        @endif
        <li class="nav-item">
            <a class="nav-link @if($type=='upcoming') active @endif" id="tab-upcoming" data-bs-toggle="tab" href="#upcoming" role="tab">
                <ion-icon name="pulse-outline"></ion-icon>
                Upcoming
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link @if($type=='past') active @endif" id="tab-past" data-bs-toggle="tab" href="#past" role="tab">
                <ion-icon name="calendar-outline"></ion-icon>
                Past
            </a>
        </li>
        @if(Session::get('user_type')==5)
        <li class="nav-item">
            <a class="nav-link @if($type=='booking') active @endif" id="tab-booking" data-bs-toggle="tab" href="#booking" role="tab">
                <ion-icon name="add-circle-outline"></ion-icon>
                Booking
            </a>
        </li>
        @endif
    </ul>
</div>
<div id="appCapsule" class="extra-header-active full-height">
    <div id="app" class="section tab-content mt-2 mb-1">
        <!-- waiting tab -->
        @if(Session::get('user_type')==3)
        <div class="tab-pane fade @if($type=='live') active show @endif  " id="live" role="tabpanel">
            <div class="transactions mt-2">
                <a v-if="data.live.length" v-for="item in data.live" :href="item.link" class="item">
                    <div class="detail">
                        <img v-if="!item.photo" src="/assets/img/driver.png" alt="img" class="image-block imaged w48">
                        <img v-if="item.photo" :src="item.photo" alt="img" class="image-block imaged w48">
                        <div>
                            <strong v-html="item.pickup_time"></strong>
                            <p><span v-html="item.pickup_location"></span> - <span v-html="item.drop_location"></span></p>
                        </div>
                    </div>
                    <div class="right">
                        <ion-icon name="chevron-forward-outline" role="img" class="md hydrated"></ion-icon>
                    </div>
                </a>
                <div v-if="!data.live">
                    <img src="/assets/img/no-record.png" alt="img" style="max-width: 100%;" class="">
                    <h3 class="text-center">No live rides</h3>
                    <p class="text-center">Time to book your next ride </p>
                </div>
            </div>
        </div>
        @endif
        <div class="tab-pane fade @if($type=='upcoming') active show @endif  " id="upcoming" role="tabpanel">
            <div class="transactions mt-2">
                @if(Session::get('user_type')!=3)
                <a v-if="data.live.length" v-for="item in data.live" :href="item.link" class="item">
                    <div class="detail">
                        <img v-if="!item.photo" src="/assets/img/driver.png" alt="img" class="image-block imaged w48">
                        <img v-if="item.photo" :src="item.photo" alt="img" class="image-block imaged w48">
                        <div>
                            <strong v-html="item.pickup_time"></strong>
                            <p><span v-html="item.pickup_location"></span> - <span v-html="item.drop_location"></span></p>
                        </div>
                    </div>
                    <div class="right">
                        <ion-icon name="chevron-forward-outline" role="img" class="md hydrated"></ion-icon>
                    </div>
                </a>
                @endif
                <!-- item -->
                <a v-if="data.upcoming.length" v-for="item in data.upcoming" :href="item.link" class="item">
                    <div class="detail">
                        <img v-if="!item.photo" src="/assets/img/driver.png" alt="img" class="image-block imaged w48">
                        <img v-if="item.photo" :src="item.photo" alt="img" class="image-block imaged w48">
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
        <div class="tab-pane fade @if($type=='past') active show @endif" id="past" role="tabpanel">
            <div class="transactions mt-2">
                <!-- item -->
                <a v-if="data.past.length" v-for="item in data.past" :href="item.link" class="item">
                    <div class="detail">
                        <img v-if="!item.photo" src="/assets/img/driver.png" alt="img" class="image-block imaged w48">
                        <img v-if="item.photo" :src="item.photo" alt="img" class="image-block imaged w48">
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
        @if(Session::get('user_type')==5)
        <div class="tab-pane fade @if($type=='booking') active show @endif" id="booking" role="tabpanel">
            <div class="transactions mt-2">
                <a v-if="data.booking.length" v-for="item in data.booking" href="#" class="item">
                    <div class="detail">
                        <img src="/assets/img/driver.png" alt="img" class="image-block imaged w48">
                        <div>
                            <h5 v-html="item.pickup_time"></h5>
                            <p><span v-html="item.type"></span></p>
                            <p><span class="badge badge-warning">Pending</span></p>
                        </div>
                    </div>
                    <div class="right">
                        <div data-bs-toggle="modal" :id="item.id" onclick="cancel(this.id)" data-bs-target="#cancelride" class="chip chip-outline chip-danger" style="padding: 5px;font-size: 25px;box-shadow: none !important;">
                            <ion-icon name="close-circle-outline" role="img" class="md hydrated" aria-label="add outline"></ion-icon>
                        </div>
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
        <div class="modal fade dialogbox" id="cancelride" data-bs-backdrop="static" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Cancel Booking</h5>
                    </div>
                    <form action="/passenger/booking/cancel" method="post">
                        @csrf
                        <div class="modal-body text-start mb-2">
                            You want to cancel?
                        </div>
                        <div class="modal-footer">
                            <div class="btn-inline">
                                <input type="hidden" id="cancel_booking_id" name="booking_id">
                                <button type="button" class="btn btn-text-secondary" data-bs-dismiss="modal">CLOSE</button>
                                <button type="submit" class="btn btn-text-primary">CONFIRM</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif



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
                cancel_booking_id: 0
            }
        },
        mounted() {
            this.data = JSON.parse('{!!json_encode($data)!!}');
        },
        methods: {
            acancel(id) {
                alert(id);
                //  document.getElementById('cancel_booking_id').value = id;
            }
        }
    })
    document.getElementById('tab-{{$type}}').click();

    function cancel(id) {
        document.getElementById('cancel_booking_id').value = id;
    }
</script>

@endsection