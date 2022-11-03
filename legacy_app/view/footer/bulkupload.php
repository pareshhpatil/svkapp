<?php
include_once '../legacy_app/view/footer/footer_common.php';
?>
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script src="/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script>
<script type="text/javascript" src="/assets/global/plugins/datatables/media/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/assets/global/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js"></script>
<script type="text/javascript" src="/assets/global/plugins/datatables/extensions/Scroller/js/dataTables.scroller.min.js"></script>
<script type="text/javascript" src="/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js"></script>
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<?php if ($this->hide_first_col == true) { ?>
    <script>
        var hide_first_col = true;
    </script>
<?php } ?>
<script src="/assets/global/scripts/swipez.js" type="text/javascript"></script>
<script src="/assets/admin/layout/scripts/layout.js<?php echo $this->fileTime('js', 'layout'); ?>" type="text/javascript"></script>
<script src="/assets/admin/layout/scripts/quick-sidebar.js<?php echo $this->fileTime('js', 'quick-sidebar'); ?>" type="text/javascript"></script>
<script src="/assets/admin/pages/scripts/<?php echo $this->datatablejs; ?>.js<?php echo $this->fileTime('pages', "assets/admin/pages/scripts/" . $this->datatablejs . ".js"); ?>"></script>
<script src="/assets/admin/layout/scripts/demo.js<?php echo $this->fileTime('js', 'demo'); ?>" type="text/javascript"></script>

<script>
    jQuery(document).ready(function() {
        Swipez.init(); // init swipez core components
        Layout.init(); // init current layout
        QuickSidebar.init(); // init quick sidebar
        Demo.init(); // init demo features
        TableAdvanced.init();
    });

    $('#template_ids').select2({}).on('select2:open', function(e) {
        pind = $(this).index();
        if (document.getElementById('templatelists' + pind)) {} else {
            $('.select2-results').append('<div class="wrapper" > <a href="/merchant/template/newtemplate" id="templatelists' + pind + '"   class="clicker" >Add new format</a> </div>');
        }
    });
</script>

<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>