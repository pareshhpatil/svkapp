<div class="page-content">
    <div class="page-bar">
        {include file="../../common/breadcumbs.tpl" title={$breadcumb_title} links=$links}
    </div>
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
                    <form action="/merchant/franchise/franchisesave" method="post" id="submit_form"
                        class="form-horizontal form-row-sepe">
                        {CSRF::create('franchise_save')}
                        <div class="form-body">
                            <!-- Start profile details -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="alert alert-danger display-none">
                                        <button class="close" data-dismiss="alert"></button>
                                        You have some form errors. Please check below.
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Franchise company name <span
                                                class="required">*
                                            </span></label>
                                        <div class="col-md-4">
                                            <input type="text" required maxlength="100" name="franchise_name"
                                                {$validate.name} class="form-control" value="{$post.franchise_name}">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-4">Contact person name <span
                                                class="required">*
                                            </span></label>
                                        <div class="col-md-4">
                                            <input type="text" required maxlength="100" name="contact_person_name"
                                                {$validate.name} class="form-control"
                                                value="{$post.contact_person_name}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Email id <span class="required">
                                            </span></label>
                                        <div class="col-md-4">
                                            <input type="email" id="f_email" maxlength="250" name="email"
                                                class="form-control" value="{$post.email}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Mobile no<span class="required">*
                                            </span></label>
                                        <div class="col-md-4">
                                            <input type="text" maxlength="12" required {$validate.mobile} name="mobile"
                                                class="form-control" value="{$post.mobile}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Address <span class="required">*
                                            </span></label>
                                        <div class="col-md-4">
                                            <textarea type="text" required name="address" maxlength="250"
                                                class="form-control">{$post.address}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">GSTIN <span class="required">
                                            </span></label>
                                        <div class="col-md-4">
                                            <input type="text" maxlength="20" name="gst" {$validate.gst_number}
                                                class="form-control" value="{$post.gst}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">PAN<span class="required">
                                            </span></label>
                                        <div class="col-md-4">
                                            <input type="text" maxlength="20" name="pan" {$validate.pan}
                                                class="form-control" value="{$post.pan}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Aadhaar Number <span class="required">
                                            </span></label>
                                        <div class="col-md-4">
                                            <input type="text" maxlength="20" name="adhar_card" class="form-control"
                                                value="{$post.adhar_card}">
                                        </div>
                                    </div>

                                    <div id="logindiv" {if $post.is_login==1}{else}style="display: none;" {/if}>
                                        <hr>
                                        <h3>Franchise login</h3>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Login Email<span class="required">*
                                                </span></label>
                                            <div class="col-md-4">
                                                <input type="email" id="login_email" name="login_email"
                                                    class="form-control" value="{$post.login_email}">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Password <span class="required">*
                                                </span></label>
                                            <div class="col-md-4">
                                                <input type="password" AUTOCOMPLETE='OFF' id="submit_form_password"
                                                    name="password" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Confirm Password <span
                                                    class="required">*
                                                </span></label>
                                            <div class="col-md-4">
                                                <input type="password" {$validate.password} AUTOCOMPLETE='OFF'
                                                    name="rpassword" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Role<span class="required">*
                                                </span></label>
                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <select required id="role" class="form-control" name="role">
                                                        {foreach from=$roles key=k item=v}
                                                            <option value="{$v.role_id}">{$v.name}</option>
                                                        {/foreach}
                                                    </select>
                                                    <span class="input-group-btn">
                                                        <a class="btn green" data-toggle="modal" href="#roles">
                                                            <i class="fa fa-plus"></i></a>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Select Customer Group</label>
                                            <div class="col-md-4">
                                                <select id="select2_sample2" data-placeholder="Select Customer Group"
                                                    name="group[]" class="form-control select2me">
                                                    <option value=""></option>
                                                    {foreach from=$customer_group item=v}
                                                        <option value="{$v.group_id}">{$v.group_name}</option>
                                                    {/foreach}
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    {if $food_franchise==true || $non_brand_food_franchise==true}
                                        <h3>Sale configuration</h3>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Franchise store ID <span
                                                    class="required">*
                                                </span></label>
                                            <div class="col-md-4">
                                                <input type="text" required name="franchise_code" class="form-control"
                                                    value="{$post.franchise_code}">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Franchise fee commission (%) <span
                                                    class="required">*
                                                </span></label>
                                            <div class="col-md-4">
                                                <input type="number" required step="0.01" max="100"
                                                    name="franchise_fee_comm" class="form-control"
                                                    value="{$post.franchise_fee_comm}">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Franchise fee waiver (%) <span
                                                    class="required">*
                                                </span></label>
                                            <div class="col-md-4">
                                                <input type="number" required step="0.01" max="100"
                                                    name="franchise_fee_waiver" class="form-control"
                                                    value="{$post.franchise_fee_waiver}">
                                            </div>
                                        </div>
                                        {if $non_brand_food_franchise}
                                            <div class="form-group">
                                                <label class="control-label col-md-4">Non brand fee commission (%) <span
                                                        class="required">*
                                                    </span></label>
                                                <div class="col-md-4">
                                                    <input type="number" required step="0.01" max="100"
                                                        name="non_brand_franchise_fee_comm" class="form-control"
                                                        value="{$post.non_brand_franchise_fee_comm}">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-4">Non brand fee waiver (%) <span
                                                        class="required">*
                                                    </span></label>
                                                <div class="col-md-4">
                                                    <input type="number" required step="0.01" max="100"
                                                        name="non_brand_franchise_fee_waiver" class="form-control"
                                                        value="{$post.non_brand_franchise_fee_waiver}">
                                                </div>
                                            </div>
                                        {/if}
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Due penalty (%) <span class="required">*
                                                </span></label>
                                            <div class="col-md-4">
                                                <input type="number" required step="0.01" max="100" name="due_penalty"
                                                    class="form-control" value="{$post.due_penalty}">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Minimum penalty (Rs.) <span
                                                    class="required">*
                                                </span></label>
                                            <div class="col-md-4">
                                                <input type="number" required step="0.01" name="min_penalty"
                                                    class="form-control" value="{$post.min_penalty}">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Default sale (Rs.) <span
                                                    class="required">*
                                                </span></label>
                                            <div class="col-md-4">
                                                <input type="number" required step="0.01" name="default_sale"
                                                    class="form-control" value="{$post.default_sale}">
                                                <input type="hidden" name="food_franchise" value="1">
                                                <input type="hidden" name="customer_id" value="0">
                                            </div>
                                        </div>
                                    {else}
                                        <h4 class="form-section">
                                            Enable Franchise login&nbsp;
                                            <input type="checkbox" id="issupplier" {if $post.is_login==1}checked{/if}
                                                name="is_login" onchange="showfranchiselogin(this.checked);" value="1"
                                                class="make-switch" data-on-text="&nbsp;Enabled&nbsp;&nbsp;"
                                                data-off-text="&nbsp;Disabled&nbsp;">
                                        </h4>
                                    {/if}

                                    <h3>Secondary contact</h3>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Contact #2 name <span class="required">
                                            </span></label>
                                        <div class="col-md-4">
                                            <input type="text" maxlength="100" name="contact_person_name2"
                                                {$validate.name_text} class="form-control"
                                                value="{$post.contact_person_name2}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Contact #2 email <span class="required">
                                            </span></label>
                                        <div class="col-md-4">
                                            <input type="email" maxlength="250" name="email2" class="form-control"
                                                value="{$post.email2}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Contact #2 mobile <span class="required">
                                            </span></label>
                                        <div class="col-md-4">
                                            <input type="text" maxlength="12" name="mobile2" {$validate.mobile_number}
                                                class="form-control" value="{$post.mobile2}">
                                        </div>
                                    </div>


                                    <hr>
                                    <h3>Bank details</h3>


                                    <div class="form-group">
                                        <label class="control-label col-md-4">Bank account holder name <span
                                                class="required">
                                            </span></label>
                                        <div class="col-md-4">
                                            <input type="text" maxlength="100" name="account_holder_name"
                                                {$validate.name_text} class="form-control"
                                                value="{$post.account_holder_name}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Account number <span class="required">
                                            </span></label>
                                        <div class="col-md-4">
                                            <input type="text" maxlength="20" name="account_number" pattern="[0-9]"
                                                class="form-control" value="{$post.account_number}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Bank name <span class="required">
                                            </span></label>
                                        <div class="col-md-4">
                                            <input type="text" maxlength="45" name="bank_name" {$validate.name_text}
                                                class="form-control" value="{$post.bank_name}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Bank account type <span class="required">
                                            </span></label>
                                        <div class="col-md-4">
                                            <select class="form-control" name="account_type">
                                                <option value="">Select account type</option>
                                                <option value="Current">Current</option>
                                                <option value="Saving">Saving</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">IFSC code <span class="required">
                                            </span></label>
                                        <div class="col-md-4">
                                            <input type="text" maxlength="20" name="ifsc_code" {$validate.name_text}
                                                class="form-control" value="{$post.ifsc_code}">
                                        </div>
                                    </div>

                                    <hr>
                                    <h3>Franchise Settlements</h3>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Online settlement via Swipez&nbsp; <span
                                                class="required">
                                            </span></label>
                                        <div class="col-md-2">
                                            <input type="checkbox" {if $enable_online_settlement==0} disabled=""
                                                {/if}id="issupplier" name="online_settlement"
                                                onchange="vendorOnlineSettlement(this.checked);" value="1"
                                                class="make-switch" data-on-text="&nbsp;Enabled&nbsp;&nbsp;"
                                                data-off-text="&nbsp;Disabled&nbsp;">
                                        </div>
                                        {if $enable_online_settlement==0}
                                            <div class="col-md-6">
                                                <div class="alert alert-info">
                                                    <strong>To enable online funds transfer to franchise/vendor contact
                                                        support@swipez.in</strong>
                                                </div>
                                            </div>
                                        {/if}
                                    </div>
                                    <div id="onlinediv" style="display: none;">
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Commision type <span class="required">
                                                </span>
                                            </label>
                                            <div class="col-md-2">
                                                <select name="commision_type"
                                                    onchange="vendorSettlementType(this.value);" class="form-control">
                                                    <option value="1">Percentage</option>
                                                    <option value="2">Fixed</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group" id="commision_per_div">
                                            <label class="control-label col-md-4">Commision (%) <span class="required">
                                                </span>
                                            </label>
                                            <div class="col-md-2" id="commision_per_div">
                                                <input type="number" max="100" step="0.01" name="commision_percent"
                                                    {$validate.percentage} class="form-control"
                                                    value="{$post.commision_percent}">
                                            </div>
                                        </div>
                                        <div class="form-group" id="commision_amt_div" style="display: none;">
                                            <label class="control-label col-md-4">Commision (Rs.) <span
                                                    class="required">
                                                </span>
                                            </label>
                                            <div class="col-md-2">
                                                <input type="number" step="0.01" name="commision_amount"
                                                    {$validate.amount} class="form-control"
                                                    value="{$post.commision_amount}">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Settlement type<span class="required">
                                                </span>
                                            </label>
                                            <div class="col-md-2">
                                                <select name="settlement_type" class="form-control">
                                                    <option value="1">Manual</option>
                                                    <option value="2">Automatic</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End profile details -->
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="pull-right">
                                        <a href="/merchant/franchise/viewlist" class="btn btn-default">Cancel</a>
                                        <input type="submit" value="Save" class="btn blue" />
                                    </div>
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


<div class="modal fade" id="roles" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="" id="roleForm" method="post" onsubmit="return false;" class="form-horizontal form-row-sepe">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Add new role</h4>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-danger display-none">
                                <button class="close" data-dismiss="alert"></button>
                                You have some form errors. Please check below.
                            </div>
                            <div class="alert alert-danger" style="display: none;" id="errorshow">
                                <button class="close"
                                    onclick="document.getElementById('errorshow').style.display = 'none';"></button>
                                <p id="error_display">Please correct the below errors to complete registration.</p>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Role name <span class="required">*
                                    </span></label>
                                <div class="col-md-6">
                                    <input type="text" required name="role_name" class="form-control"
                                        value="{$post.role_name}">
                                </div>
                            </div>

                            <div class="form-group">

                                <div class="col-md-12">

                                    <table class="table table-bordered table-hover" id="">
                                        <thead>
                                            <tr>
                                                <th rowspan="2" class="td-c">
                                                    ID
                                                </th>
                                                <th rowspan="2" class="td-c">
                                                    Functions
                                                </th>
                                                <th colspan="2" class="td-c"
                                                    style="border-bottom: 1px solid lightgrey;">
                                                    ?
                                                </th>
                                            </tr>
                                            <tr>

                                                <th class="td-c">
                                                    View
                                                </th>
                                                <th class="td-c">
                                                    Create/<br>
                                                    Edit
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {foreach from=$list item=v}
                                                <tr>
                                                    <td class="td-c">
                                                        {$v.controller_id}
                                                    </td>
                                                    <td class="td-c">
                                                        {$v.display_name}
                                                        <span class="popovers" data-container="body" data-trigger="hover"
                                                            data-content="{$v.description}"><i class="fa fa-info-circle"
                                                                style="color: #275770;"></i></span>
                                                    </td>

                                                    <td class="td-c">
                                                        {if $v.is_default==1}
                                                            <input checked="" disabled value="{$v.controller_id}"
                                                                type="checkbox">
                                                            <input checked name="view[]" value="{$v.controller_id}"
                                                                type="hidden">
                                                        {else}
                                                            <input name="view[]" value="{$v.controller_id}" type="checkbox">
                                                        {/if}

                                                    </td>
                                                    <td class="td-c">
                                                        {if $v.is_default==1}
                                                            <input checked="" disabled value="{$v.controller_id}"
                                                                type="checkbox">
                                                            <input checked name="edit[]" value="{$v.controller_id}"
                                                                type="hidden">
                                                        {else}
                                                            <input name="edit[]" value="{$v.controller_id}" type="checkbox">
                                                        {/if}

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
                <div class="modal-footer">
                    <input type="submit" onclick="saveRole();" id="btnSubmit" class="btn blue" value="Save" />
                    <button type="button" class="btn default" id="closebutton" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>