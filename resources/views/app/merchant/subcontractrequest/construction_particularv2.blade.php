<script src="/assets/admin/layout/scripts/contract.js?version=1663678119" type="text/javascript"></script>

@if(isset($template_info->template_type) && $template_info->template_type!='scan')
<!-- add particulars label -->
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
        border-right: 2px solid #D9DEDE !important;
        background-color: #fff;
    }

    .row-label {
        font-size: 14px;
    }

    .fa-exclamation-circle {
        color: #394242;
    }

    .table thead tr th {
        font-size: 0.7rem;
        font-weight: 600;
    }

    td,
    th {
        font-size: 0.7rem;
    }
</style>
<h3 class="form-section">Add particulars
</h3>
<div x-data="handler()">
    <a data-cy="add_particulars_btn" href="javascript:;" @click="addNewField()" class="btn mb-1 green pull-right"> Add new row </a>
    @if($product_taxation_type=='3')
    <div class="row">
        <div class="col-md-2">
            <select id="product_taxation_type" required onchange="setProductTaxation(this.value);calculateamt();calculatetax(undefined,undefined,'1')" class="form-control" name="taxation_type">
                <option @if($invoice_product_taxation=='1' ) selected @endif value="1">
                    <div>
                        <b>Product cost exclusive of tax</b>
                    </div>
                </option>
                <option @if($invoice_product_taxation=='2' ) selected @endif value="2">
                    <div>
                        <b> Product cost inclusive of tax</b>
                    </div>
                </option>
            </select>
        </div>
    </div>
    @endif


    <input type="hidden" name="contract_id" value="{{$contract_id}}">
    @endif


    <div class="row">
        <div class="col">
            <div class="">
                <table class="table table-bordered  table-hover table-sm">
                    @php $particular_column= json_decode($template_info->particular_column,1); @endphp
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
                            <th class="td-c">
                                ?
                            </th>
                        </tr>
                    </thead>
                    @endif
                    @php $readonly_array=array('current_contract_amount','previously_billed_percent','net_billed_amount','retainage_amount_previously_withheld','previously_billed_amount','current_billed_amount','total_billed','retainage_amount_for_this_draw','total_outstanding_retainage');
                    @endphp
                    <tbody>
                        <template x-for="(field, index) in fields" :key="index">
                            <tr>
                                @foreach($particular_column as $k=>$v)
                                @php $readonly=false; @endphp
                                @if($k!='description')
                                @if(in_array($k, $readonly_array))
                                @php $readonly=true; @endphp
                                @endif
                                <td @if($readonly==false) x-on:click="field.txt{{$k}} = true; @if($k=='bill_code') setAdvanceDropdownContract(index); @endif" x-on:blur="field.txt{{$k}} = false" @endif class="td-c @if($k=='bill_code') col-id-no @endif" @if($k=='description' || $k=='bill_code' ) style="min-width: 100px;" @endif>
                                    <span x-show="! field.txt{{$k}}" x-text="field.{{$k}}"> </span>
                                    <span x-show="field.txt{{$k}}">
                                        @if($k=='bill_code')
                                        <select required style="width: 100%; min-width: 200px;" @if($readonly==true) type="hidden" @else type="text" x-on:blur="field.txt{{$k}} = false" @endif x-model="field.{{$k}}" value="fee" name="{{$k}}[]" data-placeholder="Type or Select Bill Type" class="form-control input-sm select2me productselect">
                                            <option value="">Select Code</option>
                                            @if(!empty($csi_code))
                                            @php $excode=0; @endphp
                                            @foreach($csi_code as $pk=>$vk)
                                            <option value="{{$vk->code}}">{{$vk->code}} {{$vk->title}}</option>
                                            @endforeach

                                            @endif
                                        </select>
                                        @elseif($k=='bill_type')
                                        <select required style="min-width: 120px !important;" @if($readonly==true) type="hidden" @else type="text" x-on:blur="field.txt{{$k}} = false" @endif x-model="field.{{$k}}" value="fee" name="{{$k}}[]" data-placeholder="Type or Select Bill Type" class="form-control input-sm">
                                            <option value="0">Select Type</option>
                                            <option value="% Complete">% Complete</option>
                                            <option value="Unit">Unit</option>
                                            <option value="Calculated">Calculated</option>
                                        </select>
                                        @else
                                        <input @if($readonly==true) type="hidden" @else type="text" x-on:blur="field.txt{{$k}} = false;calc(field);" @endif x-model="field.{{$k}}" value="fee" name="{{$k}}[]" class="form-control input-sm">
                                        @endif
                                    </span>
                                </td>
                                @endif
                                @endforeach
                                <td class="td-c ">
                                    <button type="button" class="btn btn-xs red" @click="removeField(index)">&times;</button>
                                </td>
                            </tr>
                        </template>
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>



<script>
    function handler() {
        return {
            fields: [],
            grand_total: 0,
            addNewField() {
                this.fields.push({

                });
            },
            removeField(index) {
                grand_total = grand_total - getamt(this.fields[index].net_billed_amount);
                this.fields.splice(index, 1);
            },

            calc(field) {
                try {
                    field.current_contract_amount = updateTextView1(getamt(field.approved_change_order_amount) + getamt(field.original_contract_amount));
                    field.current_billed_amount = updateTextView1(getamt(field.current_contract_amount) * getamt(field.current_billed_percent) / 100);
                    field.total_billed = updateTextView1(getamt(field.current_billed_amount) + getamt(field.previously_billed_amount) + getamt(field.stored_materials));
                    field.retainage_amount_for_this_draw = updateTextView1(getamt(field.total_billed) * getamt(field.retainage_percent) / 100);
                    field.total_outstanding_retainage = field.retainage_amount_for_this_draw;
                    field.net_billed_amount = updateTextView1(getamt(field.current_billed_amount) + getamt(field.stored_materials) - getamt(field.retainage_amount_for_this_draw));
                    grand_total = grand_total + field.net_billed_amount

                    document.getElementById('totalamount').value = updateTextView1(grand_total);
                    document.getElementById('grandtotal').value = updateTextView1(grand_total);
                } catch (o) {
                    //alert(o.message);
                }

            }

        }

    }
</script>