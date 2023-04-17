@extends('app.master')
@section('content')

<style>
    .onhover-border:hover {
        border: 1px solid grey !important;
    }

    .table-hover>tbody>tr:hover {
        background-color: transparent !important;
    }


    .table thead tr th {
        font-size: 12px;
        padding: 3px;
        font-weight: 400;
        color: #333;
        background: #eee;
    }

    .table>tbody>tr>td {
        font-size: 12px !important;
        padding: 3px;
        border: 1px solid #D9DEDE;
        border-right: 0px;
        border-left: 0px;
    }

    .error-corner {
        border: 1px solid grey;
        background-image: linear-gradient(225deg, red, red 6px, transparent 6px, transparent);
    }

    ul {
        list-style-type: none !important;
    }

    li {
        list-style-type: none !important;
    }

    .select2-results__option {
        font-size: 12px !important;
    }

    .dropdown-menu li>a {
        font-size: 12px !important;
        line-height: 18px;
    }

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

    .steps {
        background-color: transparent !important;
        width: auto !important;
    }

    .vscomp-clear-icon::before,
    .vscomp-clear-icon::after {
        height: 8px;
        left: 5px;
        top: 2;
        width: 2px;
    }

    .vscomp-arrow::after {
        height: 6px;
        margin-top: -3px;
        width: 6px;
    }

    .vscomp-clear-button {
        right: 20px;
    }

    .vscomp-option {
        height: 30px;

    }


    .vscomp-option {
        height: 40px !important;
    }

    .vscomp-wrapper {
        font-size: 12px;
    }

    .vscomp-search-input {

        font-size: 12px;

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
        overflow: auto;
    }

    .headFootZIndex {
        z-index: 3;
    }

    .biggerHead {
        /*  text-align: left !important;*/
        padding-left: 15px !important;
        vertical-align: middle !important;
        font-weight: 500 !important;
    }

    .table-bordered {
        border: 1px solid #dddddd;
        border-collapse: separate;
        *border-collapse: collapsed;
        border-left: 0;
        -webkit-border-radius: 4px;
        -moz-border-radius: 4px;
        border-radius: 4px;
    }

    .sorted_table2 tr {
        cursor: pointer;
    }

    .ui-sortable-helper {
        width: 100% !important;
        background-color: #fff !important;
    }

    .sorted_table .handle {
        opacity: 0;
        cursor: move;
        position: relative;
        top: 5px;
    }

    .sorted_table .handle svg {
        color: #a0acac;
    }

    .sorted_table_tr:hover .handle {
        opacity: 1;
    }

    #update-fields-pos {
        opacity: 0;
        visibility: hidden;
    }

    .sorted_table_tr {
        left: 20px !important;
    }

    .amtbox {
        width: 100%;
        border: none;
        text-align: center;
        background-color: transparent;
    }
</style>

<script>
    @php
    $billcodeJson = json_encode($csi_codes);
    $billcodeJson = str_replace("\\", '\\\\', $billcodeJson);
    $billcodeJson = str_replace("'", "\'", $billcodeJson);
    $billcodeJson = str_replace('"', '\\"', $billcodeJson);

    $particularJson = json_encode($particulars);
    $particularJson = str_replace("\\", '\\\\', $particularJson);
    $particularJson = str_replace("'", "\'", $particularJson);
    $particularJson = str_replace('"', '\\"', $particularJson);

    $groupJson = json_encode($groups);
    $groupJson = str_replace("\\", '\\\\', $groupJson);
    $groupJson = str_replace("'", "\'", $groupJson);
    $groupJson = str_replace('"', '\\"', $groupJson);


    $onlyBillCodeJson = json_encode(array_column($csi_codes, 'value'));
    $onlyBillCodeJson = str_replace("\\", '\\\\', $onlyBillCodeJson);
    $onlyBillCodeJson = str_replace("'", "\'", $onlyBillCodeJson);
    $onlyBillCodeJson = str_replace('"', '\\"', $onlyBillCodeJson);

    //$onlyBillCodeJson=json_encode(array_column($csi_codes, 'value'));
    $ArrayBillCodeJson = str_replace("\\", '\\\\', $csi_codes_array);
    $ArrayBillCodeJson = str_replace("'", "\'", $ArrayBillCodeJson);
    $ArrayBillCodeJson = str_replace('"', '\\"', $ArrayBillCodeJson);

    $merchantCostTypeJson = json_encode($merchant_cost_types);
    $merchantCostTypeJson = str_replace("\\", '\\\\', $merchantCostTypeJson);
    $merchantCostTypeJson = str_replace("'", "\'", $merchantCostTypeJson);
    $merchantCostTypeJson = str_replace('"', '\\"', $merchantCostTypeJson);

    $merchantCostTypeJsonArray = str_replace("\\", '\\\\', $cost_types_array);
    $merchantCostTypeJsonArray = str_replace("'", "\'", $merchantCostTypeJsonArray);
    $merchantCostTypeJsonArray = str_replace('"', '\\"', $merchantCostTypeJsonArray);
    @endphp

    csi_codes = JSON.parse('{!! $billcodeJson !!}');
    csi_codes_array = JSON.parse('{!! $ArrayBillCodeJson !!}');
    var particularray = JSON.parse('{!! $particularJson !!}');
    //console.log(particularray);
    var previewArray = [];
    var bill_codes = JSON.parse('{!! $billcodeJson !!}');
    var groups = JSON.parse('{!! $groupJson !!}');
    var bill_code_details = [{
        'label': 'Yes',
        'value': 'Yes'
    }, {
        'label': 'No',
        'value': 'No'
    }];
    var billed_transactions_array = JSON.parse('{!! json_encode($billed_transactions) !!}');
    var particular_column_array = JSON.parse('{!! json_encode($particular_column) !!}');
    var draft_particulars = JSON.parse('{!! $draft_particulars !!}');
    var billed_transactions_filter = [];
    var only_bill_codes = JSON.parse('{!! $onlyBillCodeJson !!}');
    var cost_codes = JSON.parse('{!! json_encode($cost_codes) !!}');
    var cost_types = JSON.parse('{!! json_encode($cost_types) !!}');
    var merchant_cost_types = JSON.parse('{!! $merchantCostTypeJson !!}');
    var cost_types_array = JSON.parse('{!! $merchantCostTypeJsonArray !!}');
    let hangoutButton = document.getElementById("update-fields-pos");
    var particular_type = '{{$type}}';

    function updateBillCodeDropdowns(optionArray, newBillCode) {
        let selectedId = $('#selectedBillCodeId').val();

        for (let v = 0; v < particularray.length; v++) {
            let currentField = particularray[v];
            let billCodeSelector = document.querySelector('#bill_code' + v);

            if (selectedId === 'bill_code' + v) {

                billCodeSelector.setOptions(optionArray);
                billCodeSelector.setValue(newBillCode.id);

                only_bill_codes.push(newBillCode.id)

                particularray[v].bill_code = newBillCode.code;
                particularray[v].description = newBillCode.description;
                $('#description' + v).val(newBillCode.description)

            } else billCodeSelector.setOptions(optionArray, particularray[v].bill_code);

        }
        closeSidePanelBillCode();

        $('#new_bill_code').val(null);
        $('#new_bill_description').val(null);
        $('#selectedBillCodeId').val(null);
    }

    /*$('#cell_bill_code_' + p).addClass(' error-corner');
    this.goAhead = false;
    }else $('#cell_bill_code_' + p).removeClass(' error-corner');

    if(particularray[p].bill_type === null || particularray[p].bill_type === '') {
        $('#cell_bill_type_' + p).addClass(' error-corner');
        this.goAhead = false;
    }else $('#cell_bill_type_' + p).removeClass(' error-corner');
    if(particularray[p].original_contract_amount === null || particularray[p].original_contract_amount === '') {
        $('#cell_original_contract_amount_' + p).addClass(' error-corner');*/

    function addPopover(id, message) {
        $('#' + id).attr({
            'data-placement': 'right',
            'data-container': "body",
            'data-trigger': 'hover',
            'data-content': message,
            // 'data-original-title'
        }).popover();
    }
</script>
<div>


    <div class="page-content">
        <div>
            <!-- BEGIN PAGE HEADER-->
            <div class="page-bar">
                <span class="page-title" style="float: left;">{{$title}}</span>
                {{ Breadcrumbs::render('create.invoice','invoice') }}
                <span class=" pull-right badge badge-pill status steps" style="padding: 6px 16px 6px 16px !important;">Step 2 of 3</span>
            </div>
            <!-- END PAGE HEADER-->
            <!-- BEGIN PAGE CONTENT-->
            <div class="row">
                <form action="/merchant/invoice/particularsave" id="frm_invoice" onsubmit="loader();" method="post" enctype="multipart/form-data" class="form-horizontal form-row-sepe">
                    <input type="hidden" id="request_id" name="link" value="{{$link}}"></th>
                    <input type="hidden" name="order_ids" value="{{$order_id_array}}">

                    <div class="col-md-12">

                        <div id="perror" style="display: none;" class="alert alert-block alert-danger fade in">
                            <p>Error! Select a project before trying to add a new row
                            </p>
                        </div>
                        <div id="paticulars_error" style="display: none;" class="alert alert-block alert-danger fade in">
                            <p>Error! Before proceeding, please verify the details.. <br> 'Bill code', 'Bill Type', 'Original Contract Amount' are mandatory fields !
                            </p>
                        </div>
                        @csrf
                        <div>

                            <div class="portlet light bordered">
                                <div class="portlet-body form">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h3 class="form-section">Add Particulars</h3>
                                        </div>
                                        <div class="col-md-6">
                                            <a data-cy="add_particulars_btn" href="javascript:;" onclick="addnewRow();" class="btn green pull-right mb-1"> Add new row </a>
                                        </div>
                                    </div>
                                    <div class="table-scrollable  tableFixHead" id="table-scroll" style="max-height: 540px;">
                                        <div class="loading" id="loader2">Loading&#8230;</div>

                                        <table class="table table-bordered sorted_table" id="particular_table">
                                            <thead class="headFootZIndex">
                                                @if(!empty($particular_column))
                                                <thead class="headFootZIndex">
                                                    <tr>
                                                        @foreach($particular_column as $k=>$v)
                                                        @if($k!='description')
                                                        <th @if($k=='bill_code' ) class="td-c col-id-no biggerHead" style="min-width:200px ;" @elseif($k=='bill_type' ) class="td-c biggerHead" style="min-width:120px ;" @else class="td-c biggerHead" style="min-width:100px ;" @endif @if($k=='description' || $k=='bill_code' ) style="min-width: 100px;" @endif>
                                                            {!! (strlen($v) > 10) ? str_replace( ' ', '<br>', $v) : $v !!}
                                                        </th>
                                                        @endif
                                                        @endforeach
                                                        <th class="td-c" style="min-width: 60px;">
                                                            ?
                                                        </th>
                                                    </tr>
                                                </thead>
                                                @endif
                                            </thead>

                                            @php
                                            $readonly_array=array('original_contract_amount','stored_materials','retainage_amount','approved_change_order_amount','current_contract_amount','previously_billed_percent','previously_billed_amount',/*'current_billed_amount',*/'total_billed','retainage_amount_previously_withheld','retainage_amount_previously_stored_materials',/*'retainage_amount_for_this_draw',*/'net_billed_amount','total_outstanding_retainage',/*'retainage_amount_stored_materials'*/);
                                            @endphp
                                            <tbody id="particular_body">
                                                @foreach($particulars as $pk=>$pv)
                                                @php $pint=$pv['pint']; @endphp
                                                <tr id="{{$pint}}" class="sorted_table_tr">
                                                    @foreach($particular_column as $k=>$v)
                                                    @php $readonly=false; @endphp
                                                    @if(in_array($k, $readonly_array))
                                                    @php $readonly=true; @endphp
                                                    @endif
                                                    @if($k!='description')

                                                    @if($k=='bill_code')
                                                    <td style="vertical-align: middle;" id="cell_{{$k}}_{{$pint}}" onclick="virtualSelectInit({{$pint}}, '{{$k}}',{{$pk}})" class="td-c onhover-border  col-id-no">
                                                        <div style="display:flex;">
                                                            <span style="margin-right: 3px;" class="handle">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="svg-icon" style="width: 1em; height: 1em;vertical-align: middle;fill: currentColor;overflow: hidden;" viewBox="0 0 1024 1024" version="1.1">
                                                                    <path d="M384 64H256C220.66 64 192 92.66 192 128v128c0 35.34 28.66 64 64 64h128c35.34 0 64-28.66 64-64V128c0-35.34-28.66-64-64-64z m0 320H256c-35.34 0-64 28.66-64 64v128c0 35.34 28.66 64 64 64h128c35.34 0 64-28.66 64-64v-128c0-35.34-28.66-64-64-64z m0 320H256c-35.34 0-64 28.66-64 64v128c0 35.34 28.66 64 64 64h128c35.34 0 64-28.66 64-64v-128c0-35.34-28.66-64-64-64zM768 64h-128c-35.34 0-64 28.66-64 64v128c0 35.34 28.66 64 64 64h128c35.34 0 64-28.66 64-64V128c0-35.34-28.66-64-64-64z m0 320h-128c-35.34 0-64 28.66-64 64v128c0 35.34 28.66 64 64 64h128c35.34 0 64-28.66 64-64v-128c0-35.34-28.66-64-64-64z m0 320h-128c-35.34 0-64 28.66-64 64v128c0 35.34 28.66 64 64 64h128c35.34 0 64-28.66 64-64v-128c0-35.34-28.66-64-64-64z" fill="" />
                                                                </svg>
                                                            </span>
                                                            <input type="hidden" id="{{$k}}{{$pint}}" value="{{$pv['bill_code']}}" name="{{$k}}[]">
                                                            <span id="span_{{$k}}{{$pint}}" style="width:80%">{{$csi_codes_list[$pv['bill_code']]['label']}}</span>
                                                            <span id="vspan_{{$k}}{{$pint}}" style="width:80%; display: none;">
                                                                <div id="v_{{$k}}{{$pint}}"></div>
                                                            </span>
                                                            <a onclick="showupdatebillcodeattachment({{$pint}});" id="attacha-{{$pint}}" style="align-self: center; margin-left: 3px;" class="pull-right popovers">
                                                                <i id="icon-{{$pint}}" class="fa fa-paperclip" data-placement="top" data-container="body" data-trigger="hover" data-content="0 file " aria-hidden="true" data-original-title="" title="0 file"></i>
                                                            </a>
                                                            <input type="hidden" name="attachments[]" value="{{$pv['attachments']}}" id="attach-{{$pint}}" />
                                                            <input type="hidden" name="calculated_perc[]" value="{{$pv['calculated_perc']}}" id="calculated_perc{{$pint}}">
                                                            <input type="hidden" name="calculated_row[]" value="{{$pv['calculated_row']}}" id="calculated_row{{$pint}}">
                                                            <input type="hidden" name="description[]" value="{{$pv['description']}}" id="description{{$pint}}">
                                                            <input type="hidden" name="billed_transaction_ids[]" value="@isset($pv['billed_transaction_ids']){{$pv['billed_transaction_ids']}}@endif" id="billed_transaction_ids{{$pint}}">
                                                            <input id="id{{$pint}}" value="@isset($pv['id']){{$pv['id']}}@endif" type="hidden" name="id[]">
                                                            <input id="dpid{{$pint}}" value="@isset($pv['dpid']){{$pv['dpid']}}@endisset" type="hidden" name="dpid[]">
                                                            <input id="pint{{$pint}}" value="{{$pint}}" type="hidden" name="pint[]">
                                                            <input id="sort_order{{$pint}}" value="{{$pv['sort_order']}}" type="hidden" name="sort_order[]">
                                                            <input type="hidden" name="sub_group[]" value="{{$pv['sub_group']}}">
                                                            <input type="hidden" id="retainage_amount_change{{$pint}}" >
                                                        </div>
                                                    </td>
                                                    @elseif($k=='bill_type')
                                                    <td style="vertical-align: middle; min-width: 124px;" class="td-c onhover-border" id="cell_bill_type_{{$pint}}">
                                                        <span style="width: 100%;" onclick="setBilltype('{{$pv['bill_type']}}',{{$pint}})">
                                                        <span id="span_bill_type{{$pint}}">{{$pv['bill_type']}}</span>
                                                        <input type="hidden" id="bill_type{{$pint}}" name="bill_type[]" value="{{$pv['bill_type']}}"></span>
                                                    </td>
                                                    @elseif($k=='cost_type' || $k=='group' || $k=='bill_code_detail')
                                                    <td style="vertical-align: middle; " id="cell_{{$k}}_{{$pint}}" onclick="virtualSelectInit({{$pint}}, '{{$k}}',{{$pk}})" class="td-c onhover-border ">
                                                        <input type="hidden" id="{{$k}}{{$pint}}" name="{{$k}}[]" value="@isset($pv[$k]){{$pv[$k]}}@endif">
                                                        <span id="span_{{$k}}{{$pint}}">
                                                            @if($k=='cost_type')
                                                            {{$cost_types[$pv['cost_type']]['label']}}
                                                            @else
                                                            @isset($pv[$k]){{$pv[$k]}}@endif
                                                            @endif
                                                        </span>
                                                        <span id="vspan_{{$k}}{{$pint}}" style="width: 100%; display: none;">
                                                            <div id="v_{{$k}}{{$pint}}"></div>
                                                        </span>
                                                    </td>
                                                    @else
                                                    <td style="vertical-align: middle; @if($readonly==true) background-color:#f5f5f5; @endif" class="td-c onhover-border ">
                                                    @if($readonly==true)
                                                    <input type="hidden" id="{{$k}}{{$pint}}" value="@isset($pv[$k]){{$pv[$k]}}@endif" name="{{$k}}[]">
                                                    @isset($pv[$k]){{$pv[$k]}}@endif
                                                    @else
                                                    <span style="width: 100%;display: block;" id="span_{{$k}}{{$pint}}">
                                                    <span style="width: 100%;display: block;" onclick="setInput({{$pint}},'{{$k}}')">@isset($pv[$k]){{$pv[$k]}}@endif&nbsp;
                                                    <input type="hidden" id="{{$k}}{{$pint}}" name="{{$k}}[]" value="@isset($pv[$k]){{$pv[$k]}}@endif">
                                                    </span>
                                                    </span>
                                                    @if($k=='original_contract_amount')
                                                    <span id="add-calc-span{{$pint}}">
                                                    </span>
                                                    @endif
                                                    @if($k=='current_billed_amount')
                                                    <span id="add-cost-span{{$pint}}">
                                                    </span>
                                                    @endif
                                                   @endif
                                                        
                                                    </td>
                                                    @endif

                                                    @endif

                                                    @endforeach
                                                    <td class="td-c " style="vertical-align: middle;width: 60px;">
                                                        <button type="button" onclick="saveParticularRow({{$pint}},0);$(this).closest('tr').remove();calculateTotal();" class="btn btn-xs red">×</button>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot class="headFootZIndex">
                                                <tr class="warning">
                                                    <th class="col-id-no">Grand total</th>
                                                    <th></th>
                                                    <th></th>
                                                    <th>
                                                        <span id="total_oca">@isset($summary['sum_original_contract_amount']){{$summary['sum_original_contract_amount']}}@endisset</span>
                                                    </th>


                                                    <th>
                                                        <span id="total_acoa">@isset($summary['sum_approved_change_order_amount']){{$summary['sum_approved_change_order_amount']}}@endisset</span>
                                                    </th>
                                                    <th>
                                                        <span id="total_cca">@isset($summary['sum_current_contract_amount']){{$summary['sum_current_contract_amount']}}@endisset</span>
                                                    </th>
                                                    <th>

                                                    </th>
                                                    <th>
                                                        <span id="total_pba">@isset($summary['sum_previously_billed_amount']){{$summary['sum_previously_billed_amount']}}@endisset</span>
                                                    </th>
                                                    <th></th>
                                                    <th>
                                                        <span id="total_cba">@isset($summary['sum_current_billed_amount']){{$summary['sum_current_billed_amount']}}@endisset</span>
                                                    </th>

                                                    <th>
                                                        <span id="total_psm">@isset($summary['sum_previously_stored_materials']){{$summary['sum_previously_stored_materials']}}@endisset</span>
                                                    </th>
                                                    <th>
                                                        <span id="total_csm">@isset($summary['sum_current_stored_materials']){{$summary['sum_current_stored_materials']}}@endisset</span>
                                                    </th>
                                                    <th>
                                                        <span id="total_sm">@isset($summary['sum_stored_materials']){{$summary['sum_stored_materials']}}@endisset</span>
                                                    </th>
                                                    <th>
                                                        <span id="total_tb">@isset($summary['sum_total_billed']){{$summary['sum_total_billed']}}@endisset</span>
                                                    </th>
                                                    <th class="td-c"></th>
                                                    <th>
                                                        <span id="total_rapw">@isset($summary['sum_retainage_amount_previously_withheld']){{$summary['sum_retainage_amount_previously_withheld']}}@endisset</span>
                                                    </th>
                                                    <th>
                                                        <span id="total_rad">@isset($summary['sum_retainage_amount_for_this_draw']){{$summary['sum_retainage_amount_for_this_draw']}}@endisset</span>
                                                    </th>
                                                    <th class="td-c"></th>
                                                    <th>
                                                        <span id="total_rapsm">@isset($summary['sum_retainage_amount_previously_stored_materials']){{$summary['sum_retainage_amount_previously_stored_materials']}}@endisset</span>
                                                    </th>
                                                    <th>
                                                        <span id="total_rasm">@isset($summary['sum_retainage_amount_stored_materials']){{$summary['sum_retainage_amount_stored_materials']}}@endisset</span>
                                                    </th>
                                                    <th class="td-c"><span id="particulartotaldiv">@isset($summary['sum_net_billed_amount']){{$summary['sum_net_billed_amount']}}@endisset</span>
                                                        <input type="hidden" id="particulartotal" data-cy="particular-total1" name="totalcost" value="@isset($summary['sum_net_billed_amount']){{$summary['sum_net_billed_amount']}}@endisset" class="form-control " readonly="">
                                                    </th>
                                                    <th>
                                                        <span id="total_rra">@isset($summary['sum_retainage_release_amount']){{$summary['sum_retainage_release_amount']}}@endisset</span>
                                                    </th>
                                                    <th>
                                                        <span id="total_rrasm">@isset($summary['sum_retainage_stored_materials_release_amount']){{$summary['sum_retainage_stored_materials_release_amount']}}@endisset</span>
                                                    </th>
                                                    <th>
                                                        <span id="total_tor">@isset($summary['sum_total_outstanding_retainage']){{$summary['sum_total_outstanding_retainage']}}@endisset</span>
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
                                </div>
                            </div>
                            <script>

                            </script>
                        </div>

                        <div class="portlet light bordered">
                            <div class="portlet-body form">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="pull-right">

                                            <a href="/merchant/contract/list" class="btn green">Cancel</a>
                                            <a class="btn green" href="/merchant/invoice/create/{{$link}}">Back</a>
                                            <input type="submit" class="btn blue" value="{{$mode}} invoice">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                </form>

            </div>
        </div>
    </div>



</div>
<script>
    mode = '{{$mode}}';
</script>
</div>

<script src="https://releases.transloadit.com/uppy/v1.28.1/uppy.min.js"></script>

<script>
    var newdocfileslist = [];
    //uppy file upload code
    var uppy = Uppy.Core({
        autoProceed: true,
        restrictions: {
            maxFileSize: 3000000,
            maxNumberOfFiles: 10,
            minNumberOfFiles: 1,
            allowedFileTypes: ['.jpg', '.png', '.jpeg', '.pdf']
        }
    });

    uppy.use(Uppy.Dashboard, {
        target: 'body',
        trigger: '.UppyModalOpenerBtn',
        inline: false,
        height: 40,
        maxHeight: 200,

        hideAfterFinish: true,
        showProgressDetails: false,
        hideUploadButton: false,
        hideRetryButton: false,
        hidePauseResumeButton: false,
        hideCancelButton: false,
        // doneButtonHandler: () => {
        //     document.getElementById("file_upload").value = '';
        //     this.uppy.reset()
        //     this.requestCloseModal()
        // },
        // locale: {
        //     strings: {
        //         done: 'Cancel'
        // }}
    });

    uppy.use(Uppy.XHRUpload, {
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        },
        endpoint: '/merchant/uppyfileupload/uploadImage/invoice',
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
        if (response.body.fileUploadPath != undefined) {
            path = response.body.fileUploadPath;
            extvalue = document.getElementById("file_upload").value;
            newdocfileslist.push(path);
            deletedocfile('');
            if (extvalue != '') {
                document.getElementById("file_upload").value = extvalue + ',' + path;
            } else {
                document.getElementById("file_upload").value = path;
            }
            if (response.body.status == 300) {
                document.getElementById("error").innerHTML = response.body.errors;
                uppy.removeFile(file.id);
            } else {
                document.getElementById("error").innerHTML = '';
            }
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
<div class="modal fade" id="delete_doc" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 id="poptitle" class="modal-title">Delete attachment</h4>
                <input type="hidden" id="docfullurl">
            </div>
            <div class="modal-body">
                Do you want to permanently delete this attachment from this invoice?
            </div>
            <div class="modal-footer">
                <button type="button" id="closeconformdoc" class="btn default" data-dismiss="modal">Cancel </button>
                <button type="button" onclick="deletedocfile('delete')" id="deleteanchor" class="btn delete">Delete</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="draft" tabindex="-1" role="basic" >
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 id="poptitle" class="modal-title">Resume previous session</h4>
            </div>
            <div class="modal-body">
            Unsaved changes were found in this invoice from the session on . Would you like to continue with the previous changes or discard them?
            </div>
            <div class="modal-footer">
                <button type="button" onclick="loadDraft()"  class="btn blue">Resume session</button>

                <button type="button"  class="btn default" data-dismiss="modal">Discard changes</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>


@include('app.merchant.contract.add-group-modal')
@include('app.merchant.contract.add-calculation-modal2')
@include('app.merchant.contract.add-cost-modal')
@include('app.merchant.invoice.add-attachment-billcode-modal')


<div class="modal fade" id="attach-delete" tabindex="-1" role="attach-delete" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Delete attachment</h4>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this attachment?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn default" data-dismiss="modal">Close</button>
                <input type="hidden" id="removepath">
                <a id="attach-delete-click" onclick="deleteattchment()" data-dismiss="modal" class="btn delete">Confirm</a>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<script>
    $(window).load(function() {
        _('loader2').style.display = 'none';
        @if($draft_particulars!='')
        $("#draft").modal('show');
        @endif
    })
</script>
@endsection

<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

@section('readyscript')


$(".sorted_table tbody").sortable({
handle: '.handle',



});




@endsection