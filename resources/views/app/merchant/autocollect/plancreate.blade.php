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
                    <form action="/merchant/autocollect/plan/save" method="post" onsubmit="loader();" class="form-horizontal form-row-sepe">
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
                                            <input type="text" required id="name"  maxlength="45" {{$validate->name}} name="name"  class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-4">Amount<span class="required">*
                                            </span></label>
                                        <div class="col-md-4">
                                            <input type="text" required maxlength="25" name="amount"   class="form-control" >
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Expire after <span class="required">*
                                            </span></label>
                                        <div class="col-md-2">
                                            <input type="number" required max="24" min="1" maxlength="3" name="occurrence"  value="12" class="form-control" > 
                                        </div>
                                        <label class="control-label col-md-2 align-left">
                                            Months
                                        </label>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Description <span class="required">*
                                            </span></label>
                                        <div class="col-md-4">
                                            <textarea type="text" id="address" required name="description" maxlength="500" class="form-control"></textarea>
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
                                        <input type="hidden" name="type" value="PERIODIC">
                                        <input type="hidden" name="interval_type" value="month">
                                        <input type="hidden" name="intervals" value="1">
                                        <a href="/merchant/autocollect/plans" class="btn default">Cancel</a>
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