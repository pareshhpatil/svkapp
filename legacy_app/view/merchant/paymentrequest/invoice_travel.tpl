{if isset($signature.font_file)}
    <link href="{$signature.font_file}" rel="stylesheet">
{/if}
{$scn=0}
<!-- END PAGE HEADER-->
<div class="page-content" style="min-height:259px">
    <div style="text-align: -webkit-center;text-align:-moz-center;">
        <h3 class="page-title">
            &nbsp;
        </h3>
      
        {if $invoice_success}
            <div class="alert alert-block alert-success fade in" style="max-width: 900px;text-align: left;">
                {if $payment_request_status==11}
                    <h4 class="alert-heading">Preview {if $invoice_type==1} invoice {else} estimate {/if}</h4>
                    <p>
                        Confirm if your {if $invoice_type==1} invoice {else} estimate {/if} fields and values are as per your
                        expectation
                    </p>
                {else}
                    {if $notify_patron==1}
                        {if $invoice_type==1}
                            <h4 class="alert-heading">Payment request sent!</h4>
                            <p>
                                Your invoice has been sent to your customer. You will receive a notification as soon as your
                                customer makes the payment.
                            </p>
                        {else}
                            <h4 class="alert-heading">Estimate sent!</h4>
                            <p>
                                Your estimate has been sent to your customer. You will receive a notification as soon as your
                                customer makes the payment, along with the final invoice copy.
                            </p>
                        {/if}
                    {else}
                        {if $invoice_type==1}
                            <h4 class="alert-heading">Invoice saved!</h4>
                            <p>
                                Your invoice has been saved and will appear in the Requests and Reports tabs.
                            </p>
                        {else}
                            <h4 class="alert-heading">Estimate saved!</h4>
                            <p>
                                Your estimate has been saved and will appear in the Requests and Reports tabs.
                            </p>
                        {/if}

                    {/if}
                    <p>
                                <a class="btn blue input-sm" data-toggle="modal" href="#respond">
                                <i class="fa {$info.currency_icon}"></i>  Settle </a>
                                <a class="btn green input-sm" href="/merchant/invoice/update/{$link}">
                            <i class="fa fa-edit"></i>   Update {if $invoice_type==1}invoice{else}estimate{/if} </a>
                                <a class="btn green input-sm" target="_BLANk" href="{$whatsapp_share}">
                                    <i class="fa fa-whatsapp"></i> Share on Whatsapp</a>
                            <a class="bs_growl_show btn green input-sm" title="Copy Link" data-clipboard-action="copy"
                                data-clipboard-target="linkcopy"> <i class="fa fa-clipboard"></i> Copy invoice link</a>
                                <a class="btn green input-sm"  href="/merchant/paymentrequest/download/{$link}">
                                    <i class="fa fa-file"></i> Save as PDF</a>
                                <a class="btn green input-sm"  href="/merchant/paymentrequest/download/{$link}/2">
                                    <i class="fa fa-print"></i> Print</a>
                            </p>
                    <div style="font-size: 0px;">
                        <linkcopy>{$patron_url}</linkcopy>
                    </div>
                {/if}
            </div>
        {/if}
        {if ($payment_gateway_info==true)}
            <!-- Added info box on invoice creation -->
            {* <div class="alert alert-info" style="max-width: 900px;text-align: left;">
                <strong>Free online transactions for you!</strong>
                <div class="media">
                    <p class="media-heading">Collect payments for your invoices online. No charges for all your
                        online transactions for the first 5 lakhs. <a href="/merchant/profile/complete/bank">Getting
                            started.</a></p>
                </div>

            </div> *}
        {/if}
        <br>
        {if isset($error)}
            <div class="alert alert-danger" style="text-align:left;max-width: 900px;">
                <div class="media">
                    <p class="media-heading">{$error}</p>
                </div>

            </div>
        {/if}
        <div class="invoice">
            <div class="row invoice-logo  no-margin" style="margin-bottom: 0px;">
                <div class="col-md-3" style="min-width: 150px;line">
                    {if {$image_path}!=''}
                        <img src="/uploads/images/logos/{$image_path}" class="img-responsive templatelogo" alt="" />
                    {/if}
                </div>
                <div class="col-md-8">
                    <p style="text-align: left;">
                        <a href="#" style="font-size: 27px;" class="invoice-heading" target="_BLANK">{$company_name}</a>
                        <br>
                        {if $main_company_name!=''}
                            <span class="muted" style="font-size: 12px;"> (An official franchisee of
                                {$main_company_name})</span>
                        {/if}
                        <span class="muted" style="width: 418px;line-height: 20px;font-size: 13px;">
                            {$merchant_address} <br>
                        </span>
                        {foreach from=$main_header item=v}
                            {if $v.column_name!='Company name' && $v.value!='' && $v.column_name!='Merchant address'}
                                <span class="muted" style="line-height: 20px;font-size: 13px;">
                                    {{$v.column_name|replace:'Merchant ':''}|ucfirst}: {$v.value}<br>
                                </span>
                            {/if}
                        {/foreach}
                    </p>
                </div>
            </div>
            <div class="row no-margin bg-grey" style="background:{$info.design_color} !important">
                <div class="col-md-12" style="text-align: center;{if !empty($info.design_color)}color:white;{/if}">
                    {if $info.invoice_type==2}
                        <h4><b>{$plugin.invoice_title}</b></h4>
                    {else}
                        <h4><b>{if !empty($tax)}{$lang_title.tax} {/if}{$lang_title.invoice}</b></h4>
                    {/if}
                </div>
            </div>
            <div class="row no-margin bb-1" style="margin-bottom: 0.5rem;margin-top: 0.5rem;">
                <div class="col-md-6" style="padding-left: 0px;padding-right: 0px;">
                    <div class="" style="">
                        <table class="table table-condensed" style="margin: 0px 0 0px 0 !important;">
                            <tbody>
                                {foreach from=$customer_breckup item=v}
                                    {if $v.value!=''}
                                        <tr>
                                            <td class="invoice-label bx-0">{$v.column_name}</td>
                                            <td class="bx-0">{$v.value}</td>
                                        </tr>
                                    {/if}
                                {/foreach}
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-6 invoice-payment" style="padding-left: 0px;padding-right: 0px;">
                    <div class="">

                        <table class="table  table-condensed" style="margin: 0px 0 0px 0 !important;">
                            <tbody>
                                {$last_payment=NULL}
                                {$adjustment=0}
                                {$discount=0}
                                {foreach from=$header item=v}
                                    {if $v.position=="R" && $v.value!="" && $v.function_id!=4}
                                        {if $v.function_id==11}
                                            {$last_payment=$v.value}
                                        {else if $v.function_id==12}
                                            {$adjustment=$v.value}
                                        {else if $v.function_id==14}
                                            {$discount=$v.value}
                                        {else}
                                            <tr>
                                                <td class="invoice-label bx-0">
                                                    {$v.column_name}
                                                </td>
                                                {if {$v.value|substr:0:7}=='http://' || {$v.value|substr:0:8}=='https://'}
                                                    <td class="bx-0" style="min-width: 120px;"> <a target="_BlANK"
                                                            href="{$v.value}">{$v.column_name}</a></td>
                                                {else}
                                                    <td class="bx-0" style="min-width: 120px;">
                                                        {if $v.datatype=='money'}
                                                            {$v.value|number_format:2:".":","}
                                                        {elseif $v.datatype=='date'}
                                                            {$v.value|date_format:"%d %b %Y"}
                                                        {else}
                                                            {$v.value} {if $v.datatype=='percent'} %{/if}
                                                        {/if}
                                                    </td>
                                                {/if}

                                            </tr>
                                        {/if}
                                    {/if}
                                {/foreach}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            {if !empty($bds_column) && isset($properties.vehicle_det_section)}
                <div class="row no-margin bb-1" style="margin-bottom: 0.5rem;">
                    <h4 class=" invoice-sub-title" style="color:{$info.design_color} !important">
                        {if isset($properties.vehicle_det_section.title)}{$properties.vehicle_det_section.title}
                        {else}Vehicle
                        details{/if}</h4>
                    <div class="col-md-6" style="padding-left: 0px;padding-right: 0px;">
                        <div class="" style="">
                            <table class="table  table-condensed" style="margin: 0px 0 0px 0 !important;">
                                <tbody>
                                    {foreach from=$bds_column item=v}
                                        {if $v.position=="L" && $v.value!=""}
                                            <tr>
                                                <td class="invoice-label bx-0">
                                                    {$v.column_name}
                                                </td>
                                                <td class="bx-0">
                                                    {if $v.datatype=='date'}
                                                        {$v.value|date_format:"%d %b %Y"}
                                                    {else}
                                                        {$v.value}{if $v.datatype=='percent'} %{/if}
                                                    {/if}
                                                </td>
                                            </tr>
                                        {/if}
                                    {/foreach}

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6 invoice-payment" style="padding-left: 0px;padding-right: 0px;">
                        <div class="">
                            <table class="table table-condensed" style="margin: 0px 0 0px 0 !important;">
                                <tbody>
                                    {foreach from=$bds_column item=v}
                                        {if $v.position=="R" && $v.value!=""}
                                            <tr>
                                                <td class="invoice-label bx-0">
                                                    {$v.column_name}
                                                </td>
                                                <td class="bx-0">
                                                    {if $v.datatype=='date'}
                                                        {$v.value|date_format:"%d %b %Y"}
                                                    {else}
                                                        {$v.value}{if $v.datatype=='percent'} %{/if}
                                                    {/if}
                                                </td>
                                            </tr>
                                        {/if}
                                    {/foreach}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            {/if}

            {if isset($properties.vehicle_section) && !empty($particular)}
                <div class="row">
                    <div class="col-md-12">
                        <h4 class="invoice-sub-title" style="color:{$info.design_color} !important">{$properties.vehicle_section.title}</h4>
                        <div class="table-scrollable b-0">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        {foreach from=$particular_column key=k item=v}
                                            <th class="td-c bb-1">
                                                {$v}
                                            </th>
                                        {/foreach}
                                    </tr>
                                </thead>
                                <tbody>

                                    {$total=0}
                                    {$taxtotal=0}
                                    {$int=1}
                                    {$total_amount=0}
                                    {foreach from=$particular item=dp}
                                        <tr>
                                            {foreach from=$particular_column key=k item=v}
                                                {if $k=='sr_no'}
                                                    <td class="td-c bx-0">
                                                        {$int}
                                                    </td>
                                                {else}
                                                    <td class="td-c bx-0">
                                                        {$dp.{$k}}{if $k=='gst' && $dp.{$k}>0}%{/if}
                                                    </td>
                                                    {if $k=='total_amount'}
                                                        {$total_amount=$total_amount+{$dp.{$k}}}
                                                    {/if}
                                                {/if}
                                            {/foreach}
                                        </tr>
                                        {$int=$int+1}
                                    {/foreach}
                                    <tr>
                                        <td colspan="{count($particular_column)-2}">
                                        </td>
                                        <td>

                                            <span class="pull-right"> Total  ({$sec_col.{$scn}}{$scn=$scn+1})</span>

                                        </td>
                                        <td class="td-c"><b>{$info.currency_icon} {$total_amount|number_format:2:".":","}</b></td>
                                    </tr>
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            {/if}
            {if in_array(1,$secarray)}
                {$colspan=1}
                {$amount=0}
                {$charge=0}
                <div class="row">
                    <div class="col-md-12">
                        <h4 class="invoice-sub-title" style="color:{$info.design_color} !important">{$properties.travel_section.title}</h4>
                        <div class="table-scrollable b-0 bb-0">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        {foreach from=$properties.travel_section.column key=k item=v}
                                            {if $k=='charge'}
                                                {$colspan=$colspan+1}
                                            {/if}
                                            {if $k=='amount'}
                                                {$colspan=$colspan+1}
                                            {/if}
                                            <th class="td-c bb-1">
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
                                                    <td class="td-c bx-0">
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
                                        <td colspan="{count($properties.travel_section.column)-$colspan}">
                                            <span class="pull-right"> Total ({$sec_col.{$scn}}{$scn=$scn+1})</span>
                                        </td>
                                        {foreach from=$properties.travel_section.column key=k item=v}
                                            {if $k=='amount'}
                                                <td style="min-width: 100px;" class="td-c"><b>{$info.currency_icon} {$amount|number_format:2:".":","}</b></td>
                                            {/if}
                                            {if $k=='charge'}
                                                <td style="min-width: 80px;" class="td-c"><b>{$info.currency_icon} {$charge|number_format:2:".":","}</b></td>
                                            {/if}
                                        {/foreach}

                                        <td style="min-width: 100px;" class="td-c"><b>{$info.currency_icon} {$total_amount|number_format:2:".":","}</b></td>
                                    </tr>
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            {/if}

            {if in_array(2,$secarray)}
                {$colspan=1}
                {$amount=0}
                {$charge=0}
                <div class="row">
                    <div class="col-md-12">
                        <h4 class="invoice-sub-title" style="color:{$info.design_color} !important">{$properties.travel_cancel_section.title}</h4>
                        <div class="table-scrollable b-0 bb-0">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        {foreach from=$properties.travel_cancel_section.column key=k item=v}
                                            {if $k=='charge'}
                                                {$colspan=$colspan+1}
                                            {/if}
                                            {if $k=='amount'}
                                                {$colspan=$colspan+1}
                                            {/if}
                                            <th class="td-c bb-1">
                                                {$v}
                                            </th>
                                        {/foreach}
                                    </tr>
                                </thead>
                                <tbody>
                                    {$int=1}
                                    {$total_amount=0}
                                    {foreach from=$ticket_detail item=v}
                                        {if $v.type==2}
                                            <tr>
                                                {foreach from=$properties.travel_cancel_section.column key=k item=tb}
                                                    <td class="td-c bx-0">
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
                                        <td colspan="{count($properties.travel_cancel_section.column)-$colspan}">
                                            <span class="pull-right"> Total ({$sec_col.{$scn}}{$scn=$scn+1})</span>
                                        </td>
                                        {foreach from=$properties.travel_cancel_section.column key=k item=v}
                                            {if $k=='amount'}
                                                <td style="min-width: 100px;" class="td-c"><b>{$info.currency_icon} {$amount|number_format:2:".":","}</b></td>
                                            {/if}
                                            {if $k=='charge'}
                                                <td style="min-width: 80px;" class="td-c"><b>{$info.currency_icon} {$charge|number_format:2:".":","}</b></td>
                                            {/if}
                                        {/foreach}
                                        <td style="min-width: 100px;" class="td-c"><b>{$info.currency_icon} {$total_amount|number_format:2:".":","}</b></td>
                                    </tr>
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            {/if}

            {if in_array(3,$secarray)}
                <div class="row">
                    <div class="col-md-12">
                        <h4 class="invoice-sub-title" style="color:{$info.design_color} !important">{$properties.hotel_section.title}</h4>
                        <div class="table-scrollable b-0 bb-0">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        {foreach from=$properties.hotel_section.column item=v}
                                            <th class="td-c bb-1">
                                                {$v}
                                            </th>
                                        {/foreach}
                                    </tr>
                                </thead>
                                <tbody>
                                    {$int=1}
                                    {$total_amount=0}
                                    {foreach from=$ticket_detail item=v}
                                        {if $v.type==3}
                                            <tr>
                                                {foreach from=$properties.hotel_section.column key=k item=tb}
                                                    <td class="td-c bx-0">
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
                                        <td colspan="{count($properties.hotel_section.column)-1}">
                                            <span class="pull-right"> Total {$info.currency_icon} ({$sec_col.{$scn}}{$scn=$scn+1})</span>
                                        </td>
                                        <td class="td-c"><b> {$total_amount|number_format:2:".":","}</b></td>
                                    </tr>
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            {/if}

            {if in_array(4,$secarray)}
                <div class="row">
                    <div class="col-md-12">
                        <h4 class="invoice-sub-title" style="color:{$info.design_color} !important">{$properties.facility_section.title}</h4>
                        <div class="table-scrollable b-0 bb-0">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        {foreach from=$properties.facility_section.column item=v}
                                            <th class="td-c bb-1">
                                                {$v}
                                            </th>
                                        {/foreach}
                                    </tr>
                                </thead>
                                <tbody>
                                    {$int=1}
                                    {$total_amount=0}
                                    {foreach from=$ticket_detail item=v}
                                        {if $v.type==4}
                                            <tr>
                                                {foreach from=$properties.facility_section.column key=k item=tb}
                                                    <td class="td-c bx-0">
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
                                        <td colspan="{count($properties.facility_section.column)-1}">
                                            <span class="pull-right"> Total  ({$sec_col.{$scn}}{$scn=$scn+1})</span>
                                        </td>
                                        <td class="td-c"><b>{$info.currency_icon} {$total_amount|number_format:2:".":","}</b></td>
                                    </tr>
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            {/if}

            <div class="row">
                <div class="col-md-12">
                    <div class="table-scrollable b-0">
                        <table class="table ">

                            <tbody>
                                <tr>
                                    <td colspan="4" class="col-md-8" style="border-bottom: 0;border-top:0;">
                                        {if $info.tnc!=''}
                                            {$info.tnc}
                                        {/if}
                                        {if $info.narrative!=''}
                                            Narrative: {$info.narrative}
                                        {/if}
                                    </td>
                                    <td colspan="3" style="padding:0;border-top:0;">
                                        <table class="table table-hover"
                                            style="margin:0 !important;border-left: 0px !important;">
                                            <tbody>

                                                <tr>
                                                    <td colspan="2" class="td-c bt-0">
                                                        <h4 class="invoice-sub-title" style="color:{$info.design_color} !important"> FINAL SUMMARY</h4>
                                                    </td>

                                                </tr>
                                                {if $discount>0}
                                                    <tr>
                                                        <td class="col-md-6 bl-0 bt-0">
                                                            {$discount_col} ({$sec_col.{$scn}}{$scn=$scn+1})
                                                        </td>
                                                        <td class="col-md-6 td-c br-0 bt-0">
                                                            {$discount|number_format:2:".":","}
                                                        </td>
                                                    </tr>
                                                {/if}
                                                {if $adjustment>0}
                                                    <tr>
                                                        <td class="col-md-6 bl-0">
                                                            {$adjustment_col} ({$sec_col.{$scn}}{$scn=$scn+1})
                                                        </td>
                                                        <td class="col-md-6 td-c br-0">
                                                            {$adjustment|number_format:2:".":","}
                                                        </td>
                                                    </tr>
                                                {/if}
                                                <tr>
                                                    <td class="col-md-6 bl-0 " style="min-width: 160px;">
                                                        SUB TOTAL {if $scn>1}
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


                                                    </td>
                                                    <td class="col-md-6 td-c br-0">
                                                        <b>
                                                        {$info.currency_icon} {{$basic_total-$discount-$adjustment}|number_format:2:".":","}</b>
                                                    </td>
                                                </tr>
                                                {foreach from=$tax item=v}
                                                    <tr>
                                                        <td class="col-md-6 bl-0">
                                                            {$v.tax_name}
                                                        </td>
                                                        <td class="col-md-6 td-c br-0">
                                                            {$v.tax_amount}
                                                            {$taxtotal={$taxtotal}+{$v.tax_amount}}
                                                        </td>
                                                    </tr>
                                                {/foreach}
                                                {if $previousdue>0}
                                                    <tr>
                                                        <td class="col-md-6 bl-0">
                                                            {$previousdue_col}
                                                        </td>
                                                        <td class="col-md-6 td-c br-0">
                                                            {$previousdue|number_format:2:".":","}
                                                        </td>
                                                    </tr>
                                                {/if}
                                                {if !empty($coupon_details) && $payment_request_status!=1 && $payment_request_status!=2}
                                                    <tr style="">
                                                        <td class="col-md-6 bl-0">
                                                            <b>Coupon discount</b>
                                                        </td>
                                                        <td class="col-md-6 td-c br-0">
                                                            <b><span id="discount"> 0.00</span></b>
                                                        </td>
                                                    </tr>
                                                {/if}
                                                {if $advance>0}
                                                    <tr style="">
                                                        <td class="col-md-6 bl-0">
                                                            <b>Advance received </b>
                                                        </td>
                                                        <td class="col-md-6 td-c br-0">
                                                            <b>{$advance|number_format:2:".":","}</b>
                                                        </td>
                                                    </tr>
                                                {/if}
                                                {if $paid_amount>0}
                                                    <tr style="">
                                                        <td class="col-md-6 bl-0">
                                                            <b>Paid amount </b>
                                                        </td>
                                                        <td class="col-md-6 td-c br-0">
                                                            <b>{$paid_amount|number_format:2:".":","}</b>
                                                        </td>
                                                    </tr>
                                                {/if}
                                               
                                                <tr style="{if !empty($info.design_color)}color:white;{/if}background-color: {if !empty($info.design_color)}{$info.design_color}{else}#e5e5e5{/if}!important;">
                                                    <td class="col-md-6 bl-0">
                                                        <b>Grand total </b>
                                                    </td>
                                                    <td class="col-md-6 td-c br-0">
                                                        <b><span id="absolute_cost">
                                                        {$info.currency_icon} {$absolute_cost|number_format:2:".":","}</span></b>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td colspan="2" class="col-md-12 bl-0 br-0 sm-text">
                                                        {$money_words}
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                {if !isset($signature)}
                                    <tr>
                                        <td colspan="9" class="col-md-8" style="border-bottom: 0;">
                                            Note: This is a system generated invoice. No signature required.
                                        </td>
                                    </tr>
                                {/if}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {if isset($signature)}
                <div class="row">
                    <div class="col-md-12">
                        <div class="pull-{$signature.align}" style="margin-{$signature.align}: 20px;">
                            {if $signature.type=='font'}
                                <label
                                    style="font-family: '{$signature.font_name}',cursive;font-size: {$signature.font_size}px;">{$signature.name}</label>
                            {else}
                                <img src="{$signature.signature_file}" style="max-height: 100px;">
                            {/if}
                        </div>
                    </div>
                </div>
            {/if}


        </div>
<!-- END PAGE CONTENT-->