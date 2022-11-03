
<div class="page-content">

    <div class="row">
        <div class="col-md-1"></div>
        <div class=" col-md-9" style="text-align: -webkit-center;text-align: -moz-center;">

            {if $is_success=='True'}
                <div class="row max900" style="text-align: left;" >
                    <div class="col-md-12">
                        <div class="alert alert-block alert-success fade in">
                            <button type="button" class="close" data-dismiss="alert"></button>
                            <h4 class="alert-heading">Event Created / Modified</h4>
                            <p>
                                Event has been saved. You can share link to your patrons.
                            </p>
                            <p>
                                <a class="btn blue input-sm" href="/merchant/event/update/{$url}">
                                     Update event </a>
                                <a class="btn blue input-sm" href="/merchant/dashboard">
                                     Dashboard </a>
                            </p>
                        </div>
                    </div>

                </div>
            {/if}

            {if isset($haserrors)}
                <div class="alert alert-danger max900" style="text-align: left;">
                    <button type="button" class="close" data-dismiss="alert"></button>
                    <strong>Error!</strong>
                    <div class="media">
                        {foreach from=$haserrors item=v}

                            <p class="media-heading">{$v.0} - {$v.1}.</p>
                        {/foreach}
                    </div>

                </div>
            {/if}
            {if $is_valid=='NO'}
                <div class="alert alert-danger max900"  style="text-align: left;">
                    <button type="button" class="close" data-dismiss="alert"></button>
                    <strong>Invalid event!</strong>
                    <div class="media">
                        <p class="media-heading">{$invalid_message}</p>
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


                <div class="row" style="">

                    <div class="col-md-1"></div>
                    <div class="col-md-10 invoice-payment">
                        <form action="/merchant/event/seatbooking/{$url}" method="post">


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

                                {foreach from=$header item=v}
                                    {if $v.value!='' && $v.column_position!=1 &&  $v.column_position!=2}
                                        <div class="row">
                                            <div class="col-md-3"><strong>{$v.column_name}</strong></div>
                                            <div class="col-md-9">{$v.value}</div>
                                        </div>
                                    {/if}
                                {/foreach}
                                {$int=1}

                                <div class="row">
                                    <div class="col-md-3"><strong>Seat price</strong></div>
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
                                                {if {$package.0.max_seat}>{$package.0.available} && {$package.0.seats_available!=0}}
                                                    {$package.0.max_seat=$package.0.available}
                                                {/if}
                                                {for $foo=$package.0.min_seat to $package.0.max_seat}
                                                    <option value="{$foo}">{$foo}</option>
                                                {/for}
                                            </select>
                                        </div>

                                    </div>
                                {/if}


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
                                    <div class="col-md-3"><strong>{$info.tax_text}({$info.tax} %)</strong></div>
                                    <div class="col-md-9">
                                        <input type="text" min="1"  name="service_tax"  id="service_tax" readonly="" value="00.00" class="form-control col-md-2 displayonly" >
                                        <input type="hidden" name="tax" id="tax"  value="{$info.tax}">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3"><strong>Grand total</strong></div>
                                    <div class="col-md-9">
                                        <i class="fa fa-rupee"></i>&nbsp;&nbsp;&nbsp; <span id="total_display"> 00</span>
                                        <input style="display: none;" type="text" min="1"  name="grand_total"  id="grand_total" readonly="" value="00.00" class="form-control col-md-2 displayonly" >
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3"><strong>Post link</strong></div>
                                    <div class="col-md-9"><a href="{$link}" >{$link}</a>
                                    </div>
                                </div>

                                <div class="row">
                                    {if $is_valid=='YES'}
                                        <br>
                                        <div class="col-md-9">
                                            <table class="table" style="background-color: #fcf8e3;">
                                                <tbody>
                                                    {foreach from=$couponlist item=v}
                                                        <tr> <td>
                                                                <input type="hidden" id="c_package_id{$v.coupon_id}" value="{$v.package_id}"/>
                                                                <input type="hidden" id="c_type{$v.coupon_id}" value="{$v.type}"/>
                                                                <input type="hidden" id="c_percent{$v.coupon_id}" value="{$v.percent}"/>
                                                                <input type="hidden" id="c_fixed_amount{$v.coupon_id}" value="{$v.fixed_amount}"/>
                                                                Coupon code : {$v.coupon_code} (Apply for {$v.package_name})
                                                                <p class="small">{$v.descreption}</p>
                                                            </td>
                                                        </tr>
                                                    {/foreach}
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-md-3 ">
                                            {if !empty($couponlist)}
                                                <div class="row">
                                                    <select name="coupon_id" id="coupon_id" onchange="calculateevent(null);" class="form-control select2me" data-placeholder="Apply coupon">
                                                        <option value=""> </option>
                                                        {foreach from=$couponlist item=v}
                                                            <option value="{$v.coupon_id}">{$v.coupon_code} </option>
                                                        {/foreach}
                                                    </select>

                                                </div><br>
                                            {/if}
                                            <div class="row">
                                                {if {$package.0.max_seat}>{$package.0.available} && {$package.0.seats_available}!=0}
                                                    <span  class="bg-red red hidden-print margin-bottom-5 col-md-12 ">
                                                        No seats available
                                                    </span>
                                                {else}
                                                    <input type="submit" name="submit" onclick="return seatcalculate();" class="btn blue hidden-print margin-bottom-5 pull-right col-md-12" value="Book now" />
                                                {/if}
                                                <input type="hidden"  value="{$int}" id="package{$package.0.package_id}">
                                            </div>
                                        </div>
                                    {/if}
                                </div>

                            </form>
                            <br>
                        </div>
                    </div>
                </div>
                <br>
                <!-- END PAGE CONTENT-->
            </div>

            <div class="col-md-1"></div>
        </div>
    </div>
</div>
<form action="" id="occurence_form" method="post">
    <input type="hidden" value="" id="occurence_id" name="occurence_id" >
</form>



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

                }
                else
        {
                    alert('Select Quantity');
                    return false;
                }

    {/if}

            }
</script>