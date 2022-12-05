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
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            @include('layouts.alerts')

            <div class="portlet light bordered">
                <div class="portlet-body form">
                    <!--<h3 class="form-section">Profile details</h3>-->
                    <form action="/merchant/po/save" id="frm_expense" onsubmit="loader();"  method="post" enctype="multipart/form-data" class="form-horizontal form-row-sepe">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="form-body">
                            <!-- Start profile details -->
                            <div class="row">
                                <div class="col-md-6">
                                    <h3 class="form-section">
                                        Purchase order information
                                    </h3>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">PO number <span class="required">*
                                            </span></label>
                                        <div class="col-md-8">
                                            <div class="input-group" style="position:sticky;">
                                                <input id="expense_auto_generate" data-cy="expense_no" name="expense_no" type="text" @if($po_auto_generate==1) readonly="" value="Auto generate" @endif   class="form-control">
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
                                            <select class="form-control select2me" data-cy="po_vendor" id="vendor_id" data-placeholder="Select vendor" required="" name="vendor_id" >
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
                                                <select class="form-control select2me" data-cy="po_category" id="category" data-placeholder="Select category" required="" name="category_id" >
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
                                                <select class="form-control select2me" data-cy="po_department" id="department" data-placeholder="Select department" required="" name="department_id" >
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
                                </div>
                                <div class="col-md-6">
                                    <h3 class="form-section">
                                        Delivery information
                                    </h3>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Reff number <span class="required">
                                            </span></label>
                                        <div class="col-md-8">
                                            <input type="text" maxlength="45" name="invoice_no" class="form-control" data-cy="invoice_no" value="{{ old('invoice_no') }}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">PO date<span class="required">*
                                            </span></label>
                                        <div class="col-md-8">
                                            <input class="form-control form-control-inline date-picker" data-cy="bill_date" type="text" required name="bill_date"  autocomplete="off" data-date-format="{{ Session::get('default_date_format')}}"  placeholder="PO date" value="old('date')" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Expected delivery date<span class="required">*
                                            </span></label>
                                        <div class="col-md-8">
                                            <input class="form-control form-control-inline date-picker" data-cy="due_date" type="text" required  name="due_date"  autocomplete="off" data-date-format="{{ Session::get('default_date_format')}}"  placeholder="Expected delivery date" value="old('date')" />
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>              
                              
                        @livewire('expense.particular-detail')

                        <div class="form-body">
                            <!-- Start profile details -->
                            <div class="row">
                                <div class="col-md-6">    
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Notify vendor <span class="required">
                                            </span></label>
                                        <div class="col-md-4">
                                            <input type="checkbox" data-cy="notify_vendor" id="notify_" onchange="notifyPatron('notify_');" checked="" value="1"  class="make-switch" data-size="small">  
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="pull-right">
                                        <input type="hidden" id="is_notify_" name="notify" value="1">
                                        <input type="hidden" id="gst_type" name="gst_type" wire:model="gst_type">
                                        <input type="hidden" value="0" name="payment_status">
                                        <input type="hidden" value="0" name="payment_mode">
                                        <input type="hidden" value="2" name="type">
                                        <a href="/merchant/expense/viewlist/po" class="btn default">Cancel</a>
                                        <input id="subbtn" type="submit" value="Save & Send" class="btn blue" data-cy="po_save"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <!-- Side panel code for adding product/service -->
                    @include('app.merchant.product.side-panel-product',['redirect' => '/merchant/expense/po/create'])
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
                    <h4 class="modal-title">PO number</h4>
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
                                    <h5>Your PO number on auto-generate mode to save your time. Are you sure about changing this setting?</h5>

                                </div>

                                <div class="form-group" >
                                    <label for="autogen" class="col-md-12 control-label">
                                        <input type="radio" id="autogen" name="auto_generate" value="1" @if($po_auto_generate==1) checked="" @endif> Continue auto-generating numbers
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
                                        <input type="radio" class="icheck" id="auto2"  name="auto_generate" @if($po_auto_generate!=1) checked="" @endif value="0"/> I will add them manually each time
                                    </label>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="seq_id" name="seq_id" value="{{$prefix_id}}">
                    <button type="button" onclick="return saveExpenseSequence(4);" class="btn blue">Save</button>
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
