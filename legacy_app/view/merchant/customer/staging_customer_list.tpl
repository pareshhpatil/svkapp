<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <h3 class="page-title">{$title}</h3>
    <!-- END PAGE HEADER-->
    <div class="row">
        <!-- END SEARCH CONTENT-->
        <div class="col-md-12">
            {if isset($haserrors)}
                <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert"></button>
                    <strong>Error!</strong> {$haserrors}
                </div>
            {/if}
            <!-- BEGIN PORTLET-->
            <div class="portlet">

                <!-- BEGIN PORTLET-->

                <div class="portlet-body ">
                    <form class="form-inline" method="post" role="form">
                        <div class="form-group">
                            <label class="help-block">Custom column (Max 5)</label>
                            <select multiple id="column_name" data-placeholder="Column name" name="column_name[]">
                                {foreach from=$column_list item=v}
                                    {if $v.column_name!='' && $v.column_datatype!='company_name'}
                                        {if in_array($v.column_name, $column_select)}
                                            <option selected value="{$v.column_name}">{$v.column_name}</option>
                                        {else}
                                            <option value="{$v.column_name}">{$v.column_name}</option>
                                        {/if}
                                    {/if}
                                {/foreach}
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="help-block">&nbsp;</label>
                            <button type="submit" class="btn btn-sm blue">Search</button>

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
                    <table class="table table-striped  table-hover" id="sample_1">
                        <thead>
                            <tr>
                                <th>
                                    Created date
                                </th>
                                {if $auto_generate==0}
                                    <th>
                                        {$customer_default_column.customer_code|default:$lang_title.customer_code}
                                    </th>
                                {/if}
                                <th>
                                    {$customer_default_column.customer_name|default:$lang_title.customer_name}
                                </th>
                                <th>
                                    {$company_column_name}
                                </th>
                                <th>
                                    {$customer_default_column.email|default:$lang_title.email}
                                </th>
                                <th>
                                    {$customer_default_column.mobile|default:$lang_title.mobile}
                                </th>
                                <th>
                                    {$customer_default_column.address|default:'Address'}
                                </th>
                                <th>
                                    {$customer_default_column.city|default:'Country'}
                                </th>
                                <th>
                                    {$customer_default_column.state|default:'State'}
                                </th>
                                <th>
                                    {$customer_default_column.city|default:'City'}
                                </th>
                                <th>
                                    {$customer_default_column.zipcode|default:'Zipcode'}
                                </th>

                                {foreach from=$column_select item=v}
                                    <th>
                                        {$v}
                                    </th>
                                {/foreach}

                            </tr>
                        </thead>
                        <tbody>
                            {foreach from=$requestlist item=v}
                                <tr>
                                    <td>
                                        {{$v.created_date}|date_format:"%Y-%m-%d"}
                                    </td>
                                    {if $auto_generate==0}
                                        <td>
                                            {$v.customer_code}
                                        </td>
                                    {/if}
                                    <td>
                                        {$v.name}
                                    </td>
                                    <td>
                                        {$v.company_name}
                                    </td>
                                    <td>
                                        {$v.email}
                                    </td>
                                    <td>
                                        {$v.mobile}
                                    </td>
                                    <td>
                                        {$v.address}
                                    </td>
                                    <td>
                                        {$v.country}
                                    </td>
                                    <td>
                                        {$v.state}
                                    </td>
                                    <td>
                                        {$v.city}
                                    </td>
                                    <td>
                                        {$v.zipcode}
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