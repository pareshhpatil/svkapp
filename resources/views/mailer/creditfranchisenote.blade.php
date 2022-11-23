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
                    <table autosize="1" style="margin:0 auto; font-family: Open Sans, sans-serif;width: 750px; border: 1px solid grey;" align="center" border="0" cellspacing="0" cellpadding="10">
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
                                <td>

                                    <table autosize="1" width="100%" border="0" cellspacing="0" cellpadding="5" style="border: 1px solid #cbcbcb;color:#5b4d4b;font-size: 13px;line-height: 15px;text-align: left;">
                                        <tbody>
                                            <tr>
                                                <td colspan="2" style="min-width: 35%;border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb">
                                                    Credit Note No
                                                </td>
                                                <td colspan="2" style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb; width: 25%;text-align: left;">
                                                    {{$detail->credit_debit_no}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb">
                                                    Credit Note Date
                                                </td>
                                                <td colspan="2" style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb; width: 25%;text-align: left;">
                                                    {{ Helpers::htmlDate($detail->date) }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb">
                                                    Franchisee Name & Address
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb">
                                                    {{$detail->name}} {{$detail->address}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" width="300" style="width:50%;border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb">
                                                    Against Bill No : {{$detail->invoice_no}}
                                                </td>
                                                <td colspan="2" style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb">
                                                    Bill Date : {{ Helpers::htmlDate($detail->bill_date) }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb">
                                                    Bill Period
                                                </td>

                                                <td colspan="2" style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb">
                                                    {{$summary->bill_period}}
                                                </td>
                                            </tr>
                                            
                                            <tr>
                                                <td colspan="2" style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb">
                                                    Store UID
                                                </td>
                                                <td colspan="2" style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb">
                                                    {{$detail->customer_code}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb">
                                                    Franchisee GST No
                                                </td>
                                                <td colspan="2" style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb">
                                                    {{$detail->gst_number}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb">
                                                    State
                                                </td>
                                                <td colspan="2" style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb">
                                                    {{$detail->state}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb">
                                                    Daily Gross Sales
                                                </td>
                                            </tr>
                                            <tr>
                                                <th colspan="2" style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb">
                                                    Date
                                                </th>
                                                <th style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb; width: 25%;text-align: left;">
                                                    Sales As per system
                                                </th>
                                                <th style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb; width: 25%;text-align: left;">
                                                    Sales As per Franchisee
                                                </th>
                                            </tr>
                                            @foreach($sales as $key=>$row)
                                            <tr>

                                                <td colspan="2" style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb">
                                                    {{ Helpers::htmlDate($row->date) }}
                                                </td>

                                                <td style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb; width: 25%;text-align: left;">
                                                    {{$row->inv_gross_sale}}
                                                </td>
                                                <td style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb; width: 25%;text-align: left;">
                                                    {{$row->credit_gross_sale}}
                                                </td>

                                            </tr>
                                            @endforeach
                                            <tr>

                                                <td colspan="2" style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb">
                                                    Total
                                                </td>

                                                <td style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb; width: 25%;text-align: left;">
                                                    {{$summary->gross_bilable_sale}}
                                                </td>
                                                <td style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb; width: 25%;text-align: left;">
                                                    {{$summary->new_gross_bilable_sale}}
                                                </td>

                                            </tr>
                                            <tr>
                                                <td colspan="2" style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb">
                                                    Difference
                                                </td>

                                                <td colspan="2" style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb; width: 25%;text-align: left;">
                                                    @php $diff=$summary->gross_bilable_sale-$summary->new_gross_bilable_sale; @endphp
                                                    {{ Helpers::moneyFormatIndia($diff,2)}}
                                                </td>
                                            </tr>

                                            <tr>

                                                <td colspan="2" style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb">
                                                    Less: GST @5.00%
                                                </td>

                                                <td style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb; width: 25%;text-align: left;">
                                                    {{$summary->gross_bilable_sale-$summary->net_bilable_sale}}
                                                </td>
                                                <td style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb; width: 25%;text-align: left;">
                                                    {{$summary->new_gross_bilable_sale-$summary->new_net_bilable_sale}}
                                                </td>

                                            </tr>
                                            <tr>

                                                <td colspan="2" style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb">
                                                    Net Excess Sales Billed
                                                </td>

                                                <td style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb; width: 25%;text-align: left;">
                                                    {{$summary->net_bilable_sale}}
                                                </td>
                                                <td style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb; width: 25%;text-align: left;">
                                                    {{$summary->new_net_bilable_sale}}
                                                </td>

                                            </tr>
                                            <tr>

                                                <td colspan="2" style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb">
                                                    Franchisee Fee ({{$summary->gross_comm_percent}}% of net Sales)
                                                </td>

                                                <td style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb; width: 25%;text-align: left;">
                                                    {{$summary->gross_comm_amt}}
                                                </td>
                                                <td style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb; width: 25%;text-align: left;">
                                                    {{$summary->new_gross_comm_amt}}
                                                </td>

                                            </tr>
                                            <tr>

                                                <td colspan="2" style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb">
                                                    Waived Off {{$summary->waiver_comm_percent}}
                                                </td>

                                                <td style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb; width: 25%;text-align: left;">
                                                    {{$summary->waiver_comm_amt}}
                                                </td>
                                                <td style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb; width: 25%;text-align: left;">
                                                    {{$summary->new_waiver_comm_amt}}
                                                </td>

                                            </tr>
                                            <tr>

                                                <td colspan="2" style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb">
                                                    Net Franchise Fee {{$summary->net_comm_percent}}
                                                </td>

                                                <td style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb; width: 25%;text-align: left;">
                                                    {{$summary->net_comm_amt}}
                                                </td>
                                                <td style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb; width: 25%;text-align: left;">
                                                    {{$summary->new_net_comm_amt}}
                                                </td>

                                            </tr>
                                            <tr>

                                                <td colspan="2" style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb">
                                                    SGST 9%
                                                </td>

                                                <td style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb; width: 25%;text-align: left;">
                                                    @php $cgst=round($summary->net_comm_amt*0.09,2); @endphp
                                                    {{$cgst}}
                                                </td>
                                                <td style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb; width: 25%;text-align: left;">
                                                    @php $cgstnew=round($summary->new_net_comm_amt*0.09,2); @endphp
                                                    {{$cgstnew}}
                                                </td>

                                            </tr>
                                            <tr>

                                                <td colspan="2" style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb">
                                                    CGST 9%
                                                </td>

                                                <td style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb; width: 25%;text-align: left;">
                                                    {{$cgst}}
                                                </td>
                                                <td style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb; width: 25%;text-align: left;">
                                                    {{$cgstnew}}
                                                </td>

                                            </tr>
                                            <tr>

                                                <td colspan="2" style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb">
                                                    Total Franchisee Fee
                                                </td>

                                                <td style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb; width: 25%;text-align: left;">
                                                    @php $total=round($summary->net_comm_amt + $cgst + $cgst); @endphp
                                                    {{$total}}
                                                </td>
                                                <td style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb; width: 25%;text-align: left;">
                                                    @php $total=round($summary->new_net_comm_amt+$cgstnew+$cgstnew); @endphp
                                                    {{$total}}
                                                </td>

                                            </tr>
                                            <tr>
                                                <td colspan="2" style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb">
                                                    Credit / Debit Note Amount/ Royalty
                                                </td>
                                                <td colspan="2" style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb; width: 25%;text-align: left;">
                                                    {{$detail->total_amount}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb">
                                                    Income Tax PAN
                                                </td>
                                                <td colspan="2" style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb; width: 25%;text-align: left;">
                                                    {{$merchant->pan}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb">
                                                    Category of Service
                                                </td>
                                                <td colspan="2" style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb; width: 25%;text-align: left;">
                                                    Franchise Service
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb">
                                                    Maharashtra GST
                                                </td>
                                                <td colspan="2" style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb; width: 25%;text-align: left;">
                                                    {{$merchant->gst_number}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb">
                                                    CIN
                                                </td>
                                                <td colspan="2" style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb; width: 25%;text-align: left;">
                                                    {{$merchant->cin_no}}
                                                </td>
                                            </tr>


                                        </tbody>

                                    </table>

                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p style="color:#5b4d4b;font-size: 13px;line-height: 15px;">
                                    <ol style="color:#5b4d4b;font-size: 13px;line-height: 20px;">
                                        <li>
                                            Previous Balance, if any, needs to be paid immediately.
                                        </li>
                                        <li>
                                            All Amounts above are in "Rs".
                                        </li>
                                        <li>
                                            All Disputes are subject to Mumbai Jurisdiction only.
                                        </li>
                                    </ol>
                                    <p style="color:#5b4d4b;font-size: 13px;line-height: 15px;">
                                        Declaration-" I/we hereby certify that my/our registration certificate under the Maharashtra Value Added Tax Act 2002 is
                                        in force on the date on which the sales of the goods specified in this tax invoice is made by me/us and that the
                                        transaction of sale covered by this tax invoice has been effected by me/us and it shall be accounted for in the turnover of
                                        sales while filling of return and the due tax,if any, payment on the sale has been paid or shall be paid"
                                        <br>
                                        <br>
                                        This is a Computer generated document and does not require signature
                                    <p style="color:#5b4d4b;font-size: 17px;line-height: 22px;">
                                        For {{$merchant->company_name}}
                                        <br>
                                        (Authorised Signatory)
                                        <br><br>
                                    </p>
                                    </p>
                                    </p>
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