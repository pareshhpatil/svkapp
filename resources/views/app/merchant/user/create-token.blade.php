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
        {{ Breadcrumbs::render('merchant.user.create-token') }}
    </div>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        @include('layouts.alerts')
       
        <div class="col-md-12">
            <div class="portlet light bordered">
                <div class="portlet-body form">
                    <!--<h3 class="form-section">Profile details</h3>-->
                    <form action="/merchant/user/save-token" method="post" class="form-horizontal form-row-sepe">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        
                        @if($show_token==0)
                        <div class="alert alert-danger display-none">
                            <button class="close" data-dismiss="alert"></button>
                            You have some form errors. Please check below.
                        </div>
                        <!-- End Bank details -->
                        <h4 class="form-section">Please confirm your password to view token</h4>
                        <!-- Start Bulk upload details -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-4">Password</label>
                                    <div class="col-md-6">
                                        <input type="password" name="password" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="">
                                    <div class="row">
                                        <div class="col-md-offset-6">
                                            <input type="submit" name="submit_password" class="btn blue" value="Confirm"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @elseif($show_token==1)
                        <h4 class="form-section">API token</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-4">Token</label>
                                    <div class="col-md-8">
                                        <input type="text" name="plain_text" class="form-control" value="{{$plain_text}}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="pull-right">
                                        <a href="/merchant/profile/settings" class="btn btn-default">Cancel</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                    </form>	
                </div>
            </div>
        </div>
        <!-- END PAGE CONTENT-->
    </div>
</div>
<!-- END CONTENT -->
@endsection