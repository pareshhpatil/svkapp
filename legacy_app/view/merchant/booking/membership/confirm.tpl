
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="page-bar">
        {include file="../../../common/breadcumbs.tpl" title={$title} links=$links}
    </div>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row no-margin">
        <div class="col-md-1"></div>
        <div class="col-md-11">
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
            <form action="/merchant/bookings/membershiprespond" onsubmit="document.getElementById('submitbtn').disabled = true;" method="post">
                {CSRF::create('booking_respond')}
                <div class="portlet light bordered" style="max-width: 900px;text-align: left;">
                    <div class="portlet-title">
                        <div class="col-md-9">
                            <div class="caption font-blue" style="">
                                <span class="caption-subject bold uppercase"> <h2>Membership booking</h2></span>
                            </div>
                        </div>
                        
                        <br>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group">
                                <label class="control-label col-md-3">Select Membership <span class="required">* </span></label>
                                <div class="col-md-7">
                                    <div class="">
                                        <select name="membership_id" onchange="membershipCost(this.value);" required class="form-control select2me" data-placeholder="Select...">
                                            <option value=""></option>
                                            {foreach from=$list item=v}
                                                <option  value="{$v.membership_id}" >{$v.category_name} | {$v.title} | {$v.days} Days</option>
                                            {/foreach}
                                        </select>
                                    </div>	
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <br>
                            <div class="form-group">
                                <label class="control-label col-md-3">Select  Customer <span class="required">* </span></label>
                                <div class="col-md-7">
                                    <div class="">
                                        <select name="customer_id" id="customer_id" title="Create new customer" required class="form-control select2me" data-placeholder="Select...">
                                            <option value=""></option>
                                            {foreach from=$customer_list item=v}
                                                <option  value="{$v.customer_id}" >{$v.customer_code} | {$v.name}</option>
                                            {/foreach}
                                        </select>
                                    </div>	
                                </div>
                                <div class="col-md-1 no-margin no-padding">
                                    <a data-toggle="modal" href="#custom" class="btn green"><i class="fa fa-plus"></i></a>
                                </div>
                            </div>

                        </div>
                    </div>
                        <div class="row">
                            <h4> Enter transaction details</h4>
                            <div class="col-xs-12">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>
                                                Mode <span class="required">* </span>
                                            </th>
                                            <th>
                                                Transaction detail 
                                            </th>
                                            <th class="hidden-480">
                                                Amount <span class="required">* </span>
                                            </th>
                                            <th class="hidden-480">
                                               Booking Date <span class="required">* </span>
                                            </th>
                                            <th class="hidden-480">
                                                Bank name
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <select class="form-control input-sm select2me"  name="response_type" onchange="responseType(this.value);" data-placeholder="Select type">
                                                    <option value="1">Wire transfer</option>
                                                    <option value="2">Cheque</option>
                                                    <option value="3">Cash</option>
                                                    <option value="5">Online Payment</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input class="form-control form-control-inline input-sm" name="bank_transaction_no" id="bank_transaction_no" type="text" value="" placeholder="Bank ref number"/>
                                                <input class="form-control form-control-inline input-sm" id="cheque_no" name="cheque_no" style="display: none;"  type="text" value="" placeholder="Cheque no"/>
                                                <input class="form-control form-control-inline input-sm" name="cash_paid_to" id="cash_paid_to" style="display: none;" type="text" value="" placeholder="Cash paid to"/>
                                            </td>
                                            <td class="hidden-480">
                                                <input class="form-control form-control-inline input-sm" required name="amount" type="number" id="absolute_cost" value="{$absolute_cost}" placeholder="Amount"/>
                                            </td>
                                            <td class="hidden-480">
                                                <input class="form-control form-control-inline input-sm date-picker"  autocomplete="off" data-date-format="dd M yyyy"  required name="date" type="text" value="" placeholder="Date"/>
                                            </td>
                                            <td class="hidden-480">
                                                <select class="form-control input-sm select2me" id="bank_name" style="max-width: 300px;" name="bank_name" data-placeholder="Select bank name">
                                                    <option value=""></option>
                                                    {html_options values=$bank_value selected={$bank_selected} output=$bank_value}
                                                </select>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    <div class="row">
                        <div class="col-md-8">
                            <br>
                            <label> </label>
                        </div>
                        <div class="col-md-4">
                            <input name="coupon_id" type="hidden"  value="{$coupon_id}" />
                            <br>
                            <button type="submit" id="submitbtn" class="btn blue pull-right">Click here to place the order</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>
</div>

<div class="modal fade bs-modal-lg in" id="custom" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form action="" method="post" id="customerForm"  class="form-horizontal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" id="closebutton1" data-dismiss="modal" aria-hidden="true"></button>
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
                                <div  class="alert alert-block alert-warning fade in">

                                    <h4 class="alert-heading">Warning!</h4>
                                    <p id="ex_message">
                                        This Email ID, Mobile Number already exists in your customer database. You could either replace this record or create a new entry with same values. 
                                        Alternatively you can edit the records entered from the Customer create screen below.
                                    </p>
                                    <br>
                                    <br>
                                    <p class="pull-right" style="margin-top: -25px;">
                                        <button type="submit" id="ex_delete" onclick="return deleteCustomer();" class="btn red btn-sm" >
                                            Disable Existing & Add New  </button>
                                        <button type="submit" id="ex_add" onclick="return saveCustomer();" class="btn green btn-sm" >
                                            Save As New </button>
                                        <a  onclick="return confirmreplace();" class="btn blue btn-sm" >
                                            Replace existing customer data</a>
                                        <a   href="#basic" id="confirmm" data-toggle="modal">
                                        </a>
                                        <a onclick="document.getElementById('closebutton1').click();" class="btn default btn-sm"> Cancel </a>
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
                                    <button class="close" onclick="document.getElementById('errorshow').style.display = 'none';"></button>
                                    <p id="error_display">Please correct the below errors to complete registration.</p>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-4">{$customer_default_column.customer_code|default:'Customer code'}<span class="required">* </span></label>
                                            <div class="col-md-7">
                                                <input type="text" id="customer_code" name="customer_code" {if $merchant_setting.customer_auto_generate==0} required {else} readonly  value="Auto generate"  {/if}class="form-control" >
                                                <input type="hidden" id="customer_code" name="auto_generate" value="{$merchant_setting.customer_auto_generate}" >
                                            </div>
                                            <div class="col-md-3">

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-4">{$customer_default_column.customer_name|default:'Customer name'}
                                            <span class="required">* </span></label>
                                            <div class="col-md-7">
                                                <input type="text" name="customer_name" required value="" class="form-control" >
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-4">Email<span class="required"> </span></label>
                                            <div class="col-md-7">
                                                <input type="email" name="email"  value="" class="form-control" >
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Mobile<span class="required"> </span></label>
                                            <div class="col-md-7">
                                                <input type="text" {$validate.mobile} name="mobile"  value="" class="form-control" >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Address<span class="required"> </span></label>
                                            <div class="col-md-7">
                                                <textarea  value="" name="address" class="form-control" ></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">City<span class="required"> </span></label>
                                            <div class="col-md-7">
                                                <input type="text" name="city"  value="" class="form-control" >
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">State<span class="required"> </span></label>
                                            <div class="col-md-7">
                                                <input type="text" name="state"  value="" class="form-control" >
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Zipcode<span class="required"> </span></label>
                                            <div class="col-md-7">
                                                <input type="text" name="zipcode"  value="" class="form-control" >
                                            </div>
                                        </div>
                                    </div>


                                    <div class="loading" id="loader" style="display: none;">Loading&#8230;</div>
                                </div>
                                <!-- End profile details -->
                            </div>

                            <h4 class="modal-title">Custom Fields </h4> 

                            <div  class="row">
                                <div class="col-md-6">
                                    {foreach from=$column item=v}
                                        {if $v.position=='L'}
                                            <div  class="form-group">
                                                <input name="column_id[]" type="hidden" value="{$v.column_id}">
                                                <label class="control-label col-md-4">{$v.column_name}<span class="required"></span></label>
                                                <div class="col-md-7">
                                                    {if $v.column_datatype=='textarea'}
                                                        <textarea maxlength="500" class="form-control" name="column_value[]"></textarea>
                                                    {elseif $v.column_datatype=='date'}
                                                        <input class="form-control form-control-inline date-picker"   autocomplete="off" data-date-format="dd M yyyy"  name="column_value[]" type="text" />                                                      {elseif $v.column_datatype=='number'}
                                                        <input type="number" maxlength="100" class="form-control" value="" name="column_value[]">
                                                    {else}
                                                        <input maxlength="100" type="text" class="form-control" name="column_value[]">
                                                    {/if}
                                                </div>
                                            </div>
                                        {/if}
                                    {/foreach}
                                </div>


                                <div class="col-md-6">
                                    {foreach from=$column item=v}
                                        {if $v.position=='R'}
                                            <div  class="form-group">
                                                <input name="column_id[]" type="hidden" value="{$v.column_id}">
                                                <label class="control-label col-md-4">{$v.column_name}<span class="required"></span></label>
                                                <div class="col-md-7">
                                                    {if $v.column_datatype=='textarea'}
                                                        <textarea maxlength="500" class="form-control" name="column_value[]"></textarea>
                                                    {elseif $v.column_datatype=='date'}
                                                        <input class="form-control form-control-inline date-picker"   autocomplete="off" data-date-format="dd M yyyy"  name="column_value[]" type="text" />                                                      {elseif $v.column_datatype=='number'}
                                                        <input type="number" maxlength="100" class="form-control" value="" name="column_value[]">
                                                    {else}
                                                        <input maxlength="100" type="text" class="form-control" name="column_value[]">
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
                    <input type="hidden" id="template_id" name="template_id" value="{$info.template_id}"/> 
                    <input type="hidden" id="customer_id_" name="customer_id" value=""/> 
                    <button type="button" class="btn default" id="closebutton" data-dismiss="modal">Close</button>
                    <button type="submit" onclick="return display_warning();" class="btn blue">Save</button>
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
                <button type="button"  class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Replace customer</h4>
            </div>
            <div class="modal-body">
                Are you sure you want to replace <span id="totalchecked"></span> customer records with newly entered customer data?
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