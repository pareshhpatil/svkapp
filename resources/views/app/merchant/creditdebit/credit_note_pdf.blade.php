<html lang="en">

<body>
    <style>
        html {
            margin: 0px;
            margin-top: 10px;
            margin-bottom: 10px;
        }

        body {
            font-family: Open Sans, sans-serif;
        }

        .form-control-plaintext {
            color: #5b4d4b;
            font-size: 13px;
            line-height: 14px;
            height: auto;
            padding: 0;
        }

        .tx-c {
            text-align: center;
        }

        .tx-r {
            text-align: right;
        }

        .bgset {
            background-color: #5B5B5B;
            color: #FFFFFF;
        }

        .bgset2 {
            background-color: {
                    {
                    $bg_color2
                }
            }

            ;

            color: {
                    {
                    $text_color2
                }
            }

            ;
        }
    </style>

    <section class="jumbotron jumbotron-features bg-transparent py-4" id="header">
        <div class="container">
            <div class="row align-items-center">


                <div class="d-none d-lg-block col-12 col-md-12 col-lg-12 col-xl-12">
                    <table style="margin:0 auto; font-family: Open Sans, sans-serif;width: 750px; border: 1px solid grey;" align="center" border="0" cellspacing="0" cellpadding="10">
                        <tbody>
                            <tr>
                                <td style="font-size:15px; line-height:30px;">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="5">
                                        <tbody>
                                            <tr>
                                                <td width="280" style="">
                                                    <span style="font-size:20px; color:#6e605d;">{{$merchant->company_name}}</span>
                                                    <span style="font-size:13px; display:block; margin-top:5px; margin-bottom:1px;line-height: 16px;">{{$merchant->address}} </span>
                                                    @if($merchant->business_contact !='')<span style="font-size:13px; display:block; margin-top:1px; margin-bottom:1px;line-height: 16px;">
                                                        <div class="input-group">Contact: {{$merchant->business_contact}}</div>
                                                    </span>@endif
                                                    @if($merchant->business_email !='')<span style="font-size:13px; display:block; margin-top:1px; margin-bottom:1px;line-height: 16px;">
                                                        <div class="input-group"> Email: {{$merchant->business_email}}</div>
                                                    </span>
                                                    @endif
                                                    @if($merchant->gst_number !='')<span style="font-size:13px; display:block; margin-top:1px; margin-bottom:1px;line-height: 16px;">
                                                        <div class="input-group"> GST Number: {{$merchant->gst_number}}</div>
                                                    </span>
                                                    @endif
                                                </td>
                                                <td width="90" align="center" valign="top">
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr style="border-spacing: 0px;">
                                <td class="bgset" style="text-align: center;border-top: 1px solid grey;border-bottom: 1px solid grey;font-size:18px;">
                                    <b>Credit note</b>
                                </td>
                            </tr>
                            <tr>
                                <td style="font-size:11px;  border-bottom:1px #cbcbcb;">
                                    <table border="0" cellspacing="0" cellpadding="5" style="font-size: 13px;line-height: 15px;width: 100%;">
                                        <tbody>
                                            <tr>
                                                <td style="font-size:11px;  border-bottom:1px #cbcbcb;border-right: 1px solid #cbcbcb;width: 50%; ">
                                                    <table border="0" cellspacing="0" cellpadding="5" style="color:#5b4d4b;font-size: 13px;line-height: 14px;">

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
                                                            <tr>
                                                                <td style="min-width: 120px;"><b>Customer name</b></td>
                                                                <td>{{$detail->name}}</td>
                                                            </tr>
                                                            <tr>
                                                                <td style="min-width: 120px;"><b>Email id</b></td>
                                                                <td>{{$detail->email_id}}</td>
                                                            </tr>
                                                            <tr>
                                                                <td style="min-width: 120px;"><b>Mobile no</b></td>
                                                                <td>{{$detail->mobile}}</td>
                                                            </tr>
                                                            <tr>
                                                                <td style="min-width: 120px;"><b>State</b></td>
                                                                <td>{{$detail->state}}</td>
                                                            </tr>
                                                            @if($merchant->gst_number !='')
                                                            <tr>
                                                                <td style="min-width: 120px;"><b>GST Number</b></td>
                                                                <td>{{$detail->gst_number}}</td>
                                                            </tr>
                                                            @endif

                                                        </tbody>
                                                    </table>
                                                </td>
                                                <td style="font-size:11px;  border-bottom:1px #cbcbcb;width: 50%; ">
                                                    <table border="0" cellspacing="0" cellpadding="5" style="color:#5b4d4b;font-size: 13px;line-height: 14px;">

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
                                            <tr>

                                                <th style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;width:5%">
                                                    SN.
                                                </th>
                                                <th style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb; width: 25%;text-align: left;">
                                                    Particular
                                                </th>
                                                <th style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb; width: 15%;">
                                                    SAC code
                                                </th>
                                                <th style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb; width: 10%;">
                                                    Unit
                                                </th>
                                                <th style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb; width: 15%;">
                                                    Rate
                                                </th>
                                                <th style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb; width: 15%;">
                                                    GST
                                                </th>
                                                <th colspan="2" style="border-bottom: 1px solid #cbcbcb; width: 15%;text-align: right;">
                                                    Total Amount
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($particulars as $key=>$row)
                                            <tr>
                                                <td class="col-md-1 td-c" style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;">
                                                    {{$key+1}}
                                                </td>
                                                <td class="col-md-5" style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;">
                                                    {{$row->particular_name}}
                                                </td>
                                                <td class="col-md-3 td-c" style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;">
                                                    {{$row->sac_code}}
                                                </td>
                                                <td class="col-md-3 td-c" style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;">
                                                    {{$row->qty}}
                                                </td>
                                                <td class="col-md-3 td-c" style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;">
                                                    {{$row->rate}}
                                                </td>
                                                <td class="col-md-3 td-c" style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;">
                                                    {{$row->gst_percent}}%
                                                </td>
                                                <td colspan="2" class="col-md-3 td-c" style="border-bottom: 1px solid #cbcbcb;">
                                                    {{$row->amount}}
                                                </td>
                                            </tr>
                                            @endforeach


                                            <tr>
                                                <td colspan="5" class="col-md-8" style="border-bottom: 0;">
                                                </td>
                                                <td style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;border-left: 1px solid #cbcbcb;">
                                                    <b>SUB TOTAL</b>
                                                </td>
                                                <td colspan="2" style="border-bottom: 1px solid #cbcbcb;">
                                                    <b> {{$detail->base_amount}}</b>
                                                </td>
                                            </tr>
                                            @if($detail->cgst_amount>0)
                                            <tr>
                                                <td colspan="5" class="col-md-8" style="border-bottom: 0;border-top: 0;">
                                                </td>
                                                <td style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;border-left: 1px solid #cbcbcb;">
                                                    <b>CGST amount</b>
                                                </td>
                                                <td colspan="2" style="border-bottom: 1px solid #cbcbcb;">
                                                    <b> {{$detail->cgst_amount}}</b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="5" class="col-md-8" style="border-bottom: 0;border-top: 0;">
                                                </td>
                                                <td style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;border-left: 1px solid #cbcbcb;">
                                                    <b>SGST amount</b>
                                                </td>
                                                <td colspan="2" style="border-bottom: 1px solid #cbcbcb;">
                                                    <b> {{$detail->sgst_amount}}</b>
                                                </td>
                                            </tr>
                                            @endif

                                            @if($detail->igst_amount>0)
                                            <tr>
                                                <td colspan="5" class="col-md-8" style="border-bottom: 0;border-top: 0;">
                                                </td>
                                                <td style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;border-left: 1px solid #cbcbcb;">
                                                    <b>IGST amount</b>
                                                </td>
                                                <td colspan="2" style="border-bottom: 1px solid #cbcbcb;">
                                                    <b> {{$detail->igst_amount}}</b>
                                                </td>
                                            </tr>
                                            @endif

                                            <tr>
                                                <td colspan="5" class="col-md-8" style="border: 0;text-align: left;;">
                                                    @if($detail->narrative!='')
                                                    Narrative: {{$detail->narrative}}
                                                    @endif
                                                </td>
                                                <td style="border-right: 1px solid #cbcbcb;border-left: 1px solid #cbcbcb;">
                                                    <b>Total amount</b>
                                                </td>
                                                <td colspan="2">
                                                    <b> {{$detail->total_amount}}</b>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>


                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </section>
</body>
<script>
    //print();
</script>

</html>