<!-- BEGIN CONTENT -->

{if count($courts) <= 1}
    <form action="/m/{$url}/selectpackage" method="post" id="auto_submit" name="auto_submit">
        <input type="hidden" name="category_id" value="{$category_id}">
        <input type="hidden" name="slot_time" value="{$slot_time}">
        <input type="hidden" name="booking_date" value="{$current_date}">
        <input type="hidden" name="calendar_id" value="{$courts[0]["calendar_id"]}">
        <input type="hidden" name="court_name" value="{$courts[0]["court_name"]}">
    </form>
    <script>
        document.addEventListener("DOMContentLoaded", function(event) {
            document.createElement('form').submit.call(document.getElementById('auto_submit'));
        });
    </script>
{else}
    <div class="page-content" style="min-height: 858px;">
        <!-- BEGIN PAGE HEADER-->
        <!-- <h3 class="page-title">&nbsp</h3>-->
        <!-- END PAGE HEADER-->
        <!-- BEGIN PAGE CONTENT-->
        <div class="row" style="background-color: #f5f5f5;padding-top: 20px;padding-bottom: 20px;margin-top: -12px;">
            <div class="col-md-2"></div>
            <div class="col-md-8">

                <div class="row">
                    <form action="" method="post">
                        <div class="col-md-12">
                            <div class="col-md-2 no-padding"></div>
                            <div class="col-md-3 no-padding">
                                <div class="input-icon input-icon-lg">
                                    <i class="hidden-xs"><img src="/assets/admin/layout/img/sporticon.png"
                                            style="vertical-align: top;"></i>
                                    <select name="category_id" required onchange="setMerchantDays(this.value, '');
                                        " id="category_id" class="form-control input-lg"
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
                                <a href="/m/{$url}/booking" class="btn grey btn-lg"
                                    style="height: 46px;font-size: 14px;padding-top: 14px;">&nbsp;&nbsp;<i
                                        class="fa fa-backward"></i>&nbsp; Back&nbsp;</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="row no-margin">
            <form action="/m/{$url}/selectpackage" method="post">
                <div class="col-md-2"></div>
                <div class="col-md-8">
                    {if $membership_transaction_success==1}
                        <br>
                        <div class="alert alert-success">
                            <div class="media">
                                <p class="media-heading"><strong>Success!</strong> Your transaction has been successful <a
                                        class="btn blue btn-xs" target="_BLANK"
                                        href="/patron/transaction/receipt/{$membership_transaction_receipt}">Receipt</a></p>
                            </div>
                        </div>
                    {/if}
                    {if $reminder==1}
                        <br>
                        <div class="alert alert-info">
                            <div class="media">
                                <p class="media-heading"><strong>Reminder!</strong> Your membership will expire on
                                    {$end_date|date_format:"%d %b %Y"}. Renew your subscription. &nbsp;&nbsp;&nbsp; <a href="#"
                                        onclick="document.getElementById('memdiv').style.display = 'block';"
                                        class="btn blue btn-xs">Buy membership</a></p>
                            </div>
                        </div>
                    {/if}
                    {if !empty($customer_membership)}
                        <br>
                        <div class="panel-group accordion" id="accordion3">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a class="accordion-toggle accordion-toggle-styled collapsed" data-toggle="collapse"
                                            data-parent="#accordion3" href="#collapse_3_1">
                                            <b>Membership details </b></a>
                                    </h4>
                                </div>
                                <div id="collapse_3_1" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <div class="row">
                                            <table class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Category</th>
                                                        <th>Start date</th>
                                                        <th>End date</th>
                                                        <th>Amount</th>
                                                        <th>Status</th>
                                                        <th>Receipt</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    {foreach from=$customer_membership item=v}
                                                        <tr>
                                                            <td>{$category_name}</td>
                                                            <td>{$v.start_date|date_format:"%d %b %Y"}</td>
                                                            <td>{$v.end_date|date_format:"%d %b %Y"}</td>
                                                            <td>{$v.amount}</td>
                                                            <td>
                                                                {if $v.status=='Active'}
                                                                    <span class="label label-sm label-success">Active
                                                                    </span>
                                                                {else}
                                                                    <span class="label label-sm label-danger">Expired
                                                                    </span>
                                                                {/if}
                                                            </td>
                                                            <td><a class="btn blue btn-xs" target="_BLANK"
                                                                    href="/patron/transaction/receipt/{$v.transaction_link}">Receipt</a>
                                                            </td>
                                                        </tr>
                                                    {/foreach}
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    {/if}

                    {if !empty($membership_list)}
                        <div {if $is_valid==1} style="display: none;" {/if} id="memdiv">
                            <div class="row">
                                <h3 class="booking-title base-color">Buy membership for {$category_name}</h3>
                            </div>
                            <div class="row">

                                <div class="col-lg-12 no-padding booking-court-div"
                                    style="box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.5);margin-bottom: 20px;margin-right: 30px;">
                                    <table class="table table-condensed table-bordered " style="margin-bottom: 0px;">

                                        <tbody>
                                            {foreach from=$membership_list item=s}
                                                <tr>
                                                    <td style="width: 40%;padding: 20px 10px 20px 10px;border-right: 0;">
                                                        &nbsp;&nbsp;&nbsp;

                                                        <label class="booking-slot-text">{$s.title}</label>
                                                    </td>
                                                    <td style="width: 30%;padding: 20px 10px 20px 10px;"><i class="fa fa-clock-o"
                                                            style="font-size: 20px;"> </i>&nbsp;&nbsp;&nbsp;

                                                        <label class="booking-slot-text">{$s.days} Days</label>
                                                    </td>
                                                    <td style="width: 30%;padding: 20px 10px 20px 10px;border-left: 0;"
                                                        class="td-c">
                                                        <label class="booking-slot-price">{if $s.amount>0}@ Rs.
                                                            {$s.amount}/-{else}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                            {/if}
                                                            &nbsp;&nbsp;&nbsp;
                                                            <a href="/m/{$url}/confirmmembership/{$s.encrypted_id}"
                                                                class="btn btn-sm blue pull-right" style="width: 80px;"
                                                                id="slotbtn">
                                                                <span id="slotbtntext">Buy</span> <i
                                                                    class="fa fa-chevron-right"></i>
                                                            </a>
                                                        </label>
                                                    </td>
                                                </tr>
                                            {/foreach}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    {/if}

                    {if $is_valid==1}
                        {if empty($courts)}
                            <div class="row">
                                <h5 class="booking-title base-color">{$category_name}</h5>
                                <br>
                                <div class="alert alert-info">
                                    <div class="media">
                                        <p class="media-heading">Slots not available for the chosen date</p>
                                    </div>
                                </div>
                            </div>
                        {else}
                            <div class="row">
                                <h5 class="booking-title base-color">{$category_name}</h5>
                                <br>
                                <p class="booking-info">Showing availability for this date: {$current_date}</p>
                            </div>
                        {/if}
                        {$int=0}
                        {foreach from=$courts item=v}
                            {if $int==0}
                                <div class="row">
                                {/if}
                                <div class="col-lg-3 no-padding booking-court-div"
                                    style="box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.5);margin-bottom: 20px;margin-right: 30px;">
                                    <img src="{$v.logo}" style="width: 100%;">
                                    <div class="row no-margin" style="padding-left: 15px;padding-right: 15px;">
                                        <h4 class="booking-court pull-left">{$v.calendar_title}</h4>
                                        <button type="submit" onclick="selectCourt('{$v.calendar_id}', '{$v.calendar_title}');"
                                            class="btn btn-xs blue pull-right" style="margin-top: 10px;">
                                            Book <i class="fa fa-arrow-right"></i>
                                        </button>
                                    </div>
                                    <div style="padding-left: 15px;padding-right: 15px;" class="row no-margin">
                                        <p class="booking-slots-available pull-left" onmouseover="displayslot({$v.calendar_id});"
                                            onmouseout="hideslot({$v.calendar_id});">{$v.available} Slots Available</p>
                                        <br>
                                        <br>
                                        <div id="slot_div{$v.calendar_id}"
                                            style="background-color: #f5f5f5;position: absolute;z-index: 10;display: none;">
                                            <table class="table table-condensed table-bordered " style="margin-bottom: 0px;">
                                                <tr style="font-weight: bold;">
                                                    <th>Slot Time</th>
                                                    <th>Slot Price</th>
                                                </tr>
                                                <tbody>
                                                    {foreach from=$v.slots item=s}
                                                        <tr>
                                                            <td>{$s.time_from} To {$s.time_to}</td>
                                                            <td class="td-c">{$s.slot_price}</td>
                                                        </tr>
                                                    {/foreach}
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                </div>
                                {$int=$int+1}
                                {if $int==3}
                                </div>
                                {$int=0}
                            {/if}
                        {/foreach}
                    {/if}
                </div>
                <input type="hidden" name="category_id" value="{$category_id}">
                <input type="hidden" name="slot_time" value="{$slot_time}">
                <input type="hidden" name="booking_date" value="{$current_date}">
                <input type="hidden" name="calendar_id" id="calendar_id">
                <input type="hidden" name="court_name" id="court_name">
            </form>



        </div>
    </div>


    <!-- END PAGE CONTENT-->
    </div>
    </div>
    <!-- END CONTENT -->
    </div>
{/if}