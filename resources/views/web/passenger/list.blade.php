@extends('layouts.web')
@section('header')
<link rel="stylesheet" href="/assets/vendor/libs/select2/select2.css" />

@endsection
@section('content')
<div class="row">
    <div class="col-lg-12">
        <h4 class="fw-bold py-2"><span class="text-muted fw-light">Passengers /</span> List</h4>
        <div class="card invoice-preview-card">

            <div class="card-body">
                
                <div class="card-datatable table-responsive pt-0">

                    <table class="datatables-basic  table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Gender</th>
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
    // datatable (jquery)
    $(function() {
        var dt_basic_table = $('.datatables-basic'),
            dt_basic;

        // DataTable with buttons
        // --------------------------------------------------------------------
        if (dt_basic_table.length) {
            dt_basic = dt_basic_table.DataTable({
                ajax: '/ajax/passenger',
                columns: [{
                        data: 'id'
                    },
                    {
                        data: 'employee_name'
                    },
                    {
                        data: 'gender'
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
                            '<li><a href="javascript:;" class="dropdown-item">Details</a></li>' +
                            '<li><a href="javascript:;" class="dropdown-item">Edit</a></li>' +
                            '<li><a href="javascript:;" class="dropdown-item text-danger delete-record">Delete</a></li>' +
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


    });
</script>


@endsection