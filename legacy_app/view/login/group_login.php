<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->
    <head>
        <meta charset="utf-8"/>
        <title>Swipez | Group login</title>
        <link rel="canonical" href="<?php echo $this->server_name; ?>/<?php echo $this->canonical; ?>"/>
        
        
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
        <link rel="shortcut icon" href="images/briq.ico"/>
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
            <a href="<?php echo $this->server_name;?>">
                <img src="/assets/admin/layout/img/logo.png?v=2"  alt="Swipez Online Payment" title="Swipez Online Payment Solutions" />
            </a>
        </div>
        <!-- END LOGO -->
        <!-- BEGIN LOGIN -->
        <div class="content">
            <?php
            if ($this->hasError()) {
                ?>
                <div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                    <div class="media">
                        <?php
                        foreach ($this->_error as $error_name) {

                            echo $error_name[1];
                        }
                        ?>
                    </div>

                </div>
            <?php }
            ?>
            <!-- BEGIN LOGIN FORM -->
            <form class="login-form" action="/group/failed<?php
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
                    <label class="control-label visible-ie8 visible-ie9">Merchant name</label>
                    <select name="group_id" required class="form-control select2me" data-placeholder="Select...">
                        <option value="">Select Merchant</option>
                        <?php
                        foreach ($this->group_list as $row) {
                            echo '<option value="' . $row['group_id'] . '">' . $row['merchant_name'] . '</option>';
                        }
                        ?>
                    </select>
                    <span class="help-block"></span>
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
                    <button type="submit" class="btn btn-success uppercase blue">Login</button>
                    <a href="javascript:;" id="forget-password" class="forget-password">Forgot Password?</a>
                </div>

                <div class="create-account">
                    <p>
                        <a href="<?php echo $this->server_name;?>/register" class="uppercase">Create an account</a>
                    </p>
                </div>
            </form>
            <!-- END LOGIN FORM -->
            <form class="forget-form" action="/group/forgotrequest" onsubmit="return forgotvalidate();" method="post" <?php
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
                    <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
                    <label class="control-label visible-ie8 visible-ie9">Merchant name</label>
                    <select name="group_id" required class="form-control select2me" data-placeholder="Select...">
                        <option value="">Select Merchant</option>
                        <?php
                        foreach ($this->group_list as $row) {
                            echo '<option value="' . $row['group_id'] . '">' . $row['merchant_name'] . '</option>';
                        }
                        ?>
                    </select>
                    <span class="help-block"></span>
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
                        <button type="button" id="back-btn" class="btn btn-default">Back</button>
                        <button type="submit" onclick="return forgotvalidate();" class="btn btn-success uppercase pull-right blue">Submit</button>
                    </div>

                </div>

            </form>
        </div>

        <script>
                function forgotvalidate()
                {
                    forgot_email = document.getElementById('email').value;

                    filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                    if (filter.test(forgot_email)) {
                        return true;
                    }
                    else
                    {
                        document.getElementById('alerttt').style.display = 'block';
                        return false;
                    }


                }
        </script>
        <!-- END LOGIN -->
        <!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
        <!-- BEGIN CORE PLUGINS -->
        <!--[if lt IE 9]>
        <script src="/assets/global/plugins/respond.min.js"></script>
        <script src="/assets/global/plugins/excanvas.min.js"></script> 
        <![endif]-->
       <script src="/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
        <script src="/assets/global/plugins/jquery-migrate.min.js" type="text/javascript"></script>
        <script src="/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="/assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
        <script src="/assets/global/plugins/jquery.cokie.min.js" type="text/javascript"></script>
        <script src="/assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
        <!-- END CORE PLUGINS -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <script src="/assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN PAGE LEVEL SCRIPTS -->
        <script src="/assets/global/scripts/swipez.js" type="text/javascript"></script>
        <script src="/assets/admin/layout/scripts/layout.js?version=2" type="text/javascript"></script>
        <script src="/assets/admin/layout/scripts/demo.js?version=2" type="text/javascript"></script>
        <script src="/assets/admin/pages/scripts/login.js" type="text/javascript"></script>
        <script src="/assets/admin/layout/scripts/colorbox.js"></script>

        <!-- END PAGE LEVEL SCRIPTS -->
        <script>
            jQuery(document).ready(function() {
                $(".iframe").colorbox({iframe: true, width: "80%", height: "90%"});
                Swipez.init(); // init swipez core components
                Layout.init(); // init current layout
                Login.init();
                Demo.init();
            });
        </script>
        <!-- END JAVASCRIPTS -->
    </body>
    <!-- END BODY -->
</html>