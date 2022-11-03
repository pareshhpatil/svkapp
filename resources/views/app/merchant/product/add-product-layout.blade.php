<h4 class="form-section">Basic details</h4>
<div class="form-body">
    <!-- Start profile details -->
    <div class="row">
        <div id="print-error-msg" class="alert alert-danger" style="display:none">
            <button class="close" data-dismiss="alert"></button>
            <ul></ul>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label col-md-5">Type <span class="required">*</span></label>
                <div class="col-md-7">
                    <div class="md-radio-inline">
                        <div class="md-radio">
                            <input type="radio" id="radio1" checked="" value="Goods" name="type" class="md-radiobtn" onclick="setInputFields(this);" data-cy="product_type_goods">
                            <label for="radio1">
                                <span></span>
                                <span class="check"></span>
                                <span class="box"></span>
                                Product </label>
                        </div>
                        <div class="md-radio">
                            <input type="radio" id="radio2" value="Service" name="type" class="md-radiobtn" onclick="setInputFields(this);" data-cy="product_type_service">
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
                        <option value="simple" @if (old('goods_type')=='simple' ) selected @endif>Simple</option>
                        <option value="variable" @if (old('goods_type')=='variable' ) selected @endif>Variable</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-5"><span id="product_name_lbl">Product</span> name <span class="required">*</span></label>
                <div class="col-md-7">
                    <input type="text" required name="product_name" class="form-control" value="{{ old('product_name') }}" id="product_name" data-cy="product_name">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-5"><span id="product_name_lbl2">Product</span> number</label>
                <div class="col-md-7">
                    <input type="text" maxlength="40" name="product_number" class="form-control" value="{{ old('product_number') }}" id="product_number" data-cy="product_number">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-5"><span id="sac_code_lbl">HSN</span> Code </label>
                <div class="col-md-7">
                    <div class="input-group">
                        <input type="text" maxlength="8" id="sac_code" name="sac_code" class="form-control" value="{{ old('sac_code') }}" data-cy="sac_code" placeholder="Search HSN/SAC code">
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
                            <option value="{{$uk}}" @if (old('unit_type_id')==$uk) selected @endif>{{$unit}}</option>
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
                    <textarea name="description" class="form-control" data-cy="product_description">{{ old('description') }}</textarea>
                </div>
            </div>
            <div class="form-group" id="expiry_date_div">
                <label class="control-label col-md-5">Expiry Date </label>
                <div class="col-md-7">
                    <input class="form-control form-control-inline date-picker" type="text" name="product_expiry_date" autocomplete="off" data-date-format="{{ Session::get('default_date_format')}}" placeholder="Expiry Date" />
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group" id="sku_div">
                <label class="control-label col-md-5">SKU </label>
                <div class="col-md-7">
                    <input type="text" name="sku" class="form-control" placeholder="SKU" value="" data-cy="sku">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-5">GST applicable </label>
                <div class="col-md-7">
                    <select id="gst_applicable" data-placeholder="Select GST" name="gst_percent" class="form-control" data-cy="gst_applicable">
                        @foreach ($gstTax as $tk=>$tax)
                        <option value="{{$tk}}" @if (old('gst_percent')==$tk) selected @endif>{{$tax}}%</option>
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
                            <option value="{{$ck}}" @if (old('category_id')==$ck) selected @endif>{{$cat}}</option>
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
                            <option value="{{$vk}}" @if (old('vendor_id')==$vk) selected @endif>{{$vendor}}</option>
                            @endforeach
                        </select>
                        <span class="input-group-btn">
                            <a data-toggle="modal" target="_blank" href="/merchant/vendor/create/" class="btn btn-icon-only green" data-cy="add_new_vendor"><i class="fa fa-plus"> </i></a>
                        </span>
                    </div>
                    {{-- <select id="vendor" data-placeholder="Select Vendor" name="vendor_id" class="form-control" data-cy="product_vendor">
                        <option value="">Select Vendor</option>
                        @foreach ($getVendors as $vk=>$vendor)
                            <option value="{{$vk}}" @if (old('vendor_id') == $vk) selected @endif>{{$vendor}}</option>
                    @endforeach
                    </select> --}}
                </div>
            </div>
            <div class="form-group" id="product_img_div">
                <label class="control-label col-md-5">Product image </label>
                <div class="col-md-7">
                    <input type="hidden" name="product_image" class="form-control" id="uppy_file" data-cy="product_image">
                    <div id="drag-drop-area" name="image"></div>
                    <span id="error" class="text-danger"><span>
                </div>
            </div>
        </div>
    </div>
    <div class="row" id="sale_po_info" style="display:none;">
        <div class="col-md-6">
            <h4 class="form-section mb-2">Sale information</h4>
            <div class="form-group">
                <label class="control-label col-md-5">Sale price <span class="required">*
                    </span></label>
                <div class="col-md-7">
                    <input type="number" step="0.01" name="price" max="999999999" class="form-control" value="" id="sale_price" data-cy="sale_price">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-5">MRP</label>
                <div class="col-md-7">
                    <input type="number" step="0.01" name="mrp" max="999999999" class="form-control" value="" id="mrp" data-cy="mrp">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-5">Description </label>
                <div class="col-md-7">
                    <textarea maxlength="100" name="sale_info" class="form-control" data-cy="sale_info">{{ old('sale_info') }}</textarea>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <h4 class="form-section mb-2">Purchase information</h4>
            <div class="form-group">
                <label class="control-label col-md-5">Cost price </label>
                <div class="col-md-7">
                    <input type="number" step="0.01" max="999999999" name="purchase_cost" class="form-control" value="" data-cy="purchase_cost">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-5">Description </label>
                <div class="col-md-7">
                    <textarea maxlength="100" name="purchase_info" class="form-control" data-cy="purchase_info">{{ old('purchase_info') }}</textarea>
                </div>
            </div>
        </div>
    </div>
    <div class="row" id="manage_inventory_row" style="display:none">
        @if($enable_inventory==1)
        <div class="col-md-6">
            <div class="form-group stock_keep_div_simple">
                <label class="control-label col-md-5">Manage inventory <i class="popovers fa fa-info-circle support blue" data-placement="top" data-container="body" data-trigger="hover" data-content="Add stock keeping information" data-original-title="" title=""></i></label>
                <div class="col-md-7">
                    <input type="hidden" name="has_stock_keeping" value="0" id="has_stock_keeping" data-cy="has_stock_keeping">
                    <input type="checkbox" name="is_stock_keeping" id="has_inventory" onchange="stockDivEnable(this.checked,'stock_details_simple','has_stock_keeping')" value="1" class="make-switch" data-on-text="&nbsp;On&nbsp;&nbsp;" data-off-text="&nbsp;Off&nbsp;" data-cy="is_stock_keeping">
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
                    <input type="checkbox" name="is_stock_keeping" id="has_inventory" onchange="enableInventoryCTA(this.checked)" class="make-switch form-control" data-on-text="&nbsp;On&nbsp;&nbsp;" data-off-text="&nbsp;Off&nbsp;" data-cy="is_stock_keeping">
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
    <div class="stock_details_simple" style="display:none">
        <h4 class="form-section mb-2">Stock keeping units</h4>
        <div class="row">
            <div class="col-md-6">
                {{-- <div class="form-group">
                        <label class="control-label col-md-5">Minimum Order </label>
                        <div class="col-md-7">
                            <input type="number" name="minimum_order" class="form-control" value="{{ old('minimum_order') }}">
            </div>
        </div> --}}
        <div class="form-group">
            <label class="control-label col-md-5">Available stock <span class="required">*
                </span></label>
            <div class="col-md-7">
                <input type="number" step="0" name="available_stock" class="form-control" id="available_stock" value="" data-cy="available_stock">
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label col-md-5">Minimum stock </label>
            <div class="col-md-7">
                <input type="number" step="0" name="minimum_stock" class="form-control" value="" data-cy="minimum_stock">
            </div>
        </div>
        {{-- <div class="form-group">
                        <label class="control-label col-md-5">As of Date <span class="required">*
                        </span></label>
                        <div class="col-md-7">
                            <input class="form-control form-control-inline date-picker" id="as_of_date" value="{{date('d-M-Y')}}" data-date-format="dd-M-yyyy" name="as_of_date" type="text" />
    </div>
</div> --}}
</div>
</div>
</div>
@endif
<div id="variable_product_div" {{(old('goods_type') == 'variable') ? 'style=display:block' : 'style=display:none'}}>
    @livewire('product.create-variation-row', ['enable_inventory'=>$enable_inventory,'product_id'=> null, 'var_rows'=> old('price')])
</div>
</div>