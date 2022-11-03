<!DOCTYPE html>
<html lang="en" class="no-js">
    <head>
        <meta charset="utf-8"/>
        <title><?php echo $this->title; ?></title>
        <meta name="description" content="<?php echo $this->description; ?>">
        <meta name="author" content="Swipez">
        <link rel="canonical" href="<?php echo $this->server_name; ?>/<?php echo $this->canonical; ?>"/>


        <?php if (isset($this->og_type)) { ?>
            <meta property="fb:app_id" content="1838175649816458" />
            <meta property="og:url" content="<?php echo $this->server_name; ?><?php echo $this->og_link; ?>" />
            <meta property="og:title" content="<?php echo $this->title; ?>" />
            <meta property="og:type" content="<?php echo $this->og_type; ?>" /> 
            <meta property="og:description" content="<?php echo $this->description; ?>" />
            <meta property="og:image" content="<?php echo $this->server_name; ?>/images/features/<?php echo $this->og_image; ?>" />
        <?php } ?>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport"/>
        <link href="/assets/global/plugins/font-awesome/css/font-awesome.min.css?version=4.7" rel="stylesheet" type="text/css"/>
        <link href="/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css"/>
        <link href="/assets/frontend/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="/assets/global/css/components.css" id="style_components" rel="stylesheet" type="text/css"/>
        <link href="/assets/global/plugins/owl.carousel/assets/owl.carousel.css" rel="stylesheet">
        <link href="/assets/global/plugins/slider-revolution-slider/rs-plugin/css/settings.css" rel="stylesheet">
        <link href="/assets/global/plugins/cubeportfolio/cubeportfolio/css/cubeportfolio.min.css" rel="stylesheet">
        <link href="/assets/frontend/onepage2/css/layout.css?version=0.1" rel="stylesheet" type="text/css"/>
        <link href="/assets/frontend/onepage2/css/custom.css<?php echo $this->fileTime('aa', '/assets/frontend/onepage2/css/custom.css'); ?>" rel="stylesheet" type="text/css"/>
        <?php
        if ($this->nomobile_redirect == 1) {
            ?>
            <link href="/assets/frontend/onepage2/css/shorturl.css<?php echo $this->fileTime('aa', '/assets/frontend/onepage2/css/shorturl.css'); ?>" rel="stylesheet" type="text/css"/>
            <?php
        }
        ?>
        <link href="/assets/admin/layout/css/colorbox.css<?php echo $this->fileTime('css', 'colorbox'); ?>" rel="stylesheet" type="text/css"/>
        <script src="/assets/admin/layout/scripts/shorturl.js<?php echo $this->fileTime('js', 'shorturl'); ?>"></script>
    <?php
    if ($this->env == 'PROD') {
        $m_url = "https://m.swipez.in/" . $this->feature_page;
    } else {
        $m_url = "https://mh7sak8am43.swipez.in";
    }
    ?>

    <?php
    if ($this->nomobile_redirect != 1) {
        ?>
        <script type="text/javascript">
            if (screen.width <= 600) {
                window.location = "<?php echo $m_url; ?>";
            }
        </script>
        <?php
    }
    ?>
    <link rel="shortcut icon" href="/favicon.ico"/>
</head>
<body class="page-header-fixed"><header class="page-header"><nav class="navbar navbar-fixed-top" role="navigation"><div class="container"><div class="navbar-header page-scroll"><button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse"><span class="sr-only">Toggle navigation</span><span class="toggle-icon"><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></span></button><div class="logo" itemscope="" itemtype="http://schema.org/Organization"><a itemprop="url" class="navbar-brand" title="Swipez" href="<?php echo $this->server_name; ?>">   <img class="logo-default" height="73" width="200" src="/assets/frontend/onepage2/img/logo_default.png" alt="Swipez Online Payment" title="Swipez Online Payment Solutions"><img class="logo-scroll"  height="73" width="200" src="/assets/frontend/onepage2/img/logo_scroll.png" alt="Swipez Online Payment" title="Swipez Online Payment Solutions"></a></div></div><div class="collapse navbar-collapse navbar-responsive-collapse"><ul class="nav navbar-nav"><li class="dropdown page-scroll"><a class="dropdown-toggle" style="border-color: transparent;" data-toggle="dropdown" data-target="#" href="javascript:;">Products </a><ul class="dropdown-menu" style="background-color: #275770;margin-top: -20px;">                             <li style="padding: 0 0;"><a href="<?php echo $this->server_name; ?>/invoicing-software" style="font-size: 14px;">Invoicing Software</a></li><li style="padding: 0 0;"><a href="<?php echo $this->server_name; ?>/event-management-software" style="font-size: 14px;">Event Management Software</a></li><li style="padding: 0 0;"><a href="<?php echo $this->server_name; ?>/booking-management-software" style="font-size: 14px;">Booking Management Software</a></li><li style="padding: 0 0;"><a href="<?php echo $this->server_name; ?>/url-shortener" style="font-size: 14px;color: white;">URL Shortener</a></li><li style="padding: 0 0;"><a href="<?php echo $this->server_name; ?>/website-builder" style="font-size: 14px;">Website Builder</a></li><li style="padding: 0 0;"><a href="<?php echo $this->server_name; ?>/coupon-management-software" style="font-size: 14px;">Coupon Management Software</a></li></ul></li><li class="page-scroll"><a href="<?php echo $this->server_name; ?>/pricing" >Pricing</a></li><li class="page-scroll"><a href="<?php echo $this->support_link; ?>/">FAQs</a></li> <li class="page-scroll"><a href="<?php echo $this->server_name; ?>/partner" >Partner</a></li><?php if ($this->isLoggedIn == TRUE) { ?><li class="page-scroll"><a href="<?php echo $this->server_name; ?><?php echo "/" . $this->usertype . '/dashboard'; ?>">Dashboard</a></li> <li class="page-scroll"><a href="<?php echo $this->server_name; ?>/logout">Logout</a></li>  <?php } else { ?><li class="page-scroll"><a href="<?php echo $this->server_name; ?>/merchant/register">Register</a></li> <li class="page-scroll"><a href="<?php echo $this->server_name; ?>/login">Login</a></li>  <?php } ?></ul></div></div></nav></header>
    <!-- Google Tag Manager (noscript) -->
