if (typeof hide_first_col !== 'undefined')
{
    var is_show = false;
} else
{
    var is_show = true;
}

// if (showLastRememberSearchCriteria !== '')
// {
//     var showLastRememberSearchCriteria = true;
// } else
// {
//     var showLastRememberSearchCriteria = false;
// }

var TableAdvanced = function() {

    var initTable3 = function() {
        var table = $('#table-no-export');

        /*
         * Initialize DataTables, with no sorting on the 'details' column
         */
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
            
            "order": [
                [0, 'desc']
            ],
            "lengthMenu": [
                [5, 10, 15, 20, -1],
                [5, 10, 15, 20, "All"] // change per page values here
            ],
            "columnDefs": [{
                    "targets": [0],
                    "visible": is_show
                },
            { 'orderable': false, 'targets': -1 }
            ],
            // set the initial value
            "pageLength": 10,
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
        var tableWrapper = $('#sample_3_wrapper'); // datatable creates the table wrapper by adding with id {your_table_jd}_wrapper

        tableWrapper.find('.dataTables_length select').select2(); // initialize select2 dropdown

        /* Add event listener for opening and closing details
         * Note that the indicator for showing which row is open is not controlled by DataTables,
         * rather it is done here
         */

    }


    return {
        //main function to initiate the module
        init: function() {

            if (!jQuery().dataTable) {
                return;
            }

            //console.log('me 1');

            initTable3();


            //console.log('me 2');
        }

    };

}();