<script src="/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
<script src="/assets/global/plugins/jquery-migrate.min.js" type="text/javascript"></script>
<script src="/assets/global/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
<script src="/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="/assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
<script src="/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="/assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="/assets/global/plugins/jquery.cokie.min.js" type="text/javascript"></script>
<script src="/assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
<script src="/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<script src="/assets/global/scripts/swipez.js" type="text/javascript"></script>
<script src="/assets/admin/layout/scripts/layout.js<?php echo $this->fileTime('js', 'layout'); ?>" type="text/javascript"></script>
<script src="/assets/admin/layout/scripts/quick-sidebar.js<?php echo $this->fileTime('js', 'quick-sidebar'); ?>" type="text/javascript"></script>
<script src="/assets/admin/layout/scripts/demo.js<?php echo $this->fileTime('js', 'demo'); ?>" type="text/javascript"></script>
<script src="/assets/admin/layout/scripts/colorbox.js<?php echo $this->fileTime('js', 'colorbox'); ?>"></script>

<!-- END PAGE LEVEL PLUGINS -->

<script>
    jQuery(document).ready(function() {
        $(".iframe").colorbox({iframe: true, width: "80%", height: "90%"});
        Swipez.init(); // init swipez core components
        Layout.init(); // init current layout
        QuickSidebar.init(); // init quick sidebar
        Demo.init(); // init demo features
    });
</script>

<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>