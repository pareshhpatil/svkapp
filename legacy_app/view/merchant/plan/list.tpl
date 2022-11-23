
<div class="page-content">
    <div class="page-bar">
        {include file="../../common/breadcumbs.tpl" title={$title} links=$links}
    </div>
    <div class="portlet-body">
        <ul class="nav nav-tabs">
            <li class="active">
                <a href="#tab_1_1" data-toggle="tab" aria-expanded="true">
                    Plan List </a>
            </li>
            <li class="">
                <a href="#tab_1_2" data-toggle="tab" aria-expanded="false">
                    Plan settings </a>
            </li>

        </ul>
        <div class="tab-content">
            <div class="tab-pane fade active in" id="tab_1_1">
                <!-- BEGIN PAGE HEADER-->
                <h3 class="page-title mb-2">&nbsp;

                    <a href="javascript:;"  class="btn green pull-right bs_growl_show" data-clipboard-action="copy" data-clipboard-target="abc1" style="margin-left: 10px;"><i class="fa fa-clipboard"></i> &nbsp; Copy plan link</a>
                    <a href="/merchant/plan/create" class="btn blue pull-right"><i class="fa fa-plus"></i> &nbsp; Add new plan </a></h3>
                <div style="font-size: 0px;"><abc1>{$plan_link}</abc1></div>
                <!-- END PAGE HEADER-->

                <!-- BEGIN SEARCH CONTENT-->
                <!-- BEGIN SEARCH CONTENT-->

                <!-- BEGIN PAGE CONTENT-->
                <div class="row">
                    <div class="col-md-12">
                        {if $success!=''}
                            <div class="alert alert-block alert-success fade in">
                                <button type="button" class="close" data-dismiss="alert"></button>
                                <p>{$success}</p>
                            </div>
                        {/if}
                        <!-- BEGIN PAYMENT TRANSACTION TABLE -->

                        <div class="portlet ">
                            <div class="portlet-body">
                                <table class="table table-striped  table-hover" id="table-no-export">
                                    <thead>
                                        <tr>
                                            <th class="td-c">
                                                # ID
                                            </th>
                                            {if $is_source==1}
                                                <th class="td-c">
                                                    Source name
                                                </th>
                                            {/if}
                                            <th class="td-c">
                                                Category name
                                            </th>
                                            <th class="td-c">
                                                Plan name
                                            </th>
                                            <th class="td-c">
                                                Speed
                                            </th>
                                            <th class="td-c">
                                                Data
                                            </th>
                                            <th class="td-c">
                                                Duration
                                            </th>
                                            <th class="td-c">
                                                Price
                                            </th>
                                            <th class="td-c">

                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <form action="" method="">
                                        {$int=1}
                                        {foreach from=$list item=v}
                                            <tr>
                                                <td class="td-c">
                                                    {$v.plan_id}
                                                </td>
                                                {if $is_source==1}
                                                    <td class="td-c">
                                                        <div style="font-size: 0px;"><abc{$int}>{$v.url}</abc{$int}></div>
                                                        <a href="javascript:;" title="Copy plan URL" class="btn btn-xs bs_growl_show" data-clipboard-action="copy"  data-clipboard-target="abc{$int}">
                                                            {$v.source}</a>
                                                    </td>
                                                {/if}
                                                <td class="td-c">
                                                    {$v.category}
                                                </td>
                                                <td class="td-c">
                                                    {$v.plan_name}
                                                </td>
                                                <td class="td-c">
                                                    {$v.speed}
                                                </td>
                                                <td class="td-c">
                                                    {$v.data}
                                                </td>
                                                <td class="td-c">
                                                    {$v.duration} Month
                                                </td>
                                                <td class="td-c">
                                                    {$v.price}
                                                </td>

                                                <td class="td-c">
                                                    <!-- <a href="/merchant/plan/update/{$v.encrypted_id}" target="_BLANK" class="btn btn-xs green"><i class="fa fa-edit"></i> Edit </a>
                                                    <a href="#basic" onclick="document.getElementById('deleteanchor').href = '/merchant/plan/delete/{$v.encrypted_id}'" data-toggle="modal" class="btn btn-xs red"><i class="fa fa-times"></i> Delete </a> -->
                                                    <div class="btn-group dropup">
                                                        <button class="btn btn-xs btn-link dropdown-toggle" type="button" data-toggle="dropdown">
                                                            &nbsp;&nbsp;<i class="fa fa-ellipsis-v"></i>&nbsp;&nbsp;
                                                        </button>
                                                        <ul class="dropdown-menu" role="menu">
                                                            <li><a target="_BLANK" href="/merchant/plan/update/{$v.encrypted_id}"><i class="fa fa-edit"></i> Edit</a>
                                                            </li>
                                                            <li>
                                                                <a href="#basic" onclick="document.getElementById('deleteanchor').href = '/merchant/plan/delete/{$v.encrypted_id}'"  data-toggle="modal" ><i class="fa fa-times"></i> Delete</a>  
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                            {$int=$int+1}
                                        {/foreach}
                                    </form>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- END PAYMENT TRANSACTION TABLE -->
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="tab_1_2">
                <form action="/merchant/plan/savesetting" method="post" id="submit_form"  class="form-horizontal form-row-sepe">
                    <div class="col-md-12">
                        <div class="alert alert-danger display-none">
                            <button class="close" data-dismiss="alert"></button>
                            You have some form errors. Please check below.
                        </div>
                        <div class="portlet light">
                            <div class="portlet-body form">

                                <h4 class="form-section">Plan invoicing</h4>
                                <!-- Start Bulk upload details -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-5">Activate invoicing</label>
                                            <div class="col-md-7">
                                                <input type="checkbox" id="issupplier" onchange="planInvoiceEnable(this.checked)" name="plan_invoice_create" value="1" class="make-switch" data-on-text="&nbsp;Yes &nbsp;&nbsp;" 
                                                       {if $setting.plan_invoice_create==1}
                                                           checked
                                                       {/if} data-off-text="&nbsp;No&nbsp;">
                                                <br>
                                                <br>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div id="plan_invoice_create" {if $setting.plan_invoice_create==0} style="display: none;"{/if}>
                                    <div class="row">
                                        <div class="col-md-6">

                                            <div class="form-group">
                                                <label class="control-label col-md-5">Invoice Template</label>
                                                <div class="col-md-6">
                                                    <select name="selecttemplate" required class="form-control select2me" data-placeholder="Select...">
                                                        <option value=""></option>
                                                        {foreach from=$templatelist item=v}
                                                            {if $v.template_type!='travel_ticket_booking' && $v.template_type!='simple'}
                                                                {if $setting.plan_invoice_template==$v.template_id}
                                                                    <option selected value="{$v.template_id}" selected>{$v.template_name}</option>
                                                                {else}
                                                                    <option value="{$v.template_id}">{$v.template_name}</option>
                                                                {/if}
                                                            {/if}

                                                        {/foreach}
                                                    </select>
                                                    <br>
                                                    <br>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">

                                            <div class="form-group">
                                                <label class="control-label col-md-5">Send invoice to customer
                                                    &nbsp;
                                                    <i class="popovers fa fa-info-circle support blue" data-container="body" data-trigger="hover" data-content="Set this to Yes, if you want your customer to receive the generated invoice as a PDF attachment along with the payment receipt email." data-original-title="" title=""></i></label>

                                                <div class="col-md-7">
                                                    <input type="checkbox" id="issupplier" name="plan_invoice_send" value="1" class="make-switch" data-on-text="&nbsp;Yes &nbsp;&nbsp;" 
                                                           {if $setting.plan_invoice_send==1}
                                                               checked
                                                           {/if} data-off-text="&nbsp;No&nbsp;">
                                                    <br>
                                                    <br>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="pull-right">
                                                <a href="/merchant/plan/viewlist"  class="btn btn-default">Cancel</a>
                                                <input type="submit" class="btn blue" value="Save"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>						
                        </div>						
                        <!-- End Bulk upload details -->
                    </div>
                </form>	
            </div>

        </div>
        <div class="clearfix margin-bottom-20">
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
                <h4 class="modal-title">Delete Plan</h4>
            </div>
            <div class="modal-body">
                Are you sure you would like to delete this plan from your plan list?
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
