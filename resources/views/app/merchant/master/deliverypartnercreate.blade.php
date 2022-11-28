@extends('app.master')

@section('content')
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="page-bar">
        <span class="page-title" style="float: left;">{{$title}}</span>
        {{ Breadcrumbs::render() }}
    </div>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        @include('layouts.alerts')
        <div class="col-md-12">
            <div class="portlet light bordered">
                <div class="portlet-body form">
                    <!--<h3 class="form-section">Profile details</h3>-->
                    <form action="/merchant/master/deliver-partner/save" method="post" class="form-horizontal form-row-sepe">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="form-body">
                            <!-- Start profile details -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="alert alert-danger display-none">
                                        <button class="close" data-dismiss="alert"></button>
                                        You have some form errors. Please check below.
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Name <span class="required">*
                                            </span></label>
                                        <div class="col-md-5">
                                            <input type="text" required="true" maxlength="100" name="name" class="form-control" placeholder="Name">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Commission(%) <span class="required">*
                                            </span></label>
                                        <div class="col-md-5">
                                            <input type="number" required="true" max="100" min="0" step="0.01" name="commission" class="form-control" placeholder="Commission">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">GST(%) <span class="required">*
                                            </span></label>
                                        <div class="col-md-5">
                                            <input type="number" required="true" max="100" min="0" step="1" name="gst" class="form-control" placeholder="Commission">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Start date<span class="required">*
                                            </span></label>
                                        <div class="col-md-5">
                                            <input class="form-control form-control-inline date-picker" data-cy="start_date" type="text" required name="start_date" autocomplete="off" data-date-format="{{ Session::get('default_date_format')}}" placeholder="Start date" value="old('start_date')" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">End date<span class="required">*
                                            </span></label>
                                        <div class="col-md-5">
                                            <input class="form-control form-control-inline date-picker" data-cy="end_date" type="text" required name="end_date" autocomplete="off" data-date-format="{{ Session::get('default_date_format')}}" placeholder="End date" value="old('start_date')" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="pull-right">
                                        <a href="/merchant/master/delivery-partner/list" class="btn default" data-cy="cancel">Cancel</a>
                                        <input type="submit" value="Save" class="btn blue" data-cy="save_code" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- END PAGE CONTENT-->
    </div>
</div>
<!-- END CONTENT -->
@endsection