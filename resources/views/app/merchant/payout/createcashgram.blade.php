@extends('app.master')

@section('content')
<div class="page-content">
    <div class="page-bar">
        <span class="page-title" style="float: left;">{{$title}}</span>
        {{ Breadcrumbs::render() }}
        <span class="page-title pull-right">Nodal Balance: {{$balance}}</span>
    </div>
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
                                <form action="/merchant/cashgram/save" method="post"  id="submit_form" class="form-horizontal form-row-sepe">
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
                                                    <label class="control-label col-md-4">Name <span class="required">*
                                                        </span></label>
                                                    <div class="col-md-4">
                                                        <input type="text" required id="name"  maxlength="100" {{$validate->name}} name="name"  class="form-control">
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label col-md-4">Email id <span class="required">*
                                                        </span></label>
                                                    <div class="col-md-4">
                                                        <input type="email" required=""  id="email" maxlength="250" name="email_id" class="form-control" value="">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">Mobile no<span class="required">*
                                                        </span></label>
                                                    <div class="col-md-4">
                                                        <input type="text" id="mobile" maxlength="12" required {{$validate->mobile}} name="mobile" class="form-control" >
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
                                                    <label class="control-label col-md-4">Expire after <span class="required">*
                                                        </span></label>
                                                    <div class="col-md-4">
                                                        <select class="form-control" data-placeholder="Expire after" required="" name="expiry_days" >
                                                            <option value="7">7 Days</option>
                                                            <option value="6">6 Days</option>
                                                            <option value="5">5 Days</option>
                                                            <option value="4">4 Days</option>
                                                            <option value="3">3 Days</option>
                                                            <option value="2">2 Days</option>
                                                            <option value="1">1 Days</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label col-md-4">Narrative <span class="required">
                                                        </span></label>
                                                    <div class="col-md-4">
                                                        <textarea type="text"  name="narrative" maxlength="250" class="form-control"></textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">Notify customer <span class="required">
                                                        </span></label>
                                                    <div class="col-md-4">
                                                        <input type="checkbox" id="notify_" onchange="notifyPatron('notify_');" checked="" value="1"  class="make-switch" data-size="small">  
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
                                                    <a href="/merchant/cashgram/list" class="btn default">Cancel</a>
                                                    <input type="hidden" id="is_notify_" name="notify_patron" value="1">
                                                    <input type="submit" subbtn value="Save & Send" class="btn blue"/>
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

            <!-- END PAYMENT TRANSACTION TABLE -->
        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>
</div>
<!-- END CONTENT -->

@endsection