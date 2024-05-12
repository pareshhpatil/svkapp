@extends('layouts.admin')

@section('content')
<div class="">
    <div class="col-lg-12">
        @isset($success_message)
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <strong>Success! </strong> {{$success_message}}
        </div>
        @endisset
        <div class="panel panel-primary" @if($user_type==2)style="display: none;" @endif id="list">
            <div class="panel-body" style="overflow: auto;">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr class="odd gradeX">
                            <th>ID</th>
                            <th>Bill #</th>
                            <th>Vehicle </th>
                            <th>Company </th>
                            <th>Month </th>
                            <th>Bill date </th>
                            <th>GST </th>
                            <th>Total Amount </th>
                            <th style="width: 130px;">Action </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($invoice_list as $item)
                        <tr>
                            <td>{{$item->invoice_id}}</td>
                            <td>{{$item->invoice_number}}</td>
                            <td>{{$item->vehicle_name}}</td>
                            <td>{{$item->company_name}}</td>
                            <td>{{ Carbon\Carbon::parse($item->date)->format('M-Y')}}</td>
                            <td>{{ Carbon\Carbon::parse($item->bill_date)->format('d-M-Y')}}</td>
                            <td>{{$item->total_gst}}</td>
                            <td>{{$item->grand_total}}</td>
                            <td style="width: 130px;">
                                <a target="_BLANK" href="/admin/logsheet/printlogsheet/{{$item->link}}" class="btn btn-xs btn-success"><i class="fa fa-file-excel-o"></i></a>
                                <a target="_BLANK" href="/admin/logsheet/downloadlogsheet/{{$item->link}}" class="btn btn-xs btn-success"><i class="fa fa-download"></i></a>
                                <a target="_BLANK" href="/admin/logsheet/printbill/{{$item->link}}" class="btn btn-xs btn-primary"><i class="fa fa-file-word-o"></i></a>
                                <a target="_BLANK" href="/admin/logsheet/downloadbill/{{$item->link}}" class="btn btn-xs btn-primary"><i class="fa fa-download"></i></a>
                                <a href="/admin/logsheet/generatebill/{{$item->link}}" class="btn btn-xs btn-warning"><i class="fa fa-edit"></i></a>
                                <a href="#" onclick="document.getElementById('deleteanchor').href = '/admin/logsheet_invoice/delete/{{$item->link}}'" class="btn btn-xs btn-danger" data-toggle="modal" data-target="#modal-danger"><i class="fa fa-remove"></i></a>

                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <!-- /.table-responsive -->
            </div>
            
            <!-- /.panel-body -->
        </div>
        <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-2768566574593657"
     crossorigin="anonymous"></script>
<!-- Admin -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-2768566574593657"
     data-ad-slot="5658933431"
     data-ad-format="auto"
     data-full-width-responsive="true"></ins>
<script>
     (adsbygoogle = window.adsbygoogle || []).push({});
</script>

        <div class="panel panel-primary" @if($user_type==1)style="display: none;" @endif id="insert">
            <div class="panel-body" style="overflow: auto;">
                <div class="row"  >
                    <form action="" method="post" id="logsheetform" onsubmit="return confirmlogsheet();" enctype="multipart/form-data" class="form-horizontal">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-md-4">Type<span class="required">* </span></label>
                                <div class="col-md-7">
                                    <label><input checked type="radio" onchange="typechange(this.value);" name="type" value="1"> Default &nbsp;&nbsp;&nbsp;</label>
                                    <label><input type="radio" onchange="typechange(this.value);" name="type" value="2"> Location</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">Vehicle<span class="required">* </span></label>
                                <div class="col-md-7">
                                    <select name="vehicle_id" style="width: 100%;" required class="form-control select2" data-placeholder="Select...">
                                        <option value="">Select vehicle</option>
                                        @foreach ($vehicle_list as $item)
                                        <option value="{{$item->vehicle_id}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4 ">Company<span class="required">* </span></label>
                                <div class="col-md-7">
                                    <select name="company_id" style="width: 100%;" required class="form-control select2" data-placeholder="Select...">
                                        <option value="">Select comapny</option>
                                        @foreach ($company_list as $item)
                                        <option value="{{$item->company_id}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="control-label col-md-4">Date<span class="required"> </span></label>
                                <div class="col-md-7">
                                    <input type="text" name="date" readonly="" value="{{$current_date}}" autocomplete="off" class="form-control form-control-inline date-picker" data-date-format="dd M yyyy" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">Start KM<span class="required">* </span></label>
                                <div class="col-md-7">
                                    <input type="number" id="startkm" required="" pattern="[0-9]*" name="start_km"   class="form-control" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">End KM<span class="required">* </span></label>
                                <div class="col-md-7">
                                    <input type="number" id="endkm" required="" pattern="[0-9]*" name="end_km"   class="form-control" >
                                </div>
                            </div>
                            <div id="normal">
                                <div class="form-group">
                                    <label class="control-label col-md-4">Day/Night<span class="required">* </span></label>
                                    <div class="col-md-7">
                                        <label><input checked type="radio"  name="day_night" value="Day"> Day &nbsp;&nbsp;&nbsp;</label>
                                        <label><input type="radio"  name="day_night" value="Night"> Night</label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-4">Start Time</label>
                                    <div class="col-md-7">
                                        <div class="bootstrap-timepicker">
                                            <div class="">
                                                <div class="input-group">
                                                    <input type="text" required="" readonly="" value="08:00 AM" name="start_time" class="form-control timepicker">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-clock-o"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-4">Close Time</label>
                                    <div class="col-md-7">
                                        <div class="bootstrap-timepicker">
                                            <div class="">
                                                <div class="input-group">
                                                    <input type="text" required="" readonly="" value="08:00 PM" name="close_time" class="form-control timepicker">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-clock-o"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="location" style="display: none;">
                                <div class="form-group">
                                    <label class="control-label col-md-4">Pickup/Drop<span class="required">* </span></label>
                                    <div class="col-md-7">
                                        <label><input checked type="radio" onchange="pickupdrop(this.value);"  name="pickup" value="PICKUP"> PICKUP &nbsp;&nbsp;&nbsp;</label>
                                        <label><input type="radio" onchange="pickupdrop(this.value);"  name="pickup" value="DROP"> DROP</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-4">From </label>
                                    <div class="col-md-7">
                                        <input type="text"  name="from" id="from_loc" value=""  class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-4">To</label>
                                    <div class="col-md-7">
                                        <input type="text"  name="to" id="to_loc" value="Company"  class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">Toll amount<span class="required">* </span></label>
                                <div class="col-md-7">
                                    <input type="number" id="toll" pattern="[0-9]*" name="toll_amount"   class="form-control" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">Remark<span class="required">* </span></label>
                                <div class="col-md-7">
                                    <input type="text" id="remark" name="remark"   class="form-control" >
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
                                    <button id="savebutton"  type="submit" class="btn btn-primary pull-right" style="margin-right: 10px;">Save</button>
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



<div class="modal modal-danger fade" id="modal-danger">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Delete Invoice</h4>
            </div>
            <div class="modal-body">
                <p>Are you sure you would not like to use this invoice in the future?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" data-dismiss="modal">Close</button>
                <a id="deleteanchor" href="" class="btn btn-outline">Delete</a>
            </div>
        </div>
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
                <a onclick="saveLogsheet();" class="btn btn-primary">Save</a>
                <a  class="btn btn-default" id="closebtn" data-dismiss="modal">Close</a>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
@endsection
