@extends('layouts.guest',['title'=>'Payment Receipt'])
@section('content')
<div id="appCapsule" class="full-height">

    <div class="section mt-2 mb-2">

        @if($transaction->status==2)
        <div class="listed-detail mt-3">
            <div class="icon-wrapper">
                <div class="iconbox">
                    <ion-icon name="arrow-forward-outline" role="img" class="md hydrated" aria-label="arrow forward outline"></ion-icon>
                </div>
            </div>
            <h3 class="text-center mt-2">Payment Failed</h3>
        </div>
        @else
        <div class="listed-detail mt-3">
            <div class="icon-wrapper">
                <div class="iconbox" style="background: green;">
                    <ion-icon name="arrow-forward-outline" role="img" class="md hydrated" aria-label="arrow forward outline"></ion-icon>
                </div>
            </div>
            <h3 class="text-center mt-2">Payment Success</h3>
        </div>
        @endif

        <ul class="listview flush transparent simple-listview no-space mt-3">
            @if($transaction->status==2)
            <li>
                <strong>Status</strong>
                <span class="text-danger">Failed</span>
            </li>
            <li>
                <strong>Reason</strong>
                <span>{{$reason}}</span>
            </li>
            @else
            <li>
                <strong>Status</strong>
                <span class="text-success">Success</span>
            </li>
            @endif
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
        <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-2768566574593657" crossorigin="anonymous"></script>
        <ins class="adsbygoogle" style="display:block" data-ad-format="fluid" data-ad-layout-key="-hv+e-p-44+a0" data-ad-client="ca-pub-2768566574593657" data-ad-slot="6673501127"></ins>
        <script>
            (adsbygoogle = window.adsbygoogle || []).push({});
        </script>

</div>
@endsection
