
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="page-bar">
        {include file="../../common/breadcumbs.tpl" title={$title} links=$links}
    </div>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        {if isset($error)}
            <div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                <strong>Error!</strong>{$error}
            </div>
        {/if}
        {if isset($success)}
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert"></button>
                <strong>Success!</strong>{$success}
            </div>
        {/if}
        <form action="" method="post" id="submit_form"  class="form-horizontal form-row-sepe">
            {CSRF::create('profile_accesskey')}
            <div class="col-md-12">
                <div class="alert alert-danger display-none">
                    <button class="close" data-dismiss="alert"></button>
                    You have some form errors. Please check below.
                </div>
                {if $valid!=1}
                    <div class="portlet light bordered">
                        <div class="portlet-body form">
                            <!-- End Bank details -->
                            <h4 class="form-section">Please confirm your password to view appId/secretKey pair</h4>
                            <!-- Start Bulk upload details -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Password</label>
                                        <div class="col-md-6">
                                            <input type="password" name="password" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="">
                                        <div class="row">
                                            <div class="col-md-offset-6">
                                                <input type="submit" name="submit_password" class="btn blue" value="Confirm"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>						
                    </div>	
                {/if}
                <!-- End Bulk upload details -->
                {if $valid==1}
                    <div class="portlet light bordered">
                        <div class="portlet-body form">
                            <!-- End Bank details -->
                            <!-- Start Bulk upload details -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Your Access Key</label>
                                        <div class="col-md-8">
                                            <input type="text" readonly value="{$det.access_key_id}" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Your Secret Key</label>
                                        <div class="col-md-8">
                                            <input type="text" readonly value="{$det.secret_access_key}" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="">
                                        <div class="row">
                                            <div class="col-md-offset-6">
                                                {if $det.access_key_id!=''}
                                                    <a href="#basic"  data-toggle="modal" class="btn green">Reset</a>
                                                    <a href="#delete"  data-toggle="modal" class="btn red">Deactivate</a>
                                                {else}
                                                    <input type="submit" name="generate_key" class="btn green" value="Generate"/>
                                                {/if}
                                                <input type="hidden" name="key_id" value="{$det.key_id}">
                                            </div>
                                            <div class="col-md-offset-3">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>						
                    </div>	
                {/if}
            </div>
        </form>	
    </div>
</div>	
<!-- END PAGE CONTENT-->
</div>
</div>
<!-- END CONTENT -->
</div>
<div class="modal fade" id="basic" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Reset Key Details</h4>
            </div>
            <div class="modal-body">

                <form action="" method="post" id="submit_form" class="form-horizontal form-row-sepe">
                    {CSRF::create('profile_accesskey')}
                    <div class="form-body">
                        Any existing applications using this key would need to be changed to use the new key. Are you sure you would like to reset your access keys?
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="key_id" value="{$det.key_id}">
                        <input type="submit" name="reset_key" value="Yes" class="btn blue">
                        <button type="button" class="btn default" data-dismiss="modal">Cancel</button>

                    </div>
                </form>
            </div>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<div class="modal fade" id="delete" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Delete Key Details</h4>
            </div>
            <div class="modal-body">
                Are you sure you would like to deactivate keys?
            </div>
            <div class="modal-footer">
                <form action="" method="post" id="submit_form" class="form-horizontal form-row-sepe">
                    {CSRF::create('profile_accesskey')}
                    <input type="hidden" name="key_id" value="{$det.key_id}">
                    <input type="submit" name="delete_key" value="Yes" class="btn blue">
                    <button type="button" class="btn default" data-dismiss="modal">Cancel</button>
                </form>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
