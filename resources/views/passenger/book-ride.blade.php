@extends('layouts.app')
@section('content')
<style>
    .timeline.timed {
        padding-left: 0px;
    }

    .timeline.timed:before {
        left: 0px;
        top: 30px;
        bottom: 40px;
    }

    .timeline .item {
        margin-bottom: 0px;
    }

    .form-group.boxed .form-control {
        background: #fff;
        box-shadow: none;
        height: 42px;
        border-radius: 10px;
        padding: 0 40px 0 16px;
        vertical-align: middle;
    }

    .badge {
        font-size: 22px;
        height: 42px;
        min-width: 42px;
    }

    .image-listview>li:after {
        left: 0px;
    }

    .form-group.basic .form-control {
        border-bottom: 0;
    }

    body.dark-mode .listview {
        background: transparent;
    }

    .middle-line {
        top: 20px;
    }
</style>

<div id="appCapsule" class="full-height">

    <div id="app" class="section tab-content mb-1">

        <div class="">
            <div class="card-body">
                <form action="/ridesave" method="post" id="frm-book" onsubmit="return validateDate()">
                    @csrf
                    <div class="alert alert-outline-warning mb-1" role="alert" style="background-color: #ffffff;">
                        Booking & Cancellations are only permitted up to 6 hours before the scheduled pickup time.
                    </div>
                    <div class="form-group boxed">
                        <div class="timeline timed ms-1 me-2">

                            <div class="item">
                                <div class="dot bg-info"></div>
                                <div class="content">
                                    <h2 class="title" v-html="pickup">Office
                                    </h2>

                                </div>
                            </div>
                            <div class="transfer-verification">
                                <div class="from-to-block">
                                    <div class="item text-start">
                                    </div>
                                    <div class="item text-end">
                                        <a href="#" v-on:click="changeMode();"><img src="/assets/img/swap.png" alt="avatar" class="imaged w48"></a>
                                    </div>
                                    <div class="middle-line"></div>
                                </div>
                            </div>
                            <div class="item">
                                <div class="dot bg-primary bg-red"></div>
                                <div class="content">
                                    <h2 class="title" v-html="drop">Home
                                    </h2>
                                </div>
                            </div>


                        </div>
                    </div>
                    <div class="form-group basic">
                        <ul class="listview image-listview transparent flush">
                            <li>
                                <div class="item">
                                    <div class="in">
                                        <div v-html="type">Pickup</div>
                                        <span v-if="type=='Pickup'" v-on:click="changeMode();" class="badge badge-info"><ion-icon name="arrow-up-outline"></ion-icon></span></a>
                                        <span v-if="type=='Drop'" v-on:click="changeMode();" class="badge badge-primary "><ion-icon name="arrow-down-outline"></ion-icon></span></a>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="item">
                                    <div class="in">
                                        <div><input type="date" value="{{$date}}" required name="date" class="form-control" id="date" placeholder="Select Date">
                                        </div>
                                        <a onclick="document.getElementById('date').click();"><span class="badge badge-info"><ion-icon name="calendar-outline"></ion-icon></span></a>
                                    </div>
                                </div>
                            </li>
                            <li>
                                @if(empty($array))
                                <div class="item">
                                    <div class="in">
                                        <div><input type="time" required value="09:00" name="time" class="form-control" id="time" placeholder="Select Date">
                                        </div>
                                        <a onclick="document.getElementById('time').click();"><span class="badge badge-info"><ion-icon name="alarm-outline"></ion-icon></span></a>
                                    </div>
                                </div>
                                @else
                                <div class="item" style="padding-left: 0px;">
                                    <div class="in">
                                        <div>
                                            <div class="input-wrapper">
                                                <select class="form-control custom-select" id="shift_time" name="time" v-model="selected" style="padding: 0 40px 0 16px;">
                                                    <option value="">Select shift</option>
                                                    <option v-for="(item, key) in shifts" :value="key" v-html="item">
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <a onclick="document.getElementById('time').click();"><span class="badge badge-info"><ion-icon name="alarm-outline"></ion-icon></span></a>
                                    </div>
                                </div>
                                @endif
                            </li>

                        </ul>

                    </div>





                    <div class="mt-2">
                        <div class="row">
                            <div class="col-6">

                                <input type="hidden" name="type" :value="type" value="Pickup">
                                <input type="hidden" id="status" name="status" value="1">
                                <button type="submit" class="btn btn-lg btn-primary btn-block">Confirm</button>
                            </div>
                            <div class="col-6">
                                <a href="/dashboard" class="btn btn-lg btn-outline-secondary btn-block">Cancel</a>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>




    </div>

</div>

<div class="modal fade dialogbox" id="DialogIconedDanger" data-bs-backdrop="static" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-icon text-primary">
                <ion-icon name="close-circle"></ion-icon>
            </div>
            <div class="modal-header">
                <h5 class="modal-title">Warning</h5>
            </div>
            <div class="modal-body">
                Your request will be sent for admin approval as this is Ad hoc booking. Whether approved or rejected, you will receive a notification regarding the status of your booking.
            </div>
            <div class="modal-footer">
                <div class="btn-inline">
                    <a href="#" class="btn" data-bs-dismiss="modal">CLOSE</a>
                    <a href="#" class="btn btn-primary" onclick="document.getElementById('frm-book').submit();" data-bs-dismiss="modal">Confirm</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade dialogbox" id="DialogIconedDanger2" data-bs-backdrop="static" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-icon text-primary">
                <ion-icon name="close-circle"></ion-icon>
            </div>
            <div class="modal-header">
                <h5 class="modal-title">Error</h5>
            </div>
            <div class="modal-body">
                The selected pickup date and time must be in the future.
            </div>
            <div class="modal-footer">
                <div class="btn-inline">
                    <a href="#" class="btn" data-bs-dismiss="modal">CLOSE</a>
                </div>
            </div>
        </div>
    </div>
</div>

<a href="#" class="item" id="dialogclick" data-bs-toggle="modal" data-bs-target="#DialogIconedDanger"></a>
<a href="#" class="item" id="dialogclick2" data-bs-toggle="modal" data-bs-target="#DialogIconedDanger2"></a>

@endsection

@section('footer')
<script>
    new Vue({
        el: '#app',
        data() {
            return {
                data: [],
                type: 'Drop',
                pickup: 'Office',
                drop: 'Home',
                shifts: [],
                selected: '',
                allshifts: []
            }
        },
        mounted() {
            this.data = JSON.parse('{!!json_encode($data)!!}');
            this.allshifts = JSON.parse('{!!json_encode($array)!!}');
            this.shifts = this.allshifts[this.type];
        },
        methods: {
            changeMode() {
                this.selected = '';
                if (this.type == 'Pickup') {
                    this.type = 'Drop';
                    this.pickup = 'Office';
                    this.drop = 'Home';
                } else {
                    this.type = 'Pickup';
                    this.pickup = 'Home';
                    this.drop = 'Office';
                }
                this.shifts = this.allshifts[this.type];
            }
        }
    });

    function validateDate() {
        var currentDate = new Date();
        var updatedDate = new Date(document.getElementById('date').value + ' ' + document.getElementById('shift_time').value);
        if (updatedDate > currentDate) {
            currentDate.setHours(currentDate.getHours() + 6);
            if (currentDate > updatedDate) {
                document.getElementById("dialogclick").click();
                document.getElementById("status").value = '0';
                return false;
            } else {
                document.getElementById("status").value = '1';
            }
        } else {
            document.getElementById("dialogclick2").click();
            document.getElementById("status").value = '0';
            return false;

        }
    }

    var today = new Date().toISOString().split('T')[0];
    document.getElementById('date').setAttribute('min', today);
</script>
@endsection