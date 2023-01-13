<?php
include_once '../legacy_app/view/footer/footer_common.php';
if($_SESSION['session_date_format'] == 'M d yyyy'){
    $r_date_format = 'MMM DD YYYY';
}else{
    $r_date_format = 'DD MMM YYYY';
}
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
<script src="/assets/global/plugins/jquery-multi-select/js/jquery.multi-select.js"></script>

<script src="/assets/global/scripts/swipez.js" type="text/javascript"></script>
<script src="/assets/admin/layout/scripts/layout.js<?php echo $this->fileTime('js', 'layout'); ?>" type="text/javascript"></script>
<script src="/assets/admin/layout/scripts/quick-sidebar.js<?php echo $this->fileTime('js', 'quick-sidebar'); ?>" type="text/javascript"></script>
<script src="/assets/admin/layout/scripts/demo.js<?php echo $this->fileTime('js', 'demo'); ?>" type="text/javascript"></script>
<script src="/assets/admin/layout/scripts/colorbox.js<?php echo $this->fileTime('js', 'colorbox'); ?>"></script>
<script src="/assets/admin/pages/scripts/ellipsis.js"></script>

<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="/assets/admin/layout/scripts/datatable-dropdown-actions-responsive.js" type="text/javascript"></script>


<?php if ($this->hide_first_col == true) { ?>
    <script>
        var hide_first_col = true;
    </script>
<?php } ?>
<?php if ($this->table_default_length == true) { ?>
    <script>
        var default_length = -1;
    </script>
<?php } ?>
<?php if (isset($this->datatablejs)) { ?>
    <script src="/assets/admin/pages/scripts/<?php echo $this->datatablejs; ?>.js<?php echo $this->fileTime('pages', "assets/admin/pages/scripts/" . $this->datatablejs . ".js"); ?>"></script>
<?php } ?>
<script src="/assets/admin/pages/scripts/components-pickers.js?version=2"></script>
<script src="/assets/admin/pages/scripts/clipboard/dist/clipboard.min.js"></script>

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
    var sum_col = '<?php echo $this->sum_column; ?>';
    var show_all_records = '<?php echo $this->show_all_records?>';
    var list_name = '<?php echo $this->list_name?>';
    jQuery(document).ready(function () {


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
    });

    var dateRanges = new Array();
    dateRanges['Today'] = [moment(), moment()];
    dateRanges['Yesterday'] = [moment().subtract(1, 'days'), moment().subtract(1, 'days')];
    dateRanges['Last 7 Days'] =  [moment().subtract(6, 'days'), moment()];
    dateRanges['Last 30 Days'] =  [moment().subtract(29, 'days'), moment()];
    dateRanges['This Month'] = [moment().startOf('month'), moment().endOf('month')];
    dateRanges['Last Month'] = [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')];
    if (show_all_records==1){
        var start_date = '<?php echo $this->startDate; ?>';
        dateRanges['All records'] = [start_date, moment()];
    }

    $('#daterange').daterangepicker({
        <?php if ($this->show_all_records!=1){ ?>
            "dateLimit": {
                "day": 365
            },
        <?php } ?>
        locale: {format: '<?php echo $r_date_format; ?>'},
        "autoApply": true,
        ranges: dateRanges,
    }, function (start, end, label) {
        //console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
    });
</script>

<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>
