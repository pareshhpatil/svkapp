@extends('app.master')

@section('header')
<link href="/assets/global/plugins/summernote/summernote.min.css" rel="stylesheet">
<style>
    .uppy-Dashboard-AddFiles-title {
        font-size: 15px !important;
        font-weight: 400 !important;
    }

    .uppy-Dashboard-inner {
        border: 0px solid #eaeaea !important;
        border-radius: 5px !important;
    }

    [data-uppy-drag-drop-supported=true] .uppy-Dashboard-AddFiles {
        margin: 0px !important;
        padding-bottom: 9px;
        height: calc(100%) !important;
        border-radius: 3px;
        border: 1px dashed #dfdfdf;
    }

    .steps {
        background-color: transparent !important;
        border: 2px #18AEBF solid !important;
        color: #18AEBF !important;
        width: auto !important;
    }

    .uppy-Informer {
        bottom: 160px !important;
    }
</style>
@endsection

@section('content')
<div class="page-content">

    <!-- BEGIN PAGE HEADER-->
    <div class="page-bar">
        <span class="page-title" style="float: left;">{{$title}}</span>
        {{ Breadcrumbs::render('create.requestpayment','Request for payment') }}
    </div>


    <!-- END PAGE HEADER-->

    <!-- BEGIN SEARCH CONTENT-->
    <div class="row">
        <div class="col-md-12">


            <div class="row">
                <div class="col-md-12">
                    @if($link=='')
                    <div class="portlet " style="margin-bottom: 15px;">
                        <div class="portlet-body" data-tour="invoice-pick-format">
                            <form action="" method="post" id="template_create" class="form-horizontal form-row-sepe mb-0">
                                @csrf
                                <div class="form-body">
                                    <div class="form-group mb-0">
                                        <div class="col-md-12">


                                            <div class="col-md-2 pl-1 pr-0">
                                                <select data-cy="currency" required id="currency" name="currency" required class="form-control select2me" data-placeholder="Select...">
                                                    @foreach($currency_list as $v)
                                                    <option @if($currency==$v) selected @endif value="{{$v}}">{{$v}}</option>
                                                    @endforeach
                                                </select>
                                                <small class="form-text text-muted">Currency</small>
                                                <div class="help-block"></div>
                                            </div>

                                            <div class="col-md-3 pl-1 pr-0">
                                                <select data-placeholder="Select Sub contract" required class="form-control select2me" name="sub_contract_id">
                                                    <option value=""></option>
                                                    @foreach($contract as $v)
                                                    <option @if($sub_contract_id==$v->sub_contract_id) selected @endif value="{{$v->sub_contract_id}}">{{$v->project_name}} | {{$v->sub_contract_code}}</option>
                                                    @endforeach

                                                </select>
                                                <small class="form-text text-muted">Sub Contract</small>
                                                <div class="help-block"></div>
                                            </div>

                                            <div class="col-md-7 pl-1" style="width: auto;">
                                                <button type="submit" class="btn blue"> Select</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

        </div>
    </div>


    <!-- Show create invoice form -->
    @if($sub_contract_id!='')
    <span class=" pull-right badge badge-pill status steps" style="padding: 6px 16px 6px 16px !important;margin-bottom: 15px">Step <span x-text="step">1</span> of 3</span>
    <br>
    <br>
    <form action="/merchant/subcontract/requestpaymentsave" id="invoice" method="post" class="form-horizontal" enctype="multipart/form-data">
    @csrf    
    <div class="portlet light bordered">
            <div class="portlet-body form">
                <div class="alert alert-danger" style="display: none;" id="invoiceerrorshow">
                    <p id="invoiceerror_display">Please correct the below errors to complete registration.</p>
                </div>
                <div>
                    <div class="row invoice-logo">
                        <div class="col-xs-6">
                        </div>
                        <div class="col-xs-6">
                        </div>
                    </div>
                    <h3 class="form-section"> Contract information</h3>
                    <div class="row">
                        <div class="col-md-6" data-tour="invoice-create-customer-select">

                            <div class="form-group">
                                <label class="control-label col-md-4">Project name</label>
                                <div class="col-md-8">
                                    <div class="input-icon right">
                                        <input type="text" readonly="" class="form-control cust_det" value="{{$contract_detail->project_name}}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">Vendor name</label>
                                <div class="col-md-8">
                                    <div class="input-icon right">
                                        <input type="text" readonly="" class="form-control cust_det" value="{{$contract_detail->vendor_name}}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">Contract code</label>
                                <div class="col-md-8">
                                    <div class="input-icon right">
                                        <input type="text" readonly="" class="form-control cust_det" value="{{$contract_detail->sub_contract_code}}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">Vendor code</label>
                                <div class="col-md-8">
                                    <div class="input-icon right">
                                        <input type="text" readonly="" class="form-control cust_det" value="{{$contract_detail->vendor_code}}">
                                    </div>
                                </div>
                            </div>



                        </div>
                        <div class="col-md-6" data-tour="invoice-create-billing-information">

                            <input type="hidden" name="vendor_id" value="{{$contract_detail->vendor_id}}">
                            <input type="hidden" name="billing_profile_id" value="0">
                            <input type="hidden" name="currency" value="{{$currency}}">
                            <div class="form-group">
                                <label class="control-label col-md-4">Request No. <span class="required">* </span></label>
                                <div class="col-md-8">
                                    <div class="input-icon right">
                                        <input type="text" required="" maxlength="45" value="{{$request_number}}" name="request_number" autocomplete="off" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-4">Bill date <span class="required">* </span></label>
                                <div class="col-md-8">
                                    <div class="input-icon right">
                                        <input type="text" required="" value="{{$bill_date}}" id="bill_date" name="bill_date" autocomplete="off" class="form-control date-picker" data-date-format="{{ Session::get('default_date_format')}}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">Due date <span class="required">* </span></label>
                                <div class="col-md-8">
                                    <div class="input-icon right">
                                        <input type="text" required="" value="{{$due_date}}" id="due_date" name="due_date" autocomplete="off" class="form-control date-picker" data-date-format="{{ Session::get('default_date_format')}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="portlet light bordered">
            <div class="portlet-body form">
                <div class="row">
                    <div class="col-md-7">
                    </div>


                    <div class="col-md-5">
                        <div class="pull-right">
                            <p>&nbsp;</p>
                            <input type="hidden" name="link" value="{{$link}}">
                            <input type="hidden" name="narrative" value="">
                            <input type="hidden" name="sub_contract_id" value="{{$sub_contract_id}}">
                            <a href="/merchant/collect-payments" class="btn green">Cancel</a>
                            <button type="submit" onclick="return validateDates();" class="btn blue">Add particulars</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    @endif









    <!-- BEGIN SEARCH CONTENT-->
    @endsection

    <!-- add particulars label ends -->