<!-- BEGIN CONTENT -->
<div class="page-content" style="min-height: 858px;">
    <!-- BEGIN PAGE HEADER-->
    <div class="page-bar">
        {include file="../../common/breadcumbs.tpl" title={$title} links=$links}
    </div>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row" style="background-color: #f5f5f5;padding-top: 20px;padding-bottom: 20px;margin-top: -12px;">
        <div class="col-md-1">
        </div>
        <div class="col-md-11">
            <div class="row">
                <form action="" method="post">
                    <div class="col-md-3">
                        <div class="input-icon input-icon-lg">
                            <i><img src="/assets/admin/layout/img/sporticon.png" style="vertical-align: top;"></i>
                            <select name="category_id" required onchange="setMerchantDays(this.value, '');
                                        getCategoryCalendar();" id="category_id" class="form-control input-lg"
                                data-placeholder="Select Date...">
                                <option value="">Select Activity</option>
                                {foreach from=$category_list item=v}
                                    {if $category_id==$v.category_id}
                                        <option selected value="{$v.category_id}"> {$v.category_name}</option>
                                        {$category_name=$v.category_name}
                                    {else}
                                        <option value="{$v.category_id}"> {$v.category_name}</option>
                                    {/if}
                                {/foreach}
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="input-icon input-icon-lg">
                            <i class="fa fa-calendar" style="color:#3578b4;"> </i>
                            <input type="text" onchange="getCategoryCalendar();"
                                style="background-color: #ffffff;cursor: pointer;" name="booking_date" readonly
                                id="date" placeholder="Select date" class="form-control input-lg" autocomplete="off"
                                data-date-format="dd M yyyy">
                        </div>

                    </div>
                    <div class="col-md-2">
                        <div class="input-icon input-icon-lg">
                            <i class="fa fa-clock-o" style="color:#3578b4;"> </i>
                            <select name="calendar_id" required id="court" class="form-control input-lg"
                                data-placeholder="Select Court..." onchange="getPackagesCalendar()">
                                <option value="">Select Court...</option>
                                {foreach from=$court_list item=v}
                                    {if $calendar_id==$v.calendar_id}
                                        <option selected value="{$v.calendar_id}"> {$v.calendar_title}</option>
                                        {$court_name=$v.calendar_title}
                                    {else}
                                        <option value="{$v.calendar_id}"> {$v.calendar_title}</option>
                                    {/if}
                                {/foreach}
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-icon input-icon-lg">
                            <i class="fa fa-clock-o" style="color:#3578b4;"> </i>
                            <select name="package_id" required id="packages" class="form-control input-lg"
                                data-placeholder="Select packages...">
                                <option value="">Select packages...</option>
                                {foreach from=$packages item=v}
                                    {if $package_id==$v.package_id}
                                        <option selected value="{$v.package_id}"> {$v.package_name}</option>
                                        {$court_name=$v.package_name}
                                    {else}
                                        <option value="{$v.package_id}"> {$v.package_name}</option>
                                    {/if}
                                {/foreach}
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <input type="hidden" id="merchant_id" value="{$merchant_id}">
                        <button type="submit" class="btn blue input-lg">&nbsp;&nbsp;<i class="fa fa-search"></i>&nbsp;
                            Search&nbsp;&nbsp;</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {if !empty($slots)}
        <div class="row">
            <div id="max_booking_div" class="alert alert-danger display-none">
                <span id="max_booking_error"></span>
            </div>
            <form action="/merchant/bookings/slotbooking" onsubmit="return validateBooking();" name="frm_slot"
                method="post">
                <div class="col-md-1"></div>
                <div class="col-md-8">
                    <div class="row">
                        <h3 class="booking-title" style="color: #18aebf;">{$category_name} - {$court_name}</h3>
                        <p class="booking-info">Showing all avaliable slots</p>
                    </div>

                    <div class="row">
                        <div id="max_booking_div" class="alert alert-danger display-none">
                            <span id="max_booking_error"></span>
                        </div>
                        <div class="col-lg-8 no-padding booking-court-div"
                            style="box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.5);margin-bottom: 20px;">
                            <table class="table table-condensed table-bordered " style="margin-bottom: 0px;">
                                <tr style="font-weight: bold;">
                                    <th colspan="2" class="booking-slot-header">
                                        <label onclick="dateChange('{$last_date}');"
                                            style="background-color: transparent;border: 0;cursor: pointer;"><i
                                                class="fa fa-chevron-left"></i></label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        {$current_date|date_format:"%A, %B %e, %Y"} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label
                                            onclick="dateChange('{$next_date}');"
                                            style="background-color: transparent;border: 0;cursor: pointer;"><i
                                                class="fa fa-chevron-right"></i></label>

                                    </th>
                                </tr>
                                <tbody>
                                    {if count($slots) < 1}
                                        <tr>
                                            <td style="padding: 20px 10px 20px 10px;" class="td-c">
                                                <div class="row" style="margin-left: 0px;">
                                                    <label class="booking-slot-text">No slots available for
                                                        {$package.0.package_name}</label>

                                                </div>
                                            </td>
                                        </tr>
                                    {else}
                                        {foreach from=$slots item=s}
                                            <tr>
                                                <td style="padding: 20px 10px 20px 10px;" class="td-c">
                                                    <div class="row" style="margin-left: 0px;">
                                                        <div class="pull-left">
                                                            <i class="fa fa-clock-o" style="font-size: 20px;">
                                                            </i>&nbsp;&nbsp;&nbsp;

                                                            <label class="booking-slot-text">{$s.time_from} To {$s.time_to}</label>
                                                        </div>

                                                    </div>

                                                    {foreach from=$s.slots_package_array item=sa}
                                                        <div class="row"
                                                            style="margin-bottom: 10px;width: 100%;margin-left: 0px;
                                                            margin-right: 0px; padding-top: 10px;padding-bottom: 10px;border: 1px solid #BEC6C6;border-radius: 10px;">
                                                            <div class="col-lg-3 col-md-3 col-sm-3">
                                                                <div class="pull-left">
                                                                    <h3 style="margin-top: 0px; margin-bottom: 5px;">{$sa.slot_title}
                                                                    </h3>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3">
                                                                <label class="booking-slot-price">{if $sa.slot_price>0}₹
                                                                        {$sa.slot_price}
                                                                    {else}
                                                                    {/if}
                                                                </label>
                                                            </div>
                                                            <div class="col-lg-6 col-md-6 col-sm-6">
                                                                {if $sa.is_multiple==1}
                                                                    {if $sa.total_seat>0 && $sa.max_seat>$sa.available_seat}
                                                                        {$sa.max_seat=$sa.available_seat}
                                                                        {if $sa.available_seat<1}
                                                                            {$sa.min_seat=0}
                                                                        {/if}
                                                                    {/if}
                                                                    {if $sa.min_seat>0}
                                                                        {if $sa.max_seat>1}
                                                                            <input type="number" onchange="return calculateSlot('{$sa.slot_id}');"
                                                                                name="booking_qty[]" class="form-control" min="{$sa.min_seat}"
                                                                                max="{$sa.max_seat}" value="{$sa.min_seat}"
                                                                                style="width: 50px;text-align: center; display:inline;">&nbsp;
                                                                        {else}
                                                                            <input type="hidden" name="booking_qty[]" value="1">
                                                                        {/if}

                                                                    {/if}
                                                                {else}
                                                                    <input type="hidden" name="booking_qty[]" value="1">
                                                                {/if}

                                                                {if $sa.min_seat==0 && $sa.is_multiple==1}
                                                                    <label class="btn tbn-sm grey pull-right" style="width: 80px;"
                                                                        id="slotbtn{$sa.slot_id}">
                                                                        <span id="slotbtntext{$sa.slot_id}">Full</span>
                                                                    </label>
                                                                {else}
                                                                    <label class="btn btn-sm blue pull-right" style="width: 80px;"
                                                                        id="slotbtn{$sa.slot_id}">
                                                                        <span id="slotbtntext{$sa.slot_id}">Book</span> <i
                                                                            class="fa fa-chevron-right"></i>
                                                                        <span style="display: none;">
                                                                            <input name="booking_slots[]" id="slotchk{$sa.slot_id}"
                                                                                onchange="return calculateSlot('{$sa.slot_id}');"
                                                                                title="{$sa.slot_price}" value="{$sa.slot_id}"
                                                                                class="checker" type="checkbox">
                                                                        </span>

                                                                    </label>
                                                                    <input type="hidden" name="booking_slot_id[]" id="slotid{$sa.slot_id}"
                                                                        value="0">
                                                                {/if}
                                                            </div>
                                                            <div class="row"
                                                                style="margin-bottom: 10px; margin-left: 0px;
                                                                        margin-right: 0px; padding-top: 10px;padding-bottom: 10px;">
                                                                <div class=" col-lg-12 col-md-12 col-sm-12">
                                                                    <div class="pull-left">
                                                                        <h6
                                                                            style="margin-top: 10px; margin-bottom: 0px; text-align: left;">
                                                                            {$sa.slot_description}</h6>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    {/foreach}
                                                </td>
                                            </tr>

                                        {/foreach}
                                    {/if}

                                </tbody>
                            </table>

                        </div>
                        <div class="col-lg-3 no-padding booking-confirm-div" style="margin-left: 30px;">
                            <div style="height: 3px;width: 100%;background-color: #18aebf;"></div>
                            <div style="padding: 20px;">
                                <label
                                    style="font-size: 16px; letter-spacing: -0.3px;text-align: left;color: #18aebf;">Summary</label>
                                <br>
                                <br>
                                <label class="booking-confirm-detail-label">Activity</label>
                                <br>
                                <label class="booking-confirm-detail-value">{$category_name}</label>
                                <br>
                                <br>
                                <label class="booking-confirm-detail-label">Date Selected</label>
                                <br>
                                <label
                                    class="booking-confirm-detail-value">{$current_date|date_format:"%A, %B %e, %Y"}</label>
                                <br>
                                <br>
                                <label class="booking-confirm-detail-label">Number of

                                    {if $s.is_multiple==1}Seats
                                    {else}slots
                                    {/if} Selected</label>
                                <br>
                                <label class="booking-confirm-detail-value" id="totalslot">0</label>
                                <div id="total_div" style="display: none;">
                                    <br>
                                    <br>
                                    <label class="booking-confirm-detail-label" style="font-size: 12px;">Total</label>
                                    <br>
                                    <label class="booking-confirm-detail-amount" id="absolute_costt"></label>
                                    <br>
                                </div>


                            </div>
                            <br>
                            <button type="submit" class="btn blue btn-lg" style="width: 100%;height: 50px;">Confirm <i
                                    class="fa fa-chevron-right"></i></button>
                        </div>

                    </div>
                </div>
                <input type="hidden" title="0" name="booking_qty[]" value="0">
                <input type="hidden" title="0" name="booking_slots[]" value="0">
                <input type="hidden" name="booking_slot_id[]" value="0">
                <input type="hidden" name="category_id" value="{$category_id}">
                <input type="hidden" name="slot_time" value="{$slot_time}">
                <input type="hidden" name="date" value="{$current_date|date_format:"%Y-%m-%d"}">
                <input type="hidden" name="calendar_id" value="{$calendar_id}">
                <input type="hidden" required="" id="amount" name="amount" value="">
            </form>
        </div>
    {else}
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <br>
                    <br>
                    {if $category_id>0}
                        <p class="booking-info center">Slots not available Please select Search criteria</p>
                    {/if}
                </div>
            </div>
        </div>
    {/if}

</div>

<form action="" id="slotfrm" method="post">
    <input type="hidden" name="category_id" value="{$category_id}">
    <input type="hidden" name="slot_time" value="{$slot_time}">
    <input type="hidden" name="booking_date" id="book_date" value="">
    <input type="hidden" name="calendar_id" value="{$calendar_id}">
    <input type="hidden" name="court_name" value="{$court_name}">
    <input type="hidden" id="max_booking" value="{$max_booking}">
</form>

<!-- END PAGE CONTENT-->
</div>
</div>
<!-- END CONTENT -->
</div>
{if $slot_id>0}
    <script>
        document.getElementById('slotbtn' +{$slot_id}).click();
    </script>
{/if}