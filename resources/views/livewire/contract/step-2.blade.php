<div>
    <style>
        .onhover-border:hover {
            border: 1px solid #ddd !important;
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

    </style>
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
                                    <th class="td-c @if($k=='bill_code') col-id-no @endif" @if($k=='description' || $k=='bill_code' ) style="min-width: 100px;" @endif>
                                        <span class="popovers" data-placement="top" data-container="body" data-trigger="hover" data-content="{{$v}}" data-original-title=""> {{Helpers::stringShort($v)}}</span>
                                    </th>
                                @endif
                            @endforeach
                            <th class="td-c" style="width: 60px;">
                                ?
                            </th>
                        </tr>
                        </thead>
                    @endif

                    @php $readonly_array=array('retainage_amount','bill_code_detail','group','bill_type','bill_code');
                    $number_array=array('original_contract_amount','retainage_percent');
//                    @endphp

                    <tbody>
                    <template x-for="(field, index) in fields" :key="index">
                        <tr>
                            @foreach($particular_column as $k=>$v)
                                @php $readonly=false; @endphp
                                @php $number='type="text"'; @endphp
                                @if($k!='description')
                                    @if(in_array($k, $readonly_array))
                                        @php $readonly=true; @endphp
                                    @endif
                                    @if(in_array($k, $number_array))
                                        @php $number='type=number step=0.00'; @endphp
                                    @endif
                                    <td style="max-width: 100px;vertical-align: middle; @if($k=='retainage_amount') background-color:#f5f5f5; @endif" :id="`cell_{{$k}}_${index}`" @if($readonly==false)  x-on:click="field.txt{{$k}} = true; " x-on:blur="field.txt{{$k}} = false" @endif class="td-c onhover-border @if($k=='bill_code') col-id-no @endif">
                                        @if($k=='bill_code')
                                            <select required style="width: 100%; min-width: 200px;" onchange="billCode2()" :id="`billcode${index}`"
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
                                            <input type="hidden" name="calculated_perc[]" x-model="field.calculated_perc" :id="`calculated_perc${index}`">
                                            <input type="hidden" name="calculated_row[]" x-model="field.calculated_row" :id="`calculated_row${index}`">
                                            <input type="hidden" name="description[]"  x-value="field.description" :id="`description${index}`">
                                            <div class="text-center" style="display: none;">
                                                <p :id="`description-hidden${index}`" x-text="field.description"></p>
                                            </div>
                                        @elseif($k=='group')
                                            <select required style="width: 100%; min-width: 200px;" x-ref="`group${index}`" :id="`group${index}`" x-model="field.{{$k}}" name="{{$k}}[]" data-placeholder="Select group" class="form-control input-sm select2me groupSelect">
                                                <option value="">Select group</option>
                                                <template x-for="(group, groupindex) in groups" :key="groupindex">
                                                    <option x-value="group" x-text="group"></option>
                                                </template>
                                                {{--@if(!empty($groups))
                                                    @foreach($groups as $g)
                                                        <option value="{{$g}}">{{$g}}</option>
                                                    @endforeach
                                                @endif--}}

                                            </select>
                                        @elseif($k=='bill_type')
                                            <select required style="width: 100%; min-width: 15  0px;font-size: 12px;" :id="`billtype${index}`" x-model="field.{{$k}}" name="{{$k}}[]" data-placeholder="Select.." class="form-control select2me billTypeSelect">
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
                                                    <a :id="`remove-calc${index}`" x-show="field.original_contract_amount" style="padding-top:5px;" href="javascript:;" @click="RemoveCaculated(field)">Remove</a>
                                                    <span :id="`pipe-calc${index}`" x-show="field.original_contract_amount" style="margin-left: 4px; color:#859494;"> | </span>
                                                    <a :id="`edit-calc${index}`" x-show="field.original_contract_amount" style="padding-top:5px;padding-left:5px;" href="javascript:;" @click="EditCaculated(field)">Edit</a>
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

                                            <input :id="`introw${index}`" type="hidden" :value="index" x-model="field.introw" name="pint[]">


                                        @else
                                            <span x-show="field.txt{{$k}}">
                                        <input :id="`{{$k}}${index}`" @if($readonly==true) type="hidden" @else type="text" x-on:blur="field.txt{{$k}} = false;calc(field);" @endif x-model="field.{{$k}}" value="" name="{{$k}}[]" style="width: 100%;" class="form-control input-sm ">
                                    </span>
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
                        <th class="td-c">Grand total</th>
                        <th class="td-c">
                            <span id="particulartotaldiv">{{$total}}</span>
                            <input type="hidden" id="particulartotal" data-cy="particular-total1" name="totalcost" value="{{$total}}" class="form-control " readonly="">
                        </th>
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
