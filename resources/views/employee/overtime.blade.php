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
                            <th>Overtime Employee </th>
                            <th>Replace Employee </th>
                            <th>Vehicle </th>
                            <th>Amount </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($list as $item)
                        <tr>
                            <td>{{$item->ot_id}}</td>
                            <td>{{ Carbon\Carbon::parse($item->date)->format('d-M-Y')}}</td>
                            <td>{{$item->over_name}}</td>
                            <td>{{$item->replace_name}}</td>
                            <td>{{$item->vehicle_name}}</td>
                            <td>{{$item->amount}}</td>
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
                <div class="row"  >
                    <form action="/admin/employee/saveovertime" method="post"  enctype="multipart/form-data" class="form-horizontal">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-md-4">Vehicle<span class="required">* </span></label>
                                <div class="col-md-7">
                                    <select name="vehicle_id" style="width: 100%;"  required class="form-control select2" data-placeholder="Select Employee...">
                                        <option value="">Select vehicle</option>
                                        @foreach ($vehicle_list as $item)
                                        <option value="{{$item->vehicle_id}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-4">Overtime Employee<span class="required">* </span></label>
                                <div class="col-md-7">
                                    <select name="over_employee_id" style="width: 100%;" required class="form-control select2" data-placeholder="Select Employee...">
                                        <option value="">Select Employee</option>
                                        @foreach ($employee_list as $item)
                                        <option value="{{$item->employee_id}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">Replace Employee<span class="required">* </span></label>
                                <div class="col-md-7">
                                    <select name="replace_employee_id" style="width: 100%;"  required class="form-control select2" data-placeholder="Select Employee...">
                                        <option value="">Select Employee</option>
                                        @foreach ($employee_list as $item)
                                        <option value="{{$item->employee_id}}">{{$item->name}}</option>
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
                                <label class="control-label col-md-4">Replace Amount<span class="required"> </span></label>
                                <div class="col-md-7">
                                    <input type="number" pattern="[0-9]*" value="" name="amount" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">Deduct Amount<span class="required"> </span></label>
                                <div class="col-md-7">
                                    <input type="number" pattern="[0-9]*" value="" name="deduct_amount" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">Remark<span class="required"> </span></label>
                                <div class="col-md-7">
                                    <input type="text" id="remark" name="remark"   class="form-control" >
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="modal-footer">
                                    <p id="loaded_n_total"></p>
                                    <a href="" class="btn btn-default pull-right" >Close</a>
                                    <button  type="submit" class="btn btn-primary pull-right" style="margin-right: 10px;">Save</button>
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
