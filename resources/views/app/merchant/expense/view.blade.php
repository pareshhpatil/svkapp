@extends('app.master')

@section('content')
<div class="page-content">
    <h3 class="page-title">Expense detail&nbsp;
    </h3>
    <!-- BEGIN SEARCH CONTENT-->
    <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-10">
            <!-- BEGIN PORTLET-->

            <div class="invoice" style="max-width: 900px;border: 1px solid lightgrey !important;text-align: left;">
                <div class="row no-margin bg-grey" style="border-top: 1px solid black !important;border-bottom: 1px solid black !important;">
                    <div class="col-md-12" style="text-align: center;">
                        <h4><b>Expense</b></h4>
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
                                    <tr><td style="min-width: 120px;"><b>Vendor name</b></td><td>{{$detail->vendor_name}}</td></tr>
                                    <tr><td style="min-width: 120px;"><b>Email id</b></td><td>{{$detail->email_id}}</td></tr>
                                    <tr><td style="min-width: 120px;"><b>Mobile no</b></td><td>{{$detail->mobile}}</td></tr>
                                    <tr><td style="min-width: 120px;"><b>State</b></td><td>{{$detail->state}}</td></tr>
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
                        <a class="btn green hidden-print margin-bottom-5 pull-right" style="margin-right: 20px;" href="/merchant/expense/update/{{$link}}">
                            Update
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