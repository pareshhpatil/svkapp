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
                    <form action="/admin/sendsms" method="post" id="logsheetform" enctype="multipart/form-data" class="form-horizontal">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="col-md-8 ">
                            <div class="form-group">
                                <label class="control-label col-md-4">Mobile<span class="required"> </span></label>
                                <div class="col-md-7">
                                    <textarea rows="4" name="mobile"   class="form-control" ></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">SMS<span class="required"> </span></label>
                                <div class="col-md-7">
                                    <textarea rows="4" name="sms"   class="form-control" ></textarea>
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
                                    <button type="submit" class="btn btn-primary pull-right" style="margin-right: 10px;">Save</button>
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
