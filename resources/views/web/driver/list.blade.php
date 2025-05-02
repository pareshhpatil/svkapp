@extends('layouts.web')
@section('header')
<link rel="stylesheet" href="/assets/vendor/libs/select2/select2.css" />

@endsection
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-8">
                <h4 class="fw-bold py-2"><span class="text-muted fw-light">Drivers /</span> List</h4>
            </div>

        </div>
        <div class="card invoice-preview-card">

            <div class="card-body">

                <div class="card-datatable table-responsive pt-0">

                    <table id="datatable" class="datatables-basic  table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Photo</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Mobile</th>
                                <th>Location</th>
                                <th>Address</th>
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
                ajax: '/master/driver/ajax',
                columns: [{
                        data: 'id'
                    },
                    {
                        data: ''
                    },
                    {
                        data: 'name'
                    },
                    {
                        data: 'email'
                    },
                    {
                        data: 'mobile'
                    },
                    {
                        data: 'location'
                    },
                    {
                        data: 'address'
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
                            '<li><a href="/master/driver/update/'+full.id+'" class="dropdown-item">Edit</a></li>' +
                            '<li><a target="_BLANK" href="https://app.svktrv.in/login/driver/{{$enc}}/'+full.id+'" class="dropdown-item">Login</a></li>' +
                            '<li><a href="javascript:;" onclick="' + "deleteride(" + full.id + ");" + '" class="dropdown-item text-danger delete-record">Delete</a></li>' +
                            '</ul>' +
                            '</div>'
                        );
                    }
                },{
            targets: 1, // Photo column
            title: 'Photo',
            orderable: false,
            searchable: false,
            render: function(data, type, full, meta) {
                console.log(full.name,full.photo);
                if(full.photo=='null' || full.photo=='' || full.photo==null)
                {
                    full.photo='https://app.svktrv.in/assets/img/map-male.png';
                }
                return (
                    '<img src="' + full.photo + '" alt="' + full.name + '" class="rounded-circle avatar-lg" style="object-fit: cover; width: 50px; height: 50px;">'
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


    function deleteride(id) {
        response = confirm('Are you sure you want to delete this item?');
        if (response == true) {
            $.get("/master/driver/delete/" + id, function(data, status) {
                dt_basic.destroy();
                datatable();
            });
        }
    }
</script>


@endsection
