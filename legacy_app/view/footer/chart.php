<?php
include_once '../legacy_app/view/footer/footer_common.php';
?>
<script src="/assets/global/scripts/swipez.js" type="text/javascript"></script>
<script src="/assets/admin/layout/scripts/layout.js?version=2" type="text/javascript"></script>
<script src="/assets/admin/layout/scripts/quick-sidebar.js?version=2" type="text/javascript"></script>
<script src="/assets/admin/layout/scripts/demo.js?version=2" type="text/javascript"></script>
<script src="/assets/admin/pages/scripts/components-pickers.js?version=2"></script>
<!-- BEGIN PAGE LEVEL PLUGINS -->

<script type="text/javascript" src="/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="/assets/global/scripts/swipez.js" type="text/javascript"></script>
<script src="/assets/admin/layout/scripts/demo.js?version=2" type="text/javascript"></script>

<!-- END PAGE LEVEL PLUGINS -->

<script>
    jQuery(document).ready(function() {
        Swipez.init(); // init swipez core components
        Layout.init(); // init current layout
        QuickSidebar.init(); // init quick sidebar
        Demo.init(); // init demo features
        ComponentsPickers.init();
    });
</script>

<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>
