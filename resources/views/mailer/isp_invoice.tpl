{if $info.promotion_id>0}
    <table  id="promotion" style="margin:0 auto; font-family:Verdana, Arial;border: 1px solid #e2e2e2;width: 600px;margin-bottom: 5px;" align="center" width="600" border="0" cellspacing="0" cellpadding="10" >
        <tr>
            <td style="color:#5b4d4b; line-height:25px; border-bottom:1px dashed #e2e2e2; border-top:1px dashed #e2e2e2; text-align:right; font-size:12px;background-color: #18aebf;">
                <span style="float: left;">{$promotion}</span>
            </td>
        </tr>
    </table>
{/if}
<table   style="margin:0 auto; font-family:Verdana, Arial;border: 1px solid #e2e2e2;width: 600px;margin-bottom: 5px;" align="center" width="600" border="0" cellspacing="0" cellpadding="10" >
    <tr>
        <td style="color:#31708f; line-height:25px;   text-align:center; font-size:12px;background-color: #d9edf7;">
            <span style="text-align: center;">Please ignore this email if you have already paid this invoice.</span>
        </td>
    </tr>
</table>
<table  style="margin:0 auto; font-family:Verdana, Arial;width: 600px; border: 1px solid black;" align="center" width="600" border="0" cellspacing="0" cellpadding="10" >
    <tr>
        <td style="font-size:15px; line-height:30px;"><table width="100%" border="0" cellspacing="0" cellpadding="5" >
                <tr>
                    <td width="160" align="center" valign="top">
                        <img src="https://www.swipez.in/images/nologo.gif" style="max-width:200px;max-height:100px;" />
                    </td>
                    <td width="330" >
                        <span style="font-size:20px; color:#6e605d;">{$company_name}</span> 
                        {if $info.main_company_name!=''}
                            <span style="font-size:11px; display:block;  margin-bottom:1px;"> (An official franchisee of {$info.main_company_name})</span>
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
    <tr style="border-spacing: 0px;">
        <td style="text-align: center;background-color: #E5E5E5 ;font-size:18px;" >
               
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
                        <table width="290" border="0" cellspacing="0" cellpadding="5" style="color:#5b4d4b;font-size: 13px;line-height: 14px;">
                            {foreach from=$customer_breckup item=v}
                                {if $v.value!=''}
                                    <tr style="line-height: 17px;"><td> <b>{$v.column_name}</b> : {$v.value}</td></tr>
                                {/if}
                            {/foreach}
                            {if $landline!=""}
                                <tr style="line-height: 17px;"><td> <b>Landline no </b> : {$landline}</td></tr>
                            {/if}
                        </table>
                    </td>
                    <td>

                        <table width="290" border="0" cellspacing="0" cellpadding="5" style="color:#5b4d4b;font-size: 13px;line-height: 15px;">
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
                                                <td style="line-height: 17px;"> <b>{$v.column_name}</b> : <a target="_BlANK" href="{$v.value}">{$v.column_name}</a></td>
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
                            <tr style="line-height: 17px;"><td> <b>&nbsp;</b>&nbsp;</td></tr>
                        </table>
                    </td>
                </tr>
            </table>

        </td>

    </tr>

    {if $last_payment==NULL && $adjustment==NULL}
        <tr >
            <td>
                <table border="1" cellspacing="0" cellpadding="5" style="border-collapse: collapse;color:#5b4d4b;font-size: 13px;line-height: 20px;width: 100%;">
                    <tr >
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
        <tr >
            <td>
                <table border="1" cellspacing="0" cellpadding="5" style="border-collapse: collapse;color:#5b4d4b;font-size: 13px;line-height: 20px;width: 100%;">               <tr style="font-size: 12px;border-bottom:1px solid black !important;">
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
    {$is_annual=0}
    {$showtp=0}
    {$colspan=2}
    {foreach from=$particular item=v}
        {if $v.1.value!=''}
            {$is_annual=1}
            {$colspan=3}
        {/if}
    {/foreach}
    {foreach from=$particular item=v}
        {if $v.2.value!=''}
            {$showtp=1}
        {/if}
    {/foreach}
    {if isset($custom_particular)}
        {$column1=$custom_particular.0}
        {$column2=$custom_particular.1}
        {$column3=$custom_particular.2}
        {$column4=$custom_particular.3}
    {else}
        {$column1="Description"}
        {$column2="Annual Recurring<br> Charges"}
        {$column3="Time Period"}
        {$column4="Amount Rs."}
    {/if}
    <tr >
        <td>
            <div>
                <table width="100%" border="1" cellspacing="0"  cellpadding="5" style="border-collapse: collapse;color:#5b4d4b;font-size: 13px;line-height: 15px;">
                    <thead>
                        <tr style="background-color: #E5E5E5 ;">
                            <th style="width:20px;">Sr.</th>
                            <th  style="width:210px;">{$column1}</th>
                                {if $is_annual==1}
                                <th style="width: 100px;"> {$column2} </th>
                                {/if}
                            <th style="width: 100px;">{$column3}</th>
                            <th style="width: 160px;">{$column4}</th>

                        </tr>
                    </thead>
                    <tbody>
                        {$total=0}
                        {$taxtotal=0}
                        {$int=1}
                        {foreach from=$particular item=v}
                            {if {$v.0.value}!='' || {$v.3.value}>0}
                                <tr> 
                                    <td  style="" >
                                        {$int}
                                    </td>
                                    <td  style="">
                                        {$v.0.value}
                                    </td>
                                    {if $is_annual==1}
                                        <td  style="text-align: center;">
                                            {$v.1.value|number_format:2:".":","}
                                        </td>
                                    {/if}
                                    <td style="text-align: center;">
                                        {$v.2.value}
                                    </td>
                                    <td style="text-align: right;">
                                        {$v.3.value|number_format:2:".":","}
                                        {$total={$total}+{$v.3.value}}
                                    </td>
                                </tr>
                                {$int=$int+1}
                            {/if}
                        {/foreach}
                        <tr style="text-align: center;">
                            <td style="border-bottom: 0;" colspan="{$colspan}"></td>
                            <td style="width:130px;text-align: left;">SUB TOTAL</td>
                            <td style="width:110px;text-align: right;"> {$total|number_format:2:".":","}</td>
                        </tr>

                        {foreach from=$tax item=v}
                            {if $v.1.value!='' || $v.2.value!=''}
                                <tr style="text-align: center;">
                                    <td style="border-bottom: 0;border-top: 0;" colspan="{$colspan}"></td>
                                    <td style="width:130px;text-align: left;">{$v.0.value}</td>
                                    <td style="width:110px;text-align: right;">{$v.3.value|number_format:2:".":","} {$taxtotal={$taxtotal}+{$v.3.value}}</td>
                                </tr>
                            {/if}
                        {/foreach}
                        <tr style="text-align: center;">
                            <td style="border-top: 0;" colspan="{$colspan}"></td>
                            <td style="width:130px;text-align: left;"><b>Total Rs.</b></td>
                            <td style="width:110px;text-align: right;"><b> {($basic_total+$tax_total)|number_format:2:".":","}</b></td>
                        </tr>
                        {if $advance>0}
                            <tr style="text-align: center;">
                                <td style="border-top:0px;" colspan="{$colspan}" ></td>
                                <td style="width:130px;text-align: left"><b>Advance received</b></td>
                                <td style="width:110px;text-align: right;"><b>{($advance)|number_format:2:".":","}</b></td>
                            </tr>
                            <tr style="text-align: center;">
                                <td style="border-top:0px;" colspan="{$colspan}" ></td>
                                <td style="width:130px;text-align: left"><b>Balance</b></td>
                                <td style="width:110px;text-align: right;"><b>{($grand_total)|number_format:2:".":","}</b></td>
                            </tr>
                        {/if}

                        <tr style="text-align: center;">
                            <td style="text-align: left;font-size: 10px;border-bottom: 0;" rowspan="3" colspan="{$colspan}">
                                {if !empty($tnc)}
                                    <b>Terms & Conditions</b><br>
                                    {foreach from=$tnc item=v}
                                        {$v.column_name}<br>
                                    {/foreach}
                                {/if}
                            </td>
                            {if $pan!=''}
                                <td style="width:130px;text-align: left;">PAN NO.</td>
                                <td style="width:110px;text-align: left;">{$pan}</td>
                            {/if}
                        </tr>
                        <tr style="text-align: center;">
                            {if $tan!=''}
                                <td style="width:130px;text-align: left;">TAN NO.</td>
                                <td style="width:110px;text-align: left;"> {$tan}</td>
                            {/if}
                        </tr>
                        <tr style="text-align: center;">
                            {if $cin_no!=''}
                                <td style="width:130px;text-align: left;">CIN NO.</td>
                                <td style="width:110px;text-align: left;">{$cin_no}</td>
                            {/if}
                        </tr>
                        <tr style="text-align: center;">
                            {if $gst_number!=''}
                                <td colspan="{$colspan}" style="text-align: center;border-bottom: 0;border-top: 0;"></td>
                                <td style="width:130px;text-align: left;">GST Number</td>
                                <td style="width:110px;text-align: left;">{$gst_number}</td>
                            {else}
                                {if $registration_number!=''}
                                    <td colspan="{$colspan}" style="text-align: center;border-bottom: 0;border-top: 0;"></td>
                                    <td style="width:130px;text-align: left;">S. Tax Regn.</td>
                                    <td style="width:110px;text-align: left;">{$registration_number}</td>
                                {/if}
                            {/if}
                        </tr>
                        <tr style="text-align: center;">
                            <td colspan="5" style="text-align: left;">
                                <b> * Note: </b>This  is a system generated invoice. No signature required.
                            </td>

                        </tr>
                    </tbody>



                </table>
            </div>
        </td>
    </tr>

    <tr>
        <td style="color:#5b4d4b; line-height:30px;   text-align:right; font-size:15px;">

            <span style="float: right;">Bill Total : Rs. {$invoice_total|number_format:2:".":","}</span>
            <br>
            {if $advance>0}
                <span style="float: right;">Advance : Rs.{$advance|number_format:2:".":","}</span>
                <br>
                <span style="float: right;">Balance : Rs. {$grand_total|number_format:2:".":","}</span>  
            {else}
                <span style="float: right;">Grand Total : Rs. {$grand_total|number_format:2:".":","}</span>  
            {/if}
        </td>
    </tr>

    {if $info.coupon_id>0}
        <tr id="couponapplied">
            <td style="color:#5b4d4b; line-height:30px; text-align:right; font-size:15px;background-color: #fcf8e3;">

                <span style="float: left;"> Coupon discount : <b>{if $coupon_details.type==1} Rs.{$coupon_details.fixed_amount}/- {else}{$coupon_details.percent}%{/if}</b></span>
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
            <a href="{$url}" target="_blank" style="font-family: Arial, Helvetica, sans-serif; font-size: 13px;background-color: #18aebf;color: #FFFFFF;padding: 7px 10px 7px 10px;text-decoration: none;font-family: Open Sans,sans-serif;float:right;">
                <font face="Arial, Helvetica, sans-seri; font-size: 13px;" size="3">
                {if $grand_total>0 && $legal_complete==1 && $info.invoice_type!=2}Pay now{else}View{/if}</font>
            </a>
            <a href="{$pdf_url}" style="font-family: Arial, Helvetica, sans-serif; font-size: 13px;background-color: #f99636;color: #FFFFFF;padding: 7px 10px 7px 10px;text-decoration: none;font-family: Open Sans,sans-serif;float:right;margin-right: 10px;">
                <font face="Arial, Helvetica, sans-seri; font-size: 13px;" size="3">
                Save as PDF </font>
            </a>
        </td>
    </tr>

    {if $info.paid_user==0}
    <tr>
        <td style="color:#5b4d4b; line-height:25px; text-align:right; font-size:12px;background-color: #18aebf;color: #ffffff;">
            <span style="float: left;">If you would like to collect online payments for your business, <a target="_BLANK" href="https://www.swipez.in/merchant/register">register now</a> on Swipez.</span>
        </td>
    </tr>
    {/if}
    {if $info.show_ad==1}
        <tr id="campaigndiv">
            <td style="font-size:15px; color:#fb735d; line-height:30px; ">
                <table width="100%" border="0" cellspacing="0" cellpadding="5">
                    <tr>
                        <td valign="top" style="border-bottom:1px dashed #e2e2e2;">
                            <a target="_BLANK" href="{$camp_link}"><img src="https://h7sak8am43.swipez.in/images/adv/ditto.jpg"  style="display: inline;max-width: 100%;"/></a>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    {/if}
    <tr>
        <td style="font-size:15px; color:#fb735d; line-height:30px; "><table width="100%" border="0" cellspacing="0" cellpadding="5">
                <tr>
                    <td valign="top" style="">
                        <div style="font-size:13px; color:#6E605D;"> 
                            If you are having trouble viewing this invoice in your email, you can use this link to view the same invoice  
                            {$url}
                        </div>
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        <div style="font-size:13px; color:#6E605D;"> 
                            If you do not recognize the merchant - {$company_name} OR have a query regarding this request, please <a href="mailto:support@swipez.in?Subject=Query regarding the Payment Request" class="example5"> contact us.</a> 
                        </div>
                    </td>
                </tr>
            </table></td>
    </tr>
    <tr>
        <td style="font-size:13px;  line-height:30px; ">
            <table width="100%" border="0" cellspacing="0" cellpadding="5">
                <tr>
                    <td valign="top" style="">
                        If you would prefer not receiving our emails, please <a target="_BLANK" href="{$unsub_link}">click here</a> to unsubscribe.
                    </td>
                </tr>

            </table>
        </td>
    </tr>
</table>