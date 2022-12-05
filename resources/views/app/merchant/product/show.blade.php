@extends('app.master')

@section('content')
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="page-bar">
        <span class="page-title" style="float: left;">{{$title}}</span>
        {{ Breadcrumbs::render('product.show',$product->product_name) }}
        <a href="/merchant/product/edit/{{$encrypted_id}}" class="btn green pull-right"><i class="fa fa-edit"></i> Edit</a>
    </div>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12">
            <div class="panel-group accordion" id="accordion1">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1" href="#collapse_1">
                                <b>Inventory details</b> </a>
                        </h4>
                    </div>
                    <div id="collapse_1" class="panel-collapse {{($type=='show') ? 'collapse in' : 'collapse'}}">
                        <div class="panel-body">
                            <div class="portlet-body form">
                                <h4 class="form-section">Basic details</h4>
                                <div class="form-body">
                                    <!-- Start profile details -->
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">
                                                        @if($product->type=='Goods' && !empty($product->type))
                                                        Product
                                                        @else
                                                        Service
                                                        @endif
                                                        name
                                                    </label>
                                                    <label class="control-label col-md-6">{{$product->product_name}}</label>
                                                    <div class="help-inline"></div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">
                                                        @if($product->type=='Goods' && !empty($product->type))
                                                        Product
                                                        @else
                                                        Service
                                                        @endif
                                                        Number
                                                    </label>
                                                    <label class="control-label col-md-6">{{$product->product_number}}</label>
                                                    <div class="help-inline"></div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">Type</label>
                                                    <label class="control-label col-md-6">
                                                        @if($product->type=='Goods' && !empty($product->type))
                                                        Product [{{$product->goods_type}}]
                                                        @else
                                                        Service
                                                        @endif
                                                    </label>
                                                    <div class="help-inline"></div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">HSN/SAC code</label>
                                                    <label class="control-label col-md-6">{{ (!empty($product->sac_code)) ? $product->sac_code : '-'}}</label>
                                                    <div class="help-inline"></div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">Unit type</label>
                                                    <label class="control-label col-md-6">{{ (!empty($product->unit_type)) ? $product->unit_type : '-'}}</label>
                                                    <div class="help-inline"></div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">Description</label>
                                                    <label class="control-label col-md-6">{{ (!empty($product->description)) ? $product->description : '-'}}</label>
                                                    <div class="help-inline"></div>
                                                </div>
                                            </div>
                                            @if($product->goods_type=='simple' || $product->type=='Service')
                                            <div class="row">
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">Expiry Date</label>
                                                    <label class="control-label col-md-6">{{ (!empty($product->product_expiry_date)) ? $product->product_expiry_date : '-'}}</label>
                                                    <div class="help-inline"></div>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="col-md-6">
                                            @if($product->goods_type=='simple' || $product->type=='Service')
                                            <div class="row">
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">SKU</label>
                                                    <label class="control-label col-md-6">{{ (!empty($product->sku)) ? $product->sku : '-'}}</label>
                                                    <div class="help-inline"></div>
                                                </div>
                                            </div>
                                            @endif
                                            <div class="row">
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">GST applicable</label>
                                                    <label class="control-label col-md-6">{{$product->gst_percent}}%</label>
                                                    <div class="help-inline"></div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">Category</label>
                                                    <label class="control-label col-md-6">{{$product->category_name}}</label>
                                                    <div class="help-inline"></div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">Vendor</label>
                                                    <label class="control-label col-md-6">{{$product->vendor_name}}</label>
                                                    <div class="help-inline"></div>
                                                </div>
                                            </div>
                                            @if($product->type=='Goods' && !empty($product->type) && $product->goods_type=='simple')
                                            <div class="row">
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">Image</label>
                                                    @if (isset($product->product_image) && !empty($product->product_image))
                                                    <a target="_BLANK" href="{{$product->product_image}}" title="Click to view full size"><img src="{{$product->product_image}}" height="100" width="100"></a>
                                                    @else
                                                    <label class="control-label col-md-6">No Image added</label>
                                                    @endif
                                                    <div class="help-inline"></div>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    <!-- End profile details -->
                                </div>
                                @if($product->goods_type=='simple' || $product->type=='Service')
                                <h4 class="form-section">Sale & purchase information</h4>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="form-group">
                                                <label class="control-label col-md-4">MRP</label>
                                                <label class="control-label col-md-6">{{ (!empty($product->mrp)) ? $product->mrp : '-'}}</label>
                                                <div class="help-inline"></div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group">
                                                <label class="control-label col-md-4">Sale price</label>
                                                <label class="control-label col-md-6">{{ (!empty($product->price)) ? $product->price : '-'}}</label>
                                                <div class="help-inline"></div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group">
                                                <label class="control-label col-md-4">Sale info</label>
                                                <label class="control-label col-md-6">{{ (!empty($product->sale_info)) ? $product->sale_info : '-'}}</label>
                                                <div class="help-inline"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="form-group">
                                                <label class="control-label col-md-4">Purchase cost</label>
                                                <label class="control-label col-md-6">{{ (!empty($product->purchase_cost)) ? $product->purchase_cost : '-'}}</label>
                                                <div class="help-inline"></div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group">
                                                <label class="control-label col-md-4">Purchase info</label>
                                                <label class="control-label col-md-6">{{ (!empty($product->purchase_info)) ? $product->purchase_info : '-'}}</label>
                                                <div class="help-inline"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if($product->has_stock_keeping =='1' && $enable_inventory==1)
                                <h4 class="form-section">Stock keeping information</h4>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="form-group">
                                                <label class="control-label col-md-4">Available stock</label>
                                                <label class="control-label col-md-6">{{ (!empty($product->available_stock)) ? round($product->available_stock) : '-'}}</label>
                                                <div class="help-inline"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="form-group">
                                                <label class="control-label col-md-4">Minimum stock</label>
                                                <label class="control-label col-md-6">{{ (!empty($product->minimum_stock)) ? round($product->minimum_stock) : '-'}}</label>
                                                <div class="help-inline"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                @endif

                                @if($product->goods_type=='variable' && $product->parent_id==0)
                                <hr>
                                <h4 class="form-section">Variable products</h4>
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-striped table-hover dataTable no-footer" id="table-no-export">
                                            <thead>
                                                <tr>
                                                    <th class="td-c">
                                                        Product Name
                                                    </th>
                                                    <th class="td-c">
                                                        Image
                                                    </th>
                                                    <th class="td-c">
                                                        SKU
                                                    </th>
                                                    <th class="td-c">
                                                        Expiry Date
                                                    </th>
                                                    <th class="td-c">
                                                        Sale price
                                                    </th>
                                                    <th class="td-c">
                                                        MRP
                                                    </th>
                                                    <th class="td-c">
                                                        Purchase cost
                                                    </th>
                                                    <th class="td-c">
                                                        Manage inventory
                                                    </th>
                                                    <th class="td-c">
                                                        Available stock
                                                    </th>
                                                    <th class="td-c">
                                                        Minimum stock
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(!empty($variableProducts))
                                                @foreach($variableProducts as $pk=>$varProduct)
                                                <tr>
                                                    <td class="td-c">{{$varProduct->product_name}}</td>
                                                    <td class="td-c">
                                                        @if($varProduct->product_image != '' || $varProduct->product_image != null)
                                                        <img src="{{$varProduct->product_image}}" width="90" height="90" />
                                                        @else
                                                        -
                                                        @endif
                                                    </td>
                                                    <td class="td-c">{{isset($varProduct->sku) ? $varProduct->sku : '-'}}</td>
                                                    <td class="td-c">{{$varProduct->product_expiry_date}}</td>
                                                    <td class="td-c">{{$varProduct->price}}</td>
                                                    <td class="td-c">{{$varProduct->mrp}}</td>
                                                    <td class="td-c">{{$varProduct->purchase_cost}}</td>
                                                    <td class="td-c">{{($varProduct->has_stock_keeping==1) ? 'Yes' : 'No'}}</td>
                                                    <td class="td-c">{{$varProduct->available_stock}}</td>
                                                    <td class="td-c">{{$varProduct->minimum_stock}}</td>
                                                </tr>
                                                @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @if($enable_inventory==1 && ($product->type=='Goods' && $product->has_stock_keeping=='1' || $product->goods_type=='variable' && $product->parent_id==0))
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1" href="#collapse_2">
                                <b>Stock ledger</b> </a>
                        </h4>
                    </div>
                    <div id="collapse_2" class="panel-collapse {{($type!='show') ? 'collapse in' : 'collapse'}}">
                        <div class="panel-body">
                            <div class="portlet light bordered">
                                <div class="portlet-body form">
                                    <table class="table table-striped table-hover dataTable no-footer" id="table-ellipsis-small">
                                        <thead>
                                            <tr>
                                                <th class="td-c">
                                                    Product
                                                </th>
                                                <th class="td-c">
                                                    Date
                                                </th>
                                                <th class="td-c">
                                                    Quantity
                                                </th>
                                                <th class="td-c">
                                                    Amount
                                                </th>
                                                <th class="td-c">
                                                    Reference id
                                                </th>
                                                {{-- <th class="td-c">
                                                        Reference type
                                                    </th> --}}
                                                <th class="td-c">
                                                    Narrative
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(!empty($stockLedger))
                                            @foreach($stockLedger as $sk=>$stock)
                                            @foreach($stock as $st=>$stk)
                                            <tr>
                                                <td class="td-c">{{$stk->product_name}}</td>
                                                <td class="td-c">{{ Helpers::htmlDate($stk->created_date) }}</td>
                                                <td class="td-c">{{$stk->quantity}}</td>
                                                <td class="td-c">{{$stk->amount}}</td>
                                                <td class="td-c">
                                                    <a href="@if(!empty($stk->reference_link)){{$stk->reference_link}}@endif" target="_blank">{{$stk->reference_no}}</a>
                                                </td>
                                                {{-- <td class="td-c">{{$stock->reference_type}}</td> --}}
                                                <td class="td-c">{{$stk->narrative}}</td>
                                            </tr>
                                            @endforeach
                                            @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
        <!-- END PAGE CONTENT-->
    </div>
</div>
<!-- END CONTENT -->
@endsection