@extends('app.master')

@section('header')
<link href="/assets/global/plugins/summernote/summernote.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="page-content">

    <!-- BEGIN PAGE HEADER-->
    <div class="page-bar">
        <span class="page-title" style="float: left;">{{$title}}</span>
        {{ Breadcrumbs::render('create.invoice',$type) }}
    </div>


    <!-- END PAGE HEADER-->

    <!-- BEGIN SEARCH CONTENT-->
    <div class="row">
        <div class="col-md-12">
            
            <div class="row">
                <div class="col-md-12">
                    <div class="portlet ">
                        <div class="portlet-body" data-tour="invoice-pick-format">
                            <form action="" method="post" id="template_create" class="form-horizontal form-row-sepe mb-0">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <div class="form-body">
                                    <div class="form-group mb-0">
                                        <div class="col-md-12">
                                        
                                            <!--<div class="col-md-3 pl-1" style="padding-right: 0px;">
                                               {{-- @if($template_id=='' )onchange="invoicePreview(this.value);" @endif --}}
                                            <select data-cy="template_id" onchange="templateChange(this.value);" name="template_id" id="template_id"  required title="Pick an invoice format" class="form-control select2me" data-placeholder="Select format">
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
                                            
                                            <div class="col-md-3 pl-1 pr-0" >
                                                    <select data-placeholder="Select billing profile" onchange="setCurrency(this.value);" class="form-control select2me" id="billing_profile_id" data-cy="billing_profile_id" name="billing_profile_id">
                                                    <option value=""></option>
                                                    @foreach($billing_profile as $v)
                                                    <option @if($billing_profile_id==$v->id) selected @endif value="{{$v->id}}">{{$v->profile_name}} {{$v->gst_number}}</option>
                                                    @endforeach
                                                    </select>
                                                    <small class="form-text text-muted">Billing profile</small>
                                                <div class="help-block"></div>
                                            </div>-->
                                            <div class="col-md-2 pl-1 pr-0" >
                                                    <select data-cy="currency" required id="currency" name="currency" required class="form-control select2me" data-placeholder="Select...">
                                                        @foreach($currency_list as $v)
                                                        <option @if($currency==$v) selected @endif value="{{$v}}">{{$v}}</option>
                                                        @endforeach
                                                    </select>
                                                    <small class="form-text text-muted">Currency</small>
                                                <div class="help-block"></div>
                                            </div>
                                            <div id="contract_div"   class="col-md-3 pl-1 pr-0" >
                                                    <select data-placeholder="Select contract" id="contract_id" class="form-control select2me"  name="contract_id">
                                                    <option value=""></option>
                                                    @foreach($contract as $v)
                                                        <option @if($contract_id==$v->id) selected @endif value="{{$v->contract_id}}">{{$v->project_name}} {{$v->contract_code}}</option>
                                                        @endforeach
                                                    
                                                    </select>
                                                    <small class="form-text text-muted">Contract</small>
                                                <div class="help-block"></div>
                                            </div>
                                            @if($request_type==4)
                                            <div class="col-md-2 pl-1 pr-0" style="width: auto;">
                                            <select data-cy="invoice_type" name="invoice_type"  required class="form-control" data-placeholder="Select...">
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
                </div>
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
    <div class="portlet light bordered">
        <div class="portlet-body form">
            <div class="alert alert-danger" style="display: none;" id="invoiceerrorshow">
                <p id="invoiceerror_display">Please correct the below errors to complete registration.</p>
            </div>
            <form action="/merchant/invoice/invoicesave" onsubmit="return validateInvoice('');" id="invoice" method="post" class="form-horizontal" enctype="multipart/form-data">
                {!!Helpers::csrfToken('invoice')!!}
                
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
                            <div class="form-group" style="margin-right: 0px;">
                                <label class="control-label col-md-4">Select Customer <span class="required">* </span></label>
                                <div class="col-md-8" style="margin-left: 0px;">
                                    <div class="input-group">
                                        @if(count($customer_list)==1)
                                        @php $customer_id=$customer_list->first()->customer_id; @endphp
                                        @php $selected='selected' @endphp
                                        @else
                                        @php $selected='' @endphp
                                        @endif
                                        <select name="customer_id" data-cy="customer_id" id="customer_id" onchange="selectCustomer(this.value)" style="max-width: 100%;" required class="form-control select2me" data-placeholder="Select...">
                                            <option value=""></option>
                                            @if(!empty($customer_list))
                                            @foreach($customer_list as $v)
                                            <option {{$selected}} value="{{$v->customer_id}}">{{$v->customer_code}} | {{$v->first_name}} {{$v->last_name}}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                        <span id="cust_empty_error" class="help-block" style="color: maroon;display: none;">Select a customer to view past invoices</span>
                                        <span class="input-group-btn">
                                        <a onclick="showLedger();"  class="btn green ml10 popovers " style="padding: 6px 12px;margin-left: 5px;"  data-placement="top" data-container="body" data-trigger="hover" data-content="Pick a customer and view all invoices raised for the chosen customer"><i class="fa fa-list-ul"></i></a>
                                <a data-toggle="modal" title="Add new customer" style="padding: 6px 12px;margin-left: 0px;" href="#custom" class="btn green ml10 "><i class="fa fa-plus"></i></a>
                            
                            </span>
                                    </div>
                                </div>
</div>
                            @if(!empty($metadata['C']))
                            @foreach($metadata['C'] as $v)
                            <div class="form-group">
                                <label class="control-label col-md-4">{{$v->column_name}} @if($v->is_mandatory==1)<span class="required">* </span>@endif</label>
                                <div class="col-md-8">
                                    <div class="input-icon right">
                                        @if($v->column_datatype=="textarea")
                                        <span class="form-control cust_det grey" id="customer{{$v->column_id}}" style="height: 80px;" readonly> {{$v->value??''}}</span>
                                        @else
                                        <input type="text" id="customer{{$v->column_id}}" readonly class="form-control cust_det" data-cy="customer{{$v->column_id}}">
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            @endif
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
                                        <input type="text" {{$req}} value="@if(isset($v->value))<x-localize :date='$v->value' type='date' /> @endif" data-cy="invoice_detail_{{$v->column_name??''}}" id="{{$id}}" name="{{$field_name}}" autocomplete="off" class="form-control date-picker"  data-date-format="{{ Session::get('default_date_format')}}">
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
                                @if($v->function_id==4)<a onclick="showLedger();"  class="btn green ml10 popovers" style="padding: 6px 10px;margin-left: 0px;"  data-placement="top" data-container="body" data-trigger="hover" data-content="Pick a customer and view all invoices raised for the chosen customer"><i class="fa fa-list-ul"></i></a> @endif

                            </div>
                            @if($v->function_id==10)
                            <div class="form-group">
                                <label class="control-label col-md-4">Billing Period start date</label>
                                <div class="col-md-8">
                                    <input type="text" name="billing_start_date" data-cy="invoice_detail_{{$v->column_name??''}}" required class="form-control  date-picker" autocomplete="off" {!!$validate->date!!} data-date-format="{{ Session::get('default_date_format')}}" placeholder="Select start date">
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
                                <label class="control-label col-md-4">Upload file</label>
                                <div class="col-md-8">
                                    <div class="input-icon right">
                                        <input type="file" data-cy="invoice_detail_file_upload" accept="image/*,application/pdf" onchange="return validatefilesize(3000000, 'fileupload');" id="fileupload" name="scan_file">
                                        <span class="help-block red">* Max file size 3 MB</span>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @endif
                        </div>
                    </div>


                    @if(isset($properties['vehicle_det_section']))
                    <h3 class="form-section">{{$properties['vehicle_det_section']['title']??'VEHICLE DETAILS'}}</h3>
                    <div class="row">
                        <div class="col-md-6">
                            @if(!empty($metadata['BDS']))
                            @foreach($metadata['BDS'] as $v)
                            @if($v->position=='L')
                            @php if(!isset($v->value)) {$v->value="";} @endphp
                            @php if(isset($validate->{$v->column_datatype})){ $valid=$validate->{$v->column_datatype};}else{ $valid=""; } @endphp
                            <div class="form-group">
                                <label class="control-label col-md-4">{{$v->column_name}} @if($v->is_mandatory==1)<span class="required">* </span>@endif</label>
                                <div class="col-md-8">
                                    <div class="input-icon right">

                                        <input type="hidden" name="ids[]" value="{{$v->column_id}}" class="form-control">
                                        <input type="hidden" name="function_id[]" value="{{$v->function_id}}">
                                        @if($v->column_datatype=="textarea")
                                        <textarea type="text" data-cy="vehicle_detail_{{$v->column_name??''}}" name="newvalues[]" {!!$valid!!} class="form-control" placeholder="Enter specific value">{{$v->value??''}}</textarea>
                                        @elseif($v->column_datatype=="date")
                                        <input type="text" data-cy="vehicle_detail_{{$v->column_name??''}}" value="@if(isset($v->value))<x-localize :date='$v->value' type='date' /> @endif" name="newvalues[]" class="form-control date-picker" autocomplete="off" {!!$validate->date!!} data-date-format="{{ Session::get('default_date_format')}}">
                                        @elseif($v->column_datatype=="time")
                                        <input type="text" data-cy="vehicle_detail_{{$v->column_name??''}}" autocomplete="off" value="{{$v->value??''}}" name="newvalues[]" class="form-control timepicker timepicker-no-seconds">
                                        @else
                                        <input type="text" data-cy="vehicle_detail_{{$v->column_name??''}}" {!!$valid!!} value="{{$v->value??''}}" name="newvalues[]" class="form-control">
                                        @endif

                                    </div>
                                </div>
                            </div>
                            @endif
                            @endforeach
                            @endif
                        </div>
                        <div class="col-md-6">
                            @if(!empty($metadata['BDS']))
                            @foreach($metadata['BDS'] as $v)
                            @if($v->position=='R')
                            @php if(!isset($v->value)) {$v->value="";} @endphp
                            @php if(isset($validate->{$v->column_datatype})){ $valid=$validate->{$v->column_datatype};}else{ $valid=""; } @endphp
                            <div class="form-group">
                                <label class="control-label col-md-4">{{$v->column_name}} @if($v->is_mandatory==1)<span class="required">* </span>@endif</label>
                                <div class="col-md-8">
                                    <div class="input-icon right">
                                        <input type="hidden" name="ids[]" value="{{$v->column_id}}" class="form-control">
                                        <input type="hidden" name="function_id[]" value="{{$v->function_id}}">
                                        @if($v->column_datatype=="textarea")
                                        <textarea type="text" data-cy="vehicle_detail_{{$v->column_name??''}}" name="newvalues[]" {!!$valid!!} class="form-control" placeholder="Enter specific value">{{$v->value}}</textarea>
                                        @elseif($v->column_datatype=="date")
                                        <input type="text" data-cy="vehicle_detail_{{$v->column_name??''}}" value="@if(isset($v->value))<x-localize :date='$v->value' type='date' /> @endif" name="newvalues[]" class="form-control date-picker" autocomplete="off" {!!$validate->date!!} data-date-format="{{ Session::get('default_date_format')}}">
                                        @elseif($v->column_datatype=="time")
                                        <input type="text" data-cy="vehicle_detail_{{$v->column_name??''}}" autocomplete="off" value="{{$v->value??''}}" name="newvalues[]" class="form-control timepicker timepicker-no-seconds">
                                        @else
                                        <input type="text" data-cy="vehicle_detail_{{$v->column_name??''}}" {!!$valid!!} value="{{$v->value??''}}" name="newvalues[]" class="form-control">
                                        @endif

                                    </div>
                                </div>
                            </div>
                            @endif
                            @endforeach
                            @endif
                        </div>
                    </div>
                    @endif

                    @if($request_type==4)
                    <input type="hidden" name="payment_request_type" value="4">
                    @include('app.merchant.invoice.create_subscription',['is_carry'=>$is_carry])
                    @endif
                    @if($template_info->template_type=='travel')
                    @include('app.merchant.invoice.travel_particular')
                    @elseif($template_info->template_type=='franchise')
                    @include('app.merchant.invoice.franchise_particular')
                    @elseif($template_info->template_type=='construction')
                    @include('app.merchant.invoice.construction_particular')
                    @elseif($template_info->template_type=='nonbrandfranchise')
                    @include('app.merchant.invoice.non_brand_franchise_particular')
                    @else
                    @include('app.merchant.invoice.particular')
                    @endif
                    @include('app.merchant.invoice.create_footer')
                    @include('app.merchant.invoice.footer')
                    @endif
                </div>

                <!-- BEGIN SEARCH CONTENT-->
                @endsection

                <!-- add particulars label ends -->
               
                @section('footer')
                @if($template_id!='')
                <script>
                    mode = '{{$mode}}';
                    exist_paricular_cnt = '0';
                    setTaxDropdown();
                    @if(isset($customer_id))
                    selectCustomer({{$customer_id}});
                    @endif
                    @if(isset($product_list))
                    products = {!!$product_json!!};
                    @endif

                    @if(isset($tax_array))
                    tax_master = '{!!$tax_array!!}';
                    tax_array = JSON.parse(tax_master);
                    taxes_rate = '{!!$tax_rate_array!!}';
                    @endif

                    particular_values = '{{$template_info->particular_values}}';

                    drawInvoiceParticularFormat('{!!$template_info->particular_column!!}', '{{$template_info->particular_total}}');
                    @php $default_particular = json_decode($template_info->default_particular, 1); @endphp
                    @if(!empty($default_particular))
                    @foreach($default_particular as $v)
                    try{
                        AddInvoiceParticularRow('{{$v}}');
                    }catch(o){}
                    @endforeach
                    @endif
                    
                    /*@php $default_tax = json_decode($template_info->default_tax, 1);
                    @endphp
                    @if(!empty($default_tax))
                    @foreach($default_tax as $v)
                    AddInvoiceTax('{{$v}}');
                    @endforeach
                    @endif*/

                    @if(!empty($plugin['supplier']))
                    @foreach($plugin['supplier'] as $v)
                    AddsupplierRow({{$v}});
                    @endforeach
                    @endif

                    
                    var datetime='{!!$setting['has_datetime']??"0"!!}';

                    
                    @if(isset($properties['travel_section']))
                        AddSecRow(tb_col, 'tb',datetime);
                        AddSecRow(tc_col, 'tc',datetime);
                    @endif

                    @if(isset($properties['hotel_section']))
                    AddSecRow(hb_col, 'hb',datetime);
                    @endif

                    @if(isset($properties['facility_section']))
                    AddSecRow(fs_col, 'fs',datetime);
                    @endif

                    
                </script>

            @endif
            
            <script>
            $('#billing_profile_id').select2({
                
            }).on('select2:open', function (e) {
                pind = $(this).index();
                if (document.getElementById('profilelist' + pind)) {
                } else {
                    $('.select2-results').append('<div class="wrapper" > <a href="/merchant/profile/gstprofile" id="profilelist'+pind+'" target="_BLANK" class="clicker" >Add new profile</a> </div>');
                }
            });
            $('#currency').select2({
                
            }).on('select2:open', function (e) {
                pind = $(this).index();
                if (document.getElementById('currencylist' + pind)) {
                } else {
                    $('.select2-results').append('<div class="wrapper" > <a href="/merchant/profile/currency" id="currencylist'+pind+'"  target="_BLANK" class="clicker" >Add new currency</a> </div>');
                }
            });

            $('#template_id').select2({
                
            }).on('select2:open', function (e) {
                pind = $(this).index();
                if (document.getElementById('templatelists' + pind)) {
                } else {
                    $('.select2-results').append('<div class="wrapper" > <a href="/merchant/template/newtemplate" id="templatelists'+pind+'"   class="clicker" >Add new format</a> </div>');
                }
            });
            </script>
                @endsection
               
                

               