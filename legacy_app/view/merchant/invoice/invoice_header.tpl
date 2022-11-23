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
        {if isset($success)}
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert"></button>
                <strong>Success!</strong>{$success}
            </div> 
        {/if}
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
                <form action="/merchant/invoice/invoicesave" onsubmit="{if $info.has_covering_note}return coveringInvoice('');{else}return validateInvoice('');{/if}" id="invoice" method="post"  class="form-horizontal" enctype="multipart/form-data">
                    {CSRF::create('invoice')}
                    <div >

                        <div class="row invoice-logo">
                            <div class="col-xs-6">
                                {if $request_type==2}
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Estimate title <span class="required"> </span>
                                            <i class="popovers fa fa-info-circle support blue" data-container="body" data-trigger="hover" data-content="This is the title which shows in the estimate sent to the customer. You can customize this by changing this value." data-original-title="" title=""></i>
                                        </label>
                                        <div class="col-md-6">
                                            <input type="text" maxlength="100" class="form-control" name="invoice_title" value="{if ($plugin.invoice_title!='')}{$plugin.invoice_title}{else}Proforma invoice{/if}">
                                        </div>
                                    </div>
                                {/if}
                            </div>
                            <div class="col-xs-6">
                                {foreach from=$main_header item=v}
                                    {if $v.default_column_value!='Profile'}
                                        <div class="form-group" >
                                            <label class="control-label col-md-4">{$v.column_name} </label>
                                            <div class="col-md-8">
                                                <div class="input-icon right">
                                                    {assign var='valid' value=$validate.{$v.datatype}}
                                                    {if $v.datatype=="textarea"}
                                                        <textarea name="mainvalues[]" {$valid} class="form-control" ></textarea>
                                                    {else}
                                                        <input type="text" {$valid} {if $v.default_column_value!='Profile' && $v.default_column_value!='Custom'} value="{$v.default_column_value}" {/if} name="mainvalues[]" class="form-control" >
                                                    {/if}
                                                </div>	
                                            </div>		
                                            <input type="hidden" name="mainids[]" value="{$v.column_id}" class="form-control" >
                                        </div>
                                    {/if}
                                {/foreach}
                            </div>
                        </div>
                        <h3 class="form-section">{if $request_type==2}Estimate{else}Invoice{/if} information</h3>
                        <div class="row">
                            <div class="col-md-6" data-tour="invoice-create-customer-select">
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
                            <div class="col-md-6" data-tour="invoice-create-billing-information">
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
                                    {if $v.position=='R'}
                                        {if $request_type!=2 || $v.function_id!=9}
                                            {assign var='valid' value=$validate.{$v.datatype}}

                                            {if $v.is_mandatory==1}
                                                {assign var='req' value='required'}
                                            {/if}
                                            <div class="form-group">
                                                 {if $v.column_name == 'Billing cycle name'}
                                                    <label class="control-label col-md-4">{$v.column_name}{if $v.datatype=="percent"} (%){/if}{if $textarea_id=='textarea2' && $v.datatype =='textarea'} <br> (as above)<input type="checkbox" id="sameas" onchange="samevalue('textarea1', 'textarea2');" > {/if}
                                                        <i class="popovers fa fa-info-circle support blue" data-placement="top" data-container="body" data-trigger="click" data-content="Billing cycle name field is not visible to your customer i.e. this field will not be shown in the invoice you create. The purpose of this field is to group your invoices for your reporting purposes. For ex. All invoice raised in April 2021 can be tagged as 'Apr2021'" data-original-title="" title=""></i>
                                                    </label>
                                                {else}
                                                    <label class="control-label col-md-4">{$v.column_name}{if $v.datatype=="percent"} (%){/if}{if $textarea_id=='textarea2' && $v.datatype =='textarea'} <br> (as above)<input type="checkbox" id="sameas" onchange="samevalue('textarea1', 'textarea2');" > {/if}{if $v.is_mandatory==1}<span class="required">* </span>{/if}</label>
                                                {/if}
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
                                                            <input type="text" {$req} value="{$v.value}" id="{$v.id}" name="{$field_name}"  autocomplete="off" class="form-control date-picker" data-date-format="dd M yyyy" >
                                                        {else if $v.datatype=="time"}
                                                            <input type="text" {$req} autocomplete="off" value="{$v.value}" id="{$v.id}" name="{$field_name}"   class="form-control timepicker timepicker-no-seconds" >
                                                        {else}
                                                           <input type="text" id="{$v.id}"  {if $v.column_name != 'Billing cycle name'} {$req} {/if}  {$valid} {if $v.column_position==4} value="{if isset($v.value)}{$v.value}{else}{$cycleName}{/if}" {else} value="{$v.value}" {/if} name="{$field_name}" class="form-control" >
                                                        {/if}

                                                    </div>
                                                </div>
                                            </div>
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
                                {if $plugin.has_upload==1}
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
                            </div>
                        </div>
                        {if isset($properties.vehicle_det_section)}
                            <h3 class="form-section">Booking information</h3>
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
