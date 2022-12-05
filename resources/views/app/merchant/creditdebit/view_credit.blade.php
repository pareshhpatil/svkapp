@extends('app.master')

@section('content')
<div class="page-content">
    <div class="page-bar">  
        <span class="page-title" style="float: left;">{{$title}}</span>
        {{ Breadcrumbs::render('merchant.debitnote.view',$detail->type) }}
    </div>
    <!-- BEGIN SEARCH CONTENT-->
    <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-10">
            <!-- BEGIN PORTLET-->

            <div class="invoice" style="max-width: 900px;border: 1px solid lightgrey !important;text-align: left;">
                <div class="row invoice-logo  no-margin" style="margin-bottom: 0px;">
                    @if($merchant->logo!='')
                    <div class="col-md-3" style="min-width: 150px;line">
                       <!-- <img src="/uploads/images/landing/{{$merchant->logo}}" class="img-responsive templatelogo" alt=""/>-->
                    </div>
                    @endif
                    <div class="col-md-8">
                        <p style="text-align: left;">
                            <a style="font-size: 27px;">{{$merchant->company_name}}</a> 
                            <br>
                            <span class="muted" style="width: 418px;line-height: 20px;font-size: 13px;">
                               {{$merchant->address}}
                            </span><br>
                            <span class="muted" style="line-height: 20px;font-size: 13px;">
                                Contact: {{$merchant->business_contact}}<br>
                            </span>
                            <span class="muted" style="line-height: 20px;font-size: 13px;">
                                Email: {{$merchant->business_email}}<br>
                            </span>
                            @if($merchant->gst_number !='')
                            <span class="muted" style="line-height: 20px;font-size: 13px;">
                                GST Number: {{$merchant->gst_number}}<br>
                            </span>
                            @endif
                        </p>
                    </div>
                </div>
                <div class="row no-margin bg-grey" style="border-top: 1px solid black !important;border-bottom: 1px solid black !important;">
                    <div class="col-md-12" style="text-align: center;">
                        <h4><b>Credit note</b></h4>
                    </div>
                </div>

                <div class="row no-margin">
                    <div class="col-md-6" style="padding-left: 0px;padding-right: 0px;">
                        <div class="" style="">
                            <table class="table table-bordered table-condensed" style="margin: 0px 0 0px 0 !important;">

                                <tbody>
                                    <tr>
                                        <td style="min-width: 120px;">
                                            <b>Credit note no.</b>
                                        </td>
                                        <td style="min-width: 120px;">
                                            {{$detail->credit_debit_no}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="min-width: 120px;">
                                            <b>Credit note date</b>
                                        </td>
                                        <td style="min-width: 120px;">
                                            <x-localize :date="$detail->date" type="date" />
                                         
                                        </td>
                                    </tr>
                                    <tr><td style="min-width: 120px;"><b>{{$customer_default_column['customer_name']??'Customer name'}}</b></td><td>{{$detail->name}}</td></tr>
                                    <tr><td style="min-width: 120px;"><b>{{$customer_default_column['email']??'Email id'}}</b></td><td>{{$detail->email_id}}</td></tr>
                                    <tr><td style="min-width: 120px;"><b>{{$customer_default_column['mobile']??'Mobile no'}}</b></td><td>{{$detail->mobile}}</td></tr>
                                    <tr><td style="min-width: 120px;"><b>State</b></td><td>{{$detail->state}}</td></tr>
                                    @if($merchant->gst_number !='')
                                    <tr><td style="min-width: 120px;"><b>GST Number</b></td><td>{{$detail->gst_number}}</td></tr>
                                    @endif

                                </tbody>
                            </table>
                        </div>

                    </div>

                    <div class="col-md-6 invoice-payment" style="padding-left: 0px;padding-right: 0px;">
                        <div class="">
                            <table class="table table-bordered table-condensed" style="margin: 0px 0 0px 0 !important;">
                                <tbody>

                                    
                                    <tr>
                                        <td style="min-width: 120px;">
                                            <b>Invoice no.</b>
                                        </td>
                                        <td style="min-width: 120px;">
                                            {{$detail->invoice_no}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="min-width: 120px;">
                                            <b>Bill date</b>
                                        </td>
                                        <td style="min-width: 120px;">
                                            <x-localize :date="$detail->bill_date" type="date" />
                                                                                    
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="min-width: 120px;">
                                            <b>Due date</b>
                                        </td>
                                        <td style="min-width: 120px;">
                                            <x-localize :date="$detail->due_date" type="date" />
                                                                                   
                                        </td>
                                    </tr>


                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-scrollable">
                            <table class="table table-bordered table-condensed">
                                <thead>
                                    <tr>
                                        <th class="col-md-1 td-c" style="border-bottom: 1px solid #ddd;">
                                            SN.
                                        </th>
                                        <th class="col-md-5" style="border-bottom: 1px solid #ddd;">
                                            Particular
                                        </th>
                                        <th class="col-md-2 td-c" style="border-bottom: 1px solid #ddd;">
                                            SAC code
                                        </th>
                                        <th class="col-md-3 td-c" style="border-bottom: 1px solid #ddd;">
                                            Unit
                                        </th>
                                        <th class="col-md-3 td-c" style="border-bottom: 1px solid #ddd;">
                                            Rate
                                        </th>
                                        <th class="col-md-3 td-c" style="border-bottom: 1px solid #ddd;">
                                            GST Percentage
                                        </th>
                                        <th class="col-md-3 td-c" style="border-bottom: 1px solid #ddd;">
                                            Total Amount
                                        </th> 
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($particulars as $key=>$row)
                                    <tr> 
                                        <td class="col-md-1 td-c" style="border-top: 0;border-bottom: 0;">
                                            {{$key+1}}
                                        </td>
                                        <td class="col-md-5" style="border-top: 0;border-bottom: 0;">
                                            {{$row->particular_name}}
                                        </td>
                                        <td class="col-md-3 td-c" style="border-top: 0;border-bottom: 0;">
                                            {{$row->sac_code}}
                                        </td>
                                        <td class="col-md-3 td-c" style="border-top: 0;border-bottom: 0;">
                                            {{$row->qty}}
                                        </td>
                                        <td class="col-md-3 td-c" style="border-top: 0;border-bottom: 0;">
                                            {{$row->rate}}
                                        </td>
                                        <td class="col-md-3 td-c" style="border-top: 0;border-bottom: 0;">
                                            {{$row->gst_percent}}%
                                        </td>
                                        <td class="col-md-3 td-c" style="border-top: 0;border-bottom: 0;">
                                            {{$row->amount}}
                                        </td>
                                    </tr>
                                    @endforeach

                                    <tr>
                                        <td colspan="4" class="col-md-8" style="border-bottom: 0;">
                                        </td>
                                        <td colspan="2" class="col-md-2">
                                            <b>SUB TOTAL</b>
                                        </td>
                                        <td  class="col-md-2 td-c">
                                            <b>  {{$detail->base_amount}}</b>
                                        </td>
                                    </tr>
									@if($detail->cgst_amount>0)
									<tr>
                                        <td colspan="4" class="col-md-8" style="border-bottom: 0;border-top: 0;">
                                        </td>
                                        <td colspan="2" class="col-md-2">
                                            <b>CGST amount</b>
                                        </td>
                                        <td  class="col-md-2 td-c">
                                            <b>  {{$detail->cgst_amount}}</b>
                                        </td>
                                    </tr>
									<tr>
                                        <td colspan="4" class="col-md-8" style="border-bottom: 0;border-top: 0;">
                                        </td>
                                        <td colspan="2" class="col-md-2">
                                            <b>SGST amount</b>
                                        </td>
                                        <td  class="col-md-2 td-c">
                                            <b>  {{$detail->sgst_amount}}</b>
                                        </td>
                                    </tr>
									@endif
									
									@if($detail->igst_amount>0)
									<tr>
                                        <td colspan="4" class="col-md-8" style="border-bottom: 0;border-top: 0;">
                                        </td>
                                        <td colspan="2" class="col-md-2">
                                            <b>IGST amount</b>
                                        </td>
                                        <td  class="col-md-2 td-c">
                                            <b>  {{$detail->igst_amount}}</b>
                                        </td>
                                    </tr>
									@endif
                                    
                                    <tr>
                                        <td colspan="4" class="col-md-8" style="border: 0;">
                                        </td>
                                        <td colspan="2" class="col-md-2">
                                            <b>Total amount</b>
                                        </td>
                                        <td  class="col-md-2 td-c">
                                            <b> {{$detail->total_amount}}</b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="col-md-8" style="border-bottom: 0;">
                                            Narrative: {{$detail->narrative}}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>

                    </div>
                </div>
                <div class="row no-margin">
                    <div class="col-md-12">
                        <a class="btn green hidden-print margin-bottom-5 pull-right" style="margin-right: 20px;" href="/merchant/creditnote/update/{{$link}}">
                            Update
                        </a>
                        <a class="btn blue hidden-print margin-bottom-5 pull-right" style="margin-right: 20px;" href="/merchant/creditnote/download/{{$link}}">
                            Save as PDF
                        </a>
                    </div>
                </div>
            </div>

            <!-- END PORTLET-->
        </div>
    </div>

    <!-- END PAGE CONTENT-->
</div>
</div>
<!-- END CONTENT -->

@endsection