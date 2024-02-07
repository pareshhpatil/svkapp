@extends('layouts.web')
@section('header')
<link rel="stylesheet" href="/assets/vendor/libs/select2/select2.css" />
<link rel="stylesheet" href="/assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.css" />
<script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.min.js"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>


@endsection
@section('content')
<div class="row">
    <div class="col-lg-12">
        <form action="/roster/routedownload" id="frm" method="post">
            @csrf
            <div class="row">
                <div class="col-lg-4">
                    <h4 class="fw-bold py-2"><span class="text-muted fw-light">Route /</span> List</h4>
                </div>
                <div class="col-lg-4 pull-right">
                    <input type="text" onchange="reloadDate(this.value)" name="date_range" id="bs-rangepicker-time" value="{{$current_date_range}}" class="form-control" />
                </div>
                <div class="col-lg-2 pull-right">
                    <select name="project_id" id="select2Basic" onchange="reload(this.value)" class="select2 form-select input-sm" data-allow-clear="true">
                        @if(!empty($project_list))
                        @foreach($project_list as $v)
                        <option @if($project_id==$v->project_id) selected @endif @if(count($project_list)==1) selected @endif value="{{$v->project_id}}">{{$v->name}}</option>
                        @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-lg-2 pull-right">
                    <button type="submit" name="downloadRoster" class="btn btn-primary waves-effect waves-light pull-right">Download Roster</button>
                </div>

            </div>
        </form>
        <div class="card invoice-preview-card">

            <div class="card-body">

                <div class="card-datatable table-responsive pt-0">

                    <table id="datatable" class="datatables-basic  table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Type</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Start Location</th>
                                <th>Total Passenger</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>


@endsection

@section('footer')


<script src="/assets/vendor/libs/moment/moment.js"></script>
<script src="/assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.js"></script>
<script src="/assets/vendor/libs/sortablejs/sortable.js"></script>
<script src="/assets/vendor/libs/select2/select2.js"></script>

<script>
    var dt_basic;
    var project_id = 0;
    var date = '{{$current_date_range}}';

    function reload(id) {
        project_id = id;
        dt_basic.destroy();
        datatable();
    }

    function deleteride(id) {
        response = confirm('Are you sure you want to delete this item?');
        if (response == true) {
            $.get("/ride/delete/" + id, function(data, status) {
                dt_basic.destroy();
                datatable();
            });
        }
    }

    function reloadDate(id) {
        try {
            date = id;
            dt_basic.destroy();
            datatable();
        } catch (o) {}

    }
    // datatable (jquery)
    $(function() {

        datatable();

    });


    function datatable() {
        var dt_basic_table = $('#datatable');
        // dt_basic;

        // DataTable with buttons
        // --------------------------------------------------------------------
        if (dt_basic_table.length) {
            dt_basic = dt_basic_table.DataTable({
                ajax: '/ajax/route/' + project_id + '/' + date + '/{{$status}}',
                columns: [{
                        data: 'id'
                    },
                    {
                        data: 'title'
                    },
                    {
                        data: 'type'
                    },
                    {
                        data: 'date'
                    },
                    {
                        data: 'display_start_time'
                    },
                    {
                        data: 'start_location'
                    },
                    {
                        data: 'total_passengers'
                    },
                    {
                        data: 'description'
                    },
                    {
                        data: ''
                    }
                ],
                columnDefs: [{
                    // Actions
                    targets: -1,
                    title: 'Actions',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, full, meta) {
                        return (
                            '<div class="d-inline-block">' +
                            '<a href="javascript:;" class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="text-primary ti ti-dots-vertical"></i></a>' +
                            '<ul class="dropdown-menu dropdown-menu-end m-0">' +
                            '<li><a target=_BLANK href="/ride/details/' + full.id + '" class="dropdown-item">Details</a></li>' +
                            '<li><a href="/ride/update/' + full.id + '" class="dropdown-item">Edit</a></li>' +
                            '<li><a href="javascript:;" onclick="' + "deleteride(" + full.id + ");" + '" class="dropdown-item text-danger delete-record">Delete</a></li>' +
                            '</ul>' +
                            '</div>'
                        );
                    }
                }],
                order: [
                    [0, 'desc']
                ],
                displayLength: 10,
                lengthMenu: [10, 25, 50, 75, 100],
            });
        }
    }
</script>
<script>
    var bsRangePickerTime = $('#bs-rangepicker-time');
    bsRangePickerTime.daterangepicker({
        timePicker: true,
        timePickerIncrement: 30,
        locale: {
            format: 'DD MMM YYYY h:mm A'
        },
        opens: isRtl ? 'left' : 'right'
    });
</script>

@endsection
