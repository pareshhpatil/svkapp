@extends('layouts.web')
@section('header')
<link rel="stylesheet" href="/assets/vendor/libs/select2/select2.css" />
@endsection
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-8">
                <h4 class="fw-bold py-2"><span class="text-muted fw-light">Documents /</span> List</h4>
            </div>
            <div class="col-lg-4 text-end">
                <a href="/document/create" class="btn btn-primary">Add document</a>
                <a href="/document/report?tab=expiring&amp;window=1m" class="btn btn-outline-primary">Expiry report</a>
            </div>
        </div>
        <div class="card invoice-preview-card">
            <div class="card-body">
                <div class="card-datatable table-responsive pt-0">
                    <table id="datatable" class="datatables-basic table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Document type</th>
                                <th>Driver / Vehicle</th>
                                <th>Project</th>
                                <th>Issue</th>
                                <th>Expiry</th>
                                <th>Assigned to</th>
                                <th>File</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($documents as $doc)
                            <tr>
                                <td>{{ $doc->name }}</td>
                                <td>{{ \App\Models\DocumentModel::categoryLabel($doc->document_category ?? null) }}</td>
                                <td>{{ \App\Models\DocumentModel::subtypeLabel($doc->document_category ?? null, $doc->document_type ?? null) }}</td>
                                <td>
                                    @if(($doc->document_category ?? '') === 'driver' && !empty($doc->driver_name))
                                    {{ $doc->driver_name }}
                                    @elseif(($doc->document_category ?? '') === 'vehicle' && !empty($doc->vehicle_number))
                                    {{ $doc->vehicle_number }}@if(!empty($doc->vehicle_car_type)) <span class="text-muted">({{ $doc->vehicle_car_type }})</span>@endif
                                    @else
                                    —
                                    @endif
                                </td>
                                <td>{{ $doc->project_name ?? '—' }}</td>
                                <td>{{ $doc->issue_date ? date('d M Y', strtotime($doc->issue_date)) : '—' }}</td>
                                <td>{{ $doc->expiry_date ? date('d M Y', strtotime($doc->expiry_date)) : '—' }}</td>
                                <td>{{ $doc->assigned_user_name ?? '—' }}</td>
                                <td>
                                    @if(!empty($doc->file_path))
                                    <a href="{{ $doc->file_path }}" target="_blank" rel="noopener">View</a>
                                    @else
                                    —
                                    @endif
                                </td>
                                <td>
                                    <div class="d-inline-block">
                                        <a href="javascript:;" class="dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="text-primary ti ti-dots-vertical"></i></a>
                                        <ul class="dropdown-menu dropdown-menu-end m-0">
                                            <li><a href="/document/create/{{ $doc->id }}" class="dropdown-item">Edit</a></li>
                                            @if(session('user_type')==1)
                                            <li><a href="/document/delete/{{ $doc->id }}" onclick="return confirm('Remove this document?')" class="dropdown-item text-danger">Delete</a></li>
                                            @endif
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
@endsection
@section('footer')
@endsection
