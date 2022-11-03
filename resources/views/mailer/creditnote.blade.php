<table  style="margin:0 auto; font-family:Verdana, Arial;width: 600px; border: 1px solid black;" align="center" width="600" border="0" cellspacing="0" cellpadding="10" >
    <tr>
        <td style="font-size:15px; line-height:30px;"><table width="100%" border="0" cellspacing="0" cellpadding="5" >
                <tr>
                    <td width="160" align="center" valign="top">
                        @if($merchant->logo!='')<img src="{{ config('app.APP_URL') }}uploads/images/logos/{{$merchant->logo}}" style="max-width:200px;max-height:100px;" />@endif
                    </td>
                    <td width="330" >
                        <span style="font-size:20px; color:#6e605d;">{{$merchant->company_name}}</span> 
                        <span style="font-size:12px; display:block;  margin-bottom:1px;line-height: 1.5;"> {{$merchant->address}}</span> 
                        <span style="font-size:12px; display:block;  margin-bottom:1px;line-height: 1.5;"> Contact: {{$merchant->business_contact}}</span> 
                        <span style="font-size:12px; display:block;  margin-bottom:1px;line-height: 1.5;"> Email: {{$merchant->business_email}}</span> 
                    </td>
                </tr>
            </table>  
        </td>
    </tr>
    <tr style="border-spacing: 0px;">
        <td style="text-align: center;background-color: #E5E5E5 ;font-size:18px;" >
            <b>Credit note</b>
        </td>
    </tr>
    <tr>
        <td style="font-size:12px; line-height:20px;">
            <table>
                <tr>
                    <td>
                        <table width="290" border="0" cellspacing="0" cellpadding="5" style="color:#5b4d4b;font-size: 13px;line-height: 14px;">
                            <tr style="line-height: 17px;"><td> <b>Customer name</b> : {{$detail->name}}</td></tr>
                            <tr style="line-height: 17px;"><td> <b>Email id</b> : {{$detail->email_id}}</td></tr>
                            <tr style="line-height: 17px;"><td> <b>Mobile no</b> : {{$detail->mobile}}</td></tr>
                            <tr style="line-height: 17px;"><td> <b>State</b> : {{$detail->state}}</td></tr>
                            <tr style="line-height: 17px;"><td> <b>GST Number</b> : {{$detail->gst_number}}</td></tr>
                        </table>
                    </td>
                    <td>

                        <table width="290" border="0" cellspacing="0" cellpadding="5" style="color:#5b4d4b;font-size: 13px;line-height: 15px;">
                            <tr style="line-height: 17px;"><td> <b>Credit note no. </b> : {{$detail->credit_debit_no}}</td></tr>
                            <tr style="line-height: 17px;"><td> <b>Credit note date </b> : {{ Helpers::htmlDate($detail->date) }}</td></tr>
                            <tr style="line-height: 17px;"><td> <b>Invoice No </b> : {{$detail->invoice_no}}</td></tr>
                            <tr style="line-height: 17px;"><td> <b>Bill date </b> : {{ Helpers::htmlDate($detail->bill_date) }}</td></tr>
                            <tr style="line-height: 17px;"><td> <b>Due date </b> : {{ Helpers::htmlDate($detail->due_date) }}</td></tr>
                        </table>
                    </td>
                </tr>
            </table>

        </td>

    </tr>



    <tr >
        <td>
            <div>
                <table width="100%" border="1" cellspacing="0"  cellpadding="5" style="border-collapse: collapse;color:#5b4d4b;font-size: 13px;line-height: 15px;">
                    <thead>
                        <tr style="background-color: #E5E5E5 ;">
                            <th style="width:20px;">Sr.</th>
                            <th  style="width:200px;">Particular</th>
                            <th  style="width:100px;">SAC</th>
                            <th style="width: 60px;">Unit</th>
                            <th style="width: 60px;">Rate</th>
                            <th style="width: 100px;">GST</th>
                            <th style="width: 150px;">Total Amount</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach($particulars as $key=>$row)
                        <tr> 
                            <td  style="border-top: 0;border-bottom: 0;">
                                {{$key+1}}
                            </td>
                            <td class="col-md-5" style="border-top: 0;border-bottom: 0;">
                                {{$row->particular_name}}
                            </td>
                            <td  style="border-top: 0;border-bottom: 0;">
                                {{$row->sac_code}}
                            </td>
                            <td  style="border-top: 0;border-bottom: 0;">
                                {{$row->qty}}
                            </td>
                            <td  style="border-top: 0;border-bottom: 0;">
                                {{$row->rate}}
                            </td>
                            <td  style="border-top: 0;border-bottom: 0;">
                                {{$row->gst_percent}}%
                            </td>
                            <td  style="border-top: 0;border-bottom: 0;">
                                {{$row->amount}}
                            </td>
                        </tr>
                        @endforeach
                        <tr>
                            <td colspan="4"  style="border-bottom: 0;">
                            </td>
                            <td colspan="2" >
                                <b>SUB TOTAL</b>
                            </td>
                            <td  >
                                <b>  140.00</b>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4"  style="border: 0;">
                            </td>
                            <td colspan="2" >
                                <b>Total amount</b>
                            </td>
                            <td  >
                                <b> {{$detail->total_amount}}</b>
                            </td>
                        </tr>

                    </tbody>



                </table>
            </div>
        </td>
    </tr>
    <tr>
        <td style="color:#5b4d4b; line-height:25px; text-align:right; font-size:12px;background-color: #18aebf;color: #ffffff;">
            <span style="float: left;">If you would like to collect online payments for your business, <a target="_BLANK" href="https://www.swipez.in/merchant/register">register now</a> on Swipez.</span>
        </td>
    </tr>


    <tr>
        <td style="font-size:13px;  line-height:30px; ">
            <table width="100%" border="0" cellspacing="0" cellpadding="5">
                <tr>
                    <td valign="top" style="">
                        If you would prefer not receiving our emails, please <a target="_BLANK" href="{$unsub_link}">click here</a> to unsubscribe.
                    </td>
                </tr>

            </table>
        </td>
    </tr>
</table>