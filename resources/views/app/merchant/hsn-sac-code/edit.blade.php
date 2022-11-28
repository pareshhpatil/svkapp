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
                    <form action="/merchant/hsn-sac-code/update" method="POST" class="form-horizontal form-row-sepe">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="form-body">
                            <!-- Start profile details -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Type <span class="required">*</span></label>
                                        <div class="col-md-9">
                                            <div class="md-radio-inline">
                                                <div class="md-radio">
                                                    <input type="radio" id="radio1" @if ($hsnsaccode->type=='Goods') checked="" @endif value="Goods" name="type" class="md-radiobtn" onclick="setInputFields(this);" data-cy="product_type_goods">
                                                    <label for="radio1">
                                                        <span></span>
                                                        <span class="check"></span>
                                                        <span class="box"></span>
                                                        Goods </label>
                                                </div>
                                                <div class="md-radio">
                                                    <input type="radio" id="radio2" @if ($hsnsaccode->type=='Service') checked="" @endif value="Service" name="type" class="md-radiobtn" onclick="setInputFields(this);" data-cy="product_type_service">
                                                    <label for="radio2">
                                                        <span></span>
                                                        <span class="check"></span>
                                                        <span class="box"></span>
                                                        Service </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Code <span class="required">*
                                            </span></label>
                                        <div class="col-md-5">
                                            <input type="text" required="true" value="{{$hsnsaccode->code}}" maxlength="8" name="code" class="form-control" placeholder="HSN/SAC code">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">GST Percent <span class="required">*
                                            </span></label>
                                        <div class="col-md-5">
                                            <input type="number" required="true" min="0" step="1" value="{{$hsnsaccode->gst}}" name="gst" class="form-control" placeholder="GST percent">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Description<span class="required">*
                                            </span></label>
                                        <div class="col-md-8">
                                            <textarea required="true" name="description" class="form-control" placeholder="Description" rows="15">{{$hsnsaccode->description}}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="pull-right">
                                        <input type="hidden" name="id" value="{{$hsnsaccode->id}}">
                                        <a href="/merchant/hsn-sac-code/index" class="btn default" data-cy="cancel">Cancel</a>
                                        <input type="submit" value="Save" class="btn blue" data-cy="save_code"/>
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


