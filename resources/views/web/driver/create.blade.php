@extends('layouts.web')
@section('header')
<link rel="stylesheet" href="/assets/vendor/libs/select2/select2.css" />

@endsection
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-6">
                <h4 class="fw-bold py-2"><span class="text-muted fw-light">Driver /</span>@isset($det) Update @else Create @endisset</h4>
            </div>
            <div class="col-lg-6 pull-right">
            </div>
        </div>
        <div class="card invoice-preview-card">

            <div class="card-body">

                <!-- Earning Reports -->

                <!--/ Earning Reports -->

                <!-- Support Tracker -->
                <form class="source-item  px-0 px-sm-4" id="frm" action="/master/driver/save" method="post">
                    @csrf
                    <div class="mb-3" data-repeater-list="drivers">
                        <div class="repeater-wrapper pt-0 pt-md-4" data-repeater-item>
                            <div class="d-flex border rounded position-relative pe-0">
                                <div class="row w-100 p-3">
                                    <div class="col-md-4 col-12 mb-md-0 mb-3">
                                        <p class="mb-2 repeater-title">Name</p>
                                        <input name="name" id="name" @isset($det) value="{{$det->name}}" @endisset type="text" class="form-control  mb-3" placeholder="Enter name" />
                                        <p class="mb-2 repeater-title">Address</p>
                                        <textarea name="address" class="form-control" rows="2" placeholder="Address">@isset($det){{$det->address}}@endisset</textarea>
                                    </div>
                                    <div class="col-md-2 col-12 mb-md-0 mb-3">
                                        <p class="mb-2 repeater-title">Location</p>
                                        <input type="text" name="location" class="form-control  mb-3" @isset($det) value="{{$det->location}}" @endisset placeholder="Enter location" />
                                    </div>
                                    <div class="col-md-3 col-12 mb-md-0 mb-3">
                                        <p class="mb-2 repeater-title">Email</p>
                                        <input type="email" name="email" class="form-control  mb-3" @isset($det) value="{{$det->email}}" @endisset placeholder="Enter email id" />
                                    </div>
                                    <div class="col-md-3 col-12 mb-md-0 mb-3">
                                        <p class="mb-2 repeater-title">Mobile</p>
                                        <input type="text" name="mobile" class="form-control " @isset($det) value="{{$det->mobile}}" @endisset placeholder="Enter mobile number" maxlength="10" minlength="10" />
                                    </div>

                                </div>
                                <div class="d-flex flex-column align-items-center justify-content-between border-start p-2">
                                    <i class="ti ti-x cursor-pointer" data-repeater-delete></i>

                                </div>
                            </div>
                        </div>
                    </div>
                    @if(empty($det))
                    <div class="row pb-4">
                        <div class="col-12">
                            <button type="button" class="btn btn-primary" data-repeater-create>Add New</button>
                        </div>
                    </div>
                    @endif
                    <hr class="my-3 mx-n4">
                    <div class="row pb-4">
                        <div class="col-12 pull-right">

                            <input type="hidden" name="id" @isset($det) value="{{$det->id}}" @else value="0" @endisset>
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
