
<table  style="margin:0 auto; font-family:Verdana, Arial;border: 1px solid #e2e2e2;width: 600px" align="center" width="600" border="0" cellspacing="0" cellpadding="10" >
    <tr>
        <td style="font-size:15px; line-height:30px;"><table width="100%" border="0" cellspacing="0" cellpadding="5" style="border-bottom:1px solid #e2e2e2;">
                <tr>
                    <td style="float: left;width: 200px;"  valign="top">
                        {if $logo!=''}
                            <img src="{$logo}" style="max-width:200px;max-height:100px;" />
                        {/if}
                    </td>
                    <td  style="float: right;width: 350px;text-align: right;" >
                        <span style="font-size: 20px;color: #275770;width: 100%;font-weight: bold;">{$response.company_name}</span> 
                        <span style="font-size:15px; display:block; margin-top:3px; margin-bottom:1px;">Transaction Receipt </span>
                    </td>
                </tr>
            </table>  </td>
    </tr>
    <tr>
        <td style="font-size:12px; line-height:20px;  border-bottom:1px #e2e2e2;">
            <div style="float:left; width:280px; margin-right:5px;">
                <table width="580" border="0" cellspacing="0" cellpadding="5" style="color:#5b4d4b;font-size: 12px;line-height: 14px;">
                    <tr>
                        <td> <span style="font-size: 18px;color: #275770;width: 100%;font-weight: bold;">Thank You</span> </td>
                    </tr>
                    <tr>
                        <td> <span style="font-size: 13px;width: 100%;">{$from} Payment is successful. Please quote your receipt number for any queries relating to this transaction in future. Please note that this receipt is valid subject to the realisation of your payment.</span> </td>
                    </tr>
                </table>
            </div>

        </td>
    </tr>

    <tr >
        <td>
            <div>
                <table width="100%" border="0" cellspacing="0" cellpadding="5" style="color:#5b4d4b;font-size: 13px;line-height: 15px;">
                    <thead>
                        <tr style="background-color: #275770;height: 40px;color: #FFFFFF;font-size: 18px;">
                            <th colspan="2" style="font-weight: normal;" >Transaction Details</th>
                        </tr>
                    </thead>
                </table>
                {if $response.udf1!=''}
                    <table width="100%" border="0" cellspacing="0" cellpadding="5" style="color:#5b4d4b;font-size: 13px;line-height: 15px;">
                        <tr>
                            <td style="border: 1px solid #e2e2e2;border-top: 0px; width: 40%;">Customer Code</td>
                            <td style="border: 1px solid #e2e2e2;border-top: 0px;border-left: 0px;">{$response.udf1} &nbsp;</td>
                        </tr>
                    </table>
                {/if}
                <table width="100%" border="0" cellspacing="0" cellpadding="5" style="color:#5b4d4b;font-size: 13px;line-height: 15px;">
                    <tr>
                        <td style="border: 1px solid #e2e2e2;border-top: 0px; width: 40%;">Name</td>
                        <td style="border: 1px solid #e2e2e2;border-top: 0px;border-left: 0px;">{$response.billing_name} &nbsp;</td>
                    </tr>
                </table>

                <table width="100%" border="0" cellspacing="0" cellpadding="5" style="color:#5b4d4b;font-size: 13px;line-height: 15px;">
                    <tr>
                        <td style="border: 1px solid #e2e2e2;border-top: 0px; width: 40%;">Email ID</td>
                        <td style="border: 1px solid #e2e2e2;border-top: 0px;border-left: 0px;">{$response.billing_email} &nbsp;</td>
                    </tr>
                </table>

                <table width="100%" border="0" cellspacing="0" cellpadding="5" style="color:#5b4d4b;font-size: 13px;line-height: 15px;">
                    <tr>
                        <td style="border: 1px solid #e2e2e2;border-top: 0px; width: 40%;">Payment Towards</td>
                        <td style="border: 1px solid #e2e2e2;border-top: 0px;border-left: 0px;">{$response.company_name} &nbsp;</td>
                    </tr>
                </table>
                <table width="100%" border="0" cellspacing="0" cellpadding="5" style="color:#5b4d4b;font-size: 13px;line-height: 15px;">
                    <tr>
                        <td style="border: 1px solid #e2e2e2;border-top: 0px; width: 40%;">Merchant Ref Number</td>
                        <td style="border: 1px solid #e2e2e2;border-top: 0px;border-left: 0px;">{$response.reference_no} &nbsp;</td>
                    </tr>
                </table>

                <table width="100%" border="0" cellspacing="0" cellpadding="5" style="color:#5b4d4b;font-size: 13px;line-height: 15px;">
                    <tr>
                        <td style="border: 1px solid #e2e2e2;border-top: 0px; width: 40%;">Transaction Ref Number</td>
                        <td style="border: 1px solid #e2e2e2;border-top: 0px;border-left: 0px;">{$response.transaction_id} &nbsp;</td>
                    </tr>
                </table>

                <table width="100%" border="0" cellspacing="0" cellpadding="5" style="color:#5b4d4b;font-size: 13px;line-height: 15px;">
                    <tr>
                        <td style="border: 1px solid #e2e2e2;border-top: 0px; width: 40%;">Payment Date &amp; Time</td>
                        <td style="border: 1px solid #e2e2e2;border-top: 0px;border-left: 0px;">{$response.date} &nbsp;</td>
                    </tr>
                </table>

                <table width="100%" border="0" cellspacing="0" cellpadding="5" style="color:#5b4d4b;font-size: 13px;line-height: 15px;">
                    <tr>
                        <td style="border: 1px solid #e2e2e2;border-top: 0px; width: 40%;">Payment Amount (Rs)</td>
                        <td style="border: 1px solid #e2e2e2;border-top: 0px;border-left: 0px;">{$response.amount}/- &nbsp;</td>
                    </tr>
                </table>
                <table width="100%" border="0" cellspacing="0" cellpadding="5" style="color:#5b4d4b;font-size: 13px;line-height: 15px;">
                    <tr>
                        <td style="border: 1px solid #e2e2e2;border-top: 0px; width: 40%;">Mode of Payment</td>
                        <td style="border: 1px solid #e2e2e2;border-top: 0px;border-left: 0px;">{$response.mode} &nbsp;</td>
                    </tr>

                </table>
                <table width="100%" border="0" cellspacing="0" cellpadding="5" style="color:#5b4d4b;font-size: 13px;line-height: 15px;">
                    <tr>
                        <td style="border: 1px solid #e2e2e2;border-top: 0px; width: 40%;">Address</td>
                        <td style="border: 1px solid #e2e2e2;border-top: 0px;border-left: 0px;">{$response.billing_address} &nbsp;</td>
                    </tr>

                </table>
                <table width="100%" border="0" cellspacing="0" cellpadding="5" style="color:#5b4d4b;font-size: 13px;line-height: 15px;">
                    <tr>
                        <td style="border: 1px solid #e2e2e2;border-top: 0px; width: 40%;">City</td>
                        <td style="border: 1px solid #e2e2e2;border-top: 0px;border-left: 0px;">{$response.billing_city} &nbsp;</td>
                    </tr>

                </table>
                <table width="100%" border="0" cellspacing="0" cellpadding="5" style="color:#5b4d4b;font-size: 13px;line-height: 15px;">
                    <tr>
                        <td style="border: 1px solid #e2e2e2;border-top: 0px; width: 40%;">State</td>
                        <td style="border: 1px solid #e2e2e2;border-top: 0px;border-left: 0px;">{$response.billing_state} &nbsp;</td>
                    </tr>

                </table>
                <table width="100%" border="0" cellspacing="0" cellpadding="5" style="color:#5b4d4b;font-size: 13px;line-height: 15px;">
                    <tr>
                        <td style="border: 1px solid #e2e2e2;border-top: 0px; width: 40%;">Zipcode</td>
                        <td style="border: 1px solid #e2e2e2;border-top: 0px;border-left: 0px;">{$response.billing_postal_code} &nbsp;</td>
                    </tr>

                </table>
            </div>
        </td>
    </tr>
    <tr>
        <td style="color:#5b4d4b; line-height:30px; border-top:1px solid #e2e2e2; text-align:right; font-size:13px;">

            <span style="float: left;">&copy; {$current_year} OPUS Net Pvt. Handmade in Pune.</span>
            <span style="color: #275770;font-size: 15px;font-weight: 400;margin-right: 10px;">powered by</span><img src="https://www.swipez.in/images/logo.png" style="float:right; clear:right;" width="auto" height="37" class="img1" />

        </td>
    </tr>

</table>
