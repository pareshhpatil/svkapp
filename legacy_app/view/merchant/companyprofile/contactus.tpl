
                                    <div class="portlet-body form">

                                        <div class="form-body">
                                            <div class="row">
                                                <div class="col-md-1"></div>
                                                <div class="col-md-10">

                                                    <div class="form-group">
                                                        <label class="col-md-3 control-label">Office location</label>
                                                        <div class="col-md-5">
                                                            <input type="text" name="location" {if ($details.publishable==0)} readonly {/if} value="{$details.office_location}" {$validate.narrative} class="form-control form-control-inline input-sm">
                                                            <span class="help-block">
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-md-3 control-label">Contact no</label>
                                                        <div class="col-md-5">
                                                            <input type="text" name="contact_no" {if ($details.publishable==0)} readonly {/if} value="{$details.contact_no}" {$validate.landline} class="form-control form-control-inline input-sm">
                                                            <span class="help-block">
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-md-3 control-label">Email ID</label>
                                                        <div class="col-md-5">
                                                            <input type="email" name="email_id" {if ($details.publishable==0)} readonly {/if} value="{$details.email_id}" class="form-control form-control-inline input-sm">
                                                            <span class="help-block">
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6"></div>
                                                <div class="col-md-6">
                                                    <button type="submit" class="btn blue"><i class="fa fa-check"></i> Save</button>
                                                </div>
                                            </div>
                                        </div>
                                        </form></div>
                                </div>	


                        </div>
                    </div>
                </form>
            </div>

        </div>	
        <!-- END PAGE CONTENT-->
    </div>
</div>