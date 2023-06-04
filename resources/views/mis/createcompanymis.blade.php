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
                                <label class="control-label col-md-4">Logsheet no<span class="required"> * </span></label>
                                <div class="col-md-7">
                                    <input type="text" required="" @if(!empty($det)) value="{{$det->logsheet_no}}" @endif name="logsheet_no" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">Pickup Time</label>
                                <div class="col-md-7">
                                    <div class="bootstrap-timepicker">
                                        <div class="">
                                            <div class="input-group">
                                                <input type="text" required="" readonly="" @if(!empty($det)) value="{{$pickup_time}}" @else  value="08:00 AM" @endif  name="pickup_time" class="form-control timepicker">
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
                                                <input type="text" required="" readonly=""  @if(!empty($det)) value="{{$drop_time}}" @else  value="08:00 AM" @endif name="drop_time" class="form-control timepicker">
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
                                        @if(!empty($det))
                                        <option selected value="{{$car_no}}">{{$car_no}}</option>
                                        @endif  
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">Package<span class="required"> * </span></label>
                                <div class="col-md-7">
                                    <select name="zone_id" required class="form-control select2" data-placeholder="Select...">
                                        <option value="">Select package</option>
                                        @foreach ($zone as $item)
                                        <option @if($cab_zone==$item->zone) selected @endif  value="{{$item->zone_id}}">{{$item->zone}} {{$item->car_type}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

							<div class="form-group">
                                <label class="control-label col-md-4">Toll<span class="required">  </span></label>
                                <div class="col-md-7">
                                    <input type="number" @if(!empty($det)) value="{{$det->toll}}" @endif   pattern="[0-9]*" name="toll" class="form-control">
                                </div>
                            </div>
							<div class="form-group">
                                <label class="control-label col-md-4">Employee name<span class="required">  </span></label>
                                <div class="col-md-7">
                                    <input type="text" @if(!empty($det)) value="{{$det->employee_name}}" @endif   name="employee_name" class="form-control">
                                </div>
                            </div>
							<div class="form-group">
                                <label class="control-label col-md-4">User count<span class="required"> * </span></label>
                                <div class="col-md-7">
                                    <input type="number" required="" @if(!empty($det)) value="{{$det->user_count}}" @endif pattern="[0-9]*" name="user_count" class="form-control">
                                </div>
                            </div>
							
							<div class="form-group">
                                <label class="control-label col-md-4">Pickup/Drop<span class="required"> * </span></label>
                                <div class="col-md-7">
                                    <select name="pickup_drop"  class="form-control select2" data-placeholder="Select...">
                                        <option @if($pickup_drop=='Pickup') selected @endif value="Pickup">Pickup</option>
										<option @if($pickup_drop=='Drop') selected @endif value="Drop">Drop</option>
                                    </select>
                                </div>
                            </div>
							
							<div class="form-group">
                                <label class="control-label col-md-4">Pickup<span class="required"> * </span></label>
                                <div class="col-md-7">
                                    <input type="text" required="" @if(!empty($det)) value="{{$det->pickup_location}}" @endif   name="pickup" class="form-control">
                                </div>
                            </div>
							<div class="form-group">
                                <label class="control-label col-md-4">Drop<span class="required"> * </span></label>
                                <div class="col-md-7">
                                    <input type="text" required="" @if(!empty($det)) value="{{$det->drop_location}}" @endif  name="drop" class="form-control">
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
                                        @if(!empty($det))
                                        <option value="{{$det->office_location}}">{{$det->office_location}}</option>
                                        @endif  
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">Vendor<span class="required">  </span></label>
                                <div class="col-md-7">
                                    <input type="text" @if(!empty($det)) value="{{$det->vendor}}" @endif   name="vendor" class="form-control">
                                </div>
                            </div>
                            @endif
							<div class="form-group">
                                <label class="control-label col-md-4">Remark<span class="required">  </span></label>
                                <div class="col-md-7">
                                    <input type="text" @if(!empty($det)) value="{{$det->remark}}" @endif   name="remark" class="form-control">
                                </div>
                            </div>

                           

                            <div class="form-group">
                                <div class="modal-footer">
                                    <div class="alert alert-success" id="suss" style="display: none;">
                                        <button type="button" class="close" onclick="document.getElementById('suss').style.display = 'none';"></button>
                                        <strong id="status">Success!</strong>
                                    </div>
                                    <p id="loaded_n_total"></p>
                                    <a href="" class="btn btn-default pull-right">Close</a>
                                    <input type="hidden" name="company_id" value="{{$company_id}}">
                                    <input type="hidden" @if(!empty($det)) value="{{$det->id}}" @else value="0" @endif id="id"  name="id" class="form-control">
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