@extends('layouts.web')
@section('header')
<link rel="stylesheet" href="/assets/vendor/libs/select2/select2.css" />
<link rel="stylesheet" href="/assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css" />
@endsection
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-6">
                <h4 class="fw-bold py-2"><span class="text-muted fw-light">Invoice /</span> Create</h4>
            </div>
            <div class="col-lg-6 pull-right">
            </div>
        </div>
        <div class="card invoice-preview-card">

            <div class="card-body">

                <!-- Earning Reports -->

                <!--/ Earning Reports -->

                <!-- Support Tracker -->
                <form class="source-item  px-0 px-sm-4" id="frm" action="/invoice/save" enctype="multipart/form-data" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-3">
                            <label for="defaultFormControlInput" class="form-label">Project</label>
                            <select name="project_id" id="select2Basic" class="select2 form-select form-select-lg input-sm" data-allow-clear="true">
                                <option value=""></option>
                                @if(!empty($project_list))
                                @foreach($project_list as $v)
                                <option @if(!empty($det)) @if($det->project_id==$v->project_id) selected @endif @endif @if(count($project_list)==1) selected @endif value="{{$v->project_id}}">{{$v->name}}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="defaultFormControlInput" class="form-label">Invoice Number</label>
                            <input type="text" name="invoice_number" placeholder="SVTH0025" class="form-control" />
                        </div>

                        <div class="col-md-3">
                            <label for="defaultFormControlInput" class="form-label">Month</label>
                            <input type="text" autocomplete="off" name="month" required id="bs-monthpicker-autoclose" placeholder="MM YYYY" class="form-control" />
                        </div>
                        <div class="col-md-3">
                            <label for="defaultFormControlInput" class="form-label">Bill Date</label>
                            <input type="text" autocomplete="off" name="date" required id="bs-datepicker-autoclose" placeholder="DD MM YYYY" class="form-control" />
                        </div>
                        <div class="col-md-3">
                            <label for="defaultFormControlInput" class="form-label">Grand total</label>
                            <input type="number" step="0.01" placeholder="00.00" name="amount" class="form-control" />
                        </div>
                        <div class="col-md-3">
                            <label for="defaultFormControlInput" class="form-label">Description</label>
                            <input type="text" name="title" class="form-control" />
                        </div>
                    </div>
                    <div class="mb-3" data-repeater-list="documents">
                        <div class="repeater-wrapper pt-0 pt-md-4" data-repeater-item>
                            <div class="d-flex border rounded position-relative pe-0">
                                <div class="row w-100 p-3">
                                    <div class="col-md-4 col-12 mb-md-0 mb-3">
                                        <p class="mb-2 repeater-title">Document name</p>
                                        <input name="document_name"  type="text" class="form-control  mb-3" placeholder="Enter name" />
                                        <input name="document_id"  type="hidden" value="0" />
                                    </div>
                                    
                                    <div class="col-md-3 col-12 mb-md-0 mb-3">
                                        <p class="mb-2 repeater-title">File</p>
                                        <input type="file" name="file" class="form-control" />
                                    </div>
                                </div>
                                <div class="d-flex flex-column align-items-center justify-content-between border-start p-2">
                                    <i class="ti ti-x cursor-pointer" data-repeater-delete></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row pb-4">
                        <div class="col-12">
                            <button type="button" class="btn btn-primary" data-repeater-create>Add New</button>
                        </div>
                    </div>
                    <hr class="my-3 mx-n4">
                    <div class="row pb-4">
                        <div class="col-12 pull-right">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>


@endsection

@section('footer')


<script src="/assets/vendor/libs/cleavejs/cleave.js"></script>
<script src="/assets/vendor/libs/jquery-repeater/jquery-repeater.js"></script>

<script src="/assets/js/app-invoice-add.js"></script>
<script src="/assets/vendor/libs/select2/select2.js"></script>

<script src="/assets/js/forms-selects.js"></script>

<script src="/assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js"></script>

<script>
    var defaultTime = '09:00';
    $('#bs-datepicker-autoclose').datepicker({
        todayHighlight: true,
        autoclose: true,
        format: 'dd MM yyyy',
        orientation: isRtl ? 'auto right' : 'auto left'
    });

    $('#bs-monthpicker-autoclose').datepicker({
        todayHighlight: true,
        autoclose: true,
        format: 'MM yyyy',
        orientation: isRtl ? 'auto right' : 'auto left'
    });
</script>
@endsection