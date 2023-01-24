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
<script src="/assets/admin/layout/scripts/datatable-dropdown-actions-responsive.js" type="text/javascript"></script>

<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

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
<?php if ($this->language == 'hindi') { ?>
        var tview = 'देखिये';
        var tedit = 'संपादन';
        var tdelete = 'हटाइये';
<?php } else { ?>
        var tview = 'View';
        var tedit = 'Edit';
        var tdelete = 'Delete';
<?php } ?>
    var sum_col = '<?php echo $this->sum_column; ?>';
    jQuery(document).ready(function () {
        $(".iframe").colorbox({iframe: true, width: "80%", height: "90%"});
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
                "url": "/datatable/customer_list.php",
                "type": "POST",
                data: {
                    'csrf_token': '<?= $_SESSION['csrf_token'] ?>'
                },
                dataSrc: function (json) {
                    if (json.csrf_token !== undefined)
                        $('meta[name=csrf_token]').attr("content", json.csrf_token);
                    return json.data;
                },
            },
            "stateSave": true,
            //"stateDuration":-1,
            "stateSaveCallback": function (settings, oData) {
                    $.ajax( {
                    url: "/ajax/saveDatatableSearchParam",
                    data : {state: JSON.stringify(oData), 'list_name' : '<?php echo $this->list_name?>'},
                    dataType: "json",
                    type: "POST",
                    success: function () {
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                            //console.log(thrownError);
                    },
                    } );
            },
            "stateLoadCallback": function (settings) {
                    var o;
                    $.ajax( {
                    url: '/ajax/getDatatableSearchParam/<?php echo $this->list_name?>',
                    dataType: 'json',
                    success: function (json) {
                            o = json;
                            if(json!=null) {
                                table.page.len(json.length);
                                table.order(json.order).draw();
                                table.search(json.search['search']).draw();
                            }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                            //console.log(thrownError);
                    },
                    } );
                    return o;
            }
        });

        var dtable = $("#example").dataTable().api();

// Grab the datatables input box and alter how it is bound to events
        $(".dataTables_filter input")
                .unbind() // Unbind previous default bindings
                .bind("input", function (e) { // Bind our desired behavior
                    // If the length is 3 or more characters, or the user pressed ENTER, search
                    if (this.value.length >= 3) {
                        // Call the API search function
                        dtable.search(this.value).draw();
                    }
                    // Ensure we clear the search if they backspace far enough
                    if (this.value == "") {
                        dtable.search("").draw();
                    }
                    return;
                });
    });

    $('#daterange').daterangepicker({
        "dateLimit": {
            "day": 365
        },
        locale: {format: 'DD MMM YYYY'},
        "autoApply": true,
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, function (start, end, label) {
        //console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
    });
</script>

<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>
