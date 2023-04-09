@extends('layouts.admin')

@section('content')
<div class="row">

    <div class="col-md-2"></div>
    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label col-md-4">Company name<span class="required"> </span></label>
            <div class="col-md-7">
                {{$det->name}}
            </div>
        </div><br>
        <div class="form-group">
            <label class="control-label col-md-4">Email<span class="required"> </span></label>
            <div class="col-md-7">
                {{$det->email}}
            </div>
        </div><br>
        <div class="form-group">
            <label class="control-label col-md-4">GST Number<span class="required"> </span></label>
            <div class="col-md-7">
                {{$det->gst_number}}
            </div>
        </div><br>
        <div class="form-group">
            <label class="control-label col-md-4">Address<span class="required"> </span></label>
            <div class="col-md-7">
                {{$det->address}}
            </div>
        </div><br>

        <div class="form-group">
            <label class="control-label col-md-4">Joining date<span class="required"> </span></label>
            <div class="col-md-7">
                {{$det->join_date}}
            </div>
        </div><br>
        <div class="form-group">
            <div class="col-md-4"></div>
            <div class="col-md-5">
                <h4 id="status"></h4>
                <p id="loaded_n_total"></p>

                <a href="/admin/company/list" class="btn btn-default pull-right" >Close</a>
            </div>
        </div>
    </div>

</div>
@endsection
