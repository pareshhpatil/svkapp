@extends('layouts.admin')

@section('content')
<div class="row" id="insert">
    <form action="/admin/paymentsource/updatesave" method="post" id="customerForm" enctype="multipart/form-data" class="form-horizontal">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="col-md-2"></div>
        <div class="col-md-6">
            
            <div class="form-group">
                <label class="control-label col-md-4">Name<span class="required">* </span></label>
                <div class="col-md-7">
                    <input type="text" name="name" required="" value="{{$det->name}}" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4">Bank name<span class="required">* </span></label>
                <div class="col-md-7">
                    <input type="text" name="bank" required="" value="{{$det->name}}" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4">Card Number<span class="required">* </span></label>
                <div class="col-md-7">
                    <input type="number" pattern="[0-9]*" value="{{$det->card_number}}" name="card_number" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4">Type<span class="required">* </span></label>
                <div class="col-md-7">
                    <select name="type" class="form-control">
                        <option @if($det->type=='Credit card') selected @endif value="Credit card">Credit card</option>
                        <option @if($det->type=='Debit card') selected @endif value="Debit card">Debit card</option>
                        <option @if($det->type=='Current account') selected @endif value="Current account">Current account</option>
                        <option @if($det->type=='Other') selected @endif value="Other">Other</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-4">Balance<span class="required"> </span></label>
                <div class="col-md-7">
                    <input type="number" step="0.01" value="{{$det->balance}}"  name="balance" class="form-control">
                </div>
            </div>
           

            <div class="form-group">
                <div class="col-md-4"></div>
                <div class="col-md-7">
                    <h4 id="status"></h4>
                    <p id="loaded_n_total"></p>
                    <input type="hidden" name="id" value="{{$det->paymentsource_id}}">
                    <button id="savebutton" type="submit" class="btn btn-primary">Save</button>
                    <a href="/admin/paymentsource/list" class="btn btn-default">Close</a>
                </div>
            </div>
           
            
        </div>
        >
    </form>
</div>
@endsection
