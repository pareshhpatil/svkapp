@extends('layouts.web')
@section('header')
<link rel="stylesheet" href="/assets/vendor/libs/select2/select2.css" />
@endsection
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h4 class="fw-bold py-2"><span class="text-muted fw-light">Documents /</span> Expiry report</h4>
                @if(($tab ?? 'expiring') === 'expired')
                <p class="text-muted mb-2">Documents whose <strong>expiry date is in the past</strong> (sorted by expiry, most overdue first).</p>
                @else
                <p class="text-muted mb-2">Showing documents expiring between <strong>{{ $rangeLabel }}</strong></p>
                @endif
            </div>
            <div class="col-lg-6 text-lg-end">
                <a href="/document/list" class="btn btn-label-secondary">All documents</a>
            </div>
        </div>
        <ul class="nav nav-pills mb-3 flex-wrap gap-1">
            <li class="nav-item">
                <a class="nav-link @if(($tab ?? 'expiring') === 'expiring') active @endif" href="/document/report?tab=expiring&amp;window={{ $window }}">Expiring</a>
            </li>
            <li class="nav-item">
                <a class="nav-link @if(($tab ?? 'expiring') === 'expired') active @endif" href="/document/report?tab=expired">Expired</a>
            </li>
        </ul>
        @if(($tab ?? 'expiring') === 'expiring')
        <div class="btn-group mb-3 flex-wrap" role="group">
            <a href="/document/report?tab=expiring&amp;window=1w" class="btn btn-{{ $window==='1w' ? 'primary' : 'outline-primary' }}">1 week</a>
            <a href="/document/report?tab=expiring&amp;window=1m" class="btn btn-{{ $window==='1m' ? 'primary' : 'outline-primary' }}">1 month</a>
            <a href="/document/report?tab=expiring&amp;window=3m" class="btn btn-{{ $window==='3m' ? 'primary' : 'outline-primary' }}">3 months</a>
            <a href="/document/report?tab=expiring&amp;window=6m" class="btn btn-{{ $window==='6m' ? 'primary' : 'outline-primary' }}">6 months</a>
        </div>
        @endif
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
                                <th>Expiry</th>
                                <th>Assigned to</th>
                                <th>File</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($documents as $doc)
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
                                <td>{{ $doc->expiry_date ? date('d M Y', strtotime($doc->expiry_date)) : '—' }}</td>
                                <td>
                                    @if($doc->assigned_user_name)
                                    {{ $doc->assigned_user_name }}
                                    @if(!empty($doc->assigned_user_email))<br><small class="text-muted">{{ $doc->assigned_user_email }}</small>@endif
                                    @else
                                    —
                                    @endif
                                </td>
                                <td>
                                    @if(!empty($doc->file_path))
                                    <a href="{{ $doc->file_path }}" target="_blank" rel="noopener">View</a>
                                    @else
                                    —
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    @if(($tab ?? 'expiring') === 'expired')
                                    No expired documents.
                                    @else
                                    No documents expiring in this period.
                                    @endif
                                </td>
                            </tr>
                            @endforelse
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
