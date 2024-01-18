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
                <form action="/ridesave" method="post">
                    @csrf


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
                                                <select class="form-control custom-select" name="time" v-model="selected" style="padding: 0 40px 0 16px;">
                                                    <option value="">Select shift</option>
                                                    <option v-for="(item, key) in shifts"  :value="key" v-html="item">
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
@endsection

@section('footer')
<script>
    new Vue({
        el: '#app',
        data() {
            return {
                data: [],
                type: 'Pickup',
                pickup: 'Home',
                drop: 'Office',
                shifts: [],
                selected: '',
                allshifts: []
            }
        },
        mounted() {
            this.data = JSON.parse('{!!json_encode($data)!!}');
            this.allshifts = JSON.parse('{!!json_encode($array)!!}');
            this.shifts=this.allshifts[this.type];
        },
        methods: {
            changeMode() {
                this.selected='';
                if (this.type == 'Pickup') {
                    this.type = 'Drop';
                    this.pickup = 'Office';
                    this.drop = 'Home';
                } else {
                    this.type = 'Pickup';
                    this.pickup = 'Home';
                    this.drop = 'Office';
                }
                this.shifts=this.allshifts[this.type];
            }
        }
    });

    var today = new Date().toISOString().split('T')[0];
    document.getElementById('date').setAttribute('min', today);
</script>
@endsection