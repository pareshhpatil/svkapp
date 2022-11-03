<!-- stylesheet start here-->

<!-- stylesheet end here-->
<!-- js start here-->

<link href="/assets/admin/layout/css/trade_india_custom.css<?php echo $this->fileTime('css', 'trade_india_custom'); ?>" rel="stylesheet" type="text/css"/>
<!-- js end here-->
<?php $menu = $this->lang['menu']; ?>

<body class="page-header-fixed page-quick-sidebar-over-content">
    <?php
    include_once '../legacy_app/view/header/body_tag.php';
    ?>
    <!-- BEGIN HEADER -->
    <?php
    include_once '../legacy_app/view/header/tradeindia_header.php';
    ?>
    <!-- END HEADER -->
    <div class="clearfix">
    </div>
    <!-- BEGIN CONTAINER -->
    <div class="">
        <!-- BEGIN SIDEBAR -->
        <?php include VIEW . $this->usertype . '/menu_tradeindia.php'; ?>
        <!-- END SIDEBAR -->
        <div class="page-content-wrapper">
            <div class="padding-top-div hidden-xs"></div>


