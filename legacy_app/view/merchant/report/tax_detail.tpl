
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
                    <div class="alert alert-danger">
                        <button type="button" class="close" data-dismiss="alert"></button>
                        <strong>Error!</strong>
                        <div class="media">
                            <p class="media-heading">{$haserrors}.</p>
                        </div>

                    </div>
                {/if}
                <!-- BEGIN PORTLET-->
                <div class="portlet">

                    <!-- BEGIN PORTLET-->

                    <div class="portlet-body ">
                        <div class="form-group">
                            <label class="help-block">Billing date</label>
                            <input class="form-control form-control-inline  rpt-date" id="daterange"  autocomplete="off" data-date-format="{$session_date_format}"  name="date_range" type="text" value="{$from_date} - {$to_date}" placeholder="From date"/>
                        </div>
                        <div class="form-group">
                            <label class="help-block">Template name</label>
                            <select class="form-control  select2me" data-placeholder="Template name" name="template_id">
                                <option value=""></option>
                                {foreach from=$templatelist item=v}
                                    {if {{$template_id}=={$v.template_id}}}
                                        <option selected value="{$v.template_id}" selected>{$v.template_name}</option>
                                    {else}
                                        <option value="{$v.template_id}">{$v.template_name}</option>
                                    {/if}

                                {/foreach}
                            </select>
                        </div>
                        {if !empty($billing_profile_list)}
                            {if count($billing_profile_list)>1}
                                <div class="form-group">
                                    <label class="help-block">Billing profile</label>
                                    <select class="form-control rpt-date" data-placeholder="Billing profile" name="billing_profile_id">
                                        <option value="0">Select..</option>
                                        {foreach from=$billing_profile_list item=v}
                                            {if {{$billing_profile_id}=={$v.id}}}
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
                            <label class="help-block">Status</label>
                            <select class="form-control " name="status" data-placeholder="Invoice status">
                                <option value="">Select status</option>
                                {foreach from=$status_list key=k item=v}
                                    {$v.config_key}
                                    {if {{$status_selected}=={$v.config_key}}}
                                        <option selected value="{$v.config_key}" selected>{$v.config_value}</option>
                                    {else}
                                        <option value="{$v.config_key}" >{$v.config_value}</option>
                                    {/if}
                                {/foreach}
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="help-block">Column name (Max 5)</label>
                            <select multiple  id="column_name" data-placeholder="Column name" name="column_name[]">
                                {foreach from=$column_list item=v}
                                    {if $v.column_name!=''}
                                        {if in_array($v.column_name, $column_select) || in_array($v.column_value, $column_select)} 
                                            <option selected value="{$v.column_name}" >{if (isset($v.column_value))}{$v.column_value}{else}{$v.column_name}{/if}</option>
                                        {else}
                                            <option value="{$v.column_name}">{if (isset($v.column_value))}{$v.column_value}{else}{$v.column_name}{/if}</option>
                                        {/if}
                                    {/if}
                                {/foreach}
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="help-block">&nbsp;</label>
                            <button type="submit" name="submit" class="btn  blue" title="Search invoices">Search</button>
                            <button type="submit" name="exportExcel" class="btn  green" title="Download report in excel format">Excel export</button>
                        </div>

                    </div>
                </div>
                <!-- END PORTLET-->
            </div>  


            <div class="col-md-12">
                <!-- BEGIN PAYMENT TRANSACTION TABLE -->

                <div class="portlet ">
                    <div class="portlet-body">
                        <table class="table table-striped table-bordered table-hover" id="table-small">
                            <thead>
                                <tr>
                                    <th>
                                        Request #
                                    </th>
                                    <th class="td-c">
                                        Invoice #
                                    </th>
                                    <th>
                                        Bill Date
                                    </th>
                                    <th>
                                        User code
                                    </th>
                                    <th>
                                        {$customer_default_column.customer_name|default:'Customer name'}
                                    </th>
                                    <th>
                                        {$company_column_name}
                                    </th>
                                    <th>
                                        Due Date
                                    </th>
                                    <th>
                                        Status
                                    </th>
                                    <th>
                                        Base amount
                                    </th>
                                    <th>
                                        Invoice total
                                    </th>
                                    {foreach from=$column_select item=v}
                                        {if $v!='_P_Particulars' && $v!='_T_Taxes'}
                                            <th>
                                                {$col=$v|replace:'_C_':''}
                                                {$col}
                                            </th>
                                        {/if}
                                    {/foreach}
                                </tr>
                            </thead>
                            <tbody>
                                {foreach from=$reportlist item=v}
                                    <tr>
                                        <td>
                                            {$v.invoice_id}
                                        </td>

                                        <td class="td-c">
                                            {$v.invoice_number}
                                        </td>
                                        <td>
                                            {$v.bill_date|date_format:"%d %b %Y"}
                                        </td>
                                        <td>
                                            {$v.customer_code}
                                        </td>
                                        <td>
                                            {$v.customer_name}
                                        </td>
                                        <td>
                                            {$v.company_name}
                                        </td>
                                        <td>
                                            {$v.due_date|date_format:"%d %b %Y"}
                                        </td>
                                        <td>
                                            {$v.status}
                                        </td>
                                        <td>
                                            {$v.basic_amount|number_format:2:".":","}
                                        </td>
                                        <td>
                                            {$v.invoice_amount|number_format:2:".":","}
                                        </td>
                                        {$add='__'}
                                        {foreach from=$column_select item=cl}
                                            {if $cl!='_P_Particulars' && $cl!='_T_Taxes'}
                                                {if $cl!=''}
                                                    {$col=$cl|replace:'_C_':''}
                                                    {$col=$col|replace:'.':''}
                                                    {$col={$add|cat:$col|replace:' ':'_'}}
                                                    <td>
                                                      {$v.{$col}}
                                                    </td>
                                                {/if}
                                            {/if}
                                        {/foreach}
                                    </tr>
                                {/foreach}

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

