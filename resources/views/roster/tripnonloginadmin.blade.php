@extends('layouts.employee')

@section('content')
<style>

    .rate {
        float: left;
        height: 46px;
        padding: 0 10px;
    }
    .rate:not(:checked) > input {
        position:absolute;
        top:-9999px;
    }
    .rate:not(:checked) > label {
        float:right;
        width:1em;
        overflow:hidden;
        white-space:nowrap;
        cursor:pointer;
        font-size:30px;
        color:#ccc;
    }
    .rate:not(:checked) > label:before {
        content: '★ ';
    }
    .rate > input:checked ~ label {
        color: #ffc700;    
    }
    .rate:not(:checked) > label:hover,
    .rate:not(:checked) > label:hover ~ label {
        color: #deb217;  
    }
    .rate > input:checked + label:hover,
    .rate > input:checked + label:hover ~ label,
    .rate > input:checked ~ label:hover,
    .rate > input:checked ~ label:hover ~ label,
    .rate > label:hover ~ input:checked ~ label {
        color: #c59b08;
    }

    /* Modified from: https://github.com/mukulkant/Star-rating-using-pure-css */
</style>
<div class="row">
    <div class="col-md-3">
        @isset($success_message)
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <strong>Success! </strong> {{$success_message}}
        </div>
        @endisset
        <!-- Profile Image -->
        <div class="box box-primary">
            <div class="box-body box-profile">
                <div style="width: 100%; text-align: center;">
                    @if($ddet->photo!='')
                    <img style="display: inline; max-height: 150px;"  class="img-responsive " src="{{ asset('dist/uploads/employee/'.$ddet->photo) }}" alt="User profile picture">
                    @else
                    <!--<img style="display: inline;" class="img-responsive " src="{{ asset('dist/img/avatar5.png') }}" alt="User profile picture">-->
                    @endif
                </div>

                <h3 class="profile-username text-center">{{$vdet->number}}</h3>
                <ul class="list-group list-group-unbordered" style="margin-bottom: 0px;">
                    <li class="list-group-item">
                        <b>Driver Name </b> <a class="pull-right"> {{$ddet->name}}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Driver Mobile</b> <a class="pull-right">{{$ddet->mobile}}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Route No </b> <a class="pull-right"> {{$route_no}}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Route Name</b> <a class="pull-right">{{$route_name}}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Pickup Date</b> <a class="pull-right">{{ \Carbon\Carbon::parse($det->date)->format('d M Y')}}</a>
                    </li>

                </ul>
                <div class="row">
                    <div class="col-md-12">
                        <div class="box box-widget">
                            <div class="box-header with-border">
                                <div class="user-block">
                                    <span class="username" style="margin-left: 0px;"><a href="#">{{count($roster_emp)}} Passengers</a></span>
                                </div>
                                <!-- /.user-block -->

                                <!-- /.box-tools -->
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">

                                @foreach ($roster_emp as $key=>$item)
                                <p>{{$key+1}}) &nbsp;Employee name : <b>{{$item->employee_name}}</b></p>
                                <p> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Pick up Time : <b>{{ \Carbon\Carbon::parse($item->pickup_time)->format('h:i A')}}</b></p>
                                <p> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Pick up location : <b>{{$item->location}}</b></p>
                                <p> {{$item->address}}</p>
                                @if($item->map!='')
                                <p> <a href="{{$item->map}}" class="btn btn-success btn-xs">Direction</a></p>
                                @endif
                                <hr>
                                @endforeach

                            </div>

                        </div>
                    </div>
                </div>

                <!--<a href="#" class="btn btn-block btn-primary" data-toggle="modal" data-target="#modal-cancel"><b>Cancel Trip</b></a>
                <a href="#" class="btn btn-block btn-danger" data-toggle="modal" data-target="#modal-emergency"><b>Emergency</b></a>-->
            </div>
            <!-- /.box-body -->
        </div>

    </div>


</div>




@endsection
