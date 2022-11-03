<script>
    var t = '{$pg.swipez_fee_type}';
    var e = '{$pg.swipez_fee_val}';
    var a = '{$pg.pg_fee_type}';
    var n = '{$pg.pg_fee_val}';
    var u = '{$pg.pg_tax_val}';
</script>

<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <h3 class="page-title">Update a payment request</h3>
    <!-- END PAGE HEADER-->

    <!-- BEGIN SEARCH CONTENT-->
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
                    <form action="/merchant/bulkupload/invoiceupdate" method="post" id="invoice_create" class="form-horizontal form-row-sepe">
                        <div class="form-body">
                            <div>
                                <div class="row invoice-logo">
                                    <div class="col-xs-3 invoice-logo-space">
                                        <img src="/uploads/images/logos/{$info.image_path}" class="img-responsive  templatelogo" alt=""/>
                                    </div>
                                    <div class="col-xs-9">

                                    </div>
                                </div>
                                <hr/>
                            </div>

                            <h3 class="form-section">Invoice details</h3>


                            <div class="row">
                                <div class="col-md-6">
                                    {foreach from=$request_value item=v}
                                        {if $v.position=='L'}
                                            <div class="form-group">
                                                <label class="control-label col-md-4">{$v.column_name} {if $v.is_mandatory==1}<span class="required">* </span>{/if}</label>
                                                <div class="col-md-8">
                                                    <div class="input-icon right">
                                                        {assign var='valid' value=$validate.{$v.column_datatype}}
                                                        {if $v.column_position==1}
                                                            {assign var='valid' value=$validate.name}
                                                        {/if}



                                                        {$field_name="requestvalue[]"}
                                                        {$value=$v.value}
                                                        <input type="hidden" name="request_function_id[]" value="{$v.function_id}" >

                                                        {if $v.column_datatype=="textarea"}
                                                            <textarea type="text"  name="{$field_name}" id="{$v.id}" class="form-control"  placeholder="Enter specific value">{$value}</textarea>
                                                        {else if $v.column_datatype=="date"}
                                                            <input type="text" name="{$field_name}" id="{$v.id}" value="{$value}" id="date{$v.column_position}" class="form-control date-picker"  autocomplete="off" data-date-format="dd M yyyy" >
                                                        {else}
                                                            <input type="text" id="{$v.id}" name="{$field_name}" {$valid} value="{$value}" class="form-control" >
                                                        {/if}
                                                    </div>	
                                                </div>
                                            </div>
                                        {/if}
                                    {/foreach}


                                    {foreach from=$header item=v}
                                        {if $v.position=='L'}
                                            <div class="form-group">
                                                <label class="control-label col-md-4">{$v.column_name} {if $v.is_mandatory==1}<span class="required">* </span>{/if}</label>
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
                                                            <input type="text" name="{$field_name}" id="{$v.id}" value="{$value}" class="form-control date-picker"  autocomplete="off" data-date-format="dd M yyyy" >
                                                        {else if $v.column_datatype=="primary"}
                                                            <input type="text"  {$valid} value="{$value}" onblur="document.getElementById('primary').value = this.value;" name="{$field_name}" class="form-control" >
                                                            <input type="hidden"   {$valid}  id="primary" value="{$value}" name="primary" class="form-control" >
                                                        {else}
                                                            <input type="text" id="{$v.id}" {$valid} name="{$field_name}" value="{$value}" class="form-control" >
                                                        {/if}

                                                    </div>	
                                                </div>
                                            </div>
                                        {/if}
                                    {/foreach}


                                </div>

                                <div class="col-md-6">
                                    {foreach from=$header item=v}
                                        {if $v.position=='R'}
                                            <div class="form-group">
                                                <label class="control-label col-md-4">{$v.column_name} {if $v.is_mandatory==1}<span class="required">* </span>{/if}</label>
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
                                                            <input type="text" name="{$field_name}" id="{$v.id}" value="{$value}" class="form-control date-picker"  autocomplete="off" data-date-format="dd M yyyy" >
                                                        {else if $v.column_datatype=="primary"}
                                                            <input type="text"  {$valid} value="{$value}" onblur="document.getElementById('primary').value = this.value;" name="{$field_name}" class="form-control" >
                                                            <input type="hidden"   id="primary" value="{$value}" name="primary" class="form-control" >
                                                        {else}
                                                            <input type="text" {$valid} id="{$v.id}" name="{$field_name}" value="{$value}" class="form-control" >
                                                        {/if}

                                                    </div>	
                                                </div>
                                            </div>
                                        {/if}
                                    {/foreach}

                                    {foreach from=$request_value item=v}
                                        {if $v.position=='R'}
                                            <div class="form-group">
                                                <label class="control-label col-md-4">{$v.column_name}</label>
                                                <div class="col-md-8">
                                                    <div class="input-icon right">
                                                        {assign var='valid' value=$validate.{$v.column_datatype}}
                                                        {if $v.column_position==1}
                                                            {assign var='valid' value=$validate.name}
                                                        {/if}

                                                        {$field_name="requestvalue[]"}
                                                        {$value=$v.value}
                                                        <input type="hidden" name="request_function_id[]" value="{$v.function_id}" >

                                                        {if $v.column_position==8}
                                                            <input type="text" required="" {$valid} value="{$value}" name="{$field_name}" id="amount" onblur="add_late_fee();" class="form-control" aria-required="true">
                                                        {elseif $v.column_position==9}
                                                            <input type="text" required="" {$valid} value="{$value}" name="{$field_name}" id="late_fee" onblur="add_late_fee();" class="form-control" aria-required="true">
                                                        {elseif $v.column_position==10}
                                                            <input type="text" required="" {$valid} value="{$value}" name="{$field_name}" readonly id="amount_with_latefee" onblur="add_late_fee();" class="form-control" aria-required="true">
                                                        {else}
                                                            {if $v.column_datatype=="textarea"}
                                                                <textarea type="text"  name="{$field_name}" class="form-control"  placeholder="Enter specific value">{$value}</textarea>
                                                            {else if $v.column_datatype=="date"}
                                                                <input type="text" name="{$field_name}" value="{$value}" class="form-control date-picker"  autocomplete="off" data-date-format="dd M yyyy" >
                                                            {else if $v.column_datatype=="primary"}
                                                                <input type="text"  {$valid} value="{$value}" onblur="document.getElementById('primary').value = this.value;" name="{$field_name}" class="form-control" >
                                                                <input type="hidden"     id="primary" value="{$value}" name="primary" class="form-control" >
                                                            {else}
                                                                <input type="text" {$valid} name="{$field_name}" value="{$value}" class="form-control" >
                                                            {/if}
                                                        {/if}

                                                    </div>	
                                                </div>
                                            </div>
                                        {/if}
                                    {/foreach}
                                </div>
                            </div>