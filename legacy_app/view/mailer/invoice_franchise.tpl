{strip}
    <html>

    <body>
        <table autosize="1" style="margin:0 auto; font-family:Verdana, Arial;width: 800px; border: 1px solid grey;"
            align="center" width="800" border="0" cellspacing="0" cellpadding="5">
            <tr>
                <td style="font-size:13px; line-height:30px;">
                    <table autosize="1" width="100%" border="0" cellspacing="0" cellpadding="2">
                        <tr>
                            <td width="200" align="center" valign="top">
                                {if {$image_path}!=''}
                                    <img src="/uploads/images/logos/{$image_path}" style="max-width:160px;max-height:100px;" />
                                {/if}
                            </td>
                            <td width="310" style="">
                                <span style="font-size:20px; color:#6e605d;">{$company_name}</span> <br>
                                {if $info.main_company_name!=''}
                                    <span
                                        style="font-size:12px; display:block; margin-top:5px; margin-bottom:1px;line-height: 16px;">
                                        (An official franchisee of {$info.main_company_name})</span><br>
                                {/if}
                                <span
                                    style="font-size:13px; display:block; margin-top:5px; margin-bottom:1px;line-height: 16px;">{$merchant_address}
                                </span>
                                {foreach from=$main_header item=v}
                                    {if $v.column_name!='Company name' && $v.value!='' && $v.column_name!='Merchant address'}
                                        <br><span class="muted"
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
                </td>
            </tr>
            <tr style="border-spacing: 0px;">
                <td
                    style="text-align: center;background-color: #E5E5E5 ;border-top: 1px solid grey;border-bottom: 1px solid grey;font-size:15px;padding:5">

                    {if $info.invoice_type==2}
                        {$plugin.invoice_title}
                    {else}
                        {if !empty($tax)}TAX {/if}INVOICE
                    {/if}

                </td>
            </tr>
            <tr>
                <td style="font-size:13px;  border-bottom:1px #cbcbcb;">
                    <table autosize="1" border="0" cellspacing="0" cellpadding="2"
                        style="font-size:13px;line-height: 15px;width: 100%;">
                        <tr>
                            <td
                                style="font-size:13px;  border-bottom:1px #cbcbcb;border-right: 1px solid #cbcbcb; width:380px;">
                                <div style="float:left;line-height: 10px;  margin-right:5px;">
                                    <table autosize="1" width="360" border="0" cellspacing="0" cellpadding="2"
                                        style="color:#5b4d4b;font-size:13px;line-height: 20px;">

                                        {foreach from=$customer_breckup item=v}
                                            {if $v.value!=''}
                                                <tr>
                                                    <td width="35%"
                                                        style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;min-width:140px;">
                                                        <b>{$v.column_name}</b></td>
                                                    <td style="border-bottom: 1px solid #cbcbcb;">{$v.value}</td>
                                                </tr>
                                            {/if}
                                        {/foreach}

                                        </tbody>
                                    </table>
                                </div>
                            </td>
                            <td
                                style="font-size:13px;  border-bottom:1px #cbcbcb;border-left: 1px solid grey; width:380px;">
                                <div style="float: right; padding-left: 1px;">
                                    <table autosize="1" width="360" border="0" cellspacing="0" cellpadding="2"
                                        style="color:#5b4d4b;font-size:13px;line-height: 20px;">
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
                                                            <td style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;">
                                                                <b>{$v.column_name}</b></td>
                                                            <td style="border-bottom: 1px solid #cbcbcb;"><a target="_BlANK"
                                                                    href="{$v.value}">{$v.column_name}</a></td>
                                                        </tr>

                                                    {else}
                                                        <tr>
                                                            <td style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;">
                                                                <b>{$v.column_name}</b></td>
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

                </td>
            </tr>

            <tr>
                <td>




                    <table autosize="1" width="100%" border="0">
                        <tr>
                            <td style="vertical-align: top;">
                                <table autosize="1" width="100%" border="0" cellspacing="0" cellpadding="2"
                                    style="border: 1px solid grey;color:#5b4d4b;font-size:13px;line-height: 22px;text-align: center;">
                                    <tr>
                                        <td colspan="2" style="border-bottom: 1px solid grey;">
                                            <b> Daily Gross Sales</b>
                                        </td>
                                    </tr>
                                    {$gross_sale=0}
                                    {$sale_tax=0}
                                    {$net_sale=0}
                                    {foreach from=$sale_details item=v}
                                        <tr>
                                            <td style="border-right: 1px solid grey;border-bottom: 1px solid grey;">
                                                {$v.date|date_format:"%d-%m-%Y"}
                                            </td>
                                            <td style="border-bottom: 1px solid grey;">
                                                {$v.gross_sale}
                                                {$gross_sale=$v.gross_sale+$gross_sale}
                                                {$sale_tax=$v.tax+$sale_tax}
                                                {$net_sale=$v.billable_sale+$net_sale}
                                            </td>
                                        </tr>
                                    {/foreach}
                                </table>
                            </td>
                            <td>
                                <table autosize="1" width="100%" border="0" cellspacing="0" cellpadding="2"
                                    style="border: 1px solid grey;color:#5b4d4b;font-size:13px;line-height: 22px;text-align: center;">
                                    <tr>
                                        <td style="border-bottom: 1px solid grey;border-right: 1px solid grey;">
                                            <b>Summary</b>
                                        </td>
                                        <td colspan="2" style="border-bottom: 1px solid grey;">
                                            <b>Franchise Fees</b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="border-right: 1px solid grey;border-bottom: 1px solid grey;">Gross
                                            Billable Sales</td>
                                        <td colspan="2" style="border-bottom: 1px solid grey;text-align:right;">
                                            {$gross_sale|string_format:"%.2f"}</td>
                                    </tr>
                                    <tr>
                                        <td style="border-right: 1px solid grey;border-bottom: 1px solid grey;">Less : CGST
                                            and SGST 5.00 % </td>
                                        <td colspan="2" style="border-bottom: 1px solid grey;text-align:right;">
                                            {$sale_tax|string_format:"%.2f"}</td>
                                    </tr>
                                    <tr>
                                        <td style="border-right: 1px solid grey;border-bottom: 1px solid grey;">Net Billable
                                            Sales</td>
                                        <td colspan="2" style="border-bottom: 1px solid grey;text-align:right;">
                                            {$net_sale|string_format:"%.2f"}</td>
                                    </tr>
                                    <tr>
                                        <td style="border-right: 1px solid grey;border-bottom: 1px solid grey;">Gross
                                            Franchisee Fee on Net Billable</td>
                                        <td
                                            style="border-right: 1px solid grey;border-bottom: 1px solid grey;text-align:right;">
                                            {$sale_summary.commision_fee_percent}%</td>
                                        <td style="border-bottom: 1px solid grey;text-align:right;">
                                            {$sale_summary.gross_fee|string_format:"%.2f"}</td>
                                    </tr>
                                    <tr>
                                        <td style="border-right: 1px solid grey;border-bottom: 1px solid grey;">Less: Waiver
                                        </td>
                                        <td
                                            style="border-right: 1px solid grey;border-bottom: 1px solid grey;text-align:right;">
                                            {$sale_summary.commision_waiver_percent}%</td>
                                        <td style="border-bottom: 1px solid grey;text-align:right;">
                                            {$sale_summary.waiver_fee|string_format:"%.2f"}</td>
                                    </tr>
                                    <tr>
                                        <td style="border-right: 1px solid grey;border-bottom: 1px solid grey;">Net
                                            Franchise Fee receivable</td>
                                        <td
                                            style="border-right: 1px solid grey;border-bottom: 1px solid grey;text-align:right;">
                                            {$sale_summary.commision_net_percent}%</td>
                                        <td style="border-bottom: 1px solid grey;text-align:right;">
                                            {$sale_summary.net_fee|string_format:"%.2f"}</td>
                                    </tr>
                                    <tr>
                                        <td style="border-right: 1px solid grey;border-bottom: 1px solid grey;">Penalty on
                                            outstanding amt</td>
                                        <td colspan="2" style="border-bottom: 1px solid grey;text-align:right;">
                                            {$sale_summary.penalty|string_format:"%.2f"}</td>
                                    </tr>
                                    <tr>
                                        <td style="border-right: 1px solid grey;border-bottom: 1px solid grey;">Franchisee
                                            fees Payable</td>
                                        <td colspan="2" style="border-bottom: 1px solid grey;text-align:right;">
                                            {$info.basic_amount|string_format:"%.2f"}</td>
                                    </tr>
                                    {$taxtotal=0}
                                    {foreach from=$tax item=v}
                                        <tr>
                                            <td style="border-right: 1px solid grey;border-bottom: 1px solid grey;">
                                                {$v.tax_name}
                                            </td>
                                            <td colspan="2" style="border-bottom: 1px solid grey;text-align:right;">
                                                {$v.tax_amount}
                                                {$taxtotal={$taxtotal}+{$v.tax_amount}}
                                            </td>
                                        </tr>
                                    {/foreach}
                                    <tr>
                                        <td style="border-right: 1px solid grey;border-bottom: 1px solid grey;">Total Amount
                                            (FEE)</td>
                                        <td colspan="2" style="border-bottom: 1px solid grey;text-align:right;">
                                            {$info.basic_amount+$taxtotal|string_format:"%.2f"}</td>
                                    </tr>
                                    <tr>
                                        <td style="border-right: 1px solid grey;border-bottom: 1px solid grey;">Previous
                                            outstanding</td>
                                        <td colspan="2" style="border-bottom: 1px solid grey;text-align:right;">
                                            {$info.previous_due|string_format:"%.2f"}</td>
                                    </tr>
                                    <tr>
                                        <td style="border-right: 1px solid grey;border-bottom: 1px solid grey;">Total FF to
                                            be Paid with Previous outstanding</td>
                                        <td colspan="2" style="border-bottom: 1px solid grey;text-align:right;">
                                            {$info.absolute_cost|string_format:"%.2f"}</td>
                                    </tr>
                                    <tr>
                                        <td style="border-right: 1px solid grey;border-bottom: 1px solid grey;">Rounded off
                                            to</td>
                                        <td colspan="2" style="border-bottom: 1px solid grey;text-align:right;">
                                            {$grand_total|string_format:"%.2f"}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" style="text-align:left;">Amount in words: {$money_words}</td>
                                    </tr>
                                </table>
                            </td>

                        </tr>
                        <tr>
                            <td colspan="2" style="text-align:right;">
                                <table style="width:100%;">
                                    <tr>
                                        <td style="text-align:left;">
                                            {$tnc}
                                        </td>
                                        <td style="width:40%;vertical-align:top;">
                                            <table autosize="1" width="100%" border="0" cellspacing="0" cellpadding="2"
                                                style="border: 1px solid grey;color:#5b4d4b;font-size:13px;line-height: 18px;text-align: center;">
                                                {if $cin_no!=''}
                                                    <tr>
                                                        <td style="border-bottom: 1px solid grey;border-right: 1px solid grey;">
                                                            CIN No
                                                        </td>
                                                        <td style="border-bottom: 1px solid grey;">
                                                            {$cin_no}
                                                        </td>
                                                    </tr>
                                                {/if}
                                                {if $info.pan!=''}
                                                <tr>
                                                    <td style="border-bottom: 1px solid grey;border-right: 1px solid grey;">
                                                        Income Tax PAN
                                                    </td>
                                                    <td style="border-bottom: 1px solid grey;">
                                                        {$info.pan}
                                                    </td>
                                                </tr>
                                                 {/if}
                                                 {if $info.gst_number!=''}
                                                <tr>
                                                    <td style="border-bottom: 1px solid grey;border-right: 1px solid grey;">
                                                        GST No
                                                    </td>
                                                    <td style="border-bottom: 1px solid grey;">
                                                        {$info.gst_number}
                                                    </td>
                                                </tr>
                                                {/if}
                                                 {if $info.sac_code!=''}
                                                <tr>
                                                    <td style="border-bottom: 1px solid grey;border-right: 1px solid grey;">
                                                        HSN number
                                                    </td>
                                                    <td style="border-bottom: 1px solid grey;">
                                                        {$info.sac_code}
                                                    </td>
                                                </tr>
                                                {/if}
                                                 {if $info.business_category!=''}
                                                <tr>
                                                    <td style="border-right: 1px solid grey;">
                                                        Category of Service
                                                    </td>
                                                    <td style="">
                                                        {$info.business_category}
                                                    </td>
                                                </tr>
                                                {/if}

                                            </table>
                                        </td>
                                    </tr>
                                </table>

                                <div style="text-align:right;color:#5b4d4b;font-size:15px;line-height: 22px;">
                                    For {$company_name}
                                </div>
                                <br>
                                <table style="width:100%">
                                    <tr>
                                        <td
                                            style="text-align:left !important;color:#5b4d4b;font-size:10px;line-height: 22px;width:70%">
                                            *This is a Computer generated document and does not require signature
                                        </td>
                                        <td style="width:30%">
                                            <div
                                                style="text-align:right;color:#5b4d4b;font-size:15px;line-height: 22px;float:right;">
                                                (Authorised Signatory)
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </td>

                    </table>




                </td>
            </tr>
            <tr>
                <td>
                    <table autosize="1" style="margin:0 auto; font-family:Verdana, Arial;width: 800px;" align="center"
                        width="800" border="0" cellspacing="0" cellpadding="10">

                        {if $plugin.has_signature!=1}

                        {/if}
                        {if $plugin.has_signature==1}
                            {if isset($signature)}
                                <tr>
                                    <td colspan="2" style="text-align:{$signature.align};  ">
                                        {if $signature.type=='font'}
                                            <img src="{$server_path}{$signature.font_image}"
                                                style="max-height: 100px;float:{$signature.align};">
                                        {else}
                                            <img src="{$server_path}{$signature.signature_file}"
                                                style="max-height: 100px;float:{$signature.align};">
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
                                                    <th width="100"
                                                        style="border-bottom:1px solid #cbcbcb;border-right:1px solid #cbcbcb;">
                                                        Invoice #</th>
                                                {/if}
                                                <th width="100"
                                                    style="border-bottom:1px solid #cbcbcb;border-right:1px solid #cbcbcb;">
                                                    Customer Code</th>
                                                <th width="100"
                                                    style="border-bottom:1px solid #cbcbcb;border-right:1px solid #cbcbcb;">
                                                    Cheque No</th>
                                                <th width="140"
                                                    style="border-bottom:1px solid #cbcbcb;border-right:1px solid #cbcbcb;">
                                                    Bank/Branch</th>
                                                <th width="100"
                                                    style="border-bottom:1px solid #cbcbcb;border-right:1px solid #cbcbcb;">
                                                    Amount</th>
                                                <th width="140"
                                                    style="border-bottom:1px solid #cbcbcb;border-right:1px solid #cbcbcb;">Cash
                                                    Received</th>
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
                </td>
            </tr>

        </table>









    </body>

    </html>
{/strip}