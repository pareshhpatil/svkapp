@extends('layouts.employee')

@section('content')

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
                    @if($edet->photo!='')
                    <img style="display: inline;" class="img-responsive " src="{{ asset('dist/uploads/employee/'.$edet->photo) }}" alt="User profile picture">
                    @else
                    <img style="display: inline;" class="img-responsive " src="{{ asset('dist/img/avatar5.png') }}" alt="User profile picture">
                    @endif
                </div>

                <h3 class="profile-username text-center">{{$edet->name}}</h3>
                <ul class="list-group list-group-unbordered" style="margin-bottom: 0px;">
                    <li class="list-group-item">
                        <b>Vehicle Number:</b> <a class="pull-right">({{$vdet->car_type}}) {{$vdet->number}}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Mobile</b> <a class="pull-right">{{$edet->mobile}}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Pickup Time</b> <a class="pull-right">{{ \Carbon\Carbon::parse($det->date)->format('d M Y')}} {{ \Carbon\Carbon::parse($det->time)->format('h:i A')}}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Pickup Location</b> <a class="pull-right">{{$det->pickup_location}}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Drop Location</b> <a class="pull-right">{{$det->drop_location}}</a>
                    </li>
                    <li class="list-group-item">
                        <b>{{$det->total_passengers}} Passengers</b> <a class="pull-right"> {{$det->passengers}}</a>
                    </li>
                </ul>

                @if($rdet->type=='Review')
                <a href="#" class="btn btn-block btn-success" data-toggle="modal" data-target="#modal-review"><b>Review</b></a>
                @else
                <a href="#" class="btn btn-block btn-warning" data-toggle="modal" data-target="#modal-complaint"><b>Complaint</b></a>
                @endif
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
                <form action="/trip/reviewsave" method="post" id="customerForm" enctype="multipart/form-data" class="form-horizontal">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label col-md-3">Passenger Name<span class="required">* </span></label>
                            <div class="col-md-8">
                                {{$rdet->name}}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Review<span class="required">* </span></label>
                            <div class="col-md-8">
                                <p>
                                    {{$rdet->text}}
                                </p>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-4"></div>
                            <div class="col-md-7">
                                <h4 id="status"></h4>
                                <p id="loaded_n_total"></p>
                                <input type="hidden" name="type" value="Review">
                                <input type="hidden" name="trip_id" value="{{$trip_id}}">
                                <input type="hidden" name="link" value="{{$link}}">
                                <button type="button" data-dismiss="modal" aria-label="Close" class="btn btn-default pull-right" >Close</button>
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
                <form action="/trip/reviewsave" method="post" id="customerForm" enctype="multipart/form-data" class="form-horizontal">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label col-md-3">Passenger Name<span class="required"> </span></label>
                            <div class="col-md-8">
                                {{$rdet->name}}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Complaints<span class="required"> </span></label>
                            <div class="col-md-8">
                                <p>{{$rdet->text}}</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-4"></div>
                            <div class="col-md-7">
                                <h4 id="status"></h4>
                                <p id="loaded_n_total"></p>
                                <input type="hidden" name="type" value="Complanints">
                                <input type="hidden" name="trip_id" value="{{$trip_id}}">
                                <input type="hidden" name="link" value="{{$link}}">
                                <button type="button" data-dismiss="modal" aria-label="Close" class="btn btn-default pull-right" >Close</button>
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
