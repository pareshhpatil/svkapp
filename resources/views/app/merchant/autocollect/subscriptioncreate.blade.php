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
        <div class="col-md-12">

            @if(!empty($errors))
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert"></button>
                <strong>Error!</strong>
                <div class="media">
                    @foreach ($errors as $v)
                    <p class="media-heading">{{$v}}</p>
                    @endforeach
                </div>
            </div>
            @endif
            <div class="portlet light bordered">
                <div class="portlet-body form">
                    <!--<h3 class="form-section">Profile details</h3>-->
                    <form action="/merchant/autocollect/subscription/save" method="post" onsubmit="loader();" class="form-horizontal form-row-sepe">
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
                                        <label class="control-label col-md-4">Plan Name <span class="required">*
                                            </span></label>
                                        <div class="col-md-4">
                                            <select class="form-control" data-placeholder="Select Plan" required="" name="plan_id" >
                                                <option value="">Select plan</option>
                                                @foreach ($list as $v)
                                                <option value="{{$v->plan_id}}">{{$v->plan_name}} | {{$v->amount}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group" id="divtype">
                                        <label class="control-label col-md-4" id="lbltype">Select Customer</label>
                                        <div class="col-md-4">
                                            <div class="">
                                                <select id="customer_id" name="customer_id" onchange="setBenifeciaryDetail(this.value)" class="form-control select2me" data-placeholder="Select...">
                                                </select>
                                            </div>	
                                        </div>

                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Name <span class="required">*
                                            </span></label>
                                        <div class="col-md-4">
                                            <input type="text" required id="name"  maxlength="100" {{$validate->name}} name="customer_name"  class="form-control">
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
                                        <label class="control-label col-md-4">Description <span class="required">*
                                            </span></label>
                                        <div class="col-md-4">
                                            <textarea type="text" required name="description" maxlength="500" class="form-control"></textarea>
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
                                        <a href="/merchant/autocollect/subscriptions" class="btn default">Cancel</a>
                                        <input type="hidden" id="beneficiarytype" value="Customer">
                                        <input type="submit" value="Save" class="btn blue"/>
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
</div>





@endsection