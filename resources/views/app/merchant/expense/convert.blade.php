@extends('app.master')

@section('content')
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <h3 class="page-title">Create expense&nbsp;</h3>
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
                                                <input id="expense_auto_generate" name="expense_no" type="text" @if($expense_auto_generate==1) readonly="" value="Auto generate" @endif   class="form-control">
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
                                            <select class="form-control select2me" onchange="setVendorState(this.value);" data-placeholder="Select vendor" required="" name="vendor_id" >
                                                <option value="">Select vendor</option>
                                                @foreach($vendors as $v)
                                                @if($detail->vendor_id==$v->vendor_id)
                                                <option selected="" value="{{$v->vendor_id}}">{{$v->vendor_name}} - {{$v->state}}</option>
                                                @else
                                                <option value="{{$v->vendor_id}}">{{$v->vendor_name}} - {{$v->state}}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Category <span class="required">*
                                            </span></label>
                                        <div class="col-md-8">
                                            <div class="input-group">
                                                <select class="form-control select2me"  id="category" data-placeholder="Select category" required="" name="category_id" >
                                                    <option value="">Select category</option>
                                                    @foreach($category as $v)
                                                    @if($detail->category_id==$v->id)
                                                    <option selected="" value="{{$v->id}}">{{$v->name}}</option>
                                                    @else
                                                    <option value="{{$v->id}}">{{$v->name}}</option>
                                                    @endif
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
                                                <select class="form-control select2me" id="department" data-placeholder="Select department" required="" name="department_id" >
                                                    <option value="">Select department</option>
                                                    @foreach($department as $v)
                                                    @if($detail->department_id==$v->id)
                                                    <option selected="" value="{{$v->id}}">{{$v->name}}</option>
                                                    @else
                                                    <option value="{{$v->id}}">{{$v->name}}</option>
                                                    @endif
                                                    @endforeach
                                                </select>
                                                <span class="input-group-btn">
                                                    <a data-toggle="modal" href="#master" onclick="setCreateMaster('department')" class="btn btn-icon-only green"><i class="fa fa-plus"> </i></a>
                                                </span>
                                            </div>
                                        </div>



                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h3 class="form-section">
                                        Invoice information
                                    </h3>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Invoice number <span class="required">
                                            </span></label>
                                        <div class="col-md-8">
                                            <input type="text" @isset($approve) value="{{$detail->invoice_no}}" @endisset   maxlength="45"  name="invoice_no"  class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Bill date<span class="required">*
                                            </span></label>
                                        <div class="col-md-8">
                                            <input class="form-control form-control-inline date-picker" type="text" required @isset($approve) value='<x-localize :date="$detail->bill_date" type="date" />' @endisset  name="bill_date"  autocomplete="off" data-date-format="{{ Session::get('default_date_format')}}"  placeholder="Bill date"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Due date<span class="required">*
                                            </span></label>
                                        <div class="col-md-8">
                                            <input class="form-control form-control-inline date-picker" type="text" required @isset($approve) value='<x-localize :date="$detail->due_date" type="date" />' @endisset  name="due_date"  autocomplete="off" data-date-format="{{ Session::get('default_date_format')}}"  placeholder="Due date"/>
                                        </div>
                                    </div>

                                    @if($detail->file_path!='')
                                    <div class="form-group" >
                                        <label class="control-label col-md-4">Upload file</label>
                                        <div class="col-md-5">
                                            <span class="help-block">
                                                <a class="btn btn-xs green" target="_BLANK" href="{{$detail->file_path}}">View doc</a>
                                                <a onclick="document.getElementById('update12').style.display = 'block';" class="btn btn-xs btn-link">Update doc</a>
                                            </span>
                                            <input type="hidden" name="file_path" value="{{$detail->file_path}}">
                                            <span id="update12" style="display: none;">
                                                <input type="file" accept="image/*,application/pdf" onchange="return validatefilesize(3000000, 'expense');" id="expense" name="file">
                                                <a onclick="document.getElementById('update12').style.display = 'none';" class="btn btn-xs red pull-right"><i class="fa fa-remove"></i></a>
                                                <span class="help-block red">* Max file size 3 MB
                                                </span>
                                            </span>
                                        </div>		
                                    </div>
                                    @else
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Upload file<span class="required">
                                            </span>
                                        </label>
                                        <div class="col-md-8">
                                            <input type="file" accept="image/*,application/pdf" onchange="return validatefilesize(3000000, 'expense');" id="expense" name="file">
                                            <span class="help-block red">* Max file size 3 MB</span>
                                        </div>
                                    </div>
                                    @endif


                                </div>
                            </div>
                        </div>

                        @livewire('expense.particular-detail',['expense_id'=>$link,'table' => $table])

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
                                            <select onchange="paymentStatus(this.value);" class="form-control" name="payment_status">
                                                <option value="0">Select..</option>
                                                <option value="1">Paid</option>
                                                <option value="2">Unpaid</option>
                                                <option value="3">Refunded</option>
                                                <option value="4">Cancelled</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div id="paymentmode" style="display: none;">
                                        <div class="form-group">
                                            <label for="inputPassword12" class="col-md-4 control-label">Payment mode<span class="required">*
                                                </span></label>
                                            <div class="col-md-8">
                                                <select class="form-control " id="payment_mode"  name="payment_mode" onchange="responseType(this.value);" data-placeholder="Select type">
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
                                                <input class="form-control form-control-inline " name="bank_transaction_no" type="text" value="" placeholder="Bank ref number"/>
                                            </div>
                                        </div>
                                        <div id="cheque_no" style="display: none;">
                                            <div class="form-group" >
                                                <label for="inputPassword12" class="col-md-4 control-label">Cheque no</label>
                                                <div class="col-md-8">
                                                    <input class="form-control form-control-inline " name="cheque_no"   type="text" value="" placeholder="Cheque no"/>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="form-group" id="cash_paid_to" style="display: none;">
                                            <label for="inputPassword12" class="col-md-4 control-label">Cash paid to</label>
                                            <div class="col-md-8">
                                                <input class="form-control form-control-inline " name="cash_paid_to" type="text" placeholder="Cash paid to"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputPassword12" class="col-md-4 control-label">Date <span class="required">*
                                                </span></label>
                                            <div class="col-md-8">
                                                <input class="form-control form-control-inline  date-picker" onkeypress="return false;"  autocomplete="off" data-date-format="{{ Session::get('default_date_format')}}"  id="date" name="date" type="text" value="" placeholder="Date"/>
                                            </div>
                                        </div>
                                        <div class="form-group" id="bank_name">
                                            <label for="inputPassword12" class="col-md-4 control-label">Bank name</label>
                                            <div class="col-md-8">
                                                <input class="form-control form-control-inline " name="bank_name" type="text" placeholder="Bank name"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputPassword12" class="col-md-4 control-label">Amount <span class="required">*</span></label>
                                            <div class="col-md-8">
                                                <input class="form-control form-control-inline " id="amount" name="amount" type="text" placeholder="Amount"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    {{-- <div class="form-group">
                                        <label class="control-label col-md-4">Narrative<span class="required">
                                            </span></label>
                                        <div class="col-md-8">
                                            <textarea name="narrative" class="form-control">{{$detail->narrative}}</textarea>
                                        </div>
                                    </div> --}}
                                </div>
                            </div>
                        </div>

                        <!-- End profile details -->
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="pull-right">
                                        <input type="hidden" id="gst_type" value="{{$gst_type}}" name="gst_type">
                                        <input type="hidden" value="1" name="type">
                                        @isset($approve) 
                                        <input type="hidden" value="{{$detail->payment_request_id}}" name="payment_request_id">
                                        <input type="hidden" value="{{$detail->expense_id}}" name="staging_expense_id">
                                        @endisset
                                        <input type="hidden" value="{{$detail->expense_id}}" name="convert_po_id">
                                        <a href="/merchant/expense/viewlist/po" class="btn default">Cancel</a>
                                        <input type="submit" value="Save" class="btn blue"/>
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
                                        <input type="radio" id="autogen" name="auto_generate" value="1" @if($expense_auto_generate==1) checked="" @endif> Continue auto-generating numbers
                                    </label>
                                    <div class="col-md-8">
                                        <div class="col-md-7">
                                            <div class="form-group">
                                                <p>Prefix</p>
                                                <input type="text" name="prefix" id="prefix" maxlength="10" value="{{$prefix}}" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <p>Number</p>
                                                <input type="text" name="prefix_val" value="{{$prefix_val}}" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group" >
                                    <label for="auto2" class="col-md-8 control-label">
                                        <input type="radio" class="icheck" id="auto2"  name="auto_generate" @if($expense_auto_generate!=1) checked="" @endif value="0"/> I will add them manually each time
                                    </label>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="seq_id" name="seq_id" value="{{$prefix_id}}">
                    <button type="button" onclick="return saveExpenseSequence(3);" class="btn blue">Save</button>
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
                                    <div class="alert alert-danger display-none">
                                        <button class="close" data-dismiss="alert"></button>
                                        You have some form errors. Please check below.
                                    </div>


                                    <div class="form-group">
                                        <label class="control-label col-md-4">Name <span class="required">*
                                            </span></label>
                                        <div class="col-md-8">
                                            <input type="text" required=""  maxlength="45" name="name" class="form-control" value="">
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

