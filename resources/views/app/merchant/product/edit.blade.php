@extends('app.master')

@section('content')
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="page-bar">
        <span class="page-title" style="float: left;">{{$title}}</span>
        {{ Breadcrumbs::render('product.update',$product->product_name) }}
    </div>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        @include('layouts.alerts')
        <div class="col-md-12">
            <div class="portlet light bordered">
                <div class="portlet-body form">
                    <!--<h3 class="form-section">Profile details</h3>-->
                    <form action="/merchant/product/update" method="post" id="submit_form" class="form-horizontal form-row-sepe" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <h4 class="form-section">Basic details</h4>
                        <div class="form-body">
                            <!-- Start profile details -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="alert alert-danger display-none">
                                        <button class="close" data-dismiss="alert"></button>
                                        You have some form errors. Please check below.
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-5">Type <span class="required">*
                                            </span></label>
                                        <div class="col-md-7">
                                            <div class="md-radio-inline">
                                                <div class="md-radio">
                                                    <input type="radio" id="radio1" @if ($product->type=='Goods') checked="" @endif value="Goods" name="type" class="md-radiobtn" onclick="setInputFields(this);" data-cy="product_type_goods">
                                                    <label for="radio1">
                                                        <span></span>
                                                        <span class="check"></span>
                                                        <span class="box"></span>
                                                        Product </label>
                                                </div>
                                                <div class="md-radio">
                                                    <input type="radio" id="radio2" @if ($product->type=='Service') checked="" @endif value="Service" name="type" class="md-radiobtn" onclick="setInputFields(this);" data-cy="product_type_service">
                                                    <label for="radio2">
                                                        <span></span>
                                                        <span class="check"></span>
                                                        <span class="box"></span>
                                                        Service </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group" id="product_type_div">
                                        <label class="control-label col-md-5">Product Type <i class="popovers fa fa-info-circle support blue" data-placement="top" data-container="body" data-trigger="hover" data-content="Pick product type as Variable if your product has different variations like size, color, etc. For ex. A Tshirt can come in variable sizes and colors" data-original-title="" title=""></i></label></label>
                                        <div class="col-md-7">
                                            <select data-cy="product_type" id="goods_type" name="goods_type" required class="form-control" data-placeholder="Select..." onchange="setView(this.value);">
                                                <option @if('simple'==$product->goods_type) selected="" @endif value="simple">Simple</option>
                                                <option @if('variable'==$product->goods_type) selected="" @endif value="variable">Variable</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-5"><span id="product_name_lbl">Product</span> name <span class="required">*
                                            </span></label>
                                        <div class="col-md-7">
                                            <input type="text" required name="product_name" class="form-control" value="{{$product->product_name}}" data-cy="product_name">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-5"><span id="product_name_lbl2">Product</span> Number </label>
                                        <div class="col-md-7">
                                            <input type="text" maxlength="40" name="product_number" class="form-control" value="{{$product->product_number}}" data-cy="product_number">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-5"><span id="sac_code_lbl">HSN</span> Code </label>
                                        <div class="col-md-7">
                                            <div class="input-group">
                                                <input type="text" maxlength="8" id="sac_code" name="sac_code" class="form-control" value="{{$product->sac_code}}" data-cy="sac_code" placeholder="Search HSN/SAC code">
                                                <span class="input-group-btn">
                                                    <a data-toggle="modal" href="#search_hsn_sac_code" onclick="document.getElementById('hsn_sac_code').value = document.getElementById('sac_code').value; livewire.emit('setSearchTerm',document.getElementById('sac_code').value);" class="btn btn-icon-only green" data-cy="search_hsn_sac_code"><i class="fa fa-search"> </i></a>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-5">Select unit type </label>
                                        <div class="col-md-7">
                                            <div class="input-group">
                                                <select id="unit_type" data-placeholder="Select Unit Type" name="unit_type_id" class="form-control select2me" data-cy="unit_type">
                                                    <option value="">Select Unit Type</option>
                                                    @foreach ($getUnitTypes as $uk=>$unit)
                                                    <option value="{{$uk}}" @if(isset($product->unit_type_id) && ($product->unit_type_id==$uk)) selected="" @endif>{{$unit}}</option>
                                                    @endforeach
                                                </select>
                                                <span class="input-group-btn">
                                                    <a data-toggle="modal" href="#createUnitType" class="btn btn-icon-only green" data-cy="add_new_unit_type"><i class="fa fa-plus"> </i></a>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-5">Description </label>
                                        <div class="col-md-7">
                                            <textarea name="description" class="form-control" data-cy="product_description">{{$product->description}}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group" id="expiry_date_div" {{($product->goods_type =='variable') ? 'style=display:none' : 'style=display:block'}}>
                                        <label class="control-label col-md-5">Expiry Date </label>
                                        <div class="col-md-7">
                                            <input class="form-control form-control-inline date-picker" type="text" name="product_expiry_date" autocomplete="off" data-date-format="{{ Session::get('default_date_format')}}" placeholder="Expiry Date" value="@if(isset($product['product_expiry_date']))<x-localize :date='$product['product_expiry_date']' type='date' />@endif" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group" id="sku_div" {{($product->goods_type =='variable') ? 'style=display:none' : 'style=display:block'}}>
                                        <label class="control-label col-md-5">SKU </label>
                                        <div class="col-md-7">
                                            <input type="text" name="sku" class="form-control" value="@if(isset($product['sku'])){{$product['sku']}}@endif" data-cy="sku">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-5">GST applicable </label>
                                        <div class="col-md-7">
                                            <select id="gst_applicable" data-placeholder="Select GST" name="gst_percent" class="form-control" data-cy="gst_applicable">
                                                @foreach ($gstTax as $tk=>$tax)
                                                <option @if($tk==$product->gst_percent) selected="" @endif value="{{$tk}}">{{$tax}}%</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-5">Select category </label>
                                        <div class="col-md-7">
                                            <div class="input-group">
                                                <select id="productCategory" data-placeholder="Select Category" name="category_id" class="form-control select2me" data-cy="product_category">
                                                    <option value="">Select Category</option>
                                                    @foreach ($productCategories as $ck=>$cat)
                                                    <option @if(isset($product->category_id) && ($ck==$product->category_id)) selected="" @endif value="{{$ck}}">{{$cat}}</option>
                                                    @endforeach
                                                </select>
                                                <span class="input-group-btn">
                                                    <a data-toggle="modal" href="#createProductCategory" class="btn btn-icon-only green" data-cy="add_new_product_category"><i class="fa fa-plus"> </i></a>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-5">Select vendor </label>
                                        <div class="col-md-7">
                                            <div class="input-group">
                                                <select id="vendor" data-placeholder="Select Vendor" name="vendor_id" class="form-control select2me" data-cy="product_vendor">
                                                    <option value="">Select Vendor</option>
                                                    @foreach ($getVendors as $vk=>$vendor)
                                                    <option @if(isset($product->vendor_id) && ($vk==$product->vendor_id)) selected="" @endif value="{{$vk}}">{{$vendor}}</option>
                                                    @endforeach
                                                </select>
                                                <span class="input-group-btn">
                                                    <a data-toggle="modal" target="_blank" href="/merchant/vendor/create/" class="btn btn-icon-only green" data-cy="add_new_vendor"><i class="fa fa-plus"> </i></a>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group" id="product_img_div" {{($product->goods_type =='variable') ? 'style=display:none' : 'style=display:block'}}>
                                        <label class="control-label col-md-5">Product image</label>
                                        <div class="col-md-7">
                                            @if (isset($product->product_image) && !empty($product->product_image))
                                            <span class="help-block" id="existImageDiv">
                                                <a class="btn btn-xs green" target="_BLANK" href="{{$product->product_image}}" title="Click to view full size">View Image</a>
                                                <a onclick="document.getElementById('newImageDiv').style.display = 'block';" class="btn btn-xs btn-link">Update Image</a>
                                            </span>
                                            <input type="hidden" name="old_image" class="form-control" value="{{$product->product_image}}">
                                            <div id="newImageDiv" style="display: none;">
                                                <a onclick="uppy.reset();document.getElementById('newImageDiv').style.display='none';document.getElementById('uppy_file').value ='';" class="btn btn-xs red pull-right"><i class="fa fa-remove"></i></a>
                                                <input type="hidden" name="new_image" class="form-control" id="uppy_file" data-cy="product_image">
                                                <div id="drag-drop-area" name="image" style="display: inline-block"></div>
                                                <span id="error" class="text-danger"><span>
                                            </div>
                                            @else
                                            <span>
                                                <input type="hidden" name="new_image" class="form-control" id="uppy_file" data-cy="product_image">
                                                <div id="drag-drop-area" name="image"></div>
                                                <span id="error" class="text-danger"><span>
                                                    </span>
                                                    @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row" id="sale_po_info" {{($product->goods_type =='variable') ? 'style=display:none' : 'style=display:block'}}>
                                <div class="col-md-6">
                                    <h4 class="form-section mb-2">Sale information</h4>
                                    <div class="form-group">
                                        <label class="control-label col-md-5">Sale price <span class="required">*
                                            </span></label>
                                        <div class="col-md-7">
                                            <input type="number" step="0.01" max="999999999" id="sale_price" name="price" required class="form-control" value="@if(isset($product->price)){{$product->price}}@endif" data-cy="sale_price">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-5">MRP</label>
                                        <div class="col-md-7">
                                            <input type="number" step="0.01" max="999999999" name="mrp" class="form-control" value="@if(isset($product->mrp)){{$product->mrp}}@endif" data-cy="mrp">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-5">Description </label>
                                        <div class="col-md-7">
                                            <textarea maxlength="100" name="sale_info" class="form-control" data-cy="sale_info">@if(isset($product->sale_info)){{$product->sale_info}}@endif</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h4 class="form-section mb-2">Purchase information</h4>
                                    <div class="form-group">
                                        <label class="control-label col-md-5">Cost price </label>
                                        <div class="col-md-7">
                                            <input type="number" step="0.01" max="999999999" name="purchase_cost" class="form-control" value="@if(isset($product->purchase_cost)){{$product->purchase_cost}}@endif" data-cy="purchase_cost">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-5">Description </label>
                                        <div class="col-md-7">
                                            <textarea maxlength="100" name="purchase_info" class="form-control" data-cy="purchase_info">@if(isset($product->purchase_info)){{$product->purchase_info}}@endif</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="manage_inventory_row" {{($product->goods_type =='variable') ? 'style=display:none' : 'style=display:block'}}>
                                @if($enable_inventory==1)
                                <div class="col-md-6">
                                    <div class="form-group stock_keep_div_simple">
                                        <label class="control-label col-md-5">Manage inventory <i class="popovers fa fa-info-circle support blue" data-placement="top" data-container="body" data-trigger="hover" data-content="Add stock keeping information" data-original-title="" title=""></i></label>
                                        <div class="col-md-7">
                                            <input type="hidden" name="has_stock_keeping" id="has_stock_keeping" @if(isset($product->has_stock_keeping) && $product->has_stock_keeping=='1') value="1" @else value="0" @endif data-cy="has_stock_keeping">
                                            <input type="checkbox" name="is_stock_keeping" id="has_inventory" onchange="stockDivEnable(this.checked,'stock_details_simple','has_stock_keeping')" @if($product->has_stock_keeping=='1') checked="" value="1" @else value="0" @endif class="make-switch" data-on-text="&nbsp;On&nbsp;&nbsp;"
                                            data-off-text="&nbsp;Off&nbsp;" data-cy="is_stock_keeping">
                                            <br>
                                        </div>
                                    </div>
                                </div>
                                @else
                                <div class="col-md-10">
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Manage inventory <i class="popovers fa fa-info-circle support blue" data-placement="top" data-container="body" data-trigger="hover" data-content="Add stock keeping information" data-original-title="" title=""></i></label>
                                        <div class="col-md-9">
                                            @if($enable_inventory==0)
                                            <input type="checkbox" name="is_stock_keeping" id="has_inventory" onchange="enableInventoryCTA(this.checked)" class="make-switch" data-on-text="&nbsp;On&nbsp;&nbsp;" data-off-text="&nbsp;Off&nbsp;" data-cy="is_stock_keeping">
                                            @endif
                                            <div class="alert alert-info mt-1" id="enable_service" {{($enable_inventory==2) ? 'style=display:block' : 'style=display:none'}}>
                                                <div class="">
                                                    @if($enable_inventory==0)
                                                    <p><strong>Inventory has not been enabled against your account. Would you like to enable it now?</strong></p>
                                                    <p style="padding-bottom:20px"><a onclick="enableService('{{$service_id}}')" class="btn blue pull-left" data-cy="enable_inventory">Enable inventory</a></p>
                                                    @elseif ($enable_inventory==2)
                                                    <p><strong>Service activation request has been sent. Our support team will get in touch with you shortly.</strong></p>
                                                    <p style="padding-bottom:20px"><button disabled="" class="btn pull-left mb-1">In Review</button></p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                            @if($enable_inventory==1)
                            <div class="stock_details_simple" {{($product->has_stock_keeping =='1') ? 'style=display:block' : 'style=display:none'}}>
                                <h4 class="form-section mb-2">Stock keeping units</h4>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-5">Available stock <span class="required">*
                                                </span></label>
                                            <div class="col-md-7">
                                                <input type="number" step="0" name="available_stock" class="form-control" id="available_stock" data-cy="available_stock" value="@if(isset($product->available_stock)){{round($product->available_stock)}}" @endif @if(isset($product->available_stock) && ($product->available_stock!='0.00')) readonly @endif>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-5">Minimum stock </label>
                                            <div class="col-md-7">
                                                <input type="number" step="0" name="minimum_stock" class="form-control" data-cy="minimum_stock" value="@if(isset($product->minimum_stock)){{round($product->minimum_stock)}}@endif">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <div id="variable_product_div" {{($product->goods_type =='variable') ? 'style=display:block' : 'style=display:none'}}>
                                @livewire('product.create-variation-row', ['enable_inventory'=>$enable_inventory,'product_id'=>$product->product_id])
                            </div>
                        </div>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="pull-right">
                                        <input type="hidden" name="product_id" value="{{$product->product_id}}">
                                        <a href="/merchant/product/index" class="btn default" data-cy="cancel">Cancel</a>
                                        <input type="submit" value="Save" class="btn blue" data-cy="save_product" />
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

<!-- product-category-modal for adding new product category -->
@include('app.merchant.product-category.create-product-category-modal')

<!-- unit-type-modal for adding new unit type -->
@include('app.merchant.unit-type.create-unit-modal')

<!-- confirm modal for inventory service enable -->
<div class="modal fade" id="confirm" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Activate Service</h4>
            </div>
            <div class="modal-body">
                Are you sure you would like to enable this service?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn default" data-dismiss="modal" data-cy="close_activate_service_modal">Close</button>
                <button id="enableServiceOk" class="btn blue" data-cy="confirm_activate_Service_modal">Confirm</button>
            </div>
        </div>
    </div>
</div>

<!-- Service Activation message -->
<div class="modal fade" id="serviceActivated" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Activate Service</h4>
            </div>
            <div class="modal-body">
                <p id="serviceActivatedMsg"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn default" data-dismiss="modal" data-cy="close_service_activated_modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="search_hsn_sac_code" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="find_hsn_sac_code">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Find <span id="modal_sac_code_lbl">HSN</span> code</h4>
                </div>
                <div class="modal-body">
                    <div class="portlet-body form">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-12">
                                    @livewire('lookup.hsn-sac-code-search')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="close_hsn_sac_lookup_modal" class="btn default" data-dismiss="modal" data-cy="close_hsn_sac_lookup_modal">Cancel</button>
                    {{-- <button id="saveCode" class="btn blue" data-cy="save_sac_modal" onclick="return saveCode()">Save</button> --}}
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Delete Confirm</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure want to delete variation row?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">Close</button>
                {{-- <button type="button" wire:click.prevent="remove()" class="btn btn-danger close-modal" data-dismiss="modal">Yes, Delete</button> --}}
                <button type="button" onclick="deleteVariationRow()" class="btn btn-danger close-modal" data-dismiss="modal">Yes, Delete</button>
            </div>
        </div>
    </div>
</div>

<script src="https://releases.transloadit.com/uppy/v1.28.1/uppy.min.js"></script>
<script>
    mode = '{{$mode}}';
    //uppy file upload code
    var uppy = Uppy.Core({
        autoProceed: true,
        restrictions: {
            maxFileSize: 1000000,
            maxNumberOfFiles: 1,
            minNumberOfFiles: 1,
            allowedFileTypes: ['.jpg', '.png', '.jpeg']
        }
    });

    uppy.use(Uppy.Dashboard, {
        target: '#drag-drop-area',
        inline: true,
        height: 200,
        maxHeight: 200,
        width: 280,
        maxWidth: 280,
        hideAfterFinish: true,
        showProgressDetails: false,
        hideUploadButton: false,
        hideRetryButton: false,
        hidePauseResumeButton: false,
        hideCancelButton: false,
        doneButtonHandler: () => {
            document.getElementById("uppy_file").value = '';
            this.uppy.reset()
            this.requestCloseModal()
        },
        locale: {
            strings: {
                done: 'Cancel'
            }
        }
    });

    uppy.use(Uppy.XHRUpload, {
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        endpoint: '/merchant/uppyfileupload/uploadImage',
        method: 'post',
        formData: true,
        fieldName: 'image'
    });

    uppy.on('file-added', (file) => {
        document.getElementById("error").innerHTML = '';
        console.log('file-added');
    });

    uppy.on('upload', (data) => {
        console.log('Starting upload');
    });
    uppy.on('upload-success', (file, response) => {
        document.getElementById("uppy_file").value = response.body.fileUploadPath;
        if (response.body.status == 300) {
            document.getElementById("error").innerHTML = response.body.errors;
            uppy.removeFile(file.id);
        } else {
            document.getElementById("error").innerHTML = '';
        }
    });
    uppy.on('complete', (result) => {
        //console.log('successful files:', result.successful)
        //console.log('failed files:', result.failed)
    });
    uppy.on('error', (error) => {
        //console.error(error.stack);
    });
</script>
@endsection