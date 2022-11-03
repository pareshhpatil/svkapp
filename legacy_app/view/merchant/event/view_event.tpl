<div class="page-content">
    <div class="page-bar">
        {include file="../../common/breadcumbs.tpl" title={$title} links=$links}
    </div>
    <div class="row">
        <div class="col-md-1"></div>
        <div class=" col-md-9" style="text-align: -webkit-center;text-align: -moz-center;">
            {if $is_success=='True'}
                <div class="row max900" style="text-align: left;">
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
                <div class="alert alert-danger max900" style="text-align: left;">
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
            <div style="text-align: left;box-shadow: 1px 10px 10px #888888;">
                <div class="row">
                    {if $info.banner_path!=''}
                        <div class="col-md-12 fileinput fileinput-new" style="text-align: center;"
                            data-provides="fileinput">
                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                <div class="fileinput-preview thumbnail banner-container" data-trigger="fileinput">
                                    <img class="img-responsive" style="width: 100%;"
                                        src="{if $info.banner_path!=''}/uploads/images/logos/{$info.banner_path}{else}holder.js/100%x100%{/if}">
                                </div>
                            </div>
                        </div>

                    {/if}


                </div>
                <div class="row">
                    <div class="col-md-12  " style="width: 100%;" data-provides="fileinput">
                        <div class="fileinput-new thumbnail col-md-12 title">
                            <div class="caption font-blue" style="background-color: #275770;">
                                <span class="caption-subject bold" style="color: #FFFFFF;text-align:center;">
                                    <h4>{$info.event_name}</h4>
                                </span>
                                <p style="color: #FFFFFF;text-align:center;">Presented by <a href="{$merchant_page}"
                                        style="color: #FFFFFF;" target="BLANK">{$info.company_name}</a></p>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row no-margin no-padding" style="">

                    <div class="col-md-1"></div>
                    <div class="col-md-10 invoice-payment">
                        <form id="event_form" action="/merchant/event/seatbooking/{$url}" method="post">


                            {if $info.venue!=''}
                                <div class="row">
                                    <div class="col-md-3"><strong>Venue</strong></div>
                                    <div class="col-md-9"><i class="fa fa-map-marker"></i>&nbsp;&nbsp;&nbsp; {$info.venue}
                                    </div>
                                </div>
                            {/if}

                            {if $info.description!=''}
                                <div class="row">
                                    <div class="col-md-3"><strong>Description</strong></div>
                                    <div class="col-md-9">{$info.description}</div>
                                </div>
                            {/if}

                            {foreach from=$header item=v}
                                {if $v.value!='' && $v.column_position!=1 &&  $v.column_position!=2}
                                    <div class="row">
                                        <div class="col-md-3"><strong>{$v.column_name}:</strong></div>
                                        <div class="col-md-9">{$v.value}</div>
                                    </div>
                                {/if}
                            {/foreach}

                            <div class="row">

                                <div class="col-md-3"><strong>Select date</strong></div>
                                <div class="col-md-5">
                                    {if count($occurence)>1}
                                        <select id="occurence" name="start_date" class="form-control"
                                            onchange="occurenceSubmit();" data-placeholder="Select...">
                                            {foreach from=$occurence item=oc}
                                                {if $oc.start_date==$oc.end_date}
                                                    {if $oc.start_time==$oc.end_time}
                                                        {$datevalue={$oc.start_date|date_format:"%d %b %Y"}}
                                                    {else}
                                                        {$from_date={$oc.start_date|cat:" "|cat:$oc.start_time}|date_format:"%d %b %Y
                                        %I:%M %p"}
                                                        {$to_date={$oc.end_time|date_format:"%I:%M %p"}}
                                                        {$datevalue=$from_date|cat:" To "|cat:$to_date}
                                                    {/if}
                                                {else}
                                                    {if $oc.start_time==$oc.end_time}
                                                        {$from_date={$oc.start_date|date_format:"%d %b %Y"}}
                                                        {$to_date={$oc.end_date|date_format:"%d %b %Y"}}
                                                        {$datevalue=$from_date|cat:" To "|cat:$to_date}
                                                    {else}
                                                        {$from_date={$oc.start_date|cat:" "|cat:$oc.start_time}|date_format:"%d %b %Y
                                        %I:%M %p"}
                                                        {$to_date={$oc.end_date|cat:" "|cat:$oc.end_time}|date_format:"%d %b %Y %I:%M
                                        %p"}
                                                        {$datevalue=$from_date|cat:" To "|cat:$to_date}
                                                    {/if}
                                                {/if}

                                                {if $occurence_id==$oc.occurence_id}
                                                    <option selected value="{$oc.occurence_id}">{$datevalue}</option>
                                                {else}
                                                    <option value="{$oc.occurence_id}">{$datevalue}</option>
                                                {/if}
                                            {/foreach}
                                        </select>
                                    {else}
                                        <input value="{$occurence.0.occurence_id}" id="occurence" name="start_date"
                                            type="hidden">
                                        <i
                                            class="fa fa-calendar"></i>&nbsp;&nbsp;&nbsp;{if $info.event_from_date==$info.event_to_date}{{$info.event_from_date}|date_format:"%d-%b-%Y"}
                                        {else}
                                            {{$info.event_from_date}|date_format:"%d-%b-%Y"} to
                                            {{$info.event_to_date}|date_format:"%d-%b-%Y"}
                                        {/if}
                                    {/if}
                                </div>
                            </div>
                            {if count($currency)>1}
                                <div class="row">
                                    <div class="col-md-3"><strong>Select currency</strong></div>
                                    <div class="col-md-5">
                                        <select class="form-control" onchange="occurenceSubmit();" id="currency_id"
                                            name="currency" style="width: 365px">
                                            <option>Select Currency</option>
                                            {foreach from=$currency item=c}
                                                {if $currency_id==$c}
                                                    <option selected value="{$c}">{$c}</option>
                                                {else}
                                                    <option value="{$c}">{$c}</option>
                                                {/if}
                                            {/foreach}
                                        </select>
                                        </center>
                                    </div>
                                </div>
                    {else}
                        <input value="{$currency.0}" id="currency_id" name="currency" type="hidden">
                    {/if}
                    <div class="row">
                        <div class="col-xs-3"><strong>Post link:</strong></div>
                        <div class="col-xs-9"><a href="{$link}">{$link}</a></div>
                    </div>
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
                                    {$info.unit_type}
                                </th>

                            </tr>
                        </thead>
                        <tbody>
                            {$int =1}
                            {$total =0}
                            {foreach from=$package item=v}
                                <tr>
                                    <td class="col-xs-7">
                                        {$v.package_name}
                                        <p class="small">{$v.package_description}</p>
                                    </td>
                                    <td class="hidden-480 col-xs-3">
                                        {if {$v.is_flexible==1}}
                                            Min - {$v.min_price} Max - {$v.max_price}
                                            <input type="hidden" name="is_flexible[]" value="1">
                                        {else}
                                            <input type="text" readonly="" class="form-control displayonly"
                                                value="{$v.price|string_format:"%d"}" id="unitprice{$int}">
                                        {/if}

                                    </td>
                                    <td class="col-xs-2">
                                        {if $v.sold_out==1}
                                            <b class="red">Sold Out</b>
                                        {else}
                                            <select name="package_qty[]" class="form-control" id="unitnumber{$int}"
                                                onchange="calculateevent({$int});" data-placeholder="Select...">
                                                <option value="0">0</option>
                                                {if $v.max_seat>$v.available && $v.seats_available!=0}
                                                    {$v.max_seat=$v.available}
                                                {/if}
                                                {for $foo=$v.min_seat to $v.max_seat}
                                                    <option value="{$foo}">{$foo}</option>
                                                {/for}
                                            </select>
                                        {/if}
                                        <input type="hidden" readonly="" class="form-control displayonly" value=""
                                            id="cost{$int}">
                                        <input type="hidden" value="{$int}" id="package{$v.package_id}">
                                        <input type="hidden" value="{$v.package_id}" name="package_id[]">
                                        <input type="hidden" value="{$v.occurence_id}" name="occurence_id[]">
                                    </td>

                                </tr>
                                {$total = $total + $v.price}
                                {$int = $int + 1}
                            {/foreach}

                            <tr>
                                <td class="col-xs-7">
                                    Total ({$currency_icon}):
                                </td>
                                <td class="hidden-480 col-xs-3">

                                </td>
                                <td class="hidden-480 col-xs-2">
                                    <input type="text" min="1" name="total" id="totalcostamt" readonly="" value=""
                                        class="form-control col-md-2 displayonly">
                                </td>

                            </tr>
                            <tr {if $info.tax==0}style="display: none;" {/if}>
                                <td class="col-xs-7">
                                    {$info.tax_text} ({$info.tax}%):
                                </td>
                                <td class="hidden-480 col-xs-3">

                                </td>
                                <td class="hidden-480 col-xs-2">
                                    <input type="text" min="1" name="service_tax" id="service_tax" readonly="" value=""
                                        class="form-control col-md-2 displayonly">
                                    <input type="hidden" name="tax" id="tax" value="{$info.tax}">
                                </td>

                            </tr>
                            <tr {if empty($couponlist)}style="display: none;" {/if}>
                                <td class="col-xs-7">
                                    Coupon discount:
                                </td>
                                <td class="hidden-480 col-xs-3">

                                </td>
                                <td class="hidden-480 col-xs-3">
                                    <input type="text" name="coupon_discount" id="coupon_discount" readonly="" value=""
                                        class="form-control col-md-2 displayonly">
                                </td>

                            </tr>
                            <tr>
                                <td class="col-xs-7">
                                    Grand total ({$currency_icon}):
                                </td>
                                <td class="hidden-480 col-xs-3">

                                </td>
                                <td class="hidden-480 col-xs-2">
                                    <input type="text" min="1" name="grand_total" id="grand_total" readonly="" value=""
                                        class="form-control col-md-2 displayonly">
                                </td>

                            </tr>
                        </tbody>
                    </table>


                    {if $is_valid=='YES'}
                        <div class="col-md-9">
                            <table class="table" style="background-color: #fcf8e3;">
                                <tbody>
                                    {foreach from=$couponlist item=v}
                                        <tr>
                                            <td>
                                                <input type="hidden" id="c_package_id{$v.coupon_id}" value="{$v.package_id}" />
                                                <input type="hidden" id="c_type{$v.coupon_id}" value="{$v.type}" />
                                                <input type="hidden" id="c_percent{$v.coupon_id}" value="{$v.percent}" />
                                                <input type="hidden" id="c_fixed_amount{$v.coupon_id}"
                                                    value="{$v.fixed_amount}" />
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
                                    <select name="coupon_id" id="coupon_id" onchange="calculateevent(null);"
                                        class="form-control select2me" data-placeholder="Apply coupon">
                                        <option value=""> </option>
                                        {foreach from=$couponlist item=v}
                                            <option value="{$v.coupon_id}">{$v.coupon_code} </option>
                                        {/foreach}
                                    </select>

                                </div><br>
                            {/if}
                            <div class="row">
                                <input type="submit" name="submit" onclick="return seatcalculate();"
                                    class="btn blue hidden-print margin-bottom-5 pull-right col-md-12" value="Book now" />
                            </div>
                        </div>
                    {else}
                        <p class="btn btn-xs red pull-right">Book now option not available</p><br>
                    {/if}

                    </form>
                    <br>
                    <br>
                    <br>
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
    <input type="hidden" value="" id="occurence_id" name="occurence_id">
    <input type="hidden" value="" id="currency" name="currency_id">
</form>



<script>
    function seatcalculate() {

        {if $package.0.is_flexible==0}
            booking_amount_total = document.getElementById('grand_total').value;
            totalcostamt = document.getElementById('totalcostamt').value;

            if (totalcostamt > 0) {
                return true;

            } else {
                alert('Select Quantity');
                return false;
            }

        {/if}

    }
</script>