@extends('app.master')

@section('content')
    <div class="page-content">
        <div class="page-bar">
            <span class="page-title" style="float: left;">{{$title}}</span>
            {{ Breadcrumbs::render() }}
        </div>
        <!-- BEGIN SEARCH CONTENT-->
        <div class="row">
            @include('layouts.alerts')
            <div class="col-md-12">
                <!-- BEGIN PAYMENT TRANSACTION TABLE -->
                <div class="portlet">
                    <div class="portlet-body">
                        <form action="{{ route('merchant.subusers.update', $user->user_id) }}" onsubmit="loader();" method="post" id="submit_form" class="form-horizontal form-row-sepe">
                            {{ csrf_field() }}
                            <div class="form-body">
                                <!-- Start profile details -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="alert alert-danger display-none">
                                            <button class="close" data-dismiss="alert"></button>
                                            You have some form errors. Please check below.
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-4">First name <span class="required">*
                                                </span></label>
                                            <div class="col-md-4">
                                                <input type="text" required name="first_name" class="form-control" value="{{ $user->first_name }}">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Last name <span class="required">*
                                                </span>
                                            </label>
                                            <div class="col-md-4">
                                                <input type="text" required name="last_name" class="form-control" value="{{ $user->last_name }}">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Mobile <span class="required">*
                                                </span></label>
                                            <div class="col-md-1">
                                                <input type="text" name="mob_country_code" class="form-control" value="{{ $user->mob_country_code }}">
                                            </div>
                                            <div class="col-md-3">
                                                <input type="text" required name="mobile" class="form-control" value="{{ $user->mobile_no }}">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Role<span class="required">*
                                                </span></label>
                                            <div class="col-md-4">
                                                <select required id="role" class="form-control select2me" name="role">
                                                    <option value="">Select Role</option>
                                                    @foreach($briqRoles as $briqRole)
                                                        <option value="{{$briqRole->id}}" {{ ($selected_role_id == $briqRole->id) ? 'selected' : '' }}>{{$briqRole->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                    </div>


                                </div>
                            </div>
                            <!-- End profile details -->

                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="pull-right">
                                            <a href="{!! url('/merchant/subusers') !!}" class="btn default">Cancel</a>
                                            <input type="submit" value="Update" class="btn blue"/>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
                <!-- END PAYMENT TRANSACTION TABLE -->
            </div>
        </div>
        <!-- END PAGE CONTENT-->
    </div>
    <!-- END CONTENT -->
@endsection