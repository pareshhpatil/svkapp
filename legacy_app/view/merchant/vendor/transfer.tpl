
<div class="page-content">
    <h3 class="page-title">Initiate a transfer&nbsp;</h3>
    {if $enable_online_settlement==1}
        <div class="row">
            <div class="col-md-4"></div>
            <div class="col-md-4">
                <h4 class="form-section">
                    Transfer mode&nbsp;
                    <input type="checkbox" id="isdebit"   checked  onchange="changeTransferMode(this.checked);" value="1" class="make-switch" data-on-text="&nbsp;&nbsp;Online&nbsp;&nbsp;&nbsp;" data-off-text="&nbsp;Offline&nbsp;">

                </h4>
            </div>
        </div>
    {/if}
    {if $has_vendor==1 && $has_franchise==1}
        <div class="row">
            <div class="col-md-4"></div>
            <div class="col-md-4">
                <h4 class="form-section">
                    Transfer type&nbsp;
                    <input type="checkbox" id="isdebit"  {if $post.transfer_type==2} checked {/if} onchange="changeTransferType(this.checked);" value="1" class="make-switch" data-on-text="&nbsp;Franchise&nbsp;&nbsp;" data-off-text="&nbsp;Vendor&nbsp;">

                </h4>
            </div>
        </div>
    {/if}
    {if $success!=''}
        <div class="alert alert-block alert-success fade in">
            <button type="button" class="close" data-dismiss="alert"></button>
            <p>{$success}</p>
        </div>
    {/if}
    <div class="loading" id="loader" style="display: none;">Loading&#8230;</div>
    <div id="offline" style="display: none;">
        <h3 class="page-title">Offline transfer&nbsp;</h3>
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
                        <form action="/merchant/vendor/offlinetransfersave" method="post"  id="submit_form" class="form-horizontal form-row-sepe">
                            {CSRF::create('vendor_transfer')}
                            <div class="form-body">
                                <!-- Start profile details -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="alert alert-danger display-none">
                                            <button class="close" data-dismiss="alert"></button>
                                            You have some form errors. Please check below.
                                        </div>
                                        <div {if $has_vendor==0} style="display: none;" {/if} class="form-group" id="vendor1">
                                            <label class="control-label col-md-4">Select vendor <span class="required">*
                                                </span></label>
                                            <div class="col-md-4">
                                                <select class="form-control select2me" id="vendor_id1" data-placeholder="Select vendor" required="" name="vendor_id" >
                                                    <option value=""></option>
                                                    {foreach from=$vendors key=k item=v}
                                                        {if $post.vendor_id==$v.vendor_id}
                                                            <option selected value="{$v.vendor_id}">{$v.vendor_name}</option>
                                                        {else}
                                                            <option value="{$v.vendor_id}">{$v.vendor_name}</option>
                                                        {/if}
                                                    {/foreach}
                                                </select>
                                            </div>

                                        </div>
                                        <div {if $has_vendor==1} style="display: none;" {/if} class="form-group" id="franchise1">
                                            <label class="control-label col-md-4">Select franchise <span class="required">*
                                                </span></label>
                                            <div class="col-md-4">
                                                <select class="form-control select2me" id="franchise_id1" data-placeholder="Select franchise" name="franchise_id" >
                                                    <option value=""></option>
                                                    {foreach from=$franchise key=k item=v}
                                                        {if $post.franchise_id==$v.franchise_id}
                                                            <option selected="" value="{$v.franchise_id}">{$v.franchise_name}</option>
                                                        {else}
                                                            <option value="{$v.franchise_id}">{$v.franchise_name}</option>
                                                        {/if}
                                                    {/foreach}
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputPassword12" class="col-md-4 control-label">Transaction type<span class="required">*
                                                </span></label>
                                            <div class="col-md-4">
                                                <select class="form-control"  name="response_type" onchange="responseType(this.value);" data-placeholder="Select type">
                                                    <option value="1">NEFT/RTGS</option>
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

                                        </div>
                                        <div class="form-group" id="cash_paid_to" style="display: none;">
                                            <label for="inputPassword12" class="col-md-4 control-label">Cash paid to</label>
                                            <div class="col-md-4">
                                                <input class="form-control form-control-inline" name="cash_paid_to" {$validate.name} type="text" value="{$user_name}" placeholder="Cash paid to"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputPassword12" class="col-md-4 control-label">Date <span class="required">*
                                                </span></label>
                                            <div class="col-md-4">
                                                <input class="form-control form-control-inline date-picker" autocomplete="off" onkeypress="return false;"  data-date-format="dd M yyyy"  required name="date" type="text" value="" placeholder="Date"/>
                                            </div>
                                        </div>
                                        <div class="form-group" id="bank_name">
                                            <label for="inputPassword12" class="col-md-4 control-label">Bank name</label>
                                            <div class="col-md-4">
                                                <select class="form-control select2me" name="bank_name" data-placeholder="Select bank name">
                                                    <option value=""></option>
                                                    {html_options values=$bank_value selected={$bank_selected} output=$bank_value}
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputPassword12" class="col-md-4 control-label">Amount <span class="required">*
                                                </span></label>
                                            <div class="col-md-4">
                                                <input class="form-control form-control-inline" id="total" name="amount" required type="text" {$validate.amount} value="{$absolute_cost}" placeholder="Amount"/>
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
                                        <input type="hidden" id="type1" name="transfer_type" {if $has_vendor==0} value="2" {else} value="1"{/if} >
                                        <input type="hidden" name="type" value="2" >
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
    <div id="online">
        <!-- BEGIN PAGE HEADER-->
        <h3 class="page-title">Online transfer&nbsp;</h3>
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
                        <form action="/merchant/vendor/transfersave" method="post" onsubmit="return confirm('Are you sure you want to transfer this amount?');" id="submit_form" class="form-horizontal form-row-sepe">
                            {CSRF::create('vendor_transfer_online')}
                            <div class="form-body">
                                <!-- Start profile details -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="alert alert-danger display-none">
                                            <button class="close" data-dismiss="alert"></button>
                                            You have some form errors. Please check below.
                                        </div>
                                        <div {if $has_vendor==0} style="display: none;" {/if} class="form-group" id="vendor2">
                                            <label class="control-label col-md-4">Select vendor <span class="required">*
                                                </span></label>
                                            <div class="col-md-4">
                                                <select class="form-control select2me" id="vendor_id2" data-placeholder="Select vendor" {if $has_vendor==1} required="" {/if}  name="vendor_id" >
                                                    <option value=""></option>
                                                    {foreach from=$vendors key=k item=v}
                                                        {if $v.online_pg_settlement==1}
                                                            {if $post.vendor_id==$v.vendor_id}
                                                                <option selected value="{$v.vendor_id}">{$v.vendor_name}</option>
                                                            {else}
                                                                <option value="{$v.vendor_id}">{$v.vendor_name}</option>
                                                            {/if}
                                                        {/if}
                                                    {/foreach}
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group" {if $has_vendor==1} style="display: none;" {/if}  id="franchise2">
                                            <label class="control-label col-md-4">Select franchise <span class="required">*
                                                </span></label>
                                            <div class="col-md-4">
                                                <select class="form-control select2me" id="franchise_id2" data-placeholder="Select franchise" name="franchise_id" >
                                                    <option value=""></option>
                                                    {foreach from=$franchise key=k item=v}
                                                        {if $v.online_pg_settlement==1}
                                                            {if $post.franchise_id==$v.franchise_id}
                                                                <option selected="" value="{$v.franchise_id}">{$v.franchise_name}</option>
                                                            {else}
                                                                <option value="{$v.franchise_id}">{$v.franchise_name}</option>
                                                            {/if}
                                                        {/if}
                                                    {/foreach}
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-4">Amount <span class="required">*
                                                </span></label>
                                            <div class="col-md-4">
                                                <input type="number" required="" step="0.01" maxlength="10" name="amount" class="form-control" value="{$post.amount}">
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
                                        <input type="hidden" id="type2" name="transfer_type" {if $has_vendor==0} value="2" {else} value="1"{/if} >
                                        <input type="hidden" name="type" value="1" >
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
</div>
<!-- END CONTENT -->
</div>

