@extends('layouts.web')
@section('header')
<link rel="stylesheet" href="/assets/vendor/libs/select2/select2.css" />
<link rel="stylesheet" href="/assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css" />
<style>
/* Select2 trigger: stay within column (dropdown is appended to body — see .document-select2-dropdown) */
.document-page-form .select2-container {
    width: 100% !important;
    max-width: 100%;
}
/* Dropdown is under body; scope by dropdownCssClass so max-width / wrapping apply */
.select2-dropdown.document-select2-dropdown {
    max-width: calc(100vw - 2rem);
    box-sizing: border-box;
}
.select2-dropdown.document-select2-dropdown .select2-results__option {
    white-space: normal;
    word-wrap: break-word;
    overflow-wrap: anywhere;
}
.select2-dropdown.document-select2-dropdown .select2-search__field {
    max-width: 100%;
    box-sizing: border-box;
}
html {
    scrollbar-gutter: stable;
}
</style>
@endsection
@section('content')
<div class="row document-page-form">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-6">
                <h4 class="fw-bold py-2"><span class="text-muted fw-light">Documents /</span> {{ !empty($det) ? 'Edit' : 'Create' }}</h4>
            </div>
            <div class="col-lg-6 text-end">
                <a href="/document/list" class="btn btn-label-secondary">Back to list</a>
            </div>
        </div>
        <div class="card invoice-preview-card">
            <div class="card-body">
                @if($errors->any())
                <div class="alert alert-danger">{{ $errors->first() }}</div>
                @endif
                <form class="source-item px-0 px-sm-4" id="frm" action="/document/save" enctype="multipart/form-data" method="post">
                    @csrf
                    <input type="hidden" name="id" value="{{ $det->id ?? 0 }}" />
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" required class="form-control" value="{{ $det->name ?? '' }}" placeholder="e.g. MH12 AB 1234" />
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Type</label>
                            <select name="document_category" id="doc_cat" class="form-select" required>
                                @foreach($categoryLabels as $key => $label)
                                <option value="{{ $key }}" @if(!empty($det) && ($det->document_category ?? '')===$key) selected @elseif(empty($det) && $key==='driver') selected @endif>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Document type</label>
                            <select name="document_type" id="doc_subtype" class="form-select" required></select>
                        </div>
                        <div class="col-md-4 mb-3 d-none" id="driver_picker_row">
                            <label class="form-label">Driver</label>
                            <select name="driver_id" id="select2Driver" class="select2 form-select form-select-lg input-sm" data-placeholder="Choose driver">
                                <option value=""></option>
                                @foreach($driver_list as $dr)
                                <option value="{{ $dr->id }}" @if(!empty($det) && (int)($det->driver_id ?? 0)===(int)$dr->id) selected @endif>{{ $dr->name }}@if(!empty($dr->mobile)) — {{ $dr->mobile }}@endif</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3 d-none" id="vehicle_picker_row">
                            <label class="form-label">Vehicle</label>
                            <select name="vehicle_id" id="select2Vehicle" class="select2 form-select form-select-lg input-sm" data-placeholder="Choose vehicle">
                                <option value=""></option>
                                @foreach($vehicle_list as $v)
                                <option value="{{ $v->vehicle_id }}" @if(!empty($det) && (int)($det->vehicle_id ?? 0)===(int)$v->vehicle_id) selected @endif>{{ $v->number }}@if(!empty($v->car_type)) — {{ $v->car_type }}@endif</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Project (optional)</label>
                            <select name="project_id" id="select2Basic" class="select2 form-select form-select-lg input-sm" data-allow-clear="true">
                                <option value=""></option>
                                @foreach($project_list as $v)
                                <option value="{{ $v->project_id }}" @if(!empty($det) && $det->project_id==$v->project_id) selected @endif @if(count($project_list)==1 && empty($det)) selected @endif>{{ $v->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Issue date</label>
                            <input type="text" autocomplete="off" name="issue_date" id="bs-datepicker-issue" placeholder="DD MM YYYY" class="form-control" value="{{ !empty($det) && $det->issue_date ? date('d-m-Y', strtotime($det->issue_date)) : '' }}" />
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Expiry date</label>
                            <input type="text" autocomplete="off" name="expiry_date" id="bs-datepicker-expiry" placeholder="DD MM YYYY" class="form-control" value="{{ !empty($det) && $det->expiry_date ? date('d-m-Y', strtotime($det->expiry_date)) : '' }}" />
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Notify user</label>
                            <select name="assigned_user_id" id="select2User" class="select2 form-select form-select-lg input-sm" data-allow-clear="true">
                                <option value=""></option>
                                @foreach($user_list as $u)
                                <option value="{{ $u->id }}" @if(!empty($det) && (int)$det->assigned_user_id===(int)$u->id) selected @endif>{{ $u->name }} @if($u->mobile) ({{ $u->mobile }}) @endif</option>
                                @endforeach
                            </select>
                            <small class="text-muted">Used for follow-up and future expiry notifications.</small>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="2" placeholder="Notes">{{ $det->description ?? '' }}</textarea>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">File @if(!empty($det))<span class="text-muted">(leave empty to keep current)</span>@endif</label>
                            <input type="file" name="file" class="form-control" />
                            @if(!empty($det) && !empty($det->file_path))
                            <div class="mt-2"><a href="{{ $det->file_path }}" target="_blank" rel="noopener">Current file</a></div>
                            @endif
                        </div>
                    </div>
                    <hr class="my-3">
                    <div class="row pb-4">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('footer')
<script src="/assets/vendor/libs/select2/select2.js"></script>
<script src="/assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js"></script>
<script>
    $(function() {
        var subtypesByCategory = @json($subtypesByCategory);
        var savedSubtype = @json(!empty($det) ? $det->document_type : null);
        var $dropdownParent = $('body');
        var select2Common = {
            width: '100%',
            dropdownParent: $dropdownParent,
            dropdownCssClass: 'document-select2-dropdown'
        };

        function refreshDocSubtypes() {
            var cat = $('#doc_cat').val();
            var $st = $('#doc_subtype');
            $st.empty();
            var opts = subtypesByCategory[cat] || {};
            $.each(opts, function(k, v) {
                $st.append($('<option></option>').attr('value', k).text(v));
            });
            if (savedSubtype && opts.hasOwnProperty(savedSubtype)) {
                $st.val(savedSubtype);
            } else {
                var keys = Object.keys(opts);
                if (keys.length) {
                    $st.val(keys[0]);
                }
            }
        }

        function toggleDriverVehiclePickers() {
            var cat = $('#doc_cat').val();
            $('#driver_picker_row').toggleClass('d-none', cat !== 'driver');
            $('#vehicle_picker_row').toggleClass('d-none', cat !== 'vehicle');
            if (cat !== 'driver') {
                $('#select2Driver').val(null).trigger('change');
            }
            if (cat !== 'vehicle') {
                $('#select2Vehicle').val(null).trigger('change');
            }
        }

        $('#doc_cat').on('change', function() {
            savedSubtype = null;
            refreshDocSubtypes();
            toggleDriverVehiclePickers();
        });
        refreshDocSubtypes();
        toggleDriverVehiclePickers();

        $('#select2Basic').select2($.extend({}, select2Common, { placeholder: 'Select project', allowClear: true }));
        $('#select2User').select2($.extend({}, select2Common, { placeholder: 'Select user', allowClear: true }));
        $('#select2Driver').select2($.extend({}, select2Common, { placeholder: 'Choose driver', allowClear: true }));
        $('#select2Vehicle').select2($.extend({}, select2Common, { placeholder: 'Choose vehicle', allowClear: true }));
        $('#bs-datepicker-issue, #bs-datepicker-expiry').datepicker({
            autoclose: true,
            format: 'dd-mm-yyyy',
            todayHighlight: true
        });
    });
</script>
@endsection
