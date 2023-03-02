<div class="page-content">
    <div class="page-bar">
        {include file="../../common/breadcumbs.tpl" title={$title} links=$links}
        <a href="/merchant/customer/structure" class="btn green pull-right">Configure fields</a>
    </div>
    <form action="" method="post" id="submit_form" class="form-horizontal form-row-sepe">
        {CSRF::create('customer_create')}
        <!-- BEGIN PAGE HEADER-->

        <!-- END PAGE HEADER-->
        <!-- BEGIN PAGE CONTENT-->
        <div class="row">

            <div class="col-md-12">
                <div id="warning" style="display:none;" class="portlet ">
                    <div class="portlet-body">
                        <div class="alert alert-block alert-warning fade in">

                            <h4 class="alert-heading">Warning!</h4>
                            <p id="ex_message">
                                This Email ID, Mobile Number already exists in your customer database. You could either
                                replace this record or create a new entry with same values.
                                Alternatively you can edit the records entered from the Customer create screen below.
                            </p>
                            <br>
                            <p class="pull-right" style="margin-top: -22px;">
                                <button type="submit" id="ex_delete" onclick="return deleteCustomer();"
                                    class="btn red btn-sm">
                                    Disable Existing & Add New </button>
                                <button type="submit" id="ex_add" onclick="return saveCustomer();"
                                    class="btn green btn-sm">
                                    Save As New </button>
                                <a onclick="return confirmreplace();" class="btn green btn-sm">
                                    Replace existing customer data</a>
                                <a href="#basic" id="confirmm" data-toggle="modal">
                                </a>
                                <a href="/merchant/customer/create" class="btn btn-sm green"> Cancel </a>
                            </p>
                        </div>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="td-c">
                                        {$customer_default_column.customer_code|default:'Customer code'}
                                    </th>
                                    <th class="td-c">
                                        {$customer_default_column.customer_name|default:'Contact person name'}
                                    </th>
                                    <th class="td-c">
                                        {$customer_default_column.email|default:'Email'}
                                    </th>
                                    <th class="td-c">
                                        {$customer_default_column.mobile|default:'Mobile'}
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
                <div class="portlet light bordered">
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
                    {if isset($success)}
                        <div class="alert alert-success">
                            <button type="button" class="close" data-dismiss="alert"></button>
                            <strong>Success!</strong> {$success}
                        </div>
                    {/if}
                    <div class="alert alert-danger" style="display: none;" id="errorshow">
                        <button class="close"
                            onclick="document.getElementById('errorshow').style.display = 'none';"></button>
                        <p id="error_display">You have some form errors. Please check below.</p>
                    </div>
                    <div class="portlet-body form">

                        <input type="hidden" id="customer_id_" name="customer_id" value="" />
                        <h4 class="form-section">System Fields</h4>
                        <div class="form-body">
                            <!-- Start profile details -->

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label
                                            class="control-label col-md-5">{$customer_default_column.customer_code|default:'Customer code'}<span
                                                class="required">* </span></label>
                                        <div class="col-md-7">
                                            <input type="text" id="customer_code" name="customer_code"
                                                {if $merchant_setting.customer_auto_generate==0} required
                                                value="{$post.customer_code}" {else} readonly value="Auto generate"
                                                {/if}class="form-control">
                                            <input type="hidden" id="customer_code" name="auto_generate"
                                                value="{$merchant_setting.customer_auto_generate}">
                                        </div>

                                    </div>
                                    {foreach from=$column item=v}
                                        {if $v.column_datatype=='company_name'}
                                            <div class="form-group">
                                                <label class="control-label col-md-5">{$v.column_name}<span
                                                        class="required"></span></label>
                                                <div class="col-md-7">
                                                    <input type="text" maxlength="100" class="form-control" value="{$v.value}"
                                                            name="company_name">
                                                </div>
                                            </div>
                                        {/if}
                                    {/foreach}

                                    <div class="form-group">
                                        <label
                                            class="control-label col-md-5">{$customer_default_column.customer_name|default:'Contact person name'}<span
                                                class="required">*
                                            </span></label>
                                        <div class="col-md-7">
                                            <input required type="text" onpaste="return false;"
                                                onkeydown="return /[a-zA-Z\s]/i.test(event.key)" {$validate.name}
                                                name="customer_name" value="{$post.first_name}" class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label
                                            class="control-label col-md-5">{$customer_default_column.email|default:'Email'}<span
                                                class="required">
                                            </span></label>
                                        <div class="col-md-7">
                                            <input type="email" name="email" id="defaultemail" value="{$post.email}"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-5">{$customer_default_column.mobile|default:'Mobile'}</label>
                                        <div class="col-md-7">
                                            <div class="input-group">
                                            <span class="input-group-addon" id="country_code_txt">+{$selected_mobile_code}</span>
                                            {* {$validate.mobile} *}
                                            <input type="text" pattern="([0-9]{ldelim}10{rdelim})" title="Enter your valid mobile number" name="mobile" id="defaultmobile" aria-describedby="defaultmobile-error" value="{$post.mobile}" class="form-control" maxlength="10">
                                            </div>
                                            <span id="defaultmobile-error" class="help-block help-block-error"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label
                                            class="control-label col-md-4">{$customer_default_column.address|default:'Address'}<span
                                                class="required">
                                            </span></label>
                                        <div class="col-md-7">
                                            <textarea name="address" class="form-control">{$post.address}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">{$customer_default_column.country|default:'Country'}<span class="required"></span></label>
                                        <div class="col-md-7">
                                            <select name="country" class="form-control select2me"
                                                data-placeholder="Select..." onchange="showStateDiv(this.value);">
                                                <option value="">Select Country</option>
                                                {foreach from=$country_code item=v}
                                                    <option {if $v.config_value==$selected_country} selected {/if} value="{$v.config_value}">{$v.config_value}</option>
                                                {/foreach}
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label
                                            class="control-label col-md-4">{$customer_default_column.state|default:'State'}<span
                                                class="required">
                                            </span></label>
                                        <div class="col-md-7" id="state_drpdown" style="{($selected_country=='India') ? 'display:block;' : 'display:none;'}">
                                            <select name="state" class="form-control select2me"
                                                data-placeholder="Select...">
                                                <option value="">Select State</option>
                                                {foreach from=$state_code item=v}
                                                    <option {if $merchant_state==$v.config_value} selected {/if}
                                                        value="{$v.config_value}">{$v.config_value}</option>
                                                {/foreach}
                                            </select>
                                        </div>
                                        <div class="col-md-7" id="state_txt" style="{($selected_country!='India') ? 'display:block;' : 'display:none;'}">
                                            <input type="text" name="state1" value="{$post.state}"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label
                                            class="control-label col-md-4">{$customer_default_column.city|default:'City'}<span
                                                class="required">
                                            </span></label>
                                        <div class="col-md-7">
                                            <input type="text" {$validate.city} name="city" value="{$post.city}"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label
                                            class="control-label col-md-4">{$customer_default_column.zipcode|default:'Zipcode'}<span
                                                class="required">
                                            </span></label>
                                        <div class="col-md-7">
                                            <input type="text" {$validate.zipcode} name="zipcode"
                                                value="{$post.zipcode}" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End profile details -->
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                {foreach from=$column item=v}
                                    {if $v.position=='L'}
                                        <div class="form-group">
                                            {if $v.column_datatype!='company_name'}
                                                <input name="column_id[]" type="hidden" value="{$v.column_id}">
                                                <label class="control-label col-md-5">{$v.column_name}<span
                                                    class="required"></span></label>
                                            {/if}
                                            <div class="col-md-7">
                                                {if $v.column_datatype=='textarea'}
                                                    <textarea class="form-control" maxlength="500"
                                                        name="column_value[]">{$v.value}</textarea>
                                                {elseif $v.column_datatype=='date'}
                                                    <input class="form-control form-control-inline date-picker" autocomplete="off"
                                                        data-date-format="dd M yyyy" name="column_value[]" type="text" />
                                                {elseif $v.column_datatype=='number'}
                                                    <input type="number" autocomplete="false" maxlength="100" class="form-control"
                                                        value="{$v.value}" name="column_value[]">
                                                {elseif $v.column_datatype=='company_name'}
                                                    {* <input type="text" maxlength="100" class="form-control" value="{$v.value}"
                                                        name="company_name"> *}
                                                {else}
                                                    <input type="text" maxlength="100" class="form-control" value="{$v.value}"
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
                                            {if $v.column_datatype!='company_name'}
                                                <input name="column_id[]" type="hidden" value="{$v.column_id}">
                                                <label class="control-label col-md-4">{$v.column_name}<span
                                                class="required"></span></label>
                                            {/if}
                                           
                                            <div class="col-md-7">
                                                {if $v.column_datatype=='textarea'}
                                                    <textarea maxlength="500" class="form-control"
                                                        name="column_value[]">{$v.value}</textarea>
                                                {elseif $v.column_datatype=='date'}
                                                    <input class="form-control form-control-inline date-picker" autocomplete="off"
                                                        data-date-format="dd M yyyy" name="column_value[]" type="text" />
                                                {elseif $v.column_datatype=='number'}
                                                    <input type="number" autocomplete="false" maxlength="100" class="form-control"
                                                        value="{$v.value}" name="column_value[]">
                                                {elseif $v.column_datatype=='company_name'}
                                                    {* <input type="text" maxlength="100" class="form-control" value="{$v.value}"
                                                        name="company_name"> *}
                                                {else}
                                                    <input type="text" maxlength="100" class="form-control" value="{$v.value}"
                                                        name="column_value[]">
                                                {/if}
                                            </div>
                                        </div>
                                    {/if}
                                {/foreach}
                            </div>
                        </div>

                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="pull-right">
                                        <input type="reset" class="btn default" value="Reset" />
                                        <button onclick="return display_warning();" class="btn blue">Save</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="loading" id="loader" style="display: none;">Loading&#8230;</div>

            </div>
            <!-- END PAGE CONTENT-->
        </div>
    </form>
</div>
<!-- END CONTENT -->
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
                <a href="" onclick="return updatemultiCustomer();" class="btn blue">Confirm</a>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>