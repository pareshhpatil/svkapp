<table style="margin:0 auto; font-family:Verdana, Arial;border: 1px solid #e2e2e2;width: 600px;margin-bottom: 5px;"
    align="center" width="600" border="0" cellspacing="0" cellpadding="10">
    <tr>
        <td style="color:#31708f; line-height:25px;   text-align:center; font-size:12px;background-color: #d9edf7;">
            <span style="text-align: center;">Please ignore this email if you have already paid this invoice.</span>
        </td>
    </tr>
</table>
<table style="margin:0 auto; font-family:Verdana, Arial;width: 600px; border: 1px solid black;" align="center"
    width="600" border="0" cellspacing="0" cellpadding="10">
    <tr>
        <td style="font-size:15px; line-height:30px;">
            <table width="100%" border="0" cellspacing="0" cellpadding="5">
                <tr>
                    <td width="160" align="center" valign="top">
                        {if {$image_path}!=''}
                            <img src="{$server_path}/uploads/images/logos/{$image_path}" width="200"
                                style="max-width:200px;max-height:100px;" />
                        {else}
                            <img src="https://www.swipez.in/images/nologo.gif" width="200"
                                style="max-width:200px;max-height:100px;" />
                        {/if}
                    </td>
                    <td width="330">
                        <span style="font-size:20px; color:#6e605d;">{$company_name}</span>
                        {if $info.main_company_name!=''}
                            <span style="font-size:11px; display:block;  margin-bottom:1px;"> (An official franchisee of
                                {$info.main_company_name})</span>
                        {/if}
                        {$gstontop=0}
                        {$panontop=0}
                        {$tanontop=0}
                        {foreach from=$main_header item=v}
                            {if $v.column_name!='Company name'}
                                <span style="font-size:12px; display:block;  margin-bottom:1px;">
                                    {{$v.column_name|replace:'Merchant ':''}|ucfirst}: {$v.value}</span>
                            {/if}
                            {if $v.column_name=='GSTIN Number'}
                                {$gstontop=1}
                            {/if}
                            {if $v.column_name=='Company pan'}
                                {$panontop=1}
                            {/if}
                            {if $v.column_name=='Company TAN'}
                                {$tanontop=1}
                            {/if}
                        {/foreach}
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr style="border-spacing: 0px;">
        <td style="text-align: center;background-color: #E5E5E5 ;font-size:18px;">

            {if $info.invoice_type==2}
                <b>{$info.invoice_title}</b>
            {else}
                <b>{if !empty($tax)}TAX {/if}INVOICE</b>
            {/if}

        </td>
    </tr>
    <tr>
        <td style="font-size:12px; line-height:20px;">
            <table>
                <tr>
                    <td>
                        <table width="290" border="0" cellspacing="0" cellpadding="5"
                            style="color:#5b4d4b;font-size: 13px;line-height: 14px;">
                            {foreach from=$customer_breckup item=v}
                                {if $v.value!=''}
                                    <tr style="line-height: 17px;">
                                        <td> <b>{$v.column_name}</b> : {$v.value}</td>
                                    </tr>
                                {/if}
                            {/foreach}
                            {if $landline!=""}
                                <tr style="line-height: 17px;">
                                    <td> <b>Landline no </b> : {$landline}</td>
                                </tr>
                            {/if}
                        </table>
                    </td>
                    <td>

                        <table width="290" border="0" cellspacing="0" cellpadding="5"
                            style="color:#5b4d4b;font-size: 13px;line-height: 15px;">
                            {$last_payment=NULL}
                            {$adjustment=NULL}
                            {foreach from=$header item=v}
                                {if $v.position=="R" && $v.value!="" && $v.function_id!=4}
                                    {if $v.function_id==11}
                                        {$last_payment=$v.value}
                                    {else if $v.function_id==12}
                                        {$adjustment=$v.value}
                                    {else}
                                        {if {$v.value|substr:0:7}=='http://' || {$v.value|substr:0:8}=='https://'}
                                            <tr>
                                                <td style="line-height: 17px;"> <b>{$v.column_name}</b> : <a target="_BlANK"
                                                        href="{$v.value}">{$v.column_name}</a></td>
                                            </tr>
                                        {else}
                                            <tr>
                                                <td style="line-height: 17px;"> <b>{$v.column_name}</b> : {$v.value}
                                                    {if $v.datatype=='percent'} %{/if}
                                                </td>
                                            </tr>
                                        {/if}
                                    {/if}
                                {/if}
                            {/foreach}
                            <tr style="line-height: 17px;">
                                <td> <b>&nbsp;</b>&nbsp;</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

        </td>

    </tr>
    {if $info.hide_invoice_summary!=1}
        {if $last_payment==NULL && $adjustment==NULL}
            <tr>
                <td>
                    <table border="1" cellspacing="0" cellpadding="5"
                        style="border-collapse: collapse;color:#5b4d4b;font-size: 13px;line-height: 20px;width: 100%;">
                        <tr>
                            <td style="border-right: 1px solid grey;background-color:#E5E5E5;">
                                <b>Billing Summary</b>
                            </td>
                            <td style="border-right: 1px solid grey;">
                                <b> Past Due :</b> {$previous_due|number_format:2:".":","}
                            </td>
                            <td style="border-right: 1px solid grey;">
                                <b> Current Charges :</b> {($basic_total+$tax_total)|number_format:2:".":","}
                            </td>
                            <td style="background-color:#E5E5E5; ">
                                <b> Total Due :</b> {$invoice_total|number_format:2:".":","}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        {else}
            <tr>
                <td>
                    <table border="1" cellspacing="0" cellpadding="5"
                        style="border-collapse: collapse;color:#5b4d4b;font-size: 13px;line-height: 20px;width: 100%;">
                        <tr style="font-size: 12px;border-bottom:1px solid black !important;">
                            <td style="border-right:1px solid black;border-bottom:1px solid black;">Previous Balance</td>
                            <td rowspan="2" style="border-right:1px solid black;">&nbsp; - &nbsp;</td>
                            <td style="border-right:1px solid black;border-bottom:1px solid black;">Last Payments</td>
                            <td rowspan="2" style="border-right:1px solid black;">&nbsp; - &nbsp;</td>
                            <td style="border-right:1px solid black;border-bottom:1px solid black;">Adjustments</td>
                            <td rowspan="2" style="border-right:1px solid black;">&nbsp; + &nbsp;</td>
                            <td style="border-right:1px solid black;border-bottom:1px solid black;">Current Bill</td>
                            <td rowspan="2" style="border-right:1px solid black;">&nbsp; = &nbsp;</td>
                            <td style="border-bottom:1px solid black;">Total Due</td>
                        </tr>
                        <tr>
                            <td style="border-right:1px solid black;">{$previous_due|number_format:2:".":","} </td>

                            <td style="border-right:1px solid black;">{$last_payment|number_format:2:".":","}</td>
                            <td style="border-right:1px solid black;">{$adjustment|number_format:2:".":","}</td>
                            <td style="border-right:1px solid black;">{($basic_total+$tax_total)|number_format:2:".":","}</td>
                            <td>{$invoice_total|number_format:2:".":","}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        {/if}
    {/if}
    <tr>
        <td>
            <div>
                <table width="100%" border="1" cellspacing="0" cellpadding="5"
                    style="border-collapse: collapse;color:#5b4d4b;font-size: 13px;line-height: 15px;">
                    <thead>
                        <tr style="background-color: #E5E5E5 ;">
                            {foreach from=$particular_column item=v}
                                <th>
                                    {$v}
                                </th>
                            {/foreach}
                        </tr>
                    </thead>
                    <tbody>
                        {$total=0}
                        {$taxtotal=0}
                        {$int=1}
                        {$narrative=0}
                        {foreach from=$particular item=dp}
                            <tr>
                                {$colspan=1}
                                {foreach from=$particular_column key=k item=v}
                                    {if $k=='sr_no'}
                                        <td style="border-top: 0;border-bottom: 0; text-align: center;">
                                            {$int}
                                        </td>
                                    {else}
                                        {if $k!='narrative'}
                                            {$colspan=$colspan+1}
                                        {else}
                                            {$narrative=1}
                                        {/if}
                                        <td style="border-top: 0;border-bottom: 0;text-align: center;">
                                            {$dp.{$k}}
                                        </td>
                                    {/if}
                                {/foreach}
                            </tr>
                            {$int=$int+1}
                        {/foreach}
                        {$colspan=count($particular_column)-3}
                        <tr style="text-align: center;">
                            <td colspan="{$colspan}" style="border-bottom: 0;">

                            </td>
                            <td colspan="2" style="text-align: left;">
                                <b>{$lang_title.sub_total}</b>
                            </td>
                            <td style="text-align: right;" class="col-md-2 td-c">
                                <b> {$info.basic_amount|number_format:2:".":","}</b>
                            </td>
                        </tr>
                        {foreach from=$tax item=v}
                            <tr style="text-align: center;">
                                <td colspan="{$colspan}" class="col-md-8" style="border-bottom: 0;border-top: 0;">
                                </td>
                                <td style="text-align: right;" colspan="2">
                                    {$v.tax_name}
                                </td>
                                <td style="text-align: right;">
                                    {$v.tax_amount}
                                    {$taxtotal={$taxtotal}+{$v.tax_amount}}
                                </td>
                            </tr>
                        {/foreach}

                        <tr style="text-align: center;">
                            <td colspan="{$colspan+2}" style="text-align: right;"><b>Total </b></td>
                            <td style="text-align: right;"><b>
                            {$info.currency_icon} {($info.basic_amount+$tax_total)|number_format:2:".":","}</b></td>
                        </tr>
                        {if $advance>0}
                            <tr style="text-align: center;">
                                <td style="border-top:0px;" colspan="{$colspan}"></td>
                                <td style="text-align: left"><b>Advance received</b></td>
                                <td style="text-align: right;"><b>{($advance)|number_format:2:".":","}</b></td>
                            </tr>
                            <tr style="text-align: center;">
                                <td style="border-top:0px;" colspan="{$colspan}"></td>
                                <td style="text-align: left"><b>Balance</b></td>
                                <td style="text-align: right;"><b>{$info.currency_icon} {($grand_total)|number_format:2:".":","}</b></td>
                            </tr>
                        {/if}

                        <tr style="text-align: center;">
                            <td style="text-align: left;font-size: 10px;border-bottom: 0;" rowspan="3"
                                colspan="{$colspan}">
                                {if $tnc!=''}
                                    <b>Terms & Conditions</b><br>
                                    {$tnc}
                                {/if}

                            </td>
                            {if $pan!='' && $panontop==0}
                                <td style="text-align: left;">PAN NO.</td>
                                <td colspan="2" style="text-align: right;">{$pan}</td>
                            {/if}
                        </tr>
                        <tr style="text-align: center;">
                            {if $tan!='' && $tanontop==0}
                                <td style="text-align: left;">TAN NO.</td>
                                <td colspan="2" style="text-align: right;"> {$tan}</td>
                            {/if}
                        </tr>
                        <!--<tr style="text-align: center;">
                            {if $cin_no!=''}
                                <td style="text-align: left;">CIN NO.</td>
                                <td colspan="2" style="text-align: right;">{$cin_no}</td>
                            {/if}
                        </tr>-->
                        <tr style="text-align: center;">
                            {if $gst_number!='' && $gstontop==0}
                                <td style="text-align: left;">GST Number</td>
                                <td colspan="2" style="text-align: right;">{$gst_number}</td>
                            {/if}
                        </tr>
                        {if $plugin.has_signature!=1}
                            <tr style="text-align: center;">
                                <td colspan="{$colspan+3}" style="text-align: left;">
                                    <b> * Note: </b>This is a system generated invoice. No signature required.
                                </td>

                            </tr>
                        {/if}

                    </tbody>



                </table>
            </div>
        </td>
    </tr>
    {if $plugin.has_signature==1}
        {if isset($signature)}
            <tr>
                <td style="text-align:{$signature.align};  ">
                    {if $signature.type=='font'}
                        <img src="{$server_path}{$signature.font_image}" style="max-height: 100px;float:{$signature.align};">
                    {else}
                        <img src="{$server_path}{$signature.signature_file}" style="max-height: 100px;float:{$signature.align};">
                    {/if}
                </td>
            </tr>
        {/if}

    {/if}

    <tr>
        <td style="color:#5b4d4b; line-height:30px;   text-align:right; font-size:15px;">

            <span style="float: right;">Bill Total : {$info.currency_icon} {$invoice_total|number_format:2:".":","}</span>
            <br>
            {if $advance>0}
                <span style="float: right;">Advance : {$info.currency_icon}{$advance|number_format:2:".":","}</span>
                <br>
                <span style="float: right;">Balance : {$info.currency_icon} {$grand_total|number_format:2:".":","}</span>
            {else}
                <span style="float: right;">Grand Total : {$info.currency_icon} {$grand_total|number_format:2:".":","}</span>
            {/if}
        </td>
    </tr>

    {if $plugin.coupon_id>0}
        <tr id="couponapplied">
            <td style="color:#5b4d4b; line-height:30px; text-align:right; font-size:15px;background-color: #fcf8e3;">

                <span style="float: left;"> Coupon discount : <b>{if $coupon_details.type==1}
                        {$info.currency_icon}{$coupon_details.fixed_amount}/- {else}{$coupon_details.percent}%
                        {/if}</b></span>
                <span style="float: right;">Coupon available</span>
            </td>
        </tr>
    {/if}

    {if $is_surcharge!=1}
        <tr id="negative">
            <td style="">
                <div style="font-size:13px; color:#6E605D;">
                    <b> Please note: No extra charges are applicable for paying online. </b>
                </div>
            </td>
        </tr>
    {/if}


    <tr id="negative">
        <td style="font-size:15px; color:#fb735d; line-height:20px; text-align:right; ">
            <a href="{$url}" target="_blank"
                style="font-family: Arial, Helvetica, sans-serif; font-size: 13px;background-color: #18aebf;color: #FFFFFF;padding: 7px 10px 7px 10px;text-decoration: none;font-family: Open Sans,sans-serif;float:right;">
                <font face="Arial, Helvetica, sans-seri; font-size: 13px;" size="3">
                    {if $grand_total>0 && $legal_complete==1 && $info.invoice_type!=2}Pay now{else}View{/if}</font>
            </a>
            <a href="{$pdf_url}"
                style="font-family: Arial, Helvetica, sans-serif; font-size: 13px;background-color: #f99636;color: #FFFFFF;padding: 7px 10px 7px 10px;text-decoration: none;font-family: Open Sans,sans-serif;float:right;margin-right: 10px;">
                <font face="Arial, Helvetica, sans-seri; font-size: 13px;" size="3">
                    Save as PDF </font>
            </a>
        </td>
    </tr>

    {if $info.paid_user==0}                 
    <tr>
        <td
            style="color:#5b4d4b; line-height:25px; text-align:right; font-size:12px;background-color: #18aebf;color: #ffffff;">
            <span style="float: left;">If you would like to collect online payments for your business, <a
                    target="_BLANK" href="https://www.swipez.in/merchant/register">register now</a> on Swipez.</span>
        </td>
    </tr>
    {/if}
    
    <tr>
        <td style="font-size:15px; color:#fb735d; line-height:30px; ">
            <table width="100%" border="0" cellspacing="0" cellpadding="5">
                <tr>
                    <td valign="top" style="">
                        <div style="font-size:13px; color:#6E605D;">
                            If you are having trouble viewing this invoice in your email, you can use this link to view
                            the same invoice
                            {$url}
                        </div>
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        <div style="font-size:13px; color:#6E605D;">
                            If you do not recognize the merchant - {$info.company_name} OR have a query regarding this
                            request, please <a
                                href="mailto:support@swipez.in?Subject=Query regarding the Payment Request"
                                class="example5"> contact us.</a>
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td style="font-size:13px;  line-height:30px; ">
            <table width="100%" border="0" cellspacing="0" cellpadding="5">
                <tr>
                    <td valign="top" style="">
                        If you would prefer not receiving our emails, please <a target="_BLANK"
                            href="{$unsub_link}">click here</a> to unsubscribe.
                    </td>
                </tr>

            </table>
        </td>
    </tr>
</table>