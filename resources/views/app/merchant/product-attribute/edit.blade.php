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
                    <form action="/merchant/product-attribute/update" method="post" class="form-horizontal form-row-sepe">
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
                                        <label class="control-label col-md-3">Name <span class="required">*
                                            </span></label>
                                        <div class="col-md-5">
                                            <input type="text" required="true" maxlength="45" name="name" value="{{$productAttribute->name}}" class="form-control" placeholder="Enter variation name">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Values <span class="required">*
                                        </span></label>
                                            
                                            <div class="col-md-5">
                                                <a href="javascript:;" onclick="AddProductAttributes();" class="btn btn-sm green pull-right mb-1"> <i class="fa fa-plus"> </i> Add new row </a>
                                            <div class="table-scrollable">
                                                <table class="table table-bordered table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th class="td-c default-font">
                                                                Variations
                                                            </th>
                                                            <th class="td-c">
                                                                
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="new_attribute">
                                                        @php $int =1; @endphp
                                                        @if(!empty($productAttribute['default_values']))
                                                            @foreach(json_decode($productAttribute['default_values']) as $v)
                                                                <tr>
                                                                    <td>
                                                                        <div class="input-icon right">
                                                                            <input type="text" data-cy="product_attribute_value{{$int}}" name="default_values[]" required maxlength="45" value="{{$v}}" class="form-control " placeholder="Enter variation value">
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <a href="javascript:;" data-cy="product_attribute_remove{{$int}}" onclick="$(this).closest('tr').remove();showAddRowBtn();" class="btn btn-sm red"> <i class="fa fa-times"> </i> </a>
                                                                    </td>
                                                                </tr>
                                                            @php $int++; @endphp
                                                            @endforeach
                                                        @endif
                                                    </tbody>
                                                </table>
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
                                        <input type="hidden" name="id" value="{{$productAttribute->encrypted_id}}">
                                        <a href="/merchant/product-attribute/index" class="btn default" data-cy="cancel">Cancel</a>
                                        <input type="submit" value="Save" class="btn blue" data-cy="save_prodcut_attribute"/>
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


