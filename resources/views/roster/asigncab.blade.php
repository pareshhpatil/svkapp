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

        <div class="panel panel-primary">
            <div class="panel-body" style="overflow: auto;">
                <div class="row"  >
                    <form action="" method="post" class="form-horizontal">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="col-md-12">
                            <div class="col-md-3">
                                <input type="text" name="from_date" readonly="" required="" value="{{$from_date}}" autocomplete="off" class="form-control form-control-inline date-picker" data-date-format="d M yyyy" >
                                <div class="help-block"></div>
                            </div>
                            <div class="col-md-1">
                                <button  type="submit" class="btn btn-primary">Submit </button>
                            </div>

                            <br>
                            <br>
                        </div>

                    </form>
                </div>
            </div>
            <!-- /.panel-body -->
        </div>
        <div class="panel panel-primary" id="insert">
            <div class="panel-body" style="overflow: auto;">
                <div class="row"  >
                    <form action="/admin/roster/asignsaveroster" method="post" id="logsheetform" class="form-horizontal">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="col-md-8 ">
                            @foreach ($roster_list as $key=>$roster_det)
                            <div class="form-group">
                                <label class="control-label col-md-4">Date : <span class="required"> </span></label>
                                <label class="control-label pull-left">
                                    &nbsp;&nbsp;&nbsp;&nbsp; {{$roster_det->date}}
                                </label>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">Route : <span class="required"> </span></label>
                                <label class="control-label pull-left">
                                    &nbsp;&nbsp;&nbsp;&nbsp;Route {{$roster_det->route_id}}
                                </label>
                            </div>
                            
                            <div class="form-group">
                                <label class="control-label col-md-4">Driver <span class="required">* </span></label>
                                <div class="col-md-7">
                                    <select name="driver_id[]" id="route" required class="form-control select2" data-placeholder="Select...">
                                        <option value="">Select Driver</option>
                                        @foreach ($driver_list as $item)
                                        <option  value="{{$item->driver_id}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="control-label col-md-4">Vehicle <span class="required">* </span></label>
                                <div class="col-md-7">
                                    <select name="vehicle_id[]" id="route" required class="form-control select2" data-placeholder="Select...">
                                        <option value="">Select Vehicle</option>
                                        @foreach ($vehicle_list as $item)
                                        <option  value="{{$item->vehicle_id}}">{{$item->name}} - {{$item->number}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <input type="hidden" name="route_id[]" value="0">
                            <input type="hidden" name="roster_id[]" value="{{$roster_det->roster_id}}">
                            @endforeach
                            
                            
                            <div class="form-group">
                                <div class="modal-footer">
                                    
                                    <p id="loaded_n_total"></p>
                                    <a href="/admin/roster/list" class="btn btn-default pull-right" >Close</a>
                                    
                                    
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
