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
        }

        .table > tbody > tr > td {
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

        .dropdown-menu li > a {
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
    </style>


    <div>

        
<div class="page-content">
    <div x-data="handler_create()" x-init="initSelect2" >
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
                    <form action="/merchant/invoice/particularsave" id="frm_expense" onsubmit="loader();" method="post" enctype="multipart/form-data" class="form-horizontal form-row-sepe">
                        @csrf
                       
                        <div>
    
    <div class="portlet light bordered">
        <div class="portlet-body form">
            <div class="row">
                <div class="col-md-6">
                    <h3 class="form-section">Add Particulars</h3>
                </div>
                <div class="col-md-6">
                    <a data-cy="add_particulars_btn" href="javascript:;" @click="await addNewField();"
                       class="btn green pull-right mb-1"> Add new row </a>
                </div>
            </div>
            <div class="table-scrollable" >
                <table class="table table-bordered table-hover" id="particular_table" wire:ignore>
                    @if(!empty($particular_column))
                        <thead>
                        <tr>
                            @foreach($particular_column as $k=>$v)
                                @if($k!='description')
                                    <th  @if($k=='bill_code') class="td-c col-id-no" style="min-width:200px ;" @elseif($k=='bill_type') class="td-c" style="min-width:120px ;" @else class="td-c" style="min-width:100px ;" @endif @if($k=='description' || $k=='bill_code' ) style="min-width: 100px;" @endif>
                                        <span class="popovers" data-placement="top" data-container="body" data-trigger="hover" data-content="{{$v}}" data-original-title=""> {{Helpers::stringShort($v)}}</span>
                                    </th>
                                @endif
                            @endforeach
                            <th class="td-c" style="min-width: 60px;">
                                ?
                            </th>
                        </tr>
                        </thead>
                    @endif

                    @php 
                    $readonly_array=array('retainage_amount','bill_code_detail','group','bill_type','bill_code','retainage_amount','approved_change_order_amount','current_contract_amount','previously_billed_percent','previously_billed_amount','current_billed_amount','total_billed','retainage_amount_previously_withheld','retainage_amount_for_this_draw','net_billed_amount','total_outstanding_retainage');
                    $disable_array=array('retainage_amount','approved_change_order_amount','current_contract_amount','previously_billed_percent','previously_billed_amount','current_billed_amount','total_billed','retainage_amount_previously_withheld','retainage_amount_for_this_draw','net_billed_amount','total_outstanding_retainage');
                    $dropdown_array=array('group','bill_type','bill_code','bill_code_detail');
//                    @endphp

                    <tbody>
                    <template x-for="(field, index) in fields" :key="index">
                        <tr>
                            @foreach($particular_column as $k=>$v)
                                @php $readonly=false; @endphp
                                @php $disable=false; @endphp
                                @php $dropdown=false; @endphp
                                @if($k!='description')
                                    @if(in_array($k, $readonly_array))
                                        @php $readonly=true; @endphp
                                    @endif
                                    @if(in_array($k, $disable_array))
                                        @php $disable=true; @endphp
                                    @endif
                                    @if(in_array($k, $dropdown_array))
                                        @php $dropdown=true; @endphp
                                    @endif
                                    <td style="vertical-align: middle; @if($disable==true) background-color:#f5f5f5; @endif" :id="`cell_{{$k}}_${index}`" @if($readonly==false)  x-on:click="field.txt{{$k}} = true; " x-on:blur="field.txt{{$k}} = false" @endif class="td-c onhover-border @if($k=='bill_code') col-id-no @endif">
                                        @if($k=='bill_code')
                                        <div style="display:flex;">
                                            <select required style=" min-width: 200px;" onchange="billCode2()" :id="`bill_code${index}`"
                                                    x-model="field.{{$k}}" name="{{$k}}[]" data-placeholder="Select Bill Code"
                                                    class="form-control input-sm select2me productselect" @update-csi-codes.window="bill_codes = this.bill_codes">
                                                <option value="">Select Code</option>

                                                @if(!empty($csi_codes))
                                                    @foreach($csi_codes as $code)
                                                        <option value="{{$code['code']}}">{{$code['code']}} | {{$code['title']}}</option>
                                                    @endforeach
                                                    <template x-for="(bill_code, newbillcodeindex) in new_codes" :key="newbillcodeindex">
                                                        <option x-value="bill_code.code" x-text="`${bill_code.code} | ${bill_code.title}`"></option>
                                                    </template>
                                                @else
                                                    <template x-for="(bill_code, billcodeindex) in bill_codes" :key="billcodeindex">
                                                        <option x-value="bill_code.code" x-text="`${bill_code.code} | ${bill_code.title}`"></option>
                                                    </template>
                                                @endif
                                            </select>
                                            <input type="hidden" name="attachments[]" x-model="field.attachments" :id="`attach-${index}`" value=""/>
                                            <a @click="showupdatebillcodeattachment(`${index}`);" :id="`attacha-${index}`" style="align-self: center; margin-left: 3px;" class="pull-right popovers">
                                            <i :id="`icon-${index}`"  class="fa fa-paperclip" data-placement="top" data-container="body" data-trigger="hover" data-content="0 file " aria-hidden="true" data-original-title="" title="0 file"></i>
                                         </a>
                                            <input type="hidden" name="calculated_perc[]" x-model="field.calculated_perc" :id="`calculated_perc${index}`">
                                            <input type="hidden" name="calculated_row[]" x-model="field.calculated_row" :id="`calculated_row${index}`">
                                            <input type="hidden" name="description[]"  x-model="field.description" :id="`description${index}`">
                                            <div class="text-center" style="display: none;">
                                                <p :id="`description-hidden${index}`" x-text="field.description"></p>
                                            </div>
                                            </div>
                                        @elseif($k=='group')
                                            <select required style="width: 100%; min-width: 200px;" x-ref="`group${index}`" :id="`group${index}`" x-model="field.{{$k}}" name="{{$k}}[]" data-placeholder="Select group" class="form-control input-sm  ">
                                                <option value="">Select..</option>
                                                @if(!empty($groups))
                                                    @foreach($groups as $g)
                                                        <option value="{{$g}}">{{$g}}</option>
                                                    @endforeach
                                                @endif

                                            </select>
                                        @elseif($k=='bill_type')
                                            <select required style="width: 100%; min-width: 15  0px;font-size: 12px;" :id="`bill_type${index}`" x-model="field.{{$k}}" name="{{$k}}[]" data-placeholder="Select.." class="form-control select2me billTypeSelect">
                                                <option value="">Select..</option>
                                                <option value="% Complete">% Complete</option>
                                                <option value="Unit">Unit</option>
                                                <option value="Calculated">Calculated</option>
                                            </select>
                                        @elseif($k=='bill_code_detail')
                                            <select required style="width: 100%; min-width: 200px;" :id="`billcodedetail${index}`" x-model="field.{{$k}}" name="{{$k}}[]" data-placeholder="Select.." class="form-control  input-sm billcodedetail">
                                                <option value="">Select..</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        @else
                                            @if($k=='original_contract_amount')
                                                <template x-if="field.bill_type!='Calculated'">
                                                    <span x-show="! field.txt{{$k}}" x-text="field.{{$k}}"> </span>
                                                </template>
                                            @else
                                                <span x-show="! field.txt{{$k}}" x-text="field.{{$k}}" @if($k == 'project') @update-csi-codes.window="field.{{$k}} = this.project"  @endif> </span>
                                            @endif
                                        @endif

                                        @if($k=='original_contract_amount')

                                            <template x-if="field.bill_type=='Calculated'">
                                                <div>
                                                    <span :id="`lbl_original_contract_amount${index}`" x-text="field.{{$k}}"></span><br>
                                                    <a :id="`add-calc${index}`" style=" padding-top: 5px;" x-show="!field.original_contract_amount" href="javascript:;" @click="OpenAddCaculated(field)">Add calculation</a>
                                                    <template x-if="field.override==false">
                                                    <a :id="`edit-calc${index}`" x-show="field.original_contract_amount" style="padding-top:5px;padding-left:5px;" href="javascript:;" @click="EditCaculated(field)">Override</a>
                                                    <span :id="`pipe-calc${index}`"  style="margin-left: 4px; color:#859494;display: none;"> | </span>
                                                    <a :id="`remove-calc${index}`"  style="padding-top:5px;display: none;" href="javascript:;" @click="RemoveCaculated(field)">Remove</a>
                                                    </template>
                                                    <template x-if="field.override==true">
                                                    <a :id="`remove-calc${index}`" x-show="field.original_contract_amount" style="padding-top:5px;" href="javascript:;" @click="RemoveCaculated(field)">Remove</a>
                                                    <span :id="`pipe-calc${index}`" x-show="field.original_contract_amount" style="margin-left: 4px; color:#859494;"> | </span>
                                                    <a :id="`edit-calc${index}`" x-show="field.original_contract_amount" style="padding-top:5px;padding-left:5px;" href="javascript:;" @click="EditCaculated(field)">Edit</a>
                                                    </template>
                                                </div>
                                                <span x-show="field.txt{{$k}}">
                                            <input :id="`{{$k}}${index}`" type="hidden" x-model="field.{{$k}}" value="" name="{{$k}}[]" style="width: 100%;" class="form-control input-sm ">
                                        </span>
                                            </template>
                                            <template x-if="field.bill_type!='Calculated'">
                                        <span x-show="field.txt{{$k}}">
                                            <input :id="`{{$k}}${index}`" @if($readonly==true) type="hidden" @else type="text" x-on:blur="field.txt{{$k}} = false;calc(field);saveParticulars();" @endif @keyup="removeValidationError(`{{$k}}`, `${index}`)" x-model="field.{{$k}}" value="" name="{{$k}}[]" style="width: 100%;" class="form-control input-sm ">
                                        </span>
                                            </template>

                                            <input :id="`introw${index}`" type="hidden" :value="index" x-model="field.pint" name="pint[]">


                                        @else
                                        @if($dropdown==false)
                                            <span x-show="field.txt{{$k}}">
                                        <input :id="`{{$k}}${index}`" @if($readonly==true) type="hidden" @else type="text" x-on:blur="field.txt{{$k}} = false;calc(field);" @endif x-model="field.{{$k}}" value="" name="{{$k}}[]" style="width: 100%;" class="form-control input-sm ">
                                    </span>
                                        @endif
                                        @endif
                                    </td>
                                @endif
                            @endforeach
                            <td class="td-c " style="vertical-align: middle;width: 60px;">
                                <button type="button" class="btn btn-xs red" @click="removeField(index)">&times;</button>
                                <template x-if="count===index">
                                        <span>
                                            <a href="javascript:;" @click="await addNewField();" class="btn btn-xs green">+</a>
                                        </span>
                                </template>
                            </td>
                        </tr>
                    </template>
                    </tbody>
                    <tfoot>
                    <tr class="warning">
                        <th class="col-id-no"></th>
                        <th ></th>
                        <th>
                            
                        </th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>

                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th class="td-c">Grand total</th>
                        <th class="td-c"><span id="particulartotaldiv">{{$total}}</span>
                            <input type="hidden" id="particulartotal" data-cy="particular-total1" name="totalcost" value="{{$total}}" class="form-control " readonly=""></th>
                        <th></th>
                        <th></th>
                        <th></th>
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
    <script>

    </script>
</div>





                        

                        <div class="portlet light bordered">
                            <div class="portlet-body form">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="pull-right">
                                        <input type="hidden" id="request_id" name="link" value="{{$link}}" ></th>

                                            <a href="/merchant/contract/list" class="btn green">Cancel</a>
                                            <a class="btn green" href="/merchant/invoice/createv2/{{$link}}">Back</a>
                                            <button type="submit" @click="return setParticulars();" class="btn blue" >Preview invoice</button>
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
                       /* $(document).ready(function () {
                            particularsDropdowns(0, this.fields);

                        });*/
                       csi_codes = JSON.parse('{!! json_encode($csi_codes) !!}');
                       var particularray = JSON.parse('{!! json_encode($particulars) !!}');
                       var previewArray = [];

                        

                        function initSelect2(){

                            if(particularray.length > 1) {
                                for (let p =0; p < particularray.length; p++)
                                this.particularsDropdowns(p);
                            }else {
                                this.particularsDropdowns(0);
                            }
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

                        function addPopover(id, message){
                            $('#'+id).attr({
                                'data-placement' : 'right',
                                'data-container' : "body",
                                'data-trigger' : 'hover',
                                'data-content' : message,
                                // 'data-original-title'
                            }).popover();
                        }

                        function handler_create() {

                            return {
                                step: 2,
                                contract_code: '{{$contract_code}}',
                                project_id : '{{$project_id}}',
                                goAhead: true,
                                fields : JSON.parse('{!! json_encode($particulars) !!}'),
                                bill_codes : JSON.parse('{!! json_encode($csi_codes) !!}'),
                                groups : JSON.parse('{!! json_encode($groups) !!}'),
                                test : null,
                                count: particularray.length - 1,
                                group_name: '',
                                group_show: false,
                                bill_code_name: '',
                                bill_code_description: '',
                                selected_group: [],
                                panel: true,
                                billcodepanel: false,
                                new_codes: [],
                                calculations: [],
                                bcd: ['Yes', 'No'],
                                bill_types: ['% Complete', 'Unit', 'Calculated'],
                                grand_total: 0,
                                project:null,
                                goToParticulars() {
                                    this.resetFlags();
                                    /*if (this.project_id === null || this.project_id === '') {
                                        this.goAhead = false
                                    }*/
                                    if (this.contract_code === null || this.contract_code === '') {
                                        this.goAhead = false
                                    }
                                    if (document.getElementById('project_id').value === '' || document.getElementById('project_id').value === null) {
                                        this.goAhead = false
                                    }
                                    if (this.contract_date === null || this.contract_date === '') {
                                        this.goAhead = false
                                    }

                                    if (this.bill_date === null || this.bill_date === '') {
                                        this.goAhead = false
                                    }
                                    this.goToNextStep();
                                    for(let b=0; b<$('input[name="bill_code[]"]').length; b++)
                                        this.particularsDropdowns(b);

                                },
                                goToPreview() {

                                    this.resetFlags();
                                    this.validateParticulars();
                                    if(this.goAhead) {
                                        this.saveParticulars();
                                    }

                                    this.goToNextStep();
                                    previewv5(previewArray);
                                },
                                saveParticulars(){
                                    return true;

                                    var particulars = this.getParticularsInfo();
                                    /*Hack - need to change in future */
                                    /*for (let d =0 ; d< data.length; d++){
                                        data[d].original_contract_amount = data[d].original_contract_amount.replace(',','')
                                        data[d].retainage_percent = data[d].retainage_percent.replace(',','')
                                        data[d].retainage_amount = data[d].retainage_amount.replace(',','')
                                    }*/
                                    /*Hack - need to change in future */

                                    if (particulars.length == 0) {
                                        $('#paticulars_error').show()
                                        return false;
                                    }

                                    var actionUrl = '/merchant/invoice/particularsave/ajax';
                                    $.ajax({
                                        type: "POST",
                                        url: actionUrl,
                                        data: {
                                            _token: '{{ csrf_token() }}',
                                            form_data: JSON.stringify(particulars),
                                            link: $('#request_id').val(),
                                        },
                                        success: function(data) {
                                            console.log(111)
                                        }
                                    })
                                },
                                saveContract() {
                                     $('#frm_expense').submit();
                                },
                                resetFlags() {
                                    this.goAhead = true;
                                    $('#paticulars_error').hide()
                                },
                                goToNextStep() {
                                    console.log(this.goAhead, this.step);
                                    if (this.goAhead) this.step++;
                                },
                                particularsDropdowns(id = null) {

                                     try {
                                         $('#bill_code' + id).select2({
                                             insertTag: function (data, tag) {

                                             }
                                         }).on('select2:open', function (e) {
                                             pind = $(this).index();
                                             if (document.getElementById('prolist' + pind)) { } else {
                                                 $('.select2-results').append('<div class="wrapper" id="prolist' + pind + '" > <a class="clicker" onclick="billIndex(' + id + ',' + id + ',0);">Add new bill code</a> </div>');
                                             }
                                         }).on('select2:select', function (e){
                                             let data = $('#bill_code' + id).select2('val');
                                             let dataArray = data.split('|')
                                             particularray[id]['bill_code'] = dataArray[0].trim();
                                             particularray[id]['bill_code_text'] = data;

                                             if(data.length > 0) {
                                                 $('#cell_bill_code_' + id).removeClass(' error-corner').popover('destroy');

                                             }
                                         });
                                     } catch (o) { }


                                     
                                 },
                                getParticularsInfo(){
                                    previewArray = particularray;
                                    
                                     for(let i=0;i<particularray.length;i++) {
                                         particularray[i].bill_type = this.fields[i].bill_type;
                                         particularray[i].original_contract_amount = (this.fields[i].original_contract_amount === '' || this.fields[i].original_contract_amount === null)?0 : this.fields[i].original_contract_amount;
                                         particularray[i].retainage_percent = (this.fields[i].retainage_percent === '' || this.fields[i].retainage_percent === null || this.fields[i].retainage_percent === undefined)?0 : this.fields[i].retainage_percent;
                                         particularray[i].retainage_amount = (this.fields[i].retainage_amount === '' || this.fields[i].retainage_amount === null || this.fields[i].retainage_amount === undefined)?0 : this.fields[i].retainage_amount;
                                         particularray[i].project = this.fields[i].project;
                                         particularray[i].project_code = this.fields[i].project;
                                         particularray[i].cost_code = (this.fields[i].cost_code === undefined) ? '' : this.fields[i].cost_code ;
                                         particularray[i].cost_type = (this.fields[i].cost_type === undefined) ? '' : this.fields[i].cost_type ;
                                         particularray[i].calculated_perc = this.fields[i].calculated_perc;
                                         particularray[i].calculated_row = this.fields[i].calculated_row;
                                         particularray[i].approved_change_order_amount = this.fields[i].approved_change_order_amount;
                                         particularray[i].current_billed_amount = this.fields[i].current_billed_amount;
                                         particularray[i].current_billed_percent = this.fields[i].current_billed_percent;
                                         particularray[i].current_contract_amount = this.fields[i].current_contract_amount;
                                         particularray[i].description = this.fields[i].description;
                                         particularray[i].net_billed_amount = this.fields[i].net_billed_amount;
                                         particularray[i].original_contract_amount = this.fields[i].original_contract_amount;
                                         particularray[i].previously_billed_amount = this.fields[i].previously_billed_amount;
                                         particularray[i].retainage_amount = this.fields[i].retainage_amount;
                                         particularray[i].retainage_amount_for_this_draw = this.fields[i].retainage_amount_for_this_draw;
                                         particularray[i].retainage_percent = this.fields[i].retainage_percent;
                                         particularray[i].stored_materials = this.fields[i].stored_materials;
                                         particularray[i].total_billed = this.fields[i].total_billed;
                                         particularray[i].total_outstanding_retainage = this.fields[i].total_outstanding_retainage;
                                         particularray[i].txtoriginal_contract_amount = this.fields[i].txtoriginal_contract_amount;
                                         particularray[i].bill_code_detail = (this.fields[i].bill_code_detail === '' || this.fields[i].bill_code_detail === null)? 'Yes' : this.fields[i].bill_code_detail ;
                                     }
                                     return particularray
                                 },
                                removeField(id) {
                                    this.fields.splice(id, 1);
                                    particularray.splice(id, 1);

                                    total = 0;
                                    this.fields.forEach(function(currentValue, index, arr) {
                                        if(currentValue.net_billed_amount==null)
                                        {
                                            net_billed_amount=0;
                                        }else{
                                            net_billed_amount=currentValue.net_billed_amount;
                                        }

                                        total = Number(total) + Number(getamt(net_billed_amount));
                                    });

                                    document.getElementById('particulartotal').value = updateTextView1(total);
                                    document.getElementById('particulartotaldiv').innerHTML = updateTextView1(total);

                                    numrow = this.fields.length - 1;
                                    this.count = numrow;

                                },
                                addGroup(field) {

                                    this.selected_group = field;
                                    this.panel = true;
                                    document.getElementById("panelWrapIdgroup").style.boxShadow = "0 0 0 9999px rgba(0,0,0,0.5)";
                                    document.getElementById("panelWrapIdgroup").style.transform = "translateX(0%)";
                                },
                                addBillCode(field) {
                                    this.selected_group = field;
                                    this.billcodepanel = true;
                                    document.getElementById("_project_id").value = document.getElementById("project_id").value;
                                    document.getElementById("panelWrapIdBillCodePanel").style.boxShadow = "0 0 0 9999px rgba(0,0,0,0.5)";
                                    document.getElementById("panelWrapIdBillCodePanel").style.transform = "translateX(0%)";
                                },
                                validateParticulars(){

                                    for(let p=0; p < particularray.length;p++){
                                        console.log(particularray);
                                        if(particularray[p].bill_code === null || particularray[p].bill_code === '') {
                                            $('#cell_bill_code_' + p).addClass(' error-corner');
                                            addPopover('cell_bill_code_' + p, "Please select Bill code");
                                            this.goAhead = false;
                                        }else{
                                            $('#cell_bill_code_' + p).removeClass(' error-corner').popover('destroy')
                                        }

                                        if(this.fields[p].bill_type === null || this.fields[p].bill_type === '') {
                                            $('#cell_bill_type_' + p).addClass(' error-corner');
                                            addPopover('cell_bill_type_' + p, "Please select Bill type");
                                            this.goAhead = false;
                                        }else{
                                            $('#cell_bill_type_' + p).removeClass(' error-corner').popover('destroy')
                                        }

                                        if(particularray[p].original_contract_amount === null || particularray[p].original_contract_amount === '') {
                                            $('#cell_original_contract_amount_' + p).addClass(' error-corner');
                                            addPopover('cell_original_contract_amount_' + p, "Please enter original contract amount");
                                            this.goAhead = false;
                                        }else {
                                            $('#cell_original_contract_amount_' + p).removeClass(' error-corner').popover('destroy')
                                        }
                                    }
                                },
                                removeValidationError(fieldName, id){
                                    if($('#'+fieldName+id).val() !== null && $('#'+fieldName+id).val() !== '') {
                                        $('#cell_'+ fieldName+'_'+id).removeClass(' error-corner').popover('destroy')
                                    }
                                },
                                saveGroup() {
                                    if (this.group_name != '') {
                                        this.groups.push(this.group_name);
                                        group_index = document.getElementById("group_index").value;

                                        document.getElementById("group" + group_index).value = this.group_name;
                                        document.getElementById("panelWrapIdgroup").style.boxShadow = "";
                                        document.getElementById("panelWrapIdgroup").style.transform = "";
                                        var newState = new Option(this.group_name, this.group_name, true, true);
                                        $("#group" + group_index).append(newState).trigger('change');
                                        particularray[group_index].group = this.group_name
                                        this.group_name='';
                                    }

                                },

                                setBCD(val, field) {
                                    field.bill_code_detail = val;
                                },
                                setBillCode(val, field) {
                                    field.bill_code = val;
                                },

                                setParticulars()
                                {
                                    particularray.forEach(function(currentValue, index, arr) {
                                            document.getElementById('bill_code'+index).value = currentValue.bill_code;
                                    });
                                },

                                setBillCodes() {
                                    var bill_code_name = document.getElementById("bill_code_name").value;
                                    var bill_code_description = document.getElementById("bill_code_description").value;
                                    this.bill_codes.push({
                                        code: bill_code_name,
                                        title: bill_code_description,

                                    });
                                    this.new_codes.push({
                                        code: bill_code_name,
                                        title: bill_code_description,

                                    });
                                    //this.bill_codes = csi_codes;
                                    this.billcodepanel = false;
                                    this.selected_group.bill_code = bill_code_name;
                                },

                                calc(field) {
                                    try {
                                        try {
                                            this.groups.indexOf(field.group) === -1 ? this.groups.push(field.group) : console.log("This item already exists");
                                        } catch (o) {}
                                        try {
                                            if(field.original_contract_amount==null)
                                            {
                                                field.original_contract_amount='';
                                            }
                                            if(field.retainage_percent==null)
                                            {
                                                field.retainage_percent='';
                                            }
                                            field.retainage_amount = updateTextView1(getamt(field.original_contract_amount) * getamt(field.retainage_percent) / 100);
                                        } catch (o) {alert(1);}

                                        try {
                                            if(field.approved_change_order_amount==null)
                                            {
                                                field.approved_change_order_amount='';
                                            }
                                            field.current_contract_amount = updateTextView1(getamt(field.original_contract_amount) + getamt(field.approved_change_order_amount));
                                        } catch (o) {}

                                        try {
                                            if(field.current_billed_percent==null)
                                            {
                                                field.current_billed_percent='';
                                            }
                                            field.current_billed_amount = updateTextView1(getamt(field.current_contract_amount)  * getamt(field.current_billed_percent) / 100);
                                        } catch (o) {alert(3);}

                                        try {
                                            if(field.previously_billed_amount==null)
                                            {
                                                field.previously_billed_amount='';
                                            }
                                            if(field.current_billed_amount==null)
                                            {
                                                field.current_billed_amount='';
                                            }
                                            if(field.stored_materials==null)
                                            {
                                                field.stored_materials='';
                                            }
                                            field.total_billed = updateTextView1(getamt(field.current_billed_amount)  + getamt(field.previously_billed_amount) + getamt(field.stored_materials));
                                        } catch (o) {alert(4);}

                                        try {
                                            if(field.retainage_percent==null)
                                            {
                                                field.retainage_percent='';
                                            }
                                            field.retainage_amount_for_this_draw = updateTextView1(getamt(field.total_billed)  * getamt(field.retainage_percent) / 100);
                                        } catch (o) {alert(5);}

                                        try {
                                            
                                            field.total_outstanding_retainage = field.retainage_amount_for_this_draw;
                                        } catch (o) {alert(6);}

                                        try {
                                            field.net_billed_amount = updateTextView1(getamt(field.current_billed_amount)  + getamt(field.stored_materials) - getamt(field.retainage_amount_for_this_draw));
                                        } catch (o) {alert(7);}



                                        try {
                                            field.original_contract_amount = updateTextView1(getamt(field.original_contract_amount));
                                        } catch (o) {}
                                        try {
                                            field.retainage_percent = updateTextView1(getamt(field.retainage_percent));
                                        } catch (o) {}
                                        total = 0;
                                        this.fields.forEach(function(currentValue, index, arr) {
                                            try {
                                                oct = Number(getamt(currentValue.net_billed_amount));
                                            } catch (o) {
                                                oct = 0;
                                            }
                                            if (oct > 0) {
                                                total = Number(total) + oct;

                                            }
                                        });

                                        document.getElementById('particulartotal').value = updateTextView1(total);
                                        document.getElementById('particulartotaldiv').innerHTML = updateTextView1(total);
                                    } catch (o) {
                                        // alert(o.message);
                                    }

                                },

                                wait(x) {
                                    return new Promise((resolve) => {
                                        setTimeout(() => {
                                            resolve(x);
                                        }, 100);
                                    });
                                },

                                setAOriginalContractAmount() {
                                    selected_field_int = document.getElementById('selected_field_int').value;
                                    calc_amount = document.getElementById("calc_amount").value;
                                    try{
                                        this.fields[selected_field_int].original_contract_amount = calc_amount;
                                    }catch(o){}

                                    setOriginalContractAmount();
                                    this.fields[selected_field_int].calculated_perc = document.getElementById('calculated_perc' + selected_field_int).value;
                                    this.fields[selected_field_int].calculated_row = document.getElementById('calculated_row' + selected_field_int).value;

                                    calc(this.fields[selected_field_int]);

                                },
                                OpenAddCaculated(field) {
                                    console.log(field.pint);
                                    this.selected_field_int = field.pint;
                                    console.log(document.getElementById('selected_field_int'));
                                    document.getElementById('selected_field_int').value = field.pint;
                                    OpenAddCaculatedRow(field.pint);
                                },
                                RemoveCaculated(field) {
                                    this.fields[field.pint].original_contract_amount = 0;
                                    this.fields[field.pint].current_contract_amount = 0;
                                    document.getElementById('lbl_current_contract_amount' + field.pint).innerHTML = '';
                                    document.getElementById('lbl_original_contract_amount' + field.pint).innerHTML = '';
                                    RemoveCaculatedRow(field.pint);
                                },
                                EditCaculated(field) {
                                    document.getElementById('selected_field_int').value = field.pint;
                                    editCaculatedRow(field.pint);
                                },

                                select2Dropdown(id) {
                                    try {
                                        $('#bill_code' + id).select2({
                                            insertTag: function(data, tag) {

                                            }
                                        }).on('select2:open', function(e) {
                                            pind = $(this).index();
                                            if (document.getElementById('prolist' + pind)) {} else {
                                                $('.select2-results').append('<div class="wrapper" id="prolist' + pind + '" > <a class="clicker" onclick="billIndex(' + id + ',' + id + ',0);">Add new bill code</a> </div>');
                                            }
                                        });
                                        /*.on('change', function () {
                                                                    let valueArr = $(this).val().split(' | ');
                                                                    // this.fields[id].bill_code = valueArr[0];
                                                                    console.log(this.fields.length);
                                                                });*/
                                    } catch (o) {}

                                    try {
                                        // $('#billtype' + id).select2({
                                        //     minimumResultsForSearch: -1
                                        // });
                                    } catch (o) {}
                                    try {
                                        $('#billcodedetail' + id).select2({
                                            minimumResultsForSearch: -1
                                        });
                                    } catch (o) {}

                                    
                                },

                                init() {
                                    console.log('I will get evaluated when initializing each "dropdown" component.')
                                },
                                async addNewField() {
                                    document.getElementById('perror').style.display = 'none';
                                        project_code = '{{$project_id}}';
                                        this.bill_codes = csi_codes;
                                        int = this.fields.length-1;
                                        pint=Number(this.fields[int].pint) + 1;
                                        this.fields.push({
                                            introw: pint,
                                            pint: pint,
                                            bill_code: '',
                                            bill_type: '',
                                            group: '',
                                            override: true,
                                            bill_code_detail: '',
                                            bill_code_detail: 'Yes',
                                            project: project_code
                                        });
                                        particularray.push({
                                            introw: pint,
                                            pint: pint,
                                            bill_code: '',
                                            bill_type: '',
                                            override: true,
                                            group: '',
                                            bill_code_detail: '',
                                            project: project_code
                                        })
                                        const x = await this.wait(10);
                                        numrow = this.fields.length - 1;
                                        this.count = numrow;
                                        this.particularsDropdowns(numrow)
                                }

                            }
                        }
                    </script>

</div>
    <script>
        mode = '{{$mode}}';
    </script>
    </div>

    <script src="https://releases.transloadit.com/uppy/v1.28.1/uppy.min.js"></script>
<script>
    var newdocfileslist=[];
//uppy file upload code
var uppy = Uppy.Core({ 
    autoProceed: true,
    restrictions: {
        maxFileSize: 3000000,
        maxNumberOfFiles: 10,
        minNumberOfFiles: 1,
        allowedFileTypes: ['.jpg','.png','.jpeg','.pdf']
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
    method:'post',
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
    path = response.body.fileUploadPath;
    extvalue=document.getElementById("file_upload").value;
    newdocfileslist.push(path);
    deletedocfile('');
    if(extvalue!='')
    {
        document.getElementById("file_upload").value=extvalue+','+path;
    }else{
        document.getElementById("file_upload").value=path;
    }
    if(response.body.status == 300) {
        document.getElementById("error").innerHTML = response.body.errors;
        uppy.removeFile(file.id);
    } else {
        document.getElementById("error").innerHTML = '';
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


    function setdata(name,fullurl)
     {
     
       document.getElementById('poptitle').innerHTML="Delete attachment - "+name;
       document.getElementById('docfullurl').value=fullurl;
     }
     function deletedocfile(x)
     {
        var html='';
if(x=='delete')
{
 var fullurl=document.getElementById('docfullurl').value;
 var index = newdocfileslist.indexOf(fullurl);
 if (index !== -1) {
    newdocfileslist.splice(index, 1);
 }
}

 for(var i=0;i<newdocfileslist.length;i++)
 {
    var filenm=newdocfileslist[i].substring(newdocfileslist[i].lastIndexOf('/')+1);
    filenm=filenm.split('.').slice(0, -1).join('.')
    filenm =filenm.substring(0, filenm.length - 4);
  html=html+'<span class=" btn btn-xs green" style="margin-bottom: 5px;margin-left: 0px !important;margin-right: 5px !important">'+
           '<a class=" btn btn-xs " target="_BLANK" href="'+newdocfileslist[i]+'" title="Click to view full size">'+filenm.substring(0,10)+'..</a>'+
            '<a href="#delete_doc" onclick="setdata(\''+filenm.substring(0,10)+'\',\''+newdocfileslist[i]+'\');"   data-toggle="modal"> '+
             ' <i class=" popovers fa fa-close" style="color: #A0ACAC;" data-placement="top" data-container="body" data-trigger="hover"  data-content="Remove doc"></i>   </a> </span>';
            
        
 }
 clearnewuploads('no');
             document.getElementById('docviewbox').innerHTML=html;
 document.getElementById('closeconformdoc').click();
     }
     function clearnewuploads(x){
         document.getElementById("file_upload").value = '';
         
         var filesnm='';
        
 for(var i=0;i<newdocfileslist.length;i++)
 {
     if(filesnm!='')
         filesnm=filesnm+','+newdocfileslist[i];
         else
         filesnm=filesnm+newdocfileslist[i];
 }
 document.getElementById("file_upload").value =filesnm;
     }
     </script>

    @include('app.merchant.contract.add-bill-code-modal')
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
                <a  id="attach-delete-click" onclick="deleteattchment()" data-dismiss="modal" class="btn delete">Confirm</a>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

@endsection