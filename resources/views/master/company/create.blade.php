@extends('layouts.admin')

@section('content')
<div class="row" id="insert">
    <form action="/admin/company/save" method="post" id="customerForm" enctype="multipart/form-data" class="form-horizontal">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="col-md-2"></div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label col-md-4">Company name<span class="required">* </span></label>
                <div class="col-md-7">
                    <input type="text" name="name" required="" value="" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4">Email<span class="required"> </span></label>
                <div class="col-md-7">
                    <input type="email" name="email" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4">GST Number<span class="required">* </span></label>
                <div class="col-md-7">
                    <input type="text" name="gst_number" value="" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4">Address<span class="required"> </span></label>
                <div class="col-md-7">
                    <textarea name="address" class="form-control"></textarea>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-4">Joining date<span class="required"> </span></label>
                <div class="col-md-7">
                    <input type="text" name="join_date" required="" autocomplete="off" class="form-control form-control-inline date-picker" data-date-format="yyyy-mm-dd">
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-4"></div>
                <div class="col-md-7">
                    <h4 id="status"></h4>
                    <p id="loaded_n_total"></p>

                    <a href="/admin/company/list" class="btn btn-default pull-right" >Close</a>
                    <button id="savebutton" type="submit" class="btn btn-primary pull-right" style="margin-right: 10px;">Save</button>
                </div>
            </div>
        </div>

    </form>
</div>
@endsection
