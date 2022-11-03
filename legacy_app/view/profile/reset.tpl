
    <div class="page-content">
        <!-- BEGIN PAGE HEADER-->
        <div class="page-bar">
            {include file="../common/breadcumbs.tpl" title={$title} links=$links}
        </div>
        <!-- END PAGE HEADER-->
        <!-- BEGIN PAGE CONTENT-->
        <div class="row">
            <div class="col-md-12">
                {if isset($errors)}
                    <div class="alert alert-danger alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                        <strong>Error!</strong>
                        <div class="media">
                            {foreach from=$errors key=k item=v}
                                <p class="media-heading">{$v.0}- {$v.1}</p>
                            {/foreach}
                        </div>

                    </div>
                {/if}
                <div class="portlet light bordered">
                    <div class="portlet-body form">
                        <!--<h3 class="form-section">Profile details</h3>-->
                        <form action="/profile/resetpassword" method="post" id="submit_form"  class="form-horizontal form-row-sepe">
                            {CSRF::create('password_reset')}
                            <div class="form-body">


                                <!-- Start profile details -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Current password <span class="required">
                                                </span></label>
                                            <div class="col-md-4">
                                                <input AUTOCOMPLETE='OFF'  name="oldpassword" required=""  type="password" {$validate.oldpassword} class="form-control" value="">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">New password <span class="required">
                                                </span></label>
                                            <div class="col-md-4">
                                                <input type="password" id="submit_form_password" AUTOCOMPLETE='OFF' required class="form-control"  name="password"  {$validate.password} id="submit_form_password" aria-required="true" aria-invalid="false" aria-describedby="submit_form_password-error">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Confirm password <span class="required">
                                                </span></label>
                                            <div class="col-md-4">
                                                <input type="password" class="form-control" AUTOCOMPLETE='OFF' required name="rpassword"  aria-required="true" aria-invalid="true" aria-describedby="rpassword-error">
                                                  <span class="help-block"></span>                                         </div>
                                        </div>



                                    </div>


                                </div>
                            </div>					
                            <!-- End profile details -->

                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="pull-right">
                                            <a href="/merchant/profile/settings" class="btn btn-default">Cancel</a>
                                            <button type="submit" class="btn blue">Submit</button>
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