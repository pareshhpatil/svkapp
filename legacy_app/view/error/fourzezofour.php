<?php
//header(':', true, 404);
header('X-PHP-Response-Code: 404', true, 404);
?>
<?php
include_once '../legacy_app/view/header/header_common.php';
?>
<!-- BEGIN PAGE LEVEL STYLES -->

<!-- BEGIN THEME STYLES -->
<link href="/assets/global/css/components.css" id="style_components" rel="stylesheet" type="text/css"/>
<link href="/assets/global/css/plugins.css" rel="stylesheet" type="text/css"/>
<link href="/assets/admin/layout/css/layout.css" rel="stylesheet" type="text/css"/>
<link id="style_color" href="/assets/admin/layout/css/themes/blue.css<?php echo $this->fileTime('css', 'layout'); ?>" rel="stylesheet" type="text/css"/>
<link href="/assets/admin/pages/css/error.css" rel="stylesheet" type="text/css"/>
<link href="/assets/admin/layout/css/custom.css" rel="stylesheet" type="text/css"/>
<link href="/assets/admin/layout/css/colorbox.css" rel="stylesheet" type="text/css"/>
<link href="/assets/frontend/onepage2/css/layout.css" rel="stylesheet" type="text/css"/>
<link href="/assets/frontend/onepage2/css/custom.css" rel="stylesheet" type="text/css"/>
<!-- END THEME STYLES -->
<link rel="shortcut icon" href="/favicon.ico"/>

</head>
</head>
<body class="page-header-fixed page-quick-sidebar-over-content">

    <!-- BEGIN HEADER -->
    <div class="page-header navbar navbar-fixed-top">
        <!-- BEGIN HEADER INNER -->
        <div class="page-header-inner">
            <!-- BEGIN LOGO -->
            <div class="page-logo">
                <a href="<?php echo $this->server_name; ?>">
                    <img src="/assets/admin/layout/img/logo.png?v=2?v=2"  alt="Swipez Online Payment" title="Swipez Online Payment Solutions" class="logo-default"/>
                </a>
                <div class="menu-toggler sidebar-toggler hide">
                    <!-- DOC: Remove the above "hide" to enable the sidebar toggler button on header -->
                </div>
            </div>
            <!-- END LOGO -->
            <!-- BEGIN RESPONSIVE MENU TOGGLER -->

            <!-- END RESPONSIVE MENU TOGGLER -->
            <!-- BEGIN TOP NAVIGATION MENU -->
            <div class="top-menu">
                <ul class="nav navbar-nav pull-right">
                    <li class="dropdown dropdown-extended dropdown-notification">
                        <a  style="color: #5b9bd1;" data-toggle="dropdown" data-target="#" href="javascript:;">
                            <h4>Products</h4>
                        </a>
                        <ul class="dropdown-menu" style="background-color: #275770;color: white;">
                            <li style="padding: 0 0;"><a href="<?php echo $this->server_name; ?>/invoicing-software" style="font-size: 14px;color: white;">Invoicing Software</a></li>
                            <li style="padding: 0 0;"><a href="<?php echo $this->server_name; ?>/event-management-software" style="font-size: 14px;color: white;">Event Management Software</a></li>
                            <li style="padding: 0 0;"><a href="<?php echo $this->server_name; ?>/booking-management-software" style="font-size: 14px;color: white;">Booking Management Software</a></li>
                            <li style="padding: 0 0;"><a href="<?php echo $this->server_name; ?>/url-shortener" style="font-size: 14px;color: white;">URL Shortener</a></li>
                            <li style="padding: 0 0;"><a href="<?php echo $this->server_name; ?>/website-builder" style="font-size: 14px;color: white;">Website Builder</a></li>
                            <li style="padding: 0 0;"><a href="<?php echo $this->server_name; ?>/coupon-management-software" style="font-size: 14px;color: white;">Coupon Management Software</a></li>
                        </ul>
                    </li>
                    <li class="dropdown dropdown-extended dropdown-notification" id="header_notification_bar">
                        <a href="<?php echo $this->server_name; ?>/pricing" style="color: #5b9bd1;">
                            <h4>Pricing</h4>
                        </a>
                    </li>
                    <li class="dropdown dropdown-extended dropdown-notification" id="header_notification_bar">
                        <a href="<?php echo $this->support_link; ?>/" target="_BLANK" style="color: #5b9bd1;">
                            <h4>FAQs</h4>
                        </a>
                    </li>
                    <li class="dropdown dropdown-extended dropdown-notification" id="header_notification_bar">
                        <a href="<?php echo $this->server_name; ?>/partner" style="color: #5b9bd1;">
                            <h4>Partner</h4>
                        </a>
                    </li>
                    <?php if ($this->isLoggedIn == TRUE) { ?>
                        <li class="dropdown dropdown-extended dropdown-notification" id="header_notification_bar">
                            <a style="color: #5b9bd1;" href="<?php echo $this->server_name; ?><?php echo "/" . $this->usertype . '/dashboard'; ?>"><h4>Dashboard</h4></a>
                        </li>

                        <li class="dropdown dropdown-extended dropdown-notification" id="header_notification_bar">
                            <a style="color: #5b9bd1;" href="<?php echo $this->server_name; ?>/logout"><h4>Logout</h4></a>
                        </li>
                    <?php } else { ?>
                       <li class="dropdown dropdown-extended dropdown-notification" id="header_notification_bar">
                            <a style="color: #5b9bd1;" href="<?php echo $this->server_name; ?>/merchant/register"><h4>Register</h4></a>
                        </li>
                        <li class="dropdown dropdown-extended dropdown-notification" id="header_notification_bar">
                            <a style="color: #5b9bd1;" href="<?php echo $this->server_name; ?>/login"><h4>Login</h4></a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
            <!-- END TOP NAVIGATION MENU -->
        </div>
        <!-- END HEADER INNER -->
    </div>
    <!-- END HEADER -->
    <div class="clearfix">
    </div>
    <div class="page-container">

<!-- BEGIN CONTENT -->
<div class="page-content">
    <br>
    <!-- BEGIN PAGE HEADER-->
    <!-- <h3 class="page-title">&nbsp</h3>-->
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row no-margin">
        <div class="col-md-2"></div>
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-12 page-404">
                    <div class="number">
                        404
                    </div>
                    <div class="details">
                        <h3>Oops! You're lost.</h3>
                        <p>
                            We can not find the page you're looking for.<br/>
                            <a href="<?php echo $this->server_name; ?>">
                                Return home </a>
                        </p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 page-404">

                    <div class="details">
                        <br>
                        <p>
                            <br>
                            <a href="https://www.swipez.in/faq/">FAQ</a> | <a href="https://www.swipez.in/sitemap">Sitemap</a> | <a href="https://www.swipez.in/login">Login</a> | <a href="https://www.swipez.in/merchant/register">Register</a>
                        </p>


                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- END PAGE CONTENT-->
</div>
</div>
