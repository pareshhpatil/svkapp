<html>

<body>
    <table autosize="1" cellspacing="0" cellpadding="5" width="100%" border="0"
        style="font-family:Verdana,Arial;width:100%;">
        <tr>
            <td>
                <table autosize="1" width="100%" border="0"
                    style="width:100%;margin-bottom:5px;color: #5b4d4b;font-size: 13px;" cellspacing="0"
                    cellpadding="5">
                    <tr>
                        <td width="150" align="center" valign="top">
                            {if {$image_path}!=''}
                                <img src="/uploads/images/logos/{$image_path}" width="70px" height="80px"
                                    style="max-width:70px;max-height:80px;" />
                            {/if}
                        </td>
                        <td width="400" style="">
                            <span style="font-size:15px; color:#6e605d;">{$company_name}</span> <br>
                            {if $info.main_company_name!=''}
                                <span
                                    style="font-size:12px; display:block; margin-top:5px; margin-bottom:1px;line-height: 13px;">
                                    (An
                                    official franchisee of {$info.main_company_name})</span><br>
                            {/if}
                            <span
                                style="font-size:9px;display:block; margin-top:5px; margin-bottom:1px;line-height: 13px;">{$merchant_address}
                            </span>
                            {foreach from=$main_header item=v}
                                {if $v.column_name!='Company name' && $v.value!='' && $v.column_name!='Merchant address'}
                                    <br><span
                                        style="font-size:9px;display:block;margin-top:1px; margin-bottom:1px;line-height: 13px;">
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
        <tr>
            <td>
                <table autosize="1" border="0" cellspacing="0" cellpadding="5" width="99%"
                    style="text-align:center;background-color:#E5E5E5;font-family:Verdana;border-top:1px solid grey;border-bottom:1px solid grey;font-size:13px;width:100%;">
                    <tr style="border-spacing:0;">
                        <td>

                            {if $info.invoice_type==2}
                                {$plugin.invoice_title}
                            {else}
                                {if !empty($tax)}TAX {/if}INVOICE
                            {/if}

                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <table autosize="1" border="0" cellspacing="0" cellpadding="2"
                    style="font-family:Verdana;font-size:9px;line-height: 13px;width: 99%;margin-bottom: 5px;">

                    <tr>
                        <td style="font-size:9px;border-right:1px solid #cbcbcb; width:50%;">

                            <table autosize="1" width="100%" border="0" cellspacing="0" cellpadding="2"
                                style="color:#5b4d4b;font-size:9px;line-height: 13px;">

                                {foreach from=$customer_breckup item=v}
                                    {if $v.value!=''}
                                        <tr>
                                            <td width="35%"
                                                style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;min-width:140px;">
                                                <b>{$v.column_name}</b>
                                            </td>
                                            <td width="65%" style="border-bottom: 1px solid #cbcbcb;">{$v.value}</td>
                                        </tr>
                                    {/if}
                                {/foreach}

                                </tbody>
                            </table>

                        </td>
                        <td style="font-family:Verdana;font-size:9px;border-left: 1px solid grey; width:50%;">

                            <table autosize="1" width="100%" border="0" cellspacing="0" cellpadding="2"
                                style="color:#5b4d4b;font-size:9px;line-height: 13px;">
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
            </td>
        </tr>
        {if $info.hide_invoice_summary!=1}
            <tr>
                <td>
                    {if $last_payment==NULL && $adjustment==NULL}
                        <table autosize="1" border="0" cellspacing="0" cellpadding="2"
                            style="font-family:Verdana;border:1px solid #BEC6C6;color:#5b4d4b;font-size:9px;line-height: 13px;width: 100%;margin-bottom: 30px;">
                            <tr>
                                <td style="width:100;border-right: 1px solid #cbcbcb;background-color:#E5E5E5;">
                                    <b>Billing Summary</b>
                                </td>
                                <td style="width:130;border-right: 1px solid #cbcbcb;">
                                    <b> Past Due :</b> {$previous_due|number_format:2:".":","}
                                </td>
                                <td style="width:200;border-right: 1px solid #cbcbcb;">
                                    <b> Current Charges :</b>{($basic_total+$tax_total)|number_format:2:".":","}
                                </td>
                                <td style="width:190;background-color:#E5E5E5;">
                                    <b> Total Due :</b>{$invoice_total|number_format:2:".":","}
                                </td>
                            </tr>
                        </table>
                    {else}
                        {if $last_payment!=NULL && $adjustment!=NULL}
                            <table autosize="1" cellspacing="0" cellpadding="3"
                                style="font-family:Verdana;width: 99%;text-align: center;margin: 0px !important;border:1px solid grey;margin-bottom: 10px;">
                                <tr style="font-size: 9px;border-bottom:1px solid grey !important;">
                                    <td style="border-right:1px solid grey;border-bottom:1px solid grey;">Previous Balance</td>
                                    <td rowspan="2" style="border-right:1px solid grey;"> - </td>
                                    <td style="border-right:1px solid grey;border-bottom:1px solid grey;">Last Payments</td>
                                    <td rowspan="2" style="border-right:1px solid grey;">-</td>
                                    <td style="border-right:1px solid grey;border-bottom:1px solid grey;">{$adjustment_col}</td>
                                    <td rowspan="2" style="border-right:1px solid grey;">+</td>
                                    <td style="border-right:1px solid grey;border-bottom:1px solid grey;">Current Bill</td>
                                    <td rowspan="2" style="border-right:1px solid grey;"> = </td>
                                    <td style="border-bottom:1px solid grey;">Total Due</td>
                                </tr>
                                <tr>
                                    <td style="border-right:1px solid grey;">{$previous_due|number_format:2:".":","} </td>

                                    <td style="border-right:1px solid grey;">{$last_payment|number_format:2:".":","}</td>
                                    <td style="border-right:1px solid grey;">{$adjustment|number_format:2:".":","}</td>
                                    <td style="border-right:1px solid grey;">{($basic_total+$tax_total)|number_format:2:".":","}
                                    </td>
                                    <td>{$info.currency_icon} {$invoice_total|number_format:2:".":","}</td>
                                </tr>
                            </table>
                        {/if}
                    {/if}
                </td>
            </tr>
        {/if}
        <tr>
            <td>
                <table autosize="1" width="99%" border="0" cellspacing="0" cellpadding="2"
                    style="font-family:Verdana;border:1px solid #cbcbcb;color:#5b4d4b;font-size:9px;line-height: 13px;text-align:center;width:99%">
                    <thead>
                        <tr style="background-color:#E5E5E5;">
                            {foreach from=$particular_column key=h item=v}
                                {if $h=='sr_no'}
                                    <th width="5%"
                                        style="width:5%;border-bottom:1px solid #cbcbcb;border-right:1px solid #cbcbcb;">
                                        <b>{$v}</b>
                                    </th>
                                {else}
                                    <th
                                        style="width:{$info.particular_tbl_width}%;border-bottom:1px solid #cbcbcb;border-right:1px solid #cbcbcb;">
                                        <b>{$v}</b>
                                    </th>
                                {/if}
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
                                            style="width:5%;min-wdith:5%;border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb; text-align: center;">
                                            {$int}
                                        </td>
                                    {else}
                                        <td
                                            style="width:{$info.particular_tbl_width}%;border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;text-align: center;">
                                            {$dp.{$k}}
                                        </td>
                                    {/if}
                                {/foreach}
                            </tr>
                            {$int=$int+1}
                        {/foreach}
                    </tbody>
                </table>
            </td>
        </tr>
        {if $is_merchant==1 && $plugin.has_acknowledgement==1}
            <tr>
                <td>
                    <table autosize="1" style="font-family:Verdana, Arial;width: 99%;" align="center" width="99%" border="0"
                        cellspacing="0" cellpadding="5">

                        <tr>

                            <table autosize="1" width="99%"
                                style="width:100%;border:1px solid #cbcbcb;color:#5b4d4b;font-size: 9px;"
                                class="table table-bordered table-condensed">
                                <tbody>
                                    <tr>
                                        {if $info.invoice_number!=''}
                                            <th width="100px"
                                                style="font-size: 9px;border-bottom:1px solid #cbcbcb;border-right:1px solid #cbcbcb;">
                                                Invoice #</th>
                                        {/if}
                                        <th width="100"
                                            style="width:100px;border-bottom:1px solid #cbcbcb;border-right:1px solid #cbcbcb;">
                                            Customer Code</th>
                                        <th width="100"
                                            style="width:100px;border-bottom:1px solid #cbcbcb;border-right:1px solid #cbcbcb;">
                                            Cheque No</th>
                                        <th width="140"
                                            style="width:100px;border-bottom:1px solid #cbcbcb;border-right:1px solid #cbcbcb;">
                                            Bank/Branch</th>
                                        <th width="100"
                                            style="width:100px;border-bottom:1px solid #cbcbcb;border-right:1px solid #cbcbcb;">
                                            Amount</th>
                                        <th width="140"
                                            style="width:100px;border-bottom:1px solid #cbcbcb;border-right:1px solid #cbcbcb;">
                                            Cash Received</th>
                                        <th width="{if $info.invoice_number!=''}120{else}180{/if}"
                                            style="width:100px;border-bottom:1px solid #cbcbcb;">Signature & Stamp</th>
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

                        </tr>

                    </table>
                </td>
            </tr>
        {/if}
    </table>
</body>

</html>