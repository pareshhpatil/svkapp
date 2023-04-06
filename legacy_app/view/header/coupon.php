<?php
include_once '../legacy_app/view/header/header_common.php';
?>
<!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" type="text/css" href="/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css"/>
<link href="/assets/admin/pages/css/portfolio.css" rel="stylesheet" type="text/css"/>
<link href="/assets/admin/layout/css/colorbox.css<?php echo $this->fileTime('css', 'colorbox'); ?>" rel="stylesheet" type="text/css"/>
<!-- END PAGE LEVEL STYLES -->
<!-- BEGIN THEME STYLES -->
<link href="/assets/admin/layout/css/custom.css<?php echo $this->fileTime('css', 'custom'); ?>" rel="stylesheet" type="text/css"/>
<link href="/assets/admin/layout/css/movingintotailwind.css<?php echo $this->fileTime('css', 'movingintotailwind'); ?>" rel="stylesheet" type="text/css"/>

<!-- END THEME STYLES -->
<link rel="shortcut icon" href="images/briq.ico"/>


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
<body class="page-header-fixed page-quick-sidebar-over-content page-full-width">
    <?php
    include_once '../legacy_app/view/header/body_tag.php';
    ?>
    <!-- BEGIN HEADER -->
    <div class="page-header navbar navbar-fixed-top">




        <div class="page-header-inner">

            <div class="page-logo" itemscope="" itemtype="">
                <?php if ($this->details['logo'] != '') { ?>
                    <a target="_BLANK" href="<?php
                    if ($this->domain != '') {
                        echo $this->domain;
                    } else {
                        ?>/m/<?php
                           echo $this->url;
                       }
                       ?>">
                        <img src="/uploads/images/landing/<?php echo $this->details['logo']; ?>" style="max-height: 50px;max-width: 250px;" alt="<?php echo $this->details['company_name']; ?>" class="logo-default"/>
                    </a>
                <?php } ?>
            </div>


            <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse">
            </a>




        </div>





        <!-- BEGIN HEADER INNER -->
        <div class="page-header-inner">
            <!-- BEGIN LOGO -->

            <!-- END LOGO -->
            <!-- BEGIN HORIZANTAL MENU -->
            <!-- DOC: Apply "hor-menu-light" class after the "hor-menu" class below to have a horizontal menu with white background -->
            <!-- DOC: This is desktop version of the horizontal menu. The mobile version is defined(duplicated) sidebar menu below. So the horizontal menu has 2 seperate versions -->
            <div class="hor-menu hor-menu-light hidden-sm hidden-xs pull-right">
                <ul class="nav navbar-nav">
                    <!-- DOC: Remove data-hover="dropdown" and data-close-others="true" attributes below to disable the horizontal opening on mouse hover -->
                    <li id="cudubest" class="classic-menu-dropdown active">
                        <a onclick="cudu_offers();">
                            <h4>Best Offers </h4>
                            <span class="selected"></span>
                        </a>
                    </li>
                    <li id="cudustore" class="classic-menu-dropdown ">
                        <a onclick="cudu_store();">
                            <h4>Stores </h4>
                            <span class="selected"></span>
                        </a>
                    </li>

                    <li id="cuduexclusive" class="classic-menu-dropdown ">
                        <a onclick="cudu_exclusive_offers();">
                            <h4>Exclusive Offers </h4>
                            <span class="selected"></span>
                        </a>
                    </li>

                </ul>
            </div>
            <!-- END HORIZANTAL MENU -->
            <!-- BEGIN HEADER SEARCH BOX -->
            <!-- DOC: Apply "search-form-expanded" right after the "search-form" class to have half expanded search box -->

            <!-- END HEADER SEARCH BOX -->
            <!-- BEGIN RESPONSIVE MENU TOGGLER -->

            <!-- END RESPONSIVE MENU TOGGLER -->
            <!-- BEGIN TOP NAVIGATION MENU -->

            <!-- END TOP NAVIGATION MENU -->

        </div>
        <!-- END HEADER INNER -->
    </div>
    <!-- END HEADER -->
    <div class="clearfix">
    </div>
    <!-- BEGIN CONTAINER -->
    <div class="page-container">
        <!-- BEGIN SIDEBAR -->
        <div class="page-sidebar-wrapper">
            <!-- BEGIN HORIZONTAL RESPONSIVE MENU -->
            <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
            <!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
            <div class="page-sidebar navbar-collapse collapse">
                <ul class="page-sidebar-menu" data-slide-speed="200" data-auto-scroll="true">
                    <!-- DOC: To remove the search box from the sidebar you just need to completely remove the below "sidebar-search-wrapper" LI element -->
                    <!-- DOC: This is mobile version of the horizontal menu. The desktop version is defined(duplicated) in the header above -->
                    <li id="mcudubest" class="classic-menu-dropdown">
                        <a onclick="cudu_offers();">
                            <h4>Best Offers </h4>
                            <span class="selected"></span>
                        </a>
                    </li>
                    <li id="mcudustore" class="classic-menu-dropdown active">
                        <a onclick="cudu_store();">
                            <h4>Stores </h4>
                            <span class="selected"></span>
                        </a>
                    </li>

                    <li id="mcuduexclusive" class="classic-menu-dropdown ">
                        <a onclick="cudu_exclusive_offers();">
                            <h4>Exclusive Offers </h4>
                            <span class="selected"></span>
                        </a>
                    </li>
                </ul>
            </div>
            <!-- END HORIZONTAL RESPONSIVE MENU -->

        </div>
        <!-- END SIDEBAR -->
        <!-- BEGIN CONTENT -->
        <div class="page-content-wrapper">
