@extends('app.master')

@section('content')

<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="page-bar">  
    <span class="page-title" style="float: left;">{{$title}}</span>
    {{ Breadcrumbs::render() }}
    <a href="/merchant/product/createnew" class="btn blue pull-right mb-1"> Create Product/Service</a>
    </div>
    <!-- END PAGE HEADER-->

    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        @include('layouts.alerts')
        <div class="col-md-12">
            <div class="portlet ">
                <div class="portlet-body">
                    <table class="table table-striped table-bordered table-hover dataTable no-footer" id="table-no-export">
                        <thead>
                            <tr>
                                <th class="td-c">
                                    #
                                </th>
                                <th class="td-c">
                                    Name
                                </th>
                                <th class="td-c">
                                    Type
                                </th>
                                <th class="td-c">
                                    HSN/SAC code
                                </th>
                                <th class="td-c">
                                    Unit type
                                </th>
                                <th class="td-c">
                                    Sale Price
                                </th>
                                <th class="td-c">
                                    GST
                                </th>
                                <th class="td-c">
                                    Available stock
                                </th>
                                <th class="td-c">
                                    
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                        <form action="" method="">
                            @foreach($products as $product) 
                            <tr>
                                    <td class="td-c">
                                        {{$product['product_id']}}
                                    </td>
                                    <td class="td-c">
                                        <a href="/merchant/product/edit/{{$product['encrypted_id']}}">{{$product['product_name']}}</a>
                                    </td>
                                    <td class="td-c">
                                        @if($product['type']=='Goods' && !empty($product['type'])) 
                                            Product @if($product['goods_type']=='variable') [{{$product['variations']}} variations] @endif
                                        @else
                                            Service 
                                        @endif
                                    </td>
                                    <td class="td-c">
                                        <!-- Display hsn or sac code according to product type -->
                                        {{$product['sac_code']}}
                                    </td>
                                    <td class="td-c">
                                        {{$product['unit_type']}}
                                    </td>
                                    <td class="td-c">
                                        {{$product['price']}}
                                    </td>
                                    <td class="td-c">
                                        {{$product['gst_percent']}}
                                    </td>
                                    <td class="td-c">
                                        @if($enable_inventory==1 && $product['type']=='Goods' && $product['has_stock_keeping']==1)
                                            {{round($product['available_stock'])}}
                                        @elseif($enable_inventory==1 && $product['goods_type']=='variable') 
                                            {{round($product['available_stock'])}}
                                        @endif
                                    </td>
                                    <td class="td-c">
                                        <div class="btn-group dropup">
                                            <button class="btn btn-xs btn-link dropdown-toggle" type="button" data-toggle="dropdown">
                                                &nbsp;&nbsp;<i class="fa fa-ellipsis-v"></i>&nbsp;&nbsp;
                                            </button>
                                            <ul class="dropdown-menu" role="menu">
                                                <li>
                                                    <a href="/merchant/product/show/{{$product['encrypted_id']}}"><i class="fa fa-table"></i> View </a>
                                                </li>
                                                @if($enable_inventory==1 && ($product['type']=='Goods' && $product['has_stock_keeping']=='1' || $product['goods_type']=='variable' && $product['parent_id']==0)) 
                                                    <li>
                                                        <a href="/merchant/product/ledger/{{$product['encrypted_id']}}"><i class="fa fa-plus"></i> Stock ledger </a>
                                                    </li>
                                                @endif
                                                <li>
                                                    <a href="/merchant/product/edit/{{$product['encrypted_id']}}"><i class="fa fa-edit"></i> Edit </a>
                                                </li>
                                                <li>
                                                    <a href="#basic" onclick="document.getElementById('deleteanchor').href = '/merchant/product/delete/{{$product['encrypted_id']}}'" data-toggle="modal"><i class="fa fa-times"></i> Delete </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </form>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- END PAYMENT TRANSACTION TABLE -->
        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>
</div>
<!-- END CONTENT -->


<div class="modal fade" id="basic" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Delete Product</h4>
            </div>
            <div class="modal-body">
                Are you sure you would not like to use this product in the future?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn default" data-dismiss="modal">Close</button>
                <a href="" id="deleteanchor" class="btn delete">Confirm</a>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<script>
    list_name = '{{$list_name}}';
</script>
@endsection