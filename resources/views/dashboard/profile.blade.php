@extends('layouts.admin')

@section('content')
<div class="row" id="insert">
    <form action="/admin/profilesave" method="post" id="customerForm" enctype="multipart/form-data" class="form-horizontal">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="col-md-6">

            <div class="form-group">
                <label class="control-label col-md-4">Company name<span class="required">* </span></label>
                <div class="col-md-7">
                    <input type="text" readonly="" required="" value="{{$det->company_name}}" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4">Name<span class="required">* </span></label>
                <div class="col-md-7">
                    <input type="text" name="name"  required="" value="{{$det->name}}" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4">Mobile<span class="required">* </span></label>
                <div class="col-md-7">
                    <input type="number" required="" pattern="[0-9]*" value="{{$det->mobile}}" name="mobile" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4">Email<span class="required">* </span></label>
                <div class="col-md-7">
                    <input type="email" required="" name="email" value="{{$det->email}}" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4">Address<span class="required"> *</span></label>
                <div class="col-md-7">
                    <textarea value="" required="" name="address" class="form-control">{{$det->address}}</textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4">Pan number<span class="required">* </span></label>
                <div class="col-md-7">
                    <input type="text" required name="pan" value="{{$det->pan_number}}" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4">GST Number<span class="required">* </span></label>
                <div class="col-md-7">
                   <input type="text" required name="gst" value="{{$det->gst_number}}" class="form-control">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-4">SAC Code<span class="required">* </span></label>
                <div class="col-md-7">
                    <input type="text" required="" name="sac" value="{{$det->sac_code}}" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4">Upload photo<span class="required"> </span></label>
                <div class="col-md-7">
                    <input type="file" id="fileupload" accept="image/*" name="uploaded_file">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4"></label>
                <div class="col-md-7">
                    @if($det->logo!='')
                    <img style="display: inline;max-height: 100px;" class="img-responsive " src="{{ asset('dist/img/'.$det->logo) }}" alt="User profile picture">
                    @else
                    <img style="display: inline;max-height: 100px;" class="img-responsive " src="{{ asset('dist/img/avatar5.png') }}" alt="User profile picture">
                    @endif
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-4"></div>
                <div class="col-md-7">
                    <h4 id="status"></h4>
                    <p id="loaded_n_total"></p>
                    <input type="hidden" name="logo" value="{{$det->logo}}">
                    <button id="savebutton" type="submit" class="btn btn-primary">Save</button>
                    <a href="/admin/dashboard" class="btn btn-default">Close</a>
                </div>
            </div>
        </div>

    </form>
</div>
@endsection
