{strip}
    <div class="row">

        <div class="col-md-2"></div>
        <div class="col-md-8" style="box-shadow: 1px 10px 10px #888888;">
            <form action="/patron/event/pay/{$url}" onsubmit="return validateBooking();" method="post">
                <h1>{$info.event_name}</h1>
                <h7>Presented by <a href="{$merchant_page}" target="BLANK">{$info.company_name}</a></h7>

                <p>
                    <br>
                    <i class="fa fa-calendar"></i> {$info.event_from_date|date_format:"%d %B"} {if $info.event_from_date!=$info.event_to_date}– {$info.event_to_date|date_format:"%d %B"}{/if}
                </p>
                {if $info.venue!=''}
                    <p>
                        <i class="fa fa-map-marker"></i> {$info.venue}
                    </p>
                {/if}

                {foreach from=$header item=v}
                    {if $v.value!=''}
                        {if $v.column_position==1}
                        {else if $v.column_position==2}
                        {else}
                            <p>
                                <strong>{$v.column_name}:</strong><br>
                                {$v.value}
                            </p>
                        {/if}
                    {/if}
                {/foreach}

                <ul class="nav nav-tabs mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home"
                           aria-selected="true">Buy Tickets</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pills-tnc-tab" data-toggle="pill" href="#pills-tnc" role="tab" aria-controls="pills-tnc" aria-selected="false">T&C</a>
                    </li>
                </ul>

                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">

                        <div id="accordion">


                            {$int=0}
                            {foreach from=$package_category item=v}
                                {$int=$int+1}
                                {foreach from=$package.{$v.name} item=pc}

                                    {if $info.artist!=''}
                                        <div class="form-group">
                                            <label class="col-md-4"><b>Artists</b></label>
                                            <div class="col-md-12">{$info.artist}</div>
                                        </div>
                                    {/if}
                                    <div class="form-group">
                                        <label class="col-md-4"><b>Description</b></label>
                                        <div class="col-md-12">{$info.description}</div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4"><b>{$info.unit_type} Price</b></label>
                                        <div class="col-md-12">{if $pc.is_flexible==1}
                                            Min - {$pc.min_price|number_format:2:".":","} Max - {$pc.max_price|number_format:2:".":","}
                                            <input type="hidden" value="1" name="is_flexible[]">
                                            {else}
                                                {$pc.price|number_format:2:".":","}
                                                {/if}</div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-4"><b>Select {$info.unit_type}</b></label>
                                                <div class="col-md-12"><select class="form-control" name="package_qty[]" id="qty{$pc.package_id}" onchange="calculateTotal();" style="width: 85px">
                                                        <option>0</option>
                                                        {for $foo=$pc.min_seat to $pc.max_seat}
                                                            <option value="{$foo}">{$foo}</option>
                                                        {/for}
                                                    </select>
                                                    <input type="hidden" value="{$pc.price}" id="price{$pc.package_id}" name="price[]">
                                                    <input type="hidden" value="{$pc.tax}" id="tax{$pc.package_id}" name="tax[]">
                                                    <input type="hidden" value="{$pc.tax_text}" id="tax_text{$pc.package_id}">
                                                    <input type="hidden" value="{$pc.package_name}" id="pkg_name{$pc.package_id}">
                                                    <input type="hidden" value="{$pc.package_id}" name="package_id[]">
                                                    <input type="hidden" value="{$pc.occurence_id}" name="occurence_id[]">
                                                    <input type="hidden" class="displayonly" id="pkg_copun{$pc.coupon_code}" value="{$pc.package_id}"/>
                                                </div>
                                            </div>
                                            {/foreach}
                                                <br/>
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

                                        <br/>
                                        <div class="row">
                                            <div class="col-md-8">

                                            </div>
                                            <div class="col-md-4">
                                                <table class="table-sm pull-right">
                                                    <tbody>
                                                        <tr>
                                                            <td>Total</td>
                                                            <td align="right">&#8377; <span id="base_total">0.00</span></td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2">
                                                                <div id="tax_breckup">
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr id="disctr" style="display: none;">
                                                            <td>Coupon Discount</td>
                                                            <td align="right">&#8377; <span id="coupon_discount">0.00</span></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Grand Total</td>
                                                            <td align="right">&#8377; <span id="grand_total">0.00</span></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <br/>
                                        <div class="row">
                                            <div class="col-md-9">
                                                {if $is_coupon==1}
                                                    <div class="col-md-12">
                                                        <div class="row pull-left">
                                                            <div class="input-group">
                                                                <input type="text" id="coupon_code" placeholder="Enter coupon code" class="form-control" value="" />
                                                                <input type="hidden" class="displayonly" id="all_coupon" value="{$info.copoun_code}"/>
                                                                <input type="hidden" class="displayonly" id="coupon_id" name="coupon_id" value=""/>
                                                                <input type="hidden" class="displayonly" id="coupon_type" />
                                                                <input type="hidden" class="displayonly" id="coupon_percent" value=""/>
                                                                <input type="hidden" class="displayonly" id="coupon_fixed_amount" value=""/>
                                                                <input type="hidden" class="displayonly" id="coupon_description" value=""/>
                                                                <span class="input-group-btn">
                                                                    <button onclick="return validateCoupon('{$info.merchant_user_id}');" class="btn btn-primary">Apply</button>
                                                                </span>
                                                                &nbsp;&nbsp;&nbsp;<small class="font-blue" id="coupon_status">&nbsp;</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                {/if}
                                            </div>
                                            <div class="col-md-3">
                                                <small class="red" id="booking_error">&nbsp;</small>
                                                {if $is_payment==TRUE}
                                                    <button type="submit" class="btn btn-primary pull-right">Book Now</button>
                                                {/if}
                                                <input type="hidden" name="total"  id="submit_total" readonly="" value="" class="displayonly" >
                                                <input type="hidden" name="service_tax"  id="submit_tax" readonly="" value="" class="displayonly" >
                                                <input type="hidden" name="grand_total"  id="submit_grand_total" readonly="" value="" class="displayonly" >
                                                <input type="hidden" name="coupon_discount"  id="submit_discount" readonly="" value="" class="displayonly" >
                                                <input type="hidden" id="total_qty" value="0">
                                            </div>
                                        </div>
                                        <br/>
                                    </form>
                                </div>
                            </div>
                            {/strip}

                                <form action="" id="occurence_form" method="post">
                                    <input type="hidden" value="" id="occurence_id" name="occurence_id" >
                                </form>