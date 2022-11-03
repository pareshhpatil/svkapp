<!-- BEGIN CONTAINER -->

<!-- BEGIN CONTENT -->
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <h3 class="page-title">&nbsp;</h3>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="col-md-1"></div>
    <div class="col-md-10">
        <div class="loading" id="loader" style="display: none;">Loading&#8230;</div>
        <?php
        if ($this->hasError()) {
            ?> <div class="alert alert-danger display-none" style="display: block;">
                <button class="close" data-dismiss="alert"></button>
                <?php
                foreach ($this->_error as $error_name) {
                    ?> 
                    <?php
                    echo '<b>' . $error_name[0] . '</b> -';
                    $int = 1;
                    while (isset($error_name[$int])) {
                        echo '' . $error_name[$int];
                        $int++;
                    }
                    echo '<br>';
                }
                ?>
            </div>
        <?php } ?>


        <div class="portlet " id="form_wizard_1">
            <div class="portlet-title">
                <div class="caption">
                    Patron Registration
                </div>
            </div>

            <div class="portlet-body form">
                <br>
                <form action="/patron/register/saved" class="form-horizontal" id="submit_form" method="POST">
                    <div class="form-wizard">

                        <div class="form-body">



                            <div class="alert alert-danger display-none">
                                <button class="close" data-dismiss="alert"></button>
                                Please correct the below errors to complete registration.
                            </div>

                            <!--<h3 class="block">Provide your personal details</h3>-->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-5">Email ID <span class="required">*
                                            </span>
                                        </label>
                                        <div class="col-md-7">
                                            <input type="text" class="form-control" required value="<?php echo $_POST['email']; ?>"  name="email" <?php echo $this->HTMLValidatorPrinter->fetch("email"); ?>/>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-5">Password <span class="required">*
                                            </span>
                                        </label>
                                        <div class="col-md-7">
                                            <input type="password" class="form-control" AUTOCOMPLETE='OFF' name="password"  id="submit_form_password" <?php echo $this->HTMLValidatorPrinter->fetch("password"); ?>/>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-5">Confirm Password <span class="required">*
                                            </span>
                                        </label>
                                        <div class="col-md-7">
                                            <input type="password" AUTOCOMPLETE='OFF' class="form-control" name="rpassword" />
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-5">First name <span class="required">*
                                            </span>
                                        </label>
                                        <div class="col-md-7">
                                            <input type="text" required value="<?php echo $_POST['f_name']; ?>" name="f_name" <?php echo $this->HTMLValidatorPrinter->fetch("name"); ?> class="form-control"/>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-5">Last name <span class="required">*
                                            </span>
                                        </label>
                                        <div class="col-md-7">
                                            <input type="text"  required class="form-control" value="<?php echo $_POST['l_name']; ?>" name="l_name" <?php echo $this->HTMLValidatorPrinter->fetch("name"); ?>/>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-5">Date of birth <span class="required">*
                                            </span>
                                        </label>
                                        <div class="col-md-7">
                                            <input type="text" name="dob" required value="<?php echo $_POST['dob']; ?>" class="form-control date-picker"  autocomplete="off" data-date-format="dd M yyyy"  >
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-5">Mobile number <span class="required">*
                                            </span>
                                        </label>
                                        <div class="col-md-7">
                                            <input type="hidden"  value="+91" name="mob_country_code"/>
                                            <input type="text" required class="form-control" value="<?php echo $_POST['mobile']; ?>" name="mobile" <?php echo $this->HTMLValidatorPrinter->fetch("mobile"); ?>/>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-5">Landline <span class="required">
                                            </span>
                                        </label>
                                        <div class="col-md-2">
                                            <input type="text" class="form-control" placeholder="022" name="ll_country_code" <?php echo $this->HTMLValidatorPrinter->fetch("landlinecode"); ?>/>
                                            <span class="help-block"></span>
                                        </div>
                                        <div class="col-md-5">
                                            <input type="text" class="form-control" value="<?php echo $_POST['landline']; ?>" name="landline" <?php echo $this->HTMLValidatorPrinter->fetch("landline"); ?>/>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">   
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Address <span class="required">*
                                            </span>
                                        </label>
                                        <div class="col-md-7">
                                            <textarea class="form-control" name="address" required <?php echo $this->HTMLValidatorPrinter->fetch("address"); ?>><?php echo $_POST['address']; ?></textarea>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">City <span class="required" >*
                                            </span>
                                        </label>
                                        <div class="col-md-7">
                                            <input type="text" class="form-control" value="<?php echo $_POST['city']; ?>" name="city" required <?php echo $this->HTMLValidatorPrinter->fetch("name"); ?>/>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Zip code<span class="required">*
                                            </span>
                                        </label>
                                        <div class="col-md-7">
                                            <input type="text" class="form-control" value="<?php echo $_POST['zip']; ?>" name="zip" required <?php echo $this->HTMLValidatorPrinter->fetch("zipcode"); ?>/>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">State <span class="required">*
                                            </span>
                                        </label>
                                        <div class="col-md-7">
                                            <input type="text" class="form-control" value="<?php echo $_POST['state']; ?>" name="state" required <?php echo $this->HTMLValidatorPrinter->fetch("name"); ?>/>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Country <span class="required">*
                                            </span>
                                        </label>
                                        <div class="col-md-7">
                                            <input type="text" class="form-control" value="India" name="country" required <?php echo $this->HTMLValidatorPrinter->fetch("name"); ?>/>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Captcha <span class="required">*
                                            </span></label>
                                        <div class="col-md-7">
                                            <form id="comment_form" action="form.php" method="post">
                                                <div class="g-recaptcha" data-sitekey="<?php echo $this->capcha_key; ?>"></div>
                                            </form>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-2"></div>
                                        <div class="col-md-10">
                                            <label><input type="checkbox" required name="confirm"/></span>  I accept the <a href="<?php echo $this->server_name; ?>/terms-popup" class="iframe"> Terms and conditions</a> & <a href="<?php echo $this->server_name; ?>/privacy-popup" class="iframe">Privacy policy</a> <span class="required">
                                                </span>
                                            </label>
                                            <div id="form_payment_error"></div>
                                        </div>
                                    </div>

                                </div>
                            </div>



                        </div>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-offset-5 col-md-6">
                                    <input type="hidden" name="returnurl" value="<?php
                                    if (isset($this->returnUrl)) {
                                        echo $this->returnUrl;
                                    }
                                    ?>">
                                    <input type="submit" id="submit_btn" value="Submit" class="btn green"/>
                                    <input type="reset" value="Reset" class="btn default">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="clearfix">
    </div>
    <!-- END PAGE CONTENT-->
</div>
<!-- END CONTENT -->
</div>
<!-- END CONTAINER -->