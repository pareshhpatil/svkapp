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
    customer_state = '{$customer_state}';
</script>

<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <h3 class="page-title">Update {if $info.invoice_type==2}an estimate{else}an invoice{/if}</h3>
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
                    <form action="{$post_link}" method="post" onsubmit="{if $plugin.has_covering_note}return coveringInvoice('{$req_id}');{else}return validateInvoice('{$req_id}');{/if}" id="invoice" class="form-horizontal"  enctype="multipart/form-data">
                        {CSRF::create('invoice')}
                        <div class="form-body">
                            <div class="">
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
                                        {foreach from=$main_header item=v}
                                            <div class="form-group" >
                                                <label class="control-label col-md-4">{$v.column_name} </label>
                                                <div class="col-md-8">
                                                    <div class="input-icon right">
                                                        {assign var='valid' value=$validate.{$v.datatype}}
                                                        {if $v.datatype=="textarea"}
                                                            <textarea name="mainexistvalues[]" {$valid} class="form-control" >{$v.value}</textarea>
                                                        {else}
                                                            <input type="text" {$valid} name="mainexistvalues[]" value="{$v.value}" class="form-control" >
                                                        {/if}
                                                    </div>	
                                                </div>		
                                                <input type="hidden" name="mainexistids[]" value="{$v.invoice_id}" class="form-control" >
                                            </div>
                                        {/foreach}
                                    </div>
                                </div>
                            </div>

                            <h3 class="form-section">{if $request_type==2}Estimate{else}Invoice{/if} information</h3>
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
                                                        <span class="form-control cust_det grey" id="customer{$v.column_id}" style="height: 80px;" readonly> {$v.value}</span>
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
                                        {if $v.position=='R'}
                                            {if $v.column_position==4}
                                                {$value=$info.cycle_name}
                                            {elseif $v.column_position==5}
                                                {$value=$info.bill_date}
                                                {$v.is_mandatory=1}
                                            {elseif $v.column_position==6}
                                                {$value=$info.due_date}
                                                {$v.is_mandatory=1}
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

                                                            <input {if $v.is_mandatory==1} required=""{/if} type="text" name="{$field_name}" value="{$value|date_format:"%d %b %Y"}" class="form-control date-picker"  autocomplete="off" data-date-format="dd M yyyy" >
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
                                                                <input type="text" {$valid} id="{$v.id}" name="{$field_name}" value="{$value}" class="form-control" >
                                                            {/if}

                                                        </div>	
                                                    </div>
                                                </div>
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
                                    {if $plugin.has_upload==1}
                                        {if $info.document_url!=''}
                                            <div class="form-group" >
                                                <label class="control-label col-md-4">Upload file</label>
                                                <div class="col-md-5">
                                                    <span class="help-block">
                                                        <a class=" iframe btn btn-xs green" href="{$info.document_url}">View doc</a>
                                                        <a onclick="document.getElementById('update12').style.display = 'block';" class="btn btn-xs green">Update doc</a>
                                                    </span>
                                                    <span id="update12" style="display: none;">
                                                        <input type="file" accept="image/*,application/pdf" onchange="return validatefilesize(3000000, 'fileupload');" id="fileupload" name="scan_file">
                                                        <a onclick="document.getElementById('update12').style.display = 'none';" class="btn btn-xs red pull-right"><i class="fa fa-remove"></i></a>
                                                        <span class="help-block red">* Max file size 3 MB
                                                        </span>
                                                    </span>
                                                </div>		
                                            </div>
                                        {else}
                                            <div class="form-group" >
                                                <label class="control-label col-md-4">Upload file</label>
                                                <div class="col-md-8">
                                                    <div class="input-icon right">
                                                        <input type="file" accept="image/*,application/pdf" onchange="return validatefilesize(3000000, 'fileupload');" id="fileupload" name="scan_file">
                                                        <span class="help-block red">* Max file size 3 MB</span>
                                                    </div>	
                                                </div>		
                                            </div>
                                        {/if}
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