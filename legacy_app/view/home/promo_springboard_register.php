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

        <!-- END THEME STYLES -->

        <link rel="shortcut icon" href="/favicon.ico"/>
        
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
                        <img class="logo-default" src="/assets/frontend/onepage2/img/logo.png" a alt="Swipez Online Payment" title="Swipez Online Payment Solutions">
                        <img class="logo-scroll" src="/assets/frontend/onepage2/img/logo.png"  alt="Swipez Online Payment" title="Swipez Online Payment Solutions">
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
            <div class="col-md-8 hidden-xs" style="background-color: #234d65;height: 470px;">
                <div class="container">
                    <div class="">
                        <h2><strong>&nbsp;</strong></h2>
                    </div><!-- //end heading -->
                    <div class="row">
                        <div class="col-md-5 md-margin-bottom-70" style="padding: 50px;">
                            <img class="pull-right" src="/assets/frontend/onepage2/img/member/spotlight_1.png" style="width: 85%;" alt="Bulk invoicing with auto-generated invoice numbers">
                        </div>
                        <div class="col-md-7 md-margin-bottom-70 margin-bottom-80" style="color: #97A4B4; max-width: 100%;">
                            <br><br><br><br> <h1 class="bannertext" style="max-width: 100%;">
                                The easiest way to<br> manage billing &<br> collect payments.


                            </h1>
                        </div>
                    </div>

                </div>
            </div>
            <div class="col-md-4"  style="background-color: #f0f5f5;height: 470px;">
                <div class="row no-margin"  style="margin-top: -10px;">

                    <div class="col-md-12">
                        <div class="row">
                            <form  action="/merchant/register/saved" class="login-form" id="submit_form" method="post">
                                <h1 class="registertext">See Swipez in Action
                                </h1>
                                <div class="col-md-12">
                                    <div class="row ">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <input type="text" value=""  placeholder="Company name" name="company_name" maxlength="45" required="" class="form-control">
                                                <span class="help-block"> </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <input type="email" value=""  placeholder="Email ID" name="email" <?php echo $this->HTMLValidatorPrinter->fetch("email"); ?> required="" class="form-control">
                                                <span class="help-block"> </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <input type="text" value=""  placeholder="Mobile No." name="mobile" <?php echo $this->HTMLValidatorPrinter->fetch("mobile"); ?> required="" class="form-control">
                                                <span class="help-block"> </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <input type="password" value=""  placeholder="Password" name="password" <?php echo $this->HTMLValidatorPrinter->fetch("password"); ?> required="" class="form-control">
                                                <span class="help-block"> </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <div class="g-recaptcha" required data-sitekey="<?php echo $this->capcha_key; ?>"></div>
                                                <span class="help-block"> </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <label class="mt-checkbox mt-checkbox-outline" style="font-size: 13px;">
                                                    <input required="" type="checkbox" name="confirm" /> I agree to the
                                                    <a href="/terms-popup" class="iframe cboxElement"> Terms and Service</a> &
                                                    <a href="/privacy-popup" class="iframe cboxElement">Privacy Policy</a>
                                                    <span></span>
                                                </label>
                                                <span class="help-block"> </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <button type="submit" class="registerbtn">Register</button>
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
                    </div>
                </div>
            </div>
        </div>
        <!-- Slider END -->
    </section>


    <!-- BEGIN INTRO SECTION -->

    <!-- END INTRO SECTION -->

    <!-- BEGIN MAIN LAYOUT -->
    <section id="invoices">
        <div class="features-bg">
            <div class="row"></div>
            <div class="container">
                <div class="heading">
                    <h2><strong>Generate individual or bulk invoices</strong></h2>
                </div>
                <div class="col-md-12 md-margin-bottom-70 margin-bottom-80 info-text" >
                    With Swipez, you can create invoices for
                    individual customers or generate them in bulk.
                    Just upload billing data into Swipez along with
                    the customers Email Id and mobile number.
                    Swipez will generate the invoices and send it to
                    your entire customer base. 
                    Swipez also allows you to create custom
                    auto-generated invoice numbers as per your
                    business requirement. For ex. ST/MUM/FEB/1124
                    the number portion of this invoice number is
                    auto-incremented by Swipez.
                </div>
                <center>
                    <a href="#intro" class="btn-danger btn-md tryfree" style="font-size: 14px;">TRY IT FOR FREE</a>
                </center>
            </div>
        </div>
    </section>
    <section id="invoices">
        <div class="features-bg bg-white" >
            <div class="row"></div>
            <div class="container">
                <div class="heading">
                    <h2><strong>Integrate payment links into your invoices</strong></h2>
                </div>
                <div class="col-md-12 md-margin-bottom-70 margin-bottom-80 info-text" >
                    You can payment enable your invoices. This means your
                    customers can pay the invoice online by using any
                    financial instrument such as UPI, credit cards, debit card, net
                    banking, wallets or other payment instruments.
                    Payments made by your customers will be credited
                    directly to the bank account of your choice.
                </div>
                <center>
                    <a href="#intro" class="btn-danger btn-md tryfree" style="font-size: 14px;">TRY IT FOR FREE</a>
                </center>
            </div>
        </div>
    </section>
    <section id="invoices">
        <div class="features-bg">
            <div class="row"></div>
            <div class="container">
                <div class="heading">
                    <h2><strong>Set payment reminders for unpaid invoices</strong></h2>
                </div>
                <div class="col-md-12 md-margin-bottom-70 margin-bottom-80 info-text" >
                    At times due customers might forget to 
                    pay you on-time. With Swipez you can set automated payment reminders
                    against unpaid invoices at preset frequencies. Only
                    customers who haven't paid their dues will receive a 
                    reminder via Email and SMS.
                </div>
                <center>
                    <a href="#intro" class="btn-danger btn-md tryfree" style="font-size: 14px;">TRY IT FOR FREE</a>
                </center>
            </div>
        </div>
    </section>
    <section id="invoices">
        <div class="features-bg bg-white">
            <div class="row"></div>
            <div class="container">
                <div class="heading">
                    <h2><strong>Automate recurring invoices</strong></h2>
                </div>
                <div class="col-md-12 md-margin-bottom-70 margin-bottom-80 info-text" >
                    Swipez can send out invoices automatically on the
                    assigned date without any manual intervention from your end.
                    Similar to managing a subscription. Recurring invoicing also has
                    the feature to carry forward unpaid dues with late payment fees.
                </div>
                <center>
                    <a href="#intro" class="btn-danger btn-md tryfree" style="font-size: 14px;">TRY IT FOR FREE</a>
                </center>
            </div>
        </div>
    </section>
    <section id="invoices">
        <div class="features-bg">
            <div class="row"></div>
            <div class="container">
                <div class="heading">
                    <h2><strong>Bulk payments & disbursements</strong></h2>
                </div>
                <div class="col-md-12 md-margin-bottom-70 margin-bottom-80 info-text">
                Automate bulk payments and disbursements to your business contacts with ease. Simply upload an excel sheet or automate your payouts via our APIs. You could even automate payouts by setting rules for splitting payments between your vendors and franchises.
                </div>
                <center>
                    <a href="#intro" class="btn-danger btn-md" style="font-size: 14px;">TRY IT FOR FREE</a>
                </center>
            </div>
        </div>
    </section>
    <section id="invoices">
        <div class="features-bg bg-white">
            <div class="row"></div>
            <div class="container">
                <div class="heading">
                    <h2><strong>GST filing</strong></h2>
                </div>
                <div class="col-md-12 md-margin-bottom-70 margin-bottom-80 info-text">
                Automate your GST submissions from within your Swipez dashboard. All invoices generated within or outside of Swipez can be simply submitted to the GST portal at the click of a button. Keep on top of your GST submissions and stay compliant by using our GST submission facility.
                </div>
                <center>
                    <a href="#intro" class="btn-danger btn-md" style="font-size: 14px;">TRY IT FOR FREE</a>
                </center>
            </div>
        </div>
    </section>

    <section id="invoices">
        <div class="features-bg">
            <div class="row"></div>
            <div class="container">
                <div class="heading">
                    <h2><strong>Customer data management</strong></h2>
                </div>
                <div class="col-md-12 md-margin-bottom-70 margin-bottom-80 info-text">
                Keep your customer data up to date and accurate at all times. Swipez automatically organizes your customer data using our propitiatory algorithms. These algorithms automatically correct your customers email ids, mobile number and addresses as you & your customers use the system.
                </div>
                <center>
                    <a href="#intro" class="btn-danger btn-md" style="font-size: 14px;">TRY IT FOR FREE</a>
                </center>
            </div>
        </div>
    </section>
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