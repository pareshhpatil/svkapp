
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="page-bar">
        {include file="../../common/breadcumbs.tpl" title={$title} links=$links}
        <a href="/merchant/supplier/create" class="btn blue pull-right mb-1"> Create supplier </a>
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
                                    Supplier name
                                </th>
                                <th class="td-c">
                                    Contact person name
                                </th>
                                <th class="td-c">
                                    Industry type
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
                                        {$v.supplier_company_name}
                                    </td>
                                    <td class="td-c">
                                        {$v.contact_person_name}
                                    </td>
                                    <td class="td-c">
                                        {$v.config_value}
                                    </td>
                                    <td class="td-c">
                                        <!-- <a href="/merchant/supplier/update/{$v.encrypted_id}" class="btn btn-xs green"><i class="fa fa-edit"></i> Edit </a>
                                        <a href="#basic" onclick="document.getElementById('deleteanchor').href = '/merchant/supplier/delete/{$v.encrypted_id}'" data-toggle="modal" class="btn btn-xs red"><i class="fa fa-times"></i> Delete </a> -->
                                        <div class="btn-group dropup">
                                            <button class="btn btn-xs btn-link dropdown-toggle" type="button" data-toggle="dropdown">
                                                &nbsp;&nbsp;<i class="fa fa-ellipsis-v"></i>&nbsp;&nbsp;
                                            </button>
                                            <ul class="dropdown-menu" role="menu">
                                                <li><a href="/merchant/supplier/update/{$v.encrypted_id}"><i class="fa fa-edit"></i> Edit</a>
                                                </li>
                                                <li>
                                                    <a href="#basic" onclick="document.getElementById('deleteanchor').href = '/merchant/supplier/delete/{$v.encrypted_id}'"  data-toggle="modal" ><i class="fa fa-times"></i> Delete</a>  
                                                </li>
                                            </ul>
                                        </div>
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
                <h4 class="modal-title">Delete Supplier</h4>
            </div>
            <div class="modal-body">
                Are you sure you would not like to use this supplier in the future?
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
