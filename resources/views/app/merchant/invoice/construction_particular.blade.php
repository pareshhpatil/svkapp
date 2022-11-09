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
</style>
<div class="row">
    <div class="col-md-6">
        <h3 class="form-section">Add particulars</h3>
    </div>
    <div class="col-md-6">
        <a data-cy="add_particulars_btn" href="javascript:;" onclick="AddInvoiceParticularRowConstruction();" class="btn mb-1 green pull-right"> Add new row </a>
    </div>
</div>

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
<div class="table-scrollable">
    <table class="table table-bordered table-hover" id="particular_table">
        @php $particular_column= json_decode($template_info->particular_column,1); @endphp
        @if(!empty($particular_column))
        <thead>
            <tr>
                @foreach($particular_column as $k=>$v)
                @if($k!='description')
                <th class="td-c @if($k=='bill_code') col-id-no @endif" @if($k=='description' || $k=='bill_code' ) style="min-width: 200px;" @endif>
                    {{$v}}
                </th>
                @endif
                @endforeach
                <th class="td-c">
                    Actions
                </th>
            </tr>
        </thead>


        <tbody id="new_particular">
            @php $discount_perc=0; @endphp
            @php $rate=0; @endphp
            @php $int=0; @endphp
            @php $nopint=0; @endphp
            @if(!empty($contract_particulars))

            @foreach($contract_particulars as $dp)
            <tr>
                @foreach($particular_column as $k=>$v)
                @php $readonly=''; @endphp
                @if($k!='description')
                @isset($dp->{$k})
                @php $value=$dp->{$k}; @endphp
                @php $nopint=isset($dp->pint)?0:1; @endphp
                @php $int=($nopint==0)?$dp->pint:$int; @endphp
                @else
                @php $value=''; @endphp
                @endisset
                @php $calculated=0; @endphp
                @php
                $dp->description =isset($dp->description )? $dp->description : '';
                $dp->group =isset($dp->group )? $dp->group : '';
                $dp->bill_code_detail =isset($dp->bill_code_detail )? $dp->bill_code_detail : '';
                $dp->calculated_perc =isset($dp->calculated_perc )? $dp->calculated_perc : '';
                $dp->calculated_row=isset($dp->calculated_row)? $dp->calculated_row : '';
                @endphp
                <td @if($k=='bill_code' ) class="col-id-no" @endif style="text-align:center;">
                    @if($k == 'current_contract_amount' || $k == 'previously_billed_percent' || $k == 'net_billed_amount' || $k == 'approved_change_order_amount' || $k == 'retainage_amount_previously_withheld' || $k == 'previously_billed_amount' || $k == 'current_billed_amount' || $k == 'total_billed' || $k == 'retainage_amount_for_this_draw' || $k == 'total_outstanding_retainage')
                    @php $readonly='readonly'; @endphp
                    @endif


                    @if($k == 'stored_materials' || $k == 'net_billed_amount' || $k == 'current_contract_amount' || $k == 'original_contract_amount' || $k == 'approved_change_order_amount' || $k == 'previously_billed_percent' || $k == 'current_billed_amount' || $k == 'total_billed' || $k == 'retainage_amount_for_this_draw' || $k == 'total_outstanding_retainage' || $k == 'previously_billed_amount' || $k == 'current_billed_percent' || $k == 'retainage_percent' || $k == 'retainage_amount_previously_withheld' || $k == 'retainage_release_amount')
                    @php $value= ($value=='')?0:$value;
                    $value=str_replace(',','',$value);
                    @endphp

                    @if($dp->bill_type=='Calculated')
                    @php $readonly='readonly'; @endphp
                    @php $calculated=1; @endphp
                    <label class="pull-right row-label" id="{{$k}}_lb{{$int}}">{{number_format($value,2)}}</label>
                    <input type="hidden" name="{{$k}}[]" id="{{$k}}{{$int}}" value="{{number_format($value,2)}}">

                    @else
                    <input type="text" numbercom="yes" onkeyup="updateTextView($(this));" {{$readonly}} onblur="calculateConstruction();" name="{{$k}}[]" data-cy="particular_{{$k}}{{$int}}" id="{{$k}}{{$int}}" class="form-control " value="{{number_format($value,2)}}">
                    @endif

                    @else
                    @if($k=='bill_code')
                    @if($dp->bill_type=='Calculated')
                    <label class="row-label" id="{{$k}}_lb{{$int}}">{{$dp->bill_code}}</label><br>
                    <input type="hidden" name="{{$k}}[]" id="{{$k}}{{$int}}" value="{{$dp->bill_code}}">
                    @else
                   <div style="display:flex;">
                    <select required style="width: 100%; min-width: 200px;"  onchange="billCode(this.value, {{$int}});" data-cy="particular_item{{$int}}" id="bill_code{{$int}}" name="bill_code[]" data-placeholder="Type or Select" class="form-control  select2me productselect pull-left">
                        <option value="">Select Code</option>
                        @if(!empty($csi_code))
                        @php $excode=0; @endphp
                        @foreach($csi_code as $pk=>$vk)
                        @if($dp->{$k}==$vk->code)
                        @php $excode=1; @endphp
                        <option selected="" value="{{$vk->code}}">{{$vk->code}} {{$vk->title}}</option>
                        @else
                        <option value="{{$vk->code}}">{{$vk->code}} {{$vk->title}}</option>
                        @endif
                        @endforeach
                        @if($excode==0)
                        <option selected="" value="{{$dp->bill_code}}">{{$dp->bill_code}}</option>
                        @endif
                        @endif

                    </select>
                    <input type="hidden" name="attach[]" id="attach-{{$int}}" >
                    <a onclick="showupdatebillcodeattachment('{{$int}}');" style="align-self: center; margin-left: 3px;" class="pull-right">
                    <i id="icon-{{$int}}" class="fa fa-paperclip popovers" data-placement="right" data-container="body" data-trigger="hover"  data-content="0 file"  aria-hidden="true"></i> </a>
                  
                </div>
                    <div class="text-center">

                        <p id="description{{$int}}" class="lable-heading">
                            {{$dp->description}}
                        </p>
                    </div>
                    @endif
                    <input type="hidden" id="description-hidden{{$int}}" name="description[]" value="{{$dp->description}}">
                    @elseif($k=='bill_type')


                    @if($dp->bill_type=='Calculated')
                    <label class="row-label" id="{{$k}}_lb{{$int}}" style="text-align:center;">{{$dp->bill_type}}</label>
                    <i id="exicon{{$int}}" style="display: none;" class="fa fa-exclamation-circle popovers" data-placement="top" data-container="body" data-trigger="hover" data-content="Default contract calculation has been changed" data-original-title=""></i><br>
                    <div class="pull-right">
                        <input type="hidden" name="{{$k}}[]" id="{{$k}}{{$int}}" value="{{$dp->bill_type}}">
                        <span style="display: none; color: rgb(133, 148, 148);" id="pipe-calc{{$int}}"> | </span>

                        <a id="edit-calc{{$int}}" style="display: inline-block; padding-top: 5px; padding-left: 5px;" href="javascript:;" onclick="editCaculatedRow('{{$int}}')">Override</a>
                        <a id="add-calc{{$int}}" style="display:none; padding-top:5px;" href="javascript:;" onclick="OpenAddCaculatedRow('{{$int}}')">Add calculation</a>
                        <a id="remove-calc{{$int}}" style="display:none;padding-top:5px;float: left; margin-right: 5px;" href="javascript:;" onclick="RemoveCaculatedRow('{{$int}}')">Remove</a>

                    </div>
                    @else
                    <select required style="width: 100%; min-width: 200px;" onchange="addCaculatedRow(this.value,{{$int}});" id="bill_type{{$int}}" name="bill_type[]" data-placeholder="Type or Select Bill Type" class="form-control ">
                        <option @if($dp->{$k} == 0) @endif value="0">Select Type</option>
                        <option @if($dp->{$k} == '% Complete') selected @endif value="% Complete">% Complete</option>
                        <option @if($dp->{$k} == 'Unit') selected @endif value="Unit">Unit</option>
                        <option @if($dp->{$k} == 'Calculated') selected @endif value="Calculated">Calculated</option>
                    </select>
                    <a id="add-calc{{$int}}" style="display:none; padding-top:5px;" href="javascript:;" onclick="OpenAddCaculatedRow('{{$int}}')">Add calculation</a>
                    <a id="remove-calc{{$int}}" style="display:none;padding-top:5px;float: left; margin-right: 10px;" href="javascript:;" onclick="RemoveCaculatedRow('{{$int}}')">Remove</a>
                    <span style="display: none; margin-left: 4px; color: rgb(133, 148, 148);" id="pipe-calc{{$int}}"> | </span>
                    <a id="edit-calc{{$int}}" style="display:none;padding-top:5px;" href="javascript:;" onclick="editCaculatedRow('{{$int}}')">Edit</a>

                    @endif

                    @elseif($k=='group')
                    <label class="row-label" id="{{$k}}_lb{{$int}}" style="text-align:center;">{{$dp->group}}</label>
                    <input type="hidden" name="{{$k}}[]" id="{{$k}}{{$int}}" value="{{$dp->group}}">
                    @elseif($k=='bill_code_detail')
                    <label class="row-label" id="{{$k}}_lb{{$int}}" style="text-align:center;">{{$dp->bill_code_detail}}</label>
                    <input type="hidden" name="{{$k}}[]" id="{{$k}}{{$int}}" value="{{$dp->bill_code_detail}}">
                    @else

                    @if($dp->bill_type=='Calculated')
                    <label class="row-label" id="{{$k}}_lb{{$int}}">@isset($dp->$k){{$dp->$k}}@endisset</label><br>
                    <input type="hidden" name="{{$k}}[]" id="{{$k}}{{$int}}" value="@isset($dp->$k){{$dp->$k}}@endisset">
                    @else
                    <input type="text" onblur="calculateConstruction();" {{$readonly}} name="{{$k}}[]" data-cy="particular_{{$k}}{{$int}}" id="{{$k}}{{$int}}" class="form-control " value="{{$value}}">
                    @endif
                    @endif
                    @endif
                    @if($k == 'qty')
                    <span id="qtyavailable{{$int}}" class="help-block "></span>
                    @endif

                </td>
                @endif

                @endforeach
                <td class="td-c">
                    <input type="hidden" id="pint{{$int}}" name="pint[]" value="{{$int}}">
                    <input type="hidden" name="particular_id[]" value="@isset($dp->id){{$dp->id}}@endisset">
                    <input type="hidden" id="calculated_perc{{$int}}" name="calculated_perc[]" value="{{$dp->calculated_perc}}">
                    <input type="hidden" id="calculated_row{{$int}}" name="calculated_row[]" value="{{$dp->calculated_row}}">
                    <a data-cy="particular-remove{{$int}}" href="javascript:;" onclick="$(this).closest('tr').remove();
                            calculateConstruction();" class="btn btn-sm red"> <i class="fa fa-times"> </i> </a>
                </td>
            </tr>
            @php if($nopint==1){ $int++;} @endphp
            
            @endforeach
            @endif
        </tbody>

        @endif
    </table>
</div>
<input type="hidden" name="contract_id" value="{{$contract_id}}">
<script>
    groups = JSON.parse('{!!$group!!}');
</script>
@endif