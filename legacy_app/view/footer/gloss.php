<?php
include_once '../legacy_app/view/footer/footer_common.php';
?>

<!-- BEGIN PAGE LEVEL PLUGINS -->
<script type="text/javascript" src="/assets/global/plugins/select2/select2.min.js"></script>
<script type="text/javascript" src="/assets/global/plugins/datatables/media/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/assets/global/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js"></script>
<script type="text/javascript" src="/assets/global/plugins/datatables/extensions/Scroller/js/dataTables.scroller.min.js"></script>
<script type="text/javascript" src="/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js"></script>
<script type="text/javascript" src="/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>

<script type="text/javascript" src="/assets/global/plugins/jquery-multi-select/js/bootstrap-multiselect.js"></script>
<script type="text/javascript" src="/assets/global/plugins/jquery-multi-select/js/bootstrap-multiselect-collapsible-groups.js"></script>

<script src="/assets/global/plugins/bootstrap-growl/jquery.bootstrap-growl.min.js"></script>
<!-- END PAGE LEVEL SCRIPTS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="/assets/admin/pages/scripts/ui-bootstrap-growl.js"></script>
<script src="/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script>

<script src="/assets/global/scripts/swipez.js" type="text/javascript"></script>
<script src="/assets/admin/layout/scripts/layout.js<?php echo $this->fileTime('js', 'layout'); ?>" type="text/javascript"></script>
<script src="/assets/admin/layout/scripts/quick-sidebar.js<?php echo $this->fileTime('js', 'quick-sidebar'); ?>" type="text/javascript"></script>
<script src="/assets/admin/layout/scripts/demo.js<?php echo $this->fileTime('js', 'demo'); ?>" type="text/javascript"></script>
<script src="/assets/admin/layout/scripts/colorbox.js<?php echo $this->fileTime('js', 'colorbox'); ?>"></script>
<script src="/assets/admin/pages/scripts/ellipsis.js"></script>
<?php if (isset($this->datatablejs)) { ?>
    <script src="/assets/admin/pages/scripts/<?php echo $this->datatablejs; ?>.js"></script>
<?php } ?>
<script src="/assets/admin/pages/scripts/components-pickers.js?version=2"></script>
<script src="/assets/admin/pages/scripts/clipboard/dist/clipboard.min.js"></script>

<script>
    var clipboard = new Clipboard('.btn');

    clipboard.on('success', function(e) {
        console.log(e);
    });

    clipboard.on('error', function(e) {
        console.log(e);
    });

    // $(window).load(function() {
    //     $('form').get(0).reset(); //clear form data on page load
    // });
</script>
<script>
    var sum_col = '<?php echo $this->sum_column; ?>';
    jQuery(document).ready(function() {


        $(".iframe").colorbox({iframe: true, width: "80%", height: "90%"});
        Swipez.init(); // init swipez core components
        Layout.init(); // init current layout
        QuickSidebar.init(); // init quick sidebar
        Demo.init(); // init demo features
<?php if (isset($this->datatablejs)) { ?>
            TableAdvanced.init();
<?php } ?>

        ComponentsPickers.init();
        UIBootstrapGrowl.init();

        $('#column_name').multiselect({
            numberDisplayed: 2,
        });
        $('[data-toggle="popover"]').popover();

        $('.dataTables_filter input').addClass('search-query');
        $('.dataTables_filter input').attr('placeholder', 'Search Chapter');
        $('.dataTables_filter input').attr('id', 'search_id');

    });

    //window.location.replace("index.html");
//window.history.replaceState( {}, '', '/google.com');
</script>

<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>
