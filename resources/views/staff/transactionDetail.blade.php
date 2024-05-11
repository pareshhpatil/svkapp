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

    <div class="section mt-2 mb-2">


        <div class="listed-detail mt-3">
            <div class="icon-wrapper">
                <div class="iconbox" style="background: green;">
                    <ion-icon name="arrow-forward-outline" role="img" class="md hydrated" aria-label="arrow forward outline"></ion-icon>
                </div>
            </div>
            <h3 class="text-center mt-2">Payment Sent</h3>
        </div>

        <ul class="listview flush transparent simple-listview no-space mt-3">
            <li>
                <strong>Status</strong>
                <span class="text-success">Success</span>
            </li>
            <li>
                <strong>To</strong>
                <span>{{$employee->account_holder_name}}</span>
            </li>
            <li>
                <strong>Account No</strong>
                <span>{{$employee->account_no}}</span>
            </li>
            <li>
                <strong>IFSC</strong>
                <span>{{$employee->ifsc_code}}</span>
            </li>
            <li>
                <strong>Mode</strong>
                <span>{{$transaction->payment_mode}}</span>
            </li>
            <li>
                <strong style="margin-right: 50px;">Narrative</strong>
                <span>{{$transaction->narrative}}</span>
            </li>
            <li>
                <strong>Date</strong>
                <span>{{$transaction->last_update_date}}</span>
            </li>
            <li>
                <strong>Amount</strong>
                <h3 class="m-0">{{number_format($transaction->amount,2)}}</h3>
            </li>
        </ul>


    </div>

</div>


@endsection

