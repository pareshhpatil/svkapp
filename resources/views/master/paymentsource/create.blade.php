@extends('layouts.admin')

@section('content')
<div class="row" id="insert">
    <form action="/admin/paymentsource/save" method="post" id="customerForm" enctype="multipart/form-data" class="form-horizontal">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="col-md-2"></div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label col-md-4">Name<span class="required">* </span></label>
                <div class="col-md-7">
                    <input type="text" name="name" required="" value="" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4">Bank<span class="required">* </span></label>
                <div class="col-md-7">
                    <input type="text" name="bank" required="" value="" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4">Card/Account Number<span class="required"> </span></label>
                <div class="col-md-7">
                    <input type="number" pattern="[0-9]*" name="card_number" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4">Type<span class="required"> </span></label>
                <div class="col-md-7">
                    <select name="type" class="form-control">
                        <option value="Credit card">Credit card</option>
                        <option value="Debit card">Debit card</option>
                        <option value="Current account">Current account</option>
                    </select>
                </div>
            </div>

            
            <div class="form-group">
                <div class="col-md-4"></div>
                <div class="col-md-7">
                    <h4 id="status"></h4>
                    <p id="loaded_n_total"></p>

                    <a href="/admin/paymentsource/list" class="btn btn-default pull-right" >Close</a>
                    <button id="savebutton" type="submit" class="btn btn-primary pull-right" style="margin-right: 10px;">Save</button>
                </div>
            </div>
        </div>

    </form>
</div>
@endsection
