<html>

<body>
    {if $properties.hide_invoice_header==1}
        <div
            style="height: {if isset($properties.invoice_header_height)}{$properties.invoice_header_height}{else}150{/if}px;">
        </div>
    {else}
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
                        <span style="font-size:22px; color:#6e605d;">{$company_name}</span> <br>
                        {if $info.main_company_name!=''}
                            <span style="font-size:12px; display:block; margin-top:5px; margin-bottom:1px;line-height: 16px;">
                                (An
                                official franchisee of {$info.main_company_name})</span><br>
                        {/if}
                        <span
                            style="font-size:13px; display:block; margin-top:5px; margin-bottom:1px;line-height: 16px;">{$merchant_address}
                        </span>
                        {$gstontop=0}
                        {$panontop=0}
                        {$tanontop=0}
                        {$cinontop=0}
                        {foreach from=$main_header item=v}
                            {if $v.column_name!='Company name' && $v.value!='' && $v.column_name!='Merchant address'}
                                <br><span class="muted"
                                    style="font-size:13px; display:block; margin-top:1px; margin-bottom:1px;line-height: 16px;">
                                    {{$v.column_name|replace:'Merchant ':''}|ucfirst}: {$v.value}
                                </span>
                                {if $v.column_name=='GSTIN Number'}
                                    {$gstontop=1}
                                {/if}
                                {if $v.column_name=='Company pan'}
                                    {$panontop=1}
                                {/if}
                                {if $v.column_name=='Company TAN'}
                                    {$tanontop=1}
                                {/if}
                                {if $v.column_name=='CIN Number'}
                                    {$cinontop=1}
                                {/if}
                            {/if}
                        {/foreach}
                    </td>
                    <td width="100" align="center" valign="top">
                    </td>
                </tr>
            </table>
        {/if}
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
            style="font-family:Verdana;font-size:13px;line-height: 15px;width: 100%;margin-bottom: 5px;">
            <tr>
                <td style="font-size:13px;  border-bottom:1px #cbcbcb;border-right: 1px solid #cbcbcb; width:380px;">
                    <div style="float:left;line-height: 10px;  margin-right:5px;">
                        <table autosize="1" width="360" border="0" cellspacing="0" cellpadding="5"
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
                    </div>
                </td>
                <td
                    style="font-family:Verdana;font-size:13px;  border-bottom:1px #cbcbcb;border-left: 1px solid grey; width:380px;">
                    <div style="float: right; padding-left: 1px;">
                        <table autosize="1" width="360" border="0" cellspacing="0" cellpadding="5"
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


                            </tbody>
                        </table>
                    </div>
                </td>
            </tr>
        </table>
        {if $info.hide_invoice_summary!=1}
            {if $last_payment==NULL && $adjustment==NULL}
                <table autosize="1" border="0" cellspacing="0" cellpadding="5"
                    style="font-family:Verdana;border: 1px solid grey;color:#5b4d4b;font-size:13px;line-height: 22px;width: 100%;margin-bottom: 5px;">
                    <tr>
                        <td style="border-right: 1px solid #cbcbcb;background-color:#E5E5E5;">
                            <b>Billing Summary</b>
                        </td>
                        <td style="border-right: 1px solid #cbcbcb;">
                            <b> Past Due :</b> {$previous_due|number_format:2:".":","}
                        </td>
                        <td style="border-right: 1px solid #cbcbcb;">
                            <b> Current Charges :</b>{($basic_total+$tax_total)|number_format:2:".":","}
                        </td>
                        <td style="background-color:#E5E5E5; ">
                            <b> Total Due :</b>{$invoice_total|number_format:2:".":","}
                        </td>
                    </tr>
                </table>

            {else}
                {if $last_payment!=NULL && $adjustment!=NULL}

                    <table autosize="1"
                        style="font-family:Verdana;width: 100%;text-align: center;margin: 0px !important;border:1px solid grey;margin-bottom: 5px;">
                        <tr style="font-size: 12px;border-bottom:1px solid grey !important;">
                            <td style="border-right:1px solid grey;border-bottom:1px solid grey;">Previous Balance</td>
                            <td rowspan="2" style="border-right:1px solid grey;width: 40px;"> - </td>
                            <td style="border-right:1px solid grey;border-bottom:1px solid grey;">Last Payments</td>
                            <td rowspan="2" style="border-right:1px solid grey;width: 40px;">-</td>
                            <td style="border-right:1px solid grey;border-bottom:1px solid grey;">{$adjustment_col}</td>
                            <td rowspan="2" style="border-right:1px solid grey;width: 40px;">+</td>
                            <td style="border-right:1px solid grey;border-bottom:1px solid grey;">Current Bill</td>
                            <td rowspan="2" style="border-right:1px solid grey;width: 40px;"> = </td>
                            <td style="border-bottom:1px solid grey;">Total Due</td>
                        </tr>
                        <tr>
                            <td style="border-right:1px solid grey;">{$previous_due|number_format:2:".":","} </td>
                            <td style="border-right:1px solid grey;">{$last_payment|number_format:2:".":","}</td>
                            <td style="border-right:1px solid grey;">{$adjustment|number_format:2:".":","}</td>
                            <td style="border-right:1px solid grey;">{($basic_total+$tax_total)|number_format:2:".":","}</td>
                            <td>{$invoice_total|number_format:2:".":","}</td>
                        </tr>
                    </table>
                {/if}
            {/if}
        {/if}
        <table autosize="1" width="100%" border="0" cellspacing="0" cellpadding="5"
            style="font-family:Verdana;border: 1px solid #cbcbcb;color:#5b4d4b;font-size:13px;line-height: 22px;text-align: center;border-bottom: 0;">
            <thead>
                <tr style="background-color: #E5E5E5 ;">
                    {foreach from=$particular_column item=v}
                        <th style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;">
                            {$v}
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
                        {foreach from=$particular_column key=k item=v}
                            {if $k=='sr_no'}
                                <td width="5%"
                                    style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb; text-align: center;max-width:10px;">
                                    {$int}
                                </td>
                            {else}
                                <td style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;text-align: center;">
                                    {$dp.{$k}}
                                </td>
                            {/if}
                        {/foreach}
                    </tr>
                    {$int=$int+1}
                {/foreach}
            </tbody>
        </table>
        <div style="width: 100%;">
            <div width="300" style="width:59%;height: auto; font-family:Verdana;color:#5b4d4b;float: left;vertical-align: top;text-align:left;font-size: 13px;vertical-align: top;
                    padding-top: 10px;">
                {if $info.narrative!=''}
                    <b>Narrative</b><br>
                    {$info.narrative}
                    <br>
                {/if}
                {if $tnc!=''}
                    <b>Terms & Conditions</b><br>
                    {$tnc}
                {/if}

            </div>
            <div width="400" style="width: 40%;float: right;">
                <table autosize="1" width="100%" border="0" cellspacing="0" cellpadding="5"
                    style="border-left: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;border-top: 0;border-bottom: 0;color:#5b4d4b;font-size:13px;line-height: 22px;text-align: center;">
                    <tr>
                        <td colspan=""
                            style="min-width:100px; border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;text-align: right;">
                            <b>Sub Total</b>
                        </td>
                        <td style="border-bottom: 1px solid #cbcbcb;text-align: right;" class="col-md-2 td-c">
                            <b> {$info.basic_amount|number_format:2:".":","}</b>
                        </td>
                    </tr>
                    {foreach from=$tax item=v}
                        <tr style="text-align: center;">
                            <td style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;text-align: right;"
                                colspan="">
                                {$v.tax_name}
                            </td>
                            <td style="border-bottom: 1px solid #cbcbcb;text-align: right;">
                                {$v.tax_amount}
                                {$taxtotal={$taxtotal}+{$v.tax_amount}}
                            </td>
                        </tr>
                    {/foreach}
                    <tr style="text-align: center;">
                        <td style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;text-align: right;">
                            <b>Total</b>
                        </td>
                        <td style="border-bottom: 1px solid #cbcbcb;text-align: right;"><b>
                                {$info.currency_icon} {($info.basic_amount+$tax_total)|number_format:2:".":","}</b></td>
                    </tr>
                    {if $paid_amount>0}
                        <tr style="text-align: center;">
                            <td style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;text-align: right;">
                                Paid amount {$info.currency_icon}
                            </td>
                            <td style="border-bottom: 1px solid #cbcbcb;text-align: right;"><b>
                                    {$info.currency_icon} {($paid_amount)|number_format:2:".":","}</b></td>
                        </tr>
                    {/if}
                    {if $previous_due>0}
                        <tr style="text-align: center;">
                            <td style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;text-align: right;">
                                {$previous_due_col}</td>
                            <td style="border-bottom: 1px solid #cbcbcb;text-align: right;">
                                {$previous_due|number_format:2:".":","}</td>
                        </tr>
                    {/if}
                    {if $adjustment>0}
                        <tr style="text-align: center;">
                            <td style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;text-align: right;">
                                {$adjustment_col}</td>
                            <td style="border-bottom: 1px solid #cbcbcb;text-align: right;">
                                {$adjustment|number_format:2:".":","}</td>
                        </tr>
                    {/if}
                    <tr style="text-align: center;">
                        <td style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;text-align: right;">
                            <b>Grand Total</b>
                        </td>
                        <td style="border-bottom: 1px solid #cbcbcb;text-align: right;"><b>
                                {$info.currency_icon}
                                {($absolute_cost)|number_format:2:".":","}</b></td>
                    </tr>


                    {if $advance>0}
                        <tr style="text-align: center;">
                            <td style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;text-align: right">
                                <b>Advance received</b>
                            </td>
                            <td style="border-bottom: 1px solid #cbcbcb;text-align: right;">
                                <b>{($advance)|number_format:2:".":","}</b>
                            </td>
                        </tr>
                        <tr style="text-align: center;">
                            <td style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;text-align: right">
                                <b>Balance</b>
                            </td>
                            <td style="border-bottom: 1px solid #cbcbcb;text-align: right;">
                                <b>{($grand_total)|number_format:2:".":","}</b>
                            </td>
                        </tr>
                    {/if}
                    {if $pan!='' && $panontop==0}
                        <tr style="text-align: center;">
                            <td style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;text-align: left;">
                                PAN NO.</td>
                            <td style="border-bottom: 1px solid #cbcbcb;text-align: right;">{$pan}</td>
                        </tr>
                    {/if}
                    {if $tan!='' && $tanontop==0}
                        <tr style="text-align: center;">
                            <td style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;text-align: left;">
                                TAN NO.</td>
                            <td style="border-bottom: 1px solid #cbcbcb;text-align: right;"> {$tan}</td>
                        </tr>
                    {/if}
                    {if $cin_no!='' && $cinontop==0}
                        <tr style="text-align: center;">
                            <td style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;text-align: left;">
                                CIN NO.</td>
                            <td style="border-bottom: 1px solid #cbcbcb;text-align: right;">{$cin_no}</td>
                        </tr>
                    {/if}
                    {if $gst_number!='' && $gstontop==0}
                        <tr style="text-align: center;">
                            <td style="text-align: left;border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;">
                                GST Number</td>
                            <td style="text-align: right;border-bottom: 1px solid #cbcbcb;">{$gst_number}</td>
                        </tr>
                    {else}
                        {if $registration_number!=''}
                            <tr style="text-align: center;">
                                <td style="text-align: left;border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;">
                                    S. Tax Regn.</td>
                                <td style="text-align: right;border-bottom: 1px solid #cbcbcb;">{$registration_number}
                                </td>
                            </tr>
                        {/if}
                    {/if}
                    <tr>
                        <td colspan="2" style="text-align: left;border-bottom: 1px solid #cbcbcb;">
                            <p><b>Amount (in words) :</b> {$money_words}</p>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <table autosize="1" style="margin:0 auto; font-family:Verdana, Arial;width: 800px;" align="center" width="800"
            border="0" cellspacing="0" cellpadding="10">

            {if $plugin.has_signature!=1}
                <tr style="text-align: center;">
                    <td style="border-top: 1px solid grey;text-align: left;width: 100%;font-size: 12px;">
                        <b> * Note: </b>This is a system generated invoice. No signature required.
                    </td>

                </tr>
            {/if}
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
            {if $is_merchant==1 && $plugin.has_acknowledgement==1}
                <tr>
                    <td colspan="2" style="border-bottom:1px solid #cbcbcb;">
                        <table autosize="1" width="800" style="border:1px solid #cbcbcb;color:#5b4d4b;"
                            class="table table-bordered table-condensed">
                            <tbody>
                                <tr>
                                    {if $info.invoice_number!=''}
                                        <th width="100" style="border-bottom:1px solid #cbcbcb;border-right:1px solid #cbcbcb;">
                                            Invoice #</th>
                                    {/if}
                                    <th width="100" style="border-bottom:1px solid #cbcbcb;border-right:1px solid #cbcbcb;">
                                        Customer Code</th>
                                    <th width="100" style="border-bottom:1px solid #cbcbcb;border-right:1px solid #cbcbcb;">
                                        Cheque No</th>
                                    <th width="140" style="border-bottom:1px solid #cbcbcb;border-right:1px solid #cbcbcb;">
                                        Bank/Branch</th>
                                    <th width="100" style="border-bottom:1px solid #cbcbcb;border-right:1px solid #cbcbcb;">
                                        Amount</th>
                                    <th width="140" style="border-bottom:1px solid #cbcbcb;border-right:1px solid #cbcbcb;">
                                        Cash Received</th>
                                    <th width="{if $info.invoice_number!=''}120{else}180{/if}"
                                        style="border-bottom:1px solid #cbcbcb;">Signature & Stamp</th>
                                </tr>
                                <tr>
                                    {if $info.invoice_number!=''}
                                        <td style="border-right:1px solid #cbcbcb; text-align: center;" align="center">
                                            {$info.invoice_number}</td>
                                    {/if}
                                    <td style="border-right:1px solid #cbcbcb; text-align: center;" align="center">
                                        {$info.customer_code}</td>
                                    <td style="border-right:1px solid #cbcbcb;">{$transaction.cheque_no}</td>
                                    <td style="border-right:1px solid #cbcbcb;">{$transaction.bank_name}</td>
                                    <td style="border-right:1px solid #cbcbcb; text-align: center;" align="center">
                                        {$info.grand_total}</td>
                                    <td style="border-right:1px solid #cbcbcb;">{$transaction.amount}</td>
                                    <td style="height: 50px;"></td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            {/if}

        </table>
    </div>
</body>

</html>