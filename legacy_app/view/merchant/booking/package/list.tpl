<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="page-bar">
        {include file="../../../common/breadcumbs.tpl" title={$title} links=$links}
        <a href="/merchant/bookings/addpackage" class="btn blue pull-right"> Add new package</a>
    </div>
    <!-- END PAGE HEADER-->

    <!-- BEGIN SEARCH CONTENT-->
    <!-- BEGIN SEARCH CONTENT-->

    <!-- BEGIN PAGE CONTENT-->
    <div class="row ">
        <div class="col-md-12">
            {if $success!=''}
                <div class="alert alert-block alert-success fade in">
                    <button type="button" class="close" data-dismiss="alert"></button>
                    <p>Success! {$success}</p>
                </div>
            {/if}
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
            <!-- BEGIN PAYMENT TRANSACTION TABLE -->


            <div class="portlet ">
                <div class="portlet-body">
                    <table class="table table-striped  table-hover" id="table-small">
                        <thead>
                            <tr>
                                <th class="td-c">
                                    ID
                                </th>
                                <th class="td-c">
                                    Package name
                                </th>
                                <th class="td-c">
                                    Package Description
                                </th>
                                <th class="td-c">
                                    Created date
                                </th>
                                <th class="td-c">
                                    Status
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
                                            {$v.package_id}
                                        </td>
                                        <td class="td-c">
                                            {$v.package_name}
                                        </td>
                                        <td class="td-c">
                                            {$v.package_desc}
                                        </td>
                                        <td class="td-c">
                                            {$v.created_date}
                                        </td>
                                        <td class="td-c">
                                            {if $v.is_active==0}
                                                <span class="label label-sm label-warning">Draft</span>
                                            {else}
                                                <span class="label label-sm label-success">Published</span>
                                            {/if}
                                        </td>
                                        <td class="td-c">
                                            <div class="btn-group dropup">
                                                <button class="btn btn-xs btn-link dropdown-toggle" type="button"
                                                    data-toggle="dropdown">
                                                    &nbsp;&nbsp;<i class="fa fa-ellipsis-v"></i>&nbsp;&nbsp;
                                                </button>
                                                <ul class="dropdown-menu" role="menu">
                                                    <li>
                                                        {if $v.is_active==0}
                                                            <a href="/merchant/bookings/publishstatus/{$v.encrypted_id}/13"><i
                                                                    class="fa fa-upload"></i> Publish </a>
                                                        {else}
                                                            <a href="/merchant/bookings/publishstatus/{$v.encrypted_id}/03"><i
                                                                    class="fa fa-download"></i> Unpublish </a>
                                                        {/if}
                                                    </li>
                                                    <li>
                                                        <a href="/merchant/bookings/updatepackage/{$v.encrypted_id}"><i
                                                                class="fa fa-edit"></i> Update </a>
                                                    </li>
                                                    <li>
                                                        <a href="#basic"
                                                            onclick="document.getElementById('deleteanchor').href = '/merchant/bookings/delete/6/{$v.encrypted_id}'"
                                                            data-toggle="modal"><i class="fa fa-times"></i> Delete </a>
                                                    </li>
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
                <h4 class="modal-title">Delete package</h4>
            </div>
            <div class="modal-body">
                Are you sure you would not like to use this package in the future?
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

<div class="modal fade bs-modal-lg" id="updatecat" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" id="closeguest" class="close" data-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="portlet ">
                <div class="portlet-body">
                    <div class="row">
                        <div class="col-md-10">
                            <br>
                            <form class="form-horizontal" action="/merchant/bookings/updatecategory" method="post"
                                id="submit_form">
                                <div class="form-body">
                                    <div class="alert alert-danger display-hide">
                                        <button class="close" data-close="alert"></button>
                                        You have some form errors. Please check below.
                                    </div>
                                    <div class="form-group">
                                        <label for="inputPassword12" class="col-md-5 control-label">Category name <span
                                                class="required">* </span></label>
                                        <div class="col-md-5">
                                            <input class="form-control form-control-inline" maxlength="45" id="category"
                                                name="category" required="" type="text" value=""
                                                placeholder="Category name" />
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <div class="col-md-5"></div>
                                        <div class="col-md-5 center">
                                            <input type="hidden" id="cat_id" name="category_id" />
                                            <button type="submit" class="btn blue">Update</button>
                                        </div>
                                    </div>
                                </div>


                            </form>
                        </div>


                    </div>


                </div>
            </div>

        </div>

    </div>

</div>
<!-- /.modal-content -->
</div>