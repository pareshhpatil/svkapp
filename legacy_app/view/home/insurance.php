<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-js">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->
    <head>
        <meta charset="utf-8"/>
        <title>Ready to use Bill presentment| Invoice templates| Online Payments</title>
        <meta name="description" content="Create custom invoices or choose from our wide range of billing templates within Swipez">
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
        <link href="/assets/global/plugins/owl.carousel/assets/owl.carousel.css" rel="stylesheet">
        <link href="/assets/global/plugins/slider-revolution-slider/rs-plugin/css/settings.css" rel="stylesheet">
        <link href="/assets/global/plugins/cubeportfolio/cubeportfolio/css/cubeportfolio.min.css" rel="stylesheet">
        <!-- END PAGE LEVEL PLUGIN STYLES -->
        <!-- BEGIN THEME STYLES -->
        <link href="/assets/frontend/onepage2/css/layout.css" rel="stylesheet" type="text/css"/>
        <link href="/assets/frontend/onepage2/css/custom.css" rel="stylesheet" type="text/css"/>
        <link href="/assets/admin/layout/css/colorbox.css" rel="stylesheet" type="text/css"/>
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
        <?php } ?>
        
        <!-- END THEME STYLES -->
        <link rel="shortcut icon" href="images/briq.ico"/>

        <?php
        if ($this->env == 'PROD') {
            if ($this->usertype != 'merchant') {
        include_once("/inc/gatracking.php");
        }
            $m_url = "https://m.swipez.in";
        } else {
            $m_url = "https://mh7sak8am43.swipez.in";
        }
        ?>
        <script type="text/javascript">
            if (screen.width <= 600) {
                window.location = "<?php echo $m_url; ?>";
            }
        </script>
    </head>
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<!-- DOC: Apply "page-on-scroll" class to the body element to set fixed header layout -->
<body class="page-header-fixed">

    <!-- BEGIN MAIN LAYOUT -->
    <!-- Header BEGIN -->
    <header class="page-header">
        <nav class="navbar navbar-fixed-top" role="navigation">
            <div class="container">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header page-scroll">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="toggle-icon">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </span>
                    </button>
                    <div class="logo" itemscope="" itemtype="http://schema.org/Organization">
                    <a itemprop="url" class="navbar-brand" title="Swipez" href="<?php echo $this->server_name; ?>">                                                               
                        <img class="logo-default" src="/assets/frontend/onepage2/img/logo_default.png" alt="Swipez Online Payment" title="Swipez Online Payment Solutions">
                    <img class="logo-scroll" src="/assets/frontend/onepage2/img/logo_scroll.png" alt="Swipez Online Payment" title="Swipez Online Payment Solutions">
                    </a>
                    </div>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse navbar-responsive-collapse">
                    
                </div>
                <!-- End Navbar Collapse -->
            </div>
            <!--/container-->
        </nav>
    </header>
    <!-- Header END -->

    <!-- BEGIN INTRO SECTION -->
    <section id="intro">
        <!-- Slider BEGIN -->
        <div class="page-slider">
            <div class="fullwidthbanner-container revolution-slider">
                <div class="banner">
                    <ul id="revolutionul">
                        <!-- THE NEW SLIDE -->
                        <li data-transition="fade" data-slotamount="8" data-masterspeed="700" data-delay="9400" data-thumb="">
                            <!-- THE MAIN IMAGE IN THE FIRST SLIDE -->
                            <img src="/assets/frontend/onepage2/img/bg/bg_slider1.jpg" alt="" width="451" height="437" >

                            <div class="caption lft tp-resizeme"
                                 data-x="center"
                                 data-y="center"
                                 data-hoffset="-300" 
                                 data-voffset="20"
                                 data-speed="900"
                                 data-start="1000"
                                 data-easing="easeOutExpo">
                                <h3 class="subtitle-v2">CORPORATE INSURANCE <br>MADE EASY </h3>
                            </div>
                            <div class="caption lfb tp-resizeme"
                                 data-x="right"
                                 data-y="center" 
                                 data-hoffset="-100"
                                 data-voffset="0"
                                 data-speed="900" 
                                 data-start="1500"
                                 data-easing="easeOutExpo">
                                <img src="/assets/frontend/onepage2/img/member/spotlight_1.png" style="width: 95%;" alt="Image 3">
                            </div>
                        </li>
                        <!-- THE NEW SLIDE -->
                    </ul>
                </div>
            </div>
        </div>
        <!-- Slider END -->
    </section>
    <!-- END INTRO SECTION -->

    <!-- BEGIN MAIN LAYOUT -->
    <div class="page-content">
        <!-- SUBSCRIBE BEGIN -->
        <div class="subscribe" style="padding: 25px;">
            <div class="container">
                <div class="subscribe-wrap">
                    <div class="subscribe-body subscribe-desc md-margin-bottom-30">
                        <h1>Signup for Swipez now!</h1>
                    </div>
                    <div class="subscribe-body">
                        <div class="form-wrap-group">
                            <a href="https://www.swipez.in/merchant/register" class="btn-danger btn-md btn-block">Sign Up</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- SUBSCRIBE END -->



        <!-- BEGIN FEATURES SECTION -->
        <section id="features">
            <!-- Products BEGIN -->
            <div class="features-bg">
                <div class="container">
                    <div class="heading">
                        <h2><strong>Products</strong></h2>
                    </div><!-- //end heading -->

                    <div class="shop-index-carousel">
                        <div class="content-slider">
                            <div id="myCarousel" class="carousel slide" data-ride="carousel">
                                <!-- Indicators -->
                                <ol class="carousel-indicators carsoul">
                                    <li data-target="#myCarousel" data-slide-to="0" class="bg-black active"></li>
                                </ol>
                                <div class="carousel-inner">
                                    <div class="item active">
                                        <div class="row margin-bottom-70">
                                            <div class="col-md-6 md-margin-bottom-70">
                                                <div class="features">
                                                    <img src="assets/frontend/onepage2/img/widgets/screen1.png" alt="" width="501" height="277">
                                                    <div class="features-in">
                                                        <h3>Single window view for Insurance firm, Employer & Employee</h3>
                                                        <p>Top up policies are managed centrally and accessible to all parties without the hassle of email exchanges across thousands of employees and the HR team. Employees can now modify their policies as well as make TOP UP payments online. They donâ€™t need to send confirmation screenshots to the employer by Email. They also have the option of paying for their TOP UP via credit cards or other instruments.</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="features">
                                                    <img src="assets/frontend/onepage2/img/widgets/screen2.png" alt="" width="501" height="277">
                                                    <div class="features-in">
                                                        <h3>Control to activate a policy against threshold</h3>
                                                        <p>Employer & Insurer can jointly activate TOP UP policies when necessary minimum thresholds are met. The entire voting process for employees is managed centrally and online in an automated manner, simplifying the entire experience for all parties</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><!-- //end row -->
                                        <div class="row margin-bottom-80">
                                            <div class="col-md-6 md-margin-bottom-70">
                                                <div class="features">
                                                    <img src="assets/frontend/onepage2/img/widgets/screen3.png" alt="" width="501" height="277">
                                                    <div class="features-in">
                                                        <h3>Centralized automated reconciliation</h3>
                                                        <p>Employer &amp; employee payouts towards the policies are visible on a central dashboard making accounting and payouts streamlined with minimum manual intervention. When an employee leaves the organization, the Insurer is notified on the dashboard, so the policy can be cancelled accordingly.</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="features">
                                                    <img src="assets/frontend/onepage2/img/widgets/screen4.png" alt="" width="501" height="277">
                                                    <div class="features-in">
                                                        <h3>Automated Renewal Alerts</h3>
                                                        <p>Renewals are managed centrally. Renewal alerts are sent out only to employees who continue to be with the organization. When an employee, leaves the system the policies for cancellation</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><!-- //end row -->
                                    </div>
                                    
                                    
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- Products END -->
        </section>



    </div>
</div>


<!-- BEGIN PRICING SECTION -->

<!-- END PRICING SECTION -->

<!-- BEGIN CLIENTS SECTION -->

<!-- END CLIENTS SECTION -->


<!-- BEGIN CONTACT SECTION -->
<section id="contact">
    <!-- Footer -->

    <!-- End Footer -->

    <!-- Footer Coypright -->
    <div class="footer-copyright">
        <div class="container">
            <div class="row">
                <!-- BEGIN BOTTOM ABOUT BLOCK -->
                <div class="col-md-12 col-sm-6 pre-footer-col" style="color: white;">
                    <a href="<?php echo $this->server_name; ?>/privacy" class="color-white">Privacy Policy</a> | <a href="<?php echo $this->server_name; ?>/terms" class="color-white">Terms & Conditions</a> |  <a href="<?php echo $this->server_name; ?>/aboutus" class="color-white">About Us</a> | <a href="<?php echo $this->server_name; ?>/contactus" class="color-white">Contact Us</a> | <a href="<?php echo $this->server_name; ?>/work-from-home" class="color-white">Work From Home</a> 

                    <ul class="copyright-socials" style="margin-bottom: 20px;margin-top: 20px;">
                        <li>
                            <span itemscope itemtype="http://schema.org/Organization">
                                <link itemprop="url" href="https://www.swipez.in">
                                <a itemprop="sameAs" target="_BLANK" href="https://www.facebook.com/Swipez-347240818812009/" data-original-title="Facebook" title="Swipez Facebook Page"><i class="fa fa-facebook"></i>
                                </a>
                            </span>
                        </li>
                        <li>
                            <span itemscope itemtype="http://schema.org/Organization">
                                <link itemprop="url" href="https://www.swipez.in">
                                <a itemprop="sameAs" target="_BLANK" href="https://www.youtube.com/channel/UC62hpFWFt-S8aReQGDsdoSg/featured" data-original-title="Youtube" title="Youtube Channel"><i class="fa fa-youtube"></i>
                                </a>
                            </span>
                        </li>   
                        <span> Associated with</span>
                        <li>
                            <span itemscope itemtype="http://schema.org/Organization">
                                <a itemprop="sameAs" target="_BLANK" href="https://vilcap.com/" data-original-title="Home | Village Capital" title="Home | Village Capital">
                                    <img class="img-responsive" style="height: 34px;margin-bottom: -12px;" src="/assets/admin/layout/img/vilcap.png" alt="Home | Village Capital" title="Home | Village Capital">
                                </a>
                            </span>
                        </li>
                        <li>
                            <span itemscope itemtype="http://schema.org/Organization">
                                <a itemprop="sameAs" data-original-title="Home | Mumbai Fintech Hub" title="Home | Mumbai Fintech Hub">
                                    <img class="img-responsive" style="height: 34px;margin-bottom: -12px;" src="/assets/admin/layout/img/fintech.png" alt="Home | Mumbai Fintech Hub" title="Home | Mumbai Fintech Hub">
                                </a>
                            </span>
                        </li>
                    </ul>

                    <p>Powered by Swipez &copy; <?php echo $this->current_year; ?> OPUS Net Pvt. Handmade in Pune.</p>
                </div>
                <!-- END BOTTOM ABOUT BLOCK -->
            </div>
        </div>
    </div>
    <!-- End Footer Coypright -->
</section>
<!-- END CONTACT SECTION -->
</div>

<div class="modal fade modal-full " id="helpdesk" style="z-index: 10000;" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" id="closeguest" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-close"></i></button>
            </div>
            <div class="modal-body">
                <div class="portlet-body">
                    <iframe style="width: 100%;height: 500px;" src="<?php echo $this->server_name; ?>/helpdesk"></iframe>
                </div>

            </div>

        </div>

    </div>
    <!-- /.modal-content -->
</div>

<!-- END MAIN LAYOUT -->
<a href="#intro" class="go2top"><i class="fa fa-arrow-up"></i></a>

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
    var RevosliderInit = function() {
        $(".iframe").colorbox({iframe: true, width: "80%", height: "90%"});

        return {
            initRevoSlider: function() {
                var height = 580; // minimal height for medium resolution

                // smart height detection for all major screens
                if (Layout.getViewPort().width > 1600) {
                    height = $(window).height() - $('.subscribe').outerHeight();  // full height for high resolution
                } else if (Layout.getViewPort().height > height) {
                    height = Layout.getViewPort().height;
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
    jQuery(document).ready(function() {
        Layout.init();
        RevosliderInit.initRevoSlider();
    });
</script>
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>