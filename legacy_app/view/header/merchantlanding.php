<?php
include_once '../legacy_app/view/header/header_common.php';
?>
<?php if ($this->hideplugin == false) { ?>
    <script src='https://www.google.com/recaptcha/api.js'></script>
<?php }
?>
<link href="/assets/admin/layout/css/custom.css<?php echo $this->fileTime('css', 'custom'); ?>" rel="stylesheet" type="text/css" />
<link href="/assets/admin/layout/css/movingintotailwind.css<?php echo $this->fileTime('css', 'movingintotailwind'); ?>" rel="stylesheet" type="text/css" />

<link rel="shortcut icon" href="/favicon.ico" />
</head>
<body class="<?php
                if ($this->hide_header_menu == 1) {
                    echo 'bg-white';
                } else {
                    echo 'page-header-fixed';
                }
                ?> page-quick-sidebar-over-content page-full-width">
    <?php
    include_once '../legacy_app/view/header/body_tag.php';
    ?>
    <!-- BEGIN HEADER -->
    <?php
    if ($this->hide_header_menu == 1) {
    } else {
    ?>
        <div class="page-header navbar navbar-fixed-top">
            <!-- BEGIN HEADER INNER -->
            <div class="page-header-inner">
                <!-- BEGIN LOGO -->
                <div class="page-logo">
                    <?php if ($this->details['logo'] != '') { ?>
                        <a target="_BLANK" href="<?php
                                                    if ($this->domain != '') {
                                                        echo $this->domain;
                                                    } else {
                                                    ?>/m/<?php
                                                        echo $this->url;
                                                    }
                                    ?>">
                            <img src="/uploads/images/landing/<?php echo $this->details['logo']; ?>" style="max-height: 50px;" alt="<?php echo $this->details['company_name']; ?>" class="logo-default" />
                        </a>
                    <?php } ?>
                </div>
                <!-- END LOGO -->
                <!-- BEGIN HORIZANTAL MENU -->
                <!-- DOC: Apply "hor-menu-light" class after the "hor-menu" class below to have a horizontal menu with white background -->
                <!-- DOC: This is desktop version of the horizontal menu. The mobile version is defined(duplicated) sidebar menu below. So the horizontal menu has 2 seperate versions -->
                <div class="hor-menu hor-menu-light hidden-sm hidden-xs pull-right">
                    <ul class="nav navbar-nav">
                        <!-- DOC: Remove data-hover="dropdown" and data-close-others="true" attributes below to disable the horizontal opening on mouse hover -->
                        <li class="classic-menu-dropdown <?php
                                                            if ($this->selected == 'home') {
                                                                echo 'active';
                                                            }
                                                            ?>">
                            <a href="<?php
                                        if ($this->domain != '') {
                                            echo $this->domain;
                                        } else {
                                        ?>/m/<?php
                                            echo $this->url;
                                        }
                                        ?>">
                                <h4>Home </h4>
                                <?php
                                if ($this->selected == 'home') {
                                    echo '<span class="selected"></span>';
                                }
                                ?>
                                <span class="selected"></span>
                            </a>
                        </li>
                        <li class="classic-menu-dropdown <?php
                                                            if ($this->selected == 'paymybills') {
                                                                echo 'active';
                                                            }
                                                            ?>">
                            <a href="/m/<?php echo $this->url; ?>/paymybills">
                                <h4>Pay my bill </h4>
                                <?php
                                if ($this->selected == 'paymybills') {
                                    echo '<span class="selected"></span>';
                                }
                                ?>
                            </a>
                        </li>
                        <?php if ($this->is_directpay == 1) { ?>
                            <li class="classic-menu-dropdown <?php
                                                                if ($this->selected == 'directpay') {
                                                                    echo 'active';
                                                                }
                                                                ?>">
                                <a href="/m/<?php echo $this->url; ?>/directpay">
                                    <h4>Direct pay </h4>
                                    <?php
                                    if ($this->selected == 'directpay') {
                                        echo '<span class="selected"></span>';
                                    }
                                    ?>
                                </a>
                            </li>
                        <?php } ?>
                        <?php foreach ($this->form_builder as $form) { ?>
                            <li class="classic-menu-dropdown ">
                                <a href="<?php echo $form['link']; ?>">
                                    <h4><?php echo $form['name']; ?> </h4>
                                </a>
                            </li>
                        <?php } ?>
                        <?php if ($this->is_package == TRUE) { ?>
                            <li class="classic-menu-dropdown <?php
                                                                if ($this->selected == 'packages') {
                                                                    echo 'active';
                                                                }
                                                                ?>">
                                <a href="/m/<?php echo $this->url; ?>/packages">
                                    <h4>Packages</h4>
                                    <?php
                                    if ($this->selected == 'packages') {
                                        echo '<span class="selected"></span>';
                                    }
                                    ?>
                                </a>
                            </li>
                        <?php } ?>
                        <?php if ($this->is_booking == TRUE) { ?>
                            <li class="classic-menu-dropdown <?php
                                                                if ($this->selected == 'booking') {
                                                                    echo 'active';
                                                                }
                                                                ?>">
                                <a href="/m/<?php echo $this->url; ?>/booking">
                                    <h4>Booking</h4>
                                    <?php
                                    if ($this->selected == 'booking') {
                                        echo '<span class="selected"></span>';
                                    }
                                    ?>
                                </a>
                            </li>
                        <?php } ?>
                        <li class="classic-menu-dropdown <?php
                                                            if ($this->selected == 'policies') {
                                                                echo 'active';
                                                            }
                                                            ?>">
                            <a href="<?php
                                        if ($this->domain != '') {
                                            echo $this->domain;
                                        } else {
                                        ?>/m/<?php
                                            echo $this->url . '/policies';
                                        }
                                        ?>">
                                <h4>Policies</h4>
                                <?php
                                if ($this->selected == 'policies') {
                                    echo '<span class="selected"></span>';
                                }
                                ?>
                            </a>
                        </li>
                        <li class="classic-menu-dropdown <?php
                                                            if ($this->selected == 'aboutus') {
                                                                echo 'active';
                                                            }
                                                            ?>">
                            <a href="<?php
                                        if ($this->domain != '') {
                                            echo $this->domain;
                                        } else {
                                        ?>/m/<?php
                                            echo $this->url . '/aboutus';
                                        }
                                        ?>">
                                <h4>About us</h4>
                                <?php
                                if ($this->selected == 'aboutus') {
                                    echo '<span class="selected"></span>';
                                }
                                ?>
                            </a>
                        </li>
                        <li class="classic-menu-dropdown <?php
                                                            if ($this->selected == 'contactus') {
                                                                echo 'active';
                                                            }
                                                            ?>">
                            <a href="<?php
                                        if ($this->domain != '') {
                                            echo $this->domain;
                                        } else {
                                        ?>/m/<?php
                                            echo $this->url . '/contactus';
                                        }
                                        ?>">
                                <h4>Contact us</h4>
                                <?php
                                if ($this->selected == 'contactus') {
                                    echo '<span class="selected"></span>';
                                }
                                ?>
                            </a>
                        </li>
                        <?php
                        if (isset($this->session_customer_id) || isset($this->member_login)) {
                        ?>
                            <li class="classic-menu-dropdown ">
                                <a href="/m/<?php echo $this->url . '/logoutcustomer/' . $this->backpath; ?>">
                                    <h4>Logout</h4>
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
                <!-- END HORIZANTAL MENU -->
                <!-- BEGIN HEADER SEARCH BOX -->
                <!-- DOC: Apply "search-form-expanded" right after the "search-form" class to have half expanded search box -->

                <!-- END HEADER SEARCH BOX -->
                <!-- BEGIN RESPONSIVE MENU TOGGLER -->
                <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse">
                </a>
                <!-- END RESPONSIVE MENU TOGGLER -->
                <!-- BEGIN TOP NAVIGATION MENU -->

                <!-- END TOP NAVIGATION MENU -->
            </div>
            <!-- END HEADER INNER -->
        </div>
    <?php } ?>
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

                    <li class="<?php
                                if ($this->selected == 'home') {
                                    echo 'active';
                                }
                                ?>">
                        <a href="/m/<?php echo $this->url; ?>">
                            Home
                            <?php
                            if ($this->selected == 'home') {
                                echo '<span class="selected"></span>';
                            }
                            ?>
                            <span class="selected"></span>
                        </a>
                    </li>
                    <li class="<?php
                                if ($this->selected == 'paymybills') {
                                    echo 'active';
                                }
                                ?>">
                        <a href="/m/<?php echo $this->url; ?>/paymybills">
                            Pay my bill
                            <?php
                            if ($this->selected == 'paymybills') {
                                echo '<span class="selected"></span>';
                            }
                            ?>
                        </a>
                    </li>
                    <li class="<?php
                                if ($this->selected == 'directpay') {
                                    echo 'active';
                                }
                                ?>">
                        <a href="/m/<?php echo $this->url; ?>/directpay">
                            Direct pay
                            <?php
                            if ($this->selected == 'directpay') {
                                echo '<span class="selected"></span>';
                            }
                            ?>
                        </a>
                    </li>
                    <?php if ($this->is_package == TRUE) { ?>
                        <li class=" <?php
                                    if ($this->selected == 'packages') {
                                        echo 'active';
                                    }
                                    ?>">
                            <a href="/m/<?php echo $this->url; ?>/packages">
                                Packages
                                <?php
                                if ($this->selected == 'packages') {
                                    echo '<span class="selected"></span>';
                                }
                                ?>
                            </a>
                        </li>
                    <?php } ?>
                    <?php if ($this->is_booking == TRUE) { ?>
                        <li class="<?php
                                    if ($this->selected == 'booking') {
                                        echo 'active';
                                    }
                                    ?>">
                            <a href="/m/<?php echo $this->url; ?>/booking">
                                Booking
                                <?php
                                if ($this->selected == 'booking') {
                                    echo '<span class="selected"></span>';
                                }
                                ?>
                            </a>
                        </li>
                    <?php } ?>
                    <li class="<?php
                                if ($this->selected == 'policies') {
                                    echo 'active';
                                }
                                ?>">
                        <a href="/m/<?php echo $this->url; ?>/policies">
                            Policies
                            <?php
                            if ($this->selected == 'policies') {
                                echo '<span class="selected"></span>';
                            }
                            ?>
                        </a>
                    </li>
                    <li class="<?php
                                if ($this->selected == 'aboutus') {
                                    echo 'active';
                                }
                                ?>">
                        <a href="/m/<?php echo $this->url; ?>/aboutus">
                            About us
                            <?php
                            if ($this->selected == 'aboutus') {
                                echo '<span class="selected"></span>';
                            }
                            ?>
                        </a>
                    </li>
                    <li class="<?php
                                if ($this->selected == 'contactus') {
                                    echo 'active';
                                }
                                ?>">
                        <a href="/m/<?php echo $this->url; ?>/contactus">
                            Contact us
                            <?php
                            if ($this->selected == 'contactus') {
                                echo '<span class="selected"></span>';
                            }
                            ?>
                        </a>
                    </li>
                </ul>
            </div>
            <!-- END HORIZONTAL RESPONSIVE MENU -->
        </div>
        <!-- END SIDEBAR -->
        <!-- BEGIN CONTENT -->
        <div class="page-content-wrapper">