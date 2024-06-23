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
        <div class="panel panel-primary" id="list">
            <div class="panel-body" style="overflow: auto;">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr class="odd gradeX">
                            <th>ID #</th>
                            <th>Date</th>
                            <th>Vehicle Number </th>
                            <th>Owner </th>
                            <th>Replace With </th>
                            <th>Time </th>
                            <th>Amount </th>
                            <th>Paid? </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($list as $item)
                        <tr>
                            <td>{{$item->id}}</td>
                            <td>{{ Carbon\Carbon::parse($item->date)->format('d-M-Y')}}</td>
                            <td>{{$item->vehicle_number}}</td>
                            <td>{{$item->owner_name}}</td>
                            <td>{{$item->vehicle_name}}</td>
                            <td>{{$item->in_time}} To {{$item->out_time}}</td>
                            <td>{{$item->amount}}</td>
                            <td>@if($item->is_paid==1)Yes @else No @endif</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <!-- /.table-responsive -->
            </div>
            <!-- /.panel-body -->
        </div>

        <div class="panel panel-primary" style="display: none;" id="insert">
            <div class="panel-body" style="overflow: auto;">
                <div class="row">
                    <form action="/admin/vehicle/savereplacecab" method="post" enctype="multipart/form-data" class="form-horizontal">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-md-4">Vehicle Number<span class="required">* </span></label>
                                <div class="col-md-7">
                                    <select name="vehicle_number" style="width: 100%;" required class="form-control select2" data-placeholder="Select...">
                                        <option value="">Select vehicle</option>
                                        @foreach ($vehicle_list as $item)
                                        <option value="{{$item->number}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">Owner name<span class="required">* </span></label>
                                <div class="col-md-7">
                                    <input type="text" name="owner" required="" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">Replace With Vehicle<span class="required">* </span></label>
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
                                <label class="control-label col-md-4">In Time</label>
                                <div class="col-md-7">
                                    <div class="bootstrap-timepicker">
                                        <div class="">
                                            <div class="input-group">
                                                <input type="text" required="" readonly="" value="08:00 AM" name="in_time" class="form-control timepicker">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-clock-o"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">Out Time</label>
                                <div class="col-md-7">
                                    <div class="bootstrap-timepicker">
                                        <div class="">
                                            <div class="input-group">
                                                <input type="text" required="" readonly="" value="08:00 PM" name="out_time" class="form-control timepicker">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-clock-o"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">Date<span class="required"> </span></label>
                                <div class="col-md-7">
                                    <input type="text" name="date" readonly="" value="{{$current_date}}" autocomplete="off" class="form-control form-control-inline date-picker" data-date-format="dd M yyyy">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">Amount<span class="required">* </span></label>
                                <div class="col-md-7">
                                    <input type="number" pattern="[0-9]*" value="" name="amount" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">Is paid?<span class="required"> </span></label>
                                <div class="col-md-7">
                                    <label><input type="checkbox" value="1" name="is_paid"> Paid</label>
                                </div>
                            </div>


                            <div class="form-group">
                                <label class="control-label col-md-4">Remark<span class="required">* </span></label>
                                <div class="col-md-7">
                                    <input type="text" id="remark" name="remark" class="form-control">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="modal-footer">
                                    <p id="loaded_n_total"></p>
                                    <a href="" class="btn btn-default pull-right">Close</a>
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