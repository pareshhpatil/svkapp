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
    </style>
@endsection

@section('content')
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="page-bar">  
    <span class="page-title" style="float: left;">{{$title}} @if($request_type==1) invoice @elseif($request_type==2) estimate @else subscription @endif </span>
    {{ Breadcrumbs::render('update.invoice',$request_type) }}
    </div>
    <!-- END PAGE HEADER-->

    <!-- BEGIN SEARCH CONTENT-->


    <!-- Show create invoice form -->
    <div class="portlet light bordered">
        <div class="portlet-body form">
            <div class="alert alert-danger" style="display: none;" id="invoiceerrorshow">
                <p id="invoiceerror_display">Please correct the below errors to complete registration.</p>
            </div>
            <form action="/merchant/invoice/invoiceupdate" onsubmit="return checkCurrentContractAmount('{{$payment_request_id}}');" id="invoice" method="post" class="form-horizontal" enctype="multipart/form-data">
                {!!Helpers::csrfToken('invoice')!!}
             
                <input type="hidden" id="product_taxation_type" name="product_taxation_type" value="{{$invoice_product_taxation}}">
               
                <div>
                    <div class="row invoice-logo">
                        <div class="col-xs-6">
                            @if($invoice_type==2)
                            <div class="form-group">
                                <label class="control-label col-md-4">Estimate title <span class="required"> </span>
                                    <i class="popovers fa fa-info-circle support blue" data-container="body" data-trigger="hover" data-content="This is the title which shows in the estimate sent to the customer. You can customize this by changing this value." data-original-title="" title=""></i>
                                </label>
                                <div class="col-md-6">
                                    <input type="text" maxlength="100" class="form-control" name="invoice_title" value="{{$plugin['invoice_title']??'Proforma invoice'}}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">Auto-generate invoice <span class="required"> </span>
                                    <i class="popovers fa fa-info-circle support blue" data-container="body" data-trigger="hover" data-content="Keep this toggle On if you would like an invoice to be auto generated once the customer pays the estimate online. The auto-generated invoice copy is sent to your customer on email & SMS and the same invoice is added in your Swipez account." data-original-title="" title=""></i>
                                </label>
                                <div class="col-md-6">
                                 <input type="checkbox" name="generate_estimate_invoice" @if(isset($info->generate_estimate_invoice) && $info->generate_estimate_invoice==1)checked @endif data-cy="invoice_detail_generate_estimate_invoice" value="1" class="make-switch" data-size="small">
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
                                        <input type="text"  readonly class="form-control cust_det" value="{{$contract_detail->project_name}}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">Company name</label>
                                <div class="col-md-8">
                                    <div class="input-icon right">
                                        <input type="text"  readonly class="form-control cust_det" value="{{$customer->company_name}}">
                                    </div>
                                </div>
                            </div>
                        <div class="form-group">
                                <label class="control-label col-md-4">Contract code</label>
                                <div class="col-md-8">
                                    <div class="input-icon right">
                                        <input type="text"  readonly class="form-control cust_det" value="{{$contract_detail->contract_code}}">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="control-label col-md-4">Client code</label>
                                <div class="col-md-8">
                                    <div class="input-icon right">
                                        <input type="text"  readonly class="form-control cust_det" value="{{$customer->customer_code}}">
                                        <input type="hidden" name="customer_id" value="{{$customer->customer_id}}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">Client name</label>
                                <div class="col-md-8">
                                    <div class="input-icon right">
                                        <input type="text"  readonly class="form-control cust_det" value="{{$customer->first_name}} {{$customer->last_name}}">
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                        <div class="col-md-6" data-tour="invoice-create-billing-information">
                            @if(count($billing_profile)>0)
                            @if(count($billing_profile)>1)
                            <div class="form-group">
                                <label class="control-label col-md-4">Billing profile</label>
                                <div class="col-md-8">
                                    <select onchange="setGSTInvoiceSeq(this.value);" class="form-control select2me" name="billing_profile_id">
                                        <option value="0">Select Billing profile</option>
                                        @foreach($billing_profile as $v)
                                        <option @if($info->billing_profile_id==$v->id) selected @endif value="{{$v->id}}">{{$v->profile_name}} {{$v->gst_number}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @else
                            <input type="hidden" name="billing_profile_id" value="{{$billing_profile->first()->id}}">
                            @endif
                            @endif

                            @if(count($currency_list)>0)
                            @if(count($currency_list)>1)
                            <div class="form-group">
                                <label class="control-label col-md-4">Currency</label>
                                <div class="col-md-8">
                                    <select  class="form-control select2me" name="currency">
                                        <option value="0">Select Currency</option>
                                        @foreach($currency_list as $v)
                                        <option @if($info->currency==$v) selected @endif value="{{$v}}">{{$v}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @else
                            <input type="hidden" name="currency" value="{{$currency_list[0]}}">
                            @endif
                            @endif

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
                                        <input type="hidden" name="request_function_id[]" value="{{$v->function_id}}">
                                        @else
                                        @if($v->id>0)
                                        @php $field_name="existvalues[]"; @endphp
                                        <input type="hidden" name="existids[]" value="{{$v->id}}" class="form-control">
                                        @else
                                        @php $field_name="newvalues[]"; @endphp
                                        <input type="hidden" name="ids[]" value="{{$v->column_id}}" class="form-control">
                                        @endif
                                        <input type="hidden" name="function_id[]" value="{{$v->function_id}}">
                                        @endif
                                        @if($v->column_datatype=="textarea")
                                        <textarea type="text" name="{{$field_name}}" class="form-control" placeholder="Enter specific value">{{$v->value}}</textarea>
                                        @elseif($v->column_datatype=="date")
                                        <input type="text" {$req} value="@if(isset($v->value))<x-localize :date='$v->value' type='date' /> @endif" id="{{$id}}" name="{{$field_name}}" autocomplete="off" class="form-control date-picker"  data-date-format="{{ Session::get('default_date_format')}}">
                                        @elseif($v->column_datatype=="time")
                                        <input type="text" {$req} autocomplete="off" value="{{$v->value??''}}" id="{{$id}}" name="{{$field_name}}" class="form-control timepicker timepicker-no-seconds">
                                        @elseif($v->function_id==15)
                                        <select name="{{$field_name}}" required class="form-control select2me" data-cy="invoice_detail_einvoice_type" data-placeholder="Select...">
                                            @foreach($einvoice_type as $ev)
                                            <option @if($v->value==$ev->config_value) selected @endif value="{{$ev->config_value}}">{{$ev->description}}</option>
                                            @endforeach
                                        </select>   
                                        @else
                                        <input type="text" id="{{$id}}" @if($v->column_name !='Billing cycle name' ) {{$req}} @endif value="{{$v->value??''}}" name="{{$field_name}}" class="form-control">
                                        @endif
                                    </div>
                                </div>
                                @if($v->function_id==4) <a onclick="showLedger();"  class="btn green ml10 popovers" style="padding: 6px 10px;margin-left: 0px;"  data-placement="top" data-container="body" data-trigger="hover" data-content="Pick a customer and view all invoices raised for the chosen customer"><i class="fa fa-list-ul"></i></a> @endif
                            </div>
                            @if($v->function_id==10 && $request_type==4)
                            <div class="form-group">
                                <label class="control-label col-md-4">Billing Period start date</label>
                                <div class="col-md-8">
                                    <input type="text" name="billing_start_date" required class="form-control  date-picker" autocomplete="off"  data-date-format="{{ Session::get('default_date_format')}}" placeholder="Select start date" @if(isset($subscription->billing_period_start_date)) value='<x-localize :date="$subscription->billing_period_start_date" type="date" />' @endif>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">Add duration</label>
                                <div class="col-md-4"> 
                                    <input type="number" name="billing_period" required class="form-control" @if(isset($subscription->billing_period_duration)) value="{{$subscription->billing_period_duration}}" @endif>
                                </div>
                                <div class="col-md-4">
                                    <select name="period_type" required class="form-control" data-placeholder="Select...">
                                        <option value="days" @if(isset($subscription->billing_period_type) && $subscription->billing_period_type=='days') selected @endif>Days</option>
                                        <option value="month" @if(isset($subscription->billing_period_type) && $subscription->billing_period_type=='month') selected @endif>Month</option>
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
                                        <input type="checkbox" name="carry_due" id="carry_due_forward" @if(isset($subscription->carry_due)) value="{{$subscription->carry_due}}" @endif class="make-switch" data-size="small" @if(isset($subscription->carry_due) && ($subscription->carry_due==1)) checked @endif onchange="carryDueForward(this.checked)">
                                    </div>
                                </div>
                            </div>
                            @endif

                            @else
                            <input type="hidden" name="existids[]" value="{{$v->id}}" class="form-control">
                            <input type="hidden" name="existvalues[]" value="">
                            <input type="hidden" name="function_id[]" value="{{$v->function_id}}">
                            @endif
                            @endif
                            @endforeach
                            @if(isset($plugin['is_prepaid']))
                            <div class="form-group">
                                <label class="control-label col-md-4">Advance Received</label>
                                <div class="col-md-8">
                                    <div class="input-icon right">
                                        <input type="text" value="{{$info->advance??0}}" {!!$validate->money!!} onblur="calculategrandtotal();" id="advance_amt" name="advance" class="form-control">
                                    </div>
                                </div>
                            </div>
                            @endif
                            @php
                            $alldocurls='';
                            $docfile=array();
                        @endphp
                            @if(isset($plugin['has_upload']))
                         
                            <div class="form-group">
                                <label class="control-label col-md-4">Attachments</label>
                                <div class="col-md-8">
                                    @if(isset($plugin['files']) && !empty($plugin['files'][0]))
                                        <span class="help-block">
                                            <div id="docviewbox">
                                            @foreach ($plugin['files'] as $key=>$item)
                                            <span class=" btn btn-xs green" style="margin-bottom: 5px;margin-left: 0px !important">
                                            <a class=" btn-xs " target="_BLANK" href="{{$item}}" title="Click to view full size">{{substr(substr(substr(basename($item), 0, strrpos(basename($item), '.')),0,-4),0,10)}}..</a>
                                          <a href="#delete_doc" onclick="setdata('{{substr(substr(substr(basename($item), 0, strrpos(basename($item), '.')),0,-4),0,10)}}','{{$item}}');"   data-toggle="modal">  <i class=" popovers fa fa-close" style="color: #A0ACAC;" data-placement="top" data-container="body" data-trigger="hover"  data-content="Remove doc"></i>   </a>
                                        </span>
                                        @php
                                         if($alldocurls!=''){
                                              $alldocurls= $alldocurls.','.$item;
                                            }
                                              else {
                                                $alldocurls= $alldocurls.$item;
                                              }
                                              
                                              array_push($docfile,$item);

                                         @endphp
                                            @endforeach
                                            </div>
                                            <a onclick="document.getElementById('newImageDiv').style.display = 'block';" class="UppyModalOpenerBtn btn btn-xs btn-link">Upload doc
                                            </a>
                                        </span>
                                        <span id="newImageDiv" style="display: none;">
                                            <input type="hidden" name="file_upload" id="file_upload" value="{{$alldocurls}}"> 
                                            <div id="drag-drop-area2"></div>
                                        </span>
                                    @else
                                        <div class="input-icon right">
                                           
                                            <input type="hidden" name="file_upload" id="file_upload" value=""> 
                                            <a  class="UppyModalOpenerBtn btn  default">Add attachments</a>
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
                                        @php if($mode=='create'){ $field_name='newvalues[]';}else{ $field_name='existvalues[]';} @endphp
                                        @if($mode=='create')
                                        <input type="hidden" name="ids[]" value="{{$v->column_id}}" class="form-control">
                                        @else
                                        <input type="hidden" name="existids[]" value="{{$v->id}}" class="form-control">
                                        @endif
                                        <input type="hidden" name="function_id[]" value="{{$v->function_id}}">
                                        @if($v->column_datatype=="textarea")
                                        <textarea type="text" name="{{$field_name}}" {!!$valid!!} class="form-control" placeholder="Enter specific value">{{$v->value}}</textarea>
                                        @elseif($v->column_datatype=="date")
                                        <input type="text" {$req} value="@if(isset($v->value))<x-localize :date='$v->value' type='date' /> @endif" name="{{$field_name}}" class="form-control date-picker" autocomplete="off"  data-date-format="{{ Session::get('default_date_format')}}">
                                        @elseif($v->column_datatype=="time")
                                        <input type="text" autocomplete="off" value="{{$v->value??''}}" name="{{$field_name}}" class="form-control timepicker timepicker-no-seconds">
                                        @else
                                        <input type="text" {!!$valid!!} value="{{$v->value??''}}" name="{{$field_name}}" class="form-control">
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
                                        @php if($mode=='create'){ $field_name='newvalues[]';}else{ $field_name='existvalues[]';} @endphp
                                        @if($mode=='create')
                                        <input type="hidden" name="ids[]" value="{{$v->column_id}}" class="form-control">
                                        @else
                                        <input type="hidden" name="existids[]" value="{{$v->id}}" class="form-control">
                                        @endif <input type="hidden" name="function_id[]" value="{{$v->function_id}}">
                                        @if($v->column_datatype=="textarea")
                                        <textarea type="text" name="{{$field_name}}" {!!$valid!!} class="form-control" placeholder="Enter specific value">{{$v->value}}</textarea>
                                        @elseif($v->column_datatype=="date")
                                        <input type="text" {$req} value="@if(isset($v->value))<x-localize :date='$v->value' type='date' /> @endif" name="{{$field_name}}" class="form-control date-picker" autocomplete="off"  data-date-format="{{ Session::get('default_date_format')}}">
                                        @elseif($v->column_datatype=="time")
                                        <input type="text" autocomplete="off" value="{{$v->value??''}}" name="{{$field_name}}" class="form-control timepicker timepicker-no-seconds">
                                        @else
                                        <input type="text" {!!$valid!!} value="{{$v->value??''}}" name="{{$field_name}}" class="form-control">
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

                    @if(isset($template_info->template_type) && $template_info->template_type=='travel')
                    @include('app.merchant.invoice.travel_particular')
                    @elseif(isset($template_info->template_type) && $template_info->template_type=='franchise')
                    @include('app.merchant.invoice.franchise_particular')
                    @elseif($template_info->template_type=='nonbrandfranchise')
                    @include('app.merchant.invoice.non_brand_franchise_particular')
                    @elseif($template_info->template_type=='construction')
                    @include('app.merchant.invoice.construction_particular')
                    @else
                    @include('app.merchant.invoice.particular')
                    @endif

                    @include('app.merchant.invoice.create_footer')
                    @include('app.merchant.invoice.footer')
                </div>

                <!-- BEGIN SEARCH CONTENT-->



             



                @endsection

                <!-- add particulars label ends -->
                @section('footer')
                <script>
                    mode = '{{$mode}}';
                    @if(!empty($invoice_particular))
                    exist_paricular_cnt = '{{count($invoice_particular)}}';
                    @else
                    exist_paricular_cnt = '0';
                    @endif
                    exist_particular_ids = '{{$exist_particular_id}}';
                    
                    selectCustomer({{$info->customer_id}});

                    @if(isset($product_list))
                    products = {!!$product_json!!};
                    @endif

                    @if(isset($tax_array))
                    tax_master = '{!!$tax_array!!}';
                    tax_array = JSON.parse(tax_master);
                    taxes_rate = '{!!$tax_rate_array!!}';
                    @endif

                    
                    @if(isset($template_info->particular_values))
                    particular_values = '{{$template_info->particular_values}}';
                    @endif

                    @if(isset($template_info->particular_column))
                    particular_col_array = JSON.parse('{!!$template_info->particular_column!!}');
                    @endif
                    
                    @if(isset($csi_code))
                    csi_codes = {!!$csi_code_json!!};
                    @endif

                    @if(isset($contract_detail))
                    document.getElementById('_project_id').value = '{!!$contract_detail->id!!}';
                    @endif

                    @if(!empty($plugin['supplier']))
                    @foreach($plugin['supplier'] as $v)
                    AddsupplierRow({{$v}});
                    @endforeach
                    @endif

                    setAdvanceDropdown();
                    @if($request_type==4)
                        repeatChange({{$subscription->mode}});
                    @endif
              
                    @if(isset($setting['has_datetime']))
                setdatetimepiker();
                @endif 
                invoice_construction=true;

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
        if (document.getElementById('prolist' + pind)) {} else {
            $('.select2-results').append('<div class="wrapper" id="prolist' + pind + '" > <a class="clicker" onclick="billIndex('+index+','+index+ ',0);">Add new bill code</a> </div>');
        }
    });

              </script>

<script src="https://releases.transloadit.com/uppy/v3.3.0/uppy.min.js"></script>
<script>
 var envlimit='{{env('INVOICE_ATTACHMENT_LIMIT')}}';
//var newdocfileslist=[];
var docfileslist=[];
@if(isset($docfile))
                    docfileslist =  @json($docfile);
                    @endif


//uppy file upload code
var uppy =new Uppy.Uppy({ 
    autoProceed: true,
    restrictions: {
        maxFileSize: 3000000,
        maxNumberOfFiles: envlimit,
        minNumberOfFiles: 1,
        allowedFileTypes: ['.jpg','.png','.jpeg','.pdf']
    },
    onBeforeFileAdded: (currentFile, files) => {
                var remainleng=0;
        if(document.getElementById("file_upload").value!='')
            remainleng=document.getElementById("file_upload").value.split(",").length;
     
        var counts=envlimit-remainleng;
        if(remainleng==envlimit)
        {
            uppy.info({
  message: 'upload limit exceeded',
  details: 'File couldn’t be uploaded because you can upload only '+envlimit+ 'files',
}, 'error', 5000)
           // document.getElementById("up-error").innerHTML = "*Maximum "+envlimit+" files allowed";
            return Promise.reject('too few files')
        }else if (Object.keys(files).length > counts-1) 
         {
           // document.getElementById("up-error").innerHTML = "*Maximum "+envlimit+" files allowed";
       return Promise.reject('too few files')
     }else{
        return true; 
     }
    }
});

uppy.use(Uppy.Dashboard, {
    target: 'body', 
    inline: false,
    trigger: '.UppyModalOpenerBtn',
   
    height: 160,
    maxHeight: 200,
   
    hideAfterFinish: true,
    showProgressDetails: false,
    hideUploadButton: false,
    hideRetryButton: false,
    hidePauseResumeButton: false,
    hideCancelButton: false,
    // doneButtonHandler: () => {
    //    clearnewuploads('yes');
        
    //     this.uppy.reset()
    //     this.requestCloseModal()
    // },
    // locale: {
    //     strings: {
    //         done: 'Cancel'
    // }}
});
uppy.use( Compressor, {
  quality: 0.6,
  limit: envlimit,
});
uppy.use(Uppy.XHRUpload, { 
    headers: {
        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
    },
    endpoint: '/merchant/uppyfileupload/uploadImage/invoice',
    method:'post',
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
    path = response.body.fileUploadPath;
    extvalue=document.getElementById("file_upload").value;
    docfileslist.push(path);
    deletedocfile('');
    if(extvalue!='')
    {
        document.getElementById("file_upload").value=extvalue+','+path;
    }else{
        document.getElementById("file_upload").value=path;
    }
    if(response.body.status == 300) {
        document.getElementById("error").innerHTML = response.body.errors;
        uppy.removeFile(file.id);
    } else {
        document.getElementById("error").innerHTML = '';
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
                <button type="button" onclick="deletedocfile('delete');" id="deleteanchor" class="btn delete">Delete</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<script>


   function setdata(name,fullurl)
    {
    
      document.getElementById('poptitle').innerHTML="Delete attachment - "+name;
      document.getElementById('docfullurl').value=fullurl;
    }
    function deletedocfile(x)
    {
        var html=''; 
if(x=='delete')
{
var fullurl=document.getElementById('docfullurl').value;
var index = docfileslist.indexOf(fullurl);
if (index !== -1) {
    docfileslist.splice(index, 1);
}
}

for(var i=0;i<docfileslist.length;i++)
{
   var filenm=docfileslist[i].substring(docfileslist[i].lastIndexOf('/')+1);
   filenm=filenm.split('.').slice(0, -1).join('.')
   filenm =filenm.substring(0, filenm.length - 4);
 html=html+'<span class=" btn btn-xs green" style="margin-bottom: 5px;margin-right: 5px;margin-left: 0px !important">'+
          '<a class=" btn btn-xs " target="_BLANK" href="'+docfileslist[i]+'" title="Click to view full size">'+filenm.substring(0,10)+'..</a>'+
           '<a href="#delete_doc" onclick="setdata(\''+filenm.substring(0,10)+'\',\''+docfileslist[i]+'\');"   data-toggle="modal"> '+
            ' <i class=" popovers fa fa-close" style="color: #A0ACAC;" data-placement="top" data-container="body" data-trigger="hover"  data-content="Remove doc"></i>   </a> </span>';
           
       
}
clearnewuploads();
            document.getElementById('docviewbox').innerHTML=html;
document.getElementById('closeconformdoc').click();
    }
    function clearnewuploads(){
        document.getElementById("file_upload").value = '';
        // if(x=='yes')
        // {
        // while (newdocfileslist.length > 0) {newdocfileslist.pop();}
        // }
        var filesnm='';
        for(var i=0;i<docfileslist.length;i++)
{
    if(filesnm!='')
        filesnm=filesnm+','+docfileslist[i];
        else
        filesnm=filesnm+docfileslist[i];
}

document.getElementById("file_upload").value =filesnm;
    }
    </script>
                @endsection

@include('app.merchant.contract.add-calculation-modal')
@include('app.merchant.contract.add-bill-code-modal')
@include('app.merchant.invoice.add-attachment-billcode-modal')