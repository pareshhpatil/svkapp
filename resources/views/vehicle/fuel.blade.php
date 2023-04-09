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
        <div @if($add==true) style="display: none;" @endif id="list">
            <div class="panel panel-primary">
                <div class="panel-body" style="overflow: auto;">
                    <div class="row">
                        <form action="" method="post" class="form-horizontal">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <input type="text" name="from_date" readonly="" required="" value="{{$from_date}}" autocomplete="off" class="form-control form-control-inline date-picker" data-date-format="d M yyyy">
                                    <div class="help-block"></div>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" name="to_date" readonly="" required="" value="{{$to_date}}" autocomplete="off" class="form-control form-control-inline date-picker" data-date-format="d M yyyy">
                                    <div class="help-block"></div>
                                </div>
                                <div class="col-md-1">
                                    <button type="submit" class="btn btn-primary">Submit </button>
                                </div>
                                <br>
                            </div>

                        </form>
                    </div>
                </div>
                <!-- /.panel-body -->
            </div>
            <div class="panel panel-primary">
                <div class="panel-body" style="overflow: auto;">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr class="odd gradeX">
                                <th>ID #</th>
                                <th>Date</th>
                                <th>Vehicle Number </th>
                                <th>Vendor </th>
                                <th>Litre </th>
                                <th>Rate </th>
                                <th>Amount </th>
                                <th>Payment from </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($list as $item)
                            <tr>
                                <td>{{$item->fuel_id}}</td>
                                <td>{{ Carbon\Carbon::parse($item->date)->format('d-M-Y')}}</td>
                                <td>{{$item->number}}</td>
                                <td>@if($item->name=='') Siddhivinayak Travels House @else {{$item->name}}@endif</td>
                                <td>{{$item->litre}}</td>
                                <td>{{$item->rate}}</td>
                                <td>{{$item->amount}}</td>
                                <td>{{$item->paymentsource}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <!-- /.table-responsive -->
                </div>
                <!-- /.panel-body -->
            </div>
        </div>

        <div class="panel panel-primary" @if($add==false) style="display: none;" @endif id="insert">
            <div class="panel-body" style="overflow: auto;">
                <div class="row">
                    <form action="/admin/vehicle/fuelsave" method="post" enctype="multipart/form-data" class="form-horizontal">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-md-4">Vehicle<span class="required">* </span></label>
                                <div class="col-md-7">
                                    <select name="vehicle_id" required class="form-control select2" style="width: 100%;" data-placeholder="Select...">
                                        <option value="">Select vehicle</option>
                                        @foreach ($vehicle_list as $item)
                                        <option value="{{$item->vehicle_id}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">Vendor<span class="required"> </span></label>
                                <div class="col-md-7">
                                    <select name="employee_id" onchange="fuelIntrest();" id="employee_id" style="width: 100%;" required class="form-control select2" data-placeholder="Select...">
                                        <option value="0">Siddhivinayak Travels</option>
                                        @foreach ($employee_list as $item)
                                        <option value="{{$item->employee_id}}">{{$item->name}} - {{$item->category}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">Date<span class="required"> </span></label>
                                <div class="col-md-7">
                                    <input type="text" name="date" readonly="" value="{{$current_date}}" autocomplete="off" class="form-control form-control-inline date-picker" data-date-format="dd M yyyy">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">Litre<span class="required">* </span></label>
                                <div class="col-md-7">
                                    <input type="number" onblur="calculateFuel();" required pattern="[0-9]*" id="qty" step="0.01" value="" name="litre" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">Rate<span class="required">* </span></label>
                                <div class="col-md-7">
                                    <input type="number" onblur="calculateFuel();" required pattern="[0-9]*" id="rate" value="" step="0.01" name="rate" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">Amount<span class="required">* </span></label>
                                <div class="col-md-7">
                                    <input type="number" readonly step="0.01" pattern="[0-9]*" id="amt" value="" name="amount" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">Paid from<span class="required">* </span></label>
                                <div class="col-md-7">
                                    <select name="source_id"  required class="form-control select2" style="width: 100%;" data-placeholder="Select...">
                                        <option value="">Select source</option>
                                        @foreach ($source_list as $item)
                                        <option value="{{$item->paymentsource_id}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">Charge intrest? %<span class="required">* </span></label>
                                <div class="col-md-7">
                                    <input type="number" id="intrest" step="0.01" pattern="[0-9]*" name="intrest" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">Remark<span class="required">* </span></label>
                                <div class="col-md-7">
                                    <input type="text" name="note" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">Upload bill<span class="required">* </span></label>
                                <div class="col-md-7">
                                    <input type="file" id="fileupload" accept="image/*" name="uploaded_file">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="modal-footer">
                                    <p id="loaded_n_total"></p>
                                    <a href="fuel" class="btn btn-default pull-right">Close</a>
                                    <button type="submit" class="btn btn-primary pull-right" style="margin-right: 10px;">Save</button>
                                </div>
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