{strip}
    <html>
    {$scn=0}

    <body>
        <table style="margin:0 auto; font-family:Verdana, Arial;width: 800px; border: 1px solid black;" align="center"
            width="800" border="0" cellspacing="0" cellpadding="10">
            <tr>
                <td style="font-size:20px; line-height:30px;">
                    <table width="100%" border="0" cellspacing="0" cellpadding="5">
                        <tr>
                            <td width="200" align="center" valign="top">
                                {if {$image_path}!=''}
                                    <img src="/uploads/images/logos/{$image_path}" style="max-width:160px;max-height:100px;" />
                                {/if}
                            </td>
                            <td width="340" style="">
                                <span style="font-size:24px; color:#6e605d;">{$company_name}</span> <br>
                                {if $info.main_company_name!=''}
                                    <span
                                        style="font-size:12px; display:block; margin-top:5px; margin-bottom:1px;line-height: 16px;">
                                        (An official franchisee of {$info.main_company_name})</span><br>
                                {/if}
                                {foreach from=$main_header item=v}
                                    {if $v.column_name!='Company name' && $v.value!=''}
                                        <span
                                            style="font-size:13px; display:block; margin-top:5px; margin-bottom:1px;line-height: 16px;">{if $v.column_name!='Merchant address'}{{$v.column_name|replace:'Merchant ':''}|ucfirst}:{/if}
                                            {$v.value}</span><br>
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
                    style="line-height: 1;text-align: center;background-color: {if !empty($info.design_color)}{$info.design_color}{else}#E5E5E5{/if} ;{if !empty($info.design_color)}color:white;{/if} border-top: 1px solid grey;border-bottom: 1px solid grey;font-size:18px;">

                    {if $info.invoice_type==2}
                        {$plugin.invoice_title}
                    {else}
                        {if !empty($tax)}TAX {/if}INVOICE
                    {/if}

                </td>
            </tr>
            <tr>
                <td style="font-size:13px;  border-bottom:1px #e2e2e2;">
                    <table border="0" cellspacing="0" cellpadding="5"
                        style="font-size: 13px;line-height: 15px;width: 100%;">
                        <tr>
                            <td
                                style="font-size:13px;  border-bottom:1px #e2e2e2;border-right: 1px solid #e2e2e2; width:380px;">
                                <div style="float:left;line-height: 10px;  margin-right:5px;">
                                    <table width="360" border="0" cellspacing="0" cellpadding="5"
                                        style="color:#5b4d4b;font-size: 13px;line-height: 14px;">
                                        {foreach from=$customer_breckup item=v}
                                            <tr>
                                                <td style="border-bottom: 1px solid #e2e2e2;border-right: 1px solid #e2e2e2;">
                                                    <b>{$v.column_name}</b>
                                                </td>
                                                <td style="border-bottom: 1px solid #e2e2e2;">{$v.value}</td>
                                            </tr>
                                        {/foreach}

                                        </tbody>
                                    </table>
                                </div>
                            </td>
                            <td
                                style="font-size:13px;  border-bottom:1px #e2e2e2;border-left: 1px solid grey; width:380px;">
                                <div style="float: right; padding-left: 1px;">
                                    <table width="360" border="0" cellspacing="0" cellpadding="5"
                                        style="color:#5b4d4b;font-size: 13px;line-height: 14px;">

                                        {foreach from=$header item=v}
                                            {if $v.position=="R" && $v.value!=""}
                                                <tr>
                                                    <td style="border-bottom: 1px solid #e2e2e2;border-right: 1px solid #e2e2e2;">
                                                        <b>{$v.column_name}</b>
                                                    </td>
                                                    {if {$v.value|substr:0:7}=='http://' || {$v.value|substr:0:8}=='https://'}
                                                        <td> <a target="_BlANK" href="{$v.value}">{$v.column_name}</a></td>
                                                    {else}
                                                        <td style="border-bottom: 1px solid #e2e2e2;">
                                                            {$v.value}
                                                        </td>
                                                    {/if}

                                                </tr>
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
            {if !empty($bds_column) && isset($properties.vehicle_det_section)}
                <tr>
                    <td style="font-size:13px;  border-bottom:1px #e2e2e2;padding-top:0;">
                        <span style="color: {if !empty($info.design_color)}{$info.design_color}{else}#5b4d4b{/if};font-size: 14px;">
                            {if isset($properties.vehicle_det_section.title)}{$properties.vehicle_det_section.title}
                            {else}Vehicle
                            details{/if}</span>
                        <table border="0" cellspacing="0" cellpadding="5"
                            style="font-size: 13px;line-height: 15px;width: 100%;">
                            <tr>
                                <td
                                    style="font-size:13px;  border-bottom:1px #e2e2e2;border-right: 1px solid #e2e2e2; width:380px;">
                                    <div style="float:left;line-height: 10px;  margin-right:5px;">
                                        <table width="360" border="0" cellspacing="0" cellpadding="5"
                                            style="color:#5b4d4b;font-size: 13px;line-height: 14px;">
                                            {foreach from=$bds_column item=v}
                                                {if $v.position=="L" && $v.value!=""}
                                                    <tr>
                                                        <td style="border-bottom: 1px solid #e2e2e2;border-right: 1px solid #e2e2e2;">
                                                            <b>{$v.column_name}</b>
                                                        </td>
                                                        <td style="border-bottom: 1px solid #e2e2e2;">
                                                            {$v.value}
                                                        </td>
                                                    </tr>
                                                {/if}
                                            {/foreach}

                                            </tbody>
                                        </table>
                                    </div>
                                </td>
                                <td
                                    style="font-size:13px;  border-bottom:1px #e2e2e2;border-left: 1px solid grey; width:380px;">
                                    <div style="float: right; padding-left: 1px;">
                                        <table width="360" border="0" cellspacing="0" cellpadding="5"
                                            style="color:#5b4d4b;font-size: 13px;line-height: 14px;">

                                            {foreach from=$bds_column item=v}
                                                {if $v.position=="R" && $v.value!=""}
                                                    <tr>
                                                        <td style="border-bottom: 1px solid #e2e2e2;border-right: 1px solid #e2e2e2;">
                                                            <b>{$v.column_name}</b>
                                                        </td>
                                                        <td style="border-bottom: 1px solid #e2e2e2;">
                                                            {$v.value}
                                                        </td>
                                                    </tr>
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
            {/if}

            {if isset($properties.vehicle_section) && !empty($particular)}
                <tr>
                    <td style="padding-top:0;">
                        <span style="color: {if !empty($info.design_color)}{$info.design_color}{else}#5b4d4b{/if};font-size: 14px;">{$properties.vehicle_section.title}</span>
                        <div>
                            <table width="100%" border="0" cellspacing="0" cellpadding="5"
                                style="border: 1px solid grey;color:#5b4d4b;font-size: 13px;line-height: 15px;text-align: center;">
                                <thead>
                                    <tr style="background-color: #E5E5E5 ;">
                                        {foreach from=$particular_column item=v}
                                            <th style="border-bottom: 1px solid grey;border-right: 1px solid grey;">
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
                                                    <td style="border-right: 1px solid #e2e2e2;">
                                                        {$int}
                                                        {$colspan=$colspan+1}
                                                    </td>
                                                {else}
                                                    {if $k!='narrative'}
                                                        {$colspan=$colspan+1}
                                                    {else}
                                                        {$narrative=1}
                                                    {/if}
                                                    <td style="border-right: 1px solid #e2e2e2;">
                                                        {$dp.{$k}}
                                                    </td>
                                                {/if}
                                                {if $k=='total_amount'}
                                                    {$total_amount=$total_amount+{$dp.{$k}}}
                                                {/if}
                                            {/foreach}
                                        </tr>
                                        {$int=$int+1}
                                    {/foreach}
                                    {$colspan=$colspan-2}
                                </tbody>
                                <thead>
                                    <tr>
                                        <td style="border-top: 1px solid grey;text-align: right;" colspan="{$colspan}">
                                            <b>Total  ({$sec_col.{$scn}}{$scn=$scn+1})</b>
                                        </td>
                                        <td
                                            style="border-top: 1px solid grey;border-left: 1px solid #e2e2e2;border-right: 1px solid #e2e2e2;border-bottom: 1px solid #e2e2e2;">
                                            <b> {$info.currency_icon} {$total_amount|number_format:2:".":","}</b>
                                        </td>
                                        {if $narrative==1}
                                            <td style="border-top: 1px solid grey;border-bottom: 1px solid #e2e2e2;"> </td>
                                        {/if}
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </td>
                </tr>
            {/if}
            {if in_array(1,$secarray)}
                {$colspan=1}
                {$amount=0}
                {$charge=0}
                <tr>
                    <td style="padding-top:0;">
                        <span style="color: {if !empty($info.design_color)}{$info.design_color}{else}#5b4d4b{/if};font-size: 14px;">{$properties.travel_section.title}</span>
                        <div>
                            <table width="100%" border="0" cellspacing="0" cellpadding="5"
                                style="border: 1px solid grey;color:#5b4d4b;font-size: 13px;line-height: 15px;text-align: center;">
                                <thead>
                                    <tr style="background-color: #e2e2e2;">
                                        {foreach from=$properties.travel_section.column key=k item=v}
                                            {if $k=='charge'}
                                                {$colspan=$colspan+1}
                                            {/if}
                                            {if $k=='amount'}
                                                {$colspan=$colspan+1}
                                            {/if}
                                            <th style="border-bottom: 1px solid grey;border-right: 1px solid grey;">
                                                {$v}
                                            </th>
                                        {/foreach}
                                    </tr>
                                </thead>
                                <tbody>
                                    {$int=1}
                                    {$total_amount=0}
                                    {foreach from=$ticket_detail item=v}
                                        {if $v.type==1}
                                            <tr>
                                                {foreach from=$properties.travel_section.column key=k item=tb}
                                                    <td style="border-right: 1px solid #e2e2e2;">
                                                        {if $k=='sr_no'}
                                                            {$int}
                                                        {elseif $k=='booking_date' || $k=='journey_date'}
                                                             {if $setting.has_datetime=='1'}
                                                            {$v.{$k}|date_format:"%d %b %Y %H:%M %p"}
                                                            {else}
                                                              {$v.{$k}|date_format:"%d %b %Y"}
                                                            {/if}
                                                        {elseif $k=='type'}
                                                            {$v.vehicle_type}
                                                        {elseif $k=='from'}
                                                            {$v.from_station}
                                                        {elseif $k=='to'}
                                                            {$v.to_station}
                                                        {elseif $k=='total_amount'}
                                                            {$v.total}
                                                            {$total_amount=$total_amount+$v.total}
                                                        {elseif $k=='amount'}
                                                            {$v.amount}
                                                            {$amount=$amount+$v.amount}
                                                        {elseif $k=='charge'}
                                                            {$v.charge}
                                                            {$charge=$charge+$v.charge}
                                                        {else}
                                                            {$v.{$k}}
                                                        {/if}
                                                    </td>
                                                {/foreach}
                                            </tr>
                                            {$int=$int+1}
                                        {/if}
                                    {/foreach}
                                </tbody>
                                <thead>
                                    <tr>
                                        <td style="text-align: right;border-top: 1px solid grey;"
                                            colspan="{count($properties.travel_section.column)-$colspan}">
                                            <span style="text-align: right;"> Total ({$sec_col.{$scn}}{$scn=$scn+1})</span>
                                        </td>
                                        {foreach from=$properties.travel_section.column key=k item=v}
                                            {if $k=='amount'}
                                                <td style="text-align: right;border-top: 1px solid grey;"><b>
                                                        {$amount|number_format:2:".":","}</b></td>
                                            {/if}
                                            {if $k=='charge'}
                                                <td style="text-align: right;border-top: 1px solid grey;"><b>
                                                        {$charge|number_format:2:".":","}</b></td>
                                            {/if}
                                        {/foreach}
                                        <td style="border-top: 1px solid grey;"><b> {$info.currency_icon} {$total_amount|number_format:2:".":","}</b>
                                        </td>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </td>
                </tr>
            {/if}
            {if in_array(2,$secarray)}
                {$colspan=1}
                {$amount=0}
                {$charge=0}
                <tr>
                    <td style="padding-top:0;">
                        <span style="color: {if !empty($info.design_color)}{$info.design_color}{else}#5b4d4b{/if};font-size: 14px;">{$properties.travel_cancel_section.title}</span>
                        <div>
                            <table width="100%" border="0" cellspacing="0" cellpadding="5"
                                style="border: 1px solid grey;color:#5b4d4b;font-size: 13px;line-height: 15px;text-align: center;">
                                <thead>
                                    <tr style="background-color: #e2e2e2;">
                                        {foreach from=$properties.travel_cancel_section.column key=k item=v}
                                            {if $k=='charge'}
                                                {$colspan=$colspan+1}
                                            {/if}
                                            {if $k=='amount'}
                                                {$colspan=$colspan+1}
                                            {/if}
                                            <th style="border-bottom: 1px solid grey;border-right: 1px solid grey;">
                                                {$v}
                                            </th>
                                        {/foreach}
                                    </tr>
                                </thead>
                                {$int=1}
                                {$total_amount=0}
                                {foreach from=$ticket_detail item=v}
                                    {if $v.type==2}
                                        <tr>
                                            {foreach from=$properties.travel_cancel_section.column key=k item=tb}
                                                <td style="border-right: 1px solid #e2e2e2;">
                                                    {if $k=='sr_no'}
                                                        {$int}
                                                    {elseif $k=='booking_date' || $k=='journey_date'}
                                                        {if $setting.has_datetime=='1'}
                                                            {$v.{$k}|date_format:"%d %b %Y %H:%M %p"}
                                                            {else}
                                                              {$v.{$k}|date_format:"%d %b %Y"}
                                                            {/if}
                                                    {elseif $k=='type'}
                                                        {$v.vehicle_type}
                                                    {elseif $k=='from'}
                                                        {$v.from_station}
                                                    {elseif $k=='to'}
                                                        {$v.to_station}
                                                    {elseif $k=='total_amount'}
                                                        {$v.total}
                                                        {$total_amount=$total_amount+$v.total}
                                                    {elseif $k=='amount'}
                                                        {$v.amount}
                                                        {$amount=$amount+$v.amount}
                                                    {elseif $k=='charge'}
                                                        {$v.charge}
                                                        {$charge=$charge+$v.charge}
                                                    {else}
                                                        {$v.{$k}}
                                                    {/if}
                                                </td>
                                            {/foreach}
                                        </tr>
                                        {$int=$int+1}
                                    {/if}
                                {/foreach}
                                <tr>
                                    <td style="text-align: right;border-top: 1px solid grey;"
                                        colspan="{count($properties.travel_cancel_section.column)-$colspan}">
                                        <span style="text-align: right;"> Total ({$sec_col.{$scn}}{$scn=$scn+1})</span>
                                    </td>
                                    {foreach from=$properties.travel_cancel_section.column key=k item=v}
                                        {if $k=='amount'}
                                            <td style="text-align: right;border-top: 1px solid grey;"><b> {$amount|number_format:2:".":","}</b></td>
                                        {/if}
                                        {if $k=='charge'}
                                            <td style="text-align: right;border-top: 1px solid grey;"><b> {$charge|number_format:2:".":","}</b></td>
                                        {/if}
                                    {/foreach}
                                    <td style="border-top: 1px solid grey;"><b> {$info.currency_icon} {$total_amount|number_format:2:".":","}</b></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </td>
                </tr>
            {/if}

            {if in_array(3,$secarray)}

                <tr>
                    <td style="padding-top:0;">
                        <span style="color:{if !empty($info.design_color)}{$info.design_color}{else}#5b4d4b{/if};font-size: 14px;">{$properties.hotel_section.title}</span>
                        <div>
                            <table width="100%" border="0" cellspacing="0" cellpadding="5"
                                style="border: 1px solid grey;color:#5b4d4b;font-size: 13px;line-height: 15px;text-align: center;">
                                <thead>
                                    <tr style="background-color: #e2e2e2;">
                                        {foreach from=$properties.hotel_section.column item=v}
                                            <th style="border-bottom: 1px solid grey;border-right: 1px solid grey;">
                                                {$v}
                                            </th>
                                        {/foreach}
                                    </tr>
                                </thead>
                                {$int=1}
                                {$total_amount=0}
                                {foreach from=$ticket_detail item=v}
                                    {if $v.type==3}
                                        <tr>
                                            {foreach from=$properties.hotel_section.column key=k item=tb}
                                                <td style="border-right: 1px solid #e2e2e2;">
                                                    {if $k=='sr_no'}
                                                        {$int}
                                                    {elseif $k=='from_date'}
                                                        {if $setting.has_datetime=='1'}
                                                            {$v.booking_date|date_format:"%d %b %Y %H:%M %p"}
                                                            {else}
                                                              {$v.booking_date|date_format:"%d %b %Y"}
                                                            {/if}
                                                    {elseif $k=='to_date'}
                                                      {if $setting.has_datetime=='1'}
                                                            {$v.journey_date|date_format:"%d %b %Y %H:%M %p"}
                                                            {else}
                                                              {$v.journey_date|date_format:"%d %b %Y"}
                                                            {/if}
                                                    {elseif $k=='item'}
                                                        {$v.name}
                                                    {elseif $k=='qty'}
                                                        {$v.units}
                                                    {elseif $k=='total_amount'}
                                                        {$v.total}
                                                        {$total_amount=$total_amount+$v.total}
                                                    {else}
                                                        {$v.{$k}}
                                                    {/if}
                                                </td>
                                            {/foreach}
                                        </tr>
                                        {$int=$int+1}
                                    {/if}
                                {/foreach}
                                <tr>
                                    <td style="text-align: right;border-top: 1px solid grey;"
                                        colspan="{count($properties.hotel_section.column)-1}">
                                        <span style="text-align: right;"> Total ({$sec_col.{$scn}}{$scn=$scn+1})</span>
                                    </td>
                                    <td style="border-top: 1px solid grey;"><b>{$info.currency_icon} {$total_amount|number_format:2:".":","}</b></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </td>
                </tr>
            {/if}

            {if in_array(4,$secarray)}

                <tr>
                    <td style="padding-top:0;">
                        <span style="color: {if !empty($info.design_color)}{$info.design_color}{else}#5b4d4b{/if};font-size: 14px;">{$properties.facility_section.title}</span>
                        <div>
                            <table width="100%" border="0" cellspacing="0" cellpadding="5"
                                style="border: 1px solid grey;color:#5b4d4b;font-size: 13px;line-height: 15px;text-align: center;">
                                <thead>
                                    <tr style="background-color: #e2e2e2;">
                                        {foreach from=$properties.facility_section.column item=v}
                                            <th style="border-bottom: 1px solid grey;border-right: 1px solid grey;">
                                                {$v}
                                            </th>
                                        {/foreach}
                                    </tr>
                                </thead>
                                {$int=1}
                                {$total_amount=0}
                                {foreach from=$ticket_detail item=v}
                                    {if $v.type==4}
                                        <tr>
                                            {foreach from=$properties.facility_section.column key=k item=tb}
                                                <td style="border-right: 1px solid #e2e2e2;">
                                                    {if $k=='sr_no'}
                                                        {$int}
                                                    {elseif $k=='from_date'}
                                                      {if $setting.has_datetime=='1'}
                                                            {$v.booking_date|date_format:"%d %b %Y %H:%M %p"}
                                                            {else}
                                                              {$v.booking_date|date_format:"%d %b %Y"}
                                                            {/if}
                                                    {elseif $k=='to_date'}
                                                      {if $setting.has_datetime=='1'}
                                                            {$v.journey_date|date_format:"%d %b %Y %H:%M %p"}
                                                            {else}
                                                              {$v.journey_date|date_format:"%d %b %Y"}
                                                            {/if}
                                                    {elseif $k=='item'}
                                                        {$v.name}
                                                    {elseif $k=='qty'}
                                                        {$v.units}
                                                    {elseif $k=='total_amount'}
                                                        {$v.total}
                                                        {$total_amount=$total_amount+$v.total}
                                                    {else}
                                                        {$v.{$k}}
                                                    {/if}
                                                </td>
                                            {/foreach}
                                        </tr>
                                        {$int=$int+1}
                                    {/if}
                                {/foreach}
                                <tr>
                                    <td style="text-align: right;border-top: 1px solid grey;"
                                        colspan="{count($properties.facility_section.column)-1}">
                                        <span style="text-align: right;"> Total ({$sec_col.{$scn}}{$scn=$scn+1})</span>
                                    </td>
                                    <td style="border-top: 1px solid grey;"><b> {$info.currency_icon} {$total_amount|number_format:2:".":","}</b></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </td>
                </tr>
            {/if}

            <tr>
                <td>
                    <table width="100%" border="0" cellspacing="0" cellpadding="5">
                        <tr>
                            <td width="60%" style="vertical-align: top;text-align:left;">
                                {if $info.narrative!=''}
                                    <b>Narrative</b><br>
                                    {$info.narrative}
                                    <br>
                                {/if}
                                {if $tnc!=''}
                                    {$tnc}
                                {/if}
                            </td>
                            <td style="vertical-align: top;padding: 0;">
                                <table autosize="1" width="100%" border="0" cellspacing="0" cellpadding="5"
                                    style="border: 1px solid grey;border-left: 0;border-top: 0;border-bottom: 0;border-right: 0;color:#5b4d4b;font-size:13px;line-height: 22px;text-align: center;">
                                    <tr>
                                        <td colspan="2"
                                            style="{if !empty($info.design_color)}color:{$info.design_color};{/if}min-width:100px;border-left:0; border-bottom: 1px solid #cbcbcb;text-align: center;">
                                            <b>FINAL SUMMARY </b>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td colspan=""
                                            style="min-width:100px;border-left: 1px solid #cbcbcb; border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;text-align: left;">
                                            <b>SUB TOTAL
                                                {if $scn>1}
                                                    <br>(
                                                    {$scn=0}
                                                    {if !empty($particular)}
                                                        {$sec_col.{$scn}}{$scn=$scn+1}
                                                    {/if}
                                                    {if in_array(1,$secarray)}
                                                        {if $scn>0}+{/if} {$sec_col.{$scn}}{$scn=$scn+1}
                                                    {/if}
                                                    {if in_array(2,$secarray)}
                                                        {if $scn>0}-{/if} {$sec_col.{$scn}}{$scn=$scn+1}
                                                    {/if}
                                                    {if in_array(3,$secarray)}
                                                        {if $scn>0}+{/if} {$sec_col.{$scn}}{$scn=$scn+1}
                                                    {/if}
                                                    {if in_array(4,$secarray)}
                                                        {if $scn>0}+{/if} {$sec_col.{$scn}}{$scn=$scn+1}
                                                    {/if}
                                                    {if $discount>0}
                                                        {if $scn>0}-{/if} {$sec_col.{$scn}}{$scn=$scn+1}
                                                    {/if}
                                                    {if $adjustment>0}
                                                        {if $scn>0}-{/if} {$sec_col.{$scn}}{$scn=$scn+1}
                                                    {/if})
                                                {/if}
                                            </b>
                                        </td>
                                        <td style="border-bottom: 1px solid #cbcbcb;text-align: right;border-right: 1px solid #cbcbcb;"
                                            class="col-md-2 td-c">
                                            <b> {$info.basic_amount|number_format:2:".":","}</b>
                                        </td>
                                    </tr>
                                    {foreach from=$tax item=v}
                                        <tr style="text-align: center;">
                                            <td style="border-left: 1px solid #cbcbcb;border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;text-align: left;"
                                                colspan="">
                                                {$v.tax_name}
                                            </td>
                                            <td
                                                style="border-bottom: 1px solid #cbcbcb;text-align: right;border-right: 1px solid #cbcbcb;">
                                                {$v.tax_amount}
                                                {$taxtotal={$taxtotal}+{$v.tax_amount}}
                                            </td>
                                        </tr>
                                    {/foreach}
                                    <tr style="text-align: center;{if !empty($info.design_color)}background-color:{$info.design_color};{/if}">
                                        <td
                                            style="{if !empty($info.design_color)}color:white;{/if}border-left: 1px solid #cbcbcb;border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;text-align: left;">
                                            <b>Total </b>
                                        </td>
                                        <td
                                            style="{if !empty($info.design_color)}color:white;{/if}border-bottom: 1px solid #cbcbcb;text-align: right;border-right: 1px solid #cbcbcb;">
                                            <b>
                                            {$info.currency_icon} {($info.basic_amount+$tax_total)|number_format:2:".":","}</b>
                                        </td>
                                    </tr>
                                    {if $advance>0}
                                        <tr style="text-align: center;">
                                            <td
                                                style="border-left: 1px solid #cbcbcb;border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;text-align: left">
                                                <b>Advance received</b>
                                            </td>
                                            <td
                                                style="border-bottom: 1px solid #cbcbcb;text-align: right;border-right: 1px solid #cbcbcb;">
                                                <b>{($advance)|number_format:2:".":","}</b>
                                            </td>
                                        </tr>
                                        <tr style="text-align: center;">
                                            <td
                                                style="border-left: 1px solid #cbcbcb;border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;text-align: left">
                                                <b>Balance</b>
                                            </td>
                                            <td
                                                style="border-bottom: 1px solid #cbcbcb;text-align: right;border-right: 1px solid #cbcbcb;">
                                                <b>{($grand_total)|number_format:2:".":","}</b>
                                            </td>
                                        </tr>
                                    {/if}

                                    <tr>
                                        <td colspan="2"
                                            style="border-left: 1px solid #cbcbcb;text-align: left;border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;">
                                            <p><b>Amount (in words) :</b> {$money_words}</p>
                                        </td>
                                    </tr>

                                </table>
                            </td>
                        </tr>
                    </table>
                </td>





                {if $plugin.has_signature==1}
                    {if isset($signature)}
                    <tr>
                        <td colspan="2" style="text-align:{$signature.align};  ">
                            {if $signature.type=='font'}
                                <img src="{$server_path}{$signature.font_image}" style="max-height: 100px;float:{$signature.align};">
                            {else}
                                <img src="{$server_path}{$signature.signature_file}"
                                    style="max-height: 100px;float:{$signature.align};">
                            {/if}
                        </td>
                    </tr>
                {/if}

            {/if}
            {if $is_merchant==1 && $info.has_acknowledgement==1}
                <tr>
                    <td colspan="2" style="border-bottom:1px solid #e2e2e2;">
                        <table width="800" style="border:1px solid #e2e2e2;color:#5b4d4b;"
                            class="table table-bordered table-condensed">
                            <tbody>
                                <tr>
                                    {if $info.invoice_number!=''}
                                        <th width="100" style="border-bottom:1px solid #e2e2e2;border-right:1px solid #e2e2e2;">
                                            Invoice #</th>
                                    {/if}
                                    <th width="100" style="border-bottom:1px solid #e2e2e2;border-right:1px solid #e2e2e2;">
                                        Customer Code</th>
                                    <th width="100" style="border-bottom:1px solid #e2e2e2;border-right:1px solid #e2e2e2;">
                                        Cheque No</th>
                                    <th width="140" style="border-bottom:1px solid #e2e2e2;border-right:1px solid #e2e2e2;">
                                        Bank/Branch</th>
                                    <th width="100" style="border-bottom:1px solid #e2e2e2;border-right:1px solid #e2e2e2;">
                                        Amount</th>
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

        </table>
    </body>

    </html>
{/strip}