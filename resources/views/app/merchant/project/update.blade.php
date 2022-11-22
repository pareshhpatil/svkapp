@extends('app.master')

@section('content')
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="page-bar">
        <span class="page-title" style="float: left;">{{$title}}</span>
        {{ Breadcrumbs::render('home.projectupdate', $project_data->encrypted_id) }}
        <a href="/merchant/code/list/{{$project_data->encrypted_id}}" class="btn default pull-right"> View bill code </a>
    </div>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        @include('layouts.alerts')
        <div id="project_date_error" class="alert alert-block alert-danger fade in" style="display:none;">
            <button type="button" class="close" data-dismiss="alert"></button>
            <p>Error! Project end date cant be before project start date</p>
        </div>
        <div class="col-md-12">
            <div class="portlet light bordered">
                <div class="portlet-body form">
                    <!--<h3 class="form-section">Profile details</h3>-->
                    <form action="/merchant/project/updatestore" method="post" class="form-horizontal form-row-sepe" onsubmit="return validateDate();">
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
                                            <input type="text" disabled required="true" maxlength="45" name="project_id" value="{{$project_data->project_id}}" 
                                            class="form-control" placeholder="Enter project id">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Project Name <span class="required">*
                                            </span></label>
                                        <div class="col-md-5">
                                            <input type="text" required="true" maxlength="45" name="project_name" value="{{$project_data->project_name}}" 
                                            class="form-control" placeholder="Enter project name">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Customer ID <span class="required">*
                                            </span></label>
                                        <div class="col-md-5">
                                            <select required="" class="form-control select2me" name="customer_id">
                                                <option value="">Select Client</option>
                                                @foreach($cust_list as $v)
                                                <option 
                                                @if($project_data->customer_id == $v->customer_id) selected @endif 
                                                value="{{$v->customer_id}}"> {{$v->customer_code}} | {{$v->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Start Date <span class="required">*
                                            </span></label>
                                        <div class="col-md-5">
                                            <input class="form-control date-picker" type="text" name="start_date" id="start_date"
                                            value="@if(isset($project_data->start_date))<x-localize :date='$project_data->start_date' type='date' />@endif"
                                            autocomplete="off" data-date-format="{{ Session::get('default_date_format')}}" placeholder="Start Date" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">End Date <span class="required">*
                                            </span></label>
                                        <div class="col-md-5">
                                            <input class="form-control date-picker" type="text" name="end_date" id="end_date"
                                            value="@if(isset($project_data->end_date))<x-localize :date='$project_data->end_date' type='date' />@endif"
                                            autocomplete="off" data-date-format="{{ Session::get('default_date_format')}}" placeholder="End Date" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Invoice sequence number <span class="required">*</span>
                                        <i class="popovers fa fa-info-circle support blue" data-container="body" data-trigger="hover" data-content="Enter the start of your sequence i.e. If you want to start your sequence at 100 enter 100." data-original-title="" title=""></i>
                                    </label>
                                        <div class="col-md-3">
                                            <input class="form-control" type="text" value="{{$project_data->project_prefix}}" disabled id="project_prefix" name="prefix" placeholder="Project ID" />
                                        </div>
                                        <div class="col-md-2">
                                            <input class="form-control" type="number"  value="{{$sequence_data->val??''}}"  min="0" max="99999999" name="sequence_number" placeholder="Sequence number" />
                                            <input type="hidden"  value="{{$project_data->sequence_number}}"  name="sequence_id"/>
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
                                        <input type="hidden" name="id" value="{{$project_data->id}}" />
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