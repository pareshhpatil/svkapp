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
        <link href="/assets/frontend/onepage2/css/custom.css<?php echo $this->fileTime('aa', '/assets/frontend/onepage2/css/custom.css'); ?>" rel="stylesheet" type="text/css"/>
        <link href="/assets/admin/layout/css/colorbox.css" rel="stylesheet" type="text/css"/>
        <link href="/assets/frontend/onepage2/css/promoregister.css<?php echo $this->fileTime('aa', '/assets/frontend/onepage2/css/promoregister.css'); ?>" rel="stylesheet" type="text/css"/>
        <script src='https://www.google.com/recaptcha/api.js'></script>
        <script src='/assets/admin/layout/scripts/register.js'></script>

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
        <header class="page-header visible-xs" style="height: 80px;">
            <nav class="" role="navigation">
                <div class="container" style="height: 10px;">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header page-scroll" style="background-color: #234d65;line-height: -10px;">

                        <a class="navbar-brand" href="/" style="width: 100%;text-align: center;">
                            <img class="logo-default" src="/assets/frontend/onepage2/img/logo.png" alt="Logo">
                            <img class="logo-scroll" src="/assets/frontend/onepage2/img/logo.png" alt="Logo">
                        </a>
                    </div>
                </div>
                <!--/container-->
            </nav>
        </header><!-- Header END -->
        <section id="intro" >
            <!-- Slider BEGIN -->
            <div class="row no-margin">
                <div class="col-md-8 hidden-xs" style="background-color: #234d65;height:410px;">
                    <div class="container">
                        <br>
                        <div class="row">
                            <div class="col-md-5 md-margin-bottom-70" style="padding: 60px;">
                                <img class="pull-right" src="/assets/frontend/onepage2/img/member/spotlight_1.png" style="width: 85%;" alt="Bulk invoicing with auto-generated invoice numbers">
                            </div>
                            <div class="col-md-7 md-margin-bottom-70 margin-bottom-80" style="color: #97A4B4; max-width: 100%;">
                                <br><br><br><br> 
                                <h1 class="bannertext" style="max-width: 100%;margin-left: -10px;font-size: 36px;">
                                    Billing & invoicing <br>software that manages<br>all your collection needs
                                </h1>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-md-4"  style="background-color: #f0f5f5;height: 410px;">
                    <div class="row no-margin"  style="">

                        <div class="col-md-12">

                            <div class="row" id="form_demo">
                                <form action="/login/resetpassword/<?php echo $this->link; ?>" class="login-form" id="submit_form" method="post">
                                    <h1 class="registertext">Set your password
                                    </h1>
                                    <div class="col-md-12">
                                        <?php
                                        if ($this->hasError()) {
                                            ?>
                                            <div class="alert alert-danger alert-dismissable" id="login_error">
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
                                        <div class="row ">
                                            <div class="form-group">
                                                <div class="col-md-12">
                                                    <label  class="control-label"><h4>Login user name: <?php echo $this->user_name; ?></h4></label>
                                                    <span class="help-block"> </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row ">
                                            <div class="form-group">
                                                <div class="col-md-12">
                                                    <input name="password" placeholder="Enter your password" required id="password" type="password" AUTOCOMPLETE="OFF" class="form-control">
                                                    <span class="help-block"> </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group">
                                                <div class="col-md-12">
                                                    <input name="verifypassword" placeholder="Re enter password" required type="password" AUTOCOMPLETE='OFF' class="form-control">
                                                    <span class="help-block"> </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group">
                                                <div class="col-md-12">
                                                    <div class="loading" style="display: none;" id="loader">Loading&#8230;</div>
                                                    <button type="submit" class="registerbtn">Set your password</button>
                                                    <input type="hidden" name="email" value="<?php echo $this->email; ?>"/> 
                                                    <input type="hidden" name="service_id" value="<?php echo $this->service_id; ?>"/> 
                                                    <input type="hidden" name="type" value="<?php echo $this->type; ?>"/> 
                                                    <input type="hidden" name="verified" value="1"/> 
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
            </div>
            <!-- Slider END -->
        </section>
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
            <div class="features-bg bg-white">
                <div class="row"></div>
                <div class="container">
                    <div class="heading">
                        <h2><strong>Settle invoices paid offline centrally</strong></h2>
                    </div>
                    <div class="col-md-12 md-margin-bottom-70 margin-bottom-80 info-text">
                        Not all customers pay online. It is difficult to manage
                        two ledgers to track thee payments. Swipez allows you
                        to settle invoice paid either offline or online via a
                        central dashboard.
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
            <div class="features-bg bg-white" >
                <div class="row"></div>
                <div class="container">
                    <div class="heading">
                        <h2><strong>Integrate payment links into your invoices</strong></h2>
                    </div>
                    <div class="col-md-12 md-margin-bottom-70 margin-bottom-80 info-text" >
                        You can payment enable your invoices. This means your
                        customers can pay the invoice online by using any
                        financial instrument such as credit cards, debit card, net
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
        <!-- BEGIN INTRO SECTION -->

        <!-- END INTRO SECTION -->

        <!-- BEGIN MAIN LAYOUT -->

        <!-- BEGIN CONTACT SECTION -->
        <!-- END CONTACT SECTION -->
    </div>
    <!-- END MAIN LAYOUT -->
    <a href="#intro" class="go2top"><i class="fa fa-arrow-up"></i></a>
    <br>
    <br>
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
        }();
        jQuery(document).ready(function () {

            $(".tryfree").click(function () {
                $("#company_name").focus();
            });
            document.getElementById("company_name").focus();
        });
    </script>
    <!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>