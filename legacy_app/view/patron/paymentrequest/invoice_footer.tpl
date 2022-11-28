{if $payment_request_status!=3}
    <div class="invoice mt-1">
        <div class="row no-margin">
            {if $plugin.has_deductible==1}
                <div class="row no-margin">
                    <div class="col-md-12 no-margin font-blue">
                        <label>Deductions applicable to this invoice can be adjusted after clicking Pay Now</label>
                    </div>
                </div>
            {/if}

            {if $info.document_url!=''}
                <div class="row" id="document_div" style="display: none;">
                    <button class="btn btn-default pull-right" onclick="showDocument(2);" style="margin-right: 15px"><i
                            class="fa fa-close"></i></button>
                    <div class="col-md-12">
                        <div style="max-height: 700px;overflow: auto;">
                            {if {$info.document_url|substr:-3|lower}=='pdf'}
                                <iframe src="{$info.document_url}" frameborder="no" width="100%" height="500px">
                                </iframe>
                            {else}
                                <img src="{$info.document_url}" style="width: 100%;max-width: 100%;">
                            {/if}
                        </div>
                        <br>

                    </div>
                </div>
            {/if}

            <form action="/patron/paymentrequest/pay/{$Url}" method="post">
                {if $is_online_payment==TRUE && $grand_total>0}
                    {if !empty($coupon_details)}
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table" style="background-color: #fcf8e3;">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <input type="hidden" class="displayonly" id="coupon_used" readonly
                                                    name="coupon_used" value="0" />
                                                <input type="hidden" class="displayonly" name="coupon_id"
                                                    value="{$coupon_details.coupon_id}" />
                                                <input type="hidden" class="displayonly" name="discount" id="discount_amt"
                                                    value="" />
                                                <input type="hidden" class="displayonly" id="c_percent"
                                                    value="{$coupon_details.percent}" />
                                                <input type="hidden" class="displayonly" id="bill_total" value="{$invoice_total}" />
                                                <input type="hidden" class="displayonly" id="surcharge_amount"
                                                    value="{$surcharge_amount}" />
                                                <input type="hidden" class="displayonly" id="fee_id" value="{$fee_id}" />
                                                <input type="hidden" class="displayonly" id="grand_total" value="{$grand_total}" />
                                                <input type="hidden" class="displayonly" id="c_fixed_amount"
                                                    value="{$coupon_details.fixed_amount}" />
                                                <input type="hidden" class="displayonly" id="paymenturl"
                                                    value="/patron/paymentrequest/pay/{$Url}" />
                                                Coupon discount : <b>{if $coupon_details.type==1}
                                                    Rs.{$coupon_details.fixed_amount}/- {else}{$coupon_details.percent}%
                                                    {/if}</b>
                                                <p class="small">{$coupon_details.descreption}</p>
                                            </td>
                                            <td>
                                                <a class="btn blue pull-right" href="javascript:" id="btn_apply_coupun"
                                                    style="display: block;" onclick="handleCoupon(1,{$coupon_details.type});">Apply
                                                    Coupon <i class="fa fa-check"></i></a>
                                                <a class="btn red pull-right" href="javascript:" id="btn_remove_coupon"
                                                    style="display: none;" onclick="handleCoupon(2,{$coupon_details.type});">Remove
                                                    Coupon <i class="fa fa-remove"></i></a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    {else}
                        {if $plugin.has_coupon}
                            <div class="row no-margin">
                                <br>

                                <div class="col-md-3">

                                    <input type="text" id="coupon_code" placeholder="Enter coupon code" class="form-control" value="" />
                                    <input type="hidden" class="displayonly" id="coupon_id" name="coupon_id"
                                        value="{$coupon_details.coupon_id}" />
                                    <br>
                                </div>
                                <div class="col-md-2">
                                    <label class="font-red">&nbsp;</label>
                                    <button onclick="return validateCoupon('{$info.merchant_user_id}', '{$grand_total}');"
                                        class="btn green">Apply</button>
                                </div>
                                <div class="col-md-5">
                                    <label class="font-blue" id="coupon_status">&nbsp;</label>

                                </div>

                            </div>
                        {/if}
                    {/if}
                {/if}

                <div class="row no-margin">
                    <div class="col-md-12 invoice-block">
                        {if $plugin.has_digital_certificate_file==1}
                            <a class="btn btn-link hidden-print margin-bottom-5" target="_BLANK" style=""
                                href="/patron/paymentrequest/download_tcpdf/{$Url}/2">
                                Print
                            </a>
                            <a class="btn btn-link hidden-print margin-bottom-5" style=""
                                href="/patron/paymentrequest/download_tcpdf/{$Url}">
                                {$lang_title.save_pdf}
                            </a>

                        {else}
                            <a class="btn btn-link hidden-print margin-bottom-5" target="_BLANK" style=""
                                href="/patron/paymentrequest/download/{$Url}/2">
                                Print
                            </a>
                            <a class="btn btn-link hidden-print margin-bottom-5" style=""
                                href="/patron/paymentrequest/download/{$Url}">
                                {$lang_title.save_pdf}
                            </a>

                        {/if}
                        {if $info.document_url!=''}
                            <a onclick="showDocument(1);" class="btn btn-link hidden-print margin-bottom-5">
                                {$plugin.upload_file_label}
                            </a>
                        {/if}
                        {if $is_online_payment==TRUE && $grand_total>0}

                            <!--<a data-toggle="modal" href="#guestpay" class="btn green hidden-print margin-bottom-5">
                        Pay now 
                    </a>-->
                            {if $info.payment_request_status==6}
                                <a id="onlinepay" href="/patron/paymentrequest/view/{$invoice_link}"
                                    class="btn blue hidden-print margin-bottom-5">
                                    View Invoice
                                </a>
                            {else}
                                {if $plugin.has_partial==1}
                                    <button type="submit" name="partial" class="btn green hidden-print margin-bottom-5">
                                        Pay Partial Amount
                                    </button>
                                {/if}
                                {if $plugin.autocollect_plan_id>0}
                                    <button type="submit" name="autopay" class="btn green hidden-print margin-bottom-5">
                                        Enable auto payment
                                    </button>
                                {/if}
                                {if $plugin.has_online_payments==1 && $plugin.enable_payments==0}

                                {else}
                                    <button type="submit" name="paynow" class="btn blue hidden-print margin-bottom-5">
                                        Pay Now
                                    </button>
                                {/if}
                            {/if}
                        {/if}
                    </div>
                </div>
            </form>

            {if $show_ad==1}
                <div class="col-md-12 center">
                    <a target="_BLANK" href="/referrer/campaign/{$payment_request_id}/1"><img src="/images/adv/ditto.jpg"
                            class="img-responsive" style="display: inline;" /></a>
                </div>
            {/if}
        </div>

        {if !empty($partial_payments)}

            <div class="portlet-body">
                <h4><b>Payment details</b></h4>
                <div class="table-scrollable">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th class="td-c">
                                    Transaction ID
                                </th>
                                <th class="td-c">
                                    Payment date
                                </th>
                                <th class="td-c">
                                    Payment mode
                                </th>
                                <th class="td-c">
                                    Amount
                                </th>
                            </tr>
                            {foreach from=$partial_payments item=v}
                                <tr>
                                    <td class="td-c">
                                        {$v.transaction_id}
                                    </td>
                                    <td class="td-c">
                                        {$v.payment_date}
                                    </td>
                                    <td class="td-c">
                                        {$v.payment_mode}
                                    </td>
                                    <td class="td-c">
                                        {$v.amount}
                                    </td>
                                </tr>
                            {/foreach}
                        </tbody>
                    </table>
                </div>
            </div>
        {/if}


    </div>
{/if}

<!-- END PAGE CONTENT-->
<div class="row" style="max-width: 900px;margin-top: 5px;">
<div class="col-md-12 no-margin no-padding" style="text-align: left;max-width: 900px;margin-top:10px;">
        {if $info.paid_user==0}
            <div onclick="location.href = '/merchant/register';" style="cursor: pointer;"
                class="alert alert-success nolr-margin">
                <p>
                    <b>{$lang_title.footer_note}</b>
                </p>
            </div>
        {/if}
        <p><img src="/assets/admin/layout/img/logo.png" class="img-responsive pull-right powerbyimg" alt="" /><span
                class="powerbytxt">Powered by</span> </p>
        <br>
        <br>

    </div>
</div>
</div>
<div class="col-md-1"></div>
</div>
<!-- END PAGE CONTENT-->
</div>
</div>
<!-- END CONTENT -->