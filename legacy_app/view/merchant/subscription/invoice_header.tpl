<script>
    var t = '{$pg.swipez_fee_type}';
    var e = '{$pg.swipez_fee_val}';
    var a = '{$pg.pg_fee_type}';
    var n = '{$pg.pg_fee_val}';
    var u = '{$pg.pg_tax_val}';

    {if isset($product_list)}
    products ={$product_json};
    {/if}

    {if isset($tax_array)}
    tax_master = '{$tax_array}';
    tax_array = JSON.parse(tax_master);
    taxes_rate = '{$tax_rate_array}';
    {/if}


</script>
<div class="row">
    <div class="col-md-12">

        <div class="loading" id="loader" style="display: none;">Loading&#8230;</div>
        <div class="alert alert-danger" style="display: none;" id="invoiceerrorshow">
            <p id="invoiceerror_display">Please correct the below errors to complete registration.</p>
        </div>
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
                <form action="/merchant/invoice/invoicesave" method="post" onsubmit="document.getElementById('loader').style.display = 'block';" class="form-horizontal form-row-sepe">
                    <input type="hidden"  name="payment_request_type" value="4">
                    {CSRF::create('invoice')}
                    <div class="form-body">
                        <div >
                            <div class="row invoice-logo">
                                <div class="col-xs-6">
                                    {if $request_type==2}
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Invoice title <span class="required"> </span>
                                                <i class="popovers fa fa-info-circle support blue" data-container="body" data-trigger="hover" data-content="This is the title which shows in the estimate sent to the customer. You can customize this by changing this value." data-original-title="" title=""></i>
                                            </label>
                                            <div class="col-md-6">
                                                <input type="text" maxlength="100" class="form-control" name="invoice_title" value="{$plugin.invoice_title}">
                                            </div>
                                        </div>
                                    {/if}
                                </div>
                                <div class="col-xs-6">
                                    {if !empty($franchise_list)}
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Select  franchise <span class="required">* </span></label>
                                            <div class="col-md-4">
                                                <div class="">
                                                    <select name="franchise_id"  required class="form-control" data-placeholder="Select...">
                                                        <option value="">Select franchise</option>
                                                        {foreach from=$franchise_list item=v}
                                                            <option  value="{$v.franchise_id}" >{$v.franchise_name}</option>
                                                        {/foreach}
                                                    </select>
                                                </div>	
                                            </div>
                                            <div class="col-md-03">
                                                &nbsp;<label> <input type="checkbox" {if $info.franchise_name_invoice==1}checked{/if} value="1" name="franchise_name_invoice"> Display franchise name </label>
                                            </div>
                                        </div>
                                    {/if}
                                    {if !empty($vendor_list)}
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Select  vendor <span class="required">* </span></label>
                                            <div class="col-md-6">
                                                <div class="">
                                                    <select name="vendor_id"  required class="form-control" data-placeholder="Select...">
                                                        <option value="">Select vendor</option>
                                                        {foreach from=$vendor_list item=v}
                                                            <option  value="{$v.vendor_id}" >{$v.vendor_name}</option>
                                                        {/foreach}
                                                    </select>
                                                </div>	
                                            </div>
                                        </div>
                                    {/if}
                                    {foreach from=$main_header item=v}
                                        {if $v.default_column_value=='Custom'}
                                            <div class="form-group" >
                                                <label class="control-label col-md-4">{$v.column_name} </label>
                                                <div class="col-md-8">
                                                    <div class="input-icon right">
                                                        {assign var='valid' value=$validate.{$v.datatype}}
                                                        {if $v.datatype=="textarea"}
                                                            <textarea name="mainvalues[]" {$valid} class="form-control" ></textarea>
                                                        {else}
                                                            <input type="text" {$valid} name="mainvalues[]" class="form-control" >
                                                        {/if}
                                                    </div>	
                                                </div>		
                                                <input type="hidden" name="mainids[]" value="{$v.column_id}" class="form-control" >
                                            </div>
                                        {/if}
                                    {/foreach}
                                </div>
                            </div>
                            <hr/>
                        </div>

                        <h3 class="form-section">Invoice information</h3>


                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-4">Select  Customer <span class="required">* </span></label>
                                    <div class="col-md-7 form-group" style="margin-left: 0px;">
                                        <div class="">
                                            {if count($customer_list)==1}
                                                {assign var='selected' value='selected'}
                                            {else}
                                                {assign var='selected' value=''}
                                            {/if}
                                            <select name="customer_id" id="customer_id" onchange="selectCustomer(this.value)" required class="form-control select2me" data-placeholder="Select...">
                                                <option value=""></option>
                                                {foreach from=$customer_list item=v}
                                                    <option  {$selected} value="{$v.customer_id}" >{$v.customer_code} | {$v.name}</option>
                                                {/foreach}
                                            </select>
                                        </div>	
                                    </div>
                                    <a data-toggle="modal" title="Add new customer" href="#custom" class="btn green ml10"><i class="fa fa-plus"></i></a>

                                </div>
                                {foreach from=$customer_column item=v}
                                    <div class="form-group" >
                                        <label class="control-label col-md-4">{$v.column_name} {if $v.is_mandatory==1}<span class="required">* </span>{/if}</label>
                                        <div class="col-md-8">
                                            <div class="input-icon right">
                                                {if $v.datatype=="textarea"}
                                                    <span class="form-control cust_det grey" id="customer{$v.column_id}" style="height: 80px;" readonly> {$v.value}</span>
                                                {else}
                                                    <input type="text"  id="customer{$v.column_id}" readonly  class="form-control cust_det" >
                                                {/if}
                                            </div>	
                                        </div>				
                                    </div>
                                {/foreach}
                            </div>

                            <div class="col-md-6">
                                {if count($billing_profile)>0}
                                    {if count($billing_profile)>1}
                                        <div class="form-group" >
                                            <label class="control-label col-md-4">Billing profile</label>
                                            <div class="col-md-8">
                                                <select onchange="setGSTInvoiceSeq(this.value);" class="form-control select2me" name="billing_profile_id">
                                                    <option value="0">Select Billing profile</option>
                                                    {foreach from=$billing_profile item=v}
                                                        <option {if $info.profile_id==$v.id} selected {/if} value="{$v.id}">{$v.profile_name} {$v.gst_number}</option>
                                                    {/foreach}
                                                </select>    	
                                            </div>		
                                        </div>
                                    {else}
                                        <input type="hidden" name="billing_profile_id" value="{$billing_profile.{0}.id}">
                                    {/if}
                                {/if}
                                {assign var='textarea_id' value="textarea1"}
                                {foreach from=$header item=v}
                                    {$valid=""}
                                    {$req=""}
                                    {if $v.position=='R' && $v.column_position!=5 && $v.column_position!=6}
                                        {if $request_type!=2 || $v.function_id!=9}
                                            {assign var='valid' value=$validate.{$v.datatype}}

                                            {if $v.is_mandatory==1}
                                                {assign var='req' value='required'}
                                            {/if}
                                            <div class="form-group">
                                                <label class="control-label col-md-4">{$v.column_name}{if $v.datatype=="percent"} (%){/if}{if $textarea_id=='textarea2' && $v.datatype =='textarea'} <br> (as above)<input type="checkbox" id="sameas" onchange="samevalue('textarea1', 'textarea2');" > {/if}{if $v.is_mandatory==1}<span class="required">* </span>{/if}</label>
                                                <div class="col-md-8">
                                                    <div class="input-icon right">
                                                        {if $v.table_name=="request"}
                                                            {$field_name="requestvalue[]"}
                                                            <input type="hidden" name="col_position[]" value="{$v.column_position}" >
                                                        {else}
                                                            {$field_name="newvalues[]"}
                                                            <input type="hidden" name="ids[]" value="{$v.column_id}" class="form-control" >
                                                        {/if}
                                                        <input type="hidden" name="function_id[]" value="{$v.function_id}" >

                                                        {if $v.datatype=="textarea"}
                                                            <textarea type="text"  name="{$field_name}"  id="{$textarea_id}" {$validate.$v.datatype} class="form-control"  placeholder="Enter specific value">{$v.value}</textarea>
                                                            {assign var='textarea_id' value="textarea2"}
                                                        {else if $v.datatype=="date"}
                                                            <input type="text" {$req}  value="{$v.value}" id="{$v.id}" name="{$field_name}"  class="form-control date-picker"  autocomplete="off" data-date-format="dd M yyyy" >
                                                        {else if $v.datatype=="primary"}
                                                            <input type="text" id="{$v.id}" {$valid} value="{$v.value}" onblur="document.getElementById('primary').value = this.value;" name="{$field_name}" class="form-control" >
                                                            <input type="hidden"  id="primary" value="{$v.value}" name="primary" class="form-control" >
                                                        {else}
                                                            <input type="text" id="{$v.id}"  {$req}  {$valid} {if $v.column_position==4} value="{if isset($v.value)}{$v.value}{else}{$cycleName}{/if}" {else} value="{$v.value}" {/if}  {if $v.function_id==10}readonly{/if} name="{$field_name}" class="form-control" >
                                                        {/if}

                                                    </div>	
                                                </div>
                                            </div>
                                            {if $v.function_id==10}
                                                <div class="form-group" >
                                                    <label class="control-label col-md-4">Billing Period start date</label>
                                                    <div class="col-md-8">
                                                        <input type="text" name="billing_start_date"  required  class="form-control  date-picker"  autocomplete="off" data-date-format="dd M yyyy"  placeholder="Select start date">
                                                    </div>				
                                                </div>
                                                <div class="form-group" >
                                                    <label class="control-label col-md-4">Add duration</label>
                                                    <div class="col-md-4">
                                                        <input type="number" name="billing_period" required  class="form-control" >
                                                    </div>				
                                                    <div class="col-md-4">
                                                        <select name="period_type" required class="form-control" data-placeholder="Select...">
                                                            <option value="days">Days</option>
                                                            <option value="month">Month</option>
                                                        </select>
                                                    </div>				
                                                </div>
                                            {/if}
                                            {if $v.function_id==4}
                                                {$is_carry=1}
                                                <div class="form-group" >
                                                    <label class="control-label col-md-4">Carry forward dues?</label>
                                                    <div class="col-md-8">
                                                        <div class="input-icon">
                                                            <input type="checkbox" name="carry_due"  value="1" class="make-switch" data-size="small">  
                                                        </div>

                                                    </div>				
                                                </div>
                                            {/if}
                                        {else}
                                            <input type="hidden" name="ids[]" value="{$v.column_id}" class="form-control" >
                                            <input type="hidden" name="newvalues[]" value="">
                                            <input type="hidden" name="function_id[]" value="{$v.function_id}" >
                                        {/if}
                                    {/if}
                                {/foreach}
                                {if $plugin.is_prepaid==1}
                                    <div class="form-group" >
                                        <label class="control-label col-md-4">Advance Received</label>
                                        <div class="col-md-8">
                                            <div class="input-icon right">
                                                <input type="text" {$validate.money} onblur="calculategrandtotal();" id="advance_amt" name="advance" class="form-control" >
                                            </div>	
                                        </div>		
                                    </div>
                                {/if}
                            </div>
                        </div>
                        {if !empty($bds_column)}
                            <h3 class="form-section">Invoice information</h3>
                            <div class="row">
                                <div class="col-md-6">
                                    {assign var='textarea_id' value="textarea1"}
                                    {foreach from=$bds_column item=v}
                                        {$valid=""}
                                        {$req=""}
                                        {if $v.position=='L'}
                                            {assign var='valid' value=$validate.{$v.datatype}}

                                            {if $v.is_mandatory==1}
                                                {assign var='req' value='required'}
                                            {/if}
                                            <div class="form-group">
                                                <label class="control-label col-md-4">{$v.column_name}{if $v.datatype=="percent"} (%){/if}{if $textarea_id=='textarea2' && $v.datatype =='textarea'} <br> (as above)<input type="checkbox" id="sameas" onchange="samevalue('textarea1', 'textarea2');" > {/if}{if $v.is_mandatory==1}<span class="required">* </span>{/if}</label>
                                                <div class="col-md-8">
                                                    <div class="input-icon right">
                                                        {if $v.table_name=="request"}
                                                            {$field_name="requestvalue[]"}
                                                            <input type="hidden" name="col_position[]" value="{$v.column_position}" >
                                                        {else}
                                                            {$field_name="newvalues[]"}
                                                            <input type="hidden" name="ids[]" value="{$v.column_id}" class="form-control" >
                                                        {/if}

                                                        <input type="hidden" name="function_id[]" value="{$v.function_id}" >

                                                        {if $v.datatype=="textarea"}
                                                            <textarea type="text"  name="{$field_name}"  id="{$textarea_id}" {$validate.$v.datatype} class="form-control"  placeholder="Enter specific value">{$v.value}</textarea>
                                                            {assign var='textarea_id' value="textarea2"}
                                                        {else if $v.datatype=="date"}
                                                            <input type="text" {$req}  value="{$v.value}" id="{$v.id}" name="{$field_name}"  class="form-control date-picker"  autocomplete="off" data-date-format="dd M yyyy" >
                                                        {else if $v.datatype=="time"}
                                                            <input type="text" {$req} autocomplete="off" value="{$v.value}" id="{$v.id}" name="{$field_name}"   class="form-control timepicker timepicker-no-seconds" >
                                                        {else}
                                                            <input type="text" id="{$v.id}"  {$req}  {$valid} {if $v.column_position==4} value="{if isset($v.value)}{$v.value}{else}{$cycleName}{/if}" {else} value="{$v.value}" {/if} name="{$field_name}" class="form-control" >
                                                        {/if}

                                                    </div>	
                                                </div>
                                            </div>
                                        {/if}
                                    {/foreach}
                                </div>

                                <div class="col-md-6">
                                    {assign var='textarea_id' value="textarea1"}
                                    {foreach from=$bds_column item=v}
                                        {$valid=""}
                                        {$req=""}
                                        {if $v.position=='R'}
                                            {assign var='valid' value=$validate.{$v.datatype}}

                                            {if $v.is_mandatory==1}
                                                {assign var='req' value='required'}
                                            {/if}
                                            <div class="form-group">
                                                <label class="control-label col-md-4">{$v.column_name}{if $v.datatype=="percent"} (%){/if}{if $textarea_id=='textarea2' && $v.datatype =='textarea'} <br> (as above)<input type="checkbox" id="sameas" onchange="samevalue('textarea1', 'textarea2');" > {/if}{if $v.is_mandatory==1}<span class="required">* </span>{/if}</label>
                                                <div class="col-md-8">
                                                    <div class="input-icon right">
                                                        {if $v.table_name=="request"}
                                                            {$field_name="requestvalue[]"}
                                                            <input type="hidden" name="col_position[]" value="{$v.column_position}" >
                                                        {else}
                                                            {$field_name="newvalues[]"}
                                                            <input type="hidden" name="ids[]" value="{$v.column_id}" class="form-control" >
                                                        {/if}
                                                        <input type="hidden" name="function_id[]" value="{$v.function_id}" >
                                                        {if $v.datatype=="textarea"}
                                                            <textarea type="text"  name="{$field_name}"  id="{$textarea_id}" {$validate.$v.datatype} class="form-control"  placeholder="Enter specific value">{$v.value}</textarea>
                                                            {assign var='textarea_id' value="textarea2"}
                                                        {else if $v.datatype=="date"}
                                                            <input type="text" {$req}  value="{$v.value}" id="{$v.id}" name="{$field_name}"  class="form-control date-picker"  autocomplete="off" data-date-format="dd M yyyy" >
                                                        {else if $v.datatype=="time"}
                                                            <input type="text" {$req} autocomplete="off" value="{$v.value}" id="{$v.id}" name="{$field_name}"  class="form-control timepicker timepicker-no-seconds" >
                                                        {else}
                                                            <input type="text" id="{$v.id}"  {$req}  {$valid} {if $v.column_position==4} value="{if isset($v.value)}{$v.value}{else}{$cycleName}{/if}" {else} value="{$v.value}" {/if} name="{$field_name}" class="form-control" >
                                                        {/if}

                                                    </div>	
                                                </div>
                                            </div>
                                        {/if}
                                    {/foreach}

                                </div>
                            </div>
                        {/if}
                        <!-- Add subscription panel start -->

                        <h3 class="form-section">Subscription section</h3>


                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-4">Mode</label>
                                    <div class="col-md-8">
                                        <select onchange="repeatChange(this.value);"  name="mode" id="mode" class="form-control" >
                                            <option selected value="3">Monthly</option>
                                            <option value="1">Daily</option>
                                            <option value="2">Weekly</option>
                                            <option value="4">Yearly</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-4">Repeat every</label>
                                    <div class="col-md-6">
                                        <select class="form-control" name="repeat_every" onchange="diplayText();" id="repeat_every">
                                            {$i=1}
                                            {while $i<31}
                                                <option value="{$i}"> {$i}</option>
                                                {$i=$i+1}
                                            {/while}
                                        </select>
                                    </div>
                                    <label class="control-label col-md-2" id="repeat_text">Months</label>
                                </div>

                                <div id="repeat_on"  style="display: none;" class="form-group">
                                    <label class="control-label col-md-4">Repeat on</label>
                                    <div class="col-md-8">
                                        <select class="form-control" id="repeat_on_"  name="repeat_on" onchange="diplayText();" >
                                            <option value="1">Sunday</option><option value="2">Monday</option><option value="3">Tuesday</option><option value="4">Wednesday</option><option value="5">Thrusday</option><option value="6">Friday</option><option value="7">Saturday</option>

                                        </select>
                                    </div>
                                </div>


                                <div class="form-group">
                                    <label class="control-label col-md-4">Start on <span class="required">* </span></label>
                                    <div class="col-md-8">
                                        <input type="text" name="bill_date" id="start_date" required onchange="duedateSummery();
                                                diplayText();" class="form-control  date-picker"  autocomplete="off" data-date-format="dd M yyyy"  placeholder="Select start date">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-4">Due date <span class="required">* </span></label>
                                    <div class="col-md-8">
                                        <input type="text" name="due_date" required onchange="duedateSummery();" required id="due_datetime" class="form-control  date-picker"  autocomplete="off" data-date-format="dd M yyyy"  placeholder="Select due date">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-4">Summary</label>
                                    <div class="col-md-8">
                                        <label class="control-label" id="due_datetime_text">Due same day</label>
                                        <input type="hidden" name="due_datetime_diff" value="0" id="due_datetime_diff">
                                        {if $is_carry!=1}
                                            <input type="hidden" name="carry_due" value="0">
                                        {/if}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-3">End</label>
                                    <div class="radio-list col-md-9">
                                        <div class="form-group">
                                            <div class="col-md-5">
                                                <label>
                                                    <input type="radio" checked name="end_mode" onchange="endModeChange(this.value);" id="optionsRadios2" value="1"> Never</label></div>
                                            <div class="col-md-7">
                                                <div class="form-group">&nbsp;</div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-md-5">
                                                <label>
                                                    <input type="radio" name="end_mode" id="optionsRadios2" onchange="endModeChange(this.value);" value="2"> Occurences</label></div>
                                            <div class="col-md-7">
                                                <div class=""><input type="number" min="1" name="occurrence" id="occurence_text" onchange="diplayText();" disabled class="form-control input-xsmall  " ></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-5">
                                                <label>
                                                    <input type="radio" name="end_mode" id="optionsRadios3" onchange="endModeChange(this.value);" value="3"> End date</label>
                                            </div>
                                            <div class="col-md-7">
                                                <div class=""><input type="text"  onchange="diplayText();" name="end_date" disabled id="end_date_text" class="form-control input-small  date-picker"  autocomplete="off" data-date-format="dd M yyyy"  placeholder="Select end date" ></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-4">Summary</label>
                                    <div class="col-md-7">
                                        <label class="control-label" id="display_text">Daily</label>
                                        <input type="hidden" id="_display_text" value="Daily" name="display_text" >
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Subscription panel end -->