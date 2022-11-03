{strip}
    <table  style="margin:0 auto; font-family:Verdana, Arial;border: 1px solid #e2e2e2;width: 600px;margin-bottom: 10px;" id="impinfo" align="center" width="600" border="0" cellspacing="0" cellpadding="10" >
        <tr>
            <td style="font-size:12px; line-height:20px;  border-bottom:1px #e2e2e2;">
                <div style="float:left; width:280px; margin-right:5px;">
                    <table width="580" border="0" cellspacing="0" cellpadding="5" style="color:#5b4d4b;font-size: 12px;line-height: 14px;">
                        <tr>
                            <td style="font-size: 18px;color: #275770;width: 100%;font-weight: bold;"> 
                                <span style="font-size: 18px;">Next steps</span><span  style="font-size: 20px;"> - How to Download NOC and BSA</span>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-size:13px; line-height:20px;"> 
								Step 1 -  Login to seller central
								<br>
								Step 2 - Navigate to the page where you made the payment
								<br>
								Step 3 - Download NOC and BSA by clicking on the link as shown in the screen shot below
                            </td>
                        </tr>
						<tr>
                            <td> 
                                <img style="max-width:100%;" src="https://h7sak8am43.swipez.in/assets/admin/pages/media/invoice/amazon-step.png">
                            </td>
                        </tr>
                    </table>
                </div>

            </td>
        </tr>
    </table>
    <table  style="margin:0 auto; font-family:Verdana, Arial;border: 1px solid #e2e2e2;width: 600px" align="center" width="600" border="0" cellspacing="0" cellpadding="10" >
        <tr>
            <td style="font-size:12px; line-height:20px;  border-bottom:1px #e2e2e2;">
                <div style="float:left; width:280px; margin-right:5px;">
                    <table width="580" border="0" cellspacing="0" cellpadding="5" style="color:#5b4d4b;font-size: 12px;line-height: 14px;">
                        <tr>
                            <td> <span style="font-size: 18px;color: #275770;width: 100%;font-weight: bold;">Thank You</span> </td>
                        </tr>
                        <tr>
                            <td> <span style="font-size: 13px;width: 100%;">Your Payment is successful. Please quote your receipt number for any queries relating to this transaction in future. Please note that this receipt is valid subject to the realisation of your payment.</span> </td>
                        </tr>
                    </table>
                </div>

            </td>
        </tr>

        <tr >
            <td>
                <div>
                    <table width="100%" border="0" cellspacing="0" cellpadding="5" style="color:#5b4d4b;font-size: 13px;line-height: 15px;line-height: 25px;">
                        <thead>
                            <tr style="background-color: #275770;height: 40px;color: #FFFFFF;font-size: 18px;">
                                <th colspan="2" style="font-weight: normal;" >Transaction Details</th>
                            </tr>
                        </thead>
                    </table>
                    <table width="100%" border="0" cellspacing="0" cellpadding="5" style="color:#5b4d4b;font-size: 13px;line-height: 15px;line-height: 25px;">
                        <tr>
                            <td style="border: 1px solid #e2e2e2;border-top: 0px; width: 40%;">Payment Ref Number</td>
                            <td style="border: 1px solid #e2e2e2;border-top: 0px;border-left: 0px;"><span style="background-color: turquoise;"><b>{$response.TransactionID}</b></span></td>
                        </tr>
                    </table>
                    {if $response.customer_code!=''}
                        <table width="100%" border="0" cellspacing="0" cellpadding="5" style="color:#5b4d4b;font-size: 13px;line-height: 15px;line-height: 25px;">
                            <tr>
                                <td style="border: 1px solid #e2e2e2;border-top: 0px; width: 40%;">Customer code</td>
                                <td style="border: 1px solid #e2e2e2;border-top: 0px;border-left: 0px;">{$response.customer_code}&nbsp;</td>
                            </tr>
                        </table>
                    {/if}
                    <table width="100%" border="0" cellspacing="0" cellpadding="5" style="color:#5b4d4b;font-size: 13px;line-height: 15px;line-height: 25px;">
                        <tr>
                            <td style="border: 1px solid #e2e2e2;border-top: 0px; width: 40%;">Patron Name</td>
                            <td style="border: 1px solid #e2e2e2;border-top: 0px;border-left: 0px;"> {$response.BillingName} &nbsp;</td>
                        </tr>
                    </table>

                    <table width="100%" border="0" cellspacing="0" cellpadding="5" style="color:#5b4d4b;font-size: 13px;line-height: 15px;line-height: 25px;">
                        <tr>
                            <td style="border: 1px solid #e2e2e2;border-top: 0px; width: 40%;">Patron Email ID</td>
                            <td style="border: 1px solid #e2e2e2;border-top: 0px;border-left: 0px;"> {$response.BillingEmail} &nbsp;</td>
                        </tr>
                    </table>

                    <table width="100%" border="0" cellspacing="0" cellpadding="5" style="color:#5b4d4b;font-size: 13px;line-height: 15px;line-height: 25px;">
                        <tr>
                            <td style="border: 1px solid #e2e2e2;border-top: 0px; width: 40%;">Payment Towards</td>
                            <td style="border: 1px solid #e2e2e2;border-top: 0px;border-left: 0px;"> {$response.merchant_name} 
                                {if $response.main_company_name!=''}
                                    (An official franchisee of {$response.main_company_name})
                                {/if}&nbsp;</td>
                        </tr>
                    </table>
                    {if $response.invoice_number!=''}
                        <table width="100%" border="0" cellspacing="0" cellpadding="5" style="color:#5b4d4b;font-size: 13px;line-height: 15px;line-height: 25px;">
                            <tr>
                                <td style="border: 1px solid #e2e2e2;border-top: 0px; width: 40%;">Invoice Number</td>
                                <td style="border: 1px solid #e2e2e2;border-top: 0px;border-left: 0px;"> {$response.invoice_number} &nbsp;</td>
                            </tr>
                        </table>
                    {/if}



                    <table width="100%" border="0" cellspacing="0" cellpadding="5" style="color:#5b4d4b;font-size: 13px;line-height: 15px;line-height: 25px;">
                        <tr>
                            <td style="border: 1px solid #e2e2e2;border-top: 0px; width: 40%;">Transaction Ref Number</td>
                            <td style="border: 1px solid #e2e2e2;border-top: 0px;border-left: 0px;">{$response.MerchantRefNo} &nbsp;</td>
                        </tr>
                    </table>

                    <table width="100%" border="0" cellspacing="0" cellpadding="5" style="color:#5b4d4b;font-size: 13px;line-height: 15px;line-height: 25px;">
                        <tr>
                            <td style="border: 1px solid #e2e2e2;border-top: 0px; width: 40%;">Payment Date &amp; Time</td>
                            <td style="border: 1px solid #e2e2e2;border-top: 0px;border-left: 0px;">{$response.DateCreated} &nbsp;</td>
                        </tr>
                    </table>

                    <table width="100%" border="0" cellspacing="0" cellpadding="5" style="color:#5b4d4b;font-size: 13px;line-height: 15px;line-height: 25px;">
                        <tr>
                            <td style="border: 1px solid #e2e2e2;border-top: 0px; width: 40%;">Payment Amount (Rs)</td>
                            <td style="border: 1px solid #e2e2e2;border-top: 0px;border-left: 0px;">{$response.Amount}/- &nbsp;</td>
                        </tr>
                    </table>
                    {if $response.deduct_amount>0}
                        <table width="100%" border="0" cellspacing="0" cellpadding="5" style="color:#5b4d4b;font-size: 13px;line-height: 15px;line-height: 25px;">
                            <tr>
                                <td style="border: 1px solid #e2e2e2;border-top: 0px; width: 40%;">Deduct {$response.deduct_text}</td>
                                <td style="border: 1px solid #e2e2e2;border-top: 0px;border-left: 0px;">{$response.deduct_amount}/- &nbsp;</td>
                            </tr>
                        </table>
                    {/if}

                    {if $coupon_code!=''}
                        <table width="100%" border="0" cellspacing="0" cellpadding="5" style="color:#5b4d4b;font-size: 13px;line-height: 15px;line-height: 25px;">
                            <tr>
                                <td style="border: 1px solid #e2e2e2;border-top: 0px; width: 40%;">Coupon code applied</td>
                                <td style="border: 1px solid #e2e2e2;border-top: 0px;border-left: 0px;"> {$coupon_code} &nbsp;</td>
                            </tr>
                        </table>
                    {/if}
                    {if $response.discount>0}
                        <table width="100%" border="0" cellspacing="0" cellpadding="5" style="color:#5b4d4b;font-size: 13px;line-height: 15px;line-height: 25px;">
                            <tr>
                                <td style="border: 1px solid #e2e2e2;border-top: 0px; width: 40%;">Coupon Discount</td>
                                <td style="border: 1px solid #e2e2e2;border-top: 0px;border-left: 0px;">{$response.discount}/- &nbsp;</td>
                            </tr>
                        </table>
                    {/if}
                    {if $response.narrative!=''}
                        <table width="100%" border="0" cellspacing="0" cellpadding="5" style="color:#5b4d4b;font-size: 13px;line-height: 15px;line-height: 25px;">
                            <tr>
                                <td style="border: 1px solid #e2e2e2;border-top: 0px; width: 40%;">Purpose of Payment</td>
                                <td style="border: 1px solid #e2e2e2;border-top: 0px;border-left: 0px;">{$response.narrative} &nbsp;</td>
                            </tr>
                        </table>
                    {/if}

                    <table width="100%" border="0" cellspacing="0" cellpadding="5" style="color:#5b4d4b;font-size: 13px;line-height: 15px;line-height: 25px;">
                        <tr>
                            <td style="border: 1px solid #e2e2e2;border-top: 0px; width: 40%;">Mode of Payment</td>
                            <td style="border: 1px solid #e2e2e2;border-top: 0px;border-left: 0px;">{$response.payment_mode} &nbsp;</td>
                        </tr>

                    </table>

                </div>
            </td>
        </tr>
        <tr>
            <td style="font-size:12px; line-height:20px;  border-bottom:1px #e2e2e2;">
                <div style="float:left; width:280px; margin-right:5px;">
                    <table width="580" border="0" cellspacing="0" cellpadding="5" style="color:#5b4d4b;font-size: 12px;line-height: 14px;">
                        <tr>
                            <td> 
                                <span style="font-size: 18px;color: #275770;width: 100%;font-weight: bold;">Important Information</span> 
                            </td>
                        </tr>
                        {$response.mailer_content=$response.mailer_content|replace:'__MERCHANT_NAME__':$response.merchant_name}
                        {$response.mailer_content=$response.mailer_content|replace:'__MERCHANT_EMAIL__':$response.merchant_email}
                        {$response.mailer_content=$response.mailer_content|replace:'__MERCHANT_MOBILE__':$response.merchant_mobile}
                        {$response.mailer_content}
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
{/strip}