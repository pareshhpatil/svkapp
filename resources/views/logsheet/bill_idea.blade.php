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
                                    <td class="td-c" style="border-top: 0px;"><img style="max-height: 80px;" src="{{ asset('dist/img/'.$admin->logo) }}" alt="logo" class="logo-default"></td>
                                </tr>
                                
                                <tr>
                                    <td class="td-c" style="font-size: 20px;font-family: cambria;">
                                        TAX INVOICE FOR THE MONTH OF {{ Carbon\Carbon::parse($invoice->date)->format('M-Y')}}
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
                                    <td  style="width: 60%;">
                                        <div class="col-md-12" >
                                           <b>To  {{$company->name}}</b> 
                                        </div>
                                        <div class="col-md-12" >
                                             {{$company->address}}
                                        </div>
                                        <div class="col-md-12" >
                                           <b> State Maharashtra Code- 27</b>
                                        </div>
                                        <div class="col-md-12" >
                                            <b> G.S.T. NO- {{$company->gst_number}}</b>
                                        </div>
										<div class="col-md-12" >
                                            <b> RCM - YES</b> 
                                        </div>
										<div class="col-md-12" >
                                            <b> SUPPLY ATTRACTS REVERSE CHARGE</b> 
                                        </div>
                                    </td>
                                    <td  style="width: 40%;">
                                        <div class="col-md-12" >
                                            <p><b>Date:</b> {{ Carbon\Carbon::parse($invoice->bill_date)->format('d-M-Y')}}</p>
                                        </div>
                                        <div class="col-md-12" >
                                            <p><b>Invoice No:</b> {{$invoice->invoice_number}}</p>
                                        </div>
                                        <div class="col-md-12" >
                                            <p><b>VEHICLE TYPE:</b> {{$vehicle->car_type}}</p>
                                        </div>
                                        <div class="col-md-12" >
                                            <p><b>BOOKING REF. NO:</b>  Monthly Pkg.</p>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <table class="table table-bordered" style="font-size: 14px; color: black !important;">
                                            <tbody>
                                                <tr>
                                                    <th  class="td-c tds" style="min-width: 250px;">DESCRIPTION</th>
                                                    <th  class="td-c tds" >KM CAPPING</th>
                                                    <th class="td-c tds" > KM / HRS USED</th>
                                                    <th class="td-c tds" >RATE</th>
                                                    <th class="td-c tds" >AMOUNT</th>
                                                </tr>
                                                @php($int=1)
                                                @foreach($logsheet_detail as $det)
                                                @if ($int!=8)
                                                <tr>
                                                    <td class="td-c tds">{{$det['particular_name']}}</td>
                                                    <td class="td-c tds ">{{$det['unit']}}</td>
                                                    <td class="td-c  tds">{{$det['qty']}}</td>
                                                    <td class="td-c tds">@if($det['rate']!=''){{$det['rate']}}@else 0.00 @endif</td>
                                                    <td class="td-c tds ">
                                                        @if($det['is_deduct']==1)-@endif @if($det['amount']!=''){{$det['amount']}}@else 0.00 @endif</td>

                                                </tr>
                                                @endif
                                                @php($int++)
                                                @endforeach
                                                <tr>

                                                    <td class="tds" colspan="4"><span class="pull-right"><b>Total Value /Taxable Value(Rs.)</b></span></td>
                                                    <td class="tds"><span class="pull-right"><b>{{$invoice->base_total}}</b></span></td>
                                                </tr>
                                                <tr>
                                                    <td class="tds"><span class="pull-right td-c"> GST HS CODE</span></td>
                                                    <td class="tds"><span class="pull-right">98193000</span></td>
                                                    <td class="tds"><span class="pull-right"></span></td>
                                                    <td class="tds"><span class="pull-right">0%</span></td>
                                                    <td class="tds"><span class="pull-right"></span></td>
                                                </tr>
                                                <tr>
                                                    <td class="tds"><span class="pull-right"> Central category GST</span></td>
                                                    <td class="tds"><span class="pull-right"></span></td>
                                                    <td class="tds"><span class="pull-right"></span></td>
                                                    <td class="tds"><span class="pull-right">@if($invoice->cgst>0)2.50 @else 00 @endif%</span></td>
                                                    <td class="tds"><span class="pull-right">{{$invoice->cgst}}</span></td>
                                                </tr>
                                                <tr>
                                                    <td class="tds"><span class="pull-right"> State category GST</span></td>
                                                    <td class="tds"><span class="pull-right"></span></td>
                                                    <td class="tds"><span class="pull-right"></span></td>
                                                    <td class="tds"><span class="pull-right">@if($invoice->sgst>0)2.50 @else 00 @endif%</span></td>
                                                    <td class="tds"><span class="pull-right">{{$invoice->sgst}}</span></td>
                                                </tr>

                                                <tr>
                                                    <td class="tds"><span class="pull-right">  Toll & Parking (Supporting Attached)</span></td>
                                                    <td class="tds"><span class="pull-right"></span></td>
                                                    <td class="tds"><span class="pull-right"></span></td>
                                                    <td class="tds"><span class="pull-right">At Actual</span></td>
                                                    <td class="tds"><span class="pull-right">{{$invoice->toll}}.00</span></td>
                                                </tr>
                                                <tr>
                                                    <td  class="tds" colspan="4"><span class="pull-right"><b>NET TOTAL</b></span></td>
                                                    <td class="tds"><span class="pull-right"><b>{{$invoice->grand_total}}</b></span></td>
                                                </tr>
                                                <tr>
                                                    <td rowspan="5">
                                                        <table class="table table-bordered" style="font-size: 14px; color: black !important;">
                                                            <tbody>
                                                                <tr>
                                                                    <th  class="td-c tds">4+1</th>
                                                                    <th  class="td-c tds" >12hrs</th>
                                                                    <th class="td-c tds" >24hrs</th>
                                                                </tr>
                                                                <tr>
                                                                    <td  class="td-c tds">< 1500</td>
                                                                    <td  class="td-c tds" >31,000.00</td>
                                                                    <td class="td-c tds" >47,500.00</td>
                                                                </tr>
                                                                <tr>
                                                                    <td  class="td-c tds">1501-2500</td>
                                                                    <td  class="td-c tds" > 34,000.00 </td>
                                                                    <td class="td-c tds" > 49,500.00 </td>
                                                                </tr>
                                                                <tr>
                                                                    <td  class="td-c tds">2501-3000</td>
                                                                    <td  class="td-c tds" >  36,000.00  </td>
                                                                    <td class="td-c tds" >-</td>
                                                                </tr>
                                                                <tr>
                                                                    <td  class="td-c tds">2501-3500</td>
                                                                    <td  class="td-c tds" >-</td>
                                                                    <td class="td-c tds" > 51,000.00 </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>

                                                    </td>
                                                    <td class="tds" colspan="1"><span class="pull-right"><b>Amount (In Words)&nbsp;&nbsp;</b></span></td>
                                                    <td class="tds" colspan="3"> &nbsp;&nbsp;{{$word_money}}</td>
                                                </tr>
                                                <tr>
                                                    <td class="tds" colspan="1"><span class="pull-right"><b> PAN NUMBER</b></span></td>
                                                    <td class="tds" colspan="3"> AWJPK0269J</td>
                                                </tr>
                                                <tr>
                                                    <td class="tds" colspan="1"><span class="pull-right"><b> C.E.S. TAX  ASSESS CODE</b></span></td>
                                                    <td class="tds" colspan="3"> AWJPK0269JSD001    </td>
                                                </tr>
                                                <tr>
                                                    <td class="tds" colspan="1"><span class="pull-right"><b> GSTN NUMBER</b></span></td>
                                                    <td class="tds" colspan="3"> 27AWJPK0269J1Z9</td>
                                                </tr>
                                                <tr>
                                                    <td class="tds" colspan="1"><span class="pull-right"><b> UAN NUMBER (MSME,Govt.of India)</b></span></td>
                                                    <td class="tds" colspan="3"> MH19D0003563</td>
                                                </tr>
                                                <tr>
                                                    <td class="tds"><span class=""><b> Terms & Condiions:</b></span></td>
                                                    <td class="tds" colspan="4"> <b class=" pull-right">FOR- Sahyadri Tours & Travels	</b></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="5" class="tds small"><span> *Subject to Mumbai Jurisdiction
                                                            <br>
                                                            *Interest @24% p.a. on outstanding payments after 30days
                                                            <br>
                                                            *No objection pertaining to this invoice would be entertained after 7days from the date here of
                                                            <br>
                                                            *All cheques to be drawn in favour of "Sahyadri Tours & Travels"

                                                            <b class=" pull-right">Authorised Signatory		</b>
                                                        </span></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="5" class="tds"><span class="small"><b> Subhash Nagar-01, Rebello compound, Mahakali Caves Road, Andheri East, Mumbai- 400 093 - sahyadritravels15@yahoo.in  </b></span></td>
                                                </tr>
                                            </tbody>
                                        </table>

                                    </td>
                                </tr>
                            </tbody>

                        </table>
                    </div>
                </div>


                <!-- END PAYMENT TRANSACTION TABLE -->

            </div>
        </div>
    </div>
    <!-- /.panel-body -->
</div>
@endsection
