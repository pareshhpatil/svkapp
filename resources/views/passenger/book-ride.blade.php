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
</style>

<div id="appCapsule" class="full-height">
    <img class="imaged" style="width: 100%;" src="/assets/img/book.png">

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
                                <div class="dot bg-primary" style="background: #e8481e !important;"></div>
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
                                        <div>Select date</div>
                                        <span onclick="document.getElementById('date').click();" class="badge badge-info"><ion-icon name="calendar-outline"></ion-icon></span>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="item">
                                    <div class="in">
                                        <div>Select Time</div>
                                        <span onclick="document.getElementById('date').click();" class="badge badge-info"><ion-icon name="alarm-outline"></ion-icon></span>
                                    </div>
                                </div>
                            </li>

                        </ul>

                    </div>
                    




                    <div class="mt-2">
                        <div class="row">
                            <div class="col-6">
                                <input type="date" style="width: 0px;display: contents;" required name="date" class="form-control" id="date" placeholder="Select Date">
                                <input type="time" style="width: 0px;display: contents;" required name="time" class="form-control" id="time" placeholder="Select Date">

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
                drop: 'Office'
            }
        },
        mounted() {
            this.data = JSON.parse('{!!json_encode($data)!!}');
        },
        methods: {
            changeMode() {
                console.log(this.type);
                if (this.type == 'Pickup') {
                    this.type = 'Drop';
                    this.pickup = 'Office';
                    this.drop = 'Home';
                } else {
                    this.type = 'Pickup';
                    this.pickup = 'Home';
                    this.drop = 'Office';
                }
            }
        }
    });

    var today = new Date().toISOString().split('T')[0];
    //document.getElementById('date').setAttribute('min', today);
</script>
@endsection