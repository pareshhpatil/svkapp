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
                        <a class="btn blue input-sm" data-toggle="modal" href="#respond">
                            <i class="fa {$info.currency_icon}"></i> Settle </a>
                        <a class="btn green input-sm" href="/merchant/invoice/update/{$link}">
                            <i class="fa fa-edit"></i> Update {if $invoice_type==1}invoice{else}estimate{/if} </a>
                        <a class="btn green input-sm" target="_BLANk" href="{$whatsapp_share}">
                            <i class="fa fa-whatsapp"></i> Share on Whatsapp</a>
                        <a class="bs_growl_show btn green input-sm" title="Copy Link" data-clipboard-action="copy"
                            data-clipboard-target="linkcopy"> <i class="fa fa-clipboard"></i> Copy invoice link</a>
                        <a class="btn green input-sm" href="/merchant/paymentrequest/download/{$link}">
                            <i class="fa fa-file"></i> Save as PDF</a>
                        <a class="btn green input-sm" href="/merchant/paymentrequest/download/{$link}/2">
                            <i class="fa fa-print"></i> Print</a>
                    </p>
                    <div style="font-size: 0px;">
                        <linkcopy>{$patron_url}</linkcopy>
                    </div>
                {/if}
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
        {if ($payment_gateway_info==true)}
            <!-- Added info box on invoice creation -->
            <div class="alert alert-info" style="max-width: 900px;text-align: left;">
                <strong>Free online transactions for you!</strong>
                <div class="media">
                    <p class="media-heading">Collect payments for your invoices online. No charges for all your online
                        transactions for the first 5 lakhs. <a href="/merchant/profile/complete/bank">Getting started.</a>
                    </p>
                </div>

            </div>
        {/if}
        <div class="invoice" style="padding: 12px 0px 15px 0px;">
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
                        {$gstontop=0}
                        {$panontop=0}
                        {$tanontop=0}
                        {$cinontop=0}
                        {foreach from=$main_header item=v}
                            {if $v.column_name!='Company name' && $v.value!='' && $v.column_name!='Merchant address'}
                                <span class="muted" style="line-height: 20px;font-size: 13px;">
                                    {{$v.column_name|replace:'Merchant ':''}|ucfirst}: {$v.value}<br>
                                </span>
                                {if $v.column_name=='GSTIN Number'}
                                    {$gstontop=1}
                                {/if}
                                {if $v.column_name=='Company pan'}
                                    {$panontop=1}
                                {/if}
                                {if $v.column_name=='Company TAN'}
                                    {$tanontop=1}
                                {/if}
                                {if $v.column_name=='CIN Number'}
                                    {$cinontop=1}
                                {/if}
                            {/if}
                        {/foreach}
                    </p>
                </div>
            </div>
            <div class="row no-margin bg-grey"
                style="border-top: 1px solid black !important;border-bottom: 1px solid black !important;">
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


            {if $info.hide_invoice_summary!=1}
                {if $last_payment==NULL && $adjustment==NULL}

                    <div class="row no-margin"
                        style="border-top: 1px solid black !important;border-bottom: 1px solid black !important; font-size: 14px;line-height: 2.5;">
                        <div class="col-md-2 bg-grey" style="border-right:1px solid black !important;">
                            <strong>{$lang_title.billing_summary}</strong>
                        </div>
                        <div class="col-md-3" style="border-right: 1px solid black;">
                            <strong>{$lang_title.past_due} :</strong> {$previous_due|number_format:2:".":","}
                        </div>
                        <div class="col-md-4" style="border-right: 1px solid black;">
                            <strong>{$lang_title.current_charges} : </strong>{($basic_total+$tax_total)|number_format:2:".":","}
                        </div>
                        <div class="col-md-3 bg-grey" style="">
                            <strong>{$lang_title.total_due} : </strong>{$invoice_total|number_format:2:".":","}
                        </div>
                    </div>
                {else}
                    {if $last_payment!=NULL && $adjustment!=NULL}
                        <div class="row no-margin bg-grey"
                            style="border-top: 1px solid black !important; font-size: 14px;line-height: 2.5;">
                            <div class="col-md-3" style="">
                                <strong>{$lang_title.billing_summary} : </strong>
                            </div>
                        </div>
                        <div class="row no-margin"
                            style="border-top: 1px solid black !important;border-bottom: 1px solid black !important; font-size: 14px;line-height: 2.5;">
                            <div class="col-md-12 no-padding">
                                <table style="width: 100%;text-align: center;margin: 0px !important;">
                                    <tr style="font-size: 12px;border-bottom:1px solid black !important;">
                                        <td style="border-right:1px solid black;">Previous Balance</td>
                                        <td rowspan="2" style="border-right:1px solid black;">&nbsp; - &nbsp;</td>
                                        <td style="border-right:1px solid black;">Last Payments</td>
                                        <td rowspan="2" style="border-right:1px solid black;">&nbsp; - &nbsp;</td>
                                        <td style="border-right:1px solid black;">{$adjustment_col}</td>
                                        <td rowspan="2" style="border-right:1px solid black;">&nbsp; + &nbsp;</td>
                                        <td style="border-right:1px solid black;">Current Bill</td>
                                        <td rowspan="2" style="border-right:1px solid black;">&nbsp; = &nbsp;</td>
                                        <td>Total Due</td>
                                    </tr>
                                    <tr>
                                        <td style="border-right:1px solid black;">{$previous_due|number_format:2:".":","} </td>

                                        <td style="border-right:1px solid black;">{$last_payment|number_format:2:".":","}</td>
                                        <td style="border-right:1px solid black;">{$adjustment|number_format:2:".":","}</td>
                                        <td style="border-right:1px solid black;">
                                            {($basic_total+$tax_total)|number_format:2:".":","}</td>
                                        <td>{$invoice_total|number_format:2:".":","}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    {/if}
                {/if}
            {/if}




            <div class="row">
                <div class="col-md-12">
                    <div class="table-scrollable">
                        <table class="table table-bordered table-condensed">
                            <thead>
                                <tr>
                                    {foreach from=$particular_column key=k item=v}
                                        <th {if $k!='item'} class="td-c" {/if} style="border-bottom: 1px solid #ddd;">
                                            {$v}
                                        </th>
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
                                                <td class="td-c" style="border-top: 0;border-bottom: 0;">
                                                    {$int}
                                                </td>
                                            {else}
                                                <td {if $k!='item'} class="td-c" {/if} style="border-top: 0;border-bottom: 0;">
                                                    {$dp.{$k}}{if $k=='gst' && $dp.{$k}>0}%{/if}
                                                </td>
                                            {/if}
                                        {/foreach}
                                    </tr>
                                    {$int=$int+1}
                                {/foreach}
                                <tr>
                                    {foreach from=$particular_column key=k item=v}
                                        <td style="border-top: 0;border-bottom: 0;">
                                        </td>
                                    {/foreach}
                                </tr>
                                {if count($particular_column)>5}
                                    {$subtotal_colspan=2}
                                    {$colspan=count($particular_column)-3}
                                {else}
                                    {$subtotal_colspan=1}
                                    {$colspan=count($particular_column)-2}
                                {/if}

                                <tr>
                                    <td colspan="{$colspan}" class="col-md-8" style="border-bottom: 0;">
                                        {if $tnc!=''}
                                            <b>Terms & Conditions</b><br>
                                            {$tnc}
                                        {/if}
                                    </td>
                                    <td colspan="{$subtotal_colspan+1}" style="padding:0;">
                                        <table class="table table-bordered"
                                            style="margin:0 !important;border-left: 0px !important;">
                                            <tr>
                                                <td class="col-md-6 bl-0">
                                                    <b>{$lang_title.sub_total}</b>
                                                </td>
                                                <td class="col-md-6 td-c br-0">
                                                    <b> {$info.basic_amount|number_format:2:".":","}</b>
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
                                            <tr>
                                                <td class="col-md-6 bl-0">
                                                    <b>Total</b>
                                                </td>
                                                <td class="col-md-6 td-c br-0">
                                                    {$total=$info.basic_amount+$tax_total}
                                                    <b>{$info.currency_icon} {($total)|number_format:2:".":","}</b>
                                                </td>
                                            </tr>
                                            {if $previous_due>0}
                                                <tr>
                                                    <td class="col-md-6 bl-0">
                                                        {$previous_due_col}
                                                    </td>
                                                    <td class="col-md-6 td-c br-0">
                                                        {$previous_due|number_format:2:".":","}
                                                    </td>
                                                </tr>
                                            {/if}
                                            {if $adjustment>0}
                                                <tr>
                                                    <td class="col-md-6 bl-0">
                                                        {$adjustment_col}
                                                    </td>
                                                    <td class="col-md-6 td-c br-0">
                                                        {$adjustment|number_format:2:".":","}
                                                    </td>
                                                </tr>
                                            {/if}
                                            {if $advance>0}
                                                <tr>
                                                    <td class="col-md-6 bl-0">
                                                        Advance Received
                                                    </td>
                                                    <td class="col-md-6 td-c br-0">
                                                        {($advance)|number_format:2:".":","}
                                                    </td>
                                                </tr>
                                            {/if}
                                            {if $paid_amount>0}
                                                <tr>
                                                    <td class="col-md-6 bl-0">
                                                        Paid Amount
                                                    </td>
                                                    <td class="col-md-6 td-c br-0">
                                                        {($paid_amount)|number_format:2:".":","}
                                                    </td>
                                                </tr>
                                            {/if}
                                            {if $info.late_fee>0}
                                                <tr>
                                                    <td class="col-md-6 bl-0">
                                                        Late Fee
                                                    </td>
                                                    <td class="col-md-6 td-c br-0">
                                                        {($info.late_fee)|number_format:2:".":","}
                                                    </td>
                                                </tr>
                                            {/if}
                                            {if $plugin.has_coupon}
                                                <tr>
                                                    <td class="col-md-6 bl-0">
                                                        Coupon Discount
                                                    </td>
                                                    <td class="col-md-6 td-c br-0" id="discount">
                                                        0.00
                                                    </td>
                                                </tr>
                                            {/if}
                                            {if $total != $absolute_cost || $plugin.has_coupon}
                                                <tr>
                                                    <td class="col-md-6 bl-0">
                                                        <b>Grand Total</b>
                                                    </td>
                                                    <td class="col-md-6 td-c br-0">
                                                        <b id="absolute_cost">
                                                            {($absolute_cost)|number_format:2:".":","}</b>
                                                    </td>
                                                </tr>
                                            {/if}

                                            <tr>
                                                {if $pan!='' && $panontop==0}
                                                    <td class="col-md-6 bl-0">
                                                        {$lang_title.pan_no}.
                                                    </td>
                                                    <td colspan="{$subtotal_colspan}" class="col-md-6 br-0">
                                                        {$pan}
                                                    </td>
                                                {/if}
                                            </tr>

                                            <tr>
                                                {if $tan!='' && $tanontop==0}
                                                    <td class="col-md-6 bl-0">
                                                        TAN NO.
                                                    </td>
                                                    <td colspan="{$subtotal_colspan}" class="col-md-6 br-0">
                                                        {$tan}
                                                    </td>
                                                {/if}
                                            </tr>


                                            <!-- <tr>
                                                {if $cin_no!=''}
                                                    <td class="col-md-6 bl-0">
                                                        CIN NO.
                                                    </td>
                                                    <td colspan="{$subtotal_colspan}" class="col-md-6 br-0">
                                                        {$cin_no}
                                                    </td>
                                                {/if}
                                            </tr>-->


                                            <tr>
                                                {if $gst_number!='' && $gstontop==0}
                                                    <td class="col-md-6 bl-0">
                                                        {$lang_title.gst_no}
                                                    </td>
                                                    <td colspan="{$subtotal_colspan}" class="col-md-6 br-0">
                                                        {$gst_number}
                                                    </td>
                                                {else}
                                                    {if $registration_number!=''}
                                                        <td class="col-md-6 bl-0">
                                                            S. Tax Regn.
                                                        </td>
                                                        <td colspan="{$subtotal_colspan}" class="col-md-6 br-0">
                                                            {$registration_number}
                                                        </td>
                                                    {/if}
                                                {/if}
                                            </tr>

                                            <tr>
                                                <td colspan="2" class="col-md-12 bl-0 br-0">
                                                    <b>{$lang_title.amount_in_word} : </b> {$money_words}
                                                </td>
                                            </tr>

                                        </table>
                                    </td>

                                </tr>

                                {if $plugin.has_signature!=1}
                              
                                    <tr>
                                        <td colspan="{$colspan+$subtotal_colspan+1}" class="col-md-8"
                                            style="border-bottom: 0;">
                                            {$lang_title.invoice_note}
                                        </td>
                                    </tr>
                                {/if}
                            </tbody>
                        </table>

                    </div>

                </div>
            </div>
            
            {if $plugin.has_digital_certificate_file==1}
                <div class="row">
                    <div class="col-md-12">
                        <div class="pull-right" style="margin-right: 20px;">
                            <img src="/images/default_digital_signature.png" style="max-height: 100px;">
                        </div>
                    </div>
                </div>
            {else}
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
            {/if}
        </div>




<!-- END PAGE CONTENT-->