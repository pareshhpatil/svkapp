@extends('app.master')
<style>
    .lable-heading {
        font-style: normal;
        font-weight: 400;
        font-size: 14px;
        line-height: 24px;
        color: #767676;
        margin-bottom: 0px;
        margin-top: 5px;
    }

    .col-id-no {
        position: sticky !important;
        left: 0;
        z-index: 2;
        border-right: 2px solid #D9DEDE !important;
        background-color: #fff;
    }

    .table thead tr th {
        font-size: 12px !important;
        padding: 3px !important;
        font-weight: 400;
        color: #333;
    }

    .table tfoot tr th {
        font-size: 1rem !important;
        padding: 3px !important;
        font-weight: bold;
        color: #333;
    }

    .table>tbody>tr>td {
        font-size: 12px !important;
        padding: 3px !important;
        border: 1px solid #D9DEDE;
        border-right: 0px;
        border-left: 0px;
        vertical-align: middle !important;
    }

    .table>tbody>tr>td>div>label {
        margin-bottom: 0px !important;
    }
</style>
@section('content')
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="page-bar">
        <span class="page-title" style="float: left;">{{$title}}</span>
        {{ Breadcrumbs::render('home.ordercreate') }}
    </div>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12">
            @include('layouts.alerts')

            <div class="portlet light bordered">
                <div class="portlet-body form">
                    <!--<h3 class="form-section">Profile details</h3>-->
                    <form action="/merchant/order/updatesave" id="frm_expense" onsubmit="loader();" method="post" enctype="multipart/form-data" class="form-horizontal form-row-sepe">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="form-body">
                            <!-- Start profile details -->
                            <div class="row">
                                <div class="col-md-6">
                                    <h3 class="form-section">
                                        Change order information
                                    </h3>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Contract number <span class="required">
                                            </span></label>
                                        <div class="col-md-8">
                                            <input type="text" readonly maxlength="45" data-cy="contract_no" name="contract_no" class="form-control" data-cy="invoice_no" value="{{$detail2->contract_code}}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Change order number <span class="required">
                                            </span></label>
                                        <div class="col-md-8">
                                            <input type="text" readonly maxlength="45" data-cy="change_order_no" name="order_no" class="form-control" data-cy="change_order_no" value="{{$detail->order_no}}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Change order description <span class="required">
                                            </span></label>
                                        <div class="col-md-8">
                                            <input type="text" readonly maxlength="45" data-cy="change_order_desc" name="order_desc" class="form-control" data-cy="change_order_desc" value="{{$detail->order_desc}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h3 class="form-section">
                                        Change order information
                                    </h3>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Change order date<span class="required">*
                                            </span></label>
                                        <div class="col-md-8">
                                            <input class="form-control form-control-inline date-picker" readonly type="text" required data-cy="order_date" name="order_date" autocomplete="off" data-date-format="{{ Session::get('default_date_format')}}" placeholder="Change Order date" value='@if(isset($detail->order_date))<x-localize :date="$detail->order_date" type="date" />@endif' />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Status<span class="required">
                                            </span></label>
                                        <div class="col-md-8">
                                        <input class="form-control form-control-inline" readonly type="text" required  value="Approved"  />

                                           
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Project details<span class="required">
                                            </span></label>
                                        <div class="col-md-8">
                                            <input type="text" disabled maxlength="45" data-cy="project_details" name="project_details" class="form-control" data-cy="project_details" value="{{$project_details->project_id}} | {{$project_details->project_name}}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-scrollable">
                            <table class="table table-bordered table-hover" id="particular_table">
                                <thead class="headFootZIndex">
                                    <tr>
                                        <th class="td-c col-id-no" scope="row">
                                            Bill code
                                        </th>
                                        <th class="td-c">
                                            Cost Type
                                        </th>
                                        <th class="td-c">
                                            CO Type
                                        </th>
                                        <th class="td-c">
                                            Original contract amount
                                        </th>
                                        <th class="td-c">
                                            Retainage percentage
                                        </th>
                                        <th colspan="3" class="td-c">
                                            Change order amount
                                        </th>
                                        <th class="td-c">
                                            Description
                                        </th>
                                        <th class="td-c">
                                            Group level 1
                                        </th>
                                        <th class="td-c">
                                            Group level 2
                                        </th>
                                      
                                    </tr>
                                </thead>


                                <tbody id="new_particular">
                                    @foreach($detail->json_particulars as $key=>$row)
                                    @php
                                    if(!isset($row['co_type']))
                                    {
                                    $row['co_type']=1;
                                    }
                                    if(!isset($row['budget_reallocation']))
                                    {
                                    $row['budget_reallocation']='';
                                    }
                                    if($row['co_type']==1)
                                    {
                                    $co_type=1;
                                    $up='';
                                    $bd='style=display:none;';
                                    }else{
                                    $bd='';
                                    $up='style=display:none;';
                                    $co_type=2;
                                    }

                                    $is_calculated = false;
                                    @endphp
                                    <tr id="pint{{$key+1}}">

                                        <td class="col-id-no">
                                            <div class="text-center">
                                                @if(!empty($csi_code))
                                                @foreach($csi_code as $pk=>$vk)
                                                @if($row['bill_code']==$vk->id)
                                                {{$vk->code}} | {{$vk->title}}
                                                @endif
                                                @endforeach
                                                @endif
                                                <div class="text-center">
                                                    <p id="description{{$key+1}}" style="display: none;" class="lable-heading">
                                                        @isset($row['description'])
                                                        {{$row['description']}}
                                                        @endisset
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="td-c onhover-border" scope="row">
                                            <div class="text-center">
                                                @if(!empty($cost_type_list))
                                                @foreach($cost_type_list as $pk=>$vk)
                                                @if($row['cost_type']==$vk->id)
                                                {{$vk->abbrevation}} - {{$vk->name}}
                                                @endif
                                                @endforeach
                                                @endif
                                            </div>
                                        </td>
                                        <td class="td-c onhover-border" scope="row">
                                            @if($row['co_type']==1) Unit / Price @endif
                                            @if($row['co_type']==2) Budget reallocation @endif
                                            @if($row['co_type']==3) Fixed amount@endif
                                            @if($row['co_type']==4) Subcontract @endif
                                        </td>
                                        <td class="td-r onhover-border">
                                            {{number_format($row['original_contract_amount'],2)}}
                                        </td>
                                        <td class="td-c onhover-border">
                                            {{$row['retainage_percent']}}
                                        </td>
                                        <td class="td-c onhover-border" {{$up}} id="td_unit{{$key+1}}">
                                            {{$row['unit']}}
                                        </td>
                                        <td class="td-c onhover-border" {{$up}} id="td_rate{{$key+1}}">
                                            {{$row['rate']}}
                                        </td>
                                        <td class="td-c onhover-border" {{$up}} id="td_co_amount{{$key+1}}">
                                            {{$row['change_order_amount']}}
                                        </td>
                                        <td class="td-c onhover-border" colspan="3" {{$bd}} id="td_budget{{$key+1}}">
                                            {{$row['change_order_amount']}}
                                        </td>
                                        <td class="td-c onhover-border">
                                            
                                        </td>
                                        <td class="td-c onhover-border">
                                            <div class="text-center">
                                                {{$row['group']}}
                                            </div>
                                        </td>
                                        <td class="td-c onhover-border">
                                            <div class="text-center">
                                                <div id="sub_group{{$key+1}}"></div>
                                            </div>
                                        </td>


                                        
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="headFootZIndex">
                                    <tr class="warning">
                                        <th class="col-id-no">Grand total</th>
                                        <th></th>
                                        <th class="td-c">
                                            
                                        </th>
                                        <th></th>
                                        <th></th>

                                        <th colspan="3" class="text-center">
                                            {{$detail->total_change_order_amount}}
                                        </th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <br>

                        <div class="row">
                            <div class="col-md-6">
                                <h3 class="form-section">
                                    Summary
                                </h3>
                                <div class="form-group">
                                    <label class="control-label col-md-4">Original contract value <span class="required">
                                        </span></label>
                                    <div class="col-md-8">
                                        <input type="text" readonly maxlength="45" name="total_original_contract_amount" class="form-control" data-cy="total_original_contract_amount" value="@if($detail->total_original_contract_amount < 0)({{str_replace('-','',number_format($detail->total_original_contract_amount,2))}}) @else{{$detail->total_original_contract_amount}}@endif">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-4">Total value of the change order <span class="required">
                                        </span></label>
                                    <div class="col-md-8">
                                        <input type="text" readonly maxlength="45" name="total_change_order_amount" class="form-control" data-cy="total_change_order_amount" id="total_change_order_amount" value="@if($detail->total_change_order_amount < 0)({{str_replace('-','',number_format($detail->total_change_order_amount,2))}}) @else{{$detail->total_change_order_amount}}@endif">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End profile details -->
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="pull-right">
                                        <input type="hidden" value="{{$detail->total_original_contract_amount}}" id="contract_amount" name="contract_amount">
                                        <input type="hidden" value="{{$detail->contract_id}}" name="contract_id">
                                        <input type="hidden" value="{{$link}}" name="link">
                                        <a href="/merchant/order/list/{{$type}}" class="btn default">Back</a>
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
@include('app.merchant.contract.add-calculation-modal')
@include('app.merchant.contract.add-bill-code-modal')
@endsection
<script>
    mode = '{{$mode}}';
    @if(isset($csi_code))
    csi_codes = {
        !!$csi_code_json!!
    };
    @endif
    @if(isset($cost_type_list))
    cost_type_list = {
        !!$cost_type_list_json!!
    };
    @endif
</script>
@section('footer')
<script>
    calculateChangeOrder('approved');
</script>
@endsection