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
                                            <select class="form-control" readonly name="status">
                                                <option seelcted value="1">Approved</option>
                                            </select>
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

                                <thead>
                                    <tr>
                                        <th class="td-c col-id-no" scope="row">
                                            Bill code
                                        </th>
                                        <th class="td-c">
                                            Cost Type
                                        </th>
                                        <th class="td-c">
                                            Original contract amount
                                        </th>
                                        <th class="td-c">
                                            Retainage percentage
                                        </th>
                                        <th class="td-c">
                                            Unit
                                        </th>
                                        <th class="td-c">
                                            Rate
                                        </th>
                                        <th class="td-c">
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
                                    $is_calculated = false;
                                    @endphp
                                    <tr>
                                        @foreach($default_particulars as $v=>$r)
                                        @if ($v == 'original_contract_amount' )
                                        <td class="td-r">
                                            <input numbercom="yes" type="text" data-cy="particular_{{$v}}{{$key+1}}" class="form-control input-sm" value="@if($row[$v] < 0)({{str_replace('-','',number_format($row[$v],2))}}) @else {{number_format($row[$v],2)}}@endif" id="{{$v}}{{$key+1}}" name="{{$v}}[]" readonly />
                                        </td>
                                        @elseif ($v == 'retainage_percent')
										@if(isset($row[$v]))
										<td class="td-r">
                                            <input numbercom="yes" type="text" data-cy="particular_{{$v}}{{$key+1}}" class="form-control input-sm" value="@if($row[$v] < 0)({{str_replace('-','',number_format($row[$v],2))}}) @else {{number_format($row[$v],2)}}@endif" id="{{$v}}{{$key+1}}" name="{{$v}}[]" readonly />
                                        </td>
										@else
										<td class="td-r">
                                            <input numbercom="yes" type="text" data-cy="particular_{{$v}}{{$key+1}}" class="form-control input-sm" value="" id="{{$v}}{{$key+1}}" name="{{$v}}[]" readonly />
                                        </td>
										@endif
                                        @elseif ($v == 'bill_code')
                                        <td class="col-id-no">
                                            <div class="text-center">
                                                @if(!empty($csi_code))
                                                @foreach($csi_code as $pk=>$vk)
                                                @if($row[$v]==$vk->id)
                                                <label selected="" value="{{$vk->id}}">{{$vk->title}} | {{$vk->code}}</label>
                                                <input type="hidden" name="bill_code[]" value="{{$vk->id}}">
                                                @endif
                                                @endforeach
                                                @endif
                                                <div class="text-center">
                                                    <p id="description{{$key+1}}" style="display:none;" class="lable-heading">
                                                        {{$row['description']}}
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        @elseif ($v == 'unit' || $v == 'rate')
                                        <td class="col-id-no">
                                            <input numbercom="yes" onkeyup="updateTextView($(this));" type="text" readonly data-cy="particular_{{$v}}{{$key+1}}" class="form-control input-sm" value="{{$row[$v]}}" id="{{$v}}{{$key+1}}" name="{{$v}}[]"  />
                                        </td>
                                        @elseif ($v == 'change_order_amount')
                                        <td class="col-id-no">
                                            <input type="text" readonly data-cy="particular_{{$v}}{{$key+1}}" class="form-control input-sm" value="{{$row[$v]}}" id="{{$v}}{{$key+1}}" name="{{$v}}[]" />
                                        </td>
                                        @elseif ($v == 'cost_type')
                                        <td class="col-id-no">
                                            <div class="text-center">
                                                @if(!empty($cost_type_list))
                                                @foreach($cost_type_list as $pk=>$vk)
                                                @if($row[$v]==$vk->id)
                                                <label selected="" value="{{$vk->id}}">{{$vk->abbrevation}} - {{$vk->name}}</label>
                                                <input type="hidden" id="cost_type{{$key+1}}" name="cost_type[]" value="{{$vk->id}}">
                                                @endif
                                                @endforeach
                                                @endif
                                            </div>
                                        </td>
                                        @else
                                        <td>
                                            <input type="text" readonly data-cy="particular_{{$v}}{{$key+1}}" class="form-control input-sm" value="@isset($row[$v]) {{$row[$v]}} @endisset" id="{{$v}}{{$key+1}}" name="{{$v}}[]" />
                                        </td>
                                        @endif
                                        @endforeach
                                        <input type="hidden" id="description-hidden{{$key+1}}" name="description[]" value="{{$row['description']}}">
                                        <input type="hidden" id="pint{{$key+1}}" name="pint[]" value="{{$key+1}}">
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="warning">
                                    <th class="col-id-no">Grand total</th>
                                        <th></th>
                                        <th class="td-c">
                                            <span id="original_contract_amount_total"></span>
                                        </th>
                                        <th class="td-c">
                                            <span id="unit_total"></span>
                                        </th>
                                        <th class="td-c">
                                            <span id="rate_total"></span>
                                        </th>
                                        <th class="td-c">
                                            <input type="text" id="particulartotal1" data-cy="particular-total1" name="totalcost" value="{{$detail->total_change_order_amount}}" class="form-control input-sm" readonly>
                                        </th>
                                        <th></th>
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
    csi_codes = {!!$csi_code_json!!};
    @endif
    @if(isset($cost_type_list))
    cost_type_list = {!!$cost_type_list_json!!};
    @endif
</script>
@section('footer')
<script>
   calculateChangeOrder('approved');
</script>
@endsection