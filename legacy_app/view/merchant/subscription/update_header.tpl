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
    particular_col_array = JSON.parse('{$info.particular_column}');
</script>

<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <h3 class="page-title">Update a subscription request</h3>
    <!-- END PAGE HEADER-->

    <!-- BEGIN SEARCH CONTENT-->
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
                    <form action="/merchant/invoice/invoiceupdate" method="post" id="invoice_create" class="form-horizontal form-row-sepe">
                        <input type="hidden"  name="payment_request_type" value="4">
                        {CSRF::create('invoice')}
                        <div class="form-body">
                            <div>
                                <div class="row invoice-logo">
                                    <div class="col-xs-6">
                                        {if $info.invoice_type==2}
                                            <div class="form-group">
                                                <label class="control-label col-md-4">Estimate title <span class="required"> </span>
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
                                                                {if $v.franchise_id==$info.franchise_id}
                                                                    <option selected  value="{$v.franchise_id}" >{$v.franchise_name}</option>
                                                                {else}
                                                                    <option  value="{$v.franchise_id}" >{$v.franchise_name}</option>
                                                                {/if}

                                                            {/foreach}
                                                        </select>
                                                    </div>	
                                                </div>
                                                <div class="col-md-03">
                                                    &nbsp;<label> <input type="checkbox" {if $plugin.franchise_name_invoice==1}checked{/if} value="1" name="franchise_name_invoice"> Display franchise name </label>
                                                </div>
                                            </div>
                                        {/if}
                                        {if !empty($vendor_list)}
                                            <div class="form-group">
                                                <label class="control-label col-md-4">Select  vendor <span class="required">* </span></label>
                                                <div class="col-md-6">
                                                    <div class="">
                                                        <select name="vendor_id"  required class="form-control" data-placeholder="Select...">
                                                            <option value="">Select franchise</option>
                                                            {foreach from=$vendor_list item=v}
                                                                {if $v.vendor_id==$info.vendor_id}
                                                                    <option selected  value="{$v.vendor_id}" >{$v.vendor_name}</option>
                                                                {else}
                                                                    <option  value="{$v.vendor_id}" >{$v.vendor_name}</option>
                                                                {/if}
                                                            {/foreach}
                                                        </select>
                                                    </div>	
                                                </div>
                                            </div>
                                        {/if}
                                        {foreach from=$main_header item=v}
                                            <div class="form-group" >
                                                <label class="control-label col-md-4">{$v.column_name} </label>
                                                <div class="col-md-8">
                                                    <div class="input-icon right">
                                                        {assign var='valid' value=$validate.{$v.datatype}}
                                                        {if $v.datatype=="textarea"}
                                                            <textarea name="mh_existvalues[]" {$valid} class="form-control" >{$v.value}</textarea>
                                                        {else}
                                                            <input type="text" {$valid} name="mh_existvalues[]" value="{$v.value}" class="form-control" >
                                                        {/if}
                                                    </div>	
                                                </div>		
                                                <input type="hidden" name="mh_existids[]" value="{$v.invoice_id}" class="form-control" >
                                            </div>
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
                                        <div class="col-md-7">
                                            <div class="">
                                                <select name="customer_id" id="customer_id" onchange="selectCustomer(this.value)" required class="form-control select2me" data-placeholder="Select...">
                                                    <option value=""></option>
                                                    {foreach from=$customer_list item=v}
                                                        {if $info.customer_id==$v.customer_id}
                                                            <option selected  value="{$v.customer_id}" >{$v.customer_code} | {$v.name}</option>
                                                        {else}
                                                            <option  value="{$v.customer_id}" >{$v.customer_code} | {$v.name}</option>
                                                        {/if}
                                                    {/foreach}
                                                </select>
                                            </div>	
                                        </div>
                                        <div class="col-md-1 no-margin no-padding">
                                            <a data-toggle="modal" title="Add new customer"  href="#custom" class="btn green"><i class="fa fa-plus"></i></a>
                                        </div>
                                    </div>
                                    {foreach from=$customer_column item=v}
                                        <div class="form-group" >
                                            <label class="control-label col-md-4">{$v.column_name} {if $v.is_mandatory==1}<span class="required">* </span>{/if}</label>
                                            <div class="col-md-8">
                                                <div class="input-icon right">
                                                    {if $v.datatype=="textarea"}
                                                        <span class="form-control cust_det grey" id="customer{$v.column_id}" style="height: 50px;" readonly> {$v.value}</span>
                                                    {else}
                                                        <input type="text" id="customer{$v.id}" value="{$v.value}" readonly  class="form-control cust_det" >
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
                                                            <option {if $info.billing_profile_id==$v.id} selected {/if} value="{$v.id}">{$v.profile_name} {$v.gst_number}</option>
                                                        {/foreach}
                                                    </select>    	
                                                </div>		
                                            </div>
                                        {else}
                                            <input type="hidden" name="billing_profile_id" value="{$billing_profile.{0}.id}">
                                        {/if}
                                    {/if}
                                    {foreach from=$request_value item=v}
                                        {$value=$v.value}
                                        {if $v.position=='R' && $v.column_position!=5 && $v.column_position!=6}
                                            {if $v.column_position==4}
                                                {$value=$info.cycle_name}
                                            {/if}
                                            <div class="form-group">
                                                <label class="control-label col-md-4">{$v.column_name} {if $v.column_datatype=="percent"} (%){/if}{if $v.is_mandatory==1}<span class="required">* </span>{/if}</label>
                                                <div class="col-md-8">
                                                    {assign var='valid' value=$validate.{$v.column_datatype}}
                                                    {if $v.column_position==1}
                                                        {assign var='valid' value=$validate.name}
                                                    {/if}

                                                    {$field_name="requestvalue[]"}
                                                    <input type="hidden" name="col_position[]" value="{$v.column_position}" >
                                                    <input type="hidden" name="request_function_id[]" value="{$v.function_id}" >
                                                    <div class="input-icon right">
                                                        {if $v.column_datatype=="textarea"}
                                                            <textarea type="text"  name="{$field_name}" {$valid} class="form-control"  placeholder="Enter specific value">{$value}</textarea>
                                                        {else if $v.column_datatype=="date"}

                                                            <input type="text" name="{$field_name}" value="{$value|date_format:"%d %b %Y"}" class="form-control date-picker"  autocomplete="off" data-date-format="dd M yyyy" >
                                                        {else}
                                                            <input type="text" id="{$v.id}" name="{$field_name}" {$valid} value="{$value}" class="form-control" >
                                                        {/if}
                                                    </div>	
                                                </div>
                                            </div>
                                        {/if}
                                    {/foreach}

                                    {foreach from=$header item=v}
                                        {if $v.position=='R'}
                                            {$value=$v.value}
                                            {if $info.invoice_type!=2 || $v.function_id!=9}
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">{$v.column_name} {if $v.column_datatype=="percent"} (%){/if}{if $v.is_mandatory==1}<span class="required">* </span>{/if}</label>
                                                    <div class="col-md-8">
                                                        <div class="input-icon right">

                                                            {assign var='valid' value=$validate.{$v.column_datatype}}
                                                            {if $v.column_position==1}
                                                                {assign var='valid' value=$validate.name}
                                                            {/if}
                                                            <input type="hidden" name="existids[]" value="{$v.invoice_id}" class="form-control" >
                                                            {$field_name="existvalues[]"}

                                                            <input type="hidden" name="function_id[]" value="{$v.function_id}" >

                                                            {if $v.column_datatype=="textarea"}
                                                                <textarea type="text"  name="{$field_name}" {$valid} class="form-control"  placeholder="Enter specific value">{$value}</textarea>
                                                            {else if $v.column_datatype=="date"}
                                                                <input type="text" name="{$field_name}" id="{$v.id}" value="{$value|date_format:"%d %b %Y"}" class="form-control date-picker"  autocomplete="off" data-date-format="dd M yyyy" >
                                                            {else if $v.column_datatype=="primary"}
                                                                <input type="text"  {$valid} value="{$value}" onblur="document.getElementById('primary').value = this.value;" name="{$field_name}" class="form-control" >
                                                                <input type="hidden"   id="primary" value="{$value}" name="primary" class="form-control" >
                                                            {else}
                                                                <input type="text" {$valid} id="{$v.id}" name="{$field_name}" value="{$value}" {if $v.function_id==10}readonly{/if} class="form-control" >
                                                            {/if}

                                                        </div>	
                                                    </div>
                                                </div>
                                                {if $v.function_id==10}
                                                    <div class="form-group" >
                                                        <label class="control-label col-md-4">Billing Period start date</label>
                                                        <div class="col-md-8">
                                                            <input type="text" name="billing_start_date" value="{$subscription.billing_period_start_date|date_format:"%d %b %Y"}"  class="form-control  date-picker"  autocomplete="off" data-date-format="dd M yyyy"  placeholder="Select start date">
                                                        </div>				
                                                    </div>
                                                    <div class="form-group" >
                                                        <label class="control-label col-md-4">Add duration</label>
                                                        <div class="col-md-4">
                                                            <input type="number" value="{$subscription.billing_period_duration}" name="billing_period"  class="form-control" >
                                                        </div>				
                                                        <div class="col-md-4">
                                                            <select name="period_type" class="form-control" data-placeholder="Select...">
                                                                <option {if $subscription.billing_period_type=='days'} selected{/if}value="days">Days</option>
                                                                <option {if $subscription.billing_period_type=='month'} selected{/if} value="month">Month</option>
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
                                                                <input type="checkbox" name="carry_due"  {if $subscription.carry_due==1}checked{/if} value="1" class="make-switch" data-size="small">  
                                                            </div>

                                                        </div>				
                                                    </div>
                                                {/if}
                                            {else}
                                                <input type="hidden" name="existids[]" value="{$v.invoice_id}" class="form-control" >
                                                <input type="hidden" name="function_id[]" value="{$v.function_id}" >
                                                <input type="hidden" id="{$v.id}" name="existvalues[]" value="{$value}" class="form-control" >
                                            {/if}
                                        {/if}
                                    {/foreach}
                                    {if $plugin.is_prepaid==1}
                                        <div class="form-group" >
                                            <label class="control-label col-md-4">Advance Received</label>
                                            <div class="col-md-8">
                                                <div class="input-icon right">
                                                    <input type="text" {$validate.money} onblur="calculategrandtotal();" value="{$info.advance}" id="advance_amt" name="advance" class="form-control" >
                                                </div>	
                                            </div>		
                                        </div>
                                    {/if}



                                </div>
                            </div>
                            {if !empty($bds_column)}
                                <h3 class="form-section">Booking information</h3>
                                <div class="row">
                                    <div class="col-md-6">
                                        {foreach from=$bds_column item=v}
                                            {if $v.position=='L'}
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">{$v.column_name} {if $v.column_datatype=="percent"} (%){/if}{if $v.is_mandatory==1}<span class="required">* </span>{/if}</label>
                                                    <div class="col-md-8">
                                                        <div class="input-icon right">

                                                            {assign var='valid' value=$validate.{$v.column_datatype}}
                                                            {if $v.column_position==1}
                                                                {assign var='valid' value=$validate.name}
                                                            {/if}
                                                            <input type="hidden" name="existids[]" value="{$v.invoice_id}" class="form-control" >
                                                            {$field_name="existvalues[]"}
                                                            {$value=$v.value}

                                                            <input type="hidden" name="function_id[]" value="{$v.function_id}" >

                                                            {if $v.column_datatype=="textarea"}
                                                                <textarea type="text"  name="{$field_name}" {$valid} class="form-control"  placeholder="Enter specific value">{$value}</textarea>
                                                            {else if $v.column_datatype=="date"}
                                                                <input type="text" name="{$field_name}" id="{$v.id}" value="{$value|date_format:"%d %b %Y"}" class="form-control date-picker"  autocomplete="off" data-date-format="dd M yyyy" >
                                                            {else if $v.column_datatype=="time"}
                                                                <input type="text" name="{$field_name}" id="{$v.id}" value="{$value|date_format:"%I:%M %p"}" class="form-control timepicker timepicker-no-seconds" >
                                                            {else}
                                                                <input type="text" {$valid} id="{$v.id}" name="{$field_name}" value="{$value}" class="form-control" >
                                                            {/if}

                                                        </div>	
                                                    </div>
                                                </div>
                                            {/if}
                                        {/foreach}
                                    </div>

                                    <div class="col-md-6">
                                        {foreach from=$bds_column item=v}
                                            {if $v.position=='R'}
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">{$v.column_name} {if $v.column_datatype=="percent"} (%){/if}{if $v.is_mandatory==1}<span class="required">* </span>{/if}</label>
                                                    <div class="col-md-8">
                                                        <div class="input-icon right">

                                                            {assign var='valid' value=$validate.{$v.column_datatype}}
                                                            {if $v.column_position==1}
                                                                {assign var='valid' value=$validate.name}
                                                            {/if}
                                                            <input type="hidden" name="existids[]" value="{$v.invoice_id}" class="form-control" >
                                                            {$field_name="existvalues[]"}
                                                            {$value=$v.value}

                                                            <input type="hidden" name="function_id[]" value="{$v.function_id}" >

                                                            {if $v.column_datatype=="textarea"}
                                                                <textarea type="text"  name="{$field_name}" {$valid} class="form-control"  placeholder="Enter specific value">{$value}</textarea>
                                                            {else if $v.column_datatype=="date"}
                                                                <input type="text" name="{$field_name}" id="{$v.id}" value="{$value|date_format:"%d %b %Y"}" class="form-control date-picker"  autocomplete="off" data-date-format="dd M yyyy" >
                                                            {else if $v.column_datatype=="time"}
                                                                <input type="text" name="{$field_name}" id="{$v.id}" value="{$value|date_format:"%I:%M %p"}" class="form-control timepicker timepicker-no-seconds" >
                                                            {else}
                                                                <input type="text" {$valid} id="{$v.id}" name="{$field_name}" value="{$value}" class="form-control" >
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
                                            <select onchange="repeatChange(this.value);"  name="mode" id="mode" class="form-control  select2me" >
                                                <option {if $subscription.mode == 1}{$mode='Daily'}selected{/if} value="1">Daily</option>
                                                <option {if $subscription.mode == 2}{$mode='Weekly'}selected{/if} value="2">Weekly</option>
                                                <option {if $subscription.mode == 3}{$mode='Monthly'}selected{/if} value="3">Monthly</option>
                                                <option {if $subscription.mode == 4}{$mode='Yearly'}selected{/if} value="4">Yearly</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Repeat every</label>
                                        <div class="col-md-6">
                                            <select class="form-control  select2me" name="repeat_every" onchange="diplayText();" id="repeat_every">
                                                {$i=1}
                                                {while $i<31}
                                                    <option {if $subscription.repeat_every == $i}selected{/if} value="{$i}"> {$i}</option>
                                                    {$i=$i+1}
                                                {/while}
                                            </select>
                                        </div>
                                        <label class="control-label col-md-2" id="repeat_text">{$mode}</label>
                                    </div>

                                    <div  id="repeat_on" {if $mode!='Weekly'} style="display: none;" {/if} class="form-group">
                                        <label class="control-label col-md-4">Repeat on</label>
                                        <div class="col-md-8">
                                            <select class="form-control  select2me" id="repeat_on_" name="repeat_on" onchange="diplayText();" >
                                                {foreach from=$weeks key=k item=v}
                                                    <option {if $k+1 == $subscription.repeat_on}selected{/if} value="{$k + 1}">{$v}</option>
                                                {/foreach}

                                            </select>
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <label class="control-label col-md-4">Start on <span class="required">* </span></label>
                                        <div class="col-md-8">
                                            <input type="text" required {if $subscription.last_sent_date!='2014-01-01 00:00:00'}readonly class="form-control"{else}class="form-control  date-picker"  autocomplete="off" data-date-format="dd M yyyy" {/if} name="bill_date" id="start_date" value="{$start_date}" onchange="duedateSummery();
                                                    diplayText();"  placeholder="Select start date">
                                            <input type="hidden" name="exist_start_date" value="{$start_date}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Due date <span class="required">* </span></label>
                                        <div class="col-md-8">
                                            <input type="text" name="due_date" required value="{$due_date}" onchange="duedateSummery();" id="due_datetime" class="form-control  date-picker"  autocomplete="off" data-date-format="dd M yyyy"  placeholder="Select due date">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-4">Summary</label>
                                        <div class="col-md-8">
                                            <label class="control-label" id="due_datetime_text">{if $start_date==$due_date}Due same day{else}Due within {$subscription.due_diff} days{/if}</label>
                                            <input type="hidden" name="due_datetime_diff" value="{$subscription.due_diff}" id="due_datetime_diff">
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
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <label>
                                                        <input type="radio" {if $subscription.end_mode == 1}checked{/if}  name="end_mode" onchange="endModeChange(this.value);" id="optionsRadios2" value="1"> Never</label></div>
                                                <div class="col-md-7">
                                                    <div class="form-group">&nbsp;</div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-5">
                                                    <label>
                                                        <input type="radio" {if $subscription.end_mode == 2}checked{/if} name="end_mode" id="optionsRadios2" onchange="endModeChange(this.value);" value="2"> Occurences</label></div>
                                                <div class="col-md-7">
                                                    <div class="form-group"><input type="text" {if $subscription.end_mode == 2}value="{$subscription.occurrences}" {else}disabled{/if} id="occurence_text" name="occurrence" onchange="diplayText();"  class="form-control input-xsmall  " ></div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <label>
                                                        <input type="radio" {if $subscription.end_mode == 3}checked{/if} name="end_mode" id="optionsRadios3" onchange="endModeChange(this.value);" value="3"> End date</label>
                                                </div>
                                                <div class="col-md-7">
                                                    <div class="form-group"><input type="text" name="end_date"{if $subscription.end_mode == 3}value="{{$subscription.end_date}|date_format:"%d %b %Y"}" {else} disabled {/if} onchange="diplayText();" id="end_date_text" class="form-control input-small  date-picker"  autocomplete="off" data-date-format="dd M yyyy"  placeholder="Select end date" ></div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Summary</label>
                                        <div class="col-md-7">
                                            <label class="control-label" id="display_text">{$subscription.display_text}</label>
                                            <input type="hidden" id="_display_text" value="{$subscription.display_text}" name="display_text" >
                                            <input type="hidden"  value="{$subscription.subscription_id}" name="subscription_id" >
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <!-- Subscription panel end -->