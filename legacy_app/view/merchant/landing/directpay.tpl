{if $paypal_id!=''}
    <script src="https://www.paypal.com/sdk/js?client-id={$paypal_id}&locale=en_IN&currency=INR"></script>
{/if}                                
<div class="row ">            
    <div class="col-md-12" >
        <div class="loading" id="loader" style="display: none;">Processing your payment Please wait&#8230;</div>
        {if isset($haserrors)}
            <div class="alert alert-danger" style="text-align:left;">
                <button type="button" class="close" data-dismiss="alert"></button>
                <p>{$haserrors}</p>
            </div>
        {/if}
        <div class="" id="error" style="display: none;padding: 15px;background-color: #f2dede;border-color: #ebccd1;color: #a94442;"></div>
        <div class="portlet-body form" style="text-align: -webkit-center;text-align: -moz-center;">
            <form action="/m/{$url}/directpayment" method="POST" id="submit_form" class="form-horizontal form-row-sepe">
            <input type="hidden" name="recaptcha_response" id="captcha1">
            <div class="form-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <h3>{$details.company_name}</h3></label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-4" style="text-align: right;"><label class="control-label">Name<span class="required">* </span></label></div>
                                <div class="col-md-4">
                                    <input type="text" required value="{$cookie.direct_pay_name}" {$validate.name_text} name="name" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-4" style="text-align: right;"><label class="control-label">Email ID <span class="required">* </span></label></div>
                                <div class="col-md-4">
                                    <input type="email" required value="{$cookie.direct_pay_email}" name="email" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-4" style="text-align: right;"><label class="control-label">Mobile Number <span class="required">* </span></label></div>
                                <div class="col-md-4">
                                    <input type="text" required value="{$cookie.direct_pay_mobile}" {$validate.mobile} name="mobile" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-4" style="text-align: right;"><label class="control-label">{if $merchant_setting.customer_code_label!=''} {$merchant_setting.customer_code_label}{else} Customer code{/if} <span class="required">{if $merchant_setting.customer_code_mandatory==1} *{/if}</span></label></div>
                                <div class="col-md-4">
                                    <input type="text" {if $merchant_setting.customer_code_mandatory==1} required="" {else} placeholder="If available" {/if}  name="customer_code" {$validate.narrative} maxlength="45" value="{$cookie.direct_pay_customer_code}" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-4" style="text-align: right;"><label class="control-label">Purpose of payment <span class="required"> </span></label></div>
                                <div class="col-md-4">
                                    <input type="text"  name="purpose" value="{$cookie.direct_pay_purpose}" {$validate.narrative} maxlength="100" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-4" style="text-align: right;"><label class="control-label">Amount <span class="required">* </span></label></div>
                                <div class="col-md-4">
                                    <input type="number" max="{$merchant_setting.max_transaction}"  maxlength="8" id="amt" required {if $cookie.direct_pay_amount>0} readonly value="{$cookie.direct_pay_amount}" {else} value="{$post.amount}" {/if} name="amount" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-4" style="text-align: right;"><label class="control-label">Captcha <span class="required">*
                                        </span></label></div>
                                <div class="col-md-4" style="text-align: left;">
                                    <form id="comment_form" action="form.php" method="post">
                                        <div class="g-recaptcha" data-sitekey="{$capcha_key}"></div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    {if isset($radio)}
                        <div class="form-group form-md-radios">
                            <div class="col-md-12">
                                <div class="col-md-2"></div>
                                <div class="col-md-8">
                                    <h4 style="margin-left: -60%;"> Select payment mode</h4>
                                    <h4 style="margin-left: -30%;"><span style="font-size: 13px;font-weight: 500;" id="conv"></span></h4>
                                    <div id="form_gender_error" style="color: red;"></div>
                                    <h5 id="grandtotal" style="display: none;"></h5>
                                    <div class="md-radio-inline">
                                        {$int=6}
                                        {foreach from=$radio item=v}

                                            {if $v.name!='PAYPAL'}
                                                <div class="md-radio">
                                                    <input type="radio" required id="radio{$int}" name="payment_mode" onchange="getgrandtotal(document.getElementById('amt').value, '{$v.fee_id}');" value="{$v.fee_id}"  class="md-radiobtn">
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
                                                    <div class="col-md-4"><div style="max-width: 250px;" id="paypal-button-container"></div></div>
                                                    <div class="col-md-8" style="text-align: left;"><span>Pay using PayPal and get 200/- cashback or 50% off whichever is lower, on your first transaction with PayPal.</span></div>
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
                    {if $enable_tnc==1}
                        <div class="">
                            <div class="form-group no-margin">
                                <div class="col-md-3"></div>
                                <div class="col-md-6"  style="text-align: left;">

                                    <label style="text-align: left;">
                                        <input type="checkbox" required name="confirm"/>  I accept the <a href="/terms-popup/{$merchant_id}" class="iframe"> Terms and conditions</a> & <a href="/privacy-popup/{$merchant_id}" class="iframe">Privacy policy</a> <span class="required">
                                        </span>
                                    </label>
                                    <div id="form_payment_error"></div>
                                </div>
                            </div>
                        </div>
                    {/if}
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <br>
                                <input type="submit" onclick="checkmode();"  value="Pay now" class="btn blue">
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

    <hr/>
    <p style="text-align: left;">&copy; {$current_year} OPUS Net Pvt. Handmade in Pune. <a target="_BLANK" href="https://www.swipez.in"><img src="/assets/admin/layout/img/logo.png" class="img-responsive pull-right powerbyimg" alt=""/><span class="powerbytxt">powered by</span></a></p>
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
    var request_url = '/m/{$url}/directpayment/paypal/{$paypal_fee_id}';
    var response_url = "/xway/paypalresponse/";
    var paypal_pg_id = '{$paypal_pg_id}';
    var form_id = 'submit_form';
</script>
<script src="/assets/admin/layout/scripts/paypal.js?v=3" type="text/javascript"></script>