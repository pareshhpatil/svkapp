@extends('layouts.admin')

@section('content')
<div class="row" id="insert">
    <form action="/admin/bill/paymentsave" method="post" id="customerForm" enctype="multipart/form-data" class="form-horizontal">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="col-md-6">
            
            <div class="form-group">
                <label class="control-label col-md-4">Payee Name<span class="required"> </span></label>
                <div class="col-md-7">
                    <input type="text"  readonly="" value="{{$det->payee_name}}" class="form-control" >
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4">Payment Date<span class="required"> </span></label>
                <div class="col-md-7">
                    <input type="text" name="date" readonly="" value="{{$current_date}}" autocomplete="off" class="form-control form-control-inline date-picker" data-date-format="dd M yyyy" >
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-4">Amount<span class="required">* </span></label>
                <div class="col-md-7">
                    <input type="number" pattern="[0-9]*" step="0.01" required="" value="{{$det->amount}}" name="amount" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4">Remark<span class="required"> </span></label>
                <div class="col-md-7">
                    <input type="text" id="remark" name="remark" value="{{$det->note}}"  class="form-control" >
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-4">Payment mode<span class="required"> </span></label>
                <div class="col-md-7">
                    <select name="payment_mode" required="" class="form-control" data-placeholder="Select...">
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
                    <select name="source_id" required class="form-control" data-placeholder="Select...">
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
                    <input type="hidden" name="employee_id" value="{{$det->employee_id}}">
                    <input type="hidden" name="vendor_id" value="{{$det->vendor_id}}">
                    <input type="hidden" name="vehicle_id" value="{{$det->vehicle_id}}">
                    <input type="hidden" name="type" value="{{$det->type}}">
                    <input type="hidden" name="bill_id" value="{{$det->bill_id}}">
                    <input type="hidden" name="category" value="{{$det->category}}">
                    <a href="/admin/bill" class="btn btn-default pull-right" >Close</a>
                    <button  type="submit" class="btn btn-primary pull-right" style="margin-right: 10px;">Save</button>
                </div>
            </div>
        </div>

    </form>
</div>
@endsection
