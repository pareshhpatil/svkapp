@extends('layouts.app')
@section('content')
<div class="extraHeader pe-0 ps-0">
    <ul class="nav nav-tabs lined" role="tablist">
        @if($type=='request')
        <li class="nav-item">
            <a class="nav-link @if($type=='request') active @endif" id="tab-request" data-bs-toggle="tab" href="#request" role="tab">
                <ion-icon name="add-circle-outline"></ion-icon>
                Pending Request
            </a>
        </li>
        @else
        @if(Session::get('user_type')==3)
        <li class="nav-item">
            <a class="nav-link @if($type=='booking') active @endif" id="tab-pending" data-bs-toggle="tab" href="#pending" role="tab">
                <ion-icon name="reader-outline"></ion-icon>
                Pen
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link @if($type=='booking') active @endif" id="tab-booking" data-bs-toggle="tab" href="#live" role="tab">
                <ion-icon name="speedometer-outline"></ion-icon>
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
                    <span v-if="item.ride_status==2" class="badge badge-success">Live</span>
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
        <div class="tab-pane fade @if($type=='pending') active show @endif  " id="pending" role="tabpanel">
            <div class="appHeader" style="    top: auto;    margin-top: -10px;position: relative;border-radius: 10px;">
                <div class="left">
                    <a href="#" v-on:click="fetchDate(0)" class="headerButton">
                        <ion-icon name="chevron-back-outline" role="img" class="md hydrated" aria-label="chevron back outline"></ion-icon>
                    </a>
                </div>
                <div class="pageTitle" v-html="current_date">
                </div>
                <div class="right">
                <!-- <span v-if="item.ride_status==2" class="badge badge-success">Live</span> -->
                    <a v-on:click="fetchDate(1)" href="#" class="headerButton">
                        <ion-icon name="chevron-forward-outline" role="img" class="md hydrated" aria-label="chevron forward outline"></ion-icon>
                    </a>
                </div>
            </div>
            <div class="transactions mt-2">
                <a v-if="data.pending.length && item.date==current_date" v-for="item in data.pending" :href="item.link" class="item">
                    <div class="detail">
                        <div>
                            <strong v-html="item.pickup_time"></strong>
                            <p><span v-html="item.pickup_location"></span> - <span v-html="item.drop_location"></span></p>
                        </div>
                    </div>
                    <div class="right">
                    <span v-if="item.ride_status==2" class="badge badge-success">Live</span>
                        <ion-icon name="chevron-forward-outline" role="img" class="md hydrated"></ion-icon>
                    </div>
                </a>
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
                    <span v-if="item.ride_status==2" class="badge badge-success">Live</span>
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
                            <p><span v-html="item.pickup_location"></span> - <span v-html="item.drop_location"></span> (<span v-html="item.number.slice(-4)"></span>)</p>
                        </div>
                    </div>
                    <div class="right">
                    <span v-if="item.ride_status==2" class="badge badge-success">Live</span>
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
                                        <span v-for="count in 5">
                                            <svg v-if="count<=item.rating" version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 12.705 512 486.59" x="0px" y="0px" xml:space="preserve" width="15px" height="15px" fill="#e8481e">
                                                <polygon points="256.814,12.705 317.205,198.566 512.631,198.566 354.529,313.435 414.918,499.295 256.814,384.427 98.713,499.295 159.102,313.435 1,198.566 196.426,198.566 ">
                                                </polygon>
                                            </svg>
                                            <svg v-if="count>item.rating" version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 12.705 512 486.59" x="0px" y="0px" xml:space="preserve" width="15px" height="15px" fill="#dbdade">
                                                <polygon points="256.814,12.705 317.205,198.566 512.631,198.566 354.529,313.435 414.918,499.295 256.814,384.427 98.713,499.295 159.102,313.435 1,198.566 196.426,198.566 ">
                                                </polygon>
                                            </svg>
                                        </span>

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
        @if($type=='request')
        <div class="tab-pane fade @if($type=='request') active show @endif" id="request" role="tabpanel">
            <div class="alert alert-outline-warning mb-1" role="alert" style="background-color: #ffffff;">
                Admin Approval are only permitted up to 6 hours before the scheduled pickup time.
            </div>
            <div class="transactions mt-2">
                <div v-if="data.request.length" v-for="item in data.request" href="#" class="item" style="padding: 10px 10px;">
                    <div class="detail">
                        <img v-if="item.gender=='Male'" src="/assets/img/map-male.png" alt="avatar" class="avatar">
                        <img v-if="item.gender=='Female'" src="/assets/img/map-female.png" alt="avatar" class="avatar">
                        <div style="margin-left: 5px;">
                            <h5><span v-html="item.employee_name"></span></h5>
                            </h5>
                            <h5><span v-html="item.pickup_time"></span></h5>
                            </h5>
                            <h5><span v-html="item.type"></span></h5>
                        </div>
                    </div>
                    <br>
                    <br>
                    <div class="right" style="min-width: 135px;">
                        <div v-if="item.status==0 || item.status==2" data-bs-toggle="modal" v-on:click="approve(item,'Approve')" data-bs-target="#approveRide" class="btn btn-sm btn-success">
                            Approve
                        </div>
                        <div v-if="item.status==0 || item.status==2" data-bs-toggle="modal"  v-on:click="approve(item,'Reject')" data-bs-target="#approveRide" class="btn btn-sm btn-primary">
                            Reject
                        </div>
                    </div>
                </div>
                <div class="text-center" v-if="!data.request.length">
                    <img src="/assets/img/no-record.png" alt="img" style="max-width: 100%;" class="">
                    <h3 class="text-center">No request</h3>
                </div>
            </div>
        </div>
        @endif

        @if(Session::get('user_type')==5)
        <div class="tab-pane fade @if($type=='booking') active show @endif" id="booking" role="tabpanel">
            <div class="alert alert-outline-warning mb-1" role="alert" style="background-color: #ffffff;">
                Cancellations are only permitted up to 6 hours before the scheduled pickup time.
            </div>
            <div class="transactions mt-2">
                <a v-if="data.booking.length" v-for="item in data.booking" href="#" class="item" style="padding: 10px 14px;">
                    <div class="detail">
                        <img src="/assets/img/driver.png" alt="img" class="image-block imaged w48">
                        <div>
                            <h5><span v-html="item.pickup_time"></span>&nbsp;&nbsp;&nbsp;<span v-if="item.status==0 || item.status==2" class="badge badge-warning">Pending</span> <span v-if="item.status==3" class="badge badge-warning">Cancelled</span><span v-if="item.status==4" class="badge badge-primary">Rejected</span></h5>

                            </h5>

                            <h4><span v-html="item.type"></span></h4>
                        </div>
                    </div>
                    <div class="right">
                        <div v-if="item.status==0 || item.status==1" data-bs-toggle="modal" :status="item.status" :hour="item.hours" :id="item.id" v-on:click="cancel(item)" data-bs-target="#cancelride" class="btn btn-sm btn-primary">
                            Cancel
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
                        <div class="modal-body text-start mb-2" id="cancel_message">
                            You want to cancel?
                        </div>
                        <div class="modal-footer">
                            <div class="btn-inline">
                                <input type="hidden" id="cancel_booking_id" name="booking_id">
                                <input type="hidden" id="no_show" value="0" name="no_show">
                                <button type="button" class="btn btn-text-secondary" data-bs-dismiss="modal">CLOSE</button>
                                <button type="submit" class="btn btn-text-primary">CONFIRM</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        @endif
        <div class="modal fade dialogbox" id="approveRide" data-bs-backdrop="static" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div v-if="approve_type!='Approve'" class="modal-icon text-primary">
                        <ion-icon name="close-circle"></ion-icon>
                    </div>
                    <div v-if="approve_type=='Approve'" class="modal-icon text-success">
                        <ion-icon name="checkmark-circle-outline"></ion-icon>
                    </div>
                    <form action="/passenger/booking/approve" id="frm-approve" method="post">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="approve_type_title">Approve</h5>
                        </div>
                        <div class="modal-body">
                            Are you sure?
                        </div>
                        <div class="modal-footer">
                            <div class="btn-inline">
                                <input type="hidden" id="approve_booking_id" name="booking_id">
                                <input type="hidden" id="approve_type" value="Approve" name="approve_type">
                                <a href="#" class="btn" data-bs-dismiss="modal">CLOSE</a>
                                <a href="#" class="btn btn-primary" onclick="document.getElementById('frm-approve').submit();" data-bs-dismiss="modal">Confirm</a>
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
                cancel_booking_id: 0,
                approve_type: 'Approve',
                current_date: '{{$current_date}}'
            }
        },
        mounted() {
            this.data = JSON.parse('{!!json_encode($data)!!}');
        },
        methods: {
            async fetchDate(type) {
                // var date = '';
                let res = await axios.get('/date/fetch/' + this.current_date + '/' + type);
                this.current_date = res.data;

            },
            approve(item, type) {
                this.approve_type=type;
                document.getElementById('approve_type_title').innerHTML = type;
                document.getElementById('approve_booking_id').value = item.id;
                document.getElementById('approve_type').value = type;
            },
			cancel(item) {
                if (item.hours > 6 || item.status == 0) {
                    document.getElementById('no_show').value = '0';
                    document.getElementById('cancel_message').innerHTML = 'You want to cancel?';
                } else {
                    document.getElementById('no_show').value = '1';
                    document.getElementById('cancel_message').innerHTML = 'Your request will be sent for admin approval as this is Ad hoc request. Whether approved or rejected, you will receive a notification regarding the status of your request.';

                }
                document.getElementById('cancel_booking_id').value = item.id;
            }
        }
    })
    document.getElementById('tab-{{$type}}').click();
</script>

@endsection
