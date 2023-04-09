@extends('layouts.admin')

@section('content')
<div class="row" id="insert">
    @isset($success_message)
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <strong>Success! </strong> {{$success_message}}
    </div>
    @endisset
    <form action="/admin/credit/save" method="post" id="customerForm" class="form-horizontal">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="col-md-6">

            <div class="form-group">
                <label class="control-label col-md-4">From account<span class="required"> </span></label>
                <div class="col-md-7">
                    <select name="from_id" class="form-control" data-placeholder="Select...">
                        <option value="">Select source</option>
                        @foreach ($paymentsource_list as $item)
                        <option value="{{$item->paymentsource_id}}">{{$item->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-4">To account<span class="required"> </span></label>
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
                <label class="control-label col-md-4">Bill Date<span class="required"> </span></label>
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