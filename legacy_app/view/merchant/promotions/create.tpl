<style>
    .pagination{
        margin-top:10px !important;
    }
</style>
<div class="page-content">
    <div class="page-bar">
        {include file="../../common/breadcumbs.tpl" title={$title} links=$links}
    </div>
    <div class="row">
        <!-- END SEARCH CONTENT-->

        <div class="loading" id="loader" style="display: none;">Loading&#8230;</div>
        {if isset($haserrors)}
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert"></button>
                <strong>Error!</strong>  {$haserrors}
            </div> 
        {/if}
        {if isset($success)}
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert"></button>
                <strong>Success!</strong>  {$success}
            </div> 
        {/if}
        <div class="col-md-12">

            <div class="alert alert-danger display-none" id="grp_errors">
            </div>
            <div class="alert alert-success display-none" id="grp_success">
            </div>
            <form class="" id="promotion" action="/merchant/promotions/savepromotion" method="post">
                {CSRF::create('promotion_save')}
                <div class="portlet light bordered">
                    <!-- BEGIN PORTLET-->
                    
                    <div class="portlet-body form">
                        <div class="form-body">
                            <div class="row no-margin">
                                <div class="col-md-12">
                                    <label class="control-label col-md-3">{$lang_title.promotion_name}<span class="required">*
                                        </span></label>
                                    <div class="col-md-4" >
                                        <input type="text"  required maxlength="45" value="{$promotion_name}"  class="form-control" name="promotion_name">
                                    </div>
                                </div>
                            </div>
                            <div class="row no-margin">
                                <br>
                                <div class="col-md-12">
                                    <label class="control-label col-md-3">{$lang_title.select_sms} <span class="required">*
                                        </span></label>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <select onchange="setSMS();" id="cat_drop" required="" class="form-control" data-placeholder="Select..." aria-required="true">
                                                <option value="">Select SMS template</option>
                                                {foreach from=$promo_sms item=v}
                                                    <option title="{$v.sms}" value="{$v.id}" >{$v.template_name}</option>
                                                {/foreach}
                                            </select>


                                        </div>
                                    </div>
                                    <div class="col-md-3 pull-left">
                                        <a data-toggle="modal"  title="Add new customer" href="#custom" class="btn green pull-left"><i class="fa fa-plus"></i> {$lang_title.new_sms}</a>
                                    </div>
                                </div>
                            </div>
                            <div class="row no-margin">
                                <br>
                                <div class="col-md-12">
                                    <label class="control-label col-md-3">{$lang_title.message} <span class="required">
                                        </span></label>
                                    <div class="col-md-4" >
                                        <textarea  rows="3" class="form-control" readonly="" id="message" name="message"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row no-margin">
                                <br>
                                <div class="col-md-12">
                                    <label class="control-label col-md-3">Mobile number <br><span class="text-small">(separate multiple numbers with ,)</span><span class="required">
                                        </span></label>
                                    <div class="col-md-4" >
                                        <textarea type="text"  maxlength="1000" class="form-control" name="numbers">{$numbers}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="pull-right">
                                        <input type="hidden" name="template_id" id="template_id">
                                        <input type="hidden" name="sms" id="template_sms">
                                        <input type="hidden" name="template_name" id="template_name">
                                        <button type="submit" onclick="return save_promotion();" class="btn blue">{$lang_title.send_promo_sms}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- BEGIN PAYMENT TRANSACTION TABLE -->
                <div class="row">
                    <div class="col-md-8" >
                        <label class="caption">Select customers from list</label>
                    </div>
                    <div class="col-md-4" >

                        <div class="col-md-8 text-right" >
                            <label class="control-label">Customer group</label>
                        </div>
                        <div class="col-md-4 no-padding " >
                            <div class="form-group pull-right">
                                <select name="group" onchange="selectGroup(this.value);" class="form-control full-width-div ">    
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
                        </div>
                    </div>
                </div>
                <div class="portlet ">
                    <div class="portlet-body">
                        <table id="example" class="display" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                <tr>

                                    <th class="no-sort" style="width: 20px;">
                                        <input id="checkAll" type="checkbox">
                                    </th>
                                    <th>
                                        {$lang_title.customer_code}
                                    </th>

                                    <th>
                                        {$lang_title.customer_name}
                                    </th>
                                    <th>
                                        {$lang_title.email}
                                    </th>
                                    <th>
                                        {$lang_title.mobile}
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
            </form>
        </div>


    </div>

    <!-- END PAGE CONTENT-->
</div>
</div>
<!-- END CONTENT -->
<form action="" id="grp_frm" method="post">
    <input type="hidden" value="{$group}" id="group_hidden" name="group">
</form>
<div class="modal fade bs-modal-lg in" id="custom" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-lg">
        <form action="" method="post" id="categoryForm" class="form-horizontal">
            {CSRF::create('promotion_template')}
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" onclick="document.getElementById('errors').style.display = 'none';" id="closebutton1" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Add SMS template</h4>
                </div>

                <div class="modal-body">


                    <div class="row">


                        <div class="portlet-body form">
                            <div class="form-body">
                                <!-- Start profile details -->
                                <div class="alert alert-danger display-none" id="errors">

                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Template name<span class="required">* </span></label>
                                            <div class="col-md-6">
                                                <input type="text" id="template_name" maxlength="45" name="template_name" value="" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label col-md-4">SMS<span class="required">* </span></label>
                                            <div class="col-md-6">
                                                <textarea type="text" rows="4" id="sms" maxlength="160" name="sms" value="" class="form-control"></textarea>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End profile details -->
                            </div>
                            <div class="form-actions" style="background-color:white;">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="pull-right">
                                            <button type="button" class="btn default" onclick="document.getElementById('errors').style.display = 'none';" id="closebutton" data-dismiss="modal">Close</button>
                                            <button type="submit" onclick="return save_sms_template();" class="btn blue">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row" style="max-height: 100px;">
                        <div class="modal-header">
                            <h4 class="modal-title">List SMS template</h4>
                        </div>
                        <div class="col-md-12">
                            <div class="portlet-body form">
                                {if !empty($promo_sms)}
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>

                                                <th class="td-c">
                                                    Template name
                                                </th>
                                                <th class="td-c">
                                                    SMS
                                                </th>
                                                <th class="td-c">

                                                </th>


                                            </tr>
                                        </thead>
                                        <tbody>
                                            {foreach from=$promo_sms item=v}
                                                <tr>
                                                    <td class="td-c">
                                                        {$v.template_name}
                                                    </td>
                                                    <td class="td-c">
                                                        {$v.sms}
                                                    </td>
                                                    <td class="td-c">
                                                        <a href="#basic" onclick="document.getElementById('deleteanchor').href = '/merchant/promotions/deletesms/{$v.link}'" data-toggle="modal" class="btn btn-xs red"><i class="fa fa-times"></i> Delete </a>
                                                    </td>

                                                </tr>
                                            {/foreach}

                                        </tbody>
                                    </table>
                                {/if}
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                    </div>
                </div>


            </div>

            <!-- /.modal-content -->
        </form></div>
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