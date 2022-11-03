
<div class="page-content">
    <div class="page-bar">
        {include file="../../common/breadcumbs.tpl" title={$title} links=$links}
    </div>
    <div class="row">
        <!-- END SEARCH CONTENT-->

        <div class="loading" id="loader" style="display: none;">Loading&#8230;</div>
        <div class="col-md-12">
            <div class="portlet">
                <div class="portlet-title">
                    <div class="caption">
                        Filter your data
                    </div>
                    <div class="tools">
                        <a href="javascript:;" class="collapse" data-original-title="" title="">
                        </a>
                    </div>
                </div>
                <!-- BEGIN PORTLET-->

                <div class="portlet-body ">
                    <form class="form-inline" method="post" role="form">
                        <div class="form-group">
                            <label class="help-block">Show column (Max 5)</label>
                            <select multiple  id="column_name" class="full-width-div" data-placeholder="Column name" name="column_name[]">
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
                            <label class="help-block">Filter by</label>
                            <select class="form-control full-width-div" data-placeholder="Filter by" name="filter_by">
                                <option value="">Filter by</option>
                                <option value="customer_code" {if $filter_by=='customer_code'}selected={/if}>Customer code</option>
                                <option value="__Address" {if $filter_by=='__Address'}selected={/if}>Address</option>
                                <option value="__City" {if $filter_by=='__City'}selected={/if}>City</option>
                                <option value="__Zipcode" {if $filter_by=='__Zipcode'}selected={/if}>Zipcode</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="help-block">Filter Condition</label>
                            <select class="form-control full-width-div" data-placeholder="Column name" name="filter_condition">
                                <option value="">Filter Condition</option>
                                <option value="equal" {if $filter_condition=='equal'}selected={/if}>Equal to</option>
                                <option value="start_with" {if $filter_condition=='start_with'}selected={/if}>Starts with</option>
                                <option value="contain" {if $filter_condition=='contain'}selected={/if}>Contain</option>

                            </select>
                        </div>
                        <div class="form-group">
                            <label class="help-block">Filter value</label>
                            <input type="text" name="filter_value" value="{$filter_value}" maxlength="45" class="form-control  full-width-div">
                        </div>
                        <div class="form-group">
                            <label class="help-block">Choose group</label>
                            <select name="group" id="fil_grp" class="form-control full-width-div">    
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

                        </div>
                    </form>
                </div>
            </div>
        </div>

        <form  action="" method="post" role="form">
            <div class="col-md-9">
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
                {if isset($success)}
                    <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert"></button>
                        <strong>Success!</strong>  {$success}
                    </div> 
                {/if}
                <div class="alert alert-danger display-none" id="grp_errors">
                </div>
                <div class="alert alert-success display-none" id="grp_success">
                </div>


                <!-- BEGIN PAYMENT TRANSACTION TABLE -->
                <div class="portlet ">
                    <div class="portlet-body">
                        <table id="example" class="display" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                <tr>

                                    <th class="no-sort">
                                        <label><input id="checkAll" type="checkbox"><b>All</b></label>
                                    </th>

                                    <th>
                                        Set Top Box
                                    </th>
                                    <th>
                                        Amount
                                    </th>
                                    <th>
                                    {$customer_default_column.customer_code|default:'Customer code'}
                                    </th>
                                    <th>
                                    {$customer_default_column.customer_name|default:'Customer name'}
                                    </th>

                                    <th>
                                        Mobile
                                    </th>
                                    {foreach from=$column_select item=v}
                                        <th>
                                            {$v}
                                        </th>
                                    {/foreach}


                                </tr>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>


                <!-- END PAYMENT TRANSACTION TABLE -->

            </div>
            <div class="col-md-3">

                <div class="portlet">

                    <!-- BEGIN PORTLET-->

                    <div class="portlet-body ">
                        <div class="form-group">
                            <label class="help-block"><b>Create subscription for selected customers</b></label>
                            <label class="help-block">Choose template</label>
                            <select name="template_id" id="cat_drop" required="" class="form-control" data-placeholder="Select..." aria-required="true">
                                <option value="">Select Template</option>
                                {foreach from=$templatelist item=v}
                                    {if $v.template_type=='isp'}
                                        {if {{$template_selected}=={$v.template_id}}}
                                            <option selected value="{$v.template_id}" selected>{$v.template_name}</option>
                                        {else}
                                            <option value="{$v.template_id}">{$v.template_name}</option>
                                        {/if}
                                    {/if}

                                {/foreach}
                            </select>

                        </div>
                        <div class="form-group">
                            <label class="help-block">Start date</label>
                            <input class="form-control form-control-inline date-picker" type="text" required  value="{$current_date}" name="bill_date"  autocomplete="off" data-date-format="dd M yyyy"  placeholder="Start date"/>
                        </div>
                        <div class="form-group">
                            <label class="help-block">Due date</label>
                            <input class="form-control form-control-inline date-picker" type="text" required  value="" name="due_date"  autocomplete="off" data-date-format="dd M yyyy"  placeholder="Due date"/>
                        </div>
                        <div class="form-group">
                            <button  type="submit" name="subscription" class="btn green">Create Subscription</button>
                            
                        </div>
                    </div>
                </div>

            </div>  
        </form>
    </div>

    <!-- END PAGE CONTENT-->
</div>
</div>
<!-- END CONTENT -->



