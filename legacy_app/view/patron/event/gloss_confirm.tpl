<script>
    var t = '{$pg.swipez_fee_type}';
    var e = '{$pg.swipez_fee_val}';
    var a = '{$pg.pg_fee_type}';
    var n = '{$pg.pg_fee_val}';
    var u = '{$pg.pg_tax_val}';

</script>
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <h3 class="page-title">&nbsp;</h3>
    <div class="row no-margin">
        <div class="col-md-2"></div>
        <div class="col-md-8 " style="text-align: left;">
    <a href="/patron/event/view/{$payment_request_id}" class="btn default pull-left"><i class="fa fa-arrow-left"></i> &nbsp; Modify Order</a>
    <br>
    <br>
    <br>
    </div>
    </div>
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

                <form action="{$post_link}" id="gloss_confirm"  method="post">
                    <div class="portlet light bordered" style="text-align: left;">
                        <div class="portlet-title">
                            <div class="col-md-8">
                                <div class="caption font-blue">
                                    <span class="caption-subject bold uppercase"> <h2>{$info.event_name}</h2></span>
                                    <p>Presented by : {$info.company_name}</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="caption font-blue pull-right">
                                    <h3 id="grandtotal"><i class="fa fa-inr fa-large"></i> {$absolute_cost_display|string_format:"%.2f"} /-</h3>
                                    {if $absolute_cost_display!=$absolute_cost}
                                        <p>Includes convenience fee : &nbsp;<i class="fa fa-inr fa-sm"></i> {$surcharge_amount}</p>
                                    {/if}

                                </div>
                            </div>
                            <br>
                        </div>
                        {if {$isGuest} =='1'}
                            <div class="form-body">

                                <div class="row">
                                    <span style="font-size: initial;font-weight: 100;"> Enter Billing details</span>

                                    <br>
                                    <br>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="form-group">
                                                <label class="control-label col-md-4">Name <span class="required">
                                                        * </span></label>
                                                <div class="col-md-8">
                                                    <input type="text" required name="name" {$validate.name} class="form-control" >
                                                    <span class="help-block"> </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group">
                                                <label class="control-label col-md-4">Email <span class="required">
                                                        * </span></label>
                                                <div class="col-md-8">
                                                    <input type="email" required="" name="email" {$validate.email} class="form-control" >
                                                    <span class="help-block"> </span>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group">
                                                <label class="control-label col-md-4">Mobile <span class="required">
                                                        * </span> </label>

                                                <div class="col-md-8">
                                                    <input type="text" required name="mobile" {$validate.mobile} class="form-control" >
                                                    <span class="help-block"> </span>
                                                </div>
                                            </div>
                                        </div>


                                    </div>

                                    <div class="col-md-6">

                                        <div class="row">
                                            <div class="form-group">
                                                <label class="control-label col-md-4">Address <br><span style="font-size: x-small;">(min 25 char)</span> <span class="required">
                                                        * </span> </label>
                                                <div class="col-md-8">
                                                    <textarea type="text" required name="address" {$validate.address} class="form-control" ></textarea>
                                                    <span class="help-block"> </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group">
                                                <label class="control-label col-md-4">City <span class="required">
                                                        * </span> </label>
                                                <div class="col-md-8">
                                                    <input type="text" required name="city" {$validate.name} class="form-control" value="">
                                                    <span class="help-block"> </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group">
                                                <label class="control-label col-md-4">Zipcode <span class="required">
                                                        * </span> </label>
                                                <div class="col-md-8">
                                                    <input type="digit" required name="zipcode" {$validate.zipcode} class="form-control" value="">
                                                    <span class="help-block"> </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group">
                                                <label class="control-label col-md-4">State <span class="required">
                                                        * </span></label>
                                                <div class="col-md-8">
                                                    <input type="text" required name="state" {$validate.name} class="form-control" value="">
                                                    <span class="help-block"> </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                </div>
                            </div>
                        {/if}
                        {if $info.event_type!=2}

                            <div class="row">
                                {if $info.capture_details==1}
                                    <span style="font-size: initial;font-weight: 100;"> Enter Attendee Information</span>
                                    <span style="font-weight: 100;"> (Optional)</span>
                                {else}
                                    <span style="font-size: initial;font-weight: 100;"> Package Information</span>
                                {/if}

                                <table class="table table-condensed table-bordered " style="margin-bottom: 0px;">
                                    <tr style="font-weight: bold;">
                                        <th style="width: 10%;">#</th>
                                        <th style="width: 40%;">Details</th>
                                        <th style="width: 10%;">Units</th>
                                        <th style="width: 15%;">Price</th>
                                        <th style="width: 15%;">Total</th>
                                        <th style="width: 10%;"></th>
                                    </tr>
                                </table>
                                <table class="table table-condensed table-bordered " style="margin-bottom: 0px;">
                                    <tr >
                                        <td style="width: 10%;">1</td>
                                        <td style="width: 40%;">{$package.0.package_name}</td>
                                        <td style="width: 10%;">{$package.0.seat}</td>
                                        <td style="width: 15%;">{$package.0.price}</td>
                                        <td style="width: 15%;">{{$package.0.price*$package.0.seat}|string_format:"%.2f"}</td>
                            <td style="width: 10%;"><a href="javascript:;" onclick="return calculateTotal(this,{$package.0.price},{$package.0.package_id});" class="btn btn-sm red"> <i class="fa fa-remove"> </i>  </a>
                                            <input name="package_id[]"   type="hidden" value="{$package.0.package_id}" />
                                            <input name="price[]" class="displayonly"  type="hidden" value="{$package.0.price}" />
                                            <input name="occurence_id[]"   type="hidden" value="{$package.0.occurence_id}" />
                                        </td>
                                    </tr>
                                </table>
                                <table class="table table-condensed table-hover" style="margin-bottom: 0px;width: 40%;">
                                    {$package_name=$package.0.package_id}
                                    {assign var=id value=1}
                                    {foreach from=$package item=v}
                                        {if $v.seat>0}
                                            {if $v.package_id!=$package_name}
                                                {$package_name=$v.package_id}
                                                {$id=$id+1}
                                            </table>
                                            <table class="table table-condensed table-bordered " style="margin-bottom: 0px;">
                                                <tr>
                                                    <td style="width: 10%;">{$id}</td>
                                                    <td style="width: 40%;">{$v.package_name}</td>
                                                    <td style="width: 10%;">{$v.seat}</td>
                                                    <td style="width: 15%;">{$v.price}</td>
                                                    <td style="width: 15%;">{{$v.price*$v.seat}|string_format:"%.2f"}</td>
                    <td style="width: 10%;"><a href="javascript:;" onclick="return calculateTotal(this,{$v.price},{$v.package_id});" class="btn btn-sm red"> <i class="fa fa-remove"> </i> </a>
                                                        <input name="package_id[]"   type="hidden" value="{$v.package_id}" />
                                                        <input name="price[]" class="displayonly"  type="hidden" value="{$v.price}" />
                                                        <input name="occurence_id[]"   type="hidden" value="{$v.occurence_id}" />
                                                    </td>
                                                </tr>
                                            </table>
                                            <table class="table table-condensed table-hover" style="margin-bottom: 0px;width: 40%;">
                                            {/if}

                                        </table>

                                    {/if}
                                {/foreach}
                            </div>
                        {/if}
                        <input name="price[]" readonly type="hidden"  value="0" />
                        <input name="amount" readonly id="amtgloss" type="hidden"  value="{$absolute_cost}" />
                        <input name="increpted" id="incrypt" readonly type="hidden"  value="{$absolute_cost_incr}" />

                        {if isset($radio)}
                            <div class="form-group form-md-radios">
                                <h4>* Select payment mode {if $surcharge_amount>0} <span style="font-size: 13px;font-weight: 500;">(Convenience fee of <i class="fa  fa-inr"></i> {$surcharge_amount|string_format:"%.2f"}/- applicable for credit card & debit card payments)</span>{/if}</h4>
                                <div id="form_gender_error"></div>
                                <div class="md-radio-inline">
                                    <span id="payment_mode-error" class="help-block help-block-error"></span>
                                    {$int=6}
                                    {foreach from=$radio item=v}
                                        <div class="md-radio">
                                            <input type="radio"  required id="radio{$int}" name="payment_mode" value="{$v.fee_id}" aria-required="true" aria-describedby="payment_mode-error" class="md-radiobtn">
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

                        <p>
                            Please note: We do not store any of your card/ account information when you make a payment. For online payment, we may redirect you to a secure banking/payment gateway website to provide your card/account credentials.</p>
                        <p>
                            <label><input type="checkbox" required></span> I accept the <a class="iframe" href="{$server_name}/terms-popup{$terms_id}">Terms and conditions</a> & <a class="iframe" href="{$server_name}/privacy-popup{$policy_id}">Privacy policy</a> </label>
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
                                <a href="/patron/event/view/{$payment_request_id}" class="btn default"><i class="fa fa-arrow-left"></i> &nbsp; Modify Order</a>
                                {if $absolute_cost_display>0}
                                    <button type="submit" class="btn blue pull-right">Pay now</button>
                                {else}
                                    <button type="submit" class="btn blue pull-right">Book now</button>
                                {/if}
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
                            <div class="modal fade" id="basic" tabindex="-1" role="basic" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                        <h4 class="modal-title">Cart is empty</h4>
                                    </div>
                                    <div class="modal-body">
                                        You have deleted all chapters from your cart. We will redirect you to the book page to add other chapters. 
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn default" data-dismiss="modal">Close</button>
                                        <a id="deleteid" href="/patron/event/view/{$payment_request_id}" class="btn blue">Confirm</a>
                                    </div>
                                </div>
                                <!-- /.modal-content -->
                            </div>
                            <!-- /.modal-dialog -->
                        </div>
<a  href="#basic"  data-toggle="modal" id="guest"></a> 
<script src="/assets/admin/layout/scripts/gloss.js" type="text/javascript"></script>