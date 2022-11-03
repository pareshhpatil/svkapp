<div class="page-content">
    <div class="page-bar">
        {include file="../../common/breadcumbs.tpl" title={$title} links=$links}
    </div>
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
                    <form action="/merchant/vendor/vendorsave" method="post" id="submit_form"
                        class="form-horizontal form-row-sepe">
                        {CSRF::create('vendor_save')}
                        <div class="form-body">
                            <!-- Start profile details -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="alert alert-danger display-none">
                                        <button class="close" data-dismiss="alert"></button>
                                        You have some form errors. Please check below.
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Vendor code <span class="required">
                                            </span></label>
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <input id="vendor_code_auto_generate" name="vendor_code" type="text"
                                                    {if $vendor_code_auto_generate==1} readonly="" value="Auto generate"
                                                    {/if} class="form-control">
                                                <span class="input-group-btn">
                                                    <a data-toggle="modal" href="#customer"
                                                        class="btn btn-icon-only blue"><i class="icon-settings">
                                                        </i></a>
                                                </span>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Vendor name <span class="required">*
                                            </span></label>
                                        <div class="col-md-4">
                                            <input type="text" required maxlength="100" name="vendor_name"
                                                {$validate.name} class="form-control" value="{$post.vendor_name}">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-4">Email id <span class="required">*
                                            </span></label>
                                        <div class="col-md-4">
                                            <input type="email" required id="f_email" maxlength="250" name="email"
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
                                        <label class="control-label col-md-4">State <span class="required">*
                                            </span></label>
                                        <div class="col-md-4">
                                            <select required name="state" class="form-control select2me"
                                                data-placeholder="Select...">
                                                <option value="">Select State</option>
                                                {foreach from=$state_code item=v}
                                                    <option value="{$v.config_value}">{$v.config_value}</option>
                                                {/foreach}
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">City <span class="required">
                                            </span></label>
                                        <div class="col-md-4">
                                            <input type="text" maxlength="45" name="city" {$validate.city}
                                                class="form-control" value="{$post.city}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Zipcode <span class="required">
                                            </span></label>
                                        <div class="col-md-4">
                                            <input type="text" maxlength="6" name="zipcode" {$validate.zipcode}
                                                class="form-control" value="{$post.zipcode}">
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
                                    <h3>Create Login</h3>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Enable Vendor login <span
                                                class="required">
                                            </span></label>
                                        <div class="col-md-2">
                                            <input type="checkbox" {if $post.is_login==1}checked{/if} name="is_login"
                                                onchange="vendorLoginCheck(this.checked);" value="1" class="make-switch"
                                                data-on-text="&nbsp;Enabled&nbsp;&nbsp;"
                                                data-off-text="&nbsp;Disabled&nbsp;">
                                        </div>
                                    </div>
                                    <div id="logindiv" style="display: none;">
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Invoice format <span class="required">
                                                </span>
                                            </label>
                                            <div class="col-md-2">
                                                <select name="template_id" class="form-control">
                                                    <option value="">Select format</option>
                                                    {foreach from=$template_list item=v}
                                                        <option value="{$v.template_id}">{$v.template_name}</option>
                                                    {/foreach}
                                                </select>
                                            </div>
                                        </div>
                                        <hr>
                                    </div>
                                    <h3>Enable online transfer</h3>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Online payment transfer via Swipez&nbsp;
                                            <span class="required">
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
                                        <hr>
                                        <h3>Bank details</h3>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Bank account holder name <span
                                                    class="required">*
                                                </span></label>
                                            <div class="col-md-4">
                                                <input type="text" id="acc_name" maxlength="100"
                                                    name="account_holder_name" {$validate.name_text}
                                                    class="form-control" value="{$post.account_holder_name}">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Account number <span
                                                    class="required">*
                                                </span></label>
                                            <div class="col-md-4">
                                                <input type="text" id="acc_no" maxlength="20" name="account_number"
                                                    pattern="[0-9]" class="form-control" value="{$post.account_number}">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Bank name <span class="required">*
                                                </span></label>
                                            <div class="col-md-4">
                                                <input type="text" id="bank_name" maxlength="45" name="bank_name"
                                                    {$validate.name_text} class="form-control"
                                                    value="{$post.bank_name}">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Bank account type <span
                                                    class="required">*
                                                </span></label>
                                            <div class="col-md-4">
                                                <select class="form-control" id="acc_type" name="account_type">
                                                    <option value="">Select account type</option>
                                                    <option value="Current">Current</option>
                                                    <option value="Saving">Saving</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">IFSC code <span class="required">*
                                                </span></label>
                                            <div class="col-md-4">
                                                <input type="text" id="ifsc_code" maxlength="20" name="ifsc_code"
                                                    class="form-control" value="{$post.ifsc_code}">
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Commision type <span class="required">
                                                </span>
                                            </label>
                                            <div class="col-md-2">
                                                <select name="commision_type"
                                                    onchange="vendorSettlementType(this.value);" class="form-control">
                                                    <option value="0">Not applicable</option>
                                                    <option value="1">Percentage</option>
                                                    <option value="2">Fixed</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group" style="display: none;" id="commision_div">
                                            <div class="form-group" id="commision_per_div">
                                                <label class="control-label col-md-4">Commision (%) <span
                                                        class="required">
                                                    </span>
                                                </label>
                                                <div class="col-md-2" id="commision_per_div">
                                                    <input type="number" max="100" value="0" step="0.01"
                                                        name="commision_percent" {$validate.percentage}
                                                        class="form-control" value="{$post.commision_percent}">
                                                </div>
                                            </div>
                                            <div class="form-group" id="commision_amt_div" style="display: none;">
                                                <label class="control-label col-md-4">Commision (Rs.) <span
                                                        class="required">
                                                    </span>
                                                </label>
                                                <div class="col-md-2">
                                                    <input type="number" step="0.01" value="0" name="commision_amount"
                                                        {$validate.amount} class="form-control"
                                                        value="{$post.commision_amount}">
                                                    <input type="hidden" name="settlement_type" value="1">
                                                </div>
                                            </div>
                                            <!--<div class="form-group">
                                                <label class="control-label col-md-4">Settlement type <span
                                                        class="required">
                                                    </span>
                                                </label>
                                                <div class="col-md-2">
                                                    <select name="settlement_type" class="form-control">
                                                        <option value="1">Manual</option>
                                                        <option value="2">Automatic</option>
                                                    </select>
                                                </div>
                                            </div>-->
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
                                        <a href="/merchant/vendor/viewlist" class="btn btn-default">Cancel</a>
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

<div class="modal fade" id="customer" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="seq_form" method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" id="closeauto" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Vendor code</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-body">
                                <div class="alert alert-danger display-hide">
                                    <button class="close" data-close="alert"></button>
                                    You have some form errors. Please check below.
                                </div>

                                <div class="form-group">
                                    <h5>Your Vendor code is on auto-generate mode to save your time. Are you sure about
                                        changing this setting?</h5>

                                </div>

                                <div class="form-group">
                                    <label for="autogen" class="col-md-12 control-label">
                                        <input type="radio" id="autogen" name="auto_generate" value="1"
                                            {if $vendor_code_auto_generate==1} checked="" {/if}> Continue
                                        auto-generating codes
                                    </label>
                                    <div class="col-md-8">
                                        <div class="col-md-7">
                                            <div class="form-group">
                                                <p>Prefix</p>
                                                <input type="text" name="prefix" id="prefix" maxlength="10"
                                                    value="{$prefix}" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <p>Number</p>
                                                <input type="text" name="prefix_val" value="{$prefix_val}"
                                                    class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="auto2" class="col-md-8 control-label">
                                        <input type="radio" class="icheck" id="auto2" name="auto_generate"
                                            {if $vendor_code_auto_generate!=1} checked="" {/if} value="0" /> I will add
                                        them manually each time
                                    </label>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="seq_id" name="seq_id" value="{$prefix_id}">
                    <button type="button" onclick="return saveVendorSequence();" class="btn blue">Save</button>
                    <button type="button" class="btn default" id="closebutton" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>