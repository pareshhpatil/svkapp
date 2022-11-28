
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
     <div class="page-bar">
        {include file="../../common/breadcumbs.tpl" title={$title} links=$links}
    </div>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12">
            {if isset($haserrors)}
                <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert"></button>
                    <strong>Error!</strong>
                    <div class="media">
                        {foreach from=$haserrors item=v}
                            <p class="media-heading">{$v.0} - {$v.1}</p>
                        {/foreach}
                    </div>

                </div>
            {/if}

            <div class="portlet light bordered">
                <div class="portlet-body form">
                    <!--<h3 class="form-section">Profile details</h3>-->
                    <form action="/merchant/subuser/saverole" method="post" id="submit_form" class="form-horizontal form-row-sepe">
                        {CSRF::create('role_save')}
                        <div class="form-body">
                            <!-- Start profile details -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="alert alert-danger display-none">
                                        <button class="close" data-dismiss="alert"></button>
                                        You have some form errors. Please check below.
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Role name <span class="required">*
                                            </span></label>
                                        <div class="col-md-4">
                                            <input type="text" required name="role_name" class="form-control" value="{$post.role_name}">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-4">Role<span class="required">
                                            </span></label>
                                        <div class="col-md-4">

                                            <table class="table table-bordered table-hover" id="">
                                                <thead>
                                                    <tr>
                                                        <th rowspan="2" class="td-c">
                                                            ID
                                                        </th>
                                                        <th rowspan="2" class="td-c">
                                                            Functions
                                                        </th>
                                                        <th colspan="3" class="td-c" style="border-bottom: 1px solid lightgrey;">
                                                            ?
                                                        </th>
                                                    </tr>
                                                    <tr>

                                                        <th class="td-c">
                                                            View
                                                        </th>
                                                        <th class="td-c">
                                                            Create/<br>
                                                            Edit
                                                        </th>
                                                        <th class="td-c">
                                                            Delete
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    {foreach from=$list item=v}
                                                        <tr>
                                                            <td class="td-c">
                                                                {$v.controller_id}
                                                            </td>
                                                            <td class="td-c">
                                                                {$v.display_name}
                                                                <span class="popovers" data-container="body" data-trigger="hover" data-content="{$v.description}"><i class="fa fa-info-circle" style="color: #275770;"></i></span>
                                                            </td>

                                                            <td class="td-c">
                                                                {if $v.is_default==1} 
                                                                    <input  checked="" disabled value="{$v.controller_id}" type="checkbox">
                                                                    <input  checked name="view[]" value="{$v.controller_id}"  type="hidden">
                                                                {else}
                                                                    <input   name="view[]" value="{$v.controller_id}" type="checkbox">
                                                                {/if}

                                                            </td>
                                                            <td class="td-c">
                                                                {if $v.is_default==1} 
                                                                    <input  checked="" disabled value="{$v.controller_id}" type="checkbox">
                                                                    <input  checked name="edit[]" value="{$v.controller_id}"  type="hidden">
                                                                {else}
                                                                    <input   name="edit[]" value="{$v.controller_id}" type="checkbox">
                                                                {/if}

                                                            </td>
                                                            <td class="td-c">
                                                                {if $v.is_default==1} 
                                                                    <input  checked="" disabled value="{$v.controller_id}" type="checkbox">
                                                                    <input  checked name="delete[]" value="{$v.controller_id}"  type="hidden">
                                                                {else}
                                                                    <input   name="delete[]" value="{$v.controller_id}" type="checkbox">
                                                                {/if}

                                                            </td>
                                                        </tr>

                                                    {/foreach}
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                </div>


                            </div>
                        </div>					
                        <!-- End profile details -->

                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="pull-right">
                                    <a href="/merchant/subuser/roles" class="btn btn-default">Cancel</a>
                                    <input type="submit" value="Save" class="btn blue"/>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>	
        <!-- END PAGE CONTENT-->
    </div>
</div>
<!-- END CONTENT -->
</div>