@extends('app.master')

@section('content')
<div class="page-content">
    <h3 class="page-title">&nbsp;
    </h3>
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
                    <div class="col-md-12" style="padding-left: 0px;padding-right: 0px;">
                        <table class="table table-bordered table-condensed">
                            <tbody>
                                <tr>
                                    <td colspan="2">
                                        Credit Note No
                                    </td>
                                    <td colspan="4">
                                        {{$detail->credit_debit_no}}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        Credit Note Date
                                    </td>
                                    <td colspan="4">
                                        <x-localize :date="$detail->date" type="date" />
                                      
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="6" style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb">
                                        Franchisee Name & Address
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="6" style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb">
                                        {{$detail->name}} {{$detail->address}}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" width="300" style="width:50%;border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb">
                                        Against Bill No : {{$detail->invoice_no}}
                                    </td>
                                    <td colspan="4">
                                        Bill Date :  <x-localize :date="$detail->bill_date" type="date" />{{ Helpers::htmlDate($detail->bill_date) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        Bill Period
                                    </td>

                                    <td colspan="4">
                                        {{$summary->bill_period}}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        Store UID
                                    </td>
                                    <td colspan="4">
                                        {{$detail->customer_code}}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        Franchisee GST No
                                    </td>
                                    <td colspan="4">
                                        {{$detail->gst_number}}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        State
                                    </td>
                                    <td colspan="4">
                                        {{$detail->state}}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="6" style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb">
                                        Daily Gross Sales
                                    </td>
                                </tr>
                                <tr>
                                    <th colspan="2">
                                        Date
                                    </th>
                                    <th colspan="2">
                                        Sales As per system
                                    </th>
                                    <th colspan="2">
                                        Sales As per Franchisee
                                    </th>
                                </tr>
                                <tr>
                                    <th colspan="2">

                                    </th>
                                    <th>
                                        Chetty's
                                    </th>
                                    <th>
                                        Non Chetty's
                                    </th>
                                    <th>
                                        Chetty's
                                    </th>
                                    <th>
                                        Non Chetty's
                                    </th>
                                </tr>
                                @foreach($sales as $key=>$row)
                                <tr>

                                    <td colspan="2">
                                        <x-localize :date="$row->date" type="date" />
                                      
                                    </td>

                                    <td>
                                        {{$row->inv_gross_sale}}
                                    </td>
                                    <td>
                                        {{$row->non_brand_inv_gross_sale}}
                                    </td>
                                    <td>
                                        {{$row->credit_gross_sale}}
                                    </td>
                                    <td>
                                        {{$row->non_brand_credit_gross_sale}}
                                    </td>

                                </tr>
                                @endforeach
                                <tr>

                                    <td colspan="2">
                                        Total
                                    </td>

                                    <td>
                                        {{$summary->gross_bilable_sale}}
                                    </td>
                                    <td>
                                        {{$summary->non_brand_gross_bilable_sale}}
                                    </td>
                                    <td>
                                        {{$summary->new_gross_bilable_sale}}
                                    </td>
                                    <td>
                                        {{$summary->non_brand_new_gross_bilable_sale}}
                                    </td>

                                </tr>
                                <tr>
                                    <td colspan="2">
                                        Difference
                                    </td>

                                    <td colspan="1">
                                        @php $diff=$summary->gross_bilable_sale-$summary->new_gross_bilable_sale; @endphp
                                        {{ Helpers::moneyFormatIndia($diff,2)}}
                                    </td>
                                    <td colspan="1">
                                        @php $diff=$summary->non_brand_gross_bilable_sale-$summary->non_brand_new_gross_bilable_sale; @endphp
                                        {{ Helpers::moneyFormatIndia($diff,2)}}
                                    </td>
                                    <td colspan="1">
                                       
                                    </td>
                                    <td colspan="1">
                                       
                                    </td>
                                </tr>

                                <tr>

                                    <td colspan="2">
                                        Less: GST @5.00%
                                    </td>

                                    <td>
                                        {{$summary->gross_bilable_sale-$summary->net_bilable_sale}}
                                    </td>
                                    <td>
                                        {{$summary->gross_bilable_sale-$summary->non_brand_net_bilable_sale}}
                                    </td>
                                    <td>
                                        {{$summary->new_gross_bilable_sale-$summary->new_net_bilable_sale}}
                                    </td>
                                    <td>
                                        {{$summary->new_gross_bilable_sale-$summary->non_brand_new_net_bilable_sale}}
                                    </td>

                                </tr>
                                <tr>

                                    <td colspan="2">
                                        Net Excess Sales Billed
                                    </td>

                                    <td>
                                        {{$summary->net_bilable_sale}}
                                    </td>
                                    <td>
                                        {{$summary->non_brand_net_bilable_sale}}
                                    </td>
                                    <td>
                                        {{$summary->new_net_bilable_sale}}
                                    </td>
                                    <td>
                                        {{$summary->non_brand_new_net_bilable_sale}}
                                    </td>

                                </tr>
                                <tr>

                                    <td colspan="2">
                                        Gross Franchisee Fee 
                                    </td>

                                    <td>
                                        {{$summary->gross_comm_amt}} 
                                    </td>
                                    <td>
                                        {{$summary->non_brand_gross_comm_amt}} 
                                    </td>
                                    <td>
                                        {{$summary->new_gross_comm_amt}} 
                                    </td>
                                    <td>
                                        {{$summary->non_brand_new_gross_comm_amt}} 
                                    </td>

                                </tr>
                                <tr>

                                    <td colspan="2">
                                        Waived Off 
                                    </td>

                                    <td>
                                        {{$summary->waiver_comm_amt}} 
                                    </td>
                                    <td>
                                        {{$summary->non_brand_waiver_comm_amt}} 
                                    </td>
                                    <td>
                                        {{$summary->new_waiver_comm_amt}} 
                                    </td>
                                    <td>
                                        {{$summary->non_brand_new_waiver_comm_amt}} 
                                    </td>

                                </tr>
                                <tr>

                                    <td colspan="2">
                                        Net Franchise Fee 
                                    </td>

                                    <td colspan="1">
                                        {{$summary->net_comm_amt}} 
                                    </td>
                                    <td colspan="1">
                                        {{$summary->non_brand_net_comm_amt}} 
                                    </td>
                                    <td colspan="1">
                                        {{$summary->new_net_comm_amt}} 
                                    </td>
                                    <td colspan="1">
                                        {{$summary->non_brand_new_net_comm_amt}} 
                                    </td>

                                </tr>
                                <tr>

                                    <td colspan="2">
                                        SGST 9%
                                    </td>

                                    <td colspan="2">
                                        @php $cgst=round($summary->net_comm_amt*0.09,2); @endphp
                                        {{$cgst}}
                                    </td>
                                    <td colspan="2">
                                        @php $cgstnew=round($summary->new_net_comm_amt*0.09,2); @endphp
                                        {{$cgstnew}}
                                    </td>

                                </tr>
                                <tr>

                                    <td colspan="2">
                                        CGST 9%
                                    </td>

                                    <td colspan="2">
                                        {{$cgst}}
                                    </td>
                                    <td colspan="2">
                                        {{$cgstnew}}
                                    </td>

                                </tr>
                                <tr>

                                    <td colspan="2">
                                        Total Franchisee Fee
                                    </td>

                                    <td colspan="2">
                                        @php $total=round($summary->net_comm_amt + $cgst + $cgst); @endphp
                                        {{$total}}
                                    </td>
                                    <td colspan="2">
                                        @php $total=round($summary->new_net_comm_amt+$cgstnew+$cgstnew); @endphp
                                        {{$total}}
                                    </td>

                                </tr>
                                <tr>
                                    <td colspan="2">
                                        Credit / Debit Note Amount/ Royalty
                                    </td>
                                    <td colspan="4">
                                        {{$detail->total_amount}}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        Income Tax PAN
                                    </td>
                                    <td colspan="4">
                                        {{$merchant->pan}}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        Category of Service
                                    </td>
                                    <td colspan="4">
                                        Franchise Service
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        Maharashtra GST
                                    </td>
                                    <td colspan="4">
                                        {{$merchant->gst_number}}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        CIN
                                    </td>
                                    <td colspan="4">
                                        {{$merchant->cin_no}}
                                    </td>
                                </tr>


                            </tbody>

                        </table>

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