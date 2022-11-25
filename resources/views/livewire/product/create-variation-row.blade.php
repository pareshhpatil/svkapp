<div>
    <h4 class="form-section mb-2">Variations
        <a href="javascript:;" wire:click="addRow()" class="btn btn-sm green pull-right mb-1"> <i class="fa fa-plus"> </i> Add new row </a>
    </h4>
    <div class="table-scrollable">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th class="td-c default-font">
                        <label class="control-label">Product attributes <span class="required">*</span></label>
                    </th>
                    <th class="td-c  default-font">
                        <label class="control-label"> Product image </label>
                    </th>
                    <th class="td-c  default-font">
                        <label class="control-label"> SKU </label>
                    </th>
                    <th class="td-c  default-font">
                        <label class="control-label"> Expiry Date </label>
                    </th>
                    <th class="td-c  default-font">
                        <label class="control-label"> Sale price <span class="required">*</span></label>
                    </th>
                    <th class="td-c  default-font">
                        <label class="control-label"> MRP</label>
                    </th>
                    <th class="td-c  default-font">
                        <label class="control-label"> Cost price </label>
                    </th>
                    @if($enable_inventory==1)
                    <th class="td-c  default-font">
                        Inventory & Stock Keeping <i class="popovers fa fa-info-circle support blue" data-placement="top" data-container="body" data-trigger="hover" data-content="Toggle Manage inventory to On to maintain available stock and minimum stock of your product" data-original-title="" title=""></i>
                    </th>
                    @endif
                    <th class="td-c">

                    </th>
                </tr>
            </thead>
            <tbody id="new_variable_product">
                @php $int =1; @endphp
                @if(!@empty($variationsRow))
                @foreach($variationsRow as $key => $value)
                <tr>
                    {{-- {{print_r($this->getProductAttributeValues[$value['variable_product_id']])}} --}}
                    <td>
                        @if(isset($productAttributes) && !empty($productAttributes))
                        @foreach ($productAttributes as $ak=>$attr)
                        <div class="input-icon right mb-2" wire:ignore wire:key="product_attr_{{$key}}_{{$ak}}">
                            <select id="productAttribute{{$key}}_{{$ak}}" data-placeholder="Select {{$attr['name']}}" name="attribute_values[{{$ak}}][]" class="form-control product_attr" data-cy="product_attributes_{{$key}}_{{$ak}}">
                                <option value="0">Select {{$attr['name']}}</option>
                                @foreach($attr['values'] as $a=>$aval)
                                @if(isset($this->getProductAttributeValues[$value['variable_product_id']]) && array_key_exists($ak,$this->getProductAttributeValues[$value['variable_product_id']]))
                                @if(isset($this->getProductAttributeValues[$value['variable_product_id']]) && !empty($this->getProductAttributeValues[$value['variable_product_id']]))

                                <option value="{{$aval}}" @if($this->getProductAttributeValues[$value['variable_product_id']][$ak]==$aval)selected @endif>{{$aval}}</option>
                                @else
                                <option value="{{$aval}}">{{$aval}}</option>
                                @endif
                                @else
                                <option value="{{$aval}}">{{$aval}}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                        @endforeach
                        @else
                        <span class="text-danger">Please add product variations for adding variable products. To add variations <a href="/merchant/product-attribute/index" target="_blank">Click here</a></span>
                        @endif
                    </td>
                    <td>
                        <div class="input-icon right">
                            @if (isset($value['product_image']) && !empty($value['product_image']))
                            <span class="help-block" id="existImageDiv">
                                <a class="btn btn-xs green" target="_BLANK" href="{{$value['product_image']}}" title="Click to view full size"><img src="{{$value['product_image']}}" height="60" width="60" /></a>
                                <a onclick="document.getElementById('newImageDiv{{$key}}').style.display = 'inline-flex';" class="btn btn-xs btn-link">Update Image</a>
                            </span>
                            <input type="hidden" name="old_image[]" class="form-control" value="{{$value['product_image']}}">
                            <div id="newImageDiv{{$key}}" style="display: none;">
                                <input type="file" name="image[]" onchange="uploadFile(this,{{$key}})" class="form-control" data-cy="product_image_{{$key}}" accept="image/*">
                                <a onclick="document.getElementById('newImageDiv{{$key}}').style.display='none';" class="btn btn-xs red pull-right"><i class="fa fa-remove" style="padding-top:7px;"></i></a>
                                <input type="hidden" name="product_image[]" id="uppy_file{{$key}}" />
                                <span id="error{{$key}}" class="text-danger" wire:model.lazy="variationsRow.{{ $key }}.img_error"><span>
                            </div>
                            @else
                            <input type="file" name="image[]" id="image_upload{{$key}}" onchange="uploadFile(this,{{$key}})" class="form-control" data-cy="product_image_{{$key}}" accept="image/*">
                            <input type="hidden" name="product_image[]" id="uppy_file{{$key}}" />
                            <span id="error{{$key}}" class="text-danger" wire:model.lazy="variationsRow.{{ $key }}.img_error"></span>
                            @endif
                            {{-- <input type="file" name="image[]" onchange="uploadFile(this,{{$key}})" class="form-control" data-cy="product_image_{{$key}}" wire:model.lazy="variationsRow.{{ $key }}.product_image">
                            <input type="hidden" name="product_image[]" id="uppy_file{{$key}}" />
                            <p id="error{{$key}}" class="text-danger">
                            <p> --}}
                        </div>
                    </td>
                    <td>
                        <div class="input-icon right">
                            <input type="text" name="sku[]" class="form-control" placeholder="SKU" data-cy="sku_{{$key}}" wire:model.defer="variationsRow.{{ $key }}.sku">
                        </div>
                    </td>
                    <td>
                        <div class="input-icon right" wire:ignore wire:key="product_expiry_{{$key}}">
                            <input class="form-control form-control-inline" type="date" name="product_expiry_date[]" data-cy="sale_product_expiry_date_{{$key}}" wire:model="variationsRow.{{$key}}.product_expiry_date" autocomplete="off" data-date-format="dd M yyyy" placeholder="Expiry Date">
                        </div>
                    </td>
                    <td>
                        <div class="input-icon right">
                            <input type="number" step="0.01" min="0.01" max="999999999" name="price[]" required class="form-control" data-cy="sale_price_{{$key}}" placeholder="Sale price" wire:model.defer="variationsRow.{{ $key }}.price">
                        </div>
                    </td>
                    <td>
                        <div class="input-icon right">
                            <input type="number" step="0.01" name="mrp[]" max="999999999" class="form-control" data-cy="sale_mrp_{{$key}}" placeholder="MRP price" wire:model.defer="variationsRow.{{ $key }}.mrp">
                        </div>
                    </td>
                    <td>
                        <div class="input-icon right">
                            <input type="number" step="0.01" name="purchase_cost[]" max="999999999" class="form-control" data-cy="purchase_cost_{{$key}}" placeholder="Cost price" wire:model.defer="variationsRow.{{ $key }}.purchase_cost">
                        </div>
                    </td>
                    @if($enable_inventory==1)
                    <td>
                        <div class="stock_keep_div_variable{{$key}}">
                            <input type="hidden" name="has_stock_keeping[]" value="{{(isset($value['has_stock_keeping']) && $value['has_stock_keeping']==1) ? $value['has_stock_keeping'] : 0}}" id="has_stock_keeping{{$key}}" data-cy="has_stock_keeping_{{$key}}">
                            <input type="checkbox" class="form-check" name="is_stock_keeping[]" id="has_inventory{{$key}}" onchange="stockDivEnable(this.checked,'stock_details_variable{{$key}}','has_stock_keeping{{$key}}')" value="{{(isset($value['is_stock_keeping']) && $value['is_stock_keeping']==1) ? $value['is_stock_keeping'] : 0}}" data-cy="is_stock_keeping_{{$key}}" @if(isset($value['has_stock_keeping']) && $value['has_stock_keeping']==1) checked @endif> Manage inventory
                            <br>

                            {{-- <input type="hidden" name="has_stock_keeping[]" value="{{(isset($value['has_stock_keeping']) && $value['has_stock_keeping']==1) ? $value['has_stock_keeping'] : 0}}" id="has_stock_keeping{{$key}}" data-cy="has_stock_keeping_{{$key}}">
                            <input type="checkbox" name="is_stock_keeping[]" id="has_inventory{{$key}}" onchange="stockDivEnable(this.checked,'stock_details_variable{{$key}}','has_stock_keeping{{$key}}')" value="{{(isset($value['is_stock_keeping']) && $value['is_stock_keeping']==1) ? $value['is_stock_keeping'] : 0}}" class="make-switch" data-on-text="&nbsp;On&nbsp;&nbsp;" data-off-text="&nbsp;Off&nbsp;" data-cy="is_stock_keeping_{{$key}}"> --}}

                        </div>
                        <div class="stock_details_variable{{$key}}" {{(isset($value['has_stock_keeping']) && $value['has_stock_keeping']==1) ? 'style=display:block' : 'style=display:none'}}>
                            <label class="control-label mb-2">Stock keeping units</label>
                            <div class="mb-2">
                                <div class="input-icon right">
                                    <input type="number" step="0" min="0" name="available_stock[]" class="form-control" @if($value['available_stock']!=0) readonly @endif id="available_stock{{$key}}" data-cy="available_stock_{{$key}}" placeholder="Available stock" wire:model.defer="variationsRow.{{ $key }}.available_stock">
                                </div>
                            </div>
                            <div class="mb-2">
                                <div class="input-icon right">
                                    <input type="number" step="0" min="0" name="minimum_stock[]" class="form-control" data-cy="minimum_stock_{{$key}}" placeholder="Minimum stock" wire:model.defer="variationsRow.{{ $key }}.minimum_stock">
                                </div>
                            </div>
                        </div>
                    </td>
                    @endif
                    <td>
                        <button type="button" wire:click="deleteID({{$key}})" class="btn btn-sm red" data-toggle="modal" data-target="#deleteModal"><i class="fa fa-times"> </i></button>
                        <input type="hidden" name="variable_product_id[]" data-cy="variationRow_variable_product_id_{{$key}}" wire:model="variationsRow.{{$key}}.variable_product_id">
                        {{-- <a class="btn btn-sm red" wire:click="deleteID({{ $key }});" data-cy="variationRow_remove_{{$key}}" data-toggle="modal" data-target="#deleteModal"> <i class="fa fa-times"> </i></a> --}}
                    </td>
                </tr>
                @php $int++; @endphp
                @endforeach
                @endif
            </tbody>
        </table>
    </div>

    <script>
        document.addEventListener('livewire:load', function(event) {
            Livewire.hook('message.processed', (message, component) => {
                $('.product_attr').select2();
                $('.date-picker').datepicker({
                    rtl: Swipez.isRTL(),
                    orientation: "left",
                    autoclose: true,
                    todayHighlight: true
                });
            });
        });

        function uploadFile(img_file, idNO) {
            //var property = document.getElementById('photo').files[0];
            var file = img_file.files[0];
            var formData = new FormData();
            formData.append('image', file);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/merchant/uppyfileupload/uploadImage', //Server script to process data
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                //Ajax events
                success: function(response) {
                    var returnedData = JSON.parse(response);
                    if (returnedData['status'] == 300) {
                        document.getElementById("error" + idNO).innerHTML = returnedData['errors'];
                        document.getElementById("uppy_file" + idNO).value = '';
                        document.getElementById("image_upload" + idNO).value = '';
                    } else {
                        document.getElementById("error" + idNO).innerHTML = '';
                        document.getElementById("uppy_file" + idNO).value = returnedData['fileUploadPath'];
                    }
                },
                error: function(e) {
                    console.log(e);
                }
            });
            return false;
        }
    </script>
</div>