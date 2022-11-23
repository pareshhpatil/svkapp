<script>
    var t = '{$pg.swipez_fee_type}';
    var e = '{$pg.swipez_fee_val}';
    var a = '{$pg.pg_fee_type}';
    var n = '{$pg.pg_fee_val}';
    var u = '{$pg.pg_tax_val}';

</script>
<div class="page-content">
    <div class="row no-margin">

        {if {$isGuest} =='1'}
            <div class="col-md-2"></div>
            <div class="col-md-8" style="text-align: -webkit-center;text-align: -moz-center;">
            {else}
                <div class="col-md-1"></div>
                <div class="col-md-10" style="text-align: -webkit-center;text-align: -moz-center;">
                {/if}
                <br>
                {if $is_valid=='NO' && !isset($is_invoice)}
                    <div class="alert alert-danger max900">
                        <button type="button" class="close" data-dismiss="alert"></button>
                        <strong>Invalid event!</strong>
                        <div class="media">
                            <p class="media-heading">{$invalid_message}</p>
                        </div>
                    </div>
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

                {if {$package.0.min_seat}>{$package.0.available} && {$package.0.seats_available}!=0}
                    <div class="alert alert-danger">
                        <div class="media">
                            <p class="media-heading">All the seats for this event have already been booked.</p>
                        </div>
                    </div>
                {/if}

                <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->

                <!-- /.modal -->
                <!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
                <!-- BEGIN PAGE CONTENT-->
                <div  style="text-align: left;box-shadow: 1px 10px 10px #888888;">
                    <div class="row">
                        {if $info.banner_path!=''}
                            <div class="col-md-12 fileinput fileinput-new" style="text-align: center;" data-provides="fileinput">
                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                    <div class="fileinput-preview thumbnail banner-container"  data-trigger="fileinput"> 
                                        <img class="img-responsive" style="width: 100%;" src="{if $info.banner_path!=''}/uploads/images/logos/{$info.banner_path}{else}holder.js/100%x100%{/if}">
                                    </div>
                                </div>
                            </div>

                        {/if}


                    </div>
                    <div class="row">
                        <div class="col-md-12 no-padding fileinput fileinput-new " style="width: 100%;" data-provides="fileinput">
                            <div class="fileinput-new thumbnail col-md-12 title">
                                <div class="caption font-blue" style="background-color: #275770; margin-left: 10px;margin-right: 10px;">
                                    <span class="caption-subject bold" style="color: #FFFFFF;" > <h4>{$info.event_name}</h4></span>
                                    <p style="color: #FFFFFF;">Presented by <a href="{$merchant_page}" style="color: #FFFFFF;" target="BLANK">{$info.company_name}</a></p>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row no-margin no-padding" style="">

                        <div class="col-md-1"></div>
                        <div class="col-md-10 invoice-payment">
                            <form id="event_form" action="/patron/event/pay/{$url}" method="post">

                                {if $info.venue!=''}
                                    <div class="row">
                                        <div class="col-md-3"><strong>Venue</strong></div>
                                        <div class="col-md-9"><i class="fa fa-map-marker"></i>&nbsp;&nbsp;&nbsp;  {$info.venue}</div>
                                    </div>
                                {/if}


                                <div class="row">

                                    <div class="col-md-3"><strong>Start date</strong></div>
                                    <div class="col-md-9"><i class="fa fa-calendar"></i>&nbsp;&nbsp;&nbsp;{if $info.event_from_date==$info.event_to_date}{{$info.event_from_date}|date_format:"%d-%b-%Y"}{else}
                                        {{$info.event_from_date}|date_format:"%d-%b-%Y"} to {{$info.event_to_date}|date_format:"%d-%b-%Y"}
                                        {/if}
                                            <input type="hidden" value="{$occurence.0.occurence_id}" name="start_date">
                                        </div>
                                    </div>

                                    {if $info.description!=''}
                                        <div class="row">
                                            <div class="col-md-3"><strong>Description</strong></div>
                                            <div class="col-md-9">{$info.description}</div>
                                        </div>
                                    {/if}
                                    {$has_terms=0}
                                    {foreach from=$header item=v}
                                        {if $v.value!=''}
                                            {if $v.column_position==1}
                                                {$terms=$v.value}
                                                {$has_terms=1}
                                            {else if $v.column_position==2}
                                                {$privacy=$v.value}
                                                {$has_terms=1}
                                            {else}
                                                <div class="row">
                                                    <div class="col-md-3"><strong>{$v.column_name}</strong></div>
                                                    <div class="col-md-9">{$v.value}</div>
                                                </div>
                                            {/if}
                                        {/if}

                                    {/foreach}
                                    {$int=1}

                                    <div class="row">
                                        <div class="col-md-3"><strong>Price</strong></div>
                                        <div class="col-md-9"><i class="fa fa-rupee"></i>&nbsp;&nbsp;&nbsp;
                                            {if {$package.0.is_flexible==1}}
                                                Min - {$package.0.min_price} Max - {$package.0.max_price}
                                                <input type="hidden" name="is_flexible[]" value="1">
                                            {else}
                                                <input type="hidden" readonly="" class="form-control displayonly" value="{$package.0.price}" id="unitprice{$int}">
                                                {$package.0.price}
                                            {/if}
                                        </div>
                                    </div>




                                    {if $package.0.max_seat==1}
                                        <input class="form-control" type="hidden" id="unitnumber1" value="1" name="seat[]">
                                    {else}
                                        <div class="row">
                                            <div class="col-md-3"><strong>Select {$display_booking}</strong></div>
                                            <div class="col-md-2">
                                                <select name="seat[]" class="form-control" style="min-width:100px;" id="unitnumber{$int}" onchange="calculateevent({$int});" data-placeholder="Select...">
                                                    <option value="0">0</option>
                                                    {$max={$package.0.max_seat}}
                                                    {if {$package.0.max_seat}>{$package.0.available} && {$package.0.seats_available}!=0}
                                                        {$max={$package.0.available}}
                                                    {/if}
                                                    {for $foo={$package.0.min_seat} to $max}
                                                        <option value="{$foo}">{$foo}</option>
                                                    {/for}
                                                </select>
                                            </div>

                                        </div>
                                    {/if}


                                    <input type="hidden" value="{$package.0.package_id}" name="package_id[]" >
                                    <input type="hidden" min="1"  name="total"  id="totalcostamt" readonly="" value="" class="form-control col-md-2 displayonly" >
                                    <input type="hidden" readonly="" class="form-control displayonly" value="" id="cost{$int}">
                                    {if !empty($couponlist)}
                                        <div class="row">
                                            <div class="col-md-3"><strong>Coupon discount</strong></div>
                                            <div class="col-md-9">
                                                <input type="text"  name="coupon_discount"  id="coupon_discount" readonly="" value="00.00" class="form-control col-md-2 displayonly" >
                                            </div>
                                        </div>
                                    {/if}
                                    <div class="row" {if $info.tax==0}style="display: none;"{/if}>
                                        <div class="col-md-3"><strong>{$info.tax_text} ({$info.tax} %)</strong></div>
                                        <div class="col-md-9">
                                            <input type="text" min="1"  name="service_tax"  id="service_tax" readonly="" value="00.00" class="form-control col-md-2 displayonly" >
                                            <input type="hidden" name="tax" id="tax"  value="{$info.tax}">
                                        </div>
                                    </div>
                                    {if {$package.0.is_flexible==0}}
                                        <div class="row">
                                            <div class="col-md-3"><strong>Grand total</strong></div>
                                            <div class="col-md-9">
                                                <i class="fa fa-rupee"></i>&nbsp;&nbsp;&nbsp; <span id="total_display"> 00</span>
                                                <input style="display: none;" type="text" min="1"  name="grand_total"  id="grand_total" readonly="" value="00.00" class="form-control col-md-2 displayonly" >
                                            </div>
                                        </div>
                                    {/if}
                                    {if $has_terms==1}
                                        <div class="row">
                                            <div class="col-md-12">
                                                <br>
                                                <div class="panel-group accordion scrollable" id="accordion2">
                                                    <div class="panel panel-default">
                                                        <div class="panel-heading">
                                                            <h4 class="panel-title">
                                                                <a class="accordion-toggle" style="font-size: 14px;line-height: 2;    padding: 2px 10px; text-decoration:  none;"  data-toggle="collapse" data-parent="#accordion2" href="#collapse_2_2">
                                                                    <i class="fa fa-angle-down"></i> &nbsp;&nbsp;Terms and conditions & Cancellation policy  

                                                                </a>

                                                            </h4>
                                                        </div>
                                                        <div id="collapse_2_2" class="panel-collapse collapse">
                                                            <div class="panel-body">
                                                                {if $terms!=''}
                                                                    <h4><b>Terms & Conditions</b></h4>
                                                                    <p> {$terms}</p>
                                                                {/if}
                                                                {if $privacy!=''}
                                                                    <h4><b>Cancellation Policy</b></h4>
                                                                    <p> {$privacy}</p>
                                                                {/if}
                                                            </div>
                                                        </div>
                                                    </div>


                                                </div>
                                            </div>
                                        </div>
                                    {/if}
                                    <div class="row">
                                        {if $is_valid=='YES'}
                                            <br>

                                            {if !empty($couponlist)}
                                                <br>
                                                <div class="col-md-9">
                                                    <label class="font-blue" id="coupon_status">&nbsp;</label>

                                                </div>
                                                <div class="col-md-3">
                                                    <div class="input-group">
                                                        <input type="text" id="coupon_code" placeholder="Enter coupon code" class="form-control" value="" />
                                                        <input type="hidden" class="displayonly" id="coupon_id" name="coupon_id" value="{$coupon_details.coupon_id}"/>
                                                        <span class="input-group-btn">
                                                            <button onclick="return validateCoupon('{$info.merchant_user_id}');" class="btn green">Apply</button>
                                                        </span>
                                                    </div>
                                                    <br>
                                                </div>
                                                <div class="col-md-9">
                                                {else}
                                                    <div class="col-md-3">
                                                    {/if}
                                                    <table class="table" style="background-color: #fcf8e3;">
                                                        <tbody>
                                                            {foreach from=$couponlist item=v}
                                                            <input type="hidden" id="c_package_id{$v.coupon_id}" value="{$v.package_id}"/>
                                                            <input type="hidden" id="c_type{$v.coupon_id}" value="{$v.type}"/>
                                                            <input type="hidden" id="c_percent{$v.coupon_id}" value="{$v.percent}"/>
                                                            <input type="hidden" id="c_fixed_amount{$v.coupon_id}" value="{$v.fixed_amount}"/>
                                                        {/foreach}
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="col-md-3 center">
                                                    {if {$package.0.min_seat}>{$package.0.available} && {$package.0.seats_available}!=0}
                                                        <span  class="bg-red red hidden-print margin-bottom-5 col-md-12 ">
                                                            No seats available
                                                        </span>
                                                    {else}
                                                        {if $is_payment==TRUE}
                                                            <input type="submit" onclick="return seatcalculate();" class="btn blue hidden-print margin-bottom-5 col-md-12" value="Book now">
                                                        {/if}
                                                    {/if}
                                                    <input type="hidden"  value="{$int}" id="package{$package.0.package_id}">

                                                </div>
                                            {/if}
                                        </div>
                                </form>
                                <br>

                            </div>


                        </div>

                    </div>
                    {if isset($promotion)}
                        <div class="row no-margin" style="margin-top: 5px;">
                            <div class="col-md-12 no-margin no-padding" style="text-align: left;" >
                                <div class="alert alert-success" style="margin-bottom: 0px;">
                                    <p>
                                        {$promotion} 
                                    </p>
                                </div>
                            </div>
                        </div>
                    {/if}
                    <div class="row no-margin" style="margin-top: 5px;">
                        <div class="col-md-12 no-margin no-padding" style="text-align: left;">
                            <div class="alert alert-success">
                                <p>
                                    <b>If you would like to collect online payments for your business, <a target="_BLANK" href="/merchant/register">register now</a> on Swipez.</b>
                                </p>
                            </div>
                        </div>
                    </div>
                    <p> <a target="_BLANK" href="https://www.swipez.in"><img src="/assets/admin/layout/img/logo.png" class="img-responsive pull-right powerbyimg" alt=""/><span class="powerbytxt">powered by</span></a></p>
                </div>
                <div class="col-md-1"></div>
            </div>
        </div>
    </div>

    <div class="modal fade bs-modal-lg" id="guestpay" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" id="closeguest" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    <div class="portlet ">
                        <div class="portlet-title">
                            <div class="caption">
                                Guest payment
                            </div>

                        </div>
                        <div class="portlet-body">
                            <div class="row">

                                <div class="col-md-6">
                                    <h4>Book as guest</h4>
                                    <form class="form-horizontal" id="guestlogin" action="/patron/event/pay/{$url}" method="post" role="form">
                                        {if $is_flexible==0}
                                            <div class="form-group">
                                                <label for="inputEmail1" class="col-md-3 control-label">Grand total:</label>
                                                <div class="col-md-7">
                                                    <b><h4 id="booking_amount"><i class="fa fa-inr fa-large"></i> {$info.price} /-</h4></b>
                                                </div>
                                            </div>
                                        {/if}
                                        <div class="form-group">
                                            <label for="inputEmail1" class="col-md-3 control-label"></label>
                                            <div class="col-md-7">
                                                <button type="button" class="btn blue" onclick="document.getElementById('event_form').submit();" id="onlinepay" >Book as guest</button>

                                            </div>
                                        </div>
                                    </form>		
                                </div>
                                <div class="col-md-6">
                                    <h4>Login with Swipez</h4>
                                    <form class="form-horizontal" id="guestlogin" action="/login/failed/{$url}|eonl" method="post" role="form">
                                        <div class="form-group">
                                            <label for="inputEmail1" class="col-md-4 control-label">Email</label>
                                            <div class="col-md-7">
                                                <input type="email" name="username" class="form-control input-sm" id="inputEmail1" placeholder="Email">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputPassword12" class="col-md-4 control-label">Password</label>
                                            <div class="col-md-7">
                                                <input type="password" name="password" AUTOCOMPLETE='OFF' class="form-control input-sm" id="inputPassword12" placeholder="Password">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-4"></div>
                                            <label for="inputPassword12" class="col-md-4 control-label"></label>
                                            <div class="col-md-3">
                                                <button type="submit" class="btn blue">Sign in</button>
                                            </div>
                                        </div>
                                    </form>		
                                </div>
                            </div>
                            <hr>
                            <h4>Benefits of swipez login</h4>
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="col-md-2"> <img src="/assets/admin/layout/img/single.jpg"></div><div class="col-md-10"><label>
                                            Single window for paying 
                                            multiple merchants
                                        </label></div>

                                    <div class="col-md-2"> <img src="/assets/admin/layout/img/instant.jpg"></div><div class="col-md-10"><label>
                                            Instant alerts, due date 
                                            reminders across merchants
                                        </label></div>
                                </div>
                                <div class="col-md-5">
                                    <div class="col-md-2"> <img src="/assets/admin/layout/img/access.jpg"></div><div class="col-md-10"><label>
                                            Access your payments across 
                                            devices from the cloud
                                        </label></div>

                                    <div class="col-md-2"> <img src="/assets/admin/layout/img/reward.jpg"></div><div class="col-md-10"><label>
                                            Reward programs and much
                                            more coming your way
                                        </label></div>
                                </div>
                                <div class="col-md-2">
                                    <br><br>
                                    <div class="form-group">
                                        <a href="/patron/register/index/{$url}" class="btn blue">Join now</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>
        <!-- /.modal-content -->
    </div>
    <form action="" id="occurence_form" method="post">
        <input type="hidden" value="" id="occurence_id" name="occurence_id" >
    </form>
    <a  href="#guestpay"  data-toggle="modal" id="guest"></a>                         

    <script>
        {if $package.0.max_seat==1}
        calculateevent(1);
        {/if}
        function seatcalculate()
        {

        {if $package.0.is_flexible==0}
            booking_amount_total = document.getElementById('grand_total').value;
            totalcostamt = document.getElementById('totalcostamt').value;

            if (totalcostamt > 0)
            {
                return true;
                //document.getElementById('booking_amount').innerHTML = '<i class="fa fa-inr fa-large"></i>  ' + booking_amount_total + ' /-';
                //document.getElementById('guest').click();
            } else
            {
                alert('Select Quantity');
                return false;
            }
        {else}
            document.getElementById('guest').click();
            document.getElementById('booking_amount').innerHTML = '<i class="fa fa-inr fa-large"></i> {$package.0.min_price} - {$package.0.max_price} ';
        {/if}

        }
    </script>