<!-- BEGIN PAGE LEVEL STYLES -->
<script src="/assets/global/scripts/swipez.js" type="text/javascript"></script>
<script src="/assets/admin/layout/scripts/layout.js<?php echo $this->fileTime('js', 'layout'); ?>" type="text/javascript"></script>
<script src="/assets/admin/layout/scripts/quick-sidebar.js<?php echo $this->fileTime('js', 'quick-sidebar'); ?>" type="text/javascript"></script>
<!-- END PAGE LEVEL STYLES -->

<!-- BEGIN THEME STYLES -->
<link href="/assets/admin/layout/css/custom.css<?php echo $this->fileTime('css', 'custom'); ?>" rel="stylesheet" type="text/css" />
<link href="/assets/admin/layout/css/movingintotailwind.css<?php echo $this->fileTime('css', 'movingintotailwind'); ?>" rel="stylesheet" type="text/css" />
<!-- END THEME STYLES -->


<link rel="shortcut icon" href="/favicon.ico" />

<script type="text/javascript" src="https://apiv2.popupsmart.com/api/Bundle/364244" async></script>
</head>
<?php $menu = $this->lang['menu']; ?>

<body class="page-header-fixed <?php if ($this->hide_menu != 1) { ?>page-quick-sidebar-over-content<?php } ?>">
    <?php
    include_once '../legacy_app/view/header/body_tag.php';
    ?>
    <!-- BEGIN HEADER -->
    <?php if ($this->package_expire == true) { ?>
        <div class="center packageexpire">
            Your current plan has expired. Please renew your plan to start using your account - <a href="<?php echo $this->server_name . '/merchant/package/confirm/' . $this->package_link; ?>">Renew plan</a> or <a href="<?php echo $this->server_name . $this->choose_package_link; ?>">Pick another plan</a>
        </div>
    <?php } ?>
    <?php if (isset($this->package_reminder_days)) { ?>
        <div class="center packageexpire">
            Your current plan will expire in <?php if ($this->package_reminder_days == 1) {
                                                    echo "Tomorrow";
                                                }elseif ($this->package_reminder_days == 0) {
                                                    echo "Today";
                                                } else {
                                                    echo $this->package_reminder_days . " days";
                                                } ?> . Please renew your package to keep your account active - <a href="<?php echo $this->server_name . '/merchant/package/confirm/' . $this->package_link; ?>">Renew plan</a> or <a href="<?php echo $this->server_name . $this->choose_package_link; ?>">Pick another plan</a>
        </div>
    <?php } ?>

    <div class="page-header navbar navbar-fixed-top">
        <!-- BEGIN HEADER INNER -->
        <div class="page-header-inner">
            <!-- BEGIN LOGO -->
            <div class="page-logo" style="width:100%;padding-right: 0px;" itemscope="" itemtype="http://schema.org/Organization">

                <div class="top-menu" style="width: 100%;">
                    <a href="javascript:;" style="margin-top: 20px;" class="menu-toggler responsive-toggler pull-left" data-toggle="collapse" data-target=".navbar-collapse">
                    </a>
                    <ul class="nav navbar-nav pull-left">


                        <!-- BEGIN NOTIFICATION DROPDOWN -->
                        <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->

                        <!-- END NOTIFICATION DROPDOWN -->
                        <!-- BEGIN USER LOGIN DROPDOWN -->
                        <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->

                        <a itemprop="url" title="Swipez" href="<?php echo $this->server_name; ?>">
                            <img style="max-height: 50px;" class="logo-default hidden-xs" src="<?php
                                                                                                if ($this->merchant_logo != '' && $this->usertype == 'patron') {
                                                                                                    echo $this->merchant_logo;
                                                                                                } else {
                                                                                                    echo '/assets/admin/layout/img/logo.png?v=2';
                                                                                                }
                                                                                                ?>" alt="Swipez Online Payment" title="Swipez Online Payment Solutions">
                        </a>

                        <li class="dropdown dropdown-user pull-left hidden-lg hidden-sm visible-xs">
                            <a style="cursor:auto;" class="dropdown-toggle blank">
                                <?php
                                if (strlen($this->display_name) > 26) {
                                    echo substr($this->display_name, 0, 24) . '...';
                                } else {
                                    echo $this->display_name;
                                }
                                ?>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav navbar-nav pull-right">
                        <!-- BEGIN NOTIFICATION DROPDOWN -->
                        <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->

                        <!-- END NOTIFICATION DROPDOWN -->
                        <!-- BEGIN USER LOGIN DROPDOWN -->
                        <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->

                        <li class="dropdown hidden-xs">
                            <a href="javascript:;" class="dropdown-toggle blank white" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                <?php echo $this->display_name; ?>
                                <?php if ($this->has_master_login == 1) { ?>
                                    <i class="fa fa-angle-down"></i>
                                <?php } ?>
                            </a>
                            <?php if ($this->has_master_login == 1) {
                            ?>
                                <ul class="dropdown-menu">
                                    <?php foreach ($this->master_login_list as $ml) {
                                    ?>
                                        <li class="<?php echo $ml['active']; ?>">
                                            <?php if ($ml['active'] == 'active') { ?>
                                                <a href="#">
                                                    <b><?php echo $ml['display_name']; ?></b>
                                                    <p><?php echo $ml['email_id']; ?></p>
                                                </a>
                                            <?php } else { ?>
                                                <a href="/merchant/profile/switchlogin/<?php echo $ml['key']; ?>">
                                                    <b><?php echo $ml['display_name']; ?></b>
                                                    <p><?php echo $ml['email_id']; ?></p>
                                                </a>

                                            <?php } ?>

                                        </li>
                                    <?php } ?>
                                </ul>
                            <?php } ?>
                        </li>

                        <li class="dropdown ">
                            <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                <i class="fa fa-th"></i>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="dropdown-title">
                                    Active Apps
                                </li>
                                <li class="divider">
                                </li>
                                <?php foreach ($this->active_service_list as $ml) {
                                ?>

                                    <?php if ($ml['service_id'] == $this->service_id) { ?>
                                        <li class="active">
                                            <a href="#">
                                                <?php echo $ml['title']; ?>
                                            </a>
                                        </li>
                                    <?php } else { ?>
                                        <li>
                                            <a href="/merchant/dashboard/index/<?php echo $ml['key']; ?>">
                                                <?php echo $ml['title']; ?>
                                            </a>
                                        </li>
                                    <?php } ?>


                                <?php } ?>
                                <li class="dropdown-button">
                                    <a class="btn green dd-btn" href="/merchant/dashboard/home"> Available Apps</a>
                                </li>


                            </ul>
                        </li>

                        <li class="dropdown ">
                            <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                <i class="fa fa-user-circle"></i>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a target="_BLANK" href="<?php echo $this->support_link; ?>/">
                                        <i class="fa fa-question-circle"></i> Help </a>
                                </li>
                                <li>
                                    <a href="/logout">
                                        <i class="fa fa-sign-out"></i> <?php echo $menu['logout']; ?> </a>
                                </li>
                            </ul>
                        </li>
                        <!-- END USER LOGIN DROPDOWN -->

                        <!-- BEGIN USER HELP DROPDOWN -->
                        <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->

                        <!-- END USER HELP DROPDOWN -->
                    </ul>

                </div>


                <div class="menu-toggler sidebar-toggler hide">
                    <!-- DOC: Remove the above "hide" to enable the sidebar toggler button on header -->
                </div>

            </div>

            <!-- END LOGO -->
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

        <?php if ($this->hide_menu != 1) {
            include VIEW . $this->usertype . '/menu.php'; ?>
            <div class="page-content-wrapper">
            <?php } ?>
            <!-- END SIDEBAR -->