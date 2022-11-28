



<?php
include_once '../legacy_app/view/footer/footer_common.php';
?>
<!-- BEGIN PAGE LEVEL PLUGINS -->

<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="/assets/global/scripts/swipez.js" type="text/javascript"></script>
<script src="/assets/admin/layout/scripts/layout.js<?php echo $this->fileTime('js', 'layout'); ?>" type="text/javascript"></script>
<script src="/assets/admin/layout/scripts/demo.js<?php echo $this->fileTime('js', 'demo'); ?>" type="text/javascript"></script>
<script src="/assets/admin/pages/scripts/components-pickers.js?version=2"></script>
<link href="/assets/admin/layout/css/colorbox.css?version=1579611014" rel="stylesheet" type="text/css"/>

<script src="/assets/admin/layout/scripts/colorbox.js<?php echo $this->fileTime('js', 'colorbox'); ?>"></script>

<script>
    var clipboard = new Clipboard('.btn');

    clipboard.on('success', function (e) {
        console.log(e);
    });

    clipboard.on('error', function (e) {
        console.log(e);
    });
</script>
<script>
    jQuery(document).ready(function () {
        $(".iframe").colorbox({iframe: true, width: "80%", height: "90%"});
        Swipez.init(); // init swipez core components
        QuickSidebar.init(); // init quick sidebar
        Demo.init(); // init demo features
        ComponentsPickers.init();



    });

<?php
if (isset($this->jsscript)) {
    echo $this->jsscript;
}
?>
</script>

</body>
<!-- END BODY -->
</html>
