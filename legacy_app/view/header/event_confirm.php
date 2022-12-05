<html lang="en">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->
    <head>
        <meta charset="utf-8"/>
        <title><?php echo $this->title; ?> | Swipez</title>
        <link rel="canonical" href="<?php echo $this->server_name; ?>/<?php echo $this->canonical; ?>"/>
        <?php if(isset($this->global_tag)){ echo $this->global_tag; } ?>
        
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8">
        <meta name="description" content="<?php echo $this->description; ?>">
        <meta property="fb:app_id" content="1838175649816458" />
        <meta property="og:url" content="<?php echo $this->event_link; ?>" />
        <meta property="og:title" content="<?php echo $this->title; ?>" />
        <meta property="og:type" content="event" /> 
        <meta property="og:description" content="" />
        <?php
        if ($this->banner_path != '') {
            ?>
            <meta property="og:image" content="<?php echo $this->host_link; ?>/uploads/images/logos/<?php echo $this->banner_path; ?>" />
        <?php } ?>



        <!-- BEGIN THEME STYLES -->
        <link href="/assets/global/plugins/font-awesome/css/font-awesome.min.css?version=4.7" rel="stylesheet" type="text/css"/>
        <link href="/assets/admin/layout/css/swipezapp.min.css<?php echo $this->fileTime('css', 'swipezapp.min'); ?>" rel="stylesheet" type="text/css" />
        <link href="/assets/admin/layout/css/custom.css<?php echo $this->fileTime('css', 'custom'); ?>" rel="stylesheet" type="text/css"/>
        <link href="/assets/admin/layout/css/movingintotailwind.css<?php echo $this->fileTime('css', 'movingintotailwind'); ?>" rel="stylesheet" type="text/css"/>

        <!-- BEGIN SWIPEZ Scripts -->
        <?php
    foreach ($this->js as $js) {
        echo '<script src="/assets/admin/layout/scripts/' . $js . '.js'.$this->fileTime('js',$js). '" type="text/javascript"></script>';
    }
    ?>
        <!-- END SWIPEZ STYLES -->
        <link rel="shortcut icon" href="/favicon.ico"/>
        <?php if ($this->env == 'PROD') { ?>
            <!-- Google Code for Remarketing Tag -->
            <!--------------------------------------------------
            Remarketing tags may not be associated with personally identifiable information or placed on pages related to sensitive categories. See more information and instructions on how to setup the tag on: http://google.com/ads/remarketingsetup
            --------------------------------------------------->
            <script type="text/javascript">
                /* <![CDATA[ */
                var google_conversion_id = 817807754;
                var google_custom_params = window.google_tag_params;
                var google_remarketing_only = true;
                /* ]]> */
            </script>
            <script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
            </script>
            <noscript>
        <div style="display:inline;">
            <img height="1" width="1" style="border-style:none;" alt="" src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/817807754/?guid=ON&amp;script=0"/>
        </div>
        </noscript>
        
    <?php 
     
    } ?>
   
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="page-header-fixed page-quick-sidebar-over-content" style="font-size: 14px;line-height: 2;">



