<!-- BEGIN CONTAINER -->

<!-- BEGIN CONTENT -->
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <h3 class="page-title">&nbsp</h3>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="col-md-1"></div>
    <div class="col-md-10">

        <?php
        if ($this->hasError()) {
            ?> <div class="alert alert-danger display-none" style="display: block;">
                <button class="close" data-dismiss="alert"></button>
                <?php
                foreach ($this->_error as $error_name) {

                    echo '<b>' . $error_name[0] . '</b> -' . $error_name[1];
                    echo '<br>';
                }
                ?>
            </div>
        <?php } ?>


        <div class="row">
            <div class="col-md-12">
                <div class="loading" id="loader" style="display: none;">Loading&#8230;</div>
                <div class="portlet " id="form_wizard_1">
                    <div class="portlet-title">
                        <div class="caption">
                            Merchant Registration
                        </div>
                    </div>
                    <div class="portlet-body form">
                        <form action="/merchant/profile/completesaved" class="form-horizontal" id="submit_form" method="POST" enctype="multipart/form-data">
                            <div class="form-wizard">
                                <div class="form-body">
                                    <ul class="nav nav-pills nav-justified steps">
                                        <li>
                                            <a href="#tab1" data-toggle="tab" class="step">
                                                <span class="number">
                                                    1 </span>
                                                <span class="desc">
                                                    <i class="fa fa-check"></i> Company </span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#tab2" data-toggle="tab" class="step">
                                                <span class="number">
                                                    2 </span>
                                                <span class="desc">
                                                    <i class="fa fa-check"></i> Contact </span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#tab3" data-toggle="tab" class="step active">
                                                <span class="number">
                                                    3 </span>
                                                <span class="desc">
                                                    <i class="fa fa-check"></i> Documents & Bank Details </span>
                                            </a>
                                        </li>

                                    </ul>
                                    <div id="bar" class="progress progress-striped" role="progressbar">
                                        <div class="progress-bar progress-bar-success">
                                        </div>
                                    </div>
                                    <div class="tab-content">
                                        <div class="alert alert-danger display-none">
                                            <button class="close" data-dismiss="alert"></button>
                                            You have some form errors. Please check below.
                                        </div>
                                        <div class="alert alert-success display-none">
                                            <button class="close" data-dismiss="alert"></button>
                                            Your form validation is successful!
                                        </div>

                                        <div class="tab-pane active" id="tab1">
                                            <h3 class="block">Provide your company details</h3>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label col-md-5">Entity type <span class="required">*
                                                            </span>
                                                        </label>
                                                        <div class="col-md-6">
                                                            <select name="type" required class="form-control select2me" data-placeholder="Select...">
                                                                <option value=""></option>
                                                                <?php
                                                                foreach ($this->entitytype as $row) {
                                                                    if ($this->entityselected == $row['config_key']) {
                                                                        echo '<option value="' . $row['config_key'] . '" selected="selected">' . $row['config_value'] . '</option>';
                                                                    } else {
                                                                        echo '<option value="' . $row['config_key'] . '">' . $row['config_value'] . '</option>';
                                                                    }
                                                                }
                                                                ?>
                                                            </select>
                                                            <span class="help-block"></span>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="control-label col-md-5">Industry type <span class="required">*
                                                            </span>
                                                        </label>
                                                        <div class="col-md-6">
                                                            <select name="industry_type" required class="form-control select2me" data-placeholder="Select...">
                                                                <option value=""></option>
                                                                <?php
                                                                foreach ($this->industrytype as $row) {
                                                                    if ($this->industryselected == $row['config_key']) {
                                                                        echo '<option value="' . $row['config_key'] . '" selected="selected">' . $row['config_value'] . '</option>';
                                                                    } else {
                                                                        echo '<option value="' . $row['config_key'] . '">' . $row['config_value'] . '</option>';
                                                                    }
                                                                }
                                                                ?>
                                                            </select>
                                                            <span class="help-block"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label col-md-5">Company registration no <span class="required">
                                                            </span>
                                                        </label>
                                                        <div class="col-md-6">
                                                            <input type="text" value="<?php echo $_POST['registration_no']; ?>" name="registration_no" class="form-control" <?php echo $this->HTMLValidatorPrinter->fetch("resg_no"); ?> />
                                                            <span class="help-block"></span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label col-md-5">Company PAN <span class="required">
                                                            </span>
                                                        </label>
                                                        <div class="col-md-6">
                                                            <input type="text" value="<?php echo $_POST['pan']; ?>" name="pan" class="form-control" <?php echo $this->HTMLValidatorPrinter->fetch("pan"); ?>/>
                                                            <span class="help-block"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="tab2">
                                            <h3 class="block">Provide your contact details</h3>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label col-md-4">Contact person first name <span class="required" >*
                                                            </span>
                                                        </label>
                                                        <div class="col-md-7">
                                                            <input type="text" class="form-control" value="<?php echo $_POST['f_name']; ?>" name="f_name" id="f_name" required <?php echo $this->HTMLValidatorPrinter->fetch("name"); ?>/>
                                                            <span class="help-block"></span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label col-md-4">Contact person last name <span class="required" >*
                                                            </span>
                                                        </label>
                                                        <div class="col-md-7">
                                                            <input type="text" class="form-control" value="<?php echo $_POST['l_name']; ?>" name="l_name" id="l_name" required <?php echo $this->HTMLValidatorPrinter->fetch("name"); ?>/>
                                                            <span class="help-block"></span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label col-md-4">Registered Company Address <span class="required">*
                                                            </span>
                                                        </label>
                                                        <div class="col-md-7">
                                                            <textarea class="form-control"  name="address" id="address" required <?php echo $this->HTMLValidatorPrinter->fetch("address"); ?>><?php echo $_POST['address']; ?></textarea>
                                                            <span class="help-block"></span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label col-md-4">City <span class="required" >*
                                                            </span>
                                                        </label>
                                                        <div class="col-md-7">
                                                            <input type="text" class="form-control" value="<?php echo $_POST['city']; ?>" name="city" id="city" required <?php echo $this->HTMLValidatorPrinter->fetch("name"); ?>/>
                                                            <span class="help-block"></span>
                                                        </div>
                                                    </div>


                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label col-md-4">Zip code<span class="required">*
                                                            </span>
                                                        </label>
                                                        <div class="col-md-7">
                                                            <input type="text" class="form-control" value="<?php echo $_POST['zip']; ?>" name="zip" id="zip" required <?php echo $this->HTMLValidatorPrinter->fetch("zipcode"); ?>/>
                                                            <span class="help-block"></span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label col-md-4">State <span class="required">*
                                                            </span>
                                                        </label>
                                                        <div class="col-md-7">
                                                            <input type="text" class="form-control" value="<?php echo $_POST['state']; ?>" name="state" id="state" required <?php echo $this->HTMLValidatorPrinter->fetch("name"); ?>/>
                                                            <span class="help-block"></span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label col-md-4">Country <span class="required">*
                                                            </span>
                                                        </label>
                                                        <div class="col-md-7">
                                                            <input type="text" class="form-control" value="India" name="country" id="country" required <?php echo $this->HTMLValidatorPrinter->fetch("name"); ?>/>
                                                            <span class="help-block"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <h3>Current business address <label><input  type="checkbox" onchange="sameaddress();"></span> Same as registered address </label></h3>
                                            <hr>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label col-md-4">Business Address <span class="required">*
                                                            </span>
                                                        </label>
                                                        <div class="col-md-7">
                                                            <textarea class="form-control" name="current_address" id="current_address" required <?php echo $this->HTMLValidatorPrinter->fetch("addr1"); ?>><?php echo $_POST['address']; ?></textarea>
                                                            <span class="help-block"></span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label col-md-4">City <span class="required">*
                                                            </span>
                                                        </label>
                                                        <div class="col-md-7">
                                                            <input type="text" class="form-control" value="<?php echo $_POST['current_city']; ?>" name="current_city" id="current_city" required <?php echo $this->HTMLValidatorPrinter->fetch("name"); ?>/>
                                                            <span class="help-block"></span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label col-md-4">Zip code<span class="required">*
                                                            </span>
                                                        </label>
                                                        <div class="col-md-7">
                                                            <input type="text" class="form-control" value="<?php echo $_POST['current_zip']; ?>" name="current_zip"  id="current_zip" required <?php echo $this->HTMLValidatorPrinter->fetch("zipcode"); ?>/>
                                                            <span class="help-block"></span>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label col-md-4">State <span class="required">*
                                                            </span>
                                                        </label>
                                                        <div class="col-md-7">
                                                            <input type="text" class="form-control" value="<?php echo $_POST['current_state']; ?>" name="current_state" id="current_state" required <?php echo $this->HTMLValidatorPrinter->fetch("name"); ?>/>
                                                            <span class="help-block"></span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label col-md-4">Country <span class="required">*
                                                            </span>
                                                        </label>
                                                        <div class="col-md-7">
                                                            <input type="text" class="form-control" value="<?php echo $_POST['current_country']; ?>" name="current_country" id="current_country" required <?php echo $this->HTMLValidatorPrinter->fetch("name"); ?>/>
                                                            <span class="help-block"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <h3>Current business contact </h3>
                                            <hr>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label col-md-4">Business email <span class="required">*
                                                            </span><label><input id="sameas_email" onchange="sameas('email');" type="checkbox">Same as personal </label>
                                                        </label>
                                                        <div class="col-md-7">
                                                            <input type="hidden" id="email" value="<?php echo $this->personalDetails['email_id']; ?>">
                                                            <input type="text" value="<?php echo $_POST['business_email']; ?>" name="business_email" required id="business_email" class="form-control" <?php echo $this->HTMLValidatorPrinter->fetch("email"); ?>/>
                                                            <span class="help-block"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label col-md-4">Business contact <span class="required">*
                                                            </span><label><input id="sameas_phone" onchange="sameas('phone');" type="checkbox">Same as personal </label>
                                                        </label>

                                                        <div class="col-md-7">
                                                            <input type="hidden" name="business_contact_code"  value="+91">
                                                            <input type="hidden" id="mobile" value="<?php echo $this->personalDetails['mobile_no']; ?>">
                                                            <input type="text" value="<?php echo $_POST['business_contact']; ?>" required name="business_contact" id="business_contact" class="form-control" maxlength="20" />
                                                            <span class="help-block"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="tab3">
                                            <h3 class="block">Bank Details</h3>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label col-md-5">Account number <span class="required">*
                                                            </span>
                                                        </label>
                                                        <div class="col-md-6">
                                                            <input type="text" value="<?php echo $_POST['account_number']; ?>" required="" pattern="[0-9]" title="Please enter numeric characters" maxlength="20" class="form-control" name="account_number">
                                                            <span class="help-block"></span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label col-md-5">Bank account holder name <span class="required">*
                                                            </span>
                                                        </label>
                                                        <div class="col-md-6">
                                                            <input type="text" value="<?php echo $_POST['account_holder_number']; ?>" required="" placeholder="ABC Pvt. Ltd. OR Rahul Sharma" title="" maxlength="100" class="form-control" name="account_holder_number">
                                                            <span class="help-block"></span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label col-md-5">IFSC code<span class="required">*
                                                            </span>
                                                        </label>
                                                        <div class="col-md-6">
                                                            <input type="text" required="" <?php echo $this->HTMLValidatorPrinter->fetch("name_text"); ?> value="<?php echo $_POST['ifsc_code']; ?>" maxlength="20" pattern="" class="form-control" name="ifsc_code">
                                                            <span class="help-block"></span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label col-md-5">Bank name <span class="required">*
                                                            </span>
                                                        </label>
                                                        <div class="col-md-6">
                                                            <input type="text" required="" value="<?php echo $_POST['bank_name']; ?>" maxlength="100" class="form-control" name="bank_name">
                                                            <span class="help-block"></span>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                            <h3 class="block">Upload your Documents</h3>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label col-md-5">Aadhaar card <span class="required">*
                                                            </span>
                                                        </label>
                                                        <div class="col-md-6">
                                                            <input type="file" accept="image/*,application/pdf" onchange="return validatefilesize(1000000, 'adhar_card');" id="adhar_card" name="doc_adhar_card" required="" >
                                                            <span class="help-block red">* Max image size 1 MB</span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label col-md-5">Pan Card<span class="required">*
                                                            </span>
                                                        </label>
                                                        <div class="col-md-6">
                                                            <input type="file" accept="image/*,application/pdf" onchange="return validatefilesize(1000000, 'pan_card');" id="pan_card" name="doc_pan_card" required="">
                                                            <span class="help-block red">* Max image size 1 MB</span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label col-md-5">Cancelled Cheque <span class="required">*
                                                            </span>
                                                        </label>
                                                        <div class="col-md-6">
                                                            <input type="file" accept="image/*,application/pdf" onchange="return validatefilesize(1000000, 'cancelled_cheque');" id="cancelled_cheque" name="doc_cancelled_cheque" required="">
                                                            <span class="help-block red">* Max image size 1 MB</span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label col-md-5">GST certificate 
                                                        </label>
                                                        <div class="col-md-6">
                                                            <input type="file" accept="image/*,application/pdf" onchange="return validatefilesize(1000000, 'gst_cer');" id="gst_cer" name="doc_gst_cer" >
                                                            <span class="help-block red">* Max image size 1 MB</span>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="form-actions">
                                    <div class="row">
                                        <input type="hidden" name="merchant_id" value="<?php echo $this->plan_id; ?>">
                                        <div class="col-md-offset-9 col-md-3">
                                            <a href="javascript:;" class="btn default button-previous">
                                                <i class="m-icon-swapleft"></i> Back </a>
                                            <a href="javascript:;" id="conti" class="btn blue button-next">
                                                Continue <i class="m-icon-swapright m-icon-white"></i>
                                            </a>
                                            <a href="javascript:;" onclick="document.getElementById('conti').click();" class="btn blue button-submit">
                                                Save <i class="m-icon-swapright m-icon-white"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
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

<script>
    function sameas(type)
    {
        if (type == 'email') {
            if ($('#sameas_' + type).is(':checked')) {
                document.getElementById('business_email').value = document.getElementById('email').value;
            } else
            {
                document.getElementById('business_email').value = '';
            }
        } else
        {
            if ($('#sameas_' + type).is(':checked')) {
                document.getElementById('business_contact').value = document.getElementById('mobile').value;
            } else
            {
                document.getElementById('business_contact').value = '';
            }

        }
    }
</script>