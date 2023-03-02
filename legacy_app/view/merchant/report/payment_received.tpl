
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="page-bar">
        {include file="../../common/breadcumbs.tpl" title={$title} links=$links}
    </div>
    <!-- END PAGE HEADER-->
    <div class="row">
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
                    <form class="form-inline" method="post" role="form">
                        <div class="form-group">
                            <label class="help-block">Paid on</label>
                            <input class="form-control form-control-inline  rpt-date" id="daterange"  autocomplete="off" data-date-format="{$session_date_format}"  name="date_range" type="text" value="{$from_date} - {$to_date}" placeholder="From date"/>
                        </div>

                        <div class="form-group">
                            <label class="help-block">{$customer_default_column.customer_name|default:'Contact person name'}</label>
                            <select class="form-control  select2me" data-placeholder="{$customer_default_column.customer_name|default:'Contact person name'}" name="customer_name">
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
                                    <option value="0">Select franchise</option>
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
                                    <option value="0">Select vendor</option>
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
                            <label class="help-block">&nbsp;</label>
                            <button type="submit" class="btn blue">Search</button>
                            <button type="submit" name="export" class="btn green">Excel export</button>

                        </div>
                    </form>
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
                                    Paid on
                                </th>
                                <th>
                                    Payment #
                                </th>
                                
                                <th class="td-c">
                                    Invoice no.
                                </th>
                                <th >
                                {$customer_default_column.customer_code|default:'Customer code'}
                                </th>
                                <th>
                                {$customer_default_column.customer_name|default:'Contact person name'}
                                </th>
                                <th>
                                    {$company_column_name}
                                </th>
                                <th class="td-c">
                                    Cycle name
                                </th>

                                
                                <th>
                                    Reference #
                                </th>

                                <th>
                                    Method
                                </th>
                                <th>
                                    Late?
                                </th>
                                <th>
                                    TDS
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
                                <th  colspan="12" style=""></th>
                            </tr>
                        </tfoot>

                    </table>
                </div>
            </div>

            <!-- END PAYMENT TRANSACTION TABLE -->
        </div>

    </div>

    <!-- END PAGE CONTENT-->
</div>
</div>
<!-- END CONTENT -->

