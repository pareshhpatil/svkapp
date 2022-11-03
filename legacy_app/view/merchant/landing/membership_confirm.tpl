
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <h3 class="page-title">&nbsp;</h3>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row no-margin">
        <div class="col-md-2"></div>
        <div class="col-md-8" style="text-align: -webkit-center;text-align: -moz-center;">
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
                            <span class="caption-subject bold uppercase"> <h3>{$details.company_name}</h3></span>
                            <p>Membership for: {$info.title}</p>
                        </div>
                    </div>
                    {if $grand_total>0}
                        <div class="col-md-3">
                            <div class="caption font-blue">
                                <h2 id="grandtotal"><i class="fa fa-inr fa-large"></i> {$grand_total|string_format:"%.2f"}/-</h2>
                                {if $pg_surcharge_enabled==1}     
                                    <span style="text-align: right !important;font-size: 12px;">Convenience fee of applicable for online payments based on payment mode</span>
                                {/if}
                            </div>
                        </div>
                    {/if}

                </div>
                <div class="portlet-body form">
                    <!--<h3 class="form-section">Profile details</h3>-->
                    <form action="/m/{$url}/membershippayment" method="post" id="submit_form" class="form-horizontal form-row-sepe">
                        <div class="form-body">
                            <!-- Start profile details -->
                            <div class="row">
                                <div class="col-md-12">

                                    <div class="row">
                                        <h4>* Enter billing details</h4>
                                        <div class="col-md-6">
                                            {if in_array('customer_code',$capture_details)}
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">Customer code <span class="required">
                                                        </span></label>
                                                    <div class="col-md-8">
                                                        <input type="text"  name="customer_code" value="{$customerdetails.customer_code}" class="form-control" >
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
                                                    <input type="email" required="" name="email"  value="{$customerdetails.email}" class="form-control" >
                                                    <span class="help-block"> </span>
                                                </div>

                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-4">Mobile <span class="required">
                                                        * </span> </label>

                                                <div class="col-md-8">
                                                    <input type="text" required name="mobile" {$validate.mobile} value="{$customerdetails.mobile}"  class="form-control" >
                                                    <span class="help-block"> </span>
                                                </div>

                                            </div>


                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label col-md-4">Address <span class="required">
                                                    </span> </label>
                                                <div class="col-md-8">
                                                    <textarea type="text"  {$validate.payment_address} name="address" {$validate.address} class="form-control" >{$customerdetails.address}</textarea>
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
                                    {if isset($radio) && $grand_total>0}
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
                                            {if $grand_total>0 && $enable_tnc==1}
                                                <div class="form-group no-margin">
                                                    <label><input type="checkbox" required name="confirm"/></span>  I accept the <a href="/terms-popup" class="iframe"> Terms and conditions</a> & <a href="/privacy-popup" class="iframe">Privacy policy</a> <span class="required">
                                                        </span>
                                                    </label>
                                                    <div id="form_payment_error"></div>
                                                </div>
                                            {/if}
                                        </div>
                                        <div class="col-md-5">
                                            <input name="membership_id" type="hidden" class="displayonly" value="{$info.membership_id}" />
                                            <input name="coupon_id" type="hidden"  id="coupon_id" class="displayonly" value="" />
                                            <input name="amount" id="amt" type="hidden" value="{$grand_total}" />
                                            {if $grand_total>0}
                                                <input type="submit" name="pay_now" onclick="checkmode();" value="Pay now" class="btn blue pull-right">
                                            {else}
                                                <input name="book_now" type="submit" value="Book now" class="btn blue pull-right">
                                            {/if}

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

</script>