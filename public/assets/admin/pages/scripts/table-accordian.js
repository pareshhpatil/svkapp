var TableAdvanced = function() {

   

    var initTable4 = function() {
        var table = $('#table-accordian');

        /* Formatting function for row expanded details */
        function fnFormatDetails(oTable, nTr) {
            var aData = oTable.fnGetData(nTr);
            var key = aData[7];
            var sOut = '<table class="table table-bordered"><tbody><thead><tr><th style="width: 15%;background-color: beige;border:1px solid lightgrey;">Field name&nbsp;</th><th style="width: 20%;background-color: beige;;border:1px solid lightgrey;">Existing value</th><th style="width: 20%;background-color: beige;;border:1px solid lightgrey;">Changed value</th><th style="width: 7%;background-color: beige;;border:1px solid lightgrey;">Status</th><th style="width: 15%;background-color: beige;;border:1px solid lightgrey;text-align: center;"><a onclick="return approve_all(' + key + ');" id="approve_all_' + key + '" class="btn btn-xs green">All <i class="fa fa-check"></i></a>&nbsp;<a onclick="return reject_all(' + key + ');" id="reject_all_' + key + '" class="btn btn-xs red">All <i class="fa fa-remove"></i></a>&nbsp;<a onclick="" style="background-color:lightgrey;cursor:auto;" id="undo_all_' + key + '" class="btn btn-xs grey">All <i class="fa fa-undo"></i></a></th></tr></thead>';
            obj = JSON.parse(json_req);
            try {
                $.each(obj[key], function(index, value) {
                    sOut += '<tr><td style="border:1px solid lightgrey;">' + value.column_name + '</td><td style="border:1px solid lightgrey;">' + value.current_value + ' &nbsp;</td><td style="border:1px solid lightgrey;">' + value.changed_value + '</td><td style="border:1px solid lightgrey;"><span id="status_' + value.change_detail_id + '" \n\
class="label label-sm label-primary">Pending</span>  </td><td style="border:1px solid lightgrey;text-align: center;"><a onclick="return approve(' + value.change_detail_id + ');" id="approve_' + value.change_detail_id + '" class="btn btn-xs green"><i class="fa fa-check"></i></a>&nbsp;<a onclick="return reject(' + value.change_detail_id + ');" id="reject_' + value.change_detail_id + '" class="btn btn-xs red"><i class="fa fa-remove"></i></a>&nbsp;<a onclick="" id="undo_' + value.change_detail_id + '" style="background-color:lightgrey;cursor:auto;" class="btn btn-xs grey"><i class="fa fa-undo"></i></a></td></tr>';
                });
            } catch (o)
            {
                alert(o.message);
            }
            sOut += '</table></td>';
            return sOut;
        }

        /*
         * Insert a 'details' column to the table
         */
        var nCloneTh = document.createElement('th');
        nCloneTh.className = "table-checkbox";

        var nCloneTd = document.createElement('td');
        nCloneTd.innerHTML = '<span class="row-details row-details-close"></span>';

        table.find('thead tr').each(function() {
            this.insertBefore(nCloneTh, this.childNodes[0]);
        });

        table.find('tbody tr').each(function() {
            this.insertBefore(nCloneTd.cloneNode(true), this.childNodes[0]);
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
            "columnDefs": [{
                    "orderable": false,
                    "targets": [0]
                },{ 'orderable': false, 'targets': -1 }
            
            ],
            "order": [
                [1, 'desc']
            ],
            "lengthMenu": [
                [5, 10, 15, 20, -1],
                [5, 10, 15, 20, "All"] // change per page values here
            ],
            // set the initial value
            "pageLength": 10,
        });

        var tableWrapper = $('#sample_4_wrapper'); // datatable creates the table wrapper by adding with id {your_table_jd}_wrapper
        var tableColumnToggler = $('#sample_4_column_toggler');

        /* modify datatable control inputs */
        tableWrapper.find('.dataTables_length select').select2(); // initialize select2 dropdown

        /* Add event listener for opening and closing details
         * Note that the indicator for showing which row is open is not controlled by DataTables,
         * rather it is done here
         */
        table.on('click', ' tbody td .row-details', function() {
            var nTr = $(this).parents('tr')[0];
            if (oTable.fnIsOpen(nTr)) {
                /* This row is already open - close it */
                $(this).addClass("row-details-close").removeClass("row-details-open");
                oTable.fnClose(nTr);
            } else {
                /* Open this row */
                $(this).addClass("row-details-open").removeClass("row-details-close");
                oTable.fnOpen(nTr, fnFormatDetails(oTable, nTr), 'details');
            }
        });

        /* handle show/hide columns*/
        $('input[type="checkbox"]', tableColumnToggler).change(function() {
            /* Get the DataTables object again - this is not a recreation, just a get of the object */
            var iCol = parseInt($(this).attr("data-column"));
            var bVis = oTable.fnSettings().aoColumns[iCol].bVisible;
            oTable.fnSetColumnVis(iCol, (bVis ? false : true));
        });
    }

    

    return {
        //main function to initiate the module
        init: function() {

            if (!jQuery().dataTable) {
                return;
            }

            console.log('me 1');

            initTable4();

            console.log('me 2');
        }

    };

}();