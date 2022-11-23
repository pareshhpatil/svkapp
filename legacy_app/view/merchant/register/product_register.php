<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->
    <head>
        <meta charset="utf-8"/>
        <title>Swipez | Registration complete</title>
        <link rel="canonical" href="<?php echo $this->server_name; ?>/<?php echo $this->canonical; ?>"/>
        <meta name="title" content="Merchant Register">
        <meta name="description" content="Register on Swipez to collect payments, bulk invoice your customers, manage events. Business organization software for SMEs"/>
        <?php if ($this->env == 'PROD') { ?>
            <!-- Google Tag Manager -->
            <script>(function (w, d, s, l, i) {
                    w[l] = w[l] || [];
                    w[l].push({'gtm.start':
                                new Date().getTime(), event: 'gtm.js'});
                    var f = d.getElementsByTagName(s)[0],
                            j = d.createElement(s), dl = l != 'dataLayer' ? '&l=' + l : '';
                    j.async = true;
                    j.src =
                            'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
                    f.parentNode.insertBefore(j, f);
                })(window, document, 'script', 'dataLayer', 'GTM-KJ2KHBQ');</script>
            <!-- End Google Tag Manager -->
        <?php } ?>

        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8">
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>
        <link href="/assets/global/plugins/font-awesome/css/font-awesome.min.css?version=4.7" rel="stylesheet" type="text/css"/>
        <link href="/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>

        <!-- END GLOBAL MANDATORY STYLES -->
        <!-- BEGIN PAGE LEVEL STYLES -->
        <link href="/assets/admin/pages/css/login.css" rel="stylesheet" type="text/css"/>
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
        <?php
        include_once '../legacy_app/view/header/body_tag.php';
        ?>
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

            <!-- BEGIN LOGIN FORM -->
            <form  action="/merchant/register/saved"   id="submit_form" method="post">
                <h3 class="form-title font-blue">Complete your profile</h3>
                <div class="form-group">
                    <label class="control-label visible-ie8 visible-ie9">Company name</label>
                    <input class="form-control placeholder-no-fix" value="<?php echo $_POST['company_name']; ?>" maxlength="50" required="" type="text" placeholder="Company name" name="company_name" /> 
                </div>
                <div class="form-group">
                    <label class="control-label visible-ie8 visible-ie9">Full name</label>
                    <input class="form-control placeholder-no-fix" value="<?php echo $_POST['name']; ?>" maxlength="100" required="" type="text" placeholder="Full name" name="name" /> 
                </div>
                <?php if ($this->data['email_id'] != '') { ?>
                    <input type="hidden" value="<?php echo $this->data['email_id']; ?>" name="email">
                <?php } else { ?>
                    <div class="form-group">
                        <label class="control-label visible-ie8 visible-ie9">Email ID</label>
                        <input class="form-control placeholder-no-fix" required type="email" placeholder="Business email id" value="<?php echo $_POST['email']; ?>" id="email"  name="email" <?php echo $this->HTMLValidatorPrinter->fetch("email"); ?> /> 
                    </div>
                <?php } ?>

                <?php if ($this->data['mobile'] != '') { ?>
                    <input type="hidden" value="<?php echo $this->data['mobile']; ?>" name="mobile">
                <?php } else { ?>
                    <div class="form-group">
                        <label class="control-label visible-ie8 visible-ie9">Mobile No.</label>
                        <input class="form-control placeholder-no-fix" type="text" required placeholder="Mobile number" value="<?php echo $_POST['mobile']; ?>" name="mobile" id="mobile" <?php echo $this->HTMLValidatorPrinter->fetch("mobile"); ?> /> 
                    </div>
                <?php } ?>
                <?php if ($this->google_validate == true) { ?>
                    <input type="hidden" value="<?php echo $this->randpassword; ?>" name="password">
                <?php } else { ?>
                    <div class="form-group">
                        <label class="control-label visible-ie8 visible-ie9">Password</label>
                        <div class="input-group">
                            <input class="form-control placeholder-no-fix"  type="password" autocomplete="off" placeholder="Password" name="password" field="password" id="submit_form_password" <?php echo $this->HTMLValidatorPrinter->fetch("password"); ?>/> 
                            <span class="input-group-addon" onclick="showPassword();" style="background: #ffffff;cursor: pointer;max-height: 100%;">
                                <i class="fa fa-eye"></i>
                            </span>
                        </div>
                    </div>
                    <span id="form_password_error"></span>
                <?php } ?>

                <div class="form-actions center middle">
                    <br>
                    <?php echo CSRF::create('merchant_register'); ?>
                    <input type="hidden" name="service_id" value="<?php echo $this->service_id; ?>">
                    <button type="submit" class="btn blue center middle">Go to dashboard</button>
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
        <!-- BEGIN GROOVE WIDGET CODE -->

<script>

!function(e,t){if(!e.groove){var i=function(e,t){return Array.prototype.slice.call(e,t)},a={widget:null,loadedWidgets:{},classes:{Shim:null,Embeddable:function(){this._beforeLoadCallQueue=[],this.shim=null,this.finalized=!1;var e=function(e){var t=i(arguments,1);if(this.finalized){if(!this[e])throw new TypeError(e+"() is not a valid widget method");this[e].apply(this,t)}else this._beforeLoadCallQueue.push([e,t])};this.initializeShim=function(){a.classes.Shim&&(this.shim=new a.classes.Shim(this))},this.exec=e,this.init=function(){e.apply(this,["init"].concat(i(arguments,0))),this.initializeShim()},this.onShimScriptLoad=this.initializeShim.bind(this),this.onload=void 0}},scriptLoader:{callbacks:{},states:{},load:function(e,i){if("pending"!==this.states[e]){this.states[e]="pending";var a=t.createElement("script");a.id=e,a.type="text/javascript",a.async=!0,a.src=i;var s=this;a.addEventListener("load",(function(){s.states[e]="completed",(s.callbacks[e]||[]).forEach((function(e){e()}))}),!1);var n=t.getElementsByTagName("script")[0];n.parentNode.insertBefore(a,n)}},addListener:function(e,t){"completed"!==this.states[e]?(this.callbacks[e]||(this.callbacks[e]=[]),this.callbacks[e].push(t)):t()}},createEmbeddable:function(){var t=new a.classes.Embeddable;return e.Proxy?new Proxy(t,{get:function(e,t){return e instanceof a.classes.Embeddable?Object.prototype.hasOwnProperty.call(e,t)||"onload"===t?e[t]:function(){e.exec.apply(e,[t].concat(i(arguments,0)))}:e[t]}}):t},createWidget:function(){var e=a.createEmbeddable();return a.scriptLoader.load("groove-script","https://d9c27c88-adc0-4923-a7e8-2ef0b33493f5.widget.cluster.groovehq.com/api/loader"),a.scriptLoader.addListener("groove-iframe-shim-loader",e.onShimScriptLoad),e}};e.groove=a}}(window,document);

window.groove.widget = window.groove.createWidget();

window.groove.widget.init('d9c27c88-adc0-4923-a7e8-2ef0b33493f5', {});

</script>

<!-- END GROOVE WIDGET CODE -->
        <script>
            function showPassword() {
                var x = document.getElementById("submit_form_password");
                if (x.type === "password") {
                    x.type = "text";
                } else {
                    x.type = "password";
                }
            }
        </script>
        <script src="/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
        <script src="/assets/global/plugins/jquery-migrate.min.js" type="text/javascript"></script>
        <script src="/assets/global/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
        <script src="/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <!-- END CORE PLUGINS -->
        <script type="text/javascript" src="/assets/global/plugins/jquery-validation/js/jquery.validate.min.js"></script>
        <script src="/assets/global/scripts/swipez.js" type="text/javascript"></script>
        <script src="/assets/admin/layout/scripts/layout.js?version=2" type="text/javascript"></script>
        <script src="/assets/admin/layout/scripts/demo.js?version=2" type="text/javascript"></script>
        <script src="/assets/admin/pages/scripts/components-pickers.js?version=2"></script>
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <script type="text/javascript" src="/assets/global/plugins/bootstrap-wizard/jquery.bootstrap.wizard.min.js"></script>

        <script type="text/javascript" src="/assets/global/plugins/select2/select2.min.js"></script>
        <script src="/assets/admin/pages/scripts/form-validation.js?version=2"></script>

        <!-- Form validation -->
        <script src="/assets/admin/pages/scripts/form-wizard.js?version=2"></script>
        <!-- Form validation end -->


        <!-- END PAGE LEVEL PLUGINS -->

        <script>
            jQuery(document).ready(function () {
                Swipez.init(); // init swipez core components
                Layout.init(); // init current layout
                Demo.init(); // init demo features
                FormValidation.init();
                FormWizard.init();
                ComponentsPickers.init();
            });
        </script>

        <!-- END JAVASCRIPTS -->
    </body>
    <!-- END BODY -->
</html>