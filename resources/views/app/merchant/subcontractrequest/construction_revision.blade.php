@extends('app.master')



@section('content')
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    
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
                                        <label class="control-label"> {{$contract_detail->project_name}}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">Contract code</label>
                                <div class="col-md-8">
                                    <div class="input-icon right">
                                        <label class="control-label"> {{$contract_detail->contract_code}}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">Client code</label>
                                <div class="col-md-8">
                                    <div class="input-icon right">
                                        <label class="control-label"> {{$customer->customer_code}}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">Client name</label>
                                <div class="col-md-8">
                                    <div class="input-icon right">
                                        <label class="control-label"> {{$customer->first_name}} {{$customer->last_name}}</label>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-md-6" data-tour="invoice-create-billing-information">


                            @php $is_carry=0; @endphp
                            @if(!empty($metadata['H']))
                            @foreach($metadata['H'] as $v)
                            @php $req=""; @endphp
                            @if($v->position=='R' && ($request_type!=4 or ($v->column_position!=5 && $v->column_position!=6)))
                            @if($request_type!=2 || $v->function_id!=9)

                            @php if(!isset($v->value)) {$v->value="";} @endphp
                            <div class="form-group">
                                @if($v->column_name == 'Billing cycle name')
                                <label class="control-label col-md-4">{{$v->column_name}}@if($v->column_datatype=="percent") (%)@endif
                                </label>
                                @else
                                <label class="control-label col-md-4">{{$v->column_name}}</label>
                                @endif
                                <div @if($v->function_id==4) class="col-md-7" @else class="col-md-8" @endif>
                                    <div class="input-icon right">
                                        <label class="control-label">
                                            {{$v->value??''}}
                                        </label>
                                    </div>
                                </div>
                            </div>



                            @else
                            @endif
                            @endif
                            @endforeach


                            @endif
                        </div>
                    </div>






                    @if(isset($template_info->template_type) && $template_info->template_type!='scan')
                    <!-- add particulars label -->
                    <style>
                        .lable-heading {
                            font-style: normal;
                            font-weight: 400;
                            font-size: 14px;
                            line-height: 24px;
                            color: #767676;
                            margin-bottom: 0px;
                            margin-top: 5px;
                        }

                        .col-id-no {
                            position: sticky !important;
                            left: 0;
                            border-right: 2px solid #D9DEDE !important;
                            background-color: #fff;
                        }

                        .row-label {
                            font-size: 14px;
                        }

                        .fa-exclamation-circle {
                            color: #394242;
                        }
                       
                    </style>
                    <h3 class="form-section">Particulars
                    </h3>

                    <div class="table-scrollable">
                        <table class="table table-bordered table-hover" id="particular_table">
                            @php $particular_column= json_decode($template_info->particular_column,1); @endphp
                            @if(!empty($particular_column))
                            <thead>
                                <tr>
                                    @foreach($particular_column as $k=>$v)
                                    @if($k!='description')
                                    <th class="td-c @if($k=='bill_code') col-id-no @endif" @if($k=='description' || $k=='bill_code' ) style="min-width: 200px;" @endif>
                                        {{$v}}
                                    </th>
                                    @endif
                                    @endforeach

                                </tr>
                            </thead>


                            <tbody id="new_particular">
                                @php $discount_perc=0; @endphp
                                @php $rate=0; @endphp
                                @if(!empty($invoice_particular))
                                @php $int=1; @endphp
                                @foreach($invoice_particular as $dp)
                                <tr>
                                    @foreach($particular_column as $k=>$v)
                                    @php $readonly=''; @endphp
                                    @if($k!='description')
                                    @isset($dp->{$k})
                                    @php $value=$dp->{$k}; @endphp
                                    @else
                                    @php $value=''; @endphp
                                    @endisset
                                    @php $calculated=0; @endphp
                                    <td @if($k=='bill_code' ) class="col-id-no" @endif style="text-align:center;">
                                        @if($k == 'current_contract_amount' || $k == 'previously_billed_percent' || $k == 'retainage_amount_previously_withheld' || $k == 'previously_billed_amount' || $k == 'current_billed_amount' || $k == 'total_billed' || $k == 'retainage_amount_for_this_draw' || $k == 'total_outstanding_retainage')
                                        @php $readonly='readonly'; @endphp
                                        @endif


                                        @if($k == 'stored_materials' || $k == 'current_contract_amount' || $k == 'original_contract_amount' || $k == 'approved_change_order_amount' || $k == 'previously_billed_percent' || $k == 'current_billed_amount' || $k == 'total_billed' || $k == 'retainage_amount_for_this_draw' || $k == 'total_outstanding_retainage' || $k == 'previously_billed_amount' || $k == 'current_billed_percent' || $k == 'retainage_percent' || $k == 'retainage_amount_previously_withheld' || $k == 'retainage_release_amount')
                                        @php $value= ($value=='')?0:$value; @endphp

                                        @if($dp->bill_type=='Calculated')
                                        @php $readonly='readonly'; @endphp
                                        @php $calculated=1; @endphp
                                        <label class=" row-label">{{number_format($value,2)}}</label>

                                        @else
                                        {{number_format($value,2)}}
                                        @endif


                                        @else
                                        @if($k=='bill_code')
                                        @if($dp->bill_type=='Calculated')
                                        <label class="row-label">{{$dp->code}}</label><br>
                                        @else
                                        {{$dp->code}}
                                        <div class="text-center">
                                            <p id="description{{$int}}" class="lable-heading">
                                                {{$dp->description}}
                                            </p>
                                        </div>
                                        @endif
                                        @elseif($k=='bill_type')


                                        {{$dp->bill_type}}

                                        @else

                                        @if($dp->bill_type=='Calculated')
                                        <label class="row-label" id="{{$k}}_lb{{$int}}">{{$dp->$k}}</label><br>
                                        @else
                                        {{$value}}

                                        @endif
                                        @endif
                                        @endif
                                    </td>
                                    @endif

                                    @endforeach

                                </tr>
                                @php $int++; @endphp
                                @endforeach
                                @endif
                            </tbody>

                            @endif
                        </table>
                    </div>
                    @endif




                    <div class="portlet light bordered">
                        <div class="portlet-body form">
                            <h3 class="form-section">Final summary</h3>
                            <div class="row">

                                <div class="col-md-2 ml-1">
                                    <div class="form-group">
                                        <p>Grand total <label class="control-label"> {{number_format($info->grand_total,2)??0}}</label></p>

                                    </div>
                                </div>


                                <div class="col-md-8 pull-right">
                                    <div class="pull-right">
                                        <a href="/merchant/paymentrequest/viewlist" class="btn default">Cancel</a>

                                    </div>
                                </div>


                                <!--/span-->

                                <!-- grand total label ends -->
                            </div>

                        </div>
                    </div>


                </div>
        </div>
    </div>
</div>

<!-- BEGIN SEARCH CONTENT-->







@endsection