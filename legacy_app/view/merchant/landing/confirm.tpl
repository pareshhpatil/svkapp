<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    {if $hide_footer!=true}
        <h3 class="page-title">&nbsp;</h3>
    {/if}
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
                                <p class="media-heading">{$v.0} - {$v.1}</p>
                            {/foreach}
                        </div>

                    </div>
                {/if}

                <div class="portlet light bordered" style="max-width: 900px;text-align: left;">
                    <div class="portlet-title">
                        <div class="col-md-6">
                            <div class="caption font-blue" style="">
                                <span class="caption-subject bold uppercase">
                                    <h2>{$details.company_name}</h2>
                                </span>
                                <p>Package name: {$info.plan_name}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="caption font-blue">
                                <h2 id="grandtotal" style="text-align: right !important;"><i
                                        class="fa fa-inr fa-large"></i>
                                    {$grand_total+$surcharge_amount|string_format:"%.2f"}/-</h2>
                                {if $pg_surcharge_enabled==1}
                                    <span style="text-align: right !important;">Convenience fee applicable for online
                                        payments based on payment mode</span>
                                {else}
                                    {if !isset($radio) && $surcharge_amount>0}
                                        <span style="text-align: right !important;">Convenience fee of {$surcharge_amount}/-
                                            applicable for online payments</span>
                                    {/if}
                                {/if}

                            </div>
                        </div>

                    </div>
                    <div class="portlet-body form">
                        <!--<h3 class="form-section">Profile details</h3>-->

                        <div class="form-body">
                            <!-- Start profile details -->
                            <div class="row">
                                {if $merchant_setting.customer_code_validation==1 && empty($customerdetails)}
                                    <div class="col-md-12" id="customer_form">
                                        {if {$isGuest} =='1'}
                                            <div class="row">
                                                <h4> &nbsp;&nbsp;&nbsp; Enter valid
                                                    {if $merchant_setting.customer_code_label!=''}
                                                    {$merchant_setting.customer_code_label}{else} Customer code
                                                    {/if}
                                                    {if $merchant_setting.password_validation==1} & Password{/if}</h4>
                                                <div class="col-md-8">
                                                    <form id="frm_customer_code" method="post"
                                                        class="form-horizontal form-row-sepe">
                                                        <div class="form-group">
                                                            <label
                                                                class="control-label col-md-3">{if $merchant_setting.customer_code_label!=''}
                                                                {$merchant_setting.customer_code_label}{else} Customer code
                                                                {/if}
                                                                <span
                                                                    class="required">{if $merchant_setting.customer_code_mandatory==1}
                                                                    *{/if}
                                                                </span></label>
                                                            <div class="col-md-6">
                                                                <input type="text"
                                                                    {if $merchant_setting.customer_code_mandatory==1}
                                                                    required="" {/if} name="customer_code"
                                                                    value="{$customerdetails.customer_code}"
                                                                    class="form-control">
                                                                <input type="hidden" value="{$merchant_setting.merchant_id}"
                                                                    name="merchant_id">
                                                                <input type="hidden"
                                                                    value="{$merchant_setting.password_validation}"
                                                                    name="password_validation" id="password_validation">
                                                                <span class="help-block"> </span>
                                                            </div>

                                                        </div>
                                                        {if $merchant_setting.password_validation==1}
                                                            <div class="form-group">
                                                                <label class="control-label col-md-3">Password <span
                                                                        class="required">{if $merchant_setting.customer_code_mandatory==1}
                                                                        *{/if}
                                                                    </span></label>

                                                                <div class="col-md-6">
                                                                    <input type="password" placeholder="Password" name="password"
                                                                        value="" class="form-control">
                                                                    <span class="help-block"> </span>
                                                                </div>
                                                            </div>
                                                        {/if}
                                                        <div class="form-group">
                                                            <label class="control-label col-md-3"></label>
                                                            <div class="col-md-6">
                                                                <div class="g-recaptcha" required data-sitekey="{$capcha_key}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label col-md-3"></label>
                                                            <div class="col-md-6">
                                                                <a href="/m/{$url}/{$package_type}" class="btn default"><i
                                                                        class="fa fa-arrow-left"></i> &nbsp; Change package</a>
                                                                <button type="submit"
                                                                    class="btn blue">{if $merchant_setting.password_validation==1}Login{else}Validate{/if}</button>
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="control-label col-md-2"></label>
                                                            <div class="col-md-7">
                                                                <label class="font-red" id="cust_code_status"></label>
                                                            </div>
                                                        </div>

                                                    </form>
                                                </div>
                                        </div>{/if}
                                        <br><br><br><br><br><br>
                                    </div>
                                {/if}

                                <div class="col-md-12" id="payment_form"
                                    {if $merchant_setting.customer_code_validation==1 && empty($customerdetails)}style="display: none;"
                                    {/if}>
                                    <form action="{$post_url}" method="post" id="submit_form"
                                        class="form-horizontal form-row-sepe">
                                        <input type="hidden" name="recaptcha_response" id="captcha1">

                                        {if {$isGuest} =='1'}
                                            <div class="row">
                                                <h4>Enter billing details</h4>
                                                <div class="col-md-6">

                                                    <div class="form-group">
                                                        <label
                                                            class="control-label col-md-4">{if $merchant_setting.customer_code_label!=''}
                                                            {$merchant_setting.customer_code_label}{else} Customer code
                                                            {/if}
                                                            <span
                                                                class="required">{if $merchant_setting.customer_code_mandatory==1}
                                                                *{/if}
                                                            </span></label>
                                                        <div class="col-md-8">
                                                            <input type="text" id="customer_code"
                                                                {if $merchant_setting.customer_code_mandatory==1}
                                                                required="" {/if} name="customer_code"
                                                                {if isset($customerdetails.customer_code)} readonly
                                                                {/if}value="{$customerdetails.customer_code}"
                                                                class="form-control">
                                                            <span class="help-block"> </span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label col-md-4">Name <span class="required">
                                                                * </span></label>
                                                        <div class="col-md-8">
                                                            <input type="text" id="name" required name="name"
                                                                {$validate.name_text} value="{$customerdetails.name}"
                                                                class="form-control">
                                                            <span class="help-block"> </span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label col-md-4">Email <span class="required">
                                                                * </span></label>
                                                        <div class="col-md-8">
                                                            <input type="email" id="email" name="email"
                                                                value="{$customerdetails.email}" class="form-control">
                                                            <span class="help-block"> </span>
                                                        </div>

                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label col-md-4">Mobile <span class="required">
                                                                * </span> </label>

                                                        {* <div class="col-md-8">
                                                            <input type="text" required id="mobile" name="mobile"
                                                                {$validate.mobile} value="{$customerdetails.mobile}"
                                                                class="form-control">
                                                            <span class="help-block"> </span>
                                                        </div> *}
                                                        <div class="col-md-8">
                                                            <div class="input-group">
                                                                <span class="input-group-addon" id="country_code_txt">+91</span>
                                                                <input type="text" required id="mobile" pattern='{(empty($customerdetails) || $customerdetails.country=='India') ? '([0-9]{10})' : '([0-9]{7,10})'}' title="Enter your valid mobile number" maxlength="10" aria-describedby="mobile-error" name="mobile" value="{$customerdetails.mobile}" class="form-control">
                                                            </div>
                                                            <span id="mobile-error" class="help-block help-block-error"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">

                                                    <div class="form-group">
                                                        <label class="control-label col-md-4">Address <span
                                                                class="required">
                                                            </span> </label>
                                                        <div class="col-md-8">
                                                            <textarea type="text" id="address" {$validate.payment_address}
                                                                name="address" {$validate.address}
                                                                class="form-control">{$customerdetails.address}</textarea>
                                                            <span class="help-block"> </span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label col-md-4">Country</label>
                                                        <div class="col-md-8">
                                                            <select name="country" class="form-control select2me"
                                                                data-placeholder="Select..." onchange="showStateDiv(this.value);">
                                                                <option value="">Select Country</option>
                                                                {foreach from=$country_code item=v}
                                                                    {if isset($customerdetails.country)}
                                                                        <option {if $customerdetails.country==$v.config_value} selected {/if} value="{$v.config_value}">{$v.config_value}</option>
                                                                    {else}
                                                                        <option {if $v.config_value=="India"} selected {/if} value="{$v.config_value}">{$v.config_value}</option>
                                                                    {/if}                                                                    
                                                                {/foreach}
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label col-md-4">State <span class="required">
                                                            </span></label>
                                                        {* <div class="col-md-8">
                                                            <input type="text" id="state" name="state" {$validate.name}
                                                                value="{$customerdetails.state}" class="form-control">
                                                            <span class="help-block"> </span>
                                                        </div> *}
                                                        <div class="col-md-8" id="state">
                                                            <select name="state" class="form-control select2me"
                                                                data-placeholder="Select...">
                                                                <option value="">Select State</option>
                                                                {foreach from=$state_code item=v}
                                                                    <option {if $customerdetails.state==$v.config_value} selected {/if}
                                                                        value="{$v.config_value}">{$v.config_value}</option>
                                                                {/foreach}
                                                            </select>
                                                        </div>
                                                        <div class="col-md-8" id="state_txt" hidden>
                                                            <input type="text" name="state1" value="{$customerdetails.state}"
                                                                class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label col-md-4">City <span class="required">
                                                            </span> </label>
                                                        <div class="col-md-8">
                                                            <input type="text" id="city" name="city" {$validate.name}
                                                                value="{$customerdetails.city}" class="form-control">
                                                            <span class="help-block"> </span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label col-md-4">Zipcode <span
                                                                class="required">
                                                            </span> </label>
                                                        <div class="col-md-8">
                                                            <input type="digit" id="zipcode" name="zipcode"
                                                                {$validate.zipcode} value="{$customerdetails.zipcode}"
                                                                class="form-control">
                                                            <span class="help-block"> </span>
                                                        </div>
                                                    </div>
                                                </div>
                                        </div>{/if}
                                        {if $is_new_pg == false}
                                            {if isset($radio)}
                                                <div class="form-group form-md-radios">
                                                    <h4>* Select payment mode {if $surcharge_amount>0} <span id="conv"
                                                                style="font-size: 13px;font-weight: 500;">(Convenience fee of <i
                                                                    class="fa  fa-inr"></i>
                                                                {$surcharge_amount|string_format:"%.2f"}/- applicable for online
                                                            payments)</span>{/if}</h4>
                                                    <div id="form_gender_error" style="color: red;"></div>
                                                    <div class="md-radio-inline">
                                                        {$int=6}
                                                        {foreach from=$radio item=v}
                                                            <div class="md-radio">
                                                                <input type="radio" required id="radio{$int}" name="payment_mode"
                                                                    onchange="getgrandtotal('{$encrypt_grandtotal}', '{$v.fee_id}');"
                                                                    value="{$v.fee_id}" class="md-radiobtn">
                                                                <label for="radio{$int}">
                                                                    <span></span>
                                                                    <span class="check"></span>
                                                                    <span class="box"></span>
                                                                    {if $v.name=='PAYTM'}
                                                                        <img src="/assets/admin/layout/img/paytm.png">
                                                                    {else}
                                                                        {$v.name}
                                                                    {/if}
                                                                </label>
                                                            </div>
                                                            {$int=$int+1}
                                                        {/foreach}
                                                    </div>
                                                </div>
                                            {/if}
                                        {/if}

                                        {if $is_coupon}
                                            <div class="row">
                                                <br>

                                                <div class="col-md-3">

                                                    <input type="text" id="coupon_code" placeholder="Enter coupon code"
                                                        class="form-control" value="" />
                                                    <br>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="font-red">&nbsp;</label>
                                                    <button
                                                        onclick="return validateCoupon('{$merchant_id}', '{$grand_total}');"
                                                        class="btn green">Apply</button>
                                                </div>
                                                <div class="col-md-5">
                                                    <label class="font-blue" id="coupon_status">&nbsp;</label>

                                                </div>

                                            </div>
                                        {/if}
                                        <div class="form-actions">
                                            <div class="col-md-7">
                                                {if $enable_tnc==1}
                                                    <div class="form-group no-margin">
                                                        <label><input type="checkbox" required name="confirm" /></span> I
                                                            accept the <a href="/terms-popup" class="iframe"> Terms and
                                                                conditions</a> & <a href="/privacy-popup"
                                                                class="iframe">Privacy policy</a> <span class="required">
                                                            </span>
                                                        </label>
                                                        <div id="form_payment_error"></div>
                                                    </div>
                                                {/if}
                                            </div>
                                            <div class="col-md-5">
                                                <input type="hidden" name="request_post_url"
                                                    value="{$request_post_url}">
                                                <input type="hidden" name="post_url" value="{$post_url}">
                                                <input name="url" type="hidden" value="{$url}" />
                                                <input name="plan_id" type="hidden" class="displayonly"
                                                    value="{$plan_id}" />
                                                <input name="coupon_id" type="hidden" id="coupon_id" class="displayonly"
                                                    value="" />
                                                <input name="amount" id="amt" type="hidden" value="{$grand_total}" />
                                                <input name="purpose" type="hidden" value="{$info.plan_name}" />
                                                <a href="/m/{$url}/{$package_type}" class="btn default"><i
                                                        class="fa fa-arrow-left"></i> &nbsp; Change package</a>
                                                <input type="submit" onclick="checkmode();" value="Pay now"
                                                    class="btn blue">

                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End profile details -->
                </div>
                {if $hide_footer!=true}
                    <hr />
                    <p style="text-align: left;">&copy; {$current_year} OPUS Net Pvt. Handmade in Pune. <a target="_BLANK"
                            href="https://www.swipez.in"><img src="/assets/admin/layout/img/logo.png"
                                class="img-responsive pull-right powerbyimg" alt="" /><span class="powerbytxt">powered
                                by</span></a></p>
                {/if}
            </div>
        </div>
        <!-- END PAGE CONTENT-->
    </div>
</div>
<!-- END CONTENT -->
</div>

<script>
    function checkmode() {
        try {
            if ($('input[type=radio]:checked').size() > 0) {

            } else {
                document.getElementById('form_gender_error').innerHTML = 'Please select payment mode';
            }
        } catch (o) {}
    }
    function showStateDiv(country_name) {
        if(country_name!='') {
            if(country_name=='India') {
                $('#state_txt').hide();
                $('#state').show();
                $('#s2id_state_drpdown').show();
                $("#country_code_txt").text('+91');
                $("#mobile").attr('pattern',"([0-9]{ldelim}10{rdelim})"); //^(\+[\d]{ldelim}1,5{rdelim}|0)?[1-9]\d{ldelim}9{rdelim}$
                $("#mobile").attr('maxlength', "10");
            } else {
                $('#state').hide();
                $('#s2id_state_drpdown').hide();
                $('#state_txt').show();
                $("#mobile").attr('pattern', "([0-9]{ldelim}7,10{rdelim})");
                $("#mobile").attr('maxlength', "10");
                $.ajax({
                    type: 'POST',
                    url: '/ajax/getCountryCode',
                    data: {
                        "country_name": country_name,
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