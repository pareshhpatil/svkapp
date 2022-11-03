
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="page-bar">
        {include file="../../common/breadcumbs.tpl" title={$title} links=$links}
    </div>
    <!-- END PAGE HEADER-->
    <div class="row">
        <!-- END SEARCH CONTENT-->

        <div class="loading" id="loader" style="display: none;">Loading&#8230;</div>
        <div class="col-md-12">

            <div class="tabbable-line">
                <ul class="nav nav-tabs">
                    <li >
                        <a  href="/merchant/customer/register">Create Logins</a>
                    </li>
                    <li class="active">
                        <a >Manage Logins </a>
                    </li>
                </ul>

            </div>
            <br>

            <div class="portlet">
                <div class="portlet-body ">
                    <form class="form-inline" method="post" role="form">
                        <div class="form-group">
                            <label class="help-block">Show column (Max 5)</label>
                            <select multiple  id="column_name" class="full-width-div" data-placeholder="Column name" name="column_name[]">
                                {foreach from=$column_list item=v}
                                    {if $v.column_name!=''}
                                        {if in_array($v.column_name, $column_select)} 
                                            <option selected value="{$v.column_name}" >{$v.column_name}</option>
                                        {else}
                                            <option value="{$v.column_name}">{$v.column_name}</option>
                                        {/if}
                                    {/if}
                                {/foreach}
                            </select>    
                        </div>

                        <div class="form-group">
                            <label class="help-block">Filter by</label>
                            <select class="form-control full-width-div" data-placeholder="Filter by" name="filter_by">
                                <option value="">Filter by</option>
                                <option value="customer_code" {if $filter_by=='customer_code'}selected={/if}>{$customer_default_column.customer_code|default:'Customer code'}</option>
                                <option value="__Address" {if $filter_by=='__Address'}selected={/if}>Address</option>
                                <option value="__City" {if $filter_by=='__City'}selected={/if}>City</option>
                                <option value="__Zipcode" {if $filter_by=='__Zipcode'}selected={/if}>Zipcode</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="help-block">Filter Condition</label>
                            <select class="form-control full-width-div" data-placeholder="Column name" name="filter_condition">
                                <option value="">Filter Condition</option>
                                <option value="equal" {if $filter_condition=='equal'}selected={/if}>Equal to</option>
                                <option value="start_with" {if $filter_condition=='start_with'}selected={/if}>Starts with</option>
                                <option value="contain" {if $filter_condition=='contain'}selected={/if}>Contain</option>

                            </select>
                        </div>
                        <div class="form-group">
                            <label class="help-block">Filter value</label>
                            <input type="text" name="filter_value" value="{$filter_value}" maxlength="45" class="form-control  full-width-div">
                        </div>
                        <div class="form-group">
                            <label class="help-block">Choose group</label>
                            <select name="group" id="fil_grp" class="form-control full-width-div">    
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


                        <div class="form-group">
                            <label class="help-block">&nbsp;</label>
                            <button type="submit" class="btn blue">Search</button>

                        </div>
                    </form>
                </div>
            </div>
        </div>

        <form  action="" method="post" role="form">
            <div class="col-md-9">
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
                {if isset($success)}
                    <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert"></button>
                        <strong>Success!</strong>  {$success}
                    </div> 
                {/if}
                <div class="alert alert-danger display-none" id="grp_errors">
                </div>
                <div class="alert alert-success display-none" id="grp_success">
                </div>


                <!-- BEGIN PAYMENT TRANSACTION TABLE -->
                <div class="portlet ">
                    <div class="portlet-body">
                        <table id="example" class="display" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                <tr>
                                    <th class="no-sort">
                                        <label><input id="checkAll" type="checkbox"><b>All</b></label>
                                    </th>
                                    <th>
                                        {$customer_default_column.customer_code|default:$lang_title.customer_code}
                                    </th>

                                    <th>
                                        {$customer_default_column.customer_name|default:$lang_title.customer_name}
                                    </th>
                                    
                                    <th>
                                        {$customer_default_column.mobile|default:$lang_title.mobile}
                                    </th>
                                        
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

            </div>
            <div class="col-md-3">

                <div class="portlet">

                    <!-- BEGIN PORTLET-->

                    <div class="portlet-body ">
                        <div class="form-group">
                            <label class="help-block"><b>Send login details</b></label>
                        <div id="sms_div" class="form-group">
                            <label class="help-block">SMS</label>
                            <textarea rows="5" maxlength="160" name="sms" class="form-control">Greetings from {$company_name}! Create OR change your cable packages using below login {$loginurl} User ID %MOBILE% Password %PASSWORD%</textarea>
                        </div>
                        <div class="form-group">
                            <button  type="submit" name="register" class="btn green">Send Logins</button>
                        </div>
                    </div>
                </div>

            </div>  
        </form>
    </div>

    <!-- END PAGE CONTENT-->
</div>
</div>
<!-- END CONTENT -->




<script>
    function showSMSDIV(vv)
    {
        if (vv == 0)
        {
            document.getElementById('sms_div').style.display = 'none';
        } else
        {
            document.getElementById('sms_div').style.display = 'block';
        }
    }

    function changeLogin(user_id, pass)
    {
        login_user_id = user_id;
        document.getElementById('password_text').style.display = 'block';
        document.getElementById('cnfbtn').style.display = 'inline-block';
        document.getElementById('pass_success').style.display = 'none';

    }
</script>

<div class="modal fade" id="basic" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Update password</h4>
            </div>
            <div class="modal-body">
                <div id="pass_success" class="alert alert-success">
                    <strong>Success!</strong>  Password has been updated.
                </div>
                <div class="form-group"  id="password_text">
                    <label for="inputPassword12" class="col-md-5 control-label">Password</label>
                    <div class="col-md-5">
                        <input class="form-control form-control-inline input-sm" id="password_" minlength="4" maxlength="20"  type="text"  placeholder="Password"/>
                    </div>
                </div>
                <br>
            </div>
            <div class="modal-footer">
                <button type="button" id="close" class="btn default" data-dismiss="modal">Close</button>
                <a id="cnfbtn" onclick="updatePassword();" class="btn blue">Confirm</a>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>