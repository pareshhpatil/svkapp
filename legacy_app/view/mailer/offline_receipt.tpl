<table style="margin:0 auto; font-family:Verdana, Arial;border: 1px solid #e2e2e2;width: 600px" align="center"
    width="600" border="0" cellspacing="0" cellpadding="10">
    <tr>
        <td style="font-size:15px; line-height:30px;">
            <table width="100%" border="0" cellspacing="0" cellpadding="5" style="border-bottom:1px solid #e2e2e2;">
                <tr>
                    <td style="float: left;width: 200px;" valign="top">
                        <img src="{if $logo!=''}{$logo}{else}https://www.swipez.in/images/logo.png{/if}" width="200"
                            style="max-width:200px;max-height:100px;" />
                    </td>
                    <td style="float: right;width: 350px;text-align: right;">
                        <span
                            style="font-size: 20px;color: #275770;width: 100%;font-weight: bold;">{$response.company_name}</span>
                        {if $response.main_company_name!=''}
                            <span style="font-size:11px; display:block;  margin-bottom:1px;"> (An official franchisee of
                                {$response.main_company_name})</span>
                        {/if}
                        <span style="font-size:15px; display:block; margin-top:3px; margin-bottom:1px;">Transaction
                            Receipt </span>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td style="font-size:12px; line-height:20px;  border-bottom:1px #e2e2e2;">
            <div style="float:left; width:280px; margin-right:5px;">
                <table width="580" border="0" cellspacing="0" cellpadding="5"
                    style="color:#5b4d4b;font-size: 12px;line-height: 14px;">
                    <tr>
                        <td> <span style="font-size: 18px;color: #275770;width: 100%;font-weight: bold;">Thank
                                You</span> </td>
                    </tr>
                    <tr>
                        <td> <span style="font-size: 13px;width: 100%;">Your Payment is successful. Please quote your
                                receipt number for any queries relating to this transaction in future. Please note that
                                this receipt is valid subject to the realisation of your payment.</span> </td>
                    </tr>
                </table>
            </div>

        </td>
    </tr>

    <tr>
        <td>
            <div>
                <table width="100%" border="0" cellspacing="0" cellpadding="5"
                    style="color:#5b4d4b;font-size: 13px;line-height: 15px;line-height: 25px;">
                    <thead>
                        <tr style="background-color: #275770;height: 40px;color: #FFFFFF;font-size: 18px;">
                            <th colspan="2" style="font-weight: normal;">Transaction Details</th>
                        </tr>
                    </thead>
                </table>
                {if $response.has_customized_payment_receipt==1}
                    {if $response.custom_fields}
                        {foreach from=$response.custom_fields item=val}
                            <table width="100%" border="0" cellspacing="0" cellpadding="5"
                                style="color:#5b4d4b;font-size: 13px;line-height: 15px;line-height: 25px;">
                                <tr>
                                    <td style="border: 1px solid #e2e2e2;border-top: 0px; width: 40%;">{$val@key}</td>
                                    <td style="border: 1px solid #e2e2e2;border-top: 0px;border-left: 0px;"> {$val} &nbsp;</td>
                                </tr>
                            </table>
                        {/foreach}
                    {/if}
                {else}
                    <table width="100%" border="0" cellspacing="0" cellpadding="5"
                        style="color:#5b4d4b;font-size: 13px;line-height: 15px;line-height: 25px;">
                        <tr>
                            <td style="border: 1px solid #e2e2e2;border-top: 0px; width: 40%;">Customer code</td>
                            <td style="border: 1px solid #e2e2e2;border-top: 0px;border-left: 0px;">
                                {$response.customer_code}&nbsp;</td>
                        </tr>
                    </table>
                    <table width="100%" border="0" cellspacing="0" cellpadding="5"
                        style="color:#5b4d4b;font-size: 13px;line-height: 15px;line-height: 25px;">
                        <tr>
                            <td style="border: 1px solid #e2e2e2;border-top: 0px; width: 40%;">Patron Name</td>
                            <td style="border: 1px solid #e2e2e2;border-top: 0px;border-left: 0px;"> {$response.patron_name}
                                &nbsp;</td>
                        </tr>
                    </table>

                    <table width="100%" border="0" cellspacing="0" cellpadding="5"
                        style="color:#5b4d4b;font-size: 13px;line-height: 15px;line-height: 25px;">
                        <tr>
                            <td style="border: 1px solid #e2e2e2;border-top: 0px; width: 40%;">Patron Email ID</td>
                            <td style="border: 1px solid #e2e2e2;border-top: 0px;border-left: 0px;">
                                {$response.patron_email} &nbsp;</td>
                        </tr>
                    </table>

                    <table width="100%" border="0" cellspacing="0" cellpadding="5"
                        style="color:#5b4d4b;font-size: 13px;line-height: 15px;line-height: 25px;">
                        <tr>
                            <td style="border: 1px solid #e2e2e2;border-top: 0px; width: 40%;">Payment Towards</td>
                            <td style="border: 1px solid #e2e2e2;border-top: 0px;border-left: 0px;">
                                {$response.merchant_name} {if $response.main_company_name!=''}
                                    (An official franchisee of {$response.main_company_name})
                                {/if}&nbsp;</td>
                        </tr>
                    </table>
                    {if $response.invoice_number!=''}
                        <table width="100%" border="0" cellspacing="0" cellpadding="5"
                            style="color:#5b4d4b;font-size: 13px;line-height: 15px;line-height: 25px;">
                            <tr>
                                <td style="border: 1px solid #e2e2e2;border-top: 0px; width: 40%;">Invoice Number</td>
                                <td style="border: 1px solid #e2e2e2;border-top: 0px;border-left: 0px;">
                                    {$response.invoice_number} &nbsp;</td>
                            </tr>
                        </table>
                    {/if}

                    <table width="100%" border="0" cellspacing="0" cellpadding="5"
                        style="color:#5b4d4b;font-size: 13px;line-height: 15px;line-height: 25px;">
                        <tr>
                            <td style="border: 1px solid #e2e2e2;border-top: 0px; width: 40%;">Transaction Ref Number</td>
                            <td style="border: 1px solid #e2e2e2;border-top: 0px;border-left: 0px;">
                                {$response.transaction_id} &nbsp;</td>
                        </tr>
                    </table>

                    {if $response.type==1}
                        <table width="100%" border="0" cellspacing="0" cellpadding="5"
                            style="color:#5b4d4b;font-size: 13px;line-height: 15px;line-height: 25px;">
                            <tr>
                                <td style="border: 1px solid #e2e2e2;border-top: 0px; width: 40%;">Bank Ref Number</td>
                                <td style="border: 1px solid #e2e2e2;border-top: 0px;border-left: 0px;">
                                    {$response.transaction_no} &nbsp;</td>
                            </tr>
                        </table>
                        <table width="100%" border="0" cellspacing="0" cellpadding="5"
                            style="color:#5b4d4b;font-size: 13px;line-height: 15px;line-height: 25px;">
                            <tr>
                                <td style="border: 1px solid #e2e2e2;border-top: 0px; width: 40%;">Bank Name</td>
                                <td style="border: 1px solid #e2e2e2;border-top: 0px;border-left: 0px;">{$response.bank_name}
                                    &nbsp;</td>
                            </tr>
                        </table>
                    {else if $response.type==2}
                        <table width="100%" border="0" cellspacing="0" cellpadding="5"
                            style="color:#5b4d4b;font-size: 13px;line-height: 15px;line-height: 25px;">
                            <tr>
                                <td style="border: 1px solid #e2e2e2;border-top: 0px; width: 40%;">Cheque Number</td>
                                <td style="border: 1px solid #e2e2e2;border-top: 0px;border-left: 0px;"> {$response.cheque_no}
                                    &nbsp;</td>
                            </tr>
                        </table>
                        {if $response.cheque_status!=''}
                            <table width="100%" border="0" cellspacing="0" cellpadding="5"
                                style="color:#5b4d4b;font-size: 13px;line-height: 15px;line-height: 25px;">
                                <tr>
                                    <td style="border: 1px solid #e2e2e2;border-top: 0px; width: 40%;">Cheque Status</td>
                                    <td style="border: 1px solid #e2e2e2;border-top: 0px;border-left: 0px;">
                                        {$response.cheque_status} &nbsp;</td>
                                </tr>
                            </table>
                        {/if}
                        <table width="100%" border="0" cellspacing="0" cellpadding="5"
                            style="color:#5b4d4b;font-size: 13px;line-height: 15px;line-height: 25px;">
                            <tr>
                                <td style="border: 1px solid #e2e2e2;border-top: 0px; width: 40%;">Bank Name</td>
                                <td style="border: 1px solid #e2e2e2;border-top: 0px;border-left: 0px;">{$response.bank_name}
                                    &nbsp;</td>
                            </tr>
                        </table>
                    {else if $response.type==3}
                        {if $response.cash_paid_to!=''}
                            <table width="100%" border="0" cellspacing="0" cellpadding="5"
                                style="color:#5b4d4b;font-size: 13px;line-height: 15px;line-height: 25px;">
                                <tr>
                                    <td style="border: 1px solid #e2e2e2;border-top: 0px; width: 40%;">Cash Paid To</td>
                                    <td style="border: 1px solid #e2e2e2;border-top: 0px;border-left: 0px;">{$response.cash_paid_to}
                                        &nbsp;</td>
                                </tr>
                            </table>
                        {/if}
                    {/if}

                    <table width="100%" border="0" cellspacing="0" cellpadding="5"
                        style="color:#5b4d4b;font-size: 13px;line-height: 15px;line-height: 25px;">
                        <tr>
                            <td style="border: 1px solid #e2e2e2;border-top: 0px; width: 40%;">Payment Date &amp; Time</td>
                            <td style="border: 1px solid #e2e2e2;border-top: 0px;border-left: 0px;">{$response.date} &nbsp;
                            </td>
                        </tr>
                    </table>

                    <table width="100%" border="0" cellspacing="0" cellpadding="5"
                        style="color:#5b4d4b;font-size: 13px;line-height: 15px;line-height: 25px;">
                        <tr>
                            <td style="border: 1px solid #e2e2e2;border-top: 0px; width: 40%;">Payment Amount (Rs)</td>
                            <td style="border: 1px solid #e2e2e2;border-top: 0px;border-left: 0px;">{$response.amount}/-
                                &nbsp;</td>
                        </tr>
                    </table>
                    {if $response.deduct_amount>0}
                        <table width="100%" border="0" cellspacing="0" cellpadding="5"
                            style="color:#5b4d4b;font-size: 13px;line-height: 15px;line-height: 25px;">
                            <tr>
                                <td style="border: 1px solid #e2e2e2;border-top: 0px; width: 40%;">Deduct
                                    {$response.deduct_text}</td>
                                <td style="border: 1px solid #e2e2e2;border-top: 0px;border-left: 0px;">
                                    {$response.deduct_amount}/- &nbsp;</td>
                            </tr>
                        </table>
                    {/if}
                    {if $coupon_code!=''}
                        <table width="100%" border="0" cellspacing="0" cellpadding="5"
                            style="color:#5b4d4b;font-size: 13px;line-height: 15px;line-height: 25px;">
                            <tr>
                                <td style="border: 1px solid #e2e2e2;border-top: 0px; width: 40%;">Coupon code applied</td>
                                <td style="border: 1px solid #e2e2e2;border-top: 0px;border-left: 0px;"> {$coupon_code} &nbsp;
                                </td>
                            </tr>
                        </table>
                    {/if}
                    {if $response.discount>0}
                        <table width="100%" border="0" cellspacing="0" cellpadding="5"
                            style="color:#5b4d4b;font-size: 13px;line-height: 15px;line-height: 25px;">
                            <tr>
                                <td style="border: 1px solid #e2e2e2;border-top: 0px; width: 40%;">Coupon Discount</td>
                                <td style="border: 1px solid #e2e2e2;border-top: 0px;border-left: 0px;">{$response.discount}/-
                                    &nbsp;</td>
                            </tr>
                        </table>
                    {/if}
                    {if $response.narrative!=''}
                        <table width="100%" border="0" cellspacing="0" cellpadding="5"
                            style="color:#5b4d4b;font-size: 13px;line-height: 15px;line-height: 25px;">
                            <tr>
                                <td style="border: 1px solid #e2e2e2;border-top: 0px; width: 40%;">Description</td>
                                <td style="border: 1px solid #e2e2e2;border-top: 0px;border-left: 0px;">{$response.narrative}
                                    &nbsp;</td>
                            </tr>
                        </table>
                    {/if}

                    <table width="100%" border="0" cellspacing="0" cellpadding="5"
                        style="color:#5b4d4b;font-size: 13px;line-height: 15px;line-height: 25px;">
                        <tr>
                            <td style="border: 1px solid #e2e2e2;border-top: 0px; width: 40%;">Mode of Payment</td>
                            <td style="border: 1px solid #e2e2e2;border-top: 0px;border-left: 0px;">{$response.payment_mode}
                                &nbsp;</td>
                        </tr>
                    </table>
                {/if}
            </div>
        </td>
    </tr>
    {if !empty($attendee_details)}
        <tr>
            <td>
                <div>
                    <table width="100%" border="0" cellspacing="0" cellpadding="5"
                        style="color:#5b4d4b;font-size: 13px;line-height: 15px;">
                        <thead>
                            <tr style="background-color: #275770;height: 30px;color: #FFFFFF;font-size: 18px;">
                                <th colspan="2" style="font-weight: normal;">Booking Details</th>
                            </tr>
                        </thead>
                    </table>

                    <table width="100%" border="0" cellspacing="0" cellpadding="5"
                        style="color:#5b4d4b;font-size: 13px;line-height: 20px;">
                        <tr>
                            {$package_name=$attendee_details.0.package_id}
                            <td style="border: 1px solid #e2e2e2;border-top: 0px;">Name : {$attendee_details.0.event_name}
                                <br>Date : {$attendee_details.0.start_date}
                                {if $attendee_details.0.venue!=''}
                                    <br> Venue: {$attendee_details.0.venue}
                                {/if}
                                <br>Booking units : {$attendee_details.0.total_qty}
                            </td>
                        </tr>

                    </table>

                    <table width="100%" border="0" cellspacing="0" cellpadding="5"
                        style="color:#5b4d4b;font-size: 13px;line-height: 15px;line-height: 15px;">
                        <tr>
                            <td style="border: 1px solid #e2e2e2;border-top: 0px;width:10%;"><b>#</b></td>
                            <td style="border: 1px solid #e2e2e2;border-top: 0px;width:75%;"><b>Details</b></td>
                            <td style="border: 1px solid #e2e2e2;border-top: 0px;width:15%;"><b>Quantity</b></td>
                        </tr>
                    </table>
                    <table width="100%" border="0" cellspacing="0" cellpadding="5"
                        style="color:#5b4d4b;font-size: 13px;line-height: 15px;line-height: 15px;">
                        <tr>
                            <td style="border: 1px solid #e2e2e2;border-top: 0px;width:10%;">1</td>
                            <td style="border: 1px solid #e2e2e2;border-top: 0px;width:75%;">
                                {$attendee_details.0.package_name}</td>
                            <td style="border: 1px solid #e2e2e2;border-top: 0px;width:15%;">{$attendee_details.0.total_qty}
                            </td>
                        </tr>
                    </table>

                    <table width="100%" border="0" cellspacing="0" cellpadding="5"
                        style="color:#5b4d4b;font-size: 13px;line-height: 15px;">
                        {assign var=id value=1}
                        {foreach from=$attendee_details item=v}
                            {if $v.package_id!=$package_name}
                                {$package_name=$v.package_id}
                                {$id=$id+1}
                            </table>
                            <table width="100%" border="0" cellspacing="0" cellpadding="5"
                                style="color:#5b4d4b;font-size: 13px;line-height: 15px;">
                                <tr>
                                    <td style="border: 1px solid #e2e2e2;border-top: 0px;width:10%;">{$id}</td>
                                    <td style="border: 1px solid #e2e2e2;border-top: 0px;width:75%;">{$v.package_name}</td>
                                    <td style="border: 1px solid #e2e2e2;border-top: 0px;width:15%;">{$v.total_qty}</td>
                                </tr>
                            </table>
                            <table width="100%" border="0" cellspacing="0" cellpadding="5"
                                style="color:#5b4d4b;font-size: 13px;line-height: 15px;">
                            {/if}
                            {if $attendee_details.0.capture_details==1}
                                <tr>
                                    <td style="border: 1px solid #e2e2e2;border-top: 0px;width:50%;">Name - {$v.name}</td>
                                    {if $attendee_details.0.mobile_capture==1}
                                        <td style="border: 1px solid #e2e2e2;border-top: 0px;border-left: 0px;width:30%;">Mob. -
                                            {$v.mobile} &nbsp;</td>
                                    {/if}

                                    {if $attendee_details.0.age_capture==1}
                                        <td style="border: 1px solid #e2e2e2;border-top: 0px;border-left: 0px;width:20%;">Age -
                                            {if $v.age>0} {$v.age}{/if} &nbsp;</td>
                                    {/if}
                                </tr>
                            {/if}
                        {/foreach}
                    </table>
                </div>
            </td>
        </tr>
    {/if}
    {if !empty($booking_details)}
        <tr>
            <td>
                <div>
                    <table width="100%" border="0" cellspacing="0" cellpadding="5"
                        style="color:#5b4d4b;font-size: 13px;line-height: 15px;">
                        <thead>
                            <tr style="background-color: #275770;height: 30px;color: #FFFFFF;font-size: 18px;">
                                <th colspan="2" style="font-weight: normal;">Booking Details</th>
                            </tr>
                        </thead>
                    </table>

                    <table width="100%" border="0" cellspacing="0" cellpadding="5"
                        style="color:#5b4d4b;font-size: 13px;line-height: 20px;">
                        <tr>
                            <td style="border: 1px solid #e2e2e2;border-top: 0px;">Name : {$booking_details.0.category_name}
                                {$booking_details.0.calendar_title}
                                <br>Date : {$booking_details.0.calendar_date}
                            </td>
                        </tr>

                    </table>


                    <table width="100%" border="0" cellspacing="0" cellpadding="5"
                        style="color:#5b4d4b;font-size: 13px;line-height: 15px;line-height: 15px;">
                        <tr>
                            <td style="border: 1px solid #e2e2e2;border-top: 0px;width:10%;"><b>#</b></td>
                            <td style="border: 1px solid #e2e2e2;border-top: 0px;width:30%;"><b>Details</b></td>
                            <td style="border: 1px solid #e2e2e2;border-top: 0px;width:30%;"><b>Package</b></td>
                            <td style="border: 1px solid #e2e2e2;border-top: 0px;width:15%;"><b>Qty</b></td>
                            <td style="border: 1px solid #e2e2e2;border-top: 0px;width:15%;"><b>Price</b></td>
                        </tr>
                        {$int=0}
                        {foreach from=$booking_details item=v}
                            {$int=$int+1}
                            <tr>
                                <td style="border: 1px solid #e2e2e2;border-top: 0px;width:10%;">{$int}</td>
                                <td style="border: 1px solid #e2e2e2;border-top: 0px;width:30%;">{$v.slot}</td>
                                <td style="border: 1px solid #e2e2e2;border-top: 0px;width:30%;">{$v.package_name} -
                                    {$v.slot_title}</td>
                                <td style="border: 1px solid #e2e2e2;border-top: 0px;width:15%;">{$v.qty}</td>
                                <td style="border: 1px solid #e2e2e2;border-top: 0px;width:15%;">{$v.amount}</td>
                            </tr>
                        {/foreach}
                    </table>


                </div>
            </td>
        </tr>
    {/if}
    {if !empty($c_c_detail)}
        <tr>
            <td>
                <div>

                    <table width="100%" border="0" cellspacing="0" cellpadding="5"
                        style="color:#5b4d4b;font-size: 13px;line-height: 15px;line-height: 15px;">
                        {$int=0}
                        {foreach from=$c_c_detail item=v}
                            <tr>
                                <td style="border: 1px solid #e2e2e2;border-top: 0px;width:40%;"><b>{$v.column_name}</b></td>
                                <td style="border: 1px solid #e2e2e2;border-top: 0px;width:60%;">{$v.value}</td>
                            </tr>
                        {/foreach}
                    </table>
                    <br>
                    <span style="font-size:13px;">For any queries related to cards please email
                        aniruddha@puneeatouts.in</span>

                </div>
            </td>
        </tr>
    {/if}
    <tr>
        <td style="color:#5b4d4b; line-height:30px; border-top:1px solid #e2e2e2; text-align:right; font-size:13px;">

            <span style="float: left;">&copy; {$current_year} OPUS Net Pvt. Handmade in Pune.</span>
            <span style="color: #275770;font-size: 15px;font-weight: 400;margin-right: 10px;">powered by</span><img
                src="https://www.swipez.in/images/logo.png" style="float:right; clear:right;" width="auto" height="37"
                class="img1" />

        </td>
    </tr>

</table>