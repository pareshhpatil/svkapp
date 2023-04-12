{* {if $paypal_id!=''}
    <script src="https://www.paypal.com/sdk/js?client-id={$paypal_id}&locale=en_IN&currency=INR" data-client-token="">
    </script>
{/if} *}

<div class="page-content">
    <div class="loading" id="loader" style="display: none;">Processing your payment Please wait&#8230;</div>
    <!-- BEGIN PAGE HEADER-->
    <h3 class="page-title">&nbsp;</h3>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row no-margin">
        {if {$isGuest} =='1'}
            <div class="col-md-2"></div>
            <div class="col-md-8" style="text-align: -webkit-center;text-align: -moz-center;">
            {else}
                <div class="col-md-1"></div>
                <div class="col-md-10" style="text-align: -webkit-center;text-align: -moz-center;">
                {/if}

                {if isset($errors)}
                    <div class="alert alert-danger alert-dismissable" style="max-width: 900px;text-align: left;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                        <strong>Error!</strong>
                        <div class="media">
                            {foreach from=$errors key=k item=v}
                                <p class="media-heading">{$k} - {$v.1}</p>
                            {/foreach}
                        </div>

                    </div>
                {/if}

                <div class="" id="error"
                    style="display: none;padding: 15px;background-color: #f2dede;border-color: #ebccd1;color: #a94442;">
                </div>
                <div style="max-width: 900px;text-align: left;">
                    <div class="portlet light bordered">
                        <div class="portlet-title">
                            <div class="col-md-8">
                                <div class="caption font-blue" style="">
                                    <span class="caption-subject bold uppercase">
                                        <h3>{$info.company_name}</h3>
                                    </span>
                                </div>
                            </div>
                            {if $is_partial!=true}
                                <div class="col-md-4">
                                    <div class="caption font-blue pull-right">
                                        <h2 class="">{$currency_icon} <span
                                                id="grandtotal">{$amount|number_format:2:".":","}/-</span></h2>
                                        {if $surcharge_amount>0}
                                            <p>Including convenience fee : &nbsp;{$currency_icon} {$surcharge_amount}</p>
                                        {else}
                                            {if $pg_surcharge_enabled==1}
                                                <span style="text-align: right !important;font-size: 12px;">Convenience fee of
                                                    applicable for online payments based on payment mode
                                                </span>
                                            {/if}
                                        {/if}

                                    </div>
                                </div>
                            {/if}

                        </div>
                        <div class="portlet-body form">
                            <!--<h3 class="form-section">Profile details</h3>-->
                            <form action="{$post_link}" method="post" id="submit_form"
                                class="form-horizontal form-row-sepe">
                                <div class="form-body">
                                    <!-- Start profile details -->
                                    <div class="row">
                                        <div class="col-md-12">

                                            {if $is_partial==true}
                                                <div class="row">
                                                    <h4> Enter partial amount</h4>
                                                    <div class="col-md-6">
                                                        <div class="row">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">Partial Amount <span
                                                                        class="required">*
                                                                    </span></label>
                                                                <div class="col-md-8">
                                                                    <input type="hidden" name="is_partial" value="1">
                                                                    <input type="number"
                                                                        placeholder="Minimum amount {$plugin.partial_min_amount}"
                                                                        min="{$plugin.partial_min_amount}"
                                                                        max="{$info.absolute_cost}" class="form-control"
                                                                        required name="partial_amount" />
                                                                    <span class="help-block"> </span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            {/if}
                                            <div class="row">
                                                <h4> {$lang_title.enter_billing_details}</h4>
                                                <div class="col-md-6">
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label
                                                                class="control-label col-md-4">{$customer_default_column.customer_name|default:$lang_title.customer_name}
                                                                <span class="required">
                                                                    * </span></label>
                                                            <div class="col-md-8">
                                                                <input type="text" required name="name"
                                                                    class="form-control" value="{$info.customer_name}">
                                                                <span class="help-block"> </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label
                                                                class="control-label col-md-4">{$customer_default_column.email|default:$lang_title.email}
                                                                <span class="required">
                                                                    * </span></label>
                                                            <div class="col-md-8">
                                                                <input type="email" name="email" class="form-control"
                                                                    value="{$info.customer_email}">
                                                                <span class="help-block"> </span>
                                                            </div>

                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label
                                                                class="control-label col-md-4">{$customer_default_column.mobile|default:$lang_title.mobile}
                                                                <span class="required">
                                                                    * </span> </label>

                                                            {* <div class="col-md-8">
                                                                <input type="text" required name="mobile"
                                                                    {$validate.mobile} class="form-control"
                                                                    value="{$info.customer_mobile}">
                                                                <span class="help-block"> </span>
                                                            </div> *}
                                                            <div class="col-md-8">
                                                                <div class="input-group">
                                                                    <span class="input-group-addon" id="country_code_txt">{$info.country_mobile_code}</span>
                                                                    <input type="text" required pattern='{($info.customer_country=='India') ? '([0-9]{10})' : '([0-9]{7,10})'}' title="Enter your valid mobile number" maxlength="10" id="mobile" aria-describedby="mobile-error" name="mobile" value="{$info.customer_mobile}" class="form-control">
                                                                </div>
                                                                <span id="mobile-error" class="help-block help-block-error"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label
                                                                class="control-label col-md-4">{$customer_default_column.address|default:$lang_title.address}
                                                                <span class="required">
                                                                </span> </label>
                                                            <div class="col-md-8">
                                                                <textarea type="text" name="address" {$validate.address}
                                                                    class="form-control">{$info.customer_address}</textarea>
                                                                <span class="help-block"> </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label class="control-label col-md-4">{$customer_default_column.country|default:'Country'}<span class="required"></span></label>
                                                            <div class="col-md-8">
                                                                <select name="country" class="form-control select2me"
                                                                    data-placeholder="Select..." onchange="showStateDiv(this.value);">
                                                                    <option value="">Select Country</option>
                                                                    {foreach from=$country_code item=v}
                                                                        <option {if $v.config_value==$info.customer_country} selected {/if} value="{$v.config_value}">{$v.config_value}</option>
                                                                    {/foreach}
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label
                                                                class="control-label col-md-4">{$customer_default_column.city|default:$lang_title.city}
                                                                <span class="required">
                                                                </span> </label>
                                                            <div class="col-md-8">
                                                                <input type="text" name="city" {$validate.name}
                                                                    class="form-control" value="{$info.customer_city}">
                                                                <span class="help-block"> </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label
                                                                class="control-label col-md-4">{$customer_default_column.state|default:$lang_title.state}<span
                                                                    class="required">
                                                                </span></label>
                                                            <div class="col-md-8" id="state_drpdown" {($info.customer_country=='India') ? 'style=display:block' : 'style=display:none'}>
                                                                <select name="state" class="form-control select2me"
                                                                    data-placeholder="Select...">
                                                                    <option value="">Select State</option>
                                                                    {foreach from=$state_code item=v}
                                                                        <option {if $info.customer_state==$v.config_value} selected {/if}
                                                                            value="{$v.config_value}">{$v.config_value}</option>
                                                                    {/foreach}
                                                                </select>
                                                            </div>
                                                            <div class="col-md-8" id="state_txt" {($info.customer_country=='India') ? 'style=display:none' : 'style=display:block'}>
                                                                <input type="text" name="state1" value="{$info.customer_state}"
                                                                    class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label
                                                                class="control-label col-md-4">{$customer_default_column.zipcode|default:$lang_title.zipcode}<span
                                                                    class="required">
                                                                </span> </label>
                                                            <div class="col-md-8">
                                                                <input type="digit" name="zipcode" {$validate.zipcode}
                                                                    class="form-control" value="{$info.customer_zip}">
                                                                <span class="help-block"> </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                            {if !empty($plugin.deductible)}
                                                <div class="row">
                                                    <h4> * Select applicable deductible</h4>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <div class="col-md-10">
                                                                <select name="selecttemplate"
                                                                    onchange="calculatededuct('{$info.tax_amount}', '{$info.basic_amount-$info.advance}', '{$info.Previous_dues}');"
                                                                    id="deduct" required class="form-control"
                                                                    data-placeholder="Select...">
                                                                    <option value="0">Select deductible</option>
                                                                    {foreach from=$plugin.deductible item=v}
                                                                        <option value="{$v.total}">{$v.tax_name} ({$v.percent}
                                                                            %)</option>
                                                                    {/foreach}
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <table class="table table-condensed table-bordered "
                                                            style="margin-bottom: 0px;">
                                                            <tr style="font-weight: bold;">
                                                                <th style="width:50%;">Particulars total : </th>
                                                                <th>{$info.basic_amount}</th>
                                                            </tr>

                                                            <tr style="font-weight: bold;">
                                                                <th style="width: 15%;">Total tax :</th>
                                                                <th id="total_tax">{$info.tax_amount}</th>
                                                            </tr>
                                                            <tr style="font-weight: bold;">
                                                                <th id="deduct_label" style="width: 50%;">Select deductible
                                                                    :</th>
                                                                <th id="deduct_val">0.00</th>
                                                            </tr>
                                                            {if $is_coupon==1}
                                                                <tr style="font-weight: bold;">
                                                                    <th style="width: 15%;">Coupon discount :</th>
                                                                    <th id="coupon_discount_display">{$discount}</th>
                                                                </tr>
                                                            {/if}
                                                            <tr style="font-weight: bold;">
                                                                <th style="width: 15%;">Grand total :</th>
                                                                <th id="deductgrandtotal">
                                                                    {$absolute_cost|string_format:"%.2f"}</th>
                                                            </tr>
                                                        </table>
                                                        <span class="help-block"> </span>
                                                    </div>
                                                </div>

                                            {/if}

                                            {if $is_new_pg == false}
                                                {if isset($radio)}
                                                    <div class="form-md-radios">
                                                        <hr>
                                                        <h4> Select payment modes {if $surcharge_amount>0} <span id="conv"
                                                                    style="font-size: 13px;font-weight: 500;">(Convenience fee of <i
                                                                        class="fa  fa-inr"></i>
                                                                    {$surcharge_amount|string_format:"%.2f"}/- applicable for online
                                                                payments)</span>{/if}</h4>
                                                        <div id="form_gender_error"></div>
                                                        <div class="md-radio-inline">
                                                            <span id="payment_mode-error"
                                                                class="help-block help-block-error"></span>
                                                            {$int=6}
                                                            {foreach from=$radio item=v}
                                                                {if $v.name!='PAYPAL'}
                                                                    {if $int==6}
                                                                        <div class="row">
                                                                        {/if}
                                                                        <div class="col-md-3">
                                                                            <div class="md-radio" style="margin-left: 10px;">
                                                                                <input type="radio" id="radio{$int}" name="payment_mode"
                                                                                    onchange="getgrandtotal('{$encrypt_grandtotal}', '{$v.fee_id}');"
                                                                                    value="{$v.fee_id}" aria-required="true"
                                                                                    aria-describedby="payment_mode-error" required
                                                                                    class="md-radiobtn blue">
                                                                                <label for="radio{$int}">
                                                                                    <span></span>
                                                                                    <span class="check"></span>
                                                                                    <span class="box"></span>
                                                                                    {$v.name}
                                                                                </label>
                                                                                {$int=$int+1}
                                                                            </div>
                                                                        </div>
                                                                    {else}
                                                                    </div>
                                                                    <hr>
                                                                    <h4>Payment Offers</h4>
                                                                    <div class="row" style="margin-left: 10px;">
                                                                        <div class="col-md-4">
                                                                            <div style="max-width: 250px;" id="paypal-button-container">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-8"><span>Pay using PayPal and get 200/-
                                                                                cashback or 50% off whichever is lower, on your first
                                                                                transaction with PayPal.</span></div>
                                                                    </div>
                                                                    {$paypal_fee_id=$v.fee_id}
                                                                    {$paypal_pg_id=$v.pg_id}
                                                                {/if}
                                                            {/foreach}
                                                            {if !isset($paypal_fee_id)}
                                                            </div>
                                                        {/if}
                                                    </div>
                                                </div>
                                            {/if}
                                        {/if}

                                        {if $enable_tnc==1}
                                            <div class="col-md-12">
                                                <div class="form-group no-margin pull-right">
                                                    <label><input type="checkbox" class="icheck"
                                                            data-checkbox="icheckbox_minimal-blue" required
                                                            name="confirm" /></span> {$lang_title.i_accept} <a
                                                            href="/terms-popup/{$info.merchant_id}" class="iframe">
                                                            {$lang_title.terms_conditions}</a> & <a
                                                            href="/privacy-popup/{$info.merchant_id}"
                                                            class="iframe">{$lang_title.privacy_policy}</a> <span
                                                            class="required">
                                                        </span>
                                                    </label>

                                                    <div id="form_payment_error"></div>
                                                </div>
                                            </div>
                                        {/if}
                                        <div class="col-md-12">
                                            <input name="payment_req" type="hidden" class="displayonly"
                                                value="{$payment_request_id}" />
                                            <input name="customer_id" type="hidden" class="displayonly"
                                                value="{$info.customer_id}" />
                                            <input name="deduct_amount" id="deduct_amount" type="hidden"
                                                class="displayonly" value="0" />
                                            <input name="deduct_text" id="deduct_text" type="hidden" class="displayonly"
                                                value="" />
                                            <input name="coupon" type="hidden" class="displayonly"
                                                value="{$plugin.has_coupon}" />
                                            <input name="coupon_id" type="hidden" class="displayonly"
                                                value="{$coupon_id}" />
                                            <input name="autocollect_plan_id" type="hidden" class="displayonly"
                                                value="{$autocollect_plan_id}" />
                                            <input id="amt" type="hidden" class="displayonly"
                                                value="{$absolute_cost}" />
                                            <input name="discount" type="hidden" id="coupon_discount"
                                                class="displayonly" value="{$discount}" />
                                            <input name="post_url" type="hidden" value="{$post_url}" />
                                            <input name="request_post_url" type="hidden" value="{$request_post_url}" />
                                            <input name="url" type="hidden" value="{$url}" />
                                            <input name="amount" type="hidden" value="{$amount}" />
                                            <button type="submit"
                                                class="btn blue pull-right">{$lang_title.click_here}</button>
                                        </div>
                                    </div>
                                </div>
                        </div>

                    </div>

                    <!-- End profile details -->



                    </form>

                </div>
                <p><img src="/assets/admin/layout/img/logo.png" class="img-responsive pull-right powerbyimg"
                        alt="" /><span class="powerbytxt">Powered by</span> </p>
            </div>
        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>
</div>
<!-- END CONTENT -->
</div>

{* {if $paypal_id!=''}
<script>
    var request_url = '/patron/paymentrequest/payment/paypal/{$paypal_fee_id}';
    var response_url = "/secure/paypalresponse/";
    var paypal_pg_id = '{$paypal_pg_id}';
    var form_id = 'submit_form';
</script>
<script src="/assets/admin/layout/scripts/paypal.js" type="text/javascript"></script>
{/if} *}

<script> 
    function showStateDiv(country_name) {
        if(country_name!='') {
            if(country_name=='India') {
                $('#state_txt').hide();
                $('#state_drpdown').show();
                $('#s2id_state_drpdown').show();
                $("#country_code_txt").text('+91');
                $("#mobile").attr('pattern',"([0-9]{ldelim}10{rdelim})"); //^(\+[\d]{ldelim}1,5{rdelim}|0)?[1-9]\d{ldelim}9{rdelim}$
                $("#mobile").attr('maxlength', "10");
            } else {
                $('#state_drpdown').hide();
                $('#s2id_state_drpdown').hide();
                $('#state_txt').show();
                $("#mobile").attr('pattern', "([0-9]{ldelim}7,10{rdelim})");
                $("#mobile").attr('maxlength', "10");

                $.ajax({
                    type: 'POST',
                    url: '/ajax/getCountryCode',
                    data: {
                        'country_name':country_name
                    },
                    success: function (data)
                    {
                        obj = JSON.parse(data);
                        if (obj.status == 1) {
                            $("#country_code_txt").text('+' + obj.country_code);
                        } else {
                            $("#country_code_txt").text('');
                        }
                    }
                });
            }
        }
    }
</script>