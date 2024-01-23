@extends('layouts.web')
@section('header')
<link rel="stylesheet" href="/assets/vendor/libs/select2/select2.css" />

@endsection
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-6">
                <h4 class="fw-bold py-2"><span class="text-muted fw-light">@if($type==2) Escort @else Passengers @endif /</span> @if(!empty($det))
                    Update
                    @else
                    Create
                    @endif</h4>
            </div>
            @if($type!=2) <div class="col-lg-6 pull-right">
                <a class="btn btn-primary waves-effect waves-light pull-right" href="/passenger/import">Import</a>
            </div> @endif

        </div>
        <div class="card invoice-preview-card">

            <div class="card-body">

                <!-- Earning Reports -->

                <!--/ Earning Reports -->

                <!-- Support Tracker -->
                <form class="source-item pt-4 px-0 px-sm-4" id="frm" action="/passenger/save" method="post">
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
                    <div class="mb-3" data-repeater-list="group-a">
                        <div class="repeater-wrapper pt-0 pt-md-4" data-repeater-item>
                            <div class="d-flex border rounded position-relative pe-0">
                                <div class="row w-100 p-3">
                                    <div class="col-md-4 col-12 mb-md-0 mb-3">
                                        <p class="mb-2 repeater-title">Name</p>
                                        <input @if(!empty($det)) value="{{$det->employee_name}}" @endif name="employee_name" type="text" class="form-control  mb-3" placeholder="Enter name" />
                                        <p class="mb-2 repeater-title">Address</p>
                                        <textarea name="address" class="form-control" rows="2" placeholder="Address">@if(!empty($det)){{$det->address}}@endif</textarea>
                                    </div>
                                    <div class="col-md-2 col-12 mb-md-0 mb-3">
                                        <p class="mb-2 repeater-title">Gender</p>
                                        <select required name="gender" class="form-select  mb-3">
                                            <option selected value="">Select</option>
                                            <option @if(!empty($det)) @if($det->gender=='Male') selected @endif @endif value="Male">Male</option>
                                            <option @if(!empty($det)) @if($det->gender=='Female') selected @endif @endif value="Female">Female</option>
                                        </select>
                                        <p class="mb-2 repeater-title">Location</p>
                                        <input type="text" @if(!empty($det)) value="{{$det->location}}" @endif name="location" class="form-control  mb-3" placeholder="Enter location" />
                                    </div>
                                    <div class="col-md-3 col-12 mb-md-0 mb-3">
                                        <p class="mb-2 repeater-title">Email</p>
                                        <input type="email" @if(!empty($det)) value="{{$det->email}}" @endif name="email" class="form-control  mb-3" placeholder="Enter email id" />
                                        <p class="mb-2 repeater-title">Employee Code</p>
                                        <input type="text" @if(!empty($det)) value="{{$det->employee_code}}" @endif name="employee_code" class="form-control  mb-3" placeholder="Enter code" />
                                    </div>
                                    <div class="col-md-3 col-12 mb-md-0 mb-3">
                                        <p class="mb-2 repeater-title">Mobile</p>
                                        <input type="text" @if(!empty($det)) value="{{$det->mobile}}" @endif name="mobile" class="form-control mb-3" placeholder="Enter mobile number" maxlength="10" minlength="10" />
                                        <p class="mb-2 repeater-title">Cost Center Code</p>
                                        <input type="text" @if(!empty($det)) value="{{$det->cost_center_code}}" @endif name="cost_center_code" class="form-control  mb-3" placeholder="Enter code" />
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
                            <input type="hidden" name="type" value="{{$type}}">
                            <input type="hidden" name="passenger_id" @if(!empty($det)) value="{{$det->id}}" @else value="0" @endif>
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