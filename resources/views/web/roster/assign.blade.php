@extends('layouts.web')
@section('header')
<link rel="stylesheet" href="/assets/vendor/libs/select2/select2.css" />
<link rel="stylesheet" href="/assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css" />


@endsection
@section('content')
<div class="row">
    <div class="col-lg-12">
        <form action="" id="frm" method="post">
            <div class="row">
                <div class="col-lg-4">
                    <h4 class="fw-bold py-2"><span class="text-muted fw-light">Roster /</span> Assign</h4>
                </div>
                <div class="col-lg-4 pull-right">
                    <input type="text" name="date" onchange="reloadDate(this.value)" required id="bs-datepicker-autoclose" placeholder="Select Date" class="form-control" />
                </div>
                <div class="col-lg-4 pull-right">
                    @csrf
                    <select name="project_id" id="select2Basic" onchange="reload(this.value)" class="select2 form-select input-sm" data-allow-clear="true">
                        <option value="0">All</option>
                        @if(!empty($project_list))
                        @foreach($project_list as $v)
                        <option value="{{$v->project_id}}">{{$v->name}}</option>
                        @endforeach
                        @endif
                    </select>

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
                                <th>Project Name</th>
                                <th>Title</th>
                                <th>Type</th>
                                <th>Date</th>
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


<div class="modal fade" id="modalCenter" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCenterTitle">Assign Cab</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-3">
                        <label for="nameWithTitle" class="form-label">Driver</label>
                        <select name="driver_id" id="driver_id" class="select2 form-select form-select-lg input-sm" data-allow-clear="true">
                            <option value=""></option>
                            @if(!empty($driver_list))
                            @foreach($driver_list as $v)
                            <option value="{{$v->id}}">{{$v->name}}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="row g-2">
                    <div class="col mb-0">
                        <label for="emailWithTitle" class="form-label">Cab Number</label>
                        <select name="vehicle_id" id="vehicle_id" class="select2 form-select form-select-lg input-sm" data-allow-clear="true">
                            <option value=""></option>
                            @if(!empty($vehicle_list))
                            @foreach($vehicle_list as $v)
                            <option value="{{$v->vehicle_id}}">{{$v->number}}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="closemodal" class="btn btn-label-secondary" data-bs-dismiss="modal">
                    Close
                </button>
                <button type="button" onclick="assignRide();" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>


@endsection

@section('footer')


<script src="/assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js"></script>
<script src="/assets/vendor/libs/select2/select2.js"></script>
<script src="/assets/js/forms-selects.js"></script>

<script>
    var dt_basic;
    var project_id = 0;
    var date = 'na';
    var ride_id = 0;

    function reload(id) {
        project_id = id;


        dt_basic.destroy();
        datatable();
    }

    function reloadDate(id) {
        date = id;
        dt_basic.destroy();
        datatable();
    }
    // datatable (jquery)
    $(function() {

        datatable();

    });

    function setRide(id) {
        ride_id = id;
    }

    function assignRide() {
        driver_id = document.getElementById('driver_id').value;
        cab_id = document.getElementById('vehicle_id').value;
        if (driver_id > 0 && cab_id > 0) {
            $.get("/roster/assign/" + ride_id + "/" + driver_id + "/" + cab_id + "", function(data, status) {
                document.getElementById('closemodal').click();
                dt_basic.destroy();
                datatable();
            });
        }
    }


    function datatable() {
        var dt_basic_table = $('#datatable');
        // dt_basic;

        // DataTable with buttons
        // --------------------------------------------------------------------
        if (dt_basic_table.length) {
            dt_basic = dt_basic_table.DataTable({
                ajax: '/ajax/roster/' + project_id + '/' + date + '/0',
                columns: [{
                        data: 'id'
                    },
                    {
                        data: 'project_name'
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
                        data: 'start_location'
                    },
                    {
                        data: 'total_passengers'
                    },
                    {
                        data: 'status'
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
                            '<button type="button" onclick="setRide(' + full.id + ')" data-bs-toggle="modal" data-bs-target="#modalCenter" class="btn btn-success waves-effect waves-light">Assign</button>'
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
    $('#bs-datepicker-autoclose').datepicker({
        todayHighlight: true,
        autoclose: true,
        format: 'dd MM yyyy',
        orientation: isRtl ? 'auto right' : 'auto left'
    });
</script>

@endsection