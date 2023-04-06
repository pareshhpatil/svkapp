<!-- BEGIN PAGE LEVEL STYLES -->
<script src="/assets/global/scripts/swipez.js" type="text/javascript"></script>
<script src="/assets/admin/layout/scripts/layout.js<?php echo $this->fileTime('js', 'layout'); ?>" type="text/javascript"></script>
<script src="/assets/admin/layout/scripts/quick-sidebar.js<?php echo $this->fileTime('js', 'quick-sidebar'); ?>" type="text/javascript"></script>
<!-- END PAGE LEVEL STYLES -->

<!-- BEGIN THEME STYLES -->
<link href="/assets/admin/layout/css/custom.css<?php echo $this->fileTime('css', 'custom'); ?>" rel="stylesheet" type="text/css" />
<link href="/assets/admin/layout/css/movingintotailwind.css<?php echo $this->fileTime('css', 'movingintotailwind'); ?>" rel="stylesheet" type="text/css" />
<!-- END THEME STYLES -->


<link rel="shortcut icon" href="/images/briq.ico" />

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
                                                } elseif ($this->package_reminder_days == 0) {
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

                        <!-- <a itemprop="url" title="Swipez" href="<?php echo $this->server_name; ?>">
                            <img style="max-height: 50px;" class="logo-default hidden-xs" src="<?php
                                                                                                if ($this->merchant_logo != '' && $this->usertype == 'patron') {
                                                                                                    echo $this->merchant_logo;
                                                                                                } else {
                                                                                                    echo '/assets/admin/layout/img/logo.png?v=4';
                                                                                                }
                                                                                                ?>" alt="Swipez Online Payment" title="Swipez Online Payment Solutions">
                        </a> -->

                        <li class="dropdown hidden-sm hidden-md hidden-lg">
                            <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="" data-close-others="true">
                                <i class="fa fa-user-circle"></i>
                                <span><?php echo $this->talk_email_id; ?> </span>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="/merchant/profile/settings">
                                        <i class="fa fa-cog"></i> Setting </a>
                                </li>
                                <li>
                                    <a href="/logout">
                                        <i class="fa fa-sign-out"></i> <?php echo $menu['logout']; ?> </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    <ul class="nav navbar-nav pull-right">
                        <!-- BEGIN NOTIFICATION DROPDOWN -->
                        <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->

                        <!-- END NOTIFICATION DROPDOWN -->
                        <!-- BEGIN USER LOGIN DROPDOWN -->
                        <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->

                        <!-- <li class="dropdown hidden-xs">
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
                        </li> -->
                        <li class="dropdown hidden-xs">
                            <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="" data-close-others="true">
                                <i class="fa fa-user-circle"></i>
                                <span><?php echo $this->talk_email_id; ?> </span>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="/merchant/profile/settings">
                                        <i class="fa fa-cog"></i> Setting </a>
                                </li>
                                <?php if(env('BRIQ_URL_REDIRECTION') != true) { ?>
                                <li>
                                    <a href="/logout">
                                        <i class="fa fa-sign-out"></i> <?php echo $menu['logout']; ?> </a>
                                </li>
                                <?php } ?>
                            </ul>
                        </li>

                        <li class="dropdown ">
                            <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="" data-close-others="true">
                                <i class="fa fa-th"></i>
                            </a>
                            <ul class="dropdown-menu" style="min-width: 230px;">
                                <!-- <li class="dropdown-title">
                                    Active Apps
                                </li>
                                <li class="divider">
                                </li> -->
                                <li>
                                    <a href="#">
                                        <div style="position: relative;">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="v-icon__component theme--light" style="color: rgb(33, 33, 64); caret-color: rgb(33, 33, 64);">
                                                <path d="M0 2C0 0.89543 0.895431 0 2 0H22C23.1046 0 24 0.895431 24 2V22C24 23.1046 23.1046 24 22 24H2C0.89543 24 0 23.1046 0 22V2Z" fill="#212140"></path>
                                                <path d="M6.14467 10.153C5.58857 10.153 5.08239 10.3759 4.7031 10.7407V7.63672H3.92725V9.64452V11.487V13.2269V13.4948V14.4834H4.7031V14.015C5.08239 14.3798 5.58857 14.6027 6.14467 14.6027C7.32041 14.6027 8.2736 13.6065 8.2736 12.3777C8.2736 11.1489 7.32041 10.153 6.14467 10.153ZM6.08637 13.8486C5.29324 13.8486 4.65021 13.1871 4.65021 12.3713C4.65021 11.5553 5.29324 10.8937 6.08637 10.8937C6.87951 10.8937 7.52253 11.5553 7.52253 12.3713C7.52253 13.1871 6.87951 13.8486 6.08637 13.8486Z" fill="white"></path>
                                                <path d="M20.1299 15.5633L19.7623 14.9265L19.0953 15.3116V13.7344V13.4073V11.5625V11.4911V10.2984H18.3195V10.7116C17.9437 10.3635 17.4494 10.1523 16.9077 10.1523C15.732 10.1523 14.7788 11.1483 14.7788 12.3771C14.7788 13.6059 15.732 14.602 16.9077 14.602C17.4494 14.602 17.9437 14.3907 18.3195 14.0424V16.1611H19.0953V16.1588L19.096 16.16L20.1299 15.5633ZM16.9662 13.8482C16.1731 13.8482 15.5301 13.1866 15.5301 12.3708C15.5301 11.5548 16.1731 10.8933 16.9662 10.8933C17.7593 10.8933 18.4024 11.5548 18.4024 12.3708C18.4024 13.1866 17.7593 13.8482 16.9662 13.8482Z" fill="white"></path>
                                                <path d="M13.792 10.2988H13.0161V14.4876H13.792V10.2988Z" fill="white"></path>
                                                <path d="M13.5237 9.17351C13.8056 9.10696 13.9801 8.82456 13.9135 8.54274C13.847 8.26091 13.5646 8.0864 13.2827 8.15295C13.0009 8.21949 12.8264 8.5019 12.893 8.78372C12.9595 9.06554 13.2419 9.24006 13.5237 9.17351Z" fill="white"></path>
                                                <path d="M10.8718 10.2252C10.4202 10.3406 10.1441 10.5972 9.98142 10.8223V10.2992H9.20557V14.488H9.98142V12.3915C10.0029 11.7787 10.1764 11.5371 10.3254 11.3591C10.4438 11.2175 10.6232 11.1011 10.7077 11.0505C10.7492 11.0304 10.7929 11.0108 10.839 10.9923C11.4651 10.7939 11.9431 11.0793 11.9431 11.0793L12.3601 10.3683C12.36 10.3683 11.7628 9.99777 10.8718 10.2252Z" fill="white"></path>
                                            </svg>
                                            <span style="padding:10px; margin: -3px; position: absolute; top: 50%; -ms-transform: translateY(-50%); transform: translateY(-50%);">Planning & Forecasting</span>
                                        </div>

                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                    <div style="position: relative;">
                                        <img src="<?php echo '/assets/admin/layout/img/spend.png'; ?>">
                                        <span style="padding:10px; margin: -3px; position: absolute; top: 50%; -ms-transform: translateY(-50%); transform: translateY(-50%);">Spend Management</span>
                                    </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                    <div style="position: relative;">
                                    <img src="<?php echo '/assets/admin/layout/img/cash.png'; ?>">
                                        <span style="padding:10px; margin: -3px; position: absolute; top: 50%; -ms-transform: translateY(-50%); transform: translateY(-50%);">Briq Cash</span>
                                    </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                    <div style="position: relative;">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="v-icon__component theme--light" style="color: rgb(33, 33, 64); caret-color: rgb(33, 33, 64);">
                                            <path d="M18.7219 10.7219C18.7219 11.9259 18.4053 13.0559 17.8509 14.0333L15.4224 10.3087C15.097 9.80952 14.3031 9.80952 13.9777 10.3087L13.1193 11.6253L12.5097 12.5434L10.5239 9.44277C10.1752 8.89826 9.32465 8.89826 8.9759 9.44277L6.09657 13.9392C5.5747 12.9836 5.27808 11.8874 5.27808 10.7219C5.27808 7.00949 8.28757 4 12 4C15.7124 4 18.7219 7.00949 18.7219 10.7219Z" fill="#212140"></path>
                                            <path d="M20.8799 19.9969L15.4225 11.6269C15.2597 11.3774 14.9798 11.2525 14.7 11.2525C14.4201 11.2525 14.1403 11.3774 13.9774 11.6269L12.5149 13.8701L10.524 10.7609C10.3496 10.4887 10.0498 10.3525 9.74996 10.3525C9.45014 10.3525 9.15033 10.4887 8.97595 10.7609L3.12875 19.892C2.77522 20.4441 3.19147 21.1526 3.86957 21.1526H20.1883C20.8211 21.1526 21.2098 20.5032 20.8799 19.9969ZM4.78897 19.8026L9.74996 12.0555L14.7109 19.8026H4.78897ZM16.3141 19.8026L13.3137 15.1169L14.7 12.9907L19.1415 19.8026H16.3141Z" fill="#212140"></path>
                                        </svg>
                                        <span style="padding:10px; margin: -3px; position: absolute; top: 50%; -ms-transform: translateY(-50%); transform: translateY(-50%);">Discover</span>
                                    </div>
                                    </a>
                                </li>
                                <!-- <?php foreach ($this->active_service_list as $ml) {
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


                                <?php } ?> -->
                                <!-- <li class="dropdown-button">
                                    <a class="btn green dd-btn" href="/merchant/dashboard/home"> Available Apps</a>
                                </li> -->


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