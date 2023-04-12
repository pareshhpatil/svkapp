if (typeof order_col !== 'undefined')
{

} else
{
    var order_col = 0;
}

if (typeof first_colsort !== 'undefined')
{
    var first_colsort = false;
} else
{
    var first_colsort = true;
}

if (typeof default_length !== 'undefined')
{
} else
{
    var default_length = 10;
}

if (typeof hide_first_col !== 'undefined')
{
    var is_show = false;
} else
{
    var is_show = true;
}

var offsortcol = -1;
if($('#table-small').attr('sort-offcol'))
{
    var sort = $('#table-small').attr('sort-offcol');
    offsortcol=parseInt(sort);
}
var TableAdvanced = function () {

    var init_table_small = function () {
        var table = $('#table-small');
        /* Table tools samples: https://www.datatables.net/release-datatables/extras/TableTools/ */

        /* Set tabletools buttons and button container */

        $.extend(true, $.fn.DataTable.TableTools.classes, {
            "container": "btn-group tabletools-dropdown-on-portlet",
            "buttons": {
                "normal": "btn btn-sm default",
                "disabled": "btn btn-sm default disabled"
            },
            "collection": {
                "container": "DTTT_dropdown dropdown-menu tabletools-dropdown-menu"
            }
        });
        var oTable = table.dataTable({
            // Internationalisation. For more info refer to http://datatables.net/manual/i18n
            "language": {
                "aria": {
                    "sortAscending": ": activate to sort column ascending",
                    "sortDescending": ": activate to sort column descending"
                },
                "emptyTable": "No data available in table",
                "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                "infoEmpty": "No entries found",
                "infoFiltered": "(filtered1 from _MAX_ total entries)",
                "lengthMenu": "Show _MENU_ entries",
                "search": "Search:",
                "zeroRecords": "No matching records found"
            },
            // Or you can use remote translation file
            //"language": {
            //   url: '//cdn.datatables.net/plug-ins/3cfcc339e89/i18n/Portuguese.json'
            //},

            "order": [
                [order_col, 'desc']
            ],
            "lengthMenu": [
                [5, 10, 15, 20, -1],
                [5, 10, 15, 20, "All"] // change per page values here
            ],

            "columnDefs": [{
                    "targets": 0,
                    "orderable": first_colsort
                },{
                    "targets": [0],
                    "visible": is_show
                },
                { 'bSortable': false, 'targets': offsortcol }
            ],

            // set the initial value
            "pageLength": default_length,
            "dom": "<'row' <'col-md-12'T>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // horizobtal scrollable datatable

            // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
            // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js). 
            // So when dropdowns used the scrollable div should be removed. 
            //"dom": "<'row' <'col-md-12'T>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r>t<'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",

            "tableTools": {
                "sSwfPath": "/assets/global/plugins/datatables/extensions/TableTools/swf/copy_csv_xls_pdf.swf",
                "aButtons": [{
                        "sExtends": "pdf",
                        "sButtonText": "PDF"
                    }]
            }
        });
        var tableWrapper = $('#sample_1_wrapper'); // datatable creates the table wrapper by adding with id {your_table_jd}_wrapper

        tableWrapper.find('.dataTables_length select').select2(); // initialize select2 dropdown
    }


    return {
//main function to initiate the module
        init: function () {

            if (!jQuery().dataTable) {
                return;
            }

            console.log('me 1');
            init_table_small();
            console.log('me 2');
        }

    };
}();