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
</style>


<div>


    <div class="page-content">
        <div x-data="handler_create()" x-init="initializeParticulars">
            <!-- BEGIN PAGE HEADER-->
            <div class="page-bar">
                <span class="page-title" style="float: left;">{{$title}}</span>
                {{ Breadcrumbs::render('create.invoice','invoice') }}
                <span class=" pull-right badge badge-pill status steps" style="padding: 6px 16px 6px 16px !important;">Step 2 of 3</span>
            </div>
            <!-- END PAGE HEADER-->
            <!-- BEGIN PAGE CONTENT-->
            <div class="row">
                <div class="col-md-12">

                    <div id="perror" style="display: none;" class="alert alert-block alert-danger fade in">
                        <p>Error! Select a project before trying to add a new row
                        </p>
                    </div>
                    <div id="paticulars_error" style="display: none;" class="alert alert-block alert-danger fade in">
                        <p>Error! Before proceeding, please verify the details.. <br> 'Bill code', 'Bill Type', 'Original Contract Amount' are mandatory fields !
                        </p>
                    </div>
                    <form action="/merchant/invoice/particularsave" id="frm_invoice" onsubmit="loader();" method="post" enctype="multipart/form-data" class="form-horizontal form-row-sepe">
                        @csrf

                        <div>

                            <div class="portlet light bordered">
                                <div class="portlet-body form">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h3 class="form-section">Add Particulars</h3>
                                        </div>
                                        <div class="col-md-6">
                                            <a data-cy="add_particulars_btn" href="javascript:;" @click="await addNewField();" class="btn green pull-right mb-1"> Add new row </a>
                                        </div>
                                    </div>
                                    <div class="table-scrollable  tableFixHead" id="table-scroll" style="max-height: 540px;">
                                        <table class="table table-bordered table-hover" id="particular_table" wire:ignore="">
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
                                            $readonly_array=array('original_contract_amount','stored_materials','bill_type','retainage_amount','retainage_amount','approved_change_order_amount','current_contract_amount','previously_billed_percent','previously_billed_amount',/*'current_billed_amount',*/'total_billed','retainage_amount_previously_withheld','retainage_amount_previously_stored_materials',/*'retainage_amount_for_this_draw',*/'net_billed_amount','total_outstanding_retainage',/*'retainage_amount_stored_materials'*/);
                                            @endphp
                                            <tbody>
                                                @foreach($particulars as $pk=>$pv)
                                                @php $readonly=false; @endphp
                                                <tr id="{{$pk}}" class="sorted_table_tr">
                                                    @foreach($particular_column as $k=>$v)
                                                    @if(in_array($k, $readonly_array))
                                                    @php $readonly=true; @endphp
                                                    @endif
                                                    @if($k!='description')

                                                    @if($k=='bill_code')
                                                    <td style="vertical-align: middle;" id="cell_{{$k}}_{{$pv['pint']}}" onclick="virtualSelectInit({{$pv['pint']}}, {{$k}},{{$pk}})" class="td-c onhover-border  col-id-no">
                                                        <div style="display:flex;">
                                                            <span class="handle">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="svg-icon" style="width: 1em; height: 1em;vertical-align: middle;fill: currentColor;overflow: hidden;" viewBox="0 0 1024 1024" version="1.1">
                                                                    <path d="M384 64H256C220.66 64 192 92.66 192 128v128c0 35.34 28.66 64 64 64h128c35.34 0 64-28.66 64-64V128c0-35.34-28.66-64-64-64z m0 320H256c-35.34 0-64 28.66-64 64v128c0 35.34 28.66 64 64 64h128c35.34 0 64-28.66 64-64v-128c0-35.34-28.66-64-64-64z m0 320H256c-35.34 0-64 28.66-64 64v128c0 35.34 28.66 64 64 64h128c35.34 0 64-28.66 64-64v-128c0-35.34-28.66-64-64-64zM768 64h-128c-35.34 0-64 28.66-64 64v128c0 35.34 28.66 64 64 64h128c35.34 0 64-28.66 64-64V128c0-35.34-28.66-64-64-64z m0 320h-128c-35.34 0-64 28.66-64 64v128c0 35.34 28.66 64 64 64h128c35.34 0 64-28.66 64-64v-128c0-35.34-28.66-64-64-64z m0 320h-128c-35.34 0-64 28.66-64 64v128c0 35.34 28.66 64 64 64h128c35.34 0 64-28.66 64-64v-128c0-35.34-28.66-64-64-64z" fill="" />
                                                                </svg>
                                                            </span>
                                                            <input type="hidden" id="{{$k}}{{$pv['pint']}}" value="{{$pv['bill_code']}}" name="{{$k}}[]">
                                                            <span style="width:80%">{{$csi_codes_list[$pv['bill_code']]['label']}}</span>
                                                            <span>
                                                                <div id="{{$k}}{{$pv['pint']}}"></div>
                                                            </span>
                                                            <a @click="showupdatebillcodeattachment({{$pv['pint']}});" id="attacha-{{$pv['pint']}}" style="align-self: center; margin-left: 3px;" class="pull-right popovers">
                                                                <i id="icon-{{$pv['pint']}}" class="fa fa-paperclip" data-placement="top" data-container="body" data-trigger="hover" data-content="0 file " aria-hidden="true" data-original-title="" title="0 file"></i>
                                                            </a>
                                                            <input type="hidden" name="attachments[]" value={{$pv['attachments']}}" id="attach-{{$pv['pint']}}" />
                                                            <input type="hidden" name="calculated_perc[]" value={{$pv['calculated_perc']}}" id="calculated_perc{{$pv['pint']}}">
                                                            <input type="hidden" name="calculated_row[]" value={{$pv['calculated_row']}}" id="calculated_row{{$pv['pint']}}">
                                                            <input type="hidden" name="description[]" value={{$pv['description']}}" id="description{{$pv['pint']}}">
                                                            <input type="hidden" name="billed_transaction_ids[]" value={{$pv['billed_transaction_ids']}}" id="billed_transaction_ids{{$pv['pint']}}">
                                                        </div>
                                                    </td>
                                                    @elseif($k=='bill_type')
                                                    <td style="vertical-align: middle; min-width: 124px;" class="td-c onhover-border " id="cell_bill_type_{{$pv['pint']}}">
                                                        <select required="" style="width: 100%; min-width: 150px;font-size: 12px;" id="bill_type{{$pv['pint']}}" name="bill_type[]" data-placeholder="Select.." class="form-control billTypeSelect input-sm" onchange="changeBillType({{$pv['pint']}}, {{$pk}})">
                                                            <option value="">Select..</option>
                                                            <option @if($pv['bill_type']=='% Complete' ) selected @endif value="% Complete">% Complete</option>
                                                            <option @if($pv['bill_type']=='Unit' ) selected @endif value="Unit">Unit</option>
                                                            <option @if($pv['bill_type']=='Calculated' ) selected @endif value="Calculated">Calculated</option>
                                                            <option @if($pv['bill_type']=='Cost' ) selected @endif value="Cost">Cost</option>
                                                        </select>
                                                    </td>
                                                    @elseif($k=='cost_type' || $k=='group' || $k=='bill_code_detail')
                                                    <td style="vertical-align: middle; " id="cell_{{$k}}_{{$pv['pint']}}" onclick="virtualSelectInit({{$pv['pint']}}, '{{$k}}',{{$pk}})" class="td-c onhover-border ">
                                                        <input type="hidden" name="{{$k}}[]" value="{{$pv[$k]}}">
                                                        <span x-show="! field.txt{{$k}}" x-text="setdropdowndiv('{{$k}}',field)">{{$pv[$k]}}</span>
                                                        <span style="width: 100%; display: none;" x-show="field.txt{{$k}}">
                                                            <div id="{{$k}}{{$pv['pint']}}"></div>
                                                        </span>
                                                    </td>
                                                    @else
                                                    <td style="vertical-align: middle; " :id="`cell_current_billed_percent_${field.pint}`" x-on:click="field.txtcurrent_billed_percent = true;particularray[`${index}`].txtcurrent_billed_percent = true; " class="td-c onhover-border " id="cell_current_billed_percent_0">
                                                        <span x-show="! field.txtcurrent_billed_percent" x-text="field.current_billed_percent">{{$pv[$k]}}</span>
                                                        <template x-if="field.bill_type!='Cost'">
                                                            <span x-show="field.txtcurrent_billed_percent">
                                                                <input :id="`current_billed_percent${field.pint}`" type="text" x-on:blur="field.txtcurrent_billed_percent = false;calculateCurrentBillAmount(field);calc(field);" x-model.lazy="field.current_billed_percent" value="" name="current_billed_percent[]" style="width: 100%;" class="form-control input-sm ">
                                                            </span>
                                                        </template>
                                                        <span x-show="field.txtcurrent_billed_percent" style="display: none;">
                                                            <input :id="`current_billed_percent${field.pint}`" type="text" x-on:blur="field.txtcurrent_billed_percent = false;calculateCurrentBillAmount(field);calc(field);" x-model.lazy="field.current_billed_percent" value="" name="current_billed_percent[]" style="width: 100%;" class="form-control input-sm " id="current_billed_percent0">
                                                        </span>
                                                        @if($pv['bill_type']=='Cost')
                                                        <span x-show="field.txtcurrent_billed_percent">
                                                            <input :id="`current_billed_percent${field.pint}`" type="hidden" x-on:blur="field.txtcurrent_billed_percent = false;calc(field);" x-model.lazy="field.current_billed_percent" value="" name="current_billed_percent[]" style="width: 100%;" class="form-control input-sm ">
                                                        </span>
                                                        @endif
                                                    </td>
                                                    @endif

                                                    @endif

                                                    @endforeach
                                                </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot class="headFootZIndex">
                                                <tr class="warning">
                                                    <th class="col-id-no">Grand total</th>
                                                    <th></th>
                                                    <th></th>
                                                    <th>
                                                        <span id="total_oca">10,000</span>
                                                    </th>
                                                    <th>
                                                        <span id="total_acoa"></span>
                                                    </th>
                                                    <th>
                                                        <span id="total_cca">10,000</span>
                                                    </th>
                                                    <th>

                                                    </th>
                                                    <th>
                                                        <span id="total_pba"></span>
                                                    </th>
                                                    <th></th>
                                                    <th>
                                                        <span id="total_cba"></span>
                                                    </th>

                                                    <th>
                                                        <span id="total_psm"></span>
                                                    </th>
                                                    <th>
                                                        <span id="total_csm"></span>
                                                    </th>
                                                    <th>
                                                        <span id="total_sm"></span>
                                                    </th>
                                                    <th>
                                                        <span id="total_tb"></span>
                                                    </th>
                                                    <th class="td-c"></th>
                                                    <th>
                                                        <span id="total_rapw"></span>
                                                    </th>
                                                    <th>
                                                        <span id="total_rad"></span>
                                                    </th>
                                                    <th class="td-c"></th>
                                                    <th>
                                                        <span id="total_rapsm"></span>
                                                    </th>
                                                    <th>
                                                        <span id="total_rasm"></span>
                                                    </th>
                                                    <th class="td-c"><span id="particulartotaldiv"></span>
                                                        <input type="hidden" id="particulartotal" data-cy="particular-total1" name="totalcost" value="" class="form-control " readonly="">
                                                    </th>
                                                    <th>
                                                        <span id="total_rra"></span>
                                                    </th>
                                                    <th>
                                                        <span id="total_rrasm"></span>
                                                    </th>
                                                    <th>
                                                        <span id="total_tor">0</span>
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
                            @include('app.merchant.contract.add-group-modal')
                            @include('app.merchant.contract.add-calculation-modal2')
                            @include('app.merchant.contract.add-cost-modal')
                            <script>

                            </script>
                        </div>

                        <div class="portlet light bordered">
                            <div class="portlet-body form">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="pull-right">
                                            <input type="hidden" id="request_id" name="link" value="{{$link}}"></th>
                                            <input type="hidden" name="order_ids" value="{{$order_id_array}}">

                                            <a href="/merchant/contract/list" class="btn green">Cancel</a>
                                            <a class="btn green" href="/merchant/invoice/create/{{$link}}">Back</a>
                                            <a @click="return setParticulars();" class="btn blue">{{$mode}} invoice</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
                @include('app.merchant.contract.add-bill-code-modal-contract')

            </div>
        </div>
    </div>

    <script>
        /* $(document).ready(function () {
                 particularsDropdowns(0, this.fields);

             });*/
        csi_codes = JSON.parse('{!! json_encode($csi_codes) !!}');
        var particularray = JSON.parse('{!! json_encode($particulars) !!}');
        var previewArray = [];
        var bill_codes = JSON.parse('{!! json_encode($csi_codes) !!}');
        var groups = JSON.parse('{!! json_encode($groups) !!}');
        var bill_code_details = [{
            'label': 'Yes',
            'value': 'Yes'
        }, {
            'label': 'No',
            'value': 'No'
        }];
        var billed_transactions_array = JSON.parse('{!! json_encode($billed_transactions) !!}');
        var billed_transactions_filter = [];
        var only_bill_codes = JSON.parse('{!! json_encode(array_column($csi_codes, '
            value ')) !!}');
        var cost_codes = JSON.parse('{!! json_encode($cost_codes) !!}');
        var cost_types = JSON.parse('{!! json_encode($cost_types) !!}');
        var merchant_cost_types = JSON.parse('{!! json_encode($merchant_cost_types) !!}');

        function initializeParticulars() {
            this.initializeDropdowns();
            this.calculateTotal();
            $('.tableFixHead').css('max-height', screen.height / 2);
        }

        function initSelect2() {

            if (particularray.length > 1) {
                for (let p = 0; p < particularray.length; p++)
                    this.particularsDropdowns(p);
            } else {
                this.particularsDropdowns(0);
            }
        }

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
<script>
    function setdata(name, fullurl) {

        document.getElementById('poptitle').innerHTML = "Delete attachment - " + name;
        document.getElementById('docfullurl').value = fullurl;
    }

    function deletedocfile(x) {
        var html = '';
        if (x == 'delete') {
            var fullurl = document.getElementById('docfullurl').value;
            var index = newdocfileslist.indexOf(fullurl);
            if (index !== -1) {
                newdocfileslist.splice(index, 1);
            }
        }

        for (var i = 0; i < newdocfileslist.length; i++) {
            var filenm = newdocfileslist[i].substring(newdocfileslist[i].lastIndexOf('/') + 1);
            filenm = filenm.split('.').slice(0, -1).join('.')
            filenm = filenm.substring(0, filenm.length - 4);
            html = html + '<span class=" btn btn-xs green" style="margin-bottom: 5px;margin-left: 0px !important;margin-right: 5px !important">' +
                '<a class=" btn btn-xs " target="_BLANK" href="' + newdocfileslist[i] + '" title="Click to view full size">' + filenm.substring(0, 10) + '..</a>' +
                '<a href="#delete_doc" onclick="setdata(\'' + filenm.substring(0, 10) + '\',\'' + newdocfileslist[i] + '\');"   data-toggle="modal"> ' +
                ' <i class=" popovers fa fa-close" style="color: #A0ACAC;" data-placement="top" data-container="body" data-trigger="hover"  data-content="Remove doc"></i>   </a> </span>';


        }
        clearnewuploads('no');
        document.getElementById('docviewbox').innerHTML = html;
        document.getElementById('closeconformdoc').click();
    }

    function clearnewuploads(x) {
        document.getElementById("file_upload").value = '';

        var filesnm = '';

        for (var i = 0; i < newdocfileslist.length; i++) {
            if (filesnm != '')
                filesnm = filesnm + ',' + newdocfileslist[i];
            else
                filesnm = filesnm + newdocfileslist[i];
        }
        document.getElementById("file_upload").value = filesnm;
    }
</script>

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

@endsection