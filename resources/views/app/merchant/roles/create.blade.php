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
                        <form action="/merchant/roles/create" onsubmit="loader();" method="POST" class="form-horizontal form-row-sepe">
                            {{ csrf_field() }}
                            <div class="modal-header">
                                <h4 class="modal-title">Create Role</h4>
                            </div>
                            <div class="modal-body">
                                <div class="portlet-body form">
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="alert alert-danger display-none">
                                                    <button class="close" data-dismiss="alert"></button>
                                                    You have some form errors. Please check below.
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label col-md-4">Name <span class="required">*</span></label>
                                                    <div class="col-md-8">
                                                        <input type="text" minlength="2" maxlength="50" name="name" class="form-control" placeholder="Role name" value="{{ old('name') }}">
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label col-md-4">Description </label>
                                                    <div class="col-md-8">
                                                        <input type="text" minlength="1" name="description" class="form-control" placeholder="Description" value="{{ old('description') }}">
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <a href="{{ url('/merchant/roles') }}" class="btn default">Cancel</a>
                                <input type="submit" value="Save" class="btn blue"/>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- END -->
            </div>
        </div>
        <!-- END PAGE CONTENT-->
    </div>
    <!-- END CONTENT -->
@endsection