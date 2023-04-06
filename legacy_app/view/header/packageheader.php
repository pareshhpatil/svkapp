<?php
include_once '../legacy_app/view/header/header_common.php';
?>
<!-- BEGIN PAGE LEVEL STYLES -->
<link href="/assets/admin/layout/css/custom.css<?php echo $this->fileTime('css', 'custom'); ?>" rel="stylesheet" type="text/css"/>
<script src="/assets/admin/layout/scripts/register.js<?php echo $this->fileTime('js', 'register'); ?>" type="text/javascript"></script>
<link href="/assets/admin/layout/css/movingintotailwind.css<?php echo $this->fileTime('css', 'movingintotailwind'); ?>" rel="stylesheet" type="text/css"/>
<!-- END THEME STYLES -->
<link rel="shortcut icon" href="/images/briq.ico"/>

<?php
if ($this->showcampaign_script == 1 && $this->env == 'PROD') {
    ?>
    <!-- Google Code for Swipez home Conversion Page -->
    <script type="text/javascript">
        /* <![CDATA[ */
        var google_conversion_id = 817807754;
        var google_conversion_label = "wsjyCIuOjnwQioP7hQM";
        var google_remarketing_only = false;
        /* ]]> */
    </script>
    <script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
    </script>
    <noscript>
    <div style="display:inline;">
        <img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/817807754/?label=wsjyCIuOjnwQioP7hQM&amp;guid=ON&amp;script=0"/>
    </div>
    </noscript>
<?php } ?>


<?php if ($this->fb_pixel == 1 && $this->env == 'PROD') { ?>
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
        fbq('init', '168661093739358');
        fbq('track', 'PageView');
    </script>
    <noscript>
    <img height="1" width="1" src="https://www.facebook.com/tr?id=168661093739358&ev=PageView&noscript=1"/>
    </noscript>
    <!-- End Facebook Pixel Code -->
<?php } ?>

</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<!-- DOC: Apply "page-header-fixed-mobile" and "page-footer-fixed-mobile" class to body element to force fixed header or footer in mobile devices -->
<!-- DOC: Apply "page-sidebar-closed" class to the body and "page-sidebar-menu-closed" class to the sidebar menu element to hide the sidebar by default -->
<!-- DOC: Apply "page-sidebar-hide" class to the body to make the sidebar completely hidden on toggle -->
<!-- DOC: Apply "page-sidebar-closed-hide-logo" class to the body element to make the logo hidden on sidebar toggle -->
<!-- DOC: Apply "page-sidebar-hide" class to body element to completely hide the sidebar on sidebar toggle -->
<!-- DOC: Apply "page-sidebar-fixed" class to have fixed sidebar -->
<!-- DOC: Apply "page-footer-fixed" class to the body element to have fixed footer -->
<!-- DOC: Apply "page-sidebar-reversed" class to put the sidebar on the right side -->
<!-- DOC: Apply "page-full-width" class to the body element to have full width page without the sidebar menu -->
<body class="page-header-fixed page-quick-sidebar-over-content">
<?php
    include_once '../legacy_app/view/header/body_tag.php';
    ?>
    <!-- BEGIN HEADER -->
    <div class="page-header navbar navbar-fixed-top">
        <!-- BEGIN HEADER INNER -->
        <div class="page-header-inner">
            <!-- BEGIN LOGO -->
            <div class="page-logo" itemscope="" itemtype="http://schema.org/Organization">
                <a itemprop="url" title="Swipez" href="<?php echo $this->server_name; ?>">
                    <img class="logo-default" src="/assets/admin/layout/img/logo.png?v=2" alt="Swipez Online Payment" title="Swipez Online Payment Solutions">
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
                        <h4 class="text-primary"> Speak to an expert: 741 497 3338</h4>
                        <h4 class="text-primary"> Monday to Saturday 9.30 am - 6pm</h4>
                    </li>


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
