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
        <link href="/assets/frontend/onepage2/css/custom.css<?php echo $this->fileTime('custom', 'assets/frontend/onepage2/css/custom.css'); ?>" rel="stylesheet" type="text/css"/>
        <link href="/assets/admin/layout/css/colorbox.css" rel="stylesheet" type="text/css"/>
        <link href="/assets/frontend/onepage2/css/promoregister.css" rel="stylesheet" type="text/css"/>
        <script src='https://www.google.com/recaptcha/api.js'></script>
        <script src='/assets/admin/layout/scripts/register.js<?php echo $this->fileTime('custom', 'assets/admin/layout/scripts/register.js'); ?>'></script>

        <!-- END THEME STYLES -->




        <link rel="shortcut icon" href="/images/briq.ico"/>
        <?php if ($this->env == 'PROD') { ?>
            <!-- Google Code for Remarketing Tag -->
            <!--------------------------------------------------
            Remarketing tags may not be associated with personally identifiable information or placed on pages related to sensitive categories. See more information and instructions on how to setup the tag on: http://google.com/ads/remarketingsetup
            --------------------------------------------------->
            <script type="text/javascript">
                /* <![CDATA[ */
                var google_conversion_id = 817807754;
                var google_custom_params = window.google_tag_params;
                var google_remarketing_only = true;
                /* ]]> */
            </script>
            <script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
            </script>
            <noscript>
        <div style="display:inline;">
            <img height="1" width="1" style="border-style:none;" alt="" src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/817807754/?guid=ON&amp;script=0"/>
        </div>
        </noscript>
        
        <!-- Facebook Pixel Code -->
        <script>
            !function (f, b, e, v, n, t, s)
            {
                if (f.fbq)
                    return;
                n = f.fbq = function () {
                    n.callMethod ?
                            n.callMethod.apply(n, arguments) : n.queue.push(arguments)
                };
                if (!f._fbq)
                    f._fbq = n;
                n.push = n;
                n.loaded = !0;
                n.version = '2.0';
                n.queue = [];
                t = b.createElement(e);
                t.async = !0;
                t.src = v;
                s = b.getElementsByTagName(e)[0];
                s.parentNode.insertBefore(t, s)
            }(window, document, 'script',
                    'https://connect.facebook.net/en_US/fbevents.js');
            fbq('init', '489330198131207');
            fbq('track', 'PageView');
        </script>
        <noscript><img height="1" width="1" style="display:none"
                       src="https://www.facebook.com/tr?id=489330198131207&ev=PageView&noscript=1"
                       /></noscript>
        <!-- End Facebook Pixel Code -->

    <?php } ?>
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
            <div class="col-md-8 hidden-xs" style="background-color: #234d65;height:410px;">
                <div class="container">
                    <br>
                    <div class="row">
                        <div class="col-md-5 md-margin-bottom-70" style="padding: 60px;">
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
            <div class="col-md-4"  style="background-color: #f0f5f5;height: 410px;">
                <div class="row no-margin"  style="">

                    <div class="col-md-12">
                        <?php if ($this->success != 1) { ?>
                            <div class="row" id="form_demo">
                                <br>
                                <form action="" class="login-form" id="submit_form" method="post">
                                    
                                    <?php if ($this->error_type == 2) { ?>
                                        <div class="note note-danger">
                                            <h4 class="block">License key invalid. </h4>
                                            <p>In case of queries please reach out to support@swipez.in</p>
                                        </div>
                                    <?php }else{ echo '<br>'; } ?>
                                    <h1 class="registertext">Enter Serial Key
                                    </h1>
                                    <div class="col-md-12">
                                        <div class="row ">
                                            <div class="form-group">
                                                <div class="col-md-3">
                                                    <input type="number" placeholder="1234"  id="key1" value="<?php echo $_POST['key1']; ?>" title="Please enter valid key." name="key1" max="9999" required="" onkeydown="return validatemax(event, 1);" onkeyup="keyChange(event,1);" class="form-control no-spinners">
                                                    <span class="help-block"> </span>
                                                </div>
                                                <div class="col-md-3">
                                                    <input type="number" placeholder="1234" id="key2" value="<?php echo $_POST['key2']; ?>" title="Please enter valid key." min="1000" name="key2" max="9999" required="" onkeydown="return validatemax(event, 2);" onkeyup="keyChange(event,2);" class="form-control no-spinners">
                                                    <span class="help-block"> </span>
                                                </div>
                                                <div class="col-md-3">
                                                    <input type="number" placeholder="1234" id="key3" value="<?php echo $_POST['key3']; ?>" title="Please enter valid key." min="1000" name="key3" max="9999" required="" onkeydown="return validatemax(event, 3);" onkeyup="keyChange(event,3);" class="form-control no-spinners">
                                                    <span class="help-block"> </span>
                                                </div>
                                                <div class="col-md-3">
                                                    <input type="number" placeholder="1234" id="key4" value="<?php echo $_POST['key4']; ?>" title="Please enter valid key." min="1000" name="key4" max="9999" required="" onkeydown="return validatemax(event, 4);" onkeyup="keyChange(event,4);" class="form-control no-spinners">
                                                    <span class="help-block"> </span>
                                                </div>
                                            </div>
                                            <br>
                                        </div>
                                        <div class="row">
                                            <div class="form-group">
                                                <?php if ($this->error_type != 2) { echo '<br>'; } ?>
                                                <div class="col-md-12" style="text-align: -webkit-center;">
                                                    <div id="errordiv" style="color: red;"><?php
                                                        if ($this->error_type == 1) {
                                                            echo $this->error;
                                                        }
                                                        ?> 
                                                    </div>
                                                    <div class="g-recaptcha" id="key5" required data-sitekey="<?php echo $this->capcha_key; ?>"></div>
                                                    <span class="help-block"> </span>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <?php if ($this->error_type != 2) { echo '<br><br>'; } ?>
                                            
                                            <div class="form-group">
                                                <div class="col-md-12">
                                                    <div class="loading" style="display: none;" id="loader">Loading&#8230;</div>
                                                    <button type="submit" class="registerbtn">Validate License</button>
                                                    <span class="help-block"> </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        <?php }else{ ?>
                        <div class="row">
                            <div class="col-md-12">
                                <br>
                                <br>
                            <h3 class="page-title">Congratulations! Serial key verified.</h3>
                            <br>
                            <div class="note note-success">
                                <h4 class="block">Please complete your registration to apply the purchased license to your profile.</h4>
                                <p>
                                    
                                </p>
                            </div>
                            <br>
                            <div class="col-md-12 center middle" style="text-align: center;" ><a href="/merchant/register" class="btn blue">Complete Registration</a></div>
                            
                        </div>
                        </div>
                        <?php } ?>
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
            <div class="row no-margin hidden-lg">
                <div class="col-xs-3"></div>
                <div class="col-xs-6">
                    <div class="col-md-2" style="padding: 30px 10px 10px 20px;" >
                        <img src="/assets/frontend/onepage2/img/feature/page-1.png">
                        <h1 class="featuretext">Automated<br>
                            Invoicing</h1>
                        <div style="min-width: 300px;margin-left: -20px;">
                            <li >
                                <span class="featureli">Beautiful GST friendly invoices.</span>
                            </li>
                            <li>
                                <span class="featureli">Individual or bulk invoices</span>
                            </li>
                            <li>
                                <span class="featureli">Automate subscription billing</span>
                            </li>
                        </div>
                    </div>
                    <div class="col-md-2" style="padding: 30px 10px 10px 20px;" >
                        <img src="/assets/frontend/onepage2/img/feature/page-2.png">
                        <h1 class="featuretext">Collections & <br>
                            Reporting</h1>
                        <div style="min-width: 300px;margin-left: -20px;">
                            <li>
                                <span class="featureli">Single ledger reporting</span>
                            </li>
                            <li>
                                <span class="featureli">Auto debit functionality</span>
                            </li>
                            <li>
                                <span class="featureli">Multi party payment settlement</span>
                            </li>
                        </div>
                    </div>
                    <div class="col-md-2" style="padding: 30px 10px 10px 20px;" >
                        <img src="/assets/frontend/onepage2/img/feature/group.png">
                        <h1 class="featuretext">Manage <br>
                            Spaces</h1>
                        <div style="min-width: 300px;margin-left: -20px;">
                            <li>
                                <span class="featureli">Create and publish your events</span>
                            </li>
                            <li>
                                <span class="featureli">Bookings & cancellations</span>
                            </li>
                            <li>
                                <span class="featureli">Collect advance payments</span>
                            </li>

                        </div>
                    </div>
                    <div class="col-md-2" style="padding: 30px 10px 10px 20px;" >
                        <img src="/assets/frontend/onepage2/img/feature/page-3.png">
                        <h1 class="featuretext" style="min-width: 200px;">CRM & Promotion <br>
                            Tools</h1>
                        <div style="min-width: 300px;margin-left: -20px;">
                            <li>
                                <span class="featureli">SMS Gateway</span>
                            </li>
                            <li>
                                <span class="featureli">Branded Keywords</span>
                            </li>
                            <li>
                                <span class="featureli">Couponing Engine</span>
                            </li>
                        </div>
                    </div>
                    <div class="col-md-2"  style="padding: 30px 10px 10px 20px;">
                        <img src="/assets/frontend/onepage2/img/feature/group-2.png">
                        <h1 class="featuretext">Customer <br> Convenience</h1>
                        <div style="min-width: 300px;margin-left: -20px;">
                            <li>
                                <span class="featureli">Multiple payment options</span>
                            </li>
                            <li>
                                <span class="featureli">Online customer support</span>
                            </li>
                            <li>
                                <span class="featureli">Consolidated payment history</span>
                            </li>
                        </div>
                    </div>
                    <div class="col-md-2" style="padding: 30px 10px 10px 20px;" >
                        <img src="/assets/frontend/onepage2/img/feature/group-3.png">
                        <h1 class="featuretext">Data<br>
                            Security</h1>
                        <div style="min-width: 300px;margin-left: -20px;">
                            <li>
                                <span class="featureli">Gold standard data security</span>
                            </li>
                            <li>
                                <span class="featureli">Role based access</span>
                            </li>
                            <li>
                                <span class="featureli">PCI-DSS compliant & certified</span>
                            </li>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row no-margin hidden-xs">
                <div class="col-md-2" style="padding: 30px 10px 10px 20px;" >
                    <img src="/assets/frontend/onepage2/img/feature/page-1.png">
                    <h1 class="featuretext">Automated<br>
                        Invoicing</h1>
                    <li>
                        <span class="featureli">Beautiful GST friendly invoices.</span>
                    </li>
                    <li>
                        <span class="featureli">Individual or bulk invoices</span>
                    </li>
                    <li>
                        <span class="featureli">Automate subscription billing</span>
                    </li>
                </div>
                <div class="col-md-2" style="padding: 30px 10px 10px 20px;" >
                    <img src="/assets/frontend/onepage2/img/feature/page-2.png">
                    <h1 class="featuretext">Collections & <br>
                        Reporting</h1>
                    <li>
                        <span class="featureli">Single ledger reporting</span>
                    </li>
                    <li>
                        <span class="featureli">Auto debit functionality</span>
                    </li>
                    <li>
                        <span class="featureli">Multi party payment settlement</span>
                    </li>
                </div>
                <div class="col-md-2" style="padding: 30px 10px 10px 20px;" >
                    <img src="/assets/frontend/onepage2/img/feature/group.png">
                    <h1 class="featuretext">Manage <br>
                        Spaces</h1>
                    <li>
                        <span class="featureli">Create and publish your events</span>
                    </li>
                    <li>
                        <span class="featureli">Bookings & cancellations</span>
                    </li>
                    <li>
                        <span class="featureli">Collect advance payments</span>
                    </li>
                </div>
                <div class="col-md-2" style="padding: 30px 10px 10px 20px;" >
                    <img src="/assets/frontend/onepage2/img/feature/page-3.png">
                    <h1 class="featuretext">CRM & Promotion <br>
                        Tools</h1>
                    <li>
                        <span class="featureli">SMS Gateway</span>
                    </li>
                    <li>
                        <span class="featureli">Branded Keywords</span>
                    </li>
                    <li>
                        <span class="featureli">Couponing Engine</span>
                    </li>
                </div>
                <div class="col-md-2"  style="padding: 30px 10px 10px 20px;">
                    <img src="/assets/frontend/onepage2/img/feature/group-2.png">
                    <h1 class="featuretext">Customer <br> Convenience</h1>
                    <li>
                        <span class="featureli">Multiple payment options</span>
                    </li>
                    <li>
                        <span class="featureli">Online customer support</span>
                    </li>
                    <li>
                        <span class="featureli">Consolidated payment history</span>
                    </li>
                </div>
                <div class="col-md-2" style="padding: 30px 10px 10px 20px;" >
                    <img src="/assets/frontend/onepage2/img/feature/group-3.png">
                    <h1 class="featuretext">Data<br>
                        Security</h1>
                    <li>
                        <span class="featureli">Gold standard data security</span>
                    </li>
                    <li>
                        <span class="featureli">Role based access</span>
                    </li>
                    <li>
                        <span class="featureli">PCI-DSS compliant & certified</span>
                    </li>
                </div>
            </div>
        </section>
    </div>
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