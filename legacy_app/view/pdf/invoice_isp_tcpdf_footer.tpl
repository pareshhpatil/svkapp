<table autosize="1" width="99%" border="0" cellspacing="1" cellpadding="0" style="border:1px solid #cbcbcb;">
            <tr>
                <td>
                    <table autosize="1" width="100%" border="0" cellspacing="0" cellpadding="2"
                        style="color:#5b4d4b;font-size:9px;line-height:12px;text-align:left;">
                        {if $info.narrative!=''}
                            <tr>
                                <b>Narrative</b>
                                <span style="font-size:9px;">{$info.narrative}</span>
                            </tr>
                        {/if}
                        {if $tnc!=''}
                            <tr>
                                <td>
                                    <b>Terms & Conditions</b>
                                    <span style="font-size:9px;font-family:Verdana;padding:20px;">{$tnc}</span>
                                </td>
                            </tr>
                        {/if}
                    </table>
                </td>
                <td style="border-left:1px solid #cbcbcb;">
                    <table autosize="1" width="99%" border="0" cellspacing="0" cellpadding="2"
                        style="border-right:1px solid #cbcbcb;color:#5b4d4b;font-size:9px;line-height:12px;text-align:center;">
                        <tr>
                            <td style="border-bottom:1px solid #cbcbcb;border-right:1px solid #cbcbcb;text-align:right;">
                                <b>Sub Total</b>
                            </td>
                            <td style="border-bottom:1px solid #cbcbcb;text-align: right;" class="col-md-2 td-c">
                                <b> {$info.basic_amount|number_format:2:".":","}</b>
                            </td>
                        </tr>
                        {foreach from=$tax item=v}
                            <tr style="text-align: center;">
                                <td style="border-bottom:1px solid #cbcbcb;border-right:1px solid #cbcbcb;text-align:right;">
                                    {$v.tax_name}
                                </td>
                                <td style="border-bottom:1px solid #cbcbcb;text-align: right;">
                                    {$v.tax_amount}
                                    {$taxtotal={$taxtotal}+{$v.tax_amount}}
                                </td>
                            </tr>
                        {/foreach}
                        <tr style="text-align: center;">
                            <td style="border-bottom:1px solid #cbcbcb;border-right:1px solid #cbcbcb;text-align:right;">
                                <b>Total </b>
                            </td>
                            <td style="border-bottom:1px solid #cbcbcb;text-align:right;"><b>
                            {$info.currency_icon} {($info.basic_amount+$tax_total)|number_format:2:".":","}</b></td>
                        </tr>
                        {if $paid_amount>0}
                        <tr style="text-align: center;">
                            <td style="border-bottom:1px solid #cbcbcb;border-right:1px solid #cbcbcb;text-align:right;">
                                Paid amount {$info.currency_icon}
                            </td>
                            <td style="border-bottom:1px solid #cbcbcb;text-align:right;"><b>
                            {$info.currency_icon} {($paid_amount)|number_format:2:".":","}</b></td>
                        </tr>
                        {/if}
                        {if $previous_due>0}
                            <tr style="text-align: center;">
                                <td style="border-bottom:1px solid #cbcbcb;border-right:1px solid #cbcbcb;text-align: right;">
                                    {$previous_due_col}</td>
                                <td style="border-bottom: 1px solid #cbcbcb;text-align: right;">
                                    {$previous_due|number_format:2:".":","}</td>
                            </tr>
                            <tr style="text-align: center;">
                                <td style="border-bottom:1px solid #cbcbcb;border-right:1px solid #cbcbcb;text-align: right;">
                                    <b>Grand total</b>
                                </td>
                                <td style="border-bottom:1px solid #cbcbcb;text-align:right;"><b>
                                {$info.currency_icon} {$info.grand_total|number_format:2:".":","}</b></td>
                            </tr>
                        {/if}
                        {if $adjustment>0}
                            <tr style="text-align: center;">
                                <td style="border-bottom:1px solid #cbcbcb;border-right:1px solid #cbcbcb;text-align:right;">
                                    {$adjustment_col}</td>
                                <td style="border-bottom: 1px solid #cbcbcb;text-align: right;">
                                    {$adjustment|number_format:2:".":","}</td>
                            </tr>
                            <tr style="text-align: center;">
                                <td style="border-bottom:1px solid #cbcbcb;border-right:1px solid #cbcbcb;text-align:right;">
                                    <b>Grand total</b>
                                </td>
                                <td style="border-bottom:1px solid #cbcbcb;text-align: right;"><b>
                                {$info.currency_icon} {$info.grand_total|number_format:2:".":","}</b></td>
                            </tr>
                        {/if}
                        {if $total != $absolute_cost}
                            <tr style="text-align:center;">
                            <td style="border-bottom:1px solid #cbcbcb;border-right: 1px solid #cbcbcb;text-align:right;">
                                <b>Grand Total</b>
                            </td>
                            <td style="border-bottom:1px solid #cbcbcb;text-align: right;"><b>
                            {$info.currency_icon} {($absolute_cost)|number_format:2:".":","}</b></td>
                            </tr>
                        {/if}


                        {if $advance>0}
                            <tr style="text-align: center;">
                                <td style="border-bottom:1px solid #cbcbcb;border-right:1px solid #cbcbcb;text-align: right">
                                    <b>Advance received</b>
                                </td>
                                <td style="border-bottom:1px solid #cbcbcb;text-align:right;">
                                    <b>{($advance)|number_format:2:".":","}</b>
                                </td>
                            </tr>
                            <tr style="text-align: center;">
                                <td style="border-bottom:1px solid #cbcbcb;border-right:1px solid #cbcbcb;text-align:right">
                                    <b>Balance</b>
                                </td>
                                <td style="border-bottom:1px solid #cbcbcb;text-align:right;">
                                    <b>{$info.currency_icon} {($grand_total)|number_format:2:".":","}</b>
                                </td>
                            </tr>
                        {/if}
                        {if $pan!=''}
                            <tr style="text-align: center;">
                                <td style="border-bottom:1px solid #cbcbcb;border-right:1px solid #cbcbcb;text-align:left;">
                                    PAN NO.</td>
                                <td style="border-bottom:1px solid #cbcbcb;text-align:right;">{$pan}</td>
                            </tr>
                        {/if}
                        {if $tan!=''}
                            <tr style="text-align: center;">
                                <td style="border-bottom:1px solid #cbcbcb;border-right:1px solid #cbcbcb;text-align:left;">
                                    TAN NO.</td>
                                <td style="border-bottom:1px solid #cbcbcb;text-align:right;"> {$tan}</td>
                            </tr>
                        {/if}
                        {if $cin_no!=''}
                            <tr style="text-align: center;">
                                <td style="border-bottom:1px solid #cbcbcb;border-right:1px solid #cbcbcb;text-align:left;">
                                    CIN NO.</td>
                                <td style="border-bottom:1px solid #cbcbcb;text-align:right;">{$cin_no}</td>
                            </tr>
                        {/if}
                        {if $gst_number!=''}
                            <tr style="text-align: center;">
                                <td style="text-align:left;border-bottom:1px solid #cbcbcb;border-right: 1px solid #cbcbcb;">
                                    GST Number</td>
                                <td style="text-align:right;border-bottom:1px solid #cbcbcb;">{$gst_number}</td>
                            </tr>
                        {else}
                            {if $registration_number!=''}
                                <tr style="text-align: center;">
                                    <td style="text-align:left;border-bottom:1px solid #cbcbcb;border-right: 1px solid #cbcbcb;">
                                        S. Tax Regn.</td>
                                    <td style="text-align:right;border-bottom:1px solid #cbcbcb;">{$registration_number}
                                    </td>
                                </tr>
                            {/if}
                        {/if}
                        <tr>
                            <td colspan="2" style="text-align:left;border-bottom: 1px solid #cbcbcb;">
                                <span><b>Amount (in words) :</b> {$money_words}</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>