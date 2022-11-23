{if $template_type!='simple' && $template_type!='scan' }
    <div class="portlet  col-md-12">
        <div class="portlet-body">
            <h4 class="form-section mt-0">Add taxes
                <a  onclick="AddTax();"  class="btn btn-sm green pull-right"> <i class="fa fa-plus"> </i> Add new row </a>
            </h4> 
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
                                Narrative
                            </th>
                            <th class="td-c">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody id="new_tax">
                        {foreach from=$tax item=v}
                            <tr>
                                <td>
                                    <div class="input-icon right">
                                        <select  onchange="setTaxPercent(this.value);" id="tax{$int}"  name="tax_id[]" data-placeholder="Select..." class="form-control  input-sm" ><option value="">Select Tax</option>
                                            {foreach from=$tax_list key=k item=tt}
                                                <option {if $v==$k} {$tax_id=$k} selected {/if} value="{$k}">{$tt.tax_name}</option>
                                            {/foreach}
                                        </select>
                                    </div>
                                </td>
                                <td>
                                    <div class="input-icon right">
                                        <input readonly="" value="{$tax_list.{$tax_id}.percentage}" name="tax_per[]" type="number" step="0.01" max="100" class="form-control input-sm">
                                    </div>
                                </td>
                                <td>
                                    <input type="text" readonly="" class="form-control input-sm">
                                </td>
                                <td>
                                    <input type="text" class="form-control input-sm" readonly="">
                                </td>
                                <td>
                                    <input type="text" readonly="" class="form-control input-sm">
                                </td>
                                <td>
                                    <a href="javascript:;" onclick="$(this).closest('tr').remove();" class="btn btn-sm red"> <i class="fa fa-times"> </i> </a>
                                </td>
                            </tr>

                        {/foreach}
                    </tbody>
                    <tr class="warning">
                        <td><div class="input-icon right">

                                <input type="text" name="tax_total" id="tax_totallabel" value="{$info.tax_total}" class="form-control input-sm" placeholder="Enter total label"></div>
                        </td>
                        <td></td>
                        <td></td>
                        <td>
                            <input type="text" class="form-control input-sm" readonly>
                        </td>
                        <td></td>
                        <td></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="portlet  col-md-12">
        <div class="portlet-body">
            <h4 class="form-section mt-0">Terms & conditions
            </h4>
            <table class="table mb-0">
                <tbody id="new_tnc">
                    <tr>
                        <td>
                            <div class="input-icon right">
                                <textarea type="text" maxlength="5000" name="tnc" class="form-control input-sm tncrich" placeholder="Add label">{$info.tnc}</textarea>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
{/if}

<div class="portlet  col-md-12">

    <div class="portlet-body" style="padding-bottom: 50px;">
        <h4 class="form-section mt-0"> Plugins
            <a data-toggle="modal" href="#plugins"  class="btn btn-sm green pull-right"> <i class="fa fa-plus"> </i> Choose Plugins </a>
        </h4>

        <div id="pgisdebit" {if $plugin.has_deductible!=1}style="display: none;"{/if}>
            <hr>
            <div class="mb-2">
                <span class="form-section base-font"> Deductible&nbsp;</span>
                <div class="pull-right ml-1">
                    <input type="checkbox" id="isdebit" name="is_debit" {if $plugin.has_deductible==1} checked {/if} data-size="small" onchange="disablePlugin(this.checked, 'plg1');
                            showDebit('debit');" value="1" class="make-switch" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">
                </div>
                <a  onclick="AddDebit();
                        tableHead('new_debit');"   class="btn btn-sm green pull-right "> <i class="fa fa-plus"> </i> Add new row </a>
            </div>

            <div class="">
                <table id="t_new_debit" class="table table-bordered table-hover">
                    <thead id="h_new_debit" >
                        <tr>
                            <th class="td-c  default-font">
                                Deduct label
                            </th>
                            <th class="td-c  default-font">
                                Deduct in %
                            </th>
                            <th class="td-c  default-font">
                                Applicable on
                            </th>
                            <th class="td-c  default-font">
                                Absolute cost
                            </th>
                            <th class="td-c  default-font">
                            </th>
                        </tr>
                    </thead>
                    <tbody id="new_debit">
                        {foreach from=$plugin.deductible item=v}
                            <tr>
                                <td>
                                    <div class="input-icon right">
                                        <input type="text" name="debit[]" value="{$v.tax_name}" {$validate.narrative} class="form-control input-sm" placeholder="Add label">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-icon right">
                                        <input type="number" step="0.01" max="100" value="{$v.percent}" name="debitdefaultValue[]" {$validate.percent} class="form-control input-sm" placeholder="Add debit %">
                                    </div>
                                </td>
                                <td>
                                    <input type="text" readonly="" class="form-control input-sm">
                                </td>
                                <td>
                                    <input type="text" class="form-control input-sm" readonly="">
                                </td>
                                <td>
                                    <a href="javascript:;" onclick="$(this).closest('tr').remove();" class="btn btn-sm red"> <i class="fa fa-times"> </i> </a>
                                </td>
                            </tr>
                        {/foreach}
                    </tbody>
                </table>
            </div>
        </div>

        <!-- add supplier start -->
        <div id="pgissupplier" {if $plugin.has_supplier!=1}style="display: none;"{/if}>
            <hr>
            <div class="mb-2">
                <span class="form-section base-font">Supplier</span>
                <div class="pull-right ml-1">
                    <input type="checkbox" id="issupplier" {if $plugin.has_supplier==1} checked {/if} name="is_supplier" onchange="disablePlugin(this.checked, 'plg2');
                            showDebit('supplier');" value="1"  data-size="small" class="make-switch" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">
                </div>
                <a  data-toggle="modal" href="#respond" class="btn btn-sm green pull-right "> <i class="fa fa-plus"> </i> Add new row </a>
            </div>
            <div class="">
                <table id="t_new_supplier" class="table table-bordered table-hover">
                    <thead id="h_new_supplier" >
                        <tr>
                            <th class="td-c  default-font">
                                Supplier company name
                            </th>
                            <th class="td-c  default-font">
                                Contact person name
                            </th>
                            <th class="td-c  default-font">
                                Mobile
                            </th>
                            <th class="td-c  default-font">
                                Industry type
                            </th>

                            <th class="td-c  default-font">
                            </th>
                        </tr>
                    </thead>
                    <tbody id="new_supplier">
                        {section name=sec1 loop=$supplierlist}
                            <tr id="row{$supplierlist[sec1].supplier_id}">
                                <td class="td-c"><input type="hidden" name="supplier[]" value="{$supplierlist[sec1].supplier_id}">{if {$supplierlist[sec1].supplier_company_name} != ''}{$supplierlist[sec1].supplier_company_name}{else}&nbsp;{/if}</td>
                                <td class="td-c">{if {$supplierlist[sec1].contact_person_name} != ''}{$supplierlist[sec1].contact_person_name}{else}&nbsp;{/if}</td>
                                <td class="td-c">{if {$supplierlist[sec1].mobile1} != ''}{$supplierlist[sec1].mobile1}{else}&nbsp;{/if}</td>
                                <td class="td-c">{if {$supplierlist[sec1].email_id1} != ''}{$supplierlist[sec1].email_id1}{else}&nbsp;{/if} </td>
                                <td class="td-c"><a href="javascript:;" id="{$supplierlist[sec1].supplier_id}" onclick="removesupplier(this.id);
                                        $(this).closest('tr').remove();
                                        tableHead('new_supplier');" class="btn btn-sm red"> <i class="fa fa-times"> </i> </a> </td>
                            </tr>
                        {/section}
                    </tbody>
                </table>
            </div>
        </div>

        <!-- grand total label -->
        <div id="pgiscoupon" {if $plugin.has_coupon!=1}style="display: none;"{/if}>
            <hr>
            <div class="mb-2">
                <span class="form-section base-font">Coupon&nbsp;</span>
                <div class="pull-right">
                    <input  type="checkbox" id="iscoupon" {if $plugin.has_coupon==1}checked{/if} onchange="disablePlugin(this.checked, 'plg3');" name="is_coupon" value="1"  data-size="small" class="make-switch" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">
                </div>
            </div>
        </div>

        <div id="pgiscc" class=""  {if $plugin.has_cc!=1}style="display: none;"{/if}>
            <hr>
            <div class="mb-2">
                <span class="form-section base-font">CC Emails</span>
                <div class="pull-right ml-1">
                    <input type="checkbox" id="iscc" {if $plugin.has_cc==1} checked {/if} name="is_cc" onchange="disablePlugin(this.checked, 'plg4');
                            showDebit('cc');" value="1"  data-size="small" class="make-switch" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">

                </div>
                <a  onclick="AddCC();
                        tableHead('new_cc');"   class="btn btn-sm green pull-right"> <i class="fa fa-plus"> </i> Add new row </a>

            </div>
            <div class="" style="max-width: 500px;">
                <table id="t_new_cc" class="table table-bordered table-hover" >
                    <thead id="h_new_cc" >
                        <tr>
                            <th class="td-c  default-font">
                                Email
                            </th>

                            <th class="td-c">
                            </th>
                        </tr>
                    </thead>
                    <tbody id="new_cc">
                        {if !empty($plugin.cc_email)}
                            {foreach from=$plugin.cc_email item=v}
                                <tr>
                                    <td>
                                        <div class="input-icon right">
                                            <input type="email" name="cc[]" value="{$v}" class="form-control input-sm" placeholder="Add email">
                                        </div>
                                    </td>
                                    <td>
                                        <a href="javascript:;" onclick="$(this).closest('tr').remove();
                                                tableHead('new_cc');" class="btn btn-sm red"> <i class="fa fa-times"> </i>  </a>
                                    </td>
                                </tr>
                            {/foreach}
                        {/if}
                    </tbody>
                </table>
            </div>
        </div>


        <div id="pgisroundoff" {if $plugin.roundoff!=1}style="display: none;"{/if}>
            <hr>
            <div class="mb-2">
                <span class="form-section base-font">Round off&nbsp;</span>
                <div class="pull-right">
                    <input type="checkbox" id="isroundoff" {if $plugin.roundoff==1} checked="" {/if} onchange="disablePlugin(this.checked, 'plg5');" name="is_roundoff" value="1"  data-size="small" class="make-switch" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">
                </div>
            </div>
        </div>
        <div id="pgisacknowledgement" {if $plugin.has_acknowledgement!=1}style="display: none;"{/if}>
            <hr>
            <div>
                <span class="form-section base-font">Acknowledgement section&nbsp;</span>
                <div class="pull-right">
                    <input type="checkbox" id="isacknowledgement" {if $plugin.has_acknowledgement==1} checked="" {/if} onchange="disablePlugin(this.checked, 'plg6');"  name="has_acknowledgement" value="1"  data-size="small" class="make-switch" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">
                </div>
            </div>
        </div>

        {if $is_franchise==1}
            <div id="pgisfranchise" class=""  {if $plugin.has_franchise==0}style="display: none;"{/if}>
                <hr>
                <div class="mb-2">
                    <span class="form-section base-font">Franchise&nbsp;</span>
                    <div class="pull-right">
                        <input type="checkbox" id="isfranchise" {if $plugin.has_franchise==1}checked{/if}  name="is_franchise" onchange="disablePlugin(this.checked, 'plg7');
                                showDebit('franchise');" value="1"  data-size="small" class="make-switch" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">
                    </div>
                </div>
                <div>
                    <label class="control-label  mb-2">Notify Franchise on payment</label>
                    &nbsp;<label> <input type="checkbox" {if $plugin.franchise_notify_email==1} checked {/if} value="1" name="franchise_notify_email"> Email </label>
                    &nbsp;<label> <input type="checkbox" {if $plugin.franchise_notify_sms==1} checked {/if} value="1" name="franchise_notify_sms"> SMS </label>
                    <br>
                    <label class="control-label">Display franchise name on Invoice <input type="checkbox" {if $plugin.franchise_name_invoice==1}checked{/if} value="1" name="franchise_name_invoice">  Yes</label>
                </div>
            </div>

        {/if}
        {if $is_vendor==1}
            <div id="pgisvendor" {if $plugin.has_vendor==0}style="display: none;"{/if}>
                <hr>
                <div>
                    <span class="form-section base-font">Vendor&nbsp;</span>
                    <div class="pull-right">
                        <input type="checkbox" id="isvendor" {if $plugin.has_vendor==1}checked{/if}  name="is_vendor" onchange="disablePlugin(this.checked, 'plg71');" value="1"  data-size="small" class="make-switch" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">
                    </div>
                </div>
            </div>
        {/if}
        <div id="pgisprepaid" {if $plugin.is_prepaid==0}style="display: none;"{/if}>
            <hr>
            <div class="mb-2">
                <span class="form-section base-font">Pre-paid invoices&nbsp;</span>
                <div class="pull-right">
                    <input type="checkbox" id="isprepaid" {if $plugin.is_prepaid==1}checked=""{/if} onchange="disablePlugin(this.checked, 'plg8');"  name="is_prepaid" value="1"  data-size="small" class="make-switch" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">
                </div>
            </div>
        </div>
        {if $has_webhook==1}
            <div id="pgiswebhook" {if $plugin.has_webhook==0}style="display: none;"{/if}>
                <hr>
                <div class="mb-2">
                    <span class="form-section base-font">Webhook&nbsp;</span>
                    <div class="pull-right">
                        <input type="checkbox" {if $plugin.has_webhook==1}checked=""{/if} id="iswebhook" onchange="disablePlugin(this.checked, 'plg9');"  name="has_webhook" value="1"  data-size="small" class="make-switch" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">
                    </div>
                </div>
            </div>
        {/if}

        <div id="pgisautocollect" style="{if $plugin.has_autocollect==0}display: none;{/if}">
            <hr>
            <div class="mb-2">
                <span class="form-section base-font">Auto collect&nbsp;</span>
                <div class="pull-right">
                    <input type="checkbox" id="isautocollect" {if $plugin.has_autocollect==1}checked{/if} onchange="disablePlugin(this.checked, 'plg14');"  name="has_autocollect" value="1"  data-size="small" class="make-switch" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">
                </div>
            </div>
        </div>

        <div id="pgisupload" {if $plugin.has_upload!=1}style="display: none;"{/if}>
            <hr>
            <div class="mb-2">
                <span class="form-section base-font">File upload&nbsp;</span>
                <div class="pull-right">
                    <input type="checkbox" {if $plugin.has_upload==1}checked{/if}  id="isupload" onchange="disablePlugin(this.checked, 'plg15');"  name="has_upload" value="1"  data-size="small" class="make-switch" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">
                </div>
            </div>
            <div class="input-group">
                <div class="form-group form-horizontal">
                    <label class="control-label col-md-3 w-auto">File label</label>
                    <div class="col-md-8">
                        <input  value="{if $plugin.upload_file_label!=''}{$plugin.upload_file_label}{else}View document{/if}" required="" type="text" maxlength="20" class="form-control"  name="upload_file_label">
                    </div>
                </div>
            </div>
        </div>

        <div id="pgissignature" {if $plugin.has_signature!=1}style="display: none;"{/if}>
            <hr>
            <div class="mb-2">
                <span class="form-section base-font"> Digital signature&nbsp; </span>
                <div class="pull-right ml-1">
                    <input type="checkbox" id="issignature" {if $plugin.has_signature==1}checked{/if} onchange="disablePlugin(this.checked, 'plg16');"  name="has_signature" value="1"  data-size="small" class="make-switch" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">
                </div>
                <a  href="/merchant/profile/digitalsignature/iframe"   class="iframe btn btn-sm green pull-right">  Digital signature </a>
            </div>

        </div>

        <div id="pgispartial" class=""  style="{if $plugin.has_partial==0}display: none;{/if}">
            <hr>
            <div class="mb-2">
                <span class="form-section base-font">Partial payment</span>
                <div class="pull-right">
                    <input type="checkbox" id="ispartial"  {if $plugin.has_partial==1}checked{/if} name="is_partial" onchange="disablePlugin(this.checked, 'plg13');
                            showDebit('partial');" value="1"  data-size="small" class="make-switch" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">
                </div>
            </div>
            <div class="row mb-2">
                <div class="form-group form-horizontal">
                    <label class="control-label col-md-3 w-auto">Minimum partial amount</label>
                    <div class="col-md-3">
                        <input type="number" step="0.01" min="50" value="{if $plugin.has_partial==1}{$plugin.partial_min_amount}{else}50{/if}" class="form-control"  id="pma"  name="partial_min_amount">                     
                    </div>
                </div>
            </div>
        </div>

        <div id="pgiscovering" class=""  style="{if $plugin.has_covering_note==0}display: none;{/if}">
            <hr>
            <div class="mb-2">
                <span class="form-section base-font">Covering note </span>
                <div class="pull-right ml-1">
                    <input type="checkbox" id="iscovering" name="is_covering" onchange="disablePlugin(this.checked, 'plg10');
                            showDebit('covering');" value="1" {if $plugin.has_covering_note==1}checked{/if}  data-size="small" class="make-switch" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">
                </div>
                <a  href="/merchant/coveringnote/dynamicvariable"   class="iframe btn btn-sm green pull-right ml-1">Dynamic variables </a>
                <a  data-toggle="modal"  href="#new_covering"  class="btn btn-sm green pull-right ">Add new note </a>
            </div>
            <div class="row mb-2">
                <div class="form-group  form-horizontal">
                    <label class="control-label col-md-3 w-auto">Select covering note</label>
                    <div class="col-md-4">
                        <select class="form-control" id="covering_select" name="default_covering">
                            <option value="0">Select Template</option>
                            {foreach from=$covering_notes item=v}
                                <option {if $plugin.default_covering_note==$v.covering_id} selected {/if}value="{$v.covering_id}">{$v.template_name}</option>
                            {/foreach}
                        </select>                        
                    </div>
                </div>
            </div>
        </div>

        <div id="pgiscustnotification" class=""  style="{if $plugin.has_custom_notification==0}display: none;{/if}">
            <hr>
            <div class="mb-2">
                <span class="form-section base-font">Custom Notification </span>

                <div class="pull-right ml-1">
                    <input type="checkbox" id="iscustnotification" name="is_custom_notification" onchange="disablePlugin(this.checked, 'plg11');
                            showDebit('custnotification');" {if $plugin.has_custom_notification==1}checked{/if} value="1"  data-size="small" class="make-switch" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">
                </div>
                <a  href="/merchant/coveringnote/dynamicvariable"   class="iframe btn btn-sm green pull-right">  Dynamic variables </a>

            </div><div class="row mb-2">
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
        </div>


        <div id="pgiscustreminder" style="{if $plugin.has_custom_reminder==''}display: none;{/if}">
            <hr>
            <div class="mb-2">
                <span class="form-section base-font">Configure reminder schedule </span>

                <div class="pull-right ml-1">
                    <input type="checkbox" id="iscustreminder" name="is_custom_reminder" onchange="disablePlugin(this.checked, 'plg12');
                            showDebit('custreminder');" {if $plugin.has_custom_reminder!=''}checked{/if}  value="1"  data-size="small" class="make-switch" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">
                </div>
                <a  onclick="AddReminder();
                        tableHead('new_reminder');"   class="btn btn-sm green pull-right"> <i class="fa fa-plus"> </i> Add new row </a>

            </div>
            <div style="">
                <div class="" style="">
                    <table id="t_new_reminder" class="table table-bordered table-hover" >
                        <thead id="h_new_reminder" >
                            <tr>
                                <th class="td-c  default-font" style="width: 200px;">
                                    Days before due date
                                </th>
                                <th class="td-c  default-font" >
                                    Reminder email subject
                                </th>
                                <th class="td-c  default-font" >
                                    Reminder SMS
                                </th>

                                <th class="td-c" style="width: 50px;">
                                </th>
                            </tr>
                        </thead>
                        <tbody id="new_reminder">
                            {foreach from=$plugin.reminders key=k item=v}
                                <tr>
                                    <td>
                                        <div class="input-icon right">
                                            <input type="number" name="reminder[]" value="{$k}" step="1" max="100" class="form-control input-sm" placeholder="Add day">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-icon right">
                                            <input type="text" name="reminder_subject[]" value="{$v.email_subject}"  maxlength="250" class="form-control input-sm" placeholder="Reminder mail subject">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-icon right">
                                            <input type="text" name="reminder_sms[]" value="{$v.sms}"  maxlength="200" class="form-control input-sm" placeholder="Reminder SMS">
                                        </div>
                                    </td>
                                    <td>
                                        <a href="javascript:;" onclick="$(this).closest('tr').remove();
                                                tableHead('new_reminder');" class="btn btn-sm red"> <i class="fa fa-times"> </i> </a>
                                    </td>
                                </tr>
                            {/foreach}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<div class="portlet  col-md-12">

    <div class="portlet-body">
        <h3 class="form-section">Final summary </h3>
        <div class="row">
            <div class="col-md-3 w-auto">
                <div class="form-group">
                    <p>Fee value with taxes</p>
                    <input type="text" readonly class="form-control" placeholder="">
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <p>Grand total</p>
                    <input type="text" readonly class="form-control" placeholder="">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <p>Narrative</p>
                    <input type="text" readonly class="form-control" placeholder="">
                </div>
            </div>
            <div class="col-md-4">
                <input type="hidden" name="template_id" value="{$template_id}" />
                <input type="hidden" id="template_type" name="template_type" value="{$template_type}"/>
                <p>&nbsp;</p><input type="submit" value="Save" class="btn blue">
                <a href="/merchant/template/viewlist" class="btn btn-link">Cancel</a>

            </div>
            <!--/span-->
        </div>
        <!-- grand total label ends -->
        </form>
    </div>
</div>

</div>
</div>
<!-- END PAGE CONTENT-->
</div>
</div>
</div>
<!-- END CONTENT -->


<div class="modal fade in" id="plugins" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" id="closebutton1" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Plugins</h4>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">

                        <div class="flex-container">
                            <div class="col-xs-12 col-sm-6 col-md-4  flex-item">
                                <div class="panel  box-plugin">
                                    <div class="panel-body">
                                        <p class="form-section mt-0">Deductibles</p>
                                        <p class="mb-4 default-font">Allows your customer to deduct a tax amount before making the payment. Useful if tax deduction at source (TDS deductions) are applicable for your service or product.	
                                        </p>
                                        <div class="plugin-button">
                                            <input type="checkbox" id="plg1" {if $plugin.has_deductible==1} checked {/if} onchange="pluginChange(this.checked, 'isdebit');" value="1" class="make-switch" data-size="small" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-4  flex-item">
                                <div class="panel  box-plugin">
                                    <div class="panel-body">
                                        <p class="form-section mt-0"> Supplier</p>
                                        <p class="mb-4 default-font">Use the suppliers plugin to notify internal or external staff via email once an invoice has been paid.
                                        </p>
                                        <div class="plugin-button">
                                            <input type="checkbox" id="plg2" {if $plugin.has_supplier==1} checked {/if} onchange="pluginChange(this.checked, 'issupplier');" value="1" class="make-switch" data-size="small" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-4  flex-item">
                                <div class="panel  box-plugin">
                                    <div class="panel-body">
                                        <p class="form-section mt-0"> Coupon</p>
                                        <p class="mb-4 default-font">                                            Plugin to provide discount to your customers via a coupon code. Customers in possession of your coupon code can apply a discount before making a payment.
                                        </p>
                                        <div class="plugin-button">
                                            <input type="checkbox" id="plg3" {if $plugin.has_coupon==1}checked{/if} onchange="pluginChange(this.checked, 'iscoupon');" value="1" class="make-switch" data-size="small" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-4  flex-item">
                                <div class="panel  box-plugin">
                                    <div class="panel-body">
                                        <p class="form-section mt-0"> CC emails</p>
                                        <p class="mb-4 default-font">                                            Send an invoice copy to internal or external staff via email
                                        </p>
                                        <div class="plugin-button">
                                            <input type="checkbox" id="plg4" {if $plugin.has_cc==1} checked {/if} onchange="pluginChange(this.checked, 'iscc');" value="1" class="make-switch" data-size="small" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-4  flex-item">
                                <div class="panel  box-plugin">
                                    <div class="panel-body">
                                        <p class="form-section mt-0"> Round off</p>
                                        <p class="mb-4 default-font">Helps to round off final bill value by getting rid of decimal points</p>
                                        <div class="plugin-button">
                                            <input type="checkbox" id="plg5" {if $plugin.roundoff==1}checked{/if} onchange="pluginChange(this.checked, 'isroundoff');" value="1" class="make-switch" data-size="small" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">
                                        </div>
                                    </div>

                                </div>

                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-4  flex-item">
                                <div class="panel  box-plugin">
                                    <div class="panel-body">
                                        <p class="form-section mt-0"> Acknowledgement section</p>
                                        <p class="mb-4 default-font">                                            Plugin to attach an acknowledgement section at the bottom of your invoice. Useful if bills are printed and the acknowledgement section needs to be torn off after payment. This will appear in the PDF copy of your generated invoice.
                                        </p>
                                        <div class="plugin-button">
                                            <input type="checkbox" id="plg6" {if $plugin.has_acknowledgement==1}checked{/if} onchange="pluginChange(this.checked, 'isacknowledgement');" value="1" class="make-switch" data-size="small" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">

                                        </div>
                                    </div>
                                </div>
                            </div>
                            {if $is_franchise==1}
                                <div class="col-xs-12 col-sm-6 col-md-4  flex-item">
                                    <div class="panel  box-plugin">
                                        <div class="panel-body">
                                            <p class="form-section mt-0"> Franchise</p>
                                            <p class="mb-4 default-font">                                                Plugin to attach a franchisee organization against your invoice i.e. the Invoice will be raised in the name of the franchise.
                                            </p>
                                            <div class="plugin-button">
                                                <input type="checkbox" id="plg7" {if $plugin.has_franchise==1}checked{/if}  onchange="pluginChange(this.checked, 'isfranchise');" value="1" class="make-switch" data-size="small" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            {/if}
                            {if $is_vendor==1}
                                <div class="col-xs-12 col-sm-6 col-md-4  flex-item">
                                    <div class="panel  box-plugin">
                                        <div class="panel-body">
                                            <p class="form-section mt-0"> Vendor</p>
                                            <p class="mb-4 default-font">                                                Plugin to attach a vendors organization against your invoice i.e. Transaction will settle to vendor account.
                                            </p>
                                            <div class="plugin-button">
                                                <input type="checkbox" id="plg71" {if $plugin.has_vendor==1}checked{/if} onchange="pluginChange(this.checked, 'isvendor');" value="1" class="make-switch" data-size="small" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            {/if}
                            <div class="col-xs-12 col-sm-6 col-md-4  flex-item">
                                <div class="panel  box-plugin">
                                    <div class="panel-body">
                                        <p class="form-section mt-0"> Pre-paid invoices</p>
                                        <p class="mb-4 default-font">                                            Activate this plugin if you need to raise an invoice for a payment which has already been received.
                                        </p>
                                        <div class="plugin-button">
                                            <input type="checkbox" id="plg8" {if $plugin.is_prepaid==1}checked{/if} onchange="pluginChange(this.checked, 'isprepaid');" value="1" class="make-switch" data-size="small" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">

                                        </div>
                                    </div>
                                </div>
                            </div>
                            {if $has_webhook==1}
                                <div class="col-xs-12 col-sm-6 col-md-4  flex-item">
                                    <div class="panel  box-plugin">
                                        <div class="panel-body">
                                            <p class="form-section mt-0"> Webhook</p>
                                            <p class="mb-4 default-font">                                                Web link which will be invoked once an online payment is made against your invoice.
                                            </p>
                                            <div class="plugin-button">
                                                <input type="checkbox" id="plg9" {if $has_webhook==1}checked{/if} onchange="pluginChange(this.checked, 'iswebhook');" value="1" class="make-switch" data-size="small" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            {/if}
                            <div class="col-xs-12 col-sm-6 col-md-4  flex-item">
                                <div class="panel  box-plugin">
                                    <div class="panel-body">
                                        <p class="form-section mt-0"> Covering note</p>
                                        <p class="mb-4 default-font">                                            Send a custom covering note along with your invoice to your client.
                                        </p>
                                        <div class="plugin-button">
                                            <input type="checkbox" id="plg10" {if $plugin.has_covering_note==1}checked{/if} onchange="pluginChange(this.checked, 'iscovering');" value="1" class="make-switch" data-size="small" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-4  flex-item">
                                <div class="panel  box-plugin">
                                    <div class="panel-body">
                                        <p class="form-section mt-0"> Customize notification text</p>
                                        <p class="mb-4 default-font">                                            Plugin to change the default Email subject & SMS text sent to your customer once an invoice is sent.
                                        </p>
                                        <div class="plugin-button">
                                            <input type="checkbox" id="plg11" {if $plugin.has_custom_notification==1}checked{/if} onchange="pluginChange(this.checked, 'iscustnotification');" value="1" class="make-switch" data-size="small" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-4  flex-item">
                                <div class="panel  box-plugin">
                                    <div class="panel-body">
                                        <p class="form-section mt-0"> Customize reminder schedule</p>
                                        <p class="mb-4 default-font">                                            Plugin to change the frequency of reminders sent to your customers before due date.
                                        </p>
                                        <div class="plugin-button">
                                            <input type="checkbox" id="plg12" {if $plugin.has_custom_reminder!=''}checked{/if} onchange="pluginChange(this.checked, 'iscustreminder');" value="1" class="make-switch" data-size="small" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-4  flex-item">
                                <div class="panel  box-plugin">
                                    <div class="panel-body">
                                        <p class="form-section mt-0"> Partial payment</p>
                                        <p class="mb-4 default-font">Allow customer to pay partial amount of invoices.</p>
                                        <div class="plugin-button">
                                            <input type="checkbox" id="plg13" {if $plugin.has_partial==1}checked{/if} onchange="pluginChange(this.checked, 'ispartial');" value="1" class="make-switch" data-size="small" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-4  flex-item">
                                <div class="panel  box-plugin">
                                    <div class="panel-body">
                                        <p class="form-section mt-0">  Auto collect</p>
                                        <p class="mb-4 default-font">                                            Collect recurring payments from your customer. Allow your customers to avail recurring payments for your products and services
                                        </p>
                                        <div class="plugin-button">
                                            <input type="checkbox" id="plg14" {if $plugin.has_autocollect==1}checked{/if} onchange="pluginChange(this.checked, 'isautocollect');" value="1" class="make-switch" data-size="small" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-4  flex-item">
                                <div class="panel  box-plugin">
                                    <div class="panel-body">
                                        <p class="form-section mt-0"> File upload</p>
                                        <p class="mb-4 default-font">Attach a document or image along with your invoice.</p>
                                        <div class="plugin-button">
                                            <input type="checkbox" id="plg15" {if $plugin.has_upload==1}checked{/if}  onchange="pluginChange(this.checked, 'isupload');" value="1" class="make-switch" data-size="small" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-4  flex-item">
                                <div class="panel  box-plugin">
                                    <div class="panel-body">
                                        <p class="form-section mt-0"> Digital signature</p>
                                        <p class="mb-4 default-font">Add a digital signature to your invoice.</p>
                                        <div class="plugin-button">
                                            <input type="checkbox" id="plg16" {if $plugin.has_signature==1}checked{/if} onchange="pluginChange(this.checked, 'issignature');" value="1" class="make-switch" data-size="small" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">

                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        </div>


                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn default" id="closebutton" data-dismiss="modal">Close</button>
            </div>
        </div>
        </form>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script>
    {if isset($tax_array)}
    tax_master = '{$tax_array}';
    tax_array = JSON.parse(tax_master);
    {/if}
</script>
