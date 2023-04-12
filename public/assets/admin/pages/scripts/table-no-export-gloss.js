var TableAdvanced = function() {

    var initTable3 = function() {
        var table = $('#table-no-export-gloss');

        /*
         * Initialize DataTables, with no sorting on the 'details' column
         */
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
                "search": "",
                "zeroRecords": "No matching records found"
            },
            "bPaginate": false,
            "bLengthChange": false,
            "bInfo": false,
            "bSort": false,
            "scrollY": "300",
            "bAutoWidth": false,
            "order": [
                [0, 'desc']
            ],
            "columnDefs": [
            { 'orderable': false, 'targets': -1 }
            ],
            // set the initial value
            "pageLength": 100,
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

            console.log('me 1');

            initTable3();


            console.log('me 2');
        }

    };

}();