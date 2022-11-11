@extends('app.master')

@section('content')
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="page-bar">
        <span class="page-title" style="float: left;">{{$title}}</span>
        {{ Breadcrumbs::render('home.projectcreate') }}
    </div>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        @include('layouts.alerts')
        <div class="col-md-12">
            <div class="portlet light bordered">
                <div class="portlet-body form">
                    <!--<h3 class="form-section">Profile details</h3>-->
                    <form action="/merchant/project/store" method="post" class="form-horizontal form-row-sepe">
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
                                        <label class="control-label col-md-3">Project ID <span class="required">*
                                            </span></label>
                                        <div class="col-md-5">
                                            <input type="text" onchange="assignProjectID()" required="true" id="project_id" maxlength="45" name="project_id" class="form-control" placeholder="Enter project id">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Project Name <span class="required">*
                                            </span></label>
                                        <div class="col-md-5">
                                            <input type="text" required="true" maxlength="45" name="project_name" class="form-control" placeholder="Enter project name">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Customer ID <span class="required">*
                                            </span></label>
                                        <div class="col-md-5">
                                            <select required="" class="form-control select2me" name="customer_id">
                                                <option value="">Select Client</option>
                                                @foreach($cust_list as $v)
                                                <option value="{{$v->customer_id}}"> {{$v->customer_code}} | {{$v->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Start Date <span class="required">*
                                            </span></label>
                                        <div class="col-md-5">
                                            <input class="form-control date-picker" type="text" name="start_date" autocomplete="off" data-date-format="{{ Session::get('default_date_format')}}" placeholder="Start Date" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">End Date <span class="required">*
                                            </span></label>
                                        <div class="col-md-5">
                                            <input class="form-control date-picker" type="text" name="end_date" autocomplete="off" data-date-format="{{ Session::get('default_date_format')}}" placeholder="End Date" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Invoice sequence number <span class="required">*</span>
                                            <i class="popovers fa fa-info-circle support blue" data-container="body" data-trigger="hover" data-content="Enter the start of your sequence i.e. If you want to start your sequence at 100 enter 100." data-original-title="" title=""></i>
                                        </label>
                                        <div class="col-md-3">
                                            <input class="form-control" type="text" disabled id="project_prefix" name="prefix" placeholder="Project ID" />
                                        </div>
                                        <div class="col-md-2">
                                            <input class="form-control" type="number" min="0" max="99999999" name="sequence_number" placeholder="Sequence number" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="pull-right">
                                        <a href="/merchant/project/list" class="btn default" data-cy="cancel">Cancel</a>
                                        <input type="submit" value="Save" class="btn blue" data-cy="save_prodcut_attribute" />
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