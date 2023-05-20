@extends('layouts.app')
@section('content')
<style>
    .timeline.timed {
        padding-left: 0px;
    }
    .timeline.timed:before {
        left: 0px;
        top: 30px;
        bottom:40px;
}
.timeline .item {
    margin-bottom: 0px;
}
</style>
<div id="appCapsule" class="extra-header-active full-height">

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
                                    <h2 class="title">Office
                                    </h2>

                                </div>
                            </div>
                            <div class="transfer-verification">
                            <div class="from-to-block">
                                <div class="item text-start">
                                </div>
                                <div class="item text-end">
                                    <img src="/assets/img/swap.png" alt="avatar" class="imaged w48">
                                </div>
                                <div class="middle-line"></div>
                            </div>
                        </div>
                            <div class="item">
                                <div class="dot bg-primary"></div>
                                <div class="content">
                                    <h2 class="title">Home
                                    </h2>
                                </div>
                            </div>


                        </div>
                    </div>
                    <div class="form-group boxed">
                        <div class="input-wrapper">
                            <label class="label" for="email4b">Date</label>
                            <input type="date" required name="date" class="form-control" id="email4b" placeholder="Select Date">
                            <i class="clear-input">
                                <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                            </i>
                        </div>
                    </div>
                    <div class="form-group boxed">
                        <div class="input-wrapper">
                            <label class="label" for="email4b">Time</label>
                            <input type="time" required name="time" class="form-control" id="email5b" placeholder="Select Time">
                            <i class="clear-input">
                                <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                            </i>
                        </div>
                    </div>



                    
                    <div class="mt-2">
                        <div class="row">
                            <div class="col-6">
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