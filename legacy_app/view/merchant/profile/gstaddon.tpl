<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <h3 class="page-title">Create billing profile</h3>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12">
            {if isset($error)}
                <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert"></button>
                    <p class="media-heading"><strong>Failed !</strong> {$error}.</p>
                </div>
            {/if}
            <div class="portlet light bordered">
                <div class="portlet-body form">
                    <!--<h3 class="form-section">Profile details</h3>-->
                    <form action="/merchant/profile/gstsave" method="post" id="submit_form"
                        class="form-horizontal form-row-sepe">
                        {CSRF::create('gst_save')}
                        <div class="form-body">
                            <!-- Start profile details -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div id="error" class="alert alert-warning display-none">
                                        <button class="close" data-dismiss="alert"></button>
                                        <span id="gst_error">Invalid GST number. Please enter valid GST number</span>
                                    </div>
                                    <div class="alert alert-danger display-none">
                                        <button class="close" data-dismiss="alert"></button>
                                        You have some form errors. Please check below.
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">State <span class="required">
                                                *</span></label>
                                        <div class="col-md-4">
                                            <select required="" onchange="setGST(this.value);"
                                                class="form-control select2me" data-placeholder="Select...">
                                                <option value="">Select State</option>
                                                {foreach from=$state_code item=v}
                                                    <option {if $merchant_state==$v.config_value} selected {/if}
                                                        value="{if $v.config_key<10}0{/if}{$v.config_key}">
                                                        {if $v.config_key<10}0{/if}{$v.config_key} - {$v.config_value}
                                                    </option>
                                                {/foreach}
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">GST Number <span class="required">*
                                            </span>
                                        </label>
                                        <div class="col-md-4">
                                            <input type="text" value="" oninput="return inputValue(this);"
                                                name="gst_number" onchange="gstCheck(this.value);" id="gst_number"
                                                class="form-control" {$validate.gst_number} />
                                            <span class="help-block"></span>
                                        </div>
                                        <div class="col-md-2">
                                            <a onclick="gstValidate();" class="btn blue">Validate</a>
                                        </div>
                                    </div>
                                    <div class="display-none" id="cdiv">
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Company name <span class="required">*
                                                </span>
                                            </label>
                                            <div class="col-md-4">
                                                <input type="text" readonly id="company_name" name="company_name"
                                                    class="form-control" />
                                                <input type="hidden" id="city" name="city" />
                                                <input type="hidden" id="zipcode" name="zipcode" />
                                                <input type="hidden" id="first_name" name="first_name" />
                                                <input type="hidden" id="last_name" name="last_name" />
                                                <input type="hidden" value="" id="entity_type" />
                                                <input type="hidden" value="" id="pan" />
                                                <input type="hidden" name="state" value="" id="state" />
                                                <span class="help-block"></span>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-4">Address <span class="required">
                                                    *</span></label>
                                            <div class="col-md-4">
                                                <textarea name="address" required id="addresstext"
                                                    class="form-control"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Business email <span class="required">
                                                </span></label>
                                            <div class="col-md-4">
                                                <input name="business_email" value="{$mer_addr.business_email}"
                                                    type="email" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Business contact aaa <span
                                                    class="required">
                                                </span></label>
                                            <div class="col-md-4">
                                                <input type="text" name="business_contact" maxlength="20"
                                                    value="{$mer_addr.business_contact}" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputPassword12" class="col-md-4 control-label"
                                                >Currency</label>
                                            <div class="col-md-4" id="mappval">
                                                <select required class="form-control select2me" name="currency"
                                                    id="mapping_value" data-placeholder="Select">
                                                    <option value="">Select currency</option>
                                                    {foreach from=$currency_list item=v}
                                                        <option value="{$v}">{$v}</option>
                                                    {/foreach}
                                                </select>
                                                <span class="help-block"></span><span class="help-block"></span>
                                            </div>
                                            <div class="col-md-1 no-margin no-padding">
                                                <a data-toggle="modal" href="/merchant/profile/currency"
                                                    class="btn btn-sm green"><i class="fa fa-plus"></i> New currency</a>
                                            </div>
                                        </div>
                                        <div class="form-group" id="mappvalue">
                                            <label for="inputPassword12" class="col-md-4 control-label"
                                                id="valname">Invoice sequence</label>
                                            <div class="col-md-4" id="mappval">
                                                <select class="form-control " name="seq_id" id="mapping_value"
                                                    data-placeholder="Select">
                                                    <option value="">Select sequence</option>
                                                    {foreach from=$invoice_numbers item=v}
                                                        <option value="{$v.auto_invoice_id}">{$v.prefix}{$v.val}</option>
                                                    {/foreach}
                                                </select>
                                                <span class="help-block"></span><span class="help-block"></span>
                                            </div>
                                            <div class="col-md-1 no-margin no-padding" id="new_seq_number_btn"
                                                style="display: block;">
                                                <a data-toggle="modal" title="New invoice number" onclick="newInvSeq();"
                                                    class="btn btn-sm green"><i class="fa fa-plus"></i> New sequence</a>
                                            </div>
                                        </div>
                                        <div id="auto_inv_div" style="display: none;">
                                            <div class="form-group">
                                                <label for="inputPassword12" class="col-md-4 control-label">Add new
                                                    prefix</label>
                                                <div class="col-md-2">
                                                    <input type="text" placeholder="Add prefix" id="prefix"
                                                        class="form-control ">
                                                    <span class="help-block">
                                                    </span>
                                                </div>
                                                <div class="col-md-2 ">
                                                    <input type="number" value="0" placeholder="Last no."
                                                        id="current_number" class="form-control ">
                                                    <span class="help-block">
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputPassword12"
                                                    class="col-md-4 control-label">&nbsp;</label>
                                                <div class="col-md-4">
                                                    <button type="button" onclick="saveSequence();"
                                                        class="btn btn-sm blue">Save sequence</button>
                                                    <button type="button" class="btn default btn-sm"
                                                        onclick="document.getElementById('auto_inv_div').style.display = 'none';">Cancel</button>
                                                    <span class="help-block">
                                                    </span>
                                                    <p id="seq_error" style="color: red;"></p>
                                                </div>
                                            </div>
                                        </div>




                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End profile details -->
                        <div id="submit-div" class="form-actions display-none">
                            <div class="row">
                                <div class="col-md-offset-6 col-md-5">
                                    <input type="hidden" id="validated_gst_number" value="">
                                    <input type="hidden" id="exist_company" value="{$company_name}">
                                    <input type="submit" value="Save" class="btn blue" />
                                    <a href="/merchant/profile/settings" class="btn btn-link">Cancel</a>
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
<script>
    var state = '00';
    var pan = '{$pan_number}';
    document.getElementById('gst_number').value = state + pan;

    function inputValue(el) {
        text = state + pan;
        el.value = el.value.replace(text, ''); //replace all $ with empty string
        txtt = text + el.value;
        console.log(txtt.length);
        if (txtt.length > 12 && txtt.length < 16) {
            el.value = text + el.value; //prepend $ to the input value
        } else {
            el.value = text;
        }
    }

    function setGST(val) {
        state = val;
        document.getElementById('gst_number').value = state + pan;
    }


    //el.value = el.value.replace(/[27BRSPP2039Q+]/g, ''); //replace all $ with empty string
</script>