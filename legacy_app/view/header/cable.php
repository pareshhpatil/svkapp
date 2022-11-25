<!DOCTYPE html>
<html>

    <head>
        <title><?php echo $this->title; ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="shortcut icon" type="image/png" href="/assets/cable/images/favicon.png" />

        <link rel="stylesheet" type="text/css" href="/assets/cable/css/bootstrap.min.css" />
        <link rel="stylesheet" type="text/css" href="/assets/cable/css/jquery.fancybox.css" />
        <link rel="stylesheet" type="text/css" href="/assets/cable/css/owl.carousel.css" />
        <link rel="stylesheet" type="text/css" href="/assets/cable/css/owl.theme.css" />
        <link rel="stylesheet" type="text/css" href="/assets/cable/css/animate.css<?php echo $this->fileTime('pages', "assets/cable/css/animate.css"); ?>" />
        <link rel="stylesheet" type="text/css" href="/assets/cable/css/style.css<?php echo $this->fileTime('pages', "assets/cable/css/style.css"); ?>" />
        <link rel="stylesheet" type="text/css" href="/assets/cable/css/responsive.css<?php echo $this->fileTime('pages', "assets/cable/css/responsive.css"); ?>" />
        <link rel="stylesheet" type="text/css" href="/assets/cable/css/fontawesome-all.css">
        <link href="/assets/global/plugins/font-awesome/css/font-awesome.min.css?version=4.7" rel="stylesheet" type="text/css" />

        <script type="text/javascript" src="/assets/admin/layout/scripts/plan.js<?php echo $this->fileTime('js', 'plan'); ?>"></script>

    </head>

    <body>


        <header class="mainHeader wow fadeInDown">
            <nav class="navbar navbar-inverse myNavBar" id="myHeader">
                <div class="container">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>

                        <a class="navbar-brand" href="#">
                            <?php if ($this->logo != '') { ?>
                                <img src="/uploads/images/landing/<?php echo $this->logo; ?>" style="max-height: 50px;" alt="" />
                            <?php } else { ?>
                                <?php echo $this->company_name; ?>
                            <?php } ?>
                        </a>
                    </div>
                    <?php if ($this->is_loggedin == true) { ?>
                        <div class="collapse navbar-collapse" id="myNavbar">
                            <ul class="nav navbar-nav navbar-right">
                                <li><a href="/merchant/dashboard/home">Dashboard</a></li>
                                <li><a href="/cable/settopbox">Set Top Box</a></li>
                                <li class="dropdownMenu"><a href="#"><i class="fa fa-user"></i> My Account</a>
                                    <ul class="subMenu">
                                        <li><a href="#"><i class="fa fa-user"></i> <?php echo $this->display_name; ?></a></li>
                                        <li><a href="/cable/logout"><i class="fa fa-sign-out"></i> Log out</a></li>
                                    </ul>
                                </li>
                            </ul>
                            <!--navbar-right-->
                        </div>
                    <?php } ?>
                    <!--navbar-collapse-->
                </div>
                <!--container-->
            </nav>
        </header>