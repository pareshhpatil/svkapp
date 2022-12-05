
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
                    <form action="/merchant/profile/gstupdatesave" method="post" id="submit_form" class="form-horizontal form-row-sepe">
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

                                    <div >
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Profile name <span class="required">
                                                </span></label>
                                            <div class="col-md-4">
                                                <input type="text" maxlength="45" name="profile_name" class="form-control" value="{$info.profile_name}">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Company name <span class="required">
                                                </span></label>
                                            <div class="col-md-4">
                                                <input type="text" readonly  class="form-control" value="{$info.company_name}">
                                            </div>
                                        </div>
                                        {if $info.gstin!=''}
                                            <div class="form-group">
                                                <label class="control-label col-md-4">GST number <span class="required">
                                                    </span></label>
                                                <div class="col-md-4">
                                                    <input type="text"  readonly  class="form-control" value="{$info.gstin}">
                                                </div>
                                            </div>
                                        {/if}
                                        <div class="form-group">
                                            <label class="control-label col-md-4">State <span class="required">
                                                </span></label>
                                            <div class="col-md-4">
                                                <input type="text" maxlength="45" readonly  class="form-control" value="{$info.state}">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Address <span class="required">
                                                    *</span></label>
                                            <div class="col-md-4">
                                                <textarea name="address" required id="addresstext" class="form-control" >{$info.address}</textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Business email <span class="required">
                                                </span></label>
                                            <div class="col-md-4">
                                                <input  name="business_email" value="{$info.business_email}" type="email" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Business contact <span class="required">
                                                </span></label>
                                            <div class="col-md-4">
                                                <input type="text" name="business_contact" maxlength="20" value="{$info.business_contact}" class="form-control" />
                                            </div>
                                        </div>


                                        <div class="form-group" id="mappvalue" >
                                            <label for="inputPassword12" class="col-md-4 control-label" id="valname">Invoice sequence</label>
                                            <div class="col-md-4" id="mappval">
                                                <select class="form-control " name="seq_id" id="mapping_value" data-placeholder="Select">
                                                    <option value="">Select sequence</option>
                                                    {foreach from=$invoice_numbers item=v}
                                                        <option {if $info.invoice_seq_id==$v.auto_invoice_id} selected {/if} value="{$v.auto_invoice_id}">{$v.prefix}{$v.val}</option>
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
                                                <div class="col-md-5">
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
                        <div id="submit-div" class="form-actions">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="pull-right">
                                        <a href="/merchant/profile/gstprofile" class="btn btn-default">Cancel</a>
                                        <input type="hidden" name="id" value="{$info.id}">
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