
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="page-bar">
        {include file="../../common/breadcumbs.tpl" title={$title} links=$links}
        <a href="/merchant/profile/gstcreate" class="btn blue pull-right"> Create billing profile </a>
    </div>
    <!-- END PAGE HEADER-->

    <!-- BEGIN SEARCH CONTENT-->
    <!-- BEGIN SEARCH CONTENT-->

    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12">
            {if $success!=''}
                <div class="alert alert-block alert-success fade in" style="margin-left: 0px !important; margin-right: 0px !important;">
                    <button type="button" class="close" data-dismiss="alert"></button>
                    <p>{$success}</p>
                </div>
            {/if}
            <!-- BEGIN PAYMENT TRANSACTION TABLE -->
        </div>
        <div class="col-md-12">
            <div class="portlet ">
                <div class="portlet-body">
                    <table class="table table-striped  table-hover" id="table-no-export">
                        <thead>
                            <tr>
                                <th class="td-c">
                                    ID
                                </th>
                                <th class="td-c">
                                    Date
                                </th>
                                <th class="td-c">
                                    Profile name
                                </th>
                                <th class="td-c">
                                    Company name
                                </th>
                                <th class="td-c">
                                    GST Number
                                </th>
                                <th class="td-c">
                                    State
                                </th>
                                <th class="td-c">
                                    GST filing
                                </th>
                                <th class="td-c">

                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            {foreach from=$list item=v}
                                <tr>
                                    <td class="td-c">
                                        {$v.id}
                                    </td>
                                    <td class="td-c">
                                        {$v.created_at}
                                    </td>
                                    <td class="td-c">
                                        {$v.profile_name}
                                    </td>
                                    <td class="td-c">
                                        {$v.company_name}
                                    </td>
                                    <td class="td-c">
                                        {$v.gst_number}
                                    </td>
                                    <td class="td-c">
                                        {$v.state}
                                    </td>
                                    <td class="td-c">
                                        {if $v.gst_filing==1}
                                            Yes
                                        {else}
                                            No
                                        {/if}
                                    </td>
                                    <td class="td-c">
                                        <a href="/merchant/profile/gstupdate/{$v.encrypted_id}" class="btn btn-xs green"><i class="fa fa-edit"></i> Edit </a>
                                        {if $v.is_default==0}
                                        <a href="#basic" onclick="document.getElementById('deleteanchor').href = '/merchant/profile/delete/{$v.encrypted_id}/gst'" data-toggle="modal" class="btn btn-xs red"><i class="fa fa-times"></i> Delete </a>
                                        {/if}
                                    </td>
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


<div class="modal fade" id="basic" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Delete Billing profile</h4>
            </div>
            <div class="modal-body">
                Are you sure you would not like to use this GST in the future?
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
