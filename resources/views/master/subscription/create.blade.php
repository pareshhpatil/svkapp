@extends('layouts.admin')

@section('content')
<div class="row" id="insert">
    @isset($success_message)
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <strong>Success! </strong> {{$success_message}}
        </div>
        @endisset
    <form action="/admin/employee/subscriptionsave" method="post" id="customerForm" enctype="multipart/form-data" class="form-horizontal">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label col-md-4">Employee name<span class="required">* </span></label>
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
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4">Day<span class="required">* </span></label>
                <div class="col-md-7">
                    <input type="number" pattern="[0-9]*" name="day" value="5" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4">Amount<span class="required">* </span></label>
                <div class="col-md-7">
                    <input type="text" name="amount" value="" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4">Note<span class="required"> </span></label>
                <div class="col-md-7">
                    <input type="text" required name="note" value="" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-4"></div>
                <div class="col-md-7">
                    <h4 id="status"></h4>
                    <p id="loaded_n_total"></p>
                    <button id="savebutton" type="submit" class="btn btn-primary">Save</button>
                    <a href="/admin/subscription/list" class="btn btn-default">Close</a>
                </div>
            </div>
        </div>

    </form>
</div>
@endsection
