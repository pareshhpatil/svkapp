@extends('layouts.admin')

@section('content')
<div class="row" id="insert">
    <form action="/admin/employee/updatesave" method="post" id="customerForm" enctype="multipart/form-data" class="form-horizontal">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="col-md-6">
            
            <div class="form-group">
                <label class="control-label col-md-4">Employee name<span class="required">* </span></label>
                <div class="col-md-7">
                    <input type="text" name="name" required="" value="{{$det->name}}" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4">Employee code<span class="required"> </span></label>
                <div class="col-md-7">
                    <input type="text" name="employee_code"  value="{{$det->employee_code}}" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4">Mobile<span class="required">* </span></label>
                <div class="col-md-7">
                    <input type="number" pattern="[0-9]*" value="{{$det->mobile}}" name="mobile" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4">Email<span class="required">* </span></label>
                <div class="col-md-7">
                    <input type="email" name="email" value="{{$det->email}}" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4">Pan<span class="required">* </span></label>
                <div class="col-md-7">
                    <input type="text" name="pan" value="{{$det->pan}}" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4">Adharcard<span class="required"> </span></label>
                <div class="col-md-7">
                    <input type="text" name="adharcard" value="{{$det->adharcard}}" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4">Address<span class="required"> </span></label>
                <div class="col-md-7">
                    <textarea value="" name="address" class="form-control">{{$det->address}}</textarea>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-4">License<span class="required"> </span></label>
                <div class="col-md-7">
                    <input type="text" name="license" value="{{$det->license}}" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4">Upload photo<span class="required"> </span></label>
                <div class="col-md-7">
                    <input type="file" id="fileupload" accept="image/*" name="uploaded_file">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4"></label>
                <div class="col-md-7">
                    @if($det->photo!='')
                    <img style="display: inline;max-height: 100px;" class="img-responsive " src="{{ asset('dist/uploads/employee/'.$det->photo) }}" alt="User profile picture">
                    @else
                    <img style="display: inline;max-height: 100px;" class="img-responsive " src="{{ asset('dist/img/avatar5.png') }}" alt="User profile picture">
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6">

            <div class="form-group">
                <label class="control-label col-md-4">Joining date<span class="required"> </span></label>
                <div class="col-md-7">
                    <input type="text" name="join_date" required="" value="{{$det->join_date}}" autocomplete="off" class="form-control form-control-inline date-picker" data-date-format="yyyy-mm-dd">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4">Salary<span class="required">* </span></label>
                <div class="col-md-7">
                    <input type="number" pattern="[0-9]*" required="" value="{{$det->payment}}" name="payment" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4">Salary day<span class="required"> </span></label>
                <div class="col-md-7">
                    <input type="number" pattern="[0-9]*" min="1" max="28" value="{{$det->payment_day}}" name="payment_day" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4">Bank account no.<span class="required"> </span></label>
                <div class="col-md-7">
                    <input type="number" pattern="[0-9]*" name="account_no" value="{{$det->account_no}}" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4">Account holder name<span class="required"> </span></label>
                <div class="col-md-7">
                    <input type="text" name="holder_name" value="{{$det->account_holder_name}}" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4">Bank name<span class="required"> </span></label>
                <div class="col-md-7">
                    <input type="text" name="bank_name" value="{{$det->bank_name}}" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4">Account type<span class="required"> </span></label>
                <div class="col-md-7">
                    <select name="account_type" class="form-control">
                        <option @if($det->account_type=='Saving') selected @endif value="Saving">Saving</option>
                        <option @if($det->account_type=='Current') selected @endif value="Current">Current</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4">IFSC code<span class="required"> </span></label>
                <div class="col-md-7">
                    <input type="text" name="ifsc_code" value="{{$det->ifsc_code}}" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-4"></div>
                <div class="col-md-7">
                    <h4 id="status"></h4>
                    <p id="loaded_n_total"></p>
                    <input type="hidden" name="photo" value="{{$det->photo}}">
                    <input type="hidden" name="employee_id" value="{{$det->employee_id}}">
                    <button id="savebutton" type="submit" class="btn btn-primary">Save</button>
                    <a href="/admin/employee/list" class="btn btn-default">Close</a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
