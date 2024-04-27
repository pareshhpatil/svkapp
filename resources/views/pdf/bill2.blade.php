@extends('layouts.pdf')
@section('content')
<div class="panel " style="margin-left: -50px;margin-right: -50px;margin-top: -50px;margin-bottom: -50px;">
    <div class="panel-body" style="overflow: auto;">

        <div class="row" style="margin-right:0px;margin-left:0px;">
            <div class="col-md-12">

                <!-- BEGIN PAYMENT TRANSACTION TABLE -->


                <div class="portlet-body">
                    <div class="">
                        <table class="table" style="font-size: 12px !important;color: black !important;margin-bottom: 0;margin-top:10px;">
                            <tbody>
                                <tr >
                                    <td class="td-c" style="border-top: 0px;margin-top:20px;"><img style="max-width: 140px;" src="https://admin.siddhivinayaktravelshouse.in/dist/img/{{$admin->logo}}" alt="logo" class="logo-default"></td>
                                </tr>
                                <tr>
                                    <td class="td-c text-red" style="border-top: 0px;font-size: 22px;font-family: cambria;color: #ff0000;">{{$company_name}}</td>
                                </tr>
                                <tr>
                                    <td class="td-c" style="font-size: 15px;font-family: cambria;">
                                       @if($invoice->total_gst>0)TAX @endif INVOICE FOR THE MONTH OF {{ Carbon\Carbon::parse($invoice->date)->format('M-Y')}}
                                   </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row" style="margin-right:0px;margin-left:0px;">
                    <div class="col-md-12" >
                        <table class="table table-bordered" style="font-size: 12px; color: black !important;">
                            <tbody>
                                <tr>
                                    <td  style="width: 50%;padding: 10px;line-height: 20px;">
                                        <div class="col-md-12" >
                                            <p><b style="font-weight: bold;">Name :</b> {{$company_name}}</p>
                                        </div>
                                        <div class="col-md-12" >
                                            <p><b style="font-weight: bold;">GSTIN :</b> {{$admin->gst_number}}</p>
                                        </div>
                                        <div class="col-md-12" >
                                            <p><b style="font-weight: bold;">Pan No :</b> {{$admin->pan_number}}</p>
                                        </div>
                                        <div class="col-md-12" >
                                            <p><b style="font-weight: bold;">Address :</b> {{$admin->address}}</p>
                                        </div>
										@if($company->rcm==1)
										<div class="col-md-12" >
                                            <p><b style="font-weight: bold;">RCM Applicable :</b> Yes</p>
                                        </div>
										@endif
                                    </td>
                                    <td  style="width: 50%;padding: 10px;line-height: 20px;">
                                        <div class="col-md-12" >
                                            <p><b style="font-weight: bold;">Date :</b> {{ Carbon\Carbon::parse($invoice->bill_date)->format('d-M-Y')}}</p>
                                        </div>
                                        <div class="col-md-12" >
                                            <p><b style="font-weight: bold;">Invoice No :</b> {{$invoice->invoice_number}}</p>
                                        </div>
                                        <div class="col-md-12" >
                                            <p><b style="font-weight: bold;">Contact No :</b> {{$admin->mobile}}</p>
                                        </div>
                                        <div class="col-md-12" >
                                            <p><b style="font-weight: bold;">HSN/SAC Code :</b>  {{$admin->sac_code}}</p>
                                        </div>
										@if($invoice->work_order_no!='')
										<div class="col-md-12" >
                                            <p><b style="font-weight: bold;">Work Order No:</b>  {{$invoice->work_order_no}}</p>
                                        </div>
										@endif
										@if($company->company_id==3334)
											@php
												$po='7000047284';
											@endphp
										@endif 
										
										@if($po!='' && $po!='a')
										<div class="col-md-12" >
                                            <p><b style="font-weight: bold;">PO Number :</b>  {{$po}}</p>
                                        </div>
										<div class="col-md-12" >
                                            <p><b style="font-weight: bold;">Currency :</b>  Indian rupee</p>
                                        </div>
										@endif
                                    </td>
                                </tr>
								
								@if($company->company_id==1 || $company->company_id==5 || $company->company_id==8 || $company->company_id==57)
								<tr>
                                    <td colspan="2" style="padding: 10px;line-height: 15px;"><div class="col-md-12" ><b style="font-weight: bold;">GST Payable on RCM basis by the receiver</b></div></td>
                                </tr>
								@endif
								
								@if($invoice->company_id==29)
									<tr>
                                    <td colspan="2" style="padding: 10px;line-height: 15px;"><div class="col-md-12" ><b style="font-weight: bold;">Supply to SEZ Unit or SEZ developer for Authorised operation under letter of undertaking without payment of IGST</b></div></td>
                                </tr>
								@else
									<tr>
                                    <td colspan="2" style="padding: 10px;line-height: 15px;"><div class="col-md-12" ><b style="font-weight: bold;">Receivers Details:</b></div></td>
                                </tr>
								@endif
                                

                                <tr>
                                    <td colspan="2" style="padding: 10px;line-height: 20px;">
                                        <div class="col-md-12" >
                                            <p><b style="font-weight: bold;">Company Name :</b> {{$company->name}} </p>
                                        </div>
                                        <div class="col-md-12" >
                                            <p><b style="font-weight: bold;">GSTIN :</b> {{$company->gst_number}}</p>
                                        </div>

                                        <div class="col-md-12" >
                                            <p><b style="font-weight: bold;">Address :</b> {{$company->address}}</p>
                                        </div>
										
										@if($invoice->narrative!='')
										<div class="col-md-12" >
                                            <p>{!!$invoice->narrative!!}</p>
                                        </div>
										@endif
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="padding: 10px;line-height: 15px;"><div class="col-md-12" ><b style="font-weight: bold;">Vehicle No:</b> ({{$vehicle->car_type}}){{$vehicle->number}}</div></td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <table class="table table-bordered" style="font-size: 12px; color: black !important;">
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

                                                    <td class="tds" colspan="5" style="font-weight: bold;text-align: right;"><span class="pull-right"><b style="font-weight: bold;">Total Value @if($invoice->total_gst>0)/Taxable Value @endif(Rs.)</b></span></td>
                                                    <td class="tds" style="text-align: right;font-weight: bold;"><span class="pull-right"><b style="font-weight: bold;">{{$invoice->base_total}}</b></span></td>
                                                </tr>
                                                @if($invoice->total_gst>0)
                                                <tr>
                                                    <td class="tds" colspan="3" rowspan="3" style="vertical-align: middle;font-weight: bold;"><span class="pull-right"><b style="font-weight: bold;">Goods and Services Tax @ {{number_format($invoice->total_gst*100/$invoice->base_total,2)}}%</b></span></td>
                                                    <td class="tds" style="text-align: right;"><span class="pull-right">CGST@</span></td>
                                                    <td class="tds"><span class="pull-right">@if($invoice->cgst>0){{number_format($invoice->cgst*100/$invoice->base_total,2)}} @else 00 @endif%</span></td>
                                                    <td class="tds" style="text-align: right;"><span class="pull-right">{{$invoice->cgst}}</span></td>
                                                </tr>
                                                <tr>
                                                    <td class="tds" style="text-align: right;"><span class="pull-right">SGST@</span></td>
                                                    <td class="tds"><span class="pull-right" style="text-align: right;float: right;">@if($invoice->sgst>0){{number_format($invoice->sgst*100/$invoice->base_total,2)}} @else 00 @endif%</span></td>
                                                    <td class="tds" style="text-align: right;"><span class="pull-right">{{$invoice->sgst}}</span></td>
                                                </tr>
                                                <tr>
                                                    <td class="tds" style="text-align: right;"><span class="pull-right">IGST@</span></td>
                                                    <td class="tds"><span class="pull-right">@if($invoice->igst>0){{number_format($invoice->igst*100/$invoice->base_total,2)}} @else 00 @endif%</span></td>
                                                    <td class="tds" style="text-align: right;"><span class="pull-right">{{$invoice->igst}}</span></td>
                                                </tr>
                                                <tr>
                                                    <td class="tds" colspan="5" style="text-align: right;font-weight: bold;"><span class="pull-right"><b style="font-weight: bold;">Total GST Value</b></span></td>
                                                    <td class="tds" style="text-align: right;font-weight: bold;"><span class="pull-right"><b style="font-weight: bold;">{{$invoice->total_gst}}</b></span></td>
                                                </tr>
                                                <tr>
                                                    <td  class="tds" colspan="5" style="text-align: right;font-weight: bold;padding-right:10px;"><span class="pull-right"><b style="font-weight: bold;">Grand Total (Inclusive of GST)</b></span></td>
                                                    <td class="tds" style="text-align: right;font-weight: bold;"><span class="pull-right"><b style="font-weight: bold;"><strong>{{$invoice->grand_total}}</strong></b></span></td>
                                                </tr>
                                                @endif
                                                <tr>
                                                    <td class="tds" colspan="2" style="font-weight: bold;text-align: right;"><span class="pull-right"><b style="font-weight: bold;">Invoice Value (In Words)&nbsp;&nbsp;</b></span></td>
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
				@if($invoice->narrative!='')
				<div class="row" style="margin-right:0px;margin-left:0px;">
                    <div class="col-xs-8" ><b style="font-weight: bold;">Narrative:</b> {{$invoice->narrative}}</div>
                </div>
				<br>
				@endif
				
				@if($po!='')
				<div class="row">
				
                    <div class="col-xs-8" >
					@if($invoice->company_id==29)
                        <b style="font-weight: bold;">LUT Number</b> : AD270520006220W
						<br>
						<b style="font-weight: bold;">Date of filling UAT</b> : 20/05/2020
						<br>
						<hr>
					@endif
					Bank Account Details:<br>
Bank Name: HDFC BANK<br>
Account Name - SIDDHIVINAYAK TRAVELS HOUSE<br>
Branch - MUMBAI PAREL<br>
Account No - 50200043581889 <br>
IFSC CODE - HDFC0000357 <br><br>

<img style="width:300px;" src="{{ asset('dist/img/sign.png') }}">
					</div>
					
                </div>
				
				@else
				
                <div class="row" style="margin-right:0px;margin-left:0px;">
                    <div class="col-xs-8" >Date:</div>
                    <div class="col-xs-4" >
                        Name Of Signatory
						<br>
						<br>
						<br>
						<br>
                    </div>
                </div>
                
                
				@endif
                <!-- END PAYMENT TRANSACTION TABLE -->

            </div>
        </div>
    </div>
    <!-- /.panel-body -->
</div>

@endsection
