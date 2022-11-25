<html>

<body>
    <div style="margin:0 auto; font-family:Verdana, Arial;width: 800px; border: 1px solid grey;padding: 5px;">
        <table autosize="1" width="100%" border="0" style="margin-bottom: 5px;color: #5b4d4b;font-size: 13px;"
            cellspacing="0" cellpadding="5">
            <tr>
                <td width="200" align="center" valign="top">
                    {if {$image_path}!=''}
                        <img src="/uploads/images/logos/{$image_path}" style="max-width:160px;max-height:100px;" />
                    {/if}
                </td>
                <td width="310" style="">
                    <span style="font-size:22px; color:#6e605d;">{$company_name}</span> <br/>
                    {if $info.main_company_name!=''}
                        <span style="font-size:12px; display:block; margin-top:5px; margin-bottom:1px;line-height: 16px;">
                            (An
                            official franchisee of {$info.main_company_name})</span><br/>
                    {/if}
                    <span
                        style="font-size:13px; display:block; margin-top:5px; margin-bottom:1px;line-height: 16px;">{$merchant_address}
                    </span>
                    {foreach from=$main_header item=v}
                        {if $v.column_name!='Company name' && $v.value!='' && $v.column_name!='Merchant address'}
                            <br/><span class="muted"
                                style="font-size:13px; display:block; margin-top:1px; margin-bottom:1px;line-height: 16px;">
                                {{$v.column_name|replace:'Merchant ':''}|ucfirst}: {$v.value}
                            </span>
                        {/if}
                    {/foreach}
                </td>
                <td width="100" align="center" valign="top">
                </td>
            </tr>
        </table>
        <table autosize="1"
            style="text-align: center;background-color: #E5E5E5 ;font-family:Verdana;border-top: 1px solid grey;border-bottom: 1px solid grey;font-size:18px;width: 100%;"
            border="0" cellspacing="0" cellpadding="5">
            <tr style="border-spacing: 0px;">
                <td style="">

                    {if $info.invoice_type==2}
                        {$plugin.invoice_title}
                    {else}
                        {if !empty($tax)}TAX {/if}INVOICE
                    {/if}

                </td>
            </tr>
        </table>
        <table autosize="1" border="0" cellspacing="0" cellpadding="5"
            style="font-family:Verdana;font-size:13px;line-height: 15px;width:100%;margin-bottom: 5px;">
            <tr>
                <td style="font-size:13px;  border-bottom:1px #cbcbcb;border-right: 1px solid #cbcbcb; width:50%;">
                    
                        <table autosize="1" width="100%" border="0" cellspacing="0" cellpadding="5"
                            style="color:#5b4d4b;font-size:13px;line-height: 20px;">
                            {foreach from=$customer_breckup item=v}
                                {if $v.value!=''}
                                    <tr>
                                        <td width="35%"
                                            style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;min-width:140px;">
                                            <b>{$v.column_name}</b>
                                        </td>
                                        <td style="border-bottom: 1px solid #cbcbcb;">{$v.value}</td>
                                    </tr>
                                {/if}
                            {/foreach}
                            </tbody>
                        </table>
                   
                </td>
                <td style="font-family:Verdana;font-size:13px;border-bottom:1px #cbcbcb;border-left:1px solid grey;width:50%;">
                        <table autosize="1" width="100%" border="0" cellspacing="0" cellpadding="5"
                            style="color:#5b4d4b;font-size:13px;line-height: 20px;">
                            {$last_payment=NULL}
                            {$adjustment=NULL}
                            {$adjustment_col="Adjustment"}
                            {$previous_due_col="Previous due"}
                            {foreach from=$header item=v}
                                {if $v.position=="R" && $v.value!="" && $v.function_id!=4}
                                    {if $v.function_id==11}
                                        {$last_payment=$v.value}
                                    {else if $v.function_id==12}
                                        {$adjustment=$v.value}
                                        {$adjustment_col=$v.column_name}
                                    {else if $v.function_id==4}
                                        {$previous_due_col=$v.column_name}
                                    {else}
                                        {if {$v.value|substr:0:7}=='http://' || {$v.value|substr:0:8}=='https://'}

                                            <tr>
                                                <td style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;">
                                                    <b>{$v.column_name}</b>
                                                </td>
                                                <td style="border-bottom: 1px solid #cbcbcb;"><a target="_BlANK"
                                                        href="{$v.value}">{$v.column_name}</a></td>
                                            </tr>

                                        {else}
                                            <tr>
                                                <td style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;">
                                                    <b>{$v.column_name}</b>
                                                </td>
                                                <td style="border-bottom: 1px solid #cbcbcb;">{$v.value}</td>
                                            </tr>
                                        {/if}
                                    {/if}
                                {/if}
                            {/foreach}
                        </table>
                    
                </td>
            </tr>
        </table>

        <table autosize="1" width="100%" border="0" cellspacing="0" cellpadding="5"
            style="font-family:Verdana;border: 1px solid #cbcbcb;color:#5b4d4b;font-size:13px;line-height: 22px;text-align:center;border-bottom: 0;">
            <thead>
                <tr style="background-color:#E5E5E5;">
                    {foreach from=$particular_column item=v}
                        <th style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;">
                            <b>{$v}</b>
                        </th>
                    {/foreach}
                </tr>
            </thead>
            <tbody>
                {$total=0}
                {$taxtotal=0}
                {$int=1}
                {foreach from=$particular item=dp}
                    <tr>
                        {$colspan=1}
                        {foreach from=$particular_column key=k item=v}
                            {if $k=='sr_no'}
                                <td width="5%"
                                    style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb; text-align: center;max-width:10px;">
                                    {$int}
                                    {$colspan=$colspan+1}
                                </td>
                            {else}
                                {if $k!='narrative'}
                                    {$colspan=$colspan+1}
                                {else}
                                    {$narrative=1}
                                {/if}
                                <td style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;text-align: center;">
                                    {$dp.{$k}}
                                </td>
                            {/if}
                        {/foreach}
                    </tr>
                    {$int=$int+1}
                {/foreach}
                {$colspan=$colspan-2}
            </tbody>
            <tfoot>
                <tr>
                    <td style="border-bottom: 1px solid #e2e2e2;text-align: left;" colspan="{$colspan}">
                        <b>{$info.particular_total}</b>
                    </td>
                    <td
                        style="border-bottom: 1px solid #e2e2e2;border-left: 1px solid #e2e2e2;border-right: 1px solid #e2e2e2;border-bottom: 1px solid #e2e2e2;">
                        <b> {$info.basic_amount|number_format:2:".":","}</b>
                    </td>
                    {if $narrative==1}
                        <td style="border-bottom: 1px solid #e2e2e2;"> </td>
                    {/if}
                </tr>
            </tfoot>
        </table>

        
        {if !empty($tax)}
            
            <table autosize="1" width="100%" border="0" cellspacing="0" cellpadding="5"
            style="font-family:Verdana;border:1px solid #cbcbcb;color:#5b4d4b;font-size:13px;line-height:22px;text-align: center;border-bottom: 0;margin-top:20px">
                <thead>
                    <tr style="background-color:#E5E5E5;">
                        <th style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;"><b>Tax name</b></th>
                        <th style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;"><b>Percentage</b></th>
                        <th style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;"><b>Applicable</b></th>
                        <th style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;"><b>Amount</b></th>

                    </tr>
                </thead>
                <tbody>
                    {$total=0}
                    {foreach from=$tax item=v}
                        {if $v.tax_name!='' || $v.tax_amount>0}
                            <tr>
                                <td style="border-bottom: 1px solid #e2e2e2;border-right: 1px solid #e2e2e2;">
                                    {$v.tax_name}
                                </td>
                                <td style="border-bottom: 1px solid #e2e2e2;border-right: 1px solid #e2e2e2;">
                                    {$v.tax_percent}%
                                </td>
                                <td style="border-bottom: 1px solid #e2e2e2;border-right: 1px solid #e2e2e2;">
                                    {$v.applicable}
                                </td>
                                <td style="border-bottom: 1px solid #e2e2e2;">
                                    {$v.tax_amount}
                                    {$total={$total}+{$v.tax_amount}}
                                </td>
                            </tr>
                        {/if}
                    {/foreach}
                    <tr style="text-align:center;">
                        <td style="border-bottom: 1px solid #e2e2e2;text-align: left;border-right: 1px solid #e2e2e2;"
                            colspan="3"><b>{$info.tax_total}</b></td>

                        <td
                            style="border-bottom:1px solid #e2e2e2;width:110px;border-left:1px solid #e2e2e2;border-bottom: 1px solid #e2e2e2;">
                            <b>{$total|number_format:2:".":","}</b>
                        </td>
                    </tr>
                </tbody>
            </table>
        {/if}
        <table autosize="1" style="margin:0 auto; font-family:Verdana, Arial;width:100%;" align="center" width="100%"
            border="0" cellspacing="0" cellpadding="10">
            <tr>
                <td style="color:#5b4d4b; line-height:22px;font-size:13px;width:100%;">
                    <table width="100%" border="0" cellspacing="0" cellpadding="5"
                        style="color:#5b4d4b;font-size: 13px;line-height: 15px;">
                        <tr>
                            <td style="line-height:22px;text-align:left;">
                                <span><b>Amount (in words) :</b> {$money_words}</span>
                            </td>
                            <td
                                style="color:#5b4d4b; line-height:22px;text-align:right; font-size:13px;">
                                {if $paid_amount>0}
                                    <span style="float: right;padding-right: 15px;"><b>Paid amount {$info.currency_icon}
                                                {($paid_amount)|number_format:2:".":","}</b>
                                    </span>
                                    <br/>
                                {/if}
                                {if $advance>0}
                                    <span style="float: right;padding-right: 15px;">Advance received :
                                        {$info.currency_icon}{$advance|number_format:2:".":","}</span>
                                    <br/>
                                    <span style="float: right;padding-right: 15px;"><b>Balance : {$info.currency_icon}
                                            {$absolute_cost|number_format:2:".":","} </b></span><br/>
                                {else}
                                    {if $payment_request_status!=1 && $payment_request_status!=2 && $payment_request_status!=3}
                                        <span style="float: right;padding-right: 15px;"><b>Grand Total : {$info.currency_icon}
                                                {$absolute_cost|number_format:2:".":","} </b></span>
                                    {/if}
                                {/if}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        
        <table autosize="1" style="margin:0 auto; font-family:Verdana, Arial;width: 800px;" align="center" width="800"
            border="0" cellspacing="0" cellpadding="10">
            <tr>
                <td style="color:#5b4d4b; line-height:30px;float: left; text-align:left; font-size:20px;">
                    <table width="100%" border="0" cellspacing="0" cellpadding="5"
                        style="color:#5b4d4b;font-size: 13px;line-height: 15px;">
                        <tr>
                            <td>
                                {if $info.narrative!=''}
                                    <p><b>Narrative</b></p>
                                    <p>{$info.narrative}</p>
                                {/if}
                                {if $tnc!=''}
                                    <b>Terms & Conditions</b><br/>
                                    <p> {$tnc}</p>
                                {/if}
                                {if $plugin.has_signature!=1}
                                    <hr>
                                    <p>* This is a system generated invoice. No signature required.</p>
                                {/if}
                            </td>
                    </table>
                </td>
            </tr>
            {if $plugin.has_signature==1}
                {if isset($signature)}
                    <tr>
                        <td colspan="2" style="text-align:{$signature.align};  ">
                            {if $signature.type=='font'}
                                <span
                                    style="font-family: '{$signature.font_name}',cursive;font-size: {$signature.font_size}px;float:{$signature.align};">{$signature.name}</span>
                            {else}
                                <img src="{$signature.signature_file}" style="max-height: 100px;float:{$signature.align};">
                            {/if}
                        </td>
                    </tr>
                {/if}

            {/if}
        </table>
        {if $is_merchant==1 && $info.has_acknowledgement==1}
            <tr>
                <td colspan="2" style="border-bottom:1px solid #e2e2e2;">
                    <table width="800" style="border:1px solid #e2e2e2;color:#5b4d4b;"
                        class="table table-bordered table-condensed">
                        <tbody>
                            <tr>
                                {if $info.invoice_number!=''}
                                    <th width="100" style="border-bottom:1px solid #e2e2e2;border-right:1px solid #e2e2e2;">
                                        Invoice
                                        #</th>
                                {/if}
                                <th width="100" style="border-bottom:1px solid #e2e2e2;border-right:1px solid #e2e2e2;">
                                    Customer
                                    Code</th>
                                <th width="100" style="border-bottom:1px solid #e2e2e2;border-right:1px solid #e2e2e2;">
                                    Cheque
                                    No</th>
                                <th width="140" style="border-bottom:1px solid #e2e2e2;border-right:1px solid #e2e2e2;">
                                    Bank/Branch</th>
                                <th width="100" style="border-bottom:1px solid #e2e2e2;border-right:1px solid #e2e2e2;">
                                    Amount
                                </th>
                                <th width="140" style="border-bottom:1px solid #e2e2e2;border-right:1px solid #e2e2e2;">Cash
                                    Received</th>
                                <th width="{if $info.invoice_number!=''}120{else}180{/if}"
                                    style="border-bottom:1px solid #e2e2e2;">Signature & Stamp</th>
                            </tr>
                            <tr>
                                {if $info.invoice_number!=''}
                                    <td style="border-right:1px solid #e2e2e2; text-align: center;" align="center">
                                        {$info.invoice_number}</td>
                                {/if}
                                <td style="border-right:1px solid #e2e2e2; text-align: center;" align="center">
                                    {$info.customer_code}</td>
                                <td style="border-right:1px solid #e2e2e2;">{$transaction.cheque_no}</td>
                                <td style="border-right:1px solid #e2e2e2;">{$transaction.bank_name}</td>
                                <td style="border-right:1px solid #e2e2e2; text-align: center;" align="center">
                                    {$info.grand_total}</td>
                                <td style="border-right:1px solid #e2e2e2;">{$transaction.amount}</td>
                                <td style="height: 50px;"></td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        {/if}



    </div>
</body>

</html>