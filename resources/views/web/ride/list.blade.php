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
                    <h4 class="fw-bold py-2"><span class="text-muted fw-light">Ride /</span> Assign</h4>
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
                        <option @if(count($project_list)==1) selected @endif value="{{$v->project_id}}">{{$v->name}}</option>
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
                <div class="row g-2">
                    <div class="col mb-0">
                        <label for="emailWithTitle" class="form-label">Escort</label>
                        <select name="escort_id" id="escort_id" class="form-select input-sm" data-allow-clear="true">
                            <option value="0">Select Escort</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="closemodal" class="btn btn-label-secondary" data-bs-dismiss="modal">
                    Close
                </button>
                <button type="button" id="assignride" onclick="assignRide();" class="btn btn-primary">Save changes</button>
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
        if(id=='')
        {
            id=0;
        }
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

    function setRide(id, project_id) {
        ride_id = id;
        $('#escort_id')
            .empty()
            .append('<option selected="selected" value="0">Select Escort</option>');
        $.get("/ajax/passenger/" + project_id + "/0/2", function(data, status) {
            array = JSON.parse(data);
            array.data.forEach((row, index) => {
                $('#escort_id')
                    .append('<option  value="' + row.id + '">' + row.employee_name + ' - ' + row.location + '</option>');
            });
        });
    }

    function assignRide() {
        driver_id = document.getElementById('driver_id').value;
        cab_id = document.getElementById('vehicle_id').value;
        escort_id = document.getElementById('escort_id').value;
        if (driver_id > 0 && cab_id > 0) {
            $('#assignride').prop('disabled', true);
            $.get("/ride/assign/" + ride_id + "/" + driver_id + "/" + cab_id + "/" + escort_id, function(data, status) {
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
                ajax: '/ajax/route/' + project_id + '/' + date + '/5',
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
                            '<div class="d-inline-block"><a href="javascript:;" class="btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="text-primary ti ti-dots-vertical"></i></a><ul class="dropdown-menu dropdown-menu-end m-0"><li><a href="/ride/details/'+full.id+'" class="dropdown-item">Details</a></li></ul></div>'                            
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
