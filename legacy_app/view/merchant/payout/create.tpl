
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <h3 class="page-title">Create Beneficiary&nbsp;</h3>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12">
            {if isset($haserrors)}
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
            <div class="portlet light bordered">
                <div class="portlet-body form">
                    <!--<h3 class="form-section">Profile details</h3>-->
                    <form action="/merchant/payout/beneficiarysave" method="post" id="submit_form" class="form-horizontal form-row-sepe">
                        <div class="form-body">
                            <!-- Start profile details -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="alert alert-danger display-none">
                                        <button class="close" data-dismiss="alert"></button>
                                        You have some form errors. Please check below.
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Beneficiary type <span class="required">*
                                            </span></label>
                                        <div class="col-md-4">
                                            <select class="form-control" data-placeholder="Beneficiary type" required="" name="type" >
                                                <option value="">Select type</option>
                                                <option value="Customer">Customer</option>
                                                <option value="Employee">Employee</option>
                                                <option value="Franchise">Franchise</option>
                                                <option value="Vendor">Vendor</option>
                                                <option value="Other">Other</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Name <span class="required">*
                                            </span></label>
                                        <div class="col-md-4">
                                            <input type="text" required  maxlength="100" name="vendor_name" {$validate.name} class="form-control" value="{$post.vendor_name}">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-4">Email id <span class="required">*
                                            </span></label>
                                        <div class="col-md-4">
                                            <input type="email" required=""  id="f_email" maxlength="250" name="email" class="form-control" value="{$post.email}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Mobile no<span class="required">*
                                            </span></label>
                                        <div class="col-md-4">
                                            <input type="text"  maxlength="12" required {$validate.mobile} name="mobile" class="form-control" value="{$post.mobile}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Bank Account<span class="required">*
                                            </span></label>
                                        <div class="col-md-4">
                                            <input type="text" required maxlength="20" name="account_number" pattern="[0-9]"  class="form-control" value="{$post.account_number}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">IFSC code <span class="required">*
                                            </span></label>
                                        <div class="col-md-4">
                                            <input type="text" required=""  maxlength="20" name="ifsc_code" class="form-control" value="{$post.ifsc_code}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Address <span class="required">*
                                            </span></label>
                                        <div class="col-md-4">
                                            <textarea type="text" required name="address" maxlength="250" class="form-control">{$post.address}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">City <span class="required">
                                            </span></label>
                                        <div class="col-md-4">
                                            <input type="text"  maxlength="45" name="city" {$validate.city} class="form-control" value="{$post.city}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">State <span class="required">
                                            </span></label>
                                        <div class="col-md-4">
                                            <input type="text"  maxlength="45" name="state" {$validate.city} class="form-control" value="{$post.state}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Zipcode <span class="required">
                                            </span></label>
                                        <div class="col-md-4">
                                            <input type="text"  maxlength="6" name="zipcode" {$validate.zipcode} class="form-control" value="{$post.zipcode}">
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>					
                        <!-- End profile details -->
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-offset-5 col-md-6">
                                    <input type="submit" value="Save" class="btn blue"/>
                                    <a href="/merchant/beneficiary/viewlist" class="btn btn-link">Cancel</a>
                                </div>
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
                                                <div class="input-group">
                                                    <input type="email" name="email" id="defaultemail" value="" class="form-control" >
                                                    <span class="input-group-btn">
                                                        <label> <input id="def_email" onchange="defaultcustomeremail();" type="checkbox">Default</label>>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Mobile<span class="required"> </span></label>
                                            <div class="col-md-7">
                                                <div class="input-group">
                                                    <input type="text" {$validate.mobile} id="defaultmobile"  name="mobile" value="{$post.mobile}" class="form-control" >
                                                    <span class="input-group-btn">
                                                        <label> <input id="def_mobile" onchange="defaultcustomermobile();" type="checkbox">Default</label>>
                                                    </span>
                                                </div>
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
                    <button type="submit" onclick="return display_warning();" class="btn blue">Save</button>
                    <button type="button" class="btn default" id="closebutton" data-dismiss="modal">Close</button>
                </div>
            </div>
    </form>		
    <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
</div>