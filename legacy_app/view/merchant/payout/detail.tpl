
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
                    <form action="/merchant/vendor/saveupdate" method="post" id="submit_form" class="form-horizontal form-row-sepe">
                        {CSRF::create('vendor_update')}
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
                                        <label class="control-label col-md-4">Aadhaar Number <span class="required">
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
