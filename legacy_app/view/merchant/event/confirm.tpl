<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <h3 class="page-title">&nbsp;</h3>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row no-margin">
        <div class="col-md-1"></div>
        <div class="col-md-11">
            <div class="loading" id="loader" style="display: none;">Loading&#8230;</div>
            {if isset($hasErrors)}
                <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert"></button>
                    <strong>Error!</strong>
                    <div class="media">
                        {foreach from=$haserrors item=v}
                            <p class="media-heading">{$v.0} - {$v.1}.</p>
                        {/foreach}
                    </div>

                </div>
            {/if}
            <form action="/merchant/event/respond" onsubmit="document.getElementById('loader').style.display = 'block';"
                method="post">
                <div class="portlet light bordered" style="max-width: 900px;text-align: left;">
                    <div class="portlet-title">
                        <div class="col-md-9">
                            <div class="caption font-blue">
                                <span class="caption-subject bold uppercase">
                                    <h2>{$info.event_name}</h2>
                                </span>
                                <p>Presented by : {$info.company_name}</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="caption font-blue pull-right">
                                <h3>{$currency_icon} {$absolute_cost|string_format:"%.2f"} /-</h3>
                            </div>
                        </div>
                        <br>
                    </div>
                    <div class="row">
                        <h4> Enter Payee details</h4>
                        <div class="col-xs-12">
                            <div class="form-group">
                                <label class="control-label col-md-3">Select Customer <span class="required">*
                                    </span></label>
                                <div class="col-md-7">
                                    <div class="">
                                        <select name="customer_id" id="customer_id" title="Create new customer" required
                                            class="form-control select2me" data-placeholder="Select...">
                                            <option value=""></option>
                                            {foreach from=$customer_list item=v}
                                                <option value="{$v.customer_id}">{$v.customer_code} | {$v.name}</option>
                                            {/foreach}
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-1 no-margin no-padding">
                                    <a data-toggle="modal" href="#custom" class="btn green"><i
                                            class="fa fa-plus"></i></a>
                                </div>
                            </div>

                        </div>
                    </div>
                    {if $info.event_type!=2}

                        <div class="row">
                            {if $info.capture_details==1}

                            {else}
                                <span style="font-size: initial;font-weight: 100;"> Package Information</span>
                            {/if}
                            {foreach from=$package item=v}
                                {if $v.seat>0}
                                    <h5> {$v.package_name} <b>(Qty {$v.seat})</b> </h5>
                                    <div class="col-xs-12">

                                        {for $foo=1 to {$v.seat}}
                                            {if !empty($attendees_capture)}
                                                <div class="row" id="attendee_div">
                                                    <span style="font-size: initial;font-weight: 100;"> Enter Attendee
                                                        Information</span>
                                                    <span style="font-weight: 100;"></span>

                                                    {$package_name=$package.0.package_id}
                                                    {assign var=id value=1}
                                                    {foreach from=$package key=k item=v}
                                                        {if $v.seat>0}

                                                            {for $foo=1 to {$v.seat}}
                                                                <div class="form-body">
                                                                    <div class="row no-margin">
                                                                        <span style="font-size: initial;font-weight: 600;"> Attendee {$foo}
                                                                            [{$v.package_name}]</span>
                                                                        <input name="package_id[]" type="hidden" value="{$v.package_id}" />
                                                                        <input name="price[]" type="hidden" value="{$v.price}" />
                                                                        <input name="occurence_id[]" type="hidden" value="{$v.occurence_id}" />
                                                                        <br>
                                                                        <div class="col-md-6">
                                                                            {foreach from=$attendees_capture item=v}
                                                                                {if $v.position=='L'}
                                                                                    <div class="row">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label col-md-4">{$v.column_name} <span
                                                                                                    class="required">
                                                                                                    {if $v.mandatory==1}*{/if} </span></label>
                                                                                            <div class="col-md-8">
                                                                                                {if $v.datatype=='textarea'}
                                                                                                    <textarea {if $k==0 && $foo==1} id="attendeeid_{$v.name}" {/if}
                                                                                                        name="attendee_{$v.name}[]" class="form-control"></textarea>
                                                                                                {else}
                                                                                                    <input {if $k==0 && $foo==1} id="attendeeid_{$v.name}" {/if}
                                                                                                        type="text" {if $v.mandatory==1}required{/if}
                                                                                                        name="attendee_{$v.name}[]" {$validate.{$v.datatype}}
                                                                                                        class="form-control">
                                                                                                {/if}
                                                                                                <span class="help-block"> </span>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                {/if}
                                                                            {/foreach}
                                                                        </div>

                                                                        <div class="col-md-6">
                                                                            {foreach from=$attendees_capture item=v}
                                                                                {if $v.position=='R'}
                                                                                    <div class="row">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label col-md-4">{$v.column_name} <span
                                                                                                    class="required">
                                                                                                    {if $v.mandatory==1}*{/if} </span></label>
                                                                                            <div class="col-md-8">
                                                                                                {if $v.datatype=='textarea'}
                                                                                                    <textarea {if $k==0 && $foo==1} id="attendeeid_{$v.name}" {/if}
                                                                                                        name="attendee_{$v.name}[]" class="form-control"></textarea>
                                                                                                {else}
                                                                                                    <input {if $k==0 && $foo==1} id="attendeeid_{$v.name}" {/if}
                                                                                                        type="text" {if $v.mandatory==1}required{/if}
                                                                                                        name="attendee_{$v.name}[]" {$validate.{$v.datatype}}
                                                                                                        class="form-control">
                                                                                                {/if}
                                                                                                <span class="help-block"> </span>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                {/if}
                                                                            {/foreach}
                                                                        </div>
                                                                        <br>
                                                                    </div>
                                                                {/for}
                                                            </div>
                                                        {/if}
                                                    {/foreach}
                                                    <br>
                                                </div>
                                            {else}
                                                <input name="package_id[]" type="hidden" value="{$v.package_id}" />
                                                <input name="occurence_id[]" type="hidden" value="{$v.occurence_id}" />
                                                <input name="price[]" type="hidden" value="{$v.price}" />
                                            {/if}
                                        {/for}
                                        </tbody>
                                        </table>
                                    </div>
                                    {if $v.is_flexible==1}
                                        <div class="row">
                                            <div class="col-md-8">
                                                <label class="font-red">* Minimum : {$currency_icon} {$v.min_price} &
                                                    Maximum : {$currency_icon} {$v.max_price} </label>
                                            </div>
                                            <div class="col-md-4">
                                                <input name="flexible_amount[]" type="number" step="0.01" min="{$v.min_price}"
                                                    max="{$v.max_price}" placeholder="Enter amount" required class="form-control"
                                                    value="" />
                                                <br>
                                            </div>

                                        </div>
                                    {/if}
                                {/if}
                            {/foreach}
                        </div>
                    {/if}
                    {if $absolute_cost>0 || $is_flexible==1}
                        <div class="row">
                            <h4> Enter transaction details</h4>
                            <div class="col-xs-12 form-horizontal">
                                <div class="form-body">
                                    <div class="alert alert-danger display-hide">
                                        <button class="close" id="md_close" data-close="alert"></button>
                                        You have some form errors. Please check below.
                                    </div>
                                    {if !empty($deduct)}
                                        <div class="form-group">
                                            <label for="inputPassword12" class="col-md-5 control-label">Select deductible <span
                                                    class="required">*
                                                </span></label>
                                            <div class="col-md-5">
                                                <select name="selecttemplate"
                                                    onchange="calculatedeductMerchant('{$info.tax_amount}', '{$info.basic_amount-$advance}', '{$info.Previous_dues}');"
                                                    id="deduct" required class="form-control" data-placeholder="Select...">
                                                    <option value="0">Select deductible</option>
                                                    {foreach from=$deduct item=v}
                                                        <option value="{$v.absolute}">{$v.name} ({$v.percentage} %)</option>
                                                    {/foreach}
                                                </select>
                                            </div>
                                        </div>
                                    {/if}
                                    <div class="form-group">
                                        <label for="inputPassword12" class="col-md-5 control-label">Transaction type<span
                                                class="required">*
                                            </span></label>
                                        <div class="col-md-5">
                                            <select class="form-control input-sm select2me" name="response_type"
                                                onchange="responseType(this.value);" data-placeholder="Select type">
                                                <option value="1">NEFT/RTGS</option>
                                                <option value="2">Cheque</option>
                                                <option value="3">Cash</option>
                                                <option value="5">Online Payment</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group" id="bank_transaction_no">
                                        <label for="inputPassword12" class="col-md-5 control-label">Bank ref no</label>
                                        <div class="col-md-5">
                                            <input class="form-control form-control-inline input-sm"
                                                name="bank_transaction_no" type="text" value=""
                                                placeholder="Bank ref number" />
                                        </div>
                                    </div>
                                    <div id="cheque_no" style="display: none;">
                                        <div class="form-group">
                                            <label for="inputPassword12" class="col-md-5 control-label">Cheque no</label>
                                            <div class="col-md-5">
                                                <input class="form-control form-control-inline input-sm" name="cheque_no"
                                                    {$validate.number} type="text" value="" placeholder="Cheque no" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputPassword12" class="col-md-5 control-label">Cheque
                                                status</label>
                                            <div class="col-md-5">
                                                <select class="form-control input-sm" name="cheque_status"
                                                    data-placeholder="Select status">
                                                    <option value="Deposited">Deposited</option>
                                                    <option value="Realised">Realised</option>
                                                    <option value="Bounced">Bounced</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group" id="cash_paid_to" style="display: none;">
                                        <label for="inputPassword12" class="col-md-5 control-label">Cash paid to</label>
                                        <div class="col-md-5">
                                            <input class="form-control form-control-inline input-sm" name="cash_paid_to"
                                                {$validate.name} type="text" value="{$user_name}"
                                                placeholder="Cash paid to" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputPassword12" class="col-md-5 control-label">Date <span
                                                class="required">*
                                            </span></label>
                                        <div class="col-md-5">
                                            <input class="form-control form-control-inline input-sm date-picker"
                                                onkeypress="return false;" autocomplete="off" data-date-format="dd M yyyy"
                                                required name="date" type="text" value="" placeholder="Date" />
                                        </div>
                                    </div>
                                    <div class="form-group" id="bank_name">
                                        <label for="inputPassword12" class="col-md-5 control-label">Bank name</label>
                                        <div class="col-md-5">
                                            <select class="form-control input-sm select2me" name="bank_name"
                                                data-placeholder="Select bank name">
                                                <option value=""></option>
                                                {html_options values=$bank_value selected={$bank_selected}
                                                output=$bank_value}
                                            </select>
                                        </div>
                                    </div>
                                    {if $absolute_cost>0 || $is_flexible!=1}
                                        <div class="form-group">
                                            <label for="inputPassword12" class="col-md-5 control-label">Amount <span
                                                    class="required">*
                                                </span></label>
                                            <div class="col-md-5">
                                                <input class="form-control form-control-inline input-sm" id="total"
                                                    name="amount" required type="text" {$validate.amount}
                                                    value="{$absolute_cost}" placeholder="Amount" />
                                            </div>
                                        </div>
                                    {/if}
                                </div>

                            </div>
                        </div>
                    {else}
                        <input type="hidden" name="response_type" value="3">
                        <input type="hidden" name="cash_paid_to" value="">
                        <input type="hidden" name="date" value="{$current_date}">
                        <input type="hidden" name="bank_name" value="">
                    {/if}

                    <div class="row">
                        <div class="col-md-8">
                            <label> </label>
                        </div>
                        <div class="col-md-4">
                            <input name="payment_request_id" type="hidden" size="60" value="{$payment_request_id}" />
                            <input name="seat" type="hidden" value="{$seat}" />
                            <input name="coupon_id" type="hidden" value="{$coupon_id}" />
                            <input name="tax" type="hidden" value="{$tax_amount}" />
                            <input name="discount" type="hidden" value="{$discount_amount}" />
                            <input name="currency_id" type="hidden" value="{$currency_id}" />
                            <button type="submit" class="btn blue pull-right">Click here to place the order</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>
</div>

<div class="modal fade bs-modal-lg in" id="custom" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <form action="" method="post" id="customerForm" class="form-horizontal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" id="closebutton1" data-dismiss="modal"
                        aria-hidden="true"></button>
                    <h4 class="modal-title">New Customer</h4>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div id="warning" style="display:none;" class="portlet ">
                            <div class="portlet-title">
                                <div class="caption">
                                    Duplicate customer entry?
                                </div>

                            </div>
                            <div class="portlet-body">
                                <div class="alert alert-block alert-warning fade in">

                                    <h4 class="alert-heading">Warning!</h4>
                                    <p id="ex_message">
                                        This Email ID, Mobile Number already exists in your customer database. You could
                                        either replace this record or create a new entry with same values.
                                        Alternatively you can edit the records entered from the Customer create screen
                                        below.
                                    </p>
                                    <br>
                                    <br>
                                    <p class="pull-right" style="margin-top: -25px;">
                                        <button type="submit" id="ex_delete" onclick="return deleteCustomer();"
                                            class="btn red btn-sm">
                                            Disable Existing & Add New </button>
                                        <button type="submit" id="ex_add" onclick="return saveCustomer();"
                                            class="btn green btn-sm">
                                            Save As New </button>
                                        <a onclick="return confirmreplace();" class="btn blue btn-sm">
                                            Replace existing customer data</a>
                                        <a href="#basic" id="confirmm" data-toggle="modal">
                                        </a>
                                        <a onclick="document.getElementById('closebutton1').click();"
                                            class="btn default btn-sm"> Cancel </a>
                                    </p>
                                </div>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="td-c">
                                                Customer code
                                            </th>
                                            <th class="td-c">
                                                Name
                                            </th>
                                            <th class="td-c">
                                                Email
                                            </th>
                                            <th class="td-c">
                                                Mobile
                                            </th>
                                            <th class="td-c">
                                                Replace?
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="allcusta">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="portlet-body form">

                            <div class="form-body">
                                <!-- Start profile details -->
                                <div class="alert alert-danger display-none">
                                    <button class="close" data-dismiss="alert"></button>
                                    You have some form errors. Please check below.
                                </div>
                                <div class="alert alert-danger" style="display: none;" id="errorshow">
                                    <button class="close"
                                        onclick="document.getElementById('errorshow').style.display = 'none';"></button>
                                    <p id="error_display">Please correct the below errors to complete registration.</p>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label
                                                class="control-label col-md-4">{$customer_default_column.customer_code|default:'Customer code'}<span
                                                    class="required">* </span></label>
                                            <div class="col-md-7">
                                                <input type="text" id="customer_code" name="customer_code"
                                                    {if $merchant_setting.customer_auto_generate==0} required 
                                                    {else}
                                                    readonly value="Auto generate" {/if}class="form-control">
                                                <input type="hidden" id="customer_code" name="auto_generate"
                                                    value="{$merchant_setting.customer_auto_generate}">
                                            </div>
                                            <div class="col-md-3">

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label
                                                class="control-label col-md-4">{$customer_default_column.customer_name|default:'Customer name'}<span
                                                    class="required">* </span></label>
                                            <div class="col-md-7">
                                                <input type="text" name="customer_name" required value=""
                                                    class="form-control">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-4">Email<span class="required">
                                                </span></label>
                                            <div class="col-md-7">
                                                <input type="email" name="email" value="" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Mobile<span class="required">
                                                </span></label>
                                            <div class="col-md-7">
                                                <input type="text" {$validate.mobile} name="mobile" value=""
                                                    class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Address<span class="required">
                                                </span></label>
                                            <div class="col-md-7">
                                                <textarea value="" name="address" class="form-control"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">City<span class="required">
                                                </span></label>
                                            <div class="col-md-7">
                                                <input type="text" name="city" value="" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">State<span class="required">
                                                </span></label>
                                            <div class="col-md-7">
                                                <input type="text" name="state" value="" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Zipcode<span class="required">
                                                </span></label>
                                            <div class="col-md-7">
                                                <input type="text" name="zipcode" value="" class="form-control">
                                            </div>
                                        </div>
                                    </div>


                                    <div class="loading" id="loader" style="display: none;">Loading&#8230;</div>
                                </div>
                                <!-- End profile details -->
                            </div>

                            <h4 class="modal-title">Custom Fields </h4>

                            <div class="row">
                                <div class="col-md-6">
                                    {foreach from=$column item=v}
                                        {if $v.position=='L'}
                                            <div class="form-group">
                                                <input name="column_id[]" type="hidden" value="{$v.column_id}">
                                                <label class="control-label col-md-4">{$v.column_name}<span
                                                        class="required"></span></label>
                                                <div class="col-md-7">
                                                    {if $v.column_datatype=='textarea'}
                                                        <textarea maxlength="500" class="form-control"
                                                            name="column_value[]"></textarea>
                                                    {elseif $v.column_datatype=='date'}
                                                        <input class="form-control form-control-inline date-picker"
                                                            autocomplete="off" data-date-format="dd M yyyy" name="column_value[]"
                                                        type="text" /> {elseif $v.column_datatype=='number'}
                                                        <input type="number" maxlength="100" class="form-control" value=""
                                                            name="column_value[]">
                                                    {else}
                                                        <input maxlength="100" type="text" class="form-control"
                                                            name="column_value[]">
                                                    {/if}
                                                </div>
                                            </div>
                                        {/if}
                                    {/foreach}
                                </div>


                                <div class="col-md-6">
                                    {foreach from=$column item=v}
                                        {if $v.position=='R'}
                                            <div class="form-group">
                                                <input name="column_id[]" type="hidden" value="{$v.column_id}">
                                                <label class="control-label col-md-4">{$v.column_name}<span
                                                        class="required"></span></label>
                                                <div class="col-md-7">
                                                    {if $v.column_datatype=='textarea'}
                                                        <textarea maxlength="500" class="form-control"
                                                            name="column_value[]"></textarea>
                                                    {elseif $v.column_datatype=='date'}
                                                        <input class="form-control form-control-inline date-picker"
                                                            autocomplete="off" data-date-format="dd M yyyy" name="column_value[]"
                                                        type="text" /> {elseif $v.column_datatype=='number'}
                                                        <input type="number" maxlength="100" class="form-control" value=""
                                                            name="column_value[]">
                                                    {else}
                                                        <input maxlength="100" type="text" class="form-control"
                                                            name="column_value[]">
                                                    {/if}
                                                </div>
                                            </div>
                                        {/if}
                                    {/foreach}
                                </div>
                            </div>


                        </div>


                    </div>
                </div>

                <div class="modal-footer">
                    <input type="hidden" id="template_id" name="template_id" value="{$info.template_id}" />
                    <input type="hidden" id="customer_id_" name="customer_id" value="" />
                    <button type="submit" onclick="return display_warning();" class="btn blue">Save</button>
                    <button type="button" class="btn default" id="closebutton" data-dismiss="modal">Close</button>
                </div>
            </div>
    </form>
    <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
</div>


<div class="modal fade" id="basic" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Replace customer</h4>
            </div>
            <div class="modal-body">
                Are you sure you want to replace <span id="totalchecked"></span> customer records with newly entered
                customer data?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn default" data-dismiss="modal">Close</button>
                <a href="" onclick="return updatemultiCustomer();" class="btn blue" data-dismiss="modal">Confirm</a>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>


<script>
    function isFixed(id) {
        if (id == 1) {
            $("#fixed_div").slideDown(200).fadeIn();
            $("#per_div").slideDown(200).fadeOut();
        } else {
            $("#per_div").slideDown(200).fadeIn();
            $("#fixed_div").slideDown(200).fadeOut();
        }
    }
</script>