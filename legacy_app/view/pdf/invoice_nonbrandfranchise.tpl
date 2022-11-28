{strip}
    <html>
    {if isset($signature.font_file)}
        <link href="{$signature.font_file}" rel="stylesheet">
    {/if}

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
                                        <tr>
                                            <td style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;">
                                                <b>Bill Period</b>
                                            </td>
                                            <td style="border-bottom: 1px solid #cbcbcb;">{$sale_summary.bill_period}</td>
                                        </tr>
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
                                    style="border: 1px solid grey;color:#5b4d4b;font-size:13px;line-height: 20px;text-align: center;">
                                    <tr>
                                        <td colspan="3" style="border-bottom: 1px solid grey;">
                                            <b> Daily Gross Sales Offline</b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="border-bottom: 1px solid grey;border-right: 1px solid grey;">
                                            Date
                                        </td>
                                        <td style="border-bottom: 1px solid grey;border-right: 1px solid grey;">
                                            Chetty's
                                    </td>
                                    <td style="border-bottom: 1px solid grey;">
                                        Non Chetty's
                                        </td>
                                    </tr>

                                    {foreach from=$sale_details item=v}
                                        <tr>
                                            <td style="border-right: 1px solid grey;border-bottom: 1px solid grey;">
                                                {$v.date|date_format:"%d-%m-%Y"}
                                            </td>
                                            <td style="border-bottom: 1px solid grey;border-right: 1px solid grey;">
                                                {$v.gross_sale}
                                            </td>
                                            <td style="border-bottom: 1px solid grey;">
                                                {$v.non_brand_gross_sale}
                                            </td>
                                        </tr>
                                    {/foreach}
                                </table>
                                <br>
                                <table autosize="1" width="100%" border="0" cellspacing="0" cellpadding="2"
                                    style="border: 1px solid grey;color:#5b4d4b;font-size:13px;line-height: 20px;text-align: center;">
                                    <tr>
                                        <td colspan="3" style="border-bottom: 1px solid grey;">
                                            <b> Daily Gross Sales Online</b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="border-bottom: 1px solid grey;border-right: 1px solid grey;">
                                            Date
                                        </td>
                                        <td style="border-bottom: 1px solid grey;border-right: 1px solid grey;">
                                            Chetty's
                                    </td>
                                    <td style="border-bottom: 1px solid grey;">
                                        Non Chetty's
                                        </td>
                                    </tr>
                                    {foreach from=$sale_details item=v}
                                        <tr>
                                            <td style="border-right: 1px solid grey;border-bottom: 1px solid grey;">
                                                {$v.date|date_format:"%d-%m-%Y"}
                                            </td>
                                            <td style="border-bottom: 1px solid grey;border-right: 1px solid grey;">
                                                {$v.gross_sale_online}
                                            </td>
                                            <td style="border-bottom: 1px solid grey;">
                                                {$v.non_brand_gross_sale_online}
                                            </td>
                                        </tr>
                                    {/foreach}
                                </table>
                            </td>
                            <td>
                                <table autosize="1" width="100%" border="0" cellspacing="0" cellpadding="2"
                                    style="border: 1px solid grey;color:#5b4d4b;font-size:13px;line-height: 20px;text-align: center;">
                                    <tr>
                                        <td style="border-bottom: 1px solid grey;border-right: 1px solid grey;">
                                            <b>Summary</b>
                                        </td>
                                        <td colspan="4" style="border-bottom: 1px solid grey;">
                                            <b>Franchise Fees</b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="border-bottom: 1px solid grey;border-right: 1px solid grey;">

                                        </td>
                                        <td colspan="2" style="border-bottom: 1px solid grey;border-right: 1px solid grey;">
                                            Chetty's

                                    </td>
                                    <td colspan="2" style="border-bottom: 1px solid grey;border-right: 1px solid grey;">
                                        Non chetty's
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="border-right: 1px solid grey;border-bottom: 1px solid grey;">Gross
                                            Billable Sales Offline</td>
                                        <td colspan="2"
                                            style="border-bottom: 1px solid grey;text-align:right;border-right: 1px solid grey;">
                                            {$sale_summary.gross_sale|string_format:"%.2f"}</td>
                                        <td colspan="2" style="border-bottom: 1px solid grey;text-align:right;">
                                            {$sale_summary.non_brand_gross_sale|string_format:"%.2f"}</td>
                                    </tr>
                                    <tr>
                                        <td style="border-right: 1px solid grey;border-bottom: 1px solid grey;"> CGST
                                            and SGST 5.00 % </td>
                                        <td colspan="2"
                                            style="border-bottom: 1px solid grey;text-align:right;border-right: 1px solid grey;">
                                            {$sale_summary.gross_sale-$sale_summary.net_sale|string_format:"%.2f"}</td>
                                        <td colspan="2" style="border-bottom: 1px solid grey;text-align:right;">
                                            {{$sale_summary.non_brand_gross_sale-$sale_summary.non_brand_net_sale}|string_format:"%.2f"}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="border-right: 1px solid grey;border-bottom: 1px solid grey;">Gross
                                            Billable Sales Online</td>
                                        <td colspan="2"
                                            style="border-bottom: 1px solid grey;text-align:right;border-right: 1px solid grey;">
                                            {$sale_summary.gross_sale_online|string_format:"%.2f"}</td>
                                        <td colspan="2" style="border-bottom: 1px solid grey;text-align:right;">
                                            {$sale_summary.non_brand_gross_sale_online|string_format:"%.2f"}</td>
                                    </tr>
                                    <tr>
                                        <td style="border-right: 1px solid grey;border-bottom: 1px solid grey;"> CGST
                                            and SGST 5.00 % </td>
                                        <td colspan="2"
                                            style="border-bottom: 1px solid grey;text-align:right;border-right: 1px solid grey;">
                                            {{$sale_summary.gross_sale_online-$sale_summary.net_sale_online}|string_format:"%.2f"}
                                        </td>
                                        <td colspan="2" style="border-bottom: 1px solid grey;text-align:right;">
                                            {{$sale_summary.non_brand_gross_sale_online-$sale_summary.non_brand_net_sale_online}|string_format:"%.2f"}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="border-right: 1px solid grey;border-bottom: 1px solid grey;">Delivery
                                            partner commision</td>
                                        <td colspan="2"
                                            style="border-bottom: 1px solid grey;text-align:right;border-right: 1px solid grey;">
                                            {$sale_summary.delivery_partner_commision|string_format:"%.2f"}
                                        </td>
                                        <td colspan="2" style="border-bottom: 1px solid grey;text-align:right;">
                                            {$sale_summary.non_brand_delivery_partner_commision|string_format:"%.2f"}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="border-right: 1px solid grey;border-bottom: 1px solid grey;">Net Billable
                                            Sales</td>
                                        <td colspan="2"
                                            style="border-bottom: 1px solid grey;text-align:right;border-right: 1px solid grey;">
                                            {$sale_summary.net_sale+$sale_summary.net_sale_online-$sale_summary.delivery_partner_commision|string_format:"%.2f"}
                                        </td>
                                        <td colspan="2" style="border-bottom: 1px solid grey;text-align:right;">
                                            {$sale_summary.non_brand_net_sale+$sale_summary.non_brand_net_sale_online-$sale_summary.non_brand_delivery_partner_commision|string_format:"%.2f"}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="border-right: 1px solid grey;border-bottom: 1px solid grey;">Gross
                                            Franchisee Fee on Net Billable</td>
                                        <td
                                            style="border-right: 1px solid grey;border-bottom: 1px solid grey;text-align:right;">
                                            {$sale_summary.commision_fee_percent}%</td>
                                        <td
                                            style="border-bottom: 1px solid grey;text-align:right;border-right: 1px solid grey;">
                                            {$sale_summary.gross_fee|string_format:"%.2f"}</td>
                                        <td
                                            style="border-right: 1px solid grey;border-bottom: 1px solid grey;text-align:right;">
                                            {$sale_summary.non_brand_commision_fee_percent}%</td>
                                        <td style="border-bottom: 1px solid grey;text-align:right;">
                                            {$sale_summary.non_brand_gross_fee|string_format:"%.2f"}</td>
                                    </tr>
                                    <tr>
                                        <td style="border-right: 1px solid grey;border-bottom: 1px solid grey;">Less: Waiver
                                        </td>
                                        <td
                                            style="border-right: 1px solid grey;border-bottom: 1px solid grey;text-align:right;">
                                            {$sale_summary.commision_waiver_percent}%</td>
                                        <td
                                            style="border-bottom: 1px solid grey;text-align:right;border-right: 1px solid grey;">
                                            {$sale_summary.waiver_fee|string_format:"%.2f"}</td>
                                        <td
                                            style="border-right: 1px solid grey;border-bottom: 1px solid grey;text-align:right;">
                                            {$sale_summary.non_brand_commision_waiver_percent}%</td>
                                        <td style="border-bottom: 1px solid grey;text-align:right;">
                                            {$sale_summary.non_brand_waiver_fee|string_format:"%.2f"}</td>
                                    </tr>
                                    <tr>
                                        <td style="border-right: 1px solid grey;border-bottom: 1px solid grey;">Net
                                            Franchise Fee receivable</td>
                                        <td
                                            style="border-right: 1px solid grey;border-bottom: 1px solid grey;text-align:right;">
                                            {$sale_summary.commision_net_percent}%</td>
                                        <td
                                            style="border-bottom: 1px solid grey;text-align:right;border-right: 1px solid grey;">
                                            {$sale_summary.net_fee|string_format:"%.2f"}</td>
                                        <td
                                            style="border-right: 1px solid grey;border-bottom: 1px solid grey;text-align:right;">
                                            {$sale_summary.non_brand_commision_net_percent}%</td>
                                        <td style="border-bottom: 1px solid grey;text-align:right;">
                                            {$sale_summary.non_brand_net_fee|string_format:"%.2f"}</td>
                                    </tr>
                                    <tr>
                                        <td style="border-right: 1px solid grey;border-bottom: 1px solid grey;">Penalty on
                                            outstanding amt</td>
                                        <td colspan="4" style="border-bottom: 1px solid grey;text-align:right;">
                                            {$sale_summary.penalty|string_format:"%.2f"}</td>
                                    </tr>
                                    <tr>
                                        <td style="border-right: 1px solid grey;border-bottom: 1px solid grey;">Franchisee
                                            fees Payable</td>
                                        <td colspan="4" style="border-bottom: 1px solid grey;text-align:right;">
                                            {$info.basic_amount|string_format:"%.2f"}</td>
                                    </tr>
                                    {$taxtotal=0}
                                    {foreach from=$tax item=v}
                                        <tr>
                                            <td style="border-right: 1px solid grey;border-bottom: 1px solid grey;">
                                                {$v.tax_name}
                                            </td>
                                            <td colspan="4" style="border-bottom: 1px solid grey;text-align:right;">
                                                {$v.tax_amount}
                                                {$taxtotal={$taxtotal}+{$v.tax_amount}}
                                            </td>
                                        </tr>
                                    {/foreach}
                                    <tr>
                                        <td style="border-right: 1px solid grey;border-bottom: 1px solid grey;">Total Amount
                                            (FEE)</td>
                                        <td colspan="4" style="border-bottom: 1px solid grey;text-align:right;">
                                            {$info.basic_amount+$taxtotal|string_format:"%.2f"}</td>
                                    </tr>
                                    <tr>
                                        <td style="border-right: 1px solid grey;border-bottom: 1px solid grey;">Previous
                                            outstanding</td>
                                        <td colspan="4" style="border-bottom: 1px solid grey;text-align:right;">
                                            {$info.previous_due|string_format:"%.2f"}</td>

                                    </tr>
                                    <tr>
                                        <td style="border-right: 1px solid grey;border-bottom: 1px solid grey;">Total
                                            royalty to
                                            be Paid with Previous outstanding</td>
                                        <td colspan="4" style="border-bottom: 1px solid grey;text-align:right;">
                                            {$info.absolute_cost|string_format:"%.2f"}</td>
                                    </tr>
                                    <tr>
                                        <td style="border-right: 1px solid grey;border-bottom: 1px solid grey;">Rounded off
                                            to</td>
                                        <td colspan="4" style="border-bottom: 1px solid grey;text-align:right;">
                                            {$grand_total|string_format:"%.0f"}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" style="text-align:left;">Amount in words: {$money_words}</td>
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
                                                style="border: 1px solid grey;color:#5b4d4b;font-size:13px;line-height: 17px;text-align: center;">
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

                                <div style="text-align:right;color:#5b4d4b;font-size:15px;line-height: 20px;">
                                    For {$company_name}
                                </div>
                                <br>
                                <table style="width:100%">
                                    <tr>
                                        <td
                                            style="text-align:left !important;color:#5b4d4b;font-size:10px;line-height: 20px;width:70%">
                                            *This is a Computer generated document and does not require signature
                                        </td>
                                        <td style="width:30%">
                                            <div
                                                style="text-align:right;color:#5b4d4b;font-size:15px;line-height: 20px;float:right;">
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
                    <table autosize="1" style="margin:0 auto; font-family:Verdana, Arial;" align="center" width="100%"
                        border="0" cellspacing="0" cellpadding="10">

                        {if $plugin.has_signature!=1}

                        {/if}
                        {if $plugin.has_signature==1}
                            {if isset($signature)}
                                <tr>
                                    <td colspan="2" style="text-align:{$signature.align};">
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