@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-md-3">

        <!-- Profile Image -->
        <div class="box box-primary">
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

                <ul class="list-group list-group-unbordered">
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
                        <b>Joining date</b> <a class="pull-right">{{$det->join_date}}</a>
                    </li>
                </ul>

                <a href="/admin/employee/list" class="btn btn-primary btn-block"><b>List</b></a>
            </div>
            <!-- /.box-body -->
        </div>

    </div>
    <div class="col-md-9 form-horizontal">
        <div class="row">
            <div class="form-group">
                <div class=" col-md-4">Pan : {{$det->pan}}</div>
                <div class=" col-md-4">Adharcard :{{$det->adharcard}}</div>
                <div class=" col-md-4">Address : {{$det->address}}</div>
            </div>
        </div>
        <div class="row">
            <div class="form-group">
                <div class=" col-md-4">License :{{$det->license}}</div>
                <div class=" col-md-4">Salary day : {{$det->payment_day}}</div>
                <div class=" col-md-4">Bank account no. : {{$det->account_no}}</div>
            </div>
        </div>

        <div class="row">
            <div class="form-group">
                <div class=" col-md-4">Acc holder name : {{$det->account_holder_name}}</div>
                <div class=" col-md-4">Bank name : {{$det->bank_name}} ({{$det->account_type}})</div>
                <div class=" col-md-4">IFSC code : {{$det->ifsc_code}}</div>
            </div>
        </div>

        <div class="panel panel-primary">
            <h4 style="margin-left: 10px;">Payment Transaction <span class="pull-right" style="color: darkgreen;margin-right: 20px;"><b>Balance:  {{$det->balance}}</b></span></h4>
            
            <div class="panel-body" style="overflow: auto;max-height: 450px;">
                
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Paid Date </th>
                            <th>Amount </th>
                            <th>Mode </th>
                            <th>Narrative </th>
                        </tr>
                    </thead>
                    <tbody>
						@php($total_amount = 0)
                        @foreach ($transaction_list as $item)
                        @if($item->status==1)
                        <tr class="odd gradeX">
                            <td>{{$item->transaction_id}}</td>
                            <td>{{$item->paid_date}}</td>
							@php ($total_amount = $total_amount+$item->amount)
                            <td>{{$item->amount}}</td>
                            <td>{{$item->payment_mode}}</td>
                            <td>{{$item->narrative}}</td>
                            
                        </tr>
                        @endif
                        @endforeach
                    </tbody>
					<tfoot>
                        <tr class="odd gradeX">
                            <td colspan="2"><b>Total</b></td>

                            <td><b>{{$total_amount}}</b></td>
                            <td></td>
                            <td></td>
                            
                        </tr>
                    </tfoot>
                </table>
                <!-- /.table-responsive -->
            </div>
            <!-- /.panel-body -->
        </div>
        <div class="panel panel-primary">
            <h4 style="margin-left: 10px;">Payment Request</h4>
            
            <div class="panel-body" style="overflow: auto;max-height: 450px;">
                
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Date </th>
                            <th>Amount </th>
                            <th>Narrative </th>
                        </tr>
                    </thead>
                    <tbody>
					@php($total_amount = 0)
                        @foreach ($request_list as $item)
                        <tr class="odd gradeX">
                            <td>{{$item->request_id}}</td>
                            <td>{{$item->date}}</td>
							@php ($total_amount = $total_amount+$item->amount)
                            <td>{{$item->amount}}</td>
                            <td>{{$item->note}}</td>
                            
                        </tr>
                        @endforeach
                    </tbody>
					<tfoot>
                        <tr class="odd gradeX">
                            <td colspan="2"><b>Total</b></td>

                            <td><b>{{$total_amount}}</b></td>
                            <td></td>
                            <td></td>
                            
                        </tr>
                    </tfoot>
                </table>
                <!-- /.table-responsive -->
            </div>
            <!-- /.panel-body -->
        </div>
    </div>

</div>
@endsection
