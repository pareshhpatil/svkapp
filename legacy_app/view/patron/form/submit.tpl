{if $paypal_id!=''}
    <script src="https://www.paypal.com/sdk/js?client-id={$paypal_id}&locale=en_IN&currency=INR"></script>
{/if}
<div class="page-content" {if isset($background_image) && $background_image!=''}
    style="background-image: url('{$background_image}');" {/if}>
    <div class="row " id="divtop">
        <div class="col-md-12">
            <div class="loading" id="loader" style="display: none;">Loading&#8230;</div>
            <div class="alert alert-danger" style="display: none;" id="error_msg">
            </div>
            {if isset($error_title)}
                <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert"></button>
                    <strong>{$error_title}</strong>
                    {$error_message}
                </div>
            {/if}
            {if isset($haserrors)}
                <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert"></button>
                    <strong>Error!</strong>
                    <div class="media">
                        {foreach from=$haserrors item=v}
                            <p class="media-heading">{$v.0} - {$v.1}.</p>
                        {/foreach}
                    </div>
                </div>
            {/if}
            <div class="" id="error"
                style="display: none;padding: 15px;background-color: #f2dede;border-color: #ebccd1;color: #a94442;">
            </div>
            <div class="portlet-body form" style="text-align: -webkit-center;text-align: -moz-center;">
                <form autocomplete="off" action="{if $disable_payment!=1}{$post_url}{else}/patron/form/formsubmit{/if}"
                    {if $onsubmit!=''} onsubmit="return {$onsubmit}" {/if} method="POST" id="frm_form_builder"
                    enctype="multipart/form-data" class="form-horizontal form-row-sepe">
                    <input type="hidden" name="recaptcha_response" id="captcha1">
                    <div class="form-body">
                        {foreach from=$json item=v}
                            {if $v.display==1}
                                {if $v.subtype=='h3'}
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h3 style="color: #275770;"><b>{$v.label}</b></h3>
                                            </div>
                                        </div>
                                    </div>
                                {elseif $v.subtype=='text'}
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="col-md-4 visible-xs" style="text-align: left;"><label
                                                        {if isset($v.label_id)} id="{$v.label_id}xs" {/if}
                                                        class="control-label">{$v.label}<span
                                                            class="required">{if $v.required==1}*{/if} </span>
                                                        {if $v.info_text!=''}
                                                            &nbsp; <i class="popovers fa fa-info-circle support blue"
                                                                data-container="body" data-trigger="hover" data-content="{$v.info_text}"
                                                                data-original-title="" title=""></i>
                                                        {/if}
                                                    </label>
                                                </div>
                                                <div class="col-md-4 hidden-xs" style="text-align: right;"><label
                                                        {if isset($v.label_id)} id="{$v.label_id}" {/if}
                                                        class="control-label">{$v.label}<span
                                                            class="required">{if $v.required==1}*{/if} </span>
                                                        {if $v.info_text!=''}
                                                            &nbsp; <i class="popovers fa fa-info-circle support blue"
                                                                data-container="body" data-trigger="hover" data-content="{$v.info_text}"
                                                                data-original-title="" title=""></i>
                                                        {/if}
                                                    </label>
                                                </div>
                                                <div class="col-md-4" style="text-align: left;">

                                                    {if isset($v.pattern)}
                                                        {assign "find" array('lcurly', 'rcurly', 'lbox','rbox')}
                                                        {assign "repl" array('{', '}','[',']')}
                                                    {/if}

                                                    {if $v.name=='grand_total' && $v.is_textbox!=1}
                                                        <span>
                                                            <h4 class="pull-left">{if isset($v.strike_amount)}<strike><b><i
                                                                                class="fa fa-inr"></i> {$v.strike_amount}</strike></b>
                                                                &nbsp;&nbsp;{/if}<b id="g_total"><i class="fa fa-inr"></i>
                                                                    {$v.value}</b></h4>
                                                            {if $gst_number!=''}
                                                                <a class="popovers pull-left" onmouseover="changeGSTtext();" id="gsttext"
                                                                    data-container="body" data-trigger="hover"
                                                                    data-content="Inclusive 18% GST" data-original-title="" title=""
                                                                    style="margin-top: 10px; margin-bottom: 10px; margin-left: 10px;"> Taxes
                                                                    included</a>
                                                            {/if}
                                                        </span>
                                                        <input {if isset($v.id)} id="{$v.id}" {/if} readonly type="hidden"
                                                            class="displayonly" value="{$v.value}" name="{$v.name}">
                                                        {if isset($enc_grand_total)}
                                                            <input type="hidden" readonly class="displayonly" value="{$enc_grand_total}"
                                                                name="enc_grand_total">
                                                        {/if}
                                                    {else}
                                                        <input placeholder="{$v.placeholder}" type="text" {if isset($v.maxlength)}
                                                            maxlength="{$v.maxlength}" {/if}
                                                            {if isset($v.pattern)}pattern="{$v.pattern|replace:$find:$repl}" {/if}
                                                            {if isset($v.title)}title="{$v.title}" {/if}
                                                            {if $v.readonly==1}readonly{/if}
                                                            {if isset($v.onchange)}onblur="{$v.onchange}" {/if} {if isset($v.id)}
                                                            id="{$v.id}" {/if} {if $v.required==1}required{/if} value="{$v.value}"
                                                            {if $v.validation!=''} {$validate.{$v.validation}} {/if} name="{$v.name}"
                                                            class="{$v.className}">
                                                    {/if}

                                                    {if isset($v.help_text)}<span
                                                        style="text-align: left">{$v.help_text}</span>{/if}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                {elseif $v.subtype=='date'}
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="col-md-4 visible-xs" style="text-align: left;"><label
                                                        {if isset($v.label_id)} id="{$v.label_id}xs" {/if}
                                                        class="control-label">{$v.label}<span
                                                            class="required">{if $v.required==1}*{/if} </span>
                                                        {if $v.info_text!=''}
                                                            &nbsp; <i class="popovers fa fa-info-circle support blue"
                                                                data-container="body" data-trigger="hover" data-content="{$v.info_text}"
                                                                data-original-title="" title=""></i>
                                                        {/if}
                                                    </label>
                                                </div>
                                                <div class="col-md-4 hidden-xs" style="text-align: right;"><label
                                                        {if isset($v.label_id)} id="{$v.label_id}" {/if}
                                                        class="control-label">{$v.label}<span
                                                            class="required">{if $v.required==1}*{/if} </span>
                                                        {if $v.info_text!=''}
                                                            &nbsp; <i class="popovers fa fa-info-circle support blue"
                                                                data-container="body" data-trigger="hover" data-content="{$v.info_text}"
                                                                data-original-title="" title=""></i>
                                                        {/if}
                                                    </label>
                                                </div>
                                                <div class="col-md-4">
                                                    <input class="form-control date-picker" data-date-format="dd-mm-yyyy"
                                                        placeholder="{$v.placeholder}" {if $v.readonly==1}readonly{/if} type="text"
                                                        {if isset($v.onchange)}onblur="{$v.onchange}" {/if} {if isset($v.id)}
                                                        id="{$v.id}" {/if} {if $v.required==1}required{/if} value="{$v.value}"
                                                        {if $v.validation!=''} {$validate.{$v.validation}} {/if} name="{$v.name}">
                                                    {if isset($v.help_text)}<span
                                                        style="text-align: left">{$v.help_text}</span>{/if}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                {elseif $v.subtype=='textarea'}
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="col-md-4 visible-xs" style="text-align: left;"><label
                                                        {if isset($v.label_id)} id="{$v.label_id}xs" {/if}
                                                        class="control-label">{$v.label}<span
                                                            class="required">{if $v.required==1}*{/if} </span>
                                                        {if $v.info_text!=''}
                                                            &n&nbsp;&n&nbsp; <i class="popovers fa fa-info-circle support blue"
                                                                data-container="body" data-trigger="hover" data-content="{$v.info_text}"
                                                                data-original-title="" title=""></i>
                                                        {/if}
                                                    </label>
                                                </div>
                                                <div class="col-md-4 hidden-xs" style="text-align: right;"><label
                                                        {if isset($v.label_id)} id="{$v.label_id}" {/if}
                                                        class="control-label">{$v.label}<span
                                                            class="required">{if $v.required==1}*{/if} </span>
                                                        {if $v.info_text!=''}
                                                            &n&nbsp;&n&nbsp; <i class="popovers fa fa-info-circle support blue"
                                                                data-container="body" data-trigger="hover" data-content="{$v.info_text}"
                                                                data-original-title="" title=""></i>
                                                        {/if}
                                                    </label>
                                                </div>
                                                <div class="col-md-4">
                                                    <textarea {if $v.required==1}required{/if} {$validate.$v.validation}
                                                        name="{$v.name}" placeholder="{$v.placeholder}"
                                                        class="form-control">{$v.value}</textarea>
                                                    {if isset($v.help_text)}<span
                                                        style="text-align: left">{$v.help_text}</span>{/if}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                {elseif $v.subtype=='checkbox'}
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="col-md-4 visible-xs" style="text-align: left;"><label
                                                        class="">{$v.label}<span class="required">{if $v.required==1}*{/if} </span>
                                                        {if $v.info_text!=''}
                                                            &n&nbsp;&n&nbsp; <i class="popovers fa fa-info-circle support blue"
                                                                data-container="body" data-trigger="hover" data-content="{$v.info_text}"
                                                                data-original-title="" title=""></i>
                                                        {/if}
                                                    </label>
                                                </div>
                                                <div class="col-md-4 hidden-xs" style="text-align: right;"><label
                                                        class="">{$v.label}<span class="required">{if $v.required==1}*{/if} </span>
                                                        {if $v.info_text!=''}
                                                            &n&nbsp;&n&nbsp; <i class="popovers fa fa-info-circle support blue"
                                                                data-container="body" data-trigger="hover" data-content="{$v.info_text}"
                                                                data-original-title="" title=""></i>
                                                        {/if}
                                                    </label>
                                                </div>
                                                <div class="col-md-4" style="text-align: left;">
                                                    <label> <input type="checkbox" {if $v.required==1}required{/if} name="{$v.name}"
                                                            value="{$v.value}" class="form-control">{$v.value}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                {elseif $v.subtype=='select'}
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="col-md-4 visible-xs" style="text-align: left;"><label
                                                        {if isset($v.label_id)} id="{$v.label_id}xs" {/if}
                                                        class="control-label">{$v.label}<span
                                                            class="required">{if $v.required==1}*{/if} </span>
                                                        {if $v.info_text!=''}
                                                            &n&nbsp;&n&nbsp; <i class="popovers fa fa-info-circle support blue"
                                                                data-container="body" data-trigger="hover" data-content="{$v.info_text}"
                                                                data-original-title="" title=""></i>
                                                        {/if}
                                                    </label>
                                                </div>
                                                <div class="col-md-4 hidden-xs" style="text-align: right;"><label
                                                        {if isset($v.label_id)} id="{$v.label_id}" {/if}
                                                        class="control-label">{$v.label}<span
                                                            class="required">{if $v.required==1}*{/if} </span>
                                                        {if $v.info_text!=''}
                                                            &n&nbsp;&n&nbsp; <i class="popovers fa fa-info-circle support blue"
                                                                data-container="body" data-trigger="hover" data-content="{$v.info_text}"
                                                                data-original-title="" title=""></i>
                                                        {/if}
                                                    </label>
                                                </div>
                                                <div class="col-md-4" style="text-align: left;">
                                                    <select class="form-control {if isset($v.basic_dropdown)} {else} select2me{/if}"
                                                        {if isset($v.id)} id="{$v.id}" {/if}
                                                        {if isset($v.onchange)}onchange="{$v.onchange}" {/if}
                                                        {if $v.required==1}required{/if} name="{$v.name}"
                                                        data-placeholder="{$v.placeholder}">
                                                        <option value="">
                                                            {if $v.placeholder!=''}{$v.placeholder}{else}{$v.label}{/if}</option>
                                                        {if $v.name=='state' && empty($v.value)}
                                                            {foreach from=$state_code key=k item=m}
                                                                {$addtext=''}
                                                                {if $m.config_key<10}
                                                                    {$addtext='0'}
                                                                {/if}
                                                                {if $v.value==$m.config_value}
                                                                    <option selected value="{$m.config_value}">{$addtext}{$m.config_key}
                                                                        {$m.config_value}</option>
                                                                {else}
                                                                    <option value="{$m.config_value}">{$addtext}{$m.config_key}
                                                                        {$m.config_value}</option>
                                                                {/if}
                                                            {/foreach}
                                                        {else}
                                                            {foreach from=$v.value key=k item=m}
                                                                {if $m.value==$v.selected}
                                                                    <option selected value="{$m.value}">{$m.label}</option>
                                                                {else}
                                                                    <option value="{$m.value}">{$m.label}</option>
                                                                {/if}
                                                            {/foreach}
                                                        {/if}

                                                    </select>
                                                    {if isset($v.help_text)}<span
                                                        style="text-align: left">{$v.help_text}</span>{/if}

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                {elseif $v.subtype=='file'}
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="col-md-4 visible-xs" style="text-align: left;"><label
                                                        class="control-label">{$v.label}<span
                                                            class="required">{if $v.required==1}*{/if} </span>
                                                        {if $v.info_text!=''}
                                                            &n&nbsp;&n&nbsp; <i class="popovers fa fa-info-circle support blue"
                                                                data-container="body" data-trigger="hover" data-content="{$v.info_text}"
                                                                data-original-title="" title=""></i>
                                                        {/if}
                                                    </label>
                                                </div>
                                                <div class="col-md-4 hidden-xs" style="text-align: right;"><label
                                                        class="control-label">{$v.label}<span
                                                            class="required">{if $v.required==1}*{/if} </span>
                                                        {if $v.info_text!=''}
                                                            &n&nbsp;&n&nbsp; <i class="popovers fa fa-info-circle support blue"
                                                                data-container="body" data-trigger="hover" data-content="{$v.info_text}"
                                                                data-original-title="" title=""></i>
                                                        {/if}
                                                    </label>
                                                </div>
                                                <div class="col-md-4" style="text-align: left;">
                                                    <input type="file" {if $v.required==1}required{/if} accept="{$v.validation}"
                                                        onchange="return validatefilesize({$v.max_bytes}, '{$v.id}');" id="{$v.id}"
                                                        name="{$v.name}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                {elseif $v.subtype=='paragraph'}
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-3"></div>
                                            <div class="col-md-6">
                                                <p>{$v.value}</p>
                                            </div>
                                        </div>
                                    </div>
                                {elseif $v.subtype=='image'}
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12" style="text-align: {$v.align}">
                                                <img src="{$v.src}" class="{$v.className}" style="{$v.style}">
                                            </div>
                                        </div>
                                    </div>
                                {/if}

                            {else}
                                {if $v.type=='json'}
                                    <span style="display: none;" id="{$v.id}">{$v.value|@json_encode}</span>
                                {elseif $v.type=='footer'}
                                {else}
                                    {if $v.name=='base_amount'}
                                        {$base_amount=$v.value}
                                    {/if}
                                    <input type="hidden" readonly {if isset($v.id)} id="{$v.id}" {/if} value="{$v.value}"
                                        name="{$v.name}">
                                {/if}
                            {/if}

                        {/foreach}

                        {if $coupon_enable==1}
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-4 visible-xs" style="text-align: left;"><label
                                                class="control-label">Coupon code

                                            </label>
                                        </div>
                                        <div class="col-md-4 hidden-xs" style="text-align: right;"><label
                                                class="control-label">Coupon code

                                            </label>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="text" id="coupon_code" placeholder="Enter coupon code"
                                                class="form-control" value="" />
                                            <input type="hidden" readonly class="displayonly" id="coupon_id" />
                                            <br>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="font-red">&nbsp;</label>
                                            <button onclick="return validateFormCoupon('{$merchant_user_id}');"
                                                class="btn green pull-left">Apply</button>
                                        </div>

                                    </div>
                                    <div class="col-md-4">
                                    </div>
                                    <div class="col-md-8">
                                        <p><label class="font-blue pull-left" id="coupon_status">&nbsp;</label></p>
                                    </div>

                                </div>
                            </div>
                        {/if}

                        {if $disable_payment!=1}
                            {if $is_new_pg == false}
                                {if isset($radio)}
                                    <div class="form-group" id="radio-div">
                                        <div class="col-md-12">
                                            <div class="col-md-4"></div>
                                            <div class="col-md-8" style="text-align: left;">
                                                <h4> Select payment mode</h4>
                                                <h4><span style="font-size: 13px;font-weight: 500;" id="conv"></span></h4>
                                                <div id="form_gender_error" style="color: red;"></div>
                                                <h5 id="grandtotal" style="display: none;"></h5>
                                                <div class="md-radio-inline">
                                                    {$int=6}
                                                    {foreach from=$radio item=v}

                                                        {if $v.name!='PAYPAL'}
                                                            <div class="md-radio">
                                                                <input type="radio" required id="radio{$int}" name="payment_mode"
                                                                    onchange="getgrandtotal(document.getElementById('amt').value, '{$v.fee_id}');"
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
                                                                {$int=$int+1}
                                                            </div>
                                                        {else}
                                                            <hr>
                                                            <h4 style="margin-left: -65%;">Payment Offers</h4>
                                                            <div class="row" style="margin-left: 65px;">
                                                                <div class="col-md-4">
                                                                    <div style="max-width: 250px;" id="paypal-button-container"></div>
                                                                </div>
                                                                <div class="col-md-8" style="text-align: left;"><span>Pay using PayPal and
                                                                        get 200/- cashback or 50% off whichever is lower, on your first
                                                                        transaction with PayPal.</span></div>
                                                            </div>
                                                            {$paypal_fee_id=$v.fee_id}
                                                            {$paypal_pg_id=$v.pg_id}
                                                        {/if}


                                                    {/foreach}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                {/if}
                            {/if}
                            {if $enable_tnc==1}
                                {if $disable_tnc!=1}
                                    <div class="" id="tnc-div">
                                        <div class="form-group no-margin">
                                            <div class="col-md-4"></div>
                                            <div class="col-md-6" style="text-align: left;">
                                                <label style="text-align: left;">
                                                    <input id="tnc_check" type="checkbox" required name="confirm" /> I accept the <a
                                                        href="/terms-popup/{$merchant_id}" class="iframe"> Terms and conditions</a>
                                                    & <a href="/privacy-popup/{$merchant_id}" class="iframe">Privacy policy</a>
                                                    <span class="required">
                                                    </span>
                                                </label>
                                                <div id="form_payment_error"></div>
                                            </div>
                                        </div>
                                    </div>
                                {/if}
                            {/if}
                        {/if}
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <br>
                                    <input type="hidden" readonly id="instate_total_tax" value="{$instate_total_tax}">
                                    <input type="hidden" readonly id="outstate_total_tax" value="{$outstate_total_tax}">
                                    <input type="hidden" readonly id="base_amount" value="{$base_amount}">
                                    <input type="hidden" readonly name="amount" value="{$amount}">
                                    <input type="hidden" readonly name="request_id" value="{$link}">
                                    <input type="hidden" readonly id="merchant_gst_number" value="{$gst_number}">
                                    <input type="hidden" readonly id="instate_label" value="{$instate_label}">
                                    <input type="hidden" readonly id="outstate_label" value="{$outstate_label}">
                                    <input type="hidden" name="request_post_url" value="{$request_post_url}">
                                    <input type="hidden" name="post_url" value="{$post_url}">
                                    <input name="url" type="hidden" value="{$url}" />
                                    {foreach from=$udf_params key=k item=v}
                                        <input type="hidden" readonly name="{$k}" value="{$v}">
                                    {/foreach}
                                    <input type="submit" id="btn-submit" {if isset($btn_background_color)}
                                        style="background-color: {$btn_background_color}" {/if} onclick="checkmode();"
                                        value="{if $disable_payment!=1}Pay now{else}Submit{/if}" class="btn blue">
                                </div>
                            </div>
                        </div>


                    </div>
                </form>


            </div>


            <br>
            <br>
            <br>
            <hr>
        </div>

        <hr />
        <p style="text-align: left;padding-left: 20px;"> <a target="_BLANK" href="https://www.swipez.in"><img
                    src="/assets/admin/layout/img/logo.png" class="img-responsive pull-right powerbyimg" alt="" /><span
                    class="powerbytxt">powered by</span></a></p>
    </div>


</div>
</div>
</form>
</div>

</div>
<!-- END PAGE CONTENT-->
</div>
</div>
<script>
    var request_url = '/patron/form/payment/paypal/{$paypal_fee_id}';
    var response_url = "/xway/paypalresponse/";
    var paypal_pg_id = '{$paypal_pg_id}';
    var form_id = 'frm_form_builder';
</script>
<script src="/assets/admin/layout/scripts/paypal.js?v=2" type="text/javascript"></script>

{if isset($footer)}
    {$footer}
{/if}