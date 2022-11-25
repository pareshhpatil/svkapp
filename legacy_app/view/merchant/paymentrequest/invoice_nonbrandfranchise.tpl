{if isset($signature.font_file)}
    <link href="{$signature.font_file}" rel="stylesheet">
{/if}
<div class="page-content">
    <div style="text-align: -webkit-center;text-align:-moz-center;">
        <h3 class="page-title">
            &nbsp;
        </h3>
        {if $invoice_success}
            <div class="alert alert-block alert-success fade in" style="max-width: 900px;text-align: left;">
                {if $notify_patron==1}
                    {if $invoice_type==1}
                        <h4 class="alert-heading">Payment request sent!</h4>
                        <p>
                            Your invoice has been sent to your customer. You will receive a notification as soon as your customer
                            makes the payment.
                        </p>
                    {else}
                        <h4 class="alert-heading">Estimate sent!</h4>
                        <p>
                            Your estimate has been sent to your customer. You will receive a notification as soon as your customer
                            makes the payment, along with the final invoice copy.
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
                    <a class="btn blue input-sm" href="/merchant/invoice/update/{$link}">
                        Update {if $invoice_type==1}invoice{else}estimate{/if} </a>
                    <a class="btn green input-sm" target="_BLANk" href="{$whatsapp_share}">
                        <i class="fa fa-whatsapp"></i> Share on Whatsapp</a>
                    <a class="bs_growl_show btn green input-sm" title="Copy Link" data-clipboard-action="copy"
                        data-clipboard-target="linkcopy"> <i class="fa fa-clipboard"></i> Copy invoice link</a>

                </p>
                <div style="font-size: 0px;">
                    <linkcopy>{$patron_url}</linkcopy>
                </div>
            </div>
        {/if}
        {if isset($error)}
            <div class="alert alert-danger" style="max-width: 900px;text-align: left;">
                <div class="media">
                    <p class="media-heading">{$error}</p>
                </div>

            </div>
        {/if}

        <!-- END PAGE HEADER-->
        <div class="invoice">
            <div class="row invoice-logo  no-margin" style="margin-bottom: 0px;">
                <div class="col-md-3" style="min-width: 150px;line">
                    {if {$image_path}!=''}
                        <img src="/uploads/images/logos/{$image_path}" class="img-responsive templatelogo" alt="" />
                    {/if}
                </div>
                <div class="col-md-8">
                    <p style="text-align: left;">
                        <a href="{$merchant_page}" style="font-size: 27px;" target="_BLANK">{$company_name}</a> <br>
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
            <div class="row no-margin bg-grey">
                <div class="col-md-12" style="text-align: center;">
                    {if $info.invoice_type==2}
                        <h4><b>{$plugin.invoice_title}</b></h4>
                    {else}
                        <h4><b>{if !empty($tax)}{$lang_title.tax} {/if}{$lang_title.invoice}</b></h4>
                    {/if}
                </div>
            </div>
            <div class="row no-margin">
                <div class="col-md-6" style="padding-left: 0px;padding-right: 0px;">
                    <div class="" style="">
                        <table class="table table-bordered table-condensed" style="margin: 0px 0 0px 0 !important;">

                            {foreach from=$customer_breckup item=v}
                                {if $v.value!=''}
                                    <tr>
                                        <td style="min-width: 120px;"><b>{$v.column_name}</b></td>
                                        <td>{$v.value}</td>
                                    </tr>
                                {/if}
                            {/foreach}
                            </tbody>
                        </table>
                    </div>

                </div>

                <div class="col-md-6 invoice-payment" style="padding-left: 0px;padding-right: 0px;">
                    <div class="">
                        <table class="table table-bordered table-condensed" style="margin: 0px 0 0px 0 !important;">
                            {$last_payment=NULL}
                            {$adjustment=NULL}
                            {foreach from=$header item=v}
                                {if $v.position=="R" && $v.value!="" && $v.function_id!=4}
                                    {if $v.function_id==11}
                                        {$last_payment=$v.value}
                                    {else if $v.function_id==12}
                                        {$adjustment=$v.value}
                                    {else}
                                        <tr>
                                            <td style="min-width: 120px;">
                                                <b>{$v.column_name}</b>
                                            </td>
                                            {if {$v.value|substr:0:7}=='http://' || {$v.value|substr:0:8}=='https://'}
                                                <td style="min-width: 120px;"> <a target="_BlANK" href="{$v.value}">{$v.column_name}</a>
                                                </td>
                                            {else}
                                                <td style="min-width: 120px;">
                                                    {if $v.datatype=='money'}
                                                        {$v.value|number_format:2:".":","}
                                                    {else}
                                                        {$v.value} {if $v.datatype=='percent'} %{/if}
                                                    {/if}
                                                </td>
                                            {/if}

                                        </tr>
                                    {/if}
                                {/if}
                            {/foreach}

                            <tr>
                                <td style="min-width: 120px;">
                                    <b>Bill Period</b>
                                </td>
                                <td style="min-width: 120px;"> {$sale_summary.bill_period}</a>
                                </td>
                            </tr>



                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

            <div class="row">
                <div class="col-md-12 no-padding">
                    <div class="col-md-4 pr-0">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td colspan="3" class="td-c">
                                        <b>Daily Gross Sales Offline</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="td-c">
                                        Date
                                    </td>
                                    <td class="td-c">
                                        Chetty's
                                    </td>
                                    <td class="td-c">
                                        Non Chetty's
                                    </td>
                                </tr>
                                {foreach from=$sale_details item=v}
                                    <tr>
                                        <td class="td-c">
                                            {$v.date|date_format:"%d-%m-%Y"}
                                        </td>
                                        <td class="td-c">
                                            {$v.gross_sale}
                                        </td>
                                        <td class="td-c">
                                            {$v.non_brand_gross_sale}
                                        </td>
                                    </tr>
                                {/foreach}
                            </tbody>
                        </table>
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td colspan="3" class="td-c">
                                        <b>Daily Gross Sales Online</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="td-c">
                                        Date
                                    </td>
                                    <td class="td-c">
                                        Chetty's
                                    </td>
                                    <td class="td-c">
                                        Non Chetty's
                                    </td>
                                </tr>
                                {foreach from=$sale_details item=v}
                                    <tr>
                                        <td class="td-c">
                                            {$v.date|date_format:"%d-%m-%Y"}
                                        </td>
                                        <td class="td-c">
                                            {$v.gross_sale_online}
                                        </td>
                                        <td class="td-c">
                                            {$v.non_brand_gross_sale_online}
                                        </td>
                                    </tr>
                                {/foreach}
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-8">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td class="td-c">
                                        <b>Summary</b>
                                    </td>
                                    <td colspan="4" class="td-c">
                                        <b>Franchise Fees</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="td-c">

                                    </td>
                                    <td colspan="2" class="td-c">
                                        Chetty's

                                    </td>
                                    <td colspan="2" class="td-r">
                                        Non chetty's
                                    </td>
                                </tr>
                                <tr>
                                    <td class="td-c">
                                        Gross Billable Sales Offline
                                    </td>
                                    <td colspan="2" class="td-r">
                                        {$sale_summary.gross_sale|string_format:"%.2f"}
                                    </td>
                                    <td colspan="2" class="td-r">
                                        {$sale_summary.non_brand_gross_sale|string_format:"%.2f"}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="td-c">
                                        CGST and SGST 5.00 %
                                    </td>
                                    <td colspan="2" class="td-r">
                                        {{$sale_summary.gross_sale-$sale_summary.net_sale}|string_format:"%.2f"}
                                    </td>
                                    <td colspan="2" class="td-r">
                                        {{$sale_summary.non_brand_gross_sale-$sale_summary.non_brand_net_sale}|string_format:"%.2f"}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="td-c">
                                        Gross Billable Sales Online
                                    </td>
                                    <td colspan="2" class="td-r">
                                        {$sale_summary.gross_sale_online|string_format:"%.2f"}
                                    </td>
                                    <td colspan="2" class="td-r">
                                        {$sale_summary.non_brand_gross_sale_online|string_format:"%.2f"}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="td-c">
                                        CGST and SGST 5.00 %
                                    </td>
                                    <td colspan="2" class="td-r">
                                        {{$sale_summary.gross_sale_online-$sale_summary.net_sale_online}|string_format:"%.2f"}
                                    </td>
                                    <td colspan="2" class="td-r">
                                        {{$sale_summary.non_brand_gross_sale_online-$sale_summary.non_brand_net_sale_online}|string_format:"%.2f"}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="td-c">
                                        Delivery partner commision
                                    </td>
                                    <td colspan="2" class="td-r">
                                        {$sale_summary.delivery_partner_commision|string_format:"%.2f"}
                                    </td>
                                    <td colspan="2" class="td-r">
                                        {$sale_summary.non_brand_delivery_partner_commision|string_format:"%.2f"}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="td-c">
                                        Net Billable Sales
                                    </td>
                                    <td colspan="2" class="td-r">
                                        {$sale_summary.net_sale+$sale_summary.net_sale_online-$sale_summary.delivery_partner_commision|string_format:"%.2f"}
                                    </td>
                                    <td colspan="2" class="td-r">
                                        {$sale_summary.non_brand_net_sale+$sale_summary.non_brand_net_sale_online-$sale_summary.non_brand_delivery_partner_commision|string_format:"%.2f"}
                                    </td>
                                </tr>

                                <tr>
                                    <td class="td-c">
                                        Gross Franchisee Fee on Net Billable
                                    </td>
                                    <td class="td-r">
                                        {$sale_summary.commision_fee_percent}%
                                    </td>
                                    <td class="td-r">
                                        {$sale_summary.gross_fee|string_format:"%.2f"}
                                    </td>
                                    <td class="td-r">
                                        {$sale_summary.non_brand_commision_fee_percent}%
                                    </td>
                                    <td class="td-r">
                                        {$sale_summary.non_brand_gross_fee|string_format:"%.2f"}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="td-c">
                                        Less: Waiver
                                    </td>
                                    <td class="td-r">
                                        {$sale_summary.commision_waiver_percent}%
                                    </td>
                                    <td class="td-r">
                                        {$sale_summary.waiver_fee|string_format:"%.2f"}
                                    </td>
                                    <td class="td-r">
                                        {$sale_summary.non_brand_commision_waiver_percent}%
                                    </td>
                                    <td class="td-r">
                                        {$sale_summary.non_brand_waiver_fee|string_format:"%.2f"}
                                    </td>

                                </tr>
                                <tr>
                                    <td class="td-c">
                                        Net Franchise Fee receivable
                                    </td>
                                    <td class="td-r">
                                        {$sale_summary.commision_net_percent}%
                                    </td>
                                    <td class="td-r">
                                        {$sale_summary.net_fee|string_format:"%.2f"}
                                    </td>
                                    <td class="td-r">
                                        {$sale_summary.non_brand_commision_net_percent}%
                                    </td>
                                    <td class="td-r">
                                        {$sale_summary.non_brand_net_fee|string_format:"%.2f"}
                                    </td>

                                </tr>
                                <tr>
                                    <td class="td-c">
                                        Penalty on outstanding amt
                                    </td>
                                    <td colspan="4" class="td-r">
                                        {$sale_summary.penalty|string_format:"%.2f"}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="td-c">
                                        Franchisee fees Payable
                                    </td>
                                    <td colspan="4" class="td-r">
                                        {$info.basic_amount|string_format:"%.2f"}
                                    </td>
                                </tr>
                                {$taxtotal=0}
                                {foreach from=$tax item=v}
                                    <tr>
                                        <td class="td-c">
                                            {$v.tax_name}
                                        </td>
                                        <td colspan="4" class="td-r">
                                            {$v.tax_amount}
                                            {$taxtotal={$taxtotal}+{$v.tax_amount}}
                                        </td>
                                    </tr>
                                {/foreach}
                                <tr>
                                    <td class="td-c">
                                        Total Amount (FEE)
                                    </td>
                                    <td colspan="4" class="td-r">
                                        {$info.basic_amount+$taxtotal|string_format:"%.2f"}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="td-c">
                                        Adjustment
                                    </td>
                                    <td colspan="4" class="td-r">
                                        {$info.advance|string_format:"%.2f"}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="td-c">
                                        Previous outstanding
                                    </td>
                                    <td colspan="4" class="td-r">
                                        {$info.previous_due|string_format:"%.2f"}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="td-c">
                                        Total royalty to be Paid with Previous outstanding
                                    </td>
                                    <td colspan="4" class="td-r">
                                        {$info.absolute_cost|string_format:"%.2f"}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="td-c">
                                        Rounded off to
                                    </td>
                                    <td colspan="4" class="td-r">
                                        {$grand_total|string_format:"%.0f"}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="5">
                                        {$lang_title.amount_in_word} : {$money_words}
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>





            <div class="row">
                <div class="col-md-12">
                    <div class="table-scrollable" style="border: 0;">
                        <table class="table ">
                            <tbody>
                                <tr>
                                    <td style="border-bottom: 0;border-top: 0;">
                                        {if $tnc!=''}
                                            {$tnc}
                                        {/if}
                                    </td>
                                    <td style="border-bottom: 0;border-top: 0;width: 40%;">
                                        <table class="table">
                                            <tr>
                                                {if $cin_no!=''}
                                                    <td class="col-md-6 bl-0">
                                                        CIN No
                                                    </td>
                                                    <td class="col-md-6 br-0">
                                                        {$cin_no}
                                                    </td>
                                                {/if}
                                            </tr>
                                            <tr>
                                                {if $info.pan!=''}
                                                    <td class="col-md-6 bl-0">
                                                        Income Tax PAN
                                                    </td>
                                                    <td class="col-md-6 br-0">
                                                        {$info.pan}
                                                    </td>
                                                {/if}
                                            </tr>
                                            <tr>
                                                {if $info.gst_number!=''}
                                                    <td class="col-md-6 bl-0" style="border-bottom-width: 1 !important;">
                                                        GST No
                                                    </td>
                                                    <td class="col-md-6 br-0" style="border-bottom-width: 1 !important;">
                                                        {$info.gst_number}
                                                    </td>
                                                {/if}
                                            </tr>
                                            <tr>
                                                {if $info.sac_code!=''}
                                                    <td class="col-md-6 bl-0">
                                                        HSN number
                                                    </td>
                                                    <td class="col-md-6 br-0">
                                                        {$info.sac_code}
                                                    </td>
                                                {/if}
                                            </tr>
                                            <tr>
                                                {if $info.business_category!=''}
                                                    <td class="col-md-6 bl-0">
                                                        Category of Service
                                                    </td>
                                                    <td class="col-md-6 br-0">
                                                        {$info.business_category}
                                                    </td>
                                                {/if}
                                            </tr>
                                        </table>
                                    </td>

                                </tr>

                        </table>
                        </td>

                        </tr>

                        {if $plugin.has_signature!=1}
                            <tr>
                                <td colspan="{$colspan+$subtotal_colspan+1}" class="col-md-8" style="border-bottom: 0;">
                                    {$lang_title.invoice_note}
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