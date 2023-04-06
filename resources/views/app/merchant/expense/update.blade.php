@extends('app.master')

@section('content')
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="page-bar">  
    <span class="page-title" style="float: left;">{{$title}}</span>
    {{ Breadcrumbs::render('update.expense',$detail->type,$detail->expense_no) }}
    </div>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12">
            @include('layouts.alerts')

            <div class="portlet light bordered">
                <div class="portlet-body form">
                    <!--<h3 class="form-section">Profile details</h3>-->
                    <form action="/merchant/expense/{{$btn_update}}" id="frm_expense" onsubmit="loader();"  method="post" enctype="multipart/form-data"  class="form-horizontal form-row-sepe">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="form-body">
                            <!-- Start profile details -->
                            <div class="row">
                                <div class="col-md-6">
                                    <h3 class="form-section">
                                        @if($detail->type==1)
                                        Expense information
                                        @else
                                        Purchase order information
                                        @endif
                                    </h3>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">@if($detail->type==1)Expense number @else PO number @endif <span class="required">*
                                            </span></label>
                                        <div class="col-md-8">
                                            <input id="expense_auto_generate" name="expense_no" type="text" @if($expense_auto_generate==1) readonly=""  @endif value="{{$detail->expense_no}}"  class="form-control">
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
                                        @if($detail->type==1)
                                        Invoice information
                                        @else
                                        Delivery information
                                        @endif
                                    </h3>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">@if($detail->type==1)Invoice number @else Reff number @endif <span class="required">
                                            </span></label>
                                        <div class="col-md-8">
                                            <input type="text"    maxlength="45" value="{{$detail->invoice_no}}" name="invoice_no"  class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">@if($detail->type==1)Bill date @else Date @endif <span class="required">*
                                            </span></label>
                                        <div class="col-md-8">
                                            <input class="form-control form-control-inline date-picker" type="text" required   name="bill_date" value='<x-localize :date="$detail->bill_date" type="date" />' autocomplete="off" data-date-format="{{ Session::get('default_date_format')}}"  placeholder=""/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">@if($detail->type==1)Due date @else Expected delivery date @endif<span class="required">*
                                            </span></label>
                                        <div class="col-md-8">
                                            <input class="form-control form-control-inline date-picker" type="text" required   name="due_date"  value='<x-localize :date="$detail->due_date" type="date" />' autocomplete="off" data-date-format="{{ Session::get('default_date_format')}}"  placeholder=""/>
                                        </div>
                                    </div>
                                    @if($detail->type==1)
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
                                    @endif

                                </div>
                            </div>
                        </div>

                        @livewire('expense.particular-detail',['expense_id'=>$link, 'table' => $table])

                        @if($detail->type==1)
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h3 class="form-section">Payment information</h3>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Payment status<span class="required">
                                                </span></label>
                                            <div class="col-md-8">
                                                <select onchange="paymentStatus(this.value);" class="form-control" name="payment_status">
                                                    <option value="0">Select..</option>
                                                    <option @if($detail->payment_status==1)selected @endif value="1">Paid</option>
                                                    <option @if($detail->payment_status==2)selected @endif value="2">Unpaid</option>
                                                    <option @if($detail->payment_status==3)selected @endif value="3">Refunded</option>
                                                    <option @if($detail->payment_status==4)selected @endif value="4">Cancelled</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="paymentmode" @if($detail->payment_status!=1) style="display: none;" @endif>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="inputPassword12" class="col-md-4 control-label">Payment mode<span class="required">*
                                                    </span></label>
                                                <div class="col-md-8">
                                                    <select class="form-control " id="payment_mode"  name="payment_mode" onchange="responseType(this.value);" data-placeholder="Select type">
                                                        <option value="">Select..</option>
                                                        <option @if($detail->payment_mode==1)selected @endif value="1">Wire transfer</option>
                                                        <option @if($detail->payment_mode==2)selected @endif value="2">Cheque</option>
                                                        <option @if($detail->payment_mode==3)selected @endif value="3">Cash</option>
                                                        <option @if($detail->payment_mode==4)selected @endif value="4">ONLINE</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group" id="bank_transaction_no" >
                                                <label for="inputPassword12" class="col-md-4 control-label">Bank ref no</label>
                                                <div class="col-md-8">
                                                    <input class="form-control form-control-inline " name="bank_transaction_no" type="text" @if(!empty($transfer_details)) value="{{$transfer_details->bank_transaction_no}}" @endif placeholder="Bank ref number"/>
                                                </div>
                                            </div>
                                            <div id="cheque_no" style="display: none;">
                                                <div class="form-group" >
                                                    <label for="inputPassword12" class="col-md-4 control-label">Cheque no</label>
                                                    <div class="col-md-8">
                                                        <input class="form-control form-control-inline " name="cheque_no" @if(!empty($transfer_details)) value="{{$transfer_details->cheque_no}}" @endif  type="text" value="" placeholder="Cheque no"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputPassword12" class="col-md-4 control-label">Amount <span class="required">*</span></label>
                                                <div class="col-md-8">
                                                    <input class="form-control form-control-inline " id="amount" @if(!empty($transfer_details)) value="{{$transfer_details->amount}}" @endif name="amount" type="text" placeholder="Amount"/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group" id="cash_paid_to" style="display: none;">
                                                <label for="inputPassword12" class="col-md-4 control-label">Cash paid to</label>
                                                <div class="col-md-8">
                                                    <input class="form-control form-control-inline "  @if(!empty($transfer_details)) value="{{$transfer_details->cash_paid_to}}" @endif name="cash_paid_to" type="text" placeholder="Cash paid to"/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputPassword12" class="col-md-4 control-label">Date <span class="required">*
                                                    </span></label>
                                                <div class="col-md-8">
                                                    <input class="form-control form-control-inline  date-picker" id="date" onkeypress="return false;" @if(!empty($transfer_details)) value='<x-localize :date="$transfer_details->transfer_date" type="date" />' @endif  autocomplete="off" data-date-format="{{ Session::get('default_date_format')}}"  name="date" type="text" value="" placeholder="Date"/>
                                                </div>
                                            </div>
                                            <div class="form-group" id="bank_name">
                                                <label for="inputPassword12" class="col-md-4 control-label">Bank name</label>
                                                <div class="col-md-8">
                                                    <input class="form-control form-control-inline " @if(!empty($transfer_details)) value="{{$transfer_details->bank_name}}" @endif name="bank_name" type="text" placeholder="Bank name"/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Narrative<span class="required">
                                                </span></label>
                                            <div class="col-md-8">
                                                <textarea name="narrative" class="form-control">{{$detail->narrative}}</textarea>
                                            </div>
                                        </div>
                                    </div> --}}
                                </div>
                            </div>
                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="pull-right">
                                            <input type="hidden" id="gst_type" name="gst_type">
                                            <input type="hidden" id="is_notify_" name="notify" value="{{$detail->notify}}">
                                            <input type="hidden" name="expense_id" value="{{$link}}">
                                            <input type="hidden" name="transfer_id" value="{{$detail->transfer_id}}">
                                            @isset($approve) 
                                            <input type="text" value="{{$detail->expense_id}}" name="staging_expense_id">
                                            @endisset
                                            <input type="hidden" name="type" value="{{$detail->type}}">
                                            <a href="/merchant/expense/viewlist/expense" class="btn default">Cancel</a>
                                            <input id="subbtn" type="submit"  value="{{$button}}" class="btn blue"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="form-body">
                                {{-- <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Narrative<span class="required">
                                            </span></label>
                                        <div class="col-md-8">
                                            <textarea name="narrative" class="form-control">{{$detail->narrative}}</textarea>
                                        </div>
                                    </div>
                                </div> --}}
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Notify vendor <span class="required">
                                                </span></label>
                                            <div class="col-md-4">
                                                <input type="checkbox" id="notify_" onchange="notifyPatron('notify_');" checked="" value="1"  class="make-switch" data-size="small">  
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="pull-right">
                                            <input type="hidden" id="gst_type" name="gst_type">
                                            <input type="hidden" id="is_notify_" name="notify" value="{{$detail->notify}}">
                                            <input type="hidden" name="expense_id" value="{{$link}}">
                                            <input type="hidden" name="type" value="{{$detail->type}}">
                                            <a href="/merchant/expense/viewlist/po" class="btn default">Cancel</a>
                                            <input id="subbtn" type="submit" @if($detail->notify==1) value="{{$button}} & Send" @else value="{{$button}}" @endif class="btn blue"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            <!-- End profile details -->
            </form>
             <!-- Side panel code for adding product/service -->
             @include('app.merchant.product.side-panel-product',['redirect' => '/merchant/expense/po/create'])
        </div>
    </div>
</div>
<!-- END CONTENT -->
</div>

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

