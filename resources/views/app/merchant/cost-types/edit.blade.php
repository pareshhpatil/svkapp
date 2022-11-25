@extends('app.master')

@section('content')
    <div class="page-content">
        <div class="page-bar">
            {{ Breadcrumbs::render() }}
        </div>
        <!-- BEGIN SEARCH CONTENT-->
        <div class="row">
            @include('layouts.alerts')
            <div class="col-md-12">
                <!-- BEGIN PAYMENT TRANSACTION TABLE -->
                <div class="portlet">

                    <div class="portlet-body">
                        <form action="{{ route('merchant.cost-types.update', $costType->id) }}" onsubmit="loader();" method="POST" class="form-horizontal">
                            {{ csrf_field() }}
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                <h4 class="modal-title">Update Cost Type</h4>
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
                                                        <input type="text" required minlength="2" maxlength="50" name="name" class="form-control" value="{{$costType->name}}" placeholder="Cost type name">
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label col-md-4">Abbrevation <span class="required">*</span></label>
                                                    <div class="col-md-8">
                                                        <input type="text" minlength="1" maxlength="2" name="abbrevation" class="form-control" value="{{$costType->abbrevation}}" placeholder="Cost type Abbrevation">
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <a href="{{ url('merchant/cost-types/index') }}" class="btn default">Cancel</a>
                                <input type="submit" value="Save" class="btn blue"/>
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