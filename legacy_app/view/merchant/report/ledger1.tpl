
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
                    <form class="form-inline" method="post" role="form">
                        <div class="form-group">
                            <label class="help-block">Paid on</label>
                            <input class="form-control form-control-inline  rpt-date" id="daterange"  autocomplete="off" data-date-format="{$session_date_format}"  name="date_range" type="text" value="{$from_date} - {$to_date}" placeholder="From date"/>
                        </div>

                        <div class="form-group">
                            <label class="help-block">Invoice Status</label>
                            <select class="form-control " data-placeholder="" name="type">
                                <option {if $type==1}selected {/if} value="1" selected>Paid online</option>
                                <option {if $type==2}selected {/if} value="2">All invoices</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="help-block">{$customer_default_column.customer_name|default:'Customer name'}</label>
                            <select class="form-control  select2me" data-placeholder="{$customer_default_column.customer_name|default:'Customer name'}" name="customer_name">
                                <option value=""></option>
                                {foreach from=$customer_list item=v}
                                    {if {{$customer_selected}=={$v.customer_code}}}
                                        <option selected value="{$v.customer_code}" selected>{$v.first_name} {$v.last_name} ({$v.customer_code})</option>
                                    {else}
                                        <option value="{$v.customer_code}">{$v.first_name} {$v.last_name} ({$v.customer_code})</option>
                                    {/if}

                                {/foreach}
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="help-block">Column name</label>
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
                            <label class="help-block">&nbsp;</label>
                            <button type="submit" class="btn  blue">Search</button>
                            <button type="submit" name="export" class="btn  green">Excel export</button>

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
                    <table class="table table-striped table-bordered table-hover" id="table-small-nosort">
                        <thead>
                            <tr>
                                <th>
                                    Paid on
                                </th>
                                <th>
                                    Paid on
                                </th>
                                <th>
                                    Customer Detail
                                </th>

                                <th>
                                    Transaction Detail
                                </th>
                                <th>
                                    Invoice type
                                </th>
                                <th>
                                    Debit
                                </th>
                                <th>
                                    Credit
                                </th>
                                <th>
                                    TDR
                                </th>
                                <th>
                                    S.T.
                                </th>
                                <th>
                                    Settlement date
                                </th>
                                <th>
                                    Bank Reff
                                </th>
                                {foreach from=$column_select item=v}
                                    <th>
                                        {$v}
                                    </th>
                                {/foreach}
                            </tr>
                        </thead>
                        <tbody>
                            {foreach from=$reportlist item=v}
                                <tr >
                                    <td>
                                        {$v.date}
                                    </td>
                                    <td>
                                        {{$v.date}|date_format:"%d %b %Y %I:%M %p"}
                                    </td>
                                    <td>
                                        {$v.customer_detail}
                                    </td>

                                    <td>
                                        {$v.transaction_id} {$v.invoice_number}
                                    </td>
                                    <td>
                                        {$v.type}
                                    </td>
                                    <td>


                                        {if $v.debit!=''}
                                            {$v.debit|string_format:"%.2f"}
                                        {/if}
                                    </td>
                                    <td>
                                        {if $v.credit!=''}
                                            {$v.credit|string_format:"%.2f"}
                                        {/if}
                                    </td>
                                    <td>
                                        {if $v.tdr!=''}
                                            {$v.tdr|string_format:"%.2f"}
                                        {/if}
                                    </td>
                                    <td>
                                        {if $v.st!=''}
                                            {$v.st|string_format:"%.2f"}
                                        {/if}
                                    </td>
                                    <td>
                                        {$v.settlement_date}
                                    </td>
                                    <td>
                                        {$v.bank_ref}
                                    </td>
                                    {$add='__'}
                                    {foreach from=$column_select item=cl}
                                        {if $cl!=''}
                                            {$col=$cl|replace:'.':''}
                                            {$col={$add|cat:$col|replace:' ':'_'}}
                                            <td>
                                                {$v.{$col}}
                                            </td>
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

    </div>

    <!-- END PAGE CONTENT-->
</div>
</div>
<!-- END CONTENT -->

