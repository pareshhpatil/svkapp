@extends('layouts.admin')

@section('content')
<div class="row" id="insert">
    <form action="/admin/vendor/updatesave" method="post" id="customerForm" enctype="multipart/form-data" class="form-horizontal">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="col-md-6">
            
            <div class="form-group">
                <label class="control-label col-md-4">Business name<span class="required">* </span></label>
                <div class="col-md-7">
                    <input type="text" name="business_name" required="" value="{{$det->business_name}}" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4">Contact name<span class="required">* </span></label>
                <div class="col-md-7">
                    <input type="text" name="name" required="" value="{{$det->name}}" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4">Mobile<span class="required">* </span></label>
                <div class="col-md-7">
                    <input type="number" pattern="[0-9]*" value="{{$det->mobile}}" name="mobile" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4">Email<span class="required">* </span></label>
                <div class="col-md-7">
                    <input type="email" name="email" value="{{$det->email}}" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4">GST Number<span class="required"></span></label>
                <div class="col-md-7">
                    <input type="text" name="gst_number" value="{{$det->gst_number}}" class="form-control">
                </div>
            </div>
            
            <div class="form-group">
                <label class="control-label col-md-4">Address<span class="required"> </span></label>
                <div class="col-md-7">
                    <textarea value="" name="address" class="form-control">{{$det->address}}</textarea>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-4"></div>
                <div class="col-md-7">
                    <h4 id="status"></h4>
                    <p id="loaded_n_total"></p>
                    <input type="hidden" name="vendor_id" value="{{$det->vendor_id}}">
                    <button id="savebutton" type="submit" class="btn btn-primary">Save</button>
                    <a href="/admin/vendor/list" class="btn btn-default">Close</a>
                </div>
            </div>
           
            
        </div>
        >
    </form>
</div>
@endsection
