@extends('layouts.admin')
@section('content')
<div class="panel ">
    <div class="panel-body" style="overflow: auto;">

        <div class="row">
            <div class="col-md-12">

                <!-- BEGIN PAYMENT TRANSACTION TABLE -->


                <div class="portlet-body">
                    <div class="">
                        <table class="table" style="font-size: 12px !important;color: black !important;margin-bottom: 0;">
                            <tbody>
                                <tr >
                                    <td class="td-c" style="border-top: 0px;"><img style="max-width: 140px;" src="{{ asset('dist/img/'.$admin->logo) }}" alt="logo" class="logo-default"></td>
                                </tr>
                                <tr>
                                    <td class="td-c text-red" style="border-top: 0px;font-size: 30px;font-family: cambria;color: #ff0000;">{{$company_name}}</td>
                                </tr>
                                <tr>
                                    <td class="td-c" style="font-size: 20px;font-family: cambria;">
                                        @if($invoice->total_gst>0)TAX @endif INVOICE FOR THE MONTH OF {{ Carbon\Carbon::parse($invoice->date)->format('M-Y')}}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12" >
                        <table class="table table-bordered" style="font-size: 14px; color: black !important;">
                            <tbody>
                                <tr>
                                    <td  style="width: 50%;">
                                        <div class="col-md-12" >
                                            <p><b>Name:</b> {{$company_name}}</p>
                                        </div>
                                        <div class="col-md-12" >
                                            <p><b>GSTIN:</b> {{$admin->gst_number}}</p>
                                        </div>
                                        <div class="col-md-12" >
                                            <p><b>Pan No:</b> {{$admin->pan_number}}</p>
                                        </div>
                                        <div class="col-md-12" >
                                            <p><b>Address:</b> {{$admin->address}}</p>
                                        </div>
										@if($company->company_id==24)
										<div class="col-md-12" >
                                            <p><b>RCM Applicable :</b> Yes</p>
                                        </div>
										@endif
										
                                    </td>
                                    <td  style="width: 50%;">
                                        <div class="col-md-12" >
                                            <p><b>Date:</b> {{ Carbon\Carbon::parse($invoice->bill_date)->format('d-M-Y')}}</p>
                                        </div>
                                        <div class="col-md-12" >
                                            <p><b>Invoice No:</b> {{$invoice->invoice_number}}</p>
                                        </div>
                                        <div class="col-md-12" >
                                            <p><b>Contact No:</b> {{$admin->mobile}}</p>
                                        </div>
                                        <div class="col-md-12" >
                                            <p><b>HSN/SAC Code:</b>  {{$admin->sac_code}}</p>
                                        </div>
										@if($invoice->work_order_no!='')
										<div class="col-md-12" >
                                            <p><b>Work Order No:</b>  {{$invoice->work_order_no}}</p>
                                        </div>
										@endif
                                    </td>
                                </tr>
								@if($company->company_id==1 || $company->company_id==5 || $company->company_id==8)
								<tr>
                                    <td colspan="2"><div class="col-md-12" ><b>GST Payable on RCM basis by the receiver</b></div></td>
                                </tr>
								@endif
                                <tr>
                                    <td colspan="2"><div class="col-md-12" ><b>Receivers Details:</b></div></td>
                                </tr>

                                <tr>
                                    <td colspan="2">
                                        <div class="col-md-12" >
                                            <p><b>Company Name:</b> {{$company->name}} </p>
                                        </div>
                                        <div class="col-md-12" >
                                            <p><b>GSTIN:</b> {{$company->gst_number}}</p>
                                        </div>

                                        <div class="col-md-12" >
                                            <p><b>Address:</b> {{$company->address}}</p>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2"><div class="col-md-12" ><b>Vehicle No:</b> ({{$vehicle->car_type}}){{$vehicle->number}}</div></td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <table class="table table-bordered" style="font-size: 14px; color: black !important;">
                                            <tbody>
                                                <tr>
                                                    <th class="td-c tds" >Sr.No</th>
                                                    <th  class="td-c tds" style="min-width: 250px;">Particulars</th>
                                                    <th  class="td-c tds" >Unit</th>
                                                    <th class="td-c tds" >Qty.</th>
                                                    <th class="td-c tds" >Rate</th>
                                                    <th class="td-c tds" >Amount</th>
                                                </tr>
                                                @php($int=1)
                                                @foreach($logsheet_detail as $det)
                                                <tr>
                                                    <td class="td-c tds">{{$int}}</td>
                                                    <td class="td-c tds">{{$det['particular_name']}}</td>
                                                    <td class="td-c tds ">{{$det['unit']}}</td>
                                                    <td class="td-c  tds">{{$det['qty']}}</td>
                                                    <td class="td-c tds">@if($det['rate']!=''){{$det['rate']}}@else 0.00 @endif</td>
                                                    <td class="td-c tds ">
                                                        @if($det['is_deduct']==1)-@endif @if($det['amount']!=''){{$det['amount']}}@else 0.00 @endif</td>
                                                    @php($int++)
                                                </tr>
                                                @endforeach
                                                <tr>

                                                    <td class="tds" colspan="5"><span class="pull-right"><b>Total Value @if($invoice->total_gst>0)/Taxable Value @endif(Rs.)</b></span></td>
                                                    <td class="tds"><span class="pull-right"><b>{{$invoice->base_total}}</b></span></td>
                                                </tr>
                                                @if($invoice->total_gst>0)
                                                <tr>
                                                    <td class="tds" colspan="3" rowspan="3" style="vertical-align: middle;"><span class="pull-right"><b>Goods and Services Tax @5%</b></span></td>
                                                    <td class="tds"><span class="pull-right">CGST@</span></td>
                                                    <td class="tds"><span class="pull-right">@if($invoice->cgst>0)2.50 @else 00 @endif%</span></td>
                                                    <td class="tds"><span class="pull-right">{{$invoice->cgst}}</span></td>
                                                </tr>
                                                <tr>
                                                    <td class="tds"><span class="pull-right">SGST@</span></td>
                                                    <td class="tds"><span class="pull-right">@if($invoice->sgst>0)2.50 @else 00 @endif%</span></td>
                                                    <td class="tds"><span class="pull-right">{{$invoice->sgst}}</span></td>
                                                </tr>
                                                <tr>
                                                    <td class="tds"><span class="pull-right">IGST@</span></td>
                                                    <td class="tds"><span class="pull-right">@if($invoice->igst>0)5.00 @else 00 @endif%</span></td>
                                                    <td class="tds"><span class="pull-right">{{$invoice->igst}}</span></td>
                                                </tr>
                                                <tr>
                                                    <td class="tds" colspan="5"><span class="pull-right"><b>Total GST Value</b></span></td>
                                                    <td class="tds"><span class="pull-right"><b>{{$invoice->total_gst}}</b></span></td>
                                                </tr>
                                                <tr>
                                                    <td  class="tds" colspan="5"><span class="pull-right"><b>Grand Total (Inclusive of GST)</b></span></td>
                                                    <td class="tds"><span class="pull-right"><b>{{$invoice->grand_total}}</b></span></td>
                                                </tr>
                                                @endif
                                                <tr>
                                                    <td class="tds" colspan="2"><span class="pull-right"><b>Invoice Value (In Words)&nbsp;&nbsp;</b></span></td>
                                                    <td class="tds" colspan="4"> &nbsp;&nbsp;{{$word_money}}</td>
                                                </tr>
                                            </tbody>
                                        </table>

                                    </td>
                                </tr>
                            </tbody>

                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-8" >
					@if($invoice->narrative!='')
					Narrative: {{$invoice->narrative}}
					@endif
					</div>
                    <div class="col-xs-4" >
                        Name Of Signatory
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-8" ></div>
                    <div class="col-xs-4" >
                        <br>
                        <br>
                        <br>
                        Designation: 
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-8" ></div>
                    <div class="col-xs-4" >
                        Date:
                        <br>
                        <br>
                    </div>
                </div>
                <!-- END PAYMENT TRANSACTION TABLE -->

            </div>
        </div>
    </div>
    <!-- /.panel-body -->
</div>
@endsection
