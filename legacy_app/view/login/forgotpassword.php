<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <h3 class="page-title">&nbsp;</h3>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row no-margin">
        <div class="col-md-1"></div>
        <div class="col-md-10">
            <h3 class="page-title">Reset password</h3>
            <?php
            if ($this->hasError()) {
                ?>
                <div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                    <div class="media">
                        <?php
                        foreach ($this->_error as $error_name) {
                            echo $error_name[0] . '-' . $error_name[1];
                        }
                        ?>
                    </div>

                </div>
            <?php }
            ?>
            <div class="portlet light bordered">
                <div class="portlet-body form">
                    <!--<h3 class="form-section">Profile details</h3>-->
                    <form action="/login/resetpassword/<?php echo $this->link; ?>" method="post" id="submit_form"  class="form-horizontal form-row-sepe">
                        <div class="form-body">
                            <!-- Start profile details -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label col-md-4">New password <span class="required">
                                            </span></label>
                                        <div class="col-md-4">
                                            <input  name="password" required id="password" type="password" AUTOCOMPLETE="OFF" class="form-control" value="">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Confirm password <span class="required">
                                            </span></label>
                                        <div class="col-md-4">
                                            <input  name="verifypassword" required type="password" AUTOCOMPLETE='OFF' class="form-control" value="">
                                        </div>
                                    </div>
                                </div>
                            </div>					
                            <!-- End profile details -->

                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-offset-6 col-md-5">
                                        <input type="hidden" name="email" value="<?php echo $this->email; ?>"/> 
                                        <?php if (isset($this->verify)) { ?>
                                            <input type="hidden" name="verified" value="1"/> 
                                        <?php } ?>
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