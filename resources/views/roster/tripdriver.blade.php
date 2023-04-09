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
                <h3 class="profile-username text-center">{{$vdet->number}}</h3>
                <ul class="list-group list-group-unbordered" style="margin-bottom: 0px;">
                    <li class="list-group-item">
                        <b>Route No </b> <a class="pull-right"> {{$route_no}}</a>
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
                                <p> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {{$det->pickupdrop}} location : <b>{{$item->location}}</b></p>
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
                <a href="tel:9769151942" class="btn btn-block btn-danger"><b>Call Supervisor</b></a>
                <!--<a href="#" class="btn btn-block btn-primary" data-toggle="modal" data-target="#modal-cancel"><b>Cancel Trip</b></a>
                <a href="#" class="btn btn-block btn-danger" data-toggle="modal" data-target="#modal-emergency"><b>Emergency</b></a>-->
            </div>
            <!-- /.box-body -->
        </div>

    </div>


</div>


<div class="modal fade" id="modal-review">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Passengers Review</h4>
            </div>
            <div class="modal-body">
                <form action="/roster/reviewsave" method="post" id="customerForm" enctype="multipart/form-data" class="form-horizontal">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label col-md-3">Review<span class="required">* </span></label>
                            <div class="col-md-8">
                                <textarea type="text" name="note" required="" value="" class="form-control"></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-4"></div>
                            <div class="col-md-7">
                                <h4 id="status"></h4>
                                <p id="loaded_n_total"></p>
                                <input type="hidden" name="name" required="" value="{{$ddet->name}}" class="form-control">
                                <input type="hidden" name="type" value="Review">
                                <input type="hidden" name="roster_id" value="{{$roster_id}}">
                                <input type="hidden" name="link" value="{{$link}}">
                                <button type="button" data-dismiss="modal" aria-label="Close" class="btn btn-default pull-right" >Close</button>
                                <button id="savebutton" type="submit" class="btn btn-primary pull-right" style="margin-right: 10px;">Save</button>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

@endsection
