{if $info.template_type!='scan'}
    <!-- add taxes label -->
    <h3 class="form-section">Add taxes
        <a href="javascript:;" onclick="AddInvoiceTax();" class="btn btn-sm green pull-right"> <i class="fa fa-plus"> </i> Add new row </a></h3>

    <div class="table-scrollable">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th class="td-c">
                        Tax label
                    </th>
                    <th class="td-c">
                        Tax in %
                    </th>
                    <th class="td-c">
                        Applicable on
                    </th>
                    <th class="td-c">
                        Absolute cost
                    </th>

                    <th class="td-c">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody id="new_tax">
                {$int =1}
                {foreach from=$tax item=v}
                    <tr>
                        <td>
                            <select style="width: 100%" onchange="calculatetax();" name="tax_id[]" data-placeholder="Select..." class="form-control  taxselect" >
                                <option value="">Select</option>
                                {foreach from=$tax_list key=k item=tv}
                                    <option {if $k==$v.tax_id} selected="" {/if}value="{$k}">{$tv.tax_name}</option>
                                {/foreach}
                            </select>
                        </td>
                        <td>
                            <div class="input-icon right">
                                <input type="number" step="0.01" max="100" name="tax_percent[]" readonly="" value="{$v.tax_percent}" {$validate.percent}   onblur="calculatetax()"  class="form-control " placeholder="Add tax %">
                            </div>
                        </td>
                        <td>
                            <input type="number" step="0.01" name="tax_applicable[]" {if $v.tax_type==5} readonly {/if} {$validate.money}  value="{$v.applicable}"  onblur="calculatetax()" class="form-control ">
                        </td>
                        <td>
                            <input type="text" name="tax_amt[]" value="{$v.tax_amount}" readonly id="totaltax{$int}" class="form-control " >
                            <input type="hidden" name="tax_detail_id[]" value="{$v.id}">
                            <input type="hidden" name="tax_type[]" value="{$v.tax_type}">
                        </td>

                        <td class="td-c">
                            <a href="javascript:;" onclick="$(this).closest('tr').remove();
                                    calculatetax();" class="btn btn-sm red"> <i class="fa fa-times"> </i>  </a>
                        </td>
                    </tr>
                    {$int = $int + 1}
                {/foreach}
            </tbody>
            <tr class="warning">
                <td>
                    <div class="input-icon right">

                        <input type="text" value="{$info.tax_total}" readonly="" class="form-control " placeholder="Enter total label">
                    </div>
                </td>
                <td></td>
                <td></td>
                <td>
                    <input type="text" id="totaltaxcost" value="{$info.tax_amount}" name="totaltax" class="form-control " readonly>
                </td>
                <td></td>
            </tr>
        </table>
    </div>
{/if}
</div>
</div>
</div>



<div class="portlet light bordered" style="display: none;" id="plugin_div">
    <div class="portlet-body form">
        <h3 class="form-section">Plugins
        </h3>
        <!-- add taxes label ends -->
        {if $plugin.has_deductible==1}
            <h3 class="form-section base-font mb-2">Deductibles
                <a href="javascript:;" onclick="AddInvoiceDebit();" class="btn btn-sm green pull-right"> <i class="fa fa-plus"> </i> Add new row </a></h3>
            <div class="table-scrollable">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="td-c default-font">
                                Deductible
                            </th>
                            <th class="td-c default-font">
                                Deduct in %
                            </th>
                            <th class="td-c default-font">
                                Applicable on
                            </th>
                            <th class="td-c default-font">
                                Absolute cost
                            </th>

                            <th class="td-c">

                            </th>
                        </tr>
                    </thead>
                    <tbody id="new_debit">
                        {$int =1}
                        {foreach from=$plugin.deductible item=v}
                            <tr>
                                <td>
                                    <div class="input-icon right">
                                        <input type="text" name="deduct_tax[]" required {$validate.narrative} value="{$v.tax_name}" class="form-control " placeholder="Add label">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-icon right">
                                        <input type="number" step="0.01" max="100" name="deduct_percent[]" placeholder="Add %" id="debitin{$int}" {$validate.percent} onblur="calculatedebit({$int})"
                                               value="{$v.percent}"  class="form-control ">
                                    </div>
                                </td>
                                <td>
                                    <input type="text" {$validate.money} name="deduct_applicable[]" placeholder="Add applicable on" value="{$v.applicable}" id="applicabledebitamount{$int}" onblur="calculatedebit({$int})" class="form-control ">
                                </td>
                                <td>
                                    <input type="text" type="number" step="0.01" max="100" name="deduct_total[]" readonly="" value="{$v.total}" id="totaldebit{$int}" class="form-control " >
                                </td>

                                <td>
                                    <a href="javascript:;" onclick="$(this).closest('tr').remove();" class="btn btn-sm red"> <i class="fa fa-times"> </i> </a>
                                </td>
                            </tr>
                            {$int = $int + 1}
                        {/foreach}
                    </tbody>
                </table>
            </div>
            <hr>
            {$has_plugin=1}
        {/if}

        {if $plugin.has_cc==1}
            <h3 class="form-section base-font mb-2">Add CC Emails
                <a  onclick="AddCC();"  class="btn btn-sm green pull-right"> <i class="fa fa-plus"> </i> Add new row </a></h3>
            <div class="table-scrollable" style="max-width: 500px;">
                <table class="table table-bordered table-hover" >
                    <thead>
                        <tr>
                            <th class="td-c default-font">
                                Email
                            </th>

                            <th class="td-c">

                            </th>
                        </tr>
                    </thead>
                    <tbody id="new_cc">
                        {foreach from=$plugin.cc_email item=v}
                            <tr>
                                <td>
                                    <div class="input-icon right">
                                        <input type="email" name="cc[]" value="{$v}"  class="form-control " placeholder="Add email">
                                    </div>
                                </td>
                                <td>
                                    <a href="javascript:;" onclick="$(this).closest('tr').remove();" class="btn btn-sm red"> <i class="fa fa-times"> </i> </a>
                                </td>
                            </tr>
                        {/foreach}
                    </tbody>
                </table>
            </div>
            <hr>
            {$has_plugin=1}
        {/if}

    </div>

    <!-- add supplier start -->
    <div id="supplierdiv" {if $is_supplier!=1}style="display: none;"{/if}>
        <h3 class="form-section base-font mb-2">Add supplier
            <a  data-toggle="modal" href="#respond" class="btn btn-sm green pull-right"> <i class="fa fa-plus"> </i> Add new row </a></h3>
        <div class="table-scrollable">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="td-c default-font">
                            Supplier company name
                        </th>
                        <th class="td-c default-font">
                            Contact person name
                        </th>
                        <th class="td-c default-font">
                            Mobile
                        </th>
                        <th class="td-c default-font">
                            Email
                        </th>

                        <th class="td-c">
                        </th>
                    </tr>
                </thead>
                <tbody id="new_supplier">
                    {section name=sec1 loop=$plugin.supplier}
                        <tr id="row{$supplierlist[sec1].supplier_id}">
                            <td class="td-c"><input type="hidden" name="supplier[]" value="{$supplierlist[sec1].supplier_id}">{if {$supplierlist[sec1].supplier_company_name} != ''}{$supplierlist[sec1].supplier_company_name}{else}&nbsp;{/if}</td> 
                            <td class="td-c">{if {$supplierlist[sec1].contact_person_name} != ''}{$supplierlist[sec1].contact_person_name}{else}&nbsp;{/if}</td>
                            <td class="td-c">{if {$supplierlist[sec1].mobile1} != ''}{$supplierlist[sec1].mobile1}{else}&nbsp;{/if}</td>
                            <td class="td-c">{if {$supplierlist[sec1].email_id1} != ''}{$supplierlist[sec1].email_id1}{else}&nbsp;{/if} </td> 
                            <td class="td-c"><a href="javascript:;" id="{$supplierlist[sec1].supplier_id}" onclick="$(this).closest('tr').remove();" class="btn btn-sm red"> <i class="fa fa-times"> </i> </a> </td> 
                        </tr> 
                    {/section}
                </tbody>
            </table>
        </div>
        <hr>
    </div>




    <!-- grand total label -->

    {if $plugin.has_autocollect==1}
        <h3 class="form-section base-font mb-2">Auto collect plan

            <a class="btn btn-sm green pull-right" data-toggle="modal"  href="#autocollect_plan">
                <i class="fa fa-plus"></i> Add new plan</a>
        </h3>
        <div class="row mb-2">
            <div class="form-group form-horizontal">
                <label class="control-label col-md-3 w-auto">Select auto collect plan</label>
                <div class="col-md-3">
                    <select name="autocollect_plan_id" id="autocollect_plan_id" class="form-control" data-placeholder="Select...">
                        <option value="0">Select auto collect plan</option>
                        {foreach from=$autocollect_plans item=v}
                            <option {if $plugin.autocollect_plan_id==$v.plan_id} selected="" {/if} value="{$v.plan_id}">{$v.plan_name} | {$v.amount}</option>
                        {/foreach}
                    </select>                 </div>
            </div>
        </div>
        <hr>
        {$has_plugin=1}
    {/if}

    {if !empty($franchise_list)}
        <h3 class="form-section base-font mb-2">Franchise
        </h3>
        <div class="row mb-2">
            <div class="form-group form-horizontal">
                <label class="control-label col-md-3 w-auto">Select franchise</label>
                <div class="col-md-3">
                    <select name="franchise_id"  required class="form-control" data-placeholder="Select...">
                        <option value="">Select franchise</option>
                        {foreach from=$franchise_list item=v}
                            {if $v.franchise_id==$plugin.franchise_id}
                                <option selected  value="{$v.franchise_id}" >{$v.franchise_name}</option>
                            {else}
                                <option  value="{$v.franchise_id}" >{$v.franchise_name}</option>
                            {/if}
                        {/foreach}
                    </select>
                </div>
            </div>
        </div>
        <div class="row mb-2">
            <div class="form-group form-horizontal">
                <label class="control-label col-md-3 w-auto">Display franchise name on invoice </label>
                <div class="control-label col-md-3 w-auto">
                    <label> <input type="checkbox" {if $plugin.franchise_name_invoice==1}checked{/if} value="1" name="franchise_name_invoice"> Yes </label>
                </div>
            </div>
        </div>

        <hr>
        {$has_plugin=1}
    {/if}

    {if !empty($vendor_list)}
        <h3 class="form-section base-font mb-2">Vendor
        </h3>
        <div class="row mb-2">
            <div class="form-group form-horizontal">
                <label class="control-label col-md-3 w-auto">Select vendor</label>
                <div class="col-md-3">
                    <select name="vendor_id"  required class="form-control" data-placeholder="Select...">
                        <option value="">Select vendor</option>
                        {foreach from=$vendor_list item=v}
                            {if $v.vendor_id==$plugin.vendor_id}
                                <option selected  value="{$v.vendor_id}" >{$v.vendor_name}</option>
                            {else}
                                <option  value="{$v.vendor_id}" >{$v.vendor_name}</option>
                            {/if}
                        {/foreach}
                    </select>
                </div>
            </div>
        </div>
        <hr>
        {$has_plugin=1}
    {/if}


    {if $plugin.has_covering_note==1}
        <h3 class="form-section base-font mb-2">Covering note

            <a class="btn btn-sm green pull-right" data-toggle="modal"  href="#new_covering">
                <i class="fa fa-plus"></i> Add new note</a>
        </h3>

        <div class="row mb-2">
            <div class="form-group form-horizontal">
                <label class="control-label col-md-3 w-auto">Select covering note</label>
                <div class="col-md-3">
                    <select name="covering_id" id="covering_select" class="form-control" data-placeholder="Select...">
                        <option value="0">Select covering note</option>
                        {foreach from=$covering_list item=v}
                            <option {if $plugin.default_covering_note==$v.covering_id} selected{/if} value="{$v.covering_id}">{$v.template_name}</option>
                        {/foreach}
                    </select> 
                    <a class="hidden" id="conf_cov" data-toggle="modal"  href="#con_coveri"></a>
                </div>
            </div>
        </div>
        <hr>
        {$has_plugin=1}
    {/if}       
    {if $invoice_type!='subscription'}
        <!--<div class="col-md-2">
            <div class="form-group">
                <p>Preview covering note</p>
                <div class="input-icon">
                    <input type="checkbox" id="confirm_covering"  value="1" class="make-switch" data-size="small">  
                </div>
            </div>
        </div>-->
    {/if}

    {if $plugin.has_custom_notification==1}
        <h3 class="form-section base-font mb-2">Customize notification</h3>

        <div class="row mb-2">
            <div class="col-md-12">
                <div class="form-group form-horizontal">
                    <label class="control-label col-md-3 w-auto" style="min-width: 128px;">Email Subject</label>
                    <div class="col-md-6">
                        <input class="form-control" value="{if $plugin.custom_email_subject!=''}{$plugin.custom_email_subject}{else}Payment request from %COMPANY_NAME%{/if}" type="text" maxlength="200" name="custom_subject" placeholder="Email subject">
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-md-12">
                <div class="form-group form-horizontal">
                    <label class="control-label col-md-3 w-auto" style="min-width: 128px;">SMS</label>
                    <div class="col-md-6">
                        <textarea class="form-control" type="text" maxlength="200" name="custom_sms" placeholder="Payment request SMS">{if $plugin.custom_sms!=''}{$plugin.custom_sms}{else}You have received a payment request from %COMPANY_NAME% for amount %TOTAL_AMOUNT%. To make an online payment, access your bill via %SHORT_URL%{/if}</textarea>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        {$has_plugin=1}
    {/if}
    {if $plugin.has_custom_reminder==1}
        <h3 class="form-section base-font">Custom reminder
            <a  onclick="AddInvoiceReminder();"  class="btn btn-sm green pull-right "> <i class="fa fa-plus"> </i> Add new row </a>
            <input type="hidden" value="" id="cust_reminderval">
        </h3>
        <div class="row">
            <div class="col-md-12">
                <div class="table-scrollable" >
                    <table class="table table-bordered table-hover" >
                        <thead>
                            <tr>
                                <th class="td-c default-font" style="width: 150px;">
                                    Reminder before day
                                </th>
                                <th class="td-c default-font">
                                    Reminder subject
                                </th>
                                <th class="td-c default-font">
                                    Reminder SMS
                                </th>

                                <th class="td-c" style="width: 50px;">

                                </th>
                            </tr>
                        </thead>
                        <tbody id="new_reminder">
                            {if !empty($plugin.reminders)}
                                {foreach from=$plugin.reminders key=k item=v}
                                    <tr>
                                        <td>
                                            <div class="input-icon right">
                                                <input type="text" name="reminders[]" value="{$k}" id="rm{$k}" class="form-control"   >
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-icon right">
                                                <input type="text" name="reminder_subject[]" value="{$v.email_subject}" class="form-control" >
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-icon right">
                                                <input type="text" name="reminder_sms[]" value="{$v.sms}"  class="form-control" >
                                            </div>
                                        </td>
                                        <td>
                                            <a href="javascript:;" onclick="$(this).closest('tr').remove();" class="btn btn-sm red"> <i class="fa fa-times"> </i></a>
                                        </td>
                                    </tr>
                                {/foreach}
                            <input type="hidden" value="{$plugin.has_custom_reminder}" name="has_custom_reminder">
                        {/if}
                        </tbody>
                    </table>

                </div>
                {if !empty($plugin.reminders)}
                    <input type="hidden" value="1" name="has_custom_reminder">
                {/if}

            </div>
        </div>
        <hr>
        {$has_plugin=1}
    {/if}
    {if $plugin.has_partial==1}
        <div id="partial_div" >
            <h3 class="form-section base-font mb-2">Partial Payment        
                <div class="pull-right">
                    <input type="checkbox" checked="" value="1" onchange="checkValue('is_partial_', this.checked);"  class="make-switch" data-size="small">  
                    <input type="hidden" id="is_partial_" value="1" name="is_partial"/>
                </div>
            </h3>
            <div class="row mb-2">
                <div class="form-group form-horizontal">
                    <label class="control-label col-md-3 w-auto">Min amount</label>
                    <div class="col-md-3">
                        <input type="number" name="partial_min_amount" id="pma" min="50" step="0.01" value="{$plugin.partial_min_amount}" class="form-control">  
                    </div>
                </div>
            </div>
        </div>
        <hr>
        {$has_plugin=1}
    {/if}
    {if $plugin.has_coupon==1}
        <h3 class="form-section base-font mb-2">Coupon
            <a class="btn btn-sm green pull-right" data-toggle="modal"  href="#coupon">
                <i class="fa fa-plus"></i> Add new coupon</a>
        </h3>
        <div class="row mb-2">
            <div class="form-group form-horizontal">
                <label class="control-label col-md-3 w-auto">Select coupon</label>
                <div class="col-md-3">
                    <select style="min-width: 150px;" name="coupon_id" id="coupon_select" class="form-control" data-placeholder="Select...">
                        <option value="0">Select coupon</option>
                        {foreach from=$coupon_list item=v}
                            {if $plugin.coupon_id==$v.coupon_id}
                                <option selected value="{$v.coupon_id}">{$v.coupon_code}</option>
                            {else}
                                <option value="{$v.coupon_id}">{$v.coupon_code}</option>
                            {/if}
                        {/foreach}
                    </select> 
                </div>
            </div>
        </div>
        <hr>
        {$has_plugin=1}
    {/if}

</div>

<!-- add taxes label ends -->


<!-- add supplier start -->



<div class="portlet light bordered">
    <div class="portlet-body form">

        <h3 class="form-section">Final summary</h3>

        <div class="row">

            <div class="col-md-3 w-auto">
                <div class="form-group">
                    <p>Fee value with taxes</p>
                    <input type="text" id="totalamount" name="amount" value="{$info.invoice_total}" readonly class="form-control">
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <p>Grand total</p>
                    <input type="text" id="grandtotal" name="grand_total" value="{$info.grand_total}" readonly class="form-control">
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <p>Narrative</p>
                    <div class="input-icon right">

                        <input type="text" name="invoice_narrative" value="{$info.narrative}" class="form-control" placeholder="Enter narrative ">
                    </div>
                </div>
            </div>

            <div class="col-md-2">
                <div class="col-md-12">
                    <div class="form-group">
                        <p>Notify customer</p>
                        <div class="input-icon">
                            <input type="checkbox" id="notify_" onchange="notifyPatron('notify_');" value="1"  {if $info.notify_patron==1}checked{/if} class="make-switch" data-size="small">  
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <p>&nbsp;</p>
                <input type="hidden" id="custom_covering" name="custom_covering" value=""/> 
                <input type="hidden" id="template_type" name="template_type" value="{$info.template_type}"/>
                <input type="hidden" name="payment_request_id" value="{$payment_request_id}"/> 
                <input type="hidden" id="template_id" name="template_id" value="{$template_id}"/> 
                <input type="hidden" id="totalcostamt" name="totalcost" value="{$info.basic_amount}">
                <input type="hidden" id="is_notify_" name="notify_patron" value="{$info.notify_patron}"/> 
                <input type="hidden" id="is_partial_" name="is_partial" value="{$plugin.has_partial_payment}"/> 
                <input type="hidden" name="request_type" value="{if $info.invoice_type==2}2{else}1{/if}"/>
                <input type="hidden" id="merchant_state" value="{$merchant_state}">
                <input type="hidden" name="staging" value="{$staging}"/>
                <input type="submit" class="btn blue " value="Save">
                <a onclick="window.history.back();" class="btn default ">Cancel</a>
            </div>
            {if isset($signature)}
                {if isset($signature.font_file)}
                    <link href="{$signature.font_file}" rel="stylesheet">
                {/if}
                <div class="row" style="padding-right: 20px;padding-left: 20px;">
                    <div class="col-md-12">
                        <div class="pull-{$signature.align}">
                            {if $signature.type=='font'}
                                <label style="font-family: '{$signature.font_name}',cursive;font-size: {$signature.font_size}px;">{$signature.name}</label>
                            {else}
                                <img src="{$signature.signature_file}" style="max-height: 100px;">
                            {/if}
                        </div>
                    </div>
                </div>
            {/if}

            <!--/span-->

            <!-- grand total label ends -->
        </div>
    </div>
</div>
</form>
</div>
</div>	
<!-- END PAGE CONTENT-->
</div>
</div>
</div>
<!-- END CONTENT -->
</div>
<!-- /.modal -->

<div class="modal fade" id="coupon" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="/merchant/coupon/save" id="couponForm" method="post"  class="form-horizontal form-row-sepe">
                {CSRF::create('coupon_save')}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Add new coupon</h4>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="portlet">
                                <div class="portlet-body form">
                                    <!--<h3 class="form-section">Profile details</h3>-->

                                    <div class="form-body">
                                        <!-- Start profile details -->
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="alert alert-danger display-none">
                                                    <button class="close" data-dismiss="alert"></button>

                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">Coupon code <span class="required">*
                                                        </span></label>
                                                    <div class="col-md-4">
                                                        <input type="text" required name="coupon_code" id="coupon_code" class="form-control" value="{$post.coupon_code}">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">Coupon description <span class="required">*
                                                        </span></label>
                                                    <div class="col-md-6">
                                                        <textarea  required name="descreption" class="form-control" >{$post.descreption}</textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">Start date <span class="required">*
                                                        </span></label>
                                                    <div class="col-md-4">
                                                        <input class="form-control form-control-inline date-picker" type="text" required  value="{$post.start_date}" name="start_date"  autocomplete="off" data-date-format="dd M yyyy"  placeholder="Start date"/>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">End date <span class="required">*
                                                        </span></label>
                                                    <div class="col-md-4">
                                                        <input class="form-control form-control-inline date-picker" type="text" required  value="{$post.end_date}" name="end_date"  autocomplete="off" data-date-format="dd M yyyy"  placeholder="End date"/>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">Number of coupons<span class="required">*
                                                        </span></label>
                                                    <div class="col-md-4">
                                                        <input type="number" min="0" value="0" required name="limit" class="form-control" value="{$post.limit}">
                                                    </div>Keep 0 for unlimited
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Coupon offer type</label>
                                                    <div class="col-md-4">
                                                        <select name="is_fixed" class="form-control" onchange="isFixed(this.value);" data-placeholder="Select...">
                                                            <option value="1">Fixed amount</option>
                                                            <option value="2">Percentage</option>

                                                        </select>
                                                        <span class="help-block">
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="form-group" id="per_div" style="display: none;">
                                                    <label class="control-label col-md-4">Percentage <span class="required">*
                                                        </span></label>
                                                    <div class="col-md-4">
                                                        <input type="number" min="0" required id="per" name="percent" class="form-control" value="{$post.percent}">
                                                    </div>%
                                                </div>
                                                <div class="form-group" id="fixed_div" >
                                                    <label class="control-label col-md-4">Fixed amount <span class="required">*
                                                        </span></label>
                                                    <div class="col-md-4">
                                                        <input type="number" min="0" required id="fix" name="fixed_amount" class="form-control" value="{$post.fixed_amount}">
                                                    </div>
                                                </div>

                                            </div>


                                        </div>
                                    </div>					
                                    <!-- End profile details -->




                                </div>
                            </div>
                        </div>


                    </div>
                </div>
                <div class="modal-footer">
                    <input type="submit" onclick="saveCoupon();"  id="btnSubmit"  class="btn blue" value="Save"/>
                    <button type="button" class="btn default" id="closebutton" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- add taxes label ends -->
<div class="display-none" id="tax_div">
    <select style="width: 100%" onchange="calculatetax();" name="tax_id[]" data-placeholder="Select..." class="taxselectdefault" >
        <option value="">Select</option>
        {foreach from=$tax_list key=k item=v}
            <option  value="{$k}">{$v.tax_name}</option>
        {/foreach}
    </select>
</div>

<script>
    {if $has_plugin==1}
    document.getElementById('plugin_div').style.display = 'block';
    {/if}
</script>