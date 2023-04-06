<?php
include_once '../legacy_app/view/header/header_common.php';
?>
<?php
if ($this->header_file) {
    foreach ($this->header_file as $file) {
        include_once '../legacy_app/view/header/' . $file . '.php';
    }
}
?>

<link href="/assets/admin/pages/css/invoice.css" rel="stylesheet" type="text/css" />
<link href="/assets/admin/layout/css/custom.css<?php echo $this->fileTime('css', 'custom'); ?>" rel="stylesheet" type="text/css" />
<link href="/assets/admin/layout/css/movingintotailwind.css<?php echo $this->fileTime('css', 'movingintotailwind'); ?>" rel="stylesheet" type="text/css" />
<link href="/assets/global/plugins/select2/select2.css" rel="stylesheet" type="text/css" />
<link href="/assets/global/css/plugins.css" rel="stylesheet" type="text/css" />
<!-- END THEME STYLES -->
<link rel="shortcut icon" href="/images/briq.ico" />
</head>

<body class="page-quick-sidebar-over-content" <?php if ($this->is_secure == 1) { ?> oncontextmenu="return false;" <?php } ?> style="<?php echo $this->minwidth; ?>">
    <?php
    include_once '../legacy_app/view/header/body_tag.php';
    ?>
    <div class="clearfix">
    </div>
    <div class="page-container">