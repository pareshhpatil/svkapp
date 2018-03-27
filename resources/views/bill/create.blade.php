@extends('layouts.admin')

@section('content')
<div class="row" id="insert">
    <form action="/admin/bill/save" method="post" id="customerForm" enctype="multipart/form-data" class="form-horizontal">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label col-md-4">Vendor<span class="required">* </span></label>
                <div class="col-md-7">
                    <select name="vendor_id" required class="form-control" data-placeholder="Select...">
                        <option value="">Select vendor</option>
                        @foreach ($vendor_list as $item)
                        <option value="{{$item->vendor_id}}">{{$item->business_name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4">Category<span class="required">* </span></label>
                <div class="col-md-7">
                    <select name="category" required class="form-control" data-placeholder="Select...">
                        <option value="">Select category</option>
                        <option value="Maintenance">Maintenance</option>
                        <option value="Loan EMI">LOAN EMI</option>
                        <option value="Admin Commission">Admin Commission</option>
                        <option value="Fuel">Fuel</option>
                        <option value="GST & TAX">GST & TAX</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4">Vehicle<span class="required"> </span></label>
                <div class="col-md-7">
                    <select name="vehicle_id" required class="form-control" data-placeholder="Select...">
                        <option value="0">Select vehicle</option>
                        @foreach ($vehicle_list as $item)
                        <option value="{{$item->vehicle_id}}">{{$item->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4">Bill Date<span class="required"> </span></label>
                <div class="col-md-7">
                    <input type="text" name="date" readonly="" value="{{$current_date}}" autocomplete="off" class="form-control form-control-inline date-picker" data-date-format="dd M yyyy" >
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-4">Amount<span class="required">* </span></label>
                <div class="col-md-7">
                    <input type="number" pattern="[0-9]*" step="0.01" required="" value="" name="amount" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4">Remark<span class="required"> </span></label>
                <div class="col-md-7">
                    <input type="text" id="remark" name="remark"   class="form-control" >
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-4">Payment mode<span class="required"> </span></label>
                <div class="col-md-7">
                    <select name="payment_mode" class="form-control" data-placeholder="Select...">
                        <option value="">Select mode</option>
                        <option value="Cash">Cash</option>
                        <option value="Cheque">Cheque</option>
                        <option value="NEFT">NEFT</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-4">Paid By<span class="required"> </span></label>
                <div class="col-md-7">
                    <select name="source_id" class="form-control" data-placeholder="Select...">
                        <option value="">Select source</option>
                        @foreach ($paymentsource_list as $item)
                        <option value="{{$item->paymentsource_id}}">{{$item->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
             <div class="form-group">
                <label class="control-label col-md-4">Refference No<span class="required"> </span></label>
                <div class="col-md-7">
                    <input type="text" name="ref_no" class="form-control" >
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-11 modal-footer">
                    <p id="loaded_n_total"></p>
                    <a href="/admin/bill" class="btn btn-default pull-right" >Close</a>
                    <button  type="submit" class="btn btn-primary pull-right" style="margin-right: 10px;">Save</button>
                </div>
            </div>
        </div>

    </form>
</div>
@endsection
