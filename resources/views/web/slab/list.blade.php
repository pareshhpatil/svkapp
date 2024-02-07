@extends('layouts.web')
@section('header')
<link rel="stylesheet" href="/assets/vendor/libs/select2/select2.css" />

@endsection
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-8">
                <h4 class="fw-bold py-2"><span class="text-muted fw-light">Slab /</span> List</h4>
            </div>
            <div class="col-lg-4 pull-right">
                <form action="" id="frm" method="post">
                    @csrf
                    <select name="project_id" id="select2Basic" onchange="reload(this.value)" class="select2 form-select input-sm" data-allow-clear="true">
                        <option value="0">All</option>
                        @if(!empty($project_list))
                        @foreach($project_list as $v)
                        <option @if(count($project_list)==1) selected @endif value="{{$v->project_id}}">{{$v->name}}</option>
                        @endforeach
                        @endif

                    </select>
                </form>
            </div>
        </div>
        <div class="card invoice-preview-card">

            <div class="card-body">

                <div class="card-datatable table-responsive pt-0">

                    <table id="datatable" class="datatables-basic  table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Location</th>
                                <th>Car type</th>
                                <th>Company slab</th>
                                <th>Driver slab</th>
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


<script>
    var dt_basic;
    var project_id = 0;

    function reload(id) {
        project_id = id;


        dt_basic.destroy();
        datatable();
    }
    // datatable (jquery)
    $(function() {

        datatable();

    });

    function deleteride(id) {
        response = confirm('Are you sure you want to delete this item?');
        if (response == true) {
            $.get("/master/zone/delete/" + id + "/zone_id", function(data, status) {
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
                ajax: '/master/slab/ajax/' + project_id,
                columns: [{
                        data: 'zone_id'
                    },
                    {
                        data: 'zone'
                    },
                    {
                        data: 'car_type'
                    },
                    {
                        data: 'company_slab'
                    },
                    {
                        data: 'driver_slab'
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
                            '<li><a href="/master/slab/update/' + full.zone_id + '" class="dropdown-item">Edit</a></li>' +
                            '<li><a href="javascript:;" onclick="' + "deleteride(" + full.zone_id + ");" + '" class="dropdown-item text-danger delete-record">Delete</a></li>' +
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


@endsection
