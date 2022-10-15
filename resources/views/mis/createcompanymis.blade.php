@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-lg-12">
        @isset($success_message)
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <strong>Success! </strong> {{$success_message}}
        </div>
        @endisset

        <div class="panel panel-primary" id="insert">
            <div class="panel-body" style="overflow: auto;">
                <div class="row">
                    <form action="" id="frmcomp" method="post" class="form-horizontal">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="col-md-6 ">
                            <div class="form-group">
                                <label class="control-label col-md-4">Company name<span class="required"> * </span></label>
                                <div class="col-md-7">
                                    <select onchange="this.form.submit()" name="company_id" required class="form-control select2" data-placeholder="Select...">
                                        <option value="">Select Company</option>
                                        @foreach ($company_list as $item)
                                        @if($item->type>1)
                                        <option @if($company_id==$item->company_id) selected @endif value="{{$item->company_id}}">{{$item->name}}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @if($company_id>0)
        <div class="panel panel-primary" id="insert">
            <div class="panel-body" style="overflow: auto;">
                <div class="row">
                    <form action="" method="post" id="logsheetform" onsubmit="return saveCompanyMis();"  class="form-horizontal">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="col-md-6 ">

                            <div class="form-group">
                                <label class="control-label col-md-4">Date<span class="required"> </span></label>
                                <div class="col-md-7">
                                    <input type="text" name="date" readonly="" value="{{$current_date}}" autocomplete="off" class="form-control form-control-inline date-picker" data-date-format="dd M yyyy">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">Pickup Time</label>
                                <div class="col-md-7">
                                    <div class="bootstrap-timepicker">
                                        <div class="">
                                            <div class="input-group">
                                                <input type="text" required="" readonly="" value="08:00 AM" name="pickup_time" class="form-control timepicker">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-clock-o"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">Drop Time</label>
                                <div class="col-md-7">
                                    <div class="bootstrap-timepicker">
                                        <div class="">
                                            <div class="input-group">
                                                <input type="text" required="" readonly="" value="08:00 AM" name="drop_time" class="form-control timepicker">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-clock-o"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="control-label col-md-4">Vehicle<span class="required"> * </span></label>
                                <div class="col-md-7">
                                    <select name="car_no" required class="form-control select2" data-placeholder="Select...">
                                        <option value="">Select vehicle</option>
                                        @foreach ($vehicle_list as $item)
                                        <option value="{{$item->number}}">{{$item->number}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">Package<span class="required"> * </span></label>
                                <div class="col-md-7">
                                    <select name="zone_id" required class="form-control select2" data-placeholder="Select...">
                                        <option value="">Select package</option>
                                        @foreach ($zone as $item)
                                        <option value="{{$item->zone_id}}">{{$item->zone}} {{$item->car_type}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @if($company_type==3)
                            <div class="form-group">
                                <label class="control-label col-md-4">Office Location<span class="required"> * </span></label>
                                <div class="col-md-7">
                                    <select name="office_location" id="location" required class="form-control select2" data-placeholder="Select...">
                                        <option value="">Select location</option>
                                        @foreach ($location as $item)
                                        <option value="{{$item->name}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">User count<span class="required"> * </span></label>
                                <div class="col-md-7">
                                    <input type="number" required="" pattern="[0-9]*" name="user_count" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">Vendor<span class="required">  </span></label>
                                <div class="col-md-7">
                                    <input type="text"   name="vendor" class="form-control">
                                </div>
                            </div>
                            @endif

                           

                            <div class="form-group">
                                <div class="modal-footer">
                                    <div class="alert alert-success" id="suss" style="display: none;">
                                        <button type="button" class="close" onclick="document.getElementById('suss').style.display = 'none';"></button>
                                        <strong id="status">Success!</strong>
                                    </div>
                                    <p id="loaded_n_total"></p>
                                    <a href="" class="btn btn-default pull-right">Close</a>
                                    <input type="hidden" name="company_id" value="{{$company_id}}">
                                    <button id="savebutton" type="submit" class="btn btn-primary pull-right" style="margin-right: 10px;">Save</button>
                                    <a id="conf" data-toggle="modal" href="#modal-confirm"></a>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
            <!-- /.panel-body -->
        </div>
        @endisset
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>


@endsection