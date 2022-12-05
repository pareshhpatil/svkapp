<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <h3 class="page-title">Patron profile</h3>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        {if isset($success)}
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert"></button>
                <strong>Success!</strong>{$success}
            </div>
        {/if}
        {if isset($errors)}
            <div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                <strong>Error!</strong>
                <div class="media">
                    {foreach from=$errors key=k item=v}
                        <p class="media-heading">{$k} - {$v.1}</p>
                    {/foreach}
                </div>

            </div>
        {/if}
        <div class="col-md-12">
            <div class="portlet light bordered">
                <div class="portlet-body form">

                    <form action="/patron/profile/update" method="post" id="submit_form"
                        class="form-horizontal form-row-sepe">
                        {CSRF::create('profile_update')}
                        <div class="form-body">

                            <!-- Start profile details -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Email <span class="required">
                                                * </span></label>
                                        <div class="col-md-8">
                                            <input type="email" readonly name="email" class="form-control"
                                                value="{$details.email_id}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">First name <span class="required">
                                                * </span></label>
                                        <div class="col-md-8">
                                            <input type="text" required name="f_name" {$validate.name}
                                                class="form-control" value="{$details.first_name}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Last name <span class="required">
                                                * </span></label>
                                        <div class="col-md-8">
                                            <input type="text" required name="l_name" {$validate.name}
                                                class="form-control" value="{$details.last_name}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Mobile number <span class="required">
                                                * </span> </label>

                                        <div class="col-md-8">
                                            <input type="text" required name="mobile" {$validate.mobile}
                                                class="form-control" value="{$details.mobile_no}">
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        <!-- End profile details -->

                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-offset-3 col-md-9">
                                    <button type="submit" class="btn blue">Save</button>
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