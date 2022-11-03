<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->
    <head>
        <meta charset="utf-8"/>
        <title>Swipez | Register</title>
        <link rel="canonical" href="<?php echo $this->server_name; ?>/<?php echo $this->canonical; ?>"/>
        <meta name="title" content="Merchant Register">
        <meta name="description" content="Register on Swipez to collect payments, bulk invoice your customers, manage events. Business organization software for SMEs"/>
        
        
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8">
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>
        <link href="/assets/global/plugins/font-awesome/css/font-awesome.min.css?version=4.7" rel="stylesheet" type="text/css"/>
        <link href="/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css"/>
        <link href="/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="/assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
        <!-- END GLOBAL MANDATORY STYLES -->
        <!-- BEGIN PAGE LEVEL STYLES -->
        <link href="/assets/admin/pages/css/login.css" rel="stylesheet" type="text/css"/>
        <link href="/assets/admin/layout/css/colorbox.css" rel="stylesheet" type="text/css"/>
        <!-- END PAGE LEVEL SCRIPTS -->
        <!-- BEGIN THEME STYLES -->
        <link href="/assets/global/css/components.css" id="style_components" rel="stylesheet" type="text/css"/>
        <link href="/assets/global/css/plugins.css" rel="stylesheet" type="text/css"/>
        <link href="/assets/admin/layout/css/layout.css" rel="stylesheet" type="text/css"/>
        <link href="/assets/admin/layout/css/themes/blue.css<?php echo $this->fileTime('css', 'layout'); ?>" rel="stylesheet" type="text/css" id="style_color"/>
        <link href="/assets/admin/layout/css/custom.css" rel="stylesheet" type="text/css"/>
        <script src='https://www.google.com/recaptcha/api.js'></script>
        <!-- END THEME STYLES -->
        <link rel="shortcut icon" href="/favicon.ico"/>

        
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="login">
    <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
    <div class="menu-toggler sidebar-toggler">
    </div>
    <!-- END SIDEBAR TOGGLER BUTTON -->
    <!-- BEGIN LOGO -->
    <div class="logo">
        <a href="<?php echo $this->server_name; ?>">
            <img src="/assets/admin/layout/img/logo.png?v=2"  alt="Swipez Online Payment" title="Swipez Online Payment Solutions" />
        </a>
    </div>
    <!-- END LOGO -->
    <!-- BEGIN LOGIN -->
    <div class="content">
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

        <form class="register-form" style="display: none;" action="<?php echo $this->server_name; ?>/login/failed<?php
        if (isset($this->returnurl)) {
            echo '/' . $this->returnurl;
        }
        ?>" method="post" <?php
              if ($this->type == 'login') {
                  echo 'style="display: block;"';
              } else {
                  echo 'style="display: none;"';
              }
              ?>>
            <h3 class="form-title font-blue">Sign In</h3>

            <div class="alert alert-danger display-hide">
                <button class="close" data-close="alert"></button>
                <span>
                    Enter valid Email ID and password. </span>
            </div>
            <div class="form-group">
                <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
                <label class="control-label visible-ie8 visible-ie9">Email Id</label>
                <input class="form-control form-control-solid placeholder-no-fix" type="email"  placeholder="Email ID" name="username"/>
                <span class="help-block"></span>
            </div>
            <div class="form-group">
                <label class="control-label visible-ie8 visible-ie9">Password</label>
                <input class="form-control form-control-solid placeholder-no-fix" type="password" autocomplete="off" placeholder="Password" name="password"/>
            </div>
            <?php if ($this->showCaptcha) { ?>
                <div class="form-group">
                    <div class="g-recaptcha" required data-sitekey="<?php echo $this->capcha_key; ?>"></div>
                </div>
            <?php } ?>

            <div class="form-actions">

                <button type="submit" onclick="hideerror();" class="btn btn-success blue">Login</button>
                <a href="javascript:;" id="forget-password-reg" class="forget-password">Forgot Password?</a>
                <br>
                <br>
            </div>

            <div class="create-account">
                <p>
                    <a href="javascript:;" id="register-back-btn" class="uppercase">Create an account</a>
                </p>
            </div>
        </form>
        <form class="forget-form" action="<?php echo $this->server_name; ?>/login/forgotrequest" onsubmit="return forgotvalidate();" method="post" <?php
        if ($this->type == 'forgot') {
            echo 'style="display: block;"';
        } else {
            echo 'style="display: none;"';
        }
        ?>>
            <h3>Forgot Password ?</h3>
            <div class="alert alert-danger" style="display: none;" id="alerttt">
                <button class="close" data-close="alert"></button>
                <span>
                    Enter your valid e-mail address below to reset your password. </span>
            </div>


            <div class="form-group">
                <input class="form-control placeholder-no-fix" id="email" required type="email" autocomplete="off" placeholder="Email" name="email"/>

                <div class="form-group">
                    <br>
                    <form action="form.php" method="post">
                        <div class="g-recaptcha" required data-sitekey="<?php echo $this->capcha_key; ?>"></div>
                    </form>
                </div>
                <div class="form-actions">
                    <button type="button" id="back-btn-reg" class="btn btn-default">Back</button>
                    <button type="submit" onclick="return forgotvalidate();" class="btn btn-success uppercase pull-right blue">Submit</button>
                </div>

            </div>

        </form>

        <!-- BEGIN LOGIN FORM -->
        <form  action="/merchant/register/saved" class="login-form" id="submit_form" method="post">
            <h3 class="form-title font-blue">Merchant Registration</h3>
            <div class="form-group">
                <label class="control-label visible-ie8 visible-ie9">Company Name</label>
                <input class="form-control placeholder-no-fix" value="<?php echo $_POST['company_name']; ?>" maxlength="45" required="" type="text" placeholder="Company Name" name="company_name" /> </div>
            <div class="form-group">
                <label class="control-label visible-ie8 visible-ie9">Email ID</label>
                <input class="form-control placeholder-no-fix" required type="email" placeholder="Email ID" value="<?php echo $_POST['email']; ?>" id="email"  name="email" <?php echo $this->HTMLValidatorPrinter->fetch("email"); ?> /> </div>
            <div class="form-group">
                <label class="control-label visible-ie8 visible-ie9">Mobile No.</label>
                <input class="form-control placeholder-no-fix" type="text" required placeholder="Mobile No." value="<?php echo $_POST['mobile']; ?>" name="mobile" id="mobile" <?php echo $this->HTMLValidatorPrinter->fetch("mobile"); ?> /> </div>


            <div class="form-group">
                <label class="control-label visible-ie8 visible-ie9">Password</label>
                <input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="Password" name="password" id="submit_form_password" <?php echo $this->HTMLValidatorPrinter->fetch("password"); ?>/> </div>
            <div class="form-group">
                <label class="control-label visible-ie8 visible-ie9">Re-type Your Password</label>
                <input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="Re-type Your Password" name="rpassword" /> 
            </div>
            <div class="form-group">
                <label class="control-label visible-ie8 visible-ie9">Captcha</label>
                <div class="form-group">
                    <div class="g-recaptcha" required data-sitekey="<?php echo $this->capcha_key; ?>"></div>
                </div>
            </div>
            <div class="form-group margin-top-20 margin-bottom-20">
                <label class="mt-checkbox mt-checkbox-outline" style="font-size: 13px;">
                    <input required="" type="checkbox" name="confirm" /> I agree to the
                    <a href="/terms-popup" class="iframe cboxElement"> Terms and Service</a> &
                    <a href="/privacy-popup" class="iframe cboxElement">Privacy Policy</a>
                    <span></span>
                </label>
                <div id="form_payment_error"></div>
            </div>
            <div class="form-actions center middle">
                <input type="hidden" name="plan_id" value="<?php echo $this->plan_id; ?>">
                <button type="submit" id="register-submit-btn" class="btn blue center middle">Register</button>
            </div>
            <div class="form-actions center middle">
                Already have a Swipez account? <a id="register-btn" class="">Login now</a>.
            </div>
        </form>
        <!-- END LOGIN FORM -->

    </div>

    <!-- END LOGIN -->
    <!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
    <!-- BEGIN CORE PLUGINS -->
    <!--[if lt IE 9]>
    <script src="/assets/global/plugins/respond.min.js"></script>
    <script src="/assets/global/plugins/excanvas.min.js"></script> 
    <![endif]-->
      
    <script src="/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/jquery-migrate.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/jquery.cokie.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
    <!-- END CORE PLUGINS -->
    <script type="text/javascript" src="/assets/global/plugins/jquery-validation/js/jquery.validate.min.js"></script>
    <script type="text/javascript" src="/assets/global/plugins/jquery-validation/js/additional-methods.js"></script>
    <script src="/assets/global/scripts/swipez.js" type="text/javascript"></script>
    <script src="/assets/admin/layout/scripts/layout.js?version=2" type="text/javascript"></script>
    <script src="/assets/admin/layout/scripts/quick-sidebar.js?version=2" type="text/javascript"></script>
    <script src="/assets/admin/layout/scripts/demo.js?version=2" type="text/javascript"></script>
    <script src="/assets/admin/pages/scripts/components-pickers.js?version=2"></script>
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <script type="text/javascript" src="/assets/global/plugins/bootstrap-wizard/jquery.bootstrap.wizard.min.js"></script>

    <script type="text/javascript" src="/assets/global/plugins/select2/select2.min.js"></script>
    <script src="/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script>
    <script type="text/javascript" src="/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
    <script src="/assets/admin/pages/scripts/form-validation.js?version=2"></script>

    <!-- Form validation -->
    <script src="/assets/admin/pages/scripts/form-wizard.js?version=2"></script>
    <!-- Form validation end -->

    <script type="text/javascript" src="/assets/global/plugins/jquery-mixitup/jquery.mixitup.min.js"></script>
    <script type="text/javascript" src="/assets/global/plugins/fancybox/source/jquery.fancybox.pack.js"></script>
    <script src="/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/holder.js" type="text/javascript"></script>
    <script src="/assets/admin/pages/scripts/login.js" type="text/javascript"></script>
    <script src="/assets/admin/layout/scripts/colorbox.js"></script>
    <script src="/assets/admin/pages/scripts/template-validation.js?version=2"></script>

    <!-- END PAGE LEVEL PLUGINS -->

    <script>
        jQuery(document).ready(function () {
            $(".iframe").colorbox({iframe: true, width: "80%", height: "90%"});
            Swipez.init(); // init swipez core components
            Layout.init(); // init current layout
            QuickSidebar.init(); // init quick sidebar
            Demo.init(); // init demo features
            FormValidation.init();
            FormWizard.init();
            Login.init();
            ComponentsPickers.init();
        });
    </script>

    <!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>