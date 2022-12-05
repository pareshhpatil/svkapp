
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <h3 class="page-title">&nbsp;</h3>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row no-margin">
        {if {$isGuest} =='1'}
            <div class="col-md-2"></div>
            <div class="col-md-8" style="text-align: -webkit-center;text-align: -moz-center;">
            {else}
                <div class="col-md-1"></div>
                <div class="col-md-10" style="text-align: -webkit-center;text-align: -moz-center;">
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

                <form action="{$post_link}" method="post">
                    <div class="portlet light bordered" style="max-width: 900px;text-align: left;">
                        <div class="portlet-title">
                            <div class="col-md-9">
                                <div class="caption font-blue">
                                    <span class="caption-subject bold uppercase"> <h3>{$info.event_name}</h3></span>
                                    <p>Presented by : Maple</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="caption font-blue pull-right">
                                    {if $absolute_cost>0}<h3><i class="fa fa-inr fa-large"></i> {$display_absolute_cost} /-</h3>

                                    {/if}
                                </div>
                            </div>
                            <br>
                        </div>
                        {if {$isGuest} =='1'}
                            <div class="form-body">

                                <div class="row">
                                    <span style="font-size: initial;font-weight: 100;"> Enter Billing details</span>

                                    <br>
                                    <br>
                                    <div class="col-md-6">

                                        <div class="form-group">
                                            <label class="control-label col-md-4">Name <span class="required">
                                                    * </span></label>
                                            <div class="col-md-8">
                                                <input type="text" required name="name" {$validate.name} class="form-control" >
                                                <span class="help-block"> </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Email <span class="required">
                                                    * </span></label>
                                            <div class="col-md-8">
                                                <input type="email" name="email" {$validate.email} class="form-control" >
                                                <span class="help-block"> </span>
                                            </div>

                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Mobile <span class="required">
                                                    * </span> </label>

                                            <div class="col-md-8">
                                                <input type="text" required name="mobile" {$validate.mobile} class="form-control" >
                                                <span class="help-block"> </span>
                                            </div>

                                        </div>


                                    </div>

                                    <div class="col-md-6">

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">Address <br>(min 25 char) <span class="required">
                                                            * </span> </label>
                                                    <div class="col-md-8">
                                                        <textarea type="text" required name="address" {$validate.address} class="form-control" ></textarea>
                                                        <span class="help-block"> </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">City <span class="required">
                                                            * </span> </label>
                                                    <div class="col-md-8">
                                                        <input type="text" required name="city" {$validate.name} class="form-control" value="">
                                                        <span class="help-block"> </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">Zipcode <span class="required">
                                                            * </span> </label>
                                                    <div class="col-md-8">
                                                        <input type="digit" required name="zipcode" {$validate.zipcode} class="form-control" value="">
                                                        <span class="help-block"> </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">State <span class="required">
                                                            * </span></label>
                                                    <div class="col-md-8">
                                                        <input type="text" required name="state" {$validate.name} class="form-control" value="Maharashtra">
                                                        <span class="help-block"> </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                </div>
                            </div>
                        {/if}
                        {if $info.event_type!=2}

                            <div class="row">
                                {if $info.capture_details==1}
                                    <span style="font-size: initial;font-weight: 100;"> Enter Attendee Information</span>
                                    <span style="font-weight: 100;"> (Optional)</span>
                                {else}
                                    <span style="font-size: initial;font-weight: 100;"> Product Information</span>
                                {/if}
                                {$int=1}
                                <div class="table-scrollable">
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>
                                                    #
                                                </th>
                                                <th>
                                                    Product Description
                                                </th>
                                                <th class="td-c">
                                                    Quantity
                                                </th>
                                                <th class="td-c">
                                                    Price
                                                </th>
                                                <th class="td-c">
                                                    Total
                                                </th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            {foreach from=$package item=v}

                                                {if $v.seat>0}
                                                    <tr>
                                                        <td>
                                                            {$int}
                                                        </td>
                                                        <td>
                                                            {$v.package_name}
                                                        </td>
                                                        <td class="td-c">
                                                            {$v.seat}
                                                        </td>
                                                        <td class="td-c">
                                                            {$v.price}
                                                        </td>
                                                        <td class="td-c">
                                                            {{$v.price * $v.seat}|string_format:"%.2f"}
                                                        </td>

                                                    </tr>

                                                    {for $foo=1 to {$v.seat}}
                                                    <input name="package_id[]"   type="hidden" value="{$v.package_id}" />
                                                    <input name="price[]"   type="hidden" value="{$v.price|string_format:"%.2f"}" />
                                                {/for}
                                                {$int=$int+1}
                                            {/if}
                                        {/foreach}

                                        <tr>
                                            <th colspan="4">
                                                Grand Total
                                            </th>
                                           
                                            <th class="td-c">
                                                {$absolute_cost|string_format:"%.2f"}
                                            </th>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        {/if}
                        <input name="amount" readonly type="hidden"  value="{$absolute_cost}" />
                        <input name="increpted" readonly type="hidden"  value="{$absolute_cost_incr}" />


                        <p>
                            Please note: We do not store any of your card/ account information when you make a payment. For online payment, we may redirect you to a secure banking/payment gateway website to provide your card/account credentials.</p>
                        <p>
                            <label><input type="checkbox" required></span> I accept the <a  target="_BLANK" href="http://mapledti.com/terms-and-conditions-prebook.php">Terms and conditions</a> </label>
                        </p>
                        <div class="row">
                            <div class="col-md-8">
                                <label> </label>
                            </div>
                            <div class="col-md-4">
                                <input name="payment_req" type="hidden" size="60" value="{$payment_request_id}" />
                                <input name="seat" type="hidden"  value="{$seat}" />
                                <input name="occurence_id" type="hidden"  value="{$occurence_id}" />
                                <input name="narrative" type="hidden"  value="{$company_name}" />
                                <input name="coupon_id" type="hidden"  value="{$coupon_id}" />
                                <input name="tax" type="hidden"  value="{$tax_amount}" />
                                <input name="discount" type="hidden"  value="{$discount_amount}" />
                                <button type="submit" class="btn blue pull-right">Pay now</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>