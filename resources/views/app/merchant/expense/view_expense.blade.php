@extends('app.master')

@section('content')
<div class="page-content">
    <div class="page-bar">  
        <span class="page-title" style="float: left;">{{$title}}</span>
        {{ Breadcrumbs::render('merchant.expense.view',$detail->type,$detail->expense_no) }}
    </div>
    <!-- BEGIN SEARCH CONTENT-->
    <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-10">
            <!-- BEGIN PORTLET-->

            <div class="invoice" style="max-width: 900px;border: 1px solid lightgrey !important;text-align: left;">
                <div class="row invoice-logo  no-margin" style="margin-bottom: 0px;">

                    <div class="col-md-8">
                        <p style="text-align: left;">
                            <a  style="font-size: 27px;">{{$detail->vendor_name}}</a> 
                            <br>
                            <span class="muted" style="width: 418px;line-height: 20px;font-size: 13px;">
                                {{$detail->address}}
                            </span><br>
                            <span class="muted" style="line-height: 20px;font-size: 13px;">
                                State: {{$detail->state}}<br>
                            </span>
                            <span class="muted" style="line-height: 20px;font-size: 13px;">
                                Contact: {{$detail->mobile}}<br>
                            </span>
                            <span class="muted" style="line-height: 20px;font-size: 13px;">
                                Email: {{$detail->email_id}}<br>
                            </span>
                        </p>
                    </div>
                </div>

                <div class="row no-margin">
                    <div class="col-md-6" style="padding-left: 0px;padding-right: 0px;">
                        <div class="" style="">
                            <table class="table table-bordered table-condensed" style="margin: 0px 0 0px 0 !important;">

                                <tbody>
                                    <tr>
                                        <td style="min-width: 120px;">
                                            <b>Expense no.</b>
                                        </td>
                                        <td style="min-width: 120px;">
                                            {{$detail->expense_no}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="min-width: 120px;">
                                            <b>Category</b>
                                        </td>
                                        <td style="min-width: 120px;">
                                            {{$detail->category}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="min-width: 120px;">
                                            <b>Department</b>
                                        </td>
                                        <td style="min-width: 120px;">
                                            {{$detail->department}}
                                        </td>
                                    </tr>
                                    <tr><td style="min-width: 120px;"><b>GST Number</b></td><td>{{$detail->gst_number}}</td></tr>
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
                                    <tr>
                                        <td style="min-width: 120px;">
                                            <b>Payment status</b>
                                        </td>
                                        <td style="min-width: 120px;">
                                            @if($detail->payment_status==1)
                                            Paid
                                            @elseif($detail->payment_status==2)
                                            Unpaid
                                            @elseif($detail->payment_status==3)
                                            Refunded
                                            @elseif($detail->payment_status==4)
                                            Cancelled
                                            @else
                                            Submitted
                                            @endif                                          
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
                                            {{$row->gst_percent}}
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
                                    <tr>
                                        <td colspan="4" class="col-md-8" style="border: 0;">
                                        </td>
                                        <td colspan="2" class="col-md-2">
                                            <b>TDS</b>
                                        </td>
                                        <td  class="col-md-2 td-c">
                                            <b> {{$detail->tds}}%</b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="col-md-8" style="border: 0;">
                                        </td>
                                        <td colspan="2" class="col-md-2">
                                            <b>Discount</b>
                                        </td>
                                        <td  class="col-md-2 td-c">
                                            <b> {{$detail->discount}}</b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="col-md-8" style="border: 0;">
                                        </td>
                                        <td colspan="2" class="col-md-2">
                                            <b>Adjustment</b>
                                        </td>
                                        <td  class="col-md-2 td-c">
                                            <b> {{$detail->adjustment}}</b>
                                        </td>
                                    </tr>
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
                        <a class="btn green hidden-print margin-bottom-5 pull-right" style="margin-right: 20px;" href="/merchant/expense/{{$btn_update}}/{{$link}}">
                            Update
                        </a>
                        @if($detail->file_path!='')
                        <a class="btn blue hidden-print margin-bottom-5 pull-right" target="_BLANK" style="margin-right: 20px;" href="{{$detail->file_path}}">
                            View attachment
                        </a>
                        @endif
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