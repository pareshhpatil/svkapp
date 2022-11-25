<html lang="en">

    <body>


        <style>
            html { 
                margin: 0px;
                margin-top: 10px;
                margin-bottom: 10px;
            }
            body
            {
                font-family: Open Sans, sans-serif;
            }
            .form-control-plaintext{
                color: #5b4d4b;
                font-size: 13px;
                line-height: 14px;
                height: auto;
                padding: 0;
            }
            .tx-c{
                text-align: center;
            }
            .tx-r{
                text-align: right;
            }
            .bgset{
                background-color: {{$bg_color}};
                color: {{$text_color}};
            }
            .bgset2{
                background-color: {{$bg_color2}};
                color: {{$text_color2}};
            }
        </style>

        <section class="jumbotron jumbotron-features bg-transparent py-4" id="header">
            <div class="container">
                <div class="row align-items-center">


                    <div class="d-none d-lg-block col-12 col-md-12 col-lg-12 col-xl-12">
                        <table style="margin:0 auto; font-family: Open Sans, sans-serif;width: 750px; border: 1px solid grey;" align="center"  border="0" cellspacing="0" cellpadding="10">
                            <tbody><tr>
                                    <td style="font-size:15px; line-height:30px;"><table width="100%" border="0" cellspacing="0" cellpadding="5">
                                            <tbody><tr>
                                                    <td width="150"  align="center" valign="top">
                                                        @if($logo!='') <img style="max-height: 200px;max-width: 140px;" src="data:image/png;base64,{{$logo}}"> @endif
                                                    </td>
                                                    <td width="280" style="">
                                                        <span style="font-size:25px; color:#6e605d;">{{$company_name}}</span>
                                                        <span style="font-size:13px; display:block; margin-top:5px; margin-bottom:1px;line-height: 16px;">{{$merchant_address}} </span>
                                                        @if($merchant_mobile!='')<span style="font-size:13px; display:block; margin-top:1px; margin-bottom:1px;line-height: 16px;"> <div class="input-group">Contact: {{$merchant_mobile}}</div></span>@endif
                                                        @if($merchant_email!='')<span style="font-size:13px; display:block; margin-top:1px; margin-bottom:1px;line-height: 16px;"> <div class="input-group">E-mail: {{$merchant_email}}</div></span>@endif
                                                    </td>
                                                    <td width="90" align="center" valign="top">
                                                    </td>
                                                </tr>
                                            </tbody></table>  </td>
                                </tr>
                                <tr style="border-spacing: 0px;">
                                    <td class="bgset" style="text-align: center;border-top: 1px solid grey;border-bottom: 1px solid grey;font-size:18px;">
                                        <b>TAX INVOICE</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-size:11px;  border-bottom:1px #cbcbcb;">
                                        <table border="0" cellspacing="0" cellpadding="5" style="font-size: 13px;line-height: 15px;width: 100%;">
                                            <tbody>
                                                <tr>
                                                    <td style="font-size:11px;  border-bottom:1px #cbcbcb;border-right: 1px solid #cbcbcb;width: 50%; ">
                                                        <table  border="0" cellspacing="0" cellpadding="5" style="color:#5b4d4b;font-size: 13px;line-height: 14px;">

                                                            <tbody>

                                                                @if($customer_code!='')<tr><td ><b>Customer code</b></td><td >{{$customer_code}}</td></tr>@endif
                                                                @if($name!='')<tr><td ><b>Customer name</b></td><td >{{$name}}</td></tr>@endif
                                                                @if($email!='') <tr><td ><b>Email ID</b></td><td >{{$email}}</td></tr>@endif
                                                                @if($mobile!='')<tr><td ><b>Mobile no</b></td><td >{{$mobile}}</td></tr>@endif
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                    <td style="font-size:11px;  border-bottom:1px #cbcbcb;width: 50%; ">
                                                        <table  border="0" cellspacing="0" cellpadding="5" style="color:#5b4d4b;font-size: 13px;line-height: 14px;">

                                                            <tbody>
                                                                @if($bill_date!='')<tr><td ><b>Bill Date</b></td><td >{{$bill_date}}</td></tr>@endif
                                                                @if($due_date!='') <tr><td ><b>Due Date</b></td><td >{{$due_date}}</td></tr>@endif
                                                                @if($invoice_number!='') <tr><td ><b>Invoice No.</b></td><td >{{$invoice_number}}</td></tr>@endif
                                                                @if($customer_gst!='') <tr><td ><b>Customer GST No.</b></td><td >{{$customer_gst}}</td></tr>@endif
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>

                                    </td>
                                </tr>

                                <tr>
                                    <td>

                                        <table width="100%" border="0" cellspacing="0" cellpadding="5" style="border: 1px solid #cbcbcb;color:#5b4d4b;font-size: 13px;line-height: 15px;text-align: center;">
                                            <thead>
                                                <tr >

                                                    <th style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;width:5%">
                                                        #
                                                    </th>
                                                    <th  @if($sac_code_label=='' && $time_period_label=='') colspan="2" @endif style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb; width: 35%;text-align: left;">
                                                          {{$description_label}}  
                                                </th>
                                                @if($sac_code_label!='')
                                                <th style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb; width: 20%;">
                                                    {{$sac_code_label}}
                                                </th>
                                                @endif
                                                @if($time_period_label!='')
                                                <th style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb; width: 20%;">
                                                    {{$time_period_label}}
                                                </th>
                                                @endif
                                                <th @if($sac_code_label=='' && $time_period_label=='') colspan="3" @else colspan="2"  @endif style="border-bottom: 1px solid #cbcbcb; width: 20%;text-align: right;">
                                                     {{$total_label}}
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr> 
                                            <td style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;text-align: center;">
                                                1
                                            </td>
                                            <td @if($sac_code_label=='' && $time_period_label=='') colspan="2" @endif style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;text-align: left;">
                                                 {{$p_description}}
                                        </td>
                                        @if($sac_code_label!='')
                                        <td style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;text-align: center;">
                                            {{$p_sac_code}}
                                        </td>
                                        @endif
                                        @if($time_period_label!='')
                                        <td style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;text-align: center;">
                                            {{$p_time_period}}
                                        </td>
                                        @endif
                                        <td @if($sac_code_label=='' && $time_period_label=='') colspan="3" @else colspan="2" @endif style="border-bottom: 1px solid #cbcbcb;text-align: right;">
                                             @if($p_cost>0){{ number_format($p_cost, 2) }}@else 0.00 @endif
                                    </td>
                                </tr>
                                @if($p_cost2>0)
                                <tr> 
                                    <td style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;text-align: center;">
                                        2
                                    </td>
                                    <td @if($sac_code_label=='' && $time_period_label=='') colspan="2" @endif style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;text-align: left;">
                                         {{$p_description2}}
                                </td>
                                @if($sac_code_label!='')
                                <td style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;text-align: center;">
                                    {{$p_sac_code2}}
                                </td>
                                @endif
                                @if($time_period_label!='')
                                <td style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;text-align: center;">
                                    {{$p_time_period2}}
                                </td>
                                @endif
                                <td  @if($sac_code_label=='' && $time_period_label=='') colspan="3" @else colspan="2" @endif style="border-bottom: 1px solid #cbcbcb;text-align: right;">
                                      @if($p_cost2>0){{ number_format($p_cost2, 2) }}@else 0.00 @endif
                            </td>
                        </tr>
                        @endif

                        <tr style="text-align: center;">
                            <td colspan="3" rowspan="{{$rowspan}}" style="">
                                @if($tnc!='')
                                <p style="text-align: left;font-family: Open Sans, sans-serif;">
                                    {!! $tnc !!}
                                </p>
                                @endif
                            </td>
                            <td colspan="" style="border-left: 1px solid #cbcbcb;border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;text-align: left;">
                                <b>Sub Total</b>
                            </td>
                            <td @if($sac_code_label!='' && $time_period_label!='') colspan="2" @endif @if($sac_code_label=='' && $time_period_label=='') colspan="2" @endif style="border-bottom: 1px solid #cbcbcb;text-align: right;">
                                 <b> {{ number_format($sub_total, 2) }} </b>
                            </td>
                        </tr>
                        @if($tax_name1!='')
                        <tr style="text-align: center;">

                            <td style="border-left: 1px solid #cbcbcb;border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;text-align: left;" colspan="">
                                {{$tax_name1}}
                            </td>
                            <td @if($sac_code_label!='' && $time_period_label!='') colspan="2"  @endif @if($sac_code_label=='' && $time_period_label=='') colspan="2" @endif style="border-bottom: 1px solid #cbcbcb;text-align: right;">
                                 @if($tax1>0){{ number_format($tax1, 2) }}@else 0.00 @endif
                        </td>
                    </tr>
                    @endif
                    @if($tax_name2!='')
                    <tr style="text-align: center;">

                        <td style="border-left: 1px solid #cbcbcb;border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;text-align: left;" colspan="">
                            {{$tax_name2}}
                        </td>
                        <td @if($sac_code_label!='' && $time_period_label!='') colspan="2" @endif @if($sac_code_label=='' && $time_period_label=='') colspan="2" @endif style="border-bottom: 1px solid #cbcbcb;text-align: right;">
                             @if($tax2>0){{ number_format($tax2, 2) }}@else 0.00 @endif
                    </td>
                </tr>
                @endif
                <tr style="text-align: center;">
                    <td style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;border-left: 1px solid #cbcbcb;text-align: left;"><b>Total Rs.</b></td>
                    <td @if($sac_code_label!='' && $time_period_label!='') colspan="2" @endif @if($sac_code_label=='' && $time_period_label=='') colspan="2" @endif style="border-bottom: 1px solid #cbcbcb;text-align: right;"><b>{{ number_format($total_amount, 2) }} </b></td>
                </tr>
                @if($past_due>0)
                <tr style="text-align: center;">

                    <td style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;border-left: 1px solid #cbcbcb;text-align: left;">Past due</td>
                    <td @if($sac_code_label!='' && $time_period_label!='') colspan="2" @endif @if($sac_code_label=='' && $time_period_label=='') colspan="2" @endif style="border-bottom: 1px solid #cbcbcb;text-align: right;">{{ number_format($past_due, 2) }} </td>
                </tr>
                @endif
                @if($gst!='')
                <tr style="text-align: center;">
                    <td style="border-left: 1px solid #cbcbcb;text-align: left;border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;">GST Number</td>
                    <td @if($sac_code_label!='' && $time_period_label!='') colspan="2" @endif @if($sac_code_label=='' && $time_period_label=='') colspan="2" @endif style="text-align: right;border-bottom: 1px solid #cbcbcb;">{{$gst}}</td>
                </tr>
                @endif
                <tr>
                    <td class="bgset2" @if($sac_code_label=='' && $time_period_label=='') colspan="3" @elseif($sac_code_label!='' && $time_period_label!='') colspan="3" @else colspan="2" @endif  style="min-width: 250px; text-align:right; font-size:15px;vertical-align: middle;font-family: Open Sans, sans-serif;">
                        <b  style="width: 170px;">Grand Total : Rs. @if($total_due>0){{ number_format($total_due, 2) }}@else 0.00 @endif </b>
                    </td>
                </tr>
            </tbody>
        </table>
    </td>
</tr>
<tr>
    <td style="padding-bottom: 20px;padding-right: 20px;padding-left: 15px;">
        <div class="row">
            <div class="col-md-6 no-margin no-padding" style="text-align: left;margin-bottom: 0;font-size: 12px;float: left;width: 60%">
                <p style="font-family: Open Sans, sans-serif;color: #5b4d4b;"> * Note: This  is a system generated invoice. No signature required.</p>
            </div>
            <div class="col-md-6 no-margin no-padding" style="text-align: left;margin-bottom: 0;float: right;width: 40%;">
                <p style="float: right;margin-bottom: 0;margin-top: 0;font-family: Open Sans, sans-serif;">
                    <span style="color: #5B5B5B;vertical-align: middle;margin-right: 10px;font-family: Open Sans, sans-serif !important;" >Powered by</span> 
                    <img  src="data:image/png;base64,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" style="width: 130px;vertical-align: middle;" class="img-responsive pull-right powerbyimg" alt=""></p>
            </div>
        </div>
        <br>
    </td>
</tr>

</tbody></table>

</div>


</div>
</div>
</section>







</body>
<script>
print();
</script>
</html>