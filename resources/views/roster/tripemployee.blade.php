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
                        <b>Pickup Date Time</b> <a class="pull-right">{{ \Carbon\Carbon::parse($det->date)->format('d M Y')}} {{ \Carbon\Carbon::parse($pdet->pickup_time)->format('h:i A')}}</a>
                    </li>
                    @if($det->pickupdrop=='Pickup')
                    <li class="list-group-item">
                        <b>Pickup Location</b> <a class="pull-right">{{$edet->location}}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Drop Location</b> <a class="pull-right">Office</a>
                    </li>
                    @else
                    <li class="list-group-item">
                        <b>Pickup Location</b> <a class="pull-right">Office</a>
                    </li>
                    <li class="list-group-item">
                        <b>Drop Location</b> <a class="pull-right">{{$edet->location}}</a>
                    </li>
                    @endif

                </ul>
                <div class="row">
                    <div class="col-md-12">
                        <div class="box box-widget">
                            <div class="box-header with-border">
                                <div class="user-block" >
                                    <span class="username" style="margin-left: 0px;"><a href="#">{{count($roster_emp)}} Passengers</a></span>
                                </div>
                                <!-- /.user-block -->

                                <!-- /.box-tools -->
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">

                                @foreach ($roster_emp as $key=>$item)
                                <p>{{$key+1}}) &nbsp;Employee name : <b>{{$item->employee_name}}</b></p>
                                <p> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {{$det->pickupdrop}} location : <b>{{$item->location}}</b></p>
                                <p> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Pick up Time : <b>{{ \Carbon\Carbon::parse($item->pickup_time)->format('h:i A')}}</b></p>
                                @if($item->map!='')
                                <p> <a href="{{$item->map}}" class="btn btn-success btn-xs">Direction</a></p>
                                @endif
                                                                                 <hr>
                  @endforeach

                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <h5 id="rating_text">Trip Rating</h5>

                                    <div class="rate">
                            <input  onchange="rosterRating('{{$roster_emp_link}}', this.value);" type="radio" id="star5" name="rate" value="5" />
                    <label for="star5" title="Awesome">5 stars</label>
                            <input  onchange="rosterRating('{{$roster_emp_link}}', this.value);" type="radio" id="star4" name="rate" value="4" />
                            <label for="star4" title="Good">4 stars</label>
                            <input  onchange="rosterRating('{{$roster_emp_link}}', this.value);" type="radio" id="star3" name="rate" value="3" />
                    <label for="star3" title="Ok">3 stars</label>
                    <input  onchange="rosterRating('{{$roster_emp_link}}', this.value);" type="radio" id="star2" name="rate" value="2" />
                    <label  for="star2" title="Bad">2 stars</label>
                    <input onchange="rosterRating('{{$roster_emp_link}}', this.value);" type="radio" id="star1" name="rate" value="1" />
                    <label for="star1" title="Disaster">1 star</label>
                </div>
            </div>
    </div>

    <a href="#" class="btn btn-block btn-success" data-toggle="modal" data-target="#modal-review"><b>Review</b></a>
    <a href="#" class="btn btn-block btn-warning" data-toggle="modal" data-target="#modal-complaint"><b>Complaint</b></a>
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
                                <input type="hidden" name="name" required="" value="{{$edet->employee_name}}" class="form-control">
                                <input type="hidden" name="trip_type" value="2">
                                <input type="hidden" name="type" value="Review">
                                <input type="hidden" name="vehicle" value="{{$vdet->number}}">
                                <input type="hidden" name="driver" value="{{$ddet->name}}">
                                <input type="hidden" name="trip_id" value="{{$roster_id}}">
                                <input type="hidden" name="link" value="{{$roster_emp_link}}">
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
<div class="modal fade" id="modal-complaint">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Passengers Complaints</h4>
            </div>
            <div class="modal-body">
                <form action="/roster/reviewsave" method="post" id="customerForm" enctype="multipart/form-data" class="form-horizontal">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="col-md-12">

                        <div class="form-group">
                            <label class="control-label col-md-3">Complaints<span class="required">* </span></label>
                            <div class="col-md-8">
                                <textarea type="text" name="note" required="" value="" class="form-control"></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-4"></div>
                            <div class="col-md-7">
                                <h4 id="status"></h4>
                                <p id="loaded_n_total"></p>
                                <input type="hidden" name="name" required="" value="{{$edet->employee_name}}" class="form-control">
                                <input type="hidden" name="trip_type" value="2">
                                <input type="hidden" name="type" value="Complaint">
                                <input type="hidden" name="vehicle" value="{{$vdet->number}}">
                                <input type="hidden" name="driver" value="{{$ddet->name}}">
                                <input type="hidden" name="trip_id" value="{{$roster_id}}">
                                <input type="hidden" name="link" value="{{$roster_emp_link}}">
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
