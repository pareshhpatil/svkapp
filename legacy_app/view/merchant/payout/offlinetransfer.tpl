
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <h3 class="page-title">Offline vendor transfer&nbsp;</h3>
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
                    <form action="/merchant/vendor/offlinetransfersave" method="post" id="submit_form" class="form-horizontal form-row-sepe">
                        {CSRF::create('vendor_transfer')}
                        <div class="form-body">
                            <!-- Start profile details -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="alert alert-danger display-none">
                                        <button class="close" data-dismiss="alert"></button>
                                        You have some form errors. Please check below.
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Select Vendor <span class="required">*
                                            </span></label>
                                        <div class="col-md-4">
                                            <select class="form-control select2me" data-placeholder="Select Vendor" required="" name="vendor_id" >
                                                <option value=""></option>
                                                {foreach from=$vendors key=k item=v}
                                                    <option value="{$v.pg_vendor_id}">{$v.vendor_name}</option>
                                                {/foreach}
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputPassword12" class="col-md-4 control-label">Transaction type<span class="required">*
                                            </span></label>
                                        <div class="col-md-4">
                                            <select class="form-control input-sm select2me"  name="response_type" onchange="responseType(this.value);" data-placeholder="Select type">
                                                <option value="1">Wire transfer</option>
                                                <option value="2">Cheque</option>
                                                <option value="3">Cash</option>
                                                <option value="5">Online Payment</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group" id="bank_transaction_no" >
                                        <label for="inputPassword12" class="col-md-4 control-label">Bank ref no</label>
                                        <div class="col-md-4">
                                            <input class="form-control form-control-inline input-sm" name="bank_transaction_no" type="text" value="" placeholder="Bank ref number"/>
                                        </div>
                                    </div>
                                    <div id="cheque_no" style="display: none;">
                                        <div class="form-group" >
                                            <label for="inputPassword12" class="col-md-4 control-label">Cheque no</label>
                                            <div class="col-md-4">
                                                <input class="form-control form-control-inline input-sm" name="cheque_no"  {$validate.number} type="text" value="" placeholder="Cheque no"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputPassword12" class="col-md-4 control-label">Cheque status</label>
                                            <div class="col-md-4">
                                                <select class="form-control input-sm"  name="cheque_status" data-placeholder="Select status">
                                                    <option value="Deposited">Deposited</option>
                                                    <option value="Realised">Realised</option>
                                                    <option value="Bounced">Bounced</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group" id="cash_paid_to" style="display: none;">
                                        <label for="inputPassword12" class="col-md-4 control-label">Cash paid to</label>
                                        <div class="col-md-4">
                                            <input class="form-control form-control-inline input-sm" name="cash_paid_to" {$validate.name} type="text" value="{$user_name}" placeholder="Cash paid to"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputPassword12" class="col-md-4 control-label">Date <span class="required">*
                                            </span></label>
                                        <div class="col-md-4">
                                            <input class="form-control form-control-inline input-sm date-picker" onkeypress="return false;"  autocomplete="off" data-date-format="dd M yyyy"  required name="date" type="text" value="" placeholder="Date"/>
                                        </div>
                                    </div>
                                    <div class="form-group" id="bank_name">
                                        <label for="inputPassword12" class="col-md-4 control-label">Bank name</label>
                                        <div class="col-md-4">
                                            <select class="form-control input-sm select2me" name="bank_name" data-placeholder="Select bank name">
                                                <option value=""></option>
                                                {html_options values=$bank_value selected={$bank_selected} output=$bank_value}
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputPassword12" class="col-md-4 control-label">Amount <span class="required">*
                                            </span></label>
                                        <div class="col-md-4">
                                            <input class="form-control form-control-inline input-sm" id="total" name="amount" required type="text" {$validate.amount} value="{$absolute_cost}" placeholder="Amount"/>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-4">Narrative <span class="required">
                                            </span></label>
                                        <div class="col-md-4">
                                            <textarea type="text"  name="narrative" maxlength="250" class="form-control">{$post.narrative}</textarea>
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
                                    <a href="/merchant/vendor/transferlist" class="btn btn-link">Cancel</a>
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

