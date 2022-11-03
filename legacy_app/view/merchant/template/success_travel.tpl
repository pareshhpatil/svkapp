{if $ajax==0}
    <div class="page-content">
    {/if}
    {$scn=0}
    {$sec1=0}
    {$sec2=0}
    {$sec3=0}
    {$sec4=0}
    {$sec5=0}
    <!-- BEGIN PAGE HEADER-->
    <!-- END PAGE HEADER-->


    <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-10">
            {if $ajax==0}
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-block alert-success fade in  nolr-margin">
                            <h4 class="alert-heading">Template Created / Modified</h4>
                            <p>
                                Template has been saved. You can use this template to send payment requests to your patrons.
                            </p>
                            <p>
                                <a class="btn blue input-sm" href="/merchant/invoiceformat/update/{$link}">
                                    Update template </a>
                                <a class="btn green input-sm" href="/merchant/invoice/create">
                                    Create invoice </a>
                            </p>
                        </div>
                    </div>
                </div>
            {/if}
            <div class="invoice" style="display:none;">
                <div class="row invoice-logo  no-margin" style="margin-bottom: 0px;">
                    <div class="col-md-3" style="min-width: 150px;line">
                        {if {$image_path}!=''}
                            <img src="/uploads/images/logos/{$image_path}" class="img-responsive templatelogo" alt="" />
                        {/if}
                    </div>
                    <div class="col-md-8">
                        <p style="text-align: left;">
                            <a href="#" style="font-size: 27px;" class="invoice-heading"
                                target="_BLANK">{$company_name}</a> <br>
                            {if $main_company_name!=''}
                                <span class="muted" style="font-size: 12px;"> (An official franchisee of
                                    {$main_company_name})</span>
                            {/if}
                            <span class="muted" style="width: 418px;line-height: 20px;font-size: 13px;">
                                {$merchant_address} <br>
                            </span>
                            {foreach from=$main_header item=v}
                                {if $v.column_name!='Company name' && $v.column_name!='Merchant address'}
                                    <span class="muted" style="line-height: 20px;font-size: 13px;">
                                        {$column_name={$v.column_name|replace:'Merchant ':''}|ucfirst}
                                        {$column_name}:
                                        {if $column_name=='Contact'} {$business_contact}
                                        {elseif $column_name=='Email'} {$business_email}
                                        {/if}
                                        <br>
                                    </span>
                                {/if}
                            {/foreach}
                        </p>
                    </div>
                </div>
                <div class="row no-margin bg-grey" style="">
                    <div class="col-md-12" style="text-align: center;">
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
                                    {foreach from=$customer_column_list item=v}
                                        <tr>
                                            <td class="invoice-label bx-0">{$v.column_name}</td>
                                            <td class="bx-0">
                                                {if $v.customer_column_id==1}
                                                    Cust-124
                                                {else if $v.customer_column_id==2}
                                                    Rohit Sharma
                                                {else if $v.customer_column_id==3}
                                                    rohitsharmabills@gmail.com
                                                {else if $v.customer_column_id==4}
                                                    9876543210
                                                {else if $v.customer_column_id==5}
                                                    134 135, Vashi Plaza C Wing, Sector 17, Vashi, Mumbai
                                                {else if $v.customer_column_id==6}
                                                    Mumbai
                                                {else if $v.customer_column_id==7}
                                                    Maharashtra
                                                {else if $v.customer_column_id==7}
                                                    400703
                                                {/if}
                                            </td>
                                        </tr>
                                    {/foreach}

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6 invoice-payment" style="padding-left: 0px;padding-right: 0px;">
                        <div class="">

                            <table class="table  table-condensed" style="margin: 0px 0 0px 0 !important;">
                                <tbody>
                                    {$ccol=1}
                                    {foreach from=$header item=v}
                                        {if {$v.position}=='R' && {$v.column_name!='Billing cycle name'}}
                                            <tr>
                                                <td class="invoice-label bx-0">{$v.column_name}</td>
                                                <td class="bx-0" style="min-width: 120px;">
                                                    {if $v.datatype=='date'}
                                                        {$current_date}
                                                    {else if $v.datatype=='money'}
                                                        {if $template_type=='scan'}
                                                            1,000.00
                                                        {else}
                                                            12,245.00
                                                        {/if}
                                                    {else if $v.datatype=='number'}
                                                        123456
                                                    {else}
                                                        {if $v.function_id==9}
                                                            INV-001
                                                        {else}
                                                            Custom column {$ccol}
                                                            {$ccol=$ccol+1}
                                                        {/if}
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
                {if isset($properties.vehicle_det_section)}
                    <div class="row no-margin bb-1" style="margin-bottom: 0.5rem;">
                        <h4 class=" invoice-sub-title">
                            {if isset($properties.vehicle_det_section.title)}{$properties.vehicle_det_section.title}
                            {else}Vehicle
                            details{/if}</h4>
                        <div class="col-md-6" style="padding-left: 0px;padding-right: 0px;">
                            <div class="" style="">
                                <table class="table  table-condensed" style="margin: 0px 0 0px 0 !important;">
                                    <tbody>
                                        {foreach from=$bds_column item=v}
                                            {if $v.position=="L"}
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
                                            {if $v.position=="R" }
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

                {if isset($properties.vehicle_section)}
                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="invoice-sub-title">{$properties.vehicle_section.title}</h4>
                            <div class="table-scrollable b-0">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            {foreach from=$particular_col key=k item=v}
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
                                        {foreach from=$default_particular item=dp}
                                            <tr>
                                                {foreach from=$particular_col key=k item=v}
                                                    {if $k=='sr_no'}
                                                        <td class="td-c">
                                                            {$int}
                                                        </td>
                                                    {elseif $k=='item'}
                                                        <td class="td-c">
                                                            {$dp}
                                                        </td>
                                                    {else}
                                                        <td class="td-c">
                                                            {if $k=='qty'}
                                                                1
                                                            {elseif $k=='rate'}
                                                                1,000.00
                                                            {elseif $k=='total_amount'}
                                                                1,000.00
                                                            {/if}
                                                        </td>
                                                    {/if}
                                                {/foreach}
                                            </tr>
                                            {$int=$int+1}
                                        {/foreach}
                                        <tr>
                                            <td colspan="{count($particular_col)-2}">
                                            </td>
                                            <td>
                                                {$sec1=1}
                                                <span class="pull-right"> Total Rs. ({$sec_col.{$scn}}{$scn=$scn+1})</span>

                                            </td>
                                            <td class="td-c"><b> {$sub_total|number_format:2:".":","}</b></td>
                                        </tr>
                                    </tbody>

                                </table>
                            </div>
                        </div>
                    </div>
                {/if}
                {if isset($properties.travel_section)}
                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="invoice-sub-title">{$properties.travel_section.title}</h4>
                            <div class="table-scrollable b-0 bb-0">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            {foreach from=$properties.travel_section.column item=v}
                                                <th class="td-c bb-1">
                                                    {$v}
                                                </th>
                                            {/foreach}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {$int=1}
                                        {$total_amount=0}
                                        <tr>
                                            {foreach from=$properties.travel_section.column key=k item=tb}
                                                <td class="td-c bx-0">
                                                    {if $k=='sr_no'}
                                                        {$int}
                                                    {elseif $k=='booking_date'}
                                                        {$current_date|date_format:"%d %b %Y"}
                                                    {elseif $k=='journey_date'}
                                                        {$current_date|date_format:"%d %b %Y"}
                                                    {elseif $k=='name'}
                                                        Rohit
                                                    {elseif $k=='type'}
                                                        Train
                                                    {elseif $k=='from'}
                                                        Mumbai
                                                    {elseif $k=='to'}
                                                        Goa
                                                    {elseif $k=='amount'}
                                                        1000.00
                                                    {elseif $k=='charge'}
                                                        0.00
                                                    {elseif $k=='total_amount'}
                                                        1000.00
                                                        {$total_amount=$total_amount+1000.00}
                                                    {/if}
                                                </td>
                                            {/foreach}
                                        </tr>
                                        {$int=$int+1}
                                        <tr>
                                            <td colspan="{count($properties.travel_section.column)-1}">
                                                {$sec2=1}
                                                <span class="pull-right"> Total Rs. ({$sec_col.{$scn}}{$scn=$scn+1})</span>
                                            </td>
                                            <td class="td-c"><b>
                                                    {$sub_total=$sub_total+$total_amount}
                                                    {$total_amount|number_format:2:".":","}</b></td>
                                        </tr>
                                    </tbody>

                                </table>
                            </div>
                        </div>
                    </div>
                {/if}

                {if isset($properties.travel_cancel_section)}
                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="invoice-sub-title">{$properties.travel_cancel_section.title}</h4>
                            <div class="table-scrollable b-0 bb-0">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            {foreach from=$properties.travel_cancel_section.column item=v}
                                                <th class="td-c bb-1">
                                                    {$v}
                                                </th>
                                            {/foreach}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {$int=1}
                                        {$total_amount=0}
                                        <tr>
                                            {foreach from=$properties.travel_cancel_section.column key=k item=tb}
                                                <td class="td-c bx-0">
                                                    {if $k=='sr_no'}
                                                        {$int}
                                                    {elseif $k=='booking_date'}
                                                        {$current_date|date_format:"%d %b %Y"}
                                                    {elseif $k=='journey_date'}
                                                        {$current_date|date_format:"%d %b %Y"}
                                                    {elseif $k=='name'}
                                                        Rohit
                                                    {elseif $k=='type'}
                                                        Train
                                                    {elseif $k=='from'}
                                                        Mumbai
                                                    {elseif $k=='to'}
                                                        Goa
                                                    {elseif $k=='amount'}
                                                        1000.00
                                                    {elseif $k=='charge'}
                                                        0.00
                                                    {elseif $k=='total_amount'}
                                                        1000.00
                                                        {$total_amount=$total_amount+1000.00}
                                                    {/if}
                                                </td>
                                            {/foreach}
                                        </tr>
                                        {$int=$int+1}
                                        <tr>
                                            <td colspan="{count($properties.travel_cancel_section.column)-1}">
                                                {$sec3=1}
                                                <span class="pull-right"> Total Rs. ({$sec_col.{$scn}}{$scn=$scn+1})</span>
                                            </td>
                                            <td class="td-c"><b>
                                                    {$sub_total=$sub_total-$total_amount}
                                                    {$total_amount|number_format:2:".":","}</b></td>
                                        </tr>
                                    </tbody>

                                </table>
                            </div>
                        </div>
                    </div>
                {/if}

                {if isset($properties.hotel_section)}
                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="invoice-sub-title">{$properties.hotel_section.title}</h4>
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
                                        <tr>
                                            {foreach from=$properties.hotel_section.column key=k item=tb}
                                                <td class="td-c bx-0">
                                                    {if $k=='sr_no'}
                                                        {$int}
                                                    {elseif $k=='from_date'}
                                                        {$current_date|date_format:"%d %b %Y"}
                                                    {elseif $k=='to_date'}
                                                        {$current_date|date_format:"%d %b %Y"}
                                                    {elseif $k=='item'}
                                                        Rohit
                                                    {elseif $k=='rate'}
                                                        1000.00
                                                    {elseif $k=='qty'}
                                                        1
                                                    {elseif $k=='total_amount'}
                                                        1000.00
                                                        {$total_amount=$total_amount+1000.00}
                                                    {/if}
                                                </td>
                                            {/foreach}
                                        </tr>
                                        {$int=$int+1}
                                        <tr>
                                            <td colspan="{count($properties.hotel_section.column)-1}">
                                                {$sec4=1}
                                                <span class="pull-right"> Total Rs. ({$sec_col.{$scn}}{$scn=$scn+1})</span>
                                            </td>
                                            <td class="td-c"><b>
                                                    {$sub_total=$sub_total+$total_amount}
                                                    {$total_amount|number_format:2:".":","}</b></td>
                                        </tr>
                                    </tbody>

                                </table>
                            </div>
                        </div>
                    </div>
                {/if}

                {if isset($properties.facility_section)}
                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="invoice-sub-title">{$properties.facility_section.title}</h4>
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
                                        <tr>
                                            {foreach from=$properties.facility_section.column key=k item=tb}
                                                {if $k=='sr_no'}
                                                    <td class="td-c">
                                                        {$int}
                                                    </td>
                                                {elseif $k=='item'}
                                                    <td class="td-c">
                                                        {$dp}
                                                    </td>
                                                {else}
                                                    <td class="td-c">
                                                        {if $k=='qty'}
                                                            1
                                                        {elseif $k=='rate'}
                                                            1,000.00
                                                        {elseif $k=='total_amount'}
                                                            1,000.00
                                                            {$total_amount=$total_amount+1000.00}
                                                        {/if}
                                                    </td>
                                                {/if}
                                            {/foreach}
                                        </tr>
                                        {$int=$int+1}
                                        <tr>
                                            <td colspan="{count($properties.facility_section.column)-1}">
                                                {$sec5=1}
                                                <span class="pull-right"> Total Rs. ({$sec_col.{$scn}}{$scn=$scn+1})</span>
                                            </td>
                                            <td class="td-c"><b>
                                                    {$sub_total=$sub_total+$total_amount}
                                                    {$total_amount|number_format:2:".":","}</b></td>
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
                                                            <h4 class="invoice-sub-title"> FINAL SUMMARY</h4>
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
                                                            SUB TOTAL <br>(
                                                            {$scn=0}
                                                            {if $sec1==1}
                                                                {$sec_col.{$scn}}{$scn=$scn+1}
                                                            {/if}
                                                            {if $sec2==1}
                                                                {if $scn>0}+{/if} {$sec_col.{$scn}}{$scn=$scn+1}
                                                            {/if}
                                                            {if $sec3==1}
                                                                {if $scn>0}-{/if} {$sec_col.{$scn}}{$scn=$scn+1}
                                                            {/if}
                                                            {if $sec4==1}
                                                                {if $scn>0}+{/if} {$sec_col.{$scn}}{$scn=$scn+1}
                                                            {/if}
                                                            {if $sec5==1}
                                                                {if $scn>0}+{/if} {$sec_col.{$scn}}{$scn=$scn+1}
                                                            {/if}
                                                            {if $discount>0}
                                                                {if $scn>0}-{/if} {$sec_col.{$scn}}{$scn=$scn+1}
                                                            {/if}
                                                            {if $adjustment>0}
                                                                {if $scn>0}-{/if} {$sec_col.{$scn}}{$scn=$scn+1}
                                                            {/if})


                                                        </td>
                                                        <td class="col-md-6 td-c br-0">
                                                            <b>
                                                                {{$sub_total-$discount-$adjustment}|number_format:2:".":","}</b>
                                                        </td>
                                                    </tr>
                                                    {foreach from=$tax item=v}
                                                        <tr>
                                                            <td class="col-md-6 bl-0">
                                                                {$tax_list.{$v}.tax_name}
                                                            </td>
                                                            <td class="col-md-6 td-c br-0">
                                                                {$tax_amount=$sub_total*$tax_list.{$v}.percentage/100}
                                                                {$tax_amount|number_format:2:".":","}
                                                                {$taxtotal={$taxtotal}+{$tax_amount}}
                                                            </td>
                                                        </tr>
                                                    {/foreach}
                                                    {if isset($previousdue)}
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
                                                        <tr style="background-color: #e5e5e5!important;">
                                                            <td class="col-md-6 bl-0">
                                                                <b>Coupon discount Rs.</b>
                                                            </td>
                                                            <td class="col-md-6 td-c br-0">
                                                                <b><span id="discount"> 0.00</span></b>
                                                            </td>
                                                        </tr>
                                                    {/if}
                                                    {if $advance>0}
                                                        <tr style="background-color: #e5e5e5!important;">
                                                            <td class="col-md-6 bl-0">
                                                                <b>Advance received Rs.</b>
                                                            </td>
                                                            <td class="col-md-6 td-c br-0">
                                                                <b>{$advance|number_format:2:".":","}</b>
                                                            </td>
                                                        </tr>
                                                    {/if}
                                                    {if $paid_amount>0}
                                                        <tr style="background-color: #e5e5e5!important;">
                                                            <td class="col-md-6 bl-0">
                                                                <b>Paid amount Rs.</b>
                                                            </td>
                                                            <td class="col-md-6 td-c br-0">
                                                                <b>{$paid_amount|number_format:2:".":","}</b>
                                                            </td>
                                                        </tr>
                                                    {/if}
                                                    <tr style="background-color: #e5e5e5!important;">
                                                        <td class="col-md-6 bl-0">
                                                            <b>Grand total Rs.</b>
                                                        </td>
                                                        <td class="col-md-6 td-c br-0">
                                                            <b><span id="absolute_cost">
                                                                    {($sub_total+$taxtotal)|number_format:2:".":","}</span></b>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td colspan="2" class="col-md-12 bl-0 br-0 sm-text">

                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="9" class="col-md-8" style="border-bottom: 0;">
                                            Note: This is a system generated invoice. No signature required.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
</div>