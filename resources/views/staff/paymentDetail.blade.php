@extends('layouts.staff')
@section('content')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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

    .select2-container--default .select2-selection--single {
        background-color: transparent;
    }
</style>

<div id="appCapsule" class="full-height">

    <div id="app" class="section tab-content mb-1">

        <div class="">
            <div class="card-body">
                @if(session()->has('success'))
                <div class="alert alert-success d-flex align-items-center" role="alert">
                    <span class="alert-icon text-success me-2">
                        <i class="ti ti-check ti-xs"></i>
                    </span>
                    {{ session()->get('success') }}
                </div>
                @endif
                <form action="/staff/payment/paymentsave" method="post" id="frm-book">
                    @csrf

                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label mb-1" for="email1">Name</label>
                            <input type="text" value="{{$detail->name}}" readonly name="date" class="form-control">
                            <i class="clear-input">
                                <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                            </i>
                        </div>
                    </div>
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label mb-1" for="email1">Beneficiary name</label>
                            <input type="text" value="{{$detail->account_holder_name}}" readonly name="date" class="form-control">
                            <i class="clear-input">
                                <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                            </i>
                        </div>
                    </div>
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label mb-1" for="email1">Account number</label>
                            <input type="text" value="{{$detail->account_no}}" readonly name="date" class="form-control">
                            <i class="clear-input">
                                <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                            </i>
                        </div>
                    </div>

                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label mb-1" for="email2">Payment from</label>
                            <select class="form-control select2" v-model="source_id" required name="source_id" style="padding: 0 40px 0 16px;" data-placeholder="Select..">
                                <option value="">Select account</option>
                                </option>
                                <option v-for="item in paymentsource" :value="item.paymentsource_id" v-html="item.name">
                            </select>
                            <i class="clear-input">
                                <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                            </i>
                        </div>
                    </div>
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label mb-1" for="email2">Payment mode</label>
                            <select class="form-control select2" v-model="payment_mode" required name="payment_mode" style="padding: 0 40px 0 16px;" data-placeholder="Select..">
                                <option value="">Select account</option>
                                </option>
                                <option v-for="item in payment_modes" :value="item" v-html="item">
                            </select>
                            <i class="clear-input">
                                <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                            </i>
                        </div>
                    </div>
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label mb-1" for="password1">Date</label>
                            <div><input type="date" value="{{$date}}" required name="date" class="form-control" id="date" placeholder="Select Date">

                                <i class="clear-input">
                                    <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                                </i>
                            </div>
                        </div>
                    </div>
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="password2">Amount</label>
                            <input type="number" readonly value="{{$detail->amount}}" class="form-control" pattern="[0-9]*" name="amount" autocomplete="off" placeholder="Amount">
                            <i class="clear-input">
                                <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                            </i>
                        </div>
                    </div>



                    <div class="mt-2">
                        <div class="row">
                            <div class="col-6">
                                <input type="hidden" name="bill_id" value="{{$detail->transaction_id}}">
                                <input type="hidden" name="employee_id" value="{{$detail->employee_id}}">
                                <button type="submit" class="btn btn-lg btn-primary btn-block">Confirm</button>
                            </div>
                            <div class="col-6">
                                <a href="/staff/dashboard" class="btn btn-lg btn-outline-secondary btn-block">Cancel</a>
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
                employees: [],
                selected: '',
                paymentsource: [],
                payment_modes: [],
                source_id: '',
                payment_mode: '',
                companies: []
            }
        },
        mounted() {
            this.paymentsource = JSON.parse('{!!json_encode($paymentsource)!!}');
            this.payment_modes = JSON.parse('{!!json_encode($payment_modes)!!}');


        },
        methods: {

        }
    });

    function validateDate() {
        var currentDate = new Date();
        currentDate.setHours(currentDate.getHours() + 6);
        var updatedDate = new Date(document.getElementById('date').value + ' ' + document.getElementById('shift_time').value);
        if (currentDate > updatedDate) {
            document.getElementById("dialogclick").click();
            document.getElementById("status").value = '0';
            return false;
        } else {
            document.getElementById("status").value = '1';
        }
    }

    var today = new Date().toISOString().split('T')[0];
    document.getElementById('date').setAttribute('min', today);



    $(document).ready(function() {
        $('.select2').select2();
    });
</script>
@endsection
