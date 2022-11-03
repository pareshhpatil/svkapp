<!DOCTYPE html>

<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->

<head>
    <meta charset="utf-8" />
    <title><?php if(isset($this->company_name)) { echo $this->company_name. ' | ' ;}?><?php echo $this->title; ?></title>
    <link rel="canonical" href="<?php echo $this->server_name; ?>/<?php echo $this->canonical; ?>" />
    <meta name="description" content="<?php echo $this->description; ?>">
    <meta name="author" content="Swipez">
    <?php if ($this->env == 'PROD') { ?>
        <?php if ($this->logged_in==true) {
        ?>
        <script>
        window.dataLayer = window.dataLayer || [];
        dataLayer.push({'package_type': '<?php if (isset($this->package_type)) {
        echo $this->package_type;   } ?>','user_type': '<?php if (isset($this->user_package_type)) {
        echo $this->user_package_type;   } ?>'});
        </script>
        <?php } ?>
        <!-- Google Tag Manager -->
        <script>
            (function(w, d, s, l, i) {
                w[l] = w[l] || [];
                w[l].push({
                    'gtm.start': new Date().getTime(),
                    event: 'gtm.js'
                });
                var f = d.getElementsByTagName(s)[0],
                    j = d.createElement(s),
                    dl = l != 'dataLayer' ? '&l=' + l : '';
                j.async = true;
                j.src =
                    'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
                f.parentNode.insertBefore(j, f);
            })(window, document, 'script', 'dataLayer', 'GTM-KJ2KHBQ');
        </script>
        <!-- End Google Tag Manager -->
    <?php } ?>
    <?php if (isset($this->global_tag)) {
        echo $this->global_tag;
    } ?>
    <style>
        #HW_badge_cont{position:absolute!important}#HW_badge{height:18px!important;width:18px!important;line-height:18px!important;font-size:14px!important;top:6px!important;left:5px!important;background:#f99b36!important;border-radius:30px!important}
    </style>

    
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta http-equiv="Content-type" content="text/html; charset=utf-8">
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <?php
    foreach ($this->header_files as $file) {
        echo $file;
    }
    ?>
    
    <link href="/assets/global/plugins/font-awesome/css/font-awesome.min.css?version=4.7" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/admin/layout/css/swipezapp.min.css<?php echo $this->fileTime('css', 'swipezapp.min'); ?>" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css" />

    <?php
    foreach ($this->js as $js) {
        echo '<script src="/assets/admin/layout/scripts/' . $js . '.js' . $this->fileTime('js', $js) . '" type="text/javascript"></script>';
    }
    ?>
