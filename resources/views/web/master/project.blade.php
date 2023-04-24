@extends('layouts.web')
@section('header')
<link rel="stylesheet" href="/assets/vendor/libs/select2/select2.css" />

@endsection
@section('content')
<div class="row">
    <div class="col-lg-12">
        <h4 class="fw-bold py-2"><span class="text-muted fw-light">Project /</span> List</h4>
        <div class="card invoice-preview-card">

            <div class="card-body" style="min-height: 400px;">

                <div class="card">
                    <div class="table-responsive text-nowrap">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Project name</th>
                                    <th>Company name</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                @foreach($list as $v)
                                <tr>
                                    <td>{{$v->project_id}}</td>
                                    <td>{{$v->name}}</td>
                                    <td>{{$v->company_name}}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="ti ti-dots-vertical"></i></button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="javascript:void(0);"><i class="ti ti-pencil me-1"></i> Edit</a>
                                                <a class="dropdown-item" href="javascript:void(0);"><i class="ti ti-trash me-1"></i> Delete</a>
                                            </div>
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
</div>


@endsection