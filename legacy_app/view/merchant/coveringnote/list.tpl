
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="page-bar">
        {include file="../../common/breadcumbs.tpl" title={$title} links=$links}
        <a href="/merchant/coveringnote/create" class="btn blue pull-right mb-1"> Create covering note</a>
    </div>

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
                                    Created Date
                                </th>
                                <th class="td-c">
                                    Created Date
                                </th>
                                <th class="td-c">
                                    Template name
                                </th>
                                <th class="td-c">
                                    Subject
                                </th>
                                
                                <th class="td-c">
                                    
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                        <form action="" method="">
                            {foreach from=$list item=v}
                                <tr>
                                    <td class="td-c">
                                        {$v.created_date}
                                    </td>
                                    <td class="td-c">
                                        {$v.created_at}
                                    </td>
                                    <td class="td-c">
                                        {$v.template_name}
                                    </td>
                                    <td class="td-c">
                                        {$v.subject}
                                    </td>
                                    <td class="td-c">

                                        <a href="/merchant/coveringnote/update/{$v.encrypted_id}" class="btn btn-xs green"><i class="fa fa-edit"></i> Edit </a>
                                        <a href="#basic" onclick="document.getElementById('deleteanchor').href = '/merchant/coveringnote/delete/{$v.encrypted_id}'" data-toggle="modal" class="btn btn-xs red"><i class="fa fa-times"></i> Delete </a>
                                    </td>
                                </tr>

                            {/foreach}
                        </form>
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
                <h4 class="modal-title">Delete Coveringnote</h4>
            </div>
            <div class="modal-body">
                Are you sure you would not like to use this Coveringnote in the future?
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
