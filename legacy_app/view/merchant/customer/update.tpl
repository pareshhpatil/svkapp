<div class="page-content">
    <div class="page-bar">
        {include file="../../common/breadcumbs.tpl" title={$title} links=$links}
    </div>
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
            {if isset($success)}
                <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert"></button>
                    <strong>Success!</strong> {$success}
                </div>
            {/if}
            <div class="portlet light bordered">
                <div class="portlet-body form">
                    <form action="/merchant/customer/updatesave" method="post" class="form-horizontal form-row-sepe"
                        id="submit_form">
                        {CSRF::create('customer_update')}
                        <h4 class="form-section">System Fields</h4>
                        <div class="form-body">
                            <!-- Start profile details -->
                            <div class="alert alert-danger display-none">
                                <button class="close" data-dismiss="alert"></button>
                                You have some form errors. Please check below.
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label
                                            class="control-label col-md-5">{$customer_default_column.customer_code|default:$lang_title.customer_code}<span
                                                class="required">* </span></label>
                                        <div class="col-md-7">
                                            <input type="text" name="customer_code" value="{$detail.customer_code}"
                                                class="form-control">
                                            <input type="hidden" name="auto_generate"
                                                value="{$merchant_setting.customer_auto_generate}">
                                        </div>

                                    </div>

                                    {foreach from=$column item=v}
                                        {if $v.column_datatype=='company_name'}
                                            <div class="form-group">
                                                <label class="control-label col-md-5">{$v.column_name}<span
                                                        class="required">*</span></label>
                                                <div class="col-md-7">
                                                    <input type="text" maxlength="100" class="form-control"
                                                        value="{$detail.company_name}" name="company_name" required>
                                                </div>
                                            </div>
                                        {/if}
                                    {/foreach}

                                    <div class="form-group">
                                        <label
                                            class="control-label col-md-5">{$customer_default_column.customer_name|default:$lang_title.customer_name}<span
                                                class="required">* </span></label>
                                        <div class="col-md-7">
                                            <input type="text" required {$validate.name} name="customer_name"
                                                value="{$detail.first_name} {$detail.last_name}" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label
                                            class="control-label col-md-5">{$customer_default_column.email|default:$lang_title.email}<span
                                                class="required"> </span></label>
                                        <div class="col-md-7">
                                            <input type="email" {$validate.email} name="email" value="{$detail.email}"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label
                                            class="control-label col-md-5">{$customer_default_column.mobile|default:$lang_title.mobile}<span
                                                class="required"> </span></label>
                                        <div class="col-md-7">
                                            <div class="input-group">
                                                <span class="input-group-addon"
                                                    id="country_code_txt">{$detail.country_mobile_code}</span>
                                                <input type="text" pattern='{($detail.country=='India') ? '([0-9]{10})'
                                                    : '([0-9]{7,10})' }' title="Enter your valid mobile number"
                                                    maxlength="10" id="defaultmobile"
                                                    aria-describedby="defaultmobile-error" name="mobile"
                                                    value="{$detail.mobile}" class="form-control">
                                            </div>
                                            <span id="defaultmobile-error" class="help-block help-block-error"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label
                                            class="control-label col-md-4">{$customer_default_column.address|default:$lang_title.address}<span
                                                class="required"> </span></label>
                                        <div class="col-md-7">
                                            <textarea name="address"
                                                class="form-control">{$detail.address} {$detail.address2}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label
                                            class="control-label col-md-4">{$customer_default_column.country|default:$lang_title.country}<span
                                                class="required"></span></label>
                                        <div class="col-md-7">
                                            <select name="country" class="form-control select2me"
                                                data-placeholder="Select..." onchange="showStateDiv(this.value);">
                                                <option value="">Select Country</option>
                                                {foreach from=$country_code item=v}
                                                    <option
                                                        {if str_replace(' ','',strtolower($v.config_value))==str_replace(' ','',strtolower($detail.country))}
                                                        selected {/if} value="{$v.config_value}">{$v.config_value}</option>
                                                {/foreach}
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label
                                            class="control-label col-md-4">{$customer_default_column.state|default:$lang_title.state}<span
                                                class="required"> </span></label>
                                        <div class="col-md-7" id="state_drpdown"
                                            {($detail.country=='India') ? 'style=display:block' : 'style=display:none'}>
                                            <select name="state" class="form-control select2me"
                                                data-placeholder="Select...">
                                                <option value="">Select State</option>
                                                {foreach from=$state_code item=v}
                                                    <option
                                                        {if str_replace(' ','',strtolower($v.config_value))==str_replace(' ','',strtolower($detail.state))}
                                                        selected {/if} value="{$v.config_value}">{$v.config_value}</option>
                                                {/foreach}
                                            </select>
                                        </div>
                                        <div class="col-md-7" id="state_txt"
                                            {($detail.country=='India') ? 'style=display:none' : 'style=display:block'}>
                                            <input type="text" name="state1" value="{$detail.state}"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label
                                            class="control-label col-md-4">{$customer_default_column.city|default:$lang_title.city}<span
                                                class="required"> </span></label>
                                        <div class="col-md-7">
                                            <input type="text" name="city" {$validate.city} value="{$detail.city}"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label
                                            class="control-label col-md-4">{$customer_default_column.zipcode|default:$lang_title.zipcode}<span
                                                class="required"> </span></label>
                                        <div class="col-md-7">
                                            <input type="text" name="zipcode" {$validate.zipcode}
                                                value="{if $detail.zipcode!=0}{$detail.zipcode}{/if}"
                                                class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End profile details -->
                        </div>
                        <h4 class="form-section">Custom Fields </h4>

                        <div class="row">
                            <div class="col-md-6">
                                {foreach from=$column item=v}
                                    {if $v.position=='L'}
                                        <div class="form-group">
                                            {if $v.column_datatype!='company_name'}
                                                <input name="col_id[]" type="hidden" value="{$v.column_id}">
                                                {if $v.id!=''}
                                                    <input name="ids[]" type="hidden" value="{$v.id}">
                                                    {$name='values[]'}
                                                {else}
                                                    {$name='column_value[]'}
                                                    <input name="column_id[]" type="hidden" value="{$v.column_id}">
                                                {/if}
                                                <label class="control-label col-md-5">{$v.column_name}<span
                                                        class="required"></span></label>
                                            {/if}

                                            <div class="col-md-7">
                                                {if $v.column_datatype=='textarea'}
                                                    <textarea class="form-control" maxlength="500"
                                                        name="{$name}">{$v.value}</textarea>
                                                {elseif $v.column_datatype=='date'}
                                                    <input class="form-control form-control-inline date-picker" autocomplete="off"
                                                        data-date-format="dd M yyyy" name="{$name}" type="text"
                                                        value="{$v.value|date_format:"%d %b %Y"}" />
                                                {elseif $v.column_datatype=='number'}
                                                    <input type="number" autocomplete="false" maxlength="100" class="form-control"
                                                        value="{$v.value}" name="{$name}">
                                                {elseif $v.column_datatype=='company_name'}
                                                    {* <input type="text" maxlength="100" class="form-control" value="{$detail.company_name}"
                                                        name="company_name">  *}
                                                {else}
                                                    <input type="text" maxlength="100" class="form-control" value="{$v.value}"
                                                        name="{$name}">
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
                                                <input name="col_id[]" type="hidden" value="{$v.column_id}">
                                                {if $v.id!=''}
                                                    <input name="ids[]" type="hidden" value="{$v.id}">
                                                    {$name='values[]'}
                                                {else}
                                                    {$name='column_value[]'}
                                                    <input name="column_id[]" type="hidden" value="{$v.column_id}">
                                                {/if}
                                                <label class="control-label col-md-4">{$v.column_name}<span
                                                        class="required"></span></label>
                                            {/if}

                                            <div class="col-md-7">
                                                {if $v.column_datatype=='textarea'}
                                                    <textarea class="form-control" maxlength="500"
                                                        name="{$name}">{$v.value}</textarea>
                                                {elseif $v.column_datatype=='date'}
                                                    <input class="form-control form-control-inline date-picker" autocomplete="off"
                                                        data-date-format="dd M yyyy" name="{$name}" type="text"
                                                        value="{$v.value|date_format:"%d %b %Y"}" />
                                                {elseif $v.column_datatype=='number'}
                                                    <input type="number" autocomplete="false" maxlength="100" class="form-control"
                                                        value="{$v.value}" name="{$name}">
                                                {elseif $v.column_datatype=='company_name'}
                                                    {* <input type="text" maxlength="100" class="form-control" value="{$detail.company_name}"
                                                        name="company_name"> *}
                                                {else}
                                                    <input type="text" maxlength="100" class="form-control" value="{$v.value}"
                                                        name="{$name}">
                                                {/if}
                                            </div>
                                        </div>
                                    {/if}
                                {/foreach}
                            </div>
                        </div>


                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-offset-10">
                                    <a href="/merchant/customer/viewlist" class="btn default">Cancel</a>
                                    <input type="hidden" name="customer_id" value="{$link}">
                                    <input type="submit" class="btn blue" value="Save" />
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