{strip}
    <div class="row">

        <div class="col-md-8">
            {if $is_valid=='NO'}
                <div class="alert alert-danger max900" style="text-align: left;">
                    <div class="media">
                        {$invalid_message}
                    </div>
                </div>
            {/if}
            <form action="/patron/event/pay/{$url}" onsubmit="return validateBooking();" method="post">
                <h1>{$info.event_name}</h1>
                <h7>Presented by <a href="{$merchant_page}" target="BLANK">{$info.company_name}</a></h7>
                {if $info.occurence>0}
                    <p>
                        <br>
                        <i class="fa fa-calendar"></i> {$info.event_from_date|date_format:"%d %B"}
                        {if $info.event_from_date!=$info.event_to_date}– {$info.event_to_date|date_format:"%d %B"}{/if}
                    </p>
                {/if}
                {if $info.venue!=''}
                    <p>
                        <i class="fa fa-map-marker"></i> {$info.venue}
                    </p>
                {/if}

                {foreach from=$header item=v}
                    {if $v.value!='' && $v.column_position!=1 &&  $v.column_position!=2}
                        <p><strong>{$v.column_name}: </strong>{$v.value}</p>
                    {/if}
                {/foreach}

                <ul class="nav nav-tabs mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab"
                            aria-controls="pills-home" aria-selected="true">
                            {if $info.merchant_id=='M000008220'}Donation{else}Buy {$info.unit_type}{/if}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pills-tnc-tab" data-toggle="pill" href="#pills-tnc" role="tab"
                            aria-controls="pills-tnc" aria-selected="false">T&C</a>
                    </li>
                </ul>

                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">

                        <div id="accordion">

                            {$int=0}
                            {foreach from=$package_category item=v}
                                {$int=$int+1}
                                {if count($package_category)>1}
                                    <div class="card">
                                        <a href="javascript:void(0)" data-toggle="collapse" data-target="#collapse{$int}"
                                            aria-expanded="{if $int==$div_int}true{else}false{/if}" aria-controls="collapse{$int}">
                                            <div class="card-header " id="headingOne">
                                                <h5 class="mb-0 d-none d-sm-block">
                                                    {$v.name}
                                                    <span class="pull-right">{$currency_icon} {if $v.max_cat_price>$v.min_cat_price}
                                                            {$v.min_cat_price|number_format:2:".":","} -
                                                        {$v.max_cat_price|number_format:2:".":","}{else}{$v.max_cat_price|number_format:2:".":","}
                                                        {/if}</span>
                                                </h5>
                                                <h6 class="mb-0 d-block d-sm-none">
                                                    {$v.name}
                                                    <span class="pull-right">{$currency_icon} {if $v.max_cat_price>$v.min_cat_price}
                                                            {$v.min_cat_price|number_format:2:".":","} -
                                                        {$v.max_cat_price|number_format:2:".":","}{else}{$v.max_cat_price|number_format:2:".":","}
                                                        {/if}</span>
                                                </h6>
                                            </div>
                                        </a>
                                        <div id="collapse{$int}" class="collapse {if $int==$div_int}show{/if}"
                                            aria-labelledby="headingOne" data-parent="#accordion">
                                            <div class="card-body">
                                            {/if}
                                            <div class="row">

                                                <div class="col-md-2"></div>
                                                <div class="col-md-4">
                                                    {if $v.enable_date>0 && count($occurence)>1}
                                                        <div class="form-group">
                                                                <select class="form-control"
                                                                    onchange="occurenceSubmit(this.value,{$int});" id="occurence"
                                                                    style="width: 100%">
                                                                    <option>Select Booking Date</option>
                                                                    {foreach from=$occurence item=oc}
                                                                        {if $oc.start_date==$oc.end_date}
                                                                            {if $oc.start_time==$oc.end_time}
                                                                                {$datevalue={$oc.start_date|date_format:"%A, %d %b %Y"}}
                                                                            {else}
                                                                                {$from_date={$oc.start_date|cat:" "|cat:$oc.start_time}|date_format:"%A,
                                                        %d %b %Y %I:%M %p"}
                                                                                {$to_date={$oc.end_time|date_format:"%I:%M %p"}}
                                                                                {$datevalue=$from_date|cat:" To "|cat:$to_date}
                                                                            {/if}
                                                                        {else}
                                                                            {if $oc.start_time==$oc.end_time}
                                                                                {$from_date={$oc.start_date|date_format:"%A, %d %b %Y"}}
                                                                                {$to_date={$oc.end_date|date_format:"%A, %d %b %Y"}}
                                                                                {$datevalue=$from_date|cat:" To "|cat:$to_date}
                                                                            {else}
                                                                                {$from_date={$oc.start_date|cat:" "|cat:$oc.start_time}|date_format:"%A,
                                                        %d %b %Y %I:%M %p"}
                                                                                {$to_date={$oc.end_date|cat:" "|cat:$oc.end_time}|date_format:"%A,
                                                        %d %b
                                                        %Y %I:%M %p"}
                                                                                {$datevalue=$from_date|cat:" To "|cat:$to_date}
                                                                            {/if}
                                                                        {/if}

                                                                        {if $occurence_selected==$oc.occurence_id}
                                                                            <option selected value="{$oc.occurence_id}">{$datevalue}
                                                                            </option>
                                                                        {else}
                                                                            <option value="{$oc.occurence_id}">{$datevalue}</option>
                                                                        {/if}
                                                                    {/foreach}
                                                                </select>
                                                                <small class="form-text text-muted">Pick booking date</small>
                                                        </div>
                                                    </div>
                                                {/if}

                                                {if count($currency)>1}
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                                <select class="form-control"
                                                                    onchange="occurenceSubmit(this.value,{$int});" id="currency_id"
                                                                  style="200px;"  >
                                                                    <option>Select Currency</option>
                                                                    {foreach from=$currency item=c}
                                                                        {if $currency_id==$c && $currency_id!='0'}
                                                                            <option selected value="{$c}">{$c}</option>
                                                                        {else}
                                                                            <option value="{$c}">{$c}</option>
                                                                        {/if}
                                                                    {/foreach}
                                                                </select>
                                                                <small class="form-text text-muted">Pick currency</small>
                                                        </div>
                                                    </div>
                                                {/if}
                                            </div>
                                            <table class="table-sm" width="100%">
                                                <tbody>
                                                    {foreach from=$package.{$v.name} item=pc}
                                                        {if $pc.occurence_id>0}
                                                            <tr>
                                                                <td width="70%">
                                                                    <b>{$pc.package_name}</b>
                                                                </td>
                                                                <td>
                                                                    <b>
                                                                        {if $pc.is_flexible==1}
                                                                            Min - {$pc.min_price|number_format:2:".":","} Max -
                                                                            {$pc.max_price|number_format:2:".":","}
                                                                            <input type="hidden" value="1" name="is_flexible[]">
                                                                        {elseif $pc.is_flexible==2}
                                                                            Free
                                                                        {else}
                                                                            {if $payment_request_id=='R000209720' && $pc.min_seat==2}
                                                                                999.00
                                                                            {else}
                                                                                {$pc.price|number_format:2:".":","}
                                                                            {/if}
                                                                        {/if}
                                                                    </b>
                                                                </td>
                                                                <td>
                                                                    {if $pc.sold_out==1}
                                                                        <b class="red">Sold Out</b>
                                                                        <input type="hidden" value="0" id="qty{$pc.package_id}"
                                                                            name="package_qty[]">
                                                                    {else}
                                                                        <div class="form-group">
                                                                            <select class="form-control pull-right" name="package_qty[]"
                                                                                id="qty{$pc.package_id}" onchange="calculateTotal();"
                                                                                style="width: 65px">
                                                                                <option>0</option>
                                                                                {for $foo=$pc.min_seat to $pc.max_seat}
                                                                                    {if $payment_request_id=='R000209720'}
                                                                                        {if $foo is div by $pc.min_seat}
                                                                                            <option value="{$foo}">{$foo}</option>
                                                                                        {/if}
                                                                                    {else}
                                                                                        <option value="{$foo}">{$foo}</option>
                                                                                    {/if}
                                                                                {/for}
                                                                            </select>
                                                                        </div>
                                                                    {/if}
                                                                    <input type="hidden" value="{$pc.price}" id="price{$pc.package_id}"
                                                                        name="price[]">
                                                                    <input type="hidden" value="{$pc.tax}" id="tax{$pc.package_id}"
                                                                        name="tax[]">
                                                                    <input type="hidden" value="{$pc.tax_text}"
                                                                        id="tax_text{$pc.package_id}">
                                                                    <input type="hidden" value="{$pc.package_name}"
                                                                        id="pkg_name{$pc.package_id}">
                                                                    <input type="hidden" value="{$pc.package_id}" name="package_id[]">
                                                                    <input type="hidden" value="{$pc.occurence_id}"
                                                                        name="occurence_id[]">
                                                                    <input type="hidden" class="displayonly"
                                                                        id="pkg_copun{$pc.coupon_code}" value="{$pc.package_id}" />
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="3">
                                                                    <p class="text-muted">
                                                                        <small>{$pc.package_description|substr:0:130|replace:'<br>':' '}
                                                                        </small>
                                                                        {if {$pc.package_description|count_characters:true}>130}
                                                                            &nbsp;&nbsp; <a data-toggle="collapse"
                                                                                href="#collapseExample{$pc.package_id}" role="button"
                                                                                aria-expanded="false" aria-controls="collapseExample">
                                                                                <i class="fa fa-ellipsis-v"></i>
                                                                            </a>
                                                                        <div class="collapse text-muted"
                                                                            id="collapseExample{$pc.package_id}">
                                                                            <small>{$pc.package_description}
                                                                            </small>
                                                                        </div>
                                                                    {/if}

                                                                    </p>
                                                                </td>
                                                            </tr>
                                                        {/if}
                                                    {/foreach}
                                                </tbody>
                                            </table>

                                            {if count($package_category)>1}
                                            </div>
                                        </div>
                                    </div>
                                {/if}
                                <br />
                            {/foreach}
                        </div>

                    </div>
                    <div class="tab-pane fade" id="pills-tnc" role="tabpanel" aria-labelledby="pills-tnc-tab">
                        {if $info.terms!=''}
                            <strong>Terms & Conditions</strong>
                            <p> {$info.terms}</p>
                        {/if}
                        {if $info.privacy!=''}
                            <strong>Cancellation Policy</strong>
                            <p> {$info.privacy}</p>
                        {/if}

                    </div>
                </div>

                <br />
                <div class="row">
                    <div class="col-md-8">

                    </div>
                    <div class="col-md-4">
                        <table class="table-sm pull-right">
                            <tbody>
                                <tr>
                                    <td>Total</td>
                                    <td align="right"><span id="currency_icon">{$currency_icon}</span> <span
                                            id="base_total">0.00</span></td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <div id="tax_breckup">
                                        </div>
                                    </td>
                                </tr>
                                <tr id="disctr" style="display: none;">
                                    <td>Coupon Discount</td>
                                    <td align="right">{$currency_icon} <span id="coupon_discount">0.00</span></td>
                                </tr>
                                <tr>
                                    <td>Grand Total</td>
                                    <td align="right">{$currency_icon} <span id="grand_total">0.00</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <br />
                <div class="row">
                    <div class="col-md-9">
                        {if $is_coupon==1}
                            <div class="col-md-12">
                                <div class="row pull-left">
                                    <div class="input-group">
                                        <input type="text" id="coupon_code" placeholder="Enter coupon code" class="form-control"
                                            value="" />
                                        <input type="hidden" class="displayonly" id="all_coupon" value="{$info.coupon_id}" />
                                        <input type="hidden" class="displayonly" id="coupon_id" name="coupon_id" value="" />
                                        <input type="hidden" class="displayonly" id="coupon_type" />
                                        <input type="hidden" class="displayonly" id="coupon_percent" value="" />
                                        <input type="hidden" class="displayonly" id="coupon_fixed_amount" value="" />
                                        <input type="hidden" class="displayonly" id="coupon_description" value="" />
                                        <span class="input-group-btn">
                                            <button onclick="return validateCoupon('{$info.merchant_user_id}');"
                                                class="btn btn-primary">Apply</button>
                                        </span>
                                        &nbsp;&nbsp;&nbsp;<small class="font-blue" id="coupon_status">&nbsp;</small>
                                    </div>
                                </div>
                            </div>
                        {/if}
                    </div>
                    <div class="col-md-3">
                        <small class="red" id="booking_error">&nbsp;</small>
                        {if $is_payment==TRUE && $is_valid=='YES'}
                            <button type="submit" class="btn btn-primary pull-right">{if $info.merchant_id=='M000008220'}Donate
                                Now{else}Book Now
                                {/if}</button>
                        {/if}
                        <input type="hidden" name="total" id="submit_total" readonly="" value="" class="displayonly">
                        <input type="hidden" name="service_tax" id="submit_tax" readonly="" value="" class="displayonly">
                        <input type="hidden" name="grand_total" id="submit_grand_total" readonly="" value=""
                            class="displayonly">
                        <input type="hidden" name="currency" value="{$currency_id}">
                        <input type="hidden" name="coupon_discount" id="submit_discount" readonly="" value=""
                            class="displayonly">
                        <input type="hidden" id="total_qty" value="0">
                    </div>
                </div>
                <br />
            </form>
        </div>

        <div class="col-md-4">
            {if $info.artist!=''}
                <p>
                    <b class="text-danger">{$info.artist_label}</b>
                    <br />
                    <b>
                        {$info.artist}
                    </b>
                </p>
            {/if}
            <p>{$info.description}
            </p>
        </div>
    </div>
{/strip}

<form action="" id="occurence_form" method="post">
    <input type="hidden" value="" id="occurence_id" name="occurence_id">
    <input type="hidden" value="" id="currency" name="currency_id">
    <input type="hidden" value="" id="div_int" name="div_int">
</form>