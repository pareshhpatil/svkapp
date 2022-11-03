<style>
    .pagination {
        margin-top: 10px !important;
    }
</style>
<div class="page-content">
    <div class="page-bar">
        {include file="../../common/breadcumbs.tpl" title={$lang_title.customer_list} links=$links}
    </div>
    <div class="row">
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
        <!-- END SEARCH CONTENT-->
        <div class="col-md-12">

            <!-- BEGIN PORTLET-->
            <div class="portlet">

                <!-- BEGIN PORTLET-->

                <div class="portlet-body ">
                    <form class="form-inline" method="post" role="form">
                        <div class="form-group">
                            <label class="help-block">{$lang_title.payment} {$lang_title.status}</label>
                            <select class="form-control" data-placeholder="Status" name="payment_status">
                                <option value="">Select status</option>
                                <option value="0" {if {$payment_status== '0'}} selected {/if}>Not paid</option>
                                <option value="1" {if {$payment_status== '1'}} selected {/if}>Initiated</option>
                                <option value="2" {if {$payment_status== '2'}} selected {/if}>Paid</option>
                                <option value="3" {if {$payment_status== '3'}} selected {/if}>Failed</option>
                            </select>
                        </div>


                        <div class="form-group">
                            <label class="help-block">{$lang_title.custom_column}</label>
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
                            <label class="help-block">{$lang_title.choose_group}</label>
                            <select name="group" class="form-control full-width-div">
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

                        <div class="form-group">
                            <label class="help-block">&nbsp;</label>
                            <button type="submit" class="btn blue">{$lang_title.search}</button>
                            <button type="submit" name="export" class="btn green">{$lang_title.excel_export}</button>
                        </div>
                    </form>

                </div>
            </div>
            <!-- END PORTLET-->
        </div>


        <div class="col-md-12">
            <!-- BEGIN PAYMENT TRANSACTION TABLE -->

            <div class="portlet">

                <div class="portlet-body">
                    <table id="example" class="display" cellspacing="0" width="100%">
                        <thead>

                            <tr>

                                {* <th>
                                    {$customer_default_column.customer_code|default:$lang_title.customer_code}
                                </th> *}
                                <!--<th>
                                    Customer id
                                </th> -->
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
                                <th>
                                    Created on
                                </th>
                                <th>
                                    {$lang_title.payment}
                                </th>

                                <th style="width: 50px;">

                                </th>
                            </tr>
                        </thead>
                        <tbody  class="text-center">
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

<div class="modal fade" id="basic" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Delete Customer</h4>
            </div>
            <div class="modal-body">
                Are you sure you would not like to use this customer in the future?
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