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
                <div class="panel panel-primary">
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
                                                @if($emp_detail->photo!='')
                                                <img style="display: inline;" class="img-responsive " src="{{ asset('dist/uploads/employee/'.$emp_detail->photo) }}" alt="User profile picture">
                                                @else
                                                <img style="display: inline;" class="img-responsive " src="{{ asset('dist/img/avatar5.png') }}" alt="User profile picture">
                                                @endif
                                            </div>

                                            <h3 class="profile-username text-center">{{$emp_detail->name}}</h3>
                                            <div class="box-body box-profile">
                                                <ul class="list-group list-group-unbordered">
                                                    <li class="list-group-item">
                                                        <b>Employee code</b> <a class="pull-right">{{$emp_detail->employee_code}}</a>
                                                    </li>
                                                    <li class="list-group-item">
                                                        <b>Mobile</b> <a class="pull-right">{{$emp_detail->mobile}}</a>
                                                    </li>
                                                </ul>
                                            </div>


                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="box-body box-profile">
                                            <ul class="list-group list-group-unbordered">
                                                <li class="list-group-item">
                                                    <b>Salary Month</b> <a class="pull-right">{{ Carbon\Carbon::parse($det->salary_month)->format('M-Y')}}</a>
                                                </li>
                                                <li class="list-group-item">
                                                    <b>Salary Date</b> <a class="pull-right">{{ Carbon\Carbon::parse($det->salary_date)->format('d-M-Y')}}</a>
                                                </li>
                                                <li class="list-group-item">
                                                    <b>Salary Amount</b> <a class="pull-right">{{$det->salary_amount}}</a>
                                                </li>
                                                <li class="list-group-item">
                                                    <b>Advance Amount</b> <a class="pull-right">{{$det->advance_amount}}</a>
                                                </li>
                                                <li class="list-group-item">
                                                    <b>Overtime Amount</b> <a class="pull-right">{{$det->overtime_amount}}</a>
                                                </li>
                                                <li class="list-group-item">
                                                    <b>Absent Amount</b> <a class="pull-right">{{$det->absent_amount}}</a>
                                                </li>
                                                <li class="list-group-item">
                                                    <b>Total Paid Amount</b> <a class="pull-right">{{$det->paid_amount}}</a>
                                                </li>
                                                <li class="list-group-item">
                                                    <b>Remark</b> <a class="pull-right">{{$det->note}}</a>
                                                </li>

                                            </ul>
                                        </div>
                                    </div>
                                    <!-- /.box-body -->
                                </div>

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4"></div>
                            <div class="col-md-6">
                                @isset($advance_list)
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
                                                            <th style="width: 20%;">Date</th>
                                                            <th style="width: 20%;">Amount deduct</th>
                                                            <th  style="width: 30%;">Note</th>
                                                        </tr>
                                                        @foreach ($advance_list as $item)
                                                        <tr>
                                                            <td>{{ Carbon\Carbon::parse($item->date)->format('d-M-Y')}}</td>
                                                            <td>{{$item->amount}}</td>
                                                            <td>{{$item->note}}</td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody></table>
                                            </div>
                                            <!-- /.box-body -->
                                        </div>
                                    </div>
                                </div>
                                @endisset
                                @isset($overtime_list)
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
                                                            <th style="width: 20%;">Date</th>
                                                            <th style="width: 20%;">Amount</th>
                                                            <th style="width: 30%;" >Note</th>
                                                        </tr>
                                                        @foreach ($overtime_list as $item)
                                                        <tr>
                                                            <td>{{ Carbon\Carbon::parse($item->date)->format('d-M-Y')}}</td>
                                                            <td>{{$item->amount}}</td>
                                                            <td>{{$item->note}}</td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody></table>
                                            </div>
                                            <!-- /.box-body -->
                                        </div>
                                    </div>
                                </div>
                                @endisset
                                @isset($absent_list)
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
                                                            <th style="width: 20%;">Date</th>
                                                            <th style="width: 20%;">Amount deduct</th>
                                                            <th style="width: 30%;" >Note</th>
                                                        </tr>
                                                        @foreach ($absent_list as $item)
                                                        <tr>
                                                            <td>{{ Carbon\Carbon::parse($item->date)->format('d-M-Y')}}</td>
                                                            <td>{{$item->amount_deduct}}</td>
                                                            <td>{{$item->note}}</td>


                                                        </tr>
                                                        @endforeach
                                                    </tbody></table>
                                            </div>
                                            <!-- /.box-body -->
                                        </div>
                                    </div>
                                </div>
                                @endisset
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
