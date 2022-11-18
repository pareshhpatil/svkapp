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
        {{ Breadcrumbs::render('home.orderupdate') }}
    </div>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    @if($contract_id =='')
    <div class="row">
        <div class="col-md-12">
            <div class="portlet ">
                <div class="portlet-body" data-tour="invoice-pick-format">
                    <form action="" method="post" id="template_create" class="form-horizontal form-row-sepe mb-0">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="form-body">
                            <div class="form-group mb-0">
                                <div class="col-md-12">
                                    <div class="col-md-3 pl-1 pr-0">
                                        <select data-placeholder="Select contract" required class="form-control select2me" name="contract_id">
                                            <option value=""></option>
                                            @foreach($contract as $v)
                                            <option @if($contract_id==$v->contract_id) selected @endif value="{{$v->contract_id}}">{{$v->project_name}} | {{$v->contract_code}}</option>
                                            @endforeach
                                        </select>
                                        <small class="form-text text-muted">Contract</small>
                                        <div class="help-block"></div>
                                    </div>
                                    <div class="col-md-7 pl-1" style="width: auto;">
                                        <button type="submit" class="btn blue"> Select</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="row">
        <div class="col-md-12">
            @include('layouts.alerts')
            <div id="change_order_amount_error" class="alert alert-block alert-danger fade in" style="display:none;">
                <button type="button" class="close" data-dismiss="alert"></button>
                <p>Error! Change Order Amount cannot be 0</p>
            </div>
            <div class="portlet light bordered">
                <div class="portlet-body form">
                    <!--<h3 class="form-section">Profile details</h3>-->
                    <form action="/merchant/order/save" id="frm_expense" onsubmit="return changerOrderAmountCheck();loader();" method="post" enctype="multipart/form-data" class="form-horizontal form-row-sepe">
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
                                            <input type="text" disabled maxlength="45" data-cy="contract_no" name="contract_no" class="form-control" data-cy="invoice_no" value="{{$detail->contract_code}}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Change order number <span class="required">*
                                            </span></label>
                                        <div class="col-md-8">
                                            <input type="text" required maxlength="45" data-cy="change_order_no" name="order_no" class="form-control" data-cy="change_order_no" value="">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Change order description <span class="required">
                                            </span></label>
                                        <div class="col-md-8">
                                            <input type="text" maxlength="45" data-cy="change_order_desc" name="order_desc" class="form-control" data-cy="change_order_desc" value="">
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
                                            <input class="form-control form-control-inline date-picker" type="text" required data-cy="order_date" name="order_date" autocomplete="off" data-date-format="{{ Session::get('default_date_format')}}" placeholder="Change Order date" value='<x-localize :date=" old('order_date')" type="date" />' />
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
                        <h3 class="form-section">Add particulars
                            <a data-cy="add_particulars_btn" href="javascript:;" onclick="AddInvoiceParticularRowOrderV2();" class="btn green pull-right mb-1"> Add new row </a>
                        </h3>
                        <div class="table-scrollable">
                            <table class="table table-bordered table-hover" id="particular_table">

                                <thead>
                                    <tr>
                                        <th class="td-c col-id-no" scope="row">
                                            Bill code
                                        </th>
                                        <th class="td-c">
                                            Original contract amount
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
                                        @if ($v == 'original_contract_amount')
                                        <td class="td-r">
                                            <input numbercom="yes" onkeyup="updateTextView($(this));" type="text" onblur="calculateRetainage();" data-cy="particular_{{$v}}{{$key+1}}" class="form-control input-sm" value="{{number_format($row[$v],2)}}" id="{{$v}}{{$key+1}}" name="{{$v}}[]" readonly />
                                        </td>
                                        @elseif ($v == 'bill_code')
                                        <td class="col-id-no">
                                            <div class="text-center">
                                                @if(!empty($csi_code))
                                                @foreach($csi_code as $pk=>$vk)
                                                @if($row[$v]==$vk->code)
                                                <label selected="" value="{{$vk->code}}">{{$vk->title}} | {{$vk->code}}</label>
                                                <input type="hidden" name="bill_code[]" value="{{$vk->code}}">
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
                                        @elseif ($v == 'unit' || $v == 'rate')
                                        <td class="col-id-no">
                                            <input numbercom="yes" onkeyup="updateTextView($(this));" type="text" data-cy="particular_{{$v}}{{$key+1}}" class="form-control input-sm" value="" id="{{$v}}{{$key+1}}" name="{{$v}}[]" onblur="calculateChangeOrder()" />
                                        </td>
                                        @elseif ($v == 'change_order_amount')
                                        <td class="col-id-no">
                                            <input type="text" readonly data-cy="particular_{{$v}}{{$key+1}}" class="form-control input-sm" value="" id="{{$v}}{{$key+1}}" name="{{$v}}[]" onblur="calculateChangeOrder()" />
                                        </td>
                                        @elseif ($v == 'order_description')
                                        <td class="col-id-no">
                                            <input type="text" maxlength="200"  onkeypress="return limitMe(event, this)" data-cy="particular_{{$v}}{{$key+1}}" class="form-control input-sm" value="" id="{{$v}}{{$key+1}}" name="{{$v}}[]" />
                                        </td>
                                        @else
                                        <td>
                                            <input type="text" data-cy="particular_{{$v}}{{$key+1}}" class="form-control input-sm" value="" id="{{$v}}{{$key+1}}" name="{{$v}}[]" />
                                        </td>
                                        @endif
                                        @endforeach
                                        <input type="hidden" id="description-hidden{{$key+1}}" name="description[]" value="@isset($row['description']){{$row['description']}}@endisset">
                                        <input type="hidden" id="pint{{$key+1}}" name="pint[]" value="{{$key+1}}">
                                        <td class="td-c">
                                            <button data-cy="particular-remove{{$key+1}}" onclick="$(this).closest('tr').remove();addLastRowAddButton();" type="button" class="btn btn-xs red">×</button>
                                            <span id="addRowButton{{$key+1}}">
                                                @if($key == count($detail->json_particulars)-1)
                                                    <a href="javascript:;" onclick="AddInvoiceParticularRowOrderV2();" class="btn btn-xs green">+</a>
                                                @endif
                                            </span>
{{--                                            <a data-cy="particular-remove{{$key+1}}" href="javascript:;" onclick="$(this).closest('tr').remove();calculateRetainage();" class="btn btn-xs red"> <i class="fa fa-times"> </i> </a>--}}
                                        </td>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="warning">
                                        <th class="col-id-no"></th>
                                        <th></th>
                                        <th></th>
                                        <th>Grand total</th>
                                        <th class="td-c">
                                            <input type="text" id="particulartotal1" data-cy="particular-total1" name="totalcost" value="0" class="form-control input-sm" readonly>
                                        </th>
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
                                        <input type="text" readonly maxlength="45" name="total_original_contract_amount" class="form-control" data-cy="total_original_contract_amount" value="{{$detail->contract_amount}}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-4">Total value of the change order <span class="required">
                                        </span></label>
                                    <div class="col-md-8">
                                        <input type="text" readonly maxlength="45" name="total_change_order_amount" class="form-control" data-cy="total_change_order_amount" id="total_change_order_amount" value="0">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End profile details -->
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="pull-right">
                                        <input type="hidden" value="{{$detail->contract_amount}}" id="contract_amount" name="contract_amount">
                                        <input type="hidden" value="{{$contract_id}}" name="contract_id">
                                        <input type="hidden" value="0" name="status">
                                        <a href="/merchant/order/list" class="btn default">Cancel</a>
                                        <input type="submit" value="{{$button}}" class="btn blue" data-cy="expense_dave" />
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
    @endif
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
    @if(isset($project_id))
    project_id = {!!$project_id!!};
    @endif
</script>
@section('footer')
@endsection