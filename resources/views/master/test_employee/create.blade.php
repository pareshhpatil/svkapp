@extends('layouts.admin')

@section('content')
<div class="row" id="insert">
    <form action="/admin/employee/save" method="post" id="customerForm" enctype="multipart/form-data" class="form-horizontal">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label col-md-4">Employee name<span class="required">* </span></label>
                <div class="col-md-7">
                    <input type="text" name="name" required="" value="" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4">Employee code<span class="required"> </span></label>
                <div class="col-md-7">
                    <input type="text" name="employee_code"  value="" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4">Mobile<span class="required">* </span></label>
                <div class="col-md-7">
                    <input type="number" pattern="[0-9]*" name="mobile" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4">Email<span class="required">* </span></label>
                <div class="col-md-7">
                    <input type="email" name="email" value="" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4">Pan<span class="required">* </span></label>
                <div class="col-md-7">
                    <input type="text" name="pan" value="" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4">Adharcard<span class="required"> </span></label>
                <div class="col-md-7">
                    <input type="text" name="adharcard" value="" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4">Address<span class="required"> </span></label>
                <div class="col-md-7">
                    <textarea value="" name="address" class="form-control"></textarea>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-4">License<span class="required"> </span></label>
                <div class="col-md-7">
                    <input type="text" name="license" value="" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4">Upload photo<span class="required">* </span></label>
                <div class="col-md-7">
                    <input type="file" id="fileupload" accept="image/*" name="uploaded_file">
                </div>
            </div>
        </div>
        <div class="col-md-6">

            <div class="form-group">
                <label class="control-label col-md-4">Joining date<span class="required"> </span></label>
                <div class="col-md-7">
                    <input type="text" name="join_date" required="" autocomplete="off" class="form-control form-control-inline date-picker" data-date-format="yyyy-mm-dd">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4">Salary<span class="required">* </span></label>
                <div class="col-md-7">
                    <input type="number" pattern="[0-9]*" required="" name="payment" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4">Salary day<span class="required"> </span></label>
                <div class="col-md-7">
                    <input type="number" pattern="[0-9]*" min="1" max="28" value="1" name="payment_day" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4">Bank account no.<span class="required"> </span></label>
                <div class="col-md-7">
                    <input type="number" pattern="[0-9]*" name="account_no" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4">Account holder name<span class="required"> </span></label>
                <div class="col-md-7">
                    <input type="text" name="holder_name" value="" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4">Bank name<span class="required"> </span></label>
                <div class="col-md-7">
                    <input type="text" name="bank_name" value="" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4">Account type<span class="required"> </span></label>
                <div class="col-md-7">
                    <select name="account_type" class="form-control">
                        <option value="Saving">Saving</option>
                        <option value="Current">Current</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4">IFSC code<span class="required"> </span></label>
                <div class="col-md-7">
                    <input type="text" name="ifsc_code" value="" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-4"></div>
                <div class="col-md-7">
                    <h4 id="status"></h4>
                    <p id="loaded_n_total"></p>
                    <button id="savebutton" type="submit" class="btn btn-primary">Save</button>
                    <a href="/admin/employee/list" class="btn btn-default">Close</a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
