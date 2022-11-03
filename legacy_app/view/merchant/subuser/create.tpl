
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

                            <p class="media-heading">{$v.0} - {$v.1}.</p>
                        {/foreach}
                    </div>

                </div>
            {/if}

            {if empty($roles)}
                <div class="alert alert-info">
                    <button type="button" class="close" data-dismiss="alert"></button>
                    <strong>Info!</strong>
                    <div class="media">
                        <p class="media-heading">You have no roles created to assign to your new sub merchant. Please create a role before creating sub merchants. </p>
                        <p><a href="/merchant/subuser/createrole" class="btn blue">Create role</a></p>
                    </div>

                </div>
            {else}


                <div class="portlet light bordered">
                    <div class="portlet-body form">
                        <!--<h3 class="form-section">Profile details</h3>-->
                        <form action="/merchant/subuser/saved" method="post" id="submit_form" class="form-horizontal form-row-sepe">
                            {CSRF::create('subuser_save')}
                            <div class="form-body">
                                <!-- Start profile details -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="alert alert-danger display-none">
                                            <button class="close" data-dismiss="alert"></button>
                                            You have some form errors. Please check below.
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-4">Email <span class="required">*
                                                </span></label>
                                            <div class="col-md-4">
                                                <input type="email" required  name="email" class="form-control" value="{$post.email}">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Password <span class="required">*
                                                </span></label>
                                            <div class="col-md-4">
                                                <input type="password" AUTOCOMPLETE='OFF' required id="submit_form_password" name="password" class="form-control" >
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Confirm Password <span class="required">*
                                                </span></label>
                                            <div class="col-md-4">
                                                <input type="password" {$validate.password} required AUTOCOMPLETE='OFF' name="rpassword" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">First name <span class="required">*
                                                </span></label>
                                            <div class="col-md-4">
                                                <input type="text" required {$validate.name} name="first_name" class="form-control" value="{$post.first_name}">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Last name <span class="required">*
                                                </span>
                                            </label>
                                            <div class="col-md-4">
                                                <input type="text" required {$validate.name} name="last_name" class="form-control" value="{$post.last_name}">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Mobile <span class="required">*
                                                </span></label>
                                            <div class="col-md-1">
                                                <input type="text" {$validate.mobilecode}  name="mob_country_code" class="form-control" value="+91">
                                            </div>
                                            <div class="col-md-3">
                                                <input type="text" {$validate.mobile} required="" name="mobile" class="form-control" value="{$post.mobile}">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Role<span class="required">*
                                                </span></label>
                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <select required id="role" class="form-control" name="role" >
                                                        {foreach from=$roles key=k item=v}
                                                            <option value="{$v.role_id}">{$v.name}</option>
                                                        {/foreach}
                                                    </select>
                                                    <span class="input-group-btn">
                                                        <a class="btn green" data-toggle="modal"  href="#roles">
                                                            <i class="fa fa-plus"></i></a>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Select Customer Group</label>
                                            <div class="col-md-4">
                                                <select id="select2_sample2" data-placeholder="Select Customer Group" name="group[]" class="form-control select2me">
                                                <option value=""></option>    
                                                {foreach from=$customer_group item=v}
                                                        <option value="{$v.group_id}">{$v.group_name}</option>
                                                    {/foreach}
                                                </select>
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
                                            <button type="reset" class="btn default">Reset</button>
                                            <input type="submit" value="Save" class="btn blue"/>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            {/if}
        </div>	
        <!-- END PAGE CONTENT-->
    </div>
</div>
<!-- END CONTENT -->
</div>

<div class="modal fade" id="roles" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="" id="roleForm" method="post" onsubmit="return false;" class="form-horizontal form-row-sepe">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Add new role</h4>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-danger display-none">
                                <button class="close" data-dismiss="alert"></button>
                                You have some form errors. Please check below.
                            </div>
                            <div class="alert alert-danger" style="display: none;" id="errorshow">
                                <button class="close" onclick="document.getElementById('errorshow').style.display = 'none';"></button>
                                <p id="error_display">Please correct the below errors to complete registration.</p>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Role name <span class="required">*
                                    </span></label>
                                <div class="col-md-6">
                                    <input type="text" required name="role_name" class="form-control" value="{$post.role_name}">
                                </div>
                            </div>

                            <div class="form-group">

                                <div class="col-md-12">

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
                <div class="modal-footer">
                    <input type="submit" onclick="saveRole();"  id="btnSubmit"  class="btn blue" value="Save"/>
                    <button type="button" class="btn default" id="closebutton" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>