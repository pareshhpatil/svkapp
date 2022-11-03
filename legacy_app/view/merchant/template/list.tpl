<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="page-bar">
        {include file="../../common/breadcumbs.tpl" title={$title} links=$links}
        <a href="/merchant/template/newtemplate" class="btn blue pull-right mb-1"> Create new format </a>
    </div>
    <!-- END PAGE HEADER-->
    <div class="row">
        {if isset($success)}
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert"></button>
                <strong>Success!</strong>{$success}
            </div>
        {/if}
        <div class="col-md-12">
            <!-- BEGIN LIST TEMPLATES TABLE -->


            <div class="portlet ">
                <div class="portlet-body">
                    <table class="table table-striped table-hover" id="table-small">
                        <thead>
                            <tr>
                                <th class="td-c">
                                    Created date
                                </th>
                                <th class="td-c">
                                    Template name
                                </th>
                                <th class="td-c">
                                    Template type
                                </th>
                                <th></th>

                            </tr>
                        </thead>
                        <tbody>
                            {foreach from=$templatelist item=v}
                                <tr>
                                    <td class="td-c">
                                        {{$v.created_date}|date_format:"%Y-%m-%d"}
                                    </td>
                                    <td class="td-c">
                                        {if $v.template_type=='travel' || $v.template_type=='school'}
                                            <a href="/merchant/invoiceformat/update/{$v.encrypted_id}"> {$v.template_name} </a>
                                        {else}
                                            <a href="/merchant/template/update/{$v.encrypted_id}"> {$v.template_name} </a>
                                        {/if}
                                    </td>
                                    <td class="td-c">
                                        {$v.type}
                                    </td>


                                    <td class="">
                                        <div class="visible-xs btn-group-vertical">

                                            <span class="input-group-btn">
                                            </span>
                                            <span class="input-group-btn">
                                                <a class="btn btn-xs green" title="Update template"
                                                    href="/merchant/template/update/{$v.encrypted_id}"><i
                                                        class="fa fa-edit"></i></a>
                                            </span>
                                            <span class="input-group-btn">
                                                <a href="#basic" class="btn btn-xs red" title="Delete template"
                                                    onclick="document.getElementById('deleteanchor').href = '/merchant/template/delete/{$v.encrypted_id}'"
                                                    data-toggle="modal"><i class="fa fa-times"></i></a>
                                            </span>
                                        </div>

                                        <div class="hidden-xs btn-group dropup" style="position: absolute;">
                                            <button class="btn btn-xs btn-link dropdown-toggle" type="button"
                                                data-toggle="dropdown">
                                                &nbsp;&nbsp;<i class="fa fa-ellipsis-v"></i>&nbsp;&nbsp;
                                            </button>
                                            <ul class="dropdown-menu pull-right" role="menu">

                                                <li>
                                                {if $v.template_type=='travel' || $v.template_type=='school'}
                                                    <a href="/merchant/invoiceformat/update/{$v.encrypted_id}"><i
                                                    class="fa fa-edit"></i> Edit</a>
                                                {else}
                                                    <a href="/merchant/template/update/{$v.encrypted_id}"><i
                                                    class="fa fa-edit"></i> Edit</a>
                                                {/if}
                                                   
                                                </li>
                                                <li>
                                                    <a href="#basic"
                                                        onclick="document.getElementById('deleteanchor').href = '/merchant/template/delete/{$v.encrypted_id}'"
                                                        data-toggle="modal"><i class="fa fa-times"></i> Delete</a>
                                                </li>
                                                {if $v.template_type!='travel' && $v.template_type!='scan'}
                                                    <li class="divider"></li>
                                                    <li>
                                                        <a href="/merchant/template/saveInvoceJson/v3/{$v.encrypted_id}">
                                                            Upload Invoice v3 JSON</a>
                                                    </li>

                                                    <li>
                                                        <a href="/merchant/template/updateInvoceJson/v3/{$v.encrypted_id}">
                                                            Update Invoice v3 JSON</a>
                                                    </li>
                                                {/if}
                                            </ul>
                                        </div>

                                    </td>
                                </tr>
                            {/foreach}


                        </tbody>
                    </table>
                    <div class="modal fade" id="basic" tabindex="-1" role="basic" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal"
                                        aria-hidden="true"></button>
                                    <h4 class="modal-title">Delete template</h4>
                                </div>
                                <div class="modal-body">
                                    Are you sure you would not like to use this template in the future?
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
                </div>
            </div>


            <!-- END LIST TEMPLATES TABLE -->
        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>
</div>
<!-- END CONTENT -->