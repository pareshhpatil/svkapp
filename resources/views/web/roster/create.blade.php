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
                <h4 class="fw-bold py-2"><span class="text-muted fw-light">Roster /</span>
                    @if(!empty($det))
                    Update
                    @else
                    Create
                    @endif
                </h4>
            </div>
        </div>
        <div class="card invoice-preview-card">

            <div class="card-body">

                <!-- Earning Reports -->

                <!--/ Earning Reports -->

                <!-- Support Tracker -->
                <form class="source-item pt-4 px-0 px-sm-4" id="frm" action="/roster/save" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-lg-5">
                            <label for="defaultFormControlInput" class="form-label">Project</label>
                            <select name="project_id" id="select2Basic" class="select2 form-select form-select-lg input-sm" data-allow-clear="true">
                                <option value=""></option>
                                @if(!empty($project_list))
                                @foreach($project_list as $v)
                                <option @if(!empty($det)) @if($det->project_id==$v->project_id) selected @endif @endif value="{{$v->project_id}}">{{$v->name}}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-lg-5">
                            <label for="defaultFormControlInput" class="form-label">Date</label>
                            <input type="text" autocomplete="off" name="date" @if(!empty($det)) value="{{$det->date}}" @endif required id="bs-datepicker-autoclose" placeholder="DD MM YYYY" class="form-control" />
                        </div>
                        <div class="col-lg-5">
                            <label for="defaultFormControlInput" class="form-label">Type</label>
                            <select required name="type" class="form-select  mb-3">
                                <option selected value="">Select</option>
                                <option @if(!empty($det)) @if($det->type=='Pickup') selected @endif @endif value="Pickup">Pickup</option>
                                <option @if(!empty($det)) @if($det->type=='Drop') selected @endif @endif value="Drop">Drop</option>
                            </select>
                        </div>
                        <div class="col-lg-5">
                            <label for="defaultFormControlInput" class="form-label">Start Location</label>
                            <input @if(!empty($det)) value="{{$det->start_location}}" @endif type="text" name="start_location" class="form-control" />
                        </div>
                        <div class="col-lg-5">
                            <label for="defaultFormControlInput" class="form-label">End Location</label>
                            <input type="text" @if(!empty($det)) value="{{$det->end_location}}" @endif name="end_location" class="form-control" />
                        </div>
                        <div class="col-lg-5">
                            <label for="defaultFormControlInput" class="form-label">Start Time</label>
                            <input type="time" @if(!empty($det)) value="{{$det->start_time}}" @endif name="start_time" class="form-control" />
                        </div>
                        <div class="col-lg-5">
                            <label for="defaultFormControlInput" class="form-label">End Time</label>
                            <input type="time" @if(!empty($det)) value="{{$det->end_time}}" @endif name="end_time" class="form-control" />
                        </div>
                        <div class="col-lg-5">
                            <label for="defaultFormControlInput" class="form-label">Title</label>
                            <input type="text" maxlength="45" @if(!empty($det)) value="{{$det->title}}" @endif name="title" placeholder="Enter title eg. Slab 1" class="form-control" />
                        </div>
                    </div>
                    <div class="mb-3" data-repeater-list="passengers">
                        @if(!empty($passengers))
                        @foreach($passengers as $rp)
                        <div id="div{{$rp->id}}" class="repeater-wrapper pt-0 pt-md-4" data-repeater-item>
                            <div class="d-flex border rounded position-relative pe-0">
                                <div class="row w-100 p-3">
                                    <div class="col-md-4 col-12 mb-md-0 mb-3">
                                        <p class="mb-2 repeater-title">Name</p>
                                        <select name="passenger_id" class="select2 form-select form-select-lg input-sm" data-allow-clear="true">
                                            <option value=""></option>
                                            @if(!empty($passenger_list))
                                            @foreach($passenger_list as $v)
                                            <option @if($rp->passenger_id==$v->id) selected @endif value="{{$v->id}}">{{$v->location}} - {{$v->employee_name}}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <div class="col-md-2 col-12 mb-md-0 mb-3">
                                        <p class="mb-2 repeater-title">Pickup time</p>
                                        <input type="time" name="pickup_time" value="{{date('H:i:s', strtotime($rp->pickup_time))}}" class="form-control" />
                                        <input type="hidden" name="pid" value="{{$rp->id}}" />
                                    </div>
                                </div>
                                <div class="d-flex flex-column align-items-center justify-content-between border-start p-2">
                                    <i class="ti ti-x cursor-pointer" onclick="document.getElementById('div{{$rp->id}}').remove();" data-repeater-delete></i>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        @else
                        <div class="repeater-wrapper pt-0 pt-md-4" data-repeater-item>
                            <div class="d-flex border rounded position-relative pe-0">
                                <div class="row w-100 p-3">
                                    <div class="col-md-4 col-12 mb-md-0 mb-3">
                                        <p class="mb-2 repeater-title">Name</p>
                                        <select name="passenger_id" class="select2 form-select form-select-lg input-sm" data-allow-clear="true">
                                            <option value=""></option>
                                            @if(!empty($passenger_list))
                                            @foreach($passenger_list as $v)
                                            <option value="{{$v->id}}">{{$v->location}} - {{$v->employee_name}}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <div class="col-md-2 col-12 mb-md-0 mb-3">
                                        <p class="mb-2 repeater-title">Pickup time</p>
                                        <input type="time" name="pickup_time" class="form-control" />
                                        <input type="hidden" name="pid" value="0" />
                                    </div>
                                </div>
                                <div class="d-flex flex-column align-items-center justify-content-between border-start p-2">
                                    <i class="ti ti-x cursor-pointer" data-repeater-delete></i>
                                </div>
                            </div>
                        </div>
                        @endif

                    </div>
                    <div class="row pb-4">
                        <div class="col-12">
                            <button type="button" class="btn btn-primary" data-repeater-create>Add New</button>
                        </div>
                    </div>
                    <hr class="my-3 mx-n4">
                    <div class="row pb-4">
                        <div class="col-12 pull-right">
                            <input type="hidden" name="ride_id" @if(!empty($det)) value="{{$det->id}}" @else value="0" @endif>
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
</script>


@endsection