<style>
    .isHidden {
        display: none !important;
        /* hide radio buttons */
    }

    .label {
        display: inline-block;
        background-color: #FFFFFF;
        width: 100%;
        height: 120px;
        padding: 5px 10px;
    }

    .radio:checked+.label {
        /* target next sibling (+) label */
        background-color: blue;
    }
</style>
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
                            <input type="hidden" name="calendar_id" value="{$calendar_id}">
                            <button type="submit" class="btn blue input-lg">&nbsp;&nbsp;<i
                                    class="fa fa-search"></i>&nbsp; Search&nbsp;&nbsp;</button>
                            <a href="/m/{$url}/booking" class="btn grey btn-lg"
                                style="height: 46px;font-size: 14px;padding-top: 14px;">&nbsp;&nbsp;<i
                                    class="fa fa-backward"></i>&nbsp; Back&nbsp;</a>
                        </div>
                        <div class="col-md-1 no-padding"></div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="row no-margin">
        <form action="/m/{$url}/selectslot" id="package_form" method="post">
            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-10 col-md-offset-1">
                            <h3 class="booking-title base-color">{$category_name}</h3>
                            <p class="booking-desc text-center">{$description}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5 col-md-offset-1" >
                            <p style="color: #859494;
                            font-weight: 600;
                            font-size: 1.5rem;
                            letter-spacing: 0rem;
                            margin-bottom: 0px;
                            margin-top: 4px;">Pick a package</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="flex-container">
                            {foreach from=$packages item=p}
                                <input onchange="radiobuttonclicked()" onclick="packageClicked({$p.package_id})"
                                    id="package_div{$p.package_id}" class="radio isHidden" value="{$p.package_id}"
                                    name="radio_a[]" type="radio">
                                <div class="col-md-5 col-md-offset-1 flex-item">
                                    <label class="panel box-plugin" onmouseover="bigImg(this)" onmouseout="normalImg(this)"
                                        id="package_div2{$p.package_id}" for="package_div{$p.package_id}" style="
                                border-radius: 8px !important;
                                margin-top: 17px;
                                width: 100%;">
                                        <div class="panel-body">
                                            <div class="media">
                                                <div class="media-left">
                                                    {if {$p.package_image} !=''}
                                                        <img class="" src="{$p.package_image}" width="150" height="150">
                                                    {/if}
                                                </div>
                                                <div class="media-body">
                                                    <h4 class="media-heading">{$p.package_name}</h4>
                                                    {$p.package_desc}
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            {/foreach}

                        </div>
                    </div>
                </div>
                <div class="col-md-2"></div>
            </div>
            <input type="hidden" name="category_id" value="{$category_id}">
            <input type="hidden" name="slot_time" value="{$slot_time}">
            <input type="hidden" name="booking_date" value="{$current_date}">
            <input type="hidden" id="package_id" name="package_id" value="0">
            <input type="hidden" name="calendar_id" value="{$calendar_id}">
            <input type="hidden" name="court_name" id="court_name">
        </form>
    </div>
</div>


<!-- END PAGE CONTENT-->
</div>
</div>
<!-- END CONTENT -->
</div>