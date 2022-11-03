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

<script src="/assets/global/scripts/swipez.js" type="text/javascript"></script>
<script src="/assets/admin/layout/scripts/layout.js<?php echo $this->fileTime('js', 'layout'); ?>" type="text/javascript"></script>
<script src="/assets/admin/layout/scripts/quick-sidebar.js<?php echo $this->fileTime('js', 'quick-sidebar'); ?>" type="text/javascript"></script>
<script src="/assets/admin/layout/scripts/demo.js<?php echo $this->fileTime('js', 'demo'); ?>" type="text/javascript"></script>
<script src="/assets/admin/layout/scripts/colorbox.js<?php echo $this->fileTime('js', 'colorbox'); ?>"></script>
<script src="/assets/admin/pages/scripts/ellipsis.js"></script>
<script src="/assets/admin/pages/scripts/<?php echo $this->datatablejs; ?>.js"></script>
<script src="/assets/admin/pages/scripts/components-pickers.js?version=2"></script>
<script src="/assets/admin/pages/scripts/clipboard/dist/clipboard.min.js"></script>
<script src="/assets/admin/layout/scripts/booking.js<?php echo $this->fileTime('js', 'booking'); ?>" type="text/javascript"></script>
<script>
    var clipboard = new Clipboard('.btn');

    clipboard.on('success', function(e) {
        console.log(e);
    });

    clipboard.on('error', function(e) {
        console.log(e);
    });
</script>
<script>
    var sum_col = '<?php echo $this->sum_column; ?>';
    jQuery(document).ready(function() {
        $(".iframe").colorbox({
            iframe: true,
            width: "80%",
            height: "90%"
        });
        Swipez.init(); // init swipez core components
        Layout.init(); // init current layout
        QuickSidebar.init(); // init quick sidebar
        Demo.init(); // init demo features
        TableAdvanced.init();
        ComponentsPickers.init();
        UIBootstrapGrowl.init();
        $('#column_name').multiselect({
            numberDisplayed: 2,
        });
        $('[data-toggle="popover"]').popover();

        var table = $('#example').DataTable({
            "processing": true,
            "language": {
                "processing": '<div class="loading" id="loader">Loading&#8230;</div>' //add a loading image,simply putting <img src="loader.gif" /> tag.
            },
            "serverSide": true,
            "ajax": {
                "url": "/datatable/slot.php",
                "type": "POST",
                data: {
                    'csrf_token': '<?= $_SESSION['csrf_token'] ?>'
                },
                dataSrc: function(json) {
                    if (json.csrf_token !== undefined)
                        $('meta[name=csrf_token]').attr("content", json.csrf_token);
                    return json.data;
                }
            },

        });
        var search_thread = null;
        $(".dataTables_filter input")
            .unbind()
            .bind("input", function(e) {
                clearTimeout(search_thread);
                var len = this.value.length;
                search_thread = setTimeout(function() {
                    var dtable = $("#example").dataTable().api();
                    var elem = $(".dataTables_filter input");
                    if (len >= 3) {
                        return dtable.search($(elem).val()).draw();
                    }
                    // Ensure we clear the search if they backspace far enough
                    if (len == 0) {
                        dtable.search("").draw();
                    }
                    return;
                }, 2000);
            });



    });
</script>

<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->

</html>