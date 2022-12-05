@extends('app.master')

@section('content')
<div class="page-content">
    <h3 class="page-title">Beneficiary transfer&nbsp;
    </h3>
    <div class="row">
        <div class="col-md-12">
            @include('layouts.alerts')
            <!-- BEGIN PAYMENT TRANSACTION TABLE -->
            <div id="online">
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-body form">
                                <!--<h3 class="form-section">Profile details</h3>-->
                                <form action="/merchant/payout/transfersave" method="post" onsubmit="return confirm('Are you sure you want to transfer this amount?');" id="submit_form" class="form-horizontal form-row-sepe">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <div class="form-body">
                                        <!-- Start profile details -->
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="alert alert-danger display-none">
                                                    <button class="close" data-dismiss="alert"></button>
                                                    You have some form errors. Please check below.
                                                </div>
                                                <div class="form-group" id="vendor2">
                                                    <label class="control-label col-md-4">Select beneficiary <span class="required">*
                                                        </span></label>
                                                    <div class="col-md-4">
                                                        <select class="form-control select2me" id="vendor_id2" data-placeholder="Select beneficiary"  required="" name="beneficiary_id" >
                                                            <option value=""></option>
                                                            @foreach($beneficiarylist as $v)
                                                            <option value="{{$v->beneficiary_id}}">{{$v->name}} - {{$v->bank_account_no}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">Amount <span class="required">*
                                                        </span></label>
                                                    <div class="col-md-4">
                                                        <input type="number" required="" step="0.01" maxlength="10" name="amount" class="form-control" >
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label col-md-4">Narrative <span class="required">
                                                        </span></label>
                                                    <div class="col-md-4">
                                                        <textarea type="text"  name="narrative" maxlength="250" class="form-control"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>					
                                    <!-- End profile details -->

                                    <div class="form-actions">
                                        <div class="row">
                                            <div class="col-md-offset-5 col-md-6">
                                                <input type="submit" value="Save" class="btn blue"/>
                                                <a href="/merchant/vendor/transferlist" class="btn default">Cancel</a>
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

            <!-- END PAYMENT TRANSACTION TABLE -->
        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>
</div>
<!-- END CONTENT -->

@endsection