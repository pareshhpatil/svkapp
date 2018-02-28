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
    <div class="col-md-5 form-horizontal">
      
        <div class="row">
            <div class="form-group">
                <label class="control-label col-md-4">Pan</label>
                <div class="col-md-7">
                    {{$det->pan}}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="form-group">
                <label class="control-label col-md-4">Adharcard<span class="required"> </span></label>
                <div class="col-md-7">
                    {{$det->adharcard}}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="form-group">
                <label class="control-label col-md-4">Address<span class="required"> </span></label>
                <div class="col-md-7">
                    {{$det->address}}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="form-group">
                <label class="control-label col-md-4">License<span class="required"> </span></label>
                <div class="col-md-7">
                    {{$det->license}}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="form-group">
                <label class="control-label col-md-4">Salary day<span class="required"> </span></label>
                <div class="col-md-7">
                     {{$det->payment_day}}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="form-group">
                <label class="control-label col-md-4">Bank account no.<span class="required"> </span></label>
                <div class="col-md-7">
                    {{$det->account_no}}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="form-group">
                <label class="control-label col-md-4">Account holder name<span class="required"> </span></label>
                <div class="col-md-7">
                    {{$det->account_holder_name}}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="form-group">
                <label class="control-label col-md-4">Bank name<span class="required"> </span></label>
                <div class="col-md-7">
                   {{$det->bank_name}}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="form-group">
                <label class="control-label col-md-4">Account type<span class="required"> </span></label>
                <div class="col-md-7">
                    {{$det->account_type}}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="form-group">
                <label class="control-label col-md-4">IFSC code<span class="required"> </span></label>
                <div class="col-md-7">
                   {{$det->ifsc_code}}
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
