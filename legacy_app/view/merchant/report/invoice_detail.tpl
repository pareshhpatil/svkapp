
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="page-bar">
        {include file="../../common/breadcumbs.tpl" title={$title} links=$links}
    </div>
    <!-- END PAGE HEADER-->
    <div class="row">
        <div class="loading" id="loader" style="display: none;">Loading&#8230;</div>
        <form class="form-inline"  method="post" role="form">

            <!-- END SEARCH CONTENT-->
            <div class="col-md-12">
                {if isset($haserrors)}
                    <div class="alert alert-danger nolr-margin">
                        <div class="media">
                            <p class="media-heading"><strong></strong>{$haserrors}.</p>
                        </div>

                    </div>
                {/if}
                <!-- BEGIN PORTLET-->
                <div class="portlet">

                    <!-- BEGIN PORTLET-->

                    <div class="portlet-body ">
                        <div class="form-group">
                            <label class="help-block">Report by</label>
                            <select class="form-control " onchange="document.getElementById('rptby').innerHTML = $(this).find('option:selected').text();" data-placeholder="Aging interval" name="aging_by">
                                <option value="bill_date">Billing date</option>
                                <option value="due_date" {if {$aging_by_selected == 'due_date'}} selected {/if}>Due date</option>
                                <option value="created_date" {if {$aging_by_selected == 'created_date'}} selected {/if} >Sent date</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="help-block" id="rptby">{if $aging_by_selected == 'due_date'}Due date{elseif $aging_by_selected == 'created_date'}Sent date{else}Billing date{/if}</label>
                            <input class="form-control form-control-inline  rpt-date" id="daterange"  autocomplete="off" data-date-format="{$session_date_format}"  name="date_range" type="text" value="{$from_date} - {$to_date}" placeholder="From date"/>
                        </div>

                        {if !empty($billing_profile_list)}
                        {if count($billing_profile_list)>1}
                            <div class="form-group">
                                <label class="help-block">Billing profile</label>
                                <select class="form-control rpt-date" data-placeholder="Billing profile" name="billing_profile_id">
                                    <option value="0">Select..</option>
                                    {foreach from=$billing_profile_list item=v}
                                        {if {$billing_profile_id==$v.id}}
                                            <option selected value="{$v.id}" selected>{$v.profile_name} {$v.gst_number}</option>
                                        {else}
                                            <option value="{$v.id}">{$v.profile_name} {$v.gst_number}</option>
                                        {/if}
                                    {/foreach}
                                </select>
                            </div>
                        {/if}
                        {/if}

                        <div class="form-group">
                            <label class="help-block">{$customer_default_column.customer_name|default:'Customer name'}</label>
                            <select class="form-control  select2me" data-placeholder="{$customer_default_column.customer_name|default:'Customer name'}" name="customer_id">
                                <option value=""></option>
                                {foreach from=$customer_list item=v}
                                    {if {{$customer_selected}=={$v.customer_id}}}
                                        <option selected value="{$v.customer_id}" selected>{$v.name}
                                        {if $v.company_name}
                                            ({$v.company_name})
                                        {/if}
                                        </option>
                                    {else}
                                        <option value="{$v.customer_id}">{$v.name}
                                        {if $v.company_name}
                                            ({$v.company_name})
                                        {/if}
                                        </option>
                                    {/if}

                                {/foreach}
                            </select>
                        </div>
                        {if !empty($franchise_list)}
                            <div class="form-group">
                                <label class="help-block">Franchise name</label>
                                <select class="form-control rpt-date" data-placeholder="Franchise name" name="franchise_id">
                                    <option value="0">Select..</option>
                                    {foreach from=$franchise_list item=v}
                                        {if {{$franchise_id}=={$v.franchise_id}}}
                                            <option selected value="{$v.franchise_id}" selected>{$v.franchise_name}</option>
                                        {else}
                                            <option value="{$v.franchise_id}">{$v.franchise_name}</option>
                                        {/if}
                                    {/foreach}
                                </select>
                            </div>
                        {/if}
                        {if !empty($vendor_list)}
                            <div class="form-group">
                                <label class="help-block">Vendor name</label>
                                <select class="form-control rpt-date" data-placeholder="Vendor name" name="vendor_id">
                                    <option value="0">Select..</option>
                                    {foreach from=$vendor_list item=v}
                                        {if {{$vendor_id}=={$v.vendor_id}}}
                                            <option selected value="{$v.vendor_id}" selected>{$v.vendor_name}</option>
                                        {else}
                                            <option value="{$v.vendor_id}">{$v.vendor_name}</option>
                                        {/if}
                                    {/foreach}
                                </select>
                            </div>
                        {/if}
                        <div class="form-group">
                            <label class="help-block">Cycle name</label>
                            <select class="form-control  select2me rpt-date" data-placeholder="Cycle name" name="billing_cycle_name">
                                <option value=""></option>
                                {foreach from=$cycle_list item=v}
                                    {if {{$cycle_selected}=={$v.id}}}
                                        <option selected value="{$v.id}" selected>{$v.name}</option>
                                    {else}
                                        <option value="{$v.id}">{$v.name}</option>
                                    {/if}

                                {/foreach}
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="help-block">Column name (Max 5)</label>
                            <select multiple  id="column_name" data-placeholder="Column name" name="column_name[]">
                                {foreach from=$column_list item=v}
                                    {if $v.column_name!=''}
                                        {if in_array($v.column_name, $column_select)} 
                                            <option selected value="{$v.column_name}" >{$v.column_name}</option>
                                        {else}
                                            <option value="{$v.column_name}">{$v.column_name}</option>
                                        {/if}
                                    {/if}
                                {/foreach}
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="help-block">Choose group</label>
                            <select name="group"  class="form-control full-width-div rpt-date">    
                                <option value="">All customers</option>
                                {foreach from=$customer_group item=v}
                                    {if $v.group_id== $group} 
                                        <option selected value="{$v.group_id}" >{$v.group_name}</option>
                                    {else}
                                        <option value="{$v.group_id}" >{$v.group_name}</option>
                                    {/if}
                                {/foreach}
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="help-block">Invoice status</label>
                            <select multiple class="multi_column" id="column_name" data-placeholder="Invoice status" name="status[]">
                                {foreach from=$statuslist item=v}
                                    {if in_array($v.config_key, $checkedlist)} 
                                        <option selected value="{$v.config_key}" >{$v.config_value}</option>
                                    {else}
                                        <option value="{$v.config_key}">{$v.config_value}</option>
                                    {/if}
                                {/foreach}
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="help-block">&nbsp;</label>
                            <button type="submit" name="submit" class="btn blue" title="Search invoices">Search</button>
                            <button type="submit" name="exportExcel" class="btn green" title="Download report in excel format">Excel export</button>
                            <button type="submit" name="exportPDF" class="btn  green" title="Bulk download invoices in PDF format">PDF download</button>
                        </div>

                    </div>
                </div>
                <!-- END PORTLET-->
            </div>  


            <div class="col-md-12">
                <!-- BEGIN PAYMENT TRANSACTION TABLE -->

                <div class="portlet ">
                    <div class="portlet-body">
                        <table class="table table-striped table-bordered table-hover" id="example">
                            <thead>
                                <tr>
                                    <th>
                                        System #
                                    </th>
                                    <th class="td-c">
                                        {if $invoice_type==1} Invoice{else} Estimate{/if} #
                                    </th>
                                    <th>
                                        Billing Date
                                    </th>
                                    <th>
                                        User code
                                    </th>
                                    <th>
                                        Name
                                    </th>
                                    <th>
                                        {$company_column_name}
                                    </th>
                                    <th>
                                        Cycle name
                                    </th>

                                    <th>
                                        Sent Date
                                    </th>
                                    
                                    <th>
                                        Due Date
                                    </th>
                                    <th>
                                        Status
                                    </th>
                                    <th>
                                        Amount
                                    </th>
                                    {foreach from=$column_select item=v}
                                        <th>
                                            {$v}
                                        </th>
                                    {/foreach}
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th colspan="11" style=""></th>
                                </tr>
                            </tfoot>
                            <tbody>


                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- END PAYMENT TRANSACTION TABLE -->

            </div>
        </form>
    </div>


    <!-- END PAGE CONTENT-->
</div>
</div>
<!-- END CONTENT -->

