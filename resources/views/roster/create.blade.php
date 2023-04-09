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
                <div class="row"  >
                    <form action="" method="post" id="logsheetform" onsubmit="return saveRoster();" enctype="multipart/form-data" class="form-horizontal">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="col-md-8 ">
                            <div class="form-group">
                                <label class="control-label col-md-4">Date<span class="required"> </span></label>
                                <div class="col-md-7">
                                    <input type="text" name="date" readonly="" value="{{$current_date}}" autocomplete="off" class="form-control form-control-inline date-picker" data-date-format="dd M yyyy" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">Pickup/Drop<span class="required">* </span></label>
                                <div class="col-md-7">
                                    <select name="pickup" required class="form-control" data-placeholder="Select...">
                                        <option value="Pickup">Pickup</option>
                                        <option value="Drop">Drop</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">Route<span class="required">* </span></label>
                                <div class="col-md-7">
                                    <select name="route_id" id="route" required class="form-control" data-placeholder="Select...">
                                        <option value="">Select Route</option>
                                        @foreach ($route_list as $item)
                                        <option value="{{$item->route_id}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-4">Employee 1<span class="required">* </span></label>
                                <div class="col-md-4">
                                    <select name="employee_id[]" id="emp1" required class="form-control select2" data-placeholder="Select...">
                                        <option value="">Select employee</option>
                                        @foreach ($mis_employee as $item)
                                        <option value="{{$item->id}}">{{$item->project_name}} - {{$item->employee_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <div class="bootstrap-timepicker">
                                        <div class="">
                                            <div class="input-group">
                                                <input type="text" required="" readonly="" value="08:00 AM" name="shift_time[]" class="form-control timepicker">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-clock-o"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">Employee 2<span class="required"> </span></label>
                                <div class="col-md-4">
                                    <select name="employee_id[]" id="emp2"  class="form-control select2" data-placeholder="Select...">
                                        <option value="">Select employee</option>
                                        @foreach ($mis_employee as $item)
                                        <option value="{{$item->id}}">{{$item->project_name}} - {{$item->employee_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <div class="bootstrap-timepicker">
                                        <div class="">
                                            <div class="input-group">
                                                <input type="text" required="" readonly="" value="08:00 AM" name="shift_time[]" class="form-control timepicker">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-clock-o"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">Employee 3<span class="required"> </span></label>
                                <div class="col-md-4">
                                    <select name="employee_id[]" id="emp3" class="form-control select2" data-placeholder="Select...">
                                        <option value="">Select employee</option>
                                        @foreach ($mis_employee as $item)
                                        <option value="{{$item->id}}">{{$item->project_name}} - {{$item->employee_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <div class="bootstrap-timepicker">
                                        <div class="">
                                            <div class="input-group">
                                                <input type="text" required="" readonly="" value="08:00 AM" name="shift_time[]" class="form-control timepicker">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-clock-o"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">Employee 4<span class="required"> </span></label>
                                <div class="col-md-4">
                                    <select name="employee_id[]" id="emp4"  class="form-control select2" data-placeholder="Select...">
                                        <option value="">Select employee</option>
                                        @foreach ($mis_employee as $item)
                                        <option value="{{$item->id}}">{{$item->project_name}} - {{$item->employee_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <div class="bootstrap-timepicker">
                                        <div class="">
                                            <div class="input-group">
                                                <input type="text" required="" readonly="" value="08:00 AM" name="shift_time[]" class="form-control timepicker">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-clock-o"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">Employee 5<span class="required"> </span></label>
                                <div class="col-md-4">
                                    <select name="employee_id[]" id="emp5"  class="form-control select2" data-placeholder="Select...">
                                        <option value="">Select employee</option>
                                        @foreach ($mis_employee as $item)
                                        <option value="{{$item->id}}">{{$item->project_name}} - {{$item->employee_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <div class="bootstrap-timepicker">
                                        <div class="">
                                            <div class="input-group">
                                                <input type="text" required="" readonly="" value="08:00 AM" name="shift_time[]" class="form-control timepicker">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-clock-o"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">Employee 6<span class="required"> </span></label>
                                <div class="col-md-4">
                                    <select name="employee_id[]" id="emp6" class="form-control select2" data-placeholder="Select...">
                                        <option value="">Select employee</option>
                                        @foreach ($mis_employee as $item)
                                        <option value="{{$item->id}}">{{$item->project_name}} - {{$item->employee_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <div class="bootstrap-timepicker">
                                        <div class="">
                                            <div class="input-group">
                                                <input type="text" required="" readonly="" value="08:00 AM" name="shift_time[]" class="form-control timepicker">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-clock-o"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="control-label col-md-4">Remark<span class="required"> </span></label>
                                <div class="col-md-7">
                                    <input type="text" id="remark" name="narrative"   class="form-control" >
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="modal-footer">
                                    <div class="alert alert-success" id="suss" style="display: none;">
                                        <button type="button" class="close" onclick="document.getElementById('suss').style.display = 'none';"></button>
                                        <strong id="status">Success!</strong>  
                                    </div> 
                                    <p id="loaded_n_total"></p>
                                    <a href="" class="btn btn-default pull-right" >Close</a>
                                    <button id="savebutton" type="submit" class="btn btn-primary pull-right" style="margin-right: 10px;">Save</button>
                                    <a id="conf" data-toggle="modal"  href="#modal-confirm"></a>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>

<div class="modal fade" id="modal-location">
    <div class="modal-dialog">
        <form action="" method="post" id="locationform" class="form-horizontal">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Add Employee</h4>
                </div>
                <div class="modal-body" >
                    <div class="form-group">
                        <label class="control-label col-md-4">Location<span class="required">* </span></label>
                        <div class="col-md-7">
                            <input type="text" id="mislocation" name="location"   class="form-control" >
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-4">KM<span class="required">* </span></label>
                        <div class="col-md-7">
                            <input type="text" id="location_km" name="location_km"   class="form-control" >
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a onclick="saveMISLocation();" class="btn btn-primary">Save</a>
                    <a  class="btn btn-default" id="closebtn3" data-dismiss="modal">Close</a>
                </div>
            </div>
        </form>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<div class="modal fade" id="modal-employee">
    <div class="modal-dialog">
        <form action="" method="post" id="employeeform" class="form-horizontal">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Add Employee</h4>
                </div>
                <div class="modal-body" >
                    <div class="form-group">
                        <label class="control-label col-md-4">Employee Name<span class="required">* </span></label>
                        <div class="col-md-7">
                            <input type="text" id="emp_name" name="employee_name"   class="form-control" >
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <submit onclick="saveMISEmployee();" class="btn btn-primary">Save</submit>
                    <a  class="btn btn-default" id="closebtn2" data-dismiss="modal">Close</a>
                </div>
            </div>
        </form>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<div class="modal fade" id="modal-confirm">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Confirm Entry</h4>
            </div>
            <div class="modal-body" id="detail">

            </div>
            <div class="modal-footer">
                <a onclick="saveMis();" class="btn btn-primary">Save</a>
                <a  class="btn btn-default" id="closebtn" data-dismiss="modal">Close</a>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
@endsection
