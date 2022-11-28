<meta name="csrf-token" content="<?= $_SESSION['csrf_token'] ?>">
<a class="iframe" id="commentlink"></a>
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

<script src="/assets/global/scripts/swipez.js" type="text/javascript"></script>
<script src="/assets/admin/layout/scripts/layout.js<?php echo $this->fileTime('js', 'layout'); ?>" type="text/javascript"></script>
<script src="/assets/admin/layout/scripts/quick-sidebar.js<?php echo $this->fileTime('js', 'quick-sidebar'); ?>" type="text/javascript"></script>
<script src="/assets/admin/layout/scripts/demo.js<?php echo $this->fileTime('js', 'demo'); ?>" type="text/javascript"></script>
<script src="/assets/admin/layout/scripts/colorbox.js<?php echo $this->fileTime('js', 'colorbox'); ?>"></script>
<script src="/assets/admin/pages/scripts/ellipsis.js"></script>
<?php if (isset($this->datatablejs)) { ?>
        <script src="/assets/admin/pages/scripts/<?php echo $this->datatablejs; ?>.js<?php echo $this->fileTime('pages', "assets/admin/pages/scripts/" . $this->datatablejs . ".js"); ?>"></script>
<?php } ?>
<script src="/assets/admin/pages/scripts/components-pickers.js?version=2"></script>
<script src="/assets/admin/pages/scripts/clipboard/dist/clipboard.min.js"></script>
<script src="/assets/admin/layout/scripts/datatable-dropdown-actions-responsive.js" type="text/javascript"></script>


<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

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
        var is_show = true;
        <?php if ($this->hide_first_col == true) { ?>
                var is_show = false;
        <?php } ?>
        
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
                <?php if (isset($this->datatablejs)) { ?>
                        TableAdvanced.init();
                <?php } ?>
                var total_sum = '';
                ComponentsPickers.init();
                UIBootstrapGrowl.init();
                $('#column_name').multiselect({
                        numberDisplayed: 2,
                });
                $('.multi_column').multiselect({
                        numberDisplayed: 2,
                });
                $('[data-toggle="popover"]').popover();
                var table = $('#example').DataTable({
                        "processing": true,
                        "language": {
                                "processing": '<div class="loading" id="loader">Loading&#8230;</div>' //add a loading image,simply putting <img src="loader.gif" /> tag.
                        },
                        <?php if (isset($this->sortdisable)) { ?> "bSort": false,
                        <?php } ?> "serverSide": true,
                        "order": [
                                [0, "desc"]
                        ],
                        "lengthMenu": [
                                [10, 50, 100, -1],
                                [10, 50, 100, "All"]
                        ],
                        "ajax": {
                                "url": "/datatable/<?php echo $this->ajaxpage; ?>",
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
                        "columnDefs": [{
                                        "targets": [0],
                                        "searchable": false

                                },
                                {
                                        "targets": [0],
                                        "visible": is_show
                                },
                                <?php if (isset($this->hide_col_index)) { ?>{
                                        "targets": [<?php echo $this->hide_col_index;?>],
                                        "visible": false
                                        },
                                <?php } ?>
                                <?php if (!isset($this->lastsortenable)) { ?> {
                                                'orderable': false,
                                                'targets': -1
                                        }
                                <?php } ?>
                        ],
                        drawCallback: function(settings) {
                                try {
                                        var api = this.api();
                                        // Remove the formatting to get integer data for summation
                                        var intVal = function(i) {
                                                return typeof i === 'string' ?
                                                        i.replace(/[\$,]/g, '') * 1 :
                                                        typeof i === 'number' ?
                                                        i : 0;
                                        };
                                        // Total over all pages
                                        total = api
                                                .column(sum_col)
                                                .data()
                                                .reduce(function(a, b) {
                                                        return intVal(a) + intVal(b);
                                                }, 0);
                                        // Total over this page
                                        pageTotal = api
                                                .column(sum_col, {
                                                        page: 'current'
                                                })
                                                .data()
                                                .reduce(function(a, b) {
                                                        b=b.replace(/\D/g, "");
                                                        return intVal(a) + intVal(b);
                                                }, 0);
                                        total = settings.json.totalSum.toLocaleString('en-IN', {
                                                maximumFractionDigits: 2,
                                        });
                                        pagetotal = pageTotal.toLocaleString('en-IN', {
                                                maximumFractionDigits: 2,
                                        });
                                        // Update footer
                                        total_sum = '<span style="float: left;">Total amount: ' + total + '</span>';
                                        $(api.column(1).footer()).html(
                                                total_sum + '<span style="float: right;">Current page amount: ' + pagetotal + '</span>'
                                        );
                                } catch (o) {

                                }
                        }


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
        $('#daterange').daterangepicker({
                "dateLimit": {
                        "day": 365
                },
                locale:{
                        format: '<?php echo $r_date_format; ?>'
                },
                "autoApply": true,
                ranges: {
                        'Today': [moment(), moment()],
                        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
        }, function(start, end, label) {
                //console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
        });
</script>

<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->

</html>