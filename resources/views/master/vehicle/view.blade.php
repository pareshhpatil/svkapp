@extends('layouts.admin')

@section('content')
<div class="row">

    <div class="col-md-2"></div>
    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label col-md-4">Vehicle name<span class="required"> </span></label>
            <div class="col-md-7">
                {{$det->name}}
            </div>
        </div><br>
        <div class="form-group">
            <label class="control-label col-md-4">Brand<span class="required"> </span></label>
            <div class="col-md-7">
                {{$det->brand}}
            </div>
        </div><br>
        <div class="form-group">
            <label class="control-label col-md-4">Car type<span class="required"> </span></label>
            <div class="col-md-7">
                {{$det->car_type}}
            </div>
        </div><br>
        <div class="form-group">
            <label class="control-label col-md-4">Number<span class="required"> </span></label>
            <div class="col-md-7">
                {{$det->number}}
            </div>
        </div><br>
        <div class="form-group">
            <label class="control-label col-md-4">Model<span class="required"> </span></label>
            <div class="col-md-7">
                {{$det->model}}
            </div>
        </div><br>

        
        <div class="form-group">
            <div class="col-md-4"></div>
            <div class="col-md-5">
                <h4 id="status"></h4>
                <p id="loaded_n_total"></p>

                <a href="/admin/vehicle/list" class="btn btn-default pull-right" >Close</a>
            </div>
        </div>
    </div>

</div>
@endsection
