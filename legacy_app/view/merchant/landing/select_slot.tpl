<!-- BEGIN CONTENT -->
<div class="page-content" style="min-height: 858px;">
    <!-- BEGIN PAGE HEADER-->
    <!-- <h3 class="page-title">&nbsp</h3>-->
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row" style="background-color: #f5f5f5;padding-top: 20px;padding-bottom: 20px;margin-top: -12px;">
        <div class="col-md-2"></div>
        <div class="col-md-8">
            <div class="row">
                <form action="/m/{$url}/selectcourt" method="post">
                    <div class="col-md-12">
                        <div class="col-md-2 no-padding"></div>
                        <div class="col-md-3 no-padding">
                            <div class="input-icon input-icon-lg">
                                <i class="hidden-xs"><img src="/assets/admin/layout/img/sporticon.png"
                                        style="vertical-align: top;"></i>
                                <select name="category_id" required onchange="setDays(this.value, '');
                                        getCategorySlots();" id="category_id" class="form-control input-lg"
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
                            <span class="help-block"> </span>
                        </div>
                        <div class="col-md-3 no-padding">
                            <input type="text" style="background-color: #ffffff;cursor: pointer;" name="booking_date"
                                readonly id="date" placeholder="Select date" class="form-control input-lg"
                                autocomplete="off" data-date-format="dd M yyyy">
                            <span class="help-block"> </span>
                        </div>

                        <div class="col-md-3 no-padding center">
                            <input type="hidden" name="slot_time" value="{$slot_time}">
                            <input type="hidden" id="merchant_id" value="{$merchant_id}">
                            <button type="submit" class="btn blue input-lg">&nbsp;&nbsp;<i
                                    class="fa fa-search"></i>&nbsp; Search&nbsp;&nbsp;</button>
                            <button type="submit" class="btn grey input-lg">&nbsp;&nbsp;<i
                                    class="fa fa-backward"></i>&nbsp; Back&nbsp;</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="row" id="packagebackbutton">
        <div class="col-md-4 col-md-offset-2">
            <div class="pull-left">
                <form action="/m/{$url}/selectpackage" method="post">
                    {* <button class="btn btn-blue" href="/m/{$url}/selectpackage" type="submit">Back</button> *}
                    <input type="hidden" name="category_id" value="{$category_id}">
                    <input type="hidden" name="slot_time" value="{$slot_time}">
                    <input type="hidden" id="current_booking_date" name="booking_date" value="{$current_date}">
                    <input type="hidden" name="calendar_id" id="calendar_id" value="{$calendar_id}">
                    <input type="hidden" name="court_name" id="court_name">
                </form>
            </div>
        </div>
    </div>

    <div class="row no-margin" id="slotsDiv">
        <form action="/m/{$url}/confirmslot" onsubmit="return validateBooking();" name="frm_slot" method="post">
            <div class="col-md-2"></div>
            <div class="col-md-8">
                <div class="row">
                    <h3 class="booking-title base-color">{$category_name}</h3>
                    {* <h3 class="booking-title base-color">{$category_name} - {$court_name}</h3> *}
                    <p class="booking-desc text-center">{$description}</p>
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
                                                                        {if $sa.available_seat>0}
                                                                            <label class="booking-confirm-detail-label">{$sa.available_seat}
                                                                                remaining</label>
                                                                        {/if}
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
            <input type="hidden" id="max_booking" value="{$max_booking}">
            <input type="hidden" required="" id="amount" name="amount" value="">
        </form>
    </div>

</div>

<form action="" id="slotfrm" method="post">
    <input type="hidden" name="category_id" value="{$category_id}">
    <input type="hidden" name="slot_time" value="{$slot_time}">
    <input type="hidden" name="booking_date" id="book_date" value="">
    <input type="hidden" name="calendar_id" value="{$calendar_id}">
    <input type="hidden" name="package_id" value="{$package_id}">
    <input type="hidden" name="court_name" value="{$court_name}">
</form>

<!-- END PAGE CONTENT-->
</div>
</div>
<!-- END CONTENT -->
</div>
<style>
    input[type="radio"] {
        left: -999em;
        position: absolute;
    }
</style>
{if $slot_id>0}
    <script>
        document.getElementById('slotbtn' +{$slot_id}).click();
    </script>
{/if}