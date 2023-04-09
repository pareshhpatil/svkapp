@extends('layouts.admin')

@section('content')
<div class="row" id="insert">
    <form action="/admin/vehicle/updatesave" method="post" id="customerForm" enctype="multipart/form-data" class="form-horizontal">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="col-md-2"></div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label col-md-4">Vehicle name<span class="required">* </span></label>
                <div class="col-md-7">
                    <input type="text" name="name" required="" value="{{$det->name}}" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4">Brand<span class="required"> </span></label>
                <div class="col-md-7">
                    <input type="text" name="brand" value="{{$det->brand}}" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4">Car type<span class="required">* </span></label>
                <div class="col-md-7">
                    <input type="text" required name="car_type" value="{{$det->car_type}}" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4">Number<span class="required">* </span></label>
                <div class="col-md-7">
                    <input type="text" required name="number" value="{{$det->number}}" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4">Model<span class="required"> </span></label>
                <div class="col-md-7">
                    <input type="text"  name="model" value="{{$det->model}}" class="form-control">
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-4"></div>
                <div class="col-md-7">
                    <h4 id="status"></h4>
                    <p id="loaded_n_total"></p>

                    <a href="/admin/vehicle/list" class="btn btn-default pull-right" >Close</a>
                    <input type="hidden" name="vehicle_id" value="{{$det->vehicle_id}}">
                    <input type="hidden" name="purchase_date" value="2014-01-01">
                    <button id="savebutton" type="submit" class="btn btn-primary pull-right" style="margin-right: 10px;">Save</button>
                </div>
            </div>
        </div>

    </form>
</div>
@endsection
