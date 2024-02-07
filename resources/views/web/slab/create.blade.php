@extends('layouts.web')
@section('header')
<link rel="stylesheet" href="/assets/vendor/libs/select2/select2.css" />

@endsection
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-6">
                <h4 class="fw-bold py-2"><span class="text-muted fw-light">Slab /</span> @if(!empty($det))
                    Update
                    @else
                    Create
                    @endif</h4>
            </div>

        </div>
        <div class="card invoice-preview-card">

            <div class="card-body">

                <!-- Earning Reports -->

                <!--/ Earning Reports -->

                <!-- Support Tracker -->
                <form class="source-item pt-4 px-0 px-sm-4" id="frm" action="/master/slab/save" method="post">
                    @csrf
                    <div class="col-lg-5">
                        <label for="defaultFormControlInput" class="form-label">Project</label>
                        <select name="project_id" id="select2Basic" class="select2 form-select form-select-lg input-sm" data-allow-clear="true">
                            <option value=""></option>
                            @if(!empty($project_list))
                            @foreach($project_list as $v)
                            <option @if(!empty($det)) @if($det->project_id==$v->project_id) selected @endif @endif
                                @if(count($project_list)==1) selected @endif
                                value="{{$v->project_id}}">{{$v->name}}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="mb-3" data-repeater-list="drivers">
                        <div class="repeater-wrapper pt-0 pt-md-4" data-repeater-item>
                            <div class="d-flex border rounded position-relative pe-0">
                                <div class="row w-100 p-3">
                                    <div class="col-md-3 col-12 mb-md-0 mb-3">
                                        <p class="mb-2 repeater-title">Location</p>
                                        <input type="text" @if(!empty($det)) value="{{$det->zone}}" @endif name="zone" class="form-control  mb-3" placeholder="Enter location" />
                                        <p class="mb-2 repeater-title">SVK Amount</p>
                                        <input type="number" name="svk_amount" class="form-control" value="@if(!empty($det)){{$det->svk_amount}}@endif">
                                    </div>
                                    <div class="col-md-3 col-12 mb-md-0 mb-3">
                                        <p class="mb-2 repeater-title">Car type</p>
                                        <select required name="car_type" class="form-select  mb-3">
                                            <option selected value="">Car type</option>
                                            @foreach($car_type_array as $car_type)
                                            <option @if(!empty($det)) @if($det->car_type==$car_type) selected @endif @endif value="{{$car_type}}">{{$car_type}}</option>
                                            @endforeach
                                        </select>
                                        <p class="mb-2 repeater-title">Vendor Amount</p>
                                        <input type="number" name="vendor_amount" class="form-control" value="@if(!empty($det)){{$det->vendor_amount}}@endif">
                                    </div>
                                    <div class="col-md-3 col-12 mb-md-0 mb-3">
                                        <p class="mb-2 repeater-title">Company slab</p>
                                        <select required name="company_slab" class="form-select  mb-3">
                                            <option selected value="">Select</option>
                                            @foreach($slab_array as $slab)
                                            <option @if(!empty($det)) @if($det->company_slab==$slab) selected @endif @endif value="{{$slab}}">{{$slab}}</option>
                                            @endforeach
                                        </select>
                                        <p class="mb-2 repeater-title">Admin Amount</p>
                                        <input type="number" name="admin_amount" class="form-control" value="@if(!empty($det)){{$det->admin_amount}}@endif">
                                    </div>
                                    <div class="col-md-3 col-12 mb-md-0 mb-3">
                                        <p class="mb-2 repeater-title">Driver slab</p>
                                        <select required name="driver_slab" class="form-select  mb-3">
                                            <option selected value="">Select</option>
                                            @foreach($slab_array as $slab)
                                            <option @if(!empty($det)) @if($det->company_slab==$slab) selected @endif @endif value="{{$slab}}">{{$slab}}</option>
                                            @endforeach
                                        </select>
                                        <p class="mb-2 repeater-title">Company Amount</p>
                                        <input type="number" name="company_amount" class="form-control" value="@if(!empty($det)){{$det->company_amount}}@endif">
                                    </div>
                                </div>
                                <div class="d-flex flex-column align-items-center justify-content-between border-start p-2">
                                    <i class="ti ti-x cursor-pointer" data-repeater-delete></i>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row pb-4">
                        @if(empty($det))
                        <div class="col-12">
                            <button type="button" class="btn btn-primary" data-repeater-create>Add New</button>
                        </div>
                        @endif

                    </div>
                    <hr class="my-3 mx-n4">
                    <div class="row pb-4">
                        <div class="col-12 pull-right">
                            <input type="hidden" name="id" @if(!empty($det)) value="{{$det->id}}" @else value="0" @endif>
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

@endsection
