<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <h3 class="page-title">&nbsp;</h3>
    <div class="loading" id="loader" style="display: none;">Processing your payment Please wait&#8230;</div>
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
                <div class="portlet-title">
                    <div class="col-md-9">
                        <div class="caption font-blue" style="">
                            <span class="caption-subject bold uppercase">
                                <h2>{$details.company_name}</h2>
                            </span>
                            <p>{$booking_title}</p>
                            {if {$confirmation_message} !=''}
                                <p style="color: #859494;">{$confirmation_message}</p>
                            {/if}
                        </div>
                    </div>
                    {if $grand_total>0}
                        <div class="col-md-3">
                            <div class="caption font-blue">
                                <h2 id="grandtotal"><i class="fa fa-inr fa-large"></i> {$grand_total|string_format:"%.2f"}/-
                                </h2>
                                {if $pg_surcharge_enabled==1}
                                    <span style="text-align: right !important;font-size: 12px;">Convenience fee of applicable
                                        for online payments based on payment mode</span>
                                {/if}
                            </div>
                        </div>
                    {/if}

                </div>
                <div class="portlet-body form">
                    <!--<h3 class="form-section">Profile details</h3>-->
                    <form action="{$post_url}" onsubmit="document.getElementById('loader').style.display = 'block';"
                        method="post" id="submit_form" class="form-horizontal form-row-sepe">
                        <div class="form-body">
                            <!-- Start profile details -->
                            <div class="row">
                                <div class="col-md-12">

                                    {if {$isGuest} =='1'}
                                        <div class="row">
                                            <h4>* Enter billing details</h4>
                                            <div class="col-md-6">
                                                {if in_array('customer_code',$capture_details)}
                                                    <div class="form-group">
                                                        <label class="control-label col-md-4">Customer code <span
                                                                class="required">
                                                            </span></label>
                                                        <div class="col-md-8">
                                                            <input type="text" name="customer_code"
                                                                value="{$customerdetails.customer_code}" class="form-control">
                                                            <span class="help-block"> </span>
                                                        </div>
                                                    </div>
                                                {/if}
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">Name <span class="required">
                                                            * </span></label>
                                                    <div class="col-md-8">
                                                        <input type="text" required name="name"
                                                            value="{$customerdetails.name}" class="form-control">
                                                        <span class="help-block"> </span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">Email <span class="required">
                                                            * </span></label>
                                                    <div class="col-md-8">
                                                        <input type="email" name="email" value="{$customerdetails.email}"
                                                            class="form-control">
                                                        <span class="help-block"> </span>
                                                    </div>

                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">Mobile <span class="required">
                                                            * </span> </label>

                                                    <div class="col-md-8">
                                                        <input type="text" required name="mobile" {$validate.mobile}
                                                            value="{$customerdetails.mobile}" class="form-control">
                                                        <span class="help-block"> </span>
                                                    </div>

                                                </div>


                                            </div>
                                            <div class="col-md-6">
                                                {if in_array('address',$capture_details)}
                                                    <div class="form-group">
                                                        <label class="control-label col-md-4">Address <span class="required">
                                                                * </span> </label>
                                                        <div class="col-md-8">
                                                            <textarea type="text" {$validate.payment_address} name="address"
                                                                {$validate.address}
                                                                class="form-control">{$customerdetails.address}</textarea>
                                                            <span class="help-block"> </span>
                                                        </div>
                                                    </div>
                                                {/if}
                                                {if in_array('city',$capture_details)}
                                                    <div class="form-group">
                                                        <label class="control-label col-md-4">City <span class="required">
                                                                * </span> </label>
                                                        <div class="col-md-8">
                                                            <input type="text" name="city" {$validate.name}
                                                                value="{$customerdetails.city}" class="form-control">
                                                            <span class="help-block"> </span>
                                                        </div>
                                                    </div>
                                                {/if}
                                                {if in_array('zipcode',$capture_details)}
                                                    <div class="form-group">
                                                        <label class="control-label col-md-4">Zipcode <span class="required">
                                                                * </span> </label>
                                                        <div class="col-md-8">
                                                            <input type="digit" name="zipcode" {$validate.zipcode}
                                                                value="{$customerdetails.zipcode}" class="form-control">
                                                            <span class="help-block"> </span>
                                                        </div>
                                                    </div>
                                                {/if}
                                                {if in_array('state',$capture_details)}
                                                    <div class="form-group">
                                                        <label class="control-label col-md-4">State <span class="required">
                                                                * </span></label>
                                                        <div class="col-md-8">
                                                            <input type="text" name="state" {$validate.name}
                                                                value="{$customerdetails.state}" class="form-control">
                                                            <span class="help-block"> </span>
                                                        </div>
                                                    </div>
                                                {/if}
                                            </div>
                                        </div>
                                    {/if}

                                    <div class="row">
                                        <div class="col-md-12">
                                            <h4>* Booking details</h4>
                                            <div class="table-scrollable">
                                                <table class="table table-condensed table-bordered table-scrollable"
                                                    style="max-width: 100%;margin-bottom: 0px;">
                                                    <tr style="font-weight: bold;">
                                                        <th style="width: 5%;">#</th>
                                                        <th style="width: 20%;">Category</th>
                                                        <th style="width: 20%;">{$booking_unit}</th>
                                                        <th style="width: 20%;">Package</th>
                                                        <th style="width: 25%;">Date & Duration</th>
                                                        <th style="width: 10%;">Qty</th>
                                                        {if $grand_total>0}
                                                            <th style="width: 10%;">Price</th>
                                                            <th style="width: 10%;">Total</th>
                                                        {/if}
                                                    </tr>
                                                    <tbody>
                                                        {$int=1}
                                                        {foreach from=$booking_slots item=v}
                                                            <tr>
                                                                <td style="width: 5%;">{$int}
                                                                    {for $foo=1 to $v.booking_qty}
                                                                        <input type="hidden" name="booking_slots[]"
                                                                            value="{$v.slot_id}">
                                                                        <input type="hidden" name="booking_qty[]"
                                                                            value="{$v.booking_qty}">
                                                                        <input type="hidden" name="booking_amount[]"
                                                                            value="{$v.amount}">
                                                                        <input type="hidden" name="booking_fromto[]"
                                                                            value="{$v.fromto}">
                                                                    {/for}
                                                                </td>
                                                                <td style="width: 20%;">{$v.category}</td>
                                                                <td style="width: 20%;">{$v.calendar}</td>
                                                                <td style="width: 20%;">{$v.package_name} - {$v.slot_title}
                                                                </td>
                                                                <td style="width: 20%;">{$date|date_format:"%d-%b-%Y"} -
                                                                    {$v.fromto}</td>
                                                                <td style="width: 10%;">{$v.booking_qty}</td>
                                                                {if $grand_total>0}
                                                                    <td style="width: 10%;">{$v.amount}</td>
                                                                    <td style="width: 10%;">{$v.amount*$v.booking_qty}</td>
                                                                {/if}
                                                            </tr>
                                                            {$int=$int+1}
                                                        {/foreach}
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    {if $is_new_pg == false}
                                        {if isset($radio) && $grand_total>0}
                                            <div class="form-group form-md-radios">
                                                <h4>* Select payment mode {if $surcharge_amount>0} <span
                                                            style="font-size: 13px;font-weight: 500;">(Convenience fee of <i
                                                                class="fa  fa-inr"></i> {$surcharge_amount|string_format:"%.2f"}/-
                                                        applicable for credit card & debit card payments)</span>{/if}</h4>
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


                                    <div class="row">
                                        <br>

                                        <div class="col-md-3">

                                            <input type="text" id="coupon_code" placeholder="Enter coupon code"
                                                class="form-control" value="" />
                                            <br>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="font-red">&nbsp;</label>
                                            <button onclick="return validateCoupon('{$merchant_id}', '{$grand_total}');"
                                                class="btn green">Apply</button>
                                        </div>
                                        <div class="col-md-5">
                                            <label class="font-blue" id="coupon_status">&nbsp;</label>

                                        </div>

                                    </div>
                                    <div class="form-actions">
                                        <div class="col-md-7">
                                            {if $grand_total>0 && $enable_tnc==1}
                                                <div class="form-group no-margin">
                                                    <label><input type="checkbox" required name="confirm" /></span> I accept
                                                        the <a href="/terms-popup/{$merchant_id}" class="iframe"> Terms and
                                                            conditions</a> & <a href="/privacy-popup" class="iframe">Privacy
                                                            policy</a> <span class="required">
                                                        </span>
                                                    </label>
                                                    <div id="form_payment_error"></div>
                                                </div>
                                            {/if}
                                        </div>
                                        <div class="col-md-5">
                                            {if {$tandc} != '' || {$cancellation_policy} !=''}
                                                <div class="pull-right">
                                                    <input required type="checkbox" id="policy_check" name="policy_check">
                                                    <label for="policy_check">I have read the
                                                        {if {$tandc} != ''}
                                                            <a data-toggle="modal" href="#tandc"> terms and
                                                                conditions</a>
                                                        {/if}
                                                        {if {$tandc} == ''}

                                                        {else}
                                                            {if {$cancellation_policy} == ''}
                                                            {else}
                                                                and
                                                            {/if}
                                                        {/if}
                                                        {if {$cancellation_policy} != ''}
                                                            <a data-toggle="modal" href="#cancellation_policy">cancellation
                                                                policy</a>
                                                        {/if}
                                                    </label>

                                                </div>
                                            {/if}
                                            <input name="date" type="hidden" class="displayonly" value="{$date}" />
                                            <input name="calendar_id" type="hidden" class="displayonly"
                                                value="{$calendar_id}" />
                                            <input name="coupon_id" type="hidden" id="coupon_id" class="displayonly"
                                                value="" />
                                            <input name="plan_name" type="hidden" value="{$booking_title}" />
                                            <input name="category_name" type="hidden" value="{$category_name}" />
                                            <input name="calendar_title" type="hidden" value="{$calendar_title}" />
                                            <input name="post_url" type="hidden" value="/m/{$url}/slotpayment" />
                                            <input name="request_post_url" type="hidden" value="{$request_post_url}" />
                                            <input name="url" type="hidden" value="{$url}" />
                                            <input name="amount" type="hidden" value="{$grand_total}" />

                                            {if $grand_total>0}
                                                <input type="submit" name="pay_now" onclick="checkmode();" value="Pay now"
                                                    class="btn blue pull-right">
                                            {else}
                                                <input name="book_now" type="submit" value="Book now"
                                                    class="btn blue pull-right">
                                            {/if}
                                            <a href="/m/{$url}/booking" class="btn default pull-right"
                                                style="margin-right: 10px;"><i class="fa fa-arrow-left"></i> &nbsp;
                                                Back</a>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
                <!-- End profile details -->



                </form>

            </div>
            <hr />
            <div class="hidden-xs">
                <p style="text-align: left;">&copy; {$current_year} OPUS Net Pvt. Handmade in Pune. <a target="_BLANK"
                        href="https://www.swipez.in"><img src="/assets/admin/layout/img/logo.png"
                            class="img-responsive pull-right powerbyimg" alt="" /><span class="powerbytxt">powered
                            by</span></a></p>
            </div>
            <div class="hidden-lg">
                <p style="text-align: left;">&copy; {$current_year} OPUS Net Pvt. Handmade in Pune.<br> <a
                        target="_BLANK" href="https://www.swipez.in"><span class="powerbytxt pull-left">powered
                            by</span><img src="/assets/admin/layout/img/logo.png"
                            class="img-responsive pull-left powerbyimg" alt="" /></a></p>
            </div>

        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>
</div>
<!-- END CONTENT -->
</div>

<div class="modal fade bs-modal-lg in" id="tandc" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">

    <div class="modal-dialog modal-lg">
        <form action="" method="post" id="categoryForm" class="form-horizontal">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close"
                        onclick="document.getElementById('errors').style.display = 'none';" id="closebutton1"
                        data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Terms and Conditions</h4>
                </div>

                <div class="modal-body">
                    <div class="row" style="padding: 20px;">
                        <p>{$tandc}</p>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn default"
                        onclick="document.getElementById('errors').style.display = 'none';" id="closebutton"
                        data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
        <!-- /.modal-content -->
    </div>
</div>

<div class="modal fade bs-modal-lg in" id="cancellation_policy" tabindex="-1" role="dialog"
    aria-labelledby="myModalLabel1" aria-hidden="true">

    <div class="modal-dialog modal-lg">
        <form action="" method="post" id="categoryForm" class="form-horizontal">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close"
                        onclick="document.getElementById('errors').style.display = 'none';" id="closebutton1"
                        data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Cancellation Policy</h4>
                </div>

                <div class="modal-body">
                    <div class="row" style="padding: 20px;">
                        <p>{$cancellation_policy}</p>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn default"
                        onclick="document.getElementById('errors').style.display = 'none';" id="closebutton"
                        data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
        <!-- /.modal-content -->
    </div>
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
</script>