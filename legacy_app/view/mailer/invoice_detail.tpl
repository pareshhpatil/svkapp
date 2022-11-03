<table style="margin:0 auto; font-family:Verdana, Arial;border: 1px solid #e2e2e2;width: 600px;margin-bottom: 5px;"
    align="center" width="600" border="0" cellspacing="0" cellpadding="10">
    <tr>
        <td
            style="color:#31708f; line-height:25px; border-bottom:1px dashed #e2e2e2; border-top:1px dashed #e2e2e2; text-align:center; font-size:12px;background-color: #d9edf7;">
            <span style="text-align: center;">Please ignore this email if you have already paid this invoice.</span>
        </td>
    </tr>
</table>
<table style="margin:0 auto; font-family:Verdana, Arial;border: 1px solid #e2e2e2;width: 600px" align="center"
    width="600" border="0" cellspacing="0" cellpadding="10">

    <tr>
        <td style="font-size:15px; line-height:25px;">
            <table width="100%" border="0" cellspacing="0" cellpadding="5" style="border-bottom:1px dashed #e2e2e2;">
                <tr>
                    <td width="200" align="center" valign="top">
                        {if {$image_path}!=''}
                            <img src="{$server_path}/uploads/images/logos/{$image_path}" width="200"
                                style="max-width:200px;max-height:100px;" />
                        {else}
                            <img src="https://www.swipez.in/images/nologo.gif" width="200"
                                style="max-width:200px;max-height:100px;" />
                        {/if}
                    </td>
                    <td width="390">
                        <span style="font-size:20px; color:#6e605d;margin-bottom:5px;">{$company_name}</span>
                        {if $info.main_company_name!=''}
                            <span style="font-size:11px; display:block;  margin-bottom:1px;"> (An official franchisee of
                                {$info.main_company_name})</span>
                        {/if}
                        {foreach from=$main_header item=v}
                            {if $v.column_name!='Company name'}
                                <span style="font-size:12px; display:block;  margin-bottom:1px;">{$v.value}</span>
                            {/if}
                        {/foreach}
                    </td>
                </tr>
            </table>
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
                            {foreach from=$header item=v}
                                {if $v.position=="R" && $v.value!=""}
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
    {if !empty($particular)}
        <tr>
            <td style="border-top:1px dashed #e2e2e2;">
                <div>
                    <table width="100%" border="0" cellspacing="0" cellpadding="5"
                        style="color:#5b4d4b;font-size: 13px;line-height: 15px;">
                        <thead>
                            <tr style="background-color: #e2e2e2;">
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
                                            <td style="border-top: 0;border-bottom: 0;text-align: center;">
                                                {$int}
                                                {$colspan=$colspan+1}
                                            </td>
                                        {else}
                                            {if $k!='narrative'}
                                                {$colspan=$colspan+1}
                                            {else}
                                                {$narrative=1}
                                            {/if}
                                            <td class="td-c" style="border-top: 0;border-bottom: 0;text-align: center;">
                                                {$dp.{$k}}
                                            </td>
                                        {/if}
                                    {/foreach}
                                </tr>
                                {$int=$int+1}
                            {/foreach}
                            {$colspan=$colspan-2}
                        </tbody>
                        <thead>
                            <tr>
                                <td colspan="{$colspan}" style="text-align: center;">
                                    <b>{$info.particular_total}</b>
                                </td>
                                <td class="td-c" style="text-align: center;">
                                    <b> {$info.basic_amount|number_format:2:".":","}</b>
                                </td>
                                {if $narrative==1}
                                    <td class="td-c" style="text-align: center;"> </td>
                                {/if}
                            </tr>
                        </thead>

                    </table>
                </div>
            </td>
        </tr>
    {/if}
    {if !empty($tax)}
        <tr>
            <td style="border-top:1px dashed #e2e2e2;">
                <div>
                    <table width="100%" border="0" cellspacing="0" cellpadding="5"
                        style="color:#5b4d4b;font-size: 13px;line-height: 15px;">
                        <thead>
                            <tr style="background-color: #e2e2e2;">
                                <th>Tax name</th>
                                <th>Tax in %</th>
                                <th>Applicable on</th>
                                <th>Amount</th>
                            </tr>
                        </thead>

                        {$total=0}

                        {foreach from=$tax item=v}
                            {if $v.tax_name!='' || $v.tax_amount>0}
                                <tr>
                                    <td width="200" style="border-bottom: 1px solid #e2e2e2;">
                                        {$v.tax_name}
                                    </td>
                                    <td width="100" style="border-bottom: 1px solid #e2e2e2;">
                                        {$v.tax_percent}%
                                    </td>
                                    <td width="100" style="border-bottom: 1px solid #e2e2e2;">
                                        {$v.applicable}
                                    </td>
                                    <td width="100" style="border-bottom: 1px solid #e2e2e2;">
                                        {$v.tax_amount}
                                        {$total={$total}+{$v.tax_amount}}
                                    </td>

                                </tr>
                            {/if}
                        {/foreach}
                        <thead>
                            <tr>
                                <th>{$info.tax_total}</th>
                                <th></th>
                                <th></th>
                                <th>{$total}</th>
                            </tr>
                        </thead>

                    </table>
                </div>
            </td>
        </tr>
    {/if}
    <tr>
        <td style="font-size:12px; line-height:20px;">
            <table>
                <tr>
                    <td>
                        <table width="390" border="0" cellspacing="0" cellpadding="5"
                            style="color:#5b4d4b;font-size: 13px;line-height: 14px;">
                            <tr style="line-height: 17px;">
                                <td>
                                    <span style="float: left;font-size:12px;line-height:15px;text-align:left;">
                                        {if $tnc!=''}
                                            <b>Terms & Conditions</b><br>
                                            {$tnc}
                                        {/if}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td>

                        <table width="190" border="0" cellspacing="0" cellpadding="5"
                            style="color:#5b4d4b;font-size: 15px;line-height: 20px;">

                            <tr style="line-height: 22px;">
                                <td>
                                    <span style="float: right;">Bill Total : {$info.currency_icon}
                                        {$invoice_total|number_format:2:".":","}</span>
                                    <br>
                                    {if $advance>0}
                                        <span style="float: right;">Advance : {$info.currency_icon}{$advance|number_format:2:".":","}</span>
                                        <br>
                                        <span style="float: right;">Balance : {$info.currency_icon}
                                            {$grand_total|number_format:2:".":","}</span>
                                    {else}
                                        <span style="float: right;">Grand Total : {$info.currency_icon}
                                            {$grand_total|number_format:2:".":","}</span>
                                    {/if}

                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

        </td>

    </tr>
    {if $plugin.coupon_id>0}
        <tr id="couponapplied">
            <td
                style="color:#5b4d4b; line-height:30px; border-bottom:1px dashed #e2e2e2; border-top:1px dashed #e2e2e2; text-align:right; font-size:15px;background-color: #fcf8e3;">

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
    {if $plugin.has_signature==1}
        <tr>
            <td style="text-align: {$signature.align};">
                {if isset($signature)}
                    {if $signature.type=='font'}
                        <img src="{$server_path}{$signature.font_image}" style="max-height: 100px;float:{$signature.align};">
                    {else}
                        <img src="{$server_path}{$signature.signature_file}" style="max-height: 100px;">
                    {/if}
                {/if}
            </td>
        </tr>
    {/if}


    <tr id="negative">
        <td
            style="font-size:15px; color:#fb735d; line-height:20px; border-bottom:1px dashed #e2e2e2;text-align:right; ">
            <a href="{$url}" target="_blank"
                style="font-family: Arial, Helvetica, sans-serif; font-size: 13px;background-color: #18aebf;color: #FFFFFF;padding: 7px 10px 7px 10px;text-decoration: none;font-family: Open Sans,sans-serif;float:right">
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
            style="color:#5b4d4b; line-height:25px; border-bottom:1px dashed #e2e2e2; border-top:1px dashed #e2e2e2; text-align:right; font-size:12px;background-color: #18aebf;color: #ffffff;">
            <span style="float: left;">If you would like to collect online payments for your business, <a
                    target="_BLANK" href="https://www.swipez.in/merchant/register">register now</a> on Swipez.</span>
        </td>
    </tr>
    {/if}
    
    <tr>
        <td style="font-size:15px; color:#fb735d; line-height:30px; ">
            <table width="100%" border="0" cellspacing="0" cellpadding="5">
                <tr>
                    <td valign="top" style="border-bottom:1px dashed #e2e2e2;">
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
        <td style="font-size:13px;line-height:30px; ">
            <table width="100%" border="0" cellspacing="0" cellpadding="5">
                <tr>
                    <td valign="top" style="border-bottom:1px dashed #e2e2e2;">
                        If you would prefer not receiving our emails, please <a target="_BLANK"
                            href="{$unsub_link}">click here</a> to unsubscribe.
                    </td>
                </tr>

            </table>
        </td>
    </tr>
</table>