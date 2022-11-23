
<?php
include_once '../legacy_app/view/header/header_common.php';
?>
<meta name="viewport" content="width=device-width, initial-scale = 1.0,
maximum-scale=1.0, user-scalable=no" />
<meta charset="TDF-8">
<!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" type="text/css" href="/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css"/>
<!-- END PAGE LEVEL STYLES -->
<!-- BEGIN THEME STYLES -->
<link href="/assets/admin/layout/css/custom.css<?php echo $this->fileTime('css', 'custom'); ?>" rel="stylesheet" type="text/css"/>
<link href="/assets/admin/layout/css/movingintotailwind.css<?php echo $this->fileTime('css', 'movingintotailwind'); ?>" rel="stylesheet" type="text/css"/>

<!-- END THEME STYLES -->
<link rel="shortcut icon" href="favicon.ico"/>
</head>
<body class="page-header-fixed page-quick-sidebar-over-content page-full-width">
    <?php
    include_once '../legacy_app/view/header/body_tag.php';
    ?>
    <!-- BEGIN HEADER -->
    <div class="page-header navbar navbar-fixed-top">
        <!-- BEGIN HEADER INNER -->
        <div class="page-header-inner">
            <!-- BEGIN LOGO -->

                <?php if ($this->logo != '') { ?>
            <div class="page-logo">
                    <a target="_BLANK" href="<?php
                           echo $this->url;
                       ?>">
                        <img src="/uploads/images/landing/<?php echo $this->logo; ?>" style="max-height: 50px;" alt="<?php echo $this->company_name; ?>" class="logo-default"/>
                    </a>
                </div>
<?php }else{ ?>
            <div class="page-logo" style="width: auto;">

                <h3><?php echo $this->company_name;?></h3>
            </div>

<?php }?>

            <!-- END LOGO -->
            <!-- BEGIN HORIZANTAL MENU -->
            <!-- DOC: Apply "hor-menu-light" class after the "hor-menu" class below to have a horizontal menu with white background -->
            <!-- DOC: This is desktop version of the horizontal menu. The mobile version is defined(duplicated) sidebar menu below. So the horizontal menu has 2 seperate versions -->
            <?php if ($this->disable_menu != 1) { ?>
            <div class="hor-menu hor-menu-light hidden-sm hidden-xs pull-right">
                <ul class="nav navbar-nav">
                    <!-- DOC: Remove data-hover="dropdown" and data-close-others="true" attributes below to disable the horizontal opening on mouse hover -->
<?php if ($this->menu_home == 1) { ?>
                        <li class="classic-menu-dropdown">
                            <a href="<?php echo $this->url; ?>">
                                <h4>Home </h4>
                                <span class="selected"></span>
                            </a>
                        </li>
                    <?php } ?>
<?php if ($this->menu_paybill == 1) { ?>
                        <li class="classic-menu-dropdown">
                            <a href="<?php echo $this->url; ?>/paymybills">
                                <h4>Pay my bill </h4>
                            </a>
                        </li>
                    <?php } ?>
                        <?php foreach ($this->form_builder as $form) { ?>
                        <li class="classic-menu-dropdown <?php
                                if ($this->form_link == $form['key']) {
                                    echo 'active';
                                }
                                ?>">
                            <a href="<?php echo $form['link']; ?>">
                                <h4><?php echo $form['name']; ?> </h4>
                                <?php
                        if ($this->form_link == $form['key']) {
                            echo '<span class="selected"></span>';
                        }
                        ?>

                            </a>
                        </li>
                        <?php } ?>
<?php if ($this->menu_package == 1) { ?>
                        <li class="classic-menu-dropdown ">
                            <a href="<?php echo $this->url; ?>/packages">
                                <h4>Packages</h4>
                            </a>
                        </li>
                    <?php } ?>
<?php if ($this->menu_booking == 1) { ?>
                        <li class="classic-menu-dropdown">
                            <a href="<?php echo $this->url; ?>/booking">
                                <h4>Booking</h4>
                            </a>
                        </li>
                    <?php } ?>
<?php if ($this->menu_policies == 1) { ?>
                        <li class="classic-menu-dropdown">
                            <a href="<?php echo $this->url ?>/policies">
                                <h4>Policies</h4>
                            </a>
                        </li>
                    <?php } ?>
<?php if ($this->menu_about == 1) { ?>
                        <li class="classic-menu-dropdown">
                            <a href="<?php echo $this->url ?>/aboutus">
                                <h4>About us</h4>
                            </a>
                        </li>
                    <?php } ?>
<?php if ($this->menu_contact == 1) { ?>
                        <li class="classic-menu-dropdown">
                            <a href="<?php echo $this->url ?>/contactus">
                                <h4>Contact us</h4>
                            </a>
                        </li>
<?php } ?>
                </ul>
            </div>
            <?php } ?>
            <!-- END HORIZANTAL MENU -->
            <!-- BEGIN HEADER SEARCH BOX -->
            <!-- DOC: Apply "search-form-expanded" right after the "search-form" class to have half expanded search box -->

            <!-- END HEADER SEARCH BOX -->
            <!-- BEGIN RESPONSIVE MENU TOGGLER -->
            <?php if ($this->disable_menu != 1) { ?>
            <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse">
            </a>
            <?php } ?>
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
<?php if ($this->menu_home == 1) { ?>
                        <li class="">
                            <a href="/m/<?php echo $this->url; ?>">
                                Home
                                <span class="selected"></span>
                            </a>
                        </li>
                    <?php } ?>
<?php if ($this->menu_paybill == 1) { ?>
                        <li class="">
                            <a href="/m/<?php echo $this->url; ?>/paymybills">
                                Pay my bill
                            </a>
                        </li>
                    <?php } ?>
                        <li class="active">
                            <a href="/m/<?php echo $this->url; ?>/directpay">
                                Direct pay
                            </a>
                        </li>
<?php if ($this->menu_package == 1) { ?>
                        <li class="">
                            <a href="/m/<?php echo $this->url; ?>/packages">
                                Packages
                            </a>
                        </li>
                    <?php } ?>
<?php if ($this->menu_booking == 1) { ?>
                        <li class="">
                            <a href="/m/<?php echo $this->url; ?>/booking">
                                Booking
                            </a>
                        </li>
                    <?php } ?>
<?php if ($this->menu_policies == 1) { ?>
                        <li class="">
                            <a href="/m/<?php echo $this->url; ?>/policies">
                                Policies
                            </a>
                        </li>
                    <?php } ?>
<?php if ($this->menu_about == 1) { ?>
                        <li class="">
                            <a href="/m/<?php echo $this->url; ?>/aboutus">
                                About us
                            </a>
                        </li>
                    <?php } ?>
<?php if ($this->menu_contact == 1) { ?>
                        <li class="">
                            <a href="/m/<?php echo $this->url; ?>/contactus">
                                Contact us
                            </a>
                        </li>
<?php } ?>
                </ul>
            </div>
            <!-- END HORIZONTAL RESPONSIVE MENU -->
        </div>
        <!-- END SIDEBAR -->
        <!-- BEGIN CONTENT -->
        <div class="page-content-wrapper">
