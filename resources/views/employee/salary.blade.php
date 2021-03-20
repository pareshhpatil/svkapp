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
        <div class="panel panel-primary" id="list" @if($insert==1) style="display: none;" @endif>
             <div class="panel-body" style="overflow: auto;">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr class="odd gradeX">
                            <th>ID #</th>
                            <th>Date</th>
                            <th>Month </th>
                            <th>Employee name </th>
                            <th>Salary amount </th>
                            <th>Paid amount </th>
                            <th>Action </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($list as $item)
                        <tr>
                            <td>{{$item->salary_id}}</td>
                            <td>{{ Carbon\Carbon::parse($item->salary_date)->format('d-M-Y')}}</td>
                            <td>{{ Carbon\Carbon::parse($item->salary_month)->format('M-Y')}}</td>
                            <td><a target="_BLANK" href="/admin/employee/view/{{$item->emp_link}}" >{{$item->employee_name}}</a></td>
                            <td>{{$item->salary_amount}}</td>
                            <td>{{$item->paid_amount}}</td>
                            <td> 
                                <a href="/admin/employee/salarydetail/{{$item->link}}" class="btn btn-xs btn-primary"><i class="fa fa-table"></i></a>
                                @if($item->is_paid==0)
                                <a href="/admin/payment/salary/{{$item->link}}" class="btn btn-xs btn-success"><i class="fa fa-money"></i></a>
                                <a href="#" onclick="document.getElementById('deleteanchor').href = '/admin/salary/delete/{{$item->link}}'" class="btn btn-xs btn-danger" data-toggle="modal" data-target="#modal-danger"><i class="fa fa-remove"></i></a>

                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <!-- /.table-responsive -->
            </div>
            <!-- /.panel-body -->
        </div>

        <div class="panel panel-primary" @if($insert==0) style="display: none;" @endif id="insert">
             <div class="panel-body" style="overflow: auto;">
                <div class="panel panel-primary">
                    <div class="panel-body" style="overflow: auto;">
                        <div class="row"  >
                            <form action="" method="post" class="form-horizontal">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <div class="col-md-12">
                                    <div class="col-md-3">
                                        <input type="text" name="date" readonly="" required="" value="{{$month}}" autocomplete="off" class="form-control form-control-inline month-picker" data-date-format="M yyyy" >
                                        <div class="help-block"></div>
                                    </div>
                                    <div class="col-md-3">
                                        <select name="employee_id" required class="form-control" data-placeholder="Select...">
                                            <option value="">Select Employee</option>
                                            @foreach ($employee_list as $item)
                                            <option @if($employee_id==$item->employee_id) selected @endif value="{{$item->employee_id}}">{{$item->name}}</option>
                                            @endforeach
                                        </select>
                                        <div class="help-block"></div>
                                    </div>
                                    <div class="col-md-3">
                                        <button  type="submit" class="btn btn-primary">Generate salary </button>
                                        <a href="/admin/employee/salary" class="btn btn-default">Back </a>
                                    </div>
                                    <br>
                                    <br>
                                </div>

                            </form>
                        </div>
                    </div>
                    @isset($det)
                    <form action="" method="post" class="form-horizontal">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="row">

                            <div class="col-md-12">

                                <!-- Profile Image -->
                                <div class="box box-primary">
                                    <div class="col-md-4">
                                        <div class="box-body box-profile">
                                            <div style="width: 100%; text-align: center;">
                                                @if($det->photo!='')
                                                <img style="display: inline;" class="img-responsive " src="{{ asset('dist/uploads/employee/'.$det->photo) }}" alt="User profile picture">
                                                @else
                                                <img style="display: inline;" class="img-responsive " src="{{ asset('dist/img/avatar5.png') }}" alt="User profile picture">
                                                @endif
                                            </div>

                                            <h3 class="profile-username text-center">{{$det->name}}</h3>
                                            <p class="text-muted text-center">Driver</p>


                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="box-body box-profile">


                                            <ul class="list-group list-group-unbordered">
                                                <li class="list-group-item">
                                                    <b>Employee code</b> <a class="pull-right">{{$det->employee_code}}</a>
                                                </li>
                                                <li class="list-group-item">
                                                    <b>Email</b> <a class="pull-right">{{$det->email}}</a>
                                                </li>
                                                <li class="list-group-item">
                                                    <b>Mobile</b> <a class="pull-right">{{$det->mobile}}</a>
                                                </li>
                                                <li class="list-group-item">
                                                    <b>Salary</b> <a class="pull-right">{{$det->payment}}</a>
                                                </li>
                                                <li class="list-group-item">
                                                    <b>Payment Day</b> <a class="pull-right">{{$det->payment_day}}Th Of Month</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <!-- /.box-body -->
                                </div>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="box">
                                    <div class="box-header">
                                        <h3 class="box-title">Advance details</h3>
                                    </div>
                                    <!-- /.box-header -->
                                    <div class="box-body no-padding">
                                        <table class="table table-condensed">
                                            <tbody><tr>
                                                    <th style="width: 10%;">#</th>
                                                    <th style="width: 20%;">Date</th>
                                                    <th style="width: 20%;">Amount deduct</th>
                                                    <th  style="width: 30%;">Note</th>
                                                    <th  style="width: 20%;">Include in salary</th>
                                                </tr>
                                                @foreach ($advance_list as $item)
                                                @if($item->is_adjust==0)
                                                <tr>
                                                    <td>{{$item->advance_id}}</td>
                                                    <td>{{ Carbon\Carbon::parse($item->date)->format('d-M-Y')}}</td>
                                                    <td>{{$item->amount}} <input type="hidden" id="adv{{$item->advance_id}}" value="{{$item->amount}}"> </td>
                                                    <td>{{$item->note}}</td>
                                                    <td><label><input type="checkbox" name="advance_id[]" onchange="calculateSalary();"  value="{{$item->advance_id}}"> Include</label></td>
                                                </tr>
                                                @endif
                                                @endforeach
                                            </tbody></table>
                                    </div>
                                    <!-- /.box-body -->
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="box">
                                    <div class="box-header">
                                        <h3 class="box-title">Over Time details</h3>
                                    </div>
                                    <!-- /.box-header -->
                                    <div class="box-body no-padding">
                                        <table class="table table-condensed">
                                            <tbody><tr>
                                                    <th style="width: 10%;" >#</th>
                                                    <th style="width: 20%;">Date</th>
                                                    <th style="width: 20%;">Amount</th>
                                                    <th style="width: 30%;" >Note</th>
                                                    <th style="width: 20%;" >Include in salary</th>
                                                </tr>
                                                @foreach ($overtime_list as $item)
                                                @if($item->is_used==0)
                                                <tr>
                                                    <td>{{$item->ot_id}}</td>
                                                    <td>{{ Carbon\Carbon::parse($item->date)->format('d-M-Y')}}</td>
                                                    <td>{{$item->amount}}<input type="hidden" id="ot{{$item->ot_id}}" value="{{$item->amount}}"></td>
                                                    <td>{{$item->note}}</td>
                                                    <td><label><input type="checkbox" onchange="calculateSalary();"  name="overtime_id[]" value="{{$item->ot_id}}"> Include</label></td>
                                                </tr>
                                                @endif
                                                @endforeach
                                            </tbody></table>
                                    </div>
                                    <!-- /.box-body -->
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="box">
                                    <div class="box-header">
                                        <h3 class="box-title">Absent details</h3>
                                    </div>
                                    <!-- /.box-header -->
                                    <div class="box-body no-padding">
                                        <table class="table table-condensed">
                                            <tbody><tr>
                                                    <th style="width: 10%;" >#</th>
                                                    <th style="width: 20%;">Date</th>
                                                    <th style="width: 20%;">Amount deduct</th>
                                                    <th style="width: 30%;" >Note</th>
                                                    <th style="width: 20%;" >Include in salary</th>
                                                </tr>
                                                @foreach ($absent_list as $item)
                                                @if($item->is_deduct==0)
                                                <tr>
                                                    <td>{{$item->absent_id}}</td>
                                                    <td>{{ Carbon\Carbon::parse($item->date)->format('d-M-Y')}}</td>
                                                    <td><input type="text" name="absent_amount[]" onblur="calculateSalary();" id="abs{{$item->absent_id}}" value="<?php echo round($det->payment / 30, 2); ?>" class="form-control"></td>
                                                    <td>{{$item->note}}</td>
                                                    <td><label>
                                                            <input type="checkbox" onchange="calculateSalary();" name="absent_id[]" value="{{$item->absent_id}}"> Include
                                                        </label>
                                                        <input type="hidden" name="absent_idint[]" value="{{$item->absent_id}}">
                                                    </td>

                                                </tr>
                                                @endif
                                                @endforeach
                                            </tbody></table>
                                    </div>
                                    <!-- /.box-body -->
                                </div>
                            </div>
                        </div>
                        <div class="row"  >

                            <div class="col-md-2"></div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-4">Date<span class="required"> </span></label>
                                    <div class="col-md-7">
                                        <input type="text" name="date" readonly="" value="{{$current_date}}" autocomplete="off" class="form-control form-control-inline date-picker" data-date-format="dd M yyyy" >
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-4">Salary amount<span class="required"> </span></label>
                                    <div class="col-md-7">
                                        <input type="number" pattern="[0-9]*" readonly="" id="salary" value="{{$det->payment}}" name="amount"  class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-4">Advance amount<span class="required"> </span></label>
                                    <div class="col-md-7">
                                        <input type="number" pattern="[0-9]*" readonly=""  id="adv_amt" name="advance_amount"  class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-4">Overtime amount<span class="required"> </span></label>
                                    <div class="col-md-7">
                                        <input type="number" pattern="[0-9]*" readonly=""  id="ot_amt" name="overtime_amount"  class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-4">Absent amount<span class="required"> </span></label>
                                    <div class="col-md-7">
                                        <input type="number" pattern="[0-9]*" readonly=""  id="abs_amt" name="absent_total_amount"  class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-4">Paid amount<span class="required"> </span></label>
                                    <div class="col-md-7">
                                        <input type="number" pattern="[0-9]*"  step="0.01" id="paid_amt" name="paid_amount" value="{{$det->payment}}"  class="form-control">
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
                                        <input type="hidden" name="employee_id" value="{{$employee_id}}">
                                        <input type="hidden" name="salary_month" value="{{$month}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    @endisset
                    <!-- /.panel-body -->
                </div>

            </div>
        </div>
        <!-- /.panel-body -->
    </div>
    <!-- /.panel -->
</div>
<!-- /.col-lg-12 -->
</div>
@endsection

<div class="modal modal-danger fade" id="modal-danger">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Delete Amount</h4>
            </div>
            <div class="modal-body">
                <p>Are you sure you would not like to use this vehicle in the future?</p>
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