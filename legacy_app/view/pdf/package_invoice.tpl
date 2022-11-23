{strip}
<html>
    <body>
        <table  style="margin:0 auto; font-family:Verdana, Arial;width: 800px; border: 1px solid #e2e2e2;border-bottom: 0px;" align="center" width="800" border="0" cellspacing="0" cellpadding="10" >
            <tr>
                <td style="font-size:15px; line-height:30px;"><table width="100%" border="0" cellspacing="0" cellpadding="5" >
                        <tr>
                            <td width="200" align="center" valign="top">
                                <img src="/assets/admin/layout/img/logo.png"  alt="Swipez Online Payment" title="Swipez Online Payment Solutions"  style="max-width:160px;max-height:100px;" />
                            </td>
                            <td width="310" style="" >
                                <span style="font-size:25px; color:#6e605d;">Swipez</span> <br>
                                <span style="font-size:13px; display:block; margin-top:5px; margin-bottom:1px;line-height: 16px;">Swipez 91springboard, Sky Loft, Creaticity Mall, Opp Golf Course, Shastrinagar, Pune 411006  <br>
                                    Contact: +91 741 497 3338</span>

                                <span style="font-size:13px; display:block; margin-top:1px; margin-bottom:1px;line-height: 16px;"> E-mail: contactus@swipez.in<br></span>

                            </td>
                            <td width="100" align="center" valign="top">
                            </td>
                        </tr>
                    </table>  </td>
            </tr>
            <tr style="border-spacing: 0px;">
                <td style="text-align: center;background-color: #E5E5E5 ;border-top: 1px solid grey;border-bottom: 1px solid grey;font-size:18px;" >

                    {if $info.invoice_type==2}
                        <b>{$plugin.invoice_title}</b>
                    {else}
                        <b>{if !empty($tax)}TAX {/if}INVOICE</b>
                    {/if}

                </td>
            </tr>
            <tr>
                <td style="font-size:11px;  border-bottom:1px #e2e2e2;">
                    <table border="0" cellspacing="0" cellpadding="5" style="font-size: 13px;line-height: 15px;width: 100%;">
                        <tr >
                            <td style="font-size:11px;  border-bottom:1px #e2e2e2;border-right: 1px solid #e2e2e2; width:380px;">
                                <div style="float:left;line-height: 10px;  margin-right:5px;">
                                    <table width="360" border="0" cellspacing="0" cellpadding="5" style="color:#5b4d4b;font-size: 13px;line-height: 14px;">
                                        <tr><td width="120" style="border-bottom: 1px solid #e2e2e2;border-right: 1px solid #e2e2e2;"><b>Merchant name</b></td><td style="border-bottom: 1px solid #e2e2e2;">{$detail.name}</td></tr>
                                        <tr><td style="border-bottom: 1px solid #e2e2e2;border-right: 1px solid #e2e2e2;"><b>Email ID</b></td><td style="border-bottom: 1px solid #e2e2e2;">{$detail.email}</td></tr>
                                        <tr><td style="border-bottom: 1px solid #e2e2e2;border-right: 1px solid #e2e2e2;"><b>Mobile No.</b></td><td style="border-bottom: 1px solid #e2e2e2;">{$detail.mobile}</td></tr>
                                        <tr><td style="border-bottom: 1px solid #e2e2e2;border-right: 1px solid #e2e2e2;"><b>Address</b></td><td style="border-bottom: 1px solid #e2e2e2;">{$detail.address}</td></tr>
                                        </tbody>
                                    </table>
                                </div>
                            </td>
                            <td style="font-size:11px;  border-bottom:1px #e2e2e2;border-left: 1px solid grey; width:380px;">
                                <div style="float: right; padding-left: 1px;">
                                    <table width="360" border="0" cellspacing="0" cellpadding="5" style="color:#5b4d4b;font-size: 13px;line-height: 14px;">
                                        <tr><td style="border-bottom: 1px solid #e2e2e2;border-right: 1px solid #e2e2e2;"><b>Bill date</b></td><td style="border-bottom: 1px solid #e2e2e2;">{$info.created_date|date_format:"%d-%m-%Y"}</td></tr>
                                        <tr><td style="border-bottom: 1px solid #e2e2e2;border-right: 1px solid #e2e2e2;"><b>Transaction ID</b></td><td style="border-bottom: 1px solid #e2e2e2;">{$info.package_transaction_id}</td></tr>
                                        </tbody>
                                    </table>
                                </div></td></tr></table>

                </td>
            </tr>


            <tr>
                <td style="font-size:11px;  border-bottom:1px #e2e2e2;" colspan="2">
                    <table width="800" style="color:#5b4d4b;font-size: 14px;line-height: 16px;">
                        {$is_annual=0}
                        {$colspan=2}
                        <thead>
                            <tr style="background-color: #E5E5E5 ;">
                                <th width="120" style="border-bottom: 1px solid #e2e2e2;border-right: 1px solid #e2e2e2;">Sr.</th>
                                <th width="360" style="border-bottom: 1px solid #e2e2e2;border-right: 1px solid #e2e2e2;">Description</th>
                                <th width="170" style="border-bottom: 1px solid #e2e2e2;border-right: 1px solid #e2e2e2;">Time Period</th>
                                <th width="140" style="border-bottom: 1px solid #e2e2e2;border-right: 1px solid #e2e2e2;">Total </th>
                            </tr>
                        </thead>
                        <tr> 
                            <td class="col-md-1 td-c" style="border-bottom: 1px solid #e2e2e2;border-right: 1px solid #e2e2e2;" >
                                1
                            </td>
                            <td class="col-md-5" style="border-bottom: 1px solid #e2e2e2;border-right: 1px solid #e2e2e2;">
                                {$info.narrative}
                            </td>
                            <td class="col-md-3 td-c" align="middle" style="border-bottom: 1px solid #e2e2e2;border-right: 1px solid #e2e2e2;">
                                12 Months
                            </td>
                            <td class="col-md-3 td-c" align="middle" style="border-bottom: 1px solid #e2e2e2;border-right: 1px solid #e2e2e2;">
                                {$info.base_amount}
                            </td>
                        </tr>
                        {$int=2}
                        {foreach from=$addon item=v}
                            {if $v.package_id!=$info.package_id}
                                <tr> 
                                    <td class="col-md-1 td-c" style="border-bottom: 1px solid #e2e2e2;border-right: 1px solid #e2e2e2;" >
                                        {$int}
                                    </td>
                                    <td class="col-md-5" style="border-bottom: 1px solid #e2e2e2;border-right: 1px solid #e2e2e2;">
                                        {if $v.license_bought>1}
                                            {$v.package_name} ({$v.license_bought} SMS)
                                        {else}
                                            {$v.package_name}
                                        {/if}
                                    </td>
                                    <td class="col-md-3 td-c" align="middle" style="border-bottom: 1px solid #e2e2e2;border-right: 1px solid #e2e2e2;">
                                        12 Months
                                    </td>
                                    <td class="col-md-3 td-c" align="middle" style="border-bottom: 1px solid #e2e2e2;border-right: 1px solid #e2e2e2;">
                                        Free
                                    </td>
                                </tr>
                                {$int=$int+1}
                            {/if}
                        {/foreach}
                        <tr> 
                            <td class="col-md-1" style="border-top: 0;border-bottom: 0;" >
                            </td>
                            <td class="col-md-5" style="border-top: 0;border-bottom: 0;">
                            </td>
                            <td class="col-md-3" style="border-top: 0;border-bottom: 0;">
                            </td>
                            <td class="col-md-3" style="border-top: 0;border-bottom: 0;">
                            </td>
                        </tr>

                        {$colspan=2}
                        <tr>
                            <td colspan="{$colspan}" class="col-md-8" style="border-bottom: 0;">

                            </td>
                            <td class="col-md-2" style="border-left: 1px solid #e2e2e2;border-bottom: 1px solid #e2e2e2;border-right: 1px solid #e2e2e2;">
                                <b>SUB TOTAL</b>
                            </td>
                            <td class="col-md-2 td-c" align="right" style="border-bottom: 1px solid #e2e2e2;border-right: 1px solid #e2e2e2;">
                                <b>  {$info.base_amount|number_format:2:".":","}</b>
                            </td>
                        </tr>
                        {if $info.discount>0}
                            <tr>
                                <td colspan="{$colspan}" class="col-md-8" style="border-bottom: 0;">

                                </td>
                                <td class="col-md-2" style="border-left: 1px solid #e2e2e2;border-bottom: 1px solid #e2e2e2;border-right: 1px solid #e2e2e2;">
                                    <b>Discount</b>
                                </td>
                                <td class="col-md-2 td-c"  align="right" style="border-bottom: 1px solid #e2e2e2;border-right: 1px solid #e2e2e2;">
                                    <b>  {$info.discount|number_format:2:".":","}</b>
                                </td>
                            </tr>
                        {/if}
                        {if !empty($tax)}
                            <tr>
                                <td colspan="{$colspan}" class="col-md-8" style="border-top: 0;border-bottom: 0;">

                                </td>
                                <td class="col-md-2" style="border-left: 1px solid #e2e2e2;border-bottom: 1px solid #e2e2e2;border-right: 1px solid #e2e2e2;">
                                    {$tax.0}
                                </td>
                                <td rowspan="2" class="col-md-2 td-c" align="right"  style="border-bottom: 1px solid #e2e2e2;border-right: 1px solid #e2e2e2;">
                                    <b>{$info.tax_amount}</b>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="{$colspan}" class="col-md-8" style="border-top: 0;border-bottom: 0;">

                                </td>
                                <td class="col-md-2"  style="border-left: 1px solid #e2e2e2;border-bottom: 1px solid #e2e2e2;border-right: 1px solid #e2e2e2;">
                                    {$tax.1}
                                </td>

                            </tr>
                        {/if}
                        <tr>
                            <td colspan="{$colspan}" class="col-md-8" style="border-bottom: 0;">

                            </td>
                            <td class="col-md-2"   style="border-left: 1px solid #e2e2e2;border-bottom: 1px solid #e2e2e2;border-right: 1px solid #e2e2e2;">
                                <b>TOTAL {$info.currency_icon}</b>
                            </td>
                            <td class="col-md-2 td-c" align="right"  style="border-bottom: 1px solid #e2e2e2;border-right: 1px solid #e2e2e2;">
                                <b> {($info.amount)|number_format:2:".":","}</b>
                            </td>
                        </tr>
                        <tr>
                            <td  class="col-md-8" style="border: 1px solid #e2e2e2;">
                                <b>Amount (in words)</b> 
                            </td>

                            <td colspan="3" class="col-md-4" style="border: 1px solid #e2e2e2;">
                                {$money_words}
                            </td>
                        </tr>
                        <tr>
                            <td rowspan="2" colspan="{$colspan}" class="col-md-8" style="border-bottom: 0;font-size: 11px;">
                            </td>
                            <td class="col-md-2" style="border-left: 1px solid #e2e2e2;border-bottom: 1px solid #e2e2e2;border-right: 1px solid #e2e2e2;">
                                PAN NO.
                            </td>
                            <td class="col-md-2" style="border-bottom: 1px solid #e2e2e2;border-right: 1px solid #e2e2e2;">
                                AABCO8934B
                            </td>
                        </tr>
                        <tr>
                            <td class="col-md-2"style="border-left: 1px solid #e2e2e2;border-bottom: 1px solid #e2e2e2;border-right: 1px solid #e2e2e2;">
                                GST Number
                            </td>
                            <td class="col-md-2" style="border-bottom: 1px solid #e2e2e2;border-right: 1px solid #e2e2e2;">
                                27AABCO8934B2ZH
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="col-md-12" style="border-bottom: 0;">
                                <b> * Note: </b>This  is a system generated invoice. No signature required.
                            </td>
                        </tr>

                    </table>
                </td></tr>
            <tr>
                <td colspan="2" style="border-bottom:1px solid #e2e2e2;">
                    <table width="800" style="border:1px solid #e2e2e2;color:#5b4d4b;" class="table table-bordered table-condensed">
                        <tbody><tr>
                                <th width="120" style="border-bottom:1px solid #e2e2e2;border-right:1px solid #e2e2e2;" >Receipt no.</th>
                                <th width="200" style="border-bottom:1px solid #e2e2e2;border-right:1px solid #e2e2e2;">Receipt date</th>
                                <th width="200" style="border-bottom:1px solid #e2e2e2;border-right:1px solid #e2e2e2;">Customer</th>
                                <th width="160" style="border-bottom:1px solid #e2e2e2;border-right:1px solid #e2e2e2;">Payment method</th>
                                <th width="120" style="border-bottom:1px solid #e2e2e2;">Paid amount</th>
                            </tr>
                            <tr>
                                <td style="border-right:1px solid #e2e2e2;">{$info.package_transaction_id}</td>
                                <td style="border-right:1px solid #e2e2e2;">{$info.created_date}</td>
                                <td style="border-right:1px solid #e2e2e2;">{$detail.name}</td>
                                <td style="border-right:1px solid #e2e2e2;">Online paid</td>
                                <td style="">{$info.amount}</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>









</body>

</html>
{/strip}