@extends('layouts.admin')

@section('content')
<div class="row" id="insert">
    <form action="/admin/zone/save" method="post" id="customerForm" enctype="multipart/form-data" class="form-horizontal">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="col-md-1"></div>
        <div class="col-md-8">
            <div class="form-group">
                <label class="control-label col-md-4">Company name<span class="required"> * </span></label>
                <div class="col-md-7">
                    <select name="company_id" required class="form-control select2" data-placeholder="Select...">
                        <option value="">Select Company</option>
                        @foreach ($company_list as $item)
                        @if($item->type>1)
                        <option value="{{$item->company_id}}">{{$item->name}}</option>
                        @endif
                        @endforeach
                    </select>
                </div>
            </div>
            <div>
                <div class="form-group">
                    <label class="control-label col-md-4">Zone<span class="required"> </span></label>
                    <div class="col-md-7">
                        <input type="text" name="zone" value="" class="form-control">
                    </div>
                </div>
            </div>
            <div>
                <div class="form-group">
                    <label class="control-label col-md-4">From location<span class="required"> </span></label>
                    <div class="col-md-7">
                        <input type="text" name="from" value="" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4">To location<span class="required"> </span></label>
                    <div class="col-md-7">
                        <input type="text" name="to" value="" class="form-control">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-4">Car type<span class="required"> * </span></label>
                <div class="col-md-7">
                    <select name="car_type" required class="form-control select2" data-placeholder="Select...">
                        <option value="Sedan">Sedan</option>
                        <option value="Suv">Suv</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-4">KM<span class="required"> * </span></label>

                <div class="col-md-2">
                    <input type="number" placeholder="Vendor" name="vendor_km" value="" class="form-control">
                </div>
                <div class="col-md-2">
                    <input type="number" placeholder="SVK" name="svk_km" value="" class="form-control">
                </div>
                <div class="col-md-2">
                    <input type="number" placeholder="Admin" name="admin_km" value="" class="form-control">
                </div>
                <div class="col-md-2">
                    <input type="number" placeholder="company" name="company_km" value="" class="form-control">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-4">Amount<span class="required"> * </span></label>
                <div class="col-md-2">
                    <input type="number" placeholder="Vendor" name="vendor_amount" value="" class="form-control">
                </div>
                <div class="col-md-2">
                    <input type="number" placeholder="SVK" name="svk_amount" value="" class="form-control">
                </div>
                <div class="col-md-2">
                    <input type="number" placeholder="Admin" name="admin_amount" value="" class="form-control">
                </div>
                <div class="col-md-2">
                    <input type="number" placeholder="company" name="company_amount" value="" class="form-control">
                </div>
            </div>




            <div class="form-group">
                <div class="col-md-4"></div>
                <div class="col-md-7">
                    <h4 id="status"></h4>
                    <p id="loaded_n_total"></p>

                    <a href="/admin/zone/list" class="btn btn-default pull-right">Close</a>
                    <button id="savebutton" type="submit" class="btn btn-primary pull-right" style="margin-right: 10px;">Save</button>
                </div>
            </div>
        </div>

    </form>
</div>
@endsection