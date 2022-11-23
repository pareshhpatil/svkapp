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
                            Merchant Profile
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
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <div class="row">
                                        <input type="hidden" name="form_type" value="company">
                                        <div class="col-md-offset-9 col-md-3">

                                            <button type="submit"  class="btn blue">
                                                Save <i class="m-icon-swapright m-icon-white"></i>
                                            </button>
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
