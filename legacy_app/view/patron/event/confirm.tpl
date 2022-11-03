{if $paypal_id!=''}
    <script src="https://www.paypal.com/sdk/js?client-id={$paypal_id}&locale=en_IN&currency=INR"></script>
{/if}
<link rel="stylesheet" type="text/css" href="/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" />
<script>
    var t = '{$pg.swipez_fee_type}';
    var e = '{$pg.swipez_fee_val}';
    var a = '{$pg.pg_fee_type}';
    var n = '{$pg.pg_fee_val}';
    var u = '{$pg.pg_tax_val}';

</script>
{$free=0}
{$flexi=0}
<div class="page-content">
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
                <form action="{$post_link}" method="post">
                    <div class="portlet light bordered" style="max-width: 900px;text-align: left;">
                        <div class="portlet-title">
                            <div class="col-md-8">
                                <div class="caption font-blue">
                                    <span class="caption-subject bold uppercase"> <h2>{$info.event_name}</h2></span>
                                    <p>Presented by : {$info.company_name}</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="caption font-blue pull-right">
                                    <h3 >{if $absolute_cost>0}{$currency_icon}<span id="grandtotal">{$absolute_cost_display|number_format:2:".":","} /-</span>{/if}</h3>
                                    {if $tax_amount>0}
                                        <p>Inclusive tax : &nbsp;{$currency_icon} {$tax_amount|number_format:2:".":","}</p>
                                    {/if}
                                    {if $pg_surcharge_enabled==1}
                                        <span style="text-align: right !important;font-size: 11px;">Convenience fee of applicable for online payments based on payment mode</span>
                                    {else}
                                        {if $pg_surcharge_enabled==1}     
                                            <span style="text-align: right !important;font-size: 12px;">Convenience fee of applicable for online payments based on payment mode</span>
                                        {/if}
                                    {/if}
                                    {if $absolute_cost_display!=$absolute_cost && $absolute_cost>0}
                                        <p>Includes convenience fee : &nbsp;{$currency_icon} {$surcharge_amount}</p>
                                    {/if}

                                </div>
                            </div>
                            <br>
                        </div>
                        <div class="row">
                            <span style="font-size: initial;font-weight: 100;"> Booking Information <a class="btn blue btn-sm pull-right" href="/patron/event/view/{$payment_request_id}">  Modify booking</a></span>
                            <br>
                            <table class="table table-condensed table-bordered " style="margin-top: 10px;margin-bottom: 0px;">
                                <tr style="font-weight: bold;">
                                    <th style="width: 10%;">#</th>
                                    <th style="width: 50%;">Details</th>
                                    <th style="width: 10%;">Units</th>
                                    <th style="width: 15%;">Price</th>
                                    <th style="width: 15%;">Total</th>
                                </tr>
                            </table>
                            <table class="table table-condensed table-hover" style="margin-bottom: 0px;width: 40%;">
                                {assign var=id value=0}
                                {foreach from=$package item=v}
                                    {if $v.seat>0}
                                        {$id=$id+1}
                                    </table>
                                    <table class="table table-condensed table-bordered " style="margin-bottom: 0px;">
                                        <tr>
                                            <td style="width: 10%;">{$id}</td>
                                            <td style="width: 50%;">{$v.package_name} 
                                            {if $info.occurence>1}
                                                <span class="font-blue">
                                                    {if $v.package_type==2}- Season Ticket 
                                                    {else} - 
                                                        {$oc=$v.date}
                                                        {if $oc.start_date==$oc.end_date}
                                                            {if $oc.start_time==$oc.end_time}
                                                                {{$oc.start_date|date_format:"%d %b %Y"}}
                                                            {else}
                                                                {$from_date={$oc.start_date|cat:" "|cat:$oc.start_time}|date_format:"%d %b %Y %I:%M %p"}
                                                                {$from_date}  
                                                            {/if}
                                                        {else}
                                                            {if $oc.start_time==$oc.end_time}
                                                                {$from_date={$oc.start_date|date_format:"%d %b %Y"}}
                                                                {$from_date}
                                                            {else}
                                                                {$from_date={$oc.start_date|cat:" "|cat:$oc.start_time}|date_format:"%d %b %Y %I:%M %p"}
                                                                {$from_date}  
                                                            {/if}
                                                        {/if}
                                                    {/if}
                                                </span>
                                            {/if}
                                            </td>
                                            <td style="width: 10%;">{$v.seat}</td>
                                            <td style="width: 15%;">{$v.price|number_format:2:".":","}</td>
                                            <td style="width: 15%;">{{$v.price*$v.seat}|number_format:2:".":","}</td>
                                        </tr>
                                    </table>
                                    <table class="table table-condensed table-hover" style="margin-bottom: 0px;width: 40%;">
                                    </table>
                                    {if $v.is_flexible==1}       
                                        <div class="row">
                                            <br>
                                            <div class="col-md-8">
                                                <label class="font-red">* Minimum : {$currency_icon} {$v.min_price}  & Maximum : {$currency_icon} {$v.max_price} </label>
                                            </div>
                                            <div class="col-md-4">
                                                <input name="flexible_amount[]" type="number" step="0.01" min="{$v.min_price}" max="{$v.max_price}" placeholder="Enter amount" required class="form-control" value="" />
                                                <br>
                                            </div>

                                        </div>
                                        {$flexi=1}
                                    {/if}
                                    {if empty($attendees_capture)}
                                        {for $foo=1 to {$v.seat}}
                                            <input name="package_id[]"   type="hidden" value="{$v.package_id}" />
                                            <input name="price[]"   type="hidden" value="{$v.price}" />
                                            <input name="occurence_id[]"   type="hidden" value="{$v.occurence_id}" />
                                        {/for}
                                    {/if}
                                    {/if}
                                        {/foreach}
                                        </div>
                                        {if {$isGuest} =='1'}
                                            <div class="form-body" id="payee_div">
                                                <div class="row">
                                                    <br>
                                                    <span style="font-size: initial;font-weight: 100;"> Enter Billing details</span>
                                                    <br>
                                                    <br>
                                                    <div class="col-md-6">
                                                        {foreach from=$payee_capture item=v}
                                                            {if $v.position=='L'}
                                                                <div class="row">
                                                                    <div class="form-group">
                                                                        <label class="control-label col-md-4">{$v.column_name} 
                                                                            <span class="required">{if $v.mandatory==1}*{/if}</span></label>
                                                                        <div class="col-md-8">
                                                                            {if $v.datatype=='textarea'}
                                                                                <textarea  id="payeeid_{$v.name}" name="{$v.name}" class="form-control" ></textarea>
                                                                            {else}
                                                                                <input id="payeeid_{$v.name}" type="text" {if $v.mandatory==1}required{/if} 
                                                                                       name="{$v.name}" {$validate.{$v.datatype}} {if $v.datatype=='date'}class="form-control date-picker" data-date-format="dd M yyyy" {else}class="form-control"{/if} >
                                                                            {/if}
                                                                            <span id="error_{$v.name}" class="custom-error"></span>
                                                                            <span class="help-block"> </span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            {/if}
                                                        {/foreach}
                                                    </div>

                                                    <div class="col-md-6">
                                                        {foreach from=$payee_capture item=v}
                                                            {if $v.position=='R'}
                                                                <div class="row">
                                                                    <div class="form-group">
                                                                        <label class="control-label col-md-4">{$v.column_name} <span class="required">
                                                                                {if $v.mandatory==1}*{/if} </span></label>
                                                                        <div class="col-md-8">
                                                                            {if $v.datatype=='textarea'}
                                                                                <textarea id="payeeid_{$v.name}" name="{$v.name}" class="form-control" ></textarea>
                                                                            {else}
                                                                                <input id="payeeid_{$v.name}" type="text" {if $v.mandatory==1}required{/if} 
                                                                                       name="{$v.name}" {$validate.{$v.datatype}} {if $v.datatype=='date'}class="form-control date-picker" data-date-format="dd M yyyy" {else}class="form-control"{/if} >
                                                                            {/if}
                                                                            <span id="error_{$v.name}" class="custom-error"></span>
                                                                            <span class="help-block"> </span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            {/if}
                                                        {/foreach}
                                                        <br>

                                                    </div>

                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        {if !empty($attendees_capture)}
                                                            <button type="button" onclick="validatePayee();" class="btn blue pull-right">Continue</button>
                                                        {/if} 
                                                    </div>
                                                </div>
                                            </div>

                                        {/if}
                                        {if !empty($attendees_capture)}
                                            <div class="row hidden" id="attendee_div">
                                                <br>
                                                <span style="font-size: initial;font-weight: 100;"> Enter Attendee Information</span>
                                                <span style="font-weight: 100;"></span>

                                                {$package_name=$package.0.package_id}
                                                {assign var=id value=1}
                                                {foreach from=$package key=k item=v}
                                                    {if $v.seat>0}

                                                        {for $foo=1 to {$v.seat}}
                                                            <div class="form-body">
                                                                <div class="row no-margin">
                                                                    <span style="font-size: initial;font-weight: 600;"> Attendee {$foo} [{$v.package_name}]</span>
                                                                    {if $k==0 && $foo==1}<label><input onchange="sameaspayee(this.checked);" name="same_aspayee" type="checkbox">Same as payee</label>{/if}
                                                                    <input name="package_id[]"   type="hidden" value="{$v.package_id}" />
                                                                    <input name="price[]"   type="hidden" value="{$v.price}" />
                                                                    <input name="occurence_id[]"   type="hidden" value="{$v.occurence_id}" />
                                                                    <br>
                                                                    <div class="col-md-6">
                                                                        {foreach from=$attendees_capture item=v}
                                                                            {if $v.position=='L'}
                                                                                <div class="row">
                                                                                    <div class="form-group">
                                                                                        <label class="control-label col-md-4">{$v.column_name} <span class="required">
                                                                                                {if $v.mandatory==1}*{/if} </span></label>
                                                                                        <div class="col-md-8">
                                                                                            {if $v.datatype=='textarea'}
                                                                                                <textarea {if $k==0 && $foo==1} id="attendeeid_{$v.name}" {/if} name="attendee_{$v.name}[]" class="form-control" ></textarea>
                                                                                            {else}
                                                                                                <input {if $k==0 && $foo==1} id="attendeeid_{$v.name}" {/if} type="text" {if $v.mandatory==1}required{/if} 
                                                                                                name="attendee_{$v.name}[]" {$validate.{$v.datatype}}  {if $v.datatype=='date'}class="form-control date-picker" data-date-format="dd M yyyy" {else}class="form-control"{/if}  >
                                                                                            {/if}
                                                                                            <span class="help-block"> </span>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            {/if}
                                                                        {/foreach}
                                                                    </div>

                                                                    <div class="col-md-6">
                                                                        {foreach from=$attendees_capture item=v}
                                                                            {if $v.position=='R'}
                                                                                <div class="row">
                                                                                    <div class="form-group">
                                                                                        <label class="control-label col-md-4">{$v.column_name} <span class="required">
                                                                                                {if $v.mandatory==1}*{/if} </span></label>
                                                                                        <div class="col-md-8">
                                                                                            {if $v.datatype=='textarea'}
                                                                                                <textarea  {if $k==0 && $foo==1} id="attendeeid_{$v.name}" {/if} name="attendee_{$v.name}[]" class="form-control" ></textarea>
                                                                                            {else}
                                                                                                <input {if $k==0 && $foo==1} id="attendeeid_{$v.name}" {/if} type="text" {if $v.mandatory==1}required{/if} 
                                                                                                                             name="attendee_{$v.name}[]" {$validate.{$v.datatype}} {if $v.datatype=='date'}class="form-control date-picker" data-date-format="dd M yyyy" {else}class="form-control"{/if} >
                                                                                            {/if}
                                                                                            <span class="help-block"> </span>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            {/if}
                                                                        {/foreach}
                                                                    </div>
                                                                    <br>
                                                                </div>
                                                            {/for}
                                                        </div>
                                                    {/if}
                                                {/foreach}
                                                <br>
                                            </div>
                                        {/if}
                                        <input name="amount" readonly type="hidden"  value="{$absolute_cost}" />
                                        <input name="increpted" readonly type="hidden"  value="{$absolute_cost_incr}" />

                                        {if $absolute_cost==0 && $flexi==0}
                                            {$free=1}
                                        {/if}

                                        <div id="pay_div" {if !empty($attendees_capture)} class="hidden"{/if}>

                                            {if isset($radio) && $free==0}
                                                <div class="form-md-radios">
                                                    <hr>
                                                    <h4> Select payment mode {if $surcharge_amount>0} <span id="conv" style="font-size: 13px;font-weight: 500;">(Convenience fee of <i class="fa  fa-inr"></i> {$surcharge_amount|string_format:"%.2f"}/- applicable for online payments)</span>{/if}</h4>
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
                                                                            <input type="radio" id="radio{$int}" name="payment_mode" onchange="getgrandtotal('{$encrypt_grandtotal}', '{$v.fee_id}');" value="{$v.fee_id}" aria-required="true" aria-describedby="payment_mode-error" required class="md-radiobtn blue">
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
                                                                    <div class="col-md-4"><div style="max-width: 250px;" id="paypal-button-container"></div></div>
                                                                    <div class="col-md-8"><span>Pay using PayPal and get 200/- cashback or 50% off whichever is lower, on your first transaction with PayPal.</span></div>
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

                                        {if $enable_tnc==1}
                                            <label><input type="checkbox" class="icheck" data-checkbox="icheckbox_minimal-blue" required></span> I accept the online payments <a class="iframe" href="{$server_name}/terms-popup/{$info.merchant_id}">Terms and conditions</a> & <a class="iframe" href="{$server_name}/privacy-popup/{$info.merchant_id}">Privacy policy</a> </label>
                                            {/if}
                                        </p>
                                        <div class="row">
                                            <div class="col-md-8">
                                                <label> </label>
                                            </div>
                                            <div class="col-md-4">
                                                <input name="payment_req" type="hidden" size="60" value="{$payment_request_id}" />
                                                <input name="seat" type="hidden"  value="{$seat}" />
                                                <input name="coupon_id" type="hidden"  value="{$coupon_id}" />
                                                <input name="tax" type="hidden"  value="{$tax_amount}" />
                                                <input name="discount" type="hidden"  value="{$discount_amount}" />
                                                <input id="amt" type="hidden"  value="{$absolute_cost}" />
                                                <input  type="hidden" name="currency"  value="{$currency_id}" />

                                                {if $absolute_cost>0}
                                                    <button type="submit" {if isset($radio)}onclick="validate();"{/if} class="btn blue pull-right">Pay now</button>
                                                {else}
                                                    <button type="submit"  class="btn blue pull-right">Book now</button>
                                                {/if}
                                                {if !empty($attendees_capture)} 
                                                    <button type="button" onclick="showPayee();" class="btn default pull-right mr-r20"><< Back</button>
                                                {/if}
                                            </div>
                                        </div>
                                    </div>

                            </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>

            <script>
                payee_mandatory = '{$info.payee_capture}';
                function validate()
                {
                    if (!$("input[name='payment_mode']:checked").val()) {
                        document.getElementById('payment_mode_error').innerHTML = '* Please select payment mode.';
                    } else {
                    }

                }
                var request_url = '/patron/event/payment/paypal/{$paypal_fee_id}';
                var response_url = "/secure/paypalresponse/";
                var paypal_pg_id = '{$paypal_pg_id}';
                var form_id = 'submit_form';
            </script>
            <script src="/assets/admin/layout/scripts/paypal.js" type="text/javascript"></script>