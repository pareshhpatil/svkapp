<!-- BEGIN CONTENT -->
<div class="page-content" style="background-image: url('{if $booking_background!=''}{$booking_background}{else}/assets/admin/layout/img/booking_bg.jpg{/if}');
    height: 100vh;
    background-repeat: no-repeat;
    background-size: cover;
    background-position: center;">
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <!-- BEGIN PAGE HEADER-->
    <!-- <h3 class="page-title">&nbsp</h3>-->
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <div class="row">
                <!-- image upload -->
                <div class="col-md-12 booking-title">{if $booking_title!=''}{$booking_title}
                    {else}NOW BOOK SPORTS /
                    ACTIVITES ONLINE!&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{/if}</div>
            </div>
            <div class="row">
                <br>
                <form action="/m/{$url}/selectcourt" method="post">
                    <input type="hidden" id="merchant_id" value="{$merchant_id}">
                    <div class="col-md-12">
                        <br>
                        <br>
                        {if count($category_list)>1}
                            <div class="col-md-5">
                                <div class="input-icon input-icon-lg">
                                    <i class="hidden-xs"><img src="/assets/admin/layout/img/sporticon.png"
                                            style="vertical-align: top;"></i>
                                    <select name="category_id" required onchange="setMerchantDays(this.value, '');"
                                        id="category_id" class="form-control input-lg" data-placeholder="Select Date...">
                                        <option value="">Select Activity</option>
                                        {foreach from=$category_list item=v}
                                            <option value="{$v.category_id}"> {$v.category_name}</option>
                                        {/foreach}
                                    </select>
                                </div>
                                <span class="help-block"> </span>
                            </div>
                        {else}
                            <input type="hidden" name="category_id" value="{$category_list[0].category_id}">
                            <div class="col-md-2"></div>
                        {/if}
                        <div class="col-md-5">
                            <input type="text" onchange="getCategoryCalendar();"
                                style="background-color: #ffffff;cursor: pointer;" name="booking_date" readonly
                                id="date" placeholder="Select date" class="form-control input-lg" autocomplete="off"
                                data-date-format="dd M yyyy">
                            <span class="help-block"> </span>
                        </div>
                        <div class="col-md-2 center">
                            <input type="hidden" name="slot_time" value="">
                            <button type="submit" class="btn blue input-lg">&nbsp;&nbsp;<i
                                    class="fa fa-search"></i>&nbsp; Search&nbsp;&nbsp;</button>
                        </div>
                    </div>
                </form>
            </div>

        </div>


    </div>
    <!-- END PAGE CONTENT-->
</div>
</div>
<!-- END CONTENT -->
</div>