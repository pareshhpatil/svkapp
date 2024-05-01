@extends('layouts.staff')
@section('content')
<style>
    .timeline:before {
        bottom: 60px;
        top: 28px;
    }

    body.dark-mode .text-black {
        color: #fff !important;
        background: #20162a !important;
    }

    body.dark-mode .listview {
        color: #fff;
        background: #030108;
        border-top-color: #030108;
        border-bottom-color: #030108;
    }

    .bg-red {
        background: #e8481e !important;
    }

    .stat-box .value {
        font-size: 17px;
    }

    .dialogbox .modal-dialog .modal-content {
        max-width: inherit;
        max-height: inherit;
    }

    .custom-control-input {
        position: absolute;
        border: none;
    }
</style>
<div id="appCapsule" class="full-height">
    <div id="app">

        <!-- Wallet Card -->
        <div class="section wallet-card-section pt-1">
            <div class="wallet-card">
                <!-- Balance -->
                <div class="balance">
                    <div class="left">
                        <span class="title">Account Balance</span>
                        <h1 class="total">₹ {{$total_balance}}</h1>
                    </div>

                </div>
                <!-- * Balance -->
                <!-- Wallet Footer -->
                <div class="wallet-footer">
                    <div class="item">
                        <a href="/staff/payment/request">
                            <div class="icon-wrapper bg-danger">
                                <ion-icon name="arrow-down-outline" role="img" class="md hydrated" aria-label="arrow down outline"></ion-icon>
                            </div>
                            <strong>Request</strong>
                        </a>
                    </div>
                    <div class="item">
                        <a href="/staff/payment/send">
                            <div class="icon-wrapper">
                                <ion-icon name="arrow-forward-outline" role="img" class="md hydrated" aria-label="arrow forward outline"></ion-icon>
                            </div>
                            <strong>Payment</strong>
                        </a>
                    </div>
                    <div class="item">
                        <a href="/staff/payment/transactions">
                            <div class="icon-wrapper bg-success">
                                <ion-icon name="card-outline" role="img" class="md hydrated" aria-label="card outline"></ion-icon>
                            </div>
                            <strong>Transactions</strong>
                        </a>
                    </div>


                </div>
                <!-- * Wallet Footer -->
            </div>
        </div>

        <div class="section">
            <div class="row mt-2">
                <template>
                    <div class="col-6">
                        <a href="/staff/payment/pending">
                            <div class="stat-box">
                                <div class="title text-center">Total Pending </div>
                                <div class="value text-warning text-center" v-html="data.total_pending"></div>
                            </div>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="/staff/payment/transactions">
                            <div class="stat-box">
                                <div class="title text-center">Total Transactions </div>
                                <div class="value text-success text-center" v-html="data.total_transactions"></div>
                            </div>
                        </a>
                    </div>
                </template>
            </div>

        </div>

        <div class="section mt-4">
            <div class="section-heading">
                <h2 class="title">Transactions</h2>
                <a href="/staff/payment/transactions" class="link">View All</a>
            </div>
            <div class="transactions">
                <!-- item -->
                @foreach($transaction_list as $k=>$v)
                @if($k<6)
                <a href="#" class="item">
                    <div class="detail">
                        <div>
                            <strong>{{$v->name}}</strong>
                            <p>{{$v->paid_date}}</p>
                            <p>{{$v->payment_source}} - {{$v->payment_mode}}</p>
                        </div>
                    </div>
                    <div class="right">
                        <div class="price text-success">{{$v->amount}}</div>
                    </div>
                    </a>
                    @endif
                @endforeach
            </div>
        </div>
        <!-- * Stats -->
        @if(session('show_ad'))
        <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-2768566574593657" crossorigin="anonymous"></script>
        <ins class="adsbygoogle" style="display:block" data-ad-format="fluid" data-ad-layout-key="-hv+e-p-44+a0" data-ad-client="ca-pub-2768566574593657" data-ad-slot="6673501127"></ins>
        <script>
            (adsbygoogle = window.adsbygoogle || []).push({});
        </script>
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
                data: []
            }
        },
        mounted() {
            this.data = JSON.parse('{!!json_encode($data)!!}');
        }
    })
</script>
@endsection
