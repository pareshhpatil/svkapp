<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <h3 class="page-title">&nbsp;</h3>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row no-margin">
        <div class="col-md-1"></div>
        <div class="col-md-10">
            <h3 class="page-title">Validate OTP</h3>
            <?php
            if (isset($this->error)) {
                ?>
                <div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                    <div class="media">
                        <?php echo $this->error; ?>
                    </div>

                </div>
            <?php }
            ?>
            <div class="portlet light bordered">
                <div class="portlet-body form">
                    <!--<h3 class="form-section">Profile details</h3>-->
                    <form action="/login/otpvalidate" method="post" id="submit_form"  class="form-horizontal form-row-sepe">
                        <?php echo CSRF::create('otp_validate'); ?>
                        <div class="form-body">
                            <!-- Start profile details -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label col-md-4">OTP<span class="required">
                                            </span></label>
                                        <div class="col-md-4">
                                            <input maxlength="6"  name="otp" required id="password" type="text" AUTOCOMPLETE="OFF" class="form-control" value="">
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>					
                            <!-- End profile details -->

                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-offset-6 col-md-5">
                                        <input type="hidden" name="id" value="<?php echo $this->id; ?>"/> 
                                        <button type="submit" class="btn blue">Submit</button>
                                        <button type="reset" class="btn default">Reset</button>
                                    </div>
                                </div>
                            </div>
                        </div></form>
                </div>
            </div>	
            <!-- END PAGE CONTENT-->
        </div>
    </div>
    <!-- END CONTENT -->
</div>