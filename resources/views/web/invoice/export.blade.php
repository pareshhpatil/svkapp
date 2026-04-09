@extends('layouts.web')
@section('header')
<link rel="stylesheet" href="/assets/vendor/libs/select2/select2.css" />
<link rel="stylesheet" href="/assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css" />
@endsection
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-8">
                <h4 class="fw-bold py-2"><span class="text-muted fw-light">Invoice /</span> Export</h4>
            </div>
            <div class="col-lg-4 text-end">
                <a href="/invoice/list" class="btn btn-label-secondary">Back to list</a>
            </div>
        </div>
        <div class="card invoice-preview-card">
            <div class="card-body">
                <form action="/invoice/export/download" method="post" class="row g-3" id="invoice-export-form">
                    @csrf
                    <div class="col-md-4">
                        <label class="form-label">Company</label>
                        <select name="company_id" id="company_id" class="select2 form-select form-select-lg input-sm">
                            <option value="">All companies</option>
                            @foreach($company_list as $company)
                            <option value="{{ $company->company_id }}" @if((string)$filter['company_id']===(string)$company->company_id) selected @endif>{{ $company->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">From date</label>
                        <input type="text" name="from_date" autocomplete="off" id="from_date" class="form-control" placeholder="DD-MM-YYYY" value="{{ $filter['from_date'] }}" />
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">To date</label>
                        <input type="text" name="to_date" autocomplete="off" id="to_date" class="form-control" placeholder="DD-MM-YYYY" value="{{ $filter['to_date'] }}" />
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100" id="invoice-export-btn">Download Excel</button>
                    </div>
                </form>
                <div class="mt-3">
                    <small class="text-muted">
                        Export source: <code>logsheet_invoice</code>, filters by current user <code>admin_id</code>, <code>is_active=1</code>, optional company and bill date range.
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('footer')
<script src="/assets/vendor/libs/select2/select2.js"></script>
<script src="/assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js"></script>
<script>
    $('#company_id').select2({
        placeholder: 'Select company',
        allowClear: true
    });
    $('#from_date, #to_date').datepicker({
        todayHighlight: true,
        autoclose: true,
        format: 'dd-mm-yyyy',
        orientation: isRtl ? 'auto right' : 'auto left'
    });

    // Layout script disables submit buttons on form submit.
    // Re-enable this button shortly after download request is sent.
    $('#invoice-export-form').on('submit', function () {
        var $btn = $('#invoice-export-btn');
        setTimeout(function () {
            $btn.prop('disabled', false);
        }, 2500);
    });
</script>
@endsection
