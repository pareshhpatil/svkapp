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
                            <form autocomplete="off" id="event_form" action="/patron/event/pay/{$url}" method="post">
                                {if $info.venue!=''}
                                    <div class="row">
                                        <div class="col-md-3"><strong>Venue</strong></div>
                                        <div class="col-md-9"><i class="fa fa-map-marker"></i>&nbsp;&nbsp;&nbsp;  {$info.venue}</div>
                                    </div>
                                {/if}

                                {if $info.description!=''}
                                    <div class="row" >
                                        <div class="col-md-3"><strong>Description</strong></div>
                                        <div class="col-md-9" >{$info.description}</div>
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
                                                <div class="col-md-3"><strong>{$v.column_name}:</strong></div>
                                                <div class="col-md-9">{$v.value}</div>
                                            </div>
                                        {/if}
                                    {/if}

                                {/foreach}
                                <div class="row" id="select">

                                    <div class="col-md-3"><strong>Select date</strong></div>
                                    <div class="col-md-4"> 
                                        {if count($occurence)>1}
                                            <select name="start_date" class="form-control select2me" onchange="document.getElementById('occurence_id').value = this.value;
                                                    $('#occurence_form').submit();" data-placeholder="Select...">
                                                <option selected value="">Select booking date </option>
                                                {foreach from=$occurence item=v}
                                                    {if $v.occurence_id=='season'}
                                                        {$datevalue={$v.start_date}}
                                                    {else}
                                                        {$datevalue={$v.start_date." ".$v.start_time|date_format:"%d-%b-%Y %H:%M:%S"}}
                                                    {/if}
                                                    {if $occurence_selected==$v.occurence_id}
                                                        <option selected value="{$v.occurence_id}">{$datevalue}</option>
                                                        {$selected_datevalue=$datevalue}
                                                    {else}
                                                        <option value="{$v.occurence_id}">{$datevalue}</option>
                                                    {/if}
                                                {/foreach}
                                            </select>
                                        {else}
                                            <input value="{$occurence.0.occurence_id}" name="start_date" type="hidden">
                                            <i class="fa fa-calendar"></i>&nbsp;&nbsp;&nbsp;{if $info.event_from_date==$info.event_to_date}{{$info.event_from_date}|date_format:"%d-%b-%Y"}{else}
                                            {{$info.event_from_date}|date_format:"%d-%b-%Y"} to {{$info.event_to_date}|date_format:"%d-%b-%Y"}
                                        {/if}
                                        {/if}
                                        </div>
                                    </div>
                                    {if $occurence_id>0}
                                        <br>

                                        <table class="table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th class="col-xs-7">
                                                        Package name
                                                    </th>
                                                    <th class="hidden-480 col-xs-3" style="min-width: 80px;">
                                                        Price
                                                    </th>
                                                    <th class="hidden-480 col-xs-2" style="min-width: 95px;">
                                                        {$display_booking}
                                                    </th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                {$int =1}
                                                {$total =0}
                                                {foreach from=$package item=v}
                                                    <tr> <td class="col-xs-7">
                                                            {$v.package_name} {if $v.package_type==1} <span class="font-blue"> - {$selected_datevalue}</span>{/if}
                                                            <p class="small">{$v.package_description}</p>
                                                        </td>
                                                        <td class="hidden-480 col-xs-3">
                                                            {if {$v.is_flexible==1}}
                                                                Min - {$v.min_price} Max - {$v.max_price}
                                                                <input type="hidden" name="is_flexible[]" value="1">
                                                            {else}
                                                                <input type="text" readonly="" class="form-control displayonly" value="{$v.price|string_format:"%d"}" id="unitprice{$int}">
                                                            {/if}

                                                        </td>
                                                        <td class="col-xs-2" >
                                                            {$max={$v.max_seat}}
                                                            {if $v.max_seat>$v.available && $v.seats_available!=0}
                                                                {$max={$v.available}}
                                                            {/if}
                                                            {if {$v.min_seat}>{$v.available} && {$v.seats_available}!=0}
                                                                Booking full
                                                                <input type="hidden" id="unitnumber{$int}" value="0" name="seat[]" >
                                                            {else}
                                                                {$is_available_seat=1}
                                                                <select name="seat[]" class="form-control" id="unitnumber{$int}" onchange="calculateevent({$int});" data-placeholder="Select...">
                                                                    <option value="0">0</option>
                                                                    {for $foo=$v.min_seat to $max}
                                                                        <option value="{$foo}">{$foo}</option>
                                                                    {/for}
                                                                </select>
                                                            {/if}
                                                            <input type="hidden" value="{$v.package_id}" name="package_id[]" >
                                                            <input type="hidden" readonly="" class="form-control displayonly" value="" id="cost{$int}">
                                                            <input type="hidden"  value="{$int}" id="package{$v.package_id}">
                                                        </td>

                                                    </tr>
                                                    {$total = $total + $v.price}
                                                    {$int = $int + 1}
                                                {/foreach}

                                                <tr> <td class="col-xs-7">
                                                        Total:
                                                    </td>
                                                    <td class="hidden-480 col-xs-3">

                                                    </td>
                                                    <td class="hidden-480 col-xs-2">
                                                        <input type="text" min="1"  name="total"  id="totalcostamt" readonly="" value="" class="form-control col-md-2 displayonly" >
                                                    </td>

                                                </tr>
                                                <tr {if $info.tax==0}style="display: none;"{/if}> <td class="col-xs-7">
                                                        {$info.tax_text} ({$info.tax}%):
                                                    </td>
                                                    <td class="hidden-480 col-xs-3">

                                                    </td>
                                                    <td class="hidden-480 col-xs-2">
                                                        <input type="text" min="1"  name="service_tax"  id="service_tax" readonly="" value="" class="form-control col-md-2 displayonly" >
                                                        <input type="hidden" name="tax" id="tax"  value="{$info.tax}">
                                                    </td>

                                                </tr>
                                                <tr  {if empty($couponlist)}style="display: none;"{/if}> <td class="col-xs-7">
                                                        Coupon discount:
                                                    </td>
                                                    <td class="hidden-480 col-xs-3">

                                                    </td>
                                                    <td class="hidden-480 col-xs-3">
                                                        <input type="text"  name="coupon_discount"  id="coupon_discount" readonly="" value="" class="form-control col-md-2 displayonly" >
                                                    </td>

                                                </tr>
                                                <tr> <td class="col-xs-7">
                                                        Grand total:
                                                    </td>
                                                    <td class="hidden-480 col-xs-3">

                                                    </td>
                                                    <td class="hidden-480 col-xs-2">
                                                        <input type="text" min="1"  name="grand_total"  id="grand_total" readonly="" value="" class="form-control col-md-2 displayonly" >
                                                    </td>

                                                </tr>
                                            </tbody>
                                        </table>

                                        {if $has_terms==1}
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <br>
                                                    <div class="panel-group accordion scrollable" id="accordion2">
                                                        <div class="panel panel-default">
                                                            <div class="panel-heading">
                                                                <h4 class="panel-title">
                                                                    <a class="accordion-toggle"  style="font-size: 14px;line-height: 2;    padding: 2px 10px; text-decoration:  none;" 
                                                                       data-toggle="collapse"  href="#collapse_2_2" >
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

                                        {if $is_valid=='YES' && $is_available_seat==1}
                                            {if !empty($couponlist)}
                                                <br>
                                                <div class="col-md-9">
                                                    <label class="font-blue" id="coupon_status">&nbsp;</label>

                                                </div>
                                                <div class="col-md-3">
                                                    <div class="row">
                                                        <div class="input-group">
                                                            <input type="text" id="coupon_code" placeholder="Enter coupon code" class="form-control" value="" />
                                                            <input type="hidden" class="displayonly" id="coupon_id" name="coupon_id" value="{$coupon_details.coupon_id}"/>
                                                            <span class="input-group-btn">
                                                                <button onclick="return validateCoupon('{$info.merchant_user_id}');" class="btn green">Apply</button>
                                                            </span>
                                                        </div>
                                                        <br>
                                                    </div>
                                                </div>
                                            {/if}

                                            <div class="col-md-9">
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
                                            <div class="col-md-3 ">
                                                {if $is_payment==TRUE}
                                                    <div class="row center" >
                                                        <input type="submit" onclick="return seatcalculate();"  class="btn blue hidden-print margin-bottom-5 col-md-12" value="Book now">
                                                    </div>
                                                {/if}
                                            </div>
                                            <br>
                                        {else}
                                            <p class="btn btn-xs red pull-right">Book now option not available</p><br>
                                        {/if}
                                    {/if}
                                </form>
                                <br>
                                <br>
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

                    <!-- END PAGE CONTENT-->
                </div>
                <div class="col-md-1"></div>
            </div>
        </div>
    </div>
    
    <form action="" id="occurence_form" method="post">
        <input type="hidden" value="" id="occurence_id" name="occurence_id" >
    </form>
    <a  href="#guestpay"  data-toggle="modal" id="guest"></a>                         
    <a  href="#select" class="go2top" id="dateclick"></a>                         

    <script>
        
    {if $occurence_selected!=''}
        document.getElementById('dateclick').click();
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