@extends('app.master')

@section('content')
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="page-bar">  
    <span class="page-title" style="float: left;">{{$title}}</span>
    {{ Breadcrumbs::render() }}
    </div>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12">
            @include('layouts.alerts')

            <div class="portlet light bordered">
                <div class="portlet-body form">
                    <!--<h3 class="form-section">Profile details</h3>-->
                    <form action="/merchant/expense/save" id="frm_expense" onsubmit="loader();"  method="post" enctype="multipart/form-data" class="form-horizontal form-row-sepe">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="form-body">
                            <!-- Start profile details -->
                            <div class="row">
                                <div class="col-md-6">
                                    <h3 class="form-section">
                                        Expense information
                                    </h3>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Expense number <span class="required">*
                                            </span></label>
                                        <div class="col-md-8">
                                            <div class="input-group">
                                                <input id="expense_auto_generate" data-cy="expense_no" name="expense_no" type="text" @if($expense_auto_generate==1) readonly="" value="Auto generate" @endif   class="form-control">
                                                       <span class="input-group-btn">
                                                    <a data-toggle="modal" href="#customer" class="btn btn-icon-only green"><i class="icon-settings"> </i></a>
                                                </span>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Select Vendor <span class="required">*
                                            </span></label>
                                        <div class="col-md-8">
                                            <select class="form-control select2me" data-cy="expense_vendor" onchange="setVendorState(this.value);" data-placeholder="Select vendor" required="" name="vendor_id" >
                                                <option value="">Select vendor</option>
                                                @foreach($vendors as $v)
                                                <option value="{{$v->vendor_id}}" @if (old('vendor_id') == $v->vendor_id) selected @endif>{{$v->vendor_name}} - {{$v->state}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Category <span class="required">*
                                            </span></label>
                                        <div class="col-md-8">
                                            <div class="input-group">
                                                <select class="form-control select2me" data-cy="expense_category" id="category" data-placeholder="Select category" required="" name="category_id" >
                                                    <option value="">Select category</option>
                                                    @foreach($category as $v)
                                                    <option value="{{$v->id}}" @if (old('category_id') == $v->id) selected @endif>{{$v->name}}</option>
                                                    @endforeach
                                                </select>
                                                <span class="input-group-btn">
                                                    <a data-toggle="modal" onclick="setCreateMaster('category')" href="#master" class="btn btn-icon-only green"><i class="fa fa-plus"> </i></a>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-4">Department <span class="required">*
                                            </span></label>

                                        <div class="col-md-8">
                                            <div class="input-group">
                                                <select class="form-control select2me" data-cy="expense_department" id="department" data-placeholder="Select department" required="" name="department_id" >
                                                    <option value="">Select department</option>
                                                    @foreach($department as $v)
                                                    <option value="{{$v->id}}" @if (old('department_id') == $v->id) selected @endif>{{$v->name}}</option>
                                                    @endforeach
                                                </select>
                                                <span class="input-group-btn">
                                                    <a data-toggle="modal" href="#master" onclick="setCreateMaster('department')" class="btn btn-icon-only green"><i class="fa fa-plus"> </i></a>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    @if (count($gst_list) > 1)
                                    <div class="form-group">
                                        <label class="control-label col-md-4">GSTIN <span class="required">*
                                            </span></label>
                                        <div class="col-md-8">
                                            <select class="form-control" data-cy="gst_number_dropdown" data-placeholder="Select GST number" required="" name="gst_number" >
                                                <option value="">Select GSTIN</option>
                                                @foreach($gst_list as $v)
                                                @if($v!='')
                                                <option value="{{$v}}" @if (old('gst_number') == $v) selected @endif>{{$v}}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    @else 
                                    <input type="hidden" name="gst_number" value="{{$gst_list[0]}}" data-cy="gst_number">
                                    @endif
                                </div>

                                <div class="col-md-6">
                                    <h3 class="form-section">
                                        Invoice information
                                    </h3>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Invoice number <span class="required">
                                            </span></label>
                                        <div class="col-md-8">
                                            <input type="text" maxlength="45" data-cy="invoice_no" name="invoice_no" class="form-control" data-cy="invoice_no" value="{{ old('invoice_no') }}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Bill date<span class="required">*
                                            </span></label>
                                        <div class="col-md-8">
                                            <input class="form-control form-control-inline date-picker" data-cy="bill_date" type="text" required  name="bill_date"  autocomplete="off" data-date-format="{{ Session::get('default_date_format')}}"  placeholder="Bill date" value="old('date')" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Due date<span class="required">*
                                            </span></label>
                                        <div class="col-md-8">
                                            <input class="form-control form-control-inline date-picker" type="text" required  data-cy="due_date" name="due_date"  autocomplete="off" data-date-format="{{ Session::get('default_date_format')}}"  placeholder="Due date" value="old('date')" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Upload file<span class="required">
                                            </span>
                                        </label>
                                        <div class="col-md-8">
                                            <input type="file" accept="image/*,application/pdf" data-cy="file" onchange="return validatefilesize(3000000, 'expense');" id="expense" name="file">
                                            <span class="help-block red">* Max file size 3 MB</span>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        @livewire('expense.particular-detail')

                        <div class="form-body">
                            <!-- Start profile details -->
                            <h3 class="form-section">
                                Payment information
                            </h3>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Payment status<span class="required">
                                            </span></label>
                                        <div class="col-md-8">
                                            <select onchange="paymentStatus(this.value);" data-cy="payment_status" class="form-control" name="payment_status">
                                                <option value="0">Select..</option>
                                                <option value="1">Paid</option>
                                                <option value="2">Unpaid</option>
                                                <option value="3">Refunded</option>
                                                <option value="4">Cancelled</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="paymentmode" style="display: none;">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="inputPassword12" class="col-md-4 control-label">Payment mode<span class="required">*
                                                </span></label>
                                            <div class="col-md-8">
                                                <select class="form-control " id="payment_mode" data-cy="payment_mode" name="payment_mode" onchange="responseType(this.value);" data-placeholder="Select type">
                                                    <option value="">Select..</option>
                                                    <option value="1">Wire transfer</option>
                                                    <option value="2">Cheque</option>
                                                    <option value="3">Cash</option>
                                                    <option value="4">Online Payment</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group" id="bank_transaction_no" >
                                            <label for="inputPassword12" class="col-md-4 control-label">Bank ref no</label>
                                            <div class="col-md-8">
                                                <input class="form-control form-control-inline" data-cy="bank_transaction_no" name="bank_transaction_no" type="text" value="" placeholder="Bank ref number" value="{{ old('bank_transaction_no') }}"/>
                                            </div>
                                        </div>
                                        <div id="cheque_no" style="display: none;">
                                            <div class="form-group" >
                                                <label for="inputPassword12" class="col-md-4 control-label">Cheque no</label>
                                                <div class="col-md-8">
                                                    <input class="form-control form-control-inline" data-cy="cheque_no" name="cheque_no"   type="text" value="{{ old('cheque_no') }}" placeholder="Cheque no"/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputPassword12" class="col-md-4 control-label">Amount <span class="required">*</span></label>
                                            <div class="col-md-8">
                                                <input class="form-control form-control-inline" data-cy="amount" id="amount" name="amount" type="text" placeholder="Amount" value="{{ old('amount') }}"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group" id="cash_paid_to" style="display: none;">
                                            <label for="inputPassword12" class="col-md-4 control-label">Cash paid to</label>
                                            <div class="col-md-8">
                                                <input class="form-control form-control-inline" data-cy="cash_paid_to" name="cash_paid_to" type="text" placeholder="Cash paid to" value="{{ old('cash_paid_to') }}"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputPassword12" class="col-md-4 control-label">Date <span class="required">*
                                                </span></label>
                                            <div class="col-md-8">
                                                <input class="form-control form-control-inline date-picker" data-cy="date" onkeypress="return false;"  autocomplete="off" data-date-format="{{ Session::get('default_date_format')}}"  id="date" name="date" type="text"  placeholder="Date" value="old('date')" />
                                            </div>
                                        </div>
                                        <div class="form-group" id="bank_name">
                                            <label for="inputPassword12" class="col-md-4 control-label">Bank name</label>
                                            <div class="col-md-8">
                                                <input class="form-control form-control-inline " data-cy="bank_name" name="bank_name" type="text" placeholder="Bank name" value="{{ old('bank_name') }}"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Narrative<span class="required">
                                            </span></label>
                                        <div class="col-md-8">
                                            <textarea name="narrative" class="form-control"></textarea>
                                        </div>
                                    </div>
                                </div> --}}
                            </div>
                        </div>
                        <!-- End profile details -->
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="pull-right">
                                        <input type="hidden" id="gst_type" name="gst_type">
                                        <input type="hidden" value="1" name="type">
                                        <a href="/merchant/expense/viewlist/expense" class="btn default">Cancel</a>
                                        <input type="submit" value="{{$button}}" class="btn blue" data-cy="expense_dave" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <!-- Side panel code for adding product/service -->
                    @include('app.merchant.product.side-panel-product',['redirect' => '/merchant/expense/create'])
                </div>
            </div>
        </div>	
        <!-- END PAGE CONTENT-->
    </div>
</div>
<!-- END CONTENT -->
</div>

<div class="modal fade" id="customer" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="submit_form" method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" id="closeauto" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Expense number</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-body">
                                <div class="alert alert-danger display-hide">
                                    <button class="close" data-close="alert"></button>
                                    You have some form errors. Please check below.
                                </div>
                                <div class="form-group">
                                    <h5>Your Expense number is on auto-generate mode to save your time. Are you sure about changing this setting?</h5>
                                </div>
                                <div class="form-group" >
                                    <label for="autogen" class="col-md-12 control-label">
                                        <input type="radio" id="autogen" data-cy="auto_generate" name="auto_generate" value="1" @if($expense_auto_generate==1) checked="" @endif> Continue auto-generating numbers
                                    </label>
                                    <div class="col-md-8">
                                        <div class="col-md-7">
                                            <div class="form-group">
                                                <p>Prefix</p>
                                                <input type="text" data-cy="prefix" name="prefix" id="prefix" maxlength="10" value="{{$prefix}}" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <p>Number</p>
                                                <input type="text" data-cy="prefix_val" name="prefix_val" value="{{$prefix_val}}" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group" >
                                    <label for="auto2" class="col-md-8 control-label">
                                        <input type="radio" class="icheck" id="auto2"  data-cy="auto_generate" name="auto_generate" @if($expense_auto_generate!=1) checked="" @endif value="0"/> I will add them manually each time
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="seq_id" name="seq_id" value="{{$prefix_id}}">
                    <button type="button" onclick="return saveExpenseSequence(3);" class="btn blue" data-cy="expense_number_save" >Save</button>
                    <button type="button" class="btn default" id="closebutton" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div><!-- BEGIN FOOTER -->


<div class="modal fade" id="master" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form  method="post" id="frm_master" class="form-horizontal form-row-sepe">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title" id="master_title">Create category</h4>
                </div>
                <div class="modal-body">
                    <div class="portlet-body form">
                        <!--<h3 class="form-section">Profile details</h3>-->

                        <div class="form-body">
                            <!-- Start profile details -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div id="po-category-error-msg" class="alert alert-danger" style="display:none">
                                        <button class="close" data-dismiss="alert"></button>
                                        <ul></ul>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Name <span class="required">*
                                            </span></label>
                                        <div class="col-md-8">
                                            <input type="text" required="" maxlength="45" name="name" class="form-control" value="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="mstclg" class="btn default" data-dismiss="modal">Close</button>
                    <input type="hidden" id="master_type" value="category">
                    <input type="button" onclick="return saveExpenseMaster();" value="Save" class="btn blue"/>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
@endsection
<script>
    mode = '{{$mode}}';
</script>

