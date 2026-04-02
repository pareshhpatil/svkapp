@extends('layouts.web')
@section('header')
<link rel="stylesheet" href="/assets/vendor/libs/select2/select2.css" />
<link rel="stylesheet" href="/assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css" />
<style>
.document-page-form .select2-container { width: 100% !important; max-width: 100%; }
.select2-dropdown.document-select2-dropdown {
    max-width: calc(100vw - 2rem);
    box-sizing: border-box;
}
.select2-dropdown.document-select2-dropdown .select2-results__option {
    white-space: normal;
    word-wrap: break-word;
    overflow-wrap: anywhere;
}
html { scrollbar-gutter: stable; }
</style>
@endsection
@section('content')
<div class="row document-page-form">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-6">
                <h4 class="fw-bold py-2"><span class="text-muted fw-light">Documents /</span> Add multiple</h4>
            </div>
            <div class="col-lg-6 text-end">
                <a href="/document/list" class="btn btn-label-secondary">Back to list</a>
                <a href="/document/create" class="btn btn-outline-primary">Single document</a>
            </div>
        </div>
        <div class="card invoice-preview-card">
            <div class="card-body">
                @if($errors->any())
                <div class="alert alert-danger">{{ $errors->first() }}</div>
                @endif
                <form class="source-item px-0 px-sm-4" id="doc-multi-form" action="/document/save-multi" enctype="multipart/form-data" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Type</label>
                            <select name="document_category" id="doc_cat" class="form-select" required>
                                @foreach($categoryLabels as $key => $label)
                                <option value="{{ $key }}" @if($key==='vehicle') selected @endif>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3 d-none" id="driver_picker_row">
                            <label class="form-label">Driver</label>
                            <select name="driver_id" id="select2Driver" class="select2 form-select form-select-lg input-sm">
                                <option value=""></option>
                                @foreach($driver_list as $dr)
                                <option value="{{ $dr->id }}">{{ $dr->name }}@if(!empty($dr->mobile)) — {{ $dr->mobile }}@endif</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3" id="vehicle_picker_row">
                            <label class="form-label">Vehicle</label>
                            <select name="vehicle_id" id="select2Vehicle" class="select2 form-select form-select-lg input-sm">
                                <option value=""></option>
                                @foreach($vehicle_list as $v)
                                <option value="{{ $v->vehicle_id }}">{{ $v->number }}@if(!empty($v->car_type)) — {{ $v->car_type }}@endif</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Project (optional)</label>
                            <select name="project_id" id="select2Basic" class="select2 form-select form-select-lg input-sm" data-allow-clear="true">
                                <option value=""></option>
                                @foreach($project_list as $v)
                                <option value="{{ $v->project_id }}" @if(count($project_list)==1) selected @endif>{{ $v->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Notify user</label>
                            <select name="assigned_user_id" id="select2User" class="select2 form-select form-select-lg input-sm" data-allow-clear="true">
                                <option value=""></option>
                                @foreach($user_list as $u)
                                <option value="{{ $u->id }}">{{ $u->name }} @if($u->mobile) ({{ $u->mobile }}) @endif</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <h6 class="mb-3 mt-2 text-primary">Documents <span class="text-muted fw-normal">(per row)</span></h6>
                    <div class="mb-3" data-repeater-list="items">
                        <div class="repeater-wrapper border rounded mb-3 p-3" data-repeater-item>
                            <div class="row w-100">
                                <div class="col-md-3 mb-2">
                                    <label class="form-label small">Name</label>
                                    <input type="text" name="name" class="form-control" placeholder="e.g. reg / ref" />
                                </div>
                                <div class="col-md-3 mb-2">
                                    <label class="form-label small">Document type</label>
                                    <select name="document_type" class="form-select doc-item-subtype"></select>
                                </div>
                                <div class="col-md-2 mb-2">
                                    <label class="form-label small">Issue date</label>
                                    <input type="text" autocomplete="off" name="issue_date" class="form-control bs-datepicker-issue" placeholder="dd-mm-yyyy" />
                                </div>
                                <div class="col-md-2 mb-2">
                                    <label class="form-label small">Expiry date</label>
                                    <input type="text" autocomplete="off" name="expiry_date" class="form-control bs-datepicker-expiry" placeholder="dd-mm-yyyy" />
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label small">Description</label>
                                    <input type="text" name="description" class="form-control" placeholder="Optional" />
                                </div>
                                <div class="col-md-5 mb-2">
                                    <label class="form-label small">File</label>
                                    <input type="file" name="file" class="form-control" />
                                </div>
                                <div class="col-md-1 mb-2 d-flex align-items-end">
                                    <button type="button" class="btn btn-sm btn-icon btn-outline-danger" data-repeater-delete title="Remove row"><i class="ti ti-x"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row pb-2">
                        <div class="col-12">
                            <button type="button" class="btn btn-sm btn-outline-primary" data-repeater-create>Add another document</button>
                        </div>
                    </div>
                    <hr class="my-3">
                    <div class="row pb-4">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Save all</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('footer')
<script src="/assets/vendor/libs/jquery-repeater/jquery-repeater.js"></script>
<script src="/assets/vendor/libs/select2/select2.js"></script>
<script src="/assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js"></script>
<script>
    $(function() {
        var subtypesByCategory = @json($subtypesByCategory);
        var $dropdownParent = $('body');
        var select2Common = {
            width: '100%',
            dropdownParent: $dropdownParent,
            dropdownCssClass: 'document-select2-dropdown'
        };

        function fillSubtypeSelect($select, preserveVal) {
            var cat = $('#doc_cat').val();
            var opts = subtypesByCategory[cat] || {};
            $select.empty();
            $.each(opts, function(k, v) {
                $select.append($('<option></option>').attr('value', k).text(v));
            });
            if (preserveVal && opts.hasOwnProperty(preserveVal)) {
                $select.val(preserveVal);
            } else {
                var keys = Object.keys(opts);
                if (keys.length) { $select.val(keys[0]); }
            }
        }

        function fillAllSubtypeSelects() {
            $('.doc-item-subtype').each(function() {
                fillSubtypeSelect($(this), $(this).val());
            });
        }

        function bindRowDatepickers($ctx) {
            $ctx.find('.bs-datepicker-issue, .bs-datepicker-expiry').datepicker({
                autoclose: true,
                format: 'dd-mm-yyyy',
                todayHighlight: true
            });
        }

        function toggleDriverVehiclePickers() {
            var cat = $('#doc_cat').val();
            $('#driver_picker_row').toggleClass('d-none', cat !== 'driver');
            $('#vehicle_picker_row').toggleClass('d-none', cat !== 'vehicle');
            if (cat !== 'driver') { $('#select2Driver').val(null).trigger('change'); }
            if (cat !== 'vehicle') { $('#select2Vehicle').val(null).trigger('change'); }
        }

        $('#doc_cat').on('change', function() {
            fillAllSubtypeSelects();
            toggleDriverVehiclePickers();
        });

        $('#select2Basic').select2($.extend({}, select2Common, { placeholder: 'Select project', allowClear: true }));
        $('#select2User').select2($.extend({}, select2Common, { placeholder: 'Select user', allowClear: true }));
        $('#select2Driver').select2($.extend({}, select2Common, { placeholder: 'Choose driver', allowClear: true }));
        $('#select2Vehicle').select2($.extend({}, select2Common, { placeholder: 'Choose vehicle', allowClear: true }));

        toggleDriverVehiclePickers();
        fillAllSubtypeSelects();
        bindRowDatepickers($('#doc-multi-form'));

        $('#doc-multi-form').repeater({
            show: function () {
                $(this).slideDown();
                var $row = $(this);
                fillSubtypeSelect($row.find('.doc-item-subtype'), null);
                bindRowDatepickers($row);
            },
            hide: function (deleteElement) {
                if (confirm('Remove this document row?')) {
                    $(this).slideUp(deleteElement);
                }
            }
        });
    });
</script>
@endsection
