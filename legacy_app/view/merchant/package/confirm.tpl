{if $paypal_id!=''}
    <script src="https://www.paypal.com/sdk/js?client-id={$paypal_id}&locale=en_IN&currency=INR"></script>
{/if}
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <h3 class="page-title">&nbsp;</h3>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row no-margin">
        <div class="col-md-2"></div>
        <div class="col-md-8" style="text-align: -webkit-center;text-align: -moz-center;">

            {if !empty($billingerrors)}
                <div class="alert alert-danger alert-dismissable" style="max-width: 900px;text-align: left;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                    <strong>Error!</strong>
                    <div class="media">
                        {foreach from=$billingerrors key=k item=v}
                            <p class="media-heading">{$k} - {$v.1}</p>
                        {/foreach}
                    </div>

                </div>
            {/if}
            <div class="" id="error" style="text-align:left; display: none;padding: 15px;background-color: #f2dede;border-color: #ebccd1;color: #a94442;"></div>

            <div class="portlet light bordered" style="max-width: 900px;text-align: left;">
                <div class="portlet-title" >
                    <div class="col-md-8">
                        <div class="caption font-blue" style="">
                            <span class="caption-subject bold uppercase"> <h2>{$info.package_name}</h2></span>
                            <p>{$info.package_description}</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="caption font-blue pull-right">
                            {if $info.package_id==7}   
                                {$tax=($info.package_cost*5000)*18/100}
                                {$package_cost=$info.package_cost*5000}
                                <h2 id="grandtotal"><i class="fa fa-inr fa-large"></i> {{$tax+$info.package_cost*5000}|string_format:"%.0f"}/-</h2>
                                (Inclusive of GST 18%)
                            {else}
                                {$tax=$info.package_cost*18/100}
                                {$package_cost=$info.package_cost}
                                <h2 id="grandtotal"><i class="fa fa-inr fa-large"></i> {{$info.package_cost+$tax}|string_format:"%.0f"}.00/-</h2>
                                (Inclusive of GST 18%)
                            {/if}
                        </div>

                    </div>

                </div>
                <div class="portlet-body form">
                    <!--<h3 class="form-section">Profile details</h3>-->
                    <form action="/merchant/package/payment" method="post" id="submit_form" class="form-horizontal form-row-sepe">
                        <div class="form-body">
                            <!-- Start profile details -->
                            <div class="row">
                                <div class="col-md-12">

                                    <div class="row">
                                        <h4>* Enter billing details</h4>
                                        <div class="col-md-6">

                                            {if $info.package_id==7}   
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">SMS Pack <span class="required">
                                                            * </span></label>
                                                    <div class="col-md-8">
                                                        <select name="smspack" onchange="calculateSMS({$info.package_cost}, this.value);" class="form-control" >
                                                            <option value="5000">5000</option>
                                                            <option value="10000">10000</option>
                                                            <option value="15000">15000</option>
                                                            <option value="20000">20000</option>
                                                            <option value="25000">25000</option>
                                                            <option value="30000">30000</option>
                                                            <option value="35000">35000</option>
                                                            <option value="40000">40000</option>
                                                            <option value="45000">45000</option>
                                                            <option value="50000">50000</option>
                                                            <option value="55000">55000</option>
                                                            <option value="60000">60000</option>
                                                            <option value="65000">65000</option>
                                                            <option value="70000">70000</option>
                                                            <option value="75000">75000</option>
                                                            <option value="80000">80000</option>
                                                            <option value="85000">85000</option>
                                                            <option value="90000">90000</option>
                                                            <option value="95000">95000</option>
                                                            <option value="100000">100000</option>
                                                        </select>
                                                        <span class="help-block"> </span>
                                                    </div>
                                                </div>
                                            {/if}

                                            {if empty($customerdetails)}
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">Company name <span class="required">
                                                            * </span></label>
                                                    <div class="col-md-8">
                                                        <input type="text" required name="company_name" value="" class="form-control" >
                                                        <input type="hidden" required name="form_type" value="promo">
                                                        <span class="help-block"> </span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">Password <span class="required">
                                                            * </span>
                                                    </label>
                                                    <div class="col-md-8">
                                                        <input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="Password" name="password" id="submit_form_password" {$validate.password}/>
                                                        <span class="help-block"> </span>
                                                    </div>
                                                </div>
                                            {/if}
                                            <div class="form-group">
                                                <label class="control-label col-md-4">Name <span class="required">
                                                        * </span></label>
                                                <div class="col-md-8">
                                                    <input type="text" required name="name" value="{$customerdetails.name}" class="form-control" >
                                                    <span class="help-block"> </span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-4">Email <span class="required">
                                                        * </span></label>
                                                <div class="col-md-8">
                                                    <input type="email" name="email"  value="{$customerdetails.email_id}" class="form-control" >
                                                    <span class="help-block"> </span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-4">Mobile <span class="required">
                                                        * </span> </label>

                                                <div class="col-md-8">
                                                    <input type="text" required name="mobile" {$validate.mobile} value="{$customerdetails.mobile_no}"  class="form-control" >
                                                    <span class="help-block"> </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">

                                            <div class="form-group">
                                                <label class="control-label col-md-4">Address <span class="required">
                                                    </span> </label>
                                                <div class="col-md-8">
                                                    <textarea type="text"  pattern=".{ldelim}25,255{rdelim} " name="address" {$validate.address} class="form-control" >{$customerdetails.address}</textarea>
                                                    <span class="help-block"> </span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-4">City <span class="required">
                                                    </span> </label>
                                                <div class="col-md-8">
                                                    <input type="text"  name="city" {$validate.name} value="{$customerdetails.city}"  class="form-control" >
                                                    <span class="help-block"> </span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-4">Zipcode <span class="required">
                                                    </span> </label>
                                                <div class="col-md-8">
                                                    <input type="digit"  name="zipcode" {$validate.zipcode} value="{$customerdetails.zipcode}"  class="form-control" >
                                                    <span class="help-block"> </span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-4">State <span class="required">
                                                    </span></label>
                                                <div class="col-md-8">
                                                    <input type="text"  name="state" {$validate.name} value="{$customerdetails.state}"  class="form-control" >
                                                    <span class="help-block"> </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    {if $is_coupon && $info.package_id!=7}
                                        <div class="row">
                                            <br>
                                            <div class="col-md-3">

                                                <input type="text" id="coupon_code" placeholder="Enter coupon code" class="form-control" value="" />
                                                <input type="hidden" id="coupon_id" name="coupon_id" value="" />
                                                <br>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="font-red">&nbsp;</label>
                                                <button onclick="return validatePackageCoupon('{$support_user_id}');" class="btn green">Apply</button>
                                            </div>
                                            <div class="col-md-5">
                                                <label class="font-blue" id="coupon_status">&nbsp;</label>

                                            </div>

                                        </div>
                                    {/if}
                                    {if isset($radio)}
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
                                <div class="form-actions">
                                    <div class="col-md-7">
                                        <div class="form-group no-margin">
                                            <label><input type="checkbox" required name="confirm"/></span>  I accept the <a href="/terms-popup" class="iframe"> Terms and conditions</a> & <a href="/privacy-popup" class="iframe">Privacy policy</a> <span class="required">
                                                </span>
                                            </label>
                                            <div id="form_payment_error"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <input name="package_id" type="hidden" class="displayonly" value="{$info.package_id}" />
                                        <input name="custom_package_id" type="hidden" class="displayonly" value="{$info.custom_package_id}" />
                                        <input type="hidden" name="user_id" class="displayonly" value="{$customerdetails.user_id}">
                                        {if $info.custom_package_id>0}
                                            <a href="/merchant/package/custom" class="btn default"><i class="fa fa-arrow-left"></i> &nbsp; Change package</a>
                                        {else}
                                            <a href="/pricing" class="btn default"><i class="fa fa-arrow-left"></i> &nbsp; Change package</a>
                                        {/if}

                                        <input type="submit" value="Pay now" class="btn blue">
                                        <input type="hidden" class="displayonly" id="amount" value="{$package_cost}">
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
            </div>					
            <!-- End profile details -->



            </form>

        </div>
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
            if ($('input[type=radio]:checked').size() > 0)
            {

            } else
            {
                document.getElementById('form_gender_error').innerHTML = 'Please select payment mode';
            }
        } catch (o) {
        }
    }
    var request_url = '/merchant/package/payment/paypal/{$paypal_fee_id}';
    var response_url = "/secure/paypalresponse/";
    var paypal_pg_id = '{$paypal_pg_id}';
    var form_id = 'submit_form';
</script>
<script src="/assets/admin/layout/scripts/paypal.js" type="text/javascript"></script>