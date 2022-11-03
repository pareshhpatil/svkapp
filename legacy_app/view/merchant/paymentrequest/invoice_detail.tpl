{if isset($signature.font_file)}
    <link href="{$signature.font_file}" rel="stylesheet">
{/if}
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <!-- END PAGE HEADER-->
    <!-- END PAGE HEADER-->

    <div class="row no-margin">
        {if {$isGuest} =='1'}
            <div class="col-md-2"></div>
            <div class="col-md-8" style="text-align: -webkit-center;text-align: -moz-center;">
            {else}
                <div class="col-md-1"></div>
                <div class="col-md-10" style="text-align: -webkit-center;text-align: -moz-center;">
                {/if}
                {if $invoice_success}
                    <div class="alert alert-block alert-success fade in" style="max-width: 900px;text-align: left;">
                        {if $payment_request_status==11}
                            <h4 class="alert-heading">Preview {if $invoice_type==1} invoice {else} estimate {/if}</h4>
                            <p>
                                Confirm if your {if $invoice_type==1} invoice {else} estimate {/if} fields and values are as per your expectation
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
                    <div class="alert alert-info" style="max-width: 900px;text-align: left;">
                        <strong>Free online transactions for you!</strong>
                        <div class="media">
                            <p class="media-heading">Collect payments for your invoices online. No charges for all your
                                online transactions for the first 5 lakhs. <a href="/merchant/profile/complete/bank">Getting
                                    started.</a></p>
                        </div>

                    </div>
                {/if}
                <br>


                {if isset($error)}
                    <div class="alert alert-danger" style="text-align:left;max-width: 900px;">
                        <div class="media">
                            <p class="media-heading">{$error}</p>
                        </div>

                    </div>
                {/if}
                <div class="invoice" style="text-align: left;">
                    <div class="row invoice-logo  no-margin">

                        <div class="col-md-3">
                            {if {$image_path}!=''}
                                <img src="/uploads/images/logos/{$image_path}" class="img-responsive templatelogo" alt="" />
                            {/if}
                        </div>
                        <div class="col-md-9">
                            <p style="text-align: left">
                                <a href="{$merchant_page}" class="invoice-heading" target="_BLANK">{$company_name}</a>
                                <br>
                                {if $main_company_name!=''}
                                    <span class="muted" style="font-size: 12px;"> (An official franchisee of
                                        {$main_company_name})</span>
                                {/if}
                                <span class="muted">
                                    {foreach from=$main_header item=v}
                                        {if $v.column_name!='Company name' && $v.value!=''}
                                            {if $v.column_name!='Merchant address'}{{$v.column_name|replace:'Merchant ':''}|ucfirst}:{/if}
                                            {$v.value}<br>
                                        {/if}
                                    {/foreach}
                                </span>
                            </p>
                        </div>
                    </div>
                    <hr />
                    {if $info.invoice_type==2}
                        <div class="row no-margin" style="">
                            <div class="col-md-12" style="text-align: center;">
                                <h4><b>{$plugin.invoice_title}</b></h4>
                            </div>
                        </div>
                        <hr />
                    {/if}
                    <div class="row">

                        <div class="col-xs-5 invoice-payment">
                            <ul class="list-unstyled">
                                {foreach from=$customer_breckup item=v}
                                    {if $v.value!=''}
                                        <li><strong>{$v.column_name}:</strong> {$v.value}</li>
                                    {/if}
                                {/foreach}
                            </ul>
                        </div>
                        <div class="col-md-1">
                        </div>
                        <div class="col-md-5 invoice-payment">
                            <ul class="list-unstyled">
                                {foreach from=$header item=v}
                                    {if $v.position=="R" && $v.value!=""}
                                        {if {$v.value|substr:0:7}=='http://' || {$v.value|substr:0:8}=='https://'}
                                            <li><strong>{$v.column_name}:</strong> <a target="_BlANK"
                                                    href="{$v.value}">{$v.column_name}</a></li>
                                        {else}
                                            {if $v.datatype=='date'}
                                                <li><strong>{$v.column_name}:</strong> {$v.value|date_format:"%d %b %Y"}</li>
                                            {elseif $v.datatype=='money'}
                                                <li><strong>{$v.column_name}:</strong> {$v.value|number_format:2:".":","}</li>
                                            {else}
                                                <li><strong>{$v.column_name}:</strong> {$v.value}{if $v.datatype=='percent'} %{/if}
                                                </li>
                                            {/if}
                                        {/if}
                                    {/if}
                                {/foreach}
                            </ul>
                        </div>
                    </div>
                    {if !empty($bds_column)}
                        <h4>Booking Information</h4>
                        <div class="row">
                            <div class="col-md-5 invoice-payment">
                                <ul class="list-unstyled">
                                    {foreach from=$bds_column item=v}
                                        {if $v.position=="L" && $v.value!=""}
                                            {if $v.datatype=='date'}
                                                <li><strong>{$v.column_name}:</strong> {$v.value|date_format:"%d %b %Y"}</li>
                                            {else}
                                                <li><strong>{$v.column_name}:</strong> {$v.value}{if $v.datatype=='percent'} %{/if}
                                                </li>
                                            {/if}
                                        {/if}
                                    {/foreach}
                                </ul>
                            </div>
                            <div class="col-md-1">
                            </div>
                            <div class="col-md-5 invoice-payment">
                                <ul class="list-unstyled">
                                    {foreach from=$bds_column item=v}
                                        {if $v.position=="R" && $v.value!=""}
                                            {if $v.datatype=='date'}
                                                <li><strong>{$v.column_name}:</strong> {$v.value|date_format:"%d %b %Y"}</li>
                                            {else}
                                                <li><strong>{$v.column_name}:</strong> {$v.value}{if $v.datatype=='percent'} %{/if}
                                                </li>
                                            {/if}
                                        {/if}
                                    {/foreach}
                                </ul>
                            </div>
                        </div>
                    {/if}

                    {if !empty($particular)}
                        <h4>Particular details</h4>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-scrollable">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                {foreach from=$particular_column item=v}
                                                    <th class="td-c">
                                                        {$v}
                                                    </th>
                                                {/foreach}
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {$total=0}
                                            {$taxtotal=0}
                                            {$int=1}
                                            {$colspan=0}
                                            {$narrative=0}
                                            {$pk=0}
                                            {foreach from=$particular item=dp}
                                                <tr>
                                                    {foreach from=$particular_column key=k item=v}
                                                        {if $k=='sr_no'}
                                                            <td class="td-c" style="border-top: 0;border-bottom: 0;">
                                                                {$int}
                                                            </td>
                                                        {else}
                                                            {if $k=='narrative'}
                                                                {$narrative=1}
                                                            {/if}
                                                            {if $k=='total_amount'}
                                                                {$colspan=$pk}
                                                            {/if}
                                                            <td class="td-c" style="border-top: 0;border-bottom: 0;">
                                                                {$dp.{$k}}
                                                            </td>
                                                        {/if}
                                                        {$pk=$pk+1}
                                                    {/foreach}
                                                </tr>
                                                {$int=$int+1}
                                            {/foreach}
                                            <tr>
                                                <td colspan="{count($particular_column)-$narrative-1}">
                                                    <b class="pull-left">{$info.particular_total}</b>
                                                </td>
                                                <td class="td-c">
                                                    <b> {$info.basic_amount|number_format:2:".":","}</b>
                                                </td>
                                                {if $narrative==1}
                                                    <td class="td-c"> </td>
                                                {/if}
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    {/if}

                    {if !empty($ticket_detail)}
                        <h4>Booking details</h4>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-scrollable">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th class="td-c">
                                                    Booking Date
                                                </th>
                                                <th class="td-c">
                                                    Journey Date
                                                </th>
                                                <th class="td-c">
                                                    Name
                                                </th>
                                                <th class="td-c">
                                                    Type
                                                </th>
                                                <th class="td-c">
                                                    From
                                                </th>
                                                <th class="td-c">
                                                    To
                                                </th>
                                                <th class="td-c">
                                                    Amt
                                                </th>
                                                <th class="td-c">
                                                    Ser.Ch.
                                                </th>
                                                <th class="td-c">
                                                    Total
                                                </th>


                                            </tr>
                                        </thead>
                                        <tbody>
                                            {$btotal=0}
                                            {$ctotal=0}
                                            {$total=0}
                                            {$has_cancel=0}
                                            {foreach from=$ticket_detail item=v}
                                                {if $v.type==1}
                                                    <tr>
                                                        <td class="td-c">
                                                            {$v.booking_date|date_format:"%d-%m-%Y"}
                                                        </td>
                                                        <td class="td-c">
                                                            {$v.journey_date|date_format:"%d-%m-%Y"}
                                                        </td>
                                                        <td class="td-c">
                                                            {$v.name}
                                                        </td>
                                                        <td class="td-c">
                                                            {$v.vehicle_type}
                                                        </td>
                                                        <td class="td-c">
                                                            {$v.from_station}
                                                        </td>
                                                        <td class="td-c">
                                                            {$v.to_station}
                                                        </td>
                                                        <td class="td-c">
                                                            {$v.amount}
                                                            {$btotal=$btotal+$v.amount}
                                                        </td>
                                                        <td class="td-c">
                                                            {$v.charge}
                                                            {$ctotal=$ctotal+$v.charge}
                                                        </td>
                                                        <td class="td-c">
                                                            {$v.total}
                                                            {$total=$total+$v.total}
                                                        </td>
                                                    </tr>
                                                {else}
                                                    {$has_cancel=1}
                                                {/if}
                                            {/foreach}

                                            <tr>
                                                <td colspan="6">
                                                    <b class="pull-right">Total</b>
                                                </td>
                                                <td class="td-c">
                                                {$info.currency_icon} {$btotal|string_format:"%.2f"}
                                                <td class="td-c">{$info.currency_icon} {$ctotal|string_format:"%.2f"}</td>
                                                <td class="td-c">{$info.currency_icon} {$total|string_format:"%.2f"}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    {/if}
                    {if $has_cancel==1}
                        <h4>Cancellation details</h4>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-scrollable">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th class="td-c">
                                                    Booking Date
                                                </th>
                                                <th class="td-c">
                                                    Journey Date
                                                </th>
                                                <th class="td-c">
                                                    Name
                                                </th>
                                                <th class="td-c">
                                                    Type
                                                </th>
                                                <th class="td-c">
                                                    From
                                                </th>
                                                <th class="td-c">
                                                    To
                                                </th>
                                                <th class="td-c">
                                                    Amt
                                                </th>
                                                <th class="td-c">
                                                    Ser.Ch.
                                                </th>
                                                <th class="td-c">
                                                    Total
                                                </th>


                                            </tr>
                                        </thead>
                                        <tbody>
                                            {$btotal=0}
                                            {$ctotal=0}
                                            {$total=0}
                                            {foreach from=$ticket_detail item=v}
                                                {if $v.type==2}
                                                    <tr>
                                                        <td class="td-c">
                                                            {$v.booking_date|date_format:"%d-%m-%Y"}
                                                        </td>
                                                        <td class="td-c">
                                                            {$v.journey_date|date_format:"%d-%m-%Y"}
                                                        </td>
                                                        <td class="td-c">
                                                            {$v.name}
                                                        </td>
                                                        <td class="td-c">
                                                            {$v.vehicle_type}
                                                        </td>
                                                        <td class="td-c">
                                                            {$v.from_station}
                                                        </td>
                                                        <td class="td-c">
                                                            {$v.to_station}
                                                        </td>
                                                        <td class="td-c">
                                                            {$v.amount}
                                                            {$btotal=$btotal+$v.amount}
                                                        </td>
                                                        <td class="td-c">
                                                            {$v.charge}
                                                            {$ctotal=$ctotal+$v.charge}
                                                        </td>
                                                        <td class="td-c">
                                                            {$v.total}
                                                            {$total=$total+$v.total}
                                                        </td>
                                                    </tr>
                                                {/if}
                                            {/foreach}

                                            <tr>
                                                <td colspan="6">
                                                    <b class="pull-right">Total</b>
                                                </td>
                                                <td class="td-c">
                                                {$info.currency_icon} {$btotal|string_format:"%.2f"}
                                                <td class="td-c">{$info.currency_icon} {$ctotal|string_format:"%.2f"}</td>
                                                <td class="td-c">{$info.currency_icon} {$total|string_format:"%.2f"}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    {/if}

                    {if !empty($tax)}
                        <h4>Tax details</h4>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-scrollable">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th class="col-md-3">
                                                    Tax name
                                                </th>
                                                <th class="hidden-480 col-md-2">
                                                    Percentage
                                                </th>
                                                <th class="hidden-480 col-md-2">
                                                    Applicable
                                                </th>
                                                <th class="hidden-480 col-md-2">
                                                    Amount
                                                </th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            {$total=0}
                                            {foreach from=$tax item=v}
                                                {if $v.tax_name!='' || $v.tax_amount>0}
                                                    <tr>
                                                        <td class="col-md-3 td-c">
                                                            {$v.tax_name}
                                                        </td>
                                                        <td class="hidden-480 col-md-2 td-c">
                                                            {$v.tax_percent}%
                                                        </td>
                                                        <td class="hidden-480 col-md-2 td-c">
                                                            {$v.applicable}
                                                        </td>
                                                        <td class="hidden-480 col-md-2 td-c">
                                                            {$v.tax_amount}
                                                            {$total={$total}+{$v.tax_amount}}
                                                        </td>

                                                    </tr>
                                                {/if}
                                            {/foreach}

                                            <tr>
                                                <td class="col-md-3 td-c">
                                                    <b class="pull-left">{$info.tax_total}</b>
                                                </td>
                                                <td class="hidden-480 col-md-2">

                                                </td>
                                                <td class="hidden-480 col-md-2">

                                                </td>
                                                <td class="hidden-480 col-md-2 td-c">
                                                    <b>{$total|string_format:"%.2f"}</b>
                                                </td>

                                            </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    {/if}
                    <div class="row no-margin mt-1">
                        <div class="col-xs-7 no-padding">
                            <b class="pull-left">Amount (in words) :&nbsp; </b> {$money_words} <br>

                            {if $info.narrative!=''}
                                <div class="well mt-1">
                                    Narrative: {$info.narrative}
                                </div>
                            {/if}

                        </div>
                        <div class="col-xs-5 invoice-block ">
                            <ul class="list-unstyled amounts pull-right">
                                {if $info.advance>0}
                                    <li>
                                        <strong>Total Amount :</strong> {$info.currency_icon}<span>{$info.grand_total+$info.advance|string_format:"%.2f"}</span>
                                    </li>
                                    <li>
                                        <strong>Advance Received :</strong> {$info.currency_icon}<span>{$info.advance|string_format:"%.2f"}</span>
                                    </li>
                                {/if}
                                {if $plugin.has_coupon}
                                    <li>
                                        <strong>Total Amount :</strong> {$info.currency_icon}<span>{$absolute_cost|string_format:"%.2f"}</span>
                                    </li>
                                    <li>
                                        <strong>Coupon Discount :</strong> {$info.currency_icon}<span
                                            id="discount">0.00</span>
                                    </li>
                                {/if}
                                <li>
                                    <strong>Total Amount Payable : {$info.currency_icon}<span
                                        id="absolute_cost"> {$absolute_cost|string_format:"%.2f"}</span></strong>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="row no-margin">
                        <div class="col-xs-12 no-padding">
                            {if $info.tnc!=''}
                                <b>Terms &amp; Conditions</b><br>
                                {$info.tnc}
                            {/if}
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