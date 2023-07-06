@extends('layouts.web')
@section('header')
<link rel="stylesheet" href="/assets/vendor/libs/select2/select2.css" />

@endsection
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-8">
                <h4 class="fw-bold py-2"><span class="text-muted fw-light">{{ucfirst($type)}}s /</span> Import</h4>
            </div>
            <div class="col-lg-4 pull-right">
                <a class="btn btn-primary waves-effect waves-light pull-right" href="/{{$type}}/import/format">Download format</a>
            </div>
        </div>
        <div class="card invoice-preview-card">

            <div class="card-body">

                <!-- Earning Reports -->

                <!--/ Earning Reports -->

                <!-- Support Tracker -->
                <form class="source-item px-0 px-sm-4" id="frm" action="/{{$type}}/importsave" enctype="multipart/form-data" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <select required name="project_id" class="form-select">
                                <option value="">Select project</option>
                                @if(!empty($project_list))
                                @foreach($project_list as $v)
                                <option @if(count($project_list)==1) selected @endif value="{{$v->project_id}}">{{$v->name}}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="file" name="file" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <input type="hidden" name="bulk_type" value="{{$type}}">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>

            </div>
        </div><br>
        <div class="card invoice-preview-card">

            <div class="card-body">

                <div class="card-datatable table-responsive pt-0">

                    <table id="datatable" class="datatables-basic  table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Project name</th>
                                <th>File name</th>
                                <th>Date</th>
                                <th>status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($import_list as $v)
                            <tr>
                                <td>{{$v->id}}</td>
                                <td>{{$v->project_name}}</td>
                                <td>{{$v->user_file_name}}</td>
                                <td>{{$v->created_date}}</td>
                                <td>
                                    @if($v->status==1)
                                    <span class="badge bg-label-primary">Processing</span>
                                    @elseif($v->status==2)
                                    <span class="badge bg-label-danger">Error</span>
                                    @elseif($v->status==3)
                                    <span class="badge bg-label-warning">Review</span>
                                    @elseif($v->status==4)
                                    <span class="badge bg-label-primary">Saving</span>
                                    @else
                                    <span class="badge bg-label-success">Saved</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-inline-block"><a href="javascript:;" class="btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="text-primary ti ti-dots-vertical"></i></a>
                                        <ul class="dropdown-menu dropdown-menu-end m-0">
                                            @if($v->status==3)
                                            <li><a href="/{{$type}}/list/{{$v->id}}/2" class="dropdown-item">Details</a></li>
                                            <li><a href="javascript:;" onclick="document.getElementById('approve').href = '/{{$type}}/import/approve/{{$v->id}}'" data-bs-toggle="modal" data-bs-target="#modalToggle" class="dropdown-item">Approve</a></li>
                                            @endif
                                            @if($v->status==5)
                                            <li><a href="/{{$type}}/list/{{$v->id}}/0" class="dropdown-item">Details</a></li>
                                            @endif
                                            <li><a href="javascript:;" onclick="document.getElementById('delete').href = '/{{$type}}/import/delete/{{$v->id}}'" data-bs-toggle="modal" data-bs-target="#modalDelete" class="dropdown-item text-danger delete-record">Delete</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>


<!-- Modal 1-->
<div class="modal fade" id="modalToggle" aria-labelledby="modalToggleLabel" tabindex="-1" style="display: none" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalToggleLabel">Approve passengers</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">Are you sure you want to approve this sheet?</div>
            <div class="modal-footer">
                <a class="btn btn-primary waves-effect waves-light" id="approve" href="">
                    Approve
                </a>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modalDelete" aria-labelledby="modalToggleLabel" tabindex="-1" style="display: none" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalToggleLabel">Delete Sheet</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">Are you sure you want to delete this sheet?</div>
            <div class="modal-footer">
                <a class="btn btn-danger waves-effect waves-light" id="delete" href="">
                    Delete
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer')


<script>
    var dt_basic_table = $('#datatable'),
        dt_basic;

    // DataTable with buttons
    // --------------------------------------------------------------------
    dt_basic = dt_basic_table.DataTable({
        order: [
            [0, 'desc']
        ],
        displayLength: 10,
        lengthMenu: [10, 25, 50, 75, 100],
    });
</script>


@endsection