<style>
    .pagination {
        margin-top: 10px !important;
    }
</style>
<div class="page-content">
    <div class="page-bar">
        {include file="../../common/breadcumbs.tpl" title={$title} links=$links}
    </div>
    <div class="row">
        <!-- END SEARCH CONTENT-->
        {if isset($haserrors)}
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert"></button>
                <strong>Error!</strong> {$haserrors}
            </div>
        {/if}
        {if isset($success)}
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert"></button>
                <strong>Success!</strong> {$success}
            </div>
        {/if}
        <div class="loading" id="loader" style="display: none;">Loading&#8230;</div>

        <div class="col-md-8">

            <div class="alert alert-danger display-none" id="grp_errors">
            </div>
            <div class="alert alert-success display-none" id="grp_success">
            </div>
            <form class="form-inline" id="customergroup" action="/merchant/customer/savecustomergroup" method="post"
                role="form">
                {CSRF::create('customer_group')}
                <div class="portlet">
                    <div class="portlet-body ">



                        <div class="form-group">
                            <label class="help-block">Choose group</label>
                            <div class="input-group">

                                <select name="group_id" id="cat_drop" required="" class="form-control"
                                    data-placeholder="Select..." aria-required="true">
                                    <option value="">Select Group</option>
                                    {foreach from=$customer_group item=v}
                                        <option value="{$v.group_id}">{$v.group_name}</option>
                                    {/foreach}
                                </select>

                                <span class="input-group-btn">
                                    <a data-toggle="modal" title="Add new customer" href="#custom" class="btn green"><i
                                            class="fa fa-plus"></i> Add new</a>
                                </span>
                            </div>
                        </div>



                        <div class="form-group">
                            <label class="help-block">&nbsp;</label>
                            <button type="submit" onclick="return save_customer_group();" class="btn blue">Add to
                                Group</button>
                        </div>


                    </div>
                </div>
                <!-- BEGIN PAYMENT TRANSACTION TABLE -->
                <div class="portlet ">
                    <div class="portlet-body">
                        <table id="example" class="display" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                <tr>

                                    <th class="no-sort" style="width: 10px;padding: 0;">
                                        <input id="checkAll" type="checkbox">
                                    </th>
                                    {* <th>
                                        {$customer_default_column.customer_code|default:$lang_title.customer_code}
                                    </th> *}

                                    <th>
                                        {$customer_default_column.customer_name|default:$lang_title.customer_name}
                                    </th>
                                    <th>
                                        {$customer_default_column.email|default:$lang_title.email}
                                    </th>
                                    <th>
                                        {$customer_default_column.mobile|default:$lang_title.mobile}
                                    </th>
                                    {foreach from=$column_select item=v}
                                        <th>
                                            {$v}
                                        </th>
                                    {/foreach}


                                </tr>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- END PAYMENT TRANSACTION TABLE -->
            </form>
        </div>
        <div class="col-md-4">

            <!-- BEGIN PORTLET-->
            <div class="portlet">
                <!-- BEGIN PORTLET-->

                <div class="portlet-body ">

                    <form class="form-inline" method="post" role="form">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="help-block">Show Custom column (Max 5)</label>
                                <select multiple id="column_name" class="full-width-div" data-placeholder="Column name"
                                    name="column_name[]">
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
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label class="help-block">Filter by</label>
                                <select class="form-control full-width-div" data-placeholder="Filter by"
                                    name="filter_by">
                                    <option value="">Filter by</option>
                                    <option value="customer_code" {if $filter_by=='customer_code'}selected={/if}>
                                        {$customer_default_column.customer_code|default:'Customer code'}</option>
                                    <option value="__Address" {if $filter_by=='__Address'}selected={/if}>Address
                                    </option>
                                    <option value="__City" {if $filter_by=='__City'}selected={/if}>City</option>
                                    <option value="__Zipcode" {if $filter_by=='__Zipcode'}selected={/if}>Zipcode
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label class="help-block">Filter Condition</label>
                                <select class="form-control full-width-div" data-placeholder="Column name"
                                    name="filter_condition">
                                    <option value="">Filter Condition</option>
                                    <option value="equal" {if $filter_condition=='equal'}selected={/if}>Equal to
                                    </option>
                                    <option value="start_with" {if $filter_condition=='start_with'}selected={/if}>Starts
                                        with</option>
                                    <option value="contain" {if $filter_condition=='contain'}selected={/if}>Contain
                                    </option>

                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label class="help-block">Filter value</label>
                                <input type="text" name="filter_value" value="{$filter_value}" maxlength="45"
                                    class="form-control  full-width-div">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label class="help-block">Choose group</label>
                                <select name="group" id="fil_grp" class="form-control full-width-div">
                                    <option value="">All customers</option>
                                    {foreach from=$customer_group item=v}
                                        {if $v.group_id== $group}
                                            <option selected value="{$v.group_id}">{$v.group_name}</option>
                                        {else}
                                            <option value="{$v.group_id}">{$v.group_name}</option>
                                        {/if}
                                    {/foreach}
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="pull-right">
                                    <label class="help-block">&nbsp;</label>
                                    <button type="submit" name="search" class="btn blue">Search</button>
                                    <button type="reset" class="btn default">Reset</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- END PORTLET-->
        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>
</div>
<!-- END CONTENT -->

<div class="modal fade bs-modal-lg in" id="custom" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">

    <div class="modal-dialog modal-lg">
        <form action="" method="post" id="categoryForm" class="form-horizontal">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close"
                        onclick="document.getElementById('errors').style.display = 'none';" id="closebutton1"
                        data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">List Group</h4>
                </div>

                <div class="modal-body">

                    <div class="row">

                        <div class="col-md-12">
                            <div class="portlet-body form">
                                {if !empty($customer_group)}
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>

                                                <th class="td-c">
                                                    Group name
                                                </th>
                                                <th class="td-c">

                                                </th>


                                            </tr>
                                        </thead>
                                        <tbody>
                                            {foreach from=$customer_group item=v}
                                                <tr>

                                                    <td class="td-c">
                                                        {$v.group_name}
                                                    </td>

                                                    <td class="td-c">
                                                        <a href="#basic"
                                                            onclick="document.getElementById('deleteanchor').href = '/merchant/customer/deletegroup/{$v.link}'"
                                                            data-toggle="modal" class="btn btn-xs red"><i
                                                                class="fa fa-times"></i> Delete </a>
                                                    </td>

                                                </tr>
                                            {/foreach}

                                        </tbody>
                                    </table>
                                {/if}

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="modal-header">
                            <h4 class="modal-title">Add Group</h4>
                        </div>

                        <div class="portlet-body form">
                            <div class="form-body">
                                <!-- Start profile details -->
                                <div class="alert alert-danger display-none" id="errors">

                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Group name<span class="required">*
                                                </span></label>
                                            <div class="col-md-4">
                                                <input type="text" id="category_name" maxlength="45" name="group_name"
                                                    value="" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End profile details -->
                            </div>

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn default"
                        onclick="document.getElementById('errors').style.display = 'none';" id="closebutton"
                        data-dismiss="modal">Close</button>
                    <button type="submit" onclick="return save_group();" class="btn blue">Save</button>
                </div>
            </div>

            <!-- /.modal-content -->
        </form>
    </div>
</div>

<div class="modal fade" id="basic" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Delete Group</h4>
            </div>
            <div class="modal-body">
                Are you sure you would not like to use this group in the future?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn default" data-dismiss="modal">Close</button>
                <a href="" id="deleteanchor" class="btn delete">Confirm</a>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>