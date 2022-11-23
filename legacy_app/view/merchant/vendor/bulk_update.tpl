
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <h3 class="page-title">Update Vendor&nbsp;</h3>
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
                    <form action="/merchant/vendor/savebulkupdate" method="post" id="submit_form" class="form-horizontal form-row-sepe">
                        <div class="form-body">
                            <!-- Start profile details -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="alert alert-danger display-none">
                                        <button class="close" data-dismiss="alert"></button>
                                        You have some form errors. Please check below.
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Vendor name <span class="required">*
                                            </span></label>
                                        <div class="col-md-4">
                                            <input type="text" required  maxlength="100" name="vendor_name" {$validate.name} class="form-control" value="{$det.vendor_name}">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-4">Email id <span class="required">*
                                            </span></label>
                                        <div class="col-md-4">
                                            <input type="email" required=""  id="f_email" maxlength="250" name="email" class="form-control" value="{$det.email_id}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Mobile no<span class="required">*
                                            </span></label>
                                        <div class="col-md-4">
                                            <input type="text"  maxlength="12" required {$validate.mobile} name="mobile" class="form-control" value="{$det.mobile}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Address <span class="required">*
                                            </span></label>
                                        <div class="col-md-4">
                                            <textarea type="text" required name="address" maxlength="250" class="form-control">{$det.address}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">City <span class="required">
                                            </span></label>
                                        <div class="col-md-4">
                                            <input type="text"   maxlength="45" name="city" {$validate.city} class="form-control" value="{$det.city}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">State <span class="required">
                                            </span></label>
                                        <div class="col-md-4">
                                            <input type="text"   maxlength="45" name="state" {$validate.city} class="form-control" value="{$det.state}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Zipcode <span class="required">
                                            </span></label>
                                        <div class="col-md-4">
                                            <input type="text"   maxlength="6" name="zipcode" {$validate.zipcode} class="form-control" value="{$det.zipcode}">
                                        </div>
                                    </div>
                                    <hr>
                                    <h3>Documents</h3>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">PAN<span class="required">
                                            </span></label>
                                        <div class="col-md-4">
                                            <input type="text"  maxlength="20" name="pan" {$validate.pan} class="form-control" value="{$det.pan}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Adhar Number <span class="required">
                                            </span></label>
                                        <div class="col-md-4">
                                            <input type="text"  maxlength="20" name="adhar_card"  class="form-control" value="{$det.adhar_card}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">GSTIN <span class="required">
                                            </span></label>
                                        <div class="col-md-4">
                                            <input type="text"  maxlength="20" name="gst" {$validate.city} class="form-control" value="{$det.gst_number}">
                                        </div>
                                    </div>

                                    <hr>
                                    <h3>Bank details</h3>


                                    <div class="form-group">
                                        <label class="control-label col-md-4">Bank account holder name <span class="required">*
                                            </span></label>
                                        <div class="col-md-4">
                                            <input type="text" required maxlength="100" name="account_holder_name" {$validate.name_text} class="form-control" value="{$det.bank_holder_name}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Account number <span class="required">*
                                            </span></label>
                                        <div class="col-md-4">
                                            <input type="text" required maxlength="20" name="account_number" pattern="[0-9]"  class="form-control" value="{$det.bank_account_no}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Bank name <span class="required">*
                                            </span></label>
                                        <div class="col-md-4">
                                            <input type="text" required maxlength="45" name="bank_name" {$validate.name_text} class="form-control" value="{$det.bank_name}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Bank account type <span class="required">*
                                            </span></label>
                                        <div class="col-md-4">
                                            <select class="form-control" name="account_type">
                                                <option  value="">Select account type</option>
                                                <option {if $det.account_type=='Current'}selected{/if} value="Current">Current</option>
                                                <option {if $det.account_type=='Saving'}selected{/if} value="Saving">Saving</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">IFSC code <span class="required">*
                                            </span></label>
                                        <div class="col-md-4">
                                            <input type="text" required=""  maxlength="20" name="ifsc_code" {$validate.name_text} class="form-control" value="{$det.ifsc_code}">
                                        </div>
                                    </div>
                                    <hr>
                                    <h3>Vendor Settlements</h3>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Online settlement via Swipez&nbsp; <span class="required">
                                            </span></label>
                                        <div class="col-md-2">
                                            <input type="checkbox" {if $enable_online_settlement==0} disabled="" {/if} id="issupplier" {if $det.online_pg_settlement==1} checked {/if} name="online_settlement" onchange="vendorOnlineSettlement(this.checked);" value="1" class="make-switch" data-on-text="&nbsp;Enabled&nbsp;&nbsp;" data-off-text="&nbsp;Disabled&nbsp;">
                                        </div>
                                        {if $enable_online_settlement==0}
                                            <div class="col-md-6">
                                                <div class="alert alert-info">
                                                    <strong>To enable online funds transfer to franchise/vendor contact support@swipez.in</strong>
                                                </div>
                                            </div>
                                        {/if}
                                    </div>
                                    <div id="onlinediv" {if $det.online_pg_settlement!=1} style="display: none;" {/if}>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Commision type <span class="required">
                                                </span>
                                            </label>
                                            <div class="col-md-2">
                                                <select name="commision_type" onchange="vendorSettlementType(this.value);" class="form-control">
                                                    <option {if $det.commision_type==1} selected {/if} value="1">Percentage</option>
                                                    <option {if $det.commision_type==2} selected {/if} value="2">Fixed</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group" {if $det.commision_type!=1} style="display: none;" {/if} id="commision_per_div">
                                            <label class="control-label col-md-4">Commision (%) <span class="required">
                                                </span>
                                            </label>
                                            <div class="col-md-2" id="commision_per_div">
                                                <input type="number" max="100" step="0.01" name="commision_percent" {$validate.percentage} class="form-control" value="{$det.commission_percentage}">
                                            </div>
                                        </div>
                                        <div class="form-group" id="commision_amt_div" {if $det.commision_type!=2} style="display: none;" {/if}>
                                            <label class="control-label col-md-4">Commision (Rs.) <span class="required">
                                                </span>
                                            </label>
                                            <div class="col-md-2">
                                                <input type="number" step="0.01" name="commision_amount" {$validate.amount} class="form-control" value="{$det.commision_amount}">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Settlement type <span class="required">
                                                </span>
                                            </label>
                                            <div class="col-md-2">
                                                <select name="settlement_type" class="form-control">
                                                    <option {if $det.settlement_type==1} selected {/if} value="1">Manual</option>
                                                    <option {if $det.settlement_type==2} selected {/if} value="2">Automatic</option>
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
                                <div class="col-md-offset-5 col-md-6">
                                    <input type="submit" value="Save" class="btn blue"/>
                                    <input type="hidden" value="{$det.vendor_id}" name="vendor_id" />
                                    <a href="/merchant/vendor/viewlist" class="btn btn-link">Cancel</a>
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
