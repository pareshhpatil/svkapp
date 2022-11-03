
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="page-bar">
        {include file="../../common/breadcumbs.tpl" title={$title} links=$links}
    </div>
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
                    <form action="/merchant/profile/gstsave" method="post" id="submit_form" class="form-horizontal form-row-sepe">
                        {CSRF::create('gst_save')}
                        <div class="form-body">
                            <!-- Start profile details -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div id="error" class="alert alert-warning display-none">
                                        <span id="gst_error">Invalid GST number. Please enter valid GST number</span>
                                    </div>
                                    <div class="alert alert-danger display-none">
                                        <button class="close" data-dismiss="alert"></button>
                                        You have some form errors. Please check below.
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Profile name <span class="required">
                                                *</span></label>
                                        <div class="col-md-4">
                                            <input type="text" maxlength="45" name="profile_name" class="form-control" value="{$post.profile_name}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">GST available?  <span class="required">*
                                            </span>
                                        </label>
                                        <div class="col-md-4">
                                            <input type="checkbox" name="gst_available" onchange="GSTAvailable(this.checked);
                                                    gstc(this.checked)"  value="1" class="make-switch" data-on-text="&nbsp;Yes&nbsp;&nbsp;" 
                                                   {if $info.entity_type>0 && $account.gst_available==0} {else} checked {/if} data-off-text="&nbsp;No&nbsp;">
                                        </div>
                                    </div>

                                    <div id="gst_div">
                                        {if $gst_number!=''}
                                            <div class="form-group">
                                                <label class="control-label col-md-4">Select GST <span class="required">*
                                                    </span>
                                                </label>
                                                <div class="col-md-4">
                                                    <div class="input-group">
                                                        <span class="input-group-btn">
                                                            <select id="gst1" onchange="setGST();" class="form-control">
                                                                {foreach from=$state_code item=v}
                                                                    <option {if $merchant_state==$v.config_value} selected {/if} value="{if $v.config_key<10}0{/if}{$v.config_key}" >{if $v.config_key<10}0{/if}{$v.config_key}</option>
                                                                {/foreach}
                                                            </select>
                                                        </span>
                                                        <input type="text"  readonly="" style="min-width: 150px;" id="gst2" value="{$pan_number}" class="form-control" maxlength="40" placeholder="Enter label name">
                                                        <span class="input-group-btn">
                                                            <input type="text" id="gst3" onblur="setGST();" class="form-control" maxlength="3">
                                                        </span>
                                                    </div>
                                                    <span class="help-block"></span>
                                                </div>
                                            </div>
                                        {/if}
                                        <div class="form-group">
                                            <label class="control-label col-md-4">GST Number <span class="required">*
                                                </span>
                                            </label>
                                            <div class="col-md-4">
                                                <input type="text" value="{$info.gst_number}" {if $gst_number!=''} readonly {/if} name="gst_number" onchange="gstCheck(this.value);" id="gst_number" class="form-control" {$validate.gst_number} />
                                                <span class="help-block"></span>
                                            </div>
                                            <div class="col-md-2">
                                                <a onclick="gstValidate();" class="btn blue">Validate</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="display-none" id="cdiv" {if $info.entity_type>0} style="display: block;" {/if}>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Company name <span class="required">*
                                                </span>
                                            </label>
                                            <div class="col-md-4">
                                                <input type="text" readonly id="company_name" name="company_name" class="form-control"  />
                                                <input type="hidden" id="city" name="city" />
                                                <input type="hidden" id="zipcode" name="zipcode" />
                                                <input type="hidden" id="first_name" name="first_name" />
                                                <input type="hidden" id="last_name" name="last_name" />
                                                <input type="hidden" value="" id="entity_type" />
                                                <input type="hidden" value="" id="pan" />
                                                <span class="help-block"></span>
                                            </div>
                                        </div>
                                        <div class="form-group display-none" id="dstate2">
                                            <label class="control-label col-md-4">State <span class="required">
                                                    *</span></label>
                                            <div class="col-md-4">
                                                <select  required="" name="state2" id="state2" class="form-control select2me" data-placeholder="Select...">
                                                    <option value="">Select State</option>
                                                    {foreach from=$state_code item=v}
                                                        <option {if $merchant_state==$v.config_value} selected {/if} value="{$v.config_value}" >{$v.config_value}</option>
                                                    {/foreach}
                                                </select>                                            
                                            </div>
                                        </div>
                                        <div class="form-group" id="dstate1">
                                            <label class="control-label col-md-4">State <span class="required">
                                                </span></label>
                                            <div class="col-md-4">
                                                <input type="text" maxlength="45" readonly name="state" id="state" class="form-control" value="{$post.state}">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Address <span class="required">
                                                    *</span></label>
                                            <div class="col-md-4">
                                                <textarea name="address" required id="addresstext" class="form-control" ></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Business email <span class="required">
                                                </span></label>
                                            <div class="col-md-4">
                                                <input  name="business_email" value="{$mer_addr.business_email}" type="email" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Business contact <span class="required">
                                                </span></label>
                                            <div class="col-md-4">
                                                <input type="text" name="business_contact" maxlength="20" value="{$mer_addr.business_contact}" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputPassword12" class="col-md-4 control-label"
                                                >Currency</label>
                                            <div class="col-md-4" >
                                                <select multiple class="form-control select2me" name="currency[]"
                                                     data-placeholder="Select currency">
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


                                        <div class="form-group" id="mappvalue" >
                                            <label for="inputPassword12" class="col-md-4 control-label" id="valname">Invoice sequence</label>
                                            <div class="col-md-4" id="mappval">
                                                <select class="form-control " name="seq_id" id="mapping_value" data-placeholder="Select">
                                                    <option value="">Select sequence</option>
                                                    {foreach from=$invoice_numbers item=v}
                                                        <option value="{$v.auto_invoice_id}">{$v.prefix}{$v.val}</option>
                                                    {/foreach}
                                                </select>
                                                <span class="help-block"></span><span class="help-block"></span></div>
                                            <div class="col-md-1 no-margin no-padding" id="new_seq_number_btn" style="display: block;">
                                                <a data-toggle="modal" title="New invoice number" onclick="newInvSeq();" class="btn btn-sm green"><i class="fa fa-plus"></i> New sequence</a>
                                            </div>
                                        </div>
                                        <div id="auto_inv_div" style="display: none;">
                                            <div class="form-group">
                                                <label for="inputPassword12" class="col-md-4 control-label">Add new prefix</label>
                                                <div class="col-md-2">
                                                    <input type="text" placeholder="Add prefix" id="prefix" class="form-control ">
                                                    <span class="help-block">
                                                    </span>
                                                </div>
                                                <div class="col-md-2">
                                                    <input type="number" value="0" placeholder="Last no." id="current_number" class="form-control ">
                                                    <span class="help-block">
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputPassword12" class="col-md-4 control-label">&nbsp;</label>
                                                <div class="col-md-4">
                                                    <button type="button" onclick="saveSequence();" class="btn btn-sm blue">Save sequence</button>
                                                    <button type="button" class="btn default btn-sm" onclick="document.getElementById('auto_inv_div').style.display = 'none';">Cancel</button>
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
                                <div class="col-md-12">
                                    <div class="pull-right">
                                        <a href="/merchant/profile/gstprofile" class="btn btn-default">Cancel</a>
                                        <input type="hidden" id="validated_gst_number" value="">
                                        <input type="hidden" id="exist_company" value="{$company_name}">
                                        <input type="submit" value="Save" class="btn blue"/>
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

<script>
    function setGST()
    {
        val1 = _('gst1').value;
        val2 = _('gst2').value;
        val3 = _('gst3').value;
        if (val3.length == 3)
        {
            _('gst_number').value = val1 + val2 + val3;
        } else
        {
            _('gst_number').value = '';
        }
    }

    function gstc(val) {
        if (val == 1)
        {
            _('dstate2').style.display = 'none';
            _('dstate1').style.display = 'block';
            document.getElementById('state2').setAttribute("required", "false");
            document.getElementById('state').setAttribute("required", "true");
        } else
        {
            _('dstate1').style.display = 'none';
            _('dstate2').style.display = 'block';
            document.getElementById('state').setAttribute("required", "false");
            document.getElementById('state2').setAttribute("required", "true");
        }
    }
</script>