@extends('layouts.admin')

@section('content')
<div class="row" id="insert">
    @isset($success_message)
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <strong>Success! </strong> {{$success_message}}
    </div>
    @endisset
    <form action="/admin/bill/save" method="post" id="customerForm" class="form-horizontal">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label col-md-4">Company<span class="required">* </span></label>
                <div class="col-md-7">
                    <select name="company_id" required class="form-control select2" data-placeholder="Select...">
                        <option value="">Select company</option>
                        @foreach ($company_list as $item)
                        <option value="{{$item->company_id}}">{{$item->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4">Vendor<span class="required">* </span></label>
                <div class="col-md-7">
                    <select name="employee_id" required class="form-control select2" data-placeholder="Select...">
                        <option value="">Select Employee</option>
                        @foreach ($employee_list as $item)
                        <option value="{{$item->employee_id}}">{{$item->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4">Type<span class="required">* </span></label>
                <div class="col-md-7">
                    <select name="type" required class="form-control" data-placeholder="Select...">
                        <option value="">Select type</option>
                        <option value="1">Bill</option>
                        <option value="2">Bill & Payment</option>
                        <option value="3">Advance</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4">Category<span class="required"> *</span></label>
                <div class="col-md-7">
                    <select name="category" required class="form-control" data-placeholder="Select...">
                        <option value="">Select category</option>
                        <option value="Casual">Casual</option>
                        <option value="Advance">Advance</option>
                        <option value="Salary">Salary</option>
                        <option value="Vendor Package">Vendor Package</option>
                        <option value="Maintenance">Maintenance</option>
                        <option value="Company">Company</option>
                        <option value="Office Expnese">Office Expnese</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-4">Payment Date<span class="required"> </span></label>
                <div class="col-md-7">
                    <input type="text" name="date" readonly="" value="{{$current_date}}" autocomplete="off" class="form-control form-control-inline date-picker" data-date-format="dd M yyyy">
                </div>
            </div>


            <div class="form-group">
                <label class="control-label col-md-4">Amount<span class="required">* </span></label>
                <div class="col-md-7">
                    <input type="number" pattern="[0-9]*" step="1" required="" value="" name="amount" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4">Remark<span class="required">* </span></label>
                <div class="col-md-7">
                    <input type="text" required id="remark" name="remark" class="form-control">
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
                <div class="col-md-11 modal-footer">
                    <p id="loaded_n_total"></p>
                    <a href="/admin/bill" class="btn btn-default pull-right">Close</a>
                    <button type="submit" class="btn btn-primary pull-right" style="margin-right: 10px;">Save</button>
                </div>
            </div>
        </div>

    </form>
</div>
@endsection