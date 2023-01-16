if (typeof hide_first_col !== 'undefined')
{
    var is_show = false;
} else
{
    var is_show = true;
}
var TableAdvanced = function() {

    var init_table_small = function() {
        var table = $('#table-small-nosort');
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

        var oTable = table.DataTable({
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

            "lengthMenu": [
                [5, 10, 15, 20, -1],
                [5, 10, 15, 20, "All"] // change per page values here
            ],
            "columnDefs": [{
                    "targets": [0],
                    "visible": is_show
                },{ 'orderable': false, 'targets': -1 }],
            // set the initial value
            "pageLength": 10,
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
            },
            "stateSave": true,
            //"stateDuration":-1,
            "stateSaveCallback": function (settings, oData) {
                    $.ajax( {
                    url: "/ajax/saveDatatableSearchParam",
                    data : {state: JSON.stringify(oData), 'list_name' : list_name},
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
                url: '/ajax/getDatatableSearchParam/'+list_name,
                dataType: 'json',
                success: function (json) {
                        o = json;
                        if(json!=null) {
                                oTable.search(json.search['search']).draw();
                                oTable.page.len(json.length);
                                oTable.order(json.order).draw();
                        }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                        //console.log(thrownError);
                },
                } );
                return o;
            }
        });

        var tableWrapper = $('#sample_1_wrapper'); // datatable creates the table wrapper by adding with id {your_table_jd}_wrapper

        tableWrapper.find('.dataTables_length select').select2(); // initialize select2 dropdown
    }


    return {
        //main function to initiate the module
        init: function() {

            if (!jQuery().dataTable) {
                return;
            }

            console.log('me 1');

            init_table_small();


            console.log('me 2');
        }

    };

}();