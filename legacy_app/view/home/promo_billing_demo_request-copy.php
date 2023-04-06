<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-js">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->
    <head>
        <meta charset="utf-8"/>
        <title><?php echo $this->title; ?></title>
        <meta name="description" content="<?php echo $this->description; ?>">
        <meta name="author" content="Swipez">
        <link rel="canonical" href="<?php echo $this->server_name; ?>/<?php echo $this->canonical; ?>"/>
        
        
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport"/>
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>
        <link href="/assets/global/plugins/font-awesome/css/font-awesome.min.css?version=4.7" rel="stylesheet" type="text/css"/>
        <link href="/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css"/>
        <link href="/assets/frontend/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <!-- END GLOBAL MANDATORY STYLES -->
        <!-- BEGIN PAGE LEVEL PLUGIN STYLES -->
        <link href="/assets/global/css/components.css" id="style_components" rel="stylesheet" type="text/css"/>
        <link href="/assets/global/plugins/owl.carousel/assets/owl.carousel.css" rel="stylesheet">
        <link href="/assets/global/plugins/slider-revolution-slider/rs-plugin/css/settings.css" rel="stylesheet">
        <link href="/assets/global/plugins/cubeportfolio/cubeportfolio/css/cubeportfolio.min.css" rel="stylesheet">
        <!-- END PAGE LEVEL PLUGIN STYLES -->
        <!-- BEGIN THEME STYLES -->
        <link href="/assets/frontend/onepage2/css/layout.css" rel="stylesheet" type="text/css"/>
        <link href="/assets/frontend/onepage2/css/custom.css" rel="stylesheet" type="text/css"/>
        <link href="/assets/admin/layout/css/colorbox.css" rel="stylesheet" type="text/css"/>
        <link href="/assets/frontend/onepage2/css/promoregister.css" rel="stylesheet" type="text/css"/>
        <script src='https://www.google.com/recaptcha/api.js'></script>
        <script src='/assets/admin/layout/scripts/register.js'></script>

        <!-- END THEME STYLES -->




        <link rel="shortcut icon" href="/images/briq.ico"/>
        
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<!-- DOC: Apply "page-on-scroll" class to the body element to set fixed header layout -->
<body class="page-header-fixed">
    <!-- BEGIN MAIN LAYOUT -->
    <!-- Header BEGIN -->
    <header class="page-header hidden-xs" style="height: 10px;position: absolute;">
        <nav class="" role="navigation">
            <div class="container" style="height: 10px;">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header page-scroll" style="background-color: transparent;">

                    <a class="navbar-brand" href="/" style="width: 100%;text-align: center;">
                        <img class="logo-default" src="/assets/frontend/onepage2/img/logo.png"  alt="Swipez Online Payment" title="Swipez Online Payment Solutions">
                        <img class="logo-scroll" src="/assets/frontend/onepage2/img/logo.png"  alt="Swipez Online Payment" title="Swipez Online Payment Solutions">
                    </a>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->

                <!-- End Navbar Collapse -->
            </div>
            <!--/container-->
        </nav>
    </header><!-- Header END -->
    <header class="page-header visible-xs" style="height: 90px;">
        <nav class="" role="navigation">
            <div class="container" style="height: 10px;">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header page-scroll" style="background-color: #234d65;line-height: -10px;">

                    <a class="navbar-brand" href="/" style="width: 100%;text-align: center;">
                        <img class="logo-default" src="/assets/frontend/onepage2/img/logo.png" alt="Logo">
                        <img class="logo-scroll" src="/assets/frontend/onepage2/img/logo.png" alt="Logo">
                    </a>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->

                <!-- End Navbar Collapse -->
            </div>
            <!--/container-->
        </nav>
    </header><!-- Header END -->
    <section id="intro" >
        <!-- Slider BEGIN -->
        <div class="row no-margin">
            <div class="col-md-12 hidden-xs" style="background-color: #234d65;height:310px;">
                <div class="container">
                    <br>
                    <div class="row">
                        <div class="col-md-4 md-margin-bottom-70" style="padding: 60px;">
                            <img class="pull-right" src="/assets/frontend/onepage2/img/member/spotlight_1.png" style="width: 85%;" alt="Bulk invoicing with auto-generated invoice numbers">
                        </div>
                        <div class="col-md-8 md-margin-bottom-70 margin-bottom-80" style="color: #97A4B4; max-width: 100%;">
                            <br><br><br><br> <h5 class="bannertext" style="max-width: 100%;">
                                Create beautiful GST friendly invoices.<br/>Easy error free billing at your fingertips.
                            </h5>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- Slider END -->
    </section>


    <!-- BEGIN INTRO SECTION -->

    <!-- END INTRO SECTION -->

    <!-- BEGIN MAIN LAYOUT -->
    <div class="page-content">
        <!-- SUBSCRIBE BEGIN -->

        <!-- SUBSCRIBE END -->
        <section id="tax" style="">
            <div class="row no-margin hidden-lg" style="margin-top: -7px;">
                <div class="col-md-4"  style="background-color: #f0f5f5;">
                <div class="row no-margin"  style="">

                    <div class="col-md-12">
                        <div class="row" id="form_demo">
                            <form action="" class="login-form" id="submit_form" method="post">
                                <h1 class="registertext">See Swipez in Action
                                </h1>
                                <div class="col-md-12">
                                    <div class="row ">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <input type="text" placeholder="Company name" value="<?php echo $_POST['company_name']; ?>"  name="company_name" maxlength="45" required="" class="form-control">
                                                <span class="help-block"> </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <input type="email"  placeholder="Email ID" value="<?php echo $_POST['email']; ?>" name="email" <?php echo $this->HTMLValidatorPrinter->fetch("email"); ?> required="" class="form-control">
                                                <span class="help-block"> </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <input type="text"  placeholder="Mobile No." value="<?php echo $_POST['mobile']; ?>" name="mobile" <?php echo $this->HTMLValidatorPrinter->fetch("mobile"); ?> required="" class="form-control">
                                                <span class="help-block"> </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <div id="errordiv" style="color: red;"><?php if ($this->error != '') {
        echo $this->error;
    } ?> </div>
                                                <div class="g-recaptcha" required data-sitekey="<?php echo $this->capcha_key; ?>"></div>
                                                <span class="help-block"> </span>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <div class="loading" style="display: none;" id="loader">Loading&#8230;</div>
                                                <button type="submit" class="registerbtn">Request a Demo</button>
                                                <input type="hidden" name="form_type" value="promo">
                                                <input type="hidden" name="utm_source" value="<?php echo $this->utm_source; ?>">
                                                <input type="hidden" name="utm_campaign" value="<?php echo $this->utm_campaign; ?>">
                                                <input type="hidden" name="utm_adgroup" value="<?php echo $this->utm_adgroup; ?>">
                                                <input type="hidden" name="utm_term" value="<?php echo $this->utm_term; ?>">
                                                <span class="help-block"> </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div id="demosent" style="display: none;">
                            <h3 class="page-title">Thank You </h3>
                            <div class="note note-success">
                                <h4 class="block">Demo request has been sent successfully.</h4>
                                <p>
                                    We are glad to see your interest in Swipez. Our service managers will guide you through the product during the demo call.
                                    <br>
                                    Our demo requires us to share our screen with you. Request you to kindly download <a target="_BLANK" href="https://anydesk.com/platforms/mac-os">Anydesk</a> for Mac or <a target="_BLANK" href="https://anydesk.com/platforms/windows">Anydesk</a> for Windows
                                    <br>
                                    Please feel free to write to us at <a>support@swipez.in</a> for any queries or reach us at +91 741 497 3338
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                <div class="col-xs-12">
                    <div class="col-md-12">
                        <p style="color: #234D65; font-size: 15px;"></br></br>With our billing system you can create customized invoices that suit your needs. Each invoice is generated with your company's look and feel. We have a variety of invoice templates to accomodate your different invoicing needs and statutory tax structures. Swipez billing covers B2B and B2C invoicing with a variety of online payment options. Including flat rates for B2B billing.
                        </p>
                        <br/>
                        <h2 style="color: #234D65;"><strong>Key features</strong></h2>
                        <br/>
                        <div class="col-md-5">
                            <p style="color: #234D65; font-size: 15px;">
                                <i class="fa fa-circle" style="color:#F89030; font-size: 15px;"></i> &nbsp;&nbsp;Generate individual or bulk invoices
                            </p>
                            <p style="color: #234D65; font-size: 15px;">
                                <i class="fa fa-circle" style="color:#F89030; font-size: 15px;"></i> &nbsp;&nbsp;Settle invoices paid offline centrally
                            </p>
                            <p style="color: #234D65; font-size: 15px;">
                                <i class="fa fa-circle" style="color:#F89030; font-size: 15px;"></i> &nbsp;&nbsp;Automate recurring billing
                            </p>
                            <p style="color: #234D65; font-size: 15px;">
                                <i class="fa fa-circle" style="color:#F89030; font-size: 15px;"></i> &nbsp;&nbsp;Bulk printing of invoices
                            </p>
                            <p style="color: #234D65; font-size: 15px;">
                                <i class="fa fa-circle" style="color:#F89030; font-size: 15px;"></i> &nbsp;&nbsp;Detailed reporting against invoices
                            </p>
                        </div>
                        <div class="col-md-1">
                        </div>
                        <div class="col-md-6">
                            <p style="color: #234D65; font-size: 15px;">
                                <i class="fa fa-circle" style="color:#F89030; font-size: 15px;"></i> &nbsp;&nbsp;Integrate payment links into your invoices
                            </p>
                            <p style="color: #234D65; font-size: 15px;">
                                <i class="fa fa-circle" style="color:#F89030; font-size: 15px;"></i> &nbsp;&nbsp;Set payment reminders for unpaid invoices
                            </p>
                            <p style="color: #234D65; font-size: 15px;">
                                <i class="fa fa-circle" style="color:#F89030; font-size: 15px;"></i> &nbsp;&nbsp;Automated invoice expiry
                            </p>
                            <p style="color: #234D65; font-size: 15px;">
                                <i class="fa fa-circle" style="color:#F89030; font-size: 15px;"></i> &nbsp;&nbsp;Auto generate PDF invoices
                            </p>
                        </div>
                    </div>
                </div>
            </div> <!-- row no-margin hidden-lg -->
            <div class="row no-margin hidden-xs">
                <div class="col-md-8">
                    <div class="col-md-12">
                        <p style="color: #234D65; font-size: 15px;"></br></br>With our billing system you can create customized invoices that suit your needs. Each invoice is generated with your company's look and feel. We have a variety of invoice templates to accomodate your different invoicing needs and statutory tax structures. Swipez billing covers B2B and B2C invoicing with a variety of online payment options. Including flat rates for B2B billing.
                        </p>
                        <br/>
                        <h2 style="color: #234D65;"><strong>Key features</strong></h2>
                        <br/>
                        <div class="col-md-5">
                            <p style="color: #234D65; font-size: 15px;">
                                <i class="fa fa-circle" style="color:#F89030; font-size: 15px;"></i> &nbsp;&nbsp;Generate individual or bulk invoices
                            </p>
                            <p style="color: #234D65; font-size: 15px;">
                                <i class="fa fa-circle" style="color:#F89030; font-size: 15px;"></i> &nbsp;&nbsp;Settle invoices paid offline centrally
                            </p>
                            <p style="color: #234D65; font-size: 15px;">
                                <i class="fa fa-circle" style="color:#F89030; font-size: 15px;"></i> &nbsp;&nbsp;Automate recurring billing
                            </p>
                            <p style="color: #234D65; font-size: 15px;">
                                <i class="fa fa-circle" style="color:#F89030; font-size: 15px;"></i> &nbsp;&nbsp;Bulk printing of invoices
                            </p>
                            <p style="color: #234D65; font-size: 15px;">
                                <i class="fa fa-circle" style="color:#F89030; font-size: 15px;"></i> &nbsp;&nbsp;Detailed reporting against invoices
                            </p>
                        </div>
                        <div class="col-md-1">
                        </div>
                        <div class="col-md-6">
                            <p style="color: #234D65; font-size: 15px;">
                                <i class="fa fa-circle" style="color:#F89030; font-size: 15px;"></i> &nbsp;&nbsp;Integrate payment links into your invoices
                            </p>
                            <p style="color: #234D65; font-size: 15px;">
                                <i class="fa fa-circle" style="color:#F89030; font-size: 15px;"></i> &nbsp;&nbsp;Set payment reminders for unpaid invoices
                            </p>
                            <p style="color: #234D65; font-size: 15px;">
                                <i class="fa fa-circle" style="color:#F89030; font-size: 15px;"></i> &nbsp;&nbsp;Automated invoice expiry
                            </p>
                            <p style="color: #234D65; font-size: 15px;">
                                <i class="fa fa-circle" style="color:#F89030; font-size: 15px;"></i> &nbsp;&nbsp;Auto generate PDF invoices
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4"  style="background-color: #f0f5f5;">
                <div class="row no-margin"  style="">

                    <div class="col-md-12">
                        <div class="row" id="form_demo">
                            <form action="" class="login-form" id="submit_form" method="post">
                                <h1 class="registertext">See Swipez in Action
                                </h1>
                                <div class="col-md-12">
                                    <div class="row ">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <input type="text" placeholder="Company name" value="<?php echo $_POST['company_name']; ?>"  name="company_name" maxlength="45" required="" class="form-control">
                                                <span class="help-block"> </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <input type="email"  placeholder="Email ID" value="<?php echo $_POST['email']; ?>" name="email" <?php echo $this->HTMLValidatorPrinter->fetch("email"); ?> required="" class="form-control">
                                                <span class="help-block"> </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <input type="text"  placeholder="Mobile No." value="<?php echo $_POST['mobile']; ?>" name="mobile" <?php echo $this->HTMLValidatorPrinter->fetch("mobile"); ?> required="" class="form-control">
                                                <span class="help-block"> </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <div id="errordiv" style="color: red;"><?php if ($this->error != '') {
        echo $this->error;
    } ?> </div>
                                                <div class="g-recaptcha" required data-sitekey="<?php echo $this->capcha_key; ?>"></div>
                                                <span class="help-block"> </span>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <div class="loading" style="display: none;" id="loader">Loading&#8230;</div>
                                                <button type="submit" class="registerbtn">Request a Demo</button>
                                                <input type="hidden" name="form_type" value="promo">
                                                <input type="hidden" name="utm_source" value="<?php echo $this->utm_source; ?>">
                                                <input type="hidden" name="utm_campaign" value="<?php echo $this->utm_campaign; ?>">
                                                <input type="hidden" name="utm_adgroup" value="<?php echo $this->utm_adgroup; ?>">
                                                <input type="hidden" name="utm_term" value="<?php echo $this->utm_term; ?>">
                                                <span class="help-block"> </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div id="demosent" style="display: none;">
                            <h3 class="page-title">Thank You </h3>
                            <div class="note note-success">
                                <h4 class="block">Demo request has been sent successfully.</h4>
                                <p>
                                    We are glad to see your interest in Swipez. Our service managers will guide you through the product during the demo call.
                                    <br>
                                    Our demo requires us to share our screen with you. Request you to kindly download Anydek for Mac or Anydesk for Windows
                                    <br>
                                    Please feel free to write to us at <a>support@swipez.in</a> for any queries or reach us at +91 741 497 3338
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div> <!-- row no-margin hidden-xs -->
        </section>
    </div>
    <!-- BEGIN CONTACT SECTION -->
    <!-- END CONTACT SECTION -->
</div>
<!-- END MAIN LAYOUT -->
<a href="#intro" class="go2top"><i class="fa fa-arrow-up"></i></a>

<div class="page-footer">
    <div class="page-footer-inner">
        Powered by Swipez &copy; <?php echo $this->current_year; ?> OPUS Net Pvt. Handmade in Pune.
    </div>
</div>
<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN CORE PLUGINS -->
<!--[if lt IE 9]>
<script src="/assets/global/plugins/respond.min.js"></script>
<script src="/assets/global/plugins/excanvas.min.js"></script> 
<![endif]-->


<script src="/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
<script src="/assets/global/plugins/jquery-migrate.min.js" type="text/javascript"></script>
<script src="/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="/assets/global/plugins/jquery.easing.js" type="text/javascript"></script>
<script src="/assets/global/plugins/jquery.parallax.js" type="text/javascript"></script>
<script src="/assets/global/plugins/smooth-scroll/smooth-scroll.js" type="text/javascript"></script>
<script src="/assets/global/plugins/owl.carousel/owl.carousel.min.js" type="text/javascript"></script>
<!-- BEGIN Cubeportfolio -->
<script src="/assets/global/plugins/cubeportfolio/cubeportfolio/js/jquery.cubeportfolio.min.js" type="text/javascript"></script>
<script src="/assets/frontend/onepage2/scripts/portfolio.js" type="text/javascript"></script>
<!-- END Cubeportfolio -->
<!-- BEGIN RevolutionSlider -->  
<script src="/assets/global/plugins/slider-revolution-slider/rs-plugin/js/jquery.themepunch.revolution.min.js" type="text/javascript"></script> 
<script src="/assets/global/plugins/slider-revolution-slider/rs-plugin/js/jquery.themepunch.tools.min.js" type="text/javascript"></script>
<script src="/assets/admin/layout/scripts/colorbox.js"></script>

<!-- END RevolutionSlider -->
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="/assets/frontend/onepage2/scripts/layout.js" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS -->
<script>
    var RevosliderInit = function () {
        $(".iframe").colorbox({iframe: true, width: "80%", height: "90%"});

        return {
            initRevoSlider: function () {
                var height = 580; // minimal height for medium resolution

                // smart height detection for all major screens
                if (Layout.getViewPort().width > 1600) {
                    height = $(window).height() - $('.subscribe').outerHeight();  // full height for high resolution
                } else if (Layout.getViewPort().height > height) {
                    height = Layout.getViewPort( ).height;
                }
                height = height - 109;
                jQuery('.banner').revolution({
                    delay: 1000,
                    startwidth: 1170,
                    startheight: height,
                    navigationArrows: "none",
                    fullWidth: "on",
                    fullScreen: "off",
                    touchenabled: "on", // Enable Swipe Function : on/off
                    onHoverStop: "on", // Stop Banner Timet at Hover on Slide on/off
                    fullScreenOffsetContainer: ""
                });
            }
        };

    }();
    jQuery(document).ready(function () {
        Layout.init();
        RevosliderInit.initRevoSlider();
    });
</script>
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>