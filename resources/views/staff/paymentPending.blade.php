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

    <div id="app" class="">

    @if(session()->has('success'))
                <div class="alert alert-success d-flex align-items-center" role="alert">
                    <span class="alert-icon text-success me-2">
                        <i class="ti ti-check ti-xs"></i>
                    </span>
                    {{ session()->get('success') }}
                </div>
                @endif
                <div class="section">
                    <div class="section-heading">
                    </div>
                    <div class="transactions">
                        <!-- item -->
                        @foreach($list as $v)
                        <a href="/staff/payment/detail/{{$v->transaction_id}}" class="item">
                            <div class="detail">
                                <div>
                                    <strong>{{$v->name}}</strong>
                                    <p>{{$v->paid_date}}</p>
                                    <p>{{$v->narrative}}</p>
                                </div>
                            </div>
                            <div class="right">
                                <div class="price text-warning">{{$v->amount}}</div>
                            </div>
                        </a>
                        @endforeach
                        <!-- * item -->
                        <!-- item -->

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
                companies: []
            }
        },
        mounted() {
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
            }
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
