<form action="{$post_url}" method="post" id="submit_form" class="form-horizontal form-row-sepe">
    <input type="hidden" name="request_post_url" value="{$request_post_url}">
    <input type="hidden" name="post_url" value="{$post_url}">
    <input name="url" type="hidden" value="{$url}" />
    <div class="form-body">
        <!-- Start profile details -->
        <div class="row">
            {* <div class="col-md-12">
                                    {if isset($radio)}
                                        <div class="form-md-radios">
                                            <h4> Select payment mode {if $surcharge_amount>0} <span id="conv"
                                                        style="font-size: 13px;font-weight: 500;">(Convenience fee of <i
                                                            class="fa  fa-inr"></i> {$surcharge_amount|string_format:"%.2f"}/-
                                                    applicable for online payments)</span>{/if}</h4>
                                            <div id="form_gender_error"></div>
                                            <div class="md-radio-inline">
                                                <span id="payment_mode-error" class="help-block help-block-error"></span>
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
                                                                <div style="max-width: 250px;" id="paypal-button-container"></div>
                                                            </div>
                                                            <div class="col-md-8"><span>Pay using PayPal and get 200/- cashback or
                                                                    50% off whichever is lower, on your first transaction with
                                                                    PayPal.</span></div>
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
                                {/if} *}

            <div class="row">
                <div class="col-md-12">
                    {foreach from=$post key=k item=v}
                        <input type="hidden" name="{$k}" value='{$v}'>
                    {/foreach}
                    <input type="hidden" id="amt" name="amount" value="{$post.amount}">
                    <input type="hidden" name="form_type" value="xway">
                   
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
    <!-- End profile details -->



</form>


<script>
    window.onload = function() {
        document.getElementById('submit_form').submit();
    }
</script>
<script>
    var request_url = '/xway/secure/paypal/{$paypal_fee_id}';
    var response_url = "/xway/paypalresponse/";
    var paypal_pg_id = '{$paypal_pg_id}';
    var form_id = 'submit_form';
</script>
<script src="/assets/admin/layout/scripts/paypal.js" type="text/javascript"></script>