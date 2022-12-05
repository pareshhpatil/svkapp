
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

                <div class="portlet light bordered" style="max-width: 900px;text-align: left;">
                    <div class="portlet-title" >
                        <div class="col-md-9">
                            <div class="caption font-blue" style="">
                                <span class="caption-subject bold uppercase"> <h2>{$result.company_name}</h2></span>

                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="caption font-blue">
                                <h2 id="grandtotal"><i class="fa fa-inr fa-large"></i> {$grand_total|string_format:"%.2f"}/-</h2>
                            </div>
                        </div>

                    </div>
                    <div class="portlet-body form">
                        <!--<h3 class="form-section">Profile details</h3>-->
                        <form action="/m/{$url}/directpayment" method="post" id="submit_form" class="form-horizontal form-row-sepe">
                        <input type="hidden" name="recaptcha_response" id="captcha1">
                        <div class="form-body">
                                <!-- Start profile details -->
                                <div class="row">
                                    <div class="col-md-12">

                                        <div class="row">
                                            <h4>* Enter billing details</h4>
                                            <div class="col-md-6">

                                                <div class="form-group">
                                                    <label class="control-label col-md-4">Customer code <span class="required">
                                                        </span></label>
                                                    <div class="col-md-8">
                                                        <input type="text"  name="customer_code" value="{$post.customer_code}" class="form-control" >
                                                        <span class="help-block"> </span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">Name <span class="required">
                                                            * </span></label>
                                                    <div class="col-md-8">
                                                        <input type="text" required name="name" value="{$post.name}" class="form-control" >
                                                        <span class="help-block"> </span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">Email <span class="required">
                                                            * </span></label>
                                                    <div class="col-md-8">
                                                        <input type="email" name="email"  value="{$post.email}" class="form-control" >
                                                        <span class="help-block"> </span>
                                                    </div>

                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">Mobile <span class="required">
                                                            * </span> </label>

                                                    <div class="col-md-8">
                                                        <input type="text" required name="mobile" {$validate.mobile} value="{$post.mobile}"  class="form-control" >
                                                        <span class="help-block"> </span>
                                                    </div>

                                                </div>


                                            </div>
                                            <div class="col-md-6">

                                                <div class="form-group">
                                                    <label class="control-label col-md-4">Address <span class="required">
                                                        </span> </label>
                                                    <div class="col-md-8">
                                                        <textarea type="text"  maxlength="255" name="address" {$validate.address} class="form-control" >{$customerdetails.address}</textarea>
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
                                        {if isset($radio)}
                                            <div class="form-group form-md-radios">
                                                <h4>* Select payment mode {if $surcharge_amount>0} <span style="font-size: 13px;font-weight: 500;">(Convenience fee of <i class="fa  fa-inr"></i> {$surcharge_amount|string_format:"%.2f"}/- applicable for credit card & debit card payments)</span>{/if}</h4>
                                                <div id="form_gender_error" style="color: red;"></div>
                                                <div class="md-radio-inline">
                                                    {$int=6}
                                                    {foreach from=$radio item=v}
                                                        <div class="md-radio">
                                                            <input type="radio" required id="radio{$int}" name="payment_mode" onchange="getgrandtotal('{$encrypt_grandtotal}', '{$v.fee_id}');" value="{$v.fee_id}"  class="md-radiobtn">
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

                                        {if $is_coupon}
                                            <div class="row">
                                                <br>

                                                <div class="col-md-3">

                                                    <input type="text" id="coupon_code" placeholder="Enter coupon code" class="form-control" value="" />
                                                    <br>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="font-red">&nbsp;</label>
                                                    <button onclick="return validateCoupon('{$merchant_id}', '{$grand_total}');" class="btn green">Apply</button>
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
                                                        <label><input type="checkbox" required name="confirm"/></span>  I accept the <a href="/terms-popup/{$merchant_id}" class="iframe"> Terms and conditions</a> & <a href="/privacy-popup/{$merchant_id}" class="iframe">Privacy policy</a> <span class="required">
                                                            </span>
                                                        </label>
                                                        <div id="form_payment_error"></div>
                                                    </div>
                                                {/if}
                                            </div>
                                            <div class="col-md-5">
                                                <input type="submit" onclick="checkmode();" value="Pay now" class="btn blue">
                                                <input type="hidden" name="customer_code" value="{$post.customer_code}">
                                                <input type="hidden" name="purpose" value="{$post.purpose}">
                                                <input type="hidden" name="amount" value="{$amount}">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>					
                    <!-- End profile details -->



                    </form>

                </div>
                <hr/>
                <p style="text-align: left;">&copy; {$current_year} OPUS Net Pvt. Handmade in Pune. <a target="_BLANK" href="https://www.swipez.in"><img src="/assets/admin/layout/img/logo.png" class="img-responsive pull-right powerbyimg" alt=""/><span class="powerbytxt">powered by</span></a></p>
            </div>
        </div>	
        <!-- END PAGE CONTENT-->
    </div>
</div>
<!-- END CONTENT -->
</div>
