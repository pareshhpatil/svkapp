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

    table thead,
    table tfoot {
        position: sticky;
    }

    table thead {
        inset-block-start: 0;
        /* "top" */
    }

    table tfoot {
        inset-block-end: 0;
        /* "bottom" */
    }

    .tableFixHead {
        overflow: auto !important;
    }

    .table thead tr th {
        font-size: 12px;
        padding: 3px;
        font-weight: 400;
        color: #333;
        background: #eee;
    }

    .headFootZIndex {
        z-index: 3;
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

    .vscomp-toggle-button {
        height: 28px;

    }

    .vs-option {
        z-index: 99;
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
            <div id="change_order_amount_error" class="alert alert-block alert-danger fade in" style="display:none;">
                <button type="button" class="close" data-dismiss="alert"></button>
                <p>Error! Bill code cannot be blank!</p>
            </div>
            <div class="portlet light bordered">
                <div class="portlet-body form">
                    <!--<h3 class="form-section">Profile details</h3>-->
                    <form action="/merchant/order/updatesave" id="frm_expense" onsubmit="return changerOrderAmountCheck();loader();" method="post" enctype="multipart/form-data" class="form-horizontal form-row-sepe">
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
                                            <input type="text" disabled maxlength="45" data-cy="contract_no" name="contract_no" class="form-control" data-cy="invoice_no" value="{{$detail2->contract_code}}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Change order number <span class="required">*
                                            </span></label>
                                        <div class="col-md-8">
                                            <input type="text" required maxlength="45" data-cy="change_order_no" name="order_no" class="form-control" data-cy="change_order_no" value="{{$detail->order_no}}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Change order description <span class="required">
                                            </span></label>
                                        <div class="col-md-8">
                                            <input type="text" maxlength="45" data-cy="change_order_desc" name="order_desc" class="form-control" data-cy="change_order_desc" value="{{$detail->order_desc}}">
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
                                            <input class="form-control form-control-inline date-picker" type="text" required data-cy="order_date" name="order_date" autocomplete="off" data-date-format="{{ Session::get('default_date_format')}}" placeholder="Change Order date" value='@if(isset($detail->order_date))<x-localize :date="$detail->order_date" type="date" />@endif' />
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
                        <div class="row">
                            <div class="col-md-6">
                                <h3 class="form-section">Add Particulars</h3>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <input type="text" id="search" class="form-control" placeholder="Search Item No and Description of work">
                            </div>
                            <div class="col-md-1">
                                <button class="btn green" type='button' onclick="return filterRows();">Search</button>
                            </div>
                            <div class="col-md-3">
                                <input type="hidden" id="dropdown_search" class="form-control" value="0">
                            </div>
                            <div class="col-md-5">
                                <a data-cy="add_particulars_btn" href="javascript:;" onclick="AddInvoiceParticularRowOrderV2();" class="btn green pull-right mb-1"> Add new row </a>
                                <input type="submit" value="Import" name="import" class="btn green pull-right mb-1 mr-1">
                            </div>
                        </div>
                        <!-- <h3 class="form-section">Add particulars
                            <a data-cy="add_particulars_btn" href="javascript:;" onclick="AddInvoiceParticularRowOrderV2();" class="btn green pull-right mb-1"> Add new row </a>

                            <input type="submit" value="Import" name="import" class="btn green pull-right mb-1 mr-1">
                        </h3> -->
                        <div class="table-scrollable tableFixHead">
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
                                        <th class="td-c">

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
                                    <tr id="pint{{$key+1}}" >

                                        <td class="col-id-no">
                                            <div class="text-center">
                                                @if(!empty($csi_code))
                                                @foreach($csi_code as $pk=>$vk)
                                                @if($row['bill_code']==$vk->id)
                                                <label selected="" value="{{$vk->id}}">{{$vk->code}} | {{$vk->title}}</label>
                                                <input type="hidden" id="bill_code{{$key+1}}" name="bill_code[]" value="{{$vk->id}}">
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
                                        <td class="col-id-no" scope="row">
                                            <select id="cost_type{{$key+1}}" name="cost_type[]">
                                                @if(!empty($cost_type_list))
                                                @foreach($cost_type_list as $pk=>$vk)
                                                @if($row['cost_type']==$vk->id)
                                                <option selected value="{{$vk->id}}">{{$vk->abbrevation}} - {{$vk->name}}</option>
                                                @else
                                                <option value="{{$vk->id}}">{{$vk->abbrevation}} - {{$vk->name}}</option>
                                                @endif
                                                @endforeach
                                                @endif
                                            </select>
                                        </td>
                                        <td class="col-id-no" scope="row">
                                            <select id="co_type{{$key+1}}" onchange="setCOType(this.value,{{$key+1}})" class="form-control input-sm" name="co_type[]">
                                                <option @if($row['co_type']==1) selected @endif value="1">Unit / Price</option>
                                                <option @if($row['co_type']==2) selected @endif value="2">Budget reallocation</option>
                                            </select>
                                        </td>
                                        <td class="td-r">
                                            <input numbercom="yes" onkeyup="updateTextView($(this));" type="text" onblur="calculateRetainage();" data-cy="particular_original_contract_amount{{$key+1}}" class="form-control input-sm" value="{{number_format($row['original_contract_amount'],2)}}" id="original_contract_amount{{$key+1}}" name="original_contract_amount[]" readonly />
                                        </td>
                                        <td class="col-id-no">
                                            <input step=".00000000001" max='100' type="number" data-cy="particular_retainage_percent{{$key+1}}" class="form-control input-sm" value="{{$row['retainage_percent']}}" id="retainage_percent{{$key+1}}" name="retainage_percent[]" />
                                        </td>
                                        <td class="col-id-no" {{$up}} id="td_unit{{$key+1}}">
                                            <input step=".00000000001" type="number" data-cy="particular_unit{{$key+1}}" placeholder="Unit" class="form-control input-sm" value="{{$row['unit']}}" id="unit{{$key+1}}" name="unit[]" onblur="calculateChangeOrder()" />
                                        </td>
                                        <td class="col-id-no" {{$up}} id="td_rate{{$key+1}}">
                                            <input step=".00000000001" type="number" data-cy="particular_rate{{$key+1}}" placeholder="Rate" class="form-control input-sm" value="{{$row['rate']}}" id="rate{{$key+1}}" name="rate[]" onblur="calculateChangeOrder()" />
                                        </td>
                                        <td class="col-id-no" {{$up}} id="td_co_amount{{$key+1}}">
                                            <input type="text" readonly data-cy="particular_change_order_amount{{$key+1}}" class="form-control input-sm" value="{{$row['change_order_amount']}}" id="change_order_amount{{$key+1}}" name="change_order_amount[]" onblur="calculateChangeOrder()" />
                                        </td>
                                        <td class="col-id-no" colspan="3" {{$bd}} id="td_budget{{$key+1}}">
                                            <input step=".00000000001" type="number" data-cy="particular_budget{{$key+1}}" value="{{$row['budget_reallocation']}}" placeholder="Budget reallocation" class="form-control input-sm" value="" id="budget{{$key+1}}" name="budget[]" onblur="calculateChangeOrder()" />
                                        </td>
                                        <td class="col-id-no">
                                            <input type="text" maxlength="200" onkeypress="return limitMe(event, this)" data-cy="particular_order_description{{$key+1}}" class="form-control input-sm" value="" id="order_description{{$key+1}}" name="order_description[]" />
                                        </td>
                                        <td class="col-id-no">
                                            <div class="text-center">
                                                <select name="group[]" id="group_select{{$key+1}}">
                                                    @if(!empty($group_codes))
                                                    @foreach($group_codes as $value)
                                                    @if($row['group']==$value)
                                                    <option selected value="{{$value}}">{{$value}}</option>
                                                    @else
                                                    <option value="{{$value}}">{{$value}}</option>
                                                    @endif
                                                    @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </td>
                                        <td class="col-id-no">
                                            <div class="text-center">
                                                <div id="sub_group{{$key+1}}"></div>
                                            </div>
                                        </td>


                                        <input type="hidden" id="description-hidden{{$key+1}}" name="description[]" value="@isset($row['description']){{$row['description']}}@endisset">
                                        <input type="hidden" id="pint{{$key+1}}" name="pint[]" value="{{$key+1}}">
                                        <td class="td-c">
                                            <button data-cy="particular-remove{{$key+1}}" onclick="$(this).closest('tr').remove();addLastRowAddButton();calculateChangeOrder();" type="button" class="btn btn-xs red">×</button>
                                            <span id="addRowButton{{$key+1}}">
                                                @if($key == count($detail->json_particulars)-1)
                                                <a href="javascript:;" onclick="AddInvoiceParticularRowOrderV2();" class="btn btn-xs green">+</a>
                                                @endif
                                            </span>
                                            {{-- <a data-cy="particular-remove{{$key+1}}" href="javascript:;" onclick="$(this).closest('tr').remove();calculateRetainage();" class="btn btn-xs red"> <i class="fa fa-times"> </i> </a>--}}
                                        </td>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="headFootZIndex">
                                    <tr class="warning">
                                        <th class="col-id-no">Grand total</th>
                                        <th></th>
                                        <th class="td-c">
                                            <span id="original_contract_amount_total"></span>
                                        </th>
                                        <th></th>
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
                                        <input type="text" readonly maxlength="45" id="total_original_contract_amount" name="total_original_contract_amount" class="form-control" data-cy="total_original_contract_amount" value="{{$detail->total_original_contract_amount}}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-4">Total value of the change order <span class="required">
                                        </span></label>
                                    <div class="col-md-8">
                                        <input type="text" readonly maxlength="45" name="total_change_order_amount" class="form-control" data-cy="total_change_order_amount" id="total_change_order_amount" value="{{$detail->total_change_order_amount}}">
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
                                        <input type="hidden" value="{{$co_type}}" id="co_type">

                                        <input type="hidden" value="{{$link}}" name="link">
                                        <input type="hidden" value="{{$bulk_id}}" name="bulk_id">
                                        <input type="hidden" value="{{$type}}" name="type">
                                        <a href="/merchant/order/list" class="btn default">Cancel</a>
                                        <input type="submit" value="Update" class="btn blue" data-cy="order_save" />
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
    @if(isset($project_id))
    project_id = {!!$project_id!!};
    @endif
    @if(isset($group_codes))
    group_codes = {!!$group_codes_json!!};
    @endif
</script>
@section('footer')
<script>
    sub_group_codes = [];
    rows  = {!!$detail->particulars!!};
    @if(isset($detail))
     @foreach($detail->json_particulars as $key=>$row)
        key  = '{{$key+1}}';
        VirtualSelect.init({
            ele: '#group_select'+key,
            allowNewOption: true,
            dropboxWrapper: 'body',
            name: 'group[]',
            multiple: false,
            additionalClasses: 'vs-option',
            searchPlaceholderText: 'Search',
            search: true,
            options: group_codes
        });

        $('#group_select'+key).change(function() {
            var options = [
                { label: this.value, value: this.value }
            ];
            if (!group_codes.includes(this.value) && this.value !== '') {
                group_codes.push(this.value)
            for (let g = 0; g <= rows.length; g++) {
                let groupSelector = document.querySelector('#group_select' +g);

                if ('group_select' + key === 'group_select' + g)
                    groupSelector.setOptions(group_codes, this.value);
                else
                    groupSelector.setOptions(group_codes,options);
            }
        }
        });

        VirtualSelect.init({
            ele: '#sub_group'+key,
            allowNewOption: true,
            dropboxWrapper: 'body',
            name: 'sub_group[]',
            multiple: false,
            additionalClasses: 'vs-option',
            searchPlaceholderText: 'Search',
            search: true,
            options: sub_group_codes
        });

        $('#sub_group'+key).change(function() {
            var options = [
                { label: this.value, value: this.value }
            ];
            if (!sub_group_codes.includes(this.value) && this.value !== '') {
                sub_group_codes.push(this.value)
            for (let g = 0; g <= rows.length; g++) {
                let groupSelector = document.querySelector('#sub_group' +g);

                if ('sub_group' + key === 'sub_group' + g)
                    groupSelector.setOptions(sub_group_codes, this.value);
                else
                    groupSelector.setOptions(sub_group_codes,options);
            }
        }
        });

           
        VirtualSelect.init({
            ele: '#cost_type'+key,
            dropboxWrapper: 'body',
            name: 'cost_type[]',
            multiple: false,
            additionalClasses: 'vs-option',
            searchPlaceholderText: 'Search',
            search: true
        });

     @endforeach
    @endif
    project_id = '{{$detail2->project_id}}'
    projectSelected(project_id)
    $('.tableFixHead').css('max-height', screen.height/2);
    $('.productselect').select2({
        tags: true,
        insertTag: function(data, tag) {
            var $found = false;
            $.each(data, function(index, value) {
                if ($.trim(tag.text).toUpperCase() == $.trim(value.text).toUpperCase()) {
                    $found = true;
                }
            });
            if (!$found) data.unshift(tag);
        }
    }).on('select2:open', function(e) {
        pind = $(this).index();
        var index = $(".productselect").index(this);
        index += 1;
        if (document.getElementById('prolist' + pind)) {} else {
            $('.select2-results').append('<div class="wrapper" id="prolist' + pind + '" > <a class="clicker" onclick="billIndex(' + index + ',' + index + ',0);">Addi new bill code</a> </div>');
        }
    });
    calculateChangeOrder();
</script>
@endsection