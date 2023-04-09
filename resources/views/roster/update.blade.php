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
                    <form action="/admin/roster/updatesaveroster" method="post" id="logsheetform" class="form-horizontal">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="col-md-8 ">
                            <div class="form-group">
                                <label class="control-label col-md-4">Date<span class="required"> </span></label>
                                <div class="col-md-7">
                                    <input type="text" name="date" readonly="" value="{{$roster_det->date}}" autocomplete="off" class="form-control form-control-inline date-picker" data-date-format="dd M yyyy" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">Pickup/Drop<span class="required">* </span></label>
                                <div class="col-md-7">
                                    <select name="pickup" required class="form-control" data-placeholder="Select...">
                                        <option @if($roster_det->pickupdrop=='Pickup') selected @endif value="Pickup">Pickup</option>
                                        <option @if($roster_det->pickupdrop=='Drop') selected @endif value="Drop">Drop</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">Route<span class="required">* </span></label>
                                <div class="col-md-7">
                                    <select name="route_id" id="route" required class="form-control" data-placeholder="Select...">
                                        <option value="">Select Route</option>
                                        @foreach ($route_list as $item)
                                        <option @if($roster_det->route_id==$item->route_id) selected @endif value="{{$item->route_id}}">{{$item->name}}</option>
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
                                        
                                        <option @if($roster_employee[0]->employee_id==$item->id) selected @endif   value="{{$item->id}}">{{$item->project_name}} - {{$item->employee_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <div class="bootstrap-timepicker">
                                        <div class="">
                                            <div class="input-group">
                                                <input type="text" required="" readonly="" value="@if(count($roster_employee)>0){{$roster_employee[0]->time}}@else 08:00 AM @endif" name="shift_time[]" class="form-control timepicker">
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
                                        <option @if(count($roster_employee)>1) @if($roster_employee[1]->employee_id==$item->id) selected @endif @endif  value="{{$item->id}}">{{$item->project_name}} - {{$item->employee_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <div class="bootstrap-timepicker">
                                        <div class="">
                                            <div class="input-group">
                                                <input type="text" required="" readonly="" value="@if(count($roster_employee)>1){{$roster_employee[1]->time}}@else 08:00 AM @endif" name="shift_time[]" class="form-control timepicker">
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
                                        <option @if(count($roster_employee)>2) @if($roster_employee[2]->employee_id==$item->id) selected @endif @endif value="{{$item->id}}">{{$item->project_name}} - {{$item->employee_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <div class="bootstrap-timepicker">
                                        <div class="">
                                            <div class="input-group">
                                                <input type="text" required="" readonly="" value="@if(count($roster_employee)>2){{$roster_employee[2]->time}}@else 08:00 AM @endif" name="shift_time[]" class="form-control timepicker">
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
                                        <option @if(count($roster_employee)>3) @if($roster_employee[3]->employee_id==$item->id) selected @endif @endif  value="{{$item->id}}">{{$item->project_name}} - {{$item->employee_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <div class="bootstrap-timepicker">
                                        <div class="">
                                            <div class="input-group">
                                                <input type="text" required="" readonly="" value="@if(count($roster_employee)>3){{$roster_employee[3]->time}}@else 08:00 AM @endif" name="shift_time[]" class="form-control timepicker">
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
                                        <option @if(count($roster_employee)>4) @if($roster_employee[4]->employee_id==$item->id) selected @endif @endif  value="{{$item->id}}">{{$item->project_name}} - {{$item->employee_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <div class="bootstrap-timepicker">
                                        <div class="">
                                            <div class="input-group">
                                                <input type="text" required="" readonly="" value="@if(count($roster_employee)>4){{$roster_employee[4]->time}}@else 08:00 AM @endif" name="shift_time[]" class="form-control timepicker">
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
                                        <option @if(count($roster_employee)>5) @if($roster_employee[5]->employee_id==$item->id) selected @endif @endif  value="{{$item->id}}">{{$item->project_name}} - {{$item->employee_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <div class="bootstrap-timepicker">
                                        <div class="">
                                            <div class="input-group">
                                                <input type="text" required="" readonly="" value="@if(count($roster_employee)>5){{$roster_employee[5]->time}}@else 08:00 AM @endif" name="shift_time[]" class="form-control timepicker">
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
                                    <input type="text" id="remark" value="{{$roster_det->narrative}}" name="narrative"   class="form-control" >
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="modal-footer">
                                    <div class="alert alert-success" id="suss" style="display: none;">
                                        <button type="button" class="close" onclick="document.getElementById('suss').style.display = 'none';"></button>
                                        <strong id="status">Success!</strong>  
                                    </div> 
                                    <p id="loaded_n_total"></p>
                                    <a href="/admin/roster/list" class="btn btn-default pull-right" >Close</a>
                                    <input type="hidden" name="roster_id" value="{{$roster_det->roster_id}}">
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



@endsection
