<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-js">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->
    <head>
        <meta charset="utf-8"/>
        <title>Swipez | Pricing </title>
        <link rel="canonical" href=""/>


        <meta name="title" content="Swipez Pricing">
        <meta name="description" content="Swipez keeps the pricing for businesses affordable, transparent and fair. Use Swipez to collect payments, bulk invoice your customers and manage events">
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
        <link href="/assets/global/plugins/nouislider/nouislider.css" rel="stylesheet">

        <!-- END PAGE LEVEL PLUGIN STYLES -->
        <!-- BEGIN THEME STYLES -->
        <link href="/assets/frontend/onepage2/css/layout.css?version=0.1" rel="stylesheet" type="text/css"/>
        <link href="/assets/admin/layout/css/pricingtable.css" rel="stylesheet" type="text/css"/>
        <link href="/assets/frontend/onepage2/css/custom.css" rel="stylesheet" type="text/css"/>
        <link href="/assets/admin/layout/css/colorbox.css" rel="stylesheet" type="text/css"/>
        <!-- END THEME STYLES -->
        <link rel="shortcut icon" href="/favicon.ico"/>
        {if $env == 'PROD'}
        {literal}
        <!-- Facebook Pixel Code -->
        <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
        n.callMethod.apply(n,arguments):n.queue.push(arguments)};
        if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
        n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];
        s.parentNode.insertBefore(t,s)}(window, document,'script',
        'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '489330198131207');
        fbq('track', 'PageView');
        </script>
        <noscript><img height="1" width="1" style="display:none"
        src="https://www.facebook.com/tr?id=489330198131207&ev=PageView&noscript=1"
        /></noscript>
        <!-- End Facebook Pixel Code -->
        {/literal}
        {/if}
    </head>
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<!-- DOC: Apply "page-on-scroll" class to the body element to set fixed header layout -->
<body class="page-header-fixed">
    {strip}
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
                    <a itemprop="url" class="navbar-brand" title="Swipez" href="{$server_name}">
                        <img class="logo-default" src="/assets/frontend/onepage2/img/logo_default.png" alt="Swipez Online Payment" title="Swipez Online Payment Solutions">
                    <img class="logo-scroll" src="/assets/frontend/onepage2/img/logo_scroll.png" alt="Swipez Online Payment" title="Swipez Online Payment Solutions">
                    </a>
                    </div>
                    </div>

                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse navbar-responsive-collapse">
                        <ul class="nav navbar-nav">
                            <li class="dropdown page-scroll">
                                <a class="dropdown-toggle" style="border-color: transparent;" data-toggle="dropdown" data-target="#" href="javascript:;">
                                    Products
                                </a>
                                <ul class="dropdown-menu" style="background-color: #275770;margin-top: -20px;">
                                    <li style="padding: 0 0;"><a href="{$server_name}/invoicing-software" style="font-size: 14px;">Invoicing Software</a></li>
                                    <li style="padding: 0 0;"><a href="{$server_name}/event-management-software" style="font-size: 14px;">Event Management Software</a></li>
                                    <li style="padding: 0 0;"><a href="{$server_name}/booking-management-software" style="font-size: 14px;">Booking Management Software</a></li>
                                    <li style="padding: 0 0;"><a href="{$server_name}/url-shortener" style="font-size: 14px;">URL Shortener</a></li>
                                    <li style="padding: 0 0;"><a href="{$server_name}/website-builder" style="font-size: 14px;">Website Builder</a></li>
                                    <li style="padding: 0 0;"><a href="{$server_name}/coupon-management-software" style="font-size: 14px;">Coupon Management Software</a></li>
                                </ul>
                            </li>
                            <li class="page-scroll">
                                <a href="{$server_name}/pricing" >
                                    Pricing
                                </a>
                            </li>
                            <li class="page-scroll">
                                <a href="https://helpdesk.swipez.in/help">FAQs</a>
                            </li>
                            <li class="page-scroll">
                                <a href="{$server_name}/partner" >
                                    Partner
                                </a>
                            </li>
                            {if $logged_in==true}
                                <li class="page-scroll">
                                    <a href="{$server_name}/{$user_type}/dashboard">Dashboard</a>
                                </li>
                                <li class="page-scroll">
                                    <a href="/logout">Logout</a>
                                </li>
                            {else}
                                <li class="page-scroll">
                                    <a href="{$server_name}/merchant/register">Register</a>
                                </li>
                                <li class="page-scroll">
                                    <a href="{$server_name}/login">Login</a>
                                </li>
                            {/if}


                            <!--<li class="page-scroll">
                                <a href="#portfolio">Portfolio</a>
                            </li>

                            <li class="page-scroll">
                                <a href="#contact">Contact</a>
                            </li>-->
                        </ul>
                    </div>
                    <!-- End Navbar Collapse -->
                </div>
                <!--/container-->
            </nav>
        </header>
        <!-- Header END -->

        <!-- BEGIN INTRO SECTION -->
        <section id="intro" class="hidden-xs">
            <!-- Slider BEGIN -->
            <div class="page-slider">
                <div class="fullwidthbanner-container revolution-slider" style="height: 395px !important;">
                    <div class="banner" style="height: 395px !important;">
                        <ul id="revolutionul">
                            <!-- THE NEW SLIDE -->
                            <li data-transition="fade" data-slotamount="8" data-masterspeed="700" data-delay="9400" data-thumb="">
                                <!-- THE MAIN IMAGE IN THE FIRST SLIDE -->
                                <img src="/assets/admin/layout/img/pricing_banner.jpg" alt="Swipez Pricing Banner" title="Swipez Pricing Details Banner"  >

                                <div class="caption lft tp-resizeme"
                                     data-x="center"
                                     data-y="center"

                                     data-voffset="20"
                                     data-speed="900"
                                     data-start="1000"
                                     data-easing="easeOutExpo">
                                    <center><p class="subtitle-v2" style="font-size: 28px;font-weight: initial;">MAKE YOUR OWN PLAN<br></p>
                                    <p class="subtitle-v2" style="font-size: 22px;text-align: center;line-height: 0px;font-weight: initial;">USE OUR PLAN BUILDER TO CREATE CUSTOM PLANS TAILORED TO YOUR NEEDS.</p>
                                    </center>
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
        <!-- SUBSCRIBE BEGIN -->

        <!-- SUBSCRIBE END -->



        <!-- BEGIN FEATURES SECTION -->

        <div class="container">
        <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-10"><br/><br/>
                <div class="row border">
                    <div class="col-md-1"></div>
                    <div class="col-md-7">
                        <div class="row">
                            <div class="col-md-9">
                                <br/><br/>
                                <h5 class="faq_title">INVOICES</h5>
                                <p class="faq_text">Select the number of invoices required for a year.</p>
                                <div id="invoice_slider"></div>
                            </div>
                            <div class="col-md-3">
                                <br/><br/><br/><br/><span class="faq_title" id="invoice-slider-value1">0</span><span class="faq_text"> invoices</span>
                            </div>
                            <div class="col-md-9">
                                <br/><br/>
                                <h5 class="faq_title">EVENT BOOKINGS</h5>
                                <p class="faq_text">Select the number of event bookings required in one year.</p>
                                <div id="event_slider"></div>
                            </div>
                            <div class="col-md-3">
                                <br/><br/><br/><br/><span class="faq_title"  id="events-slider-value1">0</span><span class="faq_text"> bookings</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 pricing-bg" style="padding: 10px;">
                        <div class="col-md-12">
                        <h5 class="faq_title">SUMMARY</h5>
                        <br/>
                        </div>
                        <div class="col-md-6">
                            <span class="faq_text">Invoices</span>
                            <p class="faq_title" id="invoice-slider-value2">0</p>
                        </div>
                        <div class="col-md-6">
                            <span class="faq_text">&nbsp;&nbsp;&nbsp;Price</span>
                            <p class="faq_title">₹ <span class="faq_title" id="invoice-cost">0</span></p>
                        </div>
                        <div class="col-md-6">
                            <span class="faq_text">Event bookings</span>
                            <p class="faq_title" id="events-slider-value2">0</p>
                        </div>
                        <div class="col-md-6">
                            <span class="faq_text">&nbsp;</span>
                            <p class="faq_title">₹ <span class="faq_title" id="events-cost">0</span></p>
                        </div>
                        <div class="col-md-6">
                            <span class="faq_text">SMS</span>
                            <p class="faq_title" id="sms-no">0</p>
                        </div>
                        <div class="col-md-6">
                            <span class="faq_text">&nbsp;</span>
                            <p class="faq_title">&nbsp;Free</p></br/>
                        </div>
                        <div class="col-md-6" style="border-top: 1px solid #dee2e6!important; border-bottom: 1px solid #dee2e6!important;">
                            <p class="faq_title" style="padding: 10px;">COST</p>
                        </div>
                        <div class="col-md-6" style="border-top: 1px solid #dee2e6!important; border-bottom: 1px solid #dee2e6!important;">
                            <p class="faq_title" style="padding: 10px;">₹ <span class="faq_title" id="total-cost">0</span></p>
                        </div>
                        <div class="col-md-12">
                            <form method="POST"  action="/merchant/package/confirm">
                                <center><br><button type="submit" id="submitbtn" disabled class="btn" style="background-color: #f3bb76;color: #ffffff;">Purchase Plan</button></center>
                            <input type=hidden id="booking_count" name="eventsVal" value="0"/>
                            <input type=hidden id="inv_count" name="invoicesVal" value="0"/>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-1"></div>
        </div>
        </div>
        <div class="visible-xs"><br><br><br><br><br></div>
{/strip}
