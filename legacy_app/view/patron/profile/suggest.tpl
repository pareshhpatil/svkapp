
    <div class="page-content">
        <!-- BEGIN PAGE HEADER-->
        <div class="page-bar">
            {include file="../../common/breadcumbs.tpl" title={$title} links=$links}
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
                                <p class="media-heading">{$k} - {$v.1}</p>
                            {/foreach}
                        </div>

                    </div>
                {/if}
                {if $success=='TRUE'}
                <div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                    <strong>Success!</strong>
                    <div class="media">
                        Thank you for suggesting your service provider to us. Our merchant on boarding team will get in touch with your service provider.
                    </div>

                </div>
                 {/if}
                 
                <div class="portlet light bordered">
                    <div class="portlet-body form">
                        <!--<h3 class="form-section">Profile details</h3>-->
                        <form action="/patron/profile/send" method="post"  class="form-horizontal form-row-sepe">
                            {CSRF::create('merchant_seggest')}
                            <div class="form-body">


                                <!-- Start profile details -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="alert alert-info alert-dismissable">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                                            <strong>Info!</strong>
                                            <div class="media">
                                                Thank you for using Swipez. If there are any more service providers that you would like to see on our platform, please make a suggestion using the form below. We would be happy to on-board any service providers you use onto our platform.
                                            </div>

                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Service provider name <span class="required">
                                                    * </span></label>
                                            <div class="col-md-4">
                                                <input type="text" name="name"   {$validate.name} class="form-control" value="">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Service provider email id (optional) <span class="required">
                                                </span></label>
                                            <div class="col-md-4">
                                                <input type="email" class="form-control"  name="password"  {$validate.sugg_extraemail} id="submit_form_password" aria-required="true" aria-invalid="false" aria-describedby="submit_form_password-error">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Service provider contact nos. (optional) <span class="required">
                                                </span></label>
                                            <div class="col-md-4">
                                                <input type="contact_no" class="form-control"  name="verifypassword" {$validate.sugg_extrano} aria-required="true" aria-invalid="true" >
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Nature of business <span class="required">
                                                *</span></label>
                                            <div class="col-md-4">
                                                <input type="text" class="form-control"  name="business_nature" {$validate.sugg_extra} aria-required="true" aria-invalid="true" >
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
                                            <button type="submit" class="btn blue">Save</button>
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