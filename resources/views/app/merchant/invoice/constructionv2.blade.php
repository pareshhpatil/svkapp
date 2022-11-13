@extends('app.master')

@section('header')
<link href="/assets/global/plugins/summernote/summernote.min.css" rel="stylesheet">
<style>
    .uppy-Dashboard-AddFiles-title {
        font-size: 15px !important;
        font-weight: 400 !important;
    }

    .uppy-Dashboard-inner {

        border: 0px solid #eaeaea !important;
        border-radius: 5px !important;
    }

    [data-uppy-drag-drop-supported=true] .uppy-Dashboard-AddFiles {
        margin: 0px !important;
        padding-bottom: 9px;
        height: calc(100%) !important;
        border-radius: 3px;
        border: 1px dashed #dfdfdf;
    }

    .steps {
        background-color: transparent !important;
        border: 2px #18AEBF solid !important;
        color: #18AEBF !important;
        width: auto !important;
    }
</style>
@endsection

@section('content')
<div class="page-content">

    <!-- BEGIN PAGE HEADER-->
    <div class="page-bar">
        <span class="page-title" style="float: left;">{{$title}}</span>
        {{ Breadcrumbs::render('create.invoice','Invoice') }}
        @if($link=='')
        <a href="/merchant/template/viewlist" class="btn green pull-right"> Invoice formats </a>
        <a href="/merchant/template/newtemplate" class="btn green pull-right mr-1"> Add new format</a>
        @else
        <span class=" pull-right badge badge-pill status steps" style="padding: 6px 16px 6px 16px !important;margin-bottom: 15px">Step <span x-text="step">1</span> of 3</span>

        @endif
    </div>


    <!-- END PAGE HEADER-->

    <!-- BEGIN SEARCH CONTENT-->
    <div class="row">
        <div class="col-md-12">
            @if(empty($format_list))
            <div class="alert alert-info">
                <button type="button" class="close" data-dismiss="alert"></button>
                <strong>Info!</strong>
                <div class="media">
                    <p class="media-heading">You need to create a template before sending invoices. Please create a bill template using the Create template button below</p>
                    <p><a href="/merchant/template/newtemplate" class="btn blue">Create Template</a></p>
                </div>

            </div>
            @else

            <div class="row">
                <div class="col-md-12">
                    @if($link=='')
                    <div class="portlet " style="margin-bottom: 15px;">
                        <div class="portlet-body" data-tour="invoice-pick-format">
                            <form action="" method="post" id="template_create" class="form-horizontal form-row-sepe mb-0">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <div class="form-body">
                                    <div class="form-group mb-0">
                                        <div class="col-md-12">

                                            <div class="col-md-3 pl-1" style="padding-right: 0px;">
                                                {{-- @if($template_id=='' )onchange="invoicePreview(this.value);" @endif --}}
                                                <select data-cy="template_id" name="template_id" id="template_id" required title="Pick an invoice format" class="form-control select2me" data-placeholder="Select format">
                                                    <option value=""></option>
                                                    @if(!empty($format_list))
                                                    @foreach($format_list as $v)
                                                    @if(($v->template_type!='travel_ticket_booking' && $v->template_type!='scan') || $subscription!=1)
                                                    @if($template_id==$v->template_id)
                                                    <option selected value="{{$v->template_id}}" selected>{{$v->template_name}}</option>
                                                    @else
                                                    <option value="{{$v->template_id}}">{{$v->template_name}}</option>
                                                    @endif
                                                    @endif
                                                    @endforeach
                                                    @endif
                                                </select>
                                                <small class="form-text text-muted">Invoice format</small>
                                                <div class="help-block"></div>
                                            </div>
                                            <div class="col-md-3 pl-1 pr-0">
                                                <select data-placeholder="Select billing profile" onchange="setCurrency(this.value);" class="form-control select2me" id="billing_profile_id" data-cy="billing_profile_id" name="billing_profile_id">
                                                    <option value=""></option>
                                                    @foreach($billing_profile as $v)
                                                    <option @if($billing_profile_id==$v->id) selected @endif value="{{$v->id}}">{{$v->profile_name}} {{$v->gst_number}}</option>
                                                    @endforeach
                                                </select>
                                                <small class="form-text text-muted">Billing profile</small>
                                                <div class="help-block"></div>
                                            </div>
                                            <div class="col-md-2 pl-1 pr-0">
                                                <select data-cy="currency" required id="currency" name="currency" required class="form-control select2me" data-placeholder="Select...">
                                                    @foreach($currency_list as $v)
                                                    <option @if($currency==$v) selected @endif value="{{$v}}">{{$v}}</option>
                                                    @endforeach
                                                </select>
                                                <small class="form-text text-muted">Currency</small>
                                                <div class="help-block"></div>
                                            </div>

                                            @if($type=='construction')
                                            <div class="col-md-3 pl-1 pr-0">
                                                <select data-placeholder="Select contract" required class="form-control select2me" name="contract_id">
                                                    <option value=""></option>
                                                    @foreach($contract as $v)
                                                    <option @if($contract_id==$v->contract_id) selected @endif value="{{$v->contract_id}}">{{$v->project_name}} | {{$v->contract_code}}</option>
                                                    @endforeach

                                                </select>
                                                <small class="form-text text-muted">Contract</small>
                                                <div class="help-block"></div>
                                            </div>
                                            @endif
                                            @if($request_type==4)
                                            <div class="col-md-2 pl-1 pr-0" style="width: auto;">
                                                <select data-cy="invoice_type" name="invoice_type" required class="form-control" data-placeholder="Select...">
                                                    <option @if($invoice_type==1) selected @endif value="1">Invoice</option>
                                                    <option @if($invoice_type==2) selected @endif value="2">Estimate</option>
                                                </select>
                                                <div class="help-block"></div>
                                            </div>
                                            @endif
                                            <div class="col-md-7 pl-1" style="width: auto;">
                                                <button type="submit" class="btn blue"> Select</button>
                                                <input type="hidden" name="request_type" value="{{$request_type}}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    @endif
                </div>
                @endif
            </div>

        </div>
    </div>

    @if($template_id=='')
    <div class="row" id="preview_div" style="display: none;">
        <div class="col-md-12">
            <div class="portlet light">
                <div class="portlet-body form">
                    <h4 class="form-section">Preview format</h4>
                    <div id="preview">

                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <!-- Show create invoice form -->
    @if($link=='')
    <span class=" pull-right badge badge-pill status steps" style="padding: 6px 16px 6px 16px !important;margin-bottom: 15px">Step <span x-text="step">1</span> of 3</span>
    <br>
    <br>
    @endif
    <div class="portlet light bordered">
        <div class="portlet-body form">
            <div class="alert alert-danger" style="display: none;" id="invoiceerrorshow">
                <p id="invoiceerror_display">Please correct the below errors to complete registration.</p>
            </div>
            <form action="/merchant/invoice/save" onsubmit="return checkCurrentContractAmount('');" id="invoice" method="post" class="form-horizontal" enctype="multipart/form-data">
                @csrf

                <input type="hidden" id="product_taxation_type" name="product_taxation_type" value="{{$product_taxation_type}}">

                <div>

                    <div class="row invoice-logo">
                        <div class="col-xs-6">
                            @if($invoice_type==2)
                            <div class="form-group">
                                <label class="control-label col-md-4">Estimate title <span class="required"> </span>
                                    <i class="popovers fa fa-info-circle support blue" data-container="body" data-trigger="hover" data-content="This is the title which shows in the estimate sent to the customer. You can customize this by changing this value." data-original-title="" title=""></i>
                                </label>
                                <div class="col-md-6">
                                    <input type="text" maxlength="100" class="form-control" data-cy="invoice_title" name="invoice_title" value="{{$template_info->invoice_title}}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">Auto-generate invoice <span class="required"> </span>
                                    <i class="popovers fa fa-info-circle support blue" data-container="body" data-trigger="hover" data-content="Keep this toggle On if you would like an invoice to be auto generated once the customer pays the estimate online. The auto-generated invoice copy is sent to your customer on email & SMS and the same invoice is added in your Swipez account." data-original-title="" title=""></i>
                                </label>
                                <div class="col-md-6">
                                    <input type="checkbox" name="generate_estimate_invoice" checked data-cy="invoice_detail_generate_estimate_invoice" value="1" class="make-switch" data-size="small">
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="col-xs-6">
                        </div>
                    </div>
                    <h3 class="form-section">@if($invoice_type==2)Estimate @else Invoice @endif information</h3>
                    <div class="row">
                        <div class="col-md-6" data-tour="invoice-create-customer-select">

                            <div class="form-group">
                                <label class="control-label col-md-4">Project name</label>
                                <div class="col-md-8">
                                    <div class="input-icon right">
                                        <input type="text" readonly class="form-control cust_det" value="{{$contract_detail->project_name}} | {{$contract_detail->project_id}}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">Company name</label>
                                <div class="col-md-8">
                                    <div class="input-icon right">
                                        <input type="text" readonly class="form-control cust_det" value="{{$customer->company_name}}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">Contract code</label>
                                <div class="col-md-8">
                                    <div class="input-icon right">
                                        <input type="text" readonly class="form-control cust_det" value="{{$contract_detail->contract_code}}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">Client code</label>
                                <div class="col-md-8">
                                    <div class="input-icon right">
                                        <input type="text" readonly class="form-control cust_det" value="{{$customer->customer_code}}">
                                        <input type="hidden" name="customer_id" value="{{$customer->customer_id}}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">Client name</label>
                                <div class="col-md-8">
                                    <div class="input-icon right">
                                        <input type="text" readonly class="form-control cust_det" value="{{$customer->first_name}} {{$customer->last_name}}">
                                    </div>
                                </div>
                            </div>


                        </div>
                        <div class="col-md-6" data-tour="invoice-create-billing-information">

                            <input type="hidden" name="billing_profile_id" value="{{$billing_profile_id}}">
                            <input type="hidden" name="currency" value="{{$currency}}">
                            @php $is_carry=0; @endphp
                            @if(!empty($metadata['H']))
                            @foreach($metadata['H'] as $v)
                            @php $req=""; @endphp
                            @if($v->position=='R' && ($request_type!=4 or ($v->column_position!=5 && $v->column_position!=6)))
                            @if($request_type!=2 || $v->function_id!=9)
                            @php if(isset($validate->{$v->column_datatype})){ $valid=$validate->{$v->column_datatype};} @endphp

                            @if($v->is_mandatory==1)
                            @php $req='required'; @endphp
                            @endif
                            @php if(!isset($v->value)) {$v->value="";} @endphp
                            @php if(!isset($v->html_id)) {$id="";}else{ $id=$v->html_id; } @endphp
                            <div class="form-group">
                                @if($v->column_name == 'Billing cycle name')
                                <label class="control-label col-md-4">{{$v->column_name}}@if($v->column_datatype=="percent") (%)@endif
                                    <i class="popovers fa fa-info-circle support blue" data-placement="top" data-container="body" data-trigger="hover" data-content="Billing cycle name field is not visible to your customer i.e. this field will not be shown in the invoice you create. The purpose of this field is to group your invoices for your reporting purposes. For ex. All invoice raised in April 2021 can be tagged as 'Apr2021'" data-original-title="" title=""></i>
                                </label>
                                @else
                                <label class="control-label col-md-4">{{$v->column_name}}@if($v->column_datatype=="percent") (%)@endif @if($v->is_mandatory==1)<span class="required">* </span>@endif</label>
                                @endif
                                <div @if($v->function_id==4) class="col-md-7" @else class="col-md-8" @endif>
                                    <div class="input-icon right">
                                        @if($v->save_table_name=="request")
                                        @php $field_name="requestvalue[]"; @endphp
                                        <input type="hidden" name="col_position[]" value="{{$v->column_position}}">
                                        @else
                                        @php $field_name="newvalues[]"; @endphp
                                        <input type="hidden" name="ids[]" value="{{$v->column_id}}" class="form-control">
                                        @endif
                                        <input type="hidden" name="function_id[]" value="{{$v->function_id}}">
                                        <input type="hidden" value="{{$v->param}}" id="{{$id}}_param">
                                        @if($v->column_datatype=="textarea")
                                        <textarea type="text" name="{{$field_name}}" data-cy="invoice_detail_{{$v->column_name??''}}" class="form-control" placeholder="Enter specific value">{{$v->value}}</textarea>
                                        @elseif($v->column_datatype=="date")
                                        <input type="text" {{$req}} value="@if(isset($v->value))<x-localize :date='$v->value' type='date' /> @endif" data-cy="invoice_detail_{{$v->column_name??''}}" id="{{$id}}" name="{{$field_name}}" autocomplete="off" class="form-control date-picker" data-date-format="{{ Session::get('default_date_format')}}">
                                        @elseif($v->column_datatype=="time")
                                        <input type="text" {{$req}} autocomplete="off" data-cy="invoice_detail_{{$v->column_name??''}}" value="{{$v->value??''}}" id="{{$id}}" name="{{$field_name}}" class="form-control timepicker timepicker-no-seconds">
                                        @elseif($v->function_id==15)
                                        <select name="{{$field_name}}" required class="form-control select2me" data-cy="invoice_detail_einvoice_type" data-placeholder="Select...">
                                            @foreach($einvoice_type as $ev)
                                            <option value="{{$ev->config_value}}">{{$ev->description}}</option>
                                            @endforeach
                                        </select>
                                        @else
                                        @if($v->function_id==9)
                                        <input type="hidden" id="{{$id}}" {!!$valid!!} data-cy="invoice_detail_{{$v->column_name??''}}" @if($v->column_name !='Billing cycle name' ) {{$req}} @endif value="{{$v->value??''}}" name="{{$field_name}}" class="form-control">
                                        <input type="text" value="{{$v->display_value??''}}" class="form-control" readonly="readonly">
                                        @else
                                        <input type="text" id="{{$id}}" {!!$valid!!} data-cy="invoice_detail_{{$v->column_name??''}}" @if($v->column_name !='Billing cycle name' ) {{$req}} @endif value="{{$v->value??''}}" name="{{$field_name}}" class="form-control">
                                        @endif
                                        @endif
                                    </div>
                                </div>
                                @if($v->function_id==4)<a onclick="showLedger();" class="btn green ml10 popovers" style="padding: 6px 10px;margin-left: 0px;" data-placement="top" data-container="body" data-trigger="hover" data-content="Pick a customer and view all invoices raised for the chosen customer"><i class="fa fa-list-ul"></i></a> @endif

                            </div>
                            @if($v->function_id==10)
                            <div class="form-group">
                                <label class="control-label col-md-4">Billing Period start date</label>
                                <div class="col-md-8">
                                    <input type="text" name="billing_start_date" data-cy="invoice_detail_{{$v->column_name??''}}" required class="form-control  date-picker" autocomplete="off" data-date-format="{{ Session::get('default_date_format')}}" placeholder="Select start date">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">Add duration</label>
                                <div class="col-md-4">
                                    <input type="number" name="billing_period" data-cy="invoice_detail_billing period" required class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <select name="period_type" required class="form-control" data-cy="invoice_detail_period_type" data-placeholder="Select...">
                                        <option value="days">Days</option>
                                        <option value="month">Month</option>
                                    </select>
                                </div>
                            </div>
                            @endif
                            @if($v->function_id==4 && $request_type==4)
                            @php $is_carry=1; @endphp
                            <div class="form-group">
                                <label class="control-label col-md-4">Carry forward dues?</label>
                                <div class="col-md-8">
                                    <div class="input-icon">
                                        <input type="checkbox" name="carry_due" data-cy="invoice_detail_carry_due" value="1" class="make-switch" data-size="small">
                                    </div>
                                </div>
                            </div>
                            @endif

                            @else
                            <input type="hidden" name="ids[]" value="{{$v->column_id}}" class="form-control">
                            <input type="hidden" name="newvalues[]" value="">
                            <input type="hidden" name="function_id[]" value="{{$v->function_id}}">
                            @endif
                            @endif
                            @endforeach
                            @if(isset($plugin['is_prepaid']))
                            <div class="form-group">
                                <label class="control-label col-md-4">Advance Received</label>
                                <div class="col-md-8">
                                    <div class="input-icon right">
                                        <input type="text" data-cy="invoice_detail_advance_received" {!!$validate->money!!} onblur="calculategrandtotal();" id="advance_amt" name="advance" class="form-control">
                                    </div>
                                </div>
                            </div>
                            @endif
                            @if(isset($plugin['has_upload']))
                            <div class="form-group">
                                <label class="control-label col-md-4"></label>
                                <div class="col-md-8">
                                    @if(isset($plugin['files']) && !empty($plugin['files'][0]))
                                    <span class="help-block">
                                        <div id="docviewbox">
                                            @foreach ($plugin['files'] as $key=>$item)
                                            <span class=" btn btn-xs green" style="margin-bottom: 5px;margin-left: 0px !important">
                                                <a class=" btn-xs " target="_BLANK" href="{{$item}}" title="Click to view full size">{{substr(substr(substr(basename($item), 0, strrpos(basename($item), '.')),0,-4),0,10)}}..</a>
                                                <a href="#delete_doc" onclick="setdata('{{substr(substr(substr(basename($item), 0, strrpos(basename($item), '.')),0,-4),0,10)}}','{{$item}}');" data-toggle="modal"> <i class=" popovers fa fa-close" style="color: #A0ACAC;" data-placement="top" data-container="body" data-trigger="hover" data-content="Remove doc"></i> </a>
                                            </span>
                                            @php

                                            @endphp
                                            @endforeach
                                        </div>
                                        <a onclick="document.getElementById('newImageDiv').style.display = 'block';" class="UppyModalOpenerBtn btn btn-xs btn-link">Upload doc
                                        </a>
                                    </span>
                                    <span id="newImageDiv" style="display: none;">
                                        <input type="hidden" name="file_upload" id="file_upload" value="{{implode(',',$plugin['files'])}}">
                                        <div id="drag-drop-area2"></div>
                                    </span>
                                    @else
                                    <div class="input-icon right">

                                        <input type="hidden" name="file_upload" id="file_upload" value="">
                                        <a class="UppyModalOpenerBtn btn  default">Add attachments</a>
                                        <div id="docviewbox" class="mt-1">
                                        </div>

                                        <div id="drag-drop-area2"></div>

                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endif
                            @endif
                        </div>
                    </div>






                    @endif

                </div>
        </div>
    </div>
    <div class="portlet light bordered">
        <div class="portlet-body form">
            <div class="row">
                <div class="col-md-12">
                <div class="col-md-2">
                        <div class="form-group">
                            <p>Narrative</p>
                            <input type="text"  name="narrative" @isset($narrative) value="{{$narrative}}" @endisset class="form-control">
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                    @isset($plugin['has_covering_note'])
                        <div class="form-group">
                            <p><label class="control-label col-md-3 w-auto">Select covering note</label><br></p>
                            <div class="col-md-5">
                                <select name="covering_id" onchange="showEditNote();" data-cy="plugin_covering_id" id="covering_select" class="form-control" data-placeholder="Select...">
                                    <option value="0">Select covering note</option>
                                    @if(!empty($covering_list))
                                    @foreach($covering_list as $v)
                                    <option @isset($plugin['default_covering_note']) @if($plugin['default_covering_note']==$v->covering_id) selected @endif @endisset value="{{$v->covering_id}}">{{$v->template_name}}</option>
                                    @endforeach
                                    @endif
                                </select>
                                <a class="hidden" id="conf_cov" data-toggle="modal" href="#con_coveri"></a>
                            </div>
                            <div class=" col-md-2" id="edit_note_div" style="display:  none  ;">
                                <a class="btn mb-1 green " onclick="EditCoveringNote();" href="javascript:;">
                                    Edit note</a>
                            </div>
                            <div class=" col-md-2">
                                <a class="btn mb-1 green pull-right" onclick="AddCoveringNote();" href="javascript:;">
                                    Add new note</a>
                            </div>
                        </div>
                        @endisset
                    </div>
                   
                    
                    <div class="col-md-4">
                        <div class="pull-right">
                            <p>&nbsp;</p>
                            <input type="hidden" name="link" value="{{$link}}">
                            <input type="hidden" name="contract_id" value="{{$contract_id}}">
                            <input type="hidden" name="template_id" value="{{$template_id}}">
                            <a href="/merchant/collect-payments" class="btn green">Cancel</a>
                            <button type="submit" class="btn blue">Add particulars</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </form>



    @include('app.merchant.cover-note.add-covernote-modal')
    @include('app.merchant.cover-note.edit-covernote-modal')



    <div class="modal  fade" id="confirm_box" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Discard changes</h4>
                </div>
                <div class="form-body form-horizontal form-row-sepe" style="padding: 10px">
                    <p>Changes will not be saved. Do you want to proceed?</p>
                </div>



                <div class="modal-footer">
                    <button type="button" data-cy="covering-btn-close" id="cancel_confirm_box" class="btn default" data-dismiss="modal">Close</button>
                    <input type="button" data-cy="editcovering-btn-save" onclick="closeSidePanelcover()" value="Discard" class="btn blue">
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- BEGIN SEARCH CONTENT-->
    @endsection

    <!-- add particulars label ends -->

    @section('footer')
    @if($template_id!='')
    <script>
        mode = '{{$mode}}';
        exist_paricular_cnt = '0';
        @if(isset($customer_id))
        selectCustomer({
            {
                $customer_id
            }
        });
        @endif
    </script>
    @endif
    <script>
        $('#billing_profile_id').select2({

        }).on('select2:open', function(e) {
            pind = $(this).index();
            if (document.getElementById('profilelist' + pind)) {} else {
                $('.select2-results').append('<div class="wrapper" > <a href="/merchant/profile/gstprofile" id="profilelist' + pind + '" target="_BLANK" class="clicker" >Add new profile</a> </div>');
            }
        });
        $('#currency').select2({

        }).on('select2:open', function(e) {
            pind = $(this).index();
            if (document.getElementById('currencylist' + pind)) {} else {
                $('.select2-results').append('<div class="wrapper" > <a href="/merchant/profile/currency" id="currencylist' + pind + '"  target="_BLANK" class="clicker" >Add new currency</a> </div>');
            }
        });

        $('#template_id').select2({

        }).on('select2:open', function(e) {
            pind = $(this).index();
            if (document.getElementById('templatelists' + pind)) {} else {
                $('.select2-results').append('<div class="wrapper" > <a href="/merchant/template/newtemplate" id="templatelists' + pind + '"   class="clicker" >Add new format</a> </div>');
            }
        });
        invoice_construction = true;


        $('.productselect').select2({
            tags: true,
            insertTag: function(data, tag) {
                var $found = false;
                $.each(data, function(index, value) {
                    if ($.trim(tag.text).toUpperCase() == $.trim(value.text).toUpperCase()) {
                        $found = true;
                    }
                });
                if (!$found) data.unshift(tag);
            }
        }).on('select2:open', function(e) {
            pind = $(this).index();
            var index = $(".productselect").index(this);
            index += 1;
            if (document.getElementById('prolist' + pind)) {} else {
                $('.select2-results').append('<div class="wrapper" id="prolist' + pind + '" > <a class="clicker" onclick="billIndex(' + index + ',' + index + ',0);">Add new bill code</a> </div>');
            }
        });
    </script>


    <script src="https://releases.transloadit.com/uppy/v1.28.1/uppy.min.js"></script>
    <script>
        var newdocfileslist = [];
        //uppy file upload code
        var uppy = Uppy.Core({
            autoProceed: true,
            restrictions: {
                maxFileSize: 3000000,
                maxNumberOfFiles: 10,
                minNumberOfFiles: 1,
                allowedFileTypes: ['.jpg', '.png', '.jpeg', '.pdf']
            }
        });

        uppy.use(Uppy.Dashboard, {
            target: 'body',
            trigger: '.UppyModalOpenerBtn',
            inline: false,
            height: 40,
            maxHeight: 200,

            hideAfterFinish: true,
            showProgressDetails: false,
            hideUploadButton: false,
            hideRetryButton: false,
            hidePauseResumeButton: false,
            hideCancelButton: false,
        });

        uppy.use(Uppy.XHRUpload, {
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            endpoint: '/merchant/uppyfileupload/uploadImage/invoice',
            method: 'post',
            formData: true,
            fieldName: 'image'
        });

        uppy.on('file-added', (file) => {
            document.getElementById("error").innerHTML = '';
            console.log('file-added');
        });

        uppy.on('upload', (data) => {
            console.log('Starting upload');
        });
        uppy.on('upload-success', (file, response) => {
            if (response.body.fileUploadPath != undefined) {
                path = response.body.fileUploadPath;
                extvalue = document.getElementById("file_upload").value;
                newdocfileslist.push(path);
                deletedocfile('');
                if (extvalue != '') {
                    document.getElementById("file_upload").value = extvalue + ',' + path;
                } else {
                    document.getElementById("file_upload").value = path;
                }
            }
            if (response.body.status == 300) {
                try {
                    document.getElementById("error").innerHTML = response.body.errors;
                } catch (o) {}
                uppy.removeFile(file.id);
            } else {
                try {
                    document.getElementById("error").innerHTML = '';
                } catch (o) {}
            }
        });
        uppy.on('complete', (result) => {
            //console.log('successful files:', result.successful)
            //console.log('failed files:', result.failed)
        });
        uppy.on('error', (error) => {
            //console.error(error.stack);
        });
    </script>
    <div class="modal fade" id="delete_doc" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 id="poptitle" class="modal-title">Delete attachment</h4>
                    <input type="hidden" id="docfullurl">
                </div>
                <div class="modal-body">
                    Do you want to permanently delete this attachment from this invoice?
                </div>
                <div class="modal-footer">
                    <button type="button" id="closeconformdoc" class="btn default" data-dismiss="modal">Cancel </button>
                    <button type="button" onclick="deletedocfile('delete')" id="deleteanchor" class="btn delete">Delete</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <script>
        function setdata(name, fullurl) {

            document.getElementById('poptitle').innerHTML = "Delete attachment - " + name;
            document.getElementById('docfullurl').value = fullurl;
        }

        function deletedocfile(x) {
            var html = document.getElementById('docviewbox').innerHTML;
            if (x == 'delete') {
                var fullurl = document.getElementById('docfullurl').value;
                var index = newdocfileslist.indexOf(fullurl);
                if (index !== -1) {
                    newdocfileslist.splice(index, 1);
                }
            }

            for (var i = 0; i < newdocfileslist.length; i++) {
                var filenm = newdocfileslist[i].substring(newdocfileslist[i].lastIndexOf('/') + 1);
                filenm = filenm.split('.').slice(0, -1).join('.')
                filenm = filenm.substring(0, filenm.length - 4);
                html = html + '<span class=" btn btn-xs green" style="margin-bottom: 5px;margin-left: 0px !important;margin-right: 5px !important">' +
                    '<a class=" btn btn-xs " target="_BLANK" href="' + newdocfileslist[i] + '" title="Click to view full size">' + filenm.substring(0, 10) + '..</a>' +
                    '<a href="#delete_doc" onclick="setdata(\'' + filenm.substring(0, 10) + '\',\'' + newdocfileslist[i] + '\');"   data-toggle="modal"> ' +
                    ' <i class=" popovers fa fa-close" style="color: #A0ACAC;" data-placement="top" data-container="body" data-trigger="hover"  data-content="Remove doc"></i>   </a> </span>';
            }
            clearnewuploads('no');
            document.getElementById('docviewbox').innerHTML = html;
            document.getElementById('closeconformdoc').click();
        }

        function clearnewuploads(x) {
            document.getElementById("file_upload").value = '';

            var filesnm = '';

            for (var i = 0; i < newdocfileslist.length; i++) {
                if (filesnm != '')
                    filesnm = filesnm + ',' + newdocfileslist[i];
                else
                    filesnm = filesnm + newdocfileslist[i];
            }
            document.getElementById("file_upload").value = filesnm;
        }
    </script>
    @endsection