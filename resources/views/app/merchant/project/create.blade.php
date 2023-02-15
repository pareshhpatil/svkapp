@extends('app.master')
<style>
    .select2-container--default {
        /* width: 515px !important; */
    }
</style>
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
        <div id="project_date_error" class="alert alert-block alert-danger fade in" style="display:none;">
            <button type="button" class="close" data-dismiss="alert"></button>
            <p>Error! Project end date cant be before project start date</p>
        </div>
        <div class="col-md-12">
            <div class="portlet light bordered">
                <div class="portlet-body form">
                    <!--<h3 class="form-section">Profile details</h3>-->
                    <form action="/merchant/project/store" method="post" class="form-horizontal form-row-sepe" onsubmit="return validateDate();">
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
                                            <input type="text" onchange="assignProjectID()" required="true" id="project_id" maxlength="45" name="project_id" class="form-control" placeholder="Enter project id" value="{{ old('project_id') }}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Project Name <span class="required">*
                                            </span></label>
                                        <div class="col-md-5">
                                            <input type="text" required="true" maxlength="45" name="project_name" class="form-control" placeholder="Enter project name" value="{{ old('project_name') }}">
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
                                            <input class="form-control date-picker" id="start_date" type="text" name="start_date" autocomplete="off" data-date-format="{{ Session::get('default_date_format')}}" placeholder="Start Date" value="{{ old('start_date') }}" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">End Date <span class="required">*
                                            </span></label>
                                        <div class="col-md-5">
                                            <input class="form-control date-picker" id="end_date" type="text" name="end_date" autocomplete="off" data-date-format="{{ Session::get('default_date_format')}}" placeholder="End Date"  value="{{ old('end_date') }}" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Invoice sequence number <span class="required">*</span>
                                            <i class="popovers fa fa-info-circle support blue" data-container="body" data-trigger="hover" data-content="Enter the start of your sequence i.e. If you want to start your sequence at 100 enter 100." data-original-title="" title=""></i>
                                        </label>
                                        <div class="col-md-3">
                                            <select required class="form-control select2me" data-placeholder="Select" name="sequence_number" id="seq_no_drpdwn">
                                                <option value="">Select sequence</option>
                                                @if(!empty($invoiceSeq))
                                                    @foreach($invoiceSeq as $f)
                                                        <option value="{{$f['auto_invoice_id']}}">{{$f['prefix']}}{{$f['seprator']}}{{$f['val']+1}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-md-1 ml-minus-1">
                                            <a title="New invoice number" onclick="showNewSequencePanel()" class="btn btn-sm green"><i class="fa fa-plus"></i> New sequence</a>
                                        </div>
                                    </div>
                                    <div id="newSequencePanel" hidden>
                                        <div class="form-group" >
                                            <label class="control-label col-md-3">
                                            </label>
                                            <div class="col-md-3">
                                                <input class="form-control" type="text" id="project_prefix" name="prefix" placeholder="Prefix" maxlength="20" onkeyup="changeSeparatorVal(this.value)" value="{{ old('prefix') }}"/>
                                            </div> 
                                            <div class="col-md-1 ml-minus-1">
                                                <input class="form-control" type="text" name="seprator" placeholder="Separator" maxlength="5" value="{{ old('seprator')!='' ? old('seprator') : '-' }}" id="separator"/>
                                            </div>
                                            <div class="col-md-1">
                                                <input class="form-control" onkeyup=imposeMinMax(this) type="number" min="0" max="99999999" name="sequence" placeholder="Seq. no" value="{{ old('sequence') }}" id="seq_no"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3"></label>
                                            <div class="col-md-6">
                                                <button type="button" onclick="saveSequence()" class="btn btn-sm blue">Save sequence</button>
                                                <button type="button" onclick="showNewSequencePanel()" class="btn default btn-sm">Cancel</button>
                                                <p id="seq_error" style="color: red;"></p>
                                            </div>
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
<!--add sequence modal-->
@include('app.merchant.project.add-sequence-modal')
@endsection